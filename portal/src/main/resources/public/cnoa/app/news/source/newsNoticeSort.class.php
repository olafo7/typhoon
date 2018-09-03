<?php
//decode by qq2859470

class newsNoticeSort extends newsNotice
{

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            exit( );
        case "addSort" :
            $this->_addSort( );
            exit( );
        case "getNoticeSort" :
            $this->_getNoticeSort( );
            exit( );
        case "updateSort" :
            $this->_updateSort( );
            exit( );
        case "delSort" :
            $this->_delSort( );
        }
        exit( );
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/notice/sort.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    private function _addSort( )
    {
        global $CNOA_DB;
        $name = getpar( $_POST, "name" );
        $count = $CNOA_DB->db_getcount( $this->table_notice_sort, "WHERE `name`='".$name."'" );
        if ( 0 < $count )
        {
            msg::callback( FALSE, "名称不能相同，请重新操作" );
        }
        $data = array( );
        $data['name'] = $name;
        if ( $CNOA_DB->db_insert( $data, $this->table_notice_sort ) )
        {
            msg::callback( TRUE, "添加成功" );
        }
    }

    private function _getNoticeSort( )
    {
        global $CNOA_DB;
        $result = $CNOA_DB->db_select( "*", $this->table_notice_sort, "ORDER BY `sid` DESC" );
        if ( !is_array( $result ) )
        {
            $result = array( );
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $result;
        echo $ds->makeJsonData( );
    }

    private function _updateSort( )
    {
        global $CNOA_DB;
        $sid = ( integer )getpar( $_POST, "sid" );
        if ( $sid === 0 )
        {
            msg::callback( FALSE, "数据错误，请重新操作" );
        }
        $field = getpar( $_POST, "field" );
        $value = getpar( $_POST, "value" );
        $sql = "UPDATE ".tname( $this->table_notice_sort ).( " SET `".$field."`='{$value}' WHERE `sid`={$sid}" );
        $CNOA_DB->query( $sql );
        msg::callback( TRUE, "修改成功" );
    }

    private function _delSort( )
    {
        global $CNOA_DB;
        $ids = getpar( $_POST, "ids" );
        if ( empty( $ids ) )
        {
            msg::callback( FALSE, lang( "delFail" ) );
        }
        if ( isinformat( $ids ) )
        {
            $CNOA_DB->db_delete( $this->table_notice_sort, "WHERE `sid` IN (".$ids.")" );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 108, "", "公告/通知分类" );
            msg::callback( TRUE, lang( "delSuccess" ) );
        }
    }

}

?>
