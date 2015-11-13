#count por hora
SELECT SUBSTR(date, 1, 13) as "Date", COUNT(*) FROM Inscription
GROUP BY SUBSTR(date,1, 13);