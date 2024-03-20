<?php

namespace Vanier\Api\Models;

class RoversModel extends BaseModel
{

    function __construct(){
        parent::__construct();
    }


    public function getAllRovers(array $filters){
        $filter_values = array();

        $sql = "SELECT * FROM rover WHERE 1 ";

        return (array) $this->fetchAll($sql, $filter_values);
    }


}
