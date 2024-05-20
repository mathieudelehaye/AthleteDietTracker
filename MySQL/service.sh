#!/bin/bash

#service postgresql start
echo "sec ser"
service  mariadb start

mysql  -u root <<EOF
     CREATE USER 'test2070'@'%' IDENTIFIED BY 'password';
     GRANT ALL PRIVILEGES ON *.* TO 'test2070'@'%';
     FLUSH PRIVILEGES;
EOF
/bin/bash