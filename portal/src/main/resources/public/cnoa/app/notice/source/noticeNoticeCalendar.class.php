<?php
//decode by qq2859470

class noticeNoticeCalendar extends model
{

    private $t_menu_list = "system_notice_menu_list";
    private $t_notice = "system_notice_list";
    private $t_calendar = "system_notice_calendar";
    private $t_color_type = "system_notice_calendar_color";
    private $t_calendar_share = "system_notice_calendar_share";
    private $t_calendar_period = "system_notice_calendar_period";
    private $t_notice_except = "system_period_notice_except";
    private $t_period_day = "system_period_notice_day";
    private $t_period_week = "system_period_notice_week";
    private $t_period_month = "system_period_notice_month";
    protected $t_set_flow = "wf_s_flow";
    protected $t_set_sort = "wf_s_sort";
    protected $t_set_sort_permit = "wf_s_sort_permit";
    protected $t_set_sort_forbid = "wf_s_sort_forbid";
    protected $t_use_flow = "wf_u_flow";

    public function run( )
    {
        $task = getpar( $_GET, "task", "loadpage" );
        switch ( $task )
        {
        case "loadpage" :
            $this->_loadpage( );
            break;
        case "getJsonData" :
            $this->_getJsonData( );
            break;
        case "addCalendar" :
            $this->_addCalendar( );
            break;
        case "resizeDate" :
            $this->_resizeDate( );
            break;
        case "getColorData" :
            $this->_getColorData( );
            break;
        case "updateColorType" :
            $this->_updateColorType( );
            break;
        case "loadEditData" :
            $this->_loadEditData( );
            break;
        case "deleteCid" :
            $this->_deleteCid( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $userList );
            exit( );
        case "deletePeriodCal" :
            $this->_deletePeriodCal( );
            break;
        case "addForOtherFun" :
            $this->_addForOtherFun( );
            break;
        case "getFlowListInSort" :
            $this->getFlowListInSort( );
            break;
        case "getSortTree" :
            $this->api_getSortTree( "faqi" );
            break;
        case "lhcGetFlow" :
            $this->_lhcGetFlow( );
        }
    }

    private function _loadpage( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        $from = getpar( $_GET, "from", "" );
        $flowId = getpar( $_GET, "flowId", "" );
        $GLOBALS['GLOBALS']['CALENDAR']['flowId'] = $flowId;
        if ( $from == "notice" )
        {
            $cid = getpar( $_GET, "cid", 0 );
            $GLOBALS['GLOBALS']['CALENDAR']['FROM'] = $from;
            $stime = $CNOA_DB->db_getfield( "stime", $this->t_calendar, "WHERE `cid` = '".$cid."' " );
            if ( empty( $stime ) )
            {
                $stime = time( );
            }
            $GLOBALS['GLOBALS']['CALENDAR']['TIME'] = $stime."000";
        }
        else if ( $from == "viewDetail" )
        {
            $this->_viewDetail( );
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/notice/viewCalDetails.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            exit( );
        }
    }

