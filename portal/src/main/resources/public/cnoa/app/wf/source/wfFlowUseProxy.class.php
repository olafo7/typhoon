<?php

class wfFlowUseProxy extends wfCommon
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
        case "getMyProxyRecordList" :
            $this->_getMyProxyRecordList( );
            break;
        case "getTrustedRecordList" :
            $this->_getTrustedRecordList( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $userList );
            exit( );
        case "add" :
            $this->_add( );
            break;
        case "loadFormData" :
            $this->_loadFormData( );
            break;
        case "edit" :
            $this->_edit( );
            break;
        case "delete" :
            $this->_delete( );
            break;
        case "setProxyStatus" :
            $this->_setProxyStatus( );
            break;
        case "getProxyFlowList" :
            $this->_getProxyFlowList( );
            break;
        case "getProxyUflowList" :
            $this->_getProxyUflowList( );
            break;
        case "takeBackAllProxyFlow" :
            $this->_takeBackAllProxyFlow( );
            break;
        case "takeBackAllUflow" :
            $this->_takeBackAllUflow( );
            break;
        case "takeBackUflow" :
            $this->_takeBackUflow( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $from = getpar( $_GET, "from", "" );
        if ( $from == "list" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/proxy.htm";
        }
        else if ( $from == "proxyView_flow" )
        {
            $GLOBALS['GLOBALS']['app']['proxy']['id'] = getpar( $_GET, "id", 0 );
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/protyView_flow.htm";
        }
        else if ( $from == "proxyView_uflow" )
        {
            $GLOBALS['GLOBALS']['app']['proxy']['flowId'] = getpar( $_GET, "flowId", 0 );
            $GLOBALS['GLOBALS']['app']['proxy']['fromuid'] = getpar( $_GET, "fromuid", 0 );
            $GLOBALS['GLOBALS']['app']['proxy']['touid'] = getpar( $_GET, "touid", 0 );
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/protyView_uflow.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _getJsonData( $type = "" )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $start = getpar( $_POST, "start", 0 );
        $WHERE = "WHERE 1 ";
        if ( empty( $type ) )
        {
            $WHERE .= " AND `fromuid`='".$uid."' ";
        }
        $dblist = $CNOA_DB->db_select( "*", $this->t_use_proxy, $WHERE.( "ORDER BY `id` DESC LIMIT ".$start.", {$this->rows}" ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $uids = array( );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['stime'] = date( "Y-m-d H:i", $v['stime'] );
            if ( empty( $v['etime'] ) && $GLOBALS['CNOA_TIMESTAMP'] < $v['stime'] )
            {
                $dblist[$k]['etime'] = "— — — —";
                $dblist[$k]['proxyStatus'] = "未生效";
            }
            else if ( empty( $v['etime'] ) && $v['stime'] <= $GLOBALS['CNOA_TIMESTAMP'] )
            {
                $dblist[$k]['etime'] = "— — — —";
                $dblist[$k]['proxyStatus'] = "一直有效";
            }
            else
            {
                $dblist[$k]['etime'] = date( "Y-m-d H:i", $v['etime'] );
                if ( $v['etime'] < $GLOBALS['CNOA_TIMESTAMP'] )
                {
                    $dblist[$k]['proxyStatus'] = "已失效";
                }
                else if ( $v['stime'] <= $GLOBALS['CNOA_TIMESTAMP'] )
                {
                    $dblist[$k]['proxyStatus'] = "有效中";
                }
                else if ( $GLOBALS['CNOA_TIMESTAMP'] < $v['stime'] )
                {
                    $dblist[$k]['proxyStatus'] = "未生效";
                }
            }
            $uids[$v['fromuid']] = $v['fromuid'];
            $uids[$v['touid']] = $v['touid'];
        }
        $userNames = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uids );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['fromName'] = $userNames[$v['fromuid']]['truename'];
            $dblist[$k]['toName'] = $userNames[$v['touid']]['truename'];
            $num = $CNOA_DB->db_getcount( $this->t_use_proxy_flow, "WHERE `uProxyId`='".$v['id']."'" );
            if ( 0 < $num )
            {
                $dblist[$k]['count'] = "<a style=\"color:red;text-decoration:underline\">".$num."</a>";
            }
            else
            {
                $dblist[$k]['count'] = 0;
            }
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        $dataStore->total = $CNOA_DB->db_getcount( $this->t_use_proxy, $WHERE );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getMyProxyRecordList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $start = getpar( $_POST, "start", 0 );
        $uid = $CNOA_SESSION->get( "UID" );
        $dblist = $CNOA_DB->db_select( "*", $this->t_use_proxy_uflow, "WHERE `fromuid`='".$uid."' ORDER BY `id` DESC LIMIT {$start}, {$this->rows}" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $uids = array( );
        $uFlowIdArr = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $uids[] = $v['fromuid'];
            $uFlowIdArr[] = $v['uFlowId'];
        }
        $flowDB = $CNOA_DB->db_select( array( "flowNumber", "flowName", "status", "uFlowId" ), $this->t_use_flow, "WHERE `uFlowId` IN (".implode( ",", $uFlowIdArr ).")" );
        if ( !is_array( $flowDB ) )
        {
            $flowDB = array( );
        }
        $flowCt = array( );
        foreach ( $flowDB as $k => $v )
        {
            $flowCt[$v['uFlowId']] = $v;
        }
        $userNames = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uids );
        $allStep = $CNOA_DB->db_select( array( "uFlowId", "uStepId" ), $this->t_use_step, "WHERE `uFlowId` IN (".implode( ",", $uFlowIdArr ).") AND (`status`=2 OR `status`=4)" );
        $uFlowStep = array( );
        if ( !is_array( $allStep ) )
        {
            $allStep = array( );
        }
        foreach ( $allStep as $v )
        {
            if ( isset( $uFlowStep[$v['uFlowId']] ) )
            {
                $v['uStepId'] = max( $uFlowStep[$v['uFlowId']], $v['uStepId'] );
            }
            $uFlowStep[$v['uFlowId']] = $v['uStepId'];
        }
        unset( $allStep );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['fromName'] = $userNames[$v['fromuid']]['truename'];
            $dblist[$k]['posttime'] = formatdate( $v['posttime'] );
            $dblist[$k]['flowNumber'] = $flowCt[$v['uFlowId']]['flowNumber'];
            $dblist[$k]['flowName'] = $flowCt[$v['uFlowId']]['flowName'];
            $dblist[$k]['statusText'] = $this->f_status[$flowCt[$v['uFlowId']]['status']];
            $dblist[$k]['step'] = $uFlowStep[$v['uFlowId']];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        $dataStore->total = $CNOA_DB->db_getcount( $this->t_use_proxy_uflow, "WHERE `fromuid`='".$uid."'" );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getTrustedRecordList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $start = getpar( $_POST, "start", 0 );
        $dblist = $CNOA_DB->db_select( "*", $this->t_use_proxy_uflow, "WHERE `touid`='".$uid."' ORDER BY `id` DESC LIMIT {$start}, {$this->rows}" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $uids = array( );
        $uFlowIdArr = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $uids[] = $v['fromuid'];
            $uFlowIdArr[] = $v['uFlowId'];
        }
        $flowDB = $CNOA_DB->db_select( array( "flowNumber", "flowName", "status", "uFlowId", "flowId" ), $this->t_use_flow, "WHERE `uFlowId` IN (".implode( ",", $uFlowIdArr ).")" );
        if ( !is_array( $flowDB ) )
        {
            $flowDB = array( );
        }
        $flowCt = array( );
        $flowSetFlowType = array( );
        $flowSetTplsort = array( );
        foreach ( $flowDB as $k => $v )
        {
            $flowSet = $CNOA_DB->db_getone( array( "flowType", "tplSort" ), $this->t_set_flow, "WHERE `flowId`='".$v['flowId']."'" );
            $flowCt[$v['uFlowId']] = $v;
            $flowSetFlowType[$v['uFlowId']] = $flowSet['flowType'];
            $flowSetTplsort[$v['uFlowId']] = $flowSet['tplSort'];
        }
        $userNames = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uids );
        $stepDB = $CNOA_DB->db_select( array( "uFlowId", "uStepId" ), $this->t_use_step, "WHERE `uFlowId` IN (".implode( ",", $uFlowIdArr ).( ") AND `proxyUid`=".$uid." AND `status`=1" ) );
        if ( !is_array( $stepDB ) )
        {
            $stepDB = array( );
        }
        $uStepIds = array( );
        foreach ( $stepDB as $v )
        {
            $uStepIds[$v['uFlowId']] = $v['uStepId'];
        }
        unset( $stepDB );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['fromName'] = $userNames[$v['fromuid']]['truename'];
            $dblist[$k]['posttime'] = formatdate( $v['posttime'] );
            $dblist[$k]['flowNumber'] = $flowCt[$v['uFlowId']]['flowNumber'];
            $dblist[$k]['flowName'] = $flowCt[$v['uFlowId']]['flowName'];
            $dblist[$k]['flowType'] = $flowSetFlowType[$v['uFlowId']];
            $dblist[$k]['tplSort'] = $flowSetTplsort[$v['uFlowId']];
            $dblist[$k]['statusText'] = $this->f_status[$flowCt[$v['uFlowId']]['status']];
            $dblist[$k]['step'] = $uStepIds[$v['uFlowId']];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        $dataStore->total = $CNOA_DB->db_getcount( $this->t_use_proxy_uflow, "WHERE `touid`='".$uid."'" );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getProxyFlowList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $start = getpar( $_POST, "start", 0 );
        $proxyId = getpar( $_GET, "id", 0 );
        $limit = getpagesize( "wf_flow_use_proxy_getProxyFlowList" );
        $dblist = $CNOA_DB->db_select( "*", $this->t_use_proxy_flow, "WHERE `uProxyId`='".$proxyId."' ORDER BY `id` DESC LIMIT {$start}, {$limit}" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $uids = array( );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['stime'] = date( "Y-m-d H:i", $v['stime'] );
            if ( !empty( $v['etime'] ) )
            {
                $dblist[$k]['etime'] = date( "Y-m-d H:i", $v['etime'] );
            }
            else
            {
                $dblist[$k]['etime'] = "— — — —";
            }
            $dblist[$k]['flowName'] = $CNOA_DB->db_getfield( "name", $this->t_set_flow, "WHERE `flowId`='".$v['flowId']."'" );
            $dblist[$k]['flowId'] = $v['flowId'];
            $uids[$v['fromuid']] = $v['fromuid'];
            $uids[$v['touid']] = $v['touid'];
        }
        $userNames = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uids );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['fromName'] = $userNames[$v['fromuid']]['truename'];
            $dblist[$k]['toName'] = $userNames[$v['touid']]['truename'];
            $num = $CNOA_DB->db_getcount( $this->t_use_proxy_uflow, "WHERE `flowId`='".$v['flowId']."' AND `fromuid`='{$v['fromuid']}' AND `touid`='{$v['touid']}' " );
            if ( 0 < $num )
            {
                $dblist[$k]['count'] = "<a style=\"color:red;text-decoration:underline\">".$num."</a>";
            }
            else
            {
                $dblist[$k]['count'] = 0;
            }
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        $dataStore->total = $CNOA_DB->db_getcount( $this->t_use_proxy_flow, "WHERE `uProxyId`='".$proxyId."'" );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getProxyUflowList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $start = getpar( $_POST, "start", 0 );
        $flowId = getpar( $_GET, "flowId", 0 );
        $fromuid = getpar( $_GET, "fromuid", 0 );
        $touid = getpar( $_GET, "touid", 0 );
        $limit = getpagesize( "wf_flow_use_proxy_getProxyUflowList" );
        $WHERE = "WHERE `flowId`='".$flowId."' AND `fromuid`='{$fromuid}' AND `touid`='{$touid}' AND `status`!=1 ";
        $dblist = $CNOA_DB->db_select( "*", $this->t_use_proxy_uflow, $WHERE.( "ORDER BY `id` DESC LIMIT ".$start.", {$limit}" ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $uids = array( );
        $uFlowIdArr = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $uids[$v['fromuid']] = $v['fromuid'];
            $uids[$v['touid']] = $v['touid'];
            $uFlowIdArr[] = $v['uFlowId'];
        }
        $flowData = $CNOA_DB->db_select( array( "flowName", "flowNumber", "uFlowId" ), $this->t_use_flow, "WHERE `uFlowId` IN (".implode( ",", $uFlowIdArr ).")" );
        if ( !is_array( $flowData ) )
        {
            $flowData = array( );
        }
        $flowCt = array( );
        foreach ( $flowData as $k => $v )
        {
            $flowCt[$v['uFlowId']] = $v;
        }
        $userNames = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uids );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['fromName'] = $userNames[$v['fromuid']]['truename'];
            $dblist[$k]['toName'] = $userNames[$v['touid']]['truename'];
            $dblist[$k]['flowNumber'] = $flowCt[$v['uFlowId']]['flowNumber'];
            $dblist[$k]['flowName'] = $flowCt[$v['uFlowId']]['flowName'];
            $dblist[$k]['posttime'] = formatdate( $v['posttime'] );
            $dblist[$k]['statusText'] = $v['status'] == 2 ? "已委托" : "进行中";
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        $dataStore->total = $CNOA_DB->db_getcount( $this->t_use_proxy_uflow, $WHERE );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _add( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $flowStr = getpar( $_POST, "flowId", 0 );
        $flowIds = json_decode( $flowStr, TRUE );
        $data = array( );
        $data['touid'] = getpar( $_POST, "touid", 0 );
        $data['fromuid'] = $uid;
        $data['flowId'] = $flowStr;
        $data['stime'] = strtotime( getpar( $_POST, "stime", "" ) );
        $data['etime'] = strtotime( getpar( $_POST, "etime", "" ) );
        $data['say'] = getpar( $_POST, "say", "" );
        if ( $data['touid'] == $uid )
        {
            msg::callback( FALSE, lang( "principalNotChooseOwn" ) );
        }
        else
        {
            $uProxyId = $CNOA_DB->db_insert( $data, $this->t_use_proxy );
            if ( !empty( $flowIds ) )
            {
                foreach ( $flowIds as $v )
                {
                    $data['flowId'] = $v;
                    $data['uProxyId'] = $uProxyId;
                    $CNOA_DB->db_insert( $data, $this->t_use_proxy_flow );
                }
            }
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3606, lang( "proxyRule" ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadFormData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = getpar( $_POST, "id", 0 );
        $uid = $CNOA_SESSION->get( "UID" );
        if ( !empty( $id ) )
        {
            $formData = $CNOA_DB->db_getone( "*", $this->t_use_proxy, "WHERE `id`='".$id."'" );
            $formData['stime'] = date( "Y-m-d H:i:s", $formData['stime'] );
            if ( empty( $formData['etime'] ) )
            {
                $formData['etime'] = "— — —";
            }
            else
            {
                $formData['etime'] = date( "Y-m-d H:i:s", $formData['etime'] );
            }
            $uids[$formData['fromuid']] = $formData['fromuid'];
            $uids[$formData['touid']] = $formData['touid'];
            $userNames = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uids );
            $formData['fromName'] = $userNames[$formData['fromuid']]['truename'];
            $formData['toName'] = $userNames[$formData['touid']]['truename'];
        }
        else
        {
            $formData = array( );
        }
        $data = $this->api_loadProxyFormData( $id, $uid );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $formData;
        $dataStore->flow = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _edit( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = getpar( $_GET, "id", 0 );
        $uid = $CNOA_SESSION->get( "UID" );
        $flowStr = getpar( $_POST, "flowId", 0 );
        $flowIds = json_decode( $flowStr, TRUE );
        $data = array( );
        $data['touid'] = getpar( $_POST, "touid", 0 );
        $data['fromuid'] = $uid;
        $data['flowId'] = $flowStr;
        $data['stime'] = strtotime( getpar( $_POST, "stime", 0 ) );
        $data['etime'] = strtotime( getpar( $_POST, "etime", 0 ) );
        $data['say'] = getpar( $_POST, "say", "" );
        $CNOA_DB->db_update( $data, $this->t_use_proxy, "WHERE `id`='".$id."'" );
        $CNOA_DB->db_delete( $this->t_use_proxy_flow, "WHERE `uProxyId`='".$id."'" );
        foreach ( $flowIds as $v )
        {
            $data['flowId'] = $v;
            $data['uProxyId'] = $id;
            $CNOA_DB->db_insert( $data, $this->t_use_proxy_flow );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 3606, lang( "proxyRule" ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _delete( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $ids = getpar( $_POST, "ids", 0 );
        $ids = substr( $ids, 0, -1 );
        $idArr = explode( ",", $ids );
        foreach ( $idArr as $v )
        {
            $CNOA_DB->db_delete( $this->t_use_proxy, "WHERE `id`='".$v."' AND `fromuid`='{$uid}'" );
            $CNOA_DB->db_delete( $this->t_use_proxy_flow, "WHERE `uProxyId`='".$v."' AND `fromuid`='{$uid}'" );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 3606, lang( "proxyRule" ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _takeBackAllProxyFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $id = getpar( $_POST, "id", 0 );
        $ck = getpar( $_POST, "ck", "" );
        $CNOA_DB->db_delete( $this->t_use_proxy, "WHERE `id`='".$id."'" );
        $flowInfo = $CNOA_DB->db_getone( "*", $this->t_use_proxy_flow, "WHERE `uProxyId`='".$id."'" );
        $CNOA_DB->db_delete( $this->t_use_proxy_flow, "WHERE `uProxyId`='".$flowInfo['uProxyId']."'" );
        $flowDB = $CNOA_DB->db_select( "*", $this->t_use_proxy_uflow, "WHERE `flowId`='".$flowInfo['flowId']."'" );
        if ( !is_array( $flowDB ) )
        {
            $flowDB = array( );
        }
        foreach ( $flowDB as $k => $v )
        {
            if ( $ck == "true" )
            {
                $CNOA_DB->db_update( array( "proxyUid" => 0 ), $this->t_use_flow, "WHERE `uFlowId`='".$v['uFlowId']."'" );
                $CNOA_DB->db_update( array( "status" => 1 ), $this->t_use_proxy_uflow, "WHERE `flowId`='".$flowInfo['flowId']."'" );
            }
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3606, lang( "backEntructFlow" ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _takeBackAllUflow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = getpar( $_POST, "id", 0 );
        $uid = $CNOA_SESSION->get( "UID" );
        $ck = getpar( $_POST, "ck", "" );
        $flowInfo = $CNOA_DB->db_getone( "*", $this->t_use_proxy_flow, "WHERE `id`='".$id."'" );
        if ( !$flowInfo )
        {
            msg::callback( FALSE, lang( "nodata" ) );
        }
        $CNOA_DB->db_delete( $this->t_use_proxy_flow, "WHERE `id`='".$flowInfo['id']."'" );
        $flowDB = $CNOA_DB->db_select( "*", $this->t_use_proxy_uflow, "WHERE `flowId`='".$flowInfo['flowId']."'" );
        if ( !is_array( $flowDB ) )
        {
            $flowDB = array( );
        }
        foreach ( $flowDB as $k => $v )
        {
            if ( $ck == "true" )
            {
                $CNOA_DB->db_update( array( "proxyUid" => 0 ), $this->t_use_flow, "WHERE `uFlowId`='".$v['uFlowId']."'" );
                $CNOA_DB->db_update( array( "status" => 1 ), $this->t_use_proxy_uflow, "WHERE `flowId`='".$flowInfo['flowId']."'" );
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _takeBackUflow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = getpar( $_POST, "id", 0 );
        $uid = $CNOA_SESSION->get( "UID" );
        $flowInfo = $CNOA_DB->db_getone( "*", $this->t_use_proxy_uflow, "WHERE `id`='".$id."'" );
        $CNOA_DB->db_update( array( "status" => 1 ), $this->t_use_proxy_uflow, "WHERE `id`='".$id."'" );
        $CNOA_DB->db_update( array( "proxyUid" => 0 ), $this->t_use_flow, "WHERE `uFlowId`='".$flowInfo['uFlowId']."'" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _setProxyStatus( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $id = getpar( $_POST, "id", 0 );
        $flowInfo = $CNOA_DB->db_getone( "*", $this->t_use_proxy, "WHERE `id`='".$id."'" );
        if ( !$flowInfo )
        {
            msg::callback( FALSE, lang( "nodata" ) );
        }
        if ( $flowInfo['status'] == 1 )
        {
            $CNOA_DB->db_update( array( "status" => 2 ), $this->t_use_proxy, "WHERE `id`='".$id."'" );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3606, lang( "disableProxyRule" ) );
        }
        else
        {
            $CNOA_DB->db_update( array( "status" => 1 ), $this->t_use_proxy, "WHERE `id`='".$id."'" );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3606, lang( "enableProxyRule" ) );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function api_getTrustJsonData( $type )
    {
        $dataList = $this->_getJsonData( $type );
        return $dataList;
    }

    public function api_getTrustFlowList( )
    {
        $flowList = $this->_getProxyFlowList( );
        return $flowList;
    }

    public function api_getTrustUflowList( )
    {
        $uFlowList = $this->_getProxyUflowList( );
        return $uFlowList;
    }

    public function api_takeBackAllTrustFlow( )
    {
        $list = $this->_takeBackAllProxyFlow( );
        return $list;
    }

    public function api_takeBackAllUflow( )
    {
        $list = $this->_takeBackAllUflow( );
        return $list;
    }

    public function api_takeBackUflow( )
    {
        $list = $this->_takeBackUflow( );
        return $list;
    }

}

?>
