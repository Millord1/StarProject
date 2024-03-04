# Backend
## Used:
* Factory
* Seed
* db migration
* PHP Unit
* Validator
* Logger

Install dependencies
> composer require

Create database and table
> php artisan migrate

seed the database with fake data
> php artisan db:seed

start server
> php artisan serve


# Frontend
## Used:
* NuxtJs
* Bootstrap-vue
* VueJs

> cd starsFront

> npm install

> npm run dev

## Addresses

The address to show all the stars is:
> http://localhost:3000/

The admin page:
> http://localhost:3000/admin

### Issues:
* On frontend I had issue to create a new star because of the POST route from js fetch method (The backend API itself is working)
* Apparently I would need a proxy to send the request from a third party
* I tried to add a cors as middleware to the backend routes but it was not working
