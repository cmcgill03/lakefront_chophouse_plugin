<?php
/*
 * Widget Name: Lakefront Chophouse Testimonial Widget
 * Widget URI: http://phoenix.sheridanc.on.ca/~ccit3441/
 * Description:
 * Author: Adam, Cameron, Connor
 * Author URI: http://phoenix.sheridanc.on.ca/~ccit3441/
 * Version: 1.0
*/

class lakefront_testimonial extends WP_Widget {
	public function __construct() {
		$lakefront_widget = array(
		'classname' => 'widget_testimonial',
		'description' => __( 'Place a certain number of testimonials.') );
		parent::__construct('testimonial', __('Testimonials', 'lakefront_chophouse'), $lakefront_widget);
	} 
}

add_action( 'widgets_init', function(){ register_widget('lakefront_testimonial' ); }); 