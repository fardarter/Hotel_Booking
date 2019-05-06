<?php SESSION_start()?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <?php
   $sql = "CREATE TABLE bookings(
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50),
    lastname VARCHAR(50),
    hotelname VARCHAR(50),
    indate VARCHAR(30),
    outdate VARCHAR(30),
    booked INT(4))";

    require_once "connect.php";
    $conn ->query($sql);
    echo $conn-> error;
    ?>
 
     <h1>TriggerTRIP</h1>
        <form role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">

<div>
        <!-- <form action="/action_page.php"> -->
        <form>
            <input type="text" id="firstname" name="firstname" placeholder="Your name..">
            <input type="text" id="lastname" name="lastname" placeholder="Your last name.."><br>
            <input  type="date" id="In" name="indate" min="2018-01-01" max="2020-01-01"><br>
            <input  type="date" id="Out" name="outdate" min="2018-01-01" max="2020-01-01"><br>
</div>
        
        <select name="hotelname" required>
            <option value="Hilton">Hilton</option>
            <option value="Westin">Westin</option>
            <option value="Four Seasons">Four Seasons</option>
            <option value="Renaissance">Renaissance</option>
        </select><br>
        <input type="submit" name="submit"></input><br>
   </form>
   </div>
    <?php
    //write to database

    if(isset($_POST['submit'])){
        //create Session var from poste data
            $_SESSION['firstname'] = $_POST['firstname'];
            $_SESSION['lastname'] = $_POST['lastname'];
            $_SESSION['hotelname'] = $_POST['hotelname'];
            $_SESSION['indate'] = $_POST['indate'];
            $_SESSION['outdate'] = $_POST['outdate'];
        }

        //amount of days the user stays at the hotel
        $datetime1 = new DateTime($_SESSION['indate']);
        $datetime2 = new DateTime($_SESSION['outdate']);
        $interval = $datetime1-> diff($datetime2);

        

        //display booking info to user
echo "<div class='feedback'>"."<br> Firstname: ". $_SESSION['firstname']."<br>".
    "Lastname: ". $_SESSION['lastname']."<br>".
    "Start Date: ". $_SESSION['indate']."<br>".
    "End Date: ". $_SESSION['outdate']."<br>".
    "Hotel Name: ". $_SESSION['hotelname']."<br>".
        $interval->format('%R%a days')."</div>";


$daysbooked = $interval->format('%R%a days');
$value;

switch($_POST['hotelname']){
  case "Hilton":
  $value = $daysbooked * 500;
  break;

  case "Westin":
  $value = $daysbooked * 600;
  break;

  case "Four Seasons":
  $value = $daysbooked * 700;
  break;

  case "Renaissance":
  $value = $daysbooked * 800;
  break;

  default:
  return "ERROR!";
}


?>
</body>
</html>