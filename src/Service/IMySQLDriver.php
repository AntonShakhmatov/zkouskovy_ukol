<?php

namespace App\Service;

interface IMySQLDriver
{
    /** 
     * @param string $id 
     * @return array 
     */
    public function findProduct($id);
}
