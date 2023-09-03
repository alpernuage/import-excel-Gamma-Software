[![Symfony_6.3](https://img.shields.io/badge/Symfony-6-blue.svg)](https://symfony.com/)
[![PHP 8.2](https://img.shields.io/badge/PHP-8.2-purple.svg)](https://www.php.net/)
[![Docker](https://img.shields.io/badge/Docker-blue.svg)](https://www.docker.com/)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-blue.svg)
[![Makefile](https://img.shields.io/badge/Makefile-blue.svg)](https://www.gnu.org/software/make/)

# Excel Import Project
[See instructions page](INSTRUCTIONS.md)  
This project leverages various Symfony components and PHP extensions, including `Doctrine/ORM`, `Form`, `Maker`, `Twig`, `Validator`, `phpspreadsheet` to achieve different steps.

## Requirements

- [Docker](https://www.docker.com)
- Make (already installed on most Linux distributions and macOS)

## Install
- Copy the directory from source code or clone the project
```     
git clone git@github.com:alpernuage/import-excel-Gamma-Software.git
```

- Then `cd import-excel-Gamma-Software`

- The SERVER_NAME value is `localhost` by default.  
  If you want, add SERVER_NAME value, located in `.env` file, in `/etc/hosts` in order to match your docker daemon
  machine IP (it should be 127.0.0.1) or use a tool like `dnsmasq` to map the docker daemon to a local tld
  (e.g. `.local`).
- Create a database with connexion parameters in `.env` file
- Then just run `make install` command and follow instructions.
  Run `make help` to display available commands.
