<!--
N.B.: Questo README è stato automaticamente generato da <https://github.com/YunoHost/apps/tree/master/tools/readme_generator>
NON DEVE essere modificato manualmente.
-->

# Roundcube per YunoHost

[![Livello di integrazione](https://dash.yunohost.org/integration/roundcube.svg)](https://dash.yunohost.org/appci/app/roundcube) ![Stato di funzionamento](https://ci-apps.yunohost.org/ci/badges/roundcube.status.svg) ![Stato di manutenzione](https://ci-apps.yunohost.org/ci/badges/roundcube.maintain.svg)

[![Installa Roundcube con YunoHost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=roundcube)

*[Leggi questo README in altre lingue.](./ALL_README.md)*

> *Questo pacchetto ti permette di installare Roundcube su un server YunoHost in modo semplice e veloce.*  
> *Se non hai YunoHost, consulta [la guida](https://yunohost.org/install) per imparare a installarlo.*

## Panoramica

Roundcube is a browser-based multilingual IMAP client with an application-like user interface. It provides full functionality you expect from an email client, including MIME support, address book, folder manipulation, message searching and spell checking.

## YunoHost specific features

In addition to Roundcube core features, the following are made available with this package:

 * Synchronize your email aliases as identities in Roundcube
 * Install the [contextmenu](https://packagist.org/packages/johndoh/contextmenu) and [automatic addressbook](https://packagist.org/packages/projectmyst/automatic_addressbook) plugins by default
 * Allow to install the [CardDAV](https://packagist.org/packages/roundcube/carddav) (address book) synchronization plugin at the installation - note that if you have installed Nextcloud or Baïkal, it will automatically add the corresponding and existing address book.
* Support for PGP encryption with Enigma plugin by default.


**Versione pubblicata:** 1.6.6~ynh1

**Prova:** <https://demo.yunohost.org/webmail/>

## Screenshot

![Screenshot di Roundcube](./doc/screenshots/screenshot.png)

## Documentazione e risorse

- Sito web ufficiale dell’app: <https://roundcube.net/>
- Documentazione ufficiale per gli amministratori: <https://github.com/roundcube/roundcubemail/wiki>
- Repository upstream del codice dell’app: <https://github.com/roundcube/roundcubemail>
- Store di YunoHost: <https://apps.yunohost.org/app/roundcube>
- Segnala un problema: <https://github.com/YunoHost-Apps/roundcube_ynh/issues>

## Informazioni per sviluppatori

Si prega di inviare la tua pull request alla [branch di `testing`](https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing).

Per provare la branch di `testing`, si prega di procedere in questo modo:

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
o
sudo yunohost app upgrade roundcube -u https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
```

**Maggiori informazioni riguardo il pacchetto di quest’app:** <https://yunohost.org/packaging_apps>
