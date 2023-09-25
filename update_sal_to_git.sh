#!/bin/bash
#only blank
echo "give password:"
read ppp
mysqldump  -d -uroot c34 -p$ppp > c34_blank.sql


for tname in bill_type department map nonsalary_type post salary_type
do
	mysqldump  -uroot c34 $tname -p$ppp >> "c34_tables.sql"
done
git add *
git commit
git push https://github.com/nishishailesh/sal main
