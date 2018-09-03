<?php

class wfFlowUseNewM extends wfCommon
{

    public function run( )
    {
        $_obfuscate_M_5JJwÿÿ = getpar( $_GET, "task", "" );
        switch ( $_obfuscate_M_5JJwÿÿ )
        {
        case "getJsonData" :
            $_obfuscate_vholQÿÿ = getpar( $_POST, "from", getpar( $_GET, "from", "" ) );
            if ( $_obfuscate_vholQÿÿ == "need" )
            {
                $this->_getNeedJsonData( );
            }
            else
            {
                $this->_getJsonData( );
            }
            break;
        case "getSignature" :
            $this->_getSignature( );
            break;
        case "getSendNextData" :
            $this->_getSendNextData( );
            break;
        case "sendNextStep" :
            $this->_sendNextStep( );
            break;
        case "getSortJsonData" :
            $this->_getSortJsonData( );
            break;
        case "getAttLeaveDays" :
            $this->_getAttLeaveDays( );
            break;
        case "getAttEvectionDays" :
            $this->_getAttEvectionDays( );
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "loadFlowDesignData" :
            $this->_loadFlowDesignData( );
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
        case "getBusinessData" :
            $this->_getBusinessData( );
            break;
        case "getQueryList" :
            $this->_getQueryList( );
            break;
        case "loadForDetailTable" :
            $this->_loadForDetailTable( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $_obfuscate_vholQÿÿ = getpar( $_GET, "from" );
        if ( $_obfuscate_vholQÿÿ == "newflow" )
        {
            $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/new.m.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQÿÿ );
        exit( );
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mV9HBLYÿ = ( integer )getpar( $_POST, "start", getpar( $_GET, "start" ) );
        $_obfuscate_xvYeh9Iÿ = ( integer )getpar( $_POST, "limit", getpar( $_GET, "limit" ) );
        $_obfuscate_v1GprsIz = ( integer )getpar( $_POST, "sortId", getpar( $_GET, "sortId" ) );
        if ( !is_numeric( $_obfuscate_v1GprsIz ) )
        {
            $this->_getChildJsonData( );
        }
        else
        {
            $_obfuscate_OTt14hA7OyXA44prAwUÿ = $this->_getSortPermitForNew( );
            $_obfuscate_IRFhnYwÿ = "`status`=1 AND `tplSort`=0 AND `flowId` IN (".implode( ",", $_obfuscate_OTt14hA7OyXA44prAwUÿ ).") ";
            $_obfuscate_3y0Y = "SELECT `f`.`flowId`, `f`.`name` AS `flowName`,`f`.`tplSort`, `f`.`flowType`, `f`.`startStepId`, `f`.`sortId`, `s`.`name` AS `sortName` FROM ".tname( $this->t_set_flow )." AS `f` LEFT JOIN ".tname( $this->t_set_sort )." AS `s` ON `f`.`sortId`=`s`.`sortId` ".( "WHERE `f`.`sortId`=".$_obfuscate_v1GprsIz." AND {$_obfuscate_IRFhnYwÿ} " )."ORDER BY f.posttime DESC ".( "LIMIT ".$_obfuscate_mV9HBLYÿ.", {$_obfuscate_xvYeh9Iÿ}" );
            $_obfuscate_ammigv8ÿ = $CNOA_DB->query( $_obfuscate_3y0Y );
            $_obfuscate_a96y4zwÿ = array( );
            while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_ammigv8ÿ ) )
            {
                $_obfuscate_a96y4zwÿ[] = $_obfuscate_gkt;
            }
            ( );
            $_obfuscate_SUjPN94Er7yI = new dataStore( );
            $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_a96y4zwÿ;
            $_obfuscate_SUjPN94Er7yI->total = $CNOA_DB->db_getcount( $this->t_set_flow, "WHERE `sortId`=".$_obfuscate_v1GprsIz." AND {$_obfuscate_IRFhnYwÿ}" );
            echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        }
    }

