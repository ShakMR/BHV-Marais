#!/usr/bin/python
__author__ = 'shakmr'

import MySQLdb
from configparser import ConfigParser
import sys
import argh
from argh.decorators import *


class ARGS:
    file = ''
    env = 'dev'
    truncate = False


@arg('file')
@arg('-e', '--environment', default='dev', choices=['dev', 'prod'], help='Set the database to use')
@arg('-t', '--truncate', default=False, help='If set, the table will be truncated')
def parse_args(**kwargs):
    ARGS.file = kwargs['file']
    ARGS.env = kwargs['environment']
    ARGS.truncate = kwargs['truncate']

parser = argh.ArghParser()
parser.set_default_command(parse_args)
parser.dispatch()

SQL = "INSERT INTO Code (code) VALUES "
print "Parsing ini file"
cp = ConfigParser()
cp.read("mysql.ini")
host = cp.get(ARGS.env, 'host')
user = cp.get(ARGS.env, 'user')
passw = cp.get(ARGS.env, 'password')
db = cp.get(ARGS.env, 'database')

print "Reading codes from file..."
fd = open(ARGS.file)
codelist = fd.readlines()
fd.close()

conn = MySQLdb.connect(host=host, user=user, passwd=passw, db=db)
cursor = conn.cursor()
if ARGS.truncate:
	print "Truncating table..."
	cursor.execute("DELETE FROM %s.Code WHERE idCode > 0" % db)
	print "Reset AUTO_INCREMENT table..."
	cursor.execute("ALTER TABLE Code AUTO_INCREMENT = 0")
n = len(codelist)*1.0
codlist = map(lambda x: x.strip(), codelist)
insertSql = SQL+"('"+"\'),(\'".join(codlist)+"');"
i = 0.0
print "Inserting"
cursor.execute(insertSql)
#~ for code in codelist:
    #~ cursor.execute(SQL % code.strip())
    #~ i+=1
    #~ print "{0}/{1}".format(i,n), i/n*100," percent complete         \r",
    #~ sys.stdout.flush()
print ""
conn.commit()
conn.close()






