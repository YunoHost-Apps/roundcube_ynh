#!/bin/bash

#=================================================
# COMMON VARIABLES
#=================================================

YNH_PHP_VERSION="7.3"

# Package dependencies
extra_php_dependencies="php${YNH_PHP_VERSION}-cli php${YNH_PHP_VERSION}-common php${YNH_PHP_VERSION}-intl php${YNH_PHP_VERSION}-json php${YNH_PHP_VERSION}-pear php${YNH_PHP_VERSION}-auth-sasl php${YNH_PHP_VERSION}-mail-mime php${YNH_PHP_VERSION}-patchwork-utf8 php${YNH_PHP_VERSION}-net-smtp php${YNH_PHP_VERSION}-net-socket php${YNH_PHP_VERSION}-zip php${YNH_PHP_VERSION}-gd php${YNH_PHP_VERSION}-mbstring php${YNH_PHP_VERSION}-curl"

# Plugins version
contextmenu_version=2.3
automatic_addressbook_version=v0.4.3
carddav_version=3.0.3

#=================================================
# EXPERIMENTAL HELPERS
#=================================================

# Execute a command with Composer
#
# usage: ynh_composer_exec --phpversion=phpversion [--workdir=$final_path] --commands="commands"
# | arg: -w, --workdir - The directory from where the command will be executed. Default $final_path.
# | arg: -c, --commands - Commands to execute.
ynh_composer_exec () {
	# Declare an array to define the options of this helper.
	local legacy_args=vwc
	declare -Ar args_array=( [v]=phpversion= [w]=workdir= [c]=commands= )
	local phpversion
	local workdir
	local commands
	# Manage arguments with getopts
	ynh_handle_getopts_args "$@"
	workdir="${workdir:-$final_path}"
	phpversion="${phpversion:-7.3}"

	COMPOSER_HOME="$workdir/.composer" \
		php${phpversion} "$workdir/composer.phar" $commands \
		-d "$workdir" --quiet --no-interaction
}

# Install and initialize Composer in the given directory
#
# usage: ynh_install_composer --phpversion=phpversion [--workdir=$final_path]
# | arg: -w, --workdir - The directory from where the command will be executed. Default $final_path.
ynh_install_composer () {
	# Declare an array to define the options of this helper.
	local legacy_args=vw
	declare -Ar args_array=( [v]=phpversion= [w]=workdir= )
	local phpversion
	local workdir
	# Manage arguments with getopts
	ynh_handle_getopts_args "$@"
	workdir="${workdir:-$final_path}"
	phpversion="${phpversion:-7.3}"

	curl -sS https://getcomposer.org/installer \
		| COMPOSER_HOME="$workdir/.composer" \
		php${phpversion} -- --quiet --install-dir="$workdir" \
		|| ynh_die "Unable to install Composer."

	# update dependencies to create composer.lock
	ynh_composer_exec --phpversion="${phpversion}" --workdir="$workdir" --commands="install --no-dev" \
		|| ynh_die "Unable to update core dependencies with Composer."
}
