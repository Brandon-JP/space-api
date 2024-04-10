<?php

namespace Vanier\Api\Models;
use Slim\Psr7\Request;
use Vanier\Api\Exceptions\HttpBadRequestException;
use Vanier\Api\Helpers\InputsHelper;
use Vanier\Api\Helpers\Validator;

class MeteoriteModel extends BaseModel
{
    function __construct(){
        parent::__construct();
    }

    private function validateCreateMeteoriteData(array $meteorite_data)
    {
        $rules = [
            "meteorite_name" => [
                "required",
                "alphaNum",
                ["lengthMax", 32]
            ],
            "recclass" => [
                "required",
                "alphaNum",
                ["lengthMax", 16]
            ],
            "mass" =>  [
                "required",
                "integer"
            ],
            "fall" => [
                "required",
                "alpha",
                ["in", ["Fell", "Found"]]
            ],
            "year" => [
                "required",
                "integer"
            ],
            "reclat" => [
                "required",
                ["min", -90.0],
                ["max", 90.0]
            ],
            "reclong" => [
                "required",
                ["min", -180.0],
                ["max", 180.0]
            ]
        ];
        $validator = new Validator($meteorite_data);
        $validator->mapFieldsRules($rules);
        return [
            "meteorite_data_valid" => $validator->validate(),
            "validator" => $validator
        ];
    }

    private function validateUpdateMeteoriteData(array $meteorite_data)
    {
        $rules = [
            "meteorite_id" => [
                "required",
                "integer"
            ],
            "meteorite_name" => [
                "required",
                "alphaNum",
                ["lengthMax", 32]
            ],
            "recclass" => [
                "required",
                "alphaNum",
                ["lengthMax", 16]
            ],
            "mass" =>  [
                "required",
                "integer"
            ],
            "fall" => [
                "required",
                "alpha",
                ["in", ["Fell", "Found"]]
            ],
            "year" => [
                "required",
                "integer"
            ],
            "reclat" => [
                "required",
                "numeric",
                ["min", -90],
                ["max", 90]
            ],
            "reclong" => [
                "required",
                "numeric",
                ["min", -180],
                ["max", 180]
            ]
        ];
        $validator = new Validator($meteorite_data);
        $validator->mapFieldsRules($rules);
        return [
            "meteorite_data_valid" => $validator->validate(),
            "validator" => $validator
        ];
    }

    private function validateDeleteMeteoriteData(mixed $meteorite_id)
    {
        $valid_id = InputsHelper::isInt($meteorite_id, 1);
        return $valid_id;
    }

    public function createMeteorite(Request $request, array $meteorite_data)
    {
        $meteorite_validate_results = $this->validateCreateMeteoriteData($meteorite_data);


        $meteorite_data_valid = $meteorite_validate_results["meteorite_data_valid"];
        if($meteorite_data_valid)
        {
            $this->insert("meteorite", $meteorite_data);
        }
        else {
            $validator = $meteorite_validate_results["validator"];
            throw new HttpBadRequestException(
                $request,
                $validator->errorsToString()
            );
        }
    }

    public function updateMeteorite(Request $request, array $meteorite_data)
    {
        $meteorite_validate_results = $this->validateUpdateMeteoriteData($meteorite_data);

        $meteorite_data_valid = $meteorite_validate_results["meteorite_data_valid"];
        if($meteorite_data_valid)
        {
            $meteorite_id = $meteorite_data["meteorite_id"]; 
            unset($meteorite_data["meteorite_id"]);
            $this->update("meteorite", $meteorite_data, ["meteorite_id" => $meteorite_id]);
        }
        else {
            $validator = $meteorite_validate_results["validator"];
            throw new HttpBadRequestException(
                $request,
                $validator->errorsToString()
            );
        }
    }

    public function deleteMeteorite(Request $request, mixed $meteorite_id)
    {
        $meteorite_id_valid = $this->validateDeleteMeteoriteData($meteorite_id);

        if($meteorite_id_valid)
        {
            $this->delete("meteorite", ["meteorite_id" => $meteorite_id]);
        }
        else {
            $invalid_id_message = "The ID(s) provided are in an incorrect format!";
            throw new HttpBadRequestException(
                $request,
                $invalid_id_message
            );
        }
    }
    public function getAllMeteorites(array $filters) : array
    {
        $sql_query = "SELECT * FROM meteorite WHERE 1";
        
        $addMeteoriteFiltersResults = $this->addMeteoritesFilters($sql_query, $filters);
        $sql_query = $addMeteoriteFiltersResults["sql_query"];
        $placeholder_values = $addMeteoriteFiltersResults["placeholder_values"];

        $sql_query = $this->addSortingClause($sql_query, "meteorite_name", $filters["sorting_order"] ?? "ascending");
        $meteorites = $this->paginate($sql_query, $placeholder_values);

        return $meteorites;
    }

    public function addMeteoritesFilters(string $sql_query, array $filters, array $placeholder_values = []) : array
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
            $sql_query .= " AND reclat >= :meteorite_from_reclat ";
            $placeholder_values["meteorite_from_reclat"] = $filters["from_reclat"];
        }

        if (isset($filters["to_reclat"]))
        {
            $sql_query .= " AND reclat <= :meteorite_to_reclat ";
            $placeholder_values["meteorite_to_reclat"] = $filters["to_reclat"];
        }

        if (isset($filters["from_reclong"]))
        {
            $sql_query .= " AND reclong >= :meteorite_from_reclong ";
            $placeholder_values["meteorite_from_reclong"] = $filters["from_reclong"];
        }

        if (isset($filters["to_reclong"]))
        {
            $sql_query .= " AND reclong <= :meteorite_to_reclong ";
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
