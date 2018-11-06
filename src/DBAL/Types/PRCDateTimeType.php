<?php

namespace App\DBAL\Types;

use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;


class PRCDateTimeType extends DateTimeType
{
    static private $prc = null;

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if (is_null(self::$prc)) {
            self::$prc = new \DateTimeZone('PRC');
        }

        $value->setTimeZone(self::$prc);

        return $value->format($platform->getDateTimeFormatString());
    }

    // public function convertToPHPValue($value, AbstractPlatform $platform)
    // {
    //     if ($value === null) {
    //         return null;
    //     }

    //     if (is_null(self::$prc)) {
    //         self::$prc = new \DateTimeZone('PRC');
    //     }

    //     $val = \DateTime::createFromFormat($platform->getDateTimeFormatString(), $value, self::$prc);

    //     if (!$val) {
    //         throw ConversionException::conversionFailed($value, $this->getName());
    //     }

    //     return $val;
    // }
}
