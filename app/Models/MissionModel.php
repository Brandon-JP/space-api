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

    public function getMissionById(string $mission_id) : array
    {
        $sql_query = "SELECT * from mission
            WHERE mission_id = :mission_id
        ";
        $placeholder_values = [];
        $placeholder_values["mission_id"] = $mission_id;
        $mission = (array)$this->fetchSingle($sql_query, $placeholder_values);
        return $mission;
    }
}
