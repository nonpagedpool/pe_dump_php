#!/usr/bin/python

import sys
import pefile

try:
	fd = open(sys.argv[2],"w")
	pe = pefile.PE(str(sys.argv[1]))
	fd.write(pe.dump_info())
	fd.close()
except:
	print "Error parsing PE file..."