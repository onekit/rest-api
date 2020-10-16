#Symfony REST API 

Install with command: 

    composer install

Then generate the SSH keys:

    mkdir -p config/jwt
    openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

Load fixtures:

    php bin/console doctrine:database:create
    php bin/console doctrine:schema:update --force
    php bin/console doctrine:migrations:migrate
    php bin/console doctrine:fixtures:load

