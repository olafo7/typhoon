<?php

class wfFlowSetFlow extends wfCommon
{

    public static $OPERATE_TYPE_HUIQIAN = 1;
    public static $OPERATE_TYPE_FENFA = 2;

    public function run( )
    {
        $_obfuscate_M_5JJw�� = getpar( $_GET, "task", getpar( $_POST, "task", "" ) );
        switch ( $_obfuscate_M_5JJw�� )
        {
        case "loadPage" :
            $this->_loadPage( );
            break;
        case "getJsonData" :
            $this->_getJsonData( );
            break;
        case "submitFlowDesignData" :
            $this->_submitFlowDesignData( );
            break;
        case "loadFlowDesignData" :
            $this->_loadFlowDesignData( );
            break;
        case "getSortTree" :
            app::loadapp( "wf", "flowSetSort" )->api_getSortTree( "all" );
            break;
        case "getSortStore" :
            $this->_getSortStore( );
            break;
        case "submit" :
            $this->_submit( );
            break;
        case "loadFormData" :
            $this->_loadFormData( );
            break;
        case "changeStatus" :
            $this->_changeStatus( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $_obfuscate_phKp89pDgwQ� = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $_obfuscate_phKp89pDgwQ� );
            exit( );
        case "getStructTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            echo app::loadapp( "main", "struct" )->api_getStructTree( );
            exit( );
        case "ms_loadTemplateFile" :
            $this->_ms_loadTemplateFile( );
            break;
        case "ms_submitMsOfficeData" :
            $this->_ms_submitMsOfficeData( );
            break;
        case "loadFlowTotal" :
            $this->_loadFlowTotal( );
            break;
        case "delete" :
            $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
            $_obfuscate_aMwmYI� = getpar( $_POST, "checked", "" );
            $_obfuscate_XkuTFqZ6Tmk� = getpar( $_POST, "flowType", 0 );
            $_obfuscate_pEvU7Kz2Yw�� = getpar( $_POST, "tplSort", 0 );
            $this->_delete( $_obfuscate_F4AbnVRh, $_obfuscate_aMwmYI�, $_obfuscate_XkuTFqZ6Tmk�, $_obfuscate_pEvU7Kz2Yw�� );
            break;
        case "getStation" :
            $this->_getStation( );
            break;
        case "selector" :
            $_obfuscate_Ns_JyWSm = getpar( $_GET, "target", getpar( $_POST, "target", "" ) );
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            switch ( $_obfuscate_Ns_JyWSm )
            {
            case "user" :
                app::loadapp( "main", "user" )->api_getSelectorData( );
                exit( );
            case "station" :
                app::loadapp( "main", "station" )->api_getSelectorData( );
                exit( );
            case "dept" :
                app::loadapp( "main", "struct" )->api_getSelectorData( );
                exit( );
            case "job" :
                app::loadapp( "main", "job" )->api_getSelectorData( );
            }
            exit( );
        case "clone" :
            $this->_clone( );
            break;
        case "exportFlow" :
            $this->_exportFlow( );
            break;
        case "exportFlowDownload" :
            $this->_exportFlowDownload( );
            break;
        case "importFlow" :
            $this->_importFlow( );
            break;
        case "cloneFreeFlow" :
            $this->_cloneFreeFlow( );
            break;
        case "getFlowConvergence" :
            $this->_getFlowConvergence( );
            break;
        case "flowKongjianData" :
            $this->_flowKongjianData( );
            break;
        case "getOrderList" :
            $this->_getOrderList( );
            break;
        case "getOrderForm" :
            $this->_getOrderForm( );
            break;
        case "taoHong" :
            $this->_taoHong( );
            break;
        case "checkStepCondition" :
            $this->_checkStepCondition( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $_obfuscate_vholQ�� = getpar( $_GET, "from", getpar( $_POST, "from", "list" ) );
        switch ( $_obfuscate_vholQ�� )
        {
        case "list" :
            $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/set/flow.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQ�� );
            exit( );
        case "taohong" :
            $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/set/taohong.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQ�� );
        }
    }

