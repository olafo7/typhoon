<?php
//decode by qq2859470

class userTaskWatch extends model
{

    private $table_list = "user_task_list";
    private $table_participant = "user_task_participant";
    private $table_event = "user_task_event";
    private $table_progress = "user_task_progress";
    private $table_discuss_list = "user_task_discuss_list";
    private $table_discuss_content = "user_task_discuss_content";
    private $table_document = "user_task_document";
    private $viewUrl = "index.php?app=user&func=task&action=default&task=loadPage&from=view&tid=";
    private $listUrl = "index.php?app=user&func=task&action=default&task=loadPage&from=list";
    private $eventType = array
    (
        1 => "布置",
        2 => "修改",
        3 => "撤销",
        4 => "审批",
        5 => "接受",
        6 => "拒绝",
        7 => "汇报",
        8 => "完成",
        9 => "审核",
        10 => "督办",
        11 => "失败",
        12 => "延期"
    );

    public function run( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $task = getpar( $_GET, "task", getpar( $_POST, "task" ) );
        if ( $task == "loadPage" )
        {
            $this->_loadWatchPage( );
        }
        else if ( $task == "getAllUserListsInPermitDeptTree" )
        {
            $this->_getAllUserListsInPermitDeptTree( );
        }
        else if ( $task == "getJsonData" )
        {
            $this->_getJsonDataWatch( );
        }
        else if ( $task == "viewTask" )
        {
            $this->_viewTask( );
        }
        else if ( $task == "getEventList" )
        {
            $this->_getEventList( );
        }
        else if ( $task == "getDiscussList" )
        {
            $this->_getDiscussList( );
        }
        else if ( $task == "getDiscussInfo" )
        {
            $this->_getDiscussInfo( );
        }
        else if ( $task == "addDiscuss" )
        {
            $this->_addDiscuss( );
        }
        else if ( $task == "addDiscussComment" )
        {
            $this->_addDiscussComment( );
        }
        else if ( $task == "getDocumentList" )
        {
            $this->_getDocumentList( );
        }
        else if ( $task == "uploadDocument" )
        {
            $this->_uploadDocument( );
        }
        else if ( $task == "exportExcel" )
        {
            $this->_exportExcel( );
        }
        else if ( $task == "getTaskTreeList" )
        {
            $this->_getTaskTreeList( );
        }
    }

