<?php
require_once('top.php');
// Note: connection.inc.php and functions.inc.php should be included in top.php

$msg = '';

if (isset($_POST['submit'])) {
    $name = get_safe_value($con, $_POST['name']);
    $email = get_safe_value($con, $_POST['email']);
    $mobile = get_safe_value($con, $_POST['mobile']);
    $password = get_safe_value($con, $_POST['password']);

    $check_user = mysqli_num_rows(mysqli_query($con, "SELECT * FROM users WHERE email='$email'"));
    $check_mobile = mysqli_num_rows(mysqli_query($con, "SELECT * FROM users WHERE mobile='$mobile'"));

    if ($check_user > 0) {
        $msg = "Email already exists";
    } elseif ($check_mobile > 0) {
        $msg = "Mobile number already exists";
    } else {
        $added_on = date('Y-m-d h:i:s');
        $insert_sql = "INSERT INTO users(name, email, mobile, password, added_on) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $insert_sql);
        mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $mobile, $password, $added_on);

        if (mysqli_stmt_execute($stmt)) {
            $msg = "Registration successful";
        } else {
            $msg = "Registration failed: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    }
}

?>
<!-- Start Bradcaump area -->
        <div class="ht__bradcaump__area" style="background: rgba(0, 0, 0, 0) url(images/bg/4.jpg) no-repeat scroll center center / cover ;">
            <div class="ht__bradcaump__wrap">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="bradcaump__inner">
                                <nav class="bradcaump-inner">
                                  <a class="breadcrumb-item" href="index.php">Home</a>
                                  <span class="brd-separetor"><i class="zmdi zmdi-chevron-right"></i></span>
                                  <span class="breadcrumb-item active">Login/Register</span>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Bradcaump area -->
        
		<!-- Start Contact Area -->
       
<!-- Start Contact Area -->
<section class="htc__contact__area ptb--100 bg__white">
    <div class="container">
	<div class="container">
                <div class="row">
					<div class="col-md-6">
						<div class="contact-form-wrap mt--60">
							<div class="col-xs-12">
								<div class="contact-title">
									<h2 class="title__line--6">Login</h2>
								</div>
							</div>
							<div class="col-xs-12">
								<form id="login-form" method="post">
									<div class="single-contact-form">
										<div class="contact-box name">
											<input type="text" name="login_email" id="login_email" placeholder="Your Email*" style="width:100%">
										</div>
										<span class="field_error" id="login_email_error"></span>
									</div>
									<div class="single-contact-form">
										<div class="contact-box name">
											<input type="password" name="login_password" id="login_password" placeholder="Your Password*" style="width:100%">
										</div>
										<span class="field_error" id="login_password_error"></span>
									</div>
									
									<div class="contact-btn">
										<button type="button" class="fv-btn" onclick="user_login()">Login</button>
										<a href="forgot_password.php" class="forgot_password">Forgot Password</a>
									</div>
								</form>
								<div class="form-output login_msg">
									<p class="form-messege field_error"></p>
								</div>
							</div>
						</div> 
                
				</div>
				<div class="row">
            <div class="col-md-6">
                <div class="contact-form-wrap mt--60">
                    <div class="col-xs-12">
                        <div class="contact-title">
                            <h2 class="title__line--6">Register</h2>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <form id="register-form" method="post" action="">
                            <div class="single-contact-form">
                                <div class="contact-box name">
                                    <input type="text" name="name" placeholder="Your Name*" style="width:100%" required>
                                </div>
                            </div>
                            <div class="single-contact-form">
                                <div class="contact-box name">
                                    <input type="email" name="email" placeholder="Your Email*" style="width:100%" required>
                                </div>
                            </div>
                            <div class="single-contact-form">
                                <div class="contact-box name">
                                    <input type="text" name="mobile" placeholder="Your Mobile*" style="width:100%" required>
                                </div>
                            </div>
                            <div class="single-contact-form">
                                <div class="contact-box name">
                                    <input type="password" name="password" placeholder="Your Password*" style="width:100%" required>
                                </div>
                            </div>
                            
                            <div class="contact-btn">
                                <button type="submit" name="submit" class="fv-btn">Register</button>
                            </div>
                        </form>
                        <div class="form-output">
                            <p class="form-messege"><?php echo $msg; ?></p>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</section>
<?php require('footer.php'); ?>