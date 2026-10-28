
<?php

require_once __DIR__.'/../config/database.php';
function updateRole($conn)
{
    $updateRole = $_POST['role'] ?? '';
    $username = $_POST['username'] ?? '';

    if ($updateRole === '' || $username === '') {
        return false;
    }

    $sql = 'UPDATE users
            SET role = ?
            WHERE username = ?';

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        exit('Prepare failed: '.mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        'ss',
        $updateRole,
        $username
    );

    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $success;
}

/*
|--------------------------------------------------------------------------
| HANDLE ROLE UPDATE
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_role'])) {
        if (updateRole($conn)) {
            header('Location: user.php?role_updated=success');
            exit;
        } else {
            $error = 'Role update failed.';
        }
    }
}

/*
|--------------------------------------------------------------------------
| SELECT ALL USERS
|--------------------------------------------------------------------------
*/

$sql = 'SELECT id, username, firstname, surname, contact, role, created_at
        FROM users
        ORDER BY id DESC';

$result = mysqli_query($conn, $sql);

if (!$result) {
    exit('Query failed: '.mysqli_error($conn));
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Users | CampusFix</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

<div class="dashboard">

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

        <nav class="sidebar-nav">

            <div class="nav-section">

                <span class="nav-title">MAIN</span>

                <a href="dashboard.php" class="nav-link">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>

                <a href="faults.php" class="nav-link">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Faults</span>
                </a>

                <a href="students.php" class="nav-link active">
                    <i class="fa-solid fa-users"></i>
                    <span>Students</span>
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

        </nav>

    </aside>


    <main class="main-content">

        <header class="topbar">

            <div class="topbar-left">

                <button class="menu-toggle" type="button">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>

                    <input
                        type="search"
                        placeholder="Search students..."
                    >
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


        <section class="content">

            <div class="page-header">

                <div class="page-heading">

                    <span class="page-label">
                        USER MANAGEMENT
                    </span>

                    <h1>Students</h1>

                    <p>
                        View and manage all students registered in the CampusFix system.
                    </p>

                </div>

            </div>


            <div class="dashboard-card">

                <div class="card-header">

                    <div>

                        <h3>Registered Students</h3>

                        <p>
                            All students currently registered in the system
                        </p>

                    </div>

                    <div>

                        <strong>
                            <?php echo mysqli_num_rows($result); ?>
                        </strong>

                        <span> Students</span>

                    </div>

                </div>


                <div class="table-container">

                    <table>

                        <thead>

                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Date Registered</th>
                                <th>Action</th>
                            </tr>

                        </thead>

                        <tbody>

                        <?php if (mysqli_num_rows($result) > 0) { ?>

                            <?php $number = 1; ?>

                            <?php while ($student = mysqli_fetch_assoc($result)) { ?>

                                <tr>

                                    <td>
                                        <?php echo $number++; ?>
                                    </td>

                                    <td>

                                        <div class="technician">

                                            <div class="technician-avatar">
                                                <?php
                                                echo strtoupper(
                                                    substr($student['firstname'], 0, 1)
                                                );
                                ?>
                                            </div>

                                            <div class="technician-info">

                                                <strong>
                                                    <?php
                                    echo htmlspecialchars(
                                        $student['firstname'].' '.$student['surname']
                                    );
                                ?>
                                                </strong>

                                                <span>
                                                    Student
                                                </span>

                                            </div>

                                        </div>

                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars($student['username']);
                                ?>
                                    </td>

                                    <td>
                                        <?php
                                echo htmlspecialchars($student['contact']);
                                ?>
                                    </td>

                                    <td>
                                        <?php
                                echo date('d M Y', strtotime($student['created_at']));
                                ?>
                                    </td>

                                    <td>       
                                        <form action="user.php" method="POST">
                                            <input type="hidden" name="username" value="<?php echo htmlspecialchars($student['username']); ?>" >

                                            <select name="role" required>
                                                <option value="">Update Role</option>
                                                <option value="student">Student </option>
                                                <option value="technician"> Technician </option>
                                                <option value="admin">Admin </option>
                                            </select>
                                            <button type="submit" name="update_role"> Update</button>
                                        </form>
                                    </td>
                                </tr>

                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="6" style="text-align:center; padding:30px;">
                                    <i class="fa-solid fa-users"></i>
                                    <p> No students are currently registered. </p>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</div>

</body>
</html>

<?php
mysqli_free_result($result);
mysqli_close($conn);
?>

