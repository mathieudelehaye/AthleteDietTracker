# Diet_track_PHP
An application to track diet calories written in PHP with Symfony, Doctrine and Bootstrap. 

It was later converted into a iPhone application: https://github.com/mathieudelehaye/FeedMe

<p float="left">
  <img src="screenshots/Screenshot00.png" alt="Screenshot00.png" style="float: left; margin-right: 10px;" width="600" hspace="10" />
</p>

<p float="left">
  <img src="screenshots/Screenshot01.png" alt="Screenshot01.png" style="float: left; margin-right: 10px;" width="600" hspace="10" />
</p>

<p float="left">
  <img src="screenshots/Screenshot02.png" alt="Screenshot02.png" style="float: left; margin-right: 10px;" width="600" hspace="10" />
</p>

Run it: 

```
php composer.phar install
```

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
symfony server:start -d
symfony open:local
symfony server:stop
```

Clear Symfony cache: 
```
php bin/console cache:clear --no-warmup --env=dev 
```

