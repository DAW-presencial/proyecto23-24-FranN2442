# Índice

- [**Introducción**](#introducción)
  - [**Especificaciones**](#especificaciones)
- [**Frameworks**](#frameworks)
  - [**Laravel**](#laravel)
  - [**Quasar**](#quasar)
  - [**Conexión entre Laravel y Quasar**](#conexión-entre-laravel-y-quasar)
- [**Instalación**](#instalación)
  - [**Requisitos**](#requisitos)
  - [**Backend**](#backend)
  - [**Frontend**](#frontend)
- [**Bibliografía**](#bibliografía)

# Introducción

![Book Now Banner](https://github.com/DAW-presencial/proyecto23-24-FranN2442/blob/development/docs/img/banner.png "Book Now Banner")

**Book Now** es una API REST creada para restaurantes y clientes, en la cual podrás encontrar restaurantes, bares y cualquier tipo de establecimiento culinario, tanto cercano a ti como más alejado. Una vez hayas elegido el sitio idóneo, podrás reservar una mesa o contactar con el establecimiento para un trato más personalizado. Es una aplicación versátil con la que podrás disfrutar de tus platos favoritos en cualquier momento del día, con una serie ventajas para usuarios registrados.

Entre sus funcionalidades, dispone de un portal de gestión para empleados y reservas. Además, permite registrar un negocio o establecimiento que quiera trabajar con nosotros.

### Especificaciones

Para ver las instrucciones y detalles sobre la estructura de este proyecto, [haz clic aquí](https://github.com/DAW-presencial/proyecto23-24-FranN2442/tree/development/docs/pdfs/proyecto_final_instrucciones.pdf).

# Frameworks

Explicación y uso de frameworks en el proyecto:

### Laravel

Laravel es un framework de PHP y es un requisito principal para el desarrollo de este proyecto web. Laravel sigue el patrón arquitectónico MVC (Modelo-Vista-Controlador) y tiene como objetivo hacer que el desarrollo web sea más fácil y rápido mediante la simplificación de tareas comunes como la autenticación, la gestión de bases de datos, el enrutamiento y el almacenamiento en caché.

### Quasar

Quasar es un framework que utiliza Vue.js. Ofrece una serie de componentes, rendimiento, personalización y configuración de diseño que facilita la creación de aplicaciones web progresivas. Incluye una CLI que ayuda a crear e implementar funcionalidades web de manera sencilla.

### Conexión entre Laravel y Quasar

Aunque se podría usar un solo framework para desarrollar la aplicación web completa, hemos decidido separar el backend, que estará hecho con Laravel 10, y el frontend, que se desarrollará con Quasar. El objetivo es aprovechar lo mejor de ambos mundos: un backend robusto y un frontend sofisticado.

# Instalación

### Requisitos

- PHP: Necesario para ejecutar Laravel, que es un framework de backend PHP.
- Composer: Gestor de dependencias para PHP. Se utiliza para instalar Laravel y sus dependencias.
- Node.js: Plataforma que permite ejecutar JavaScript fuera del navegador. Necesario para ejecutar herramientas de desarrollo como Vue CLI y Quasar CLI.
- Vue CLI: Herramienta de línea de comandos para Vue.js que proporciona una configuración predefinida para proyectos     Vue.js, incluyendo aplicaciones Quasar.
- Quasar CLI: Herramienta de línea de comandos para Quasar Framework. Se utiliza para crear, desarrollar y compilar      aplicaciones Quasar.

### Backend

* Clona este repositorio
  ````
  git clone https://github.com/DAW-presencial/proyecto23-24-FranN2442
  ````
* Abre el repositorio y navega a la carpeta de raíz, instala las dependencias con Composer
  ````
  composer install
  ````
* Configura tu base de datos, copia el archivo de ejemplo de configuración y actualiza los datos con los tuyos.
  ````
  copy .env.example .env
  ````
* Ejecuta las migraciones para crear las tablas en tu base de datos
  ````
  php artisan migrate
  ````
* Inicia el servidor de desarollo
  ````
  php artisan serve
  ````
### Frontend
* Navega a la carpeta raíz del proyecot y ejecuta
  ````
  npm install
  ````
* Una vez instaladas todas las dependencias, inicia el servidor de desarollo de Quasar
  ````
  quasar dev
  ````

# Bibliografía
* [Documentación Laravel](https://laravel.com/docs)
* [Documentación Quasar](https://quasar.dev)
