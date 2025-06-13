// Profile page functionality
document.addEventListener("DOMContentLoaded", () => {
  initializeProfile()
  loadEducationData()
  loadExperienceData()
  loadSkillsData()
  setupFormHandlers()
  setupFileHandlers()
})

// Initialize profile data
function initializeProfile() {
  const profileData = getProfileData()
  if (profileData) {
    populatePersonalForm(profileData)
    updateProfileDisplay(profileData)
  }
}

// Get profile data from localStorage
function getProfileData() {
  const data = localStorage.getItem("bantukerja_profile")
  return data ? JSON.parse(data) : getDefaultProfileData()
}

// Save profile data to localStorage
function saveProfileData(data) {
  localStorage.setItem("bantukerja_profile", JSON.stringify(data))
}

// Default profile data
function getDefaultProfileData() {
  return {
    fullName: "Fadlullah Hasan",
    email: "Fadlullah.Hasan@email.com",
    phone: "081234567890",
    birthDate: "1990-05-15",
    gender: "male",
    religion: "islam",
    address: "Jl. Merdeka No. 123, RT 01/RW 02",
    village: "Sukamaju",
    district: "Maju Jaya",
    city: "Jakarta Selatan",
    province: "DKI Jakarta",
    postalCode: "12345",
    profileImage:
      "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80",
    cv: null,
    education: [
      {
        id: 1,
        level: "S1",
        school: "Universitas Indonesia",
        major: "Teknik Informatika",
        year: 2012,
        gpa: 3.5,
      },
    ],
    experience: [
      {
        id: 1,
        title: "Web Developer",
        company: "PT Tech Solutions",
        startDate: "2020-01-01",
        endDate: "2023-12-31",
        current: false,
        description: "Mengembangkan aplikasi web menggunakan React dan Node.js",
      },
    ],
    skills: [
      {
        id: 1,
        name: "JavaScript",
        level: "advanced",
        category: "technical",
      },
      {
        id: 2,
        name: "Komunikasi",
        level: "expert",
        category: "soft",
      },
    ],
  }
}

// Populate personal form with data
function populatePersonalForm(data) {
  const form = document.getElementById("personalForm")
  const formData = new FormData()

  Object.keys(data).forEach((key) => {
    const input = form.querySelector(`[name="${key}"]`)
    if (input && data[key]) {
      input.value = data[key]
    }
  })
}

// Update profile display
function updateProfileDisplay(data) {
  document.getElementById("profileName").textContent = data.fullName
  document.getElementById("profileEmail").textContent = data.email

  if (data.profileImage) {
    document.getElementById("profileImage").src = data.profileImage
  }

  if (data.cv) {
    showCVPreview(data.cv)
  }
}

// Tab functionality
function showTab(tabName) {
  // Hide all tabs
  document.querySelectorAll(".tab-content").forEach((tab) => {
    tab.classList.remove("active")
  })

  // Remove active class from all buttons
  document.querySelectorAll(".tab-btn").forEach((btn) => {
    btn.classList.remove("active")
  })

  // Show selected tab
  document.getElementById(tabName + "Tab").classList.add("active")

  // Add active class to clicked button
  event.target.classList.add("active")
}

// Setup form handlers
function setupFormHandlers() {
  // Personal form
  document.getElementById("personalForm").addEventListener("submit", handlePersonalFormSubmit)

  // Education form
  document.getElementById("educationForm").addEventListener("submit", handleEducationFormSubmit)

  // Experience form
  document.getElementById("experienceForm").addEventListener("submit", handleExperienceFormSubmit)

  // Skill form
  document.getElementById("skillForm").addEventListener("submit", handleSkillFormSubmit)

  // Current job checkbox
  document.getElementById("currentJob").addEventListener("change", function () {
    const endDateInput = document.getElementById("endDate")
    if (this.checked) {
      endDateInput.disabled = true
      endDateInput.value = ""
    } else {
      endDateInput.disabled = false
    }
  })
}

// Setup file handlers
function setupFileHandlers() {
  // Avatar upload
  document.getElementById("avatarInput").addEventListener("change", handleAvatarUpload)

  // CV upload
  document.getElementById("cvInput").addEventListener("change", handleCVUpload)
}

// Handle personal form submission
function handlePersonalFormSubmit(e) {
  e.preventDefault()

  const formData = new FormData(e.target)
  const profileData = getProfileData()

  // Update profile data
  for (const [key, value] of formData.entries()) {
    profileData[key] = value
  }

  saveProfileData(profileData)
  updateProfileDisplay(profileData)

  showToast("Data personal berhasil disimpan!", "success")
}

