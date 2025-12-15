// Shared JavaScript functions

// Initialize tooltips
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Show alert message
function showAlert(message, type = 'success') {
    const alertTypes = {
        success: 'bg-green-100 border-green-400 text-green-700',
        error: 'bg-red-100 border-red-400 text-red-700',
        warning: 'bg-yellow-100 border-yellow-400 text-yellow-700',
        info: 'bg-blue-100 border-blue-400 text-blue-700'
    };
    
    const alertHtml = `
        <div class="fixed top-4 right-4 max-w-sm w-full ${alertTypes[type]} border px-4 py-3 rounded animate-fadeIn z-50">
            <div class="flex items-center">
                <span class="block sm:inline">${message}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.parentElement.remove()">
                    <i data-feather="x"></i>
                </span>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', alertHtml);
    feather.replace();
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.animate-fadeIn');
        alerts.forEach(alert => {
            alert.remove();
        });
    }, 5000);
}

// Confirm dialog
function confirmAction(message, callback) {
    const confirmHtml = `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-6 w-6 text-yellow-500">
                        <i data-feather="alert-triangle"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-gray-900">Confirmation</h3>
                        <div class="mt-2 text-sm text-gray-500">
                            <p>${message}</p>
                        </div>
                        <div class="mt-4 flex justify-end space-x-3">
                            <button type="button" class="btn-cancel px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none">
                                Annuler
                            </button>
                            <button type="button" class="btn-confirm px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none">
                                Confirmer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const confirmEl = document.createElement('div');
    confirmEl.innerHTML = confirmHtml;
    document.body.appendChild(confirmEl);
    feather.replace();
    
    confirmEl.querySelector('.btn-cancel').addEventListener('click', () => {
        confirmEl.remove();
    });
    
    confirmEl.querySelector('.btn-confirm').addEventListener('click', () => {
        callback();
        confirmEl.remove();
    });
}

// Initialize datepickers
function initDatepickers() {
    document.querySelectorAll('[data-datepicker]').forEach(input => {
        new Datepicker(input, {
            format: 'dd/mm/yyyy',
            autohide: true,
            language: 'fr'
        });
    });
}

// Export to PDF
function exportToPDF(elementId, filename = 'document') {
    const element = document.getElementById(elementId);
    const opt = {
        margin: 10,
        filename: `${filename}.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };
    
    html2pdf().set(opt).from(element).save();
}

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
    initTooltips();
    initDatepickers();
    feather.replace();
});

// Handle delete buttons
document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const itemName = this.getAttribute('data-item-name') || 'cet élément';
        confirmAction(`Êtes-vous sûr de vouloir supprimer ${itemName} ? Cette action est irréversible.`, () => {
            // Here you would typically submit a form or make an AJAX request
            showAlert(`${itemName} a été supprimé avec succès`, 'success');
        });
    });
});