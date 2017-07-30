#
# Common variables
#

# Package dependencies
PKG_DEPENDENCIES="php5-cli php5-common php5-intl php5-json php5-mcrypt php-pear php-auth-sasl php-mail-mime php-patchwork-utf8 php-net-smtp php-net-socket php-crypt-gpg php-net-ldap2 php-net-ldap3"

# App package root directory should be the parent folder
PKGDIR=$(cd ../; pwd)

QUIET () {  # Redirect standard out into /dev/null
  $@ > /dev/null
}

WARNING () {  # Writes on the error channel to go into warning.
  eval "$@" >&2
}

#
# Common helpers
#

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

# Install and configure CardDAV plugin for Roundcube
# usage: install_carddav DESTDIR [AS_USER]
install_carddav() {
  local DESTDIR=$1
  local AS_USER=${2:-admin}

  local carddav_config="${DESTDIR}/plugins/carddav/config.inc.php"
  local carddav_tmp_config="${PKGDIR}/conf/carddav.config.inc.php"

  exec_composer "$AS_USER" "$DESTDIR" require \
      "roundcube/carddav dev-master"

  # Look for installed and supported CardDAV servers
  for carddav_app in "owncloud" "baikal"; do
    local app_id=$(sudo yunohost app list --installed -f "$carddav_app" \
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
  sudo cp "$carddav_tmp_config" "$carddav_config"
  sudo chown "${AS_USER}:" "$carddav_config"
}

# Normalize the url path syntax
# Handle the slash at the beginning of path and its absence at ending
# Return a normalized url path
#
# example: url_path=$(ynh_normalize_url_path $url_path)
#          ynh_normalize_url_path example -> /example
#          ynh_normalize_url_path /example -> /example
#          ynh_normalize_url_path /example/ -> /example
#          ynh_normalize_url_path / -> /
#
# usage: ynh_normalize_url_path path_to_normalize
# | arg: url_path_to_normalize - URL path to normalize before using it
ynh_normalize_url_path () {
  path_url=$1
  test -n "$path_url" || ynh_die "ynh_normalize_url_path expect a URL path as first argument and received nothing."
  if [ "${path_url:0:1}" != "/" ]; then    # If the first character is not a /
    path_url="/$path_url"    # Add / at begin of path variable
  fi
  if [ "${path_url:${#path_url}-1}" == "/" ] && [ ${#path_url} -gt 1 ]; then    # If the last character is a / and that not the only character.
    path_url="${path_url:0:${#path_url}-1}" # Delete the last character
  fi
  echo $path_url
}


# Add config nginx
ynh_nginx_config () {
  finalnginxconf="/etc/nginx/conf.d/$domain.d/$app.conf"
  ynh_compare_checksum_config "$finalnginxconf" 1
  sudo cp ../conf/nginx.conf "$finalnginxconf"

  # To avoid a break by set -u, use a void substitution ${var:-}. If the variable is not set, it's simply set with an empty variable.
  # Substitute in a nginx config file only if the variable is not empty
  if test -n "${path:-}"; then
    ynh_substitute_char "__PATH__" "$path" "$finalnginxconf"
  fi
  if test -n "${domain:-}"; then
    ynh_substitute_char "__DOMAIN__" "$domain" "$finalnginxconf"
  fi
  if test -n "${port:-}"; then
    ynh_substitute_char "__PORT__" "$port" "$finalnginxconf"
  fi
  if test -n "${app:-}"; then
    ynh_substitute_char "__NAME__" "$app" "$finalnginxconf"
  fi
  if test -n "${final_path:-}"; then
    ynh_substitute_char "__FINALPATH__" "$final_path" "$finalnginxconf"
  fi
  ynh_store_checksum_config "$finalnginxconf"

  sudo systemctl reload nginx
}

# Remove config nginx
ynh_remove_nginx_config () {
  ynh_secure_remove "/etc/nginx/conf.d/$domain.d/$app.conf"
  sudo systemctl reload nginx
}

ynh_fpm_config () {
  finalphpconf="/etc/php5/fpm/pool.d/$app.conf"
  ynh_compare_checksum_config "$finalphpconf" 1
  sudo cp ../conf/php-fpm.conf "$finalphpconf"
  ynh_substitute_char "__NAMETOCHANGE__" "$app" "$finalphpconf"
  ynh_substitute_char "__FINALPATH__" "$final_path" "$finalphpconf"
  ynh_substitute_char "__USER__" "$app" "$finalphpconf"
  sudo chown root: "$finalphpconf"
  ynh_store_checksum_config "$finalphpconf"

  if [ -e "../conf/php-fpm.ini" ]
  then
    finalphpini="/etc/php5/fpm/conf.d/20-$app.ini"
    ynh_compare_checksum_config "$finalphpini" 1
    sudo cp ../conf/php-fpm.ini "$finalphpini"
    sudo chown root: "$finalphpini"
    ynh_store_checksum_config "$finalphpini"
  fi

  sudo systemctl reload php5-fpm
}

ynh_remove_fpm_config () {
  ynh_secure_remove "/etc/php5/fpm/pool.d/$app.conf"
  ynh_secure_remove "/etc/php5/fpm/conf.d/20-$app.ini"
  sudo systemctl reload php5-fpm
}

ynh_compare_checksum_config () {
  current_config_file=$1
  compress_backup=${2:-0} # If $2 is empty, compress_backup will set at 0
  config_file_checksum=checksum_${current_config_file//[\/ ]/_} # Replace all '/' and ' ' by '_'
  checksum_value=$(ynh_app_setting_get $app $config_file_checksum)
  if [ -n "$checksum_value" ]
  then  # Proceed only if a value was stocked into the app config
    if ! echo "$checksum_value $current_config_file" | md5sum -c --status
    then  # If the checksum is now different
      backup_config_file="$current_config_file.backup.$(date '+%d.%m.%y_%Hh%M,%Ss')"
      if [ compress_backup -eq 1 ]
      then
        sudo tar --create --gzip --file "$backup_config_file.tar.gz" "$current_config_file" # Backup the current config file and compress
        backup_config_file="$backup_config_file.tar.gz"
      else
        sudo cp -a "$current_config_file" "$backup_config_file" # Backup the current config file
      fi
      echo "Config file $current_config_file has been manually modified since the installation or last upgrade. So it has been duplicated in $backup_config_file" >&2
      echo "$backup_config_file"  # Return the name of the backup file
    fi
  fi
}

# Substitute a string by another in a file
#
# usage: ynh_substitute_char string_to_find replace_string file_to_analyse
# | arg: string_to_find - String to replace in the file
# | arg: replace_string - New string that will replace
# | arg: file_to_analyse - File where the string will be replaced.
ynh_substitute_char () {
  delimit=@
  match_char=${1//${delimit}/"\\${delimit}"}  # Escape the delimiter if it's in the string.
  replace_char=${2//${delimit}/"\\${delimit}"}
  workfile=$3

  sudo sed --in-place "s${delimit}${match_char}${delimit}${replace_char}${delimit}g" "$workfile"
}

ynh_store_checksum_config () {
  config_file_checksum=checksum_${1//[\/ ]/_} # Replace all '/' and ' ' by '_'
  ynh_app_setting_set $app $config_file_checksum $(sudo md5sum "$1" | cut -d' ' -f1)
}

ynh_backup_fail_upgrade () {
  WARNING echo "Upgrade failed."
  app_bck=${app//_/-} # Replace all '_' by '-'
  if sudo yunohost backup list | grep -q $app_bck-pre-upgrade$backup_number; then # Vérifie l'existence de l'archive avant de supprimer l'application et de restaurer
    sudo yunohost app remove $app # Supprime l'application avant de la restaurer.
    sudo yunohost backup restore --ignore-hooks $app_bck-pre-upgrade$backup_number --apps $app --force  # Restore the backup if upgrade failed
    ynh_die "The app was restored to the way it was before the failed upgrade."
  fi
}

ynh_backup_before_upgrade () {  # Backup the current version of the app, restore it if the upgrade fails
  backup_number=1
  old_backup_number=2
  app_bck=${app//_/-} # Replace all '_' by '-'
  if sudo yunohost backup list | grep -q $app_bck-pre-upgrade1; then  # Vérifie l'existence d'une archive déjà numéroté à 1.
    backup_number=2 # Et passe le numéro de l'archive à 2
    old_backup_number=1
  fi

  sudo yunohost backup create --ignore-hooks --apps $app --name $app_bck-pre-upgrade$backup_number  # Créer un backup différent de celui existant.
  if [ "$?" -eq 0 ]; then # Si le backup est un succès, supprime l'archive précédente.
    if sudo yunohost backup list | grep -q $app_bck-pre-upgrade$old_backup_number; then # Vérifie l'existence de l'ancienne archive avant de la supprimer, pour éviter une erreur.
      QUIET sudo yunohost backup delete $app_bck-pre-upgrade$old_backup_number
    fi
  else  # Si le backup a échoué
    ynh_die "Backup failed, the upgrade process was aborted."
  fi
}


# Manage a fail of the script
#
# Print a warning to inform that the script was failed
# Execute the ynh_clean_setup function if used in the app script
#
# usage of ynh_clean_setup function
# This function provide a way to clean some residual of installation that not managed by remove script.
# To use it, simply add in your script:
# ynh_clean_setup () {
#        instructions...
# }
# This function is optionnal.
#
# Usage: ynh_exit_properly is used only by the helper ynh_check_error.
# You must not use it directly.
ynh_exit_properly () {
  exit_code=$?
  if [ "$exit_code" -eq 0 ]; then
      ynh_die # Exit without error if the script ended correctly
  fi

  trap '' EXIT  # Ignore new exit signals
  set +eu # Do not exit anymore if a command fail or if a variable is empty

  echo -e "!!\n  $app's script has encountered an error. Its execution was cancelled.\n!!" >&2

  if type -t ynh_clean_setup > /dev/null; then  # Check if the function exist in the app script.
    ynh_clean_setup # Call the function to do specific cleaning for the app.
  fi

  ynh_die # Exit with error status
}

# Exit if an error occurs during the execution of the script.
#
# Stop immediatly the execution if an error occured or if a empty variable is used.
# The execution of the script is derivate to ynh_exit_properly function before exit.
#
# Usage: ynh_abort_if_errors
ynh_abort_if_errors () {
  set -eu # Exit if a command fail, and if a variable is used unset.
  trap ynh_exit_properly EXIT # Capturing exit signals on shell script
}