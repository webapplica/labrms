# Laboratory Resource Management System

LaRMS is a Resource Management System made specifically for the Laboratory Operations Office of the developers current company. It includes the following subsystems:

  - Inventory
  - Ticketing
  - Reservation

# Current Features

  - Reservation creation and approval done through the system
  - Complaints creation and action taken to a certain complaint logged under the system
  - Inventory monitoring

### Tech

LabRMS uses a number of open source projects to work properly:

* Laravel - PHP Framework
* [JQuery] - Javascript library
* Bootstrap - Mobile responsive front-end
* Backpack.io -  backup and restore modules

Other packages and plugins are credited to their respective owners.

### Installation

Dillinger requires [Node.js](https://nodejs.org/) v4+ to run.

Install the dependencies and devDependencies and start the server.

```sh
prerequisite:

- composer
- xampp, wamp, or any web server

installation:

PREPARING THE SYSTEM

1. copy the project to the webserver location
e.g. C:/xampp/htdocs

2. open command prompt under the project
- shift  + right click then open command window here
- e.g. cmd location C:/xampp/htdocs/labrms_v2 >

3. run xampp or any web server you've chosen under the menu
- start apache
- start mysql

CREATING DATABASE

1. open browser
2. url: localhost/phpmyadmin
3. click new
4. enter 'labrms' as database name
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

```sh
For production environments...

APP_NAME='Laboratory Resource Management System'
APP_ENV=local
APP_KEY=base64:DPajOJAcGHnWS12aGdyhMrR7yyHXHVktTLdHSNzABoU=
APP_DEBUG=true
APP_LOG=daily
APP_LOG_LEVEL=debug
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=labrms
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=pusher
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=ssl

PUSHER_APP_ID=396662
PUSHER_APP_KEY=9e33dd8721931c8255d4
PUSHER_APP_SECRET=daa92c592ee1b929b6d2

MAILGUN_DOMAIN=sandboxd170108d61244d799da25708c23da1e8.mailgun.org
MAILGUN_SECRET=key-f23b71a997a3ae2d1e30ccf80a91a2ef

COMPANY_HEADER='Polytechnic University Of the Philippines'
COMPANY_SUBHEADER='Office of the Vice President for Academic Affairs'
COMPANY_DEPARTMENT='College Of Computer and Information Sciences'
COMPANY_SUBDEPARTMENT='Laboratory Operations Office'
COMPANY_IMAGE='images/logo/pup/pup-logo.png'
COMPANY_SUBIMAGE=''

```



### Plugins

LabRMS is currently extended with the following plugins. Instructions on how to use them in your own application are linked below.

| Plugin | README |
| ------ | ------ |


### Development

Want to contribute? Great!

LabRMS uses Laravel for fast developing.
Make a change in your file and instantanously see your updates! The system is available on github for forking and read-only purposes.
