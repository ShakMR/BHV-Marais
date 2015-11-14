from random import Random
from argh import arg
import argh
import datetime

class Args:
    npresents = 4
    startprob = 0.15



@arg('-r', '--regalos', default=4, help='Set the number of presents')
@arg('-p', '--probability', default=1, help='Set the starting probability')
def parse_args(**kwargs):
    Args.npresents = kwargs['regalos']
    Args.startprob = kwargs['probability']

class Day:

    def __init__(self, year, month, day, ihour=8, ehour=20, nextd=None, prevd=None):
        self.year = int(year)
        self.month = int(month)
        self.day = int(day)
        self.init_hour = ihour
        self.end_hour = ehour
        self.next_day = nextd
        self.previous_day = prevd
        self.selected_time = None

    def random_hour(self):
        r = Random()
        self.selected_time = r.choice(range(self.init_hour,self.end_hour))

    def tomorrow(self, nextd=None):
        if nextd:
            self.next_day = nextd
        else:
            return self.next_day

    def yesterday(self, prevd=None):
        if prevd:
            self.previous_day = prevd
        else:
            return self.previous_day

    def datetime(self):
        return datetime.datetime(self.year, self.month, self.day, self.selected_time)


def read_calendar(datehourdelim=',', datedatedelim='-', hourdelim=':'):
    lines = open("calendar.config", 'r').readlines()
    calendar = []
    for line in lines:
        parts = line.split(datehourdelim)
        date = parts[0].split(datedatedelim)
        hours = parts[1].split(hourdelim)
        day = Day(date[0], date[1], date[2], int(hours[0]), int(hours[1]))
        calendar.append(day)
    return calendar


def random_assign(cal, ini, end):
    r = Random()
    dia = r.choice(range(ini, end))
    cal[dia].random_hour()
    return cal[dia].datetime()


def main():
    cal = read_calendar()
    N = Args.npresents
    D = len(cal)
    DR = D/float(N)
    dia = 0
    choosen = []
    while dia < D:
        rightinter = min(D, int(dia+DR))
        choose = random_assign(cal, dia, rightinter)
        choosen.append(choose)
        dia += int(DR)+1

    for c in choosen:
        SQL = "INSERT INTO Awards (activation_date, delivered) VALUES (\""+str(c)+"\", 0);"
        print SQL


if __name__ == "__main__":
    main()
