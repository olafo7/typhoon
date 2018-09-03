<?php

class wfFlowMgrList extends wfCommon
{

    public function run( )
    {
        $_obfuscate_M_5JJwÿÿ = getpar( $_GET, "task", getpar( $_POST, "task", "" ) );
        switch ( $_obfuscate_M_5JJwÿÿ )
        {
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "getSortTree" :
            app::loadapp( "wf", "flowSetSort" )->api_getSortTree( "chayue&guanli" );
            break;
        case "getJsonData" :
            $_obfuscate_vholQÿÿ = getpar( $_POST, "from", "normal" );
            if ( $_obfuscate_vholQÿÿ == "normal" )
            {
                $this->_getJsonData( );
            }
            else
            {
                if ( !( $_obfuscate_vholQÿÿ == "overtime" ) )
                {
                    break;
                }
                $this->_getOverTimeJsonData( );
            }
            break;
        case "getAllUserListsInPermitDeptTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $_obfuscate_phKp89pDgwQÿ = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $_obfuscate_phKp89pDgwQÿ );
            exit( );
        case "loadFormHtml" :
            $this->_loadFormHtml( );
            break;
        case "loadUflowInfo" :
            $_obfuscate_mPAjEGLn = app::loadapp( "wf", "flowUseTodo" )->api_loadUflowInfo( "mgr" );
            echo json_encode( $_obfuscate_mPAjEGLn );
            exit( );
        case "submitEntrustFormData" :
            $this->_submitEntrustFormData( );
            break;
        case "loadEntrustForm" :
            $this->_loadEntrustForm( );
            break;
        case "warnFlow" :
            $this->_warnFlow( );
            break;
        case "getFenfaList" :
            $this->_getFenfaList( );
            break;
        case "addFenfa" :
            $this->_addFenfa( );
            break;
        case "delFenfa" :
            $this->_delFenfa( );
            break;
        case "loadModifyForm" :
            $this->_loadModifyForm( );
            break;
        case "modify" :
            $this->_modify( );
            break;
        case "stopFlow" :
            $this->_stopFlow( );
            break;
        case "delete" :
            $this->_delete( );
            break;
        case "exportFlow" :
            $this->exportFlow( );
            break;
        case "ms_loadTemplateFile" :
            $this->_ms_loadTemplateFile( );
            break;
        case "ms_submitTemplateFile" :
            $this->_ms_submitTemplateFile( );
            break;
        case "getManageStep" :
            $this->_getManageStep( );
            break;
        case "getFlowListInSort" :
            $this->getFlowListInSort( );
            break;
        case "again" :
            $this->_getFlowInfo( );
            break;
        case "getFlowFieldsList" :
            $_obfuscate_Tc82k3jOQÿÿ = $this->getFlowFields( );
            ( );
            $_obfuscate_NlQÿ = new dataStore( );
            $_obfuscate_NlQÿ->data = $_obfuscate_Tc82k3jOQÿÿ;
            echo $_obfuscate_NlQÿ->makeJsonData( );
            exit( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $_obfuscate_vholQÿÿ = getpar( $_GET, "from", getpar( $_POST, "from", "list" ) );
        switch ( $_obfuscate_vholQÿÿ )
        {
        case "list" :
            $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
            $_obfuscate_Tjp5MUfA0Faowÿÿ = $CNOA_DB->db_getone( "*", $this->t_use_step_temptime, "WHERE `uid` = ".$_obfuscate_7Ri3." " );
            if ( empty( $_obfuscate_Tjp5MUfA0Faowÿÿ['stime'] ) )
            {
                $_obfuscate_Tjp5MUfA0Faowÿÿ['stime'] = "";
            }
            if ( empty( $_obfuscate_Tjp5MUfA0Faowÿÿ['etime'] ) )
            {
                $_obfuscate_Tjp5MUfA0Faowÿÿ['etime'] = date( "Y-m-t", $GLOBALS['CNOA_TIMESTAMP'] );
            }
            $GLOBALS['GLOBALS']['wf']['mgrList']['stime'] = $_obfuscate_Tjp5MUfA0Faowÿÿ['stime'];
            $GLOBALS['GLOBALS']['wf']['mgrList']['etime'] = $_obfuscate_Tjp5MUfA0Faowÿÿ['etime'];
            $GLOBALS['GLOBALS']['wf']['mgrList']['hour'] = $_obfuscate_Tjp5MUfA0Faowÿÿ['hour'];
            $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/mgr/list.htm";
            break;
        case "viewMgrflow" :
            $GLOBALS['GLOBALS']['app']['uFlowId'] = getpar( $_GET, "uFlowId", 0 );
            $GLOBALS['GLOBALS']['app']['step'] = getpar( $_GET, "step", 0 );
            $GLOBALS['GLOBALS']['app']['flowId'] = getpar( $_GET, "flowId", 0 );
            $GLOBALS['GLOBALS']['app']['status'] = getpar( $_GET, "status", 0 );
            $GLOBALS['GLOBALS']['app']['stepUid'] = getpar( $_GET, "stepUid", 0 );
            $GLOBALS['GLOBALS']['app']['wf']['flowType'] = getpar( $_GET, "flowType", 0 );
            $GLOBALS['GLOBALS']['app']['wf']['tplSort'] = getpar( $_GET, "tplSort", 0 );
            app::loadapp( "wf", "flowUseTodo" )->api_getRelFlow( );
            $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/mgr/listView.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQÿÿ );
        exit( );
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_neM4JBUJlmgÿ = getpar( $_POST, "flowName", "" );
        $_obfuscate_6b8lIO4y = getpar( $_POST, "status", "doing" );
        $_obfuscate_mV9HBLYÿ = ( integer )getpar( $_POST, "start" );
        $_obfuscate_v1GprsIz = ( integer )getpar( $_POST, "sortId" );
        $_obfuscate_F4AbnVRh = ( integer )getpar( $_POST, "flowId" );
        $_obfuscate_xvYeh9Iÿ = getpagesize( "wf_flow_mgr_list_getJsonData" );
        $_obfuscate_ggyI68Xtqd62AQÿÿ = $this->checkSortPermit( );
        if ( !$_obfuscate_ggyI68Xtqd62AQÿÿ )
        {
            ( );
            $_obfuscate_NlQÿ = new dataStore( );
            echo $_obfuscate_NlQÿ->makeJsonData( );
            exit( );
        }
        $_obfuscate_MujU5qIjWAÿÿ = array_keys( $_obfuscate_ggyI68Xtqd62AQÿÿ );
        $_obfuscate_j9sJesÿ = $_obfuscate_IRFhnYwÿ = array( );
        if ( $_obfuscate_6b8lIO4y == "doing" )
        {
            $_obfuscate_IRFhnYwÿ[] = "u.status=1";
            $_obfuscate_LeZTBxNr4gÿÿ = "ORDER BY u.updatetime DESC";
            $_obfuscate_MBTw2buyv8Lb = "AND `status`=1 ORDER BY `id` DESC";
        }
        else if ( $_obfuscate_6b8lIO4y == "done" )
        {
            $_obfuscate_IRFhnYwÿ[] = "u.status=2";
            $_obfuscate_LeZTBxNr4gÿÿ = "ORDER BY u.endtime DESC";
        }
        else
        {
            $_obfuscate_IRFhnYwÿ[] = "u.status!=0";
            $_obfuscate_LeZTBxNr4gÿÿ = "ORDER BY u.uFlowId DESC";
        }
        if ( $_obfuscate_F4AbnVRh !== 0 )
        {
            $_obfuscate_tjILu7ZH = array( );
            foreach ( $GLOBALS['_POST'] as $_obfuscate_Vwty => $_obfuscate_TAxu )
            {
                if ( preg_match( "/^T_\\d+\$/", $_obfuscate_Vwty ) )
                {
                    $_obfuscate_tjILu7ZH[$_obfuscate_Vwty] = getpar( $_POST, $_obfuscate_Vwty );
                }
            }
            $_obfuscate_dcwitxb = $this->getUFlowIdsBySearch( $_obfuscate_F4AbnVRh, $_obfuscate_tjILu7ZH );
            if ( !empty( $_obfuscate_dcwitxb ) )
            {
                $_obfuscate_IRFhnYwÿ[] = $_obfuscate_dcwitxb;
                $_obfuscate_IRFhnYwÿ = "WHERE ".implode( " AND ", $_obfuscate_IRFhnYwÿ );
            }
            else
            {
                $_obfuscate_IRFhnYwÿ = "WHERE ".implode( "", $_obfuscate_IRFhnYwÿ );
            }
            $_obfuscate_3y0Y = "SELECT u.*, s.name AS flowSetName, s.flowType, s.tplSort, c.name AS sname, s.status AS againStatus FROM ".tname( self::TABLE_PRE_DATA.$_obfuscate_F4AbnVRh )." AS d LEFT JOIN ".tname( $this->t_use_flow )." AS u ON u.uFlowId=d.uFlowId LEFT JOIN ".tname( $this->t_set_flow )." AS s ON s.flowId=u.flowId LEFT JOIN ".tname( $this->t_set_sort )." AS c ON c.sortId=u.sortId "."{$_obfuscate_IRFhnYwÿ} {$_obfuscate_LeZTBxNr4gÿÿ} LIMIT {$_obfuscate_mV9HBLYÿ}, {$_obfuscate_xvYeh9Iÿ}";
            $_obfuscate_k848c9fV6gIÿ = "SELECT count(*) AS count FROM ".tname( self::TABLE_PRE_DATA.$_obfuscate_F4AbnVRh )." AS d LEFT JOIN ".tname( $this->t_use_flow )." AS u ON u.uFlowId=d.uFlowId "."{$_obfuscate_IRFhnYwÿ}";
            $_obfuscate_j9sJesÿ = $CNOA_DB->get_one( $_obfuscate_k848c9fV6gIÿ );
            $_obfuscate_j9sJesÿ = ( integer )$_obfuscate_j9sJesÿ['count'];
        }
        else
        {
            if ( !empty( $_obfuscate_v1GprsIz ) )
            {
                if ( !in_array( $_obfuscate_v1GprsIz, $_obfuscate_MujU5qIjWAÿÿ ) )
                {
                    ( );
                    $_obfuscate_NlQÿ = new dataStore( );
                    echo $_obfuscate_NlQÿ->makeJsonData( );
                    exit( );
                }
                $_obfuscate_IRFhnYwÿ[] = "u.sortId=".$_obfuscate_v1GprsIz;
            }
            else
            {
                $_obfuscate_IRFhnYwÿ[] = "u.sortId IN(".implode( ",", $_obfuscate_MujU5qIjWAÿÿ ).")";
            }
            if ( !empty( $_obfuscate_neM4JBUJlmgÿ ) )
            {
                $_obfuscate_IRFhnYwÿ[] = "(u.flowName LIKE '%".$_obfuscate_neM4JBUJlmgÿ."%' OR u.flowNumber LIKE '%{$_obfuscate_neM4JBUJlmgÿ}%')";
            }
            $_obfuscate_IRFhnYwÿ = "WHERE ".implode( " AND ", $_obfuscate_IRFhnYwÿ );
            $_obfuscate_3y0Y = "SELECT u.*, s.name AS flowSetName, s.flowType, s.tplSort, c.name AS sname, s.status AS againStatus FROM ".tname( $this->t_use_flow )." AS u LEFT JOIN ".tname( $this->t_set_flow )." AS s ON s.flowId=u.flowId LEFT JOIN ".tname( $this->t_set_sort )." AS c ON c.sortId=u.sortId "."{$_obfuscate_IRFhnYwÿ} {$_obfuscate_LeZTBxNr4gÿÿ} LIMIT {$_obfuscate_mV9HBLYÿ}, {$_obfuscate_xvYeh9Iÿ}";
            $_obfuscate_j9sJesÿ = $CNOA_DB->db_getCount( $this->t_use_flow, str_replace( "u.", "", $_obfuscate_IRFhnYwÿ ) );
        }
        $_obfuscate__eqrEQÿÿ = $_obfuscate_dDHiUSY4Qoÿ = $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_dDHiUSY4Qoÿ[] = $_obfuscate_gkt['uFlowId'];
            $_obfuscate__eqrEQÿÿ[] = $_obfuscate_gkt['uid'];
            $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_gkt;
        }
        if ( count( $_obfuscate_6RYLWQÿÿ ) == 0 )
        {
            ( );
            $_obfuscate_NlQÿ = new dataStore( );
            echo $_obfuscate_NlQÿ->makeJsonData( );
            exit( );
        }
        $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "id", "uFlowId", "dealUid", "proxyUid", "uid", "uStepId" ), $this->t_use_step, "WHERE `uFlowId` IN (".implode( ",", $_obfuscate_dDHiUSY4Qoÿ ).") ".$_obfuscate_MBTw2buyv8Lb );
        if ( !is_array( $_obfuscate_Tx7M9W ) )
        {
            $_obfuscate_Tx7M9W = array( );
        }
        $_obfuscate_Q_ThbHHpIfvI = array( );
        foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_Q_ThbHHpIfvI[$_obfuscate_6Aÿÿ['uFlowId']][] = $_obfuscate_6Aÿÿ;
        }
        foreach ( $_obfuscate_Q_ThbHHpIfvI as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            usort( &$_obfuscate_Q_ThbHHpIfvI[$_obfuscate_5wÿÿ], array(
                $this,
                "_stepSort"
            ) );
        }
        $_obfuscate_Tx7M9W = array( );
        foreach ( $_obfuscate_Q_ThbHHpIfvI as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_3QYÿ => $_obfuscate_EGUÿ )
            {
                if ( $_obfuscate_3QYÿ == 0 )
                {
                    $_obfuscate_Tx7M9W[] = $_obfuscate_EGUÿ;
                }
            }
        }
        foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( !empty( $_obfuscate_6Aÿÿ['proxyUid'] ) )
            {
                $_obfuscate__eqrEQÿÿ[] = $_obfuscate_6Aÿÿ['proxyUid'];
                $_obfuscate_bBvQ3BLPRzlI[$_obfuscate_6Aÿÿ['uFlowId']]['stepUid'] = $_obfuscate_6Aÿÿ['proxyUid'];
            }
            if ( !empty( $_obfuscate_6Aÿÿ['uid'] ) )
            {
                $_obfuscate__eqrEQÿÿ[] = $_obfuscate_6Aÿÿ['uid'];
                $_obfuscate_bBvQ3BLPRzlI[$_obfuscate_6Aÿÿ['uFlowId']]['step'] = $_obfuscate_6Aÿÿ['uStepId'];
            }
            $_obfuscate_bBvQ3BLPRzlI[$_obfuscate_6Aÿÿ['uFlowId']]['step'] = $_obfuscate_6Aÿÿ['uStepId'];
        }
        $_obfuscate_YbfVGewvqPYÿ = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate__eqrEQÿÿ );
        $_obfuscate_tY4_zeP385SOvgÿÿ = $_obfuscate_PEqrtnNpeS5H = array( );
        foreach ( $_obfuscate_Tx7M9W as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_QwT4KwrB2wÿÿ[$_obfuscate_6Aÿÿ['uFlowId']] = $_obfuscate_6Aÿÿ;
            if ( $_obfuscate_6Aÿÿ['proxyUid'] != 0 )
            {
                $_obfuscate_PEqrtnNpeS5H[$_obfuscate_6Aÿÿ['uFlowId']] = $_obfuscate_6Aÿÿ['dealUid'];
                $_obfuscate_tY4_zeP385SOvgÿÿ[$_obfuscate_6Aÿÿ['uFlowId']] = $_obfuscate_6Aÿÿ['proxyUid'];
                $_obfuscate_QwT4KwrB2wÿÿ[$_obfuscate_6Aÿÿ['uFlowId']]['stepUid'] = $_obfuscate_6Aÿÿ['dealUid'];
            }
            else
            {
                $_obfuscate_QwT4KwrB2wÿÿ[$_obfuscate_6Aÿÿ['uFlowId']]['stepUid'] = $_obfuscate_6Aÿÿ['uid'];
            }
        }
        foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['level'] = $this->f_level[$_obfuscate_6Aÿÿ['level']];
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['statusText'] = $this->f_status[$_obfuscate_6Aÿÿ['status']];
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['uname'] = $_obfuscate_YbfVGewvqPYÿ[$_obfuscate_6Aÿÿ['uid']];
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['posttime'] = date( "Y-m-d H:i", $_obfuscate_6Aÿÿ['posttime'] );
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['permit'] = $_obfuscate_ggyI68Xtqd62AQÿÿ[$_obfuscate_6Aÿÿ['sortId']];
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['step'] = $_obfuscate_bBvQ3BLPRzlI[$_obfuscate_6Aÿÿ['uFlowId']]['step'];
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['stepUid'] = $_obfuscate_QwT4KwrB2wÿÿ[$_obfuscate_6Aÿÿ['uFlowId']]['stepUid'];
            if ( $_obfuscate_QwT4KwrB2wÿÿ[$_obfuscate_6Aÿÿ['uFlowId']]['stepUid'] == 0 )
            {
                $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['stepUname'] = "------";
            }
            else
            {
                $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['stepUname'] = $_obfuscate_YbfVGewvqPYÿ[$_obfuscate_QwT4KwrB2wÿÿ[$_obfuscate_6Aÿÿ['uFlowId']]['stepUid']];
            }
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['stepTrustUname'] = $_obfuscate_YbfVGewvqPYÿ[$_obfuscate_QwT4KwrB2wÿÿ[$_obfuscate_6Aÿÿ['uFlowId']]['proxyUid']];
            if ( empty( $_obfuscate_PEqrtnNpeS5H[$_obfuscate_6Aÿÿ['uFlowId']] ) || !( $_obfuscate_PEqrtnNpeS5H[$_obfuscate_6Aÿÿ['uFlowId']] == $_obfuscate_tY4_zeP385SOvgÿÿ[$_obfuscate_6Aÿÿ['uFlowId']] ) )
            {
                $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['proxyStatus'] = 1;
            }
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        $_obfuscate_NlQÿ->total = $_obfuscate_j9sJesÿ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _stepSort( $m, $_obfuscate_8Aÿÿ )
    {
        if ( $m['id'] < $_obfuscate_8Aÿÿ['id'] )
        {
            return 1;
        }
    }

    private function checkSortPermit( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_iuzS = $CNOA_SESSION->get( "DID" );
        $_obfuscate_y6jH = $CNOA_SESSION->get( "SID" );
        $_obfuscate_IRFhnYwÿ = "WHERE (`from`='g' OR `from`='c') AND ".( "( (`type` = 'p' AND `permitId` = '".$_obfuscate_7Ri3."') " ).( "OR (`type` = 's' AND `permitId` = '".$_obfuscate_y6jH."') " ).( "OR (`type` = 'd' AND `permitId` = '".$_obfuscate_iuzS."') ) " );
        $_obfuscate_99gmCq3UfCfZTQÿÿ = $CNOA_DB->db_select( array( "sortId", "from" ), $this->t_set_sort_permit, $_obfuscate_IRFhnYwÿ );
        if ( !is_array( $_obfuscate_99gmCq3UfCfZTQÿÿ ) )
        {
            $_obfuscate_99gmCq3UfCfZTQÿÿ = array( );
        }
        $_obfuscate_ggyI68Xtqd62AQÿÿ = array( );
        foreach ( $_obfuscate_99gmCq3UfCfZTQÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['from'] == "g" )
            {
                $_obfuscate_ggyI68Xtqd62AQÿÿ[$_obfuscate_6Aÿÿ['sortId']] = "g";
            }
            else if ( !( $_obfuscate_6Aÿÿ['from'] == "c" ) && isset( $_obfuscate_ggyI68Xtqd62AQÿÿ[$_obfuscate_6Aÿÿ['sortId']] ) )
            {
                $_obfuscate_ggyI68Xtqd62AQÿÿ[$_obfuscate_6Aÿÿ['sortId']] = "c";
            }
        }
        if ( 0 < count( $_obfuscate_ggyI68Xtqd62AQÿÿ ) )
        {
            return $_obfuscate_ggyI68Xtqd62AQÿÿ;
        }
        return FALSE;
    }

    private function _getOverTimeJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_3y0Y = "UPDATE ".tname( $this->t_use_step ).( " SET `timegap`=".$GLOBALS['CNOA_TIMESTAMP']."-`stime` WHERE `etime`=0" );
        $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_qx37NMÿ = getpar( $_POST, "stime", 0 );
        $_obfuscate_KWKBW4ÿ = getpar( $_POST, "etime", 0 );
        $_obfuscate_4QMgvgÿÿ = getpar( $_POST, "hour", 0 );
        $_obfuscate_v1GprsIz = getpar( $_POST, "sortId", 0 );
        $_obfuscate_neM4JBUJlmgÿ = getpar( $_POST, "flowName", "" );
        $CNOA_DB->db_replace( array(
            "uid" => $_obfuscate_7Ri3,
            "stime" => $_obfuscate_qx37NMÿ,
            "etime" => $_obfuscate_KWKBW4ÿ,
            "hour" => $_obfuscate_4QMgvgÿÿ,
            "uid" => $_obfuscate_7Ri3
        ), $this->t_use_step_temptime );
        $_obfuscate_xvYeh9Iÿ = getpagesize( "wf_flow_mgr_list_getOverTimeJsonData" );
        $_obfuscate_Bk2lGlkÿ = "WHERE 1 ";
        $_obfuscate_mV9HBLYÿ = getpar( $_POST, "start", 0 );
        $_obfuscate_eVTMIa1A = app::loadapp( "wf", "flowSetSort" )->api_getSortDB( "guanli" );
        if ( !is_array( $_obfuscate_eVTMIa1A ) )
        {
            $_obfuscate_eVTMIa1A = array( );
        }
        $_obfuscate_5_FLZDYg5BK5 = array( 0 );
        foreach ( $_obfuscate_eVTMIa1A as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_5_FLZDYg5BK5[] = $_obfuscate_6Aÿÿ['sortId'];
        }
        $_obfuscate_Bk2lGlkÿ .= "AND `sortId` IN (".implode( ",", $_obfuscate_5_FLZDYg5BK5 ).")";
        if ( !empty( $_obfuscate_v1GprsIz ) )
        {
            $_obfuscate_Bk2lGlkÿ .= "AND `sortId` = ".$_obfuscate_v1GprsIz." ";
        }
        if ( !empty( $_obfuscate_qx37NMÿ ) )
        {
            $_obfuscate_qx37NMÿ = strtotime( "{$_obfuscate_qx37NMÿ} 00:00:00" );
            $_obfuscate_Bk2lGlkÿ .= "AND `stime` > ".$_obfuscate_qx37NMÿ." ";
        }
        if ( !empty( $_obfuscate_KWKBW4ÿ ) )
        {
            $_obfuscate_KWKBW4ÿ = strtotime( "{$_obfuscate_KWKBW4ÿ} 23:59:59" );
            $_obfuscate_Bk2lGlkÿ .= "AND `stime` < ".$_obfuscate_KWKBW4ÿ." ";
        }
        if ( !empty( $_obfuscate_4QMgvgÿÿ ) )
        {
            $_obfuscate_4QMgvgÿÿ *= 3600;
            $_obfuscate_Bk2lGlkÿ .= "AND `timegap` > ".$_obfuscate_4QMgvgÿÿ." ";
        }
        $_obfuscate_6b8lIO4y = getpar( $_POST, "status", "doing" );
        if ( $_obfuscate_6b8lIO4y == "doing" )
        {
            $_obfuscate_Bk2lGlkÿ .= "AND `status` = 1 ";
        }
        $_obfuscate_dekTUGw = "";
        if ( !empty( $_obfuscate_neM4JBUJlmgÿ ) )
        {
            $_obfuscate_dekTUGw .= "WHERE 1 AND (`flowName` LIKE '%".$_obfuscate_neM4JBUJlmgÿ."%' OR `flowNumber` LIKE '%{$_obfuscate_neM4JBUJlmgÿ}%') ";
            $_obfuscate_G1iYRp3F = $CNOA_DB->db_select( array( "flowNumber", "flowName", "uFlowId", "flowId" ), $this->t_use_flow, $_obfuscate_dekTUGw );
            if ( !is_array( $_obfuscate_G1iYRp3F ) )
            {
                $_obfuscate_G1iYRp3F = array( );
            }
            $_obfuscate_dDHiUSY4Qoÿ = array( );
            foreach ( $_obfuscate_G1iYRp3F as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_dDHiUSY4Qoÿ[] = $_obfuscate_6Aÿÿ['uFlowId'];
            }
            if ( 0 < count( $_obfuscate_dDHiUSY4Qoÿ ) )
            {
                $_obfuscate_Bk2lGlkÿ .= "AND `uFlowId` IN (".implode( ",", $_obfuscate_dDHiUSY4Qoÿ ).") ";
            }
        }
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "id", "uFlowId", "uStepId", "stime", "etime", "dealUid", "uid", "proxyUid", "stepname", "status", "uFlowId", "timegap", "sortId", "status" ), $this->t_use_step, $_obfuscate_Bk2lGlkÿ.( "AND `stepType` != 1 AND `stepType` != 3 ORDER BY `timegap` DESC LIMIT ".$_obfuscate_mV9HBLYÿ.", {$_obfuscate_xvYeh9Iÿ} " ) );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_PVLK5jra = array( 0 );
        $_obfuscate_JC6sZe6bzW0Hygÿÿ = array( 0 );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( !empty( $_obfuscate_6Aÿÿ['dealUid'] ) )
            {
                $_obfuscate_PVLK5jra[] = $_obfuscate_6Aÿÿ['dealUid'];
            }
            else
            {
                $_obfuscate_PVLK5jra[] = $_obfuscate_6Aÿÿ['uid'];
                $_obfuscate_PVLK5jra[] = $_obfuscate_6Aÿÿ['proxyUid'];
            }
            $_obfuscate_JC6sZe6bzW0Hygÿÿ[] = $_obfuscate_6Aÿÿ['uFlowId'];
            $_obfuscate_uly_hPh_dQÿÿ[] = $_obfuscate_6Aÿÿ['sortId'];
        }
        $_obfuscate_GCfDSanL49WUEAÿÿ = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_PVLK5jra );
        $_obfuscate_oCNnaL2WsRZ = $CNOA_DB->db_select( array( "flowNumber", "flowName", "uFlowId", "level", "flowId" ), $this->t_use_flow, "WHERE `uFlowId` IN (".implode( ",", $_obfuscate_JC6sZe6bzW0Hygÿÿ ).")" );
        if ( !is_array( $_obfuscate_oCNnaL2WsRZ ) )
        {
            $_obfuscate_oCNnaL2WsRZ = array( );
        }
        foreach ( $_obfuscate_oCNnaL2WsRZ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_LEdLG6CYLyp6orIÿ[$_obfuscate_6Aÿÿ['uFlowId']] = $_obfuscate_6Aÿÿ;
            $_obfuscate_l2CIvUX0Kvp4[$_obfuscate_6Aÿÿ['flowId']] = $_obfuscate_6Aÿÿ['flowId'];
        }
        $_obfuscate_RopcQP_w = app::loadapp( "wf", "flowSetSort" )->api_getSortData( array(
            "sortIdArr" => $_obfuscate_uly_hPh_dQÿÿ
        ) );
        $_obfuscate_SIUSR4F6 = app::loadapp( "wf", "flowSetFlow" )->api_getFlowData( array(
            "flowIdArr" => $_obfuscate_l2CIvUX0Kvp4,
            "field" => array( "name", "flowType", "tplSort" )
        ) );
        $_obfuscate_6RYLWQÿÿ = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( !empty( $_obfuscate_6Aÿÿ['dealUid'] ) )
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['stepUname'] = $_obfuscate_GCfDSanL49WUEAÿÿ[$_obfuscate_6Aÿÿ['dealUid']]['truename'];
            }
            else if ( empty( $_obfuscate_6Aÿÿ['dealUid'] ) && empty( $_obfuscate_6Aÿÿ['uid'] ) )
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['stepUname'] = $_obfuscate_GCfDSanL49WUEAÿÿ[$_obfuscate_6Aÿÿ['proxyUid']]['truename'];
            }
            else
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['stepUname'] = $_obfuscate_GCfDSanL49WUEAÿÿ[$_obfuscate_6Aÿÿ['uid']]['truename'];
            }
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['flowNumber'] = empty( $_obfuscate_LEdLG6CYLyp6orIÿ[$_obfuscate_6Aÿÿ['uFlowId']]['flowNumber'] ) ? "----" : $_obfuscate_LEdLG6CYLyp6orIÿ[$_obfuscate_6Aÿÿ['uFlowId']]['flowNumber'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['flowName'] = empty( $_obfuscate_LEdLG6CYLyp6orIÿ[$_obfuscate_6Aÿÿ['uFlowId']]['flowName'] ) ? "----" : $_obfuscate_LEdLG6CYLyp6orIÿ[$_obfuscate_6Aÿÿ['uFlowId']]['flowName'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['level'] = $this->f_level[$_obfuscate_LEdLG6CYLyp6orIÿ[$_obfuscate_6Aÿÿ['uFlowId']]['level']];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['sname'] = empty( $_obfuscate_RopcQP_w[$_obfuscate_6Aÿÿ['sortId']]['name'] ) ? "----" : $_obfuscate_RopcQP_w[$_obfuscate_6Aÿÿ['sortId']]['name'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['flowSetName'] = empty( $_obfuscate_SIUSR4F6[$_obfuscate_LEdLG6CYLyp6orIÿ[$_obfuscate_6Aÿÿ['uFlowId']]['flowId']]['name'] ) ? "----" : $_obfuscate_SIUSR4F6[$_obfuscate_LEdLG6CYLyp6orIÿ[$_obfuscate_6Aÿÿ['uFlowId']]['flowId']]['name'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['usetime'] = timeformat2( $_obfuscate_6Aÿÿ['timegap'] );
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['stime'] = formatdate( $_obfuscate_6Aÿÿ['stime'] );
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['etime'] = formatdate( $_obfuscate_6Aÿÿ['etime'] );
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['flowId'] = $_obfuscate_LEdLG6CYLyp6orIÿ[$_obfuscate_6Aÿÿ['uFlowId']]['flowId'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['step'] = $_obfuscate_LEdLG6CYLyp6orIÿ[$_obfuscate_6Aÿÿ['uFlowId']]['step'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['stepUid'] = $_obfuscate_LEdLG6CYLyp6orIÿ[$_obfuscate_6Aÿÿ['uFlowId']]['stepUid'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['flowType'] = $_obfuscate_SIUSR4F6[$_obfuscate_LEdLG6CYLyp6orIÿ[$_obfuscate_6Aÿÿ['uFlowId']]['flowId']]['flowType'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['tplSort'] = $_obfuscate_SIUSR4F6[$_obfuscate_LEdLG6CYLyp6orIÿ[$_obfuscate_6Aÿÿ['uFlowId']]['flowId']]['tplSort'];
            ( $_obfuscate_6Aÿÿ['uFlowId'] );
            $_obfuscate_bIsJe6Aÿ = new wfCache( );
            $_obfuscate_5NhzjnJq_f8ÿ = $_obfuscate_bIsJe6Aÿ->getStepByStepId( $_obfuscate_6Aÿÿ['uStepId'] );
            $_obfuscate_TWcMviltrckÿ = $_obfuscate_5NhzjnJq_f8ÿ['stepTime'];
            unset( $_obfuscate_bIsJe6Aÿ );
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['overtime'] = $_obfuscate_TWcMviltrckÿ == 0 ? FALSE : $_obfuscate_TWcMviltrckÿ * 3600 < $_obfuscate_6Aÿÿ['timegap'];
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_mPAjEGLn;
        $_obfuscate_NlQÿ->total = $CNOA_DB->db_getcount( $this->t_use_step, $_obfuscate_Bk2lGlkÿ."AND `stepType` != 1 AND `stepType` != 3" );
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _loadFormHtml( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = intval( getpar( $_GET, "uFlowId", 0 ) );
        ( $_obfuscate_TlvKhtsoOQÿÿ );
        $GLOBALS['GLOBALS']['UWFCACHE'] = new wfCache( );
        $_obfuscate_SIUSR4F6 = $GLOBALS['UWFCACHE']->getFlow( );
        $_obfuscate_WYOD1IJTSwÿÿ = $CNOA_DB->db_getfield( "uStepId", $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' ORDER BY `etime` DESC" );
        ( $GLOBALS['UWFCACHE']->getFlowId( ), $_obfuscate_WYOD1IJTSwÿÿ, FALSE, "done", $_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_P5_qcMiY39uereSduytrzD8ÿ = new wfFieldFormaterForView( );
        $_obfuscate_5MjAF_AntLkÿ = $_obfuscate_P5_qcMiY39uereSduytrzD8ÿ->crteateFormHtml( );
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->total = 0;
        $_obfuscate_SUjPN94Er7yI->data = array(
            "formHtml" => $_obfuscate_5MjAF_AntLkÿ
        );
        $_obfuscate_SUjPN94Er7yI->pageset = $_obfuscate_SIUSR4F6['pageset'];
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _submitEntrustFormData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_6RYLWQÿÿ['uFlowId'] = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_6RYLWQÿÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_6RYLWQÿÿ['flowId'] = 0;
        $_obfuscate_6RYLWQÿÿ['fromuid'] = $_obfuscate_7Ri3;
        $_obfuscate_6RYLWQÿÿ['touid'] = getpar( $_POST, "touid", 0 );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_0W8ÿ = getpar( $_POST, "stepId", 0 );
        $_obfuscate_gkt = $this->getStepId( $_obfuscate_0W8ÿ );
        $_obfuscate_ecqaH_ev7Aÿÿ = $_obfuscate_gkt['uStepId'];
        $_obfuscate_VZzSMXQx6Qÿÿ = getpar( $_POST, "stepUid", 0 );
        $_obfuscate_Bk2lGlkÿ = "WHERE `fromuid`='".$_obfuscate_6RYLWQÿÿ['fromuid']."' AND `uFlowId`='{$_obfuscate_6RYLWQÿÿ['uFlowId']}' AND `flowId`=0";
        $_obfuscate_SIUSR4F6 = $CNOA_DB->db_getone( "*", $this->t_use_proxy_uflow, $_obfuscate_Bk2lGlkÿ );
        if ( !empty( $_obfuscate_SIUSR4F6 ) )
        {
            $CNOA_DB->db_delete( $this->t_use_proxy_uflow, $_obfuscate_Bk2lGlkÿ );
        }
        if ( $_obfuscate_6RYLWQÿÿ['touid'] == $_obfuscate_VZzSMXQx6Qÿÿ )
        {
            msg::callback( FALSE, lang( "principalNotChooseOwn" ) );
        }
        else
        {
            $_obfuscate_0Ul8BBkt = $_obfuscate_0W8ÿ;
            $this->deleteNotice( "both", $_obfuscate_0Ul8BBkt, "trust" );
            $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber", "flowId" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_6RYLWQÿÿ['uFlowId']."'  " );
            $_obfuscate_0AITFwÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "weiTuoToYou" );
            $_obfuscate_0AITFwÿÿ['href'] = "&uFlowId=".$_obfuscate_6RYLWQÿÿ['uFlowId']."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_ecqaH_ev7Aÿÿ}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
            $_obfuscate_0AITFwÿÿ['fromid'] = $_obfuscate_0Ul8BBkt;
            $this->addNotice( "both", $_obfuscate_6RYLWQÿÿ['touid'], $_obfuscate_0AITFwÿÿ, "trust" );
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_use_proxy_uflow );
            $CNOA_DB->db_update( array(
                "proxyUid" => $_obfuscate_6RYLWQÿÿ['touid']
            ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_6RYLWQÿÿ['uFlowId']."' AND `id`='{$_obfuscate_0W8ÿ}'" );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3631, lang( "flowWTflowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadEntrustForm( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_0W8ÿ = getpar( $_POST, "stepId", 0 );
        $_obfuscate_gkt = $this->getStepId( $_obfuscate_0W8ÿ );
        $_obfuscate_ecqaH_ev7Aÿÿ = $_obfuscate_gkt['uStepId'];
        $_obfuscate_SXBi6VrH2yEÿ = $_obfuscate_gkt['proxyUid'];
        if ( empty( $_obfuscate_SXBi6VrH2yEÿ ) )
        {
            $_obfuscate_7qDAYo85aGAÿ = array( );
        }
        else
        {
            $_obfuscate_7qDAYo85aGAÿ['touid'] = $_obfuscate_SXBi6VrH2yEÿ;
            $_obfuscate_7qDAYo85aGAÿ['toName'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_SXBi6VrH2yEÿ );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_7qDAYo85aGAÿ;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _warnFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_0W8ÿ = getpar( $_POST, "stepId", 0 );
        $_obfuscate_1l6P = getpar( $_POST, "say", "" );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_a9lP = getpar( $_POST, "sms", "false" );
        $_obfuscate_a9lP = $_obfuscate_a9lP == "false" ? FALSE : TRUE;
        $_obfuscate_gkt = $this->getStepId( $_obfuscate_0W8ÿ );
        $_obfuscate_0Ul8BBkt = $_obfuscate_gkt['uStepId'];
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowNumber", "flowName", "flowId" ), $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $_obfuscate_lQ81YBMÿ = $this->getProxyUid( $_obfuscate_hTew0boWJESy['flowId'], $_obfuscate_gkt['uid'] );
        $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "beDuBanNeedStepTime" ).":".$_obfuscate_1l6P;
        $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
        $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_0W8ÿ;
        $this->addNotice( "notice", array(
            $_obfuscate_gkt['uid'],
            $_obfuscate_lQ81YBMÿ
        ), $_obfuscate_6RYLWQÿÿ, "warn" );
        if ( $_obfuscate_a9lP )
        {
            ( );
            $_obfuscate_a9lP = new sms( );
            $_obfuscate_a9lP->sendByUids( array(
                $_obfuscate_gkt['uid'],
                $_obfuscate_lQ81YBMÿ
            ), $_obfuscate_6RYLWQÿÿ['content'], 0, "flow", 0 );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3631, lang( "superFlowFlowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getFenfaList( )
    {
        global $CNOA_DB;
        $_obfuscate_TlvKhtsoOQÿÿ = ( integer )getpar( $_GET, "uFlowId" );
        $_obfuscate_3y0Y = "SELECT f.uFenfaId AS id, m.truename AS fenfaUname, u.truename AS viewUname, f.viewtime, f.say, f.isread FROM ".tname( $this->t_use_fenfa )." AS f LEFT JOIN ".tname( "main_user" )." AS m ON m.uid=f.fenfauid LEFT JOIN ".tname( "main_user" )." AS u ON u.uid=f.touid ".( "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ." ORDER BY uFenfaId" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQÿÿ = array( );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_gkt['viewtime'] = empty( $_obfuscate_gkt['viewtime'] ) ? "---" : date( "Y-m-d H:i", $_obfuscate_gkt['viewtime'] );
            $_obfuscate_gkt['isread'] = $_obfuscate_gkt['isread'] == 0 ? "å¦" : "æ˜¯";
            $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_gkt;
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _addFenfa( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQÿÿ = ( integer )getpar( $_POST, "uFlowId" );
        $_obfuscate_rHwsX0gg = explode( ",", getpar( $_POST, "toUids" ) );
        $_obfuscate_sZ_Wvha1 = $CNOA_DB->db_select( array( "touid" ), $this->t_use_fenfa, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ );
        if ( !is_array( $_obfuscate_sZ_Wvha1 ) )
        {
            $_obfuscate_sZ_Wvha1 = array( );
        }
        $_obfuscate_u9gCXhG9Sgÿÿ = array( );
        foreach ( $_obfuscate_sZ_Wvha1 as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_u9gCXhG9Sgÿÿ[] = $_obfuscate_6Aÿÿ['touid'];
        }
        $_obfuscate_3y0Y = "SELECT u.flowName, u.flowNumber, u.flowId, s.flowType, s.tplSort FROM ".tname( $this->t_use_flow )." AS u LEFT JOIN ".tname( $this->t_set_flow )." AS s ON s.flowId=u.flowId ".( "WHERE u.uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ." LIMIT 0, 1" );
        $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->get_one( $_obfuscate_3y0Y );
        $_obfuscate_Ybai = 0;
        foreach ( $_obfuscate_rHwsX0gg as $_obfuscate_6Aÿÿ )
        {
            if ( !( $_obfuscate_6Aÿÿ == $_obfuscate_7Ri3 ) )
            {
                if ( $_obfuscate_6Aÿÿ == $_obfuscate_7Ri3 )
                {
                    break;
                }
            }
            else
            {
                continue;
            }
            $_obfuscate_rLpOghzteElp['touid'] = $_obfuscate_6Aÿÿ;
            $_obfuscate_rLpOghzteElp['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
            $_obfuscate_rLpOghzteElp['fenfauid'] = $_obfuscate_7Ri3;
            $_obfuscate_Ce9h = $CNOA_DB->db_insert( $_obfuscate_rLpOghzteElp, $this->t_use_fenfa );
            $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" ).( "[".$_obfuscate_7qDAYo85aGAÿ['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_7qDAYo85aGAÿ['flowNumber']."]" ).lang( "fenFaForYouRead" );
            $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_7qDAYo85aGAÿ['flowId']}&flowType={$_obfuscate_7qDAYo85aGAÿ['flowType']}&tplSort={$_obfuscate_7qDAYo85aGAÿ['tplSort']}";
            $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_Ce9h;
            $this->addNotice( "notice", $_obfuscate_rLpOghzteElp['touid'], $_obfuscate_6RYLWQÿÿ, "fenfa" );
            ++$_obfuscate_Ybai;
        }
        if ( 0 < $_obfuscate_Ybai )
        {
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3604, lang( "fenFaPersonnel" ).( "[ ".$_obfuscate_7qDAYo85aGAÿ['flowName']." ] " ).lang( "bianHao" ).( "[ ".$_obfuscate_7qDAYo85aGAÿ['flowNumber']." ]" ) );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _delFenfa( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_0W8ÿ = ( integer )getpar( $_POST, "id" );
        $CNOA_DB->db_delete( $this->t_use_fenfa, "WHERE `uFenfaId`=".$_obfuscate_0W8ÿ );
        notice::deletenotice( $_obfuscate_0W8ÿ, $_obfuscate_0W8ÿ, $this->t_use_fenfa, "fenfa" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadModifyForm( )
    {
        global $CNOA_DB;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_0W8ÿ = getpar( $_POST, "stepId", 0 );
        $_obfuscate_gkt = $this->getStepId( $_obfuscate_0W8ÿ );
        $_obfuscate_0Ul8BBkt = $_obfuscate_gkt['uStepId'];
        $_obfuscate_xC4hS_ButxQÿ = $_obfuscate_gkt['uid'];
        if ( empty( $_obfuscate_xC4hS_ButxQÿ ) )
        {
            $_obfuscate_xC4hS_ButxQÿ = array( );
        }
        else
        {
            $_obfuscate_7qDAYo85aGAÿ['uid'] = $_obfuscate_xC4hS_ButxQÿ;
            $_obfuscate_7qDAYo85aGAÿ['uname'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_xC4hS_ButxQÿ );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_7qDAYo85aGAÿ;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _modify( )
    {
        global $CNOA_DB;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_0W8ÿ = getpar( $_POST, "stepId", 0 );
        $_obfuscate_gkt = $this->getStepId( $_obfuscate_0W8ÿ );
        $_obfuscate_ecqaH_ev7Aÿÿ = $_obfuscate_gkt['uStepId'];
        $_obfuscate_7Ri3 = getpar( $_POST, "uid", 0 );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_5ZL98vEÿ = $this->getProxyUid( $_obfuscate_F4AbnVRh, $_obfuscate_7Ri3 );
        $_obfuscate_0Ul8BBkt = $_obfuscate_0W8ÿ;
        $this->deleteNotice( "both", $_obfuscate_0Ul8BBkt, "todo" );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber" ), $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "correctNeesYouApp" );
        $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_ecqaH_ev7Aÿÿ}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
        $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_0Ul8BBkt;
        $this->addNotice( "both", array(
            $_obfuscate_7Ri3,
            $_obfuscate_5ZL98vEÿ
        ), $_obfuscate_6RYLWQÿÿ, "todo" );
        $CNOA_DB->db_update( array(
            "uid" => $_obfuscate_7Ri3,
            "proxyUid" => $_obfuscate_5ZL98vEÿ
        ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `id`='{$_obfuscate_0W8ÿ}'" );
        $CNOA_DB->db_update( array(
            "uid" => $_obfuscate_7Ri3
        ), $this->t_use_event, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `step`='{$_obfuscate_ecqaH_ev7Aÿÿ}' AND `uid`={$_obfuscate_gkt['dealUid']}" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3631, lang( "correctFlow" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _cancelFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $this->api_cancelFlow( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _stopFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_POST, "tplSort", 0 );
        if ( !$this->checkMgrPermit( $_obfuscate_TlvKhtsoOQÿÿ ) )
        {
            msg::callback( FALSE, lang( "notPermitMgrFlow" ) );
        }
        ( $_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_e53ODz04JQÿÿ = new wfCache( );
        $_obfuscate_qZkmBgÿÿ = $_obfuscate_e53ODz04JQÿÿ->getFlow( );
        $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "id", "uid", "proxyUid", "dealUid", "status", "uStepId", "stepname" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND status!=0 " );
        if ( !is_array( $_obfuscate_Tx7M9W ) )
        {
            $_obfuscate_Tx7M9W = array( );
        }
        $_obfuscate_VZzSMXQx6Qÿÿ = array( );
        foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_QwT4KwrB2wÿÿ[] = $_obfuscate_6Aÿÿ['id'];
            $_obfuscate_VZzSMXQx6Qÿÿ[] = $_obfuscate_6Aÿÿ['dealUid'];
            $_obfuscate_VZzSMXQx6Qÿÿ[] = $_obfuscate_6Aÿÿ['proxyUid'];
            $_obfuscate_VZzSMXQx6Qÿÿ[] = $_obfuscate_6Aÿÿ['uid'];
        }
        $this->deleteAllNotice( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_QwT4KwrB2wÿÿ );
        if ( $_obfuscate_qZkmBgÿÿ['noticeAtInterrupt'] !== 4 )
        {
            $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber", "uid" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
            if ( $_obfuscate_qZkmBgÿÿ['noticeAtInterrupt'] == 1 )
            {
                $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "by" )."[".$CNOA_SESSION->get( "TRUENAME" )."]".lang( "abort" );
                $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
                $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_0Ul8BBkt;
                $this->addNotice( "notice", $_obfuscate_hTew0boWJESy['uid'], $_obfuscate_6RYLWQÿÿ, "stop", 0 );
            }
            else if ( $_obfuscate_qZkmBgÿÿ['noticeAtInterrupt'] == 2 )
            {
                $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "uid", "proxyUid", "dealUid" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `status` = '1' " );
                $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "by" )."[".$CNOA_SESSION->get( "TRUENAME" )."]".lang( "abort" );
                $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
                $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_0Ul8BBkt;
                $this->addNotice( "notice", array(
                    $_obfuscate_hTew0boWJESy['uid'],
                    $_obfuscate_Tx7M9W['proxyUid'],
                    $_obfuscate_Tx7M9W['dealUid']
                ), $_obfuscate_6RYLWQÿÿ, "stop", 1 );
            }
            else if ( $_obfuscate_qZkmBgÿÿ['noticeAtInterrupt'] == 3 )
            {
                $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "by" )."[".$CNOA_SESSION->get( "TRUENAME" )."]".lang( "abort" );
                $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
                $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_0Ul8BBkt;
                $this->addNotice( "notice", array_unique( $_obfuscate_VZzSMXQx6Qÿÿ ), $_obfuscate_6RYLWQÿÿ, "stop", 2 );
            }
        }
        $_obfuscate_JG8GuYÿ = array( );
        $_obfuscate_JG8GuYÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
        $_obfuscate_JG8GuYÿ['type'] = 10;
        $_obfuscate_JG8GuYÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuYÿ['uid'] = $_obfuscate_7Ri3;
        foreach ( $_obfuscate_Tx7M9W as $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['status'] == 1 )
            {
                $_obfuscate_JG8GuYÿ['step'] = $_obfuscate_6Aÿÿ['uStepId'];
                $_obfuscate_JG8GuYÿ['stepname'] = $_obfuscate_6Aÿÿ['stepname'];
                $this->insertEvent( $_obfuscate_JG8GuYÿ );
            }
        }
        $CNOA_DB->db_update( array( "status" => 3 ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `status`=1" );
        $CNOA_DB->db_update( array( "status" => 6 ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        $CNOA_DB->db_update( array( "status" => 6 ), "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE `uFlowId` = ".$_obfuscate_TlvKhtsoOQÿÿ." " );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3631, lang( "suspendFlow" )."[".$_obfuscate_qZkmBgÿÿ['flowName']."]".lang( "bianHao" )."[".$_obfuscate_qZkmBgÿÿ['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _delete( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowId", "flowName", "flowNumber", "attach", "sortId" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        if ( $_obfuscate_hTew0boWJESy['flowId'] == "0" || $_obfuscate_hTew0boWJESy['sortId'] == "0" )
        {
            if ( $_obfuscate_7Ri3 != 1 )
            {
                msg::callback( FALSE, lang( "flowRequireSAtoDel" ) );
            }
        }
        else if ( !$this->checkMgrPermit( $_obfuscate_TlvKhtsoOQÿÿ ) )
        {
            msg::callback( FALSE, lang( "notPermitMgrFlow" ) );
        }
        $_obfuscate_8NFcIjs_ = $CNOA_DB->db_getone( "*", $this->t_use_step_child_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        if ( $_obfuscate_8NFcIjs_ )
        {
            $_obfuscate_g_yvfhSllnxRQÿÿ = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_8NFcIjs_['puFlowId']."'" );
            msg::callback( FALSE, lang( "flowIsNumber" ).( " (".$_obfuscate_g_yvfhSllnxRQÿÿ['flowNumber'].")" ).lang( "flowOfSubflow" )."ï¼Œ".lang( "notDel" )."<br />".lang( "ifDelPleaseDelPflow" ) );
        }
        $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "id" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        if ( !is_array( $_obfuscate_Tx7M9W ) )
        {
            $_obfuscate_Tx7M9W = array( );
        }
        foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_QwT4KwrB2wÿÿ[] = $_obfuscate_6Aÿÿ['id'];
        }
        $this->deleteAllNotice( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_QwT4KwrB2wÿÿ );
        $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "by" )."[".$CNOA_SESSION->get( "TRUENAME" )."]".lang( "del" );
        $_obfuscate_6RYLWQÿÿ['href'] = "";
        $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_TlvKhtsoOQÿÿ;
        $this->addNotice( "notice", $_obfuscate_hTew0boWJESy['uid'], $_obfuscate_6RYLWQÿÿ, "delete", 0 );
        ( );
        $_obfuscate_2ggÿ = new fs( );
        $_obfuscate_WPvkSFEMgÿÿ = json_decode( $_obfuscate_hTew0boWJESy['attach'], TRUE );
        if ( is_array( $_obfuscate_WPvkSFEMgÿÿ ) && 0 < count( $_obfuscate_WPvkSFEMgÿÿ ) )
        {
            $_obfuscate_2ggÿ->deleteFile( $_obfuscate_WPvkSFEMgÿÿ );
        }
        $CNOA_DB->db_delete( "z_wf_t_".$_obfuscate_hTew0boWJESy['flowId'], "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        ( $_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_izJGxnXh9Aÿÿ = new wfCache( );
        $_obfuscate_mnGpwkqvvb1bAÿÿ = $_obfuscate_izJGxnXh9Aÿÿ->getFlowFields( );
        if ( !is_array( $_obfuscate_mnGpwkqvvb1bAÿÿ ) )
        {
            $_obfuscate_mnGpwkqvvb1bAÿÿ = array( );
        }
        foreach ( $_obfuscate_mnGpwkqvvb1bAÿÿ as $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['otype'] == "detailtable" )
            {
                $CNOA_DB->db_delete( "z_wf_d_".$_obfuscate_hTew0boWJESy['flowId']."_".$_obfuscate_6Aÿÿ['id'], "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
            }
        }
        ( $_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_e53ODz04JQÿÿ = new wfCache( );
        $_obfuscate_e53ODz04JQÿÿ->deleteCache( );
        $CNOA_DB->db_delete( $this->t_use_step_huiqian, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $CNOA_DB->db_delete( $this->t_use_proxy_uflow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $CNOA_DB->db_delete( $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $CNOA_DB->db_delete( $this->t_use_proxy_uflow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $CNOA_DB->db_delete( $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $CNOA_DB->db_delete( $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $CNOA_DB->db_delete( $this->t_use_event, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $CNOA_DB->db_delete( "wf_u_convergence_deal", "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $_obfuscate_eOytqZnoPmMkuTA2Aÿÿ = $CNOA_DB->db_select( "*", $this->t_use_step_child_flow, "WHERE `puFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        if ( $_obfuscate_eOytqZnoPmMkuTA2Aÿÿ )
        {
            $_obfuscate_xHZmyK5cgÿÿ = array( );
            foreach ( $_obfuscate_eOytqZnoPmMkuTA2Aÿÿ as $_obfuscate_6Aÿÿ )
            {
                $this->deleteNotice( "both", $_obfuscate_6Aÿÿ['id'], "child" );
            }
        }
        $CNOA_DB->db_delete( $this->t_use_step_child_flow, "WHERE `puFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 3631, lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _ms_loadTemplateFile( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_GET, "tplSort", 0 );
        $_obfuscate_o6LA2yPirJIreFAÿ = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQÿÿ}/doc.history.0.php" );
        $_obfuscate_U9NJHZRq6Jr7T_Aÿ = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQÿÿ}/xls.history.0.php" );
        debug::xprint( 2323232 );
        if ( $_obfuscate_pEvU7Kz2Ywÿÿ == 1 || $_obfuscate_pEvU7Kz2Ywÿÿ == 3 )
        {
            if ( file_exists( $_obfuscate_o6LA2yPirJIreFAÿ ) )
            {
                $_obfuscate_6hS1Rwÿÿ = @file_get_contents( $_obfuscate_o6LA2yPirJIreFAÿ );
            }
        }
        else if ( file_exists( $_obfuscate_U9NJHZRq6Jr7T_Aÿ ) )
        {
            $_obfuscate_6hS1Rwÿÿ = @file_get_contents( $_obfuscate_U9NJHZRq6Jr7T_Aÿ );
        }
        echo $_obfuscate_6hS1Rwÿÿ;
        exit( );
    }

    private function _ms_submitTemplateFile( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", 0 );
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_GET, "tplSort", 0 );
        $this->api_saveTemplateData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_pEvU7Kz2Ywÿÿ );
        exit( );
    }

    private function _getManageStep( )
    {
        global $CNOA_DB;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "id", "uStepId", "stepname", "uid" ), $this->t_use_step, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ." AND `status`=1 AND `uid`!=0" );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_3gn_eQÿÿ = app::loadapp( "main", "user" )->api_getUserDataByUid( $_obfuscate_VgKtFegÿ['uid'] );
            $_obfuscate_mPAjEGLn[$_obfuscate_Vwty]['stepname'] = $_obfuscate_VgKtFegÿ['stepname']."-".$_obfuscate_3gn_eQÿÿ['truename'];
            unset( $_obfuscate_VgKtFegÿ['stepname']['uid'] );
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function getStepId( $_obfuscate_0W8ÿ )
    {
        global $CNOA_DB;
        $_obfuscate_6RYLWQÿÿ = $CNOA_DB->db_getone( "*", $this->t_use_step, "WHERE `id`=".$_obfuscate_0W8ÿ );
        return $_obfuscate_6RYLWQÿÿ;
    }

    private function _getFlowInfo( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId" );
        $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->db_getone( array( "nameRuleId", "flowType", "tplSort" ), $this->t_set_flow, "WHERE `flowId`=".$_obfuscate_F4AbnVRh );
        echo json_encode( $_obfuscate_7qDAYo85aGAÿ );
    }

}

?>
