<?php
//decode by qq2859470

class userTaskSetting extends userTask
{

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            exit( );
        case "approvalSetting" :
            $this->_approvalSetting( );
            exit( );
        case "getStatus" :
            $this->_getSetStatus( );
        }
        exit( );
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/task/setting.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    private function _approvalSetting( )
    {
        global $CNOA_DB;
        $status = getpar( $_POST, "status", "" );
        $ids = getpar( $_POST, "signInIDs", "" );
        $count = $CNOA_DB->db_getcount( $this->table_approval );
        if ( $count )
        {
            $CNOA_DB->db_update( array(
                "status" => $status,
                "uids" => $ids
            ), $this->table_approval, "WHERE 1" );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 40, "", "成功" );
            msg::callback( TRUE, lang( "successopt" ) );
        }
        else
        {
            $CNOA_DB->db_insert( array(
                "status" => $status,
                "uids" => $ids
            ), $this->table_approval );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 40, "", "成功" );
            msg::callback( TRUE, lang( "successopt" ) );
        }
        msg::callback( FALSE, lang( "optFail" ) );
    }

    private function _getSetStatus( )
    {
        global $CNOA_DB;
        $data = $this->_getStatus( FALSE );
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
    }

}

?>
