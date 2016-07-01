Mabike
======
A REST API for keeping track of motorcycles maintenance 

### Features
- Motorcycle Resource;
- Oil Change Resource;
- Rear and Front Tire Change Resources;
- Maintenance Warning System  (adds warnings to the motorcycle resource representation when it's time to perform a maintenance).

### Requirements
- The usual [Symfony 3 application requirements](http://symfony.com/doc/3.0/reference/requirements.html);
- PDO-MySQL PHP extension;
- PHPUnit and PDO-SQLite PHP extension (needed in order to run functional tests).

### Instalation
- Clone the project;
- Run ``` $ composer install ```;
- Create the parameters.yml file at app/config/ directory based on the app/config/parameters.yml.dist file. Mailer information is not needed;
- Run ``` $ php bin/console doctrine:database:create ```;
- Run ``` $ php bin/console doctrine:schema:create ```;
- Clear the cache with ``` $ php bin/console cache:clear ```.

### Documentation
- The API manual (with sandbox) is available at [localhost:8000/app.php/api/v1/doc](localhost:8000/app.php/api/v1/doc)

### Usage
- Start the PHP embedded server with ``` $ php bin/console server:run ```;
- Register a User at [localhost:8000/app.php/api/v1/registration](localhost:8000/app.php/api/v1/registration);
- Get an authentication token at [localhost:8000/app.php/api/v1/login](localhost:8000/app.php/api/v1/login);
- Add the token to the *"X-AUTH-TOKEN"* request header for every request;
- The API entry point is at [localhost:8000/app.php/api/v1](localhost:8000/app.php/api/v1);
- The API manual and HATEOAS with HAL standard will tell everything else!

