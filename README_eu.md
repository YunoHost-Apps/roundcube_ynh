<!--
Ohart ongi: README hau automatikoki sortu da <https://github.com/YunoHost/apps/tree/master/tools/readme_generator>ri esker
EZ editatu eskuz.
-->

# Roundcube YunoHost-erako

[![Integrazio maila](https://apps.yunohost.org/badge/integration/roundcube)](https://ci-apps.yunohost.org/ci/apps/roundcube/)
![Funtzionamendu egoera](https://apps.yunohost.org/badge/state/roundcube)
![Mantentze egoera](https://apps.yunohost.org/badge/maintained/roundcube)

[![Instalatu Roundcube YunoHost-ekin](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=roundcube)

*[Irakurri README hau beste hizkuntzatan.](./ALL_README.md)*

> *Pakete honek Roundcube YunoHost zerbitzari batean azkar eta zailtasunik gabe instalatzea ahalbidetzen dizu.*  
> *YunoHost ez baduzu, kontsultatu [gida](https://yunohost.org/install) nola instalatu ikasteko.*

## Aurreikuspena

Roundcube is a web-based e-mail client. It offers all the features you'd expect from a mail client, including multilingual support, address book management, folder manipulation, message search and spell checking.

### YunoHost specific features

- Synchronize your email aliases as identities in Roundcube
- Install the [contextmenu](https://packagist.org/packages/johndoh/contextmenu) and [automatic addressbook](https://packagist.org/packages/projectmyst/automatic_addressbook) plugins by default
- Allow to install the [CardDAV](https://packagist.org/packages/roundcube/carddav) (address book) synchronization plugin at the installation - note that if you have installed Nextcloud or Baïkal, it will automatically add the corresponding and existing address book.
- Support for PGP encryption with Enigma plugin by default.


**Paketatutako bertsioa:** 1.6.9~ynh1

**Demoa:** <https://demo.yunohost.org/webmail/>

## Pantaila-argazkiak

![Roundcube(r)en pantaila-argazkia](./doc/screenshots/screenshot.png)

## Dokumentazioa eta baliabideak

- Aplikazioaren webgune ofiziala: <https://roundcube.net/>
- Administratzaileen dokumentazio ofiziala: <https://github.com/roundcube/roundcubemail/wiki>
- Jatorrizko aplikazioaren kode-gordailua: <https://github.com/roundcube/roundcubemail>
- YunoHost Denda: <https://apps.yunohost.org/app/roundcube>
- Eman errore baten berri: <https://github.com/YunoHost-Apps/roundcube_ynh/issues>

## Garatzaileentzako informazioa

Bidali `pull request`a [`testing` abarrera](https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing).

`testing` abarra probatzeko, ondorengoa egin:

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
edo
sudo yunohost app upgrade roundcube -u https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
```

**Informazio gehiago aplikazioaren paketatzeari buruz:** <https://yunohost.org/packaging_apps>
