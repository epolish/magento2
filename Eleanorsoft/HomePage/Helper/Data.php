<?php

namespace Eleanorsoft\HomePage\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public static function formatCurrency($value)
    {
        return !$value ? null : number_format($value, 2, '.', ',');
    }

    public static function getSavedInfo($msrp, $price)
    {
        return !$msrp ? null : [
            'difference' => $msrp - $price,
            'percent' => round(($price / $msrp) * 100)
        ];
    }
}
