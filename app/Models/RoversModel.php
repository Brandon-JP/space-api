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

        if(isset($filters["name"])){
            $sql .= " AND name LIKE CONCAT(:r_name,'%')";
            $filter_values["r_name"] = $filters["name"];
        }

        if(isset($filters["country"])){
            $sql .= " AND country LIKE CONCAT(:r_country,'%')";
            $filter_values["r_country"] = $filters["country"];
        }

        if(isset($filters["agency"])){
            $sql .= " AND agency LIKE CONCAT(:r_agency,'%')";
            $filter_values["r_agency"] = $filters["agency"];
        }

        if(isset($filters["from_landing_date"])){
            $sql .= " AND agency LIKE CONCAT(:r_agency,'%')";
            $filter_values["r_agency"] = $filters["agency"];
        }


        return (array) $this->fetchAll($sql, $filter_values);
    }


    public function getRover(string $rover_id){
        $sql = "SELECT * FROM rover WHERE rover_id = '$rover_id'";
        
        return (array) $this->fetchSingle($sql);
    }


    public function getRoverMissions($filters){
        $filter_values = array();

        $sql = "";

        return (array) $this->fetchAll($sql, $filter_values);
    }

}
