<?php

include 'config/config.php';
// Sample data - Replace this section with database connection

// Function to format currency
function formatCurrency($amount)
{
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evently - Pemesanan Tiket</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Admin Styles -->
    <link rel="stylesheet" href="style/admin-styles.css">
</head>

<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid px-3">
            <span class="navbar-brand fw-bold fs-5 mb-0">EVENTLY</span>
            <div class="d-flex align-items-center">
                <span class="admin-username">ADMIN#1</span>
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
                <!-- Header with Search -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="text-primary-custom fw-bold">PEMESANAN TIKET</h2>
                </div>

                <!-- Orders Table -->
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center" style="width: 100px;">ID PESANAN</th>
                                        <th scope="col" class="text-center">ID TIKET</th>
                                        <th scope="col" class="text-center">ID USER</th>
                                        <th scope="col" class="text-center">METODE BAYAR</th>
                                        <th scope="col" class="text-center">BANYAK TIKET</th>
                                        <th scope="col" class="text-center">TOTAL HARGA</th>
                                        <th scope="col" class="text-center">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM pesanan";
                                    $result = mysqli_query($conn, $query);

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td scope='col' class='text-center' style='width: 100px;'>" . $row['id_pesanan'] . "</td>";
                                        echo "<td scope='col' class='text-center'>" . $row['id_tiket'] . "</td>";
                                        echo "<td scope='col' class='text-center'>" . $row['id_user'] . "</td>";
                                        echo "<td scope='col' class='text-center'>" . $row['metode_bayar'] . "</td>";
                                        echo "<td scope='col' class='text-center'>" . $row['banyak_tiket'] . "</td>";
                                        echo "<td scope='col' class='text-center'>Rp " . number_format($row['total_harga'], 0, ',', '.') . "</td>";
                                        echo "<td scope='col' class='text-center'>" . $row['status_pesanan'] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>