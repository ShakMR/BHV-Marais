#!/usr/bin/python

import sys
import time

def runtime(func, *args):
	t1 = time.time()
	ret = func(*args)
	t2 = time.time()
	print func.__name__, t2-t1
	return ret

		
if __name__ == "__main__":
	print "Reading first"
	fd1 = runtime(open,sys.argv[1],'r')
	print "Reading second"
	fd2 = runtime(open, sys.argv[2],'r')
	l1 = fd1.readlines()
	l2 = fd2.readlines()
	s1 = set(l1)
	s2 = set(l2)
	print "Intersecting list"
	s3 = runtime(lambda x,y: y-x, s1, s2)
	print len(s3)
	lfin = sorted(list(s3))
	print "Writing in:",sys.argv[1]+".filtered"
	fd = open(sys.argv[1]+".filtered", 'w')
	for e in lfin:
		fd.write(e)
	fd.close()
	#~ print s3
		
#~ a = range(100)
#~ el = 30
#~ runtime(dicosearch, a, el, 0, 100)	
#~ runtime(dicosearchite, a, el, 0, 100)
	
