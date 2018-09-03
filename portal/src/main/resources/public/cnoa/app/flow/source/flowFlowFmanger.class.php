<?php
//decode by qq2859470

class flowFlowFmanger extends model
{

    private $table_sort = "flow_flow_sort";
    private $table_dept_sort = "flow_flow_dept_sort";
    private $table_user_sort = "flow_flow_user_sort";
    private $table_list = "flow_flow_list";
    private $table_list_node = "flow_flow_list_node";
    private $table_form = "flow_flow_form";
    private $table_form_item = "flow_flow_form_item";
    private $table_u_list = "flow_flow_u_list";
    private $table_u_node = "flow_flow_u_node";
    private $table_u_formdata = "flow_flow_u_formdata";
    private $table_u_event = "flow_flow_u_event";
    private $table_u_entrust = "flow_flow_u_entrust";
    private $table_sort_permit = "news_news_sort_permit";
    private $cachePath = "";

    public function __construct( )
    {
        $this->cachePath = CNOA_PATH_FILE."/cache";
    }

    public function __destruct( )
    {
    }

    public function run( )
    {
        global $CNOA_SESSION;
        $task = getpar( $_GET, "task", getpar( $_POST, "task" ) );
        if ( $task == "loadPage" )
        {
            $this->_loadPage( );
        }
        else if ( $task == "sortGetJsonData" )
        {
            $this->_sortGetJsonData( );
        }
        else if ( $task == "sortAdd" )
        {
            $this->_sortAdd( );
        }
        else if ( $task == "sortEdit" )
        {
            $this->_sortEdit( );
        }
        else if ( $task == "sortDelete" )
        {
            $this->_sortDelete( );
        }
        else if ( $task == "sortLoadFormData" )
        {
            $this->_sortLoadFormData( );
        }
        else if ( $task == "getFlowJsonData" )
        {
            $this->_getFlowJsonData( );
        }
        else if ( $task == "flowadd" )
        {
            $this->_flowadd( );
        }
        else if ( $task == "flowedit" )
        {
            $this->_flowedit( );
        }
        else if ( $task == "floweditLoadForm" )
        {
            $this->_floweditLoadForm( );
        }
        else if ( $task == "flowdelete" )
        {
            $this->_flowdelete( );
        }
        else if ( $task == "flowdesignLoadData" )
        {
            $this->_flowdesignLoadData( );
        }
        else if ( $task == "flowdesignSubmitData" )
        {
            $this->_flowdesignSubmitData( );
        }
        else if ( $task == "setFlowPublish" )
        {
            $this->_setFlowPublish( );
        }
        else if ( $task == "getAllFlowJsonData" )
        {
            $this->_getAllFlowJsonData( );
        }
        else if ( $task == "deleteflow" )
        {
            $this->_deleteflow( );
        }
        else if ( $task == "exportExcel" )
        {
            $this->_exportExcel( );
        }
        else if ( $task == "getFormJsonData" )
        {
            $this->_getFormJsonData( );
        }
        else if ( $task == "formAdd" )
        {
            $this->_formAdd( );
        }
        else if ( $task == "formEdit" )
        {
            $this->_fromEdit( );
        }
        else if ( $task == "formeditLoadForm" )
        {
            $this->_formeditLoadForm( );
        }
        else if ( $task == "formdelete" )
        {
            $this->_formdelete( );
        }
        else if ( $task == "formdesign" )
        {
            $this->_formdesign( );
        }
        else if ( $task == "saveFormDesignData" )
        {
            $this->_saveFormDesignData( );
        }
        else if ( $task == "getSortList" )
        {
            $this->_getSortList( );
        }
        else if ( $task == "getFormList" )
        {
            $this->_getFormList( );
        }
        else if ( $task == "getAllUserListsInPermitDeptTree" )
        {
            $this->_getAllUserListsInPermitDeptTree( );
        }
        else if ( $task == "getStructTree" )
        {
            $this->_getStructTree( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $from = getpar( $_GET, "from", "" );
        if ( $from == "flow" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/setting_flow.htm";
        }
        else if ( $from == "form" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/setting_form.htm";
        }
        else if ( $from == "sort" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/setting_sort.htm";
        }
        else if ( $from == "mgr" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/fmanger_mgr.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    public function _getSortList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $type = getpar( $_GET, "type", "combo" );
        $myJobType = $CNOA_SESSION->get( "JOBTYPE" );
        if ( $myJobType == "superAdmin" )
        {
            $dblist = $CNOA_DB->db_select( "*", $this->table_sort, "WHERE 1 ORDER BY `order` ASC" );
        }
        else
        {
            $deptId = $CNOA_DB->db_getone( "*", "main_user", "WHERE `uid` = '".$uid."'" );
            $ssid = $CNOA_DB->db_select( "*", $this->table_dept_sort, "WHERE `deptId` = '".$deptId['deptId']."'" );
            $sids = array( 0 );
            foreach ( $ssid as $v )
            {
                $sids[] = $v['sid'];
            }
            $dblist = $CNOA_DB->db_select( "*", $this->table_sort, "WHERE `sid` IN (".implode( ",", $sids ).") ORDER BY `order` ASC" );
        }
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        if ( $type == "tree" )
        {
            $list = array( );
            foreach ( $dblist as $v )
            {
                $r = array( );
                $r['text'] = $v['name'];
                $r['sid'] = $v['sid'];
                $r['iconCls'] = "icon-style-page-key";
                $r['leaf'] = TRUE;
                $r['href'] = "javascript:void(0);";
                $list[] = $r;
            }
            echo json_encode( $list );
            exit( );
        }
        if ( $type == "combo" )
        {
            ( );
            $dataStore = new dataStore( );
            $dataStore->total = count( $dblist );
            $dataStore->data = $dblist;
            echo $dataStore->makeJsonData( );
            exit( );
        }
    }

    private function _getAllFlowJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $start = getpar( $_POST, "start", 0 );
        $rows = 15;
        $sort = intval( getpar( $_POST, "sort", 0 ) );
        $myJobType = $CNOA_SESSION->get( "JOBTYPE" );
        if ( $myJobType == "superAdmin" )
        {
            if ( $sort == 0 )
            {
                $where = "WHERE 1";
            }
            else
            {
                $where = "WHERE `sort`='".$sort."' ";
            }
        }
        else if ( $sort == 0 )
        {
            $deptId = $CNOA_DB->db_getone( "*", "main_user", "WHERE `uid` = '".$uid."'" );
            $ssid = $CNOA_DB->db_select( "*", $this->table_dept_sort, "WHERE `deptId` = '".$deptId['deptId']."'" );
            $sids = array( 0 );
            foreach ( $ssid as $v )
            {
                $sids[] = $v['sid'];
            }
            $where = "WHERE `sort` IN (".implode( ",", $sids ).") ";
        }
        else
        {
            $where = "WHERE `sort`='".$sort."' ";
        }
        $where .= $this->__findFlowInfo( );
        $order = "ORDER BY `posttime` DESC LIMIT ".$start.", {$rows} ";
        $dbList = $CNOA_DB->db_select( "*", $this->table_u_list, $where.$order );
        if ( !is_array( $dbList ) )
        {
            $dbList = array( );
        }
        $uids = array( );
        foreach ( $dbList as $k => $v )
        {
            $dbList[$k]['posttime'] = date( "Y-m-d H:i", $v['posttime'] );
            if ( $v['level'] == 2 )
            {
                $dbList[$k]['level'] = "<span class='cnoa_color_orange'>重要</span>";
            }
            else if ( $v['level'] == 3 )
            {
                $dbList[$k]['level'] = "<span class='cnoa_color_red'>非常重要</span>";
            }
            else
            {
                $dbList[$k]['level'] = "普通";
            }
            if ( $v['status'] == 0 )
            {
                $dbList[$k]['step'] = $CNOA_DB->db_getfield( "name", $this->table_list_node, "WHERE `lid`='".$v['lid']."' AND `stepid`='0'" );
            }
            else
            {
                $cacheFile = include( $this->cachePath.( "/flow/user/".$v['ulid']."/" )."flow_node.php" );
                $dbList[$k]['step'] = $cacheFile[$v['step']]['name'];
            }
            $dbList[$k]['despanseStatus'] = $CNOA_DB->db_getfield( "isread", "flow_flow_u_dispense", "WHERE `ulid`='".$v['ulid']."' AND `to_uid`='{$uid}'" );
            if ( !$dbList[$k]['despanseStatus'] && $dbList[$k]['despanseStatus'] == 0 )
            {
                $dbList[$k]['despanseStatus'] = "未阅";
            }
            else
            {
                $dbList[$k]['despanseStatus'] = "已阅";
            }
            $uids[$v['uid']] = $v['uid'];
        }
        $userNames = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uids );
        foreach ( $dbList as $key => $value )
        {
            $dbList[$key]['uname'] = $userNames[$value['uid']]['truename'];
        }
        $delbtn = $this->_flowDelBtn( );
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = $CNOA_DB->db_getcount( $this->table_u_list, $where );
        $dataStore->data = $dbList;
        $dataStore->hasDel = $delbtn;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _flowDelBtn( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $myJobType = $CNOA_SESSION->get( "JOBTYPE" );
        $uid = $CNOA_SESSION->get( "UID" );
        $sort = intval( getpar( $_POST, "sort", "" ) );
        if ( $myJobType == "superAdmin" )
        {
            return TRUE;
        }
        if ( $sort == 0 )
        {
            return FALSE;
        }
        $info = $CNOA_DB->db_getone( "*", $this->table_user_sort, "WHERE `sid` = '".$sort."' AND `allUid` = '{$uid}' " );
        if ( $info === FALSE )
        {
            return FALSE;
        }
        return TRUE;
    }

    private function _exportExcel( )
    {
        global $CNOA_SESSION;
        $uid = intval( getpar( $_POST, "uid", "" ) );
        $stime = strtotime( getpar( $_POST, "stime", "0000-00-00" )." 00:00:00" );
        $etime = strtotime( getpar( $_POST, "etime", "0000-00-00" )." 23:59:59" );
        $truename = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $uid );
        if ( $etime <= $stime )
        {
            msg::callback( FALSE, "结束时间不能早于开始时间" );
        }
        $fileName = "CNOA.FLOW-".$uid.date( "Ymd", $stime )."-".date( "Ymd", $etime )."-".string::rands( 10, 2 ).".xlsx";
        $dataInfo = $this->_getExportExcelData( $stime, $etime );
        $excelClass = app::loadapp( "flow", "flowExportExcel" );
        $excelClass->init( $dataInfo, $truename, $stime, $etime );
        $excelClass->save( CNOA_PATH_FILE."/common/temp/".$fileName );
        $truename = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $uid );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 81, "条件：".$truename.", ".date( "Y-m-d", $stime )."--".date( "Y-m-d", $etime ), "导出excel报表" );
        msg::callback( TRUE, makedownloadicon( "{$GLOBALS['URL_FILE']}/common/temp/".$fileName, $fileName, "img" ) );
    }

    private function _getExportExcelData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = intval( getpar( $_POST, "uid", "" ) );
        $stime = strtotime( getpar( $_POST, "stime", "0000-00-00" )." 00:00:00" );
        $etime = strtotime( getpar( $_POST, "etime", "0000-00-00" )." 23:59:59" );
        $where = "WHERE `uid`='".$uid."' AND `status`>0 ";
        $where .= "AND `posttime`>='".$stime."' AND `posttime`<='{$etime}' ";
        $where .= "ORDER BY `posttime` ASC ";
        $dbList = $CNOA_DB->db_select( "*", $this->table_u_list, $where );
        if ( !is_array( $dbList ) )
        {
            $dbList = array( );
        }
        foreach ( $dbList as $k => $v )
        {
            $dbList[$k]['posttime'] = date( "Y-m-d H:i", $v['posttime'] );
            if ( $v['level'] == 2 )
            {
                $dbList[$k]['level'] = "重要";
            }
            else if ( $v['level'] == 3 )
            {
                $dbList[$k]['level'] = "非常重要";
            }
            else
            {
                $dbList[$k]['level'] = "普通";
            }
            if ( $v['status'] == 0 )
            {
                $dbList[$k]['step'] = $CNOA_DB->db_getfield( "name", $this->table_list_node, "WHERE `lid`='".$v['lid']."' AND `stepid`='0'" );
            }
            else
            {
                $cacheFile = include( $this->cachePath.( "/flow/user/".$v['ulid']."/" )."flow_node.php" );
                $dbList[$k]['step'] = $cacheFile[$v['step']]['name'];
            }
            $dbList[$k]['statusText'] = "";
            if ( $v['status'] == 0 )
            {
                $dbList[$k]['statusText'] = "未发送";
            }
            else if ( $v['status'] == 1 )
            {
                $dbList[$k]['statusText'] = "办理中";
            }
            else if ( $v['status'] == 2 )
            {
                $dbList[$k]['statusText'] = "已办理";
            }
            else if ( $v['status'] == 3 )
            {
                $dbList[$k]['statusText'] = "已退件";
            }
            else if ( $v['status'] == 4 )
            {
                $dbList[$k]['statusText'] = "已撤销";
            }
        }
        return $dbList;
    }

    private function _deleteflow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $ulids = getpar( $_POST, "ulids", 0 );
        $ulids = substr( $ulids, 0, -1 );
        $ulidArr = explode( ",", $ulids );
        $DB = $CNOA_DB->db_select( array( "name" ), $this->table_u_list, "WHERE `ulid` IN (".$ulids.")" );
        $names = "";
        foreach ( $DB as $v )
        {
            $names .= "{$v['name']}, ";
        }
        $names = substr( $names, 0, -2 );
        foreach ( $ulidArr as $v )
        {
            $CNOA_DB->db_delete( $this->table_u_list, "WHERE `ulid`='".$v."'" );
            $CNOA_DB->db_delete( $this->table_u_node, "WHERE `ulid`='".$v."'" );
            $CNOA_DB->db_delete( $this->table_u_formdata, "WHERE `ulid`='".$v."'" );
            $CNOA_DB->db_delete( $this->table_u_event, "WHERE `ulid`='".$v."'" );
            $cachePath = $this->cachePath.( "/flow/user/".$v."/" );
        }
        $cacheFile = array( );
        $cacheFile['flow'] = $cachePath."flow.php";
        $cacheFile['flow_node'] = $cachePath."flow_node.php";
        $cacheFile['form'] = $cachePath."form.php";
        $cacheFile['form_item'] = $cachePath."form_item.php";
        foreach ( $cacheFile as $v1 )
        {
            @unlink( $v1 );
        }
        @rmdir( $cachePath );
        ( );
        $fs = new fs( );
        $fs->deleteFile( json_decode( $flowInfo['attach'], TRUE ) );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 81, $names, "流程" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getAllUserListsInPermitDeptTree( )
    {
        $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
        $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
        echo json_encode( $userList );
        exit( );
    }

    private function __findFlowInfo( )
    {
        $name = getpar( $_POST, "name" );
        $title = getpar( $_POST, "title" );
        $document = getpar( $_POST, "document" );
        $stime = getpar( $_POST, "beginTime" );
        $etime = getpar( $_POST, "endTime" );
        $status = getpar( $_POST, "status" );
        $uid = getpar( $_POST, "buildUser" );
        $s = "";
        if ( !empty( $uid ) )
        {
            $s .= " AND `uid`=".$uid;
        }
        if ( !empty( $status ) || strval( $status ) != "-99" )
        {
            $s .= " AND `status` = ".$status;
        }
        if ( !empty( $name ) )
        {
            $s .= " AND `name` LIKE '%".$name."%'";
        }
        if ( !empty( $title ) )
        {
            $s .= " AND `title` LIKE '%".$title."%'";
        }
        if ( !empty( $document ) )
        {
            $s .= " AND `document` LIKE '%".$document."%'";
        }
        if ( !empty( $stime ) || empty( $etime ) )
        {
            $stime = strtotime( $stime." 00:00:00" );
            $s .= " AND `posttime` >= ".$stime;
        }
        if ( !empty( $etime ) || empty( $stime ) )
        {
            $etime = strtotime( $etime." 23:59:59" );
            $s .= " AND `posttime` <= ".$etime;
        }
        if ( !empty( $stime ) || !empty( $etime ) )
        {
            $stime = strtotime( $stime." 00:00:00" );
            $etime = strtotime( $etime." 23:59:59" );
            if ( $etime < $stime )
            {
                msg::callback( FALSE, "查询开始时间不能大于结束时间" );
            }
            else
            {
                $s .= " AND `posttime` > ".$stime." AND `posttime` < {$etime}";
            }
        }
        return " ".$s." ";
    }

}

?>
