<?php

namespace Vanier\Api\Models;

class AstronautModel extends BaseModel
{
    public function getAllAstronauts(array $filters)
    {
        $sql_query = "SELECT * FROM astronaut WHERE 1";
        $addAstronautsFiltersResults = $this->addGetAllAstronautsFilters($sql_query, $filters);

        $sql_query = $addAstronautsFiltersResults["sql_query"];
        $placeholder_values = $addAstronautsFiltersResults["placeholder_values"];

        $this->setPaginationOptions(
            $filters["page"] ?? 1,
            $filters["page_size"] ?? 15
        );
        $astronauts = (array)$this->paginate($sql_query, $placeholder_values);

        return $astronauts;
    }
    private function addGetAllAstronautsFilters(string $sql_query, array $filters, array $placeholder_values = [])
    {
        if(isset($filters["first_name"]))
        {
            $sql_query .= " AND first_name LIKE CONCAT(:astronaut_first_name, '%') ";
            $placeholder_values["astronaut_first_name"] = $filters["first_name"];
        }

        if(isset($filters["last_name"]))
        {
            $sql_query .= " AND last_name LIKE CONCAT(:astronaut_last_name, '%') ";
            $placeholder_values["astronaut_last_name"] = $filters["last_name"];
        }

        if(isset($filters["gender"]))
        {
            $sql_query .= " AND gender LIKE CONCAT(:astronaut_gender, '%') ";
            $placeholder_values["astronaut_gender"] = $filters["gender"];
        }

        if(isset($filters["country"]))
        {
            $sql_query .= " AND country LIKE CONCAT(:astronaut_country, '%') ";
            $placeholder_values["astronaut_country"] = $filters["country"];
        }

        if(isset($filters["from_flight_count"]))
        {
            $sql_query .= " AND flight_count >= :astronaut_from_flight_count ";
            $placeholder_values["astronaut_from_flight_count"] = $filters["from_flight_count"];
        }

        if(isset($filters["to_flight_count"]))
        {
            $sql_query .= " AND flight_count <= :astronaut_to_flight_count ";
            $placeholder_values["astronaut_to_flight_count"] = $filters["to_flight_count"];
        }

        if(isset($filters["from_flight_time"]))
        {
            $sql_query .= " AND total_flight_time >= :astronaut_from_total_flight_time ";
            $placeholder_values["astronaut_from_total_flight_time"] = $filters["from_flight_time"];
        }

        if(isset($filters["to_flight_time"]))
        {
            $sql_query .= " AND total_flight_time <= :astronaut_to_total_flight_time ";
            $placeholder_values["astronaut_to_total_flight_time"] = $filters["to_flight_time"];
        }

        return [
            "sql_query" => $sql_query,
            "placeholder_values" => $placeholder_values
        ];
    }

    public function getAstronautById(string $astronaut_id)
    {
        $sql_query = "SELECT * FROM astronaut
            WHERE astronaut_id = :astronaut_id
        ";
        $placeholder_values = [];
        $placeholder_values["astronaut_id"] = $astronaut_id;

        $astronaut = (array)$this->fetchSingle($sql_query, $placeholder_values);
        return $astronaut;
    }
}
