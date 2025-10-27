<?php

namespace App\Services;

class UnitConversionService
{
    private const FEET_TO_CM = 30.48;
    private const LBS_TO_G = 453.592;
    private const YARDS_TO_M = 0.9144;

    public function toCm(float $value, string $unit): float
    {
        if ($unit === "feet"){
            return $value * self::FEET_TO_CM;
        }
        return  $value;
    }public function toGrams (float $value, string $unit): float
{
    if ($unit === "lbs"){ // <-- CORREÇÃO
        return $value * self::LBS_TO_G;
    }
    return $value;
}
    public function toMeters(float $value, string $unit) : float
    {
        if ($unit === "yards"){ // <-- CORREÇÃO
            return $value * self::YARDS_TO_M;
        }
        return $value;
    }
}
