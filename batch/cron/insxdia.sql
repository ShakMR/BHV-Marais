#count por dia
SELECT SUBSTR(date, 1, 10) as "Date", COUNT(*) FROM Inscription
GROUP BY SUBSTR(date, 1, 10);