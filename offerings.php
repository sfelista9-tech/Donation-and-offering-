<?php
session_start();
include("../config/db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$success_msg = "";
$error_msg = "";

// Add Offering
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_offering'])) {
    $member_id = intval($_POST['member_id']);
    $amount = floatval($_POST['amount']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "INSERT INTO offerings (member_id, amount, description) 
            VALUES ($member_id, $amount, '$description')";

    if (mysqli_query($conn, $sql)) {
        $success_msg = "Offering Added Successfully!";
    } else {
        $error_msg = "Error: " . mysqli_error($conn);
    }
}

// Delete Offering
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM offerings WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        $success_msg = "Offering Deleted Successfully!";
    } else {
        $error_msg = "Error: " . mysqli_error($conn);
    }
}

// Fetch all offerings with member names
$offerings_list = mysqli_query($conn, "
    SELECT o.id, m.fullname, o.amount, o.offering_date, o.description 
    FROM offerings o 
    LEFT JOIN members m ON o.member_id = m.id 
    ORDER BY o.offering_date DESC
");

// Fetch members for dropdown
$members_dropdown = mysqli_query($conn, "SELECT id, fullname FROM members WHERE status='Active'");

// Calculate total
$total_result = mysqli_query($conn, "SELECT SUM(amount) as total FROM offerings");
$total = mysqli_fetch_assoc($total_result);

$page_title = "Offerings";
$css_path = "../css/style.css";
$dashboard_link = "dashboard.php";
$members_link = "members.php";
$offerings_link = "offerings.php";
$donations_link = "donations.php";
?>
<?php include("../includes/header.php"); ?>

<div class="container">
    <header>
        <h1>Offerings Management</h1>
        <p>Record and track offerings from members.</p>
    </header>

    <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
    <?php endif; ?>

    <?php if (!empty($error_msg)): ?>
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <div style="margin-bottom: 30px;">
        <h2>Record New Offering</h2>
        <div class="form-container">
            <form method="POST" action="" id="offeringForm">
                <div class="form-group">
                    <label for="member_id">Member:</label>
                    <select id="member_id" name="member_id" required>
                        <option value="">Select Member</option>
                        <?php
                        if (mysqli_num_rows($members_dropdown) > 0) {
                            mysqli_data_seek($members_dropdown, 0);
                            while ($member = mysqli_fetch_assoc($members_dropdown)) {
                                echo "<option value='" . $member['id'] . "'>" . htmlspecialchars($member['fullname']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="amount">Amount (TZS):</label>
                    <input type="number" id="amount" name="amount" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>
                </div>

                <button type="submit" name="save_offering" class="btn btn-success btn-block">Save Offering</button>
            </form>
        </div>
    </div>

    <div class="dashboard-cards">
        <div class="card success">
            <h3>Total Offerings</h3>
            <div class="value" data-currency><?php echo $total['total'] ?? 0; ?></div>
        </div>
    </div>

    <div class="table-container">
        <h2>Offerings List</h2>
        <input type="text" id="searchInput" placeholder="Search offerings..." style="margin-bottom: 15px; padding: 10px; width: 100%; border: 1px solid #bdc3c7; border-radius: 4px;">
        
        <table id="offeringsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Member Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($offerings_list) > 0) {
                    while ($row = mysqli_fetch_assoc($offerings_list)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['fullname'] ?? 'Anonymous') . "</td>";
                        echo "<td data-currency>" . $row['amount'] . "</td>";
                        echo "<td>" . date('Y-m-d H:i', strtotime($row['offering_date'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td class='action-links'>";
                        echo "<a href='offerings.php?delete=" . $row['id'] . "' class='delete-link' onclick='return confirmDelete(\"Offering #" . $row['id'] . "\")'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center;'>No offerings found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="../js/main.js"></script>
<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        filterTable('searchInput', 'offeringsTable');
    });
</script>

<?php include("../includes/footer.php"); ?>
