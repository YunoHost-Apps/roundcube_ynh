<!--
注意：此 README 由 <https://github.com/YunoHost/apps/tree/master/tools/readme_generator> 自动生成
请勿手动编辑。
-->

# YunoHost 上的 Roundcube

[![集成程度](https://dash.yunohost.org/integration/roundcube.svg)](https://ci-apps.yunohost.org/ci/apps/roundcube/) ![工作状态](https://ci-apps.yunohost.org/ci/badges/roundcube.status.svg) ![维护状态](https://ci-apps.yunohost.org/ci/badges/roundcube.maintain.svg)

[![使用 YunoHost 安装 Roundcube](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=roundcube)

*[阅读此 README 的其它语言版本。](./ALL_README.md)*

> *通过此软件包，您可以在 YunoHost 服务器上快速、简单地安装 Roundcube。*  
> *如果您还没有 YunoHost，请参阅[指南](https://yunohost.org/install)了解如何安装它。*

## 概况

Roundcube is a web-based e-mail client. It offers all the features you'd expect from a mail client, including multilingual support, address book management, folder manipulation, message search and spell checking.

### YunoHost specific features

- Synchronize your email aliases as identities in Roundcube
- Install the [contextmenu](https://packagist.org/packages/johndoh/contextmenu) and [automatic addressbook](https://packagist.org/packages/projectmyst/automatic_addressbook) plugins by default
- Allow to install the [CardDAV](https://packagist.org/packages/roundcube/carddav) (address book) synchronization plugin at the installation - note that if you have installed Nextcloud or Baïkal, it will automatically add the corresponding and existing address book.
- Support for PGP encryption with Enigma plugin by default.


**分发版本：** 1.6.7~ynh3

**演示：** <https://demo.yunohost.org/webmail/>

## 截图

![Roundcube 的截图](./doc/screenshots/screenshot.png)

## 文档与资源

- 官方应用网站： <https://roundcube.net/>
- 官方管理文档： <https://github.com/roundcube/roundcubemail/wiki>
- 上游应用代码库： <https://github.com/roundcube/roundcubemail>
- YunoHost 商店： <https://apps.yunohost.org/app/roundcube>
- 报告 bug： <https://github.com/YunoHost-Apps/roundcube_ynh/issues>

## 开发者信息

请向 [`testing` 分支](https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing) 发送拉取请求。

如要尝试 `testing` 分支，请这样操作：

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
或
sudo yunohost app upgrade roundcube -u https://github.com/YunoHost-Apps/roundcube_ynh/tree/testing --debug
```

**有关应用打包的更多信息：** <https://yunohost.org/packaging_apps>
