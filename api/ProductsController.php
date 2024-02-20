<?php

class ProductsController
{
    static function saveProducts($data)
    {
        $product = new Product();
        try {
            if (!$product->checkIfExists($data[0])) {
                $product->code = $data[0];
                $product->name = $data[1];
                $product->level1 = $data[2];
                $product->level2 = $data[3];
                $product->level3 = $data[4];
                $product->price = number_format((float)str_replace(' ', '', $data[5]), 2);
                $product->quantity = $data[7];
                $product->price_sp = number_format((float)str_replace(' ', '', $data[6]), 2);
                $product->properties = $data[8];
                $product->joint_buys = $data[9];
                $product->unit = $data[10];
                $product->image = $data[11];
                $product->display_on_main = $data[12];
                $product->description = isset($data[13]) ? $data[13] : '';
                $product->created_at = date('Y-m-d H:i:s', time());
                return $product->save();
            } else {
                return null;
            }
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    static function getProducts()
    {
        try {
            if (!isset($_GET['page'])) {
                $page = 1;
            } else {
                $page = $_GET['page'];
            }
            $start = ($page - 1) * 100;
            $product = new Product();
            return $product->getAll($start);
        } catch (Exception $ex) {
            die($ex->getMessage());
        }
    }
}