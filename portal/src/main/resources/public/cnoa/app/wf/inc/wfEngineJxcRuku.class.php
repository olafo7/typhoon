<?php

class wfEngineJxcRuku extends wfEngineJxc
{

    protected $code = "jxcRuku";
    protected $modelId = 3;
    protected $model_table = "jxc_stock_ruku";
    protected $table_detail = "jxc_stock_goods_detail";

    const DETAIL_STYLE = 1;

    public function __construct( )
    {
        $FN_-2147483647( );
    }

    protected function init( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        ( "jxc" );
        $_obfuscate_LU8? = new customField( );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_6RYLWQ?? = $this->getCustomComboStore( );
        $_obfuscate_mPAjEGLn = app::loadapp( "jxc", "base" )->api_getStorage( );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_rpiErMTsOg?? = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A?? )
        {
            $_obfuscate_1jUa = $_obfuscate_6A??['manager'];
            $_obfuscate_O6QLLac? = explode( ",", $_obfuscate_1jUa );
            if ( in_array( $_obfuscate_7Ri3, $_obfuscate_O6QLLac? ) )
            {
                $_obfuscate_rpiErMTsOg??[] = array(
                    "storageid" => $_obfuscate_6A??['id'],
                    "storagename" => $_obfuscate_6A??['storagename']
                );
            }
        }
        $_obfuscate_3y0Y = "SELECT `b`.`widgetId`,`b`.`fieldId`, `f`.`valuefield`, `f`.`displayfield` FROM ".tname( $this->table_fix_field )." AS `f` LEFT JOIN ".tname( $this->table_bind_fields )." AS `b` ON `f`.`fieldId`=`b`.`fieldId` ".( "WHERE `f`.`mid`=".$this->modelId." AND `b`.`type`=1 AND `displayfield` IN ('rukuTypeName', 'storagename')" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_mEai2Zy66H7hgbNygKFj = 0;
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_BeDD62zcK1U? = $_obfuscate_gkt['widgetId'];
            $_obfuscate_8jhldA9Y9A?? = $_obfuscate_gkt['fieldId'];
            if ( $_obfuscate_gkt['displayfield'] == "storagename" )
            {
                $_obfuscate_6RYLWQ??[$_obfuscate_BeDD62zcK1U?]['data'] = $_obfuscate_rpiErMTsOg??;
            }
            else
            {
                $_obfuscate_6RYLWQ??[$_obfuscate_BeDD62zcK1U?]['data'] = $_obfuscate_LU8?->getComboItem( $this->modelId, $_obfuscate_8jhldA9Y9A??, 1 );
            }
            $_obfuscate_6RYLWQ??[$_obfuscate_BeDD62zcK1U?]['display'] = $_obfuscate_gkt['displayfield'];
            $_obfuscate_6RYLWQ??[$_obfuscate_BeDD62zcK1U?]['value'] = $_obfuscate_gkt['valuefield'];
        }
        return $_obfuscate_6RYLWQ??;
    }

    public function setData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ??, $_obfuscate_b79bo3UyH9A? )
    {
        $_obfuscate_3tiDsnM? = "z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_b79bo3UyH9A?;
        ( $_obfuscate_TlvKhtsoOQ?? );
        $_obfuscate_e53ODz04JQ?? = new wfCache( );
        $_obfuscate_6AxGjhiu62pGaX6 = $_obfuscate_e53ODz04JQ??->getDetailFields( );
        $_obfuscate_8q4EcPJGgrXqPw?? = array( );
        if ( is_array( $_obfuscate_6AxGjhiu62pGaX6 ) )
        {
            foreach ( $_obfuscate_6AxGjhiu62pGaX6 as $_obfuscate_6A?? )
            {
                switch ( $_obfuscate_6A??['binfield'] )
                {
                case "sum" :
                    $_obfuscate_8q4EcPJGgrXqPw??['sum'] = $_obfuscate_6A??['id'];
                    break;
                case "quantity" :
                    $_obfuscate_8q4EcPJGgrXqPw??['quantity'] = $_obfuscate_6A??['id'];
                    break;
                case "price" :
                    $_obfuscate_8q4EcPJGgrXqPw??['price'] = $_obfuscate_6A??['id'];
                }
            }
        }
        global $CNOA_DB;
        $_obfuscate_RJlC_vRd_Apfw?? = $CNOA_DB->db_getone( array( "id", "storageid" ), $this->model_table, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ?? );
        $_obfuscate_jvlg = intval( $_obfuscate_RJlC_vRd_Apfw??['id'] );
        $_obfuscate_NwefuWkoWhUJ = intval( $_obfuscate_RJlC_vRd_Apfw??['storageid'] );
        $_obfuscate_fMfssw?? = $CNOA_DB->db_select( "*", $_obfuscate_3tiDsnM?, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ??."'" );
        if ( !is_array( $_obfuscate_fMfssw?? ) )
        {
            $_obfuscate_fMfssw?? = array( );
        }
        $_obfuscate_e8yPxDokWw? = $_obfuscate_6RYLWQ?? = array( );
        foreach ( $_obfuscate_fMfssw?? as $_obfuscate_gkt )
        {
            $_obfuscate_6RYLWQ?? = array( );
            $_obfuscate_6RYLWQ??['type'] = self::DETAIL_STYLE;
            $_obfuscate_6RYLWQ??['rid'] = $_obfuscate_jvlg;
            $_obfuscate_6RYLWQ??['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $_obfuscate_6RYLWQ??['storageId'] = $_obfuscate_NwefuWkoWhUJ;
            $_obfuscate_6RYLWQ??['goodsId'] = $_obfuscate_gkt['bindid'];
            $_obfuscate_6RYLWQ??['uFlowId'] = $_obfuscate_TlvKhtsoOQ??;
            $_obfuscate_6RYLWQ??['quantity'] = $_obfuscate_gkt["D_".$_obfuscate_8q4EcPJGgrXqPw??['quantity']];
            $_obfuscate_6RYLWQ??['price'] = $_obfuscate_gkt["D_".$_obfuscate_8q4EcPJGgrXqPw??['price']];
            $_obfuscate_6RYLWQ??['sum'] = floatval( $_obfuscate_6RYLWQ??['quantity'] ) * floatval( $_obfuscate_6RYLWQ??['price'] );
            if ( $_obfuscate_gkt['bindid'] != "0" )
            {
                $_obfuscate_e8yPxDokWw?[] = "(".implode( ",", $_obfuscate_6RYLWQ?? ).")";
            }
        }
        if ( !empty( $_obfuscate_e8yPxDokWw? ) )
        {
            $_obfuscate_3y0Y = "INSERT INTO ".tname( $this->table_detail )."(`type`, `rid`, `posttime`, `storageid`, `goodsId`,`uFlowId`, `quantity`, `price`, `sum`) VALUES ".implode( ",", $_obfuscate_e8yPxDokWw? );
            $CNOA_DB->query( $_obfuscate_3y0Y );
        }
    }

}

?>
