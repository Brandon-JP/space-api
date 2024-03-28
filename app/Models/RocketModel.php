<?php

namespace Vanier\Api\Models;

class RocketModel extends BaseModel
{
    public function getAllRockets(array $filters): array
    {
        $sql_query = "SELECT * FROM rocket WHERE 1";

        $addRocketFiltersResults = $this->addRocketFilters($sql_query, $filters);
        $sql_query = $addRocketFiltersResults["sql_query"];
        $placeholder_values = $addRocketFiltersResults["placeholder_values"];

        $sql_query = $this->addSortingClause($sql_query, "rocket_name", $filters["sorting_order"] ?? "ascending");
        $rockets = (array)$this->paginate($sql_query, $placeholder_values);

        return $rockets;
    }

    public function addRocketFilters(string $sql_query, array $filters, array $placeholder_values = []): array
    {
        if (isset($filters["name"])) {
            $sql_query .= " AND rocket_name LIKE CONCAT(:rocket_name, '%') ";
            $placeholder_values["rocket_name"] = $filters["name"];
        }

        if (isset($filters["company"])) {
            $sql_query .= " AND company = :rocket_company ";
            $placeholder_values["rocket_company"] = $filters["company"];
        }

        if (isset($filters["status"])) {
            $sql_query .= " AND status = :rocket_status ";
            $placeholder_values["rocket_status"] = $filters["status"];
        }

        if (isset($filters["from_liftoff_thrust"])) {
            $sql_query .= " AND liftoff_thrust >= :rocket_from_liftoff_thrust ";
            $placeholder_values["rocket_from_liftoff_thrust"] = $filters["from_liftoff_thrust"];
        }

        if (isset($filters["to_liftoff_thrust"])) {
            $sql_query .= " AND liftoff_thrust <= :rocket_to_liftoff_thrust ";
            $placeholder_values["rocket_to_liftoff_thrust"] = $filters["to_liftoff_thrust"];
        }

        if (isset($filters["from_payload_to_leo"])) {
            $sql_query .= " AND payload_to_leo >= :rocket_from_payload_to_leo ";
            $placeholder_values["rocket_from_payload_to_leo"] = $filters["from_payload_to_leo"];
        }

        if(isset($filters["to_payload_to_leo"]))
        {
            $sql_query .= " AND payload_to_leo <= :rocket_to_payload_to_leo ";
            $placeholder_values["rocket_to_payload_to_leo"] = $filters["to_payload_to_leo"];
        }

        if (isset($filters["from_stages"])) {
            $sql_query .= " AND stages >= :rocket_from_stages ";
            $placeholder_values["rocket_from_stages"] = $filters["from_stages"];
        }

        if (isset($filters["to_stages"])) {
            $sql_query .= " AND stages <= :rocket_to_stages ";
            $placeholder_values["rocket_to_stages"] = $filters["to_stages"];
        }

        if (isset($filters["from_side_strap_count"])) {
            $sql_query .= " AND side_strap_count >= :rocket_from_side_strap_count ";
            $placeholder_values["rocket_from_side_strap_count"] = $filters["from_side_strap_count"];
        }

        if (isset($filters["to_side_strap_count"])) {
            $sql_query .= " AND side_strap_count <= :rocket_to_side_strap_count ";
            $placeholder_values["rocket_to_side_strap_count"] = $filters["to_side_strap_count"];
        }

        if (isset($filters["from_rocket_height"])) {
            $sql_query .= " AND rocket_height >= :rocket_from_rocket_height ";
            $placeholder_values["rocket_from_rocket_height"] = $filters["from_rocket_height"];
        }

        if (isset($filters["to_rocket_height"])) {
            $sql_query .= " AND rocket_height <= :rocket_to_rocket_height ";
            $placeholder_values["rocket_to_rocket_height"] = $filters["to_rocket_height"];
        }

        if (isset($filters["from_cost"])) {
            $sql_query .= " AND cost >= :rocket_from_cost ";
            $placeholder_values["rocket_from_cost"] = $filters["from_cost"];
        }

        if (isset($filters["to_cost"])) {
            $sql_query .= " AND cost <= :rocket_to_cost ";
            $placeholder_values["rocket_to_cost"] = $filters["to_cost"];
        }
        
        return [
            "sql_query" => $sql_query,
            "placeholder_values" => $placeholder_values
        ];
    }

    public function getRocketById(string $rocket_id)
    {
        $sql_query = "SELECT * FROM rocket
            WHERE rocket_id = :rocket_id
        ";
        $placeholder_values["rocket_id"] = $rocket_id;
        
        $rocket = (array)$this->fetchSingle($sql_query, $placeholder_values);
        return $rocket;
    }
    public function getRocketMissionsById(string $rocket_id, array $filters): array
    {
        $data = [];
        $data["rocket"] = $this->getRocketById($rocket_id);

        $sql_query = "SELECT m.* from mission m, mission_rocket mr 
        WHERE m.mission_id = mr.mission_id
        AND mr.rocket_id = :rocket_id";
        $placeholder_values["rocket_id"] = $rocket_id;
        $mission_model = new MissionModel();
        $addMissionsFiltersResults = $mission_model->addGetMissionsFilters($sql_query, $filters, $placeholder_values);
        $sql_query = $addMissionsFiltersResults["sql_query"];
        $placeholder_values = $addMissionsFiltersResults["placeholder_values"];


        $sql_query = $this->addSortingClause($sql_query, "mission_name", $filters["sorting_order"] ?? "ascending");
        $rocket_missions = $this->paginate($sql_query, $placeholder_values);
        $data["missions"] = $rocket_missions;
        return $data;
    }
}
