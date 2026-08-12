import { api } from '../utils/api.js';
import { icon } from '../utils/icons.js';

export const getUserList = async () => {
    const container = document.getElementById('UsersTableList');
    container.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-center text-slate-500">Cargando usuarios...</td></tr>';

    try {
        const users = await api.get('users');

        if (!Array.isArray(users) || users.length === 0) {
            container.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-center text-slate-500">No hay usuarios registrados.</td></tr>';
            return;
        }

        container.innerHTML = users.map((user) => `
            <tr class="transition hover:bg-slate-50">
                <td class="px-4 py-4 font-semibold text-slate-900">${user.id ?? ''}</td>
                <td class="px-4 py-4 text-slate-700">
                    <span class="flex items-center gap-2">
                        ${icon('user', 'h-4 w-4 text-cyan-700')}
                        ${user.nombre ?? ''}
                    </span>
                </td>
                <td class="px-4 py-4 text-slate-700">${user.apellidos ?? ''}</td>
                <td class="px-4 py-4 text-slate-700">
                    <span class="flex items-center gap-2">
                        ${icon('filePen', 'h-4 w-4 text-cyan-700')}
                        ${user.email ?? ''}
                    </span>
                </td>
                <td class="px-4 py-4 text-slate-500">
                    <span class="flex items-center gap-2">
                        ${icon('lock', 'h-4 w-4 text-slate-400')}
                        ${user.password ?? ''}
                    </span>
                </td>
            </tr>
        `).join('');
    } catch (error) {
        container.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-center font-semibold text-red-600">Error al cargar la lista de usuarios.</td></tr>';
    }
};
