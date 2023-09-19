<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["login_id"];
    $password = $_POST["pwd"];
          
    $sql = "SELECT * FROM users WHERE login_id='$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $db_password = $row["pwd"];
        $is_admin=$row["is_admin"];
        echo "$is_admin";
        if ($password == $db_password) {
            if($is_admin == 1){
                // Set a session variable
                $_SESSION['user_id'] = $username;
                header("Location: admin/insert.php");
            }else{
                header("Location: normal.php");
            }
        }else {
            echo "<script>alert('Invalid username or password');</script>";
            header("Location: index.php");
        }
    }else {
        echo "<script>alert('Invalid username or password');</script>";
        header("Location: index.php");
    }
}
?>
