FROM alpine/git as clone
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
RUN apt-get -y install php8.2
RUN apt-get -y install php8.2-curl
RUN apt-get -y install php8.2-mysql
RUN apt-get -y install php8.2-xml
RUN apt-get -y install vim
RUN apt-get -y install git
RUN apt-get -y install net-tools
RUN apt-get -y install iputils-ping
RUN apt-get -y install curl 
RUN apt-get -y install wget 

RUN mkdir -p /var/www/domain.com/public_html
COPY --from=clone /app /var/www/domain.com/public_html

WORKDIR /var/www/domain.com/public_html
# RUN php composer.phar update 
# RUN php composer.phar install

RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN echo 'export PATH="$HOME/.symfony5/bin:$PATH"' >> "$HOME/.bash_profile"

EXPOSE 80

CMD ["source", '"$HOME/.bash_profile"']
# CMD ["symfony", "server:start", "-d", "--no-tls"]