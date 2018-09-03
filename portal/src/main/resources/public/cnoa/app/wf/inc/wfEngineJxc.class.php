<?php

class wfEngineJxc
{

    protected $table_bind_fields = "jxc_bind_flowfield";
    protected $table_custom_field = "jxc_custom_field";
    protected $table_fix_field = "jxc_custom_fix_field";

    const FIELD_TYPE_DATE = 5;
    const FIELD_TYPE_COMBO = 6;
    const FIELD_TYPE_USER = 7;
    const TYPE_FIX = 1;
    const TYPE_CUSTOM = 2;
    const CUSTOM_FIELD_CODE = "jxc";

    public function __construct( )
    {
    }

    public function getBusinessData( $_obfuscate_pYzeLf4ÿ, $_obfuscate_h8xAiUQÿ = NULL )
    {
        if ( $_obfuscate_h8xAiUQÿ == "phone" )
        {
            $_obfuscate_6RYLWQÿÿ = $this->$_obfuscate_pYzeLf4ÿ( "phone" );
            return $_obfuscate_6RYLWQÿÿ;
        }
        echo json_encode( $this->$_obfuscate_pYzeLf4ÿ( ) );
        exit( );
    }

    public function setBusinessData( $_obfuscate_wD9kdBYÿ, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_0Ul8BBkt, $_obfuscate_WyQCbNwalCxRcNMÿ )
    {
        global $CNOA_DB;
        $_obfuscate_BeDD62zcK1Uÿ = array( );
        foreach ( $GLOBALS['_POST'] as $_obfuscate_3gn_eQÿÿ => $_obfuscate_TAxu )
        {
            if ( preg_match( "/^wf_field_(\\d+)/", $_obfuscate_3gn_eQÿÿ, $_obfuscate_jq3moiIÿ ) )
            {
                $_obfuscate_BeDD62zcK1Uÿ[] = $_obfuscate_jq3moiIÿ[1];
            }
        }
        $_obfuscate_8LH7ik2lzjhs7gÿÿ = $this->_getFieldByWidgetId( $_obfuscate_BeDD62zcK1Uÿ );
        $_obfuscate_6RYLWQÿÿ = array( );
        foreach ( $_obfuscate_BeDD62zcK1Uÿ as $_obfuscate_rPHT )
        {
            $_obfuscate_3gn_eQÿÿ = $_obfuscate_8LH7ik2lzjhs7gÿÿ[$_obfuscate_rPHT]['valuefield'];
            $_obfuscate_2VylPNGhxLc0 = $_obfuscate_8LH7ik2lzjhs7gÿÿ[$_obfuscate_rPHT]['fieldtype'];
            if ( isset( $_obfuscate_3gn_eQÿÿ ) )
            {
                if ( $_obfuscate_2VylPNGhxLc0 == self::FIELD_TYPE_COMBO )
                {
                    $_obfuscate_FckHk8RR1IQÿ = "wf_engine_field_".$_obfuscate_rPHT;
                    $_obfuscate_6RYLWQÿÿ[$_obfuscate_3gn_eQÿÿ] = isset( $_POST[$_obfuscate_FckHk8RR1IQÿ] ) ? getpar( $_POST, $_obfuscate_FckHk8RR1IQÿ ) : getpar( $_POST, "wf_field_".$_obfuscate_rPHT );
                }
                else if ( $_obfuscate_2VylPNGhxLc0 == self::FIELD_TYPE_DATE )
                {
                    $_obfuscate_6RYLWQÿÿ[$_obfuscate_3gn_eQÿÿ] = strtotime( getpar( $_POST, "wf_field_".$_obfuscate_rPHT ) );
                }
                else
                {
                    $_obfuscate_6RYLWQÿÿ[$_obfuscate_3gn_eQÿÿ] = getpar( $_POST, "wf_field_".$_obfuscate_rPHT );
                }
            }
        }
        if ( getpar( $_POST, "nextStepId" ) == 3 )
        {
            $_obfuscate_wD9kdBYÿ = $_obfuscate_wD9kdBYÿ == "new" ? "all" : "end";
        }
        $this->saveData( $_obfuscate_6RYLWQÿÿ, $_obfuscate_wD9kdBYÿ, $_obfuscate_TlvKhtsoOQÿÿ );
    }

