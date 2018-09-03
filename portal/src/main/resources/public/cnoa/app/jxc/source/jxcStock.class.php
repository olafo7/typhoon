<?php
//decode by qq2859470

include_once( CNOA_PATH."/app/jxc/inc/jxcCommon.class.php" );
class jxcStock extends jxcCommon
{

    public function actionRuku( )
    {
        app::loadapp( "jxc", "stockRuku" )->run( );
    }

    public function actionChuku( )
    {
        app::loadapp( "jxc", "stockChuku" )->run( );
    }

    public function actionReport( )
    {
        app::loadapp( "jxc", "stockReport" )->run( );
    }

    public function actionXghlist( )
    {
        app::loadapp( "jxc", "stockXghlist" )->run( );
    }

    public function api_getGoodsQuantity( $storageId = 0 )
    {
        $quantity = array( );
        if ( empty( $storageId ) )
        {
            $where = "WHERE 1";
        }
        else
        {
            $where = "WHERE `storageId`=".$storageId;
        }
        $sql = "SELECT `goodsId`, sum(`quantity`) AS `quantity` FROM ".tname( $this->table_goods_detail ).( " ".$where." GROUP BY `storageId`, `goodsId`" );
        global $CNOA_DB;
        $result = $CNOA_DB->query( $sql );
        while ( $row = $CNOA_DB->get_array( $result ) )
        {
            $quantity[$row['goodsId']] += intval( $row['quantity'] );
        }
        return $quantity;
    }

}

?>
