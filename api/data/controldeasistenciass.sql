SELECT
    e.nombre,
    e.apellido,
    c.nombre_curso,
    m.nombre_materia,
    a.fecha,
    a.estado,
    a.observacion
FROM ASISTENCIAS a
INNER JOIN ESTUDIANTES e
    ON a.cod_estudiante = e.id
INNER JOIN ASIGNACIONES ag
    ON a.cod_asignacion = ag.id
INNER JOIN CURSOS c
    ON ag.cod_curso = c.id
INNER JOIN MATERIAS m
    ON ag.cod_materia = m.id
ORDER BY a.fecha;