    private function _exportExcel( )
    {
        global $CNOA_SESSION;
        $execman = intval( getpar( $_POST, "execman", "" ) );
        $stime = strtotime( getpar( $_POST, "stime", "0000-00-00" )." 00:00:00" );
        $etime = strtotime( getpar( $_POST, "etime", "0000-00-00" )." 23:59:59" );
        $truename = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $execman );
        if ( $etime <= $stime )
        {
            msg::callback( FALSE, lang( "endTimeNoBigStartTime" ) );
        }
        $fileName = "CNOA.TASK-".$uid.date( "Ymd", $stime )."-".date( "Ymd", $etime )."-".string::rands( 10, 2 ).".xlsx";
        $dataInfo = $this->_getExportExcelData( $stime, $etime );
        $excelClass = app::loadapp( "user", "taskExportExcel" );
        $excelClass->init( $dataInfo, $truename, $stime, $etime );
        $excelClass->save( CNOA_PATH_FILE."/common/temp/".$fileName );
        msg::callback( TRUE, makedownloadicon( "{$GLOBALS['URL_FILE']}/common/temp/".$fileName, $fileName, "img" ) );
    }

    private function _loadWatchPage( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $GLOBALS['GLOBALS']['tid'] = intval( getpar( $_POST, "tid", getpar( $_GET, "tid", 0 ) ) );
        $from = getpar( $_GET, "from", "list" );
        if ( $from == "list" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/task/list_watch.htm";
        }
        else if ( $from == "view" )
        {
            $info = $CNOA_DB->db_getone( "*", $this->table_list, "WHERE `tid`='".$GLOBALS['tid']."'" );
            if ( !$info )
            {
                echo lang( "noDataOrSPing" );
                exit( );
            }
            $GLOBALS['GLOBALS']['status'] = $info['status'];
            $GLOBALS['GLOBALS']['from'] = $info['from'];
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/task/view_watch.htm";
        }
        else if ( $from == "discussview" )
        {
            $GLOBALS['GLOBALS']['ttitle'] = getpar( $_GET, "ttitle", "" );
            $GLOBALS['GLOBALS']['did'] = getpar( $_GET, "did", "" );
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/task/discussview.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _getAllUserListsInPermitDeptTree( )
    {
        $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
        echo json_encode( $userList );
        exit( );
    }

    private function _getExportExcelData( $stime, $etime )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $execman = intval( getpar( $_POST, "execman", "" ) );
        $where = "WHERE `execman`='".$execman."' ";
        $where .= "AND `stime`>='".$stime."' AND `stime`<='{$etime}' ";
        $sql = "SELECT  * FROM ".tname( $this->table_list )." ".$where."ORDER BY `stime` ASC ";
        $dblist = array( );
        $queryList = $CNOA_DB->query( $sql );
        $uids = array( );
        while ( $list = $CNOA_DB->get_array( $queryList ) )
        {
            $v = array( );
            $v['tid'] = $list['tid'];
            $v['title'] = $list['title']."\r\n";
            $v['status'] = $list['status'];
            $v['stime'] = date( "Y-m-d", $list['stime'] );
            $v['etime'] = date( "Y-m-d", $list['etime'] );
            $v['uid'] = $list['uid'];
            $v['execman'] = $list['execman'];
            $uids[$list['uid']] = $list['uid'];
            $uids[$list['examapp']] = $list['examapp'];
            $uids[$list['execman']] = $list['execman'];
            $v1 = $v2 = $v3 = array( );
            if ( $list['status'] == 1 )
            {
                $v['statusText'] = lang( "waitingForAccept" );
                if ( $list['execman'] == $uid )
                {
                    $v['title'] = $v['title'].lang( "receiveTaskNotice" );
                }
            }
            else if ( $list['status'] == 2 )
            {
                $v['statusText'] = lang( "pendingApproval" );
                if ( $list['examapp'] == $uid )
                {
                    $v['title'] = $v['title'].lang( "approvelTaskNotice" );
                }
            }
            else if ( $list['status'] == 3 )
            {
                $v['statusText'] = lang( "approvalNotThrough" );
                if ( $list['uid'] == $uid )
                {
                    $v['title'] = $v['title'].lang( "taskEditNotice" );
                }
            }
            else if ( $list['status'] == 4 )
            {
                $v['statusText'] = lang( "rejectReceive" );
                if ( $list['uid'] == $uid )
                {
                    $v['title'] = $v['title'].lang( "taskEditNotice" );
                }
            }
            else if ( $list['status'] == 5 )
            {
                $v['statusText'] = lang( "inProgress" ).( "(".$list['progress']."%)" );
                if ( $list['execman'] == $uid )
                {
                    $v['title'] = $v['title'].lang( "taskReportNotice" );
                    if ( $list['isUrge'] == 1 )
                    {
                        $v['title'] .= "\r\n".lang( "taskCBNotice" );
                    }
                }
                if ( $list['etime'] < $GLOBALS['CNOA_TIMESTAMP'] )
                {
                    $c1 = floor( ( $GLOBALS['CNOA_TIMESTAMP'] - $list['etime'] ) / 3600 / 24 );
                    $c2 = $c1 == 0 ? "" : "\r\n已超期:".$c1."天";
                    $v['statusText'] .= $c2;
                }
            }
            else if ( $list['status'] == 6 )
            {
                $list['progress'] = 100;
                $v['statusText'] = lang( "submitApproval" ).( "(".$list['progress']."%)" );
                if ( $list['uid'] == $uid )
                {
                    $v['title'] = $v['title'].lang( "taskBZNotice" );
                }
            }
            else if ( $list['status'] == 7 )
            {
                $list['progress'] = 100;
                $v['statusText'] = lang( "taskFinish" )." (".$list['point'].lang( "point" ).")";
            }
            else if ( $list['status'] == 8 )
            {
                $list['progress'] = 0;
                $v['statusText'] = lang( "taskRecelled" );
            }
            else if ( $list['status'] == 9 )
            {
                $v['statusText'] = lang( "taskFailed" );
            }
            $v['title'] = $v['title'];
            $v = $this->_intersection( $v, array(
                $v1,
                $v2,
                $v3
            ) );
            $dblist[] = $v;
        }
        $userNames = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uids );
        foreach ( $dblist as $key => $value )
        {
            $dblist[$key]['postter'] = $userNames[$value['uid']]['truename'];
            $dblist[$key]['execman'] = $userNames[$value['execman']]['truename'];
            $dblist[$key]['examapp'] = $userNames[$value['examapp']]['truename'];
        }
        return $dblist;
    }

    private function _getTaskTreeList( )
    {
        global $CNOA_DB;
        $pid = getpar( $_POST, "node", 0 );
        echo json_encode( $this->__getTaskTreeList( ) );
        exit( );
    }

    private function __getTaskTreeList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $start = getpar( $_POST, "start", 0 );
        $rows = 15;
        $uerInfoArr = app::loadapp( "main", "user" )->api_getUserByFids( $GLOBALS['user']['permitArea']['area'], "", TRUE );
        $uidArr = array( 0 );
        foreach ( $uerInfoArr as $k => $v )
        {
            $uidArr[] = $v['uid'];
        }
        $where = "WHERE 1 ";
        $where .= "AND `execman` IN (".implode( ",", $uidArr ).") ";
        $searchKey = array( );
        $searchKey['title'] = getpar( $_POST, "title", "" );
        $searchKey['uid'] = intval( getpar( $_POST, "uid", 0 ) );
        $searchKey['execman'] = intval( getpar( $_POST, "execman", 0 ) );
        $searchKey['examapp'] = intval( getpar( $_POST, "examapp", 0 ) );
        if ( !empty( $searchKey['title'] ) )
        {
            $where .= "AND `title` LIKE '%".$searchKey['title']."%' ";
        }
        if ( $searchKey['uid'] !== 0 )
        {
            $where .= "AND `uid`='".$searchKey['uid']."' ";
        }
        if ( $searchKey['execman'] !== 0 )
        {
            $where .= "AND `execman`='".$searchKey['execman']."' ";
        }
        if ( $searchKey['examapp'] !== 0 )
        {
            $where .= "AND `examapp`='".$searchKey['examapp']."' ";
        }
        $dblist = $CNOA_DB->db_select( "*", $this->table_list, $where."ORDER BY `posttime` DESC " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $uidArr2 = array( 0 );
        foreach ( $dblist as $v )
        {
            $uidArr2[$v['postter']] = $v['uid'];
            $uidArr2[$v['execman']] = $v['execman'];
        }
        $userNames = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr2 );
        foreach ( $dblist as $k => $v )
        {
            if ( $v['status'] == 1 )
            {
                $dblist[$k]['statusText'] = lang( "waitingForAccept" );
                $dblist[$k]['statusColor'] = "#FF0000";
                $dblist[$k]['progress'] = 2;
            }
            else if ( $v['status'] == 2 )
            {
                $dblist[$k]['statusText'] = lang( "pendingApproval" );
                $dblist[$k]['statusColor'] = "#800040";
                $dblist[$k]['progress'] = 2;
            }
            else if ( $v['status'] == 3 )
            {
                $dblist[$k]['statusText'] = lang( "approvalNotThrough" );
                $dblist[$k]['statusColor'] = "#FF0000";
                $dblist[$k]['progress'] = 100;
            }
            else if ( $v['status'] == 4 )
            {
                $dblist[$k]['statusText'] = lang( "rejectReceive" );
                $dblist[$k]['statusColor'] = "#FF8000";
                $dblist[$k]['progress'] = 100;
            }
            else if ( $v['status'] == 5 )
            {
                $dblist[$k]['statusText'] = lang( "inProgress" ).( "(".$v['progress']."%)" );
                $dblist[$k]['statusColor'] = "#008040";
                $dblist[$k]['progress'] = $v['progress'];
                if ( $v['etime'] < $GLOBALS['CNOA_TIMESTAMP'] )
                {
                    $c1 = floor( ( $GLOBALS['CNOA_TIMESTAMP'] - $v['etime'] ) / 3600 / 24 );
                    $c2 = $c1 == 0 ? "" : "<br /><span style='color:red'>已超期:".$c1."天</span>";
                    $dblist[$k]['statusText'] .= $c2;
                }
            }
            else if ( $v['status'] == 6 )
            {
                $v['progress'] = 100;
                $dblist[$k]['statusText'] = lang( "submitApproval" ).( "(".$v['progress']."%)" );
                $dblist[$k]['statusColor'] = "#008040";
                $dblist[$k]['progress'] = $v['progress'];
            }
            else if ( $v['status'] == 7 )
            {
                $v['progress'] = 100;
                $dblist[$k]['statusText'] = lang( "taskFinish" )." (".$v['point'].lang( "point" ).")";
                $dblist[$k]['statusColor'] = "#0000FF";
                $dblist[$k]['progress'] = $v['progress'];
            }
            else if ( $v['status'] == 8 )
            {
                $v['progress'] = 0;
                $dblist[$k]['statusText'] = lang( "taskRecelled" );
                $dblist[$k]['statusColor'] = "#008040";
                $dblist[$k]['progress'] = $v['progress'];
            }
            else if ( $v['status'] == 9 )
            {
                $v['progress'] = 0;
                $dblist[$k]['statusText'] = lang( "taskFailed" );
                $dblist[$k]['statusColor'] = "#808080";
                $dblist[$k]['progress'] = $v['progress'];
            }
        }
        $tmpArr = array( );
        $rootTotal = array( );
        foreach ( $dblist as $k => $v )
        {
            if ( $v['from'] != "mothertask" )
            {
                $rootTotal[] = $v;
                $v['_id'] = $v['tid'];
                $v['_parent'] = NULL;
                $v['_is_leaf'] = FALSE;
                $v['_level'] = 1;
                $v['text'] = $v['title'];
                $v['status'] = $v['status'];
                $v['postter'] = $userNames[$v['uid']]['truename'];
                $v['execman'] = $userNames[$v['execman']]['truename'];
                $v['jixiao'] = $this->__formatJixiao( $v['stime'], $v['etime'], $v['progress'], $v['sttime'], $v['entime'] );
                $v['stime'] = date( "Y-m-d", $v['stime'] );
                $v['etime'] = date( "Y-m-d", $v['etime'] );
            }
            else
            {
                $tempArr[$v['fromid']][] = $v;
                $childTotal[] = $v;
                $v['postter'] = $userNames[$v['uid']]['truename'];
                $v['execman'] = $userNames[$v['execman']]['truename'];
                $v['_id'] = $v['tid'];
                $v['_parent'] = intval( $v['fromid'] );
                $v['_is_leaf'] = TRUE;
                $v['_level'] = 2;
                $v['stime'] = date( "Y-m-d", $v['stime'] );
                $v['etime'] = date( "Y-m-d", $v['etime'] );
            }
            $dblist[$k] = $v;
        }
        foreach ( $dblist as $k => $v )
        {
            if ( $v['from'] != "mothertask" )
            {
                if ( is_array( $tempArr[$v['_id']] ) )
                {
                    $dblist[$k]['_is_leaf'] = FALSE;
                }
                else
                {
                    $dblist[$k]['_is_leaf'] = TRUE;
                }
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $dblist;
        $ds->total = $CNOA_DB->db_getcount( $this->table_list, "WHERE `from` != 'mothertask' " );
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _getJsonDataWatch( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $uid = $CNOA_SESSION->get( "UID" );
        $GLOBALS['GLOBALS']['tid'] = intval( getpar( $_POST, "tid", getpar( $_GET, "tid", 0 ) ) );
        $start = getpar( $_POST, "start", 0 );
        $rows = 15;
        $uerInfoArr = app::loadapp( "main", "user" )->api_getUserByFids( $GLOBALS['user']['permitArea']['area'], "", TRUE );
        $uidArr = array( 0 );
        foreach ( $uerInfoArr as $k => $v )
        {
            $uidArr[] = $v['uid'];
        }
        $where = "WHERE 1 ";
        $where .= "AND `execman` IN (".implode( ",", $uidArr ).") ";
        $sql = "SELECT  * FROM ".tname( $this->table_list )." ".$where.( "AND `from`='mothertask' AND `fromid` = '".$GLOBALS['tid']."' " );
        $dblist = array( );
        $queryList = $CNOA_DB->query( $sql );
        $uids = array( );
        while ( $list = $CNOA_DB->get_array( $queryList ) )
        {
            $v = array( );
            $tipColor = "#FF8080";
            $v['tid'] = $list['tid'];
            $v['title'] = $list['title'];
            $v['status'] = $list['status'];
            $v['stime'] = date( "Y-m-d", $list['stime'] );
            $v['etime'] = date( "Y-m-d", $list['etime'] );
            $v['uid'] = $list['uid'];
            $v['execman'] = $list['execman'];
            $uids[$list['uid']] = $list['uid'];
            $uids[$list['examapp']] = $list['examapp'];
            $uids[$list['execman']] = $list['execman'];
            $v1 = $v2 = $v3 = array( );
            if ( $list['status'] == 1 )
            {
                $v['statusText'] = lang( "waitingForAccept" );
                $v['statusColor'] = "#FF0000";
                $v['progress'] = 2;
            }
            else if ( $list['status'] == 2 )
            {
                $v['statusText'] = lang( "pendingApproval" );
                $v['statusColor'] = "#800040";
                $v['progress'] = 2;
            }
            else if ( $list['status'] == 3 )
            {
                $v['statusText'] = lang( "approvalNotThrough" );
                $v['statusColor'] = "#FF0000";
                $v['progress'] = 100;
            }
            else if ( $list['status'] == 4 )
            {
                $v['statusText'] = lang( "rejectReceive" );
                $v['statusColor'] = "#FF8000";
                $v['progress'] = 100;
            }
            else if ( $list['status'] == 5 )
            {
                $v['statusText'] = lang( "inProgress" ).( "(".$list['progress']."%)" );
                $v['statusColor'] = "#008040";
                $v['progress'] = $list['progress'];
                if ( $list['etime'] < $GLOBALS['CNOA_TIMESTAMP'] )
                {
                    $c1 = floor( ( $GLOBALS['CNOA_TIMESTAMP'] - $list['etime'] ) / 3600 / 24 );
                    $c2 = $c1 == 0 ? "" : "<br /><span style='color:red'>已超期:".$c1."天</span>";
                    $v['statusText'] .= $c2;
                }
            }
            else if ( $list['status'] == 6 )
            {
                $list['progress'] = 100;
                $v['statusText'] = lang( "submitApproval" ).( "(".$list['progress']."%)" );
                $v['statusColor'] = "#008040";
                $v['progress'] = $list['progress'];
            }
            else if ( $list['status'] == 7 )
            {
                $list['progress'] = 100;
                $v['statusText'] = lang( "taskFinish" )." (".$list['point'].lang( "point" ).")";
                $v['statusColor'] = "#0000FF";
                $v['progress'] = $list['progress'];
            }
            else if ( $list['status'] == 8 )
            {
                $list['progress'] = 0;
                $v['statusText'] = lang( "taskRecelled" );
                $v['statusColor'] = "#008040";
                $v['progress'] = $list['progress'];
            }
            else if ( $list['status'] == 9 )
            {
                $list['progress'] = 0;
                $v['statusText'] = lang( "taskFailed" );
                $v['statusColor'] = "#808080";
                $v['progress'] = $list['progress'];
            }
            $v['title'] = $v['title']."&nbsp;";
            $v = $this->_intersection( $v, array(
                $v1,
                $v2,
                $v3
            ) );
            $v['jixiao'] = $this->__formatJixiao( $list['stime'], $list['etime'], $list['progress'], $list['sttime'], $list['entime'] );
            $dblist[] = $v;
        }
        $userNames = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uids );
        foreach ( $dblist as $key => $value )
        {
            $dblist[$key]['postter'] = $userNames[$value['uid']]['truename'];
            $dblist[$key]['execman'] = $userNames[$value['execman']]['truename'];
            $dblist[$key]['examapp'] = $userNames[$value['examapp']]['truename'];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function __formatJixiao( $stime, $etime, $progress, $sttime, $entime = "" )
    {
        $plan = $etime - $stime;
        if ( $plan == 0 )
        {
            return "";
        }
        if ( empty( $sttime ) )
        {
            return "";
        }
        if ( empty( $entime ) )
        {
            $now = $GLOBALS['CNOA_TIMESTAMP'] - $stime;
            if ( !( $plan < $now ) )
            {
                $p_rate = number_format( $now / $plan, 2 );
                $rate = $progress - $p_rate;
                if ( $rate < 0 )
                {
                    return "<span class='cnoa_color_red'>".$rate."%</span>";
                }
                return "<span class='cnoa_color_green'>".$rate."%</span>";
            }
        }
        else
        {
            $now = $entime - $sttime;
            $rate = $now / $plan;
            if ( $rate < 0 )
            {
                return "<span class='cnoa_color_red'>".$rate."%</span>";
            }
            return "<span class='cnoa_color_green'>".$rate."%</span>";
        }
    }

    private function _viewTask( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $tid = getpar( $_GET, "tid", 0 );
        $uid = $CNOA_SESSION->get( "UID" );
        $info = $CNOA_DB->db_getone( "*", $this->table_list, "WHERE `tid`='".$tid."'" );
        ( );
        $dataStore = new dataStore( );
        $uids = array( );
        if ( !$info )
        {
            $dataStore->success = FALSE;
            $dataStore->msg = lang( "noDataOrSPing" );
            echo $dataStore->makeJsonData( );
            exit( );
        }
        $info['posttime'] = date( "Y-m-d H:i", $info['posttime'] );
        $info['stime'] = date( "Y-m-d", $info['stime'] );
        $info['etime'] = date( "Y-m-d", $info['etime'] );
        $info['content'] = nl2br( $info['content'] );
        $info['point'] = $info['status'] == 7 ? $info['point'] : "--";
        $info['sttime'] = $info['sttime'] == 0 ? "--" : date( "Y-m-d H:i", $info['sttime'] );
        $info['entime'] = $info['entime'] == 0 ? "--" : date( "Y-m-d H:i", $info['entime'] );
        $info['eenable'] = FALSE;
        $info['denable'] = FALSE;
        $info['renable'] = FALSE;
        $info['aenable'] = FALSE;
        $info['benable'] = FALSE;
        $info['uenable'] = FALSE;
        $info['fenable'] = FALSE;
        $info['showTip'] = FALSE;
        $info['tip'] = "";
        $info['tipJob'] = "";
        $uids[$info['uid']] = $info['uid'];
        $uids[$info['examapp']] = $info['examapp'];
        $uids[$info['execman']] = $info['execman'];
        $participantDb = $CNOA_DB->db_select( "*", $this->table_participant, "WHERE `tid`='".$tid."'" );
        $participantDb = is_array( $participantDb ) ? $participantDb : array( );
        $participantArr = array( );
        foreach ( $participantDb as $v )
        {
            $uids[$v['uid']] = $v['uid'];
            $participantArr[] = $v['uid'];
        }
        unset( $v );
        ( );
        $fs = new fs( );
        $info['attach'] = json_decode( $info['attach'], TRUE );
        $info['attachCount'] = !$info['attach'] ? 0 : count( $info['attach'] );
        $info['attach'] = $fs->getDownLoadItems4normal( $info['attach'], TRUE );
        if ( $info['needexamapp'] == 1 && $info['status'] == 2 && !( $info['uid'] == $uid ) )
        {
        }
        if ( !( $info['uid'] == $uid ) || !( $info['examapp'] == $uid ) || !( $info['execman'] == $uid ) )
        {
            in_array( $uid, $participantArr );
        }
        $v1 = $v2 = $v3 = array( );
        if ( $info['status'] == 1 )
        {
            $info['statusText'] = lang( "waitingForAccept" );
            $info['statusColor'] = "#FF0000";
            $info['progress'] = 0;
        }
        else if ( $info['status'] == 2 )
        {
            $info['statusText'] = lang( "pendingApproval" );
            $info['statusColor'] = "#800040";
            $info['progress'] = 0;
        }
        else if ( $info['status'] == 3 )
        {
            $info['statusText'] = lang( "approvalNotThrough" );
            $info['statusColor'] = "#FF0000";
            $info['progress'] = 0;
        }
        else if ( $info['status'] == 4 )
        {
            $info['statusText'] = lang( "rejectReceive" );
            $info['statusColor'] = "#FF8000";
            $info['progress'] = 0;
        }
        else if ( $info['status'] == 5 )
        {
            $info['statusText'] = lang( "inProgress" ).( "(".$info['progress']."%)" );
            $info['statusColor'] = "#008000";
            $info['progress'] = $info['progress'];
        }
        else if ( $info['status'] == 6 )
        {
            $info['progress'] = 100;
            $info['statusText'] = lang( "submitApproval" ).( "(".$info['progress']."%)" );
            $info['statusColor'] = "#008000";
            $info['progress'] = $info['progress'];
        }
        else if ( $info['status'] == 7 )
        {
            $info['progress'] = 100;
            $info['statusText'] = lang( "taskFinish" );
            $info['statusColor'] = "#0000FF";
            $info['progress'] = $info['progress'];
            $info['point'] = $info['point'].lang( "point" );
        }
        else if ( $info['status'] == 8 )
        {
            $info['progress'] = 0;
            $info['statusText'] = lang( "taskRecelled" );
            $info['statusColor'] = "#000000";
            $info['progress'] = $info['progress'];
        }
        else if ( $info['status'] == 9 )
        {
            $info['progress'] = 0;
            $info['statusText'] = lang( "taskFailed" );
            $info['statusColor'] = "#808080";
            $info['progress'] = $info['progress'];
        }
        $info = $this->_intersection2( $info, array(
            $v1,
            $v2,
            $v3
        ) );
        $userNames = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uids );
        foreach ( $participantArr as $key => $value )
        {
            $participantArr[$key] = "<a>".$userNames[$value]['truename']."</a>";
        }
        unset( $key );
        unset( $value );
        $info['postter'] = $userNames[$info['uid']]['truename'];
        $info['execman'] = $userNames[$info['execman']]['truename'];
        $info['examapp'] = $info['examapp'] == 0 ? "--" : $userNames[$info['examapp']]['truename'];
        $info['participant'] = implode( "&nbsp;", $participantArr );
        $info['participant'] = $info['participant'] == "<a></a>" ? "--" : $info['participant'];
        $dataStore->data = $info;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getEventList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $uid = $CNOA_SESSION->get( "UID" );
        $tid = getpar( $_GET, "tid", 0 );
        $dblist = $CNOA_DB->db_select( "*", $this->table_event, "WHERE `tid`='".$tid."' ORDER BY `id` DESC" );
        if ( is_array( $dblist ) )
        {
            foreach ( $dblist as $k => $v )
            {
                $dblist[$k]['typename'] = $this->eventType[$v['type']];
                $dblist[$k]['user'] = $v['truename']." (".timeformat( $v['posttime'] ).")";
                $dblist[$k]['posttime'] = date( "Y-m-d H:i", $v['posttime'] );
            }
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = 0;
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getDiscussList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $tid = getpar( $_GET, "tid", 0 );
        $dblist = $CNOA_DB->db_select( "*", $this->table_discuss_list, "WHERE `tid`='".$tid."' ORDER BY `id` DESC" );
        if ( is_array( $dblist ) )
        {
            foreach ( $dblist as $k => $v )
            {
                $dblist[$k]['content'] = string::cut( strip_tags( $v['content'] ), 150 );
                $dblist[$k]['user'] = app::loadapp( "main", "user" )->api_getUserNameByUid( $v['uid'] );
                $dblist[$k]['posttime'] = date( "Y-m-d H:i", $v['posttime'] );
            }
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = 0;
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getDiscussInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $tid = getpar( $_GET, "tid", 0 );
        $did = getpar( $_GET, "did", 0 );
        $data = array( );
        $list = array( );
        $data['title'] = "只允许布置人/审批人/负责人/参与人列表(未做)";
        $info = $CNOA_DB->db_getone( "*", $this->table_discuss_list, "WHERE `tid`='".$tid."' AND `id`='{$did}'" );
        $data['title'] = $info['title'];
        $list[0]['content'] = nl2br( $info['content'] );
        $list[0]['user'] = $info['uid'];
        $list[0]['posttime'] = date( "Y年m月d日 H:i", $info['posttime'] );
        $dblist = $CNOA_DB->db_select( "*", $this->table_discuss_content, "WHERE `fid`='".$did."' ORDER BY `id` ASC" );
        if ( is_array( $dblist ) )
        {
            $tmp = array( );
            foreach ( $dblist as $k => $v )
            {
                $tmp['user'] = $v['uid'];
                $tmp['content'] = nl2br( $v['content'] );
                $tmp['posttime'] = date( "Y年m月d日 H:i", $v['posttime'] );
                $list[] = $tmp;
            }
        }
        unset( $tmp );
        $uids = array( );
        $deptids = array( );
        foreach ( $list as $v )
        {
            $uids[] = $v['user'];
        }
        unset( $v );
        $users = app::loadapp( "main", "user" )->api_getUserInfoByUids( $uids );
        $userList = array( );
        $users = is_array( $users ) ? $users : array( );
        foreach ( $users as $v )
        {
            $userList[$v['uid']]['face'] = $v['faceUrl'];
            $userList[$v['uid']]['truename'] = $v['truename'];
            $userList[$v['uid']]['deptId'] = $v['deptId'];
            $deptids[] = $v['deptId'];
        }
        unset( $v );
        $deptList = app::loadapp( "main", "struct" )->api_getNamesByIds( $deptids );
        foreach ( $userList as $key => $v )
        {
            $userList[$key]['dept'] = $deptList[$userList[$key]['deptId']];
        }
        unset( $v );
        unset( $key );
        foreach ( $list as $key => $v )
        {
            $list[$key]['user'] = $userList[$v['user']]['truename'];
            $list[$key]['dept'] = $userList[$v['user']]['dept'];
            $list[$key]['face'] = $userList[$v['user']]['face'];
        }
        unset( $v );
        unset( $key );
        $data['list'] = $list;
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = 0;
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _addDiscuss( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $data['uid'] = $CNOA_SESSION->get( "UID" );
        $data['tid'] = getpar( $_GET, "tid", 0 );
        $data['content'] = getpar( $_POST, "content", "", 1, 0 );
        $data['title'] = getpar( $_POST, "title", "" );
        $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $CNOA_DB->db_insert( $data, $this->table_discuss_list );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _addDiscussComment( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $data['uid'] = $CNOA_SESSION->get( "UID" );
        $data['fid'] = getpar( $_GET, "did", 0 );
        $data['content'] = getpar( $_POST, "content", "", 0 );
        $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $CNOA_DB->db_insert( $data, $this->table_discuss_content );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getDocumentList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $tid = getpar( $_GET, "tid", 0 );
        $info = $CNOA_DB->db_select( "*", $this->table_document, "WHERE `tid`='".$tid."' ORDER By `posttime` DESC" );
        $info = is_array( $info ) ? $info : array( );
        foreach ( $info as $k => $v )
        {
            ( );
            $fs = new fs( );
            $info[$k]['attach'] = json_decode( $info[$k]['attach'], TRUE );
            $info[$k]['attachCount'] = !$info[$k]['attach'] ? 0 : count( $info[$k]['attach'] );
            $info[$k]['attach'] = $fs->getDownLoadItems4normal( $info[$k]['attach'], TRUE );
            $info[$k]['user'] = app::loadapp( "main", "user" )->api_getUserNameByUid( $v['uid'] );
            $info[$k]['posttime'] = date( "Y-m-d H:i", $v['posttime'] );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $info;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _uploadDocument( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $data['tid'] = getpar( $_GET, "tid", 0 );
        $data['uid'] = $CNOA_SESSION->get( "UID" );
        $data['content'] = getpar( $_POST, "content", "", 1 );
        $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        ( );
        $fs = new fs( );
        $filesUpload = getpar( $_POST, "filesUpload", array( ) );
        if ( empty( $filesUpload ) )
        {
            msg::callback( FALSE, lang( "selectAttachToUpload" ) );
        }
        $attch = $fs->add( $filesUpload, 4 );
        $data['attach'] = json_encode( $attch );
        $tid = $CNOA_DB->db_insert( $data, $this->table_document );
        msg::callback( TRUE, lang( "uploadSucess" ) );
        exit( );
    }

    private function _eventAdd( $tid, $title, $content, $type, $type2 )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $truename = $CNOA_SESSION->get( "TRUENAME" );
        $data['tid'] = $tid;
        $data['type'] = $type;
        $data['type2'] = $type2;
        $data['title'] = $title;
        $data['content'] = $content;
        $data['uid'] = $uid;
        $data['truename'] = $truename;
        $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        return $CNOA_DB->db_insert( $data, $this->table_event );
    }

    private function _intersection( $vSource, $vArray )
    {
        $f['eenable'] = FALSE;
        $f['denable'] = FALSE;
        $f['repealenable'] = FALSE;
        $f['urgeenable'] = FALSE;
        $f['failenable'] = FALSE;
        $vArray = is_array( $vArray ) ? $vArray : array( );
        foreach ( $vArray as $k => $v )
        {
            if ( $v['eenable'] )
            {
                $f['eenable'] = TRUE;
            }
            if ( $v['denable'] )
            {
                $f['denable'] = TRUE;
            }
            if ( $v['repealenable'] )
            {
                $f['repealenable'] = TRUE;
            }
            if ( $v['urgeenable'] )
            {
                $f['urgeenable'] = TRUE;
            }
            if ( $v['failenable'] )
            {
                $f['failenable'] = TRUE;
            }
        }
        $vSource['eenable'] = $f['eenable'];
        $vSource['denable'] = $f['denable'];
        $vSource['repealenable'] = $f['repealenable'];
        $vSource['urgeenable'] = $f['urgeenable'];
        $vSource['failenable'] = $f['failenable'];
        return $vSource;
    }

    private function _intersection2( $vSource, $vArray )
    {
        $vArray = is_array( $vArray ) ? $vArray : array( );
        foreach ( $vArray as $k => $v )
        {
            if ( $v['eenable'] )
            {
                $f['eenable'] = TRUE;
            }
            if ( $v['denable'] )
            {
                $f['denable'] = TRUE;
            }
            if ( $v['renable'] )
            {
                $f['renable'] = TRUE;
            }
            if ( $v['aenable'] )
            {
                $f['aenable'] = TRUE;
            }
            if ( $v['benable'] )
            {
                $f['benable'] = TRUE;
            }
            if ( $v['uenable'] )
            {
                $f['uenable'] = TRUE;
            }
            if ( $v['fenable'] )
            {
                $f['fenable'] = TRUE;
            }
        }
        $vSource['eenable'] = $f['eenable'];
        $vSource['denable'] = $f['denable'];
        $vSource['renable'] = $f['renable'];
        $vSource['aenable'] = $f['aenable'];
        $vSource['benable'] = $f['benable'];
        $vSource['uenable'] = $f['uenable'];
        $vSource['fenable'] = $f['fenable'];
        return $vSource;
    }

}

?>
