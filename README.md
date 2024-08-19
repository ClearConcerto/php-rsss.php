# php-rsss.php

单页php将多个rss源并合并成一个新的rss源

示例

rss输入

链接引入

https://xx/rsss/index.php?urls=https://seths.blog/feed/,https://rsshub.app/weibo/user/7615854123/,https://rsshub.app/weibo/user/2090591961/1

文本内容引入增加了bbb

网络txt

https://xx/rsss/index.php?bbb&urls=https://xx/rsss/z.txt

本地txt

https://xx/rsss/index.php?bbb&urls=youtube.txt

rss输出

修改输出的文件名

网络地址

http://xx/timerss/rsss.php?bbb&urls=http://xx/timerss/youtube.txt&outfile=youtube.xml

本地地址

http://xx/timerss/rsss.php?bbb&urls=youtube.txt&outfile=youtube.xml

新的rss订阅源

设置定时访问,每间隔15分钟访问一次http://xx/timerss/rsss.php?bbb&urls=youtube.txt&outfile=youtube.xml

订阅新的合成源http://xx/timerss/youtube.xml


