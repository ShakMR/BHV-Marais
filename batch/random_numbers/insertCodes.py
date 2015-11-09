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

SQL = "INSERT INTO Code (code) VALUES (\'%s\');"
cp = ConfigParser()
cp.read("mysql.ini")
host = cp.get(ARGS.env, 'host')
user = cp.get(ARGS.env, 'user')
passw = cp.get(ARGS.env, 'password')
db = cp.get(ARGS.env, 'database')

fd = open(ARGS.file)
codelist = fd.readlines()
fd.close()

conn = MySQLdb.connect(host=host, user=user, passwd=passw, db=db)
cursor = conn.cursor()
if ARGS.truncate:
    print "truncating"
    cursor.execute("DELETE FROM %s.Code WHERE idCode > 0" % db)
for code in codelist:
    print "Inserting", code
    cursor.execute(SQL % code)
conn.commit()
conn.close()






