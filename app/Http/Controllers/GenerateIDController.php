<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GenerateID extends Controller
{
    private function generateID_10() {
        $year = date('Y');
        $month = date('m');
        $randomNumber = mt_rand(1000, 9999);
        $id = $year . $month . $randomNumber;

        return $id;
    }

    private function generateID_3() {
        $randomNumber = mt_rand(100, 999);
        $id = (string) $randomNumber;

        return $id;
    }

    private function generateID_8() {
        $year = date('Y');
        $randomNumber = mt_rand(1000, 9999);
        $id = $year . $randomNumber;

        return $id;
    }
}
