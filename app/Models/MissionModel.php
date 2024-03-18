<?php

namespace Vanier\Api\Models;

class MissionModel extends BaseModel
{
    public function getAllMissions() : array
    {
        $sql_query = "SELECT * from mission";

        $missions = (array)$this->fetchAll($sql_query);
        return $missions;
    }
}
