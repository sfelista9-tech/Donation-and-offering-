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

// Add Member
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
    $member_no = mysqli_real_escape_string($conn, $_POST['member_no']);
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "INSERT INTO members (member_no, fullname, gender, phone, address, email) 
            VALUES ('$member_no', '$fullname', '$gender', '$phone', '$address', '$email')";

    if (mysqli_query($conn, $sql)) {
        $success_msg = "Member Added Successfully!";
    } else {
        $error_msg = "Error: " . mysqli_error($conn);
    }
}

// Delete Member
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM members WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        $success_msg = "Member Deleted Successfully!";
    } else {
        $error_msg = "Error: " . mysqli_error($conn);
    }
}

// Fetch all members
$members_list = mysqli_query($conn, "SELECT * FROM members ORDER BY joined_date DESC");

$page_title = "Members";
$css_path = "../css/style.css";
$dashboard_link = "dashboard.php";
$members_link = "members.php";
$offerings_link = "offerings.php";
$donations_link = "donations.php";
?>
<?php include("../includes/header.php"); ?>

<div class="container">
    <header>
        <h1>Members Management</h1>
        <p>Add, view, and manage members in the system.</p>
    </header>

    <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success"><?php echo $success_msg; ?></div>
    <?php endif; ?>

    <?php if (!empty($error_msg)): ?>
        <div class="alert alert-danger"><?php echo $error_msg; ?></div>
    <?php endif; ?>

    <div style="margin-bottom: 30px;">
        <h2>Add New Member</h2>
        <div class="form-container">
            <form method="POST" action="" id="memberForm">
                <div class="form-group">
                    <label for="member_no">Member No:</label>
                    <input type="text" id="member_no" name="member_no" required>
                </div>

                <div class="form-group">
                    <label for="fullname">Full Name:</label>
                    <input type="text" id="fullname" name="fullname" required>
                </div>

                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email">
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" required></textarea>
                </div>

                <button type="submit" name="save" class="btn btn-success btn-block">Save Member</button>
            </form>
        </div>
    </div>

    <div class="table-container">
        <h2>Members List</h2>
        <input type="text" id="searchInput" placeholder="Search members..." style="margin-bottom: 15px; padding: 10px; width: 100%; border: 1px solid #bdc3c7; border-radius: 4px;">
        
        <table id="membersTable">
            <thead>
                <tr>
                    <th>Member No</th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Joined Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($members_list) > 0) {
                    while ($row = mysqli_fetch_assoc($members_list)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['member_no']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email'] ?? '-') . "</td>";
                        echo "<td><span style='background-color: #27ae60; color: white; padding: 5px 10px; border-radius: 4px;'>" . $row['status'] . "</span></td>";
                        echo "<td>" . date('Y-m-d', strtotime($row['joined_date'])) . "</td>";
                        echo "<td class='action-links'>";
                        echo "<a href='members.php?delete=" . $row['id'] . "' class='delete-link' onclick='return confirmDelete(\"" . htmlspecialchars($row['fullname']) . "\")'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' style='text-align:center;'>No members found</td></tr>";
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
        filterTable('searchInput', 'membersTable');
    });
</script>

<?php include("../includes/footer.php"); ?>
