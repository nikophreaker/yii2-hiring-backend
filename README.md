<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://www.yiiframework.com/image/logo.svg" height="200px">
    </a>
    <h1 align="center">Yii 2 RESTful API</h1>
    <br>
</p>

++++++++++++++++++++++++++++++++++
Yii2 RESTful API
++++++++++++++++++++++++++++++++++
This API using template from [Yii-api-template](https://github.com/hoaaah/yii2-rest-api-template).
This is a a REST API TEMPLATE with Yii2. This template use [Yii2-Micro](https://github.com/hoaaah/yii2-micro) approach so it will be lightweight and easy to deploy.

# Installation

---
Install [composer](http://getcomposer.org/download/).

Install with git

```bash
git clone https://github.com/nikophreaker/yii2-hiring-backend.git [app-name]

```

To directory `[app-name]`

```bash
composer install

```


# Setup Database

---

Setup database configuration from `config/db.php`.

```php
<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=your_db_name',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
    ],
];

```

# Running API

```bash
yii serve

```

# Directory Structure

---

Since this template use MicroFramework approach, directory structure might be a little bit different from Yii2.

      config/             contains application configurations
      controllers/        contains Web controller classes
      models/             contains model classes
      modules/            contains your rest-api versioning (based on modules)
      vendor/             contains dependent 3rd-party packages
      web/                contains the entry script and Web resources

# API Scenario

---

<p align="center">
    <h7 align="center">Creating User</h7>
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://raw.githubusercontent.com/nikophreaker/yii2-hiring-backend/main/ss%2Bdb%5BUntouched%20db%5D/register-hiring.JPG" height="400px">
    </a>
    <br>
</p>

<p align="center">
    <h7 align="center">Login User</h7>
    <h7 align="center">Don't forget to save your token</h7>
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://raw.githubusercontent.com/nikophreaker/yii2-hiring-backend/main/ss%2Bdb%5BUntouched%20db%5D/login-hiring.JPG" height="400px">
    </a>
    <br>
</p>


## Supported Authentication

---

This template support 3 most used authentication. (Actually it's not me who make it, Yii2 already support it all :D ).

1. HTTP Basic Auth: the access token is sent as the username. This should only be used when an access token can be safely stored on the API consumer side. For example, the API consumer is a program running on a server.
2. Query parameter: the access token is sent as a query parameter in the API URL, e.g., https://example.com/users?access-token=xxxxxxxx. Because most Web servers will keep query parameters in server logs, this approach should be mainly used to serve JSONP requests which cannot use HTTP headers to send access tokens.
3. OAuth 2: the access token is obtained by the consumer from an authorization server and sent to the API server via HTTP Bearer Tokens, according to the OAuth2 protocol.

## Auth Scenario

---

This template already have basic endpoint that you can use to start your REST-API. Such as:

| Endpoint                                         | Type        | Usage                                                                            |
| ------------------------------------------------ | ----------- | -------------------------------------------------------------------------------- |
| https://localhost:8080/                          | GET         | list all session created                                                         |
| https://localhost:8080/view?id={id}              | POST        | View a session                                                                   |
| https://localhost:8080/login                     | POST        | Login with username and password (stored 3 cookies include id, username & token) |
| https://localhost:8080/signup                    | POST        | Signup with username, email and password                                         |
| https://localhost:8080/logout                    |             | This endpoint must be use if you want to clear your cookies                      |
| https://localhost:8080/v1/session                | GET         | List all session created                                                         |
| https://localhost:8080/v1/session/create         | POST        | Create a new session                                                             |
| https://localhost:8080/v1/session/update?id={id} | PUT / PATCH | Update a session                                                                 |
| https://localhost:8080/v1/session/delete?id={id} | DELETE      | Delete a session                                                                 |
| https://localhost:8080/v1/session/view?id={id}   | GET         | View a session                                                                   |

This template use modules as versioning pattern. Every version of API saved in a module. This template already have v1 module, so it means if consumer want to use v1 API, it can access `https://localhost:8080/v1/endpoint`.

## Access Token Management

---

This application manage token via cookie without storing to table or data in database. Access Token have certain expiration based on $tokenExpiration value. Default Token Expiration are in seconds.

```php
public $tokenExpiration = 60 * 24 * 365; // in seconds
```

## API versioning

---

This template give you versioning scenario based on module application. In Yii2 a module are self-contained software units that consist of model, views, controllers and other supporting components. This template already have v1 module, it means all of endpoint for API v1 created in this module. When you publish a new API version (that break backward compatibility / BBC), you can create a new module. For more information create a module, you can visit this [Yii2 Guide on Creating Module](https://www.yiiframework.com/doc/guide/2.0/en/structure-modules).

## Screenshoot TEsting

---

<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://raw.githubusercontent.com/nikophreaker/yii2-hiring-backend/main/ss%2Bdb%5BUntouched%20db%5D/register-hiring.JPG" height="400px">
    </a>
</p>
<br>
<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://raw.githubusercontent.com/nikophreaker/yii2-hiring-backend/main/ss%2Bdb%5BUntouched%20db%5D/login-hiring.JPG" height="400px">
    </a>
</p>
<br>
<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://raw.githubusercontent.com/nikophreaker/yii2-hiring-backend/main/ss%2Bdb%5BUntouched%20db%5D/session-hiring.JPG" height="400px">
    </a>
</p>
<br>
<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://raw.githubusercontent.com/nikophreaker/yii2-hiring-backend/main/ss%2Bdb%5BUntouched%20db%5D/view-hiring.JPG" height="400px">
    </a>
</p>
<br>
<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://raw.githubusercontent.com/nikophreaker/yii2-hiring-backend/main/ss%2Bdb%5BUntouched%20db%5D/create-hiring.JPG" height="400px">
    </a>
</p>
