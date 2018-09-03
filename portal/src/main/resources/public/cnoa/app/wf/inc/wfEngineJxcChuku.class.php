<?php

class wfEngineJxcChuku extends wfEngineJxc
{

    protected $code = "jxcChuku";
    protected $modelId = 4;
    protected $model_table = "jxc_stock_Chuku";
    protected $table_detail = "jxc_stock_goods_detail";

    const DETAIL_STYLE = 2;

    public function __construct( )
    {
        $FN_-2147483647( );
    }

    protected function init( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_6RYLWQÿÿ = array( );
        ( "jxc" );
        $_obfuscate_LU8ÿ = new customField( );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_6RYLWQÿÿ = $this->getCustomComboStore( );
        $_obfuscate_mPAjEGLn = app::loadapp( "jxc", "base" )->api_getStorage( );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_rpiErMTsOgÿÿ = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_1jUa = $_obfuscate_6Aÿÿ['manager'];
            $_obfuscate_O6QLLacÿ = explode( ",", $_obfuscate_1jUa );
            if ( in_array( $_obfuscate_7Ri3, $_obfuscate_O6QLLacÿ ) )
            {
                $_obfuscate_rpiErMTsOgÿÿ[] = array(
                    "storageid" => $_obfuscate_6Aÿÿ['id'],
                    "storagename" => $_obfuscate_6Aÿÿ['storagename']
                );
            }
        }
        $_obfuscate_3y0Y = "SELECT `b`.`widgetId`,`b`.`fieldId`, `f`.`valuefield`, `f`.`displayfield` FROM ".tname( $this->table_fix_field )." AS `f` LEFT JOIN ".tname( $this->table_bind_fields )." AS `b` ON `f`.`fieldId`=`b`.`fieldId` ".( "WHERE `f`.`mid`=".$this->modelId." AND `b`.`type`=1 AND `displayfield` IN ('ChukuTypeName', 'storagename')" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_mEai2Zy66H7hgbNygKFj = 0;
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_BeDD62zcK1Uÿ = $_obfuscate_gkt['widgetId'];
            $_obfuscate_8jhldA9Y9Aÿÿ = $_obfuscate_gkt['fieldId'];
            if ( $_obfuscate_gkt['displayfield'] == "storagename" )
            {
                $_obfuscate_6RYLWQÿÿ[$_obfuscate_BeDD62zcK1Uÿ]['data'] = $_obfuscate_rpiErMTsOgÿÿ;
            }
            else
            {
                $_obfuscate_6RYLWQÿÿ[$_obfuscate_BeDD62zcK1Uÿ]['data'] = $_obfuscate_LU8ÿ->getComboItem( $this->modelId, $_obfuscate_8jhldA9Y9Aÿÿ, 1 );
            }
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_BeDD62zcK1Uÿ]['display'] = $_obfuscate_gkt['displayfield'];
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_BeDD62zcK1Uÿ]['value'] = $_obfuscate_gkt['valuefield'];
        }
        return $_obfuscate_6RYLWQÿÿ;
    }

    public function setData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_b79bo3UyH9Aÿ )
    {
        $_obfuscate_3tiDsnMÿ = "z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_b79bo3UyH9Aÿ;
        ( $_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_e53ODz04JQÿÿ = new wfCache( );
        $_obfuscate_6AxGjhiu62pGaX6 = $_obfuscate_e53ODz04JQÿÿ->getDetailFields( );
        $_obfuscate_8q4EcPJGgrXqPwÿÿ = array( );
        if ( is_array( $_obfuscate_6AxGjhiu62pGaX6 ) )
        {
            foreach ( $_obfuscate_6AxGjhiu62pGaX6 as $_obfuscate_6Aÿÿ )
            {
                switch ( $_obfuscate_6Aÿÿ['binfield'] )
                {
                case "sum" :
                    $_obfuscate_8q4EcPJGgrXqPwÿÿ['sum'] = $_obfuscate_6Aÿÿ['id'];
                    break;
                case "quantity" :
                    $_obfuscate_8q4EcPJGgrXqPwÿÿ['quantity'] = $_obfuscate_6Aÿÿ['id'];
                    break;
                case "price" :
                    $_obfuscate_8q4EcPJGgrXqPwÿÿ['price'] = $_obfuscate_6Aÿÿ['id'];
                }
            }
        }
        global $CNOA_DB;
        $_obfuscate_RJlC_vRd_Apfwÿÿ = $CNOA_DB->db_getone( array( "id", "storageid" ), $this->model_table, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_jvlg = intval( $_obfuscate_RJlC_vRd_Apfwÿÿ['id'] );
        $_obfuscate_NwefuWkoWhUJ = intval( $_obfuscate_RJlC_vRd_Apfwÿÿ['storageid'] );
        $_obfuscate_fMfsswÿÿ = $CNOA_DB->db_select( "*", $_obfuscate_3tiDsnMÿ, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        if ( !is_array( $_obfuscate_fMfsswÿÿ ) )
        {
            $_obfuscate_fMfsswÿÿ = array( );
        }
        $_obfuscate_e8yPxDokWwÿ = $_obfuscate_6RYLWQÿÿ = array( );
        foreach ( $_obfuscate_fMfsswÿÿ as $_obfuscate_gkt )
        {
            $_obfuscate_6RYLWQÿÿ = array( );
            $_obfuscate_6RYLWQÿÿ['type'] = self::DETAIL_STYLE;
            $_obfuscate_6RYLWQÿÿ['rid'] = $_obfuscate_jvlg;
            $_obfuscate_6RYLWQÿÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $_obfuscate_6RYLWQÿÿ['storageId'] = $_obfuscate_NwefuWkoWhUJ;
            $_obfuscate_6RYLWQÿÿ['goodsId'] = $_obfuscate_gkt['bindid'];
            $_obfuscate_6RYLWQÿÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
            $_obfuscate_6RYLWQÿÿ['quantity'] = 0 - floatval( $_obfuscate_gkt["D_".$_obfuscate_8q4EcPJGgrXqPwÿÿ['quantity']] );
            $_obfuscate_6RYLWQÿÿ['price'] = floatval( $_obfuscate_gkt["D_".$_obfuscate_8q4EcPJGgrXqPwÿÿ['price']] );
            $_obfuscate_6RYLWQÿÿ['sum'] = floatval( $_obfuscate_6RYLWQÿÿ['quantity'] ) * floatval( $_obfuscate_6RYLWQÿÿ['price'] );
            if ( $_obfuscate_gkt['bindid'] != "0" )
            {
                $_obfuscate_e8yPxDokWwÿ[] = "(".implode( ",", $_obfuscate_6RYLWQÿÿ ).")";
            }
        }
        if ( !empty( $_obfuscate_e8yPxDokWwÿ ) )
        {
            $_obfuscate_3y0Y = "INSERT INTO ".tname( $this->table_detail )."(`type`, `rid`, `posttime`, `storageid`, `goodsId`,`uFlowId`, `quantity`, `price`, `sum`) VALUES ".implode( ",", $_obfuscate_e8yPxDokWwÿ );
            $CNOA_DB->query( $_obfuscate_3y0Y );
        }
    }

}

?>
