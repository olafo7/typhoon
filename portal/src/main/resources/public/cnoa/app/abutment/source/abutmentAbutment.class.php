<?php
//decode by qq2859470

class abutmentAbutment extends model
{

    public function __construct( )
    {
    }

    public function actionSheet( )
    {
        app::loadapp( "abutment", "abutmentSheet" )->run( );
    }

    public function actionDatabase( )
    {
        app::loadapp( "abutment", "abutmentDatabase" )->run( );
    }

    public function actionFlow( )
    {
        app::loadapp( "abutment", "abutmentFlow" )->run( );
    }

    public function actionSqlselector( )
    {
        app::loadapp( "abutment", "abutmentSqlselector" )->run( );
    }

    public function actionSqldetail( )
    {
        app::loadapp( "abutment", "abutmentSqldetail" )->run( );
    }

    public function connectDb( $_obfuscate_SeV31Qÿÿ )
    {
        global $CNOA_XXTEA;
        if ( !is_array( $_obfuscate_SeV31Qÿÿ ) )
        {
            global $CNOA_DB;
            $_obfuscate_dYGaubEIH28ÿ = $CNOA_DB->db_getone( "*", "abutment_database", "WHERE `id`=".$_obfuscate_SeV31Qÿÿ );
        }
        else
        {
            $_obfuscate_dYGaubEIH28ÿ =& $_obfuscate_SeV31Qÿÿ;
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
        if ( $_obfuscate_mvD3kolYxoIx !== TRUE )
        {
            msg::callback( FALSE, "ä¸è½è¿æ¥æ°æ®åº" );
        }
        return $_obfuscate_sx8ÿ;
    }

    public function api_getDbFieldName( $_obfuscate_0W8ÿ, $_obfuscate_CGvieHoÿ )
    {
        global $CNOA_DB;
        $_obfuscate_dYGaubEIH28ÿ = $CNOA_DB->db_getone( "*", "abutment_database", "WHERE `id`=".$_obfuscate_0W8ÿ );
        $_obfuscate_sx8ÿ = $this->connectDb( $_obfuscate_dYGaubEIH28ÿ );
        return $_obfuscate_sx8ÿ->db_getFieldsName( $_obfuscate_CGvieHoÿ );
    }

    protected function testConnect( )
    {
        $_obfuscate_D9yo3Aÿÿ = getpar( $_POST, "host" );
        $_obfuscate_m2Kuwwÿÿ = getpar( $_POST, "user" );
        $_obfuscate_LyySC3IF7Iÿ = getpar( $_POST, "password" );
        $_obfuscate_1mEwE08ÿ = getpar( $_POST, "chart" );
        $_obfuscate_KLbKkj9 = getpar( $_POST, "dbname" );
        $_obfuscate_wiJctCGp = getpar( $_POST, "dbType" );
        if ( $_obfuscate_wiJctCGp == "MYSQL" )
        {
            ( );
            $_obfuscate_sx8ÿ = new mysql2( );
            $_obfuscate_mvD3kolYxoIx = $_obfuscate_sx8ÿ->connect( $_obfuscate_D9yo3Aÿÿ, $_obfuscate_KLbKkj9, $_obfuscate_m2Kuwwÿÿ, $_obfuscate_LyySC3IF7Iÿ, $_obfuscate_1mEwE08ÿ );
        }
        else if ( $_obfuscate_wiJctCGp == "SQL SERVER" )
        {
            $_obfuscate_1mEwE08ÿ = $_obfuscate_1mEwE08ÿ == "UTF8" ? "utf-8" : $_obfuscate_dYGaubEIH28ÿ['chart'];
            ( $_obfuscate_1mEwE08ÿ );
            $_obfuscate_sx8ÿ = new mssql2( );
            $_obfuscate_mvD3kolYxoIx = $_obfuscate_sx8ÿ->connect( $_obfuscate_D9yo3Aÿÿ, $_obfuscate_m2Kuwwÿÿ, $_obfuscate_LyySC3IF7Iÿ, $_obfuscate_KLbKkj9 );
        }
        if ( $_obfuscate_mvD3kolYxoIx !== TRUE )
        {
            msg::callback( FALSE, "è¿æ¥å¤±è´¥ï¼è¯·æ£æ¥ä¿¡æ¯æ¯å¦æé" );
        }
        else
        {
            msg::callback( TRUE, "è¿æ¥æå" );
        }
    }

    protected function testSQL( )
    {
        global $CNOA_SESSION;
        $_obfuscate_oDAS4YopcE7Hswÿÿ = getpar( $_POST, "databaseId" );
        $_obfuscate_sx8ÿ = $this->connectDb( $_obfuscate_oDAS4YopcE7Hswÿÿ );
        $_obfuscate_3y0Y = $this->changeSql( $_POST['selectorSQL'], $_POST['id'] );
        if ( $_obfuscate_sx8ÿ->query( $_obfuscate_3y0Y ) )
        {
            msg::callback( TRUE, "SQLæ­£ç¡®" );
        }
        else
        {
            msg::callback( FALSE, "éè¯¯çSQLï¼è¯·æ£æ¥å­æ®µåãè¡¨åæ¯å¦æ­£ç¡®ï¼å­ç¬¦ä¸²æ¯å¦å·²å å¼å·" );
        }
    }

    public function api_getDatabaseNameById( $_obfuscate_0W8ÿ )
    {
        if ( empty( $_obfuscate_0W8ÿ ) )
        {
            return "";
        }
        global $CNOA_DB;
        return $CNOA_DB->db_getfield( "name", "abutment_database", "WHERE `id`=".$_obfuscate_0W8ÿ );
    }

    public function encode_json( $_obfuscate_R2_b )
    {
        return urldecode( json_encode( $this->url_encode( $_obfuscate_R2_b ) ) );
    }

    public function url_encode( $_obfuscate_R2_b )
    {
        if ( is_array( $_obfuscate_R2_b ) )
        {
            foreach ( $_obfuscate_R2_b as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
            {
                $_obfuscate_R2_b[urlencode( $_obfuscate_Vwty )] = $this->url_encode( $_obfuscate_VgKtFegÿ );
            }
            return $_obfuscate_R2_b;
        }
        $_obfuscate_R2_b = urlencode( $_obfuscate_R2_b );
        return $_obfuscate_R2_b;
    }

    protected function changeSql( $_obfuscate_3y0Y, $_obfuscate_0W8ÿ = NULL )
    {
        global $CNOA_SESSION;
        $_obfuscate_mV9HBLYÿ = getpar( $_POST, "start", 0 );
        $_obfuscate_xvYeh9Iÿ = getpagesize( "abutment_abutment_limit" );
        $_obfuscate_3y0Y = str_replace( "fro^m", "from", $_obfuscate_3y0Y );
        $_obfuscate_NYI5Ywÿÿ = array( "{uid}", "{username}", "{truename}", "{deptId}", "{deptName}", "{start}", "{limit}" );
        $_obfuscate_cIxBiQÿÿ = array(
            $CNOA_SESSION->get( "UID" ),
            $CNOA_SESSION->get( "USERNAME" ),
            $CNOA_SESSION->get( "TRUENAME" ),
            $CNOA_SESSION->get( "DID" ),
            $CNOA_SESSION->get( "DEPTNAME" ),
            $_obfuscate_mV9HBLYÿ,
            $_obfuscate_xvYeh9Iÿ
        );
        $_obfuscate_3y0Y = str_replace( $_obfuscate_NYI5Ywÿÿ, $_obfuscate_cIxBiQÿÿ, $_obfuscate_3y0Y );
        if ( !is_null( $_obfuscate_0W8ÿ ) )
        {
            $_obfuscate_3y0Y = $this->getSearchSql( $_obfuscate_3y0Y, $_obfuscate_0W8ÿ );
        }
        return $_obfuscate_3y0Y;
    }

    protected function getSearchSql( $_obfuscate_3y0Y, $_obfuscate_b79bo3UyH9Aÿ )
    {
        global $CNOA_DB;
        $_obfuscate_dcwitxb = $_obfuscate_tD3t16Or4rN5nQÿÿ = array( );
        foreach ( $GLOBALS['_POST'] as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
        {
            if ( FALSE !== strpos( $_obfuscate_Vwty, "search|" ) )
            {
                $_obfuscate_0W8ÿ = str_replace( "search|", "", $_obfuscate_Vwty );
                $_obfuscate_dcwitxb[$_obfuscate_0W8ÿ] = $_obfuscate_VgKtFegÿ;
            }
        }
        $_obfuscate_SeV31Qÿÿ = $CNOA_DB->db_select( array( "id" ), "abutment_search", "WHERE did = ".$_obfuscate_b79bo3UyH9Aÿ );
        if ( !is_array( $_obfuscate_SeV31Qÿÿ ) )
        {
            $_obfuscate_SeV31Qÿÿ = array( );
        }
        $_obfuscate_K16DLBjnEvIhAÿÿ = array( );
        foreach ( $_obfuscate_SeV31Qÿÿ as $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_K16DLBjnEvIhAÿÿ[] = $_obfuscate_VgKtFegÿ['id'];
        }
        $_obfuscate_1Yv3R3ZVwQ86Iatsk3Dlmwÿÿ = $CNOA_DB->db_select( array( "id", "fid", "name" ), "abutment_search_fields", "WHERE fid in(".implode( ",", $_obfuscate_K16DLBjnEvIhAÿÿ ).")" );
        if ( !is_array( $_obfuscate_1Yv3R3ZVwQ86Iatsk3Dlmwÿÿ ) )
        {
            $_obfuscate_1Yv3R3ZVwQ86Iatsk3Dlmwÿÿ = array( );
        }
    default :
        switch ( $_obfuscate_msf0z_SCGPqPqgÿÿ )
        {
            foreach ( $_obfuscate_1Yv3R3ZVwQ86Iatsk3Dlmwÿÿ as $_obfuscate_VgKtFegÿ )
            {
                if ( !empty( $_obfuscate_dcwitxb[$_obfuscate_VgKtFegÿ['id']] ) )
                {
                    $_obfuscate_msf0z_SCGPqPqgÿÿ = getpar( $_POST, "searchType|".$_obfuscate_VgKtFegÿ['id'], 0 );
                case "0" :
                    $_obfuscate_tD3t16Or4rN5nQÿÿ[$_obfuscate_VgKtFegÿ['fid']] .= " AND ".$_obfuscate_VgKtFegÿ['name']." LIKE '%".$_obfuscate_dcwitxb[$_obfuscate_VgKtFegÿ['id']]."%'";
                    continue;
                case "1" :
                    $_obfuscate_tD3t16Or4rN5nQÿÿ[$_obfuscate_VgKtFegÿ['fid']] .= " AND ".$_obfuscate_VgKtFegÿ['name']." = '".$_obfuscate_dcwitxb[$_obfuscate_VgKtFegÿ['id']]."'";
                    continue;
                case "2" :
                    $_obfuscate_tD3t16Or4rN5nQÿÿ[$_obfuscate_VgKtFegÿ['fid']] .= " AND ".$_obfuscate_VgKtFegÿ['name']." > '".$_obfuscate_dcwitxb[$_obfuscate_VgKtFegÿ['id']]."'";
                }
            }
            continue;
        case "3" :
            $_obfuscate_tD3t16Or4rN5nQÿÿ[$_obfuscate_VgKtFegÿ['fid']] .= " AND ".$_obfuscate_VgKtFegÿ['name']." < '".$_obfuscate_dcwitxb[$_obfuscate_VgKtFegÿ['id']]."'";
        }
        foreach ( $_obfuscate_K16DLBjnEvIhAÿÿ as $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_3y0Y = str_replace( "{search".$_obfuscate_VgKtFegÿ."}", $_obfuscate_tD3t16Or4rN5nQÿÿ[$_obfuscate_VgKtFegÿ], $_obfuscate_3y0Y );
        }
        return $_obfuscate_3y0Y;
    }

}

?>
