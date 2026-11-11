<?php
require_once __DIR__.'/../config/auth_check.php';
requireRole('student');                       // only students may open this page
require_once __DIR__.'/../config/database.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

const DEBUG = true;                           // set to false before going live

$userId = (int) $_SESSION['user_id'];
$username = $_SESSION['name'] ?? 'User';

function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function fetchFaults(mysqli $conn, string $sql, int $userId): array
{
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $rows;
}

$faults = [];
$dbError = null;

try {
    try {
        // Preferred: join the categories table to get the category name
        $faults = fetchFaults(
            $conn,
            'SELECT f.*, c.name AS category_name
             FROM faults f
             LEFT JOIN categories c ON c.id = f.category_id
             WHERE f.user_id = ?
             ORDER BY f.id DESC',
            $userId
        );
    } catch (mysqli_sql_exception $e) {
        // The categories table/columns are named differently: fall back to the faults table alone
        error_log('CampusFix my_faults join failed: '.$e->getMessage());
        $faults = fetchFaults(
            $conn,
            'SELECT * FROM faults WHERE user_id = ? ORDER BY id DESC',
            $userId
        );
    }
} catch (mysqli_sql_exception $e) {
    error_log('CampusFix my_faults error: '.$e->getMessage());
    $dbError = 'An error has occurred while loading your faults from the database.';
    if (DEBUG) {
        $dbError .= ' ('.$e->getMessage().')';
    }
}

