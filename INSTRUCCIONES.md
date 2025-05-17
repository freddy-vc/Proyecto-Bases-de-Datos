# Instrucciones de Instalación y Ejecución

## Requisitos Previos

- Servidor Apache2
- PHP 7.4 o superior
- PostgreSQL 12 o superior
- Navegador web moderno (Chrome, Firefox, Edge, etc.)

## Pasos de Instalación

### 1. Configuración de la Base de Datos

1. Inicia sesión en PostgreSQL:
   ```
   sudo -u postgres psql
   ```

2. Crea la base de datos:
   ```sql
   CREATE DATABASE futsala;
   ```

3. Conéctate a la base de datos:
   ```
   \c futsala
   ```

4. Importa el esquema de la base de datos:
   ```
   \i /ruta/a/backend/db/futsala.sql
   ```

5. Importa los triggers:
   ```
   \i /ruta/a/backend/db/triggers.sql
   ```

### 2. Configuración del Servidor Web

1. Copia el proyecto en el directorio de Apache:
   ```
   sudo cp -r /ruta/a/Proyecto-Bases-de-Datos /var/www/html/
   ```

2. Configura los permisos:
   ```
   sudo chown -R www-data:www-data /var/www/html/Proyecto-Bases-de-Datos
   sudo chmod -R 755 /var/www/html/Proyecto-Bases-de-Datos
   ```

3. Configura la conexión a la base de datos:
   - Edita el archivo `backend/config/database.php`
   - Actualiza los valores de `$host`, `$db_name`, `$username` y `$password` según tu configuración

### 3. Acceso a la Aplicación

1. Abre un navegador web
2. Accede a la aplicación mediante la URL:
   ```
   http://localhost/Proyecto-Bases-de-Datos/frontend/index.html
   ```

## Estructura del Proyecto

- **backend/**: Contiene la lógica del servidor
  - **api/**: Endpoints para las operaciones CRUD
  - **config/**: Configuración de la base de datos
  - **controllers/**: Controladores para la lógica de negocio
  - **models/**: Modelos para interactuar con la base de datos
  - **db/**: Scripts SQL para la creación de la base de datos

- **frontend/**: Contiene la interfaz de usuario
  - **assets/**: Recursos estáticos (CSS, JS, imágenes)
  - **pages/**: Páginas HTML de la aplicación
  - **index.html**: Página principal

## Usuarios de Prueba

Para probar la aplicación, puedes insertar estos usuarios en la base de datos:

```sql
INSERT INTO Usuarios (username, email, password, rol) VALUES 
('admin', 'admin@futsala.com', 'admin123', 'admin'),
('usuario', 'usuario@futsala.com', 'usuario123', 'usuario');
```

## Notas Importantes

- La aplicación no utiliza encriptación para las contraseñas (como se solicitó)
- Para acceder como administrador, usa las credenciales: admin / admin123
- Para acceder como usuario normal, usa las credenciales: usuario / usuario123
- Asegúrate de que el servidor Apache tenga habilitado el módulo PHP

## Solución de Problemas

### Problemas de Conexión a la Base de Datos

Si tienes problemas para conectarte a la base de datos:

1. Verifica que PostgreSQL esté en ejecución:
   ```
   sudo service postgresql status
   ```

2. Asegúrate de que las credenciales en `backend/config/database.php` sean correctas

3. Verifica que el usuario de PostgreSQL tenga permisos para la base de datos:
   ```sql
   GRANT ALL PRIVILEGES ON DATABASE futsala TO tu_usuario;
   ```

### Problemas de Permisos

Si encuentras errores de permisos:

```
sudo chown -R www-data:www-data /var/www/html/Proyecto-Bases-de-Datos
sudo chmod -R 755 /var/www/html/Proyecto-Bases-de-Datos
```

### Errores en la Carga de Páginas

Si las páginas no cargan correctamente:

1. Verifica la consola del navegador para identificar errores JavaScript
2. Revisa los logs de Apache:
   ```
   sudo tail -f /var/log/apache2/error.log
   ```