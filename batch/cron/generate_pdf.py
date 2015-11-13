import mysql.connector as mysqlconn
from configparser import ConfigParser
import subprocess


def latex_table(caption, colheader, data):
    n = len(colheader)
    header = "\\begin{table}[]\n" \
             "\\centering\n" \
             "\\caption{"+caption+"}\n" \
             "\\label{lbl"+caption+"}\n" \
             "\\begin{tabular}{|"+"l"*n+"|}\n"
    titles = "\\textbf{%s} &"*(n-1)+"\\textbf{%s} \\\\\n"
    titles2 = titles  % tuple(colheader)
    titles2 += "\\hline \n"

    footer = "\\end{tabular}\n" \
             "\\end{table}\n"

    rowstr = "%s & "*(n-1)+"%s \\\\\n"
    txt = header + titles2
    for d in data:
        txt += rowstr % tuple(d)
    txt += footer
    return txt

def preproces(CSV):
    header = CSV[0].rstrip().split(',')
    data = map(lambda x: x.rstrip().split(','), CSV[1:])
    return header, data

CSVINS = open('inscriptions.csv', 'r').readlines()
CSVDIA = open("insxdia.csv", 'r').readlines()
CSVHOR = open("insxhora.csv", 'r').readlines()
CSVMIN = open("insxmin.csv", 'r').readlines()
LATEX = open("template.tex", 'r').read()

env = 'dev'
p = subprocess.Popen(["hostname","-f"], stdout=subprocess.PIPE,
                 stderr=subprocess.PIPE,
                 stdin=subprocess.PIPE)


header, data = preproces(CSVINS)
inscrip = latex_table("Total Inscriptions", header, data)
header, data = preproces(CSVDIA)
dia     = latex_table("Num Incriptions by day", header, data)
header, data = preproces(CSVHOR)
hour     = latex_table("Num Incriptions by hour", header, data)
header, data = preproces(CSVMIN)
min     = latex_table("Num Incriptions by minute", header, data)

LATEX = LATEX.replace("%%body%%", inscrip+"\n"+dia+"\n"+hour+"\n"+min)

print LATEX