// Handle avatar upload
function handleAvatarUpload(e) {
  const file = e.target.files[0]
  if (!file) return

  if (!file.type.startsWith("image/")) {
    showToast("File harus berupa gambar!", "error")
    return
  }

  if (file.size > 5 * 1024 * 1024) {
    showToast("Ukuran file maksimal 5MB!", "error")
    return
  }

  const reader = new FileReader()
  reader.onload = (e) => {
    const imageUrl = e.target.result
    document.getElementById("profileImage").src = imageUrl

    // Save to profile data
    const profileData = getProfileData()
    profileData.profileImage = imageUrl
    saveProfileData(profileData)

    showToast("Foto profil berhasil diperbarui!", "success")
  }
  reader.readAsDataURL(file)
}

// Handle CV upload
function handleCVUpload(e) {
  const file = e.target.files[0]
  if (!file) return

  if (file.type !== "application/pdf") {
    showToast("File harus berupa PDF!", "error")
    return
  }

  if (file.size > 5 * 1024 * 1024) {
    showToast("Ukuran file maksimal 5MB!", "error")
    return
  }

  const reader = new FileReader()
  reader.onload = (e) => {
    const cvData = {
      name: file.name,
      size: formatFileSize(file.size),
      data: e.target.result,
      uploadDate: new Date().toISOString(),
    }

    // Save to profile data
    const profileData = getProfileData()
    profileData.cv = cvData
    saveProfileData(profileData)

    showCVPreview(cvData)
    showToast("CV berhasil diupload!", "success")
  }
  reader.readAsDataURL(file)
}

// Show CV preview
function showCVPreview(cvData) {
  document.getElementById("cvUpload").style.display = "none"
  document.getElementById("cvPreview").style.display = "block"

  document.getElementById("cvFileName").textContent = cvData.name
  document.getElementById("cvFileSize").textContent = cvData.size
}

// Remove CV
function removeCV() {
  const profileData = getProfileData()
  profileData.cv = null
  saveProfileData(profileData)

  document.getElementById("cvUpload").style.display = "block"
  document.getElementById("cvPreview").style.display = "none"

  showToast("CV berhasil dihapus!", "success")
}

// Download CV
function downloadCV() {
  const profileData = getProfileData()
  if (!profileData.cv) return

  const link = document.createElement("a")
  link.href = profileData.cv.data
  link.download = profileData.cv.name
  link.click()
}

// Education functions
function loadEducationData() {
  const profileData = getProfileData()
  const educationList = document.getElementById("educationList")

  if (!profileData.education || profileData.education.length === 0) {
    educationList.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-graduation-cap"></i>
                <p>Belum ada data pendidikan</p>
            </div>
        `
    return
  }

  educationList.innerHTML = ""
  profileData.education.forEach((edu) => {
    const eduElement = createEducationElement(edu)
    educationList.appendChild(eduElement)
  })
}

function createEducationElement(education) {
  const div = document.createElement("div")
  div.className = "education-item"
  div.innerHTML = `
        <div class="item-actions">
            <button type="button" class="btn-icon" onclick="editEducation(${education.id})" title="Edit">
                <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn-icon" onclick="deleteEducation(${education.id})" title="Hapus">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <h4>${education.level} - ${education.school}</h4>
        <p><strong>Jurusan:</strong> ${education.major || "-"}</p>
        <p><strong>Tahun Lulus:</strong> ${education.year}</p>
        ${education.gpa ? `<p><strong>IPK:</strong> ${education.gpa}</p>` : ""}
    `
  return div
}

function addEducation() {
  document.getElementById("educationModalTitle").textContent = "Tambah Pendidikan"
  document.getElementById("educationForm").reset()
  document.getElementById("educationId").value = ""
  openModal(document.getElementById("educationModal"))
}

function editEducation(id) {
  const profileData = getProfileData()
  const education = profileData.education.find((edu) => edu.id === id)

  if (!education) return

  document.getElementById("educationModalTitle").textContent = "Edit Pendidikan"
  document.getElementById("educationId").value = education.id
  document.getElementById("educationLevel").value = education.level
  document.getElementById("schoolName").value = education.school
  document.getElementById("major").value = education.major || ""
  document.getElementById("graduationYear").value = education.year
  document.getElementById("gpa").value = education.gpa || ""

  openModal(document.getElementById("educationModal"))
}

function deleteEducation(id) {
  if (!confirm("Apakah Anda yakin ingin menghapus data pendidikan ini?")) return

  const profileData = getProfileData()
  profileData.education = profileData.education.filter((edu) => edu.id !== id)
  saveProfileData(profileData)
  loadEducationData()

  showToast("Data pendidikan berhasil dihapus!", "success")
}

function handleEducationFormSubmit(e) {
  e.preventDefault()

  const formData = new FormData(e.target)
  const profileData = getProfileData()

  const educationData = {
    id: Number.parseInt(document.getElementById("educationId").value) || Date.now(),
    level: formData.get("educationLevel"),
    school: formData.get("schoolName"),
    major: formData.get("major"),
    year: Number.parseInt(formData.get("graduationYear")),
    gpa: Number.parseFloat(formData.get("gpa")) || null,
  }

  if (!profileData.education) {
    profileData.education = []
  }

  const existingIndex = profileData.education.findIndex((edu) => edu.id === educationData.id)
  if (existingIndex >= 0) {
    profileData.education[existingIndex] = educationData
  } else {
    profileData.education.push(educationData)
  }

  saveProfileData(profileData)
  loadEducationData()
  closeEducationModal()

  showToast("Data pendidikan berhasil disimpan!", "success")
}

function closeEducationModal() {
  closeModal(document.getElementById("educationModal"))
}

// Experience functions
function loadExperienceData() {
  const profileData = getProfileData()
  const experienceList = document.getElementById("experienceList")

  if (!profileData.experience || profileData.experience.length === 0) {
    experienceList.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-briefcase"></i>
                <p>Belum ada data pengalaman kerja</p>
            </div>
        `
    return
  }

  experienceList.innerHTML = ""
  profileData.experience.forEach((exp) => {
    const expElement = createExperienceElement(exp)
    experienceList.appendChild(expElement)
  })
}

