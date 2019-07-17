<?php 
session_start();
?>
<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <script src="Javascript/main.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Hotel Booking</title>
</head>
<body>

<body style="text-align: center;">

   <nav class="navbar navbar-dark bg-dark" style="width: 100%;">
      <h1><a class="navbar-brand" style="color:#4CAF50;"> Hotel Booking </a></h1>
   </nav>
   
    <?php
     require_once "connect.php";

// Some reources to check out: https://www.php.net/manual/en/book.pdo.php
// A DB wrapper for PDO:  https://github.com/paragonie/easydb
// The guy who wrote the library: https://twitter.com/ciphpercoder?lang=en
// His blog (he's the best PHP security person I know): https://paragonie.com/blog/2019/01/our-php-security-roadmap-for-year-2019
    
    $sql = "CREATE TABLE IF NOT EXISTS bookings(
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        firstname VARCHAR(50),
        lastname VARCHAR(50),
        hotelname VARCHAR(50),
        indate VARCHAR(30),
        outdate VARCHAR(30),
        booked INT(4))";

  
    $conn ->query($sql);
    echo $conn-> error;


    ?>

        <?php
            if (isset($_GET['error']) && $_GET['error'] == 'timestamp') {
        ?>
            <div class='panel panel-default'>
                    <h1>
                        You must select at least  1 day 
                    </h1>
            </div>
        <?php
            }
        ?>

        <form class="forms" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
            <input type="text" id="firstname" name="firstname" placeholder="Your name.." required>
            <input type="text" id="lastname" name="lastname" placeholder="Your last name.." required><br>
            <input  type="date" id="In" class="checkInInput" name="indate" min="2018-01-01" max="2020-01-01" required><br>
            <input  type="date" id="Out" class="checkOutInput" name="outdate" min="2018-01-01" max="2020-01-01" required><br>

        
        <select name="hotelname" required>
            <option value="Hilton">Hilton</option>
            <option value="Westin">Westin</option>
            <option value="Four Seasons">Four Seasons</option>
            <option value="Renaissance">Renaissance</option>
        </select><br>
        <button class="button" name="submit" type="submit">Check for availablity</button><br>
   </form>
   
    <?php
    //write to database

    // function pre($x)
    // {
    //     print '<pre>';
    //     print_r($x);
    //     print '</pre>';
    //     exit;
    // }

    if(isset($_POST['submit'])){
        //create Session var from poste data
            $_SESSION['firstname'] = $_POST['firstname'];
            $_SESSION['lastname'] = $_POST['lastname'];
            $_SESSION['hotelname'] = $_POST['hotelname'];
            $_SESSION['indate'] = $_POST['indate'];
            $_SESSION['outdate'] = $_POST['outdate'];
    
    
        //amount of days the user stays at the hotel
        $datetime1 = new DateTime($_SESSION['indate']);
        $datetime2 = new DateTime($_SESSION['outdate']);
        $interval = $datetime1->diff($datetime2);
     
        $checkInStamp = strtotime($_SESSION['indate']);
        $checkOutStamp = strtotime($_SESSION['outdate']);

        // echo $checkInStamp . '<br>';
        // echo $checkOutStamp;

        if ($checkInStamp - $checkOutStamp > 86400 || $checkInStamp == $checkOutStamp) {
            header("Location: ?error=timestamp");
            exit;
        }

$daysbooked = $interval->format('%d');
switch($_SESSION['hotelname']){
  case "Hilton":
  $value = $daysbooked * 500;
  break;

  case "Westin":
  $value = $daysbooked * 600;
  break;

  case "Four Seasons":
  $value = $daysbooked * 900;
  break;

  case "Renaissance":
  $value = $daysbooked * 800;
  break;

  default:
  return "Invalid Booking";
}
 
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];

$result = mysqli_query($conn,"SELECT hotelname, indate, outdate, firstname, lastname FROM bookings WHERE firstname='$firstname' && lastname='$lastname'"); 

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {    
 echo "<div class='feedback'> You already have a booking. <br> Firstname: ". $row['firstname'] . "<br>
Lastname: " . $row['lastname'].
"<br> Start Date: " . $row['indate'].
"<br> End Date: " . $row['outdate'].
"<br> Hotel Name: " . $row['hotelname'].
"<br>" . $interval->format('%r%a days') . "<br> Total: R " . $value ."</div>";
    } 
}

echo "<div class='feedback'> <br> Firstname: ". $_SESSION['firstname'] . "<br>
Lastname: " . $_SESSION['lastname'].
"<br> Start Date: " . $_SESSION['indate'].
"<br> End Date: " . $_SESSION['outdate'].
"<br> Hotel Name: " . $_SESSION['hotelname'].
"<br>" . $interval->format('%r%a days') . "<br> Total: R " . $value.
"</div>";


echo "<form role='form' method='post' action=".htmlentities($_SERVER["PHP_SELF"]).">
<button class='button' type='submit' name='confirm'> Confirm Booking </button></form></div>";
}
        if(isset($_POST['confirm'])){
            $stmt = $conn->prepare("INSERT INTO bookings(firstname,lastname,hotelname,indate,outdate)VALUES(?,?,?,?,?)");
                $stmt -> bind_param('sssss',$firstname,$lastname,$hotelname,$indate,$outdate);
                
        
        $firstname = $_SESSION['firstname'];
        $lastname = $_SESSION['lastname'];
        $hotelname = $_SESSION['hotelname'];
        $indate = $_SESSION['indate'];
        $outdate = $_SESSION['outdate'];
            $stmt -> execute();
                echo  "<div class='confirm'> Booking Confirmed</div>";
    }
?>


</body>
</html>
