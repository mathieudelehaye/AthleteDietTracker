FROM ubuntu:23.04
RUN apt-get -y update
RUN apt-get -y upgrade
RUN apt-get -y install mariadb-server
RUN apt-get -y install postgresql
RUN apt-get -y install vim
RUN apt-get install -y net-tools
RUN apt-get -y install iputils-ping
EXPOSE 3306 5432
COPY server.cnf /etc/mysql/mariadb.conf.d/server.cnf
COPY mysqld_safe.cnf /etc/mysql/mariadb.conf.d/mysqld_safe.cnf
COPY service.sh /App/service.sh
WORKDIR /App
RUN chmod +x /App/service.sh
CMD ["./service.sh"]
