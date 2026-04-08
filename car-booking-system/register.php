<?php
include 'includes/db.php';

$message = "";
$error = "";

if(isset($_POST['register'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = md5($_POST['password']);

    // SERVER VALIDATION
    if(empty($name) || empty($email) || empty($_POST['password'])){
        $error = "All fields are required!";
    } else {

        $sql = "INSERT INTO users(name,email,password)
                VALUES('$name','$email','$password')";

        if($conn->query($sql)){
            $message = "Registration Successful!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>

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

/* CONTAINER */
.box{
    width:350px;
    padding:25px;
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(10px);
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
    color:white;
    text-align:center;
}

/* INPUT */
input{
    width:100%;
    padding:12px;
    margin:10px 0;
    border:none;
    border-radius:8px;
    outline:none;
}

/* BUTTON */
button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background:#00c6ff;
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    background:#0072ff;
    color:white;
}

/* MESSAGES */
.success{
    color:#00ffcc;
    margin-bottom:10px;
}

.error{
    color:#ff4d4d;
    margin-bottom:10px;
}

a{
    color:white;
    text-decoration:underline;
}

</style>

</head>

<body>

<div class="box">

<h2>📝 Register</h2>
<p>Create your account</p>

<?php if($message){ ?>
    <p class="success"><?php echo $message; ?></p>
<?php } ?>

<?php if($error){ ?>
    <p class="error"><?php echo $error; ?></p>
<?php } ?>

<form method="POST" onsubmit="return validateForm()">
    <input type="text" id="name" name="name" placeholder="Full Name">
    <input type="email" id="email" name="email" placeholder="Email">
    <input type="password" id="password" name="password" placeholder="Password (min 6 chars)">
    <button name="register">Register</button>
</form>

<p>Already have account? <a href="login.php">Login</a></p>

</div>

<script>
function validateForm(){
    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    if(name === "" || email === "" || password === ""){
        alert("All fields are required!");
        return false;
    }

    if(password.length < 6){
        alert("Password must be at least 6 characters");
        return false;
    }

    return true;
}
</script>

</body>
</html>