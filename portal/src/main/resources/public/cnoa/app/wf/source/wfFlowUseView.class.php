<?php

class wfFlowUseView extends wfCommon
{

    public function run( )
    {
        $task = getpar( $_GET, "task", "loadPage" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "getJsonData" :
            $this->_getJsonData( );
            break;
        case "getFlowTypeData" :
            $dblist = app::loadapp( "wf", "flowUseTodo" )->api_getFlowTypeData( );
            echo json_encode( $dblist );
            exit( );
        case "loadFormHtml" :
            $this->_loadFormHtml( );
            break;
        case "loadUflowInfo" :
            $list = app::loadapp( "wf", "flowUseTodo" )->api_loadUflowInfo( );
            echo json_encode( $list );
            exit( );
        case "addReaderSay" :
            $this->_addReaderSay( );
            break;
        case "loadReaderSay" :
            $this->_loadReaderSay( );
            break;
        case "exportFlow" :
            app::loadapp( "wf", "flowUseExportHtml" )->init( );
            exit( );
        case "ms_loadTemplateFile" :
            $this->_ms_loadTemplateFile( );
            break;
        case "recallList" :
            $this->_recallList( );
            break;
        case "getDisposeType" :
            $this->_getDisposeType( );
            break;
        case "updateDisposeType" :
            $this->_updateDisposeType( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $uid = $CNOA_SESSION->get( "UID" );
        $from = getpar( $_GET, "from", "" );
        if ( $from == "list" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/view.htm";
        }
        else if ( $from == "viewflow" )
        {
            $GLOBALS['GLOBALS']['app']['uFlowId'] = getpar( $_GET, "uFlowId", 0 );
            $GLOBALS['GLOBALS']['app']['step'] = getpar( $_GET, "stepId", 0 );
            $GLOBALS['GLOBALS']['app']['flowId'] = getpar( $_GET, "flowId", 0 );
            $GLOBALS['GLOBALS']['app']['flowType'] = getpar( $_GET, "flowType", 0 );
            $GLOBALS['GLOBALS']['app']['tplSort'] = getpar( $_GET, "tplSort", 0 );
            $uFlowId = $GLOBALS['app']['uFlowId'];
            $GLOBALS['GLOBALS']['app']['readId'] = $CNOA_DB->db_getfield( "isread", $this->t_use_fenfa, "WHERE `uFlowId`='".$uFlowId."' AND `uid`='{$uid}'" );
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/viewflow.htm";
        }
        else if ( $from == "recall" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/recall.htm";
        }
        else if ( $from == "taohong" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/set/taohong.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $start = getpar( $_POST, "start", 0 );
        $storeType = getpar( $_POST, "storeType", "waiting" );
        $flowName = getpar( $_POST, "flowName", "" );
        $sortId = getpar( $_POST, "sname", 0 );
        $from = getpar( $_GET, "from", "" );
        if ( $from != "desktop" )
        {
            $limit = getpagesize( "wf_flow_use_view_getJsonData" );
            $limits = "LIMIT ".$start.",{$limit}";
        }
        $WHERE = "WHERE 1 ";
        $WHERE2 = "WHERE 1 ";
        switch ( $storeType )
        {
        case "waiting" :
            $WHERE .= "AND `isread`='0' ";
            break;
        case "finish" :
            $WHERE .= "AND `isread`='1' ";
            break;
        case "all" :
            $WHERE .= "";
        }
        $fenfaDB = $CNOA_DB->db_select( "*", $this->t_use_fenfa, $WHERE.( "AND `touid`='".$uid."'" ) );
        if ( !is_array( $fenfaDB ) )
        {
            $fenfaDB = array( );
        }
        $uFlowIdArr = array( 0 );
        $readArr = array( );
        foreach ( $fenfaDB as $k => $v )
        {
            $uFlowIdArr[] = $v['uFlowId'];
            $readArr[] = $v['isread'];
        }
        if ( !empty( $flowName ) )
        {
            $WHERE2 .= "AND (`flowName` LIKE '%".$flowName."%' OR `flowNumber` LIKE '%{$flowName}%') ";
        }
        if ( !empty( $sortId ) )
        {
            $WHERE2 .= "AND `sortId` = '".$sortId."' ";
        }
        $WHERE2 .= "AND `uFlowId` IN (".implode( ",", $uFlowIdArr ).") ";
        $dblist = $CNOA_DB->db_select( "*", $this->t_use_flow, $WHERE2.( " ORDER BY `uFlowId` DESC ".$limits ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $sortArr = array( 0 );
        $flowIds = array( 0 );
        $uids = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $flowIds[$v['flowId']] = $v['flowId'];
            $uids[] = $v['uid'];
            $sortArr[] = $v['sortId'];
        }
        $sortDB = $this->api_getSortByIds( $sortArr );
        $flowSetNames_tmp = $CNOA_DB->db_select( array( "name", "flowId", "flowType", "tplSort" ), $this->t_set_flow, "WHERE `flowId` IN (".implode( ",", $flowIds ).")" );
        if ( !is_array( $flowSetNames_tmp ) )
        {
            $flowSetNames_tmp = array( );
        }
        $flowSetNames = array( );
        $flowSetTplsort = array( );
        $flowSetFlowType = array( );
        foreach ( $flowSetNames_tmp as $fsv )
        {
            $flowSetNames[$fsv['flowId']] = $fsv['name'];
            $flowSetTplsort[$fsv['flowId']] = $fsv['tplSort'];
            $flowSetFlowType[$fsv['flowId']] = $fsv['flowType'];
        }
        $username = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uids );
        $stopStep = $CNOA_DB->db_select( array( "id", "uFlowId", "uStepId" ), $this->t_use_step, $WHERE2 );
        if ( !is_array( $stopStep ) )
        {
            $stopStep = array( );
        }
        $endStep = array( );
        foreach ( $stopStep as $v )
        {
            if ( isset( $endStep[$v['uFlowId']] ) )
            {
                if ( $endStep[$v['uFlowId']]['id'] < $v['id'] )
                {
                    $endStep[$v['uFlowId']] = $v;
                }
            }
            else
            {
                $endStep[$v['uFlowId']] = $v;
            }
        }
        unset( $stopStep );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['storeType'] = $storeType;
            $dblist[$k]['sname'] = $sortDB[$v['sortId']]['name'];
            $dblist[$k]['level'] = $this->f_level[$v['level']];
            $dblist[$k]['isread'] = $this->f_isread[$readArr[$k]];
            $dblist[$k]['sortposttime'] = $v['posttime'];
            $dblist[$k]['posttime'] = date( "Y-m-d H:i", $v['posttime'] );
            $dblist[$k]['flowSetName'] = $flowSetNames[$v['flowId']];
            $dblist[$k]['tplSort'] = $flowSetTplsort[$v['flowId']];
            $dblist[$k]['flowType'] = $flowSetFlowType[$v['flowId']];
            $dblist[$k]['uname'] = $username[$v['uid']]['truename'];
            $dblist[$k]['step'] = $endStep[$v['uFlowId']]['uStepId'];
            $disposeType = $CNOA_DB->db_getfield( "disposeType", $this->t_use_dispose_type, "WHERE `uid`=".$uid." AND `uFlowId`={$dblist[$k]['uFlowId']}" );
            $dblist[$k]['disposeType'] = $disposeType ? $disposeType : 4;
        }
        $dispose = getpar( $_POST, "sort" );
        $dir = getpar( $_POST, "dir" );
        if ( $dispose == "disposeType" )
        {
            if ( $dir == "DESC" )
            {
                $desc = TRUE;
            }
            else
            {
                $desc = FALSE;
            }
            $this->sortArrByField( $dblist, "disposeType", "sortposttime", $desc );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        $dataStore->total = $CNOA_DB->db_getcount( $this->t_use_flow, $WHERE2 );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function sortArrByField( &$array, $field1, $field2, $desc = FALSE )
    {
        $fieldArr = array( );
        foreach ( $array as $k => $v )
        {
            $fieldArr1[$k] = $v[$field1];
            $fieldArr2[$k] = $v[$field2];
        }
        $sort = !$desc ? SORT_DESC : SORT_ASC;
        array_multisort( $fieldArr1, $sort, $fieldArr2, $sort, $array );
    }

    private function _getReaderList( )
    {
        global $CNOA_DB;
        $uFlowId = getpar( $_GET, "uFlowId", 0 );
        $dblist = $CNOA_DB->db_select( "*", $this->t_use_fenfa, "WHERE `uFlowId`='".$uFlowId."'" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $uids = array( );
        $deptids = array( );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['viewtime'] = formatdate( $v['viewtime'] );
            $uids[$v['uid']] = $v['uid'];
        }
        $userList = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uids );
        if ( !is_array( $userList ) )
        {
            $userList = array( );
        }
        foreach ( $userList as $v )
        {
            $deptids[] = $v['deptId'];
        }
        $deptList = app::loadapp( "main", "struct" )->api_getNamesByIds( $deptids );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['uname'] = $userList[$v['uid']]['truename'];
            $dblist[$k]['deptName'] = $deptList[$deptids[$k]];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _loadFormHtml( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uFlowId = intval( getpar( $_GET, "uFlowId", 0 ) );
        ( $uFlowId );
        $GLOBALS['GLOBALS']['UWFCACHE'] = new wfCache( );
        $flowDB = $GLOBALS['UWFCACHE']->getFlow( );
        $nowStep = $CNOA_DB->db_getfield( "uStepId", $this->t_use_step, "WHERE `uFlowId`='".$uFlowId."' ORDER BY `id` DESC" );
        ( $GLOBALS['UWFCACHE']->getFlowId( ), $nowStep, FALSE, "done", $uFlowId );
        $wfFormHtmlForView = new wfFieldFormaterForView( );
        $formHtml = $wfFormHtmlForView->crteateFormHtml( );
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = 0;
        $dataStore->data = array(
            "formHtml" => $formHtml
        );
        $dataStore->pageset = $flowDB['pageset'];
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _addReaderSay( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $flowType = getpar( $_POST, "flowType", 0 );
        $tplSort = getpar( $_POST, "tplSort", 0 );
        $uFlowId = getpar( $_POST, "uFlowId", 0 );
        $stepId = getpar( $_POST, "stepId", 0 );
        $data = array( );
        $data['uid'] = $uid;
        $data['isread'] = 1;
        $data['say'] = getpar( $_POST, "say", "" );
        $data['viewtime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $DB = $CNOA_DB->db_getone( array( "fenfauid" ), $this->t_use_fenfa, "WHERE `uFlowId`='".$uFlowId."' AND `touid`='{$uid}'" );
        $fenfaName = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $DB['fenfauid'] );
        if ( !empty( $DB ) )
        {
            $CNOA_DB->db_update( $data, $this->t_use_fenfa, "WHERE `uFlowId`='".$uFlowId."' AND `touid`='{$uid}'" );
            $uFlowInfo = $CNOA_DB->db_getone( array( "flowName", "flowNumber", "flowId" ), $this->t_use_flow, "WHERE `uFlowId`='".$uFlowId."' " );
            $newD['content'] = lang( "flowName" )."[".$uFlowInfo['flowName']."]".lang( "bianHao" )."[".$uFlowInfo['flowNumber']."]".lang( "flowBeenReview" );
            $newD['href'] = "&uFlowId=".$uFlowId."&flowId={$uFlowInfo['flowId']}&step={$stepId}&flowType={$flowType}&tplSort={$tplSort}";
            $newD['fromid'] = $stepId;
            $this->addNotice( "notice", $DB['fenfauid'], $newD, "comment" );
        }
        echo json_encode( array(
            "success" => TRUE,
            "msg" => lang( "successopt" ),
            "fenfaName" => $fenfaName
        ) );
        exit( );
    }

    private function _loadReaderSay( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $uFlowId = getpar( $_POST, "uFlowId", 0 );
        $readData = $CNOA_DB->db_getone( array( "say" ), $this->t_use_fenfa, "WHERE `uFlowId`='".$uFlowId."' AND `touid`='{$uid}'" );
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = 0;
        $dataStore->data = $readData;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _ms_loadTemplateFile( )
    {
        global $CNOA_DB;
        $flowId = getpar( $_GET, "flowId", 0 );
        $uFlowId = getpar( $_GET, "uFlowId", 0 );
        $tplSort = getpar( $_GET, "tplSort", 0 );
        $docfilePath = CNOA_PATH_FILE.( "/common/wf/use/".$flowId."/{$uFlowId}/doc.history.0.php" );
        $xlsfilePath = CNOA_PATH_FILE.( "/common/wf/use/".$flowId."/{$uFlowId}/xls.history.0.php" );
        if ( $tplSort == 1 || $tplSort == 3 )
        {
            if ( file_exists( $docfilePath ) )
            {
                $file = @file_get_contents( $docfilePath );
            }
            else
            {
                mkdirs( dirname( $docfilePath ) );
                $file = " ";
            }
        }
        else if ( file_exists( $xlsfilePath ) )
        {
            $file = @file_get_contents( $xlsfilePath );
        }
        else
        {
            mkdirs( dirname( $xlsfilePath ) );
            $file = " ";
        }
        echo $file;
        exit( );
    }

    public function api_getFormHtml( $uFlowId )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        ( $uFlowId );
        $GLOBALS['GLOBALS']['UWFCACHE'] = new wfCache( );
        $nowStep = $CNOA_DB->db_getfield( "uStepId", $this->t_use_step, "WHERE `uFlowId`='".$uFlowId."' ORDER BY `id` DESC" );
        ( $GLOBALS['UWFCACHE']->getFlowId( ), $nowStep, FALSE, "done", $uFlowId );
        $wfFormHtmlForView = new wfFieldFormaterForView( );
        $formHtml = $wfFormHtmlForView->crteateFormHtml( );
        return $formHtml;
    }

    private function _recallList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $start = getpar( $_POST, "start", 0 );
        $row = 15;
        $flowName = getpar( $_POST, "flowName" );
        $suid = getpar( $_POST, "suid" );
        $stime = getpar( $_POST, "stime" );
        $etime = getpar( $_POST, "etime" );
        $and = " ";
        if ( !empty( $suid ) )
        {
            $and = " AND `uid`=".$suid;
        }
        if ( empty( $stime ) || empty( $etime ) || empty( $stime ) && !empty( $etime ) )
        {
            msg::callback( FALSE, lang( "stimeOrEtimeNotEmpty" ) );
        }
        if ( !empty( $stime ) || !empty( $etime ) )
        {
            $stime = strtotime( $stime."00:00:00" );
            $etime = strtotime( $etime."23:59:59" );
            if ( $etime < $stime )
            {
                msg::callback( FALSE, lang( "StimeGreaterThanEtime" ) );
            }
            $and .= " AND (`posttime` > ".$stime." AND `posttime` < {$etime})";
        }
        if ( $uid != 1 )
        {
            $where = " AND `uid`=".$uid;
        }
        else
        {
            $where = " ";
        }
        $rows = $CNOA_DB->db_select( array( "uFlowId" ), $this->t_use_event, " WHERE `type` =".self::EVENT_RECALL.$and." GROUP BY `uFlowId`" );
        $uFlowIds = $this->getFieldData( $rows, "uFlowId" );
        $uFlowIds = implode( ",", $uFlowIds );
        if ( !empty( $flowName ) )
        {
            $rows = $CNOA_DB->db_select( array( "uFlowId" ), $this->t_use_flow, " WHERE `flowName` LIKE '%".$flowName."%'" );
            $uFlowIds = $this->getFieldData( $rows, "uFlowId" );
            $uFlowIds = implode( ",", $uFlowIds );
        }
        $dblist = $CNOA_DB->db_select( array( "uFlowId" ), $this->t_use_event, " WHERE `uFlowId` IN (".$uFlowIds.") ".$where." GROUP BY `uFlowId`" );
        unset( $uFlowIds );
        $dblist = $this->getFieldData( $dblist, "uFlowId" );
        $dblist = implode( ",", $dblist );
        $events = $CNOA_DB->db_select( array( "uFlowId", "uid", "posttime", "say" ), $this->t_use_event, " WHERE `uFlowId` IN (".$dblist.") AND `type` = ".self::EVENT_RECALL.( " ORDER BY `uEventId` DESC LIMIT ".$start.", {$row}" ) );
        $total = $CNOA_DB->db_getcount( $this->t_use_event, " WHERE `uFlowId` IN (".$dblist.") AND `type` = ".self::EVENT_RECALL.$and );
        unset( $dblist );
        if ( !is_array( $events ) )
        {
            $events = array( );
        }
        $items = $data = array( );
        foreach ( $events as $key => $value )
        {
            $row = $this->getFlowIsset( $value['uFlowId'] );
            $flowArray = $CNOA_DB->db_getone( "*", $this->t_use_flow, " WHERE `uFlowId` = ".$value['uFlowId'] );
            $items['flowNumber'] = $flowArray['flowNumber'] ? $flowArray['flowNumber'] : "<span style='color: red;'>此流程已删除</span>";
            $items['flowName'] = $row ? $this->getFlowNameById( $value['uFlowId'] ) : "<span style='color: red;'>此流程已删除</span>";
            $items['truename'] = $this->getTrueName( $value['uid'] );
            $items['posttime'] = date( "Y-m-d H:i:s", $value['posttime'] );
            $items['event'] = $this->getFlowEvent( $value['uFlowId'] );
            $data[] = $items;
        }
        unset( $events );
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        $ds->total = $total;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function getFieldData( $data, $field )
    {
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        $items = array( );
        foreach ( $data as $key => $value )
        {
            if ( $value[$field] )
            {
                $items[] = $value[$field];
            }
        }
        return $items;
    }

    private function getTrueName( $uid )
    {
        return app::loadapp( "main", "user" )->api_getUserTruenameByUid( $uid );
    }

    private function getFlowNameById( $uFlowId )
    {
        global $CNOA_DB;
        $flowName = $CNOA_DB->db_getfield( "flowName", $this->t_use_flow, " WHERE `uFlowId`=".$uFlowId );
        return $flowName;
    }

    private function getFlowIsset( $uFlowId )
    {
        global $CNOA_DB;
        $row = $CNOA_DB->db_getcount( $this->t_use_flow, " WHERE `uFlowId`=".$uFlowId );
        return $row;
    }

    private function getFlowEvent( $uFlowId )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "uEventId", "uFlowId", "type", "stepname", "uid" ), $this->t_use_event, " WHERE `uFlowId`=".$uFlowId." ORDER BY `uEventId` ASC" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $temp = $data = array( );
        foreach ( $dblist as $key => $value )
        {
            $temp[$value['uFlowId']][] = $this->getTrueName( $value['uid'] ).$this->getType( $value['type'] );
            $data = implode( "->", $temp[$value['uFlowId']] );
        }
        return $data;
    }

    private function getType( $type )
    {
        switch ( $type )
        {
        case self::EVENT_START :
            $typeText = "(开始)";
            return $typeText;
        case self::EVENT_DEAL :
            $typeText = "(办理)";
            return $typeText;
        case self::EVENT_BACKOUT :
            $typeText = "(撤销)";
            return $typeText;
        case self::EVENT_RETURN :
            $typeText = "(退回)";
            return $typeText;
        case self::EVENT_REJECT :
            $typeText = "(拒绝)";
            return $typeText;
        case self::EVENT_END :
            $typeText = "(结束)";
            return $typeText;
        case self::EVENT_RECALL :
            $typeText = "(召回)";
            return $typeText;
        case self::EVENT_TRUST :
            $typeText = "(委托)";
            return $typeText;
        case self::EVENT_RESERVATION :
            $typeText = "(保留意见)";
            return $typeText;
        case self::EVENT_BACKOFF :
            $typeText = "(中止)";
        }
        return $typeText;
    }

    private function _getDisposeType( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $uFlowId = getpar( $_POST, "uFlowId", "0" );
        $data = $CNOA_DB->db_getone( "*", $this->t_use_dispose_type, "WHERE `uid`=".$uid." AND `uFlowId`= {$uFlowId}" );
        if ( $data )
        {
            ( );
            $ds = new dataStore( );
            $ds->data = $data;
            echo $ds->makeJsonData( );
        }
        exit( );
    }

    private function _updateDisposeType( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $data['uid'] = $CNOA_SESSION->get( "UID" );
        $data['uFlowId'] = getpar( $_POST, "uFlowId", "0" );
        $data['disposeType'] = getpar( $_POST, "disposeType", "0" );
        $result = $CNOA_DB->db_getcount( $this->t_use_dispose_type, "WHERE `uid`=".$data['uid']." AND `uFlowId`= {$data['uFlowId']}" );
        if ( 0 < $result )
        {
            $type = $CNOA_DB->db_update( array(
                "disposeType" => $data['disposeType']
            ), $this->t_use_dispose_type, "WHERE `uid`=".$data['uid']." AND `uFlowId`= {$data['uFlowId']}" );
        }
        else
        {
            $type = $CNOA_DB->db_insert( $data, $this->t_use_dispose_type );
        }
        if ( $type )
        {
            msg::callback( TRUE, lang( "editSuccess" ) );
        }
        else
        {
            msg::callback( FALSE, lang( "editFail" ) );
        }
    }

}

?>
