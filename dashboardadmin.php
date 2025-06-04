<?php
include 'config/config.php';
require 'auth/auth.php';
require_role('admin');

// Sample notification data
$notifications = [
    ['event' => 'EVENT X', 'buyer' => 'Andi', 'quantity' => 3, 'date' => '01/01/2024'],
    ['event' => 'EVENT Y', 'buyer' => 'Budi', 'quantity' => 1, 'date' => '02/01/2024'],
    ['event' => 'EVENT Z', 'buyer' => 'Citra', 'quantity' => 2, 'date' => '03/01/2024'],
    ['event' => 'EVENT A', 'buyer' => 'Dewi', 'quantity' => 5, 'date' => '04/01/2024'],
    ['event' => 'EVENT B', 'buyer' => 'Eko', 'quantity' => 4, 'date' => '05/01/2024'],
];

// Sample events data
$events = [
    ['no' => 1, 'name' => 'EVENT X', 'category' => 'X', 'date' => '01/01/2024'],
    ['no' => 2, 'name' => 'EVENT Y', 'category' => 'Y', 'date' => '02/01/2024'],
    ['no' => 3, 'name' => 'EVENT Z', 'category' => 'Z', 'date' => '03/01/2024'],
    ['no' => 4, 'name' => 'EVENT A', 'category' => 'A', 'date' => '04/01/2024'],
    ['no' => 5, 'name' => 'EVENT B', 'category' => 'B', 'date' => '05/01/2024'],
    ['no' => 6, 'name' => 'EVENT C', 'category' => 'C', 'date' => '06/01/2024'],
    ['no' => 7, 'name' => 'EVENT D', 'category' => 'D', 'date' => '07/01/2024'],
    ['no' => 8, 'name' => 'EVENT E', 'category' => 'E', 'date' => '08/01/2024'],
    ['no' => 9, 'name' => 'EVENT F', 'category' => 'F', 'date' => '09/01/2024'],
    ['no' => 10, 'name' => 'EVENT G', 'category' => 'G', 'date' => '10/01/2024'],
    ['no' => 11, 'name' => 'EVENT H', 'category' => 'H', 'date' => '11/01/2024'],
    ['no' => 12, 'name' => 'EVENT I', 'category' => 'I', 'date' => '12/01/2024'],
    ['no' => 13, 'name' => 'EVENT J', 'category' => 'J', 'date' => '13/01/2024'],
    ['no' => 14, 'name' => 'EVENT K', 'category' => 'K', 'date' => '14/01/2024'],
    ['no' => 15, 'name' => 'EVENT L', 'category' => 'L', 'date' => '15/01/2024'],
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evently - Admin Dashboard</title>
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

        .bg-primary-custom {
            background-color: var(--primary-color) !important;
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

        /* Account section styling */
        .account-section {
            background-color: var(--primary-color);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .account-icon {
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        /* Notification section styling */
        .notification-section {
            background-color: var(--primary-color);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            height: 300px;
            overflow-y: auto;
        }

        .notification-item {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            font-size: 0.85rem;
            line-height: 1.4;
        }

        .notification-item:last-child {
            margin-bottom: 0;
        }

        .notification-title {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .notification-icon {
            width: 24px;
            height: 24px;
            margin-right: 10px;
        }

        /* Events section styling */
        .events-section {
            background-color: var(--primary-color);
            color: white;
            border-radius: 12px;
            padding: 20px;
        }

        .events-table {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 15px;
        }

        .events-table table {
            margin-bottom: 0;
            color: #333;
        }

        .events-table th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 8px;
            border: none;
        }

        .events-table td {
            padding: 10px 8px;
            font-size: 0.85rem;
            border-top: 1px solid #dee2e6;
        }

        .events-table tbody tr:hover {
            background-color: rgba(59, 139, 255, 0.05);
        }

        .events-table tbody tr:nth-child(even) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-content {
                padding: 15px !important;
            }

            .notification-section,
            .events-section {
                margin-bottom: 15px;
            }
        }
    </style>
</head>

<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid px-3">
            <span class="navbar-brand fw-bold fs-5 mb-0">EVENTLY</span>
            <div class="d-flex align-items-center">
                <a href="dashboardadmin.php" class="admin-username">DASHBOARD</a>
                <a href="#" class="logout-link" onclick="logout()">
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
            <div class="col-md-10 col-lg-10 main-content p-4">
                <!-- Account Section -->
                <div class="account-section">
                    <div class="d-flex align-items-center">
                        <div class="account-icon">
                            <i class="fas fa-user fa-lg"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">AKUN</h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Notifications Section -->
                    <div class="col-md-6">
                        <div class="notification-section">
                            <div class="notification-title">
                                <i class="fas fa-bell notification-icon"></i>
                                <h5 class="mb-0 fw-bold">NOTIFIKASI PEMBELIAN</h5>
                            </div>

                            <?php foreach ($notifications as $notification): ?>
                                <div class="notification-item">
                                    - Tiket <?php echo htmlspecialchars($notification['event']); ?> berhasil dibeli oleh <strong><?php echo htmlspecialchars($notification['buyer']); ?></strong> sebanyak <?php echo $notification['quantity']; ?> pada <strong><?php echo htmlspecialchars($notification['date']); ?></strong>.
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Events Section -->
                    <div class="col-md-6">
                        <div class="events-section">
                            <h5 class="mb-0 fw-bold">EVENT TERBARU</h5>

                            <div class="events-table">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th style="width: 60px;">NO.</th>
                                            <th>NAMA EVENT</th>
                                            <th style="width: 100px;">TANGGAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($events as $event): ?>
                                            <tr>
                                                <td class="text-center"><?php echo $event['no']; ?></td>
                                                <td><?php echo htmlspecialchars($event['name']); ?></td>
                                                <td class="text-center" style="font-size: 0.8rem;"><?php echo htmlspecialchars($event['date']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function logout() {
            window.location.href = 'login.php';
        }
    </script>
</body>

</html>