<?php
/*
Plugin Name: Materialized Carousel
Plugin URI:  http://karim88.github.io/mcarousel
Description: A simple Material Disgn Carousel, based on Materializecss slider.
Version:     0.2
Author:      Karim Oulad Chalha
Author URI:  http://karim88.github.io
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: materialized-carousel
*/

class Materialized_Carousel_Widget extends WP_Widget {
	public static $options = array();
	function __construct(){
		parent::__construct(
			'material_carousel_widget',
			__('Materialized Carousel Widget', 'materialized-carousel'),
			array(
				'classname' => 'material_widget',
				'description' => __('A simple Material Disgn Carousel Widget Plugin', 'materialized-carousel'),
			)
		);
	}

	public function widget($args, $instance)
	{
		self::$options = $instance;
		$title = apply_filters('widget_title', $instance['title']);
		echo $args['before_widget'];
		$my_args =array(
				'numberposts' => $instance['postNumbers'],
				'offset'      => 0,
				'meta_key' => '_thumbnail_id',
				'post_status' => 'publish',
				);
				global $post;

				$posts = get_posts( $my_args );
		?>
		<div class="slider center">
    <ul class="slides">
			<?php
				foreach( $posts as $post ) :	setup_postdata($post);
			?>
			<li>
	<?php $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full'); ?>
				<a class="waves-effect waves-block" href="<?php the_permalink(); ?>" rel="bookmark"  title="<?php the_title_attribute(); ?>">
					<img class="waves-effect" src="<?php echo $thumbnail[0]; ?>" alt="<?php the_title(); ?>" title="<?php the_title_attribute(); ?>" />
					<div class="caption center-align">
						<h4 class="light grey-text text-lighten-3">
							<?php the_title(); ?>
						</h4>
					</div>
				</a>
			</li>
			<?php endforeach; ?>
    </ul>
		</div>
		<?php
	}

	public function form($instance)
	{
		$postNumbers = (isset($instance['postNumbers'])) ? $instance['postNumbers'] : 6;
		$title = (isset($instance['title'])) ? $instance['title'] : __('New Title', 'materialized-carousel');
		$full_width = (isset($instance['full_width'])) ? $instance['full_width'] : "true";
		$height = (isset($instance['height'])) ? $instance['height'] : 300;
		$indicators = (isset($instance['indicators'])) ? $instance['indicators'] : "true";
		$transition = (isset($instance['transition'])) ? $instance['transition'] : 500;
		$interval = (isset($instance['interval'])) ? $instance['interval'] : 6000;
		self::$options = $instance;
		?>
		<!-- HTML here -->
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title: ', 'materialized-carousel'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>

		<!-- full_width -->
		<p>
		<label for="<?php echo $this->get_field_id('full_width'); ?>"><?php _e('full Width: ', 'materialized-carousel'); ?></label>
		<p class="widefat">
		<?php _e('True ', 'materialized-carousel'); ?><input name="<?php echo $this->get_field_name('full_width'); ?>" type="radio" value="true"  <?php if($full_width === 'true'){ echo 'checked="checked"'; } ?>/>
		<?php _e('False ', 'materialized-carousel'); ?><input name="<?php echo $this->get_field_name('full_width'); ?>" type="radio" value="false" <?php if($full_width === 'false'){ echo 'checked="checked"'; } ?>/>
		</p>
		</p>


		<!-- postNumbers -->
		<p>
			<label for="<?php echo $this->get_field_id('postNumbers'); ?>"><?php _e('Number of posts: ', 'materialized-carousel'); ?></label>
			<input type="number" min="3" max="15" id="<?php echo $this->get_field_id('postNumbers'); ?>" name="<?php echo $this->get_field_name('postNumbers'); ?>" value="<?php echo esc_attr($postNumbers); ?>" class="widefat" />
		</p>

		<!-- height -->
		<p>
			<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height of slider: ', 'materialized-carousel'); ?></label>
			<input type="number" min="200" max="500" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" value="<?php echo esc_attr($height); ?>" class="widefat" />
		</p>
		<!-- indicators -->
		<p>
		<label for="<?php echo $this->get_field_id('indicators'); ?>"><?php _e('Slide indicator visiblity: ', 'materialized-carousel'); ?></label>
		<p class="widefat">
		<?php _e('True ', 'materialized-carousel'); ?><input name="<?php echo $this->get_field_name('indicators'); ?>" type="radio" value="true"  <?php if($indicators === 'true'){ echo 'checked="checked"'; } ?>/>
		<?php _e('False ', 'materialized-carousel'); ?><input name="<?php echo $this->get_field_name('indicators'); ?>" type="radio" value="false" <?php if($indicators === 'false'){ echo 'checked="checked"'; } ?>/>
		</p>
		</p>
		<!-- transition -->
		<p>
			<label for="<?php echo $this->get_field_id('transition'); ?>"><?php _e('Duration of slide transition: ', 'materialized-carousel'); ?></label>
			<input type="number" min="0" max="1000" id="<?php echo $this->get_field_id('transition'); ?>" name="<?php echo $this->get_field_name('transition'); ?>" value="<?php echo esc_attr($transition); ?>" class="widefat" />
		</p>
		<!-- interval -->
		<p>
			<label for="<?php echo $this->get_field_id('interval'); ?>"><?php _e('Duration between each slide: ', 'materialized-carousel'); ?></label>
			<input type="number" min="0" max="1000" id="<?php echo $this->get_field_id('interval'); ?>" name="<?php echo $this->get_field_name('interval'); ?>" value="<?php echo esc_attr($interval); ?>" class="widefat" />
		</p>
	<?php
	}

