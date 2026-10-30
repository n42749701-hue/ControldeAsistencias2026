const apiUrl = 'http://api.asistenciasexto';

const buildUrl = (endpoint) => `${apiUrl}/${String(endpoint).replace(/^\/+/, '')}`;

export const api = {
    get: async (endpoint) => {
        try {
            const response = await fetch(buildUrl(endpoint), {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' },
            });

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            return response.json();
        } catch (error) {
            console.error('Error al obtener los datos:', error);
            throw error;
        }
    },

    post: async (endpoint, payload) => {
        const response = await fetch(buildUrl(endpoint), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        });

        try {
            return await response.json();
        } catch {
            throw new Error(`El servidor respondió con HTTP ${response.status}, pero no envió JSON válido.`);
        }
    },

    put: async (endpoint, payload) => {
        const response = await fetch(buildUrl(endpoint), {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        });

        return response.json();
    },

    del: async (endpoint) => {
        const response = await fetch(buildUrl(endpoint), {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
        });

        return response.json();
    },
};
