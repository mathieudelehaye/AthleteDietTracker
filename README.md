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

Run it as a microservice: 

```
docker build --no-cache -t mariadb_image MySQL/
docker run -it -d --name maria_container -p 12345:3306 mariadb_image

docker build --no-cache -t diet_tracker_image Microservice/
docker run -it -d --name diet_tracker_container -p 8081:8000 diet_tracker_image
```

Then connect to the `http://localhost:8081/` address.
