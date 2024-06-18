<?php

namespace App\Services;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;

class GeoIPService
{
    protected $reader;

    public function __construct()
    {
        $databasePath = config('geoip.maxmind_database_path');
        $this->reader = new Reader($databasePath);
    }

    public function getCountryFromIp($ip)
    {
        try {
            $record = $this->reader->country($ip);
            return $record->country->isoCode;
        } catch (AddressNotFoundException $e) {
            // Handle address not found exception
            return null;
        } catch (\Exception $e) {
            // Handle other exceptions
            return null;
        }
    }
}
