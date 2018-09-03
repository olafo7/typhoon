<?php

class wfFlowUseTodo extends wfCommon
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
        case "getDeskTopJsonData" :
            $this->_getDeskTopJsonData( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $_obfuscate_phKp89pDgwQ� = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $_obfuscate_phKp89pDgwQ� );
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
            $_obfuscate_Tc82k3jOQ�� = $this->getFlowFields( );
            ( );
            $_obfuscate_NlQ� = new dataStore( );
            $_obfuscate_NlQ�->data = $_obfuscate_Tc82k3jOQ��;
            echo $_obfuscate_NlQ�->makeJsonData( );
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
        $_obfuscate_vholQ�� = getpar( $_GET, "from", "" );
        if ( $_obfuscate_vholQ�� == "list" )
        {
            $_obfuscate_7wvDhu7G = getpar( $_GET, "levels", "" );
            $GLOBALS['GLOBALS']['app']['levels'] = $_obfuscate_7wvDhu7G;
            $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/todo.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQ�� );
            exit( );
        }
        $_obfuscate_TlvKhtsoOQ�� = ( integer )getpar( $_GET, "uFlowId" );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_GET, "step" );
        if ( $_obfuscate_TlvKhtsoOQ�� == 0 )
        {
            msg::showerror( "没有选择需要办理的流程" );
        }
        $_obfuscate_3y0Y = "SELECT f.flowId, f.tplSort, f.flowType, u.status FROM ".tname( $this->t_use_flow )." AS u RIGHT JOIN ".tname( $this->t_set_flow )." AS f ON u.flowId=f.flowId ".( "WHERE uFlowId=".$_obfuscate_TlvKhtsoOQ�� );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->get_one( $_obfuscate_3y0Y );
        if ( !$_obfuscate_hTew0boWJESy )
        {
            msg::showerror( "没有此流程" );
        }
        if ( $_obfuscate_vholQ�� == "showflow" )
        {
            $GLOBALS['GLOBALS']['app']['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
            $GLOBALS['GLOBALS']['app']['step'] = $_obfuscate_0Ul8BBkt;
            $GLOBALS['GLOBALS']['app']['flowId'] = ( integer )$_obfuscate_hTew0boWJESy['flowId'];
            $GLOBALS['GLOBALS']['app']['status'] = ( integer )$_obfuscate_hTew0boWJESy['status'];
            $GLOBALS['GLOBALS']['app']['wf']['tplSort'] = ( integer )$_obfuscate_hTew0boWJESy['tplSort'];
            $GLOBALS['GLOBALS']['app']['wf']['flowType'] = ( integer )$_obfuscate_hTew0boWJESy['flowType'];
            $GLOBALS['GLOBALS']['app']['wf']['type'] = getpar( $_GET, "type", "show" );
            $GLOBALS['GLOBALS']['app']['wf']['childSeeParent'] = getpar( $_GET, "childSeeParent" );
            $GLOBALS['GLOBALS']['app']['wf']['puStepId'] = getpar( $_GET, "puStepId" );
            ( $_obfuscate_TlvKhtsoOQ�� );
            $_obfuscate_bIsJe6A� = new wfCache( );
            $_obfuscate_Tx7M9W = $_obfuscate_bIsJe6A�->getStepByStepId( $GLOBALS['app']['step'] );
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
            $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/showflow.htm";
        }
        else if ( $_obfuscate_vholQ�� == "dealflow" )
        {
            $GLOBALS['GLOBALS']['app']['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
            $GLOBALS['GLOBALS']['app']['step'] = $_obfuscate_0Ul8BBkt;
            $GLOBALS['GLOBALS']['app']['flowId'] = ( integer )$_obfuscate_hTew0boWJESy['flowId'];
            $GLOBALS['GLOBALS']['app']['wf']['tplSort'] = ( integer )$_obfuscate_hTew0boWJESy['tplSort'];
            $GLOBALS['GLOBALS']['app']['wf']['flowType'] = ( integer )$_obfuscate_hTew0boWJESy['flowType'];
            $GLOBALS['GLOBALS']['app']['wf']['changeFlowInfo'] = getpar( $_GET, "changeFlowInfo" );
            ( $_obfuscate_TlvKhtsoOQ�� );
            $_obfuscate_e53ODz04JQ�� = new wfCache( );
            $_obfuscate_5NhzjnJq_f8� = $CNOA_DB->db_getone( array( "uid", "proxyUid", "childFlow", "stepType" ), $this->t_use_step, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ��." AND `uStepId`={$_obfuscate_0Ul8BBkt} AND (`uid`={$_obfuscate_7Ri3} OR `proxyUid`={$_obfuscate_7Ri3}) ORDER BY `id` DESC" );
            $GLOBALS['GLOBALS']['app']['wf']['stepType'] = $_obfuscate_5NhzjnJq_f8�['stepType'];
            if ( $_obfuscate_5NhzjnJq_f8�['childFlow'] == 1 )
            {
                $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->t_use_step_child_flow, "WHERE `puFlowId` = ".$_obfuscate_TlvKhtsoOQ��." AND `stepId` = {$_obfuscate_0Ul8BBkt} " );
                if ( empty( $_obfuscate_Ybai ) )
                {
                    $_obfuscate_ibEsWI9S['puFlowId'] = $_obfuscate_TlvKhtsoOQ��;
                    $_obfuscate_ibEsWI9S['stepId'] = $_obfuscate_0Ul8BBkt;
                    $_obfuscate_ibEsWI9S['postuid'] = $_obfuscate_7Ri3;
                    $_obfuscate_Tx7M9W = $_obfuscate_e53ODz04JQ��->getStepByStepId( $_obfuscate_0Ul8BBkt );
                    $_obfuscate_MnVVbyZQFVw� = json_decode( $_obfuscate_Tx7M9W['nextStep'], TRUE );
                    if ( !is_array( $_obfuscate_MnVVbyZQFVw� ) )
                    {
                        $_obfuscate_MnVVbyZQFVw� = array( );
                    }
                    foreach ( $_obfuscate_MnVVbyZQFVw� as $_obfuscate_5w�� => $_obfuscate_6A�� )
                    {
                        $_obfuscate_SeV31Q�� = $_obfuscate_e53ODz04JQ��->getStepByStepId( $_obfuscate_6A�� );
                        if ( $_obfuscate_SeV31Q��['stepType'] == 7 )
                        {
                            $_obfuscate_J9i4sncOcw�� = substr( $_obfuscate_SeV31Q��['bingids'], 0, -1 );
                            if ( !empty( $_obfuscate_J9i4sncOcw�� ) )
                            {
                                $_obfuscate_SIUSR4F6 = $CNOA_DB->db_select( array( "name", "flowId" ), $this->t_set_flow, "WHERE `flowId` IN (".$_obfuscate_J9i4sncOcw��.") " );
                                foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_snM� )
                                {
                                    $_obfuscate_ibEsWI9S['flowId'] = $_obfuscate_snM�['flowId'];
                                    $_obfuscate_ibEsWI9S['faqiFlow'] = $_obfuscate_SeV31Q��['faqiFlow'];
                                    $_obfuscate_ibEsWI9S['endFlow'] = $_obfuscate_SeV31Q��['endFlow'];
                                    $_obfuscate_ibEsWI9S['sharefile'] = $_obfuscate_SeV31Q��['sharefile'];
                                    $CNOA_DB->db_insert( $_obfuscate_ibEsWI9S, $this->t_use_step_child_flow );
                                }
                            }
                        }
                    }
                }
                $GLOBALS['GLOBALS']['app']['wf']['childFlow'] = $_obfuscate_5NhzjnJq_f8�['childFlow'];
                $_obfuscate_zWYc3vx7p7TyMq4W = $CNOA_DB->db_getcount( $this->t_use_step_child_flow, "WHERE `puFlowId` = ".$_obfuscate_TlvKhtsoOQ��."  AND `stepId` = {$_obfuscate_0Ul8BBkt} AND `status` = 0 " );
                if ( empty( $_obfuscate_zWYc3vx7p7TyMq4W ) )
                {
                    $GLOBALS['GLOBALS']['app']['wf']['showChildAlert'] = 0;
                }
                else
                {
                    $GLOBALS['GLOBALS']['app']['wf']['showChildAlert'] = 1;
                }
                $GLOBALS['GLOBALS']['app']['wf']['childNum'] = $CNOA_DB->db_getcount( $this->t_use_step_child_flow, "WHERE `puFlowId` = ".$_obfuscate_TlvKhtsoOQ��."  AND `stepId` = {$_obfuscate_0Ul8BBkt}" );
            }
            $_obfuscate_3Huhdq9QriZt7_7Rsw�� = $_obfuscate_e53ODz04JQ��->getStepByStepId( $_obfuscate_0Ul8BBkt );
            if ( empty( $_obfuscate_3Huhdq9QriZt7_7Rsw��['allowWordEdit'] ) )
            {
                $_obfuscate_3Huhdq9QriZt7_7Rsw��['allowWordEdit'] = 0;
            }
            $GLOBALS['GLOBALS']['allowWordEdit'] = $_obfuscate_3Huhdq9QriZt7_7Rsw��['allowWordEdit'];
            $this->_getRelFlow( );
            if ( $_obfuscate_7Ri3 != $_obfuscate_5NhzjnJq_f8�['uid'] && $_obfuscate_7Ri3 != $_obfuscate_5NhzjnJq_f8�['proxyUid'] )
            {
                $GLOBALS['GLOBALS']['app']['huiqian']['num'] = 0;
                $GLOBALS['GLOBALS']['ismystep'] = 0;
            }
            else
            {
                $GLOBALS['GLOBALS']['app']['huiqian']['num'] = $CNOA_DB->db_getcount( $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' AND `uid` = '{$_obfuscate_7Ri3}' AND `writetime` > 0 " );
                $GLOBALS['GLOBALS']['ismystep'] = 1;
            }
            $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( "*", $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND (`uid`='{$_obfuscate_7Ri3}' OR `proxyUid`='{$_obfuscate_7Ri3}') AND `uStepId`='{$_obfuscate_0Ul8BBkt}' AND `status`=1" );
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
                $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/dealInNewWindow.htm";
            }
            else
            {
                $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/dealflow.htm";
            }
        }
        else if ( $_obfuscate_vholQ�� == "viewflow" )
        {
            $GLOBALS['GLOBALS']['app']['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
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
            $_obfuscate_BxoH_SjRHQ�� = CNOA_PATH."/app/wf/tpl/default/flow/use/showflow.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQ�� );
        exit( );
    }

    private function _getRelFlow( )
    {
        global $CNOA_DB;
        $_obfuscate_7K2adqbGwg�� = $CNOA_DB->db_select( array( "puFlowId", "uFlowId" ), $this->t_use_step_child_flow, "WHERE (`puFlowId` = ".$GLOBALS['app']['uFlowId']." AND `uFlowId`!=0) OR `uFlowId`={$GLOBALS['app']['uFlowId']}" );
        $_obfuscate_vYhijxiHce2BjA��[] = $GLOBALS['app']['uFlowId'];
        if ( !is_array( $_obfuscate_7K2adqbGwg�� ) )
        {
            $_obfuscate_7K2adqbGwg�� = array( );
        }
        $_obfuscate_TlvKhtsoOQ�� = array( );
        foreach ( $_obfuscate_7K2adqbGwg�� as $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['puFlowId'] == $GLOBALS['app']['uFlowId'] )
            {
                $_obfuscate_vYhijxiHce2BjA��[] = $_obfuscate_6A��['uFlowId'];
                $_obfuscate_TlvKhtsoOQ��[] = $_obfuscate_6A��['uFlowId'];
            }
            if ( $_obfuscate_6A��['uFlowId'] == $GLOBALS['app']['uFlowId'] )
            {
                $_obfuscate_vYhijxiHce2BjA��[] = $_obfuscate_6A��['puFlowId'];
                $_obfuscate_TlvKhtsoOQ��[] = $_obfuscate_6A��['puFlowId'];
            }
        }
        while ( 0 < count( $_obfuscate_TlvKhtsoOQ�� ) )
        {
            $_obfuscate_TlvKhtsoOQ�� = implode( ",", $_obfuscate_TlvKhtsoOQ�� );
            $_obfuscate_7K2adqbGwg�� = $CNOA_DB->db_select( array( "puFlowId", "uFlowId" ), $this->t_use_step_child_flow, "WHERE (`puFlowId` IN (".$_obfuscate_TlvKhtsoOQ��.") AND `uFlowId`!=0) OR `uFlowId` IN ({$_obfuscate_TlvKhtsoOQ��})" );
            if ( !is_array( $_obfuscate_7K2adqbGwg�� ) && !( 0 < count( $_obfuscate_7K2adqbGwg�� ) ) )
            {
                $_obfuscate_TlvKhtsoOQ�� = array( );
                foreach ( $_obfuscate_7K2adqbGwg�� as $_obfuscate_6A�� )
                {
                    if ( !in_array( $_obfuscate_6A��['puFlowId'], $_obfuscate_vYhijxiHce2BjA�� ) )
                    {
                        $_obfuscate_vYhijxiHce2BjA��[] = $_obfuscate_6A��['puFlowId'];
                        $_obfuscate_TlvKhtsoOQ��[] = $_obfuscate_6A��['puFlowId'];
                    }
                    if ( !in_array( $_obfuscate_6A��['uFlowId'], $_obfuscate_vYhijxiHce2BjA�� ) )
                    {
                        $_obfuscate_vYhijxiHce2BjA��[] = $_obfuscate_6A��['uFlowId'];
                        $_obfuscate_TlvKhtsoOQ��[] = $_obfuscate_6A��['uFlowId'];
                    }
                }
            }
        }
        unset( $_obfuscate_7K2adqbGwg�� );
        unset( $_obfuscate_TlvKhtsoOQ�� );
        array_shift( &$_obfuscate_vYhijxiHce2BjA�� );
        if ( 0 < count( $_obfuscate_vYhijxiHce2BjA�� ) )
        {
            $_obfuscate_vYhijxiHce2BjA�� = implode( ",", $_obfuscate_vYhijxiHce2BjA�� );
            $_obfuscate_J3xoGDNkbSJolvqe = $CNOA_DB->db_select( array( "uFlowId", "flowId", "flowNumber", "tplSort", "status" ), $this->t_use_flow, "WHERE `uFlowId` IN (".$_obfuscate_vYhijxiHce2BjA��.")" );
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "uFlowId", "uStepId", "etime" ), $this->t_use_step, "WHERE `uFlowId` IN (".$_obfuscate_vYhijxiHce2BjA��.")" );
            if ( !is_array( $_obfuscate_mPAjEGLn ) )
            {
                $_obfuscate_mPAjEGLn = array( );
            }
            $_obfuscate_EAma1UOylRWnspih = array( );
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                if ( isset( $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6A��['uFlowId']] ) )
                {
                    if ( $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6A��['uFlowId']]['etime'] < $_obfuscate_6A��['etime'] )
                    {
                        $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6A��['uFlowId']] = $_obfuscate_6A��;
                    }
                }
                else
                {
                    $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6A��['uFlowId']] = $_obfuscate_6A��;
                }
            }
            if ( !is_array( $_obfuscate_J3xoGDNkbSJolvqe ) )
            {
                $_obfuscate_J3xoGDNkbSJolvqe = array( );
            }
            foreach ( $_obfuscate_J3xoGDNkbSJolvqe as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_J3xoGDNkbSJolvqe[$_obfuscate_5w��]['step'] = $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6A��['uFlowId']]['uStepId'];
                $_obfuscate_EPBXIrrI[] = $_obfuscate_6A��['flowId'];
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
                foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A�� )
                {
                    $_obfuscate_EPBXIrrI[$_obfuscate_6A��['flowId']] = $_obfuscate_6A��['flowType'];
                }
                foreach ( $_obfuscate_J3xoGDNkbSJolvqe as $_obfuscate_5w�� => $_obfuscate_6A�� )
                {
                    $_obfuscate_J3xoGDNkbSJolvqe[$_obfuscate_5w��]['flowType'] = $_obfuscate_EPBXIrrI[$_obfuscate_6A��['flowId']];
                }
                unset( $_obfuscate_EPBXIrrIAMD0 );
            }
            unset( $_obfuscate_mPAjEGLn );
            unset( $_obfuscate_EAma1UOylRWnspih );
            $_obfuscate_J3xoGDNkbSJolvqe = json_encode( $_obfuscate_J3xoGDNkbSJolvqe );
            $GLOBALS['GLOBALS']['app']['wf']['relevanceUFlowInfo'] = $_obfuscate_J3xoGDNkbSJolvqe;
            unset( $_obfuscate_vYhijxiHce2BjA�� );
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
        $_obfuscate_mV9HBLY� = ( integer )getpar( $_POST, "start" );
        $_obfuscate_wWP8H_hVD6l = getpar( $_POST, "flowTitle", "" );
        $_obfuscate_mSWYi45v = getpar( $_POST, "faqiId", "" );
        $_obfuscate_qx37NM� = getpar( $_POST, "stime", "" );
        $_obfuscate_KWKBW4� = getpar( $_POST, "etime", "" );
        $_obfuscate_v1GprsIz = ( integer )getpar( $_POST, "flowType" );
        $_obfuscate_F4AbnVRh = ( integer )getpar( $_POST, "flowId" );
        $_obfuscate_pYzeLf4� = getpar( $_POST, "level" );
        $_obfuscate_7wvDhu7G = getpar( $_GET, "levels" );
        $_obfuscate_637B = getpar( $_POST, "lhc", FALSE );
        $_obfuscate_MXKOdzptzrE� = explode( ":", CNOA_DB_HOST );
        $_obfuscate_D9yo3A�� = $_obfuscate_MXKOdzptzrE�[0];
        $_obfuscate_4Honjw�� = isset( $_obfuscate_MXKOdzptzrE�[1] ) ? $_obfuscate_MXKOdzptzrE�[1] : "3306";
        if ( !$_obfuscate_637B )
        {
            $_obfuscate_xvYeh9I� = getpagesize( "wf_flow_use_todo_getJsonData" );
        }
        $_obfuscate_IRFhnYw� = array( );
        if ( !empty( $_obfuscate_v1GprsIz ) )
        {
            $_obfuscate_IRFhnYw�[] = "f.sortId='".$_obfuscate_v1GprsIz."'";
        }
        if ( $_obfuscate_pYzeLf4� == "3" )
        {
            $_obfuscate_pYzeLf4� = "0";
        }
        if ( !empty( $_obfuscate_7wvDhu7G ) )
        {
            if ( $_obfuscate_7wvDhu7G == "3" )
            {
                $_obfuscate_7wvDhu7G = "0";
            }
            $_obfuscate_IRFhnYw�[] = "f.level='".$_obfuscate_7wvDhu7G."'";
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
                $_obfuscate_NlQ� = new dataStore( );
                echo $_obfuscate_NlQ�->makeJsonData( );
                exit( );
            }
            $_obfuscate_IRFhnYw�[] = $_obfuscate_dcwitxb;
            $_obfuscate_FC6WZeq5G1D5_dA� = "FROM ".tname( self::TABLE_PRE_DATA.$_obfuscate_F4AbnVRh )." AS d LEFT JOIN ".tname( $this->t_use_step )." AS s ON s.uFlowId=d.uFlowId ";
        }
        else
        {
            $_obfuscate_FC6WZeq5G1D5_dA� = "FROM ".tname( $this->t_use_step )." AS `s` ";
        }
        if ( CNOA_ISSAAS === TRUE )
        {
            $_obfuscate_IRFhnYw�[] = "f.domainid=".$GLOBALS['CNOA_DOMAIN_ID'];
        }
        $_obfuscate_IRFhnYw� = implode( " AND ", $_obfuscate_IRFhnYw� );
        $_obfuscate_3y0Y = "CREATE TEMPORARY TABLE IF NOT EXISTS tmp_wftodo_table(\r\n\t\t\t\t`uStepId` INTEGER NOT NULL , `hqUid` INTEGER NULL DEFAULT 0,\r\n\t\t\t\t`uFlowId` int(10) NOT NULL,`puFlowId` int(10) NOT NULL , `flowId` int(10) NOT NULL ,\r\n\t\t\t\t`flowNumber` char(100) NOT NULL ,`flowName` varchar(200) NOT NULL , `tplSort` int(3) NOT NULL ,\r\n\t\t\t\t`uid` int(10) NOT NULL , `level` int(1) NOT NULL , `reason` mediumtext NOT NULL ,\r\n\t\t\t\t`posttime` int(10) NOT NULL , `endtime` int(10) NOT NULL , `edittime` int(10) NOT NULL ,\r\n\t\t\t\t`updatetime` int(10) NOT NULL ,`attach` mediumtext NOT NULL ,`sortId` int(3) NOT NULL ,\r\n\t\t\t\t`status` int(2) NOT NULL ,`allowCallback` int(1) NOT NULL ,`allowCancel` int(1) NOT NULL ,\r\n\t\t\t\t`htmlFormContent` mediumtext NOT NULL ,`otherApp` int(10) NOT NULL,`callBackStatus` int(1) NOT NULL,\r\n\t\t\t\t`changeFlowInfo` int(1) NULL DEFAULT 0 \r\n\t\t\t\t".( CNOA_ISSAAS === TRUE ? ",`domainid` int(10) NOT NULL" : "" )."\r\n\t\t\t\t);\r\n\t\t\t\tset names UTF8;\r\n\r\n\t\t\t\tTRUNCATE TABLE tmp_wftodo_table;\r\n\r\n\t\t\t\tINSERT INTO tmp_wftodo_table( uStepId, hqUid, uFlowId, puFlowId, flowId, flowNumber, flowName, tplSort, uid, level, reason, posttime, endtime, edittime, updatetime, attach, sortId, status, allowCallback, allowCancel, htmlFormContent, otherApp, callBackStatus, changeFlowInfo".( CNOA_ISSAAS === TRUE ? ",domainid" : "" ).") \r\n\t\t\t\tSELECT `s`.`uStepId`,`h`.`touid` AS `hqUid`,  `f`.* ".$_obfuscate_FC6WZeq5G1D5_dA�." LEFT JOIN ".tname( $this->t_use_flow )." AS  `f` ON  `f`.`uFlowId` =  `s`.`uFlowId` LEFT JOIN ".tname( $this->t_use_step_huiqian ).( " AS `h` ON `f`.`uFlowId`=`h`.`uFlowId` AND s.uStepId = h.stepId WHERE (`s`.`uid`='".$_obfuscate_7Ri3."' OR `s`.`proxyUid`='{$_obfuscate_7Ri3}' OR (`h`.`touid`='{$_obfuscate_7Ri3}' AND `h`.`status`=0 AND `h`.`issubmit`=1)) AND `s`.`status`=1 " ).( empty( $_obfuscate_IRFhnYw� ) ? "" : "AND ".$_obfuscate_IRFhnYw�." " )." AND `f`.`uFlowId` is not null GROUP BY f.uFlowId ORDER BY `f`.`level` DESC,`f`.`updatetime` DESC;\r\n\r\n\t\t\t\tSELECT COUNT(*) AS `num` FROM tmp_wftodo_table ";
        ( $_obfuscate_D9yo3A��, CNOA_DB_USER, CNOA_DB_PWD, CNOA_DB_NAME, $_obfuscate_4Honjw�� );
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
                        $_obfuscate_j34pdsEPbI� = ( integer )$_obfuscate_gkt['num'];
                    }
                    $_obfuscate_xs33Yt_k->close( );
                }
            } while ( $_obfuscate_uGf5evmH->more_results( ) && $_obfuscate_uGf5evmH->next_result( ) );
        }
        $_obfuscate_uGf5evmH->close( );
        if ( $_obfuscate_j34pdsEPbI� <= 0 )
        {
            ( );
            $_obfuscate_NlQ� = new dataStore( );
            echo $_obfuscate_NlQ�->makeJsonData( );
            exit( );
        }
        $_obfuscate_3y0Y = "CREATE TEMPORARY TABLE IF NOT EXISTS tmp_wftodo_table(\r\n\t\t\t\t`uStepId` INTEGER NOT NULL , `hqUid` INTEGER NULL DEFAULT 0,\r\n\t\t\t\t`uFlowId` int(10) NOT NULL,`puFlowId` int(10) NOT NULL , `flowId` int(10) NOT NULL ,\r\n\t\t\t\t`flowNumber` char(100) NOT NULL ,`flowName` varchar(200) NOT NULL , `tplSort` int(3) NOT NULL ,\r\n\t\t\t\t`uid` int(10) NOT NULL , `level` int(1) NOT NULL , `reason` mediumtext NOT NULL ,\r\n\t\t\t\t`posttime` int(10) NOT NULL , `endtime` int(10) NOT NULL , `edittime` int(10) NOT NULL ,\r\n\t\t\t\t`updatetime` int(10) NOT NULL ,`attach` mediumtext NOT NULL ,`sortId` int(3) NOT NULL ,\r\n\t\t\t\t`status` int(2) NOT NULL ,`allowCallback` int(1) NOT NULL ,`allowCancel` int(1) NOT NULL ,\r\n\t\t\t\t`htmlFormContent` mediumtext NOT NULL ,`otherApp` int(10) NOT NULL,`callBackStatus` int(1) NOT NULL,\r\n\t\t\t\t`changeFlowInfo` int(1) NULL DEFAULT 0 \r\n\t\t\t\t".( CNOA_ISSAAS === TRUE ? ",`domainid` int(10) NOT NULL" : "" )."\r\n\t\t\t\t) CHARSET=utf8 ;\r\n\t\t\t\tset names UTF8;\r\n\r\n\t\t\t\tTRUNCATE TABLE tmp_wftodo_table;\r\n\r\n\t\t\t\tINSERT INTO tmp_wftodo_table( uStepId, hqUid, uFlowId, puFlowId, flowId, flowNumber, flowName, tplSort, uid, level, reason, posttime, endtime, edittime, updatetime, attach, sortId, status, allowCallback, allowCancel, htmlFormContent, otherApp, callBackStatus, changeFlowInfo".( CNOA_ISSAAS === TRUE ? ",domainid" : "" ).") \r\n\t\t\t\tSELECT `s`.`uStepId`,`h`.`touid` AS `hqUid`,  `f`.* ".$_obfuscate_FC6WZeq5G1D5_dA�." \r\n\t\t\t\tLEFT JOIN ".tname( $this->t_use_flow )." AS  `f` ON  `f`.`uFlowId` =  `s`.`uFlowId` \r\n\t\t\t\tLEFT JOIN ".tname( $this->t_use_step_huiqian ).( " AS `h` ON `f`.`uFlowId`=`h`.`uFlowId` \r\n\t\t\t\tAND s.uStepId = h.stepId WHERE (`s`.`uid`='".$_obfuscate_7Ri3."' OR `s`.`proxyUid`='{$_obfuscate_7Ri3}' OR (`h`.`touid`='{$_obfuscate_7Ri3}' AND `h`.`status`=0 AND `h`.`issubmit`=1)) \r\n\t\t\t\tAND `s`.`status`=1 " ).( empty( $_obfuscate_IRFhnYw� ) ? "" : "AND ".$_obfuscate_IRFhnYw�." " ).( empty( $_obfuscate_637B ) ? "" : " AND `f`.`level` =".$_obfuscate_pYzeLf4�." " )."\r\n\t\t\t\tAND `f`.`uFlowId` is not null GROUP BY f.uFlowId ORDER BY `f`.`level` DESC,`f`.`updatetime` DESC;\r\n\r\n\t\t\t\tSELECT * FROM tmp_wftodo_table ";
        if ( !$_obfuscate_637B )
        {
            $_obfuscate_3y0Y .= "LIMIT ".$_obfuscate_mV9HBLY�.",{$_obfuscate_xvYeh9I�} ";
        }
        $_obfuscate__eqrEQ�� = $_obfuscate_uWfP0Bouw�� = $_obfuscate_MujU5qIjWA�� = $_obfuscate_8Bnz38wN01c� = $_obfuscate_MnVpYTUssjiE0Q�� = $_obfuscate_SlAlWrNusqbf = array( );
        ( $_obfuscate_D9yo3A��, CNOA_DB_USER, CNOA_DB_PWD, CNOA_DB_NAME, $_obfuscate_4Honjw�� );
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
                        $_obfuscate__eqrEQ��[] = $_obfuscate_gkt['uid'];
                        $_obfuscate_MujU5qIjWA��[] = $_obfuscate_gkt['sortId'];
                        $_obfuscate_uWfP0Bouw��[] = $_obfuscate_gkt['flowId'];
                        $_obfuscate_MnVpYTUssjiE0Q��[] = $_obfuscate_gkt['uFlowId'];
                        $_obfuscate_8Bnz38wN01c�[] = $_obfuscate_gkt;
                    }
                    $_obfuscate_xs33Yt_k->close( );
                }
            } while ( $_obfuscate_uGf5evmH->more_results( ) && $_obfuscate_uGf5evmH->next_result( ) );
        }
        $_obfuscate_uGf5evmH->close( );
        if ( !empty( $_obfuscate_MnVpYTUssjiE0Q�� ) )
        {
            $_obfuscate_3y0Y = "SELECT `type`, `uFlowId` FROM ".tname( $this->t_use_event )." WHERE `uFlowId` IN(".implode( ",", $_obfuscate_MnVpYTUssjiE0Q�� ).")";
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
        $_obfuscate_YbfVGewvqPY� = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate__eqrEQ�� );
        $_obfuscate_RopcQP_w = $this->api_getSortByIds( $_obfuscate_MujU5qIjWA�� );
        if ( empty( $_obfuscate_uWfP0Bouw�� ) )
        {
            $_obfuscate_uWfP0Bouw�� = array( 0 );
        }
        $_obfuscate_5ZWvow6yfBMjKt0EvsXWPg�� = $CNOA_DB->db_select( array( "name", "flowId", "tplSort", "flowType" ), $this->t_set_flow, "WHERE `flowId` IN (".implode( ",", $_obfuscate_uWfP0Bouw�� ).")" );
        if ( !is_array( $_obfuscate_5ZWvow6yfBMjKt0EvsXWPg�� ) )
        {
            $_obfuscate_5ZWvow6yfBMjKt0EvsXWPg�� = array( );
        }
        $_obfuscate_U6s1cCExb6szBojQ = array( );
        $_obfuscate_MgWE1yPRnMBaRa4� = array( );
        $_obfuscate_xhCy8yogiWJQ4D00o7G2 = array( );
        foreach ( $_obfuscate_5ZWvow6yfBMjKt0EvsXWPg�� as $_obfuscate_1U6d )
        {
            $_obfuscate_U6s1cCExb6szBojQ[$_obfuscate_1U6d['flowId']] = $_obfuscate_1U6d['name'];
            $_obfuscate_MgWE1yPRnMBaRa4�[$_obfuscate_1U6d['flowId']] = $_obfuscate_1U6d['tplSort'];
            $_obfuscate_xhCy8yogiWJQ4D00o7G2[$_obfuscate_1U6d['flowId']] = $_obfuscate_1U6d['flowType'];
        }
        $_obfuscate_dDHiUSY4Qo� = array( 0 );
        foreach ( $_obfuscate_8Bnz38wN01c� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_dDHiUSY4Qo�[] = $_obfuscate_6A��['uFlowId'];
            if ( $_obfuscate_6A��['hqUid'] == $_obfuscate_7Ri3 )
            {
                $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['statusText'] = "会签中";
                $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['status'] = "toHQ";
            }
            else
            {
                $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['statusText'] = $this->f_status[$_obfuscate_6A��['status']];
                $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['status'] = "todo";
            }
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['eventType'] = $this->f_eventType[$_obfuscate_SlAlWrNusqbf[$_obfuscate_6A��['uFlowId']]];
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['level'] = $this->f_level[$_obfuscate_6A��['level']];
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['sname'] = $_obfuscate_RopcQP_w[$_obfuscate_6A��['sortId']]['name'];
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['uname'] = empty( $_obfuscate_YbfVGewvqPY�[$_obfuscate_6A��['uid']] ) ? "<span style=\"color:#FF6600\">".lang( "userNotExist" )."</span>" : $_obfuscate_YbfVGewvqPY�[$_obfuscate_6A��['uid']];
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['flowSetName'] = $_obfuscate_U6s1cCExb6szBojQ[$_obfuscate_6A��['flowId']];
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['tplSort'] = $_obfuscate_MgWE1yPRnMBaRa4�[$_obfuscate_6A��['flowId']];
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['flowType'] = $_obfuscate_xhCy8yogiWJQ4D00o7G2[$_obfuscate_6A��['flowId']];
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['posttime'] = formatdate( $_obfuscate_6A��['posttime'], "Y-m-d H:i" );
            if ( $_obfuscate_637B )
            {
                $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['flowNumber'] = "流程名称[".$_obfuscate_6A��['flowName']."] 编号:".$_obfuscate_6A��['flowNumber'];
            }
        }
        $_obfuscate_Xzj2mw�� = $CNOA_DB->db_select( "*", $this->t_use_step_child_flow, "WHERE `puFlowId` IN (".implode( ",", $_obfuscate_dDHiUSY4Qo� ).")" );
        if ( !is_array( $_obfuscate_Xzj2mw�� ) )
        {
            $_obfuscate_Xzj2mw�� = array( );
        }
        $_obfuscate_f2cxyCnzRCvtrg�� = array( );
        foreach ( $_obfuscate_Xzj2mw�� as $_obfuscate_6A�� )
        {
            if ( !isset( $_obfuscate_f2cxyCnzRCvtrg��[$_obfuscate_6A��['puFlowId']]['done'] ) )
            {
                $_obfuscate_f2cxyCnzRCvtrg��[$_obfuscate_6A��['puFlowId']]['done'] = 0;
            }
            if ( !isset( $_obfuscate_f2cxyCnzRCvtrg��[$_obfuscate_6A��['puFlowId']]['all'] ) )
            {
                $_obfuscate_f2cxyCnzRCvtrg��[$_obfuscate_6A��['puFlowId']]['all'] = 0;
            }
            if ( in_array( $_obfuscate_6A��['status'], array( 1, 2, 3 ) ) )
            {
                ++$_obfuscate_f2cxyCnzRCvtrg��[$_obfuscate_6A��['puFlowId']]['all'];
            }
            if ( in_array( $_obfuscate_6A��['status'], array( 3 ) ) )
            {
                ++$_obfuscate_f2cxyCnzRCvtrg��[$_obfuscate_6A��['puFlowId']]['done'];
            }
        }
        foreach ( $_obfuscate_8Bnz38wN01c� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['childDone'] = 0;
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['childAll'] = 0;
            if ( isset( $_obfuscate_f2cxyCnzRCvtrg��[$_obfuscate_6A��['uFlowId']] ) )
            {
                $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['childDone'] = $_obfuscate_f2cxyCnzRCvtrg��[$_obfuscate_6A��['uFlowId']]['done'];
                $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��]['childAll'] = $_obfuscate_f2cxyCnzRCvtrg��[$_obfuscate_6A��['uFlowId']]['all'];
            }
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_8Bnz38wN01c�;
        $_obfuscate_SUjPN94Er7yI->total = $_obfuscate_j34pdsEPbI�;
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
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['sname'] = $_obfuscate_6A��['name'];
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
        $_obfuscate_TlvKhtsoOQ�� = intval( getpar( $_GET, "uFlowId", 0 ) );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_GET, "stepId" );
        ( $_obfuscate_TlvKhtsoOQ�� );
        $GLOBALS['GLOBALS']['UWFCACHE'] = new wfCache( );
        $_obfuscate_SIUSR4F6 = $GLOBALS['UWFCACHE']->getFlow( );
        $_obfuscate_7qDAYo85aGA� = $CNOA_DB->db_getone( array( "flowNumber", "flowName", "uid" ), $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        ( $GLOBALS['UWFCACHE']->getFlowId( ), $_obfuscate_0Ul8BBkt, FALSE, $_obfuscate_TlvKhtsoOQ�� );
        $_obfuscate_fr489CNrjWzinRUKCuozmmU� = new wfFieldFormaterForDeal( );
        $_obfuscate_fr489CNrjWzinRUKCuozmmU�->creatorUid = $_obfuscate_7qDAYo85aGA�['uid'];
        $_obfuscate_fr489CNrjWzinRUKCuozmmU�->flowName = $_obfuscate_7qDAYo85aGA�['flowName'];
        $_obfuscate_fr489CNrjWzinRUKCuozmmU�->flowNumber = $_obfuscate_7qDAYo85aGA�['flowNumber'];
        $_obfuscate_5MjAF_AntLk� = $_obfuscate_fr489CNrjWzinRUKCuozmmU�->crteateFormHtml( );
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
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_VBCv7Q�� = getpar( $_POST, "step", 0 );
        $this->__checkChildPass( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_VBCv7Q�� );
        ( $_obfuscate_TlvKhtsoOQ�� );
        $_obfuscate_e53ODz04JQ�� = new wfCache( );
        $_obfuscate_7qDAYo85aGA� = $_obfuscate_e53ODz04JQ��->getFlow( );
        $_obfuscate_F4AbnVRh = ( integer )$_obfuscate_7qDAYo85aGA�['flowId'];
        $_obfuscate_pEvU7Kz2Yw�� = ( integer )$_obfuscate_7qDAYo85aGA�['tplSort'];
        $_obfuscate_JQJwE4USnB0� = array( );
        $_obfuscate_urgydSw7IkMKIoqpA�� = array( );
        if ( $_obfuscate_pEvU7Kz2Yw�� == 0 )
        {
            foreach ( $GLOBALS['_POST'] as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                if ( ereg( "wf_field_", $_obfuscate_5w�� ) )
                {
                    $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_field_", "", $_obfuscate_5w�� );
                    $_obfuscate_JQJwE4USnB0�[$_obfuscate_gfGsQGKrGg��] = $_obfuscate_6A��;
                }
                if ( ereg( "wf_fieldJ_", $_obfuscate_5w�� ) )
                {
                    $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_fieldJ_", "", $_obfuscate_5w�� );
                    $_obfuscate_JQJwE4USnB0�[$_obfuscate_gfGsQGKrGg��] = $_obfuscate_6A��;
                }
                if ( ereg( "wf_fieldC_", $_obfuscate_5w�� ) )
                {
                    $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_fieldC_", "", $_obfuscate_5w�� );
                    $_obfuscate_SeV31Q�� = explode( "_", $_obfuscate_gfGsQGKrGg�� );
                    $_obfuscate_gfGsQGKrGg�� = $_obfuscate_SeV31Q��[0];
                    if ( !empty( $_obfuscate_6A�� ) )
                    {
                        $_obfuscate_JQJwE4USnB0�[$_obfuscate_gfGsQGKrGg��][] = $_obfuscate_6A��;
                    }
                }
            }
            $_obfuscate_urgydSw7IkMKIoqpA�� = $this->api_getWfFieldData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, TRUE );
            foreach ( $_obfuscate_urgydSw7IkMKIoqpA�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_urgydSw7IkMKIoqpA��[$_obfuscate_5w��] = $_obfuscate_6A��;
                if ( !empty( $_obfuscate_JQJwE4USnB0�[$_obfuscate_5w��] ) )
                {
                    $_obfuscate_urgydSw7IkMKIoqpA��[$_obfuscate_5w��] = $_obfuscate_JQJwE4USnB0�[$_obfuscate_5w��];
                }
                else
                {
                    $_obfuscate_YIq2A8c� = $_obfuscate_e53ODz04JQ��->getField( $_obfuscate_5w�� );
                    if ( $_obfuscate_YIq2A8c�['otype'] == "checkbox" )
                    {
                        $_obfuscate_wKRjVA�� = array( );
                        $_obfuscate_6A�� = json_decode( $_obfuscate_6A��, TRUE );
                        if ( !is_array( $_obfuscate_6A�� ) )
                        {
                            $_obfuscate_6A�� = array( );
                        }
                        foreach ( $_obfuscate_6A�� as $_obfuscate_snM� )
                        {
                            if ( !empty( $_obfuscate_snM� ) )
                            {
                                $_obfuscate_wKRjVA��[] = $_obfuscate_snM�;
                            }
                        }
                        $_obfuscate_urgydSw7IkMKIoqpA��[$_obfuscate_5w��] = $_obfuscate_wKRjVA��;
                    }
                }
            }
        }
        $_obfuscate_7qDAYo85aGA�['flowId'] = $_obfuscate_F4AbnVRh;
        $_obfuscate_7qDAYo85aGA�['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
        $_obfuscate_7qDAYo85aGA�['step'] = $_obfuscate_VBCv7Q��;
        $_obfuscate_7qDAYo85aGA�['formData'] = $_obfuscate_urgydSw7IkMKIoqpA��;
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        ( );
        $_obfuscate_xPTkGjow2od2YSeeUUc� = new wfNextStepData( );
        $_obfuscate_NlQ�->data = $_obfuscate_xPTkGjow2od2YSeeUUc�->getNextStepInfo( $_obfuscate_7qDAYo85aGA� );
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function __checkChildPass( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_VBCv7Q�� )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "status", "uFlowId", "stepId" ), $this->t_use_step_child_flow, "WHERE `puFlowId` = ".$_obfuscate_TlvKhtsoOQ��." AND `endFlow` = 'nextStep' AND `status`!=0 AND `stepId`={$_obfuscate_VBCv7Q��}" );
        if ( empty( $_obfuscate_mPAjEGLn ) )
        {
            return;
        }
        $_obfuscate_QPIn3xJk = FALSE;
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['status'] != 3 )
            {
                if ( $_obfuscate_6A��['stepId'] != $_obfuscate_VBCv7Q�� )
                {
                }
                else
                {
                    $_obfuscate_QPIn3xJk = TRUE;
                    $_obfuscate_JC6sZe6bzW0Hyg��[] = $_obfuscate_6A��['uFlowId'];
                }
            }
        }
        if ( $_obfuscate_QPIn3xJk )
        {
            $_obfuscate_cBbyO4MnDg�� = $CNOA_DB->db_select( array( "flowNumber" ), $this->t_use_flow, "WHERE `uFlowId` IN (".implode( ",", $_obfuscate_JC6sZe6bzW0Hyg�� ).") " );
            if ( !empty( $_obfuscate_cBbyO4MnDg�� ) )
            {
                foreach ( $_obfuscate_cBbyO4MnDg�� as $_obfuscate_6A�� )
                {
                    $_obfuscate_A1jN .= "[".$_obfuscate_6A��['flowNumber']."] ";
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
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_GET, "uFlowId", getpar( $_POST, "uFlowId", 0 ) );
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", getpar( $_POST, "flowId", 0 ) );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_GET, "flowType", getpar( $_POST, "flowType", 0 ) );
        $_obfuscate_mPAjEGLn = $this->api_getStepList( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmk� );
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
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", 0 );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_GET, "flowType", 0 );
        $_obfuscate_mPAjEGLn = $this->api_getEventList( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmk� );
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
        $_obfuscate_TlvKhtsoOQ�� = ( integer )getpar( $_GET, "uFlowId" );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_GET, "stepId" );
        $_obfuscate_3y0Y = "SELECT h.id, h.stepId, h.truename, h.message, h.writetime, s.stepname AS stepName FROM ".tname( $this->t_use_step_huiqian )." AS h LEFT JOIN ".tname( $this->t_use_step )." AS s ON (s.uStepId=h.stepId AND s.uFlowId=h.uFlowId) ".( "WHERE h.uFlowId=".$_obfuscate_TlvKhtsoOQ��." ORDER BY h.posttime " );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQ�� = array( );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_gkt['writetime'] = empty( $_obfuscate_gkt['writetime'] ) ? "---" : date( "Y-m-d H:i", $_obfuscate_gkt['writetime'] );
            $_obfuscate_6RYLWQ��[] = $_obfuscate_gkt;
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_6RYLWQ��;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _submitHuiQianInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQ�� = ( integer )getpar( $_POST, "uFlowId" );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_POST, "stepId" );
        $_obfuscate_sZ_Wvha1 = $CNOA_DB->db_select( array( "touid" ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' " );
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
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_6RYLWQ��['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_6RYLWQ��['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
        $_obfuscate_6RYLWQ��['stepId'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_6RYLWQ��['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_HweFzDn2 = array( );
        $_obfuscate_QabuumMSpAVzrvaOA�� = explode( ",", getpar( $_POST, "huiqianUids", 0 ) );
        $_obfuscate_1XvASPFcSAJ6MqwjC4F = explode( ",", getpar( $_POST, "huiqianNames", 0 ) );
        foreach ( $_obfuscate_QabuumMSpAVzrvaOA�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( !( $_obfuscate_6A�� == $_obfuscate_6RYLWQ��['uid'] ) )
            {
                if ( $_obfuscate_6A�� == $_obfuscate_6RYLWQ��['uid'] )
                {
                    break;
                }
            }
            else
            {
                continue;
            }
            $_obfuscate_6RYLWQ��['touid'] = $_obfuscate_6A��;
            $_obfuscate_6RYLWQ��['truename'] = $_obfuscate_1XvASPFcSAJ6MqwjC4F[$_obfuscate_5w��];
            $_obfuscate_6RYLWQ��['issubmit'] = 0;
            $_obfuscate_LvAlJbKidRGZ = $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_use_step_huiqian );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _sendHuiQianInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQ�� = ( integer )getpar( $_POST, "uFlowId" );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_POST, "stepId" );
        $_obfuscate_UUNCCPwN8E6 = getpar( $_POST, "qfmessage" );
        $_obfuscate_sZ_Wvha1 = $CNOA_DB->db_select( "*", $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' AND `issubmit`=0" );
        if ( !is_array( $_obfuscate_sZ_Wvha1 ) )
        {
            $_obfuscate_sZ_Wvha1 = array( );
        }
        $_obfuscate_bXYR68w2KA�� = array( );
        foreach ( $_obfuscate_sZ_Wvha1 as $_obfuscate_6A�� )
        {
            $_obfuscate_bXYR68w2KA��[] = $_obfuscate_6A��['touid'];
            $_obfuscate_Bun8ntxTA��[] = $_obfuscate_6A��['id'];
        }
        $_obfuscate_3y0Y = "SELECT u.flowName, u.flowNumber, u.flowId, s.flowType, s.tplSort FROM ".tname( $this->t_use_flow )." AS u LEFT JOIN ".tname( $this->t_set_flow )." AS s ON s.flowId=u.flowId ".( "WHERE u.uFlowId=".$_obfuscate_TlvKhtsoOQ��." LIMIT 0, 1" );
        $_obfuscate_7qDAYo85aGA� = $CNOA_DB->get_one( $_obfuscate_3y0Y );
        foreach ( $_obfuscate_bXYR68w2KA�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $CNOA_DB->db_update( array(
                "qfmessage" => $_obfuscate_UUNCCPwN8E6,
                "issubmit" => 1
            ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' AND `issubmit`=0" );
            $_obfuscate_gb3bCas1['content'] = lang( "flowName" ).( "[".$_obfuscate_7qDAYo85aGA�['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_7qDAYo85aGA�['flowNumber']."]" ).lang( "needYouSign" );
            $_obfuscate_gb3bCas1['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_7qDAYo85aGA�['flowId']}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_7qDAYo85aGA�['flowType']}&tplSort={$_obfuscate_7qDAYo85aGA�['tplSort']}";
            $_obfuscate_gb3bCas1['fromid'] = $_obfuscate_Bun8ntxTA��[$_obfuscate_5w��];
            $this->addNotice( "both", $_obfuscate_6A��, $_obfuscate_gb3bCas1, "huiqian" );
            $_obfuscate_HweFzDn2[] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_6A�� );
        }
        if ( 0 < count( $_obfuscate_HweFzDn2 ) )
        {
            $_obfuscate_HweFzDn2 = implode( ",", $_obfuscate_HweFzDn2 );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3604, lang( "signPersonnel" ).( "[ ".$_obfuscate_HweFzDn2." ]，" ).lang( "flowName" ).( "[ ".$_obfuscate_7qDAYo85aGA�['flowName']." ]" ).lang( "bianHao" ).( "[ ".$_obfuscate_7qDAYo85aGA�['flowNumber']." ]" ) );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadHuiqianMsg( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_A1jN = $CNOA_DB->db_getone( array( "message", "qfmessage" ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' AND `touid` = '{$_obfuscate_7Ri3}' " );
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_A1jN;
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function _huiqianMsg( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_FYJCcRzosA�� = getpar( $_POST, "message", "" );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_cWB6Ym = getpar( $_POST, "delAtt", "" );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_faHPtjcdAp8� = $CNOA_DB->db_getfield( "attach", $this->t_use_flow, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ��." and `status`=1" );
        $_obfuscate_1_pbjTIdLU49 = json_decode( $_obfuscate_faHPtjcdAp8� );
        if ( !empty( $_obfuscate_cWB6Ym ) )
        {
            $_obfuscate_cWB6Ym = explode( ",", rtrim( $_obfuscate_cWB6Ym, "," ) );
            $_obfuscate_juwe = array( );
            foreach ( $_obfuscate_cWB6Ym as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                if ( ereg( "wf_attach_", $_obfuscate_6A�� ) )
                {
                    $_obfuscate_juwe[] = str_replace( "wf_attach_", "", $_obfuscate_6A�� );
                }
            }
            $_obfuscate_We9a = array_values( array_diff( $_obfuscate_1_pbjTIdLU49, $_obfuscate_juwe ) );
            if ( !is_array( $_obfuscate_We9a ) )
            {
                $_obfuscate_We9a = array( );
            }
            $_obfuscate_SUSIHA�� = json_encode( $_obfuscate_We9a );
            $CNOA_DB->db_update( array(
                "attach" => $_obfuscate_SUSIHA��
            ), $this->t_use_flow, " WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ��." and `status`=1" );
            $_obfuscate_1_pbjTIdLU49 = $_obfuscate_We9a;
        }
        if ( !empty( $_FILES ) )
        {
            foreach ( $GLOBALS['_FILES'] as $_obfuscate_6A�� )
            {
                ( );
                $_obfuscate_2gg� = new fs( );
                $_obfuscate_Ce9h = $_obfuscate_2gg�->addFromInternal( $_obfuscate_6A��['tmp_name'], $_obfuscate_6A��['name'], $_obfuscate_7Ri3, 8 );
                array_push( &$_obfuscate_1_pbjTIdLU49, $_obfuscate_Ce9h );
                $_obfuscate_1_pbjTIdLU49 = json_encode( $_obfuscate_1_pbjTIdLU49 );
                $CNOA_DB->db_update( array(
                    "attach" => $_obfuscate_1_pbjTIdLU49
                ), $this->t_use_flow, " WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ��." and `status`=1" );
            }
        }
        $CNOA_DB->db_update( array(
            "message" => $_obfuscate_FYJCcRzosA��,
            "writetime" => $GLOBALS['CNOA_TIMESTAMP'],
            "status" => 1
        ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' AND `touid` = '{$_obfuscate_7Ri3}' " );
        $_obfuscate_o5fQ1g�� = $CNOA_DB->db_getone( array( "id", "truename", "uid", "uFlowId" ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' AND `touid` = '{$_obfuscate_7Ri3}' " );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowId" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        $this->doneAll( "both", $_obfuscate_o5fQ1g��['id'], "huiqian" );
        $_obfuscate_AVrjaAn6 = $CNOA_DB->db_getone( array( "id" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId` = '{$_obfuscate_0Ul8BBkt}' " );
        $_obfuscate_0AITFw��['content'] = lang( "YouhaveFlow" )."[".$_obfuscate_o5fQ1g��['truename']."]".lang( "haveSubmitSign" );
        $_obfuscate_0AITFw��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
        $_obfuscate_0AITFw��['fromid'] = $_obfuscate_AVrjaAn6['id'];
        $this->addNotice( "notice", array(
            $_obfuscate_o5fQ1g��['uid']
        ), $_obfuscate_0AITFw��, "huiqian", 1 );
        $_obfuscate_JG8GuY� = array( );
        $_obfuscate_JG8GuY�['type'] = 11;
        $_obfuscate_JG8GuY�['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
        $_obfuscate_JG8GuY�['step'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_JG8GuY�['stepname'] = "会签";
        $_obfuscate_JG8GuY�['say'] = $_obfuscate_FYJCcRzosA��;
        $_obfuscate_JG8GuY�['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuY�['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $this->insertEvent( $_obfuscate_JG8GuY� );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _deleteHuiqian( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_0W8� = ( integer )getpar( $_POST, "id" );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_POST, "stepId" );
        $CNOA_DB->db_delete( $this->t_use_step_huiqian, "WHERE `id`=".$_obfuscate_0W8�." AND stepId={$_obfuscate_0Ul8BBkt} AND `uid`={$_obfuscate_7Ri3}" );
        notice::deletenotice( $_obfuscate_0W8�, $_obfuscate_0W8�, $this->t_use_step_huiqian, "write" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getSelectorUser( )
    {
        $_obfuscate_TlvKhtsoOQ�� = ( integer )getpar( $_GET, "uFlowId" );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_GET, "stepId" );
        if ( $_obfuscate_TlvKhtsoOQ�� == 0 || $_obfuscate_0Ul8BBkt == 0 )
        {
            exit( );
        }
        $_obfuscate_LeS8hw�� = getpar( $_GET, "type" );
        $_obfuscate_VVs2K5EESSsPi1o� = array( 1 => "huiqian", 2 => "fenfa" );
        if ( !in_array( $_obfuscate_LeS8hw��, $_obfuscate_VVs2K5EESSsPi1o� ) )
        {
            exit( );
        }
        ( $_obfuscate_TlvKhtsoOQ�� );
        $_obfuscate_e53ODz04JQ�� = new wfCache( );
        $_obfuscate_ktRUIU_2er7vxw�� = $_obfuscate_e53ODz04JQ��->getStepPermitByOperate( $_obfuscate_0Ul8BBkt, array_search( $_obfuscate_LeS8hw��, $_obfuscate_VVs2K5EESSsPi1o� ) );
        $_obfuscate__eqrEQ�� = $_obfuscate_2sZ8Toxw = NULL;
        if ( !empty( $_obfuscate_ktRUIU_2er7vxw��['user'] ) )
        {
            $_obfuscate__eqrEQ�� = $_obfuscate_ktRUIU_2er7vxw��['user'];
        }
        if ( !empty( $_obfuscate_ktRUIU_2er7vxw��['dept'] ) )
        {
            $_obfuscate_2sZ8Toxw = $_obfuscate_ktRUIU_2er7vxw��['dept'];
        }
        if ( !empty( $_obfuscate_ktRUIU_2er7vxw��['rule'] ) )
        {
            $_obfuscate_6mlyHg�� = explode( ",", $_obfuscate_ktRUIU_2er7vxw��['rule'] );
            $_obfuscate_1jUa = $this->_parsingRule( $_obfuscate_6mlyHg��, $_obfuscate_TlvKhtsoOQ�� );
            if ( isset( $_obfuscate__eqrEQ�� ) )
            {
                $_obfuscate__eqrEQ�� = array_merge( explode( ",", $_obfuscate__eqrEQ�� ), $_obfuscate_1jUa );
            }
            else
            {
                $_obfuscate__eqrEQ�� = $_obfuscate_1jUa;
            }
        }
        app::loadapp( "main", "user" )->api_getSelectorData( $_obfuscate__eqrEQ��, $_obfuscate_2sZ8Toxw );
        exit( );
    }

    private function _parsingRule( $_obfuscate_6mlyHg��, $_obfuscate_TlvKhtsoOQ�� )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_xs33Yt_k = array( );
    default :
        switch ( $_obfuscate_vwGQSA�� )
        {
            foreach ( $_obfuscate_6mlyHg�� as $_obfuscate_OQ�� )
            {
                list( $_obfuscate_YsVdvv0c, $_obfuscate_vwGQSA��, $_obfuscate_m5leXC9_Zg�� ) = explode( "|", $_obfuscate_OQ�� );
                if ( $_obfuscate_YsVdvv0c == "faqi" )
                {
                    if ( !isset( $_obfuscate_Ce9h ) )
                    {
                        $_obfuscate_Ce9h = $CNOA_DB->db_getfield( "uid", $this->t_use_flow, "WHERE uFlowId=".$_obfuscate_TlvKhtsoOQ�� );
                    }
                    $_obfuscate_7Ri3 = $_obfuscate_Ce9h;
                }
                else if ( $_obfuscate_YsVdvv0c == "zhuban" )
                {
                    $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
                }
                if ( !isset( $_obfuscate_oZ6tCOQeBlI� ) )
                {
                    $_obfuscate_3y0Y = "SELECT s.id, s.fid, s.path FROM ".tname( "main_user" )." AS u LEFT JOIN ".tname( "main_struct" )." AS s ON s.id=u.deptId ".( "WHERE u.uid=".$_obfuscate_7Ri3." " );
                    $_obfuscate_oZ6tCOQeBlI� = $CNOA_DB->get_one( $_obfuscate_3y0Y );
                }
            case "myDept" :
                $_obfuscate_xs33Yt_k[$_obfuscate_oZ6tCOQeBlI�['id']][] = $_obfuscate_m5leXC9_Zg��;
                continue;
            case "upDept" :
                $_obfuscate_xs33Yt_k[$_obfuscate_oZ6tCOQeBlI�['fid']][] = $_obfuscate_m5leXC9_Zg��;
                continue;
            case "myUpDept" :
                $_obfuscate_xs33Yt_k[$_obfuscate_oZ6tCOQeBlI�['id']][] = $_obfuscate_m5leXC9_Zg��;
                $_obfuscate_xs33Yt_k[$_obfuscate_oZ6tCOQeBlI�['fid']][] = $_obfuscate_m5leXC9_Zg��;
            }
            continue;
        case "allDept" :
            $_obfuscate_pp9pYw�� = explode( ",", $_obfuscate_oZ6tCOQeBlI�['path'] );
            foreach ( $_obfuscate_pp9pYw�� as $_obfuscate_iuzS )
            {
                $_obfuscate_xs33Yt_k[$_obfuscate_iuzS][] = $_obfuscate_m5leXC9_Zg��;
            }
        }
        $_obfuscate__eqrEQ�� = array( );
        foreach ( $_obfuscate_xs33Yt_k as $_obfuscate_iuzS => $_obfuscate_wNcldw�� )
        {
            if ( !empty( $_obfuscate_wNcldw�� ) )
            {
                $_obfuscate_wNcldw�� = implode( ",", array_unique( $_obfuscate_wNcldw�� ) );
                $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "uid" ), "main_user", "WHERE deptId=".$_obfuscate_iuzS." AND stationid IN ({$_obfuscate_wNcldw��})" );
                if ( !is_array( $_obfuscate_mPAjEGLn ) )
                {
                    $_obfuscate_mPAjEGLn = array( );
                }
                foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_gkt )
                {
                    $_obfuscate__eqrEQ��[] = $_obfuscate_gkt['uid'];
                }
            }
        }
        if ( empty( $_obfuscate__eqrEQ�� ) )
        {
            return array( 0 );
        }
        return array_unique( $_obfuscate__eqrEQ�� );
    }

    private function _getFenfaList( )
    {
        global $CNOA_DB;
        $_obfuscate_TlvKhtsoOQ�� = ( integer )getpar( $_GET, "uFlowId" );
        $_obfuscate_3y0Y = "SELECT f.uFenfaId AS id, m.truename AS fenfaUname, u.truename AS viewUname, f.viewtime, f.say, f.isread , f.stepId FROM ".tname( $this->t_use_fenfa )." AS f LEFT JOIN ".tname( "main_user" )." AS m ON m.uid=f.fenfauid LEFT JOIN ".tname( "main_user" )." AS u ON u.uid=f.touid ".( "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ��." ORDER BY uFenfaId" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQ�� = array( );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_gkt['viewtime'] = empty( $_obfuscate_gkt['viewtime'] ) ? "---" : date( "Y-m-d H:i", $_obfuscate_gkt['viewtime'] );
            $_obfuscate_gkt['isread'] = $_obfuscate_gkt['isread'] == 0 ? "未阅读" : "已阅读";
            $_obfuscate_6RYLWQ��[] = $_obfuscate_gkt;
        }
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_6RYLWQ��;
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function _addFenfa( $_obfuscate_TlvKhtsoOQ�� = "", $_obfuscate_rHwsX0gg = array( ), $_obfuscate_0Ul8BBkt, $_obfuscate_CRya7qfm = FALSE )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        if ( $_obfuscate_TlvKhtsoOQ�� == "" )
        {
            $_obfuscate_TlvKhtsoOQ�� = ( integer )getpar( $_POST, "uFlowId" );
        }
        if ( empty( $_obfuscate_rHwsX0gg ) )
        {
            $_obfuscate_rHwsX0gg = explode( ",", getpar( $_POST, "toUids" ) );
        }
        if ( !$_obfuscate_rHwsX0gg[0] )
        {
            msg::callback( FALSE, "您还没有选择用户!" );
        }
        if ( empty( $_obfuscate_0Ul8BBkt ) )
        {
            $_obfuscate_0Ul8BBkt = ( integer )getpar( $_POST, "stepId" );
        }
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
            $_obfuscate_rLpOghzteElp['stepId'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_Ce9h = $CNOA_DB->db_insert( $_obfuscate_rLpOghzteElp, $this->t_use_fenfa );
            $_obfuscate_6RYLWQ��['content'] = lang( "flowName" ).( "[".$_obfuscate_7qDAYo85aGA�['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_7qDAYo85aGA�['flowNumber']."]分发给你阅读。" );
            $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_7qDAYo85aGA�['flowId']}&flowType={$_obfuscate_7qDAYo85aGA�['flowType']}&tplSort={$_obfuscate_7qDAYo85aGA�['tplSort']}";
            $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_Ce9h;
            $this->addNotice( "notice", $_obfuscate_rLpOghzteElp['touid'], $_obfuscate_6RYLWQ��, "fenfa" );
            ++$_obfuscate_Ybai;
        }
        if ( 0 < $_obfuscate_Ybai )
        {
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3604, "分发人员，流程名称[ ".$_obfuscate_7qDAYo85aGA�['flowName']." ] ".lang( "bianHao" ).( "[ ".$_obfuscate_7qDAYo85aGA�['flowNumber']." ]" ) );
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
        $_obfuscate_0W8� = ( integer )getpar( $_POST, "id" );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_POST, "stepId" );
        $CNOA_DB->db_delete( $this->t_use_fenfa, "WHERE `uFenfaId`=".$_obfuscate_0W8�." AND `stepId`={$_obfuscate_0Ul8BBkt}" );
        notice::deletenotice( $_obfuscate_0W8�, $_obfuscate_0W8�, $this->t_use_fenfa, "fenfa" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function _loadFormHtmlView( $_obfuscate_F4AbnVRh = "", $_obfuscate_TlvKhtsoOQ�� = "", $_obfuscate_VBCv7Q�� = "", $_obfuscate_LeS8hw�� = "", $_obfuscate_XRy5y1Ql2aA� = FALSE )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_pcmxgqXbvl0yPCL_KHM� = getpar( $_POST, "childSeeParent", "" );
        $_obfuscate_tp9SP3Q9McA� = getpar( $_POST, "puStepId", "" );
        if ( !$_obfuscate_TlvKhtsoOQ�� )
        {
            $_obfuscate_TlvKhtsoOQ�� = intval( getpar( $_GET, "uFlowId", getpar( $_POST, "uFlowId", 0 ) ) );
        }
        if ( !$_obfuscate_VBCv7Q�� )
        {
            $_obfuscate_WYOD1IJTSw�� = intval( getpar( $_GET, "stepId", getpar( $_POST, "stepId", 0 ) ) );
        }
        if ( !$_obfuscate_LeS8hw�� )
        {
            $_obfuscate_LeS8hw�� = getpar( $_POST, "type", "" );
        }
        if ( $_obfuscate_XRy5y1Ql2aA� && $_obfuscate_LeS8hw�� == "show" )
        {
            $_obfuscate_WYOD1IJTSw�� = $_obfuscate_VBCv7Q��;
        }
        ( $_obfuscate_TlvKhtsoOQ�� );
        $GLOBALS['GLOBALS']['UWFCACHE'] = new wfCache( );
        $_obfuscate_SIUSR4F6 = $GLOBALS['UWFCACHE']->getFlow( );
        ( $GLOBALS['UWFCACHE']->getFlowId( ), $_obfuscate_WYOD1IJTSw��, FALSE, $_obfuscate_LeS8hw��, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_pcmxgqXbvl0yPCL_KHM�, $_obfuscate_tp9SP3Q9McA� );
        $_obfuscate_P5_qcMiY39uereSduytrzD8� = new wfFieldFormaterForView( );
        $_obfuscate_5MjAF_AntLk� = $_obfuscate_P5_qcMiY39uereSduytrzD8�->crteateFormHtml( );
        if ( $_obfuscate_XRy5y1Ql2aA� )
        {
            return $_obfuscate_5MjAF_AntLk�;
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->total = 0;
        $_obfuscate_SUjPN94Er7yI->pageset = $_obfuscate_SIUSR4F6['pageset'];
        $_obfuscate_SUjPN94Er7yI->data = array(
            "formHtml" => $_obfuscate_5MjAF_AntLk�
        );
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _loadEntrustForm( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_7qDAYo85aGA� = $CNOA_DB->db_getone( "*", $this->t_use_proxy_uflow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `flowId`!=0" );
        if ( empty( $_obfuscate_7qDAYo85aGA� ) )
        {
            $_obfuscate_7qDAYo85aGA� = array( );
        }
        else
        {
            $_obfuscate_7qDAYo85aGA�['toName'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_7qDAYo85aGA�['touid'] );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_7qDAYo85aGA�;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _submitEntrustFormData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_ecqaH_ev7A�� = getpar( $_POST, "uStepId", 0 );
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_6RYLWQ��['fromuid'] = $_obfuscate_7Ri3;
        $_obfuscate_6RYLWQ��['touid'] = getpar( $_POST, "touid", 0 );
        $_obfuscate_6RYLWQ��['uFlowId'] = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_6RYLWQ��['flowId'] = getpar( $_POST, "flowId", 0 );
        $_obfuscate_6RYLWQ��['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_1l6P = getpar( $_POST, "say", "无" );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_SIUSR4F6 = $CNOA_DB->db_getone( "*", $this->t_use_proxy_uflow, "WHERE `fromuid`='".$_obfuscate_6RYLWQ��['fromuid']."' AND (`flowId`!=0 AND `flowId`='{$_obfuscate_6RYLWQ��['flowId']}')" );
        if ( !empty( $_obfuscate_SIUSR4F6 ) )
        {
            $CNOA_DB->db_delete( $this->t_use_proxy_uflow, "WHERE `fromuid`='".$_obfuscate_6RYLWQ��['fromuid']."' AND (`flowId`!=0 AND `flowId`='{$_obfuscate_6RYLWQ��['flowId']}')" );
        }
        if ( $_obfuscate_6RYLWQ��['touid'] == $_obfuscate_7Ri3 )
        {
            msg::callback( FALSE, lang( "principalNotChooseOwn" ) );
        }
        else
        {
            $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_6RYLWQ��['uFlowId']."'  " );
            $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "id" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_6RYLWQ��['uFlowId']."' AND `uStepId` = '{$_obfuscate_ecqaH_ev7A��}' " );
            $this->deleteNotice( "both", $_obfuscate_Tx7M9W['id'], "todo" );
            $_obfuscate_0AITFw��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "by" )."[".$CNOA_SESSION->get( "TRUENAME" )."]".lang( "entrusteYouNeedYouApp" )."[".$_obfuscate_1l6P."]";
            $_obfuscate_0AITFw��['href'] = "&uFlowId=".$_obfuscate_6RYLWQ��['uFlowId']."&flowId={$_obfuscate_6RYLWQ��['flowId']}&step={$_obfuscate_ecqaH_ev7A��}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
            $_obfuscate_0AITFw��['fromid'] = $_obfuscate_Tx7M9W['id'];
            $this->addNotice( "both", $_obfuscate_6RYLWQ��['touid'], $_obfuscate_0AITFw��, "todo" );
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_use_proxy_uflow );
            $CNOA_DB->db_update( array(
                "proxyUid" => $_obfuscate_6RYLWQ��['touid']
            ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_6RYLWQ��['uFlowId']."' AND `uStepId`='{$_obfuscate_ecqaH_ev7A��}' AND `uid`='{$_obfuscate_6RYLWQ��['fromuid']}'" );
        }
        $_obfuscate_JG8GuY� = array( );
        $_obfuscate_JG8GuY�['uFlowId'] = $_obfuscate_6RYLWQ��['uFlowId'];
        $_obfuscate_JG8GuY�['type'] = 8;
        $_obfuscate_JG8GuY�['step'] = $_obfuscate_ecqaH_ev7A��;
        $_obfuscate_JG8GuY�['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuY�['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuY�['say'] = $_obfuscate_1l6P;
        $_obfuscate_JG8GuY�['stepname'] = $CNOA_DB->db_getfield( "stepName", $this->t_set_step, "WHERE `flowId`='".$_obfuscate_6RYLWQ��['flowId']."' AND `stepId`='{$_obfuscate_ecqaH_ev7A��}'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3604, lang( "entrustFlowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _submitRejectAbout( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_TlvKhtsoOQ�� = intval( getpar( $_POST, "uFlowId", 0 ) );
        $_obfuscate_0Ul8BBkt = intval( getpar( $_POST, "step", 0 ) );
        $_obfuscate_hXVTMt5XyOk� = $this->_checkDraft( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_0Ul8BBkt );
        if ( $_obfuscate_hXVTMt5XyOk� )
        {
            unlink( $_obfuscate_hXVTMt5XyOk� );
        }
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_qZkmBg�� = array( );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber", "flowId", "uid" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        $_obfuscate_SXBi6VrH2yE� = $this->getProxyFromuid( $_obfuscate_hTew0boWJESy['flowId'], $_obfuscate_7Ri3 );
        if ( !empty( $_obfuscate_SXBi6VrH2yE� ) )
        {
            $_obfuscate_7Ri3 = $_obfuscate_SXBi6VrH2yE�;
        }
        if ( $_obfuscate_XkuTFqZ6Tmk� == 0 )
        {
            ( $_obfuscate_TlvKhtsoOQ�� );
            $_obfuscate_e53ODz04JQ�� = new wfCache( );
            if ( !$_obfuscate_e53ODz04JQ��->isUserInStep( $_obfuscate_0Ul8BBkt, $_obfuscate_7Ri3 ) )
            {
                msg::callback( FALSE, lang( "stepIsNotYouDeal" ) );
            }
            $_obfuscate_5NhzjnJq_f8� = $_obfuscate_e53ODz04JQ��->getStepByStepId( $_obfuscate_0Ul8BBkt );
            if ( $_obfuscate_5NhzjnJq_f8�['allowReject'] != 1 )
            {
                msg::callback( FALSE, lang( "stepNotRefuse" ) );
            }
        }
        $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "pStepId", "uStepId", "id", "stepname" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId` = '{$_obfuscate_0Ul8BBkt}' " );
        $_obfuscate_bIDuCBSCgg�� = $CNOA_DB->db_getone( array( "id", "proxyUid", "uid", "uStepId" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId` = '{$_obfuscate_Tx7M9W['pStepId']}' " );
        if ( $_obfuscate_XkuTFqZ6Tmk� == 0 && $_obfuscate_pEvU7Kz2Yw�� == 0 )
        {
            $_obfuscate_KXAlJs� = $_obfuscate_e53ODz04JQ��->getFields( );
            $_obfuscate_zUsRh9c_Fjc� = array( );
            foreach ( $_obfuscate_KXAlJs� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_zUsRh9c_Fjc�[$_obfuscate_6A��['id']] = "";
            }
            $_obfuscate_gf7VaSeaaPca_Vs� = $_obfuscate_e53ODz04JQ��->getDetailFields( );
            $_obfuscate_dGoPOiQ2Iw5a = array( );
            $_obfuscate_Y4GjuRyoCOQ3 = array( );
            foreach ( $_obfuscate_gf7VaSeaaPca_Vs� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_6A��['id']] = $_obfuscate_6A��['fid'];
                $_obfuscate_Y4GjuRyoCOQ3[$_obfuscate_6A��['id']] = "";
            }
            $_obfuscate_kM1PB1K = array( );
            $_obfuscate_8XjS1n72 = array( );
            $_obfuscate_o5fQ1g�� = array( );
            $_obfuscate_Thg� = $CNOA_DB->db_select( "*", $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `id` <= '{$_obfuscate_bIDuCBSCgg��['id']}' " );
            if ( !is_array( $_obfuscate_Thg� ) )
            {
                $_obfuscate_Thg� = array( );
            }
            foreach ( $_obfuscate_Thg� as $_obfuscate_CIKnWK8� => $_obfuscate_WkU7ZbnYkg�� )
            {
                foreach ( $_obfuscate_e53ODz04JQ��->getStepFields( $_obfuscate_WkU7ZbnYkg��['uStepId'], self::FIELD_RULE_NORMAL ) as $_obfuscate_LQ8UKg�� => $ea )
                {
                    if ( !( $ea['show'] == 1 ) && !( $ea['write'] == 1 ) )
                    {
                        unset( $_obfuscate_zUsRh9c_Fjc�[$ea['fieldId']] );
                    }
                }
                foreach ( $_obfuscate_e53ODz04JQ��->getStepFields( $_obfuscate_WkU7ZbnYkg��['uStepId'], self::FIELD_RULE_DETAIL ) as $_obfuscate_LQ8UKg�� => $_obfuscate_RsA� )
                {
                    if ( !( $_obfuscate_RsA�['show'] == 1 ) && !( $_obfuscate_RsA�['write'] == 1 ) )
                    {
                        unset( $_obfuscate_Y4GjuRyoCOQ3[$_obfuscate_RsA�['fieldId']] );
                    }
                }
            }
            foreach ( $_obfuscate_zUsRh9c_Fjc� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_kM1PB1K["T_".$_obfuscate_5w��] = "";
            }
            foreach ( $_obfuscate_Y4GjuRyoCOQ3 as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_8XjS1n72[$_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_5w��]]["D_".$_obfuscate_5w��] = "";
            }
        }
        if ( $_obfuscate_bIDuCBSCgg��['uStepId'] == $_obfuscate_Tx7M9W['pStepId'] )
        {
            $_obfuscate_o5fQ1g��['uid'] = $_obfuscate_bIDuCBSCgg��['uid'];
            if ( empty( $_obfuscate_bIDuCBSCgg��['proxyUid'] ) )
            {
                $_obfuscate_o5fQ1g��['proxyUid'] = $this->getProxyUid( $_obfuscate_hTew0boWJESy['flowId'], $_obfuscate_CArovL72w�� );
            }
            else
            {
                $_obfuscate_o5fQ1g��['proxyUid'] = $_obfuscate_bIDuCBSCgg��['proxyUid'];
            }
            $_obfuscate_EOibFA�� = array(
                $_obfuscate_bIDuCBSCgg��['uid'],
                $_obfuscate_o5fQ1g��['proxyUid']
            );
            $_obfuscate_1l6P = getpar( $_POST, "say", "拒绝该流程" );
            $thisStep = $_obfuscate_Tx7M9W;
            $this->doneAll( "both", $thisStep['id'], "todo" );
            $this->doneAll( "notice", $thisStep['id'], "huiqian", 1 );
            if ( $_obfuscate_XkuTFqZ6Tmk� == 1 )
            {
                $CNOA_DB->db_update( array( "status" => 0 ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId` = '{$_obfuscate_0Ul8BBkt}' " );
                $CNOA_DB->db_update( array( "etime" => 0, "say" => "", "status" => 1, "stepType" => 1, "dealUid" => 0 ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId` = '{$_obfuscate_bIDuCBSCgg��['uStepId']}' " );
            }
            else
            {
                $CNOA_DB->db_delete( $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId` = '{$_obfuscate_0Ul8BBkt}' " );
                $CNOA_DB->db_update( array(
                    "etime" => 0,
                    "say" => "",
                    "nStepId" => 0,
                    "status" => 1,
                    "proxyUid" => $_obfuscate_o5fQ1g��['proxyUid']
                ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId` = '{$_obfuscate_bIDuCBSCgg��['uStepId']}' " );
            }
            if ( $_obfuscate_XkuTFqZ6Tmk� == 0 )
            {
                $_obfuscate_qZkmBg�� = $_obfuscate_e53ODz04JQ��->getFlow( );
            }
            else
            {
                $_obfuscate_qZkmBg�� = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_hTew0boWJESy['flowId']."'" );
            }
            if ( $_obfuscate_qZkmBg��['noticeAtGoBack'] != 4 )
            {
                if ( $_obfuscate_qZkmBg��['noticeAtGoBack'] == 2 )
                {
                    $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "by" )."[".$CNOA_SESSION->get( "TRUENAME" )."]".lang( "reject" );
                    $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step=2&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
                    $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "uid", "id", "uStepId" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId` = '2' " );
                    $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_Tx7M9W['id'];
                    $this->addNotice( "notice", $_obfuscate_hTew0boWJESy['uid'], $_obfuscate_6RYLWQ��, "tuihui", 2 );
                }
                else if ( $_obfuscate_qZkmBg��['noticeAtGoBack'] == 3 )
                {
                    $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "by" )."[".$CNOA_SESSION->get( "TRUENAME" )."]".lang( "reject" );
                    $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "uid", "proxyUid", "dealUid", "id", "uStepId" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId` != '{$_obfuscate_Tx7M9W['pStepId']}' AND `uStepId` != '2' " );
                    if ( !is_array( $_obfuscate_Tx7M9W ) )
                    {
                        $_obfuscate_Tx7M9W = array( );
                    }
                    foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5w�� => $_obfuscate_6A�� )
                    {
                        $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_Tx7M9W['uStepId']}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
                        $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_Tx7M9W['id'];
                        $this->addNotice( "notice", array(
                            $_obfuscate_Tx7M9W['dealUid'],
                            $_obfuscate_Tx7M9W['uid'],
                            $_obfuscate_Tx7M9W['proxyUid']
                        ), $_obfuscate_6RYLWQ��, "tuihui", 1 );
                    }
                }
                $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "by" )."[".$CNOA_SESSION->get( "TRUENAME" )."]".lang( "rejectNeedYouAppAG" )."<br /> ".lang( "rejectReason" ).":[".$_obfuscate_1l6P."]";
                $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_bIDuCBSCgg��['uStepId']}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
                $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_bIDuCBSCgg��['id'];
                $this->addNotice( "both", array(
                    $_obfuscate_bIDuCBSCgg��['proxyUid'],
                    $_obfuscate_bIDuCBSCgg��['uid']
                ), $_obfuscate_6RYLWQ��, "todo" );
            }
        }
        $_obfuscate_JG8GuY� = array( );
        $_obfuscate_JG8GuY�['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
        $_obfuscate_JG8GuY�['type'] = 5;
        $_obfuscate_JG8GuY�['step'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_JG8GuY�['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuY�['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuY�['say'] = $_obfuscate_1l6P;
        if ( $_obfuscate_XkuTFqZ6Tmk� == 0 )
        {
            $_obfuscate_JG8GuY�['stepname'] = $CNOA_DB->db_getfield( "stepName", $this->t_set_step, "WHERE `flowId`='".$_obfuscate_hTew0boWJESy['flowId']."' AND `stepId`='{$_obfuscate_0Ul8BBkt}'" );
        }
        else
        {
            $_obfuscate_JG8GuY�['stepname'] = $thisStep['stepname'];
        }
        $this->insertEvent( $_obfuscate_JG8GuY� );
        if ( $_obfuscate_XkuTFqZ6Tmk� == 0 && $_obfuscate_pEvU7Kz2Yw�� == 0 )
        {
            $CNOA_DB->db_update( $_obfuscate_kM1PB1K, "z_wf_t_".$_obfuscate_hTew0boWJESy['flowId'], "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
            if ( !empty( $_obfuscate_8XjS1n72 ) )
            {
                foreach ( $_obfuscate_8XjS1n72 as $_obfuscate_5w�� => $_obfuscate_6A�� )
                {
                    $CNOA_DB->db_update( $_obfuscate_6A��, "z_wf_d_".$_obfuscate_hTew0boWJESy['flowId']."_".$_obfuscate_5w��, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
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
        $_obfuscate_TlvKhtsoOQ�� = intval( getpar( $_POST, "uFlowId", 0 ) );
        $_obfuscate_0Ul8BBkt = intval( getpar( $_POST, "step", 0 ) );
        $_obfuscate_HYI4w55m58H2WjCs = $this->getAllStepByUFlowId( $_obfuscate_TlvKhtsoOQ�� );
        $_obfuscate_j9eamhY� = array( );
        foreach ( $_obfuscate_HYI4w55m58H2WjCs['stepInfo'] as $_obfuscate_6A�� )
        {
            if ( in_array( $_obfuscate_6A��['status'], array(
                self::STEP_STATUS_DONE,
                self::STEP_STATUS_RESERVATION
            ) ) && !( $_obfuscate_6A��['dealUid'] != 0 ) || !( $_obfuscate_0Ul8BBkt == $_obfuscate_6A��['uStepId'] ) )
            {
                $_obfuscate_j9eamhY�[$_obfuscate_6A��['uStepId']] = $_obfuscate_6A��;
            }
        }
        unset( $_obfuscate_HYI4w55m58H2WjCs );
        $_obfuscate_pp9pYw�� = array( );
        $this->getPath( $_obfuscate_j9eamhY�, $_obfuscate_0Ul8BBkt, $_obfuscate_pp9pYw�� );
        $_obfuscate_pp9pYw�� = array_reverse( $_obfuscate_pp9pYw��, TRUE );
        array_pop( &$_obfuscate_pp9pYw�� );
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_7w�� = 1;
        foreach ( $_obfuscate_pp9pYw�� as $_obfuscate_0W8� => $_obfuscate_WKs3DA�� )
        {
            $_obfuscate_VBCv7Q�� = $_obfuscate_j9eamhY�[$_obfuscate_0W8�];
            if ( !( $_obfuscate_VBCv7Q��['stepType'] != 4 ) && is_array( $_obfuscate_WKs3DA�� ) )
            {
                $_obfuscate_LQ8UKg�� = array( );
                $_obfuscate_LQ8UKg��['boxLabel'] = $_obfuscate_VBCv7Q��['stepname'];
                $_obfuscate_LQ8UKg��['inputValue'] = $_obfuscate_VBCv7Q��['uStepId'];
                $_obfuscate_LQ8UKg��['name'] = "uStepId";
                $_obfuscate_LQ8UKg��['checked'] = $_obfuscate_7w�� == 1 ? TRUE : FALSE;
                if ( is_array( $_obfuscate_WKs3DA�� ) && !empty( $_obfuscate_WKs3DA�� ) )
                {
                    foreach ( $_obfuscate_WKs3DA�� as $_obfuscate_cO77 => $_obfuscate_9HHDStdl )
                    {
                        $_obfuscate_VtHt = array( );
                        foreach ( $_obfuscate_9HHDStdl as $_obfuscate_5w�� => $_obfuscate_6A�� )
                        {
                            $_obfuscate_fPSqRzE� = $_obfuscate_j9eamhY�[$_obfuscate_5w��];
                            $_obfuscate_VtHt[] = array(
                                "id" => "bf_step_".$_obfuscate_VBCv7Q��['uStepId']."_{$_obfuscate_fPSqRzE�['uStepId']}",
                                "boxLabel" => $_obfuscate_fPSqRzE�['stepname'],
                                "inputValue" => $_obfuscate_fPSqRzE�['uStepId'],
                                "name" => "bingfaStepId_".$_obfuscate_cO77
                            );
                        }
                        $_obfuscate_LQ8UKg��['bingfaChild'][] = $_obfuscate_VtHt;
                    }
                }
                $_obfuscate_6RYLWQ��[] = $_obfuscate_LQ8UKg��;
                ++$_obfuscate_7w��;
            }
        }
        if ( $_obfuscate_6y3M )
        {
            return $_obfuscate_6RYLWQ��;
        }
        msg::callback( TRUE, json_encode( $_obfuscate_6RYLWQ�� ) );
    }

    private function getPath( $_obfuscate_j9eamhY�, $_obfuscate_0Ul8BBkt, &$_obfuscate_pp9pYw�� )
    {
        $_obfuscate_WKs3DA�� = $_obfuscate_j9eamhY�[$_obfuscate_0Ul8BBkt];
        if ( isset( $_obfuscate_WKs3DA�� ) )
        {
            if ( $_obfuscate_WKs3DA��['steType'] != 4 && empty( $_obfuscate_pp9pYw��[$_obfuscate_0Ul8BBkt] ) )
            {
                $_obfuscate_pp9pYw��[$_obfuscate_0Ul8BBkt] = $_obfuscate_0Ul8BBkt;
            }
            if ( $_obfuscate_WKs3DA��['stepType'] == 5 )
            {
                $_obfuscate_BQjjgu6Bg� = $_obfuscate_WKs3DA��['pStepId'];
                foreach ( $_obfuscate_j9eamhY� as $p )
                {
                    if ( $p['pStepId'] == $_obfuscate_BQjjgu6Bg� )
                    {
                        $_obfuscate_9HHDStdl = array( );
                        $this->getBranch( $_obfuscate_9HHDStdl, $_obfuscate_j9eamhY�, $p['uStepId'], $_obfuscate_0Ul8BBkt );
                        if ( !empty( $_obfuscate_9HHDStdl ) )
                        {
                            $_obfuscate_pp9pYw��[$_obfuscate_BQjjgu6Bg�][] = $_obfuscate_9HHDStdl;
                        }
                    }
                }
            }
            $this->getPath( $_obfuscate_j9eamhY�, $_obfuscate_WKs3DA��['pStepId'], $_obfuscate_pp9pYw�� );
        }
    }

    private function getBranch( &$_obfuscate_pp9pYw��, $_obfuscate_j9eamhY�, $_obfuscate_0Ul8BBkt, $_obfuscate_il12XepTzMCB = NULL )
    {
        if ( $_obfuscate_0Ul8BBkt == $_obfuscate_il12XepTzMCB )
        {
            return;
        }
        $_obfuscate_VBCv7Q�� = $_obfuscate_j9eamhY�[$_obfuscate_0Ul8BBkt];
        if ( isset( $_obfuscate_VBCv7Q�� ) )
        {
            $this->getBranch( $_obfuscate_pp9pYw��, $_obfuscate_j9eamhY�, $_obfuscate_VBCv7Q��['nStepId'], $_obfuscate_il12XepTzMCB );
            $_obfuscate_pp9pYw��[$_obfuscate_0Ul8BBkt] = $_obfuscate_0Ul8BBkt;
        }
    }

    private function _submitPrevstepData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_ecqaH_ev7A�� = getpar( $_POST, "uStepId", 0 );
        $_obfuscate_1l6P = getpar( $_POST, "say", "退回该流程" );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_hXVTMt5XyOk� = $this->_checkDraft( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_0Ul8BBkt );
        if ( $_obfuscate_hXVTMt5XyOk� )
        {
            unlink( $_obfuscate_hXVTMt5XyOk� );
        }
        $_obfuscate_HYI4w55m58H2WjCs = $this->getAllStepByUFlowId( $_obfuscate_TlvKhtsoOQ�� );
        $_obfuscate_j9eamhY� = $_obfuscate_HYI4w55m58H2WjCs['stepInfo'];
        $_obfuscate_hsTQkq6NOXRA = $_obfuscate_j9eamhY�;
        ( $_obfuscate_TlvKhtsoOQ�� );
        $_obfuscate_e53ODz04JQ�� = new wfCache( );
        $_obfuscate_7qDAYo85aGA� = $_obfuscate_e53ODz04JQ��->getFlow( );
        $_obfuscate_XkuTFqZ6Tmk� = $_obfuscate_7qDAYo85aGA�['flowType'];
        $_obfuscate_pEvU7Kz2Yw�� = $_obfuscate_7qDAYo85aGA�['tplSort'];
        if ( $_obfuscate_XkuTFqZ6Tmk� == 0 )
        {
            $_obfuscate_5NhzjnJq_f8� = $_obfuscate_e53ODz04JQ��->getStepByStepId( $_obfuscate_0Ul8BBkt );
            if ( $_obfuscate_5NhzjnJq_f8�['allowTuihui'] != 1 )
            {
                msg::callback( FALSE, lang( "noPermitToDo" ) );
            }
            if ( $_obfuscate_HYI4w55m58H2WjCs['stepInfo'][$_obfuscate_0Ul8BBkt]['status'] != 1 )
            {
                msg::callback( FALSE, lang( "noPermitOpt" ) );
            }
        }
        $_obfuscate_v1oL = $CNOA_DB->db_getfield( "deal", $this->t_use_deal_way, "WHERE `stepId`=".$_obfuscate_ecqaH_ev7A��." AND `flowId`={$_obfuscate_7qDAYo85aGA�['flowId']}" );
        $_obfuscate_pp9pYw�� = array( );
        $this->getPath( $_obfuscate_j9eamhY�, $_obfuscate_0Ul8BBkt, $_obfuscate_pp9pYw�� );
        $_obfuscate_umeHLFZ6 = $_obfuscate_j9eamhY�[$_obfuscate_ecqaH_ev7A��];
        $_obfuscate_NenxpX50gw�� = $_obfuscate_j9eamhY�[$_obfuscate_0Ul8BBkt];
        $_obfuscate_rX2e4Qw_AQ�� = array( );
        $_obfuscate_ZRB1fr3t0YM� = array( );
        $_obfuscate_Tz0Oow9UfYEsg�� = array( );
        if ( $_obfuscate_umeHLFZ6['stepType'] == 4 )
        {
            $_obfuscate_Q0seLzjzqtYp = array( );
            foreach ( $GLOBALS['_POST'] as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                if ( preg_match( "/bingfaStepId_\\d+/", $_obfuscate_5w�� ) )
                {
                    $_obfuscate_Q0seLzjzqtYp[] = getpar( $_POST, $_obfuscate_5w�� );
                }
            }
            $_obfuscate_FcvH8FD7nIf1tXFjmw�� = 0;
            foreach ( $_obfuscate_pp9pYw�� as $_obfuscate_Cy1W => $_obfuscate_WKs3DA�� )
            {
                if ( $_obfuscate_Cy1W == $_obfuscate_ecqaH_ev7A�� )
                {
                    $_obfuscate_FcvH8FD7nIf1tXFjmw�� = array_pop( &$_obfuscate_rX2e4Qw_AQ�� );
                }
                if ( is_array( $_obfuscate_WKs3DA�� ) )
                {
                    foreach ( $_obfuscate_WKs3DA�� as $_obfuscate_cO77 => $_obfuscate_9HHDStdl )
                    {
                        $_obfuscate_g0z4XGkB83_1 = array_intersect( $_obfuscate_9HHDStdl, $_obfuscate_Q0seLzjzqtYp );
                        if ( !is_array( $_obfuscate_9HHDStdl ) && empty( $_obfuscate_g0z4XGkB83_1 ) )
                        {
                            foreach ( $_obfuscate_9HHDStdl as $_obfuscate_1tGeYg�� )
                            {
                                if ( in_array( $_obfuscate_1tGeYg��, $_obfuscate_Q0seLzjzqtYp ) )
                                {
                                    $_obfuscate_ZRB1fr3t0YM�[] = $_obfuscate_1tGeYg��;
                                }
                                else
                                {
                                    $_obfuscate_rX2e4Qw_AQ��[] = $_obfuscate_1tGeYg��;
                                }
                            }
                        }
                    }
                }
                if ( $_obfuscate_Cy1W == $_obfuscate_ecqaH_ev7A�� )
                {
                    break;
                }
                $_obfuscate_rX2e4Qw_AQ��[] = $_obfuscate_Cy1W;
            }
            if ( empty( $_obfuscate_g0z4XGkB83_1 ) )
            {
                msg::callback( FALSE, lang( "pleaseSelectStep" ) );
            }
            $_obfuscate_6RYLWQ�� = array( "proxyUid" => 0, "dealUid" => 0, "status" => 0, "stime" => 0, "etime" => 0, "timegap" => "0", "say" => "", "nStepId" => 0 );
            $CNOA_DB->db_update( $_obfuscate_6RYLWQ��, $this->t_use_step, "WHERE `id`=".$_obfuscate_j9eamhY�[$_obfuscate_FcvH8FD7nIf1tXFjmw��]['id']." AND `uFlowId`={$_obfuscate_TlvKhtsoOQ��} " );
        }
        else
        {
            foreach ( $_obfuscate_pp9pYw�� as $_obfuscate_Cy1W => $_obfuscate_WKs3DA�� )
            {
                if ( $_obfuscate_Cy1W == $_obfuscate_ecqaH_ev7A�� )
                {
                    $_obfuscate_ZRB1fr3t0YM�[] = $_obfuscate_Cy1W;
                }
                else
                {
                    if ( $_obfuscate_j9eamhY�[$_obfuscate_Cy1W]['stepType'] == 4 )
                    {
                        $_obfuscate_BQjjgu6Bg� = $_obfuscate_j9eamhY�[$_obfuscate_Cy1W]['uStepId'];
                        foreach ( $_obfuscate_j9eamhY� as $p )
                        {
                            if ( $p['pStepId'] == $_obfuscate_BQjjgu6Bg� )
                            {
                                $_obfuscate_9HHDStdl = array( );
                                $this->getBranch( $_obfuscate_9HHDStdl, $_obfuscate_j9eamhY�, $p['uStepId'], $_obfuscate_0Ul8BBkt );
                                if ( !empty( $_obfuscate_9HHDStdl ) )
                                {
                                    $_obfuscate_rX2e4Qw_AQ�� = array_merge( $_obfuscate_rX2e4Qw_AQ��, $_obfuscate_9HHDStdl );
                                }
                            }
                        }
                    }
                    $_obfuscate_rX2e4Qw_AQ��[] = $_obfuscate_Cy1W;
                    if ( is_array( $_obfuscate_WKs3DA�� ) )
                    {
                        foreach ( $_obfuscate_WKs3DA�� as $_obfuscate_cO77 => $_obfuscate_9HHDStdl )
                        {
                            if ( is_array( $_obfuscate_9HHDStdl ) )
                            {
                                $_obfuscate_rX2e4Qw_AQ�� = array_merge( $_obfuscate_rX2e4Qw_AQ��, $_obfuscate_9HHDStdl );
                            }
                        }
                    }
                }
            }
        }
        $_obfuscate_BRPhVA�� = array( );
        $_obfuscate_8CJrZg�� = array( );
        foreach ( $_obfuscate_ZRB1fr3t0YM� as $_obfuscate_6A�� )
        {
            $_obfuscate_BRPhVA��[] = $_obfuscate_j9eamhY�[$_obfuscate_6A��]['id'];
        }
        foreach ( $_obfuscate_rX2e4Qw_AQ�� as $_obfuscate_6A�� )
        {
            $_obfuscate_8CJrZg��[] = $_obfuscate_j9eamhY�[$_obfuscate_6A��]['id'];
            if ( $_obfuscate_j9eamhY�[$_obfuscate_6A��]['status'] != 0 && $_obfuscate_j9eamhY�[$_obfuscate_6A��]['stepType'] != 4 )
            {
                $_obfuscate_S0PSA37yAw��[] = $_obfuscate_j9eamhY�[$_obfuscate_6A��]['uStepId'];
            }
            if ( $_obfuscate_j9eamhY�[$_obfuscate_6A��]['stepType'] == 6 )
            {
                $_obfuscate_Tz0Oow9UfYEsg��[] = $_obfuscate_j9eamhY�[$_obfuscate_6A��]['id'];
            }
            $_obfuscate_BWjlX0Jr = $CNOA_DB->db_getfield( "deal", $this->t_use_deal_way, "WHERE `stepId`=".$_obfuscate_6A��." AND `flowId`={$_obfuscate_7qDAYo85aGA�['flowId']}" );
            if ( $_obfuscate_BWjlX0Jr == 1 )
            {
                $CNOA_DB->db_delete( $this->t_use_step, "WHERE `uStepId`=".$_obfuscate_6A��." AND `uFlowId`={$_obfuscate_TlvKhtsoOQ��} " );
            }
        }
        $_obfuscate_BRPhVA�� = implode( ",", $_obfuscate_BRPhVA�� );
        $_obfuscate_8CJrZg�� = implode( ",", $_obfuscate_8CJrZg�� );
        $_obfuscate_b6uYSQ�� = implode( ",", $_obfuscate_S0PSA37yAw�� );
        $_obfuscate_Tz0Oow9UfYEsg�� = implode( ",", $_obfuscate_Tz0Oow9UfYEsg�� );
        $_obfuscate_6RYLWQ�� = array( "etime" => 0, "say" => "", "nStepId" => 0, "status" => 1 );
        if ( !empty( $_obfuscate_S0PSA37yAw�� ) )
        {
            $_obfuscate_NuFUbhnhRkM� = $_obfuscate_TeJEvUaP_x_1Tw�� = array( );
            foreach ( $_obfuscate_S0PSA37yAw�� as $_obfuscate_6A�� )
            {
                $_obfuscate_Bk2lGlk� = "WHERE `stepId`=".$_obfuscate_6A��." AND `uFlowId`={$_obfuscate_TlvKhtsoOQ��}";
                $_obfuscate_hChFFlJCLh3o = $CNOA_DB->db_select( "*", $this->t_use_fenfa, $_obfuscate_Bk2lGlk� );
                if ( !is_array( $_obfuscate_hChFFlJCLh3o ) )
                {
                    $_obfuscate_hChFFlJCLh3o = array( );
                }
                $_obfuscate_qPt6frCIa6xJ0og� = $CNOA_DB->db_select( "*", $this->t_use_step_huiqian, $_obfuscate_Bk2lGlk� );
                if ( !is_array( $_obfuscate_qPt6frCIa6xJ0og� ) )
                {
                    $_obfuscate_qPt6frCIa6xJ0og� = array( );
                }
                if ( $_obfuscate_hChFFlJCLh3o )
                {
                    foreach ( $_obfuscate_hChFFlJCLh3o as $_obfuscate_azM9k_U� )
                    {
                        $_obfuscate_NuFUbhnhRkM�[] = $_obfuscate_F2cksfdRXA�� = $_obfuscate_azM9k_U�['uFenfaId'];
                        notice::deletenotice( $_obfuscate_F2cksfdRXA��, $_obfuscate_F2cksfdRXA��, $this->t_use_fenfa, "fenfa" );
                    }
                }
                if ( $_obfuscate_qPt6frCIa6xJ0og� )
                {
                    foreach ( $_obfuscate_qPt6frCIa6xJ0og� as $_obfuscate_ouqX2cxvhA�� )
                    {
                        $_obfuscate_TeJEvUaP_x_1Tw��[] = $_obfuscate_0W8� = $_obfuscate_ouqX2cxvhA��['id'];
                        notice::deletenotice( $_obfuscate_0W8�, $_obfuscate_0W8�, $this->t_use_step_huiqian, "write" );
                    }
                }
            }
            if ( $_obfuscate_NuFUbhnhRkM� )
            {
                $_obfuscate_NuFUbhnhRkM� = implode( ",", $_obfuscate_NuFUbhnhRkM� );
                $CNOA_DB->db_delete( $this->t_use_fenfa, "WHERE `uFenfaId` IN(".$_obfuscate_NuFUbhnhRkM�.")" );
            }
            if ( $_obfuscate_TeJEvUaP_x_1Tw�� )
            {
                $_obfuscate_TeJEvUaP_x_1Tw�� = implode( ",", $_obfuscate_TeJEvUaP_x_1Tw�� );
                $CNOA_DB->db_delete( $this->t_use_step_huiqian, "WHERE `id` IN(".$_obfuscate_TeJEvUaP_x_1Tw��.")" );
            }
        }
        $CNOA_DB->db_update( $_obfuscate_6RYLWQ��, $this->t_use_step, "WHERE `id` IN (".$_obfuscate_BRPhVA��.") AND `uFlowId`={$_obfuscate_TlvKhtsoOQ��} " );
        if ( $_obfuscate_v1oL == 1 )
        {
            $CNOA_DB->db_update( $_obfuscate_6RYLWQ��, $this->t_use_step, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ��." AND `uStepId`={$_obfuscate_ecqaH_ev7A��} AND `status`=2 " );
        }
        if ( !empty( $_obfuscate_8CJrZg�� ) )
        {
            $this->clearReturnOption( $_obfuscate_S0PSA37yAw��, $_obfuscate_TlvKhtsoOQ�� );
            $CNOA_DB->db_delete( $this->t_use_step, "WHERE `id` IN(".$_obfuscate_8CJrZg��.") AND `uFlowId`={$_obfuscate_TlvKhtsoOQ��} " );
        }
        if ( !empty( $_obfuscate_Tz0Oow9UfYEsg�� ) )
        {
            $_obfuscate_3y0Y = "UPDATE ".tname( "system_notice_list" )." SET `readed`=1 ".( "WHERE `fromid` IN (".$_obfuscate_Tz0Oow9UfYEsg��.")" );
            $CNOA_DB->query( $_obfuscate_3y0Y );
            $_obfuscate_3y0Y = "UPDATE ".tname( "system_notice_history" )." SET `isread`=1 ".( "WHERE `sourceid` IN (".$_obfuscate_Tz0Oow9UfYEsg��.")" );
            $CNOA_DB->query( $_obfuscate_3y0Y );
        }
        unset( $_obfuscate_8CJrZg�� );
        unset( $_obfuscate_BRPhVA�� );
        unset( $_obfuscate_Tz0Oow9UfYEsg�� );
        $this->doneAll( "both", $_obfuscate_NenxpX50gw��['id'], "todo" );
        $this->doneAll( "notice", $_obfuscate_NenxpX50gw��['id'], "huiqian", 1 );
        if ( $_obfuscate_7qDAYo85aGA�['noticeAtGoBack'] != 4 )
        {
            $_obfuscate_6RYLWQ�� = array( );
            $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber", "flowId" ), $this->t_use_flow, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ�� );
            if ( $_obfuscate_XkuTFqZ6Tmk� == 0 )
            {
                $_obfuscate_qZkmBg�� = $_obfuscate_e53ODz04JQ��->getFlow( );
            }
            else
            {
                $_obfuscate_qZkmBg�� = $CNOA_DB->db_getone( array( "noticeAtGoBack" ), $this->t_set_flow, "WHERE `flowId`=".$_obfuscate_hTew0boWJESy['flowId'] );
            }
            $_obfuscate_rLakms3x4ur74Q�� = array( );
            foreach ( $_obfuscate_ZRB1fr3t0YM� as $_obfuscate_y6jH )
            {
                $_obfuscate_VBCv7Q�� = $_obfuscate_j9eamhY�[$_obfuscate_y6jH];
                if ( !empty( $_obfuscate_VBCv7Q��['dealUid'] ) )
                {
                    $_obfuscate_rLakms3x4ur74Q��[] = $_obfuscate_VBCv7Q��['dealUid'];
                }
                $_obfuscate_6RYLWQ�� = array( );
                $_obfuscate_6RYLWQ��['content'] = lang( "flowName" ).( "[".$_obfuscate_hTew0boWJESy['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_hTew0boWJESy['flowNumber']."]" ).lang( "beReturnNeedReApp" )."<br /> ".lang( "returnReason" ).( ":[".$_obfuscate_1l6P."]" );
                $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_VBCv7Q��['uStepId']}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
                $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_VBCv7Q��['id'];
                $this->addNotice( "both", $_obfuscate_VBCv7Q��['dealUid'], $_obfuscate_6RYLWQ��, "todo", 0 );
                $_obfuscate_v1oL = $CNOA_DB->db_getfield( "deal", $this->t_use_deal_way, "WHERE `stepId`=".$_obfuscate_y6jH." AND `flowId`={$_obfuscate_7qDAYo85aGA�['flowId']}" );
                if ( $_obfuscate_v1oL == 1 )
                {
                    $_obfuscate_CPw� = $CNOA_DB->db_select( array( "dealUid" ), $this->t_use_step, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ��." AND `uStepId`={$_obfuscate_y6jH}" );
                    if ( !is_array( $_obfuscate_CPw� ) )
                    {
                        $_obfuscate_CPw� = array( );
                    }
                    foreach ( $_obfuscate_CPw� as $_obfuscate_5g�� )
                    {
                        if ( !empty( $_obfuscate_5g��['dealUid'] ) )
                        {
                            if ( $_obfuscate_5g��['dealUid'] != $_obfuscate_VBCv7Q��['dealUid'] )
                            {
                                $this->addNotice( "both", $_obfuscate_5g��['dealUid'], $_obfuscate_6RYLWQ��, "todo", 0 );
                            }
                        }
                    }
                }
            }
            if ( $_obfuscate_qZkmBg��['noticeAtGoBack'] == 2 )
            {
                $_obfuscate_VBCv7Q�� = $_obfuscate_j9eamhY�[2];
                if ( !in_array( $_obfuscate_VBCv7Q��['dealUid'], $_obfuscate_rLakms3x4ur74Q�� ) )
                {
                    $_obfuscate_6RYLWQ��['content'] = lang( "flowName" ).( "[".$_obfuscate_hTew0boWJESy['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_hTew0boWJESy['flowNumber']."]" ).lang( "beReturn" );
                    $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step=2&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
                    $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_VBCv7Q��['id'];
                    $this->addNotice( "notice", $_obfuscate_VBCv7Q��['dealUid'], $_obfuscate_6RYLWQ��, "tuihui", 2 );
                }
            }
            else if ( $_obfuscate_qZkmBg��['noticeAtGoBack'] == 3 )
            {
                $_obfuscate_6RYLWQ��['content'] = lang( "flowName" ).( "[".$_obfuscate_hTew0boWJESy['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_hTew0boWJESy['flowNumber']."]" ).lang( "beReturn" );
                $_obfuscate_UcZOQ_tY828b1t7T8A�� = array( );
                foreach ( $_obfuscate_hsTQkq6NOXRA as $_obfuscate_VgKtFeg� )
                {
                    if ( in_array( $_obfuscate_VgKtFeg�['dealUid'], $_obfuscate_rLakms3x4ur74Q�� ) || !empty( $_obfuscate_UcZOQ_tY828b1t7T8A��[$_obfuscate_VgKtFeg�['dealUid']] ) )
                    {
                        $_obfuscate_UcZOQ_tY828b1t7T8A��[$_obfuscate_VgKtFeg�['dealUid']] = $_obfuscate_VgKtFeg�;
                    }
                }
                foreach ( $_obfuscate_UcZOQ_tY828b1t7T8A�� as $_obfuscate_VBCv7Q�� )
                {
                    $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_VBCv7Q��['uStepId']}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
                    $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_VBCv7Q��['id'];
                    $this->addNotice( "notice", $_obfuscate_VBCv7Q��['dealUid'], $_obfuscate_6RYLWQ��, "tuihui", 1 );
                }
            }
        }
        $_obfuscate_JG8GuY� = array( );
        $_obfuscate_JG8GuY�['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
        $_obfuscate_JG8GuY�['type'] = 4;
        $_obfuscate_JG8GuY�['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuY�['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuY�['say'] = $_obfuscate_1l6P;
        foreach ( $_obfuscate_ZRB1fr3t0YM� as $_obfuscate_y6jH )
        {
            $_obfuscate_VBCv7Q�� = $_obfuscate_j9eamhY�[$_obfuscate_y6jH];
            $_obfuscate_JG8GuY�['step'] = $_obfuscate_y6jH;
            $_obfuscate_JG8GuY�['stepname'] = $_obfuscate_VBCv7Q��['stepname'];
            $this->insertEvent( $_obfuscate_JG8GuY� );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3604, lang( "backToFlowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _ms_loadTemplateFile( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", 0 );
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_GET, "tplSort", 0 );
        $_obfuscate_o6LA2yPirJIreFA� = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQ��}/doc.history.0.php" );
        $_obfuscate_U9NJHZRq6Jr7T_A� = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQ��}/xls.history.0.php" );
        if ( $_obfuscate_pEvU7Kz2Yw�� == 1 || $_obfuscate_pEvU7Kz2Yw�� == 3 )
        {
            if ( file_exists( $_obfuscate_o6LA2yPirJIreFA� ) )
            {
                $_obfuscate_6hS1Rw�� = @file_get_contents( $_obfuscate_o6LA2yPirJIreFA� );
            }
            else
            {
                mkdirs( dirname( $_obfuscate_o6LA2yPirJIreFA� ) );
                $_obfuscate_6hS1Rw�� = " ";
            }
        }
        else if ( file_exists( $_obfuscate_U9NJHZRq6Jr7T_A� ) )
        {
            $_obfuscate_6hS1Rw�� = @file_get_contents( $_obfuscate_U9NJHZRq6Jr7T_A� );
        }
        else
        {
            mkdirs( dirname( $_obfuscate_U9NJHZRq6Jr7T_A� ) );
            $_obfuscate_6hS1Rw�� = " ";
        }
        echo $_obfuscate_6hS1Rw��;
        exit( );
    }

    private function _ms_submitMsOfficeData( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", 0 );
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_GET, "tplSort", 0 );
        $_obfuscate_zfubNC9lKJs� = strtolower( strrchr( $_FILES['msOffice']['name'], "." ) );
        if ( !in_array( strtolower( $_obfuscate_zfubNC9lKJs� ), array( ".doc", ".docx", ".xls", ".xlsx" ) ) )
        {
            echo "0";
            exit( );
        }
        if ( $_obfuscate_pEvU7Kz2Yw�� == "1" || $_obfuscate_pEvU7Kz2Yw�� == "3" )
        {
            $_obfuscate_7tmDAr7acvgDxw�� = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQ��}/doc.history.0.php" );
            mkdirs( dirname( $_obfuscate_7tmDAr7acvgDxw�� ) );
            if ( !cnoa_move_uploaded_file( $_FILES['msOffice']['tmp_name'], $_obfuscate_7tmDAr7acvgDxw�� ) )
            {
                echo "0";
                exit( );
            }
        }
        else
        {
            $_obfuscate_7tmDAr7acvgDxw�� = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQ��}/xls.history.0.php" );
            mkdirs( dirname( $_obfuscate_7tmDAr7acvgDxw�� ) );
            if ( !cnoa_move_uploaded_file( $_FILES['msOffice']['tmp_name'], $_obfuscate_7tmDAr7acvgDxw�� ) )
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
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_LeS8hw�� = getpar( $_POST, "type", 0 );
        $_obfuscate_1l6P = getpar( $_POST, "say", "" );
        $_obfuscate_5RySNZO3T0k� = getpar( $_POST, "htmlFormContent", "", 1, 0 );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_POST, "tplSort", 0 );
        $thisStep = $CNOA_DB->db_getone( array( "id", "stepname" ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId`='{$_obfuscate_0Ul8BBkt}'" );
        $this->doneAll( "both", $thisStep['id'], "todo" );
        $this->doneAll( "notice", $thisStep['id'], "huiqian", 1 );
        $_obfuscate_EyPNUV5TiETe = $CNOA_DB->db_select( array( "id" ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' " );
        if ( !is_array( $_obfuscate_EyPNUV5TiETe ) )
        {
            $_obfuscate_EyPNUV5TiETe = array( );
        }
        foreach ( $_obfuscate_EyPNUV5TiETe as $_obfuscate_6A�� )
        {
            $this->doneAll( "both", $_obfuscate_6A��['id'], "huiqian" );
        }
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $_obfuscate_UTaQ0tZbOZ2slLemr40_Q�� = 0;
        $_obfuscate_IuoXR2yOaxkRDw�� = array( );
        foreach ( $GLOBALS['_POST'] as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( ereg( "wf_attach_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_IuoXR2yOaxkRDw��[] = intval( str_replace( "wf_attach_", "", $_obfuscate_5w�� ) );
            }
        }
        ( );
        $_obfuscate_2gg� = new fs( );
        $_obfuscate_vCKayYE4IxP3uvQw0A�� = json_decode( $_obfuscate_hTew0boWJESy['attach'], TRUE );
        if ( !is_array( $_obfuscate_vCKayYE4IxP3uvQw0A�� ) )
        {
            $_obfuscate_vCKayYE4IxP3uvQw0A�� = array( );
        }
        $_obfuscate_1ERfSWbp = $_obfuscate_2gg�->uploadFile4Wf( $_obfuscate_IuoXR2yOaxkRDw��, $_obfuscate_vCKayYE4IxP3uvQw0A�� );
        $_obfuscate_8CpDPPa = json_encode( $_obfuscate_1ERfSWbp[0] );
        $_obfuscate_hYX302lGYvY5 = $CNOA_DB->db_getone( array( "id", "nStepId", "uid" ), $this->t_use_step, ( "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId`='".( $_obfuscate_0Ul8BBkt + 1 ) )."'" );
        if ( $_obfuscate_hYX302lGYvY5['nStepId'] == 0 )
        {
            $_obfuscate_L9PX7r5kCPQ� = "done";
            $_obfuscate_VBCv7Q�� = array( );
            $_obfuscate_VBCv7Q��['status'] = 2;
            $_obfuscate_VBCv7Q��['etime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $_obfuscate_VBCv7Q��['say'] = $_obfuscate_1l6P;
            $_obfuscate_VBCv7Q��['dealUid'] = $_obfuscate_7Ri3;
            $_obfuscate_VBCv7Q��['stepType'] = 2;
            $CNOA_DB->db_update( $_obfuscate_VBCv7Q��, $this->t_use_step, "WHERE uFlowId=".$_obfuscate_TlvKhtsoOQ��." AND `uStepId`='{$_obfuscate_0Ul8BBkt}'" );
            $_obfuscate_kVqhf0IeMg�� = array( );
            $_obfuscate_kVqhf0IeMg��['status'] = 2;
            $_obfuscate_kVqhf0IeMg��['stepType'] = 3;
            $CNOA_DB->db_update( $_obfuscate_kVqhf0IeMg��, $this->t_use_step, ( "WHERE uFlowId=".$_obfuscate_TlvKhtsoOQ��." AND `uStepId`='".( $_obfuscate_0Ul8BBkt + 1 ) )."'" );
            $_obfuscate_JG8GuY� = array( );
            $_obfuscate_JG8GuY�['type'] = 2;
            $_obfuscate_JG8GuY�['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
            $_obfuscate_JG8GuY�['step'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_JG8GuY�['stepname'] = "结束";
            $_obfuscate_JG8GuY�['say'] = $_obfuscate_1l6P;
            $_obfuscate_JG8GuY�['uid'] = $_obfuscate_7Ri3;
            $_obfuscate_JG8GuY�['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $this->insertEvent( $_obfuscate_JG8GuY� );
            $_obfuscate_qZkmBg�� = array( );
            $_obfuscate_qZkmBg��['status'] = 2;
            $_obfuscate_qZkmBg��['attach'] = $_obfuscate_8CpDPPa;
            $_obfuscate_qZkmBg��['htmlFormContent'] = $_obfuscate_5RySNZO3T0k�;
            $_obfuscate_7qDAYo85aGA� = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_hTew0boWJESy['flowId']."'" );
            if ( $_obfuscate_7qDAYo85aGA�['noticeAtFinish'] == 1 )
            {
                $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "flowOver" );
                $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
                $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_hYX302lGYvY5['id'];
                $this->addNotice( "notice", $_obfuscate_hTew0boWJESy['uid'], $_obfuscate_6RYLWQ��, "done" );
            }
            else if ( $_obfuscate_7qDAYo85aGA�['noticeAtFinish'] == 2 )
            {
                $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "flowOver" );
                $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
                $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_hYX302lGYvY5['id'];
                $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "dealUid" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
                if ( !is_array( $_obfuscate_Tx7M9W ) )
                {
                    $_obfuscate_Tx7M9W = array( );
                }
                foreach ( $_obfuscate_Tx7M9W as $_obfuscate_6A�� )
                {
                    $this->addNotice( "notice", $_obfuscate_6A��['dealUid'], $_obfuscate_6RYLWQ��, "done" );
                }
            }
        }
        else
        {
            $_obfuscate_L9PX7r5kCPQ� = "todo";
            $_obfuscate_UTaQ0tZbOZ2slLemr40_Q�� = $this->getProxyUid( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt + 1 );
            $_obfuscate_RNvHv6KVxd_drYca6gd = array( );
            if ( $_obfuscate_LeS8hw�� == "agree" )
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
            $CNOA_DB->db_update( $_obfuscate_RNvHv6KVxd_drYca6gd, $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId`='{$_obfuscate_0Ul8BBkt}'" );
            $_obfuscate_NeEP0UTNX62DrQn = array( );
            $_obfuscate_NeEP0UTNX62DrQn['status'] = 1;
            $_obfuscate_NeEP0UTNX62DrQn['stime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $_obfuscate_NeEP0UTNX62DrQn['stepType'] = 2;
            $CNOA_DB->db_update( $_obfuscate_NeEP0UTNX62DrQn, $this->t_use_step, ( "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId`='".( $_obfuscate_0Ul8BBkt + 1 ) )."'" );
            $_obfuscate_JG8GuY� = array( );
            if ( $_obfuscate_LeS8hw�� == "agree" )
            {
                $_obfuscate_JG8GuY�['type'] = 2;
            }
            else
            {
                $_obfuscate_JG8GuY�['type'] = 9;
            }
            $_obfuscate_JG8GuY�['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
            $_obfuscate_JG8GuY�['step'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_JG8GuY�['stepname'] = $thisStep['stepname'];
            $_obfuscate_JG8GuY�['uid'] = $_obfuscate_7Ri3;
            $_obfuscate_JG8GuY�['say'] = $_obfuscate_1l6P;
            $_obfuscate_JG8GuY�['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $this->insertEvent( $_obfuscate_JG8GuY� );
            $_obfuscate_qZkmBg�� = array( );
            $_obfuscate_qZkmBg��['htmlFormContent'] = $_obfuscate_5RySNZO3T0k�;
            $_obfuscate_qZkmBg��['status'] = 1;
            $_obfuscate_qZkmBg��['attach'] = $_obfuscate_8CpDPPa;
            $_obfuscate_fgY2Vw2IN54w9w��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]编号[".$_obfuscate_hTew0boWJESy['flowNumber']."]需要您审批";
            $_obfuscate_fgY2Vw2IN54w9w��['href'] = ( "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_F4AbnVRh}&step=".( $_obfuscate_0Ul8BBkt + 1 ) ).( "&flowType=".$_obfuscate_XkuTFqZ6Tmk�."&tplSort={$_obfuscate_pEvU7Kz2Yw��}" );
            $_obfuscate_fgY2Vw2IN54w9w��['fromid'] = $_obfuscate_hYX302lGYvY5['id'];
            $this->addNotice( "both", array(
                $_obfuscate_hYX302lGYvY5['uid'],
                $_obfuscate_UTaQ0tZbOZ2slLemr40_Q��
            ), $_obfuscate_fgY2Vw2IN54w9w��, "todo" );
        }
        $CNOA_DB->db_update( $_obfuscate_qZkmBg��, $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = array(
            "stepType" => $_obfuscate_L9PX7r5kCPQ�
        );
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function _freeFlowSendNextStep( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_GET, "step", 0 );
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_LeS8hw�� = getpar( $_POST, "type", "agree" );
        $_obfuscate_Xthk75oEMy4� = getpar( $_POST, "stepname", "" );
        $_obfuscate_PodmRNAQbw�� = getpar( $_POST, "dealuid", 0 );
        $_obfuscate_1l6P = getpar( $_POST, "say", "" );
        $_obfuscate_5RySNZO3T0k� = getpar( $_POST, "htmlFormContent", "", 1, 0 );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_a9lP = getpar( $_POST, "sms", "false" );
        $_obfuscate_a9lP = $_obfuscate_a9lP == "false" ? FALSE : TRUE;
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $thisStep = $CNOA_DB->db_getone( array( "id" ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId`='{$_obfuscate_0Ul8BBkt}'" );
        $this->doneAll( "both", $thisStep['id'], "todo" );
        $this->doneAll( "notice", $thisStep['id'], "huiqian", 1 );
        $_obfuscate_EyPNUV5TiETe = $CNOA_DB->db_select( array( "id" ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' " );
        if ( !is_array( $_obfuscate_EyPNUV5TiETe ) )
        {
            $_obfuscate_EyPNUV5TiETe = array( );
        }
        foreach ( $_obfuscate_EyPNUV5TiETe as $_obfuscate_6A�� )
        {
            $this->doneAll( "both", $_obfuscate_6A��['id'], "huiqian" );
        }
        $_obfuscate_UTaQ0tZbOZ2slLemr40_Q�� = 0;
        $_obfuscate_UTaQ0tZbOZ2slLemr40_Q�� = $this->getProxyUid( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt + 1 );
        $_obfuscate_IuoXR2yOaxkRDw�� = array( );
        foreach ( $GLOBALS['_POST'] as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( ereg( "wf_attach_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_IuoXR2yOaxkRDw��[] = intval( str_replace( "wf_attach_", "", $_obfuscate_5w�� ) );
            }
        }
        ( );
        $_obfuscate_2gg� = new fs( );
        $_obfuscate_vCKayYE4IxP3uvQw0A�� = json_decode( $_obfuscate_hTew0boWJESy['attach'], TRUE );
        if ( !is_array( $_obfuscate_vCKayYE4IxP3uvQw0A�� ) )
        {
            $_obfuscate_vCKayYE4IxP3uvQw0A�� = array( );
        }
        $_obfuscate_1ERfSWbp = $_obfuscate_2gg�->uploadFile4Wf( $_obfuscate_IuoXR2yOaxkRDw��, $_obfuscate_vCKayYE4IxP3uvQw0A�� );
        $_obfuscate_8CpDPPa = json_encode( $_obfuscate_1ERfSWbp[0] );
        $_obfuscate_OOSxy1QQ55_QiM� = array( );
        if ( $_obfuscate_LeS8hw�� == "agree" )
        {
            $_obfuscate_OOSxy1QQ55_QiM�['status'] = 2;
        }
        else
        {
            $_obfuscate_OOSxy1QQ55_QiM�['status'] = 4;
        }
        $_obfuscate_OOSxy1QQ55_QiM�['etime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_OOSxy1QQ55_QiM�['say'] = $_obfuscate_1l6P;
        $_obfuscate_OOSxy1QQ55_QiM�['dealUid'] = $_obfuscate_7Ri3;
        $_obfuscate_OOSxy1QQ55_QiM�['stepType'] = 2;
        $CNOA_DB->db_update( $_obfuscate_OOSxy1QQ55_QiM�, $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId`='{$_obfuscate_0Ul8BBkt}'" );
        $_obfuscate_MnVVbyZQFVw� = array( );
        $_obfuscate_MnVVbyZQFVw�['uStepId'] = $_obfuscate_0Ul8BBkt + 1;
        $_obfuscate_MnVVbyZQFVw�['pStepId'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_MnVVbyZQFVw�['nStepId'] = $_obfuscate_0Ul8BBkt + 2;
        $_obfuscate_MnVVbyZQFVw�['stepname'] = $_obfuscate_Xthk75oEMy4�;
        $_obfuscate_MnVVbyZQFVw�['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
        $_obfuscate_MnVVbyZQFVw�['uid'] = $_obfuscate_PodmRNAQbw��;
        $_obfuscate_MnVVbyZQFVw�['status'] = 1;
        $_obfuscate_MnVVbyZQFVw�['stime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_MnVVbyZQFVw�['stepType'] = 2;
        $_obfuscate_6l4kzHfz = $CNOA_DB->db_insert( $_obfuscate_MnVVbyZQFVw�, $this->t_use_step );
        $_obfuscate_JG8GuY� = array( );
        $_obfuscate_JG8GuY�['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
        $_obfuscate_JG8GuY�['step'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_JG8GuY�['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuY�['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuY�['say'] = $_obfuscate_1l6P;
        $_obfuscate_JG8GuY�['stepname'] = $_obfuscate_Xthk75oEMy4�;
        if ( $_obfuscate_LeS8hw�� == "agree" )
        {
            $_obfuscate_JG8GuY�['type'] = 2;
        }
        else
        {
            $_obfuscate_JG8GuY�['type'] = 9;
        }
        $this->insertEvent( $_obfuscate_JG8GuY� );
        $_obfuscate_qZkmBg�� = array( );
        $_obfuscate_qZkmBg��['htmlFormContent'] = $_obfuscate_5RySNZO3T0k�;
        $_obfuscate_qZkmBg��['status'] = 1;
        $_obfuscate_qZkmBg��['attach'] = $_obfuscate_8CpDPPa;
        $CNOA_DB->db_update( $_obfuscate_qZkmBg��, $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $_obfuscate_fgY2Vw2IN54w9w�� = array( );
        $_obfuscate_fgY2Vw2IN54w9w��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]编号[".$_obfuscate_hTew0boWJESy['flowNumber']."]需要您审批";
        $_obfuscate_fgY2Vw2IN54w9w��['href'] = ( "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_F4AbnVRh}&step=".( $_obfuscate_0Ul8BBkt + 1 ) ).( "&flowType=".$_obfuscate_XkuTFqZ6Tmk�."&tplSort={$_obfuscate_pEvU7Kz2Yw��}" );
        $_obfuscate_fgY2Vw2IN54w9w��['fromid'] = $_obfuscate_6l4kzHfz;
        $this->addNotice( "both", array(
            $_obfuscate_PodmRNAQbw��,
            $_obfuscate_UTaQ0tZbOZ2slLemr40_Q��
        ), $_obfuscate_fgY2Vw2IN54w9w��, "todo" );
        if ( $_obfuscate_a9lP )
        {
            ( );
            $_obfuscate_a9lP = new sms( );
            $_obfuscate_a9lP->sendByUids( array(
                $_obfuscate_PodmRNAQbw��,
                $_obfuscate_UTaQ0tZbOZ2slLemr40_Q��
            ), $_obfuscate_fgY2Vw2IN54w9w��['content'], 0, "flow", 0 );
        }
        $this->doneAll( "both", $thisStep, "todo" );
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = array(
            "uFlowId" => $_obfuscate_TlvKhtsoOQ��
        );
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function _finishFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_GET, "stepId", 0 );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_1l6P = getpar( $_POST, "say", "" );
        $_obfuscate_5RySNZO3T0k� = getpar( $_POST, "htmlFormContent", "", 1, 0 );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_POST, "tplSort", 0 );
        $thisStep = $CNOA_DB->db_getone( array( "id" ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId`='{$_obfuscate_0Ul8BBkt}'" );
        $this->doneAll( "both", $thisStep['id'], "todo" );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $_obfuscate_IuoXR2yOaxkRDw�� = array( );
        foreach ( $GLOBALS['_POST'] as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( ereg( "wf_attach_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_IuoXR2yOaxkRDw��[] = intval( str_replace( "wf_attach_", "", $_obfuscate_5w�� ) );
            }
        }
        ( );
        $_obfuscate_2gg� = new fs( );
        $_obfuscate_vCKayYE4IxP3uvQw0A�� = json_decode( $_obfuscate_hTew0boWJESy['attach'], TRUE );
        if ( !is_array( $_obfuscate_vCKayYE4IxP3uvQw0A�� ) )
        {
            $_obfuscate_vCKayYE4IxP3uvQw0A�� = array( );
        }
        $_obfuscate_1ERfSWbp = $_obfuscate_2gg�->uploadFile4Wf( $_obfuscate_IuoXR2yOaxkRDw��, $_obfuscate_vCKayYE4IxP3uvQw0A�� );
        $_obfuscate_8CpDPPa = json_encode( $_obfuscate_1ERfSWbp[0] );
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_6RYLWQ��['dealUid'] = $_obfuscate_7Ri3;
        $_obfuscate_6RYLWQ��['status'] = 2;
        $_obfuscate_6RYLWQ��['etime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_6RYLWQ��['say'] = $_obfuscate_1l6P;
        $_obfuscate_6RYLWQ��['stepType'] = 2;
        $CNOA_DB->db_update( $_obfuscate_6RYLWQ��, $this->t_use_step, "WHERE `uStepId`='".$_obfuscate_0Ul8BBkt."' AND `uFlowId`='{$_obfuscate_TlvKhtsoOQ��}'" );
        $_obfuscate_kVqhf0IeMg�� = array( );
        $_obfuscate_kVqhf0IeMg��['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
        $_obfuscate_kVqhf0IeMg��['uStepId'] = $_obfuscate_0Ul8BBkt + 1;
        $_obfuscate_kVqhf0IeMg��['status'] = 2;
        $_obfuscate_kVqhf0IeMg��['pStepId'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_kVqhf0IeMg��['nStepId'] = 0;
        $_obfuscate_kVqhf0IeMg��['uid'] = 0;
        $_obfuscate_kVqhf0IeMg��['stepname'] = "结束";
        $_obfuscate_6RYLWQ��['stepType'] = 3;
        $CNOA_DB->db_insert( $_obfuscate_kVqhf0IeMg��, $this->t_use_step );
        $_obfuscate_JG8GuY� = array( );
        $_obfuscate_JG8GuY�['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
        $_obfuscate_JG8GuY�['type'] = 2;
        $_obfuscate_JG8GuY�['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuY�['stepname'] = "结束";
        $_obfuscate_JG8GuY�['step'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_JG8GuY�['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuY�['say'] = $_obfuscate_1l6P;
        $CNOA_DB->db_insert( $_obfuscate_JG8GuY�, $this->t_use_event );
        $_obfuscate_qZkmBg�� = array( );
        $_obfuscate_qZkmBg��['status'] = 2;
        $_obfuscate_qZkmBg��['htmlFormContent'] = $_obfuscate_5RySNZO3T0k�;
        $_obfuscate_qZkmBg��['attach'] = $_obfuscate_8CpDPPa;
        $CNOA_DB->db_update( $_obfuscate_qZkmBg��, $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $_obfuscate_7qDAYo85aGA� = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_hTew0boWJESy['flowId']."'" );
        if ( $_obfuscate_7qDAYo85aGA�['noticeAtFinish'] == 1 )
        {
            $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "flowOver" );
            $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_7qDAYo85aGA�['flowId']}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
            $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_0Ul8BBkt;
            $this->addNotice( "notice", $_obfuscate_hTew0boWJESy['uid'], $_obfuscate_6RYLWQ��, "done" );
        }
        else if ( $_obfuscate_7qDAYo85aGA�['noticeAtFinish'] == 2 )
        {
            $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "flowOver" );
            $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_7qDAYo85aGA�['flowId']}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
            $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "dealUid" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
            if ( !is_array( $_obfuscate_Tx7M9W ) )
            {
                $_obfuscate_Tx7M9W = array( );
            }
            foreach ( $_obfuscate_Tx7M9W as $_obfuscate_6A�� )
            {
                $this->addNotice( "notice", $_obfuscate_6A��['dealUid'], $_obfuscate_6RYLWQ��, "done" );
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getChildFlowJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_GET, "stepId", 0 );
        $_obfuscate_fKTc3pvqkNs� = $CNOA_DB->db_getfield( "uid", $this->t_use_step, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ��." AND `uStepId`={$_obfuscate_0Ul8BBkt} AND `proxyUid`={$_obfuscate_7Ri3}" );
        if ( empty( $_obfuscate_fKTc3pvqkNs� ) )
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_step_child_flow, "WHERE `puFlowId` = ".$_obfuscate_TlvKhtsoOQ��." AND `stepId` = {$_obfuscate_0Ul8BBkt} AND `postuid` = {$_obfuscate_7Ri3} " );
        }
        else
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_step_child_flow, "WHERE `puFlowId`=".$_obfuscate_TlvKhtsoOQ��." AND `stepId`={$_obfuscate_0Ul8BBkt} AND `postuid`={$_obfuscate_fKTc3pvqkNs�}" );
        }
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_l2CIvUX0Kvp4 = array( 0 );
        $_obfuscate_81hHUJ9pJKTIlQ�� = array( 0 );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_l2CIvUX0Kvp4[] = $_obfuscate_6A��['flowId'];
            $_obfuscate_81hHUJ9pJKTIlQ��[] = $_obfuscate_6A��['faqiUid'];
            if ( empty( $_obfuscate_6A��['status'] ) )
            {
                $_obfuscate_vzHuN2c = $this->__formatChildFlowUserJsonData( $_obfuscate_6A��['flowId'] );
                $_obfuscate_Ybai = count( $_obfuscate_vzHuN2c );
                if ( empty( $_obfuscate_Ybai ) )
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['operate'] = 1;
                }
                else if ( $_obfuscate_Ybai == 1 )
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['operate'] = 2;
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['operatename'] = $_obfuscate_vzHuN2c[0]['truename'];
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['operateuid'] = $_obfuscate_vzHuN2c[0]['uid'];
                }
                else
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['operate'] = 0;
                }
            }
            else if ( $_obfuscate_6A��['status'] == 2 )
            {
                $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "stepType", "stepname", "uid", "proxyUid", "dealUid" ), $this->t_use_step, "WHERE `uFlowId` = ".$_obfuscate_6A��['uFlowId']." AND `status` = 1 " );
                if ( !is_array( $_obfuscate_Tx7M9W ) )
                {
                    $_obfuscate_Tx7M9W = array( );
                }
                foreach ( $_obfuscate_Tx7M9W as $_obfuscate_ty0� => $_obfuscate_snM� )
                {
                    $_obfuscate_QwT4KwrB2w��[$_obfuscate_6A��['id']][] = array(
                        "stepname" => $_obfuscate_snM�['stepname'],
                        "stepType" => $_obfuscate_snM�['stepType'],
                        "uid" => $_obfuscate_snM�['uid'],
                        "proxyUid" => $_obfuscate_snM�['proxyUid']
                    );
                    $_obfuscate_81hHUJ9pJKTIlQ��[] = $_obfuscate_snM�['uid'];
                    $_obfuscate_81hHUJ9pJKTIlQ��[] = $_obfuscate_snM�['proxyUid'];
                    $_obfuscate_81hHUJ9pJKTIlQ��[] = $_obfuscate_snM�['dealUid'];
                }
            }
        }
        $_obfuscate_GCfDSanL49WUEA�� = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_81hHUJ9pJKTIlQ�� );
        $_obfuscate_SIUSR4F6 = $CNOA_DB->db_select( array( "name", "flowId" ), $this->t_set_flow, "WHERE `flowId` IN (".implode( ",", $_obfuscate_l2CIvUX0Kvp4 ).") " );
        if ( !is_array( $_obfuscate_SIUSR4F6 ) )
        {
            $_obfuscate_SIUSR4F6 = array( );
        }
        foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_R7khXq3cT4VZ[$_obfuscate_6A��['flowId']] = $_obfuscate_6A��['name'];
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['name'] = $_obfuscate_R7khXq3cT4VZ[$_obfuscate_6A��['flowId']];
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['faqiname'] = $_obfuscate_GCfDSanL49WUEA��[$_obfuscate_6A��['faqiUid']]['truename'];
            $_obfuscate_Ybai = count( $_obfuscate_QwT4KwrB2w��[$_obfuscate_6A��['id']] );
            if ( $_obfuscate_Ybai == 1 )
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['stepname'] = $_obfuscate_QwT4KwrB2w��[$_obfuscate_6A��['id']][0]['stepname'];
                $_obfuscate_VZzSMXQx6Q�� = $_obfuscate_QwT4KwrB2w��[$_obfuscate_6A��['id']][0]['uid'];
                if ( empty( $_obfuscate_VZzSMXQx6Q�� ) )
                {
                    $_obfuscate_VZzSMXQx6Q�� = $_obfuscate_QwT4KwrB2w��[$_obfuscate_6A��['id']][0]['proxyUid'];
                }
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['stepuid'] = $_obfuscate_GCfDSanL49WUEA��[$_obfuscate_VZzSMXQx6Q��]['truename'];
            }
            else if ( 1 < $_obfuscate_Ybai )
            {
                foreach ( $_obfuscate_QwT4KwrB2w��[$_obfuscate_6A��['id']] as $_obfuscate_ty0� => $_obfuscate_snM� )
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['stepname'] .= $_obfuscate_snM�['stepname'];
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['stepname'] .= "<br />";
                    $_obfuscate_VZzSMXQx6Q�� = $_obfuscate_snM�['uid'];
                    if ( empty( $_obfuscate_VZzSMXQx6Q�� ) )
                    {
                        $_obfuscate_VZzSMXQx6Q�� = $_obfuscate_6A��['proxyUid'];
                    }
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['stepuid'] .= $_obfuscate_GCfDSanL49WUEA��[$_obfuscate_VZzSMXQx6Q��]['truename'];
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['stepuid'] .= "<br />";
                }
            }
        }
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function _childFaqi( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate__e3924lsxQ�� = $CNOA_SESSION->get( "UID" );
        $_obfuscate_0W8� = getpar( $_POST, "id", 0 );
        $_obfuscate_tC8MNsAzXA�� = getpar( $_POST, "uid", $_obfuscate__e3924lsxQ�� );
        $_obfuscate_Ia59JpJlk_Wd = $CNOA_DB->db_getone( array( "puFlowId", "flowId", "faqiFlow" ), $this->t_use_step_child_flow, "WHERE `id` = ".$_obfuscate_0W8� );
        if ( $_obfuscate_Ia59JpJlk_Wd['faqiFlow'] == "myself" )
        {
            $_obfuscate_tC8MNsAzXA�� = $_obfuscate__e3924lsxQ��;
        }
        $CNOA_DB->db_update( array(
            "status" => 1,
            "faqiUid" => $_obfuscate_tC8MNsAzXA��
        ), $this->t_use_step_child_flow, "WHERE `id` = ".$_obfuscate_0W8� );
        $_obfuscate_qZkmBg�� = $CNOA_DB->db_getone( array( "flowNumber", "flowName" ), $this->t_use_flow, "WHERE `uFlowId` = ".$_obfuscate_Ia59JpJlk_Wd['puFlowId']." " );
        $_obfuscate_iDvjm2M� = $CNOA_DB->db_getone( array( "nameRule", "nameRuleId", "tplSort", "flowType" ), $this->t_set_flow, "WHERE `flowId` = ".$_obfuscate_Ia59JpJlk_Wd['flowId']." " );
        $_obfuscate_TMoT['content'] = lang( "flowName" )."[".$_obfuscate_qZkmBg��['flowName']."]".lang( "bianHao" )."[".$_obfuscate_qZkmBg��['flowNumber']."]".lang( "stepDealPerople" )."[".$CNOA_SESSION->get( "TRUENAME" )."]".lang( "buZhiSubflowReInit" );
        $_obfuscate_TMoT['href'] = "&flowId=".$_obfuscate_Ia59JpJlk_Wd['flowId']."&nameRuleId=".$_obfuscate_iDvjm2M�['nameRuleId']."&flowType=".$_obfuscate_iDvjm2M�['flowType']."&tplSort=".$_obfuscate_iDvjm2M�['tplSort']."&childId=".$_obfuscate_0W8�;
        $_obfuscate_TMoT['fromid'] = $_obfuscate_0W8�;
        $this->addNotice( "both", $_obfuscate_tC8MNsAzXA��, $_obfuscate_TMoT, "child" );
        $_obfuscate_JG8GuY� = array( );
        $_obfuscate_JG8GuY�['type'] = 12;
        $_obfuscate_JG8GuY�['uFlowId'] = $_obfuscate_Ia59JpJlk_Wd['puFlowId'];
        $_obfuscate_JG8GuY�['step'] = $_obfuscate_Ia59JpJlk_Wd['stepId'];
        $_obfuscate_JG8GuY�['stepname'] = "子流程布置";
        $_obfuscate_JG8GuY�['say'] = "";
        $_obfuscate_JG8GuY�['uid'] = $_obfuscate__e3924lsxQ��;
        $_obfuscate_JG8GuY�['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $this->insertEvent( $_obfuscate_JG8GuY� );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getChildFlowUserJsonData( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_POST, "cflowId", 0 );
        $_obfuscate_phKp89pDgwQ� = $this->__formatChildFlowUserJsonData( $_obfuscate_F4AbnVRh );
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_phKp89pDgwQ�;
        echo $_obfuscate_NlQ�->makeJsonData( );
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
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['type'] == "people" )
            {
                $_obfuscate_PVLK5jra[] = $_obfuscate_6A��['people'];
            }
            else if ( $_obfuscate_6A��['type'] == "station" )
            {
                $_obfuscate_MtpzvDgUD7YblQ��[] = $_obfuscate_6A��['station'];
            }
            else if ( $_obfuscate_6A��['type'] == "dept" )
            {
                $_obfuscate_Lw9wXKzqBg��[] = $_obfuscate_6A��['dept'];
            }
            else if ( $_obfuscate_6A��['type'] == "deptstation" )
            {
                $_obfuscate_Qoa1pKbG2zuGkblGUZmZPw��[] = $_obfuscate_6A��['dept'];
                $_obfuscate_UsUCkyqS6df49QRFnhyG9A��[] = $_obfuscate_6A��['station'];
            }
            else
            {
                $_obfuscate_phKp89pDgwQ� = app::loadapp( "main", "user" )->api_getAllUserInfo( );
            }
        }
        $_obfuscate_SeV31Q�� = array( );
        if ( isset( $_obfuscate_PVLK5jra ) )
        {
            $_obfuscate_qSP4Gc� = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_PVLK5jra );
            foreach ( $_obfuscate_qSP4Gc� as $_obfuscate_6A�� )
            {
                $_obfuscate_SeV31Q��[$_obfuscate_6A��['uid']] = $_obfuscate_6A��['truename'];
            }
        }
        if ( isset( $_obfuscate_MtpzvDgUD7YblQ�� ) )
        {
            $_obfuscate_uLf44wk1NRqS = app::loadapp( "main", "user" )->api_getUserNamesByStationIds( $_obfuscate_MtpzvDgUD7YblQ�� );
            foreach ( $_obfuscate_uLf44wk1NRqS as $_obfuscate_6A�� )
            {
                $_obfuscate_SeV31Q��[$_obfuscate_6A��['uid']] = $_obfuscate_6A��['truename'];
            }
        }
        if ( isset( $_obfuscate_Lw9wXKzqBg�� ) )
        {
            $_obfuscate_Lw9wXKzqBg�� = app::loadapp( "main", "user" )->api_getUserByFids( $_obfuscate_Lw9wXKzqBg�� );
            foreach ( $_obfuscate_Lw9wXKzqBg�� as $_obfuscate_6A�� )
            {
                $_obfuscate_SeV31Q��[$_obfuscate_6A��['uid']] = $_obfuscate_6A��['truename'];
            }
        }
        if ( isset( $_obfuscate_Qoa1pKbG2zuGkblGUZmZPw�� ) )
        {
            $_obfuscate_Bk2lGlk� = " AND `stationid` IN (".implode( ",", $_obfuscate_UsUCkyqS6df49QRFnhyG9A�� ).")";
            $_obfuscate_Lw9wXKzqBg�� = app::loadapp( "main", "user" )->api_getUserByFids( $_obfuscate_Qoa1pKbG2zuGkblGUZmZPw��, $_obfuscate_Bk2lGlk� );
            foreach ( $_obfuscate_Lw9wXKzqBg�� as $_obfuscate_6A�� )
            {
                $_obfuscate_SeV31Q��[$_obfuscate_6A��['uid']] = $_obfuscate_6A��['truename'];
            }
        }
        foreach ( $_obfuscate_SeV31Q�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_phKp89pDgwQ�[] = array(
                "uid" => $_obfuscate_5w��,
                "truename" => $_obfuscate_6A��
            );
        }
        $_obfuscate_xs33Yt_k = array( );
        foreach ( $_obfuscate_phKp89pDgwQ� as $_obfuscate_6A�� )
        {
            $_obfuscate_xs33Yt_k[] = $_obfuscate_6A��;
        }
        return $_obfuscate_xs33Yt_k;
    }

    private function _cuiban( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_ecqaH_ev7A�� = getpar( $_POST, "uStepId", 0 );
        $_obfuscate__WwKzYz1wA�� = getpar( $_POST, "content", "" );
        $_obfuscate_a9lP = getpar( $_POST, "sms", "false" );
        $_obfuscate_a9lP = $_obfuscate_a9lP == "false" ? FALSE : TRUE;
        $_obfuscate_NS44QYk� = $CNOA_SESSION->get( "TRUENAME" );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowNumber", "flowName", "flowId" ), $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $_obfuscate_7qDAYo85aGA� = $CNOA_DB->db_getone( array( "tplSort", "flowType" ), $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_hTew0boWJESy['flowId']."'" );
        $_obfuscate_5NhzjnJq_f8� = $CNOA_DB->db_getone( array( "id", "uid" ), $this->t_use_step, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ��." AND `uStepId`={$_obfuscate_ecqaH_ev7A��}" );
        $_obfuscate_lQ81YBM� = $this->getProxyUid( $_obfuscate_hTew0boWJESy['flowId'], $_obfuscate_5NhzjnJq_f8�['uid'] );
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_6RYLWQ��['content'] = $_obfuscate_NS44QYk�.lang( "flowDealRemin" ).":".$_obfuscate__WwKzYz1wA��;
        $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_ecqaH_ev7A��}&flowType={$_obfuscate_7qDAYo85aGA�['flowType']}&tplSort={$_obfuscate_7qDAYo85aGA�['tplSort']}";
        $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_5NhzjnJq_f8�['id'];
        $this->addNotice( "notice", array(
            $_obfuscate_5NhzjnJq_f8�['uid'],
            $_obfuscate_lQ81YBM�
        ), $_obfuscate_6RYLWQ��, "warn" );
        if ( $_obfuscate_a9lP )
        {
            ( );
            $_obfuscate_a9lP = new sms( );
            $_obfuscate_a9lP->sendByUids( array(
                $_obfuscate_5NhzjnJq_f8�['uid'],
                $_obfuscate_lQ81YBM�
            ), $_obfuscate_6RYLWQ��['content'], 0, "flow", 0 );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3631, lang( "remindFlow" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _addChildFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_0W8� = getpar( $_POST, "id", 0 );
        $_obfuscate_sx8� = $CNOA_DB->db_getone( "*", $this->t_use_step_child_flow, "WHERE `id` = ".$_obfuscate_0W8�." " );
        unset( $_obfuscate_sx8�['id'] );
        $_obfuscate_sx8�['original'] = $_obfuscate_0W8�;
        $_obfuscate_sx8�['status'] = 0;
        $CNOA_DB->db_insert( $_obfuscate_sx8�, $this->t_use_step_child_flow );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _deleteChildFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_0W8� = getpar( $_POST, "id", 0 );
        $_obfuscate_tC8MNsAzXA�� = $CNOA_DB->db_getfield( "faqiUid", $this->t_use_step_child_flow, "WHERE `id`=".$_obfuscate_0W8� );
        if ( $_obfuscate_tC8MNsAzXA�� != $_obfuscate_7Ri3 )
        {
            msg::callback( FALSE, lang( "currenUserNotSponsor" ) );
        }
        $CNOA_DB->db_delete( $this->t_use_step_child_flow, "WHERE `id` = ".$_obfuscate_0W8�." " );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _cancelChildFaqi( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_0W8� = getpar( $_POST, "id", 0 );
        $_obfuscate_3qoQ0OmYgg� = $CNOA_DB->db_getone( array( "faqiUid", "postuid" ), $this->t_use_step_child_flow, "WHERE `id`=".$_obfuscate_0W8� );
        if ( !in_array( $_obfuscate_7Ri3, array(
            $_obfuscate_3qoQ0OmYgg�['faqiUid'],
            $_obfuscate_3qoQ0OmYgg�['postuid']
        ) ) )
        {
            msg::callback( FALSE, lang( "currentUserNotCancel" ) );
        }
        $CNOA_DB->db_update( array( "status" => 0, "faqitime" => 0, "faqiUid" => 0 ), $this->t_use_step_child_flow, "WHERE `id` = ".$_obfuscate_0W8�." " );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function __getPrevstepData( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $_obfuscate_5NhzjnJq_f8� = $CNOA_DB->db_getone( "*", $this->t_use_step, "WHERE `uFlowId` = ".$_obfuscate_TlvKhtsoOQ��." AND `uStepId` = {$_obfuscate_0Ul8BBkt} " );
        if ( $_obfuscate_5NhzjnJq_f8�['stepType'] == 6 )
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_step, "WHERE `uFlowId` = ".$_obfuscate_TlvKhtsoOQ��." AND `dealUid`!=0 AND (`status` = 2 OR `status` = 4 OR (`status` = 0 AND `stepType` = 4)) AND (`stepType` = 6 AND `pStepId` != {$_obfuscate_5NhzjnJq_f8�['pStepId']} OR `uStepId` != {$_obfuscate_5NhzjnJq_f8�['pStepId']}) ORDER BY `id` ASC " );
        }
        else
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_step, "WHERE `uFlowId` = ".$_obfuscate_TlvKhtsoOQ��." AND `dealUid`!=0 AND (`status` = 2 OR `status` = 4 OR (`status` = 0 AND `stepType` = 4)) GROUP BY `uStepId` ORDER BY `id` ASC " );
        }
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        return $_obfuscate_mPAjEGLn;
    }

    private function __updateHuiqianStatus( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_0Ul8BBkt, $_obfuscate_7Ri3 )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $CNOA_DB->db_update( array( "status" => 2 ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' AND `uid` = '{$_obfuscate_7Ri3}' " );
    }

    private function __isMyStep( $_obfuscate_TlvKhtsoOQ�� )
    {
    }

    private function _submitFreeSendBackData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQ�� = ( integer )getpar( $_POST, "uFlowId" );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_POST, "stepId" );
        $_obfuscate_ecqaH_ev7A�� = ( integer )getpar( $_POST, "uStepId" );
        $_obfuscate_1l6P = getpar( $_POST, "say", "退回该流程" );
        $_obfuscate_XkuTFqZ6Tmk� = ( integer )getpar( $_POST, "flowType" );
        $_obfuscate_pEvU7Kz2Yw�� = ( integer )getpar( $_POST, "tplSort" );
        $_obfuscate_HYI4w55m58H2WjCs = $this->getAllStepByUFlowId( $_obfuscate_TlvKhtsoOQ�� );
        $_obfuscate_j9eamhY� = $_obfuscate_HYI4w55m58H2WjCs['stepInfo'];
        if ( $_obfuscate_HYI4w55m58H2WjCs['stepInfo'][$_obfuscate_0Ul8BBkt]['status'] != 1 )
        {
            msg::callback( FALSE, lang( "noPermitOpt" ) );
        }
        $_obfuscate_pp9pYw�� = array( );
        $this->getPath( $_obfuscate_j9eamhY�, $_obfuscate_0Ul8BBkt, $_obfuscate_pp9pYw�� );
        $_obfuscate_umeHLFZ6 = $_obfuscate_j9eamhY�[$_obfuscate_ecqaH_ev7A��];
        $_obfuscate_NenxpX50gw�� = $_obfuscate_j9eamhY�[$_obfuscate_0Ul8BBkt];
        $_obfuscate_rX2e4Qw_AQ�� = array( );
        $_obfuscate_ZRB1fr3t0YM� = array( );
        foreach ( $_obfuscate_pp9pYw�� as $_obfuscate_Cy1W => $_obfuscate_WKs3DA�� )
        {
            if ( $_obfuscate_Cy1W == $_obfuscate_ecqaH_ev7A�� )
            {
                $_obfuscate_ZRB1fr3t0YM� = $_obfuscate_Cy1W;
            }
            else
            {
                $_obfuscate_rX2e4Qw_AQ��[] = $_obfuscate_Cy1W;
                if ( is_array( $_obfuscate_WKs3DA�� ) )
                {
                    foreach ( $_obfuscate_WKs3DA�� as $_obfuscate_cO77 => $_obfuscate_9HHDStdl )
                    {
                        if ( is_array( $_obfuscate_9HHDStdl ) )
                        {
                            $_obfuscate_rX2e4Qw_AQ�� = array_merge( $_obfuscate_rX2e4Qw_AQ��, $_obfuscate_9HHDStdl );
                        }
                    }
                }
            }
        }
        $_obfuscate_BRPhVA�� = array( );
        $_obfuscate_8CJrZg�� = array( );
        $_obfuscate_BRPhVA�� = $_obfuscate_j9eamhY�[$_obfuscate_ZRB1fr3t0YM�]['id'];
        foreach ( $_obfuscate_rX2e4Qw_AQ�� as $_obfuscate_6A�� )
        {
            $_obfuscate_8CJrZg��[] = $_obfuscate_j9eamhY�[$_obfuscate_6A��]['id'];
        }
        if ( $_obfuscate_ZRB1fr3t0YM� == 1 )
        {
            msg::callback( FALSE, lang( "freeFlowNotAllowReturn" ) );
        }
        $_obfuscate_8CJrZg�� = implode( ",", $_obfuscate_8CJrZg�� );
        $_obfuscate_etgCHrO23Qft = array( "dealUid" => 0, "etime" => 0, "say" => "", "status" => 1, "stepType" => 1 );
        $_obfuscate_P8p3tYC05bV7iA�� = array( "dealUid" => 0, "etime" => 0, "say" => "", "status" => 0, "stepType" => 1 );
        $CNOA_DB->db_update( $_obfuscate_etgCHrO23Qft, $this->t_use_step, "WHERE `id` IN (".$_obfuscate_BRPhVA��.") AND `uFlowId`={$_obfuscate_TlvKhtsoOQ��} " );
        if ( !empty( $_obfuscate_8CJrZg�� ) )
        {
            $CNOA_DB->db_update( $_obfuscate_P8p3tYC05bV7iA��, $this->t_use_step, "WHERE `id` IN (".$_obfuscate_8CJrZg��.") AND `uFlowId`={$_obfuscate_TlvKhtsoOQ��} " );
        }
        unset( $_obfuscate_8CJrZg�� );
        unset( $_obfuscate_BRPhVA�� );
        ( $_obfuscate_TlvKhtsoOQ�� );
        $_obfuscate_e53ODz04JQ�� = new wfCache( );
        $_obfuscate_7qDAYo85aGA� = $_obfuscate_e53ODz04JQ��->getFlow( );
        $this->doneAll( "both", $_obfuscate_NenxpX50gw��['id'], "todo" );
        $this->doneAll( "notice", $_obfuscate_NenxpX50gw��['id'], "huiqian", 1 );
        if ( $_obfuscate_7qDAYo85aGA�['noticeAtGoBack'] != 4 )
        {
            $_obfuscate_6RYLWQ�� = array( );
            $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber", "flowId" ), $this->t_use_flow, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ�� );
            if ( $_obfuscate_XkuTFqZ6Tmk� == 0 )
            {
                $_obfuscate_qZkmBg�� = $_obfuscate_e53ODz04JQ��->getFlow( );
            }
            else
            {
                $_obfuscate_qZkmBg�� = $CNOA_DB->db_getone( array( "noticeAtGoBack" ), $this->t_set_flow, "WHERE `flowId`=".$_obfuscate_hTew0boWJESy['flowId'] );
            }
            $_obfuscate_rLakms3x4ur74Q�� = array( );
            $_obfuscate_VBCv7Q�� = $_obfuscate_j9eamhY�[$_obfuscate_ZRB1fr3t0YM�];
            if ( !empty( $_obfuscate_VBCv7Q��['dealUid'] ) )
            {
                $_obfuscate_rLakms3x4ur74Q��[] = $_obfuscate_VBCv7Q��['dealUid'];
            }
            $_obfuscate_6RYLWQ�� = array( );
            $_obfuscate_6RYLWQ��['content'] = lang( "flowName" ).( "[".$_obfuscate_hTew0boWJESy['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_hTew0boWJESy['flowNumber']."]" ).lang( "beReturnNeedReApp" )."<br /> ".lang( "returnReason" ).( ":[".$_obfuscate_1l6P."]" );
            $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_VBCv7Q��['uStepId']}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
            $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_VBCv7Q��['id'];
            $this->addNotice( "both", $_obfuscate_VBCv7Q��['dealUid'], $_obfuscate_6RYLWQ��, "todo", 0 );
            if ( $_obfuscate_qZkmBg��['noticeAtGoBack'] == 2 )
            {
                $_obfuscate_VBCv7Q�� = $_obfuscate_j9eamhY�[2];
                if ( !in_array( $_obfuscate_VBCv7Q��['dealUid'], $_obfuscate_rLakms3x4ur74Q�� ) )
                {
                    $_obfuscate_6RYLWQ��['content'] = lang( "flowName" ).( "[".$_obfuscate_hTew0boWJESy['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_hTew0boWJESy['flowNumber']."]" ).lang( "beReturn" );
                    $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step=2&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
                    $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_VBCv7Q��['id'];
                    $this->addNotice( "notice", $_obfuscate_VBCv7Q��['dealUid'], $_obfuscate_6RYLWQ��, "tuihui", 2 );
                }
            }
            else if ( $_obfuscate_qZkmBg��['noticeAtGoBack'] == 3 )
            {
                $_obfuscate_6RYLWQ��['content'] = lang( "flowName" ).( "[".$_obfuscate_hTew0boWJESy['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_hTew0boWJESy['flowNumber']."]" ).lang( "beReturn" );
                $_obfuscate_rLakms3x4ur74Q�� = implode( ",", $_obfuscate_rLakms3x4ur74Q�� );
                $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "dealUid", "id", "uStepId" ), $this->t_use_step, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ��." AND `uStepId`!=2 AND `dealUid`!=0 AND `dealUid` NOT IN ({$_obfuscate_rLakms3x4ur74Q��})" );
                if ( !is_array( $_obfuscate_Tx7M9W ) )
                {
                    $_obfuscate_Tx7M9W = array( );
                }
                foreach ( $_obfuscate_Tx7M9W as $_obfuscate_VBCv7Q�� )
                {
                    $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_VBCv7Q��['uStepId']}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
                    $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_VBCv7Q��['id'];
                    $this->addNotice( "notice", $_obfuscate_VBCv7Q��['dealUid'], $_obfuscate_6RYLWQ��, "tuihui", 1 );
                }
            }
        }
        $_obfuscate_JG8GuY� = array( );
        $_obfuscate_JG8GuY�['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
        $_obfuscate_JG8GuY�['type'] = 4;
        $_obfuscate_JG8GuY�['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuY�['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuY�['say'] = $_obfuscate_1l6P;
        $_obfuscate_VBCv7Q�� = $_obfuscate_j9eamhY�[$_obfuscate_ZRB1fr3t0YM�];
        $_obfuscate_JG8GuY�['step'] = $_obfuscate_ZRB1fr3t0YM�;
        $_obfuscate_JG8GuY�['stepname'] = $_obfuscate_VBCv7Q��['stepname'];
        $this->insertEvent( $_obfuscate_JG8GuY� );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3604, lang( "backToFlowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function clearReturnOption( $_obfuscate_S0PSA37yAw��, $_obfuscate_TlvKhtsoOQ�� )
    {
        global $CNOA_DB;
        $_obfuscate_7qDAYo85aGA� = $CNOA_DB->db_getone( array( "flowId" ), $this->t_use_flow, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ�� );
        $_obfuscate_F4AbnVRh = $_obfuscate_7qDAYo85aGA�['flowId'];
        $_obfuscate_OwOOwPh71SP8g�� = "z_wf_t_".$_obfuscate_F4AbnVRh;
        foreach ( $_obfuscate_S0PSA37yAw�� as $_obfuscate_0Ul8BBkt )
        {
            $_obfuscate_KCPlBXFqX1tKA�� = $CNOA_DB->db_select( "*", $this->t_set_step_fields, "WHERE `stepId`=".$_obfuscate_0Ul8BBkt." AND `flowId`={$_obfuscate_F4AbnVRh} AND `write`=1 AND `from`=0" );
            if ( !is_array( $_obfuscate_KCPlBXFqX1tKA�� ) )
            {
                $_obfuscate_KCPlBXFqX1tKA�� = array( );
            }
            if ( $_obfuscate_KCPlBXFqX1tKA�� )
            {
                foreach ( $_obfuscate_KCPlBXFqX1tKA�� as $_obfuscate_tjILu7ZH )
                {
                    $_obfuscate_YIq2A8c� = $CNOA_DB->db_getone( array( "otype", "dvalue" ), $this->t_set_field, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND `id`={$_obfuscate_tjILu7ZH['fieldId']}" );
                    if ( !( $_obfuscate_tjILu7ZH['from'] == 0 ) && !( $_obfuscate_YIq2A8c�['otype'] != "detailtable" ) )
                    {
                        $_obfuscate_255EBzbYVVE� = "T_".$_obfuscate_tjILu7ZH['fieldId'];
                        $_obfuscate_XoQj4PaA = $_obfuscate_YIq2A8c�['dvalue'] == "default" ? "" : $_obfuscate_YIq2A8c�['dvalue'];
                        $CNOA_DB->db_update( array(
                            $_obfuscate_255EBzbYVVE� => $_obfuscate_XoQj4PaA
                        ), $_obfuscate_OwOOwPh71SP8g��, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ�� );
                    }
                }
            }
        }
        return TRUE;
    }

    public function api_loadUflowInfo( $_obfuscate_vholQ�� = NULL )
    {
        return $this->_loadUflowInfo( $_obfuscate_vholQ�� );
    }

    public function api_getFlowTypeData( )
    {
        $_obfuscate_fMfssw�� = $this->_getFlowTypeData( );
        return $_obfuscate_fMfssw��;
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

    public function api_addFenfa( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $_obfuscate_xs33Yt_k = $CNOA_DB->db_getone( "*", $this->t_set_autoFenfa, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND `stepId`={$_obfuscate_0Ul8BBkt}" );
        $_obfuscate_6b8lIO4y = $_obfuscate_xs33Yt_k['status'];
        $_obfuscate__eqrEQ�� = $_obfuscate_xs33Yt_k['uids'];
        if ( $_obfuscate_6b8lIO4y )
        {
            $_obfuscate__eqrEQ�� = explode( ",", $_obfuscate__eqrEQ�� );
            $this->_addFenfa( $_obfuscate_TlvKhtsoOQ��, $_obfuscate__eqrEQ��, $_obfuscate_0Ul8BBkt, TRUE );
        }
    }

    private function _checkHuiqian( )
    {
        global $CNOA_DB;
        $_obfuscate_TlvKhtsoOQ�� = ( integer )getpar( $_POST, "uFlowId" );
        $_obfuscate_0Ul8BBkt = ( integer )getpar( $_POST, "step" );
        $_obfuscate_qPt6frCIa6xJ0og� = $CNOA_DB->db_select( "*", $this->t_use_step_huiqian, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ��." AND `stepId`={$_obfuscate_0Ul8BBkt}" );
        if ( !is_array( $_obfuscate_qPt6frCIa6xJ0og� ) )
        {
            $_obfuscate_qPt6frCIa6xJ0og� = array( );
        }
        $_obfuscate_WkJpjIf2P4ulnQ�� = 0;
        if ( empty( $_obfuscate_qPt6frCIa6xJ0og� ) )
        {
            msg::callback( FALSE, "" );
        }
        else
        {
            foreach ( $_obfuscate_qPt6frCIa6xJ0og� as $_obfuscate_6A�� )
            {
                if ( $_obfuscate_6A��['status'] == 0 )
                {
                    $_obfuscate_WkJpjIf2P4ulnQ�� += 1;
                }
            }
            if ( $_obfuscate_WkJpjIf2P4ulnQ�� != 0 )
            {
                msg::callback( TRUE, "该步骤有".$_obfuscate_WkJpjIf2P4ulnQ��."条会签意见没提交，是否转下一步？" );
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
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_NlQ�->makeJsonData( );
    }

    private function _saveFieldDraft( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_6RYLWQ�� = $_obfuscate_ueAmmqePj0k� = $_obfuscate_dGoPOiQ2Iw5a = $_obfuscate_FYo_0_BVp9xjgDs� = $_obfuscate_1_pbjTIdLU49 = $_obfuscate_n03EzxA4CC93dt0� = array( );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId" );
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId" );
        $_obfuscate_VBCv7Q�� = getpar( $_POST, "step" );
        $_obfuscate_LeS8hw�� = getpar( $_POST, "type" );
        $_obfuscate_1l6P = getpar( $_POST, "say" );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        ( $_obfuscate_TlvKhtsoOQ�� );
        $_obfuscate_e53ODz04JQ�� = new wfCache( );
        $_obfuscate_WMVwRv5Dg�� = "cwj&flowId=".$_obfuscate_F4AbnVRh."&uFlowId={$_obfuscate_TlvKhtsoOQ��}&step={$_obfuscate_VBCv7Q��}";
        $_obfuscate_PW9SQhMxAg�� = $this->_getFilePath( $_obfuscate_WMVwRv5Dg�� );
        $_obfuscate_4puJ00P0cS = $this->api_getWfFieldData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, TRUE );
        foreach ( $GLOBALS['_POST'] as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( ereg( "wf_field_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_0W8� = str_replace( "wf_field_", "", $_obfuscate_5w�� );
                $_obfuscate_ueAmmqePj0k�[$_obfuscate_0W8�] = $_obfuscate_6A��;
            }
            if ( ereg( "wf_fieldJ_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_0W8� = str_replace( "wf_fieldJ_", "", $_obfuscate_5w�� );
                $_obfuscate_ueAmmqePj0k�[$_obfuscate_0W8�] = $_obfuscate_6A��;
            }
            if ( ereg( "wf_fieldC_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_0W8� = str_replace( "wf_fieldC_", "", $_obfuscate_5w�� );
                $_obfuscate_SeV31Q�� = explode( "_", $_obfuscate_0W8� );
                $_obfuscate_0W8� = $_obfuscate_SeV31Q��[0];
                $_obfuscate_ueAmmqePj0k�[$_obfuscate_0W8�][$_obfuscate_SeV31Q��[1]] = $_obfuscate_6A��;
            }
            if ( ereg( "wf_detail_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_SeV31Q�� = explode( "_", str_replace( "wf_detail_", "", $_obfuscate_5w�� ) );
                $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_SeV31Q��[0]][$_obfuscate_SeV31Q��[1]] = $_obfuscate_6A��;
            }
            if ( ereg( "wf_detailJ_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_SeV31Q�� = explode( "_", str_replace( "wf_detailJ_", "", $_obfuscate_5w�� ) );
                $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_SeV31Q��[0]][$_obfuscate_SeV31Q��[1]] = $_obfuscate_6A��;
            }
            if ( ereg( "detailid_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_FYo_0_BVp9xjgDs�[] = intval( str_replace( "detailid_", "", $_obfuscate_5w�� ) );
            }
            if ( ereg( "wf_attach", $_obfuscate_5w�� ) )
            {
                $_obfuscate_1_pbjTIdLU49[] = intval( str_replace( "wf_attach_", "", $_obfuscate_5w�� ) );
            }
        }
        if ( $_FILES )
        {
            ( );
            $_obfuscate_2gg� = new fs( );
            $_obfuscate_KE_ngbhTLbfzRa4n = $_obfuscate_2gg�->uploadFile4Wf( "", "", 1 );
            $_obfuscate_1_pbjTIdLU49 = array_merge( $_obfuscate_1_pbjTIdLU49, $_obfuscate_KE_ngbhTLbfzRa4n );
        }
        if ( $_obfuscate_FYo_0_BVp9xjgDs� )
        {
            $_obfuscate_7Hp0w_lfFt4� = $CNOA_DB->db_select( array( "id", "fid" ), $this->t_set_field_detail, "WHERE `fid` IN(".implode( ",", $_obfuscate_FYo_0_BVp9xjgDs� ).")" );
            if ( !is_array( $_obfuscate_7Hp0w_lfFt4� ) )
            {
                $_obfuscate_7Hp0w_lfFt4� = array( );
            }
            foreach ( $_obfuscate_7Hp0w_lfFt4� as $_obfuscate_6A�� )
            {
                foreach ( $_obfuscate_dGoPOiQ2Iw5a as $_obfuscate_5w�� => $_obfuscate_snM� )
                {
                    if ( array_key_exists( $_obfuscate_6A��['id'], $_obfuscate_snM� ) )
                    {
                        $_obfuscate_6RYLWQ��['detail'][$_obfuscate_6A��['fid']][$_obfuscate_5w��][$_obfuscate_6A��['id']] = $_obfuscate_snM�[$_obfuscate_6A��['id']];
                    }
                }
            }
        }
        foreach ( $_obfuscate_4puJ00P0cS as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_YIq2A8c� = $_obfuscate_e53ODz04JQ��->getField( $_obfuscate_5w�� );
            if ( array_key_exists( $_obfuscate_5w��, $_obfuscate_ueAmmqePj0k� ) )
            {
                if ( $_obfuscate_YIq2A8c�['otype'] == "checkbox" )
                {
                    $_obfuscate_4puJ00P0cS[$_obfuscate_5w��] = json_encode( $_obfuscate_ueAmmqePj0k�[$_obfuscate_5w��] );
                    if ( $_obfuscate_LeS8hw�� == 2 )
                    {
                        $_obfuscate_4puJ00P0cS[$_obfuscate_5w��] = addcslashes( $_obfuscate_4puJ00P0cS[$_obfuscate_5w��], "\\" );
                    }
                }
                else
                {
                    $_obfuscate_4puJ00P0cS[$_obfuscate_5w��] = $_obfuscate_ueAmmqePj0k�[$_obfuscate_5w��];
                }
            }
        }
        $_obfuscate_6RYLWQ��['field'] = $_obfuscate_4puJ00P0cS;
        if ( $_obfuscate_6RYLWQ��['detail'] )
        {
            foreach ( $_obfuscate_6RYLWQ��['detail'] as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                ksort( &$_obfuscate_6RYLWQ��['detail'][$_obfuscate_5w��] );
            }
        }
        $_obfuscate_6RYLWQ��['attach'] = json_encode( $_obfuscate_1_pbjTIdLU49 );
        $_obfuscate_6RYLWQ��['newAttach'] = $_obfuscate_KE_ngbhTLbfzRa4n;
        if ( $_obfuscate_LeS8hw�� == "1" )
        {
            $_obfuscate_R2_b = "<?php \n return ".var_export( $_obfuscate_6RYLWQ��, TRUE ).";";
            file_put_contents( $_obfuscate_PW9SQhMxAg��, $_obfuscate_R2_b );
        }
        else if ( $_obfuscate_LeS8hw�� == "2" )
        {
            foreach ( $_obfuscate_6RYLWQ�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_0fY05oSiDdok6A�� = array( );
                if ( $_obfuscate_5w�� == "field" )
                {
                    foreach ( $_obfuscate_6A�� as $_obfuscate_ty0� => $_obfuscate_snM� )
                    {
                        $_obfuscate_0fY05oSiDdok6A��["T_".$_obfuscate_ty0�] = $_obfuscate_snM�;
                    }
                    $CNOA_DB->db_update( $_obfuscate_0fY05oSiDdok6A��, "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ�� );
                }
                if ( $_obfuscate_5w�� == "detail" )
                {
                    foreach ( $_obfuscate_6A�� as $_obfuscate_8jhldA9Y9A�� => $_obfuscate_iGxAiPLV2uzR )
                    {
                        if ( $_obfuscate_iGxAiPLV2uzR )
                        {
                            $_obfuscate_Gfham6St = $_obfuscate_0fY05oSiDdok6A�� = array( );
                            $_obfuscate_3tiDsnM� = "z_wf_d_".$_obfuscate_F4AbnVRh."_{$_obfuscate_8jhldA9Y9A��}";
                            foreach ( $_obfuscate_iGxAiPLV2uzR as $_obfuscate_KF0� => $_obfuscate_r9K7G3QJ )
                            {
                                $_obfuscate_Gfham6St[] = $_obfuscate_KF0�;
                                $_obfuscate_8Q1yVKU� = $CNOA_DB->db_getone( "*", $_obfuscate_3tiDsnM�, "WHERE `rowid`=".$_obfuscate_KF0�." AND `uFlowId`={$_obfuscate_TlvKhtsoOQ��}" );
                                foreach ( $_obfuscate_r9K7G3QJ as $_obfuscate_ty0� => $_obfuscate_snM� )
                                {
                                    $_obfuscate_0fY05oSiDdok6A��["D_".$_obfuscate_ty0�] = $_obfuscate_snM�;
                                }
                                $_obfuscate_0fY05oSiDdok6A��['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
                                $_obfuscate_0fY05oSiDdok6A��['rowid'] = $_obfuscate_KF0�;
                                if ( $_obfuscate_8Q1yVKU� )
                                {
                                    $CNOA_DB->db_update( $_obfuscate_0fY05oSiDdok6A��, $_obfuscate_3tiDsnM�, "WHERE `rowid`=".$_obfuscate_KF0�." AND `uFlowId`={$_obfuscate_TlvKhtsoOQ��}" );
                                }
                                else
                                {
                                    $CNOA_DB->db_insert( $_obfuscate_0fY05oSiDdok6A��, $_obfuscate_3tiDsnM� );
                                }
                            }
                            $CNOA_DB->db_delete( $_obfuscate_3tiDsnM�, "WHERE `rowid` NOT IN(".implode( ",", $_obfuscate_Gfham6St ).( ") AND `uFlowId`=".$_obfuscate_TlvKhtsoOQ�� ) );
                        }
                    }
                }
                if ( $_obfuscate_5w�� == "attach" )
                {
                    $_obfuscate_OrvB8VTuw�� = array(
                        "attach" => $_obfuscate_6A��
                    );
                    $CNOA_DB->db_update( $_obfuscate_OrvB8VTuw��, $this->t_use_flow, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND `uFlowId`={$_obfuscate_TlvKhtsoOQ��}" );
                }
            }
            $_obfuscate_JG8GuY�['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
            $_obfuscate_JG8GuY�['step'] = $_obfuscate_VBCv7Q��;
            $_obfuscate_JG8GuY�['uid'] = $_obfuscate_7Ri3;
            $_obfuscate_JG8GuY�['say'] = $_obfuscate_1l6P;
            $_obfuscate_JG8GuY�['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $_obfuscate_JG8GuY�['stepname'] = $this->_getStepName( $_obfuscate_F4AbnVRh, $_obfuscate_VBCv7Q�� );
            $_obfuscate_JG8GuY�['type'] = 13;
            $this->insertEvent( $_obfuscate_JG8GuY� );
            $_obfuscate_qpSFgn2oZ1R6 = $this->_checkDraft( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_VBCv7Q�� );
            if ( $_obfuscate_qpSFgn2oZ1R6 )
            {
                unlink( $_obfuscate_qpSFgn2oZ1R6 );
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getFilePath( $_obfuscate_WMVwRv5Dg�� )
    {
        $_obfuscate_DfXWN0u3Qlf5yOo� = CNOA_PATH_FILE."/common/wf/draft/";
        @mkdirs( $_obfuscate_DfXWN0u3Qlf5yOo� );
        return $_obfuscate_DfXWN0u3Qlf5yOo�.$_obfuscate_WMVwRv5Dg��.".php";
    }

    private function _getStepName( $_obfuscate_F4AbnVRh, $_obfuscate_VBCv7Q�� )
    {
        global $CNOA_DB;
        $_obfuscate_sdCqoaGDkaA� = $CNOA_DB->db_getone( array( "stepName" ), $this->t_set_step, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND `stepId`={$_obfuscate_VBCv7Q��}" );
        return $_obfuscate_sdCqoaGDkaA�['stepName'];
    }

}

?>
