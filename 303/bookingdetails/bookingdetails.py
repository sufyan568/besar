import sys, traceback
from scrapy.http import Request
from scrapy.selector import Selector
from scrapy.linkextractors import LinkExtractor
from scrapy.spiders import CrawlSpider, Rule
from hotel_bookingdetails.items import HotelBookingdetailsItem
from scrapy.http.cookies import CookieJar
from bs4 import BeautifulSoup
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from urlparse import urljoin
from urlparse import urlparse, parse_qs
from dotenv import load_dotenv
import urllib
from datetime import datetime
import MySQLdb
import MySQLdb.cursors
import time
import json
import random
import os
import subprocess
import re

geckodriver = '/home/yo2/303/PYTHON/geckodriver'
options = webdriver.FirefoxOptions()
options.add_argument('-headless')
driver = webdriver.Firefox(executable_path=geckodriver, firefox_options=options)

SQL_DB = ""
SQL_HOST = 'localhost'
SQL_USER = ""
SQL_PASSWD = ""

proc = subprocess.Popen("php /var/zpanel/hostdata/towerregency/public_html/s2_towerregency_com_my/getdbdetails.php", shell=True, stdout=subprocess.PIPE)
jsonS = proc.stdout.read().splitlines()
for index,val in enumerate(jsonS):
	if "database" in val:
		 SQL_DB = re.search('"(.*)"', jsonS[index+1]).group(1)
	if "username" in val:
		 SQL_USER = re.search('"(.*)"', jsonS[index+1]).group(1)
	if "password" in val:
		 SQL_PASSWD = re.search('"(.*)"', jsonS[index+1]).group(1)


