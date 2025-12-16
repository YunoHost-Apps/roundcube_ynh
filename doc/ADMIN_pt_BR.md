Você pode estender — ou até mesmo sobrescrever — a configuração do Roundcube que acompanha este pacote no arquivo `config/<DOMÍNIO_ROUNDCUBE>.inc.php`. Não edite o arquivo `config/config.inc.php`, pois futuras atualizações o sobrescreverão.

Você pode instalar plugins - que não serão removidos com atualizações. Para fazer isso, você pode usar o [Repositório de Plugins](https://plugins.roundcube.net/) oficial.

#### Do Repositório de Plug-ins

Digamos, por exemplo, que queremos instalar o plugin [html5_notifier](https://packagist.org/packages/kitist/html5_notifier).

1. Conecte-se ao seu servidor como root usando SSH:
   ```
   $ ssh admin@1.2.3.4
   $ sudo -i
   ```

2. Faça login como o usuário 'roundcube' - que possui o diretório roundcube - e navegue nele:
   ```
   # su -s /bin/bash - roundcube
   $ cd /var/www/roundcube
   ```

3. Instale o plugin que você deseja usando o composer - note que você tem que especificar *kitist/html5_notifier* e não apenas *html5_notifier*:
   ```
   $ COMPOSER_HOME=./.composer php composer.phar require "kitist/html5_notifier"
   ```

4. Habilite-o no arquivo de configuração local 'config/<ROUNDCUBE_DOMAIN>.inc.php' usando seu editor de texto favorito adicionando:

   ```
   <?php
   array_push($this->prop['plugins'], 'html5_notifier');
   ```
   Consulte https://github.com/roundcube/roundcubemail/issues/9458#issuecomment-2121753923.

Observe que você também deve verificar a página inicial do plug-in para obter etapas adicionais de instalação, conforme necessário.

#### Instalação manual

Você também pode baixar o plugin e colocá-lo no diretório 'plugins/'. Nesse caso, não se esqueça de alterar o proprietário deste diretório para 'roundcube'.
