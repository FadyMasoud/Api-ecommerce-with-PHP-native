<?php
include 'smartchipsdb.php';
if(isset($_GET['id'])){
    $id=$_GET['id'];
    $sql="UPDATE categories SET cat_status = 0 WHERE cat_id=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("i",$id);

    if($stmt->execute()){
        http_response_code(200);
        echo json_encode(array("messsage"=>"category deleted successfully"));
    }
    else {
        http_response_code(500);
        echo json_encode(array("messsage"=>"category deleted failed"));
    }
}
    else{
        http_response_code(400);
        echo json_encode(array("messsage"=>"Missing id"));
    }

?>