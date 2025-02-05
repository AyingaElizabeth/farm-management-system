<?php
require('top.inc.php');

$condition = '';
$condition1 = '';
if ($_SESSION['ADMIN_ROLE'] == 1) {
    $condition = " AND product.added_by='" . $_SESSION['ADMIN_ID'] . "'";
    $condition1 = " AND added_by='" . $_SESSION['ADMIN_ID'] . "'";
}

$categories_id = '';
$name = '';
$oldprice = '';
$price = '';
$qty = '';
$image = '';
$short_desc = '';
$description = '';
$meta_title = '';
$meta_desc = '';
$meta_keyword = '';
$best_seller = '';
$sub_categories_id = '';

$msg = '';
$image_required = 'required';

if (isset($_GET['id']) && $_GET['id'] != '') {
    $image_required = '';
    $id = get_safe_value($con, $_GET['id']);
    $query = "SELECT * FROM product WHERE id=?" . $condition1;
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $check = mysqli_num_rows($res);
    if ($check > 0) {
        $row = mysqli_fetch_assoc($res);
        $categories_id = $row['categories_id'];
        $sub_categories_id = $row['sub_categories_id'];
        $name = $row['name'];
        $oldprice = $row['oldprice'];
        $price = $row['price'];
        $qty = $row['qty'];
        $short_desc = $row['short_desc'];
        $description = $row['description'];
        $meta_title = $row['meta_title'];
        $meta_desc = $row['meta_desc'];
        $meta_keyword = $row['meta_keyword'];
        $best_seller = $row['best_seller'];
    } else {
        header('location:product.php');
        die();
    }
}

if (isset($_POST['submit'])) {
    $categories_id = get_safe_value($con, $_POST['categories_id']);
    $sub_categories_id = get_safe_value($con, $_POST['sub_categories_id']);
    $name = get_safe_value($con, $_POST['name']);
    $oldprice = get_safe_value($con, $_POST['oldprice']);
    $price = get_safe_value($con, $_POST['price']);
    $qty = get_safe_value($con, $_POST['qty']);
    $short_desc = get_safe_value($con, $_POST['short_desc']);
    $description = get_safe_value($con, $_POST['description']);
    $meta_title = get_safe_value($con, $_POST['meta_title']);
    $meta_desc = get_safe_value($con, $_POST['meta_desc']);
    $meta_keyword = get_safe_value($con, $_POST['meta_keyword']);
    $best_seller = get_safe_value($con, $_POST['best_seller']);
    
    $query = "SELECT * FROM product WHERE name=?" . $condition1;
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $check = mysqli_num_rows($res);
    
    if ($check > 0) {
        if (isset($_GET['id']) && $_GET['id'] != '') {
            $getData = mysqli_fetch_assoc($res);
            if ($id == $getData['id']) {
                // Do nothing
            } else {
                $msg = "Product already exists";
            }
        } else {
            $msg = "Product already exists";
        }
    }
    
    if (isset($_GET['id']) && $_GET['id'] == 0) {
        if ($_FILES['image']['type'] != 'image/png' && $_FILES['image']['type'] != 'image/jpg' && $_FILES['image']['type'] != 'image/jpeg') {
            $msg = "Please select only png, jpg and jpeg image format";
        }
    } else {
        if ($_FILES['image']['type'] != '') {
            if ($_FILES['image']['type'] != 'image/png' && $_FILES['image']['type'] != 'image/jpg' && $_FILES['image']['type'] != 'image/jpeg') {
                $msg = "Please select only png, jpg and jpeg image format";
            }
        }
    }
    
    if ($msg == '') {
        if (isset($_GET['id']) && $_GET['id'] != '') {
            if ($_FILES['image']['name'] != '') {
                $image = rand(111111111, 999999999) . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], PRODUCT_IMAGE_SERVER_PATH . $image);
                $update_sql = "UPDATE product SET categories_id=?, name=?, oldprice=?, price=?, qty=?, short_desc=?, description=?, meta_title=?, meta_desc=?, meta_keyword=?, image=?, best_seller=?, sub_categories_id=? WHERE id=?";
                $stmt = mysqli_prepare($con, $update_sql);
                mysqli_stmt_bind_param($stmt, "sssssssssssssi", $categories_id, $name, $oldprice, $price, $qty, $short_desc, $description, $meta_title, $meta_desc, $meta_keyword, $image, $best_seller, $sub_categories_id, $id);
            } else {
                $update_sql = "UPDATE product SET categories_id=?, name=?, oldprice=?, price=?, qty=?, short_desc=?, description=?, meta_title=?, meta_desc=?, meta_keyword=?, best_seller=?, sub_categories_id=? WHERE id=?";
                $stmt = mysqli_prepare($con, $update_sql);
                mysqli_stmt_bind_param($stmt, "ssssssssssssi", $categories_id, $name, $oldprice, $price, $qty, $short_desc, $description, $meta_title, $meta_desc, $meta_keyword, $best_seller, $sub_categories_id, $id);
            }
            mysqli_stmt_execute($stmt);
        } else {
            $image = rand(111111111, 999999999) . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], PRODUCT_IMAGE_SERVER_PATH . $image);
            $insert_sql = "INSERT INTO product(categories_id, name, oldprice, price, qty, short_desc, description, meta_title, meta_desc, meta_keyword, status, image, best_seller, sub_categories_id, added_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $insert_sql);
            mysqli_stmt_bind_param($stmt, "sssssssssssssi", $categories_id, $name, $oldprice, $price, $qty, $short_desc, $description, $meta_title, $meta_desc, $meta_keyword, $image, $best_seller, $sub_categories_id, $_SESSION['ADMIN_ID']);
            mysqli_stmt_execute($stmt);
        }
        header('location:product.php');
        die();
    }
}
?>


