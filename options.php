<?php
/**
	* Settings for the Credit Line Generator.
	*/

add_action( 'admin_menu', 'creditline_add_admin_menu' );
add_action( 'admin_init', 'creditline_settings_init' );

function creditline_add_admin_menu() { 
	add_options_page( 'Credit Line Generator', 'Credit Line Generator', 'manage_options', 'credit_line_generator', 'creditline_options_page' );
}

function creditline_settings_init() { 
	register_setting( 'pluginPage', 'creditline_settings', array('sanitize_callback' => 'creditline_settings_sanitize') );

	add_settings_section(
		'creditline_pluginPage_section', 
		__( 'Extended Mark-up', CREDITLINE_DOMAIN ), 
		'creditline_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'creditline_checkbox_field_0', 
		__( 'Enable extended mark-up?', CREDITLINE_DOMAIN ), 
		'creditline_checkbox_field_0_render', 
		'pluginPage', 
		'creditline_pluginPage_section' 
	);

	add_settings_field( 
		'creditline_text_field_1', 
		__( 'HTML class name', CREDITLINE_DOMAIN ), 
		'creditline_text_field_1_render', 
		'pluginPage', 
		'creditline_pluginPage_section' 
	);

}

/* Render functions. */

function creditline_checkbox_field_0_render() { 
	$options = get_option( 'creditline_settings' );
	$checked = isset( $options['creditline_checkbox_field_0'] ) ? checked( $options['creditline_checkbox_field_0'], 1, false ) : '';
	creditline_print('<input type="checkbox" name="creditline_settings[creditline_checkbox_field_0]" ' . $checked . ' value="1">');
}

function creditline_text_field_1_render() { 
	$options = get_option( 'creditline_settings' );
	$this_option = $options['creditline_text_field_1'];
	if ( $this_option === '' ) {
		$this_option = 'creditline';
	}
	creditline_print('<input type="text" name="creditline_settings[creditline_text_field_1]" value="' . $this_option . '">');
	creditline_print('<ul><li>' . __( 'Only works if extended mark-up is enabled.', CREDITLINE_DOMAIN ) . '</li> <li>' . __( 'Use only lower case letters, digits, hyphens and underscores. The initial character must be a lower case letter.', CREDITLINE_DOMAIN ) . '</li> <li>' . __( 'If left empty, the value "creditline" will be used.', CREDITLINE_DOMAIN ) . '</li></ul>');
}

function creditline_settings_section_callback() { 
	echo __( 'By default, credit lines are generated as is, e.g. "Photo by John". The Extended Mark-up setting will wrap the credit in HTML mark-up that can then be styled, e.g. <code>&lt;span class="creditline"&gt;Photo by &lt;span class="author"&gt;John&lt;/span&gt;&lt;/span&gt;</code>.', CREDITLINE_DOMAIN );
}

/* Sanitisation. */

function creditline_settings_sanitize( $input ) {
	if( isset( $input['creditline_text_field_1'] ) ) {
		$new = $input['creditline_text_field_1'];
		if ( $new !== '' ) {
			$new = strtolower($new);
			$pattern = '/[^a-z0-9_-]/';
			$replacement = '';
			$new = preg_replace( $pattern, $replacement, $new );
			$first_char_is_not_a_letter = true;
			$pattern = '/^[^a-z]/';
			while ( $first_char_is_not_a_letter && $new !== '' ) {
				$old = $new; 
				$new = preg_replace( $pattern, $replacement, $new );
				if ( $new === $old ) {
					$first_char_is_not_a_letter = false;
				}
			}
			$new = substr( $new, 0, 240 );
		}
		if ( $new === '' ) {
			$new = 'creditline';
		}
		$input['creditline_text_field_1'] = $new;
	}
	if ( !isset( $input['creditline_checkbox_field_0'] ) ) {
		$input['creditline_checkbox_field_0'] = false;
  }
	
	return $input;
}

function creditline_options_page() { 
	creditline_print( '<form action="options.php" method="post">' );
	creditline_print( '<h2>Credit Line Generator</h2>' );
	settings_fields( 'pluginPage' );
	do_settings_sections( 'pluginPage' );
	submit_button();
	creditline_print( '</form>' );
}

