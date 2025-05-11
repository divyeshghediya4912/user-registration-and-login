<?php
add_action( 'admin_menu', 'dew_add_admin_menu' );
function dew_add_admin_menu() {
	add_options_page( 'Dew Settings', 'Dew Settings', 'manage_options', 'dew-settings', 'dew_options_page' );
}

add_action( 'admin_init', 'dew_settings_init' );
function dew_settings_init() {
	register_setting( 'dewPlugin', 'dew_settings' );
	add_settings_section(
		'dew_dewPlugin_section',
		__( '', 'dew-registration-login' ),
		'dew_settings_section_callback',
		'dewPlugin'
	);

	add_settings_field(
		'dew_select_field_0',
		__( 'After Register Redirect Page ', 'dew-registration-login' ),
		'dew_select_field_0_render',
		'dewPlugin',
		'dew_dewPlugin_section',
		array( 'label_for' => 'dew_select_field_0', 'class' => 'dew-select' )
	);

	add_settings_field(
		'dew_select_field_1',
		__( 'After Login Redirect Page ', 'dew-registration-login' ),
		'dew_select_field_1_render',
		'dewPlugin',
		'dew_dewPlugin_section',
		array( 'label_for' => 'dew_select_field_1', 'class' => 'dew-select' )
	);

	add_settings_field(
		'dew_select_field_2',
		__( 'Registration Link on Login Page ', 'dew-registration-login' ),
		'dew_select_field_2_render',
		'dewPlugin',
		'dew_dewPlugin_section',
		array( 'label_for' => 'dew_select_field_2', 'class' => 'dew-select' )
	);
}

function dew_select_field_0_render() {
	$options = get_option( 'dew_settings' );
	$dew_select_field_0 = isset($options['dew_select_field_0']) ? $options['dew_select_field_0'] : '';
	global $post;
	$pages = get_posts( array(
		'posts_per_page' => -1,
		'post_type' => 'page'
	) );
	?>
	<select name='dew_settings[dew_select_field_0]'>
		<option value=''>Select Page</option>
		<?php if ( $pages ) {
			foreach ( $pages as $post ) : 
				setup_postdata( $post ); ?>
				<option value='<?php the_ID(); ?>' <?php selected( $dew_select_field_0, get_the_ID() ); ?>><?php the_title(); ?></option>
			<?php
			endforeach;
			wp_reset_postdata();
		} ?>
	</select>
	<?php
}

function dew_select_field_1_render() {
	$options = get_option( 'dew_settings' );
	$dew_select_field_1 = isset($options['dew_select_field_1']) ? $options['dew_select_field_1'] : '';
	global $post;
	$pages = get_posts( array(
		'posts_per_page' => -1,
		'post_type' => 'page'
	) );
	?>
	<select name='dew_settings[dew_select_field_1]'>
		<option value=''>Select Page</option>
		<?php if ( $pages ) {
			foreach ( $pages as $post ) : 
				setup_postdata( $post ); ?>
				<option value='<?php the_ID(); ?>' <?php selected( $dew_select_field_1, get_the_ID() ); ?>><?php the_title(); ?></option>
			<?php
			endforeach;
			wp_reset_postdata();
		} ?>
	</select>
	<?php
}

function dew_select_field_2_render() {
	$options = get_option( 'dew_settings' );
	$dew_select_field_2 = isset($options['dew_select_field_2']) ? $options['dew_select_field_2'] : '';
	global $post;
	$pages = get_posts( array(
		'posts_per_page' => -1,
		'post_type' => 'page'
	) );
	?>
	<select name='dew_settings[dew_select_field_2]'>
		<option value=''>Select Page</option>
		<?php if ( $pages ) {
			foreach ( $pages as $post ) : 
				setup_postdata( $post ); ?>
				<option value='<?php the_ID(); ?>' <?php selected( $dew_select_field_2, get_the_ID() ); ?>><?php the_title(); ?></option>
			<?php
			endforeach;
			wp_reset_postdata();
		} ?>
	</select>
	<?php
}

function dew_settings_section_callback() {
	echo __( '', 'dew-registration-login' );
}

function dew_options_page() {
	$tabs = array( 'general' => 'General', 'shortcodes' => 'Shortcodes' );
	$current = isset($_GET['tab']) ? $_GET['tab'] : 'general';
	?>
	<form action='options.php' method='post'>
		<nav class="nav-tab-wrapper ur-nav-tab-wrapper">
			<?php foreach( $tabs as $tab => $name ){
		        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
		        echo '<a class="nav-tab'.$class.'" href="' . admin_url( 'options-general.php?page=dew-settings&tab='.$tab).'">'.$name.'</a>';
		    } ?>
		</nav>
		<h2><?php echo esc_html( $tabs[ $current ] ); ?></h2>
		<?php
		switch ( $current ){
      		case 'general' :
				settings_fields( 'dewPlugin' );
				do_settings_sections( 'dewPlugin' );
				submit_button();
			break;
      		case 'shortcodes' :
      			echo '<table class="form-table" role="presentation"><tbody><tr class="dew-text"><th scope="row"><label for="registration_shortcode">Registration Shortcode</label></th><td><input type="text" name="registration_shortcode" id="registration_shortcode" value="[dew_register_form]" readonly></td></tr><tr class="dew-text"><th scope="row"><label for="login_shortcode">Login Shortcode</label></th><td><input type="text" name="login_shortcode" id="login_shortcode" value="[dew_signin_form]" readonly></td></tr><tr class="dew-text"><th scope="row"><label for="profile_shortcode">Profile Shortcode</label></th><td><input type="text" name="profile_shortcode" id="profile_shortcode" value="[dew_profile_form]" readonly></td></tr></tbody></table>';
			break;
		}
		?>
	</form>
	<?php
}