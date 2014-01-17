<?php

/** GEBGravityFormsToXlsManager class, this class is used to manage functionality involved in exporting a given gravityforms entry to a template xlsx file
 *
 */
class GEBGravityFormsToXlsManager {

    /** Active sheet variable, this will be an instantiation of the GEB_Excel_Sheet_Editor class
     * @var
     */
    public $activeSheet;

    /** Extracted file variable, this will be an extracted version of the supplied xlsx file
     * @var
     */
    public $extractedFile;

    /** The construct. Some utility methods on this class have static declarations and so instantiation is not always required
     *
     * @param null $input_data
     * @param array $args
     */
    function __construct( $input_data = null, $args = array() ) {

        $this->inputData = $input_data;

        //parse any args supplied to the instantiation
        $this->args = wp_parse_args( $args, array(
            'sourceFile'            => $this::getSourceFilePath(),
            'destinationFileName'   => null,
            'mailErrorsTo'          => 'theo@humanmade.co.uk',
            'destinationFile'       => null
        ) );

        if ( ! $this->args['destinationFile'] ) {

            $destinationFileName = ( $this->args['destinationFileName'] ) ? $this->args['destinationFileName'] : 'entry-export-' . date( 'Y-m-d' ) . '-' . get_userdata( get_current_user_id() )->user_nicename;

            $this->args[ 'destinationFile' ] = $this->getExportDirectory() . '/' . $destinationFileName . '.xlsx';
        }
    }

    /** Run an export of the data supplied at instantiation, inserting the data into a copy of the supplied template xlsx file
     *
     */
    public function export() {

        foreach ( $this::getFieldMap() as $key => $mappedField ) {

            if ( $mappedField->sheet == '-None-'  )
                continue;

            if ( $this->setActiveSheet( $mappedField->sheet, 'name' ) ) {

                $this->activeSheet->setCellValue( $mappedField->cell, $this->getValueForField( (int) $mappedField->field_id ) );

                $this->saveActiveSheetChanges();

            }
        }

        $this->saveAndFlushCalculationCaches();

        $current_user = get_userdata( get_current_user_id() );

        $emailContent = 'This is an automated email containing an exported copy of a user form submission'
            . "\n\n User Login: " . $current_user->user_login
            . "\n User Email: " . $current_user->user_email;

        if ( $current_user->first_name &&  $current_user->last_name ) {

            $emailContent .= "\n Name: " . $current_user->first_name . ' ' . $current_user->last_name;
        }

        wp_mail( $this::getExportDestinationEmails(), 'Gravityforms Entry Export from GEB', $emailContent, '', $this->args['destinationFile']  );
    }

    /** Extract the value for a specified field from the supplied data, the supplied data must of course be correctly formatted
     *
     * @param $field_id
     * @return bool|int
     * @throws Exception
     */
    public function getValueForField( $field_id ) {

        if ( $this->inputData === null )
            throw new Exception ( "This method requires input_data to be supplied with the object instantiation" );

        foreach( $this->inputData as $field ) {

            if ( $field_id == $field->id ) {
                if ( is_numeric( $field->value ) )
                    return (int) $field->value;
                else
                    return $field->value;
            }
        }

        return false;
    }

    /** Select a worksheet from the supplied source xlsx template file and load it into the activeSheet variable, the worksheet will be wrapped inside the GEB_Excel_Sheet_Editor class object
     *
     * @param $arg
     * @param string $searchBy
     * @return GEB_Excel_Sheet_Editor
     * @throws Exception
     */
    public function setActiveSheet( $arg, $searchBy = 'Id' ) {

        if ( ! empty( $this->activeSheet->sheet->attributes->$searchBy ) && $this->activeSheet->sheet->attributes->$searchBy == $arg  )
            return $this->activeSheet;

        foreach( $this->getSheetAttributes() as $attribute ) {

            if ( isset( $attribute->$searchBy ) && $attribute->$searchBy == $arg ) {

                $file = $this->getExtractedFile();

                $xmlWorkbook = (object) null;
                $xmlWorkbook->attributes  = $attribute;
                $xmlWorkbook->contents    = simplexml_load_string( $file->getFromName( $attribute->path ) );
                return $this->activeSheet = new GEB_Excel_Sheet_Editor( $xmlWorkbook );
            }
        }

        return false;

    }

    /** Flush any cached calculated values from the worksheets
     *
     * Newer versions of excel for mac do not auto update calculated values (values within the sheet which are linked to formulae)
     * So if we update a cell which is being used by a formula, we need to cache the calculated solution to that formula
     */
    private function flushFileCachedValues() {

        foreach ( $this->getSheetNames( true ) as $sheet ) {

            $this->setActiveSheet( $sheet, 'name' );
            $this->activeSheet->updateCalculations();
            $this->saveActiveSheetChanges();
        }

    }

