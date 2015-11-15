#!/bin/bash

query () {
    mysql -B -u bhv bhv_dev --password=dwph4DVq < $1 | sed 's/\t/,/g'
}

query inscriptions.sql > inscriptions.csv &
cp inscriptions.csv pdf/
query insxdia.sql > insxdia.csv &

query insxhora.sql > insxhora.csv &

query insxmin.sql > insxmin.csv &

python generate_pdf.py > report.tex && \
pdflatex report.tex && bash send-report.sh

