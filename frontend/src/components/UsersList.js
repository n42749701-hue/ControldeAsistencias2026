import { api } from '../utils/api.js';

const escapeHtml = (value) => String(value ?? '')
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');

const showMessage = (message, isError = false) => {
    const element = document.getElementById('UsersMessage');
    element.textContent = message;
    element.className = `mb-4 rounded-lg px-4 py-3 text-sm font-semibold ${isError ? 'bg-red-50 text-red-700' : 'bg-emerald-50 text-emerald-700'}`;
};

const clearForm = () => {
    document.getElementById('UserForm').reset();
    document.getElementById('UserId').value = '';
    document.getElementById('UserFormTitle').textContent = 'Nuevo usuario';
    document.getElementById('UserPassword').required = true;
};

const setUserFormOpen = (isOpen) => {
    const panel = document.getElementById('UserFormPanel');
    const toggle = document.getElementById('ToggleUserForm');
    panel.classList.toggle('hidden', !isOpen);
    toggle.setAttribute('aria-expanded', String(isOpen));
    document.getElementById('UserFormToggleLabel').textContent = isOpen ? 'Cerrar formulario' : 'Agregar usuario';
};

const fillForm = (user) => {
    document.getElementById('UserId').value = user.id;
    document.getElementById('UserName').value = user.username;
    document.getElementById('UserPassword').value = '';
    document.getElementById('UserPassword').required = false;
    document.getElementById('UserTeacher').value = user.id_docente ?? '';
    document.getElementById('UserStudent').value = user.id_estudiante ?? '';
    document.getElementById('UserFormTitle').textContent = 'Editar usuario';
    setUserFormOpen(true);
};

const renderUsers = (users) => {
    const container = document.getElementById('UsersTableList');
    if (!users.length) {
        container.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-center text-slate-500">No hay usuarios registrados.</td></tr>';
        return;
    }

    container.innerHTML = users.map((user) => `
        <tr class="transition hover:bg-slate-50">
            <td class="px-4 py-4 font-semibold text-slate-900">${escapeHtml(user.id)}</td>
            <td class="px-4 py-4 text-slate-700">${escapeHtml(user.username)}</td>
            <td class="px-4 py-4 text-slate-700">${escapeHtml(user.id_docente || 'Administrativo')}</td>
            <td class="px-4 py-4 text-slate-700">${escapeHtml(user.id_estudiante || 'No aplica')}</td>
            <td class="px-4 py-4 text-right">
                <button data-edit-user="${escapeHtml(user.id)}" class="mr-2 rounded-md px-3 py-2 text-sm font-semibold text-cyan-700 hover:bg-cyan-50">Editar</button>
                <button data-delete-user="${escapeHtml(user.id)}" class="rounded-md px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-50">Eliminar</button>
            </td>
        </tr>
    `).join('');
};

const loadUsers = async () => {
    const container = document.getElementById('UsersTableList');
    container.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-center text-slate-500">Cargando usuarios...</td></tr>';
    try {
        const users = await api.get('users');
        renderUsers(Array.isArray(users) ? users : []);
    } catch (error) {
        container.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-center font-semibold text-red-600">Error al cargar la lista de usuarios.</td></tr>';
    }
};

export const initUsersCrud = async () => {
    document.getElementById('UserForm').addEventListener('submit', async (event) => {
        event.preventDefault();
        const form = event.currentTarget;
        if (!form.reportValidity()) return;

        const saveButton = document.getElementById('SaveUserButton');
        if (saveButton.disabled) return;

        const id = document.getElementById('UserId').value;
        const payload = {
            username: document.getElementById('UserName').value.trim(),
            password: document.getElementById('UserPassword').value,
            id_docente: document.getElementById('UserTeacher').value || null,
            id_estudiante: document.getElementById('UserStudent').value || null,
        };
        if (!payload.password && id) delete payload.password;

        saveButton.disabled = true;
        saveButton.textContent = id ? 'Actualizando...' : 'Guardando...';
        try {
            const response = id ? await api.put(`users/${id}`, payload) : await api.post('users', payload);
            if (response.estado !== true) {
                const message = Array.isArray(response.errores)
                    ? response.errores.join('. ')
                    : response.message;
                throw new Error(message || 'No se pudo guardar el usuario');
            }
            showMessage(response.message);
            clearForm();
            await loadUsers();
            setUserFormOpen(false);
        } catch (error) {
            showMessage(error.message, true);
        } finally {
            saveButton.disabled = false;
            saveButton.textContent = 'Guardar usuario';
        }
    });

    document.getElementById('ToggleUserForm').addEventListener('click', () => {
        const isOpen = document.getElementById('ToggleUserForm').getAttribute('aria-expanded') === 'true';
        if (isOpen) clearForm();
        setUserFormOpen(!isOpen);
    });
    document.getElementById('CancelUserEdit').addEventListener('click', () => {
        clearForm();
        setUserFormOpen(false);
    });
    document.getElementById('UsersTableList').addEventListener('click', async (event) => {
        const editButton = event.target.closest('[data-edit-user]');
        const deleteButton = event.target.closest('[data-delete-user]');
        if (editButton) fillForm(await api.get(`users/${editButton.dataset.editUser}`));
        if (deleteButton && window.confirm('¿Deseas eliminar este usuario?')) {
            const response = await api.del(`users/${deleteButton.dataset.deleteUser}`);
            showMessage(response.message, response.estado !== true);
            await loadUsers();
        }
    });

    await loadUsers();
};
