document.addEventListener("DOMContentLoaded", function () {
  const notificationsList = document.getElementById("notificationsList");
  const emptyState = document.getElementById("emptyState");
  const loadingState = document.getElementById("loadingState");

  // Fungsi untuk mengambil dan menampilkan notifikasi
  async function fetchNotifications() {
    // Tampilkan status loading
    loadingState.style.display = "block";
    notificationsList.style.display = "none";
    emptyState.style.display = "none";

    try {
      const response = await fetch("get-notifications.php");
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      const notifications = await response.json();

      // Sembunyikan status loading
      loadingState.style.display = "none";

      if (notifications.length > 0) {
        notificationsList.style.display = "block";
        notificationsList.innerHTML = ""; // Kosongkan list sebelum diisi

        notifications.forEach((notif) => {
          const isAccepted = notif.pesan.toLowerCase().includes("diterima");
          const iconClass = isAccepted ? "fa-check-circle" : "fa-times-circle";
          const iconColor = isAccepted ? "#28a745" : "#dc3545";

          // Format tanggal agar lebih mudah dibaca
          const date = new Date(notif.tanggal);
          const formattedDate = date.toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "long",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
          });

          const notificationElement = document.createElement("div");
          notificationElement.className = "notification-item";
          notificationElement.innerHTML = `
                        <div class="notif-icon">
                            <i class="fas ${iconClass}" style="color: ${iconColor};"></i>
                        </div>
                        <div class="notif-content">
                            <p>${notif.pesan}</p>
                            <span class="notif-date">${formattedDate}</span>
                        </div>
                    `;
          notificationsList.appendChild(notificationElement);
        });
      } else {
        // Tampilkan status kosong jika tidak ada notifikasi
        emptyState.style.display = "block";
      }
    } catch (error) {
      console.error("Fetch error:", error);
      loadingState.style.display = "none";
      emptyState.style.display = "block";
      emptyState.querySelector("p").textContent = "Gagal memuat notifikasi. Coba lagi nanti.";
    }
  }

  // Panggil fungsi saat halaman dimuat
  fetchNotifications();
});
