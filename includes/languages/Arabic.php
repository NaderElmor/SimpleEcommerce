<?php

function lang($phrase)
{
    static $lang = array
    (
        'home' => 'الرئيسية',
        'name' => 'نادر المر'
    );

    return $lang[$phrase];

}