class HotelBookingsSpider(CrawlSpider):
	name = 'hotelbookinglists'
	base_url = 'https://www.google.com'
	allowed_domains = ['www.google.com']
	start_urls = []
	CONN = MySQLdb.connect(host=SQL_HOST, user=SQL_USER, passwd=SQL_PASSWD, db=SQL_DB, charset='utf8')
	CURSOR = CONN.cursor()
	CURSOR.execute("""SELECT hotelname FROM hotelname""")
	if CURSOR.rowcount > 0:
		result = CURSOR.fetchall()
		hoteldata = [row[0] for row in result]
		for lists in hoteldata:
			start_urls.append('https://www.google.com/search?q='+str(lists))

	rules = (
		Rule(LinkExtractor(allow=(), deny=()), callback='parse_start_url', follow=True),
	)
	
	def start_requests(self):
		try:
			for url in self.start_urls:
				count = 0
				parsed = urlparse(url)
				hotelname = parse_qs(parsed.query)['q'][0]
				yield Request(url=url,callback=self.parse_start_url,meta={'hotelname':hotelname, 'count':count})
				time.sleep(3)
		except Exception as e:
			f = open(HotelBookingsSpider.name+'-log.txt', 'a')
			traceback.print_exc(file=f)
			f.close()

	def parse_start_url(self, response):
		try:
			sel = Selector(response)
			hotelname = response.meta['hotelname']
			count = response.meta['count']
			booklink = sel.xpath('//div[@class="vRH59e gws-local-hotels__hab"]').xpath('//a[@class="vJdf1c"]/@href').extract_first() if len(sel.xpath('//div[@class="vRH59e gws-local-hotels__hab"]')) > 0 else ""
			print "URL: ", booklink
			if booklink != "":
				actual_crawl_url = self.base_url+booklink
				yield Request(url=actual_crawl_url,callback=self.getBookingDetails,meta={'hotelname':hotelname})
			elif count < 3:
				print "Retrying ", response.url
				count = count + 1
				yield Request(url=response.url,callback=self.parse_start_url,meta={'hotelname':hotelname, 'count':count},dont_filter = True)
		except Exception as e:
			f = open(HotelBookingsSpider.name+'-log.txt', 'a')
			f.write('\n\n%s' %(response.url))
			traceback.print_exc(file=f)
			f.close()
			

	def getBookingDetails(self, response):
		try:
			driver.get(response.url)
			sel = Selector(response)
			hotelname = response.meta['hotelname']
			soup = BeautifulSoup(response.text, 'html.parser')
			#hotelname = soup.find(class_="BgYkof gHL2Jc").text.encode('ascii', 'ignore').decode('ascii')
			today_date = time.strftime('%m %d %Y') 
			todaydt = datetime.strptime(today_date, '%m %d %Y')
			get_checkin = soup.find_all(class_="p0RA ogfYpf Py5Hke")[0].text.encode('ascii', 'ignore').decode('ascii')
			if todaydt.month > datetime.strptime(get_checkin.split(',')[-1].strip(), '%b %d').month:
				year = str(int(time.strftime('%Y'))+1)
			else:
				year = str(time.strftime('%Y'))
			checkin_withyr = get_checkin.split(',')[-1].strip()+ " " +year
			checkin_dt = datetime.strptime(checkin_withyr, '%b %d %Y')
			delta = checkin_dt - todaydt

			for i in range(0,delta.days):
				driver.find_elements_by_xpath('//button[@class="VfPpkd-Bz112c-LgbsSe yHy1rc eT1oJ QDwDD GwzyAc cU51ne"]')[6].click()
				driver.find_elements_by_xpath('//span[@class="sSHqwe jLfzJd OLGrn"]')[2].click()
				time.sleep(2)

			checkin_noresult = driver.find_elements_by_xpath('//div[@class="p0RA ogfYpf Py5Hke"]')[2].text
			noresult_withyr = checkin_noresult.split(',')[-1].strip()+ " " +str(time.strftime('%Y'))
			noresult_dt = datetime.strptime(noresult_withyr, '%b %d %Y')
			if todaydt==noresult_dt:
				if len(driver.find_elements_by_xpath('//div[@class="KQO6ob"]')) == 0:
					driver.find_elements_by_xpath('//button[@class="VfPpkd-Bz112c-LgbsSe yHy1rc eT1oJ QDwDD GwzyAc cU51ne"]')[5].click()
					driver.find_elements_by_xpath('//span[@class="sSHqwe jLfzJd OLGrn"]')[2].click()
			WebDriverWait(driver, 5).until(EC.presence_of_element_located((By.CLASS_NAME, 'Hkwcrd')))
			time.sleep(2)
			while len(driver.find_elements_by_xpath('//div[@class="KQO6ob"]')) > 0:
				soup = BeautifulSoup(driver.page_source, 'html.parser')
				check_in = soup.find_all(class_="p0RA ogfYpf Py5Hke")[0].text.encode('ascii', 'ignore').decode('ascii')
				check_out = soup.find_all(class_="p0RA ogfYpf Py5Hke")[1].text.encode('ascii', 'ignore').decode('ascii')
				print "Checkin", check_in
				print "Checkout", check_out

				bookingData = soup.find(class_="KoLVjf bM3xBe").find_all(class_="KQO6ob")
				print hotelname
				for index,record in enumerate(bookingData):
			 		sitename = record.find(class_="mK0tQb").text.encode('ascii', 'ignore').decode('ascii')
			 		rate =  record.find(class_="wqcQP").find(class_="MW1oTb").text.encode('ascii', 'ignore').decode('ascii') if len(record.find_all(class_="wqcQP")) > 0 else ""
			 		print "Sitename: ", sitename
			 		print "Rate: ", rate
			 		print "Checkin: ", check_in
			 		print "Checkout:", check_out
			 		updated_date_time = time.strftime('%Y-%m-%d %H:%M:%S') 
					CONN = MySQLdb.connect(host=SQL_HOST, user=SQL_USER, passwd=SQL_PASSWD, db=SQL_DB, charset='utf8')
					CURSOR = CONN.cursor()
					CURSOR.execute("""SELECT * FROM OTA_checklist WHERE hotelname='%s' and sitename='%s' and checkin='%s' and checkout='%s'"""%(str(hotelname), str(sitename), str(check_in), str(check_out)))
					if CURSOR.rowcount == 0:
						CURSOR.execute("""INSERT into OTA_checklist (hotelname,sitename,rate,checkin,checkout,created_at) values (%s,%s,%s,%s,%s,%s)""", (hotelname,sitename,rate,check_in,check_out,updated_date_time))
						CONN.commit()
					else:
						result = CURSOR.fetchone()
						resid = result[0]
						print "Updated", resid
						CURSOR.execute("""UPDATE OTA_checklist SET rate = %s, created_at = %s WHERE id = %s""", (rate,updated_date_time,int(resid)))
						CONN.commit()
				driver.find_elements_by_xpath('//button[@class="VfPpkd-Bz112c-LgbsSe yHy1rc eT1oJ QDwDD GwzyAc cU51ne"]')[5].click()
				driver.find_elements_by_xpath('//span[@class="sSHqwe jLfzJd OLGrn"]')[2].click()
				WebDriverWait(driver, 5).until(EC.presence_of_element_located((By.CLASS_NAME, 'Hkwcrd')))
				time.sleep(4)
		except Exception as e:
			f = open(HotelBookingsSpider.name+'-log.txt', 'a')
			f.write('\n\n%s' %(response.url))
			traceback.print_exc(file=f)
			f.close()


			
