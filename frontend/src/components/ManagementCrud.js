import { api } from '../utils/api.js';

const escapeHtml = (value) => String(value ?? '')
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');

const relationshipFields = {
    cod_docente: {
        label: 'Docente',
        endpoint: 'docentes',
        optionLabel: (item) => `${item.nombre} ${item.apellido}`,
    },
    cod_materia: {
        label: 'Materia',
        endpoint: 'materias',
        optionLabel: (item) => item.nombre_materia,
    },
    cod_curso: {
        label: 'Curso',
        endpoint: 'cursos',
        optionLabel: (item) => `${item.nombre_curso} - ${item.nivel} ${item.paralelo}`,
    },
    cod_estudiante: {
        label: 'Estudiante',
        endpoint: 'estudiantes',
        optionLabel: (item) => `${item.nombre} ${item.apellido}`,
    },
    cod_asignacion: {
        label: 'Asignacion',
        endpoint: 'asignaciones',
        optionLabel: (item, options) => {
            const teacher = options.docentes.find((record) => Number(record.id) === Number(item.cod_docente));
            const subject = options.materias.find((record) => Number(record.id) === Number(item.cod_materia));
            const course = options.cursos.find((record) => Number(record.id) === Number(item.cod_curso));
            return [
                teacher ? `${teacher.nombre} ${teacher.apellido}` : `Docente ${item.cod_docente}`,
                subject?.nombre_materia || `Materia ${item.cod_materia}`,
                course ? `${course.nombre_curso} - ${course.nivel} ${course.paralelo}` : `Curso ${item.cod_curso}`,
            ].join(' / ');
        },
    },
    cod_usuario_registro: {
        label: 'Usuario que registra',
        endpoint: 'users',
        optionLabel: (item) => item.username,
    },
};

const configurations = {
    docentes: {
        title: 'Docentes',
        singular: 'docente',
        fields: [
            { name: 'nombre', label: 'Nombre', type: 'text', required: true, maxLength: 50 },
            { name: 'apellido', label: 'Apellido', type: 'text', required: true, maxLength: 50 },
            { name: 'telefono', label: 'Telefono', type: 'tel', required: true, maxLength: 15 },
        ],
    },
    cursos: {
        title: 'Cursos',
        singular: 'curso',
        fields: [
            { name: 'nombre_curso', label: 'Nombre del curso', type: 'text', required: true, maxLength: 50 },
            { name: 'nivel', label: 'Nivel', type: 'text', required: true, maxLength: 30 },
            { name: 'paralelo', label: 'Paralelo', type: 'text', required: true, maxLength: 10 },
        ],
    },
    materias: {
        title: 'Materias',
        singular: 'materia',
        fields: [
            { name: 'nombre_materia', label: 'Nombre de la materia', type: 'text', required: true, maxLength: 100 },
        ],
    },
    asignaciones: {
        title: 'Asignaciones',
        singular: 'asignacion',
        fields: ['cod_docente', 'cod_materia', 'cod_curso'].map((name) => ({
            name,
            ...relationshipFields[name],
            type: 'select',
            required: true,
        })),
    },
    asistencias: {
        title: 'Asistencias',
        singular: 'asistencia',
        extraOptionEndpoints: ['docentes', 'materias', 'cursos'],
        fields: [
            { name: 'cod_estudiante', ...relationshipFields.cod_estudiante, type: 'select', required: true },
            { name: 'cod_asignacion', ...relationshipFields.cod_asignacion, type: 'select', required: true },
            { name: 'cod_usuario_registro', ...relationshipFields.cod_usuario_registro, type: 'select', required: true },
            { name: 'fecha', label: 'Fecha', type: 'date', required: true },
            { name: 'estado', label: 'Estado', type: 'select', required: true, choices: ['Presente', 'Ausente', 'Licencia', 'Retraso'] },
            { name: 'observacion', label: 'Observacion', type: 'textarea', required: true, maxLength: 250 },
        ],
    },
    inscripciones: {
        title: 'Inscripciones',
        singular: 'inscripcion',
        fields: [
            { name: 'cod_estudiante', ...relationshipFields.cod_estudiante, type: 'select', required: true },
            { name: 'cod_curso', ...relationshipFields.cod_curso, type: 'select', required: true },
            { name: 'gestion', label: 'Gestion', type: 'number', required: true, min: 2000, max: 9999 },
        ],
    },
};

const getOptionLabel = (field, item, options) => {
    if (field.optionLabel) return field.optionLabel(item, options);
    return item[field.name];
};

