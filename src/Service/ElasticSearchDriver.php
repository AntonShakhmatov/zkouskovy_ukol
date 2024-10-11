<?php

namespace App\Service;

class ElasticSearchDriver implements InterfaceElasticSearch
{
    public function findById($id)
    {
        return [
            'id' => $id,
            'name' => 'Product from ElasticSearch',
            'price' => 100,
        ];
    }
}
