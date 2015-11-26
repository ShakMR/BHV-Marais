
Repositorio de trabajo para el evento de BHV Marais

# Ramas
para coordinarnos y no pisarnos entre nosotros he creado 6 ramas.

* master: rama de producción, cuando todo esté listo. (no se cambia nada aquí)
* development: rama de pruebas, para probar antes de enviar a master. (no se cambia nada aquí)
* frontend: rama estable de frontend. (no se cambia nada aquí)
* frontend_dev: rama de trabajo de frontend. (aquí si cambia)
* backend: rama estable de backend. (no se cambia nada aquí)
* backend_dev: rama de trabajo de backend. (aquí si cambia)

Joel -> frontend
Borja -> backend

# Caso de uso: prueba
Suponemos que estoy trabajando en frontend_dev y tengo una nueva cosa que quiero probar si funciona con la última actualización de backend.
Seguimos los siguientes pasos (tanto si se usa un programa como por terminal):
* Guardamos los cambios
```
 git commit -am "comentario oportuno"
```
* Actualizamos la sección backend de nuestra rama (no la backend_dev, porque puede estar inestable)
```
 git merge backend
```
* Si no han habido errores (que no debería) hacemos las pruebas que queramos.
* Si las pruebas nos satisfacen pasamos a subir los cambios a nuestra rama estable.
``` 
 git commit -am "comentario que toque" # en el caso de que hayamos cambiado algo durante las pruebas"
 git push  #subimos los cambios
 git checkout frontend  #cambiamos a la rama frontend
 git merge frontend_dev #descargamos los cambios hechos en dev
```




