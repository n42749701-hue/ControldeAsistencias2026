import { api } from '../utils/api.js';

const escapeHtml = (value) => String(value ?? '')
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');

const currentDate = () => {
    const date = new Date();
    return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
};

const courseLabel = (course) => `${course.nombre_curso} - ${course.nivel} ${course.paralelo}`;

const assignmentLabel = (assignment, options) => {
    const teacher = options.docentes.find((item) => Number(item.id) === Number(assignment.cod_docente));
    const subject = options.materias.find((item) => Number(item.id) === Number(assignment.cod_materia));
    return `${subject?.nombre_materia || `Materia ${assignment.cod_materia}`} · ${teacher ? `${teacher.nombre} ${teacher.apellido}` : `Docente ${assignment.cod_docente}`}`;
};

const fieldClass = 'mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 focus:border-cyan-600 focus:outline-none focus:ring-4 focus:ring-cyan-600/10';

export const initAttendanceRegister = async () => {
    const root = document.getElementById('management-view');
    if (!root) throw new Error('La vista de asistencias no tiene el contenedor para cargar su contenido.');

    root.innerHTML = `
        <section class="space-y-6">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <p class="text-sm font-semibold uppercase tracking-wide text-cyan-700">Registro diario</p>
                <h3 class="mt-1 text-2xl font-bold text-slate-950">Asistencia por curso</h3>
                <p class="mt-2 text-sm text-slate-600">Selecciona el curso, la asignatura y la fecha; luego marca el estado de cada estudiante.</p>
                <div id="AttendanceMessage" class="mt-4 hidden rounded-lg px-4 py-3 text-sm font-semibold" role="status" aria-live="polite"></div>
                <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <label class="text-sm font-semibold text-slate-700">Curso
                        <select id="AttendanceCourse" class="${fieldClass}" required><option value="">Cargando cursos...</option></select>
                    </label>
                    <label class="text-sm font-semibold text-slate-700">Asignatura y docente
                        <select id="AttendanceAssignment" class="${fieldClass}" required disabled><option value="">Selecciona un curso</option></select>
                    </label>
                    <label class="text-sm font-semibold text-slate-700">Fecha
                        <input id="AttendanceDate" type="date" value="${currentDate()}" class="${fieldClass}" required>
                    </label>
                    <label class="text-sm font-semibold text-slate-700">Registrado por
                        <select id="AttendanceUser" class="${fieldClass}" required><option value="">Cargando usuarios...</option></select>
                    </label>
                </div>
            </div>

            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-cyan-700">Lista del curso</p>
                        <h4 id="AttendanceRosterTitle" class="mt-1 text-xl font-bold text-slate-950">Selecciona un curso</h4>
                        <p id="AttendanceRosterCount" class="mt-1 text-sm text-slate-500"></p>
                    </div>
                    <button id="MarkAllPresent" type="button" class="hidden w-fit rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-800 transition hover:bg-emerald-100">Marcar todos presentes</button>
                </div>
                <div id="AttendanceRoster" class="overflow-x-auto">
                    <p class="px-5 py-10 text-center text-sm text-slate-500">Selecciona un curso para cargar sus estudiantes.</p>
                </div>
                <div id="AttendanceActions" class="hidden justify-end border-t border-slate-200 p-5 sm:p-6">
                    <button id="SaveAttendance" type="button" class="inline-flex items-center gap-2 rounded-lg bg-cyan-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-800 disabled:cursor-wait disabled:opacity-60">
                        Guardar asistencia
                    </button>
                </div>
            </section>
        </section>`;

    const courseSelect = document.getElementById('AttendanceCourse');
    const assignmentSelect = document.getElementById('AttendanceAssignment');
    const dateInput = document.getElementById('AttendanceDate');
    const userSelect = document.getElementById('AttendanceUser');
    const roster = document.getElementById('AttendanceRoster');
    const rosterTitle = document.getElementById('AttendanceRosterTitle');
    const rosterCount = document.getElementById('AttendanceRosterCount');
    const markAllButton = document.getElementById('MarkAllPresent');
    const actions = document.getElementById('AttendanceActions');
    const saveButton = document.getElementById('SaveAttendance');
    const message = document.getElementById('AttendanceMessage');

    let options;
    let enrollments;
    let attendanceRecords;
    const registrationYear = new Date().getFullYear();

    const showMessage = (text, isError = false) => {
        message.textContent = text;
        message.className = `mt-4 rounded-lg px-4 py-3 text-sm font-semibold ${isError ? 'border border-red-200 bg-red-50 text-red-700' : 'border border-emerald-200 bg-emerald-50 text-emerald-800'}`;
    };

    const selectedStudents = () => {
        const courseId = Number(courseSelect.value);
        const uniqueStudentIds = new Set();
        return enrollments
            .filter((item) => Number(item.cod_curso) === courseId && Number(item.gestion) === registrationYear)
            .map((item) => options.estudiantes.find((student) => Number(student.id) === Number(item.cod_estudiante)))
            .filter((student) => {
                if (!student || uniqueStudentIds.has(Number(student.id))) return false;
                uniqueStudentIds.add(Number(student.id));
                return true;
            })
            .sort((first, second) => `${first.apellido} ${first.nombre}`.localeCompare(`${second.apellido} ${second.nombre}`, 'es'));
    };

    const updateProgress = () => {
        const statuses = [...roster.querySelectorAll('[data-attendance-status]')];
        const studentCount = new Set(statuses.map((input) => input.dataset.attendanceStatus)).size;
        const marked = roster.querySelectorAll('[data-attendance-status]:checked').length;
        rosterCount.textContent = `${marked} de ${studentCount} estudiantes marcados`;
    };

    const renderRoster = () => {
        const course = options.cursos.find((item) => Number(item.id) === Number(courseSelect.value));
        const students = selectedStudents();
        const assignmentId = Number(assignmentSelect.value);
        const date = dateInput.value;

        rosterTitle.textContent = course ? courseLabel(course) : 'Selecciona un curso';
        markAllButton.classList.toggle('hidden', students.length === 0 || !assignmentId);
        actions.classList.toggle('hidden', students.length === 0 || !assignmentId);
        actions.classList.toggle('flex', students.length > 0 && Boolean(assignmentId));

        if (!course || !assignmentId) {
            roster.innerHTML = '<p class="px-5 py-10 text-center text-sm text-slate-500">Selecciona un curso y una asignatura para cargar la lista.</p>';
            rosterCount.textContent = '';
            return;
        }
        if (!students.length) {
            roster.innerHTML = `<p class="px-5 py-10 text-center text-sm text-slate-500">No hay estudiantes inscritos en este curso para la gestión ${registrationYear}.</p>`;
            rosterCount.textContent = '0 estudiantes inscritos';
            return;
        }

        const dayHeading = new Intl.DateTimeFormat('es', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        }).format(new Date(`${date}T12:00:00`));

        roster.innerHTML = `
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th rowspan="2" class="whitespace-nowrap px-5 py-3 text-left">#</th>
                        <th rowspan="2" class="min-w-64 px-5 py-3 text-left">Estudiante</th>
                        <th colspan="3" class="border-x border-slate-200 px-5 py-3 text-center text-cyan-800">${escapeHtml(dayHeading)}</th>
                        <th rowspan="2" class="px-5 py-3 text-center">Retraso</th>
                        <th rowspan="2" class="min-w-56 px-5 py-3 text-left">Observación (opcional)</th>
                    </tr>
                    <tr class="border-t border-slate-200">
                        <th class="min-w-28 border-l border-slate-200 px-4 py-3 text-center">Asistencia</th>
                        <th class="min-w-24 px-4 py-3 text-center">Licencia</th>
                        <th class="min-w-24 border-r border-slate-200 px-4 py-3 text-center">Falta</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    ${students.map((student, index) => {
                        const existing = attendanceRecords.find((record) =>
                            Number(record.cod_estudiante) === Number(student.id) &&
                            Number(record.cod_asignacion) === assignmentId &&
                            record.fecha === date
                        );
                        const statusChoices = [
                            ['Presente', 'Asistencia', 'text-emerald-700 accent-emerald-600'],
                            ['Licencia', 'Licencia', 'text-blue-700 accent-blue-600'],
                            ['Ausente', 'Falta', 'text-red-700 accent-red-600'],
                            ['Retraso', 'Retraso', 'text-amber-700 accent-amber-600'],
                        ];
                        return `
                            <tr class="hover:bg-slate-50">
                                <td class="whitespace-nowrap px-5 py-3.5 text-slate-500">${index + 1}</td>
                                <td class="whitespace-nowrap px-5 py-3.5 font-semibold text-slate-800">${escapeHtml(student.apellido)}, ${escapeHtml(student.nombre)}</td>
                                ${statusChoices.slice(0, 3).map(([value, label, colorClass]) => `
                                    <td class="px-4 py-3.5 text-center">
                                        <label class="inline-flex min-h-10 min-w-10 cursor-pointer items-center justify-center rounded-lg transition hover:bg-slate-100" title="${label}">
                                            <input type="radio" name="attendance-${escapeHtml(student.id)}" data-attendance-status="${escapeHtml(student.id)}" value="${value}" aria-label="${label} para ${escapeHtml(student.nombre)} ${escapeHtml(student.apellido)}" ${existing?.estado === value ? 'checked' : ''} class="h-5 w-5 cursor-pointer ${colorClass}">
                                        </label>
                                    </td>
                                `).join('')}
                                <td class="px-4 py-3.5 text-center">
                                    <label class="inline-flex min-h-10 min-w-10 cursor-pointer items-center justify-center rounded-lg transition hover:bg-slate-100" title="Retraso">
                                        <input type="radio" name="attendance-${escapeHtml(student.id)}" data-attendance-status="${escapeHtml(student.id)}" value="Retraso" aria-label="Retraso para ${escapeHtml(student.nombre)} ${escapeHtml(student.apellido)}" ${existing?.estado === 'Retraso' || existing?.estado === 'Retrasado' ? 'checked' : ''} class="h-5 w-5 cursor-pointer accent-amber-600">
                                    </label>
                                </td>
                                <td class="px-5 py-3">
                                    <input data-attendance-note="${escapeHtml(student.id)}" type="text" maxlength="250" value="${escapeHtml(existing?.observacion || '')}" aria-label="Observación de ${escapeHtml(student.nombre)} ${escapeHtml(student.apellido)}" placeholder="Motivo o detalle" class="${fieldClass} mt-0">
                                </td>
                            </tr>`;
                    }).join('')}
                </tbody>
            </table>`;

        roster.querySelectorAll('[data-attendance-status]').forEach((input) => input.addEventListener('change', updateProgress));
        updateProgress();
    };

    const updateAssignments = () => {
        const courseId = Number(courseSelect.value);
        const assignments = options.asignaciones.filter((item) => Number(item.cod_curso) === courseId);
        assignmentSelect.innerHTML = `<option value="">${assignments.length ? 'Selecciona una asignatura' : 'No hay asignaturas asignadas'}</option>${assignments.map((item) => `<option value="${escapeHtml(item.id)}">${escapeHtml(assignmentLabel(item, options))}</option>`).join('')}`;
        assignmentSelect.disabled = assignments.length === 0;
        renderRoster();
    };

    const refreshAttendance = async () => {
        attendanceRecords = await api.get('asistencias');
        if (!Array.isArray(attendanceRecords)) throw new Error('El API de asistencias devolvió una respuesta inesperada.');
        renderRoster();
    };

    courseSelect.addEventListener('change', updateAssignments);
    assignmentSelect.addEventListener('change', renderRoster);
    dateInput.addEventListener('change', renderRoster);
    markAllButton.addEventListener('click', () => {
        roster.querySelectorAll('[data-attendance-status][value="Presente"]').forEach((input) => { input.checked = true; });
        updateProgress();
    });

    saveButton.addEventListener('click', async () => {
        if (!courseSelect.value || !assignmentSelect.value || !dateInput.value || !userSelect.value) {
            showMessage('Selecciona el curso, la asignatura, la fecha y el usuario que registra.', true);
            return;
        }
        const markedStudents = [...roster.querySelectorAll('[data-attendance-status]:checked')];
        if (!markedStudents.length) {
            showMessage('Marca el estado de al menos un estudiante antes de guardar.', true);
            return;
        }

        saveButton.disabled = true;
        saveButton.textContent = 'Guardando...';
        message.classList.add('hidden');
        const assignmentId = Number(assignmentSelect.value);
        const date = dateInput.value;
        const userId = Number(userSelect.value);
        const results = await Promise.allSettled(markedStudents.map(async (select) => {
            const studentId = Number(select.dataset.attendanceStatus);
            const existing = attendanceRecords.find((record) =>
                Number(record.cod_estudiante) === studentId &&
                Number(record.cod_asignacion) === assignmentId &&
                record.fecha === date
            );
            const payload = {
                cod_estudiante: studentId,
                cod_asignacion: assignmentId,
                cod_usuario_registro: userId,
                fecha: date,
                estado: select.value,
                observacion: roster.querySelector(`[data-attendance-note="${studentId}"]`).value.trim(),
            };
            const response = existing
                ? await api.put(`asistencias/${existing.id}`, payload)
                : await api.post('asistencias', payload);
            if (response.estado !== true) throw new Error(response.message || `No se pudo guardar la asistencia del estudiante ${studentId}.`);
        }));

        const failed = results.filter((result) => result.status === 'rejected');
        let refreshError;
        try {
            await refreshAttendance();
        } catch (error) {
            refreshError = error;
        }
        if (failed.length) {
            const firstError = failed[0].reason?.message || 'Error desconocido al guardar.';
            const refreshDetails = refreshError ? ` No se pudo actualizar la lista: ${refreshError.message}` : '';
            showMessage(`Se guardaron ${results.length - failed.length} de ${results.length} registros. ${firstError}${refreshDetails}`, true);
        } else if (refreshError) {
            showMessage(`Los cambios se guardaron, pero no se pudo actualizar la lista: ${refreshError.message}`, true);
        } else {
            showMessage(`Se guardó la asistencia de ${results.length} estudiantes.`);
        }
        saveButton.disabled = false;
        saveButton.textContent = 'Guardar asistencia';
    });

    try {
        const [courses, students, registrationRecords, assignments, teachers, subjects, users, records] = await Promise.all([
            api.get('cursos'),
            api.get('estudiantes'),
            api.get('inscripciones'),
            api.get('asignaciones'),
            api.get('docentes'),
            api.get('materias'),
            api.get('users'),
            api.get('asistencias'),
        ]);
        const collections = [courses, students, registrationRecords, assignments, teachers, subjects, users, records];
        if (collections.some((collection) => !Array.isArray(collection))) {
            throw new Error('El API devolvió una respuesta inesperada al cargar los datos de asistencia.');
        }
        options = {
            cursos: courses,
            estudiantes: students,
            asignaciones: assignments,
            docentes: teachers,
            materias: subjects,
        };
        enrollments = registrationRecords;
        attendanceRecords = records;

        const activeCourses = courses.filter((course) => registrationRecords.some((item) =>
            Number(item.cod_curso) === Number(course.id) && Number(item.gestion) === registrationYear
        ));
        courseSelect.innerHTML = `<option value="">Selecciona un curso</option>${activeCourses.map((course) => `<option value="${escapeHtml(course.id)}">${escapeHtml(courseLabel(course))}</option>`).join('')}`;
        if (!activeCourses.length) {
            courseSelect.innerHTML = '<option value="">No hay cursos con estudiantes inscritos</option>';
        }
        userSelect.innerHTML = `<option value="">Selecciona quién registra</option>${users.map((user) => `<option value="${escapeHtml(user.id)}">${escapeHtml(user.username)}</option>`).join('')}`;
        if (!users.length) userSelect.innerHTML = '<option value="">No hay usuarios registrados</option>';
    } catch (error) {
        showMessage(error.message || 'No se pudieron cargar los datos para registrar asistencia.', true);
        throw error;
    }
};
