# random-string-generator
## Random string generation from given string

*{Пожалуйста,|Просто|Если сможете,} сделайте так, чтобы это
{удивительное|крутое|простое|важное|бесполезное} тестовое предложение {изменялось
{быстро|мгновенно|оперативно|правильно} случайным образом|менялось каждый раз}.*

Application opens brackets and get random generated string. Brackets can be infinitely nested.

Then it gets all possible strings and writes it to mysql database.

Installation instruction
=====================
1. Make sure you have installed [MySql Server](https://dev.mysql.com/downloads/mysql/) and [PHP](https://www.php.net/downloads.php).
2. Run MySql Server ($ mysql -u root -p).
3. Create MySql database using schema.sql instructions.
4. Change variables values to your own in index.php (lines 25 - 27).
5. Run index.php ($ php index.php).
6. If everything was done correct, you will see "Success!" and new lines in database table.
 
