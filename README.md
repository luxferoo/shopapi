shop_be
=======

ReactJS client : https://github.com/luxferoo/shopclient

This API will provide to the client (Mobile,Web app) the possibility to :

- Register.
- Logging in (SSO).
- Fetch a list of shops (only non-disliked shops within the last 2 hours).
- Like or dislike a shop.
- Fetch a list of Liked shops.

# Installation

### Clone the project localy using command line : 

```command
$ git clone https://github.com/luxferoo/shopapi.git
```

### configure your database : 

```yaml
# app/config/parameters.yml
parameters:
    database_host: 127.0.0.1
    database_port: null
    database_name: database_name
    database_user: user
    database_password: password
```

### install dependencies with composer 
I added 2 bundles in the project :
LexikJWTAuthenticationBundle : for JWT generation and NelmioApiDocBundle : for the api doc.
at root project
```command
$ composer install
```
### JWT configuration :
put your own passphrase and ttl (for token expiration)
```yaml
# app/config/config.yml
...
parameters:
    locale: en
    jwt_passphrase: passphrase
    jwt_token_ttl: 36000 #in seconds
```  
Generate the SSH keys : 
```
$ mkdir -p var/jwt # For Symfony3+, no need of the -p option
$ openssl genrsa -out var/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem
```

and for more details about LexikJWTAuthenticationBundle you can visit the project repo :
[GitHub](https://github.com/lexik/LexikJWTAuthenticationBundle)

### Tables creation :
```
$ bin/console doctrine:schema:update --force
```
### User & password for Api doc access (http authentication) :
```yaml
# app/config/security.yml
...
in_memory:
                        memory:
                                users:
                                    luxfero:
                                        password: azerty
                                        roles: 'ROLE_ADMIN'
```
### Api doc :

NelmioApiDoc main page is available on : baseUrl/doc
```yaml
# app/config/routing.yml
...
app.swagger_ui:
    resource: "@NelmioApiDocBundle/Resources/config/routing/swaggerui.xml"
    prefix:   /doc
```