$counts = ['total' => count($faults), 'pending' => 0, 'progress' => 0, 'resolved' => 0];
foreach ($faults as $f) {
    $s = strtolower(trim($f['fault_status'] ?? ''));
    if ($s === 'pending') {
        ++$counts['pending'];
    } elseif ($s === 'in progress') {
        ++$counts['progress'];
    } elseif ($s === 'resolved') {
        ++$counts['resolved'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusFix | My Faults</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="user_style.css">
    <style>
/* Styles for my_faults.php (load after user_style.css) */

:root {
    --fl-green: #1a6b3c;
    --fl-ink: #111827;
    --fl-muted: #6b7280;
    --fl-line: #eceeed;
}

.alert { padding: 12px 16px; border-radius: 12px; margin-bottom: 18px; font-size: 14px; }
.alert.error { background: #fdecea; color: #b71c1c; }

/* ---------- Summary tiles ---------- */
.fl-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 14px;
    margin-bottom: 18px;
}
.fl-stat {
    background: #fff;
    border: 1px solid var(--fl-line);
    border-radius: 24px;
    padding: 18px 18px 16px;
    min-height: 104px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.fl-stat strong { font-size: 30px; font-weight: 600; line-height: 1; color: var(--fl-ink); }
.fl-stat span { font-size: 14px; color: var(--fl-muted); line-height: 1.25; }
.fl-stat-dark { background: var(--fl-green); border-color: var(--fl-green); }
.fl-stat-dark strong, .fl-stat-dark span { color: #fff; }

/* ---------- Buttons ---------- */
.fl-actions { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 22px; }
.fl-btn {
    flex: 1 1 180px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 15px 22px;
    border-radius: 999px;
    font-size: 16px;
    font-weight: 500;
    text-decoration: none;
    transition: transform 0.12s ease, box-shadow 0.12s ease;
}
.fl-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(17, 24, 39, 0.10); }
.fl-btn-primary { background: var(--fl-green); color: #fff; border: 1px solid var(--fl-green); }
.fl-btn-outline { background: #fff; color: var(--fl-ink); border: 1px solid var(--fl-line); }

/* ---------- Cards ---------- */
.fl-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
    gap: 18px;
    align-items: start;
}
.fl-card {
    background: #fff;
    border: 1px solid var(--fl-line);
    border-radius: 28px;
    box-shadow: 0 2px 8px rgba(17, 24, 39, 0.05);
    overflow: hidden;
}
.fl-card-done { background: #f8f9f8; box-shadow: none; }

.fl-main { display: flex; gap: 16px; padding: 18px 18px 14px; }

.fl-thumb {
    flex: 0 0 96px;
    width: 96px;
    height: 124px;
    border-radius: 20px;
    overflow: hidden;
    background: #e7f1ec;
    display: block;
}
.fl-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.fl-thumb-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--fl-green);
    font-size: 28px;
}

.fl-info { flex: 1; min-width: 0; }

.fl-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }
.fl-head h3 { margin: 0; font-size: 18px; font-weight: 500; color: var(--fl-ink); line-height: 1.3; }

.fl-sub { margin: 6px 0 0; font-size: 15px; color: var(--fl-muted); line-height: 1.35; }
.fl-strong { margin: 6px 0 0; font-size: 18px; font-weight: 600; color: var(--fl-ink); }
.fl-strong span { font-size: 15px; font-weight: 400; color: var(--fl-muted); }
.fl-muted { margin: 4px 0 0; font-size: 14px; color: var(--fl-muted); }

/* Status pills */
.fl-pill {
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 500;
    background: #f1f2f4;
    color: #4b5563;
}
.fl-dot { width: 8px; height: 8px; border-radius: 50%; background: currentColor; }
.st-pending     { background: #fef3c7; color: #92400e; }
.st-in-progress { background: #dbeafe; color: #1e40af; }
.st-resolved    { background: #e3f4ea; color: #1a6b3c; }

/* Description note box */
.fl-note {
    margin: 0 18px 14px;
    padding: 12px 14px;
    background: #f5f6f6;
    border-radius: 16px;
    color: var(--fl-muted);
    font-size: 14px;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.fl-foot { border-top: 1px solid var(--fl-line); text-align: center; }
.fl-foot a {
    display: block;
    padding: 15px;
    font-size: 15px;
    font-weight: 500;
    color: var(--fl-ink);
    text-decoration: none;
}
.fl-foot a:hover { color: var(--fl-green); }

/* Empty state */
.fl-empty {
    text-align: center;
    padding: 48px 20px;
    background: #fff;
    border: 1px dashed #cfd5d2;
    border-radius: 28px;
    color: var(--fl-muted);
}
.fl-empty i { font-size: 34px; margin-bottom: 10px; color: #9ca3af; }
.fl-empty a { color: var(--fl-green); font-weight: 600; text-decoration: none; }

@media (max-width: 600px) {
    .fl-grid { grid-template-columns: 1fr; }
    .fl-stats { grid-template-columns: repeat(2, 1fr); }
}
    </style>
</head>

<body>

<div class="dashboard">

    <!-- SIDEBAR -->
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
            <a href="user_dashboard.php" class="nav-link">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>

            <a href="report_fault.php" class="nav-link">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>Report Fault</span>
            </a>

            <a href="my_faults.php" class="nav-link active">
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

    <!-- MAIN CONTENT -->
    <main class="main-content">

        <header class="topbar">

            <div class="page-title">
                <h1>My Faults</h1>
                <p>View the maintenance reports you have submitted.</p>
            </div>

            <div class="user-area">
                <button class="notification-button">
                    <i class="fa-solid fa-bell"></i>
                </button>

                <div class="user-profile">
                    <div class="avatar"><?php echo h(strtoupper(substr($username, 0, 1))); ?></div>

                    <div class="user-info">
                        <strong><?php echo h($username); ?></strong>
                        <small>Student</small>
                    </div>

                    <i class="fa-solid fa-chevron-down"></i>
                </div>
            </div>

        </header>

        <section class="content">

            <?php if ($dbError) { ?>
                <div class="alert error"><?php echo h($dbError); ?></div>
            <?php } ?>

            <!-- SUMMARY TILES -->
            <div class="fl-stats">
                <div class="fl-stat fl-stat-dark">
                    <strong><?php echo $counts['total']; ?></strong>
                    <span>Total reports</span>
                </div>
                <div class="fl-stat">
                    <strong><?php echo $counts['pending']; ?></strong>
                    <span>Pending</span>
                </div>
                <div class="fl-stat">
                    <strong><?php echo $counts['progress']; ?></strong>
                    <span>In progress</span>
                </div>
                <div class="fl-stat">
                    <strong><?php echo $counts['resolved']; ?></strong>
                    <span>Resolved</span>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="fl-actions">
                <a href="report_fault.php" class="fl-btn fl-btn-primary">
                    <i class="fa-solid fa-plus"></i> Report Fault
                </a>
                <a href="user_dashboard.php" class="fl-btn fl-btn-outline">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
            </div>

            <?php if (empty($faults) && !$dbError) { ?>

                <div class="fl-empty">
                    <i class="fa-solid fa-clipboard-check"></i>
                    <p>You haven't submitted any fault reports yet.</p>
                    <a href="report_fault.php">Report your first fault</a>
                </div>

            <?php } else { ?>

                <div class="fl-grid">

                    <?php foreach ($faults as $fault) {
                        $category = $fault['category_name'] ?? $fault['category'] ?? $fault['category_id'] ?? '';
                        $location = $fault['fault_location'] ?? $fault['location'] ?? '';
                        $status = $fault['fault_status'] ?? 'Pending';
                        $statusClass = 'st-'.trim(preg_replace('/[^a-z]+/', '-', strtolower($status)), '-');
                        $priority = $fault['priority'] ?? '';
                        $date = !empty($fault['created_at'])
                            ? date('d M Y, H:i', strtotime($fault['created_at']))
                            : '';
                        $isDone = strtolower(trim($status)) === 'resolved';
                        $sub = trim($location.($location !== '' && !empty($fault['ticket_number']) ? ' · ' : '').($fault['ticket_number'] ?? ''));
                        ?>

                        <article class="fl-card<?php echo $isDone ? ' fl-card-done' : ''; ?>">

                            <div class="fl-main">

                                <?php if (!empty($fault['image'])) { ?>
                                    <a class="fl-thumb"
                                       href="../uploads/<?php echo rawurlencode($fault['image']); ?>"
                                       target="_blank" rel="noopener">
                                        <img src="../uploads/<?php echo rawurlencode($fault['image']); ?>"
                                             alt="Photo of the reported fault" loading="lazy">
                                    </a>
                                <?php } else { ?>
                                    <div class="fl-thumb fl-thumb-icon">
                                        <i class="fa-solid fa-screwdriver-wrench"></i>
                                    </div>
                                <?php } ?>

                                <div class="fl-info">

                                    <div class="fl-head">
                                        <h3><?php echo h($category); ?></h3>
                                        <span class="fl-pill <?php echo h($statusClass); ?>">
                                            <span class="fl-dot"></span><?php echo h($status); ?>
                                        </span>
                                    </div>

                                    <?php if ($sub !== '') { ?>
                                        <p class="fl-sub"><?php echo h($sub); ?></p>
                                    <?php } ?>

                                    <?php if ($priority !== '') { ?>
                                        <p class="fl-strong"><?php echo h($priority); ?> <span>priority</span></p>
                                    <?php } ?>

                                    <?php if ($date !== '') { ?>
                                        <p class="fl-muted"><?php echo h($date); ?></p>
                                    <?php } ?>

                                </div>

                            </div>

                            <?php if (!empty($fault['fault_description'])) { ?>
                                <div class="fl-note"><?php echo nl2br(h($fault['fault_description'])); ?></div>
                            <?php } ?>
                        </article>

                    <?php } ?>

                </div>

            <?php } ?>

        </section>

    </main>

</div>

</body>
</html>
<?php
mysqli_close($conn);
