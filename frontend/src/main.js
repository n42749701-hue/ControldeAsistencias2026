import { getUserList } from './components/UsersList.js';
import { icon, mountIcons } from './utils/icons.js';

const app = document.getElementById('app');
const pageTitle = document.getElementById('page-title');

const titles = {
    home: 'Dashboard',
    users: 'Usuarios',
    estudiantes: 'Estudiantes',
    docentes: 'Docentes',
    cursos: 'Cursos',
    materias: 'Materias',
    asistencias: 'Asistencias',
    asignaciones: 'Asignaciones',
    inscripciones: 'Inscripciones',
};

const moduleDescriptions = {
    estudiantes: 'Gestiona la informacion de los estudiantes registrados en el sistema.',
    docentes: 'Organiza los docentes y sus datos principales.',
    cursos: 'Administra cursos disponibles para la gestion academica.',
    materias: 'Consulta y organiza las materias del sistema.',
    asistencias: 'Registra y revisa la asistencia por curso, materia y fecha.',
    asignaciones: 'Relaciona docentes, cursos y materias.',
    inscripciones: 'Revisa las inscripciones activas de estudiantes.',
};

const loadHtmlView = async (viewName) => {
    const response = await fetch(`./src/views/${viewName}.html`);
    if (!response.ok) {
        throw new Error(`No se pudo cargar la vista ${viewName}`);
    }
    app.innerHTML = await response.text();
};

const renderModuleView = (viewName) => {
    const title = titles[viewName] ?? 'Modulo';
    const description = moduleDescriptions[viewName] ?? 'Modulo administrativo del sistema.';

    app.innerHTML = `
        <section class="space-y-6">
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-cyan-50 text-cyan-700">
                        ${icon('layers', 'h-6 w-6')}
                    </span>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-cyan-700">Modulo</p>
                        <h3 class="mt-1 text-3xl font-bold text-slate-950">${title}</h3>
                    </div>
                </div>
                <p class="mt-3 max-w-2xl text-slate-600">${description}</p>
            </div>

            <div class="rounded-lg border border-dashed border-slate-300 bg-white p-8 text-center shadow-sm">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                    ${icon('database', 'h-6 w-6')}
                </div>
                <p class="text-lg font-bold text-slate-900">Vista lista para conectar</p>
                <p class="mt-2 text-slate-500">Puedes crear <span class="font-semibold text-slate-700">src/views/${viewName}.html</span> y este menu la cargara automaticamente.</p>
            </div>
        </section>
    `;
};

const setActiveLink = (viewName) => {
    document.querySelectorAll('.nav-link').forEach((link) => {
        const isActive = link.dataset.view === viewName;
        link.classList.toggle('bg-cyan-400', isActive);
        link.classList.toggle('text-slate-950', isActive);
        link.classList.toggle('shadow-sm', isActive);
        link.classList.toggle('text-slate-200', !isActive);
    });
};

const navigateTo = async (viewName) => {
    pageTitle.textContent = titles[viewName] ?? 'Dashboard';
    setActiveLink(viewName);

    try {
        if (viewName === 'home' || viewName === 'users') {
            await loadHtmlView(viewName);
        } else {
            renderModuleView(viewName);
        }

        if (viewName === 'users') {
            await getUserList();
        }

        mountIcons(app);
    } catch (error) {
        app.innerHTML = `
            <section class="rounded-lg border border-red-200 bg-red-50 p-6 text-red-800">
                <div class="flex items-center gap-3">
                    ${icon('activity', 'h-5 w-5')}
                    <h3 class="text-lg font-bold">No se pudo cargar la pagina</h3>
                </div>
                <p class="mt-2 text-sm">${error.message}</p>
            </section>
        `;
    }
};

document.addEventListener('click', async (event) => {
    const link = event.target.closest('[data-view]');
    if (!link) {
        return;
    }

    event.preventDefault();
    await navigateTo(link.dataset.view);
});

await navigateTo('home');
mountIcons();
