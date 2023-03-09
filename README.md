1) Take 3 database Universal , BLPL21 , BLPL211 and restore databaseon MS SQL SERVER and MS SQL SERVER MANAGEMENT STUDIO
2) Run composer install 
3) Make two connections in database.php sqlsrv and default
4) Getpdo sql server drivers and paste it in your php/ext folder and include both in php.ini extension=NAME OF DRIVERS as there are two sqlsrv and pdo_sqlsrv drivers
5) Run all migration using php artisan companies:migrate and rollback migration if needed using php artisan companies:migrate-rollback
6) Then Run php artisan serve to start server
Then Run php artisan serve to start server always enable extension=gd in your xampp increase memory_limit=2048M in your php.ini for installing laravel anappy can use https://www.webappfix.com/post/how-to-convert-html-to-image-pdf-using-snappy-in-laravel-9.html
