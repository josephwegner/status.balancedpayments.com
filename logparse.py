#Imports
import re
import os
import sys
import getopt
import json
import datetime

#Check for vhost path
opts, extraparams = getopt.getopt(sys.argv[1:], 'hp:o:')
path = '/tmp/'
output = False

for o,p in opts:
	if o in ['-p', '--path']:
		path = p
	elif o in ['-o', '--output']:
		output = p
	elif o in ['-h', '--help']:
		print "Use the -p flag to set the VHost path.  Default path is "+path
		sys.exit()

if path[-1:] == "/":
	path = path[:-1]

#Read the files
failures = 0
successes = 0
vhosts = {}

for direct in os.listdir(path):
	if os.path.isdir(path+"/"+direct) & os.path.isfile(path+"/"+direct+"/log/access.log"):
		vhosts[direct] = {'failures': 0, 'successes': 0}

		f = open(path+"/"+direct+"/log/access.log", 'r')

		for line in f:
			#Parse the output
			regex = '([(\d\.)]+) (.*?) (.*?) \[(.*?)\] "(.*?)" (\d+) (\d+) "(.*?)" "(.*?)"'

			logParts =  re.match(regex, line).groups()

			status = logParts[5]

			if status[:1] == "5":
				failures += 1
				vhosts[direct]['failures'] += 1
			else:
				vhosts[direct]['successes'] += 1
				successes += 1
		f.close()
	else:
		print "access log does not exist in "+path+"/"+direct

results = {'failures': failures, 'successes': successes, 'vhosts': vhosts, 'time': str(datetime.datetime.now())  }

if not output:
	print json.dumps(results)
else:
	f = open(output, 'w')
	f.write(json.dumps(results))
	f.close()
