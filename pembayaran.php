<?php
// Sample data - Replace this section with database connection
$ticket_orders = [
    ['id' => 'T001', 'event' => 'Concert Rock Spectacular', 'user_id' => 'U001', 'quantity' => 2, 'total' => 300000, 'status' => 'Confirmed'],
    ['id' => 'T002', 'event' => 'Jazz Night Festival', 'user_id' => 'U002', 'quantity' => 1, 'total' => 200000, 'status' => 'Pending'],
    ['id' => 'T003', 'event' => 'Tech Conference 2024', 'user_id' => 'U003', 'quantity' => 3, 'total' => 1500000, 'status' => 'Confirmed'],
    ['id' => 'T004', 'event' => 'Art Exhibition Modern', 'user_id' => 'U004', 'quantity' => 1, 'total' => 75000, 'status' => 'Cancelled'],
    ['id' => 'T005', 'event' => 'Food Festival Jakarta', 'user_id' => 'U005', 'quantity' => 4, 'total' => 400000, 'status' => 'Confirmed'],
];

// Function to format currency
function formatCurrency($amount)
{
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

// Handle search functionality
$searchTerm = '';
$filteredOrders = $ticket_orders;

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = trim($_GET['search']);
    $filteredOrders = array_filter($ticket_orders, function ($order) use ($searchTerm) {
        return stripos($order['id'], $searchTerm) !== false ||
            stripos($order['event'], $searchTerm) !== false ||
            stripos($order['user_id'], $searchTerm) !== false;
    });
}

