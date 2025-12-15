// Shared JavaScript functions

// Initialize tooltips
function initTooltips() {
    // ... (unchanged) ...
}

// Show alert message
function showAlert(message, type = 'success') {
    // ... (unchanged) ...
}

// Confirm dialog
function confirmAction(message, callback) {
    // ... (unchanged) ...
}

// Initialize datepickers
function initDatepickers() {
    // ... (unchanged) ...
}

// Export to PDF
function exportToPDF(elementId, filename = 'document') {
    // ... (unchanged) ...
}

// Initialize Detail Panel for Conseils
function initDetailPanelConseils() {
    const detailPanel = document.getElementById('detailPanel');
    const closeDetailPanelBtn = document.getElementById('closeDetailPanel');
    
    document.querySelectorAll('.view-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const conseilId = this.getAttribute('data-id');
            
            fetch(`get_conseil_details.php?id=${conseilId}`)
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        const data = result.data;
                        document.getElementById('detailTitle').textContent = data.title;
                        document.getElementById('detailAuthor').textContent = data.author;
                        document.getElementById('detailLocation').textContent = data.location || '-';
                        document.getElementById('detailDate').textContent = data.created_at_formatted;
                        document.getElementById('detailContent').textContent = data.content;
                        document.getElementById('detailAnecdote').textContent = data.anecdote || 'Aucune anecdote fournie.';
                        
                        detailPanel.classList.remove('translate-x-full');
                    } else {
                        alert(result.message);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Impossible de récupérer les détails.');
                });
        });
    });
    
    closeDetailPanelBtn.addEventListener('click', function() {
        detailPanel.classList.add('translate-x-full');
    });
}

// Initialize Detail Panel for Publicités
function initDetailPanelPublicites() {
    const detailPanel = document.getElementById('detailPanel');
    const closeDetailPanelBtn = document.getElementById('closeDetailPanel');
    
    document.querySelectorAll('.view-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const pubId = this.getAttribute('data-id');
            
            fetch(`get_publicite_details.php?id=${pubId}`)
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        const data = result.data;
                        document.getElementById('detailImage').src = data.image_url || 'https://via.placeholder.com/300x150?text=Pas+d\'image';
                        document.getElementById('detailTitle').textContent = data.title;
                        document.getElementById('detailStatus').textContent = data.status_text;
                        const targetUrlLink = document.getElementById('detailTargetUrl');
                        targetUrlLink.href = data.target_url;
                        targetUrlLink.textContent = data.target_url;
                        document.getElementById('detailStartDate').textContent = data.start_date_formatted;
                        document.getElementById('detailEndDate').textContent = data.end_date_formatted;
                        document.getElementById('detailContent').textContent = data.content;
                        
                        detailPanel.classList.remove('translate-x-full');
                    } else {
                        alert(result.message);
                    }
                })
                .catch(error => console.error('Erreur:', error));
        });
    });
    
    closeDetailPanelBtn.addEventListener('click', () => {
        detailPanel.classList.add('translate-x-full');
    });
}


// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
    feather.replace(); // Call feather.replace() once here
    initTooltips();
    initDatepickers();

    // Toggle sidebar on mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
        });
    }

    // Toggle dropdown menus
    const usersMenuBtn = document.getElementById('usersMenuBtn');
    const usersMenu = document.getElementById('usersMenu');
    if (usersMenuBtn && usersMenu) {
        usersMenuBtn.addEventListener('click', function() {
            usersMenu.classList.toggle('hidden');
            usersMenuBtn.querySelector('i:last-child').classList.toggle('rotate-180');
        });
    }

    const settingsMenuBtn = document.getElementById('settingsMenuBtn');
    const settingsMenu = document.getElementById('settingsMenu');
    if (settingsMenuBtn && settingsMenu) {
        settingsMenuBtn.addEventListener('click', function() {
            settingsMenu.classList.toggle('hidden');
            settingsMenuBtn.querySelector('i:last-child').classList.toggle('rotate-180');
        });
    }

    // Initialize detail panels conditionally
    if (document.getElementById('detailPanel') && document.querySelector('[data-id][title="Voir"]')) {
        // Check if it's the conseils or publicites page based on the elements present
        if (window.location.pathname.includes('conseils.php')) {
            initDetailPanelConseils();
        } else if (window.location.pathname.includes('publicites.php')) {
            initDetailPanelPublicites();
        }
    }
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