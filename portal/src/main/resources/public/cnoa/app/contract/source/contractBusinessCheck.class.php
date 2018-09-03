<?php
//decode by qq2859470

class contractBusinessCheck extends model
{

    private $table = "contract_business_step";
    private $rows = 15;

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "getAllUserListsInPermitDeptTreeAll" :
            $this->_getAllUserListsInPermitDeptTreeAll( );
            break;
        case "getAllJobListForTree" :
            echo app::loadapp( "main", "job" )->api_getAllListForTree( );
            exit( );
        case "getAllUserListsInPermitDeptTree" :
            $this->_getAllUserListsInPermitDeptTreeAll( );
            break;
        case "getJsonData" :
            $this->_getJsonData( );
            break;
        case "getList" :
            app::loadapp( "contract", "getList" )->run( );
            break;
        case "pass" :
            $this->_pass( );
            break;
        case "unpass" :
            $this->_unPass( );
        }
    }

    private function _unPass( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $cuid = $CNOA_SESSION->get( "UID" );
        $cid = getpar( $_POST, "cid" );
        $reason = getpar( $_POST, "reason" );
        $cInfo = app::loadapp( "contract", "business" )->api_getInfo( $cid );
        $step = intval( $cInfo['step'] ) + 1;
        $where = "WHERE `uid`=".$cuid." AND `step`={$step} AND `cid`={$cid}";
        $data = array( );
        $data['status'] = 2;
        $data['reason'] = $reason;
        $CNOA_DB->db_update( $data, $this->table, $where );
        $name = $CNOA_DB->db_getfield( "name", $this->table, $where );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 341, $name.lang( "approvalNotThrough" ), lang( "contractStatus1" ) );
        $notice = $CNOA_DB->db_getone( array( "noticeid_c", "todoid_c" ), $this->table, $where );
        notice::donen( $notice['noticeid_c'] );
        notice::donet( $notice['todoid_c'] );
        unset( $notice );
        notice::add( $cInfo['writePersonID'], lang( "contractMgr" ), lang( "contract" ).( "[".$cInfo['name']."]" ).lang( "approvalNotThrough" ), "index.php?app=contract&func=business&action=manage&task=loadPage&from=manage", 9, 0 );
        $where = "WHERE `id`=".$cid;
        $data = array( );
        $data['step'] = 0;
        $data['checked'] = 2;
        app::loadapp( "contract", "business" )->api_updateBusiness( $data, $where );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _pass( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $cuid = $CNOA_SESSION->get( "UID" );
        $cid = getpar( $_POST, "cid" );
        $cInfo = app::loadapp( "contract", "business" )->api_getInfo( $cid );
        $step = intval( $cInfo['step'] ) + 1;
        $where = "WHERE `uid`=".$cuid." AND `step`={$step} AND `cid`={$cid}";
        $data = array( );
        $data['status'] = 1;
        $CNOA_DB->db_update( $data, $this->table, $where );
        $name = $CNOA_DB->db_getfield( "name", $this->table, $where );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 341, $name." ".lang( "approvalThrough" ), lang( "contractStatus1" ) );
        $notice = $CNOA_DB->db_getone( array( "noticeid_c", "todoid_c" ), $this->table, $where );
        notice::donen( $notice['noticeid_c'] );
        notice::donet( $notice['todoid_c'] );
        unset( $notice );
        $data = array( );
        $where = "WHERE `cid`=".$cid;
        $max = $CNOA_DB->db_max( "step", $this->table, $where );
        if ( intval( $max ) <= intval( $step ) )
        {
            $data['checked'] = 1;
        }
        else
        {
            $step2 = $step + 1;
            $uid = $CNOA_DB->db_getfield( "uid", $this->table, "WHERE `cid` = '".$cid."' AND `step` = '{$step2}' " );
            $insert['noticeid_c'] = notice::add( $uid, lang( "contractAppMGR" ), lang( "contract" ).( "[".$cInfo['name']."]" ).lang( "needYouApproval" ), "index.php?app=contract&func=business&action=check", "", 9, 0 );
            $notice['touid'] = $uid;
            $notice['from'] = 9;
            $notice['fromid'] = 0;
            $notice['href'] = "index.php?app=contract&func=business&action=check";
            $notice['title'] = lang( "contract" ).( "[".$cInfo['name']."]" ).lang( "needYouApproval" );
            $notice['content'] = "";
            $notice['funname'] = lang( "contractMgr" );
            $notice['move'] = lang( "approval2" );
            $insert['todoid_c'] = notice::add2( $notice );
            $CNOA_DB->db_update( $insert, $this->table, "WHERE `cid` = '".$cid."' AND `step` = '{$step2}' " );
        }
        $where = "WHERE `id`=".$cid;
        $data['step'] = $step;
        app::loadapp( "contract", "business" )->api_updateBusiness( $data, $where );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $cuid = $CNOA_SESSION->get( "UID" );
        $GLOBALS['_GET']['type'] = "business";
        app::loadapp( "contract", "getList" )->run( );
        exit( );
    }

    private function _getAllUserListsInPermitDeptTreeAll( )
    {
        $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
        $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
        echo json_encode( $userList );
        exit( );
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $from = getpar( $_GET, "from" );
        if ( $from == "manage" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/business/manage.htm";
        }
        else if ( $from == "setting" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/business/setting.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    public function api_addNotice( )
    {
        return $this->_addNotice( );
    }

    public function api_insert( $data )
    {
        global $CNOA_DB;
        return $CNOA_DB->db_insert( $data, $this->table );
    }

    public function api_select( $where )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->table, $where );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        return $dblist;
    }

}

?>
