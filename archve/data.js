// Mock data for the application

// Training data
const mockTrainings = [
    {
        id: 1,
        name: "Pelatihan Digital Marketing",
        date: "15 Juni 2024",
        location: "Balai Desa Sukamaju",
        quota: 20,
        description: "Pelatihan dasar digital marketing untuk pemula. Peserta akan belajar tentang social media marketing, SEO, dan content marketing."
    },
    {
        id: 2,
        name: "Pelatihan Desain Grafis",
        date: "20 Juni 2024",
        location: "Aula Kecamatan Maju Jaya",
        quota: 15,
        description: "Pelatihan desain grafis menggunakan Adobe Photoshop dan Illustrator. Cocok untuk pemula yang ingin belajar desain."
    },
    {
        id: 3,
        name: "Pelatihan Bahasa Inggris untuk Perhotelan",
        date: "25 Juni 2024",
        location: "Balai Desa Sukamaju",
        quota: 25,
        description: "Pelatihan bahasa Inggris khusus untuk bidang perhotelan dan pariwisata. Peserta akan belajar percakapan dasar dan terminologi perhotelan."
    },
    {
        id: 4,
        name: "Pelatihan Keterampilan Komputer Dasar",
        date: "1 Juli 2024",
        location: "Balai Desa Sukamaju",
        quota: 30,
        description: "Pelatihan dasar penggunaan komputer, Microsoft Office, dan internet. Cocok untuk pemula yang ingin belajar komputer."
    },
    {
        id: 5,
        name: "Pelatihan Barista",
        date: "5 Juli 2024",
        location: "Aula Kecamatan Maju Jaya",
        quota: 10,
        description: "Pelatihan dasar menjadi barista. Peserta akan belajar tentang jenis kopi, teknik brewing, dan latte art."
    },
    {
        id: 6,
        name: "Pelatihan Menjahit",
        date: "10 Juli 2024",
        location: "Balai Desa Sukamaju",
        quota: 15,
        description: "Pelatihan dasar menjahit untuk pemula. Peserta akan belajar tentang dasar-dasar menjahit, pola, dan teknik jahit."
    }
];

// Job data
const mockJobs = [
    {
        id: 1,
        title: "Customer Service",
        company: "PT Maju Bersama",
        type: "Full Time",
        location: "Kecamatan Maju Jaya",
        deadline: "30 Juni 2024",
        description: "Kami mencari Customer Service Representative yang berpengalaman untuk melayani pelanggan kami. Kandidat harus memiliki kemampuan komunikasi yang baik dan dapat bekerja dalam tim."
    },
    {
        id: 2,
        title: "Admin Kantor",
        company: "CV Sukses Mandiri",
        type: "Full Time",
        location: "Desa Sukamaju",
        deadline: "25 Juni 2024",
        description: "Dibutuhkan admin kantor untuk mengelola administrasi perusahaan. Kandidat harus teliti, rapi, dan memiliki kemampuan komputer yang baik."
    },
    {
        id: 3,
        title: "Barista",
        company: "Kopi Kita",
        type: "Part Time",
        location: "Kecamatan Maju Jaya",
        deadline: "20 Juni 2024",
        description: "Kopi Kita membuka lowongan untuk posisi barista. Pengalaman tidak diutamakan, akan dilatih. Kandidat harus memiliki semangat belajar dan attitude yang baik."
    },
    {
        id: 4,
        title: "Digital Marketing Staff",
        company: "PT Digital Kreatif",
        type: "Full Time",
        location: "Desa Sukamaju",
        deadline: "5 Juli 2024",
        description: "PT Digital Kreatif mencari Digital Marketing Staff untuk mengelola kampanye digital perusahaan. Kandidat harus memiliki pengetahuan tentang social media marketing dan SEO."
    },
    {
        id: 5,
        title: "Kasir",
        company: "Supermarket Sejahtera",
        type: "Full Time",
        location: "Kecamatan Maju Jaya",
        deadline: "15 Juni 2024",
        description: "Dibutuhkan kasir untuk Supermarket Sejahtera. Kandidat harus jujur, teliti, dan dapat bekerja dengan cepat."
    },
    {
        id: 6,
        title: "Guru Bahasa Inggris",
        company: "Lembaga Kursus Cerdas",
        type: "Part Time",
        location: "Desa Sukamaju",
        deadline: "10 Juli 2024",
        description: "Lembaga Kursus Cerdas membuka lowongan untuk posisi guru bahasa Inggris. Kandidat harus memiliki kemampuan bahasa Inggris yang baik dan suka mengajar."
    }
];

