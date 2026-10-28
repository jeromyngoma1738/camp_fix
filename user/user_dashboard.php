<?php
require_once __DIR__.'/../config/auth_check.php';
requireRole('student');                       // only students may open this page
require_once __DIR__.'/../config/database.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

const DEBUG = true;                           // set to false before going live

$userid = (int) $_SESSION['user_id'];
$username = $_SESSION['name'] ?? 'User';

$totalFaults = $pendingCount = $inprogress = $countComplete = 0;
$recentFaults = [];
$dbError = null;

try {
    // ---- Statistics ----
    $stmt = $conn->prepare(
        "SELECT COUNT(*) AS total_faults,
                SUM(fault_status = 'Pending')     AS pending,
                SUM(fault_status = 'In Progress') AS in_progress,
                SUM(fault_status = 'Resolved')    AS resolved
         FROM faults
         WHERE user_id = ?"
    );
    $stmt->bind_param('i', $userid);
    $stmt->execute();
    $stats = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $totalFaults = (int) ($stats['total_faults'] ?? 0);
    $pendingCount = (int) ($stats['pending'] ?? 0);
    $inprogress = (int) ($stats['in_progress'] ?? 0);
    $countComplete = (int) ($stats['resolved'] ?? 0);

    // ---- Recent faults ----
    // SELECT * so the page works whatever the category/location columns are called
    $stmt = $conn->prepare('SELECT * FROM faults WHERE user_id = ? ORDER BY id DESC LIMIT 5');
    $stmt->bind_param('i', $userid);
    $stmt->execute();
    $recentFaults = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (mysqli_sql_exception $e) {
    error_log('CampusFix user_dashboard error: '.$e->getMessage());
    $dbError = 'An error has occurred while loading your data from the database.';
    if (DEBUG) {
        $dbError .= ' ('.$e->getMessage().')';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CampusFix | User Dashboard</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="user_style.css">

</head>


<body>

<div class="dashboard">


    <!-- ========================================
         SIDEBAR
    ========================================= -->

    <aside class="sidebar">

        <div class="logo">

            <div class="logo-icon">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>

            <div>
                <h2>CampusFix</h2>
                <span>CBU Maintenance</span>
            </div>

        </div>


        <nav class="sidebar-nav">

            <a href="user_dashboard.php" class="nav-link active">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>


            <a href="report_fault.php" class="nav-link">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>Report Fault</span>
            </a>


            <a href="my_faults.php" class="nav-link">
                <i class="fa-solid fa-list-check"></i>
                <span>My Faults</span>
            </a>


            <a href="notifications.php" class="nav-link">
                <i class="fa-solid fa-bell"></i>
                <span>Notifications</span>
            </a>


            <a href="profile.php" class="nav-link">
                <i class="fa-solid fa-user"></i>
                <span>My Profile</span>
            </a>


            <a href="settings.php" class="nav-link">
                <i class="fa-solid fa-gear"></i>
                <span>Settings</span>
            </a>

        </nav>


        <div class="sidebar-bottom">

            <a href="../auth/logout.php" class="nav-link logout">

                <i class="fa-solid fa-right-from-bracket"></i>

                <span>Logout</span>

            </a>

        </div>

    </aside>



    <!-- ========================================
         MAIN CONTENT
    ========================================= -->

    <main class="main-content">


        <!-- TOP BAR -->

        <header class="topbar">

            <div class="page-title">

                <h1>Dashboard</h1>

                <p>
                    Manage and track your maintenance requests.
                </p>

            </div>


            <div class="user-area">

                <button class="notification-button">

                    <i class="fa-solid fa-bell"></i>

                </button>


                <div class="user-profile">

                    <div class="avatar">

                        <?php
                        echo strtoupper(substr($username, 0, 1));
?>

                    </div>


                    <div class="user-info">

                        <strong>
                            <?php echo htmlspecialchars($username); ?>
                        </strong>

                        <small>Student</small>

                    </div>


                    <i class="fa-solid fa-chevron-down"></i>

                </div>

            </div>

        </header>



        <!-- ========================================
             PAGE CONTENT
        ========================================= -->

        <section class="content">

            <?php if ($dbError) { ?>
                <div class="alert error"><?php echo htmlspecialchars($dbError); ?></div>
            <?php } ?>


            <!-- WELCOME SECTION -->

            <div class="welcome-section">

                <div>

                    <h2>
                        Welcome back,
                        <?php echo htmlspecialchars($username); ?>
                    </h2>


                    <p>
                        Here's an overview of your maintenance requests.
                    </p>

                </div>


                <a href="report_fault.php" class="primary-button">

                    <i class="fa-solid fa-plus"></i>

                    Report New Fault

                </a>

            </div>



            <!-- ========================================
                 STATISTICS
            ========================================= -->

            <div class="stats-grid">


                <!-- PENDING -->

                <div class="stat-card">

                    <div class="stat-icon pending">

                        <i class="fa-solid fa-clock"></i>

                    </div>


                    <div class="stat-information">

                        <span>Pending</span>

                        <h3>
                            <?php echo $pendingCount; ?>
                        </h3>

                    </div>

                </div>



                <!-- IN PROGRESS -->

                <div class="stat-card">

                    <div class="stat-icon progress">

                        <i class="fa-solid fa-spinner"></i>

                    </div>


                    <div class="stat-information">

                        <span>In Progress</span>

                        <h3>
                            <?php echo $inprogress; ?>
                        </h3>

                    </div>

                </div>



                <!-- RESOLVED -->

                <div class="stat-card">

                    <div class="stat-icon resolved">

                        <i class="fa-solid fa-circle-check"></i>

                    </div>


                    <div class="stat-information">

                        <span>Resolved</span>

                        <h3>
                            <?php echo $countComplete; ?>
                        </h3>

                    </div>

                </div>


            </div>



            <!-- ========================================
                 MAIN DASHBOARD GRID
            ========================================= -->

            <div class="dashboard-grid">


                <!-- ========================================
                     RECENT FAULTS
                ========================================= -->

                <section class="dashboard-card faults-card">


                    <div class="card-header">

                        <div>

                            <h3>Recent Faults</h3>

                            <p>
                                Your latest maintenance requests.
                            </p>

                        </div>


                        <a href="my_faults.php" class="view-all">

                            View All

                            <i class="fa-solid fa-arrow-right"></i>

                        </a>

                    </div>



                    <!-- FAULT TABLE -->

                    <div class="table-container">

                        <table>


                            <thead>

                                <tr>

                                    <th>Ticket</th>

                                    <th>Fault</th>

                                    <th>Location</th>

                                    <th>Status</th>

                                    <th>Date</th>

                                    <th>Action</th>

                                </tr>

                            </thead>



                            <tbody>


                                <?php if (!empty($recentFaults)) { ?>


                                    <?php foreach ($recentFaults as $row) { ?>


                                        <tr>


                                            <!-- TICKET NUMBER -->

                                            <td>

                                                <?php
                        echo htmlspecialchars($row['ticket_number']);
                                        ?>

                                            </td>



                                            <!-- FAULT CATEGORY -->

                                            <td>

                                                <?php echo htmlspecialchars((string) ($row['category'] ?? $row['category_name'] ?? $row['category_id'] ?? '')); ?>

                                            </td>



                                            <!-- LOCATION -->

                                            <td>

                                                <?php
                                        echo htmlspecialchars((string) ($row['location'] ?? $row['fault_location'] ?? $row['location_id'] ?? ''));
                                        ?>

                                            </td>



                                            <!-- STATUS -->

                                            <td>

                                                <span class="status-badge">

                                                    <?php
                                            echo htmlspecialchars($row['fault_status']);
                                        ?>

                                                </span>

                                            </td>



                                            <!-- DATE -->

                                            <td>

                                                <?php
                                                echo htmlspecialchars((string) ($row['created_at'] ?? ''));
                                        ?>

                                            </td>



                                            <!-- VIEW DETAILS -->

                                            <td>

                                                <a href="view_fault.php?id=<?php echo (int) $row['id']; ?>">

                                                    View Details

                                                </a>

                                            </td>


                                        </tr>


                                    <?php } ?>


                                <?php } else { ?>


                                    <!-- NO FAULTS -->

                                    <tr>

                                        <td colspan="6" style="text-align:center; padding:25px;">

                                            <i class="fa-solid fa-clipboard-check"></i>

                                            <p>
                                                You have not reported any faults yet.
                                            </p>

                                            <a href="report_fault.php">

                                                Report Your First Fault

                                            </a>

                                        </td>

                                    </tr>


                                <?php } ?>


                            </tbody>


                        </table>

                    </div>


                </section>



                <!-- ========================================
                     RECENT UPDATES
                ========================================= -->

                <section class="dashboard-card notifications-card">


                    <div class="card-header">

                        <div>

                            <h3>Recent Updates</h3>

                            <p>
                                Stay informed about your maintenance requests.
                            </p>

                        </div>


                        <a href="notifications.php" class="view-all">

                            View All

                        </a>

                    </div>



                    <div class="notifications-list">


                        <div class="notification-item">


                            <div class="notification-icon info">

                                <i class="fa-solid fa-bell"></i>

                            </div>


                            <div class="notification-text">

                                <strong>Stay Updated</strong>

                                <p>
                                    Check your reported faults regularly
                                    to see whether their status has changed.
                                </p>

                            </div>


                        </div>


                    </div>


                </section>


            </div>



            <!-- ========================================
                 QUICK ACTIONS
            ========================================= -->

            <section class="quick-actions">


                <div class="quick-action-text">

                    <h3>
                        Need to report another problem?
                    </h3>


                    <p>
                        Submit a new maintenance request and we'll
                        take care of it.
                    </p>

                </div>



                <a href="report_fault.php" class="primary-button">

                    <i class="fa-solid fa-screwdriver-wrench"></i>

                    Report a Fault

                </a>


            </section>



        </section>


    </main>


</div>


</body>

</html>


<?php
mysqli_close($conn);
