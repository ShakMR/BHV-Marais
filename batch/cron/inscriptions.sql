#select inscripciones
SELECT  name as Name, lastname as Lastname, email as "E-mail", date as Date FROM Inscription i
  JOIN People p ON p.idPerson = i.People_idPerson
WHERE SUBSTR(date, 1, 10) = SUBSTR(NOW(), 1, 10)
ORDER BY i.date;
