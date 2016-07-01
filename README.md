Mabike
======
A REST API for keeping track of motorcycles maintenance 

--------
### Features
- Motorcycle Resource;
- Oil Change Resource;
- Rear and Front Tire Change Resources;
- Maintenance Warning System  (adds warnings to the motorcycle resource representation when it's time to perform a maintenance).

### Requirements
- The usual [Symfony 3 application requirements.](http://symfony.com/doc/3.0/reference/requirements.html);
- PDO-MySQL PHP extension;
- PHPUnit and PDO-SQLite PHP extension (needed in order to run functional testes).

### Instalation
- Clone the project;
- Run  ``` $ composer install ```;
- Clear the cache with ``` $ php bin/console cache:clear ```.

### Documentation
- The API manual (with sandbox) is available at the URI [/api/v1/doc](/api/v1/doc)

### Usage
- Register a User at the URI [/api/v1/registration](/api/v1/registration);
- Get an authentication token at the URI  [/api/v1/login](/api/v1/login);
- Add the token to the request header *X-AUTH-TOKEN* for every request;
- The API manual and HATEOAS with HAL standard will tell everything else!

