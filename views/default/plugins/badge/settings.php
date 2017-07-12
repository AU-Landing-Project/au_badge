<?php

/* settings:
* only admin can create badge
* allow multiple badges-per-user
* allow inheritable badges
* enable group badges
*/

//admin only to create badges
 echo "<div>";
 // toggle next login notification
  $options = array(
      'name' => 'params[admin_only_badges]',
      'value' => $vars['entity']->admin_only_badges ? $vars['entity']->admin_only_badges : 'no',
      'options_values' => array(
          'yes' => elgg_echo('option:yes'),
          'no' => elgg_echo('option:no')
      ),
  );
  echo elgg_echo('badge:settings:admin_only') . "<br>";
  echo elgg_view('input/dropdown', $options) . "<br><br>";   
 echo "</div>";
//self-award
 echo "<div>";
  $options = array(
      'name' => 'params[badge_self_award]',
      'value' => $vars['entity']->badge_self_award ? $vars['entity']->badge_self_award : 'no',
      'options_values' => array(
          'yes' => elgg_echo('option:yes'),
          'no' => elgg_echo('option:no')
      ),
  );
  echo elgg_echo('badge:settings:self_award') . "<br>";
  echo elgg_view('input/dropdown', $options) . "<br><br>" ;  	
 echo "</div>";