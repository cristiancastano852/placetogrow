<p align="center"><a href="https://soluciones.evertecinc.com/escuela-de-desarrolladores-php-evertec" target="_blank"><img src="https://soluciones.evertecinc.com/hs-fs/hubfs/logoEvertec.png?width=250&height=50&name=logoEvertec.png" width="400" alt="Laravel Logo"></a></p>

<p align="center"></p>
</p>

## Microsites | PlaceToGrow
Autor:
  Cristian Alexander Castaño Montoya
  
  [<img src="https://img.shields.io/badge/LinkedIn-Connect-blue?style=flat&logo=linkedin">](https://www.linkedin.com/in/cristiancastano852/)

### Stack Tecnológico

- **Separación por Capas**: Se añadieron capas diferentes a MVC, se uso [single action classes](https://medium.com/@remi_collin/keeping-your-laravel-applications-dry-with-single-action-classes-6a950ec54d1d).
- **Framework Backend**: Laravel.
- **Framework Frontend**: Inertia.js con Vue.js 3.
- **Estilos**: Tailwind CSS.
- **Base de datos**: MySQL Database.
- **Contenerización**: Docker y Docker Compose.
- **Gestión de Versiones**: Git y GitFlow.
- **Integración Continua**: GitHub Actions.
- **Gestiones de roles y permisos**: Spatie
- **Calidad de Código**: Utilización de PSR para el formato de código y herramientas de análisis estático (Sonar Cloud).
- **Tipado**: Uso de tipado estricto en la declaración de funciones y métodos.
- **Cache**: Implementación de caching para mejorar el rendimiento.
- **Gestión de Logs**: Configuración para registrar y gestionar logs de la aplicación.

### Diagrama entidad-relación
![DIAGRAM ER MICROSITES EVERTEC - Diagrama ER de base de datos (pata de gallo)](https://github.com/cristiancastano852/placetogrow/assets/44209773/dac31313-51cf-4834-b008-58e380f58f08)

Claro, aquí tienes una versión estructurada y clara para incluir en tu README:

---

## Instalación en Docker

Sigue estos pasos para configurar y ejecutar tu aplicación Laravel en Docker.

### Pasos para la instalación

1. **Clonar el repositorio:**
   
   Clona este repositorio en tu máquina local.

   ```bash
   git clone https://github.com/cristiancastano852/placetogrow
   cd placetogrow
   ```

2. **Construir y levantar los contenedores:**
   
   Desde la carpeta raíz del proyecto, ejecuta:

   ```bash
   docker-compose up -d --build
   ```

3. **Instalar las dependencias de Composer:**
   
   Una vez que los contenedores estén en funcionamiento, instala las dependencias de Composer:

   ```bash
   docker-compose exec app composer install
   ```

4. **Instalar las dependencias de node:**
   
   Instalar las dependencias de node

   ```bash
   docker-compose exec app npm install
   ```
5. **Hacer el build de node:**

   ```bash
   docker-compose exec app npm run build 
   ```

6. **Correr las migraciones y seeders:**
   
   Ejecuta las migraciones y seeders para configurar la base de datos:

   ```bash
   docker-compose exec app php artisan migrate:refresh --seed
   ```
7. **Crear ambiente para los jobs:**
   
   Ejecuta el comando para poder ejecutar los jobs:

   ```bash
   docker-compose exec app php artisan queue:work
   ```
8. **Crear ambiente para las tareas programadas:**
   
   Ejecuta el comando para poder ejecutar las tareas programadas:

   ```bash
   docker-compose exec app php artisan schedule:work
   ```
9. **Acceder a la aplicación:**
   
   Abre tu navegador web y accede a `http://localhost:9005/` (o el puerto que hayas configurado).

### Notas importantes

- **Configurar variables de entorno:**
   
   Asegúrate de que las variables de entorno en tu archivo `.env` estén configuradas correctamente. Aquí tienes un ejemplo de la configuración de la base de datos:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=root
   DB_PASSWORD=root
   ```

- **Verificar el estado de los contenedores:**
   
   Puedes verificar el estado de los contenedores en cualquier momento usando:

   ```bash
   docker-compose ps
   ```



### Uso

- **Autenticación**:
  - Accede a `/login` para iniciar sesión como administrador.
  - Registra nuevos usuarios desde la interzar en `/users` .

- **Gestión de Micrositios**:
  
  Administrador y Cliente
  - Accede a las funcionalidades CRUD para gestionar micrositios en `/microsites`.
  - Configura detalles como nombre, logo, categoría, configuración de pagos y tipos de sitios.
  
  Invitado:
  - Ver todos los micrositios en`/micrositesall`
  - Ver el formulario de un sitio en especifico.
    
- **Gestión de usuarios**:

  Administrador
  - Acceder a las funcionalidades para gestionar usuarios en `/users`.
  - Crea nuevos usuarios
  - Ver todos los usuarios
  - Añadir roles a los usuarios
  - Cambiar roles a los usuarios
  - Eliminar usuarios.
 
- **Gestión de roles y permisos**:

  Administrador
  - Acceder a las funcionalidades para gestionar los roles y permisos en `/role-permission`.
  - Ver todos los roles
  - Cambiar los permisos de los roles



