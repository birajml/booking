<?php
include 'includes/db.php';
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

/* AJAX STATUS UPDATE */
if(isset($_POST['updateStatus'])){
    $id = (int)$_POST['id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE bookings SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    echo "success";
    exit();
}

/* ADD CAR */
if(isset($_POST['add'])){
    $stmt = $conn->prepare("INSERT INTO cars(name,model,price,passengers,image) VALUES(?,?,?,?,?)");
    $stmt->bind_param("ssdis", $_POST['name'], $_POST['model'], $_POST['price'], $_POST['passengers'], $_POST['image']);
    $stmt->execute();
}

/* DELETE CAR */
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM cars WHERE id=$id");
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
    display:flex;
    background:#0f172a;
    color:white;
}

/* SIDEBAR */
.sidebar{
    width:230px;
    height:100vh;
    background:#020617;
    padding:20px;
}

.sidebar h2{
    text-align:center;
    margin-bottom:20px;
}

.sidebar a{
    display:block;
    padding:12px;
    margin:10px 0;
    text-decoration:none;
    color:#cbd5f5;
    border-radius:8px;
}

.sidebar a:hover{
    background:#2563eb;
    color:white;
}

/* MAIN */
.main{
    flex:1;
    padding:25px;
}

/* TITLE */
h1{
    text-align:center;
    margin-bottom:20px;
}

/* CARD COMMON */
.card{
    background:#1e293b;
    border-radius:12px;
    padding:15px;
    margin:10px;
    box-shadow:0 5px 20px rgba(0,0,0,0.4);
}

/* GRID */
.grid{
    display:flex;
    flex-wrap:wrap;
    gap:15px;
}

/* CAR CARD */
.car{
    width:240px;
}

.car img{
    width:100%;
    height:140px;
    border-radius:10px;
}

/* BUTTONS */
.btn{
    display:block;
    text-align:center;
    padding:8px;
    border-radius:6px;
    margin-top:8px;
    text-decoration:none;
    color:white;
    font-size:14px;
}

.delete{background:#ef4444;}
.approve{background:#22c55e;}
.reject{background:#f97316;}

/* BOOKING CARD */
.booking{
    max-width:420px;
    margin:15px auto;
    background:#1e293b;
    border-radius:12px;
    padding:18px;
    box-shadow:0 8px 25px rgba(0,0,0,0.5);
}

/* ROW */
.row{
    display:flex;
    justify-content:space-between;
    margin:5px 0;
}

/* STATUS */
.status{
    padding:5px 12px;
    border-radius:20px;
    font-size:13px;
}

.pending{background:#64748b;}
.approved{background:#22c55e;}
.rejected{background:#ef4444;}

/* ACTION BUTTONS */
.actions{
    display:flex;
    gap:10px;
    margin-top:10px;
}

.actions button{
    flex:1;
    border:none;
    padding:8px;
    border-radius:6px;
    cursor:pointer;
    color:white;
}

.actions .approve{background:#22c55e;}
.actions .reject{background:#ef4444;}

/* FORM */
.form{
    max-width:320px;
    margin:20px auto;
}

.form input{
    width:100%;
    padding:10px;
    margin:6px 0;
    border:none;
    border-radius:6px;
}
</style>

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Admin</h2>
    <a href="index.php">🏠 Home</a>
    <a href="dashboard.php">📊 Dashboard</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<!-- MAIN -->
<div class="main">

<h1>Admin Dashboard</h1>

<!-- ADD CAR -->
<div class="card form">
<h3>Add Car</h3>
<form method="POST">
<input name="name" placeholder="Car Name" required>
<input name="model" placeholder="Model" required>
<input name="price" placeholder="Price" required>
<input name="passengers" placeholder="Passengers" required>
<input name="image" placeholder="Image URL" required>
<button name="add">Add Car</button>
</form>
</div>

<!-- CARS -->
<h2>Cars</h2>
<div class="grid">
<?php
$res=$conn->query("SELECT * FROM cars");
while($row=$res->fetch_assoc()){
?>
<div class="card car">
<img src="<?php echo $row['image']; ?>">
<h3><?php echo $row['name']; ?></h3>
<p><?php echo $row['model']; ?></p>
<p>$<?php echo $row['price']; ?></p>
<a class="btn delete" href="?delete=<?php echo $row['id']; ?>">Delete</a>
</div>
<?php } ?>
</div>

<!-- BOOKINGS -->
<h2>Booking Details</h2>

<?php
$res=$conn->query("SELECT b.*,u.name as user,c.name as car 
FROM bookings b
JOIN users u ON b.user_id=u.id
JOIN cars c ON b.car_id=c.id");

while($row=$res->fetch_assoc()){
$statusClass=strtolower($row['status']);
?>
<div class="booking" id="row<?php echo $row['id']; ?>">

<div class="row"><b>User:</b> <span><?php echo $row['user']; ?></span></div>
<div class="row"><b>Car:</b> <span><?php echo $row['car']; ?></span></div>
<div class="row"><b>Date:</b> <span><?php echo $row['booking_date']; ?></span></div>

<div class="row">
<b>Status:</b>
<span id="status<?php echo $row['id']; ?>" class="status <?php echo $statusClass; ?>">
<?php echo $row['status']; ?>
</span>
</div>

<div class="actions">
<button class="approve" onclick="updateStatus(<?php echo $row['id']; ?>,'Approved')">Approve</button>
<button class="reject" onclick="updateStatus(<?php echo $row['id']; ?>,'Rejected')">Reject</button>
</div>

</div>
<?php } ?>

<!-- USERS -->
<h2>Users</h2>
<?php
$res=$conn->query("SELECT * FROM users");
while($row=$res->fetch_assoc()){
?>
<div class="booking">
<b><?php echo $row['name']; ?></b><br>
<?php echo $row['email']; ?><br>
Password: ••••••••
</div>
<?php } ?>

</div>

<script>
function updateStatus(id,status){

    if(!confirm("Are you sure?")) return;

    let formData = new FormData();
    formData.append("updateStatus", true);
    formData.append("id", id);
    formData.append("status", status);

    fetch("admin.php",{
        method:"POST",
        body:formData
    })
    .then(res=>res.text())
    .then(data=>{
        if(data.trim()=="success"){
            let el=document.getElementById("status"+id);
            el.innerText=status;
            el.className="status " + status.toLowerCase();
        }
    });
}
</script>

</body>
</html>