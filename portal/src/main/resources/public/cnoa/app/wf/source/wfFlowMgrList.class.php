<?php

class wfFlowMgrList extends wfCommon
{

    public function run( )
    {
        $_obfuscate_M_5JJw�� = getpar( $_GET, "task", getpar( $_POST, "task", "" ) );
        switch ( $_obfuscate_M_5JJw�� )
        {
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "getSortTree" :
            app::loadapp( "wf", "flowSetSort" )->api_getSortTree( "chayue&guanli" );
            break;
        case "getJsonData" :
            $_obfuscate_vholQ�� = getpar( $_POST, "from", "normal" );
            if ( $_obfuscate_vholQ�� == "normal" )
            {
                $this->_getJsonData( );
            }
            else
            {
                if ( !( $_obfuscate_vholQ�� == "overtime" ) )
                {
                    break;
                }
                $this->_getOverTimeJsonData( );
            }
            break;
        case "getAllUserListsInPermitDeptTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $_obfuscate_phKp89pDgwQ� = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $_obfuscate_phKp89pDgwQ� );
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
            $_obfuscate_Tc82k3jOQ�� = $this->getFlowFields( );
            ( );
            $_obfuscate_NlQ� = new dataStore( );
            $_obfuscate_NlQ�->data = $_obfuscate_Tc82k3jOQ��;
            echo $_obfuscate_NlQ�->makeJsonData( );
            exit( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $_obfuscate_vholQ�� = getpar( $_GET, "from", getpar( $_POST, "from", "list" ) );
        switch ( $_obfuscate_vholQ�� )
        {
        case "list" :
            $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
            $_obfuscate_Tjp5MUfA0Faow�� = $CNOA_DB->db_getone( "*", $this->t_use_step_temptime, "WHERE `uid` = ".$_obfuscate_7Ri3." " );
            if ( empty( $_obfuscate_Tjp5MUfA0Faow��['stime'] ) )
            {
                $_obfuscate_Tjp5MUfA0Faow��['stime'] = "";
            }
            if ( empty( $_obfuscate_Tjp5MUfA0Faow��['etime'] ) )
            {
                $_obfuscate_Tjp5MUfA0Faow��['etime'] = date( "Y-m-t", $GLOBALS['CNOA_TIMESTAMP'] );
            }
            $GLOBALS['GLOBALS']['wf']['mgrList']['stime'] = $_obfuscate_Tjp5MUfA0Faow��['stime'];
            $GLOBALS['GLOBALS']['wf']['mgrList']['etime'] = $_obfuscate_Tjp5MUfA0Faow��['etime'];
            $GLOBALS['GLOBALS']['wf']['mgrList']['hour'] = $_obfuscate_Tjp5MUfA0Faow��['hour'];
            $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/mgr/list.htm";
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
            $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/mgr/listView.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQ�� );
        exit( );
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_neM4JBUJlmg� = getpar( $_POST, "flowName", "" );
        $_obfuscate_6b8lIO4y = getpar( $_POST, "status", "doing" );
        $_obfuscate_mV9HBLY� = ( integer )getpar( $_POST, "start" );
        $_obfuscate_v1GprsIz = ( integer )getpar( $_POST, "sortId" );
        $_obfuscate_F4AbnVRh = ( integer )getpar( $_POST, "flowId" );
        $_obfuscate_xvYeh9I� = getpagesize( "wf_flow_mgr_list_getJsonData" );
        $_obfuscate_ggyI68Xtqd62AQ�� = $this->checkSortPermit( );
        if ( !$_obfuscate_ggyI68Xtqd62AQ�� )
        {
            ( );
            $_obfuscate_NlQ� = new dataStore( );
            echo $_obfuscate_NlQ�->makeJsonData( );
            exit( );
        }
        $_obfuscate_MujU5qIjWA�� = array_keys( $_obfuscate_ggyI68Xtqd62AQ�� );
        $_obfuscate_j9sJes� = $_obfuscate_IRFhnYw� = array( );
        if ( $_obfuscate_6b8lIO4y == "doing" )
        {
            $_obfuscate_IRFhnYw�[] = "u.status=1";
            $_obfuscate_LeZTBxNr4g�� = "ORDER BY u.updatetime DESC";
            $_obfuscate_MBTw2buyv8Lb = "AND `status`=1 ORDER BY `id` DESC";
        }
        else if ( $_obfuscate_6b8lIO4y == "done" )
        {
            $_obfuscate_IRFhnYw�[] = "u.status=2";
            $_obfuscate_LeZTBxNr4g�� = "ORDER BY u.endtime DESC";
        }
        else
        {
            $_obfuscate_IRFhnYw�[] = "u.status!=0";
            $_obfuscate_LeZTBxNr4g�� = "ORDER BY u.uFlowId DESC";
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
                $_obfuscate_IRFhnYw�[] = $_obfuscate_dcwitxb;
                $_obfuscate_IRFhnYw� = "WHERE ".implode( " AND ", $_obfuscate_IRFhnYw� );
            }
            else
            {
                $_obfuscate_IRFhnYw� = "WHERE ".implode( "", $_obfuscate_IRFhnYw� );
            }
            $_obfuscate_3y0Y = "SELECT u.*, s.name AS flowSetName, s.flowType, s.tplSort, c.name AS sname, s.status AS againStatus FROM ".tname( self::TABLE_PRE_DATA.$_obfuscate_F4AbnVRh )." AS d LEFT JOIN ".tname( $this->t_use_flow )." AS u ON u.uFlowId=d.uFlowId LEFT JOIN ".tname( $this->t_set_flow )." AS s ON s.flowId=u.flowId LEFT JOIN ".tname( $this->t_set_sort )." AS c ON c.sortId=u.sortId "."{$_obfuscate_IRFhnYw�} {$_obfuscate_LeZTBxNr4g��} LIMIT {$_obfuscate_mV9HBLY�}, {$_obfuscate_xvYeh9I�}";
            $_obfuscate_k848c9fV6gI� = "SELECT count(*) AS count FROM ".tname( self::TABLE_PRE_DATA.$_obfuscate_F4AbnVRh )." AS d LEFT JOIN ".tname( $this->t_use_flow )." AS u ON u.uFlowId=d.uFlowId "."{$_obfuscate_IRFhnYw�}";
            $_obfuscate_j9sJes� = $CNOA_DB->get_one( $_obfuscate_k848c9fV6gI� );
            $_obfuscate_j9sJes� = ( integer )$_obfuscate_j9sJes�['count'];
        }
        else
        {
            if ( !empty( $_obfuscate_v1GprsIz ) )
            {
                if ( !in_array( $_obfuscate_v1GprsIz, $_obfuscate_MujU5qIjWA�� ) )
                {
                    ( );
                    $_obfuscate_NlQ� = new dataStore( );
                    echo $_obfuscate_NlQ�->makeJsonData( );
                    exit( );
                }
                $_obfuscate_IRFhnYw�[] = "u.sortId=".$_obfuscate_v1GprsIz;
            }
            else
            {
                $_obfuscate_IRFhnYw�[] = "u.sortId IN(".implode( ",", $_obfuscate_MujU5qIjWA�� ).")";
            }
            if ( !empty( $_obfuscate_neM4JBUJlmg� ) )
            {
                $_obfuscate_IRFhnYw�[] = "(u.flowName LIKE '%".$_obfuscate_neM4JBUJlmg�."%' OR u.flowNumber LIKE '%{$_obfuscate_neM4JBUJlmg�}%')";
            }
            $_obfuscate_IRFhnYw� = "WHERE ".implode( " AND ", $_obfuscate_IRFhnYw� );
            $_obfuscate_3y0Y = "SELECT u.*, s.name AS flowSetName, s.flowType, s.tplSort, c.name AS sname, s.status AS againStatus FROM ".tname( $this->t_use_flow )." AS u LEFT JOIN ".tname( $this->t_set_flow )." AS s ON s.flowId=u.flowId LEFT JOIN ".tname( $this->t_set_sort )." AS c ON c.sortId=u.sortId "."{$_obfuscate_IRFhnYw�} {$_obfuscate_LeZTBxNr4g��} LIMIT {$_obfuscate_mV9HBLY�}, {$_obfuscate_xvYeh9I�}";
            $_obfuscate_j9sJes� = $CNOA_DB->db_getCount( $this->t_use_flow, str_replace( "u.", "", $_obfuscate_IRFhnYw� ) );
        }
        $_obfuscate__eqrEQ�� = $_obfuscate_dDHiUSY4Qo� = $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_dDHiUSY4Qo�[] = $_obfuscate_gkt['uFlowId'];
            $_obfuscate__eqrEQ��[] = $_obfuscate_gkt['uid'];
            $_obfuscate_6RYLWQ��[] = $_obfuscate_gkt;
        }
        if ( count( $_obfuscate_6RYLWQ�� ) == 0 )
        {
            ( );
            $_obfuscate_NlQ� = new dataStore( );
            echo $_obfuscate_NlQ�->makeJsonData( );
            exit( );
        }
        $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "id", "uFlowId", "dealUid", "proxyUid", "uid", "uStepId" ), $this->t_use_step, "WHERE `uFlowId` IN (".implode( ",", $_obfuscate_dDHiUSY4Qo� ).") ".$_obfuscate_MBTw2buyv8Lb );
        if ( !is_array( $_obfuscate_Tx7M9W ) )
        {
            $_obfuscate_Tx7M9W = array( );
        }
        $_obfuscate_Q_ThbHHpIfvI = array( );
        foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_Q_ThbHHpIfvI[$_obfuscate_6A��['uFlowId']][] = $_obfuscate_6A��;
        }
        foreach ( $_obfuscate_Q_ThbHHpIfvI as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            usort( &$_obfuscate_Q_ThbHHpIfvI[$_obfuscate_5w��], array(
                $this,
                "_stepSort"
            ) );
        }
        $_obfuscate_Tx7M9W = array( );
        foreach ( $_obfuscate_Q_ThbHHpIfvI as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            foreach ( $_obfuscate_6A�� as $_obfuscate_3QY� => $_obfuscate_EGU� )
            {
                if ( $_obfuscate_3QY� == 0 )
                {
                    $_obfuscate_Tx7M9W[] = $_obfuscate_EGU�;
                }
            }
        }
        foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( !empty( $_obfuscate_6A��['proxyUid'] ) )
            {
                $_obfuscate__eqrEQ��[] = $_obfuscate_6A��['proxyUid'];
                $_obfuscate_bBvQ3BLPRzlI[$_obfuscate_6A��['uFlowId']]['stepUid'] = $_obfuscate_6A��['proxyUid'];
            }
            if ( !empty( $_obfuscate_6A��['uid'] ) )
            {
                $_obfuscate__eqrEQ��[] = $_obfuscate_6A��['uid'];
                $_obfuscate_bBvQ3BLPRzlI[$_obfuscate_6A��['uFlowId']]['step'] = $_obfuscate_6A��['uStepId'];
            }
            $_obfuscate_bBvQ3BLPRzlI[$_obfuscate_6A��['uFlowId']]['step'] = $_obfuscate_6A��['uStepId'];
        }
        $_obfuscate_YbfVGewvqPY� = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate__eqrEQ�� );
        $_obfuscate_tY4_zeP385SOvg�� = $_obfuscate_PEqrtnNpeS5H = array( );
        foreach ( $_obfuscate_Tx7M9W as $_obfuscate_6A�� )
        {
            $_obfuscate_QwT4KwrB2w��[$_obfuscate_6A��['uFlowId']] = $_obfuscate_6A��;
            if ( $_obfuscate_6A��['proxyUid'] != 0 )
            {
                $_obfuscate_PEqrtnNpeS5H[$_obfuscate_6A��['uFlowId']] = $_obfuscate_6A��['dealUid'];
                $_obfuscate_tY4_zeP385SOvg��[$_obfuscate_6A��['uFlowId']] = $_obfuscate_6A��['proxyUid'];
                $_obfuscate_QwT4KwrB2w��[$_obfuscate_6A��['uFlowId']]['stepUid'] = $_obfuscate_6A��['dealUid'];
            }
            else
            {
                $_obfuscate_QwT4KwrB2w��[$_obfuscate_6A��['uFlowId']]['stepUid'] = $_obfuscate_6A��['uid'];
            }
        }
        foreach ( $_obfuscate_6RYLWQ�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['level'] = $this->f_level[$_obfuscate_6A��['level']];
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['statusText'] = $this->f_status[$_obfuscate_6A��['status']];
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['uname'] = $_obfuscate_YbfVGewvqPY�[$_obfuscate_6A��['uid']];
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['posttime'] = date( "Y-m-d H:i", $_obfuscate_6A��['posttime'] );
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['permit'] = $_obfuscate_ggyI68Xtqd62AQ��[$_obfuscate_6A��['sortId']];
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['step'] = $_obfuscate_bBvQ3BLPRzlI[$_obfuscate_6A��['uFlowId']]['step'];
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['stepUid'] = $_obfuscate_QwT4KwrB2w��[$_obfuscate_6A��['uFlowId']]['stepUid'];
            if ( $_obfuscate_QwT4KwrB2w��[$_obfuscate_6A��['uFlowId']]['stepUid'] == 0 )
            {
                $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['stepUname'] = "------";
            }
            else
            {
                $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['stepUname'] = $_obfuscate_YbfVGewvqPY�[$_obfuscate_QwT4KwrB2w��[$_obfuscate_6A��['uFlowId']]['stepUid']];
            }
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['stepTrustUname'] = $_obfuscate_YbfVGewvqPY�[$_obfuscate_QwT4KwrB2w��[$_obfuscate_6A��['uFlowId']]['proxyUid']];
            if ( empty( $_obfuscate_PEqrtnNpeS5H[$_obfuscate_6A��['uFlowId']] ) || !( $_obfuscate_PEqrtnNpeS5H[$_obfuscate_6A��['uFlowId']] == $_obfuscate_tY4_zeP385SOvg��[$_obfuscate_6A��['uFlowId']] ) )
            {
                $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['proxyStatus'] = 1;
            }
        }
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_6RYLWQ��;
        $_obfuscate_NlQ�->total = $_obfuscate_j9sJes�;
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function _stepSort( $m, $_obfuscate_8A�� )
    {
        if ( $m['id'] < $_obfuscate_8A��['id'] )
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
        $_obfuscate_IRFhnYw� = "WHERE (`from`='g' OR `from`='c') AND ".( "( (`type` = 'p' AND `permitId` = '".$_obfuscate_7Ri3."') " ).( "OR (`type` = 's' AND `permitId` = '".$_obfuscate_y6jH."') " ).( "OR (`type` = 'd' AND `permitId` = '".$_obfuscate_iuzS."') ) " );
        $_obfuscate_99gmCq3UfCfZTQ�� = $CNOA_DB->db_select( array( "sortId", "from" ), $this->t_set_sort_permit, $_obfuscate_IRFhnYw� );
        if ( !is_array( $_obfuscate_99gmCq3UfCfZTQ�� ) )
        {
            $_obfuscate_99gmCq3UfCfZTQ�� = array( );
        }
        $_obfuscate_ggyI68Xtqd62AQ�� = array( );
        foreach ( $_obfuscate_99gmCq3UfCfZTQ�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['from'] == "g" )
            {
                $_obfuscate_ggyI68Xtqd62AQ��[$_obfuscate_6A��['sortId']] = "g";
            }
            else if ( !( $_obfuscate_6A��['from'] == "c" ) && isset( $_obfuscate_ggyI68Xtqd62AQ��[$_obfuscate_6A��['sortId']] ) )
            {
                $_obfuscate_ggyI68Xtqd62AQ��[$_obfuscate_6A��['sortId']] = "c";
            }
        }
        if ( 0 < count( $_obfuscate_ggyI68Xtqd62AQ�� ) )
        {
            return $_obfuscate_ggyI68Xtqd62AQ��;
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
        $_obfuscate_qx37NM� = getpar( $_POST, "stime", 0 );
        $_obfuscate_KWKBW4� = getpar( $_POST, "etime", 0 );
        $_obfuscate_4QMgvg�� = getpar( $_POST, "hour", 0 );
        $_obfuscate_v1GprsIz = getpar( $_POST, "sortId", 0 );
        $_obfuscate_neM4JBUJlmg� = getpar( $_POST, "flowName", "" );
        $CNOA_DB->db_replace( array(
            "uid" => $_obfuscate_7Ri3,
            "stime" => $_obfuscate_qx37NM�,
            "etime" => $_obfuscate_KWKBW4�,
            "hour" => $_obfuscate_4QMgvg��,
            "uid" => $_obfuscate_7Ri3
        ), $this->t_use_step_temptime );
        $_obfuscate_xvYeh9I� = getpagesize( "wf_flow_mgr_list_getOverTimeJsonData" );
        $_obfuscate_Bk2lGlk� = "WHERE 1 ";
        $_obfuscate_mV9HBLY� = getpar( $_POST, "start", 0 );
        $_obfuscate_eVTMIa1A = app::loadapp( "wf", "flowSetSort" )->api_getSortDB( "guanli" );
        if ( !is_array( $_obfuscate_eVTMIa1A ) )
        {
            $_obfuscate_eVTMIa1A = array( );
        }
        $_obfuscate_5_FLZDYg5BK5 = array( 0 );
        foreach ( $_obfuscate_eVTMIa1A as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_5_FLZDYg5BK5[] = $_obfuscate_6A��['sortId'];
        }
        $_obfuscate_Bk2lGlk� .= "AND `sortId` IN (".implode( ",", $_obfuscate_5_FLZDYg5BK5 ).")";
        if ( !empty( $_obfuscate_v1GprsIz ) )
        {
            $_obfuscate_Bk2lGlk� .= "AND `sortId` = ".$_obfuscate_v1GprsIz." ";
        }
        if ( !empty( $_obfuscate_qx37NM� ) )
        {
            $_obfuscate_qx37NM� = strtotime( "{$_obfuscate_qx37NM�} 00:00:00" );
            $_obfuscate_Bk2lGlk� .= "AND `stime` > ".$_obfuscate_qx37NM�." ";
        }
        if ( !empty( $_obfuscate_KWKBW4� ) )
        {
            $_obfuscate_KWKBW4� = strtotime( "{$_obfuscate_KWKBW4�} 23:59:59" );
            $_obfuscate_Bk2lGlk� .= "AND `stime` < ".$_obfuscate_KWKBW4�." ";
        }
        if ( !empty( $_obfuscate_4QMgvg�� ) )
        {
            $_obfuscate_4QMgvg�� *= 3600;
            $_obfuscate_Bk2lGlk� .= "AND `timegap` > ".$_obfuscate_4QMgvg��." ";
        }
        $_obfuscate_6b8lIO4y = getpar( $_POST, "status", "doing" );
        if ( $_obfuscate_6b8lIO4y == "doing" )
        {
            $_obfuscate_Bk2lGlk� .= "AND `status` = 1 ";
        }
        $_obfuscate_dekTUGw = "";
        if ( !empty( $_obfuscate_neM4JBUJlmg� ) )
        {
            $_obfuscate_dekTUGw .= "WHERE 1 AND (`flowName` LIKE '%".$_obfuscate_neM4JBUJlmg�."%' OR `flowNumber` LIKE '%{$_obfuscate_neM4JBUJlmg�}%') ";
            $_obfuscate_G1iYRp3F = $CNOA_DB->db_select( array( "flowNumber", "flowName", "uFlowId", "flowId" ), $this->t_use_flow, $_obfuscate_dekTUGw );
            if ( !is_array( $_obfuscate_G1iYRp3F ) )
            {
                $_obfuscate_G1iYRp3F = array( );
            }
            $_obfuscate_dDHiUSY4Qo� = array( );
            foreach ( $_obfuscate_G1iYRp3F as $_obfuscate_6A�� )
            {
                $_obfuscate_dDHiUSY4Qo�[] = $_obfuscate_6A��['uFlowId'];
            }
            if ( 0 < count( $_obfuscate_dDHiUSY4Qo� ) )
            {
                $_obfuscate_Bk2lGlk� .= "AND `uFlowId` IN (".implode( ",", $_obfuscate_dDHiUSY4Qo� ).") ";
            }
        }
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "id", "uFlowId", "uStepId", "stime", "etime", "dealUid", "uid", "proxyUid", "stepname", "status", "uFlowId", "timegap", "sortId", "status" ), $this->t_use_step, $_obfuscate_Bk2lGlk�.( "AND `stepType` != 1 AND `stepType` != 3 ORDER BY `timegap` DESC LIMIT ".$_obfuscate_mV9HBLY�.", {$_obfuscate_xvYeh9I�} " ) );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_PVLK5jra = array( 0 );
        $_obfuscate_JC6sZe6bzW0Hyg�� = array( 0 );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( !empty( $_obfuscate_6A��['dealUid'] ) )
            {
                $_obfuscate_PVLK5jra[] = $_obfuscate_6A��['dealUid'];
            }
            else
            {
                $_obfuscate_PVLK5jra[] = $_obfuscate_6A��['uid'];
                $_obfuscate_PVLK5jra[] = $_obfuscate_6A��['proxyUid'];
            }
            $_obfuscate_JC6sZe6bzW0Hyg��[] = $_obfuscate_6A��['uFlowId'];
            $_obfuscate_uly_hPh_dQ��[] = $_obfuscate_6A��['sortId'];
        }
        $_obfuscate_GCfDSanL49WUEA�� = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_PVLK5jra );
        $_obfuscate_oCNnaL2WsRZ = $CNOA_DB->db_select( array( "flowNumber", "flowName", "uFlowId", "level", "flowId" ), $this->t_use_flow, "WHERE `uFlowId` IN (".implode( ",", $_obfuscate_JC6sZe6bzW0Hyg�� ).")" );
        if ( !is_array( $_obfuscate_oCNnaL2WsRZ ) )
        {
            $_obfuscate_oCNnaL2WsRZ = array( );
        }
        foreach ( $_obfuscate_oCNnaL2WsRZ as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_LEdLG6CYLyp6orI�[$_obfuscate_6A��['uFlowId']] = $_obfuscate_6A��;
            $_obfuscate_l2CIvUX0Kvp4[$_obfuscate_6A��['flowId']] = $_obfuscate_6A��['flowId'];
        }
        $_obfuscate_RopcQP_w = app::loadapp( "wf", "flowSetSort" )->api_getSortData( array(
            "sortIdArr" => $_obfuscate_uly_hPh_dQ��
        ) );
        $_obfuscate_SIUSR4F6 = app::loadapp( "wf", "flowSetFlow" )->api_getFlowData( array(
            "flowIdArr" => $_obfuscate_l2CIvUX0Kvp4,
            "field" => array( "name", "flowType", "tplSort" )
        ) );
        $_obfuscate_6RYLWQ�� = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( !empty( $_obfuscate_6A��['dealUid'] ) )
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['stepUname'] = $_obfuscate_GCfDSanL49WUEA��[$_obfuscate_6A��['dealUid']]['truename'];
            }
            else if ( empty( $_obfuscate_6A��['dealUid'] ) && empty( $_obfuscate_6A��['uid'] ) )
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['stepUname'] = $_obfuscate_GCfDSanL49WUEA��[$_obfuscate_6A��['proxyUid']]['truename'];
            }
            else
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['stepUname'] = $_obfuscate_GCfDSanL49WUEA��[$_obfuscate_6A��['uid']]['truename'];
            }
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['flowNumber'] = empty( $_obfuscate_LEdLG6CYLyp6orI�[$_obfuscate_6A��['uFlowId']]['flowNumber'] ) ? "----" : $_obfuscate_LEdLG6CYLyp6orI�[$_obfuscate_6A��['uFlowId']]['flowNumber'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['flowName'] = empty( $_obfuscate_LEdLG6CYLyp6orI�[$_obfuscate_6A��['uFlowId']]['flowName'] ) ? "----" : $_obfuscate_LEdLG6CYLyp6orI�[$_obfuscate_6A��['uFlowId']]['flowName'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['level'] = $this->f_level[$_obfuscate_LEdLG6CYLyp6orI�[$_obfuscate_6A��['uFlowId']]['level']];
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['sname'] = empty( $_obfuscate_RopcQP_w[$_obfuscate_6A��['sortId']]['name'] ) ? "----" : $_obfuscate_RopcQP_w[$_obfuscate_6A��['sortId']]['name'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['flowSetName'] = empty( $_obfuscate_SIUSR4F6[$_obfuscate_LEdLG6CYLyp6orI�[$_obfuscate_6A��['uFlowId']]['flowId']]['name'] ) ? "----" : $_obfuscate_SIUSR4F6[$_obfuscate_LEdLG6CYLyp6orI�[$_obfuscate_6A��['uFlowId']]['flowId']]['name'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['usetime'] = timeformat2( $_obfuscate_6A��['timegap'] );
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['stime'] = formatdate( $_obfuscate_6A��['stime'] );
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['etime'] = formatdate( $_obfuscate_6A��['etime'] );
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['flowId'] = $_obfuscate_LEdLG6CYLyp6orI�[$_obfuscate_6A��['uFlowId']]['flowId'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['step'] = $_obfuscate_LEdLG6CYLyp6orI�[$_obfuscate_6A��['uFlowId']]['step'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['stepUid'] = $_obfuscate_LEdLG6CYLyp6orI�[$_obfuscate_6A��['uFlowId']]['stepUid'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['flowType'] = $_obfuscate_SIUSR4F6[$_obfuscate_LEdLG6CYLyp6orI�[$_obfuscate_6A��['uFlowId']]['flowId']]['flowType'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['tplSort'] = $_obfuscate_SIUSR4F6[$_obfuscate_LEdLG6CYLyp6orI�[$_obfuscate_6A��['uFlowId']]['flowId']]['tplSort'];
            ( $_obfuscate_6A��['uFlowId'] );
            $_obfuscate_bIsJe6A� = new wfCache( );
            $_obfuscate_5NhzjnJq_f8� = $_obfuscate_bIsJe6A�->getStepByStepId( $_obfuscate_6A��['uStepId'] );
            $_obfuscate_TWcMviltrck� = $_obfuscate_5NhzjnJq_f8�['stepTime'];
            unset( $_obfuscate_bIsJe6A� );
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['overtime'] = $_obfuscate_TWcMviltrck� == 0 ? FALSE : $_obfuscate_TWcMviltrck� * 3600 < $_obfuscate_6A��['timegap'];
        }
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_mPAjEGLn;
        $_obfuscate_NlQ�->total = $CNOA_DB->db_getcount( $this->t_use_step, $_obfuscate_Bk2lGlk�."AND `stepType` != 1 AND `stepType` != 3" );
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function _loadFormHtml( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQ�� = intval( getpar( $_GET, "uFlowId", 0 ) );
        ( $_obfuscate_TlvKhtsoOQ�� );
        $GLOBALS['GLOBALS']['UWFCACHE'] = new wfCache( );
        $_obfuscate_SIUSR4F6 = $GLOBALS['UWFCACHE']->getFlow( );
        $_obfuscate_WYOD1IJTSw�� = $CNOA_DB->db_getfield( "uStepId", $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' ORDER BY `etime` DESC" );
        ( $GLOBALS['UWFCACHE']->getFlowId( ), $_obfuscate_WYOD1IJTSw��, FALSE, "done", $_obfuscate_TlvKhtsoOQ�� );
        $_obfuscate_P5_qcMiY39uereSduytrzD8� = new wfFieldFormaterForView( );
        $_obfuscate_5MjAF_AntLk� = $_obfuscate_P5_qcMiY39uereSduytrzD8�->crteateFormHtml( );
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->total = 0;
        $_obfuscate_SUjPN94Er7yI->data = array(
            "formHtml" => $_obfuscate_5MjAF_AntLk�
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
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_6RYLWQ��['uFlowId'] = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_6RYLWQ��['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_6RYLWQ��['flowId'] = 0;
        $_obfuscate_6RYLWQ��['fromuid'] = $_obfuscate_7Ri3;
        $_obfuscate_6RYLWQ��['touid'] = getpar( $_POST, "touid", 0 );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_0W8� = getpar( $_POST, "stepId", 0 );
        $_obfuscate_gkt = $this->getStepId( $_obfuscate_0W8� );
        $_obfuscate_ecqaH_ev7A�� = $_obfuscate_gkt['uStepId'];
        $_obfuscate_VZzSMXQx6Q�� = getpar( $_POST, "stepUid", 0 );
        $_obfuscate_Bk2lGlk� = "WHERE `fromuid`='".$_obfuscate_6RYLWQ��['fromuid']."' AND `uFlowId`='{$_obfuscate_6RYLWQ��['uFlowId']}' AND `flowId`=0";
        $_obfuscate_SIUSR4F6 = $CNOA_DB->db_getone( "*", $this->t_use_proxy_uflow, $_obfuscate_Bk2lGlk� );
        if ( !empty( $_obfuscate_SIUSR4F6 ) )
        {
            $CNOA_DB->db_delete( $this->t_use_proxy_uflow, $_obfuscate_Bk2lGlk� );
        }
        if ( $_obfuscate_6RYLWQ��['touid'] == $_obfuscate_VZzSMXQx6Q�� )
        {
            msg::callback( FALSE, lang( "principalNotChooseOwn" ) );
        }
        else
        {
            $_obfuscate_0Ul8BBkt = $_obfuscate_0W8�;
            $this->deleteNotice( "both", $_obfuscate_0Ul8BBkt, "trust" );
            $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber", "flowId" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_6RYLWQ��['uFlowId']."'  " );
            $_obfuscate_0AITFw��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "weiTuoToYou" );
            $_obfuscate_0AITFw��['href'] = "&uFlowId=".$_obfuscate_6RYLWQ��['uFlowId']."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_ecqaH_ev7A��}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
            $_obfuscate_0AITFw��['fromid'] = $_obfuscate_0Ul8BBkt;
            $this->addNotice( "both", $_obfuscate_6RYLWQ��['touid'], $_obfuscate_0AITFw��, "trust" );
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_use_proxy_uflow );
            $CNOA_DB->db_update( array(
                "proxyUid" => $_obfuscate_6RYLWQ��['touid']
            ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_6RYLWQ��['uFlowId']."' AND `id`='{$_obfuscate_0W8�}'" );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3631, lang( "flowWTflowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadEntrustForm( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_0W8� = getpar( $_POST, "stepId", 0 );
        $_obfuscate_gkt = $this->getStepId( $_obfuscate_0W8� );
        $_obfuscate_ecqaH_ev7A�� = $_obfuscate_gkt['uStepId'];
        $_obfuscate_SXBi6VrH2yE� = $_obfuscate_gkt['proxyUid'];
        if ( empty( $_obfuscate_SXBi6VrH2yE� ) )
        {
            $_obfuscate_7qDAYo85aGA� = array( );
        }
        else
        {
            $_obfuscate_7qDAYo85aGA�['touid'] = $_obfuscate_SXBi6VrH2yE�;
            $_obfuscate_7qDAYo85aGA�['toName'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_SXBi6VrH2yE� );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_7qDAYo85aGA�;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _warnFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_0W8� = getpar( $_POST, "stepId", 0 );
        $_obfuscate_1l6P = getpar( $_POST, "say", "" );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_a9lP = getpar( $_POST, "sms", "false" );
        $_obfuscate_a9lP = $_obfuscate_a9lP == "false" ? FALSE : TRUE;
        $_obfuscate_gkt = $this->getStepId( $_obfuscate_0W8� );
        $_obfuscate_0Ul8BBkt = $_obfuscate_gkt['uStepId'];
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowNumber", "flowName", "flowId" ), $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $_obfuscate_lQ81YBM� = $this->getProxyUid( $_obfuscate_hTew0boWJESy['flowId'], $_obfuscate_gkt['uid'] );
        $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "beDuBanNeedStepTime" ).":".$_obfuscate_1l6P;
        $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
        $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_0W8�;
        $this->addNotice( "notice", array(
            $_obfuscate_gkt['uid'],
            $_obfuscate_lQ81YBM�
        ), $_obfuscate_6RYLWQ��, "warn" );
        if ( $_obfuscate_a9lP )
        {
            ( );
            $_obfuscate_a9lP = new sms( );
            $_obfuscate_a9lP->sendByUids( array(
                $_obfuscate_gkt['uid'],
                $_obfuscate_lQ81YBM�
            ), $_obfuscate_6RYLWQ��['content'], 0, "flow", 0 );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3631, lang( "superFlowFlowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getFenfaList( )
    {
        global $CNOA_DB;
        $_obfuscate_TlvKhtsoOQ�� = ( integer )getpar( $_GET, "uFlowId" );
        $_obfuscate_3y0Y = "SELECT f.uFenfaId AS id, m.truename AS fenfaUname, u.truename AS viewUname, f.viewtime, f.say, f.isread FROM ".tname( $this->t_use_fenfa )." AS f LEFT JOIN ".tname( "main_user" )." AS m ON m.uid=f.fenfauid LEFT JOIN ".tname( "main_user" )." AS u ON u.uid=f.touid ".( "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ��." ORDER BY uFenfaId" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQ�� = array( );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_gkt['viewtime'] = empty( $_obfuscate_gkt['viewtime'] ) ? "---" : date( "Y-m-d H:i", $_obfuscate_gkt['viewtime'] );
            $_obfuscate_gkt['isread'] = $_obfuscate_gkt['isread'] == 0 ? "否" : "是";
            $_obfuscate_6RYLWQ��[] = $_obfuscate_gkt;
        }
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_6RYLWQ��;
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function _addFenfa( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQ�� = ( integer )getpar( $_POST, "uFlowId" );
        $_obfuscate_rHwsX0gg = explode( ",", getpar( $_POST, "toUids" ) );
        $_obfuscate_sZ_Wvha1 = $CNOA_DB->db_select( array( "touid" ), $this->t_use_fenfa, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ�� );
        if ( !is_array( $_obfuscate_sZ_Wvha1 ) )
        {
            $_obfuscate_sZ_Wvha1 = array( );
        }
        $_obfuscate_u9gCXhG9Sg�� = array( );
        foreach ( $_obfuscate_sZ_Wvha1 as $_obfuscate_6A�� )
        {
            $_obfuscate_u9gCXhG9Sg��[] = $_obfuscate_6A��['touid'];
        }
        $_obfuscate_3y0Y = "SELECT u.flowName, u.flowNumber, u.flowId, s.flowType, s.tplSort FROM ".tname( $this->t_use_flow )." AS u LEFT JOIN ".tname( $this->t_set_flow )." AS s ON s.flowId=u.flowId ".( "WHERE u.uFlowId=".$_obfuscate_TlvKhtsoOQ��." LIMIT 0, 1" );
        $_obfuscate_7qDAYo85aGA� = $CNOA_DB->get_one( $_obfuscate_3y0Y );
        $_obfuscate_Ybai = 0;
        foreach ( $_obfuscate_rHwsX0gg as $_obfuscate_6A�� )
        {
            if ( !( $_obfuscate_6A�� == $_obfuscate_7Ri3 ) )
            {
                if ( $_obfuscate_6A�� == $_obfuscate_7Ri3 )
                {
                    break;
                }
            }
            else
            {
                continue;
            }
            $_obfuscate_rLpOghzteElp['touid'] = $_obfuscate_6A��;
            $_obfuscate_rLpOghzteElp['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
            $_obfuscate_rLpOghzteElp['fenfauid'] = $_obfuscate_7Ri3;
            $_obfuscate_Ce9h = $CNOA_DB->db_insert( $_obfuscate_rLpOghzteElp, $this->t_use_fenfa );
            $_obfuscate_6RYLWQ��['content'] = lang( "flowName" ).( "[".$_obfuscate_7qDAYo85aGA�['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_7qDAYo85aGA�['flowNumber']."]" ).lang( "fenFaForYouRead" );
            $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_7qDAYo85aGA�['flowId']}&flowType={$_obfuscate_7qDAYo85aGA�['flowType']}&tplSort={$_obfuscate_7qDAYo85aGA�['tplSort']}";
            $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_Ce9h;
            $this->addNotice( "notice", $_obfuscate_rLpOghzteElp['touid'], $_obfuscate_6RYLWQ��, "fenfa" );
            ++$_obfuscate_Ybai;
        }
        if ( 0 < $_obfuscate_Ybai )
        {
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3604, lang( "fenFaPersonnel" ).( "[ ".$_obfuscate_7qDAYo85aGA�['flowName']." ] " ).lang( "bianHao" ).( "[ ".$_obfuscate_7qDAYo85aGA�['flowNumber']." ]" ) );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _delFenfa( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_0W8� = ( integer )getpar( $_POST, "id" );
        $CNOA_DB->db_delete( $this->t_use_fenfa, "WHERE `uFenfaId`=".$_obfuscate_0W8� );
        notice::deletenotice( $_obfuscate_0W8�, $_obfuscate_0W8�, $this->t_use_fenfa, "fenfa" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadModifyForm( )
    {
        global $CNOA_DB;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_0W8� = getpar( $_POST, "stepId", 0 );
        $_obfuscate_gkt = $this->getStepId( $_obfuscate_0W8� );
        $_obfuscate_0Ul8BBkt = $_obfuscate_gkt['uStepId'];
        $_obfuscate_xC4hS_ButxQ� = $_obfuscate_gkt['uid'];
        if ( empty( $_obfuscate_xC4hS_ButxQ� ) )
        {
            $_obfuscate_xC4hS_ButxQ� = array( );
        }
        else
        {
            $_obfuscate_7qDAYo85aGA�['uid'] = $_obfuscate_xC4hS_ButxQ�;
            $_obfuscate_7qDAYo85aGA�['uname'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_xC4hS_ButxQ� );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_7qDAYo85aGA�;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _modify( )
    {
        global $CNOA_DB;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_0W8� = getpar( $_POST, "stepId", 0 );
        $_obfuscate_gkt = $this->getStepId( $_obfuscate_0W8� );
        $_obfuscate_ecqaH_ev7A�� = $_obfuscate_gkt['uStepId'];
        $_obfuscate_7Ri3 = getpar( $_POST, "uid", 0 );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_5ZL98vE� = $this->getProxyUid( $_obfuscate_F4AbnVRh, $_obfuscate_7Ri3 );
        $_obfuscate_0Ul8BBkt = $_obfuscate_0W8�;
        $this->deleteNotice( "both", $_obfuscate_0Ul8BBkt, "todo" );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber" ), $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "correctNeesYouApp" );
        $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_ecqaH_ev7A��}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
        $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_0Ul8BBkt;
        $this->addNotice( "both", array(
            $_obfuscate_7Ri3,
            $_obfuscate_5ZL98vE�
        ), $_obfuscate_6RYLWQ��, "todo" );
        $CNOA_DB->db_update( array(
            "uid" => $_obfuscate_7Ri3,
            "proxyUid" => $_obfuscate_5ZL98vE�
        ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `id`='{$_obfuscate_0W8�}'" );
        $CNOA_DB->db_update( array(
            "uid" => $_obfuscate_7Ri3
        ), $this->t_use_event, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `step`='{$_obfuscate_ecqaH_ev7A��}' AND `uid`={$_obfuscate_gkt['dealUid']}" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3631, lang( "correctFlow" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _cancelFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $this->api_cancelFlow( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _stopFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_POST, "tplSort", 0 );
        if ( !$this->checkMgrPermit( $_obfuscate_TlvKhtsoOQ�� ) )
        {
            msg::callback( FALSE, lang( "notPermitMgrFlow" ) );
        }
        ( $_obfuscate_TlvKhtsoOQ�� );
        $_obfuscate_e53ODz04JQ�� = new wfCache( );
        $_obfuscate_qZkmBg�� = $_obfuscate_e53ODz04JQ��->getFlow( );
        $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "id", "uid", "proxyUid", "dealUid", "status", "uStepId", "stepname" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND status!=0 " );
        if ( !is_array( $_obfuscate_Tx7M9W ) )
        {
            $_obfuscate_Tx7M9W = array( );
        }
        $_obfuscate_VZzSMXQx6Q�� = array( );
        foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_QwT4KwrB2w��[] = $_obfuscate_6A��['id'];
            $_obfuscate_VZzSMXQx6Q��[] = $_obfuscate_6A��['dealUid'];
            $_obfuscate_VZzSMXQx6Q��[] = $_obfuscate_6A��['proxyUid'];
            $_obfuscate_VZzSMXQx6Q��[] = $_obfuscate_6A��['uid'];
        }
        $this->deleteAllNotice( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_QwT4KwrB2w�� );
        if ( $_obfuscate_qZkmBg��['noticeAtInterrupt'] !== 4 )
        {
            $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber", "uid" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
            if ( $_obfuscate_qZkmBg��['noticeAtInterrupt'] == 1 )
            {
                $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "by" )."[".$CNOA_SESSION->get( "TRUENAME" )."]".lang( "abort" );
                $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
                $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_0Ul8BBkt;
                $this->addNotice( "notice", $_obfuscate_hTew0boWJESy['uid'], $_obfuscate_6RYLWQ��, "stop", 0 );
            }
            else if ( $_obfuscate_qZkmBg��['noticeAtInterrupt'] == 2 )
            {
                $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "uid", "proxyUid", "dealUid" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `status` = '1' " );
                $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "by" )."[".$CNOA_SESSION->get( "TRUENAME" )."]".lang( "abort" );
                $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
                $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_0Ul8BBkt;
                $this->addNotice( "notice", array(
                    $_obfuscate_hTew0boWJESy['uid'],
                    $_obfuscate_Tx7M9W['proxyUid'],
                    $_obfuscate_Tx7M9W['dealUid']
                ), $_obfuscate_6RYLWQ��, "stop", 1 );
            }
            else if ( $_obfuscate_qZkmBg��['noticeAtInterrupt'] == 3 )
            {
                $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "by" )."[".$CNOA_SESSION->get( "TRUENAME" )."]".lang( "abort" );
                $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
                $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_0Ul8BBkt;
                $this->addNotice( "notice", array_unique( $_obfuscate_VZzSMXQx6Q�� ), $_obfuscate_6RYLWQ��, "stop", 2 );
            }
        }
        $_obfuscate_JG8GuY� = array( );
        $_obfuscate_JG8GuY�['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
        $_obfuscate_JG8GuY�['type'] = 10;
        $_obfuscate_JG8GuY�['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuY�['uid'] = $_obfuscate_7Ri3;
        foreach ( $_obfuscate_Tx7M9W as $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['status'] == 1 )
            {
                $_obfuscate_JG8GuY�['step'] = $_obfuscate_6A��['uStepId'];
                $_obfuscate_JG8GuY�['stepname'] = $_obfuscate_6A��['stepname'];
                $this->insertEvent( $_obfuscate_JG8GuY� );
            }
        }
        $CNOA_DB->db_update( array( "status" => 3 ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `status`=1" );
        $CNOA_DB->db_update( array( "status" => 6 ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        $CNOA_DB->db_update( array( "status" => 6 ), "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE `uFlowId` = ".$_obfuscate_TlvKhtsoOQ��." " );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3631, lang( "suspendFlow" )."[".$_obfuscate_qZkmBg��['flowName']."]".lang( "bianHao" )."[".$_obfuscate_qZkmBg��['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _delete( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowId", "flowName", "flowNumber", "attach", "sortId" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        if ( $_obfuscate_hTew0boWJESy['flowId'] == "0" || $_obfuscate_hTew0boWJESy['sortId'] == "0" )
        {
            if ( $_obfuscate_7Ri3 != 1 )
            {
                msg::callback( FALSE, lang( "flowRequireSAtoDel" ) );
            }
        }
        else if ( !$this->checkMgrPermit( $_obfuscate_TlvKhtsoOQ�� ) )
        {
            msg::callback( FALSE, lang( "notPermitMgrFlow" ) );
        }
        $_obfuscate_8NFcIjs_ = $CNOA_DB->db_getone( "*", $this->t_use_step_child_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        if ( $_obfuscate_8NFcIjs_ )
        {
            $_obfuscate_g_yvfhSllnxRQ�� = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_8NFcIjs_['puFlowId']."'" );
            msg::callback( FALSE, lang( "flowIsNumber" ).( " (".$_obfuscate_g_yvfhSllnxRQ��['flowNumber'].")" ).lang( "flowOfSubflow" )."，".lang( "notDel" )."<br />".lang( "ifDelPleaseDelPflow" ) );
        }
        $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "id" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        if ( !is_array( $_obfuscate_Tx7M9W ) )
        {
            $_obfuscate_Tx7M9W = array( );
        }
        foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_QwT4KwrB2w��[] = $_obfuscate_6A��['id'];
        }
        $this->deleteAllNotice( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_QwT4KwrB2w�� );
        $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "by" )."[".$CNOA_SESSION->get( "TRUENAME" )."]".lang( "del" );
        $_obfuscate_6RYLWQ��['href'] = "";
        $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_TlvKhtsoOQ��;
        $this->addNotice( "notice", $_obfuscate_hTew0boWJESy['uid'], $_obfuscate_6RYLWQ��, "delete", 0 );
        ( );
        $_obfuscate_2gg� = new fs( );
        $_obfuscate_WPvkSFEMg�� = json_decode( $_obfuscate_hTew0boWJESy['attach'], TRUE );
        if ( is_array( $_obfuscate_WPvkSFEMg�� ) && 0 < count( $_obfuscate_WPvkSFEMg�� ) )
        {
            $_obfuscate_2gg�->deleteFile( $_obfuscate_WPvkSFEMg�� );
        }
        $CNOA_DB->db_delete( "z_wf_t_".$_obfuscate_hTew0boWJESy['flowId'], "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        ( $_obfuscate_TlvKhtsoOQ�� );
        $_obfuscate_izJGxnXh9A�� = new wfCache( );
        $_obfuscate_mnGpwkqvvb1bA�� = $_obfuscate_izJGxnXh9A��->getFlowFields( );
        if ( !is_array( $_obfuscate_mnGpwkqvvb1bA�� ) )
        {
            $_obfuscate_mnGpwkqvvb1bA�� = array( );
        }
        foreach ( $_obfuscate_mnGpwkqvvb1bA�� as $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['otype'] == "detailtable" )
            {
                $CNOA_DB->db_delete( "z_wf_d_".$_obfuscate_hTew0boWJESy['flowId']."_".$_obfuscate_6A��['id'], "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
            }
        }
        ( $_obfuscate_TlvKhtsoOQ�� );
        $_obfuscate_e53ODz04JQ�� = new wfCache( );
        $_obfuscate_e53ODz04JQ��->deleteCache( );
        $CNOA_DB->db_delete( $this->t_use_step_huiqian, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $CNOA_DB->db_delete( $this->t_use_proxy_uflow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $CNOA_DB->db_delete( $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $CNOA_DB->db_delete( $this->t_use_proxy_uflow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $CNOA_DB->db_delete( $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $CNOA_DB->db_delete( $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $CNOA_DB->db_delete( $this->t_use_event, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $CNOA_DB->db_delete( "wf_u_convergence_deal", "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $_obfuscate_eOytqZnoPmMkuTA2A�� = $CNOA_DB->db_select( "*", $this->t_use_step_child_flow, "WHERE `puFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        if ( $_obfuscate_eOytqZnoPmMkuTA2A�� )
        {
            $_obfuscate_xHZmyK5cg�� = array( );
            foreach ( $_obfuscate_eOytqZnoPmMkuTA2A�� as $_obfuscate_6A�� )
            {
                $this->deleteNotice( "both", $_obfuscate_6A��['id'], "child" );
            }
        }
        $CNOA_DB->db_delete( $this->t_use_step_child_flow, "WHERE `puFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 3631, lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _ms_loadTemplateFile( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_GET, "tplSort", 0 );
        $_obfuscate_o6LA2yPirJIreFA� = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQ��}/doc.history.0.php" );
        $_obfuscate_U9NJHZRq6Jr7T_A� = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQ��}/xls.history.0.php" );
        debug::xprint( 2323232 );
        if ( $_obfuscate_pEvU7Kz2Yw�� == 1 || $_obfuscate_pEvU7Kz2Yw�� == 3 )
        {
            if ( file_exists( $_obfuscate_o6LA2yPirJIreFA� ) )
            {
                $_obfuscate_6hS1Rw�� = @file_get_contents( $_obfuscate_o6LA2yPirJIreFA� );
            }
        }
        else if ( file_exists( $_obfuscate_U9NJHZRq6Jr7T_A� ) )
        {
            $_obfuscate_6hS1Rw�� = @file_get_contents( $_obfuscate_U9NJHZRq6Jr7T_A� );
        }
        echo $_obfuscate_6hS1Rw��;
        exit( );
    }

    private function _ms_submitTemplateFile( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", 0 );
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_GET, "tplSort", 0 );
        $this->api_saveTemplateData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_pEvU7Kz2Yw�� );
        exit( );
    }

    private function _getManageStep( )
    {
        global $CNOA_DB;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "id", "uStepId", "stepname", "uid" ), $this->t_use_step, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ��." AND `status`=1 AND `uid`!=0" );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_Vwty => $_obfuscate_VgKtFeg� )
        {
            $_obfuscate_3gn_eQ�� = app::loadapp( "main", "user" )->api_getUserDataByUid( $_obfuscate_VgKtFeg�['uid'] );
            $_obfuscate_mPAjEGLn[$_obfuscate_Vwty]['stepname'] = $_obfuscate_VgKtFeg�['stepname']."-".$_obfuscate_3gn_eQ��['truename'];
            unset( $_obfuscate_VgKtFeg�['stepname']['uid'] );
        }
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function getStepId( $_obfuscate_0W8� )
    {
        global $CNOA_DB;
        $_obfuscate_6RYLWQ�� = $CNOA_DB->db_getone( "*", $this->t_use_step, "WHERE `id`=".$_obfuscate_0W8� );
        return $_obfuscate_6RYLWQ��;
    }

    private function _getFlowInfo( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId" );
        $_obfuscate_7qDAYo85aGA� = $CNOA_DB->db_getone( array( "nameRuleId", "flowType", "tplSort" ), $this->t_set_flow, "WHERE `flowId`=".$_obfuscate_F4AbnVRh );
        echo json_encode( $_obfuscate_7qDAYo85aGA� );
    }

}

?>
