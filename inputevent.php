<?php
include 'config/config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'config/config.php'; // gunakan file koneksi yang baru

    // Ambil data dari form
    $nama_event = $_POST['nama_event'] ?? '';
    $kategori = $_POST['kategori'] ?? '';
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
    $harga = isset($_POST['harga']) ? (int)str_replace('.', '', $_POST['harga']) : 0;
    $stok  = isset($_POST['stok']) ? (int)$_POST['stok'] : 0;

    $deskripsi = $_POST['deskripsi'] ?? '';

    // Baca file gambar ke dalam variabel binary
    $poster_blob = null;
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
        $poster_blob = file_get_contents($_FILES['event_image']['tmp_name']);
    }

    // Ambil ID event baru (manual auto-increment)
    $getMaxEvent = mysqli_query($conn, "SELECT MAX(id_event) AS max_event FROM event");
    $maxEvent = mysqli_fetch_assoc($getMaxEvent);
    $nextEventId = $maxEvent['max_event'] ? $maxEvent['max_event'] + 1 : 1110000;

    // Simpan ke tabel event
    $stmt = mysqli_prepare($conn,"INSERT INTO event (id_event, nama_event, kategori, deskripsi_event, tanggal_event, tanggal_selesai, poster_event) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issssss", $nextEventId, $nama_event, $kategori, $deskripsi, $tanggal_mulai, $tanggal_selesai, $poster_blob);
    mysqli_stmt_send_long_data($stmt, 5, $poster_blob);

    if (mysqli_stmt_execute($stmt)) {
        // Ambil ID tiket baru (manual auto-increment)
        $getMaxTiket = mysqli_query($conn, "SELECT MAX(id_tiket) AS max_tiket FROM tiket");
        $maxTiket = mysqli_fetch_assoc($getMaxTiket);
        $nextTiketId = $maxTiket['max_tiket'] ? $maxTiket['max_tiket'] + 1 : 1120000;

        // Simpan tiket default
        $query_tiket = "INSERT INTO tiket (id_tiket, harga_tiket, stok_tiket, id_event)
                        VALUES ('$nextTiketId', '$harga', '$stok', '$nextEventId')";
        $insert_tiket = mysqli_query($conn, $query_tiket);

        if ($insert_tiket) {
            $success_message = "Event dan tiket berhasil ditambahkan!";
        } else {
            $error_message = "Gagal menambahkan tiket: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Gagal menambahkan event: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evently - Tambah Event</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3B8BFF;
        }

        .navbar-custom {
            background-color: var(--primary-color) !important;
        }

        .text-primary-custom {
            color: var(--primary-color) !important;
        }

        .border-primary-custom {
            border-color: var(--primary-color) !important;
        }

        .btn-outline-primary-custom {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary-custom:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .sidebar {
            min-height: calc(100vh - 56px);
        }

        /* Admin username styling */
        .admin-username {
            color: #fff;
            font-weight: 500;
            margin-right: 1rem;
        }

        /* Logout link styling with hover effect */
        .logout-link {
            color: #ffcccb;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .logout-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            text-decoration: none;
            transform: translateY(-1px);
        }

        /* Remove underlines from sidebar navigation */
        .sidebar .nav-link {
            text-decoration: none !important;
        }

        .sidebar .nav-link:hover {
            text-decoration: none !important;
        }

        /* Form styling */
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(59, 139, 255, 0.25);
        }

        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(59, 139, 255, 0.25);
        }

        /* Image upload area */
        .image-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 0.375rem;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            cursor: pointer;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .image-upload-area:hover {
            border-color: var(--primary-color);
            background-color: rgba(59, 139, 255, 0.05);
        }

        .image-upload-area.drag-over {
            border-color: var(--primary-color);
            background-color: rgba(59, 139, 255, 0.1);
        }

        .image-preview {
            max-width: 100%;
            max-height: 180px;
            border-radius: 0.375rem;
        }

        /* Button styling */
        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 0.625rem 1.25rem;
            border-radius: 0.375rem;
            transition: all 0.15s ease-in-out;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.875rem;
        }

        .btn-primary-custom:hover {
            background-color: #2a6adf;
            border-color: #2a6adf;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(59, 139, 255, 0.25);
        }

        .btn-primary-custom:focus {
            box-shadow: 0 0 0 0.2rem rgba(59, 139, 255, 0.5);
        }

        .btn-secondary-custom {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
            font-weight: 600;
            padding: 0.625rem 1.25rem;
            border-radius: 0.375rem;
            transition: all 0.15s ease-in-out;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.875rem;
        }

        .btn-secondary-custom:hover {
            background-color: #5a6268;
            border-color: #545b62;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(108, 117, 125, 0.25);
        }

        /* Card styling */
        .form-card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }

        /* Required field indicator */
        .required {
            color: #dc3545;
        }
    </style>
