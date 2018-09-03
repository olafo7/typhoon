<?php

class wfFlowUseNewM extends wfCommon
{

    public function run( )
    {
        $_obfuscate_M_5JJw�� = getpar( $_GET, "task", "" );
        switch ( $_obfuscate_M_5JJw�� )
        {
        case "getJsonData" :
            $_obfuscate_vholQ�� = getpar( $_POST, "from", getpar( $_GET, "from", "" ) );
            if ( $_obfuscate_vholQ�� == "need" )
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
        $_obfuscate_vholQ�� = getpar( $_GET, "from" );
        if ( $_obfuscate_vholQ�� == "newflow" )
        {
            $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/new.m.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQ�� );
        exit( );
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mV9HBLY� = ( integer )getpar( $_POST, "start", getpar( $_GET, "start" ) );
        $_obfuscate_xvYeh9I� = ( integer )getpar( $_POST, "limit", getpar( $_GET, "limit" ) );
        $_obfuscate_v1GprsIz = ( integer )getpar( $_POST, "sortId", getpar( $_GET, "sortId" ) );
        if ( !is_numeric( $_obfuscate_v1GprsIz ) )
        {
            $this->_getChildJsonData( );
        }
        else
        {
            $_obfuscate_OTt14hA7OyXA44prAwU� = $this->_getSortPermitForNew( );
            $_obfuscate_IRFhnYw� = "`status`=1 AND `tplSort`=0 AND `flowId` IN (".implode( ",", $_obfuscate_OTt14hA7OyXA44prAwU� ).") ";
            $_obfuscate_3y0Y = "SELECT `f`.`flowId`, `f`.`name` AS `flowName`,`f`.`tplSort`, `f`.`flowType`, `f`.`startStepId`, `f`.`sortId`, `s`.`name` AS `sortName` FROM ".tname( $this->t_set_flow )." AS `f` LEFT JOIN ".tname( $this->t_set_sort )." AS `s` ON `f`.`sortId`=`s`.`sortId` ".( "WHERE `f`.`sortId`=".$_obfuscate_v1GprsIz." AND {$_obfuscate_IRFhnYw�} " )."ORDER BY f.posttime DESC ".( "LIMIT ".$_obfuscate_mV9HBLY�.", {$_obfuscate_xvYeh9I�}" );
            $_obfuscate_ammigv8� = $CNOA_DB->query( $_obfuscate_3y0Y );
            $_obfuscate_a96y4zw� = array( );
            while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_ammigv8� ) )
            {
                $_obfuscate_a96y4zw�[] = $_obfuscate_gkt;
            }
            ( );
            $_obfuscate_SUjPN94Er7yI = new dataStore( );
            $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_a96y4zw�;
            $_obfuscate_SUjPN94Er7yI->total = $CNOA_DB->db_getcount( $this->t_set_flow, "WHERE `sortId`=".$_obfuscate_v1GprsIz." AND {$_obfuscate_IRFhnYw�}" );
            echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        }
    }

    private function _getChildJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mV9HBLY� = getpar( $_GET, "start", 0 );
        $_obfuscate_xvYeh9I� = getpar( $_GET, "limit" );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_3y0Y = "SELECT `f`.`flowId`,`f`.`name` AS `flowName`,`f`.`tplSort`,`f`.`flowType`,`f`.`startStepId`,`s`.`name` AS `sortName` FROM ".tname( $this->t_use_step_child_flow )." AS `c` LEFT JOIN ".tname( $this->t_set_flow )." AS `f` ON `f`.`flowId`=`c`.`flowId` LEFT JOIN ".tname( $this->t_set_sort )." AS `s` ON `s`.`sortId`=`f`.`sortId` ".( "WHERE `c`.`faqiUid` = ".$_obfuscate_7Ri3." AND `c`.`status` = 1 " );
        "LIMIT ".$_obfuscate_mV9HBLY�.", {$_obfuscate_xvYeh9I�}";
        $_obfuscate_ammigv8� = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_a96y4zw� = array( );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_ammigv8� ) )
        {
            $_obfuscate_a96y4zw�[] = $_obfuscate_gkt;
        }
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_a96y4zw�;
        $_obfuscate_NlQ�->total = $CNOA_DB->db_getcount( $this->t_use_step_child_flow, "WHERE `faqiUid` = ".$_obfuscate_7Ri3." AND `status` = 1 " );
        echo $_obfuscate_NlQ�->makeJsonData( );
    }

    private function _getFacePath( $_obfuscate_pp9pYw��, $_obfuscate_7Ri3 )
    {
        if ( empty( $_obfuscate_pp9pYw�� ) )
        {
            $_obfuscate_6UUC = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
            return $_obfuscate_6UUC;
        }
        $_obfuscate_b9_qaEFdaQ�� = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/".$_obfuscate_7Ri3."/130x130_".$_obfuscate_pp9pYw��;
        if ( file_exists( $_obfuscate_b9_qaEFdaQ�� ) )
        {
            $_obfuscate_6UUC = $_obfuscate_b9_qaEFdaQ��;
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

    private function _loadForDetailTable( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_F4AbnVRh = intval( getpar( $_GET, "flowId", getpar( $_POST, "flowId", 0 ) ) );
        $_obfuscate_TlvKhtsoOQ�� = intval( getpar( $_GET, "uFlowId", getpar( $_POST, "uFlowId", 0 ) ) );
        ( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, TRUE );
        $_obfuscate_fr489CNrjWzinRUKCuozmmU� = new wfFieldFormaterForDealM( );
        $_obfuscate_HAEJuyDtQpZD9IA� = $_obfuscate_fr489CNrjWzinRUKCuozmmU�->getDetailedTable( );
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->total = 0;
        $_obfuscate_SUjPN94Er7yI->data = array(
            "formHtml" => $_obfuscate_HAEJuyDtQpZD9IA�
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
        $_obfuscate_fMfssw�� = $CNOA_DB->db_select( "*", $this->t_set_step_user, "WHERE `firstStep`=1" );
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

    private function _getSignature( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_6RYLWQ�� = $CNOA_DB->db_select( array( "signature", "url", "isUsePwd" ), "system_signature", "WHERE `uid`=".$_obfuscate_7Ri3 );
        echo json_encode( $_obfuscate_6RYLWQ�� );
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
            $_obfuscate_2gg� = new fs( );
            $_obfuscate_8CpDPPa = $_obfuscate_2gg�->add( $_obfuscate_9ga6vjaQ61MybPYk, 1 );
            foreach ( $_obfuscate_8CpDPPa as $_obfuscate_2PfU )
            {
                $GLOBALS['_POST']["wf_attach_".$_obfuscate_2PfU] = 1;
            }
        }
        unset( $_POST['upload_attach'] );
        ( );
        $_obfuscate_z9ygGPQKQgSeYn4opTWVK8g� = new wfNewSendNextStep( );
        msg::callback( TRUE, lang( "flowSendSuccess" ) );
    }

    private function _getSortJsonData( )
    {
        $_obfuscate_a49UK2tYTQ�� = app::loadapp( "wf", "flowSetSort" )->api_getSortTree( "faqi", TRUE );
        if ( !is_array( $_obfuscate_a49UK2tYTQ�� ) )
        {
            $_obfuscate_a49UK2tYTQ�� = array( );
        }
        $_obfuscate_Y5hr7Fg�[] = array( "sortId" => "-1", "sortName" => "待发起子流程" );
        foreach ( $_obfuscate_a49UK2tYTQ�� as $_obfuscate_6A�� )
        {
            $_obfuscate_Y5hr7Fg�[] = array(
                "sortId" => $_obfuscate_6A��['sortId'],
                "sortName" => $_obfuscate_6A��['text']
            );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_Y5hr7Fg�;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _getAttLeaveDays( )
    {
        $_obfuscate_xs33Yt_k = app::loadapp( "wf", "flowUse" )->api_getWfAttLeaveDays( );
        header( "Content-Type: application/json" );
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_xs33Yt_k;
        echo $_obfuscate_NlQ�->makeJsonData( );
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

}

?>
