-- =============================================
-- 1. Estadísticas por asignatura
-- =============================================
DROP PROCEDURE IF EXISTS GetEstadisticasAsignatura;
DELIMITER $$
CREATE PROCEDURE GetEstadisticasAsignatura(
    IN p_asignatura_id BIGINT,
    IN p_carga_datos_id BIGINT
)
BEGIN
    SELECT 
        COUNT(*) AS total_estudiantes,
        SUM(CASE WHEN e.calificacion >= 70 AND e.asistencia >= 75 THEN 1 ELSE 0 END) AS aprobados,
        SUM(CASE WHEN e.riesgo_predicho = 'riesgo' THEN 1 ELSE 0 END) AS en_riesgo,
        ROUND(AVG(e.calificacion), 2) AS promedio_calificacion,
        ROUND(AVG(e.asistencia), 2) AS promedio_asistencia
    FROM estudiantes e
    WHERE e.asignatura_id = p_asignatura_id
      AND (p_carga_datos_id IS NULL OR e.carga_datos_id = p_carga_datos_id);
END$$
DELIMITER ;

-- =============================================
-- 2. Top 10 estudiantes en riesgo por carga
-- =============================================
DROP PROCEDURE IF EXISTS GetTop10EstudiantesEnRiesgo;
DELIMITER $$
CREATE PROCEDURE GetTop10EstudiantesEnRiesgo(IN p_carga_datos_id BIGINT)
BEGIN
    SELECT 
        e.nombre, 
        e.codigo, 
        e.calificacion, 
        e.asistencia, 
        e.riesgo_predicho
    FROM estudiantes e
    WHERE e.carga_datos_id = p_carga_datos_id
      AND e.riesgo_predicho = 'riesgo'
    ORDER BY e.calificacion ASC, e.asistencia ASC
    LIMIT 10;
END$$
DELIMITER ;

-- =============================================
-- 3. Resumen de cargas por docente
-- =============================================
DROP PROCEDURE IF EXISTS GetResumenCargasPorDocente;
DELIMITER $$
CREATE PROCEDURE GetResumenCargasPorDocente(IN p_docente_id BIGINT)
BEGIN
    SELECT 
        a.nombre AS asignatura,
        cd.archivo_nombre,
        cd.total_filas,
        cd.validos,
        cd.errores,
        cd.precision_ia,
        cd.created_at
    FROM carga_datos cd
    JOIN asignaturas a ON cd.asignatura_id = a.id
    WHERE a.docente_id = p_docente_id
    ORDER BY cd.created_at DESC;
END$$
DELIMITER ;

-- =============================================
-- 4. Registrar informe generado
-- =============================================
DROP PROCEDURE IF EXISTS RegistrarInformeGenerado;
DELIMITER $$
CREATE PROCEDURE RegistrarInformeGenerado(
    IN p_carga_datos_id BIGINT,
    IN p_ruta_pdf VARCHAR(255),
    IN p_ruta_excel VARCHAR(255),
    IN p_destinatario_email VARCHAR(255),
    OUT p_informe_id BIGINT
)
BEGIN
    INSERT INTO informes (
        carga_datos_id, ruta_pdf, ruta_excel, generado_en, destinatario_email
    ) VALUES (
        p_carga_datos_id, p_ruta_pdf, p_ruta_excel, NOW(), p_destinatario_email
    );
    SET p_informe_id = LAST_INSERT_ID();
END$$
DELIMITER ;

-- =============================================
-- 5. Validar estudiantes con IA simulada
-- =============================================
DROP PROCEDURE IF EXISTS ValidarEstudiantesIA;
DELIMITER $$
CREATE PROCEDURE ValidarEstudiantesIA(IN p_carga_datos_id BIGINT)
BEGIN
    UPDATE estudiantes
    SET riesgo_predicho = CASE
        WHEN calificacion < 60 OR asistencia < 70 THEN 'riesgo'
        ELSE 'aprobado'
    END
    WHERE carga_datos_id = p_carga_datos_id;
END$$
DELIMITER ;

-- =============================================
-- ¡LISTO! .
-- =============================================