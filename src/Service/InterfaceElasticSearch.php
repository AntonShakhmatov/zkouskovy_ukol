<?php

namespace App\Service;

interface InterfaceElasticSearch
{
    /** 
     * @param string $id 
     * @return array 
     */
    public function findById($id);
}
