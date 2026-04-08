<?php
include 'includes/db.php';
session_start();

/* AUTH CHECK */
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$success = "";

/* ADD CAR */
if(isset($_POST['add'])){
    $name = $_POST['name'];
    $model = $_POST['model'];
    $price = $_POST['price'];
    $passengers = $_POST['passengers'];
    $image = $_POST['image'];

    $stmt = $conn->prepare("INSERT INTO cars(name,model,price,passengers,image) VALUES(?,?,?,?,?)");
    $stmt->bind_param("ssdis", $name, $model, $price, $passengers, $image);

    if($stmt->execute()){
        $success = "Car Added Successfully!";
    }
    $stmt->close();
}

/* DELETE CAR */
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM cars WHERE id=?");
    $stmt->bind_param("i", $id);

    if($stmt->execute()){
        $success = "Car Deleted Successfully!";
    }
    $stmt->close();
}

/* BOOKING APPROVE */
if(isset($_GET['approve'])){
    $id = (int)$_GET['approve'];
    $conn->query("UPDATE bookings SET status='Approved' WHERE id=$id");
    header("Location: admin.php");
}

/* BOOKING REJECT */
if(isset($_GET['reject'])){
    $id = (int)$_GET['reject'];
    $conn->query("UPDATE bookings SET status='Rejected' WHERE id=$id");
    header("Location: admin.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Panel</title>

<style>
body{
    margin:0;
    font-family:Segoe UI;
    background:linear-gradient(135deg,#141e30,#243b55);
    color:white;
}

/* NAVBAR */
.nav{
    background:#000;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.nav a{
    color:white;
    margin-left:15px;
    text-decoration:none;
    padding:8px 12px;
    border-radius:6px;
    background:#333;
}

.nav a:hover{
    background:#00c6ff;
    color:black;
}

/* CONTAINER */
.container{
    padding:30px;
}

h1,h2{
    text-align:center;
}

/* FORM */
.form-box{
    width:350px;
    margin:20px auto;
    background:white;
    color:black;
    padding:20px;
    border-radius:10px;
}

.form-box input, .form-box button{
    width:100%;
    padding:10px;
    margin:8px 0;
}

.form-box button{
    background:#28a745;
    color:white;
    border:none;
    cursor:pointer;
}

/* GRID */
.grid{
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
}

/* CARD */
.card{
    background:white;
    color:black;
    width:260px;
    margin:15px;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(0,0,0,0.4);
}

.card img{
    width:100%;
    height:150px;
    object-fit:cover;
}

.card-body{
    padding:15px;
}

/* DELETE BUTTON */
.delete-btn{
    display:block;
    text-align:center;
    background:red;
    color:white;
    padding:8px;
    margin-top:10px;
    text-decoration:none;
    border-radius:5px;
}

/* USERS */
.user{
    background:white;
    color:black;
    width:300px;
    margin:10px auto;
    padding:15px;
    border-radius:10px;
}

/* BOOKINGS */
.booking{
    background:white;
    color:black;
    width:320px;
    margin:10px auto;
    padding:15px;
    border-radius:10px;
}

.action a{
    margin-right:10px;
    text-decoration:none;
    padding:5px 8px;
    border-radius:5px;
    color:white;
}

.approve{ background:green; }
.reject{ background:red; }

.success{
    text-align:center;
    color:#00ffcc;
}
</style>

</head>

<body>

<!-- NAVBAR -->
<div class="nav">
    <div><b>👨‍💼 Admin Panel</b></div>
    <div>
        <a href="index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

<h1>Admin Dashboard</h1>

<?php if($success != ""){ ?>
    <p class="success"><?php echo $success; ?></p>
<?php } ?>

<!-- STATS -->
<h2>Statistics</h2>
<?php
$totalCars = $conn->query("SELECT COUNT(*) as c FROM cars")->fetch_assoc()['c'];
$totalUsers = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
$totalBookings = $conn->query("SELECT COUNT(*) as c FROM bookings")->fetch_assoc()['c'];
$pending = $conn->query("SELECT COUNT(*) as c FROM bookings WHERE status='Pending'")->fetch_assoc()['c'];
?>

<p style="text-align:center;">
Cars: <?php echo $totalCars; ?> |
Users: <?php echo $totalUsers; ?> |
Bookings: <?php echo $totalBookings; ?> |
Pending: <?php echo $pending; ?>
</p>

<!-- ADD CAR -->
<h2>Add New Car</h2>

<div class="form-box">
<form method="POST">
    <input type="text" name="name" placeholder="Car Name" required>
    <input type="text" name="model" placeholder="Model" required>
    <input type="number" name="price" placeholder="Price" required>
    <input type="number" name="passengers" placeholder="Passengers" required>
    <input type="text" name="image" placeholder="Image URL" required>
    <button name="add">Add Car</button>
</form>
</div>

<!-- CARS -->
<h2>Cars List</h2>

<div class="grid">
<?php
$res = $conn->query("SELECT * FROM cars");

while($row = $res->fetch_assoc()){
?>
<div class="card">
    <img src="<?php echo $row['image']; ?>">
    <div class="card-body">
        <h3><?php echo $row['name']; ?></h3>
        <p><?php echo $row['model']; ?></p>
        <p>$<?php echo $row['price']; ?></p>
        <p><?php echo $row['passengers']; ?> Passengers</p>

        <a class="delete-btn" href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this car?')">Delete</a>
    </div>
</div>
<?php } ?>
</div>

<!-- BOOKINGS -->
<h2>Bookings</h2>

<?php
$res = $conn->query("SELECT bookings.*, users.name AS user_name, cars.name AS car_name 
FROM bookings
JOIN users ON bookings.user_id = users.id
JOIN cars ON bookings.car_id = cars.id");

while($row = $res->fetch_assoc()){
?>
<div class="booking">
    <p><b>User:</b> <?php echo $row['user_name']; ?></p>
    <p><b>Car:</b> <?php echo $row['car_name']; ?></p>
    <p><b>Date:</b> <?php echo $row['booking_date']; ?></p>
    <p><b>Status:</b> <?php echo $row['status']; ?></p>

    <div class="action">
        <a class="approve" href="?approve=<?php echo $row['id']; ?>">Approve</a>
        <a class="reject" href="?reject=<?php echo $row['id']; ?>">Reject</a>
    </div>
</div>
<?php } ?>

<!-- USERS -->
<h2>Users</h2>

<?php
$res = $conn->query("SELECT * FROM users");

while($row = $res->fetch_assoc()){
?>
<div class="user">
    <b>Name:</b> <?php echo $row['name']; ?><br>
    <b>Email:</b> <?php echo $row['email']; ?><br>
    <b>Password:</b> <span style="color:gray;">••••••••</span>
</div>
<?php } ?>

</div>

</body>
</html>