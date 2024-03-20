<?php

namespace Vanier\Api\Models;

class AstronautModel extends BaseModel
{
    public function getAllAstronauts()
    {
        $sql_query = "SELECT * FROM astronaut";

        $astronauts = (array)$this->fetchAll($sql_query);

        return $astronauts;
    }


}
