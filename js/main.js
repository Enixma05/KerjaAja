// ========================================
//         KERJAAJA ADMIN PANEL
// ========================================

// Toast/Alert functionality
function showToast(message, type = 'success') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.toast');
    existingToasts.forEach(toast => toast.remove());
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="${type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
        <button class="toast-close">&times;</button>
    `;
    
    // Add to document
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        hideToast(toast);
    }, 3000);
    
    // Close button
    const closeBtn = toast.querySelector('.toast-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            hideToast(toast);
        });
    }
}

// Hide toast message
function hideToast(toast) {
    if (toast && toast.parentNode) {
        toast.classList.remove('show');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }
}

// ========================================
// MODAL FUNCTIONALITY (UNIFIED)
// ========================================

// Global modal functions
function openModal(modal) {
    if (!modal) {
        console.error('Modal element not found');
        return;
    }
    
    modal.style.display = "flex";
    setTimeout(() => {
        modal.classList.add("show");
    }, 10);
    document.body.style.overflow = "hidden";
    console.log('Modal opened:', modal.id);
}

function closeModal(modal) {
    if (!modal) return;
    
    modal.classList.remove("show");
    setTimeout(() => {
        modal.style.display = "none";
        document.body.style.overflow = "";
    }, 300);
    console.log('Modal closed:', modal.id);
}

function closeAllModals() {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => closeModal(modal));
}

// ========================================
// INITIALIZATION
// ========================================

document.addEventListener("DOMContentLoaded", function() {
    console.log("KerjaAja Admin Panel - Initializing...");
    
    // Add toast styles if not already added
    if (!document.getElementById('toast-styles')) {
        addToastStyles();
    }
    
    // Add responsive styles
    addResponsiveStyles();
    
    // Initialize all functionalities
    initializeTrainingModal();
    initializeJobModal();
    initializeCompanyModal();
    initializeGeneralModal();
    initializeSearch();
    initializeLogout();
    
    console.log("KerjaAja Admin Panel - Initialization complete");
});

// ========================================
// TRAINING MODAL FUNCTIONALITY
// ========================================

function initializeTrainingModal() {
    console.log("Initializing training modal...");
    
    const addTrainingBtn = document.getElementById("addTrainingBtn");
    const trainingModal = document.getElementById("trainingModal");
    const trainingForm = document.getElementById("trainingForm");
    
    if (!addTrainingBtn || !trainingModal) {
        console.log("Training modal elements not found, skipping...");
        return;
    }
    
    // Add Training Button
    addTrainingBtn.addEventListener("click", function(e) {
        e.preventDefault();
        console.log("Add Training button clicked");
        
        try {
            // Reset form
            if (trainingForm) trainingForm.reset();
            
            // Set form values
            const elements = {
                trainingId: document.getElementById("trainingId"),
                modalTitle: document.getElementById("modalTitle"),
                modalDescription: document.getElementById("modalDescription")
            };
            
            if (elements.trainingId) elements.trainingId.value = "";
            if (elements.modalTitle) elements.modalTitle.textContent = "Tambah Pelatihan Baru";
            if (elements.modalDescription) elements.modalDescription.textContent = "Isi form berikut untuk menambahkan pelatihan baru";
            if (trainingForm) trainingForm.action = "tambah_pelatihan.php";
            
            // Open modal
            openModal(trainingModal);
            
        } catch (error) {
            console.error("Error opening training modal:", error);
            showToast("Terjadi kesalahan saat membuka form", "error");
        }
    });
    
    // Edit Training Buttons
    const editButtons = document.querySelectorAll(".edit-training-btn, .edit-btn");
    editButtons.forEach(button => {
        button.addEventListener("click", function(e) {
            e.preventDefault();
            const trainingId = this.getAttribute("data-id") || this.getAttribute("href")?.split("id=")[1];
            
            if (trainingId) {
                // Set form for editing
                const trainingIdInput = document.getElementById("trainingId");
                const modalTitle = document.getElementById("modalTitle");
                const modalDescription = document.getElementById("modalDescription");
                
                if (trainingIdInput) trainingIdInput.value = trainingId;
                if (modalTitle) modalTitle.textContent = "Edit Pelatihan";
                if (modalDescription) modalDescription.textContent = "Ubah informasi pelatihan";
                if (trainingForm) trainingForm.action = "edit_pelatihan.php";
                
                openModal(trainingModal);
            }
        });
    });
    
    console.log("Training modal initialized");
}

// ========================================
// JOB MODAL FUNCTIONALITY
// ========================================

function initializeJobModal() {
    console.log("Initializing job modal...");
    
    const addJobBtn = document.getElementById("addJobBtn");
    const jobModal = document.getElementById("modalTambahLowongan");
    const jobForm = document.getElementById("formTambahLowongan");
    
    if (!addJobBtn || !jobModal) {
        console.log("Job modal elements not found, skipping...");
        return;
    }
    
    // Add Job Button
    addJobBtn.addEventListener("click", function(e) {
        e.preventDefault();
        console.log("Add Job button clicked");
        
        try {
            // Reset form
            if (jobForm) jobForm.reset();
            
            // Set form values
            const jobIdInput = document.getElementById("jobId");
            const modalTitle = jobModal.querySelector("#modalTitle");
            const modalDescription = jobModal.querySelector("#modalDescription");
            
            if (jobIdInput) jobIdInput.value = "";
            if (modalTitle) modalTitle.textContent = "Tambah Lowongan Baru";
            if (modalDescription) modalDescription.textContent = "Isi form berikut untuk menambahkan lowongan baru";
            
            // Open modal
            openModal(jobModal);
            
        } catch (error) {
            console.error("Error opening job modal:", error);
            showToast("Terjadi kesalahan saat membuka form", "error");
        }
    });
    
    console.log("Job modal initialized");
}

// ========================================
// COMPANY MODAL FUNCTIONALITY
// ========================================

function initializeCompanyModal() {
    console.log("Initializing company modal...");
    
    const addCompanyBtn = document.getElementById("addCompanyBtn");
    const companyModal = document.getElementById("companyModal");
    
    if (!addCompanyBtn || !companyModal) {
        console.log("Company modal elements not found, skipping...");
        return;
    }
    
    addCompanyBtn.addEventListener("click", function(e) {
        e.preventDefault();
        console.log("Add Company button clicked");
        openModal(companyModal);
    });
    
    console.log("Company modal initialized");
}

// ========================================
// GENERAL MODAL FUNCTIONALITY
// ========================================

function initializeGeneralModal() {
    console.log("Initializing general modal handlers...");
    
    // Close button handlers
    const closeButtons = document.querySelectorAll(".close-modal, .close, [data-close='modal']");
    closeButtons.forEach(button => {
        button.addEventListener("click", function() {
            const modal = this.closest(".modal");
            if (modal) closeModal(modal);
        });
    });
    
    // Cancel button handlers
    const cancelButtons = document.querySelectorAll("#cancelTraining, #cancelDelete, #batalTambahLowongan, .btn-cancel");
    cancelButtons.forEach(button => {
        button.addEventListener("click", function() {
            const modal = this.closest(".modal");
            if (modal) closeModal(modal);
        });
    });
    
    // Click outside modal to close
    window.addEventListener("click", function(event) {
        if (event.target.classList.contains("modal")) {
            closeModal(event.target);
        }
    });
    
    // ESC key to close modal
    document.addEventListener("keydown", function(event) {
        if (event.key === "Escape") {
            closeAllModals();
        }
    });
    
    console.log("General modal handlers initialized");
}

// ========================================
// SEARCH FUNCTIONALITY
// ========================================

function initializeSearch() {
    const searchInputs = document.querySelectorAll("#searchTraining, #searchJob, #searchCompany, .search-input input");
    
    searchInputs.forEach(searchInput => {
        if (searchInput) {
            searchInput.addEventListener("keyup", function() {
                const filter = this.value.toLowerCase();
                const tableId = this.id.replace("search", "").toLowerCase() + "Table";
                const table = document.getElementById(tableId) || document.querySelector(".data-table");
                
                if (table) {
                    const rows = table.getElementsByTagName("tr");
                    
                    for (let i = 1; i < rows.length; i++) {
                        const cells = rows[i].getElementsByTagName("td");
                        let found = false;
                        
                        for (let j = 0; j < cells.length - 1; j++) {
                            if (cells[j] && cells[j].textContent.toLowerCase().includes(filter)) {
                                found = true;
                                break;
                            }
                        }
                        
                        rows[i].style.display = found ? "" : "none";
                    }
                }
            });
        }
    });
    
    console.log("Search functionality initialized");
}

// ========================================
// LOGOUT FUNCTIONALITY
// ========================================

function initializeLogout() {
    const logoutBtn = document.getElementById("logoutBtn");
    if (logoutBtn) {
        logoutBtn.addEventListener("click", function(e) {
            e.preventDefault();
            if (confirm("Apakah Anda yakin ingin logout?")) {
                window.location.href = "../auth/logout.php";
            }
        });
    }
    
    console.log("Logout functionality initialized");
}

// ========================================
// STYLES
// ========================================

function addToastStyles() {
    const toastStyles = document.createElement('style');
    toastStyles.id = 'toast-styles';
    toastStyles.textContent = `
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 9999;
            transform: translateX(110%);
            transition: transform 0.3s ease;
            min-width: 300px;
            max-width: 400px;
            border-left: 4px solid #10b981;
        }
        
        .toast.show {
            transform: translateX(0);
        }
        
        .toast-error {
            border-left-color: #ef4444;
        }
        
        .toast-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .toast-success i {
            color: #10b981;
        }
        
        .toast-error i {
            color: #ef4444;
        }
        
        .toast-close {
            background: none;
            border: none;
            font-size: 1.25rem;
            cursor: pointer;
            color: #6b7280;
            padding: 0;
            margin-left: 1rem;
        }
        
        .toast-close:hover {
            color: #374151;
        }
    `;
    document.head.appendChild(toastStyles);
}

function addResponsiveStyles() {
    if (document.getElementById('responsive-styles')) return;
    
    const mobileStyles = document.createElement('style');
    mobileStyles.id = 'responsive-styles';
    mobileStyles.textContent = `
        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
            
            .dashboard-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e5e7eb;
                padding: 1rem 0;
            }
            
            .sidebar-nav {
                display: flex;
                flex-direction: row;
                overflow-x: auto;
                padding: 0 1rem;
            }
            
            .sidebar-nav a {
                padding: 0.5rem 1rem;
                white-space: nowrap;
            }
            
            .main-content {
                padding: 1rem;
            }
            
            .data-table-container {
                overflow-x: auto;
            }
            
            .modal-content {
                margin: 10px;
                width: calc(100% - 20px);
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .toast {
                right: 10px;
                left: 10px;
                max-width: none;
                min-width: auto;
            }
        }
    `;
    document.head.appendChild(mobileStyles);
}

// ========================================
// GLOBAL FUNCTIONS FOR TESTING
// ========================================

// Test functions - dapat dipanggil dari console
window.testModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        openModal(modal);
        console.log(`Testing modal: ${modalId}`);
    } else {
        console.error(`Modal not found: ${modalId}`);
    }
};

window.testToast = function(message = "Test message", type = "success") {
    showToast(message, type);
};
  
// Override default alert
window.alert = function(message) {
    showToast(message, 'success');
};

console.log("KerjaAja Admin Panel - Main.js loaded successfully");