<?php

class wfEngineSaleRecord extends wfEngineInterface implements wfEngineInterface
{

    protected $code = "saleRecord";
    private $table_record = "user_customers_record";
    private $table_customers = "user_customers";
    private $table_linkman = "user_customers_linkman";
    private $table_share_update_record = "user_customers_share_update_record";

    public function __construct( )
    {
        $FN_-2147483647( );
    }

    protected function init( )
    {
        global $CNOA_DB;
        $_obfuscate_6RYLWQ?? = $this->checkIdea;
        return $_obfuscate_6RYLWQ??;
    }

    public function runWithoutBindingStep( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        if ( !$this->interfaceCheckBind( ) )
        {
            return;
        }
        $this->makeData4Table( );
        $_obfuscate_W8kbIJeYImJLnA?? = getpar( $_POST, "checkfield" );
        if ( $_obfuscate_W8kbIJeYImJLnA?? == "??????" )
        {
            $_obfuscate_6RYLWQ?? = array( );
            $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId" );
            $_obfuscate_Zg1gaCk? = getpar( $_POST, "cname" );
            $_obfuscate_09VzsUg? = getpar( $_POST, "lname" );
            $_obfuscate_KBWh = getpar( $_POST, "cid" );
            $_obfuscate_wFji = getpar( $_POST, "lid" );
            $_obfuscate_2BZypkQ? = getpar( $_POST, "gname", "" ) == "??????" ? "gd" : "sv";
            if ( $_obfuscate_2BZypkQ? == "gd" )
            {
                $_obfuscate_3Przhe2p = getpar( $_POST, "gdname", "" );
                $_obfuscate_CNeCILs? = getpar( $_POST, "price", 0 );
                $_obfuscate_L0Hguw79ZhZP = getpar( $_POST, "dealprice", 0 );
                $_obfuscate_Ybai = getpar( $_POST, "num", 0 );
                $_obfuscate_iJlhanNi = getpar( $_POST, "amount", 0 );
                $_obfuscate_J9H8bu0? = getpar( $_POST, "about", "", 1 );
            }
            else if ( $_obfuscate_2BZypkQ? == "sv" )
            {
                $_obfuscate_3Przhe2p = getpar( $_POST, "proname", "" );
                $_obfuscate_U2UlS2JM = getpar( $_POST, "serpro" );
                $_obfuscate_J9H8bu0? = getpar( $_POST, "serpro" );
                $_obfuscate_iJlhanNi = getpar( $_POST, "proprice", 0 );
            }
            $_obfuscate_O6ZGVA?? = getpar( $_POST, "date" );
            $_obfuscate_MCvOwNvf4933jpJT = getpar( $_POST, "fahuocontent", "" );
            $_obfuscate_kSPXkGIDvpn7Rsk? = getpar( $_POST, "fahuonotice", 0 );
            $_obfuscate_meLHS0jJnKis = getpar( $_POST, "fahuotype" );
            $_obfuscate_D0EqHBNGfjMR = getpar( $_POST, "transport", "" );
            $_obfuscate_le67WvSdpHcYDt1kA?? = getpar( $_POST, "fahuocheckuid" );
            $_obfuscate_8CpDPPa = getpar( $_POST, "attach", "[]" );
            $_obfuscate_rScOVaMlB40? = intval( getpar( $_POST, "checkuid", 0 ) );
            $_obfuscate_7Ri3 = getpar( $_POST, "uid", "" );
            if ( $_obfuscate_meLHS0jJnKis == "?б└?ии??" )
            {
                $_obfuscate_6RYLWQ??['fahuotype'] = 1;
            }
            else if ( $_obfuscate_meLHS0jJnKis == "??oии??" )
            {
                $_obfuscate_6RYLWQ??['fahuotype'] = 2;
            }
            else if ( $_obfuscate_meLHS0jJnKis == "???иж??" )
            {
                $_obfuscate_6RYLWQ??['fahuotype'] = 3;
            }
            else if ( $_obfuscate_meLHS0jJnKis == "???ии??" )
            {
                $_obfuscate_6RYLWQ??['fahuotype'] = 4;
            }
            $_obfuscate_6RYLWQ??['fahuonotes'] = getpar( $_POST, "fahuonotes", "" );
            $_obfuscate_O6ZGVA?? = explode( "-", $_obfuscate_O6ZGVA?? );
            $_obfuscate_6RYLWQ??['date'] = mktime( 0, 0, 0, $_obfuscate_O6ZGVA??[1], $_obfuscate_O6ZGVA??[2], $_obfuscate_O6ZGVA??[0] );
            $_obfuscate_kSPXkGIDvpn7Rsk? = explode( "-", $_obfuscate_kSPXkGIDvpn7Rsk? );
            $_obfuscate_6RYLWQ??['fahuonotice'] = mktime( 0, 0, 0, $_obfuscate_kSPXkGIDvpn7Rsk?[1], $_obfuscate_kSPXkGIDvpn7Rsk?[2], $_obfuscate_kSPXkGIDvpn7Rsk?[0] );
            $_obfuscate_lWk5hHye = $CNOA_DB->db_getone( array( "posttime", "uid" ), "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE `uFlowId`='".$this->uFlowId."'" );
            if ( $_obfuscate_lWk5hHye['posttime'] )
            {
                $_obfuscate_PqzkWMU3WN4? = $_obfuscate_lWk5hHye['posttime'];
            }
            else
            {
                $_obfuscate_PqzkWMU3WN4? = time( );
            }
            if ( !$_obfuscate_7Ri3 )
            {
                $_obfuscate_7Ri3 = $_obfuscate_lWk5hHye['uid'];
            }
            $_obfuscate__Wi6396IheA? = app::loadapp( "main", "user" )->api_getUserNameByUids( $_obfuscate_7Ri3 );
            if ( !$_obfuscate_iJlhanNi )
            {
                $_obfuscate_iJlhanNi = ( integer )$_obfuscate_CNeCILs? * ( double )$_obfuscate_L0Hguw79ZhZP * ( integer )$_obfuscate_Ybai;
            }
            $_obfuscate_6RYLWQ??['addUid'] = intval( $_obfuscate_7Ri3 );
            $_obfuscate_6RYLWQ??['uid'] = intval( $CNOA_DB->db_getfield( "uid", $this->table_customers, "WHERE `cid`=".$_obfuscate_KBWh ) );
            $_obfuscate_6RYLWQ??['cid'] = intval( $_obfuscate_KBWh );
            $_obfuscate_6RYLWQ??['lid'] = intval( $_obfuscate_wFji );
            $_obfuscate_6RYLWQ??['gname'] = $_obfuscate_2BZypkQ?;
            $_obfuscate_6RYLWQ??['gdname'] = $_obfuscate_3Przhe2p;
            $_obfuscate_6RYLWQ??['price'] = $_obfuscate_CNeCILs?;
            $_obfuscate_6RYLWQ??['dealprice'] = $_obfuscate_L0Hguw79ZhZP;
            $_obfuscate_6RYLWQ??['num'] = intval( $_obfuscate_Ybai );
            $_obfuscate_6RYLWQ??['amount'] = $_obfuscate_iJlhanNi;
            $_obfuscate_6RYLWQ??['transport'] = $_obfuscate_D0EqHBNGfjMR;
            $_obfuscate_6RYLWQ??['fahuocontent'] = $_obfuscate_MCvOwNvf4933jpJT;
            $_obfuscate_6RYLWQ??['fahuonotes'] = $_obfuscate_MCvOwNvf4933jpJT;
            $_obfuscate_6RYLWQ??['posttime'] = $_obfuscate_PqzkWMU3WN4?;
            $_obfuscate_6RYLWQ??['status'] = "yes";
            $_obfuscate_6RYLWQ??['attach'] = $_obfuscate_8CpDPPa;
            $_obfuscate_6RYLWQ??['about'] = $_obfuscate_J9H8bu0?;
            $_obfuscate_6RYLWQ??['checkuid'] = $_obfuscate_rScOVaMlB40?;
            $_obfuscate_6RYLWQ??['checkdate'] = time( );
            $_obfuscate_BOv37ISEbxxb04w9 = $this->nextStepType;
            if ( $this->nextStepUid == 0 && $_POST['checkfield'] == "" && $_obfuscate_BOv37ISEbxxb04w9 != 4 )
            {
                msg::callback( FALSE, lang( "noBindingField" ) );
            }
            if ( $this->nextStepUid == 0 && $_obfuscate_BOv37ISEbxxb04w9 != 4 )
            {
                $_obfuscate_8joQHkshQw?? = $CNOA_DB->db_getone( array( "name" ), $this->table_linkman, "WHERE `lid`=".$_obfuscate_6RYLWQ??['lid'] );
                $CNOA_DB->db_insert( array(
                    "cid" => $_obfuscate_KBWh,
                    "position" => "иж?????ии?бу???",
                    "where" => "",
                    "original" => "",
                    "change" => "?бд?????o?ии???3??oo".$_obfuscate_8joQHkshQw??['name']."???иж?????ии?бу???",
                    "time" => $_obfuscate_6RYLWQ??['posttime'],
                    "uid" => $_obfuscate_7Ri3,
                    "ownID" => $_obfuscate_6RYLWQ??['uid']
                ), $this->table_share_update_record );
                $CNOA_DB->db_insert( $_obfuscate_6RYLWQ??, $this->table_record );
            }
        }
    }

}

?>
