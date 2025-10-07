// Enhanced Notification System
class NotificationManager {
    constructor() {
        this.container = null;
        this.notifications = [];
        this.init();
    }
    
    init() {
        // Create notification container
        this.container = document.createElement('div');
        this.container.id = 'notification-container';
        this.container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1060;
            max-width: 400px;
            pointer-events: none;
        `;
        document.body.appendChild(this.container);
    }
    
    show(message, type = 'info', duration = 5000, options = {}) {
        const notification = this.createNotification(message, type, options);
        this.container.appendChild(notification);
        this.notifications.push(notification);
        
        // Trigger animation
        requestAnimationFrame(() => {
            notification.classList.add('show');
        });
        
        // Auto-dismiss
        if (duration > 0) {
            setTimeout(() => {
                this.dismiss(notification);
            }, duration);
        }
        
        return notification;
    }
    
    createNotification(message, type, options) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.style.cssText = `
            margin-bottom: 10px;
            padding: 1rem;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: 0 8px 32px var(--shadow-color);
            transform: translateX(400px);
            transition: transform 0.3s ease, opacity 0.3s ease;
            pointer-events: auto;
            position: relative;
        `;
        
        const iconMap = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-triangle',
            warning: 'fas fa-exclamation-circle',
            info: 'fas fa-info-circle'
        };
        
        const colorMap = {
            success: '#4caf50',
            error: '#f44336',
            warning: '#ff9800',
            info: '#2196f3'
        };
        
        notification.innerHTML = `
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <i class="${iconMap[type]}" style="color: ${colorMap[type]}; font-size: 1.25rem;"></i>
                <div style="flex: 1;">
                    <div style="color: var(--text-primary); font-weight: 500; margin-bottom: 0.25rem;">
                        ${options.title || this.getDefaultTitle(type)}
                    </div>
                    <div style="color: var(--text-secondary); font-size: 0.875rem;">
                        ${message}
                    </div>
                </div>
                <button onclick="notificationManager.dismiss(this.parentElement.parentElement)" 
                        style="background: none; border: none; color: var(--text-muted); font-size: 1.25rem; cursor: pointer; padding: 0; line-height: 1;"
                        aria-label="Cerrar notificación">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            ${options.actions ? this.createActions(options.actions) : ''}
        `;
        
        // Add border color based on type
        notification.style.borderLeftWidth = '4px';
        notification.style.borderLeftColor = colorMap[type];
        
        return notification;
    }
    
    createActions(actions) {
        const actionsHtml = actions.map(action => 
            `<button onclick="${action.onClick}" 
                     class="btn btn-sm ${action.class || 'btn-outline-primary'}" 
                     style="margin-right: 0.5rem; margin-top: 0.5rem;">
                ${action.text}
             </button>`
        ).join('');
        
        return `<div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid var(--border-color);">
                    ${actionsHtml}
                </div>`;
    }
    
    getDefaultTitle(type) {
        const titles = {
            success: 'Éxito',
            error: 'Error',
            warning: 'Advertencia',
            info: 'Información'
        };
        return titles[type] || 'Notificación';
    }
    
    dismiss(notification) {
        if (!notification) return;
        
        notification.style.transform = 'translateX(400px)';
        notification.style.opacity = '0';
        
        setTimeout(() => {
            if (notification.parentElement) {
                notification.parentElement.removeChild(notification);
            }
            this.notifications = this.notifications.filter(n => n !== notification);
        }, 300);
    }
    
    dismissAll() {
        this.notifications.forEach(notification => {
            this.dismiss(notification);
        });
    }
    
    // Utility methods
    success(message, options = {}) {
        return this.show(message, 'success', 5000, options);
    }
    
    error(message, options = {}) {
        return this.show(message, 'error', 8000, options);
    }
    
    warning(message, options = {}) {
        return this.show(message, 'warning', 6000, options);
    }
    
    info(message, options = {}) {
        return this.show(message, 'info', 5000, options);
    }
    
    persistent(message, type = 'info', options = {}) {
        return this.show(message, type, 0, options);
    }
}

// Initialize notification manager
const notificationManager = new NotificationManager();

// Enhanced form validation with notifications
class FormValidator {
    constructor(form) {
        this.form = form;
        this.rules = {};
        this.init();
    }
    
    init() {
        this.form.addEventListener('submit', (e) => {
            if (!this.validate()) {
                e.preventDefault();
                notificationManager.error('Por favor, corrige los errores en el formulario');
            }
        });
        
        // Real-time validation
        this.form.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('blur', () => {
                this.validateField(field);
            });
            
            field.addEventListener('input', () => {
                this.clearFieldError(field);
            });
        });
    }
    
    addRule(fieldName, rule) {
        this.rules[fieldName] = rule;
        return this;
    }
    
    validate() {
        let isValid = true;
        
        Object.keys(this.rules).forEach(fieldName => {
            const field = this.form.querySelector(`[name="${fieldName}"]`);
            if (field && !this.validateField(field)) {
                isValid = false;
            }
        });
        
        return isValid;
    }
    
    validateField(field) {
        const rule = this.rules[field.name];
        if (!rule) return true;
        
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';
        
        // Required validation
        if (rule.required && !value) {
            isValid = false;
            errorMessage = rule.requiredMessage || 'Este campo es obligatorio';
        }
        
        // Length validation
        if (isValid && rule.minLength && value.length < rule.minLength) {
            isValid = false;
            errorMessage = `Debe tener al menos ${rule.minLength} caracteres`;
        }
        
        if (isValid && rule.maxLength && value.length > rule.maxLength) {
            isValid = false;
            errorMessage = `No puede tener más de ${rule.maxLength} caracteres`;
        }
        
        // Pattern validation
        if (isValid && rule.pattern && !rule.pattern.test(value)) {
            isValid = false;
            errorMessage = rule.patternMessage || 'Formato inválido';
        }
        
        // Custom validation
        if (isValid && rule.custom) {
            const customResult = rule.custom(value);
            if (customResult !== true) {
                isValid = false;
                errorMessage = customResult || 'Valor inválido';
            }
        }
        
        if (isValid) {
            this.clearFieldError(field);
        } else {
            this.showFieldError(field, errorMessage);
        }
        
        return isValid;
    }
    
    showFieldError(field, message) {
        field.classList.add('is-invalid');
        
        let errorElement = field.parentElement.querySelector('.invalid-feedback');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'invalid-feedback';
            field.parentElement.appendChild(errorElement);
        }
        
        errorElement.textContent = message;
    }
    
    clearFieldError(field) {
        field.classList.remove('is-invalid');
        const errorElement = field.parentElement.querySelector('.invalid-feedback');
        if (errorElement) {
            errorElement.remove();
        }
    }
}

// Loading manager
class LoadingManager {
    static show(element, message = 'Cargando...') {
        element.classList.add('loading');
        element.setAttribute('aria-busy', 'true');
        element.setAttribute('aria-label', message);
    }
    
    static hide(element) {
        element.classList.remove('loading');
        element.removeAttribute('aria-busy');
        element.removeAttribute('aria-label');
    }
    
    static showGlobal(message = 'Cargando...') {
        let overlay = document.getElementById('global-loading');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.id = 'global-loading';
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1070;
                backdrop-filter: blur(2px);
            `;
            overlay.innerHTML = `
                <div style="text-align: center; color: white;">
                    <div style="width: 40px; height: 40px; border: 4px solid rgba(255,255,255,0.3); border-top: 4px solid white; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 1rem;"></div>
                    <div>${message}</div>
                </div>
            `;
            document.body.appendChild(overlay);
        }
    }
    
    static hideGlobal() {
        const overlay = document.getElementById('global-loading');
        if (overlay) {
            overlay.remove();
        }
    }
}

