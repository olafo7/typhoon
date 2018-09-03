<?php

class wfFlowUseTodoM extends wfCommon
{

    public function run( )
    {
        $this->clientType = "MOB";
        $_obfuscate_M_5JJwÿÿ = getpar( $_GET, "task", "loadPage" );
        switch ( $_obfuscate_M_5JJwÿÿ )
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
        $_obfuscate_vholQÿÿ = getpar( $_GET, "from", "list" );
        if ( $_obfuscate_vholQÿÿ == "list" )
        {
            $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/todo.m.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQÿÿ );
        }
        else if ( $_obfuscate_vholQÿÿ == "showflow" )
        {
            $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/showflow.m.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQÿÿ );
        }
    }

    private function _loadUflowInfo( $_obfuscate_vholQÿÿ = NULL )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQÿÿ = intval( getpar( $_POST, "uFlowId", 0 ) );
        $_obfuscate_VBCv7Qÿÿ = intval( getpar( $_POST, "step", 0 ) );
        $_obfuscate_pEvU7Kz2Ywÿÿ = intval( getpar( $_POST, "tplSort", 0 ) );
        $_obfuscate_XkuTFqZ6Tmkÿ = intval( getpar( $_POST, "flowType", 0 ) );
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_5LuNFL5U2xQÿ = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $_obfuscate_6RYLWQÿÿ['flowNumber'] = $_obfuscate_5LuNFL5U2xQÿ['flowNumber'];
        $_obfuscate_6RYLWQÿÿ['flowName'] = $_obfuscate_5LuNFL5U2xQÿ['flowName'];
        $_obfuscate_6RYLWQÿÿ['reason'] = $_obfuscate_5LuNFL5U2xQÿ['reason'];
        $_obfuscate_6RYLWQÿÿ['level'] = $this->f_level[$_obfuscate_5LuNFL5U2xQÿ['level']];
        $_obfuscate_6RYLWQÿÿ['status'] = $this->f_status[$_obfuscate_5LuNFL5U2xQÿ['status']];
        $_obfuscate_6RYLWQÿÿ['uname'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_5LuNFL5U2xQÿ['uid'] );
        $_obfuscate_6RYLWQÿÿ['posttime'] = formatdate( $_obfuscate_5LuNFL5U2xQÿ['posttime'], "Y-m-d H:i" );
        $_obfuscate_6RYLWQÿÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
        $_obfuscate_6RYLWQÿÿ['htmlFormContent'] = $_obfuscate_5LuNFL5U2xQÿ['htmlFormContent'];
        $_obfuscate_F4AbnVRh = $_obfuscate_5LuNFL5U2xQÿ['flowId'];
        ( $_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_e53ODz04JQÿÿ = new wfCache( );
        $_obfuscate_5NhzjnJq_f8ÿ = $_obfuscate_e53ODz04JQÿÿ->getStepByStepId( $_obfuscate_VBCv7Qÿÿ );
        $_obfuscate_vzGArcjOKr8_7Aÿÿ = array( );
        $_obfuscate_vzGArcjOKr8_7Aÿÿ['btnText'] = $this->f_btn_text[$_obfuscate_5NhzjnJq_f8ÿ['doBtnText']];
        $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowReject'] = $_obfuscate_5NhzjnJq_f8ÿ['allowReject'];
        $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowHuiqian'] = $_obfuscate_5NhzjnJq_f8ÿ['allowHuiqian'];
        $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowFenfa'] = $_obfuscate_5NhzjnJq_f8ÿ['allowFenfa'];
        $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowTuihui'] = $_obfuscate_5NhzjnJq_f8ÿ['allowTuihui'];
        $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachAdd'] = $_obfuscate_5NhzjnJq_f8ÿ['allowAttachAdd'];
        $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachView'] = $_obfuscate_5NhzjnJq_f8ÿ['allowAttachView'];
        $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachEdit'] = $_obfuscate_5NhzjnJq_f8ÿ['allowAttachEdit'];
        $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachDelete'] = $_obfuscate_5NhzjnJq_f8ÿ['allowAttachDelete'];
        $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachDown'] = $_obfuscate_5NhzjnJq_f8ÿ['allowAttachDown'];
        $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowWordEdit'] = $_obfuscate_5NhzjnJq_f8ÿ['allowWordEdit'];
        $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachWordEdit'] = $_obfuscate_5NhzjnJq_f8ÿ['allowAttachWordEdit'];
        $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowSms'] = $_obfuscate_5NhzjnJq_f8ÿ['allowSms'];
        $_obfuscate_hYX302lGYvY5 = $CNOA_DB->db_getone( "*", $this->t_use_step, "WHERE `uStepId`='".$_obfuscate_VBCv7Qÿÿ."' AND `uFlowId`='{$_obfuscate_TlvKhtsoOQÿÿ}'" );
        $_obfuscate_5ZL98vEÿ = $this->getProxyUid( $_obfuscate_F4AbnVRh, $_obfuscate_7Ri3 );
        if ( $_obfuscate_7Ri3 == $_obfuscate_hYX302lGYvY5['proxyUid'] || $_obfuscate_7Ri3 == $_obfuscate_5ZL98vEÿ )
        {
            $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowErust'] = 0;
        }
        else
        {
            $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowErust'] = 1;
        }
        $_obfuscate_LeS8hwÿÿ = getpar( $_POST, "type", "" );
        if ( $_obfuscate_LeS8hwÿÿ == "done" )
        {
            $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "uStepId" ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uid` = '{$_obfuscate_7Ri3}' " );
            if ( !is_array( $_obfuscate_Tx7M9W ) )
            {
                $_obfuscate_Tx7M9W = array( );
            }
            $_obfuscate_QwT4KwrB2wÿÿ = array( );
            foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_QwT4KwrB2wÿÿ[] = $_obfuscate_6Aÿÿ['uStepId'];
            }
            $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowReject'] = 0;
            $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachAdd'] = 0;
            $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachEdit'] = 0;
            $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachDelete'] = 0;
            $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachView'] = 0;
            $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachDown'] = 0;
            foreach ( $_obfuscate_e53ODz04JQÿÿ->getStepInfoByIdArr( $_obfuscate_QwT4KwrB2wÿÿ ) as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                if ( $_obfuscate_6Aÿÿ['allowAttachView'] == 1 )
                {
                    $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachView'] = 1;
                }
                if ( $_obfuscate_6Aÿÿ['allowAttachDown'] == 1 )
                {
                    $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachDown'] = 1;
                }
            }
        }
        $_obfuscate_5_QxeMoÿ = getpar( $_GET, "modul", "" );
        ( );
        $_obfuscate_2ggÿ = new fs( );
        if ( $_obfuscate_XkuTFqZ6Tmkÿ == 0 )
        {
            if ( $_obfuscate_5_QxeMoÿ == "view" )
            {
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachAdd'] = 0;
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachEdit'] = 0;
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachDelete'] = 0;
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachView'] = 1;
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachDown'] = 1;
            }
            if ( $_obfuscate_5NhzjnJq_f8ÿ['stepType'] == 1 )
            {
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowReject'] = 0;
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowHuiqian'] = 0;
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowTuihui'] = 0;
            }
            if ( $_obfuscate_vholQÿÿ == "mgr" )
            {
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachView'] = 1;
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachDown'] = 1;
            }
            $_obfuscate_8CpDPPa = $_obfuscate_2ggÿ->getListInfo4wf( json_decode( $_obfuscate_5LuNFL5U2xQÿ['attach'], TRUE ), $_obfuscate_vzGArcjOKr8_7Aÿÿ, TRUE, "deal" );
        }
        else
        {
            if ( $_obfuscate_LeS8hwÿÿ == "done" )
            {
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachEdit'] = 0;
            }
            else
            {
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachEdit'] = 1;
            }
            $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachDelete'] = 1;
            $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachView'] = 1;
            $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachDown'] = 1;
            if ( $_obfuscate_5_QxeMoÿ == "view" )
            {
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachAdd'] = 0;
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachEdit'] = 0;
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachDelete'] = 0;
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachView'] = 1;
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachDown'] = 1;
            }
            if ( $_obfuscate_5NhzjnJq_f8ÿ['stepType'] == 1 )
            {
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowReject'] = 0;
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowHuiqian'] = 0;
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowTuihui'] = 0;
            }
            if ( $_obfuscate_vholQÿÿ == "mgr" )
            {
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachView'] = 1;
                $_obfuscate_vzGArcjOKr8_7Aÿÿ['allowAttachDown'] = 1;
            }
            $_obfuscate_8CpDPPa = $_obfuscate_2ggÿ->getListInfo4wf( json_decode( $_obfuscate_5LuNFL5U2xQÿ['attach'], TRUE ), $_obfuscate_vzGArcjOKr8_7Aÿÿ, TRUE, "deal" );
        }
        if ( $_obfuscate_XkuTFqZ6Tmkÿ == 0 && $_obfuscate_pEvU7Kz2Ywÿÿ == 0 )
        {
            $_obfuscate_urgydSw7IkMKIoqpAÿÿ = $this->api_getWfFieldData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ );
            $_obfuscate_sAnybXEÿ = $CNOA_DB->db_select( array( "id" ), $this->t_set_field, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' AND `otype` = 'detailtable' " );
            if ( !empty( $_obfuscate_sAnybXEÿ ) )
            {
                $_obfuscate_ScER1S69bt_Qmgÿÿ = array( );
                foreach ( $_obfuscate_sAnybXEÿ as $_obfuscate_6Aÿÿ )
                {
                    $_obfuscate_XNOBiCm6rWto5G4wFkJiVkUqo0ÿ[] = $this->api_getWfFieldDetailData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_6Aÿÿ['id'] );
                }
            }
        }
        $_obfuscate_0WaREsXoZ4wÿ = $this->api_getReadList( $_obfuscate_TlvKhtsoOQÿÿ );
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        $_obfuscate_NlQÿ->attach = $_obfuscate_8CpDPPa;
        $_obfuscate_NlQÿ->readInfo = $_obfuscate_0WaREsXoZ4wÿ;
        if ( $_obfuscate_XkuTFqZ6Tmkÿ == 0 && $_obfuscate_pEvU7Kz2Ywÿÿ == 0 )
        {
            $_obfuscate_NlQÿ->wf_field_data = $_obfuscate_urgydSw7IkMKIoqpAÿÿ;
            $_obfuscate_NlQÿ->wf_detail_field_data = $_obfuscate_XNOBiCm6rWto5G4wFkJiVkUqo0ÿ;
        }
        $_obfuscate_NlQÿ->config = $_obfuscate_vzGArcjOKr8_7Aÿÿ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
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

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_mV9HBLYÿ = ( integer )getpar( $_GET, "start", getpar( $_POST, "start" ) );
        $_obfuscate_xvYeh9Iÿ = ( integer )getpar( $_GET, "limit", getpar( $_POST, "limit", 15 ) );
        $_obfuscate_KYPe3Fn6DvBxAÿÿ = getpar( $_GET, "flowNumber", getpar( $_POST, "flowNumber", "" ) );
        $_obfuscate_Bk2lGlkÿ = "";
        if ( !empty( $_obfuscate_KYPe3Fn6DvBxAÿÿ ) )
        {
            $_obfuscate_Bk2lGlkÿ = "AND (f.flowNumber LIKE '%".$_obfuscate_KYPe3Fn6DvBxAÿÿ."%' OR f.flowName LIKE '%{$_obfuscate_KYPe3Fn6DvBxAÿÿ}%') ";
        }
        $_obfuscate_3y0Y = "FROM ".tname( $this->t_use_flow )." AS f LEFT JOIN ".tname( $this->t_set_flow )." AS  sf ON  sf.flowId =  f.flowId LEFT JOIN ".tname( $this->t_use_step )." AS  s ON  f.uFlowId =  s.uFlowId LEFT JOIN ".tname( $this->t_use_step_huiqian )." AS h ON f.uFlowId=h.uFlowId AND s.uStepId=h.stepId LEFT JOIN ".tname( $this->t_use_fenfa )." AS ff ON f.uFlowId=ff.uFlowId AND s.uStepId=ff.stepId LEFT JOIN ".tname( $this->main_user )."AS user ON f.uid=user.uid ".( "WHERE s.status=1 AND sf.flowType=0 AND sf.tplSort=0 AND (s.uid='".$_obfuscate_7Ri3."' OR s.proxyUid='{$_obfuscate_7Ri3}' OR (h.touid='{$_obfuscate_7Ri3}' AND h.status=0) OR (ff.touid='{$_obfuscate_7Ri3}' AND ff.status='0')) {$_obfuscate_Bk2lGlkÿ}" );
        $_obfuscate_bz5X7ZKzzwÿÿ = "SELECT f.uFlowId, f.flowId, s.uStepId, f.flowName, f.flowNumber, f.uid, f.posttime, f.level, f.status AS `fstatus`, user.face, user.truename, h.touid AS `hqUid`, h.status AS `hqStatus`, ff.touid AS `ffUid`, ff.status AS `ffStatus` ".$_obfuscate_3y0Y."ORDER BY f.level DESC, f.updatetime DESC";
        $_obfuscate_mPAjEGLn = $CNOA_DB->query( $_obfuscate_bz5X7ZKzzwÿÿ );
        $_obfuscate_8Bnz38wN01cÿ = $_obfuscate_uWfP0Bouwÿÿ = $_obfuscate__eqrEQÿÿ = $_obfuscate_DZ6dPQKYRUM2jAÿÿ = $_obfuscate_4JMFZ0YFHlXdrgÿÿ = array( );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_mPAjEGLn ) )
        {
            if ( $_obfuscate_gkt['hqUid'] == $_obfuscate_7Ri3 )
            {
                $_obfuscate_DZ6dPQKYRUM2jAÿÿ[] = $_obfuscate_gkt['uFlowId'];
            }
            if ( $_obfuscate_gkt['ffUid'] == $_obfuscate_7Ri3 )
            {
                $_obfuscate_4JMFZ0YFHlXdrgÿÿ[] = $_obfuscate_gkt['uFlowId'];
            }
        }
        $_obfuscate_bz5X7ZKzzwÿÿ = "SELECT f.uFlowId, f.flowId, s.uStepId, f.flowName, f.flowNumber, f.uid, f.posttime, f.level, f.status AS `fstatus`, user.face, user.truename, h.touid AS `hqUid`, h.status AS `hqStatus`, ff.touid AS `ffUid`, ff.status AS `ffStatus` ".$_obfuscate_3y0Y.( "ORDER BY f.level DESC, f.updatetime DESC LIMIT ".$_obfuscate_mV9HBLYÿ.", {$_obfuscate_xvYeh9Iÿ}" );
        $_obfuscate_mPAjEGLn = $CNOA_DB->query( $_obfuscate_bz5X7ZKzzwÿÿ );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_uWfP0Bouwÿÿ[] = $_obfuscate_gkt['flowId'];
            $_obfuscate__eqrEQÿÿ[] = $_obfuscate_gkt['uid'];
            $_obfuscate_8Bnz38wN01cÿ[] = $_obfuscate_gkt;
        }
        unset( $_obfuscate_mPAjEGLn );
        if ( !empty( $_obfuscate__eqrEQÿÿ ) )
        {
            $_obfuscate_Ni1jOphVejkÿ = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate__eqrEQÿÿ );
        }
        $_obfuscate_7qDAYo85aGAÿ = array( );
        if ( !empty( $_obfuscate_uWfP0Bouwÿÿ ) )
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "flowId", "tplSort", "flowType" ), $this->t_set_flow, "WHERE `flowId` IN (".implode( ",", $_obfuscate_uWfP0Bouwÿÿ ).")" );
            if ( !is_array( $_obfuscate_mPAjEGLn ) )
            {
                $_obfuscate_mPAjEGLn = array( );
            }
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_7qDAYo85aGAÿ[$_obfuscate_6Aÿÿ['flowId']] = $_obfuscate_6Aÿÿ;
            }
        }
        if ( !is_array( $_obfuscate_8Bnz38wN01cÿ ) )
        {
            $_obfuscate_8Bnz38wN01cÿ = array( );
        }
        foreach ( $_obfuscate_8Bnz38wN01cÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6Aÿÿ['userName'] = empty( $_obfuscate_Ni1jOphVejkÿ[$_obfuscate_6Aÿÿ['uid']] ) ? "<span style=\"color:#FF6600\">ç”¨æˆ·ä¸å­˜åœ¨</span>" : $_obfuscate_Ni1jOphVejkÿ[$_obfuscate_6Aÿÿ['uid']];
            $_obfuscate_6Aÿÿ['posttime'] = formatdate( $_obfuscate_6Aÿÿ['posttime'], "Y-m-d H:i" );
            $_obfuscate_6Aÿÿ['tplSort'] = $_obfuscate_7qDAYo85aGAÿ[$_obfuscate_6Aÿÿ['flowId']]['tplSort'];
            $_obfuscate_6Aÿÿ['flowType'] = $_obfuscate_7qDAYo85aGAÿ[$_obfuscate_6Aÿÿ['flowId']]['flowType'];
            $_obfuscate_6Aÿÿ['toHQ'] = 0;
            $_obfuscate_6Aÿÿ['toFenfa'] = 0;
            $_obfuscate_6Aÿÿ['status'] = "other";
            if ( in_array( $_obfuscate_6Aÿÿ['uFlowId'], $_obfuscate_DZ6dPQKYRUM2jAÿÿ ) && $_obfuscate_6Aÿÿ['hqStatus'] == 0 )
            {
                $_obfuscate_6Aÿÿ['toHQ'] = 1;
            }
            if ( in_array( $_obfuscate_6Aÿÿ['uFlowId'], $_obfuscate_4JMFZ0YFHlXdrgÿÿ ) && $_obfuscate_6Aÿÿ['ffStatus'] == 0 )
            {
                $_obfuscate_6Aÿÿ['toFenfa'] = 1;
            }
            if ( $_obfuscate_6Aÿÿ['toFenfa'] == 0 && $_obfuscate_6Aÿÿ['toHQ'] == 0 )
            {
                $_obfuscate_6Aÿÿ['status'] = "todo";
            }
            if ( $_obfuscate_6Aÿÿ['level'] == 0 )
            {
                $_obfuscate_6Aÿÿ['level'] = "<span style=\"color: green\">æ™®é€š</span>";
            }
            else if ( $_obfuscate_6Aÿÿ['level'] == 1 )
            {
                $_obfuscate_6Aÿÿ['level'] = "<span style=\"color: orange\">é‡è¦</span>";
            }
            else if ( $_obfuscate_6Aÿÿ['level'] == 2 )
            {
                $_obfuscate_6Aÿÿ['level'] = "<span style=\"color: red\">éå¸¸é‡è¦</span>";
            }
            if ( $_obfuscate_6Aÿÿ['fstatus'] == 0 )
            {
                $_obfuscate_6Aÿÿ['fstatus'] = "æœªå‘å¸ƒ";
            }
            else if ( $_obfuscate_6Aÿÿ['fstatus'] == 1 )
            {
                $_obfuscate_6Aÿÿ['fstatus'] = "åŠç†ä¸­";
            }
            else if ( $_obfuscate_6Aÿÿ['fstatus'] == 2 )
            {
                $_obfuscate_6Aÿÿ['fstatus'] = "å·²åŠç†";
            }
            else if ( $_obfuscate_6Aÿÿ['fstatus'] == 3 )
            {
                $_obfuscate_6Aÿÿ['fstatus'] = "å·²é€€ä»¶";
            }
            else if ( $_obfuscate_6Aÿÿ['fstatus'] == 4 )
            {
                $_obfuscate_6Aÿÿ['fstatus'] = "å·²æ’¤é”€";
            }
            else if ( $_obfuscate_6Aÿÿ['fstatus'] == 5 )
            {
                $_obfuscate_6Aÿÿ['fstatus'] = "å·²åˆ é™¤";
            }
            else if ( $_obfuscate_6Aÿÿ['fstatus'] == 6 )
            {
                $_obfuscate_6Aÿÿ['fstatus'] = "å·²ä¸­æ­¢";
            }
            if ( empty( $_obfuscate_6Aÿÿ['face'] ) )
            {
                $_obfuscate_6Aÿÿ['face'] = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
            }
            else
            {
                $_obfuscate_b9_qaEFdaQÿÿ = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/".$_obfuscate_7Ri3."/130x130_".$_obfuscate_6Aÿÿ['face'];
                if ( file_exists( $_obfuscate_b9_qaEFdaQÿÿ ) )
                {
                    $_obfuscate_6Aÿÿ['face'] = $_obfuscate_b9_qaEFdaQÿÿ;
                }
                else
                {
                    $_obfuscate_6Aÿÿ['face'] = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
                }
            }
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ] = $_obfuscate_6Aÿÿ;
        }
        $_obfuscate_oDHJs415kPUÿ = "SELECT count(`f`.`flowId`) AS `count` ".$_obfuscate_3y0Y;
        $_obfuscate_mPAjEGLn = $CNOA_DB->query( $_obfuscate_oDHJs415kPUÿ );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_gftfagwÿ = $_obfuscate_gkt['count'];
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_8Bnz38wN01cÿ;
        $_obfuscate_SUjPN94Er7yI->total = $_obfuscate_gftfagwÿ;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _getJsonDataNew( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_mV9HBLYÿ = ( integer )getpar( $_GET, "start", getpar( $_POST, "start" ) );
        $_obfuscate_xvYeh9Iÿ = ( integer )getpar( $_GET, "limit", getpar( $_POST, "limit", 15 ) );
        $_obfuscate_dcwitxb = getpar( $_GET, "search", getpar( $_POST, "search", "" ) );
        $_obfuscate_Bk2lGlkÿ = "";
        if ( !empty( $_obfuscate_dcwitxb ) )
        {
            $_obfuscate_Bk2lGlkÿ = "AND (f.flowNumber LIKE '%".$_obfuscate_dcwitxb."%' OR f.flowName LIKE '%{$_obfuscate_dcwitxb}%') ";
        }
        $_obfuscate_3y0Y = "FROM ".tname( $this->t_use_flow )." AS f LEFT JOIN ".tname( $this->t_set_flow )." AS sf ON sf.flowId = f.flowId LEFT JOIN ".tname( $this->t_use_step )." AS s ON f.uFlowId = s.uFlowId LEFT JOIN ".tname( $this->main_user )."AS user ON f.uid=user.uid ".( "WHERE s.status=1 AND sf.flowType=0 AND sf.tplSort=0 AND (s.uid='".$_obfuscate_7Ri3."' OR s.proxyUid='{$_obfuscate_7Ri3}') {$_obfuscate_Bk2lGlkÿ}" );
        $_obfuscate_bz5X7ZKzzwÿÿ = "SELECT f.uFlowId, f.flowId, s.uStepId, f.flowName, f.flowNumber, f.uid, f.posttime, f.level, f.status AS `fstatus`, user.face, user.truename ".$_obfuscate_3y0Y.( "GROUP BY f.uFlowId DESC ORDER BY f.level DESC, f.updatetime DESC LIMIT ".$_obfuscate_mV9HBLYÿ.", {$_obfuscate_xvYeh9Iÿ}" );
        $_obfuscate_mPAjEGLn = $CNOA_DB->query( $_obfuscate_bz5X7ZKzzwÿÿ );
        $_obfuscate_8Bnz38wN01cÿ = $_obfuscate_uWfP0Bouwÿÿ = $_obfuscate__eqrEQÿÿ = $_obfuscate_DZ6dPQKYRUM2jAÿÿ = $_obfuscate_4JMFZ0YFHlXdrgÿÿ = array( );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_uWfP0Bouwÿÿ[] = $_obfuscate_gkt['flowId'];
            $_obfuscate__eqrEQÿÿ[] = $_obfuscate_gkt['uid'];
            $_obfuscate_8Bnz38wN01cÿ[] = $_obfuscate_gkt;
        }
        unset( $_obfuscate_mPAjEGLn );
        if ( !empty( $_obfuscate__eqrEQÿÿ ) )
        {
            $_obfuscate_Ni1jOphVejkÿ = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate__eqrEQÿÿ );
        }
        $_obfuscate_7qDAYo85aGAÿ = array( );
        if ( !empty( $_obfuscate_uWfP0Bouwÿÿ ) )
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "flowId", "tplSort", "flowType" ), $this->t_set_flow, "WHERE `flowId` IN (".implode( ",", $_obfuscate_uWfP0Bouwÿÿ ).")" );
            if ( !is_array( $_obfuscate_mPAjEGLn ) )
            {
                $_obfuscate_mPAjEGLn = array( );
            }
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_7qDAYo85aGAÿ[$_obfuscate_6Aÿÿ['flowId']] = $_obfuscate_6Aÿÿ;
            }
        }
        if ( !is_array( $_obfuscate_8Bnz38wN01cÿ ) )
        {
            $_obfuscate_8Bnz38wN01cÿ = array( );
        }
        foreach ( $_obfuscate_8Bnz38wN01cÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6Aÿÿ['userName'] = empty( $_obfuscate_Ni1jOphVejkÿ[$_obfuscate_6Aÿÿ['uid']] ) ? "<span style=\"color:#FF6600\">ç”¨æˆ·ä¸å­˜åœ¨</span>" : $_obfuscate_Ni1jOphVejkÿ[$_obfuscate_6Aÿÿ['uid']];
            $_obfuscate_6Aÿÿ['posttime'] = formatdate( $_obfuscate_6Aÿÿ['posttime'], "Y-m-d H:i" );
            $_obfuscate_6Aÿÿ['tplSort'] = $_obfuscate_7qDAYo85aGAÿ[$_obfuscate_6Aÿÿ['flowId']]['tplSort'];
            $_obfuscate_6Aÿÿ['flowType'] = $_obfuscate_7qDAYo85aGAÿ[$_obfuscate_6Aÿÿ['flowId']]['flowType'];
            $_obfuscate_6Aÿÿ['toHQ'] = 0;
            $_obfuscate_6Aÿÿ['toFenfa'] = 0;
            $_obfuscate_6Aÿÿ['status'] = "other";
            if ( $_obfuscate_6Aÿÿ['toFenfa'] == 0 && $_obfuscate_6Aÿÿ['toHQ'] == 0 )
            {
                $_obfuscate_6Aÿÿ['status'] = "todo";
            }
            if ( $_obfuscate_6Aÿÿ['level'] == 0 )
            {
                $_obfuscate_6Aÿÿ['level'] = "<span style=\"color: green\">æ™®é€š</span>";
            }
            else if ( $_obfuscate_6Aÿÿ['level'] == 1 )
            {
                $_obfuscate_6Aÿÿ['level'] = "<span style=\"color: orange\">é‡è¦</span>";
            }
            else if ( $_obfuscate_6Aÿÿ['level'] == 2 )
            {
                $_obfuscate_6Aÿÿ['level'] = "<span style=\"color: red\">éå¸¸é‡è¦</span>";
            }
            if ( $_obfuscate_6Aÿÿ['fstatus'] == 0 )
            {
                $_obfuscate_6Aÿÿ['fstatus'] = "æœªå‘å¸ƒ";
            }
            else if ( $_obfuscate_6Aÿÿ['fstatus'] == 1 )
            {
                $_obfuscate_6Aÿÿ['fstatus'] = "åŠç†ä¸­";
            }
            else if ( $_obfuscate_6Aÿÿ['fstatus'] == 2 )
            {
                $_obfuscate_6Aÿÿ['fstatus'] = "å·²åŠç†";
            }
            else if ( $_obfuscate_6Aÿÿ['fstatus'] == 3 )
            {
                $_obfuscate_6Aÿÿ['fstatus'] = "å·²é€€ä»¶";
            }
            else if ( $_obfuscate_6Aÿÿ['fstatus'] == 4 )
            {
                $_obfuscate_6Aÿÿ['fstatus'] = "å·²æ’¤é”€";
            }
            else if ( $_obfuscate_6Aÿÿ['fstatus'] == 5 )
            {
                $_obfuscate_6Aÿÿ['fstatus'] = "å·²åˆ é™¤";
            }
            else if ( $_obfuscate_6Aÿÿ['fstatus'] == 6 )
            {
                $_obfuscate_6Aÿÿ['fstatus'] = "å·²ä¸­æ­¢";
            }
            if ( empty( $_obfuscate_6Aÿÿ['face'] ) )
            {
                $_obfuscate_6Aÿÿ['face'] = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
            }
            else
            {
                $_obfuscate_b9_qaEFdaQÿÿ = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/".$_obfuscate_7Ri3."/120x160_".$_obfuscate_6Aÿÿ['face'];
                if ( file_exists( $_obfuscate_b9_qaEFdaQÿÿ ) )
                {
                    $_obfuscate_6Aÿÿ['face'] = $_obfuscate_b9_qaEFdaQÿÿ;
                }
                else
                {
                    $_obfuscate_6Aÿÿ['face'] = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
                }
            }
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_5wÿÿ] = $_obfuscate_6Aÿÿ;
        }
        $_obfuscate_oDHJs415kPUÿ = "SELECT count(`f`.`flowId`) AS `count` ".$_obfuscate_3y0Y;
        $_obfuscate_mPAjEGLn = $CNOA_DB->query( $_obfuscate_oDHJs415kPUÿ );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_gftfagwÿ = $_obfuscate_gkt['count'];
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_8Bnz38wN01cÿ;
        $_obfuscate_SUjPN94Er7yI->total = $_obfuscate_gftfagwÿ;
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
            $_obfuscate_2ggÿ = new fs( );
            $_obfuscate_8CpDPPa = $_obfuscate_2ggÿ->add( $_obfuscate_9ga6vjaQ61MybPYk, 1 );
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
        $_obfuscate_o5n931n9CIUÿ = new stdClass( );
        $_obfuscate_o5n931n9CIUÿ->success = FALSE;
        if ( $_FILES['userfile']['error'] !== UPLOAD_ERR_OK )
        {
            $_obfuscate_o5n931n9CIUÿ->message = lang( "uploadFail" );
        }
        else if ( isimage( $_FILES['userfile']['name'] ) )
        {
            $_obfuscate_3gn_eQÿÿ = $_FILES['userfile']['name'];
            $_obfuscate_zo8F = substr( $_obfuscate_3gn_eQÿÿ, strrpos( $_obfuscate_3gn_eQÿÿ, "." ) );
            $_obfuscate_7tmDAr7acvgDxwÿÿ = $GLOBALS['URL_FILE']."/editpic/".md5( time( ) )."_".string::rands( 10 ).$_obfuscate_zo8F;
            if ( is_uploaded_file( $_FILES['userfile']['tmp_name'] ) && cnoa_move_uploaded_file( $_FILES['userfile']['tmp_name'], $_obfuscate_7tmDAr7acvgDxwÿÿ ) )
            {
                $_obfuscate_o5n931n9CIUÿ->success = TRUE;
                $_obfuscate_o5n931n9CIUÿ->message = $_obfuscate_7tmDAr7acvgDxwÿÿ;
            }
            else
            {
                $_obfuscate_o5n931n9CIUÿ->message = lang( "uploadFail" );
            }
        }
        else
        {
            $_obfuscate_o5n931n9CIUÿ->message = lang( "fileFormarNotMeet" );
        }
        echo json_encode( $_obfuscate_o5n931n9CIUÿ );
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
        $_obfuscate_6RYLWQÿÿ = app::loadapp( "wf", "flowUseTodo" )->api_loadPrevstepData( );
        if ( !is_array( $_obfuscate_6RYLWQÿÿ ) )
        {
            $_obfuscate_6RYLWQÿÿ = array( );
        }
        $_obfuscate_j9eamhYÿ = array( );
        foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_j9eamhYÿ[] = array(
                "xtype" => "radiofield",
                "name" => "ustepId",
                "label" => $_obfuscate_6Aÿÿ['boxLabel'],
                "value" => $_obfuscate_6Aÿÿ['inputValue'],
                "checked" => $_obfuscate_5wÿÿ ? FALSE : TRUE
            );
            if ( isset( $_obfuscate_6Aÿÿ['bingfaChild'] ) )
            {
                foreach ( $_obfuscate_6Aÿÿ['bingfaChild'] as $_obfuscate_eBU_Sjcÿ )
                {
                    $_obfuscate_j9eamhYÿ[] = array(
                        "xtype" => "radiofield",
                        "name" => "ustepId",
                        "label" => $_obfuscate_eBU_Sjcÿ['boxLabel'],
                        "value" => $_obfuscate_eBU_Sjcÿ['inputValue'],
                        "checked" => $_obfuscate_5wÿÿ ? FALSE : TRUE
                    );
                }
            }
        }
        echo json_encode( $_obfuscate_j9eamhYÿ );
    }

    private function _loadPrevstepDataNew( )
    {
        $_obfuscate_6RYLWQÿÿ = app::loadapp( "wf", "flowUseTodo" )->api_loadPrevstepData( );
        if ( !is_array( $_obfuscate_6RYLWQÿÿ ) )
        {
            $_obfuscate_6RYLWQÿÿ = array( );
        }
        $_obfuscate_j9eamhYÿ = array( );
        foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_j9eamhYÿ[] = array(
                "xtype" => "radiofield",
                "name" => "ustepId",
                "label" => $_obfuscate_6Aÿÿ['boxLabel'],
                "value" => $_obfuscate_6Aÿÿ['inputValue'],
                "checked" => $_obfuscate_5wÿÿ ? FALSE : TRUE
            );
            if ( isset( $_obfuscate_6Aÿÿ['bingfaChild'] ) )
            {
                foreach ( $_obfuscate_6Aÿÿ['bingfaChild'] as $_obfuscate_eBU_Sjcÿ )
                {
                    $_obfuscate_j9eamhYÿ[] = array(
                        "xtype" => "radiofield",
                        "name" => "ustepId",
                        "label" => $_obfuscate_eBU_Sjcÿ['boxLabel'],
                        "value" => $_obfuscate_eBU_Sjcÿ['inputValue'],
                        "checked" => $_obfuscate_5wÿÿ ? FALSE : TRUE
                    );
                }
            }
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_j9eamhYÿ;
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
        $_obfuscate_ELA8NXksz6D4NeGh = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_QabuumMSpAVzrvaOAÿÿ );
        $_obfuscate_1XvASPFcSAJ6MqwjC4F = array( );
        foreach ( $_obfuscate_ELA8NXksz6D4NeGh as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_1XvASPFcSAJ6MqwjC4F[$_obfuscate_6Aÿÿ['uid']]['name'] = $_obfuscate_6Aÿÿ['truename'];
        }
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
            $_obfuscate_6RYLWQÿÿ['truename'] = $_obfuscate_1XvASPFcSAJ6MqwjC4F[$_obfuscate_6Aÿÿ]['name'];
            $_obfuscate_LvAlJbKidRGZ = $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_use_step_huiqian );
            $_obfuscate_gb3bCas1['content'] = lang( "flowName" ).( "[".$_obfuscate_7qDAYo85aGAÿ['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_7qDAYo85aGAÿ['flowNumber']."]" ).lang( "needYouSign" );
            $_obfuscate_gb3bCas1['href'] = "&uFlowId=".$_obfuscate_6RYLWQÿÿ['uFlowId']."&flowId={$_obfuscate_7qDAYo85aGAÿ['flowId']}&step={$_obfuscate_6RYLWQÿÿ['stepId']}&flowType={$_obfuscate_7qDAYo85aGAÿ['flowType']}&tplSort={$_obfuscate_7qDAYo85aGAÿ['tplSort']}";
            $_obfuscate_gb3bCas1['fromid'] = $_obfuscate_LvAlJbKidRGZ;
            $this->addNotice( "both", $_obfuscate_6RYLWQÿÿ['touid'], $_obfuscate_gb3bCas1, "huiqian" );
            $_obfuscate_HweFzDn2[] = $_obfuscate_6RYLWQÿÿ['truename'];
        }
        if ( 0 < count( $_obfuscate_HweFzDn2 ) )
        {
            $_obfuscate_HweFzDn2 = implode( ",", $_obfuscate_HweFzDn2 );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3604, lang( "signPersonnel" ).( "[ ".$_obfuscate_HweFzDn2." ]ï¼Œ" ).lang( "flowName" ).( "[ ".$_obfuscate_7qDAYo85aGAÿ['flowName']." ]" ).lang( "bianHao" ).( "[ ".$_obfuscate_7qDAYo85aGAÿ['flowNumber']." ]" ) );
        }
        msg::callback( TRUE, lang( "successopt" ) );
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
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $CNOA_DB->db_update( array(
            "message" => $_obfuscate_FYJCcRzosAÿÿ,
            "writetime" => $GLOBALS['CNOA_TIMESTAMP'],
            "status" => 1
        ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' AND `touid` = '{$_obfuscate_7Ri3}'" );
        $_obfuscate_o5fQ1gÿÿ = $CNOA_DB->db_getone( array( "id", "truename", "uid", "uFlowId" ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' AND `touid` = '{$_obfuscate_7Ri3}' " );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowId" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        $this->doneAll( "both", $_obfuscate_o5fQ1gÿÿ['id'], "huiqian" );
        $_obfuscate_AVrjaAn6 = $CNOA_DB->db_getone( array( "id" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId` = '{$_obfuscate_0Ul8BBkt}'" );
        $_obfuscate_0AITFwÿÿ['content'] = lang( "YouhaveFlow" )."[".$_obfuscate_o5fQ1gÿÿ['truename']."]".lang( "haveSubmitSign" );
        $_obfuscate_0AITFwÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_GB1J1EM4}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
        $_obfuscate_0AITFwÿÿ['fromid'] = $_obfuscate_AVrjaAn6['id'];
        $this->addNotice( "notice", array(
            $_obfuscate_o5fQ1gÿÿ['uid']
        ), $_obfuscate_0AITFwÿÿ, "huiqian", 1 );
        $_obfuscate_JG8GuYÿ = array( );
        $_obfuscate_JG8GuYÿ['type'] = 11;
        $_obfuscate_JG8GuYÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
        $_obfuscate_JG8GuYÿ['step'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_JG8GuYÿ['stepname'] = "ä¼šç­¾";
        $_obfuscate_Vm9G3dwÿ['say'] = $_obfuscate_FYJCcRzosAÿÿ;
        $_obfuscate_JG8GuYÿ['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuYÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $this->insertEvent( $_obfuscate_JG8GuYÿ );
        msg::callback( TRUE, lang( "successopt" ) );
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
        $_obfuscate_O6ZGVAÿÿ = array( );
        $_obfuscate_6RYLWQÿÿ['fromuid'] = $_obfuscate_7Ri3;
        $_obfuscate_6RYLWQÿÿ['touid'] = getpar( $_POST, "touid", 0 );
        $_obfuscate_6RYLWQÿÿ['uFlowId'] = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_6RYLWQÿÿ['flowId'] = getpar( $_POST, "flowId", 0 );
        $_obfuscate_6RYLWQÿÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_1l6P = getpar( $_POST, "say", "æ— " );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_SIUSR4F6 = $CNOA_DB->db_getone( "*", $this->t_use_proxy_uflow, "WHERE `fromuid`='".$_obfuscate_6RYLWQÿÿ['fromuid']."' AND (`flowId` !=0 AND `flowId`='{$_obfuscate_6RYLWQÿÿ['flowId']}')" );
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
            $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_6RYLWQÿÿ['uFlowId']."' " );
            $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "id" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_6RYLWQÿÿ['uFlowId']."' AND `uStepId` = '{$_obfuscate_ecqaH_ev7Aÿÿ}' " );
            $this->deleteNotice( "both", $_obfuscate_Tx7M9W['id'], "todo" );
            $_obfuscate_0AITFwÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "by" )."[".$CNOA_SESSION->get( "TRUENAME" )."][".$_obfuscate_1l6P."]";
            $_obfuscate_0AITFwÿÿ['href'] = "&uFlowId=".$_obfuscate_6RYLWQÿÿ['uFlowId']."&flowId={$_obfuscate_6RYLWQÿÿ['flowId']}&step={$_obfuscate_ecqaH_ev7Aÿÿ}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_71iMkk6t0wÿÿ}";
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
        $_obfuscate_JG8GuYÿ['stepname'] = $CNOA_DB->db_getfield( "stepName", $this->t_set_step, "WHERE `flowId`='".$_obfuscate_6RYLWQÿÿ['flowId']."' AND `uStepId`='{$_obfuscate_ecqaH_ev7Aÿÿ}'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3604, lang( "entrustFlowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
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
            $_obfuscate_gkt['isread'] = $_obfuscate_gkt['isread'] == 0 ? "æœªé˜…è¯»" : "å·²é˜…è¯»";
            $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_gkt;
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _addFenfa( $_obfuscate_TlvKhtsoOQÿÿ = "", $_obfuscate_rHwsX0gg = array( ), $_obfuscate_CRya7qfm = FALSE )
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
        $_obfuscate_sZ_Wvha1 = $CNOA_DB->db_select( array( "touid" ), $this->t_use_fenfa, "WHERE `uflowId`=".$_obfuscate_TlvKhtsoOQÿÿ );
        if ( !is_array( $_obfuscate_sZ_Wvha1 ) )
        {
            $_obfuscate_sZ_Wvha1 = array( );
        }
        $_obfuscate_u9gCXhG9Sgÿÿ = array( );
        foreach ( $_obfuscate_sZ_Wvha1 as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_u9gCXhG9Sgÿÿ[] = $_obfuscate_6Aÿÿ['touid'];
        }
        $_obfuscate_3y0Y = "SELECT u.flowName, u.flowNumber, u.flowId, s.flowType, s.tplSort FROM ".tname( $this->t_use_flow )."AS u LEFT JOIN ".tname( $this->t_set_flow )."AS s ON s.flowId=u.flowId".( "WHERE u.uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ." LIMIT 0, 1" );
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
            $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" ).( "[".$_obfuscate_7qDAYo85aGAÿ['flowName']."]" ).lang( "bianHao" ).( "[".$_obfuscate_7qDAYo85aGAÿ['flowNumber']."]åˆ†å‘ç»™ä½ é˜…è¯»ã€‚" );
            $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_7qDAYo85aGAÿ['flowId']}&flowType={$_obfuscate_7qDAYo85aGAÿ['flowType']}&tplSort={$_obfuscate_7qDAYo85aGAÿ['tplSort']}";
            $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_Ce9h;
            $this->addNotice( "notice", $_obfuscate_rLpOghzteElp['touid'], $_obfuscate_6RYLWQÿÿ, "fenfa" );
            ++$_obfuscate_Ybai;
        }
        if ( 0 < $_obfuscate_Ybai )
        {
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3604, "åˆ†å‘äººå‘˜ï¼Œæµç¨‹åç§°{".$_obfuscate_7qDAYo85aGAÿ['flowName']."}".lang( "bianHao" ).( "[".$_obfuscate_7qDAYo85aGAÿ['flowNumber']."]" ) );
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
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_6RYLWQÿÿ['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_6RYLWQÿÿ['isread'] = 1;
        $_obfuscate_6RYLWQÿÿ['say'] = getpar( $_POST, "say", "" );
        $_obfuscate_6RYLWQÿÿ['viewtime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_6RYLWQÿÿ['status'] = 1;
        $_obfuscate_Thgÿ = $CNOA_DB->db_getone( array( "fenfauid" ), $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `touid`='{$_obfuscate_7Ri3}'" );
        $_obfuscate_W3cYj7TbmC9r = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_Thgÿ['fenfauid'] );
        if ( !empty( $_obfuscate_Thgÿ ) )
        {
            $CNOA_DB->db_update( $_obfuscate_6RYLWQÿÿ, $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `touid`='{$_obfuscate_7Ri3}'" );
            $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber", "flowId" ), $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' " );
            $_obfuscate_0AITFwÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "flowBeenReview" );
            $_obfuscate_0AITFwÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_hTew0boWJESy['flowId']}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
            $_obfuscate_0AITFwÿÿ['fromid'] = $_obfuscate_0Ul8BBkt;
            $this->addNotice( "notice", $_obfuscate_Thgÿ['fenfauid'], $_obfuscate_0AITFwÿÿ, "comment" );
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
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_GET, "uFlowId", getpar( $_POST, "uFlowId", 0 ) );
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", getpar( $_POST, "flowId", 0 ) );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_GET, "flowType", getpar( $_POST, "flowType", 0 ) );
        $_obfuscate_mPAjEGLn = $this->api_getStepList( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmkÿ );
        echo "<table width='100%' border='0' cellspacing='1' cellpadding='0' style='border-collapse: collapse;'>\r\n\t\t\t\t\t<tr>\r\n\t\t\t\t\t\t<td style='border:1px solid #999; padding: 2px; background:#CDCDCD;'>æ­¥éª¤åç§°</td>\r\n\t\t\t\t\t\t<td style='border:1px solid #999; padding: 2px; background:#CDCDCD;'>çŠ¶æ€</td>\r\n\t\t\t\t\t\t<td style='border:1px solid #999; padding: 2px; background:#CDCDCD;'>ç»åŠäººã€å¼€å§‹æ—¶é—´</td>\r\n\t\t\t\t\t\t<td style='border:1px solid #999; padding: 2px; background:#CDCDCD;'>æŒç»­æ—¶é—´</td>\r\n\t\t\t\t\t\t<td style='border:1px solid #999; padding: 2px; background:#CDCDCD;'>åŠç†ç†ç”±</td>\r\n\t\t\t\t\t</tr>\r\n\t\t\t";
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )
        {
            echo "<tr>";
            echo "<td style='border:1px solid #999; padding: 2px;'>".$_obfuscate_6Aÿÿ['stepname']."</td>";
            echo "<td style='border:1px solid #999; padding: 2px;'>".$_obfuscate_6Aÿÿ['statusText']."</td>";
            echo "<td style='border:1px solid #999; padding: 2px;'>".$_obfuscate_6Aÿÿ['uname']."<br />{$_obfuscate_6Aÿÿ['formatStime']}</td>";
            echo "<td style='border:1px solid #999; padding: 2px;'>".$_obfuscate_6Aÿÿ['utime']."</td>";
            echo "<td style='border:1px solid #999; padding: 2px;'>".$_obfuscate_6Aÿÿ['say']."</td>";
            echo "</tr>";
        }
        echo "</table>";
        exit( );
    }

    private function _getStepLists( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_GET, "uFlowId", getpar( $_POST, "uFlowId", 0 ) );
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", getpar( $_POST, "flowId", 0 ) );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_GET, "flowType", getpar( $_POST, "flowType", 0 ) );
        $_obfuscate_mPAjEGLn = $this->api_getStepList( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmkÿ );
        $_obfuscate_j9eamhYÿ = array( );
        if ( is_array( $_obfuscate_mPAjEGLn ) )
        {
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_j9eamhYÿ[$_obfuscate_5wÿÿ]['utime'] = $_obfuscate_6Aÿÿ['utime'];
                $_obfuscate_j9eamhYÿ[$_obfuscate_5wÿÿ]['stepname'] = $_obfuscate_6Aÿÿ['stepname'];
                $_obfuscate_j9eamhYÿ[$_obfuscate_5wÿÿ]['uname'] = $_obfuscate_6Aÿÿ['uname'];
                $_obfuscate_j9eamhYÿ[$_obfuscate_5wÿÿ]['formatStime'] = $_obfuscate_6Aÿÿ['formatStime'];
                $_obfuscate_j9eamhYÿ[$_obfuscate_5wÿÿ]['statusText'] = $_obfuscate_6Aÿÿ['statusText'];
                $_obfuscate_j9eamhYÿ[$_obfuscate_5wÿÿ]['say'] = $_obfuscate_6Aÿÿ['say'];
            }
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_j9eamhYÿ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _cuiban( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_ecqaH_ev7Aÿÿ = getpar( $_POST, "uStepId", 0 );
        $_obfuscate__WwKzYz1wAÿÿ = getpar( $_POST, "content", "" );
        $_obfuscate_NS44QYkÿ = $CNOA_SESSION->get( "TRUENAME" );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowNumber", "flowName", "flowId" ), $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->db_getone( array( "tplSort", "flowType" ), $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_hTew0boWJESy['flowId']."'" );
        $_obfuscate_5NhzjnJq_f8ÿ = $CNOA_DB->db_getone( array( "id", "uid" ), $this->t_use_step, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ." AND `uStepId`={$_obfuscate_ecqaH_ev7Aÿÿ}" );
        $_obfuscate_lQ81YBMÿ = $this->getProxyUid( $_obfuscate_hTew0boWJESy['flowId'], $_obfuscate_5NhzjnJq_f8ÿ['uid'] );
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_6RYLWQÿÿ['content'] = $_obfuscate_NS44QYkÿ.lang( "flowDealRemin" ).":".$_obfuscate__WwKzYz1wAÿÿ;
        $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_7qDAYo85aGAÿ['flowId']}&step={$_obfuscate_ecqaH_ev7Aÿÿ}&flowType={$_obfuscate_7qDAYo85aGAÿ['flowType']}&tplSort={$_obfuscate_7qDAYo85aGAÿ['tplSort']}";
        $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_5NhzjnJq_f8ÿ['id'];
        $this->addNotice( "notice", array(
            $_obfuscate_5NhzjnJq_f8ÿ['uid'],
            $_obfuscate_lQ81YBMÿ
        ), $_obfuscate_6RYLWQÿÿ, "warn" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3631, lang( "remindFlow" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getEventList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", 0 );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_GET, "flowType", 0 );
        $_obfuscate_mPAjEGLn = $this->api_getEventList( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmkÿ );
        echo "<table width='100%' border='0' cellspacing='1' cellpadding='0' style='border-collapse: collapse;'>\r\n\t\t\t\t\t<tr>\r\n\t\t\t\t\t\t<td style='border:1px solid #999; padding: 2px; background:#CDCDCD;'>ç±»å‹</td>\r\n\t\t\t\t\t\t<td style='border:1px solid #999; padding: 2px; background:#CDCDCD;'>æ­¥éª¤</td>\r\n\t\t\t\t\t\t<td style='border:1px solid #999; padding: 2px; background:#CDCDCD;'>ç»åŠäººã€æ—¶é—´</td>\r\n\t\t\t\t\t\t<td style='border:1px solid #999; padding: 2px; background:#CDCDCD;'>åŠç†ç†ç”±</td>\r\n\t\t\t\t\t</tr>\r\n\t\t\t";
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )
        {
            echo "<tr>";
            echo "<td style='border:1px solid #999; padding: 2px;'>".$_obfuscate_6Aÿÿ['typename']."</td>";
            echo "<td style='border:1px solid #999; padding: 2px;'>".$_obfuscate_6Aÿÿ['stepname']."</td>";
            echo "<td style='border:1px solid #999; padding: 2px;'>".$_obfuscate_6Aÿÿ['uname']."<br />{$_obfuscate_6Aÿÿ['posttime']}</td>";
            echo "<td style='border:1px solid #999; padding: 2px;'>".$_obfuscate_6Aÿÿ['say']."</td>";
            echo "</tr>";
        }
        echo "</table>";
        exit( );
    }

    private function _getEventLists( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_GET, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", 0 );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_GET, "flowType", 0 );
        $_obfuscate_mPAjEGLn = $this->api_getEventList( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmkÿ );
        $_obfuscate_c_buaHGB = array( );
        if ( is_array( $_obfuscate_mPAjEGLn ) )
        {
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_c_buaHGB[$_obfuscate_5wÿÿ]['typename'] = $_obfuscate_6Aÿÿ['typename'];
                $_obfuscate_c_buaHGB[$_obfuscate_5wÿÿ]['stepname'] = $_obfuscate_6Aÿÿ['stepname'];
                $_obfuscate_c_buaHGB[$_obfuscate_5wÿÿ]['uname'] = $_obfuscate_6Aÿÿ['uname'];
                $_obfuscate_c_buaHGB[$_obfuscate_5wÿÿ]['posttime'] = $_obfuscate_6Aÿÿ['posttime'];
                $_obfuscate_c_buaHGB[$_obfuscate_5wÿÿ]['say'] = $_obfuscate_6Aÿÿ['say'];
            }
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_c_buaHGB;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

}

?>
