# Marvel API

## Requisitos do Sistema
* PHP >= 7.2
* Laravel: 6.0.*
* Composer: 1.6.9

## Setup do Projeto

Instalar pacotes obrigatórios:
```
composer install

```
#### Arquivo .env
Renomeie o arquivo .env.example para .env e atributa valores as seguintes váriaveis de ambiente: MARVEL_PUBLICKEY, MARVEL_PRIVATEKEY e MARVEL_URL.

MARVEL_PUBLICKEY e MARVEL_PRIVATECKEY são as chaves geradas ao fazer cadastro na Marvel. E MARVEL_URL é url base da API Marvel.

Criar chave única para aplicação:
```
php artisan key:generate
```

Iniciar servidor de desenvolvimento
```
php artisan serve
```
