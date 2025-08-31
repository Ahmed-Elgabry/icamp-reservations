

## About Project

This Dashboard has static features , We used Repositiry / Interface  Design Pattern , If You want to know more about design pattern click [here] (https://asperbrothers.com/blog/implement-repository-pattern-in-laravel).


## Usage 
create empty DataBase , change database data in .env file 
first run commands 

```bash
composer install
```

```bash
php artisan migrate:fresh  --seed
```

to migrate base tables of dashboard and create new users , roles and permissions to use dashboard 

## Security Vulnerabilities

***Dashboard Credentials***
* *still local*
* *dashboard:* <a href="#" target="_blank">Click To Open Dashboard</a>
* *email:*  admin@gmail.com
* *password:* admin



## Usage for create new Feature in dashboard

***Usage***
* php artisan make:crud Model_name_begin_with_Capital_char SingleName PluralName 
* *Example*
php artisan make:crud Category category categories --ob --seed --request --resource 
## This command line will create 
* Model Category with attributes ['name' ,'desc' , 'image']
* observer for image upload and delete 
* repository and interface which extends from base repository
* view files for index which contain delete one or more selecte items , edit page and create new 
* migration file 
* route with all resources create,edit,delete,delete_all and index
* You should migrate and seed to save new routes as permissions 


## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## License
 