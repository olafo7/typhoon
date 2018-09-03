<?php
//decode by qq2859470

class userAttendanceSetting extends userAttendance
{

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            exit( );
        case "loadSetting" :
            $this->_loadSetting( );
            exit( );
        case "saveSetting" :
            $this->_saveSetting( );
        }
        exit( );
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/attendance/setting.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    private function _loadSetting( )
    {
        global $CNOA_DB;
        $row = $CNOA_DB->db_getone( "*", $this->table_setting_approver, "WHERE id=1" );
        $truename = app::loadapp( "main", "user" )->api_getUserTruenameByUid( explode( ",", $row['approverUid'] ) );
        $data = array( );
        $data['uname'] = empty( $truename ) ? "" : implode( ",", $truename );
        $data['uids'] = $row['approverUid'];
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
    }

    private function _saveSetting( )
    {
        $uids = getpar( $_POST, "uids" );
        global $CNOA_DB;
        $CNOA_DB->db_update( array(
            "approverUid" => $uids
        ), $this->table_setting_approver, "WHERE id=1" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

}

?>
