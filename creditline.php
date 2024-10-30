<?php
/*
Plugin Name: Credit line generator
Description: Adds a credit line for an illustration, linking to the illustration source.
Version: 0.3.3
Author: Branko Collin
Author URI: http://www.brankocollin.nl/drupal-en-wordpress-programmeur-en-themer
Text Domain: creditline
*/

/*
	Copyright 2009, Branko Collin (email: collin@xs4all.nl)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301					USA
*/

define('CREDITLINE_URL', plugin_dir_url( __FILE__ ));
define('CREDITLINE_DIR', plugin_dir_path( __FILE__ ));
define('CREDITLINE_DOMAIN', 'creditline'); // Text domain.

// Strings.
define('CREDITLINE_GLUE_PHOTO', 'Photo');
define('CREDITLINE_GLUE_OF', ' of ');
define('CREDITLINE_GLUE_BY', ' by ');
define('CREDITLINE_GLUE_CCL', 'Some rights reserved');
define('CREDITLINE_GLUE_FDL', 'Used under the terms of ');


add_action( 'admin_enqueue_scripts', 'creditline_scripts' );
add_action( 'admin_print_footer_scripts', 'creditline_init' );

add_filter( 'mce_external_plugins', 'creditline_mce_external_plugins' );
add_filter( 'mce_buttons', 'creditline_mce_buttons' );



/**
 * Include CSS and Javascript.
 */
function creditline_scripts() {
	wp_register_style( 'creditline-admin', CREDITLINE_URL . 'admin/css/styles.css' );
	wp_enqueue_style( 'creditline-admin' );
	wp_enqueue_script( 'creditline-admin-footer', CREDITLINE_URL . 'admin/js/script.js', array('quicktags', 'jquery'), false, true );
}



/**
 * Gets a deserialised Wordpress option.
 * 
 * Unlike get_option(), does not return false upon failure, but the value of $default.
 * (Although the user may set $default to false of course.)
 */
function creditline_get_option( $name = '', $default = '' ) {
  if ( '' === $name ) {
    return $default;
  }
  
  $options = 	get_option( 'creditline_settings' );

  if ( false === $options ) {
    return $default;
  }
  
  $option = !empty( $options[$name] ) ? $options[$name] : $default;
  
  return $option;
}



/**
 * DEPRECATED.
 */
function creditline_head() {
	// Deprecated function. Will be deleted in a future version.
} 



/**
 * Set-up.
 */