    private function _viewDetail( )
    {
        global $CNOA_DB;
        $cid = getpar( $_GET, "cid", 0 );
        $dblist = $CNOA_DB->db_getone( "*", $this->t_calendar, "WHERE `cid` = '".$cid."' " );
        $share = $CNOA_DB->db_select( "*", $this->t_calendar_share, "WHERE `cid` = '".$cid."' " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        if ( !is_array( $share ) )
        {
            $share = array( );
        }
        $uid = array( 0 );
        foreach ( $share as $k => $v )
        {
            $uid[] = $v['touid'];
        }
        $dblist['owner'] = ( integer )getpar( $_GET, "owner" );
        $truename = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uid );
        foreach ( $share as $k => $v )
        {
            if ( $truename[$v['touid']]['truename'] )
            {
                $dblist['shareName'] .= $truename[$v['touid']]['truename'].", ";
            }
        }
        if ( $dblist['shareName'] )
        {
            $dblist['shareName'] = rtrim( trim( $dblist['shareName'] ), "," );
        }
        $dblist['stime'] = formatdate( $dblist['stime'], "Y-m-d H:i" );
        $dblist['etime'] = formatdate( $dblist['etime'], "Y-m-d H:i" );
        $dblist['content'] = nl2br( $dblist['content'] );
        if ( !empty( $dblist['flowId'] ) )
        {
            $temp = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId` = '".$dblist['flowId']."' " );
            $dblist['flowName'] = $temp['name'];
            $dblist['tplSort'] = $temp['tplSort'];
            $dblist['flowType'] = $temp['flowType'];
            $dblist['step'] = $temp['startStepId'];
            $dblist['nameRuleId'] = $temp['nameRuleId'];
        }
        if ( !empty( $dblist['pid'] ) )
        {
            $DB = $CNOA_DB->db_getone( "*", $this->t_calendar, "WHERE `cid` = '".$cid."' AND `pid` = '{$dblist['pid']}' " );
            $dblist['periodText'] = $DB['periodText'];
            $dblist['uFlowId'] = $DB['uFlowId'];
            $dblist['tplSort'] = $temp['tplSort'];
            $dblist['flowType'] = $temp['flowType'];
            $dblist['step'] = $temp['startStepId'];
        }
        if ( $dblist['flowId'] )
        {
            $status = $CNOA_DB->db_getfield( "status", $this->t_use_flow, "WHERE `uFlowId` = ".$dblist['uFlowId']." " );
            if ( $dblist['cid'] && empty( $dblist['pid'] ) )
            {
                $CNOA_DB->db_update( array(
                    "fStatus" => $status
                ), $this->t_calendar, "WHERE `cid` = ".$dblist['cid']." " );
            }
            else
            {
                $CNOA_DB->db_update( array(
                    "fStatus" => $status
                ), $this->t_calendar_period, "WHERE `pid` = ".$dblist['pid']." " );
            }
            $status ? $dblist['status'] = $status : $dblist['status'] = "0";
            $dblist['fStatus'] = $this->_getFStatus( $status );
        }
        else
        {
            $dblist['fStatus'] = "";
            $dblist['status'] = "0";
        }
        $GLOBALS['GLOBALS']['notice']['viewDetails'] = $dblist;
    }

    private function _getFStatus( $status )
    {
        switch ( $status )
        {
        case "1" :
            return "办理中";
        case "2" :
            return "已办理";
        case "3" :
            return "已退件";
        case "4" :
            return "已撤销";
        case "5" :
            return "已删除";
        case "6" :
            return "已终止";
        }
        return "未发起";
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $stime = strtotime( getpar( $_POST, "start", date( "Y-m-d", $GLOBALS['CNOA_TIMESTAMP'] ) )." 00:00:00" );
        $etime = strtotime( getpar( $_POST, "end", date( "Y-m-d", $GLOBALS['CNOA_TIMESTAMP'] ) )." 23:59:59" );
        $uid = $CNOA_SESSION->get( "UID" );
        $month = FALSE;
        if ( 604800 < $etime - $stime )
        {
            $month = TRUE;
        }
        $storeType = getpar( $_POST, "storeLoad", "my" );
        if ( $storeType == $uid )
        {
            $storeType = "my";
        }
        if ( $storeType != "my" )
        {
            $this->__getOtherJsonData( $stime, $etime, $month, $storeType );
        }
        $this->__getPeriodCal( $stime, $etime );
        $CNOA_DB->db_update( array( "older" => 1 ), $this->t_calendar, "WHERE `older` = 0 AND `etime` < '".$GLOBALS['CNOA_TIMESTAMP']."' " );
        $CNOA_DB->db_update( array( "older" => 0 ), $this->t_calendar, "WHERE `older` = 1 AND `etime` >= '".$GLOBALS['CNOA_TIMESTAMP']."' " );
        $shareDB = $CNOA_DB->db_select( array( "cid", "uid" ), $this->t_calendar_share, "WHERE `touid` = '".$uid."' AND `stime` > '{$stime}' AND `etime` < '{$etime}' " );
        if ( !is_array( $shareDB ) )
        {
            $shareDB = array( );
        }
        $shareArr = array( 0 );
        $uidArr = array( 0 );
        foreach ( $shareDB as $k => $v )
        {
            $shareArr[] = $v['cid'];
            $uidArr[] = $v['uid'];
        }
        $truename = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr );
        $dblist = $CNOA_DB->db_select( "*", $this->t_calendar, "WHERE (`uid` = '".$uid."' AND ((stime < {$stime} AND etime > {$etime}) OR (`stime` > '{$stime}' AND `stime` < '{$etime}') OR (`etime` > '{$stime}' AND `etime` < '{$etime}'))) OR `cid` IN (".implode( ",", $shareArr ).") " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $k => $v )
        {
            $temp = array( );
            if ( !empty( $v['stime'] ) )
            {
                if ( empty( $v['etime'] ) )
                {
                    break;
                }
            }
            else
            {
                continue;
            }
            $temp['cid'] = $v['cid'];
            if ( $v['flowId'] )
            {
                $title = $CNOA_DB->db_getfield( "name", $this->t_set_flow, "WHERE `flowId` = '".$v['flowId']."' " );
                $status = $CNOA_DB->db_getfield( "status", $this->t_use_flow, "WHERE `uFlowId` = ".$v['uFlowId']." " );
                $status ? $dblist['status'] = $status : $dblist['status'] = "0";
                $dblist['fStatus'] = $this->_getFStatus( $status );
                $temp['title'] = "<span style=\"color:yellow\">流程:".$title."(".$dblist['fStatus'].")</span> 名称:".$v['title'];
            }
            else
            {
                $temp['title'] = $v['title'];
            }
            $temp['content'] = $v['content'];
            $temp['CalendarId'] = $v['colorid'];
            $temp['uid'] = $v['uid'];
            if ( $v['uid'] != $uid )
            {
                $temp['title'] = " [".$truename[$v['uid']]['truename']."的日程] ".$v['title'];
                $temp['CalendarId'] = 8;
                $temp['share'] = $v['share'];
            }
            $temp['start'] = formatdate( $v['stime'], "Y-m-d H:i" );
            $temp['end'] = formatdate( $v['etime'], "Y-m-d H:i" );
            $temp['id'] = $v['cid'];
            $temp['pid'] = $v['pid'];
            if ( $v['older'] == 1 )
            {
                $temp['CalendarId'] = 10;
            }
            if ( $month )
            {
                $temp['ad'] = TRUE;
            }
            $data[] = $temp;
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function __getPeriodCal( $stime, $etime )
    {
        global $CNOA_DB;
        $DB = $CNOA_DB->db_select( "*", $this->t_calendar_period, "WHERE `status` = 0 " );
        if ( !is_array( $DB ) )
        {
            return;
        }
        $dayArr = array( 0 );
        $weekArr = array( 0 );
        $monthArr = array( 0 );
        foreach ( $DB as $k => $v )
        {
            switch ( $v['type'] )
            {
            case 1 :
                $dayArr[] = $v['nid'];
                break;
            case 2 :
                $weekArr[] = $v['nid'];
                break;
            case 3 :
                $monthArr[] = $v['nid'];
            }
        }
        $periodDB = $this->__selectPeriod( $dayArr, $weekArr, $monthArr, $etime );
        foreach ( $DB as $k => $v )
        {
            $updateTime = 0;
            if ( $v['lastupdate'] == 0 )
            {
                if ( $v['time'] < $etime )
                {
                    $stime = strtotime( date( "Y-m-d ".$v['stime'], $v['time'] ) );
                    switch ( $v['type'] )
                    {
                    case 1 :
                        if ( !isset( $periodDB['day'][$v['nid']] ) )
                        {
                            break;
                        }
                        $updateTime = $this->__insertDay( $periodDB['day'][$v['nid']], $v, $stime, $etime );
                        break;
                    case 2 :
                        if ( !isset( $periodDB['week'][$v['nid']] ) )
                        {
                            break;
                        }
                        $stime = strtotime( date( "Y-m-d", $stime )." next sunday" ) - 604800;
                        $updateTime = $this->__insertWeek( $periodDB['week'][$v['nid']], $v, $stime, $etime );
                        $CNOA_DB->db_delete( $this->t_calendar, "WHERE `pid` = '".$v['pid']."' AND `stime` < '{$v['time']}' " );
                        break;
                    case 3 :
                        if ( isset( $periodDB['month'][$v['nid']] ) )
                        {
                            break;
                        }
                        $updateTime = $this->__insertMonth( $periodDB['month'][$v['nid']], $v, $stime, $etime );
                    }
                }
            }
            else if ( $v['lastupdate'] <= $etime )
            {
                switch ( $v['type'] )
                {
                case 1 :
                    if ( !isset( $periodDB['day'][$v['nid']] ) )
                    {
                        break;
                    }
                    $updateTime = $this->__insertDay( $periodDB['day'][$v['nid']], $v, $v['lastupdate'], $etime );
                    break;
                case 2 :
                    if ( !isset( $periodDB['week'][$v['nid']] ) )
                    {
                        break;
                    }
                    $updateTime = $this->__insertWeek( $periodDB['week'][$v['nid']], $v, $v['lastupdate'], $etime );
                    break;
                case 3 :
                    if ( isset( $periodDB['month'][$v['nid']] ) )
                    {
                        break;
                    }
                    $updateTime = $this->__insertMonth( $periodDB['month'][$v['nid']], $v, $v['lastupdate'], $etime );
                }
            }
            if ( !empty( $updateTime ) )
            {
                $CNOA_DB->db_update( array(
                    "lastupdate" => $updateTime
                ), $this->t_calendar_period, "WHERE `pid` = '".$v['pid']."' " );
            }
        }
    }

    private function __selectPeriod( $dayArr, $weekArr, $monthArr, $etime )
    {
        $etime = strtotime( date( "Y-m-d", $etime ), " 00:00:00" );
        global $CNOA_DB;
        $data = array( );
        $dayDB = $CNOA_DB->db_select( "*", $this->t_period_day, "WHERE `edate`>=".$etime." AND `nid` IN (".implode( ",", $dayArr ).")" );
        if ( !is_array( $dayDB ) )
        {
            $dayDB = array( );
        }
        foreach ( $dayDB as $k => $v )
        {
            $data['day'][$v['nid']] = $v;
        }
        $weekDB = $CNOA_DB->db_select( "*", $this->t_period_week, "WHERE `edate`>=".$etime." AND `nid` IN (".implode( ",", $weekArr ).") " );
        if ( !is_array( $weekDB ) )
        {
            $weekDB = array( );
        }
        foreach ( $weekDB as $k => $v )
        {
            $data['week'][$v['nid']] = $v;
        }
        $monthDB = $CNOA_DB->db_select( "*", $this->t_period_month, "WHERE `edate`>=".$etime." AND `nid` IN (".implode( ",", $monthArr ).") " );
        if ( !is_array( $monthDB ) )
        {
            $monthDB = array( );
        }
        foreach ( $monthDB as $k => $v )
        {
            $data['month'][$v['nid']] = $v;
        }
        return $data;
    }

    private function __insertDay( $period, $data, $stime, $etime )
    {
        global $CNOA_DB;
        $cal['stime'] = $stime;
        $i = 0;
        $edate = $data['etime'];
        do
        {
            $cal['stime'] = $cal['stime'] + ( $period['interval'] + 1 ) * 86400 * $i;
            $cal['etime'] = strtotime( date( "Y-m-d", $cal['stime'] )."23:59:59" );
            $cal['pid'] = $data['pid'];
            $cal['title'] = $data['title'];
            $cal['content'] = $data['content'];
            $cal['early'] = $data['early'];
            $cal['uid'] = $data['uid'];
            $cal['colorid'] = $data['colorid'];
            $cal['cellphone'] = $data['cellphone'];
            $cal['flowId'] = $data['flowId'];
            $this->__insertCal( $cal, $data['signInIDs'] );
            $i = 1;
        } while ( 1 );
        return $cal['stime'];
    }

    private function __insertWeek( $period, $data, $stime, $etime )
    {
        $time = $stime;
        $i = 0;
        $edate = $data['etime'];
        do
        {
            $sunday = date( "Y-m-d", $time );
            $cal['pid'] = $data['pid'];
            $cal['title'] = $data['title'];
            $cal['content'] = $data['content'];
            $cal['early'] = $data['early'];
            $cal['uid'] = $data['uid'];
            $cal['colorid'] = $data['colorid'];
            $cal['cellphone'] = $data['cellphone'];
            if ( !empty( $period['day1'] ) )
            {
                $time = strtotime( $sunday." Monday ".$data['stime'] );
                $cal['stime'] = $time;
                $cal['etime'] = strtotime( date( "Y-m-d", $cal['stime'] )." 23:59:59" );
                if ( $edate < $time )
                {
                    break;
                }
                $this->__insertCal( $cal, $data['signInIDs'] );
            }
            if ( !empty( $period['day2'] ) )
            {
                $time = strtotime( $sunday." Tuesday ".$data['stime'] );
                $cal['stime'] = $time;
                $cal['etime'] = strtotime( date( "Y-m-d", $cal['stime'] )." 23:59:59" );
                if ( $edate < $time )
                {
                    break;
                }
                $this->__insertCal( $cal, $data['signInIDs'] );
            }
            if ( !empty( $period['day3'] ) )
            {
                $time = strtotime( $sunday." Wednesday ".$data['stime'] );
                $cal['stime'] = $time;
                $cal['etime'] = strtotime( date( "Y-m-d", $cal['stime'] )." 23:59:59" );
                if ( $edate < $time )
                {
                    break;
                }
                $this->__insertCal( $cal, $data['signInIDs'] );
            }
            if ( !empty( $period['day4'] ) )
            {
                $time = strtotime( $sunday." Thursday ".$data['stime'] );
                $cal['stime'] = $time;
                $cal['etime'] = strtotime( date( "Y-m-d", $cal['stime'] )." 23:59:59" );
                if ( $edate < $time )
                {
                    break;
                }
                $this->__insertCal( $cal, $data['signInIDs'] );
            }
            if ( !empty( $period['day5'] ) )
            {
                $time = strtotime( $sunday." Friday ".$data['stime'] );
                $cal['stime'] = $time;
                $cal['etime'] = strtotime( date( "Y-m-d", $cal['stime'] )." 23:59:59" );
                if ( $edate < $time )
                {
                    break;
                }
                $this->__insertCal( $cal, $data['signInIDs'] );
            }
            if ( !empty( $period['day6'] ) )
            {
                $time = strtotime( $sunday." Saturday ".$data['stime'] );
                $cal['stime'] = $time;
                $cal['etime'] = strtotime( date( "Y-m-d", $cal['stime'] )." 23:59:59" );
                if ( $edate < $time )
                {
                    break;
                }
                $this->__insertCal( $cal, $data['signInIDs'] );
            }
            if ( !empty( $period['day7'] ) )
            {
                $time = strtotime( $sunday." next Sunday ".$data['stime'] );
                if ( $edate < $time )
                {
                    break;
                }
                $cal['stime'] = $time;
                $cal['etime'] = strtotime( date( "Y-m-d", $cal['stime'] )." 23:59:59" );
                $this->__insertCal( $cal, $data['signInIDs'] );
            }
            $time = strtotime( $sunday." +".$period['interval']."week next Sunday" );
            ++$i;
        } while ( !( 10 < $i ) );
        return $time;
    }

    private function __insertMonth( $period, $data, $stime, $etime )
    {
        global $CNOA_DB;
        $edate = $data['etime'];
        $time = $data['time'];
        if ( $edate <= $stime )
        {
            return;
        }
        $cal['stime'] = $time;
        $cal['etime'] = $edate;
        $cal['pid'] = $data['pid'];
        $cal['title'] = $data['title'];
        $cal['content'] = $data['content'];
        $cal['early'] = $data['early'];
        $cal['uid'] = $data['uid'];
        $cal['colorid'] = $data['colorid'];
        $cal['cellphone'] = $data['cellphone'];
        $this->__insertCal( $cal, $data['signInIDs'] );
        return strtotime( date( "Y-m-d H:I", $cal['etime'] ) );
    }

    private function __insertCal( $data, $signInIDs )
    {
        global $CNOA_DB;
        $cid = $CNOA_DB->db_insert( $data, $this->t_calendar, FALSE );
        $signInIDArr = explode( ",", $signInIDs );
        if ( !empty( $cid ) )
        {
            $share['cid'] = $cid;
            $share['uid'] = $data['uid'];
            $share['stime'] = $data['stime'];
            $share['etime'] = $data['etime'];
            foreach ( $signInIDArr as $v )
            {
                if ( !( $share['uid'] == $v ) )
                {
                    $share['touid'] = $v;
                    $CNOA_DB->db_insert( $share, $this->t_calendar_share );
                }
            }
        }
        $CNOA_DB->db_update( array( "share" => 1 ), $this->t_calendar, "WHERE `cid` = '".$cid."' " );
        return $cid;
    }

    private function __getOtherJsonData( $stime, $etime, $month, $other )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $shareDB = $CNOA_DB->db_select( array( "cid", "uid" ), $this->t_calendar_share, "WHERE `touid` = '".$uid."' AND `stime` > '{$stime}' AND `etime` < '{$etime}' " );
        if ( !is_array( $shareDB ) )
        {
            $shareDB = array( );
        }
        $shareArr = array( );
        foreach ( $shareDB as $k => $v )
        {
            $shareArr[] = $v['cid'];
        }
        $selectShare = "";
        if ( !empty( $shareArr ) )
        {
            $selectShare = "AND `cid` IN ()".implode( ",", $shareArr ).") ";
        }
        $dblist = $CNOA_DB->db_select( "*", $this->t_calendar, "WHERE (`uid` = ".$other." ".$selectShare.( "AND ((`stime` > '".$stime."' AND `stime` < '{$etime}') " ).( "OR (`etime` > '".$stime."' AND `etime` < '{$etime}')))" ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['CalendarId'] = $v['colorid'];
            $dblist[$k]['start'] = formatdate( $v['stime'], "Y-m-d H:i" );
            $dblist[$k]['end'] = formatdate( $v['etime'], "Y-m-d H:i" );
            $dblist[$k]['id'] = $v['cid'];
            if ( $v['older'] == 1 )
            {
                $dblist[$k]['CalendarId'] = 10;
            }
            else
            {
                $dblist[$k]['CalendarId'] = 1;
            }
            if ( $month )
            {
                $dblist[$k]['ad'] = TRUE;
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $dblist;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _addCalendar( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $data['nid'] = getpar( $_POST, "nid", 0 );
        $data['title'] = getpar( $_POST, "title", "" );
        $data['content'] = getpar( $_POST, "content", "", 1, 0 );
        $edate = getpar( $_POST, "edate", 0 );
        $data['stime'] = strtotime( getpar( $_POST, "sdate", 0 )." ".getpar( $_POST, "stime", "09:00:00" ) );
        $data['etime'] = strtotime( $edate." ".getpar( $_POST, "etime", "18:00:00" ) );
        $periodNotice = getpar( $_POST, "period_notice", "", 0, 0 );
        $flowId = getpar( $_POST, "flowId", "" );
        if ( $data['etime'] < $data['stime'] || $edate == "" )
        {
            msg::callback( FALSE, lang( "StimeGreaterThanEtime" ) );
        }
        if ( $flowId )
        {
            $list = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE flowId = ".$flowId );
            $flowUrl = "index.php?app=wf&func=flow&action=use&modul=new&task=loadPage&from=newflow&flowId=".$flowId."&nameRuleId=".$list['nameRuleId']."&flowType=".$list['flowType']."&tplSort=".$list['tplSort'];
        }
        $data['early'] = getpar( $_POST, "early", 0 );
        $data['uid'] = $CNOA_SESSION->get( "UID" );
        $data['colorid'] = getpar( $_POST, "colorid", 0 );
        $cellphone = getpar( $_POST, "cellphone", "" );
        $data['flowId'] = $flowId;
        if ( $cellphone == "on" )
        {
            $data['cellphone'] = 1;
            ( );
            $sms = new sms( );
            $time = $data['early'] * 3600;
            $sms->sendByUids( array(
                $uid
            ), string::cut( $data['content'], 50, "..." ), $data['stime'] - $time, "calendar" );
        }
        $type = getpar( $_POST, "type", "" );
        $notice['title'] = "日程提醒";
        $sdate = formatdate( $data['stime'], "Y-m-d H:i" );
        $notice['content'] = "您的日程[".$data['title']."]将要在[{$sdate}]开始。";
        $notice['uid'] = $uid;
        if ( $type == "add" )
        {
            if ( $periodNotice != "" )
            {
                $period_f = json_decode( html_entity_decode( getpar( $_POST, "period_notice", "", 0, 0 ) ), TRUE );
                if ( $period_f['type'] == "one" )
                {
                    $long = $data['etime'] - $data['stime'];
                    foreach ( $period_f['data'] as $k => $v )
                    {
                        if ( !empty( $v ) )
                        {
                            $data['stime'] = strtotime( substr( $v, 0, 10 )." ".getpar( $_POST, "stime", "09:00:00" ) );
                            $data['etime'] = $data['stime'] + $long;
                            $cid = $CNOA_DB->db_insert( $data, $this->t_calendar );
                            if ( !empty( $data['early'] ) )
                            {
                                $time = $data['early'] * 3600;
                                $sdate = formatdate( $data['stime'], "Y-m-d H:i" );
                                $notice['content'] = "您的日程[".$data['title']."]将要在[{$sdate}]开始。";
                                if ( $list )
                                {
                                    $notice['url'] = $flowUrl."&cid=".$cid;
                                    notice::add( $notice['uid'], $notice['title'], $notice['content'], $notice['url'], strtotime( $v ), 31, $cid, 0 );
                                }
                                else
                                {
                                    $notice['url'] = "index.php?app=notice&func=notice&action=calendar&from=notice&cid=".$cid;
                                    notice::add( $notice['uid'], $notice['title'], $notice['content'], $notice['url'], strtotime( $v ), 28, $cid, 0 );
                                }
                            }
                        }
                    }
                }
                else
                {
                    $this->__insertUpdatePeriod( $data, $flowUrl );
                }
            }
            else
            {
                $cid = $CNOA_DB->db_insert( $data, $this->t_calendar );
                if ( !empty( $data['early'] ) )
                {
                    $time = $data['early'] * 3600;
                    if ( $list )
                    {
                        $notice['url'] = $flowUrl."&cid=".$cid;
                        notice::add( $notice['uid'], $notice['title'], $notice['content'], $notice['url'], $data['stime'] - $time, 31, $cid, 0 );
                    }
                    else
                    {
                        $notice['url'] = "index.php?app=notice&func=notice&action=calendar&from=notice&cid=".$cid;
                        notice::add( $notice['uid'], $notice['title'], $notice['content'], $notice['url'], $data['stime'] - $time, 28, $cid, 0 );
                    }
                }
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3502, $data['title'], "我的日程" );
        }
        else
        {
            $cid = getpar( $_POST, "cid", 0 );
            $DB = $CNOA_DB->db_getone( "*", $this->t_calendar, "WHERE `cid` = '".$cid."' AND `uid` = '{$uid}' " );
            if ( empty( $DB ) )
            {
                msg::callback( FALSE, lang( "calendarSharedToOthers" ) );
            }
            if ( $periodNotice != "" )
            {
                if ( empty( $DB['pid'] ) )
                {
                    $pid = $this->__insertUpdatePeriod( $data, $flowUrl );
                }
                else
                {
                    $period = $CNOA_DB->db_getone( "*", $this->t_calendar_period, "WHERE `pid` = '".$DB['pid']."' " );
                    if ( $data['flowId'] != $period['flowId'] )
                    {
                        $data['uFlowId'] = 0;
                        $data['fStatus'] = 0;
                    }
                    notice::perioddelete( $period['type'], $period['nid'] );
                    $this->__insertUpdatePeriod( $data, $flowUrl, $DB['pid'] );
                    notice::deleteafterperiod( $period['type'], $period['nid'], $data['stime'] );
                    $this->__cleanPeriodCal( $DB['pid'], $data['stime'] );
                }
            }
            $data['older'] = 0;
            $lhcFlowId = $CNOA_DB->db_getfield( "flowId", $this->t_calendar, "WHERE `cid` = '".$cid."' " );
            if ( $data['flowId'] != $lhcFlowId )
            {
                $data['uFlowId'] = 0;
                $data['fStatus'] = 0;
            }
            if ( empty( $DB['pid'] ) )
            {
                $CNOA_DB->db_update( $data, $this->t_calendar, "WHERE `cid` = '".$cid."' " );
            }
            $DB['pid'] ? $data['pid'] = $DB['pid'] : $data['pid'] = $pid;
            if ( !empty( $data['early'] ) )
            {
                $time = $data['early'] * 3600;
                if ( $list )
                {
                    $notice['url'] = $flowUrl."&cid=".$cid;
                    notice::add( $notice['uid'], $notice['title'], $notice['content'], $notice['url'], $data['stime'] - $time, 31, $cid, 0 );
                }
                else
                {
                    $notice['url'] = "index.php?app=notice&func=notice&action=calendar&from=notice&cid=".$cid;
                    notice::add( $notice['uid'], $notice['title'], $notice['content'], $notice['url'], $data['stime'] - $time, 28, $cid, 0 );
                }
            }
        }
        $signInIDs = getpar( $_POST, "signInIDs", 0 );
        if ( !empty( $signInIDs ) )
        {
            $signInIDArr = explode( ",", $signInIDs );
            $CNOA_DB->db_delete( $this->t_calendar_share, "WHERE `cid` = '".$cid."' " );
            $share['cid'] = $cid;
            $share['uid'] = $CNOA_SESSION->get( "UID" );
            $share['stime'] = $data['stime'];
            $share['etime'] = $data['etime'];
            foreach ( $signInIDArr as $v )
            {
                if ( !( $share['uid'] == $v ) )
                {
                    $share['touid'] = $v;
                    $CNOA_DB->db_insert( $share, $this->t_calendar_share );
                }
            }
            $CNOA_DB->db_update( array( "share" => 1 ), $this->t_calendar, "WHERE `cid` = '".$cid."' " );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 3502, $data['title'], "我的日程" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function __insertUpdatePeriod( $data, $flowUrl, $pid = 0 )
    {
        global $CNOA_DB;
        $etime = strtotime( getpar( $_POST, "period_edate", 0 )." 23:59:59" );
        if ( $etime )
        {
            $data['etime'] = $etime;
        }
        if ( empty( $pid ) )
        {
            $pid = $CNOA_DB->db_insert( $data, $this->t_calendar_period );
        }
        else
        {
            $data['lastupdate'] = 0;
            $CNOA_DB->db_update( $data, $this->t_calendar_period, "WHERE `pid` = '".$pid."' " );
        }
        $flowUrl ? ( $href = $flowUrl."&pid=".$pid ) : ( $href = "index.php?app=notice&func=notice&action=calendar&from=notice" );
        $flowUrl ? ( $types = 31 ) : ( $types = 28 );
        $data['content'] ? ( $content = $data['content'] ) : ( $content = $data['title'] );
        $periodArr = notice::periodadd( lang( "cycleCalenDarRemind", $data['title'] ), lang( "cycleCalenDarRemind", $content ), $href, 1, $types );
        $data['nid'] = $periodArr['nid'];
        $data['type'] = $periodArr['type'];
        $data['long'] = $data['etime'] - $data['stime'];
        $data['time'] = $data['stime'];
        $data['stime'] = getpar( $_POST, "stime", "09:00:00" );
        $data['signInIDs'] = getpar( $_POST, "signInIDs", 0 );
        $data['periodText'] = getpar( $_POST, "periodText", "" );
        $CNOA_DB->db_update( $data, $this->t_calendar_period, "WHERE `pid` = '".$pid."' " );
        return $pid;
    }

    private function __cleanPeriodCal( $pid, $stime )
    {
        global $CNOA_DB;
        $CNOA_DB->db_update( array( "pid" => 0 ), $this->t_calendar, "WHERE `pid` = '".$pid."' AND `stime` < '{$stime}' " );
        $dblist = $CNOA_DB->db_select( array( "cid" ), $this->t_calendar, "WHERE `pid` = '".$pid."' AND `stime` >= '{$stime}' " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $v )
        {
            $CNOA_DB->db_delete( $this->t_calendar_share, "WHERE `cid` = '".$v['cid']."' " );
        }
        return $CNOA_DB->db_delete( $this->t_calendar, "WHERE `pid` = '".$pid."' AND `stime` >= '{$stime}' " );
    }

    private function _resizeDate( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $cid = getpar( $_POST, "cid", 0 );
        $uid = $CNOA_SESSION->get( "UID" );
        $stime = strtotime( getpar( $_POST, "dt", 0 ) );
        if ( !empty( $stime ) )
        {
            $data['stime'] = $stime;
        }
        $data['etime'] = strtotime( getpar( $_POST, "endT", 0 ) );
        $data['older'] = 0;
        $num = $CNOA_DB->db_getcount( $this->t_calendar, "WHERE `cid` = '".$cid."' AND `uid` = '{$uid}' " );
        if ( empty( $num ) )
        {
            msg::callback( FALSE, lang( "calendarSharedToOthers" ) );
        }
        $CNOA_DB->db_update( $data, $this->t_calendar, "WHERE `cid` = '".$cid."' " );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getColorData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $num = $CNOA_DB->db_getcount( $this->t_color_type, "WHERE `uid` = '".$uid."' " );
        if ( empty( $num ) )
        {
            $this->__addUser( );
        }
        $dblist = $CNOA_DB->db_select( "*", $this->t_color_type, "WHERE `uid` = '".$uid."' ORDER BY `order` ASC " );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function __addUser( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $data['uid'] = $uid;
        $data['name'] = lang( "undefinition" );
        $i = 1;
        for ( ; $i <= 5; ++$i )
        {
            $data['colorid'] = $i;
            $data['order'] = $i;
            $CNOA_DB->db_insert( $data, $this->t_color_type );
        }
    }

    private function _updateColorType( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $nameArr = getpar( $_POST, "colorName", array( ) );
        $orderArr = getpar( $_POST, "colorOrder", array( ) );
        foreach ( $nameArr as $k => $v )
        {
            $data['name'] = $v;
            $data['order'] = $orderArr[$k];
            $CNOA_DB->db_update( $data, $this->t_color_type, "WHERE `colorid` = '".$k."' AND `uid` = '{$uid}' " );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadEditData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $cid = getpar( $_POST, "cid", 0 );
        $dblist = $CNOA_DB->db_getone( array( "title", "flowId", "early", "colorid", "share", "cellphone", "pid" ), $this->t_calendar, "WHERE `cid` = '".$cid."' " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        if ( !empty( $dblist['share'] ) )
        {
            $shareDB = $CNOA_DB->db_select( array( "touid" ), $this->t_calendar_share, "WHERE `uid` = '".$uid."' AND `cid` = '{$cid}' " );
            if ( !is_array( $shareDB ) )
            {
                $shareDB = array( );
            }
            foreach ( $shareDB as $k => $v )
            {
                $shareUidArr[] = $v['touid'];
            }
            $truenames = app::loadapp( "main", "user" )->api_getUserNamesByUids( $shareUidArr );
            $dblist['signInIDs'] = implode( ",", $shareUidArr );
            $dblist['signInNames'] = "";
            foreach ( $shareUidArr as $v )
            {
                $dblist['signInNames'] .= $truenames[$v]['truename'].", ";
            }
            $dblist['signInNames'] = rtrim( trim( $dblist['signInNames'] ), "," );
        }
        if ( !empty( $dblist['flowId'] ) )
        {
            $dblist['flowName'] = $CNOA_DB->db_getfield( "name", $this->t_set_flow, "WHERE `flowId` = '".$dblist['flowId']."' " );
        }
        if ( !empty( $dblist['pid'] ) )
        {
            $period = $CNOA_DB->db_getone( "*", $this->t_calendar_period, "WHERE `pid` = '".$dblist['pid']."' " );
            $dblist = notice::periodlist( $period['type'], $period['nid'], $dblist );
            if ( $period['flowId'] )
            {
                $dblist['flowName'] = $CNOA_DB->db_getfield( "name", $this->t_set_flow, "WHERE `flowId` = '".$period['flowId']."' " );
            }
            $dblist['flowId'] = $period['flowId'];
            $dblist['period_uidName'] = rtrim( trim( $dblist['period_uidName'] ), "," );
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $dblist;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _deleteCid( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $cid = getpar( $_POST, "cid", 0 );
        $dblist = $CNOA_DB->db_getone( "*", $this->t_calendar, "WHERE `cid` = '".$cid."' AND `uid` = '{$uid}' " );
        notice::deletenotice( 0, $dblist['nid'] );
        if ( $dblist['pid'] )
        {
            $CNOA_DB->db_delete( $this->t_calendar, "WHERE `pid` = ".$dblist['pid']." " );
            $CNOA_DB->db_delete( $this->t_calendar_period, "WHERE `pid` = ".$dblist['pid']." " );
        }
        else
        {
            $CNOA_DB->db_delete( $this->t_calendar, "WHERE `cid` = '".$cid."' " );
        }
        $CNOA_DB->db_delete( $this->t_calendar_share, "WHERE `cid` = '".$cid."' " );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 3502, $dblist['title'], "我的日程" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _deletePeriodCal( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $cid = getpar( $_POST, "cid", 0 );
        $type = getpar( $_POST, "type", 0 );
        $dblist = $CNOA_DB->db_getone( "*", $this->t_calendar, "WHERE `cid` = '".$cid."' AND `uid` = '{$uid}' " );
        if ( empty( $dblist ) )
        {
            msg::callback( FALSE, lang( "cannotDelCalendar" ) );
        }
        if ( $type == 1 )
        {
            $CNOA_DB->db_delete( $this->t_calendar, "WHERE `cid` = '".$cid."' " );
            $CNOA_Db->db_delete( $this->t_calendar_share, "WHERE `cid` = '".$cid."' " );
            $DB = $CNOA_DB->db_getone( array(
                "nid",
                "type",
                "stime" => $dblist['stime'],
                "etime" => $dblist['etime']
            ), $this->t_calendar_period, "WHERE `pid` = '".$dblist['pid']."' " );
            $CNOA_DB->db_insert( array(
                "type" => $DB['type'],
                "nid" => $DB['type']
            ), $this->t_notice_except );
        }
        else if ( $type == 2 )
        {
            $cid -= 1;
            $cidDB = $CNOA_DB->db_select( array( "cid" ), $this->t_calendar, "WHERE `cid` > '".$cid."' AND `pid` = '{$dblist['pid']}' " );
            if ( !is_array( $cidDB ) )
            {
                $cidDB = array( );
            }
            foreach ( $cidDB as $k => $v )
            {
                $CNOA_DB->db_delete( $this->t_calendar_share, "WHERE `cid` = '".$v['cid']."' " );
            }
            $CNOA_DB->db_delete( $this->t_calendar, "WHERE `cid` > '".$cid."' AND `pid` = '{$dblist['pid']}' " );
            $CNOA_DB->db_update( array( "status" => 1 ), $this->t_calendar_period, "WHERE `pid` = '".$dblist['pid']."' " );
        }
        else if ( $type == 3 )
        {
            $cidDB = $CNOA_DB->db_select( array( "cid" ), $this->t_calendar, "WHERE `older` = 0 AND `pid` = '".$dblist['pid']."' " );
            if ( !is_array( $cidDB ) )
            {
                $cidDB = array( );
            }
            foreach ( $cidDB as $k => $v )
            {
                $CNOA_DB->db_delete( $this->t_calendar_share, "WHERE `cid` = '".$v['cid']."' " );
            }
            $CNOA_DB->db_delete( $this->t_calendar, "WHERE `older` = 0 AND `pid` = '".$dblist['pid']."' " );
            $CNOA_DB->db_update( array( "status" => 1 ), $this->t_calendar_period, "WHERE `pid` = '".$dblist['pid']."' " );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _addForOtherFun( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $data['uid'] = $CNOA_SESSION->get( "UID" );
        $select = getpar( $_POST, "select", 1 );
        $date = getpar( $_POST, "date", "" );
        $time = getpar( $_POST, "time", "9:00 AM" );
        if ( $select == 5 )
        {
            $data['stime'] = strtotime( $date." ".$time );
        }
        else
        {
            $data['stime'] = strtotime( date( "Y-m-d", $GLOBALS['CNOA_TIMESTAMP'] )." ".$time ) + $select * 86400;
        }
        $data['etime'] = $data['stime'] + 3600;
        $data['title'] = getpar( $_POST, "title", "" );
        $data['content'] = getpar( $_POST, "content", "" );
        $data['colorid'] = 1;
        $cid = $CNOA_DB->db_insert( $data, $this->t_calendar );
        $url = "index.php?app=notice&func=notice&action=calendar&from=notice&cid=".$cid;
        notice::add( $data['uid'], $data['title'], $data['content'], $url, $data['stime'], 28, $cid, 0 );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function api_getColorData( )
    {
        $this->_getColorData( );
    }

    public function api_addCalendar( )
    {
        $this->_addCalendar( );
    }

    public function getFlowListInSort( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $sortId = intval( getpar( $_POST, "sortId", 0 ) );
        $dblist = $CNOA_DB->db_select( array( "flowId", "name" ), $this->t_set_flow, "WHERE `sortId`='".$sortId."' AND `status`=1" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $dblist;
        echo $ds->makeJsonData( );
        exit( );
    }

    public function api_getSortTree( $from, $returnArray = FALSE )
    {
        global $CNOA_DB;
        $dblist = $this->api_getSortDB( $from );
        $list = array( );
        foreach ( $dblist as $v )
        {
            $r = array( );
            $r['text'] = $v['name'];
            $r['type'] = $v['name'];
            $r['sortId'] = $v['sortId'];
            $r['iconCls'] = "icon-style-page-key";
            $r['leaf'] = TRUE;
            $r['href'] = "javascript:void(0);";
            $list[] = $r;
        }
        if ( $returnArray )
        {
            return $list;
        }
        echo json_encode( $list );
        exit( );
    }

    public function api_getSortDB( $from, $uid = 0, $did = 0, $sid = 0 )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        if ( empty( $uid ) )
        {
            $uid = $CNOA_SESSION->get( "UID" );
        }
        if ( empty( $did ) )
        {
            $did = $CNOA_SESSION->get( "DID" );
        }
        if ( empty( $sid ) )
        {
            $sid = $CNOA_SESSION->get( "SID" );
        }
        if ( $from == "faqi" )
        {
            $WHERE = "WHERE `from` = 'f' ";
        }
        else if ( $from == "chayue" )
        {
            $WHERE = "WHERE `from` = 'c' ";
        }
        else if ( $from == "guanli" )
        {
            $WHERE = "WHERE `from` = 'g' ";
        }
        else
        {
            if ( $from == "chayue&guanli" )
            {
                $WHERE = "WHERE (`from` = 'g' OR `from` = 'c') ";
            }
            else if ( $from == "all" )
            {
                $dblist = $CNOA_DB->db_select( "*", $this->t_set_sort, "WHERE 1 ORDER BY `order`" );
                if ( !is_array( $dblist ) )
                {
                    $dblist = array( );
                }
                return $dblist;
            }
            else
            {
                return array( );
            }
        }
        if ( $from == "faqi" )
        {
            $permit = $CNOA_DB->db_select( "*", $this->t_set_sort_permit, $WHERE.( "AND ((`type` = 'p' AND `permitId` = '".$uid."' ) OR (`type` = 's' AND `permitId` = '{$sid}' ) OR (`type` = 'd' AND `permitId` = '{$did}' ) OR (`type` = 'n')) GROUP BY `sortId` " ) );
        }
        else
        {
            $permit = $CNOA_DB->db_select( "*", $this->t_set_sort_permit, $WHERE.( "AND ((`type` = 'p' AND `permitId` = '".$uid."' ) OR (`type` = 's' AND `permitId` = '{$sid}' ) OR (`type` = 'd' AND `permitId` = '{$did}' )) GROUP BY `sortId` " ) );
        }
        if ( !is_array( $permit ) )
        {
            $permit = array( );
        }
        $sortIdArr = array( 0 );
        foreach ( $permit as $k => $v )
        {
            $forbidFaqi = $CNOA_DB->db_select( "*", $this->t_set_sort_forbid, "WHERE `sortId`=".$v['sortId']." AND ((`type` = 'p' AND `permitId` = '{$uid}' ) OR (`type` = 's' AND `permitId` = '{$sid}' ) OR (`type` = 'd' AND `permitId` = '{$did}' )) GROUP BY `sortId` " );
            if ( empty( $forbidFaqi ) )
            {
                $sortIdArr[] = $v['sortId'];
            }
        }
        $dblist = $CNOA_DB->db_select( "*", $this->t_set_sort, "WHERE `sortId` IN (".implode( ",", $sortIdArr ).") ORDER BY `order` " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        return $dblist;
    }

    public function _lhcGetFlow( )
    {
        global $CNOA_DB;
        $lhcFlowId = getpar( $_POST, "lhcFlowId", "" );
        $list = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId` = ".$lhcFlowId );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $list;
        echo $dataStore->makeJsonData( );
        exit( );
    }

}

?>
