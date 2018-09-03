<?php

class wfFlowUseNew extends wfCommon
{

    public function run( )
    {
        $_obfuscate_M_5JJwÿÿ = getpar( $_GET, "task", "loadPage" );
        switch ( $_obfuscate_M_5JJwÿÿ )
        {
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "savePosition" :
            $this->_savePosition( );
            break;
        case "getJsonData" :
            $_obfuscate_vholQÿÿ = getpar( $_POST, "from", "normal" );
            if ( $_obfuscate_vholQÿÿ == "need" )
            {
                $this->_getNeedJsonData( );
            }
            else
            {
                $this->_getJsonData( );
            }
            break;
        case "getCollectFlow" :
            $this->_getCollectFlow( );
            break;
        case "getQueryList" :
            $this->_getQueryList( );
            break;
        case "getBusinessData" :
            $this->_getBusinessData( );
            break;
        case "getSortList" :
            $this->_getSortList( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $_obfuscate_phKp89pDgwQÿ = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $_obfuscate_phKp89pDgwQÿ );
            exit( );
        case "show_loadFormInfo" :
            $this->_show_loadFormInfo( );
            break;
        case "loadFormHtml" :
            $this->_loadFormHtml( );
            break;
        case "getSendNextData" :
            $this->_getSendNextData( );
            break;
        case "sendNextStep" :
            $this->_sendNextStep( );
            break;
        case "saveFlow" :
            $this->_saveFlow( );
            break;
        case "savefreeFlow" :
            $this->_savefreeFlow( );
            break;
        case "loadUflowInfo" :
            $this->_loadUflowInfo( );
            break;
        case "loadFlowDesignData" :
            $this->_loadFlowDesignData( );
            break;
        case "addFavFlow" :
            $this->_addFavFlow( );
            break;
        case "delFavFlow" :
            $this->_delFavFlow( );
            break;
        case "ms_loadTemplateFile" :
            $this->_ms_loadTemplateFile( );
            break;
        case "ms_submitMsOfficeData" :
            $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", 0 );
            $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_GET, "uFlowId", 0 );
            $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_GET, "tplSort", 0 );
            $this->api_saveTemplateData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_pEvU7Kz2Ywÿÿ );
            exit( );
        case "getSignature" :
            $this->_getSignature( );
            break;
        case "getDsFields" :
            $_obfuscate_oWs9ywUÿ = getpar( $_GET, "dsTag" );
            ( );
            $_obfuscate_NlQÿ = new wfDatasource( );
            $_obfuscate_tjILu7ZH = $_obfuscate_NlQÿ->getSourceFields( $_obfuscate_oWs9ywUÿ, FALSE );
            echo json_encode( $_obfuscate_tjILu7ZH );
            break;
        case "getDsData" :
            $_obfuscate_oWs9ywUÿ = getpar( $_GET, "dsTag" );
            ( );
            $_obfuscate_NlQÿ = new wfDatasource( );
            $_obfuscate_6RYLWQÿÿ = $_obfuscate_NlQÿ->getDsData( $_obfuscate_oWs9ywUÿ );
            echo $_obfuscate_6RYLWQÿÿ;
            break;
        case "delSalaryDraft" :
            $this->_delSalaryDraft( );
            break;
        case "bindingProcess" :
            $this->_bindingProcess( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $_obfuscate_vholQÿÿ = getpar( $_GET, "from" );
        if ( $_obfuscate_vholQÿÿ == "list" )
        {
            $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/list.htm";
            if ( getpar( $_GET, "sn" ) == "29462005873682712611" )
            {
                $_obfuscate_BxoH_SjRHQÿÿ = CNOA_PATH_FILE."/common/custom/29462005873682712611/new_list.htm";
            }
        }
        else if ( $_obfuscate_vholQÿÿ == "newflow" )
        {
            $_obfuscate_F4AbnVRh = ( integer )getpar( $_GET, "flowId" );
            if ( $_obfuscate_F4AbnVRh == 0 )
            {
                msg::showerror( lang( "noChoiceFlow" ) );
            }
            $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->db_getone( array( "flowType", "tplSort" ), $this->t_set_flow, "WHERE flowId=".$_obfuscate_F4AbnVRh );
            $GLOBALS['GLOBALS']['app']['flowId'] = $_obfuscate_F4AbnVRh;
            $GLOBALS['GLOBALS']['app']['uFlowId'] = 0;
            $GLOBALS['GLOBALS']['app']['action'] = "add";
            $GLOBALS['GLOBALS']['app']['flowType'] = ( integer )$_obfuscate_7qDAYo85aGAÿ['flowType'];
            $GLOBALS['GLOBALS']['app']['tplSort'] = ( integer )$_obfuscate_7qDAYo85aGAÿ['tplSort'];
            $GLOBALS['GLOBALS']['app']['childId'] = getpar( $_GET, "childId", 0 );
            $GLOBALS['GLOBALS']['app']['otherApp'] = getpar( $_GET, "otherApp", 0 );
            $GLOBALS['GLOBALS']['app']['puFlowId'] = getpar( $_GET, "puFlowId", 0 );
            $GLOBALS['GLOBALS']['app']['cid'] = getpar( $_GET, "cid", 0 );
            $GLOBALS['GLOBALS']['app']['pid'] = getpar( $_GET, "pid", 0 );
            $GLOBALS['GLOBALS']['app']['againId'] = getpar( $_GET, "againId", 0 );
            $_obfuscate_jOcDpChC9wÿÿ = $CNOA_DB->db_getone( array( "status", "uFlowId" ), $this->t_use_step_child_flow, "WHERE `id`=".$GLOBALS['app']['childId']." " );
            if ( $_obfuscate_jOcDpChC9wÿÿ['status'] == 2 || $_obfuscate_jOcDpChC9wÿÿ['status'] == 3 )
            {
                $GLOBALS['GLOBALS']['app']['uFlowId'] = $_obfuscate_jOcDpChC9wÿÿ['uFlowId'];
                $GLOBALS['GLOBALS']['app']['action'] = "edit";
                $GLOBALS['GLOBALS']['app']['step'] = ( integer )getpar( $_GET, "step", 0 );
                $GLOBALS['GLOBALS']['app']['flowId'] = $_obfuscate_F4AbnVRh;
                $GLOBALS['GLOBALS']['app']['wf']['tplSort'] = ( integer )$_obfuscate_7qDAYo85aGAÿ['tplSort'];
                $GLOBALS['GLOBALS']['app']['wf']['flowType'] = ( integer )$_obfuscate_7qDAYo85aGAÿ['flowType'];
                $GLOBALS['GLOBALS']['app']['wf']['type'] = "show";
                $GLOBALS['GLOBALS']['app']['status'] = $CNOA_DB->db_getfield( "status", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_jOcDpChC9wÿÿ['uFlowId']."'" );
                $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/showflow.htm";
            }
            else
            {
                $_obfuscate_VI9iDYjvhuCK9tEBZq8ÿ = $this->__checkDraft( $_obfuscate_F4AbnVRh );
                $GLOBALS['GLOBALS']['saveduFlowId'] = $_obfuscate_VI9iDYjvhuCK9tEBZq8ÿ['uFlowId'];
                $GLOBALS['GLOBALS']['saveduFlowTime'] = $_obfuscate_VI9iDYjvhuCK9tEBZq8ÿ['savedTime'];
                $GLOBALS['GLOBALS']['app']['userCid'] = ( integer )getpar( $_GET, "cid", 0 );
                $_obfuscate_tbbmYWlqU7jyQÿÿ = $this->__isFlowNewPermit( $GLOBALS['app']['flowId'] );
                $GLOBALS['GLOBALS']['havePermit'] = $_obfuscate_tbbmYWlqU7jyQÿÿ ? 1 : 0;
                $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/new.htm";
            }
        }
        else if ( $_obfuscate_vholQÿÿ == "editflow" )
        {
            $_obfuscate_TlvKhtsoOQÿÿ = ( integer )getpar( $_GET, "uFlowId" );
            if ( $_obfuscate_TlvKhtsoOQÿÿ == 0 )
            {
                msg::showerror( lang( "noChoiceFlow" ) );
            }
            $_obfuscate_3y0Y = "SELECT u.flowId, u.otherApp, s.tplSort, s.flowType FROM ".tname( $this->t_use_flow )." AS u  LEFT JOIN ".tname( $this->t_set_flow )." AS s on u.flowId = s.flowId  WHERE uFlowId = ".$_obfuscate_TlvKhtsoOQÿÿ;
            $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->get_one( $_obfuscate_3y0Y );
            $GLOBALS['GLOBALS']['app']['flowType'] = ( integer )$_obfuscate_7qDAYo85aGAÿ['flowType'];
            $GLOBALS['GLOBALS']['app']['tplSort'] = ( integer )$_obfuscate_7qDAYo85aGAÿ['tplSort'];
            $GLOBALS['GLOBALS']['app']['flowId'] = ( integer )$_obfuscate_7qDAYo85aGAÿ['flowId'];
            $GLOBALS['GLOBALS']['app']['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
            $GLOBALS['GLOBALS']['app']['action'] = "edit";
            $GLOBALS['GLOBALS']['app']['otherApp'] = $_obfuscate_7qDAYo85aGAÿ['otherApp'];
            $GLOBALS['GLOBALS']['app']['salaryApproveId'] = ( integer )getpar( $_GET, "salaryApproveId" );
            $GLOBALS['GLOBALS']['app']['noticeLid'] = ( integer )getpar( $_GET, "noticeLid" );
            $GLOBALS['GLOBALS']['havePermit'] = 1;
            if ( $_obfuscate_7qDAYo85aGAÿ['flowType'] == 0 )
            {
                if ( $_obfuscate_7qDAYo85aGAÿ['tplSort'] == 0 )
                {
                    $_obfuscate_tbbmYWlqU7jyQÿÿ = $this->__isFlowNewPermit( $_obfuscate_7qDAYo85aGAÿ['flowId'] );
                    $GLOBALS['GLOBALS']['havePermit'] = $_obfuscate_tbbmYWlqU7jyQÿÿ ? 1 : 0;
                }
                $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/new.htm";
            }
            else
            {
                $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/newfree_flowdesign.htm";
            }
        }
        $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQÿÿ );
        exit( );
    }

    public function _getSortList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_a49UK2tYTQÿÿ = app::loadapp( "wf", "flowSetSort" )->api_getSortTree( "faqi", TRUE );
        $_obfuscate_3UMpmYP6_RXGpYhmfE1rwÿÿ = $CNOA_DB->db_select( "*", $this->t_u_setting, "WHERE `uid`='".$_obfuscate_7Ri3."'" );
        if ( !is_array( $_obfuscate_3UMpmYP6_RXGpYhmfE1rwÿÿ ) )
        {
            $_obfuscate_3UMpmYP6_RXGpYhmfE1rwÿÿ = array( );
        }
        $_obfuscate_DlN3cp0_V5jHMzgfnmTgÿÿ = array( );
        foreach ( $_obfuscate_3UMpmYP6_RXGpYhmfE1rwÿÿ as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_DlN3cp0_V5jHMzgfnmTgÿÿ[$_obfuscate_6Aÿÿ['sortId']] = array(
                $_obfuscate_6Aÿÿ['column'],
                $_obfuscate_6Aÿÿ['position']
            );
        }
        $_obfuscate_OyHxCdsZEH5bk_wL = array( );
        foreach ( $_obfuscate_a49UK2tYTQÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6Aÿÿ['column'] = $_obfuscate_3UMpmYP6_RXGpYhmfE1rwÿÿ ? intval( $_obfuscate_DlN3cp0_V5jHMzgfnmTgÿÿ[$_obfuscate_6Aÿÿ['sortId']][0] ) : -1;
            $_obfuscate_6Aÿÿ['position'] = intval( $_obfuscate_DlN3cp0_V5jHMzgfnmTgÿÿ[$_obfuscate_6Aÿÿ['sortId']][1] );
            $_obfuscate_OyHxCdsZEH5bk_wL[$_obfuscate_6Aÿÿ['position']][] = $_obfuscate_6Aÿÿ;
        }
        ksort( &$_obfuscate_OyHxCdsZEH5bk_wL );
        $_obfuscate_a49UK2tYTQÿÿ = array( );
        foreach ( $_obfuscate_OyHxCdsZEH5bk_wL as $_obfuscate_6Aÿÿ )
        {
            if ( is_array( $_obfuscate_6Aÿÿ ) )
            {
                foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_snMÿ )
                {
                    $_obfuscate_a49UK2tYTQÿÿ[] = $_obfuscate_snMÿ;
                }
            }
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_a49UK2tYTQÿÿ;
        $_obfuscate_NlQÿ->childTotal = $CNOA_DB->db_getcount( $this->t_use_step_child_flow, "WHERE `faqiUid` = ".$_obfuscate_7Ri3." AND `status` = 1 " );
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    public function api_getSortList( $_obfuscate_vholQÿÿ, $_obfuscate_v1E5GY8ÿ, $_obfuscate_AmrqwIMÿ = TRUE )
    {
        $_obfuscate_a49UK2tYTQÿÿ = app::loadapp( "wf", "flowSetSort" )->api_getSortTree( $_obfuscate_vholQÿÿ, TRUE );
        $_obfuscate_OQÿÿ = array( );
        $_obfuscate_OQÿÿ['text'] = "æµç¨‹åˆ†ç±»åˆ—è¡¨";
        $_obfuscate_OQÿÿ['iconCls'] = "icon-tree-root-cnoa";
        $_obfuscate_OQÿÿ['expanded'] = TRUE;
        $_obfuscate_OQÿÿ['sortId'] = 0;
        $_obfuscate_OQÿÿ['href'] = "javascript:void(0);";
        $_obfuscate_OQÿÿ['children'] = $_obfuscate_a49UK2tYTQÿÿ;
        if ( $_obfuscate_AmrqwIMÿ )
        {
            $_obfuscate_oEsÿ = array( );
            $_obfuscate_oEsÿ['text'] = "æˆ‘çš„å¸¸ç”¨æµç¨‹(<span style=\"color:red\">".$_obfuscate_v1E5GY8ÿ."</span>)";
            $_obfuscate_oEsÿ['iconCls'] = "icon-heart";
            $_obfuscate_oEsÿ['from'] = "wffav";
            $_obfuscate_oEsÿ['expanded'] = TRUE;
            $_obfuscate_oEsÿ['leaf'] = TRUE;
            $_obfuscate_oEsÿ['href'] = "javascript:void(0);";
        }
        return array(
            $_obfuscate_OQÿÿ,
            $_obfuscate_oEsÿ
        );
    }

    public function api_getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_mV9HBLYÿ = getpar( $_POST, "start", 0 );
        $_obfuscate_v1GprsIz = getpar( $_POST, "sortId", 0 );
        $_obfuscate_ubP__Qÿÿ = getpar( $_POST, "sort", "" );
        $_obfuscate_neM4JBUJlmgÿ = getpar( $_POST, "flowName", "" );
        $_obfuscate_Bk2lGlkÿ = "";
        if ( empty( $_obfuscate_v1GprsIz ) )
        {
            $_obfuscate_ggyI68Xtqd62AQÿÿ = app::loadapp( "wf", "flowSetSort" )->api_getSortDB( "faqi" );
            $_obfuscate_xHt_KRTgaAYpz6jkpgÿÿ = array( 0 );
            foreach ( $_obfuscate_ggyI68Xtqd62AQÿÿ as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_xHt_KRTgaAYpz6jkpgÿÿ[] = $_obfuscate_6Aÿÿ['sortId'];
            }
            $_obfuscate_Bk2lGlkÿ .= "WHERE `sortId` IN (".implode( ",", $_obfuscate_xHt_KRTgaAYpz6jkpgÿÿ ).") ";
        }
        else
        {
            $_obfuscate_Bk2lGlkÿ .= "WHERE `sortId`='".$_obfuscate_v1GprsIz."' ";
        }
        if ( !empty( $_obfuscate_neM4JBUJlmgÿ ) )
        {
            $_obfuscate_Bk2lGlkÿ .= "AND `name` LIKE '%".$_obfuscate_neM4JBUJlmgÿ."%' ";
        }
        $_obfuscate_OTt14hA7OyXA44prAwUÿ = $this->_getSortPermitForNew( );
        $_obfuscate_Bk2lGlkÿ .= "AND `flowId` IN (".implode( ",", $_obfuscate_OTt14hA7OyXA44prAwUÿ ).") ";
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_flow, $_obfuscate_Bk2lGlkÿ.( "AND `status`='1' ORDER BY `flowId` DESC LIMIT ".$_obfuscate_mV9HBLYÿ.",{$this->rows}" ) );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_uly_hPh_dQÿÿ = array( 0 );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_uly_hPh_dQÿÿ[] = $_obfuscate_6Aÿÿ['sortId'];
        }
        $_obfuscate_RopcQP_w = $this->api_getSortByIds( $_obfuscate_uly_hPh_dQÿÿ );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['flowName'] = $_obfuscate_6Aÿÿ['name'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['sname'] = $_obfuscate_RopcQP_w[$_obfuscate_6Aÿÿ['sortId']]['name'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['abouttip'] = nl2br( $_obfuscate_6Aÿÿ['about'] );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_mPAjEGLn;
        $_obfuscate_SUjPN94Er7yI->total = $CNOA_DB->db_getcount( $this->t_set_flow, $_obfuscate_Bk2lGlkÿ."AND `status`='1' " );
        $_obfuscate_SUjPN94Er7yI->childTotal = $CNOA_DB->db_getcount( $this->t_use_step_child_flow, "WHERE `faqiUid` = ".$_obfuscate_7Ri3." AND `status` = 1 " );
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_v1GprsIz = getpar( $_POST, "sortId", 0 );
        $_obfuscate_Bk2lGlkÿ = "WHERE `sortId`='".$_obfuscate_v1GprsIz."' ";
        $_obfuscate_OTt14hA7OyXA44prAwUÿ = $this->_getSortPermitForNew( );
        $_obfuscate_Bk2lGlkÿ .= "AND `flowId` IN (".implode( ",", $_obfuscate_OTt14hA7OyXA44prAwUÿ ).") ";
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "sortId", "flowId", "name", "tplSort", "flowType", "about", "nameRuleId", "order" ), $this->t_set_flow, $_obfuscate_Bk2lGlkÿ."AND `status`='1' ORDER BY `order` ASC" );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_Qlo05cU_HQÿÿ = $CNOA_DB->db_select( "*", $this->t_u_wffav, "WHERE `uid`='".$_obfuscate_7Ri3."'" );
        if ( !is_array( $_obfuscate_Qlo05cU_HQÿÿ ) )
        {
            $_obfuscate_Qlo05cU_HQÿÿ = array( );
        }
        $_obfuscate_9HRMcXrsFGc6gÿÿ = array( );
        foreach ( $_obfuscate_Qlo05cU_HQÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_9HRMcXrsFGc6gÿÿ[] = $_obfuscate_6Aÿÿ['flowId'];
        }
        unset( $_obfuscate_Qlo05cU_HQÿÿ );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['about'] = empty( $_obfuscate_6Aÿÿ['about'] ) ? $_obfuscate_6Aÿÿ['name'] : nl2br( $_obfuscate_6Aÿÿ['about'] );
            if ( in_array( $_obfuscate_6Aÿÿ['flowId'], $_obfuscate_9HRMcXrsFGc6gÿÿ ) )
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['fav'] = 1;
            }
            else
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['fav'] = 0;
            }
        }
        usort( &$_obfuscate_mPAjEGLn, array(
            $this,
            "_my_sort"
        ) );
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _getCollectFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_3y0Y = "SELECT `w`.`flowId`, `f`.`name`, `f`.`tplSort`, `f`.`flowType`, `f`.`nameRuleId`, `f`.`about`, `s`.`name` AS `sname` FROM ".tname( $this->t_u_wffav )." as `w` RIGHT JOIN ".tname( $this->t_set_flow )." AS `f` ON `f`.`flowId`=`w`.`flowId` LEFT JOIN ".tname( $this->t_set_sort )." AS `s` ON `s`.`sortId`=`f`.`sortId` ".( "WHERE `w`.`status`=1 AND `w`.`uid`=".$_obfuscate_7Ri3 );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_6RYLWQÿÿ[] = array(
                "flowId" => $_obfuscate_gkt['flowId'],
                "flowName" => $_obfuscate_gkt['name'],
                "sname" => $_obfuscate_gkt['sname'],
                "about" => $_obfuscate_gkt['about'],
                "nameRuleId" => $_obfuscate_gkt['nameRuleId'],
                "tplSort" => $_obfuscate_gkt['tplSort'],
                "flowType" => $_obfuscate_gkt['flowType']
            );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_6RYLWQÿÿ;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _my_sort( $m, $_obfuscate_8Aÿÿ )
    {
        if ( $m['fav'] == $_obfuscate_8Aÿÿ['fav'] )
        {
            if ( $m['order'] < $_obfuscate_8Aÿÿ['order'] )
            {
                return -1;
            }
            return 1;
        }
        if ( $_obfuscate_8Aÿÿ['fav'] < $m['fav'] )
        {
            return -1;
        }
        return 1;
    }

    private function _getNeedJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_l2CIvUX0Kvp4 = $_obfuscate_mPAjEGLn = array( );
        $_obfuscate_3y0Y = "SELECT c.*, u.flowNumber, u.flowName AS puFlowName FROM ".tname( $this->t_use_step_child_flow )." AS c LEFT JOIN ".tname( $this->t_use_flow )." AS u ON c.puFlowId = u.uFlowId ".( "WHERE c.`faqiUid` = ".$_obfuscate_7Ri3." AND c.`status` = 1" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_l2CIvUX0Kvp4[] = $_obfuscate_gkt['flowId'];
            $_obfuscate_mPAjEGLn[] = $_obfuscate_gkt;
        }
        $_obfuscate_SIUSR4F6 = $CNOA_DB->db_select( array( "flowId", "name", "about", "sortId", "tplSort", "nameRuleId", "flowType" ), $this->t_set_flow, "WHERE `flowId` IN (".implode( ",", $_obfuscate_l2CIvUX0Kvp4 ).")  " );
        if ( !is_array( $_obfuscate_SIUSR4F6 ) )
        {
            $_obfuscate_SIUSR4F6 = array( );
        }
        $_obfuscate_R7khXq3cT4VZ = array( );
        $_obfuscate_YROI4z1Sytcm = array( );
        foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_R7khXq3cT4VZ[$_obfuscate_6Aÿÿ['flowId']] = $_obfuscate_6Aÿÿ;
            $_obfuscate_YROI4z1Sytcm[] = $_obfuscate_6Aÿÿ['sortId'];
        }
        $_obfuscate_RopcQP_w = app::loadapp( "wf", "flowSetSort" )->api_getSortData( array(
            "sortIdArr" => $_obfuscate_YROI4z1Sytcm
        ) );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['flowName'] = $_obfuscate_R7khXq3cT4VZ[$_obfuscate_6Aÿÿ['flowId']]['name'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['about'] = $_obfuscate_R7khXq3cT4VZ[$_obfuscate_6Aÿÿ['flowId']]['about'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['tplSort'] = $_obfuscate_R7khXq3cT4VZ[$_obfuscate_6Aÿÿ['flowId']]['tplSort'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['nameRuleId'] = $_obfuscate_R7khXq3cT4VZ[$_obfuscate_6Aÿÿ['flowId']]['nameRuleId'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['flowType'] = $_obfuscate_R7khXq3cT4VZ[$_obfuscate_6Aÿÿ['flowId']]['flowType'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['childId'] = $_obfuscate_6Aÿÿ['id'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['sname'] = $_obfuscate_RopcQP_w[$_obfuscate_R7khXq3cT4VZ[$_obfuscate_6Aÿÿ['flowId']]['sortId']]['name'];
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _getQueryList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mV9HBLYÿ = getpar( $_POST, "start", 0 );
        $_obfuscate_v1GprsIz = getpar( $_POST, "sortId", 0 );
        $_obfuscate_GRtq_c1sXSMthq47 = getpar( $_GET, "bindfunction", "" );
        $_obfuscate_Bk2lGlkÿ = "";
        if ( array_key_exists( $_obfuscate_GRtq_c1sXSMthq47, $this->bindFunctionList ) )
        {
            ( );
            $_obfuscate_83c0kD6Npoÿ = new wfEngine( );
            $_obfuscate_83c0kD6Npoÿ->getQueryData( $_obfuscate_GRtq_c1sXSMthq47 );
            exit( );
        }
    }

    private function _show_loadFormInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_o5fQ1gÿÿ = $CNOA_DB->db_getone( array( "formHtml" ), $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( !$_obfuscate_o5fQ1gÿÿ )
        {
            msg::callback( FALSE, lang( "nodata" ) );
        }
        $_obfuscate_lEGQqwÿÿ = str_replace( "&#39;", "'", $_obfuscate_o5fQ1gÿÿ['formHtml'] );
        $_obfuscate_DxNBNSYrOIcÿ = app::loadapp( "wf", "flowSetForm" )->api_getHtmlElement( $_obfuscate_lEGQqwÿÿ );
        ( $_obfuscate_DxNBNSYrOIcÿ, $_obfuscate_lEGQqwÿÿ );
        $_obfuscate_fgijdEZiFS2w4Q9zQQÿÿ = new wfFieldFormaterForPreview( );
        $_obfuscate_lEGQqwÿÿ = $_obfuscate_fgijdEZiFS2w4Q9zQQÿÿ->crteateFormHtml( );
        $_obfuscate_lEGQqwÿÿ = "<table class=\"wf_div_cttb\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n\t\t<tr>\r\n\t\t<td class=\"wf_bd wf_tl\"></td>\r\n\t\t<td class=\"wf_bd wf_t\"></td>\r\n\t\t<td class=\"wf_bd wf_tr\"></td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t<td class=\"wf_bd2 wf_l\"></td>\r\n\t\t<td style=\"padding:50px;\" class=\"wf_c wf_form_content\">".$_obfuscate_lEGQqwÿÿ."</td>\r\n\t\t<td class=\"wf_bd2 wf_r\"></td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t<td class=\"wf_bd wf_bl\"></td>\r\n\t\t<td class=\"wf_bd wf_b\"></td>\r\n\t\t<td class=\"wf_bd wf_br\"></td>\r\n\t\t</tr>\r\n\t\t</table>";
        $_obfuscate_o5fQ1gÿÿ['formHtml'] = $_obfuscate_lEGQqwÿÿ;
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->total = 0;
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_o5fQ1gÿÿ;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _loadFormHtml( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_F4AbnVRh = intval( getpar( $_GET, "flowId", 0 ) );
        $_obfuscate_TlvKhtsoOQÿÿ = intval( getpar( $_POST, "uFlowId", 0 ) );
        $_obfuscate_8ftcdUn7PAÿÿ = getpar( $_POST, "againId", 0 );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        ( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, TRUE, 0, $_obfuscate_8ftcdUn7PAÿÿ );
        $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ = new wfFieldFormaterForDeal( );
        $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ->uFlowId = $_obfuscate_8ftcdUn7PAÿÿ ? $_obfuscate_8ftcdUn7PAÿÿ : $_obfuscate_TlvKhtsoOQÿÿ;
        $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ->creatorUid = $_obfuscate_7Ri3;
        $_obfuscate_5MjAF_AntLkÿ = $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ->crteateFormHtml( );
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->total = 0;
        $_obfuscate_SUjPN94Er7yI->data = array(
            "formHtml" => $_obfuscate_5MjAF_AntLkÿ
        );
        $_obfuscate_SUjPN94Er7yI->pageset = $this->getPageSet( $_obfuscate_F4AbnVRh );
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _getSendNextData( )
    {
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_JQJwE4USnB0ÿ = array( );
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
        }
        $_obfuscate_7qDAYo85aGAÿ['flowId'] = $_obfuscate_F4AbnVRh;
        $_obfuscate_7qDAYo85aGAÿ['formData'] = $_obfuscate_JQJwE4USnB0ÿ;
        $_obfuscate_7qDAYo85aGAÿ['flowFrom'] = "new";
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        ( );
        $_obfuscate_xPTkGjow2od2YSeeUUcÿ = new wfNextStepData( );
        $_obfuscate_NlQÿ->data = $_obfuscate_xPTkGjow2od2YSeeUUcÿ->getNextStepInfo( $_obfuscate_7qDAYo85aGAÿ );
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _sendNextStep( )
    {
        ( );
        $_obfuscate_z9ygGPQKQgSeYn4opTWVK8gÿ = new wfNewSendNextStep( );
        ob_end_clean( );
        header( "content-type:text/html; charset=utf-8;" );
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = array(
            "uFlowId" => $_obfuscate_z9ygGPQKQgSeYn4opTWVK8gÿ->getuFlowId( )
        );
        echo "<textarea>".$_obfuscate_SUjPN94Er7yI->makeJsonData( )."</textarea>";
        exit( );
    }

    private function _makeDetailMaxCache( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_piwqe2DnH9mIPU0P )
    {
        $_obfuscate_6hS1Rwÿÿ = CNOA_PATH_FILE."/common/wf/detailmaxcache/";
        @mkdirs( $_obfuscate_6hS1Rwÿÿ );
        $_obfuscate_R2_b = "<?php\r\n";
        $_obfuscate_R2_b .= "return ".var_export( $_obfuscate_piwqe2DnH9mIPU0P, TRUE ).";";
        @file_put_contents( $_obfuscate_6hS1Rwÿÿ.$_obfuscate_TlvKhtsoOQÿÿ.".php", $_obfuscate_R2_b );
    }

    private function _saveFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_KYPe3Fn6DvBxAÿÿ = getpar( $_POST, "flowNumber", "" );
        $_obfuscate_neM4JBUJlmgÿ = getpar( $_POST, "flowName", "" );
        $_obfuscate_pYzeLf4ÿ = getpar( $_POST, "level", 0 );
        $_obfuscate_MtvJpVij = getpar( $_POST, "reason", "" );
        $_obfuscate_s8yswCWZEIYÿ = getpar( $_GET, "otherApp", "" );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_GET, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_GET, "tplSort", 0 );
        $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $_obfuscate_DZrwJy4LZQÿÿ = $this->_saveFormFieldInfo( );
        $_obfuscate_IuoXR2yOaxkRDwÿÿ = $_obfuscate_DZrwJy4LZQÿÿ[0];
        $_obfuscate_JQJwE4USnB0ÿ = $_obfuscate_DZrwJy4LZQÿÿ[1];
        $_obfuscate_FYo_0_BVp9xjgDsÿ = $_obfuscate_DZrwJy4LZQÿÿ[2];
        $_obfuscate_BqBV6WSz3wel0ZDw = $_obfuscate_DZrwJy4LZQÿÿ[3];
        $_obfuscate_piwqe2DnH9mIPU0P = $_obfuscate_DZrwJy4LZQÿÿ[4];
        $_obfuscate_qZkmBgÿÿ = array( );
        $_obfuscate_qZkmBgÿÿ['flowId'] = $_obfuscate_F4AbnVRh;
        $_obfuscate_qZkmBgÿÿ['flowNumber'] = $_obfuscate_KYPe3Fn6DvBxAÿÿ;
        $_obfuscate_qZkmBgÿÿ['flowName'] = $_obfuscate_neM4JBUJlmgÿ;
        $_obfuscate_qZkmBgÿÿ['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_qZkmBgÿÿ['level'] = $_obfuscate_pYzeLf4ÿ;
        $_obfuscate_qZkmBgÿÿ['reason'] = $_obfuscate_MtvJpVij;
        $_obfuscate_qZkmBgÿÿ['posttime'] = 0;
        $_obfuscate_qZkmBgÿÿ['updatetime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_qZkmBgÿÿ['edittime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_qZkmBgÿÿ['sortId'] = $_obfuscate_7qDAYo85aGAÿ['sortId'];
        $_obfuscate_qZkmBgÿÿ['tplSort'] = $_obfuscate_7qDAYo85aGAÿ['tplSort'];
        $_obfuscate_qZkmBgÿÿ['status'] = 0;
        $_obfuscate_qZkmBgÿÿ['otherApp'] = $_obfuscate_s8yswCWZEIYÿ;
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowId", "attach" ), $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `uFlowId`='{$_obfuscate_TlvKhtsoOQÿÿ}'" );
        $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ = json_decode( $_obfuscate_hTew0boWJESy['attach'], TRUE );
        if ( !is_array( $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ ) )
        {
            $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ = array( );
        }
        ( );
        $_obfuscate_2ggÿ = new fs( );
        $_obfuscate_1ERfSWbp = $_obfuscate_2ggÿ->uploadFile4Wf( $_obfuscate_IuoXR2yOaxkRDwÿÿ, $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ );
        $_obfuscate_qZkmBgÿÿ['attach'] = json_encode( $_obfuscate_1ERfSWbp[0] );
        if ( !$_obfuscate_hTew0boWJESy )
        {
            $_obfuscate_TlvKhtsoOQÿÿ = $CNOA_DB->db_insert( $_obfuscate_qZkmBgÿÿ, $this->t_use_flow );
        }
        else
        {
            $CNOA_DB->db_update( $_obfuscate_qZkmBgÿÿ, $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `uFlowId`='{$_obfuscate_TlvKhtsoOQÿÿ}'" );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3602, lang( "saveDraftBox" ) );
        $this->api_saveFieldData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_JQJwE4USnB0ÿ, $_obfuscate_FYo_0_BVp9xjgDsÿ, $_obfuscate_BqBV6WSz3wel0ZDw );
        $this->_makeDetailMaxCache( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_piwqe2DnH9mIPU0P );
        ob_end_clean( );
        header( "content-type:text/html; charset=utf-8;" );
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = array(
            "uFlowId" => $_obfuscate_TlvKhtsoOQÿÿ,
            "saveTime" => formatdate( $GLOBALS['CNOA_TIMESTAMP'], " Hæ—¶iåˆ†sç§’ " )
        );
        $_obfuscate_NlQÿ->attach = $_obfuscate_1ERfSWbp[1];
        echo "<textarea>".$_obfuscate_NlQÿ->makeJsonData( )."</textarea>";
        exit( );
    }

    private function _savefreeFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_KYPe3Fn6DvBxAÿÿ = getpar( $_POST, "flowNumber", "" );
        $_obfuscate_neM4JBUJlmgÿ = getpar( $_POST, "flowName", "" );
        $_obfuscate_pYzeLf4ÿ = getpar( $_POST, "level", 0 );
        $_obfuscate_MtvJpVij = getpar( $_POST, "reason", "" );
        $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $_obfuscate_IuoXR2yOaxkRDwÿÿ = array( );
        foreach ( $GLOBALS['_POST'] as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( ereg( "wf_attach_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_IuoXR2yOaxkRDwÿÿ[] = intval( str_replace( "wf_attach_", "", $_obfuscate_5wÿÿ ) );
            }
        }
        $_obfuscate_qZkmBgÿÿ = array( );
        $_obfuscate_qZkmBgÿÿ['flowId'] = $_obfuscate_F4AbnVRh;
        $_obfuscate_qZkmBgÿÿ['flowNumber'] = $_obfuscate_KYPe3Fn6DvBxAÿÿ;
        $_obfuscate_qZkmBgÿÿ['flowName'] = $_obfuscate_neM4JBUJlmgÿ;
        $_obfuscate_qZkmBgÿÿ['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_qZkmBgÿÿ['level'] = $_obfuscate_pYzeLf4ÿ;
        $_obfuscate_qZkmBgÿÿ['reason'] = $_obfuscate_MtvJpVij;
        $_obfuscate_qZkmBgÿÿ['posttime'] = 0;
        $_obfuscate_qZkmBgÿÿ['updatetime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_qZkmBgÿÿ['edittime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_qZkmBgÿÿ['sortId'] = $_obfuscate_7qDAYo85aGAÿ['sortId'];
        $_obfuscate_qZkmBgÿÿ['status'] = 0;
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowId", "attach" ), $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `uFlowId`='{$_obfuscate_TlvKhtsoOQÿÿ}'" );
        $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ = json_decode( $_obfuscate_hTew0boWJESy['attach'], TRUE );
        if ( !is_array( $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ ) )
        {
            $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ = array( );
        }
        ( );
        $_obfuscate_2ggÿ = new fs( );
        $_obfuscate_1ERfSWbp = $_obfuscate_2ggÿ->uploadFile4Wf( $_obfuscate_IuoXR2yOaxkRDwÿÿ, $_obfuscate_vCKayYE4IxP3uvQw0Aÿÿ );
        $_obfuscate_qZkmBgÿÿ['attach'] = json_encode( $_obfuscate_1ERfSWbp[0] );
        if ( !$_obfuscate_hTew0boWJESy )
        {
            $_obfuscate_TlvKhtsoOQÿÿ = $CNOA_DB->db_insert( $_obfuscate_qZkmBgÿÿ, $this->t_use_flow );
        }
        else
        {
            $CNOA_DB->db_update( $_obfuscate_qZkmBgÿÿ, $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `uFlowId`='{$_obfuscate_TlvKhtsoOQÿÿ}'" );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3602, lang( "saveDraftBox" ).",".lang( "flowName" ).( "[".$_obfuscate_qZkmBgÿÿ['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_qZkmBgÿÿ['flowNumber']."]" ) );
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = array(
            "uFlowId" => $_obfuscate_TlvKhtsoOQÿÿ,
            "attach" => $_obfuscate_1ERfSWbp[1],
            "saveTime" => formatdate( $GLOBALS['CNOA_TIMESTAMP'], " Hæ—¶iåˆ†sç§’ " )
        );
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _makeAttachAllow( $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( "*", $this->t_set_step, "WHERE `stepType` = 1 AND `flowId` = ".$_obfuscate_F4AbnVRh." " );
        if ( !is_array( $_obfuscate_Tx7M9W ) )
        {
            $_obfuscate_Tx7M9W = array( );
        }
        $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachAdd'] = $_obfuscate_Tx7M9W['allowAttachAdd'];
        $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachEdit'] = $_obfuscate_Tx7M9W['allowAttachEdit'];
        $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachDelete'] = $_obfuscate_Tx7M9W['allowAttachDelete'];
        $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachView'] = $_obfuscate_Tx7M9W['allowAttachView'];
        $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachDown'] = $_obfuscate_Tx7M9W['allowAttachDown'];
        return $_obfuscate_vzGArcjOKr8_7Aÿÿ;
    }

    private function _loadUflowInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_jTbXTguM6pC9CAÿÿ = getpar( $_POST, "editAction", "add" );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_POST, "flowType", 0 );
        $_obfuscate_d34ykzwUSke5REÿ = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $_obfuscate_6RYLWQÿÿ = array( );
        if ( $_obfuscate_jTbXTguM6pC9CAÿÿ == "edit" )
        {
            $_obfuscate_qzT8MGGEZ7qaWGA4 = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `uFlowId`='{$_obfuscate_TlvKhtsoOQÿÿ}'" );
            $_obfuscate_6RYLWQÿÿ['flowNumber'] = $_obfuscate_qzT8MGGEZ7qaWGA4['flowNumber'];
            $_obfuscate_6RYLWQÿÿ['flowName'] = $_obfuscate_qzT8MGGEZ7qaWGA4['flowName'];
            $_obfuscate_6RYLWQÿÿ['reason'] = $_obfuscate_qzT8MGGEZ7qaWGA4['reason'];
            $_obfuscate_6RYLWQÿÿ['level'] = $_obfuscate_qzT8MGGEZ7qaWGA4['level'];
            $_obfuscate_6RYLWQÿÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
            $_obfuscate_vzGArcjOKr8_7Aÿÿ = $this->_makeAttachAllow( $_obfuscate_F4AbnVRh );
            ( );
            $_obfuscate_2ggÿ = new fs( );
            $_obfuscate_qzT8MGGEZ7qaWGA4['attach'] = $this->getAttachList( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_qzT8MGGEZ7qaWGA4 );
            $_obfuscate_8CpDPPa = $_obfuscate_2ggÿ->getListInfo4wf( json_decode( $_obfuscate_qzT8MGGEZ7qaWGA4['attach'], TRUE ), $_obfuscate_vzGArcjOKr8_7Aÿÿ, TRUE, "new" );
            if ( $_obfuscate_XkuTFqZ6Tmkÿ == 0 && $_obfuscate_pEvU7Kz2Ywÿÿ == 0 )
            {
                $_obfuscate_urgydSw7IkMKIoqpAÿÿ = $this->api_getWfFieldData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ );
                $_obfuscate_sAnybXEÿ = $CNOA_DB->db_select( array( "id" ), $this->t_set_field, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' AND `otype` = 'detailtable' " );
                if ( is_array( $_obfuscate_sAnybXEÿ ) )
                {
                    $_obfuscate_sAnybXEÿ = array( );
                }
                if ( !empty( $_obfuscate_sAnybXEÿ ) )
                {
                    $_obfuscate_ScER1S69bt_Qmgÿÿ = array( );
                    foreach ( $_obfuscate_sAnybXEÿ as $_obfuscate_6Aÿÿ )
                    {
                        $_obfuscate_XNOBiCm6rWto5G4wFkJiVkUqo0ÿ[] = $this->api_getWfFieldDetailData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_6Aÿÿ['id'] );
                    }
                }
            }
        }
        else
        {
            $_obfuscate_6RYLWQÿÿ['flowNumber'] = $_obfuscate_d34ykzwUSke5REÿ['nameRule'];
            $_obfuscate_6RYLWQÿÿ['uFlowId'] = 0;
        }
        $_obfuscate_l5xoT48YaQÿÿ = getpar( $_GET, "childId", 0 );
        if ( !empty( $_obfuscate_l5xoT48YaQÿÿ ) )
        {
            $_obfuscate_jOcDpChC9wÿÿ = $CNOA_DB->db_getone( array( "puFlowId" ), $this->t_use_step_child_flow, "WHERE `id` = ".$_obfuscate_l5xoT48YaQÿÿ." AND `sharefile` = 1 " );
            if ( !empty( $_obfuscate_jOcDpChC9wÿÿ ) )
            {
                $_obfuscate_ViAjUrWq8zMÿ = $CNOA_DB->db_getone( array( "attach" ), $this->t_use_flow, "WHERE `uFlowId` = ".$_obfuscate_jOcDpChC9wÿÿ['puFlowId']." " );
                $_obfuscate_vzGArcjOKr8_7Aÿÿ = $this->_makeAttachAllow( $_obfuscate_F4AbnVRh );
                ( );
                $_obfuscate_2ggÿ = new fs( );
                $_obfuscate_8CpDPPa = $_obfuscate_2ggÿ->getListInfo4wf( json_decode( $_obfuscate_ViAjUrWq8zMÿ['attach'], TRUE ), $_obfuscate_vzGArcjOKr8_7Aÿÿ, TRUE, "new" );
            }
        }
        $_obfuscate_CgmidHjdgZGsBgÿÿ = getpar( $_GET, "otherApp", 0 );
        if ( !empty( $_obfuscate_CgmidHjdgZGsBgÿÿ ) )
        {
            $_obfuscate_WPvkSFEMgÿÿ = $CNOA_DB->db_getfield( "attach", "odoc_data", "WHERE `otherAppId`='".$_obfuscate_CgmidHjdgZGsBgÿÿ."'" );
            if ( !empty( $_obfuscate_WPvkSFEMgÿÿ ) )
            {
                $_obfuscate_vzGArcjOKr8_7Aÿÿ = $this->_makeAttachAllow( $_obfuscate_F4AbnVRh );
                ( );
                $_obfuscate_2ggÿ = new fs( );
                $_obfuscate_8CpDPPa = $_obfuscate_2ggÿ->getListInfo4wf( json_decode( $_obfuscate_WPvkSFEMgÿÿ, TRUE ), $_obfuscate_vzGArcjOKr8_7Aÿÿ, TRUE, "new" );
            }
        }
        $_obfuscate_QSpsVAzJ = array( );
        $_obfuscate_5NhzjnJq_f8ÿ = $CNOA_DB->db_getone( "*", $this->t_set_step, "WHERE `stepType`=1 AND `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $_obfuscate_QSpsVAzJ['allowAttachAdd'] = $_obfuscate_5NhzjnJq_f8ÿ['allowAttachAdd'];
        $_obfuscate_QSpsVAzJ['allowSms'] = $_obfuscate_5NhzjnJq_f8ÿ['allowSms'];
        $_obfuscate_QSpsVAzJ['allowEditFlowNumber'] = $_obfuscate_d34ykzwUSke5REÿ['nameRuleAllowEdit'];
        $_obfuscate_QSpsVAzJ['nameDisallowBlank'] = $_obfuscate_d34ykzwUSke5REÿ['nameDisallowBlank'];
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        $_obfuscate_NlQÿ->attach = $_obfuscate_8CpDPPa;
        if ( $_obfuscate_XkuTFqZ6Tmkÿ == 0 && $_obfuscate_pEvU7Kz2Ywÿÿ == 0 )
        {
            $_obfuscate_NlQÿ->wf_field_data = $_obfuscate_urgydSw7IkMKIoqpAÿÿ;
            $_obfuscate_NlQÿ->wf_detail_field_data = $_obfuscate_XNOBiCm6rWto5G4wFkJiVkUqo0ÿ;
        }
        $_obfuscate_NlQÿ->config = $_obfuscate_QSpsVAzJ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _loadFlowDesignData( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = intval( getpar( $_POST, "flowId" ) );
        $_obfuscate_TlvKhtsoOQÿÿ = intval( getpar( $_POST, "uFlowId" ) );
        if ( $_obfuscate_TlvKhtsoOQÿÿ !== 0 )
        {
            ( $_obfuscate_TlvKhtsoOQÿÿ );
            $_obfuscate_e53ODz04JQÿÿ = new wfCache( );
            $_obfuscate_dw4x = $_obfuscate_e53ODz04JQÿÿ->getFlowXML( );
            $_obfuscate_T1JGvNjMhVsÿ = array( );
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
            if ( !is_array( $_obfuscate_mPAjEGLn ) )
            {
                $_obfuscate_mPAjEGLn = array( );
            }
            $_obfuscate__eqrEQÿÿ = array( );
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_0Ul8BBkt = $_obfuscate_6Aÿÿ['uStepId'];
                if ( isset( $_obfuscate_T1JGvNjMhVsÿ[$_obfuscate_0Ul8BBkt] ) )
                {
                    if ( $_obfuscate_T1JGvNjMhVsÿ[$_obfuscate_0Ul8BBkt]['stime'] < $_obfuscate_6Aÿÿ['stime'] )
                    {
                        $_obfuscate_T1JGvNjMhVsÿ[$_obfuscate_0Ul8BBkt] = $_obfuscate_6Aÿÿ;
                    }
                }
                else
                {
                    $_obfuscate_T1JGvNjMhVsÿ[$_obfuscate_0Ul8BBkt] = $_obfuscate_6Aÿÿ;
                }
                if ( $_obfuscate_6Aÿÿ['uid'] )
                {
                    $_obfuscate__eqrEQÿÿ[] = $_obfuscate_6Aÿÿ['uid'];
                }
                if ( $_obfuscate_6Aÿÿ['proxyUid'] )
                {
                    $_obfuscate__eqrEQÿÿ[] = $_obfuscate_6Aÿÿ['proxyUid'];
                }
                if ( $_obfuscate_6Aÿÿ['dealUid'] )
                {
                    $_obfuscate__eqrEQÿÿ[] = $_obfuscate_6Aÿÿ['dealUid'];
                }
            }
            unset( $_obfuscate_mPAjEGLn );
            if ( !empty( $_obfuscate_T1JGvNjMhVsÿ ) )
            {
                $_obfuscate__eqrEQÿÿ = array_unique( $_obfuscate__eqrEQÿÿ );
                $_obfuscate__Wi6396IheAÿ = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate__eqrEQÿÿ );
                $_obfuscate_V2OgJ71KLjey = simplexml_load_string( $_obfuscate_dw4x );
                foreach ( $_obfuscate_V2OgJ71KLjey->root->mxCell as $_obfuscate_7kIMZwÿÿ )
                {
                    $_obfuscate_IyicAyAÿ = $_obfuscate_7kIMZwÿÿ->attributes( );
                    if ( intval( $_obfuscate_IyicAyAÿ['edge'] ) == 1 )
                    {
                        $_obfuscate_UqWgv15HzZ3SbAÿÿ = $_obfuscate_T1JGvNjMhVsÿ[intval( $_obfuscate_IyicAyAÿ->target )];
                        if ( !isset( $_obfuscate_UqWgv15HzZ3SbAÿÿ ) && !( $_obfuscate_UqWgv15HzZ3SbAÿÿ['status'] != 0 ) && !( $_obfuscate_UqWgv15HzZ3SbAÿÿ['uid'] != 0 ) || !( $_obfuscate_UqWgv15HzZ3SbAÿÿ['proxyUid'] != 0 ) )
                        {
                            $_obfuscate_IyicAyAÿ->addAttribute( "done", 1 );
                        }
                    }
                    else
                    {
                        $_obfuscate_0Ul8BBkt = ( integer )$_obfuscate_IyicAyAÿ->id;
                        $_obfuscate_VBCv7Qÿÿ = $_obfuscate_T1JGvNjMhVsÿ[$_obfuscate_0Ul8BBkt];
                        if ( !isset( $_obfuscate_VBCv7Qÿÿ ) )
                        {
                        }
                        else if ( $_obfuscate_VBCv7Qÿÿ['stepType'] == 5 && $_obfuscate_VBCv7Qÿÿ['status'] == 0 )
                        {
                            $_obfuscate_Jrp1 = $CNOA_DB->db_select( array( "uid", "proxyUid", "dealUid" ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId`={$_obfuscate_VBCv7Qÿÿ['uStepId']}" );
                            if ( !is_array( $_obfuscate_Jrp1 ) )
                            {
                                $_obfuscate_Jrp1 = array( );
                            }
                            $_obfuscate_rFR1zydggÿÿ = $CNOA_DB->db_getfield( "deal", $this->t_use_deal_way, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND `stepId`={$_obfuscate_VBCv7Qÿÿ['uStepId']}" );
                            $_obfuscate_Gnm2iOeObk8ÿ = "";
                            if ( $_obfuscate_rFR1zydggÿÿ == 1 )
                            {
                                foreach ( $_obfuscate_Jrp1 as $_obfuscate_fh2Upgÿÿ )
                                {
                                    if ( $_obfuscate_fh2Upgÿÿ['uid'] != 0 )
                                    {
                                        $_obfuscate_Gnm2iOeObk8ÿ .= "\r\n".$_obfuscate__Wi6396IheAÿ[$_obfuscate_fh2Upgÿÿ['uid']]['truename'];
                                        if ( $_obfuscate_fh2Upgÿÿ['dealUid'] == $_obfuscate_fh2Upgÿÿ['uid'] || $_obfuscate_fh2Upgÿÿ['dealUid'] == 0 && $_obfuscate_fh2Upgÿÿ['proxyUid'] == 0 )
                                        {
                                            $_obfuscate_Gnm2iOeObk8ÿ .= "[â˜º]";
                                        }
                                    }
                                    if ( $_obfuscate_fh2Upgÿÿ['proxyUid'] != 0 )
                                    {
                                        $_obfuscate_Gnm2iOeObk8ÿ .= " â†’ ".$_obfuscate__Wi6396IheAÿ[$_obfuscate_fh2Upgÿÿ['proxyUid']]['truename'];
                                        if ( $_obfuscate_fh2Upgÿÿ['dealUid'] == $_obfuscate_fh2Upgÿÿ['proxyUid'] )
                                        {
                                            $_obfuscate_Gnm2iOeObk8ÿ .= "[â˜º]";
                                        }
                                    }
                                }
                            }
                            else
                            {
                                if ( $_obfuscate_VBCv7Qÿÿ['uid'] != 0 )
                                {
                                    $_obfuscate_Gnm2iOeObk8ÿ .= "\r\n".$_obfuscate__Wi6396IheAÿ[$_obfuscate_VBCv7Qÿÿ['uid']]['truename'];
                                    if ( $_obfuscate_VBCv7Qÿÿ['dealUid'] == $_obfuscate_VBCv7Qÿÿ['uid'] || $_obfuscate_VBCv7Qÿÿ['dealUid'] == 0 && $_obfuscate_VBCv7Qÿÿ['proxyUid'] == 0 )
                                    {
                                        $_obfuscate_Gnm2iOeObk8ÿ .= "[â˜º]";
                                    }
                                }
                                if ( $_obfuscate_VBCv7Qÿÿ['proxyUid'] != 0 )
                                {
                                    $_obfuscate_Gnm2iOeObk8ÿ .= " â†’ ".$_obfuscate__Wi6396IheAÿ[$_obfuscate_VBCv7Qÿÿ['proxyUid']]['truename'];
                                    if ( $_obfuscate_VBCv7Qÿÿ['dealUid'] == $_obfuscate_VBCv7Qÿÿ['proxyUid'] )
                                    {
                                        $_obfuscate_Gnm2iOeObk8ÿ .= "[â˜º]";
                                    }
                                }
                            }
                            $_obfuscate_IyicAyAÿ->value = strval( $_obfuscate_IyicAyAÿ->value ).$_obfuscate_Gnm2iOeObk8ÿ;
                            if ( $_obfuscate_VBCv7Qÿÿ['uid'] == 0 )
                            {
                                $_obfuscate_IyicAyAÿ->addAttribute( "nodoing", 1 );
                            }
                            else if ( $_obfuscate_VBCv7Qÿÿ['status'] == 1 )
                            {
                                $_obfuscate_IyicAyAÿ->addAttribute( "doing", 1 );
                            }
                            else if ( $_obfuscate_VBCv7Qÿÿ['status'] == 2 && $_obfuscate_VBCv7Qÿÿ['dealUid'] == 0 && $_obfuscate_VBCv7Qÿÿ['stepType'] != 1 )
                            {
                                $_obfuscate_IyicAyAÿ->addAttribute( "nodo", 1 );
                            }
                            else
                            {
                                $_obfuscate_IyicAyAÿ->addAttribute( "done", 1 );
                            }
                        }
                    }
                }
                $_obfuscate_dw4x = $_obfuscate_V2OgJ71KLjey->asXML( );
            }
        }
        else
        {
            $_obfuscate_dw4x = $CNOA_DB->db_getfield( "flowXml", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
            $_obfuscate_V2OgJ71KLjey = simplexml_load_string( $_obfuscate_dw4x );
            foreach ( $_obfuscate_V2OgJ71KLjey->root->mxCell as $_obfuscate_7kIMZwÿÿ )
            {
                $_obfuscate_IyicAyAÿ = $_obfuscate_7kIMZwÿÿ->attributes( );
                if ( intval( $_obfuscate_IyicAyAÿ->id ) < 2 || !in_array( strval( $_obfuscate_IyicAyAÿ->nodeType ), array( "edge", "node", "startNode", "endNode", "cNode", "bNode", "bcNode" ) ) )
                {
                    $_obfuscate_IyicAyAÿ->addAttribute( "newnode", 1 );
                }
            }
            $_obfuscate_dw4x = $_obfuscate_V2OgJ71KLjey->asXML( );
        }
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_6RYLWQÿÿ['flowXml'] = $_obfuscate_dw4x;
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _addFavFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->t_set_flow, "WHERE `flowType` = 0 AND `flowId` = ".$_obfuscate_F4AbnVRh." " );
        if ( $_obfuscate_Ybai != 0 && !$this->__isFlowNewPermit( $_obfuscate_F4AbnVRh ) )
        {
            msg::callback( FALSE, lang( "youNoUseThisFlowPermit" ).$_obfuscate_F4AbnVRh );
        }
        $_obfuscate_o5fQ1gÿÿ = $CNOA_DB->db_getone( "*", $this->t_u_wffav, "WHERE `uid`='".$_obfuscate_7Ri3."' AND `flowId`='{$_obfuscate_F4AbnVRh}'" );
        if ( $_obfuscate_o5fQ1gÿÿ !== FALSE )
        {
            $CNOA_DB->db_delete( $this->t_u_wffav, "WHERE `uid`='".$_obfuscate_7Ri3."' AND `flowId`='{$_obfuscate_F4AbnVRh}'" );
            msg::callback( TRUE, lang( "cancelStar" ) );
        }
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_6RYLWQÿÿ['flowId'] = $_obfuscate_F4AbnVRh;
        $_obfuscate_6RYLWQÿÿ['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_6RYLWQÿÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_6RYLWQÿÿ['status'] = 1;
        $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_u_wffav );
        msg::callback( TRUE, lang( "hasStar" ) );
    }

    private function _getSortPermitForNew( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_iuzS = $CNOA_SESSION->get( "DID" );
        $_obfuscate_y6jH = $CNOA_SESSION->get( "SID" );
        $_obfuscate_fMfsswÿÿ = $CNOA_DB->db_select( "*", $this->t_set_step_user, "WHERE `firstStep`=1" );
        if ( !is_array( $_obfuscate_fMfsswÿÿ ) )
        {
            $_obfuscate_fMfsswÿÿ = array( );
        }
        $_obfuscate_4kUQwYUyFDNukgQvGdIÿ = $CNOA_DB->db_select( array( "flowId" ), $this->t_set_step_user, "WHERE `firstStep`=1 AND `exclude` = ".$_obfuscate_7Ri3 );
        if ( !is_array( $_obfuscate_4kUQwYUyFDNukgQvGdIÿ ) )
        {
            $_obfuscate_4kUQwYUyFDNukgQvGdIÿ = array( );
        }
        $_obfuscate_VZUqlJW7N20ÿ = array( );
        foreach ( $_obfuscate_4kUQwYUyFDNukgQvGdIÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_VZUqlJW7N20ÿ[] = $_obfuscate_VgKtFegÿ['flowId'];
        }
        if ( !is_array( $_obfuscate_fMfsswÿÿ ) )
        {
            $_obfuscate_fMfsswÿÿ = array( );
        }
        $_obfuscate_8Bnz38wN01cÿ = array( );
        foreach ( $_obfuscate_fMfsswÿÿ as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_6Aÿÿ['flowId']][] = $_obfuscate_6Aÿÿ;
        }
        $_obfuscate_uWfP0Bouwÿÿ = array( 0 );
        foreach ( $_obfuscate_8Bnz38wN01cÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_xv5Nfm7aigkÿ = FALSE;
            if ( count( $_obfuscate_6Aÿÿ ) == 1 && $_obfuscate_6Aÿÿ[0]['type'] == "" )
            {
                $_obfuscate_xv5Nfm7aigkÿ = TRUE;
            }
            else if ( 0 < count( $_obfuscate_6Aÿÿ ) )
            {
                foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_snMÿ )
                {
                    if ( $_obfuscate_snMÿ['type'] == "people" )
                    {
                        if ( $_obfuscate_snMÿ['people'] == $_obfuscate_7Ri3 )
                        {
                            $_obfuscate_xv5Nfm7aigkÿ = TRUE;
                        }
                    }
                    else if ( $_obfuscate_snMÿ['type'] == "dept" )
                    {
                        if ( $_obfuscate_snMÿ['dept'] == $_obfuscate_iuzS )
                        {
                            $_obfuscate_xv5Nfm7aigkÿ = TRUE;
                        }
                    }
                    else if ( $_obfuscate_snMÿ['type'] == "station" )
                    {
                        if ( $_obfuscate_snMÿ['station'] == $_obfuscate_y6jH )
                        {
                            $_obfuscate_xv5Nfm7aigkÿ = TRUE;
                        }
                    }
                    else if ( !( $_obfuscate_snMÿ['type'] == "deptstation" ) && !( $_obfuscate_snMÿ['dept'] == $_obfuscate_iuzS ) && !( $_obfuscate_snMÿ['station'] == $_obfuscate_y6jH ) )
                    {
                        $_obfuscate_xv5Nfm7aigkÿ = TRUE;
                    }
                }
            }
            if ( $_obfuscate_xv5Nfm7aigkÿ )
            {
                $_obfuscate_uWfP0Bouwÿÿ[] = $_obfuscate_5wÿÿ;
            }
        }
        foreach ( $_obfuscate_uWfP0Bouwÿÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
        {
            if ( !empty( $_obfuscate_VZUqlJW7N20ÿ ) )
            {
                foreach ( $_obfuscate_VZUqlJW7N20ÿ as $_obfuscate_6Aÿÿ )
                {
                    if ( $_obfuscate_6Aÿÿ == $_obfuscate_VgKtFegÿ )
                    {
                        unset( $_obfuscate_uWfP0Bouwÿÿ[$_obfuscate_Vwty] );
                    }
                }
            }
        }
        $_obfuscate_rCz1avpqUCVhbQÿÿ = $CNOA_DB->db_select( array( "flowId" ), $this->t_set_flow, "WHERE `flowType`!=0" );
        if ( !is_array( $_obfuscate_rCz1avpqUCVhbQÿÿ ) )
        {
            $_obfuscate_rCz1avpqUCVhbQÿÿ = array( );
        }
        foreach ( $_obfuscate_rCz1avpqUCVhbQÿÿ as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_uWfP0Bouwÿÿ[] = $_obfuscate_6Aÿÿ['flowId'];
        }
        return $_obfuscate_uWfP0Bouwÿÿ;
    }

    private function __isFlowNewPermit( $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_iuzS = $CNOA_SESSION->get( "DID" );
        $_obfuscate_y6jH = $CNOA_SESSION->get( "SID" );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_ycwÿ = $CNOA_DB->db_select( "*", $this->t_set_step_user, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `firstStep`=1" );
        if ( !is_array( $_obfuscate_ycwÿ ) )
        {
            $_obfuscate_ycwÿ = array( );
        }
        $_obfuscate_4kUQwYUyFDNukgQvGdIÿ = $CNOA_DB->db_select( array( "flowId" ), $this->t_set_step_user, "WHERE `firstStep`=1 AND `exclude` = ".$_obfuscate_7Ri3 );
        if ( !is_array( $_obfuscate_4kUQwYUyFDNukgQvGdIÿ ) )
        {
            $_obfuscate_4kUQwYUyFDNukgQvGdIÿ = array( );
        }
        $_obfuscate_uWfP0Bouwÿÿ = array( );
        foreach ( $_obfuscate_4kUQwYUyFDNukgQvGdIÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_uWfP0Bouwÿÿ[] = $_obfuscate_VgKtFegÿ['flowId'];
        }
        $_obfuscate_xv5Nfm7aigkÿ = FALSE;
        if ( count( $_obfuscate_ycwÿ ) == 1 && $_obfuscate_ycwÿ[0]['type'] == "" )
        {
            $_obfuscate_xv5Nfm7aigkÿ = TRUE;
        }
        else
        {
            $_obfuscate_JDTeROs = FALSE;
            foreach ( $_obfuscate_ycwÿ as $_obfuscate_snMÿ )
            {
                if ( $_obfuscate_snMÿ['type'] == "people" )
                {
                    if ( $_obfuscate_snMÿ['people'] == $_obfuscate_7Ri3 )
                    {
                        $_obfuscate_xv5Nfm7aigkÿ = TRUE;
                    }
                }
                else if ( $_obfuscate_snMÿ['type'] == "dept" )
                {
                    if ( $_obfuscate_snMÿ['dept'] == $_obfuscate_iuzS )
                    {
                        $_obfuscate_xv5Nfm7aigkÿ = TRUE;
                    }
                }
                else if ( $_obfuscate_snMÿ['type'] == "station" )
                {
                    if ( $_obfuscate_snMÿ['station'] == $_obfuscate_y6jH )
                    {
                        $_obfuscate_xv5Nfm7aigkÿ = TRUE;
                    }
                }
                else if ( !( $_obfuscate_snMÿ['type'] == "deptstation" ) && !( $_obfuscate_snMÿ['dept'] == $_obfuscate_iuzS ) && !( $_obfuscate_snMÿ['station'] == $_obfuscate_y6jH ) )
                {
                    $_obfuscate_xv5Nfm7aigkÿ = TRUE;
                }
            }
        }
        if ( in_array( $_obfuscate_F4AbnVRh, $_obfuscate_uWfP0Bouwÿÿ ) )
        {
            return FALSE;
        }
        return $_obfuscate_xv5Nfm7aigkÿ;
    }

    private function _savePosition( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_6RYLWQÿÿ = json_decode( $_POST['data'], TRUE );
        $GLOBALS['dataSafeScan']->stopAttackFromArray( $_obfuscate_6RYLWQÿÿ );
        if ( is_array( $_obfuscate_6RYLWQÿÿ ) )
        {
            $CNOA_DB->db_delete( $this->t_u_setting, "WHERE `uid`='".$_obfuscate_7Ri3."'" );
            foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                if ( is_array( $_obfuscate_6Aÿÿ ) )
                {
                    foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_ty0ÿ => $_obfuscate_snMÿ )
                    {
                        $Tc = array( );
                        $Tc['uid'] = $_obfuscate_7Ri3;
                        $Tc['sortId'] = str_replace( "flowColumnItem", "", $_obfuscate_snMÿ );
                        $Tc['column'] = $_obfuscate_5wÿÿ;
                        $Tc['position'] = $_obfuscate_ty0ÿ;
                        $CNOA_DB->db_insert( $Tc, $this->t_u_setting );
                    }
                }
            }
        }
    }

    private function _getSignature( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_IRFhnYwÿ = "WHERE `uid`=".$_obfuscate_7Ri3;
        $_obfuscate_6RYLWQÿÿ = $CNOA_DB->db_select( array( "signature", "url", "isUsePwd" ), "system_signature", $_obfuscate_IRFhnYwÿ );
        if ( !is_array( $_obfuscate_6RYLWQÿÿ ) )
        {
            $_obfuscate_6RYLWQÿÿ = array( );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_6RYLWQÿÿ;
        $_obfuscate_SUjPN94Er7yI->total = $CNOA_DB->db_sum( "id", "system_signature", $_obfuscate_IRFhnYwÿ );
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _ms_loadTemplateFile( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = intval( getpar( $_GET, "flowId", 0 ) );
        $_obfuscate_TlvKhtsoOQÿÿ = intval( getpar( $_GET, "uFlowId", 0 ) );
        $_obfuscate_XkuTFqZ6Tmkÿ = intval( getpar( $_GET, "flowType", 0 ) );
        $_obfuscate_pEvU7Kz2Ywÿÿ = intval( getpar( $_GET, "tplSort", 0 ) );
        $_obfuscate_jTbXTguM6pC9CAÿÿ = getpar( $_GET, "editAction", "add" );
        $this->api_loadTemplateFile( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_XkuTFqZ6Tmkÿ, $_obfuscate_pEvU7Kz2Ywÿÿ, $_obfuscate_jTbXTguM6pC9CAÿÿ );
        exit( );
    }

    private function _getBusinessData( )
    {
        $_obfuscate_olwD8Qÿÿ = getpar( $_GET, "bindfunc", "" );
        $_obfuscate_pYzeLf4ÿ = getpar( $_GET, "level", "" );
        if ( empty( $_obfuscate_olwD8Qÿÿ ) )
        {
            return "";
        }
        $_obfuscate_wZ6MPP0ÿ = "wfEngine".ucfirst( $_obfuscate_olwD8Qÿÿ );
        if ( !class_exists( $_obfuscate_wZ6MPP0ÿ ) )
        {
            return;
        }
        ( );
        $_obfuscate_7eRCEirmYGaYE1FJCfc0hwÿÿ = new $_obfuscate_wZ6MPP0ÿ( );
        return $_obfuscate_7eRCEirmYGaYE1FJCfc0hwÿÿ->getBusinessData( $_obfuscate_pYzeLf4ÿ );
    }

    private function __checkDraft( $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `uid`='{$_obfuscate_7Ri3}' AND `status`=0 ORDER BY `uFlowId` DESC" );
        if ( $_obfuscate_7qDAYo85aGAÿ !== FALSE )
        {
            return array(
                "uFlowId" => $_obfuscate_7qDAYo85aGAÿ['uFlowId'],
                "savedTime" => formatdate( $_obfuscate_7qDAYo85aGAÿ['edittime'], "Yå¹´mæœˆdæ—¥ Hæ—¶iåˆ†sç§’" )
            );
        }
        return array( "uFlowId" => "0", "savedTime" => "0" );
    }

    private function _delSalaryDraft( )
    {
        global $CNOA_DB;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId" );
        $_obfuscate_gftfagwÿ = $CNOA_DB->db_getcount( $this->t_use_flow, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ );
        if ( 0 < $_obfuscate_gftfagwÿ )
        {
            $CNOA_DB->db_delete( $this->t_use_flow, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ );
        }
    }

    private function _bindingProcess( )
    {
        global $CNOA_DB;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId" );
        $_obfuscate_0muy1AMpeuH = getpar( $_POST, "noticeLid" );
        $_obfuscate_qXF5WAWqYSIÿ = getpar( $_POST, "salaryApproveId" );
        if ( $_obfuscate_0muy1AMpeuH )
        {
            $_obfuscate_3tiDsnMÿ = "news_notice_list";
            $CNOA_DB->db_update( array(
                "uFlowId" => $_obfuscate_TlvKhtsoOQÿÿ
            ), $_obfuscate_3tiDsnMÿ, "WHERE `id` = ".$_obfuscate_0muy1AMpeuH );
        }
    }

    public function api_makeDetailMaxCache( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_piwqe2DnH9mIPU0P )
    {
        $this->_makeDetailMaxCache( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_piwqe2DnH9mIPU0P );
    }

    public function api_getSendNextData( )
    {
        $this->_getSendNextData( );
    }

}

?>
