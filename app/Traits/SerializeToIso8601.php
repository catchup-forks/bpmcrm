<?php
namespace App\Traits;

use DateTimeInterface;
/**
 * Trait that allows that all dates of an Eloquent model be serialized in ISO 8601 format
 *
 * @package app\Traits
 */
trait SerializeToIso8601
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('c');
    }

}