// Theme persistence and management
class ThemeManager {
    constructor() {
        this.currentTheme = this.getStoredTheme() || this.getSystemTheme();
        this.applyTheme(this.currentTheme);
        this.watchSystemChanges();
    }
    
    getStoredTheme() {
        return localStorage.getItem('theme');
    }
    
    getSystemTheme() {
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    
    applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        this.currentTheme = theme;
        localStorage.setItem('theme', theme);
        
        // Emit theme change event
        window.dispatchEvent(new CustomEvent('themeChanged', { 
            detail: { theme, previousTheme: this.previousTheme } 
        }));
        
        this.previousTheme = theme;
    }
    
    toggle() {
        const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        this.applyTheme(newTheme);
        return newTheme;
    }
    
    watchSystemChanges() {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!this.getStoredTheme()) {
                this.applyTheme(e.matches ? 'dark' : 'light');
            }
        });
    }
}

// Initialize theme manager
const themeManager = new ThemeManager();

// Keyboard navigation helper
class KeyboardNavigation {
    constructor() {
        this.init();
    }
    
    init() {
        document.addEventListener('keydown', (e) => {
            // ESC key to close modals/dropdowns
            if (e.key === 'Escape') {
                this.closeActiveElements();
            }
            
            // Tab navigation enhancement
            if (e.key === 'Tab') {
                this.handleTabNavigation(e);
            }
            
            // Arrow key navigation for custom components
            if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                this.handleArrowNavigation(e);
            }
        });
        
