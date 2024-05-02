<?php

namespace Vanier\Api\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Vanier\Api\Helpers\Validator;

class BmiController extends BaseController
{
    public function handleCalculateBmi(Request $request, Response $response, array $uri_args): Response
    {
        $parsed_body = $request->getParsedBody();
        
        $mass = $parsed_body["mass"] ?? "";
        $height = $parsed_body["height"] ?? "";
        
        $response = $this->calculateBmi($mass, $height, $response);
        return $response;
        
    }

    public function calculateBmi(mixed $mass, mixed $height, Response $response)
    {
        $validator = $this->validateBmiValues($mass, $height);
        if($validator->validate() === false)
        {
            $invalid_values_info = [
                "code" => 406,
                "message" => "Invalid BMI Values Provided",
                "description" => json_decode($validator->errorsToJson())
            ];
            return $this->makeResponse(
                $response,
                $invalid_values_info,
                406
            );
        }
        $calculated_bmi = round($mass / ($height * $height), 2);
        $bmi_class = $this->getBmiClassification($calculated_bmi);
        $calculated_response = [
            "bmi" =>  $calculated_bmi,
            "bmi_class" => $bmi_class,
            "units" => "metric"
        ];
        return $this->makeResponse($response, $calculated_response, 200);
    }

    public function getBmiClassification(float $bmi)
    {
        switch($bmi)
        {
            case $bmi < 16:
                return "Severe Thinness";
            case $bmi < 17:
                return "Moderate Thinness";
            case $bmi < 18.5:
                return "Mild Thinness";
            case $bmi < 25:
                return "Normal";
            case $bmi < 30:
                return "Overweight";
            default:
                return "Obese";
        }
    }

    private function validateBmiValues(mixed $mass, mixed $height)
    {
        $validator = new Validator([
            "mass" => $mass,
            "height" => $height
        ]);
        $rules = [
            "mass" => [
                "required",
                "numeric",
                ["min", 2]
            ],
            "height" => [
                "required",
                "numeric",
                ["min", 0.25]
            ]
        ];
        $validator->mapFieldsRules($rules);

        return $validator;
    }
}