function createExperienceElement(experience) {
  const div = document.createElement("div")
  div.className = "experience-item"

  const endDate = experience.current ? "Sekarang" : formatDate(experience.endDate)

  div.innerHTML = `
        <div class="item-actions">
            <button type="button" class="btn-icon" onclick="editExperience(${experience.id})" title="Edit">
                <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn-icon" onclick="deleteExperience(${experience.id})" title="Hapus">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <h4>${experience.title}</h4>
        <p><strong>Perusahaan:</strong> ${experience.company}</p>
        <p><strong>Periode:</strong> ${formatDate(experience.startDate)} - ${endDate}</p>
        ${experience.description ? `<p><strong>Deskripsi:</strong> ${experience.description}</p>` : ""}
    `
  return div
}

function addExperience() {
  document.getElementById("experienceModalTitle").textContent = "Tambah Pengalaman"
  document.getElementById("experienceForm").reset()
  document.getElementById("experienceId").value = ""
  document.getElementById("endDate").disabled = false
  openModal(document.getElementById("experienceModal"))
}

function editExperience(id) {
  const profileData = getProfileData()
  const experience = profileData.experience.find((exp) => exp.id === id)

  if (!experience) return

  document.getElementById("experienceModalTitle").textContent = "Edit Pengalaman"
  document.getElementById("experienceId").value = experience.id
  document.getElementById("jobTitle").value = experience.title
  document.getElementById("companyName").value = experience.company
  document.getElementById("startDate").value = experience.startDate
  document.getElementById("endDate").value = experience.endDate || ""
  document.getElementById("currentJob").checked = experience.current || false
  document.getElementById("jobDescription").value = experience.description || ""

  if (experience.current) {
    document.getElementById("endDate").disabled = true
  }

  openModal(document.getElementById("experienceModal"))
}

function deleteExperience(id) {
  if (!confirm("Apakah Anda yakin ingin menghapus data pengalaman ini?")) return

  const profileData = getProfileData()
  profileData.experience = profileData.experience.filter((exp) => exp.id !== id)
  saveProfileData(profileData)
  loadExperienceData()

  showToast("Data pengalaman berhasil dihapus!", "success")
}

function handleExperienceFormSubmit(e) {
  e.preventDefault()

  const formData = new FormData(e.target)
  const profileData = getProfileData()

  const experienceData = {
    id: Number.parseInt(document.getElementById("experienceId").value) || Date.now(),
    title: formData.get("jobTitle"),
    company: formData.get("companyName"),
    startDate: formData.get("startDate"),
    endDate: formData.get("endDate"),
    current: document.getElementById("currentJob").checked,
    description: formData.get("jobDescription"),
  }

  if (!profileData.experience) {
    profileData.experience = []
  }

  const existingIndex = profileData.experience.findIndex((exp) => exp.id === experienceData.id)
  if (existingIndex >= 0) {
    profileData.experience[existingIndex] = experienceData
  } else {
    profileData.experience.push(experienceData)
  }

  saveProfileData(profileData)
  loadExperienceData()
  closeExperienceModal()

  showToast("Data pengalaman berhasil disimpan!", "success")
}

function closeExperienceModal() {
  closeModal(document.getElementById("experienceModal"))
}