    /** Save changes made to the active sheet
     *
     */
    public function saveActiveSheetChanges() {

        $file = $this->getExtractedFile();

        $file->addFromString( $this->activeSheet->sheet->attributes->path, $this->activeSheet->sheet->contents->asXML() );
    }

    /** Close the extracted file with sheets modified via $this->saveActiveSheetChanges(), finalising changes
     *
     * This method should be called at the very end of the export job
     */
    public function saveToFile() {

        $file = $this->getExtractedFile();

        $file->close();

        $this->extractedFile = false;
    }

    /** Save the file and flush the file's cell values for calculations cache
     *
     */
    function saveAndFlushCalculationCaches() {

        $this->saveToFile();

        $file = $this->getExtractedFile();

        $this->flushFileCachedValues();

        $this->saveToFile();
    }

    /** Extract and return a zipArchive object of the given xlsx template file
     *
     *  this method will clone the original template, the directory given by $this->exportDirectory()
     * @return ZipArchive
     */
    public function getExtractedFile() {

        if ( ! empty( $this->extractedFile ) )
            return $this->extractedFile;

        $zip = new ZipArchive();

        //if no destination, use the source file to open and save to, otherwise copy source file to destination and open there
        if ( ! is_file( $this->args['destinationFile'] ) ) {
            copy( $this->args['sourceFile'], $this->args['destinationFile'] );
        }

        $zip->open( $this->args['destinationFile'] );

        return $this->extractedFile = $zip;
    }

    /** Get the path to the exports directory (inside uploads).
     *
     * Will create a ht_access file to protect the directory
     * @return string
     */
    function getExportDirectory() {

        $upload_dir = wp_upload_dir();

        $path = $upload_dir['basedir'] . '/form_entry_exports';

        // Create the backups directory if it doesn't exist
        if ( is_writable( dirname( $path ) ) && ! is_dir( $path ) )
            mkdir( $path, 0755 );

        // Secure the directory with a .htaccess file
        $htaccess = $path . '/.htaccess';

        $contents[]	= '# ' . sprintf( 'This %s file ensures that other people cannot download your export files' , '.htaccess' );
        $contents[] = '';
        $contents[] = '<IfModule mod_rewrite.c>';
        $contents[] = 'RewriteEngine On';
        $contents[] = 'RewriteCond %{QUERY_STRING} !key=334}gtrte';
        $contents[] = 'RewriteRule (.*) - [F]';
        $contents[] = '</IfModule>';
        $contents[] = '';

        if ( ! file_exists( $htaccess ) && is_writable( $path ) && require_once( ABSPATH . '/wp-admin/includes/misc.php' ) )
            insert_with_markers( $htaccess, 'BackUpWordPress', $contents );

        return $path;
    }


    /** Get an array of all the names of the sheets in the given xlsx template file
     *
     * @param bool $onlyGetWorksheets
     * @return array
     */
    public function getSheetNames( $onlyGetWorksheets = true ) {

        $names = array();

        foreach ( $this->getSheetAttributes() as $attribute ) {

            if ( isset( $attribute->name ) && ( $attribute->typeSlug == 'worksheet' || $onlyGetWorksheets == false ) )
                $names[] = $attribute->name;
        }

        return $names;
    }

    /** Get a list of basic attributes for each of the sheets in the given xlsx template
     *
     * @return array
     */
    public function getSheetAttributes() {

        $file = $this->getExtractedFile();

        $xmlWorkbook = simplexml_load_string( $file->getFromName( "xl/workbook.xml" ) );

        foreach ( $sheetAttributes = (array) $this->getRelationships() as $key => $sheetAttribute ) {

            foreach ( reset( $xmlWorkbook->sheets ) as $sheet ) {

                if ( $sheetAttribute->rId == $sheet->attributes( 'http://schemas.openxmlformats.org/officeDocument/2006/relationships')->id ) {
                    $sheetAttributes[$key]->Id = (string) $sheet->attributes()->sheetId;
                    $sheetAttributes[$key]->name = (string) $sheet->attributes()->name;

                }
            }
        }

        return $sheetAttributes;
    }

