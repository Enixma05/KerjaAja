// Notification System
class NotificationManager {
  constructor() {
    this.notifications = []
    this.unreadCount = 0
    this.init()
  }

  init() {
    this.loadNotifications()
    this.setupEventListeners()
    this.updateNotificationDisplay()
    this.startPeriodicCheck()
  }

  setupEventListeners() {
    // Notification dropdown toggle
    const notificationTrigger = document.getElementById("notificationTrigger")
    const notificationDropdown = document.querySelector(".notification-dropdown")

    if (notificationTrigger && notificationDropdown) {
      notificationTrigger.addEventListener("click", (e) => {
        e.preventDefault()
        notificationDropdown.classList.toggle("active")
      })

      // Close dropdown when clicking outside
      document.addEventListener("click", (e) => {
        if (!notificationDropdown.contains(e.target)) {
          notificationDropdown.classList.remove("active")
        }
      })
    }

    // Mark all as read
    const markAllReadBtn = document.getElementById("markAllRead")
    if (markAllReadBtn) {
      markAllReadBtn.addEventListener("click", () => {
        this.markAllAsRead()
      })
    }

    // View all notifications
    const viewAllBtn = document.getElementById("viewAllNotifications")
    const viewAllBtnMain = document.getElementById("viewAllNotificationsBtn")

    if (viewAllBtn) {
      viewAllBtn.addEventListener("click", (e) => {
        e.preventDefault()
        this.showAllNotifications()
      })
    }

    if (viewAllBtnMain) {
      viewAllBtnMain.addEventListener("click", (e) => {
        e.preventDefault()
        this.showAllNotifications()
      })
    }
  }

  loadNotifications() {
    // Load from localStorage or use mock data
    const stored = localStorage.getItem("bantukerja_notifications")
    if (stored) {
      this.notifications = JSON.parse(stored)
    } else {
      this.notifications = this.getMockNotifications()
      this.saveNotifications()
    }

    this.updateUnreadCount()
  }

  saveNotifications() {
    localStorage.setItem("bantukerja_notifications", JSON.stringify(this.notifications))
  }

  getMockNotifications() {
    return [
      {
        id: 1,
        type: "success",
        title: "Lamaran Diterima!",
        message: "Selamat! Lamaran Anda untuk posisi Customer Service di PT Maju Bersama telah diterima.",
        time: new Date(Date.now() - 30 * 60 * 1000).toISOString(), // 30 minutes ago
        read: false,
        actionUrl: "riwayat.html",
        actionText: "Lihat Detail",
      },
      {
        id: 2,
        type: "info",
        title: "Pelatihan Dimulai Besok",
        message: "Pelatihan Digital Marketing akan dimulai besok pukul 09:00 di Balai Desa Sukamaju.",
        time: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString(), // 2 hours ago
        read: false,
        actionUrl: "pelatihan.html",
        actionText: "Lihat Jadwal",
      },
      {
        id: 3,
        type: "warning",
        title: "Deadline Pendaftaran",
        message: "Pendaftaran pelatihan Desain Grafis akan berakhir dalam 2 hari.",
        time: new Date(Date.now() - 4 * 60 * 60 * 1000).toISOString(), // 4 hours ago
        read: false,
        actionUrl: "pelatihan.html",
        actionText: "Daftar Sekarang",
      },
      {
        id: 4,
        type: "info",
        title: "Profil Diperbarui",
        message: "Data profil Anda telah berhasil diperbarui.",
        time: new Date(Date.now() - 24 * 60 * 60 * 1000).toISOString(), // 1 day ago
        read: true,
        actionUrl: "profile.html",
        actionText: "Lihat Profil",
      },
      {
        id: 5,
        type: "error",
        title: "Lamaran Ditolak",
        message: "Mohon maaf, lamaran Anda untuk posisi Admin Kantor di CV Sukses Mandiri tidak dapat kami terima.",
        time: new Date(Date.now() - 2 * 24 * 60 * 60 * 1000).toISOString(), // 2 days ago
        read: true,
        actionUrl: "lowongan.html",
        actionText: "Cari Lowongan Lain",
      },
    ]
  }