    public function getData( )
    {
        $operate = getpar( $_GET, "operate", "" );
        switch ( $operate )
        {
        case "getStorageBindWidgetId" :
            ( self::CUSTOM_FIELD_CODE );
            $cf = new customField( );
            echo $cf->getBindWidgetIdByDisplayfield( $this->getModelIdByObject( ), "storagename" );
            break;
        case "getGoodsCustomField" :
            include_once( CNOA_PATH."/app/jxc/source/jxcBase.class.php" );
            $fields = app::loadapp( "jxc", "baseGoods" )->api_getGoodsCustomField( );
            $fields[] = array( "type" => "fix", "field" => "usable", "fieldname" => "å¯ç¨æ°", "show" => 1, "add" => 1 );
            echo json_encode( $fields );
            break;
        case "getGoodsList" :
            include_once( CNOA_PATH."/app/jxc/source/jxcBase.class.php" );
            $storageId = intval( getpar( $_GET, "storageId" ) );
            $data = app::loadapp( "jxc", "baseGoods" )->api_getGoodsList( $storageId, TRUE );
            $total = $data['total'];
            $data = $data['data'];
            $quantity = app::loadapp( "jxc", "stock" )->api_getGoodsQuantity( $storageId );
            if ( !is_array( $data ) )
            {
                $data = array( );
            }
            foreach ( $data as $k => $v )
            {
                $v['usable'] = $quantity[$v['id']];
                $data[$k] = $v;
            }
            ( );
            $dataStore = new dataStore( );
            $dataStore->data = $data;
            $dataStore->total = $total;
            echo $dataStore->makeJsonData( );
            break;
        case "getComboList" :
            include_once( CNOA_PATH."/app/jxc/source/jxcBase.class.php" );
            $storageId = intval( getpar( $_GET, "storageId" ) );
            $data = app::loadapp( "jxc", "baseGoods" )->api_getSortsBySid( $storageId );
            ( );
            $dataStore = new dataStore( );
            $dataStore->data = $data;
            echo $dataStore->makeJsonData( );
            break;
        case "getOnlyCustomField" :
            include_once( CNOA_PATH."/app/jxc/source/jxcBase.class.php" );
            $onlyFields = array( );
            $fields = app::loadapp( "jxc", "baseGoods" )->api_getGoodsCustomField( );
            foreach ( $fields as $k => $v )
            {
                if ( !( strpos( $v[field], "field" ) !== FALSE ) && !( $v[fieldtype] < 5 ) )
                {
                    $onlyFields[] = $v;
                }
            }
            ( );
            $dataStore = new dataStore( );
            $dataStore->data = $onlyFields;
            echo $dataStore->makeJsonData( );
        }
    }

