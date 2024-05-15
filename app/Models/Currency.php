<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Currency extends Model
{
    public static function getRates()
    {
        $api = '3ZAyYRhrsJVAm1tVtN6IVgiI6w3KZMZ3f2w5ktAtqPhm72u7qE2EJoCczvdzry3ridWzY5UgHVlXMiO9abNw0kM4d82WbgEnK6K2yJpfEZl2s2guzOGtyVpl3yU7FthY';
        $url = 'https://www.completeapi.com/v1/';
        $action = '/currency/';
        $baseCurrency = 'ZAR';
        $response = Http::get($url . $api .$action . $baseCurrency);
        return $response->json();
    }

}
