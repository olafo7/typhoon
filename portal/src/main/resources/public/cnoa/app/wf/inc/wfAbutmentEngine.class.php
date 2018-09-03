<?php

class wfAbutmentEngine
{

    private $map = NULL;
    private $isNew = "";
    private $flowId = NULL;
    private $uFlowId = NULL;
    private $stepId = NULL;
    private $nextStepUid = NULL;
    private $bindFields = NULL;
    private $detailFields = NULL;
    private $detailData = array( );

    public function __construct( $isNew, $flowId, $uFlowId, $stepId, $nextStepUid )
    {
        $mapPath = CNOA_PATH_FILE.( "/common/wf/abutment/".$flowId.".map.php" );
        if ( !file_exists( $mapPath ) )
        {
            return FALSE;
        }
        $this->isNew = $isNew;
        $this->flowId = $flowId;
        $this->uFlowId = $uFlowId;
        $this->stepId = $stepId;
        $this->nextStepUid = $nextStepUid;
        $this->map = include( $mapPath );
        $this->run( );
    }

    private function run( )
    {
        foreach ( $this->map as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
        {
            if ( !$_obfuscate_VgKtFegÿ['open'] )
            {
            }
            else
            {
                $_obfuscate_0W8ÿ = $_obfuscate_VgKtFegÿ['check']['id'];
                $_obfuscate_aMwmYIÿ = isset( $_POST["wf_engine_field_".$_obfuscate_0W8ÿ] ) ? $_POST["wf_engine_field_".$_obfuscate_0W8ÿ] : $_POST["wf_field_".$_obfuscate_0W8ÿ];
                if ( !( $_obfuscate_aMwmYIÿ != "åŒæ„" ) )
                {
                    $this->bindFields = $_obfuscate_VgKtFegÿ['field'];
                    if ( $this->isNew == "new" )
                    {
                        $this->makeData4Post( );
                    }
                    else
                    {
                        $this->makeData4Table( );
                    }
                    if ( !empty( $_obfuscate_VgKtFegÿ['detail'] ) )
                    {
                        $this->makeData4Detail( );
                        $this->detailFields = $_obfuscate_VgKtFegÿ['detail'];
                        $this->writeDetailData( $_obfuscate_Vwty, $_obfuscate_VgKtFegÿ['outType'], $_obfuscate_VgKtFegÿ['whereSQL'] );
                    }
                    else
                    {
                        $this->writeData( $_obfuscate_Vwty, $_obfuscate_VgKtFegÿ['outType'], $_obfuscate_VgKtFegÿ['whereSQL'] );
                    }
                }
            }
        }
    }

    private function makeData4Post( $_obfuscate_b55OQÿÿ = array( ) )
    {
        if ( !is_array( $_obfuscate_b55OQÿÿ ) )
        {
            return FALSE;
        }
        foreach ( $GLOBALS['_POST'] as $_obfuscate_3gn_eQÿÿ => $_obfuscate_TAxu )
        {
            if ( preg_match( "/^wf_field_(\\d+)/", $_obfuscate_3gn_eQÿÿ, $_obfuscate_0W8ÿ ) )
            {
                $_obfuscate_YIq2A8cÿ = $this->bindFields[$_obfuscate_0W8ÿ[1]];
                if ( !isset( $_obfuscate_YIq2A8cÿ ) )
                {
                }
                else if ( !empty( $_obfuscate_b55OQÿÿ ) || !in_array( $_obfuscate_YIq2A8cÿ, $_obfuscate_b55OQÿÿ ) )
                {
                    $_obfuscate_FckHk8RR1IQÿ = "wf_engine_field_".$_obfuscate_0W8ÿ[1];
                    $GLOBALS['_POST'][$_obfuscate_YIq2A8cÿ] = isset( $_POST[$_obfuscate_FckHk8RR1IQÿ] ) ? $_POST[$_obfuscate_FckHk8RR1IQÿ] : $_obfuscate_TAxu;
                }
            }
        }
    }

    private function makeData4Table( $_obfuscate_rFekUqpblT = array( ) )
    {
        global $CNOA_DB;
        if ( empty( $this->flowId ) )
        {
            return FALSE;
        }
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_getone( "*", "z_wf_t_".$this->flowId, "WHERE `uFlowId`=".$this->uFlowId );
        foreach ( ( array )$_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( !( stripos( $_obfuscate_5wÿÿ, "T_" ) !== 0 ) )
            {
                $_obfuscate_8jhldA9Y9Aÿÿ = intval( substr( $_obfuscate_5wÿÿ, 2 ) );
                $_obfuscate_YIq2A8cÿ = $this->bindFields[$_obfuscate_8jhldA9Y9Aÿÿ];
                if ( !isset( $_obfuscate_YIq2A8cÿ ) )
                {
                }
                else
                {
                    $GLOBALS['_POST'][$_obfuscate_YIq2A8cÿ] = in_array( $_obfuscate_YIq2A8cÿ, $_obfuscate_rFekUqpblT ) ? $_POST["wf_engine_field_".$_obfuscate_8jhldA9Y9Aÿÿ] : $_obfuscate_6Aÿÿ;
                }
            }
        }
        $this->makeData4Post( );
    }

    private function makeData4Detail( )
    {
        if ( !empty( $this->detailData ) )
        {
            return;
        }
        $_obfuscate_lWk5hHye = $this->_saveFormFieldInfo( "", "", $this->uFlowId );
        $this->detailData = array( );
        if ( !empty( $_obfuscate_lWk5hHye[0]['detail'] ) )
        {
            foreach ( $_obfuscate_lWk5hHye[0]['detail'] as $_obfuscate_gkt => $_obfuscate_eBU_Sjcÿ )
            {
                foreach ( $_obfuscate_eBU_Sjcÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
                {
                    $this->detailData[$_obfuscate_lWk5hHye[1][$_obfuscate_5wÿÿ]][$_obfuscate_gkt][$_obfuscate_5wÿÿ] = $_obfuscate_6Aÿÿ;
                }
            }
        }
    }

    private function writeData( $_obfuscate_TGTDzydTYAÿÿ, $_obfuscate_H2KBlenOIgÿÿ, $_obfuscate_dKpM2M15b0Iÿ )
    {
        global $CNOA_DB;
        global $CNOA_XXTEA;
        $_obfuscate_3y0Y = "SELECT db.host, db.dbname, db.chart, db.dbType, db.user, db.password, ds.sheet \r\n\t\t\t\tFROM `cnoa_abutment_database` AS `db` LEFT JOIN `cnoa_abutment_datasheet` AS `ds` ON db.id = ds.sid \r\n\t\t\t\tWHERE ds.id = ".$_obfuscate_TGTDzydTYAÿÿ;
        $_obfuscate_dYGaubEIH28ÿ = $CNOA_DB->get_one( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQÿÿ = array( );
        foreach ( $this->bindFields as $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_VgKtFegÿ] = getpar( $_POST, $_obfuscate_VgKtFegÿ );
        }
        $_obfuscate_pbOuOg8ÿ = unserialize( $CNOA_XXTEA->decrypt( $_obfuscate_dYGaubEIH28ÿ['password'] ) );
        $_obfuscate_dYGaubEIH28ÿ['password'] = $_obfuscate_pbOuOg8ÿ['PASSWORD'];
        if ( $_obfuscate_dYGaubEIH28ÿ['dbType'] == "MYSQL" )
        {
            ( );
            $_obfuscate_sx8ÿ = new mysql2( );
            $_obfuscate_mvD3kolYxoIx = $_obfuscate_sx8ÿ->connect( $_obfuscate_dYGaubEIH28ÿ['host'], $_obfuscate_dYGaubEIH28ÿ['dbname'], $_obfuscate_dYGaubEIH28ÿ['user'], $_obfuscate_dYGaubEIH28ÿ['password'], $_obfuscate_dYGaubEIH28ÿ['chart'] );
        }
        else if ( $_obfuscate_dYGaubEIH28ÿ['dbType'] == "SQL SERVER" )
        {
            $_obfuscate_dYGaubEIH28ÿ['chart'] = $_obfuscate_dYGaubEIH28ÿ['chart'] == "UTF8" ? "utf-8" : $_obfuscate_dYGaubEIH28ÿ['chart'];
            ( $_obfuscate_dYGaubEIH28ÿ['chart'] );
            $_obfuscate_sx8ÿ = new mssql2( );
            $_obfuscate_mvD3kolYxoIx = $_obfuscate_sx8ÿ->connect( $_obfuscate_dYGaubEIH28ÿ['host'], $_obfuscate_dYGaubEIH28ÿ['user'], $_obfuscate_dYGaubEIH28ÿ['password'], $_obfuscate_dYGaubEIH28ÿ['dbname'] );
        }
        else
        {
            msg::callback( FALSE, "æ— æ³•è¿žæŽ¥æ•°æ®åº“" );
        }
        if ( $_obfuscate_mvD3kolYxoIx !== TRUE )
        {
            msg::callback( FALSE, "ä¸èƒ½è¿žæŽ¥æ•°æ®åº“" );
        }
        if ( $_obfuscate_H2KBlenOIgÿÿ == "UPDATE" )
        {
            $_obfuscate_IRFhnYwÿ = $this->changeSQL( $_obfuscate_dKpM2M15b0Iÿ, $_obfuscate_6RYLWQÿÿ );
            $_obfuscate_sx8ÿ->db_update( $_obfuscate_6RYLWQÿÿ, $_obfuscate_dYGaubEIH28ÿ['sheet'], $_obfuscate_IRFhnYwÿ );
        }
        else if ( $_obfuscate_H2KBlenOIgÿÿ == "INSERT" )
        {
            $_obfuscate_sx8ÿ->db_insert( $_obfuscate_6RYLWQÿÿ, $_obfuscate_dYGaubEIH28ÿ['sheet'] );
        }
        $CNOA_DB->select_db( CNOA_DB_NAME );
    }

    private function writeDetailData( $_obfuscate_TGTDzydTYAÿÿ, $_obfuscate_H2KBlenOIgÿÿ, $_obfuscate_dKpM2M15b0Iÿ )
    {
        global $CNOA_DB;
        $_obfuscate_3y0Y = "SELECT db.host, db.dbname, db.chart, db.dbType, db.user, db.password, ds.sheet \r\n\t\t\t\tFROM `cnoa_abutment_database` AS `db` LEFT JOIN `cnoa_abutment_datasheet` AS `ds` ON db.id = ds.sid \r\n\t\t\t\tWHERE ds.id = ".$_obfuscate_TGTDzydTYAÿÿ;
        $_obfuscate_dYGaubEIH28ÿ = $CNOA_DB->get_one( $_obfuscate_3y0Y );
        if ( $_obfuscate_dYGaubEIH28ÿ['dbType'] == "MYSQL" )
        {
            ( );
            $_obfuscate_sx8ÿ = new mysql2( );
            $_obfuscate_mvD3kolYxoIx = $_obfuscate_sx8ÿ->connect( $_obfuscate_dYGaubEIH28ÿ['host'], $_obfuscate_dYGaubEIH28ÿ['dbname'], $_obfuscate_dYGaubEIH28ÿ['user'], $_obfuscate_dYGaubEIH28ÿ['password'], $_obfuscate_dYGaubEIH28ÿ['chart'] );
        }
        else if ( $_obfuscate_dYGaubEIH28ÿ['dbType'] == "SQL SERVER" )
        {
            $_obfuscate_dYGaubEIH28ÿ['chart'] = $_obfuscate_dYGaubEIH28ÿ['chart'] == "UTF8" ? "utf-8" : $_obfuscate_dYGaubEIH28ÿ['chart'];
            ( $_obfuscate_dYGaubEIH28ÿ['chart'] );
            $_obfuscate_sx8ÿ = new mssql2( );
            $_obfuscate_mvD3kolYxoIx = $_obfuscate_sx8ÿ->connect( $_obfuscate_dYGaubEIH28ÿ['host'], $_obfuscate_dYGaubEIH28ÿ['user'], $_obfuscate_dYGaubEIH28ÿ['password'], $_obfuscate_dYGaubEIH28ÿ['dbname'] );
        }
        else
        {
            msg::callback( FALSE, "æ— æ³•è¿žæŽ¥æ•°æ®åº“" );
        }
        if ( $_obfuscate_mvD3kolYxoIx !== TRUE )
        {
            msg::callback( FALSE, "ä¸èƒ½è¿žæŽ¥æ•°æ®åº“" );
        }
        $_obfuscate_6RYLWQÿÿ = array( );
        foreach ( $this->bindFields as $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_VgKtFegÿ] = getpar( $_POST, $_obfuscate_VgKtFegÿ );
        }
        foreach ( $this->detailData[$this->detailFields['id']] as $_obfuscate_VgKtFegÿ )
        {
            foreach ( $this->detailFields['field'] as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_6RYLWQÿÿ[$_obfuscate_6Aÿÿ] = $_obfuscate_VgKtFegÿ[$_obfuscate_5wÿÿ];
            }
            if ( $_obfuscate_H2KBlenOIgÿÿ == "UPDATE" )
            {
                $_obfuscate_IRFhnYwÿ = $this->changeSQL( $_obfuscate_dKpM2M15b0Iÿ, $_obfuscate_6RYLWQÿÿ );
                $_obfuscate_sx8ÿ->db_update( $_obfuscate_6RYLWQÿÿ, $_obfuscate_dYGaubEIH28ÿ['sheet'], $_obfuscate_IRFhnYwÿ );
            }
            else if ( $_obfuscate_H2KBlenOIgÿÿ == "INSERT" )
            {
                $_obfuscate_sx8ÿ->db_insert( $_obfuscate_6RYLWQÿÿ, $_obfuscate_dYGaubEIH28ÿ['sheet'] );
            }
        }
        $CNOA_DB->select_db( CNOA_DB_NAME );
    }

    private function _saveFormFieldInfo( $_obfuscate_uGltphXQjCRWoAÿÿ = "", $_obfuscate_0cocFTVhmhKt8lwÿ = "", $_obfuscate_TlvKhtsoOQÿÿ = 0 )
    {
        global $CNOA_DB;
        $_obfuscate_JQJwE4USnB0ÿ = array( );
        $_obfuscate_V7H2J5ahgÿÿ = array( );
        $_obfuscate_u_DK_o5AB8le = array( );
        $_obfuscate_BqBV6WSz3wel0ZDw = array( );
        $_obfuscate_FYo_0_BVp9xjgDsÿ = array( );
        $_obfuscate_piwqe2DnH9mIPU0P = array( );
        foreach ( $GLOBALS['_POST'] as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( ereg( "wf_field_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_field_", "", $_obfuscate_5wÿÿ );
                if ( !is_array( $_obfuscate_uGltphXQjCRWoAÿÿ ) )
                {
                    $_obfuscate_JQJwE4USnB0ÿ['normal'][$_obfuscate_gfGsQGKrGgÿÿ] = $_obfuscate_6Aÿÿ;
                }
                else if ( in_array( $_obfuscate_gfGsQGKrGgÿÿ, $_obfuscate_uGltphXQjCRWoAÿÿ ) )
                {
                    $_obfuscate_JQJwE4USnB0ÿ['normal'][$_obfuscate_gfGsQGKrGgÿÿ] = $_obfuscate_6Aÿÿ;
                }
            }
            else if ( ereg( "wf_fieldJ_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_fieldJ_", "", $_obfuscate_5wÿÿ );
                $_obfuscate_JQJwE4USnB0ÿ['normal'][$_obfuscate_gfGsQGKrGgÿÿ] = $_obfuscate_6Aÿÿ;
            }
            else if ( ereg( "wf_fieldC_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_fieldC_", "", $_obfuscate_5wÿÿ );
                $_obfuscate_SeV31Qÿÿ = explode( "_", $_obfuscate_gfGsQGKrGgÿÿ );
                $_obfuscate_V7H2J5ahgÿÿ[$_obfuscate_SeV31Qÿÿ[0]][$_obfuscate_SeV31Qÿÿ[1]] = $_obfuscate_6Aÿÿ;
            }
            else if ( ereg( "wf_detail_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_detail_", "", $_obfuscate_5wÿÿ );
                $_obfuscate_SeV31Qÿÿ = explode( "_", $_obfuscate_gfGsQGKrGgÿÿ );
                if ( !is_array( $_obfuscate_0cocFTVhmhKt8lwÿ ) )
                {
                    $_obfuscate_JQJwE4USnB0ÿ['detail'][$_obfuscate_SeV31Qÿÿ[0]][$_obfuscate_SeV31Qÿÿ[1]] = $_obfuscate_6Aÿÿ;
                }
                else if ( in_array( $_obfuscate_SeV31Qÿÿ[1], $_obfuscate_0cocFTVhmhKt8lwÿ ) )
                {
                    $_obfuscate_JQJwE4USnB0ÿ['detail'][$_obfuscate_SeV31Qÿÿ[0]][$_obfuscate_SeV31Qÿÿ[1]] = $_obfuscate_6Aÿÿ;
                }
            }
            else if ( ereg( "wf_detailJ_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_detailJ_", "", $_obfuscate_5wÿÿ );
                $_obfuscate_SeV31Qÿÿ = explode( "_", $_obfuscate_gfGsQGKrGgÿÿ );
                $_obfuscate_JQJwE4USnB0ÿ['detail'][$_obfuscate_SeV31Qÿÿ[0]][$_obfuscate_SeV31Qÿÿ[1]] = $_obfuscate_6Aÿÿ;
            }
            else if ( ereg( "wf_detailC_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_detailC_", "", $_obfuscate_5wÿÿ );
                $_obfuscate_SeV31Qÿÿ = explode( "_", $_obfuscate_gfGsQGKrGgÿÿ );
                $_obfuscate_u_DK_o5AB8le[$_obfuscate_SeV31Qÿÿ[0]][$_obfuscate_SeV31Qÿÿ[1]][] = $_obfuscate_6Aÿÿ;
            }
            else if ( ereg( "wf_detailbid_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_detailbid_", "", $_obfuscate_5wÿÿ );
                $_obfuscate_SeV31Qÿÿ = explode( "_", $_obfuscate_gfGsQGKrGgÿÿ );
                $_obfuscate_BqBV6WSz3wel0ZDw[intval( $_obfuscate_SeV31Qÿÿ[1] )][intval( $_obfuscate_SeV31Qÿÿ[0] )] = $_obfuscate_6Aÿÿ;
            }
            else if ( ereg( "wf_detailmax_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_detailmax_", "", $_obfuscate_5wÿÿ );
                $_obfuscate_SeV31Qÿÿ = explode( "_", $_obfuscate_gfGsQGKrGgÿÿ );
                $_obfuscate_piwqe2DnH9mIPU0P[$_obfuscate_SeV31Qÿÿ[1]."_".$_obfuscate_SeV31Qÿÿ[0]] = $_obfuscate_6Aÿÿ;
            }
            else if ( ereg( "detailid_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_FYo_0_BVp9xjgDsÿ[] = intval( str_replace( "detailid_", "", $_obfuscate_5wÿÿ ) );
            }
            else if ( ereg( "wf_fieldS_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_fieldS_", "", $_obfuscate_5wÿÿ );
                if ( !empty( $_obfuscate_6Aÿÿ ) )
                {
                    $_obfuscate_6Aÿÿ = explode( ";", $_obfuscate_6Aÿÿ );
                    $_obfuscate_kCxvBLni6Qÿÿ = array( );
                    foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_EGUÿ )
                    {
                        if ( !empty( $_obfuscate_EGUÿ ) )
                        {
                            list( $_obfuscate_Vwty, $_obfuscate_TAxu ) = explode( ":", $_obfuscate_EGUÿ );
                            $_obfuscate_kCxvBLni6Qÿÿ[$_obfuscate_Vwty] = $_obfuscate_TAxu;
                        }
                    }
                    $_obfuscate_JQJwE4USnB0ÿ['normal'][$_obfuscate_gfGsQGKrGgÿÿ] = json_encode( $_obfuscate_kCxvBLni6Qÿÿ );
                }
            }
            else if ( ereg( "wf_fieldpic_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_fieldpic_", "", $_obfuscate_5wÿÿ );
                if ( ereg( "editpic", $_obfuscate_6Aÿÿ ) )
                {
                    $_obfuscate_JTe7jJ4eGW8ÿ = str_replace( "{$GLOBALS['URL_FILE']}/editpic/", "", $_obfuscate_6Aÿÿ );
                    $_obfuscate_GsJ20flAQÿÿ = $GLOBALS['URL_FILE']."/common/wf/form/".getpar( $_POST, "flowId", 0 );
                    @mkdirs( CNOA_PATH."/".$_obfuscate_GsJ20flAQÿÿ );
                    @rename( CNOA_PATH."/".$_obfuscate_6Aÿÿ, CNOA_PATH."/".$_obfuscate_GsJ20flAQÿÿ."/".$_obfuscate_JTe7jJ4eGW8ÿ );
                    $_obfuscate_JQJwE4USnB0ÿ['normal'][$_obfuscate_gfGsQGKrGgÿÿ] = $_obfuscate_GsJ20flAQÿÿ."/".$_obfuscate_JTe7jJ4eGW8ÿ;
                }
                else
                {
                    $_obfuscate_JQJwE4USnB0ÿ['normal'][$_obfuscate_gfGsQGKrGgÿÿ] = $_obfuscate_6Aÿÿ;
                }
            }
            else if ( preg_match( "/^wf_budgetDept_(\\d+)\$/", $_obfuscate_5wÿÿ, $_obfuscate_8UmnTppRcAÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = $_obfuscate_8UmnTppRcAÿÿ[1];
                $_obfuscate_6Aÿÿ = intval( $_obfuscate_6Aÿÿ );
                $_obfuscate_3y0Y = "INSERT INTO ".tname( "budget_tmp_tabel" )."(`uFlowId`, `fieldId`, `deptId`) ".( "VALUES(".$_obfuscate_TlvKhtsoOQÿÿ.", {$_obfuscate_gfGsQGKrGgÿÿ}, {$_obfuscate_6Aÿÿ}) " ).( "ON DUPLICATE KEY UPDATE `deptId`=".$_obfuscate_6Aÿÿ );
                $CNOA_DB->query( $_obfuscate_3y0Y );
            }
            else if ( preg_match( "/^wf_budgetProj_(\\d+)\$/", $_obfuscate_5wÿÿ, $_obfuscate_8UmnTppRcAÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = $_obfuscate_8UmnTppRcAÿÿ[1];
                $_obfuscate_6Aÿÿ = intval( $_obfuscate_6Aÿÿ );
                $_obfuscate_3y0Y = "INSERT INTO ".tname( "budget_tmp_tabel_proj" )."(`uFlowId`, `fieldId`, `projId`) ".( "VALUES(".$_obfuscate_TlvKhtsoOQÿÿ.", {$_obfuscate_gfGsQGKrGgÿÿ}, {$_obfuscate_6Aÿÿ}) " ).( "ON DUPLICATE KEY UPDATE `projId`=".$_obfuscate_6Aÿÿ );
                $CNOA_DB->query( $_obfuscate_3y0Y );
            }
        }
        foreach ( $_obfuscate_V7H2J5ahgÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_JQJwE4USnB0ÿ['normal'][$_obfuscate_5wÿÿ] = json_encode( $_obfuscate_6Aÿÿ );
        }
        foreach ( $_obfuscate_u_DK_o5AB8le as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_ClAÿ => $_obfuscate_bRQÿ )
            {
                $_obfuscate_JQJwE4USnB0ÿ['detail'][$_obfuscate_5wÿÿ][$_obfuscate_ClAÿ] = json_encode( $_obfuscate_bRQÿ );
            }
        }
        $_obfuscate_dGoPOiQ2Iw5a = array( );
        if ( !empty( $_obfuscate_FYo_0_BVp9xjgDsÿ ) )
        {
            $_obfuscate_7Hp0w_lfFt4ÿ = $CNOA_DB->db_select( array( "id", "fid" ), "wf_s_field_detail", "WHERE `fid` IN (".implode( ",", $_obfuscate_FYo_0_BVp9xjgDsÿ ).") " );
            if ( !is_array( $_obfuscate_7Hp0w_lfFt4ÿ ) )
            {
                $_obfuscate_7Hp0w_lfFt4ÿ = array( );
            }
            foreach ( $_obfuscate_7Hp0w_lfFt4ÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_6Aÿÿ['id']] = $_obfuscate_6Aÿÿ['fid'];
            }
        }
        return array(
            $_obfuscate_JQJwE4USnB0ÿ,
            $_obfuscate_dGoPOiQ2Iw5a,
            $_obfuscate_BqBV6WSz3wel0ZDw,
            $_obfuscate_piwqe2DnH9mIPU0P
        );
    }

    private function changeSQL( $_obfuscate_dKpM2M15b0Iÿ, &$_obfuscate_6RYLWQÿÿ )
    {
        $_obfuscate_VGqEVoP33gÿÿ = "/\\{(\\w+)\\}/";
        preg_match_all( $_obfuscate_VGqEVoP33gÿÿ, $_obfuscate_dKpM2M15b0Iÿ, $_obfuscate_Jrp1 );
        foreach ( $_obfuscate_Jrp1[1] as $_obfuscate_VgKtFegÿ )
        {
            if ( empty( $_obfuscate_6RYLWQÿÿ[$_obfuscate_VgKtFegÿ] ) )
            {
                return FALSE;
            }
            $_obfuscate_dKpM2M15b0Iÿ = str_replace( "{".$_obfuscate_VgKtFegÿ."}", "'".$_obfuscate_6RYLWQÿÿ[$_obfuscate_VgKtFegÿ]."'", $_obfuscate_dKpM2M15b0Iÿ );
            unset( $_obfuscate_6RYLWQÿÿ[$_obfuscate_VgKtFegÿ] );
        }
        return $_obfuscate_dKpM2M15b0Iÿ;
    }

}

?>
