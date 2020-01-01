<?php

    function lang($phrase)
    {
       static $lang = array
        (
              // navbar page
             'home'        => 'Home',
             'categories'  => 'Categories',
             'items'       => 'Items',
             'members'     => 'Members',
             'comments'    => 'Comments',
             'adminName'   => 'Nader',
             'editProfile' => 'Edit Profile',
             'settings'    => 'Settings',
             'logout'      => 'Log Out',
             'visitShop'   => 'Visit Shop'

       );

       return $lang[$phrase];

    }