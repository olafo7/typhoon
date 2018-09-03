<?php
//decode by qq2859470

class contractBusinessNotice extends model
{

    private $table = "contract_business_notice";
    private $rows = 15;

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $this->_getAllUserListsInPermitDeptTreeAll( );
            break;
        case "getJsonData" :
            $this->_getJsonData( );
            break;
        case "addNotice" :
            $this->_addNotice( );
            break;
        case "loadNoticeData" :
            $this->_loadNoticeData( );
            break;
        case "deleteNotice" :
            $this->_deleteNotice( );
            break;
        case "noticeCheck" :
            $this->_noticeCheck( );
            break;
        case "editStatus" :
            $this->_editStatus( );
        }
    }

    private function _noticeCheck( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $cuid = $CNOA_SESSION->get( "UID" );
        $cjid = $CNOA_SESSION->get( "JID" );
        $where = "WHERE 1";
        $stime = strtotime( date( "Y-m-d H:i:00" ) );
        $etime = strtotime( date( "Y-m-d H:i:59" ) );
        $where .= " AND `time` >= ".$stime." AND `time` <= {$etime}";
        $dblist = $CNOA_DB->db_select( "*", $this->table, $where );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $dbinfo )
        {
            $arrUid = explode( ",", $dbinfo['uids'] );
            $arrJid = explode( ",", $dbinfo['jids'] );
            if ( in_array( $cuid, $arrUid ) && in_array( $cjid, $arrJid ) )
            {
                $cjid == "";
            }
            if ( in_array( $cuid, $arrUid ) )
            {
                $cid = $dbinfo['cid'];
                $cInfo = app::loadapp( "contract", "businessManage" )->api_getContractInfo( $cid, "name" );
                $dbinfo['contract'] = $cInfo['name'];
                $data[] = $dbinfo;
            }
            if ( in_array( $cjid, $arrJid ) )
            {
                $cid = $dbinfo['cid'];
                $cInfo = app::loadapp( "contract", "businessManage" )->api_getContractInfo( $cid, "name" );
                $dbinfo['contract'] = $cInfo['name'];
                $data[] = $dbinfo;
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        $js = $ds->makeJsonData( );
        echo $js;
        exit( );
    }

    private function _deleteNotice( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $cuid = $CNOA_SESSION->get( "UID" );
        $ids = getpar( $_POST, "ids" );
        $where = "WHERE `id` IN (".$ids.") AND `uid`={$cuid}";
        $titles = $CNOA_DB->db_select( array(
            title
        ), $this->table, $where );
        $CNOA_DB->db_delete( $this->table, $where );
        foreach ( $titles as $title )
        {
            app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 340, $title['title'], lang( "todoReminderContract" ) );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadNoticeData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $nid = getpar( $_POST, "nid" );
        $where = "WHERE `id`=".$nid;
        $dbinfo = $CNOA_DB->db_getone( "*", $this->table, $where );
        $data = array( );
        $data['contract'] = $dbinfo['cid'];
        $data['ntTitle'] = $dbinfo['title'];
        $data['ntContent'] = $dbinfo['content'];
        $data['ntTime'] = formatdate( $dbinfo['time'], "Y-m-d H:i:s" );
        $uids = $dbinfo['uids'];
        $arrUid = explode( ",", $uids );
        $unames = app::loadapp( "main", "user" )->api_getUserNamesByUids( $arrUid );
        $arrName = array( );
        foreach ( $unames as $uinfo )
        {
            $arrName[] = $uinfo['truename'];
        }
        $unames = implode( ",", $arrName );
        $data['ntNames'] = $unames;
        $data['ntUids'] = $dbinfo['uids'];
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        $js = $ds->makeJsonData( );
        echo $js;
        exit( );
    }

    private function _addNotice( $autoNotice = array( ), $autoSetting = "" )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $data['uid'] = $CNOA_SESSION->get( "UID" );
        $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['cid'] = getpar( $_POST, "contract" );
        $data['title'] = getpar( $_POST, "ntTitle" );
        $data['time'] = strtotime( getpar( $_POST, "ntTime" ) );
        $data['content'] = getpar( $_POST, "ntContent" );
        $data['uids'] = getpar( $_POST, "ntUids" );
        if ( $autoSetting )
        {
            $data['cid'] = $autoNotice['cid'];
            $data['title'] = $autoNotice['title'];
            $data['time'] = strtotime( $autoNotice['time'] );
            $data['content'] = $autoNotice['content'];
            $data['uids'] = $autoNotice['uids'];
            $data['jids'] = $autoNotice['jids'];
            $data['type'] = $autoNotice['type'];
        }
        $sort = $CNOA_DB->db_getfield( "sort", "contract_business", "WHERE `id`=".$data['cid'] );
        if ( !app::loadapp( "contract", "businessManage" )->api_checkPermit( $sort, "1", $data['uid'] ) )
        {
            msg::callback( FALSE, lang( "noPermitOpt" ) );
        }
        $nid = getpar( $_POST, "nid" );
        if ( intval( $nid ) <= 0 )
        {
            $CNOA_DB->db_insert( $data, $this->table );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 113, $data['title'], lang( "todoReminderContract" ) );
        }
        else
        {
            $where = "WHERE `id`=".$nid;
            $CNOA_DB->db_update( $data, $this->table, $where );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 340, $data['title'], lang( "todoReminderContract" ) );
        }
        if ( $autoSetting )
        {
            return TRUE;
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $start = getpar( $_POST, "start", 0 );
        $type = getpar( $_POST, "type", 0 );
        $where = "WHERE `uid`=".$uid;
        switch ( intval( $type ) )
        {
        case 2 :
            $stime = strtotime( date( "Y-m-1 00:00:00" ) );
            $etime = strtotime( date( "Y-m-t 23:59:59" ) );
            $where .= " AND `time` >= ".$stime." AND `time` <= {$etime}";
            break;
        case 3 :
            $month = date( "n" ) + 1;
            $year = date( "Y" );
            if ( $month == 13 )
            {
                $month = 1;
                ++$year;
            }
            $stime = strtotime( date( "{$year}-{$month}-1 00:00:00" ) );
            $etime = strtotime( date( "{$year}-{$month}-t 23:59:59" ) );
            $where .= " AND `time` >= ".$stime." AND `time` <= {$etime}";
        }
        $dblist = $CNOA_DB->db_select( "*", $this->table, $where.( " ORDER BY `id` DESC LIMIT ".$start.",{$this->rows}" ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $cids = $uids = array( );
        foreach ( $dblist as $k => $v )
        {
            $cids[] = $v['cid'];
            if ( !empty( $v['uids'] ) )
            {
                $uids = array_merge( $uids, explode( ",", $v['uids'] ) );
            }
        }
        $cids = implode( ",", array_unique( $cids ) );
        $cnames = $truenames = array( );
        if ( !empty( $cids ) )
        {
            $contract = $CNOA_DB->db_select( array( "id", "name" ), "contract_business", "WHERE `id` IN (".$cids.")" );
            foreach ( $contract as $v )
            {
                $cnames[$v['id']] = $v['name'];
            }
        }
        $truenames = app::loadapp( "main", "user" )->api_getUserTruenameByUid( array_unique( $uids ) );
        foreach ( $dblist as $k => $v )
        {
            $v['time'] = formatdate( $v['time'], "Y-m-d H:i" );
            $v['names'] = array( );
            foreach ( explode( ",", $v['uids'] ) as $uid )
            {
                if ( !isset( $truenames[$uid] ) )
                {
                }
                else
                {
                    $v['names'][] = $truenames[$uid];
                }
            }
            $v['names'] = implode( ",", $v['names'] );
            $v['name'] = $cnames[$v['cid']];
            $jids = explode( ",", $v['jids'] );
            $jNames = app::loadapp( "main", "job" )->api_getListByJids( $jids );
            $arrJob = array( );
            foreach ( $jNames as $job )
            {
                $arrJob[] = $job['name'];
            }
            $jNames = implode( ",", $arrJob );
            $v['jobs'] = $jNames;
            $v['type'] = $v['type'] == 0 ? lang( "manualNotice" ) : lang( "autoNotice" );
            $v['status'] = $v['status'] == 0 ? "<span style='color:green;'>".lang( "Untreated" )."</span>" : "<span style='color:red;'>".lang( "haveBeenProcess" )."</span>";
            $dblist[$k] = $v;
        }
        ( );
        $ds = new dataStore( );
        $ds->total = $CNOA_DB->db_getcount( $this->table, $where );
        $ds->data = $dblist;
        echo $ds->makeJsonData( );
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

    private function _editStatus( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $cuid = $CNOA_SESSION->get( "UID" );
        $ids = getpar( $_POST, "ids" );
        $where = "WHERE `id` IN (".$ids.") AND `uid`={$cuid}";
        $titles = $CNOA_DB->db_select( array(
            title
        ), $this->table, $where );
        $CNOA_DB->db_update( array( "status" => "1" ), $this->table, $where );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function api_addNotice( )
    {
        return $this->_addNotice( );
    }

    public function api_addAutoNotice( $autoNotice, $autoSetting )
    {
        return $this->_addNotice( $autoNotice, $autoSetting );
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
