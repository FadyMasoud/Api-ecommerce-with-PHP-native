
<?php
include 'smartchipsdb.php';
$data=json_decode(file_get_contents("php://input"),true);

if(isset($_GET['cond']) && !empty($_GET['cond'])){
    $cond=$_GET['cond'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE $cond AND us_status = 1 ");
}else{
    $stmt = $conn->prepare("SELECT * FROM users where us_status = 1");
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $users = array();
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users);
} else {
    echo json_encode(array());
}

$stmt->close();
?>

