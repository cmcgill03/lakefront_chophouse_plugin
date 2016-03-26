<?php
/*
 * Plugin Name: Lakefront Chophouse Testimonials
 * Plugin URI: http://phoenix.sheridanc.on.ca/~ccit3441
 * Description: Create a widget that allows you to display a certain number of testimonials
 * Author: Adam, Cameron, Connor
 * Author URI: http://phoenix.sheridanc.on.ca/~ccit3441
 * Version: 1.0
 */
 
 
 //enqueues the style sheet if needed
function lakefront_plguin(){ 
	wp_enqueue_style( 'lakefront_plugin_style', plugins_url('/lakefront_plugin_style.css', __FILE__) );
}
add_action('wp_enqueue_scripts','lakefront_plugin');






function testimonials($atts, $content = null) { //declare the function for the shortcode
        extract(shortcode_atts(array(
                "num" => '1', //how many posts will be shown
                "posttype" => 'testimonials' //which category the posts will be shown from
        ), $atts));
		
        global $post; //declares a global variable for post
		
        $myposts = get_posts('numberposts='.$num.'&orderby=rand&post_type='.$posttype); //gets the posts based on the attributes given and in a random order
        foreach($myposts as $post) : //sets up the for loop if it is looking for more than one post
                setup_postdata($post);
             $testimonial= the_title().'</br>' . the_content(); //determines what information will be displayed
        endforeach;
        return $testimonial; //displays the title and the content
}
add_shortcode('testimonials', 'testimonials'); 
 


/*Call the widget */

//require plugins_url( 'C:/MAMP/htdocs/Wordpress1/wp-content/plugins/lakefront_chophouse_plugin/lakefront_widget.php', dirname(__FILE__) ); 

?>