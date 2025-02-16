<!--
N.B.: README ini dibuat secara otomatis oleh <https://github.com/YunoHost/apps/tree/master/tools/readme_generator>
Ini TIDAK boleh diedit dengan tangan.
-->

# Roundcube untuk YunoHost

[![Tingkat integrasi](https://apps.yunohost.org/badge/integration/roundcube)](https://ci-apps.yunohost.org/ci/apps/roundcube/)
![Status kerja](https://apps.yunohost.org/badge/state/roundcube)
![Status pemeliharaan](https://apps.yunohost.org/badge/maintained/roundcube)

[![Pasang Roundcube dengan YunoHost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=roundcube)

*[Baca README ini dengan bahasa yang lain.](./ALL_README.md)*

> *Paket ini memperbolehkan Anda untuk memasang Roundcube secara cepat dan mudah pada server YunoHost.*  
> *Bila Anda tidak mempunyai YunoHost, silakan berkonsultasi dengan [panduan](https://yunohost.org/install) untuk mempelajari bagaimana untuk memasangnya.*

## Ringkasan

Roundcube is a web-based e-mail client. It offers all the features you'd expect from a mail client, including multilingual support, address book management, folder manipulation, message search and spell checking.

### YunoHost specific features

- Synchronize your email aliases as identities in Roundcube
- Install the [contextmenu](https://packagist.org/packages/johndoh/contextmenu) and [automatic addressbook](https://packagist.org/packages/projectmyst/automatic_addressbook) plugins by default
- Allow to install the [CardDAV](https://packagist.org/packages/roundcube/carddav) (address book) synchronization plugin at the installation - note that if you have installed Nextcloud or Baïkal, it will automatically add the corresponding and existing address book.
- Support for PGP encryption with Enigma plugin by default.


**Versi terkirim:** 1.6.9~ynh1

**Demo:** <https://demo.yunohost.org/webmail/>

## Tangkapan Layar

![Tangkapan Layar pada Roundcube](./doc/screenshots/screenshot.png)

## Dokumentasi dan sumber daya

- Website aplikasi resmi: <https://roundcube.net/>
- Dokumentasi admin resmi: <https://github.com/roundcube/roundcubemail/wiki>
- Depot kode aplikasi hulu: <https://github.com/roundcube/roundcubemail>
- Gudang YunoHost: <https://apps.yunohost.org/app/roundcube>
- Laporkan bug: <https://github.com/YunoHost-Apps/roundcube_ynh/issues>

## Info developer

Silakan kirim pull request ke [`testing` branch](https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing).

Untuk mencoba branch `testing`, silakan dilanjutkan seperti:

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
atau
sudo yunohost app upgrade roundcube -u https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
```

**Info lebih lanjut mengenai pemaketan aplikasi:** <https://yunohost.org/packaging_apps>