    /** Get a list of relationships arguments from the rels file inside the given template xlsx file
     *
     * @return array
     */
    private function getRelationships() {

        $file = $this->getExtractedFile();

        $xmlWorkbook = simplexml_load_string( $file->getFromName( "xl/_rels/workbook.xml.rels" ) );

        $rels = array();
        $key = 0;

        foreach ( $xmlWorkbook->Relationship as $ele ) {

            $key++;
            $rels[$key]->rId = (string) $ele->attributes()->Id;
            $rels[$key]->type = (string) $ele->attributes()->Type;
            $rels[$key]->typeSlug = end( explode( '/', $ele->attributes()->Type ) );
            $rels[$key]->path = 'xl/' . $ele->attributes()->Target;

        }

        return $rels;
    }

    /** Save the path of the source xlsx file to the database, if a source file arg is not supplied on class instantiation, the class will fallback to the stored path set by this method
     *
     * @static
     * @param $path
     */
    public static function setSourceFilePath( $path ) {

        update_option( 'geb_gfe_uploaded_template', $path );
    }

    /** Get stored path argument of the template xlsx file
     *
     * @static
     * @return mixed|void
     */
    public static function getSourceFilePath() {

        return get_option( 'geb_gfe_uploaded_template' );
    }

    /** Get the last saved map of gravityforms fields to excel sheet cells
     *
     * @static
     * @return mixed|void
     */
    public static function getFieldMap() {

        return get_option( 'geb_gfe_field_map' );
    }

    /** Save a map of gravityforms fields to excel sheet cells
     *
     * @static
     * @param $mappingData
     */
    public static function setFieldMap( $mappingData ) {

        update_option( 'geb_gfe_field_map', $mappingData );
    }

    /** Extract the mapping args for a specific mapped field via field ID
     *
     * @static
     * @param $fieldId
     * @return bool|object
     */
    public static function getFieldMapEntryForField( $fieldId ) {

        foreach ( self::getFieldMap() as $mappedField ) {

            if ( (int) $mappedField->field_id == (int) $fieldId ) {

                return $mappedField;
            }
        }

        return false;
    }

    /** Set which form should be hooked into and have it's entries exported
     *
     * @param $formId
     * @static
     */
    public static function setSelectedFormId( $formId ) {

        return update_option( 'geb_gfe_form_id', $formId );
    }

    /** Get the saved record of which form to use for exports
     *
     * @static
     * @return int
     */
    public static function getSelectedFormId() {

        return (int) get_option( 'geb_gfe_form_id' );
    }

    /** Get an array of gravityforms fields for a given form ID
     *
     * Defaults to getting fields for the form set via the setSelectedFormId method
     *
     * @static
     * @param $formId
     * @return array
     */
    public static function getGravityFormFields( $formId = null ) {

        if ( $formId === null )
            $formId = self::getSelectedFormId();

        if ( ! $formId )
            return array();

        $form = RGFormsModel::get_form_meta( $formId );

        $formatted = array();

        foreach ( $form['fields'] as $key => $field ) {

            $formatted[$key]->id = (int) $field['id'];
            $formatted[$key]->type = $field['type'];

            if ( ! empty( $field['adminLabel'] ) )
                $formatted[$key]->label = $field['adminLabel'];
            else
                $formatted[$key]->label = ( ! empty( $field['label'] ) ) ? $field['label'] : '';

        }
        return $formatted;
    }

    /** Get a list of gravity forms that are on the site
     *
     * @static
     * @return mixed
     */
    public static function getFormsList() {

        return RGFormsModel::get_forms(null, "title");
    }

    /** Set the destination email addresses that exported gravity forms entries should be emailed to
     *
     * Supply a comma seperated list of email addresses
     *
     * @static
     * @param $emails
     */
    public static function setExportDestinationEmails( $emails ) {

        update_option( 'geb_gfe_export_destination_emails', $emails );
    }

    /** Get the comma seperated list of email addresses
     *
     * @static
     * @return mixed|void
     */
    public static function getExportDestinationEmails() {

        return get_option( 'geb_gfe_export_destination_emails' );
    }
}

/** GEB_Excel_Sheet_Editor class, this class is concerned solely with setting data for given cells on an excel formatted xml worksheet
 *
 */
class GEB_Excel_Sheet_Editor {

    /** The construct
     *
     * @param $sheet
     */
    function __construct( $sheet ) {

        $this->sheet = $sheet;
    }

    /** Set the value of a given cell
     *
     * @param $cell
     * @param $value
     * @return bool
     */
    public function setCellValue( $cell, $value ) {

        $rowCol = $this->cellToRowAndColumn( $cell );

        foreach( $this->sheet->contents->sheetData->row as $row ) {

            //if we find a row in the xml that matches the row for the cell given
            if ( $row->attributes()->r == $rowCol[0] ) {

                foreach( $row->c as $col ) {

                    //if we find a matching cell entry in the xml that we can just replace the value of
                    if ( $col->attributes()->r == $cell ) {
                        $col->v = $value;

                        return true;
                    }
                }

                //if we get to this point, a matching column wasn't found, but a row was, we must add the column
                $this->newCol( $row, $cell, $value );

                return true;
            }
        }

        //If we get to this point, a matching row was not found, and must be added
        $newRow = $this->newRow( $cell );

        //then a column must be added to the new row
        $this->newCol( $newRow, $cell, $value );


    }

