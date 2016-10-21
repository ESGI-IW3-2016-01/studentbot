# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://atlas.hashicorp.com/search.w
  config.vm.box = "michaelward82/trusty64-php7"

  config.vm.network "forwarded_port", guest: 80, host: 8080

  config.vm.network "private_network", ip: "192.168.33.10"
  config.vm.network "public_network"

  config.vm.provider "virtualbox" do |vb|
      vb.gui = false
      vb.memory = "2048"
  end

config.vm.synced_folder "./", "/vagrant", id: "vagrant-root", :owner => "www-data", :group => "www-data"

config.vm.provision "shell", inline: <<-SHELL
    apt-get -qq update
    apt-get -q install -y apache2 php-xml git zip
    phpdismod xdebug
    echo "### DISABLING XDEBUG ###"

    # Composer installation
    echo "### COMPOSER INSTALLATION ###"
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
    mv composer.phar /usr/local/bin/composer
    echo "### COMPOSER INSTALLED ###"

    # Virtual Host
    echo "### VIRTUAL HOSTING ###"
    sudo cp /vagrant/symfony-app.conf /etc/apache2/sites-available/
    ln -s /vagrant/web/ /var/www/studentBot
    a2ensite symfony-app.conf
    echo "127.0.0.1     studentbot.localhost.com" >> /etc/hosts
    echo "### VIRTUAL HOSTED ! ###"

    # Restarts apache2
    service apache2 restart

    # New Database for Symfony App
    mysql -u root --execute "CREATE DATABASE symfony_app;"
    echo "### DATABASE CREATED ###"

    echo "### SO MUCH WIN \m/ ###"
    SHELL

config.vm.provision "shell", privileged: false, inline: <<-SHELL
    # Run Composer installation as vagrant user
    composer install -q -n --no-ansi -d /vagrant
    #echo "### COMPOSER UPDATED ###"

    # Run Doctrine
    /vagrant/bin/console doctrine:schema:create
    /vagrant/bin/symfony_requirements
    SHELL
end