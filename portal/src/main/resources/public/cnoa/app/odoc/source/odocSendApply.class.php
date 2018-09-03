<?php//decode by qq2859470class odocSendApply extends model{    private $t_odoc_view_fieldset = "odoc_view_fieldset";    private $t_odoc_view_field = "odoc_view_field";    private $t_odoc_data = "odoc_data";    private $t_odoc_view_field_list = "odoc_view_field_list";    private $t_odoc_bind_flow_kj = "odoc_bind_flow_kj";    private $t_odoc_bind_flow = "odoc_bind_flow";    private $t_odoc_fenfa = "odoc_fenfa";    private $t_s_flow_other_app_data = "wf_s_flow_other_app_data";    private $rows = 15;    public function __construct( )    {    }    public function __destruct( )    {    }    public function run( )    {        $task = getpar( $_GET, "task", "loadPage" );        switch ( $task )        {        case "loadPage" :            $this->_loadPage( );            break;        case "loadLayout" :            $this->_loadLayout( );            break;        case "getJsonList" :            $this->_getJsonList( );            break;        case "loadColumn" :            $this->_loadColumn( );            break;        case "submitData" :            $this->_submitData( );            break;        case "getFaqiFlow" :            $this->_getFaqiFlow( );            break;        case "loadFormData" :            $this->_loadFormData( );            break;        case "deleteData" :            $this->_deleteData( );            break;        case "activeAccount" :            $this->_activeAccount( );            break;        case "getSearchList" :        case "getAllUserListsInPermitDeptTree" :            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";            $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );            echo json_encode( $userList );            exit( );        case "submitIssue" :            $this->_submitIssue( );            break;        case "submitSign" :            $this->_submitSign( );        }    }    private function _loadPage( )    {        global $CNOA_SESSION;        global $CNOA_CONTROLLER;        global $CNOA_DB;        $from = getpar( $_GET, "from", "" );        switch ( $from )        {        case "viewflow" :            $GLOBALS['GLOBALS']['app']['uFlowId'] = getpar( $_GET, "uFlowId", 0 );            $GLOBALS['GLOBALS']['app']['step'] = getpar( $_GET, "step", 0 );            $GLOBALS['GLOBALS']['app']['wf']['type'] = "done";            $DB = $CNOA_DB->db_getone( array( "status", "flowId" ), "wf_u_flow", "WHERE `uFlowId`='".$GLOBALS['app']['uFlowId']."'" );            $GLOBALS['GLOBALS']['app']['flowId'] = $DB['flowId'];            $flow = $CNOA_DB->db_getone( array( "tplSort", "flowType" ), "wf_s_flow", "WHERE `flowId` = ".$DB['flowId']." " );            $GLOBALS['GLOBALS']['app']['wf']['allowCallback'] = 0;            $GLOBALS['GLOBALS']['app']['wf']['allowCancel'] = 0;            $GLOBALS['GLOBALS']['app']['allowPrint'] = "false";            $GLOBALS['GLOBALS']['app']['allowFenfa'] = "false";            $GLOBALS['GLOBALS']['app']['allowExport'] = "false";            $GLOBALS['GLOBALS']['app']['status'] = 1;            $GLOBALS['GLOBALS']['app']['wf']['tplSort'] = $flow['tplSort'];            $GLOBALS['GLOBALS']['app']['wf']['flowType'] = $flow['flowType'];            $GLOBALS['GLOBALS']['app']['wf']['owner'] = 0;            $tplPath = CNOA_PATH."/app/wf/tpl/default/flow/use/showflow.htm";            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );            exit( );        }    }    private function _loadLayout( )    {        global $CNOA_DB;        global $CNOA_SESSION;        $flowList = $CNOA_DB->db_select( array( "id", "name" ), $this->t_odoc_bind_flow, "WHERE `status`=1 AND `from`='send'" );        if ( !is_array( $flowList ) )        {            $flowList = array( );        }        $dbFieldSetDB = $CNOA_DB->db_select( "*", $this->t_odoc_view_fieldset, "WHERE `from` = 'send' ORDER BY `id` ASC " );        if ( !is_array( $dbFieldSetDB ) )        {            $dbFieldSetDB = array( );        }        $dbFieldDB = $CNOA_DB->db_select( "*", $this->t_odoc_view_field, "WHERE `from` = 'send' ORDER BY `id` ASC " );        if ( !is_array( $dbFieldDB ) )        {            $dbFieldDB = array( );        }        $dbFieldArr = array( );        $temp = array( );        $dataList = array( );        foreach ( $dbFieldDB as $k => $v )        {            if ( $v['type'] == "textarea" )            {                $dataList[] = $v;                $dataList[] = array( );            }            else            {                $dataList[] = $v;            }        }        foreach ( $dataList as $k => $v )        {            if ( $k % 2 != 0 )            {                $temp['secname'] = trim( $v['name'] );                $temp['secfield'] = $v['field'];                $dbFieldArr[] = $temp;                $temp = array( );            }            else            {                $temp['fstname'] = trim( $v['name'] );                $temp['type'] = $v['type'];                $temp['fstfield'] = $v['field'];                $temp['fieldset'] = $v['fieldset'];            }        }        $result = array(            "success" => TRUE,            "fieldset" => $dbFieldSetDB,            "field" => $dbFieldArr,            "flowList" => $flowList        );        echo json_encode( $result );        exit( );    }    public function api_checkData( )    {        $this->_checkData( );    }    private function _checkData( )    {        global $CNOA_DB;        global $CNOA_SESSION;        $dblist = $CNOA_DB->db_select( array( "otherAppId" ), $this->t_odoc_data, "WHERE `status` != 2 " );        if ( !is_array( $dblist ) )        {            $dblist = array( );        }        $otherAppIDArr = array( 0 );        foreach ( $dblist as $k => $v )        {            $otherAppIDArr[] = $v['otherAppId'];        }        $dblist = $CNOA_DB->db_select( array( "uFlowId", "id" ), $this->t_s_flow_other_app_data, "WHERE `id` IN (".implode( ",", $otherAppIDArr ).") " );        $uFlowIdArr = array( 0 );        $tempArr = array( );        if ( !is_array( $dblist ) )        {            $dblist = array( );        }        foreach ( $dblist as $k => $v )        {            $uFlowIdArr[] = $v['uFlowId'];            $tempArr[$v['uFlowId']] = $v['id'];        }        $bindData = $CNOA_DB->db_select( "*", $this->t_odoc_bind_flow_kj, "WHERE `kongjian` > 0 " );        if ( !is_array( $bindData ) )        {            $bindData = array( );        }        $statusDB = $CNOA_DB->db_select( array( "status", "uFlowId", "flowId", "attach" ), "wf_u_flow", "WHERE `uFlowId` IN (".implode( ",", $uFlowIdArr ).") " );        if ( !is_array( $statusDB ) )        {            $statusDB = array( );        }        foreach ( $statusDB as $k => $v )        {            if ( $v['status'] == 2 )            {                $tableExists = $CNOA_DB->db_tableExists( CNOA_DB_PRE."z_wf_t_".$v['flowId'] );                if ( $tableExists )                {                    $data = $CNOA_DB->db_getone( "*", "z_wf_t_".$v['flowId'], "WHERE `uFlowId` = ".$v['uFlowId']." " );                    if ( !is_array( $data ) )                    {                        $data = array( );                    }                    foreach ( $data as $key => $val )                    {                        $update['status'] = 2;                        $update['uFlowId'] = $v['uFlowId'];                        $update['attach'] = $v['attach'];                        foreach ( $bindData as $k2 => $v2 )                        {                            if ( $key == "T_".$v2['kongjian'] )                            {                                $update["field_".$v2['field']] = $val;                            }                        }                    }                    $CNOA_DB->db_update( $update, $this->t_odoc_data, "WHERE `otherAppId` = ".$tempArr[$v['uFlowId']]." " );                }            }            else            {                $CNOA_DB->db_update( array(                    "status" => 1,                    "uFlowId" => $v['uFlowId']                ), $this->t_odoc_data, "WHERE `otherAppId` = ".$tempArr[$v['uFlowId']]." " );            }        }    }    private function _formatSearch( )    {        $where = "";        $i = 0;        for ( ; $i < 3; ++$i )        {            $kong = getpar( $_POST, "kong".$i, "" );            if ( !empty( $kong ) )            {                $where = "";            }        }    }    private function _getJsonList( )    {        global $CNOA_DB;        global $CNOA_SESSION;        $uid = $CNOA_SESSION->get( "UID" );        $this->_checkData( );        $WHERE = "WHERE `from` = 'send' ";        $start = getpar( $_POST, "start", 0 );        $dblist = $CNOA_DB->db_select( "*", $this->t_odoc_data, $WHERE.( "AND `uid` = ".$uid." ORDER BY `id` DESC LIMIT {$start}, {$this->rows} " ) );        if ( !is_array( $dblist ) )        {            $dblist = array( );        }        foreach ( $dblist as $k => $v )        {            $dblist[$k]['posttime'] = date( "Y-m-d", $v['posttime'] );        }        ( );        $ds = new dataStore( );        $ds->data = $dblist;        echo $ds->makeJsonData( );        exit( );    }    private function _loadColumn( )    {        global $CNOA_DB;        $dblist = $CNOA_DB->db_select( "*", $this->t_odoc_view_field_list, "WHERE `from` = 'send' ORDER BY `order` ASC" );        if ( !is_array( $dblist ) )        {            $dblist = array( );        }        $field = array( );        foreach ( $dblist as $k => $v )        {            $field[] = "field_".$v['field'];        }        $result = array(            "success" => TRUE,            "field" => $field,            "column" => $dblist        );        echo json_encode( $result );        exit( );    }    private function _submitData( )    {        global $CNOA_DB;        global $CNOA_SESSION;        $odocId = getpar( $_POST, "ococ_send_apply_flowList", 0 );        unset( $_POST['ococ_send_apply_flowList'] );        $uid = $CNOA_SESSION->get( "UID" );        $data = array(            "posttime" => $GLOBALS['CNOA_TIMESTAMP'],            "uid" => $uid        );        foreach ( $GLOBALS['_POST'] as $k => $v )        {            $data[$k] = $v;        }        unset( $data['id'] );        $data['odocId'] = $odocId;        $data['from'] = "send";        $id = getpar( $_POST, "id", 0 );        if ( empty( $id ) )        {            $id = $CNOA_DB->db_insert( $data, $this->t_odoc_data );        }        else        {            $CNOA_DB->db_update( $data, $this->t_odoc_data, "WHERE `id` = ".$id." " );        }        msg::callback( TRUE, $id );    }    private function _getFaqiFlow( )    {        global $CNOA_DB;        global $CNOA_SESSION;        $id = getpar( $_POST, "id", 0 );        $data = $CNOA_DB->db_getone( "*", $this->t_odoc_data, "WHERE `id` = ".$id." " );        $bindData = $CNOA_DB->db_select( "*", $this->t_odoc_bind_flow_kj, "WHERE `from` = 'send' AND `id`='".$data['odocId']."' " );        if ( empty( $bindData[0]['flowId'] ) )        {            msg::callback( FALSE, "此公文流程未绑定工作流中的流程，请联系管理员进行绑定！" );        }        $flowDB = $CNOA_DB->db_getone( array( "nameRule", "tplSort", "flowType", "flowId" ), "wf_s_flow", "WHERE `flowId` = ".$bindData[0]['flowId']." " );        if ( !is_array( $bindData ) )        {            $bindData = array( );        }        $source = array( );        foreach ( $bindData as $k => $v )        {            if ( !empty( $v['kongjian'] ) )            {                $source[$v['kongjian']] = $data["field_".$v['field']];            }        }        $source = addslashes( json_encode( $source ) );        $otherAppId = $CNOA_DB->db_insert( array(            "posttime" => $GLOBALS['CNOA_TIMESTAMP'],            "data" => $source        ), $this->t_s_flow_other_app_data );        $result = array(            "success" => TRUE,            "flowId" => $flowDB['flowId'],            "nameRule" => $flowDB['nameRule'],            "tplSort" => $flowDB['tplSort'],            "flowType" => $flowDB['flowType'],            "otherApp" => $otherAppId        );        $CNOA_DB->db_update( array(            "otherAppId" => $otherAppId        ), $this->t_odoc_data, "WHERE `id` = ".$id." " );        echo json_encode( $result );        exit( );    }    private function _loadFormData( )    {        global $CNOA_DB;        global $CNOA_SESSION;        $id = getpar( $_POST, "id", 0 );        $data = $CNOA_DB->db_getone( "*", $this->t_odoc_data, "WHERE `id` = ".$id." " );        ( );        $ds = new dataStore( );        $data['ococ_send_apply_flowList'] = $data['odocId'];        unset( $data['odocId'] );        $ds->data = $data;        echo $ds->makeJsonData( );        exit( );    }    private function _deleteData( )    {        global $CNOA_DB;        $ids = getpar( $_POST, "ids", 0 );        $ids = substr( $ids, 0, -1 );        $CNOA_DB->db_delete( $this->t_odoc_data, "WHERE `id` IN (".$ids.") " );        msg::callback( TRUE, lang( "successopt" ) );    }    private function _getSearchList( )    {        global $CNOA_DB;        $dblist = $CNOA_DB->db_select( "*", $this->t_agent_view_field_list, "WHERE 1" );        if ( !is_array( $dblist ) )        {            $dblist = array( );        }        foreach ( $dblist as $k => $v )        {        }        ( );        $ds = new dataStore( );        $ds->data = $dblist;        echo $ds->makeJsonData( );        exit( );    }    public function api_loadColumn( )    {        $this->_loadColumn( );    }    private function _submitIssue( )    {        global $CNOA_DB;        global $CNOA_SESSION;        $cuid = $CNOA_SESSION->get( "UID" );        $id = getpar( $_POST, "id" );        $recvMan = getpar( $_POST, "recvMan" );        $recvUids = getpar( $_POST, "recvUids" );        $viewConfig = array( );        $viewConfig['viewForm'] = getpar( $_POST, "viewForm", "off" ) == "on" ? "1" : "0";        $viewConfig['viewWord'] = getpar( $_POST, "viewWord", "off" ) == "on" ? "1" : "0";        $viewConfig['viewAttach'] = getpar( $_POST, "viewAttach", "off" ) == "on" ? "1" : "0";        $odocInfo = $CNOA_DB->db_getone( "*", $this->t_odoc_data, "WHERE `id` = ".$id." " );        $uFlowId = $odocInfo['uFlowId'];        $uFlowInfo = $CNOA_DB->db_getone( array( "flowId", "attach" ), "wf_u_flow", "WHERE `uFlowId`='".$uFlowId."'" );        $argMan = explode( ",", $recvMan );        $argUid = explode( ",", $recvUids );        $wfFenFa = array( );        $wfFenFa['fenfauid'] = $cuid;        include( CNOA_PATH."/app/wf/inc/wfCommon.class.php" );        include( CNOA_PATH."/app/wf/inc/wfCache.class.php" );        include( CNOA_PATH."/app/wf/inc/wfFieldFormaterForView.class.php" );        $formHtml = "<!-- <?php echo '-'.'->disallow !';exit;?> --><div class='wf_form_content'>";        $formHtml .= app::loadapp( "wf", "flowUseView" )->api_getFormHtml( $uFlowId );        $formHtml .= "</div>";        $targetPath = ( CNOA_PATH_FILE."/common/odoc/sendForm/".$uFlowId % 300 )."/";        mkdirs( $targetPath );        @file_put_contents( $targetPath.$uFlowId.".php", $formHtml );        $attachs = array( );        ( );        $fs = new fs( );        if ( $viewConfig['viewWord'] == 1 )        {            $source = CNOA_PATH_FILE.( "/common/wf/use/".$uFlowInfo['flowId']."/{$uFlowId}/doc.history.0.php" );            if ( file_exists( $source ) )            {                $attachId = $fs->addFromInternal( $source, "[正文]审批正文.doc", $cuid, 8 );                $attachs[] = $attachId;            }        }        ( );        $fs = new fs( );        if ( $viewConfig['viewAttach'] == 1 )        {            $attachList = json_decode( $uFlowInfo['attach'], TRUE );            if ( is_array( $attachList ) )            {                foreach ( $attachList as $v )                {                    $attachInfo = $fs->getFileInfoById( $v, TRUE );                    if ( file_exists( $attachInfo['realPath'] ) )                    {                        $attachId = $fs->addFromInternal( $attachInfo['realPath'], "[附件]".$attachInfo['oldname'], $cuid, 8 );                        $attachs[] = $attachId;                    }                }            }        }        $CNOA_DB->db_update( array(            "attach" => json_encode( $attachs )        ), $this->t_odoc_data, "WHERE `id` = ".$id );        foreach ( $argUid as $uid )        {            $data = array( );            $data['id'] = $id;            $data['viewForm'] = $viewConfig['viewForm'];            $data['viewWord'] = $viewConfig['viewWord'];            $data['viewAttach'] = $viewConfig['viewAttach'];            $data['from'] = "send";            $data['uid'] = $uid;            $data['postuid'] = $cuid;            $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];            $data['uFlowId'] = $uFlowId;            $CNOA_DB->db_delete( $this->t_odoc_fenfa, "WHERE `uid`='".$uid."' AND `id`='{$id}'" );            $CNOA_DB->db_insert( $data, $this->t_odoc_fenfa );            $noticeT = "发文分发";            $noticeC = "你有一条发文信息分发给您";            $noticeH = "index.php?app=odoc&func=read&action=send";            notice::add( $uid, $noticeT, $noticeC, $noticeH, 0, 17 );            $wfFenFa['touid'] = $uid;            $wfFenFa['uFlowId'] = $uFlowId;            $CNOA_DB->db_delete( "wf_u_fenfa", "WHERE `touid`='".$uid."'" );            $CNOA_DB->db_insert( $wfFenFa, "wf_u_fenfa" );        }        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3004, $recvMan, "分发发文" );        msg::callback( TRUE, lang( "successopt" ) );    }    private function _submitSign( )    {        global $CNOA_DB;        global $CNOA_SESSION;        $data['deptment'] = getpar( $_POST, "deptment", "" );        $data['title'] = getpar( $_POST, "title", "" );        $data['number'] = getpar( $_POST, "number", "" );        $data['print'] = getpar( $_POST, "print", 0 );        $data['from'] = "receive";        $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];        $data['pid'] = getpar( $_POST, "pid", 0 );        $data['withWord'] = getpar( $_POST, "withWord", "off" ) == "on" ? "1" : "0";        $data['withAttach'] = getpar( $_POST, "withAttach", "off" ) == "on" ? "1" : "0";        $uid = getpar( $_POST, "uids", 0 );        $data['uid'] = $uid;        $uFlowId = $CNOA_DB->db_getfield( "uFlowId", $this->t_odoc_data, "WHERE `id`='".$data['pid']."'" );        $uFlowInfo = $CNOA_DB->db_getone( array( "flowId", "attach" ), "wf_u_flow", "WHERE `uFlowId`='".$uFlowId."'" );        $data['attach'] = array( );        ( );        $fs = new fs( );        if ( $data['withWord'] == 1 )        {            $source = CNOA_PATH_FILE.( "/common/wf/use/".$uFlowInfo['flowId']."/{$uFlowId}/doc.history.0.php" );            if ( file_exists( $source ) )            {                $attachId = $fs->addFromInternal( $source, "[正文]审批正文.doc", $uid, 8 );                $data['attach'][] = $attachId;            }        }        ( );        $fs = new fs( );        if ( $data['withAttach'] == 1 )        {            $attachList = json_decode( $uFlowInfo['attach'], TRUE );            if ( is_array( $attachList ) )            {                foreach ( $attachList as $v )                {                    $attachInfo = $fs->getFileInfoById( $v, TRUE );                    if ( file_exists( $attachInfo['realPath'] ) )                    {                        $attachId = $fs->addFromInternal( $attachInfo['realPath'], "[附件]".$attachInfo['oldname'], $uid, 8 );                        $data['attach'][] = $attachId;                    }                }            }        }        $data['attach'] = json_encode( $data['attach'] );        $id = $CNOA_DB->db_insert( $data, $this->t_odoc_data );        $noticeT = "公文签发";        $noticeC = "你有一条公文信息需要签收,".lang( "title" ).":".$data['title'];        $noticeH = "index.php?app=odoc&func=receive&action=apply";        notice::add( $uid, $noticeT, $noticeC, $noticeH, 0, 17 );        msg::callback( TRUE, lang( "successopt" ) );    }}?>