## Configuration

Vous pouvez étendre (ou même remplacer) la configuration de Roundcube fournie avec ce paquet dans le fichier `conf/local.inc.php`. Ne modifiez pas le fichier `conf/config.inc.php` car les futures mises à jour le remplaceront.

#### Support multi-utilisateur

* Intégration avec les utilisateurs YunoHost et SSO - c'est-à-dire le bouton de déconnexion, reconnaissance des autres utilisateurs de l'instance YunoHost.

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

3. Installez le plugin que vous voulez en utilisant Composer - notez que vous devez spécifier *kitist/html5_notifier* et pas seulement *html5_notifier* :
   ```
   $ COMPOSER_HOME=./.composer php composer.phar require "kitist/html5_notifier"
   ```

4. Activez-le dans le fichier de configuration local `conf/local.inc.php` en ajoutant :
   ```
   <?php
   $config['plugins'][] = 'html5_notifier';
   ```
   
Notez que vous devez également consulter la page d'accueil du plugin pour connaître les étapes d'installation supplémentaires si nécessaire.

##### Installation manual 

Vous pouvez également télécharger le plugin et le placer dans le répertoire `plugins /`. Dans ce cas, n'oubliez pas de changer la propriété de ce dossier en `roundcube`.
