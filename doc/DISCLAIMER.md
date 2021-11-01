## Configuration

You can extend - or even override - the Roundcube configuration which is coming with this package in the file `conf/local.inc.php`. Do not edit the file `conf/config.inc.php` as future upgrades will overwrite it.

#### Multi-users support

* Integrate with YunoHost users and SSO - i.e logout button, YunoHost users search

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
