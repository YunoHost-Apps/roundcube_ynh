Roundcube for YunoHost
----------------------

*This is a work-in-progress package review to update Roundcube to 1.1.x
and make use of new YunoHost facilities - e.g. helpers - coming with 2.3.x.*

Roundcube is a browser-based multilingual IMAP client with an application-like
user interface.

## Installation

While it's merged to the official application list, you can install it in order
to try - or use it with caution! - either from the command line:

    $ sudo yunohost app install https://github.com/jeromelebleu/roundcube_ynh/tree/dev

or from the Web administration:

  * Go to *Applications*
  * Click on *Install*
  * Scroll to the bottom of the page and put `https://github.com/jeromelebleu/roundcube_ynh/tree/dev`
    under **Install custom app**.

## Extend and tweaks

### Configuration

You can extend - or even override - the Roundcube configuration which is coming
with this package in the file `conf/local.inc.php`. Do not edit the file
`conf/config.inc.php` as it will be overriden with upgrades.

### Plugins

You can also install other plugins - which will not removed with upgrades. To do so,
you can use the official [Plugin Repository](https://plugins.roundcube.net/).

#### From the Plugin Repository

Let's say for example that we want to install the
[html5_notifier](https://plugins.roundcube.net/packages/kitist/html5_notifier) plugin.

1. Connect to your server as root using SSH:
   ```
   $ ssh admin@1.2.3.4
   $ sudo -i
   ```

2. Log as the `www-data` user - which owns the roundcube directory - and navigate
   under it:
   ```
   # su -s /bin/bash - www-data
   $ cd /var/www/roundcube
   ```

3. Install the plugin you want using composer - note that you have to specify
   *kitist/html5_notifier* and not only *html5_notifier*:
   ```
   $ COMPOSER_HOME=./.composer php composer.phar require "kitist/html5_notifier"
   ```

4. Enable it in the local configuration file `conf/local.inc.php` using you're
   favorite text editor by adding:
   ```
   <?php
   $config['plugins'][] = 'html5_notifier';
   ```

Note that you should also check the plugin homepage for additional installation
steps as needed.

#### Download & extract

You can also download the plugin and put it under the `plugins/` directory. In that
case, do not forget to change ownerships to this folder to `www-data`.

## TODO

 * Ask for rcmcarddav installation and/or check if *Baikal* or *ownCloud*
   is installed
 * Move log file outside of the public folder or protect it at least
 * ...

## Links ##

**Roundcube**: https://roundcube.net/

**YunoHost**: https://yunohost.org/
