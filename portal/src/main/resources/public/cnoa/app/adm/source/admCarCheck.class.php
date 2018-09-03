<?php
//decode by qq2859470

class admCarCheck extends model
{

    private $table_check = "adm_car_check";
    private $table_apply = "adm_car_apply";
    private $table_info = "adm_car_info";
    private $rows = 15;

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        if ( $task == "list" )
        {
            $this->_list( );
        }
        else if ( $task == "pass" )
        {
            $this->_pass( );
        }
        else if ( $task == "getAllUserListsInPermitDeptTree" )
        {
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $userList );
            exit( );
        }
        else if ( $task == "newcheck" )
        {
            $this->_newcheck( );
        }
        else if ( $task == "info" )
        {
            $id = getpar( $_GET, "id", 0 );
            app::loadapp( "adm", "carInfo" )->api_admCarInfoShow( $id );
        }
        else if ( $task == "apply" )
        {
            $this->_apply( );
        }
        else if ( $task == "applylist" )
        {
            global $CNOA_CONTROLLER;
            $id = getpar( $_GET, "cid", 0 );
            $from = getpar( $_GET, "from", "" );
            if ( $from == "loadPage" )
            {
                $aid = getpar( $_GET, "aid", 0 );
                $GLOBALS['GLOBALS']['car']['apply']['list'] = $aid;
                $path = $CNOA_CONTROLLER->appPath."/tpl/default/car/applylist.htm";
                $CNOA_CONTROLLER->loadExtraTpl( $path );
                exit( );
            }
        }
    }

    private function __getDepartName( $deptID )
    {
        $depList = app::loadapp( "main", "struct" )->api_getArrayList( );
        $deptName = $depList[$deptID];
        return $deptName;
    }

    private function _newcheck( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = getpar( $_POST, "id", 0 );
        $checkid = getpar( $_POST, "checkid", 0 );
        $temp = $CNOA_DB->db_getone( array( "cid", "aid", "noticeid_c", "todoid_c" ), $this->table_check, "WHERE `id`=".$id );
        notice::donen( $temp['noticeid_c'] );
        notice::donet( $temp['todoid_c'] );
        $carInfo = $CNOA_DB->db_getone( array( "status", "carnumber" ), $this->table_info, "WHERE `id`=".$temp['cid'] );
        $data['cid'] = $temp['cid'];
        $data['aid'] = $temp['aid'];
        $data['preuid'] = $CNOA_SESSION->get( "UID" );
        $data['checkid'] = $checkid;
        $data['notes'] = getpar( $_POST, "notes", "" );
        $data['noticeid_c'] = notice::add( $checkid, lang( "vehicleAppMgr" ), lang( "car" ).( "[".$carInfo['carnumber']."]" ).lang( "needYouApproval" ), "index.php?app=adm&func=car&action=check", "", 11, $temp['aid'] );
        $notice['touid'] = $data['checkid'];
        $notice['from'] = 11;
        $notice['fromid'] = $id;
        $notice['href'] = "index.php?app=adm&func=car&action=check";
        $notice['title'] = lang( "car" ).( "[".$carInfo['carnumber']."]，" ).lang( "needYouApproval" ).",".lang( "submitter" ).": [".$CNOA_SESSION->get( "TRUENAME" )."]";
        $notice['content'] = lang( "vehicleSubmitTime" )."[".formatdate( $data['starttime'], "Y-m-d H:i" )."]".lang( "to" )."[".formatdate( $data['endtime'], "Y-m-d H:i" )."]";
        if ( !empty( $data['caruser'] ) )
        {
            $notice['content'] .= "  ".lang( "vehicleUser" ).( "[".$data['caruser']."]" );
        }
        if ( !empty( $data['driver'] ) )
        {
            $notice['content'] .= "  ".lang( "driver" ).( "[".$data['driver']."]" );
        }
        $notice['funname'] = lang( "carMgr" );
        $notice['move'] = lang( "approval2" );
        $data['todoid_c'] = notice::add2( $notice );
        $insertid = $CNOA_DB->db_insert( $data, $this->table_check );
        $CNOA_DB->db_update( array(
            "nextcheckid" => $checkid,
            "nextcheck" => $insertid,
            "status" => 4,
            "time" => $GLOBALS['CNOA_TIMESTAMP']
        ), $this->table_check, "WHERE `id`=".$id );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _apply( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        $aid = getpar( $_GET, "aid", 0 );
        $dblist = $CNOA_DB->db_getone( "*", $this->table_apply, "WHERE `id`=".$aid );
        $temp = $CNOA_DB->db_getone( array( "notes" ), $this->table_info, "WHERE `id`=".$dblist['cid'] );
        $info = $this->__getCarInfo( $dblist['cid'] );
        $dblist['carnumber'] = $info['carnumber'];
        $dblist['notes'] = $info['notes'];
        $dblist['applyname'] = $this->__takeUserName( $dblist['applyUid'] );
        $dblist['transport'] = $this->__takeUserName( $dblist['tid'] );
        $dblist['checkname'] = $this->__takeUserName( $dblist['checkid'] );
        $dblist['starttime'] = formatdate( $dblist['starttime'], "Y-m-d H:i" );
        $dblist['endtime'] = formatdate( $dblist['endtime'], "Y-m-d H:i" );
        $dblist['realstarttime'] = formatdate( $dblist['realstarttime'], "Y-m-d H:i" );
        $dblist['realendtime'] = formatdate( $dblist['realendtime'], "Y-m-d H:i" );
        $dblist['department'] = $dblist['deptID'] == "" ? "" : $this->__getDepartName( $dblist['deptID'] );
        $dblist['notes'] = $temp['notes'];
        $dblist['driver'] = app::loadapp( "adm", "CarDriver" )->api_getDriverNameById( $dblist['driver'] );
        $GLOBALS['GLOBALS']['adm']['car']['apply'] = $dblist;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/car/applydetails.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _list( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $checkid = $CNOA_SESSION->get( "UID" );
        $type = getpar( $_POST, "storeType", "uncheck" );
        $start = getpar( $_POST, "start", 0 );
        $WHERE = "WHERE 1 ";
        if ( $type == "uncheck" )
        {
            $WHERE .= "AND `checkid`='".$checkid."' AND `status`=0 ";
        }
        else if ( $type == "check" )
        {
            $WHERE .= "AND `checkid`='".$checkid."' AND `status`>0 ";
        }
        $number = getpar( $_POST, "number", 0 );
        $caruser = getpar( $_POST, "caruser", "" );
        $stime = getpar( $_POST, "stime", 0 );
        $etime = getpar( $_POST, "etime", 0 );
        if ( !empty( $number ) )
        {
            $temp = $CNOA_DB->db_select( array( "id" ), $this->table_info, "WHERE `carnumber` LIKE '%".$number."%' " );
            if ( empty( $temp ) )
            {
                $WHERE .= "AND `cid` = 0 ";
            }
            else
            {
                if ( !is_array( $temp ) )
                {
                    $temp = array( );
                }
                foreach ( $temp as $v )
                {
                    $temp2[] = $v['id'];
                }
                $WHERE .= "AND `cid` IN (".implode( ",", $temp2 ).") ";
            }
        }
        $APPLY_WHERE = "WHERE 1";
        if ( !empty( $stime ) )
        {
            $stime = strtotime( $stime." 00:00:00" );
            $APPLY_WHERE .= " AND `starttime` > ".$stime." ";
        }
        if ( !empty( $etime ) )
        {
            $etime = strtotime( $etime." 23:59:59" );
            $APPLY_WHERE .= " AND `endtime` < ".$etime." ";
        }
        if ( !empty( $stime ) && !empty( $etime ) )
        {
            $apply_ids = $CNOA_DB->db_select( array( "id" ), $this->table_apply, $APPLY_WHERE );
            if ( !is_array( $apply_ids ) )
            {
                $apply_ids = array( );
            }
            $temp_id = array( );
            foreach ( $apply_ids as $v )
            {
                $temp_id[] = $v['id'];
            }
            $ids = implode( ",", $temp_id );
        }
        if ( isset( $ids, $ids ) )
        {
            $WHERE .= " AND `aid` IN (".$ids.")";
        }
        else if ( isset( $ids, $ids ) )
        {
            $WHERE .= " AND `aid` IN (0)";
        }
        $dblist = $CNOA_DB->db_select( "*", $this->table_check, $WHERE.( " ORDER BY `id` DESC LIMIT ".$start.", {$this->rows}" ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $carArr = array( 0 );
        $uidArr = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $carArr[] = $v['cid'];
            $uidArr[] = $v['preuid'];
            $uidArr[] = $v['nextcheckid'];
        }
        $carInfo = app::loadapp( "adm", "carInfo" )->api_getCarInfo( $carArr );
        $truenameArr = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['nextcheckname'] = $truenameArr[$v['nextcheckid']]['truename'];
            $dblist[$k]['prename'] = $truenameArr[$v['preuid']]['truename'];
            $temp = $this->__getCarApply( $v['aid'] );
            $dblist[$k]['starttime'] = formatdate( $temp['starttime'], "Y-m-d H:i" );
            $dblist[$k]['endtime'] = formatdate( $temp['endtime'], "Y-m-d H:i" );
            $dblist[$k]['carnumber'] = $carInfo[$temp['cid']]['carnumber'];
            $dblist[$k]['pic'] = $info['pic'];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = $CNOA_DB->db_getcount( $this->table_check, $WHERE );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _pass( $isexit = TRUE )
    {
        global $CNOA_DB;
        $pass = getpar( $_POST, "pass", "" );
        $id = getpar( $_POST, "id", 0 );
        $data['nextcheck'] = 0;
        $data['time'] = $GLOBALS['CNOA_TIMESTAMP'];
        $temp = $CNOA_DB->db_getone( array( "aid", "preuid", "noticeid_c", "todoid_c" ), $this->table_check, "WHERE `id`=".$id );
        $apply = $CNOA_DB->db_getone( "*", $this->table_apply, "WHERE `id`=".$temp['aid'] );
        if ( $pass == "pass" )
        {
            $data['passnotes'] = getpar( $_POST, "notes", "" );
            $data['status'] = 2;
            $CNOA_DB->db_update( array( "status" => 3 ), $this->table_apply, "WHERE `id`=".$temp['aid'] );
            $info = $CNOA_DB->db_getone( array( "carnumber" ), $this->table_info, "WHERE `id`=".$apply['cid'] );
            $applyID = $CNOA_DB->db_select( array( "id" ), $this->table_apply, "WHERE `starttime`<".$apply['endtime']." AND `endtime`>{$apply['starttime']} AND `status`!=3 AND `cid`={$apply['cid']}" );
            if ( !is_array( $applyID ) )
            {
                $applyID = array( );
            }
            foreach ( $applyID as $v )
            {
                $CNOA_DB->db_update( array( "status" => 3 ), $this->table_check, "WHERE `aid`=".$v['id'] );
                $CNOA_DB->db_update( array( "status" => 0 ), $this->table_apply, "WHERE `id`=".$v['id'] );
                notice::add( intval( $apply['applyUid'] ), lang( "vehicleAppCancel" ), lang( "licensePalte" )."：".$info['carnumber'].lang( "appTime" ).$this->__formatDate( $apply['starttime'] ).lang( "to" ).$this->__formatDate( $apply['endtime'] )."，".lang( "anotherAppChooseOther" ), "index.php?app=adm&func=car&action=apply&task=applylist&from=loadPage&aid=".$temp['aid'], "", 11, $temp['aid'] );
            }
            $CNOA_DB->db_update( array( "status" => 3 ), $this->table_apply, "WHERE `id`=".$temp['aid'] );
            notice::donen( $temp['noticeid_c'] );
            notice::donet( $temp['todoid_c'] );
            notice::add( intval( $apply['applyUid'] ), lang( "vehicleAppMgr" ), lang( "youAppVehicle" ).( "[".$info['carnumber']."]" ).lang( "approveRemark" ).":".$data['passnotes'], "index.php?app=adm&func=car&action=apply&task=applylist&from=loadPage&aid=".$temp['aid'], "", 11, $temp['aid'] );
            $applyName = $this->__takeUserName( $apply['applyUid'] );
            $noticeid_t = notice::add( intval( $apply['tid'] ), lang( "vehicleDDmgr" ), lang( "proposer" ).":".$applyName.", ".lang( "requestCar" ).":".$info['carnumber'].", ".lang( "appTime" ).":".$this->__formatDate( $apply['starttime'] ).lang( "to" ).$this->__formatDate( $apply['endtime'] ).", ".lang( "appRemark" ).":".$data['passnotes'], "index.php?app=adm&func=car&action=transport", "", 11, $temp['aid'] );
            $notice['touid'] = $apply['tid'];
            $notice['from'] = 11;
            $notice['fromid'] = $temp['aid'];
            $notice['href'] = "index.php?app=adm&func=car&action=transport";
            $notice['title'] = lang( "car" ).( "[".$info['carnumber']."]，" ).lang( "needYouDDsubmitter" ).": [".$applyName."]";
            $notice['content'] = lang( "vehicleSubmitTime" )."[".formatdate( $apply['starttime'], "Y-m-d H:i" )."]".lang( "to" )."[".formatdate( $apply['endtime'], "Y-m-d H:i" )."]";
            if ( !empty( $apply['caruser'] ) )
            {
                $notice['content'] .= "  ".lang( "vehicleUser" ).( "[".$apply['caruser']."]" );
            }
            if ( !empty( $apply['driver'] ) )
            {
                $notice['content'] .= "  ".lang( "driver" ).( "[".$apply['driver']."]" );
            }
            $notice['funname'] = lang( "carMgr" );
            $notice['move'] = lang( "dispatch" );
            if ( $isexit )
            {
                $todoid_t = notice::add2( $notice );
            }
            $CNOA_DB->db_update( array(
                "todoid_t" => $todoid_t,
                "noticeid_t" => $noticeid_t
            ), $this->table_apply, "WHERE `id` = '".$temp['aid']."' " );
        }
        else if ( $pass == "unpass" )
        {
            $data['status'] = 1;
            $CNOA_DB->db_update( array( "status" => 1 ), $this->table_apply, "WHERE `id`=".$temp['aid'] );
            $data['unpassnotes'] = getpar( $_POST, "unpassnotes" );
            notice::donen( $temp['noticeid_c'] );
            notice::donet( $temp['todoid_c'] );
            notice::add( intval( $apply['applyUid'] ), lang( "vehicleAppMgr" ), lang( "youAppVehicle" ).( "[".$info['carnumber']."]" ).lang( "approvalNotThrough" ).",".lang( "contentIs" ).": ".$data['unpassnotes'], "index.php?app=adm&func=car&action=apply&task=applylist&from=loadPage&aid=".$temp['aid'], "", 11, $temp['aid'] );
        }
        $CNOA_DB->db_update( $data, $this->table_check, "WHERE `id`=".$id );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 144, lang( "vehicleAppStatus" ) );
        if ( $isexit )
        {
            msg::callback( TRUE, lang( "successopt" ) );
        }
    }

    private function __getCarApply( $value )
    {
        global $CNOA_DB;
        $data = $CNOA_DB->db_getone( "*", $this->table_apply, "WHERE `id`=".$value );
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        return $data;
    }

    private function __getCarInfo( $value )
    {
        global $CNOA_DB;
        $data = $CNOA_DB->db_getone( "*", $this->table_info, "WHERE `id`=".$value );
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        return $data;
    }

    private function __takeUserName( $id )
    {
        $data = app::loadapp( "main", "user" )->api_getUserDataByUid( $id );
        return $data['truename'];
    }

    private function __formatDate( $value )
    {
        if ( $value == 0 )
        {
            return "";
        }
        return date( "Y-m-d H:i", $value );
    }

    public function api_apply( )
    {
        $this->_apply( );
    }

    public function api_pass( $isexit )
    {
        $this->_pass( $isexit );
    }

}

?>
