<!-- Componente de búsqueda global -->
<div class="global-search-container">
    <label for="globalSearch" class="visually-hidden">Búsqueda global</label>
    <div class="input-group input-group-lg">
        <span class="input-group-text bg-white border-end-0">
            <i class="fas fa-search text-muted"></i>
        </span>
        <input 
            type="text" 
            class="form-control border-start-0" 
            id="globalSearch" 
            name="globalSearch"
            placeholder="Buscar tareas, eventos..." 
            autocomplete="off"
        >
    </div>
    
    <!-- Resultados de búsqueda -->
    <div class="search-results position-absolute w-100" style="z-index: 1000; top: 100%; display: none;">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="search-loading d-none">
                    <div class="text-center p-3">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Buscando...</span>
                        </div>
                        <div class="mt-2">Buscando...</div>
                    </div>
                </div>
                <div class="search-content"></div>
                <div class="search-empty d-none">
                    <div class="text-center p-3 text-muted">
                        <i class="fas fa-search fa-2x mb-2"></i>
                        <div>No se encontraron resultados</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.global-search-container {
    position: relative;
    width: 100%;
    max-width: 400px;
}

#globalSearch {
    border-radius: 1.5rem;
    border: 2px solid #e9ecef;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
}

#globalSearch:focus {
    border-color: #ffd600;
    box-shadow: 0 0 0 0.2rem rgba(255, 214, 0, 0.25);
    outline: 0;
}

.search-results {
    margin-top: 0.5rem;
    border-radius: 1rem;
    overflow: hidden;
}

.search-highlight {
    background-color: #fff3cd;
    font-weight: bold;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('globalSearch');
    const searchResults = document.querySelector('.search-results');
    const searchLoading = document.querySelector('.search-loading');
    const searchContent = document.querySelector('.search-content');
    const searchEmpty = document.querySelector('.search-empty');
    
    if (!searchInput) return;
    
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            hideResults();
            return;
        }
        
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });
    
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2) {
            searchResults.style.display = 'block';
        }
    });
    
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            hideResults();
        }
    });
    
    function showLoading() {
        searchResults.style.display = 'block';
        searchLoading.classList.remove('d-none');
        searchContent.innerHTML = '';
        searchEmpty.classList.add('d-none');
    }
    
    function hideLoading() {
        searchLoading.classList.add('d-none');
    }
    
    function hideResults() {
        searchResults.style.display = 'none';
    }
    
    function showResults(data) {
        hideLoading();
        
        if (data.length === 0) {
            searchEmpty.classList.remove('d-none');
            return;
        }
        
        let html = '';
        data.forEach(item => {
            html += `
                <div class="search-item p-2 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">${highlightText(item.title, searchInput.value)}</h6>
                            <small class="text-muted">${getTypeLabel(item.type)}</small>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">${formatDate(item.date)}</small>
                        </div>
                    </div>
                </div>
            `;
        });
        
        searchContent.innerHTML = html;
    }
    
    function highlightText(text, query) {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<span class="search-highlight">$1</span>');
    }
    
    function getTypeLabel(type) {
        const labels = {
            task: 'Tarea',
            event: 'Evento',
            payment: 'Pago'
        };
        return labels[type] || type;
    }
    
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    function performSearch(query) {
        showLoading();
        
        // Simulación de búsqueda - en producción sería una llamada AJAX
        setTimeout(() => {
            const mockData = [
                {
                    title: `Tarea que contiene "${query}"`,
                    type: 'task',
                    date: new Date().toISOString()
                }
            ];
            
            showResults(query.length >= 2 ? mockData : []);
        }, 500);
    }
});
</script>
