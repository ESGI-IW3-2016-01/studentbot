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
   apt-get update
   apt-get install -y apache2
   apt-get install php-xml
   apt-get install git
   SHELL
end
