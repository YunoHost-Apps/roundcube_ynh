<!--
N.B.: This README was automatically generated by https://github.com/YunoHost/apps/tree/master/tools/README-generator
It shall NOT be edited by hand.
-->

# Roundcube for YunoHost

[![Integration level](https://dash.yunohost.org/integration/roundcube.svg)](https://dash.yunohost.org/appci/app/roundcube) ![Working status](https://ci-apps.yunohost.org/ci/badges/roundcube.status.svg) ![Maintenance status](https://ci-apps.yunohost.org/ci/badges/roundcube.maintain.svg)

[![Install Roundcube with YunoHost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=roundcube)

*[Lire ce readme en français.](./README_fr.md)*

> *This package allows you to install Roundcube quickly and simply on a YunoHost server.
If you don't have YunoHost, please consult [the guide](https://yunohost.org/#/install) to learn how to install it.*

## Overview

Roundcube is a browser-based multilingual IMAP client with an application-like user interface. It provides full functionality you expect from an email client, including MIME support, address book, folder manipulation, message searching and spell checking.

## YunoHost specific features

In addition to Roundcube core features, the following are made available with this package:

 * Synchronize your email aliases as identities in Roundcube
 * Install the [contextmenu](https://packagist.org/packages/johndoh/contextmenu) and [automatic addressbook](https://packagist.org/packages/projectmyst/automatic_addressbook) plugins by default
 * Allow to install the [CardDAV](https://packagist.org/packages/roundcube/carddav) (address book) synchronization plugin at the installation - note that if you have installed Nextcloud or Baïkal, it will automatically add the corresponding and existing address book.
* Support for PGP encryption with Enigma plugin by default.


**Shipped version:** 1.6.1~ynh1

**Demo:** https://demo.yunohost.org/webmail/

## Screenshots

![Screenshot of Roundcube](./doc/screenshots/screenshot.png)

## Disclaimers / important information

## Configuration

You can extend - or even override - the Roundcube configuration which is coming with this package in the file `conf/local.inc.php`. Do not edit the file `conf/config.inc.php` as future upgrades will overwrite it.

#### Multi-users support

* Integrate with YunoHost users and SSO - i.e logout button, YunoHost users search

#### Plugins

You can also install other plugins - which will not be removed with upgrades. To do so, you can use the official [Plugin Repository](https://plugins.roundcube.net/).

##### From the Plugin Repository

Let's say for example that we want to install the [html5_notifier](https://packagist.org/packages/kitist/html5_notifier) plugin.

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

## Documentation and resources

* Official app website: <https://roundcube.net/>
* Official admin documentation: <https://github.com/roundcube/roundcubemail/wiki>
* Upstream app code repository: <https://github.com/roundcube/roundcubemail>
* YunoHost documentation for this app: <https://yunohost.org/app_roundcube>
* Report a bug: <https://github.com/YunoHost-Apps/roundcube_ynh/issues>

## Developer info

Please send your pull request to the [testing branch](https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing).

To try the testing branch, please proceed like that.

``` bash
sudo yunohost app install https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
or
sudo yunohost app upgrade roundcube -u https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
```

**More info regarding app packaging:** <https://yunohost.org/packaging_apps>
