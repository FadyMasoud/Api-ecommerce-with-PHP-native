<?php
include 'smartchipsdb.php';
$data=json_decode(file_get_contents("php://input"),true);


$id=$data['id'];
$name=$data['name'];
$description=$data['description'];
$cost=$data['cost'];
$review=$data['review'];
$img=$data['img'];
$star=$data['star'];
$category=$data['category'];

// if($data['name']  || $data['description']) 
//  {
//     echo json_encode(array('status'=>false, 'msg'=>'This data is entry before'));
//    return;
// }

$sql = "SELECT pd_id FROM product WHERE pd_name = ? AND pd_id <> ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $name, $id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    http_response_code(400);
    $response = array("message" => "Product Name already exists");
    echo json_encode($response);
    $stmt->close();
    $conn->close();
    return;
}



$sql="UPDATE product SET pd_name=?,pd_description=?,pd_cost=?,pd_review=?,pd_img=?,pd_star=?,pd_category_ID=? WHERE pd_id=?";

$stmt=$conn->prepare($sql);

$stmt->bind_param("ssissssi",$name,$description,$cost,$review,$img,$star,$category,$id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        http_response_code(200);
        echo json_encode(array("message" => "Product data updated successfully"));
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "product not found"));
    }
} else {
    http_response_code(500); 
    echo json_encode(array("message" => "Failed to update record: " . $conn->error));
}

$stmt->close();

$conn->close();


?>
