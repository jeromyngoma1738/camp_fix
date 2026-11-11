
<?php
session_start();
require_once __DIR__.'/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = 'SELECT id, username, firstname, surname, username, contact, role, created_at
        FROM users
        WHERE id = ?';

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    exit('User account not found.');
}

$initials = strtoupper(substr($user['firstname'], 0, 1).substr($user['surname'], 0, 1));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | CampusFix</title>
    <link rel="stylesheet" href="profile.css">
</head>

<body>

<div class="profile-container">

    <div class="profile-header">
        <h2>My Profile</h2>
        <p>Manage your personal information and account settings.</p>
    </div>

    <div class="profile-card">

        <div class="profile-top">

            <div class="avatar">
                <?php echo htmlspecialchars($initials); ?>
            </div>

            <div class="profile-details">
                <h2>
                    <?php echo htmlspecialchars($user['firstname'].' '.$user['surname']); ?>
                </h2>

                <p>@<?php echo htmlspecialchars($user['username']); ?></p>

                <span class="role">
                    <?php echo htmlspecialchars($user['role']); ?>
                </span>
            </div>
            
            <a href="user_dashboard.php"><button class="edit-btn"> Dashboard </button></a>

        </div>

        <hr>

        <form action="update_profile.php" method="POST">

            <input type="hidden" name="user_id"
                   value="<?php echo $user['id']; ?>">

            <h3>Personal Information</h3>

            <div class="form-grid">

                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="firstname"
                           value="<?php echo htmlspecialchars($user['firstname']); ?>"
                           required disabled>
                </div>

                <div class="form-group">
                    <label>Surname</label>
                    <input type="text" name="surname"
                           value="<?php echo htmlspecialchars($user['surname']); ?>"
                           required disabled>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username"
                           value="<?php echo htmlspecialchars($user['username']); ?>"
                           required disabled>
                </div>


                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="contact"
                           value="<?php echo htmlspecialchars($user['contact']); ?>"
                           disabled>
                </div>

            </div>

            <h3>Account Information</h3>

            <div class="account-info">

                <div>
                    <span>Account ID</span>
                    <strong>#<?php echo $user['id']; ?></strong>
                </div>

                <div>
                    <span>Account Role</span>
                    <strong><?php echo htmlspecialchars($user['role']); ?></strong>
                </div>

                <div>
                    <span>Date Joined</span>
                    <strong>
                        <?php echo date('d M Y', strtotime($user['created_at'])); ?>
                    </strong>
                </div>

            </div>

            <div class="form-actions" id="formActions">

                <button type="submit" class="save-btn">
                    Save Changes
                </button>

                <button type="button" class="cancel-btn"
                        onclick="location.reload()">
                    Cancel
                </button>

            </div>

        </form>

    </div>

</div>

<script>
function enableEditing() {

    const inputs = document.querySelectorAll(
        'input:not([type="hidden"])'
    );

    inputs.forEach(input => {
        input.disabled = false;
    });

    document.getElementById('formActions').style.display = 'flex';
}
</script>

</body>
</html>
