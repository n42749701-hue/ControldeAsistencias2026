import { initUsersCrud } from './components/UsersList.js';

const app = document.getElementById('app');
const pageTitle = document.getElementById('pagetitle');
const navLinks = document.querySelectorAll('[data-view]');

const titles = {
    home: 'Dashboard',
    users: 'Usuarios',
    students: 'Estudiantes',
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
        app.innerHTML = await res.text();
        pageTitle.textContent = titles.home;
        setActiveLink('home');
    },
    users: async () => {
        const res = await fetch('./src/views/users.html');
        app.innerHTML = await res.text();
        pageTitle.textContent = titles.users;
        setActiveLink('users');
        await initUsersCrud();
    },
    students: async () => {
        const res = await fetch('./src/views/students.html');
        app.innerHTML = await res.text();
        pageTitle.textContent = titles.students;
        setActiveLink('students');
    }
};

navLinks.forEach(link => {
    link.addEventListener('click', async (event) => {
        event.preventDefault();
        const view = link.dataset.view;
        if (views[view]) {
            await views[view]();
        }
    });
});

views.home();