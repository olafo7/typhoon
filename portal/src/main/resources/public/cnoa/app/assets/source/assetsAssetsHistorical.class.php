<?php
//decode by qq2859470

class assetsAssetsHistorical extends assetsAssets
{

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadpage( );
            exit( );
        case "getHistoricalList" :
            $this->_gethistoricallist( );
            exit( );
        case "cnumstore" :
            $this->_cnumstore( );
        }
        exit( );
    }

    private function _loadpage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/assets/historical.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    private function _gethistoricallist( )
    {
        global $CNOA_DB;
        $searchOperator = getpar( $_POST, "searchOperator" );
        $searchOperation = getpar( $_POST, "searchOperation" );
        $searchNumber = getpar( $_POST, "searchNumber" );
        $searchAssetsName = getpar( $_POST, "searchAssetsName" );
        $searchReturnStatus = getpar( $_POST, "searchReturnStatus" );
        $searchAssetsNum = getpar( $_POST, "searchAssetsNum" );
        $searchsturnover = getpar( $_POST, "searchsturnover" );
        $searcheturnover = getpar( $_POST, "searcheturnover" );
        $start = getpar( $_POST, "start", 0 );
        $row = getpagesize( "assets_assets_historical" );
        $sturnover = strtotime( $searchsturnover." 00:00:00" );
        $eturnover = strtotime( $searcheturnover." 23:59:59" );
        $WHERE[] = "WHERE 1";
        if ( !empty( $searchOperator ) )
        {
        }
        if ( !empty( $searchOperation ) )
        {
        }
        if ( !empty( $searchAssetsNum ) )
        {
        }
        if ( !empty( $searchReturnStatus ) )
        {
        }
        if ( !empty( $searchNumber ) )
        {
        }
        if ( !empty( $searchAssetsName ) )
        {
        }
        if ( empty( $searchsturnover ) && $searcheturnover )
        {
            msg::callback( FALSE, "开始时间未设置" );
        }
        if ( empty( $searcheturnover ) )
        {
            do
            {
                if ( !$searchsturnover )
                {
                    break;
                }
                else
                {
                    msg::callback( FALSE, "结束时间未设置" );
                }
            }
            if ( !$searchsturnover && !$searcheturnover )
            {
                break;
            }
            if ( if ( !$$eturnover < $sturnover )
            {
                msg::callback( FALSE, "开始时间不能比结束时间大" );
            }
        } while ( 0 );
        } while$WHERE = implode( " AND ", $WHERE );
        $result = $CNOA_DB->db_select( "*", $this->table_historical, "{$WHERE} ORDER BY `turnover` DESC LIMIT {$start},{$row}" );
        if ( !is_array( $result ) )
        {
            $result = array( );
        }
        $data = $temp = array( );
        foreach ( $result as $value )
        {
            switch ( $value['operation'] )
            {
            case "1" :
                $data['operation'] = "<span class=\"cnoa_color_green\">".lang( "add" )."</span>";
                break;
            case "2" :
                $data['operation'] = "<span class=\"cnoa_color_red\">".lang( "secondChange" )."</span>";
                break;
            case "3" :
                $data['operation'] = "<span class=\"cnoa_color_blue\">".lang( "del" )."</span>";
                break;
            case "4" :
                $data['operation'] = "<span style=\"color: #000000\">".lang( "restitution" )."</span>";
                break;
            default :
                $data['operation'] = "";
            }
            $data['turnover'] = date( "Y-m-d H:i:s", $value['turnover'] );
            $data['operator'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $value['operator'] );
            $data['jiediaonumber'] = $value['cnum']."-".$value['jiediaonumber'];
            $data['borrowdate'] = date( "Y-m-d H:i:s", $value['borrowdate'] );
            $data['acceptdpt'] = !empty( $value['acceptdpt'] ) ? app::loadapp( "main", "struct" )->api_getNameById( $value['acceptdpt'] ) : "";
            $data['receiver'] = !empty( $value['receiver'] ) ? app::loadapp( "main", "user" )->api_getUserTruenameByUid( $value['receiver'] ) : "";
            $data['returndate'] = !empty( $value['returndate'] ) ? date( "Y-m-d H:i:s", $value['returndate'] ) : " ";
            $data['status'] = $value['status'];
            $data['remark'] = $value['remark'];
            $data['assetsName'] = $value['assetsName'];
            $temp[] = $data;
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $temp;
        $ds->total = $CNOA_DB->db_getcount( $this->table_historical, $WHERE );
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _cnumstore( )
    {
        global $CNOA_DB;
        $data = $CNOA_DB->db_select( array( "id", "cnum", "assetsName" ), $this->table_manage, "WHERE `type`='1' ORDER BY `id` DESC" );
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
        exit( );
    }

}

?>