  addNotification(notification) {
    const newNotification = {
      id: Date.now(),
      time: new Date().toISOString(),
      read: false,
      ...notification,
    }

    this.notifications.unshift(newNotification)
    this.saveNotifications()
    this.updateNotificationDisplay()
    this.showToast(newNotification)
  }

  markAsRead(notificationId) {
    const notification = this.notifications.find((n) => n.id === notificationId)
    if (notification && !notification.read) {
      notification.read = true
      this.saveNotifications()
      this.updateNotificationDisplay()
    }
  }

  markAllAsRead() {
    this.notifications.forEach((notification) => {
      notification.read = true
    })
    this.saveNotifications()
    this.updateNotificationDisplay()
    this.showToast({
      type: "success",
      title: "Semua notifikasi telah ditandai sebagai dibaca",
      message: "",
    })
  }

  updateUnreadCount() {
    this.unreadCount = this.notifications.filter((n) => !n.read).length
  }

  updateNotificationDisplay() {
    this.updateUnreadCount()
    this.updateBadge()
    this.updateDropdownList()
    this.updateNotificationsGrid()
  }

  updateBadge() {
    const badge = document.getElementById("notificationBadge")
    if (badge) {
      if (this.unreadCount > 0) {
        badge.textContent = this.unreadCount > 99 ? "99+" : this.unreadCount
        badge.classList.remove("hidden")
      } else {
        badge.classList.add("hidden")
      }
    }
  }

  updateDropdownList() {
    const notificationList = document.getElementById("notificationList")
    if (!notificationList) return

    if (this.notifications.length === 0) {
      notificationList.innerHTML = `
        <div class="notification-empty">
          <i class="fas fa-bell-slash"></i>
          <p>Tidak ada notifikasi</p>
        </div>
      `
      return
    }

    const recentNotifications = this.notifications.slice(0, 5)
    notificationList.innerHTML = recentNotifications
      .map(
        (notification) => `
      <div class="notification-item ${!notification.read ? "unread" : ""}" 
           onclick="notificationManager.handleNotificationClick(${notification.id})">
        <div class="notification-icon ${notification.type}">
          <i class="fas ${this.getNotificationIcon(notification.type)}"></i>
        </div>
        <div class="notification-content">
          <h5 class="notification-title">${notification.title}</h5>
          <p class="notification-message">${notification.message}</p>
          <span class="notification-time">${this.formatTime(notification.time)}</span>
        </div>
      </div>
    `,
      )
      .join("")
  }

  updateNotificationsGrid() {
    const notificationsGrid = document.getElementById("notificationsGrid")
    if (!notificationsGrid) return

    const recentNotifications = this.notifications.slice(0, 3)

    if (recentNotifications.length === 0) {
      notificationsGrid.innerHTML = `
        <div class="notification-empty">
          <i class="fas fa-bell-slash"></i>
          <p>Tidak ada notifikasi terbaru</p>
        </div>
      `
      return
    }

    notificationsGrid.innerHTML = recentNotifications
      .map(
        (notification) => `
      <div class="notification-card ${!notification.read ? "unread" : ""}">
        <div class="notification-card-icon ${notification.type}">
          <i class="fas ${this.getNotificationIcon(notification.type)}"></i>
        </div>
        <div class="notification-card-content">
          <h4 class="notification-card-title">${notification.title}</h4>
          <p class="notification-card-message">${notification.message}</p>
          <div class="notification-card-meta">
            <span>${this.formatTime(notification.time)}</span>
            <div class="notification-card-actions">
              ${
                !notification.read
                  ? `
                <button class="notification-action-btn" onclick="notificationManager.markAsRead(${notification.id})">
                  Tandai Dibaca
                </button>
              `
                  : ""
              }
              ${
                notification.actionUrl
                  ? `
                <button class="notification-action-btn primary" onclick="window.location.href='${notification.actionUrl}'">
                  ${notification.actionText || "Lihat"}
                </button>
              `
                  : ""
              }
            </div>
          </div>
        </div>
      </div>
    `,
      )
      .join("")
  }

