<?php

session_start();
require_once __DIR__.'/../config/database.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Faults | CampusFix</title>

    <link rel="stylesheet" href="fault.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

    <div class="dashboard">

        <!-- Sidebar -->
        <aside class="sidebar">

            <div class="logo">

                <div class="logo-icon">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>

                <div class="logo-text">
                    <h2>CampusFix</h2>
                    <span>CBU Maintenance</span>
                </div>

            </div>


            <nav class="sidebar-nav">

                <a href="dashboard.php">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>

                <a href="faults.php" class="active">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Faults</span>
                </a>

                <a href="report_fault.php">
                    <i class="fa-solid fa-circle-plus"></i>
                    <span>Report Fault</span>
                </a>

                <a href="categories.php">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>Categories</span>
                </a>

                <a href="profile.php">
                    <i class="fa-solid fa-user"></i>
                    <span>My Profile</span>
                </a>

            </nav>


            <div class="sidebar-bottom">

                <a href="settings.php">
                    <i class="fa-solid fa-gear"></i>
                    <span>Settings</span>
                </a>

                <a href="logout.php" class="logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </a>

            </div>

        </aside>


        <!-- Main Content -->
        <main class="main-content">

            <!-- Top Bar -->
            <header class="topbar">

                <div class="page-heading">

                    <button class="mobile-menu">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <div>
                        <h1>Fault Reports</h1>
                        <p>Monitor and manage campus maintenance issues.</p>
                    </div>

                </div>


                <div class="topbar-right">

                    <button class="notification">
                        <i class="fa-regular fa-bell"></i>
                        <span>3</span>
                    </button>


                    <div class="profile">

                        <div class="profile-avatar">
                            J
                        </div>

                        <div class="profile-info">
                            <strong>Jeromy Ngoma</strong>
                            <span>Student</span>
                        </div>

                        <i class="fa-solid fa-chevron-down"></i>

                    </div>

                </div>

            </header>


            <!-- Page Content -->
            <section class="content">


                <!-- Page Actions -->
                <div class="page-actions">

                    <div>
                        <h2>Maintenance Faults</h2>
                        <p>View and track reported faults around campus.</p>
                    </div>

                    <a href="report_fault.php" class="report-button">
                        <i class="fa-solid fa-plus"></i>
                        Report New Fault
                    </a>

                </div>


                <!-- Statistics -->
                <div class="statistics">

                    <div class="stat-card">

                        <div class="stat-icon total">
                            <i class="fa-solid fa-clipboard-list"></i>
                        </div>

                        <div class="stat-content">
                            <span>Total Faults</span>
                            <h2>44</h2>
                            <small>
                                <i class="fa-solid fa-arrow-up"></i>
                                12% this month
                            </small>
                        </div>

                    </div>


                    <div class="stat-card">

                        <div class="stat-icon pending">
                            <i class="fa-solid fa-clock"></i>
                        </div>

                        <div class="stat-content">
                            <span>Pending</span>
                            <h2>12</h2>
                            <small>Awaiting action</small>
                        </div>

                    </div>


                    <div class="stat-card">

                        <div class="stat-icon progress">
                            <i class="fa-solid fa-spinner"></i>
                        </div>

                        <div class="stat-content">
                            <span>In Progress</span>
                            <h2>8</h2>
                            <small>Currently being fixed</small>
                        </div>

                    </div>


                    <div class="stat-card">

                        <div class="stat-icon resolved">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>

                        <div class="stat-content">
                            <span>Resolved</span>
                            <h2>24</h2>
                            <small>Successfully completed</small>
                        </div>

                    </div>

                </div>


                <!-- Fault Table Card -->
                <div class="fault-panel">

                    <div class="panel-header">

                        <div>
                            <h2>All Fault Reports</h2>
                            <p>Recent maintenance requests submitted by students.</p>
                        </div>

                    </div>


                    <!-- Search and Filters -->
                    <div class="filters">

                        <div class="search-box">

                            <i class="fa-solid fa-magnifying-glass"></i>

                            <input
                                type="text"
                                placeholder="Search fault reports..."
                            >

                        </div>


                        <select>

                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="progress">In Progress</option>
                            <option value="resolved">Resolved</option>

                        </select>


                        <select>

                            <option value="">All Categories</option>
                            <option value="electrical">Electrical</option>
                            <option value="plumbing">Plumbing</option>
                            <option value="ict">ICT</option>
                            <option value="furniture">Furniture</option>
                            <option value="other">Other</option>

                        </select>


                        <button class="filter-button">
                            <i class="fa-solid fa-filter"></i>
                            Filter
                        </button>

                    </div>


                    <!-- Fault Table -->
                    <div class="table-container">

                        <table>

                            <thead>

                                <tr>
                                    <th>Ticket</th>
                                    <th>Fault Details</th>
                                    <th>Location</th>
                                    <th>Category</th>
                                    <th>Reported</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>

                            </thead>


                            <tbody>

                                <!-- Fault 1 -->
                                <tr>

                                    <td>
                                        <span class="ticket-number">
                                            #CF-00124
                                        </span>
                                    </td>


                                    <td>

                                        <div class="fault-details">

                                            <div class="fault-icon electrical">
                                                <i class="fa-solid fa-bolt"></i>
                                            </div>

                                            <div>
                                                <strong>
                                                    Broken electrical socket
                                                </strong>

                                                <span>
                                                    Room 204 requires repair
                                                </span>
                                            </div>

                                        </div>

                                    </td>


                                    <td>

                                        <div class="location">
                                            <i class="fa-solid fa-location-dot"></i>
                                            ICT Block
                                        </div>

                                    </td>


                                    <td>

                                        <span class="category-badge">
                                            Electrical
                                        </span>

                                    </td>


                                    <td>
                                        <span class="date">
                                            05 Oct 2026
                                        </span>
                                    </td>


                                    <td>

                                        <span class="status pending">

                                            <span class="status-dot"></span>

                                            Pending

                                        </span>

                                    </td>


                                    <td>

                                        <a href="view_fault.php"
                                           class="view-button">
                                            View
                                        </a>

                                    </td>

                                </tr>


                                <!-- Fault 2 -->
                                <tr>

                                    <td>
                                        <span class="ticket-number">
                                            #CF-00123
                                        </span>
                                    </td>


                                    <td>

                                        <div class="fault-details">

                                            <div class="fault-icon plumbing">
                                                <i class="fa-solid fa-droplet"></i>
                                            </div>

                                            <div>
                                                <strong>
                                                    Water pipe leaking
                                                </strong>

                                                <span>
                                                    Continuous water leakage
                                                </span>
                                            </div>

                                        </div>

                                    </td>


                                    <td>

                                        <div class="location">
                                            <i class="fa-solid fa-location-dot"></i>
                                            Riverside Hostel
                                        </div>

                                    </td>


                                    <td>

                                        <span class="category-badge">
                                            Plumbing
                                        </span>

                                    </td>


                                    <td>
                                        <span class="date">
                                            04 Oct 2026
                                        </span>
                                    </td>


                                    <td>

                                        <span class="status progress">

                                            <span class="status-dot"></span>

                                            In Progress

                                        </span>

                                    </td>


                                    <td>

                                        <a href="view_fault.php"
                                           class="view-button">
                                            View
                                        </a>

                                    </td>

                                </tr>


                                <!-- Fault 3 -->
                                <tr>

                                    <td>
                                        <span class="ticket-number">
                                            #CF-00122
                                        </span>
                                    </td>


                                    <td>

                                        <div class="fault-details">

                                            <div class="fault-icon ict">
                                                <i class="fa-solid fa-wifi"></i>
                                            </div>

                                            <div>
                                                <strong>
                                                    Network connection issue
                                                </strong>

                                                <span>
                                                    No internet connection
                                                </span>
                                            </div>

                                        </div>

                                    </td>


                                    <td>

                                        <div class="location">
                                            <i class="fa-solid fa-location-dot"></i>
                                            Computer Lab
                                        </div>

                                    </td>


                                    <td>

                                        <span class="category-badge">
                                            ICT
                                        </span>

                                    </td>


                                    <td>
                                        <span class="date">
                                            03 Oct 2026
                                        </span>
                                    </td>


                                    <td>

                                        <span class="status resolved">

                                            <span class="status-dot"></span>

                                            Resolved

                                        </span>

                                    </td>


                                    <td>

                                        <a href="view_fault.php" class="view-button">
                                            View
                                        </a>

                                    </td>

                                </tr>


                                <!-- Fault 4 -->
                                <tr>

                                    <td>
                                        <span class="ticket-number">
                                            #CF-00121
                                        </span>
                                    </td>


                                    <td>

                                        <div class="fault-details">

                                            <div class="fault-icon furniture">
                                                <i class="fa-solid fa-chair"></i>
                                            </div>

                                            <div>
                                                <strong>
                                                    Broken classroom desk
                                                </strong>

                                                <span>
                                                    Desk requires replacement
                                                </span>
                                            </div>

                                        </div>

                                    </td>


                                    <td>

                                        <div class="location">
                                            <i class="fa-solid fa-location-dot"></i>
                                            Main Lecture Theatre
                                        </div>

                                    </td>


                                    <td>

                                        <span class="category-badge">
                                            Furniture
                                        </span>

                                    </td>


                                    <td>
                                        <span class="date">
                                            02 Oct 2026
                                        </span>
                                    </td>


                                    <td>

                                        <span class="status pending">

                                            <span class="status-dot"></span>

                                            Pending

                                        </span>

                                    </td>


                                    <td>

                                        <a href="view_fault.php"
                                           class="view-button">
                                            View
                                        </a>

                                    </td>

                                </tr>


                            </tbody>

                        </table>

                    </div>


                    <!-- Pagination -->
                    <div class="pagination">

                        <span>
                            Showing 1–4 of 44 faults
                        </span>

                        <div class="pagination-buttons">

                            <button disabled>
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>

                            <button class="active">1</button>

                            <button>2</button>

                            <button>3</button>

                            <button>4</button>

                            <button>...</button>

                            <button>11</button>

                            <button>
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>

                        </div>

                    </div>

                </div>

            </section>

        </main>

    </div>

</body>

</html>
