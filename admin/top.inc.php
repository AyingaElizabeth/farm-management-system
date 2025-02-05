<?php
ob_start();
require('connection.inc.php');
require('functions.inc.php');

if(!isset($_SESSION['ADMIN_LOGIN']) || $_SESSION['ADMIN_LOGIN'] == ''){
    header('location:login.php');
    exit();
}

$admin_username = htmlspecialchars($_SESSION['ADMIN_USERNAME']);
$admin_role = isset($_SESSION['ADMIN_ROLE']) ? $_SESSION['ADMIN_ROLE'] : 0;

// Don't close the PHP tag here
// Function to output menu items based on admin role
function outputMenuItem($href, $text) {
    echo "<li class=\"menu-item-has-children dropdown\"><a href=\"$href\">$text</a></li>";
}
?>
<!doctype html>
<html class="no-js" lang="">
   <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Dashboard Page</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="assets/css/normalize.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/font-awesome.min.css">
      <link rel="stylesheet" href="assets/css/themify-icons.css">
      <link rel="stylesheet" href="assets/css/pe-icon-7-filled.css">
      <link rel="stylesheet" href="assets/css/flag-icon.min.css">
      <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
      <link rel="stylesheet" href="assets/css/style.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
   </head>
   <body>
      <aside id="left-panel" class="left-panel">
         <nav class="navbar navbar-expand-sm navbar-default">
            <div id="main-menu" class="main-menu collapse navbar-collapse">
               <ul class="nav navbar-nav">
                  <li class="menu-title">Menu</li>
                  <li class="menu-item-has-children dropdown">
                     <?php
                     if($_SESSION['ADMIN_ROLE'] ==1){
                        echo '<a href="vendor_index.php" > Home</a>';
                     }else{
                        echo '<a href="index.php" > Home</a>';
                     }
                     ?>
                  </li>
                  
				  <li class="menu-item-has-children dropdown">
                     <a href="product.php" > Product Master</a>
                  </li>
				  
              <?php 
					 if($_SESSION['ADMIN_ROLE']==1){?>
                  <li class="menu-item-has-children dropdown">
						<a href="order_master_vendor.php" > Order Master</a>
                  </li>
                  <li class="menu-item-has-children dropdown">
                     <a href="weather.php" > Weather Updates</a>
                  </li> 
                
                <li class="menu-item-has-children dropdown">
                    <a href="resources_blog.php">Common Advisory Extension</a>
                  </li>
					 <?php } ?>
                  
                  


				  <?php if($_SESSION['ADMIN_ROLE']!=1){?>
               <li class="menu-item-has-children dropdown">
               <a href="order_master.php" > Order Master</a>
               </li>
				   <li class="menu-item-has-children dropdown">
                     <a href="vendor_management.php" > Vendor Management</a>
                  </li>
				  <li class="menu-item-has-children dropdown">
                     <a href="categories.php" > Categories Master</a>
                  </li>
				  <li class="menu-item-has-children dropdown">
                     <a href="sub_categories.php" > Sub Categories Master</a>
                  </li>
                  
				  <li class="menu-item-has-children dropdown">
                     <a href="users.php" > User Master</a>
                  </li>
				  <li class="menu-item-has-children dropdown">
                     <a href="coupon_master.php" > Coupon Master</a>
                  </li>
				  <li class="menu-item-has-children dropdown">
                     <a href="contact_us.php" > Contact Us</a>
                  </li>
               
                  <li class="menu-item-has-children dropdown">
                     <a href="weather.php" > Weather Updates</a>
                  </li> 
                <li class="menu-item-has-children dropdown">
                    <a href="resources_blog.php">Common Advisory Extension</a>
                </li>
				  <?php } ?>
              <li class="menu-item-has-children dropdown">
                    <a href="agriNote_view.php">Write A Record</a>
                </li>
               </ul>
            </div>
         </nav>
      </aside>
      <div id="right-panel" class="right-panel">
         <header id="header" class="header">
            <div class="top-left">
               <div class="navbar-header">
                  <a class="navbar-brand" href="index.php"><img src="images/logo5.png" alt="Logo"></a>
                  <a class="navbar-brand hidden" href="index.php"><img src="images/logo2.png" alt="Logo"></a>
                  <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
               </div>
            </div>
            <div class="top-right">
               <div class="header-menu">
                  <div class="user-area dropdown float-right">
                     <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Welcome <?php echo $_SESSION['ADMIN_USERNAME']?></a>
                     <div class="user-menu dropdown-menu">
                        <a class="nav-link" href="logout.php"><i class="fa fa-power-off"></i>Logout</a>
                     </div>
                  </div>
               </div>
            </div>
         </header>
</html>