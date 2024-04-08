<p align="center">
    <h1 align="center">POS Laravel-Vue with Inertia</h1>
</p>

## Installation

### Requirements

For system requirements you [Check Laravel Requirement](https://laravel.com/docs/10.x/deployment#server-requirements)

### Clone the repository from github.

    git clone https://github.com/fayzisme/pos-laravel.git [YourDirectoryName]

The command installs the project in a directory named `YourDirectoryName`. You can choose a different
directory name if you want.

### Install dependencies

Laravel utilizes [Composer](https://getcomposer.org/) to manage its dependencies. So, before using Laravel, make sure you have Composer installed on your machine.

    cd YourDirectoryName
    composer install

### Config file

1. Rename or copy `.env.example` file to `.env` and `php artisan key:generate` to generate app key.
1. Set your database credentials in your `.env` file
1. Set your `APP_URL` in your `.env` file.

### Database

1. Migrate database table `php artisan migrate`
1. `php artisan db:seed`, this will initialize settings and create user

### Install Node Dependencies

1. `npm install` to install node dependencies
1. `npm run dev` for development or `npm run build` for production

### Create storage link

`php artisan storage:link`

### Run Server

1. `php artisan serve` or Laravel Homestead
1. Visit `localhost:8000` in your browser. 

<!-- ### Screenshots -->


<!-- [!["Buy Me A Coffee"](https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png)](url buy me cofee your account) -->
