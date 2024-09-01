<!--
Важно: этот README был автоматически сгенерирован <https://github.com/YunoHost/apps/tree/master/tools/readme_generator>
Он НЕ ДОЛЖЕН редактироваться вручную.
-->

# Roundcube для YunoHost

[![Уровень интеграции](https://dash.yunohost.org/integration/roundcube.svg)](https://ci-apps.yunohost.org/ci/apps/roundcube/) ![Состояние работы](https://ci-apps.yunohost.org/ci/badges/roundcube.status.svg) ![Состояние сопровождения](https://ci-apps.yunohost.org/ci/badges/roundcube.maintain.svg)

[![Установите Roundcube с YunoHost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=roundcube)

*[Прочтите этот README на других языках.](./ALL_README.md)*

> *Этот пакет позволяет Вам установить Roundcube быстро и просто на YunoHost-сервер.*  
> *Если у Вас нет YunoHost, пожалуйста, посмотрите [инструкцию](https://yunohost.org/install), чтобы узнать, как установить его.*

## Обзор

Roundcube is a web-based e-mail client. It offers all the features you'd expect from a mail client, including multilingual support, address book management, folder manipulation, message search and spell checking.

### YunoHost specific features

- Synchronize your email aliases as identities in Roundcube
- Install the [contextmenu](https://packagist.org/packages/johndoh/contextmenu) and [automatic addressbook](https://packagist.org/packages/projectmyst/automatic_addressbook) plugins by default
- Allow to install the [CardDAV](https://packagist.org/packages/roundcube/carddav) (address book) synchronization plugin at the installation - note that if you have installed Nextcloud or Baïkal, it will automatically add the corresponding and existing address book.
- Support for PGP encryption with Enigma plugin by default.


**Поставляемая версия:** 1.6.9~ynh1

**Демо-версия:** <https://demo.yunohost.org/webmail/>

## Снимки экрана

![Снимок экрана Roundcube](./doc/screenshots/screenshot.png)

## Документация и ресурсы

- Официальный веб-сайт приложения: <https://roundcube.net/>
- Официальная документация администратора: <https://github.com/roundcube/roundcubemail/wiki>
- Репозиторий кода главной ветки приложения: <https://github.com/roundcube/roundcubemail>
- Магазин YunoHost: <https://apps.yunohost.org/app/roundcube>
- Сообщите об ошибке: <https://github.com/YunoHost-Apps/roundcube_ynh/issues>

## Информация для разработчиков

Пришлите Ваш запрос на слияние в [ветку `testing`](https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing).

Чтобы попробовать ветку `testing`, пожалуйста, сделайте что-то вроде этого:

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
или
sudo yunohost app upgrade roundcube -u https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
```

**Больше информации о пакетировании приложений:** <https://yunohost.org/packaging_apps>
