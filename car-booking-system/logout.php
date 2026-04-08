<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
<title>Logout</title>

<style>
body{
    margin:0;
    font-family:Segoe UI;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#141e30,#243b55);
    color:white;
}

.box{
    text-align:center;
    background:rgba(255,255,255,0.1);
    padding:30px;
    border-radius:15px;
    backdrop-filter:blur(10px);
    box-shadow:0 10px 30px rgba(0,0,0,0.4);
}

a{
    display:inline-block;
    margin-top:15px;
    padding:10px 20px;
    background:#00c6ff;
    color:black;
    text-decoration:none;
    border-radius:8px;
    font-weight:bold;
}

a:hover{
    background:#0072ff;
    color:white;
}
</style>

</head>

<body>

<div class="box">
    <h2>🔒 You have been logged out</h2>
    <p>Thank you for using the system.</p>
    <a href="login.php">Go to Login</a>
</div>

</body>
</html>