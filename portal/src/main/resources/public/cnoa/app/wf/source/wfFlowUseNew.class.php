<?php

class wfFlowUseNew extends wfCommon
{

    public function run( )
    {
        $_obfuscate_M_5JJw�� = getpar( $_GET, "task", "loadPage" );
        switch ( $_obfuscate_M_5JJw�� )
        {
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "savePosition" :
            $this->_savePosition( );
            break;
        case "getJsonData" :
            $_obfuscate_vholQ�� = getpar( $_POST, "from", "normal" );
            if ( $_obfuscate_vholQ�� == "need" )
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
            $_obfuscate_phKp89pDgwQ� = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $_obfuscate_phKp89pDgwQ� );
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
            $_obfuscate_TlvKhtsoOQ�� = getpar( $_GET, "uFlowId", 0 );
            $_obfuscate_pEvU7Kz2Yw�� = getpar( $_GET, "tplSort", 0 );
            $this->api_saveTemplateData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_pEvU7Kz2Yw�� );
            exit( );
        case "getSignature" :
            $this->_getSignature( );
            break;
        case "getDsFields" :
            $_obfuscate_oWs9ywU� = getpar( $_GET, "dsTag" );
            ( );
            $_obfuscate_NlQ� = new wfDatasource( );
            $_obfuscate_tjILu7ZH = $_obfuscate_NlQ�->getSourceFields( $_obfuscate_oWs9ywU�, FALSE );
            echo json_encode( $_obfuscate_tjILu7ZH );
            break;
        case "getDsData" :
            $_obfuscate_oWs9ywU� = getpar( $_GET, "dsTag" );
            ( );
            $_obfuscate_NlQ� = new wfDatasource( );
            $_obfuscate_6RYLWQ�� = $_obfuscate_NlQ�->getDsData( $_obfuscate_oWs9ywU� );
            echo $_obfuscate_6RYLWQ��;
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
        $_obfuscate_vholQ�� = getpar( $_GET, "from" );
        if ( $_obfuscate_vholQ�� == "list" )
        {
            $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/list.htm";
            if ( getpar( $_GET, "sn" ) == "29462005873682712611" )
            {
                $_obfuscate_BxoH_SjRHQ�� = CNOA_PATH_FILE."/common/custom/29462005873682712611/new_list.htm";
            }
        }
        else if ( $_obfuscate_vholQ�� == "newflow" )
        {
            $_obfuscate_F4AbnVRh = ( integer )getpar( $_GET, "flowId" );
            if ( $_obfuscate_F4AbnVRh == 0 )
            {
                msg::showerror( lang( "noChoiceFlow" ) );
            }
            $_obfuscate_7qDAYo85aGA� = $CNOA_DB->db_getone( array( "flowType", "tplSort" ), $this->t_set_flow, "WHERE flowId=".$_obfuscate_F4AbnVRh );
            $GLOBALS['GLOBALS']['app']['flowId'] = $_obfuscate_F4AbnVRh;
            $GLOBALS['GLOBALS']['app']['uFlowId'] = 0;
            $GLOBALS['GLOBALS']['app']['action'] = "add";
            $GLOBALS['GLOBALS']['app']['flowType'] = ( integer )$_obfuscate_7qDAYo85aGA�['flowType'];
            $GLOBALS['GLOBALS']['app']['tplSort'] = ( integer )$_obfuscate_7qDAYo85aGA�['tplSort'];
            $GLOBALS['GLOBALS']['app']['childId'] = getpar( $_GET, "childId", 0 );
            $GLOBALS['GLOBALS']['app']['otherApp'] = getpar( $_GET, "otherApp", 0 );
            $GLOBALS['GLOBALS']['app']['puFlowId'] = getpar( $_GET, "puFlowId", 0 );
            $GLOBALS['GLOBALS']['app']['cid'] = getpar( $_GET, "cid", 0 );
            $GLOBALS['GLOBALS']['app']['pid'] = getpar( $_GET, "pid", 0 );
            $GLOBALS['GLOBALS']['app']['againId'] = getpar( $_GET, "againId", 0 );
            $_obfuscate_jOcDpChC9w�� = $CNOA_DB->db_getone( array( "status", "uFlowId" ), $this->t_use_step_child_flow, "WHERE `id`=".$GLOBALS['app']['childId']." " );
            if ( $_obfuscate_jOcDpChC9w��['status'] == 2 || $_obfuscate_jOcDpChC9w��['status'] == 3 )
            {
                $GLOBALS['GLOBALS']['app']['uFlowId'] = $_obfuscate_jOcDpChC9w��['uFlowId'];
                $GLOBALS['GLOBALS']['app']['action'] = "edit";
                $GLOBALS['GLOBALS']['app']['step'] = ( integer )getpar( $_GET, "step", 0 );
                $GLOBALS['GLOBALS']['app']['flowId'] = $_obfuscate_F4AbnVRh;
                $GLOBALS['GLOBALS']['app']['wf']['tplSort'] = ( integer )$_obfuscate_7qDAYo85aGA�['tplSort'];
                $GLOBALS['GLOBALS']['app']['wf']['flowType'] = ( integer )$_obfuscate_7qDAYo85aGA�['flowType'];
                $GLOBALS['GLOBALS']['app']['wf']['type'] = "show";
                $GLOBALS['GLOBALS']['app']['status'] = $CNOA_DB->db_getfield( "status", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_jOcDpChC9w��['uFlowId']."'" );
                $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/showflow.htm";
            }
            else
            {
                $_obfuscate_VI9iDYjvhuCK9tEBZq8� = $this->__checkDraft( $_obfuscate_F4AbnVRh );
                $GLOBALS['GLOBALS']['saveduFlowId'] = $_obfuscate_VI9iDYjvhuCK9tEBZq8�['uFlowId'];
                $GLOBALS['GLOBALS']['saveduFlowTime'] = $_obfuscate_VI9iDYjvhuCK9tEBZq8�['savedTime'];
                $GLOBALS['GLOBALS']['app']['userCid'] = ( integer )getpar( $_GET, "cid", 0 );
                $_obfuscate_tbbmYWlqU7jyQ�� = $this->__isFlowNewPermit( $GLOBALS['app']['flowId'] );
                $GLOBALS['GLOBALS']['havePermit'] = $_obfuscate_tbbmYWlqU7jyQ�� ? 1 : 0;
                $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/new.htm";
            }
        }
        else if ( $_obfuscate_vholQ�� == "editflow" )
        {
            $_obfuscate_TlvKhtsoOQ�� = ( integer )getpar( $_GET, "uFlowId" );
            if ( $_obfuscate_TlvKhtsoOQ�� == 0 )
            {
                msg::showerror( lang( "noChoiceFlow" ) );
            }
            $_obfuscate_3y0Y = "SELECT u.flowId, u.otherApp, s.tplSort, s.flowType FROM ".tname( $this->t_use_flow )." AS u  LEFT JOIN ".tname( $this->t_set_flow )." AS s on u.flowId = s.flowId  WHERE uFlowId = ".$_obfuscate_TlvKhtsoOQ��;
            $_obfuscate_7qDAYo85aGA� = $CNOA_DB->get_one( $_obfuscate_3y0Y );
            $GLOBALS['GLOBALS']['app']['flowType'] = ( integer )$_obfuscate_7qDAYo85aGA�['flowType'];
            $GLOBALS['GLOBALS']['app']['tplSort'] = ( integer )$_obfuscate_7qDAYo85aGA�['tplSort'];
            $GLOBALS['GLOBALS']['app']['flowId'] = ( integer )$_obfuscate_7qDAYo85aGA�['flowId'];
            $GLOBALS['GLOBALS']['app']['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
            $GLOBALS['GLOBALS']['app']['action'] = "edit";
            $GLOBALS['GLOBALS']['app']['otherApp'] = $_obfuscate_7qDAYo85aGA�['otherApp'];
            $GLOBALS['GLOBALS']['app']['salaryApproveId'] = ( integer )getpar( $_GET, "salaryApproveId" );
            $GLOBALS['GLOBALS']['app']['noticeLid'] = ( integer )getpar( $_GET, "noticeLid" );
            $GLOBALS['GLOBALS']['havePermit'] = 1;
            if ( $_obfuscate_7qDAYo85aGA�['flowType'] == 0 )
            {
                if ( $_obfuscate_7qDAYo85aGA�['tplSort'] == 0 )
                {
                    $_obfuscate_tbbmYWlqU7jyQ�� = $this->__isFlowNewPermit( $_obfuscate_7qDAYo85aGA�['flowId'] );
                    $GLOBALS['GLOBALS']['havePermit'] = $_obfuscate_tbbmYWlqU7jyQ�� ? 1 : 0;
                }
                $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/new.htm";
            }
            else
            {
                $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/newfree_flowdesign.htm";
            }
        }
        $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQ�� );
        exit( );
    }

    public function _getSortList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_a49UK2tYTQ�� = app::loadapp( "wf", "flowSetSort" )->api_getSortTree( "faqi", TRUE );
        $_obfuscate_3UMpmYP6_RXGpYhmfE1rw�� = $CNOA_DB->db_select( "*", $this->t_u_setting, "WHERE `uid`='".$_obfuscate_7Ri3."'" );
        if ( !is_array( $_obfuscate_3UMpmYP6_RXGpYhmfE1rw�� ) )
        {
            $_obfuscate_3UMpmYP6_RXGpYhmfE1rw�� = array( );
        }
        $_obfuscate_DlN3cp0_V5jHMzgfnmTg�� = array( );
        foreach ( $_obfuscate_3UMpmYP6_RXGpYhmfE1rw�� as $_obfuscate_6A�� )
        {
            $_obfuscate_DlN3cp0_V5jHMzgfnmTg��[$_obfuscate_6A��['sortId']] = array(
                $_obfuscate_6A��['column'],
                $_obfuscate_6A��['position']
            );
        }
        $_obfuscate_OyHxCdsZEH5bk_wL = array( );
        foreach ( $_obfuscate_a49UK2tYTQ�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_6A��['column'] = $_obfuscate_3UMpmYP6_RXGpYhmfE1rw�� ? intval( $_obfuscate_DlN3cp0_V5jHMzgfnmTg��[$_obfuscate_6A��['sortId']][0] ) : -1;
            $_obfuscate_6A��['position'] = intval( $_obfuscate_DlN3cp0_V5jHMzgfnmTg��[$_obfuscate_6A��['sortId']][1] );
            $_obfuscate_OyHxCdsZEH5bk_wL[$_obfuscate_6A��['position']][] = $_obfuscate_6A��;
        }
        ksort( &$_obfuscate_OyHxCdsZEH5bk_wL );
        $_obfuscate_a49UK2tYTQ�� = array( );
        foreach ( $_obfuscate_OyHxCdsZEH5bk_wL as $_obfuscate_6A�� )
        {
            if ( is_array( $_obfuscate_6A�� ) )
            {
                foreach ( $_obfuscate_6A�� as $_obfuscate_snM� )
                {
                    $_obfuscate_a49UK2tYTQ��[] = $_obfuscate_snM�;
                }
            }
        }
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_a49UK2tYTQ��;
        $_obfuscate_NlQ�->childTotal = $CNOA_DB->db_getcount( $this->t_use_step_child_flow, "WHERE `faqiUid` = ".$_obfuscate_7Ri3." AND `status` = 1 " );
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    public function api_getSortList( $_obfuscate_vholQ��, $_obfuscate_v1E5GY8�, $_obfuscate_AmrqwIM� = TRUE )
    {
        $_obfuscate_a49UK2tYTQ�� = app::loadapp( "wf", "flowSetSort" )->api_getSortTree( $_obfuscate_vholQ��, TRUE );
        $_obfuscate_OQ�� = array( );
        $_obfuscate_OQ��['text'] = "流程分类列表";
        $_obfuscate_OQ��['iconCls'] = "icon-tree-root-cnoa";
        $_obfuscate_OQ��['expanded'] = TRUE;
        $_obfuscate_OQ��['sortId'] = 0;
        $_obfuscate_OQ��['href'] = "javascript:void(0);";
        $_obfuscate_OQ��['children'] = $_obfuscate_a49UK2tYTQ��;
        if ( $_obfuscate_AmrqwIM� )
        {
            $_obfuscate_oEs� = array( );
            $_obfuscate_oEs�['text'] = "我的常用流程(<span style=\"color:red\">".$_obfuscate_v1E5GY8�."</span>)";
            $_obfuscate_oEs�['iconCls'] = "icon-heart";
            $_obfuscate_oEs�['from'] = "wffav";
            $_obfuscate_oEs�['expanded'] = TRUE;
            $_obfuscate_oEs�['leaf'] = TRUE;
            $_obfuscate_oEs�['href'] = "javascript:void(0);";
        }
        return array(
            $_obfuscate_OQ��,
            $_obfuscate_oEs�
        );
    }

    public function api_getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_mV9HBLY� = getpar( $_POST, "start", 0 );
        $_obfuscate_v1GprsIz = getpar( $_POST, "sortId", 0 );
        $_obfuscate_ubP__Q�� = getpar( $_POST, "sort", "" );
        $_obfuscate_neM4JBUJlmg� = getpar( $_POST, "flowName", "" );
        $_obfuscate_Bk2lGlk� = "";
        if ( empty( $_obfuscate_v1GprsIz ) )
        {
            $_obfuscate_ggyI68Xtqd62AQ�� = app::loadapp( "wf", "flowSetSort" )->api_getSortDB( "faqi" );
            $_obfuscate_xHt_KRTgaAYpz6jkpg�� = array( 0 );
            foreach ( $_obfuscate_ggyI68Xtqd62AQ�� as $_obfuscate_6A�� )
            {
                $_obfuscate_xHt_KRTgaAYpz6jkpg��[] = $_obfuscate_6A��['sortId'];
            }
            $_obfuscate_Bk2lGlk� .= "WHERE `sortId` IN (".implode( ",", $_obfuscate_xHt_KRTgaAYpz6jkpg�� ).") ";
        }
        else
        {
            $_obfuscate_Bk2lGlk� .= "WHERE `sortId`='".$_obfuscate_v1GprsIz."' ";
        }
        if ( !empty( $_obfuscate_neM4JBUJlmg� ) )
        {
            $_obfuscate_Bk2lGlk� .= "AND `name` LIKE '%".$_obfuscate_neM4JBUJlmg�."%' ";
        }
        $_obfuscate_OTt14hA7OyXA44prAwU� = $this->_getSortPermitForNew( );
        $_obfuscate_Bk2lGlk� .= "AND `flowId` IN (".implode( ",", $_obfuscate_OTt14hA7OyXA44prAwU� ).") ";
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_flow, $_obfuscate_Bk2lGlk�.( "AND `status`='1' ORDER BY `flowId` DESC LIMIT ".$_obfuscate_mV9HBLY�.",{$this->rows}" ) );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_uly_hPh_dQ�� = array( 0 );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_uly_hPh_dQ��[] = $_obfuscate_6A��['sortId'];
        }
        $_obfuscate_RopcQP_w = $this->api_getSortByIds( $_obfuscate_uly_hPh_dQ�� );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['flowName'] = $_obfuscate_6A��['name'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['sname'] = $_obfuscate_RopcQP_w[$_obfuscate_6A��['sortId']]['name'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['abouttip'] = nl2br( $_obfuscate_6A��['about'] );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_mPAjEGLn;
        $_obfuscate_SUjPN94Er7yI->total = $CNOA_DB->db_getcount( $this->t_set_flow, $_obfuscate_Bk2lGlk�."AND `status`='1' " );
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
        $_obfuscate_Bk2lGlk� = "WHERE `sortId`='".$_obfuscate_v1GprsIz."' ";
        $_obfuscate_OTt14hA7OyXA44prAwU� = $this->_getSortPermitForNew( );
        $_obfuscate_Bk2lGlk� .= "AND `flowId` IN (".implode( ",", $_obfuscate_OTt14hA7OyXA44prAwU� ).") ";
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "sortId", "flowId", "name", "tplSort", "flowType", "about", "nameRuleId", "order" ), $this->t_set_flow, $_obfuscate_Bk2lGlk�."AND `status`='1' ORDER BY `order` ASC" );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_Qlo05cU_HQ�� = $CNOA_DB->db_select( "*", $this->t_u_wffav, "WHERE `uid`='".$_obfuscate_7Ri3."'" );
        if ( !is_array( $_obfuscate_Qlo05cU_HQ�� ) )
        {
            $_obfuscate_Qlo05cU_HQ�� = array( );
        }
        $_obfuscate_9HRMcXrsFGc6g�� = array( );
        foreach ( $_obfuscate_Qlo05cU_HQ�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_9HRMcXrsFGc6g��[] = $_obfuscate_6A��['flowId'];
        }
        unset( $_obfuscate_Qlo05cU_HQ�� );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['about'] = empty( $_obfuscate_6A��['about'] ) ? $_obfuscate_6A��['name'] : nl2br( $_obfuscate_6A��['about'] );
            if ( in_array( $_obfuscate_6A��['flowId'], $_obfuscate_9HRMcXrsFGc6g�� ) )
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['fav'] = 1;
            }
            else
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['fav'] = 0;
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
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_3y0Y = "SELECT `w`.`flowId`, `f`.`name`, `f`.`tplSort`, `f`.`flowType`, `f`.`nameRuleId`, `f`.`about`, `s`.`name` AS `sname` FROM ".tname( $this->t_u_wffav )." as `w` RIGHT JOIN ".tname( $this->t_set_flow )." AS `f` ON `f`.`flowId`=`w`.`flowId` LEFT JOIN ".tname( $this->t_set_sort )." AS `s` ON `s`.`sortId`=`f`.`sortId` ".( "WHERE `w`.`status`=1 AND `w`.`uid`=".$_obfuscate_7Ri3 );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_6RYLWQ��[] = array(
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
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_6RYLWQ��;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _my_sort( $m, $_obfuscate_8A�� )
    {
        if ( $m['fav'] == $_obfuscate_8A��['fav'] )
        {
            if ( $m['order'] < $_obfuscate_8A��['order'] )
            {
                return -1;
            }
            return 1;
        }
        if ( $_obfuscate_8A��['fav'] < $m['fav'] )
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
        foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_R7khXq3cT4VZ[$_obfuscate_6A��['flowId']] = $_obfuscate_6A��;
            $_obfuscate_YROI4z1Sytcm[] = $_obfuscate_6A��['sortId'];
        }
        $_obfuscate_RopcQP_w = app::loadapp( "wf", "flowSetSort" )->api_getSortData( array(
            "sortIdArr" => $_obfuscate_YROI4z1Sytcm
        ) );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['flowName'] = $_obfuscate_R7khXq3cT4VZ[$_obfuscate_6A��['flowId']]['name'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['about'] = $_obfuscate_R7khXq3cT4VZ[$_obfuscate_6A��['flowId']]['about'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['tplSort'] = $_obfuscate_R7khXq3cT4VZ[$_obfuscate_6A��['flowId']]['tplSort'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['nameRuleId'] = $_obfuscate_R7khXq3cT4VZ[$_obfuscate_6A��['flowId']]['nameRuleId'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['flowType'] = $_obfuscate_R7khXq3cT4VZ[$_obfuscate_6A��['flowId']]['flowType'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['childId'] = $_obfuscate_6A��['id'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['sname'] = $_obfuscate_RopcQP_w[$_obfuscate_R7khXq3cT4VZ[$_obfuscate_6A��['flowId']]['sortId']]['name'];
        }
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function _getQueryList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mV9HBLY� = getpar( $_POST, "start", 0 );
        $_obfuscate_v1GprsIz = getpar( $_POST, "sortId", 0 );
        $_obfuscate_GRtq_c1sXSMthq47 = getpar( $_GET, "bindfunction", "" );
        $_obfuscate_Bk2lGlk� = "";
        if ( array_key_exists( $_obfuscate_GRtq_c1sXSMthq47, $this->bindFunctionList ) )
        {
            ( );
            $_obfuscate_83c0kD6Npo� = new wfEngine( );
            $_obfuscate_83c0kD6Npo�->getQueryData( $_obfuscate_GRtq_c1sXSMthq47 );
            exit( );
        }
    }

    private function _show_loadFormInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_o5fQ1g�� = $CNOA_DB->db_getone( array( "formHtml" ), $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( !$_obfuscate_o5fQ1g�� )
        {
            msg::callback( FALSE, lang( "nodata" ) );
        }
        $_obfuscate_lEGQqw�� = str_replace( "&#39;", "'", $_obfuscate_o5fQ1g��['formHtml'] );
        $_obfuscate_DxNBNSYrOIc� = app::loadapp( "wf", "flowSetForm" )->api_getHtmlElement( $_obfuscate_lEGQqw�� );
        ( $_obfuscate_DxNBNSYrOIc�, $_obfuscate_lEGQqw�� );
        $_obfuscate_fgijdEZiFS2w4Q9zQQ�� = new wfFieldFormaterForPreview( );
        $_obfuscate_lEGQqw�� = $_obfuscate_fgijdEZiFS2w4Q9zQQ��->crteateFormHtml( );
        $_obfuscate_lEGQqw�� = "<table class=\"wf_div_cttb\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n\t\t<tr>\r\n\t\t<td class=\"wf_bd wf_tl\"></td>\r\n\t\t<td class=\"wf_bd wf_t\"></td>\r\n\t\t<td class=\"wf_bd wf_tr\"></td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t<td class=\"wf_bd2 wf_l\"></td>\r\n\t\t<td style=\"padding:50px;\" class=\"wf_c wf_form_content\">".$_obfuscate_lEGQqw��."</td>\r\n\t\t<td class=\"wf_bd2 wf_r\"></td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t<td class=\"wf_bd wf_bl\"></td>\r\n\t\t<td class=\"wf_bd wf_b\"></td>\r\n\t\t<td class=\"wf_bd wf_br\"></td>\r\n\t\t</tr>\r\n\t\t</table>";
        $_obfuscate_o5fQ1g��['formHtml'] = $_obfuscate_lEGQqw��;
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->total = 0;
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_o5fQ1g��;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _loadFormHtml( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_F4AbnVRh = intval( getpar( $_GET, "flowId", 0 ) );
        $_obfuscate_TlvKhtsoOQ�� = intval( getpar( $_POST, "uFlowId", 0 ) );
        $_obfuscate_8ftcdUn7PA�� = getpar( $_POST, "againId", 0 );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        ( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, TRUE, 0, $_obfuscate_8ftcdUn7PA�� );
        $_obfuscate_fr489CNrjWzinRUKCuozmmU� = new wfFieldFormaterForDeal( );
        $_obfuscate_fr489CNrjWzinRUKCuozmmU�->uFlowId = $_obfuscate_8ftcdUn7PA�� ? $_obfuscate_8ftcdUn7PA�� : $_obfuscate_TlvKhtsoOQ��;
        $_obfuscate_fr489CNrjWzinRUKCuozmmU�->creatorUid = $_obfuscate_7Ri3;
        $_obfuscate_5MjAF_AntLk� = $_obfuscate_fr489CNrjWzinRUKCuozmmU�->crteateFormHtml( );
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->total = 0;
        $_obfuscate_SUjPN94Er7yI->data = array(
            "formHtml" => $_obfuscate_5MjAF_AntLk�
        );
        $_obfuscate_SUjPN94Er7yI->pageset = $this->getPageSet( $_obfuscate_F4AbnVRh );
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _getSendNextData( )
    {
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_JQJwE4USnB0� = array( );
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
        }
        $_obfuscate_7qDAYo85aGA�['flowId'] = $_obfuscate_F4AbnVRh;
        $_obfuscate_7qDAYo85aGA�['formData'] = $_obfuscate_JQJwE4USnB0�;
        $_obfuscate_7qDAYo85aGA�['flowFrom'] = "new";
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        ( );
        $_obfuscate_xPTkGjow2od2YSeeUUc� = new wfNextStepData( );
        $_obfuscate_NlQ�->data = $_obfuscate_xPTkGjow2od2YSeeUUc�->getNextStepInfo( $_obfuscate_7qDAYo85aGA� );
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function _sendNextStep( )
    {
        ( );
        $_obfuscate_z9ygGPQKQgSeYn4opTWVK8g� = new wfNewSendNextStep( );
        ob_end_clean( );
        header( "content-type:text/html; charset=utf-8;" );
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = array(
            "uFlowId" => $_obfuscate_z9ygGPQKQgSeYn4opTWVK8g�->getuFlowId( )
        );
        echo "<textarea>".$_obfuscate_SUjPN94Er7yI->makeJsonData( )."</textarea>";
        exit( );
    }

    private function _makeDetailMaxCache( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_piwqe2DnH9mIPU0P )
    {
        $_obfuscate_6hS1Rw�� = CNOA_PATH_FILE."/common/wf/detailmaxcache/";
        @mkdirs( $_obfuscate_6hS1Rw�� );
        $_obfuscate_R2_b = "<?php\r\n";
        $_obfuscate_R2_b .= "return ".var_export( $_obfuscate_piwqe2DnH9mIPU0P, TRUE ).";";
        @file_put_contents( $_obfuscate_6hS1Rw��.$_obfuscate_TlvKhtsoOQ��.".php", $_obfuscate_R2_b );
    }

    private function _saveFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_KYPe3Fn6DvBxA�� = getpar( $_POST, "flowNumber", "" );
        $_obfuscate_neM4JBUJlmg� = getpar( $_POST, "flowName", "" );
        $_obfuscate_pYzeLf4� = getpar( $_POST, "level", 0 );
        $_obfuscate_MtvJpVij = getpar( $_POST, "reason", "" );
        $_obfuscate_s8yswCWZEIY� = getpar( $_GET, "otherApp", "" );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_GET, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_GET, "tplSort", 0 );
        $_obfuscate_7qDAYo85aGA� = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $_obfuscate_DZrwJy4LZQ�� = $this->_saveFormFieldInfo( );
        $_obfuscate_IuoXR2yOaxkRDw�� = $_obfuscate_DZrwJy4LZQ��[0];
        $_obfuscate_JQJwE4USnB0� = $_obfuscate_DZrwJy4LZQ��[1];
        $_obfuscate_FYo_0_BVp9xjgDs� = $_obfuscate_DZrwJy4LZQ��[2];
        $_obfuscate_BqBV6WSz3wel0ZDw = $_obfuscate_DZrwJy4LZQ��[3];
        $_obfuscate_piwqe2DnH9mIPU0P = $_obfuscate_DZrwJy4LZQ��[4];
        $_obfuscate_qZkmBg�� = array( );
        $_obfuscate_qZkmBg��['flowId'] = $_obfuscate_F4AbnVRh;
        $_obfuscate_qZkmBg��['flowNumber'] = $_obfuscate_KYPe3Fn6DvBxA��;
        $_obfuscate_qZkmBg��['flowName'] = $_obfuscate_neM4JBUJlmg�;
        $_obfuscate_qZkmBg��['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_qZkmBg��['level'] = $_obfuscate_pYzeLf4�;
        $_obfuscate_qZkmBg��['reason'] = $_obfuscate_MtvJpVij;
        $_obfuscate_qZkmBg��['posttime'] = 0;
        $_obfuscate_qZkmBg��['updatetime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_qZkmBg��['edittime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_qZkmBg��['sortId'] = $_obfuscate_7qDAYo85aGA�['sortId'];
        $_obfuscate_qZkmBg��['tplSort'] = $_obfuscate_7qDAYo85aGA�['tplSort'];
        $_obfuscate_qZkmBg��['status'] = 0;
        $_obfuscate_qZkmBg��['otherApp'] = $_obfuscate_s8yswCWZEIY�;
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowId", "attach" ), $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `uFlowId`='{$_obfuscate_TlvKhtsoOQ��}'" );
        $_obfuscate_vCKayYE4IxP3uvQw0A�� = json_decode( $_obfuscate_hTew0boWJESy['attach'], TRUE );
        if ( !is_array( $_obfuscate_vCKayYE4IxP3uvQw0A�� ) )
        {
            $_obfuscate_vCKayYE4IxP3uvQw0A�� = array( );
        }
        ( );
        $_obfuscate_2gg� = new fs( );
        $_obfuscate_1ERfSWbp = $_obfuscate_2gg�->uploadFile4Wf( $_obfuscate_IuoXR2yOaxkRDw��, $_obfuscate_vCKayYE4IxP3uvQw0A�� );
        $_obfuscate_qZkmBg��['attach'] = json_encode( $_obfuscate_1ERfSWbp[0] );
        if ( !$_obfuscate_hTew0boWJESy )
        {
            $_obfuscate_TlvKhtsoOQ�� = $CNOA_DB->db_insert( $_obfuscate_qZkmBg��, $this->t_use_flow );
        }
        else
        {
            $CNOA_DB->db_update( $_obfuscate_qZkmBg��, $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `uFlowId`='{$_obfuscate_TlvKhtsoOQ��}'" );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3602, lang( "saveDraftBox" ) );
        $this->api_saveFieldData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_JQJwE4USnB0�, $_obfuscate_FYo_0_BVp9xjgDs�, $_obfuscate_BqBV6WSz3wel0ZDw );
        $this->_makeDetailMaxCache( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_piwqe2DnH9mIPU0P );
        ob_end_clean( );
        header( "content-type:text/html; charset=utf-8;" );
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = array(
            "uFlowId" => $_obfuscate_TlvKhtsoOQ��,
            "saveTime" => formatdate( $GLOBALS['CNOA_TIMESTAMP'], " H时i分s秒 " )
        );
        $_obfuscate_NlQ�->attach = $_obfuscate_1ERfSWbp[1];
        echo "<textarea>".$_obfuscate_NlQ�->makeJsonData( )."</textarea>";
        exit( );
    }

    private function _savefreeFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_KYPe3Fn6DvBxA�� = getpar( $_POST, "flowNumber", "" );
        $_obfuscate_neM4JBUJlmg� = getpar( $_POST, "flowName", "" );
        $_obfuscate_pYzeLf4� = getpar( $_POST, "level", 0 );
        $_obfuscate_MtvJpVij = getpar( $_POST, "reason", "" );
        $_obfuscate_7qDAYo85aGA� = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $_obfuscate_IuoXR2yOaxkRDw�� = array( );
        foreach ( $GLOBALS['_POST'] as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( ereg( "wf_attach_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_IuoXR2yOaxkRDw��[] = intval( str_replace( "wf_attach_", "", $_obfuscate_5w�� ) );
            }
        }
        $_obfuscate_qZkmBg�� = array( );
        $_obfuscate_qZkmBg��['flowId'] = $_obfuscate_F4AbnVRh;
        $_obfuscate_qZkmBg��['flowNumber'] = $_obfuscate_KYPe3Fn6DvBxA��;
        $_obfuscate_qZkmBg��['flowName'] = $_obfuscate_neM4JBUJlmg�;
        $_obfuscate_qZkmBg��['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_qZkmBg��['level'] = $_obfuscate_pYzeLf4�;
        $_obfuscate_qZkmBg��['reason'] = $_obfuscate_MtvJpVij;
        $_obfuscate_qZkmBg��['posttime'] = 0;
        $_obfuscate_qZkmBg��['updatetime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_qZkmBg��['edittime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_qZkmBg��['sortId'] = $_obfuscate_7qDAYo85aGA�['sortId'];
        $_obfuscate_qZkmBg��['status'] = 0;
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowId", "attach" ), $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `uFlowId`='{$_obfuscate_TlvKhtsoOQ��}'" );
        $_obfuscate_vCKayYE4IxP3uvQw0A�� = json_decode( $_obfuscate_hTew0boWJESy['attach'], TRUE );
        if ( !is_array( $_obfuscate_vCKayYE4IxP3uvQw0A�� ) )
        {
            $_obfuscate_vCKayYE4IxP3uvQw0A�� = array( );
        }
        ( );
        $_obfuscate_2gg� = new fs( );
        $_obfuscate_1ERfSWbp = $_obfuscate_2gg�->uploadFile4Wf( $_obfuscate_IuoXR2yOaxkRDw��, $_obfuscate_vCKayYE4IxP3uvQw0A�� );
        $_obfuscate_qZkmBg��['attach'] = json_encode( $_obfuscate_1ERfSWbp[0] );
        if ( !$_obfuscate_hTew0boWJESy )
        {
            $_obfuscate_TlvKhtsoOQ�� = $CNOA_DB->db_insert( $_obfuscate_qZkmBg��, $this->t_use_flow );
        }
        else
        {
            $CNOA_DB->db_update( $_obfuscate_qZkmBg��, $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `uFlowId`='{$_obfuscate_TlvKhtsoOQ��}'" );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3602, lang( "saveDraftBox" ).",".lang( "flowName" ).( "[".$_obfuscate_qZkmBg��['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_qZkmBg��['flowNumber']."]" ) );
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = array(
            "uFlowId" => $_obfuscate_TlvKhtsoOQ��,
            "attach" => $_obfuscate_1ERfSWbp[1],
            "saveTime" => formatdate( $GLOBALS['CNOA_TIMESTAMP'], " H时i分s秒 " )
        );
        echo $_obfuscate_NlQ�->makeJsonData( );
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
        $_obfuscate_vzGArcjOKr8_7A��['allowAttachAdd'] = $_obfuscate_Tx7M9W['allowAttachAdd'];
        $_obfuscate_vzGArcjOKr8_7A��['allowAttachEdit'] = $_obfuscate_Tx7M9W['allowAttachEdit'];
        $_obfuscate_vzGArcjOKr8_7A��['allowAttachDelete'] = $_obfuscate_Tx7M9W['allowAttachDelete'];
        $_obfuscate_vzGArcjOKr8_7A��['allowAttachView'] = $_obfuscate_Tx7M9W['allowAttachView'];
        $_obfuscate_vzGArcjOKr8_7A��['allowAttachDown'] = $_obfuscate_Tx7M9W['allowAttachDown'];
        return $_obfuscate_vzGArcjOKr8_7A��;
    }

    private function _loadUflowInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_jTbXTguM6pC9CA�� = getpar( $_POST, "editAction", "add" );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_POST, "flowType", 0 );
        $_obfuscate_d34ykzwUSke5RE� = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $_obfuscate_6RYLWQ�� = array( );
        if ( $_obfuscate_jTbXTguM6pC9CA�� == "edit" )
        {
            $_obfuscate_qzT8MGGEZ7qaWGA4 = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `uFlowId`='{$_obfuscate_TlvKhtsoOQ��}'" );
            $_obfuscate_6RYLWQ��['flowNumber'] = $_obfuscate_qzT8MGGEZ7qaWGA4['flowNumber'];
            $_obfuscate_6RYLWQ��['flowName'] = $_obfuscate_qzT8MGGEZ7qaWGA4['flowName'];
            $_obfuscate_6RYLWQ��['reason'] = $_obfuscate_qzT8MGGEZ7qaWGA4['reason'];
            $_obfuscate_6RYLWQ��['level'] = $_obfuscate_qzT8MGGEZ7qaWGA4['level'];
            $_obfuscate_6RYLWQ��['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
            $_obfuscate_vzGArcjOKr8_7A�� = $this->_makeAttachAllow( $_obfuscate_F4AbnVRh );
            ( );
            $_obfuscate_2gg� = new fs( );
            $_obfuscate_qzT8MGGEZ7qaWGA4['attach'] = $this->getAttachList( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_qzT8MGGEZ7qaWGA4 );
            $_obfuscate_8CpDPPa = $_obfuscate_2gg�->getListInfo4wf( json_decode( $_obfuscate_qzT8MGGEZ7qaWGA4['attach'], TRUE ), $_obfuscate_vzGArcjOKr8_7A��, TRUE, "new" );
            if ( $_obfuscate_XkuTFqZ6Tmk� == 0 && $_obfuscate_pEvU7Kz2Yw�� == 0 )
            {
                $_obfuscate_urgydSw7IkMKIoqpA�� = $this->api_getWfFieldData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ�� );
                $_obfuscate_sAnybXE� = $CNOA_DB->db_select( array( "id" ), $this->t_set_field, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' AND `otype` = 'detailtable' " );
                if ( is_array( $_obfuscate_sAnybXE� ) )
                {
                    $_obfuscate_sAnybXE� = array( );
                }
                if ( !empty( $_obfuscate_sAnybXE� ) )
                {
                    $_obfuscate_ScER1S69bt_Qmg�� = array( );
                    foreach ( $_obfuscate_sAnybXE� as $_obfuscate_6A�� )
                    {
                        $_obfuscate_XNOBiCm6rWto5G4wFkJiVkUqo0�[] = $this->api_getWfFieldDetailData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_6A��['id'] );
                    }
                }
            }
        }
        else
        {
            $_obfuscate_6RYLWQ��['flowNumber'] = $_obfuscate_d34ykzwUSke5RE�['nameRule'];
            $_obfuscate_6RYLWQ��['uFlowId'] = 0;
        }
        $_obfuscate_l5xoT48YaQ�� = getpar( $_GET, "childId", 0 );
        if ( !empty( $_obfuscate_l5xoT48YaQ�� ) )
        {
            $_obfuscate_jOcDpChC9w�� = $CNOA_DB->db_getone( array( "puFlowId" ), $this->t_use_step_child_flow, "WHERE `id` = ".$_obfuscate_l5xoT48YaQ��." AND `sharefile` = 1 " );
            if ( !empty( $_obfuscate_jOcDpChC9w�� ) )
            {
                $_obfuscate_ViAjUrWq8zM� = $CNOA_DB->db_getone( array( "attach" ), $this->t_use_flow, "WHERE `uFlowId` = ".$_obfuscate_jOcDpChC9w��['puFlowId']." " );
                $_obfuscate_vzGArcjOKr8_7A�� = $this->_makeAttachAllow( $_obfuscate_F4AbnVRh );
                ( );
                $_obfuscate_2gg� = new fs( );
                $_obfuscate_8CpDPPa = $_obfuscate_2gg�->getListInfo4wf( json_decode( $_obfuscate_ViAjUrWq8zM�['attach'], TRUE ), $_obfuscate_vzGArcjOKr8_7A��, TRUE, "new" );
            }
        }
        $_obfuscate_CgmidHjdgZGsBg�� = getpar( $_GET, "otherApp", 0 );
        if ( !empty( $_obfuscate_CgmidHjdgZGsBg�� ) )
        {
            $_obfuscate_WPvkSFEMg�� = $CNOA_DB->db_getfield( "attach", "odoc_data", "WHERE `otherAppId`='".$_obfuscate_CgmidHjdgZGsBg��."'" );
            if ( !empty( $_obfuscate_WPvkSFEMg�� ) )
            {
                $_obfuscate_vzGArcjOKr8_7A�� = $this->_makeAttachAllow( $_obfuscate_F4AbnVRh );
                ( );
                $_obfuscate_2gg� = new fs( );
                $_obfuscate_8CpDPPa = $_obfuscate_2gg�->getListInfo4wf( json_decode( $_obfuscate_WPvkSFEMg��, TRUE ), $_obfuscate_vzGArcjOKr8_7A��, TRUE, "new" );
            }
        }
        $_obfuscate_QSpsVAzJ = array( );
        $_obfuscate_5NhzjnJq_f8� = $CNOA_DB->db_getone( "*", $this->t_set_step, "WHERE `stepType`=1 AND `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $_obfuscate_QSpsVAzJ['allowAttachAdd'] = $_obfuscate_5NhzjnJq_f8�['allowAttachAdd'];
        $_obfuscate_QSpsVAzJ['allowSms'] = $_obfuscate_5NhzjnJq_f8�['allowSms'];
        $_obfuscate_QSpsVAzJ['allowEditFlowNumber'] = $_obfuscate_d34ykzwUSke5RE�['nameRuleAllowEdit'];
        $_obfuscate_QSpsVAzJ['nameDisallowBlank'] = $_obfuscate_d34ykzwUSke5RE�['nameDisallowBlank'];
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_6RYLWQ��;
        $_obfuscate_NlQ�->attach = $_obfuscate_8CpDPPa;
        if ( $_obfuscate_XkuTFqZ6Tmk� == 0 && $_obfuscate_pEvU7Kz2Yw�� == 0 )
        {
            $_obfuscate_NlQ�->wf_field_data = $_obfuscate_urgydSw7IkMKIoqpA��;
            $_obfuscate_NlQ�->wf_detail_field_data = $_obfuscate_XNOBiCm6rWto5G4wFkJiVkUqo0�;
        }
        $_obfuscate_NlQ�->config = $_obfuscate_QSpsVAzJ;
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function _loadFlowDesignData( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = intval( getpar( $_POST, "flowId" ) );
        $_obfuscate_TlvKhtsoOQ�� = intval( getpar( $_POST, "uFlowId" ) );
        if ( $_obfuscate_TlvKhtsoOQ�� !== 0 )
        {
            ( $_obfuscate_TlvKhtsoOQ�� );
            $_obfuscate_e53ODz04JQ�� = new wfCache( );
            $_obfuscate_dw4x = $_obfuscate_e53ODz04JQ��->getFlowXML( );
            $_obfuscate_T1JGvNjMhVs� = array( );
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
            if ( !is_array( $_obfuscate_mPAjEGLn ) )
            {
                $_obfuscate_mPAjEGLn = array( );
            }
            $_obfuscate__eqrEQ�� = array( );
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A�� )
            {
                $_obfuscate_0Ul8BBkt = $_obfuscate_6A��['uStepId'];
                if ( isset( $_obfuscate_T1JGvNjMhVs�[$_obfuscate_0Ul8BBkt] ) )
                {
                    if ( $_obfuscate_T1JGvNjMhVs�[$_obfuscate_0Ul8BBkt]['stime'] < $_obfuscate_6A��['stime'] )
                    {
                        $_obfuscate_T1JGvNjMhVs�[$_obfuscate_0Ul8BBkt] = $_obfuscate_6A��;
                    }
                }
                else
                {
                    $_obfuscate_T1JGvNjMhVs�[$_obfuscate_0Ul8BBkt] = $_obfuscate_6A��;
                }
                if ( $_obfuscate_6A��['uid'] )
                {
                    $_obfuscate__eqrEQ��[] = $_obfuscate_6A��['uid'];
                }
                if ( $_obfuscate_6A��['proxyUid'] )
                {
                    $_obfuscate__eqrEQ��[] = $_obfuscate_6A��['proxyUid'];
                }
                if ( $_obfuscate_6A��['dealUid'] )
                {
                    $_obfuscate__eqrEQ��[] = $_obfuscate_6A��['dealUid'];
                }
            }
            unset( $_obfuscate_mPAjEGLn );
            if ( !empty( $_obfuscate_T1JGvNjMhVs� ) )
            {
                $_obfuscate__eqrEQ�� = array_unique( $_obfuscate__eqrEQ�� );
                $_obfuscate__Wi6396IheA� = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate__eqrEQ�� );
                $_obfuscate_V2OgJ71KLjey = simplexml_load_string( $_obfuscate_dw4x );
                foreach ( $_obfuscate_V2OgJ71KLjey->root->mxCell as $_obfuscate_7kIMZw�� )
                {
                    $_obfuscate_IyicAyA� = $_obfuscate_7kIMZw��->attributes( );
                    if ( intval( $_obfuscate_IyicAyA�['edge'] ) == 1 )
                    {
                        $_obfuscate_UqWgv15HzZ3SbA�� = $_obfuscate_T1JGvNjMhVs�[intval( $_obfuscate_IyicAyA�->target )];
                        if ( !isset( $_obfuscate_UqWgv15HzZ3SbA�� ) && !( $_obfuscate_UqWgv15HzZ3SbA��['status'] != 0 ) && !( $_obfuscate_UqWgv15HzZ3SbA��['uid'] != 0 ) || !( $_obfuscate_UqWgv15HzZ3SbA��['proxyUid'] != 0 ) )
                        {
                            $_obfuscate_IyicAyA�->addAttribute( "done", 1 );
                        }
                    }
                    else
                    {
                        $_obfuscate_0Ul8BBkt = ( integer )$_obfuscate_IyicAyA�->id;
                        $_obfuscate_VBCv7Q�� = $_obfuscate_T1JGvNjMhVs�[$_obfuscate_0Ul8BBkt];
                        if ( !isset( $_obfuscate_VBCv7Q�� ) )
                        {
                        }
                        else if ( $_obfuscate_VBCv7Q��['stepType'] == 5 && $_obfuscate_VBCv7Q��['status'] == 0 )
                        {
                            $_obfuscate_Jrp1 = $CNOA_DB->db_select( array( "uid", "proxyUid", "dealUid" ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId`={$_obfuscate_VBCv7Q��['uStepId']}" );
                            if ( !is_array( $_obfuscate_Jrp1 ) )
                            {
                                $_obfuscate_Jrp1 = array( );
                            }
                            $_obfuscate_rFR1zydgg�� = $CNOA_DB->db_getfield( "deal", $this->t_use_deal_way, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND `stepId`={$_obfuscate_VBCv7Q��['uStepId']}" );
                            $_obfuscate_Gnm2iOeObk8� = "";
                            if ( $_obfuscate_rFR1zydgg�� == 1 )
                            {
                                foreach ( $_obfuscate_Jrp1 as $_obfuscate_fh2Upg�� )
                                {
                                    if ( $_obfuscate_fh2Upg��['uid'] != 0 )
                                    {
                                        $_obfuscate_Gnm2iOeObk8� .= "\r\n".$_obfuscate__Wi6396IheA�[$_obfuscate_fh2Upg��['uid']]['truename'];
                                        if ( $_obfuscate_fh2Upg��['dealUid'] == $_obfuscate_fh2Upg��['uid'] || $_obfuscate_fh2Upg��['dealUid'] == 0 && $_obfuscate_fh2Upg��['proxyUid'] == 0 )
                                        {
                                            $_obfuscate_Gnm2iOeObk8� .= "[☺]";
                                        }
                                    }
                                    if ( $_obfuscate_fh2Upg��['proxyUid'] != 0 )
                                    {
                                        $_obfuscate_Gnm2iOeObk8� .= " → ".$_obfuscate__Wi6396IheA�[$_obfuscate_fh2Upg��['proxyUid']]['truename'];
                                        if ( $_obfuscate_fh2Upg��['dealUid'] == $_obfuscate_fh2Upg��['proxyUid'] )
                                        {
                                            $_obfuscate_Gnm2iOeObk8� .= "[☺]";
                                        }
                                    }
                                }
                            }
                            else
                            {
                                if ( $_obfuscate_VBCv7Q��['uid'] != 0 )
                                {
                                    $_obfuscate_Gnm2iOeObk8� .= "\r\n".$_obfuscate__Wi6396IheA�[$_obfuscate_VBCv7Q��['uid']]['truename'];
                                    if ( $_obfuscate_VBCv7Q��['dealUid'] == $_obfuscate_VBCv7Q��['uid'] || $_obfuscate_VBCv7Q��['dealUid'] == 0 && $_obfuscate_VBCv7Q��['proxyUid'] == 0 )
                                    {
                                        $_obfuscate_Gnm2iOeObk8� .= "[☺]";
                                    }
                                }
                                if ( $_obfuscate_VBCv7Q��['proxyUid'] != 0 )
                                {
                                    $_obfuscate_Gnm2iOeObk8� .= " → ".$_obfuscate__Wi6396IheA�[$_obfuscate_VBCv7Q��['proxyUid']]['truename'];
                                    if ( $_obfuscate_VBCv7Q��['dealUid'] == $_obfuscate_VBCv7Q��['proxyUid'] )
                                    {
                                        $_obfuscate_Gnm2iOeObk8� .= "[☺]";
                                    }
                                }
                            }
                            $_obfuscate_IyicAyA�->value = strval( $_obfuscate_IyicAyA�->value ).$_obfuscate_Gnm2iOeObk8�;
                            if ( $_obfuscate_VBCv7Q��['uid'] == 0 )
                            {
                                $_obfuscate_IyicAyA�->addAttribute( "nodoing", 1 );
                            }
                            else if ( $_obfuscate_VBCv7Q��['status'] == 1 )
                            {
                                $_obfuscate_IyicAyA�->addAttribute( "doing", 1 );
                            }
                            else if ( $_obfuscate_VBCv7Q��['status'] == 2 && $_obfuscate_VBCv7Q��['dealUid'] == 0 && $_obfuscate_VBCv7Q��['stepType'] != 1 )
                            {
                                $_obfuscate_IyicAyA�->addAttribute( "nodo", 1 );
                            }
                            else
                            {
                                $_obfuscate_IyicAyA�->addAttribute( "done", 1 );
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
            foreach ( $_obfuscate_V2OgJ71KLjey->root->mxCell as $_obfuscate_7kIMZw�� )
            {
                $_obfuscate_IyicAyA� = $_obfuscate_7kIMZw��->attributes( );
                if ( intval( $_obfuscate_IyicAyA�->id ) < 2 || !in_array( strval( $_obfuscate_IyicAyA�->nodeType ), array( "edge", "node", "startNode", "endNode", "cNode", "bNode", "bcNode" ) ) )
                {
                    $_obfuscate_IyicAyA�->addAttribute( "newnode", 1 );
                }
            }
            $_obfuscate_dw4x = $_obfuscate_V2OgJ71KLjey->asXML( );
        }
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_6RYLWQ��['flowXml'] = $_obfuscate_dw4x;
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_6RYLWQ��;
        echo $_obfuscate_NlQ�->makeJsonData( );
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
        $_obfuscate_o5fQ1g�� = $CNOA_DB->db_getone( "*", $this->t_u_wffav, "WHERE `uid`='".$_obfuscate_7Ri3."' AND `flowId`='{$_obfuscate_F4AbnVRh}'" );
        if ( $_obfuscate_o5fQ1g�� !== FALSE )
        {
            $CNOA_DB->db_delete( $this->t_u_wffav, "WHERE `uid`='".$_obfuscate_7Ri3."' AND `flowId`='{$_obfuscate_F4AbnVRh}'" );
            msg::callback( TRUE, lang( "cancelStar" ) );
        }
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_6RYLWQ��['flowId'] = $_obfuscate_F4AbnVRh;
        $_obfuscate_6RYLWQ��['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_6RYLWQ��['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_6RYLWQ��['status'] = 1;
        $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_u_wffav );
        msg::callback( TRUE, lang( "hasStar" ) );
    }

    private function _getSortPermitForNew( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_iuzS = $CNOA_SESSION->get( "DID" );
        $_obfuscate_y6jH = $CNOA_SESSION->get( "SID" );
        $_obfuscate_fMfssw�� = $CNOA_DB->db_select( "*", $this->t_set_step_user, "WHERE `firstStep`=1" );
        if ( !is_array( $_obfuscate_fMfssw�� ) )
        {
            $_obfuscate_fMfssw�� = array( );
        }
        $_obfuscate_4kUQwYUyFDNukgQvGdI� = $CNOA_DB->db_select( array( "flowId" ), $this->t_set_step_user, "WHERE `firstStep`=1 AND `exclude` = ".$_obfuscate_7Ri3 );
        if ( !is_array( $_obfuscate_4kUQwYUyFDNukgQvGdI� ) )
        {
            $_obfuscate_4kUQwYUyFDNukgQvGdI� = array( );
        }
        $_obfuscate_VZUqlJW7N20� = array( );
        foreach ( $_obfuscate_4kUQwYUyFDNukgQvGdI� as $_obfuscate_Vwty => $_obfuscate_VgKtFeg� )
        {
            $_obfuscate_VZUqlJW7N20�[] = $_obfuscate_VgKtFeg�['flowId'];
        }
        if ( !is_array( $_obfuscate_fMfssw�� ) )
        {
            $_obfuscate_fMfssw�� = array( );
        }
        $_obfuscate_8Bnz38wN01c� = array( );
        foreach ( $_obfuscate_fMfssw�� as $_obfuscate_6A�� )
        {
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_6A��['flowId']][] = $_obfuscate_6A��;
        }
        $_obfuscate_uWfP0Bouw�� = array( 0 );
        foreach ( $_obfuscate_8Bnz38wN01c� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_xv5Nfm7aigk� = FALSE;
            if ( count( $_obfuscate_6A�� ) == 1 && $_obfuscate_6A��[0]['type'] == "" )
            {
                $_obfuscate_xv5Nfm7aigk� = TRUE;
            }
            else if ( 0 < count( $_obfuscate_6A�� ) )
            {
                foreach ( $_obfuscate_6A�� as $_obfuscate_snM� )
                {
                    if ( $_obfuscate_snM�['type'] == "people" )
                    {
                        if ( $_obfuscate_snM�['people'] == $_obfuscate_7Ri3 )
                        {
                            $_obfuscate_xv5Nfm7aigk� = TRUE;
                        }
                    }
                    else if ( $_obfuscate_snM�['type'] == "dept" )
                    {
                        if ( $_obfuscate_snM�['dept'] == $_obfuscate_iuzS )
                        {
                            $_obfuscate_xv5Nfm7aigk� = TRUE;
                        }
                    }
                    else if ( $_obfuscate_snM�['type'] == "station" )
                    {
                        if ( $_obfuscate_snM�['station'] == $_obfuscate_y6jH )
                        {
                            $_obfuscate_xv5Nfm7aigk� = TRUE;
                        }
                    }
                    else if ( !( $_obfuscate_snM�['type'] == "deptstation" ) && !( $_obfuscate_snM�['dept'] == $_obfuscate_iuzS ) && !( $_obfuscate_snM�['station'] == $_obfuscate_y6jH ) )
                    {
                        $_obfuscate_xv5Nfm7aigk� = TRUE;
                    }
                }
            }
            if ( $_obfuscate_xv5Nfm7aigk� )
            {
                $_obfuscate_uWfP0Bouw��[] = $_obfuscate_5w��;
            }
        }
        foreach ( $_obfuscate_uWfP0Bouw�� as $_obfuscate_Vwty => $_obfuscate_VgKtFeg� )
        {
            if ( !empty( $_obfuscate_VZUqlJW7N20� ) )
            {
                foreach ( $_obfuscate_VZUqlJW7N20� as $_obfuscate_6A�� )
                {
                    if ( $_obfuscate_6A�� == $_obfuscate_VgKtFeg� )
                    {
                        unset( $_obfuscate_uWfP0Bouw��[$_obfuscate_Vwty] );
                    }
                }
            }
        }
        $_obfuscate_rCz1avpqUCVhbQ�� = $CNOA_DB->db_select( array( "flowId" ), $this->t_set_flow, "WHERE `flowType`!=0" );
        if ( !is_array( $_obfuscate_rCz1avpqUCVhbQ�� ) )
        {
            $_obfuscate_rCz1avpqUCVhbQ�� = array( );
        }
        foreach ( $_obfuscate_rCz1avpqUCVhbQ�� as $_obfuscate_6A�� )
        {
            $_obfuscate_uWfP0Bouw��[] = $_obfuscate_6A��['flowId'];
        }
        return $_obfuscate_uWfP0Bouw��;
    }

    private function __isFlowNewPermit( $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_iuzS = $CNOA_SESSION->get( "DID" );
        $_obfuscate_y6jH = $CNOA_SESSION->get( "SID" );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_ycw� = $CNOA_DB->db_select( "*", $this->t_set_step_user, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `firstStep`=1" );
        if ( !is_array( $_obfuscate_ycw� ) )
        {
            $_obfuscate_ycw� = array( );
        }
        $_obfuscate_4kUQwYUyFDNukgQvGdI� = $CNOA_DB->db_select( array( "flowId" ), $this->t_set_step_user, "WHERE `firstStep`=1 AND `exclude` = ".$_obfuscate_7Ri3 );
        if ( !is_array( $_obfuscate_4kUQwYUyFDNukgQvGdI� ) )
        {
            $_obfuscate_4kUQwYUyFDNukgQvGdI� = array( );
        }
        $_obfuscate_uWfP0Bouw�� = array( );
        foreach ( $_obfuscate_4kUQwYUyFDNukgQvGdI� as $_obfuscate_Vwty => $_obfuscate_VgKtFeg� )
        {
            $_obfuscate_uWfP0Bouw��[] = $_obfuscate_VgKtFeg�['flowId'];
        }
        $_obfuscate_xv5Nfm7aigk� = FALSE;
        if ( count( $_obfuscate_ycw� ) == 1 && $_obfuscate_ycw�[0]['type'] == "" )
        {
            $_obfuscate_xv5Nfm7aigk� = TRUE;
        }
        else
        {
            $_obfuscate_JDTeROs = FALSE;
            foreach ( $_obfuscate_ycw� as $_obfuscate_snM� )
            {
                if ( $_obfuscate_snM�['type'] == "people" )
                {
                    if ( $_obfuscate_snM�['people'] == $_obfuscate_7Ri3 )
                    {
                        $_obfuscate_xv5Nfm7aigk� = TRUE;
                    }
                }
                else if ( $_obfuscate_snM�['type'] == "dept" )
                {
                    if ( $_obfuscate_snM�['dept'] == $_obfuscate_iuzS )
                    {
                        $_obfuscate_xv5Nfm7aigk� = TRUE;
                    }
                }
                else if ( $_obfuscate_snM�['type'] == "station" )
                {
                    if ( $_obfuscate_snM�['station'] == $_obfuscate_y6jH )
                    {
                        $_obfuscate_xv5Nfm7aigk� = TRUE;
                    }
                }
                else if ( !( $_obfuscate_snM�['type'] == "deptstation" ) && !( $_obfuscate_snM�['dept'] == $_obfuscate_iuzS ) && !( $_obfuscate_snM�['station'] == $_obfuscate_y6jH ) )
                {
                    $_obfuscate_xv5Nfm7aigk� = TRUE;
                }
            }
        }
        if ( in_array( $_obfuscate_F4AbnVRh, $_obfuscate_uWfP0Bouw�� ) )
        {
            return FALSE;
        }
        return $_obfuscate_xv5Nfm7aigk�;
    }

    private function _savePosition( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_6RYLWQ�� = json_decode( $_POST['data'], TRUE );
        $GLOBALS['dataSafeScan']->stopAttackFromArray( $_obfuscate_6RYLWQ�� );
        if ( is_array( $_obfuscate_6RYLWQ�� ) )
        {
            $CNOA_DB->db_delete( $this->t_u_setting, "WHERE `uid`='".$_obfuscate_7Ri3."'" );
            foreach ( $_obfuscate_6RYLWQ�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                if ( is_array( $_obfuscate_6A�� ) )
                {
                    foreach ( $_obfuscate_6A�� as $_obfuscate_ty0� => $_obfuscate_snM� )
                    {
                        $Tc = array( );
                        $Tc['uid'] = $_obfuscate_7Ri3;
                        $Tc['sortId'] = str_replace( "flowColumnItem", "", $_obfuscate_snM� );
                        $Tc['column'] = $_obfuscate_5w��;
                        $Tc['position'] = $_obfuscate_ty0�;
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
        $_obfuscate_IRFhnYw� = "WHERE `uid`=".$_obfuscate_7Ri3;
        $_obfuscate_6RYLWQ�� = $CNOA_DB->db_select( array( "signature", "url", "isUsePwd" ), "system_signature", $_obfuscate_IRFhnYw� );
        if ( !is_array( $_obfuscate_6RYLWQ�� ) )
        {
            $_obfuscate_6RYLWQ�� = array( );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_6RYLWQ��;
        $_obfuscate_SUjPN94Er7yI->total = $CNOA_DB->db_sum( "id", "system_signature", $_obfuscate_IRFhnYw� );
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _ms_loadTemplateFile( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = intval( getpar( $_GET, "flowId", 0 ) );
        $_obfuscate_TlvKhtsoOQ�� = intval( getpar( $_GET, "uFlowId", 0 ) );
        $_obfuscate_XkuTFqZ6Tmk� = intval( getpar( $_GET, "flowType", 0 ) );
        $_obfuscate_pEvU7Kz2Yw�� = intval( getpar( $_GET, "tplSort", 0 ) );
        $_obfuscate_jTbXTguM6pC9CA�� = getpar( $_GET, "editAction", "add" );
        $this->api_loadTemplateFile( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_XkuTFqZ6Tmk�, $_obfuscate_pEvU7Kz2Yw��, $_obfuscate_jTbXTguM6pC9CA�� );
        exit( );
    }

    private function _getBusinessData( )
    {
        $_obfuscate_olwD8Q�� = getpar( $_GET, "bindfunc", "" );
        $_obfuscate_pYzeLf4� = getpar( $_GET, "level", "" );
        if ( empty( $_obfuscate_olwD8Q�� ) )
        {
            return "";
        }
        $_obfuscate_wZ6MPP0� = "wfEngine".ucfirst( $_obfuscate_olwD8Q�� );
        if ( !class_exists( $_obfuscate_wZ6MPP0� ) )
        {
            return;
        }
        ( );
        $_obfuscate_7eRCEirmYGaYE1FJCfc0hw�� = new $_obfuscate_wZ6MPP0�( );
        return $_obfuscate_7eRCEirmYGaYE1FJCfc0hw��->getBusinessData( $_obfuscate_pYzeLf4� );
    }

    private function __checkDraft( $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_7qDAYo85aGA� = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `uid`='{$_obfuscate_7Ri3}' AND `status`=0 ORDER BY `uFlowId` DESC" );
        if ( $_obfuscate_7qDAYo85aGA� !== FALSE )
        {
            return array(
                "uFlowId" => $_obfuscate_7qDAYo85aGA�['uFlowId'],
                "savedTime" => formatdate( $_obfuscate_7qDAYo85aGA�['edittime'], "Y年m月d日 H时i分s秒" )
            );
        }
        return array( "uFlowId" => "0", "savedTime" => "0" );
    }

    private function _delSalaryDraft( )
    {
        global $CNOA_DB;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId" );
        $_obfuscate_gftfagw� = $CNOA_DB->db_getcount( $this->t_use_flow, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ�� );
        if ( 0 < $_obfuscate_gftfagw� )
        {
            $CNOA_DB->db_delete( $this->t_use_flow, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ�� );
        }
    }

    private function _bindingProcess( )
    {
        global $CNOA_DB;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId" );
        $_obfuscate_0muy1AMpeuH = getpar( $_POST, "noticeLid" );
        $_obfuscate_qXF5WAWqYSI� = getpar( $_POST, "salaryApproveId" );
        if ( $_obfuscate_0muy1AMpeuH )
        {
            $_obfuscate_3tiDsnM� = "news_notice_list";
            $CNOA_DB->db_update( array(
                "uFlowId" => $_obfuscate_TlvKhtsoOQ��
            ), $_obfuscate_3tiDsnM�, "WHERE `id` = ".$_obfuscate_0muy1AMpeuH );
        }
    }

    public function api_makeDetailMaxCache( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_piwqe2DnH9mIPU0P )
    {
        $this->_makeDetailMaxCache( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_piwqe2DnH9mIPU0P );
    }

    public function api_getSendNextData( )
    {
        $this->_getSendNextData( );
    }

}

?>