        // Add focus indicators
        document.addEventListener('keydown', () => {
            document.body.classList.add('keyboard-navigation');
        });
        
        document.addEventListener('mousedown', () => {
            document.body.classList.remove('keyboard-navigation');
        });
    }
    
    closeActiveElements() {
        // Close modals
        const modals = document.querySelectorAll('.modal.show');
        modals.forEach(modal => {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) bsModal.hide();
        });
        
        // Close dropdowns
        const dropdowns = document.querySelectorAll('.dropdown-menu.show');
        dropdowns.forEach(dropdown => {
            const toggle = dropdown.previousElementSibling;
            if (toggle) toggle.click();
        });
    }
    
    handleTabNavigation(e) {
        const focusableElements = document.querySelectorAll(
            'a[href], button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])'
        );
        
        const currentIndex = Array.from(focusableElements).indexOf(document.activeElement);
        
        if (e.shiftKey && currentIndex === 0) {
            e.preventDefault();
            focusableElements[focusableElements.length - 1].focus();
        } else if (!e.shiftKey && currentIndex === focusableElements.length - 1) {
            e.preventDefault();
            focusableElements[0].focus();
        }
    }
    
    handleArrowNavigation(e) {
        const activeElement = document.activeElement;
        
        // Handle dropdown navigation
        if (activeElement.closest('.dropdown-menu')) {
            e.preventDefault();
            const items = activeElement.closest('.dropdown-menu').querySelectorAll('.dropdown-item');
            const currentIndex = Array.from(items).indexOf(activeElement);
            
            if (e.key === 'ArrowDown') {
                const nextIndex = (currentIndex + 1) % items.length;
                items[nextIndex].focus();
            } else if (e.key === 'ArrowUp') {
                const prevIndex = currentIndex === 0 ? items.length - 1 : currentIndex - 1;
                items[prevIndex].focus();
            }
        }
    }
}

// Initialize keyboard navigation
const keyboardNavigation = new KeyboardNavigation();

// Performance monitor
class PerformanceMonitor {
    constructor() {
        this.metrics = {};
        this.init();
    }
    
    init() {
        if ('PerformanceObserver' in window) {
            this.observeLCP();
            this.observeFID();
            this.observeCLS();
        }
    }
    
    observeLCP() {
        const observer = new PerformanceObserver((list) => {
            const entries = list.getEntries();
            const lastEntry = entries[entries.length - 1];
            this.metrics.lcp = lastEntry.startTime;
        });
        observer.observe({ entryTypes: ['largest-contentful-paint'] });
    }
    
    observeFID() {
        const observer = new PerformanceObserver((list) => {
            const entries = list.getEntries();
            entries.forEach(entry => {
                this.metrics.fid = entry.processingStart - entry.startTime;
            });
        });
        observer.observe({ entryTypes: ['first-input'] });
    }
    
    observeCLS() {
        let clsValue = 0;
        const observer = new PerformanceObserver((list) => {
            const entries = list.getEntries();
            entries.forEach(entry => {
                if (!entry.hadRecentInput) {
                    clsValue += entry.value;
                }
            });
            this.metrics.cls = clsValue;
        });
        observer.observe({ entryTypes: ['layout-shift'] });
    }
    
    getMetrics() {
        return this.metrics;
    }
}

// Initialize performance monitor
const performanceMonitor = new PerformanceMonitor();

// Export for global use
window.notificationManager = notificationManager;
window.FormValidator = FormValidator;
window.LoadingManager = LoadingManager;
window.themeManager = themeManager;
window.performanceMonitor = performanceMonitor;
