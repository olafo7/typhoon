<?php
//decode by qq2859470

class jxcBaseOutputtype extends jxcBase
{

    const FIXTYPE = 1;
    const CUSTOMTYPE = 2;

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            exit( );
        case "getOutputtypeList" :
            $this->_getOutputtypeList( );
            exit( );
        case "submitOutputtype" :
            $this->_submitOutputtype( );
            exit( );
        case "delOutputtype" :
            $this->_delOutputtype( );
        }
        exit( );
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/base/outputtype.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    private function _getOutputtypeList( )
    {
        global $CNOA_DB;
        $model = array( 3 => "入库业务", 4 => "出库业务" );
        $data = $CNOA_DB->db_select( "*", $this->table_select_item, "WHERE (`mid`=3 AND `fieldId`=3) OR (`mid`=4 AND `fieldId`=13)" );
        foreach ( $data as $key => $value )
        {
            if ( $value['mid'] == 4 )
            {
                $data[$key]['outputtype'] = "出库类型";
            }
            else if ( $value['mid'] == 3 )
            {
                $data[$key]['outputtype'] = "入库类型";
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
    }

    private function _submitOutputtype( )
    {
        global $CNOA_DB;
        $name = getpar( $_POST, "name" );
        if ( empty( $name ) )
        {
            msg::callback( FALSE, lang( "nameNoEmpty" ) );
        }
        $mid = getpar( $_POST, "mid" );
        if ( empty( $mid ) )
        {
            msg::callback( FALSE, lang( "belongFunNotEmpty" ) );
        }
        $data['name'] = $name;
        $data['mid'] = $mid;
        if ( $mid == 3 )
        {
            $data['fieldId'] = 3;
        }
        else if ( $mid == 4 )
        {
            $data['fieldId'] = 13;
        }
        $data['type'] = 1;
        $id = intval( getpar( $_POST, "id" ) );
        if ( empty( $id ) )
        {
            $CNOA_DB->db_insert( $data, $this->table_select_item );
            msg::callback( TRUE, "增加成功" );
        }
        else
        {
            $CNOA_DB->db_update( $data, $this->table_select_item, "WHERE `id`=".$id );
            msg::callback( TRUE, "修改成功" );
        }
    }

    private function _delOutputtype( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", 0 );
        $CNOA_DB->db_delete( $this->table_select_item, "WHERE `id`=".$id );
        msg::callback( TRUE, "删除成功" );
    }

}

?>
