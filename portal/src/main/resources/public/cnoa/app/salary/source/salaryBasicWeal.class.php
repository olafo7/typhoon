<?php
//decode by qq2859470

class salaryBasicWeal extends salaryBasic
{

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            exit( );
        case "getWealJsonData" :
            $this->_getWealJsonData( );
            exit( );
        case "submitWeal" :
            $this->_submitWeal( );
            exit( );
        case "delWeal" :
            $this->_delWeal( );
        }
        exit( );
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/basic/weal.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    private function _getWealJsonData( )
    {
        global $CNOA_DB;
        $result = $CNOA_DB->db_select( "*", $this->table_basic_weal );
        if ( !is_array( $result ) )
        {
            $result = array( );
        }
        $data = array( );
        foreach ( $result as $value )
        {
            if ( $value['type'] == self::SALARY_BASIC_DEPT )
            {
                $dids = explode( ",", $value['scope'] );
                $value['scope'] = app::loadapp( "main", "struct" )->api_getNamesByIds( $dids );
                $value['scope'] = implode( ",", $value['scope'] );
            }
            else if ( $value['type'] == self::SALARY_BASIC_USER )
            {
                $uids = explode( ",", $value['scope'] );
                $value['scope'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $uids );
                $value['scope'] = implode( ",", $value['scope'] );
            }
            $data[] = $value;
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
    }

    private function _submitWeal( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $type = ( integer )getpar( $_POST, "selectType" );
        if ( $type === 0 )
        {
            msg::callback( FALSE, "数据错误，请重新操作" );
        }
        if ( $type == self::SALARY_BASIC_DEPT )
        {
            $scope = getpar( $_POST, "dids" );
        }
        else if ( $type == self::SALARY_BASIC_USER )
        {
            $scope = getpar( $_POST, "uids" );
        }
        $title = getpar( $_POST, "title" );
        $content = getpar( $_POST, "content" );
        $tuid = $CNOA_SESSION->get( "UID" );
        $data['tuid'] = $tuid;
        $data['title'] = $title;
        $data['content'] = $content;
        $data['type'] = $type;
        $data['scope'] = $scope;
        if ( $CNOA_DB->db_insert( $data, $this->table_basic_weal ) )
        {
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 5000, substr( $title, 0, 80 ), "福利" );
            msg::callback( TRUE, "操作成功" );
        }
        msg::callback( FALSE, "操作成功" );
    }

    private function _delWeal( )
    {
        $wids = getpar( $_POST, "wids" );
        if ( isinformat( $wids ) )
        {
            global $CNOA_DB;
            if ( $CNOA_DB->db_delete( $this->table_basic_weal, "WHERE wid IN(".$wids.")" ) )
            {
                app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 5000, "", "福利" );
                msg::callback( TRUE, "删除成功" );
            }
        }
        msg::callback( FALSE, "删除失败" );
    }

}

?>
