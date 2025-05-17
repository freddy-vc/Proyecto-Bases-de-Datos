# Aplicación Web para Torneo de Futsala

Este proyecto es una aplicación web para la gestión de un torneo de futsala en la ciudad de Villavicencio, Meta (Colombia).

## Descripción

La aplicación permite el registro, actualización y consulta de información relacionada con:
- Equipos
- Jugadores
- Ciudades
- Canchas
- Directores técnicos
- Encuentros
- Tablas de puntuación

## Tecnologías utilizadas

- **Backend**: PHP nativo (sin frameworks)
- **Frontend**: JavaScript nativo (sin frameworks)
- **Base de datos**: PostgreSQL
- **Servidor web**: Apache2 en Ubuntu

## Estructura del proyecto

```
/
├── backend/
│   ├── config/
│   │   └── database.php
│   ├── models/
│   │   ├── ciudad.php
│   │   ├── director.php
│   │   ├── equipo.php
│   │   ├── jugador.php
│   │   ├── cancha.php
│   │   ├── encuentro.php
│   │   ├── gol.php
│   │   ├── asistencia.php
│   │   ├── falta.php
│   │   ├── faseequipo.php
│   │   └── usuario.php
│   ├── controllers/
│   │   ├── ciudad_controller.php
│   │   ├── director_controller.php
│   │   ├── equipo_controller.php
│   │   ├── jugador_controller.php
│   │   ├── cancha_controller.php
│   │   ├── encuentro_controller.php
│   │   ├── gol_controller.php
│   │   ├── asistencia_controller.php
│   │   ├── falta_controller.php
│   │   ├── faseequipo_controller.php
│   │   └── usuario_controller.php
│   ├── api/
│   │   ├── ciudades.php
│   │   ├── directores.php
│   │   ├── equipos.php
│   │   ├── jugadores.php
│   │   ├── canchas.php
│   │   ├── encuentros.php
│   │   ├── goles.php
│   │   ├── asistencias.php
│   │   ├── faltas.php
│   │   ├── faseequipo.php
│   │   └── usuarios.php
│   └── db/
│       └── futsala.sql
└── frontend/
    ├── assets/
    │   ├── css/
    │   │   └── styles.css
    │   ├── js/
    │   │   ├── main.js
    │   │   ├── auth.js
    │   │   ├── equipos.js
    │   │   ├── jugadores.js
    │   │   ├── encuentros.js
    │   │   └── clasificaciones.js
    │   └── img/
    │       └── logo.svg
    ├── pages/
    │   ├── equipos.html
    │   ├── jugadores.html
    │   ├── encuentros.html
    │   ├── clasificaciones.html
    │   ├── login.html
    │   └── registro.html
    └── index.html
```

## Instalación y configuración

1. Clonar el repositorio
2. Importar la base de datos desde `backend/db/futsala.sql`
3. Configurar la conexión a la base de datos en `backend/config/database.php`
4. Desplegar en un servidor Apache con PHP y PostgreSQL

## Funcionalidades

- Registro e inicio de sesión de usuarios
- Gestión de equipos, jugadores y directores técnicos
- Programación y seguimiento de encuentros
- Registro de goles, asistencias y faltas
- Visualización de tablas de clasificación

## Roles de usuario

- **Usuario normal**: Puede visualizar toda la información del torneo
- **Administrador**: Puede modificar, agregar y eliminar información del torneo