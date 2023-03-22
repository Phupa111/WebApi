<?php
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    $app->get('/order', function (Request $request, Response $response, $args) {
        $conn = $GLOBALS['connect'];
        $sql = "select 	bill.bid,bill.cid,customer.name,bill.status,bill.totalPrice,bill.dateDay as day
                FROM	customer,bill
                where 	customer.cid = bill.cid";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = array();

        foreach($result as $row){
            array_push($data,$row);
        }

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
        return $response->withHeader('Content-Type','application/json; charset=utf-8')
                        ->withStatus(200);
    });

    $app->get('/orderDetail/{bid}', function (Request $request, Response $response, $args) {
        $conn =$GLOBALS['connect'];
        $sql = 'select 	food.name,food.price,orderitems.amount
                FROM	orderitems,food,bill
                WHERe	bill.bid = orderitems.blid
                AND		food.fid = orderitems.fid
                and 	bill.bid = ? ';
        $stmt = $conn->prepare($sql);
        $bid = $args['bid'];
        $stmt->bind_param('i', $bid);
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

    $app->get('/nameCustomer/{bid}', function (Request $request, Response $response, $args) {
        $conn = $GLOBALS['connect'];
        $sql = "select customer.name,bill.status,bill.totalPrice,bill.dateDay as day
                FROM	customer,bill
                where 	customer.cid = bill.cid
                and     bill.bid = ?";
        $stmt = $conn->prepare($sql);
        $bid = $args['bid'];
        $stmt->bind_param('i', $bid);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = array();

        foreach($result as $row){
            array_push($data,$row);
        }

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
        return $response->withHeader('Content-Type','application/json; charset=utf-8')
                        ->withStatus(200);
    });