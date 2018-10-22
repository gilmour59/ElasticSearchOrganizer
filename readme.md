- added code to Exceptions/Handler.php
- added code to Http/RedirectIfAuthenticated.php

#to save changes in .env file
- php artisan config:cache 


#Things TODO
1. composer install
2. cp .env.example .env
3. php artisan key:generate
4. php artisan storage:link

#env
- APP_URL=http://organizer.test
- MAIL_DRIVER=smtp
- MAIL_HOST=smtp.gmail.com
- MAIL_PORT=2525
- MAIL_PORT=587
- MAIL_USERNAME=gilmouralmalbisdev@gmail.com
- MAIL_PASSWORD=heheksdi1
- MAIL_ENCRYPTION=tls
- SCOUT_DRIVER=elastic

#Elasticsearch
https://github.com/babenkoivan/scout-elasticsearch-driver

1. config/app.php:

'providers' => [
    Laravel\Scout\ScoutServiceProvider::class,
    ScoutElastic\ScoutElasticServiceProvider::class,
]

2. run 
- php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
- php artisan vendor:publish --provider="ScoutElastic\ScoutElasticServiceProvider"

3. Run Elasticsearch.bat

4. php artisan elastic:create-index App\\ArchiveFileIndexConfigurator

5. php artisan elastic:update-mapping App\\ArchiveFile

6. php artisan scout:import "App\ArchiveFile"

#Getting Started
1. goto /register 
2. register an account
3. goto /permissions
4. add "administer roles and permissions" , "view post", "create post", "edit post", "delete post"
5. goto /roles
6. add Super Admin and set permission
7. Create desired users


#Super Admin
- has access to all 
- right to create user 
- right to delete user

TODO: Create interface for creating and deleting users

#Admin
- can CRUD records

#Normal User 
-can only view files