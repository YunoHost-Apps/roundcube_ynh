

# =============================================================================
# COMMON VARIABLES
# =============================================================================

# Package dependencies
pkg_dependencies="php5-cli php5-common php5-intl php5-json php5-mcrypt php-pear php-auth-sasl php-mail-mime php-patchwork-utf8 php-net-smtp php-net-socket php-crypt-gpg php-net-ldap2 php-net-ldap3"

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

# =============================================================================
#                     YUNOHOST 2.7 FORTHCOMING HELPERS
# =============================================================================

# Create a dedicated nginx config
#
# This will use a template in ../conf/nginx.conf
#   __PATH__      by  $path_url
#   __DOMAIN__    by  $domain
#   __PORT__      by  $port
#   __NAME__      by  $app
#   __FINALPATH__ by  $final_path
#
# usage: ynh_add_nginx_config
ynh_add_nginx_config () {
	finalnginxconf="/etc/nginx/conf.d/$domain.d/$app.conf"
	ynh_backup_if_checksum_is_different "$finalnginxconf"
	sudo cp ../conf/nginx.conf "$finalnginxconf"

	# To avoid a break by set -u, use a void substitution ${var:-}. If the variable is not set, it's simply set with an empty variable.
	# Substitute in a nginx config file only if the variable is not empty
	if test -n "${path_url:-}"; then
		ynh_replace_string "__PATH__" "$path_url" "$finalnginxconf"
	fi
	if test -n "${domain:-}"; then
		ynh_replace_string "__DOMAIN__" "$domain" "$finalnginxconf"
	fi
	if test -n "${port:-}"; then
		ynh_replace_string "__PORT__" "$port" "$finalnginxconf"
	fi
	if test -n "${app:-}"; then
		ynh_replace_string "__NAME__" "$app" "$finalnginxconf"
	fi
	if test -n "${final_path:-}"; then
		ynh_replace_string "__FINALPATH__" "$final_path" "$finalnginxconf"
	fi
	ynh_store_file_checksum "$finalnginxconf"

	sudo systemctl reload nginx
}

# Remove the dedicated nginx config
#
# usage: ynh_remove_nginx_config
ynh_remove_nginx_config () {
	ynh_secure_remove "/etc/nginx/conf.d/$domain.d/$app.conf"
	sudo systemctl reload nginx
}

# Create a dedicated php-fpm config
#
# usage: ynh_add_fpm_config
ynh_add_fpm_config () {
	finalphpconf="/etc/php5/fpm/pool.d/$app.conf"
	ynh_backup_if_checksum_is_different "$finalphpconf"
	sudo cp ../conf/php-fpm.conf "$finalphpconf"
	ynh_replace_string "__NAMETOCHANGE__" "$app" "$finalphpconf"
	ynh_replace_string "__FINALPATH__" "$final_path" "$finalphpconf"
	ynh_replace_string "__USER__" "$app" "$finalphpconf"
	sudo chown root: "$finalphpconf"
	ynh_store_file_checksum "$finalphpconf"

	if [ -e "../conf/php-fpm.ini" ]
	then
		finalphpini="/etc/php5/fpm/conf.d/20-$app.ini"
		ynh_backup_if_checksum_is_different "$finalphpini"
		sudo cp ../conf/php-fpm.ini "$finalphpini"
		sudo chown root: "$finalphpini"
		ynh_store_file_checksum "$finalphpini"
	fi

	sudo systemctl reload php5-fpm
}

# Remove the dedicated php-fpm config
#
# usage: ynh_remove_fpm_config
ynh_remove_fpm_config () {
	ynh_secure_remove "/etc/php5/fpm/pool.d/$app.conf"
	ynh_secure_remove "/etc/php5/fpm/conf.d/20-$app.ini" 2>&1
	sudo systemctl reload php5-fpm
}

# Restore a previous backup if the upgrade process failed
#
# usage:
# ynh_backup_before_upgrade
# ynh_clean_setup () {
# 	ynh_restore_upgradebackup
# }
# ynh_abort_if_errors
#
ynh_restore_upgradebackup () {
	echo "Upgrade failed." >&2
	app_bck=${app//_/-}	# Replace all '_' by '-'
	
        # Check if an existing backup can be found before removing and restoring the application.
	if sudo yunohost backup list | grep -q $app_bck-pre-upgrade$backup_number
    	then
		# Remove the application then restore it
		sudo yunohost app remove $app
		# Restore the backup
		sudo yunohost backup restore --ignore-system $app_bck-pre-upgrade$backup_number --apps $app --force
		ynh_die "The app was restored to the way it was before the failed upgrade."
	fi
}

# Make a backup in case of failed upgrade
#
# usage:
# ynh_backup_before_upgrade
# ynh_clean_setup () {
# 	ynh_restore_upgradebackup
# }
# ynh_abort_if_errors
#
ynh_backup_before_upgrade () {
	backup_number=1
	old_backup_number=2
	app_bck=${app//_/-}	# Replace all '_' by '-'
	
    	# Check if a backup already exists with the prefix 1
	if sudo yunohost backup list | grep -q $app_bck-pre-upgrade1
    	then
        	# Prefix becomes 2 to preserve the previous backup
		backup_number=2
		old_backup_number=1
	fi

	# Create backup
	sudo yunohost backup create --ignore-system --apps $app --name $app_bck-pre-upgrade$backup_number
	if [ "$?" -eq 0 ]
    	then
		# If the backup succeeded, remove the previous backup
		if sudo yunohost backup list | grep -q $app_bck-pre-upgrade$old_backup_number
		then
			# Remove the previous backup only if it exists
			sudo yunohost backup delete $app_bck-pre-upgrade$old_backup_number > /dev/null
		fi
	else
		ynh_die "Backup failed, the upgrade process was aborted."
	fi
}
