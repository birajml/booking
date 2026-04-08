<?php
include 'includes/db.php';
include 'includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
$user = $_SESSION['user_id'] ?? 0;

$error = "";
$success = "";

/* HANDLE FORM */
if(isset($_POST['book'])){

    $date = $_POST['date'] ?? '';
    $pickup_time = $_POST['pickup_time'] ?? '';
    $drop_time = $_POST['drop_time'] ?? '';
    $pickup_location = $_POST['pickup_location'] ?? '';
    $drop_location = $_POST['drop_location'] ?? '';
    $notes = $_POST['notes'] ?? '';

    if($date == "" || $pickup_time == "" || $drop_time == "" || $pickup_location == "" || $drop_location == ""){
        $error = "All required fields must be filled!";
    } else {

        // check duplicate booking
        $check = $conn->query("SELECT * FROM bookings WHERE car_id=$id AND booking_date='$date'");

        if($check && $check->num_rows > 0){
            $error = "Car already booked for selected date!";
        } else {

            $stmt = $conn->prepare("INSERT INTO bookings(user_id,car_id,booking_date,status)
                                   VALUES(?,?,?,'Pending')");
            $stmt->bind_param("iis", $user, $id, $date);

            if($stmt->execute()){
                $success = "Booking Successful! Status: Pending";
            } else {
                $error = "Something went wrong!";
            }

            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Book Car</title>

<style>
body{
    margin:0;
    font-family:Segoe UI;
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    color:white;
}

/* NAV BACK */
.back{
    position:absolute;
    top:20px;
    left:20px;
    background:#00c6ff;
    color:black;
    padding:8px 12px;
    border-radius:6px;
    text-decoration:none;
    font-weight:bold;
}

/* CENTER */
.container{
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

/* CARD */
.card{
    background:rgba(255,255,255,0.1);
    padding:25px;
    border-radius:12px;
    width:380px;
    backdrop-filter:blur(10px);
    box-shadow:0 10px 25px rgba(0,0,0,0.5);
}

/* INPUT */
input, textarea{
    width:100%;
    padding:10px;
    margin:8px 0;
    border:none;
    border-radius:5px;
}

/* BUTTON */
button{
    width:100%;
    padding:10px;
    border:none;
    border-radius:5px;
    background:#00ffcc;
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    background:#00c6ff;
}

/* MESSAGE */
.error{color:red;}
.success{color:#00ffcc;}
</style>

</head>

<body>

<a href="javascript:history.back()" class="back">⬅ Back</a>

<div class="container">
<div class="card">

<h2>🚗 Book Your Car</h2>

<?php if($error) echo "<p class='error'>$error</p>"; ?>
<?php if($success) echo "<p class='success'>$success</p>"; ?>

<form method="POST" onsubmit="return validateForm()">

    <label>Date</label>
    <input type="date" name="date" id="date" required>

    <label>Pickup Time</label>
    <input type="time" name="pickup_time" required>

    <label>Drop Time</label>
    <input type="time" name="drop_time" required>

    <label>Pickup Location</label>
    <input type="text" name="pickup_location" placeholder="Enter pickup location" required>

    <label>Drop Location</label>
    <input type="text" name="drop_location" placeholder="Enter drop location" required>

    <label>Special Notes</label>
    <textarea name="notes" placeholder="Optional..."></textarea>

    <button name="book">Confirm Booking</button>
</form>

</div>
</div>

<script>
function validateForm(){

    let date = document.getElementById("date").value;
    let today = new Date();
    let selected = new Date(date);

    today.setHours(0,0,0,0);

    if(selected < today){
        alert("Past dates are not allowed!");
        return false;
    }

    return true;
}
</script>

</body>
</html>