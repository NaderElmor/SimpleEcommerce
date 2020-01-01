<?php

    function lang($phrase)
    {
       static $lang = array
        (
            //Dashboard page
             'home'        => 'Home',
             'categories'  => 'Categories',
             'items'       => 'Items',
             'members'     => 'Members',
             'comments'    => 'Comments',
             'statistics'  => 'Statistics',
             'logs'        => 'Logs',
             'adminName'   => 'Nader',
             'editProfile' => 'Edit Profile',
             'settings'    => 'Settings',
             'logout'      => 'Log Out'
        );

       return $lang[$phrase];

    }