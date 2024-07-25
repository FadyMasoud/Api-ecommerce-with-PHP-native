<?php
include 'smartchipsdb.php';
$data=json_decode(file_get_contents("php://input"),true);

if(!isset($data['name']) || !isset($data['description']) || !isset($data['cost']) || !isset($data['review']) ||
 !isset($data['img']) || !isset($data['star'])|| !isset($data['category']) ){
    echo json_encode(array( 'message'=>'please fill all parameter'));
    $response['message'] = 'please fill all parameter';
   return;
}

if(empty($data['name']) || empty($data['description']) || empty($data['cost']) || empty($data['review']) || 
empty($data['img']) || empty($data['star']) || empty($data['category'])){
    $missing_param = array_search('', $data);
    echo json_encode(array('message'=>$missing_param.' parameter is empty'));
    $response['message'] = $missing_param.' parameter is empty';
    return;
}




$name=$data['name'];
$description=$data['description'];
$cost=$data['cost'];
$review=$data['review'];
$img=$data['img'];
$star=$data['star'];
$category=$data['category'];
// $sale_cost=$data['sale_cost'];


$sql="SELECT pd_name FROM product WHERE pd_name=? or pd_img=?";
$stmt=$conn->prepare($sql);
$stmt->bind_param("ss",$name,$img);
$stmt->execute();
$result=$stmt->get_result();
if($result->num_rows>0){
    echo json_encode(array('message'=>'prodct (name or img) already exist'));
    $response['message'] = 'prodct (name or img) already exist';
    return;
}
$stmt->close();


// if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
//     echo json_encode(array('status'=>false, 'msg'=>'please provide valid email'));
//     return;
// }


$sql="INSERT INTO product (pd_name,pd_description,pd_cost,pd_review,pd_img,pd_star,pd_category_ID,pd_new_cost) VALUES(?,?,?,?,?,?,?,?)";

$stmt=$conn->prepare($sql);

$stmt->bind_param("ssisssii",$name,$description,$cost,$review,$img,$star,$category,$sale_cost);

if($stmt->execute()){

    echo json_encode(array('message'=>'product added successfully.'));
    $response['message'] = 'product added successfully.';


    
}else{
    echo json_encode(array( 'message'=>'Product added is failed.'.$stmt->error));
    $response['message'] = 'Product added is failed.'.$stmt->error;
   
}


$stmt->close();




?>