</head>

<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid px-3">
            <span class="navbar-brand fw-bold fs-5 mb-0">EVENTLY</span>
            <div class="d-flex align-items-center">
                <a href="dashboardadmin.php" class="admin-username">ADMIN#1</a>
                <a href="#" class="logout-link">
                    LOG OUT
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 col-lg-2 sidebar bg-white p-4">
                <h5 class="fw-bold text-primary-custom mb-4">KELOLA</h5>
                <nav class="nav flex-column">
                    <a href="daftarevent.php" class="nav-link text-primary-custom fw-semibold p-0 mb-3" href="#">
                        DAFTAR EVENT
                    </a>
                    <a href="daftaruser.php" class="nav-link text-primary-custom fw-semibold p-0 mb-3" href="#">
                        DAFTAR USER
                    </a>
                    <a href="pemesanantiket.php" class="nav-link text-primary-custom fw-semibold p-0 mb-3" href="#">
                        PEMESANAN TIKET
                    </a>
                    <a href="pembayaran.php" class="nav-link text-primary-custom fw-semibold p-0 mb-3" href="#">
                        PEMBAYARAN USER
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 col-lg-10 p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="text-primary-custom fw-bold">TAMBAH EVENT BARU</h2>
                    <a href="daftarevent.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Kembali ke Daftar Event
                    </a>
                </div>

                <!-- Success Message -->
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo htmlspecialchars($success_message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Form Card -->
                <div class="card form-card">
                    <div class="card-body p-4">
                        <form method="POST" enctype="multipart/form-data" id="eventForm">
                            <div class="row">
                                <!-- Image Upload Section -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label fw-semibold">
                                        Gambar Event <span class="required">*</span>
                                    </label>
                                    <div class="image-upload-area" onclick="document.getElementById('event_image').click()">
                                        <div id="imagePreview" class="text-center">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-2">Klik untuk memilih gambar</p>
                                            <small class="text-muted">atau drag & drop file di sini</small>
                                            <p class="text-muted small mt-2">Format: JPG, PNG, GIF (Max: 2MB)</p>
                                        </div>
                                    </div>
                                    <input type="file" id="event_image" name="event_image" class="d-none" accept="image/*" required>
                                </div>

                                <!-- Form Fields Section -->
                                <div class="col-md-8">
                                    <div class="row">
                                        <!-- Event Name -->
                                        <div class="col-md-6 mb-3">
                                            <label for="nama_event" class="form-label fw-semibold">
                                                Nama Event <span class="required">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="nama_event" name="nama_event"
                                                placeholder="Masukkan nama event" required>
                                        </div>

                                        <!-- Category -->
                                        <div class="col-md-6 mb-3">
                                            <label for="kategori" class="form-label fw-semibold">
                                                Kategori <span class="required">*</span>
                                            </label>
                                            <select class="form-select" id="kategori" name="kategori" required>
                                                <option value="">Pilih Kategori</option>
                                                <option value="Music">Music</option>
                                                <option value="Technology">Technology</option>
                                                <option value="Art">Art</option>
                                                <option value="Food">Food</option>
                                                <option value="Entertainment">Entertainment</option>
                                                <option value="Education">Education</option>
                                                <option value="Business">Business</option>
                                                <option value="Sports">Sports</option>
                                            </select>
                                        </div>

                                        <!-- Start Date -->
                                        <div class="col-md-6 mb-3">
                                            <label for="tanggal_mulai" class="form-label fw-semibold">
                                                Tanggal Mulai <span class="required">*</span>
                                            </label>
                                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                                        </div>

                                        <!-- End Date -->
                                        <div class="col-md-6 mb-3">
                                            <label for="tanggal_selesai" class="form-label fw-semibold">
                                                Tanggal Selesai
                                            </label>
                                            <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai">
                                        </div>

                                        <!-- Price -->
                                        <div class="col-md-6 mb-3">
                                            <label for="harga" class="form-label fw-semibold">
                                                Harga Tiket <span class="required">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control" id="harga" name="harga"
                                                    placeholder="0" min="0" required>
                                            </div>
                                        </div>

                                        <!-- Stock -->
                                        <div class="col-md-6 mb-3">
                                            <label for="stok" class="form-label fw-semibold">
                                                Stok Tiket <span class="required">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="stok" name="stok"
                                                    placeholder="0" min="0" required>
                                            </div>
                                        </div>


                                        <!-- Description -->
                                        <div class="col-12 mb-3">
                                            <label for="deskripsi" class="form-label fw-semibold">
                                                Deskripsi Event <span class="required">*</span>
                                            </label>
                                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"
                                                placeholder="Masukkan deskripsi detail tentang event" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-3">
                                        <button type="button" class="btn btn-secondary-custom" onclick="resetForm()">
                                            <i class="fas fa-times me-2"></i>
                                            BATAL
                                        </button>
                                        <button type="submit" class="btn btn-primary-custom">
                                            <i class="fas fa-plus me-2"></i>
                                            TAMBAH EVENT
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Help Text -->
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Field yang ditandai dengan <span class="required">*</span> wajib diisi
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Image upload preview
        document.getElementById('event_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewContainer = document.getElementById('imagePreview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.innerHTML = `
                        <img src="${e.target.result}" class="image-preview" alt="Preview">
                        <p class="text-muted mt-2 small">${file.name}</p>
                    `;
                };
                reader.readAsDataURL(file);
            } else {
                resetImagePreview();
            }
        });

        // Drag and drop functionality
        const uploadArea = document.querySelector('.image-upload-area');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            uploadArea.classList.add('drag-over');
        }

        function unhighlight(e) {
            uploadArea.classList.remove('drag-over');
        }

        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                document.getElementById('event_image').files = files;
                document.getElementById('event_image').dispatchEvent(new Event('change'));
            }
        }

        // Reset form function
        function resetForm() {
            if (confirm('Apakah Anda yakin ingin membatalkan? Semua data yang dimasukkan akan hilang.')) {
                document.getElementById('eventForm').reset();
                resetImagePreview();
            }
        }

        function resetImagePreview() {
            document.getElementById('imagePreview').innerHTML = `
                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                <p class="text-muted mb-2">Klik untuk memilih gambar</p>
                <small class="text-muted">atau drag & drop file di sini</small>
                <p class="text-muted small mt-2">Format: JPG, PNG, GIF (Max: 2MB)</p>
            `;
        }

        // Form validation
        document.getElementById('eventForm').addEventListener('submit', function(e) {
            const startDate = document.getElementById('tanggal_mulai').value;
            const endDate = document.getElementById('tanggal_selesai').value;

            if (endDate && new Date(endDate) < new Date(startDate)) {
                e.preventDefault();
                alert('Tanggal selesai tidak boleh lebih awal dari tanggal mulai!');
                return false;
            }

            // Check file size
            const fileInput = document.getElementById('event_image');
            if (fileInput.files[0] && fileInput.files[0].size > 2 * 1024 * 1024) {
                e.preventDefault();
                alert('Ukuran file tidak boleh lebih dari 2MB!');
                return false;
            }
        });

        // Set minimum date to today
        document.getElementById('tanggal_mulai').min = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal_selesai').min = new Date().toISOString().split('T')[0];

        // Update end date minimum when start date changes
        document.getElementById('tanggal_mulai').addEventListener('change', function() {
            document.getElementById('tanggal_selesai').min = this.value;
        });
    </script>
</body>

</html>