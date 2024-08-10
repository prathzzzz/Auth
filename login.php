<?php
require_once('./app/init.php');

if(isset($_POST['login'])) {
    if(Auth::login($connection,$_POST['username'],$_POST['password'])) {
        redirect('secure-page.php');
    }
    dd("Wrong Username/Password");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auth | Login</title>

    <!-- CUSTOM STYLE -->
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/responsive.css">
</head>
<body>
    <div class="container">
        <div class="d-grid grid-cols-2 main-container">
            <!-- VISUAL CONTAINER STARTS -->
            <div class="visual-container bg-primary d-flex flex-column flex-center">
                <h2 class="visual-container__title">
                    Step into a visual login experience like never before.
                </h2>
                <p class="visual-container__subtitle">
                    Unlock a world of secure access. Safeguarding your data with our robust authentication system.
                </p>
                <img src="images/login.svg" alt="login image" class="img-responsive visual-container__image">
            </div>
            <!-- VISUAL CONTAINER ENDS -->
            <!-- FORM CONTAINER STARTS -->
            <div class="form-container d-flex flex-column">
                <h2 class="form-container__title">Welcome Back!</h2>
                <p class="form-container__subtitle">
                    Enter your details to access your account
                </p>
                <!-- FORM STARTS -->
                <form action="<?=$_SERVER['PHP_SELF'];?>"  method="POST" class="mt-3">
                    <div class="input-container d-flex flex-column">
                        <label for="username" class="input-label">Username</label>
                        <input type="text" name="username" id="username" class="input-field" placeholder="Enter your username">
                        <!-- <span class="error-message" id="usernameError">inavlid username</span> -->
                    </div>
                    <div class="input-container d-flex flex-column">
                        <label for="password" class="input-label">Password</label>
                        <input type="password" name="password" id="password" class="input-field" placeholder="Enter your password">
                        <!-- <span class="error-message" id="passwordError">inavlid password</span> -->
                    </div>
                    <div class="input-container d-flex flex-space-between">
                        <div class="d-flex items-center">
                            <input type="checkbox" name="rememberme" id="rememberme" class="checkbox">
                            <p class="rememberme-text">Remember me</p>
                        </div>
                        <p class="forgot-password"><a href="forgot-password.php">Forgot Password?</a></p>
                    </div>
                    <button type="submit"  name="login" class="btn btn-primary">Login</button>
                </form>
                <!-- FORM ENDS -->
                <p class="create-account">Don't have an account? <a href="register.php" class="text-primary">Create account</a></p>
            </div>
            <!-- FORM CONTAINER ENDS -->
        </div>
    </div>
</body>
</html>