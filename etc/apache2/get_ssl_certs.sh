cd /home/sacarlson/github/letsencrypt
#./letsencrypt-auto certonly --webroot -w /var/www/test/tricktraker.com/map -d www.tricktraker.com
#./letsencrypt-auto certonly --webroot -w /var/www/test/tricktraker.com -d api.tricktraker.com
#./letsencrypt-auto certonly --webroot -w /var/www/test/tricktraker.com/map/wiki -d wiki.tricktraker.com
#./letsencrypt-auto certonly --webroot -w /var/www/test/funtracker.site/port80 -d www.funtracker.site
./letsencrypt-auto certonly --webroot -w /var/www/test/funtracker.site/api -d api.funtracker.site
#will expire on 2016-07-25
