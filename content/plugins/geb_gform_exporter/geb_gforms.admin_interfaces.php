<?php

//Create an admin submenu settings page for the gravity form to xls exporter
add_action( 'admin_menu', function() {

    $hook = add_submenu_page( 'gf_edit_forms', 'Excel Export Settings', 'Excel Export Settings', 'manage_options', 'gfe_exporter_settings', function() {
        $table_headers = array( 'Field Name', 'Field ID', 'Maps To', 'Excel Worksheet', 'Worksheet Cell' );

        $export_manager = new GEBGravityFormsToXlsManager();

        $sheet_options  = array_merge( array( 0 => '-None-' ), $export_manager->getSheetNames() );

        $form_fields    = $export_manager::getGravityFormFields();
        $field_map      = $export_manager::getFieldMap();

        ?>
        <div class="wrap">

            <?php hm_the_messages( 'gbe_gfe' ); ?>

            <div class="icon32" id="gravity-edit-icon"><br></div>
            <h2>Edit Gravityforms Exporter Settings</h2>

            <div class="gfe_block">

                <h3>General Settings</h3>

                <form method="post" enctype="multipart/form-data" >
                    <table class="form-table widefat bordertop">
                        <tr>
                            <td>
                                <strong>Select Form to Export</strong>
                                <br />Submissions from the form selected here will be automatically exported to the .xlsx template

                            </td>
                            <td>
                                <select name="geb_gfe_form_id">
                                    <?php foreach ( GEBGravityFormsToXlsManager::getFormsList() as $form ): ?>
                                        <option <?php selected( (int) $form->id, GEBGravityFormsToXlsManager::getSelectedFormId() ); ?> value="<?php echo absint( $form->id ); ?>"><?php echo esc_html( $form->title ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>Change template file to Export to</strong>
                                <?php if ( $template_path = GEBGravityFormsToXlsManager::getSourceFilePath() ): ?>
                                    <br />(current file: <?php echo $template_path ?>)
                                    <?php endif; ?>
                            </td>
                            <td><input type="file" name="geb_gfe_template_xls" /></td>
                        </tr>

                        <tr>
                            <td>
                                <strong>Email exports to:</strong>
                                <br />Select who should receive and email copy of the exports (comma separated list)
                            </td>
                            <td><input type="text" name="geb_gfe_export_destination_emails" value="<?php echo ( $emails = GEBGravityFormsToXlsManager::getExportDestinationEmails() ) ? $emails : ""; ?>"/></td>
                        </tr>

                    </table>

                    <input type="submit" class="submit_btn" name="geb_gfe_update_settings" value="Save Settings" />

                    <?php wp_nonce_field( 'geb_gfe_update_settings', 'geb_gfe_update_settings_nonce' ); ?>

                </form>

            </div>
            <?php if ( $form_fields ): ?>
                <div class="gfe_block">

                    <h3>Gravityforms Field to Excel Template Cell (Mapping)</h3>
                    <form method="post">
                        <table class="form-table widefat">
                            <tbody>

                                <?php foreach ( $table_headers as $header ): ?>

                                    <th><?php echo $header; ?></th>
                                <?php endforeach; ?>

                                <?php foreach ( $form_fields as $key => $field  ) : ?>

                                    <?php if ( $field->type == 'page' || $field->type == 'section' ): ?>

                                        <?php $break_text = ( $field->type == 'page' ) ? 'NEW PAGE' : 'SECTION BREAK'; ?>
                                        <tr class="seperator">
                                            <td class="<?php echo $field->type; ?> separator" colspan="<?php echo count( $table_headers ); ?>"><?php echo $break_text; ?></td>
                                        </tr>

                                    <?php else: ?>

                                        <?php $field_map_entry = GEBGravityFormsToXlsManager::getFieldMapEntryForField( $field->id ); ?>

                                        <tr>
                                            <td><?php echo $field->label; ?></td>
                                            <td>
                                                <?php echo $field->id; ?>
                                                <input type="hidden" name="geb_gfe_field_id[]" value="<?php echo $field->id; ?>" />
                                            </td>
                                            <td>&#187;</td>
                                            <td>
                                                <select name="geb_gfe_field_map_sheet[]">
                                                    <?php foreach ( $sheet_options as $option_key => $option ): ?>
                                                    <option <?php selected( ( isset( $field_map_entry->sheet) && $field_map_entry->sheet == $option ) ); ?> value="<?php echo $option; ?>"><?php echo $option; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input name="geb_gfe_field_map_cell[]" value="<?php echo ( isset( $field_map_entry->cell ) ) ? $field_map_entry->cell : ""; ?>" />
                                            </td>
                                        </tr>

                                    <?php endif; ?>

                                <?php endforeach; ?>

                            </tbody>
                        </table>
                        <?php wp_nonce_field( 'geb_gfe_field_mapping', 'geb_gfe_update_field_mapping_nonce' ); ?>

                        <input class="submit_btn" type="submit" name="geb_gfe_update_field_mapping" value="Update Mapping" />
                    </form>
                </div>
             <?php endif; ?>
        </div>
        <?php
    } );

}, 11 );

//Load in some styles to the admin page, and fire the wp_footer hook to ensure hm_messages get cleared after page load
add_action( 'load-forms_page_gfe_exporter_settings', function() {

    add_action( 'admin_footer', function() {
        do_action( 'wp_footer' );
    } );

    add_action( 'admin_head', function() {

        ?><style type="text/css">
            .bordertop td {
                border-top: 1px solid #DFDFDF;
            }
            tr td.separator {
                text-align: center;
            }
            tr td.page {
                background-color: #e2e1ff;
                height: 50px;
            }
            tr td.section {
                background-color: #ffe2e9;
            }
            tr.seperator{
                border-top: 2px solid #BBB;
                border-bottom: 2px solid #BBB;
            }
            .form-table.widefat th {
                border-top: 1px solid #DFDFDF;
            }
            .gfe_block {
                margin-bottom: 20px;
                margin-top: 40px;
            }
            .submit_btn {
                display: block;
                float: right;
                margin-top: 20px;
            }
        </style><?php
    } );
} );