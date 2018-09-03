<?php

class wfFlowUseDraft extends wfCommon
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
        case "deleteFlowList" :
            $this->_deleteFlowList( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        $from = getpar( $_GET, "from", "" );
        if ( $from == "list" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/draft.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $flowName = getpar( $_POST, "flowname", "" );
        $uid = $CNOA_SESSION->get( "UID" );
        $start = getpar( $_POST, "start", 0 );
        $limit = getpagesize( "wf_flow_use_draft_getJsonData" );
        $WHERE = "WHERE `uid`='".$uid."' AND `status`=0 ";
        if ( !empty( $flowName ) )
        {
            $WHERE .= "AND (`flowName` LIKE '%".$flowName."%' OR `flowNumber` LIKE '%{$flowName}%') ";
        }
        $dblist = $CNOA_DB->db_select( "*", $this->t_use_flow, $WHERE.( " ORDER BY `uFlowId` DESC LIMIT ".$start.",{$limit}" ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $sortArr = array( 0 );
        $flowIds = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $sortArr[] = $v['sortId'];
            $flowIds[$v['flowId']] = $v['flowId'];
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
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['sname'] = $sortDB[$v['sortId']]['name'];
            $dblist[$k]['edittime'] = date( "Y-m-d H:i", $v['edittime'] );
            $dblist[$k]['flowSetName'] = $flowSetNames[$v['flowId']];
            $dblist[$k]['tplSort'] = $flowSetTplsort[$v['flowId']];
            $dblist[$k]['flowType'] = $flowSetFlowType[$v['flowId']];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        $dataStore->total = $CNOA_DB->db_getcount( $this->t_use_flow, $WHERE );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _deleteFlowList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uFlowId = getpar( $_POST, "uFlowId", 0 );
        $uid = $CNOA_SESSION->get( "UID" );
        $uFlowIds = explode( ",", $uFlowId );
        if ( is_array( $uFlowIds ) )
        {
            foreach ( $uFlowIds as $uFlowId )
            {
                $flowInfo = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$uFlowId."' AND `status`=0 AND `uid`='{$uid}'" );
                if ( !$flowInfo )
                {
                }
                else
                {
                    $CNOA_DB->db_delete( $this->t_use_flow, "WHERE `uFlowId`='".$uFlowId."'" );
                    app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 3603, lang( "flowName" )."[".$flowInfo['flowName']."] 编号".$flowInfo['flowNumber'] );
                    $CNOA_DB->db_delete( "z_wf_t_".$flowInfo['flowId'], "WHERE `uFlowId`='".$uFlowId."'" );
                    $detailList = $CNOA_DB->db_select( array( "id" ), $this->t_set_field, "WHERE `flowId`='".$flowInfo['flowId']."' AND `otype`='detailtable'" );
                    if ( !is_array( $detailList ) )
                    {
                        $detailList = array( );
                    }
                    foreach ( $detailList as $v )
                    {
                        $CNOA_DB->db_delete( "z_wf_d_".$flowInfo['flowId']."_".$v['id'], "WHERE `uFlowId`='".$uFlowId."'" );
                    }
                }
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

}

?>
