

# =============================================================================
# COMMON VARIABLES
# =============================================================================

# Package dependencies
pkg_dependencies="php5-cli php5-common php5-intl php5-json php5-mcrypt php-pear php-auth-sasl php-mail-mime php-patchwork-utf8 php-net-smtp php-net-socket php-net-ldap2 php-net-ldap3"
if [ "$(lsb_release --codename --short)" != "jessie" ]; then
  pkg_dependencies="$pkg_dependencies php-zip php-gd php-mbstring"
else
  pkg_dependencies="$pkg_dependencies php-crypt-gpg"
fi

# Plugins version
contextmenu_version=2.3
automatic_addressbook_version=v0.4.3
carddav_version=2.0.4

# =============================================================================
# COMMON ROUNDCUBE FUNCTIONS
# =============================================================================

# Execute a composer command from a given directory
# usage: composer_exec workdir COMMAND [ARG ...]
exec_composer() {
  local workdir=$1
  shift 1

  COMPOSER_HOME="${workdir}/.composer" \
    php "${workdir}/composer.phar" $@ \
      -d "${workdir}" --quiet --no-interaction
}

# Install and initialize Composer in the given directory
# usage: init_composer destdir
init_composer() {
  local destdir=$1

  # install composer
  curl -sS https://getcomposer.org/installer \
    | COMPOSER_HOME="${destdir}/.composer" \
        php -- --quiet --install-dir="$destdir" \
    || ynh_die "Unable to install Composer"

  # install composer.json
  cp "${destdir}/composer.json-dist" "${destdir}/composer.json"

  # update dependencies to create composer.lock
  exec_composer "$destdir" install --no-dev \
    || ynh_die "Unable to update Roundcube core dependencies"
}

# Install and configure CardDAV plugin for Roundcube
# usage: install_carddav destdir
# https://plugins.roundcube.net/packages/roundcube/carddav
install_carddav() {
  local destdir=$1

  local carddav_config="${destdir}/plugins/carddav/config.inc.php"
  local carddav_tmp_config="../conf/carddav.config.inc.php"

  exec_composer "$destdir" require \
      "roundcube/carddav $carddav_version"

  # Look for installed and supported CardDAV servers
  for carddav_app in "owncloud" "baikal"; do
    local app_id=$(yunohost app list --installed -f "$carddav_app" \
            --output-as json | grep -Po '"id":[ ]?"\K.*?(?=")' | head -1)
    [[ -z "$app_id" ]] || {
      # Retrieve app settings and enable relevant preset
      carddav_domain=$(ynh_app_setting_get "$app_id" domain)
      carddav_path=$(ynh_app_setting_get "$app_id" path)
      carddav_url="https://${carddav_domain}${carddav_path%/}"
      sed -i "s#{${carddav_app}_url}#${carddav_url}#g" "$carddav_tmp_config"
      sed -i \
"/\/\/\/\/ PRESET FOR: ${carddav_app}/\
,/\/\/\/\/ END: ${carddav_app}/s/^\/\///" "$carddav_tmp_config"
    }
  done

  # Copy plugin the configuration file
  cp "$carddav_tmp_config" "$carddav_config"
}
