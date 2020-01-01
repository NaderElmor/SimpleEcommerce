<?php

function lang($phrase)
{
    static $lang = array
    (
        'home' => 'الرئيسية',
        'categories'  => 'الأصناف',
        'items'       => 'العناصر',
        'members'     => 'الأعضاء',
        'comments'    => 'التعليقات',
        'adminName'   => 'نادر',
        'editProfile' => 'تعديل البروفايل',
        'settings'    => 'الإعدادات',
        'logout'      => 'تسجيل الخروج',
        'visitShop'   => 'زيارة المتجر'
    );

    return $lang[$phrase];

}