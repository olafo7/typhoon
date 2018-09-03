<?php

class wfEngineContract extends wfBusinessEngine
{

    protected $code = "contract";

    public function __construct( )
    {
        $FN_-2147483647( );
    }

    protected function init( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_6RYLWQ?? = $this->checkIdea;
        $_obfuscate_ubP__Q?? = $this->_getBindFieldId( "sort" );
        $_obfuscate_6RYLWQ??[$_obfuscate_ubP__Q??]['data'] = $CNOA_DB->db_select( array( "name", "id" ), "contract_sort" );
        $_obfuscate_6RYLWQ??[$_obfuscate_ubP__Q??]['display'] = "name";
        $_obfuscate_6RYLWQ??[$_obfuscate_ubP__Q??]['value'] = "id";
        $_obfuscate_6RYLWQ??[$_obfuscate_ubP__Q??]['relative'] = array( "level" => "gettype", "keys" => "id" );
        return $_obfuscate_6RYLWQ??;
    }

    protected function gettype( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_y6jH = getpar( $_POST, "id" );
        $_obfuscate_LeS8hw?? = $this->_getBindFieldId( "ctype" );
        $_obfuscate_6RYLWQ??[$_obfuscate_LeS8hw??]['data'] = app::loadapp( "contract", "businessSetting" )->api_getPermitType( $CNOA_SESSION->get( "UID" ), $_obfuscate_y6jH );
        $_obfuscate_6RYLWQ??[$_obfuscate_LeS8hw??]['display'] = "tname";
        $_obfuscate_6RYLWQ??[$_obfuscate_LeS8hw??]['value'] = "tid";
        return $_obfuscate_6RYLWQ??;
    }

    protected function _approve( )
    {
        global $CNOA_DB;
        if ( !isset( $_POST["wf_field_".$this->bindCheck['id']] ) )
        {
            msg::callback( FALSE, lang( "noBindingField" ) );
        }
        switch ( $_POST["wf_field_".$this->bindCheck['id']] )
        {
        case $this->bindCheck['idea'][0] :
            $this->makeData4Table( array( "ctype" ) );
            $GLOBALS['_POST']['person'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_POST['person'] );
            app::loadapp( "contract", "businessManage" )->api_editContract( );
            $this->insertAttach( "contract_business", "attach" );
            break;
        case $this->bindCheck['idea'][1] :
            msg::callback( FALSE, lang( "notAppCpndition" ) );
        }
    }

}

?>
