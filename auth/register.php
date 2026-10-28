<?php

function clean($var)
{
    $var = trim($var);
    $var = stripslashes($var);
    $var = htmlspecialchars($var);

    return $var;
}

$error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = clean($_POST['first_name'] ?? '');
    $surname = clean($_POST['surname'] ?? '');
    $username = clean($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    $tel = clean($_POST['contact'] ?? '');

    if ($first_name === '') {
        $error['first_name'] = 'First name should not be empty.';
    }

    if ($surname === '') {
        $error['surname'] = 'Surname should not be empty.';
    }

    if ($tel === '') {
        $error['contact'] = 'Contact number should not be empty.';
    }

    if ($username === '') {
        $error['username'] = 'Email address is required.';
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $error['username'] = 'Invalid email address.';
    }

    if ($password === '') {
        $error['password'] = 'Password should not be empty.';
    } elseif (strlen($password) < 8) {
        $error['password'] = 'Password should not be less than 8 characters.';
    }

    if ($confirm === '') {
        $error['confirm'] = 'Please confirm your password.';
    } elseif ($password !== $confirm) {
        $error['confirm'] = 'Passwords do not match.';
    }

    if (empty($error)) {
        require_once __DIR__.'/../config/database.php';

        // Check if username already exists
        $check_sql = 'SELECT id FROM users WHERE username = ?';
        $check_stmt = mysqli_prepare($conn, $check_sql);

        if (!$check_stmt) {
            exit('Prepare failed: '.mysqli_error($conn));
        }

        mysqli_stmt_bind_param($check_stmt, 's', $username);
        mysqli_stmt_execute($check_stmt);

        $result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($result) > 0) {
            $error['username'] = 'Username already exists.';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Every new registration is a student
            $role = 'student';

            // Insert user
            $sql = 'INSERT INTO users
                    (username, firstname, surname, user_password, contact, role)
                    VALUES (?, ?, ?, ?, ?, ?)';

            $stmt = mysqli_prepare($conn, $sql);

            if (!$stmt) {
                exit('Insert prepare failed: '.mysqli_error($conn));
            }

            mysqli_stmt_bind_param($stmt, 'ssssss',
                $username,
                $first_name,
                $surname,
                $hashed_password,
                $tel,
                $role
            );

            if (mysqli_stmt_execute($stmt)) {
                header('Location: login.php?registered=success');
                exit;
            } else {
                $error['database'] = 'Registration failed. Please try again.';
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($check_stmt);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | CBU Maintenance System</title>
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
                <h2>Create Account</h2>
                <p>Register to report and track campus maintenance faults.</p>
            </div>

            <form action="register.php" method="POST" class="auth-form">

                <div class="form-row">

                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" placeholder="Enter first name" required>
                    </div>

                    <div class="form-group">
                        <label for="surname">Surname</label>
                        <input type="text" id="surname" name="surname" placeholder="Enter surname" required>
                    </div>

                </div>

                <div class="form-group">
                    <label for="contact">Contact Number</label>
                    <input type="tel" id="contact" name="contact" placeholder="Enter contact number" required>
                </div>

                <div class="form-group">
                    <label for="username">Email Address</label>
                    <input type="email" id="username" name="username" placeholder="Enter email address" required>
                </div>

                <div class="form-row">

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Create password" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm">Confirm Password</label>
                        <input type="password" id="confirm" name="confirm" placeholder="Confirm password" required>
                    </div>

                </div>

                <button type="submit" name="register" class="btn-primary">Create Account</button>

            </form>

            <div class="auth-footer">
                Already have an account?
                <a href="login.php">Sign in</a>
            </div>

        </div>
    </div>


</body>
</html>