## Set up project

### With docker-compose, 
```
docker-compose up -d
docker-compose exec backend bash
composer install

## FOR WSL2 USERS: 
## before running next command
## update DATABASE_URL .env variable host IP with IP of WSL instance (inet from ifconfig output) 

php bin/console doctrine:migrations:migrate
php bin/console cache:clear
```
review project settings on .env

your application should be running on http://localhost:8080

### Without docker-compose:
 - requirements
   - php 8.1
   - postgres 14
   - php composer
   - webserver
 - update postgres connection details on .env 

## App usage

### Creating a Post

```
curl --location --request POST 'http://localhost:8080/posts' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data-raw '{
    "title": "My first post",
    "summary": "Nam non est risus. Donec at orci at lectus consequat scelerisque vel ac justo."
}'
```

## Logic:

- Feature 1: updated the fields with new field description and used
  ``php bin/console doctrine:schema:update --force --complete `` to sync the entity with database


- feature 3: used transformer to transform entity to Application DTO and use the response to transform to JSON object


## Accessing API docs
Please use the below link to access API's through docs
```
http://localhost:8080/api/doc
```
