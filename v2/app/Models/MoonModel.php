<?php

namespace Vanier\Api\Models;

class MoonModel extends BaseModel
{
    function __construct(){
        parent::__construct();
    }

    public function getAllMoons(array $filters){
        $filter_values = array();
        $sql = "SELECT m.*, p.planet_name FROM moon m, planet p WHERE p.planet_id = m.planet_id";

        if(isset($filters["name"])){
            $sql .= " AND m.moon_name LIKE CONCAT(:m_name,'%')";
            $filter_values["m_name"] = $filters["name"];
        }

        if(isset($filters["planet"])){
            $sql .= " AND p.planet_name LIKE CONCAT(:p_name,'%')";
            $filter_values["p_name"] = $filters["planet"];
        }

        if(isset($filters["from_radius"])){
            $sql .= " AND m.radius >= :from_rad";
            $filter_values["from_rad"] = $filters["from_radius"];
        }

        if(isset($filters["to_radius"])){
            $sql .= " AND m.radius <= :to_rad";
            $filter_values["to_rad"] = $filters["to_radius"];
        }

        if(isset($filters["from_density"])){
            $sql .= " AND m.density >= :from_den";
            $filter_values["from_den"] = $filters["from_density"];
        }

        if(isset($filters["to_density"])){
            $sql .= " AND m.density <= :to_den";
            $filter_values["to_den"] = $filters["to_density"];
        }

        if(isset($filters["from_magnitude"])){
            $sql .= " AND m.magnitude >= :from_mag";
            $filter_values["from_mag"] = $filters["from_magnitude"];
        }

        if(isset($filters["to_magnitude"])){
            $sql .= " AND m.magnitude <= :to_mag";
            $filter_values["to_mag"] = $filters["to_magnitude"];
        }

        if(isset($filters["from_albedo"])){
            $sql .= " AND m.albedo >= :from_alb";
            $filter_values["from_alb"] = $filters["from_albedo"];
        }

        if(isset($filters["to_albedo"])){
            $sql .= " AND m.albedo <= :to_alb";
            $filter_values["to_alb"] = $filters["to_albedo"];
        }

        $sql = $this->addSortingClause($sql, "m.moon_name", $filters["sorting_order"] ?? "ascending");


        return (array)$this->paginate($sql, $filter_values);
    }


    public function getMoon(string $moon_id){
        $sql = "SELECT * FROM moon WHERE moon_id = '$moon_id'";
        
        return (array) $this->fetchSingle($sql);
    }


    public function getMoonRovers(string $moon_id, array $filters){
        $filter_values = array();

        $sql = "SELECT r.* FROM rover r WHERE moon_id = '$moon_id'";
        

        
        if(isset($filters["name"])){
            $sql .= " AND r.rover_name LIKE CONCAT(:r_name,'%')";
            $filter_values["r_name"] = $filters["name"];
        }

        if(isset($filters["country"])){
            $sql .= " AND r.country LIKE CONCAT(:r_country,'%')";
            $filter_values["r_country"] = $filters["country"];
        }

        if(isset($filters["agency"])){
            $sql .= " AND r.agency LIKE CONCAT(:r_agency,'%')";
            $filter_values["r_agency"] = $filters["agency"];
        }

        if(isset($filters["from_landing_date"])){
            $sql .= " AND r.agency LIKE CONCAT(:r_agency,'%')";
            $filter_values["r_agency"] = $filters["agency"];
        }

        
        $sql = $this->addSortingClause($sql, "r.rover_name", $filters["sorting_order"] ?? "ascending");

        return (array) $this->paginate($sql, $filter_values);
    }

    public function createMoon(array $moon_data){
        return $this->insert('moon', $moon_data);
    }

    public function updateMoon(array $moon_data, int $moon_id):mixed {
        return $this->update('moon', $moon_data, ['moon_id' => $moon_id]);
    }
    public function deleteMoon(int $moon_id):mixed {
        return $this->delete('moon',['moon_id' => $moon_id]);
    }
}
