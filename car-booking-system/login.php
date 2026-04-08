<?php
include 'includes/db.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = "";

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $res = $conn->query($sql);

    if($res && $res->num_rows > 0){
        $row = $res->fetch_assoc();

        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['name'];

        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid Email or Password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<style>

/* BACKGROUND */
body{
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:Segoe UI;
    background:linear-gradient(135deg,#667eea,#764ba2);
}

/* GLASS CONTAINER */
.login-box{
    width:350px;
    padding:30px;
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(10px);
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
    text-align:center;
    color:white;
}

/* INPUT */
input{
    width:100%;
    padding:12px;
    margin:10px 0;
    border:none;
    border-radius:8px;
}

/* BUTTON */
button{
    width:100%;
    padding:12px;
    background:#00c6ff;
    border:none;
    border-radius:8px;
    color:#000;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#0072ff;
    color:white;
}

/* ERROR */
.error{
    color:#ff4d4d;
    margin-bottom:10px;
}

</style>

</head>
<body>

<div class="login-box">

    <h2>🔐 Login</h2>
    <p>Access your car booking dashboard</p>

    <?php if($error != ""){ ?>
        <div class="error"><?php echo $error; ?></div>
    <?php } ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Enter Email" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button name="login">Login</button>
    </form>

    <p style="margin-top:15px;">
        Don't have account? <a href="register.php" style="color:#fff;">Register</a>
    </p>

</div>

</body>
</html>