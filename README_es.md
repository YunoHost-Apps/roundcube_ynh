<!--
Este archivo README esta generado automaticamente<https://github.com/YunoHost/apps/tree/master/tools/readme_generator>
No se debe editar a mano.
-->

# Roundcube para Yunohost

[![Nivel de integración](https://dash.yunohost.org/integration/roundcube.svg)](https://ci-apps.yunohost.org/ci/apps/roundcube/) ![Estado funcional](https://ci-apps.yunohost.org/ci/badges/roundcube.status.svg) ![Estado En Mantención](https://ci-apps.yunohost.org/ci/badges/roundcube.maintain.svg)

[![Instalar Roundcube con Yunhost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=roundcube)

*[Leer este README en otros idiomas.](./ALL_README.md)*

> *Este paquete le permite instalarRoundcube rapidamente y simplement en un servidor YunoHost.*  
> *Si no tiene YunoHost, visita [the guide](https://yunohost.org/install) para aprender como instalarla.*

## Descripción general

Roundcube is a web-based e-mail client. It offers all the features you'd expect from a mail client, including multilingual support, address book management, folder manipulation, message search and spell checking.

### YunoHost specific features

- Synchronize your email aliases as identities in Roundcube
- Install the [contextmenu](https://packagist.org/packages/johndoh/contextmenu) and [automatic addressbook](https://packagist.org/packages/projectmyst/automatic_addressbook) plugins by default
- Allow to install the [CardDAV](https://packagist.org/packages/roundcube/carddav) (address book) synchronization plugin at the installation - note that if you have installed Nextcloud or Baïkal, it will automatically add the corresponding and existing address book.
- Support for PGP encryption with Enigma plugin by default.


**Versión actual:** 1.6.9~ynh1

**Demo:** <https://demo.yunohost.org/webmail/>

## Capturas

![Captura de Roundcube](./doc/screenshots/screenshot.png)

## Documentaciones y recursos

- Sitio web oficial: <https://roundcube.net/>
- Documentación administrador oficial: <https://github.com/roundcube/roundcubemail/wiki>
- Repositorio del código fuente oficial de la aplicación : <https://github.com/roundcube/roundcubemail>
- Catálogo YunoHost: <https://apps.yunohost.org/app/roundcube>
- Reportar un error: <https://github.com/YunoHost-Apps/roundcube_ynh/issues>

## Información para desarrolladores

Por favor enviar sus correcciones a la [rama `testing`](https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing).

Para probar la rama `testing`, sigue asÍ:

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
o
sudo yunohost app upgrade roundcube -u https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
```

**Mas informaciones sobre el empaquetado de aplicaciones:** <https://yunohost.org/packaging_apps>
