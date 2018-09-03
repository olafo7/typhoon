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
        foreach ( $this->map as $_obfuscate_Vwty => $_obfuscate_VgKtFeg� )
        {
            if ( !$_obfuscate_VgKtFeg�['open'] )
            {
            }
            else
            {
                $_obfuscate_0W8� = $_obfuscate_VgKtFeg�['check']['id'];
                $_obfuscate_aMwmYI� = isset( $_POST["wf_engine_field_".$_obfuscate_0W8�] ) ? $_POST["wf_engine_field_".$_obfuscate_0W8�] : $_POST["wf_field_".$_obfuscate_0W8�];
                if ( !( $_obfuscate_aMwmYI� != "同意" ) )
                {
                    $this->bindFields = $_obfuscate_VgKtFeg�['field'];
                    if ( $this->isNew == "new" )
                    {
                        $this->makeData4Post( );
                    }
                    else
                    {
                        $this->makeData4Table( );
                    }
                    if ( !empty( $_obfuscate_VgKtFeg�['detail'] ) )
                    {
                        $this->makeData4Detail( );
                        $this->detailFields = $_obfuscate_VgKtFeg�['detail'];
                        $this->writeDetailData( $_obfuscate_Vwty, $_obfuscate_VgKtFeg�['outType'], $_obfuscate_VgKtFeg�['whereSQL'] );
                    }
                    else
                    {
                        $this->writeData( $_obfuscate_Vwty, $_obfuscate_VgKtFeg�['outType'], $_obfuscate_VgKtFeg�['whereSQL'] );
                    }
                }
            }
        }
    }

    private function makeData4Post( $_obfuscate_b55OQ�� = array( ) )
    {
        if ( !is_array( $_obfuscate_b55OQ�� ) )
        {
            return FALSE;
        }
        foreach ( $GLOBALS['_POST'] as $_obfuscate_3gn_eQ�� => $_obfuscate_TAxu )
        {
            if ( preg_match( "/^wf_field_(\\d+)/", $_obfuscate_3gn_eQ��, $_obfuscate_0W8� ) )
            {
                $_obfuscate_YIq2A8c� = $this->bindFields[$_obfuscate_0W8�[1]];
                if ( !isset( $_obfuscate_YIq2A8c� ) )
                {
                }
                else if ( !empty( $_obfuscate_b55OQ�� ) || !in_array( $_obfuscate_YIq2A8c�, $_obfuscate_b55OQ�� ) )
                {
                    $_obfuscate_FckHk8RR1IQ� = "wf_engine_field_".$_obfuscate_0W8�[1];
                    $GLOBALS['_POST'][$_obfuscate_YIq2A8c�] = isset( $_POST[$_obfuscate_FckHk8RR1IQ�] ) ? $_POST[$_obfuscate_FckHk8RR1IQ�] : $_obfuscate_TAxu;
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
        foreach ( ( array )$_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( !( stripos( $_obfuscate_5w��, "T_" ) !== 0 ) )
            {
                $_obfuscate_8jhldA9Y9A�� = intval( substr( $_obfuscate_5w��, 2 ) );
                $_obfuscate_YIq2A8c� = $this->bindFields[$_obfuscate_8jhldA9Y9A��];
                if ( !isset( $_obfuscate_YIq2A8c� ) )
                {
                }
                else
                {
                    $GLOBALS['_POST'][$_obfuscate_YIq2A8c�] = in_array( $_obfuscate_YIq2A8c�, $_obfuscate_rFekUqpblT ) ? $_POST["wf_engine_field_".$_obfuscate_8jhldA9Y9A��] : $_obfuscate_6A��;
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
            foreach ( $_obfuscate_lWk5hHye[0]['detail'] as $_obfuscate_gkt => $_obfuscate_eBU_Sjc� )
            {
                foreach ( $_obfuscate_eBU_Sjc� as $_obfuscate_5w�� => $_obfuscate_6A�� )
                {
                    $this->detailData[$_obfuscate_lWk5hHye[1][$_obfuscate_5w��]][$_obfuscate_gkt][$_obfuscate_5w��] = $_obfuscate_6A��;
                }
            }
        }
    }

    private function writeData( $_obfuscate_TGTDzydTYA��, $_obfuscate_H2KBlenOIg��, $_obfuscate_dKpM2M15b0I� )
    {
        global $CNOA_DB;
        global $CNOA_XXTEA;
        $_obfuscate_3y0Y = "SELECT db.host, db.dbname, db.chart, db.dbType, db.user, db.password, ds.sheet \r\n\t\t\t\tFROM `cnoa_abutment_database` AS `db` LEFT JOIN `cnoa_abutment_datasheet` AS `ds` ON db.id = ds.sid \r\n\t\t\t\tWHERE ds.id = ".$_obfuscate_TGTDzydTYA��;
        $_obfuscate_dYGaubEIH28� = $CNOA_DB->get_one( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQ�� = array( );
        foreach ( $this->bindFields as $_obfuscate_VgKtFeg� )
        {
            $_obfuscate_6RYLWQ��[$_obfuscate_VgKtFeg�] = getpar( $_POST, $_obfuscate_VgKtFeg� );
        }
        $_obfuscate_pbOuOg8� = unserialize( $CNOA_XXTEA->decrypt( $_obfuscate_dYGaubEIH28�['password'] ) );
        $_obfuscate_dYGaubEIH28�['password'] = $_obfuscate_pbOuOg8�['PASSWORD'];
        if ( $_obfuscate_dYGaubEIH28�['dbType'] == "MYSQL" )
        {
            ( );
            $_obfuscate_sx8� = new mysql2( );
            $_obfuscate_mvD3kolYxoIx = $_obfuscate_sx8�->connect( $_obfuscate_dYGaubEIH28�['host'], $_obfuscate_dYGaubEIH28�['dbname'], $_obfuscate_dYGaubEIH28�['user'], $_obfuscate_dYGaubEIH28�['password'], $_obfuscate_dYGaubEIH28�['chart'] );
        }
        else if ( $_obfuscate_dYGaubEIH28�['dbType'] == "SQL SERVER" )
        {
            $_obfuscate_dYGaubEIH28�['chart'] = $_obfuscate_dYGaubEIH28�['chart'] == "UTF8" ? "utf-8" : $_obfuscate_dYGaubEIH28�['chart'];
            ( $_obfuscate_dYGaubEIH28�['chart'] );
            $_obfuscate_sx8� = new mssql2( );
            $_obfuscate_mvD3kolYxoIx = $_obfuscate_sx8�->connect( $_obfuscate_dYGaubEIH28�['host'], $_obfuscate_dYGaubEIH28�['user'], $_obfuscate_dYGaubEIH28�['password'], $_obfuscate_dYGaubEIH28�['dbname'] );
        }
        else
        {
            msg::callback( FALSE, "无法连接数据库" );
        }
        if ( $_obfuscate_mvD3kolYxoIx !== TRUE )
        {
            msg::callback( FALSE, "不能连接数据库" );
        }
        if ( $_obfuscate_H2KBlenOIg�� == "UPDATE" )
        {
            $_obfuscate_IRFhnYw� = $this->changeSQL( $_obfuscate_dKpM2M15b0I�, $_obfuscate_6RYLWQ�� );
            $_obfuscate_sx8�->db_update( $_obfuscate_6RYLWQ��, $_obfuscate_dYGaubEIH28�['sheet'], $_obfuscate_IRFhnYw� );
        }
        else if ( $_obfuscate_H2KBlenOIg�� == "INSERT" )
        {
            $_obfuscate_sx8�->db_insert( $_obfuscate_6RYLWQ��, $_obfuscate_dYGaubEIH28�['sheet'] );
        }
        $CNOA_DB->select_db( CNOA_DB_NAME );
    }

    private function writeDetailData( $_obfuscate_TGTDzydTYA��, $_obfuscate_H2KBlenOIg��, $_obfuscate_dKpM2M15b0I� )
    {
        global $CNOA_DB;
        $_obfuscate_3y0Y = "SELECT db.host, db.dbname, db.chart, db.dbType, db.user, db.password, ds.sheet \r\n\t\t\t\tFROM `cnoa_abutment_database` AS `db` LEFT JOIN `cnoa_abutment_datasheet` AS `ds` ON db.id = ds.sid \r\n\t\t\t\tWHERE ds.id = ".$_obfuscate_TGTDzydTYA��;
        $_obfuscate_dYGaubEIH28� = $CNOA_DB->get_one( $_obfuscate_3y0Y );
        if ( $_obfuscate_dYGaubEIH28�['dbType'] == "MYSQL" )
        {
            ( );
            $_obfuscate_sx8� = new mysql2( );
            $_obfuscate_mvD3kolYxoIx = $_obfuscate_sx8�->connect( $_obfuscate_dYGaubEIH28�['host'], $_obfuscate_dYGaubEIH28�['dbname'], $_obfuscate_dYGaubEIH28�['user'], $_obfuscate_dYGaubEIH28�['password'], $_obfuscate_dYGaubEIH28�['chart'] );
        }
        else if ( $_obfuscate_dYGaubEIH28�['dbType'] == "SQL SERVER" )
        {
            $_obfuscate_dYGaubEIH28�['chart'] = $_obfuscate_dYGaubEIH28�['chart'] == "UTF8" ? "utf-8" : $_obfuscate_dYGaubEIH28�['chart'];
            ( $_obfuscate_dYGaubEIH28�['chart'] );
            $_obfuscate_sx8� = new mssql2( );
            $_obfuscate_mvD3kolYxoIx = $_obfuscate_sx8�->connect( $_obfuscate_dYGaubEIH28�['host'], $_obfuscate_dYGaubEIH28�['user'], $_obfuscate_dYGaubEIH28�['password'], $_obfuscate_dYGaubEIH28�['dbname'] );
        }
        else
        {
            msg::callback( FALSE, "无法连接数据库" );
        }
        if ( $_obfuscate_mvD3kolYxoIx !== TRUE )
        {
            msg::callback( FALSE, "不能连接数据库" );
        }
        $_obfuscate_6RYLWQ�� = array( );
        foreach ( $this->bindFields as $_obfuscate_VgKtFeg� )
        {
            $_obfuscate_6RYLWQ��[$_obfuscate_VgKtFeg�] = getpar( $_POST, $_obfuscate_VgKtFeg� );
        }
        foreach ( $this->detailData[$this->detailFields['id']] as $_obfuscate_VgKtFeg� )
        {
            foreach ( $this->detailFields['field'] as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_6RYLWQ��[$_obfuscate_6A��] = $_obfuscate_VgKtFeg�[$_obfuscate_5w��];
            }
            if ( $_obfuscate_H2KBlenOIg�� == "UPDATE" )
            {
                $_obfuscate_IRFhnYw� = $this->changeSQL( $_obfuscate_dKpM2M15b0I�, $_obfuscate_6RYLWQ�� );
                $_obfuscate_sx8�->db_update( $_obfuscate_6RYLWQ��, $_obfuscate_dYGaubEIH28�['sheet'], $_obfuscate_IRFhnYw� );
            }
            else if ( $_obfuscate_H2KBlenOIg�� == "INSERT" )
            {
                $_obfuscate_sx8�->db_insert( $_obfuscate_6RYLWQ��, $_obfuscate_dYGaubEIH28�['sheet'] );
            }
        }
        $CNOA_DB->select_db( CNOA_DB_NAME );
    }

    private function _saveFormFieldInfo( $_obfuscate_uGltphXQjCRWoA�� = "", $_obfuscate_0cocFTVhmhKt8lw� = "", $_obfuscate_TlvKhtsoOQ�� = 0 )
    {
        global $CNOA_DB;
        $_obfuscate_JQJwE4USnB0� = array( );
        $_obfuscate_V7H2J5ahg�� = array( );
        $_obfuscate_u_DK_o5AB8le = array( );
        $_obfuscate_BqBV6WSz3wel0ZDw = array( );
        $_obfuscate_FYo_0_BVp9xjgDs� = array( );
        $_obfuscate_piwqe2DnH9mIPU0P = array( );
        foreach ( $GLOBALS['_POST'] as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( ereg( "wf_field_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_field_", "", $_obfuscate_5w�� );
                if ( !is_array( $_obfuscate_uGltphXQjCRWoA�� ) )
                {
                    $_obfuscate_JQJwE4USnB0�['normal'][$_obfuscate_gfGsQGKrGg��] = $_obfuscate_6A��;
                }
                else if ( in_array( $_obfuscate_gfGsQGKrGg��, $_obfuscate_uGltphXQjCRWoA�� ) )
                {
                    $_obfuscate_JQJwE4USnB0�['normal'][$_obfuscate_gfGsQGKrGg��] = $_obfuscate_6A��;
                }
            }
            else if ( ereg( "wf_fieldJ_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_fieldJ_", "", $_obfuscate_5w�� );
                $_obfuscate_JQJwE4USnB0�['normal'][$_obfuscate_gfGsQGKrGg��] = $_obfuscate_6A��;
            }
            else if ( ereg( "wf_fieldC_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_fieldC_", "", $_obfuscate_5w�� );
                $_obfuscate_SeV31Q�� = explode( "_", $_obfuscate_gfGsQGKrGg�� );
                $_obfuscate_V7H2J5ahg��[$_obfuscate_SeV31Q��[0]][$_obfuscate_SeV31Q��[1]] = $_obfuscate_6A��;
            }
            else if ( ereg( "wf_detail_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_detail_", "", $_obfuscate_5w�� );
                $_obfuscate_SeV31Q�� = explode( "_", $_obfuscate_gfGsQGKrGg�� );
                if ( !is_array( $_obfuscate_0cocFTVhmhKt8lw� ) )
                {
                    $_obfuscate_JQJwE4USnB0�['detail'][$_obfuscate_SeV31Q��[0]][$_obfuscate_SeV31Q��[1]] = $_obfuscate_6A��;
                }
                else if ( in_array( $_obfuscate_SeV31Q��[1], $_obfuscate_0cocFTVhmhKt8lw� ) )
                {
                    $_obfuscate_JQJwE4USnB0�['detail'][$_obfuscate_SeV31Q��[0]][$_obfuscate_SeV31Q��[1]] = $_obfuscate_6A��;
                }
            }
            else if ( ereg( "wf_detailJ_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_detailJ_", "", $_obfuscate_5w�� );
                $_obfuscate_SeV31Q�� = explode( "_", $_obfuscate_gfGsQGKrGg�� );
                $_obfuscate_JQJwE4USnB0�['detail'][$_obfuscate_SeV31Q��[0]][$_obfuscate_SeV31Q��[1]] = $_obfuscate_6A��;
            }
            else if ( ereg( "wf_detailC_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_detailC_", "", $_obfuscate_5w�� );
                $_obfuscate_SeV31Q�� = explode( "_", $_obfuscate_gfGsQGKrGg�� );
                $_obfuscate_u_DK_o5AB8le[$_obfuscate_SeV31Q��[0]][$_obfuscate_SeV31Q��[1]][] = $_obfuscate_6A��;
            }
            else if ( ereg( "wf_detailbid_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_detailbid_", "", $_obfuscate_5w�� );
                $_obfuscate_SeV31Q�� = explode( "_", $_obfuscate_gfGsQGKrGg�� );
                $_obfuscate_BqBV6WSz3wel0ZDw[intval( $_obfuscate_SeV31Q��[1] )][intval( $_obfuscate_SeV31Q��[0] )] = $_obfuscate_6A��;
            }
            else if ( ereg( "wf_detailmax_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_detailmax_", "", $_obfuscate_5w�� );
                $_obfuscate_SeV31Q�� = explode( "_", $_obfuscate_gfGsQGKrGg�� );
                $_obfuscate_piwqe2DnH9mIPU0P[$_obfuscate_SeV31Q��[1]."_".$_obfuscate_SeV31Q��[0]] = $_obfuscate_6A��;
            }
            else if ( ereg( "detailid_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_FYo_0_BVp9xjgDs�[] = intval( str_replace( "detailid_", "", $_obfuscate_5w�� ) );
            }
            else if ( ereg( "wf_fieldS_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_fieldS_", "", $_obfuscate_5w�� );
                if ( !empty( $_obfuscate_6A�� ) )
                {
                    $_obfuscate_6A�� = explode( ";", $_obfuscate_6A�� );
                    $_obfuscate_kCxvBLni6Q�� = array( );
                    foreach ( $_obfuscate_6A�� as $_obfuscate_EGU� )
                    {
                        if ( !empty( $_obfuscate_EGU� ) )
                        {
                            list( $_obfuscate_Vwty, $_obfuscate_TAxu ) = explode( ":", $_obfuscate_EGU� );
                            $_obfuscate_kCxvBLni6Q��[$_obfuscate_Vwty] = $_obfuscate_TAxu;
                        }
                    }
                    $_obfuscate_JQJwE4USnB0�['normal'][$_obfuscate_gfGsQGKrGg��] = json_encode( $_obfuscate_kCxvBLni6Q�� );
                }
            }
            else if ( ereg( "wf_fieldpic_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_fieldpic_", "", $_obfuscate_5w�� );
                if ( ereg( "editpic", $_obfuscate_6A�� ) )
                {
                    $_obfuscate_JTe7jJ4eGW8� = str_replace( "{$GLOBALS['URL_FILE']}/editpic/", "", $_obfuscate_6A�� );
                    $_obfuscate_GsJ20flAQ�� = $GLOBALS['URL_FILE']."/common/wf/form/".getpar( $_POST, "flowId", 0 );
                    @mkdirs( CNOA_PATH."/".$_obfuscate_GsJ20flAQ�� );
                    @rename( CNOA_PATH."/".$_obfuscate_6A��, CNOA_PATH."/".$_obfuscate_GsJ20flAQ��."/".$_obfuscate_JTe7jJ4eGW8� );
                    $_obfuscate_JQJwE4USnB0�['normal'][$_obfuscate_gfGsQGKrGg��] = $_obfuscate_GsJ20flAQ��."/".$_obfuscate_JTe7jJ4eGW8�;
                }
                else
                {
                    $_obfuscate_JQJwE4USnB0�['normal'][$_obfuscate_gfGsQGKrGg��] = $_obfuscate_6A��;
                }
            }
            else if ( preg_match( "/^wf_budgetDept_(\\d+)\$/", $_obfuscate_5w��, $_obfuscate_8UmnTppRcA�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = $_obfuscate_8UmnTppRcA��[1];
                $_obfuscate_6A�� = intval( $_obfuscate_6A�� );
                $_obfuscate_3y0Y = "INSERT INTO ".tname( "budget_tmp_tabel" )."(`uFlowId`, `fieldId`, `deptId`) ".( "VALUES(".$_obfuscate_TlvKhtsoOQ��.", {$_obfuscate_gfGsQGKrGg��}, {$_obfuscate_6A��}) " ).( "ON DUPLICATE KEY UPDATE `deptId`=".$_obfuscate_6A�� );
                $CNOA_DB->query( $_obfuscate_3y0Y );
            }
            else if ( preg_match( "/^wf_budgetProj_(\\d+)\$/", $_obfuscate_5w��, $_obfuscate_8UmnTppRcA�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = $_obfuscate_8UmnTppRcA��[1];
                $_obfuscate_6A�� = intval( $_obfuscate_6A�� );
                $_obfuscate_3y0Y = "INSERT INTO ".tname( "budget_tmp_tabel_proj" )."(`uFlowId`, `fieldId`, `projId`) ".( "VALUES(".$_obfuscate_TlvKhtsoOQ��.", {$_obfuscate_gfGsQGKrGg��}, {$_obfuscate_6A��}) " ).( "ON DUPLICATE KEY UPDATE `projId`=".$_obfuscate_6A�� );
                $CNOA_DB->query( $_obfuscate_3y0Y );
            }
        }
        foreach ( $_obfuscate_V7H2J5ahg�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_JQJwE4USnB0�['normal'][$_obfuscate_5w��] = json_encode( $_obfuscate_6A�� );
        }
        foreach ( $_obfuscate_u_DK_o5AB8le as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            foreach ( $_obfuscate_6A�� as $_obfuscate_ClA� => $_obfuscate_bRQ� )
            {
                $_obfuscate_JQJwE4USnB0�['detail'][$_obfuscate_5w��][$_obfuscate_ClA�] = json_encode( $_obfuscate_bRQ� );
            }
        }
        $_obfuscate_dGoPOiQ2Iw5a = array( );
        if ( !empty( $_obfuscate_FYo_0_BVp9xjgDs� ) )
        {
            $_obfuscate_7Hp0w_lfFt4� = $CNOA_DB->db_select( array( "id", "fid" ), "wf_s_field_detail", "WHERE `fid` IN (".implode( ",", $_obfuscate_FYo_0_BVp9xjgDs� ).") " );
            if ( !is_array( $_obfuscate_7Hp0w_lfFt4� ) )
            {
                $_obfuscate_7Hp0w_lfFt4� = array( );
            }
            foreach ( $_obfuscate_7Hp0w_lfFt4� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_6A��['id']] = $_obfuscate_6A��['fid'];
            }
        }
        return array(
            $_obfuscate_JQJwE4USnB0�,
            $_obfuscate_dGoPOiQ2Iw5a,
            $_obfuscate_BqBV6WSz3wel0ZDw,
            $_obfuscate_piwqe2DnH9mIPU0P
        );
    }

    private function changeSQL( $_obfuscate_dKpM2M15b0I�, &$_obfuscate_6RYLWQ�� )
    {
        $_obfuscate_VGqEVoP33g�� = "/\\{(\\w+)\\}/";
        preg_match_all( $_obfuscate_VGqEVoP33g��, $_obfuscate_dKpM2M15b0I�, $_obfuscate_Jrp1 );
        foreach ( $_obfuscate_Jrp1[1] as $_obfuscate_VgKtFeg� )
        {
            if ( empty( $_obfuscate_6RYLWQ��[$_obfuscate_VgKtFeg�] ) )
            {
                return FALSE;
            }
            $_obfuscate_dKpM2M15b0I� = str_replace( "{".$_obfuscate_VgKtFeg�."}", "'".$_obfuscate_6RYLWQ��[$_obfuscate_VgKtFeg�]."'", $_obfuscate_dKpM2M15b0I� );
            unset( $_obfuscate_6RYLWQ��[$_obfuscate_VgKtFeg�] );
        }
        return $_obfuscate_dKpM2M15b0I�;
    }

}

?>
