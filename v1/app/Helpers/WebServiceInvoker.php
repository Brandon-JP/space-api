<?php

namespace Vanier\Api\Helpers;
use GuzzleHttp\Client;

class WebServiceInvoker
{
    private array $client_options = [];
    public function __construct($options = []) {
        // You can pass a list of options for Guzzle client
        $this->client_options = $options;
    }


    public function invokeURI(string $resource_uri) : mixed {
        // We will initiate a GET request
        $client = new Client($this->client_options);
        $response = $client->get($resource_uri);
        //! We need to process the response: get status code, response header: application json
        if($response->getStatusCode() !== 200)
        {
            // Return an array containing the status code, message and reason
            return $this->returnError(
                $response->getStatusCode(),
                $response->getReasonPhrase()
            );
        }
        // We have a valid response => process it.
        // Prepare the data structure to be parsed
        $response_data = $response->getBody()->getContents();
        if(empty($response_data))
        {
            return $this->returnError(
                "Error",
                "Empty response received"
            );
        }
        
        $data = json_decode($response_data);
        return $data;
    }

    public function parseSpacestations(mixed $space_stations)
    {
        $space_stations_array = [];
        foreach($space_stations->results as $key => $space_station)
        {
            $space_stations_array[$key]["id"] = $space_station->id;
            $space_stations_array[$key]["name"] = $space_station->name;
            $space_stations_array[$key]["status"] = $space_station->status;
            $space_stations_array[$key]["type"] = $space_station->type;
            $space_stations_array[$key]["founded"] = $space_station->founded;
            $space_stations_array[$key]["deorbited"] = $space_station->deorbited;
            $space_stations_array[$key]["description"] = $space_station->description;
            $space_stations_array[$key]["orbit"] = $space_station->orbit;
            $space_stations_array[$key]["owners"] = $space_station->owners;
            $space_stations_array[$key]["image_url"] = $space_station->image_url;
        }
        return $space_stations_array;
    }

    private function returnError($status_code, $reason) : array {
        return array(
            "code" => $status_code,
            "reason" => $reason
        );
    }
}
