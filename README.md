# TecnoMarket

Sistema web para una tienda ficticia de productos tecnológicos. Permite mostrar un catálogo dinámico cargado desde MySQL, registrar consultas de clientes y preparar pedidos por WhatsApp desde un carrito de compras.

## Estructura

- `index.php`: catálogo dinámico con búsqueda y filtro por categoría.
- `contacto.php`: formulario con validación en cliente y servidor.
- `checkout.php`: carrito, resumen de compra y envío del pedido por WhatsApp.
- `config/database.php`: credenciales de conexión a MySQL.
- `includes/`: cabecera, pie de página y funciones auxiliares.
- `assets/css/styles.css`: estilos del sitio.
- `assets/js/cart.js`: lógica del carrito, cantidades, total y mensaje de WhatsApp.
- `database/schema.sql`: creación de tablas y productos de ejemplo.
- `Dockerfile`: imagen PHP 8.2 con Apache y extensión `pdo_mysql`.
- `docker-compose.yml`: servicio web para despliegue con Docker Compose.
- `.env.example`: variables necesarias para conectar con una base MySQL remota.

## Instalación local en XAMPP

1. Copiar la carpeta del sistema dentro de `htdocs`.
2. Iniciar Apache y MySQL desde XAMPP.
3. Crear una base de datos llamada `tecnomarket`.
4. Seleccionar esa base de datos en phpMyAdmin e importar `database/schema.sql`.
5. Revisar las variables de conexión usadas por `config/database.php`:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=tecnomarket
DB_USER=root
DB_PASS=
```

6. Abrir el sistema en el navegador:

```text
http://localhost/nombre-de-la-carpeta/
```

## Uso

- En el catálogo se pueden buscar productos por nombre o descripción.
- El filtro de categoría se carga desde los datos existentes en MySQL.
- Cada producto disponible se puede agregar al carrito.
- El carrito se guarda en el navegador mediante `localStorage`.
- En checkout se calculan cantidades y total estimado.
- Al enviar el pedido se abre WhatsApp con el detalle listo para enviar al número configurado.
- El formulario de contacto valida nombre, correo y mensaje antes de guardar el registro en la tabla `contactos`.

## WhatsApp de pedidos

El número de destino está configurado en `assets/js/cart.js`:

```js
const WHATSAPP_NUMBER = '593983987321';
```

## Despliegue con Docker Compose

El despliegue usa una sola imagen PHP/Apache. No incluye MySQL, porque la base de datos debe existir en un servidor remoto.

1. Crear una base de datos MySQL remota.
2. Importar `database/schema.sql` en esa base de datos.
3. Copiar `.env.example` como `.env` o configurar esas variables en Dokploy.
4. Completar las variables con los datos reales de la base remota:

```env
APP_PORT=8080

DB_HOST=host-remoto-de-mysql
DB_PORT=3306
DB_NAME=tecnomarket
DB_USER=usuario_remoto
DB_PASS=clave_remota
DB_CHARSET=utf8mb4
```

5. Desplegar con Docker Compose:

```bash
docker compose up -d --build
```

La aplicación queda expuesta en el puerto definido por `APP_PORT`. Dentro del contenedor Apache escucha en el puerto `80`.

## Despliegue en Dokploy

1. Crear una aplicación de tipo Docker Compose.
2. Usar `docker-compose.yml` como archivo Compose.
3. Definir las variables de `.env.example` en el panel de variables de entorno.
4. Verificar que la base MySQL remota permita conexiones desde el servidor donde corre Dokploy.
5. Desplegar la aplicación.

No agregues un servicio MySQL al Compose; este sistema está preparado para conectarse a una base externa mediante variables de entorno.

## Despliegue manual en servidor

1. Crear una base de datos MySQL en el servidor.
2. Importar `database/schema.sql` desde el panel del proveedor.
3. Subir todos los archivos del sistema al directorio público del servidor.
4. Configurar `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` y `DB_PASS` en el entorno del servidor.

URL de producción: `PENDIENTE_DE_REEMPLAZAR_CON_LA_URL`
