**TURN OFF DEBUG MODE!
*Please Test Out the "return redirect('/')->with('isAdd', $isAdd)->with('errors', $errors)->withInput();" in the function viewFiles! - OK for the time being.


- added code to Exceptions/Handler.php
- added code to Http/RedirectIfAuthenticated.php

Reminder:
- the URL is case sensitive; 
- e.g. 192.1.23/ElasticSearchOrganizer/public 


#to save changes in .env file
- php artisan config:cache 


#TODO
1. composer install
2. cp .env.example .env
3. php artisan key:generate
4. php artisan storage:link
5. Apache php.ini posts and upload limitations change

#env
- APP_URL=http://organizer.test
- MAIL_DRIVER=smtp
- MAIL_HOST=smtp.gmail.com
- MAIL_PORT=2525
- MAIL_PORT=587
- MAIL_USERNAME=gilmouralmalbisdev@gmail.com
- MAIL_PASSWORD=
- MAIL_ENCRYPTION=tls
- SCOUT_DRIVER=elastic

#Elasticsearch
https://github.com/babenkoivan/scout-elasticsearch-driver

1. php artisan migrate 

2. config/app.php:

'providers' => [
    Laravel\Scout\ScoutServiceProvider::class,
    ScoutElastic\ScoutElasticServiceProvider::class,
]

3. run 
- php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
- php artisan vendor:publish --provider="ScoutElastic\ScoutElasticServiceProvider"

4. Run Elasticsearch.bat

5. php artisan elastic:create-index App\\ArchiveFileIndexConfigurator

6. php artisan elastic:update-mapping App\\ArchiveFile

7. php artisan scout:import "App\ArchiveFile"

8. php artisan db:seed

9. set storage path; by puting ORGANIZER_STORAGE="PATH" to .env

*References:
- https://github.com/babenkoivan/scout-elasticsearch-driver#console-commands
- https://laravel.com/docs/5.7/scout#indexing
*Errors:
- 500: elasticsearch not running!
- 401: not authorized!
- Yeet!
