#
# Common variables
#

# Roundcube version
VERSION=1.1.5

# Roundcube complete tarball checksum
ROUNDCUBE_SOURCE_SHA256="ed50384c5ca0bcd9df08e1d0f2a46f2e7f468f583bcf410709f0a0659e00c453"

# Remote URL to fetch Roundcube source tarball
ROUNDCUBE_SOURCE_URL="https://github.com/roundcube/roundcubemail/releases/download/${VERSION}/roundcubemail-${VERSION}.tar.gz"

# App package root directory should be the parent folder
PKGDIR=$(cd ../; pwd)

#
# Common helpers
#

# Download and extract Roundcube sources to the given directory
# usage: extract_roundcube_to DESTDIR
extract_roundcube() {
  local DESTDIR=$1

  # retrieve and extract Roundcube tarball
  rc_tarball="${DESTDIR}/roundcube.tar.gz"
  wget -q -O "$rc_tarball" "$ROUNDCUBE_SOURCE_URL" \
    || ynh_die "Unable to download Roundcube tarball"
  echo "$ROUNDCUBE_SOURCE_SHA256 $rc_tarball" | sha256sum -c >/dev/null \
    || ynh_die "Invalid checksum of downloaded tarball"
  tar xf "$rc_tarball" -C "$DESTDIR" --strip-components 1 \
    || ynh_die "Unable to extract Roundcube tarball"
  rm "$rc_tarball"

  # apply patches
  (cd "$DESTDIR" \
   && for p in ${PKGDIR}/patches/*.patch; do patch -p1 < $p; done) \
    || ynh_die "Unable to apply patches to Roundcube"

  # copy composer.json-dist for Roundcube with complete dependencies
  cp "${PKGDIR}/sources/composer.json-dist" "${DESTDIR}/composer.json-dist"
}

# Execute a command as another user
# usage: exec_as USER COMMAND [ARG ...]
exec_as() {
  local USER=$1
  shift 1

  if [[ $USER = $(whoami) ]]; then
    eval $@
  else
    # use sudo twice to be root and be allowed to use another user
    sudo sudo -u "$USER" $@
  fi
}

# Execute a composer command from a given directory
# usage: composer_exec AS_USER WORKDIR COMMAND [ARG ...]
exec_composer() {
  local AS_USER=$1
  local WORKDIR=$2
  shift 2

  exec_as "$AS_USER" COMPOSER_HOME="${WORKDIR}/.composer" \
    php "${WORKDIR}/composer.phar" $@ \
      -d "${WORKDIR}" --quiet --no-interaction
}

# Install and initialize Composer in the given directory
# usage: init_composer DESTDIR [AS_USER]
init_composer() {
  local DESTDIR=$1
  local AS_USER=${2:-admin}

  # install composer
  curl -sS https://getcomposer.org/installer \
    | exec_as "$AS_USER" COMPOSER_HOME="${DESTDIR}/.composer" \
        php -- --quiet --install-dir="$DESTDIR" \
    || ynh_die "Unable to install Composer"

  # install composer.json
  exec_as "$AS_USER" \
    cp "${DESTDIR}/composer.json-dist" "${DESTDIR}/composer.json"

  # update dependencies to create composer.lock
  exec_composer "$AS_USER" "$DESTDIR" install --no-dev \
    || ynh_die "Unable to update Roundcube core dependencies"
}