    private function _getChildJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mV9HBLYÿ = getpar( $_GET, "start", 0 );
        $_obfuscate_xvYeh9Iÿ = getpar( $_GET, "limit" );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_3y0Y = "SELECT `f`.`flowId`,`f`.`name` AS `flowName`,`f`.`tplSort`,`f`.`flowType`,`f`.`startStepId`,`s`.`name` AS `sortName` FROM ".tname( $this->t_use_step_child_flow )." AS `c` LEFT JOIN ".tname( $this->t_set_flow )." AS `f` ON `f`.`flowId`=`c`.`flowId` LEFT JOIN ".tname( $this->t_set_sort )." AS `s` ON `s`.`sortId`=`f`.`sortId` ".( "WHERE `c`.`faqiUid` = ".$_obfuscate_7Ri3." AND `c`.`status` = 1 " );
        "LIMIT ".$_obfuscate_mV9HBLYÿ.", {$_obfuscate_xvYeh9Iÿ}";
        $_obfuscate_ammigv8ÿ = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_a96y4zwÿ = array( );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_ammigv8ÿ ) )
        {
            $_obfuscate_a96y4zwÿ[] = $_obfuscate_gkt;
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_a96y4zwÿ;
        $_obfuscate_NlQÿ->total = $CNOA_DB->db_getcount( $this->t_use_step_child_flow, "WHERE `faqiUid` = ".$_obfuscate_7Ri3." AND `status` = 1 " );
        echo $_obfuscate_NlQÿ->makeJsonData( );
    }

    private function _getFacePath( $_obfuscate_pp9pYwÿÿ, $_obfuscate_7Ri3 )
    {
        if ( empty( $_obfuscate_pp9pYwÿÿ ) )
        {
            $_obfuscate_6UUC = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
            return $_obfuscate_6UUC;
        }
        $_obfuscate_b9_qaEFdaQÿÿ = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/".$_obfuscate_7Ri3."/130x130_".$_obfuscate_pp9pYwÿÿ;
        if ( file_exists( $_obfuscate_b9_qaEFdaQÿÿ ) )
        {
            $_obfuscate_6UUC = $_obfuscate_b9_qaEFdaQÿÿ;
            return $_obfuscate_6UUC;
        }
        $_obfuscate_6UUC = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
        return $_obfuscate_6UUC;
    }

    private function _getNeedJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_l2CIvUX0Kvp4 = $_obfuscate_mPAjEGLn = array( );
        $_obfuscate_3y0Y = "SELECT c.*, u.flowNumber, u.flowName AS puFlowName, ue.face, ue.truename FROM ".tname( $this->t_use_step_child_flow )." AS c LEFT JOIN ".tname( $this->t_use_flow )." AS u ON c.puFlowId = u.uFlowId LEFT JOIN ".tname( $this->main_user )." AS ue ON ue.uid = c.faqiUid ".( "WHERE c.`faqiUid` = ".$_obfuscate_7Ri3." AND c.`status` = 1" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_gkt['face'] = $this->_getFacePath( $_obfuscate_gkt['face'], $_obfuscate_gkt['faqiUid'] );
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

    private function _loadForDetailTable( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_F4AbnVRh = intval( getpar( $_GET, "flowId", getpar( $_POST, "flowId", 0 ) ) );
        $_obfuscate_TlvKhtsoOQÿÿ = intval( getpar( $_GET, "uFlowId", getpar( $_POST, "uFlowId", 0 ) ) );
        ( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, TRUE );
        $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ = new wfFieldFormaterForDealM( );
        $_obfuscate_HAEJuyDtQpZD9IAÿ = $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ->getDetailedTable( );
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->total = 0;
        $_obfuscate_SUjPN94Er7yI->data = array(
            "formHtml" => $_obfuscate_HAEJuyDtQpZD9IAÿ
        );
        $_obfuscate_SUjPN94Er7yI->pageset = $_obfuscate_SIUSR4F6['pageset'];
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
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

    private function _getSignature( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_6RYLWQÿÿ = $CNOA_DB->db_select( array( "signature", "url", "isUsePwd" ), "system_signature", "WHERE `uid`=".$_obfuscate_7Ri3 );
        echo json_encode( $_obfuscate_6RYLWQÿÿ );
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

    private function _getSendNextData( )
    {
        app::loadapp( "wf", "flowUseNew" )->api_getSendNextData( );
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
        $_obfuscate_z9ygGPQKQgSeYn4opTWVK8gÿ = new wfNewSendNextStep( );
        msg::callback( TRUE, lang( "flowSendSuccess" ) );
    }

    private function _getSortJsonData( )
    {
        $_obfuscate_a49UK2tYTQÿÿ = app::loadapp( "wf", "flowSetSort" )->api_getSortTree( "faqi", TRUE );
        if ( !is_array( $_obfuscate_a49UK2tYTQÿÿ ) )
        {
            $_obfuscate_a49UK2tYTQÿÿ = array( );
        }
        $_obfuscate_Y5hr7Fgÿ[] = array( "sortId" => "-1", "sortName" => "å¾…å‘èµ·å­æµç¨‹" );
        foreach ( $_obfuscate_a49UK2tYTQÿÿ as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_Y5hr7Fgÿ[] = array(
                "sortId" => $_obfuscate_6Aÿÿ['sortId'],
                "sortName" => $_obfuscate_6Aÿÿ['text']
            );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_Y5hr7Fgÿ;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _getAttLeaveDays( )
    {
        $_obfuscate_xs33Yt_k = app::loadapp( "wf", "flowUse" )->api_getWfAttLeaveDays( );
        header( "Content-Type: application/json" );
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_xs33Yt_k;
        echo $_obfuscate_NlQÿ->makeJsonData( );
    }

    private function _getAttEvectionDays( $return = FALSE )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $truename = $CNOA_SESSION->get( "TRUENAME" );
        $stime = strtotime( getpar( $_POST, "stime" ) );
        $etime = strtotime( getpar( $_POST, "etime" ) );
        $fieldsId = ( integer )getpar( $_POST, "fieldsId" );
        if ( $etime < $stime )
        {
            msg::callback( FALSE, lang( "endTimeNoBigStartTime" ) );
        }
        if ( $fieldsId !== 0 )
        {
            $result = $CNOA_DB->db_getone( array( "flowId", "odata" ), $this->t_set_field, "WHERE `id`=".$fieldsId );
            $odata = json_decode( str_replace( "'", "\"", $result['odata'] ), TRUE );
            $flowId = $result['flowId'];
            $fieldName = $odata['fieldsName'];
            $fieldId = $CNOA_DB->db_getfield( "id", $this->t_set_field, "WHERE `flowId`=".$flowId." AND `name`='{$fieldName}'" );
            include_once( CNOA_PATH."/app/att/inc/attCommon.class.php" );
            $days = app::loadapp( "att", "Person" )->api_getAttEvectionDays( $stime, $etime, $uid, "getdays", "" );
            $data = array( );
            $data['days'] = $days;
            $data['stime'] = date( "Y-m-d H:i:s", $stime );
            $data['etime'] = date( "Y-m-d H:i:s", $etime );
            $data['fieldId'] = $fieldId;
            if ( $return )
            {
                return $data;
            }
            ( );
            $ds = new dataStore( );
            $ds->data = $data;
            echo $ds->makeJsonData( );
        }
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

}

?>
