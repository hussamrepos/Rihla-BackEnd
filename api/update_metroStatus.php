 <?php

include "../config/database.php";


$line = $_POST['line_name'];
$status = $_POST['status'];


$sql = "UPDATE metro_status 
        SET status='$status'
        WHERE line_name='$line'";


if(mysqli_query($conn,$sql)){

    echo json_encode([
        "success"=>true,
        "message"=>"Status updated"
    ]);

}
else{

    echo json_encode([
        "success"=>false
    ]);

}

?>