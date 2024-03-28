<?php

namespace Vanier\Api\Models;

class MissionModel extends BaseModel
{
    public function getAllMissions(array $filters) : array
    {
        $sql_query = "SELECT * from mission WHERE 1 ";
        $addMissionsFiltersResults = $this->addGetAllMissionsFilters($sql_query, $filters);
        $sql_query = $addMissionsFiltersResults["sql_query"];
        $placeholder_values = $addMissionsFiltersResults["placeholder_values"];

        $sql_query = $this->addSortingClause($sql_query, "mission_name", $filters["sorting_order"] ?? "ascending");
        $missions = (array)$this->paginate($sql_query, $placeholder_values);
        return $missions;
    }

    private function addGetAllMissionsFilters(string $sql_query, array $filters, array $placeholder_values = [])
    {
        if(isset($filters["name"]))
        {
            $sql_query .= " AND mission_name LIKE CONCAT(:mission_name, '%') ";
            $placeholder_values["mission_name"] = $filters["name"];
        }

        if(isset($filters["from_astronaut_count"]))
        {
            $sql_query .= " AND astronaut_count >= :mission_from_astronaut_count ";
            $placeholder_values["mission_from_astronaut_count"] = $filters["from_astronaut_count"];
        }

        if(isset($filters["to_astronaut_count"]))
        {
            $sql_query .= " AND astronaut_count <= :mission_to_astronaut_count ";
            $placeholder_values["mission_to_astronaut_count"] = $filters["to_astronaut_count"];
        }

        if(isset($filters["status"]))
        {
            switch($filters["status"])
            {
                case "Success":
                    $sql_query .= " AND status = 'Success' ";
                    break;
                case "Failure":
                    $sql_query .= " AND status = 'Failure' ";
                    break;
                case "Partial Failure":
                    $sql_query .= " AND status = 'Partial Failure' ";
                    break;
                case "Prelaunch Failure":
                    $sql_query .= " AND status = 'Prelaunch Failure' ";
                    break;
                default:
                    $sql_query .= " AND status = 'Success' ";
            }
        }

        return [
            "sql_query" => $sql_query,
            "placeholder_values" => $placeholder_values
        ];
    }

    public function getMissionById(string $mission_id) : array
    {
        $sql_query = "SELECT * from mission
            WHERE mission_id = :mission_id
        ";
        $placeholder_values = [];
        $placeholder_values["mission_id"] = $mission_id;
        $mission = (array)$this->fetchSingle
        ($sql_query, $placeholder_values);
        return $mission;
    }

    public function getMissionRocketsById(string $mission_id, array $filters)
    {
        $data = [];
        $mission = $this->getMissionById($mission_id);
        $data["mission"] = $mission;

        $sql_query = "SELECT r.* FROM rocket r, mission_rocket mr
            WHERE mr.rocket_id = r.rocket_id
            AND mr.mission_id = :mission_id
        ";
        $placeholder_values = [];
        $placeholder_values["mission_id"] = $mission_id;

        $sql_query = $this->addSortingClause($sql_query, "rocket_name", $filters["sorting_order"] ?? "ascending");
        $rockets = $this->paginate($sql_query, $placeholder_values);
        $data["rockets"] = $rockets;
        return $data;
    }

    public function getMissionRoversById(string $mission_id, array $filters)
    {
        $data = [];
        $mission = $this->getMissionById($mission_id);
        $data["mission"] = $mission;

        $sql_query = "SELECT r.* FROM rover r, mission_rover mr
        WHERE mr.rover_id = r.rover_id
        AND mr.mission_id = :mission_id
        ";
        $placeholder_values = [];
        $placeholder_values["mission_id"] = $mission_id;

        $sql_query = $this->addSortingClause($sql_query, "rover_name", $filters["sorting_order"] ?? "ascending");
        $rovers = $this->paginate($sql_query, $placeholder_values);
        $data["rovers"] = $rovers;
        return $data;
    }
}
