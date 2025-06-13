// Admin Notification Manager
class AdminNotificationManager {
  constructor() {
    this.notifications = this.loadAdminNotifications()
    this.init()
  }

  init() {
    this.setupEventListeners()
    this.updateNotificationBadge()
    this.loadNotificationDropdown()
    this.updateAnalytics()

    // Auto-refresh every 30 seconds
    setInterval(() => {
      this.checkForNewNotifications()
      this.updateAnalytics()
    }, 30000)
  }

  setupEventListeners() {
    // Notification dropdown toggle
    const trigger = document.getElementById("adminNotificationTrigger")
    const menu = document.getElementById("adminNotificationMenu")

    if (trigger && menu) {
      trigger.addEventListener("click", (e) => {
        e.preventDefault()
        menu.classList.toggle("show")
      })

      // Close dropdown when clicking outside
      document.addEventListener("click", (e) => {
        if (!trigger.contains(e.target) && !menu.contains(e.target)) {
          menu.classList.remove("show")
        }
      })
    }

    // Mark all as read
    const markAllRead = document.getElementById("adminMarkAllRead")
    if (markAllRead) {
      markAllRead.addEventListener("click", () => {
        this.markAllAsRead()
      })
    }

    // Send notification modal
    this.setupSendNotificationModal()

    // Target selection change
    const targetSelect = document.getElementById("notificationTarget")
    const specificGroup = document.getElementById("specificUsersGroup")

    if (targetSelect && specificGroup) {
      targetSelect.addEventListener("change", (e) => {
        if (e.target.value === "specific") {
          specificGroup.style.display = "block"
        } else {
          specificGroup.style.display = "none"
        }
      })
    }

    // Schedule notification toggle
    const scheduleCheckbox = document.getElementById("scheduleNotification")
    const scheduleGroup = document.getElementById("scheduleGroup")

    if (scheduleCheckbox && scheduleGroup) {
      scheduleCheckbox.addEventListener("change", (e) => {
        if (e.target.checked) {
          scheduleGroup.style.display = "block"
        } else {
          scheduleGroup.style.display = "none"
        }
      })
    }
  }

  setupSendNotificationModal() {
    const sendBtn = document.getElementById("sendNotificationBtn")
    const modal = document.getElementById("sendNotificationModal")
    const closeBtn = document.getElementById("closeSendModal")
    const cancelBtn = document.getElementById("cancelSendNotification")
    const form = document.getElementById("sendNotificationForm")

    if (sendBtn && modal) {
      sendBtn.addEventListener("click", () => {
        this.openModal(modal)
      })
    }

    if (closeBtn && modal) {
      closeBtn.addEventListener("click", () => {
        this.closeModal(modal)
      })
    }

    if (cancelBtn && modal) {
      cancelBtn.addEventListener("click", () => {
        this.closeModal(modal)
      })
    }

    if (form) {
      form.addEventListener("submit", (e) => {
        e.preventDefault()
        this.sendNotification()
      })
    }

    // Close modal when clicking outside
    if (modal) {
      modal.addEventListener("click", (e) => {
        if (e.target === modal) {
          this.closeModal(modal)
        }
      })
    }
  }

  openModal(modal) {
    modal.style.display = "flex"
    setTimeout(() => {
      modal.classList.add("show")
    }, 10)
    document.body.style.overflow = "hidden"
  }

  closeModal(modal) {
    modal.classList.remove("show")
    setTimeout(() => {
      modal.style.display = "none"
      document.body.style.overflow = ""
    }, 300)
  }

  sendNotification() {
    const form = document.getElementById("sendNotificationForm")
    const formData = new FormData(form)

    const notificationData = {
      type: document.getElementById("notificationType").value,
      target: document.getElementById("notificationTarget").value,
      title: document.getElementById("notificationTitle").value,
      message: document.getElementById("notificationMessage").value,
      actionUrl: document.getElementById("notificationAction").value,
      scheduled: document.getElementById("scheduleNotification").checked,
      scheduleDateTime: document.getElementById("scheduleDateTime").value,
      specificUsers: document.getElementById("specificUsers").value,
    }

    // Validate required fields
    if (!notificationData.type || !notificationData.target || !notificationData.title || !notificationData.message) {
      this.showToast({
        type: "error",
        title: "Error",
        message: "Mohon lengkapi semua field yang wajib diisi.",
      })
      return
    }

    // Simulate sending notification
    this.processSendNotification(notificationData)
  }

