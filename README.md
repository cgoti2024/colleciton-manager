# Shopify App

### Setup
- Clone the Repository
- Run `composer install` command in root of repo
- Create SQL database and update below variable in .env file
> copy `.env.example`  to `.env`  for reference
- DB_CONNECTION
- DB_HOST
- DB_PORT
- DB_DATABASE
- DB_USERNAME
- DB_PASSWORD
> Other envs to update
- APP_URL
- SHOPIFY_API_KEY
- SHOPIFY_API_SECRET

> For shopify api key and secret, you can find in your shopify app (partner dashboard) for more information visit [laravel-shopify](https://github.com/Kyon147/laravel-shopify/wiki/Installation)

- Run `php artisan migrate` command in root
- Run `php artisan key:generate` command in root
- Run `npm install` command in root it will install the front-end dependency

### Front-end
After above setup run `npm run dev` in root of project it will compile the front-end code.

- At last open the shopify app in your one of shopify stores, if not installed then install by clicking on  **Select Store** in app overview.
- When you open the shopify app it will show your front-end output.