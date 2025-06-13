// Notifications Page Manager
class NotificationsPageManager {
  constructor() {
    this.currentFilter = "all"
    this.currentSort = "newest"
    this.currentPage = 1
    this.itemsPerPage = 10
    this.searchQuery = ""
    this.filteredNotifications = []
    this.settings = this.loadSettings()
    this.init()
  }

  init() {
    this.setupEventListeners()
    this.updateStats()
    this.applyFilters()
    this.renderNotifications()
    this.renderPagination()
  }

  setupEventListeners() {
    // Filter tabs
    document.querySelectorAll(".filter-tab").forEach((tab) => {
      tab.addEventListener("click", (e) => {
        this.setActiveFilter(e.target.dataset.filter)
      })
    })

    // Search
    const searchInput = document.getElementById("searchNotifications")
    if (searchInput) {
      searchInput.addEventListener("input", (e) => {
        this.searchQuery = e.target.value.toLowerCase()
        this.currentPage = 1
        this.applyFilters()
        this.renderNotifications()
        this.renderPagination()
      })
    }

    // Sort
    const sortSelect = document.getElementById("sortNotifications")
    if (sortSelect) {
      sortSelect.addEventListener("change", (e) => {
        this.currentSort = e.target.value
        this.applyFilters()
        this.renderNotifications()
      })
    }

    // Action buttons
    document.getElementById("markAllReadBtn")?.addEventListener("click", () => {
      this.markAllAsRead()
    })

    document.getElementById("clearAllBtn")?.addEventListener("click", () => {
      this.clearAllNotifications()
    })

    document.getElementById("notificationSettingsBtn")?.addEventListener("click", () => {
      this.openSettingsModal()
    })

    // Settings modal
    document.getElementById("closeSettingsModal")?.addEventListener("click", () => {
      this.closeSettingsModal()
    })

    document.getElementById("cancelSettings")?.addEventListener("click", () => {
      this.closeSettingsModal()
    })

    document.getElementById("saveSettings")?.addEventListener("click", () => {
      this.saveSettings()
    })

    // Close modal on overlay click
    document.getElementById("settingsModal")?.addEventListener("click", (e) => {
      if (e.target.id === "settingsModal") {
        this.closeSettingsModal()
      }
    })
  }

  setActiveFilter(filter) {
    this.currentFilter = filter
    this.currentPage = 1

    // Update active tab
    document.querySelectorAll(".filter-tab").forEach((tab) => {
      tab.classList.remove("active")
    })
    document.querySelector(`[data-filter="${filter}"]`).classList.add("active")

    this.applyFilters()
    this.renderNotifications()
    this.renderPagination()
  }

  applyFilters() {
    let notifications = [...window.notificationManager.notifications]

    // Apply type filter
    if (this.currentFilter !== "all") {
      if (this.currentFilter === "unread") {
        notifications = notifications.filter((n) => !n.read)
      } else {
        notifications = notifications.filter((n) => n.type === this.currentFilter)
      }
    }

    // Apply search filter
    if (this.searchQuery) {
      notifications = notifications.filter(
        (n) => n.title.toLowerCase().includes(this.searchQuery) || n.message.toLowerCase().includes(this.searchQuery),
      )
    }

    // Apply sorting
    notifications.sort((a, b) => {
      switch (this.currentSort) {
        case "newest":
          return new Date(b.time) - new Date(a.time)
        case "oldest":
          return new Date(a.time) - new Date(b.time)
        case "unread":
          if (a.read === b.read) return new Date(b.time) - new Date(a.time)
          return a.read ? 1 : -1
        case "type":
          if (a.type === b.type) return new Date(b.time) - new Date(a.time)
          return a.type.localeCompare(b.type)
        default:
          return new Date(b.time) - new Date(a.time)
      }
    })

    this.filteredNotifications = notifications
  }

