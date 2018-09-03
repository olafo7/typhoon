<?php

class wfFlowUseDone extends wfCommon
{

    public function run( )
    {
        $_obfuscate_M_5JJwÿÿ = getpar( $_GET, "task", "loadPage" );
        switch ( $_obfuscate_M_5JJwÿÿ )
        {
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "getJsonData" :
            $this->_getJsonData( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $_obfuscate_phKp89pDgwQÿ = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $_obfuscate_phKp89pDgwQÿ );
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
        global $CNOA_CONTROLLER;
        global $CNOA_SESSION;
        $_obfuscate_vholQÿÿ = getpar( $_GET, "from", "" );
        if ( $_obfuscate_vholQÿÿ == "list" )
        {
            $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/done.htm";
        }
        else if ( $_obfuscate_vholQÿÿ == "showflow" )
        {
            $_obfuscate_TlvKhtsoOQÿÿ = ( integer )getpar( $_GET, "uFlowId" );
            $_obfuscate_0Ul8BBkt = ( integer )getpar( $_GET, "step" );
            $_obfuscate_3y0Y = "SELECT f.flowId, f.tplSort, f.flowType, u.status, u.allowCallback, u.allowCancel, u.uid FROM ".tname( $this->t_use_flow )." AS u RIGHT JOIN ".tname( $this->t_set_flow )." AS f ON u.flowId=f.flowId ".( "WHERE uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ );
            $_obfuscate_hTew0boWJESy = $CNOA_DB->get_one( $_obfuscate_3y0Y );
            if ( !$_obfuscate_hTew0boWJESy )
            {
                msg::showerror( lang( "noThisFlow" ) );
            }
            $GLOBALS['GLOBALS']['app']['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
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
                ( $_obfuscate_TlvKhtsoOQÿÿ );
                $_obfuscate_bIsJe6Aÿ = new wfCache( );
                $_obfuscate_Tx7M9W = $_obfuscate_bIsJe6Aÿ->getStepByStepId( $_obfuscate_0Ul8BBkt );
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
            $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/showflow.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQÿÿ );
        exit( );
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_mV9HBLYÿ = ( integer )getpar( $_POST, "start" );
        $_obfuscate_F4AbnVRh = ( integer )getpar( $_POST, "flowId" );
        $_obfuscate_mSWYi45v = ( integer )getpar( $_POST, "faqiId" );
        $_obfuscate_v1GprsIz = ( integer )getpar( $_POST, "flowType" );
        $_obfuscate_wWP8H_hVD6l = getpar( $_POST, "flowTitle", "" );
        $_obfuscate_Bj089LCE6qL = getpar( $_POST, "storeType", "running" );
        $_obfuscate_qx37NMÿ = getpar( $_POST, "stime", "" );
        $_obfuscate_KWKBW4ÿ = getpar( $_POST, "etime", "" );
        $_obfuscate_IRFhnYwÿ = array( );
        $_obfuscate_H9Mbnwÿÿ = "";
        $_obfuscate_xvYeh9Iÿ = getpagesize( "wf_flow_use_done_getJsonData" );
        switch ( $_obfuscate_Bj089LCE6qL )
        {
        case "running" :
            $_obfuscate_IRFhnYwÿ[] = "f.status=1";
            $_obfuscate_H9Mbnwÿÿ = "ORDER BY `f`.`level` DESC,`posttime` DESC";
            break;
        case "done" :
            $_obfuscate_IRFhnYwÿ[] = "f.status IN (2, 4, 6)";
            $_obfuscate_H9Mbnwÿÿ = "ORDER BY `endtime` DESC";
            break;
        case "all" :
            $_obfuscate_IRFhnYwÿ[] = "f.status IN (1, 2, 4, 6)";
            $_obfuscate_H9Mbnwÿÿ = "ORDER BY `f`.`level` DESC,`posttime` DESC";
            break;
        case "returned" :
            $_obfuscate_IRFhnYwÿ[] = "f.status IN (1, 2, 4, 6)";
            $_obfuscate_H9Mbnwÿÿ = "ORDER BY `f`.`level` DESC,`posttime` DESC";
            break;
        case "mypublish" :
            $_obfuscate_IRFhnYwÿ[] = "f.status IN (1, 2, 4, 6)";
            $_obfuscate_mSWYi45v = 0;
            $_obfuscate_IRFhnYwÿ[] = "f.uid IN (".$_obfuscate_7Ri3.")";
            $_obfuscate_H9Mbnwÿÿ = "ORDER BY `f`.`level` DESC,`posttime` DESC";
            break;
        case "mymanagement" :
            $_obfuscate_IRFhnYwÿ[] = "f.status IN (1, 2, 4, 6)";
            $_obfuscate_IRFhnYwÿ[] = "f.uid NOT IN (".$_obfuscate_7Ri3.")";
            $_obfuscate_H9Mbnwÿÿ = "ORDER BY `f`.`level` DESC,`posttime` DESC";
        }
        if ( !empty( $_obfuscate_v1GprsIz ) )
        {
            $_obfuscate_IRFhnYwÿ[] = "f.sortId=".$_obfuscate_v1GprsIz;
        }
        if ( !empty( $_obfuscate_mSWYi45v ) || filter_var( $_obfuscate_mSWYi45v, FILTER_VALIDATE_REGEXP, array(
            "options" => array( "regexp" => "/^(\\d+,)*\\d+\$/" )
        ) ) )
        {
            $_obfuscate_IRFhnYwÿ[] = "f.uid IN (".$_obfuscate_mSWYi45v.")";
        }
        if ( !empty( $_obfuscate_qx37NMÿ ) )
        {
            $_obfuscate_qx37NMÿ = strtotime( $_obfuscate_qx37NMÿ." 00:00:00" );
            $_obfuscate_IRFhnYwÿ[] = "f.posttime > '".$_obfuscate_qx37NMÿ."'";
        }
        if ( !empty( $_obfuscate_KWKBW4ÿ ) )
        {
            $_obfuscate_KWKBW4ÿ = strtotime( $_obfuscate_KWKBW4ÿ." 23:59:59" );
            $_obfuscate_IRFhnYwÿ[] = "f.posttime < '".$_obfuscate_KWKBW4ÿ."'";
        }
        if ( !empty( $_obfuscate_wWP8H_hVD6l ) )
        {
            $_obfuscate_IRFhnYwÿ[] = "(f.flowNumber LIKE '%".$_obfuscate_wWP8H_hVD6l."%' OR f.flowName LIKE '%{$_obfuscate_wWP8H_hVD6l}%')";
        }
        $_obfuscate_dDHiUSY4Qoÿ = array( );
        $_obfuscate_zkNKvwÿÿ = "SELECT uFlowId FROM ".tname( $this->t_use_step ).( " WHERE (`status`=2 OR `status`=4) AND `dealUid`!=0 AND (`uid`='".$_obfuscate_7Ri3."' OR `proxyUid`='{$_obfuscate_7Ri3}')" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_zkNKvwÿÿ );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_dDHiUSY4Qoÿ[] = $_obfuscate_gkt['uFlowId'];
        }
        $_obfuscate_2bhmuAÿÿ = "SELECT h.uFlowId FROM ".tname( $this->t_use_step_huiqian ).( " AS `h` WHERE `h`.`touid`='".$_obfuscate_7Ri3."' AND `h`.`status`=1" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_2bhmuAÿÿ );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_dDHiUSY4Qoÿ[] = $_obfuscate_gkt['uFlowId'];
        }
        unset( $_obfuscate_xs33Yt_k );
        unset( $_obfuscate_zkNKvwÿÿ );
        unset( $_obfuscate_2bhmuAÿÿ );
        $_obfuscate_dDHiUSY4Qoÿ = array_unique( $_obfuscate_dDHiUSY4Qoÿ );
        if ( count( $_obfuscate_dDHiUSY4Qoÿ ) <= 0 )
        {
            ( );
            $_obfuscate_NlQÿ = new dataStore( );
            echo $_obfuscate_NlQÿ->makeJsonData( );
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
                $_obfuscate_NlQÿ = new dataStore( );
                echo $_obfuscate_NlQÿ->makeJsonData( );
                exit( );
            }
            $_obfuscate_IRFhnYwÿ[] = $_obfuscate_dcwitxb;
            $_obfuscate_FC6WZeq5G1D5_dAÿ = "FROM ".tname( self::TABLE_PRE_DATA.$_obfuscate_F4AbnVRh )." AS d LEFT JOIN ".tname( $this->t_use_flow )." AS f ON f.uFlowId=d.uFlowId ";
        }
        else
        {
            $_obfuscate_FC6WZeq5G1D5_dAÿ = "FROM ".tname( $this->t_use_flow )." AS `f` ";
        }
        $_obfuscate_IRFhnYwÿ = array_filter( $_obfuscate_IRFhnYwÿ );
        $_obfuscate_IRFhnYwÿ = implode( " AND ", $_obfuscate_IRFhnYwÿ );
        if ( $_obfuscate_Bj089LCE6qL == "returned" )
        {
            $_obfuscate_dDHiUSY4Qoÿ = array( );
            $_obfuscate_2SArQQEHoHpx = $CNOA_DB->db_select( "*", $this->t_use_event, "WHERE uid='".$_obfuscate_7Ri3."' AND type='4'" );
            foreach ( $_obfuscate_2SArQQEHoHpx as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_dDHiUSY4Qoÿ[] = $_obfuscate_6Aÿÿ['uFlowId'];
            }
            $_obfuscate_dDHiUSY4Qoÿ = implode( ",", $_obfuscate_dDHiUSY4Qoÿ );
        }
        else
        {
            $_obfuscate_dDHiUSY4Qoÿ = implode( ",", $_obfuscate_dDHiUSY4Qoÿ );
        }
        $_obfuscate_pBjeuVXL = "SELECT COUNT(DISTINCT f.uFlowId) AS num ".$_obfuscate_FC6WZeq5G1D5_dAÿ.( "WHERE (f.uid='".$_obfuscate_7Ri3."' OR (f.uFlowId IN ({$_obfuscate_dDHiUSY4Qoÿ}))) AND {$_obfuscate_IRFhnYwÿ}" );
        $_obfuscate_IAGEj482gQÿÿ = $CNOA_DB->get_one( $_obfuscate_pBjeuVXL );
        $_obfuscate_j34pdsEPbIÿ = ( integer )$_obfuscate_IAGEj482gQÿÿ['num'];
        if ( $_obfuscate_j34pdsEPbIÿ <= 0 )
        {
            ( );
            $_obfuscate_NlQÿ = new dataStore( );
            echo $_obfuscate_NlQÿ->makeJsonData( );
            exit( );
        }
        if ( $_obfuscate_Bj089LCE6qL == "returned" )
        {
            $_obfuscate_3y0Y = "SELECT DISTINCT f.uFlowId, f.* ".$_obfuscate_FC6WZeq5G1D5_dAÿ.( "WHERE f.uFlowId IN (".$_obfuscate_dDHiUSY4Qoÿ.") AND {$_obfuscate_IRFhnYwÿ} {$_obfuscate_H9Mbnwÿÿ} LIMIT {$_obfuscate_mV9HBLYÿ}, {$_obfuscate_xvYeh9Iÿ} " );
        }
        else
        {
            $_obfuscate_3y0Y = "SELECT DISTINCT f.uFlowId, f.* ".$_obfuscate_FC6WZeq5G1D5_dAÿ.( "WHERE (f.uid='".$_obfuscate_7Ri3."' OR (f.uFlowId IN ({$_obfuscate_dDHiUSY4Qoÿ}))) AND {$_obfuscate_IRFhnYwÿ} {$_obfuscate_H9Mbnwÿÿ} LIMIT {$_obfuscate_mV9HBLYÿ}, {$_obfuscate_xvYeh9Iÿ} " );
        }
        $_obfuscate_mPAjEGLn = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_8Bnz38wN01cÿ = $_obfuscate_MujU5qIjWAÿÿ = $_obfuscate_uWfP0Bouwÿÿ = $_obfuscate_ElR49KmUBtFSUBoÿ = $_obfuscate_mKAUcVABjS7vqDZ = array( );
        while ( $_obfuscate_LQ8UKgÿÿ = $CNOA_DB->get_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_uWfP0Bouwÿÿ[] = $_obfuscate_LQ8UKgÿÿ['flowId'];
            $_obfuscate_MujU5qIjWAÿÿ[] = $_obfuscate_LQ8UKgÿÿ['sortId'];
            $_obfuscate_8Bnz38wN01cÿ[] = $_obfuscate_LQ8UKgÿÿ;
            $_obfuscate_ElR49KmUBtFSUBoÿ[] = $_obfuscate_LQ8UKgÿÿ['uFlowId'];
        }
        $_obfuscate_ElR49KmUBtFSUBoÿ = implode( ",", $_obfuscate_ElR49KmUBtFSUBoÿ );
        $_obfuscate_dS1NUvKPGMgÿ = $CNOA_DB->db_select( "*", $this->t_use_step, "WHERE `uFlowId` IN(".$_obfuscate_ElR49KmUBtFSUBoÿ.") AND `proxyUid` !=0" );
        if ( !empty( $_obfuscate_dS1NUvKPGMgÿ ) )
        {
            foreach ( $_obfuscate_dS1NUvKPGMgÿ as $_obfuscate_6Aÿÿ )
            {
                if ( $_obfuscate_6Aÿÿ['dealUid'] == $_obfuscate_6Aÿÿ['proxyUid'] )
                {
                    $_obfuscate_mKAUcVABjS7vqDZ[] = $_obfuscate_6Aÿÿ['uFlowId'];
                }
            }
        }
        $_obfuscate_RopcQP_w = $this->api_getSortByIds( $_obfuscate_MujU5qIjWAÿÿ );
        $_obfuscate_5ZWvow6yfBMjKt0EvsXWPgÿÿ = $CNOA_DB->db_select( array( "name", "flowId", "flowType", "tplSort", "status" ), $this->t_set_flow, "WHERE `flowId` IN (".implode( ",", $_obfuscate_uWfP0Bouwÿÿ ).")" );
        if ( !is_array( $_obfuscate_5ZWvow6yfBMjKt0EvsXWPgÿÿ ) )
        {
            $_obfuscate_5ZWvow6yfBMjKt0EvsXWPgÿÿ = array( );
        }
        $_obfuscate_U6s1cCExb6szBojQ = $_obfuscate_MgWE1yPRnMBaRa4ÿ = $_obfuscate_xhCy8yogiWJQ4D00o7G2 = $_obfuscate_dBcIDGSEkvbn9ZCU3Aÿÿ = array( );
        foreach ( $_obfuscate_5ZWvow6yfBMjKt0EvsXWPgÿÿ as $_obfuscate_1U6d )
        {
            $_obfuscate_U6s1cCExb6szBojQ[$_obfuscate_1U6d['flowId']] = $_obfuscate_1U6d['name'];
            $_obfuscate_MgWE1yPRnMBaRa4ÿ[$_obfuscate_1U6d['flowId']] = $_obfuscate_1U6d['tplSort'];
            $_obfuscate_xhCy8yogiWJQ4D00o7G2[$_obfuscate_1U6d['flowId']] = $_obfuscate_1U6d['flowType'];
            $_obfuscate_dBcIDGSEkvbn9ZCU3Aÿÿ[$_obfuscate_1U6d['flowId']] = $_obfuscate_1U6d['status'];
        }
        $_obfuscate_x1tcCgpyXQÿÿ = $CNOA_DB->db_select( array( "uFlowId", "uStepId" ), $this->t_use_step, "WHERE `uFlowId` IN (".$_obfuscate_dDHiUSY4Qoÿ.") AND `uid`={$_obfuscate_7Ri3} AND (`status`=2 OR `status`=4)" );
        $_obfuscate_DugEvweRydCf = array( );
        if ( !is_array( $_obfuscate_x1tcCgpyXQÿÿ ) )
        {
            $_obfuscate_x1tcCgpyXQÿÿ = array( );
        }
        foreach ( $_obfuscate_x1tcCgpyXQÿÿ as $_obfuscate_6Aÿÿ )
        {
            if ( isset( $_obfuscate_DugEvweRydCf[$_obfuscate_6Aÿÿ['uFlowId']] ) )
            {
                $_obfuscate_6Aÿÿ['uStepId'] = max( $_obfuscate_DugEvweRydCf[$_obfuscate_6Aÿÿ['uFlowId']], $_obfuscate_6Aÿÿ['uStepId'] );
            }
            $_obfuscate_DugEvweRydCf[$_obfuscate_6Aÿÿ['uFlowId']] = $_obfuscate_6Aÿÿ['uStepId'];
        }
        unset( $_obfuscate_x1tcCgpyXQÿÿ );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "uFlowId", "uid", "proxyUid" ), $this->t_use_step, "WHERE `uFlowId` IN (".$_obfuscate_dDHiUSY4Qoÿ.") AND `status`=1" );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_18W8kI0F6OJoU7Mÿ = array( );
        $_obfuscate__eqrEQÿÿ = array( );
        $_obfuscate__Wi6396IheAÿ = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['uid'] != 0 )
            {
                $_obfuscate__eqrEQÿÿ[] = $_obfuscate_6Aÿÿ['uid'];
                $_obfuscate_18W8kI0F6OJoU7Mÿ[$_obfuscate_6Aÿÿ['uFlowId']][] = $_obfuscate_6Aÿÿ['uid'];
            }
            if ( $_obfuscate_6Aÿÿ['proxyUid'] != 0 )
            {
                $_obfuscate__eqrEQÿÿ[] = $_obfuscate_6Aÿÿ['proxyUid'];
                $_obfuscate_18W8kI0F6OJoU7Mÿ[$_obfuscate_6Aÿÿ['uFlowId']][] = $_obfuscate_6Aÿÿ['proxyUid'];
            }
        }
        if ( 0 < count( $_obfuscate__eqrEQÿÿ ) )
        {
            $_obfuscate__eqrEQÿÿ = array_diff( $_obfuscate__eqrEQÿÿ, array( "" ) );
            $_obfuscate__Wi6396IheAÿ = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate__eqrEQÿÿ );
        }
        $_obfuscate_IVEStjJMAbn73TD = array( );
        foreach ( $_obfuscate_18W8kI0F6OJoU7Mÿ as $_obfuscate_Vwty => $_obfuscate_1jUa )
        {
            $_obfuscate_NS44QYkÿ = array( );
            foreach ( $_obfuscate_1jUa as $_obfuscate_0W8ÿ )
            {
                $_obfuscate_NS44QYkÿ[] = $_obfuscate__Wi6396IheAÿ[$_obfuscate_0W8ÿ];
            }
            $_obfuscate_IVEStjJMAbn73TD[$_obfuscate_Vwty] = implode( ",", $_obfuscate_NS44QYkÿ );
        }
        unset( $_obfuscate_18W8kI0F6OJoU7Mÿ );
        unset( $_obfuscate__eqrEQÿÿ );
        unset( $_obfuscate__Wi6396IheAÿ );
        if ( !is_array( $_obfuscate_8Bnz38wN01cÿ ) )
        {
            $_obfuscate_8Bnz38wN01cÿ = array( );
        }
        foreach ( $_obfuscate_8Bnz38wN01cÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['sname'] = $_obfuscate_RopcQP_w[$_obfuscate_6Aÿÿ['sortId']]['name'];
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['level'] = $this->f_level[$_obfuscate_6Aÿÿ['level']];
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['statusText'] = $this->f_status[$_obfuscate_6Aÿÿ['status']];
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['posttime'] = formatdate( $_obfuscate_6Aÿÿ['posttime'], "Y-m-d H:i" );
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['endtime'] = $_obfuscate_6Aÿÿ['endtime'] == 0 ? "----" : formatdate( $_obfuscate_6Aÿÿ['endtime'], "Y-m-d H:i" );
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['flowSetName'] = $_obfuscate_U6s1cCExb6szBojQ[$_obfuscate_6Aÿÿ['flowId']];
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['againStatus'] = $_obfuscate_dBcIDGSEkvbn9ZCU3Aÿÿ[$_obfuscate_6Aÿÿ['flowId']];
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['tplSort'] = $_obfuscate_MgWE1yPRnMBaRa4ÿ[$_obfuscate_6Aÿÿ['flowId']];
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['flowType'] = $_obfuscate_xhCy8yogiWJQ4D00o7G2[$_obfuscate_6Aÿÿ['flowId']];
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['step'] = $_obfuscate_DugEvweRydCf[$_obfuscate_6Aÿÿ['uFlowId']];
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['curUname'] = $_obfuscate_IVEStjJMAbn73TD[$_obfuscate_6Aÿÿ['uFlowId']];
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['allowCallback'] = 0;
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['allowCancel'] = 0;
            if ( $_obfuscate_6Aÿÿ['uid'] == $_obfuscate_7Ri3 )
            {
                $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['owner'] = 1;
                if ( $_obfuscate_6Aÿÿ['status'] == 1 )
                {
                    $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['allowCallback'] = $_obfuscate_6Aÿÿ['allowCallback'];
                    $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['allowCancel'] = $_obfuscate_6Aÿÿ['allowCancel'];
                }
            }
            else
            {
                $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['owner'] = 0;
            }
            if ( in_array( $_obfuscate_6Aÿÿ['uFlowId'], $_obfuscate_mKAUcVABjS7vqDZ ) )
            {
                $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['proxyText'] = "å§”æ‰˜";
            }
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_8Bnz38wN01cÿ;
        $_obfuscate_SUjPN94Er7yI->total = $_obfuscate_j34pdsEPbIÿ;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _loadFenfaFormData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
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
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_5ZL98vEÿ .= $_obfuscate_6Aÿÿ['touid'].",";
                $_obfuscate_e_N_6txY .= app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_6Aÿÿ['touid'] ).",";
            }
            $_obfuscate_5ZL98vEÿ = substr( $_obfuscate_5ZL98vEÿ, 0, -1 );
            $_obfuscate_e_N_6txY = substr( $_obfuscate_e_N_6txY, 0, -1 );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = array(
            "touid" => $_obfuscate_5ZL98vEÿ,
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
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate__eqrEQÿÿ = getpar( $_POST, "touid", "" );
        $_obfuscate_PVLK5jra = explode( ",", $_obfuscate__eqrEQÿÿ );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        if ( 0 < $_obfuscate_Ybai )
        {
            $CNOA_DB->db_delete( $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        }
        $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_rLpOghzteElp = array( );
        foreach ( $_obfuscate_PVLK5jra as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_rLpOghzteElp['touid'] = $_obfuscate_6Aÿÿ;
            $_obfuscate_rLpOghzteElp['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
            $_obfuscate_rLpOghzteElp['fenfauid'] = $_obfuscate_7Ri3;
            $CNOA_DB->db_insert( $_obfuscate_rLpOghzteElp, $this->t_use_fenfa );
            $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_7qDAYo85aGAÿ['flowName']."]ç¼–å·[".$_obfuscate_7qDAYo85aGAÿ['flowNumber']."]åˆ†å‘ç»™ä½ é˜…è¯»ã€‚";
            $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_7qDAYo85aGAÿ['flowId']}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
            $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_7qDAYo85aGAÿ['flowId'];
            $this->addNotice( "notice", $_obfuscate_rLpOghzteElp['touid'], $_obfuscate_6RYLWQÿÿ, "fenfa" );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _callback( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_JG8GuYÿ = array( );
        $_obfuscate_JG8GuYÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
        $_obfuscate_JG8GuYÿ['type'] = 7;
        $_obfuscate_JG8GuYÿ['step'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_JG8GuYÿ['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuYÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuYÿ['say'] = "";
        $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "stepname", "dealUid", "proxyUid" ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId`='{$_obfuscate_0Ul8BBkt}'" );
        $_obfuscate_JG8GuYÿ['stepname'] = $_obfuscate_Tx7M9W['stepName'];
        $this->insertEvent( $_obfuscate_JG8GuYÿ );
        $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "id", "uid", "proxyUid", "uStepId" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        if ( !is_array( $_obfuscate_Tx7M9W ) )
        {
            $_obfuscate_Tx7M9W = array( );
        }
        $_obfuscate_QwT4KwrB2wÿÿ = array( );
        foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_QwT4KwrB2wÿÿ[] = $_obfuscate_6Aÿÿ['id'];
        }
        $this->deleteAllNotice( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_QwT4KwrB2wÿÿ );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        $_obfuscate_qZkmBgÿÿ = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( $_obfuscate_qZkmBgÿÿ['noticeCallback'] != 4 )
        {
            if ( $_obfuscate_qZkmBgÿÿ['noticeCallback'] == 1 )
            {
                $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "recalles" );
                $_obfuscate_6RYLWQÿÿ['href'] = "";
                $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "uid", "id", "dealUid" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `stepType` = '1' " );
                $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_Tx7M9W['id'];
                $this->addNotice( "notice", $_obfuscate_Tx7M9W['dealUid'], $_obfuscate_6RYLWQÿÿ, "callback", 1 );
            }
            else if ( $_obfuscate_qZkmBgÿÿ['noticeCallback'] == 2 )
            {
                $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "recalles" );
                $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "uid", "id", "dealUid" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId` = '{$_obfuscate_0Ul8BBkt}' " );
                $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
                $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_Tx7M9W['id'];
                $this->addNotice( "notice", $_obfuscate_Tx7M9W['dealUid'], $_obfuscate_6RYLWQÿÿ, "callback", 2 );
            }
            else
            {
                $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "recalles" );
                $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
                $_obfuscate_SIUSR4F6 = $CNOA_DB->db_select( "*", $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `status`=2" );
                if ( !is_array( $_obfuscate_SIUSR4F6 ) )
                {
                    $_obfuscate_SIUSR4F6 = array( );
                }
                foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_6Aÿÿ )
                {
                    $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_6Aÿÿ['id'];
                    $this->addNotice( "notice", $_obfuscate_6Aÿÿ['dealUid'], $_obfuscate_6RYLWQÿÿ, "callback", 3 );
                }
            }
        }
        $_obfuscate_Le1NAQIeVbEkanEÿ = $CNOA_DB->db_select( array( "id" ), "wf_u_huiqian", "WHERE `uFlowId`=4989" );
        if ( !is_array( $_obfuscate_Le1NAQIeVbEkanEÿ ) )
        {
            $_obfuscate_Le1NAQIeVbEkanEÿ = array( );
        }
        if ( $_obfuscate_Le1NAQIeVbEkanEÿ )
        {
            foreach ( $_obfuscate_Le1NAQIeVbEkanEÿ as $_obfuscate_6Aÿÿ )
            {
                notice::deletenotice( $_obfuscate_6Aÿÿ['id'], $_obfuscate_6Aÿÿ['id'], $this->t_use_step_huiqian, "write" );
            }
        }
        $CNOA_DB->db_delete( $this->t_use_step_huiqian, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_7I1iFsUPtGtFc0eFtkEÿ = "1";
        $CNOA_DB->db_update( array(
            "status" => 0,
            "edittime" => $GLOBALS['CNOA_TIMESTAMP'],
            "callBackStatus" => $_obfuscate_7I1iFsUPtGtFc0eFtkEÿ
        ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        $CNOA_DB->db_delete( $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        $_obfuscate_BxXST4lMTfRblygB = array( );
        $_obfuscate_BxXST4lMTfRblygB['status'] = 0;
        $_obfuscate_8WrXMY5WMEIjCNkjAÿÿ = array( );
        $_obfuscate_Y5NNIxXl7FiX = $CNOA_DB->db_select( array( "fieldId" ), $this->t_set_step_fields, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' AND `stepId` = 2 AND `write` = 1 " );
        if ( $_obfuscate_Y5NNIxXl7FiX )
        {
            foreach ( $_obfuscate_Y5NNIxXl7FiX as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_8WrXMY5WMEIjCNkjAÿÿ[] = $_obfuscate_6Aÿÿ['fieldId'];
            }
        }
        $_obfuscate_mnGpwkqvvb1bAÿÿ = $CNOA_DB->db_select( array( "id", "dvalue" ), $this->t_set_field, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' " );
        if ( $_obfuscate_mnGpwkqvvb1bAÿÿ )
        {
            foreach ( $_obfuscate_mnGpwkqvvb1bAÿÿ as $_obfuscate_6Aÿÿ )
            {
                if ( !in_array( $_obfuscate_6Aÿÿ['id'], $_obfuscate_8WrXMY5WMEIjCNkjAÿÿ ) )
                {
                    $_obfuscate_6Aÿÿ['dvalue'] = $_obfuscate_6Aÿÿ['dvalue'] == "default" ? "" : $_obfuscate_6Aÿÿ['dvalue'];
                    $_obfuscate_BxXST4lMTfRblygB["T_".$_obfuscate_6Aÿÿ['id']] = $_obfuscate_6Aÿÿ['dvalue'];
                }
            }
        }
        $CNOA_DB->db_update( $_obfuscate_BxXST4lMTfRblygB, "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        $_obfuscate_eOytqZnoPmMkuTA2Aÿÿ = $CNOA_DB->db_select( "*", $this->t_use_step_child_flow, "WHERE `status` IN (0,1) AND `puFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        if ( $_obfuscate_eOytqZnoPmMkuTA2Aÿÿ )
        {
            $_obfuscate_xHZmyK5cgÿÿ = array( );
            foreach ( $_obfuscate_eOytqZnoPmMkuTA2Aÿÿ as $_obfuscate_6Aÿÿ )
            {
                $this->deleteNotice( "both", $_obfuscate_6Aÿÿ['id'], "child" );
            }
        }
        $CNOA_DB->db_delete( $this->t_use_step_child_flow, "WHERE `status` IN (0,1) AND `puFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _cancelflow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uid` = '{$_obfuscate_7Ri3}' " );
        if ( empty( $_obfuscate_Ybai ) )
        {
            msg::callback( FALSE, lang( "youNotSponsor" ) );
        }
        $this->api_cancelFlow( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt );
        msg::callback( TRUE, lang( "successopt" ) );
    }

}

?>
