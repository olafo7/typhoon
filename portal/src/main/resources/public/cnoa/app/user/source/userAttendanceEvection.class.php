<?php
//decode by qq2859470

class userAttendanceEvection extends userAttendance
{

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        switch ( $task )
        {
        case "getEvection" :
            $this->_getEvection( );
            break;
        case "getVerifier" :
            $this->getVerifier( );
            exit( );
        case "editEvection" :
            $this->_editEvection( );
            break;
        case "getEvectionList" :
            $this->_getEvectionList( );
            break;
        case "delEvection" :
            $this->_delEvection( );
            break;
        case "updateEvection" :
            $this->_updateEvection( );
        }
    }

    private function _getEvection( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $id = intval( getpar( $_POST, "id", 0 ) );
        $data = array( );
        if ( !empty( $id ) )
        {
            $data = $CNOA_DB->db_getone( array( "vid", "stime", "etime", "address", "reason" ), $this->table_evection, "WHERE `uid`=".$uid." AND `id`={$id}" );
            if ( !empty( $data['vid'] ) )
            {
                $truename = app::loadapp( "main", "user" )->api_getUserTruenameByUid( explode( ",", $data['vid'] ) );
                $data['verifier'] = implode( ",", $truename );
            }
            $data['stime'] = date( "Y-m-d H:i:s", $data['stime'] );
            $data['etime'] = date( "Y-m-d H:i:s", $data['etime'] );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _editEvection( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $vid = getpar( $_POST, "vid" );
        $stime = getpar( $_POST, "stime" );
        $etime = getpar( $_POST, "etime" );
        $reason = getpar( $_POST, "reason" );
        $address = getpar( $_POST, "address" );
        if ( $etime - $stime < 0 )
        {
            msg::callback( FALSE, lang( "StimeGreaterThanEtime" ) );
        }
        if ( empty( $vid ) )
        {
            msg::callback( FALSE, lang( "appManNotEmpty" ) );
        }
        if ( empty( $address ) )
        {
            msg::callback( FALSE, lang( "localNotEmpty" ) );
        }
        if ( empty( $reason ) )
        {
            msg::callback( FALSE, lang( "travelReasonNotEmpty" ) );
        }
        $data = array( );
        $data['uid'] = $uid;
        $data['vid'] = $vid;
        $data['stime'] = strtotime( $stime );
        $data['etime'] = strtotime( $etime );
        $data['reason'] = $reason;
        $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['address'] = $address;
        $data['ip'] = getip( );
        $data['status'] = 0;
        $data['oreason'] = "";
        $vid = explode( ",", $vid );
        $noticeIds = array( );
        $todoIds = array( );
        foreach ( $vid as $id )
        {
            $noticeC = lang( "travelNeedYouApp" );
            $noticeT = lang( "travelMgr" );
            $noticeH = "index.php?app=hr&func=attendance&action=check";
            $noticeIds[] = notice::add( $id, $noticeT, $noticeC, $noticeH, 0, 15 );
            $notice['touid'] = $id;
            $notice['from'] = 15;
            $notice['fromid'] = 0;
            $notice['href'] = $noticeH;
            $notice['title'] = $noticeC;
            $notice['content'] = lang( "reason" ).":".$data['reason'].lang( "address" ).$data['address'].lang( "time" ).formatdate( $data['stime'], "Y-m-d H:i" ).lang( "to" ).formatdate( $data['etime'], "Y-m-d H:i" );
            $notice['funname'] = lang( "travelRegMgr" );
            $notice['move'] = lang( "approval2" )." ";
            $todoIds[] = notice::add2( $notice );
        }
        $data['noticeid_c'] = implode( ",", $noticeIds );
        $data['todoid_c'] = implode( ",", $todoIds );
        $id = intval( getpar( $_POST, "id" ) );
        if ( empty( $id ) )
        {
            $CNOA_DB->db_insert( $data, $this->table_evection );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 2303, NULL, "出差申请记录" );
        }
        else
        {
            $where = "WHERE `uid`=".$uid." AND `id`={$id}";
            $info = $CNOA_DB->db_getone( array( "noticeid_c", "todoid_c" ), $this->table_evection, $where );
            if ( $info )
            {
                $noticeIds = explode( ",", $info['noticeid_c'] );
                $todoIds = explode( ",", $info['todoid_c'] );
                notice::deletenotice( $noticeIds, $todoIds );
                $CNOA_DB->db_update( $data, $this->table_evection, $where );
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 2303, NULL, "出差申请记录" );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function __getEvectionList( $where = "" )
    {
        global $CNOA_DB;
        $start = getpar( $_POST, "start", 0 );
        $dblist = $CNOA_DB->db_select( "*", $this->table_evection, $where.( " ORDER BY `id` DESC LIMIT ".$start.", {$this->rows}" ) );
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

    private function _getEvectionList( )
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
        $data = $this->__getEvectionList( $where );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        $dataStore->total = $CNOA_DB->db_getcount( $this->table_evection, $where );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _delEvection( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $ids = getpar( $_POST, "ids" );
        if ( $ids )
        {
            $dblist = $CNOA_DB->db_select( array( "todoid_c", "noticeid_c" ), $this->table_evection, "WHERE `uid`=".$uid." AND `id` IN ({$ids}) " );
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
            $CNOA_DB->db_delete( $this->table_evection, "WHERE `uid`=".$uid." AND `id` IN ({$ids}) " );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 2303, NULL, "出差申请记录" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _updateEvection( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $truename = $CNOA_SESSION->get( "TRUENAME" );
        $id = intval( getpar( $_POST, "id" ) );
        $status = intval( getpar( $_POST, "status" ) );
        $info = $CNOA_DB->db_getone( array( "stime", "vid", "noticeid_a", "todoid_a" ), $this->table_evection, "WHERE `uid`=".$uid." AND `id`={$id}" );
        $update = array( );
        $update['status'] = $status;
        $update['otime'] = time( );
        $update['hour'] = time( ) - intval( $info['stime'] );
        notice::donen( $info['noticeid_a'] );
        notice::donet( $info['todoid_a'] );
        $noticeC = "“".$truename."”已经出差归来，请您核实.";
        if ( $status == 5 )
        {
            $noticeC = "“".$truename."”申请注销出差登记，请您审批.";
        }
        $noticeT = "出差归来管理";
        $noticeH = "index.php?app=hr&func=attendance&action=check";
        $update['noticeid_c'] = notice::add( $info['vid'], $noticeT, $noticeC, $noticeH, 0, 15 );
        $notice['touid'] = $info['vid'];
        $notice['from'] = 15;
        $notice['fromid'] = $id;
        $notice['href'] = $noticeH;
        $notice['title'] = $noticeC;
        $notice['content'] = "";
        $notice['funname'] = "个人管理";
        $notice['move'] = "审批 ";
        $update['todoid_c'] = notice::add2( $notice );
        $CNOA_DB->db_update( $update, $this->table_evection, "WHERE `uid`=".$uid." AND `id`={$id}" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 2303, "出差登记状态" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function api_getEvectionList( $where )
    {
        return $this->__getEvectionList( $where );
    }

}

?>