  handleNotificationClick(notificationId) {
    const notification = this.notifications.find((n) => n.id === notificationId)
    if (notification) {
      this.markAsRead(notificationId)
      if (notification.actionUrl) {
        window.location.href = notification.actionUrl
      }
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
    })
  }

  showToast(notification) {
    const toastContainer = document.getElementById("toastContainer")
    if (!toastContainer) return

    const toast = document.createElement("div")
    toast.className = `toast ${notification.type}`
    toast.innerHTML = `
      <div class="toast-icon">
        <i class="fas ${this.getNotificationIcon(notification.type)}"></i>
      </div>
      <div class="toast-content">
        <h5 class="toast-title">${notification.title}</h5>
        ${notification.message ? `<p class="toast-message">${notification.message}</p>` : ""}
      </div>
      <button class="toast-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
      </button>
    `

    toastContainer.appendChild(toast)

    // Show toast
    setTimeout(() => {
      toast.classList.add("show")
    }, 100)

    // Auto remove after 5 seconds
    setTimeout(() => {
      toast.classList.remove("show")
      setTimeout(() => {
        toast.remove()
      }, 300)
    }, 5000)
  }

  showAllNotifications() {
    // Navigate to notifications page
    window.location.href = "notifications.html"
  }

  // Simulate receiving new notifications
  startPeriodicCheck() {
    // Check for new notifications every 30 seconds
    setInterval(() => {
      this.checkForNewNotifications()
    }, 30000)
  }

  checkForNewNotifications() {
    // Simulate random notifications for demo
    const randomNotifications = [
      {
        type: "info",
        title: "Lowongan Baru Tersedia",
        message: "Ada lowongan baru untuk posisi Graphic Designer di PT Creative Studio.",
        actionUrl: "lowongan.html",
        actionText: "Lihat Lowongan",
      },
      {
        type: "success",
        title: "Sertifikat Siap Diunduh",
        message: "Sertifikat pelatihan Digital Marketing Anda sudah siap untuk diunduh.",
        actionUrl: "riwayat.html",
        actionText: "Unduh Sertifikat",
      },
      {
        type: "warning",
        title: "Reminder: Interview Besok",
        message: "Jangan lupa interview untuk posisi Customer Service besok pukul 10:00.",
        actionUrl: "riwayat.html",
        actionText: "Lihat Detail",
      },
    ]

    // 5% chance of getting a new notification (reduced for demo)
    if (Math.random() < 0.05) {
      const randomNotification = randomNotifications[Math.floor(Math.random() * randomNotifications.length)]
      this.addNotification(randomNotification)
    }
  }

  // Public methods for external use
  static getInstance() {
    if (!window.notificationManagerInstance) {
      window.notificationManagerInstance = new NotificationManager()
    }
    return window.notificationManagerInstance
  }

  // Simulate different notification scenarios
  simulateJobAccepted() {
    this.addNotification({
      type: "success",
      title: "Lamaran Diterima!",
      message: "Selamat! Lamaran Anda untuk posisi Web Developer telah diterima.",
      actionUrl: "riwayat.html",
      actionText: "Lihat Detail",
    })
  }

  simulateJobRejected() {
    this.addNotification({
      type: "error",
      title: "Lamaran Ditolak",
      message: "Mohon maaf, lamaran Anda untuk posisi Marketing tidak dapat kami terima.",
      actionUrl: "lowongan.html",
      actionText: "Cari Lowongan Lain",
    })
  }

  simulateTrainingReminder() {
    this.addNotification({
      type: "info",
      title: "Pelatihan Dimulai Besok",
      message: "Pelatihan Web Development akan dimulai besok pukul 09:00.",
      actionUrl: "pelatihan.html",
      actionText: "Lihat Jadwal",
    })
  }

  simulateDeadlineWarning() {
    this.addNotification({
      type: "warning",
      title: "Deadline Pendaftaran",
      message: "Pendaftaran pelatihan akan berakhir dalam 1 hari.",
      actionUrl: "pelatihan.html",
      actionText: "Daftar Sekarang",
    })
  }
}

// Initialize notification manager when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  window.notificationManager = NotificationManager.getInstance()
})

// Export for use in other scripts
window.NotificationManager = NotificationManager
