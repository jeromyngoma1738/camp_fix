<?php
session_start();
$error = [];
$username = '';

// Where each role lands after login
$redirects = [
    'admin' => '../admin/dashboard.php',
    'technician' => '../technician/dashboard.php',
    'student' => '../user/user_dashboard.php',
];

// Already logged in? Send them straight to their dashboard
if (isset($_SESSION['user_id'], $_SESSION['role'], $redirects[$_SESSION['role']])) {
    header('Location: '.$redirects[$_SESSION['role']]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Only trim the username. Never alter the password: it must be checked exactly as typed.
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '') {
        $error['username'] = 'Username is required.';
    }
    if ($password === '') {
        $error['password'] = 'Password is required.';
    }

    if (empty($error)) {
        require_once __DIR__.'/../config/database.php';

        $sql = 'SELECT id, username, firstname, user_password, role FROM users WHERE username = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['user_password'])) {
            $role = strtolower(trim($user['role']));

            if (isset($redirects[$role])) {
                session_regenerate_id(true); // prevents session fixation

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['name'] = $user['firstname'];
                $_SESSION['role'] = $role;

                header('Location: '.$redirects[$role]);
                exit;
            }

            // Unknown or empty role: don't let them in
            $error['login'] = 'Your account has no valid role. Contact the administrator.';
        } else {
            $error['login'] = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | CBU Maintenance System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="auth.css">
</head>

<body>

<div class="auth-container">

    <div class="auth-left">
        <div class="auth-card">

            <div class="brand">
                <div class="brand-icon">
                    <i class="fas fa-tools"></i>
                </div>

                <div class="brand-text">
                    <h1>CampusFix</h1>
                    <p>CBU Maintenance System</p>
                </div>
            </div>

            <div class="welcome">
                <h2>Welcome Back</h2>
                <p>Sign in to report and track campus maintenance faults.</p>
            </div>

            <?php if (!empty($error)) { ?>
                <div class="alert error">
                    <?php foreach ($error as $message) { ?>
                        <p><?php echo htmlspecialchars($message); ?></p>
                    <?php } ?>
                </div>
            <?php } ?>

            <form action="login.php" method="POST" class="auth-form">

                <div class="form-group">
                    <label for="username">Email Address</label>
                    <input type="email" id="username" name="username" placeholder="Enter email address"
                           value="<?php echo htmlspecialchars($username); ?>" required>
                </div>

                <div class="form-group">

                    <div class="password-row">
                        <label for="password">Password</label>
                        <a href="#" class="forgot-password">Forgot password?</a>
                    </div>

                    <input type="password" id="password" name="password" placeholder="Enter password" required>

                </div>

                <button type="submit" name="login" class="btn-primary">Sign In</button>

            </form>

            <div class="auth-footer">
                Don't have an account?
                <a href="register.php">Create an account</a>
            </div>

        </div>
    </div>

</div>

</body>
</html>