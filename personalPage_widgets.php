<?php 
/*
    Plugin Name: PersonalPageJK - Widgets
    Plugin URI:
    Description: Añade Widgets personalizados al sitio
    Version: 1.0.0
    Author: Juan Kaenel
    Author URI: https://twitter.com/juankaenel1
    Text Domain: PersonalPageJK
*/

if(!defined('ABSPATH')) die();

/**
 * Adds personalPage_cursos_Widget widget.
 */
class personalPage_cursos_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'foo_widget', // Base ID
			esc_html__( 'PersonalPage Cursos', 'text_domain' ), // Name
			array( 'description' => esc_html__( 'Agrega los cursos como widget', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
        echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
        $cantidad = $instance['cantidad']; // cantidad de cursos ingresados en el formulario
        if($cantidad == ''){
            $cantidad = 3;
        }
        
        ?>

        <ul>
            <?php 
                $args = array(
                        'post_type' => 'personalPage_Cursos',
                        'posts_per_page'=> $cantidad,
                        /* 'posts_per_page' => 3, */
                       /*  'orderby' => 'rand' */
                    );
                    $cursos = new WP_Query($args);
                    while($cursos->have_posts()): $cursos->the_post();
                ?>
                <li class="sidebar-curso">
                    <div class="imagen">
                        <?php the_post_thumbnail('thumbnail');?>
                    </div>

                    <div class="contenido-curso">
                        <a href="<?php the_permalink(); ?>">
                            <h3><?php the_title(); ?></h3>
                        </a>
                        <p><?php the_field('descripcion_del_curso'); ?></p>
                    </div>
                </li>

                <?php endwhile; wp_reset_postdata(); ?>
        </ul>


        <?php 
		echo $args['after_widget'];
	}


	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database. 
     * 	 
     * */

    /**
    * Desplegaremos un formulario para agregar cantidad de cursos en el menú de widgets-sidebars
    **/
	public function form( $instance ) { 
		$cantidad = !empty($instance['cantidad']) ? $instance['cantidad'] : esc_html__('Cuántos cursos deseas mostrar?', 'personalPage'); ?>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('cantidad') ) ?>"></label>
                <?php esc_attr_e('¿Cuántos Cursos deseas mostrar?', 'personalPage') ?>
        
            <input 
            class="widefat" 
            id="<?php echo esc_attr( $this->get_field_id( 'cantidad' ) ); ?>" 
            name="<?php echo esc_attr( $this->get_field_name( 'cantidad' ) ); ?>" 
            type="number" 
            value="<?php echo esc_attr( $cantidad ); ?>" >
        </p>
    <?php 


    $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
    ?>
    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label> 
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
    /*Almacenará los datos del formulario */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['cantidad'] = ( ! empty( $new_instance['cantidad'] ) ) ? sanitize_text_field( $new_instance['cantidad'] ) : '';
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
	}

} 

function personalPage_registrar_widget() {
    register_widget( 'personalPage_cursos_Widget' );
}
add_action( 'widgets_init', 'personalPage_registrar_widget' ); 