  processSendNotification(data) {
    // Show loading state
    const submitBtn = document.querySelector('#sendNotificationForm button[type="submit"]')
    const originalText = submitBtn.innerHTML
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...'
    submitBtn.disabled = true

    // Simulate API call
    setTimeout(() => {
      // Reset button
      submitBtn.innerHTML = originalText
      submitBtn.disabled = false

      // Close modal
      const modal = document.getElementById("sendNotificationModal")
      this.closeModal(modal)

      // Reset form
      document.getElementById("sendNotificationForm").reset()
      document.getElementById("specificUsersGroup").style.display = "none"
      document.getElementById("scheduleGroup").style.display = "none"

      // Show success message
      this.showToast({
        type: "success",
        title: "Notifikasi Terkirim",
        message: `Notifikasi berhasil dikirim ke ${this.getTargetDescription(data.target)}.`,
      })

      // Add to admin notifications
      this.addAdminNotification({
        id: Date.now(),
        type: "success",
        title: "Notifikasi Terkirim",
        message: `Notifikasi "${data.title}" berhasil dikirim ke ${this.getTargetDescription(data.target)}`,
        timestamp: new Date().toISOString(),
        read: false,
        actionUrl: null,
      })

      // Update analytics
      this.updateAnalytics()
    }, 2000)
  }

  getTargetDescription(target) {
    const targets = {
      all: "semua pengguna",
      active: "pengguna aktif",
      training: "peserta pelatihan",
      job_seekers: "pencari kerja",
      specific: "pengguna tertentu",
    }
    return targets[target] || target
  }

  sendQuickNotification(type) {
    const templates = {
      training_reminder: {
        type: "info",
        title: "Reminder Pelatihan",
        message:
          "Jangan lupa menghadiri pelatihan Anda besok. Pastikan Anda sudah mempersiapkan semua yang diperlukan.",
        target: "training",
      },
      job_alert: {
        type: "success",
        title: "Lowongan Kerja Baru",
        message: "Ada lowongan kerja baru yang sesuai dengan profil Anda. Segera daftar sebelum kuota penuh!",
        target: "job_seekers",
      },
      system_maintenance: {
        type: "warning",
        title: "Maintenance Sistem",
        message: "Sistem akan mengalami maintenance pada hari Minggu, 15 Januari 2025 pukul 02.00-04.00 WIB.",
        target: "all",
      },
      announcement: {
        type: "info",
        title: "Pengumuman Penting",
        message:
          "Terdapat update kebijakan baru terkait pendaftaran pelatihan. Silakan baca pengumuman lengkap di dashboard.",
        target: "all",
      },
    }

    const template = templates[type]
    if (template) {
      this.processSendNotification(template)
    }
  }

  loadAdminNotifications() {
    const stored = localStorage.getItem("bantukerja_admin_notifications")
    if (stored) {
      return JSON.parse(stored)
    }

    // Default admin notifications
    return [
      {
        id: 1,
        type: "info",
        title: "Pendaftaran Pelatihan Baru",
        message: "5 peserta baru mendaftar pelatihan Digital Marketing hari ini.",
        timestamp: new Date(Date.now() - 5 * 60 * 1000).toISOString(),
        read: false,
        actionUrl: "admin-pelatihan.html",
      },
      {
        id: 2,
        type: "success",
        title: "Lamaran Kerja Diterima",
        message: "PT Maju Bersama menerima 3 lamaran untuk posisi Customer Service.",
        timestamp: new Date(Date.now() - 30 * 60 * 1000).toISOString(),
        read: false,
        actionUrl: "admin-lowongan.html",
      },
      {
        id: 3,
        type: "warning",
        title: "Kuota Pelatihan Hampir Penuh",
        message: "Pelatihan Desain Grafis tinggal 2 slot tersisa dari 20 kuota.",
        timestamp: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString(),
        read: false,
        actionUrl: "admin-pelatihan.html",
      },
      {
        id: 4,
        type: "error",
        title: "Sistem Error",
        message: "Terjadi error pada sistem pembayaran. Segera lakukan pengecekan.",
        timestamp: new Date(Date.now() - 3 * 60 * 60 * 1000).toISOString(),
        read: true,
        actionUrl: null,
      },
      {
        id: 5,
        type: "info",
        title: "Laporan Bulanan Siap",
        message: "Laporan aktivitas bulan Desember 2024 telah siap untuk diunduh.",
        timestamp: new Date(Date.now() - 24 * 60 * 60 * 1000).toISOString(),
        read: true,
        actionUrl: null,
      },
    ]
  }

  saveAdminNotifications() {
    localStorage.setItem("bantukerja_admin_notifications", JSON.stringify(this.notifications))
  }

  addAdminNotification(notification) {
    this.notifications.unshift(notification)
    this.saveAdminNotifications()
    this.updateNotificationBadge()
    this.loadNotificationDropdown()
  }

  markAsRead(notificationId) {
    const notification = this.notifications.find((n) => n.id === notificationId)
    if (notification) {
      notification.read = true
      this.saveAdminNotifications()
      this.updateNotificationBadge()
      this.loadNotificationDropdown()
    }
  }

  markAllAsRead() {
    this.notifications.forEach((notification) => {
      notification.read = true
    })
    this.saveAdminNotifications()
    this.updateNotificationBadge()
    this.loadNotificationDropdown()

    this.showToast({
      type: "success",
      title: "Berhasil",
      message: "Semua notifikasi telah ditandai sebagai dibaca.",
    })
  }

  updateNotificationBadge() {
    const badge = document.getElementById("adminNotificationBadge")
    const unreadCount = this.notifications.filter((n) => !n.read).length

    if (badge) {
      if (unreadCount > 0) {
        badge.textContent = unreadCount > 99 ? "99+" : unreadCount
        badge.style.display = "block"
      } else {
        badge.style.display = "none"
      }
    }
  }

