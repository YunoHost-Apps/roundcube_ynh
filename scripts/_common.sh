#!/bin/bash

#=================================================
# COMMON VARIABLES
#=================================================

# Composer version
YNH_COMPOSER_VERSION=2.5.5

# Plugins version
contextmenu_version=3.3.1
automatic_addressbook_version=v0.4.3
carddav_version=5.0.1

#=================================================
# DEFINE ALL COMMON FONCTIONS
#=================================================

install_roundcube_with_plugins() {

	#=================================================
	# INSTALL AND INITIALIZE COMPOSER
	#=================================================
	ynh_script_progression --message="Installing Roundcube with Composer..." --weight=30

	# Install composer.json
	cp "$install_dir/composer.json-dist" "$install_dir/composer.json"

	# Install composer
	ynh_install_composer

	#=================================================
	# CONFIGURE ROUNDCUBE
	#=================================================
	ynh_script_progression --message="Configuring Roundcube..." --weight=2

	deskey=$(ynh_string_random --length=24)
	ynh_add_config --template="../conf/config.inc.php" --destination="$install_dir/config/config.inc.php"

	#=================================================
	# INSTALL ADDITIONAL PLUGINS
	#=================================================
	ynh_script_progression --message="Installing additional plugins..." --weight=60

	# Create logs and temp directories
	mkdir -p "$install_dir/"{logs,temp}

	# Install net_LDAP
	export COMPOSER_ALLOW_SUPERUSER=1
	ynh_composer_exec --commands="require kolab/net_ldap3"

	# Install contextmenu and automatic_addressbook plugins
	# https://plugins.roundcube.net/packages/sblaisot/automatic_addressbook
	# https://plugins.roundcube.net/packages/johndoh/contextmenu
	ynh_composer_exec --commands="require \
	    johndoh/contextmenu $contextmenu_version \
	    sblaisot/automatic_addressbook $automatic_addressbook_version"

	installed_plugins+=" 'contextmenu', 'automatic_addressbook',"

	ynh_add_config --template="../conf/enigma.config.inc.php" --destination="$install_dir/plugins/enigma/config.inc.php"
	mkdir -p "$install_dir/plugins/enigma/home"
	chown -R $app:www-data "$install_dir/plugins/enigma/home"

	# Install CardDAV plugin
	if [ $with_carddav -eq 1 ]
	then
	    ynh_composer_exec --commands="require roundcube/carddav $carddav_version --with-all-dependencies"

	    carddav_tmp_config="../conf/carddav.config.inc.php"
	    carddav_server=0

	    # Copy the plugin configuration file
	    cp $install_dir/plugins/carddav/config.inc.php{.dist,}

	    # Look for installed and supported CardDAV servers
	    for carddav_app in "nextcloud" "baikal"
	    do
		carddav_app_ids=$(yunohost app list | grep "id: $carddav_app" | grep -Po 'id: \K(.*)' || echo "")
		for carddav_app_id in $carddav_app_ids
		do
		    carddav_server=1
		    # Append preset configuration to the config file
		    cat "../conf/${carddav_app}.inc.php" >> $install_dir/plugins/carddav/config.inc.php
		    # Retrieve app settings and enable relevant preset
		    carddav_domain=$(ynh_app_setting_get --app=$carddav_app_id --key=domain)
		    carddav_path=$(ynh_app_setting_get --app=$carddav_app_id --key=path)
		    carddav_url="https://${carddav_domain}${carddav_path%/}"
		    ynh_replace_string --match_string="{${carddav_app}_id}" --replace_string="$carddav_app_id" --target_file="$install_dir/plugins/carddav/config.inc.php"
		    ynh_replace_string --match_string="{${carddav_app}_url}" --replace_string="$carddav_url" --target_file="$install_dir/plugins/carddav/config.inc.php"
		done
	    done

	    # Do not actualy add the cardDAV plugin if there's no cardDAV server available...
	    if [ $carddav_server -eq 1 ]
	    then
		installed_plugins+=" 'carddav',"
	    fi
	fi

	#=================================================
	# UPDATE ROUNDCUBE CONFIGURATION
	#=================================================
	ynh_script_progression --message="Updating Roundcube configuration..." --weight=3

	ynh_replace_string --match_string="^\s*// installed plugins" --replace_string="&\n $installed_plugins" --target_file="$install_dir/config/config.inc.php"
	    
	# Store the config file checksum into the app settings
	ynh_store_file_checksum --file="$install_dir/config/config.inc.php"

	chmod 400 "$install_dir/config/config.inc.php"
	chown $app:$app "$install_dir/config/config.inc.php"

	# Update javascript dependencies
	(cd "$install_dir"
	  /usr/bin/php$phpversion -q ./bin/install-jsdeps.sh -v ?)
	    
}

