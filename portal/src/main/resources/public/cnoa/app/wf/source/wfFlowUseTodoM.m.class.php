<?php

class wfFlowUseTodoM extends wfCommon
{

    public function run( )
    {
        $this->clientType = "MOB";
        $_obfuscate_M_5JJw�� = getpar( $_GET, "task", "loadPage" );
        switch ( $_obfuscate_M_5JJw�� )
        {
        case "getJsonData" :
            $this->_getJsonData( );
            break;
        case "getJsonDataNew" :
            $this->_getJsonDataNew( );
            break;
        case "getSendNextData" :
            $this->_getSendNextData( );
            break;
        case "sendNextStep" :
            $this->_sendNextStep( );
            break;
        case "uploadfile" :
            $this->_uploadfile( );
            break;
        case "uploadAttach" :
            $this->_uploadAttach( );
            break;
        case "submitRejectAbout" :
            $this->_submitRejectAbout( );
            break;
        case "loadPrevstepData" :
            $this->_loadPrevstepData( );
            break;
        case "loadPrevstepDataNew" :
            $this->_loadPrevstepDataNew( );
            break;
        case "submitPrevstepData" :
            $this->_submitPrevstepData( );
            break;
        case "showDetailTable" :
            $this->_showDetailTable( );
            break;
        case "getHuiqianJsonData" :
            $this->_getHuiqianJsonData( );
            break;
        case "submitHuiQianInfo" :
            $this->_submitHuiQianInfo( );
            break;
        case "huiqianMsg" :
            $this->_huiqianMsg( );
            break;
        case "loadEntrustForm" :
            $this->_loadEntrustForm( );
            break;
        case "submitEntrustFormData" :
            $this->_submitEntrustFormData( );
            break;
        case "getFenfaList" :
            $this->_getFenfaList( );
            break;
        case "addFenfa" :
            $this->_addFenfa( );
            break;
        case "addReaderSay" :
            $this->_addReaderSay( );
            break;
        case "getStepList" :
            $this->_getStepList( );
            break;
        case "cuiban" :
            $this->_cuiban( );
            break;
        case "getEventList" :
            $this->_getEventList( );
            break;
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "getFlowTypeData" :
            $this->_getFlowTypeData( );
            break;
        case "loadUflowInfo" :
            $this->_loadUflowInfo( );
            break;
        case "getEventLists" :
            $this->_getEventLists( );
            break;
        case "getStepLists" :
            $this->_getStepLists( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $_obfuscate_vholQ�� = getpar( $_GET, "from", "list" );
        if ( $_obfuscate_vholQ�� == "list" )
        {
            $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/todo.m.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQ�� );
        }
        else if ( $_obfuscate_vholQ�� == "showflow" )
        {
            $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/showflow.m.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQ�� );
        }
    }

