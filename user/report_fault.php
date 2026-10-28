<?php
session_start();
require_once __DIR__.'/../config/database.php';

// Make every mysqli failure throw an exception so we can catch it and show a message
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Set to false before going live. true = show the real MySQL error on the page.
const DEBUG = true;

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
$userId = (int) $_SESSION['user_id'];

$errors = [];
$old = ['roomNo' => '', 'category' => '', 'priority' => '', 'description' => ''];

$categories = ['Electrical', 'Plumbing', 'Carpentry', 'Masonry', 'Painting', 'Air Conditioning',
    'ICT', 'Furniture', 'Cleaning', 'Water Supply', 'Security', 'General Maintenance'];
$priorities = ['Low', 'Medium', 'High', 'Urgent'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['roomNo'] = trim($_POST['roomNo'] ?? '');
    $old['category'] = $_POST['category'] ?? '';
    $old['priority'] = $_POST['priority'] ?? '';
    $old['description'] = trim($_POST['description'] ?? '');

    if ($old['roomNo'] === '') {
        $errors['room'] = 'Room number is required.';
    }
    if (!in_array($old['category'], $categories, true)) {
        $errors['category'] = 'Please choose a valid category.';
    }
    if (!in_array($old['priority'], $priorities, true)) {
        $errors['priority'] = 'Please choose a valid priority.';
    }
    if ($old['description'] === '') {
        $errors['description'] = 'Description is required.';
    }

    // Optional image: validate first, save later
    $imageName = null;
    $upload = $_FILES['image'] ?? null;
    if ($upload && $upload['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($upload['error'] !== UPLOAD_ERR_OK) {
            $errors['image'] = 'Image upload failed.';
        } elseif ($upload['size'] > 5 * 1024 * 1024) {
            $errors['image'] = 'Image must be smaller than 5 MB.';
        } else {
            $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
            $mime = (new finfo(FILEINFO_MIME_TYPE))->file($upload['tmp_name']);
            if (!isset($allowed[$mime])) {
                $errors['image'] = 'Only JPG, PNG or WebP images are allowed.';
            } else {
                $imageName = bin2hex(random_bytes(16)).'.'.$allowed[$mime];
            }
        }
    }

    if (empty($errors)) {
        $savedPath = null;
        try {
            if ($imageName !== null) {
                $dir = __DIR__.'/../uploads/';
                if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
                    throw new RuntimeException('Could not create the uploads folder.');
                }
                $savedPath = $dir.$imageName;
                if (!move_uploaded_file($upload['tmp_name'], $savedPath)) {
                    $savedPath = null;
                    throw new RuntimeException('Could not save the uploaded image.');
                }
            }

            $conn->begin_transaction();

            // Temporary unique ticket number, replaced with the real one below
            $tmpTicket = 'TMP-'.bin2hex(random_bytes(6));
            $status = 'Pending';

            $stmt = $conn->prepare(
                'INSERT INTO faults (ticket_number, user_id, category, location, priority, fault_status, fault_description, image)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->bind_param(
                'sissssss',
                $tmpTicket, $userId, $old['category'], $old['roomNo'],
                $old['priority'], $status, $old['description'], $imageName
            );
            $stmt->execute();

            $id = $conn->insert_id;
            $ticket = sprintf('CF-%s-%04d', date('Y'), $id);

            $upd = $conn->prepare('UPDATE faults SET ticket_number = ? WHERE id = ?');
            $upd->bind_param('si', $ticket, $id);
            $upd->execute();

            $conn->commit();

            header('Location: report_fault.php?success='.urlencode($ticket));
            exit;
        } catch (Throwable $e) {
            try {
                $conn->rollback();
            } catch (Throwable $ignore) {
            }
            if ($savedPath !== null && file_exists($savedPath)) {
                unlink($savedPath);
            }
            error_log('CampusFix report_fault error: '.$e->getMessage());

            $errors['db'] = 'An error has occurred while sending your report to the database. Please try again.';
            if (DEBUG) {
                $errors['db_debug'] = 'Debug: '.$e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="report_fault.css">
    <title>Report a Fault | CampusFix</title>
</head>
<body>

    <div class="page-wrapper">

        <div class="form-card">

            <div class="card-header">
                <div class="icon">+</div>
                <h2>Report a Fault</h2>
                <p>Let us know about a maintenance issue on campus.</p>
            </div>

            <form action="report_fault.php" method="POST" enctype="multipart/form-data">

                <?php if (!empty($errors)) { ?>
                    <div class="alert error">
                        <?php foreach ($errors as $e) { ?>
                            <p><?php echo htmlspecialchars($e); ?></p>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php if (isset($_GET['success'])) { ?>
                    <div class="alert success">
                        Report submitted. Your ticket number is <strong><?php echo htmlspecialchars($_GET['success']); ?></strong>.
                    </div>
                <?php } ?>

                <div class="form-group">
                    <label for="roomNo">Room Number / Location</label>
                    <input type="text" id="roomNo" name="roomNo"
                           placeholder="e.g. B117" value="<?php echo htmlspecialchars($old['roomNo']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="category">Fault Category</label>
                    <select name="category" id="category" required>
                        <option value="">Choose a Category</option>
                        <option value="Electrical" <?php echo $old['category'] === 'Electrical' ? 'selected' : ''; ?>>Electrical</option>
                        <option value="Plumbing" <?php echo $old['category'] === 'Plumbing' ? 'selected' : ''; ?>>Plumbing</option>
                        <option value="Carpentry" <?php echo $old['category'] === 'Carpentry' ? 'selected' : ''; ?>>Carpentry</option>
                        <option value="Masonry" <?php echo $old['category'] === 'Masonry' ? 'selected' : ''; ?>>Masonry</option>
                        <option value="Painting" <?php echo $old['category'] === 'Painting' ? 'selected' : ''; ?>>Painting</option>
                        <option value="Air Conditioning" <?php echo $old['category'] === 'Air Conditioning' ? 'selected' : ''; ?>>Air Conditioning</option>
                        <option value="ICT" <?php echo $old['category'] === 'ICT' ? 'selected' : ''; ?>>ICT / Computer Repairs</option>
                        <option value="Furniture" <?php echo $old['category'] === 'Furniture' ? 'selected' : ''; ?>>Furniture Repairs</option>
                        <option value="Cleaning" <?php echo $old['category'] === 'Cleaning' ? 'selected' : ''; ?>>Cleaning and Sanitation</option>
                        <option value="Water Supply" <?php echo $old['category'] === 'Water Supply' ? 'selected' : ''; ?>>Water Supply</option>
                        <option value="Security" <?php echo $old['category'] === 'Security' ? 'selected' : ''; ?>>Security Systems</option>
                        <option value="General Maintenance" <?php echo $old['category'] === 'General Maintenance' ? 'selected' : ''; ?>>General Maintenance</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="priority">Priority Level</label>
                    <select name="priority" id="priority" required>
                        <option value="">Choose Priority</option>
                        <option value="Low" <?php echo $old['priority'] === 'Low' ? 'selected' : ''; ?>>Low</option>
                        <option value="Medium" <?php echo $old['priority'] === 'Medium' ? 'selected' : ''; ?>>Medium</option>
                        <option value="High" <?php echo $old['priority'] === 'High' ? 'selected' : ''; ?>>High</option>
                        <option value="Urgent" <?php echo $old['priority'] === 'Urgent' ? 'selected' : ''; ?>>Urgent</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Fault Description</label>
                    <textarea name="description" id="description" placeholder="Describe the maintenance problem..." rows="5" required><?php echo htmlspecialchars($old['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="image">Attach Image <span>(Optional)</span></label>
                    <input type="file" name="image" id="image" accept="image/*">
                </div>

                <button type="submit" name="submit">
                    Submit Fault Report
                </button>

            </form>

            <div class="card-footer">
                CampusFix | Campus Maintenance System
            </div>

        </div>

    </div>

</body>
</html>

