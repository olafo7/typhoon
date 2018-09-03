<?php
//decode by qq2859470

class jxcBaseCustom extends jxcBase
{

    const CUSTOM_FIELD_CODE = "jxc";

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            exit( );
        case "getCustomFieldlist" :
            ( self::CUSTOM_FIELD_CODE );
            $cf = new customFieldDesign( );
            $cf->getCustomFieldlist( );
            exit( );
        case "updateCustomField" :
            ( self::CUSTOM_FIELD_CODE );
            $cf = new customFieldDesign( );
            $cf->updateCustomField( );
            exit( );
        case "delCustomField" :
            $this->_delCustomField( );
            exit( );
        case "getCustomFieldModel" :
            ( self::CUSTOM_FIELD_CODE );
            $cf = new customFieldDesign( );
            $cf->getCustomFieldModel( );
            exit( );
        case "getCustomFieldType" :
            ( self::CUSTOM_FIELD_CODE );
            $cf = new customFieldDesign( );
            $cf->getCustomFieldType( );
            exit( );
        case "addCustomField" :
            ( self::CUSTOM_FIELD_CODE );
            $cf = new customFieldDesign( );
            $cf->addCustomField( );
        }
        exit( );
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/base/custom.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    private function _delCustomField( )
    {
        global $CNOA_DB;
        $ids = getpar( $_POST, "ids" );
        $CNOA_DB->db_delete( $this->table_bindfield, "WHERE fieldId IN (".$ids.") AND type=".customFieldDesign::FIELD_FROM_CUSTOM );
        ( self::CUSTOM_FIELD_CODE );
        $cf = new customFieldDesign( );
        $cf->delCustomField( );
    }

}

?>
