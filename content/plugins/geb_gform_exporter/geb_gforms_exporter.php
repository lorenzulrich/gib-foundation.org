<?php
/*
Plugin Name: GEB GravityForms Auto Exporter
Description: Automatically export project form submissions to a given xlsm file
Author: Humanmade Limited
Author URI: http://hmn.md
Version: 0.1
*/

define( 'GEB_GFE_PATH', dirname( __FILE__ ) . '/' );
define( 'GEB_GFE_URL', str_replace( ABSPATH, site_url( '/' ), GEB_GFE_PATH ) );
geb_gfe_load_resources();


function geb_gfe_load_resources() {

    if ( ! class_exists( 'ZipArchive' ) )
        require_once( GEB_GFE_PATH . '/assets/lib/ZipArchive/ZipArchive.php' );

    require_once( GEB_GFE_PATH . '/geb_gforms.admin_interfaces.php' );
    require_once( GEB_GFE_PATH . '/geb_gforms.classes.php' );
}

add_action( 'gform_post_submission', function( $entry, $form ) {

    if ( GEBGravityFormsToXlsManager::getSelectedFormId() != (int) $entry['form_id'] )
        return $entry;

    $export_data = array();

	try {

    //convert the gravity forms entry data into something a little bit more programmer friendly before doing anything with it
    foreach ( GEBGravityFormsToXlsManager::getGravityFormFields( (int) $entry['form_id'] ) as $key => $field ) {

        $export_data[$key]->id = $field->id;
        $export_data[$key]->value = $entry[$field->id];
    }

    $exporter = new GEBGravityFormsToXlsManager( $export_data );

    $exporter->export();

	} catch ( Exception $e ) {

		error_log( 'WARNING: Forms Exporter failed to export file' );

		wp_mail( 'theo@humanmade.co.uk', 'failed GEB export: error', var_export( $e, true ) );

		wp_mail( 'theo@humanmade.co.uk', 'failed GEB export: exporter', var_export( $exporter, true ) );
	}

    return $entry;

}, 10, 2 );

//catch a new template xls file upload from the admin settings page
add_action( 'load-forms1_page_gfe_exporter_settings', function() {

    if ( ! isset( $_POST['geb_gfe_update_settings'] ) )
        return;

    if ( ! wp_verify_nonce( $_POST['geb_gfe_update_settings_nonce'], 'geb_gfe_update_settings' ) )
        wp_die( 'Nonce did not verify' );


    GEBGravityFormsToXlsManager::setSelectedFormId( (int) $_POST['geb_gfe_form_id'] );

    GEBGravityFormsToXlsManager::setExportDestinationEmails( (string) $_POST['geb_gfe_export_destination_emails'] );

    if (  $_FILES['geb_gfe_template_xls']['size'] != 0 )  {

        $file = $_FILES['geb_gfe_template_xls'];

        $file_attribs = wp_handle_upload( $file, array( 'test_form' => FALSE ) );

        if ( $file_attribs['error'] ) {
            hm_error_message( "There appears to have been an error uploading your file. <br />" . $file_attribs['error'], 'gbe_gfe' );
            wp_redirect( add_query_arg( 'upload_failed', 'true' ) );
            exit;
        }

        GEBGravityFormsToXlsManager::setSourceFilePath( $file_attribs['file'] );
    }

    hm_error_message( "Settings successfully updated", 'gbe_gfe' );
    wp_redirect( add_query_arg( 'update_complete', 'true' ) );

    exit;

} );

//catch updates to the field mapping system from the admin settings page
add_action( 'load-forms1_page_gfe_exporter_settings', function() {

    if ( ! isset( $_POST['geb_gfe_update_field_mapping'] ) )
        return;

    if ( ! wp_verify_nonce( $_POST['geb_gfe_update_field_mapping_nonce'], 'geb_gfe_field_mapping' ) )
        wp_die( 'Nonce did not verify' );

    $mapping_data = array();

    foreach ( $_POST['geb_gfe_field_id'] as $key => $id ) {

        $mapping_data[$key]->field_id = $id;
        $mapping_data[$key]->sheet = $_POST['geb_gfe_field_map_sheet'][$key];
        $mapping_data[$key]->cell = $_POST['geb_gfe_field_map_cell'][$key];
    }

    GEBGravityFormsToXlsManager::setFieldMap( $mapping_data );

    hm_success_message( "Mapping data successfully updated", 'gbe_gfe' );
    wp_redirect( add_query_arg( 'mapping_updated', 'true' ) );

    exit;

} );