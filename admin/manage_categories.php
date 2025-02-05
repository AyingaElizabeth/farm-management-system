<?php
require('top.inc.php');
isAdmin();

$categories = '';
$msg = '';
$id = '';

if(isset($_GET['id']) && $_GET['id']!=''){
    $id = get_safe_value($con, $_GET['id']);
    $res = mysqli_query($con, "SELECT * FROM categories WHERE id='$id'");
    $check = mysqli_num_rows($res);
    if($check > 0){
        $row = mysqli_fetch_assoc($res);
        $categories = htmlspecialchars($row['categories']);
    } else {
        header('location: categories.php');
        exit();
    }
}

if(isset($_POST['submit'])){
    $categories = get_safe_value($con, $_POST['categories']);
    $res = mysqli_query($con, "SELECT * FROM categories WHERE categories='$categories'");
    $check = mysqli_num_rows($res);
    if($check > 0){
        if(isset($_GET['id']) && $_GET['id'] != ''){
            $getData = mysqli_fetch_assoc($res);
            if($id != $getData['id']){
                $msg = "Category already exists";
            }
        } else {
            $msg = "Category already exists";
        }
    }
    
    if($msg == ''){
        if(isset($_GET['id']) && $_GET['id'] != ''){
            mysqli_query($con, "UPDATE categories SET categories='$categories' WHERE id='$id'");
        } else {
            mysqli_query($con, "INSERT INTO categories(categories, status) VALUES ('$categories', '1')");
        }
        header('location: categories.php');
        exit();
    }
}

// HTML output starts here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
</head>
<body>
    <div class="content pb-0">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header"><strong>Categories</strong><small> Form</small></div>
                        <form method="post">
                            <div class="card-body card-block">
                                <div class="form-group">
                                    <label for="categories" class="form-control-label">Categories</label>
                                    <input type="text" id="categories" name="categories" placeholder="Enter category name" class="form-control" required value="<?php echo $categories; ?>">
                                </div>
                                <button id="submit-button" name="submit" type="submit" class="btn btn-lg btn-info btn-block">
                                    <span id="submit-button-text">Submit</span>
                                </button>
                                <?php if($msg != ''): ?>
                                    <div class="alert alert-danger mt-3"><?php echo $msg; ?></div>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
require('footer.inc.php');
ob_end_flush();
?>