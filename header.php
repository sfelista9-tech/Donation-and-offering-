<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - Smart Donation System' : 'Smart Donation System'; ?></title>
    <link rel="stylesheet" href="<?php echo isset($css_path) ? $css_path : '../css/style.css'; ?>">
</head>
<body>
    <nav>
        <ul>
            <li class="navbar-brand">💰 Smart Donation System</li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="<?php echo isset($dashboard_link) ? $dashboard_link : '../admin/dashboard.php'; ?>">Dashboard</a></li>
                <li><a href="<?php echo isset($members_link) ? $members_link : '../admin/members.php'; ?>">Members</a></li>
                <li><a href="<?php echo isset($offerings_link) ? $offerings_link : '../admin/offerings.php'; ?>">Offerings</a></li>
                <li><a href="<?php echo isset($donations_link) ? $donations_link : '../admin/donations.php'; ?>">Donations</a></li>
                <li style="margin-left: auto;"><a href="../auth/logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</body>
</html>
