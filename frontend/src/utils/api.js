const apiUrl = 'http://api.asistenciasexto';

export const api = {
    get: async (endpoint) => {
        try {
            const cleanEndpoint = endpoint.replace(/^\/+/, '');
            const response = await fetch(`${apiUrl}/${cleanEndpoint}`);

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            return response.json();
        } catch (error) {
            console.error('Error al obtener los datos:', error);
            throw error;
        }
    },
};
