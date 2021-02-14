FROM ubuntu

ENV TZ=Europe/Samara
RUN echo $TZ > /etc/timezone && ln -s /usr/share/zoneinfo/$TZ /etc/localtime
RUN apt update && apt -y dist-upgrade && apt install -y apache2 php tzdata

WORKDIR /etc/apache2/sites-enabled
RUN ln -s ../sites-available/default-ssl.conf .

WORKDIR /etc/apache2/mods-enabled
RUN ln -s ../mods-available/ssl.conf . && ln -s ../mods-available/ssl.load . && ln -s ../mods-available/socache_shmcb.load .

COPY html /var/www/html

EXPOSE 80
EXPOSE 443

CMD apachectl -D FOREGROUND
