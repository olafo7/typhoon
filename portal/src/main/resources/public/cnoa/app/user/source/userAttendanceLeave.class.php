<?php
//decode by qq2859470

class userAttendanceLeave extends userAttendance
{

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        switch ( $task )
        {
        case "getLeave" :
            $this->_getLeave( );
            break;
        case "getVerifier" :
            $this->getVerifier( );
            exit( );
        case "editLeave" :
            $this->_editLeave( );
            break;
        case "getLeaveList" :
            $this->_getLeaveList( );
            break;
        case "delLeave" :
            $this->_delLeave( );
            break;
        case "updateLeave" :
            $this->_updateLeave( );
        }
    }

    private function _getLeave( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $id = intval( getpar( $_POST, "id" ) );
        $userInfo = app::loadapp( "main", "user" )->api_getUserInfoByUid( $uid );
        $leaveDay = $userInfo['leaveDay'];
        $leaveDayCount = $this->__getLeaveDaySum( $uid );
        $data = array( );
        $data['leaveDays'] = $leaveDay - $leaveDayCount;
        if ( !empty( $id ) )
        {
            $info = $CNOA_DB->db_getone( array( "vid", "stime", "etime", "reason", "leaveDay" ), $this->table_leave, "WHERE `uid`=".$uid." AND `id`={$id}" );
            if ( !empty( $info['vid'] ) )
            {
                $truename = app::loadapp( "main", "user" )->api_getUserTruenameByUid( explode( ",", $info['vid'] ) );
                $data['verifier'] = implode( ",", $truename );
            }
            $data['vid'] = $info['vid'];
            $data['stime'] = date( "Y-m-d H:i:s", $info['stime'] );
            $data['etime'] = date( "Y-m-d H:i:s", $info['etime'] );
            $data['reason'] = $info['reason'];
            $data['leaveDay'] = $info['leaveDay'];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _editLeave( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $vid = ( integer )getpar( $_POST, "vid" );
        $stime = getpar( $_POST, "stime" );
        $etime = getpar( $_POST, "etime" );
        $reason = getpar( $_POST, "reason" );
        $leaveDay = ( double )getpar( $_POST, "leaveDay", 0 );
        if ( $etime - $stime < 0 )
        {
            msg::callback( FALSE, lang( "StimeGreaterThanEtime" ) );
        }
        if ( empty( $reason ) )
        {
            msg::callback( FALSE, lang( "leaveReasonNotEmpty" ) );
        }
        $userInfo = app::loadapp( "main", "user" )->api_getUserInfoByUid( $uid );
        $leaveDays = $userInfo['leaveDay'];
        $leaveDayCount = $this->__getLeaveDaySum( $uid );
        $surplus = $leaveDays - $leaveDayCount;
        if ( $leaveDays < $leaveDay || $surplus < $leaveDay )
        {
            msg::callback( FALSE, lang( "currentAnnualLeave" ).( ":".$surplus." " ).lang( "tian" ) );
        }
        if ( !preg_match( "/^[0-9]{1,4}(\\.5)?\$/", $leaveDay ) )
        {
            msg::callback( FALSE, lang( "inputNumFormatWrong" ) );
        }
        if ( 366 < $leaveDay || $leaveDay < 0 )
        {
            msg::callback( FALSE, lang( "beyondAnnualLeave" ) );
        }
        $data = array( );
        $data['uid'] = $uid;
        $data['vid'] = $vid;
        $data['stime'] = strtotime( $stime );
        $data['etime'] = strtotime( $etime );
        $data['reason'] = $reason;
        $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['leaveDay'] = $leaveDay;
        $data['ip'] = getip( );
        $data['status'] = 0;
        $data['oreason'] = "";
        $vid = explode( ",", $vid );
        $noticeIds = array( );
        $todoIds = array( );
        foreach ( $vid as $id )
        {
            $noticeC = lang( "leaveRegNeedApp" );
            $noticeT = lang( "leaveaMgr" );
            $noticeH = "index.php?app=hr&func=attendance&action=check";
            $noticeIds[] = notice::add( $id, $noticeT, $noticeC, $noticeH, 0, 15 );
            $notice['touid'] = $id;
            $notice['from'] = 15;
            $notice['fromid'] = 0;
            $notice['href'] = $noticeH;
            $notice['title'] = $noticeC;
            $notice['content'] = lang( "reason" ).( ":".$reason );
            $notice['funname'] = lang( "personLeaMgr" );
            $notice['move'] = lang( "approval2" );
            $todoIds[] = notice::add2( $notice );
        }
        $data['noticeid_c'] = implode( ",", $noticeIds );
        $data['todoid_c'] = implode( ",", $todoIds );
        $id = intval( getpar( $_POST, "id" ) );
        if ( empty( $id ) )
        {
            $CNOA_DB->db_insert( $data, $this->table_leave );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 2304, NULL, "请假申请记录" );
        }
        else
        {
            $where = "WHERE `uid`=".$uid." AND `id`={$id}";
            $info = $CNOA_DB->db_getone( array( "noticeid_c", "todoid_c" ), $this->table_leave, $where );
            if ( $info )
            {
                $noticeIds = explode( ",", $info['noticeid_c'] );
                $todoIds = explode( ",", $info['todoid_c'] );
                notice::deletenotice( $noticeIds, $todoIds );
                $CNOA_DB->db_update( $data, $this->table_leave, $where );
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 2304, NULL, "请假申请记录" );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function __getLeaveList( $where = "" )
    {
        global $CNOA_DB;
        $start = getpar( $_POST, "start", 0 );
        $dblist = $CNOA_DB->db_select( "*", $this->table_leave, $where.( " ORDER BY `id` DESC LIMIT ".$start.", {$this->rows}" ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $uids = array( );
        foreach ( $dblist as $k => $v )
        {
            if ( !empty( $v['vid'] ) )
            {
                $uids[] = $v['vid'];
            }
        }
        if ( !empty( $uids ) )
        {
            $truenames = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $uids );
        }
        $data = array( );
        foreach ( $dblist as $v )
        {
            $v['posttime'] = formatdate( $v['posttime'], "Y-m-d H:i" );
            $v['stime'] = formatdate( $v['stime'], "Y-m-d H:i" );
            $v['etime'] = formatdate( $v['etime'], "Y-m-d H:i" );
            $v['otime'] = formatdate( $v['otime'], "Y-m-d H:i" );
            if ( !empty( $v['vid'] ) )
            {
                $verifier = array( );
                foreach ( explode( ",", $v['vid'] ) as $uid )
                {
                    $verifier[] = $truenames[$uid];
                }
                $v['verifier'] = implode( ",", $verifier );
            }
            $data[] = $v;
        }
        return $data;
    }

    private function _getLeaveList( )
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
        $data = $this->__getLeaveList( $where );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        $dataStore->total = $CNOA_DB->db_getcount( $this->table_leave, $where );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _delLeave( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $ids = getpar( $_POST, "ids" );
        if ( $ids )
        {
            $dblist = $CNOA_DB->db_select( array( "todoid_c", "noticeid_c" ), $this->table_leave, "WHERE `uid`=".$uid." AND `id` IN ({$ids}) " );
            $noticeIds = array( );
            $todoIds = array( );
            foreach ( $dblist as $v )
            {
                if ( !empty( $v['noticeid_c'] ) )
                {
                    $v['noticeid_c'] = explode( ",", $v['noticeid_c'] );
                    $noticeIds = array_merge( $noticeIds, $v['noticeid_c'] );
                }
                if ( !empty( $v['todoid_c'] ) )
                {
                    $v['todoid_c'] = explode( ",", $v['todoid_c'] );
                    $todoIds = array_merge( $todoIds, $v['todoid_c'] );
                }
            }
            $noticeIds = array_unique( $noticeIds );
            $todoIds = array_unique( $todoIds );
            notice::deletenotice( $noticeIds, $todoIds );
            $CNOA_DB->db_delete( $this->table_leave, "WHERE `uid`=".$uid." AND `id` IN ({$ids}) " );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 2304, NULL, "记录" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _updateLeave( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $id = intval( getpar( $_POST, "id" ) );
        $status = intval( getpar( $_POST, "status" ) );
        $info = $CNOA_DB->db_getone( array( "stime", "vid", "noticeid_a", "todoid_a" ), $this->table_leave, "WHERE `id`=".$id );
        $update = array( );
        $update['status'] = $status;
        $update['otime'] = time( );
        $update['hour'] = time( ) - intval( $info['stime'] );
        notice::donen( $info['noticeid_a'] );
        notice::donet( $info['todoid_a'] );
        $noticeC = lang( "sickLeaveNeedYouApp" );
        if ( intval( $status ) == 5 )
        {
            $noticeC = "“".$truename."”".lang( "appSickLeavePleaseApp" );
        }
        $noticeT = lang( "leaveaMgr" );
        $noticeH = "index.php?app=hr&func=attendance&action=check";
        $update['noticeid_c'] = notice::add( $info['vid'], $noticeT, $noticeC, $noticeH, 0, 15 );
        $notice['touid'] = $info['vid'];
        $notice['from'] = 15;
        $notice['fromid'] = 0;
        $notice['href'] = $noticeH;
        $notice['title'] = $noticeC;
        $notice['content'] = "";
        $notice['funname'] = lang( "personSickLeave" );
        $notice['move'] = lang( "approval2" )." ";
        $update['todoid_c'] = notice::add2( $notice );
        $CNOA_DB->db_update( $update, $this->table_leave, "WHERE `uid`=".$uid." AND `id`={$id}" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 2304, "请假登记状态" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function api_getLeaveList( $where )
    {
        return $this->__getLeaveList( $where );
    }

    public function api_getLeaveCount( $where )
    {
        global $CNOA_DB;
        return $CNOA_DB->db_getcount( $this->table_leave, $where );
    }

}

?>
