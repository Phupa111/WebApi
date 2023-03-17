<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/foods', function (Request $request, Response $response, $args) {
    $conn =$GLOBALS['connect'];
    $sql = 'select food.fid ,food.price,food.name,food.image,foodtype.name as type
            from food join foodtype on food.ftid = foodtype.ftid';
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
$app->get('/foods/type/{type}', function (Request $request, Response $response, $args) {
    $conn =$GLOBALS['connect'];
    $sql = 'select food.fid ,food.price,food.name,food.image,foodtype.name as type
            from food join foodtype on food.ftid = foodtype.ftid where foodtype.name like ?';
    $stmt = $conn->prepare($sql);
    $name = '%'.$args['type'].'%';
    $stmt->bind_param('s', $name);
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

?>