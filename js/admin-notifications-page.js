// Admin Notifications Page Manager
class AdminNotificationsPageManager {
  constructor() {
    this.currentPage = 1
    this.itemsPerPage = 10
    this.filteredNotifications = []
    this.init()
  }

  init() {
    this.setupEventListeners()
    this.loadNotificationHistory()
    this.updateStatistics()
  }

  setupEventListeners() {
    // Search functionality
    const searchInput = document.getElementById("searchNotifications")
    if (searchInput) {
      searchInput.addEventListener("input", (e) => {
        this.filterNotifications()
      })
    }

    // Filter by type
    const filterType = document.getElementById("filterType")
    if (filterType) {
      filterType.addEventListener("change", () => {
        this.filterNotifications()
      })
    }

    // Sort functionality
    const sortBy = document.getElementById("sortBy")
    if (sortBy) {
      sortBy.addEventListener("change", () => {
        this.filterNotifications()
      })
    }

    // Mark all as read
    const markAllRead = document.getElementById("markAllReadHistory")
    if (markAllRead) {
      markAllRead.addEventListener("click", () => {
        this.markAllAsRead()
      })
    }

    // Clear all notifications
    const clearAll = document.getElementById("clearAllNotifications")
    if (clearAll) {
      clearAll.addEventListener("click", () => {
        this.clearAllNotifications()
      })
    }
  }

  loadNotificationHistory() {
    if (!window.adminNotificationManager) {
      setTimeout(() => this.loadNotificationHistory(), 100)
      return
    }

    this.filteredNotifications = [...window.adminNotificationManager.notifications]
    this.filterNotifications()
  }

  filterNotifications() {
    if (!window.adminNotificationManager) return

    let notifications = [...window.adminNotificationManager.notifications]

    // Search filter
    const searchTerm = document.getElementById("searchNotifications")?.value.toLowerCase() || ""
    if (searchTerm) {
      notifications = notifications.filter(
        (notification) =>
          notification.title.toLowerCase().includes(searchTerm) ||
          notification.message.toLowerCase().includes(searchTerm),
      )
    }

    // Type filter
    const typeFilter = document.getElementById("filterType")?.value || ""
    if (typeFilter) {
      notifications = notifications.filter((notification) => notification.type === typeFilter)
    }

    // Sort notifications
    const sortBy = document.getElementById("sortBy")?.value || "newest"
    notifications.sort((a, b) => {
      switch (sortBy) {
        case "oldest":
          return new Date(a.timestamp) - new Date(b.timestamp)
        case "type":
          return a.type.localeCompare(b.type)
        case "newest":
        default:
          return new Date(b.timestamp) - new Date(a.timestamp)
      }
    })

    this.filteredNotifications = notifications
    this.currentPage = 1
    this.renderNotifications()
    this.renderPagination()
  }

