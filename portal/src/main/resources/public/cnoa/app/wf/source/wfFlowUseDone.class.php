<?php

class wfFlowUseDone extends wfCommon
{

    public function run( )
    {
        $_obfuscate_M_5JJw�� = getpar( $_GET, "task", "loadPage" );
        switch ( $_obfuscate_M_5JJw�� )
        {
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "getJsonData" :
            $this->_getJsonData( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $_obfuscate_phKp89pDgwQ� = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $_obfuscate_phKp89pDgwQ� );
            exit( );
        case "loadFenfaFormData" :
            $this->_loadFenfaFormData( );
            break;
        case "fenFaFlow" :
            $this->_fenFaFlow( );
            break;
        case "callback" :
            $this->_callback( );
            break;
        case "cancelflow" :
            $this->_cancelflow( );
            break;
        case "exportFlow" :
            $this->exportFlow( );
            break;
        case "saveprint" :
            $this->saveprint( );
            break;
        case "printList" :
            $this->printList( );
            break;
        case "exportWord" :
            $this->exportWord( );
            break;
        case "exportFreeFlow" :
            $this->exportFreeFlow( );
            break;
        case "getFlowTypeData" :
            $_obfuscate_mPAjEGLn = app::loadapp( "wf", "flowUseTodo" )->api_getFlowTypeData( );
            echo json_encode( $_obfuscate_mPAjEGLn );
            exit( );
        case "getSortTree" :
            app::loadapp( "wf", "flowSetSort" )->api_getSortTree( "all" );
            break;
        case "getFlowListInSort" :
            $this->getFlowListInSort( );
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
        global $CNOA_CONTROLLER;
        global $CNOA_SESSION;
        $_obfuscate_vholQ�� = getpar( $_GET, "from", "" );
        if ( $_obfuscate_vholQ�� == "list" )
        {
            $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/done.htm";
        }
        else if ( $_obfuscate_vholQ�� == "showflow" )
        {
            $_obfuscate_TlvKhtsoOQ�� = ( integer )getpar( $_GET, "uFlowId" );
            $_obfuscate_0Ul8BBkt = ( integer )getpar( $_GET, "step" );
            $_obfuscate_3y0Y = "SELECT f.flowId, f.tplSort, f.flowType, u.status, u.allowCallback, u.allowCancel, u.uid FROM ".tname( $this->t_use_flow )." AS u RIGHT JOIN ".tname( $this->t_set_flow )." AS f ON u.flowId=f.flowId ".( "WHERE uFlowId=".$_obfuscate_TlvKhtsoOQ�� );
            $_obfuscate_hTew0boWJESy = $CNOA_DB->get_one( $_obfuscate_3y0Y );
            if ( !$_obfuscate_hTew0boWJESy )
            {
                msg::showerror( lang( "noThisFlow" ) );
            }
            $GLOBALS['GLOBALS']['app']['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
            $GLOBALS['GLOBALS']['app']['step'] = $_obfuscate_0Ul8BBkt;
            $GLOBALS['GLOBALS']['app']['flowId'] = $_obfuscate_hTew0boWJESy['flowId'];
            $GLOBALS['GLOBALS']['app']['status'] = $_obfuscate_hTew0boWJESy['status'];
            $GLOBALS['GLOBALS']['app']['wf']['type'] = "done";
            $GLOBALS['GLOBALS']['app']['wf']['allowCallback'] = $_obfuscate_hTew0boWJESy['allowCallback'];
            $GLOBALS['GLOBALS']['app']['wf']['allowCancel'] = $_obfuscate_hTew0boWJESy['allowCancel'];
            $GLOBALS['GLOBALS']['app']['wf']['tplSort'] = ( integer )$_obfuscate_hTew0boWJESy['tplSort'];
            $GLOBALS['GLOBALS']['app']['wf']['flowType'] = ( integer )$_obfuscate_hTew0boWJESy['flowType'];
            $GLOBALS['GLOBALS']['app']['wf']['owner'] = ( integer )getpar( $_GET, "owner" );
            if ( $CNOA_SESSION->get( "UID" ) == $_obfuscate_hTew0boWJESy['uid'] )
            {
                $GLOBALS['GLOBALS']['app']['wf']['isInitiator'] = ture;
            }
            else
            {
                $GLOBALS['GLOBALS']['app']['wf']['isInitiator'] = FALSE;
            }
            app::loadapp( "wf", "flowUseTodo" )->api_getRelFlow( );
            if ( !empty( $_obfuscate_0Ul8BBkt ) )
            {
                ( $_obfuscate_TlvKhtsoOQ�� );
                $_obfuscate_bIsJe6A� = new wfCache( );
                $_obfuscate_Tx7M9W = $_obfuscate_bIsJe6A�->getStepByStepId( $_obfuscate_0Ul8BBkt );
                $GLOBALS['GLOBALS']['app']['wf']['allowHqAttachAdd'] = $_obfuscate_Tx7M9W['allowHqAttachAdd'];
                $GLOBALS['GLOBALS']['app']['wf']['allowHqAttachView'] = $_obfuscate_Tx7M9W['allowHqAttachView'];
                $GLOBALS['GLOBALS']['app']['wf']['allowHqAttachEdit'] = $_obfuscate_Tx7M9W['allowHqAttachEdit'];
                $GLOBALS['GLOBALS']['app']['wf']['allowHqAttachDelete'] = $_obfuscate_Tx7M9W['allowHqAttachDelete'];
                $GLOBALS['GLOBALS']['app']['wf']['allowHqAttachDown'] = $_obfuscate_Tx7M9W['allowHqAttachDown'];
                if ( $_obfuscate_Tx7M9W['allowPrint'] == 1 )
                {
                    $GLOBALS['GLOBALS']['app']['wf']['allowPrint'] = 1;
                }
                else
                {
                    $GLOBALS['GLOBALS']['app']['wf']['allowPrint'] = 0;
                }
            }
            $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/showflow.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQ�� );
        exit( );
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_mV9HBLY� = ( integer )getpar( $_POST, "start" );
        $_obfuscate_F4AbnVRh = ( integer )getpar( $_POST, "flowId" );
        $_obfuscate_mSWYi45v = ( integer )getpar( $_POST, "faqiId" );
        $_obfuscate_v1GprsIz = ( integer )getpar( $_POST, "flowType" );
        $_obfuscate_wWP8H_hVD6l = getpar( $_POST, "flowTitle", "" );
        $_obfuscate_Bj089LCE6qL = getpar( $_POST, "storeType", "running" );
        $_obfuscate_qx37NM� = getpar( $_POST, "stime", "" );
        $_obfuscate_KWKBW4� = getpar( $_POST, "etime", "" );
        $_obfuscate_IRFhnYw� = array( );
        $_obfuscate_H9Mbnw�� = "";
        $_obfuscate_xvYeh9I� = getpagesize( "wf_flow_use_done_getJsonData" );
        switch ( $_obfuscate_Bj089LCE6qL )
        {
        case "running" :
            $_obfuscate_IRFhnYw�[] = "f.status=1";
            $_obfuscate_H9Mbnw�� = "ORDER BY `f`.`level` DESC,`posttime` DESC";
            break;
        case "done" :
            $_obfuscate_IRFhnYw�[] = "f.status IN (2, 4, 6)";
            $_obfuscate_H9Mbnw�� = "ORDER BY `endtime` DESC";
            break;
        case "all" :
            $_obfuscate_IRFhnYw�[] = "f.status IN (1, 2, 4, 6)";
            $_obfuscate_H9Mbnw�� = "ORDER BY `f`.`level` DESC,`posttime` DESC";
            break;
        case "returned" :
            $_obfuscate_IRFhnYw�[] = "f.status IN (1, 2, 4, 6)";
            $_obfuscate_H9Mbnw�� = "ORDER BY `f`.`level` DESC,`posttime` DESC";
            break;
        case "mypublish" :
            $_obfuscate_IRFhnYw�[] = "f.status IN (1, 2, 4, 6)";
            $_obfuscate_mSWYi45v = 0;
            $_obfuscate_IRFhnYw�[] = "f.uid IN (".$_obfuscate_7Ri3.")";
            $_obfuscate_H9Mbnw�� = "ORDER BY `f`.`level` DESC,`posttime` DESC";
            break;
        case "mymanagement" :
            $_obfuscate_IRFhnYw�[] = "f.status IN (1, 2, 4, 6)";
            $_obfuscate_IRFhnYw�[] = "f.uid NOT IN (".$_obfuscate_7Ri3.")";
            $_obfuscate_H9Mbnw�� = "ORDER BY `f`.`level` DESC,`posttime` DESC";
        }
        if ( !empty( $_obfuscate_v1GprsIz ) )
        {
            $_obfuscate_IRFhnYw�[] = "f.sortId=".$_obfuscate_v1GprsIz;
        }
        if ( !empty( $_obfuscate_mSWYi45v ) || filter_var( $_obfuscate_mSWYi45v, FILTER_VALIDATE_REGEXP, array(
            "options" => array( "regexp" => "/^(\\d+,)*\\d+\$/" )
        ) ) )
        {
            $_obfuscate_IRFhnYw�[] = "f.uid IN (".$_obfuscate_mSWYi45v.")";
        }
        if ( !empty( $_obfuscate_qx37NM� ) )
        {
            $_obfuscate_qx37NM� = strtotime( $_obfuscate_qx37NM�." 00:00:00" );
            $_obfuscate_IRFhnYw�[] = "f.posttime > '".$_obfuscate_qx37NM�."'";
        }
        if ( !empty( $_obfuscate_KWKBW4� ) )
        {
            $_obfuscate_KWKBW4� = strtotime( $_obfuscate_KWKBW4�." 23:59:59" );
            $_obfuscate_IRFhnYw�[] = "f.posttime < '".$_obfuscate_KWKBW4�."'";
        }
        if ( !empty( $_obfuscate_wWP8H_hVD6l ) )
        {
            $_obfuscate_IRFhnYw�[] = "(f.flowNumber LIKE '%".$_obfuscate_wWP8H_hVD6l."%' OR f.flowName LIKE '%{$_obfuscate_wWP8H_hVD6l}%')";
        }
        $_obfuscate_dDHiUSY4Qo� = array( );
        $_obfuscate_zkNKvw�� = "SELECT uFlowId FROM ".tname( $this->t_use_step ).( " WHERE (`status`=2 OR `status`=4) AND `dealUid`!=0 AND (`uid`='".$_obfuscate_7Ri3."' OR `proxyUid`='{$_obfuscate_7Ri3}')" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_zkNKvw�� );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_dDHiUSY4Qo�[] = $_obfuscate_gkt['uFlowId'];
        }
        $_obfuscate_2bhmuA�� = "SELECT h.uFlowId FROM ".tname( $this->t_use_step_huiqian ).( " AS `h` WHERE `h`.`touid`='".$_obfuscate_7Ri3."' AND `h`.`status`=1" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_2bhmuA�� );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_dDHiUSY4Qo�[] = $_obfuscate_gkt['uFlowId'];
        }
        unset( $_obfuscate_xs33Yt_k );
        unset( $_obfuscate_zkNKvw�� );
        unset( $_obfuscate_2bhmuA�� );
        $_obfuscate_dDHiUSY4Qo� = array_unique( $_obfuscate_dDHiUSY4Qo� );
        if ( count( $_obfuscate_dDHiUSY4Qo� ) <= 0 )
        {
            ( );
            $_obfuscate_NlQ� = new dataStore( );
            echo $_obfuscate_NlQ�->makeJsonData( );
            exit( );
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
            if ( empty( $_obfuscate_dcwitxb ) && empty( $_obfuscate_F4AbnVRh ) )
            {
                ( );
                $_obfuscate_NlQ� = new dataStore( );
                echo $_obfuscate_NlQ�->makeJsonData( );
                exit( );
            }
            $_obfuscate_IRFhnYw�[] = $_obfuscate_dcwitxb;
            $_obfuscate_FC6WZeq5G1D5_dA� = "FROM ".tname( self::TABLE_PRE_DATA.$_obfuscate_F4AbnVRh )." AS d LEFT JOIN ".tname( $this->t_use_flow )." AS f ON f.uFlowId=d.uFlowId ";
        }
        else
        {
            $_obfuscate_FC6WZeq5G1D5_dA� = "FROM ".tname( $this->t_use_flow )." AS `f` ";
        }
        $_obfuscate_IRFhnYw� = array_filter( $_obfuscate_IRFhnYw� );
        $_obfuscate_IRFhnYw� = implode( " AND ", $_obfuscate_IRFhnYw� );
        if ( $_obfuscate_Bj089LCE6qL == "returned" )
        {
            $_obfuscate_dDHiUSY4Qo� = array( );
            $_obfuscate_2SArQQEHoHpx = $CNOA_DB->db_select( "*", $this->t_use_event, "WHERE uid='".$_obfuscate_7Ri3."' AND type='4'" );
            foreach ( $_obfuscate_2SArQQEHoHpx as $_obfuscate_6A�� )
            {
                $_obfuscate_dDHiUSY4Qo�[] = $_obfuscate_6A��['uFlowId'];
            }
            $_obfuscate_dDHiUSY4Qo� = implode( ",", $_obfuscate_dDHiUSY4Qo� );
        }
        else
        {
            $_obfuscate_dDHiUSY4Qo� = implode( ",", $_obfuscate_dDHiUSY4Qo� );
        }
        $_obfuscate_pBjeuVXL = "SELECT COUNT(DISTINCT f.uFlowId) AS num ".$_obfuscate_FC6WZeq5G1D5_dA�.( "WHERE (f.uid='".$_obfuscate_7Ri3."' OR (f.uFlowId IN ({$_obfuscate_dDHiUSY4Qo�}))) AND {$_obfuscate_IRFhnYw�}" );
        $_obfuscate_IAGEj482gQ�� = $CNOA_DB->get_one( $_obfuscate_pBjeuVXL );
        $_obfuscate_j34pdsEPbI� = ( integer )$_obfuscate_IAGEj482gQ��['num'];
        if ( $_obfuscate_j34pdsEPbI� <= 0 )
        {
            ( );
            $_obfuscate_NlQ� = new dataStore( );
            echo $_obfuscate_NlQ�->makeJsonData( );
            exit( );
        }
        if ( $_obfuscate_Bj089LCE6qL == "returned" )
        {
            $_obfuscate_3y0Y = "SELECT DISTINCT f.uFlowId, f.* ".$_obfuscate_FC6WZeq5G1D5_dA�.( "WHERE f.uFlowId IN (".$_obfuscate_dDHiUSY4Qo�.") AND {$_obfuscate_IRFhnYw�} {$_obfuscate_H9Mbnw��} LIMIT {$_obfuscate_mV9HBLY�}, {$_obfuscate_xvYeh9I�} " );
        }
        else
        {
            $_obfuscate_3y0Y = "SELECT DISTINCT f.uFlowId, f.* ".$_obfuscate_FC6WZeq5G1D5_dA�.( "WHERE (f.uid='".$_obfuscate_7Ri3."' OR (f.uFlowId IN ({$_obfuscate_dDHiUSY4Qo�}))) AND {$_obfuscate_IRFhnYw�} {$_obfuscate_H9Mbnw��} LIMIT {$_obfuscate_mV9HBLY�}, {$_obfuscate_xvYeh9I�} " );
        }
        $_obfuscate_mPAjEGLn = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_8Bnz38wN01c� = $_obfuscate_MujU5qIjWA�� = $_obfuscate_uWfP0Bouw�� = $_obfuscate_ElR49KmUBtFSUBo� = $_obfuscate_mKAUcVABjS7vqDZ = array( );
        while ( $_obfuscate_LQ8UKg�� = $CNOA_DB->get_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_uWfP0Bouw��[] = $_obfuscate_LQ8UKg��['flowId'];
            $_obfuscate_MujU5qIjWA��[] = $_obfuscate_LQ8UKg��['sortId'];
            $_obfuscate_8Bnz38wN01c�[] = $_obfuscate_LQ8UKg��;
            $_obfuscate_ElR49KmUBtFSUBo�[] = $_obfuscate_LQ8UKg��['uFlowId'];
        }
        $_obfuscate_ElR49KmUBtFSUBo� = implode( ",", $_obfuscate_ElR49KmUBtFSUBo� );
        $_obfuscate_dS1NUvKPGMg� = $CNOA_DB->db_select( "*", $this->t_use_step, "WHERE `uFlowId` IN(".$_obfuscate_ElR49KmUBtFSUBo�.") AND `proxyUid` !=0" );
        if ( !empty( $_obfuscate_dS1NUvKPGMg� ) )
        {
            foreach ( $_obfuscate_dS1NUvKPGMg� as $_obfuscate_6A�� )
            {
                if ( $_obfuscate_6A��['dealUid'] == $_obfuscate_6A��['proxyUid'] )
                {
                    $_obfuscate_mKAUcVABjS7vqDZ[] = $_obfuscate_6A��['uFlowId'];
                }
            }
        }
        $_obfuscate_RopcQP_w = $this->api_getSortByIds( $_obfuscate_MujU5qIjWA�� );
        $_obfuscate_5ZWvow6yfBMjKt0EvsXWPg�� = $CNOA_DB->db_select( array( "name", "flowId", "flowType", "tplSort", "status" ), $this->t_set_flow, "WHERE `flowId` IN (".implode( ",", $_obfuscate_uWfP0Bouw�� ).")" );
        if ( !is_array( $_obfuscate_5ZWvow6yfBMjKt0EvsXWPg�� ) )
        {
            $_obfuscate_5ZWvow6yfBMjKt0EvsXWPg�� = array( );
        }
        $_obfuscate_U6s1cCExb6szBojQ = $_obfuscate_MgWE1yPRnMBaRa4� = $_obfuscate_xhCy8yogiWJQ4D00o7G2 = $_obfuscate_dBcIDGSEkvbn9ZCU3A�� = array( );
        foreach ( $_obfuscate_5ZWvow6yfBMjKt0EvsXWPg�� as $_obfuscate_1U6d )
        {
            $_obfuscate_U6s1cCExb6szBojQ[$_obfuscate_1U6d['flowId']] = $_obfuscate_1U6d['name'];
            $_obfuscate_MgWE1yPRnMBaRa4�[$_obfuscate_1U6d['flowId']] = $_obfuscate_1U6d['tplSort'];
            $_obfuscate_xhCy8yogiWJQ4D00o7G2[$_obfuscate_1U6d['flowId']] = $_obfuscate_1U6d['flowType'];
            $_obfuscate_dBcIDGSEkvbn9ZCU3A��[$_obfuscate_1U6d['flowId']] = $_obfuscate_1U6d['status'];
        }
        $_obfuscate_x1tcCgpyXQ�� = $CNOA_DB->db_select( array( "uFlowId", "uStepId" ), $this->t_use_step, "WHERE `uFlowId` IN (".$_obfuscate_dDHiUSY4Qo�.") AND `uid`={$_obfuscate_7Ri3} AND (`status`=2 OR `status`=4)" );
        $_obfuscate_DugEvweRydCf = array( );
        if ( !is_array( $_obfuscate_x1tcCgpyXQ�� ) )
        {
            $_obfuscate_x1tcCgpyXQ�� = array( );
        }
        foreach ( $_obfuscate_x1tcCgpyXQ�� as $_obfuscate_6A�� )
        {
            if ( isset( $_obfuscate_DugEvweRydCf[$_obfuscate_6A��['uFlowId']] ) )
            {
                $_obfuscate_6A��['uStepId'] = max( $_obfuscate_DugEvweRydCf[$_obfuscate_6A��['uFlowId']], $_obfuscate_6A��['uStepId'] );
            }
            $_obfuscate_DugEvweRydCf[$_obfuscate_6A��['uFlowId']] = $_obfuscate_6A��['uStepId'];
        }
        unset( $_obfuscate_x1tcCgpyXQ�� );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "uFlowId", "uid", "proxyUid" ), $this->t_use_step, "WHERE `uFlowId` IN (".$_obfuscate_dDHiUSY4Qo�.") AND `status`=1" );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_18W8kI0F6OJoU7M� = array( );
        $_obfuscate__eqrEQ�� = array( );
        $_obfuscate__Wi6396IheA� = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['uid'] != 0 )
            {
                $_obfuscate__eqrEQ��[] = $_obfuscate_6A��['uid'];
                $_obfuscate_18W8kI0F6OJoU7M�[$_obfuscate_6A��['uFlowId']][] = $_obfuscate_6A��['uid'];
            }
            if ( $_obfuscate_6A��['proxyUid'] != 0 )
            {
                $_obfuscate__eqrEQ��[] = $_obfuscate_6A��['proxyUid'];
                $_obfuscate_18W8kI0F6OJoU7M�[$_obfuscate_6A��['uFlowId']][] = $_obfuscate_6A��['proxyUid'];
            }
        }
        if ( 0 < count( $_obfuscate__eqrEQ�� ) )
        {
            $_obfuscate__eqrEQ�� = array_diff( $_obfuscate__eqrEQ��, array( "" ) );
            $_obfuscate__Wi6396IheA� = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate__eqrEQ�� );
        }
        $_obfuscate_IVEStjJMAbn73TD = array( );
        foreach ( $_obfuscate_18W8kI0F6OJoU7M� as $_obfuscate_Vwty => $_obfuscate_1jUa )
        {
            $_obfuscate_NS44QYk� = array( );
            foreach ( $_obfuscate_1jUa as $_obfuscate_0W8� )
            {
                $_obfuscate_NS44QYk�[] = $_obfuscate__Wi6396IheA�[$_obfuscate_0W8�];
            }
            $_obfuscate_IVEStjJMAbn73TD[$_obfuscate_Vwty] = implode( ",", $_obfuscate_NS44QYk� );
        }
        unset( $_obfuscate_18W8kI0F6OJoU7M� );
        unset( $_obfuscate__eqrEQ�� );
        unset( $_obfuscate__Wi6396IheA� );
        if ( !is_array( $_obfuscate_8Bnz38wN01c� ) )
        {
            $_obfuscate_8Bnz38wN01c� = array( );
        }
        foreach ( $_obfuscate_8Bnz38wN01c� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['sname'] = $_obfuscate_RopcQP_w[$_obfuscate_6A��['sortId']]['name'];
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['level'] = $this->f_level[$_obfuscate_6A��['level']];
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['statusText'] = $this->f_status[$_obfuscate_6A��['status']];
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['posttime'] = formatdate( $_obfuscate_6A��['posttime'], "Y-m-d H:i" );
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['endtime'] = $_obfuscate_6A��['endtime'] == 0 ? "----" : formatdate( $_obfuscate_6A��['endtime'], "Y-m-d H:i" );
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['flowSetName'] = $_obfuscate_U6s1cCExb6szBojQ[$_obfuscate_6A��['flowId']];
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['againStatus'] = $_obfuscate_dBcIDGSEkvbn9ZCU3A��[$_obfuscate_6A��['flowId']];
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['tplSort'] = $_obfuscate_MgWE1yPRnMBaRa4�[$_obfuscate_6A��['flowId']];
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['flowType'] = $_obfuscate_xhCy8yogiWJQ4D00o7G2[$_obfuscate_6A��['flowId']];
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['step'] = $_obfuscate_DugEvweRydCf[$_obfuscate_6A��['uFlowId']];
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['curUname'] = $_obfuscate_IVEStjJMAbn73TD[$_obfuscate_6A��['uFlowId']];
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['allowCallback'] = 0;
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['allowCancel'] = 0;
            if ( $_obfuscate_6A��['uid'] == $_obfuscate_7Ri3 )
            {
                $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['owner'] = 1;
                if ( $_obfuscate_6A��['status'] == 1 )
                {
                    $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['allowCallback'] = $_obfuscate_6A��['allowCallback'];
                    $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['allowCancel'] = $_obfuscate_6A��['allowCancel'];
                }
            }
            else
            {
                $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['owner'] = 0;
            }
            if ( in_array( $_obfuscate_6A��['uFlowId'], $_obfuscate_mKAUcVABjS7vqDZ ) )
            {
                $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['proxyText'] = "委托";
            }
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_8Bnz38wN01c�;
        $_obfuscate_SUjPN94Er7yI->total = $_obfuscate_j34pdsEPbI�;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _loadFenfaFormData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        if ( empty( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        else
        {
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_5ZL98vE� .= $_obfuscate_6A��['touid'].",";
                $_obfuscate_e_N_6txY .= app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_6A��['touid'] ).",";
            }
            $_obfuscate_5ZL98vE� = substr( $_obfuscate_5ZL98vE�, 0, -1 );
            $_obfuscate_e_N_6txY = substr( $_obfuscate_e_N_6txY, 0, -1 );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = array(
            "touid" => $_obfuscate_5ZL98vE�,
            "toName" => $_obfuscate_e_N_6txY
        );
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _fenFaFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate__eqrEQ�� = getpar( $_POST, "touid", "" );
        $_obfuscate_PVLK5jra = explode( ",", $_obfuscate__eqrEQ�� );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        if ( 0 < $_obfuscate_Ybai )
        {
            $CNOA_DB->db_delete( $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        }
        $_obfuscate_7qDAYo85aGA� = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_rLpOghzteElp = array( );
        foreach ( $_obfuscate_PVLK5jra as $_obfuscate_6A�� )
        {
            $_obfuscate_rLpOghzteElp['touid'] = $_obfuscate_6A��;
            $_obfuscate_rLpOghzteElp['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
            $_obfuscate_rLpOghzteElp['fenfauid'] = $_obfuscate_7Ri3;
            $CNOA_DB->db_insert( $_obfuscate_rLpOghzteElp, $this->t_use_fenfa );
            $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_7qDAYo85aGA�['flowName']."]编号[".$_obfuscate_7qDAYo85aGA�['flowNumber']."]分发给你阅读。";
            $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_7qDAYo85aGA�['flowId']}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
            $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_7qDAYo85aGA�['flowId'];
            $this->addNotice( "notice", $_obfuscate_rLpOghzteElp['touid'], $_obfuscate_6RYLWQ��, "fenfa" );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _callback( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_JG8GuY� = array( );
        $_obfuscate_JG8GuY�['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
        $_obfuscate_JG8GuY�['type'] = 7;
        $_obfuscate_JG8GuY�['step'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_JG8GuY�['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuY�['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuY�['say'] = "";
        $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "stepname", "dealUid", "proxyUid" ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId`='{$_obfuscate_0Ul8BBkt}'" );
        $_obfuscate_JG8GuY�['stepname'] = $_obfuscate_Tx7M9W['stepName'];
        $this->insertEvent( $_obfuscate_JG8GuY� );
        $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "id", "uid", "proxyUid", "uStepId" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        if ( !is_array( $_obfuscate_Tx7M9W ) )
        {
            $_obfuscate_Tx7M9W = array( );
        }
        $_obfuscate_QwT4KwrB2w�� = array( );
        foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_QwT4KwrB2w��[] = $_obfuscate_6A��['id'];
        }
        $this->deleteAllNotice( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_QwT4KwrB2w�� );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        $_obfuscate_qZkmBg�� = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( $_obfuscate_qZkmBg��['noticeCallback'] != 4 )
        {
            if ( $_obfuscate_qZkmBg��['noticeCallback'] == 1 )
            {
                $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "recalles" );
                $_obfuscate_6RYLWQ��['href'] = "";
                $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "uid", "id", "dealUid" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `stepType` = '1' " );
                $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_Tx7M9W['id'];
                $this->addNotice( "notice", $_obfuscate_Tx7M9W['dealUid'], $_obfuscate_6RYLWQ��, "callback", 1 );
            }
            else if ( $_obfuscate_qZkmBg��['noticeCallback'] == 2 )
            {
                $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "recalles" );
                $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "uid", "id", "dealUid" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId` = '{$_obfuscate_0Ul8BBkt}' " );
                $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
                $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_Tx7M9W['id'];
                $this->addNotice( "notice", $_obfuscate_Tx7M9W['dealUid'], $_obfuscate_6RYLWQ��, "callback", 2 );
            }
            else
            {
                $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "recalles" );
                $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
                $_obfuscate_SIUSR4F6 = $CNOA_DB->db_select( "*", $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `status`=2" );
                if ( !is_array( $_obfuscate_SIUSR4F6 ) )
                {
                    $_obfuscate_SIUSR4F6 = array( );
                }
                foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_6A�� )
                {
                    $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_6A��['id'];
                    $this->addNotice( "notice", $_obfuscate_6A��['dealUid'], $_obfuscate_6RYLWQ��, "callback", 3 );
                }
            }
        }
        $_obfuscate_Le1NAQIeVbEkanE� = $CNOA_DB->db_select( array( "id" ), "wf_u_huiqian", "WHERE `uFlowId`=4989" );
        if ( !is_array( $_obfuscate_Le1NAQIeVbEkanE� ) )
        {
            $_obfuscate_Le1NAQIeVbEkanE� = array( );
        }
        if ( $_obfuscate_Le1NAQIeVbEkanE� )
        {
            foreach ( $_obfuscate_Le1NAQIeVbEkanE� as $_obfuscate_6A�� )
            {
                notice::deletenotice( $_obfuscate_6A��['id'], $_obfuscate_6A��['id'], $this->t_use_step_huiqian, "write" );
            }
        }
        $CNOA_DB->db_delete( $this->t_use_step_huiqian, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ�� );
        $_obfuscate_7I1iFsUPtGtFc0eFtkE� = "1";
        $CNOA_DB->db_update( array(
            "status" => 0,
            "edittime" => $GLOBALS['CNOA_TIMESTAMP'],
            "callBackStatus" => $_obfuscate_7I1iFsUPtGtFc0eFtkE�
        ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        $CNOA_DB->db_delete( $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        $_obfuscate_BxXST4lMTfRblygB = array( );
        $_obfuscate_BxXST4lMTfRblygB['status'] = 0;
        $_obfuscate_8WrXMY5WMEIjCNkjA�� = array( );
        $_obfuscate_Y5NNIxXl7FiX = $CNOA_DB->db_select( array( "fieldId" ), $this->t_set_step_fields, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' AND `stepId` = 2 AND `write` = 1 " );
        if ( $_obfuscate_Y5NNIxXl7FiX )
        {
            foreach ( $_obfuscate_Y5NNIxXl7FiX as $_obfuscate_6A�� )
            {
                $_obfuscate_8WrXMY5WMEIjCNkjA��[] = $_obfuscate_6A��['fieldId'];
            }
        }
        $_obfuscate_mnGpwkqvvb1bA�� = $CNOA_DB->db_select( array( "id", "dvalue" ), $this->t_set_field, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' " );
        if ( $_obfuscate_mnGpwkqvvb1bA�� )
        {
            foreach ( $_obfuscate_mnGpwkqvvb1bA�� as $_obfuscate_6A�� )
            {
                if ( !in_array( $_obfuscate_6A��['id'], $_obfuscate_8WrXMY5WMEIjCNkjA�� ) )
                {
                    $_obfuscate_6A��['dvalue'] = $_obfuscate_6A��['dvalue'] == "default" ? "" : $_obfuscate_6A��['dvalue'];
                    $_obfuscate_BxXST4lMTfRblygB["T_".$_obfuscate_6A��['id']] = $_obfuscate_6A��['dvalue'];
                }
            }
        }
        $CNOA_DB->db_update( $_obfuscate_BxXST4lMTfRblygB, "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        $_obfuscate_eOytqZnoPmMkuTA2A�� = $CNOA_DB->db_select( "*", $this->t_use_step_child_flow, "WHERE `status` IN (0,1) AND `puFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        if ( $_obfuscate_eOytqZnoPmMkuTA2A�� )
        {
            $_obfuscate_xHZmyK5cg�� = array( );
            foreach ( $_obfuscate_eOytqZnoPmMkuTA2A�� as $_obfuscate_6A�� )
            {
                $this->deleteNotice( "both", $_obfuscate_6A��['id'], "child" );
            }
        }
        $CNOA_DB->db_delete( $this->t_use_step_child_flow, "WHERE `status` IN (0,1) AND `puFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _cancelflow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `uid` = '{$_obfuscate_7Ri3}' " );
        if ( empty( $_obfuscate_Ybai ) )
        {
            msg::callback( FALSE, lang( "youNotSponsor" ) );
        }
        $this->api_cancelFlow( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt );
        msg::callback( TRUE, lang( "successopt" ) );
    }

}

?>
