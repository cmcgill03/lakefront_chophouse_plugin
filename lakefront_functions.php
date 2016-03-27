<?php
/*
 * Plugin Name: Lakefront Chophouse Testimonials
 * Plugin URI: http://phoenix.sheridanc.on.ca/~ccit3441
 * Description: Create a widget that allows you to display a certain number of testimonials
 * Author: Adam, Cameron, Connor
 * Author URI: http://phoenix.sheridanc.on.ca/~ccit3441
 * Version: 1.0
 */
 
/***********************************************
  Custom Post Type(s)
  
  Based on register_post_type rules from codex
************************************************/
function lakefront_custom_posttype () {
	
	//Menu Item Custom Post Type
	 $labels = array( //Changing all the labels to relate to custom post type
        'name'               => 'Menu Items',
        'singular_name'      => 'Menu Item',
        'menu_name'          => 'Menu Items',
        'name_admin_bar'     => 'Menu Item',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Menu Item',
        'new_item'           => 'New Menu Item',
        'edit_item'          => 'Edit Menu Item',
        'view_item'          => 'View Menu Item',
        'all_items'          => 'All Menu Items',
        'search_items'       => 'Search Menu Items',
        'parent_item_colon'  => 'Parent Menu Items:',
        'not_found'          => 'No Menu Items found.',
        'not_found_in_trash' => 'No Menu Items found in Trash.',
    );
    
    $args = array(
        'labels'             => $labels, //uses the labels we just declared
        'public'             => true, //makes it public use
        'show_in_menu'       => true, // allows it be shown in the menu
        'menu_icon'          => 'dashicons-carrot', //changes the admin icon
        'rewrite'            => array( 'slug' => 'menu-item' ), //changes the slug name
        'capability_type'    => 'post', //declares the type
        'has_archive'        => true, //allows it to be archived
        'menu_position'      => 5, //changes position of custom post type
        'supports'           => array( 'title', 'editor', 'thumbnail' ), //supports title, editor box and thumbnail
		'taxonomies'         => array( 'category', 'post_tag' ) //Allows categories and tags
    );
	
	//Testimonial Custom Post Type
	$labels2 = array( //Changing all the labels to relate to custom post type
        'name'               => 'Testimonials',
        'singular_name'      => 'Testimonial',
        'menu_name'          => 'Testimonials',
        'name_admin_bar'     => 'Testimonial',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Testimonial',
        'new_item'           => 'New Testimonial',
        'edit_item'          => 'Edit Testimonial',
        'view_item'          => 'View Testimonial',
        'all_items'          => 'All Testimonials',
        'search_items'       => 'Search Testimonials',
        'parent_item_colon'  => 'Parent Testimonials:',
        'not_found'          => 'No testimonials found.',
        'not_found_in_trash' => 'No testimonials found in Trash.',
    );
    
    $args2 = array(
        'labels'             => $labels2, //uses the labels we just declared
        'public'             => true, //makes it public use
        'show_in_menu'       => true, // allows it be shown in the menu,
        'menu_icon'          => 'dashicons-testimonial', //changes the admin icon
        'rewrite'            => array( 'slug' => 'testimonials' ), //changes the slug name
        'capability_type'    => 'post', //declares the type
        'has_archive'        => true, //allows it to be archived
        'menu_position'      => 5, //changes position of custom post type
        'supports'           => array( 'title', 'editor' ) //supports title, and editor box
    );
	
	register_post_type( 'menu_items', $args); //Register menu items custom post type
	register_post_type( 'testimonials', $args2); //Register testimonials custom post type
}
 
 //function will run when the plugin is initiated 
add_action ('init', 'lakefront_custom_posttype' );

/***********************************************
  Permalink Slug
  
  From Codex - used when changing the slug name as we did above for custom post type
************************************************/
function my_rewrite_flush() {
    lakefront_custom_posttype();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'my_rewrite_flush' );
 
/***********************************************
  StyleSheet
************************************************/
function lakefront_plguin(){ //enqueues the stylesheet
	wp_enqueue_style( 'lakefront_plugin_style', plugins_url('/lakefront_plugin_style.css', __FILE__) );
}
add_action('wp_enqueue_scripts','lakefront_plugin');


/***********************************************
  Shortcodes
************************************************/
function testimonials($atts, $content = null) { //declare the function for the shortcode
        extract(shortcode_atts(array(
                "num" => '1', //how many posts will be shown
                "posttype" => 'testimonials' //which post type the posts will be shown from
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
 

 
/***********************************************
 Widget
************************************************/
 class lakefront_testimonial extends WP_Widget {
	public function __construct() {
		$lakefront_widget = array(
		'classname' => 'widget_sod',
		'description' => __( 'Display the Special(s) of the Day.') );
		parent::__construct('special', __('Specials', 'lakefront_chophouse'), $lakefront_widget);
	} 
	
	//Front End
	public function widget( $args, $instance ) {
		//$count = $instance['count'];
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Special of the Day', 'lakefront') : $instance['title'], $instance, $this->id_base); 
		// Determines if there's a user-provided title and if not, displays a default title.
		
		echo $args['before_widget']; // what's set up when you registered the sidebar
		
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		/*****************
		loop to search through post type and get certain amount of posts
		******************
		$args = array( 
			'post_type' => 'testimonials', 
			'posts_per_page' => 3,
			'orderby' => 'rand'
		);

		$testimonials = new WP_Query( $args );
		while ( $testimonials->have_posts() ) : $testimonials->the_post();
			echo '<div class="testimonial">';
			echo '<figure class="testimonial-thumb">';
			the_post_thumbnail('medium');
			echo '</figure>';
			echo '<h1 class="entry-title">' . get_the_title() . '</h1>';
			echo '<div class="entry-content">';
			the_content();
			echo '</div>';
			echo '</div>';
		endwhile;
		*/
		
		echo $args['after_widget']; // what's set up when you registered the sidebar
	}
	
	
	//Back end
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'count' => 1) );
		$title = strip_tags($instance['title']);
		$count = $instance['count'];
?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
		<input
		class="widefat"
		id="<?php echo $this->get_field_id('title'); ?>"
		name="<?php echo $this->get_field_name('title'); ?>"
		type="text" value="<?php echo esc_attr($title); ?>" 
		/>
		</p>
		
<?php }

}

add_action( 'widgets_init', function(){ register_widget('lakefront_testimonial' ); }); 



?>