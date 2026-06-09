<?php
session_start();
include("../config/db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch statistics
$members_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM members");
$members = mysqli_fetch_assoc($members_result);

$offerings_result = mysqli_query($conn, "SELECT SUM(amount) as total FROM offerings");
$offerings = mysqli_fetch_assoc($offerings_result);

$donations_result = mysqli_query($conn, "SELECT SUM(amount) as total FROM donations");
$donations = mysqli_fetch_assoc($donations_result);

$page_title = "Dashboard";
$css_path = "../css/style.css";
$dashboard_link = "dashboard.php";
$members_link = "members.php";
$offerings_link = "offerings.php";
$donations_link = "donations.php";
?>
<?php include("../includes/header.php"); ?>

<div class="container">
    <header>
        <h1>Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! Here's your system overview.</p>
    </header>

    <div class="dashboard-cards">
        <div class="card primary">
            <h3>Total Members</h3>
            <div class="value"><?php echo $members['total'] ?? 0; ?></div>
        </div>

        <div class="card success">
            <h3>Total Offerings</h3>
            <div class="value" data-currency><?php echo $offerings['total'] ?? 0; ?></div>
        </div>

        <div class="card warning">
            <h3>Total Donations</h3>
            <div class="value" data-currency><?php echo $donations['total'] ?? 0; ?></div>
        </div>

        <div class="card">
            <h3>Total Collected</h3>
            <div class="value" data-currency><?php echo ($offerings['total'] ?? 0) + ($donations['total'] ?? 0); ?></div>
        </div>
    </div>

    <div class="table-container">
        <h2>Recent Offerings</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Member Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $recent_offerings = mysqli_query($conn, "
                    SELECT o.id, m.fullname, o.amount, o.offering_date, o.description 
                    FROM offerings o 
                    LEFT JOIN members m ON o.member_id = m.id 
                    ORDER BY o.offering_date DESC 
                    LIMIT 5
                ");
                
                if (mysqli_num_rows($recent_offerings) > 0) {
                    while ($row = mysqli_fetch_assoc($recent_offerings)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['fullname'] ?? 'Anonymous') . "</td>";
                        echo "<td data-currency>" . $row['amount'] . "</td>";
                        echo "<td>" . date('Y-m-d H:i', strtotime($row['offering_date'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['description'] ?? '-') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center;'>No offerings found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <br>

    <div class="table-container">
        <h2>Recent Donations</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Donor Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Purpose</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $recent_donations = mysqli_query($conn, "
                    SELECT d.id, COALESCE(m.fullname, d.donor_name) as donor_name, d.amount, d.donation_date, d.purpose 
                    FROM donations d 
                    LEFT JOIN members m ON d.member_id = m.id 
                    ORDER BY d.donation_date DESC 
                    LIMIT 5
                ");
                
                if (mysqli_num_rows($recent_donations) > 0) {
                    while ($row = mysqli_fetch_assoc($recent_donations)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['donor_name'] ?? 'Anonymous') . "</td>";
                        echo "<td data-currency>" . $row['amount'] . "</td>";
                        echo "<td>" . date('Y-m-d H:i', strtotime($row['donation_date'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['purpose'] ?? '-') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center;'>No donations found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="../js/main.js"></script>
<?php include("../includes/footer.php"); ?>
