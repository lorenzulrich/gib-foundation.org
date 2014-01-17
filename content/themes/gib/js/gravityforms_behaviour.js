jQuery( document ).ready( function( $ ) {

    var save_button  = '<div class="geb_gform_custom_save_button_container" style="float: left; position: relative" ><input type="button" class="geb_gform_custom_save_button" value="Save" /></div>';
    var ajax_spinner = '<img id="geb_gform_ajax_spinner" class="geb_gform_ajax_spinner" style="position: absolute; top: 5px; right: -18px;" src="/content/plugins/gravityforms/images/spinner.gif" alt="">';

    //if theres a next button, this is a multipart form - so add in a save button
    if ( jQuery( '.gform_next_button' ) ) {

        jQuery( '.gform_page' ).each( function() {
            jQuery( this).find( '.gform_page_footer' ).append( save_button );
        } );
    }

    jQuery( '.entry-content' ).on( 'click', '#gform_submit_button_5', function( e ) {

        jQuery( this ).closest( '.gform_page_footer').find( '.geb_gform_custom_save_button').click();

    } );

    //auto-populate any inputs with stored values for the current user
    if ( typeof( geb_gform_field_values ) != 'undefined'  ) {

        jQuery.each( geb_gform_field_values, function( key, value ) {

            var selectedInput = jQuery( '#' + key );

            //if we are dealing with a standard input
            if ( selectedInput.is( "input" ) ) {
                selectedInput.val( value );

            //if the input is a list of checkboxes or radio buttons, we need to loop through and set the correct input to 'checked'
            } else {

                selectedInput.find( 'input' ).each( function() {

                    if ( value ==  jQuery( this ).val() )
                        jQuery( this).attr( 'checked', 'checked' );
                } );
            }

        } );
    }

    //when a user selects the next page on a multi page form, inputs are moved about which means we have to add the save button after the page part is loaded
    jQuery(document).bind( 'gform_page_loaded', function( event, form_id, current_page ){

        jQuery( '#gform_page_' + form_id + '_' + current_page).find( '.gform_page_footer' ).append( save_button );

    } );

    //When a user clicks the save button, save the form field values to their usermeta
    jQuery( document ).on( 'click', '.geb_gform_custom_save_button', function () {

        var self = this;

        var form_values = {};

        jQuery( '.gform_body  :input' ).each( function() {

            var nonStandardInputs = [ 'radio', 'checkbox' ];

            if ( jQuery.inArray( jQuery( this ).attr( 'type' ), nonStandardInputs ) == -1 )
                form_values[jQuery( this ).attr( 'id' )] = jQuery( this ).val();
        } );

        jQuery( '.gfield_radio, .gfield_checkbox' ).each( function() {

            var self = this;

            jQuery( this).find( 'input' ).each( function() {

                if ( jQuery( this).is( ':checked' ) ) {
                    form_values[jQuery( self ).attr( 'id' )] = jQuery( this ).val();
                }

            } );

        } );

        jQuery( this ).closest( '.geb_gform_custom_save_button_container').append( ajax_spinner );

        jQuery( this ).attr( 'disabled', 'disabled' );

        jQuery.post( ajaxurl, { action: 'form_field_save', form_values: form_values }, function() {

            jQuery( self ).removeAttr( 'disabled' );
            jQuery( '.geb_gform_ajax_spinner' ).remove();

        } );

    } );

    //fix for older browsers to enable selected styling on radio labels

    //apply fix on input value change
    jQuery( '#gform_wrapper_5' ).on( 'click', 'label', function( e ) {

        var label = jQuery( this );

        if ( label.closest( 'li').find( 'input').attr( 'type' ) != 'radio' )
            return;

        label.closest( 'ul' ).find( 'li' ).each(  function() {

            jQuery( this ).find( 'label').removeClass( 'geb-radio-selected' );
            jQuery( this ).find( 'label').removeAttr( 'checked' );

        } );

        label.addClass( 'geb-radio-selected' );
        label.closest( 'li').find( 'input' ).attr( 'checked', 'checked' );

    } );

    //apply fix on page load for saved field values
    jQuery( '#gform_wrapper_5 .gfield_radio input' ).each( function() {

        var input = jQuery( this );

        if ( input.is( ':checked' ) )
            input.next( 'label').addClass( 'geb-radio-selected' );

    } );

	jQuery( '.gfield_radio' ).each( function() {

		var checked = false;
		var self = jQuery( this );

		self.find( 'input' ).each( function() {

			if ( jQuery( this ).is( ':checked' ) )
				checked = true;
		} );

		if ( checked == false )
			self.find( 'input:last' ).attr( 'checked', 'checked' );
	} );

} );