const renderField = (field, options) => {
    const required = field.required ? ' required' : '';
    const maxLength = field.maxLength ? ` maxlength="${field.maxLength}"` : '';
    const min = field.min !== undefined ? ` min="${field.min}"` : '';
    const max = field.max !== undefined ? ` max="${field.max}"` : '';
    const controlClass = 'mt-1 w-full rounded-md border border-slate-300 px-3 py-2 font-normal';
    let control;

    if (field.type === 'select') {
        const choices = field.choices
            ? field.choices.map((choice) => `<option value="${escapeHtml(choice)}">${escapeHtml(choice)}</option>`).join('')
            : (options[field.endpoint] || []).map((item) => `
                <option value="${escapeHtml(item.id)}">${escapeHtml(getOptionLabel(field, item, options))}</option>
            `).join('');
        control = `<select id="Record_${field.name}" class="${controlClass}"${required}>
            <option value="">Selecciona ${escapeHtml(field.label.toLowerCase())}</option>
            ${choices}
        </select>`;
    } else if (field.type === 'textarea') {
        control = `<textarea id="Record_${field.name}" class="${controlClass}"${required}${maxLength} rows="2"></textarea>`;
    } else {
        control = `<input id="Record_${field.name}" type="${field.type}" class="${controlClass}"${required}${maxLength}${min}${max}>`;
    }

    return `<label class="text-sm font-semibold text-slate-700">${escapeHtml(field.label)}${control}</label>`;
};

const renderView = (config, options) => `
    <section class="space-y-6">
        <div class="space-y-4">
            <div class="flex flex-col gap-4 rounded-lg border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-cyan-700">Administracion</p>
                    <h3 class="mt-1 text-2xl font-bold text-slate-950">${escapeHtml(config.title)}</h3>
                </div>
                <button id="ToggleRecordForm" type="button" aria-expanded="false" aria-controls="RecordFormPanel" class="inline-flex items-center justify-center gap-2 rounded-md bg-cyan-700 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-800">
                    Agregar ${escapeHtml(config.singular)}
                </button>
            </div>
            <div id="RecordFormPanel" class="hidden rounded-lg border border-slate-200 bg-white p-5 shadow-sm" style="display: none;">
                <h4 id="RecordFormTitle" class="text-lg font-bold text-slate-950">Nuevo ${escapeHtml(config.singular)}</h4>
                <div id="RecordMessage" class="hidden" role="status" aria-live="polite"></div>
                <form id="RecordForm" class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <input id="RecordId" type="hidden">
                    ${config.fields.map((field) => renderField(field, options)).join('')}
                    <div class="flex gap-2 sm:col-span-2 xl:col-span-4">
                        <button id="SaveRecordButton" type="submit" class="inline-flex items-center rounded-md bg-cyan-700 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-800 disabled:cursor-wait disabled:opacity-60">Guardar ${escapeHtml(config.singular)}</button>
                        <button id="CancelRecordEdit" type="button" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <div class="border-b border-slate-200 pb-5">
                <p class="text-sm font-semibold uppercase tracking-wide text-cyan-700">Administracion</p>
                <h3 class="mt-1 text-2xl font-bold text-slate-950">Listado de ${escapeHtml(config.title.toLowerCase())}</h3>
            </div>
            <div class="mt-6 overflow-x-auto rounded-lg border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            ${config.fields.map((field) => `<th class="px-4 py-3">${escapeHtml(field.label)}</th>`).join('')}
                            <th class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="RecordsTableList" class="divide-y divide-slate-100 bg-white">
                        <tr><td colspan="${config.fields.length + 2}" class="px-4 py-6 text-center text-slate-500">Cargando...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>`;

const openForm = (isOpen, config) => {
    const panel = document.getElementById('RecordFormPanel');
    const toggle = document.getElementById('ToggleRecordForm');
    panel.classList.toggle('hidden', !isOpen);
    panel.style.display = isOpen ? 'block' : 'none';
    toggle.setAttribute('aria-expanded', String(isOpen));
    toggle.textContent = isOpen ? 'Cerrar formulario' : `Agregar ${config.singular}`;
};

const showMessage = (message, isError = false) => {
    const element = document.getElementById('RecordMessage');
    element.textContent = message;
    element.className = `mb-4 rounded-lg px-4 py-3 text-sm font-semibold ${isError ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'}`;
};

const loadRecords = async (endpoint, config, options) => {
    const container = document.getElementById('RecordsTableList');
    try {
        const records = await api.get(endpoint);
        if (!Array.isArray(records)) throw new Error(`El API de ${config.title.toLowerCase()} devolvio una respuesta inesperada.`);
        if (!records.length) {
            container.innerHTML = `<tr><td colspan="${config.fields.length + 2}" class="px-4 py-6 text-center text-slate-500">No hay registros.</td></tr>`;
            return;
        }
        container.innerHTML = records.map((record) => `
            <tr class="transition hover:bg-slate-50">
                <td class="px-4 py-4 font-semibold text-slate-900">${escapeHtml(record.id)}</td>
                ${config.fields.map((field) => {
                    const option = field.endpoint
                        ? (options[field.endpoint] || []).find((item) => Number(item.id) === Number(record[field.name]))
                        : null;
                    const value = option ? getOptionLabel(field, option, options) : record[field.name];
                    return `<td class="px-4 py-4 text-slate-700">${escapeHtml(value)}</td>`;
                }).join('')}
                <td class="whitespace-nowrap px-4 py-4 text-right">
                    <button data-edit-record="${escapeHtml(record.id)}" class="mr-2 rounded-md px-3 py-2 text-sm font-semibold text-cyan-700 hover:bg-cyan-50">Editar</button>
                    <button data-delete-record="${escapeHtml(record.id)}" class="rounded-md px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-50">Eliminar</button>
                </td>
            </tr>
        `).join('');
    } catch (error) {
        container.innerHTML = `<tr><td colspan="${config.fields.length + 2}" class="px-4 py-6 text-center font-semibold text-red-600">${escapeHtml(error.message || 'Error al cargar los registros.')}</td></tr>`;
    }
};

