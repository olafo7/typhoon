<?php
//decode by qq2859470

class userTaskTotal extends model
{

    private $table_list = "user_task_list";
    private $rows = "15";

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "getJsonData" :
            $this->_getJsonData( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $userList );
            exit( );
        case "viewTask" :
            app::loadapp( "user", "taskDefault" )->api_viewTask( );
            break;
        case "exportExcel" :
            $this->_exportExcel( );
            break;
        case "getTaskDetailList" :
            $this->_getTaskDetailList( );
        }
    }

    public function _loadPage( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        $from = getpar( $_GET, "from", "view" );
        $GLOBALS['GLOBALS']['tid'] = getpar( $_GET, "tid", 0 );
        switch ( $from )
        {
        case "view" :
            $info = $CNOA_DB->db_getone( "*", $this->table_list, "WHERE `tid`='".$GLOBALS['tid']."'" );
            if ( !$info )
            {
                echo lang( "noDataOrSPing" );
                exit( );
            }
            $GLOBALS['GLOBALS']['status'] = $info['status'];
            $GLOBALS['GLOBALS']['user_task']['type'] = "total";
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/task/view.htm";
            break;
        case "viewTotalDetial" :
            $GLOBALS['GLOBALS']['status'] = getpar( $_GET, "status", "" );
            $GLOBALS['GLOBALS']['execman'] = getpar( $_GET, "execman", 0 );
            $GLOBALS['GLOBALS']['uname'] = app::loadapp( "main", "user" )->api_getUserNameByUids( $GLOBALS['execman'] );
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/task/total_detail.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _exportExcel( )
    {
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $truename = $CNOA_SESSION->get( "TRUENAME" );
        $stime = strtotime( getpar( $_POST, "stime", "0000-00-00" )." 00:00:00" );
        $etime = strtotime( getpar( $_POST, "etime", "0000-00-00" )." 23:59:59" );
        if ( $etime <= $stime )
        {
            msg::callback( FALSE, lang( "endTimeNoBigStartTime" ) );
        }
        $fileName = "CNOA.TASK-".$uid.date( "Ymd", $stime )."-".date( "Ymd", $etime )."-".string::rands( 10, 2 ).".xlsx";
        $dataInfo = $this->_getJsonData( "excel" );
        $excelClass = app::loadapp( "user", "taskTotalExportExcel" );
        $excelClass->init( $dataInfo, $truename, $stime, $etime );
        $excelClass->save( CNOA_PATH_FILE."/common/temp/".$fileName );
        msg::callback( TRUE, makedownloadicon( "{$GLOBALS['URL_FILE']}/common/temp/".$fileName, $fileName, "img" ) );
    }

    private function _getJsonData( $type = "" )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $start = getpar( $_POST, "start", 0 );
        $s_execman = getpar( $_POST, "execman", 0 );
        $s_stime = getpar( $_POST, "stime", 0 );
        $s_etime = getpar( $_POST, "etime", 0 );
        if ( $type != "excel" )
        {
            $LIMIT = "LIMIT ".$start.", {$this->rows}";
        }
        $manArr = array( );
        if ( !empty( $s_execman ) )
        {
            $manArr[] = $s_execman;
        }
        else
        {
            $mans = $CNOA_DB->db_select( array( "execman" ), $this->table_list, "GROUP BY `execman` ORDER BY `execman` ASC ".$LIMIT );
            if ( !is_array( $mans ) )
            {
                $mans = array( );
            }
            foreach ( $mans as $k => $v )
            {
                $manArr[] = $v['execman'];
            }
        }
        $WHERE = "WHERE 1 ";
        if ( !empty( $s_stime ) && !empty( $s_etime ) )
        {
            $s_stime = strtotime( $s_stime." 00:00:00" );
            $s_etime = strtotime( $s_etime." 23:59:59" );
            if ( $s_stime < $s_etime )
            {
                $WHERE .= "AND `sttime`>'".$s_stime."' AND `entime`<'{$s_etime}' ";
            }
            else
            {
                msg::callback( FALSE, lang( "endTimeNoBigStartTime" ) );
            }
        }
        $truenameArr = app::loadapp( "main", "user" )->api_getUserNamesByUids( $manArr );
        $tidList = $CNOA_DB->db_select( array( "tid", "execman" ), $this->table_list, $WHERE."AND `execman` IN (".implode( ",", $manArr ).")" );
        if ( !is_array( $tidList ) )
        {
            $tidList = array( );
        }
        if ( empty( $tidList ) )
        {
            ( );
            $dataStore = new dataStore( );
            $dataStore->data = array( );
            echo $dataStore->makeJsonData( );
            exit( );
        }
        $tidArr = array( 0 );
        foreach ( $tidList as $k => $v )
        {
            $tidArr[$v['execman']][] = $v['tid'];
        }
        $uidArr = array( );
        $deptArr = array( 0 );
        foreach ( $manArr as $k => $v )
        {
            $uidArr[] = $v['execman'];
            if ( empty( $tidArr[$v] ) )
            {
                $tidArr[$v] = array( 0 );
            }
            $WHERETID = "WHERE 1 AND `tid` IN (".implode( ",", $tidArr[$v] ).") ";
            $data['name'] = $truenameArr[$v]['truename'];
            $data['execman'] = $v;
            $data['deptId'] = $truenameArr[$v]['deptId'];
            $deptArr[] = $data['deptId'];
            $jixiaoArr = $this->__formatJixiao( $tidArr[$v] );
            $data['jixiaofast'] = number_format( $jixiaoArr['fast'], 4 );
            $data['jixiaoslow'] = number_format( $jixiaoArr['slow'], 4 );
            $data['jixiaoall'] = number_format( $jixiaoArr['total'], 4 );
            $data['jixiaoaver'] = number_format( $jixiaoArr['aver'], 4 );
            $data['all'] = $CNOA_DB->db_getcount( $this->table_list, $WHERETID );
            if ( 0 < $data['all'] )
            {
                $data['all'] = "<a style=\"color: #FF0000\">".$data['all']."</a>";
            }
            $data['done'] = $CNOA_DB->db_getcount( $this->table_list, $WHERETID."AND `status`='7' " );
            if ( 0 < $data['done'] )
            {
                $data['done'] = "<a style=\"color: #FF0000\">".$data['done']."</a>";
            }
            $data['overdone'] = $CNOA_DB->db_getcount( $this->table_list, $WHERETID."AND `overdone`='1' AND `status`='7'" );
            if ( 0 < $data['overdone'] )
            {
                $data['overdone'] = "<a style=\"color: #FF0000\">".$data['overdone']."</a>";
            }
            $data['overdoing'] = $CNOA_DB->db_getcount( $this->table_list, $WHERETID.( "AND `status`='5' AND `etime`<'".$GLOBALS['CNOA_TIMESTAMP']."' " ) );
            if ( 0 < $data['overdoing'] )
            {
                $data['overdoing'] = "<a style=\"color: #FF0000\">".$data['overdoing']."</a>";
            }
            $data['doing'] = $CNOA_DB->db_getcount( $this->table_list, $WHERETID."AND `status`='5' " );
            if ( 0 < $data['doing'] )
            {
                $data['doing'] = "<a style=\"color: #FF0000\">".$data['doing']."</a>";
            }
            $data['cancel'] = $CNOA_DB->db_getcount( $this->table_list, $WHERETID."AND `status`='8' " );
            if ( 0 < $data['cancel'] )
            {
                $data['cancel'] = "<a style=\"color: #FF0000\">".$data['cancel']."</a>";
            }
            $data['wait'] = $CNOA_DB->db_getcount( $this->table_list, $WHERETID."AND `status`='1' " );
            if ( 0 < $data['wait'] )
            {
                $data['wait'] = "<a style=\"color: #FF0000\">".$data['wait']."</a>";
            }
            $data['refuse'] = $CNOA_DB->db_getcount( $this->table_list, $WHERETID."AND `status`='4' " );
            if ( 0 < $data['refuse'] )
            {
                $data['refuse'] = "<a style=\"color: #FF0000\">".$data['refuse']."</a>";
            }
            $dblist[] = $data;
        }
        $deptInfoArr = app::loadapp( "main", "struct" )->api_getNamesByIds( $deptArr );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['dept'] = $deptInfoArr[$v['deptId']];
        }
        if ( $type == "excel" )
        {
            return $dblist;
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        $dataStore->total = $CNOA_DB->db_getcount( $this->table_list, "GROUP BY `execman`" );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function __formatDept( $uidArr )
    {
        $allDept = app::loadapp( "main", "struct" )->api_getArrayList( );
        foreach ( $uidArr as $k => $v )
        {
        }
        return $dept;
    }

    private function __formatJixiao( $tidArr )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        if ( empty( $tidArr ) )
        {
            $tidArr = array( 0 );
        }
        $dblist = $CNOA_DB->db_select( array( "stime", "etime", "sttime", "entime" ), $this->table_list, "WHERE `tid` IN (".implode( ",", $tidArr ).")" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $v )
        {
            if ( !empty( $v['sttime'] ) )
            {
                if ( !empty( $v['entime'] ) )
                {
                    $plan = $v['etime'] - $v['stime'];
                    $true = $v['entime'] - $v['sttime'];
                    $count = $plan / $true;
                    if ( $count < 1 )
                    {
                        $data['slow'] += $count;
                    }
                    else
                    {
                        $data['fast'] += $count;
                    }
                    $data['total'] += $count;
                }
            }
        }
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        if ( array_sum( $data ) != 0 )
        {
            $data['aver'] = $data['total'] / count( $dblist );
            return $data;
        }
        $data = array( "aver" => "", "fast" => "", "slow" => "", "total" => "" );
        return $data;
    }

    private function __formatJixiaoSlow( )
    {
    }

    private function __formatJixiaoAll( )
    {
    }

    private function __formatJixiaoAver( )
    {
    }

    private function _getJsonData2( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $start = getpar( $_POST, "start", 0 );
        $storeType = getpar( $_POST, "storeType", "doing" );
        $WHERE = "WHERE 1 ";
        switch ( $storeType )
        {
        case "doing" :
            $WHERE .= "AND `status`='5' ";
            break;
        case "over" :
        case "already" :
            $WHERE .= "AND `status`='7' ";
            break;
        case "all" :
        }
        $s_title = getpar( $_POST, "title", "" );
        $s_uid = getpar( $_POST, "uid", 0 );
        $s_execman = getpar( $_POST, "execman", 0 );
        if ( !empty( $s_title ) )
        {
            $WHERE .= "AND `title` LIKE '%".$s_title."%' ";
        }
        else if ( !empty( $s_uid ) )
        {
            $WHERE .= "AND `uid` = '".$s_uid."' ";
        }
        else if ( !empty( $s_execman ) )
        {
            $WHERE .= "AND `execman` = '".$s_execman."' ";
        }
        $dblist = $CNOA_DB->db_select( "*", $this->table_list, $WHERE.( "ORDER BY `execman` ASC, `tid` DESC LIMIT ".$start.", {$this->rows}" ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $execmanArr = array( );
        $uidArr = array( );
        $dataList = array( );
        foreach ( $dblist as $k => $v )
        {
            if ( $storeType == "over" )
            {
                if ( !( $v['entime'] < $v['etime'] ) && !( $v['entime'] != 0 ) )
                {
                    if ( $GLOBALS['CNOA_TIMESTAMP'] < $v['etime'] && $v['entime'] == 0 )
                    {
                        break;
                    }
                }
                else
                {
                }
            }
            else
            {
                $data = $v;
                $jixiao = $this->__formatJixiao( $v['stime'], $v['etime'], $v['sttime'], $v['entime'] );
                $data['jixiao'] = $jixiao;
                if ( !empty( $jixiao ) )
                {
                    $jixiaoArr[$v['execman']][] = $jixiao;
                }
                $data['p_stime'] = formatdate( $v['stime'], "Y-m-d H:i" );
                $data['p_etime'] = formatdate( $v['etime'], "Y-m-d H:i" );
                $data['t_stime'] = formatdate( $v['sttime'], "Y-m-d H:i" );
                $data['t_etime'] = formatdate( $v['entime'], "Y-m-d H:i" );
                $execmanArr[] = $v['execman'];
                $uidArr[] = $v['uid'];
                if ( $v['status'] == 1 )
                {
                    $data['statusText'] = lang( "waitingForAccept" );
                    $data['statusColor'] = "#FF0000";
                    $data['progress'] = 2;
                }
                else if ( $v['status'] == 2 )
                {
                    $data['statusText'] = lang( "pendingApproval" );
                    $data['statusColor'] = "#800040";
                    $data['progress'] = 2;
                }
                else if ( $v['status'] == 3 )
                {
                    $data['statusText'] = lang( "approvalNotThrough" );
                    $data['statusColor'] = "#FF0000";
                    $data['progress'] = 100;
                }
                else if ( $v['status'] == 4 )
                {
                    $data['statusText'] = lang( "rejectReceive" );
                    $data['statusColor'] = "#FF8000";
                    $data['progress'] = 100;
                }
                else if ( $v['status'] == 5 )
                {
                    $data['statusText'] = lang( "inProgress" ).( "(".$v['progress']."%)" );
                    $data['statusColor'] = "#008040";
                    $data['progress'] = $v['progress'];
                    if ( $v['etime'] < $GLOBALS['CNOA_TIMESTAMP'] )
                    {
                        $c1 = floor( ( $GLOBALS['CNOA_TIMESTAMP'] - $v['etime'] ) / 3600 / 24 );
                        $c2 = $c1 == 0 ? "" : "<br /><span style='color:red'>".lang( "chaoqiTian", $c1 )."</span>";
                        $data['statusText'] .= $c2;
                    }
                }
                else if ( $v['status'] == 6 )
                {
                    $v['progress'] = 100;
                    $data['statusText'] = "提交审核(".$v['progress']."%)";
                    $data['statusColor'] = "#008040";
                    $data['progress'] = $v['progress'];
                }
                else if ( $v['status'] == 7 )
                {
                    $v['progress'] = 100;
                    if ( $v['etime'] < $GLOBALS['CNOA_TIMESTAMP'] && $v['entime'] == 0 )
                    {
                        $c1 = floor( ( $GLOBALS['CNOA_TIMESTAMP'] - $v['etime'] ) / 3600 / 24 );
                        $c2 = $c1 == 0 ? "" : "<br /><span style='color:red'>".lang( "chaoqiTian", $c1 )."</span>";
                        $data['statusText'] .= $c2;
                    }
                    else if ( $v['etime'] < $v['entime'] )
                    {
                        $data['statusText'] = lang( "taskFinish" )." (".$v['point']." ".lang( "point" ).")";
                        $c1 = floor( ( $v['entime'] - $v['etime'] ) / 3600 / 24 );
                        $c2 = $c1 == 0 ? "<br /><span style='color:red'>".lang( "chaoqiTian2" )."</span>" : "<br /><span style='color:red'>".lang( "chaoqiTian", $c1 )."</span>";
                        $data['statusText'] .= $c2;
                        $data['statusColor'] = "#0000FF";
                        $data['progress'] = $v['progress'];
                    }
                    else
                    {
                        $data['statusText'] = lang( "taskFinish" )." (".$v['point']." ".lang( "point" ).")";
                        $data['statusColor'] = "#0000FF";
                        $data['progress'] = $v['progress'];
                    }
                }
                else if ( $v['status'] == 8 )
                {
                    $v['progress'] = 0;
                    $data['statusText'] = lang( "taskRecelled" );
                    $data['statusColor'] = "#008040";
                    $data['progress'] = $v['progress'];
                }
                else if ( $v['status'] == 9 )
                {
                    $v['progress'] = 0;
                    $data['statusText'] = lang( "taskFailed" );
                    $data['statusColor'] = "#808080";
                    $data['progress'] = $v['progress'];
                }
                $dataList[] = $data;
            }
        }
        $truenameExe = app::loadapp( "main", "user" )->api_getUserNamesByUids( $execmanArr );
        $truenameUid = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr );
        foreach ( $dataList as $k => $v )
        {
            $dataList[$k]['postter'] = $truenameUid[$v['uid']]['truename'];
            if ( is_array( $jixiaoArr ) )
            {
                $avi = lang( "pjxw" ).": ".number_format( array_sum( $jixiaoArr[$v['execman']] ) / count( $jixiaoArr[$v['execman']] ), 4 );
            }
            else
            {
                $avi = "";
            }
            $dataList[$k]['info'] = $truenameExe[$v['execman']]['truename'].$avi;
            if ( $v['jixiao'] < 1 )
            {
                $dataList[$k]['jixiao'] = "<span class='cnoa_color_red'>".$v['jixiao']."</span>";
            }
            else
            {
                $dataList[$k]['jixiao'] = "<span class='cnoa_color_green'>".$v['jixiao']."</span>";
            }
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = $CNOA_DB->db_getcount( $this->table_list, $WHERE );
        $dataStore->data = $dataList;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getTaskDetailList( )
    {
        $execman = getpar( $_GET, "execman", 0 );
        $status = getpar( $_GET, "status", "" );
        app::loadapp( "user", "taskDefault" )->api_getTaskDetailList( $execman, $status );
        exit( );
    }

}

?>
