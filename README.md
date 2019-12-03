danbelden/symfony-crud-api
========

[![Build Status](https://travis-ci.org/danbelden/symfony-crud-api.svg?branch=master)](https://travis-ci.org/danbelden/symfony-crud-api)

This project is designed to give you the starting point for a Symfony PHP REST/CRUD single responsibility api project.

# Requirements

The following software dependencies are required to run this project, see the docker image for more clarification:

- PHP >= 7.1
- MySQL >= 5.6

# Installation

This project is designed to be forked/extended to provide an internal MySQL dependant data service in your project.

This project is installed like any Symfony application project, using the PHP package manager `composer`:
- https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies

Simply navigate to the project root directory and run `composer install`.

# Swagger

This project supports SWAGGER Api documentation using the `zircote/swagger-php` plugin dependency, and class annotations:
- https://github.com/zircote/swagger-php
- https://github.com/danbelden/symfony-crud-api/blob/master/src/AppBundle/Controller/CreateController.php#L17-L43
- https://github.com/danbelden/symfony-crud-api/blob/master/src/AppBundle/Controller/DeleteController.php#L17-L42
- https://github.com/danbelden/symfony-crud-api/blob/master/src/AppBundle/Controller/DocController.php#L12-L21
- https://github.com/danbelden/symfony-crud-api/blob/master/src/AppBundle/Controller/ReadManyController.php#L18-L59
- https://github.com/danbelden/symfony-crud-api/blob/master/src/AppBundle/Controller/ReadOneController.php#L17-L43
- https://github.com/danbelden/symfony-crud-api/blob/master/src/AppBundle/Controller/UpdateController.php#L18-L54

The `swagger.json` file will be re-generated from these annotations each time you refresh the doc page itself (in dev mode), to simplify the update process to the swagger configuration file.

Using said annotation file, the swagger UX provides a rendered webpage of the api documentation and sanboxing facilities for interactive testing of each API endpoint.

You can test this using the docker file provided, once running using `docker-compose up` in the root folder, you should be able to reach this documentation on the `/doc` endpoint like this:
- http://localhost/doc

<img src="https://github.com/danbelden/symfony-crud-api/blob/master/readme/swagger.png" />

The UI is taken from a popular swagger UX template and can be customized to your requirements via the template file here:
- https://github.com/danbelden/symfony-crud-api/blob/master/src/AppBundle/Resources/views/doc.html.twig


# Security

Please take note this API has no security/ACL checking abilities, it is designed for use as an internal data service.

Therefore you should create buisess logic wrapping functionality to check ACL requirements before interacting with this project for data requirements and persistence needs.

# Usage

This project manages a demonstrative `Model` entity, `read one`, `read many`, `create`, `update` and `delete` endpoints are all provided, using `uuid` indexes (Less insecure than numeric identifiers).

This is why this project is designed to be forked, to supply CRUD abilities around the entity of your choosing... simply update references to `Model` to your requirements.

This should enable fast and simple data service development and deployment, removing the issues around developing data layer services and offerings.

I'll soon be developing an OpenSource client for interacting these sorts of services with your wrapping ACL services, that should provide your product specific behaviour.

Enjoy.
