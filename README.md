#Symfony REST API 

Install with command: 

    composer install

Then generate the SSH keys:

    mkdir -p config/jwt
    openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

Load fixtures:

    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate --no-interaction
    php bin/console doctrine:fixtures:load --no-interaction


#DevTools scripts:

Cache clear:
    
    ./c.cmd 

Start a local server https://127.0.0.1:8080/:

    ./s.cmd

http-request.http - file with test requests to API end-points

Nelmio API Doc Sandbox is available on: 

    https://127.0.0.1:8080/api/doc
