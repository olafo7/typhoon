<?php
//decode by qq2859470

class userAttendanceEgression extends userAttendance
{

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        switch ( $task )
        {
        case "getVerifier" :
            $this->_getVerifier( );
            break;
        case "addEgression" :
            $this->_addEgression( );
            break;
        case "editEgression" :
            $this->_editEgression( );
            break;
        case "getEgressionList" :
            $this->_getEgressionList( );
            break;
        case "delEgression" :
            $this->_delEgression( );
            break;
        case "getEgression" :
            $this->_getEgression( );
            break;
        case "updateEgression" :
            $this->_updateEgression( );
        }
    }

    private function _getEgressionList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $status = getpar( $_POST, "status", -1 );
        $where = "WHERE `uid`=".$uid;
        if ( 0 <= $status )
        {
            $where .= " AND `status`=".$status." ";
        }
        $data = $this->__getEgression( $where );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        $dataStore->total = $CNOA_DB->db_getcount( $this->table_egression, $where );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _addEgression( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $vid = getpar( $_POST, "verifier" );
        $date = getpar( $_POST, "date" );
        $stime = getpar( $_POST, "stime" );
        $etime = getpar( $_POST, "etime" );
        $reason = getpar( $_POST, "reason" );
        $stime = strtotime( "{$date} {$stime}" );
        $etime = strtotime( "{$date} {$etime}" );
        $now_time = strtotime( date( "Y-m-d H:i:s" ) );
        if ( $etime - $stime < 0 )
        {
            msg::callback( FALSE, lang( "StimeGreaterThanEtime" ) );
        }
        $insert = array( );
        $insert['uid'] = $uid;
        $insert['vid'] = $vid;
        $insert['stime'] = $stime;
        $insert['etime'] = $etime;
        $insert['reason'] = $reason;
        $insert['posttime'] = $now_time;
        $insert['ip'] = getip( );
        $noticeC = lang( "outRegNeedApp" );
        $noticeT = lang( "outMgr" );
        $noticeH = "index.php?app=hr&func=attendance&action=check";
        $insert['noticeid_c'] = notice::add( $vid, $noticeT, $noticeC, $noticeH, 0, 15 );
        $notice['touid'] = $vid;
        $notice['from'] = 15;
        $notice['fromid'] = 0;
        $notice['href'] = $noticeH;
        $notice['title'] = $noticeC;
        $notice['content'] = lang( "reason" ).":".$reason;
        $notice['funname'] = lang( "outMgr" );
        $notice['move'] = lang( "approval2" );
        $insert['todoid_c'] = notice::add2( $notice );
        $CNOA_DB->db_insert( $insert, $this->table_egression );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 2305, NULL, "记录" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getVerifier( )
    {
        global $CNOA_DB;
        $userNames = array( );
        $uids = $CNOA_DB->db_getfield( "approverUid", $this->table_setting_approver, "WHERE id=1" );
        if ( !empty( $uids ) )
        {
            $userNames = app::loadapp( "main", "user" )->api_getUserNamesByUids( explode( ",", $uids ) );
            $userNames = array_merge( $userNames );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $userNames;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function __getEgression( $where = "" )
    {
        global $CNOA_DB;
        $start = getpar( $_POST, "start", 0 );
        $dblist = $CNOA_DB->db_select( "*", $this->table_egression, $where.( " ORDER BY `id` DESC LIMIT ".$start.", {$this->rows}" ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $tmp )
        {
            $info =& $data[];
            $info = $tmp;
            $info['posttime'] = formatdate( $tmp['posttime'], "Y-m-d H:i" );
            $info['stime'] = formatdate( $tmp['stime'], "Y-m-d H:i" );
            $info['etime'] = formatdate( $tmp['etime'], "Y-m-d H:i" );
            $info['otime'] = formatdate( $tmp['otime'], "Y-m-d H:i" );
            $info['id'] = $tmp['id'];
            $info['verifier'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $tmp['vid'] );
            $info['status'] = $tmp['status'];
            $info['reason'] = $tmp['reason'];
        }
        return $data;
    }

    private function _delEgression( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $ids = getpar( $_POST, "ids" );
        if ( $ids )
        {
            $ids = explode( ",", substr( $ids, 0, -1 ) );
            if ( is_array( $ids ) )
            {
                foreach ( $ids as $v )
                {
                    $DB = $CNOA_DB->db_getone( array( "todoid_c", "noticeid_c" ), $this->table_egression, "WHERE `uid` = '".$uid."' AND `id` = '{$v}' " );
                    notice::deletenotice( $DB['noticeid_c'], $DB['todoid_c'] );
                    $CNOA_DB->db_delete( $this->table_egression, "WHERE `uid`=".$uid." AND `id`='{$v}'" );
                }
            }
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 2305, NULL, "记录" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getEgression( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $id = getpar( $_POST, "id" );
        $infos = $CNOA_DB->db_getone( "*", $this->table_egression, "WHERE `uid`=".$uid." AND `id`={$id}" );
        if ( !is_array( $infos ) )
        {
            $infos = array( );
        }
        $infos['date'] = date( "Y-m-d", $infos['stime'] );
        $infos['stime'] = date( "H:i", $infos['stime'] );
        $infos['etime'] = date( "H:i", $infos['etime'] );
        $infos['verifier'] = $infos['vid'];
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $infos;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _editEgression( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $id = getpar( $_POST, "id" );
        $vid = getpar( $_POST, "verifier" );
        $date = getpar( $_POST, "date" );
        $stime = getpar( $_POST, "stime" );
        $etime = getpar( $_POST, "etime" );
        $reason = getpar( $_POST, "reason" );
        $stime = strtotime( "{$date} {$stime}" );
        $etime = strtotime( "{$date} {$etime}" );
        $now_time = strtotime( date( "Y-m-d H:i:s" ) );
        if ( $etime - $stime < 0 )
        {
            msg::callback( FALSE, lang( "StimeGreaterThanEtime" ) );
        }
        $update = array( );
        $update['uid'] = $uid;
        $update['vid'] = $vid;
        $update['stime'] = $stime;
        $update['etime'] = $etime;
        $update['reason'] = $reason;
        $update['posttime'] = $now_time;
        $update['ip'] = getip( );
        $update['status'] = 0;
        $update['oreason'] = "";
        $noticeC = lang( "outRegNeedApp" );
        $noticeT = lang( "outMgr" );
        $noticeH = "index.php?app=hr&func=attendance&action=check&ajax=1&parentid=docs-CNOA_MENU_PERSONMGR_ATTENDANCE_CHECK&_dc=1303801634505";
        $update['noticeid_c'] = notice::add( $vid, $noticeT, $noticeC, $noticeH, 0, 15 );
        $notice['touid'] = $vid;
        $notice['from'] = 15;
        $notice['fromid'] = 0;
        $notice['href'] = $noticeH;
        $notice['title'] = $noticeC;
        $notice['content'] = lang( "reason" ).":".$reason;
        $notice['funname'] = lang( "personalMgr" );
        $notice['move'] = lang( "approval2" );
        $update['todoid_c'] = notice::add2( $notice );
        $CNOA_DB->db_update( $update, $this->table_egression, "WHERE `uid`=".$uid." AND `id`={$id}" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 2305, NULL, "记录" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _updateEgression( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $id = getpar( $_POST, "id" );
        $status = getpar( $_POST, "status" );
        $stime = $CNOA_DB->db_getfield( "stime", $this->table_egression, "WHERE `uid`=".$uid." AND `id`={$id}" );
        $hours = time( ) - intval( $stime );
        $update = array( );
        $update['status'] = 3;
        $update['otime'] = time( );
        $update['hour'] = $hours;
        if ( intval( $status ) == 2 )
        {
            $update['status'] = 5;
        }
        $user = $CNOA_DB->db_getone( array( "vid", "noticeid_a", "todoid_a" ), $this->table_egression, "WHERE `id`=".$id );
        notice::donen( $user['noticeid_a'] );
        notice::donet( $user['todoid_a'] );
        $truename = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $uid );
        $noticeC = "“".$truename."”".lang( "goOutBackVerify" );
        if ( intval( $status ) == 2 )
        {
            $noticeC = "“".$truename."”".lang( "appZXoutReg" );
        }
        $noticeT = lang( "goOutBackMgr" );
        $noticeH = "index.php?app=hr&func=attendance&action=check";
        $update['noticeid_c'] = notice::add( $user['vid'], $noticeT, $noticeC, $noticeH, 0, 15 );
        $notice['touid'] = $user['vid'];
        $notice['from'] = 15;
        $notice['fromid'] = 0;
        $notice['href'] = $noticeH;
        $notice['title'] = $noticeC;
        $notice['content'] = "";
        $notice['funname'] = lang( "goOutBackMgr" );
        $notice['move'] = lang( "approval2" )." ";
        $update['todoid_c'] = notice::add2( $notice );
        $CNOA_DB->db_update( $update, $this->table_egression, "WHERE `uid`=".$uid." AND `id`={$id}" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 2305, "外出登记状态" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function api_getEgression( $where )
    {
        return $this->__getEgression( $where );
    }

}

?>
