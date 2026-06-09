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

// Add Donation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_donation'])) {
    $member_id = !empty($_POST['member_id']) ? intval($_POST['member_id']) : null;
    $amount = floatval($_POST['amount']);
    $purpose = mysqli_real_escape_string($conn, $_POST['purpose']);
    $donor_name = mysqli_real_escape_string($conn, $_POST['donor_name']);

    if ($member_id === null) {
        $sql = "INSERT INTO donations (amount, purpose, donor_name) 
                VALUES ($amount, '$purpose', '$donor_name')";
    } else {
        $sql = "INSERT INTO donations (member_id, amount, purpose, donor_name) 
                VALUES ($member_id, $amount, '$purpose', '$donor_name')";
    }

    if (mysqli_query($conn, $sql)) {
        $success_msg = "Donation Added Successfully!";
    } else {
        $error_msg = "Error: " . mysqli_error($conn);
    }
}

// Delete Donation
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM donations WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        $success_msg = "Donation Deleted Successfully!";
    } else {
        $error_msg = "Error: " . mysqli_error($conn);
    }
}

// Fetch all donations
$donations_list = mysqli_query($conn, "
    SELECT d.id, COALESCE(m.fullname, d.donor_name) as donor_name, d.amount, d.donation_date, d.purpose 
    FROM donations d 
    LEFT JOIN members m ON d.member_id = m.id 
    ORDER BY d.donation_date DESC
");

// Fetch members for dropdown
$members_dropdown = mysqli_query($conn, "SELECT id, fullname FROM members WHERE status='Active'");

// Calculate total
$total_result = mysqli_query($conn, "SELECT SUM(amount) as total FROM donations");
$total = mysqli_fetch_assoc($total_result);

$page_title = "Donations";
$css_path = "../css/style.css";
$dashboard_link = "dashboard.php";
$members_link = "members.php";
$offerings_link = "offerings.php";
$donations_link = "donations.php";
?>
<?php include("../includes/header.php"); ?>

<div class="container">
    <header>
        <h1>Donations Management</h1>
        <p>Record and track donations from donors.</p>
    </header>

    <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
    <?php endif; ?>

    <?php if (!empty($error_msg)): ?>
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <div style="margin-bottom: 30px;">
        <h2>Record New Donation</h2>
        <div class="form-container">
            <form method="POST" action="" id="donationForm">
                <div class="form-group">
                    <label for="member_id">Member (Optional):</label>
                    <select id="member_id" name="member_id">
                        <option value="">-- Not a Member --</option>
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
                    <label for="donor_name">Donor Name:</label>
                    <input type="text" id="donor_name" name="donor_name" required>
                </div>

                <div class="form-group">
                    <label for="amount">Amount (TZS):</label>
                    <input type="number" id="amount" name="amount" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="purpose">Purpose:</label>
                    <textarea id="purpose" name="purpose" required></textarea>
                </div>

                <button type="submit" name="save_donation" class="btn btn-success btn-block">Save Donation</button>
            </form>
        </div>
    </div>

    <div class="dashboard-cards">
        <div class="card warning">
            <h3>Total Donations</h3>
            <div class="value" data-currency><?php echo $total['total'] ?? 0; ?></div>
        </div>
    </div>

    <div class="table-container">
        <h2>Donations List</h2>
        <input type="text" id="searchInput" placeholder="Search donations..." style="margin-bottom: 15px; padding: 10px; width: 100%; border: 1px solid #bdc3c7; border-radius: 4px;">
        
        <table id="donationsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Donor Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Purpose</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($donations_list) > 0) {
                    while ($row = mysqli_fetch_assoc($donations_list)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['donor_name'] ?? 'Anonymous') . "</td>";
                        echo "<td data-currency>" . $row['amount'] . "</td>";
                        echo "<td>" . date('Y-m-d H:i', strtotime($row['donation_date'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['purpose']) . "</td>";
                        echo "<td class='action-links'>";
                        echo "<a href='donations.php?delete=" . $row['id'] . "' class='delete-link' onclick='return confirmDelete(\"Donation #" . $row['id'] . "\")'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center;'>No donations found</td></tr>";
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
        filterTable('searchInput', 'donationsTable');
    });
</script>

<?php include("../includes/footer.php"); ?>