    protected function saveData( $_obfuscate_6RYLWQÿÿ, $_obfuscate_wD9kdBYÿ, $_obfuscate_TlvKhtsoOQÿÿ )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        if ( $this->getModelIdByObject( ) == 4 )
        {
            ( $_obfuscate_TlvKhtsoOQÿÿ );
            $_obfuscate_e53ODz04JQÿÿ = new wfCache( );
            $_obfuscate_6AxGjhiu62pGaX6 = $_obfuscate_e53ODz04JQÿÿ->getDetailFields( );
            $_obfuscate_mnGpwkqvvb1bAÿÿ = $_obfuscate_e53ODz04JQÿÿ->getFlowFields( );
            $_obfuscate_8q4EcPJGgrXqPwÿÿ = array( );
            ( self::CUSTOM_FIELD_CODE );
            $_obfuscate_LU8ÿ = new customField( );
            $_obfuscate_8cfLuqe_579RjweN = "wf_engine_field_".$_obfuscate_LU8ÿ->getBindWidgetIdByDisplayfield( $this->getModelIdByObject( ), "storagename" );
            $_obfuscate_NwefuWkoWhUJ = getpar( $_POST, $_obfuscate_8cfLuqe_579RjweN, 0 );
            $_obfuscate_Z0hLGsoÿ = $CNOA_DB->db_getcount( "jxc_storage", " WHERE `storageid`=".$_obfuscate_NwefuWkoWhUJ );
            if ( $_obfuscate_Z0hLGsoÿ <= 0 )
            {
                $_obfuscate_NwefuWkoWhUJ = $CNOA_DB->db_getfield( "id", "jxc_storage", " WHERE `storagename`='".$_obfuscate_NwefuWkoWhUJ."'" );
            }
            $_obfuscate_4zvQEÿ = app::loadapp( "jxc", "stock" )->api_getGoodsQuantity( $_obfuscate_NwefuWkoWhUJ );
            if ( is_array( $_obfuscate_6AxGjhiu62pGaX6 ) )
            {
                foreach ( $_obfuscate_6AxGjhiu62pGaX6 as $_obfuscate_6Aÿÿ )
                {
                    switch ( $_obfuscate_6Aÿÿ['binfield'] )
                    {
                    case "quantity" :
                        $_obfuscate_8q4EcPJGgrXqPwÿÿ['quantity'] = $_obfuscate_6Aÿÿ['id'];
                    }
                }
            }
            foreach ( $_obfuscate_mnGpwkqvvb1bAÿÿ as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_3tiDsnMÿ = "z_wf_d_".$_obfuscate_6Aÿÿ['flowId']."_".$_obfuscate_6Aÿÿ['id'];
                $_obfuscate_xcq8G0B1Gyj_fUUÿ = $CNOA_DB->db_tableExists( CNOA_DB_PRE.$_obfuscate_3tiDsnMÿ );
                if ( $_obfuscate_xcq8G0B1Gyj_fUUÿ )
                {
                    $_obfuscate_fMfsswÿÿ = $CNOA_DB->db_select( "*", $_obfuscate_3tiDsnMÿ, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
                    if ( !is_array( $_obfuscate_fMfsswÿÿ ) )
                    {
                        $_obfuscate_fMfsswÿÿ = array( );
                    }
                    foreach ( $_obfuscate_fMfsswÿÿ as $_obfuscate_gkt )
                    {
                        $_obfuscate_177GXFi4h8Mr = $this->getGoodsName( $_obfuscate_gkt['bindid'] );
                        $_obfuscate_U2hLJYnHZ6Iÿ = floatval( $_obfuscate_gkt["D_".$_obfuscate_8q4EcPJGgrXqPwÿÿ['quantity']] );
                        if ( !isset( $_obfuscate_4zvQEÿ[$_obfuscate_gkt['bindid']] ) && !( $_obfuscate_4zvQEÿ[$_obfuscate_gkt['bindid']] < $_obfuscate_U2hLJYnHZ6Iÿ ) )
                        {
                            msg::callback( FALSE, "{$_obfuscate_177GXFi4h8Mr}å·²è¶åºåºå­" );
                        }
                    }
                }
            }
        }
        if ( $_obfuscate_wD9kdBYÿ == "all" )
        {
            $_obfuscate_wD9kdBYÿ = "new";
            $_obfuscate_6RYLWQÿÿ['status'] = 1;
        }
        if ( $_obfuscate_wD9kdBYÿ == "new" )
        {
            $_obfuscate_6RYLWQÿÿ['uid'] = $CNOA_SESSION->get( "UID" );
            $_obfuscate_6RYLWQÿÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
            $_obfuscate_6RYLWQÿÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $_obfuscate_6RYLWQÿÿ['indentnumber'] = $CNOA_DB->db_getfield( "flowNumber", "wf_u_flow", "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ );
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->model_table );
        }
        else
        {
            if ( $_obfuscate_wD9kdBYÿ == "end" )
            {
                $_obfuscate_6RYLWQÿÿ['status'] = 1;
            }
            if ( empty( $_obfuscate_6RYLWQÿÿ ) )
            {
                return;
            }
            $CNOA_DB->db_update( $_obfuscate_6RYLWQÿÿ, $this->model_table, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ );
        }
    }

