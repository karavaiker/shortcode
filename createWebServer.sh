#!/bin/bash
#Цвета для вывода
RED='\033[0;31m'
GREEN='\033[0;32m'
MAGENTA='\033[0;35m'
CYAN='\033[0;36m'
MAGENTA='\033[0m'
NORMAL='\033[0m'


clear
read -p "Введите имя проекта: " nameProject
read -p "Создаем сайт $nameProject?[Y/N]: " isReady

if [[ $isReady == 'y' ]]; then

	directory=/var/www/$nameProject
	mkdir $directory
	echo -e "${GREEN}Создал папку $directory${NORMAL}"
	read -p "Создаем сайт в зоне DEV?[y/n]?: " isDev
	if [[ $isDev == "y"  ]]; then
		siteURL="$nameProject.dev"
		echo -e "${MAGENTA}Сайт будет доступен по http://$nameProject ${NORMAL}"
	else
		siteURL="$nameProject"
		echo -e "${MAGENTA}Сайт будет доступен по имени проекта http://$nameProject ${NORMAL}"
	fi

	CONFIG="<VirtualHost *:80>
    ServerName $siteURL
    DocumentRoot $directory
    
    <Directory $directory>
        Options Indexes FollowSymlinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>"

	
	sudo touch "/etc/apache2/sites-available/$nameProject.conf" 
	echo -e "${GREEN}Создал конфиг: \n${CYAN}"
	echo -e "$CONFIG" | sudo tee "/etc/apache2/sites-available/$nameProject.conf"
	echo -e "${NORMAL}"
	echo -e "${GREEN} Создал apache конфиг $nameProject.conf ${NORMAL}"
	sudo a2ensite "$nameProject.conf"
	echo -e "${GREEN} Запустил в а2ensite ${NORMAL}"


	echo -e "${CYAN}Правка файла hosts${NORMAL}"
	sudo gedit /etc/hosts
	echo -e "${RED}"
	read -p "Перезапустить сервер [Y/N]?" ISRESTARTSERVER

	if [[ $ISRESTARTSERVER == "y"  ]]; then
		sudo service apache2 restart
	fi
fi
