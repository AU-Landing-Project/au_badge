<?php
//display who can award this
$badge=elgg_extract('entity');
echo elgg_echo('badge:assignment').$badge->award_access_id;