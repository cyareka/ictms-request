<?php

namespace App\Helpers;

class IDGenerator
{
    public function __construct() {

    }

    // generate IDs for Office, Employee, Conference Room, Conference Request, and Vehicle Request
    public function generateID_CR(): string
    {
        $currentYear = date('Y');
        do {
            $randomNumber = str_pad(mt_rand(10, 9999), 4, '0', STR_PAD_LEFT);
        } while (preg_match('/^0[1-9]/', $randomNumber));

        return 'CR' . $currentYear . $randomNumber;
    }

    public function generateID_10(): string
    {
        $currentYear = date('Y');
        $currentMonth = date('m');
        $randomNumber = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        return $currentYear . $currentMonth . $randomNumber;
    }

    public function generateID_VR(): string
    {
        $currentYear = date('Y');
        do {
            $randomNumber = str_pad(mt_rand(10, 9999), 4, '0', STR_PAD_LEFT);
        } while (preg_match('/^0[1-9]/', $randomNumber));

        return 'VR' . $currentYear . $randomNumber;
    }

    // generate IDs for Vehicle
    public function generateID_3(): string
    {
        do {
            $randomNumber = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        } while ($randomNumber[0] === '0');

        return $randomNumber;
    }

    public function generateID_8(): string
    {
        $currentYear = date('Y');
        $randomNumber = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        return substr($currentYear . $randomNumber, 0, 8);
    }
}
