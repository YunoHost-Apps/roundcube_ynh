<!--
NOTA: Este README foi creado automáticamente por <https://github.com/YunoHost/apps/tree/master/tools/readme_generator>
NON debe editarse manualmente.
-->

# Roundcube para YunoHost

[![Nivel de integración](https://dash.yunohost.org/integration/roundcube.svg)](https://dash.yunohost.org/appci/app/roundcube) ![Estado de funcionamento](https://ci-apps.yunohost.org/ci/badges/roundcube.status.svg) ![Estado de mantemento](https://ci-apps.yunohost.org/ci/badges/roundcube.maintain.svg)

[![Instalar Roundcube con YunoHost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=roundcube)

*[Le este README en outros idiomas.](./ALL_README.md)*

> *Este paquete permíteche instalar Roundcube de xeito rápido e doado nun servidor YunoHost.*  
> *Se non usas YunoHost, le a [documentación](https://yunohost.org/install) para saber como instalalo.*

## Vista xeral

Roundcube is a browser-based multilingual IMAP client with an application-like user interface. It provides full functionality you expect from an email client, including MIME support, address book, folder manipulation, message searching and spell checking.

## YunoHost specific features

In addition to Roundcube core features, the following are made available with this package:

 * Synchronize your email aliases as identities in Roundcube
 * Install the [contextmenu](https://packagist.org/packages/johndoh/contextmenu) and [automatic addressbook](https://packagist.org/packages/projectmyst/automatic_addressbook) plugins by default
 * Allow to install the [CardDAV](https://packagist.org/packages/roundcube/carddav) (address book) synchronization plugin at the installation - note that if you have installed Nextcloud or Baïkal, it will automatically add the corresponding and existing address book.
* Support for PGP encryption with Enigma plugin by default.


**Versión proporcionada:** 1.6.6~ynh2

**Demo:** <https://demo.yunohost.org/webmail/>

## Capturas de pantalla

![Captura de pantalla de Roundcube](./doc/screenshots/screenshot.png)

## Documentación e recursos

- Web oficial da app: <https://roundcube.net/>
- Documentación oficial para admin: <https://github.com/roundcube/roundcubemail/wiki>
- Repositorio de orixe do código: <https://github.com/roundcube/roundcubemail>
- Tenda YunoHost: <https://apps.yunohost.org/app/roundcube>
- Informar dun problema: <https://github.com/YunoHost-Apps/roundcube_ynh/issues>

## Info de desenvolvemento

Envía a túa colaboración á [rama `testing`](https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing).

Para probar a rama `testing`, procede deste xeito:

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
ou
sudo yunohost app upgrade roundcube -u https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
```

**Máis info sobre o empaquetado da app:** <https://yunohost.org/packaging_apps>
