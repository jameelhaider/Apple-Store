<?php

if (!function_exists('iphone_models')) {
    function iphone_models(): array
    {
        return [
            'iPhone 17 Pro Max',
            'iPhone 17 Pro',
            'iPhone 17 Plus',
            'iPhone 17',
            'iPhone 16 Pro Max',
            'iPhone 16 Pro',
            'iPhone 16 Plus',
            'iPhone 16',
            'iPhone 15 Pro Max',
            'iPhone 15 Pro',
            'iPhone 15 Plus',
            'iPhone 15',
            'iPhone 14 Pro Max',
            'iPhone 14 Pro',
            'iPhone 14 Plus',
            'iPhone 14',
            'iPhone SE (3rd Gen)',
            'iPhone 13 Pro Max',
            'iPhone 13 Pro',
            'iPhone 13',
            'iPhone 13 mini',
            'iPhone 12 Pro Max',
            'iPhone 12 Pro',
            'iPhone 12',
            'iPhone 12 mini',
            'iPhone SE (2nd Gen)',
            'iPhone 11 Pro Max',
            'iPhone 11 Pro',
            'iPhone 11',
            'iPhone XS Max',
            'iPhone XS',
            'iPhone XR',
            'iPhone X',
            'iPhone 8 Plus',
            'iPhone 8',
            'iPhone 7 Plus',
            'iPhone 7',
            'iPhone SE (1st Gen)',
            'iPhone 6s Plus',
            'iPhone 6s',
            'iPhone 6 Plus',
            'iPhone 6',
            'iPhone 5s',
            'iPhone 5c',
            'iPhone 5',
            'iPhone 4s',
            'iPhone 4',
        ];
    }
}


function isValidImei($imei)
{
    $sum = 0;

    for ($i = 0; $i < 14; $i++) {
        $digit = (int) $imei[$i];

        if ($i % 2 === 1) {
            $digit *= 2;
            if ($digit > 9) {
                $digit -= 9;
            }
        }

        $sum += $digit;
    }

    $checkDigit = (10 - ($sum % 10)) % 10;

    return $checkDigit === (int) $imei[14];
}



function numberToWords($number)
{
    $f = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
    return ucfirst($f->format($number));
}

if (!function_exists('other_companies')) {
    function other_companies(): array
    {
        return [
            'Samsung',
            'Oppo',
            'Vivo',
            'Xiaomi',
            'Huawei',
            'Infinix',
            'Tecno',
            'Itel',
            'Google',
            'One Plus',
            'Honor',
        ];
    }
}
