<h1>Небольшой проект магазина книг</h1>
Реализован запрет на установку родителем категории дочернего элемента.

<ol>Для поднятия проекта:
<li>docker-compose up --build</li>
<li>docker exec -it php bash</li>
<li>composer install</li>
<li>php yii migrate</li>
<li>php yii import</li>
<li>php yii queue/listen</li>
</ol>
<small>Пока не могу понять почему не создается папка vendor при исполнении composer install в время запуска контейнера, поэтому пока приходится вручную запускать composer install, а также запускать очередь, т.к. supervisord требует autoload файл при старте</small>


<ul>Логины пароли:
<li>логин: admin</li> 
<li>пароль: admin</li>
</ul>