  renderNotifications() {
    const container = document.getElementById("notificationsList")
    const emptyState = document.getElementById("emptyState")

    if (!container) return

    const startIndex = (this.currentPage - 1) * this.itemsPerPage
    const endIndex = startIndex + this.itemsPerPage
    const pageNotifications = this.filteredNotifications.slice(startIndex, endIndex)

    if (pageNotifications.length === 0) {
      container.innerHTML = ""
      emptyState.style.display = "block"
      return
    }

    emptyState.style.display = "none"

    container.innerHTML = pageNotifications
      .map(
        (notification) => `
      <div class="notification-item-full ${!notification.read ? "unread" : ""}" 
           data-id="${notification.id}">
        <div class="notification-item-icon ${notification.type}">
          <i class="fas ${this.getNotificationIcon(notification.type)}"></i>
        </div>
        <div class="notification-item-content">
          <div class="notification-item-header">
            <h4 class="notification-item-title">${notification.title}</h4>
            <span class="notification-item-time">${this.formatTime(notification.time)}</span>
          </div>
          <p class="notification-item-message">${notification.message}</p>
          <div class="notification-item-actions">
            ${
              !notification.read
                ? `
              <button class="notification-item-action" onclick="notificationsPageManager.markAsRead(${notification.id})">
                <i class="fas fa-check"></i>
                Tandai Dibaca
              </button>
            `
                : ""
            }
            ${
              notification.actionUrl
                ? `
              <a href="${notification.actionUrl}" class="notification-item-action primary">
                <i class="fas fa-external-link-alt"></i>
                ${notification.actionText || "Lihat"}
              </a>
            `
                : ""
            }
            <button class="notification-item-action danger" onclick="notificationsPageManager.deleteNotification(${notification.id})">
              <i class="fas fa-trash"></i>
              Hapus
            </button>
          </div>
        </div>
      </div>
    `,
      )
      .join("")
  }

  renderPagination() {
    const container = document.getElementById("pagination")
    const infoContainer = document.getElementById("paginationInfo")

    if (!container || !infoContainer) return

    const totalItems = this.filteredNotifications.length
    const totalPages = Math.ceil(totalItems / this.itemsPerPage)
    const startIndex = (this.currentPage - 1) * this.itemsPerPage + 1
    const endIndex = Math.min(startIndex + this.itemsPerPage - 1, totalItems)

    // Update info
    if (totalItems === 0) {
      infoContainer.textContent = "Tidak ada notifikasi"
    } else {
      infoContainer.textContent = `Menampilkan ${startIndex}-${endIndex} dari ${totalItems} notifikasi`
    }

    // Generate pagination buttons
    let paginationHTML = ""

    // Previous button
    paginationHTML += `
      <button class="pagination-btn" ${this.currentPage === 1 ? "disabled" : ""} 
              onclick="notificationsPageManager.goToPage(${this.currentPage - 1})">
        <i class="fas fa-chevron-left"></i>
      </button>
    `

    // Page numbers
    const maxVisiblePages = 5
    let startPage = Math.max(1, this.currentPage - Math.floor(maxVisiblePages / 2))
    const endPage = Math.min(totalPages, startPage + maxVisiblePages - 1)

    if (endPage - startPage + 1 < maxVisiblePages) {
      startPage = Math.max(1, endPage - maxVisiblePages + 1)
    }

    for (let i = startPage; i <= endPage; i++) {
      paginationHTML += `
        <button class="pagination-btn ${i === this.currentPage ? "active" : ""}" 
                onclick="notificationsPageManager.goToPage(${i})">
          ${i}
        </button>
      `
    }

    // Next button
    paginationHTML += `
      <button class="pagination-btn" ${this.currentPage === totalPages || totalPages === 0 ? "disabled" : ""} 
              onclick="notificationsPageManager.goToPage(${this.currentPage + 1})">
        <i class="fas fa-chevron-right"></i>
      </button>
    `

    container.innerHTML = paginationHTML
  }

  goToPage(page) {
    const totalPages = Math.ceil(this.filteredNotifications.length / this.itemsPerPage)
    if (page >= 1 && page <= totalPages) {
      this.currentPage = page
      this.renderNotifications()
      this.renderPagination()

      // Scroll to top of notifications
      document.querySelector(".notifications-container").scrollIntoView({
        behavior: "smooth",
      })
    }
  }

  updateStats() {
    const notifications = window.notificationManager.notifications
    const unreadCount = notifications.filter((n) => !n.read).length
    const totalCount = notifications.length

    // Count today's notifications
    const today = new Date()
    today.setHours(0, 0, 0, 0)
    const todayCount = notifications.filter((n) => {
      const notificationDate = new Date(n.time)
      notificationDate.setHours(0, 0, 0, 0)
      return notificationDate.getTime() === today.getTime()
    }).length

    document.getElementById("unreadCount").textContent = unreadCount
    document.getElementById("totalCount").textContent = totalCount
    document.getElementById("todayCount").textContent = todayCount
  }

  markAsRead(notificationId) {
    window.notificationManager.markAsRead(notificationId)
    this.updateStats()
    this.applyFilters()
    this.renderNotifications()
    this.renderPagination()
  }

