<?php
//decode by qq2859470

class userAttendance extends model
{

    protected $table_checktime = "user_attendance_checktime";
    protected $table_egression = "user_attendance_egression";
    protected $table_leave = "user_attendance_leave";
    protected $table_evection = "user_attendance_evection";
    protected $table_overtime = "user_attendance_overtime";
    protected $table_setting_approver = "hr_attendance_setting_approver";
    protected $rows = 15;

    public function actionOvertime( )
    {
        app::loadapp( "user", "attendanceOvertime" )->run( );
    }

    public function actionEvection( )
    {
        app::loadapp( "user", "attendanceEvection" )->run( );
    }

    public function actionLeave( )
    {
        app::loadapp( "user", "attendanceLeave" )->run( );
    }

    public function actionEgression( )
    {
        app::loadapp( "user", "attendanceEgression" )->run( );
    }

    public function actionAgent( )
    {
        app::loadapp( "user", "attendanceAgent" )->run( );
    }

    public function actionReturn( )
    {
        app::loadapp( "user", "attendanceReturn" )->run( );
    }

    public function actionSetting( )
    {
        app::loadapp( "user", "attendanceSetting" )->run( );
    }

    protected function getVerifier( )
    {
        global $CNOA_DB;
        $uids = $CNOA_DB->db_getfield( "approverUid", $this->table_setting_approver, "WHERE id=1" );
        if ( empty( $uids ) )
        {
            $uids = array( 0 );
        }
        app::loadapp( "main", "user" )->api_getSelectorData( $uids );
    }

    public function actionChecktime( )
    {
        $task = getpar( $_GET, "task" );
        if ( $task == "alert" )
        {
            $type = getpar( $_GET, "type" );
            switch ( $type )
            {
            case "loadData" :
                $this->_alertLoadData( );
                exit( );
            case "checkin" :
                $this->_alertCheckin( );
            }
            exit( );
        }
        app::loadapp( "user", "attendanceChecktime" )->run( );
    }

    private function _alertCheckin( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $cid = getpar( $_GET, "cid" );
        $cnum = getpar( $_GET, "cnum" );
        $did = $CNOA_SESSION->get( "DID" );
        $sid = $CNOA_SESSION->get( "SID" );
        $jid = $CNOA_SESSION->get( "JID" );
        if ( app::loadapp( "main", "systemLoginIp" )->api_is_limited( $did, $jid, $uid, $sid, "checkin" ) )
        {
            msg::callback( FALSE, lang( "youIpSXZdetermine" ) );
        }
        app::loadapp( "user", "attendanceChecktime" )->api_addChecktime( $uid, $cid, $cnum );
        msg::callback( TRUE, lang( "registerSuccess" ) );
    }

