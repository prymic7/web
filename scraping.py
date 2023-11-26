
from bs4 import BeautifulSoup
import requests
import mysql.connector
import time

db_host = "localhost"
db_username = "root"
db_passwd = ""
db_name = "login"

connection = mysql.connector.connect(
    host=db_host,
    user=db_username,
    password=db_passwd,
    database=db_name
)

if connection.is_connected():
    print("Connected to the database")


sql_delete = "DELETE FROM scraped_data"
cursor = connection.cursor()
cursor.execute(sql_delete)
connection.commit()
print("All records deleted from the scraped_data table.")

texts = []
links = []

first_article_alt = []
first_article_src = []
first_article_links = []
first_article_texts = []


response = requests.get("https://www.seznam.cz/")
soup = BeautifulSoup(response.text, "html.parser")
news = soup.find_all(class_="article__title")
imgsWithFirstArticle = soup.find_all(class_="d-block atm-picture__img atm-picture__img--loaded")

articles_to_scrape = 20
# while True:
for i, article in enumerate(news):
    
    link = article.get("href")
    text = article.getText()
    
    if "www.sport" in link or "www.prozeny" in link or "www.novinky" in link or "www.seznamzpravy" in link:
        primary = False
        
    

        for img in imgsWithFirstArticle:
            alt = img.get("alt")
            if text == alt:
                src = img.get("src")
                first_article_src.append(src)
                first_article_alt.append(alt)
                first_article_texts.append(text)
                first_article_links.append(link)
                primary = True
                break
                
        if not primary:
            links.append(link)
            texts.append(text)
            

scraped_data = list(zip(first_article_texts, first_article_links, first_article_src, first_article_alt))
for text, link, src, alt in scraped_data:
    
    sql = "INSERT INTO scraped_data (text, link, img_src, img_alt) VALUES (%s, %s, %s, %s)"
    values = (text, link, src, alt)
    cursor = connection.cursor()
    cursor.execute(sql, values)
    connection.commit()

scraped_data = list(zip(texts, links))

for text, link in scraped_data:

    sql = "INSERT INTO scraped_data (text, link) VALUES (%s, %s)"
    values = (text, link)
    cursor = connection.cursor()
    cursor.execute(sql, values)
    connection.commit()

    

    # time.sleep(60*20)
    
connection.close()
