  markAllAsRead() {
    if (confirm("Tandai semua notifikasi sebagai dibaca?")) {
      window.notificationManager.markAllAsRead()
      this.updateStats()
      this.applyFilters()
      this.renderNotifications()
      this.renderPagination()
    }
  }

  deleteNotification(notificationId) {
    if (confirm("Hapus notifikasi ini?")) {
      const index = window.notificationManager.notifications.findIndex((n) => n.id === notificationId)
      if (index !== -1) {
        window.notificationManager.notifications.splice(index, 1)
        window.notificationManager.saveNotifications()
        window.notificationManager.updateNotificationDisplay()

        this.updateStats()
        this.applyFilters()
        this.renderNotifications()
        this.renderPagination()

        window.notificationManager.showToast({
          type: "success",
          title: "Notifikasi Dihapus",
          message: "Notifikasi berhasil dihapus.",
        })
      }
    }
  }

  clearAllNotifications() {
    if (confirm("Hapus semua notifikasi? Tindakan ini tidak dapat dibatalkan.")) {
      window.notificationManager.notifications = []
      window.notificationManager.saveNotifications()
      window.notificationManager.updateNotificationDisplay()

      this.updateStats()
      this.applyFilters()
      this.renderNotifications()
      this.renderPagination()

      window.notificationManager.showToast({
        type: "success",
        title: "Semua Notifikasi Dihapus",
        message: "Semua notifikasi berhasil dihapus.",
      })
    }
  }

  openSettingsModal() {
    const modal = document.getElementById("settingsModal")
    if (modal) {
      // Load current settings
      document.getElementById("enableJobNotifications").checked = this.settings.jobNotifications
      document.getElementById("enableTrainingNotifications").checked = this.settings.trainingNotifications
      document.getElementById("enableSystemNotifications").checked = this.settings.systemNotifications
      document.getElementById("enableBrowserNotifications").checked = this.settings.browserNotifications
      document.getElementById("enableEmailNotifications").checked = this.settings.emailNotifications
      document.getElementById("enableSoundNotifications").checked = this.settings.soundNotifications
      document.getElementById("notificationFrequency").value = this.settings.frequency

      modal.classList.add("active")
    }
  }

  closeSettingsModal() {
    const modal = document.getElementById("settingsModal")
    if (modal) {
      modal.classList.remove("active")
    }
  }

  saveSettings() {
    this.settings = {
      jobNotifications: document.getElementById("enableJobNotifications").checked,
      trainingNotifications: document.getElementById("enableTrainingNotifications").checked,
      systemNotifications: document.getElementById("enableSystemNotifications").checked,
      browserNotifications: document.getElementById("enableBrowserNotifications").checked,
      emailNotifications: document.getElementById("enableEmailNotifications").checked,
      soundNotifications: document.getElementById("enableSoundNotifications").checked,
      frequency: document.getElementById("notificationFrequency").value,
    }

    localStorage.setItem("bantukerja_notification_settings", JSON.stringify(this.settings))

    this.closeSettingsModal()

    window.notificationManager.showToast({
      type: "success",
      title: "Pengaturan Disimpan",
      message: "Pengaturan notifikasi berhasil disimpan.",
    })
  }

  loadSettings() {
    const stored = localStorage.getItem("bantukerja_notification_settings")
    if (stored) {
      return JSON.parse(stored)
    }

    return {
      jobNotifications: true,
      trainingNotifications: true,
      systemNotifications: true,
      browserNotifications: true,
      emailNotifications: false,
      soundNotifications: true,
      frequency: "30min",
    }
  }

  getNotificationIcon(type) {
    const icons = {
      success: "fa-check-circle",
      info: "fa-info-circle",
      warning: "fa-exclamation-triangle",
      error: "fa-times-circle",
    }
    return icons[type] || "fa-bell"
  }

  formatTime(timeString) {
    const now = new Date()
    const time = new Date(timeString)
    const diffInMinutes = Math.floor((now - time) / (1000 * 60))

    if (diffInMinutes < 1) return "Baru saja"
    if (diffInMinutes < 60) return `${diffInMinutes} menit yang lalu`

    const diffInHours = Math.floor(diffInMinutes / 60)
    if (diffInHours < 24) return `${diffInHours} jam yang lalu`

    const diffInDays = Math.floor(diffInHours / 24)
    if (diffInDays < 7) return `${diffInDays} hari yang lalu`

    return time.toLocaleDateString("id-ID", {
      day: "numeric",
      month: "short",
      year: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    })
  }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  // Wait for notification manager to be ready
  setTimeout(() => {
    window.notificationsPageManager = new NotificationsPageManager()
  }, 100)
})
