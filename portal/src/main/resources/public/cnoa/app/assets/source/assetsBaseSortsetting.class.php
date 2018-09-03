<?php
//decode by qq2859470

class assetsBaseSortsetting extends assetsBase
{

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "getJsonData" :
            $this->_getJsonData( );
            break;
        case "loadForm" :
            $this->_loadForm( );
            break;
        case "getSortList" :
            $this->_getSortList( );
            break;
        case "submit" :
            $this->_submit( );
            break;
        case "loadFname" :
            $this->_loadFname( );
            break;
        case "deleteSort" :
            $this->_deleteSort( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/base/sortsetting.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        $start = ( integer )getpar( $_POST, "start", 0 );
        $row = 15;
        $dblist = $CNOA_DB->db_select( array( "id", "name", "fid" ), $this->table_sort, "LIMIT ".$start.", {$row}" );
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = $CNOA_DB->db_getcount( $this->table_sort );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _loadForm( )
    {
        global $CNOA_DB;
        $count = $CNOA_DB->db_getcount( $this->table_sort );
        echo json_encode( $count );
        exit( );
    }

    private function _getSortList( )
    {
        global $CNOA_DB;
        $list = $CNOA_DB->db_select( array( "id", "name", "fid", "order", "about" ), $this->table_sort, "WHERE 1 ORDER BY `order` ASC" );
        $type = getpar( $_GET, "type" );
        $data = $temp = array( );
        if ( $type == "tree" )
        {
            foreach ( $list as $key => $value )
            {
                $temp['id'] = $value['id'];
                $temp['text'] = $value['name'];
                $temp['fid'] = $value['fid'];
                $temp['order'] = $value['order'];
                $temp['about'] = $value['about'];
                $temp['leaf'] = 1;
                $temp['iconCls'] = $v['id'] == 1 ? "icon-tree-root-cnoa" : "icon-style-page-key";
                $data[$value['id']] = $temp;
            }
            $dblist = $this->getTree( $data );
            $dblist = $this->_makeArray( $dblist );
            echo json_encode( $dblist );
            exit( );
        }
        if ( $type == "combo" )
        {
            foreach ( $list as $key => $value )
            {
                $temp['id'] = $value['id'];
                $temp['text'] = $value['name'];
                $temp['fid'] = $value['fid'];
                $temp['order'] = $value['order'];
                $temp['about'] = $value['about'];
                $temp['leaf'] = 1;
                $temp['checked'] = FALSE;
                $temp['iconCls'] = $v['id'] == 1 ? "icon-tree-root-cnoa" : "icon-style-page-key";
                $data[$value['id']] = $temp;
            }
            $dblist = $this->getTree( $data );
            $dblist = $this->_makeArray( $dblist );
            echo json_encode( $dblist );
        }
        exit( );
    }

    private function getTree( $items )
    {
        foreach ( $items as $k => $item )
        {
            $items[$item['fid']]['children'][] =& $items[$item['id']];
        }
        if ( isset( $items[0]['children'] ) )
        {
            return $items[0]['children'];
        }
        return array( );
    }

    private function _makeArray( $arr )
    {
        foreach ( $arr as $k => $v )
        {
            if ( is_array( $arr[$k]['children'] ) )
            {
                $arr[$k]['leaf'] = FALSE;
                $this->_makeArray( $arr[$k]['children'] );
            }
        }
        return $arr;
    }

    private function _submit( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", "" );
        $data = array( );
        $data['name'] = getpar( $_POST, "name" );
        $data['fid'] = getpar( $_POST, "fid", 0 );
        $data['order'] = getpar( $_POST, "order" );
        $data['about'] = getpar( $_POST, "about" );
        if ( empty( $id ) )
        {
            $result = $CNOA_DB->db_insert( $data, $this->table_sort );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 6002, "", "分类设置" );
        }
        else
        {
            $result = $CNOA_DB->db_update( $data, $this->table_sort, "WHERE id=".$id );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 6002, "", "分类设置" );
        }
        $response = lang( "successopt" );
        msg::callback( TRUE, $response );
    }

    private function _loadFname( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", "" );
        if ( $id )
        {
            $sql = "select `name` as `fname` from ".tname( $this->table_sort )." WHERE id=".$id;
            $query = $CNOA_DB->query( $sql );
            $info = $CNOA_DB->get_array( $query );
            ( );
            $dataStore = new dataStore( );
            $dataStore->data = $info;
            echo $dataStore->makeJsonData( );
            exit( );
        }
    }

    private function _deleteSort( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id" );
        if ( !empty( $id ) )
        {
            $list = $CNOA_DB->db_select( array( "typeName" ), $this->table_manage, "WHERE `typeName`=".$id );
            if ( !empty( $list ) )
            {
                msg::callback( FALSE, lang( "stopToDel" ) );
            }
            $count = $CNOA_DB->db_getcount( $this->table_sort, "WHERE `fid`=".$id );
            if ( $count )
            {
                msg::callback( FALSE, "请先删除子集" );
            }
            $CNOA_DB->db_delete( $this->table_sort, " WHERE id=".$id );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 6002, "", "分类设置" );
            msg::callback( TRUE, lang( "successopt" ) );
        }
        exit( );
    }

}

?>
