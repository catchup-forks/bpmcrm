<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Application Data
 *
 * @package app\Models
 */
final class JsonData extends Model
{
     public static function timezones(){
         return json_decode(file_get_contents(resource_path(). '/js/data/timeszones.json'), false, 512, JSON_THROW_ON_ERROR);
     }

     public static function states(){
      return json_decode(file_get_contents(resource_path(). '/js/data/states_hash.json'), true, 512, JSON_THROW_ON_ERROR);
     }

     public static function countries(){
      return json_decode(file_get_contents(resource_path(). '/js/data/countries.json'), true, 512, JSON_THROW_ON_ERROR);
     }

    public static function datetimeFormats(){
        return json_decode(file_get_contents(resource_path(). '/js/data/datetime_formats.json'), true, 512, JSON_THROW_ON_ERROR);
    }
}