// Training history data
const mockTrainingHistory = [
    {
        id: 1,
        name: "Pelatihan Digital Marketing",
        date: "15 Mei 2024",
        location: "Balai Desa Sukamaju",
        status: "completed",
        description: "Pelatihan dasar digital marketing untuk pemula. Peserta akan belajar tentang social media marketing, SEO, dan content marketing."
    },
    {
        id: 2,
        name: "Pelatihan Bahasa Inggris untuk Perhotelan",
        date: "25 Mei 2024",
        location: "Balai Desa Sukamaju",
        status: "ongoing",
        description: "Pelatihan bahasa Inggris khusus untuk bidang perhotelan dan pariwisata. Peserta akan belajar percakapan dasar dan terminologi perhotelan."
    },
    {
        id: 3,
        name: "Pelatihan Desain Grafis",
        date: "10 Juni 2024",
        location: "Aula Kecamatan Maju Jaya",
        status: "pending",
        description: "Pelatihan desain grafis menggunakan Adobe Photoshop dan Illustrator. Cocok untuk pemula yang ingin belajar desain."
    }
];

// Job application history data
const mockJobApplicationHistory = [
    {
        id: 1,
        jobTitle: "Customer Service Representative",
        company: "PT Maju Bersama",
        location: "Kecamatan Maju Jaya",
        applicationDate: "5 Mei 2024",
        status: "accepted",
        cv: "CV_BudiSantoso.pdf"
    },
    {
        id: 2,
        jobTitle: "Admin Kantor",
        company: "CV Sukses Mandiri",
        location: "Desa Sukamaju",
        applicationDate: "10 Mei 2024",
        status: "rejected",
        cv: "CV_BudiSantoso.pdf"
    },
    {
        id: 3,
        jobTitle: "Digital Marketing Staff",
        company: "PT Digital Kreatif",
        location: "Desa Sukamaju",
        applicationDate: "1 Juni 2024",
        status: "pending",
        cv: "CV_BudiSantoso_Updated.pdf"
    }
];

// Admin training data
const mockAdminTrainings = [
    {
        id: 1,
        name: "Pelatihan Digital Marketing",
        date: "15 Juni 2024",
        location: "Balai Desa Sukamaju",
        quota: 20,
        status: "upcoming",
        description: "Pelatihan dasar digital marketing untuk pemula. Peserta akan belajar tentang social media marketing, SEO, dan content marketing."
    },
    {
        id: 2,
        name: "Pelatihan Desain Grafis",
        date: "20 Juni 2024",
        location: "Aula Kecamatan Maju Jaya",
        quota: 15,
        status: "upcoming",
        description: "Pelatihan desain grafis menggunakan Adobe Photoshop dan Illustrator. Cocok untuk pemula yang ingin belajar desain."
    },
    {
        id: 3,
        name: "Pelatihan Bahasa Inggris untuk Perhotelan",
        date: "25 Juni 2024",
        location: "Balai Desa Sukamaju",
        quota: 25,
        status: "upcoming",
        description: "Pelatihan bahasa Inggris khusus untuk bidang perhotelan dan pariwisata. Peserta akan belajar percakapan dasar dan terminologi perhotelan."
    },
    {
        id: 4,
        name: "Pelatihan Microsoft Office",
        date: "10 Mei 2024",
        location: "Balai Desa Sukamaju",
        quota: 30,
        status: "completed",
        description: "Pelatihan Microsoft Office (Word, Excel, PowerPoint) untuk pemula. Peserta akan belajar dasar-dasar penggunaan aplikasi Microsoft Office."
    },
    {
        id: 5,
        name: "Pelatihan Fotografi",
        date: "5 Mei 2024",
        location: "Aula Kecamatan Maju Jaya",
        quota: 15,
        status: "completed",
        description: "Pelatihan fotografi dasar untuk pemula. Peserta akan belajar tentang komposisi, pencahayaan, dan teknik dasar fotografi."
    }
];

