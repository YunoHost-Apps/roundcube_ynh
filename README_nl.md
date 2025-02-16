<!--
NB: Deze README is automatisch gegenereerd door <https://github.com/YunoHost/apps/tree/master/tools/readme_generator>
Hij mag NIET handmatig aangepast worden.
-->

# Roundcube voor Yunohost

[![Integratieniveau](https://apps.yunohost.org/badge/integration/roundcube)](https://ci-apps.yunohost.org/ci/apps/roundcube/)
![Mate van functioneren](https://apps.yunohost.org/badge/state/roundcube)
![Onderhoudsstatus](https://apps.yunohost.org/badge/maintained/roundcube)

[![Roundcube met Yunohost installeren](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=roundcube)

*[Deze README in een andere taal lezen.](./ALL_README.md)*

> *Met dit pakket kun je Roundcube snel en eenvoudig op een YunoHost-server installeren.*  
> *Als je nog geen YunoHost hebt, lees dan [de installatiehandleiding](https://yunohost.org/install), om te zien hoe je 'm installeert.*

## Overzicht

Roundcube is a web-based e-mail client. It offers all the features you'd expect from a mail client, including multilingual support, address book management, folder manipulation, message search and spell checking.

### YunoHost specific features

- Synchronize your email aliases as identities in Roundcube
- Install the [contextmenu](https://packagist.org/packages/johndoh/contextmenu) and [automatic addressbook](https://packagist.org/packages/projectmyst/automatic_addressbook) plugins by default
- Allow to install the [CardDAV](https://packagist.org/packages/roundcube/carddav) (address book) synchronization plugin at the installation - note that if you have installed Nextcloud or Baïkal, it will automatically add the corresponding and existing address book.
- Support for PGP encryption with Enigma plugin by default.


**Geleverde versie:** 1.6.9~ynh1

**Demo:** <https://demo.yunohost.org/webmail/>

## Schermafdrukken

![Schermafdrukken van Roundcube](./doc/screenshots/screenshot.png)

## Documentatie en bronnen

- Officiele website van de app: <https://roundcube.net/>
- Officiele beheerdersdocumentatie: <https://github.com/roundcube/roundcubemail/wiki>
- Upstream app codedepot: <https://github.com/roundcube/roundcubemail>
- YunoHost-store: <https://apps.yunohost.org/app/roundcube>
- Meld een bug: <https://github.com/YunoHost-Apps/roundcube_ynh/issues>

## Ontwikkelaarsinformatie

Stuur je pull request alsjeblieft naar de [`testing`-branch](https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing).

Om de `testing`-branch uit te proberen, ga als volgt te werk:

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
of
sudo yunohost app upgrade roundcube -u https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
```

**Verdere informatie over app-packaging:** <https://yunohost.org/packaging_apps>
