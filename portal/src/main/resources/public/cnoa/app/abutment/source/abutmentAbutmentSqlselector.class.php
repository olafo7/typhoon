<?php
//decode by qq2859470

class abutmentAbutmentSqlselector extends abutmentAbutment
{

    public function run( )
    {
        global $CNOA_SESSION;
        $_obfuscate_M_5JJwÿÿ = getpar( $_GET, "task", "loadPage" );
        switch ( $_obfuscate_M_5JJwÿÿ )
        {
        case "loadPage" :
            global $CNOA_CONTROLLER;
            $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/abutment_sqlselector.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQÿÿ );
            break;
        case "saveSqlselector" :
            $this->_saveSqlselector( );
            break;
        case "getSqlselector" :
            $this->_getSqlselector( );
            break;
        case "getDatabase" :
            $this->_getDatabase( );
            break;
        case "testSQL" :
            $this->testSQL( );
            break;
        case "getDbFieldName" :
            $this->_getDbFieldName( );
            break;
        case "deleteSqlselector" :
            $this->_deleteSqlselector( );
        }
    }

    private function _saveSqlselector( )
    {
        global $CNOA_DB;
        $_obfuscate_0W8ÿ = getpar( $_POST, "id", 0 );
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_6RYLWQÿÿ['selectorName'] = getpar( $_POST, "selectorName", "" );
        if ( !empty( $_obfuscate_0W8ÿ ) )
        {
            $_obfuscate_gftfagwÿ = $CNOA_DB->db_getcount( "abutment_sqlselector", "WHERE `selectorName` LIKE '%".$_obfuscate_6RYLWQÿÿ['selectorName']."%' AND id!={$_obfuscate_0W8ÿ}" );
        }
        else
        {
            $_obfuscate_gftfagwÿ = $CNOA_DB->db_getcount( "abutment_sqlselector", "WHERE `selectorName` LIKE '%".$_obfuscate_6RYLWQÿÿ['selectorName']."%'" );
        }
        if ( !empty( $_obfuscate_gftfagwÿ ) )
        {
            msg::callback( FALSE, "åç§°å·²å­å¨ï¼" );
        }
        $_obfuscate_6RYLWQÿÿ['selectorStyle'] = getpar( $_POST, "selectorStyle" );
        $_obfuscate_6RYLWQÿÿ['databaseId'] = getpar( $_POST, "databaseId", 0 );
        $_obfuscate_6RYLWQÿÿ['selectorType'] = getpar( $_POST, "selectorType", 0 );
        $_obfuscate_6RYLWQÿÿ['items'] = getpar( $_POST, "items", "" );
        if ( !empty( $_obfuscate_6RYLWQÿÿ['items'] ) )
        {
            $_obfuscate_6RYLWQÿÿ['items'] = substr( $_obfuscate_6RYLWQÿÿ['items'], 0, -1 );
        }
        $_obfuscate_6RYLWQÿÿ['selectorSQL'] = getpar( $_POST, "selectorSQL", "" );
        $_obfuscate_6RYLWQÿÿ['selectorSQL'] = str_replace( "fro^m", "from", $_obfuscate_6RYLWQÿÿ['selectorSQL'] );
        $_obfuscate_6RYLWQÿÿ['display'] = getpar( $_POST, "display", "" );
        $_obfuscate_6RYLWQÿÿ['value'] = getpar( $_POST, "value", "" );
        if ( !empty( $_obfuscate_0W8ÿ ) )
        {
            $CNOA_DB->db_update( $_obfuscate_6RYLWQÿÿ, "abutment_sqlselector", "WHERE id=".$_obfuscate_0W8ÿ );
        }
        else
        {
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, "abutment_sqlselector" );
        }
        msg::callback( TRUE, "æä½æåï¼" );
    }

    private function _getSqlselector( )
    {
        global $CNOA_DB;
        $_obfuscate_6RYLWQÿÿ = $CNOA_DB->db_select( "*", "abutment_sqlselector", "WHERE 1" );
        if ( !is_array( $_obfuscate_6RYLWQÿÿ ) )
        {
            $_obfuscate_6RYLWQÿÿ = array( );
        }
        foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_Vwty]['selectorTypeName'] = $_obfuscate_VgKtFegÿ['selectorType'] == 1 ? "æ®ééæ©å¨" : "SQLéæ©å¨";
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_Vwty]['selectorStyleName'] = $_obfuscate_VgKtFegÿ['selectorStyle'] == 1 ? "ä¸æ" : "æ ç­¾";
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_Vwty]['databaseName'] = $this->api_getDatabaseNameById( $_obfuscate_VgKtFegÿ['databaseId'] );
        }
        ( );
        $_obfuscate_NlQÿ = new DataStore( );
        $_obfuscate_NlQÿ->total = count( $_obfuscate_6RYLWQÿÿ );
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
    }

    private function _getDatabase( )
    {
        global $CNOA_DB;
        $_obfuscate_6RYLWQÿÿ = $CNOA_DB->db_select( array( "id", "name" ), "abutment_database", "WHERE 1" );
        ( );
        $_obfuscate_NlQÿ = new DataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
    }

    private function _getDbFieldName( )
    {
        global $CNOA_SESSION;
        $_obfuscate_oDAS4YopcE7Hswÿÿ = getpar( $_POST, "databaseId" );
        $_obfuscate_sx8ÿ = $this->connectDb( $_obfuscate_oDAS4YopcE7Hswÿÿ );
        $_obfuscate_3y0Y = $this->changeSql( $_POST['selectorSQL'] );
        $_obfuscate_6RYLWQÿÿ = $_obfuscate_sx8ÿ->get_one( $_obfuscate_3y0Y );
        if ( !is_array( $_obfuscate_6RYLWQÿÿ ) )
        {
            $_obfuscate_6RYLWQÿÿ = array( );
        }
        if ( empty( $_obfuscate_6RYLWQÿÿ ) )
        {
            msg::callback( FALSE, "SQLè¯­å¥éè¯¯æèè¯¥è¯­å¥æ¥è¯¢ä¸å°æ°æ®" );
        }
        else
        {
            $_obfuscate_SeV31Qÿÿ = array( );
            foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
            {
                $_obfuscate_SeV31Qÿÿ[] = $_obfuscate_Vwty;
            }
            echo json_encode( $_obfuscate_SeV31Qÿÿ );
        }
    }

    private function _deleteSqlselector( )
    {
        global $CNOA_DB;
        $_obfuscate_0W8ÿ = getpar( $_POST, "id", 0 );
        $CNOA_DB->db_delete( "abutment_sqlselector", "WHERE id=".$_obfuscate_0W8ÿ );
        msg::callback( TRUE, "å é¤æåï¼" );
    }

}

?>