    private function _submitFlowDesignData( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_6RYLWQ�� = json_decode( $_POST['data'], TRUE );
        $_obfuscate_4FIIlesHxw�� = $_obfuscate_6RYLWQ��['flowXml'];
        $_obfuscate_Wpkgl9Q6Gm = $_obfuscate_6RYLWQ��['flowHtml5'];
        $_obfuscate_S0PSA37yAw�� = $this->__updateStepsInfo( $_obfuscate_4FIIlesHxw��, $_obfuscate_F4AbnVRh );
        $_obfuscate_F1iF5t0j = array( );
        $_obfuscate_F1iF5t0j['flowXml'] = addslashes( $_obfuscate_4FIIlesHxw�� );
        $_obfuscate_F1iF5t0j['flowHtml5'] = addslashes( $_obfuscate_Wpkgl9Q6Gm );
        $_obfuscate_F1iF5t0j['startStepId'] = $_obfuscate_S0PSA37yAw��[1];
        $CNOA_DB->db_update( $_obfuscate_F1iF5t0j, $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( !is_array( $_obfuscate_6RYLWQ��['steps'] ) )
        {
            $_obfuscate_6RYLWQ��['steps'] = array( );
        }
        $_obfuscate_SnVDWpzHiHs� = array( );
        foreach ( $_obfuscate_6RYLWQ��['steps'] as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_0Ul8BBkt = $_obfuscate_6A��['base']['stepId'];
            $_obfuscate_SnVDWpzHiHs�[] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_6RYLWQ�� = array( );
            $_obfuscate_6RYLWQ��['doBtnText'] = $_obfuscate_6A��['base']['doBtnText'] == "" ? 1 : $_obfuscate_6A��['base']['doBtnText'];
            $_obfuscate_6RYLWQ��['allowReject'] = $_obfuscate_6A��['base']['allowReject'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowHuiqian'] = $_obfuscate_6A��['base']['allowHuiqian'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowFenfa'] = $_obfuscate_6A��['base']['allowFenfa'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowTuihui'] = $_obfuscate_6A��['base']['allowTuihui'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowPrint'] = $_obfuscate_6A��['base']['allowPrint'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowCallback'] = $_obfuscate_6A��['base']['allowCallback'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowCancel'] = $_obfuscate_6A��['base']['allowCancel'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowYijian'] = $_obfuscate_6A��['base']['allowYijian'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowAttachAdd'] = $_obfuscate_6A��['base']['allowAttachAdd'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowAttachView'] = $_obfuscate_6A��['base']['allowAttachView'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowAttachEdit'] = $_obfuscate_6A��['base']['allowAttachEdit'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowAttachDelete'] = $_obfuscate_6A��['base']['allowAttachDelete'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowAttachDown'] = $_obfuscate_6A��['base']['allowAttachDown'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowHqAttachAdd'] = $_obfuscate_6A��['base']['allowHqAttachAdd'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowHqAttachView'] = $_obfuscate_6A��['base']['allowHqAttachView'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowHqAttachEdit'] = $_obfuscate_6A��['base']['allowHqAttachEdit'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowHqAttachDelete'] = $_obfuscate_6A��['base']['allowHqAttachDelete'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowHqAttachDown'] = $_obfuscate_6A��['base']['allowHqAttachDown'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowWordEdit'] = $_obfuscate_6A��['base']['allowWordEdit'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowAttachWordEdit'] = $_obfuscate_6A��['base']['allowAttachWordEdit'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['allowSms'] = $_obfuscate_6A��['base']['allowSms'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQ��['stepTime'] = floatval( $_obfuscate_6A��['base']['stepTime'] );
            $_obfuscate_6RYLWQ��['urgeBefore'] = intval( $_obfuscate_6A��['base']['urgeBefore'] );
            $_obfuscate_6RYLWQ��['urgeTarget'] = empty( $_obfuscate_6A��['base']['urgeTarget'] ) ? 2 : intval( $_obfuscate_6A��['base']['urgeTarget'] );
            $_obfuscate_6RYLWQ��['bingnames'] = $_obfuscate_6A��['base']['bingnames'];
            $_obfuscate_6RYLWQ��['bingids'] = $_obfuscate_6A��['base']['bingids'];
            $_obfuscate_6RYLWQ��['faqiFlow'] = $_obfuscate_6A��['base']['faqiFlow'];
            $_obfuscate_6RYLWQ��['endFlow'] = $_obfuscate_6A��['base']['endFlow'];
            $_obfuscate_6RYLWQ��['sharefile'] = $_obfuscate_6A��['base']['sharefile'] == "on" ? 1 : 0;
            $CNOA_DB->db_update( $_obfuscate_6RYLWQ��, $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `stepId`='{$_obfuscate_0Ul8BBkt}'" );
            $this->_filterPermit( $_obfuscate_6A��['base'], $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt );
            if ( $_obfuscate_6A��['base']['stepId'] != 2 && $_obfuscate_6A��['base']['stepId'] != 3 )
            {
                $this->_fillAutoFenFaUsers( $_obfuscate_6RYLWQ��['allowFenfa'], $_obfuscate_F4AbnVRh, $_obfuscate_6A��['base']['stepId'], $_obfuscate_6A��['base']['fenfaPermit']['autoFenfa'] );
            }
            if ( !empty( $_obfuscate_6A��['fields'] ) )
            {
                $this->_filterFields( $_obfuscate_6A��['fields'], $_obfuscate_6A��['base']['stepId'], $_obfuscate_F4AbnVRh );
            }
            $this->_filterUser( $_obfuscate_6A��['user'], $_obfuscate_6A��['base']['stepId'], $_obfuscate_F4AbnVRh, $_obfuscate_F1iF5t0j['startStepId'] );
            if ( !empty( $_obfuscate_6A��['condition'] ) )
            {
                $this->_filterCondition( $_obfuscate_6A��['condition'], $_obfuscate_6A��['base']['stepId'], $_obfuscate_F4AbnVRh );
            }
            if ( isset( $_obfuscate_6A��['convergence'] ) )
            {
                $this->_filterConvergence( $_obfuscate_6A��['convergence'], $_obfuscate_6A��['base']['stepId'], $_obfuscate_F4AbnVRh );
            }
            if ( isset( $_obfuscate_6A��['child'] ) )
            {
                $this->_filterChild( $_obfuscate_6A��['child'], $_obfuscate_F4AbnVRh, $_obfuscate_6A��['base']['stepId'] );
            }
            if ( isset( $_obfuscate_6A��['dealWay'] ) )
            {
                $this->_filterDeal( $_obfuscate_6A��['dealWay'], $_obfuscate_F4AbnVRh, $_obfuscate_6A��['base']['stepId'] );
            }
            if ( isset( $_obfuscate_6A��['childPermit'] ) )
            {
                $this->_filterChildPermit( $_obfuscate_6A��['childPermit'], $_obfuscate_F4AbnVRh );
            }
        }
        foreach ( $_obfuscate_S0PSA37yAw��[0] as $_obfuscate_6A�� )
        {
            if ( !in_array( $_obfuscate_6A��, $_obfuscate_SnVDWpzHiHs� ) )
            {
                $this->_filterUser( NULL, $_obfuscate_6A��, $_obfuscate_F4AbnVRh, $_obfuscate_F1iF5t0j['startStepId'] );
            }
        }
        $_obfuscate_neM4JBUJlmg� = $CNOA_DB->db_getfield( "name", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 3662, $_obfuscate_neM4JBUJlmg�, "流程步骤" );
        msg::callback( TRUE, lang( "saved" ) );
    }

    private function _filterFields( $_obfuscate_tjILu7ZH, $_obfuscate_0Ul8BBkt, $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        $CNOA_DB->db_delete( $this->t_set_step_fields, "WHERE `stepId`='".$_obfuscate_0Ul8BBkt."' AND `flowId`='{$_obfuscate_F4AbnVRh}' " );
        if ( !is_array( $_obfuscate_tjILu7ZH ) )
        {
            $_obfuscate_tjILu7ZH = array( );
        }
        foreach ( $_obfuscate_tjILu7ZH as $_obfuscate_6A�� )
        {
            $_obfuscate_6RYLWQ�� = array( );
            $_obfuscate_6RYLWQ��['stepId'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_6RYLWQ��['flowId'] = $_obfuscate_F4AbnVRh;
            if ( $_obfuscate_6A��['id'][0] == "d" )
            {
                $_obfuscate_6RYLWQ��['from'] = 1;
                $_obfuscate_6RYLWQ��['fieldId'] = substr( $_obfuscate_6A��['id'], 2 );
            }
            else
            {
                $_obfuscate_6RYLWQ��['from'] = 0;
                $_obfuscate_6RYLWQ��['fieldId'] = $_obfuscate_6A��['id'];
            }
            $_obfuscate_6RYLWQ��['show'] = $_obfuscate_6A��['show'];
            $_obfuscate_6RYLWQ��['hide'] = $_obfuscate_6A��['hide'];
            $_obfuscate_6RYLWQ��['write'] = $_obfuscate_6A��['write'];
            $_obfuscate_6RYLWQ��['must'] = $_obfuscate_6A��['must'];
            if ( $_obfuscate_6A��['otype'] == "calculate" && $_obfuscate_6A��['show'] == 1 )
            {
                $_obfuscate_6RYLWQ��['write'] = 1;
            }
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_set_step_fields );
        }
    }

    private function _filterChildPermit( $_obfuscate_tjILu7ZH, $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        if ( !is_array( $_obfuscate_tjILu7ZH ) )
        {
            $_obfuscate_tjILu7ZH = array( );
        }
        foreach ( $_obfuscate_tjILu7ZH as $_obfuscate_6A�� )
        {
            $_obfuscate_6RYLWQ�� = array( );
            if ( $_obfuscate_6A��['id'][0] == "d" )
            {
                $_obfuscate_8jhldA9Y9A�� = substr( $_obfuscate_6A��['id'], 2 );
            }
            else
            {
                $_obfuscate_8jhldA9Y9A�� = $_obfuscate_6A��['id'];
            }
            $_obfuscate_gftfagw� = $CNOA_DB->db_getcount( $this->t_set_step_fields, "WHERE `stepId`=".$_obfuscate_6A��['stepId']." AND `flowId`={$_obfuscate_F4AbnVRh}" );
            $_obfuscate_6RYLWQ��['status'] = $_obfuscate_6A��['status'];
            $CNOA_DB->db_update( $_obfuscate_6RYLWQ��, $this->t_set_step_fields, "WHERE `stepId`=".$_obfuscate_6A��['stepId']." AND `flowId`={$_obfuscate_F4AbnVRh} AND `fieldId`={$_obfuscate_8jhldA9Y9A��}" );
        }
    }

    private function _filterUser( $_obfuscate_m2Kuww��, $_obfuscate_0Ul8BBkt, $_obfuscate_F4AbnVRh, $_obfuscate_vpZO7cBY1GZYtnU� )
    {
        global $CNOA_DB;
        $CNOA_DB->db_delete( $this->t_set_step_user, "WHERE `stepId`='".$_obfuscate_0Ul8BBkt."' AND `flowId`='{$_obfuscate_F4AbnVRh}' " );
        if ( empty( $_obfuscate_m2Kuww�� ) || !isset( $_obfuscate_m2Kuww�� ) )
        {
            $_obfuscate_6RYLWQ�� = array( );
            $_obfuscate_6RYLWQ��['stepId'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_6RYLWQ��['firstStep'] = $_obfuscate_0Ul8BBkt == $_obfuscate_vpZO7cBY1GZYtnU� ? 1 : 0;
            $_obfuscate_6RYLWQ��['flowId'] = $_obfuscate_F4AbnVRh;
            $_obfuscate_6RYLWQ��['type'] = "";
            $_obfuscate_6RYLWQ��['rule_p'] = "";
            $_obfuscate_6RYLWQ��['rule_d'] = "";
            $_obfuscate_6RYLWQ��['rule_s'] = "";
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_set_step_user );
        }
        else
        {
            $_obfuscate_6RYLWQ�� = array( );
            $_obfuscate_6RYLWQ��['stepId'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_6RYLWQ��['firstStep'] = $_obfuscate_0Ul8BBkt == $_obfuscate_vpZO7cBY1GZYtnU� ? 1 : 0;
            $_obfuscate_6RYLWQ��['flowId'] = $_obfuscate_F4AbnVRh;
            $_obfuscate_6RYLWQ��['type'] = "";
            $_obfuscate_6RYLWQ��['rule_p'] = "";
            $_obfuscate_6RYLWQ��['rule_d'] = "";
            $_obfuscate_6RYLWQ��['rule_s'] = "";
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_set_step_user );
            if ( !is_array( $_obfuscate_m2Kuww��['rule'] ) )
            {
                $_obfuscate_m2Kuww��['rule'] = array( );
            }
            foreach ( $_obfuscate_m2Kuww��['rule'] as $_obfuscate_6A�� )
            {
                $_obfuscate_6RYLWQ�� = array( );
                $_obfuscate_6RYLWQ��['stepId'] = $_obfuscate_0Ul8BBkt;
                $_obfuscate_6RYLWQ��['firstStep'] = $_obfuscate_0Ul8BBkt == $_obfuscate_vpZO7cBY1GZYtnU� ? 1 : 0;
                $_obfuscate_6RYLWQ��['flowId'] = $_obfuscate_F4AbnVRh;
                $_obfuscate_6RYLWQ��['type'] = "rule";
                $_obfuscate_6RYLWQ��['rule_p'] = $_obfuscate_6A��['people'];
                $_obfuscate_6RYLWQ��['rule_d'] = $_obfuscate_6A��['dept'];
                $_obfuscate_6RYLWQ��['rule_s'] = $_obfuscate_6A��['id'];
                $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_set_step_user );
            }
            if ( !is_array( $_obfuscate_m2Kuww��['people'] ) )
            {
                $_obfuscate_m2Kuww��['people'] = array( );
            }
            foreach ( $_obfuscate_m2Kuww��['people'] as $_obfuscate_6A�� )
            {
                $_obfuscate_6RYLWQ�� = array( );
                $_obfuscate_6RYLWQ��['rule_p'] = "";
                $_obfuscate_6RYLWQ��['rule_d'] = "";
                $_obfuscate_6RYLWQ��['stepId'] = $_obfuscate_0Ul8BBkt;
                $_obfuscate_6RYLWQ��['firstStep'] = $_obfuscate_0Ul8BBkt == $_obfuscate_vpZO7cBY1GZYtnU� ? 1 : 0;
                $_obfuscate_6RYLWQ��['flowId'] = $_obfuscate_F4AbnVRh;
                $_obfuscate_6RYLWQ��['type'] = "people";
                $_obfuscate_6RYLWQ��['people'] = $_obfuscate_6A��['id'];
                $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_set_step_user );
            }
            if ( !is_array( $_obfuscate_m2Kuww��['exclude'] ) )
            {
                $_obfuscate_m2Kuww��['exclude'] = array( );
            }
            foreach ( $_obfuscate_m2Kuww��['exclude'] as $_obfuscate_6A�� )
            {
                $_obfuscate_6RYLWQ�� = array( );
                $_obfuscate_6RYLWQ��['rule_p'] = "";
                $_obfuscate_6RYLWQ��['rule_d'] = "";
                $_obfuscate_6RYLWQ��['stepId'] = $_obfuscate_0Ul8BBkt;
                $_obfuscate_6RYLWQ��['firstStep'] = $_obfuscate_0Ul8BBkt == $_obfuscate_vpZO7cBY1GZYtnU� ? 1 : 0;
                $_obfuscate_6RYLWQ��['flowId'] = $_obfuscate_F4AbnVRh;
                $_obfuscate_6RYLWQ��['type'] = "exclude";
                $_obfuscate_6RYLWQ��['exclude'] = $_obfuscate_6A��['id'];
                $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_set_step_user );
            }
            if ( !is_array( $_obfuscate_m2Kuww��['kong'] ) )
            {
                $_obfuscate_m2Kuww��['kong'] = array( );
            }
            foreach ( $_obfuscate_m2Kuww��['kong'] as $_obfuscate_6A�� )
            {
                $_obfuscate_6RYLWQ�� = array( );
                $_obfuscate_6RYLWQ��['rule_p'] = "";
                $_obfuscate_6RYLWQ��['rule_d'] = "";
                $_obfuscate_6RYLWQ��['stepId'] = $_obfuscate_0Ul8BBkt;
                $_obfuscate_6RYLWQ��['firstStep'] = $_obfuscate_0Ul8BBkt == $_obfuscate_vpZO7cBY1GZYtnU� ? 1 : 0;
                $_obfuscate_6RYLWQ��['flowId'] = $_obfuscate_F4AbnVRh;
                $_obfuscate_6RYLWQ��['type'] = "kong";
                $_obfuscate_6RYLWQ��['kong'] = $_obfuscate_6A��['id'];
                $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_set_step_user );
            }
            if ( !is_array( $_obfuscate_m2Kuww��['dept'] ) )
            {
                $_obfuscate_m2Kuww��['dept'] = array( );
            }
            foreach ( $_obfuscate_m2Kuww��['dept'] as $_obfuscate_6A�� )
            {
                $_obfuscate_6RYLWQ�� = array( );
                $_obfuscate_6RYLWQ��['rule_p'] = "";
                $_obfuscate_6RYLWQ��['rule_d'] = "";
                $_obfuscate_6RYLWQ��['stepId'] = $_obfuscate_0Ul8BBkt;
                $_obfuscate_6RYLWQ��['firstStep'] = $_obfuscate_0Ul8BBkt == $_obfuscate_vpZO7cBY1GZYtnU� ? 1 : 0;
                $_obfuscate_6RYLWQ��['flowId'] = $_obfuscate_F4AbnVRh;
                $_obfuscate_6RYLWQ��['type'] = "dept";
                $_obfuscate_6RYLWQ��['dept'] = $_obfuscate_6A��['id'];
                $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_set_step_user );
            }
            if ( !is_array( $_obfuscate_m2Kuww��['station'] ) )
            {
                $_obfuscate_m2Kuww��['station'] = array( );
            }
            foreach ( $_obfuscate_m2Kuww��['station'] as $_obfuscate_6A�� )
            {
                $_obfuscate_6RYLWQ�� = array( );
                $_obfuscate_6RYLWQ��['rule_p'] = "";
                $_obfuscate_6RYLWQ��['rule_d'] = "";
                $_obfuscate_6RYLWQ��['stepId'] = $_obfuscate_0Ul8BBkt;
                $_obfuscate_6RYLWQ��['firstStep'] = $_obfuscate_0Ul8BBkt == $_obfuscate_vpZO7cBY1GZYtnU� ? 1 : 0;
                $_obfuscate_6RYLWQ��['flowId'] = $_obfuscate_F4AbnVRh;
                $_obfuscate_6RYLWQ��['type'] = "station";
                $_obfuscate_6RYLWQ��['station'] = $_obfuscate_6A��['id'];
                $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_set_step_user );
            }
            if ( !is_array( $_obfuscate_m2Kuww��['deptStation'] ) )
            {
                $_obfuscate_m2Kuww��['deptStation'] = array( );
            }
            foreach ( $_obfuscate_m2Kuww��['deptStation'] as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_O6QLLac� = explode( ",", $_obfuscate_6A��['id'] );
                $_obfuscate_6RYLWQ�� = array( );
                $_obfuscate_6RYLWQ��['rule_p'] = "";
                $_obfuscate_6RYLWQ��['rule_d'] = "";
                $_obfuscate_6RYLWQ��['stepId'] = $_obfuscate_0Ul8BBkt;
                $_obfuscate_6RYLWQ��['firstStep'] = $_obfuscate_0Ul8BBkt == $_obfuscate_vpZO7cBY1GZYtnU� ? 1 : 0;
                $_obfuscate_6RYLWQ��['flowId'] = $_obfuscate_F4AbnVRh;
                $_obfuscate_6RYLWQ��['type'] = "deptstation";
                $_obfuscate_6RYLWQ��['dept'] = $_obfuscate_O6QLLac�[0];
                $_obfuscate_6RYLWQ��['station'] = $_obfuscate_O6QLLac�[1];
                $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_set_step_user );
            }
            if ( 0 < count( $_obfuscate_m2Kuww��['deptStation'] ) || 0 < count( $_obfuscate_m2Kuww��['station'] ) || 0 < count( $_obfuscate_m2Kuww��['dept'] ) || 0 < count( $_obfuscate_m2Kuww��['people'] ) || 0 < count( $_obfuscate_m2Kuww��['exclude'] ) || 0 < count( $_obfuscate_m2Kuww��['rule'] ) )
            {
                $CNOA_DB->db_delete( $this->t_set_step_user, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `stepId`='{$_obfuscate_0Ul8BBkt}' AND `type`=''" );
            }
        }
    }

    private function _filterCondition( $_obfuscate_XP4WpjIMhOSD, $_obfuscate_0Ul8BBkt, $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        $CNOA_DB->db_delete( $this->t_set_step_condition, "WHERE `stepId`='".$_obfuscate_0Ul8BBkt."' AND `flowId`='{$_obfuscate_F4AbnVRh}' " );
        $_obfuscate_6RYLWQ��['stepId'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_6RYLWQ��['flowId'] = $_obfuscate_F4AbnVRh;
        if ( !is_array( $_obfuscate_XP4WpjIMhOSD ) )
        {
            $_obfuscate_XP4WpjIMhOSD = array( );
        }
        foreach ( $_obfuscate_XP4WpjIMhOSD as $_obfuscate_6A�� )
        {
            $_obfuscate_6RYLWQ��['nextStepId'] = $_obfuscate_6A��['id'];
            foreach ( $_obfuscate_6A��['items'] as $_obfuscate_bRQ� )
            {
                $_obfuscate_6RYLWQ��['text'] = addslashes( $_obfuscate_bRQ�['text'] );
                if ( count( $_obfuscate_bRQ�['items'] ) <= 1 )
                {
                    foreach ( $_obfuscate_bRQ�['items'] as $_obfuscate_NZM� )
                    {
                        if ( in_array( $_obfuscate_NZM�['name'], array( "s|n_n", "s|n_s", "s|n_d", "s|d_n", "s|d_s", "s|d_d" ) ) )
                        {
                            $_obfuscate_6RYLWQ��['fieldType'] = str_replace( "s|", "", $_obfuscate_NZM�['name'] );
                            $_obfuscate_6RYLWQ��['name'] = 0;
                        }
                        else
                        {
                            $_obfuscate_6RYLWQ��['fieldType'] = "nor";
                            $_obfuscate_6RYLWQ��['name'] = $_obfuscate_NZM�['name'];
                        }
                        $_obfuscate_6RYLWQ��['rule'] = $_obfuscate_NZM�['rule'];
                        $_obfuscate_6RYLWQ��['ovalue'] = $_obfuscate_NZM�['ovalue'];
                        $_obfuscate_6RYLWQ��['orAnd'] = empty( $_obfuscate_bRQ�['orAnd'] ) ? $_obfuscate_NZM�['orAnd'] : $_obfuscate_bRQ�['orAnd'];
                        $_obfuscate_6RYLWQ��['pid'] = 0;
                        $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_set_step_condition );
                    }
                }
                else
                {
                    $_obfuscate_jYGVpw�� = TRUE;
                    $_obfuscate_fdpE = 0;
                    foreach ( $_obfuscate_bRQ�['items'] as $_obfuscate_5w�� => $_obfuscate_NZM� )
                    {
                        if ( in_array( $_obfuscate_NZM�['name'], array( "s|n_n", "s|n_s", "s|n_d", "s|d_n", "s|d_s", "s|d_d" ) ) )
                        {
                            $_obfuscate_6RYLWQ��['fieldType'] = str_replace( "s|", "", $_obfuscate_NZM�['name'] );
                            $_obfuscate_6RYLWQ��['name'] = 0;
                        }
                        else
                        {
                            $_obfuscate_6RYLWQ��['fieldType'] = "nor";
                            $_obfuscate_6RYLWQ��['name'] = $_obfuscate_NZM�['name'];
                        }
                        $_obfuscate_6RYLWQ��['rule'] = $_obfuscate_NZM�['rule'];
                        $_obfuscate_6RYLWQ��['ovalue'] = $_obfuscate_NZM�['ovalue'];
                        $_obfuscate_6RYLWQ��['orAnd'] = $_obfuscate_5w�� == 0 ? $_obfuscate_bRQ�['orAnd'] : $_obfuscate_NZM�['orAnd'];
                        if ( $_obfuscate_jYGVpw�� )
                        {
                            $_obfuscate_6RYLWQ��['head'] = 1;
                            $_obfuscate_6RYLWQ��['pid'] = 0;
                            $_obfuscate_fdpE = $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_set_step_condition );
                            $_obfuscate_jYGVpw�� = FALSE;
                        }
                        else
                        {
                            $_obfuscate_6RYLWQ��['head'] = 0;
                            $_obfuscate_6RYLWQ��['pid'] = $_obfuscate_fdpE;
                            $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_set_step_condition );
                        }
                    }
                }
            }
        }
    }

    private function _filterPermit( $_obfuscate_O1dluw��, $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $_obfuscate_jXRqIoPJxOTkM9U_GQ�� = $_obfuscate_O1dluw��['huiqianPermit'];
        $_obfuscate_kou4P057dsY2Dzs� = $_obfuscate_O1dluw��['fenfaPermit'];
        if ( $_obfuscate_O1dluw��['allowHuiqian'] && 0 < count( $_obfuscate_jXRqIoPJxOTkM9U_GQ�� ) )
        {
            $_obfuscate_6RYLWQ�� = $this->_savePermit( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt, self::$OPERATE_TYPE_HUIQIAN, $_obfuscate_jXRqIoPJxOTkM9U_GQ�� );
        }
        else
        {
            $CNOA_DB->db_delete( $this->t_set_step_permit, "WHERE flowId=".$_obfuscate_F4AbnVRh." AND stepId={$_obfuscate_0Ul8BBkt} AND operate=".self::$OPERATE_TYPE_HUIQIAN );
        }
        if ( $_obfuscate_O1dluw��['allowFenfa'] && 0 < count( $_obfuscate_kou4P057dsY2Dzs� ) )
        {
            $_obfuscate_6RYLWQ�� = $this->_savePermit( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt, self::$OPERATE_TYPE_FENFA, $_obfuscate_kou4P057dsY2Dzs� );
        }
        else
        {
            $CNOA_DB->db_delete( $this->t_set_step_permit, "WHERE flowId=".$_obfuscate_F4AbnVRh." AND stepId={$_obfuscate_0Ul8BBkt} AND operate=".self::$OPERATE_TYPE_FENFA );
        }
    }

    private function _savePermit( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt, $_obfuscate_bBdOnB4sqA��, $_obfuscate_eVTMIa1A )
    {
        global $CNOA_DB;
        $_obfuscate_m2Kuww�� = $_obfuscate_vwGQSA�� = $_obfuscate_6mlyHg�� = array( );
        if ( is_array( $_obfuscate_eVTMIa1A['user'] ) )
        {
            foreach ( $_obfuscate_eVTMIa1A['user'] as $_obfuscate_cBms )
            {
                $_obfuscate_m2Kuww��[] = $_obfuscate_cBms[0];
            }
        }
        if ( is_array( $_obfuscate_eVTMIa1A['dept'] ) )
        {
            foreach ( $_obfuscate_eVTMIa1A['dept'] as $_obfuscate_cBms )
            {
                $_obfuscate_vwGQSA��[] = $_obfuscate_cBms[0];
            }
        }
        if ( is_array( $_obfuscate_eVTMIa1A['rule'] ) )
        {
            foreach ( $_obfuscate_eVTMIa1A['rule'] as $_obfuscate_cBms )
            {
                $_obfuscate_6mlyHg��[] = $_obfuscate_cBms[0];
            }
        }
        $_obfuscate_6RYLWQ�� = array( );
        if ( !empty( $_obfuscate_m2Kuww�� ) )
        {
            $_obfuscate_6RYLWQ��['user'] = implode( ",", $_obfuscate_m2Kuww�� );
        }
        if ( !empty( $_obfuscate_vwGQSA�� ) )
        {
            $_obfuscate_6RYLWQ��['dept'] = implode( ",", $_obfuscate_vwGQSA�� );
        }
        if ( !empty( $_obfuscate_6mlyHg�� ) )
        {
            $_obfuscate_6RYLWQ��['rule'] = implode( ",", $_obfuscate_6mlyHg�� );
        }
        if ( empty( $_obfuscate_6RYLWQ�� ) )
        {
            $CNOA_DB->db_delete( $this->t_set_step_permit, "WHERE flowId=".$_obfuscate_F4AbnVRh." AND stepId={$_obfuscate_0Ul8BBkt} AND operate={$_obfuscate_bBdOnB4sqA��}" );
        }
        else
        {
            $_obfuscate_6RYLWQ��['flowId'] = $_obfuscate_F4AbnVRh;
            $_obfuscate_6RYLWQ��['stepId'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_6RYLWQ��['operate'] = $_obfuscate_bBdOnB4sqA��;
            $CNOA_DB->db_replace( $_obfuscate_6RYLWQ��, $this->t_set_step_permit );
        }
    }

    private function _loadFlowDesignData( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_7qDAYo85aGA� = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $_obfuscate_EdcUyMWd6ZEv = $CNOA_DB->db_select( "*", $this->t_set_field, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' ORDER BY `id` ASC" );
        if ( !is_array( $_obfuscate_EdcUyMWd6ZEv ) )
        {
            $_obfuscate_EdcUyMWd6ZEv = array( );
        }
        $_obfuscate_D5cvgOQDiG = array( );
        $_obfuscate_HAEJuyDtQpZD9IA� = array( );
        foreach ( $_obfuscate_EdcUyMWd6ZEv as $_obfuscate_WgE� )
        {
            $_obfuscate_p5ZWxr4� = json_decode( str_replace( "'", "\"", $_obfuscate_WgE�['odata'] ), TRUE );
            $_obfuscate_juwe = array( );
            $_obfuscate_juwe['id'] = $_obfuscate_WgE�['id'];
            $_obfuscate_juwe['name'] = $_obfuscate_WgE�['name'];
            $_obfuscate_juwe['otype'] = $_obfuscate_WgE�['otype'];
            $_obfuscate_juwe['type'] = $_obfuscate_WgE�['type'];
            $_obfuscate_juwe['table'] = "";
            $_obfuscate_juwe['gname'] = "&nbsp;普通表单控件";
            $_obfuscate_juwe['tableid'] = 0;
            $_obfuscate_juwe['from'] = "normal";
            $_obfuscate_juwe['dataType'] = $_obfuscate_p5ZWxr4�['dataType'];
            if ( $_obfuscate_juwe['otype'] == "detailtable" )
            {
                $_obfuscate_7Hp0w_lfFt4� = $this->_loadDetailFieldsData( $_obfuscate_WgE�['id'] );
                foreach ( $_obfuscate_7Hp0w_lfFt4� as $_obfuscate_6A�� )
                {
                    $_obfuscate_SeV31Q��['id'] = "d_".$_obfuscate_6A��['id'];
                    $_obfuscate_SeV31Q��['tableid'] = $_obfuscate_juwe['id'];
                    $_obfuscate_SeV31Q��['table'] = $_obfuscate_juwe['name'];
                    $_obfuscate_SeV31Q��['gname'] = "&nbsp;明细表：".$_obfuscate_juwe['name'];
                    $_obfuscate_SeV31Q��['name'] = $_obfuscate_6A��['name'];
                    $_obfuscate_SeV31Q��['otype'] = $_obfuscate_6A��['type'];
                    $_obfuscate_SeV31Q��['type'] = "text";
                    $_obfuscate_SeV31Q��['dataType'] = $_obfuscate_6A��['dataType'];
                    $_obfuscate_HAEJuyDtQpZD9IA�[] = $_obfuscate_SeV31Q��;
                }
            }
            $_obfuscate_D5cvgOQDiG[] = $_obfuscate_juwe;
        }
        foreach ( $_obfuscate_HAEJuyDtQpZD9IA� as $_obfuscate_6A�� )
        {
            $_obfuscate_D5cvgOQDiG[] = $_obfuscate_6A��;
        }
        $_obfuscate_ktRUIU_2er7vxw�� = $this->_loadPermit( $_obfuscate_F4AbnVRh );
        $_obfuscate_5NhzjnJq_f8� = $CNOA_DB->db_select( "*", $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' ORDER BY `id` ASC" );
        if ( !is_array( $_obfuscate_5NhzjnJq_f8� ) )
        {
            $_obfuscate_5NhzjnJq_f8� = array( );
        }
        $_obfuscate_T1JGvNjMhVs� = $_obfuscate_mbrsRUqoKg�� = array( );
        foreach ( $_obfuscate_5NhzjnJq_f8� as $_obfuscate_6A�� )
        {
            $_obfuscate_MnVVbyZQFVw� = json_decode( $_obfuscate_6A��['nextStep'] );
            if ( !empty( $_obfuscate_MnVVbyZQFVw� ) )
            {
                $_obfuscate_mbrsRUqoKg��[$_obfuscate_6A��['stepId']] = $_obfuscate_MnVVbyZQFVw�;
            }
        }
        foreach ( $_obfuscate_5NhzjnJq_f8� as $_obfuscate_B0M� )
        {
            $_obfuscate_juwe = array( );
            $_obfuscate_juwe['stepId'] = $_obfuscate_B0M�['stepId'];
            $_obfuscate_juwe['stepName'] = $_obfuscate_B0M�['stepName'];
            $_obfuscate_juwe['allowReject'] = $_obfuscate_B0M�['allowReject'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowHuiqian'] = $_obfuscate_B0M�['allowHuiqian'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowFenfa'] = $_obfuscate_B0M�['allowFenfa'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowTuihui'] = $_obfuscate_B0M�['allowTuihui'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowPrint'] = $_obfuscate_B0M�['allowPrint'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowCallback'] = $_obfuscate_B0M�['allowCallback'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowCancel'] = $_obfuscate_B0M�['allowCancel'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowYijian'] = $_obfuscate_B0M�['allowYijian'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowAttachAdd'] = $_obfuscate_B0M�['allowAttachAdd'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowAttachView'] = $_obfuscate_B0M�['allowAttachView'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowAttachEdit'] = $_obfuscate_B0M�['allowAttachEdit'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowAttachDelete'] = $_obfuscate_B0M�['allowAttachDelete'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowAttachDown'] = $_obfuscate_B0M�['allowAttachDown'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowWordEdit'] = $_obfuscate_B0M�['allowWordEdit'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowAttachWordEdit'] = $_obfuscate_B0M�['allowAttachWordEdit'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowHqAttachAdd'] = $_obfuscate_B0M�['allowHqAttachAdd'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowHqAttachView'] = $_obfuscate_B0M�['allowHqAttachView'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowHqAttachEdit'] = $_obfuscate_B0M�['allowHqAttachEdit'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowHqAttachDelete'] = $_obfuscate_B0M�['allowHqAttachDelete'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowHqAttachDown'] = $_obfuscate_B0M�['allowHqAttachDown'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowSms'] = $_obfuscate_B0M�['allowSms'] == 1 ? "on" : "";
            $_obfuscate_juwe['doBtnText'] = $_obfuscate_B0M�['doBtnText'];
            $_obfuscate_juwe['stepTime'] = $_obfuscate_B0M�['stepTime'];
            $_obfuscate_juwe['urgeBefore'] = $_obfuscate_B0M�['urgeBefore'];
            $_obfuscate_juwe['urgeTarget'] = $_obfuscate_B0M�['urgeTarget'] == 0 ? "" : $_obfuscate_B0M�['urgeTarget'];
            $_obfuscate_juwe['fields'] = $this->_loadFieldsData( $_obfuscate_F4AbnVRh, $_obfuscate_B0M�['stepId'] );
            $_obfuscate_juwe['user'] = $this->_loadUserData( $_obfuscate_F4AbnVRh, $_obfuscate_B0M�['stepId'] );
            $_obfuscate_juwe['condition'] = $this->_loadConditionData( $_obfuscate_F4AbnVRh, $_obfuscate_B0M�['stepId'] );
            $_obfuscate_juwe['dealWay'] = $this->_loadDealWay( $_obfuscate_F4AbnVRh, $_obfuscate_B0M�['stepId'] );
            if ( !empty( $_obfuscate_B0M�['bingids'] ) )
            {
                foreach ( $_obfuscate_mbrsRUqoKg�� as $_obfuscate_5w�� => $_obfuscate_jVOC2dbrYw�� )
                {
                    if ( in_array( $_obfuscate_B0M�['stepId'], $_obfuscate_jVOC2dbrYw�� ) )
                    {
                        $_obfuscate_juwe['childPermit'] = $this->_loadChildPermit( $_obfuscate_F4AbnVRh, $_obfuscate_5w�� );
                    }
                }
            }
            $_obfuscate_juwe['bingnames'] = $_obfuscate_B0M�['bingnames'];
            $_obfuscate_juwe['bingids'] = $_obfuscate_B0M�['bingids'];
            $_obfuscate_juwe['faqiFlow'] = $_obfuscate_B0M�['faqiFlow'];
            $_obfuscate_juwe['endFlow'] = $_obfuscate_B0M�['endFlow'];
            $_obfuscate_juwe['sharefile'] = $_obfuscate_B0M�['sharefile'] == 1 ? "on" : "";
            $_obfuscate_juwe['child'] = $this->_loadChildData( $_obfuscate_F4AbnVRh, $_obfuscate_B0M�['stepId'] );
            $_obfuscate_juwe['huiqianPermit'] = $_obfuscate_ktRUIU_2er7vxw��[$_obfuscate_B0M�['stepId']]['huiqian'];
            $_obfuscate_juwe['fenfaPermit'] = $_obfuscate_ktRUIU_2er7vxw��[$_obfuscate_B0M�['stepId']]['fenfa'];
            $_obfuscate_T1JGvNjMhVs�[] = $_obfuscate_juwe;
        }
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_6RYLWQ��['flowXml'] = $_obfuscate_7qDAYo85aGA�['flowXml'];
        $_obfuscate_6RYLWQ��['fields'] = $_obfuscate_D5cvgOQDiG;
        $_obfuscate_6RYLWQ��['steps'] = $_obfuscate_T1JGvNjMhVs�;
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_6RYLWQ��;
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function _loadFieldsData( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_step_fields, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_O6QLLac� = array( 0 );
        $_obfuscate_QrenqRn2PzA = array( 0 );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['from'] == 1 )
            {
                $_obfuscate_QrenqRn2PzA[] = $_obfuscate_6A��['fieldId'];
            }
            else
            {
                $_obfuscate_O6QLLac�[] = $_obfuscate_6A��['fieldId'];
            }
        }
        $_obfuscate_Pm3ZMWpPkg�� = $CNOA_DB->db_select( array( "id", "name", "otype", "type" ), $this->t_set_field, "WHERE `id` IN (".implode( ",", $_obfuscate_O6QLLac� ).") " );
        if ( !is_array( $_obfuscate_Pm3ZMWpPkg�� ) )
        {
            $_obfuscate_Pm3ZMWpPkg�� = array( );
        }
        $_obfuscate_mLjk2t6lphU� = array( );
        foreach ( $_obfuscate_Pm3ZMWpPkg�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_mLjk2t6lphU�[$_obfuscate_6A��['id']] = $_obfuscate_6A��;
        }
        $_obfuscate_2Zj6 = $CNOA_DB->db_select( array( "id", "name", "type" ), $this->t_set_field_detail, "WHERE `id` IN (".implode( ",", $_obfuscate_QrenqRn2PzA ).") " );
        if ( !is_array( $_obfuscate_2Zj6 ) )
        {
            $_obfuscate_2Zj6 = array( );
        }
        $_obfuscate_rvwe5cLI9_YAQ�� = array( );
        foreach ( $_obfuscate_2Zj6 as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_rvwe5cLI9_YAQ��[$_obfuscate_6A��['id']] = $_obfuscate_6A��;
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_SeV31Q��['write'] = $_obfuscate_6A��['write'];
            $_obfuscate_SeV31Q��['must'] = $_obfuscate_6A��['must'];
            $_obfuscate_SeV31Q��['hide'] = $_obfuscate_6A��['hide'];
            $_obfuscate_SeV31Q��['show'] = $_obfuscate_6A��['show'];
            $_obfuscate_SeV31Q��['status'] = $_obfuscate_6A��['status'];
            if ( $_obfuscate_6A��['from'] == 1 )
            {
                $_obfuscate_SeV31Q��['id'] = "d_".$_obfuscate_6A��['fieldId'];
                $_obfuscate_SeV31Q��['name'] = $_obfuscate_rvwe5cLI9_YAQ��[$_obfuscate_6A��['fieldId']]['name'];
                $_obfuscate_SeV31Q��['otype'] = $_obfuscate_rvwe5cLI9_YAQ��[$_obfuscate_6A��['fieldId']]['type'];
                $_obfuscate_SeV31Q��['type'] = "text";
            }
            else
            {
                $_obfuscate_SeV31Q��['id'] = $_obfuscate_6A��['fieldId'];
                $_obfuscate_SeV31Q��['name'] = $_obfuscate_mLjk2t6lphU�[$_obfuscate_6A��['fieldId']]['name'];
                $_obfuscate_SeV31Q��['otype'] = $_obfuscate_mLjk2t6lphU�[$_obfuscate_6A��['fieldId']]['otype'];
                $_obfuscate_SeV31Q��['type'] = $_obfuscate_mLjk2t6lphU�[$_obfuscate_6A��['fieldId']]['type'];
            }
            $_obfuscate_6RYLWQ��[] = $_obfuscate_SeV31Q��;
        }
        foreach ( $_obfuscate_6RYLWQ�� as $_obfuscate_Vwty => $_obfuscate_VgKtFeg� )
        {
            if ( $_obfuscate_VgKtFeg�['otype'] == "calculate" )
            {
                $_obfuscate_6RYLWQ��[$_obfuscate_Vwty]['write'] = 0;
            }
        }
        return $_obfuscate_6RYLWQ��;
    }

    private function _loadChildPermit( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_step_fields, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_O6QLLac� = array( 0 );
        $_obfuscate_QrenqRn2PzA = array( 0 );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['from'] == 1 )
            {
                $_obfuscate_QrenqRn2PzA[] = $_obfuscate_6A��['fieldId'];
            }
            else
            {
                $_obfuscate_O6QLLac�[] = $_obfuscate_6A��['fieldId'];
            }
        }
        $_obfuscate_Pm3ZMWpPkg�� = $CNOA_DB->db_select( array( "id", "name", "otype", "type" ), $this->t_set_field, "WHERE `id` IN (".implode( ",", $_obfuscate_O6QLLac� ).") " );
        if ( !is_array( $_obfuscate_Pm3ZMWpPkg�� ) )
        {
            $_obfuscate_Pm3ZMWpPkg�� = array( );
        }
        $_obfuscate_mLjk2t6lphU� = array( );
        foreach ( $_obfuscate_Pm3ZMWpPkg�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_mLjk2t6lphU�[$_obfuscate_6A��['id']] = $_obfuscate_6A��;
        }
        $_obfuscate_2Zj6 = $CNOA_DB->db_select( array( "id", "name", "type" ), $this->t_set_field_detail, "WHERE `id` IN (".implode( ",", $_obfuscate_QrenqRn2PzA ).") " );
        if ( !is_array( $_obfuscate_2Zj6 ) )
        {
            $_obfuscate_2Zj6 = array( );
        }
        $_obfuscate_rvwe5cLI9_YAQ�� = array( );
        foreach ( $_obfuscate_2Zj6 as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_rvwe5cLI9_YAQ��[$_obfuscate_6A��['id']] = $_obfuscate_6A��;
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_SeV31Q��['status'] = $_obfuscate_6A��['status'];
            $_obfuscate_SeV31Q��['stepId'] = $_obfuscate_0Ul8BBkt;
            if ( $_obfuscate_6A��['from'] == 1 )
            {
                $_obfuscate_SeV31Q��['id'] = "d_".$_obfuscate_6A��['fieldId'];
                $_obfuscate_SeV31Q��['name'] = $_obfuscate_rvwe5cLI9_YAQ��[$_obfuscate_6A��['fieldId']]['name'];
                $_obfuscate_SeV31Q��['otype'] = $_obfuscate_rvwe5cLI9_YAQ��[$_obfuscate_6A��['fieldId']]['type'];
                $_obfuscate_SeV31Q��['type'] = "text";
            }
            else
            {
                $_obfuscate_SeV31Q��['id'] = $_obfuscate_6A��['fieldId'];
                $_obfuscate_SeV31Q��['name'] = $_obfuscate_mLjk2t6lphU�[$_obfuscate_6A��['fieldId']]['name'];
                $_obfuscate_SeV31Q��['otype'] = $_obfuscate_mLjk2t6lphU�[$_obfuscate_6A��['fieldId']]['otype'];
                $_obfuscate_SeV31Q��['type'] = $_obfuscate_mLjk2t6lphU�[$_obfuscate_6A��['fieldId']]['type'];
            }
            $_obfuscate_6RYLWQ��[] = $_obfuscate_SeV31Q��;
        }
        return $_obfuscate_6RYLWQ��;
    }

    private function _loadDetailFieldsData( $_obfuscate_0W8� )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "dataType", "fid", "id", "name", "type" ), $this->t_set_field_detail, "WHERE `fid` = '".$_obfuscate_0W8�."' ORDER BY `id` ASC " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
        }
        return $_obfuscate_mPAjEGLn;
    }

    private function _loadUserData( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_step_user, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_vwGQSA�� = array( );
        $_obfuscate_YsVdvv0c = array( );
        $_obfuscate_fdSO3eSyAQ�� = array( );
        $_obfuscate_6mlyHg�� = array( );
        $_obfuscate_m5leXC9_Zg�� = array( );
        $_obfuscate_ZxVMfNbI5ugv4Ks� = array( );
        $_obfuscate_flKx1g�� = array( );
        $_obfuscate_Lw9wXKzqBg�� = array( 0 );
        $_obfuscate_MtpzvDgUD7YblQ�� = array( 0 );
        $_obfuscate__ooZFvTbHA�� = array( 0 );
        $_obfuscate_HmJYx_HCew�� = array( 0 );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['type'] == "dept" )
            {
                $_obfuscate_Lw9wXKzqBg��[] = $_obfuscate_6A��['dept'];
            }
            else if ( $_obfuscate_6A��['type'] == "people" )
            {
                $_obfuscate__ooZFvTbHA��[] = $_obfuscate_6A��['people'];
            }
            else if ( $_obfuscate_6A��['type'] == "exclude" )
            {
                $_obfuscate_JF89pTCN4WiNCg��[] = $_obfuscate_6A��['exclude'];
            }
            else if ( $_obfuscate_6A��['type'] == "station" )
            {
                $_obfuscate_MtpzvDgUD7YblQ��[] = $_obfuscate_6A��['station'];
            }
            else if ( $_obfuscate_6A��['type'] == "rule" )
            {
                $_obfuscate_MtpzvDgUD7YblQ��[] = $_obfuscate_6A��['rule_s'];
            }
            else if ( $_obfuscate_6A��['type'] == "kong" )
            {
                $_obfuscate_HmJYx_HCew��[] = $_obfuscate_6A��['kong'];
            }
            else
            {
                $_obfuscate_Lw9wXKzqBg��[] = $_obfuscate_6A��['dept'];
                $_obfuscate_MtpzvDgUD7YblQ��[] = $_obfuscate_6A��['station'];
            }
        }
        $_obfuscate_dga5p5gjYJ23VQ�� = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate__ooZFvTbHA�� );
        $_obfuscate_dQ8cgLyveESU = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_JF89pTCN4WiNCg�� );
        $_obfuscate_uLf44wk1NRqS = app::loadapp( "main", "station" )->api_getNamesByIds( $_obfuscate_MtpzvDgUD7YblQ�� );
        $_obfuscate_2w�� = app::loadapp( "main", "struct" )->api_getNamesByIds( $_obfuscate_Lw9wXKzqBg�� );
        $_obfuscate__Ja_D7YH = $CNOA_DB->db_select( array( "id", "name" ), $this->t_set_field, "WHERE `id` IN (".implode( ",", $_obfuscate_HmJYx_HCew�� ).") " );
        if ( !is_array( $_obfuscate__Ja_D7YH ) )
        {
            $_obfuscate__Ja_D7YH = array( );
        }
        $_obfuscate_HmJYx_HCew�� = array( );
        foreach ( $_obfuscate__Ja_D7YH as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_HmJYx_HCew��[$_obfuscate_6A��['id']] = $_obfuscate_6A��['name'];
        }
        $_obfuscate_EzJXRY5VDKzFxg�� = array( "faqi" => "[发起人]", "zhuban" => "[主办人]", "faqiself" => "[发起人自己]", "beforepeop" => "[所有已办理人]", "myDept" => "[所属部门]", "upDept" => "[上级部门]", "myUpDept" => "[所属部门和上级部门]", "allDept" => "[所属部门及所有上级部门]" );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['type'] == "dept" )
            {
                $_obfuscate_XRvPgP5V0t4� = $_obfuscate_2w��[$_obfuscate_6A��['dept']];
                $_obfuscate_SeV31Q��['id'] = $_obfuscate_6A��['dept'];
                $_obfuscate_SeV31Q��['text'] = isset( $_obfuscate_XRvPgP5V0t4� ) ? $_obfuscate_XRvPgP5V0t4� : lang( "deptBeenDel" );
                $_obfuscate_vwGQSA��[] = $_obfuscate_SeV31Q��;
            }
            else if ( $_obfuscate_6A��['type'] == "people" )
            {
                $_obfuscate__Wi6396IheA� = $_obfuscate_dga5p5gjYJ23VQ��[$_obfuscate_6A��['people']]['truename'];
                $_obfuscate_SeV31Q��['id'] = $_obfuscate_6A��['people'];
                $_obfuscate_SeV31Q��['text'] = isset( $_obfuscate__Wi6396IheA� ) ? $_obfuscate__Wi6396IheA� : lang( "userNotExists" );
                $_obfuscate_YsVdvv0c[] = $_obfuscate_SeV31Q��;
            }
            else if ( $_obfuscate_6A��['type'] == "exclude" )
            {
                $_obfuscate_RoxLIlJRRAIfNYKoClY = $_obfuscate_dQ8cgLyveESU[$_obfuscate_6A��['exclude']]['truename'];
                $_obfuscate_SeV31Q��['id'] = $_obfuscate_6A��['exclude'];
                $_obfuscate_SeV31Q��['text'] = isset( $_obfuscate_RoxLIlJRRAIfNYKoClY ) ? $_obfuscate_RoxLIlJRRAIfNYKoClY : lang( "userNotExists" );
                $_obfuscate_fdSO3eSyAQ��[] = $_obfuscate_SeV31Q��;
            }
            else if ( $_obfuscate_6A��['type'] == "rule" )
            {
                $_obfuscate_SeV31Q��['dept'] = $_obfuscate_6A��['rule_d'];
                $_obfuscate_SeV31Q��['id'] = $_obfuscate_6A��['rule_s'];
                $_obfuscate_SeV31Q��['people'] = $_obfuscate_6A��['rule_p'];
                if ( $_obfuscate_6A��['rule_p'] == "faqiself" )
                {
                    $_obfuscate_SeV31Q��['text'] = $_obfuscate_EzJXRY5VDKzFxg��[$_obfuscate_6A��['rule_p']];
                }
                else if ( $_obfuscate_6A��['rule_p'] == "beforepeop" )
                {
                    $_obfuscate_SeV31Q��['text'] = $_obfuscate_EzJXRY5VDKzFxg��[$_obfuscate_6A��['rule_p']];
                }
                else
                {
                    $_obfuscate_SeV31Q��['text'] = $_obfuscate_EzJXRY5VDKzFxg��[$_obfuscate_6A��['rule_p']]." ".$_obfuscate_EzJXRY5VDKzFxg��[$_obfuscate_6A��['rule_d']]." [(".lang( "station" ).")".$_obfuscate_uLf44wk1NRqS[$_obfuscate_6A��['rule_s']]."]";
                }
                $_obfuscate_6mlyHg��[] = $_obfuscate_SeV31Q��;
            }
            else if ( $_obfuscate_6A��['type'] == "station" )
            {
                $_obfuscate_SeV31Q��['id'] = $_obfuscate_6A��['station'];
                $_obfuscate_SeV31Q��['text'] = $_obfuscate_uLf44wk1NRqS[$_obfuscate_6A��['station']];
                $_obfuscate_m5leXC9_Zg��[] = $_obfuscate_SeV31Q��;
            }
            else if ( $_obfuscate_6A��['type'] == "deptstation" )
            {
                $_obfuscate_XRvPgP5V0t4� = $_obfuscate_2w��[$_obfuscate_6A��['dept']];
                $_obfuscate_Ox8sY3sXWruQLLY� = $_obfuscate_uLf44wk1NRqS[$_obfuscate_6A��['station']];
                if ( $_obfuscate_XRvPgP5V0t4� && $_obfuscate_Ox8sY3sXWruQLLY� )
                {
                    $_obfuscate_SeV31Q��['text'] = "[".$_obfuscate_XRvPgP5V0t4�."] [(".lang( "station" ).")".$_obfuscate_Ox8sY3sXWruQLLY�."]";
                }
                else
                {
                    $_obfuscate_SeV31Q��['text'] = lang( "ruleNotLegitimate" );
                }
                $_obfuscate_SeV31Q��['id'] = $_obfuscate_6A��['dept'].",".$_obfuscate_6A��['station'];
                $_obfuscate_ZxVMfNbI5ugv4Ks�[] = $_obfuscate_SeV31Q��;
            }
            else if ( $_obfuscate_6A��['type'] == "kong" )
            {
                $_obfuscate_SeV31Q��['id'] = $_obfuscate_6A��['kong'];
                $_obfuscate_SeV31Q��['text'] = $_obfuscate_HmJYx_HCew��[$_obfuscate_6A��['kong']];
                $_obfuscate_flKx1g��[] = $_obfuscate_SeV31Q��;
            }
        }
        $_obfuscate_6RYLWQ��['dept'] = $_obfuscate_vwGQSA��;
        $_obfuscate_6RYLWQ��['kong'] = array( );
        $_obfuscate_6RYLWQ��['people'] = $_obfuscate_YsVdvv0c;
        $_obfuscate_6RYLWQ��['exclude'] = $_obfuscate_fdSO3eSyAQ��;
        $_obfuscate_6RYLWQ��['rule'] = $_obfuscate_6mlyHg��;
        $_obfuscate_6RYLWQ��['station'] = $_obfuscate_m5leXC9_Zg��;
        $_obfuscate_6RYLWQ��['deptStation'] = $_obfuscate_ZxVMfNbI5ugv4Ks�;
        $_obfuscate_6RYLWQ��['kong'] = $_obfuscate_flKx1g��;
        return $_obfuscate_6RYLWQ��;
    }

    private function _loadDealWay( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $_obfuscate_fMfssw�� = $CNOA_DB->db_select( array( "deal" ), $this->t_use_deal_way, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND `stepId`={$_obfuscate_0Ul8BBkt}" );
        if ( !is_array( $_obfuscate_fMfssw�� ) )
        {
            $_obfuscate_fMfssw�� = array( );
        }
        $_obfuscate_SeV31Q�� = $_obfuscate_6RYLWQ�� = array( );
        foreach ( $_obfuscate_fMfssw�� as $_obfuscate_Vwty => $_obfuscate_VgKtFeg� )
        {
            $_obfuscate_SeV31Q��[] = $_obfuscate_VgKtFeg�;
        }
        $_obfuscate_6RYLWQ��['dealWay'] = $_obfuscate_SeV31Q��;
        return $_obfuscate_6RYLWQ��;
    }

    private function _loadConditionData( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_step_condition, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' ORDER BY `id` ASC " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_MnVVbyZQFVw� = array( );
        $_obfuscate_wO3K = array( );
        $_obfuscate_eBU_Sjc� = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_MnVVbyZQFVw�[$_obfuscate_6A��['nextStepId']][] = $_obfuscate_6A��;
            if ( $_obfuscate_6A��['head'] == 1 || $_obfuscate_6A��['head'] == 0 && $_obfuscate_6A��['pid'] == 0 )
            {
                $_obfuscate_wO3K[$_obfuscate_6A��['nextStepId']][] = $_obfuscate_6A��['id'];
            }
            else
            {
                $_obfuscate_eBU_Sjc�[$_obfuscate_6A��['pid']][] = $_obfuscate_6A��;
            }
            $_obfuscate_Hb1v[$_obfuscate_6A��['id']] = $_obfuscate_6A��;
        }
        $_obfuscate_j9sJes� = array( );
        foreach ( $_obfuscate_wO3K as $_obfuscate_5w�� => $_obfuscate_YupB5g�� )
        {
            $_obfuscate_ = array( );
            foreach ( $_obfuscate_YupB5g�� as $_obfuscate_6A�� )
            {
                $_obfuscate_SeV31Q��['text'] = $_obfuscate_Hb1v[$_obfuscate_6A��]['text'];
                $_obfuscate_SeV31Q��['orAnd'] = $_obfuscate_Hb1v[$_obfuscate_6A��]['orAnd'];
                $_obfuscate_Wlf9Dg��['name'] = $_obfuscate_Hb1v[$_obfuscate_6A��]['name'];
                $_obfuscate_Wlf9Dg��['rule'] = $_obfuscate_Hb1v[$_obfuscate_6A��]['rule'];
                $_obfuscate_Wlf9Dg��['ovalue'] = $_obfuscate_Hb1v[$_obfuscate_6A��]['ovalue'];
                $_obfuscate_Wlf9Dg��['orAnd'] = $_obfuscate_Hb1v[$_obfuscate_6A��]['orAnd'];
                if ( in_array( $_obfuscate_Hb1v[$_obfuscate_6A��]['fieldType'], array( "n_n", "n_s", "n_d", "d_n", "d_s", "d_d" ) ) )
                {
                    $_obfuscate_Wlf9Dg��['name'] = "s|".$_obfuscate_Hb1v[$_obfuscate_6A��]['fieldType'];
                }
                $_obfuscate_SeV31Q��['items'][] = $_obfuscate_Wlf9Dg��;
                if ( 0 < count( $_obfuscate_eBU_Sjc�[$_obfuscate_6A��] ) )
                {
                    $_obfuscate_SeV31Q��['left'] = 1;
                    $_obfuscate_SeV31Q��['right'] = 1;
                    foreach ( $_obfuscate_eBU_Sjc�[$_obfuscate_6A��] as $_obfuscate_bRQ� )
                    {
                        $_obfuscate_Wlf9Dg��['name'] = $_obfuscate_bRQ�['name'];
                        $_obfuscate_Wlf9Dg��['rule'] = $_obfuscate_bRQ�['rule'];
                        $_obfuscate_Wlf9Dg��['ovalue'] = $_obfuscate_bRQ�['ovalue'];
                        $_obfuscate_Wlf9Dg��['orAnd'] = $_obfuscate_bRQ�['orAnd'];
                        if ( in_array( $_obfuscate_bRQ�['fieldType'], array( "n_n", "n_s", "n_d", "d_n", "d_s", "d_d" ) ) )
                        {
                            $_obfuscate_Wlf9Dg��['name'] = "s|".$_obfuscate_bRQ�['fieldType'];
                        }
                        $_obfuscate_SeV31Q��['items'][] = $_obfuscate_Wlf9Dg��;
                    }
                }
                else
                {
                    $_obfuscate_SeV31Q��['left'] = 0;
                    $_obfuscate_SeV31Q��['right'] = 0;
                }
                $_obfuscate_[] = $_obfuscate_SeV31Q��;
                unset( $_obfuscate_SeV31Q�� );
            }
            $_obfuscate_6RYLWQ��['id'] = $_obfuscate_5w��;
            $_obfuscate_6RYLWQ��['items'] = $_obfuscate_;
            $_obfuscate_j9sJes�[] = $_obfuscate_6RYLWQ��;
            unset( $_obfuscate_AGk1QY4� );
        }
        return $_obfuscate_j9sJes�;
    }

    private function _loadChildData( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_step_child_kongjian, "WHERE `flowId` = ".$_obfuscate_F4AbnVRh." AND `cStepId` = {$_obfuscate_0Ul8BBkt} " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( ereg( "T_", $_obfuscate_6A��['childKongjian'] ) )
            {
                $_obfuscate_kM1PB1K[] = str_replace( "T_", "", $_obfuscate_6A��['childKongjian'] );
            }
            else if ( ereg( "D_", $_obfuscate_6A��['childKongjian'] ) )
            {
                $_obfuscate_8XjS1n72[] = str_replace( "D_", "", $_obfuscate_6A��['childKongjian'] );
            }
            if ( ereg( "T_", $_obfuscate_6A��['parentKongjian'] ) )
            {
                $_obfuscate_kM1PB1K[] = str_replace( "T_", "", $_obfuscate_6A��['parentKongjian'] );
            }
            else if ( ereg( "D_", $_obfuscate_6A��['parentKongjian'] ) )
            {
                $_obfuscate_8XjS1n72[] = str_replace( "D_", "", $_obfuscate_6A��['parentKongjian'] );
            }
            $_obfuscate_l2CIvUX0Kvp4[] = $_obfuscate_6A��['bangdingFlow'];
        }
        if ( !empty( $_obfuscate_l2CIvUX0Kvp4 ) )
        {
            $_obfuscate_SIUSR4F6 = $CNOA_DB->db_select( "*", $this->t_set_flow, "WHERE `flowId` IN (".implode( ",", $_obfuscate_l2CIvUX0Kvp4 ).") " );
            if ( !is_array( $_obfuscate_SIUSR4F6 ) )
            {
                $_obfuscate_SIUSR4F6 = array( );
            }
            foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_WMVwRv5Dg��[$_obfuscate_6A��['flowId']] = $_obfuscate_6A��['name'];
            }
        }
        if ( !empty( $_obfuscate_kM1PB1K ) )
        {
            $_obfuscate_XKxKFeaAMUQ� = $CNOA_DB->db_select( "*", $this->t_set_field, "WHERE `id` IN (".implode( ",", $_obfuscate_kM1PB1K ).") " );
            if ( !is_array( $_obfuscate_XKxKFeaAMUQ� ) )
            {
                $_obfuscate_XKxKFeaAMUQ� = array( );
            }
            foreach ( $_obfuscate_XKxKFeaAMUQ� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_Eedcyws5SG0U["T_".$_obfuscate_6A��['id']] = $_obfuscate_6A��['name'];
            }
        }
        if ( !empty( $_obfuscate_8XjS1n72 ) )
        {
            $_obfuscate_7Hp0w_lfFt4� = $CNOA_DB->db_select( "*", $this->t_set_field_detail, "WHERE `id` IN (".implode( ",", $_obfuscate_8XjS1n72 ).") " );
            if ( !is_array( $_obfuscate_7Hp0w_lfFt4� ) )
            {
                $_obfuscate_7Hp0w_lfFt4� = array( );
            }
            foreach ( $_obfuscate_7Hp0w_lfFt4� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_dGoPOiQ2Iw5a["D_".$_obfuscate_6A��['id']] = $_obfuscate_6A��['name'];
            }
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['bangdingFlowName'] = $_obfuscate_WMVwRv5Dg��[$_obfuscate_6A��['bangdingFlow']];
            if ( ereg( "T_", $_obfuscate_6A��['parentKongjian'] ) )
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['parentKongjianName'] = $_obfuscate_Eedcyws5SG0U[$_obfuscate_6A��['parentKongjian']];
            }
            else if ( ereg( "D_", $_obfuscate_6A��['parentKongjian'] ) )
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['parentKongjianName'] = $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_6A��['parentKongjian']];
            }
            if ( ereg( "T_", $_obfuscate_6A��['childKongjian'] ) )
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['childKongjianName'] = $_obfuscate_Eedcyws5SG0U[$_obfuscate_6A��['childKongjian']];
            }
            else if ( ereg( "D_", $_obfuscate_6A��['childKongjian'] ) )
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['childKongjianName'] = $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_6A��['childKongjian']];
            }
        }
        return $_obfuscate_mPAjEGLn;
    }

    private function _loadPermit( $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        $_obfuscate_xs33Yt_k = $CNOA_DB->db_select( "*", $this->t_set_step_permit, "WHERE `flowId`=".$_obfuscate_F4AbnVRh );
        if ( !is_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_xs33Yt_k = array( );
        }
        $_obfuscate_7Ri3 = $_obfuscate_2sZ8Toxw = $_obfuscate_y6jH = array( );
        foreach ( $_obfuscate_xs33Yt_k as $_obfuscate_6A�� )
        {
            if ( !empty( $_obfuscate_6A��['user'] ) )
            {
                $_obfuscate_7Ri3[] = $_obfuscate_6A��['user'];
            }
            if ( !empty( $_obfuscate_6A��['dept'] ) )
            {
                $_obfuscate_2sZ8Toxw[] = $_obfuscate_6A��['dept'];
            }
            if ( !empty( $_obfuscate_6A��['rule'] ) )
            {
                $_obfuscate_6mlyHg�� = explode( ",", $_obfuscate_6A��['rule'] );
                foreach ( $_obfuscate_6mlyHg�� as $_obfuscate_OQ�� )
                {
                    list( ,  ) = explode( "|", $_obfuscate_OQ�� );
                    $_obfuscate_y6jH[] = $Var_384;
                }
            }
        }
        $_obfuscate_rNTRvK6XhPsP = $_obfuscate_mMnyN6u83w�� = $_obfuscate_wtaEWTJi_Q�� = array( );
        $_obfuscate_9Weh8jtBTtqrLw�� = $CNOA_DB->db_select( "*", $this->t_set_autoFenfa, "WHERE `flowId`=".$_obfuscate_F4AbnVRh );
        if ( !is_array( $_obfuscate_9Weh8jtBTtqrLw�� ) )
        {
            $_obfuscate_9Weh8jtBTtqrLw�� = array( );
        }
        foreach ( $_obfuscate_9Weh8jtBTtqrLw�� as $_obfuscate_VgKtFeg� )
        {
            $_obfuscate_mMnyN6u83w��[$_obfuscate_VgKtFeg�['stepId']] = array_unique( explode( ",", $_obfuscate_VgKtFeg�['uids'] ) );
            $_obfuscate_wtaEWTJi_Q��[$_obfuscate_VgKtFeg�['stepId']] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( explode( ",", $_obfuscate_VgKtFeg�['uids'] ) );
        }
        foreach ( $_obfuscate_mMnyN6u83w�� as $_obfuscate_Vwty => $_obfuscate_VgKtFeg� )
        {
            foreach ( $_obfuscate_VgKtFeg� as $_obfuscate_LQ8UKg�� )
            {
                $_obfuscate_rNTRvK6XhPsP[$_obfuscate_Vwty]['autoFenfa'][] = array(
                    $_obfuscate_LQ8UKg��,
                    $_obfuscate_wtaEWTJi_Q��[$_obfuscate_Vwty][$_obfuscate_LQ8UKg��]
                );
            }
        }
        $_obfuscate__Wi6396IheA� = $_obfuscate_XRvPgP5V0t4� = $_obfuscate_m5leXC9_Zg�� = array( );
        if ( 0 < count( $_obfuscate_7Ri3 ) )
        {
            $_obfuscate_7Ri3 = array_unique( explode( ",", implode( ",", $_obfuscate_7Ri3 ) ) );
            $_obfuscate__Wi6396IheA� = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_7Ri3 );
        }
        if ( 0 < count( $_obfuscate_2sZ8Toxw ) )
        {
            $_obfuscate_2sZ8Toxw = array_unique( explode( ",", implode( ",", $_obfuscate_2sZ8Toxw ) ) );
            $_obfuscate_XRvPgP5V0t4� = app::loadapp( "main", "struct" )->api_getNamesByIds( $_obfuscate_2sZ8Toxw );
        }
        if ( 0 < count( $_obfuscate_y6jH ) )
        {
            $_obfuscate_y6jH = array_unique( $_obfuscate_y6jH );
            $_obfuscate_m5leXC9_Zg�� = app::loadapp( "main", "station" )->api_getNamesByIds( $_obfuscate_y6jH );
        }
        $_obfuscate_dhMyCb2idSLFhA�� = array( "faqi" => "发起人", "zhuban" => "主办人" );
        $_obfuscate_ZoJ6n0QmFoI� = array( "myDept" => "所属部门", "upDept" => "上级部门", "myUpDept" => "所属部门和上级部门", "allDept" => "所属部门及所有上级部门" );
        $_obfuscate_ktRUIU_2er7vxw�� = array( );
        foreach ( $_obfuscate_xs33Yt_k as $_obfuscate_6A�� )
        {
            $_obfuscate_eVTMIa1A = $_obfuscate_7Ri3 = $_obfuscate_2sZ8Toxw = $_obfuscate_6mlyHg�� = array( );
            if ( !empty( $_obfuscate_6A��['user'] ) )
            {
                $_obfuscate_7Ri3 = explode( ",", $_obfuscate_6A��['user'] );
            }
            if ( !empty( $_obfuscate_6A��['dept'] ) )
            {
                $_obfuscate_2sZ8Toxw = explode( ",", $_obfuscate_6A��['dept'] );
            }
            if ( !empty( $_obfuscate_6A��['rule'] ) )
            {
                $_obfuscate_6mlyHg�� = explode( ",", $_obfuscate_6A��['rule'] );
            }
            foreach ( $_obfuscate_7Ri3 as $_obfuscate_0W8� )
            {
                $_obfuscate_eVTMIa1A['user'][] = array(
                    $_obfuscate_0W8�,
                    $_obfuscate__Wi6396IheA�[$_obfuscate_0W8�]
                );
            }
            foreach ( $_obfuscate_2sZ8Toxw as $_obfuscate_0W8� )
            {
                $_obfuscate_eVTMIa1A['dept'][] = array(
                    $_obfuscate_0W8�,
                    $_obfuscate_XRvPgP5V0t4�[$_obfuscate_0W8�]
                );
            }
            foreach ( $_obfuscate_6mlyHg�� as $_obfuscate_TAxu )
            {
                list( $_obfuscate_8w��, $_obfuscate_5g��, $p ) = explode( "|", $_obfuscate_TAxu );
                $_obfuscate_eVTMIa1A['rule'][] = array(
                    $_obfuscate_TAxu,
                    "[".$_obfuscate_dhMyCb2idSLFhA��[$_obfuscate_8w��]."][{$_obfuscate_ZoJ6n0QmFoI�[$_obfuscate_5g��]}][{$_obfuscate_m5leXC9_Zg��[$p]}]"
                );
            }
            switch ( $_obfuscate_6A��['operate'] )
            {
            case self::$OPERATE_TYPE_HUIQIAN :
                $_obfuscate_ktRUIU_2er7vxw��[$_obfuscate_6A��['stepId']]['huiqian'] = $_obfuscate_eVTMIa1A;
                break;
            case self::$OPERATE_TYPE_FENFA :
                $_obfuscate_ktRUIU_2er7vxw��[$_obfuscate_6A��['stepId']]['fenfa'] = $_obfuscate_eVTMIa1A;
            }
        }
        foreach ( $_obfuscate_rNTRvK6XhPsP as $_obfuscate_Vwty => $_obfuscate_VgKtFeg� )
        {
            $_obfuscate_ktRUIU_2er7vxw��[$_obfuscate_Vwty]['fenfa']['autoFenfa'] = $_obfuscate_rNTRvK6XhPsP[$_obfuscate_Vwty]['autoFenfa'];
        }
        return $_obfuscate_ktRUIU_2er7vxw��;
    }

    private function _getJsonData( $_obfuscate_lWk5hHye = FALSE )
    {
        global $CNOA_DB;
        $_obfuscate_Bk2lGlk� = "WHERE 1 ";
        $_obfuscate_mV9HBLY� = getpar( $_POST, "start", 0 );
        $_obfuscate_v1GprsIz = getpar( $_POST, "sortId", 0 );
        $_obfuscate_KiCCqRQ� = getpar( $_POST, "sname", "" );
        if ( !empty( $_obfuscate_v1GprsIz ) )
        {
            $_obfuscate_Bk2lGlk� = "WHERE `sortId` = '".$_obfuscate_v1GprsIz."' ";
        }
        if ( !empty( $_obfuscate_KiCCqRQ� ) )
        {
            $_obfuscate_Bk2lGlk� .= "AND `name` LIKE '%".$_obfuscate_KiCCqRQ�."%' ";
        }
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "flowId", "name", "sortId", "status", "tplSort", "flowType", "pageset" ), $this->t_set_flow, $_obfuscate_Bk2lGlk�.( "ORDER BY `order`,`flowId` DESC LIMIT ".$_obfuscate_mV9HBLY�.",{$this->rows}" ) );
        if ( $_obfuscate_lWk5hHye )
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "flowId", "name", "sortId", "status", "tplSort", "flowType", "pageset" ), $this->t_set_flow, $_obfuscate_Bk2lGlk�."ORDER BY `order`,`flowId` DESC" );
        }
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
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['sort'] = $_obfuscate_RopcQP_w[$_obfuscate_6A��['sortId']]['name'];
        }
        if ( $_obfuscate_lWk5hHye )
        {
            return $_obfuscate_mPAjEGLn;
        }
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_mPAjEGLn;
        $_obfuscate_NlQ�->total = $CNOA_DB->db_getCount( $this->t_set_flow, $_obfuscate_Bk2lGlk� );
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    public function api_getJsonData( )
    {
        $this->_getJsonData( );
    }

    private function _getSortStore( )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = app::loadapp( "wf", "flowSetSort" )->api_getSortDB( "all" );
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function _submit( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_LeS8hw�� = getpar( $_POST, "type", 0 );
        $_obfuscate_v1GprsIz = getpar( $_POST, "sortId", 0 );
        $_obfuscate_6RYLWQ��['name'] = getpar( $_POST, "name", "" );
        $_obfuscate_6RYLWQ��['nameRule'] = getpar( $_POST, "nameRule", "" );
        $_obfuscate_6RYLWQ��['sortId'] = $_obfuscate_v1GprsIz;
        $_obfuscate_6RYLWQ��['about'] = getpar( $_POST, "about", "" );
        $_obfuscate_6RYLWQ��['tplSort'] = getpar( $_POST, "tplSort", "" );
        $_obfuscate_6RYLWQ��['flowType'] = getpar( $_POST, "flowType", "" );
        $_obfuscate_6RYLWQ��['nameRuleAllowEdit'] = getpar( $_POST, "nameRuleAllowEdit", 0 );
        $_obfuscate_6RYLWQ��['nameDisallowBlank'] = getpar( $_POST, "nameDisallowBlank", 1 );
        $_obfuscate_6RYLWQ��['noticeCallback'] = getpar( $_POST, "noticeCallback", 1 );
        $_obfuscate_6RYLWQ��['noticeCancel'] = getpar( $_POST, "noticeCancel", 1 );
        $_obfuscate_6RYLWQ��['noticeAtGoBack'] = getpar( $_POST, "noticeAtGoBack", 1 );
        $_obfuscate_6RYLWQ��['noticeAtReject'] = getpar( $_POST, "noticeAtReject", 1 );
        $_obfuscate_6RYLWQ��['noticeAtInterrupt'] = getpar( $_POST, "noticeAtInterrupt", 1 );
        $_obfuscate_6RYLWQ��['noticeAtFinish'] = getpar( $_POST, "noticeAtFinish", 1 );
        if ( empty( $_obfuscate_F4AbnVRh ) )
        {
            $_obfuscate_6RYLWQ��['uid'] = $CNOA_SESSION->get( "UID" );
            $_obfuscate_6RYLWQ��['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->t_set_flow, "WHERE `name` = '".$_obfuscate_6RYLWQ��['name']."' " );
            if ( !empty( $_obfuscate_Ybai ) )
            {
                msg::callback( FALSE, lang( "flowNameExist" ) );
            }
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_set_flow );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3662, $_obfuscate_6RYLWQ��['name'], lang( "flow" ) );
        }
        else
        {
            $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->t_set_flow, "WHERE `name` = '".$_obfuscate_6RYLWQ��['name']."' AND `flowId` != '{$_obfuscate_F4AbnVRh}' " );
            $_obfuscate_Z4PHlvE� = $CNOA_DB->db_getcount( $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
            if ( !empty( $_obfuscate_Ybai ) )
            {
                msg::callback( FALSE, lang( "flowNameExist" ) );
            }
            $CNOA_DB->db_update( $_obfuscate_6RYLWQ��, $this->t_set_flow, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."'" );
            if ( !empty( $_obfuscate_Z4PHlvE� ) )
            {
                $CNOA_DB->db_update( "`sortId`=".$_obfuscate_v1GprsIz, $this->t_use_flow, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."'" );
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 3662, $_obfuscate_6RYLWQ��['name'], lang( "flow" ) );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadFormData( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_6RYLWQ�� = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' " );
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_6RYLWQ��;
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function _changeStatus( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_LeS8hw�� = getpar( $_POST, "type", "" );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_POST, "flowType", 0 );
        if ( $_obfuscate_XkuTFqZ6Tmk� == 0 )
        {
            if ( $_obfuscate_LeS8hw�� == "stop" )
            {
                $_obfuscate_6RYLWQ��['status'] = 0;
            }
            else if ( $_obfuscate_LeS8hw�� == "use" )
            {
                $_obfuscate_euP1Hw5BxD3jMYDR4Q�� = $CNOA_DB->db_getcount( $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
                if ( $_obfuscate_euP1Hw5BxD3jMYDR4Q�� < 2 )
                {
                    msg::callback( FALSE, lang( "noStepDesignStep" ) );
                }
                $_obfuscate_6RYLWQ��['status'] = 1;
            }
        }
        else if ( $_obfuscate_LeS8hw�� == "stop" )
        {
            $_obfuscate_6RYLWQ��['status'] = 0;
        }
        else
        {
            $_obfuscate_6RYLWQ��['status'] = 1;
        }
        $CNOA_DB->db_update( $_obfuscate_6RYLWQ��, $this->t_set_flow, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' " );
        $CNOA_DB->db_update( array(
            "status" => $_obfuscate_6RYLWQ��['status']
        ), $this->t_u_wffav, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' " );
        $_obfuscate_Thg� = $CNOA_DB->db_getone( array( "name", "status" ), $this->t_set_flow, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' " );
        if ( $_obfuscate_Thg�['status'] == 1 )
        {
            app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3662, lang( "enableFlow" ), $_obfuscate_sx8�['status'] );
        }
        else
        {
            app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3662, lang( "disableFlow" ), $_obfuscate_sx8�['status'] );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadFlowTotal( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_fKn99vuO9tU� = $CNOA_DB->db_getcount( $this->t_use_flow, "WHERE `status`='2' AND `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $_obfuscate_c426Ts9OtRNmjK0� = $CNOA_DB->db_getcount( $this->t_use_flow, "WHERE `status`='1' AND `flowId`='".$_obfuscate_F4AbnVRh."'" );
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->doing = $_obfuscate_c426Ts9OtRNmjK0�;
        $_obfuscate_NlQ�->done = $_obfuscate_fKn99vuO9tU�;
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function _ms_loadTemplateFile( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = intval( getpar( $_GET, "flowId", 0 ) );
        $_obfuscate_pEvU7Kz2Yw�� = intval( getpar( $_GET, "tplSort", 0 ) );
        $_obfuscate_o6LA2yPirJIreFA� = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/doc.history.0.php" );
        $_obfuscate_U9NJHZRq6Jr7T_A� = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/xls.history.0.php" );
        if ( $_obfuscate_pEvU7Kz2Yw�� == 1 || $_obfuscate_pEvU7Kz2Yw�� == 3 )
        {
            if ( file_exists( $_obfuscate_o6LA2yPirJIreFA� ) )
            {
                $_obfuscate_6hS1Rw�� = @file_get_contents( $_obfuscate_o6LA2yPirJIreFA� );
            }
            else
            {
                $_obfuscate_MI4_ZH9BUw�� = CNOA_PATH_FILE.( "/common/wf/set/{".$_obfuscate_F4AbnVRh."}/{$_obfuscate_F4AbnVRh}.php" );
                if ( file_exists( $_obfuscate_MI4_ZH9BUw�� ) )
                {
                    $_obfuscate_6hS1Rw�� = @file_get_contents( $_obfuscate_MI4_ZH9BUw�� );
                }
                else
                {
                    mkdirs( dirname( $_obfuscate_o6LA2yPirJIreFA� ) );
                }
            }
        }
        else if ( file_exists( $_obfuscate_U9NJHZRq6Jr7T_A� ) )
        {
            $_obfuscate_6hS1Rw�� = @file_get_contents( $_obfuscate_U9NJHZRq6Jr7T_A� );
        }
        else
        {
            $_obfuscate_MI4_ZH9BUw�� = CNOA_PATH_FILE.( "/common/wf/set/{".$_obfuscate_F4AbnVRh."}/{$_obfuscate_F4AbnVRh}.php" );
            if ( file_exists( $_obfuscate_MI4_ZH9BUw�� ) )
            {
                $_obfuscate_6hS1Rw�� = @file_get_contents( $_obfuscate_MI4_ZH9BUw�� );
            }
            else
            {
                mkdirs( dirname( $_obfuscate_U9NJHZRq6Jr7T_A� ) );
            }
        }
        echo $_obfuscate_6hS1Rw��;
        exit( );
    }

    private function _ms_submitMsOfficeData( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = intval( getpar( $_POST, "flowId", 0 ) );
        $_obfuscate_LeS8hw�� = getpar( $_GET, "type", 0 );
        $_obfuscate_zfubNC9lKJs� = strtolower( strrchr( $_FILES['msOffice']['name'], "." ) );
        if ( !in_array( strtolower( $_obfuscate_zfubNC9lKJs� ), array( ".doc", ".docx", ".xls", ".xlsx" ) ) )
        {
            echo "0";
            exit( );
        }
        if ( $_obfuscate_LeS8hw�� == "1" || $_obfuscate_LeS8hw�� == "3" )
        {
            $_obfuscate_7tmDAr7acvgDxw�� = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/doc.history.0.php" );
            mkdirs( dirname( $_obfuscate_7tmDAr7acvgDxw�� ) );
            if ( !cnoa_move_uploaded_file( $_FILES['msOffice']['tmp_name'], $_obfuscate_7tmDAr7acvgDxw�� ) )
            {
                echo "0";
                exit( );
            }
        }
        else
        {
            $_obfuscate_7tmDAr7acvgDxw�� = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/xls.history.0.php" );
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

    private function __updateStepsInfo( $_obfuscate_dw4x, $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        $_obfuscate_sc7AoZlouuA� = xml2array( stripslashes( $_obfuscate_dw4x ), 1, "mxGraphModel" );
        $_obfuscate_t3xmZlf1zM0� = $_obfuscate_sc7AoZlouuA�['mxGraphModel']['root']['mxCell'];
        if ( !is_array( $_obfuscate_t3xmZlf1zM0� ) )
        {
            $_obfuscate_t3xmZlf1zM0� = array( );
        }
        $_obfuscate_j9eamhY� = array( );
        $_obfuscate_7B4LUz4� = array( );
        $_obfuscate_S0PSA37yAw�� = array( );
        foreach ( $_obfuscate_t3xmZlf1zM0� as $_obfuscate_6A�� )
        {
            $_obfuscate_snM� = $_obfuscate_6A��['attr'];
            if ( $_obfuscate_snM�['edge'] == 1 )
            {
                $_obfuscate_7B4LUz4�[] = array(
                    "source" => $_obfuscate_snM�['source'],
                    "target" => $_obfuscate_snM�['target'],
                    "mark" => $_obfuscate_snM�['mark']
                );
            }
            if ( in_array( $_obfuscate_snM�['nodeType'], array( "node", "startNode", "endNode", "cNode", "bNode", "childNode", "bcNode" ) ) )
            {
                switch ( $_obfuscate_snM�['nodeType'] )
                {
                case "startNode" :
                    $_obfuscate_L9PX7r5kCPQ� = 1;
                    $_obfuscate_vpZO7cBY1GZYtnU� = intval( $_obfuscate_snM�['id'] );
                    break;
                case "endNode" :
                    $_obfuscate_L9PX7r5kCPQ� = 3;
                    break;
                case "bNode" :
                    $_obfuscate_L9PX7r5kCPQ� = 4;
                    break;
                case "cNode" :
                    $_obfuscate_L9PX7r5kCPQ� = 5;
                    break;
                case "bcNode" :
                    $_obfuscate_L9PX7r5kCPQ� = 6;
                    break;
                case "childNode" :
                    $_obfuscate_L9PX7r5kCPQ� = 7;
                    break;
                default :
                    $_obfuscate_L9PX7r5kCPQ� = 2;
                }
                $_obfuscate_xmNtcXVJoqq_154F = 0;
                if ( $_obfuscate_snM�['mark'] == "child" )
                {
                    $_obfuscate_xmNtcXVJoqq_154F = 1;
                }
                $_obfuscate_j9eamhY�[$_obfuscate_snM�['id']] = array(
                    "stepType" => addslashes( $_obfuscate_L9PX7r5kCPQ� ),
                    "stepId" => intval( $_obfuscate_snM�['id'] ),
                    "stepName" => addslashes( $_obfuscate_snM�['value'] ),
                    "childFlow" => $_obfuscate_xmNtcXVJoqq_154F,
                    "nextStep" => array( )
                );
                $_obfuscate_S0PSA37yAw��[] = $_obfuscate_snM�['id'];
            }
        }
        unset( $_obfuscate_6A�� );
        unset( $_obfuscate_snM� );
        foreach ( $_obfuscate_7B4LUz4� as $_obfuscate_6A�� )
        {
            if ( !in_array( $_obfuscate_6A��['target'], $_obfuscate_j9eamhY�[$_obfuscate_6A��['source']]['nextStep'] ) )
            {
                $_obfuscate_j9eamhY�[$_obfuscate_6A��['source']]['nextStep'][] = $_obfuscate_6A��['target'];
            }
            if ( !( $_obfuscate_6A��['mark'] == "bothway" ) && in_array( $_obfuscate_6A��['source'], $_obfuscate_j9eamhY�[$_obfuscate_6A��['target']]['nextStep'] ) )
            {
                $_obfuscate_j9eamhY�[$_obfuscate_6A��['target']]['nextStep'][] = $_obfuscate_6A��['source'];
            }
        }
        unset( $_obfuscate_6A�� );
        foreach ( $_obfuscate_j9eamhY� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_6A��['nextStep'] = json_encode( $_obfuscate_6A��['nextStep'] );
            $_obfuscate_6A��['flowId'] = $_obfuscate_F4AbnVRh;
            $_obfuscate_o5fQ1g�� = $CNOA_DB->db_getone( "*", $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `stepId`='{$_obfuscate_6A��['stepId']}'" );
            if ( !$_obfuscate_o5fQ1g�� )
            {
                $CNOA_DB->db_insert( $_obfuscate_6A��, $this->t_set_step );
            }
            else
            {
                $CNOA_DB->db_update( $_obfuscate_6A��, $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `stepId`='{$_obfuscate_6A��['stepId']}'" );
            }
            $CNOA_DB->db_delete( $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `stepId` NOT IN(".implode( ",", $_obfuscate_S0PSA37yAw�� ).")" );
        }
        return array(
            $_obfuscate_S0PSA37yAw��,
            $_obfuscate_vpZO7cBY1GZYtnU�
        );
    }

    private function _getList( )
    {
    }

    private function _editLoadData( )
    {
    }

    private function _add( )
    {
    }

    private function _edit( )
    {
    }

    private function _delete( $_obfuscate_F4AbnVRh, $_obfuscate_aMwmYI�, $_obfuscate_XkuTFqZ6Tmk�, $_obfuscate_pEvU7Kz2Yw�� )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_gftfagw� = $CNOA_DB->db_getcount( $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( $_obfuscate_gftfagw� && $_obfuscate_aMwmYI� === "false" )
        {
            msg::callback( FALSE, lang( "DelBPMfirst" ) );
        }
        if ( $_obfuscate_aMwmYI� === "true" )
        {
            $_obfuscate_8Bnz38wN01c� = $CNOA_DB->db_select( array( "uFlowId", "attach" ), $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
            if ( !is_array( $_obfuscate_8Bnz38wN01c� ) )
            {
                $_obfuscate_8Bnz38wN01c� = array( );
            }
            ( );
            $_obfuscate_2gg� = new fs( );
            foreach ( $_obfuscate_8Bnz38wN01c� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_WPvkSFEMg�� = json_decode( $_obfuscate_6A��['attach'], TRUE );
                if ( is_array( $_obfuscate_WPvkSFEMg�� ) && 0 < count( $_obfuscate_WPvkSFEMg�� ) )
                {
                    $_obfuscate_2gg�->deleteFile( $_obfuscate_WPvkSFEMg�� );
                }
                ( $_obfuscate_6A��['uFlowId'] );
                $_obfuscate_e53ODz04JQ�� = new wfCache( );
                $_obfuscate_e53ODz04JQ��->deleteCache( );
                $CNOA_DB->db_delete( $this->t_use_event, "WHERE `uFlowId`='".$_obfuscate_6A��['uFlowId']."'" );
                $CNOA_DB->db_delete( $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_6A��['uFlowId']."'" );
                $CNOA_DB->db_delete( $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_6A��['uFlowId']."'" );
                $CNOA_DB->db_delete( $this->t_use_step_huiqian, "WHERE `uFlowId`='".$_obfuscate_6A��['uFlowId']."'" );
                $CNOA_DB->db_delete( $this->t_use_proxy_uflow, "WHERE `uFlowId`='".$_obfuscate_6A��['uFlowId']."'" );
                $CNOA_DB->db_delete( $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
                $CNOA_DB->db_delete( "wf_u_convergence_deal", "WHERE `uFlowId`='".$_obfuscate_6A��['uFlowId']."'" );
            }
            $_obfuscate_7qDAYo85aGA� = $CNOA_DB->db_select( array( "id" ), $this->t_set_field, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `otype`='detailtable'" );
            if ( !is_array( $_obfuscate_7qDAYo85aGA� ) )
            {
                $_obfuscate_7qDAYo85aGA� = array( );
            }
            foreach ( $_obfuscate_7qDAYo85aGA� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_P8V5kc� = mysql_table_exists( "cnoa_z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_6A��['id'] );
                $_obfuscate_HO4Pzns� = mysql_table_exists( "cnoa_z_wf_t_".$_obfuscate_F4AbnVRh );
                if ( $_obfuscate_P8V5kc� )
                {
                    $CNOA_DB->query( "DROP TABLE cnoa_z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_6A��['id'] );
                }
                if ( $_obfuscate_HO4Pzns� )
                {
                    $CNOA_DB->query( "DROP TABLE cnoa_z_wf_t_".$_obfuscate_F4AbnVRh."" );
                }
            }
        }
        $_obfuscate_h8TEbtzlSdi = $CNOA_DB->db_select( "*", $this->t_use_proxy, "WHERE 1" );
        if ( !is_array( $_obfuscate_h8TEbtzlSdi ) )
        {
            $_obfuscate_h8TEbtzlSdi = array( );
        }
        foreach ( $_obfuscate_h8TEbtzlSdi as $_obfuscate_6A�� )
        {
            $_obfuscate_rRDK3rdtK6wC = json_decode( $_obfuscate_6A��['flowId'], TRUE );
            $_obfuscate_816eS6MRxRMEflcQ = array( );
            if ( is_array( $_obfuscate_rRDK3rdtK6wC ) && 0 < count( $_obfuscate_rRDK3rdtK6wC ) )
            {
                foreach ( $_obfuscate_rRDK3rdtK6wC as $_obfuscate_6cg� )
                {
                    if ( $_obfuscate_6cg� != $_obfuscate_F4AbnVRh )
                    {
                        $_obfuscate_816eS6MRxRMEflcQ[] = $_obfuscate_6cg�;
                    }
                }
            }
            $CNOA_DB->db_update( array(
                "flowId" => addslashes( json_encode( $_obfuscate_816eS6MRxRMEflcQ ) )
            ), $this->t_use_proxy, "WHERE `id`='".$_obfuscate_6A��['id']."'" );
        }
        $CNOA_DB->db_delete( $this->t_use_proxy_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $_obfuscate_neM4JBUJlmg� = $CNOA_DB->db_getfield( "name", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 3362, $_obfuscate_neM4JBUJlmg�, lang( "flow" ) );
        $CNOA_DB->db_delete( $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $CNOA_DB->db_delete( $this->t_set_field, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $CNOA_DB->db_delete( $this->t_set_field_detail, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."'" );
        $CNOA_DB->db_delete( $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $CNOA_DB->db_delete( $this->t_set_step_condition, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $CNOA_DB->db_delete( $this->t_set_step_fields, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $CNOA_DB->db_delete( $this->t_set_step_user, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $CNOA_DB->db_delete( $this->t_s_bingfa_condition, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $CNOA_DB->db_delete( "wf_s_step_child_kongjian", "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( $_obfuscate_XkuTFqZ6Tmk� == 0 && $_obfuscate_pEvU7Kz2Yw�� != 0 )
        {
            $_obfuscate_eG0q4_wH0Qc� = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/doc.history.0.php" );
            if ( file_exists( $_obfuscate_eG0q4_wH0Qc� ) )
            {
                $_obfuscate_8SedAwBPA�� = dirname( $_obfuscate_eG0q4_wH0Qc� );
                @rmdir( $_obfuscate_8SedAwBPA�� );
            }
        }
        $CNOA_DB->db_delete( "", $this->t_use_step_child_flow, "WHERE `flowId` = ".$_obfuscate_F4AbnVRh." AND `status` = 0 " );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function api_deleteDesignFlow( $_obfuscate_F4AbnVRh, $_obfuscate_aMwmYI� )
    {
        $this->_delete( $_obfuscate_F4AbnVRh, $_obfuscate_aMwmYI� );
    }

    private function _getStation( )
    {
        $GLOBALS['user']['permitArea']['area'] == "all";
        app::loadapp( "main", "station" )->api_getStationJsonData( );
    }

    private function _clone( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_SIUSR4F6 = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( $_obfuscate_SIUSR4F6 === FALSE )
        {
            msg::callback( FALSE, lang( "beCopyFlowNotExist" ) );
        }
        if ( CNOA_ISSAAS === TRUE )
        {
            unset( $_obfuscate_SIUSR4F6['domainID'] );
        }
        if ( $_obfuscate_SIUSR4F6 )
        {
            unset( $_obfuscate_SIUSR4F6['flowId'] );
            foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_SIUSR4F6[$_obfuscate_5w��] = addslashes( $_obfuscate_6A�� );
            }
            $_obfuscate_SIUSR4F6['name'] = getpar( $_POST, "name", "" );
            $_obfuscate_SIUSR4F6['nameRule'] = getpar( $_POST, "nameRule", "" );
            $_obfuscate_SIUSR4F6['sortId'] = getpar( $_POST, "sortId", 0 );
            $_obfuscate_SIUSR4F6['about'] = getpar( $_POST, "about", "" );
            $_obfuscate_SIUSR4F6['tplSort'] = getpar( $_POST, "tplSort", "" );
            $_obfuscate_SIUSR4F6['flowType'] = getpar( $_POST, "flowType", "" );
            $_obfuscate_SIUSR4F6['nameRuleAllowEdit'] = getpar( $_POST, "nameRuleAllowEdit", 0 );
            $_obfuscate_SIUSR4F6['nameDisallowBlank'] = getpar( $_POST, "nameDisallowBlank", 1 );
            $_obfuscate_SIUSR4F6['noticeAtGoBack'] = getpar( $_POST, "noticeAtGoBack", 1 );
            $_obfuscate_SIUSR4F6['noticeAtReject'] = getpar( $_POST, "noticeAtReject", 1 );
            $_obfuscate_SIUSR4F6['noticeAtInterrupt'] = getpar( $_POST, "noticeAtInterrupt", 1 );
            $_obfuscate_SIUSR4F6['noticeAtFinish'] = getpar( $_POST, "noticeAtFinish", 1 );
            $_obfuscate_SIUSR4F6['status'] = 0;
            $_obfuscate_SIUSR4F6['nameRuleId'] = 0;
            $_obfuscate_SIUSR4F6['uid'] = $_obfuscate_7Ri3;
            $_obfuscate_SIUSR4F6['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $_obfuscate_SIUSR4F6['bindfunction'] = "";
            $_obfuscate_7Jp_oeV8fuZh = $CNOA_DB->db_insert( $_obfuscate_SIUSR4F6, $this->t_set_flow );
        }
        $_obfuscate_Tx7M9W = $CNOA_DB->db_select( "*", $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( !is_array( $_obfuscate_Tx7M9W ) )
        {
            $_obfuscate_Tx7M9W = array( );
        }
        foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( CNOA_ISSAAS === TRUE )
            {
                unset( $_obfuscate_6A��['domainID'] );
            }
            unset( $_obfuscate_6A��['id'] );
            foreach ( $_obfuscate_6A�� as $_obfuscate_3QY� => $_obfuscate_EGU� )
            {
                $_obfuscate_6A��[$_obfuscate_3QY�] = addslashes( $_obfuscate_EGU� );
            }
            $_obfuscate_6A��['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
            $CNOA_DB->db_insert( $_obfuscate_6A��, $this->t_set_step );
        }
        $_obfuscate_u7iA1IfUn0� = array( );
        $_obfuscate_Iol2Fs8yYvPvnJBshw�� = array( );
        $_obfuscate_ECnh5SGd0mzGXxdpqqFH = array( );
        $_obfuscate_Pm3ZMWpPkg�� = $CNOA_DB->db_select( "*", $this->t_set_field, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( !is_array( $_obfuscate_Pm3ZMWpPkg�� ) )
        {
            $_obfuscate_Pm3ZMWpPkg�� = array( );
        }
        foreach ( $_obfuscate_Pm3ZMWpPkg�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_8jhldA9Y9A�� = $_obfuscate_6A��['id'];
            if ( CNOA_ISSAAS === TRUE )
            {
                unset( $_obfuscate_6A��['domainID'] );
            }
            $_obfuscate_Iol2Fs8yYvPvnJBshw��[$_obfuscate_8jhldA9Y9A��] = 0;
            $_obfuscate_I3l9sFvE = $_obfuscate_6A��['id'];
            unset( $_obfuscate_6A��['id'] );
            foreach ( $_obfuscate_6A�� as $_obfuscate_3QY� => $_obfuscate_EGU� )
            {
                $_obfuscate_6A��[$_obfuscate_3QY�] = addslashes( $_obfuscate_EGU� );
            }
            $_obfuscate_6A��['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
            if ( $_obfuscate_6A��['otype'] == "detailtable" )
            {
                $_obfuscate_ECnh5SGd0mzGXxdpqqFH[$_obfuscate_8jhldA9Y9A��] = $CNOA_DB->db_insert( $_obfuscate_6A��, $this->t_set_field );
                $_obfuscate_u7iA1IfUn0�['normal'][$_obfuscate_I3l9sFvE] = $_obfuscate_ECnh5SGd0mzGXxdpqqFH[$_obfuscate_8jhldA9Y9A��];
                $_obfuscate_Iol2Fs8yYvPvnJBshw��[$_obfuscate_8jhldA9Y9A��] = $_obfuscate_ECnh5SGd0mzGXxdpqqFH[$_obfuscate_8jhldA9Y9A��];
            }
            else
            {
                $_obfuscate_Iol2Fs8yYvPvnJBshw��[$_obfuscate_8jhldA9Y9A��] = $CNOA_DB->db_insert( $_obfuscate_6A��, $this->t_set_field );
                $_obfuscate_u7iA1IfUn0�['normal'][$_obfuscate_I3l9sFvE] = $_obfuscate_Iol2Fs8yYvPvnJBshw��[$_obfuscate_8jhldA9Y9A��];
            }
        }
        $_obfuscate_rsprfS3qDSJ2MvYrvA� = array( );
        $_obfuscate_7Hp0w_lfFt4� = $CNOA_DB->db_select( "*", $this->t_set_field_detail, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( !is_array( $_obfuscate_7Hp0w_lfFt4� ) )
        {
            $_obfuscate_7Hp0w_lfFt4� = array( );
        }
        foreach ( $_obfuscate_7Hp0w_lfFt4� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_Xw4g1h2yLKFUuQ�� = $_obfuscate_6A��['fid'];
            if ( CNOA_ISSAAS === TRUE )
            {
                unset( $_obfuscate_6A��['domainID'] );
            }
            $_obfuscate_ndJExEgCiEo8 = $_obfuscate_6A��['id'];
            $_obfuscate_rsprfS3qDSJ2MvYrvA�[$_obfuscate_ndJExEgCiEo8] = 0;
            $_obfuscate_I3l9sFvE = $_obfuscate_6A��['id'];
            unset( $_obfuscate_6A��['id'] );
            foreach ( $_obfuscate_6A�� as $_obfuscate_3QY� => $_obfuscate_EGU� )
            {
                $_obfuscate_6A��[$_obfuscate_3QY�] = addslashes( $_obfuscate_EGU� );
            }
            $_obfuscate_6A��['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
            $_obfuscate_6A��['fid'] = $_obfuscate_ECnh5SGd0mzGXxdpqqFH[$_obfuscate_Xw4g1h2yLKFUuQ��];
            $_obfuscate_rsprfS3qDSJ2MvYrvA�[$_obfuscate_ndJExEgCiEo8] = $CNOA_DB->db_insert( $_obfuscate_6A��, $this->t_set_field_detail );
            $_obfuscate_u7iA1IfUn0�['detail'][$_obfuscate_I3l9sFvE] = $_obfuscate_rsprfS3qDSJ2MvYrvA�[$_obfuscate_ndJExEgCiEo8];
        }
        $_obfuscate_8MSmTrf2URD2fg�� = $CNOA_DB->db_select( "*", $this->t_set_step_fields, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( !is_array( $_obfuscate_8MSmTrf2URD2fg�� ) )
        {
            $_obfuscate_8MSmTrf2URD2fg�� = array( );
        }
        foreach ( $_obfuscate_8MSmTrf2URD2fg�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            unset( $_obfuscate_6A��['id'] );
            if ( CNOA_ISSAAS === TRUE )
            {
                unset( $_obfuscate_6A��['domainID'] );
            }
            foreach ( $_obfuscate_6A�� as $_obfuscate_3QY� => $_obfuscate_EGU� )
            {
                $_obfuscate_6A��[$_obfuscate_3QY�] = addslashes( $_obfuscate_EGU� );
            }
            if ( $_obfuscate_6A��['from'] == 0 )
            {
                $_obfuscate_6A��['fieldId'] = $_obfuscate_Iol2Fs8yYvPvnJBshw��[$_obfuscate_6A��['fieldId']];
            }
            else
            {
                $_obfuscate_6A��['fieldId'] = $_obfuscate_rsprfS3qDSJ2MvYrvA�[$_obfuscate_6A��['fieldId']];
            }
            $_obfuscate_6A��['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
            $CNOA_DB->db_insert( $_obfuscate_6A��, $this->t_set_step_fields );
        }
        $_obfuscate_kdHhV5xyiC7wnDiem5LxSqE9Fe2c4E� = array( );
        $_obfuscate_XfY8n0HVHlugRiyQdw�� = $CNOA_DB->db_select( "*", $this->t_set_step_condition, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' ORDER BY `id` ASC" );
        if ( !is_array( $_obfuscate_XfY8n0HVHlugRiyQdw�� ) )
        {
            $_obfuscate_XfY8n0HVHlugRiyQdw�� = array( );
        }
        foreach ( $_obfuscate_XfY8n0HVHlugRiyQdw�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_sBdvc3n = $_obfuscate_6A��['id'];
            unset( $_obfuscate_6A��['id'] );
            if ( CNOA_ISSAAS === TRUE )
            {
                unset( $_obfuscate_6A��['domainID'] );
            }
            foreach ( $_obfuscate_6A�� as $_obfuscate_3QY� => $_obfuscate_EGU� )
            {
                $_obfuscate_6A��[$_obfuscate_3QY�] = addslashes( $_obfuscate_EGU� );
            }
            $_obfuscate_6A��['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
            $_obfuscate_6A��['name'] = $_obfuscate_Iol2Fs8yYvPvnJBshw��[$_obfuscate_6A��['name']];
            $_obfuscate_kdHhV5xyiC7wnDiem5LxSqE9Fe2c4E�[$_obfuscate_sBdvc3n] = $CNOA_DB->db_insert( $_obfuscate_6A��, $this->t_set_step_condition );
        }
        foreach ( $_obfuscate_kdHhV5xyiC7wnDiem5LxSqE9Fe2c4E� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_XcKeMw�� = $CNOA_DB->db_getone( "*", $this->t_set_step_condition, "WHERE `id`='".$_obfuscate_6A��."'" );
            if ( $_obfuscate_XcKeMw��['pid'] != 0 )
            {
                $CNOA_DB->db_update( array(
                    "pid" => $_obfuscate_kdHhV5xyiC7wnDiem5LxSqE9Fe2c4E�[$_obfuscate_XcKeMw��['pid']]
                ), $this->t_set_step_condition, "WHERE `id`='".$_obfuscate_6A��."'" );
            }
        }
        $_obfuscate_o_IRkEDpxeo� = $CNOA_DB->db_select( "*", $this->t_set_step_user, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( !is_array( $_obfuscate_o_IRkEDpxeo� ) )
        {
            $_obfuscate_o_IRkEDpxeo� = array( );
        }
        foreach ( $_obfuscate_o_IRkEDpxeo� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            unset( $_obfuscate_6A��['id'] );
            if ( CNOA_ISSAAS === TRUE )
            {
                unset( $_obfuscate_6A��['domainID'] );
            }
            $_obfuscate_6A��['kong'] = $_obfuscate_6A��['kong'] != 0 ? $_obfuscate_Iol2Fs8yYvPvnJBshw��[$_obfuscate_6A��['kong']] : 0;
            foreach ( $_obfuscate_6A�� as $_obfuscate_3QY� => $_obfuscate_EGU� )
            {
                $_obfuscate_6A��[$_obfuscate_3QY�] = addslashes( $_obfuscate_EGU� );
            }
            $_obfuscate_6A��['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
            $CNOA_DB->db_insert( $_obfuscate_6A��, $this->t_set_step_user );
        }
        unset( $_obfuscate_o_IRkEDpxeo� );
        $_obfuscate_WxmaeT80fdbw = $CNOA_DB->db_select( "*", $this->t_s_bingfa_condition, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( !is_array( $_obfuscate_WxmaeT80fdbw ) )
        {
            $_obfuscate_WxmaeT80fdbw = array( );
        }
        foreach ( $_obfuscate_WxmaeT80fdbw as $_obfuscate_6A�� )
        {
            unset( $_obfuscate_6A��['id'] );
            if ( CNOA_ISSAAS === TRUE )
            {
                unset( $_obfuscate_6A��['domainID'] );
            }
            $_obfuscate_6A��['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
            $CNOA_DB->db_insert( $_obfuscate_6A��, $this->t_s_bingfa_condition );
        }
        unset( $_obfuscate_WxmaeT80fdbw );
        $_obfuscate_WO71JHrWfe8IY1qw�� = $CNOA_DB->db_select( "*", "wf_s_step_child_kongjian", "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( !is_array( $_obfuscate_WO71JHrWfe8IY1qw�� ) )
        {
            $_obfuscate_WO71JHrWfe8IY1qw�� = array( );
        }
        foreach ( $_obfuscate_WO71JHrWfe8IY1qw�� as $_obfuscate_6A�� )
        {
            unset( $_obfuscate_6A��['id'] );
            if ( CNOA_ISSAAS === TRUE )
            {
                unset( $_obfuscate_6A��['domainID'] );
            }
            $_obfuscate_6A��['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
            $_obfuscate_6A��['parentKongjian'] = "T_".$_obfuscate_Iol2Fs8yYvPvnJBshw��[substr( $_obfuscate_6A��['parentKongjian'], 2 )];
            $CNOA_DB->db_insert( $_obfuscate_6A��, "wf_s_step_child_kongjian" );
        }
        unset( $_obfuscate_WO71JHrWfe8IY1qw�� );
        $this->api_createTable( $_obfuscate_7Jp_oeV8fuZh, $_obfuscate_SIUSR4F6 );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3662, $_obfuscate_SIUSR4F6['name'], lang( "flowCopy" ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _cloneFreeFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_SIUSR4F6 = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( $_obfuscate_SIUSR4F6 === FALSE )
        {
            msg::callback( FALSE, lang( "beCopyFlowNotExist" ) );
        }
        if ( $_obfuscate_SIUSR4F6 )
        {
            unset( $_obfuscate_SIUSR4F6['flowId'] );
            foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_SIUSR4F6[$_obfuscate_5w��] = addslashes( $_obfuscate_6A�� );
            }
            $_obfuscate_SIUSR4F6['name'] = getpar( $_POST, "name", "" );
            $_obfuscate_SIUSR4F6['nameRule'] = getpar( $_POST, "nameRule", "" );
            $_obfuscate_SIUSR4F6['sortId'] = getpar( $_POST, "sortId", 0 );
            $_obfuscate_SIUSR4F6['about'] = getpar( $_POST, "about", "" );
            $_obfuscate_SIUSR4F6['tplSort'] = getpar( $_POST, "tplSort", "" );
            $_obfuscate_SIUSR4F6['flowType'] = getpar( $_POST, "flowType", "" );
            $_obfuscate_SIUSR4F6['nameRuleAllowEdit'] = getpar( $_POST, "nameRuleAllowEdit", 0 );
            $_obfuscate_SIUSR4F6['nameDisallowBlank'] = getpar( $_POST, "nameDisallowBlank", 1 );
            $_obfuscate_SIUSR4F6['noticeAtGoBack'] = getpar( $_POST, "noticeAtGoBack", 1 );
            $_obfuscate_SIUSR4F6['noticeAtReject'] = getpar( $_POST, "noticeAtReject", 1 );
            $_obfuscate_SIUSR4F6['noticeAtInterrupt'] = getpar( $_POST, "noticeAtInterrupt", 1 );
            $_obfuscate_SIUSR4F6['noticeAtFinish'] = getpar( $_POST, "noticeAtFinish", 1 );
            $_obfuscate_SIUSR4F6['status'] = 0;
            $_obfuscate_SIUSR4F6['nameRuleId'] = 0;
            $_obfuscate_SIUSR4F6['uid'] = $_obfuscate_7Ri3;
            $_obfuscate_SIUSR4F6['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $_obfuscate_7Jp_oeV8fuZh = $CNOA_DB->db_insert( $_obfuscate_SIUSR4F6, $this->t_set_flow );
            if ( $_obfuscate_XkuTFqZ6Tmk� == 0 && $_obfuscate_pEvU7Kz2Yw�� != 0 )
            {
                $_obfuscate_eG0q4_wH0Qc� = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/doc.history.0.php" );
                if ( file_exists( $_obfuscate_eG0q4_wH0Qc� ) )
                {
                    $_obfuscate_3D8Qp35EPoWsdC0� = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_7Jp_oeV8fuZh."/doc.history.0.php" );
                    @mkdirs( @dirname( $_obfuscate_3D8Qp35EPoWsdC0� ) );
                    @copy( $_obfuscate_eG0q4_wH0Qc�, $_obfuscate_3D8Qp35EPoWsdC0� );
                }
                $_obfuscate_Tx7M9W = $CNOA_DB->db_select( "*", $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
                if ( !is_array( $_obfuscate_Tx7M9W ) )
                {
                    $_obfuscate_Tx7M9W = array( );
                }
                foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5w�� => $_obfuscate_6A�� )
                {
                    unset( $_obfuscate_6A��['id'] );
                    foreach ( $_obfuscate_6A�� as $_obfuscate_3QY� => $_obfuscate_EGU� )
                    {
                        $_obfuscate_6A��[$_obfuscate_3QY�] = addslashes( $_obfuscate_EGU� );
                    }
                    $_obfuscate_6A��['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
                    $CNOA_DB->db_insert( $_obfuscate_6A��, $this->t_set_step );
                }
                $_obfuscate_XfY8n0HVHlugRiyQdw�� = $CNOA_DB->db_select( "*", $this->t_set_step_condition, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
                if ( !is_array( $_obfuscate_XfY8n0HVHlugRiyQdw�� ) )
                {
                    $_obfuscate_XfY8n0HVHlugRiyQdw�� = array( );
                }
                foreach ( $_obfuscate_XfY8n0HVHlugRiyQdw�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
                {
                    unset( $_obfuscate_6A��['id'] );
                    foreach ( $_obfuscate_6A�� as $_obfuscate_3QY� => $_obfuscate_EGU� )
                    {
                        $_obfuscate_6A��[$_obfuscate_3QY�] = addslashes( $_obfuscate_EGU� );
                    }
                    $_obfuscate_6A��['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
                    $CNOA_DB->db_insert( $_obfuscate_6A��, $this->t_set_step_condition );
                }
                $_obfuscate_o_IRkEDpxeo� = $CNOA_DB->db_select( "*", $this->t_set_step_user, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
                if ( !is_array( $_obfuscate_o_IRkEDpxeo� ) )
                {
                    $_obfuscate_o_IRkEDpxeo� = array( );
                }
                foreach ( $_obfuscate_o_IRkEDpxeo� as $_obfuscate_5w�� => $_obfuscate_6A�� )
                {
                    unset( $_obfuscate_6A��['id'] );
                    foreach ( $_obfuscate_6A�� as $_obfuscate_3QY� => $_obfuscate_EGU� )
                    {
                        $_obfuscate_6A��[$_obfuscate_3QY�] = addslashes( $_obfuscate_EGU� );
                    }
                    $_obfuscate_6A��['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
                    $CNOA_DB->db_insert( $_obfuscate_6A��, $this->t_set_step_user );
                }
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getFlowConvergence( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", "" );
        $_obfuscate_0Ul8BBkt = getpar( $_GET, "stepId", "" );
        if ( !empty( $_obfuscate_F4AbnVRh ) )
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "stepId", "stepName" ), $this->t_set_step, "WHERE `flowId`=".$_obfuscate_F4AbnVRh );
            if ( !is_array( $_obfuscate_mPAjEGLn ) )
            {
                $_obfuscate_mPAjEGLn = array( );
            }
            $_obfuscate_VBCv7Q�� = array( );
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A�� )
            {
                $_obfuscate_VBCv7Q��[$_obfuscate_6A��['stepId']] = $_obfuscate_6A��['stepName'];
            }
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "id", "condition" ), $this->t_s_bingfa_condition, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND `stepId`={$_obfuscate_0Ul8BBkt}" );
            if ( !is_array( $_obfuscate_mPAjEGLn ) )
            {
                $_obfuscate_mPAjEGLn = array( );
            }
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_6A�� = json_decode( $_obfuscate_6A��['condition'], TRUE );
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['text'] = "";
                foreach ( $_obfuscate_6A�� as $_obfuscate_EGU� )
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['text'] .= $_obfuscate_VBCv7Q��[$_obfuscate_EGU�]."+";
                }
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['text'] = substr( $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['text'], 0, -1 );
            }
            ( );
            $_obfuscate_SUjPN94Er7yI = new dataStore( );
            $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_mPAjEGLn;
            echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        }
        exit( );
    }

    private function _flowKongjianData( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_Pm3ZMWpPkg�� = $CNOA_DB->db_select( "*", $this->t_set_field, "WHERE `flowId` = ".$_obfuscate_F4AbnVRh." " );
        $_obfuscate_mPAjEGLn = array( );
        if ( !is_array( $_obfuscate_Pm3ZMWpPkg�� ) )
        {
            $_obfuscate_Pm3ZMWpPkg�� = array( );
        }
        foreach ( $_obfuscate_Pm3ZMWpPkg�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_p5ZWxr4� = json_decode( str_replace( "'", "\"", $_obfuscate_6A��['odata'] ), TRUE );
            $_obfuscate_SeV31Q��['id'] = "T_".$_obfuscate_6A��['id'];
            $_obfuscate_SeV31Q��['name'] = $_obfuscate_6A��['name'];
            $_obfuscate_SeV31Q��['childType'] = $_obfuscate_p5ZWxr4�['dataType'];
            $_obfuscate_mPAjEGLn[] = $_obfuscate_SeV31Q��;
        }
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    private function _filterConvergence( $_obfuscate_2WmUvLIQx8SCLGg�, $_obfuscate_0Ul8BBkt, $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        if ( empty( $_obfuscate_2WmUvLIQx8SCLGg� ) )
        {
            $CNOA_DB->db_delete( $this->t_s_bingfa_condition, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND `stepId`={$_obfuscate_0Ul8BBkt}" );
        }
        else
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "id" ), $this->t_s_bingfa_condition, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND `stepId`={$_obfuscate_0Ul8BBkt}" );
            if ( !is_array( $_obfuscate_mPAjEGLn ) )
            {
                $_obfuscate_mPAjEGLn = array( );
            }
            $_obfuscate_905Hi3c� = array( );
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A�� )
            {
                $_obfuscate_905Hi3c�[] = $_obfuscate_6A��['id'];
            }
            unset( $_obfuscate_mPAjEGLn );
            $_obfuscate_6RYLWQ�� = array( );
            foreach ( $_obfuscate_2WmUvLIQx8SCLGg� as $_obfuscate_6A�� )
            {
                if ( $_obfuscate_6A��['id'] == 0 )
                {
                    $_obfuscate_6RYLWQ��[] = "('".$_obfuscate_F4AbnVRh."','{$_obfuscate_0Ul8BBkt}','{$_obfuscate_6A��['condition']}')";
                }
                else
                {
                    unset( $_obfuscate_905Hi3c�[array_search( $_obfuscate_6A��['id'], $_obfuscate_905Hi3c� )] );
                }
            }
            if ( !empty( $_obfuscate_6RYLWQ�� ) )
            {
                $_obfuscate_3y0Y = "INSERT INTO ".tname( $this->t_s_bingfa_condition )." (`flowId`,`stepId`,`condition`) VALUES ".implode( ",", $_obfuscate_6RYLWQ�� );
                $CNOA_DB->query( $_obfuscate_3y0Y );
            }
            unset( $_obfuscate_6RYLWQ�� );
            $_obfuscate_905Hi3c� = implode( ",", $_obfuscate_905Hi3c� );
            if ( !empty( $_obfuscate_905Hi3c� ) )
            {
                $CNOA_DB->db_delete( $this->t_s_bingfa_condition, "WHERE `id` IN (".$_obfuscate_905Hi3c�.")" );
            }
            unset( $_obfuscate_905Hi3c� );
        }
    }

    private function _filterChild( $_obfuscate_eBU_Sjc�, $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $CNOA_DB->db_delete( $this->t_set_step_child_kongjian, "WHERE `flowId` = ".$_obfuscate_F4AbnVRh." AND `cStepId` = {$_obfuscate_0Ul8BBkt} " );
        foreach ( $_obfuscate_eBU_Sjc� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_6RYLWQ�� = array( );
            $_obfuscate_6RYLWQ��['bangdingFlow'] = $_obfuscate_6A��['bangdingFlow'];
            $_obfuscate_6RYLWQ��['childKongjian'] = $_obfuscate_6A��['childKongjian'];
            if ( ereg( "D_d_", $_obfuscate_6A��['parentKongjian'] ) )
            {
                $_obfuscate_6RYLWQ��['parentKongjian'] = str_replace( "D_d_", "D_", $_obfuscate_6A��['parentKongjian'] );
            }
            else
            {
                $_obfuscate_6RYLWQ��['parentKongjian'] = $_obfuscate_6A��['parentKongjian'];
            }
            $_obfuscate_6RYLWQ��['arrow'] = $_obfuscate_6A��['arrow'];
            $_obfuscate_6RYLWQ��['flowId'] = $_obfuscate_F4AbnVRh;
            $_obfuscate_6RYLWQ��['stepId'] = $_obfuscate_6A��['stepId'];
            $_obfuscate_6RYLWQ��['cStepId'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_6RYLWQ��['childType'] = $_obfuscate_6A��['childType'];
            $_obfuscate_6RYLWQ��['parentType'] = $_obfuscate_6A��['parentType'];
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_set_step_child_kongjian );
        }
    }

    private function _filterDeal( $_obfuscate_rFR1zydgg��, $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $CNOA_DB->db_delete( $this->t_use_deal_way, "WHERE `stepId`='".$_obfuscate_0Ul8BBkt."' AND `flowId`='{$_obfuscate_F4AbnVRh}' " );
        if ( !empty( $_obfuscate_rFR1zydgg�� ) )
        {
            $_obfuscate_6RYLWQ�� = array( );
            $_obfuscate_6RYLWQ��['stepId'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_6RYLWQ��['flowId'] = $_obfuscate_F4AbnVRh;
            $_obfuscate_6RYLWQ��['deal'] = $_obfuscate_rFR1zydgg��['deal'];
            if ( $_obfuscate_6RYLWQ��['deal'] )
            {
                $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_use_deal_way );
            }
        }
    }

    private function _exportFlow( )
    {
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        ( $_obfuscate_F4AbnVRh );
        $_obfuscate_KiK8 = new wfExportImport( );
        $_obfuscate_KiK8->export( $_obfuscate_F4AbnVRh );
    }

    private function _exportFlowDownload( )
    {
        ( );
        $_obfuscate_KiK8 = new wfExportImport( );
        $_obfuscate_KiK8->downloadExportedFile( );
    }

    private function _importFlow( )
    {
        ( );
        $_obfuscate_KiK8 = new wfExportImport( );
        $_obfuscate_KiK8->importFlow( );
    }

    public function api_getFlowData( $_obfuscate_UZ68Oucf )
    {
        global $CNOA_DB;
        if ( empty( $_obfuscate_UZ68Oucf['flowIdArr'] ) )
        {
            return array( );
        }
        array_push( &$_obfuscate_UZ68Oucf['field'], "flowId" );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( $_obfuscate_UZ68Oucf['field'], $this->t_set_flow, "WHERE `flowId` IN (".implode( ",", $_obfuscate_UZ68Oucf['flowIdArr'] ).") " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_6RYLWQ��[$_obfuscate_6A��['flowId']] = $_obfuscate_6A��;
        }
        return $_obfuscate_6RYLWQ��;
    }

    public function _getOrderList( )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $this->_getJsonData( TRUE );
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    public function _getOrderForm( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_fbdkiHkL = getpar( $_POST, "order", "" );
        $_obfuscate_fbdkiHkL = substr( $_obfuscate_fbdkiHkL, 0 );
        $_obfuscate_fbdkiHkL = explode( ",", $_obfuscate_fbdkiHkL );
        foreach ( $_obfuscate_fbdkiHkL as $_obfuscate_Vwty => $_obfuscate_F4AbnVRh )
        {
            $_obfuscate_f517kg�� = intval( $_obfuscate_Vwty ) + 1;
            $_obfuscate_6RYLWQ�� = array( );
            $_obfuscate_6RYLWQ��['order'] = $_obfuscate_f517kg��;
            $CNOA_DB->db_update( $_obfuscate_6RYLWQ��, $this->t_set_flow, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' " );
        }
        msg::callback( TRUE, lang( "successopt" ) );
        exit( );
    }

    private function _taoHong( )
    {
        $_obfuscate_LeS8hw�� = getpar( $_GET, "type" );
        switch ( $_obfuscate_LeS8hw�� )
        {
        case "tplList" :
            $this->_tplList( );
            exit( );
        case "upload" :
            $this->_upload( );
            exit( );
        case "delTpl" :
            $this->_delTpl( );
            exit( );
        case "getfile" :
            $this->_getfile( );
        }
        exit( );
    }

    private function _tplList( )
    {
        global $CNOA_DB;
        $_obfuscate_y6jH = session_id( );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "id", "oldname", "name" ), $this->t_set_taohong );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_Il8i = "index.php?app=wf&func=flow&action=set&modul=flow&task=taoHong&type=getfile&file=".$_obfuscate_6A��['name']."&CNOAOASESSID={$_obfuscate_y6jH}";
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['urlView'] = "<a href='javascript:void(0);' class='gridview' onclick='openOfficeForView(\"".$_obfuscate_Il8i."\", \"doc\", \"{$_obfuscate_6A��['oldname']}\")'>浏览</a>";
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['urlEdit'] = "<a href='javascript:void(0);' class='gridview3 jianju' onclick='openOfficeForEdit_Attach(\"".$_obfuscate_Il8i."\", \"doc\", \"{$_obfuscate_6A��['oldname']}\", 1, \"{$_obfuscate_Il8i}&edit=msofficeeditsubmit\", true)'>".lang( "modify" )."</a>";
        }
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_mPAjEGLn;
        $_obfuscate_NlQ�->total = $CNOA_DB->db_getcount( $this->t_set_taohong );
        echo $_obfuscate_NlQ�->makeJsonData( );
    }

    private function _getfile( )
    {
        $_obfuscate_6hS1Rw�� = getpar( $_GET, "file" );
        $_obfuscate_6aSoeVqLulQ� = getpar( $_GET, "edit" );
        $_obfuscate_Il8i = CNOA_PATH_FILE."/common/wf/weboffice/".$_obfuscate_6hS1Rw��;
        if ( $_obfuscate_6aSoeVqLulQ� == "msofficeeditsubmit" )
        {
            cnoa_move_uploaded_file( $_FILES['msOffice']['tmp_name'], $_obfuscate_Il8i );
            exit( );
        }
        echo file_get_contents( $_obfuscate_Il8i );
    }

    private function _upload( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        @ini_set( "default_socket_timeout", "86400" );
        @ini_set( "max_input_time", "86400" );
        set_time_limit( 0 );
        $_obfuscate_VXnKvu82BA�� = TRUE;
        if ( !isset( $_FILES['Filedata'] ) )
        {
            $_obfuscate_mHQL4kA3m08nUiQ� = lang( "fileTooBigToUpload" );
            $_obfuscate_VXnKvu82BA�� = FALSE;
        }
        else if ( !is_uploaded_file( $_FILES['Filedata']['tmp_name'] ) )
        {
            $_obfuscate_mHQL4kA3m08nUiQ� = lang( "notNormalFile" );
            $_obfuscate_VXnKvu82BA�� = FALSE;
        }
        else if ( $_FILES['Filedata']['error'] != 0 )
        {
            $_obfuscate_mHQL4kA3m08nUiQ� = lang( "uploadError" ).":" + $_FILES['Filedata']['error'];
            $_obfuscate_VXnKvu82BA�� = FALSE;
        }
        else
        {
            $_obfuscate_mHQL4kA3m08nUiQ� = lang( "uploadSucess" );
        }
        $_obfuscate_p9iS3rrNwQQ� = CNOA_PATH_FILE."/common/wf/weboffice/";
        @mkdirs( $_obfuscate_p9iS3rrNwQQ� );
        if ( $_obfuscate_VXnKvu82BA�� )
        {
            $_obfuscate_moWVHtDG_A�� = strtolower( strrchr( $_FILES['Filedata']['name'], "." ) );
            if ( $_obfuscate_moWVHtDG_A�� == ".doc" || $_obfuscate_moWVHtDG_A�� == ".docx" )
            {
                $_obfuscate_JTe7jJ4eGW8� = string::rands( 50 ).".cnoa";
                $_obfuscate_OESonJ_jLYc� = $_obfuscate_p9iS3rrNwQQ�."/".$_obfuscate_JTe7jJ4eGW8�;
                @cnoa_move_uploaded_file( $_FILES['Filedata']['tmp_name'], $_obfuscate_OESonJ_jLYc� );
                $_obfuscate_6RYLWQ�� = array( );
                $_obfuscate_6RYLWQ��['oldname'] = $_FILES['Filedata']['name'];
                $_obfuscate_6RYLWQ��['name'] = $_obfuscate_JTe7jJ4eGW8�;
                $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_set_taohong );
            }
        }
        msg::callback( $_obfuscate_VXnKvu82BA��, $_obfuscate_mHQL4kA3m08nUiQ� );
    }

    private function _delTpl( )
    {
        global $CNOA_DB;
        $_obfuscate_1jUa = getpar( $_POST, "ids" );
        if ( empty( $_obfuscate_1jUa ) )
        {
            msg::callback( FALSE, lang( "delFail" ) );
        }
        if ( isinformat( $_obfuscate_1jUa ) )
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "name" ), $this->t_set_taohong, "WHERE `id` IN (".$_obfuscate_1jUa.")" );
            $CNOA_DB->db_delete( $this->t_set_taohong, "WHERE `id` IN (".$_obfuscate_1jUa.")" );
            if ( !is_array( $_obfuscate_mPAjEGLn ) )
            {
                $_obfuscate_mPAjEGLn = array( );
            }
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A�� )
            {
                if ( !empty( $_obfuscate_6A��['name'] ) )
                {
                    @unlink( CNOA_PATH_FILE."/common/wf/weboffice/".$_obfuscate_6A��['name'] );
                }
            }
            msg::callback( TRUE, lang( "successopt" ) );
        }
    }

    private function _checkStepUser( )
    {
        global $CNOA_DB;
        $_obfuscate_3y0Y = "SELECT a.flowid, a.name, b.stepName, b.stepid, c.id FROM cnoa_wf_s_flow AS a \r\n\t\t\t\tLEFT JOIN cnoa_wf_s_step AS b ON a.flowid = b.flowid \r\n\t\t\t\tLEFT JOIN cnoa_wf_s_step_user AS c ON (a.flowid=c.flowid AND b.stepid = c.stepid) \r\n\t\t\t\tWHERE c.type = '' AND b.stepType!=1 AND b.stepType!=3 AND b.stepType!=4 AND b.stepType!=5\r\n\t\t\t\tORDER BY a.flowid,b.stepid";
        $_obfuscate_ammigv8� = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQ�� = array( );
        while ( $_obfuscate_SF4� = $CNOA_DB->get_array( $_obfuscate_ammigv8� ) )
        {
            $_obfuscate_6RYLWQ��[] = $_obfuscate_SF4�;
        }
        $_obfuscate_A1jN = "";
        foreach ( $_obfuscate_6RYLWQ�� as $_obfuscate_VgKtFeg� )
        {
            $_obfuscate_A1jN .= "流程[<span style='color:red;'>".$_obfuscate_VgKtFeg�['name']."</span>]步骤[<span style='color:green;'>{$_obfuscate_VgKtFeg�['stepName']}</span>]不存在经办人<br />";
        }
        return $_obfuscate_A1jN;
    }

    private function _checkStepCondition( )
    {
        global $CNOA_DB;
        $_obfuscate_A1jN = $this->_checkStepUser( );
        $_obfuscate_3y0Y = "SELECT a.fieldType, a.ovalue, b.name, c.stepName FROM cnoa_wf_s_step_condition AS a \r\n\t\t\t\tLEFT JOIN cnoa_wf_s_flow AS b ON a.flowid = b.flowid \r\n\t\t\t\tLEFT JOIN cnoa_wf_s_step AS c ON ( a.flowid = c.flowid AND a.stepid = c.stepid ) \r\n\t\t\t\tWHERE a.fieldType != 'nor'";
        $_obfuscate_ammigv8� = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQ�� = array( );
        while ( $_obfuscate_SF4� = $CNOA_DB->get_array( $_obfuscate_ammigv8� ) )
        {
            $_obfuscate_6RYLWQ��[] = $_obfuscate_SF4�;
        }
        foreach ( $_obfuscate_6RYLWQ�� as $_obfuscate_VgKtFeg� )
        {
            $_obfuscate_BcV_lA�� = "流程[<span style='color:red;'>".$_obfuscate_VgKtFeg�['name']."</span>]步骤[<span style='color:green;'>{$_obfuscate_VgKtFeg�['stepName']}</span>]条件设置不存在";
            $_obfuscate_R6z0YA�� = "[<span style='color:blue;'>".$_obfuscate_VgKtFeg�['ovalue']."</span>]<br />";
            if ( ( $_obfuscate_VgKtFeg�['fieldType'] == "n_n" || $_obfuscate_VgKtFeg�['fieldType'] == "d_n" ) && !empty( $_obfuscate_VgKtFeg�['ovalue'] ) )
            {
                $_obfuscate_gftfagw� = $CNOA_DB->db_getcount( "main_user", "WHERE `truename`='".$_obfuscate_VgKtFeg�['ovalue']."' AND `isSystemUser`=1 AND `workStatusType`=1" );
                if ( empty( $_obfuscate_gftfagw� ) )
                {
                    $_obfuscate_A1jN .= "{$_obfuscate_BcV_lA��}用户{$_obfuscate_R6z0YA��}";
                }
            }
            if ( ( $_obfuscate_VgKtFeg�['fieldType'] == "n_s" || $_obfuscate_VgKtFeg�['fieldType'] == "d_s" ) && !empty( $_obfuscate_VgKtFeg�['ovalue'] ) )
            {
                $_obfuscate_gftfagw� = $CNOA_DB->db_getcount( "main_station", "WHERE `name`='".$_obfuscate_VgKtFeg�['ovalue']."'" );
                if ( empty( $_obfuscate_gftfagw� ) )
                {
                    $_obfuscate_A1jN .= "{$_obfuscate_BcV_lA��}岗位{$_obfuscate_R6z0YA��}";
                }
            }
            if ( !( $_obfuscate_VgKtFeg�['fieldType'] == "n_d" ) || !( $_obfuscate_VgKtFeg�['fieldType'] == "d_d" ) && empty( $_obfuscate_VgKtFeg�['ovalue'] ) )
            {
                $_obfuscate_gftfagw� = $CNOA_DB->db_getcount( "main_struct", "WHERE `name`='".$_obfuscate_VgKtFeg�['ovalue']."'" );
                if ( empty( $_obfuscate_gftfagw� ) )
                {
                    $_obfuscate_A1jN .= "{$_obfuscate_BcV_lA��}部门{$_obfuscate_R6z0YA��}";
                }
            }
        }
        if ( empty( $_obfuscate_A1jN ) )
        {
            msg::callback( TRUE, "流程设置没问题" );
        }
        else
        {
            $_obfuscate_A1jN = substr( $_obfuscate_A1jN, 0, -6 );
            msg::callback( FALSE, $_obfuscate_A1jN );
        }
    }

}

?>
