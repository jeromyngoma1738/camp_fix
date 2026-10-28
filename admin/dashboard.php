<?php

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | CampusFix</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

<div class="dashboard">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="sidebar-brand">

            <div class="brand-icon">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>

            <div class="brand-text">
                <h2>CampusFix</h2>
                <span>Maintenance System</span>
            </div>

        </div>


        <!-- NAVIGATION -->
        <nav class="sidebar-nav">

            <div class="nav-section">

                <span class="nav-title">MAIN</span>

                <a href="dashboard.php" class="nav-link active">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>

                <a href="faults.php" class="nav-link">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Faults</span>
                    <span class="nav-badge">24</span>
                </a>

                <a href="users.php" class="nav-link">
                    <i class="fa-solid fa-users"></i>
                    <span>Users</span>
                </a>

                <a href="technicians.php" class="nav-link">
                    <i class="fa-solid fa-user-gear"></i>
                    <span>Technicians</span>
                </a>

            </div>


            <div class="nav-section">

                <span class="nav-title">MANAGEMENT</span>

                <a href="categories.php" class="nav-link">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>Categories</span>
                </a>

                <a href="locations.php" class="nav-link">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>Locations</span>
                </a>

                <a href="reports.php" class="nav-link">
                    <i class="fa-solid fa-file-lines"></i>
                    <span>Reports</span>
                </a>

            </div>


            <div class="nav-section">

                <span class="nav-title">SYSTEM</span>

                <a href="notifications.php" class="nav-link">
                    <i class="fa-solid fa-bell"></i>
                    <span>Notifications</span>
                    <span class="nav-badge danger">5</span>
                </a>

                <a href="settings.php" class="nav-link">
                    <i class="fa-solid fa-gear"></i>
                    <span>Settings</span>
                </a>

            </div>

        </nav>


        <!-- ADMIN PROFILE -->
        <div class="sidebar-user">

            <div class="user-avatar">
                A
            </div>

            <div class="user-details">
                <strong>Administrator</strong>
                <span>System Admin</span>
            </div>

            <a href="../auth/logout.php" class="logout-icon">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>

        </div>

    </aside>


    <!-- MAIN CONTENT -->
    <main class="main-content">


        <!-- TOP BAR -->
        <header class="topbar">

            <div class="topbar-left">

                <button class="menu-toggle" type="button">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="search-box">

                    <i class="fa-solid fa-magnifying-glass"></i>

                    <input type="search" placeholder="Search faults, users, technicians...">

                </div>

            </div>


            <div class="topbar-right">

                <button class="notification-button" type="button">

                    <i class="fa-regular fa-bell"></i>

                    <span class="notification-count">5</span>

                </button>


                <div class="topbar-divider"></div>


                <div class="profile">

                    <div class="profile-avatar">
                        A
                    </div>

                    <div class="profile-info">
                        <strong>Administrator</strong>
                        <span>System Admin</span>
                    </div>

                    <i class="fa-solid fa-chevron-down"></i>

                </div>

            </div>

        </header>


        <!-- PAGE CONTENT -->
        <section class="content">


            <!-- PAGE HEADER -->
            <div class="page-header">

                <div class="page-heading">

                    <span class="page-label">
                        OVERVIEW
                    </span>

                    <h1>Dashboard</h1>

                    <p>
                        Welcome back, Administrator. Here's what's happening
                        across campus today.
                    </p>

                </div>


                <div class="page-actions">

                    <button type="button" class="btn-secondary">
                        <i class="fa-solid fa-download"></i>
                        Export Report
                    </button>

                    <a href="faults.php" class="btn-primary">
                        <i class="fa-solid fa-eye"></i>
                        View Faults
                    </a>

                </div>

            </div>


            <!-- STATISTICS -->
            <div class="stats-grid">


                <!-- TOTAL FAULTS -->
                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon green">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                        </div>

                        <span class="stat-menu">
                            <i class="fa-solid fa-ellipsis"></i>
                        </span>

                    </div>

                    <div class="stat-content">

                        <span class="stat-title">
                            Total Faults
                        </span>

                        <h2>128</h2>

                        <div class="stat-footer positive">

                            <i class="fa-solid fa-arrow-trend-up"></i>

                            <span>12.5%</span>

                            <small>from last month</small>

                        </div>

                    </div>

                </div>


                <!-- PENDING -->
                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon orange">
                            <i class="fa-solid fa-clock"></i>
                        </div>

                        <span class="stat-menu">
                            <i class="fa-solid fa-ellipsis"></i>
                        </span>

                    </div>

                    <div class="stat-content">

                        <span class="stat-title">
                            Pending Faults
                        </span>

                        <h2>24</h2>

                        <div class="stat-footer warning">

                            <i class="fa-solid fa-circle-exclamation"></i>

                            <span>Requires attention</span>

                        </div>

                    </div>

                </div>


                <!-- IN PROGRESS -->
                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon blue">
                            <i class="fa-solid fa-spinner"></i>
                        </div>

                        <span class="stat-menu">
                            <i class="fa-solid fa-ellipsis"></i>
                        </span>

                    </div>

                    <div class="stat-content">

                        <span class="stat-title">
                            In Progress
                        </span>

                        <h2>15</h2>

                        <div class="stat-footer">

                            <i class="fa-solid fa-wrench"></i>

                            <span>Currently being handled</span>

                        </div>

                    </div>

                </div>


                <!-- RESOLVED -->
                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon purple">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>

                        <span class="stat-menu">
                            <i class="fa-solid fa-ellipsis"></i>
                        </span>

                    </div>

                    <div class="stat-content">

                        <span class="stat-title">
                            Resolved
                        </span>

                        <h2>89</h2>

                        <div class="stat-footer positive">

                            <i class="fa-solid fa-arrow-trend-up"></i>

                            <span>8.2%</span>

                            <small>this month</small>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ANALYTICS ROW -->
            <div class="dashboard-grid">


                <!-- FAULT ANALYTICS -->
                <div class="dashboard-card analytics-card">

                    <div class="card-header">

                        <div>

                            <h3>Fault Analytics</h3>

                            <p>
                                Maintenance requests over time
                            </p>

                        </div>

                        <select class="chart-filter">

                            <option>Last 6 months</option>
                            <option>Last 12 months</option>
                            <option>This year</option>

                        </select>

                    </div>


                    <div class="chart-area">

                        <div class="chart-placeholder">

                            <i class="fa-solid fa-chart-line"></i>

                            <span>
                                Fault analytics chart
                            </span>

                        </div>

                    </div>

                </div>


                <!-- FAULT STATUS -->
                <div class="dashboard-card status-card">

                    <div class="card-header">

                        <div>

                            <h3>Fault Status</h3>

                            <p>
                                Current maintenance status
                            </p>

                        </div>

                    </div>


                    <div class="status-content">

                        <div class="donut-placeholder">

                            <span>
                                128
                            </span>

                            <small>
                                Total
                            </small>

                        </div>


                        <div class="status-list">


                            <div class="status-item">

                                <div class="status-name">

                                    <span class="status-dot reported"></span>

                                    <span>Reported</span>

                                </div>

                                <strong>24</strong>

                            </div>


                            <div class="status-item">

                                <div class="status-name">

                                    <span class="status-dot assigned"></span>

                                    <span>Assigned</span>

                                </div>

                                <strong>18</strong>

                            </div>


                            <div class="status-item">

                                <div class="status-name">

                                    <span class="status-dot progress"></span>

                                    <span>In Progress</span>

                                </div>

                                <strong>15</strong>

                            </div>


                            <div class="status-item">

                                <div class="status-name">

                                    <span class="status-dot resolved"></span>

                                    <span>Resolved</span>

                                </div>

                                <strong>71</strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- LOWER SECTION -->
            <div class="dashboard-grid lower-grid">


                <!-- RECENT FAULTS -->
                <div class="dashboard-card recent-faults">

                    <div class="card-header">

                        <div>

                            <h3>Recent Faults</h3>

                            <p>
                                Latest maintenance requests
                            </p>

                        </div>

                        <a href="faults.php" class="view-link">
                            View all
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>

                    </div>


                    <div class="table-container">

                        <table>

                            <thead>

                                <tr>
                                    <th>Ticket</th>
                                    <th>Category</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>

                            </thead>


                            <tbody>


                                <tr>

                                    <td>
                                        <strong>#CF-1024</strong>
                                    </td>

                                    <td>
                                        Electrical
                                    </td>

                                    <td>
                                        CBU Main Library
                                    </td>

                                    <td>
                                        <span class="status-badge pending">
                                            Pending
                                        </span>
                                    </td>

                                    <td>
                                        02 Oct 2026
                                    </td>

                                </tr>


                                <tr>

                                    <td>
                                        <strong>#CF-1023</strong>
                                    </td>

                                    <td>
                                        Plumbing
                                    </td>

                                    <td>
                                        Hostel Block A
                                    </td>

                                    <td>
                                        <span class="status-badge progress">
                                            In Progress
                                        </span>
                                    </td>

                                    <td>
                                        02 Oct 2026
                                    </td>

                                </tr>


                                <tr>

                                    <td>
                                        <strong>#CF-1022</strong>
                                    </td>

                                    <td>
                                        ICT / Network
                                    </td>

                                    <td>
                                        SICT Building
                                    </td>

                                    <td>
                                        <span class="status-badge assigned">
                                            Assigned
                                        </span>
                                    </td>

                                    <td>
                                        01 Oct 2026
                                    </td>

                                </tr>


                                <tr>

                                    <td>
                                        <strong>#CF-1021</strong>
                                    </td>

                                    <td>
                                        Furniture
                                    </td>

                                    <td>
                                        Lecture Block B
                                    </td>

                                    <td>
                                        <span class="status-badge resolved">
                                            Resolved
                                        </span>
                                    </td>

                                    <td>
                                        01 Oct 2026
                                    </td>

                                </tr>


                            </tbody>

                        </table>

                    </div>

                </div>


                <!-- RECENT ACTIVITY -->
                <div class="dashboard-card activity-card">

                    <div class="card-header">

                        <div>

                            <h3>Recent Activity</h3>

                            <p>
                                Latest system activity
                            </p>

                        </div>

                        <a href="notifications.php" class="view-link">
                            View all
                        </a>

                    </div>


                    <div class="activity-list">


                        <div class="activity-item">

                            <div class="activity-icon green">

                                <i class="fa-solid fa-plus"></i>

                            </div>

                            <div class="activity-details">

                                <strong>
                                    New fault reported
                                </strong>

                                <p>
                                    Electrical fault at Main Library
                                </p>

                                <span>
                                    5 minutes ago
                                </span>

                            </div>

                        </div>


                        <div class="activity-item">

                            <div class="activity-icon blue">

                                <i class="fa-solid fa-user-check"></i>

                            </div>

                            <div class="activity-details">

                                <strong>
                                    Fault assigned
                                </strong>

                                <p>
                                    #CF-1023 assigned to technician
                                </p>

                                <span>
                                    18 minutes ago
                                </span>

                            </div>

                        </div>


                        <div class="activity-item">

                            <div class="activity-icon purple">

                                <i class="fa-solid fa-check"></i>

                            </div>

                            <div class="activity-details">

                                <strong>
                                    Fault resolved
                                </strong>

                                <p>
                                    #CF-1021 marked as resolved
                                </p>

                                <span>
                                    32 minutes ago
                                </span>

                            </div>

                        </div>


                        <div class="activity-item">

                            <div class="activity-icon orange">

                                <i class="fa-solid fa-user-plus"></i>

                            </div>

                            <div class="activity-details">

                                <strong>
                                    New user registered
                                </strong>

                                <p>
                                    New student account created
                                </p>

                                <span>
                                    1 hour ago
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- TECHNICIAN PERFORMANCE -->
            <div class="dashboard-card technician-card">

                <div class="card-header">

                    <div>

                        <h3>Technician Performance</h3>

                        <p>
                            Current maintenance workload
                        </p>

                    </div>

                    <a href="technicians.php" class="view-link">
                        Manage technicians
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>


                <div class="table-container">

                    <table>

                        <thead>

                            <tr>
                                <th>Technician</th>
                                <th>Assigned</th>
                                <th>In Progress</th>
                                <th>Completed</th>
                                <th>Workload</th>
                            </tr>

                        </thead>


                        <tbody>


                            <tr>

                                <td>

                                    <div class="technician">

                                        <div class="technician-avatar">
                                            JB
                                        </div>

                                        <div class="technician-info">

                                            <strong>
                                                John Banda
                                            </strong>

                                            <span>
                                                Electrical Technician
                                            </span>

                                        </div>

                                    </div>

                                </td>

                                <td>
                                    12
                                </td>

                                <td>
                                    3
                                </td>

                                <td>
                                    9
                                </td>

                                <td>

                                    <div class="progress-container">

                                        <div class="progress-bar">

                                            <span style="width: 75%;"></span>

                                        </div>

                                        <small>
                                            75%
                                        </small>

                                    </div>

                                </td>

                            </tr>


                            <tr>

                                <td>

                                    <div class="technician">

                                        <div class="technician-avatar">
                                            MP
                                        </div>

                                        <div class="technician-info">

                                            <strong>
                                                Mary Phiri
                                            </strong>

                                            <span>
                                                Plumbing Technician
                                            </span>

                                        </div>

                                    </div>

                                </td>

                                <td>
                                    8
                                </td>

                                <td>
                                    2
                                </td>

                                <td>
                                    6
                                </td>

                                <td>

                                    <div class="progress-container">

                                        <div class="progress-bar">

                                            <span style="width: 55%;"></span>

                                        </div>

                                        <small>
                                            55%
                                        </small>

                                    </div>

                                </td>

                            </tr>


                        </tbody>

                    </table>

                </div>

            </div>


        </section>

    </main>

</div>

</body>
</html>

