<?php

namespace Vanier\Api\Models;

class PlanetModel extends BaseModel
{
    function __construct(){
        parent::__construct();
    }
    public function getAllPlanets(array $filters) : array
    {
        $sql_query = "SELECT * FROM planet WHERE 1";

        $addPlanetsFiltersResults = $this->addGetAllPlanetsFilters($sql_query, $filters);
        $sql_query = $addPlanetsFiltersResults["sql_query"];
        $placeholder_values = $addPlanetsFiltersResults["placeholder_values"];

        $planets = (array)$this->fetchAll($sql_query, $placeholder_values);
        return $planets;
    }

    private function addGetAllPlanetsFilters(string $sql_query, array $filters, array $placeholder_values = [])
    {
        if(isset($filters["name"]))
        {
            $sql_query .= " AND name LIKE CONCAT(:planet_name, '%') ";
            $placeholder_values["planet_name"] = $filters["name"];
        }

        if (isset($filters["from_mass"]))
        {
            $sql_query .= " AND mass >= :planet_from_mass ";
            $placeholder_values["planet_from_mass"] = $filters["from_mass"];
        }

        
        if (isset($filters["to_mass"]))
        {
            $sql_query .= " AND mass <= :planet_to_mass ";
            $placeholder_values["planet_to_mass"] = $filters["to_mass"];
        }

        if (isset($filters["from_diameter"]))
        {
            $sql_query .= " AND diameter >= :planet_from_diameter ";

            $placeholder_values["planet_from_diameter"] = $filters["from_diameter"];
            
        }

        if (isset($filters["to_diameter"]))
        {
            $sql_query .= " AND diameter <= :planet_to_diameter ";
            $placeholder_values["planet_to_diameter"] = $filters["to_diameter"];
        }

        if (isset($filters["from_density"]))
        {
            $sql_query .= " AND density >= :planet_from_density ";
            $placeholder_values["planet_from_density"] = $filters["from_density"];
        }

        if (isset($filters["to_density"]))
        {
            $sql_query .= " AND density <= :planet_to_density ";
            $placeholder_values["planet_to_density"] = $filters["to_density"];
        }

        if (isset($filters["from_gravity"]))
        {
            $sql_query .= " AND gravity >= :planet_from_gravity ";
            $placeholder_values["planet_from_gravity"] = $filters["from_gravity"];
        }

        if (isset($filters["to_gravity"]))
        {
            $sql_query .= " AND gravity <= :planet_to_gravity ";
            $placeholder_values["planet_to_gravity"] = $filters["to_gravity"];
        }

        if (isset($filters["from_escape_velocity"]))
        {
            $sql_query .= " AND escape_velocity >= :planet_from_escape_velocity ";
            $placeholder_values["planet_from_escape_velocity"] = $filters["from_escape_velocity"];
        }

        if (isset($filters["to_escape_velocity"]))
        {
            $sql_query .= " AND escape_velocity <= :planet_to_escape_velocity ";
            $placeholder_values["planet_to_escape_velocity"] = $filters["to_escape_velocity"];
        }

        if (isset($filters["from_rot_period"]))
        {
            $sql_query .= " AND rotation_period >= :planet_from_rot_period ";
            $placeholder_values["planet_from_rot_period"] = $filters["from_rot_period"];
        }

        if (isset($filters["to_rot_period"]))
        {
            $sql_query .= " AND rotation_period <= :planet_to_rot_period ";
            $placeholder_values["planet_to_rot_period"] = $filters["to_rot_period"];
        }

        if (isset($filters["from_moon_count"]))
        {
            $sql_query .= " AND moon_count >= :planet_from_moon_count ";
            $placeholder_values["planet_from_moon_count"] = $filters["from_moon_count"];
        }

        if (isset($filters["to_moon_count"]))
        {
            $sql_query .= " AND moon_count <= :planet_to_moon_count ";
            $placeholder_values["planet_to_moon_count"] = $filters["to_moon_count"];
        }
        return [
            "sql_query" => $sql_query,
            "placeholder_values" => $placeholder_values
        ];
    }
    public function getPlanetById(string $planet_id) : array
    {
        $sql_query = "SELECT * FROM planet
            WHERE planet_id = :planet_id
        ";
        $placeholder_values = [];
        $placeholder_values["planet_id"] = $planet_id;

        $planet = (array)$this->fetchSingle($sql_query, $placeholder_values);
        return $planet;
    }

    public function getPlanetMoonsById(string $planet_id) : array
    {
        $data = [];
        $planet = $this->getPlanetById($planet_id);
        $data["planet"] = $planet;

        $sql_query = "SELECT * FROM moon
            WHERE planet_id = :planet_id
        ";
        $placeholder_values = [];
        $placeholder_values["planet_id"] = $planet_id;

        $moons = $this->fetchAll($sql_query, $placeholder_values);
        $data["moons"] = $moons;

        return $data;
    }

    public function getPlanetRoversById(string $planet_id) : array 
    {
        $data = [];
        $planet = $this->getPlanetById($planet_id);
        $data["planet"] = $planet;

        $sql_query = "SELECT * FROM rover
            WHERE planet_id = :planet_id
        ";
        $placeholder_values = [];
        $placeholder_values["planet_id"] = $planet_id;

        $rovers = $this->fetchAll($sql_query, $placeholder_values);
        $data["rovers"] = $rovers;

        return $data;
    }   
}
