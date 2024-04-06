<?php

namespace App\Service\Reservation;

abstract class DistanceServices
{



    /**
     * check if the user is in the place
     * @param $myLongitude
     * @param $myLatitude
     * @param $longitude
     * @param $latitude
     * @throws \Exception
     */
    public function checkInPlace($myLongitude, $myLatitude ,$longitude ,$latitude) : void
    {
        $distanceByMeter = $this->distance($myLatitude, $myLongitude, $latitude, $longitude, "M");
        //        if distance is greater than 5 meters
        if ($distanceByMeter > 5) {
            throw new \Exception(__('errors.not_in_place'), 405);
        }
    }





    /**
     * Calculate the distance between two points
     * @param float $myLatitude
     * @param float $myLongitude
     * @param float $latitude
     * @param float $longitude
     * @param string $string
     * @return float
     */
    protected function distance(float $myLatitude, float $myLongitude, float $latitude, float $longitude, string $string): float
    {
        $theta = $myLongitude - $longitude;
        $dist = sin(deg2rad($myLatitude)) * sin(deg2rad($latitude)) + cos(deg2rad($myLatitude)) * cos(deg2rad($latitude)) * cos(deg2rad($theta));
        $dist = acos(min(max($dist, -1.0), 1.0)); // Clamp the value to avoid NaN results
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        // Convert distance to the requested unit
        return match (strtoupper($string)) {
            "K" => ($miles * 1.609344),
            "M" => ($miles * 1609.344),
            default => $miles,
        };
    }
}
