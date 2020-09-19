# just-menu

## Локальное окружение
* `docker-compose up -d` 
* `docker-compose exec fpm composer install`
* `localhost:8000`


### Сгенерировать серты для шифрования ключей  
* `docker-compose exec fpm openssl genrsa -out config/jwt/private.pem -aes256 4096`
* `docker-compose exec fpm openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem`

### Актуализировать .env
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=1111


## Доступные роуты
`localhost:8000/`
`localhost:8000/login`
`localhost:8000/logout`
`localhost:8000/register`


### Дополнительно
* После регистрации приходит письмо для подтверждения на EMAIL, локально можно увидеть вот здесь `http://localhost:1080`


### Docs 
Docs SWAGGER https://hrka.atlassian.net/wiki/spaces/JUS/pages/57933825/Swagger+live+documentation
