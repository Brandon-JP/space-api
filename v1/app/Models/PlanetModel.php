<?php

namespace Vanier\Api\Models;
use Vanier\Api\Helpers\WebServiceInvoker;

class PlanetModel extends BaseModel
{
    function __construct(){
        parent::__construct();
    }
    public function getAllPlanets(array $filters) : array
    {
        $sql_query = "SELECT * FROM planet WHERE 1";

        $addPlanetsFiltersResults = $this->addGetAllPlanetsFilters($sql_query, $filters);
        $sql_query = $addPlanetsFiltersResults["sql_query"];
        $placeholder_values = $addPlanetsFiltersResults["placeholder_values"];

        $sql_query = $this->addSortingClause($sql_query, "planet_name", $filters["sorting_order"] ?? "ascending");

        
        
        $planets = (array)$this->paginate($sql_query, $placeholder_values);

        
        return $planets;
    }

    public function getAmiibosData() 
    {
        $ws_invoker = new WebServiceInvoker([
            "timeout" => 20.0
        ]);
        $composite_res_uri = "https://www.amiiboapi.com/api/amiibo/";
        $amiibos_data = $ws_invoker->invokeURI($composite_res_uri);
        return $amiibos_data;
    }

    public function parseAmiibos(mixed $amiibos_data)
    {
        $amiibos_array = [];
        foreach($amiibos_data->amiibo as $key => $amiibo)
        {
            $amiibos_array[$key]["amiiboSeries"] = $amiibo->amiiboSeries;
            $amiibos_array[$key]["character"] = $amiibo->character;
            $amiibos_array[$key]["gameSeries"] = $amiibo->gameSeries;
            $amiibos_array[$key]["head"] = $amiibo->head;
            $amiibos_array[$key]["image"] = $amiibo->image;
            $amiibos_array[$key]["name"] = $amiibo->name;
            $amiibos_array[$key]["release"] = $amiibo->release;
            $amiibos_array[$key]["tail"] = $amiibo->tail;
            $amiibos_array[$key]["type"] = $amiibo->type;
        }
        return $amiibos_array;
    }

    public function addGetAllPlanetsFilters(string $sql_query, array $filters, array $placeholder_values = [])
    {
        if(isset($filters["name"]))
        {
            $sql_query .= " AND planet_name LIKE CONCAT(:planet_name, '%') ";
            $placeholder_values["planet_name"] = $filters["name"];
        }

        if (isset($filters["from_mass"]))
        {
            $sql_query .= " AND mass >= :planet_from_mass ";
            $placeholder_values["planet_from_mass"] = $filters["from_mass"];
        }

        
        if (isset($filters["to_mass"]))
        {
            $sql_query .= " AND mass <= :planet_to_mass ";
            $placeholder_values["planet_to_mass"] = $filters["to_mass"];
        }

        if (isset($filters["from_diameter"]))
        {
            $sql_query .= " AND diameter >= :planet_from_diameter ";
            $placeholder_values["planet_from_diameter"] = $filters["from_diameter"];
            
        }

        if (isset($filters["to_diameter"]))
        {
            $sql_query .= " AND diameter <= :planet_to_diameter ";
            $placeholder_values["planet_to_diameter"] = $filters["to_diameter"];
        }

        if (isset($filters["from_density"]))
        {
            $sql_query .= " AND density >= :planet_from_density ";
            $placeholder_values["planet_from_density"] = $filters["from_density"];
        }

        if (isset($filters["to_density"]))
        {
            $sql_query .= " AND density <= :planet_to_density ";
            $placeholder_values["planet_to_density"] = $filters["to_density"];
        }

        if (isset($filters["from_gravity"]))
        {
            $sql_query .= " AND gravity >= :planet_from_gravity ";
            $placeholder_values["planet_from_gravity"] = $filters["from_gravity"];
        }

        if (isset($filters["to_gravity"]))
        {
            $sql_query .= " AND gravity <= :planet_to_gravity ";
            $placeholder_values["planet_to_gravity"] = $filters["to_gravity"];
        }

        if (isset($filters["from_escape_velocity"]))
        {
            $sql_query .= " AND escape_velocity >= :planet_from_escape_velocity ";
            $placeholder_values["planet_from_escape_velocity"] = $filters["from_escape_velocity"];
        }

        if (isset($filters["to_escape_velocity"]))
        {
            $sql_query .= " AND escape_velocity <= :planet_to_escape_velocity ";
            $placeholder_values["planet_to_escape_velocity"] = $filters["to_escape_velocity"];
        }

        if (isset($filters["from_rot_period"]))
        {
            $sql_query .= " AND rotation_period >= :planet_from_rot_period ";
            $placeholder_values["planet_from_rot_period"] = $filters["from_rot_period"];
        }

        if (isset($filters["to_rot_period"]))
        {
            $sql_query .= " AND rotation_period <= :planet_to_rot_period ";
            $placeholder_values["planet_to_rot_period"] = $filters["to_rot_period"];
        }

        if (isset($filters["from_moon_count"]))
        {
            $sql_query .= " AND moon_count >= :planet_from_moon_count ";
            $placeholder_values["planet_from_moon_count"] = $filters["from_moon_count"];
        }

        if (isset($filters["to_moon_count"]))
        {
            $sql_query .= " AND moon_count <= :planet_to_moon_count ";
            $placeholder_values["planet_to_moon_count"] = $filters["to_moon_count"];
        }
        return [
            "sql_query" => $sql_query,
            "placeholder_values" => $placeholder_values
        ];
    }
    public function getPlanetById(string $planet_id) : array
    {
        $sql_query = "SELECT * FROM planet
            WHERE planet_id = :planet_id
        ";
        $placeholder_values = [];
        $placeholder_values["planet_id"] = $planet_id;

        $planet = (array)$this->fetchSingle($sql_query, $placeholder_values);
        return $planet;
    }

