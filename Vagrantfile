Vagrant.configure("2") do |config|
  config.vm.box = "bento/ubuntu-20.04"
  config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.synced_folder ".", "/data/websites/great-foods-api-handler"
  config.vm.provision "shell", inline: <<-SHELL
    ## Basic Provisioning (I would normally use Ansible or similar for this)

    # Install PHP Dependencies
    sudo apt-get update
    sudo apt-get install -y lsb-release ca-certificates apt-transport-https software-properties-common
    sudo add-apt-repository ppa:ondrej/php
    sudo apt-get update
    sudo apt-get install -y php8.0
    sudo apt-get install -y php8.0-mbstring php8.0-xml

    # Install Composer
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
    ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

    if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]
    then
        >&2 echo 'ERROR: Invalid installer checksum'
        rm composer-setup.php
        exit 1
    fi

    php composer-setup.php --quiet
    RESULT=$?
    rm composer-setup.php
    sudo mv composer.phar /usr/local/bin/composer
    exit $RESULT

    # Install from composer.lock file
    cd /data/websites/great-foods-api-handler
    composer install
  SHELL
end