    /** Remove cell values which have a formula attached to them, this will force excel to recalculate the solution to the formula
     *
     */
    public function updateCalculations() {

        foreach( $this->sheet->contents->sheetData->row as $row ) {

            foreach ( $row->c as $col ) {

                if ( ! empty ($col->f ) ) {
                    unset( $col->v );
                }
            }
        }
    }

    /** Helper method to extract row and column IDs from a cell ID
     *
     * Returns an array with the first element as row, and the second element as column
     *
     * @param $cell
     * @return array
     */
    private function cellToRowAndColumn( $cell ) {
        return array(
            trim( str_replace( range( 'A' , 'Z' ),'', strtoupper( $cell ) ) ),
            trim( str_replace( range( 0 , 9 ),'', $cell ) )
        );
    }

    /** Add a new row object to the xml
     *
     * Excel expects ordered xml, i.e. if a new row (row 4) is added to the list, it must be inserted between row 3 and row 5 (if they exist)
     * This method will ensure the row is inserted at the correct location within the list
     *
     * @param $cell
     * @return mixed
     */
    private function newRow( $cell ) {

        $sheetDataRef = $this->sheet->contents->sheetData;
        $rowCol = $this->cellToRowAndColumn( $cell );

        // SimpleXML does not seem to allow adding XML objects directly into other XML objects, and so some trickery must be used to insert a row between
        // already existing rows. We must redraw the entire rows section of the xml object but insert our new row in the correct place as we redraw.
        if ( count( $sheetDataRef->row ) > 0 ) {

            $rowsCopy = clone $this->sheet->contents->sheetData;
            unset( $this->sheet->contents->sheetData->row );

            foreach ( $rowsCopy->row as $row  ) {

                if ( (int) $row->attributes()->r > $rowCol[0] && empty( $inserted ) ) {

                    $insertedRow = $sheetDataRef->addChild( 'row' );
                    $insertedRow->addAttribute( 'r', $rowCol[0] );
                    $insertedRow->addAttribute( 'spans', '1:2' );

                    $inserted = true;
                }

                $copiedRow = $sheetDataRef->addChild( 'row' );
                $copiedRow->addAttribute( 'r', $row->attributes()->r );
                $copiedRow->addAttribute( 'spans', $row->attributes()->spans );

                foreach( $row->c as $col ) {

                    $this->newCol( $copiedRow, $col->attributes()->r, $col->v );
                }
            }

            if ( ! empty( $inserted ) )
                return $insertedRow;
        }

        $insertedRow = $sheetDataRef->addChild( 'row' );
        $insertedRow->addAttribute( 'r', $rowCol[0] );
        $insertedRow->addAttribute( 'spans', '1:2' );

        return $insertedRow;
    }

    /** Add a new column to a row object
     *
     * Excel expects ordered xml, i.e. if a new col (col 4) is added to the list, it must be inserted between col 3 and col 5 (if they exist)
     * This method will ensure the col is inserted at the correct location within the list
     *
     * @param $row
     * @param $cell
     * @param $value
     * @return mixed
     */
    private function newCol( $row, $cell, $value ) {

        if ( count( $row->c ) > 0 ) {

            $rowCopy = clone $row;
            unset( $row->c );

            // SimpleXML does not seem to allow adding XML objects directly into other XML objects, and so some trickery must be used to insert a column between
            // already existing columns. We must redraw the entire columns ('c') section of the xml object but insert our new column in the correct place as we redraw.
            foreach ( $rowCopy as $col ) {

                if ( $cell < $col->attributes()->r && empty( $inserted ) ) {
                    $insertedCol = $row->addChild( 'c' );
                    $insertedCol->addAttribute( 'r', $cell );
                    $insertedCol->addChild( 'v', $value );

                    $inserted = true;
                }

                $copiedCol = $row->addChild( 'c' );
                $copiedCol->addAttribute( 'r', $col->attributes()->r );
                $copiedCol->addChild( 'v', $col->v );
            }

            if ( ! empty( $inserted ) )
                return $insertedCol;
        }

        $insertedCol = $row->addChild( 'c' );
        $insertedCol->addAttribute( 'r', $cell );
        $insertedCol->addChild( 'v', $value );

        return $insertedCol;
    }
}