


## Backend Dictionary - Docker Setup

>  This is a challenge by [Coodesh](https://coodesh.com/)

Este repositório contém a aplicação API para o projeto **Dictionary**, construída com **Laravel**. 

O Docker Compose é utilizado para facilitar o desenvolvimento e a execução do projeto em containers. Siga as etapas abaixo para configurar o ambiente de desenvolvimento no Docker.


### Pré-requisitos

- [**Docker** (versão 20.10.0 ou superior)](https://docs.docker.com/get-docker/) 
- [**Docker Compose**  (versão 1.27.0 ou superior)](https://docs.docker.com/compose/install/)

### Configuração do Ambiente

1. Renomeie o arquivo `.env.example` para `.env`
```bash
cp .env.example .env
```

2. Primeiro clone o repositório e navegue até o diretório
```bash
git clone https://github.com/ataidefcjr/coodesh-backend-dictionary-test.git
cd coodesh-backend-dictionary-test/dictionary-coodesh
```

3. Agora precisamos construir e subir a imagem Docker
```bash
docker compose build
docker compose up -d 
```

4. Nesse passo garantimos que as dependencias sejam atualizadas e configuramos o banco de dados, nesse passo as palavras são importadas automaticamente com o argumento --seed.
```bash
docker compose exec laravel.test composer update
docker compose exec laravel.test php artisan migrate:fresh --seed
```

5. Agora damos as permissões corretas para garantir que não tenhamos erros de permissão no diretório de cache e storage
```bash
docker compose exec chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
```
6. Reiniciamos os containeres para garantir que todas as alterações aplicadas entrem em vigor:
```bash
docker compose restart
```

### Acesse a documentação

- Agora com o container rodando, basta acessar a [**Documentação**](http://localhost:8000/docs/api) para conferir se está tudo certo.
