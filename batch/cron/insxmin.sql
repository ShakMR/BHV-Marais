#count por minuto
SELECT SUBSTR(date, 1, 16) as "Date",COUNT(*) FROM Inscription
GROUP BY SUBSTR(date,1, 16);