    private function _alertLoadData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $data = array( );
        $uid = $CNOA_SESSION->get( "UID" );
        $dbinfo = app::loadapp( "main", "user" )->api_getUserDataByUid( $uid );
        $cid = $dbinfo['worktimeId'];
        $wt = app::loadapp( "hr", "attendanceSetting" )->api_getSettingWorktime( $cid );
        $wt = json_decode( $wt );
        $i = 0;
        $date = date( "Y-m-d" );
        foreach ( $wt->data as $wtdata )
        {
            $tmp =& $wt->data[$i];
            $time = $date." ".$tmp->time;
            $time = strtotime( $time );
            $tmp->ctime = $time;
            if ( intval( $tmp->type ) == 0 )
            {
                $s = strtotime( "-".$wt->onbeforetime." minutes", $time );
                $tmp->stime = $s;
                $s = strtotime( "+".$wt->onaftertime." minutes", $time );
                $tmp->etime = $s;
            }
            else
            {
                $s = strtotime( "-".$wt->offbeforetime." minutes", $time );
                $tmp->stime = $time;
                $s = strtotime( "+".$wt->offaftertime." minutes", $time );
                $tmp->etime = $s;
            }
            ++$i;
            $where = "WHERE `uid`=".$uid." AND `cid`={$cid} AND `cnum`={$tmp->cnum} AND `date`='{$date}'";
            $dbcount = $CNOA_DB->db_getcount( $this->table_checktime, $where );
            $tmp->checked = 0;
            if ( 0 < $dbcount )
            {
                $tmp->checked = 1;
            }
        }
        $where = "WHERE `uid`=".$uid;
        $dblist = app::loadapp( "hr", "attendanceSetting" )->api_getSpecialUser( $where );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $isSpeUser = 0 < count( $dblist );
        $out = array( );
        $out['alert'] = 0;
        $out['type'] = 0;
        $out['num'] = 0;
        $out['time'] = "";
        $out['id'] = $wt->id;
        if ( $wt->alert == 1 && strval( $isSpeUser ) == "" )
        {
            $datas = $wt->data;
            $week = date( "w" );
            $isRestDay = strpos( "{$wt->restDay}", "{$week}" );
            $isVacation = app::loadapp( "user", "attendanceChecktime" )->api_isVacation( );
            if ( strval( $isRestDay ) == "" && strval( $isVacation ) == "" )
            {
                $i = 0;
                for ( ; $i <= count( $datas ) - 1; ++$i )
                {
                    $data = $datas[$i];
                    $stime = $data->stime;
                    $etime = $data->etime;
                    $checked = $data->checked;
                    $type = $data->type;
                    $num = $data->num;
                    $time = $data->time;
                    $now = time( );
                    if ( !( $checked == 0 ) && !( $stime <= $now ) && !( $now <= $etime ) )
                    {
                        if ( $type == 1 )
                        {
                            $type = "下班";
                        }
                        else
                        {
                            $type = "上班";
                        }
                        $out['alert'] = 1;
                        $out['type'] = $type;
                        $out['num'] = $num;
                        $out['time'] = $time;
                    }
                }
            }
        }
        $out['alert'] = 1;
        $js = json_encode( $out );
        echo $js;
    }

    private function __getTableNameByType( $type )
    {
        $table = "";
        switch ( $type )
        {
        case 1 :
            $table = $this->table_egression;
            return $table;
        case 2 :
            $table = $this->table_leave;
            return $table;
        case 3 :
            $table = $this->table_evection;
            return $table;
        case 4 :
            $table = $this->table_overtime;
        }
        return $table;
    }

    public function api_getUidByRecordId( $type, $id )
    {
        global $CNOA_DB;
        $table = $this->__getTableNameByType( $type );
        return $CNOA_DB->db_getfield( "uid", $table, "WHERE `id`=".$id );
    }

    protected function __getLeaveDaySum( $uid )
    {
        global $CNOA_DB;
        $syear = date( "Y" )."-01-01 00:00:00";
        $syear = strtotime( $syear );
        $eyear = date( "Y" )."-12-31 23:59:59";
        $eyear = strtotime( $eyear );
        $sql = "SELECT SUM(leaveDay) AS sum  FROM ".tname( $this->table_leave ).( " WHERE `uid`=".$uid." AND `posttime` >={$syear} AND `posttime`<= {$eyear}" );
        $db = $CNOA_DB->get_one( $sql );
        if ( empty( $db ) )
        {
            return 0;
        }
        return $db['sum'];
    }

    public function api_getLeaveDaySum( $uid )
    {
        return $this->__getLeaveDaySum( $uid );
    }

    public function api_getTaskListForPlan( $planid )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $info = $CNOA_DB->db_select( array( "tid", "title" ), $this->table_list, "WHERE `from`='plan' AND `fromid`='".$planid."' ORDER BY `posttime` DESC" );
        return $info;
    }

    public function api_getCheck( $type = 0, $where = "WHERE `status`=0" )
    {
        switch ( $type )
        {
        case 1 :
            $data = app::loadapp( "user", "attendanceEgression" )->api_getEgression( $where );
            return $data;
        case 2 :
            $data = app::loadapp( "user", "attendanceLeave" )->api_getLeaveList( $where );
            return $data;
        case 3 :
            $data = app::loadapp( "user", "attendanceEvection" )->api_getEvectionList( $where );
            return $data;
        case 4 :
            $data = app::loadapp( "user", "attendanceOvertime" )->api_getOvertimeList( $where );
        }
        return $data;
    }

    public function api_getCheckCount( $type = 0, $where = "WHERE `status`=0" )
    {
        global $CNOA_DB;
        $table = $this->__getTableNameByType( $type );
        return $CNOA_DB->db_getcount( $table, $where );
    }

    public function api_updateStatus( $type, $id, $status, $oreason = "" )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $update = array( );
        $update['status'] = $status;
        if ( $oreason != "" )
        {
            $update['oreason'] = $oreason;
        }
        $update['vid'] = $uid;
        if ( $type == 2 && $status == 4 )
        {
            $update['otime'] = time( );
        }
        $table = $this->__getTableNameByType( $type );
        $CNOA_DB->db_update( $update, $table, "WHERE `id`=".$id );
    }

    public function api_getInfoById( $type, $id )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $table = $this->__getTableNameByType( $type );
        return $CNOA_DB->db_getone( "*", $table, "WHERE `id`=".$id );
    }

    public function api_getChecktimeList( $uid, $where = "" )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->table_checktime, "WHERE `uid` IN (".$uid.") {$where} ORDER BY `id` DESC" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        return $dblist;
    }

    public function api_getEgressionList( $uid, $where = "" )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->table_egression, "WHERE `uid` IN (".$uid.") {$where}" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        return $dblist;
    }

    public function api_getEgressionCount( $uid, $where = "" )
    {
        global $CNOA_DB;
        $count = $CNOA_DB->db_getcount( $this->table_egression, "WHERE `uid` IN (".$uid.") AND `status` IN (1, 4) {$where}" );
        return $count;
    }

    public function api_getLeaveList( $uid, $where = "" )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->table_leave, "WHERE `uid` IN (".$uid.") {$where}" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        return $dblist;
    }

    public function api_getLeaveCount( $uid, $where = "" )
    {
        global $CNOA_DB;
        $count = $CNOA_DB->db_getcount( $this->table_leave, "WHERE `uid` IN (".$uid.") AND `status` IN (1, 4) {$where}" );
        return $count;
    }

    public function api_getEvectionList( $uid, $where = "" )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->table_evection, "WHERE `uid` IN (".$uid.") {$where}" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        return $dblist;
    }

    public function api_getEvectionCount( $uid, $where = "" )
    {
        global $CNOA_DB;
        $count = $CNOA_DB->db_getcount( $this->table_evection, "WHERE `uid` IN (".$uid.") AND `status` IN (1, 4) {$where}" );
        return $count;
    }

    public function api_getOvertimeList( $uid, $where = "" )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->table_overtime, "WHERE `uid` IN (".$uid.") {$where}" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        return $dblist;
    }

    public function api_getOvertimeCount( $uid, $where = "" )
    {
        global $CNOA_DB;
        $count = $CNOA_DB->db_getcount( $this->table_overtime, "WHERE `uid` IN (".$uid.") {$where}" );
        return $count;
    }

    public function api_getCheckTime( )
    {
        $this->_alertLoadData( );
    }

    public function api_getEgressionHours( $uid, $where = "" )
    {
        global $CNOA_DB;
        $field = "SUM(`hour`)";
        $sql = "SELECT ".$field." FROM ".tname( $this->table_egression )." WHERE `uid`=".$uid." AND `otime`>0 ".$where;
        $dbOtime = $CNOA_DB->get_one( $sql );
        $time = $dbOtime["{$field}"];
        $hour = intval( $time / 3600 );
        $minute = intval( $time / 60 ) - intval( $hour * 60 );
        $hours = $hour;
        return $hours;
    }

    public function api_getEvectionHours( $uid, $where = "" )
    {
        global $CNOA_DB;
        $field = "SUM(`hour`)";
        $sql = "SELECT ".$field." FROM ".tname( $this->table_evection )." WHERE `uid`=".$uid." AND `otime`>0 ".$where;
        $dbOtime = $CNOA_DB->get_one( $sql );
        $time = $dbOtime["{$field}"];
        $hour = intval( $time / 3600 );
        $minute = intval( $time / 60 ) - intval( $hour * 60 );
        $hours = $hour;
        return $hours;
    }

    public function api_getLeaveHours( $uid, $where = "" )
    {
        global $CNOA_DB;
        $field = "SUM(`hour`)";
        $sql = "SELECT ".$field." FROM ".tname( $this->table_leave )." WHERE `uid`=".$uid." AND `otime`>0 ".$where;
        $dbOtime = $CNOA_DB->get_one( $sql );
        $time = $dbOtime["{$field}"];
        $hour = intval( $time / 3600 );
        $minute = intval( $time / 60 ) - intval( $hour * 60 );
        $hours = $hour;
        return $hours;
    }

    public function api_getOvertimeHours( $uid, $where = "" )
    {
        global $CNOA_DB;
        $field = "SUM(`hour`)";
        $sql = "SELECT ".$field." FROM ".tname( $this->table_overtime )." WHERE `uid`=".$uid." AND `otime`>0 AND `hour`>0 ".$where;
        $dbOtime = $CNOA_DB->get_one( $sql );
        $time = $dbOtime["{$field}"];
        $hour = intval( $time / 3600 );
        $minute = intval( $time / 60 ) - intval( $hour * 60 );
        $hours = $hour;
        return $hours;
    }

}

?>
