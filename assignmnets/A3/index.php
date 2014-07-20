<html>
<head>
</head>
<body>
	<h1 align="center">Adder.php</h1>
<?php
if (isset($_POST['num1'])) {
      $num1 = $_POST['num1'];
      $num2 = $_POST['num2'];
      $myTotal = $num1 + $num2;
      echo('<h2 align=center>You added <font color="blue">'. $num1 . '</font> and ');
      echo('<font color="blue">' . $num2 . '</font> and the answer is <font color="red">' . $myTotal .'</font>!');
      unset($_POST['num1']);
      unset($_POST['num2']);
      echo('<br><a href="assignmnet3.php">Reset page</a>');
} else { 
?>

       <form action="assignmnet3.php" method="POST">
       Enter the first number:<input type="text" name="num1"><br>
       Enter the second number:<input type="text" name="num2"><br>
       <input type="submit" value="Add Em!!">
       </form>

<?
}
?>
</body>
</html>