<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## About Appliction

Integrating Azure Active Directory (AD) for login functionality in a Laravel application provides a seamless and secure authentication experience by leveraging Microsoft's identity management solutions. By using the Laravel Socialite package, developers can easily implement Azure AD login, allowing users to authenticate using their existing Microsoft credentials. This process involves configuring Azure AD to set up an application registration, obtaining necessary client ID and secret keys, and then integrating these credentials into the Laravel application. 


## Application Installation & Setup

Begin by installing this application through Composer.

```bash
git clone git@github.com:ashokbittusharma/azure-login.git
```

Run compoer

```bash
composer install
```

copy .env.example in .env

```bash
cp .env.example .env
```

Add database detail in .env file

DB_CONNECTION=

DB_HOST=

DB_PORT=

DB_DATABASE=

DB_USERNAME=

DB_PASSWORD=

Add Azure AD credentials in .env file

AZURE_CLIENT_ID=

AZURE_CLIENT_SECRET=

AZURE_REDIRECT_URI=

AZURE_TENANT_ID=

Run migration

```bash
php artisan migrate
```

Generate the Application Encryption Key

```bash
php artisan key:generate
```

Vite manifest run commands

```bash
npm i
```

```bash
npm run dev
```

