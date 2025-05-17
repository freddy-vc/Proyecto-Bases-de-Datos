-- Disparador para actualizar el estado de un encuentro cuando se registra un gol

-- Este disparador se activa después de insertar un nuevo gol
-- Verifica si el encuentro debe cambiar a estado 'finalizado' basado en alguna condición
-- Por ejemplo, si el tiempo del gol es cercano al final del partido (minuto 45 o superior)

CREATE OR REPLACE FUNCTION actualizar_estado_encuentro()
RETURNS TRIGGER AS $$
BEGIN
    -- Si el gol ocurre en el minuto 45 o posterior, consideramos que el partido está por terminar
    -- y actualizamos su estado a 'finalizado'
    IF NEW.minuto >= 45 THEN
        UPDATE Encuentros
        SET estado = 'finalizado'
        WHERE cod_encuentro = NEW.cod_encuentro
        AND estado = 'programado';
    END IF;
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Crear el trigger que se activará después de insertar un nuevo gol
CREATE TRIGGER trigger_actualizar_estado_encuentro
AFTER INSERT ON Goles
FOR EACH ROW
EXECUTE FUNCTION actualizar_estado_encuentro();

-- Comentario explicativo:
-- Este trigger se activa cada vez que se inserta un nuevo gol en la tabla Goles.
-- Si el gol ocurre en el minuto 45 o posterior (considerando que los partidos de futsala
-- suelen durar 40-50 minutos), el trigger actualiza automáticamente el estado del encuentro
-- a 'finalizado', pero solo si su estado actual es 'programado'.
-- Esto automatiza la actualización del estado de los partidos sin requerir intervención manual.