// Reset array keys after filtering
$filteredOrders = array_values($filteredOrders);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Evently - Pembayaran</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* =================================
           ADMIN PANEL STYLES - EVENTLY
           ================================= */

        /* CSS Variables */
        :root {
            --primary-color: #3B8BFF;
            --primary-hover: #2a6adf;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #495057;
            --muted-color: #6c757d;
            --border-color: #dee2e6;
            --hover-bg: rgba(0, 0, 0, 0.075);
            --stripe-bg: rgba(0, 0, 0, 0.05);
        }

        /* =================================
           NAVBAR STYLES
           ================================= */
        .navbar-custom {
            background-color: var(--primary-color) !important;
        }

        .admin-username {
            color: #fff;
            font-weight: 500;
            margin-right: 1rem;
        }

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

        /* =================================
           SIDEBAR STYLES
           ================================= */
        .sidebar {
            min-height: calc(100vh - 56px);
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

        /* Remove underlines from sidebar navigation */
        .sidebar .nav-link {
            text-decoration: none !important;
        }

        .sidebar .nav-link:hover {
            text-decoration: none !important;
        }

        /* =================================
           TABLE STYLES
           ================================= */
        .table {
            width: 100%;
            border-collapse: collapse;
            color: #212529;
            font-size: 0.875rem;
            background-color: #fff;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: middle;
            border-top: 1px solid var(--border-color);
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid var(--border-color);
            border-top: none;
            background-color: var(--light-color);
            font-weight: 600;
            color: var(--dark-color);
            position: sticky;
            top: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.75rem;
        }

        .table tbody tr:hover {
            background-color: var(--hover-bg);
        }

        .table tbody tr:nth-child(odd) {
            background-color: var(--stripe-bg);
        }

        /* =================================
           SEARCH STYLES
           ================================= */
        .search-container {
            position: relative;
        }

        .search-btn {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: var(--primary-color);
            padding: 4px 8px;
        }

        .search-btn:hover {
            color: var(--primary-hover);
        }

        /* =================================
           BUTTON STYLES
           ================================= */

        /* Edit Button */
        .btn-edit {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            border-radius: 0.25rem;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-block;
            font-weight: 500;
        }

        .btn-edit:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: white;
            text-decoration: none;
        }

        /* Delete Button */
        .btn-delete {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
            color: white;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
            transition: all 0.15s ease-in-out;
        }

        .btn-delete:hover {
            background-color: #c82333;
            border-color: #bd2130;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.25);
        }

        .btn-delete:focus {
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.5);
        }

        /* =================================
           STATUS DROPDOWN STYLES
           ================================= */
        .status-dropdown {
            border: 1px solid var(--border-color);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            min-width: 120px;
        }

        .status-dropdown option[value="Confirmed"] {
            color: var(--success-color);
        }

        .status-dropdown option[value="Pending"] {
            color: var(--warning-color);
        }

        .status-dropdown option[value="Cancelled"] {
            color: var(--danger-color);
        }

        /* =================================
           CATEGORY STYLES
           ================================= */
        .category {
            background-color: var(--light-color);
            color: var(--dark-color);
            font-weight: 500;
        }

        /* =================================
           EMPTY STATE STYLES
           ================================= */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--muted-color);
            margin-bottom: 1rem;
        }

        .empty-state h5 {
            color: var(--muted-color);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--muted-color);
        }

        /* =================================
           RESPONSIVE ADJUSTMENTS
           ================================= */
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }

            .table-responsive {
                font-size: 0.8rem;
            }

            .btn-edit,
            .btn-delete {
                padding: 0.2rem 0.4rem;
                font-size: 0.7rem;
            }

            .search-container {
                width: 100% !important;
                margin-bottom: 1rem;
            }

            .admin-username {
                display: none;
            }
        }

        /* =================================
           UTILITY CLASSES
           ================================= */
        .fw-medium {
            font-weight: 500;
        }

        .text-dark-custom {
            color: #212529 !important;
        }

        .text-muted-custom {
            color: var(--muted-color) !important;
        }

        .bg-light-custom {
            background-color: var(--light-color) !important;
        }
    </style>
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
                    <h2 class="text-primary-custom fw-bold">PEMBAYARAN</h2>

                    <!-- Search Form -->
                    <form class="search-container" method="GET" style="width: 250px;">
                        <div class="input-group">
                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Search order..."
                                value="<?php echo htmlspecialchars($searchTerm); ?>"
                                style="padding-right: 40px;">
                            <button class="search-btn" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Search Results Info -->
                <?php if (!empty($searchTerm)): ?>
                    <div class="alert alert-info d-flex justify-content-between align-items-center" role="alert">
                        <span>
                            <i class="fas fa-info-circle me-2"></i>
                            Showing <?php echo count($filteredOrders); ?> result(s) for "<?php echo htmlspecialchars($searchTerm); ?>"
                        </span>
                        <a href="?" class="btn btn-sm btn-outline-secondary">Clear Search</a>
                    </div>
                <?php endif; ?>

                <!-- Orders Table -->
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <?php if (empty($filteredOrders)): ?>
                            <div class="empty-state">
                                <i class="fas fa-ticket-alt"></i>
                                <h5>No orders found</h5>
                                <p>Try adjusting your search criteria</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center" style="width: 100px;">ID TIKET</th>
                                            <th scope="col">NAMA EVENT</th>
                                            <th scope="col" class="text-center" style="width: 80px;">ID USER</th>
                                            <th scope="col" class="text-center" style="width: 120px;">BANYAK TIKET</th>
                                            <th scope="col" class="text-end" style="width: 150px;">TOTAL HARGA</th>
                                            <th scope="col" class="text-center" style="width: 130px;">STATUS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($filteredOrders as $order): ?>
                                            <tr>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-dark fw-semibold"><?php echo htmlspecialchars($order['id']); ?></span>
                                                </td>
                                                <td class="fw-medium text-dark"><?php echo htmlspecialchars($order['event']); ?></td>
                                                <td class="text-center text-muted"><?php echo htmlspecialchars($order['user_id']); ?></td>
                                                <td class="text-center"><?php echo $order['quantity']; ?></td>
                                                <td class="text-end fw-medium"><?php echo formatCurrency($order['total']); ?></td>
                                                <td class="text-center">
                                                    <select class="form-select form-select-sm status-dropdown"
                                                        data-order-id="<?php echo htmlspecialchars($order['id']); ?>"
                                                        onchange="updateStatus(this)">
                                                        <option value="Confirmed" <?php echo $order['status'] === 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                                        <option value="Pending" <?php echo $order['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="Cancelled" <?php echo $order['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Pagination Info -->
                <?php if (!empty($filteredOrders)): ?>
                    <div class="mt-3 text-muted">
                        <small>Showing <?php echo count($filteredOrders); ?> of <?php echo count($ticket_orders); ?> orders</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Optional: Auto-submit search on input -->
    <script>
        // Auto-submit search after user stops typing (debounced)
        let searchTimeout;
        const searchInput = document.querySelector('input[name="search"]');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (this.value.length >= 2 || this.value.length === 0) {
                        this.form.submit();
                    }
                }, 500); // 500ms delay
            });
        }

        // Handle status update
        function updateStatus(selectElement) {
            const orderId = selectElement.getAttribute('data-order-id');
            const newStatus = selectElement.value;

            // Here you would typically send an AJAX request to update the database
            // For now, we'll just show a confirmation
            console.log(`Updating order ${orderId} to status: ${newStatus}`);

            // Optional: Show a temporary success message
            const row = selectElement.closest('tr');
            row.style.backgroundColor = '#d4edda';
            setTimeout(() => {
                row.style.backgroundColor = '';
            }, 1000);

            // In a real application, you would make an AJAX call like this:
            // fetch('update_status.php', {
            //     method: 'POST',
            //     headers: {
            //         'Content-Type': 'application/json',
            //     },
            //     body: JSON.stringify({
            //         order_id: orderId,
            //         status: newStatus
            //     })
            // });
        }
    </script>
</body>

</html>