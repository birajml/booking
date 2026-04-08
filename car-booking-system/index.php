<?php 
include 'includes/db.php'; 
include 'includes/auth.php';
?>

<!DOCTYPE html>
<html>
<head>
<title>Car Booking System</title>

<style>

/* RESET */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Segoe UI;
}

/* BODY */
body{
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    color:white;
}

/* NAVBAR */
.nav{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 30px;
    background:#000;
}

.nav .menu a{
    color:white;
    margin-left:15px;
    text-decoration:none;
    font-weight:500;
}

.nav .menu a:hover{
    color:#00c6ff;
}

/* HERO */
.hero{
    text-align:center;
    padding:60px 20px;
}

.hero h1{
    font-size:40px;
    margin-bottom:10px;
}

.hero p{
    opacity:0.8;
}

/* SEARCH */
.search-box{
    text-align:center;
    margin:20px;
}

.search-box input, .search-box select{
    padding:10px;
    margin:5px;
    border:none;
    border-radius:6px;
}

.search-box button{
    padding:10px 15px;
    border:none;
    background:#00c6ff;
    border-radius:6px;
    cursor:pointer;
}

.search-box button:hover{
    background:#0072ff;
    color:white;
}

/* GRID */
.container{
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    padding:20px;
}

/* CARD */
.card{
    width:280px;
    margin:15px;
    background:white;
    color:black;
    border-radius:12px;
    overflow:hidden;
    transition:0.3s;
    box-shadow:0 10px 25px rgba(0,0,0,0.4);
}

.card:hover{
    transform:translateY(-10px);
}

.card img{
    width:100%;
    height:180px;
    object-fit:cover;
}

/* CARD BODY */
.card-body{
    padding:15px;
}

.card-body h3{
    margin-bottom:5px;
}

/* BUTTON */
.btn{
    display:block;
    text-align:center;
    margin-top:10px;
    padding:10px;
    background:#00c6ff;
    color:black;
    text-decoration:none;
    border-radius:6px;
    font-weight:bold;
}

.btn:hover{
    background:#0072ff;
    color:white;
}

/* BADGE */
.badge{
    display:inline-block;
    padding:5px 8px;
    background:green;
    color:white;
    font-size:12px;
    border-radius:5px;
}

/* FOOTER */
.footer{
    text-align:center;
    padding:20px;
    background:#000;
    margin-top:30px;
}

/* RESPONSIVE */
@media(max-width:600px){
    .hero h1{
        font-size:28px;
    }
}

</style>

</head>

<body>

<!-- NAVBAR -->
<div class="nav">
    <div><b>🚗 Car Booking</b></div>
    <div class="menu">
        <a href="index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="admin.php">Admin</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <h1>Drive Your Dream Car</h1>
    <p>Book premium cars instantly with a secure system</p>
</div>

<!-- SEARCH + SORT -->
<div class="search-box">
<form method="GET">

    <input type="number" name="price" placeholder="Max Price">

    <input type="number" name="passengers" placeholder="Passengers">

    <select name="sort">
        <option value="">Sort By</option>
        <option value="low">Price Low → High</option>
        <option value="high">Price High → Low</option>
    </select>

    <button>Search</button>
</form>
</div>

<!-- CAR GRID -->
<div class="container">

<?php
$where = "WHERE 1";

/* FILTERS */
if(!empty($_GET['price'])){
    $where .= " AND price <= " . (int)$_GET['price'];
}

if(!empty($_GET['passengers'])){
    $where .= " AND passengers >= " . (int)$_GET['passengers'];
}

/* SORT */
$order = "";
if(isset($_GET['sort'])){
    if($_GET['sort'] == "low"){
        $order = "ORDER BY price ASC";
    } elseif($_GET['sort'] == "high"){
        $order = "ORDER BY price DESC";
    }
}

/* QUERY */
$res = $conn->query("SELECT * FROM cars $where $order");

if($res && $res->num_rows > 0){
    while($row = $res->fetch_assoc()){

        // DEMO AVAILABILITY
        $available = ($row['id'] % 2 == 0) ? "Available" : "Limited";
        $badgeColor = ($available == "Available") ? "green" : "orange";
?>

<div class="card">

    <img src="<?php echo $row['image']; ?>">

    <div class="card-body">

        <span class="badge" style="background:<?php echo $badgeColor; ?>">
            <?php echo $available; ?>
        </span>

        <h3><?php echo $row['name']; ?></h3>

        <p><b>Model:</b> <?php echo $row['model']; ?></p>
        <p><b>Price:</b> $<?php echo $row['price']; ?>/day</p>
        <p><b>Passengers:</b> <?php echo $row['passengers']; ?></p>

        <a class="btn" href="book.php?id=<?php echo $row['id']; ?>">
            Book Now
        </a>

    </div>
</div>

<?php 
    }
} else {
    echo "<p style='text-align:center;width:100%'>No cars found</p>";
}
?>

</div>

<!-- FOOTER -->
<div class="footer">
    <p>© 2026 Car Booking System | MADE BY BIRAJ MALLA</p>
</div>

</body>
</html>