	public function update($new, $old)
	{
		$instance = $old;
		$instance['title'] = (!empty($new['title'])) ? strip_tags($new['title']) : $old['title'];
		$instance['postNumbers'] = (!empty($new['postNumbers'])) ? strip_tags($new['postNumbers']) : $old['postNumbers'];
		$instance['indicators'] = (!empty($new['indicators'])) ? strip_tags($new['indicators']) : $old['indicators'];
		$instance['full_width'] = (!empty($new['full_width'])) ? strip_tags($new['full_width']) : $old['full_width'];

		$instance['height'] = (!empty($new['height'])) ? strip_tags($new['height']) : $old['height'];
		$instance['transition'] = (!empty($new['transition'])) ? strip_tags($new['transition']) : $old['transition'];
		$instance['interval'] = (!empty($new['interval'])) ? strip_tags($new['interval']) : $old['interval'];
		self::$options = $instance;
		return $instance;
	}
}

function initjs()
{
	$instance = Materialized_Carousel_Widget::$options;
	?>
		<script type="text/javascript">
			if ( undefined !== window.jQuery ) {
					jQuery('.slider').slider({
						full_width: <?php echo $instance['full_width']; ?>,
						indicators: <?php echo $instance['indicators']; ?>,
						height: <?php echo $instance['height']; ?>,
						transition: <?php echo $instance['transition']; ?>,
						interval: <?php echo $instance['interval']; ?>,
					});
			}else{
				console.log('missing jQuery!');
			}
		</script>

	<?php
}

function myscripts() {
	wp_enqueue_style('materializecss', plugins_url('/css/materializecss.min.css', __FILE__), array());
	wp_enqueue_style('mystyle', plugins_url('/css/style.css', __FILE__), array());
	wp_enqueue_script('jquery1', plugins_url('/js/jquery-2.1.4.min.js', __FILE__), array(), '2.1.4');
	wp_enqueue_script('materializejs', plugins_url('/js/materialize.min.js', __FILE__), array('jquery1'));
}

function mc_load_widget()
{
	register_widget('Materialized_Carousel_Widget');
}
add_action('widgets_init', 'mc_load_widget');
add_action( 'wp_head' , 'myscripts');
add_action( 'wp_footer', 'initjs', 100);
load_plugin_textdomain('materialized-carousel', false, basename(dirname(__FILE__)) . '/languages');
?>