<div class="content pb-0">
            <div class="animated fadeIn">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="card">
                        <div class="card-header"><strong>Product</strong><small> Form</small></div>
                        <form method="post" enctype="multipart/form-data">
							<div class="card-body card-block">
							   <div class="form-group">
									<label for="categories" class=" form-control-label">Categories</label>
									<select class="form-control" name="categories_id" id="categories_id" onchange="get_sub_cat('')" required>
										<option>Select Category</option>
										<?php
										$res=mysqli_query($con,"select id,categories from categories order by categories asc");
										while($row=mysqli_fetch_assoc($res)){
											if($row['id']==$categories_id){
												echo "<option selected value=".$row['id'].">".$row['categories']."</option>";
											}else{
												echo "<option value=".$row['id'].">".$row['categories']."</option>";
											}
											
										}
										?>
									</select>
								</div>
								
								<div class="form-group">
									<label for="categories" class=" form-control-label">Sub Categories</label>
									<select class="form-control" name="sub_categories_id" id="sub_categories_id">
										<option>Select Sub Category</option>
									</select>
								</div>
								
								<div class="form-group">
									<label for="categories" class=" form-control-label">Product Name</label>
									<input type="text" name="name" placeholder="Enter product name" class="form-control" required value="<?php echo $name?>">
								</div>
								<div class="form-group">
									<label for="categories" class=" form-control-label">Best Seller</label>
									<select class="form-control" name="best_seller" required>
										<option value=''>Select</option>
										<?php
										if($best_seller==1){
											echo '<option value="1" selected>Yes</option>
												<option value="0">No</option>';
										}elseif($best_seller==0){
											echo '<option value="1">Yes</option>
												<option value="0" selected>No</option>';
										}else{
											echo '<option value="1">Yes</option>
												<option value="0">No</option>';
										}
										?>
									</select>
								</div>
								<div class="form-group">
									<label for="categories" class=" form-control-label">Old Price</label>
									<input type="text" name="oldprice" placeholder="Enter product oldprice" class="form-control" required value="<?php echo $oldprice?>">
								</div>
								
								<div class="form-group">
									<label for="categories" class=" form-control-label">Price</label>
									<input type="text" name="price" placeholder="Enter product price" class="form-control" required value="<?php echo $price?>">
								</div>
								
								<div class="form-group">
									<label for="categories" class=" form-control-label">Qty</label>
									<input type="text" name="qty" placeholder="Enter qty" class="form-control" required value="<?php echo $qty?>">
								</div>
								
								<div class="form-group">
									<label for="categories" class=" form-control-label">Image</label>
									<input type="file" name="image" class="form-control" <?php echo  $image_required?>>
								</div>
								
								<div class="form-group">
									<label for="categories" class=" form-control-label">Short Description</label>
									<textarea name="short_desc" placeholder="Enter product short description" class="form-control" required><?php echo $short_desc?></textarea>
								</div>
								
								<div class="form-group">
									<label for="categories" class=" form-control-label">Description</label>
									<textarea name="description" placeholder="Enter product description" class="form-control" required><?php echo $description?></textarea>
								</div>
								
								<div class="form-group">
									<label for="categories" class=" form-control-label">Meta Title</label>
									<textarea name="meta_title" placeholder="Enter product meta title" class="form-control"><?php echo $meta_title?></textarea>
								</div>
								
								<div class="form-group">
									<label for="categories" class=" form-control-label">Meta Description</label>
									<textarea name="meta_desc" placeholder="Enter product meta description" class="form-control"><?php echo $meta_desc?></textarea>
								</div>
								
								<div class="form-group">
									<label for="categories" class=" form-control-label">Meta Keyword</label>
									<textarea name="meta_keyword" placeholder="Enter product meta keyword" class="form-control"><?php echo $meta_keyword?></textarea>
								</div>
								
								
							   <button id="payment-button" name="submit" type="submit" class="btn btn-lg btn-info btn-block">
							   <span id="payment-button-amount">Submit</span>
							   </button>
							   <div class="field_error"><?php echo $msg?></div>
							</div>
						</form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
		 
		 <script>
			function get_sub_cat(sub_cat_id){
				var categories_id=jQuery('#categories_id').val();
				jQuery.ajax({
					url:'get_sub_cat.php',
					type:'post',
					data:'categories_id='+categories_id+'&sub_cat_id='+sub_cat_id,
					success:function(result){
						jQuery('#sub_categories_id').html(result);
					}
				});
			}
		 </script>
         
<?php
require('footer.inc.php');/////////////////////////////////////////
?>
<script>
<?php
if(isset($_GET['id'])){
?>
get_sub_cat('<?php echo $sub_categories_id?>');
<?php } ?>
</script>