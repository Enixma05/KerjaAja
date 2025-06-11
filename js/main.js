// Show toast/alert message
function showToast(message, type = 'success') {
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
    closeBtn.addEventListener('click', () => {
        hideToast(toast);
    });
}

// Hide toast message
function hideToast(toast) {
    toast.classList.remove('show');
    setTimeout(() => {
        document.body.removeChild(toast);
    }, 300);
}

// Add toast styles
const toastStyles = document.createElement('style');
toastStyles.textContent = `
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: white;
        border-radius: 4px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 1000;
        transform: translateX(110%);
        transition: transform 0.3s ease;
        min-width: 300px;
        max-width: 400px;
    }
    
    .toast.show {
        transform: translateX(0);
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
    }
`;
document.head.appendChild(toastStyles);

// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    // Add responsive styles for mobile
    const mobileStyles = document.createElement('style');
    mobileStyles.textContent = `
        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
            
            .hero .container {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .hero-buttons {
                justify-content: center;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
            }
            
            .dashboard-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid var(--gray-200);
                padding: 1rem 0;
            }
            
            .sidebar-nav {
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
            
            .dashboard-cards, .training-grid, .job-grid, .stats-grid, .charts-grid {
                grid-template-columns: 1fr;
            }
            
            .data-table-container {
                overflow-x: auto;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    `;
    document.head.appendChild(mobileStyles);
    
    // Handle alert function (alternative to showToast)
    window.alert = function(message) {
        showToast(message);
        return undefined;
    };
});

// Modal functionality Pelatihan
document.addEventListener("DOMContentLoaded", () => {
  // Get modal elements
  const trainingModal = document.getElementById("trainingModal")
  const deleteModal = document.getElementById("deleteModal")
  const closeButtons = document.querySelectorAll(".close-modal")
  const cancelTraining = document.getElementById("cancelTraining")
  const cancelDelete = document.getElementById("cancelDelete")

  // Function to open modal
  function openModal(modal) {
    modal.style.display = "flex"
    setTimeout(() => {
      modal.classList.add("show")
    }, 10)
    document.body.style.overflow = "hidden" // Prevent scrolling
  }

  // Function to close modal
  function closeModal(modal) {
    modal.classList.remove("show")
    setTimeout(() => {
      modal.style.display = "none"
      document.body.style.overflow = "" // Re-enable scrolling
    }, 300)
  }

  // Close modal when clicking the X button
  closeButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const modal = this.closest(".modal")
      closeModal(modal)
    })
  })

  // Close modal when clicking cancel button
  if (cancelTraining) {
    cancelTraining.addEventListener("click", () => {
      closeModal(trainingModal)
    })
  }

  if (cancelDelete) {
    cancelDelete.addEventListener("click", () => {
      closeModal(deleteModal)
    })
  }

  // Close modal when clicking outside the modal content
  window.addEventListener("click", (event) => {
    if (event.target.classList.contains("modal")) {
      closeModal(event.target)
    }
  })

  // Add training button functionality (if exists)
  const addTrainingBtn = document.getElementById("addTrainingBtn")
  if (addTrainingBtn) {
    addTrainingBtn.addEventListener("click", () => {
      // Reset form
      document.getElementById("trainingForm").reset()
      document.getElementById("trainingId").value = ""
      document.getElementById("modalTitle").textContent = "Tambah Pelatihan Baru"
      document.getElementById("modalDescription").textContent = "Isi form berikut untuk menambahkan pelatihan baru"

      openModal(trainingModal)
    })
  }

  // Edit training functionality
  const editButtons = document.querySelectorAll(".edit-training-btn")
  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const trainingId = this.getAttribute("data-id")
      const training = mockAdminTrainings.find((t) => t.id == trainingId)

      if (training) {
        document.getElementById("trainingId").value = training.id
        document.getElementById("name").value = training.name
        document.getElementById("date").value = training.date
        document.getElementById("location").value = training.location
        document.getElementById("quota").value = training.quota
        document.getElementById("description").value = training.description

        document.getElementById("modalTitle").textContent = "Edit Pelatihan"
        document.getElementById("modalDescription").textContent = "Ubah informasi pelatihan"

        openModal(trainingModal)
      }
    })
  })

  // Delete training functionality
  const deleteButtons = document.querySelectorAll(".delete-training-btn")
  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const trainingId = this.getAttribute("data-id")

      // Set the training ID for the delete confirmation
      document.getElementById("confirmDelete").setAttribute("data-id", trainingId)

      openModal(deleteModal)
    })
  })

  // Confirm delete
  const confirmDelete = document.getElementById("confirmDelete")
  if (confirmDelete) {
    confirmDelete.addEventListener("click", function () {
      const trainingId = this.getAttribute("data-id")

      closeModal(deleteModal)
      showToast("Pelatihan berhasil dihapus", "success")
    })
  }
  
  // Show toast function (for demonstration purposes)
  function showToast(message, type) {
    const toastContainer = document.getElementById("toast-container")

    if (!toastContainer) {
      const container = document.createElement("div")
      container.id = "toast-container"
      container.style.position = "fixed"
      container.style.top = "20px"
      container.style.right = "20px"
      container.style.zIndex = "1000"
      document.body.appendChild(container)
    }

    const toast = document.createElement("div")
    toast.classList.add("toast")
    toast.textContent = message
    toast.style.backgroundColor = type === "success" ? "green" : "red"
    toast.style.color = "white"
    toast.style.padding = "10px"
    toast.style.marginBottom = "5px"
    toast.style.borderRadius = "5px"

    document.getElementById("toast-container").appendChild(toast)

    setTimeout(() => {
      toast.remove()
    }, 3000)
  } 
});


