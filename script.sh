#!/bin/bash

    echo "Iniciando composer install"
    composer install 

    echo "Iniciando artisan migrate --path "
    php artisan migrate --path=database/migrations/crow_empty

    echo "Iniciando as migrações..."
    php artisan migrate

    echo "Instanciando as tabelas do banco"
    php artisan db:seed
    
    echo "Iniciando a geração da chave"
    php artisan key:generate

    echo "Executando o link do armazenamento"
    php artisan storage:link

    echo "Executando setup storage"
    php artisan setup:storage

    echo "Iniciando as permissões do diretório"
    chmod -R 775 *

    echo "Iniciando as permissões de grupos"
    chown -R www-data:www-data *

