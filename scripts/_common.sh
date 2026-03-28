#!/bin/bash

#=================================================
# COMMON VARIABLES AND CUSTOM HELPERS
#=================================================

timezone=$(timedatectl show --value --property=Timezone)

# Plugins version
contextmenu_version=3.3.1
carddav_version=5.1.2
