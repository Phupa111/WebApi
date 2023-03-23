<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/bill/insert', function (Request $request, Response $response, $args) {

    $conn =$GLOBALS['connect'];
    $json = $request->getBody();
    $jsonData = json_decode($json,true);
    $sql = 'INSERT INTO `bill`(`bid`, `cid`, `dateDay`, `status`, `totalPrice`) VALUES (null,?,NOW(),"ยังไม่ชำระเงิน",0)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$jsonData['cid']);
    $stmt->execute();
    $result = $stmt->get_result();
 
    $response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response
    ->withHeader('Content-Type', 'application/json; charset=utf-8')
    ->withStatus(200);
  
 
});
$app->get('/bill', function (Request $request, Response $response, $args) {

    $conn =$GLOBALS['connect'];
    $sql = 'SELECT * FROM bill';
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();
   
    foreach ($result as $row) {
        array_push($data, $row);
    }
 
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response
    ->withHeader('Content-Type', 'application/json; charset=utf-8')
    ->withStatus(200);
  
 
});

$app->delete('/bil/delete/{bid}', function (Request $request, Response $response, $args) {
    $conn =$GLOBALS['connect'];
    
    $bid= $args['bid'];
    
    $sql ="DELETE FROM `bill` WHERE `bill`.`bid` = ?";
    $stmt =$conn->prepare($sql);
    $stmt->bind_param("i",$bid);
    $stmt->execute();
    $result =$stmt->affected_rows;

    $json = json_encode($result);

    //return result
    $response->getBody()->write(json_encode($json, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response  ->withHeader('Content-Type', 'application/json; charset=utf-8')
    ->withStatus(200);


});


?>