function creditline_init() {  
	$html_enabled = creditline_get_option( 'creditline_checkbox_field_0', false );
	$dataattribute = '';
	if ( $html_enabled ) {
		$base_class = creditline_get_option( 'creditline_text_field_1', 'creditline' );
		$dataattribute = ' data-output_base_class="' . $base_class .'"';
	}
  
  // Tags allowed in the introductory text.
  $kses_intro = array( 
        'a' => array( 
              'href' => array() 
        ), 
        'code' => array() 
  );
  
  // Set up a form for a 'render array'.
	$cc = 0; 
	$c2 = 0;
	
  $form = array(
    $cc++ => array(
      'type' => 'html',
      'markup' => '<h2>' . __( 'Image credit', CREDITLINE_DOMAIN ) . "</h2>\r\n",
      ),
		$cc++ => array(
			'type' => 'container',
			'element' => 'div',
			'contents' => array(
				$c2++ => array(
					'type' => 'html',
					'markup' => "<p><em>" . __( 'Make photo credits easy.', CREDITLINE_DOMAIN ) . "</em></p>\r\n<p>" . wp_kses( __( 'This tool will, if you feed it the right data, come up with something like <code><a href="">Photo</a> by John Smith.</code> or <code><a href="">Photo of kittens</a> by John Smith, <a href="">some rights reserved</a>.</code> and so on.', CREDITLINE_DOMAIN ), $kses_intro ) . '</p>',
				),
				$c2++ => array(
					'type' => 'text',
					'label' => __( 'URL', CREDITLINE_DOMAIN ),
					'id' => 'clg_url',
					'size' => '60',
					'required' => false,
					'description' => __( 'Webpage on which you found the photo.', CREDITLINE_DOMAIN ),
				),
				$c2++ => array(
					'type' => 'text',
					'label' => __( 'Photographer', CREDITLINE_DOMAIN ),
					'id' => 'clg_photographer',
					'size' => '40',
				),
				$c2++ => array(
					'type' => 'text',
					'label' => __( 'CC license URL', CREDITLINE_DOMAIN ),
					'id' => 'clg_ccurl',
					'description' => __( 'If the photo is Creative Commons licensed.', CREDITLINE_DOMAIN ),
				),
				$c2++ => array(
					'type' => 'text',
					'label' => __( 'GNU FDL URL', CREDITLINE_DOMAIN ),
					'id' => 'clg_fdlurl',
					'description' => __( 'If the photo is GPL FDL licensed.', CREDITLINE_DOMAIN ),
				),
				$c2++ => array(
					'type' => 'text',
					'label' => __( '&quot;Photo of&quot;', CREDITLINE_DOMAIN ),
					'id' => 'clg_extension',
					'size' => '40',
					'description' => __( 'The subject of the photo. If you fill out this field with f. ex. "a letterbox", the link text to the photo page will read "Photo of a letterbox." Otherwise, the link will simply read "Photo."', CREDITLINE_DOMAIN ),
				),
			),
		),
    $cc++ => array(
      'type' => 'html',
      'markup' => '<p class="clear">' . "\r\n" . '<span class="button" onclick="creditline.submitLine(); return false;">' . __( 'Submit', CREDITLINE_DOMAIN ) . '</span>' . "\r\n" . '<span class="button" onclick="creditline.cancelLine(); return false;">' . __( 'Cancel', CREDITLINE_DOMAIN ) . '</span>' . "\r\n" . '</p>',
    ),
    $cc++ => array(
      'type' => 'hidden',
      'id' => 'clg_glue_photo',
      'value' => __( creditline_get_option('creditline_text_field_strings_0', CREDITLINE_GLUE_PHOTO ), CREDITLINE_DOMAIN ),
    ),
    $cc++ => array(
      'type' => 'hidden',
      'id' => 'clg_glue_author',
      'value' => __( creditline_get_option('creditline_text_field_strings_1', CREDITLINE_GLUE_BY ), CREDITLINE_DOMAIN ), // Notes the spaces.
    ),
    $cc++ => array(
      'type' => 'hidden',
      'id' => 'clg_glue_subject',
      'value' => __( creditline_get_option('creditline_text_field_strings_2', CREDITLINE_GLUE_OF ), CREDITLINE_DOMAIN ), // Note the spaces.
    ),
    $cc++ => array(
      'type' => 'hidden',
      'id' => 'clg_glue_ccl',
      'value' => __( creditline_get_option('creditline_text_field_strings_3', CREDITLINE_GLUE_CCL ), CREDITLINE_DOMAIN ),
    ),
    $cc++ => array(
      'type' => 'hidden',
      'id' => 'clg_glue_fdl',
      'value' => __( creditline_get_option('creditline_text_field_strings_4', CREDITLINE_GLUE_FDL ), CREDITLINE_DOMAIN ),
    ),
  );
  
  creditline_print( '<div id="creditline" class="stuffbox"' . $dataattribute. '>' );
  creditline_print( '<form method="GET" action="#">' );
  
  print creditline_render_form( $form );
  
  // @todo Remove this echo statement. Leaving it in for reference for now.
	echo <<<END
	<!-- div id="creditline" class="stuffbox"{$dataattribute}>
		<h2>Image credit</h2>
		
		<p><em>Make photo credits easy.</em></p>
		
		<p>This tool will, if you feed it the right data, come up with something like <code><a href="">Photo</a> by John Smith.</code> or <code><a href="">Photo of kittens</a> by John Smith, <a href="">some rights reserved</a>.</code> and so on.</p>
    
		<form method="GET" action="#">

			<p class="clear">
				<span class="creditline_labelcontainer"><label>URL</label>:</span> 
				<input type="text" name="url" id="url" size="60" /> 
				<br /><small class="howto">Webpage on which you found the photo.</small>
			</p>
			<p class="clear">
				<span class="creditline_labelcontainer"><label>Photographer</label>*:</span> 
				<input type="text" name="photographer" id="photographer" size="40" />
			</p>
			<p class="clear">
				<span class="creditline_labelcontainer"><label>CC license URL</label>:</span>
				<input type="text" name="ccurl" id="ccurl" size="60" /> 
				<br /><small class="howto">If the photo is Creative Commons licensed.</small>
			</p>
			<p class="clear">
				<span class="creditline_labelcontainer"><label>GNU FDL URL</label>:</span> 
				<input type="text" name="fdlurl" id="fdlurl" size="60" />
				<br /><small class="howto">If the photo is GPL FDL licensed.</small>
			</p>
			<p class="clear">
				<span class="creditline_labelcontainer"><label>&quot;Photo of&quot;</label>:</span> 
				<input type="text" name="extension" id="extension" size="40" /> 
				<br /><small class="howto">The subject of the photo. If you fill out this field with f. ex. "a letterbox", the link text to the photo page will read "Photo of a letterbox." Otherwise, the link will simply read "Photo."</small>
			</p>
			<p class="clear">
				<span class="button" onclick="creditline.submitLine(); return false;">Submit</span>
				<span class="button" onclick="creditline.cancelLine(); return false;">Cancel</span> 
			</p>
			<div class="creditline_clear creditline_low"></div>
		</form>
	</div --> <!-- #creditline -->
END;

  creditline_print( '<div class="creditline_clear creditline_low"><!-- --></div>' );
  creditline_print( '</form>' );
  creditline_print( '</div> <!-- #creditline -->' );
} // end creditline_init()


