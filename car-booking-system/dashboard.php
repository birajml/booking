<?php
include 'includes/db.php';
include 'includes/auth.php';

$user_id = $_SESSION['user_id'];

/* DELETE */
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM bookings WHERE id=$id");
    header("Location: dashboard.php");
    exit();
}

/* UPDATE */
if(isset($_POST['update'])){
    $id = (int)$_POST['id'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    $conn->query("UPDATE bookings 
                  SET booking_date='$date', status='$status' 
                  WHERE id=$id");

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<style>

/* BACKGROUND */
body{
    margin:0;
    font-family:Segoe UI;
    background:linear-gradient(135deg,#1d2b64,#f8cdda);
}

/* NAV */
.nav{
    background:#111;
    color:#fff;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
}

.nav a{
    color:#fff;
    margin-left:15px;
    text-decoration:none;
}

/* CONTAINER */
.container{
    padding:30px;
}

/* TITLE */
h1{
    text-align:center;
    color:white;
}

/* CARD */
.card{
    background:white;
    padding:20px;
    margin:15px auto;
    width:350px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.3);
}

/* INPUT */
input,select{
    width:100%;
    padding:8px;
    margin:5px 0;
}

/* BUTTON */
button{
    padding:8px;
    border:none;
    background:#007bff;
    color:white;
    border-radius:5px;
    cursor:pointer;
}

/* DELETE BUTTON */
.delete{
    background:#dc3545;
    padding:6px 10px;
    color:white;
    text-decoration:none;
    border-radius:5px;
}

/* STATUS */
.status{
    padding:5px 10px;
    color:white;
    border-radius:5px;
    font-size:12px;
}

</style>

</head>

<body>

<!-- NAVBAR -->
<div class="nav">
    <div>🚗 Dashboard</div>
    <div>
        <a href="index.php">Home</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

<h1>Your Bookings</h1>

<?php
$sql = "SELECT bookings.*, cars.name, cars.model, cars.price
        FROM bookings
        JOIN cars ON bookings.car_id = cars.id
        WHERE bookings.user_id = $user_id";

$res = $conn->query($sql);

if($res && $res->num_rows > 0){
while($row = $res->fetch_assoc()){

    // STATUS COLOR
    $color = "gray";
    if($row['status']=="Pending") $color="orange";
    if($row['status']=="Completed") $color="green";
    if($row['status']=="Cancelled") $color="red";
?>

<div class="card">

<h3><?php echo $row['name']; ?></h3>
<p><b>Model:</b> <?php echo $row['model']; ?></p>
<p><b>Price:</b> $<?php echo $row['price']; ?></p>

<p>
Status:
<span class="status" style="background:<?php echo $color; ?>">
<?php echo $row['status']; ?>
</span>
</p>

<!-- EDIT FORM -->
<form method="POST">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    <label>Date:</label>
    <input type="date" name="date" value="<?php echo $row['booking_date']; ?>">

    <label>Status:</label>
    <select name="status">
        <option <?php if($row['status']=="Pending") echo "selected"; ?>>Pending</option>
        <option <?php if($row['status']=="Completed") echo "selected"; ?>>Completed</option>
        <option <?php if($row['status']=="Cancelled") echo "selected"; ?>>Cancelled</option>
    </select>

    <button name="update">Update</button>
</form>

<br>

<!-- DELETE -->
<a class="delete" 
   href="dashboard.php?delete=<?php echo $row['id']; ?>" 
   onclick="return confirm('Are you sure to delete?')">
   Delete
</a>

</div>

<?php 
}
} else {
    echo "<p style='text-align:center;color:white;'>No bookings found</p>";
}
?>

</div>

</body>
</html>