added code to Exceptions/Handler.php
added code to Http/RedirectIfAuthenticated.php

php artisan config:cache to save changes in .env file

APP_URL=http://organizer.test

MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=2525
MAIL_PORT=587
MAIL_USERNAME=gilmouralmalbisdev@gmail.com
MAIL_PASSWORD=heheksdi1
MAIL_ENCRYPTION=tls

composer install:

1. Download Elasticsearch 
2. Run elasticsearch.bat
3. php artisan elastic:create-index App\\ArchiveFileIndexConfigurator
4. php artisan elastic:update-mapping App\\ArchiveFile
5. hp artisan scout:import "App\ArchiveFile"