    private function _loadUflowInfo( $_obfuscate_vholQ�� = NULL )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQ�� = intval( getpar( $_POST, "uFlowId", 0 ) );
        $_obfuscate_VBCv7Q�� = intval( getpar( $_POST, "step", 0 ) );
        $_obfuscate_pEvU7Kz2Yw�� = intval( getpar( $_POST, "tplSort", 0 ) );
        $_obfuscate_XkuTFqZ6Tmk� = intval( getpar( $_POST, "flowType", 0 ) );
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_5LuNFL5U2xQ� = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $_obfuscate_6RYLWQ��['flowNumber'] = $_obfuscate_5LuNFL5U2xQ�['flowNumber'];
        $_obfuscate_6RYLWQ��['flowName'] = $_obfuscate_5LuNFL5U2xQ�['flowName'];
        $_obfuscate_6RYLWQ��['reason'] = $_obfuscate_5LuNFL5U2xQ�['reason'];
        $_obfuscate_6RYLWQ��['level'] = $this->f_level[$_obfuscate_5LuNFL5U2xQ�['level']];
        $_obfuscate_6RYLWQ��['status'] = $this->f_status[$_obfuscate_5LuNFL5U2xQ�['status']];
        $_obfuscate_6RYLWQ��['uname'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_5LuNFL5U2xQ�['uid'] );
        $_obfuscate_6RYLWQ��['posttime'] = formatdate( $_obfuscate_5LuNFL5U2xQ�['posttime'], "Y-m-d H:i" );
        $_obfuscate_6RYLWQ��['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
        $_obfuscate_6RYLWQ��['htmlFormContent'] = $_obfuscate_5LuNFL5U2xQ�['htmlFormContent'];
        $_obfuscate_F4AbnVRh = $_obfuscate_5LuNFL5U2xQ�['flowId'];
        ( $_obfuscate_TlvKhtsoOQ�� );
        $_obfuscate_e53ODz04JQ�� = new wfCache( );
        $_obfuscate_5NhzjnJq_f8� = $_obfuscate_e53ODz04JQ��->getStepByStepId( $_obfuscate_VBCv7Q�� );
        $_obfuscate_vzGArcjOKr8_7A�� = array( );
        $_obfuscate_vzGArcjOKr8_7A��['btnText'] = $this->f_btn_text[$_obfuscate_5NhzjnJq_f8�['doBtnText']];
        $_obfuscate_vzGArcjOKr8_7A��['allowReject'] = $_obfuscate_5NhzjnJq_f8�['allowReject'];
        $_obfuscate_vzGArcjOKr8_7A��['allowHuiqian'] = $_obfuscate_5NhzjnJq_f8�['allowHuiqian'];
        $_obfuscate_vzGArcjOKr8_7A��['allowFenfa'] = $_obfuscate_5NhzjnJq_f8�['allowFenfa'];
        $_obfuscate_vzGArcjOKr8_7A��['allowTuihui'] = $_obfuscate_5NhzjnJq_f8�['allowTuihui'];
        $_obfuscate_vzGArcjOKr8_7A��['allowAttachAdd'] = $_obfuscate_5NhzjnJq_f8�['allowAttachAdd'];
        $_obfuscate_vzGArcjOKr8_7A��['allowAttachView'] = $_obfuscate_5NhzjnJq_f8�['allowAttachView'];
        $_obfuscate_vzGArcjOKr8_7A��['allowAttachEdit'] = $_obfuscate_5NhzjnJq_f8�['allowAttachEdit'];
        $_obfuscate_vzGArcjOKr8_7A��['allowAttachDelete'] = $_obfuscate_5NhzjnJq_f8�['allowAttachDelete'];
        $_obfuscate_vzGArcjOKr8_7A��['allowAttachDown'] = $_obfuscate_5NhzjnJq_f8�['allowAttachDown'];
        $_obfuscate_vzGArcjOKr8_7A��['allowWordEdit'] = $_obfuscate_5NhzjnJq_f8�['allowWordEdit'];
        $_obfuscate_vzGArcjOKr8_7A��['allowAttachWordEdit'] = $_obfuscate_5NhzjnJq_f8�['allowAttachWordEdit'];
        $_obfuscate_vzGArcjOKr8_7A��['allowSms'] = $_obfuscate_5NhzjnJq_f8�['allowSms'];
        $_obfuscate_hYX302lGYvY5 = $CNOA_DB->db_getone( "*", $this->t_use_step, "WHERE `uStepId`='".$_obfuscate_VBCv7Q��."' AND `uFlowId`='{$_obfuscate_TlvKhtsoOQ��}'" );
        $_obfuscate_5ZL98vE� = $this->getProxyUid( $_obfuscate_F4AbnVRh, $_obfuscate_7Ri3 );
        if ( $_obfuscate_7Ri3 == $_obfuscate_hYX302lGYvY5['proxyUid'] || $_obfuscate_7Ri3 == $_obfuscate_5ZL98vE� )
        {
            $_obfuscate_vzGArcjOKr8_7A��['allowErust'] = 0;
        }
        else
        {
            $_obfuscate_vzGArcjOKr8_7A��['allowErust'] = 1;
        }
        $_obfuscate_LeS8hw�� = getpar( $_POST, "type", "" );
        if ( $_obfuscate_LeS8hw�� == "done" )
        {
            $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "uStepId" ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `uid` = '{$_obfuscate_7Ri3}' " );
            if ( !is_array( $_obfuscate_Tx7M9W ) )
            {
                $_obfuscate_Tx7M9W = array( );
            }
            $_obfuscate_QwT4KwrB2w�� = array( );
            foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_QwT4KwrB2w��[] = $_obfuscate_6A��['uStepId'];
            }
            $_obfuscate_vzGArcjOKr8_7A��['allowReject'] = 0;
            $_obfuscate_vzGArcjOKr8_7A��['allowAttachAdd'] = 0;
            $_obfuscate_vzGArcjOKr8_7A��['allowAttachEdit'] = 0;
            $_obfuscate_vzGArcjOKr8_7A��['allowAttachDelete'] = 0;
            $_obfuscate_vzGArcjOKr8_7A��['allowAttachView'] = 0;
            $_obfuscate_vzGArcjOKr8_7A��['allowAttachDown'] = 0;
            foreach ( $_obfuscate_e53ODz04JQ��->getStepInfoByIdArr( $_obfuscate_QwT4KwrB2w�� ) as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                if ( $_obfuscate_6A��['allowAttachView'] == 1 )
                {
                    $_obfuscate_vzGArcjOKr8_7A��['allowAttachView'] = 1;
                }
                if ( $_obfuscate_6A��['allowAttachDown'] == 1 )
                {
                    $_obfuscate_vzGArcjOKr8_7A��['allowAttachDown'] = 1;
                }
            }
        }
        $_obfuscate_5_QxeMo� = getpar( $_GET, "modul", "" );
        ( );
        $_obfuscate_2gg� = new fs( );
        if ( $_obfuscate_XkuTFqZ6Tmk� == 0 )
        {
            if ( $_obfuscate_5_QxeMo� == "view" )
            {
                $_obfuscate_vzGArcjOKr8_7A��['allowAttachAdd'] = 0;
                $_obfuscate_vzGArcjOKr8_7A��['allowAttachEdit'] = 0;
                $_obfuscate_vzGArcjOKr8_7A��['allowAttachDelete'] = 0;
                $_obfuscate_vzGArcjOKr8_7A��['allowAttachView'] = 1;
                $_obfuscate_vzGArcjOKr8_7A��['allowAttachDown'] = 1;
            }
            if ( $_obfuscate_5NhzjnJq_f8�['stepType'] == 1 )
            {
                $_obfuscate_vzGArcjOKr8_7A��['allowReject'] = 0;
                $_obfuscate_vzGArcjOKr8_7A��['allowHuiqian'] = 0;
                $_obfuscate_vzGArcjOKr8_7A��['allowTuihui'] = 0;
            }
            if ( $_obfuscate_vholQ�� == "mgr" )
            {
                $_obfuscate_vzGArcjOKr8_7A��['allowAttachView'] = 1;
                $_obfuscate_vzGArcjOKr8_7A��['allowAttachDown'] = 1;
            }
            $_obfuscate_8CpDPPa = $_obfuscate_2gg�->getListInfo4wf( json_decode( $_obfuscate_5LuNFL5U2xQ�['attach'], TRUE ), $_obfuscate_vzGArcjOKr8_7A��, TRUE, "deal" );
        }
        else
        {
            if ( $_obfuscate_LeS8hw�� == "done" )
            {
                $_obfuscate_vzGArcjOKr8_7A��['allowAttachEdit'] = 0;
            }
            else
            {
                $_obfuscate_vzGArcjOKr8_7A��['allowAttachEdit'] = 1;
            }
            $_obfuscate_vzGArcjOKr8_7A��['allowAttachDelete'] = 1;
            $_obfuscate_vzGArcjOKr8_7A��['allowAttachView'] = 1;
            $_obfuscate_vzGArcjOKr8_7A��['allowAttachDown'] = 1;
            if ( $_obfuscate_5_QxeMo� == "view" )
            {
                $_obfuscate_vzGArcjOKr8_7A��['allowAttachAdd'] = 0;
                $_obfuscate_vzGArcjOKr8_7A��['allowAttachEdit'] = 0;
                $_obfuscate_vzGArcjOKr8_7A��['allowAttachDelete'] = 0;
                $_obfuscate_vzGArcjOKr8_7A��['allowAttachView'] = 1;
                $_obfuscate_vzGArcjOKr8_7A��['allowAttachDown'] = 1;
            }
            if ( $_obfuscate_5NhzjnJq_f8�['stepType'] == 1 )
            {
                $_obfuscate_vzGArcjOKr8_7A��['allowReject'] = 0;
                $_obfuscate_vzGArcjOKr8_7A��['allowHuiqian'] = 0;
                $_obfuscate_vzGArcjOKr8_7A��['allowTuihui'] = 0;
            }
            if ( $_obfuscate_vholQ�� == "mgr" )
            {
                $_obfuscate_vzGArcjOKr8_7A��['allowAttachView'] = 1;
                $_obfuscate_vzGArcjOKr8_7A��['allowAttachDown'] = 1;
            }
            $_obfuscate_8CpDPPa = $_obfuscate_2gg�->getListInfo4wf( json_decode( $_obfuscate_5LuNFL5U2xQ�['attach'], TRUE ), $_obfuscate_vzGArcjOKr8_7A��, TRUE, "deal" );
        }
        if ( $_obfuscate_XkuTFqZ6Tmk� == 0 && $_obfuscate_pEvU7Kz2Yw�� == 0 )
        {
            $_obfuscate_urgydSw7IkMKIoqpA�� = $this->api_getWfFieldData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ�� );
            $_obfuscate_sAnybXE� = $CNOA_DB->db_select( array( "id" ), $this->t_set_field, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' AND `otype` = 'detailtable' " );
            if ( !empty( $_obfuscate_sAnybXE� ) )
            {
                $_obfuscate_ScER1S69bt_Qmg�� = array( );
                foreach ( $_obfuscate_sAnybXE� as $_obfuscate_6A�� )
                {
                    $_obfuscate_XNOBiCm6rWto5G4wFkJiVkUqo0�[] = $this->api_getWfFieldDetailData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_6A��['id'] );
                }
            }
        }
        $_obfuscate_0WaREsXoZ4w� = $this->api_getReadList( $_obfuscate_TlvKhtsoOQ�� );
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_6RYLWQ��;
        $_obfuscate_NlQ�->attach = $_obfuscate_8CpDPPa;
        $_obfuscate_NlQ�->readInfo = $_obfuscate_0WaREsXoZ4w�;
        if ( $_obfuscate_XkuTFqZ6Tmk� == 0 && $_obfuscate_pEvU7Kz2Yw�� == 0 )
        {
            $_obfuscate_NlQ�->wf_field_data = $_obfuscate_urgydSw7IkMKIoqpA��;
            $_obfuscate_NlQ�->wf_detail_field_data = $_obfuscate_XNOBiCm6rWto5G4wFkJiVkUqo0�;
        }
        $_obfuscate_NlQ�->config = $_obfuscate_vzGArcjOKr8_7A��;
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
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

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_mV9HBLY� = ( integer )getpar( $_GET, "start", getpar( $_POST, "start" ) );
        $_obfuscate_xvYeh9I� = ( integer )getpar( $_GET, "limit", getpar( $_POST, "limit", 15 ) );
        $_obfuscate_KYPe3Fn6DvBxA�� = getpar( $_GET, "flowNumber", getpar( $_POST, "flowNumber", "" ) );
        $_obfuscate_Bk2lGlk� = "";
        if ( !empty( $_obfuscate_KYPe3Fn6DvBxA�� ) )
        {
            $_obfuscate_Bk2lGlk� = "AND (f.flowNumber LIKE '%".$_obfuscate_KYPe3Fn6DvBxA��."%' OR f.flowName LIKE '%{$_obfuscate_KYPe3Fn6DvBxA��}%') ";
        }
        $_obfuscate_3y0Y = "FROM ".tname( $this->t_use_flow )." AS f LEFT JOIN ".tname( $this->t_set_flow )." AS  sf ON  sf.flowId =  f.flowId LEFT JOIN ".tname( $this->t_use_step )." AS  s ON  f.uFlowId =  s.uFlowId LEFT JOIN ".tname( $this->t_use_step_huiqian )." AS h ON f.uFlowId=h.uFlowId AND s.uStepId=h.stepId LEFT JOIN ".tname( $this->t_use_fenfa )." AS ff ON f.uFlowId=ff.uFlowId AND s.uStepId=ff.stepId LEFT JOIN ".tname( $this->main_user )."AS user ON f.uid=user.uid ".( "WHERE s.status=1 AND sf.flowType=0 AND sf.tplSort=0 AND (s.uid='".$_obfuscate_7Ri3."' OR s.proxyUid='{$_obfuscate_7Ri3}' OR (h.touid='{$_obfuscate_7Ri3}' AND h.status=0) OR (ff.touid='{$_obfuscate_7Ri3}' AND ff.status='0')) {$_obfuscate_Bk2lGlk�}" );
        $_obfuscate_bz5X7ZKzzw�� = "SELECT f.uFlowId, f.flowId, s.uStepId, f.flowName, f.flowNumber, f.uid, f.posttime, f.level, f.status AS `fstatus`, user.face, user.truename, h.touid AS `hqUid`, h.status AS `hqStatus`, ff.touid AS `ffUid`, ff.status AS `ffStatus` ".$_obfuscate_3y0Y."ORDER BY f.level DESC, f.updatetime DESC";
        $_obfuscate_mPAjEGLn = $CNOA_DB->query( $_obfuscate_bz5X7ZKzzw�� );
        $_obfuscate_8Bnz38wN01c� = $_obfuscate_uWfP0Bouw�� = $_obfuscate__eqrEQ�� = $_obfuscate_DZ6dPQKYRUM2jA�� = $_obfuscate_4JMFZ0YFHlXdrg�� = array( );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_mPAjEGLn ) )
        {
            if ( $_obfuscate_gkt['hqUid'] == $_obfuscate_7Ri3 )
            {
                $_obfuscate_DZ6dPQKYRUM2jA��[] = $_obfuscate_gkt['uFlowId'];
            }
            if ( $_obfuscate_gkt['ffUid'] == $_obfuscate_7Ri3 )
            {
                $_obfuscate_4JMFZ0YFHlXdrg��[] = $_obfuscate_gkt['uFlowId'];
            }
        }
        $_obfuscate_bz5X7ZKzzw�� = "SELECT f.uFlowId, f.flowId, s.uStepId, f.flowName, f.flowNumber, f.uid, f.posttime, f.level, f.status AS `fstatus`, user.face, user.truename, h.touid AS `hqUid`, h.status AS `hqStatus`, ff.touid AS `ffUid`, ff.status AS `ffStatus` ".$_obfuscate_3y0Y.( "ORDER BY f.level DESC, f.updatetime DESC LIMIT ".$_obfuscate_mV9HBLY�.", {$_obfuscate_xvYeh9I�}" );
        $_obfuscate_mPAjEGLn = $CNOA_DB->query( $_obfuscate_bz5X7ZKzzw�� );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_uWfP0Bouw��[] = $_obfuscate_gkt['flowId'];
            $_obfuscate__eqrEQ��[] = $_obfuscate_gkt['uid'];
            $_obfuscate_8Bnz38wN01c�[] = $_obfuscate_gkt;
        }
        unset( $_obfuscate_mPAjEGLn );
        if ( !empty( $_obfuscate__eqrEQ�� ) )
        {
            $_obfuscate_Ni1jOphVejk� = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate__eqrEQ�� );
        }
        $_obfuscate_7qDAYo85aGA� = array( );
        if ( !empty( $_obfuscate_uWfP0Bouw�� ) )
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "flowId", "tplSort", "flowType" ), $this->t_set_flow, "WHERE `flowId` IN (".implode( ",", $_obfuscate_uWfP0Bouw�� ).")" );
            if ( !is_array( $_obfuscate_mPAjEGLn ) )
            {
                $_obfuscate_mPAjEGLn = array( );
            }
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A�� )
            {
                $_obfuscate_7qDAYo85aGA�[$_obfuscate_6A��['flowId']] = $_obfuscate_6A��;
            }
        }
        if ( !is_array( $_obfuscate_8Bnz38wN01c� ) )
        {
            $_obfuscate_8Bnz38wN01c� = array( );
        }
        foreach ( $_obfuscate_8Bnz38wN01c� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_6A��['userName'] = empty( $_obfuscate_Ni1jOphVejk�[$_obfuscate_6A��['uid']] ) ? "<span style=\"color:#FF6600\">用户不存在</span>" : $_obfuscate_Ni1jOphVejk�[$_obfuscate_6A��['uid']];
            $_obfuscate_6A��['posttime'] = formatdate( $_obfuscate_6A��['posttime'], "Y-m-d H:i" );
            $_obfuscate_6A��['tplSort'] = $_obfuscate_7qDAYo85aGA�[$_obfuscate_6A��['flowId']]['tplSort'];
            $_obfuscate_6A��['flowType'] = $_obfuscate_7qDAYo85aGA�[$_obfuscate_6A��['flowId']]['flowType'];
            $_obfuscate_6A��['toHQ'] = 0;
            $_obfuscate_6A��['toFenfa'] = 0;
            $_obfuscate_6A��['status'] = "other";
            if ( in_array( $_obfuscate_6A��['uFlowId'], $_obfuscate_DZ6dPQKYRUM2jA�� ) && $_obfuscate_6A��['hqStatus'] == 0 )
            {
                $_obfuscate_6A��['toHQ'] = 1;
            }
            if ( in_array( $_obfuscate_6A��['uFlowId'], $_obfuscate_4JMFZ0YFHlXdrg�� ) && $_obfuscate_6A��['ffStatus'] == 0 )
            {
                $_obfuscate_6A��['toFenfa'] = 1;
            }
            if ( $_obfuscate_6A��['toFenfa'] == 0 && $_obfuscate_6A��['toHQ'] == 0 )
            {
                $_obfuscate_6A��['status'] = "todo";
            }
            if ( $_obfuscate_6A��['level'] == 0 )
            {
                $_obfuscate_6A��['level'] = "<span style=\"color: green\">普通</span>";
            }
            else if ( $_obfuscate_6A��['level'] == 1 )
            {
                $_obfuscate_6A��['level'] = "<span style=\"color: orange\">重要</span>";
            }
            else if ( $_obfuscate_6A��['level'] == 2 )
            {
                $_obfuscate_6A��['level'] = "<span style=\"color: red\">非常重要</span>";
            }
            if ( $_obfuscate_6A��['fstatus'] == 0 )
            {
                $_obfuscate_6A��['fstatus'] = "未发布";
            }
            else if ( $_obfuscate_6A��['fstatus'] == 1 )
            {
                $_obfuscate_6A��['fstatus'] = "办理中";
            }
            else if ( $_obfuscate_6A��['fstatus'] == 2 )
            {
                $_obfuscate_6A��['fstatus'] = "已办理";
            }
            else if ( $_obfuscate_6A��['fstatus'] == 3 )
            {
                $_obfuscate_6A��['fstatus'] = "已退件";
            }
            else if ( $_obfuscate_6A��['fstatus'] == 4 )
            {
                $_obfuscate_6A��['fstatus'] = "已撤销";
            }
            else if ( $_obfuscate_6A��['fstatus'] == 5 )
            {
                $_obfuscate_6A��['fstatus'] = "已删除";
            }
            else if ( $_obfuscate_6A��['fstatus'] == 6 )
            {
                $_obfuscate_6A��['fstatus'] = "已中止";
            }
            if ( empty( $_obfuscate_6A��['face'] ) )
            {
                $_obfuscate_6A��['face'] = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
            }
            else
            {
                $_obfuscate_b9_qaEFdaQ�� = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/".$_obfuscate_7Ri3."/130x130_".$_obfuscate_6A��['face'];
                if ( file_exists( $_obfuscate_b9_qaEFdaQ�� ) )
                {
                    $_obfuscate_6A��['face'] = $_obfuscate_b9_qaEFdaQ��;
                }
                else
                {
                    $_obfuscate_6A��['face'] = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
                }
            }
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��] = $_obfuscate_6A��;
        }
        $_obfuscate_oDHJs415kPU� = "SELECT count(`f`.`flowId`) AS `count` ".$_obfuscate_3y0Y;
        $_obfuscate_mPAjEGLn = $CNOA_DB->query( $_obfuscate_oDHJs415kPU� );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_gftfagw� = $_obfuscate_gkt['count'];
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_8Bnz38wN01c�;
        $_obfuscate_SUjPN94Er7yI->total = $_obfuscate_gftfagw�;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _getJsonDataNew( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_mV9HBLY� = ( integer )getpar( $_GET, "start", getpar( $_POST, "start" ) );
        $_obfuscate_xvYeh9I� = ( integer )getpar( $_GET, "limit", getpar( $_POST, "limit", 15 ) );
        $_obfuscate_dcwitxb = getpar( $_GET, "search", getpar( $_POST, "search", "" ) );
        $_obfuscate_Bk2lGlk� = "";
        if ( !empty( $_obfuscate_dcwitxb ) )
        {
            $_obfuscate_Bk2lGlk� = "AND (f.flowNumber LIKE '%".$_obfuscate_dcwitxb."%' OR f.flowName LIKE '%{$_obfuscate_dcwitxb}%') ";
        }
        $_obfuscate_3y0Y = "FROM ".tname( $this->t_use_flow )." AS f LEFT JOIN ".tname( $this->t_set_flow )." AS sf ON sf.flowId = f.flowId LEFT JOIN ".tname( $this->t_use_step )." AS s ON f.uFlowId = s.uFlowId LEFT JOIN ".tname( $this->main_user )."AS user ON f.uid=user.uid ".( "WHERE s.status=1 AND sf.flowType=0 AND sf.tplSort=0 AND (s.uid='".$_obfuscate_7Ri3."' OR s.proxyUid='{$_obfuscate_7Ri3}') {$_obfuscate_Bk2lGlk�}" );
        $_obfuscate_bz5X7ZKzzw�� = "SELECT f.uFlowId, f.flowId, s.uStepId, f.flowName, f.flowNumber, f.uid, f.posttime, f.level, f.status AS `fstatus`, user.face, user.truename ".$_obfuscate_3y0Y.( "GROUP BY f.uFlowId DESC ORDER BY f.level DESC, f.updatetime DESC LIMIT ".$_obfuscate_mV9HBLY�.", {$_obfuscate_xvYeh9I�}" );
        $_obfuscate_mPAjEGLn = $CNOA_DB->query( $_obfuscate_bz5X7ZKzzw�� );
        $_obfuscate_8Bnz38wN01c� = $_obfuscate_uWfP0Bouw�� = $_obfuscate__eqrEQ�� = $_obfuscate_DZ6dPQKYRUM2jA�� = $_obfuscate_4JMFZ0YFHlXdrg�� = array( );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_uWfP0Bouw��[] = $_obfuscate_gkt['flowId'];
            $_obfuscate__eqrEQ��[] = $_obfuscate_gkt['uid'];
            $_obfuscate_8Bnz38wN01c�[] = $_obfuscate_gkt;
        }
        unset( $_obfuscate_mPAjEGLn );
        if ( !empty( $_obfuscate__eqrEQ�� ) )
        {
            $_obfuscate_Ni1jOphVejk� = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate__eqrEQ�� );
        }
        $_obfuscate_7qDAYo85aGA� = array( );
        if ( !empty( $_obfuscate_uWfP0Bouw�� ) )
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "flowId", "tplSort", "flowType" ), $this->t_set_flow, "WHERE `flowId` IN (".implode( ",", $_obfuscate_uWfP0Bouw�� ).")" );
            if ( !is_array( $_obfuscate_mPAjEGLn ) )
            {
                $_obfuscate_mPAjEGLn = array( );
            }
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A�� )
            {
                $_obfuscate_7qDAYo85aGA�[$_obfuscate_6A��['flowId']] = $_obfuscate_6A��;
            }
        }
        if ( !is_array( $_obfuscate_8Bnz38wN01c� ) )
        {
            $_obfuscate_8Bnz38wN01c� = array( );
        }
        foreach ( $_obfuscate_8Bnz38wN01c� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_6A��['userName'] = empty( $_obfuscate_Ni1jOphVejk�[$_obfuscate_6A��['uid']] ) ? "<span style=\"color:#FF6600\">用户不存在</span>" : $_obfuscate_Ni1jOphVejk�[$_obfuscate_6A��['uid']];
            $_obfuscate_6A��['posttime'] = formatdate( $_obfuscate_6A��['posttime'], "Y-m-d H:i" );
            $_obfuscate_6A��['tplSort'] = $_obfuscate_7qDAYo85aGA�[$_obfuscate_6A��['flowId']]['tplSort'];
            $_obfuscate_6A��['flowType'] = $_obfuscate_7qDAYo85aGA�[$_obfuscate_6A��['flowId']]['flowType'];
            $_obfuscate_6A��['toHQ'] = 0;
            $_obfuscate_6A��['toFenfa'] = 0;
            $_obfuscate_6A��['status'] = "other";
            if ( $_obfuscate_6A��['toFenfa'] == 0 && $_obfuscate_6A��['toHQ'] == 0 )
            {
                $_obfuscate_6A��['status'] = "todo";
            }
            if ( $_obfuscate_6A��['level'] == 0 )
            {
                $_obfuscate_6A��['level'] = "<span style=\"color: green\">普通</span>";
            }
            else if ( $_obfuscate_6A��['level'] == 1 )
            {
                $_obfuscate_6A��['level'] = "<span style=\"color: orange\">重要</span>";
            }
            else if ( $_obfuscate_6A��['level'] == 2 )
            {
                $_obfuscate_6A��['level'] = "<span style=\"color: red\">非常重要</span>";
            }
            if ( $_obfuscate_6A��['fstatus'] == 0 )
            {
                $_obfuscate_6A��['fstatus'] = "未发布";
            }
            else if ( $_obfuscate_6A��['fstatus'] == 1 )
            {
                $_obfuscate_6A��['fstatus'] = "办理中";
            }
            else if ( $_obfuscate_6A��['fstatus'] == 2 )
            {
                $_obfuscate_6A��['fstatus'] = "已办理";
            }
            else if ( $_obfuscate_6A��['fstatus'] == 3 )
            {
                $_obfuscate_6A��['fstatus'] = "已退件";
            }
            else if ( $_obfuscate_6A��['fstatus'] == 4 )
            {
                $_obfuscate_6A��['fstatus'] = "已撤销";
            }
            else if ( $_obfuscate_6A��['fstatus'] == 5 )
            {
                $_obfuscate_6A��['fstatus'] = "已删除";
            }
            else if ( $_obfuscate_6A��['fstatus'] == 6 )
            {
                $_obfuscate_6A��['fstatus'] = "已中止";
            }
            if ( empty( $_obfuscate_6A��['face'] ) )
            {
                $_obfuscate_6A��['face'] = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
            }
            else
            {
                $_obfuscate_b9_qaEFdaQ�� = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/".$_obfuscate_7Ri3."/120x160_".$_obfuscate_6A��['face'];
                if ( file_exists( $_obfuscate_b9_qaEFdaQ�� ) )
                {
                    $_obfuscate_6A��['face'] = $_obfuscate_b9_qaEFdaQ��;
                }
                else
                {
                    $_obfuscate_6A��['face'] = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
                }
            }
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_5w��] = $_obfuscate_6A��;
        }
        $_obfuscate_oDHJs415kPU� = "SELECT count(`f`.`flowId`) AS `count` ".$_obfuscate_3y0Y;
        $_obfuscate_mPAjEGLn = $CNOA_DB->query( $_obfuscate_oDHJs415kPU� );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_gftfagw� = $_obfuscate_gkt['count'];
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_8Bnz38wN01c�;
        $_obfuscate_SUjPN94Er7yI->total = $_obfuscate_gftfagw�;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _getSendNextData( )
    {
        app::loadapp( "wf", "flowUseTodo" )->api_getSendNextData( );
    }

    private function _sendNextStep( )
    {
        $_obfuscate_9ga6vjaQ61MybPYk = explode( ",", getpar( $_POST, "upload_attach" ) );
        $_obfuscate_8CpDPPa = array( );
        if ( 0 < count( $_obfuscate_9ga6vjaQ61MybPYk ) )
        {
            ( );
            $_obfuscate_2gg� = new fs( );
            $_obfuscate_8CpDPPa = $_obfuscate_2gg�->add( $_obfuscate_9ga6vjaQ61MybPYk, 1 );
            foreach ( $_obfuscate_8CpDPPa as $_obfuscate_2PfU )
            {
                $GLOBALS['_POST']["wf_attach_".$_obfuscate_2PfU] = 1;
            }
        }
        unset( $_POST['upload_attach'] );
        ( );
        $_obfuscate_KU1umg5BJ4xlQyU7RBJvODtb = new wfTodoSendNextStep( );
        msg::callback( TRUE, lang( "flowDealSuccess" ) );
    }

    private function _uploadfile( )
    {
        ( );
        $_obfuscate_o5n931n9CIU� = new stdClass( );
        $_obfuscate_o5n931n9CIU�->success = FALSE;
        if ( $_FILES['userfile']['error'] !== UPLOAD_ERR_OK )
        {
            $_obfuscate_o5n931n9CIU�->message = lang( "uploadFail" );
        }
        else if ( isimage( $_FILES['userfile']['name'] ) )
        {
            $_obfuscate_3gn_eQ�� = $_FILES['userfile']['name'];
            $_obfuscate_zo8F = substr( $_obfuscate_3gn_eQ��, strrpos( $_obfuscate_3gn_eQ��, "." ) );
            $_obfuscate_7tmDAr7acvgDxw�� = $GLOBALS['URL_FILE']."/editpic/".md5( time( ) )."_".string::rands( 10 ).$_obfuscate_zo8F;
            if ( is_uploaded_file( $_FILES['userfile']['tmp_name'] ) && cnoa_move_uploaded_file( $_FILES['userfile']['tmp_name'], $_obfuscate_7tmDAr7acvgDxw�� ) )
            {
                $_obfuscate_o5n931n9CIU�->success = TRUE;
                $_obfuscate_o5n931n9CIU�->message = $_obfuscate_7tmDAr7acvgDxw��;
            }
            else
            {
                $_obfuscate_o5n931n9CIU�->message = lang( "uploadFail" );
            }
        }
        else
        {
            $_obfuscate_o5n931n9CIU�->message = lang( "fileFormarNotMeet" );
        }
        echo json_encode( $_obfuscate_o5n931n9CIU� );
    }

    private function _uploadAttach( )
    {
        echo json_encode( uploadattach4m( ) );
    }

    private function _submitRejectAbout( )
    {
        app::loadapp( "wf", "flowUseTodo" )->api_submitRejectAbout( );
    }

    private function _loadPrevstepData( )
    {
        $_obfuscate_6RYLWQ�� = app::loadapp( "wf", "flowUseTodo" )->api_loadPrevstepData( );
        if ( !is_array( $_obfuscate_6RYLWQ�� ) )
        {
            $_obfuscate_6RYLWQ�� = array( );
        }
        $_obfuscate_j9eamhY� = array( );
        foreach ( $_obfuscate_6RYLWQ�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_j9eamhY�[] = array(
                "xtype" => "radiofield",
                "name" => "ustepId",
                "label" => $_obfuscate_6A��['boxLabel'],
                "value" => $_obfuscate_6A��['inputValue'],
                "checked" => $_obfuscate_5w�� ? FALSE : TRUE
            );
            if ( isset( $_obfuscate_6A��['bingfaChild'] ) )
            {
                foreach ( $_obfuscate_6A��['bingfaChild'] as $_obfuscate_eBU_Sjc� )
                {
                    $_obfuscate_j9eamhY�[] = array(
                        "xtype" => "radiofield",
                        "name" => "ustepId",
                        "label" => $_obfuscate_eBU_Sjc�['boxLabel'],
                        "value" => $_obfuscate_eBU_Sjc�['inputValue'],
                        "checked" => $_obfuscate_5w�� ? FALSE : TRUE
                    );
                }
            }
        }
        echo json_encode( $_obfuscate_j9eamhY� );
    }

    private function _loadPrevstepDataNew( )
    {
        $_obfuscate_6RYLWQ�� = app::loadapp( "wf", "flowUseTodo" )->api_loadPrevstepData( );
        if ( !is_array( $_obfuscate_6RYLWQ�� ) )
        {
            $_obfuscate_6RYLWQ�� = array( );
        }
        $_obfuscate_j9eamhY� = array( );
        foreach ( $_obfuscate_6RYLWQ�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_j9eamhY�[] = array(
                "xtype" => "radiofield",
                "name" => "ustepId",
                "label" => $_obfuscate_6A��['boxLabel'],
                "value" => $_obfuscate_6A��['inputValue'],
                "checked" => $_obfuscate_5w�� ? FALSE : TRUE
            );
            if ( isset( $_obfuscate_6A��['bingfaChild'] ) )
            {
                foreach ( $_obfuscate_6A��['bingfaChild'] as $_obfuscate_eBU_Sjc� )
                {
                    $_obfuscate_j9eamhY�[] = array(
                        "xtype" => "radiofield",
                        "name" => "ustepId",
                        "label" => $_obfuscate_eBU_Sjc�['boxLabel'],
                        "value" => $_obfuscate_eBU_Sjc�['inputValue'],
                        "checked" => $_obfuscate_5w�� ? FALSE : TRUE
                    );
                }
            }
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_j9eamhY�;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _submitPrevstepData( )
    {
        app::loadapp( "wf", "flowUseTodo" )->api_submitPrevstepData( );
    }

    private function _showDetailTable( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        $flowId = getpar( $_GET, "flowId" );
        $uFlowId = getpar( $_GET, "uFlowId" );
        $tableId = getpar( $_GET, "tableId" );
        $nowStep = $CNOA_DB->db_getfield( "uStepId", $this->t_use_step, "WHERE `uFlowId`='".$uFlowId."' ORDER BY `id` DESC" );
        ( $uFlowId );
        $GLOBALS['GLOBALS']['UWFCACHE'] = new wfCache( );
        $field = $GLOBALS['UWFCACHE']->getFlowFields( );
        foreach ( $field as $value )
        {
            if ( !( $value['id'] == $tableId ) )
            {
                continue;
            }
            $detailName = $value['name'];
            break;
        }
        ( $flowId, $nowStep, FALSE, "show", $uFlowId );
        $wfFormHtmlForView = new wfFieldFormaterForView( );
        $detailFormHtml = $wfFormHtmlForView->crteateFormHtml( );
        include( CNOA_PATH_CLASS."/phpQuery.class.php" );
        $doc = phpQuery::newdocumenthtml( $detailFormHtml );
        $htmlText = pq( "tr[detailtableid=".$tableId."]" )->parent( )->parent( )->html( );
        include( $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/showDetailTableM.htm" );
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
        $_obfuscate_ELA8NXksz6D4NeGh = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_QabuumMSpAVzrvaOA�� );
        $_obfuscate_1XvASPFcSAJ6MqwjC4F = array( );
        foreach ( $_obfuscate_ELA8NXksz6D4NeGh as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_1XvASPFcSAJ6MqwjC4F[$_obfuscate_6A��['uid']]['name'] = $_obfuscate_6A��['truename'];
        }
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
            $_obfuscate_6RYLWQ��['truename'] = $_obfuscate_1XvASPFcSAJ6MqwjC4F[$_obfuscate_6A��]['name'];
            $_obfuscate_LvAlJbKidRGZ = $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_use_step_huiqian );
            $_obfuscate_gb3bCas1['content'] = lang( "flowName" ).( "[".$_obfuscate_7qDAYo85aGA�['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_7qDAYo85aGA�['flowNumber']."]" ).lang( "needYouSign" );
            $_obfuscate_gb3bCas1['href'] = "&uFlowId=".$_obfuscate_6RYLWQ��['uFlowId']."&flowId={$_obfuscate_7qDAYo85aGA�['flowId']}&step={$_obfuscate_6RYLWQ��['stepId']}&flowType={$_obfuscate_7qDAYo85aGA�['flowType']}&tplSort={$_obfuscate_7qDAYo85aGA�['tplSort']}";
            $_obfuscate_gb3bCas1['fromid'] = $_obfuscate_LvAlJbKidRGZ;
            $this->addNotice( "both", $_obfuscate_6RYLWQ��['touid'], $_obfuscate_gb3bCas1, "huiqian" );
            $_obfuscate_HweFzDn2[] = $_obfuscate_6RYLWQ��['truename'];
        }
        if ( 0 < count( $_obfuscate_HweFzDn2 ) )
        {
            $_obfuscate_HweFzDn2 = implode( ",", $_obfuscate_HweFzDn2 );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3604, lang( "signPersonnel" ).( "[ ".$_obfuscate_HweFzDn2." ]，" ).lang( "flowName" ).( "[ ".$_obfuscate_7qDAYo85aGA�['flowName']." ]" ).lang( "bianHao" ).( "[ ".$_obfuscate_7qDAYo85aGA�['flowNumber']." ]" ) );
        }
        msg::callback( TRUE, lang( "successopt" ) );
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
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $CNOA_DB->db_update( array(
            "message" => $_obfuscate_FYJCcRzosA��,
            "writetime" => $GLOBALS['CNOA_TIMESTAMP'],
            "status" => 1
        ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' AND `touid` = '{$_obfuscate_7Ri3}'" );
        $_obfuscate_o5fQ1g�� = $CNOA_DB->db_getone( array( "id", "truename", "uid", "uFlowId" ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' AND `touid` = '{$_obfuscate_7Ri3}' " );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowId" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        $this->doneAll( "both", $_obfuscate_o5fQ1g��['id'], "huiqian" );
        $_obfuscate_AVrjaAn6 = $CNOA_DB->db_getone( array( "id" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId` = '{$_obfuscate_0Ul8BBkt}'" );
        $_obfuscate_0AITFw��['content'] = lang( "YouhaveFlow" )."[".$_obfuscate_o5fQ1g��['truename']."]".lang( "haveSubmitSign" );
        $_obfuscate_0AITFw��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_GB1J1EM4}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
        $_obfuscate_0AITFw��['fromid'] = $_obfuscate_AVrjaAn6['id'];
        $this->addNotice( "notice", array(
            $_obfuscate_o5fQ1g��['uid']
        ), $_obfuscate_0AITFw��, "huiqian", 1 );
        $_obfuscate_JG8GuY� = array( );
        $_obfuscate_JG8GuY�['type'] = 11;
        $_obfuscate_JG8GuY�['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
        $_obfuscate_JG8GuY�['step'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_JG8GuY�['stepname'] = "会签";
        $_obfuscate_Vm9G3dw�['say'] = $_obfuscate_FYJCcRzosA��;
        $_obfuscate_JG8GuY�['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuY�['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $this->insertEvent( $_obfuscate_JG8GuY� );
        msg::callback( TRUE, lang( "successopt" ) );
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
        $_obfuscate_O6ZGVA�� = array( );
        $_obfuscate_6RYLWQ��['fromuid'] = $_obfuscate_7Ri3;
        $_obfuscate_6RYLWQ��['touid'] = getpar( $_POST, "touid", 0 );
        $_obfuscate_6RYLWQ��['uFlowId'] = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_6RYLWQ��['flowId'] = getpar( $_POST, "flowId", 0 );
        $_obfuscate_6RYLWQ��['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_1l6P = getpar( $_POST, "say", "无" );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_SIUSR4F6 = $CNOA_DB->db_getone( "*", $this->t_use_proxy_uflow, "WHERE `fromuid`='".$_obfuscate_6RYLWQ��['fromuid']."' AND (`flowId` !=0 AND `flowId`='{$_obfuscate_6RYLWQ��['flowId']}')" );
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
            $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_6RYLWQ��['uFlowId']."' " );
            $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "id" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_6RYLWQ��['uFlowId']."' AND `uStepId` = '{$_obfuscate_ecqaH_ev7A��}' " );
            $this->deleteNotice( "both", $_obfuscate_Tx7M9W['id'], "todo" );
            $_obfuscate_0AITFw��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "by" )."[".$CNOA_SESSION->get( "TRUENAME" )."][".$_obfuscate_1l6P."]";
            $_obfuscate_0AITFw��['href'] = "&uFlowId=".$_obfuscate_6RYLWQ��['uFlowId']."&flowId={$_obfuscate_6RYLWQ��['flowId']}&step={$_obfuscate_ecqaH_ev7A��}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_71iMkk6t0w��}";
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
        $_obfuscate_JG8GuY�['stepname'] = $CNOA_DB->db_getfield( "stepName", $this->t_set_step, "WHERE `flowId`='".$_obfuscate_6RYLWQ��['flowId']."' AND `uStepId`='{$_obfuscate_ecqaH_ev7A��}'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3604, lang( "entrustFlowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
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
            $_obfuscate_gkt['isread'] = $_obfuscate_gkt['isread'] == 0 ? "未阅读" : "已阅读";
            $_obfuscate_6RYLWQ��[] = $_obfuscate_gkt;
        }
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_6RYLWQ��;
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function _addFenfa( $_obfuscate_TlvKhtsoOQ�� = "", $_obfuscate_rHwsX0gg = array( ), $_obfuscate_CRya7qfm = FALSE )
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
        $_obfuscate_sZ_Wvha1 = $CNOA_DB->db_select( array( "touid" ), $this->t_use_fenfa, "WHERE `uflowId`=".$_obfuscate_TlvKhtsoOQ�� );
        if ( !is_array( $_obfuscate_sZ_Wvha1 ) )
        {
            $_obfuscate_sZ_Wvha1 = array( );
        }
        $_obfuscate_u9gCXhG9Sg�� = array( );
        foreach ( $_obfuscate_sZ_Wvha1 as $_obfuscate_6A�� )
        {
            $_obfuscate_u9gCXhG9Sg��[] = $_obfuscate_6A��['touid'];
        }
        $_obfuscate_3y0Y = "SELECT u.flowName, u.flowNumber, u.flowId, s.flowType, s.tplSort FROM ".tname( $this->t_use_flow )."AS u LEFT JOIN ".tname( $this->t_set_flow )."AS s ON s.flowId=u.flowId".( "WHERE u.uFlowId=".$_obfuscate_TlvKhtsoOQ��." LIMIT 0, 1" );
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
            $_obfuscate_6RYLWQ��['content'] = lang( "flowName" ).( "[".$_obfuscate_7qDAYo85aGA�['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_7qDAYo85aGA�['flowNumber']."]分发给你阅读。" );
            $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_7qDAYo85aGA�['flowId']}&flowType={$_obfuscate_7qDAYo85aGA�['flowType']}&tplSort={$_obfuscate_7qDAYo85aGA�['tplSort']}";
            $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_Ce9h;
            $this->addNotice( "notice", $_obfuscate_rLpOghzteElp['touid'], $_obfuscate_6RYLWQ��, "fenfa" );
            ++$_obfuscate_Ybai;
        }
        if ( 0 < $_obfuscate_Ybai )
        {
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3604, "分发人员，流程名称{".$_obfuscate_7qDAYo85aGA�['flowName']."}".lang( "bianHao" ).( "[".$_obfuscate_7qDAYo85aGA�['flowNumber']."]" ) );
        }
        if ( !$_obfuscate_CRya7qfm )
        {
            msg::callback( TRUE, lang( "successopt" ) );
        }
    }

    private function _addReaderSay( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_6RYLWQ��['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_6RYLWQ��['isread'] = 1;
        $_obfuscate_6RYLWQ��['say'] = getpar( $_POST, "say", "" );
        $_obfuscate_6RYLWQ��['viewtime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_6RYLWQ��['status'] = 1;
        $_obfuscate_Thg� = $CNOA_DB->db_getone( array( "fenfauid" ), $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `touid`='{$_obfuscate_7Ri3}'" );
        $_obfuscate_W3cYj7TbmC9r = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_Thg�['fenfauid'] );
        if ( !empty( $_obfuscate_Thg� ) )
        {
            $CNOA_DB->db_update( $_obfuscate_6RYLWQ��, $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `touid`='{$_obfuscate_7Ri3}'" );
            $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber", "flowId" ), $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' " );
            $_obfuscate_0AITFw��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "flowBeenReview" );
            $_obfuscate_0AITFw��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
            $_obfuscate_0AITFw��['fromid'] = $_obfuscate_0Ul8BBkt;
            $this->addNotice( "notice", $_obfuscate_Thg�['fenfauid'], $_obfuscate_0AITFw��, "comment" );
        }
        echo json_encode( array(
            "success" => TRUE,
            "msg" => lang( "successopt" ),
            "fenfaName" => $_obfuscate_W3cYj7TbmC9r
        ) );
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
        echo "<table width='100%' border='0' cellspacing='1' cellpadding='0' style='border-collapse: collapse;'>\r\n\t\t\t\t\t<tr>\r\n\t\t\t\t\t\t<td style='border:1px solid #999; padding: 2px; background:#CDCDCD;'>步骤名称</td>\r\n\t\t\t\t\t\t<td style='border:1px solid #999; padding: 2px; background:#CDCDCD;'>状态</td>\r\n\t\t\t\t\t\t<td style='border:1px solid #999; padding: 2px; background:#CDCDCD;'>经办人、开始时间</td>\r\n\t\t\t\t\t\t<td style='border:1px solid #999; padding: 2px; background:#CDCDCD;'>持续时间</td>\r\n\t\t\t\t\t\t<td style='border:1px solid #999; padding: 2px; background:#CDCDCD;'>办理理由</td>\r\n\t\t\t\t\t</tr>\r\n\t\t\t";
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A�� )
        {
            echo "<tr>";
            echo "<td style='border:1px solid #999; padding: 2px;'>".$_obfuscate_6A��['stepname']."</td>";
            echo "<td style='border:1px solid #999; padding: 2px;'>".$_obfuscate_6A��['statusText']."</td>";
            echo "<td style='border:1px solid #999; padding: 2px;'>".$_obfuscate_6A��['uname']."<br />{$_obfuscate_6A��['formatStime']}</td>";
            echo "<td style='border:1px solid #999; padding: 2px;'>".$_obfuscate_6A��['utime']."</td>";
            echo "<td style='border:1px solid #999; padding: 2px;'>".$_obfuscate_6A��['say']."</td>";
            echo "</tr>";
        }
        echo "</table>";
        exit( );
    }

    private function _getStepLists( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_GET, "uFlowId", getpar( $_POST, "uFlowId", 0 ) );
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", getpar( $_POST, "flowId", 0 ) );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_GET, "flowType", getpar( $_POST, "flowType", 0 ) );
        $_obfuscate_mPAjEGLn = $this->api_getStepList( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmk� );
        $_obfuscate_j9eamhY� = array( );
        if ( is_array( $_obfuscate_mPAjEGLn ) )
        {
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_j9eamhY�[$_obfuscate_5w��]['utime'] = $_obfuscate_6A��['utime'];
                $_obfuscate_j9eamhY�[$_obfuscate_5w��]['stepname'] = $_obfuscate_6A��['stepname'];
                $_obfuscate_j9eamhY�[$_obfuscate_5w��]['uname'] = $_obfuscate_6A��['uname'];
                $_obfuscate_j9eamhY�[$_obfuscate_5w��]['formatStime'] = $_obfuscate_6A��['formatStime'];
                $_obfuscate_j9eamhY�[$_obfuscate_5w��]['statusText'] = $_obfuscate_6A��['statusText'];
                $_obfuscate_j9eamhY�[$_obfuscate_5w��]['say'] = $_obfuscate_6A��['say'];
            }
        }
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_j9eamhY�;
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function _cuiban( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_ecqaH_ev7A�� = getpar( $_POST, "uStepId", 0 );
        $_obfuscate__WwKzYz1wA�� = getpar( $_POST, "content", "" );
        $_obfuscate_NS44QYk� = $CNOA_SESSION->get( "TRUENAME" );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowNumber", "flowName", "flowId" ), $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $_obfuscate_7qDAYo85aGA� = $CNOA_DB->db_getone( array( "tplSort", "flowType" ), $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_hTew0boWJESy['flowId']."'" );
        $_obfuscate_5NhzjnJq_f8� = $CNOA_DB->db_getone( array( "id", "uid" ), $this->t_use_step, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ��." AND `uStepId`={$_obfuscate_ecqaH_ev7A��}" );
        $_obfuscate_lQ81YBM� = $this->getProxyUid( $_obfuscate_hTew0boWJESy['flowId'], $_obfuscate_5NhzjnJq_f8�['uid'] );
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_6RYLWQ��['content'] = $_obfuscate_NS44QYk�.lang( "flowDealRemin" ).":".$_obfuscate__WwKzYz1wA��;
        $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_7qDAYo85aGA�['flowId']}&step={$_obfuscate_ecqaH_ev7A��}&flowType={$_obfuscate_7qDAYo85aGA�['flowType']}&tplSort={$_obfuscate_7qDAYo85aGA�['tplSort']}";
        $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_5NhzjnJq_f8�['id'];
        $this->addNotice( "notice", array(
            $_obfuscate_5NhzjnJq_f8�['uid'],
            $_obfuscate_lQ81YBM�
        ), $_obfuscate_6RYLWQ��, "warn" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3631, lang( "remindFlow" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getEventList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", 0 );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_GET, "flowType", 0 );
        $_obfuscate_mPAjEGLn = $this->api_getEventList( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmk� );
        echo "<table width='100%' border='0' cellspacing='1' cellpadding='0' style='border-collapse: collapse;'>\r\n\t\t\t\t\t<tr>\r\n\t\t\t\t\t\t<td style='border:1px solid #999; padding: 2px; background:#CDCDCD;'>类型</td>\r\n\t\t\t\t\t\t<td style='border:1px solid #999; padding: 2px; background:#CDCDCD;'>步骤</td>\r\n\t\t\t\t\t\t<td style='border:1px solid #999; padding: 2px; background:#CDCDCD;'>经办人、时间</td>\r\n\t\t\t\t\t\t<td style='border:1px solid #999; padding: 2px; background:#CDCDCD;'>办理理由</td>\r\n\t\t\t\t\t</tr>\r\n\t\t\t";
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A�� )
        {
            echo "<tr>";
            echo "<td style='border:1px solid #999; padding: 2px;'>".$_obfuscate_6A��['typename']."</td>";
            echo "<td style='border:1px solid #999; padding: 2px;'>".$_obfuscate_6A��['stepname']."</td>";
            echo "<td style='border:1px solid #999; padding: 2px;'>".$_obfuscate_6A��['uname']."<br />{$_obfuscate_6A��['posttime']}</td>";
            echo "<td style='border:1px solid #999; padding: 2px;'>".$_obfuscate_6A��['say']."</td>";
            echo "</tr>";
        }
        echo "</table>";
        exit( );
    }

    private function _getEventLists( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", 0 );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_GET, "flowType", 0 );
        $_obfuscate_mPAjEGLn = $this->api_getEventList( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmk� );
        $_obfuscate_c_buaHGB = array( );
        if ( is_array( $_obfuscate_mPAjEGLn ) )
        {
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_c_buaHGB[$_obfuscate_5w��]['typename'] = $_obfuscate_6A��['typename'];
                $_obfuscate_c_buaHGB[$_obfuscate_5w��]['stepname'] = $_obfuscate_6A��['stepname'];
                $_obfuscate_c_buaHGB[$_obfuscate_5w��]['uname'] = $_obfuscate_6A��['uname'];
                $_obfuscate_c_buaHGB[$_obfuscate_5w��]['posttime'] = $_obfuscate_6A��['posttime'];
                $_obfuscate_c_buaHGB[$_obfuscate_5w��]['say'] = $_obfuscate_6A��['say'];
            }
        }
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_c_buaHGB;
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

}

?>
