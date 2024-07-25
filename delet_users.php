<?php
include 'smartchipsdb.php';
    if(isset($_GET['id'])){
        $id = $_GET['id']; 
        $sql = "UPDATE users SET us_status = 0 WHERE us_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i",$id);
    
        if ($stmt->execute()) {
            http_response_code(200); 
            echo json_encode(array("message" => "Record deleted successfully."));
        } else {
            http_response_code(500); 
            echo json_encode(array("message" => "Failed to delete record: " . $conn->error));
        }
        $stmt->close();

    }else{
        http_response_code(400); 
        echo json_encode(array("message" => "Missing id."));
    }





?>
