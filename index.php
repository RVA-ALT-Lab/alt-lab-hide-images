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
          // Do something...

        $html .= '<div class="timed-image-holder" data-show="' . $time . '">';
        $html .= '<div class="show-time">You will have ' . $time . ' seconds</div>';
        $html .= '<button class="timer">Start</button>';
        $html .= '<div class="progress-bar"></div>';
        $html .= '<img src="' . $image . '" class="hide">';
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
