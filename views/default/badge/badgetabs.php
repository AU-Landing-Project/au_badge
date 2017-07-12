<?php

//used to override the default tabs in order to show awards, etc


$tabs = array(

	    'myawards' => array(
        'title' => elgg_echo('badge:claimed'),
        'url' => "badge/claimed/".elgg_get_logged_in_user_guid() ,
        'selected' => $vars['selected'] == 'myawards',
    ),
        'mybadgepending' => array(
        'title' => elgg_echo('badge:unclaimed:owner'),
        'url' => "badge/awarded/".elgg_get_logged_in_user_guid() ,
        'selected' => $vars['selected'] == 'mybadgepending',
    ),
		'myawarded' => array(
        'title' => elgg_echo('badge:awarded:mine'),
        'url' => "badge/awardedby/".elgg_get_logged_in_user_guid() ,
        'selected' => $vars['selected'] == 'myawarded',
    ),
    	'allclaimed' => array(
        'title' => elgg_echo('badge:awarded:all'),
        'url' => "badge/claimed",
        'selected' => $vars['selected'] == 'allclaimed',
    ),
         'badgependingall' => array(
        'title' => elgg_echo('badge:unclaimed:all'),
        'url' => "badge/awarded" ,
        'selected' => $vars['selected'] == 'badgependingall',
    ),
 	  	'allbadges' => array(
        'title' => elgg_echo('badge:all'),
        'url' => "badge/all",
        'selected' => $vars['selected'] == 'allbadges',
    ),
     	'mybadges' => array(
        'title' => elgg_echo('badge:mine'),
        'url' => "badge/owner/".elgg_get_logged_in_user_guid() ,
        'selected' => $vars['selected'] == 'mybadges',
    ),
);

echo elgg_view('navigation/tabs', array('tabs' => $tabs));