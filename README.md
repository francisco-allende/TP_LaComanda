### Trabajo Práctico “La Comanda”

## Materia: Programación III
## División: 3D
## Alumno: Francisco Allende


### Funcionalidad principal: registrarse, loguearse, tomar pedido, prepararlo, servir, cobrar, encuestar

Paso 1) →  Registrarse. Para poder utilizar la app se necesita estar registrado. Aqui el usuario setea su username, password, su rol laboral, si es o no es admin y la fecha de inicio de actividades

Paso 2) → Login. Loguearse correctamente retorna un token. Hay tres tipos de tokens: de mozo, de administrador (socio) y de gastronómico. Los 3 con funcionalidades diferentes. De aquí en más, todas las funcionalidades piden como mínimo un token de estar logueado (middleware de ruta), después según tarea pide un rol específico.

Paso 3) →Alta pedido. Se crea un pedido con id único. La mesa pasa a estar ocupada, con “cliente esperando pedido” y el id de la mesa se relaciona con la del pedido, solo si la mesa se encuentra previamente “libre”. El mozo saca un foto y esta se guarda en la carpeta “FotosPedido”

Paso 4) →Alta producto. Requiere token de mozo. Se toma el pedido haciendo un alta de producto por cada producto. El pedido debe estar realizado o no se podra hacer el alta de producto. Cada producto puede ser un plato de comida (cocinero), de postre (repostero), un trago (barman) o una cerveza (birrero). 
Estos productos están asociados a un pedido con id único, permitiendo relacionar un conjunto de productos en un solo pedido. El producto queda en estado de “pendiente”

Paso 5) →Preparar. Requiere token de cocinero. Según el área correspondiente, cada trabajador empieza a preparar el producto, cambiando el status de pedido y producto por “en preparación”, asignando un tiempo para finalizar. 

Paso 6) → Cuánto falta. Requiere token de logueado. El cliente pregunta cuánto falta y, según id de pedido y mesa, el mozo le da el tiempo que falta para cada producto. 

Paso 7) → Socio consulta demora pedido. El socio puede ver el estado y contenido de todos los pedidos, así también el tiempo de demora y su status

Paso 8) → Listo. Requiere token de cocinero. El trabajador gastronómico termina de preparar el producto. No es necesario que estén todos los productos listos para servir. Si la cerveza sale antes que la milanesa, el mozo sirve antes la cerveza y la milanesa cuando se encuentre lista. Cambia el status de producto por “listo”

Paso 9) → Servir. Requiere token de mozo. El mozo sirve el producto cambiando el status por “listo”. Si el pedido entero está listo, el status es “todo servido”. Si falta alguno, el pedido tiene el status de “producto servido pero no todos”. La mesa cambia el status a “con cliente comiendo”.

Paso 10) → Cobrar. Requiere token de mozo. Solo si todos los productos están servidos, el mozo puede cobrar. El status del pedido cambia a “cobrado” y la mesa a “con clientes pagando”. Se calcula el total del pedido según todos los productos que estén relacionados a ese pedido único.

Paso 11) → Alta Encuesta. Requiere token de logueado. Se le toma al cliente una encuesta con la puntuación de la mesa, del cocinero, del mozo, del restaurante y un comentario breve de 66 caracteres. Se lo asocia con su pedido y se genera un promedio de puntuacion. 

Paso 12) → Levantar Mesa. Requiere token de mozo. Si la mesa se encuentra con clientes pagando y se cobro el total del pedido, el mozo levanta la mesa, permitiendo que vuelva a estar libre para un nuevo cliente


## Otras funcionalidades

El CRUD es completo en todas las entidades. Se puede crear, modificar, borrar y leer tanto las áreas, como las encuestas, los productos, los pedidos y los trabajadores. También buscar una entidad específica por id

El cliente si así lo prefiere, puede levantarse de la mesa y cambiarse. Requiere token de mozo. 

Solo el socio puede dar de baja empleados. Esta es una baja lógica que setea la fecha de final de actividades para dicho empleado. 

Se pueden listar las áreas y que productos tienen asignados. También se puede listar por área y por status de producto (por ejemplo, todos los “en preparación” de la cocina)

Se carga un csv y se guarda. Este csv hace una tabla dinámica en html con todos los trabajadores, activos o no, del restaurante.

Se pueden ver los mejores y peores comentarios. Esto depende del promedio de puntuación.


   


 


