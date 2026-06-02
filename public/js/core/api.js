// ==================== API FUNCTIONS ====================

const API = {
    // Get CSRF Token
    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content;
    },

    // Dashboard API
    dashboard: {
        async getData(year) {
            const res = await fetch(`/api/dashboard?range=year&year=${year}`);
            return res.json();
        },
        
        async getTotalKlien() {
            const res = await fetch('/api/dashboard/total-klien');
            return res.json();
        },
        
        async getKlienAktif() {
            const res = await fetch('/api/dashboard/klien-aktif');
            return res.json();
        },
        
        async getKlienTidakAktif() {
            const res = await fetch('/api/dashboard/klien-tidak-aktif');
            return res.json();
        },
        
        async getTotalPendapatan(tahun) {
            const res = await fetch(`/api/dashboard/total-pendapatan?tahun=${tahun}`);
            return res.json();
        }
    },

    // Clients API
    clients: {
        async delete(id) {
            const res = await fetch(`/klien/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': API.getCsrfToken(),
                    'Accept': 'application/json'
                }
            });
            return res.json();
        }
    },

    // Settings API (localStorage)
    settings: {
        get() {
            return JSON.parse(localStorage.getItem('companySettings') || '{}');
        },
        
        save(settings) {
            localStorage.setItem('companySettings', JSON.stringify(settings));
            return true;
        },
        
        reset() {
            localStorage.removeItem('companySettings');
            return true;
        }
    }
};