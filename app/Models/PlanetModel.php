<?php

namespace Vanier\Api\Models;

class PlanetModel extends BaseModel
{
    public function getAllPlanets() : array
    {
        $sql_query = "SELECT * FROM planet";
        $planets = (array)$this->fetchAll($sql_query);
        return $planets;
    }

    public function getPlanetById(string $planet_id) : array
    {
        $sql_query = "SELECT * FROM planet
            WHERE planet_id = :planet_id
        ";
        $placeholder_values["planet_id"] = $planet_id;

        $planet = (array)$this->fetchSingle($sql_query, $placeholder_values);
        return $planet;
    }
}
