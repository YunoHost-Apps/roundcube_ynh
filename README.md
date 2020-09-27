# Roundcube for YunoHost

[![Integration level](https://dash.yunohost.org/integration/roundcube.svg)](https://dash.yunohost.org/appci/app/roundcube) ![](https://ci-apps.yunohost.org/ci/badges/roundcube.status.svg) ![](https://ci-apps.yunohost.org/ci/badges/roundcube.maintain.svg)  
[![Install Roundcube with YunoHost](https://install-app.yunohost.org/install-with-yunohost.png)](https://install-app.yunohost.org/?app=roundcube)

*[Read this readme in english.](./README.md)* 

> *This package allow you to install Roundcube quickly and simply on a YunoHost server.
If you don't have YunoHost, please see [here](https://yunohost.org/#/install) to know how to install and enjoy it.*

## Overview
[Roundcube](https://roundcube.net/) is a browser-based multilingual IMAP client with an application-like user interface.

**Shipped version:** 1.4.9

## Screenshots

![](https://roundcube.net/screens/skins/elastic/desktop/screens/mailbox_widescreen.png)

## Demo

* [YunoHost demo](https://demo.yunohost.org/webmail/)

## Configuration

You can extend - or even override - the Roundcube configuration which is coming with this package in the file `conf/local.inc.php`. Do not edit the file `conf/config.inc.php` as future upgrades will overwrite it.

## Documentation

 * Official documentation: https://github.com/roundcube/roundcubemail/wiki
 * YunoHost documentation: https://github.com/YunoHost/doc/blob/master/app_roundcube.md:

## YunoHost specific features

In addition to Roundcube core features, the following are made available with this package:

 * Synchronize your email aliases as identities in Roundcube
 * Install the [contextmenu](https://plugins.roundcube.net/packages/johndoh/contextmenu) and [automatic addressbook](https://plugins.roundcube.net/packages/sblaisot/automatic_addressbook) plugins by default
 * Allow to install the [CardDAV](https://plugins.roundcube.net/packages/roundcube/carddav) (address book) synchronization plugin at the installation - note that if you have installed Nextcloud or Baïkal, it will automatically add the corresponding and existing address book.

#### Multi-users support
* Integrate with YunoHost users and SSO - i.e logout button, YunoHost users search

#### Supported architectures

* x86-64 - [![Build Status](https://ci-apps.yunohost.org/ci/logs/roundcube%20%28Apps%29.svg)](https://ci-apps.yunohost.org/ci/apps/roundcube/)
* ARMv8-A - [![Build Status](https://ci-apps-arm.yunohost.org/ci/logs/roundcube%20%28Apps%29.svg)](https://ci-apps-arm.yunohost.org/ci/apps/roundcube/)

## Limitations

* No known limitations.

## Additional information

#### Plugins

You can also install other plugins - which will not be removed with upgrades. To do so, you can use the official [Plugin Repository](https://plugins.roundcube.net/).

##### From the Plugin Repository

Let's say for example that we want to install the [html5_notifier](https://plugins.roundcube.net/packages/kitist/html5_notifier) plugin.

1. Connect to your server as root using SSH:
   ```
   $ ssh admin@1.2.3.4
   $ sudo -i
   ```

2. Log in as the `roundcube` user - which owns the roundcube directory - and navigate in it:
   ```
   # su -s /bin/bash - roundcube
   $ cd /var/www/roundcube
   ```

3. Install the plugin you want using composer - note that you have to specify *kitist/html5_notifier* and not only *html5_notifier*:
   ```
   $ COMPOSER_HOME=./.composer php composer.phar require "kitist/html5_notifier"
   ```

4. Enable it in the local configuration file `conf/local.inc.php` using your favorite text editor by adding:
   ```
   <?php
   $config['plugins'][] = 'html5_notifier';
   ```

Note that you should also check the plugin homepage for additional installation steps as needed.

##### Manual installation

You can also download the plugin and put it under the `plugins/` directory. In this case, do not forget to change ownerships of this folder to `roundcube`.

## Links

 * Report a bug: https://github.com/YunoHost-Apps/roundcube_ynh/issues
 * Roundcube website: https://roundcube.net/
 * Roundcube repository: https://github.com/roundcube/roundcubemail
 * YunoHost website: https://yunohost.org/

---

## Developers info

Please do your pull request to the [testing branch](https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing).

To try the testing branch, please proceed like that.
```
sudo yunohost app install https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
or
sudo yunohost app upgrade roundcube -u https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
```
