# Roundcube pour YunoHost

[![Integration level](https://dash.yunohost.org/integration/roundcube.svg)](https://dash.yunohost.org/appci/app/roundcube) ![](https://ci-apps.yunohost.org/ci/badges/roundcube.status.svg) ![](https://ci-apps.yunohost.org/ci/badges/roundcube.maintain.svg)  
[![Installer Roundcube avec YunoHost](https://install-app.yunohost.org/install-with-yunohost.png)](https://install-app.yunohost.org/?app=roundcube)

*[Read this readme in english.](./README.md)* 

> *Ce package vous permet d'installer Roundcube rapidement et simplement sur un serveur YunoHost.  
Si vous n'avez pas YunoHost, consultez [le guide](https://yunohost.org/#/install) pour apprendre comment l'installer.*

## Overview
[Roundcube](https://roundcube.net/) est un client IMAP multilingue basé sur un navigateur avec une interface utilisateur semblable à une application.

**Shipped version:** 1.4.9

## Screenshots

![](https://roundcube.net/screens/skins/elastic/desktop/screens/mailbox_widescreen.png)

## Démo

* [Démo YunoHost](https://demo.yunohost.org/webmail/)

## Configuration

Vous pouvez étendre (ou même remplacer) la configuration de Roundcube fournie avec ce paquet dans le fichier `conf/local.inc.php`. Ne modifiez pas le fichier `conf/config.inc.php` car les futures mises à jour le remplaceront.

## Documentation

 * Documentation officielle : https://github.com/roundcube/roundcubemail/wiki
 * Documentation YunoHost : https://github.com/YunoHost/doc/blob/master/app_roundcube.md:

## Caractéristiques spécifiques YunoHost

En plus des fonctionnalités principales de Roundcube, les éléments suivants sont disponibles avec ce paquet :

 * Synchronisez vos alias de messagerie en tant qu'identités dans Roundcube.
 * Installation des plugins [contextmenu](https://plugins.roundcube.net/packages/johndoh/contextmenu)
   et [automatic addressbook](https://plugins.roundcube.net/packages/sblaisot/automatic_addressbook) par default.
 * Permettre d'installer [CardDAV](https://plugins.roundcube.net/packages/roundcube/carddav) (carnet d'adresses) de synchronisation à l'installation - notez que si vous avez installé Nextcloud ou Baïkal, il ajoutera automatiquement le carnet d'adresses correspondant.

#### Support multi-utilisateur

* Intégration avec les utilisateurs YunoHost et SSO - c'est-à-dire le bouton de déconnexion, reconnaissance des autres utilisateurs de l'instance YunoHost.

#### Supported architectures

* x86-64 - [![Build Status](https://ci-apps.yunohost.org/ci/logs/roundcube%20%28Apps%29.svg)](https://ci-apps.yunohost.org/ci/apps/roundcube/)
* ARMv8-A - [![Build Status](https://ci-apps-arm.yunohost.org/ci/logs/roundcube%20%28Apps%29.svg)](https://ci-apps-arm.yunohost.org/ci/apps/roundcube/)

## Limitations

* Aucune limitation connue.

## Additional information

#### Plugins

Vous pouvez également installer d'autres plugins (qui ne seront pas supprimés avec les mises à niveau). Pour cela, vous pouvez utiliser le [Plugin Repository](https://plugins.roundcube.net/) officiel.

##### Depuis le dépôt de plugins

Si, par exemple, vous voulez installer le plugin [html5_notifier](https://plugins.roundcube.net/packages/kitist/html5_notifier).

1. Connectez-vous en SSH à votre serveur en tant que root :
   ```
   $ ssh admin@1.2.3.4
   $ sudo -i
   ```

2. Connectez-vous en tant qu'utilisateur `roundcube` (qui possède le répertoire roundcube) et naviguez dedans :
   ```
   # su -s /bin/bash - roundcube
   $ cd /var/www/roundcube
   ```

3. Installez le plugin que vous voulez en utilisant Composer - notez que vous devez spécifier
   *kitist/html5_notifier* et pas seulement *html5_notifier* :
   ```
   $ COMPOSER_HOME=./.composer php composer.phar require "kitist/html5_notifier"
   ```

4. Activez-le dans le fichier de configuration local `conf/local.inc.php` en utilisant un éditeur de texte en ajoutant :
   ```
   <?php
   $config['plugins'][] = 'html5_notifier';
   ```

Notez que vous devriez également vérifier la page d'accueil du plugin pour une installation supplémentaire si besoin.

##### Installation manuelle

Vous pouvez également télécharger le plugin et le placer dans le répertoire `plugins/`. Dans ce cas, n'oubliez pas de changer l'*ownerships* de ce dossier à `roundcube`.

## Liens

 * Signaler un bug : https://github.com/YunoHost-Apps/roundcube_ynh/issues
 * Site de l'application : https://roundcube.net/
 * Dépôt de l'application principale : https://github.com/roundcube/roundcubemail
 * Site web YunoHost : https://yunohost.org/

---

## Informations pour les développeurs

Merci de faire vos pull request sur la [branche testing](https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing).

Pour essayer la branche testing, procédez comme suit.
```
sudo yunohost app install https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
ou
sudo yunohost app upgrade roundcube -u https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
```
