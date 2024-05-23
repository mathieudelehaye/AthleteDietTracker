FROM alpine/git as clone
RUN rm -rf /app
RUN mkdir /app
WORKDIR /app
RUN git clone https://github.com/mathieudelehaye/AthleteDietTracker .
RUN git fetch origin && git pull origin master

FROM ubuntu:24.04
RUN apt-get -y update
RUN apt-get -y upgrade

RUN apt-get -y update
RUN apt-get -y upgrade
RUN apt-get -y install software-properties-common # apt-add-repository
RUN add-apt-repository ppa:ondrej/php # php8.2
RUN apt-get -y update
RUN apt-get -y install php8.2
RUN apt-get -y install php8.2-curl
RUN apt-get -y install php8.2-mysql
RUN apt-get -y install php8.2-xml
RUN apt-get -y install php8.2-zip
RUN apt-get -y install php8.2-sqlite3
RUN apt-get -y install vim
RUN apt-get -y install default-mysql-client
RUN apt-get -y install git
RUN apt-get -y install net-tools
RUN apt-get -y install iputils-ping
RUN apt-get -y install curl 
RUN apt-get -y install wget 

RUN mkdir -p /var/www/domain.com/public_html
COPY --from=clone /app /var/www/domain.com/public_html

RUN wget https://get.symfony.com/cli/installer -O - | bash
ENV PATH="/root/.symfony5/bin:${PATH}"
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/domain.com/public_html
RUN php composer.phar update 
RUN php composer.phar install

EXPOSE 80

CMD ["symfony", "server:start", "-d", "--no-tls"]