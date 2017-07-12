 <?php
 if (elgg_is_active_plugin('elggx_userpoints')){
 
 
		 $plugin = elgg_get_plugin_from_id('elggx_userpoints');
		 ?>
		  <table>
		
		    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		
		    <tr><td><h3><?php echo elgg_echo('userpoints_standard:activities'); ?></h3></td><td>&nbsp;</td></tr>
		    <tr><td colspan="2"><hr /><br /></td></tr>
		
		    <tr>
		        <td width="40%"><label><?php echo elgg_echo('userpoints_standard:friend'); ?></label></td>
		        <td><?php echo elgg_view('output/text', array('value' => $plugin->friend)); ?></td>
		    </tr>
		
		    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		
		    <tr>
		        <td width="40%"><label><?php echo elgg_echo('userpoints_standard:blog'); ?></label></td>
		        <td><?php echo elgg_view('output/text', array('value' => $plugin->blog)); ?></td>
		    </tr>
		
		    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		
		    <tr>
		        <td width="40%"><label><?php echo elgg_echo('userpoints_standard:file'); ?></label></td>
		        <td><?php echo elgg_view('output/text', array('value' => $plugin->file)); ?></td>
		    </tr>
		
		    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		
		    <tr>
		        <td width="40%"><label><?php echo elgg_echo('userpoints_standard:bookmarks'); ?></label></td>
		        <td><?php echo elgg_view('output/text', array('value' => $plugin->bookmarks)); ?></td>
		    </tr>
		
		    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		
		    <tr>
		        <td><label><?php echo elgg_echo('userpoints_standard:comment'); ?></label></td>
		        <td><?php echo elgg_view('output/text', array( 'value' => $plugin->generic_comment)); ?></td>
		    </tr>
		
		    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		
		    <tr>
		        <td><label><?php echo elgg_echo('userpoints_standard:riverpost'); ?></label></td>
		        <td><?php echo elgg_view('output/text', array('value' => $plugin->riverpost)); ?></td>
		    </tr>
		
		    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		
		    <tr>
		        <td><label><?php echo elgg_echo('userpoints_standard:thewire'); ?></label></td>
		        <td><?php echo elgg_view('output/text', array('value' => $plugin->thewire)); ?></td>
		    </tr>
		
		    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		
		    <tr>
		        <td><label><?php echo elgg_echo('userpoints_standard:group'); ?></label></td>
		        <td><?php echo elgg_view('output/text', array( 'value' => $plugin->group)); ?></td>
		    </tr>
		
		    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
			
		    <tr>
		        <td><label><?php echo elgg_echo('userpoints_standard:group_topic_post'); ?></label></td>
		        <td><?php echo elgg_view('output/text', array('value' => $plugin->group_topic_post)); ?></td>
		    </tr>
		
		    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		
		    <tr>
		        <td><label><?php echo elgg_echo('userpoints_standard:profile'); ?></label></td>
		        <td><?php echo elgg_view('output/text', array( 'value' => $plugin->profileupdate)); ?></td>
		    </tr>
		
		    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
			
		    <tr>
		        <td><label><?php echo elgg_echo('userpoints_standard:messageboard'); ?></label></td>
		        <td><?php echo elgg_view('output/text', array( 'value' => $plugin->messageboard)); ?></td>
		    </tr>
		
		    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		
		    <tr>
		        <td><label><?php echo elgg_echo('userpoints_standard:page_top'); ?></label></td>
		        <td><?php echo elgg_view('output/text', array('value' => $plugin->page_top)); ?></td>
		    </tr>
		
		    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		
		    <tr>
		        <td><label><?php echo elgg_echo('userpoints_standard:likes'); ?></label></td>
		        <td><?php echo elgg_view('output/text', array('value' => $plugin->likes)); ?></td>
		    </tr>

		
		    <tr>
		        <td><label><?php echo elgg_echo('userpoints_standard:login'); ?></label></td>
		        <td><?php echo elgg_view('output/text', array('value' => $plugin->login)); ?></td>
		    </tr>
		

		
		    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
		
		    <tr>
			
		  </table>
		
<?php

} //endif check for userpoints plugin


