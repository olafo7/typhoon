<?php

class wfFlowUseNewfree extends wfCommon
{

    public function run( )
    {
        $task = getpar( $_GET, "task" );
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
        case "ms_loadTemplateFile" :
            $this->_ms_loadTemplateFile( );
            break;
        case "ms_submitMsOfficeData" :
            $this->_ms_submitMsOfficeData( );
            break;
        case "loadStepFormData" :
            $this->_loadStepFormData( );
            break;
        case "seqFlowSendNextStep" :
            $this->_seqFlowSendNextStep( );
            break;
        case "freeFlowSendNextStep" :
            $this->_freeFlowSendNextStep( );
            break;
        case "loadUflowInfo" :
            $this->_loadUflowInfo( );
            break;
        case "saveFlow" :
            $this->_saveFlow( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        global $CNOA_DB;
        $from = getpar( $_GET, "from", "" );
        if ( $from == "myflow" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/list.htm";
        }
        else if ( $from == "flowdesign" )
        {
            $GLOBALS['GLOBALS']['app']['flowId'] = getpar( $_GET, "flowId", 0 );
            $GLOBALS['GLOBALS']['app']['uFlowId'] = getpar( $_GET, "uFlowId", 0 );
            $GLOBALS['GLOBALS']['app']['flowType'] = getpar( $_GET, "flowType", 0 );
            $GLOBALS['GLOBALS']['app']['tplSort'] = getpar( $_GET, "tplSort", 0 );
            $GLOBALS['GLOBALS']['app']['action'] = "add";
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/newfree_flowdesign.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _loadStepFormData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uFlowId = getpar( $_GET, "uFlowId", 0 );
        $dblist = $CNOA_DB->db_select( "*", $this->t_use_step, "WHERE `uFlowId`='".$uFlowId."' ORDER BY `uStepId` ASC " );
        foreach ( $dblist as $k => $v )
        {
            $uids[] = $v['uid'];
        }
        $userList = app::loadapp( "main", "user" )->api_getUserInfoByUids( $uids );
        $unames = array( );
        $uids = array( );
        $stepct = array( );
        foreach ( $dblist as $k => $v )
        {
            $unames[] = "[".$userList[$v['uid']]['truename']."]";
            $listName[] = $userList[$v['uid']]['truename'];
            $uids[] = $v['uid'];
            $stepct[] = $v['stepname'];
        }
        ( );
        $ds = new dataStore( );
        if ( empty( $unames ) )
        {
            $ds->data = array( );
        }
        else
        {
            $ds->data = array(
                "uid" => implode( ",", $uids ),
                "uname" => "起始人".implode( "->", $unames )."结束人",
                "getname" => implode( ",", $listName ),
                "stepname" => implode( ",", $stepct )
            );
        }
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _ms_loadTemplateFile( )
    {
        global $CNOA_DB;
        $flowType = getpar( $_GET, "flowType", 0 );
        $tplSort = getpar( $_GET, "tplSort", 0 );
        $flowId = getpar( $_GET, "flowId", 0 );
        $editAction = getpar( $_GET, "editAction", "add" );
        $uFlowId = getpar( $_GET, "uFlowId", 0 );
        $this->api_loadTemplateFile( $flowId, $uFlowId, $flowType, $tplSort, $editAction );
        exit( );
    }

    private function _loadUflowInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $flowId = getpar( $_POST, "flowId", 0 );
        $editAction = getpar( $_POST, "editAction", "add" );
        $uFlowId = getpar( $_POST, "uFlowId", 0 );
        $data = array( );
        if ( $editAction == "edit" )
        {
            $baseInfo = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `flowId`='".$flowId."' AND `uFlowId`='{$uFlowId}'" );
            $data['flowNumber'] = $baseInfo['flowNumber'];
            $data['flowName'] = $baseInfo['flowName'];
            $data['reason'] = $baseInfo['reason'];
            $data['level'] = $baseInfo['level'];
            $data['uFlowId'] = $uFlowId;
            $data['htmlFormContent'] = $baseInfo['htmlFormContent'];
            ( );
            $fs = new fs( );
            $attach = $fs->getListInfo4wf( json_decode( $baseInfo['attach'], TRUE ), "", FALSE, "new" );
        }
        else
        {
            $baseInfo = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$flowId."'" );
            $data['flowNumber'] = $baseInfo['nameRule'];
            $data['uFlowId'] = 0;
        }
        $config = array( );
        $configInfo = $CNOA_DB->db_getone( array( "nameRuleAllowEdit", "nameRule", "nameDisallowBlank" ), $this->t_set_flow, "WHERE `flowId`='".$flowId."'" );
        $config['allowEditFlowNumber'] = $configInfo['nameRuleAllowEdit'];
        $config['nameDisallowBlank'] = $configInfo['nameDisallowBlank'];
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        $ds->attach = $attach;
        $ds->config = $config;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _ms_submitMsOfficeData( )
    {
        $flowId = intval( getpar( $_GET, "flowId", 0 ) );
        $uFlowId = getpar( $_GET, "uFlowId", 0 );
        $tplSort = getpar( $_GET, "tplSort", 0 );
        $this->api_saveTemplateData( $flowId, $uFlowId, $tplSort );
        exit( );
    }

    private function _seqFlowSendNextStep( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $flowType = getpar( $_GET, "flowType", 0 );
        $tplSort = getpar( $_GET, "tplSort", 0 );
        $uFlowId = getpar( $_POST, "uFlowId", 0 );
        $flowId = getpar( $_POST, "flowId", 0 );
        $data = array( );
        $flowInfo = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$flowId."'" );
        $data['flowId'] = $flowId;
        $data['flowName'] = getpar( $_POST, "flowName", "" );
        $data['level'] = getpar( $_POST, "level", 0 );
        $data['reason'] = getpar( $_POST, "reason", 0 );
        $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['updatetime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['uid'] = $uid;
        $data['status'] = 1;
        $data['sortId'] = $flowInfo['sortId'];
        $data['tplSort'] = $flowInfo['tplSort'];
        $flowNumber = getpar( $_POST, "flowNumber", "" );
        $data['flowNumber'] = $this->api_formatFlowNumber( $flowNumber, $flowInfo['name'], $flowInfo['nameRuleId'] + 1, $flowId );
        $data['htmlFormContent'] = getpar( $_POST, "htmlFormContent", "", 1, 0 );
        $data['allowCallback'] = 1;
        $data['allowCancel'] = 1;
        $data['changeFlowInfo'] = getpar( $_POST, "changeFlowInfo" ) == "on" ? 1 : 0;
        $uids = getpar( $_POST, "uid", 0 );
        $uidArr = explode( ",", $uids );
        $CNOA_DB->query( ( "UPDATE ".tname( $this->t_set_flow )." SET `nameRuleId`='".( $flowInfo['nameRuleId'] + 1 ) ).( "' WHERE `flowId`=".$flowId ) );
        $uFlowInfo = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$uFlowId."' " );
        $stepname = getpar( $_POST, "stepname", 0 );
        $stepct = explode( ",", $stepname );
        $stepdg = array( );
        foreach ( $stepct as $k => $v )
        {
            $stepdg[] = $v;
        }
        $attachList = array( );
        foreach ( $GLOBALS['_POST'] as $k => $v )
        {
            if ( ereg( "wf_attach_", $k ) )
            {
                $attachList[] = intval( str_replace( "wf_attach_", "", $k ) );
            }
        }
        if ( empty( $uFlowId ) )
        {
            ( );
            $fs = new fs( );
            $retuns = $fs->uploadFile4Wf( $attachList, array( ) );
            $data['attach'] = json_encode( $retuns[0] );
            $uFlowId = $CNOA_DB->db_insert( $data, $this->t_use_flow );
        }
        else
        {
            ( );
            $fs = new fs( );
            $attachListOld = json_decode( $uFlowInfo['attach'], TRUE );
            if ( !is_array( $attachListOld ) )
            {
                $attachListOld = array( );
            }
            $retuns = $fs->uploadFile4Wf( $attachList, $attachListOld );
            $data['attach'] = json_encode( $retuns[0] );
            $CNOA_DB->db_update( $data, $this->t_use_flow, "WHERE `flowId`='".$flowId."' AND `uFlowId`='{$uFlowId}'" );
            $stepNum = $CNOA_DB->db_getcount( $this->t_use_step, "WHERE `uFlowId`='".$uFlowId."'" );
            if ( 0 < $stepNum )
            {
                $CNOA_DB->db_delete( $this->t_use_step, "WHERE `uFlowId`='".$uFlowId."'" );
            }
        }
        $startStep = array( );
        $startStep['uFlowId'] = $uFlowId;
        $startStep['uid'] = $uid;
        $startStep['uStepId'] = 1;
        $startStep['pStepId'] = 0;
        $startStep['nStepId'] = 2;
        $startStep['stepname'] = "开始";
        $startStep['status'] = 2;
        $startStep['dealUid'] = $uid;
        $startStep['stime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $startStep['etime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $startStep['stepType'] = 1;
        $CNOA_DB->db_insert( $startStep, $this->t_use_step );
        $nextStepProxyUid = 0;
        $nextStepProxyUid = $this->getProxyUid( $flowId, $uidArr[0] );
        $this->insertProxyData( $flowId, $uFlowId, $uidArr[0], $nextStepProxyUid );
        $fromid = 0;
        $i = 2;
        $stepData = array( );
        foreach ( $uidArr as $k => $v )
        {
            $stepData['uFlowId'] = $uFlowId;
            $stepData['uid'] = $v;
            $stepData['uStepId'] = $i;
            $stepData['stepname'] = $stepdg[$k];
            $stepData['pStepId'] = $i - 1;
            $stepData['nStepId'] = $i + 1;
            if ( $i == 2 )
            {
                $stepData['status'] = 1;
            }
            else
            {
                $stepData['status'] = 0;
            }
            $stepData['proxyUid'] = $nextStepProxyUid;
            $stepData['stime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $stepData['stepType'] = 1;
            $id = $CNOA_DB->db_insert( $stepData, $this->t_use_step );
            if ( $i == 2 )
            {
                $fromid = $id;
            }
            ++$i;
        }
        $endStep = array( );
        $endStep['uFlowId'] = $uFlowId;
        $endStep['uid'] = 0;
        $endStep['uStepId'] = $i;
        $endStep['pStepId'] = $i - 1;
        $endStep['nStepId'] = 0;
        $endStep['stepname'] = "结束";
        $endStep['status'] = 0;
        $endStep['stepType'] = 3;
        $CNOA_DB->db_insert( $endStep, $this->t_use_step );
        $event = array( );
        $event['type'] = 1;
        $event['uFlowId'] = $uFlowId;
        $event['step'] = 1;
        $event['stepname'] = "开始";
        $event['uid'] = $uid;
        $event['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $this->insertEvent( $event );
        $noticedata['content'] = lang( "flowName" )."[".$data['flowName']."]".lang( "bianHao" )."[".$data['flowNumber']."]".lang( "needYouApproval" );
        $noticedata['href'] = "&uFlowId=".$uFlowId."&flowId={$flowId}&step=2&flowType={$flowType}&tplSort={$tplSort}";
        $noticedata['fromid'] = $fromid;
        $this->doneAll( "both", $uFlowId, "todo" );
        $this->addNotice( "both", array(
            $uidArr[0],
            $nextStepProxyUid
        ), $noticedata, "todo" );
        ( );
        $ds = new dataStore( );
        $ds->data = array(
            "uFlowId" => $uFlowId
        );
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _freeFlowSendNextStep( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $flowId = getpar( $_GET, "flowId", 0 );
        $dealuid = getpar( $_POST, "dealuid", 0 );
        $stepname = getpar( $_POST, "stepname", "" );
        $uFlowId = getpar( $_POST, "uFlowId", 0 );
        $say = getpar( $_POST, "say", "" );
        if ( empty( $stepname ) )
        {
            msg::callback( FALSE, lang( "selectNextStep" ) );
        }
        $flowType = getpar( $_GET, "flowType", 0 );
        $tplSort = getpar( $_GET, "tplSort", 0 );
        $sms = getpar( $_POST, "sms", "false" );
        $sms = $sms == "false" ? FALSE : TRUE;
        $flowInfo = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$flowId."'" );
        $data = array( );
        $data['flowId'] = $flowId;
        $data['flowName'] = getpar( $_POST, "flowName", "" );
        $data['level'] = getpar( $_POST, "level", 0 );
        $data['reason'] = getpar( $_POST, "reason", "" );
        $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['updatetime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['uid'] = $uid;
        $data['status'] = 1;
        $data['sortId'] = $flowInfo['sortId'];
        $data['htmlFormContent'] = getpar( $_POST, "htmlFormContent", "", 1, 0 );
        $flowNumber = getpar( $_POST, "flowNumber", "" );
        $data['flowNumber'] = $this->api_formatFlowNumber( $flowNumber, $flowInfo['name'], $flowInfo['nameRuleId'] + 1, $flowId );
        $data['allowCallback'] = 1;
        $data['allowCancel'] = 1;
        $CNOA_DB->query( ( "UPDATE ".tname( $this->t_set_flow )." SET `nameRuleId`='".( $flowInfo['nameRuleId'] + 1 ) ).( "' WHERE `flowId`=".$flowId ) );
        $uFlowInfo = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$uFlowId."' " );
        $attachList = array( );
        foreach ( $GLOBALS['_POST'] as $k => $v )
        {
            if ( ereg( "wf_attach_", $k ) )
            {
                $attachList[] = intval( str_replace( "wf_attach_", "", $k ) );
            }
        }
        if ( empty( $uFlowId ) )
        {
            ( );
            $fs = new fs( );
            $retuns = $fs->uploadFile4Wf( $attachList, array( ) );
            $data['attach'] = json_encode( $retuns[0] );
            $uFlowId = $CNOA_DB->db_insert( $data, $this->t_use_flow );
        }
        else
        {
            ( );
            $fs = new fs( );
            $attachListOld = json_decode( $uFlowInfo['attach'], TRUE );
            if ( !is_array( $attachListOld ) )
            {
                $attachListOld = array( );
            }
            $retuns = $fs->uploadFile4Wf( $attachList, $attachListOld );
            $data['attach'] = json_encode( $retuns[0] );
            $CNOA_DB->db_update( $data, $this->t_use_flow, "WHERE `flowId`='".$flowId."' AND `uFlowId`='{$uFlowId}'" );
        }
        $stepData = array( );
        $stepData['uFlowId'] = $uFlowId;
        $stepData['uid'] = $uid;
        $stepData['stepname'] = "开始";
        $stepData['uStepId'] = 1;
        $stepData['pStepId'] = 0;
        $stepData['nStepId'] = 2;
        $stepData['status'] = 2;
        $stepData['stime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $stepData['stepType'] = 1;
        $CNOA_DB->db_insert( $stepData, $this->t_use_step );
        $nextStepProxyUid = 0;
        $nextStepProxyUid = $this->getProxyUid( $flowId, $dealuid );
        $this->insertProxyData( $flowId, $uFlowId, $dealuid, $nextStepProxyUid );
        $nextStepData = array( );
        $nextStepData['uFLowId'] = $uFlowId;
        $nextStepData['uid'] = $dealuid;
        $nextStepData['stepname'] = $stepname;
        $nextStepData['uStepId'] = 2;
        $nextStepData['pStepId'] = 1;
        $nextStepData['nStepId'] = 3;
        $nextStepData['stime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $nextStepData['status'] = 1;
        $nextStepData['stepType'] = 2;
        $nextStepData['proxyUid'] = $nextStepProxyUid;
        $thisStep = $CNOA_DB->db_insert( $nextStepData, $this->t_use_step );
        $event = array( );
        $event['uFlowId'] = $uFlowId;
        $event['type'] = 1;
        $event['step'] = 1;
        $event['uid'] = $uid;
        $event['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $event['say'] = $say;
        $event['stepname'] = "开始";
        $this->insertEvent( $event );
        $noticedata['content'] = lang( "flowName" )."[".$data['flowName']."]".lang( "bianHao" )."[".$data['flowNumber']."]".lang( "needYouApproval" );
        $noticedata['href'] = "&uFlowId=".$uFlowId."&flowId={$flowId}&step=2&flowType={$flowType}&tplSort={$tplSort}";
        $noticedata['fromid'] = $thisStep;
        $this->addNotice( "both", array(
            $dealuid,
            $nextStepProxyUid
        ), $noticedata, "todo" );
        if ( $sms )
        {
            ( );
            $sms = new sms( );
            $sms->sendByUids( array(
                $dealuid,
                $nextStepProxyUid
            ), $noticedata['content'], 0, "flow", 0 );
        }
        ( );
        $ds = new dataStore( );
        $ds->data = array(
            "uFlowId" => $uFlowId
        );
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _saveFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $flowType = getpar( $_GET, "flowType", 0 );
        $tplSort = getpar( $_POST, "tplSort", 0 );
        $flowId = getpar( $_POST, "flowId", 0 );
        $uFlowId = getpar( $_POST, "uFlowId", 0 );
        $flowNumber = getpar( $_POST, "flowNumber", "" );
        $flowName = getpar( $_POST, "flowName", "" );
        $level = getpar( $_POST, "level", 0 );
        $reason = getpar( $_POST, "reason", "" );
        $htmlFormContent = getpar( $_POST, "htmlFormContent", "", 1, 0 );
        $flowInfo = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$flowId."'" );
        $flow = array( );
        $flow['flowId'] = $flowId;
        $flow['flowNumber'] = $flowNumber;
        $flow['flowName'] = $flowName;
        $flow['uid'] = $uid;
        $flow['level'] = $level;
        $flow['reason'] = $reason;
        $flow['posttime'] = 0;
        $flow['updatetime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $flow['edittime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $flow['sortId'] = $flowInfo['sortId'];
        $flow['status'] = 0;
        $flow['htmlFormContent'] = $htmlFormContent;
        $flow['allowCallback'] = 1;
        $flow['allowCancel'] = 1;
        $uFlowInfo = $CNOA_DB->db_getone( array( "flowId", "attach" ), $this->t_use_flow, "WHERE `flowId`='".$flowId."' AND `uFlowId`='{$uFlowId}'" );
        $attachList = array( );
        foreach ( $GLOBALS['_POST'] as $k => $v )
        {
            if ( ereg( "wf_attach_", $k ) )
            {
                $attachList[] = intval( str_replace( "wf_attach_", "", $k ) );
            }
        }
        $attachListOld = json_decode( $uFlowInfo['attach'], TRUE );
        if ( !is_array( $attachListOld ) )
        {
            $attachListOld = array( );
        }
        ( );
        $fs = new fs( );
        $retuns = $fs->uploadFile4Wf( $attachList, $attachListOld );
        $flow['attach'] = json_encode( $retuns[0] );
        if ( empty( $uFlowId ) )
        {
            $uFlowId = $CNOA_DB->db_insert( $flow, $this->t_use_flow );
        }
        else
        {
            $CNOA_DB->db_update( $flow, $this->t_use_flow, "WHERE `flowId`='".$flowId."' AND `uFlowId`='{$uFlowId}'" );
        }
        if ( $flowType == 1 )
        {
            $num = $CNOA_DB->db_getcount( $this->t_use_step, "WHERE `uFlowId`='".$uFlowId."'" );
            if ( 0 < $num )
            {
                $CNOA_DB->db_delete( $this->t_use_step, "WHERE `uFlowId`='".$uFlowId."'" );
            }
            $stepname = getpar( $_POST, "stepname", 0 );
            $stepct = explode( ",", $stepname );
            $stepdg = array( );
            foreach ( $stepct as $k => $v )
            {
                $stepdg[] = $v;
            }
            $uids = getpar( $_POST, "uid", 0 );
            $uidArr = explode( ",", $uids );
            $i = 1;
            $stepData = array( );
            foreach ( $uidArr as $k => $v )
            {
                $stepData['uFlowId'] = $uFlowId;
                $stepData['uid'] = $v;
                $stepData['uStepId'] = $i;
                $stepData['stepname'] = $stepdg[$k];
                $CNOA_DB->db_insert( $stepData, $this->t_use_step );
                ++$i;
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = array(
            "uFlowId" => $uFlowId,
            "attach" => $retuns[1],
            "saveTime" => formatdate( $GLOBALS['CNOA_TIMESTAMP'], " H时i分s秒 " )
        );
        echo $ds->makeJsonData( );
        exit( );
    }

}

?>
