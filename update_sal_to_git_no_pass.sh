#!/bin/bash
mysqldump  -d -uroot c34  > c34_blank.sql


for tname in bill_type department map nonsalary_type post salary_type
do
	mysqldump  -uroot c34 $tname  >> "c34_tables.sql"
done
git add *
git commit
git push https://github.com/nishishailesh/sal main
