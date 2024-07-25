<?php
include 'smartchipsdb.php';
$data=json_decode(file_get_contents("php://input"),true);

if(!isset($data['name']) || !isset($data['description']) || !isset($data['cost']) || !isset($data['review']) ||
 !isset($data['img']) || !isset($data['star'])|| !isset($data['categoryname']) ){
    echo json_encode(array( 'message'=>'please fill all parameter'));
    $response['message'] = 'please fill all parameter';
   return;
}

if(empty($data['name']) || empty($data['description']) || empty($data['cost']) || empty($data['review']) || 
empty($data['img']) || empty($data['star']) || empty($data['categoryname'])){
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
$category=$data['categoryname'];
// $sale_cost=$data['sale_cost'];


$sql="SELECT cat_name FROM categories WHERE cat_name=? or cat_pd_img=?";
$stmt=$conn->prepare($sql);
$stmt->bind_param("ss",$category,$img);
$stmt->execute();
$result=$stmt->get_result();
if($result->num_rows>0){
    echo json_encode(array('message'=>'Category already exist'));
    $response['message'] = 'Category already exist';
    return;
}
$stmt->close();


// if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
//     echo json_encode(array('status'=>false, 'msg'=>'please provide valid email'));
//     return;
// }


$sql="INSERT INTO categories (cat_name,cat_pd_name,cat_pd_description,cat_pd_cost,cat_pd_review,cat_pd_img,cat_pd_star) VALUES(?,?,?,?,?,?,?)";

$stmt=$conn->prepare($sql);

$stmt->bind_param("sssisss",$category,$name,$description,$cost,$review,$img,$star,);

if($stmt->execute()){

    echo json_encode(array('message'=>'category added successfully.'));
    $response['message'] = 'category added successfully.';


    
}else{
    echo json_encode(array( 'message'=>'category added is failed.'.$stmt->error));
    $response['message'] = 'category added is failed.'.$stmt->error;
   
}


$stmt->close();




?>