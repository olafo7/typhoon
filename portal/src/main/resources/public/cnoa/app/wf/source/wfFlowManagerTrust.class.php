<?php

class wfFlowManagerTrust extends wfCommon
{

    public function run( )
    {
        $task = getpar( $_GET, "task", getpar( $_POST, "task", "" ) );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "getJsonData" :
            $dataList = app::loadapp( "wf", "flowUseProxy" )->api_getTrustJsonData( "mgrTrust" );
            echo json_encode( $dataList );
            exit( );
        case "add" :
            $this->_add( );
            break;
        case "loadAddFormData" :
            $this->_loadAddFormData( );
            break;
        case "edit" :
            $this->_edit( );
            break;
        case "loadEditFormData" :
            $this->_loadEditFormData( );
            break;
        case "delete" :
            $this->_delete( );
            break;
        case "setTrustStatus" :
            $this->_setTrustStatus( );
            break;
        case "getTrustFlowList" :
            $flowlist = app::loadapp( "wf", "flowUseProxy" )->api_getTrustFlowList( );
            echo json_encode( $flowlist );
            exit( );
        case "getTrustUflowList" :
            $uFlowlist = app::loadapp( "wf", "flowUseProxy" )->api_getTrustUflowList( );
            echo json_encode( $uFlowlist );
            exit( );
        case "takeBackAllTrustFlow" :
            app::loadapp( "wf", "flowUseProxy" )->api_takeBackAllTrustFlow( );
            exit( );
        case "takeBackAllUflow" :
            app::loadapp( "wf", "flowUseProxy" )->api_takeBackAllUflow( );
            break;
        case "takeBackUflow" :
            app::loadapp( "wf", "flowUseProxy" )->api_takeBackUflow( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $userList );
            exit( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $from = getpar( $_GET, "from", getpar( $_POST, "from", "list" ) );
        if ( $from == "list" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/manager/trust.htm";
        }
        else if ( $from == "trustView_flow" )
        {
            $GLOBALS['GLOBALS']['app']['trust']['id'] = getpar( $_GET, "id", 0 );
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/manager/trustView_flow.htm";
        }
        else if ( $from == "trustView_uflow" )
        {
            $GLOBALS['GLOBALS']['app']['trust']['flowId'] = getpar( $_GET, "flowId", 0 );
            $GLOBALS['GLOBALS']['app']['trust']['fromuid'] = getpar( $_GET, "fromuid", 0 );
            $GLOBALS['GLOBALS']['app']['trust']['touid'] = getpar( $_GET, "touid", 0 );
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/manager/trustView_uflow.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _add( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $flowStr = getpar( $_POST, "flowId", 0 );
        $flowIds = json_decode( $flowStr, TRUE );
        $data = array( );
        $data['fromuid'] = getpar( $_POST, "fromuid", 0 );
        $data['touid'] = getpar( $_POST, "touid", 0 );
        $data['flowId'] = $flowStr;
        $data['stime'] = strtotime( getpar( $_POST, "stime", "" ) );
        $data['etime'] = strtotime( getpar( $_POST, "etime", "" ) );
        if ( $data['touid'] == $data['fromuid'] )
        {
            msg::callback( FALSE, lang( "weiTuoShiOneself" ) );
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
        app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3632, lang( "proxyRule" ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _edit( )
    {
        global $CNOA_DB;
        $id = ( integer )getpar( $_GET, "id" );
        $flowStr = getpar( $_POST, "flowId", 0 );
        $flowIds = json_decode( $flowStr, TRUE );
        $rule = $CNOA_DB->db_getone( "*", $this->t_use_proxy, "WHERE id=".$id );
        $data = array( );
        $data['touid'] = $rule['touid'];
        $data['fromuid'] = $rule['fromuid'];
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
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 3632, lang( "proxyRule" ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadEditFormData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = getpar( $_POST, "id", 0 );
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
        $data = $this->api_loadProxyFormData( $id, $formData['fromuid'] );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $formData;
        $dataStore->flow = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _loadAddFormData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $fromuid = getpar( $_POST, "fromuid", 0 );
        $data = $this->api_loadProxyFormData( 0, $fromuid );
        ( );
        $dataStore = new dataStore( );
        $dataStore->flow = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _delete( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $ids = getpar( $_POST, "ids", 0 );
        $ids = substr( $ids, 0, -1 );
        $idArr = explode( ",", $ids );
        foreach ( $idArr as $v )
        {
            $CNOA_DB->db_delete( $this->t_use_proxy, "WHERE `id`='".$v."'" );
            $CNOA_DB->db_delete( $this->t_use_proxy_flow, "WHERE `uProxyId`='".$ids."'" );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 3632, lang( "proxyRule" ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _setTrustStatus( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = getpar( $_POST, "id", 0 );
        $flowInfo = $CNOA_DB->db_getone( "*", $this->t_use_proxy, "WHERE `id`='".$id."'" );
        if ( !$flowInfo )
        {
            msg::callback( FALSE, lang( "nodata" ) );
        }
        if ( $flowInfo['status'] == 1 )
        {
            $CNOA_DB->db_update( array( "status" => 2 ), $this->t_use_proxy, "WHERE `id`='".$id."'" );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3632, lang( "disableProxyRule" ) );
        }
        else
        {
            $CNOA_DB->db_update( array( "status" => 1 ), $this->t_use_proxy, "WHERE `id`='".$id."'" );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3632, lang( "enableProxyRule" ) );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

}

?>
