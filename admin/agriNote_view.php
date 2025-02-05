<?php
require('top.inc.php');


// Admin and Vendor Filtering
$condition = ""; 
$condition1 = ""; 

// If the user is a vendor, only show their notes
if ($_SESSION['ADMIN_ROLE'] == 1) {
    $condition = " WHERE added_by='" . $_SESSION['ADMIN_ID'] . "'";
    $condition1 = " AND added_by='" . $_SESSION['ADMIN_ID'] . "'";
}

// Check if delete request is made
if (isset($_GET['type']) && $_GET['type'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize the ID
    // Build the delete query with the condition
    $delete_query = "DELETE FROM agri_tips WHERE id = $id";
    
    // If the user is a vendor, ensure they can only delete their own notes
    if ($_SESSION['ADMIN_ROLE'] == 1) {
        $delete_query .= " AND added_by='" . $_SESSION['ADMIN_ID'] . "'";
    }
    
    $delete_res = mysqli_query($con, $delete_query);
    
    if (mysqli_affected_rows($con) > 0) {
        // Redirect with a success message if any row was deleted
        header("Location: agriNote_view.php?delete_success=true");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error deleting record: " . mysqli_error($con) . "</div>";
    }
}

// Search functionality
$search_query = "";
if (isset($_GET['str']) && $_GET['str'] != '') {
    $str = mysqli_real_escape_string($con, $_GET['str']);
    
    // Adjust the search query based on whether there's an existing condition
    if ($condition == "") {
        $search_query = " WHERE (title LIKE '%$str%' OR description LIKE '%$str%' OR about LIKE '%$str%')";
    } else {
        $search_query = " AND (title LIKE '%$str%' OR description LIKE '%$str%' OR about LIKE '%$str%')";
    }
}

// Fetch notes based on the search and role
$sql = "SELECT * FROM agri_tips" . $condition . $search_query . " ORDER BY id DESC";
$res = mysqli_query($con, $sql);

if (!$res) {
    die("Error fetching records: " . mysqli_error($con));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agriculture Notes</title>
    <style>
        .d-flex {
            margin-bottom: 5px;
            width: 25%;
            margin-left: 73%;
        }
        .form-control {
            border: none;
            border-bottom: 1.5px solid #17a2b8;
            border-radius: 0px;
            margin-right: 10px;
            outline: 0;
        }
        .form-control:focus {
            box-shadow: none;
        }
        #move {
            margin-left: 20%;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="content pb-0">
        <?php
        if (isset($_GET['delete_success'])) {
            echo "<div class='alert alert-success'>Note deleted successfully.</div>";
        }
        ?>
        <div class="orders">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="box-title">Agriculture Notes</h4>
                            <h4 class="box-link"><a href="agiNote_add.php">Add note</a></h4>
                        </div>
                        
                        <!-- Search form -->
                        <div class="d-flex">
                            <input class="form-control me-2" type="search" id="search_notes" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-info" onclick="search('<?php echo SITE_PATH ?>')">
                                <i class="fas fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                        <!-- End of Search form -->
                        
                        <div class="card-body--">
                            <div class="table-stats order-table ov-h">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="serial">#</th>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>About</th>
                                            <th>Added on</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $i = 1;
                                        if (mysqli_num_rows($res) > 0) {
                                            while ($row = mysqli_fetch_assoc($res)) {
                                        ?>
                                        <tr>
                                            <td class="serial"><?php echo $i; ?></td>
                                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                                            <td><?php echo htmlspecialchars($row['about']); ?></td>
                                            <td><?php echo htmlspecialchars($row['date_t']); ?></td>
                                            <td>
                                                <a href='agiNote_add.php?id=<?php echo $row['id']; ?>' class='badge badge-edit'>Edit</a>
                                                <a href='?type=delete&id=<?php echo $row['id']; ?>' class='badge badge-delete' onclick="return confirm('Are you sure you want to delete this note?');">Delete</a>
                                            </td>
                                        </tr>
                                        <?php 
                                            $i++;
                                            }
                                        } else {
                                            echo "<h2 id='move'>Data not found</h2>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// JavaScript for search functionality
function search(site_path) {
    var search_value = document.getElementById('search_notes').value;
    window.location.href = site_path + "admin/agriNote_view.php?str=" + search_value;
}
</script>
</body>
</html>
<?php 
require('footer.inc.php'); 
?>