// Admin job data
const mockAdminJobs = [
    {
        id: 1,
        title: "Customer Service Representative",
        company: "PT Maju Bersama",
        type: "Full Time",
        location: "Kecamatan Maju Jaya",
        deadline: "30 Juni 2024",
        status: "active",
        description: "Kami mencari Customer Service Representative yang berpengalaman untuk melayani pelanggan kami. Kandidat harus memiliki kemampuan komunikasi yang baik dan dapat bekerja dalam tim."
    },
    {
        id: 2,
        title: "Admin Kantor",
        company: "CV Sukses Mandiri",
        type: "Full Time",
        location: "Desa Sukamaju",
        deadline: "25 Juni 2024",
        status: "active",
        description: "Dibutuhkan admin kantor untuk mengelola administrasi perusahaan. Kandidat harus teliti, rapi, dan memiliki kemampuan komputer yang baik."
    },
    {
        id: 3,
        title: "Barista",
        company: "Kopi Kita",
        type: "Part Time",
        location: "Kecamatan Maju Jaya",
        deadline: "20 Juni 2024",
        status: "active",
        description: "Kopi Kita membuka lowongan untuk posisi barista. Pengalaman tidak diutamakan, akan dilatih. Kandidat harus memiliki semangat belajar dan attitude yang baik."
    },
    {
        id: 4,
        title: "Digital Marketing Staff",
        company: "PT Digital Kreatif",
        type: "Full Time",
        location: "Desa Sukamaju",
        deadline: "5 Juli 2024",
        status: "active",
        description: "PT Digital Kreatif mencari Digital Marketing Staff untuk mengelola kampanye digital perusahaan. Kandidat harus memiliki pengetahuan tentang social media marketing dan SEO."
    },
    {
        id: 5,
        title: "Kasir",
        company: "Supermarket Sejahtera",
        type: "Full Time",
        location: "Kecamatan Maju Jaya",
        deadline: "15 Mei 2024",
        status: "filled",
        description: "Dibutuhkan kasir untuk Supermarket Sejahtera. Kandidat harus jujur, teliti, dan dapat bekerja dengan cepat."
    },
    {
        id: 6,
        title: "Guru Bahasa Inggris",
        company: "Lembaga Kursus Cerdas",
        type: "Part Time",
        location: "Desa Sukamaju",
        deadline: "10 Mei 2024",
        status: "closed",
        description: "Lembaga Kursus Cerdas membuka lowongan untuk posisi guru bahasa Inggris. Kandidat harus memiliki kemampuan bahasa Inggris yang baik dan suka mengajar."
    }
];

// Training applicant data
const mockTrainingApplicants = [
    {
        id: 1,
        name: "Budi Santoso",
        training: "Pelatihan Digital Marketing",
        date: "10 Juni 2024",
        status: "pending"
    },
    {
        id: 2,
        name: "Ani Wijaya",
        training: "Pelatihan Desain Grafis",
        date: "12 Juni 2024",
        status: "accepted"
    },
    {
        id: 3,
        name: "Dedi Kurniawan",
        training: "Pelatihan Bahasa Inggris untuk Perhotelan",
        date: "15 Juni 2024",
        status: "rejected"
    },
    {
        id: 4,
        name: "Siti Rahayu",
        training: "Pelatihan Microsoft Office",
        date: "5 Mei 2024",
        status: "completed"
    },
    {
        id: 5,
        name: "Joko Widodo",
        training: "Pelatihan Digital Marketing",
        date: "11 Juni 2024",
        status: "pending"
    }
];

// Job applicant data
const mockJobApplicants = [
    {
        id: 1,
        name: "Budi Santoso",
        position: "Customer Service Representative",
        company: "PT Maju Bersama",
        date: "5 Juni 2024",
        cv: "CV_BudiSantoso.pdf",
        status: "pending"
    },
    {
        id: 2,
        name: "Ani Wijaya",
        position: "Admin Kantor",
        company: "CV Sukses Mandiri",
        date: "7 Juni 2024",
        cv: "CV_AniWijaya.pdf",
        status: "accepted"
    },
    {
        id: 3,
        name: "Dedi Kurniawan",
        position: "Barista",
        company: "Kopi Kita",
        date: "10 Juni 2024",
        cv: "CV_DediKurniawan.pdf",
        status: "rejected"
    },
    {
        id: 4,
        name: "Siti Rahayu",
        position: "Digital Marketing Staff",
        company: "PT Digital Kreatif",
        date: "12 Juni 2024",
        cv: "CV_SitiRahayu.pdf",
        status: "pending"
    },
    {
        id: 5,
        name: "Joko Widodo",
        position: "Customer Service Representative",
        company: "PT Maju Bersama",
        date: "6 Juni 2024",
        cv: "CV_JokoWidodo.pdf",
        status: "pending"
    }
];

