<?php 

/*
Plugin Name:PUbox
Plugin URI:
Description:This plugin enables user to add various effects with Pop ups
Author:Sreejith S
Author URI:http://about.me/sreejiths910
Version:1.0


  Copyright 2014  Sreejith S  

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA


*/
date_default_timezone_set('Asia/Kolkata');
//checking wp version
global $wp_version;
    if( !version_compare($wp_version,3.0,">=") )
    {
    	
    	error_log("<".date('Y-m-d h:i:sa').">"."You need at least version 3.0 or higher \r\n",3,ABSPATH.'wp-content/plugins/PUbox/log.txt');
    	die("You need at least version 3.0 or higher");
    } 

register_activation_hook(__FILE__,'PUbox_activation');
register_deactivation_hook(__FILE__,'PUbox_deactivation');

function PUbox_activation()
{
	//do what all is u want to do when plugin is activated ie create database,create options etc

	error_log("<".date('Y-m-d h:i:sa').">"."Plugin PUbox has been Activated \r\n",3,ABSPATH.'wp-content/plugins/PUbox/log.txt');

}

function PUbox_deactivation()
{
	//do something when plugin is deactivated
	error_log("<".date('Y-m-d h:i:sa').">"."Plugin PUbox has been De-activated \r\n",3,ABSPATH.'wp-content/plugins/PUbox/log.txt');
}

add_action('init','sjs_cp_PUbox');

function sjs_cp_PUbox()
{
	$PUbox_labels=array(

				'name'=>_x('Pop up',none),
				'singular_name'=>_x('Pop up',none),
				'add_new'=>_x('Add new pop up','adds new pop up'),
				'add_new_item'=>_x('Add New Pop Up',none)

		);

	$args=array(

			'labels'=>$PUbox_labels,
			'show_ui'=>true,
			'capability_type'=>'post',
			'hierarchical'=>false,
			'menu_position'=>5,
			'supports'=>array('')
		);
	register_post_type('cp_PUbox',$args);
}


add_action('admin_menu','sjs_settings');
function sjs_settings()
{
		//the first parameter is the title of the page when you access PUbox Options from Dashboard
		//@params: page title,menu title,capability,menu slug(id),call back func
		add_options_page('PUbox Settings','PUbox Options','administrator',__FILE__,'sjs_PUbox_disp_options_page');//here __FILE__ is used as a unique id for best practices,eg select PUbox Settings under Settings from Dashboard,then see at the url , where it shows ....?page=PUbox/PUbox.php which represents the path of file hence the unique id so it does not clash with other pages


		function sjs_PUbox_disp_options_page()
		{
			//the action options.php can be accessed by url manipulation,we have to make sure when we create options for the plugin it shows up here in the options.php page
			//enctype is used here to ensure that user can upload things
		?>
		
				<div class="wrap">
						<?php get_screen_icon(); ?>
						<h2>PU box Options Settings</h2>


						<form method="post" action="options.php" enctype="multipart/form-data">

								<?php settings_fields('sjs_PUbox_group'); //provides hidden inputs and nonces for security@params:group name?>
								<?php do_settings_sections(__FILE__); ?>
								<p class="submit">
										<input type="submit" name="submit" class="button-primary" value="Apply Changes">

								</p>

						</form>	


				</div>

		<?php
		}

		function register_settings_and_fields()
		{
			//@params:option_group,option_name(appears in options.php),sanitize_callback(optional,this can be used
			 // to sanitize ,validate and processing our inputs)
			//creates new option
			register_setting('sjs_PUbox_group','sjs_PUbox_options','sjs_validate_settings');//sjs_PUbox_options appears in options.php


				

			//@params:id,title of section,callback that handles validation/sanitization(fills the section with desired content),page(page should match $menu_slug)
			add_settings_section('sjs_first_section', 'First Section','sjs_first_section_contents',__FILE__);


			//(adds a field) add settings field function assumes you already know the settings $page and the page $section that the field should be shown on
			//@params:id of field,title of the field,callback that shows(echo out) the desired fields(text,select etc).,
			 //page(page should match $menu_slug), section name:which section this is going to be part of.

			add_settings_field('sjs_enter_text','Enter text','sjs_enter_text_settings',__FILE__,'sjs_first_section');
			add_settings_field('sjs_background_image','Add Background Image','sjs_background_image_settings',__FILE__,'sjs_first_section');


		}

		function sjs_validate_settings($plugin_options)
		{
			print_r($plugin_options);

		}

		function sjs_first_section_contents()
		{
			//optional
		}
		/*Inputs*/  
		//enter text
		function sjs_enter_text_settings()
		{
			//get_option('option_name');
				
				echo '<pre>';
					print_r($option_data);
				echo '</pre>';
			$option_data=get_option('sjs_PUbox_options');
			echo "<input type='text' name='sjs_PUbox_options[sjs_enter_text]' value=' ".$option_data['sjs_enter_text']." '>";//same name sjs_PUbox_options lets wp know its associated.
			 																			  //sjs_enter_text is the id of that field
																						  //the array $options_data contains serialised data/array of data that was updated when apply changes is hit.
 
		}
		function sjs_background_image_settings()
		{
			echo '<input type="file">';
		}

		add_action('admin_init','register_settings_and_fields');

}




?>