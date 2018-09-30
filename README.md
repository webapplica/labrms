# Laboratory Resource Management System

Labrms is a Resource Management System made specifically for the Laboratory Operations Office of CCIS. It includes the following sub systems:

  - Inventory Management
  - Ticketing Monitoring
  - Reservation

# Current Features

  - Reservation creation and approval done through the system
  - Complaints creation and action taken to a certain complaint logged under the system
  - Inventory monitoring

### Tech

LabRMS uses a number of open source projects to work properly:

* Laravel - PHP Framework
* [Jquery] - Javascript library
* Bootstrap - Mobile responsive front-end
* Backpack.io -  backup and restore modules

Other packages and plugins are credited to their respective owners.

### Installation

Labrms requires a web server with php 7.1+ and mysql to start

Install the dependencies and devDependencies and start the server.

```sh
Prerequisite:

- composer
- xampp, wamp, or any web server

installation:

PREPARING THE SYSTEM

1. copy the project to the webserver location
e.g. C:/xampp/htdocs

2. open command prompt under the project
- shift  + right click then open command window here
- e.g. cmd location C:/xampp/htdocs/labrms

3. start xampp
- start apache
- start mysql

CREATING DATABASE

1. open browser
2. url: localhost/phpmyadmin
3. click new
4. enter database name
5. click create

INSTALLING ADDITIONAL FILES

 ** composer **
  - go back to command prompt
  - run 'composer install'

  proxy problems?
  If we use the internet connection behind a proxy,
  then we have to make adjustments to the command prompt.

  To do so, before using the composer, run the command
  (for HTTP) set http_proxy:proxy_name:port_number and
  set https_proxy:proxy_name:port_number for https,
  for example: set https_proxy:10.20.2.263:8080

  ** Updating System **

  1. copy and replace the project to the webserver location
  e.g. C:/xampp/htdocs

  2. open command prompt under the project
  - shift  + right click then open command window here
  - e.g. cmd location C:/xampp/htdocs/labrms_v2 >

  3. On the command prompt, enter the following commands depending on the developers:
  1. Full migration refresh
  > php artisan rollback
  > php artisan migrate --seed
  2. Refresh migration
  > php artisan migrate:refresh --seed


  ** setting up the system **

  OPEN THE SYSTEM

  open browser then go to

 - localhost/labrms/public

initial accounts:
  - admin => 12345678
  - labassistant => 12345678
  - labstaff => 12345678
  - faculty => 12345678
  - student => 12345678

  run the following code
  - composer dump-autoload
  - php artisan migrate --seed
  - php artisan cache:clear
  - php artisan config:clear
  - php artisan routes:clear
  - php artisan config:cache
  - php artisan routes:cache

```

### Development

Want to contribute? Great!

Labrms uses Laravel for fast developing.
The system is available on github for forking and read-only purposes.
