# Diet_track_PHP
An application to track diet calories written in PHP with Symfony and Bootstrap 4. 

Screenshot: 

<img src="Image 6-6-20 at 12.55 PM.jpeg" alt="App_screenshot" style="float: left; margin-right: 10px;" width="800"/>

Run it: 

php composer.phar install

**! Ensure it is possible to write in project sub-directories !** : 

```
sudo chmod -R 777 .
```

Install Symfony Profiler:
```
php composer.phar require --dev symfony/profiler-pack
```

In .env file: check that: APP_ENV=dev

Then: 
```
php bin/console cache:clear --no-warmup --env=dev 
```

Clear Symfony cache: 
```
php bin/console cache:clear 
```

