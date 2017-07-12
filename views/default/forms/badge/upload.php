<?php
/**
 * Elgg badge upload/save form
 *
 * @package ElggFile
 */

$title = elgg_extract('title', $vars, '');
$desc = elgg_extract('description', $vars, '');
$criteria = elgg_extract('criteria', $vars, '');
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$lifespan = elgg_extract('lifespan', $vars, '');
$inheritable = elgg_extract('inheritable', $vars, '');
$musthavebadge = elgg_extract('musthavebadge',$vars,'');
$userpoints = elgg_extract('userpoints',$vars,0);

//who can award this badge - defaults to private so only creator can award it
$award_access_id = elgg_extract('award_access_id', $vars, ACCESS_PRIVATE);

$container_guid = elgg_extract('container_guid', $vars);
if (!$container_guid) {
	$container_guid = elgg_get_logged_in_user_guid();
}
$guid = elgg_extract('guid', $vars, null);

if ($guid) {
	$badge_label = elgg_echo("badge:replace");
	$submit_label = elgg_echo('save');
} else {
	$badge_label = elgg_echo("badge:badge");
	$submit_label = elgg_echo('upload');
}


?>
<div>
	<label><?php echo $badge_label; ?></label><br />
	<em><?php echo elgg_echo('badge:upload:help'); ?></em><br />
	<?php echo elgg_view('input/file', array('name' => 'upload')); ?>
</div>
<div>
	<label><?php echo elgg_echo('title'); ?></label><br />
	<?php echo elgg_view('input/text', array('name' => 'title', 'value' => $title)); ?>
</div>
<div>
	<label><?php echo elgg_echo('description'); ?></label>
	<br />	<em><?php echo elgg_echo('badge:desc:help'); ?></em><br />
	<?php echo elgg_view('input/longtext', array('name' => 'description', 'value' => $desc)); ?>
</div>
<div>
	<label><?php echo elgg_echo('badge:criteria'); ?></label>
	<br />	<em><?php echo elgg_echo('badge:criteria:help'); ?><em><br />

	<?php echo elgg_view('input/longtext', array('name' => 'criteria', 'value' => $criteria)); ?>
</div>

<div>
	<label><?php echo elgg_echo('tags'); ?></label>
	<?php echo elgg_view('input/tags', array('name' => 'tags', 'value' => $tags)); ?>
</div>
<?php

$categories = elgg_view('input/categories', $vars);
if ($categories) {
	echo $categories;
}

?>
<div>
<br />
<p>	<label><?php echo elgg_echo('access'); ?></label><br />
	<?php echo elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id)); ?>
</p>
</div>

<div>
<br />
<p>
	<label><?php echo elgg_echo('badge:assign'); ?></label><br />
		<em><?php echo elgg_echo('badge:assign:help'); ?></em><br />
			<?php
	// we are only allowing private and everyone else 
	// further limits can be placed via inheritance
	// to do: would be nice to have group-only permission if in group context
	$options_values= array(ACCESS_PRIVATE=>elgg_echo('PRIVATE'),
						ACCESS_LOGGED_IN=>elgg_echo('LOGGED_IN'),
					);	
					
	$options = array(
		'name'=> 'award_access_id',
		'id' => 'award_access_id',
		'value'=> $award_access_id,
		'options_values'=>$options_values,
		
	);				
	echo elgg_view('input/dropdown', $options);
	?>

</p>
</div>

<div>
<br />

<p>
	<label><?php echo elgg_echo('badge:lifespan'); ?></label><br />
	<em><?php echo elgg_echo('badge:lifespan:help'); ?></em><br />
</p>
	<?php
	$options_values= array('0'=>elgg_echo('badge:indefinite'),
						'7'=>elgg_echo('badge:1week'),
						'31'=>elgg_echo('badge:1month'),
						'183'=>elgg_echo('badge:6months'),
						'365'=>elgg_echo('badge:1year'),
					);	
					
	$options = array(
		'name'=> 'lifespan',
		'id' => 'lifespan',
		'value'=> $lifespan,
		'options_values'=>$options_values,
		
	);				
	echo elgg_view('input/dropdown', $options);
	?>

</div>
<div>
<br />
	<p><label><?php echo elgg_echo('badge:musthavebadge'); ?></label><br />
	<em><?php echo elgg_echo('badge:musthavebadge:help'); ?></em><br />
	</p>
	<?php
	//to make it possible to only allow an award if awarder possesses a different badge
	//or this badge (inheritable)

	$options_badge_entities=array('0'=>elgg_echo('badge:nonespecified'));
	$options_badge_entities['this']= elgg_echo('badge:thisbadge');
	$badges=elgg_get_entities(array('type'=>'object', 'subtype'=>'badge'));
	foreach ($badges as $key=>$value){
		$thisguid=$badges[$key]->getGUID();
		$thisname=$badges[$key]->title;
		$options_badge_entities[$thisguid]=$thisname;
	}					
	$options = array(
		'name'=> 'musthavebadge',
		'id' => 'musthavebadge',
		'value'=> $musthavebadge,
		'options_values'=>$options_badge_entities,
		
	);				
	echo elgg_view('input/dropdown', $options);
	?>

</div>

<?php 
	if (elgg_is_active_plugin('elggx_userpoints')){  // automatic badge awarding
		
	?>
		<br />
		<div>
			<label><?php echo elgg_echo('badge:userpoints'); ?></label><br />
			<em><?php echo elgg_echo('badge:userpoints:help'); ?></em><br />
			
			<?php 
				echo elgg_view('badge/userpointssettings');
				echo "<p>". elgg_echo('badge:userpoints:howmany')."</p>"; 
				echo elgg_view('input/text', array('name' => 'userpoints', 'value' => $userpoints));
			 ?>
		</div>

<?php 
	} //endif check for userpoints plugin
?>
<div class="elgg-foot">
<?php

echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));

if ($guid) {
	echo elgg_view('input/hidden', array('name' => 'badge_guid', 'value' => $guid));
}

echo elgg_view('input/submit', array('value' => $submit_label));

?>
</div>
