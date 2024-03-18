<?php

namespace Vanier\Api\Models;

class PlanetModel extends BaseModel
{
    public function getPlanets()
    {
        $sql_query = "SELECT * FROM planet";
        $planets = $this->fetchAll($sql_query);
        return (array)$planets;
    }
}