// Skills functions
function loadSkillsData() {
  const profileData = getProfileData()
  const skillsList = document.getElementById("skillsList")

  if (!profileData.skills || profileData.skills.length === 0) {
    skillsList.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-cogs"></i>
                <p>Belum ada data keahlian</p>
            </div>
        `
    return
  }

  skillsList.innerHTML = ""
  profileData.skills.forEach((skill) => {
    const skillElement = createSkillElement(skill)
    skillsList.appendChild(skillElement)
  })
}

function createSkillElement(skill) {
  const div = document.createElement("div")
  div.className = "skill-item"

  const levelLabels = {
    beginner: "Pemula",
    intermediate: "Menengah",
    advanced: "Mahir",
    expert: "Ahli",
  }

  const categoryLabels = {
    technical: "Teknis",
    soft: "Soft Skill",
    language: "Bahasa",
    computer: "Komputer",
    other: "Lainnya",
  }

  div.innerHTML = `
        <div class="item-actions">
            <button type="button" class="btn-icon" onclick="editSkill(${skill.id})" title="Edit">
                <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn-icon" onclick="deleteSkill(${skill.id})" title="Hapus">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <h4>${skill.name}</h4>
        <span class="skill-level ${skill.level}">${levelLabels[skill.level] || skill.level}</span>
        ${skill.category ? `<p class="skill-category">${categoryLabels[skill.category] || skill.category}</p>` : ""}
    `
  return div
}

function addSkill() {
  document.getElementById("skillModalTitle").textContent = "Tambah Keahlian"
  document.getElementById("skillForm").reset()
  document.getElementById("skillId").value = ""
  openModal(document.getElementById("skillModal"))
}

function editSkill(id) {
  const profileData = getProfileData()
  const skill = profileData.skills.find((s) => s.id === id)

  if (!skill) return

  document.getElementById("skillModalTitle").textContent = "Edit Keahlian"
  document.getElementById("skillId").value = skill.id
  document.getElementById("skillName").value = skill.name
  document.getElementById("skillLevel").value = skill.level
  document.getElementById("skillCategory").value = skill.category || ""

  openModal(document.getElementById("skillModal"))
}

function deleteSkill(id) {
  if (!confirm("Apakah Anda yakin ingin menghapus keahlian ini?")) return

  const profileData = getProfileData()
  profileData.skills = profileData.skills.filter((skill) => skill.id !== id)
  saveProfileData(profileData)
  loadSkillsData()

  showToast("Keahlian berhasil dihapus!", "success")
}

function handleSkillFormSubmit(e) {
  e.preventDefault()

  const formData = new FormData(e.target)
  const profileData = getProfileData()

  const skillData = {
    id: Number.parseInt(document.getElementById("skillId").value) || Date.now(),
    name: formData.get("skillName"),
    level: formData.get("skillLevel"),
    category: formData.get("skillCategory"),
  }

  if (!profileData.skills) {
    profileData.skills = []
  }

  const existingIndex = profileData.skills.findIndex((skill) => skill.id === skillData.id)
  if (existingIndex >= 0) {
    profileData.skills[existingIndex] = skillData
  } else {
    profileData.skills.push(skillData)
  }

  saveProfileData(profileData)
  loadSkillsData()
  closeSkillModal()

  showToast("Keahlian berhasil disimpan!", "success")
}

function closeSkillModal() {
  closeModal(document.getElementById("skillModal"))
}

// Utility functions
function formatFileSize(bytes) {
  if (bytes === 0) return "0 Bytes"
  const k = 1024
  const sizes = ["Bytes", "KB", "MB", "GB"]
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return Number.parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i]
}

function formatDate(dateString) {
  if (!dateString) return ""
  const date = new Date(dateString)
  return date.toLocaleDateString("id-ID", {
    year: "numeric",
    month: "long",
  })
}

// Modal functions
function openModal(modal) {
  modal.style.display = "flex"
  setTimeout(() => {
    modal.classList.add("show")
  }, 10)
  document.body.style.overflow = "hidden"
}

function closeModal(modal) {
  modal.classList.remove("show")
  setTimeout(() => {
    modal.style.display = "none"
    document.body.style.overflow = ""
  }, 300)
}

// Close modals when clicking outside or on close button
document.addEventListener("click", (e) => {
  if (e.target.classList.contains("modal")) {
    closeModal(e.target)
  }
  if (e.target.classList.contains("close-modal")) {
    closeModal(e.target.closest(".modal"))
  }
})

// Make functions globally available
window.showTab = showTab
window.addEducation = addEducation
window.editEducation = editEducation
window.deleteEducation = deleteEducation
window.closeEducationModal = closeEducationModal
window.addExperience = addExperience
window.editExperience = editExperience
window.deleteExperience = deleteExperience
window.closeExperienceModal = closeExperienceModal
window.addSkill = addSkill
window.editSkill = editSkill
window.deleteSkill = deleteSkill
window.closeSkillModal = closeSkillModal
window.removeCV = removeCV
window.downloadCV = downloadCV
