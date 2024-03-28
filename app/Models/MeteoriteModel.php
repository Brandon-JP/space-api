<?php

namespace Vanier\Api\Models;

class MeteoriteModel extends BaseModel
{
    function __construct(){
        parent::__construct();
    }

    public function getAllMeteorites(array $filters) : array
    {
        $sql_query = "SELECT * FROM meteorite WHERE 1";

        $sql_query = $this->addMeteoritesFilters($sql_query, $filters);
        $placeholder_values = [];

        $sql_query = $this->addSortingClause($sql_query, "meteorite_name", $filters["sorting_order"] ?? "ascending");
        $meteorites = (array)$this->paginate($sql_query, $placeholder_values);

        return $meteorites;
    }

    private function addMeteoritesFilters(string $sql_query, array $filters, array $placeholder_values = []) : array
    {
        if(isset($filters["name"]))
        {
            $sql_query .= " AND meteorite_name LIKE CONCAT(:meteorite_name, '%') ";
            $placeholder_values["meteorite_name"] = $filters["name"];
        }

        if (isset($filters["recclass"]))
        {
            $sql_query .= " AND recclass = :meteorite_recclass ";
            $placeholder_values["meteorite_recclass"] = $filters["recclass"];
        }

        if (isset($filters["from_mass"]))
        {
            $sql_query .= " AND mass >= :meteorite_from_mass ";
            $placeholder_values["meteorite_from_mass"] = $filters["from_mass"];
        }

        if (isset($filters["to_mass"]))
        {
            $sql_query .= " AND mass <= :meteorite_to_mass ";
            $placeholder_values["meteorite_to_mass"] = $filters["to_mass"];
        }

        if (isset($filters["fall"]))
        {
            $sql_query .= " AND fall = :meteorite_fall ";
            $placeholder_values["meteorite_fall"] = $filters["fall"];
        }

        if (isset($filters["from_year"]))
        {
            $sql_query .= " AND year >= :meteorite_from_year ";
            $placeholder_values["meteorite_from_year"] = $filters["from_year"];
        }

        if (isset($filters["to_year"]))
        {
            $sql_query .= " AND year <= :meteorite_to_year ";
            $placeholder_values["meteorite_to_year"] = $filters["to_year"];
        }

        if (isset($filters["from_reclat"]))
        {
            $sql_query .= " AND reclat >= :meteorite_reclat ";
            $placeholder_values["meteorite_from_reclat"] = $filters["from_reclat"];
        }

        if (isset($filters["to_reclat"]))
        {
            $sql_query .= " AND reclat <= :meteorite_reclat ";
            $placeholder_values["meteorite_to_reclat"] = $filters["to_reclat"];
        }

        if (isset($filters["from_reclong"]))
        {
            $sql_query .= " AND reclong >= :meteorite_from_reclong ";
            $placeholder_values["meteorite_from_reclong"] = $filters["from_reclong"];
        }

        if (isset($filters["to_reclong"]))
        {
            $sql_query .= " AND reclong <= :meteorite_reclong ";
            $placeholder_values["meteorite_to_reclong"] = $filters["to_reclong"];
        }

        return [
            "sql_query" => $sql_query,
            "placeholder_values" => $placeholder_values
        ];
    }

    public function getMeteoriteById(string $meteorite_id) : array
    {
        $sql_query = "SELECT * FROM meteorite
            WHERE meteorite_id = :meteorite_id
        ";
        $placeholder_values = [];
        $placeholder_values["meteorite_id"] = $meteorite_id;

        $meteorite = (array)$this->fetchSingle($sql_query, $placeholder_values);
        return $meteorite;
    }
}
