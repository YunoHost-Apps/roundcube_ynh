<!--
Nota bene : ce README est automatiquement généré par <https://github.com/YunoHost/apps/tree/master/tools/readme_generator>
Il NE doit PAS être modifié à la main.
-->

# Roundcube pour YunoHost

[![Niveau d’intégration](https://dash.yunohost.org/integration/roundcube.svg)](https://ci-apps.yunohost.org/ci/apps/roundcube/) ![Statut du fonctionnement](https://ci-apps.yunohost.org/ci/badges/roundcube.status.svg) ![Statut de maintenance](https://ci-apps.yunohost.org/ci/badges/roundcube.maintain.svg)

[![Installer Roundcube avec YunoHost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=roundcube)

*[Lire le README dans d'autres langues.](./ALL_README.md)*

> *Ce package vous permet d’installer Roundcube rapidement et simplement sur un serveur YunoHost.*  
> *Si vous n’avez pas YunoHost, consultez [ce guide](https://yunohost.org/install) pour savoir comment l’installer et en profiter.*

## Vue d’ensemble

Roundcube est un client mail sous forme d'application web. Il offre toutes les fonctionnalités que vous attendez d'un client de messagerie, y compris le support multilingue, la gestion du carnet d'adresses, la manipulation de dossiers, la recherche dans les messages et la vérification orthographique.

### Caractéristiques spécifiques YunoHost

- Synchronisation des alias de messagerie en tant qu'identités dans Roundcube.
- Installation des plugins [contextmenu](https://packagist.org/packages/johndoh/contextmenu) et [automatic addressbook](https://packagist.org/packages/projectmyst/automatic_addressbook) par défaut.
- Permettre d'installer [CardDAV](https://packagist.org/packages/roundcube/carddav) (carnet d'adresses) de synchronisation à l'installation - notez que si vous avez installé Nextcloud ou Baïkal, il ajoutera automatiquement le carnet d'adresses correspondant.
- Prise en charge du chiffrement PGP avec le plugin Enigma installé par default.


**Version incluse :** 1.6.8~ynh1

**Démo :** <https://demo.yunohost.org/webmail/>

## Captures d’écran

![Capture d’écran de Roundcube](./doc/screenshots/screenshot.png)

## Documentations et ressources

- Site officiel de l’app : <https://roundcube.net/>
- Documentation officielle de l’admin : <https://github.com/roundcube/roundcubemail/wiki>
- Dépôt de code officiel de l’app : <https://github.com/roundcube/roundcubemail>
- YunoHost Store : <https://apps.yunohost.org/app/roundcube>
- Signaler un bug : <https://github.com/YunoHost-Apps/roundcube_ynh/issues>

## Informations pour les développeurs

Merci de faire vos pull request sur la [branche `testing`](https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing).

Pour essayer la branche `testing`, procédez comme suit :

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
ou
sudo yunohost app upgrade roundcube -u https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
```

**Plus d’infos sur le packaging d’applications :** <https://yunohost.org/packaging_apps>
