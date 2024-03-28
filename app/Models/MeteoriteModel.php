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

        $sql_query = $this->addSortingClause($sql_query, "name", $filters["sorting_order"] ?? "ascending");
        $meteorites = (array)$this->paginate($sql_query, $placeholder_values);

        return $meteorites;
    }

    private function addMeteoritesFilters(string $sql_query, array $filters) : string
    {
        if(isset($filters["name"]))
        {
            $sql_query .= " AND name LIKE CONCAT(:meteorite_name, '%') ";
            $placeholder_values["meteorite_name"] = $filters["name"];
        }

        if (isset($filters["recclass"]))
        {
            $sql_query .= " AND recclass = :meteorite_recclass ";
            $placeholder_values["meteorite_recclass"] = $filters["recclass"];
        }

        if (isset($filters["mass"]))
        {
            $sql_query .= " AND mass >= :meteorite_mass ";
            $placeholder_values["meteorite_mass"] = $filters["mass"];
        }

        if (isset($filters["fall"]))
        {
            $sql_query .= " AND fall = :meteorite_fall ";
            $placeholder_values["meteorite_fall"] = $filters["fall"];
        }

        if (isset($filters["year"]))
        {
            $sql_query .= " AND year >= :meteorite_year ";
            $placeholder_values["meteorite_year"] = $filters["year"];
        }

        if (isset($filters["reclat"]))
        {
            $sql_query .= " AND reclat = :meteorite_reclat ";
            $placeholder_values["meteorite_reclat"] = $filters["reclat"];
        }

        if (isset($filters["reclong"]))
        {
            $sql_query .= " AND reclong = :meteorite_reclong ";
            $placeholder_values["meteorite_reclong"] = $filters["reclong"];
        }

        return $sql_query;
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
