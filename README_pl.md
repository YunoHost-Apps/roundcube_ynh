<!--
To README zostało automatycznie wygenerowane przez <https://github.com/YunoHost/apps/tree/master/tools/readme_generator>
Nie powinno być ono edytowane ręcznie.
-->

# Roundcube dla YunoHost

[![Poziom integracji](https://apps.yunohost.org/badge/integration/roundcube)](https://ci-apps.yunohost.org/ci/apps/roundcube/)
![Status działania](https://apps.yunohost.org/badge/state/roundcube)
![Status utrzymania](https://apps.yunohost.org/badge/maintained/roundcube)

[![Zainstaluj Roundcube z YunoHost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=roundcube)

*[Przeczytaj plik README w innym języku.](./ALL_README.md)*

> *Ta aplikacja pozwala na szybką i prostą instalację Roundcube na serwerze YunoHost.*  
> *Jeżeli nie masz YunoHost zapoznaj się z [poradnikiem](https://yunohost.org/install) instalacji.*

## Przegląd

Roundcube is a web-based e-mail client. It offers all the features you'd expect from a mail client, including multilingual support, address book management, folder manipulation, message search and spell checking.

### YunoHost specific features

- Synchronize your email aliases as identities in Roundcube
- Install the [contextmenu](https://packagist.org/packages/johndoh/contextmenu) and [automatic addressbook](https://packagist.org/packages/projectmyst/automatic_addressbook) plugins by default
- Allow to install the [CardDAV](https://packagist.org/packages/roundcube/carddav) (address book) synchronization plugin at the installation - note that if you have installed Nextcloud or Baïkal, it will automatically add the corresponding and existing address book.
- Support for PGP encryption with Enigma plugin by default.

**Dostarczona wersja:** 1.6.10~ynh1

**Demo:** <https://demo.yunohost.org/webmail/>

## Zrzuty ekranu

![Zrzut ekranu z Roundcube](./doc/screenshots/screenshot.png)

## Dokumentacja i zasoby

- Oficjalna strona aplikacji: <https://roundcube.net/>
- Oficjalna dokumentacja dla administratora: <https://github.com/roundcube/roundcubemail/wiki>
- Repozytorium z kodem źródłowym: <https://github.com/roundcube/roundcubemail>
- Sklep YunoHost: <https://apps.yunohost.org/app/roundcube>
- Zgłaszanie błędów: <https://github.com/YunoHost-Apps/roundcube_ynh/issues>

## Informacje od twórców

Wyślij swój pull request do [gałęzi `testing`](https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing).

Aby wypróbować gałąź `testing` postępuj zgodnie z instrukcjami:

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
lub
sudo yunohost app upgrade roundcube -u https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
```

**Więcej informacji o tworzeniu paczek aplikacji:** <https://yunohost.org/packaging_apps>
