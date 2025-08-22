<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

### Demo Credentials
    Admin: admin@cactusweb.gr 
    Password: 123456

## Installation
### 1. Download
Download the files above and place on your server.

### 2. Environment Files
This package ships with a .env.example file in the root of the project.

You must rename this file to just .env

Note: Make sure you have hidden files shown on your system.

### 3. Composer
Laravel project dependencies are managed through the PHP Composer tool. The first step is to install the depencencies by navigating into your project in terminal and typing this command:

    composer install

### 4. NPM/Yarn
In order to install the Javascript packages for frontend development, you will need the Node Package Manager, and optionally the Yarn Package Manager by Facebook (Recommended)

If you only have NPM installed you have to run this command from the root of the project:

    npm install

### 5. Create Database
You must create your database on your server and on your .env file update the following lines:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=homestead
    DB_USERNAME=homestead
    DB_PASSWORD=secret

Change these lines to reflect your new database settings.

### 6. Artisan Commands
The first thing we are going to do is set the key that Laravel will use when doing encryption.

    php artisan key:generate

You should see a green message stating your key was successfully generated. As well as you should see the APP_KEY variable in your .env file reflected.

It's time to see if your database credentials are correct.

We are going to run the built in migrations to create the database tables:

    php artisan migrate

You should see a message for each table migrated, if you don't and see errors, than your credentials are most likely not correct.

We are now going to set the administrator account information. To do this you need to navigate to this file and change the name/email/password of the Administrator account.

You can delete the other dummy users, but do not delete the administrator account or you will not be able to access the backend.

Now seed the database with:

    php artisan db:seed

You should get a message for each file seeded, you should see the information in your database tables.

### 7. NPM Run '*'
Now that you have the database tables and default rows, you need to build the styles and scripts.

These files are generated using Laravel Mix, which is a wrapper around many tools, and works off the vite.config.js in the root of the project.

You can build with:

    npm run <command>

The available commands are listed at the top of the package.json file under the 'scripts' key.

You will see a lot of information flash on the screen and then be provided with a table at the end explaining what was compiled and where the files live.

At this point you are done, you should be able to hit the project in your local browser and see the project, as well as be able to log in with the administrator and view the backend.

### 8. Storage:link
After your project is installed you must run this command to link your public storage folder for user avatar uploads:

    php artisan storage:link

### 9. Login

After your project is installed and you can access it in a browser, click the login button on the right of the navigation bar.

The administrator credentials are:

    Username: admin@cactusweb.gr 
    Password: 123456

You will be automatically redirected to the backend. If you changed these values in the seeder prior, then obviously use the ones you updated to.
