-- ----------------------
-- 1. Ciudades
-- ----------------------
CREATE TABLE Ciudades (
    id_ciudad SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- ----------------------
-- 2. Directores Técnicos
-- ----------------------
CREATE TABLE Directores (
    id_dt SERIAL PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL
);

-- ----------------------
-- 3. Equipos
-- ----------------------
CREATE TABLE Equipos (
    id_equipo SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    id_ciudad INT NOT NULL,
    logo BYTEA,
    id_dt INT,
    FOREIGN KEY (id_ciudad) REFERENCES Ciudades(id_ciudad)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    FOREIGN KEY (id_dt) REFERENCES Directores(id_dt)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- ----------------------
-- 4. Jugadores
-- ----------------------
CREATE TABLE Jugadores (
    id_jugador SERIAL PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    posicion VARCHAR(50),
    dorsal INT,
    id_equipo INT NOT NULL,
    foto BYTEA,
    FOREIGN KEY (id_equipo) REFERENCES Equipos(id_equipo)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CHECK (posicion IN ('delantero', 'defensa', 'mediocampista', 'arquero'))
);

-- ----------------------
-- 5. Canchas
-- ----------------------
CREATE TABLE Canchas (
    id_cancha SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(255),
    capacidad INT
);

-- ----------------------
-- 6. Encuentros
-- ----------------------
CREATE TABLE Encuentros (
    id_encuentro SERIAL PRIMARY KEY,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    id_cancha INT NOT NULL,
    equipo_local INT NOT NULL,
    equipo_visitante INT NOT NULL,
    estado VARCHAR(20) DEFAULT 'programado',
    FOREIGN KEY (id_cancha) REFERENCES Canchas(id_cancha)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    FOREIGN KEY (equipo_local) REFERENCES Equipos(id_equipo)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    FOREIGN KEY (equipo_visitante) REFERENCES Equipos(id_equipo)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    CHECK (estado IN ('programado', 'finalizado'))
);

-- ----------------------
-- 7. Goles
-- ----------------------
CREATE TABLE Goles (
    id_gol SERIAL PRIMARY KEY,
    id_encuentro INT NOT NULL,
    id_jugador INT NOT NULL,
    minuto INT NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    FOREIGN KEY (id_encuentro) REFERENCES Encuentros(id_encuentro)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (id_jugador) REFERENCES Jugadores(id_jugador)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    CHECK (tipo IN ('normal', 'penal', 'autogol')),
    CHECK (minuto >= 0 AND minuto <= 50)
);

-- ----------------------
-- 8. Asistencias
-- ----------------------
CREATE TABLE Asistencias (
    id_asistencia SERIAL PRIMARY KEY,
    id_encuentro INT NOT NULL,
    id_jugador INT NOT NULL,
    minuto INT NOT NULL,
    FOREIGN KEY (id_encuentro) REFERENCES Encuentros(id_encuentro)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (id_jugador) REFERENCES Jugadores(id_jugador)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    CHECK (minuto >= 0 AND minuto <= 50)
);

-- ----------------------
-- 9. Faltas
-- ----------------------
CREATE TABLE Faltas (
    id_falta SERIAL PRIMARY KEY,
    id_encuentro INT NOT NULL,
    id_jugador INT NOT NULL,
    minuto INT NOT NULL,
    tipo_falta VARCHAR(50) NOT NULL,
    FOREIGN KEY (id_encuentro) REFERENCES Encuentros(id_encuentro)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (id_jugador) REFERENCES Jugadores(id_jugador)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    CHECK (tipo_falta IN ('roja', 'amarilla', 'normal')),
    CHECK (minuto >= 0 AND minuto <= 50)
);

-- ----------------------
-- 10. FaseEquipo *(sin id_faseequipo)*
-- ----------------------
CREATE TABLE FaseEquipo (
    id_equipo INT NOT NULL,
    fase VARCHAR(50) NOT NULL,
    clasificado BOOLEAN NOT NULL,
    FOREIGN KEY (id_equipo) REFERENCES Equipos(id_equipo)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    PRIMARY KEY (id_equipo, fase),
    CHECK (fase IN ('cuartos', 'semis', 'final'))
);

-- ----------------------
-- 11. Usuarios
-- ----------------------
CREATE TABLE Usuarios (
    id_usuario SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    rol VARCHAR(50) DEFAULT 'usuario',
    foto_perfil BYTEA,
    CHECK (rol IN ('admin', 'usuario'))
);