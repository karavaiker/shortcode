#!/usr/bin/env python3
# -*- coding: UTF-8 -*-

import os, sys

import csv
import json
import urllib2
import urllib
import locale
import math
import time
#hack Ascii to utf-8
import pango
sys.getdefaultencoding()


#Входные данные
apiKey = 'xxxx'
#название, рубрика, сайт, телефон, страница в соцсетях, пометка статуса (платно бесплатно они стоят в рубрике)

def searchFirm(what,page):
	request = urllib2.Request('http://catalog.api.2gis.ru/search')
	request.add_header('User-agent', 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)')
	request.add_data(urllib.urlencode({
			'key': apiKey,
			'version': '1.3',
			'what' : what, 
			'where': 'Пермь',
			'radius': 20000,
			'page' : page,
			'pagesize' : 40,
			'sort' : 'name'
    }))
	response = urllib2.urlopen(request)		
	return json.loads(response.read())

def profile(id,hash):
	request = urllib2.Request('http://catalog.api.2gis.ru/profile')
	request.add_header('User-agent', 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)')
	request.add_data(urllib.urlencode({
			'key': apiKey,
			'version': '1.3',
			'id' : id, 
			'hash': hash,
    }))
	response = urllib2.urlopen(request)		
	return json.loads(response.read())

def findInContacts(what,contacts):
	findresult=""
	if len(contacts)>0:
		for x in range(0, len(contacts)):
			type = contacts[x]['type']
			if type == what:
				findresult += contacts[x]['value']+" "
	return findresult

def findWebsite(contacts):
	findresult=""
	if len(contacts)>0:
		for x in range(0, len(contacts)):
			type = contacts[x]['type']
			if type == 'website':
				findresult += contacts[x]['alias']+" "
	return findresult

count = 0
searchResult = []
total = 0


def start(search,writer,total):
	print search + '  Всего:' + str(total)
	totalPages = int(math.floor(total / 40)+2)
	print 'Страниц ' + str(totalPages)
        for page in range(1,totalPages):
        #Получим все результаты на страницу
        	request = searchFirm(search,page)
        	for numberOnPage in range(0,len(request['result'])):
		 		id = request['result'][numberOnPage]['id']
		 		hash = request['result'][numberOnPage]['hash']
		 		#Запрашиваем подробную информацию о фирме
		 		firma = profile(id,hash);

		 		(name,address,phone,webpage,pageOnSocial) = ('','','','','')
		 		try:
		 			name = firma['name']
		 		except:
		 			pass


		 		try:
		 			address = firma['address']
		 		except:
		 			pass

		 		try:
		 			arrayContacts= firma['contacts'][0]['contacts']
		 			phone = findInContacts('phone', arrayContacts)
		 		except:
		 			pass

		 		try:
		 			arrayContacts= firma['contacts'][0]['contacts']
		 			webpage = findWebsite(arrayContacts)
		 		except:
		 			pass

		 		try:
		 			arrayContacts= firma['contacts'][0]['contacts']
		 			pageOnSocial = findInContacts('vkontakte', arrayContacts)+findInContacts('facebook',arrayContacts)+findInContacts('instagram', arrayContacts)
		 		except:
		 			pass

			
		 		writer.writerow((name,phone,webpage,pageOnSocial))
		 		# writer.writerow(name,phone,webpage,pageOnSocial)


def save(search):
	total = int(searchFirm(search, 1)['total'])
	with open(search +'('+str(total)+').csv', 'w') as csvfile:
		writer = csv.writer(csvfile)
		writer.writerow(('Название', 'Телефон', 'Сайт', 'Страница в соцсетях'))
		start(search,writer,total)


searchArr = [
	'университеты',
	'повышение квалификации',
	'языковые центры',
	'Спортивные товары',
	'Инструменты',
	'мед центры',
	'стоматология',
	'Резервуары',
	'Химчистки',
	'Нефтехимия',
	'Клининг',
	'Системы безопасности и охраны',
	'Ветеринарные клиники',
	'Спецтехника',
	'элетронагревательное оборудование',
	'Пицца, пироги',
]


def automate(search):
	try:
		save(search)
	except :
		print 'Ошибочка вышла на ' + search

		pass
	else:
		print 'Все хорошо на ' + search
		print 'Теперь усну на 30 минут в' + time.strftime('%H:%M')
		time.sleep(1800) 
		pass
	
	print 'Тада!!!!!!!'
	return "Выжил"

#Перебираем словарь, ищем, пишем csv
for search in xrange(1,len(searchArr)):
  automate(searchArr[search])



