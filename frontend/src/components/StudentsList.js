import { api } from '../utils/api.js';

const escapeHtml = (value) => String(value ?? '')
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');

const showMessage = (message, isError = false) => {
    const element = document.getElementById('StudentsMessage');
    element.textContent = message;
    element.className = `mb-4 rounded-lg px-4 py-3 text-sm font-semibold ${isError ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'}`;
};

const setStudentFormOpen = (isOpen) => {
    const panel = document.getElementById('StudentFormPanel');
    const toggle = document.getElementById('ToggleStudentForm');
    panel.classList.toggle('hidden', !isOpen);
    panel.style.display = isOpen ? 'block' : 'none';
    toggle.setAttribute('aria-expanded', String(isOpen));
    document.getElementById('StudentFormToggleLabel').textContent = isOpen ? 'Cerrar formulario' : 'Agregar estudiante';
};

const clearForm = () => {
    document.getElementById('StudentForm').reset();
    document.getElementById('StudentId').value = '';
    document.getElementById('StudentFormTitle').textContent = 'Nuevo estudiante';
};

const fillForm = (student) => {
    document.getElementById('StudentId').value = student.id;
    document.getElementById('StudentName').value = student.nombre ?? '';
    document.getElementById('StudentSurname').value = student.apellido ?? '';
    document.getElementById('StudentAddress').value = student.direccion ?? '';
    document.getElementById('StudentPhone').value = student.telefono ?? '';
    document.getElementById('StudentFormTitle').textContent = 'Editar estudiante';
    setStudentFormOpen(true);
};

const renderStudents = (students) => {
    const container = document.getElementById('StudentsTableList');
    if (!students.length) {
        container.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-center text-slate-500">No hay estudiantes registrados.</td></tr>';
        return;
    }

    container.innerHTML = students.map((student) => `
        <tr class="transition hover:bg-slate-50">
            <td class="px-4 py-4 font-semibold text-slate-900">${escapeHtml(student.id)}</td>
            <td class="px-4 py-4 text-slate-700">${escapeHtml(student.nombre)}</td>
            <td class="px-4 py-4 text-slate-700">${escapeHtml(student.apellido)}</td>
            <td class="px-4 py-4 text-slate-700">${escapeHtml(student.direccion)}</td>
            <td class="px-4 py-4 text-slate-700">${escapeHtml(student.telefono)}</td>
            <td class="px-4 py-4 text-right">
                <button data-edit-student="${escapeHtml(student.id)}" class="mr-2 rounded-md px-3 py-2 text-sm font-semibold text-cyan-700 hover:bg-cyan-50">Editar</button>
                <button data-delete-student="${escapeHtml(student.id)}" class="rounded-md px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-50">Eliminar</button>
            </td>
        </tr>
    `).join('');
};

const loadStudents = async () => {
    const container = document.getElementById('StudentsTableList');
    container.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-center text-slate-500">Cargando estudiantes...</td></tr>';
    try {
        const students = await api.get('estudiantes');
        renderStudents(Array.isArray(students) ? students : []);
    } catch (error) {
        container.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-center font-semibold text-red-600">Error al cargar la lista de estudiantes.</td></tr>';
        showMessage(error.message || 'No se pudieron cargar los estudiantes.', true);
    }
};

const getErrorMessage = (response, fallback) => {
    if (Array.isArray(response.errores)) return response.errores.join('. ');
    return response.message || fallback;
};

export const initStudentsCrud = async () => {
    const requiredElements = [
        'StudentForm',
        'ToggleStudentForm',
        'CancelStudentEdit',
        'StudentsTableList',
    ];
    const missingElements = requiredElements.filter((id) => !document.getElementById(id));
    if (missingElements.length) {
        throw new Error(`La vista de estudiantes no contiene los elementos requeridos: ${missingElements.join(', ')}. Recarga la página e inténtalo de nuevo.`);
    }

    document.getElementById('StudentForm').addEventListener('submit', async (event) => {
        event.preventDefault();
        const form = event.currentTarget;
        if (!form.reportValidity()) return;

        const saveButton = document.getElementById('SaveStudentButton');
        if (saveButton.disabled) return;

        const id = document.getElementById('StudentId').value;
        const payload = {
            nombre: document.getElementById('StudentName').value.trim(),
            apellido: document.getElementById('StudentSurname').value.trim(),
            direccion: document.getElementById('StudentAddress').value.trim(),
            telefono: document.getElementById('StudentPhone').value.trim(),
        };

        saveButton.disabled = true;
        saveButton.textContent = id ? 'Actualizando...' : 'Guardando...';
        try {
            const response = id
                ? await api.put(`estudiantes/${id}`, payload)
                : await api.post('estudiantes', payload);
            if (response.estado !== true) {
                throw new Error(getErrorMessage(response, 'No se pudieron guardar los datos del estudiante.'));
            }
            showMessage(response.message || 'Estudiante guardado correctamente.');
            clearForm();
            setStudentFormOpen(false);
            await loadStudents();
        } catch (error) {
            showMessage(error.message || 'No se pudieron guardar los datos del estudiante.', true);
        } finally {
            saveButton.disabled = false;
            saveButton.textContent = 'Guardar estudiante';
        }
    });

    document.getElementById('ToggleStudentForm').addEventListener('click', () => {
        const isOpen = document.getElementById('ToggleStudentForm').getAttribute('aria-expanded') === 'true';
        if (isOpen) clearForm();
        setStudentFormOpen(!isOpen);
    });

    document.getElementById('CancelStudentEdit').addEventListener('click', () => {
        clearForm();
        setStudentFormOpen(false);
    });

    document.getElementById('StudentsTableList').addEventListener('click', async (event) => {
        const editButton = event.target.closest('[data-edit-student]');
        const deleteButton = event.target.closest('[data-delete-student]');
        try {
            if (editButton) {
                fillForm(await api.get(`estudiantes/${editButton.dataset.editStudent}`));
            }
            if (deleteButton && window.confirm('¿Deseas eliminar este estudiante?')) {
                const response = await api.del(`estudiantes/${deleteButton.dataset.deleteStudent}`);
                if (response.estado !== true) {
                    throw new Error(getErrorMessage(response, 'No se pudo eliminar el estudiante.'));
                }
                showMessage(response.message || 'Estudiante eliminado correctamente.');
                await loadStudents();
            }
        } catch (error) {
            showMessage(error.message || 'No se pudo completar la operación.', true);
        }
    });

    await loadStudents();
};