    public function getPlanetMoonsById(string $planet_id, array $filters) : array
    {
        $data = [];
        $planet = $this->getPlanetById($planet_id);
        $data["planet"] = $planet;

        $sql_query = "SELECT * FROM moon
            WHERE planet_id = :planet_id
        ";
        $placeholder_values = [];
        $placeholder_values["planet_id"] = $planet_id;

        if(isset($filters["name"])){
            $sql_query .= " AND m.moon_name LIKE CONCAT(:m_name,'%')";
            $placeholder_values["m_name"] = $filters["name"];
        }

        if(isset($filters["planet"])){
            $sql_query .= " AND p.planet_name LIKE CONCAT(:p_name,'%')";
            $placeholder_values["p_name"] = $filters["planet"];
        }

        if(isset($filters["from_radius"])){
            $sql_query .= " AND m.radius >= :from_rad";
            $placeholder_values["from_rad"] = $filters["from_radius"];
        }

        if(isset($filters["to_radius"])){
            $sql_query .= " AND m.radius <= :to_rad";
            $placeholder_values["to_rad"] = $filters["to_radius"];
        }

        if(isset($filters["from_density"])){
            $sql_query .= " AND m.density >= :from_den";
            $placeholder_values["from_den"] = $filters["from_density"];
        }

        if(isset($filters["to_density"])){
            $sql_query .= " AND m.density <= :to_den";
            $placeholder_values["to_den"] = $filters["to_density"];
        }

        if(isset($filters["from_magnitude"])){
            $sql_query .= " AND m.magnitude >= :from_mag";
            $placeholder_values["from_mag"] = $filters["from_magnitude"];
        }

        if(isset($filters["to_magnitude"])){
            $sql_query .= " AND m.magnitude <= :to_mag";
            $placeholder_values["to_mag"] = $filters["to_magnitude"];
        }

        if(isset($filters["from_albedo"])){
            $sql_query .= " AND m.albedo >= :from_alb";
            $placeholder_values["from_alb"] = $filters["from_albedo"];
        }

        if(isset($filters["to_albedo"])){
            $sql_query .= " AND m.albedo <= :to_alb";
            $placeholder_values["to_alb"] = $filters["to_albedo"];
        }
        
        $sql_query = $this->addSortingClause($sql_query, "moon_name", $filters["sorting_order"] ?? "ascending");
        $moons = $this->paginate($sql_query, $placeholder_values);
        
        $data["moons"] = $moons;

        return $data;
    }

    public function getPlanetRoversById(string $planet_id, array $filters) : array 
    {
        $data = [];
        $planet = $this->getPlanetById($planet_id);
        $data["planet"] = $planet;

        $sql_query = "SELECT * FROM rover
            WHERE planet_id = :planet_id
        ";
        $placeholder_values = [];
        $placeholder_values["planet_id"] = $planet_id;


        if(isset($filters["name"])){
            $sql_query .= " AND rover_name LIKE CONCAT(:r_name,'%')";
            $placeholder_values["r_name"] = $filters["name"];
        }

        if(isset($filters["country"])){
            $sql_query .= " AND country LIKE CONCAT(:r_country,'%')";
            $placeholder_values["r_country"] = $filters["country"];
        }

        if(isset($filters["agency"])){
            $sql_query .= " AND agency LIKE CONCAT(:r_agency,'%')";
            $placeholder_values["r_agency"] = $filters["agency"];
        }

        if(isset($filters["from_landing_date"])){
            $sql_query .= " AND agency LIKE CONCAT(:r_agency,'%')";
            $placeholder_values["r_agency"] = $filters["agency"];
        }

        $sql_query = $this->addSortingClause($sql_query, "rover_name", $filters["sorting_order"] ?? "ascending");
        $rovers = $this->paginate($sql_query, $placeholder_values);
        $data["rovers"] = $rovers;

        return $data;
    }   
}
