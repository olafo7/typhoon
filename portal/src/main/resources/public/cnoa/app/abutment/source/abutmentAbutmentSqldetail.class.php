<?php
//decode by qq2859470

class abutmentAbutmentSqldetail extends abutmentAbutment
{

    public function run( )
    {
        global $CNOA_SESSION;
        $_obfuscate_M_5JJwÿÿ = getpar( $_GET, "task", "loadPage" );
        switch ( $_obfuscate_M_5JJwÿÿ )
        {
        case "loadPage" :
            global $CNOA_CONTROLLER;
            $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/abutment_sqldetail.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQÿÿ );
            break;
        case "saveSqldetail" :
            $this->_saveSqldetail( );
            break;
        case "getSqldetail" :
            $this->_getSqldetail( );
            break;
        case "getDatabase" :
            $this->_getDatabase( );
            break;
        case "updateDetailName" :
            $this->_updateDetailName( );
            break;
        case "adddetail" :
            $this->_adddetail( );
            break;
        case "testSQL" :
            $this->testSQL( );
            break;
        case "getDbFieldName" :
            $this->_getDbFieldName( );
            break;
        case "updateMapName" :
            $this->_updateMapName( );
            break;
        case "getCustomerCustomField" :
            $this->_getCustomerCustomField( );
            break;
        case "getCustomerList" :
            $this->_getCustomerList( );
            break;
        case "deleteSqldetail" :
            $this->_deleteSqldetail( );
            break;
        case "getSearchList" :
            $this->_getSearchList( );
            break;
        case "addSearch" :
            $this->_addSearch( );
            break;
        case "updateSearchName" :
            $this->_updateSearchName( );
            break;
        case "addSearchField" :
            $this->_addSearchField( );
            break;
        case "getSearchField" :
            $this->_getSearchField( );
            break;
        case "updateSearchFieldName" :
            $this->_updateSearchFieldName( );
            break;
        case "delSearchFields" :
            $this->_delSearchFields( );
            break;
        case "getCustomerSearchFields" :
            $this->_getCustomerSearchFields( );
        }
    }

    private function _saveSqldetail( )
    {
        global $CNOA_DB;
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_0W8ÿ = getpar( $_POST, "id" );
        $_obfuscate_6RYLWQÿÿ['selectorSQL'] = getpar( $_POST, "selectorSQL" );
        $_obfuscate_6RYLWQÿÿ['selectorSQL'] = str_replace( "fro^m", "from", $_obfuscate_6RYLWQÿÿ['selectorSQL'] );
        $_obfuscate_6RYLWQÿÿ['databaseId'] = getpar( $_POST, "databaseId" );
        $CNOA_DB->db_update( $_obfuscate_6RYLWQÿÿ, "abutment_sqldetail", "WHERE id=".$_obfuscate_0W8ÿ );
        msg::callback( TRUE, "æä½æåï¼" );
    }

    private function _getSqldetail( )
    {
        global $CNOA_DB;
        $_obfuscate_6RYLWQÿÿ = $CNOA_DB->db_select( "*", "abutment_sqldetail", "WHERE 1 ORDER BY `id`" );
        if ( !is_array( $_obfuscate_6RYLWQÿÿ ) )
        {
            $_obfuscate_6RYLWQÿÿ = array( );
        }
        foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_Vwty]['selectorSQL'] = htmlspecialchars_decode( $_obfuscate_VgKtFegÿ['selectorSQL'] );
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

    private function _updateDetailName( )
    {
        global $CNOA_DB;
        $_obfuscate_0W8ÿ = getpar( $_POST, "id", 0 );
        $_obfuscate_VgKtFegÿ = getpar( $_POST, "value", 0 );
        $_obfuscate_YIq2A8cÿ = getpar( $_POST, "field", 0 );
        $_obfuscate_gftfagwÿ = $CNOA_DB->db_getcount( "abutment_sqldetail", "WHERE id!=".$_obfuscate_0W8ÿ." AND `{$_obfuscate_YIq2A8cÿ}`='{$_obfuscate_VgKtFegÿ}'" );
        if ( !empty( $_obfuscate_gftfagwÿ ) )
        {
            msg::callback( FALSE, "åç§°å·²ç»å­å¨ï¼" );
        }
        $CNOA_DB->db_update( array(
            $_obfuscate_YIq2A8cÿ => $_obfuscate_VgKtFegÿ
        ), "abutment_sqldetail", "WHERE id=".$_obfuscate_0W8ÿ );
        msg::callback( TRUE, "æä½æåï¼" );
    }

    private function _adddetail( )
    {
        global $CNOA_DB;
        $CNOA_DB->db_insert( array( "name" => "åå»ä¿®æ¹åç§°" ), "abutment_sqldetail" );
        msg::callback( TRUE, "æä½æåï¼" );
    }

    private function _getDbFieldName( $_obfuscate_lWk5hHye = FALSE )
    {
        global $CNOA_SESSION;
        global $CNOA_DB;
        $_obfuscate_0W8ÿ = getpar( $_POST, "id", getpar( $_GET, "id", 0 ) );
        if ( empty( $_obfuscate_0W8ÿ ) )
        {
            $_obfuscate_b79bo3UyH9Aÿ = getpar( $_GET, "detailId", 0 );
            $_obfuscate_0W8ÿ = $CNOA_DB->db_getfield( "sqldetailId", "wf_s_field", "WHERE id=".$_obfuscate_b79bo3UyH9Aÿ );
        }
        if ( !isset( $_POST['new'] ) )
        {
            $_obfuscate_LB6BqQÿÿ = $CNOA_DB->db_getfield( "map", "abutment_sqldetail", "WHERE `id`=".$_obfuscate_0W8ÿ );
        }
        if ( !empty( $_obfuscate_LB6BqQÿÿ ) )
        {
            $_obfuscate_cBms = json_decode( $_obfuscate_LB6BqQÿÿ, TRUE );
        }
        else
        {
            $_obfuscate_oDAS4YopcE7Hswÿÿ = getpar( $_POST, "databaseId" );
            $_obfuscate_sx8ÿ = $this->connectDb( $_obfuscate_oDAS4YopcE7Hswÿÿ );
            $_obfuscate_3y0Y = $this->changeSql( $_POST['selectorSQL'], $_obfuscate_0W8ÿ );
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
                $_obfuscate_cBms = array( );
                foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
                {
                    $_obfuscate_cBms[]['name'] = $_obfuscate_Vwty;
                }
            }
            $_obfuscate_LB6BqQÿÿ = $this->encode_json( $_obfuscate_cBms );
            $CNOA_DB->select_db( CNOA_DB_NAME );
            $CNOA_DB->db_update( array(
                "map" => $_obfuscate_LB6BqQÿÿ
            ), "abutment_sqldetail", "WHERE `id`=".$_obfuscate_0W8ÿ );
        }
        if ( $_obfuscate_lWk5hHye )
        {
            return $_obfuscate_cBms;
        }
        ( );
        $_obfuscate_NlQÿ = new DataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_cBms;
        echo $_obfuscate_NlQÿ->makeJsonData( );
    }

    private function _updateMapName( )
    {
        global $CNOA_DB;
        $_obfuscate_0W8ÿ = getpar( $_POST, "id" );
        $_obfuscate_gkt = getpar( $_POST, "row", 0 );
        $_obfuscate_LB6BqQÿÿ = $CNOA_DB->db_getfield( "map", "abutment_sqldetail", "WHERE `id`=".$_obfuscate_0W8ÿ );
        $_obfuscate_cBms = json_decode( $_obfuscate_LB6BqQÿÿ, TRUE );
        $_obfuscate_cBms[$_obfuscate_gkt]['mapName'] = getpar( $_POST, "value" );
        $_obfuscate_LB6BqQÿÿ = $this->encode_json( $_obfuscate_cBms );
        $_obfuscate_LB6BqQÿÿ = addslashes( $_obfuscate_LB6BqQÿÿ );
        $CNOA_DB->db_update( array(
            "map" => $_obfuscate_LB6BqQÿÿ
        ), "abutment_sqldetail", "WHERE `id`=".$_obfuscate_0W8ÿ );
        msg::callback( TRUE, "æä½æå" );
    }

    private function _getCustomerCustomField( )
    {
        $_obfuscate_6RYLWQÿÿ = $this->_getDbFieldName( TRUE );
        if ( !is_array( $_obfuscate_6RYLWQÿÿ ) )
        {
            $_obfuscate_6RYLWQÿÿ = array( );
        }
        $_obfuscate_m2Gt6Iÿ = array( );
        $_obfuscate_7wÿÿ = 0;
        foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_VgKtFegÿ )
        {
            if ( !empty( $_obfuscate_VgKtFegÿ['mapName'] ) )
            {
                $_obfuscate_m2Gt6Iÿ[$_obfuscate_7wÿÿ]['show'] = 1;
                $_obfuscate_m2Gt6Iÿ[$_obfuscate_7wÿÿ]['field'] = $_obfuscate_VgKtFegÿ['name'];
                $_obfuscate_m2Gt6Iÿ[$_obfuscate_7wÿÿ]['fieldname'] = $_obfuscate_VgKtFegÿ['mapName'];
                ++$_obfuscate_7wÿÿ;
            }
        }
        echo $this->encode_json( $_obfuscate_m2Gt6Iÿ );
    }

    private function _getCustomerList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_0W8ÿ = getpar( $_GET, "id", getpar( $_POST, "id", 0 ) );
        if ( empty( $_obfuscate_0W8ÿ ) )
        {
            $_obfuscate_b79bo3UyH9Aÿ = getpar( $_GET, "detailId", 0 );
            $_obfuscate_0W8ÿ = $CNOA_DB->db_getfield( "sqldetailId", "wf_s_field", "WHERE id=".$_obfuscate_b79bo3UyH9Aÿ );
        }
        $_obfuscate_oDAS4YopcE7Hswÿÿ = getpar( $_GET, "databaseId", getpar( $_POST, "databaseId", 0 ) );
        if ( empty( $_obfuscate_oDAS4YopcE7Hswÿÿ ) )
        {
            $_obfuscate_SeV31Qÿÿ = $CNOA_DB->db_getone( array( "databaseId", "selectorSQL" ), "abutment_sqldetail", "WHERE id=".$_obfuscate_0W8ÿ );
            $_obfuscate_oDAS4YopcE7Hswÿÿ = $_obfuscate_SeV31Qÿÿ['databaseId'];
            $_obfuscate_f00irgStQpqqe3Aÿ = $_obfuscate_SeV31Qÿÿ['selectorSQL'];
        }
        else
        {
            $_obfuscate_f00irgStQpqqe3Aÿ = getpar( $_GET, "selectorSQL", getpar( $_POST, "selectorSQL" ) );
            $_obfuscate_f00irgStQpqqe3Aÿ = $_GET['selectorSQL'];
        }
        if ( FALSE !== strpos( $_obfuscate_f00irgStQpqqe3Aÿ, "{limit}" ) || FALSE !== strpos( $_obfuscate_f00irgStQpqqe3Aÿ, "{start}" ) )
        {
            $_obfuscate_AedrEgÿÿ = TRUE;
        }
        else
        {
            $_obfuscate_AedrEgÿÿ = FALSE;
            $_obfuscate_mV9HBLYÿ = getpar( $_POST, "start", 0 );
            $_obfuscate_xvYeh9Iÿ = getpar( $_POST, "limit", 100 );
        }
        $_obfuscate_dYGaubEIH28ÿ = $CNOA_DB->db_getone( "*", "abutment_database", "WHERE `id`=".$_obfuscate_oDAS4YopcE7Hswÿÿ );
        $_obfuscate_sx8ÿ = $this->connectDb( $_obfuscate_dYGaubEIH28ÿ );
        $_obfuscate_f00irgStQpqqe3Aÿ = $this->changeSql( $_obfuscate_f00irgStQpqqe3Aÿ, $_obfuscate_0W8ÿ );
        $_obfuscate_f00irgStQpqqe3Aÿ = htmlspecialchars_decode( $_obfuscate_f00irgStQpqqe3Aÿ );
        ( );
        $_obfuscate_NlQÿ = new DataStore( );
        if ( !$_obfuscate_AedrEgÿÿ || $_obfuscate_dYGaubEIH28ÿ['dbType'] == "MYSQL" )
        {
            $_obfuscate_NlQÿ->total = $_obfuscate_sx8ÿ->getCount( $_obfuscate_f00irgStQpqqe3Aÿ );
            $_obfuscate_f00irgStQpqqe3Aÿ .= " LIMIT ".$_obfuscate_mV9HBLYÿ.", {$_obfuscate_xvYeh9Iÿ} ";
        }
        $_obfuscate_ammigv8ÿ = $_obfuscate_sx8ÿ->query( $_obfuscate_f00irgStQpqqe3Aÿ );
        $_obfuscate_6RYLWQÿÿ = $_obfuscate_sx8ÿ->getResult( $_obfuscate_ammigv8ÿ );
        if ( !empty( $_obfuscate_6RYLWQÿÿ[0] ) || !array_key_exists( "id", $_obfuscate_6RYLWQÿÿ[0] ) )
        {
            $_obfuscate_7wÿÿ = 1;
            foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
            {
                $_obfuscate_6RYLWQÿÿ[$_obfuscate_Vwty]['id'] = $_obfuscate_7wÿÿ;
                ++$_obfuscate_7wÿÿ;
            }
        }
        if ( $_obfuscate_AedrEgÿÿ )
        {
            $_obfuscate_mV9HBLYÿ = getpar( $_POST, "start", 0 );
            $_obfuscate_xvYeh9Iÿ = getpagesize( "abutment_abutment_limit" );
            if ( $_obfuscate_xvYeh9Iÿ == count( $_obfuscate_6RYLWQÿÿ ) )
            {
                $_obfuscate_NlQÿ->total = $_obfuscate_mV9HBLYÿ + $_obfuscate_xvYeh9Iÿ + 1;
            }
            else
            {
                $_obfuscate_NlQÿ->total = $_obfuscate_mV9HBLYÿ + count( $_obfuscate_6RYLWQÿÿ );
                unset( $this->pageSize['id'] );
                unset( $this->pageSize['limit'] );
            }
        }
        else if ( $_obfuscate_dYGaubEIH28ÿ['dbType'] == "SQL SERVER" )
        {
            $_obfuscate_NlQÿ->total = count( $_obfuscate_6RYLWQÿÿ );
            $_obfuscate_m2Gt6Iÿ = array_chunk( $_obfuscate_6RYLWQÿÿ, $_obfuscate_xvYeh9Iÿ );
            $_obfuscate_6RYLWQÿÿ = $_obfuscate_m2Gt6Iÿ[$_obfuscate_mV9HBLYÿ / $_obfuscate_xvYeh9Iÿ];
        }
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
    }

    private function _getCustomerSearchFields( )
    {
        global $CNOA_DB;
        $_obfuscate_0W8ÿ = getpar( $_GET, "id", getpar( $_POST, "id", 0 ) );
        if ( empty( $_obfuscate_0W8ÿ ) )
        {
            $_obfuscate_b79bo3UyH9Aÿ = getpar( $_GET, "detailId", 0 );
            $_obfuscate_0W8ÿ = $CNOA_DB->db_getfield( "sqldetailId", "wf_s_field", "WHERE id=".$_obfuscate_b79bo3UyH9Aÿ );
        }
        $_obfuscate_SeV31Qÿÿ = $CNOA_DB->db_select( array( "id" ), "abutment_search", "WHERE did = ".$_obfuscate_0W8ÿ );
        if ( !is_array( $_obfuscate_SeV31Qÿÿ ) )
        {
            $_obfuscate_SeV31Qÿÿ = array( );
        }
        $_obfuscate_K16DLBjnEvIhAÿÿ = array( );
        foreach ( $_obfuscate_SeV31Qÿÿ as $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_K16DLBjnEvIhAÿÿ[] = $_obfuscate_VgKtFegÿ['id'];
        }
        $_obfuscate_1Yv3R3ZVwQ86Iatsk3Dlmwÿÿ = $CNOA_DB->db_select( array( "id", "mapName" ), "abutment_search_fields", "WHERE fid in(".implode( ",", $_obfuscate_K16DLBjnEvIhAÿÿ ).")" );
        msg::callback( TRUE, $_obfuscate_1Yv3R3ZVwQ86Iatsk3Dlmwÿÿ );
    }

    private function _deleteSqldetail( )
    {
        global $CNOA_DB;
        $_obfuscate_0W8ÿ = getpar( $_POST, "id", 0 );
        $this->deleteDetailSearch( $_obfuscate_0W8ÿ );
        $CNOA_DB->db_delete( "abutment_sqldetail", "WHERE id=".$_obfuscate_0W8ÿ );
        msg::callback( TRUE, "å é¤æå" );
    }

    private function deleteDetailSearch( $_obfuscate_0W8ÿ )
    {
        global $CNOA_DB;
        $_obfuscate_SeV31Qÿÿ = $CNOA_DB->db_select( array( "id" ), "abutment_search", "WHERE did = ".$_obfuscate_0W8ÿ );
        $CNOA_DB->db_delete( "abutment_search", "WHERE did = ".$_obfuscate_0W8ÿ );
        if ( !is_array( $_obfuscate_SeV31Qÿÿ ) )
        {
            $_obfuscate_SeV31Qÿÿ = array( );
        }
        $_obfuscate_K16DLBjnEvIhAÿÿ = array( );
        foreach ( $_obfuscate_SeV31Qÿÿ as $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_K16DLBjnEvIhAÿÿ[] = $_obfuscate_VgKtFegÿ['id'];
        }
        $CNOA_DB->db_delete( "abutment_search_fields", "WHERE fid in(".implode( ",", $_obfuscate_K16DLBjnEvIhAÿÿ ).")" );
    }

    private function _getSearchList( )
    {
        global $CNOA_DB;
        $_obfuscate_0W8ÿ = getpar( $_GET, "id", 0 );
        $_obfuscate_6RYLWQÿÿ = $CNOA_DB->db_select( "*", "abutment_search", "WHERE did = ".$_obfuscate_0W8ÿ );
        ( );
        $_obfuscate_NlQÿ = new DataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
    }

    private function _addSearch( )
    {
        global $CNOA_DB;
        $_obfuscate_0W8ÿ = getpar( $_POST, "id", 0 );
        if ( empty( $_obfuscate_0W8ÿ ) )
        {
            msg::callback( FALSE, "éè¯¯" );
        }
        $_obfuscate_6RYLWQÿÿ = array(
            "did" => $_obfuscate_0W8ÿ,
            "name" => "åå»ä¿®æ¹åç§°"
        );
        $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, "abutment_search" );
        msg::callback( TRUE, "æä½æåï¼" );
    }

    private function _updateSearchName( $_obfuscate_3tiDsnMÿ = "abutment_search" )
    {
        global $CNOA_DB;
        $_obfuscate_0W8ÿ = getpar( $_POST, "id", 0 );
        $_obfuscate_VgKtFegÿ = getpar( $_POST, "value", 0 );
        $_obfuscate_YIq2A8cÿ = getpar( $_POST, "field", 0 );
        $CNOA_DB->db_update( array(
            $_obfuscate_YIq2A8cÿ => $_obfuscate_VgKtFegÿ
        ), $_obfuscate_3tiDsnMÿ, "WHERE id=".$_obfuscate_0W8ÿ );
        msg::callback( TRUE, "æä½æåï¼" );
    }

    private function _addSearchField( )
    {
        global $CNOA_DB;
        $_obfuscate_0W8ÿ = getpar( $_POST, "id", 0 );
        if ( empty( $_obfuscate_0W8ÿ ) )
        {
            msg::callback( FALSE, "éè¯¯" );
        }
        $_obfuscate_6RYLWQÿÿ = array(
            "fid" => $_obfuscate_0W8ÿ,
            "name" => "åå»ä¿®æ¹åç§°",
            "mapName" => "æ å°å"
        );
        $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, "abutment_search_fields" );
        msg::callback( TRUE, "æä½æåï¼" );
    }

    private function _getSearchField( )
    {
        global $CNOA_DB;
        $_obfuscate_0W8ÿ = getpar( $_GET, "id", 0 );
        $_obfuscate_6RYLWQÿÿ = $CNOA_DB->db_select( "*", "abutment_search_fields", "WHERE fid = ".$_obfuscate_0W8ÿ );
        ( );
        $_obfuscate_NlQÿ = new DataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
    }

    private function _updateSearchFieldName( )
    {
        $this->_updateSearchName( "abutment_search_fields" );
    }

    private function _delSearchFields( )
    {
        global $CNOA_DB;
        $_obfuscate_0W8ÿ = getpar( $_POST, "id", 0 );
        $CNOA_DB->db_delete( "abutment_search_fields", "WHERE id = ".$_obfuscate_0W8ÿ );
        msg::callback( TRUE, "æä½æåï¼" );
    }

}

?>
