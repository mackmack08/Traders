<?php
session_start();

$page_title="Reset Password";
include("includes/header.php"); 
include("includes/navbar.php");
 
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                <?php
                    if (isset($_SESSION['status'])) 
                    {
                        ?>
                        <div class="alert alert-success">
                            <h5><?= $_SESSION['status']; ?></h5>
                        </div>
                        <?php
                        unset($_SESSION['status']);
                    }
                    ?>
                    <div class="card-header">
                        <h5>Reset Password</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="password_reset_code.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="text" name="email" class="form-control" placeholder="Enter Email Address">
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" name="password_reset" class="btn btn-primary">Send Reset Password link</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>    
        </div>
    </div>
</div>
