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
            $sql .= " AND rover_name LIKE CONCAT(:r_name,'%')";
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

        $sql = $this->addSortingClause($sql, "rover_name", $filters["sorting_order"] ?? "ascending");

        return (array) $this->paginate($sql, $filter_values);
    }


    public function getRover(string $rover_id){
        $sql = "SELECT * FROM rover WHERE rover_id = '$rover_id'";
        
        return (array) $this->fetchSingle($sql);
    }


    public function getRoverMissions(array $filters, string $rover_id){
        $filter_values = array();

        $sql = "SELECT rm.* FROM mission_rover rm WHERE 
        rm.rover_id = '$rover_id'";

        $sql = $this->addSortingClause($sql, "mission_name", $filters["sorting_order"] ?? "ascending");

        return (array) $this->paginate($sql, $filter_values);
    }

}
