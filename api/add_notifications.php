<?php

include "../config/database.php";


$title = $_POST['title'];
$message = $_POST['message'];


$sql = "INSERT INTO notifications(title,message)
VALUES('$title','$message')";


$result = mysqli_query($conn,$sql);


if($result){

    echo json_encode([
        "success"=>true,
        "message"=>"Notification added"
    ]);

}else{

    echo json_encode([
        "success"=>false,
        "message"=>"Failed to add notification"
    ]);

}

?>