<?php

//include our custom gravityforms script file and define the ajaxurl variable for the front end
add_action( 'init', function() {

    if ( ! is_user_logged_in() )
        return;

    wp_enqueue_script( 'gravityforms-custom-js', get_template_directory_uri() . '/js/gravityforms_behaviour.js', array( 'jquery' ), '20121008-2', true );

    add_action( 'wp_head', function() {
        ?>
        <script type="text/javascript">
            var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        </script>
        <?php
    } );

} );


//Insert the values for a given form stored in usermeta into the dom so we can extract it later with javascript to fill in the form fields
add_action( 'gform_field_input', function( $input, $field, $value, $lead_id, $form_id ) {

    if ( ! is_user_logged_in() )
        return;

    $fieldHtmlIds = array(
        'input_' . $field['formId'] . '_' . $field['id'],
    );

    foreach ( $fieldHtmlIds as $fieldHtmlId ): ?>
        <?php if (  $value = get_user_meta( get_current_user_id(), $fieldHtmlId, true ) ): ?>
            <script>
                if ( typeof( geb_gform_field_values ) == 'undefined' )
                    var geb_gform_field_values = {};

                geb_gform_field_values.<?php echo $fieldHtmlId; ?> = '<?php echo $value; ?>';
            </script>

        <?php endif;
    endforeach;

}, 10 , 5 );

//When someone clicks our custom save button on a multi-part form, hook in and save the form data to their user meta
add_action( 'wp_ajax_form_field_save', function() {

    if ( ! is_user_logged_in() )
        exit;

    foreach( $_POST['form_values'] as $key => $input ) {

        if ( strpos( $key,  'input' ) === false )
            continue;

        update_user_meta( get_current_user_id(), $key, $input );
    }
    exit;

} );

//Automatically insert user's name into the form
add_filter( 'gform_field_value_username', function( $value ) {

	if ( ! is_user_logged_in() )
		return 'Unknown - Not logged in';

	return wp_get_current_user()->user_login;
} );