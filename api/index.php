<?php

require_once '../Autoload.php';

header("Content-Type: json/application");
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case "GET":
        try {
            $products = ProductsController::getProducts();
            http_response_code(200);
            die(json_encode($products));
        }catch (Exception $ex){
            http_response_code(500);
            die(json_encode(['res' => false]));
        }
    case "POST":
        $row = 0;
        $notInsertedCodes = [];
        $totalInserted = 0;
        $totalRows = 0;
        $totalSkipped = [];
        if(!empty($_FILES["csv"])) {
            if ($_FILES["csv"]['type'] == 'text/csv') {
                if (($file = fopen($_FILES["csv"]["tmp_name"], "r")) !== FALSE) {
                    while (($data = fgetcsv($file, 9999, ";")) !== FALSE) {
                        if ($row == 0) {
                            $row++;
                            continue;
                        }
                        $totalRows++;
                        try {
                            $res = ProductsController::saveProducts($data);
                            if ($res === null)
                                $totalSkipped[] = $data[0];
                            else if ($res)
                                $totalInserted++;
                        } catch (Exception $ex) {
                            $notInsertedCodes[] = $data[0];
                        }
                    }
                    fclose($file);
                    http_response_code(200);
                    die(json_encode(['res' => true, 'totalRows' => $totalRows, 'totalInserted' => $totalInserted, 'notInsertedCodes' => $notInsertedCodes, 'skipped' => $totalSkipped]));
                } else {
                    http_response_code(404);
                    die(json_encode(['res' => false, 'message' => 'Предоставленный файл не существует']));
                }
            }else {
                http_response_code(415);
                die(json_encode(['res' => false, 'message' => 'Вы должны предоставить csv-файл']));
            }
        }else{
            http_response_code(400);
            die(json_encode(['res' => false, 'message' => 'Поле файл пусто']));
        }
    default:
        http_response_code(404);
        die(json_encode(['res' => false, 'message' => 'Метод ' . $method . ' не определен']));
}