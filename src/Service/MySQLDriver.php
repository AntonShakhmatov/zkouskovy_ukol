<?php

namespace App\Service;

class MySQLDriver implements IMySQLDriver
{
    public function findProduct($id)
    {
        return [
            'id' => $id,
            'name' => 'Product from MySQLDriver',
            'price' => 300,
        ];
    }
}
