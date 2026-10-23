import { initUsersCrud } from './components/UsersList.js';
import { initStudentsCrud } from './components/StudentsList.js';
import { mountIcons } from './utils/icons.js';
import { initManagementCrud } from './components/ManagementCrud.js';
import { initAttendanceRegister } from './components/AttendanceRegister.js';

const app = document.getElementById('app');
const pageTitle = document.getElementById('pagetitle');
const navLinks = document.querySelectorAll('[data-view]');
const logoutButton = document.getElementById('logoutButton');

logoutButton.addEventListener('click', () => {
    sessionStorage.removeItem('controlAsistenciasUser');
    window.location.replace('./src/views/login.html');
});

const setAppContent = (html) => {
    app.innerHTML = html;
    mountIcons(app);
};

const showViewError = (error) => {
    app.replaceChildren();
    const message = document.createElement('p');
    message.className = 'rounded-lg border border-red-200 bg-red-50 p-4 font-semibold text-red-700';
    message.textContent = error.message || 'No se pudo cargar la vista.';
    app.append(message);
};

mountIcons(document);

const titles = {
    home: 'Dashboard',
    users: 'Usuarios',
    students: 'Estudiantes',
    docentes: 'Docentes',
    cursos: 'Cursos',
    materias: 'Materias',
    asistencias: 'Asistencias',
    asignaciones: 'Asignaciones',
    inscripciones: 'Inscripciones',

};
function setActiveLink(view) {
    navLinks.forEach(link => {
        link.classList.remove('bg-indigo-600', 'text-white');
        link.classList.add('text-slate-300', 'hover:bg-slate-700', 'hover:text-white');
        if (link.dataset.view === view) {
            link.classList.add('bg-indigo-600', 'text-white');
            link.classList.remove('text-slate-300', 'hover:bg-slate-700', 'hover:text-white');
        }
    });
}

const views = {
    home: async () => {
        const res = await fetch('./src/views/home.html');
        setAppContent(await res.text());
        pageTitle.textContent = titles.home;
        setActiveLink('home');
    },
    users: async () => {
        const res = await fetch('./src/views/users.html');
        setAppContent(await res.text());
        pageTitle.textContent = titles.users;
        setActiveLink('users');
        await initUsersCrud();
    },
    students: async () => {
        const res = await fetch('./src/views/students.html', { cache: 'no-store' });
        if (!res.ok) {
            throw new Error(`No se pudo cargar la vista de estudiantes (HTTP ${res.status}).`);
        }
        setAppContent(await res.text());
        pageTitle.textContent = titles.students;
        setActiveLink('students');
        await initStudentsCrud();
    },
    docentes: () => loadManagementView('docentes'),
    cursos: () => loadManagementView('cursos'),
    materias: () => loadManagementView('materias'),
    asistencias: () => loadAttendanceView(),
    asignaciones: () => loadManagementView('asignaciones'),
    inscripciones: () => loadManagementView('inscripciones')
};

async function loadManagementView(view) {
    const res = await fetch(`./src/views/${view}.html`, { cache: 'no-store' });
    if (!res.ok) {
        throw new Error(`No se pudo cargar la vista ${titles[view]} (HTTP ${res.status}).`);
    }
    setAppContent(await res.text());
    pageTitle.textContent = titles[view];
    setActiveLink(view);
    await initManagementCrud(view);
}

async function loadAttendanceView() {
    const res = await fetch('./src/views/asistencias.html', { cache: 'no-store' });
    if (!res.ok) {
        throw new Error(`No se pudo cargar la vista ${titles.asistencias} (HTTP ${res.status}).`);
    }
    setAppContent(await res.text());
    pageTitle.textContent = titles.asistencias;
    setActiveLink('asistencias');
    await initAttendanceRegister();
}

navLinks.forEach(link => {
    link.addEventListener('click', async (event) => {
        event.preventDefault();
        const view = link.dataset.view;
        if (views[view]) {
            try {
                await views[view]();
            } catch (error) {
                showViewError(error);
            }
        }
    });
});

views.home();