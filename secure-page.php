<?php
require_once("./app/init.php");
if(!Auth::check()) {
    redirect('login.php');
}
if(isset($_POST['logout'])) {
    Auth::logout();
    redirect('login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auth | Home</title>
    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/responsive.css">
</head>
<body>
    <header class="d-flex flex-space-between">
        <h3>Welcome, <?=Auth::user()['username'];?></h3>
        <nav>
            <ul class="d-flex">
                <li class="link"><a href="#">Reset Password</a></li>
                <li class="link"><a href="#">Verify user</a></li>
            </ul>
        </nav>

        <form action="<?=$_SERVER['PHP_SELF'];?>" method="POST">
            <button class="logout-btn" type="submit" name="logout">Log out</button>
        </form>
        
    </header>

    <section class="info d-grid grid-cols-2">
        <div class="d-flex flex-column justify-center security-info">
            <h2>What is Authentication ?</h2>
            <p class="text-justify mt-1">
                Authentication, in simple terms, is the process of verifying the identity of a user, device, or system. It ensures that the entity trying to access a particular resource or service is who or what it claims to be.
            </p>
            <p class="mt-1 text-justify">
                Think of it like showing your ID card at the entrance of a secure building. The security guard checks your ID card to confirm that you are the person you claim to be before granting you access.
            </p>
            <p class="mt-1 text-justify">
                In the digital world, authentication often involves providing a username and password. When you log in to your email or social media account, you're authenticating yourself by entering the correct username (or email) and password. Other methods of authentication include fingerprint scans, face recognition, or using security tokens.
            </p>
            <p class="mt-1 text-justify">
                Authentication is a crucial part of security measures to protect your personal information and ensure that only authorized individuals or systems can access specific resources or services.
            </p>
        </div>
        <img src="images/Security.svg" alt="security image" class="img-responsive security-image">
    </section>
</body>
</html>