  renderNotifications() {
    const container = document.getElementById("notificationHistoryList")
    if (!container) return

    const startIndex = (this.currentPage - 1) * this.itemsPerPage
    const endIndex = startIndex + this.itemsPerPage
    const pageNotifications = this.filteredNotifications.slice(startIndex, endIndex)

    if (pageNotifications.length === 0) {
      container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-bell-slash"></i>
                    </div>
                    <h3>Tidak ada notifikasi</h3>
                    <p>Belum ada notifikasi yang sesuai dengan filter Anda.</p>
                </div>
            `
      return
    }

    container.innerHTML = ""
    pageNotifications.forEach((notification) => {
      const item = this.createNotificationHistoryItem(notification)
      container.appendChild(item)
    })
  }

  createNotificationHistoryItem(notification) {
    const item = document.createElement("div")
    item.className = `notification-history-item ${notification.read ? "read" : "unread"}`

    const typeIcons = {
      success: "fas fa-check-circle",
      error: "fas fa-exclamation-circle",
      warning: "fas fa-exclamation-triangle",
      info: "fas fa-info-circle",
    }

    const typeColors = {
      success: "green",
      error: "red",
      warning: "amber",
      info: "blue",
    }

    item.innerHTML = `
            <div class="notification-icon ${typeColors[notification.type]}">
                <i class="${typeIcons[notification.type] || "fas fa-bell"}"></i>
            </div>
            <div class="notification-content">
                <div class="notification-header">
                    <h4>${notification.title}</h4>
                    <div class="notification-meta">
                        <span class="notification-type ${notification.type}">${this.getTypeLabel(notification.type)}</span>
                        <span class="notification-time">${this.formatDateTime(notification.timestamp)}</span>
                    </div>
                </div>
                <p class="notification-message">${notification.message}</p>
                ${notification.actionUrl ? `<a href="${notification.actionUrl}" class="notification-action">Lihat Detail</a>` : ""}
            </div>
            <div class="notification-actions">
                ${
                  !notification.read
                    ? `
                    <button class="action-btn" onclick="adminNotificationsPageManager.markAsRead(${notification.id})" title="Tandai sebagai dibaca">
                        <i class="fas fa-check"></i>
                    </button>
                `
                    : ""
                }
                <button class="action-btn delete" onclick="adminNotificationsPageManager.deleteNotification(${notification.id})" title="Hapus">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `

    return item
  }

  renderPagination() {
    const container = document.getElementById("notificationPagination")
    if (!container) return

    const totalPages = Math.ceil(this.filteredNotifications.length / this.itemsPerPage)

    if (totalPages <= 1) {
      container.innerHTML = ""
      return
    }

    let paginationHTML = `
            <div class="pagination-info">
                Menampilkan ${(this.currentPage - 1) * this.itemsPerPage + 1}-${Math.min(this.currentPage * this.itemsPerPage, this.filteredNotifications.length)} dari ${this.filteredNotifications.length} notifikasi
            </div>
            <div class="pagination-controls">
        `

    // Previous button
    if (this.currentPage > 1) {
      paginationHTML += `
                <button class="pagination-btn" onclick="adminNotificationsPageManager.goToPage(${this.currentPage - 1})">
                    <i class="fas fa-chevron-left"></i>
                </button>
            `
    }

    // Page numbers
    const startPage = Math.max(1, this.currentPage - 2)
    const endPage = Math.min(totalPages, this.currentPage + 2)

    if (startPage > 1) {
      paginationHTML += `<button class="pagination-btn" onclick="adminNotificationsPageManager.goToPage(1)">1</button>`
      if (startPage > 2) {
        paginationHTML += `<span class="pagination-ellipsis">...</span>`
      }
    }

    for (let i = startPage; i <= endPage; i++) {
      paginationHTML += `
                <button class="pagination-btn ${i === this.currentPage ? "active" : ""}" onclick="adminNotificationsPageManager.goToPage(${i})">
                    ${i}
                </button>
            `
    }

    if (endPage < totalPages) {
      if (endPage < totalPages - 1) {
        paginationHTML += `<span class="pagination-ellipsis">...</span>`
      }
      paginationHTML += `<button class="pagination-btn" onclick="adminNotificationsPageManager.goToPage(${totalPages})">${totalPages}</button>`
    }

    // Next button
    if (this.currentPage < totalPages) {
      paginationHTML += `
                <button class="pagination-btn" onclick="adminNotificationsPageManager.goToPage(${this.currentPage + 1})">
                    <i class="fas fa-chevron-right"></i>
                </button>
            `
    }

    paginationHTML += `</div>`
    container.innerHTML = paginationHTML
  }

  goToPage(page) {
    this.currentPage = page
    this.renderNotifications()
    this.renderPagination()

    // Scroll to top of notifications list
    const container = document.getElementById("notificationHistoryList")
    if (container) {
      container.scrollIntoView({ behavior: "smooth", block: "start" })
    }
  }

  markAsRead(notificationId) {
    if (window.adminNotificationManager) {
      window.adminNotificationManager.markAsRead(notificationId)
      this.loadNotificationHistory()
      this.updateStatistics()
    }
  }

  deleteNotification(notificationId) {
    if (confirm("Apakah Anda yakin ingin menghapus notifikasi ini?")) {
      if (window.adminNotificationManager) {
        window.adminNotificationManager.notifications = window.adminNotificationManager.notifications.filter(
          (n) => n.id !== notificationId,
        )
        window.adminNotificationManager.saveAdminNotifications()
        window.adminNotificationManager.updateNotificationBadge()
        window.adminNotificationManager.loadNotificationDropdown()

        this.loadNotificationHistory()
        this.updateStatistics()

        window.adminNotificationManager.showToast({
          type: "success",
          title: "Berhasil",
          message: "Notifikasi berhasil dihapus.",
        })
      }
    }
  }

  markAllAsRead() {
    if (window.adminNotificationManager) {
      window.adminNotificationManager.markAllAsRead()
      this.loadNotificationHistory()
      this.updateStatistics()
    }
  }

  clearAllNotifications() {
    if (confirm("Apakah Anda yakin ingin menghapus semua notifikasi? Tindakan ini tidak dapat dibatalkan.")) {
      if (window.adminNotificationManager) {
        window.adminNotificationManager.notifications = []
        window.adminNotificationManager.saveAdminNotifications()
        window.adminNotificationManager.updateNotificationBadge()
        window.adminNotificationManager.loadNotificationDropdown()

        this.loadNotificationHistory()
        this.updateStatistics()

        window.adminNotificationManager.showToast({
          type: "success",
          title: "Berhasil",
          message: "Semua notifikasi berhasil dihapus.",
        })
      }
    }
  }

  updateStatistics() {
    if (!window.adminNotificationManager) return

    const notifications = window.adminNotificationManager.notifications
    const totalSent = notifications.length
    const totalRead = notifications.filter((n) => n.read).length
    const readRate = totalSent > 0 ? ((totalRead / totalSent) * 100).toFixed(1) : 0

    // Update statistics display
    const totalSentEl = document.getElementById("totalSentNotifications")
    const readRateEl = document.getElementById("readRate")
    const clickRateEl = document.getElementById("clickRate")
    const scheduledEl = document.getElementById("scheduledNotifications")

    if (totalSentEl) totalSentEl.textContent = totalSent
    if (readRateEl) readRateEl.textContent = `${readRate}%`
    if (clickRateEl) clickRateEl.textContent = "45.2%" // Mock data
    if (scheduledEl) scheduledEl.textContent = "8" // Mock data

    // Update read rate description
    const readRateDesc = readRateEl?.parentElement.querySelector(".stat-description")
    if (readRateDesc) {
      readRateDesc.textContent = `${totalRead} dari ${totalSent} dibaca`
    }
  }

  getTypeLabel(type) {
    const labels = {
      success: "Berhasil",
      error: "Error",
      warning: "Peringatan",
      info: "Informasi",
    }
    return labels[type] || type
  }

  formatDateTime(timestamp) {
    const date = new Date(timestamp)
    const options = {
      year: "numeric",
      month: "short",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit",
      timeZone: "Asia/Jakarta",
    }
    return date.toLocaleDateString("id-ID", options)
  }
}

// Initialize admin notifications page manager
let adminNotificationsPageManager

document.addEventListener("DOMContentLoaded", () => {
  // Wait for admin notification manager to be ready
  const initPageManager = () => {
    if (window.adminNotificationManager) {
      adminNotificationsPageManager = new AdminNotificationsPageManager()
      window.adminNotificationsPageManager = adminNotificationsPageManager
    } else {
      setTimeout(initPageManager, 100)
    }
  }

  initPageManager()
})
