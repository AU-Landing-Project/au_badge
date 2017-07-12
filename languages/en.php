<?php
/**
 * Elgg badge plugin language pack
 *
 * @package ElggBadge
 */

$english = array(

	/**
	 * Menu items and titles
	 */
	'badge' => "Badges",
	'badge:user' => "%s's badges",
	'badge:friends' => "Friends' badges",
	'badge:all' => "All site badges",
	'badge:edit' => "Edit badge",
	'badge:more' => "More badges",
	'badge:list' => "list view",
	'badge:gallery' => "gallery view",
	'badge:gallery_list' => "Gallery or list view",
	'badge:num_badges' => "Number of badges to display",
	'badge:user:gallery'=>'View %s gallery',
	'badge:upload' => "Upload a badge",
	'badge:upload:help' => "Uploaded badges will be resized as 90x90 pixel square PNG files. If possible they should start that way
							otherwise they will likely appear distorted, or pixellated, or colours may not match the original",
	'badge:replace' => 'Replace badge content (leave blank to not change badge)',
	'badge:list:title' => "%s's %s %s",
	'badge:title:friends' => "Friends'",

	'badge:add' => 'Create a new badge',

	'badge:badge' => "Badge",
	'badge:title' => "Title",
	'badge:desc' => "Description",
	'badge:desc:help' => 'Provide a brief description of this badge and what it is for.',
	'badge:criteria' => "Criteria for award",
	'badge:criteria:help' =>"Enter clear competences or qualities for which this badge should be awarded.",
	'badge:nocriteria'=>"No criteria specified",
	'badge:settings:self_award'=>"Allow users to award badges to themselves",
	'badge:settings:admin_only'=>"Only admins can create badges",
	'badge:nonewbadge'=>"You do not have sufficient rights to create a new badge",
	"badge:award:notallowed" => "Insufficient rights to do this",
	'badge:tags' => "Tags",
	'badge:moreinfo' => "More information about this badge",
	'badge:assign'=>"Who will be allowed to award this badge?",
	'badge:assign:help' => " 	You can allow others to award this badge if you wish. 
								Do <strong>not</strong> set this as private if you intend to allow
								anyone else to award this badge. <strong>Do</strong> set this as private
								if you want to be the only one with awarding rights for this badge.
								Note that you may set further limits by requiring the ability to award
								the badge to depend on having received this or another badge (option below)",
	'badge:assign:display' => "This badge can be awarded by ",
	'badge:assign:owner' => " its owner and no one else",
	'badge:assign:anyone' => "any registered user",							

	'badge:nobadge'=>"not a badge",
	'badge:notimage' => "This does not seem to be an image",
	'badge:none'=>"No badges to be found",
	'badge:award' =>"Award a badge",
	'badge:awardthis' => "Award with this badge",
	'badge:awardedto' => "Awarded to: ",
	'badge:award:to_for' =>"Award a badge to %s for %s",
	'badge:awardedfor' => 'URL providing evidence',
	'badge:award:success'=>'Badge successfully awarded',
	'badge:award:failure'=>'Problem saving badge, sorry',
	'badge:award:duplicate' => 'This badge has already been awarded',
	'badge:awarded'=>"Unclaimed badges",
	'badge:awarded:widget:description'=>'Badges awarded (not yet claimed)',
	'badge:award:all'=>"Awards given",
	'badge:awarder'=>"Awarded by ",
	'badge:awarded:all' => "All accepted awards",
	'badge:mine' => "Badges you created",
	'badge:awarded:mine' => "Awards you have made",
	'badge:awarded:unclaimed' => 'Not yet accepted',
	'badge:awarded:claimed'=> 'Accepted',
	'badge:unclaimed:owner' => 'Your unclaimed awards',
	'badge:unclaimed:all' => 'All unclaimed awards',
	'badge:claimedby'=>' accepted by ',
	'badge:unclaimedby' => 'not yet accepted by ',
	'badge:claim:success' => 'Successfully claimed badge ',
	'badge:claim:failure' => "Sorry, you cannot claim this badge. We are not sure why.",
	'badge:lifespan' => 'Awards made using this badge expire:',
	'badge:lifespan:help' => 'Choose a time period if you want awards made with this badge to have a limited lifespan',
	'badge:lifespan:days' => 'days',
	'badge:award:expired'=> 'This award has expired',
	'badge:expiry_date' => 'Date this award expires',
	'badge:expires:never' => "Award never expires",
	'badge:expires:never:display' => "Awards made using this badge never expire",
	'badge:1day'=>'One day',
	'badge:1week' => 'One week',
	'badge:1month'=>"One month",
	'badge:6months' => 'Six months',
	'badge:1year' => 'One year',
	'badge:indefinite' =>'Never',
	'badge:recipient'=>"Recipient",
	'badge:inheritable' =>"Inheritability",
	'badge:inheritable:caption' =>"Only recipients of this award can award it to others",
	'badge:inheritable:help' => "This allows you to limit the ability to award this badge to only those who have previously received it.
								Note that this does not override the permissions to award the badge that you have
								already set on this page so make sure the permissions are not set to private, otherwise
								no one but you will ever be able to award it.",
	'badge:recipients'=>'People who have accepted this badge',
	'badge:thisbadge'=>"This badge (only recipients of this badge can award it)",
	'badge:normal' => "Anyone with rights to see it can award this badge",
	'badge:auto' => "Badge awarded automatically",
	'badge:claim' => "Accept this badge",
	'badge:claim:title' => "Claimable badges",
	'badge:claimed'=>"Your accepted awards",
	'badge:claimed:widget:description'=>"Badges accepted",
	'badge:received'=>"You have this badge",
	'badge:error:norecipient'=>'No recipient provided',
	'badge:error:nobadge'=>'No badge specified',
	'badge:error:notuser'=>'Attempt to claim badge while not logged in',
	'badge:selected_user' => 'no specific post',	
	'badge:selected_entity' => 'the selected item',
	'badge:nonespecified'=>'No specific badge',
	'badge:musthavebadge'=>"To award this badge, the awarder must already have a badge",
	'badge:musthavebadge:help'=>"Optionally, choose a prerequisite badge. You may, if you wish, choose this badge itself, or any other
								badge to which you have access. 
								Only individuals who have been awarded the prerequisite badge can award this badge to others.
								<strong>This does not override other limits on who can award this badge</strong>, so 
								make sure the awarding permissions are set so that your intended badge-awarders are actually able to award it.",
	'badge:musthavebadge:none'=> "There are no prerequisite badges needed before someone can award this badge.",							
	'badge:list:list' => 'Switch to the list view',
	'badge:list:gallery' => 'Switch to the gallery view',
	'badge:userpoints' =>'Automatically awarded badge',
	'badge:userpoints:help' => "To automatically award this badge for user activity, enter the number of user points needed. Any 
								awards that are made automatically will be made in your name.
								Leave this blank or set to zero if you <em>don't</em> want this badge to be awarded automatically. 
								<br/ ><strong> What are user points? </strong>
								The system administrator has already set user points to be awarded for a range of activities, from 
								simply logging in to posting to a blog, making connections with others, commenting, and so on. If 
								you are not sure how many points are awarded for which activities, contact the system administrator. 
								The following list may be incomplete, depending on the features your administrator has enabled, but it
								should give you a sense of how user points are awarded on this site:",
	'badge:userpoints:display' => "Number of user points needed for this badge to be automatically awarded:",
	'badge:userpoints:none' => "This badge is not automatically awarded - it must be awarded by a person",
	'badge:userpoints:howmany' => 'How many points are needed to automatically make this award? (enter 0 if you do not wish an award to 
									be made automatically)',

	'badge:types' => "Uploaded badge types",

	'badge:type:' => 'Badges',
	'badge:type:all' => "All badges",
	'badge:type:video' => "Videos",
	'badge:type:document' => "Documents",
	'badge:type:audio' => "Audio",
	'badge:type:image' => "Pictures",
	'badge:type:general' => "General",

	'badge:user:type:video' => "%s's videos",
	'badge:user:type:document' => "%s's documents",
	'badge:user:type:audio' => "%s's audio",
	'badge:user:type:image' => "%s's pictures",
	'badge:user:type:general' => "%s's general badges",

	'badge:friends:type:video' => "Your friends' videos",
	'badge:friends:type:document' => "Your friends' documents",
	'badge:friends:type:audio' => "Your friends' audio",
	'badge:friends:type:image' => "Your friends' pictures",
	'badge:friends:type:general' => "Your friends' general badges",

	'badge:widget' => "Badge widget",
	'badge:widget:description' => "Showcase your latest badges",

	'badge:download' => "Download this",

	'badge:delete:confirm' => "Are you sure you want to delete this badge?",

	'badge:tagcloud' => "Tag cloud",

	'badge:display:number' => "Number of badges to display",

	'river:create:object:badge' => '%s uploaded the badge %s',
	'river:comment:object:badge' => '%s commented on the badge %s',
	'river:create:object:award' => '%s made a new award %s',
	'river:comment:object:award' => '%s commented on the awarded badge %s',


	'item:object:badge' => 'Badges',

	'badge:newupload' => 'A new badge has been uploaded',
	'badge:notification' =>
'%s uploaded a new badge:

%s
%s

View and comment on the new badge:
%s
',
	'badge:newaward'=> "A new award has been given",
	
	'badge:award:notification' =>
'%s awarded you with a badge:

%s
%s

To accept the badge, please visit:
%s
',	
	/**
	 * Embed media
	 **/

		'badge:embed' => "Embed media",
		'badge:embedall' => "All",

	/**
	 * Status messages
	 */

		'badge:saved' => "Your badge was successfully saved.",
		'badge:deleted' => "Your badge was successfully deleted.",

	/**
	 * Error messages
	 */

		'badge:none' => "No badges.",
		'badge:uploadfailed' => "Sorry; we could not save your badge.",
		'badge:downloadfailed' => "Sorry; this badge is not available at this time.",
		'badge:deletefailed' => "Your badge could not be deleted at this time.",
		'badge:award:deletefailed' => "Your award could not be deleted at this time.",
		'badge:noaccess' => "You do not have permissions to change this badge",
		'badge:cannotload' => "There was an error uploading the badge",
		'badge:nobadge' => "You must select a badge",
);

add_translation("en", $english);