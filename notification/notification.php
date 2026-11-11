
<?php
session_start();

require_once __DIR__.'/../config/database.php';

// Check user session
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Retrieve user notifications
$sql = 'SELECT * FROM notification WHERE user_id = ? ORDER BY created_at DESC';

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, 'i', $user_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$notificationCount = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Notifications | CampusFix</title>

    <link rel="stylesheet" href="notification.css">
</head>

<body>

<div class="notification-container">

    <div class="notification-header">

        <div>
            <h2>Notifications</h2>
            <p>Stay updated on your reported maintenance faults.</p>
        </div>

        <span class="notification-count">
            <?php echo $notificationCount; ?> Notifications
        </span>

    </div>

    <div class="notification-card">

        <div class="card-heading">
            <h3>Recent Notifications</h3>
        </div>

        <?php if ($notificationCount > 0) { ?>

            <?php while ($notification = mysqli_fetch_assoc($result)) { ?>

                <div class="notification-item 
                    <?php echo $notification['is_read'] == 0 ? 'unread' : ''; ?>">

                    <div class="notification-icon">
                        <span>!</span>
                    </div>

                    <div class="notification-content">

                        <p>
                            <?php echo htmlspecialchars($notification['message']); ?>
                        </p>

                        <span class="notification-date">
                            <?php
                            echo date(
                                'd M Y, h:i A',
                                strtotime($notification['created_at'])
                            );
                ?>
                        </span>

                    </div>

                    <?php if ($notification['is_read'] == 0) { ?>
                        <span class="status">New</span>
                    <?php } ?>

                </div>

            <?php } ?>

        <?php } else { ?>

            <div class="empty-notifications">

                <div class="empty-icon">!</div>

                <h3>No Notifications Yet</h3>

                <p>
                    You currently have no notifications.
                    Updates about your reported faults will appear here.
                </p>

            </div>

        <?php } ?>

    </div>

</div>

</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
