<?php

namespace Vanier\Api\Models;

class RocketModel extends BaseModel
{
    public function getAllRockets(array $filters): array
    {
        $sql_query = "SELECT * FROM rocket WHERE 1";

        $sql_query = $this->addRocketFilters($sql_query, $filters);
        $rockets = (array)$this->paginate($sql_query, $this->placeholderValues);

        return $rockets;
    }

    private function addRocketFilters(string $sql_query, array $filters): string
    {
        if (isset($filters["name"])) {
            $sql_query .= " AND rocket_name LIKE CONCAT(:rocket_name, '%') ";
            $this->placeholderValues["rocket_name"] = $filters["name"];
        }

        if (isset($filters["company"])) {
            $sql_query .= " AND company = :rocket_company ";
            $this->placeholderValues["rocket_company"] = $filters["company"];
        }

        if (isset($filters["status"])) {
            $sql_query .= " AND status = :rocket_status ";
            $this->placeholderValues["rocket_status"] = $filters["status"];
        }

        if (isset($filters["liftoff_thrust"])) {
            $sql_query .= " AND liftoff_thrust >= :rocket_liftoff_thrust ";
            $this->placeholderValues["rocket_liftoff_thrust"] = $filters["liftoff_thrust"];
        }

        if (isset($filters["payload_to_leo"])) {
            $sql_query .= " AND payload_to_leo >= :rocket_payload_to_leo ";
            $this->placeholderValues["rocket_payload_to_leo"] = $filters["payload_to_leo"];
        }

        if (isset($filters["stages"])) {
            $sql_query .= " AND stages = :rocket_stages ";
            $this->placeholderValues["rocket_stages"] = $filters["stages"];
        }

        if (isset($filters["side_strap_count"])) {
            $sql_query .= " AND side_strap_count = :rocket_side_strap_count ";
            $this->placeholderValues["rocket_side_strap_count"] = $filters["side_strap_count"];
        }

        if (isset($filters["rocket_height"])) {
            $sql_query .= " AND rocket_height >= :rocket_rocket_height ";
            $this->placeholderValues["rocket_rocket_height"] = $filters["rocket_height"];
        }

        if (isset($filters["cost"])) {
            $sql_query .= " AND cost <= :rocket_cost ";
            $this->placeholderValues["rocket_cost"] = $filters["cost"];
        }

        return $sql_query;
    }

    public function getRocketMissionsById(string $rocket_id): array
    {
        $sql_query = "SELECT * FROM mission_rocket WHERE rocket_id = :rocket_id";
        $placeholder_values = ["rocket_id" => $rocket_id];

        $rocket_missions = $this->fetchAll($sql_query, $placeholder_values);
        return $rocket_missions;
    }
}
