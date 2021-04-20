<?php


namespace App\Http\Controllers\Places;


use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class Places extends Controller
{
    public function __construct() { }

    public function getPlaces(Response $response)
    {
        try {
            if (!file_exists('places.csv'))
                throw new \Exception('File not found.', 404);
            $parsePlaces = str_getcsv(file_get_contents('places.csv'), "\r");
            $tmpPlace = [];
            array_shift($parsePlaces);
            foreach($parsePlaces as $place) {
                $tmp = explode(',"', $place);
                $place = array_shift($tmp);
                $coordinates = explode(',', str_replace('"', '', array_shift($tmp)));
                $tmpPlace['name'] = $place;
                $tmpPlace['coordinates'] = [
                    'latitude' => array_shift($coordinates),
                    'longitude' => array_shift($coordinates),
                ];
                $places[] = $tmpPlace;
            }
            return $response->setContent(['places' => $places ?? []])->setStatusCode(200);
        } catch(\Exception $exception) {
            return $response->setStatusCode($exception->getCode(), $exception->getMessage());
        }
    }
}