    protected function getCustomComboStore( )
    {
        $_obfuscate_6RYLWQÿÿ = array( );
        ( self::CUSTOM_FIELD_CODE );
        $_obfuscate_LU8ÿ = new customField( );
        $_obfuscate_3y0Y = "SELECT `b`.`widgetId`,`b`.`fieldId` FROM ".tname( $this->table_bind_fields )." AS `b` LEFT JOIN ".tname( $this->table_custom_field )." AS `c` ON `c`.`fieldId`=`b`.`fieldId` ".( "WHERE `b`.`mid`=".$this->modelId." AND `type`=2 AND `c`.`fieldtype`=6" );
        global $CNOA_DB;
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_BeDD62zcK1Uÿ]['data'] = $_obfuscate_LU8ÿ->getComboItem( $this->modelId, $_obfuscate_8jhldA9Y9Aÿÿ, 2 );
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_BeDD62zcK1Uÿ]['display'] = "field".$_obfuscate_8jhldA9Y9Aÿÿ."_name";
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_BeDD62zcK1Uÿ]['value'] = "field".$_obfuscate_8jhldA9Y9Aÿÿ;
        }
        return $_obfuscate_6RYLWQÿÿ;
    }

    private function _getFieldByWidgetId( $_obfuscate_BeDD62zcK1Uÿ )
    {
        $_obfuscate_BeDD62zcK1Uÿ = empty( $_obfuscate_BeDD62zcK1Uÿ ) ? intval( $_obfuscate_BeDD62zcK1Uÿ ) : implode( ",", $_obfuscate_BeDD62zcK1Uÿ );
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "type", "fieldId", "widgetId" ), $this->table_bind_fields, "WHERE `mid`=".$this->modelId." AND `widgetId` IN ({$_obfuscate_BeDD62zcK1Uÿ})" );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate__tpd9ynTdV_R2Aÿÿ = $_obfuscate_RHPwmcMk3GbU3QfQDQÿÿ = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['type'] == 1 )
            {
                $_obfuscate__tpd9ynTdV_R2Aÿÿ[] = $_obfuscate_6Aÿÿ['fieldId'];
            }
            else if ( $_obfuscate_6Aÿÿ['type'] == 2 )
            {
                $_obfuscate_RHPwmcMk3GbU3QfQDQÿÿ[] = $_obfuscate_6Aÿÿ['fieldId'];
            }
        }
        $_obfuscate__tpd9ynTdV_R2Aÿÿ = empty( $_obfuscate__tpd9ynTdV_R2Aÿÿ ) ? 0 : implode( ",", $_obfuscate__tpd9ynTdV_R2Aÿÿ );
        $_obfuscate_RHPwmcMk3GbU3QfQDQÿÿ = empty( $_obfuscate_RHPwmcMk3GbU3QfQDQÿÿ ) ? 0 : implode( ",", $_obfuscate_RHPwmcMk3GbU3QfQDQÿÿ );
        $_obfuscate_q92wBwv7zcUAy0J = $CNOA_DB->db_select( array( "fieldId", "valuefield", "fieldtype" ), $this->table_fix_field, "WHERE `fieldId` IN (".$_obfuscate__tpd9ynTdV_R2Aÿÿ.")" );
        if ( !is_array( $_obfuscate_q92wBwv7zcUAy0J ) )
        {
            $_obfuscate_q92wBwv7zcUAy0J = array( );
        }
        $_obfuscate_4mDwHxkIFTTF = array( );
        foreach ( $_obfuscate_q92wBwv7zcUAy0J as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_4mDwHxkIFTTF[$_obfuscate_6Aÿÿ['fieldId']] = $_obfuscate_6Aÿÿ;
        }
        $_obfuscate_dIU3O4WItT0LshUBv_oc = $CNOA_DB->db_select( array( "fieldId", "fieldtype" ), $this->table_custom_field, "WHERE `fieldId` IN (".$_obfuscate_RHPwmcMk3GbU3QfQDQÿÿ.")" );
        if ( !is_array( $_obfuscate_dIU3O4WItT0LshUBv_oc ) )
        {
            $_obfuscate_dIU3O4WItT0LshUBv_oc = array( );
        }
        $_obfuscate_LVBcg10uhBIolWRd = array( );
        foreach ( $_obfuscate_dIU3O4WItT0LshUBv_oc as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_LVBcg10uhBIolWRd[$_obfuscate_6Aÿÿ['fieldId']] = $_obfuscate_6Aÿÿ;
        }
        $_obfuscate_8LH7ik2lzjhs7gÿÿ = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_Ce9h = $_obfuscate_6Aÿÿ['fieldId'];
            if ( $_obfuscate_6Aÿÿ['type'] == 2 )
            {
                $_obfuscate_LVBcg10uhBIolWRd[$_obfuscate_Ce9h]['valuefield'] = "field".$_obfuscate_Ce9h;
                $_obfuscate_8LH7ik2lzjhs7gÿÿ[$_obfuscate_6Aÿÿ['widgetId']] = $_obfuscate_LVBcg10uhBIolWRd[$_obfuscate_Ce9h];
            }
            else if ( $_obfuscate_6Aÿÿ['type'] == 1 )
            {
                $_obfuscate_8LH7ik2lzjhs7gÿÿ[$_obfuscate_6Aÿÿ['widgetId']] = $_obfuscate_4mDwHxkIFTTF[$_obfuscate_Ce9h];
            }
        }
        return $_obfuscate_8LH7ik2lzjhs7gÿÿ;
    }

    private function getModelIdByObject( )
    {
        switch ( get_class( $this ) )
        {
        case "wfEngineJxcRuku" :
            return 3;
        case "wfEngineJxcChuku" :
            return 4;
        }
    }

    private function getGoodsName( $_obfuscate_hYZjHESruAÿÿ )
    {
        global $CNOA_DB;
        $_obfuscate_3gn_eQÿÿ = $CNOA_DB->db_getfield( "goodsname", "jxc_goods", "WHERE `id`=".$_obfuscate_hYZjHESruAÿÿ );
        return $_obfuscate_3gn_eQÿÿ;
    }

}

?>
