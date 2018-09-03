<?php

class wfFlowUseTodo extends wfCommon
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
        case "getDeskTopJsonData" :
            $this->_getDeskTopJsonData( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $_obfuscate_phKp89pDgwQÿ = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $_obfuscate_phKp89pDgwQÿ );
            exit( );
        case "getFlowTypeData" :
            $this->_getFlowTypeData( );
            break;
        case "loadFormHtml" :
            $this->_loadFormHtml( );
            break;
        case "loadUflowInfo" :
            $this->_loadUflowInfo( );
            break;
        case "getSendNextData" :
            $this->_getSendNextData( );
            break;
        case "sendNextStep" :
            $this->_sendNextStep( );
            break;
        case "getStepList" :
            $this->_getStepList( );
            break;
        case "getEventList" :
            $this->_getEventList( );
            break;
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
        case "getHuiqianJsonData" :
            $this->_getHuiqianJsonData( );
            break;
        case "submitHuiQianInfo" :
            $this->_submitHuiQianInfo( );
            break;
        case "sendHuiQianInfo" :
            $this->_sendHuiQianInfo( );
            break;
        case "loadHuiqianMsg" :
            $this->_loadHuiqianMsg( );
            break;
        case "huiqianMsg" :
            $this->_huiqianMsg( );
            break;
        case "deleteHuiqian" :
            $this->_deleteHuiqian( );
            break;
        case "getSelectorUser" :
            $this->_getSelectorUser( );
            break;
        case "getFenfaList" :
            $this->_getFenfaList( );
            break;
        case "delFenfa" :
            $this->_delFenfa( );
            break;
        case "addFenfa" :
            $this->_addFenfa( );
            break;
        case "loadFormHtmlView" :
            $this->_loadFormHtmlView( );
            break;
        case "submitEntrustFormData" :
            $this->_submitEntrustFormData( );
            break;
        case "loadEntrustForm" :
            $this->_loadEntrustForm( );
            break;
        case "submitRejectAbout" :
            $this->_submitRejectAbout( );
            break;
        case "loadPrevstepData" :
            $this->_loadPrevstepData( );
            break;
        case "submitPrevstepData" :
            $this->_submitPrevstepData( );
            break;
        case "exportFlow" :
            $this->exportFlow( );
            break;
        case "getExportFlowInfo" :
            $this->getExportFlowInfo( );
            break;
        case "exportFreeFlow" :
            $this->exportFreeFlow( );
            break;
        case "ms_loadTemplateFile" :
            $this->_ms_loadTemplateFile( );
            break;
        case "ms_submitMsOfficeData" :
            $this->_ms_submitMsOfficeData( );
            break;
        case "seqFlowSendNextStep" :
            $this->_seqFlowSendNextStep( );
            break;
        case "freeFlowSendNextStep" :
            $this->_freeFlowSendNextStep( );
            break;
        case "finishFlow" :
            $this->_finishFlow( );
            break;
        case "getChildFlowJsonData" :
            $this->_getChildFlowJsonData( );
            break;
        case "childFaqi" :
            $this->_childFaqi( );
            break;
        case "getChildFlowUserJsonData" :
            $this->_getChildFlowUserJsonData( );
            break;
        case "cancelChildFaqi" :
            $this->_cancelChildFaqi( );
            break;
        case "cuiban" :
            $this->_cuiban( );
            break;
        case "addChildFlow" :
            $this->_addChildFlow( );
            break;
        case "deleteChildFlow" :
            $this->_deleteChildFlow( );
            break;
        case "submitFreeSendBackData" :
            $this->_submitFreeSendBackData( );
            break;
        case "todoInNewWindow" :
            $this->_todoInNewWindow( );
            break;
        case "checkHuiqian" :
            $this->_checkHuiqian( );
            break;
        case "taoHong" :
            $this->_taoHong( );
            break;
        case "saveFieldDraft" :
            $this->_saveFieldDraft( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_vholQÿÿ = getpar( $_GET, "from", "" );
        if ( $_obfuscate_vholQÿÿ == "list" )
        {
            $_obfuscate_7wvDhu7G = getpar( $_GET, "levels", "" );
            $GLOBALS['GLOBALS']['app']['levels'] = $_obfuscate_7wvDhu7G;
            $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/todo.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQÿÿ );
            exit( );
        }
        $_obfuscate_TlvKhtsoOQÿÿ = ( integer )getpar( $_GET, "uFlowId" );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_GET, "step" );
        if ( $_obfuscate_TlvKhtsoOQÿÿ == 0 )
        {
            msg::showerror( "æ²¡æœ‰é€‰æ‹©éœ€è¦åŠžç†çš„æµç¨‹" );
        }
        $_obfuscate_3y0Y = "SELECT f.flowId, f.tplSort, f.flowType, u.status FROM ".tname( $this->t_use_flow )." AS u RIGHT JOIN ".tname( $this->t_set_flow )." AS f ON u.flowId=f.flowId ".( "WHERE uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->get_one( $_obfuscate_3y0Y );
        if ( !$_obfuscate_hTew0boWJESy )
        {
            msg::showerror( "æ²¡æœ‰æ­¤æµç¨‹" );
        }
        if ( $_obfuscate_vholQÿÿ == "showflow" )
        {
            $GLOBALS['GLOBALS']['app']['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
            $GLOBALS['GLOBALS']['app']['step'] = $_obfuscate_0Ul8BBkt;
            $GLOBALS['GLOBALS']['app']['flowId'] = ( integer )$_obfuscate_hTew0boWJESy['flowId'];
            $GLOBALS['GLOBALS']['app']['status'] = ( integer )$_obfuscate_hTew0boWJESy['status'];
            $GLOBALS['GLOBALS']['app']['wf']['tplSort'] = ( integer )$_obfuscate_hTew0boWJESy['tplSort'];
            $GLOBALS['GLOBALS']['app']['wf']['flowType'] = ( integer )$_obfuscate_hTew0boWJESy['flowType'];
            $GLOBALS['GLOBALS']['app']['wf']['type'] = getpar( $_GET, "type", "show" );
            $GLOBALS['GLOBALS']['app']['wf']['childSeeParent'] = getpar( $_GET, "childSeeParent" );
            $GLOBALS['GLOBALS']['app']['wf']['puStepId'] = getpar( $_GET, "puStepId" );
            ( $_obfuscate_TlvKhtsoOQÿÿ );
            $_obfuscate_bIsJe6Aÿ = new wfCache( );
            $_obfuscate_Tx7M9W = $_obfuscate_bIsJe6Aÿ->getStepByStepId( $GLOBALS['app']['step'] );
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
            $this->_getRelFlow( );
            $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/showflow.htm";
        }
        else if ( $_obfuscate_vholQÿÿ == "dealflow" )
        {
            $GLOBALS['GLOBALS']['app']['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
            $GLOBALS['GLOBALS']['app']['step'] = $_obfuscate_0Ul8BBkt;
            $GLOBALS['GLOBALS']['app']['flowId'] = ( integer )$_obfuscate_hTew0boWJESy['flowId'];
            $GLOBALS['GLOBALS']['app']['wf']['tplSort'] = ( integer )$_obfuscate_hTew0boWJESy['tplSort'];
            $GLOBALS['GLOBALS']['app']['wf']['flowType'] = ( integer )$_obfuscate_hTew0boWJESy['flowType'];
            $GLOBALS['GLOBALS']['app']['wf']['changeFlowInfo'] = getpar( $_GET, "changeFlowInfo" );
            ( $_obfuscate_TlvKhtsoOQÿÿ );
            $_obfuscate_e53ODz04JQÿÿ = new wfCache( );
            $_obfuscate_5NhzjnJq_f8ÿ = $CNOA_DB->db_getone( array( "uid", "proxyUid", "childFlow", "stepType" ), $this->t_use_step, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ." AND `uStepId`={$_obfuscate_0Ul8BBkt} AND (`uid`={$_obfuscate_7Ri3} OR `proxyUid`={$_obfuscate_7Ri3}) ORDER BY `id` DESC" );
            $GLOBALS['GLOBALS']['app']['wf']['stepType'] = $_obfuscate_5NhzjnJq_f8ÿ['stepType'];
            if ( $_obfuscate_5NhzjnJq_f8ÿ['childFlow'] == 1 )
            {
                $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->t_use_step_child_flow, "WHERE `puFlowId` = ".$_obfuscate_TlvKhtsoOQÿÿ." AND `stepId` = {$_obfuscate_0Ul8BBkt} " );
                if ( empty( $_obfuscate_Ybai ) )
                {
                    $_obfuscate_ibEsWI9S['puFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
                    $_obfuscate_ibEsWI9S['stepId'] = $_obfuscate_0Ul8BBkt;
                    $_obfuscate_ibEsWI9S['postuid'] = $_obfuscate_7Ri3;
                    $_obfuscate_Tx7M9W = $_obfuscate_e53ODz04JQÿÿ->getStepByStepId( $_obfuscate_0Ul8BBkt );
                    $_obfuscate_MnVVbyZQFVwÿ = json_decode( $_obfuscate_Tx7M9W['nextStep'], TRUE );
                    if ( !is_array( $_obfuscate_MnVVbyZQFVwÿ ) )
                    {
                        $_obfuscate_MnVVbyZQFVwÿ = array( );
                    }
                    foreach ( $_obfuscate_MnVVbyZQFVwÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
                    {
                        $_obfuscate_SeV31Qÿÿ = $_obfuscate_e53ODz04JQÿÿ->getStepByStepId( $_obfuscate_6Aÿÿ );
                        if ( $_obfuscate_SeV31Qÿÿ['stepType'] == 7 )
                        {
                            $_obfuscate_J9i4sncOcwÿÿ = substr( $_obfuscate_SeV31Qÿÿ['bingids'], 0, -1 );
                            if ( !empty( $_obfuscate_J9i4sncOcwÿÿ ) )
                            {
                                $_obfuscate_SIUSR4F6 = $CNOA_DB->db_select( array( "name", "flowId" ), $this->t_set_flow, "WHERE `flowId` IN (".$_obfuscate_J9i4sncOcwÿÿ.") " );
                                foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_snMÿ )
                                {
                                    $_obfuscate_ibEsWI9S['flowId'] = $_obfuscate_snMÿ['flowId'];
                                    $_obfuscate_ibEsWI9S['faqiFlow'] = $_obfuscate_SeV31Qÿÿ['faqiFlow'];
                                    $_obfuscate_ibEsWI9S['endFlow'] = $_obfuscate_SeV31Qÿÿ['endFlow'];
                                    $_obfuscate_ibEsWI9S['sharefile'] = $_obfuscate_SeV31Qÿÿ['sharefile'];
                                    $CNOA_DB->db_insert( $_obfuscate_ibEsWI9S, $this->t_use_step_child_flow );
                                }
                            }
                        }
                    }
                }
                $GLOBALS['GLOBALS']['app']['wf']['childFlow'] = $_obfuscate_5NhzjnJq_f8ÿ['childFlow'];
                $_obfuscate_zWYc3vx7p7TyMq4W = $CNOA_DB->db_getcount( $this->t_use_step_child_flow, "WHERE `puFlowId` = ".$_obfuscate_TlvKhtsoOQÿÿ."  AND `stepId` = {$_obfuscate_0Ul8BBkt} AND `status` = 0 " );
                if ( empty( $_obfuscate_zWYc3vx7p7TyMq4W ) )
                {
                    $GLOBALS['GLOBALS']['app']['wf']['showChildAlert'] = 0;
                }
                else
                {
                    $GLOBALS['GLOBALS']['app']['wf']['showChildAlert'] = 1;
                }
                $GLOBALS['GLOBALS']['app']['wf']['childNum'] = $CNOA_DB->db_getcount( $this->t_use_step_child_flow, "WHERE `puFlowId` = ".$_obfuscate_TlvKhtsoOQÿÿ."  AND `stepId` = {$_obfuscate_0Ul8BBkt}" );
            }
            $_obfuscate_3Huhdq9QriZt7_7Rswÿÿ = $_obfuscate_e53ODz04JQÿÿ->getStepByStepId( $_obfuscate_0Ul8BBkt );
            if ( empty( $_obfuscate_3Huhdq9QriZt7_7Rswÿÿ['allowWordEdit'] ) )
            {
                $_obfuscate_3Huhdq9QriZt7_7Rswÿÿ['allowWordEdit'] = 0;
            }
            $GLOBALS['GLOBALS']['allowWordEdit'] = $_obfuscate_3Huhdq9QriZt7_7Rswÿÿ['allowWordEdit'];
            $this->_getRelFlow( );
            if ( $_obfuscate_7Ri3 != $_obfuscate_5NhzjnJq_f8ÿ['uid'] && $_obfuscate_7Ri3 != $_obfuscate_5NhzjnJq_f8ÿ['proxyUid'] )
            {
                $GLOBALS['GLOBALS']['app']['huiqian']['num'] = 0;
                $GLOBALS['GLOBALS']['ismystep'] = 0;
            }
            else
            {
                $GLOBALS['GLOBALS']['app']['huiqian']['num'] = $CNOA_DB->db_getcount( $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' AND `uid` = '{$_obfuscate_7Ri3}' AND `writetime` > 0 " );
                $GLOBALS['GLOBALS']['ismystep'] = 1;
            }
            $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( "*", $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND (`uid`='{$_obfuscate_7Ri3}' OR `proxyUid`='{$_obfuscate_7Ri3}') AND `uStepId`='{$_obfuscate_0Ul8BBkt}' AND `status`=1" );
            if ( !$_obfuscate_Tx7M9W )
            {
                $GLOBALS['GLOBALS']['app']['huiqian']['num'] = 0;
                $GLOBALS['GLOBALS']['ismystep'] = 0;
            }
            if ( $_obfuscate_Tx7M9W )
            {
                $_obfuscate_CrLWnAZT = " OR `stepId` =".$_obfuscate_Tx7M9W['pStepId'];
            }
            $_obfuscate_BWjlX0Jr = $CNOA_DB->db_getfield( "deal", $this->t_use_deal_way, "WHERE `flowId`=".$_obfuscate_hTew0boWJESy['flowId']." AND (`stepId`={$_obfuscate_0Ul8BBkt} {$_obfuscate_CrLWnAZT} )" );
            if ( $_obfuscate_BWjlX0Jr == 1 )
            {
                $GLOBALS['GLOBALS']['app']['isDeal'] = 1;
            }
            else
            {
                $GLOBALS['GLOBALS']['app']['isDeal'] = 0;
            }
            $_obfuscate_I8JYCNdCxPHGk_ = getpar( $_GET, "openInNewWin", 0 );
            if ( $_obfuscate_I8JYCNdCxPHGk_ == 1 )
            {
                $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/dealInNewWindow.htm";
            }
            else
            {
                $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/dealflow.htm";
            }
        }
        else if ( $_obfuscate_vholQÿÿ == "viewflow" )
        {
            $GLOBALS['GLOBALS']['app']['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
            $GLOBALS['GLOBALS']['app']['step'] = $_obfuscate_0Ul8BBkt;
            $GLOBALS['GLOBALS']['app']['flowId'] = $_obfuscate_hTew0boWJESy['flowId'];
            $GLOBALS['GLOBALS']['app']['wf']['tplSort'] = ( integer )$_obfuscate_hTew0boWJESy['tplSort'];
            $GLOBALS['GLOBALS']['app']['wf']['flowType'] = ( integer )$_obfuscate_hTew0boWJESy['flowType'];
            $GLOBALS['GLOBALS']['app']['wf']['type'] = "show";
            $GLOBALS['GLOBALS']['app']['wf']['allowCallback'] = 0;
            $GLOBALS['GLOBALS']['app']['wf']['allowCancel'] = 0;
            $GLOBALS['GLOBALS']['app']['wf']['owner'] = 0;
            $GLOBALS['GLOBALS']['app']['allowPrint'] = "false";
            $GLOBALS['GLOBALS']['app']['allowFenfa'] = "false";
            $GLOBALS['GLOBALS']['app']['allowExport'] = "false";
            $GLOBALS['GLOBALS']['app']['status'] = 1;
            $this->_getRelFlow( );
            $_obfuscate_BxoH_SjRHQÿÿ = CNOA_PATH."/app/wf/tpl/default/flow/use/showflow.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQÿÿ );
        exit( );
    }

    private function _getRelFlow( )
    {
        global $CNOA_DB;
        $_obfuscate_7K2adqbGwgÿÿ = $CNOA_DB->db_select( array( "puFlowId", "uFlowId" ), $this->t_use_step_child_flow, "WHERE (`puFlowId` = ".$GLOBALS['app']['uFlowId']." AND `uFlowId`!=0) OR `uFlowId`={$GLOBALS['app']['uFlowId']}" );
        $_obfuscate_vYhijxiHce2BjAÿÿ[] = $GLOBALS['app']['uFlowId'];
        if ( !is_array( $_obfuscate_7K2adqbGwgÿÿ ) )
        {
            $_obfuscate_7K2adqbGwgÿÿ = array( );
        }
        $_obfuscate_TlvKhtsoOQÿÿ = array( );
        foreach ( $_obfuscate_7K2adqbGwgÿÿ as $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['puFlowId'] == $GLOBALS['app']['uFlowId'] )
            {
                $_obfuscate_vYhijxiHce2BjAÿÿ[] = $_obfuscate_6Aÿÿ['uFlowId'];
                $_obfuscate_TlvKhtsoOQÿÿ[] = $_obfuscate_6Aÿÿ['uFlowId'];
            }
            if ( $_obfuscate_6Aÿÿ['uFlowId'] == $GLOBALS['app']['uFlowId'] )
            {
                $_obfuscate_vYhijxiHce2BjAÿÿ[] = $_obfuscate_6Aÿÿ['puFlowId'];
                $_obfuscate_TlvKhtsoOQÿÿ[] = $_obfuscate_6Aÿÿ['puFlowId'];
            }
        }
        while ( 0 < count( $_obfuscate_TlvKhtsoOQÿÿ ) )
        {
            $_obfuscate_TlvKhtsoOQÿÿ = implode( ",", $_obfuscate_TlvKhtsoOQÿÿ );
            $_obfuscate_7K2adqbGwgÿÿ = $CNOA_DB->db_select( array( "puFlowId", "uFlowId" ), $this->t_use_step_child_flow, "WHERE (`puFlowId` IN (".$_obfuscate_TlvKhtsoOQÿÿ.") AND `uFlowId`!=0) OR `uFlowId` IN ({$_obfuscate_TlvKhtsoOQÿÿ})" );
            if ( !is_array( $_obfuscate_7K2adqbGwgÿÿ ) && !( 0 < count( $_obfuscate_7K2adqbGwgÿÿ ) ) )
            {
                $_obfuscate_TlvKhtsoOQÿÿ = array( );
                foreach ( $_obfuscate_7K2adqbGwgÿÿ as $_obfuscate_6Aÿÿ )
                {
                    if ( !in_array( $_obfuscate_6Aÿÿ['puFlowId'], $_obfuscate_vYhijxiHce2BjAÿÿ ) )
                    {
                        $_obfuscate_vYhijxiHce2BjAÿÿ[] = $_obfuscate_6Aÿÿ['puFlowId'];
                        $_obfuscate_TlvKhtsoOQÿÿ[] = $_obfuscate_6Aÿÿ['puFlowId'];
                    }
                    if ( !in_array( $_obfuscate_6Aÿÿ['uFlowId'], $_obfuscate_vYhijxiHce2BjAÿÿ ) )
                    {
                        $_obfuscate_vYhijxiHce2BjAÿÿ[] = $_obfuscate_6Aÿÿ['uFlowId'];
                        $_obfuscate_TlvKhtsoOQÿÿ[] = $_obfuscate_6Aÿÿ['uFlowId'];
                    }
                }
            }
        }
        unset( $_obfuscate_7K2adqbGwgÿÿ );
        unset( $_obfuscate_TlvKhtsoOQÿÿ );
        array_shift( &$_obfuscate_vYhijxiHce2BjAÿÿ );
        if ( 0 < count( $_obfuscate_vYhijxiHce2BjAÿÿ ) )
        {
            $_obfuscate_vYhijxiHce2BjAÿÿ = implode( ",", $_obfuscate_vYhijxiHce2BjAÿÿ );
            $_obfuscate_J3xoGDNkbSJolvqe = $CNOA_DB->db_select( array( "uFlowId", "flowId", "flowNumber", "tplSort", "status" ), $this->t_use_flow, "WHERE `uFlowId` IN (".$_obfuscate_vYhijxiHce2BjAÿÿ.")" );
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "uFlowId", "uStepId", "etime" ), $this->t_use_step, "WHERE `uFlowId` IN (".$_obfuscate_vYhijxiHce2BjAÿÿ.")" );
            if ( !is_array( $_obfuscate_mPAjEGLn ) )
            {
                $_obfuscate_mPAjEGLn = array( );
            }
            $_obfuscate_EAma1UOylRWnspih = array( );
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                if ( isset( $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6Aÿÿ['uFlowId']] ) )
                {
                    if ( $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6Aÿÿ['uFlowId']]['etime'] < $_obfuscate_6Aÿÿ['etime'] )
                    {
                        $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6Aÿÿ['uFlowId']] = $_obfuscate_6Aÿÿ;
                    }
                }
                else
                {
                    $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6Aÿÿ['uFlowId']] = $_obfuscate_6Aÿÿ;
                }
            }
            if ( !is_array( $_obfuscate_J3xoGDNkbSJolvqe ) )
            {
                $_obfuscate_J3xoGDNkbSJolvqe = array( );
            }
            foreach ( $_obfuscate_J3xoGDNkbSJolvqe as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_J3xoGDNkbSJolvqe[$_obfuscate_5wÿÿ]['step'] = $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6Aÿÿ['uFlowId']]['uStepId'];
                $_obfuscate_EPBXIrrI[] = $_obfuscate_6Aÿÿ['flowId'];
            }
            if ( 0 < count( $_obfuscate_EPBXIrrI ) )
            {
                $_obfuscate_EPBXIrrI = implode( ",", $_obfuscate_EPBXIrrI );
                $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "flowId", "flowType" ), $this->t_set_flow, "WHERE `flowId` IN (".$_obfuscate_EPBXIrrI.")" );
                if ( !is_array( $_obfuscate_mPAjEGLn ) )
                {
                    $_obfuscate_mPAjEGLn = array( );
                }
                $_obfuscate_EPBXIrrI = array( );
                foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )
                {
                    $_obfuscate_EPBXIrrI[$_obfuscate_6Aÿÿ['flowId']] = $_obfuscate_6Aÿÿ['flowType'];
                }
                foreach ( $_obfuscate_J3xoGDNkbSJolvqe as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
                {
                    $_obfuscate_J3xoGDNkbSJolvqe[$_obfuscate_5wÿÿ]['flowType'] = $_obfuscate_EPBXIrrI[$_obfuscate_6Aÿÿ['flowId']];
                }
                unset( $_obfuscate_EPBXIrrIAMD0 );
            }
            unset( $_obfuscate_mPAjEGLn );
            unset( $_obfuscate_EAma1UOylRWnspih );
            $_obfuscate_J3xoGDNkbSJolvqe = json_encode( $_obfuscate_J3xoGDNkbSJolvqe );
            $GLOBALS['GLOBALS']['app']['wf']['relevanceUFlowInfo'] = $_obfuscate_J3xoGDNkbSJolvqe;
            unset( $_obfuscate_vYhijxiHce2BjAÿÿ );
            unset( $_obfuscate_J3xoGDNkbSJolvqe );
        }
        else
        {
            $GLOBALS['GLOBALS']['app']['wf']['relevanceUFlowInfo'] = 0;
        }
    }

    private function _getJsonData( $_obfuscate_637B = FALSE )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_mV9HBLYÿ = ( integer )getpar( $_POST, "start" );
        $_obfuscate_wWP8H_hVD6l = getpar( $_POST, "flowTitle", "" );
        $_obfuscate_mSWYi45v = getpar( $_POST, "faqiId", "" );
        $_obfuscate_qx37NMÿ = getpar( $_POST, "stime", "" );
        $_obfuscate_KWKBW4ÿ = getpar( $_POST, "etime", "" );
        $_obfuscate_v1GprsIz = ( integer )getpar( $_POST, "flowType" );
        $_obfuscate_F4AbnVRh = ( integer )getpar( $_POST, "flowId" );
        $_obfuscate_pYzeLf4ÿ = getpar( $_POST, "level" );
        $_obfuscate_7wvDhu7G = getpar( $_GET, "levels" );
        $_obfuscate_637B = getpar( $_POST, "lhc", FALSE );
        $_obfuscate_MXKOdzptzrEÿ = explode( ":", CNOA_DB_HOST );
        $_obfuscate_D9yo3Aÿÿ = $_obfuscate_MXKOdzptzrEÿ[0];
        $_obfuscate_4Honjwÿÿ = isset( $_obfuscate_MXKOdzptzrEÿ[1] ) ? $_obfuscate_MXKOdzptzrEÿ[1] : "3306";
        if ( !$_obfuscate_637B )
        {
            $_obfuscate_xvYeh9Iÿ = getpagesize( "wf_flow_use_todo_getJsonData" );
        }
        $_obfuscate_IRFhnYwÿ = array( );
        if ( !empty( $_obfuscate_v1GprsIz ) )
        {
            $_obfuscate_IRFhnYwÿ[] = "f.sortId='".$_obfuscate_v1GprsIz."'";
        }
        if ( $_obfuscate_pYzeLf4ÿ == "3" )
        {
            $_obfuscate_pYzeLf4ÿ = "0";
        }
        if ( !empty( $_obfuscate_7wvDhu7G ) )
        {
            if ( $_obfuscate_7wvDhu7G == "3" )
            {
                $_obfuscate_7wvDhu7G = "0";
            }
            $_obfuscate_IRFhnYwÿ[] = "f.level='".$_obfuscate_7wvDhu7G."'";
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
            if ( empty( $_obfuscate_dcwitxb ) )
            {
                ( );
                $_obfuscate_NlQÿ = new dataStore( );
                echo $_obfuscate_NlQÿ->makeJsonData( );
                exit( );
            }
            $_obfuscate_IRFhnYwÿ[] = $_obfuscate_dcwitxb;
            $_obfuscate_FC6WZeq5G1D5_dAÿ = "FROM ".tname( self::TABLE_PRE_DATA.$_obfuscate_F4AbnVRh )." AS d LEFT JOIN ".tname( $this->t_use_step )." AS s ON s.uFlowId=d.uFlowId ";
        }
        else
        {
            $_obfuscate_FC6WZeq5G1D5_dAÿ = "FROM ".tname( $this->t_use_step )." AS `s` ";
        }
        if ( CNOA_ISSAAS === TRUE )
        {
            $_obfuscate_IRFhnYwÿ[] = "f.domainid=".$GLOBALS['CNOA_DOMAIN_ID'];
        }
        $_obfuscate_IRFhnYwÿ = implode( " AND ", $_obfuscate_IRFhnYwÿ );
        $_obfuscate_3y0Y = "CREATE TEMPORARY TABLE IF NOT EXISTS tmp_wftodo_table(\r\n\t\t\t\t`uStepId` INTEGER NOT NULL , `hqUid` INTEGER NULL DEFAULT 0,\r\n\t\t\t\t`uFlowId` int(10) NOT NULL,`puFlowId` int(10) NOT NULL , `flowId` int(10) NOT NULL ,\r\n\t\t\t\t`flowNumber` char(100) NOT NULL ,`flowName` varchar(200) NOT NULL , `tplSort` int(3) NOT NULL ,\r\n\t\t\t\t`uid` int(10) NOT NULL , `level` int(1) NOT NULL , `reason` mediumtext NOT NULL ,\r\n\t\t\t\t`posttime` int(10) NOT NULL , `endtime` int(10) NOT NULL , `edittime` int(10) NOT NULL ,\r\n\t\t\t\t`updatetime` int(10) NOT NULL ,`attach` mediumtext NOT NULL ,`sortId` int(3) NOT NULL ,\r\n\t\t\t\t`status` int(2) NOT NULL ,`allowCallback` int(1) NOT NULL ,`allowCancel` int(1) NOT NULL ,\r\n\t\t\t\t`htmlFormContent` mediumtext NOT NULL ,`otherApp` int(10) NOT NULL,`callBackStatus` int(1) NOT NULL,\r\n\t\t\t\t`changeFlowInfo` int(1) NULL DEFAULT 0 \r\n\t\t\t\t".( CNOA_ISSAAS === TRUE ? ",`domainid` int(10) NOT NULL" : "" )."\r\n\t\t\t\t);\r\n\t\t\t\tset names UTF8;\r\n\r\n\t\t\t\tTRUNCATE TABLE tmp_wftodo_table;\r\n\r\n\t\t\t\tINSERT INTO tmp_wftodo_table( uStepId, hqUid, uFlowId, puFlowId, flowId, flowNumber, flowName, tplSort, uid, level, reason, posttime, endtime, edittime, updatetime, attach, sortId, status, allowCallback, allowCancel, htmlFormContent, otherApp, callBackStatus, changeFlowInfo".( CNOA_ISSAAS === TRUE ? ",domainid" : "" ).") \r\n\t\t\t\tSELECT `s`.`uStepId`,`h`.`touid` AS `hqUid`,  `f`.* ".$_obfuscate_FC6WZeq5G1D5_dAÿ." LEFT JOIN ".tname( $this->t_use_flow )." AS  `f` ON  `f`.`uFlowId` =  `s`.`uFlowId` LEFT JOIN ".tname( $this->t_use_step_huiqian ).( " AS `h` ON `f`.`uFlowId`=`h`.`uFlowId` AND s.uStepId = h.stepId WHERE (`s`.`uid`='".$_obfuscate_7Ri3."' OR `s`.`proxyUid`='{$_obfuscate_7Ri3}' OR (`h`.`touid`='{$_obfuscate_7Ri3}' AND `h`.`status`=0 AND `h`.`issubmit`=1)) AND `s`.`status`=1 " ).( empty( $_obfuscate_IRFhnYwÿ ) ? "" : "AND ".$_obfuscate_IRFhnYwÿ." " )." AND `f`.`uFlowId` is not null GROUP BY f.uFlowId ORDER BY `f`.`level` DESC,`f`.`updatetime` DESC;\r\n\r\n\t\t\t\tSELECT COUNT(*) AS `num` FROM tmp_wftodo_table ";
        ( $_obfuscate_D9yo3Aÿÿ, CNOA_DB_USER, CNOA_DB_PWD, CNOA_DB_NAME, $_obfuscate_4Honjwÿÿ );
        $_obfuscate_uGf5evmH = new mysqli( );
        if ( mysqli_connect_errno( ) )
        {
            printf( "Connect failed: %s\n", mysqli_connect_error( ) );
            exit( );
        }
        if ( $_obfuscate_uGf5evmH->multi_query( $_obfuscate_3y0Y ) )
        {
            do
            {
                if ( $_obfuscate_xs33Yt_k = $_obfuscate_uGf5evmH->store_result( ) )
                {
                    while ( $_obfuscate_gkt = $_obfuscate_xs33Yt_k->fetch_assoc( ) )
                    {
                        $_obfuscate_j34pdsEPbIÿ = ( integer )$_obfuscate_gkt['num'];
                    }
                    $_obfuscate_xs33Yt_k->close( );
                }
            } while ( $_obfuscate_uGf5evmH->more_results( ) && $_obfuscate_uGf5evmH->next_result( ) );
        }
        $_obfuscate_uGf5evmH->close( );
        if ( $_obfuscate_j34pdsEPbIÿ <= 0 )
        {
            ( );
            $_obfuscate_NlQÿ = new dataStore( );
            echo $_obfuscate_NlQÿ->makeJsonData( );
            exit( );
        }
        $_obfuscate_3y0Y = "CREATE TEMPORARY TABLE IF NOT EXISTS tmp_wftodo_table(\r\n\t\t\t\t`uStepId` INTEGER NOT NULL , `hqUid` INTEGER NULL DEFAULT 0,\r\n\t\t\t\t`uFlowId` int(10) NOT NULL,`puFlowId` int(10) NOT NULL , `flowId` int(10) NOT NULL ,\r\n\t\t\t\t`flowNumber` char(100) NOT NULL ,`flowName` varchar(200) NOT NULL , `tplSort` int(3) NOT NULL ,\r\n\t\t\t\t`uid` int(10) NOT NULL , `level` int(1) NOT NULL , `reason` mediumtext NOT NULL ,\r\n\t\t\t\t`posttime` int(10) NOT NULL , `endtime` int(10) NOT NULL , `edittime` int(10) NOT NULL ,\r\n\t\t\t\t`updatetime` int(10) NOT NULL ,`attach` mediumtext NOT NULL ,`sortId` int(3) NOT NULL ,\r\n\t\t\t\t`status` int(2) NOT NULL ,`allowCallback` int(1) NOT NULL ,`allowCancel` int(1) NOT NULL ,\r\n\t\t\t\t`htmlFormContent` mediumtext NOT NULL ,`otherApp` int(10) NOT NULL,`callBackStatus` int(1) NOT NULL,\r\n\t\t\t\t`changeFlowInfo` int(1) NULL DEFAULT 0 \r\n\t\t\t\t".( CNOA_ISSAAS === TRUE ? ",`domainid` int(10) NOT NULL" : "" )."\r\n\t\t\t\t) CHARSET=utf8 ;\r\n\t\t\t\tset names UTF8;\r\n\r\n\t\t\t\tTRUNCATE TABLE tmp_wftodo_table;\r\n\r\n\t\t\t\tINSERT INTO tmp_wftodo_table( uStepId, hqUid, uFlowId, puFlowId, flowId, flowNumber, flowName, tplSort, uid, level, reason, posttime, endtime, edittime, updatetime, attach, sortId, status, allowCallback, allowCancel, htmlFormContent, otherApp, callBackStatus, changeFlowInfo".( CNOA_ISSAAS === TRUE ? ",domainid" : "" ).") \r\n\t\t\t\tSELECT `s`.`uStepId`,`h`.`touid` AS `hqUid`,  `f`.* ".$_obfuscate_FC6WZeq5G1D5_dAÿ." \r\n\t\t\t\tLEFT JOIN ".tname( $this->t_use_flow )." AS  `f` ON  `f`.`uFlowId` =  `s`.`uFlowId` \r\n\t\t\t\tLEFT JOIN ".tname( $this->t_use_step_huiqian ).( " AS `h` ON `f`.`uFlowId`=`h`.`uFlowId` \r\n\t\t\t\tAND s.uStepId = h.stepId WHERE (`s`.`uid`='".$_obfuscate_7Ri3."' OR `s`.`proxyUid`='{$_obfuscate_7Ri3}' OR (`h`.`touid`='{$_obfuscate_7Ri3}' AND `h`.`status`=0 AND `h`.`issubmit`=1)) \r\n\t\t\t\tAND `s`.`status`=1 " ).( empty( $_obfuscate_IRFhnYwÿ ) ? "" : "AND ".$_obfuscate_IRFhnYwÿ." " ).( empty( $_obfuscate_637B ) ? "" : " AND `f`.`level` =".$_obfuscate_pYzeLf4ÿ." " )."\r\n\t\t\t\tAND `f`.`uFlowId` is not null GROUP BY f.uFlowId ORDER BY `f`.`level` DESC,`f`.`updatetime` DESC;\r\n\r\n\t\t\t\tSELECT * FROM tmp_wftodo_table ";
        if ( !$_obfuscate_637B )
        {
            $_obfuscate_3y0Y .= "LIMIT ".$_obfuscate_mV9HBLYÿ.",{$_obfuscate_xvYeh9Iÿ} ";
        }
        $_obfuscate__eqrEQÿÿ = $_obfuscate_uWfP0Bouwÿÿ = $_obfuscate_MujU5qIjWAÿÿ = $_obfuscate_8Bnz38wN01cÿ = $_obfuscate_MnVpYTUssjiE0Qÿÿ = $_obfuscate_SlAlWrNusqbf = array( );
        ( $_obfuscate_D9yo3Aÿÿ, CNOA_DB_USER, CNOA_DB_PWD, CNOA_DB_NAME, $_obfuscate_4Honjwÿÿ );
        $_obfuscate_uGf5evmH = new mysqli( );
        if ( mysqli_connect_errno( ) )
        {
            printf( "Connect failed: %s\n", mysqli_connect_error( ) );
            exit( );
        }
        if ( $_obfuscate_uGf5evmH->multi_query( $_obfuscate_3y0Y ) )
        {
            do
            {
                if ( $_obfuscate_xs33Yt_k = $_obfuscate_uGf5evmH->store_result( ) )
                {
                    while ( $_obfuscate_gkt = $_obfuscate_xs33Yt_k->fetch_assoc( ) )
                    {
                        $_obfuscate__eqrEQÿÿ[] = $_obfuscate_gkt['uid'];
                        $_obfuscate_MujU5qIjWAÿÿ[] = $_obfuscate_gkt['sortId'];
                        $_obfuscate_uWfP0Bouwÿÿ[] = $_obfuscate_gkt['flowId'];
                        $_obfuscate_MnVpYTUssjiE0Qÿÿ[] = $_obfuscate_gkt['uFlowId'];
                        $_obfuscate_8Bnz38wN01cÿ[] = $_obfuscate_gkt;
                    }
                    $_obfuscate_xs33Yt_k->close( );
                }
            } while ( $_obfuscate_uGf5evmH->more_results( ) && $_obfuscate_uGf5evmH->next_result( ) );
        }
        $_obfuscate_uGf5evmH->close( );
        if ( !empty( $_obfuscate_MnVpYTUssjiE0Qÿÿ ) )
        {
            $_obfuscate_3y0Y = "SELECT `type`, `uFlowId` FROM ".tname( $this->t_use_event )." WHERE `uFlowId` IN(".implode( ",", $_obfuscate_MnVpYTUssjiE0Qÿÿ ).")";
        }
        else
        {
            $_obfuscate_3y0Y = "SELECT `type`, `uFlowId` FROM ".tname( $this->t_use_event )." WHERE `uFlowId` IN(NULL)";
        }
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_SlAlWrNusqbf[$_obfuscate_gkt['uFlowId']] = $_obfuscate_gkt['type'];
        }
        $_obfuscate_YbfVGewvqPYÿ = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate__eqrEQÿÿ );
        $_obfuscate_RopcQP_w = $this->api_getSortByIds( $_obfuscate_MujU5qIjWAÿÿ );
        if ( empty( $_obfuscate_uWfP0Bouwÿÿ ) )
        {
            $_obfuscate_uWfP0Bouwÿÿ = array( 0 );
        }
        $_obfuscate_5ZWvow6yfBMjKt0EvsXWPgÿÿ = $CNOA_DB->db_select( array( "name", "flowId", "tplSort", "flowType" ), $this->t_set_flow, "WHERE `flowId` IN (".implode( ",", $_obfuscate_uWfP0Bouwÿÿ ).")" );
        if ( !is_array( $_obfuscate_5ZWvow6yfBMjKt0EvsXWPgÿÿ ) )
        {
            $_obfuscate_5ZWvow6yfBMjKt0EvsXWPgÿÿ = array( );
        }
        $_obfuscate_U6s1cCExb6szBojQ = array( );
        $_obfuscate_MgWE1yPRnMBaRa4ÿ = array( );
        $_obfuscate_xhCy8yogiWJQ4D00o7G2 = array( );
        foreach ( $_obfuscate_5ZWvow6yfBMjKt0EvsXWPgÿÿ as $_obfuscate_1U6d )
        {
            $_obfuscate_U6s1cCExb6szBojQ[$_obfuscate_1U6d['flowId']] = $_obfuscate_1U6d['name'];
            $_obfuscate_MgWE1yPRnMBaRa4ÿ[$_obfuscate_1U6d['flowId']] = $_obfuscate_1U6d['tplSort'];
            $_obfuscate_xhCy8yogiWJQ4D00o7G2[$_obfuscate_1U6d['flowId']] = $_obfuscate_1U6d['flowType'];
        }
        $_obfuscate_dDHiUSY4Qoÿ = array( 0 );
        foreach ( $_obfuscate_8Bnz38wN01cÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_dDHiUSY4Qoÿ[] = $_obfuscate_6Aÿÿ['uFlowId'];
            if ( $_obfuscate_6Aÿÿ['hqUid'] == $_obfuscate_7Ri3 )
            {
                $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['statusText'] = "ä¼šç­¾ä¸­";
                $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['status'] = "toHQ";
            }
            else
            {
                $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['statusText'] = $this->f_status[$_obfuscate_6Aÿÿ['status']];
                $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['status'] = "todo";
            }
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['eventType'] = $this->f_eventType[$_obfuscate_SlAlWrNusqbf[$_obfuscate_6Aÿÿ['uFlowId']]];
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['level'] = $this->f_level[$_obfuscate_6Aÿÿ['level']];
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['sname'] = $_obfuscate_RopcQP_w[$_obfuscate_6Aÿÿ['sortId']]['name'];
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['uname'] = empty( $_obfuscate_YbfVGewvqPYÿ[$_obfuscate_6Aÿÿ['uid']] ) ? "<span style=\"color:#FF6600\">".lang( "userNotExist" )."</span>" : $_obfuscate_YbfVGewvqPYÿ[$_obfuscate_6Aÿÿ['uid']];
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['flowSetName'] = $_obfuscate_U6s1cCExb6szBojQ[$_obfuscate_6Aÿÿ['flowId']];
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['tplSort'] = $_obfuscate_MgWE1yPRnMBaRa4ÿ[$_obfuscate_6Aÿÿ['flowId']];
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['flowType'] = $_obfuscate_xhCy8yogiWJQ4D00o7G2[$_obfuscate_6Aÿÿ['flowId']];
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['posttime'] = formatdate( $_obfuscate_6Aÿÿ['posttime'], "Y-m-d H:i" );
            if ( $_obfuscate_637B )
            {
                $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['flowNumber'] = "æµç¨‹åç§°[".$_obfuscate_6Aÿÿ['flowName']."] ç¼–å·:".$_obfuscate_6Aÿÿ['flowNumber'];
            }
        }
        $_obfuscate_Xzj2mwÿÿ = $CNOA_DB->db_select( "*", $this->t_use_step_child_flow, "WHERE `puFlowId` IN (".implode( ",", $_obfuscate_dDHiUSY4Qoÿ ).")" );
        if ( !is_array( $_obfuscate_Xzj2mwÿÿ ) )
        {
            $_obfuscate_Xzj2mwÿÿ = array( );
        }
        $_obfuscate_f2cxyCnzRCvtrgÿÿ = array( );
        foreach ( $_obfuscate_Xzj2mwÿÿ as $_obfuscate_6Aÿÿ )
        {
            if ( !isset( $_obfuscate_f2cxyCnzRCvtrgÿÿ[$_obfuscate_6Aÿÿ['puFlowId']]['done'] ) )
            {
                $_obfuscate_f2cxyCnzRCvtrgÿÿ[$_obfuscate_6Aÿÿ['puFlowId']]['done'] = 0;
            }
            if ( !isset( $_obfuscate_f2cxyCnzRCvtrgÿÿ[$_obfuscate_6Aÿÿ['puFlowId']]['all'] ) )
            {
                $_obfuscate_f2cxyCnzRCvtrgÿÿ[$_obfuscate_6Aÿÿ['puFlowId']]['all'] = 0;
            }
            if ( in_array( $_obfuscate_6Aÿÿ['status'], array( 1, 2, 3 ) ) )
            {
                ++$_obfuscate_f2cxyCnzRCvtrgÿÿ[$_obfuscate_6Aÿÿ['puFlowId']]['all'];
            }
            if ( in_array( $_obfuscate_6Aÿÿ['status'], array( 3 ) ) )
            {
                ++$_obfuscate_f2cxyCnzRCvtrgÿÿ[$_obfuscate_6Aÿÿ['puFlowId']]['done'];
            }
        }
        foreach ( $_obfuscate_8Bnz38wN01cÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['childDone'] = 0;
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['childAll'] = 0;
            if ( isset( $_obfuscate_f2cxyCnzRCvtrgÿÿ[$_obfuscate_6Aÿÿ['uFlowId']] ) )
            {
                $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['childDone'] = $_obfuscate_f2cxyCnzRCvtrgÿÿ[$_obfuscate_6Aÿÿ['uFlowId']]['done'];
                $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ]['childAll'] = $_obfuscate_f2cxyCnzRCvtrgÿÿ[$_obfuscate_6Aÿÿ['uFlowId']]['all'];
            }
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_8Bnz38wN01cÿ;
        $_obfuscate_SUjPN94Er7yI->total = $_obfuscate_j34pdsEPbIÿ;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _getDeskTopJsonData( )
    {
        $_obfuscate_v1GprsIz = intval( getpar( $_GET, "sortId" ) );
        $GLOBALS['_POST']['flowType'] = $_obfuscate_v1GprsIz;
        $this->_getJsonData( TRUE );
    }

    private function _getFlowTypeData( )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_sort );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['sname'] = $_obfuscate_6Aÿÿ['name'];
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _loadFormHtml( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQÿÿ = intval( getpar( $_GET, "uFlowId", 0 ) );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_GET, "stepId" );
        ( $_obfuscate_TlvKhtsoOQÿÿ );
        $GLOBALS['GLOBALS']['UWFCACHE'] = new wfCache( );
        $_obfuscate_SIUSR4F6 = $GLOBALS['UWFCACHE']->getFlow( );
        $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->db_getone( array( "flowNumber", "flowName", "uid" ), $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        ( $GLOBALS['UWFCACHE']->getFlowId( ), $_obfuscate_0Ul8BBkt, FALSE, $_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ = new wfFieldFormaterForDeal( );
        $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ->creatorUid = $_obfuscate_7qDAYo85aGAÿ['uid'];
        $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ->flowName = $_obfuscate_7qDAYo85aGAÿ['flowName'];
        $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ->flowNumber = $_obfuscate_7qDAYo85aGAÿ['flowNumber'];
        $_obfuscate_5MjAF_AntLkÿ = $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ->crteateFormHtml( );
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

    private function _loadUflowInfo( $from = NULL )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $uFlowId = intval( getpar( $_POST, "uFlowId", 0 ) );
        $step = intval( getpar( $_POST, "step", 0 ) );
        $tplSort = intval( getpar( $_POST, "tplSort", 0 ) );
        $flowType = intval( getpar( $_POST, "flowType", 0 ) );
        $type = getpar( $_POST, "type", "" );
        $data = array( );
        $baseInfo = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$uFlowId."'" );
        $data['flowNumber'] = $baseInfo['flowNumber'];
        $data['flowName'] = $baseInfo['flowName'];
        $data['reason'] = $baseInfo['reason'];
        $data['level'] = $this->f_level[$baseInfo['level']];
        $data['status'] = $this->f_status[$baseInfo['status']];
        $data['uname'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $baseInfo['uid'] );
        $data['posttime'] = formatdate( $baseInfo['posttime'], "Y-m-d H:i" );
        $data['uFlowId'] = $uFlowId;
        $data['htmlFormContent'] = $baseInfo['htmlFormContent'];
        $data['changeFlowInfo'] = $baseInfo['changeFlowInfo'];
        $flowId = $baseInfo['flowId'];
        ( $uFlowId );
        $wfCache = new wfCache( );
        $stepInfo = $wfCache->getStepByStepId( $step );
        $flowConfig = array( );
        $flowConfig['btnText'] = $this->f_btn_text[$stepInfo['doBtnText']];
        $flowConfig['allowReject'] = $stepInfo['allowReject'];
        $flowConfig['allowHuiqian'] = $stepInfo['allowHuiqian'];
        $flowConfig['allowFenfa'] = $stepInfo['allowFenfa'];
        $flowConfig['allowTuihui'] = $stepInfo['allowTuihui'];
        $flowConfig['allowAttachAdd'] = $type != "huiqian" ? $stepInfo['allowAttachAdd'] : $stepInfo['allowHqAttachAdd'];
        $flowConfig['allowAttachView'] = $type != "huiqian" ? $stepInfo['allowAttachView'] : $stepInfo['allowHqAttachView'];
        $flowConfig['allowAttachEdit'] = $type != "huiqian" ? $stepInfo['allowAttachEdit'] : $stepInfo['allowHqAttachEdit'];
        $flowConfig['allowAttachDelete'] = $type != "huiqian" ? $stepInfo['allowAttachDelete'] : $stepInfo['allowHqAttachDelete'];
        $flowConfig['allowAttachDown'] = $type != "huiqian" ? $stepInfo['allowAttachDown'] : $stepInfo['allowHqAttachDown'];
        $flowConfig['allowWordEdit'] = $stepInfo['allowWordEdit'];
        $flowConfig['allowAttachWordEdit'] = $stepInfo['allowAttachWordEdit'];
        $flowConfig['allowSms'] = $stepInfo['allowSms'];
        $flowConfig['allowYijian'] = $stepInfo['allowYijian'];
        $uStepInfo = $CNOA_DB->db_getone( "*", $this->t_use_step, "WHERE `uStepId`='".$step."' AND `uFlowId`='{$uFlowId}'" );
        $touid = $this->getProxyUid( $flowId, $uid );
        if ( $uid == $uStepInfo['proxyUid'] || $uid == $touid )
        {
            $flowConfig['allowErust'] = 0;
        }
        else
        {
            $flowConfig['allowErust'] = 1;
        }
        if ( $type == "done" )
        {
            $stepDB = $CNOA_DB->db_select( array( "uStepId" ), $this->t_use_step, "WHERE `uFlowId`='".$uFlowId."' AND `uid` = '{$uid}' " );
            if ( !is_array( $stepDB ) )
            {
                $stepDB = array( );
            }
            $stepArr = array( );
            foreach ( $stepDB as $k => $v )
            {
                $stepArr[] = $v['uStepId'];
            }
            $flowConfig['allowReject'] = 0;
            $flowConfig['allowAttachAdd'] = 0;
            $flowConfig['allowAttachEdit'] = 0;
            $flowConfig['allowAttachDelete'] = 0;
            $flowConfig['allowAttachView'] = 0;
            $flowConfig['allowAttachDown'] = 0;
            foreach ( $wfCache->getStepInfoByIdArr( $stepArr ) as $k => $v )
            {
                if ( $v['allowAttachView'] == 1 )
                {
                    $flowConfig['allowAttachView'] = 1;
                }
                if ( $v['allowAttachDown'] == 1 )
                {
                    $flowConfig['allowAttachDown'] = 1;
                }
            }
        }
        $modul = getpar( $_GET, "modul", "" );
        ( );
        $fs = new fs( );
        if ( $flowType == 0 )
        {
            if ( $modul == "view" )
            {
                $flowConfig['allowAttachAdd'] = 0;
                $flowConfig['allowAttachEdit'] = 0;
                $flowConfig['allowAttachDelete'] = 0;
                $flowConfig['allowAttachView'] = 1;
                $flowConfig['allowAttachDown'] = 1;
            }
            if ( $stepInfo['stepType'] == 1 )
            {
                $flowConfig['allowReject'] = 0;
                $flowConfig['allowHuiqian'] = 0;
                $flowConfig['allowTuihui'] = 0;
            }
            if ( $from == "mgr" )
            {
                $flowConfig['allowAttachView'] = 1;
                $flowConfig['allowAttachDown'] = 1;
            }
            $draftUrl = $this->_checkDraft( $flowId, $uFlowId, $step );
            if ( $draftUrl )
            {
                $draftData = include_once( $draftUrl );
                $baseAtt = json_decode( $draftData['attach'], TRUE );
            }
            else
            {
                $baseAtt = json_decode( $baseInfo['attach'], TRUE );
            }
            $attach = $fs->getListInfo4wf( $baseAtt, $flowConfig, TRUE, "deal" );
        }
        else
        {
            if ( $type == "done" )
            {
                $flowConfig['allowAttachEdit'] = 0;
            }
            else
            {
                $flowConfig['allowAttachEdit'] = 1;
            }
            $flowConfig['allowAttachDelete'] = 1;
            $flowConfig['allowAttachView'] = 1;
            $flowConfig['allowAttachDown'] = 1;
            if ( $modul == "view" )
            {
                $flowConfig['allowAttachAdd'] = 0;
                $flowConfig['allowAttachEdit'] = 0;
                $flowConfig['allowAttachDelete'] = 0;
                $flowConfig['allowAttachView'] = 1;
                $flowConfig['allowAttachDown'] = 1;
            }
            if ( $stepInfo['stepType'] == 1 )
            {
                $flowConfig['allowReject'] = 0;
                $flowConfig['allowHuiqian'] = 0;
                $flowConfig['allowTuihui'] = 0;
            }
            if ( $from == "mgr" )
            {
                $flowConfig['allowAttachView'] = 1;
                $flowConfig['allowAttachDown'] = 1;
            }
            $attach = $fs->getListInfo4wf( json_decode( $baseInfo['attach'], TRUE ), $flowConfig, TRUE, "deal" );
        }
        if ( $flowType == 0 && $tplSort == 0 )
        {
            $wf_field_data = $this->api_getWfFieldData( $flowId, $uFlowId );
            $temp = array( );
            $attachID = $CNOA_DB->db_select( array( "id" ), $this->t_set_field, "WHERE `flowId` = '".$flowId."' AND otype = 'attach' " );
            if ( !is_array( $attachID ) )
            {
                $attachID = array( );
            }
            foreach ( $attachID as $key => $value )
            {
                foreach ( $wf_field_data as $k => $v )
                {
                    if ( !( $value['id'] == $v['id'] ) && !$v['data'] )
                    {
                        $attachData .= substr( $v['data'], 1, -1 ).",";
                    }
                }
            }
            if ( 2 < strlen( $baseInfo['attach'] ) )
            {
                $attachData = "[".$attachData.substr( $baseInfo['attach'], 1, -1 )."]";
            }
            else
            {
                $attachData = "[".substr( $attachData, 0, -1 )."]";
            }
            $attach = $fs->getListInfo4wf( json_decode( $attachData, TRUE ), $flowConfig, TRUE, "deal" );
            $didDB = $CNOA_DB->db_select( array( "id" ), $this->t_set_field, "WHERE `flowId` = '".$flowId."' AND `otype` = 'detailtable' " );
            if ( !empty( $didDB ) )
            {
                $detaildArr = array( );
                foreach ( $didDB as $v )
                {
                    $wf_detail_field_data[] = $this->api_getWfFieldDetailData( $flowId, $uFlowId, $v['id'] );
                }
            }
        }
        $readList = $this->api_getReadList( $uFlowId );
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        $ds->attach = $attach;
        $ds->readInfo = $readList;
        if ( $flowType == 0 && $tplSort == 0 )
        {
            $ds->wf_field_data = $wf_field_data;
            $ds->wf_detail_field_data = $wf_detail_field_data;
        }
        $ds->config = $flowConfig;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _getSendNextData( )
    {
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_VBCv7Qÿÿ = getpar( $_POST, "step", 0 );
        $this->__checkChildPass( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_VBCv7Qÿÿ );
        ( $_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_e53ODz04JQÿÿ = new wfCache( );
        $_obfuscate_7qDAYo85aGAÿ = $_obfuscate_e53ODz04JQÿÿ->getFlow( );
        $_obfuscate_F4AbnVRh = ( integer )$_obfuscate_7qDAYo85aGAÿ['flowId'];
        $_obfuscate_pEvU7Kz2Ywÿÿ = ( integer )$_obfuscate_7qDAYo85aGAÿ['tplSort'];
        $_obfuscate_JQJwE4USnB0ÿ = array( );
        $_obfuscate_urgydSw7IkMKIoqpAÿÿ = array( );
        if ( $_obfuscate_pEvU7Kz2Ywÿÿ == 0 )
        {
            foreach ( $GLOBALS['_POST'] as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                if ( ereg( "wf_field_", $_obfuscate_5wÿÿ ) )
                {
                    $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_field_", "", $_obfuscate_5wÿÿ );
                    $_obfuscate_JQJwE4USnB0ÿ[$_obfuscate_gfGsQGKrGgÿÿ] = $_obfuscate_6Aÿÿ;
                }
                if ( ereg( "wf_fieldJ_", $_obfuscate_5wÿÿ ) )
                {
                    $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_fieldJ_", "", $_obfuscate_5wÿÿ );
                    $_obfuscate_JQJwE4USnB0ÿ[$_obfuscate_gfGsQGKrGgÿÿ] = $_obfuscate_6Aÿÿ;
                }
                if ( ereg( "wf_fieldC_", $_obfuscate_5wÿÿ ) )
                {
                    $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_fieldC_", "", $_obfuscate_5wÿÿ );
                    $_obfuscate_SeV31Qÿÿ = explode( "_", $_obfuscate_gfGsQGKrGgÿÿ );
                    $_obfuscate_gfGsQGKrGgÿÿ = $_obfuscate_SeV31Qÿÿ[0];
                    if ( !empty( $_obfuscate_6Aÿÿ ) )
                    {
                        $_obfuscate_JQJwE4USnB0ÿ[$_obfuscate_gfGsQGKrGgÿÿ][] = $_obfuscate_6Aÿÿ;
                    }
                }
            }
            $_obfuscate_urgydSw7IkMKIoqpAÿÿ = $this->api_getWfFieldData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, TRUE );
            foreach ( $_obfuscate_urgydSw7IkMKIoqpAÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_urgydSw7IkMKIoqpAÿÿ[$_obfuscate_5wÿÿ] = $_obfuscate_6Aÿÿ;
                if ( !empty( $_obfuscate_JQJwE4USnB0ÿ[$_obfuscate_5wÿÿ] ) )
                {
                    $_obfuscate_urgydSw7IkMKIoqpAÿÿ[$_obfuscate_5wÿÿ] = $_obfuscate_JQJwE4USnB0ÿ[$_obfuscate_5wÿÿ];
                }
                else
                {
                    $_obfuscate_YIq2A8cÿ = $_obfuscate_e53ODz04JQÿÿ->getField( $_obfuscate_5wÿÿ );
                    if ( $_obfuscate_YIq2A8cÿ['otype'] == "checkbox" )
                    {
                        $_obfuscate_wKRjVAÿÿ = array( );
                        $_obfuscate_6Aÿÿ = json_decode( $_obfuscate_6Aÿÿ, TRUE );
                        if ( !is_array( $_obfuscate_6Aÿÿ ) )
                        {
                            $_obfuscate_6Aÿÿ = array( );
                        }
                        foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_snMÿ )
                        {
                            if ( !empty( $_obfuscate_snMÿ ) )
                            {
                                $_obfuscate_wKRjVAÿÿ[] = $_obfuscate_snMÿ;
                            }
                        }
                        $_obfuscate_urgydSw7IkMKIoqpAÿÿ[$_obfuscate_5wÿÿ] = $_obfuscate_wKRjVAÿÿ;
                    }
                }
            }
        }
        $_obfuscate_7qDAYo85aGAÿ['flowId'] = $_obfuscate_F4AbnVRh;
        $_obfuscate_7qDAYo85aGAÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
        $_obfuscate_7qDAYo85aGAÿ['step'] = $_obfuscate_VBCv7Qÿÿ;
        $_obfuscate_7qDAYo85aGAÿ['formData'] = $_obfuscate_urgydSw7IkMKIoqpAÿÿ;
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        ( );
        $_obfuscate_xPTkGjow2od2YSeeUUcÿ = new wfNextStepData( );
        $_obfuscate_NlQÿ->data = $_obfuscate_xPTkGjow2od2YSeeUUcÿ->getNextStepInfo( $_obfuscate_7qDAYo85aGAÿ );
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function __checkChildPass( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_VBCv7Qÿÿ )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "status", "uFlowId", "stepId" ), $this->t_use_step_child_flow, "WHERE `puFlowId` = ".$_obfuscate_TlvKhtsoOQÿÿ." AND `endFlow` = 'nextStep' AND `status`!=0 AND `stepId`={$_obfuscate_VBCv7Qÿÿ}" );
        if ( empty( $_obfuscate_mPAjEGLn ) )
        {
            return;
        }
        $_obfuscate_QPIn3xJk = FALSE;
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['status'] != 3 )
            {
                if ( $_obfuscate_6Aÿÿ['stepId'] != $_obfuscate_VBCv7Qÿÿ )
                {
                }
                else
                {
                    $_obfuscate_QPIn3xJk = TRUE;
                    $_obfuscate_JC6sZe6bzW0Hygÿÿ[] = $_obfuscate_6Aÿÿ['uFlowId'];
                }
            }
        }
        if ( $_obfuscate_QPIn3xJk )
        {
            $_obfuscate_cBbyO4MnDgÿÿ = $CNOA_DB->db_select( array( "flowNumber" ), $this->t_use_flow, "WHERE `uFlowId` IN (".implode( ",", $_obfuscate_JC6sZe6bzW0Hygÿÿ ).") " );
            if ( !empty( $_obfuscate_cBbyO4MnDgÿÿ ) )
            {
                foreach ( $_obfuscate_cBbyO4MnDgÿÿ as $_obfuscate_6Aÿÿ )
                {
                    $_obfuscate_A1jN .= "[".$_obfuscate_6Aÿÿ['flowNumber']."] ";
                }
            }
            else
            {
                $_obfuscate_A1jN = lang( "allSubprocess" );
            }
            msg::callback( FALSE, $_obfuscate_A1jN.lang( "notOverNextTurnNot" ) );
        }
    }

    private function _sendNextStep( )
    {
        ( );
        $_obfuscate_KU1umg5BJ4xlQyU7RBJvODtb = new wfTodoSendNextStep( );
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = array(
            "uFlowId" => $_obfuscate_KU1umg5BJ4xlQyU7RBJvODtb->getuFlowId( )
        );
        if ( $_obfuscate_KU1umg5BJ4xlQyU7RBJvODtb->stepOfEnd )
        {
            $_obfuscate_SUjPN94Er7yI->autoNextWfInfo = $this->getAutoNextWfInfo( );
        }
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _getStepList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_GET, "uFlowId", getpar( $_POST, "uFlowId", 0 ) );
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", getpar( $_POST, "flowId", 0 ) );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_GET, "flowType", getpar( $_POST, "flowType", 0 ) );
        $_obfuscate_mPAjEGLn = $this->api_getStepList( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmkÿ );
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->total = 0;
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _getEventList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", 0 );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_GET, "flowType", 0 );
        $_obfuscate_mPAjEGLn = $this->api_getEventList( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmkÿ );
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->total = 0;
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _getHuiqianJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQÿÿ = ( integer )getpar( $_GET, "uFlowId" );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_GET, "stepId" );
        $_obfuscate_3y0Y = "SELECT h.id, h.stepId, h.truename, h.message, h.writetime, s.stepname AS stepName FROM ".tname( $this->t_use_step_huiqian )." AS h LEFT JOIN ".tname( $this->t_use_step )." AS s ON (s.uStepId=h.stepId AND s.uFlowId=h.uFlowId) ".( "WHERE h.uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ." ORDER BY h.posttime " );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQÿÿ = array( );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_gkt['writetime'] = empty( $_obfuscate_gkt['writetime'] ) ? "---" : date( "Y-m-d H:i", $_obfuscate_gkt['writetime'] );
            $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_gkt;
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_6RYLWQÿÿ;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _submitHuiQianInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQÿÿ = ( integer )getpar( $_POST, "uFlowId" );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_POST, "stepId" );
        $_obfuscate_sZ_Wvha1 = $CNOA_DB->db_select( array( "touid" ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' " );
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
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_6RYLWQÿÿ['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_6RYLWQÿÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
        $_obfuscate_6RYLWQÿÿ['stepId'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_6RYLWQÿÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_HweFzDn2 = array( );
        $_obfuscate_QabuumMSpAVzrvaOAÿÿ = explode( ",", getpar( $_POST, "huiqianUids", 0 ) );
        $_obfuscate_1XvASPFcSAJ6MqwjC4F = explode( ",", getpar( $_POST, "huiqianNames", 0 ) );
        foreach ( $_obfuscate_QabuumMSpAVzrvaOAÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( !( $_obfuscate_6Aÿÿ == $_obfuscate_6RYLWQÿÿ['uid'] ) )
            {
                if ( $_obfuscate_6Aÿÿ == $_obfuscate_6RYLWQÿÿ['uid'] )
                {
                    break;
                }
            }
            else
            {
                continue;
            }
            $_obfuscate_6RYLWQÿÿ['touid'] = $_obfuscate_6Aÿÿ;
            $_obfuscate_6RYLWQÿÿ['truename'] = $_obfuscate_1XvASPFcSAJ6MqwjC4F[$_obfuscate_5wÿÿ];
            $_obfuscate_6RYLWQÿÿ['issubmit'] = 0;
            $_obfuscate_LvAlJbKidRGZ = $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_use_step_huiqian );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _sendHuiQianInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQÿÿ = ( integer )getpar( $_POST, "uFlowId" );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_POST, "stepId" );
        $_obfuscate_UUNCCPwN8E6 = getpar( $_POST, "qfmessage" );
        $_obfuscate_sZ_Wvha1 = $CNOA_DB->db_select( "*", $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' AND `issubmit`=0" );
        if ( !is_array( $_obfuscate_sZ_Wvha1 ) )
        {
            $_obfuscate_sZ_Wvha1 = array( );
        }
        $_obfuscate_bXYR68w2KAÿÿ = array( );
        foreach ( $_obfuscate_sZ_Wvha1 as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_bXYR68w2KAÿÿ[] = $_obfuscate_6Aÿÿ['touid'];
            $_obfuscate_Bun8ntxTAÿÿ[] = $_obfuscate_6Aÿÿ['id'];
        }
        $_obfuscate_3y0Y = "SELECT u.flowName, u.flowNumber, u.flowId, s.flowType, s.tplSort FROM ".tname( $this->t_use_flow )." AS u LEFT JOIN ".tname( $this->t_set_flow )." AS s ON s.flowId=u.flowId ".( "WHERE u.uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ." LIMIT 0, 1" );
        $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->get_one( $_obfuscate_3y0Y );
        foreach ( $_obfuscate_bXYR68w2KAÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $CNOA_DB->db_update( array(
                "qfmessage" => $_obfuscate_UUNCCPwN8E6,
                "issubmit" => 1
            ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' AND `issubmit`=0" );
            $_obfuscate_gb3bCas1['content'] = lang( "flowName" ).( "[".$_obfuscate_7qDAYo85aGAÿ['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_7qDAYo85aGAÿ['flowNumber']."]" ).lang( "needYouSign" );
            $_obfuscate_gb3bCas1['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_7qDAYo85aGAÿ['flowId']}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_7qDAYo85aGAÿ['flowType']}&tplSort={$_obfuscate_7qDAYo85aGAÿ['tplSort']}";
            $_obfuscate_gb3bCas1['fromid'] = $_obfuscate_Bun8ntxTAÿÿ[$_obfuscate_5wÿÿ];
            $this->addNotice( "both", $_obfuscate_6Aÿÿ, $_obfuscate_gb3bCas1, "huiqian" );
            $_obfuscate_HweFzDn2[] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_6Aÿÿ );
        }
        if ( 0 < count( $_obfuscate_HweFzDn2 ) )
        {
            $_obfuscate_HweFzDn2 = implode( ",", $_obfuscate_HweFzDn2 );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3604, lang( "signPersonnel" ).( "[ ".$_obfuscate_HweFzDn2." ]ï¼Œ" ).lang( "flowName" ).( "[ ".$_obfuscate_7qDAYo85aGAÿ['flowName']." ]" ).lang( "bianHao" ).( "[ ".$_obfuscate_7qDAYo85aGAÿ['flowNumber']." ]" ) );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadHuiqianMsg( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_A1jN = $CNOA_DB->db_getone( array( "message", "qfmessage" ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' AND `touid` = '{$_obfuscate_7Ri3}' " );
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_A1jN;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _huiqianMsg( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_FYJCcRzosAÿÿ = getpar( $_POST, "message", "" );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_cWB6Ym = getpar( $_POST, "delAtt", "" );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_faHPtjcdAp8ÿ = $CNOA_DB->db_getfield( "attach", $this->t_use_flow, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ." and `status`=1" );
        $_obfuscate_1_pbjTIdLU49 = json_decode( $_obfuscate_faHPtjcdAp8ÿ );
        if ( !empty( $_obfuscate_cWB6Ym ) )
        {
            $_obfuscate_cWB6Ym = explode( ",", rtrim( $_obfuscate_cWB6Ym, "," ) );
            $_obfuscate_juwe = array( );
            foreach ( $_obfuscate_cWB6Ym as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                if ( ereg( "wf_attach_", $_obfuscate_6Aÿÿ ) )
                {
                    $_obfuscate_juwe[] = str_replace( "wf_attach_", "", $_obfuscate_6Aÿÿ );
                }
            }
            $_obfuscate_We9a = array_values( array_diff( $_obfuscate_1_pbjTIdLU49, $_obfuscate_juwe ) );
            if ( !is_array( $_obfuscate_We9a ) )
            {
                $_obfuscate_We9a = array( );
            }
            $_obfuscate_SUSIHAÿÿ = json_encode( $_obfuscate_We9a );
            $CNOA_DB->db_update( array(
                "attach" => $_obfuscate_SUSIHAÿÿ
            ), $this->t_use_flow, " WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ." and `status`=1" );
            $_obfuscate_1_pbjTIdLU49 = $_obfuscate_We9a;
        }
        if ( !empty( $_FILES ) )
        {
            foreach ( $GLOBALS['_FILES'] as $_obfuscate_6Aÿÿ )
            {
                ( );
                $_obfuscate_2ggÿ = new fs( );
                $_obfuscate_Ce9h = $_obfuscate_2ggÿ->addFromInternal( $_obfuscate_6Aÿÿ['tmp_name'], $_obfuscate_6Aÿÿ['name'], $_obfuscate_7Ri3, 8 );
                array_push( &$_obfuscate_1_pbjTIdLU49, $_obfuscate_Ce9h );
                $_obfuscate_1_pbjTIdLU49 = json_encode( $_obfuscate_1_pbjTIdLU49 );
                $CNOA_DB->db_update( array(
                    "attach" => $_obfuscate_1_pbjTIdLU49
                ), $this->t_use_flow, " WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ." and `status`=1" );
            }
        }
        $CNOA_DB->db_update( array(
            "message" => $_obfuscate_FYJCcRzosAÿÿ,
            "writetime" => $GLOBALS['CNOA_TIMESTAMP'],
            "status" => 1
        ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' AND `touid` = '{$_obfuscate_7Ri3}' " );
        $_obfuscate_o5fQ1gÿÿ = $CNOA_DB->db_getone( array( "id", "truename", "uid", "uFlowId" ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' AND `touid` = '{$_obfuscate_7Ri3}' " );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowId" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        $this->doneAll( "both", $_obfuscate_o5fQ1gÿÿ['id'], "huiqian" );
        $_obfuscate_AVrjaAn6 = $CNOA_DB->db_getone( array( "id" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId` = '{$_obfuscate_0Ul8BBkt}' " );
        $_obfuscate_0AITFwÿÿ['content'] = lang( "YouhaveFlow" )."[".$_obfuscate_o5fQ1gÿÿ['truename']."]".lang( "haveSubmitSign" );
        $_obfuscate_0AITFwÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
        $_obfuscate_0AITFwÿÿ['fromid'] = $_obfuscate_AVrjaAn6['id'];
        $this->addNotice( "notice", array(
            $_obfuscate_o5fQ1gÿÿ['uid']
        ), $_obfuscate_0AITFwÿÿ, "huiqian", 1 );
        $_obfuscate_JG8GuYÿ = array( );
        $_obfuscate_JG8GuYÿ['type'] = 11;
        $_obfuscate_JG8GuYÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
        $_obfuscate_JG8GuYÿ['step'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_JG8GuYÿ['stepname'] = "ä¼šç­¾";
        $_obfuscate_JG8GuYÿ['say'] = $_obfuscate_FYJCcRzosAÿÿ;
        $_obfuscate_JG8GuYÿ['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuYÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $this->insertEvent( $_obfuscate_JG8GuYÿ );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _deleteHuiqian( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_0W8ÿ = ( integer )getpar( $_POST, "id" );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_POST, "stepId" );
        $CNOA_DB->db_delete( $this->t_use_step_huiqian, "WHERE `id`=".$_obfuscate_0W8ÿ." AND stepId={$_obfuscate_0Ul8BBkt} AND `uid`={$_obfuscate_7Ri3}" );
        notice::deletenotice( $_obfuscate_0W8ÿ, $_obfuscate_0W8ÿ, $this->t_use_step_huiqian, "write" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getSelectorUser( )
    {
        $_obfuscate_TlvKhtsoOQÿÿ = ( integer )getpar( $_GET, "uFlowId" );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_GET, "stepId" );
        if ( $_obfuscate_TlvKhtsoOQÿÿ == 0 || $_obfuscate_0Ul8BBkt == 0 )
        {
            exit( );
        }
        $_obfuscate_LeS8hwÿÿ = getpar( $_GET, "type" );
        $_obfuscate_VVs2K5EESSsPi1oÿ = array( 1 => "huiqian", 2 => "fenfa" );
        if ( !in_array( $_obfuscate_LeS8hwÿÿ, $_obfuscate_VVs2K5EESSsPi1oÿ ) )
        {
            exit( );
        }
        ( $_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_e53ODz04JQÿÿ = new wfCache( );
        $_obfuscate_ktRUIU_2er7vxwÿÿ = $_obfuscate_e53ODz04JQÿÿ->getStepPermitByOperate( $_obfuscate_0Ul8BBkt, array_search( $_obfuscate_LeS8hwÿÿ, $_obfuscate_VVs2K5EESSsPi1oÿ ) );
        $_obfuscate__eqrEQÿÿ = $_obfuscate_2sZ8Toxw = NULL;
        if ( !empty( $_obfuscate_ktRUIU_2er7vxwÿÿ['user'] ) )
        {
            $_obfuscate__eqrEQÿÿ = $_obfuscate_ktRUIU_2er7vxwÿÿ['user'];
        }
        if ( !empty( $_obfuscate_ktRUIU_2er7vxwÿÿ['dept'] ) )
        {
            $_obfuscate_2sZ8Toxw = $_obfuscate_ktRUIU_2er7vxwÿÿ['dept'];
        }
        if ( !empty( $_obfuscate_ktRUIU_2er7vxwÿÿ['rule'] ) )
        {
            $_obfuscate_6mlyHgÿÿ = explode( ",", $_obfuscate_ktRUIU_2er7vxwÿÿ['rule'] );
            $_obfuscate_1jUa = $this->_parsingRule( $_obfuscate_6mlyHgÿÿ, $_obfuscate_TlvKhtsoOQÿÿ );
            if ( isset( $_obfuscate__eqrEQÿÿ ) )
            {
                $_obfuscate__eqrEQÿÿ = array_merge( explode( ",", $_obfuscate__eqrEQÿÿ ), $_obfuscate_1jUa );
            }
            else
            {
                $_obfuscate__eqrEQÿÿ = $_obfuscate_1jUa;
            }
        }
        app::loadapp( "main", "user" )->api_getSelectorData( $_obfuscate__eqrEQÿÿ, $_obfuscate_2sZ8Toxw );
        exit( );
    }

    private function _parsingRule( $_obfuscate_6mlyHgÿÿ, $_obfuscate_TlvKhtsoOQÿÿ )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_xs33Yt_k = array( );
    default :
        switch ( $_obfuscate_vwGQSAÿÿ )
        {
            foreach ( $_obfuscate_6mlyHgÿÿ as $_obfuscate_OQÿÿ )
            {
                list( $_obfuscate_YsVdvv0c, $_obfuscate_vwGQSAÿÿ, $_obfuscate_m5leXC9_Zgÿÿ ) = explode( "|", $_obfuscate_OQÿÿ );
                if ( $_obfuscate_YsVdvv0c == "faqi" )
                {
                    if ( !isset( $_obfuscate_Ce9h ) )
                    {
                        $_obfuscate_Ce9h = $CNOA_DB->db_getfield( "uid", $this->t_use_flow, "WHERE uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ );
                    }
                    $_obfuscate_7Ri3 = $_obfuscate_Ce9h;
                }
                else if ( $_obfuscate_YsVdvv0c == "zhuban" )
                {
                    $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
                }
                if ( !isset( $_obfuscate_oZ6tCOQeBlIÿ ) )
                {
                    $_obfuscate_3y0Y = "SELECT s.id, s.fid, s.path FROM ".tname( "main_user" )." AS u LEFT JOIN ".tname( "main_struct" )." AS s ON s.id=u.deptId ".( "WHERE u.uid=".$_obfuscate_7Ri3." " );
                    $_obfuscate_oZ6tCOQeBlIÿ = $CNOA_DB->get_one( $_obfuscate_3y0Y );
                }
            case "myDept" :
                $_obfuscate_xs33Yt_k[$_obfuscate_oZ6tCOQeBlIÿ['id']][] = $_obfuscate_m5leXC9_Zgÿÿ;
                continue;
            case "upDept" :
                $_obfuscate_xs33Yt_k[$_obfuscate_oZ6tCOQeBlIÿ['fid']][] = $_obfuscate_m5leXC9_Zgÿÿ;
                continue;
            case "myUpDept" :
                $_obfuscate_xs33Yt_k[$_obfuscate_oZ6tCOQeBlIÿ['id']][] = $_obfuscate_m5leXC9_Zgÿÿ;
                $_obfuscate_xs33Yt_k[$_obfuscate_oZ6tCOQeBlIÿ['fid']][] = $_obfuscate_m5leXC9_Zgÿÿ;
            }
            continue;
        case "allDept" :
            $_obfuscate_pp9pYwÿÿ = explode( ",", $_obfuscate_oZ6tCOQeBlIÿ['path'] );
            foreach ( $_obfuscate_pp9pYwÿÿ as $_obfuscate_iuzS )
            {
                $_obfuscate_xs33Yt_k[$_obfuscate_iuzS][] = $_obfuscate_m5leXC9_Zgÿÿ;
            }
        }
        $_obfuscate__eqrEQÿÿ = array( );
        foreach ( $_obfuscate_xs33Yt_k as $_obfuscate_iuzS => $_obfuscate_wNcldwÿÿ )
        {
            if ( !empty( $_obfuscate_wNcldwÿÿ ) )
            {
                $_obfuscate_wNcldwÿÿ = implode( ",", array_unique( $_obfuscate_wNcldwÿÿ ) );
                $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "uid" ), "main_user", "WHERE deptId=".$_obfuscate_iuzS." AND stationid IN ({$_obfuscate_wNcldwÿÿ})" );
                if ( !is_array( $_obfuscate_mPAjEGLn ) )
                {
                    $_obfuscate_mPAjEGLn = array( );
                }
                foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_gkt )
                {
                    $_obfuscate__eqrEQÿÿ[] = $_obfuscate_gkt['uid'];
                }
            }
        }
        if ( empty( $_obfuscate__eqrEQÿÿ ) )
        {
            return array( 0 );
        }
        return array_unique( $_obfuscate__eqrEQÿÿ );
    }

    private function _getFenfaList( )
    {
        global $CNOA_DB;
        $_obfuscate_TlvKhtsoOQÿÿ = ( integer )getpar( $_GET, "uFlowId" );
        $_obfuscate_3y0Y = "SELECT f.uFenfaId AS id, m.truename AS fenfaUname, u.truename AS viewUname, f.viewtime, f.say, f.isread , f.stepId FROM ".tname( $this->t_use_fenfa )." AS f LEFT JOIN ".tname( "main_user" )." AS m ON m.uid=f.fenfauid LEFT JOIN ".tname( "main_user" )." AS u ON u.uid=f.touid ".( "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ." ORDER BY uFenfaId" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQÿÿ = array( );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_gkt['viewtime'] = empty( $_obfuscate_gkt['viewtime'] ) ? "---" : date( "Y-m-d H:i", $_obfuscate_gkt['viewtime'] );
            $_obfuscate_gkt['isread'] = $_obfuscate_gkt['isread'] == 0 ? "æœªé˜…è¯»" : "å·²é˜…è¯»";
            $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_gkt;
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _addFenfa( $_obfuscate_TlvKhtsoOQÿÿ = "", $_obfuscate_rHwsX0gg = array( ), $_obfuscate_0Ul8BBkt, $_obfuscate_CRya7qfm = FALSE )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        if ( $_obfuscate_TlvKhtsoOQÿÿ == "" )
        {
            $_obfuscate_TlvKhtsoOQÿÿ = ( integer )getpar( $_POST, "uFlowId" );
        }
        if ( empty( $_obfuscate_rHwsX0gg ) )
        {
            $_obfuscate_rHwsX0gg = explode( ",", getpar( $_POST, "toUids" ) );
        }
        if ( !$_obfuscate_rHwsX0gg[0] )
        {
            msg::callback( FALSE, "æ‚¨è¿˜æ²¡æœ‰é€‰æ‹©ç”¨æˆ·!" );
        }
        if ( empty( $_obfuscate_0Ul8BBkt ) )
        {
            $_obfuscate_0Ul8BBkt = ( integer )getpar( $_POST, "stepId" );
        }
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
            $_obfuscate_rLpOghzteElp['stepId'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_Ce9h = $CNOA_DB->db_insert( $_obfuscate_rLpOghzteElp, $this->t_use_fenfa );
            $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" ).( "[".$_obfuscate_7qDAYo85aGAÿ['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_7qDAYo85aGAÿ['flowNumber']."]åˆ†å‘ç»™ä½ é˜…è¯»ã€‚" );
            $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_7qDAYo85aGAÿ['flowId']}&flowType={$_obfuscate_7qDAYo85aGAÿ['flowType']}&tplSort={$_obfuscate_7qDAYo85aGAÿ['tplSort']}";
            $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_Ce9h;
            $this->addNotice( "notice", $_obfuscate_rLpOghzteElp['touid'], $_obfuscate_6RYLWQÿÿ, "fenfa" );
            ++$_obfuscate_Ybai;
        }
        if ( 0 < $_obfuscate_Ybai )
        {
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3604, "åˆ†å‘äººå‘˜ï¼Œæµç¨‹åç§°[ ".$_obfuscate_7qDAYo85aGAÿ['flowName']." ] ".lang( "bianHao" ).( "[ ".$_obfuscate_7qDAYo85aGAÿ['flowNumber']." ]" ) );
        }
        if ( !$_obfuscate_CRya7qfm )
        {
            msg::callback( TRUE, lang( "successopt" ) );
        }
    }

    private function _delFenfa( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_0W8ÿ = ( integer )getpar( $_POST, "id" );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_POST, "stepId" );
        $CNOA_DB->db_delete( $this->t_use_fenfa, "WHERE `uFenfaId`=".$_obfuscate_0W8ÿ." AND `stepId`={$_obfuscate_0Ul8BBkt}" );
        notice::deletenotice( $_obfuscate_0W8ÿ, $_obfuscate_0W8ÿ, $this->t_use_fenfa, "fenfa" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function _loadFormHtmlView( $_obfuscate_F4AbnVRh = "", $_obfuscate_TlvKhtsoOQÿÿ = "", $_obfuscate_VBCv7Qÿÿ = "", $_obfuscate_LeS8hwÿÿ = "", $_obfuscate_XRy5y1Ql2aAÿ = FALSE )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_pcmxgqXbvl0yPCL_KHMÿ = getpar( $_POST, "childSeeParent", "" );
        $_obfuscate_tp9SP3Q9McAÿ = getpar( $_POST, "puStepId", "" );
        if ( !$_obfuscate_TlvKhtsoOQÿÿ )
        {
            $_obfuscate_TlvKhtsoOQÿÿ = intval( getpar( $_GET, "uFlowId", getpar( $_POST, "uFlowId", 0 ) ) );
        }
        if ( !$_obfuscate_VBCv7Qÿÿ )
        {
            $_obfuscate_WYOD1IJTSwÿÿ = intval( getpar( $_GET, "stepId", getpar( $_POST, "stepId", 0 ) ) );
        }
        if ( !$_obfuscate_LeS8hwÿÿ )
        {
            $_obfuscate_LeS8hwÿÿ = getpar( $_POST, "type", "" );
        }
        if ( $_obfuscate_XRy5y1Ql2aAÿ && $_obfuscate_LeS8hwÿÿ == "show" )
        {
            $_obfuscate_WYOD1IJTSwÿÿ = $_obfuscate_VBCv7Qÿÿ;
        }
        ( $_obfuscate_TlvKhtsoOQÿÿ );
        $GLOBALS['GLOBALS']['UWFCACHE'] = new wfCache( );
        $_obfuscate_SIUSR4F6 = $GLOBALS['UWFCACHE']->getFlow( );
        ( $GLOBALS['UWFCACHE']->getFlowId( ), $_obfuscate_WYOD1IJTSwÿÿ, FALSE, $_obfuscate_LeS8hwÿÿ, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_pcmxgqXbvl0yPCL_KHMÿ, $_obfuscate_tp9SP3Q9McAÿ );
        $_obfuscate_P5_qcMiY39uereSduytrzD8ÿ = new wfFieldFormaterForView( );
        $_obfuscate_5MjAF_AntLkÿ = $_obfuscate_P5_qcMiY39uereSduytrzD8ÿ->crteateFormHtml( );
        if ( $_obfuscate_XRy5y1Ql2aAÿ )
        {
            return $_obfuscate_5MjAF_AntLkÿ;
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->total = 0;
        $_obfuscate_SUjPN94Er7yI->pageset = $_obfuscate_SIUSR4F6['pageset'];
        $_obfuscate_SUjPN94Er7yI->data = array(
            "formHtml" => $_obfuscate_5MjAF_AntLkÿ
        );
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _loadEntrustForm( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->db_getone( "*", $this->t_use_proxy_uflow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `flowId`!=0" );
        if ( empty( $_obfuscate_7qDAYo85aGAÿ ) )
        {
            $_obfuscate_7qDAYo85aGAÿ = array( );
        }
        else
        {
            $_obfuscate_7qDAYo85aGAÿ['toName'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_7qDAYo85aGAÿ['touid'] );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_7qDAYo85aGAÿ;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _submitEntrustFormData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_ecqaH_ev7Aÿÿ = getpar( $_POST, "uStepId", 0 );
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_6RYLWQÿÿ['fromuid'] = $_obfuscate_7Ri3;
        $_obfuscate_6RYLWQÿÿ['touid'] = getpar( $_POST, "touid", 0 );
        $_obfuscate_6RYLWQÿÿ['uFlowId'] = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_6RYLWQÿÿ['flowId'] = getpar( $_POST, "flowId", 0 );
        $_obfuscate_6RYLWQÿÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_1l6P = getpar( $_POST, "say", "æ— " );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_SIUSR4F6 = $CNOA_DB->db_getone( "*", $this->t_use_proxy_uflow, "WHERE `fromuid`='".$_obfuscate_6RYLWQÿÿ['fromuid']."' AND (`flowId`!=0 AND `flowId`='{$_obfuscate_6RYLWQÿÿ['flowId']}')" );
        if ( !empty( $_obfuscate_SIUSR4F6 ) )
        {
            $CNOA_DB->db_delete( $this->t_use_proxy_uflow, "WHERE `fromuid`='".$_obfuscate_6RYLWQÿÿ['fromuid']."' AND (`flowId`!=0 AND `flowId`='{$_obfuscate_6RYLWQÿÿ['flowId']}')" );
        }
        if ( $_obfuscate_6RYLWQÿÿ['touid'] == $_obfuscate_7Ri3 )
        {
            msg::callback( FALSE, lang( "principalNotChooseOwn" ) );
        }
        else
        {
            $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_6RYLWQÿÿ['uFlowId']."'  " );
            $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "id" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_6RYLWQÿÿ['uFlowId']."' AND `uStepId` = '{$_obfuscate_ecqaH_ev7Aÿÿ}' " );
            $this->deleteNotice( "both", $_obfuscate_Tx7M9W['id'], "todo" );
            $_obfuscate_0AITFwÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "by" )."[".$CNOA_SESSION->get( "TRUENAME" )."]".lang( "entrusteYouNeedYouApp" )."[".$_obfuscate_1l6P."]";
            $_obfuscate_0AITFwÿÿ['href'] = "&uFlowId=".$_obfuscate_6RYLWQÿÿ['uFlowId']."&flowId={$_obfuscate_6RYLWQÿÿ['flowId']}&step={$_obfuscate_ecqaH_ev7Aÿÿ}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
            $_obfuscate_0AITFwÿÿ['fromid'] = $_obfuscate_Tx7M9W['id'];
            $this->addNotice( "both", $_obfuscate_6RYLWQÿÿ['touid'], $_obfuscate_0AITFwÿÿ, "todo" );
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_use_proxy_uflow );
            $CNOA_DB->db_update( array(
                "proxyUid" => $_obfuscate_6RYLWQÿÿ['touid']
            ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_6RYLWQÿÿ['uFlowId']."' AND `uStepId`='{$_obfuscate_ecqaH_ev7Aÿÿ}' AND `uid`='{$_obfuscate_6RYLWQÿÿ['fromuid']}'" );
        }
        $_obfuscate_JG8GuYÿ = array( );
        $_obfuscate_JG8GuYÿ['uFlowId'] = $_obfuscate_6RYLWQÿÿ['uFlowId'];
        $_obfuscate_JG8GuYÿ['type'] = 8;
        $_obfuscate_JG8GuYÿ['step'] = $_obfuscate_ecqaH_ev7Aÿÿ;
        $_obfuscate_JG8GuYÿ['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuYÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuYÿ['say'] = $_obfuscate_1l6P;
        $_obfuscate_JG8GuYÿ['stepname'] = $CNOA_DB->db_getfield( "stepName", $this->t_set_step, "WHERE `flowId`='".$_obfuscate_6RYLWQÿÿ['flowId']."' AND `stepId`='{$_obfuscate_ecqaH_ev7Aÿÿ}'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3604, lang( "entrustFlowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _submitRejectAbout( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_TlvKhtsoOQÿÿ = intval( getpar( $_POST, "uFlowId", 0 ) );
        $_obfuscate_0Ul8BBkt = intval( getpar( $_POST, "step", 0 ) );
        $_obfuscate_hXVTMt5XyOkÿ = $this->_checkDraft( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_0Ul8BBkt );
        if ( $_obfuscate_hXVTMt5XyOkÿ )
        {
            unlink( $_obfuscate_hXVTMt5XyOkÿ );
        }
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_qZkmBgÿÿ = array( );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber", "flowId", "uid" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        $_obfuscate_SXBi6VrH2yEÿ = $this->getProxyFromuid( $_obfuscate_hTew0boWJESy['flowId'], $_obfuscate_7Ri3 );
        if ( !empty( $_obfuscate_SXBi6VrH2yEÿ ) )
        {
            $_obfuscate_7Ri3 = $_obfuscate_SXBi6VrH2yEÿ;
        }
        if ( $_obfuscate_XkuTFqZ6Tmkÿ == 0 )
        {
            ( $_obfuscate_TlvKhtsoOQÿÿ );
            $_obfuscate_e53ODz04JQÿÿ = new wfCache( );
            if ( !$_obfuscate_e53ODz04JQÿÿ->isUserInStep( $_obfuscate_0Ul8BBkt, $_obfuscate_7Ri3 ) )
            {
                msg::callback( FALSE, lang( "stepIsNotYouDeal" ) );
            }
            $_obfuscate_5NhzjnJq_f8ÿ = $_obfuscate_e53ODz04JQÿÿ->getStepByStepId( $_obfuscate_0Ul8BBkt );
            if ( $_obfuscate_5NhzjnJq_f8ÿ['allowReject'] != 1 )
            {
                msg::callback( FALSE, lang( "stepNotRefuse" ) );
            }
        }
        $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "pStepId", "uStepId", "id", "stepname" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId` = '{$_obfuscate_0Ul8BBkt}' " );
        $_obfuscate_bIDuCBSCggÿÿ = $CNOA_DB->db_getone( array( "id", "proxyUid", "uid", "uStepId" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId` = '{$_obfuscate_Tx7M9W['pStepId']}' " );
        if ( $_obfuscate_XkuTFqZ6Tmkÿ == 0 && $_obfuscate_pEvU7Kz2Ywÿÿ == 0 )
        {
            $_obfuscate_KXAlJsÿ = $_obfuscate_e53ODz04JQÿÿ->getFields( );
            $_obfuscate_zUsRh9c_Fjcÿ = array( );
            foreach ( $_obfuscate_KXAlJsÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_zUsRh9c_Fjcÿ[$_obfuscate_6Aÿÿ['id']] = "";
            }
            $_obfuscate_gf7VaSeaaPca_Vsÿ = $_obfuscate_e53ODz04JQÿÿ->getDetailFields( );
            $_obfuscate_dGoPOiQ2Iw5a = array( );
            $_obfuscate_Y4GjuRyoCOQ3 = array( );
            foreach ( $_obfuscate_gf7VaSeaaPca_Vsÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_6Aÿÿ['id']] = $_obfuscate_6Aÿÿ['fid'];
                $_obfuscate_Y4GjuRyoCOQ3[$_obfuscate_6Aÿÿ['id']] = "";
            }
            $_obfuscate_kM1PB1K = array( );
            $_obfuscate_8XjS1n72 = array( );
            $_obfuscate_o5fQ1gÿÿ = array( );
            $_obfuscate_Thgÿ = $CNOA_DB->db_select( "*", $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `id` <= '{$_obfuscate_bIDuCBSCggÿÿ['id']}' " );
            if ( !is_array( $_obfuscate_Thgÿ ) )
            {
                $_obfuscate_Thgÿ = array( );
            }
            foreach ( $_obfuscate_Thgÿ as $_obfuscate_CIKnWK8ÿ => $_obfuscate_WkU7ZbnYkgÿÿ )
            {
                foreach ( $_obfuscate_e53ODz04JQÿÿ->getStepFields( $_obfuscate_WkU7ZbnYkgÿÿ['uStepId'], self::FIELD_RULE_NORMAL ) as $_obfuscate_LQ8UKgÿÿ => $ea )
                {
                    if ( !( $ea['show'] == 1 ) && !( $ea['write'] == 1 ) )
                    {
                        unset( $_obfuscate_zUsRh9c_Fjcÿ[$ea['fieldId']] );
                    }
                }
                foreach ( $_obfuscate_e53ODz04JQÿÿ->getStepFields( $_obfuscate_WkU7ZbnYkgÿÿ['uStepId'], self::FIELD_RULE_DETAIL ) as $_obfuscate_LQ8UKgÿÿ => $_obfuscate_RsAÿ )
                {
                    if ( !( $_obfuscate_RsAÿ['show'] == 1 ) && !( $_obfuscate_RsAÿ['write'] == 1 ) )
                    {
                        unset( $_obfuscate_Y4GjuRyoCOQ3[$_obfuscate_RsAÿ['fieldId']] );
                    }
                }
            }
            foreach ( $_obfuscate_zUsRh9c_Fjcÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_kM1PB1K["T_".$_obfuscate_5wÿÿ] = "";
            }
            foreach ( $_obfuscate_Y4GjuRyoCOQ3 as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_8XjS1n72[$_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_5wÿÿ]]["D_".$_obfuscate_5wÿÿ] = "";
            }
        }
        if ( $_obfuscate_bIDuCBSCggÿÿ['uStepId'] == $_obfuscate_Tx7M9W['pStepId'] )
        {
            $_obfuscate_o5fQ1gÿÿ['uid'] = $_obfuscate_bIDuCBSCggÿÿ['uid'];
            if ( empty( $_obfuscate_bIDuCBSCggÿÿ['proxyUid'] ) )
            {
                $_obfuscate_o5fQ1gÿÿ['proxyUid'] = $this->getProxyUid( $_obfuscate_hTew0boWJESy['flowId'], $_obfuscate_CArovL72wÿÿ );
            }
            else
            {
                $_obfuscate_o5fQ1gÿÿ['proxyUid'] = $_obfuscate_bIDuCBSCggÿÿ['proxyUid'];
            }
            $_obfuscate_EOibFAÿÿ = array(
                $_obfuscate_bIDuCBSCggÿÿ['uid'],
                $_obfuscate_o5fQ1gÿÿ['proxyUid']
            );
            $_obfuscate_1l6P = getpar( $_POST, "say", "æ‹’ç»è¯¥æµç¨‹" );
            $thisStep = $_obfuscate_Tx7M9W;
            $this->doneAll( "both", $thisStep['id'], "todo" );
            $this->doneAll( "notice", $thisStep['id'], "huiqian", 1 );
            if ( $_obfuscate_XkuTFqZ6Tmkÿ == 1 )
            {
                $CNOA_DB->db_update( array( "status" => 0 ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId` = '{$_obfuscate_0Ul8BBkt}' " );
                $CNOA_DB->db_update( array( "etime" => 0, "say" => "", "status" => 1, "stepType" => 1, "dealUid" => 0 ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId` = '{$_obfuscate_bIDuCBSCggÿÿ['uStepId']}' " );
            }
            else
            {
                $CNOA_DB->db_delete( $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId` = '{$_obfuscate_0Ul8BBkt}' " );
                $CNOA_DB->db_update( array(
                    "etime" => 0,
                    "say" => "",
                    "nStepId" => 0,
                    "status" => 1,
                    "proxyUid" => $_obfuscate_o5fQ1gÿÿ['proxyUid']
                ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId` = '{$_obfuscate_bIDuCBSCggÿÿ['uStepId']}' " );
            }
            if ( $_obfuscate_XkuTFqZ6Tmkÿ == 0 )
            {
                $_obfuscate_qZkmBgÿÿ = $_obfuscate_e53ODz04JQÿÿ->getFlow( );
            }
            else
            {
                $_obfuscate_qZkmBgÿÿ = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_hTew0boWJESy['flowId']."'" );
            }
            if ( $_obfuscate_qZkmBgÿÿ['noticeAtGoBack'] != 4 )
            {
                if ( $_obfuscate_qZkmBgÿÿ['noticeAtGoBack'] == 2 )
                {
                    $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "by" )."[".$CNOA_SESSION->get( "TRUENAME" )."]".lang( "reject" );
                    $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step=2&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
                    $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "uid", "id", "uStepId" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId` = '2' " );
                    $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_Tx7M9W['id'];
                    $this->addNotice( "notice", $_obfuscate_hTew0boWJESy['uid'], $_obfuscate_6RYLWQÿÿ, "tuihui", 2 );
                }
                else if ( $_obfuscate_qZkmBgÿÿ['noticeAtGoBack'] == 3 )
                {
                    $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "by" )."[".$CNOA_SESSION->get( "TRUENAME" )."]".lang( "reject" );
                    $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "uid", "proxyUid", "dealUid", "id", "uStepId" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId` != '{$_obfuscate_Tx7M9W['pStepId']}' AND `uStepId` != '2' " );
                    if ( !is_array( $_obfuscate_Tx7M9W ) )
                    {
                        $_obfuscate_Tx7M9W = array( );
                    }
                    foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
                    {
                        $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_Tx7M9W['uStepId']}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
                        $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_Tx7M9W['id'];
                        $this->addNotice( "notice", array(
                            $_obfuscate_Tx7M9W['dealUid'],
                            $_obfuscate_Tx7M9W['uid'],
                            $_obfuscate_Tx7M9W['proxyUid']
                        ), $_obfuscate_6RYLWQÿÿ, "tuihui", 1 );
                    }
                }
                $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "by" )."[".$CNOA_SESSION->get( "TRUENAME" )."]".lang( "rejectNeedYouAppAG" )."<br /> ".lang( "rejectReason" ).":[".$_obfuscate_1l6P."]";
                $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_bIDuCBSCggÿÿ['uStepId']}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
                $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_bIDuCBSCggÿÿ['id'];
                $this->addNotice( "both", array(
                    $_obfuscate_bIDuCBSCggÿÿ['proxyUid'],
                    $_obfuscate_bIDuCBSCggÿÿ['uid']
                ), $_obfuscate_6RYLWQÿÿ, "todo" );
            }
        }
        $_obfuscate_JG8GuYÿ = array( );
        $_obfuscate_JG8GuYÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
        $_obfuscate_JG8GuYÿ['type'] = 5;
        $_obfuscate_JG8GuYÿ['step'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_JG8GuYÿ['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuYÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuYÿ['say'] = $_obfuscate_1l6P;
        if ( $_obfuscate_XkuTFqZ6Tmkÿ == 0 )
        {
            $_obfuscate_JG8GuYÿ['stepname'] = $CNOA_DB->db_getfield( "stepName", $this->t_set_step, "WHERE `flowId`='".$_obfuscate_hTew0boWJESy['flowId']."' AND `stepId`='{$_obfuscate_0Ul8BBkt}'" );
        }
        else
        {
            $_obfuscate_JG8GuYÿ['stepname'] = $thisStep['stepname'];
        }
        $this->insertEvent( $_obfuscate_JG8GuYÿ );
        if ( $_obfuscate_XkuTFqZ6Tmkÿ == 0 && $_obfuscate_pEvU7Kz2Ywÿÿ == 0 )
        {
            $CNOA_DB->db_update( $_obfuscate_kM1PB1K, "z_wf_t_".$_obfuscate_hTew0boWJESy['flowId'], "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
            if ( !empty( $_obfuscate_8XjS1n72 ) )
            {
                foreach ( $_obfuscate_8XjS1n72 as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
                {
                    $CNOA_DB->db_update( $_obfuscate_6Aÿÿ, "z_wf_d_".$_obfuscate_hTew0boWJESy['flowId']."_".$_obfuscate_5wÿÿ, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
                }
            }
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3604, lang( "refushFlowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadPrevstepData( $_obfuscate_6y3M = FALSE )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQÿÿ = intval( getpar( $_POST, "uFlowId", 0 ) );
        $_obfuscate_0Ul8BBkt = intval( getpar( $_POST, "step", 0 ) );
        $_obfuscate_HYI4w55m58H2WjCs = $this->getAllStepByUFlowId( $_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_j9eamhYÿ = array( );
        foreach ( $_obfuscate_HYI4w55m58H2WjCs['stepInfo'] as $_obfuscate_6Aÿÿ )
        {
            if ( in_array( $_obfuscate_6Aÿÿ['status'], array(
                self::STEP_STATUS_DONE,
                self::STEP_STATUS_RESERVATION
            ) ) && !( $_obfuscate_6Aÿÿ['dealUid'] != 0 ) || !( $_obfuscate_0Ul8BBkt == $_obfuscate_6Aÿÿ['uStepId'] ) )
            {
                $_obfuscate_j9eamhYÿ[$_obfuscate_6Aÿÿ['uStepId']] = $_obfuscate_6Aÿÿ;
            }
        }
        unset( $_obfuscate_HYI4w55m58H2WjCs );
        $_obfuscate_pp9pYwÿÿ = array( );
        $this->getPath( $_obfuscate_j9eamhYÿ, $_obfuscate_0Ul8BBkt, $_obfuscate_pp9pYwÿÿ );
        $_obfuscate_pp9pYwÿÿ = array_reverse( $_obfuscate_pp9pYwÿÿ, TRUE );
        array_pop( &$_obfuscate_pp9pYwÿÿ );
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_7wÿÿ = 1;
        foreach ( $_obfuscate_pp9pYwÿÿ as $_obfuscate_0W8ÿ => $_obfuscate_WKs3DAÿÿ )
        {
            $_obfuscate_VBCv7Qÿÿ = $_obfuscate_j9eamhYÿ[$_obfuscate_0W8ÿ];
            if ( !( $_obfuscate_VBCv7Qÿÿ['stepType'] != 4 ) && is_array( $_obfuscate_WKs3DAÿÿ ) )
            {
                $_obfuscate_LQ8UKgÿÿ = array( );
                $_obfuscate_LQ8UKgÿÿ['boxLabel'] = $_obfuscate_VBCv7Qÿÿ['stepname'];
                $_obfuscate_LQ8UKgÿÿ['inputValue'] = $_obfuscate_VBCv7Qÿÿ['uStepId'];
                $_obfuscate_LQ8UKgÿÿ['name'] = "uStepId";
                $_obfuscate_LQ8UKgÿÿ['checked'] = $_obfuscate_7wÿÿ == 1 ? TRUE : FALSE;
                if ( is_array( $_obfuscate_WKs3DAÿÿ ) && !empty( $_obfuscate_WKs3DAÿÿ ) )
                {
                    foreach ( $_obfuscate_WKs3DAÿÿ as $_obfuscate_cO77 => $_obfuscate_9HHDStdl )
                    {
                        $_obfuscate_VtHt = array( );
                        foreach ( $_obfuscate_9HHDStdl as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
                        {
                            $_obfuscate_fPSqRzEÿ = $_obfuscate_j9eamhYÿ[$_obfuscate_5wÿÿ];
                            $_obfuscate_VtHt[] = array(
                                "id" => "bf_step_".$_obfuscate_VBCv7Qÿÿ['uStepId']."_{$_obfuscate_fPSqRzEÿ['uStepId']}",
                                "boxLabel" => $_obfuscate_fPSqRzEÿ['stepname'],
                                "inputValue" => $_obfuscate_fPSqRzEÿ['uStepId'],
                                "name" => "bingfaStepId_".$_obfuscate_cO77
                            );
                        }
                        $_obfuscate_LQ8UKgÿÿ['bingfaChild'][] = $_obfuscate_VtHt;
                    }
                }
                $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_LQ8UKgÿÿ;
                ++$_obfuscate_7wÿÿ;
            }
        }
        if ( $_obfuscate_6y3M )
        {
            return $_obfuscate_6RYLWQÿÿ;
        }
        msg::callback( TRUE, json_encode( $_obfuscate_6RYLWQÿÿ ) );
    }

    private function getPath( $_obfuscate_j9eamhYÿ, $_obfuscate_0Ul8BBkt, &$_obfuscate_pp9pYwÿÿ )
    {
        $_obfuscate_WKs3DAÿÿ = $_obfuscate_j9eamhYÿ[$_obfuscate_0Ul8BBkt];
        if ( isset( $_obfuscate_WKs3DAÿÿ ) )
        {
            if ( $_obfuscate_WKs3DAÿÿ['steType'] != 4 && empty( $_obfuscate_pp9pYwÿÿ[$_obfuscate_0Ul8BBkt] ) )
            {
                $_obfuscate_pp9pYwÿÿ[$_obfuscate_0Ul8BBkt] = $_obfuscate_0Ul8BBkt;
            }
            if ( $_obfuscate_WKs3DAÿÿ['stepType'] == 5 )
            {
                $_obfuscate_BQjjgu6Bgÿ = $_obfuscate_WKs3DAÿÿ['pStepId'];
                foreach ( $_obfuscate_j9eamhYÿ as $p )
                {
                    if ( $p['pStepId'] == $_obfuscate_BQjjgu6Bgÿ )
                    {
                        $_obfuscate_9HHDStdl = array( );
                        $this->getBranch( $_obfuscate_9HHDStdl, $_obfuscate_j9eamhYÿ, $p['uStepId'], $_obfuscate_0Ul8BBkt );
                        if ( !empty( $_obfuscate_9HHDStdl ) )
                        {
                            $_obfuscate_pp9pYwÿÿ[$_obfuscate_BQjjgu6Bgÿ][] = $_obfuscate_9HHDStdl;
                        }
                    }
                }
            }
            $this->getPath( $_obfuscate_j9eamhYÿ, $_obfuscate_WKs3DAÿÿ['pStepId'], $_obfuscate_pp9pYwÿÿ );
        }
    }

    private function getBranch( &$_obfuscate_pp9pYwÿÿ, $_obfuscate_j9eamhYÿ, $_obfuscate_0Ul8BBkt, $_obfuscate_il12XepTzMCB = NULL )
    {
        if ( $_obfuscate_0Ul8BBkt == $_obfuscate_il12XepTzMCB )
        {
            return;
        }
        $_obfuscate_VBCv7Qÿÿ = $_obfuscate_j9eamhYÿ[$_obfuscate_0Ul8BBkt];
        if ( isset( $_obfuscate_VBCv7Qÿÿ ) )
        {
            $this->getBranch( $_obfuscate_pp9pYwÿÿ, $_obfuscate_j9eamhYÿ, $_obfuscate_VBCv7Qÿÿ['nStepId'], $_obfuscate_il12XepTzMCB );
            $_obfuscate_pp9pYwÿÿ[$_obfuscate_0Ul8BBkt] = $_obfuscate_0Ul8BBkt;
        }
    }

    private function _submitPrevstepData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_ecqaH_ev7Aÿÿ = getpar( $_POST, "uStepId", 0 );
        $_obfuscate_1l6P = getpar( $_POST, "say", "é€€å›žè¯¥æµç¨‹" );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_hXVTMt5XyOkÿ = $this->_checkDraft( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_0Ul8BBkt );
        if ( $_obfuscate_hXVTMt5XyOkÿ )
        {
            unlink( $_obfuscate_hXVTMt5XyOkÿ );
        }
        $_obfuscate_HYI4w55m58H2WjCs = $this->getAllStepByUFlowId( $_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_j9eamhYÿ = $_obfuscate_HYI4w55m58H2WjCs['stepInfo'];
        $_obfuscate_hsTQkq6NOXRA = $_obfuscate_j9eamhYÿ;
        ( $_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_e53ODz04JQÿÿ = new wfCache( );
        $_obfuscate_7qDAYo85aGAÿ = $_obfuscate_e53ODz04JQÿÿ->getFlow( );
        $_obfuscate_XkuTFqZ6Tmkÿ = $_obfuscate_7qDAYo85aGAÿ['flowType'];
        $_obfuscate_pEvU7Kz2Ywÿÿ = $_obfuscate_7qDAYo85aGAÿ['tplSort'];
        if ( $_obfuscate_XkuTFqZ6Tmkÿ == 0 )
        {
            $_obfuscate_5NhzjnJq_f8ÿ = $_obfuscate_e53ODz04JQÿÿ->getStepByStepId( $_obfuscate_0Ul8BBkt );
            if ( $_obfuscate_5NhzjnJq_f8ÿ['allowTuihui'] != 1 )
            {
                msg::callback( FALSE, lang( "noPermitToDo" ) );
            }
            if ( $_obfuscate_HYI4w55m58H2WjCs['stepInfo'][$_obfuscate_0Ul8BBkt]['status'] != 1 )
            {
                msg::callback( FALSE, lang( "noPermitOpt" ) );
            }
        }
        $_obfuscate_v1oL = $CNOA_DB->db_getfield( "deal", $this->t_use_deal_way, "WHERE `stepId`=".$_obfuscate_ecqaH_ev7Aÿÿ." AND `flowId`={$_obfuscate_7qDAYo85aGAÿ['flowId']}" );
        $_obfuscate_pp9pYwÿÿ = array( );
        $this->getPath( $_obfuscate_j9eamhYÿ, $_obfuscate_0Ul8BBkt, $_obfuscate_pp9pYwÿÿ );
        $_obfuscate_umeHLFZ6 = $_obfuscate_j9eamhYÿ[$_obfuscate_ecqaH_ev7Aÿÿ];
        $_obfuscate_NenxpX50gwÿÿ = $_obfuscate_j9eamhYÿ[$_obfuscate_0Ul8BBkt];
        $_obfuscate_rX2e4Qw_AQÿÿ = array( );
        $_obfuscate_ZRB1fr3t0YMÿ = array( );
        $_obfuscate_Tz0Oow9UfYEsgÿÿ = array( );
        if ( $_obfuscate_umeHLFZ6['stepType'] == 4 )
        {
            $_obfuscate_Q0seLzjzqtYp = array( );
            foreach ( $GLOBALS['_POST'] as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                if ( preg_match( "/bingfaStepId_\\d+/", $_obfuscate_5wÿÿ ) )
                {
                    $_obfuscate_Q0seLzjzqtYp[] = getpar( $_POST, $_obfuscate_5wÿÿ );
                }
            }
            $_obfuscate_FcvH8FD7nIf1tXFjmwÿÿ = 0;
            foreach ( $_obfuscate_pp9pYwÿÿ as $_obfuscate_Cy1W => $_obfuscate_WKs3DAÿÿ )
            {
                if ( $_obfuscate_Cy1W == $_obfuscate_ecqaH_ev7Aÿÿ )
                {
                    $_obfuscate_FcvH8FD7nIf1tXFjmwÿÿ = array_pop( &$_obfuscate_rX2e4Qw_AQÿÿ );
                }
                if ( is_array( $_obfuscate_WKs3DAÿÿ ) )
                {
                    foreach ( $_obfuscate_WKs3DAÿÿ as $_obfuscate_cO77 => $_obfuscate_9HHDStdl )
                    {
                        $_obfuscate_g0z4XGkB83_1 = array_intersect( $_obfuscate_9HHDStdl, $_obfuscate_Q0seLzjzqtYp );
                        if ( !is_array( $_obfuscate_9HHDStdl ) && empty( $_obfuscate_g0z4XGkB83_1 ) )
                        {
                            foreach ( $_obfuscate_9HHDStdl as $_obfuscate_1tGeYgÿÿ )
                            {
                                if ( in_array( $_obfuscate_1tGeYgÿÿ, $_obfuscate_Q0seLzjzqtYp ) )
                                {
                                    $_obfuscate_ZRB1fr3t0YMÿ[] = $_obfuscate_1tGeYgÿÿ;
                                }
                                else
                                {
                                    $_obfuscate_rX2e4Qw_AQÿÿ[] = $_obfuscate_1tGeYgÿÿ;
                                }
                            }
                        }
                    }
                }
                if ( $_obfuscate_Cy1W == $_obfuscate_ecqaH_ev7Aÿÿ )
                {
                    break;
                }
                $_obfuscate_rX2e4Qw_AQÿÿ[] = $_obfuscate_Cy1W;
            }
            if ( empty( $_obfuscate_g0z4XGkB83_1 ) )
            {
                msg::callback( FALSE, lang( "pleaseSelectStep" ) );
            }
            $_obfuscate_6RYLWQÿÿ = array( "proxyUid" => 0, "dealUid" => 0, "status" => 0, "stime" => 0, "etime" => 0, "timegap" => "0", "say" => "", "nStepId" => 0 );
            $CNOA_DB->db_update( $_obfuscate_6RYLWQÿÿ, $this->t_use_step, "WHERE `id`=".$_obfuscate_j9eamhYÿ[$_obfuscate_FcvH8FD7nIf1tXFjmwÿÿ]['id']." AND `uFlowId`={$_obfuscate_TlvKhtsoOQÿÿ} " );
        }
        else
        {
            foreach ( $_obfuscate_pp9pYwÿÿ as $_obfuscate_Cy1W => $_obfuscate_WKs3DAÿÿ )
            {
                if ( $_obfuscate_Cy1W == $_obfuscate_ecqaH_ev7Aÿÿ )
                {
                    $_obfuscate_ZRB1fr3t0YMÿ[] = $_obfuscate_Cy1W;
                }
                else
                {
                    if ( $_obfuscate_j9eamhYÿ[$_obfuscate_Cy1W]['stepType'] == 4 )
                    {
                        $_obfuscate_BQjjgu6Bgÿ = $_obfuscate_j9eamhYÿ[$_obfuscate_Cy1W]['uStepId'];
                        foreach ( $_obfuscate_j9eamhYÿ as $p )
                        {
                            if ( $p['pStepId'] == $_obfuscate_BQjjgu6Bgÿ )
                            {
                                $_obfuscate_9HHDStdl = array( );
                                $this->getBranch( $_obfuscate_9HHDStdl, $_obfuscate_j9eamhYÿ, $p['uStepId'], $_obfuscate_0Ul8BBkt );
                                if ( !empty( $_obfuscate_9HHDStdl ) )
                                {
                                    $_obfuscate_rX2e4Qw_AQÿÿ = array_merge( $_obfuscate_rX2e4Qw_AQÿÿ, $_obfuscate_9HHDStdl );
                                }
                            }
                        }
                    }
                    $_obfuscate_rX2e4Qw_AQÿÿ[] = $_obfuscate_Cy1W;
                    if ( is_array( $_obfuscate_WKs3DAÿÿ ) )
                    {
                        foreach ( $_obfuscate_WKs3DAÿÿ as $_obfuscate_cO77 => $_obfuscate_9HHDStdl )
                        {
                            if ( is_array( $_obfuscate_9HHDStdl ) )
                            {
                                $_obfuscate_rX2e4Qw_AQÿÿ = array_merge( $_obfuscate_rX2e4Qw_AQÿÿ, $_obfuscate_9HHDStdl );
                            }
                        }
                    }
                }
            }
        }
        $_obfuscate_BRPhVAÿÿ = array( );
        $_obfuscate_8CJrZgÿÿ = array( );
        foreach ( $_obfuscate_ZRB1fr3t0YMÿ as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_BRPhVAÿÿ[] = $_obfuscate_j9eamhYÿ[$_obfuscate_6Aÿÿ]['id'];
        }
        foreach ( $_obfuscate_rX2e4Qw_AQÿÿ as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_8CJrZgÿÿ[] = $_obfuscate_j9eamhYÿ[$_obfuscate_6Aÿÿ]['id'];
            if ( $_obfuscate_j9eamhYÿ[$_obfuscate_6Aÿÿ]['status'] != 0 && $_obfuscate_j9eamhYÿ[$_obfuscate_6Aÿÿ]['stepType'] != 4 )
            {
                $_obfuscate_S0PSA37yAwÿÿ[] = $_obfuscate_j9eamhYÿ[$_obfuscate_6Aÿÿ]['uStepId'];
            }
            if ( $_obfuscate_j9eamhYÿ[$_obfuscate_6Aÿÿ]['stepType'] == 6 )
            {
                $_obfuscate_Tz0Oow9UfYEsgÿÿ[] = $_obfuscate_j9eamhYÿ[$_obfuscate_6Aÿÿ]['id'];
            }
            $_obfuscate_BWjlX0Jr = $CNOA_DB->db_getfield( "deal", $this->t_use_deal_way, "WHERE `stepId`=".$_obfuscate_6Aÿÿ." AND `flowId`={$_obfuscate_7qDAYo85aGAÿ['flowId']}" );
            if ( $_obfuscate_BWjlX0Jr == 1 )
            {
                $CNOA_DB->db_delete( $this->t_use_step, "WHERE `uStepId`=".$_obfuscate_6Aÿÿ." AND `uFlowId`={$_obfuscate_TlvKhtsoOQÿÿ} " );
            }
        }
        $_obfuscate_BRPhVAÿÿ = implode( ",", $_obfuscate_BRPhVAÿÿ );
        $_obfuscate_8CJrZgÿÿ = implode( ",", $_obfuscate_8CJrZgÿÿ );
        $_obfuscate_b6uYSQÿÿ = implode( ",", $_obfuscate_S0PSA37yAwÿÿ );
        $_obfuscate_Tz0Oow9UfYEsgÿÿ = implode( ",", $_obfuscate_Tz0Oow9UfYEsgÿÿ );
        $_obfuscate_6RYLWQÿÿ = array( "etime" => 0, "say" => "", "nStepId" => 0, "status" => 1 );
        if ( !empty( $_obfuscate_S0PSA37yAwÿÿ ) )
        {
            $_obfuscate_NuFUbhnhRkMÿ = $_obfuscate_TeJEvUaP_x_1Twÿÿ = array( );
            foreach ( $_obfuscate_S0PSA37yAwÿÿ as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_Bk2lGlkÿ = "WHERE `stepId`=".$_obfuscate_6Aÿÿ." AND `uFlowId`={$_obfuscate_TlvKhtsoOQÿÿ}";
                $_obfuscate_hChFFlJCLh3o = $CNOA_DB->db_select( "*", $this->t_use_fenfa, $_obfuscate_Bk2lGlkÿ );
                if ( !is_array( $_obfuscate_hChFFlJCLh3o ) )
                {
                    $_obfuscate_hChFFlJCLh3o = array( );
                }
                $_obfuscate_qPt6frCIa6xJ0ogÿ = $CNOA_DB->db_select( "*", $this->t_use_step_huiqian, $_obfuscate_Bk2lGlkÿ );
                if ( !is_array( $_obfuscate_qPt6frCIa6xJ0ogÿ ) )
                {
                    $_obfuscate_qPt6frCIa6xJ0ogÿ = array( );
                }
                if ( $_obfuscate_hChFFlJCLh3o )
                {
                    foreach ( $_obfuscate_hChFFlJCLh3o as $_obfuscate_azM9k_Uÿ )
                    {
                        $_obfuscate_NuFUbhnhRkMÿ[] = $_obfuscate_F2cksfdRXAÿÿ = $_obfuscate_azM9k_Uÿ['uFenfaId'];
                        notice::deletenotice( $_obfuscate_F2cksfdRXAÿÿ, $_obfuscate_F2cksfdRXAÿÿ, $this->t_use_fenfa, "fenfa" );
                    }
                }
                if ( $_obfuscate_qPt6frCIa6xJ0ogÿ )
                {
                    foreach ( $_obfuscate_qPt6frCIa6xJ0ogÿ as $_obfuscate_ouqX2cxvhAÿÿ )
                    {
                        $_obfuscate_TeJEvUaP_x_1Twÿÿ[] = $_obfuscate_0W8ÿ = $_obfuscate_ouqX2cxvhAÿÿ['id'];
                        notice::deletenotice( $_obfuscate_0W8ÿ, $_obfuscate_0W8ÿ, $this->t_use_step_huiqian, "write" );
                    }
                }
            }
            if ( $_obfuscate_NuFUbhnhRkMÿ )
            {
                $_obfuscate_NuFUbhnhRkMÿ = implode( ",", $_obfuscate_NuFUbhnhRkMÿ );
                $CNOA_DB->db_delete( $this->t_use_fenfa, "WHERE `uFenfaId` IN(".$_obfuscate_NuFUbhnhRkMÿ.")" );
            }
            if ( $_obfuscate_TeJEvUaP_x_1Twÿÿ )
            {
                $_obfuscate_TeJEvUaP_x_1Twÿÿ = implode( ",", $_obfuscate_TeJEvUaP_x_1Twÿÿ );
                $CNOA_DB->db_delete( $this->t_use_step_huiqian, "WHERE `id` IN(".$_obfuscate_TeJEvUaP_x_1Twÿÿ.")" );
            }
        }
        $CNOA_DB->db_update( $_obfuscate_6RYLWQÿÿ, $this->t_use_step, "WHERE `id` IN (".$_obfuscate_BRPhVAÿÿ.") AND `uFlowId`={$_obfuscate_TlvKhtsoOQÿÿ} " );
        if ( $_obfuscate_v1oL == 1 )
        {
            $CNOA_DB->db_update( $_obfuscate_6RYLWQÿÿ, $this->t_use_step, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ." AND `uStepId`={$_obfuscate_ecqaH_ev7Aÿÿ} AND `status`=2 " );
        }
        if ( !empty( $_obfuscate_8CJrZgÿÿ ) )
        {
            $this->clearReturnOption( $_obfuscate_S0PSA37yAwÿÿ, $_obfuscate_TlvKhtsoOQÿÿ );
            $CNOA_DB->db_delete( $this->t_use_step, "WHERE `id` IN(".$_obfuscate_8CJrZgÿÿ.") AND `uFlowId`={$_obfuscate_TlvKhtsoOQÿÿ} " );
        }
        if ( !empty( $_obfuscate_Tz0Oow9UfYEsgÿÿ ) )
        {
            $_obfuscate_3y0Y = "UPDATE ".tname( "system_notice_list" )." SET `readed`=1 ".( "WHERE `fromid` IN (".$_obfuscate_Tz0Oow9UfYEsgÿÿ.")" );
            $CNOA_DB->query( $_obfuscate_3y0Y );
            $_obfuscate_3y0Y = "UPDATE ".tname( "system_notice_history" )." SET `isread`=1 ".( "WHERE `sourceid` IN (".$_obfuscate_Tz0Oow9UfYEsgÿÿ.")" );
            $CNOA_DB->query( $_obfuscate_3y0Y );
        }
        unset( $_obfuscate_8CJrZgÿÿ );
        unset( $_obfuscate_BRPhVAÿÿ );
        unset( $_obfuscate_Tz0Oow9UfYEsgÿÿ );
        $this->doneAll( "both", $_obfuscate_NenxpX50gwÿÿ['id'], "todo" );
        $this->doneAll( "notice", $_obfuscate_NenxpX50gwÿÿ['id'], "huiqian", 1 );
        if ( $_obfuscate_7qDAYo85aGAÿ['noticeAtGoBack'] != 4 )
        {
            $_obfuscate_6RYLWQÿÿ = array( );
            $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber", "flowId" ), $this->t_use_flow, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ );
            if ( $_obfuscate_XkuTFqZ6Tmkÿ == 0 )
            {
                $_obfuscate_qZkmBgÿÿ = $_obfuscate_e53ODz04JQÿÿ->getFlow( );
            }
            else
            {
                $_obfuscate_qZkmBgÿÿ = $CNOA_DB->db_getone( array( "noticeAtGoBack" ), $this->t_set_flow, "WHERE `flowId`=".$_obfuscate_hTew0boWJESy['flowId'] );
            }
            $_obfuscate_rLakms3x4ur74Qÿÿ = array( );
            foreach ( $_obfuscate_ZRB1fr3t0YMÿ as $_obfuscate_y6jH )
            {
                $_obfuscate_VBCv7Qÿÿ = $_obfuscate_j9eamhYÿ[$_obfuscate_y6jH];
                if ( !empty( $_obfuscate_VBCv7Qÿÿ['dealUid'] ) )
                {
                    $_obfuscate_rLakms3x4ur74Qÿÿ[] = $_obfuscate_VBCv7Qÿÿ['dealUid'];
                }
                $_obfuscate_6RYLWQÿÿ = array( );
                $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" ).( "[".$_obfuscate_hTew0boWJESy['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_hTew0boWJESy['flowNumber']."]" ).lang( "beReturnNeedReApp" )."<br /> ".lang( "returnReason" ).( ":[".$_obfuscate_1l6P."]" );
                $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_VBCv7Qÿÿ['uStepId']}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
                $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_VBCv7Qÿÿ['id'];
                $this->addNotice( "both", $_obfuscate_VBCv7Qÿÿ['dealUid'], $_obfuscate_6RYLWQÿÿ, "todo", 0 );
                $_obfuscate_v1oL = $CNOA_DB->db_getfield( "deal", $this->t_use_deal_way, "WHERE `stepId`=".$_obfuscate_y6jH." AND `flowId`={$_obfuscate_7qDAYo85aGAÿ['flowId']}" );
                if ( $_obfuscate_v1oL == 1 )
                {
                    $_obfuscate_CPwÿ = $CNOA_DB->db_select( array( "dealUid" ), $this->t_use_step, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ." AND `uStepId`={$_obfuscate_y6jH}" );
                    if ( !is_array( $_obfuscate_CPwÿ ) )
                    {
                        $_obfuscate_CPwÿ = array( );
                    }
                    foreach ( $_obfuscate_CPwÿ as $_obfuscate_5gÿÿ )
                    {
                        if ( !empty( $_obfuscate_5gÿÿ['dealUid'] ) )
                        {
                            if ( $_obfuscate_5gÿÿ['dealUid'] != $_obfuscate_VBCv7Qÿÿ['dealUid'] )
                            {
                                $this->addNotice( "both", $_obfuscate_5gÿÿ['dealUid'], $_obfuscate_6RYLWQÿÿ, "todo", 0 );
                            }
                        }
                    }
                }
            }
            if ( $_obfuscate_qZkmBgÿÿ['noticeAtGoBack'] == 2 )
            {
                $_obfuscate_VBCv7Qÿÿ = $_obfuscate_j9eamhYÿ[2];
                if ( !in_array( $_obfuscate_VBCv7Qÿÿ['dealUid'], $_obfuscate_rLakms3x4ur74Qÿÿ ) )
                {
                    $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" ).( "[".$_obfuscate_hTew0boWJESy['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_hTew0boWJESy['flowNumber']."]" ).lang( "beReturn" );
                    $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step=2&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
                    $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_VBCv7Qÿÿ['id'];
                    $this->addNotice( "notice", $_obfuscate_VBCv7Qÿÿ['dealUid'], $_obfuscate_6RYLWQÿÿ, "tuihui", 2 );
                }
            }
            else if ( $_obfuscate_qZkmBgÿÿ['noticeAtGoBack'] == 3 )
            {
                $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" ).( "[".$_obfuscate_hTew0boWJESy['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_hTew0boWJESy['flowNumber']."]" ).lang( "beReturn" );
                $_obfuscate_UcZOQ_tY828b1t7T8Aÿÿ = array( );
                foreach ( $_obfuscate_hsTQkq6NOXRA as $_obfuscate_VgKtFegÿ )
                {
                    if ( in_array( $_obfuscate_VgKtFegÿ['dealUid'], $_obfuscate_rLakms3x4ur74Qÿÿ ) || !empty( $_obfuscate_UcZOQ_tY828b1t7T8Aÿÿ[$_obfuscate_VgKtFegÿ['dealUid']] ) )
                    {
                        $_obfuscate_UcZOQ_tY828b1t7T8Aÿÿ[$_obfuscate_VgKtFegÿ['dealUid']] = $_obfuscate_VgKtFegÿ;
                    }
                }
                foreach ( $_obfuscate_UcZOQ_tY828b1t7T8Aÿÿ as $_obfuscate_VBCv7Qÿÿ )
                {
                    $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_VBCv7Qÿÿ['uStepId']}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
                    $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_VBCv7Qÿÿ['id'];
                    $this->addNotice( "notice", $_obfuscate_VBCv7Qÿÿ['dealUid'], $_obfuscate_6RYLWQÿÿ, "tuihui", 1 );
                }
            }
        }
        $_obfuscate_JG8GuYÿ = array( );
        $_obfuscate_JG8GuYÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
        $_obfuscate_JG8GuYÿ['type'] = 4;
        $_obfuscate_JG8GuYÿ['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuYÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuYÿ['say'] = $_obfuscate_1l6P;
        foreach ( $_obfuscate_ZRB1fr3t0YMÿ as $_obfuscate_y6jH )
        {
            $_obfuscate_VBCv7Qÿÿ = $_obfuscate_j9eamhYÿ[$_obfuscate_y6jH];
            $_obfuscate_JG8GuYÿ['step'] = $_obfuscate_y6jH;
            $_obfuscate_JG8GuYÿ['stepname'] = $_obfuscate_VBCv7Qÿÿ['stepname'];
            $this->insertEvent( $_obfuscate_JG8GuYÿ );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3604, lang( "backToFlowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _ms_loadTemplateFile( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", 0 );
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_GET, "tplSort", 0 );
        $_obfuscate_o6LA2yPirJIreFAÿ = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQÿÿ}/doc.history.0.php" );
        $_obfuscate_U9NJHZRq6Jr7T_Aÿ = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQÿÿ}/xls.history.0.php" );
        if ( $_obfuscate_pEvU7Kz2Ywÿÿ == 1 || $_obfuscate_pEvU7Kz2Ywÿÿ == 3 )
        {
            if ( file_exists( $_obfuscate_o6LA2yPirJIreFAÿ ) )
            {
                $_obfuscate_6hS1Rwÿÿ = @file_get_contents( $_obfuscate_o6LA2yPirJIreFAÿ );
            }
            else
            {
                mkdirs( dirname( $_obfuscate_o6LA2yPirJIreFAÿ ) );
                $_obfuscate_6hS1Rwÿÿ = " ";
            }
        }
        else if ( file_exists( $_obfuscate_U9NJHZRq6Jr7T_Aÿ ) )
        {
            $_obfuscate_6hS1Rwÿÿ = @file_get_contents( $_obfuscate_U9NJHZRq6Jr7T_Aÿ );
        }
        else
        {
            mkdirs( dirname( $_obfuscate_U9NJHZRq6Jr7T_Aÿ ) );
            $_obfuscate_6hS1Rwÿÿ = " ";
        }
        echo $_obfuscate_6hS1Rwÿÿ;
        exit( );
    }

    private function _ms_submitMsOfficeData( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", 0 );
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_GET, "tplSort", 0 );
        $_obfuscate_zfubNC9lKJsÿ = strtolower( strrchr( $_FILES['msOffice']['name'], "." ) );
        if ( !in_array( strtolower( $_obfuscate_zfubNC9lKJsÿ ), array( ".doc", ".docx", ".xls", ".xlsx" ) ) )
        {
            echo "0";
            exit( );
        }
        if ( $_obfuscate_pEvU7Kz2Ywÿÿ == "1" || $_obfuscate_pEvU7Kz2Ywÿÿ == "3" )
        {
            $_obfuscate_7tmDAr7acvgDxwÿÿ = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQÿÿ}/doc.history.0.php" );
            mkdirs( dirname( $_obfuscate_7tmDAr7acvgDxwÿÿ ) );
            if ( !cnoa_move_uploaded_file( $_FILES['msOffice']['tmp_name'], $_obfuscate_7tmDAr7acvgDxwÿÿ ) )
            {
                echo "0";
                exit( );
            }
        }
        else
        {
            $_obfuscate_7tmDAr7acvgDxwÿÿ = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQÿÿ}/xls.history.0.php" );
            mkdirs( dirname( $_obfuscate_7tmDAr7acvgDxwÿÿ ) );
            if ( !cnoa_move_uploaded_file( $_FILES['msOffice']['tmp_name'], $_obfuscate_7tmDAr7acvgDxwÿÿ ) )
            {
                echo "0";
                exit( );
            }
        }
        echo "1";
        exit( );
    }

    private function _seqFlowSendNextStep( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "step", 0 );
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_LeS8hwÿÿ = getpar( $_POST, "type", 0 );
        $_obfuscate_1l6P = getpar( $_POST, "say", "" );
        $_obfuscate_5RySNZO3T0kÿ = getpar( $_POST, "htmlFormContent", "", 1, 0 );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_POST, "tplSort", 0 );
        $thisStep = $CNOA_DB->db_getone( array( "id", "stepname" ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId`='{$_obfuscate_0Ul8BBkt}'" );
        $this->doneAll( "both", $thisStep['id'], "todo" );
        $this->doneAll( "notice", $thisStep['id'], "huiqian", 1 );
        $_obfuscate_EyPNUV5TiETe = $CNOA_DB->db_select( array( "id" ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' " );
        if ( !is_array( $_obfuscate_EyPNUV5TiETe ) )
        {
            $_obfuscate_EyPNUV5TiETe = array( );
        }
        foreach ( $_obfuscate_EyPNUV5TiETe as $_obfuscate_6Aÿÿ )
        {
            $this->doneAll( "both", $_obfuscate_6Aÿÿ['id'], "huiqian" );
        }
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $_obfuscate_UTaQ0tZbOZ2slLemr40_Qÿÿ = 0;
        $_obfuscate_IuoXR2yOaxkRDwÿÿ = array( );
        foreach ( $GLOBALS['_POST'] as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( ereg( "wf_attach_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_IuoXR2yOaxkRDwÿÿ[] = intval( str_replace( "wf_attach_", "", $_obfuscate_5wÿÿ ) );
            }
        }
        ( );
        $_obfuscate_2ggÿ = new fs( );
        $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ = json_decode( $_obfuscate_hTew0boWJESy['attach'], TRUE );
        if ( !is_array( $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ ) )
        {
            $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ = array( );
        }
        $_obfuscate_1ERfSWbp = $_obfuscate_2ggÿ->uploadFile4Wf( $_obfuscate_IuoXR2yOaxkRDwÿÿ, $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ );
        $_obfuscate_8CpDPPa = json_encode( $_obfuscate_1ERfSWbp[0] );
        $_obfuscate_hYX302lGYvY5 = $CNOA_DB->db_getone( array( "id", "nStepId", "uid" ), $this->t_use_step, ( "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId`='".( $_obfuscate_0Ul8BBkt + 1 ) )."'" );
        if ( $_obfuscate_hYX302lGYvY5['nStepId'] == 0 )
        {
            $_obfuscate_L9PX7r5kCPQÿ = "done";
            $_obfuscate_VBCv7Qÿÿ = array( );
            $_obfuscate_VBCv7Qÿÿ['status'] = 2;
            $_obfuscate_VBCv7Qÿÿ['etime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $_obfuscate_VBCv7Qÿÿ['say'] = $_obfuscate_1l6P;
            $_obfuscate_VBCv7Qÿÿ['dealUid'] = $_obfuscate_7Ri3;
            $_obfuscate_VBCv7Qÿÿ['stepType'] = 2;
            $CNOA_DB->db_update( $_obfuscate_VBCv7Qÿÿ, $this->t_use_step, "WHERE uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ." AND `uStepId`='{$_obfuscate_0Ul8BBkt}'" );
            $_obfuscate_kVqhf0IeMgÿÿ = array( );
            $_obfuscate_kVqhf0IeMgÿÿ['status'] = 2;
            $_obfuscate_kVqhf0IeMgÿÿ['stepType'] = 3;
            $CNOA_DB->db_update( $_obfuscate_kVqhf0IeMgÿÿ, $this->t_use_step, ( "WHERE uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ." AND `uStepId`='".( $_obfuscate_0Ul8BBkt + 1 ) )."'" );
            $_obfuscate_JG8GuYÿ = array( );
            $_obfuscate_JG8GuYÿ['type'] = 2;
            $_obfuscate_JG8GuYÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
            $_obfuscate_JG8GuYÿ['step'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_JG8GuYÿ['stepname'] = "ç»“æŸ";
            $_obfuscate_JG8GuYÿ['say'] = $_obfuscate_1l6P;
            $_obfuscate_JG8GuYÿ['uid'] = $_obfuscate_7Ri3;
            $_obfuscate_JG8GuYÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $this->insertEvent( $_obfuscate_JG8GuYÿ );
            $_obfuscate_qZkmBgÿÿ = array( );
            $_obfuscate_qZkmBgÿÿ['status'] = 2;
            $_obfuscate_qZkmBgÿÿ['attach'] = $_obfuscate_8CpDPPa;
            $_obfuscate_qZkmBgÿÿ['htmlFormContent'] = $_obfuscate_5RySNZO3T0kÿ;
            $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_hTew0boWJESy['flowId']."'" );
            if ( $_obfuscate_7qDAYo85aGAÿ['noticeAtFinish'] == 1 )
            {
                $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "flowOver" );
                $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
                $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_hYX302lGYvY5['id'];
                $this->addNotice( "notice", $_obfuscate_hTew0boWJESy['uid'], $_obfuscate_6RYLWQÿÿ, "done" );
            }
            else if ( $_obfuscate_7qDAYo85aGAÿ['noticeAtFinish'] == 2 )
            {
                $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "flowOver" );
                $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
                $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_hYX302lGYvY5['id'];
                $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "dealUid" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
                if ( !is_array( $_obfuscate_Tx7M9W ) )
                {
                    $_obfuscate_Tx7M9W = array( );
                }
                foreach ( $_obfuscate_Tx7M9W as $_obfuscate_6Aÿÿ )
                {
                    $this->addNotice( "notice", $_obfuscate_6Aÿÿ['dealUid'], $_obfuscate_6RYLWQÿÿ, "done" );
                }
            }
        }
        else
        {
            $_obfuscate_L9PX7r5kCPQÿ = "todo";
            $_obfuscate_UTaQ0tZbOZ2slLemr40_Qÿÿ = $this->getProxyUid( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt + 1 );
            $_obfuscate_RNvHv6KVxd_drYca6gd = array( );
            if ( $_obfuscate_LeS8hwÿÿ == "agree" )
            {
                $_obfuscate_RNvHv6KVxd_drYca6gd['status'] = 2;
            }
            else
            {
                $_obfuscate_RNvHv6KVxd_drYca6gd['status'] = 4;
            }
            $_obfuscate_RNvHv6KVxd_drYca6gd['say'] = $_obfuscate_1l6P;
            $_obfuscate_RNvHv6KVxd_drYca6gd['etime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $_obfuscate_RNvHv6KVxd_drYca6gd['dealUid'] = $_obfuscate_7Ri3;
            $_obfuscate_RNvHv6KVxd_drYca6gd['stepType'] = 2;
            $CNOA_DB->db_update( $_obfuscate_RNvHv6KVxd_drYca6gd, $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId`='{$_obfuscate_0Ul8BBkt}'" );
            $_obfuscate_NeEP0UTNX62DrQn = array( );
            $_obfuscate_NeEP0UTNX62DrQn['status'] = 1;
            $_obfuscate_NeEP0UTNX62DrQn['stime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $_obfuscate_NeEP0UTNX62DrQn['stepType'] = 2;
            $CNOA_DB->db_update( $_obfuscate_NeEP0UTNX62DrQn, $this->t_use_step, ( "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId`='".( $_obfuscate_0Ul8BBkt + 1 ) )."'" );
            $_obfuscate_JG8GuYÿ = array( );
            if ( $_obfuscate_LeS8hwÿÿ == "agree" )
            {
                $_obfuscate_JG8GuYÿ['type'] = 2;
            }
            else
            {
                $_obfuscate_JG8GuYÿ['type'] = 9;
            }
            $_obfuscate_JG8GuYÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
            $_obfuscate_JG8GuYÿ['step'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_JG8GuYÿ['stepname'] = $thisStep['stepname'];
            $_obfuscate_JG8GuYÿ['uid'] = $_obfuscate_7Ri3;
            $_obfuscate_JG8GuYÿ['say'] = $_obfuscate_1l6P;
            $_obfuscate_JG8GuYÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $this->insertEvent( $_obfuscate_JG8GuYÿ );
            $_obfuscate_qZkmBgÿÿ = array( );
            $_obfuscate_qZkmBgÿÿ['htmlFormContent'] = $_obfuscate_5RySNZO3T0kÿ;
            $_obfuscate_qZkmBgÿÿ['status'] = 1;
            $_obfuscate_qZkmBgÿÿ['attach'] = $_obfuscate_8CpDPPa;
            $_obfuscate_fgY2Vw2IN54w9wÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]ç¼–å·[".$_obfuscate_hTew0boWJESy['flowNumber']."]éœ€è¦æ‚¨å®¡æ‰¹";
            $_obfuscate_fgY2Vw2IN54w9wÿÿ['href'] = ( "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_F4AbnVRh}&step=".( $_obfuscate_0Ul8BBkt + 1 ) ).( "&flowType=".$_obfuscate_XkuTFqZ6Tmkÿ."&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}" );
            $_obfuscate_fgY2Vw2IN54w9wÿÿ['fromid'] = $_obfuscate_hYX302lGYvY5['id'];
            $this->addNotice( "both", array(
                $_obfuscate_hYX302lGYvY5['uid'],
                $_obfuscate_UTaQ0tZbOZ2slLemr40_Qÿÿ
            ), $_obfuscate_fgY2Vw2IN54w9wÿÿ, "todo" );
        }
        $CNOA_DB->db_update( $_obfuscate_qZkmBgÿÿ, $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = array(
            "stepType" => $_obfuscate_L9PX7r5kCPQÿ
        );
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _freeFlowSendNextStep( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_GET, "step", 0 );
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_LeS8hwÿÿ = getpar( $_POST, "type", "agree" );
        $_obfuscate_Xthk75oEMy4ÿ = getpar( $_POST, "stepname", "" );
        $_obfuscate_PodmRNAQbwÿÿ = getpar( $_POST, "dealuid", 0 );
        $_obfuscate_1l6P = getpar( $_POST, "say", "" );
        $_obfuscate_5RySNZO3T0kÿ = getpar( $_POST, "htmlFormContent", "", 1, 0 );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_a9lP = getpar( $_POST, "sms", "false" );
        $_obfuscate_a9lP = $_obfuscate_a9lP == "false" ? FALSE : TRUE;
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $thisStep = $CNOA_DB->db_getone( array( "id" ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId`='{$_obfuscate_0Ul8BBkt}'" );
        $this->doneAll( "both", $thisStep['id'], "todo" );
        $this->doneAll( "notice", $thisStep['id'], "huiqian", 1 );
        $_obfuscate_EyPNUV5TiETe = $CNOA_DB->db_select( array( "id" ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' " );
        if ( !is_array( $_obfuscate_EyPNUV5TiETe ) )
        {
            $_obfuscate_EyPNUV5TiETe = array( );
        }
        foreach ( $_obfuscate_EyPNUV5TiETe as $_obfuscate_6Aÿÿ )
        {
            $this->doneAll( "both", $_obfuscate_6Aÿÿ['id'], "huiqian" );
        }
        $_obfuscate_UTaQ0tZbOZ2slLemr40_Qÿÿ = 0;
        $_obfuscate_UTaQ0tZbOZ2slLemr40_Qÿÿ = $this->getProxyUid( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt + 1 );
        $_obfuscate_IuoXR2yOaxkRDwÿÿ = array( );
        foreach ( $GLOBALS['_POST'] as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( ereg( "wf_attach_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_IuoXR2yOaxkRDwÿÿ[] = intval( str_replace( "wf_attach_", "", $_obfuscate_5wÿÿ ) );
            }
        }
        ( );
        $_obfuscate_2ggÿ = new fs( );
        $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ = json_decode( $_obfuscate_hTew0boWJESy['attach'], TRUE );
        if ( !is_array( $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ ) )
        {
            $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ = array( );
        }
        $_obfuscate_1ERfSWbp = $_obfuscate_2ggÿ->uploadFile4Wf( $_obfuscate_IuoXR2yOaxkRDwÿÿ, $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ );
        $_obfuscate_8CpDPPa = json_encode( $_obfuscate_1ERfSWbp[0] );
        $_obfuscate_OOSxy1QQ55_QiMÿ = array( );
        if ( $_obfuscate_LeS8hwÿÿ == "agree" )
        {
            $_obfuscate_OOSxy1QQ55_QiMÿ['status'] = 2;
        }
        else
        {
            $_obfuscate_OOSxy1QQ55_QiMÿ['status'] = 4;
        }
        $_obfuscate_OOSxy1QQ55_QiMÿ['etime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_OOSxy1QQ55_QiMÿ['say'] = $_obfuscate_1l6P;
        $_obfuscate_OOSxy1QQ55_QiMÿ['dealUid'] = $_obfuscate_7Ri3;
        $_obfuscate_OOSxy1QQ55_QiMÿ['stepType'] = 2;
        $CNOA_DB->db_update( $_obfuscate_OOSxy1QQ55_QiMÿ, $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId`='{$_obfuscate_0Ul8BBkt}'" );
        $_obfuscate_MnVVbyZQFVwÿ = array( );
        $_obfuscate_MnVVbyZQFVwÿ['uStepId'] = $_obfuscate_0Ul8BBkt + 1;
        $_obfuscate_MnVVbyZQFVwÿ['pStepId'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_MnVVbyZQFVwÿ['nStepId'] = $_obfuscate_0Ul8BBkt + 2;
        $_obfuscate_MnVVbyZQFVwÿ['stepname'] = $_obfuscate_Xthk75oEMy4ÿ;
        $_obfuscate_MnVVbyZQFVwÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
        $_obfuscate_MnVVbyZQFVwÿ['uid'] = $_obfuscate_PodmRNAQbwÿÿ;
        $_obfuscate_MnVVbyZQFVwÿ['status'] = 1;
        $_obfuscate_MnVVbyZQFVwÿ['stime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_MnVVbyZQFVwÿ['stepType'] = 2;
        $_obfuscate_6l4kzHfz = $CNOA_DB->db_insert( $_obfuscate_MnVVbyZQFVwÿ, $this->t_use_step );
        $_obfuscate_JG8GuYÿ = array( );
        $_obfuscate_JG8GuYÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
        $_obfuscate_JG8GuYÿ['step'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_JG8GuYÿ['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuYÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuYÿ['say'] = $_obfuscate_1l6P;
        $_obfuscate_JG8GuYÿ['stepname'] = $_obfuscate_Xthk75oEMy4ÿ;
        if ( $_obfuscate_LeS8hwÿÿ == "agree" )
        {
            $_obfuscate_JG8GuYÿ['type'] = 2;
        }
        else
        {
            $_obfuscate_JG8GuYÿ['type'] = 9;
        }
        $this->insertEvent( $_obfuscate_JG8GuYÿ );
        $_obfuscate_qZkmBgÿÿ = array( );
        $_obfuscate_qZkmBgÿÿ['htmlFormContent'] = $_obfuscate_5RySNZO3T0kÿ;
        $_obfuscate_qZkmBgÿÿ['status'] = 1;
        $_obfuscate_qZkmBgÿÿ['attach'] = $_obfuscate_8CpDPPa;
        $CNOA_DB->db_update( $_obfuscate_qZkmBgÿÿ, $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $_obfuscate_fgY2Vw2IN54w9wÿÿ = array( );
        $_obfuscate_fgY2Vw2IN54w9wÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]ç¼–å·[".$_obfuscate_hTew0boWJESy['flowNumber']."]éœ€è¦æ‚¨å®¡æ‰¹";
        $_obfuscate_fgY2Vw2IN54w9wÿÿ['href'] = ( "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_F4AbnVRh}&step=".( $_obfuscate_0Ul8BBkt + 1 ) ).( "&flowType=".$_obfuscate_XkuTFqZ6Tmkÿ."&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}" );
        $_obfuscate_fgY2Vw2IN54w9wÿÿ['fromid'] = $_obfuscate_6l4kzHfz;
        $this->addNotice( "both", array(
            $_obfuscate_PodmRNAQbwÿÿ,
            $_obfuscate_UTaQ0tZbOZ2slLemr40_Qÿÿ
        ), $_obfuscate_fgY2Vw2IN54w9wÿÿ, "todo" );
        if ( $_obfuscate_a9lP )
        {
            ( );
            $_obfuscate_a9lP = new sms( );
            $_obfuscate_a9lP->sendByUids( array(
                $_obfuscate_PodmRNAQbwÿÿ,
                $_obfuscate_UTaQ0tZbOZ2slLemr40_Qÿÿ
            ), $_obfuscate_fgY2Vw2IN54w9wÿÿ['content'], 0, "flow", 0 );
        }
        $this->doneAll( "both", $thisStep, "todo" );
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = array(
            "uFlowId" => $_obfuscate_TlvKhtsoOQÿÿ
        );
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _finishFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_GET, "stepId", 0 );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_1l6P = getpar( $_POST, "say", "" );
        $_obfuscate_5RySNZO3T0kÿ = getpar( $_POST, "htmlFormContent", "", 1, 0 );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_POST, "tplSort", 0 );
        $thisStep = $CNOA_DB->db_getone( array( "id" ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId`='{$_obfuscate_0Ul8BBkt}'" );
        $this->doneAll( "both", $thisStep['id'], "todo" );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $_obfuscate_IuoXR2yOaxkRDwÿÿ = array( );
        foreach ( $GLOBALS['_POST'] as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( ereg( "wf_attach_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_IuoXR2yOaxkRDwÿÿ[] = intval( str_replace( "wf_attach_", "", $_obfuscate_5wÿÿ ) );
            }
        }
        ( );
        $_obfuscate_2ggÿ = new fs( );
        $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ = json_decode( $_obfuscate_hTew0boWJESy['attach'], TRUE );
        if ( !is_array( $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ ) )
        {
            $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ = array( );
        }
        $_obfuscate_1ERfSWbp = $_obfuscate_2ggÿ->uploadFile4Wf( $_obfuscate_IuoXR2yOaxkRDwÿÿ, $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ );
        $_obfuscate_8CpDPPa = json_encode( $_obfuscate_1ERfSWbp[0] );
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_6RYLWQÿÿ['dealUid'] = $_obfuscate_7Ri3;
        $_obfuscate_6RYLWQÿÿ['status'] = 2;
        $_obfuscate_6RYLWQÿÿ['etime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_6RYLWQÿÿ['say'] = $_obfuscate_1l6P;
        $_obfuscate_6RYLWQÿÿ['stepType'] = 2;
        $CNOA_DB->db_update( $_obfuscate_6RYLWQÿÿ, $this->t_use_step, "WHERE `uStepId`='".$_obfuscate_0Ul8BBkt."' AND `uFlowId`='{$_obfuscate_TlvKhtsoOQÿÿ}'" );
        $_obfuscate_kVqhf0IeMgÿÿ = array( );
        $_obfuscate_kVqhf0IeMgÿÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
        $_obfuscate_kVqhf0IeMgÿÿ['uStepId'] = $_obfuscate_0Ul8BBkt + 1;
        $_obfuscate_kVqhf0IeMgÿÿ['status'] = 2;
        $_obfuscate_kVqhf0IeMgÿÿ['pStepId'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_kVqhf0IeMgÿÿ['nStepId'] = 0;
        $_obfuscate_kVqhf0IeMgÿÿ['uid'] = 0;
        $_obfuscate_kVqhf0IeMgÿÿ['stepname'] = "ç»“æŸ";
        $_obfuscate_6RYLWQÿÿ['stepType'] = 3;
        $CNOA_DB->db_insert( $_obfuscate_kVqhf0IeMgÿÿ, $this->t_use_step );
        $_obfuscate_JG8GuYÿ = array( );
        $_obfuscate_JG8GuYÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
        $_obfuscate_JG8GuYÿ['type'] = 2;
        $_obfuscate_JG8GuYÿ['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuYÿ['stepname'] = "ç»“æŸ";
        $_obfuscate_JG8GuYÿ['step'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_JG8GuYÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuYÿ['say'] = $_obfuscate_1l6P;
        $CNOA_DB->db_insert( $_obfuscate_JG8GuYÿ, $this->t_use_event );
        $_obfuscate_qZkmBgÿÿ = array( );
        $_obfuscate_qZkmBgÿÿ['status'] = 2;
        $_obfuscate_qZkmBgÿÿ['htmlFormContent'] = $_obfuscate_5RySNZO3T0kÿ;
        $_obfuscate_qZkmBgÿÿ['attach'] = $_obfuscate_8CpDPPa;
        $CNOA_DB->db_update( $_obfuscate_qZkmBgÿÿ, $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_hTew0boWJESy['flowId']."'" );
        if ( $_obfuscate_7qDAYo85aGAÿ['noticeAtFinish'] == 1 )
        {
            $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "flowOver" );
            $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_7qDAYo85aGAÿ['flowId']}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
            $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_0Ul8BBkt;
            $this->addNotice( "notice", $_obfuscate_hTew0boWJESy['uid'], $_obfuscate_6RYLWQÿÿ, "done" );
        }
        else if ( $_obfuscate_7qDAYo85aGAÿ['noticeAtFinish'] == 2 )
        {
            $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "flowOver" );
            $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_7qDAYo85aGAÿ['flowId']}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
            $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "dealUid" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
            if ( !is_array( $_obfuscate_Tx7M9W ) )
            {
                $_obfuscate_Tx7M9W = array( );
            }
            foreach ( $_obfuscate_Tx7M9W as $_obfuscate_6Aÿÿ )
            {
                $this->addNotice( "notice", $_obfuscate_6Aÿÿ['dealUid'], $_obfuscate_6RYLWQÿÿ, "done" );
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getChildFlowJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_GET, "stepId", 0 );
        $_obfuscate_fKTc3pvqkNsÿ = $CNOA_DB->db_getfield( "uid", $this->t_use_step, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ." AND `uStepId`={$_obfuscate_0Ul8BBkt} AND `proxyUid`={$_obfuscate_7Ri3}" );
        if ( empty( $_obfuscate_fKTc3pvqkNsÿ ) )
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_step_child_flow, "WHERE `puFlowId` = ".$_obfuscate_TlvKhtsoOQÿÿ." AND `stepId` = {$_obfuscate_0Ul8BBkt} AND `postuid` = {$_obfuscate_7Ri3} " );
        }
        else
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_step_child_flow, "WHERE `puFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ." AND `stepId`={$_obfuscate_0Ul8BBkt} AND `postuid`={$_obfuscate_fKTc3pvqkNsÿ}" );
        }
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_l2CIvUX0Kvp4 = array( 0 );
        $_obfuscate_81hHUJ9pJKTIlQÿÿ = array( 0 );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_l2CIvUX0Kvp4[] = $_obfuscate_6Aÿÿ['flowId'];
            $_obfuscate_81hHUJ9pJKTIlQÿÿ[] = $_obfuscate_6Aÿÿ['faqiUid'];
            if ( empty( $_obfuscate_6Aÿÿ['status'] ) )
            {
                $_obfuscate_vzHuN2c = $this->__formatChildFlowUserJsonData( $_obfuscate_6Aÿÿ['flowId'] );
                $_obfuscate_Ybai = count( $_obfuscate_vzHuN2c );
                if ( empty( $_obfuscate_Ybai ) )
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['operate'] = 1;
                }
                else if ( $_obfuscate_Ybai == 1 )
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['operate'] = 2;
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['operatename'] = $_obfuscate_vzHuN2c[0]['truename'];
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['operateuid'] = $_obfuscate_vzHuN2c[0]['uid'];
                }
                else
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['operate'] = 0;
                }
            }
            else if ( $_obfuscate_6Aÿÿ['status'] == 2 )
            {
                $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "stepType", "stepname", "uid", "proxyUid", "dealUid" ), $this->t_use_step, "WHERE `uFlowId` = ".$_obfuscate_6Aÿÿ['uFlowId']." AND `status` = 1 " );
                if ( !is_array( $_obfuscate_Tx7M9W ) )
                {
                    $_obfuscate_Tx7M9W = array( );
                }
                foreach ( $_obfuscate_Tx7M9W as $_obfuscate_ty0ÿ => $_obfuscate_snMÿ )
                {
                    $_obfuscate_QwT4KwrB2wÿÿ[$_obfuscate_6Aÿÿ['id']][] = array(
                        "stepname" => $_obfuscate_snMÿ['stepname'],
                        "stepType" => $_obfuscate_snMÿ['stepType'],
                        "uid" => $_obfuscate_snMÿ['uid'],
                        "proxyUid" => $_obfuscate_snMÿ['proxyUid']
                    );
                    $_obfuscate_81hHUJ9pJKTIlQÿÿ[] = $_obfuscate_snMÿ['uid'];
                    $_obfuscate_81hHUJ9pJKTIlQÿÿ[] = $_obfuscate_snMÿ['proxyUid'];
                    $_obfuscate_81hHUJ9pJKTIlQÿÿ[] = $_obfuscate_snMÿ['dealUid'];
                }
            }
        }
        $_obfuscate_GCfDSanL49WUEAÿÿ = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_81hHUJ9pJKTIlQÿÿ );
        $_obfuscate_SIUSR4F6 = $CNOA_DB->db_select( array( "name", "flowId" ), $this->t_set_flow, "WHERE `flowId` IN (".implode( ",", $_obfuscate_l2CIvUX0Kvp4 ).") " );
        if ( !is_array( $_obfuscate_SIUSR4F6 ) )
        {
            $_obfuscate_SIUSR4F6 = array( );
        }
        foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_R7khXq3cT4VZ[$_obfuscate_6Aÿÿ['flowId']] = $_obfuscate_6Aÿÿ['name'];
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['name'] = $_obfuscate_R7khXq3cT4VZ[$_obfuscate_6Aÿÿ['flowId']];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['faqiname'] = $_obfuscate_GCfDSanL49WUEAÿÿ[$_obfuscate_6Aÿÿ['faqiUid']]['truename'];
            $_obfuscate_Ybai = count( $_obfuscate_QwT4KwrB2wÿÿ[$_obfuscate_6Aÿÿ['id']] );
            if ( $_obfuscate_Ybai == 1 )
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['stepname'] = $_obfuscate_QwT4KwrB2wÿÿ[$_obfuscate_6Aÿÿ['id']][0]['stepname'];
                $_obfuscate_VZzSMXQx6Qÿÿ = $_obfuscate_QwT4KwrB2wÿÿ[$_obfuscate_6Aÿÿ['id']][0]['uid'];
                if ( empty( $_obfuscate_VZzSMXQx6Qÿÿ ) )
                {
                    $_obfuscate_VZzSMXQx6Qÿÿ = $_obfuscate_QwT4KwrB2wÿÿ[$_obfuscate_6Aÿÿ['id']][0]['proxyUid'];
                }
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['stepuid'] = $_obfuscate_GCfDSanL49WUEAÿÿ[$_obfuscate_VZzSMXQx6Qÿÿ]['truename'];
            }
            else if ( 1 < $_obfuscate_Ybai )
            {
                foreach ( $_obfuscate_QwT4KwrB2wÿÿ[$_obfuscate_6Aÿÿ['id']] as $_obfuscate_ty0ÿ => $_obfuscate_snMÿ )
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['stepname'] .= $_obfuscate_snMÿ['stepname'];
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['stepname'] .= "<br />";
                    $_obfuscate_VZzSMXQx6Qÿÿ = $_obfuscate_snMÿ['uid'];
                    if ( empty( $_obfuscate_VZzSMXQx6Qÿÿ ) )
                    {
                        $_obfuscate_VZzSMXQx6Qÿÿ = $_obfuscate_6Aÿÿ['proxyUid'];
                    }
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['stepuid'] .= $_obfuscate_GCfDSanL49WUEAÿÿ[$_obfuscate_VZzSMXQx6Qÿÿ]['truename'];
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['stepuid'] .= "<br />";
                }
            }
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _childFaqi( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate__e3924lsxQÿÿ = $CNOA_SESSION->get( "UID" );
        $_obfuscate_0W8ÿ = getpar( $_POST, "id", 0 );
        $_obfuscate_tC8MNsAzXAÿÿ = getpar( $_POST, "uid", $_obfuscate__e3924lsxQÿÿ );
        $_obfuscate_Ia59JpJlk_Wd = $CNOA_DB->db_getone( array( "puFlowId", "flowId", "faqiFlow" ), $this->t_use_step_child_flow, "WHERE `id` = ".$_obfuscate_0W8ÿ );
        if ( $_obfuscate_Ia59JpJlk_Wd['faqiFlow'] == "myself" )
        {
            $_obfuscate_tC8MNsAzXAÿÿ = $_obfuscate__e3924lsxQÿÿ;
        }
        $CNOA_DB->db_update( array(
            "status" => 1,
            "faqiUid" => $_obfuscate_tC8MNsAzXAÿÿ
        ), $this->t_use_step_child_flow, "WHERE `id` = ".$_obfuscate_0W8ÿ );
        $_obfuscate_qZkmBgÿÿ = $CNOA_DB->db_getone( array( "flowNumber", "flowName" ), $this->t_use_flow, "WHERE `uFlowId` = ".$_obfuscate_Ia59JpJlk_Wd['puFlowId']." " );
        $_obfuscate_iDvjm2Mÿ = $CNOA_DB->db_getone( array( "nameRule", "nameRuleId", "tplSort", "flowType" ), $this->t_set_flow, "WHERE `flowId` = ".$_obfuscate_Ia59JpJlk_Wd['flowId']." " );
        $_obfuscate_TMoT['content'] = lang( "flowName" )."[".$_obfuscate_qZkmBgÿÿ['flowName']."]".lang( "bianHao" )."[".$_obfuscate_qZkmBgÿÿ['flowNumber']."]".lang( "stepDealPerople" )."[".$CNOA_SESSION->get( "TRUENAME" )."]".lang( "buZhiSubflowReInit" );
        $_obfuscate_TMoT['href'] = "&flowId=".$_obfuscate_Ia59JpJlk_Wd['flowId']."&nameRuleId=".$_obfuscate_iDvjm2Mÿ['nameRuleId']."&flowType=".$_obfuscate_iDvjm2Mÿ['flowType']."&tplSort=".$_obfuscate_iDvjm2Mÿ['tplSort']."&childId=".$_obfuscate_0W8ÿ;
        $_obfuscate_TMoT['fromid'] = $_obfuscate_0W8ÿ;
        $this->addNotice( "both", $_obfuscate_tC8MNsAzXAÿÿ, $_obfuscate_TMoT, "child" );
        $_obfuscate_JG8GuYÿ = array( );
        $_obfuscate_JG8GuYÿ['type'] = 12;
        $_obfuscate_JG8GuYÿ['uFlowId'] = $_obfuscate_Ia59JpJlk_Wd['puFlowId'];
        $_obfuscate_JG8GuYÿ['step'] = $_obfuscate_Ia59JpJlk_Wd['stepId'];
        $_obfuscate_JG8GuYÿ['stepname'] = "å­æµç¨‹å¸ƒç½®";
        $_obfuscate_JG8GuYÿ['say'] = "";
        $_obfuscate_JG8GuYÿ['uid'] = $_obfuscate__e3924lsxQÿÿ;
        $_obfuscate_JG8GuYÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $this->insertEvent( $_obfuscate_JG8GuYÿ );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getChildFlowUserJsonData( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_POST, "cflowId", 0 );
        $_obfuscate_phKp89pDgwQÿ = $this->__formatChildFlowUserJsonData( $_obfuscate_F4AbnVRh );
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_phKp89pDgwQÿ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function __formatChildFlowUserJsonData( $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_step_user, "WHERE `flowId` = ".$_obfuscate_F4AbnVRh." AND `firstStep` = 1 " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['type'] == "people" )
            {
                $_obfuscate_PVLK5jra[] = $_obfuscate_6Aÿÿ['people'];
            }
            else if ( $_obfuscate_6Aÿÿ['type'] == "station" )
            {
                $_obfuscate_MtpzvDgUD7YblQÿÿ[] = $_obfuscate_6Aÿÿ['station'];
            }
            else if ( $_obfuscate_6Aÿÿ['type'] == "dept" )
            {
                $_obfuscate_Lw9wXKzqBgÿÿ[] = $_obfuscate_6Aÿÿ['dept'];
            }
            else if ( $_obfuscate_6Aÿÿ['type'] == "deptstation" )
            {
                $_obfuscate_Qoa1pKbG2zuGkblGUZmZPwÿÿ[] = $_obfuscate_6Aÿÿ['dept'];
                $_obfuscate_UsUCkyqS6df49QRFnhyG9Aÿÿ[] = $_obfuscate_6Aÿÿ['station'];
            }
            else
            {
                $_obfuscate_phKp89pDgwQÿ = app::loadapp( "main", "user" )->api_getAllUserInfo( );
            }
        }
        $_obfuscate_SeV31Qÿÿ = array( );
        if ( isset( $_obfuscate_PVLK5jra ) )
        {
            $_obfuscate_qSP4Gcÿ = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_PVLK5jra );
            foreach ( $_obfuscate_qSP4Gcÿ as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_SeV31Qÿÿ[$_obfuscate_6Aÿÿ['uid']] = $_obfuscate_6Aÿÿ['truename'];
            }
        }
        if ( isset( $_obfuscate_MtpzvDgUD7YblQÿÿ ) )
        {
            $_obfuscate_uLf44wk1NRqS = app::loadapp( "main", "user" )->api_getUserNamesByStationIds( $_obfuscate_MtpzvDgUD7YblQÿÿ );
            foreach ( $_obfuscate_uLf44wk1NRqS as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_SeV31Qÿÿ[$_obfuscate_6Aÿÿ['uid']] = $_obfuscate_6Aÿÿ['truename'];
            }
        }
        if ( isset( $_obfuscate_Lw9wXKzqBgÿÿ ) )
        {
            $_obfuscate_Lw9wXKzqBgÿÿ = app::loadapp( "main", "user" )->api_getUserByFids( $_obfuscate_Lw9wXKzqBgÿÿ );
            foreach ( $_obfuscate_Lw9wXKzqBgÿÿ as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_SeV31Qÿÿ[$_obfuscate_6Aÿÿ['uid']] = $_obfuscate_6Aÿÿ['truename'];
            }
        }
        if ( isset( $_obfuscate_Qoa1pKbG2zuGkblGUZmZPwÿÿ ) )
        {
            $_obfuscate_Bk2lGlkÿ = " AND `stationid` IN (".implode( ",", $_obfuscate_UsUCkyqS6df49QRFnhyG9Aÿÿ ).")";
            $_obfuscate_Lw9wXKzqBgÿÿ = app::loadapp( "main", "user" )->api_getUserByFids( $_obfuscate_Qoa1pKbG2zuGkblGUZmZPwÿÿ, $_obfuscate_Bk2lGlkÿ );
            foreach ( $_obfuscate_Lw9wXKzqBgÿÿ as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_SeV31Qÿÿ[$_obfuscate_6Aÿÿ['uid']] = $_obfuscate_6Aÿÿ['truename'];
            }
        }
        foreach ( $_obfuscate_SeV31Qÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_phKp89pDgwQÿ[] = array(
                "uid" => $_obfuscate_5wÿÿ,
                "truename" => $_obfuscate_6Aÿÿ
            );
        }
        $_obfuscate_xs33Yt_k = array( );
        foreach ( $_obfuscate_phKp89pDgwQÿ as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_xs33Yt_k[] = $_obfuscate_6Aÿÿ;
        }
        return $_obfuscate_xs33Yt_k;
    }

    private function _cuiban( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_ecqaH_ev7Aÿÿ = getpar( $_POST, "uStepId", 0 );
        $_obfuscate__WwKzYz1wAÿÿ = getpar( $_POST, "content", "" );
        $_obfuscate_a9lP = getpar( $_POST, "sms", "false" );
        $_obfuscate_a9lP = $_obfuscate_a9lP == "false" ? FALSE : TRUE;
        $_obfuscate_NS44QYkÿ = $CNOA_SESSION->get( "TRUENAME" );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowNumber", "flowName", "flowId" ), $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->db_getone( array( "tplSort", "flowType" ), $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_hTew0boWJESy['flowId']."'" );
        $_obfuscate_5NhzjnJq_f8ÿ = $CNOA_DB->db_getone( array( "id", "uid" ), $this->t_use_step, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ." AND `uStepId`={$_obfuscate_ecqaH_ev7Aÿÿ}" );
        $_obfuscate_lQ81YBMÿ = $this->getProxyUid( $_obfuscate_hTew0boWJESy['flowId'], $_obfuscate_5NhzjnJq_f8ÿ['uid'] );
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_6RYLWQÿÿ['content'] = $_obfuscate_NS44QYkÿ.lang( "flowDealRemin" ).":".$_obfuscate__WwKzYz1wAÿÿ;
        $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_ecqaH_ev7Aÿÿ}&flowType={$_obfuscate_7qDAYo85aGAÿ['flowType']}&tplSort={$_obfuscate_7qDAYo85aGAÿ['tplSort']}";
        $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_5NhzjnJq_f8ÿ['id'];
        $this->addNotice( "notice", array(
            $_obfuscate_5NhzjnJq_f8ÿ['uid'],
            $_obfuscate_lQ81YBMÿ
        ), $_obfuscate_6RYLWQÿÿ, "warn" );
        if ( $_obfuscate_a9lP )
        {
            ( );
            $_obfuscate_a9lP = new sms( );
            $_obfuscate_a9lP->sendByUids( array(
                $_obfuscate_5NhzjnJq_f8ÿ['uid'],
                $_obfuscate_lQ81YBMÿ
            ), $_obfuscate_6RYLWQÿÿ['content'], 0, "flow", 0 );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3631, lang( "remindFlow" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _addChildFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_0W8ÿ = getpar( $_POST, "id", 0 );
        $_obfuscate_sx8ÿ = $CNOA_DB->db_getone( "*", $this->t_use_step_child_flow, "WHERE `id` = ".$_obfuscate_0W8ÿ." " );
        unset( $_obfuscate_sx8ÿ['id'] );
        $_obfuscate_sx8ÿ['original'] = $_obfuscate_0W8ÿ;
        $_obfuscate_sx8ÿ['status'] = 0;
        $CNOA_DB->db_insert( $_obfuscate_sx8ÿ, $this->t_use_step_child_flow );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _deleteChildFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_0W8ÿ = getpar( $_POST, "id", 0 );
        $_obfuscate_tC8MNsAzXAÿÿ = $CNOA_DB->db_getfield( "faqiUid", $this->t_use_step_child_flow, "WHERE `id`=".$_obfuscate_0W8ÿ );
        if ( $_obfuscate_tC8MNsAzXAÿÿ != $_obfuscate_7Ri3 )
        {
            msg::callback( FALSE, lang( "currenUserNotSponsor" ) );
        }
        $CNOA_DB->db_delete( $this->t_use_step_child_flow, "WHERE `id` = ".$_obfuscate_0W8ÿ." " );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _cancelChildFaqi( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_0W8ÿ = getpar( $_POST, "id", 0 );
        $_obfuscate_3qoQ0OmYggÿ = $CNOA_DB->db_getone( array( "faqiUid", "postuid" ), $this->t_use_step_child_flow, "WHERE `id`=".$_obfuscate_0W8ÿ );
        if ( !in_array( $_obfuscate_7Ri3, array(
            $_obfuscate_3qoQ0OmYggÿ['faqiUid'],
            $_obfuscate_3qoQ0OmYggÿ['postuid']
        ) ) )
        {
            msg::callback( FALSE, lang( "currentUserNotCancel" ) );
        }
        $CNOA_DB->db_update( array( "status" => 0, "faqitime" => 0, "faqiUid" => 0 ), $this->t_use_step_child_flow, "WHERE `id` = ".$_obfuscate_0W8ÿ." " );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function __getPrevstepData( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $_obfuscate_5NhzjnJq_f8ÿ = $CNOA_DB->db_getone( "*", $this->t_use_step, "WHERE `uFlowId` = ".$_obfuscate_TlvKhtsoOQÿÿ." AND `uStepId` = {$_obfuscate_0Ul8BBkt} " );
        if ( $_obfuscate_5NhzjnJq_f8ÿ['stepType'] == 6 )
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_step, "WHERE `uFlowId` = ".$_obfuscate_TlvKhtsoOQÿÿ." AND `dealUid`!=0 AND (`status` = 2 OR `status` = 4 OR (`status` = 0 AND `stepType` = 4)) AND (`stepType` = 6 AND `pStepId` != {$_obfuscate_5NhzjnJq_f8ÿ['pStepId']} OR `uStepId` != {$_obfuscate_5NhzjnJq_f8ÿ['pStepId']}) ORDER BY `id` ASC " );
        }
        else
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_step, "WHERE `uFlowId` = ".$_obfuscate_TlvKhtsoOQÿÿ." AND `dealUid`!=0 AND (`status` = 2 OR `status` = 4 OR (`status` = 0 AND `stepType` = 4)) GROUP BY `uStepId` ORDER BY `id` ASC " );
        }
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        return $_obfuscate_mPAjEGLn;
    }

    private function __updateHuiqianStatus( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_0Ul8BBkt, $_obfuscate_7Ri3 )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $CNOA_DB->db_update( array( "status" => 2 ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' AND `uid` = '{$_obfuscate_7Ri3}' " );
    }

    private function __isMyStep( $_obfuscate_TlvKhtsoOQÿÿ )
    {
    }

    private function _submitFreeSendBackData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQÿÿ = ( integer )getpar( $_POST, "uFlowId" );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_POST, "stepId" );
        $_obfuscate_ecqaH_ev7Aÿÿ = ( integer )getpar( $_POST, "uStepId" );
        $_obfuscate_1l6P = getpar( $_POST, "say", "é€€å›žè¯¥æµç¨‹" );
        $_obfuscate_XkuTFqZ6Tmkÿ = ( integer )getpar( $_POST, "flowType" );
        $_obfuscate_pEvU7Kz2Ywÿÿ = ( integer )getpar( $_POST, "tplSort" );
        $_obfuscate_HYI4w55m58H2WjCs = $this->getAllStepByUFlowId( $_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_j9eamhYÿ = $_obfuscate_HYI4w55m58H2WjCs['stepInfo'];
        if ( $_obfuscate_HYI4w55m58H2WjCs['stepInfo'][$_obfuscate_0Ul8BBkt]['status'] != 1 )
        {
            msg::callback( FALSE, lang( "noPermitOpt" ) );
        }
        $_obfuscate_pp9pYwÿÿ = array( );
        $this->getPath( $_obfuscate_j9eamhYÿ, $_obfuscate_0Ul8BBkt, $_obfuscate_pp9pYwÿÿ );
        $_obfuscate_umeHLFZ6 = $_obfuscate_j9eamhYÿ[$_obfuscate_ecqaH_ev7Aÿÿ];
        $_obfuscate_NenxpX50gwÿÿ = $_obfuscate_j9eamhYÿ[$_obfuscate_0Ul8BBkt];
        $_obfuscate_rX2e4Qw_AQÿÿ = array( );
        $_obfuscate_ZRB1fr3t0YMÿ = array( );
        foreach ( $_obfuscate_pp9pYwÿÿ as $_obfuscate_Cy1W => $_obfuscate_WKs3DAÿÿ )
        {
            if ( $_obfuscate_Cy1W == $_obfuscate_ecqaH_ev7Aÿÿ )
            {
                $_obfuscate_ZRB1fr3t0YMÿ = $_obfuscate_Cy1W;
            }
            else
            {
                $_obfuscate_rX2e4Qw_AQÿÿ[] = $_obfuscate_Cy1W;
                if ( is_array( $_obfuscate_WKs3DAÿÿ ) )
                {
                    foreach ( $_obfuscate_WKs3DAÿÿ as $_obfuscate_cO77 => $_obfuscate_9HHDStdl )
                    {
                        if ( is_array( $_obfuscate_9HHDStdl ) )
                        {
                            $_obfuscate_rX2e4Qw_AQÿÿ = array_merge( $_obfuscate_rX2e4Qw_AQÿÿ, $_obfuscate_9HHDStdl );
                        }
                    }
                }
            }
        }
        $_obfuscate_BRPhVAÿÿ = array( );
        $_obfuscate_8CJrZgÿÿ = array( );
        $_obfuscate_BRPhVAÿÿ = $_obfuscate_j9eamhYÿ[$_obfuscate_ZRB1fr3t0YMÿ]['id'];
        foreach ( $_obfuscate_rX2e4Qw_AQÿÿ as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_8CJrZgÿÿ[] = $_obfuscate_j9eamhYÿ[$_obfuscate_6Aÿÿ]['id'];
        }
        if ( $_obfuscate_ZRB1fr3t0YMÿ == 1 )
        {
            msg::callback( FALSE, lang( "freeFlowNotAllowReturn" ) );
        }
        $_obfuscate_8CJrZgÿÿ = implode( ",", $_obfuscate_8CJrZgÿÿ );
        $_obfuscate_etgCHrO23Qft = array( "dealUid" => 0, "etime" => 0, "say" => "", "status" => 1, "stepType" => 1 );
        $_obfuscate_P8p3tYC05bV7iAÿÿ = array( "dealUid" => 0, "etime" => 0, "say" => "", "status" => 0, "stepType" => 1 );
        $CNOA_DB->db_update( $_obfuscate_etgCHrO23Qft, $this->t_use_step, "WHERE `id` IN (".$_obfuscate_BRPhVAÿÿ.") AND `uFlowId`={$_obfuscate_TlvKhtsoOQÿÿ} " );
        if ( !empty( $_obfuscate_8CJrZgÿÿ ) )
        {
            $CNOA_DB->db_update( $_obfuscate_P8p3tYC05bV7iAÿÿ, $this->t_use_step, "WHERE `id` IN (".$_obfuscate_8CJrZgÿÿ.") AND `uFlowId`={$_obfuscate_TlvKhtsoOQÿÿ} " );
        }
        unset( $_obfuscate_8CJrZgÿÿ );
        unset( $_obfuscate_BRPhVAÿÿ );
        ( $_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_e53ODz04JQÿÿ = new wfCache( );
        $_obfuscate_7qDAYo85aGAÿ = $_obfuscate_e53ODz04JQÿÿ->getFlow( );
        $this->doneAll( "both", $_obfuscate_NenxpX50gwÿÿ['id'], "todo" );
        $this->doneAll( "notice", $_obfuscate_NenxpX50gwÿÿ['id'], "huiqian", 1 );
        if ( $_obfuscate_7qDAYo85aGAÿ['noticeAtGoBack'] != 4 )
        {
            $_obfuscate_6RYLWQÿÿ = array( );
            $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber", "flowId" ), $this->t_use_flow, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ );
            if ( $_obfuscate_XkuTFqZ6Tmkÿ == 0 )
            {
                $_obfuscate_qZkmBgÿÿ = $_obfuscate_e53ODz04JQÿÿ->getFlow( );
            }
            else
            {
                $_obfuscate_qZkmBgÿÿ = $CNOA_DB->db_getone( array( "noticeAtGoBack" ), $this->t_set_flow, "WHERE `flowId`=".$_obfuscate_hTew0boWJESy['flowId'] );
            }
            $_obfuscate_rLakms3x4ur74Qÿÿ = array( );
            $_obfuscate_VBCv7Qÿÿ = $_obfuscate_j9eamhYÿ[$_obfuscate_ZRB1fr3t0YMÿ];
            if ( !empty( $_obfuscate_VBCv7Qÿÿ['dealUid'] ) )
            {
                $_obfuscate_rLakms3x4ur74Qÿÿ[] = $_obfuscate_VBCv7Qÿÿ['dealUid'];
            }
            $_obfuscate_6RYLWQÿÿ = array( );
            $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" ).( "[".$_obfuscate_hTew0boWJESy['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_hTew0boWJESy['flowNumber']."]" ).lang( "beReturnNeedReApp" )."<br /> ".lang( "returnReason" ).( ":[".$_obfuscate_1l6P."]" );
            $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_VBCv7Qÿÿ['uStepId']}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
            $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_VBCv7Qÿÿ['id'];
            $this->addNotice( "both", $_obfuscate_VBCv7Qÿÿ['dealUid'], $_obfuscate_6RYLWQÿÿ, "todo", 0 );
            if ( $_obfuscate_qZkmBgÿÿ['noticeAtGoBack'] == 2 )
            {
                $_obfuscate_VBCv7Qÿÿ = $_obfuscate_j9eamhYÿ[2];
                if ( !in_array( $_obfuscate_VBCv7Qÿÿ['dealUid'], $_obfuscate_rLakms3x4ur74Qÿÿ ) )
                {
                    $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" ).( "[".$_obfuscate_hTew0boWJESy['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_hTew0boWJESy['flowNumber']."]" ).lang( "beReturn" );
                    $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step=2&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
                    $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_VBCv7Qÿÿ['id'];
                    $this->addNotice( "notice", $_obfuscate_VBCv7Qÿÿ['dealUid'], $_obfuscate_6RYLWQÿÿ, "tuihui", 2 );
                }
            }
            else if ( $_obfuscate_qZkmBgÿÿ['noticeAtGoBack'] == 3 )
            {
                $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" ).( "[".$_obfuscate_hTew0boWJESy['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_hTew0boWJESy['flowNumber']."]" ).lang( "beReturn" );
                $_obfuscate_rLakms3x4ur74Qÿÿ = implode( ",", $_obfuscate_rLakms3x4ur74Qÿÿ );
                $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "dealUid", "id", "uStepId" ), $this->t_use_step, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ." AND `uStepId`!=2 AND `dealUid`!=0 AND `dealUid` NOT IN ({$_obfuscate_rLakms3x4ur74Qÿÿ})" );
                if ( !is_array( $_obfuscate_Tx7M9W ) )
                {
                    $_obfuscate_Tx7M9W = array( );
                }
                foreach ( $_obfuscate_Tx7M9W as $_obfuscate_VBCv7Qÿÿ )
                {
                    $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_VBCv7Qÿÿ['uStepId']}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
                    $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_VBCv7Qÿÿ['id'];
                    $this->addNotice( "notice", $_obfuscate_VBCv7Qÿÿ['dealUid'], $_obfuscate_6RYLWQÿÿ, "tuihui", 1 );
                }
            }
        }
        $_obfuscate_JG8GuYÿ = array( );
        $_obfuscate_JG8GuYÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
        $_obfuscate_JG8GuYÿ['type'] = 4;
        $_obfuscate_JG8GuYÿ['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuYÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuYÿ['say'] = $_obfuscate_1l6P;
        $_obfuscate_VBCv7Qÿÿ = $_obfuscate_j9eamhYÿ[$_obfuscate_ZRB1fr3t0YMÿ];
        $_obfuscate_JG8GuYÿ['step'] = $_obfuscate_ZRB1fr3t0YMÿ;
        $_obfuscate_JG8GuYÿ['stepname'] = $_obfuscate_VBCv7Qÿÿ['stepname'];
        $this->insertEvent( $_obfuscate_JG8GuYÿ );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3604, lang( "backToFlowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function clearReturnOption( $_obfuscate_S0PSA37yAwÿÿ, $_obfuscate_TlvKhtsoOQÿÿ )
    {
        global $CNOA_DB;
        $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->db_getone( array( "flowId" ), $this->t_use_flow, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_F4AbnVRh = $_obfuscate_7qDAYo85aGAÿ['flowId'];
        $_obfuscate_OwOOwPh71SP8gÿÿ = "z_wf_t_".$_obfuscate_F4AbnVRh;
        foreach ( $_obfuscate_S0PSA37yAwÿÿ as $_obfuscate_0Ul8BBkt )
        {
            $_obfuscate_KCPlBXFqX1tKAÿÿ = $CNOA_DB->db_select( "*", $this->t_set_step_fields, "WHERE `stepId`=".$_obfuscate_0Ul8BBkt." AND `flowId`={$_obfuscate_F4AbnVRh} AND `write`=1 AND `from`=0" );
            if ( !is_array( $_obfuscate_KCPlBXFqX1tKAÿÿ ) )
            {
                $_obfuscate_KCPlBXFqX1tKAÿÿ = array( );
            }
            if ( $_obfuscate_KCPlBXFqX1tKAÿÿ )
            {
                foreach ( $_obfuscate_KCPlBXFqX1tKAÿÿ as $_obfuscate_tjILu7ZH )
                {
                    $_obfuscate_YIq2A8cÿ = $CNOA_DB->db_getone( array( "otype", "dvalue" ), $this->t_set_field, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND `id`={$_obfuscate_tjILu7ZH['fieldId']}" );
                    if ( !( $_obfuscate_tjILu7ZH['from'] == 0 ) && !( $_obfuscate_YIq2A8cÿ['otype'] != "detailtable" ) )
                    {
                        $_obfuscate_255EBzbYVVEÿ = "T_".$_obfuscate_tjILu7ZH['fieldId'];
                        $_obfuscate_XoQj4PaA = $_obfuscate_YIq2A8cÿ['dvalue'] == "default" ? "" : $_obfuscate_YIq2A8cÿ['dvalue'];
                        $CNOA_DB->db_update( array(
                            $_obfuscate_255EBzbYVVEÿ => $_obfuscate_XoQj4PaA
                        ), $_obfuscate_OwOOwPh71SP8gÿÿ, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ );
                    }
                }
            }
        }
        return TRUE;
    }

    public function api_loadUflowInfo( $_obfuscate_vholQÿÿ = NULL )
    {
        return $this->_loadUflowInfo( $_obfuscate_vholQÿÿ );
    }

    public function api_getFlowTypeData( )
    {
        $_obfuscate_fMfsswÿÿ = $this->_getFlowTypeData( );
        return $_obfuscate_fMfsswÿÿ;
    }

    public function api_getRelFlow( )
    {
        $this->_getRelFlow( );
    }

    public function api_submitRejectAbout( )
    {
        $this->_submitRejectAbout( );
    }

    public function api_loadPrevstepData( )
    {
        return $this->_loadPrevstepData( TRUE );
    }

    public function api_submitPrevstepData( )
    {
        $this->_submitPrevstepData( );
    }

    public function api_getSendNextData( )
    {
        $this->_getSendNextData( );
    }

    public function api_addFenfa( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $_obfuscate_xs33Yt_k = $CNOA_DB->db_getone( "*", $this->t_set_autoFenfa, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND `stepId`={$_obfuscate_0Ul8BBkt}" );
        $_obfuscate_6b8lIO4y = $_obfuscate_xs33Yt_k['status'];
        $_obfuscate__eqrEQÿÿ = $_obfuscate_xs33Yt_k['uids'];
        if ( $_obfuscate_6b8lIO4y )
        {
            $_obfuscate__eqrEQÿÿ = explode( ",", $_obfuscate__eqrEQÿÿ );
            $this->_addFenfa( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate__eqrEQÿÿ, $_obfuscate_0Ul8BBkt, TRUE );
        }
    }

    private function _checkHuiqian( )
    {
        global $CNOA_DB;
        $_obfuscate_TlvKhtsoOQÿÿ = ( integer )getpar( $_POST, "uFlowId" );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_POST, "step" );
        $_obfuscate_qPt6frCIa6xJ0ogÿ = $CNOA_DB->db_select( "*", $this->t_use_step_huiqian, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ." AND `stepId`={$_obfuscate_0Ul8BBkt}" );
        if ( !is_array( $_obfuscate_qPt6frCIa6xJ0ogÿ ) )
        {
            $_obfuscate_qPt6frCIa6xJ0ogÿ = array( );
        }
        $_obfuscate_WkJpjIf2P4ulnQÿÿ = 0;
        if ( empty( $_obfuscate_qPt6frCIa6xJ0ogÿ ) )
        {
            msg::callback( FALSE, "" );
        }
        else
        {
            foreach ( $_obfuscate_qPt6frCIa6xJ0ogÿ as $_obfuscate_6Aÿÿ )
            {
                if ( $_obfuscate_6Aÿÿ['status'] == 0 )
                {
                    $_obfuscate_WkJpjIf2P4ulnQÿÿ += 1;
                }
            }
            if ( $_obfuscate_WkJpjIf2P4ulnQÿÿ != 0 )
            {
                msg::callback( TRUE, "è¯¥æ­¥éª¤æœ‰".$_obfuscate_WkJpjIf2P4ulnQÿÿ."æ¡ä¼šç­¾æ„è§æ²¡æäº¤ï¼Œæ˜¯å¦è½¬ä¸‹ä¸€æ­¥ï¼Ÿ" );
            }
            else
            {
                msg::callback( FALSE, "" );
            }
        }
    }

    private function _taoHong( )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "id", "oldname", "name" ), $this->t_set_taohong );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_NlQÿ->makeJsonData( );
    }

    private function _saveFieldDraft( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_6RYLWQÿÿ = $_obfuscate_ueAmmqePj0kÿ = $_obfuscate_dGoPOiQ2Iw5a = $_obfuscate_FYo_0_BVp9xjgDsÿ = $_obfuscate_1_pbjTIdLU49 = $_obfuscate_n03EzxA4CC93dt0ÿ = array( );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId" );
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId" );
        $_obfuscate_VBCv7Qÿÿ = getpar( $_POST, "step" );
        $_obfuscate_LeS8hwÿÿ = getpar( $_POST, "type" );
        $_obfuscate_1l6P = getpar( $_POST, "say" );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        ( $_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_e53ODz04JQÿÿ = new wfCache( );
        $_obfuscate_WMVwRv5Dgÿÿ = "cwj&flowId=".$_obfuscate_F4AbnVRh."&uFlowId={$_obfuscate_TlvKhtsoOQÿÿ}&step={$_obfuscate_VBCv7Qÿÿ}";
        $_obfuscate_PW9SQhMxAgÿÿ = $this->_getFilePath( $_obfuscate_WMVwRv5Dgÿÿ );
        $_obfuscate_4puJ00P0cS = $this->api_getWfFieldData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, TRUE );
        foreach ( $GLOBALS['_POST'] as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( ereg( "wf_field_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_0W8ÿ = str_replace( "wf_field_", "", $_obfuscate_5wÿÿ );
                $_obfuscate_ueAmmqePj0kÿ[$_obfuscate_0W8ÿ] = $_obfuscate_6Aÿÿ;
            }
            if ( ereg( "wf_fieldJ_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_0W8ÿ = str_replace( "wf_fieldJ_", "", $_obfuscate_5wÿÿ );
                $_obfuscate_ueAmmqePj0kÿ[$_obfuscate_0W8ÿ] = $_obfuscate_6Aÿÿ;
            }
            if ( ereg( "wf_fieldC_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_0W8ÿ = str_replace( "wf_fieldC_", "", $_obfuscate_5wÿÿ );
                $_obfuscate_SeV31Qÿÿ = explode( "_", $_obfuscate_0W8ÿ );
                $_obfuscate_0W8ÿ = $_obfuscate_SeV31Qÿÿ[0];
                $_obfuscate_ueAmmqePj0kÿ[$_obfuscate_0W8ÿ][$_obfuscate_SeV31Qÿÿ[1]] = $_obfuscate_6Aÿÿ;
            }
            if ( ereg( "wf_detail_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_SeV31Qÿÿ = explode( "_", str_replace( "wf_detail_", "", $_obfuscate_5wÿÿ ) );
                $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_SeV31Qÿÿ[0]][$_obfuscate_SeV31Qÿÿ[1]] = $_obfuscate_6Aÿÿ;
            }
            if ( ereg( "wf_detailJ_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_SeV31Qÿÿ = explode( "_", str_replace( "wf_detailJ_", "", $_obfuscate_5wÿÿ ) );
                $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_SeV31Qÿÿ[0]][$_obfuscate_SeV31Qÿÿ[1]] = $_obfuscate_6Aÿÿ;
            }
            if ( ereg( "detailid_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_FYo_0_BVp9xjgDsÿ[] = intval( str_replace( "detailid_", "", $_obfuscate_5wÿÿ ) );
            }
            if ( ereg( "wf_attach", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_1_pbjTIdLU49[] = intval( str_replace( "wf_attach_", "", $_obfuscate_5wÿÿ ) );
            }
        }
        if ( $_FILES )
        {
            ( );
            $_obfuscate_2ggÿ = new fs( );
            $_obfuscate_KE_ngbhTLbfzRa4n = $_obfuscate_2ggÿ->uploadFile4Wf( "", "", 1 );
            $_obfuscate_1_pbjTIdLU49 = array_merge( $_obfuscate_1_pbjTIdLU49, $_obfuscate_KE_ngbhTLbfzRa4n );
        }
        if ( $_obfuscate_FYo_0_BVp9xjgDsÿ )
        {
            $_obfuscate_7Hp0w_lfFt4ÿ = $CNOA_DB->db_select( array( "id", "fid" ), $this->t_set_field_detail, "WHERE `fid` IN(".implode( ",", $_obfuscate_FYo_0_BVp9xjgDsÿ ).")" );
            if ( !is_array( $_obfuscate_7Hp0w_lfFt4ÿ ) )
            {
                $_obfuscate_7Hp0w_lfFt4ÿ = array( );
            }
            foreach ( $_obfuscate_7Hp0w_lfFt4ÿ as $_obfuscate_6Aÿÿ )
            {
                foreach ( $_obfuscate_dGoPOiQ2Iw5a as $_obfuscate_5wÿÿ => $_obfuscate_snMÿ )
                {
                    if ( array_key_exists( $_obfuscate_6Aÿÿ['id'], $_obfuscate_snMÿ ) )
                    {
                        $_obfuscate_6RYLWQÿÿ['detail'][$_obfuscate_6Aÿÿ['fid']][$_obfuscate_5wÿÿ][$_obfuscate_6Aÿÿ['id']] = $_obfuscate_snMÿ[$_obfuscate_6Aÿÿ['id']];
                    }
                }
            }
        }
        foreach ( $_obfuscate_4puJ00P0cS as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_YIq2A8cÿ = $_obfuscate_e53ODz04JQÿÿ->getField( $_obfuscate_5wÿÿ );
            if ( array_key_exists( $_obfuscate_5wÿÿ, $_obfuscate_ueAmmqePj0kÿ ) )
            {
                if ( $_obfuscate_YIq2A8cÿ['otype'] == "checkbox" )
                {
                    $_obfuscate_4puJ00P0cS[$_obfuscate_5wÿÿ] = json_encode( $_obfuscate_ueAmmqePj0kÿ[$_obfuscate_5wÿÿ] );
                    if ( $_obfuscate_LeS8hwÿÿ == 2 )
                    {
                        $_obfuscate_4puJ00P0cS[$_obfuscate_5wÿÿ] = addcslashes( $_obfuscate_4puJ00P0cS[$_obfuscate_5wÿÿ], "\\" );
                    }
                }
                else
                {
                    $_obfuscate_4puJ00P0cS[$_obfuscate_5wÿÿ] = $_obfuscate_ueAmmqePj0kÿ[$_obfuscate_5wÿÿ];
                }
            }
        }
        $_obfuscate_6RYLWQÿÿ['field'] = $_obfuscate_4puJ00P0cS;
        if ( $_obfuscate_6RYLWQÿÿ['detail'] )
        {
            foreach ( $_obfuscate_6RYLWQÿÿ['detail'] as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                ksort( &$_obfuscate_6RYLWQÿÿ['detail'][$_obfuscate_5wÿÿ] );
            }
        }
        $_obfuscate_6RYLWQÿÿ['attach'] = json_encode( $_obfuscate_1_pbjTIdLU49 );
        $_obfuscate_6RYLWQÿÿ['newAttach'] = $_obfuscate_KE_ngbhTLbfzRa4n;
        if ( $_obfuscate_LeS8hwÿÿ == "1" )
        {
            $_obfuscate_R2_b = "<?php \n return ".var_export( $_obfuscate_6RYLWQÿÿ, TRUE ).";";
            file_put_contents( $_obfuscate_PW9SQhMxAgÿÿ, $_obfuscate_R2_b );
        }
        else if ( $_obfuscate_LeS8hwÿÿ == "2" )
        {
            foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_0fY05oSiDdok6Aÿÿ = array( );
                if ( $_obfuscate_5wÿÿ == "field" )
                {
                    foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_ty0ÿ => $_obfuscate_snMÿ )
                    {
                        $_obfuscate_0fY05oSiDdok6Aÿÿ["T_".$_obfuscate_ty0ÿ] = $_obfuscate_snMÿ;
                    }
                    $CNOA_DB->db_update( $_obfuscate_0fY05oSiDdok6Aÿÿ, "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ );
                }
                if ( $_obfuscate_5wÿÿ == "detail" )
                {
                    foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_8jhldA9Y9Aÿÿ => $_obfuscate_iGxAiPLV2uzR )
                    {
                        if ( $_obfuscate_iGxAiPLV2uzR )
                        {
                            $_obfuscate_Gfham6St = $_obfuscate_0fY05oSiDdok6Aÿÿ = array( );
                            $_obfuscate_3tiDsnMÿ = "z_wf_d_".$_obfuscate_F4AbnVRh."_{$_obfuscate_8jhldA9Y9Aÿÿ}";
                            foreach ( $_obfuscate_iGxAiPLV2uzR as $_obfuscate_KF0ÿ => $_obfuscate_r9K7G3QJ )
                            {
                                $_obfuscate_Gfham6St[] = $_obfuscate_KF0ÿ;
                                $_obfuscate_8Q1yVKUÿ = $CNOA_DB->db_getone( "*", $_obfuscate_3tiDsnMÿ, "WHERE `rowid`=".$_obfuscate_KF0ÿ." AND `uFlowId`={$_obfuscate_TlvKhtsoOQÿÿ}" );
                                foreach ( $_obfuscate_r9K7G3QJ as $_obfuscate_ty0ÿ => $_obfuscate_snMÿ )
                                {
                                    $_obfuscate_0fY05oSiDdok6Aÿÿ["D_".$_obfuscate_ty0ÿ] = $_obfuscate_snMÿ;
                                }
                                $_obfuscate_0fY05oSiDdok6Aÿÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
                                $_obfuscate_0fY05oSiDdok6Aÿÿ['rowid'] = $_obfuscate_KF0ÿ;
                                if ( $_obfuscate_8Q1yVKUÿ )
                                {
                                    $CNOA_DB->db_update( $_obfuscate_0fY05oSiDdok6Aÿÿ, $_obfuscate_3tiDsnMÿ, "WHERE `rowid`=".$_obfuscate_KF0ÿ." AND `uFlowId`={$_obfuscate_TlvKhtsoOQÿÿ}" );
                                }
                                else
                                {
                                    $CNOA_DB->db_insert( $_obfuscate_0fY05oSiDdok6Aÿÿ, $_obfuscate_3tiDsnMÿ );
                                }
                            }
                            $CNOA_DB->db_delete( $_obfuscate_3tiDsnMÿ, "WHERE `rowid` NOT IN(".implode( ",", $_obfuscate_Gfham6St ).( ") AND `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ ) );
                        }
                    }
                }
                if ( $_obfuscate_5wÿÿ == "attach" )
                {
                    $_obfuscate_OrvB8VTuwÿÿ = array(
                        "attach" => $_obfuscate_6Aÿÿ
                    );
                    $CNOA_DB->db_update( $_obfuscate_OrvB8VTuwÿÿ, $this->t_use_flow, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND `uFlowId`={$_obfuscate_TlvKhtsoOQÿÿ}" );
                }
            }
            $_obfuscate_JG8GuYÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
            $_obfuscate_JG8GuYÿ['step'] = $_obfuscate_VBCv7Qÿÿ;
            $_obfuscate_JG8GuYÿ['uid'] = $_obfuscate_7Ri3;
            $_obfuscate_JG8GuYÿ['say'] = $_obfuscate_1l6P;
            $_obfuscate_JG8GuYÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $_obfuscate_JG8GuYÿ['stepname'] = $this->_getStepName( $_obfuscate_F4AbnVRh, $_obfuscate_VBCv7Qÿÿ );
            $_obfuscate_JG8GuYÿ['type'] = 13;
            $this->insertEvent( $_obfuscate_JG8GuYÿ );
            $_obfuscate_qpSFgn2oZ1R6 = $this->_checkDraft( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_VBCv7Qÿÿ );
            if ( $_obfuscate_qpSFgn2oZ1R6 )
            {
                unlink( $_obfuscate_qpSFgn2oZ1R6 );
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getFilePath( $_obfuscate_WMVwRv5Dgÿÿ )
    {
        $_obfuscate_DfXWN0u3Qlf5yOoÿ = CNOA_PATH_FILE."/common/wf/draft/";
        @mkdirs( $_obfuscate_DfXWN0u3Qlf5yOoÿ );
        return $_obfuscate_DfXWN0u3Qlf5yOoÿ.$_obfuscate_WMVwRv5Dgÿÿ.".php";
    }

    private function _getStepName( $_obfuscate_F4AbnVRh, $_obfuscate_VBCv7Qÿÿ )
    {
        global $CNOA_DB;
        $_obfuscate_sdCqoaGDkaAÿ = $CNOA_DB->db_getone( array( "stepName" ), $this->t_set_step, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND `stepId`={$_obfuscate_VBCv7Qÿÿ}" );
        return $_obfuscate_sdCqoaGDkaAÿ['stepName'];
    }

}

?>