//job dashboard modal functionality
document.addEventListener("DOMContentLoaded", () => {
  // Definisikan jobModal agar bisa dipakai di mana saja
  const jobModal = document.getElementById("modalTambahLowongan");

  const addJobBtn = document.getElementById("addJobBtn");
  if (addJobBtn) {
    addJobBtn.addEventListener("click", () => {
      // Reset form lowongan
      const form = document.getElementById("formTambahLowongan");
      if (form) form.reset();

      // Reset jobId hidden input jika ada
      const jobIdInput = document.getElementById("jobId");
      if (jobIdInput) jobIdInput.value = "";

      // Set judul modal jika ada
      const modalTitle = jobModal.querySelector("#modalTitle");
      if (modalTitle) modalTitle.textContent = "Tambah Lowongan Baru";

      const modalDescription = jobModal.querySelector("#modalDescription");
      if (modalDescription) modalDescription.textContent = "Isi form berikut untuk menambahkan lowongan baru";

      // Gunakan fungsi openModal agar konsisten dengan modal lain
      openModal(jobModal);
    });
  }

  // Fungsi openModal dan closeModal dari kode sebelumnya
  function openModal(modal) {
    modal.style.display = "flex";
    setTimeout(() => {
      modal.classList.add("show");
    }, 10);
    document.body.style.overflow = "hidden";
  }

  function closeModal(modal) {
    modal.classList.remove("show");
    setTimeout(() => {
      modal.style.display = "none";
      document.body.style.overflow = "";
    }, 300);
  }

  // Tombol close modal lowongan
  const closeTambahLowongan = document.getElementById('closeTambahLowongan');
  const batalTambahLowongan = document.getElementById('batalTambahLowongan');
  if (closeTambahLowongan) {
    closeTambahLowongan.addEventListener('click', () => closeModal(jobModal));
  }
  if (batalTambahLowongan) {
    batalTambahLowongan.addEventListener('click', () => closeModal(jobModal));
  }

  // Tutup modal kalau klik di luar konten modal
  window.addEventListener('click', (e) => {
    if (e.target === jobModal) {
      closeModal(jobModal);
    }
  });
});




