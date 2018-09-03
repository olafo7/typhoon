<?php
//decode by qq2859470

class flowFlowUserCommonFlow extends model
{

    private $table_sort = "flow_flow_sort";
    private $table_list = "flow_flow_list";
    private $table_list_node = "flow_flow_list_node";
    private $table_form = "flow_flow_form";
    private $table_form_item = "flow_flow_form_item";
    private $table_u_list = "flow_flow_u_list";
    private $table_u_node = "flow_flow_u_node";
    private $table_u_formdata = "flow_flow_u_formdata";
    private $table_u_event = "flow_flow_u_event";
    private $table_u_entrust = "flow_flow_u_entrust";
    private $eventType = array
    (
        1 => "开始",
        2 => "已办理",
        3 => "撤销",
        4 => "召回",
        5 => "退件",
        6 => "退回上一步",
        7 => "结束"
    );
    private $statusType = array
    (
        1 => "办理中",
        2 => "已办理",
        3 => "退件"
    );
    private $entrustType = array
    (
        0 => "禁用",
        1 => "启用",
        2 => "未设置"
    );
    private $viewUrlCommonFlow = "index.php?app=flow&func=flow&action=user&module=commonFlow&task=loadPage&from=viewflow&ulid=";
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
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            exit( );
        case "getAllUserListsInPermitDeptTree" :
            $this->_getAllUserListsInPermitDeptTree( );
            exit( );
        case "sendFlow" :
            $this->_sendFlow( );
            exit( );
        case "editLoadFormDataInfo" :
            $this->_editLoadFormDataInfo( );
            exit( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $from = getpar( $_GET, "from", "" );
        if ( $from == "newflow" )
        {
            $GLOBALS['GLOBALS']['ac'] = getpar( $_GET, "ac", "add" );
            $GLOBALS['GLOBALS']['ulid'] = getpar( $_GET, "ulid", "0" );
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/user_newcommonflow.htm";
        }
        else if ( $from == "showflow" )
        {
            $GLOBALS['GLOBALS']['ulid'] = getpar( $_GET, "ulid", 0 );
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/user_showcommonflow.htm";
        }
        else if ( $from == "viewflow" )
        {
            $GLOBALS['GLOBALS']['ulid'] = getpar( $_GET, "ulid", 0 );
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/user_viewcommonflow.htm";
        }
        else if ( $from == "showdispenseflow" )
        {
            $GLOBALS['GLOBALS']['ulid'] = getpar( $_GET, "ulid", 0 );
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/dispense_view_commonflow.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _getAllUserListsInPermitDeptTree( )
    {
        $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
        $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
        echo json_encode( $userList );
        exit( );
    }

    private function _sendFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $uname = $CNOA_SESSION->get( "TRUENAME" );
        $stepInfo = $_POST['step'];
        $nextOperatorUid = 0;
        $ac = getpar( $_POST, "ac", "add" );
        $ulid = getpar( $_POST, "ulid", 0 );
        if ( $ac == "edit" )
        {
            $ds = $CNOA_DB->db_getone( "*", $this->table_u_list, "WHERE `ulid`='".$ulid."' AND `uid`='{$uid}'" );
            $CNOA_DB->db_delete( $this->table_u_list, "WHERE `ulid`='".$ulid."' AND `uid`='{$uid}'" );
            ( );
            $fs = new fs( );
            $attach = $fs->edit( getpar( $_POST, "filesUpload", array( ) ), json_decode( $ds['attach'], FALSE ), 7 );
            $ds['attach'] = json_encode( $attach );
        }
        else
        {
            $ds = array( );
            $ds['lid'] = 0;
            $ds['flowmax'] = $CNOA_DB->db_getmax( "flowmax", $this->table_u_list, "WHERE `flowtype`=1" ) + 1;
            $ds['name'] = "通用流程".str_pad( $ds['flowmax'], 10, "0", STR_PAD_LEFT );
            ( );
            $fs = new fs( );
            $filesUpload = getpar( $_POST, "filesUpload", array( ) );
            $attach = $fs->add( $filesUpload, 7 );
            $ds['attach'] = json_encode( $attach );
        }
        $ds['flowtype'] = 1;
        $ds['flowname'] = "通用流程";
        $ds['title'] = getpar( $_POST, "title", "" );
        $ds['uid'] = $uid;
        $ds['level'] = getpar( $_POST, "level", "" );
        $ds['reason'] = getpar( $_POST, "reason", "", 1, 0 );
        $ds['about'] = "";
        $ds['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $ds['file'] = "";
        $ds['status'] = 1;
        $ds['step'] = 1;
        $ds['commonflowdata'] = addslashes( $stepInfo );
        $ulid = $CNOA_DB->db_insert( $ds, $this->table_u_list );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 73, $ds['title'], "通用流程" );
        $flowStepInfos = array( );
        $flowStepItem = array( );
        $flowStepItem['stepid'] = 0;
        $flowStepItem['name'] = "开始";
        $flowStepItem['about'] = "";
        $flowStepItem['allowup'] = "1";
        $flowStepItem['allowdown'] = "1";
        $flowStepItem['type'] = "start";
        $flowStepItem['operatorperson'] = "1";
        $flowStepItem['operatortype'] = "1";
        $flowStepItem['operator'] = json_encode( array(
            "user" => array(
                array(
                    "uid" => $uid,
                    "name" => $uname
                )
            )
        ) );
        $flowStepInfos[] = $flowStepItem;
        $stepInfo = json_decode( $stepInfo, TRUE );
        if ( is_array( $stepInfo ) )
        {
            foreach ( $stepInfo as $k => $v )
            {
                $flowStepItem = array( );
                $flowStepItem['stepid'] = $k + 1;
                $flowStepItem['name'] = $v['stepName'];
                $flowStepItem['about'] = "";
                $flowStepItem['allowup'] = "1";
                $flowStepItem['allowdown'] = "1";
                $flowStepItem['type'] = "node";
                $flowStepItem['operatorperson'] = "1";
                $flowStepItem['operatortype'] = "1";
                $flowStepItem['operator'] = json_encode( array(
                    "user" => array(
                        array(
                            "uid" => $v['uid'],
                            "name" => $v['uname']
                        )
                    )
                ) );
                $flowStepInfos[] = $flowStepItem;
                if ( $k == 0 )
                {
                    $nextOperatorUid = $v['uid'];
                }
            }
        }
        $flowStepItem = array( );
        $flowStepItem['stepid'] = $k + 1;
        $flowStepItem['name'] = "结束";
        $flowStepItem['about'] = "";
        $flowStepItem['allowup'] = "1";
        $flowStepItem['allowdown'] = "1";
        $flowStepItem['type'] = "end";
        $flowStepItem['operatorperson'] = "1";
        $flowStepItem['operatortype'] = "1";
        $flowStepItem['operator'] = json_encode( array(
            "user" => array(
                array( )
            )
        ) );
        $flowStepInfos[] = $flowStepItem;
        $cachePath = $this->cachePath.( "/flow/user/".$ulid."/" )."flow_node.php";
        @mkdirs( @dirname( $cachePath ) );
        file_put_contents( $cachePath, "<?php\r\nreturn ".var_export( $flowStepInfos, TRUE ).";" );
        app::loadapp( "flow", "flowUser" )->api_eventAdd( $ulid, 1, 0, "开始", $ds['reason'] );
        unset( $data );
        $data = array( );
        $data['ulid'] = $ulid;
        $data['stepid'] = 0;
        $data['say'] = $ds['reason'];
        $data['status'] = 2;
        $data['nodename'] = $flowStepInfos[0]['name'];
        $data['stime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['uid'] = $uid;
        $data['operatortype'] = 1;
        $data['attachdown'] = 1;
        $data['attachup'] = 1;
        $data['sponsor'] = 1;
        $CNOA_DB->db_insert( $data, $this->table_u_node );
        unset( $data );
        $data = array( );
        $nextUids = explode( ",", getpar( $_POST, "nextUids", "" ) );
        $data['ulid'] = $ulid;
        $data['stepid'] = 1;
        $data['status'] = 1;
        $data['nodename'] = $flowStepInfos[1]['name'];
        $data['stime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['operatortype'] = 1;
        $data['attachdown'] = 1;
        $data['attachup'] = 1;
        $data['sponsor'] = 1;
        $data['uid'] = $nextOperatorUid;
        $noticeT = "工作流管理";
        $noticeC = "你有一个工作需要审批".$ds['name']."[{$ds['title']}]";
        $noticeH = $this->viewUrlCommonFlow.$ulid;
        $data['noticeid_c'] = notice::add( $nextOperatorUid, $noticeT, $noticeC, $noticeH, 0, 5 );
        $notice['touid'] = $nextOperatorUid;
        $notice['from'] = 5;
        $notice['fromid'] = 0;
        $notice['href'] = $noticeH;
        $notice['title'] = $noticeC;
        $notice['funname'] = "工作流管理";
        $notice['move'] = "审批";
        $data['todoid_c'] = notice::add2( $notice );
        $CNOA_DB->db_insert( $data, $this->table_u_node );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function _editLoadFormDataInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $uname = $CNOA_SESSION->get( "TRUENAME" );
        $ulid = intval( getpar( $_POST, "ulid", 0 ) );
        if ( $ulid != 0 )
        {
            $formInfo = $CNOA_DB->db_getone( "*", $this->table_u_list, "WHERE `ulid`='".$ulid."' AND `uid`='{$uid}'" );
            $formInfo['commonflowdata'] = json_decode( $formInfo['commonflowdata'], TRUE );
            ( );
            $fs = new fs( );
            $formInfo['attach'] = $fs->getEditList( json_decode( $formInfo['attach'], TRUE ) );
            ( );
            $ds = new dataStore( );
            $ds->data = $formInfo;
            $ds->step = $formInfo['commonflowdata'];
            echo $ds->makeJsonData( );
            exit( );
        }
        msg::callback( FALSE, "参数错误" );
    }

    public function api_getStepInfo( $flowInfo )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $stepInfo = "开始[".app::loadapp( "main", "user" )->api_getUserTruenameByUid( $flowInfo['uid'] )."]";
        $flowInfo['commonflowdata'] = json_decode( $flowInfo['commonflowdata'], TRUE );
        if ( !is_array( $flowInfo['commonflowdata'] ) )
        {
            $flowInfo['commonflowdata'] = array( );
        }
        foreach ( $flowInfo['commonflowdata'] as $v )
        {
            $stepInfo .= " <img src='resources/images/icons/arrow-right.png' align='absmiddle'> ".$v['stepName']."[{$v['uname']}]";
        }
        return $stepInfo;
    }

}

?>