const getErrorMessage = (response, fallback) => {
    if (Array.isArray(response.errores)) return response.errores.join('. ');
    return response.message || fallback;
};

export const initManagementCrud = async (endpoint) => {
    const config = configurations[endpoint];
    if (!config) throw new Error(`No existe una configuracion CRUD para ${endpoint}.`);

    const optionEndpoints = [...new Set([
        ...config.fields.filter((field) => field.endpoint).map((field) => field.endpoint),
        ...(config.extraOptionEndpoints || []),
    ])];
    const options = {};
    await Promise.all(optionEndpoints.map(async (optionEndpoint) => {
        const result = await api.get(optionEndpoint);
        if (!Array.isArray(result)) throw new Error(`El API de ${optionEndpoint} devolvio una respuesta inesperada.`);
        options[optionEndpoint] = result;
    }));

    const root = document.getElementById('management-view');
    if (!root) throw new Error(`La vista ${config.title} no tiene el contenedor para cargar su contenido.`);
    root.innerHTML = renderView(config, options);
    const form = document.getElementById('RecordForm');
    const list = document.getElementById('RecordsTableList');
    const clearForm = () => {
        form.reset();
        document.getElementById('RecordId').value = '';
        document.getElementById('RecordFormTitle').textContent = `Nuevo ${config.singular}`;
    };

    document.getElementById('ToggleRecordForm').addEventListener('click', () => {
        const isOpen = document.getElementById('ToggleRecordForm').getAttribute('aria-expanded') === 'true';
        if (isOpen) clearForm();
        openForm(!isOpen, config);
    });
    document.getElementById('CancelRecordEdit').addEventListener('click', () => {
        clearForm();
        openForm(false, config);
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (!form.reportValidity()) return;

        const saveButton = document.getElementById('SaveRecordButton');
        if (saveButton.disabled) return;
        const id = document.getElementById('RecordId').value;
        const payload = Object.fromEntries(config.fields.map((field) => {
            const value = document.getElementById(`Record_${field.name}`).value.trim();
            return [field.name, field.type === 'number' || (field.type === 'select' && field.endpoint) ? Number(value) : value];
        }));

        saveButton.disabled = true;
        saveButton.textContent = id ? 'Actualizando...' : 'Guardando...';
        try {
            const response = id
                ? await api.put(`${endpoint}/${id}`, payload)
                : await api.post(endpoint, payload);
            if (response.estado !== true) {
                throw new Error(getErrorMessage(response, `No se pudo guardar ${config.singular}.`));
            }
            showMessage(response.message || `${config.singular} guardado correctamente.`);
            clearForm();
            openForm(false, config);
            await loadRecords(endpoint, config, options);
        } catch (error) {
            showMessage(error.message || `No se pudo guardar ${config.singular}.`, true);
        } finally {
            saveButton.disabled = false;
            saveButton.textContent = `Guardar ${config.singular}`;
        }
    });

    list.addEventListener('click', async (event) => {
        const editButton = event.target.closest('[data-edit-record]');
        const deleteButton = event.target.closest('[data-delete-record]');
        try {
            if (editButton) {
                const record = await api.get(`${endpoint}/${editButton.dataset.editRecord}`);
                document.getElementById('RecordId').value = record.id;
                config.fields.forEach((field) => {
                    document.getElementById(`Record_${field.name}`).value = record[field.name] ?? '';
                });
                document.getElementById('RecordFormTitle').textContent = `Editar ${config.singular}`;
                openForm(true, config);
            }
            if (deleteButton && window.confirm(`¿Deseas eliminar ${config.singular}?`)) {
                const response = await api.del(`${endpoint}/${deleteButton.dataset.deleteRecord}`);
                if (response.estado !== true) {
                    throw new Error(getErrorMessage(response, `No se pudo eliminar ${config.singular}.`));
                }
                showMessage(response.message || `${config.singular} eliminado correctamente.`);
                await loadRecords(endpoint, config, options);
            }
        } catch (error) {
            showMessage(error.message || `No se pudo completar la operacion de ${config.singular}.`, true);
        }
    });

    await loadRecords(endpoint, config, options);
};