  loadNotificationDropdown() {
    const container = document.getElementById("adminNotificationList")
    if (!container) return

    container.innerHTML = ""

    const recentNotifications = this.notifications.slice(0, 5)

    if (recentNotifications.length === 0) {
      container.innerHTML = `
                <div class="notification-item empty">
                    <p>Tidak ada notifikasi</p>
                </div>
            `
      return
    }

    recentNotifications.forEach((notification) => {
      const item = this.createNotificationItem(notification)
      container.appendChild(item)
    })
  }

  createNotificationItem(notification) {
    const item = document.createElement("div")
    item.className = `notification-item ${notification.read ? "read" : "unread"}`

    const typeIcons = {
      success: "fas fa-check-circle",
      error: "fas fa-exclamation-circle",
      warning: "fas fa-exclamation-triangle",
      info: "fas fa-info-circle",
    }

    item.innerHTML = `
            <div class="notification-icon ${notification.type}">
                <i class="${typeIcons[notification.type] || "fas fa-bell"}"></i>
            </div>
            <div class="notification-content">
                <h4>${notification.title}</h4>
                <p>${notification.message}</p>
                <span class="notification-time">${this.getRelativeTime(notification.timestamp)}</span>
            </div>
            ${!notification.read ? '<div class="notification-dot"></div>' : ""}
        `

    item.addEventListener("click", () => {
      if (!notification.read) {
        this.markAsRead(notification.id)
      }

      if (notification.actionUrl) {
        window.location.href = notification.actionUrl
      }
    })

    return item
  }

  getRelativeTime(timestamp) {
    const now = new Date()
    const time = new Date(timestamp)
    const diffInSeconds = Math.floor((now - time) / 1000)

    if (diffInSeconds < 60) {
      return "Baru saja"
    } else if (diffInSeconds < 3600) {
      const minutes = Math.floor(diffInSeconds / 60)
      return `${minutes} menit yang lalu`
    } else if (diffInSeconds < 86400) {
      const hours = Math.floor(diffInSeconds / 3600)
      return `${hours} jam yang lalu`
    } else {
      const days = Math.floor(diffInSeconds / 86400)
      return `${days} hari yang lalu`
    }
  }

  updateAnalytics() {
    // Update notification analytics
    const totalSent = document.getElementById("totalSentNotifications")
    const totalRead = document.getElementById("totalReadNotifications")
    const clickRate = document.getElementById("notificationClickRate")
    const pending = document.getElementById("pendingNotifications")

    // Mock data - in real app, this would come from API
    if (totalSent) totalSent.textContent = "247"
    if (totalRead) totalRead.textContent = "189"
    if (clickRate) clickRate.textContent = "45.2%"
    if (pending) pending.textContent = "8"
  }

  checkForNewNotifications() {
    // Simulate checking for new notifications
    // In real app, this would be an API call
    const random = Math.random()

    if (random < 0.1) {
      // 10% chance of new notification
      const notifications = [
        {
          type: "info",
          title: "Pendaftar Baru",
          message: "Ada pendaftar baru untuk pelatihan Web Development.",
        },
        {
          type: "success",
          title: "Pelatihan Selesai",
          message: "Pelatihan Digital Marketing telah selesai dengan sukses.",
        },
        {
          type: "warning",
          title: "Kuota Hampir Penuh",
          message: "Pelatihan Data Analysis tinggal 1 slot tersisa.",
        },
      ]

      const randomNotification = notifications[Math.floor(Math.random() * notifications.length)]

      this.addAdminNotification({
        id: Date.now(),
        type: randomNotification.type,
        title: randomNotification.title,
        message: randomNotification.message,
        timestamp: new Date().toISOString(),
        read: false,
        actionUrl: null,
      })

      // Show toast for new notification
      this.showToast({
        type: randomNotification.type,
        title: "Notifikasi Baru",
        message: randomNotification.message,
      })
    }
  }

  showToast(notification) {
    if (window.notificationManager && window.notificationManager.showToast) {
      window.notificationManager.showToast(notification)
    } else {
      // Fallback toast implementation
      const toast = document.createElement("div")
      toast.className = `toast toast-${notification.type}`
      toast.innerHTML = `
                <div class="toast-content">
                    <h4>${notification.title}</h4>
                    <p>${notification.message}</p>
                </div>
                <button class="toast-close">&times;</button>
            `

      const container = document.getElementById("toastContainer") || document.body
      container.appendChild(toast)

      // Auto remove after 5 seconds
      setTimeout(() => {
        toast.remove()
      }, 5000)

      // Manual close
      toast.querySelector(".toast-close").addEventListener("click", () => {
        toast.remove()
      })
    }
  }
}

// Initialize admin notification manager
let adminNotificationManager

document.addEventListener("DOMContentLoaded", () => {
  adminNotificationManager = new AdminNotificationManager()

  // Make it globally available
  window.adminNotificationManager = adminNotificationManager
})
