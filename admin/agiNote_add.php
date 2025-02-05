<?php
require_once('top.inc.php');
require_once('functions.inc.php');

// Ensure database connection is established
if (!isset($con)) { 
    die("Database connection not established. Check your top.inc.php file."); 
}

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $title = isset($_POST['title']) ? get_safe_value($con, $_POST['title']) : '';
    $about = isset($_POST['about']) ? get_safe_value($con, $_POST['about']) : '';
    $description = isset($_POST['description']) ? get_safe_value($con, $_POST['description']) : '';
    $date_t = date('Y-m-d');
    $added_by = $_SESSION['ADMIN_ID']; // Capture the ID of the user adding the note

    // If the user is a vendor, restrict 'about' options to their allowed categories
    if ($_SESSION['ADMIN_ROLE'] == 1 && !in_array($about, ['marketprice', 'myfarm', 'workers'])) {
        $error_message = "You don't have permission to add notes under this category.";
    } else {
        $sql = "INSERT INTO agri_tips(title, about, description, date_t, added_by) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssi", $title, $about, $description, $date_t, $added_by);
            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                $success_message = "You have added an Agricultural note";
            } else {
                $error_message = "Error: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error_message = "Error: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>WeFarm | Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Add your CSS files here -->
</head>
<body>
    <div class="cardbox">
        <div class="wrapper">
            <div class="content pb-0">
                <div class="animated fadeIn">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header"><strong>Add Agriculture note</strong><small> Form</small></div>  
                                <div class="card-body">
                                    <?php
                                    if ($success_message) {
                                        echo "<div class='alert alert-success animated bounce' id='sams1'>$success_message</div>";
                                    }
                                    if ($error_message) {
                                        echo "<div class='alert alert-danger animated shake' id='sams1'>$error_message</div>";
                                    }
                                    ?>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                        <div class="row">
                                            <div class="col-md-12 form-group">
                                                <label for="title">Title</label>
                                                <input type="text" id="title" name="title" class="form-control" maxlength="200" placeholder="Agricultural Fungicides Broad-spectrum fungicides" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label for="description">Description</label>
                                                <input type="text" id="description" class="form-control" name="description" maxlength="200" placeholder="">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="about">About</label>
                                                <select class="form-control" id="about" name="about">
                                                    <?php
                                                    // Admin can add notes under any category, but vendors are restricted
                                                    if ($_SESSION['ADMIN_ROLE'] == 0) { // Admin
                                                        echo '<option value="marketprice">Market Price</option>';
                                                        echo '<option value="myfarm">My Farm</option>';
                                                        echo '<option value="workers">Workers</option>';
                                                    } else { // Vendor, restrict categories
                                                        echo '<option value="marketprice">Market Price</option>';
                                                        echo '<option value="myfarm">My Farm</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <button type="submit" name="submit" class="btn btn-success btn-block"><span class="fa fa-check"></span> Add</button>  
                                            </div> 
                                            <div class="col-md-6 form-group">
                                                <button type="reset" class="btn btn-danger btn-block"><span class="fa fa-times"></span> Cancel</button>  
                                            </div>    
                                        </div>
                                    </form>
                                </div>
                            </div>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php require('footer.inc.php'); ?>
    
    <!-- Add your JavaScript files here -->
</body> 
</html>
