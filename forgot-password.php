<?php
require_once("./app/init.php");

if(isset($_POST['forgot'])) {
    $email = $_POST['email'];
    $user = User::findByEmail($connection,$email);
    if($user) {
        $tokenData = Token::createForgotPasswordToken($connection,$user['id']);
        if($tokenData) {
            $url = AppConfig::getInstance()->APP_URL;
            $token = $tokenData['token'];
            $url = $url . "reset-password.php?t=$token";
            $mail = Mail::getMailer();
            $mail->addAddress($user['email']);
            $mail->Subject = 'Reset Link';
            $mail->Body = <<<MAIL_BODY
                <p>Use the below link to reset your password, The link will only be valid for 10 minutes</p>
                <p><a href="$url" target="_blank">Click Here</a></p>
                MAIL_BODY;
            
                if($mail->send()) {
                    die("We have sent yoy reset link!Please check your mail!");
                }else {
                    die("Issue with your mail config!");
                }
        }else {
            die("Issue with Token Genertaion ! Critical Error");
        }
    }else {
        dd("No such user Found!");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/responsive.css">
</head>
<body>
    <div class="d-flex flex-center forgot-container">
        <div class="forgot-card d-flex flex-center flex-column">
            <img src="/images/Lock.svg" alt="" class="img-responsive">
            <h2 class="form-container__title">Forgot Password ?</h2>
            <p class="text-center forgot-info mt-1">Enter the email address associated with your account, and we'll send you a link to reset your password.</p>

            <!-- FORM CONTAINER STARTS -->
            <div class="forgot-form">
                <!-- FORM STARTS -->
                <form action="<?=$_SERVER['PHP_SELF'];?>" method="POST" class="mt-3">
                    <div class="input-container d-flex flex-column">
                        <label for="email" class="input-label">Email</label>
                        <input type="text" name="email" id="email" class="input-field" placeholder="Enter your email">
                        <!-- <span class="error-message" id="emailError">inavlid email</span> -->
                    </div>
                    <button type="submit" name="forgot" class="btn btn-primary">Send Reset Link</button>
                </form>
                <!-- FORM ENDS -->
                <p class="create-account">Never mind <a href="login.html" class="text-primary">Take me back to login</a></p>
            </div>
            <!-- FORM CONTAINER ENDS -->
        </div>
    </div>
</body>
</html>