/**
 * Load translations if available for current locale.
 */
function creditline_load_textdomain() {
  $languages_path = basename( dirname( __FILE__ ) ) . '/languages';
  $result = load_plugin_textdomain( CREDITLINE_DOMAIN, false, $languages_path );
}
add_action( 'plugins_loaded', 'creditline_load_textdomain' );


/** 
 * Render an HTML form based on an array.
 * 
 * @param  array     $form      Array containing form data.
 * @param  integer   $level     Call level for indentation.
 * 
 * @return string
 */
function creditline_render_form( $form, $level = 0 ) {
  $crlf = "\r\n";
  $out = '';

  foreach ( $form as $key => $item ) {
    $type = !empty( $item['type'] ) ? $item['type'] : 'html';
    $id   = ' id="' . ( empty( $item['id'] ) ? 'item_' . $key : $item['id'] ) . '"'; 
    $name = ' name="' . ( empty( $item['id'] ) ? 'item_' . $key : $item['id'] ) . '"'; 
    $size = ' size="' . ( empty( $item['size'] ) ? '60' : $item['size'] ) . '"';
    $required_string = empty( $item['required'] ) ? '' : ' *';
    $required = empty( $field['is_required'] ) ? '' : ' required="required"';

    if ( 'container' === $type ) {
      $elem = !empty( $item['elem'] ) ? $item['elem'] : 'div';
      $contents = !empty( $item['contents'] ) ? $item['contents'] : [];
      $tag_in = '<' . $elem . ' class="inner">';
      $tag_out = '</' . $elem . '>';
      $inner = creditline_render_form( $contents, $level++ );
      $out .= creditline_render_line( $tag_in . $inner . $tag_out );
    }

    if ( 'html' === $type ) {
      // You can use 'html' items to render non-form content, but also to 
      // render form content for which no processor exists.
      $out .= creditline_render_line( $item['markup'], 1 );
    }

    if ( 'text' === $type ) {
      // Input type="text" processor.
      $out .= creditline_render_line( '<p class="clear">', 1 );
      if ( !empty( $item['label'] ) ) {
        $out .= creditline_render_line( '<span class="creditline_labelcontainer"><label>' . $item['label'] . '</label>: ' . $required_string . '</span> ', 2 );
      }
      $out .= creditline_render_line( '<input type="text"' . $name . $id . $size . $required . '> ', 2);
      if ( !empty( $item['description'] ) ) {
        $out .= creditline_render_line( '<br /><small class="howto">' . $item['description'] . '</small>', 2 );
      }
      $out .= creditline_render_line( '</p>', 1 );
    }
    
    if ( 'hidden' === $type ) {
      // Input type="hidden" processor.
      if ( !empty( $item['value'] ) ) {
        $out .= creditline_render_line( '<input type="hidden"' . $name . $id . 'value="' . $item['value'] . '">', 1 );
      }
    }
  }

    return $out;
}



/**
 * Add the TinyMCE button functionality.
 */
function creditline_mce_external_plugins( $plugins ) {
	$plugins['creditline_plugin'] = CREDITLINE_URL . 'visual-editor/visual-editor-plugin.js';
	return $plugins;
}



/**
 * Register the TinyMCE button.
 */
function creditline_mce_buttons( $buttons ) {
  array_push($buttons, 'creditline_button');
  return $buttons;
} 



// HTML line printer.
function creditline_print($text='', $level=0, $wrap='') {
	print creditline_render_line($text, $level, $wrap);
}

// HTML line getter.
// @todo: simple tag wrapper.
function creditline_render_line($text='', $level = 0, $wrap='') {
	$linebreak = "\r\n";
	$indentation = str_pad( '', $level, "\t" );
	return $linebreak . $indentation . $text;
}




require_once( CREDITLINE_DIR . 'options.php' );

