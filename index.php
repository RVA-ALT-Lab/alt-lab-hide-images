<?php 
/*
Plugin Name: ALT Lab Hide Images on a Timer
Plugin URI:  https://github.com/
Description: For stuff that's magical
Version:     1.0
Author:      ALT Lab
Author URI:  http://altlab.vcu.edu
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: my-toolset

*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


add_action('wp_enqueue_scripts', 'prefix_load_scripts');

function prefix_load_scripts() {                           
    $deps = array('jquery');
    $version= '1.0'; 
    $in_footer = true;    
    wp_enqueue_script('hide-images-main-js', plugin_dir_url( __FILE__) . 'js/hide-images-main.js', $deps, $version, $in_footer); 
    wp_enqueue_style( 'hide-images-main-css', plugin_dir_url( __FILE__) . 'css/hide-images-main.css');
}



function hide_images_timer_builder($content){
    if( have_rows('image_data') ):
      $html = '';
      // Loop through rows.
      while( have_rows('image_data') ) : the_row();

          // Load sub field value.
          $image = get_sub_field('image');
          $time = get_sub_field('time_to_show');
          if(get_sub_field('alt_text')){
            $alt = get_sub_field('alt_text');
          } else {
            $alt = "An image for sketching.";
          }
          // Do something...

        $html .= '<div class="timed-image-holder" data-show="' . $time . '">';
        $html .= '<div class="show-time">You will have ' . $time . ' seconds</div>';
        $html .= '<button class="timer">Start</button>';
        $html .= '<div class="progress-bar"></div>';
        $html .= '<img src="' . $image . '" class="hide" alt="' . $alt . '">';
        $html .= '<div class="finished"><img class="sunset" src="' . plugin_dir_url(__FILE__) . '/imgs/sunset.png" alt="A setting sun icon."><h2>Time has expired.<h2></div>';
        $html .= '</div>';

      // End loop.
      endwhile;

  // No value.
  else :
      // Do something...
  endif;
  return $content . $html;
}

add_filter( 'the_content', 'hide_images_timer_builder', 10);



//LOGGER -- like frogger but more useful

if ( ! function_exists('write_log')) {
   function write_log ( $log )  {
      if ( is_array( $log ) || is_object( $log ) ) {
         error_log( print_r( $log, true ) );
      } else {
         error_log( $log );
      }
   }
}

  //print("<pre>".print_r($a,true)."</pre>");

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
  'key' => 'group_5f160cbe25d5b',
  'title' => 'Images',
  'fields' => array(
    array(
      'key' => 'field_5f160cc9397b9',
      'label' => 'Image Data',
      'name' => 'image_data',
      'type' => 'repeater',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'collapsed' => '',
      'min' => 0,
      'max' => 0,
      'layout' => 'table',
      'button_label' => '',
      'sub_fields' => array(
        array(
          'key' => 'field_5f160cd3397ba',
          'label' => 'Image',
          'name' => 'image',
          'type' => 'image',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '30',
            'class' => '',
            'id' => '',
          ),
          'return_format' => 'url',
          'preview_size' => 'medium',
          'library' => 'all',
          'min_width' => '',
          'min_height' => '',
          'min_size' => '',
          'max_width' => '',
          'max_height' => '',
          'max_size' => '',
          'mime_types' => '',
        ),
        array(
          'key' => 'field_5f160d08397bb',
          'label' => 'Time to Show',
          'name' => 'time_to_show',
          'type' => 'number',
          'instructions' => 'In seconds',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '20',
            'class' => '',
            'id' => '',
          ),
          'default_value' => '',
          'placeholder' => '',
          'prepend' => '',
          'append' => '',
          'min' => '',
          'max' => '',
          'step' => '',
        ),
        array(
          'key' => 'field_5f160d41397bc',
          'label' => 'Alt Text',
          'name' => 'alt_text',
          'type' => 'text',
          'instructions' => 'Describe your image in a way that\'s meaningful for those who may have trouble viewing it.',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'default_value' => '',
          'placeholder' => '',
          'prepend' => '',
          'append' => '',
          'maxlength' => '',
        ),
      ),
    ),
  ),
  'location' => array(
    array(
      array(
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'post',
      ),
    ),
  ),
  'menu_order' => 0,
  'position' => 'normal',
  'style' => 'default',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'hide_on_screen' => '',
  'active' => true,
  'description' => '',
));

endif;


 //ACF JSON SAVER
  add_filter('acf/settings/save_json', 'hide_images_json_save_point');
   
  function hide_images_json_save_point( $path ) {
      
      // update path
      $path = plugin_dir_path(__FILE__) . '/acf-json';
      
      // return
      return $path;
      
  }

  //ACF JSON LOADER
  add_filter('acf/settings/load_json', 'hide_images_acf_json_load_point');

  function hide_images_acf_json_load_point( $paths ) {
      
      // remove original path (optional)
      unset($paths[0]);    
      
      // append path
      $path = plugin_dir_path(__FILE__) . '/acf-json';
      
      // return
      return $paths;
      
  }
