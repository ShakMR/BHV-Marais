__author__ = 'shakmr'
__version__ = 0.1

import argh
from argh.decorators import *
import random
import string


class Args:
    seed = None
    nchars = 1
    ncodes = 10


@arg('-s', '--seed', default=None, help='Set the seed for the random generator')
@arg('-c', '--nchars', default=1, help='Set the number of characters for the codes')
@arg('-n', '--ncodes', default=10, help='Set the number of code to generate')
def parse_args(**kwargs):
    Args.seed = kwargs['seed']
    Args.nchars = kwargs['nchars']
    Args.ncodes = kwargs['ncodes']


parser = argh.ArghParser()
parser.set_default_command(parse_args)


def generate_numbers():
    i = 0
    codedict = {}
    r = random.Random()
    r.seed(Args.seed)
    while i < Args.ncodes:
        code = ''.join(r.choice(string.ascii_uppercase + string.digits) for _ in range(Args.nchars))
        if code not in codedict:
            codedict[code] = 0
            i += 1
        else:
            codedict[code] += 1
    totalcol = 0
    fd = open("{0}.codes".format(Args.seed),'w')
    for code in codedict.keys():
        print code, "colisions:", codedict[code]
        totalcol += codedict[code]
        fd.write(code+"\n")
    print "Total Colisions", totalcol


if __name__ == '__main__':
    parser.dispatch()
    generate_numbers()
