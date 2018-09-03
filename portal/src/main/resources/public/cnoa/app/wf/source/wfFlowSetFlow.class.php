<?php

class wfFlowSetFlow extends wfCommon
{

    public static $OPERATE_TYPE_HUIQIAN = 1;
    public static $OPERATE_TYPE_FENFA = 2;

    public function run( )
    {
        $_obfuscate_M_5JJwÿÿ = getpar( $_GET, "task", getpar( $_POST, "task", "" ) );
        switch ( $_obfuscate_M_5JJwÿÿ )
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
            $_obfuscate_phKp89pDgwQÿ = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $_obfuscate_phKp89pDgwQÿ );
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
            $_obfuscate_aMwmYIÿ = getpar( $_POST, "checked", "" );
            $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_POST, "flowType", 0 );
            $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_POST, "tplSort", 0 );
            $this->_delete( $_obfuscate_F4AbnVRh, $_obfuscate_aMwmYIÿ, $_obfuscate_XkuTFqZ6Tmkÿ, $_obfuscate_pEvU7Kz2Ywÿÿ );
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
        $_obfuscate_vholQÿÿ = getpar( $_GET, "from", getpar( $_POST, "from", "list" ) );
        switch ( $_obfuscate_vholQÿÿ )
        {
        case "list" :
            $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/set/flow.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQÿÿ );
            exit( );
        case "taohong" :
            $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/set/taohong.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQÿÿ );
        }
    }

    private function _submitFlowDesignData( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_6RYLWQÿÿ = json_decode( $_POST['data'], TRUE );
        $_obfuscate_4FIIlesHxwÿÿ = $_obfuscate_6RYLWQÿÿ['flowXml'];
        $_obfuscate_Wpkgl9Q6Gm = $_obfuscate_6RYLWQÿÿ['flowHtml5'];
        $_obfuscate_S0PSA37yAwÿÿ = $this->__updateStepsInfo( $_obfuscate_4FIIlesHxwÿÿ, $_obfuscate_F4AbnVRh );
        $_obfuscate_F1iF5t0j = array( );
        $_obfuscate_F1iF5t0j['flowXml'] = addslashes( $_obfuscate_4FIIlesHxwÿÿ );
        $_obfuscate_F1iF5t0j['flowHtml5'] = addslashes( $_obfuscate_Wpkgl9Q6Gm );
        $_obfuscate_F1iF5t0j['startStepId'] = $_obfuscate_S0PSA37yAwÿÿ[1];
        $CNOA_DB->db_update( $_obfuscate_F1iF5t0j, $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( !is_array( $_obfuscate_6RYLWQÿÿ['steps'] ) )
        {
            $_obfuscate_6RYLWQÿÿ['steps'] = array( );
        }
        $_obfuscate_SnVDWpzHiHsÿ = array( );
        foreach ( $_obfuscate_6RYLWQÿÿ['steps'] as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_0Ul8BBkt = $_obfuscate_6Aÿÿ['base']['stepId'];
            $_obfuscate_SnVDWpzHiHsÿ[] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_6RYLWQÿÿ = array( );
            $_obfuscate_6RYLWQÿÿ['doBtnText'] = $_obfuscate_6Aÿÿ['base']['doBtnText'] == "" ? 1 : $_obfuscate_6Aÿÿ['base']['doBtnText'];
            $_obfuscate_6RYLWQÿÿ['allowReject'] = $_obfuscate_6Aÿÿ['base']['allowReject'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowHuiqian'] = $_obfuscate_6Aÿÿ['base']['allowHuiqian'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowFenfa'] = $_obfuscate_6Aÿÿ['base']['allowFenfa'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowTuihui'] = $_obfuscate_6Aÿÿ['base']['allowTuihui'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowPrint'] = $_obfuscate_6Aÿÿ['base']['allowPrint'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowCallback'] = $_obfuscate_6Aÿÿ['base']['allowCallback'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowCancel'] = $_obfuscate_6Aÿÿ['base']['allowCancel'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowYijian'] = $_obfuscate_6Aÿÿ['base']['allowYijian'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowAttachAdd'] = $_obfuscate_6Aÿÿ['base']['allowAttachAdd'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowAttachView'] = $_obfuscate_6Aÿÿ['base']['allowAttachView'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowAttachEdit'] = $_obfuscate_6Aÿÿ['base']['allowAttachEdit'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowAttachDelete'] = $_obfuscate_6Aÿÿ['base']['allowAttachDelete'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowAttachDown'] = $_obfuscate_6Aÿÿ['base']['allowAttachDown'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowHqAttachAdd'] = $_obfuscate_6Aÿÿ['base']['allowHqAttachAdd'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowHqAttachView'] = $_obfuscate_6Aÿÿ['base']['allowHqAttachView'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowHqAttachEdit'] = $_obfuscate_6Aÿÿ['base']['allowHqAttachEdit'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowHqAttachDelete'] = $_obfuscate_6Aÿÿ['base']['allowHqAttachDelete'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowHqAttachDown'] = $_obfuscate_6Aÿÿ['base']['allowHqAttachDown'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowWordEdit'] = $_obfuscate_6Aÿÿ['base']['allowWordEdit'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowAttachWordEdit'] = $_obfuscate_6Aÿÿ['base']['allowAttachWordEdit'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['allowSms'] = $_obfuscate_6Aÿÿ['base']['allowSms'] == "on" ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['stepTime'] = floatval( $_obfuscate_6Aÿÿ['base']['stepTime'] );
            $_obfuscate_6RYLWQÿÿ['urgeBefore'] = intval( $_obfuscate_6Aÿÿ['base']['urgeBefore'] );
            $_obfuscate_6RYLWQÿÿ['urgeTarget'] = empty( $_obfuscate_6Aÿÿ['base']['urgeTarget'] ) ? 2 : intval( $_obfuscate_6Aÿÿ['base']['urgeTarget'] );
            $_obfuscate_6RYLWQÿÿ['bingnames'] = $_obfuscate_6Aÿÿ['base']['bingnames'];
            $_obfuscate_6RYLWQÿÿ['bingids'] = $_obfuscate_6Aÿÿ['base']['bingids'];
            $_obfuscate_6RYLWQÿÿ['faqiFlow'] = $_obfuscate_6Aÿÿ['base']['faqiFlow'];
            $_obfuscate_6RYLWQÿÿ['endFlow'] = $_obfuscate_6Aÿÿ['base']['endFlow'];
            $_obfuscate_6RYLWQÿÿ['sharefile'] = $_obfuscate_6Aÿÿ['base']['sharefile'] == "on" ? 1 : 0;
            $CNOA_DB->db_update( $_obfuscate_6RYLWQÿÿ, $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `stepId`='{$_obfuscate_0Ul8BBkt}'" );
            $this->_filterPermit( $_obfuscate_6Aÿÿ['base'], $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt );
            if ( $_obfuscate_6Aÿÿ['base']['stepId'] != 2 && $_obfuscate_6Aÿÿ['base']['stepId'] != 3 )
            {
                $this->_fillAutoFenFaUsers( $_obfuscate_6RYLWQÿÿ['allowFenfa'], $_obfuscate_F4AbnVRh, $_obfuscate_6Aÿÿ['base']['stepId'], $_obfuscate_6Aÿÿ['base']['fenfaPermit']['autoFenfa'] );
            }
            if ( !empty( $_obfuscate_6Aÿÿ['fields'] ) )
            {
                $this->_filterFields( $_obfuscate_6Aÿÿ['fields'], $_obfuscate_6Aÿÿ['base']['stepId'], $_obfuscate_F4AbnVRh );
            }
            $this->_filterUser( $_obfuscate_6Aÿÿ['user'], $_obfuscate_6Aÿÿ['base']['stepId'], $_obfuscate_F4AbnVRh, $_obfuscate_F1iF5t0j['startStepId'] );
            if ( !empty( $_obfuscate_6Aÿÿ['condition'] ) )
            {
                $this->_filterCondition( $_obfuscate_6Aÿÿ['condition'], $_obfuscate_6Aÿÿ['base']['stepId'], $_obfuscate_F4AbnVRh );
            }
            if ( isset( $_obfuscate_6Aÿÿ['convergence'] ) )
            {
                $this->_filterConvergence( $_obfuscate_6Aÿÿ['convergence'], $_obfuscate_6Aÿÿ['base']['stepId'], $_obfuscate_F4AbnVRh );
            }
            if ( isset( $_obfuscate_6Aÿÿ['child'] ) )
            {
                $this->_filterChild( $_obfuscate_6Aÿÿ['child'], $_obfuscate_F4AbnVRh, $_obfuscate_6Aÿÿ['base']['stepId'] );
            }
            if ( isset( $_obfuscate_6Aÿÿ['dealWay'] ) )
            {
                $this->_filterDeal( $_obfuscate_6Aÿÿ['dealWay'], $_obfuscate_F4AbnVRh, $_obfuscate_6Aÿÿ['base']['stepId'] );
            }
            if ( isset( $_obfuscate_6Aÿÿ['childPermit'] ) )
            {
                $this->_filterChildPermit( $_obfuscate_6Aÿÿ['childPermit'], $_obfuscate_F4AbnVRh );
            }
        }
        foreach ( $_obfuscate_S0PSA37yAwÿÿ[0] as $_obfuscate_6Aÿÿ )
        {
            if ( !in_array( $_obfuscate_6Aÿÿ, $_obfuscate_SnVDWpzHiHsÿ ) )
            {
                $this->_filterUser( NULL, $_obfuscate_6Aÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_F1iF5t0j['startStepId'] );
            }
        }
        $_obfuscate_neM4JBUJlmgÿ = $CNOA_DB->db_getfield( "name", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 3662, $_obfuscate_neM4JBUJlmgÿ, "æµç¨‹æ­¥éª¤" );
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
        foreach ( $_obfuscate_tjILu7ZH as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6RYLWQÿÿ = array( );
            $_obfuscate_6RYLWQÿÿ['stepId'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_6RYLWQÿÿ['flowId'] = $_obfuscate_F4AbnVRh;
            if ( $_obfuscate_6Aÿÿ['id'][0] == "d" )
            {
                $_obfuscate_6RYLWQÿÿ['from'] = 1;
                $_obfuscate_6RYLWQÿÿ['fieldId'] = substr( $_obfuscate_6Aÿÿ['id'], 2 );
            }
            else
            {
                $_obfuscate_6RYLWQÿÿ['from'] = 0;
                $_obfuscate_6RYLWQÿÿ['fieldId'] = $_obfuscate_6Aÿÿ['id'];
            }
            $_obfuscate_6RYLWQÿÿ['show'] = $_obfuscate_6Aÿÿ['show'];
            $_obfuscate_6RYLWQÿÿ['hide'] = $_obfuscate_6Aÿÿ['hide'];
            $_obfuscate_6RYLWQÿÿ['write'] = $_obfuscate_6Aÿÿ['write'];
            $_obfuscate_6RYLWQÿÿ['must'] = $_obfuscate_6Aÿÿ['must'];
            if ( $_obfuscate_6Aÿÿ['otype'] == "calculate" && $_obfuscate_6Aÿÿ['show'] == 1 )
            {
                $_obfuscate_6RYLWQÿÿ['write'] = 1;
            }
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_set_step_fields );
        }
    }

    private function _filterChildPermit( $_obfuscate_tjILu7ZH, $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        if ( !is_array( $_obfuscate_tjILu7ZH ) )
        {
            $_obfuscate_tjILu7ZH = array( );
        }
        foreach ( $_obfuscate_tjILu7ZH as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6RYLWQÿÿ = array( );
            if ( $_obfuscate_6Aÿÿ['id'][0] == "d" )
            {
                $_obfuscate_8jhldA9Y9Aÿÿ = substr( $_obfuscate_6Aÿÿ['id'], 2 );
            }
            else
            {
                $_obfuscate_8jhldA9Y9Aÿÿ = $_obfuscate_6Aÿÿ['id'];
            }
            $_obfuscate_gftfagwÿ = $CNOA_DB->db_getcount( $this->t_set_step_fields, "WHERE `stepId`=".$_obfuscate_6Aÿÿ['stepId']." AND `flowId`={$_obfuscate_F4AbnVRh}" );
            $_obfuscate_6RYLWQÿÿ['status'] = $_obfuscate_6Aÿÿ['status'];
            $CNOA_DB->db_update( $_obfuscate_6RYLWQÿÿ, $this->t_set_step_fields, "WHERE `stepId`=".$_obfuscate_6Aÿÿ['stepId']." AND `flowId`={$_obfuscate_F4AbnVRh} AND `fieldId`={$_obfuscate_8jhldA9Y9Aÿÿ}" );
        }
    }

    private function _filterUser( $_obfuscate_m2Kuwwÿÿ, $_obfuscate_0Ul8BBkt, $_obfuscate_F4AbnVRh, $_obfuscate_vpZO7cBY1GZYtnUÿ )
    {
        global $CNOA_DB;
        $CNOA_DB->db_delete( $this->t_set_step_user, "WHERE `stepId`='".$_obfuscate_0Ul8BBkt."' AND `flowId`='{$_obfuscate_F4AbnVRh}' " );
        if ( empty( $_obfuscate_m2Kuwwÿÿ ) || !isset( $_obfuscate_m2Kuwwÿÿ ) )
        {
            $_obfuscate_6RYLWQÿÿ = array( );
            $_obfuscate_6RYLWQÿÿ['stepId'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_6RYLWQÿÿ['firstStep'] = $_obfuscate_0Ul8BBkt == $_obfuscate_vpZO7cBY1GZYtnUÿ ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['flowId'] = $_obfuscate_F4AbnVRh;
            $_obfuscate_6RYLWQÿÿ['type'] = "";
            $_obfuscate_6RYLWQÿÿ['rule_p'] = "";
            $_obfuscate_6RYLWQÿÿ['rule_d'] = "";
            $_obfuscate_6RYLWQÿÿ['rule_s'] = "";
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_set_step_user );
        }
        else
        {
            $_obfuscate_6RYLWQÿÿ = array( );
            $_obfuscate_6RYLWQÿÿ['stepId'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_6RYLWQÿÿ['firstStep'] = $_obfuscate_0Ul8BBkt == $_obfuscate_vpZO7cBY1GZYtnUÿ ? 1 : 0;
            $_obfuscate_6RYLWQÿÿ['flowId'] = $_obfuscate_F4AbnVRh;
            $_obfuscate_6RYLWQÿÿ['type'] = "";
            $_obfuscate_6RYLWQÿÿ['rule_p'] = "";
            $_obfuscate_6RYLWQÿÿ['rule_d'] = "";
            $_obfuscate_6RYLWQÿÿ['rule_s'] = "";
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_set_step_user );
            if ( !is_array( $_obfuscate_m2Kuwwÿÿ['rule'] ) )
            {
                $_obfuscate_m2Kuwwÿÿ['rule'] = array( );
            }
            foreach ( $_obfuscate_m2Kuwwÿÿ['rule'] as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_6RYLWQÿÿ = array( );
                $_obfuscate_6RYLWQÿÿ['stepId'] = $_obfuscate_0Ul8BBkt;
                $_obfuscate_6RYLWQÿÿ['firstStep'] = $_obfuscate_0Ul8BBkt == $_obfuscate_vpZO7cBY1GZYtnUÿ ? 1 : 0;
                $_obfuscate_6RYLWQÿÿ['flowId'] = $_obfuscate_F4AbnVRh;
                $_obfuscate_6RYLWQÿÿ['type'] = "rule";
                $_obfuscate_6RYLWQÿÿ['rule_p'] = $_obfuscate_6Aÿÿ['people'];
                $_obfuscate_6RYLWQÿÿ['rule_d'] = $_obfuscate_6Aÿÿ['dept'];
                $_obfuscate_6RYLWQÿÿ['rule_s'] = $_obfuscate_6Aÿÿ['id'];
                $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_set_step_user );
            }
            if ( !is_array( $_obfuscate_m2Kuwwÿÿ['people'] ) )
            {
                $_obfuscate_m2Kuwwÿÿ['people'] = array( );
            }
            foreach ( $_obfuscate_m2Kuwwÿÿ['people'] as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_6RYLWQÿÿ = array( );
                $_obfuscate_6RYLWQÿÿ['rule_p'] = "";
                $_obfuscate_6RYLWQÿÿ['rule_d'] = "";
                $_obfuscate_6RYLWQÿÿ['stepId'] = $_obfuscate_0Ul8BBkt;
                $_obfuscate_6RYLWQÿÿ['firstStep'] = $_obfuscate_0Ul8BBkt == $_obfuscate_vpZO7cBY1GZYtnUÿ ? 1 : 0;
                $_obfuscate_6RYLWQÿÿ['flowId'] = $_obfuscate_F4AbnVRh;
                $_obfuscate_6RYLWQÿÿ['type'] = "people";
                $_obfuscate_6RYLWQÿÿ['people'] = $_obfuscate_6Aÿÿ['id'];
                $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_set_step_user );
            }
            if ( !is_array( $_obfuscate_m2Kuwwÿÿ['exclude'] ) )
            {
                $_obfuscate_m2Kuwwÿÿ['exclude'] = array( );
            }
            foreach ( $_obfuscate_m2Kuwwÿÿ['exclude'] as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_6RYLWQÿÿ = array( );
                $_obfuscate_6RYLWQÿÿ['rule_p'] = "";
                $_obfuscate_6RYLWQÿÿ['rule_d'] = "";
                $_obfuscate_6RYLWQÿÿ['stepId'] = $_obfuscate_0Ul8BBkt;
                $_obfuscate_6RYLWQÿÿ['firstStep'] = $_obfuscate_0Ul8BBkt == $_obfuscate_vpZO7cBY1GZYtnUÿ ? 1 : 0;
                $_obfuscate_6RYLWQÿÿ['flowId'] = $_obfuscate_F4AbnVRh;
                $_obfuscate_6RYLWQÿÿ['type'] = "exclude";
                $_obfuscate_6RYLWQÿÿ['exclude'] = $_obfuscate_6Aÿÿ['id'];
                $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_set_step_user );
            }
            if ( !is_array( $_obfuscate_m2Kuwwÿÿ['kong'] ) )
            {
                $_obfuscate_m2Kuwwÿÿ['kong'] = array( );
            }
            foreach ( $_obfuscate_m2Kuwwÿÿ['kong'] as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_6RYLWQÿÿ = array( );
                $_obfuscate_6RYLWQÿÿ['rule_p'] = "";
                $_obfuscate_6RYLWQÿÿ['rule_d'] = "";
                $_obfuscate_6RYLWQÿÿ['stepId'] = $_obfuscate_0Ul8BBkt;
                $_obfuscate_6RYLWQÿÿ['firstStep'] = $_obfuscate_0Ul8BBkt == $_obfuscate_vpZO7cBY1GZYtnUÿ ? 1 : 0;
                $_obfuscate_6RYLWQÿÿ['flowId'] = $_obfuscate_F4AbnVRh;
                $_obfuscate_6RYLWQÿÿ['type'] = "kong";
                $_obfuscate_6RYLWQÿÿ['kong'] = $_obfuscate_6Aÿÿ['id'];
                $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_set_step_user );
            }
            if ( !is_array( $_obfuscate_m2Kuwwÿÿ['dept'] ) )
            {
                $_obfuscate_m2Kuwwÿÿ['dept'] = array( );
            }
            foreach ( $_obfuscate_m2Kuwwÿÿ['dept'] as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_6RYLWQÿÿ = array( );
                $_obfuscate_6RYLWQÿÿ['rule_p'] = "";
                $_obfuscate_6RYLWQÿÿ['rule_d'] = "";
                $_obfuscate_6RYLWQÿÿ['stepId'] = $_obfuscate_0Ul8BBkt;
                $_obfuscate_6RYLWQÿÿ['firstStep'] = $_obfuscate_0Ul8BBkt == $_obfuscate_vpZO7cBY1GZYtnUÿ ? 1 : 0;
                $_obfuscate_6RYLWQÿÿ['flowId'] = $_obfuscate_F4AbnVRh;
                $_obfuscate_6RYLWQÿÿ['type'] = "dept";
                $_obfuscate_6RYLWQÿÿ['dept'] = $_obfuscate_6Aÿÿ['id'];
                $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_set_step_user );
            }
            if ( !is_array( $_obfuscate_m2Kuwwÿÿ['station'] ) )
            {
                $_obfuscate_m2Kuwwÿÿ['station'] = array( );
            }
            foreach ( $_obfuscate_m2Kuwwÿÿ['station'] as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_6RYLWQÿÿ = array( );
                $_obfuscate_6RYLWQÿÿ['rule_p'] = "";
                $_obfuscate_6RYLWQÿÿ['rule_d'] = "";
                $_obfuscate_6RYLWQÿÿ['stepId'] = $_obfuscate_0Ul8BBkt;
                $_obfuscate_6RYLWQÿÿ['firstStep'] = $_obfuscate_0Ul8BBkt == $_obfuscate_vpZO7cBY1GZYtnUÿ ? 1 : 0;
                $_obfuscate_6RYLWQÿÿ['flowId'] = $_obfuscate_F4AbnVRh;
                $_obfuscate_6RYLWQÿÿ['type'] = "station";
                $_obfuscate_6RYLWQÿÿ['station'] = $_obfuscate_6Aÿÿ['id'];
                $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_set_step_user );
            }
            if ( !is_array( $_obfuscate_m2Kuwwÿÿ['deptStation'] ) )
            {
                $_obfuscate_m2Kuwwÿÿ['deptStation'] = array( );
            }
            foreach ( $_obfuscate_m2Kuwwÿÿ['deptStation'] as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_O6QLLacÿ = explode( ",", $_obfuscate_6Aÿÿ['id'] );
                $_obfuscate_6RYLWQÿÿ = array( );
                $_obfuscate_6RYLWQÿÿ['rule_p'] = "";
                $_obfuscate_6RYLWQÿÿ['rule_d'] = "";
                $_obfuscate_6RYLWQÿÿ['stepId'] = $_obfuscate_0Ul8BBkt;
                $_obfuscate_6RYLWQÿÿ['firstStep'] = $_obfuscate_0Ul8BBkt == $_obfuscate_vpZO7cBY1GZYtnUÿ ? 1 : 0;
                $_obfuscate_6RYLWQÿÿ['flowId'] = $_obfuscate_F4AbnVRh;
                $_obfuscate_6RYLWQÿÿ['type'] = "deptstation";
                $_obfuscate_6RYLWQÿÿ['dept'] = $_obfuscate_O6QLLacÿ[0];
                $_obfuscate_6RYLWQÿÿ['station'] = $_obfuscate_O6QLLacÿ[1];
                $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_set_step_user );
            }
            if ( 0 < count( $_obfuscate_m2Kuwwÿÿ['deptStation'] ) || 0 < count( $_obfuscate_m2Kuwwÿÿ['station'] ) || 0 < count( $_obfuscate_m2Kuwwÿÿ['dept'] ) || 0 < count( $_obfuscate_m2Kuwwÿÿ['people'] ) || 0 < count( $_obfuscate_m2Kuwwÿÿ['exclude'] ) || 0 < count( $_obfuscate_m2Kuwwÿÿ['rule'] ) )
            {
                $CNOA_DB->db_delete( $this->t_set_step_user, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `stepId`='{$_obfuscate_0Ul8BBkt}' AND `type`=''" );
            }
        }
    }

    private function _filterCondition( $_obfuscate_XP4WpjIMhOSD, $_obfuscate_0Ul8BBkt, $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        $CNOA_DB->db_delete( $this->t_set_step_condition, "WHERE `stepId`='".$_obfuscate_0Ul8BBkt."' AND `flowId`='{$_obfuscate_F4AbnVRh}' " );
        $_obfuscate_6RYLWQÿÿ['stepId'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_6RYLWQÿÿ['flowId'] = $_obfuscate_F4AbnVRh;
        if ( !is_array( $_obfuscate_XP4WpjIMhOSD ) )
        {
            $_obfuscate_XP4WpjIMhOSD = array( );
        }
        foreach ( $_obfuscate_XP4WpjIMhOSD as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6RYLWQÿÿ['nextStepId'] = $_obfuscate_6Aÿÿ['id'];
            foreach ( $_obfuscate_6Aÿÿ['items'] as $_obfuscate_bRQÿ )
            {
                $_obfuscate_6RYLWQÿÿ['text'] = addslashes( $_obfuscate_bRQÿ['text'] );
                if ( count( $_obfuscate_bRQÿ['items'] ) <= 1 )
                {
                    foreach ( $_obfuscate_bRQÿ['items'] as $_obfuscate_NZMÿ )
                    {
                        if ( in_array( $_obfuscate_NZMÿ['name'], array( "s|n_n", "s|n_s", "s|n_d", "s|d_n", "s|d_s", "s|d_d" ) ) )
                        {
                            $_obfuscate_6RYLWQÿÿ['fieldType'] = str_replace( "s|", "", $_obfuscate_NZMÿ['name'] );
                            $_obfuscate_6RYLWQÿÿ['name'] = 0;
                        }
                        else
                        {
                            $_obfuscate_6RYLWQÿÿ['fieldType'] = "nor";
                            $_obfuscate_6RYLWQÿÿ['name'] = $_obfuscate_NZMÿ['name'];
                        }
                        $_obfuscate_6RYLWQÿÿ['rule'] = $_obfuscate_NZMÿ['rule'];
                        $_obfuscate_6RYLWQÿÿ['ovalue'] = $_obfuscate_NZMÿ['ovalue'];
                        $_obfuscate_6RYLWQÿÿ['orAnd'] = empty( $_obfuscate_bRQÿ['orAnd'] ) ? $_obfuscate_NZMÿ['orAnd'] : $_obfuscate_bRQÿ['orAnd'];
                        $_obfuscate_6RYLWQÿÿ['pid'] = 0;
                        $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_set_step_condition );
                    }
                }
                else
                {
                    $_obfuscate_jYGVpwÿÿ = TRUE;
                    $_obfuscate_fdpE = 0;
                    foreach ( $_obfuscate_bRQÿ['items'] as $_obfuscate_5wÿÿ => $_obfuscate_NZMÿ )
                    {
                        if ( in_array( $_obfuscate_NZMÿ['name'], array( "s|n_n", "s|n_s", "s|n_d", "s|d_n", "s|d_s", "s|d_d" ) ) )
                        {
                            $_obfuscate_6RYLWQÿÿ['fieldType'] = str_replace( "s|", "", $_obfuscate_NZMÿ['name'] );
                            $_obfuscate_6RYLWQÿÿ['name'] = 0;
                        }
                        else
                        {
                            $_obfuscate_6RYLWQÿÿ['fieldType'] = "nor";
                            $_obfuscate_6RYLWQÿÿ['name'] = $_obfuscate_NZMÿ['name'];
                        }
                        $_obfuscate_6RYLWQÿÿ['rule'] = $_obfuscate_NZMÿ['rule'];
                        $_obfuscate_6RYLWQÿÿ['ovalue'] = $_obfuscate_NZMÿ['ovalue'];
                        $_obfuscate_6RYLWQÿÿ['orAnd'] = $_obfuscate_5wÿÿ == 0 ? $_obfuscate_bRQÿ['orAnd'] : $_obfuscate_NZMÿ['orAnd'];
                        if ( $_obfuscate_jYGVpwÿÿ )
                        {
                            $_obfuscate_6RYLWQÿÿ['head'] = 1;
                            $_obfuscate_6RYLWQÿÿ['pid'] = 0;
                            $_obfuscate_fdpE = $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_set_step_condition );
                            $_obfuscate_jYGVpwÿÿ = FALSE;
                        }
                        else
                        {
                            $_obfuscate_6RYLWQÿÿ['head'] = 0;
                            $_obfuscate_6RYLWQÿÿ['pid'] = $_obfuscate_fdpE;
                            $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_set_step_condition );
                        }
                    }
                }
            }
        }
    }

    private function _filterPermit( $_obfuscate_O1dluwÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $_obfuscate_jXRqIoPJxOTkM9U_GQÿÿ = $_obfuscate_O1dluwÿÿ['huiqianPermit'];
        $_obfuscate_kou4P057dsY2Dzsÿ = $_obfuscate_O1dluwÿÿ['fenfaPermit'];
        if ( $_obfuscate_O1dluwÿÿ['allowHuiqian'] && 0 < count( $_obfuscate_jXRqIoPJxOTkM9U_GQÿÿ ) )
        {
            $_obfuscate_6RYLWQÿÿ = $this->_savePermit( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt, self::$OPERATE_TYPE_HUIQIAN, $_obfuscate_jXRqIoPJxOTkM9U_GQÿÿ );
        }
        else
        {
            $CNOA_DB->db_delete( $this->t_set_step_permit, "WHERE flowId=".$_obfuscate_F4AbnVRh." AND stepId={$_obfuscate_0Ul8BBkt} AND operate=".self::$OPERATE_TYPE_HUIQIAN );
        }
        if ( $_obfuscate_O1dluwÿÿ['allowFenfa'] && 0 < count( $_obfuscate_kou4P057dsY2Dzsÿ ) )
        {
            $_obfuscate_6RYLWQÿÿ = $this->_savePermit( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt, self::$OPERATE_TYPE_FENFA, $_obfuscate_kou4P057dsY2Dzsÿ );
        }
        else
        {
            $CNOA_DB->db_delete( $this->t_set_step_permit, "WHERE flowId=".$_obfuscate_F4AbnVRh." AND stepId={$_obfuscate_0Ul8BBkt} AND operate=".self::$OPERATE_TYPE_FENFA );
        }
    }

    private function _savePermit( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt, $_obfuscate_bBdOnB4sqAÿÿ, $_obfuscate_eVTMIa1A )
    {
        global $CNOA_DB;
        $_obfuscate_m2Kuwwÿÿ = $_obfuscate_vwGQSAÿÿ = $_obfuscate_6mlyHgÿÿ = array( );
        if ( is_array( $_obfuscate_eVTMIa1A['user'] ) )
        {
            foreach ( $_obfuscate_eVTMIa1A['user'] as $_obfuscate_cBms )
            {
                $_obfuscate_m2Kuwwÿÿ[] = $_obfuscate_cBms[0];
            }
        }
        if ( is_array( $_obfuscate_eVTMIa1A['dept'] ) )
        {
            foreach ( $_obfuscate_eVTMIa1A['dept'] as $_obfuscate_cBms )
            {
                $_obfuscate_vwGQSAÿÿ[] = $_obfuscate_cBms[0];
            }
        }
        if ( is_array( $_obfuscate_eVTMIa1A['rule'] ) )
        {
            foreach ( $_obfuscate_eVTMIa1A['rule'] as $_obfuscate_cBms )
            {
                $_obfuscate_6mlyHgÿÿ[] = $_obfuscate_cBms[0];
            }
        }
        $_obfuscate_6RYLWQÿÿ = array( );
        if ( !empty( $_obfuscate_m2Kuwwÿÿ ) )
        {
            $_obfuscate_6RYLWQÿÿ['user'] = implode( ",", $_obfuscate_m2Kuwwÿÿ );
        }
        if ( !empty( $_obfuscate_vwGQSAÿÿ ) )
        {
            $_obfuscate_6RYLWQÿÿ['dept'] = implode( ",", $_obfuscate_vwGQSAÿÿ );
        }
        if ( !empty( $_obfuscate_6mlyHgÿÿ ) )
        {
            $_obfuscate_6RYLWQÿÿ['rule'] = implode( ",", $_obfuscate_6mlyHgÿÿ );
        }
        if ( empty( $_obfuscate_6RYLWQÿÿ ) )
        {
            $CNOA_DB->db_delete( $this->t_set_step_permit, "WHERE flowId=".$_obfuscate_F4AbnVRh." AND stepId={$_obfuscate_0Ul8BBkt} AND operate={$_obfuscate_bBdOnB4sqAÿÿ}" );
        }
        else
        {
            $_obfuscate_6RYLWQÿÿ['flowId'] = $_obfuscate_F4AbnVRh;
            $_obfuscate_6RYLWQÿÿ['stepId'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_6RYLWQÿÿ['operate'] = $_obfuscate_bBdOnB4sqAÿÿ;
            $CNOA_DB->db_replace( $_obfuscate_6RYLWQÿÿ, $this->t_set_step_permit );
        }
    }

    private function _loadFlowDesignData( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $_obfuscate_EdcUyMWd6ZEv = $CNOA_DB->db_select( "*", $this->t_set_field, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' ORDER BY `id` ASC" );
        if ( !is_array( $_obfuscate_EdcUyMWd6ZEv ) )
        {
            $_obfuscate_EdcUyMWd6ZEv = array( );
        }
        $_obfuscate_D5cvgOQDiG = array( );
        $_obfuscate_HAEJuyDtQpZD9IAÿ = array( );
        foreach ( $_obfuscate_EdcUyMWd6ZEv as $_obfuscate_WgEÿ )
        {
            $_obfuscate_p5ZWxr4ÿ = json_decode( str_replace( "'", "\"", $_obfuscate_WgEÿ['odata'] ), TRUE );
            $_obfuscate_juwe = array( );
            $_obfuscate_juwe['id'] = $_obfuscate_WgEÿ['id'];
            $_obfuscate_juwe['name'] = $_obfuscate_WgEÿ['name'];
            $_obfuscate_juwe['otype'] = $_obfuscate_WgEÿ['otype'];
            $_obfuscate_juwe['type'] = $_obfuscate_WgEÿ['type'];
            $_obfuscate_juwe['table'] = "";
            $_obfuscate_juwe['gname'] = "&nbsp;æ™®é€šè¡¨å•æ§ä»¶";
            $_obfuscate_juwe['tableid'] = 0;
            $_obfuscate_juwe['from'] = "normal";
            $_obfuscate_juwe['dataType'] = $_obfuscate_p5ZWxr4ÿ['dataType'];
            if ( $_obfuscate_juwe['otype'] == "detailtable" )
            {
                $_obfuscate_7Hp0w_lfFt4ÿ = $this->_loadDetailFieldsData( $_obfuscate_WgEÿ['id'] );
                foreach ( $_obfuscate_7Hp0w_lfFt4ÿ as $_obfuscate_6Aÿÿ )
                {
                    $_obfuscate_SeV31Qÿÿ['id'] = "d_".$_obfuscate_6Aÿÿ['id'];
                    $_obfuscate_SeV31Qÿÿ['tableid'] = $_obfuscate_juwe['id'];
                    $_obfuscate_SeV31Qÿÿ['table'] = $_obfuscate_juwe['name'];
                    $_obfuscate_SeV31Qÿÿ['gname'] = "&nbsp;æ˜ç»†è¡¨ï¼š".$_obfuscate_juwe['name'];
                    $_obfuscate_SeV31Qÿÿ['name'] = $_obfuscate_6Aÿÿ['name'];
                    $_obfuscate_SeV31Qÿÿ['otype'] = $_obfuscate_6Aÿÿ['type'];
                    $_obfuscate_SeV31Qÿÿ['type'] = "text";
                    $_obfuscate_SeV31Qÿÿ['dataType'] = $_obfuscate_6Aÿÿ['dataType'];
                    $_obfuscate_HAEJuyDtQpZD9IAÿ[] = $_obfuscate_SeV31Qÿÿ;
                }
            }
            $_obfuscate_D5cvgOQDiG[] = $_obfuscate_juwe;
        }
        foreach ( $_obfuscate_HAEJuyDtQpZD9IAÿ as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_D5cvgOQDiG[] = $_obfuscate_6Aÿÿ;
        }
        $_obfuscate_ktRUIU_2er7vxwÿÿ = $this->_loadPermit( $_obfuscate_F4AbnVRh );
        $_obfuscate_5NhzjnJq_f8ÿ = $CNOA_DB->db_select( "*", $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' ORDER BY `id` ASC" );
        if ( !is_array( $_obfuscate_5NhzjnJq_f8ÿ ) )
        {
            $_obfuscate_5NhzjnJq_f8ÿ = array( );
        }
        $_obfuscate_T1JGvNjMhVsÿ = $_obfuscate_mbrsRUqoKgÿÿ = array( );
        foreach ( $_obfuscate_5NhzjnJq_f8ÿ as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_MnVVbyZQFVwÿ = json_decode( $_obfuscate_6Aÿÿ['nextStep'] );
            if ( !empty( $_obfuscate_MnVVbyZQFVwÿ ) )
            {
                $_obfuscate_mbrsRUqoKgÿÿ[$_obfuscate_6Aÿÿ['stepId']] = $_obfuscate_MnVVbyZQFVwÿ;
            }
        }
        foreach ( $_obfuscate_5NhzjnJq_f8ÿ as $_obfuscate_B0Mÿ )
        {
            $_obfuscate_juwe = array( );
            $_obfuscate_juwe['stepId'] = $_obfuscate_B0Mÿ['stepId'];
            $_obfuscate_juwe['stepName'] = $_obfuscate_B0Mÿ['stepName'];
            $_obfuscate_juwe['allowReject'] = $_obfuscate_B0Mÿ['allowReject'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowHuiqian'] = $_obfuscate_B0Mÿ['allowHuiqian'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowFenfa'] = $_obfuscate_B0Mÿ['allowFenfa'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowTuihui'] = $_obfuscate_B0Mÿ['allowTuihui'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowPrint'] = $_obfuscate_B0Mÿ['allowPrint'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowCallback'] = $_obfuscate_B0Mÿ['allowCallback'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowCancel'] = $_obfuscate_B0Mÿ['allowCancel'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowYijian'] = $_obfuscate_B0Mÿ['allowYijian'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowAttachAdd'] = $_obfuscate_B0Mÿ['allowAttachAdd'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowAttachView'] = $_obfuscate_B0Mÿ['allowAttachView'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowAttachEdit'] = $_obfuscate_B0Mÿ['allowAttachEdit'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowAttachDelete'] = $_obfuscate_B0Mÿ['allowAttachDelete'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowAttachDown'] = $_obfuscate_B0Mÿ['allowAttachDown'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowWordEdit'] = $_obfuscate_B0Mÿ['allowWordEdit'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowAttachWordEdit'] = $_obfuscate_B0Mÿ['allowAttachWordEdit'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowHqAttachAdd'] = $_obfuscate_B0Mÿ['allowHqAttachAdd'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowHqAttachView'] = $_obfuscate_B0Mÿ['allowHqAttachView'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowHqAttachEdit'] = $_obfuscate_B0Mÿ['allowHqAttachEdit'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowHqAttachDelete'] = $_obfuscate_B0Mÿ['allowHqAttachDelete'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowHqAttachDown'] = $_obfuscate_B0Mÿ['allowHqAttachDown'] == 1 ? "on" : "";
            $_obfuscate_juwe['allowSms'] = $_obfuscate_B0Mÿ['allowSms'] == 1 ? "on" : "";
            $_obfuscate_juwe['doBtnText'] = $_obfuscate_B0Mÿ['doBtnText'];
            $_obfuscate_juwe['stepTime'] = $_obfuscate_B0Mÿ['stepTime'];
            $_obfuscate_juwe['urgeBefore'] = $_obfuscate_B0Mÿ['urgeBefore'];
            $_obfuscate_juwe['urgeTarget'] = $_obfuscate_B0Mÿ['urgeTarget'] == 0 ? "" : $_obfuscate_B0Mÿ['urgeTarget'];
            $_obfuscate_juwe['fields'] = $this->_loadFieldsData( $_obfuscate_F4AbnVRh, $_obfuscate_B0Mÿ['stepId'] );
            $_obfuscate_juwe['user'] = $this->_loadUserData( $_obfuscate_F4AbnVRh, $_obfuscate_B0Mÿ['stepId'] );
            $_obfuscate_juwe['condition'] = $this->_loadConditionData( $_obfuscate_F4AbnVRh, $_obfuscate_B0Mÿ['stepId'] );
            $_obfuscate_juwe['dealWay'] = $this->_loadDealWay( $_obfuscate_F4AbnVRh, $_obfuscate_B0Mÿ['stepId'] );
            if ( !empty( $_obfuscate_B0Mÿ['bingids'] ) )
            {
                foreach ( $_obfuscate_mbrsRUqoKgÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_jVOC2dbrYwÿÿ )
                {
                    if ( in_array( $_obfuscate_B0Mÿ['stepId'], $_obfuscate_jVOC2dbrYwÿÿ ) )
                    {
                        $_obfuscate_juwe['childPermit'] = $this->_loadChildPermit( $_obfuscate_F4AbnVRh, $_obfuscate_5wÿÿ );
                    }
                }
            }
            $_obfuscate_juwe['bingnames'] = $_obfuscate_B0Mÿ['bingnames'];
            $_obfuscate_juwe['bingids'] = $_obfuscate_B0Mÿ['bingids'];
            $_obfuscate_juwe['faqiFlow'] = $_obfuscate_B0Mÿ['faqiFlow'];
            $_obfuscate_juwe['endFlow'] = $_obfuscate_B0Mÿ['endFlow'];
            $_obfuscate_juwe['sharefile'] = $_obfuscate_B0Mÿ['sharefile'] == 1 ? "on" : "";
            $_obfuscate_juwe['child'] = $this->_loadChildData( $_obfuscate_F4AbnVRh, $_obfuscate_B0Mÿ['stepId'] );
            $_obfuscate_juwe['huiqianPermit'] = $_obfuscate_ktRUIU_2er7vxwÿÿ[$_obfuscate_B0Mÿ['stepId']]['huiqian'];
            $_obfuscate_juwe['fenfaPermit'] = $_obfuscate_ktRUIU_2er7vxwÿÿ[$_obfuscate_B0Mÿ['stepId']]['fenfa'];
            $_obfuscate_T1JGvNjMhVsÿ[] = $_obfuscate_juwe;
        }
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_6RYLWQÿÿ['flowXml'] = $_obfuscate_7qDAYo85aGAÿ['flowXml'];
        $_obfuscate_6RYLWQÿÿ['fields'] = $_obfuscate_D5cvgOQDiG;
        $_obfuscate_6RYLWQÿÿ['steps'] = $_obfuscate_T1JGvNjMhVsÿ;
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
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
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_O6QLLacÿ = array( 0 );
        $_obfuscate_QrenqRn2PzA = array( 0 );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['from'] == 1 )
            {
                $_obfuscate_QrenqRn2PzA[] = $_obfuscate_6Aÿÿ['fieldId'];
            }
            else
            {
                $_obfuscate_O6QLLacÿ[] = $_obfuscate_6Aÿÿ['fieldId'];
            }
        }
        $_obfuscate_Pm3ZMWpPkgÿÿ = $CNOA_DB->db_select( array( "id", "name", "otype", "type" ), $this->t_set_field, "WHERE `id` IN (".implode( ",", $_obfuscate_O6QLLacÿ ).") " );
        if ( !is_array( $_obfuscate_Pm3ZMWpPkgÿÿ ) )
        {
            $_obfuscate_Pm3ZMWpPkgÿÿ = array( );
        }
        $_obfuscate_mLjk2t6lphUÿ = array( );
        foreach ( $_obfuscate_Pm3ZMWpPkgÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_mLjk2t6lphUÿ[$_obfuscate_6Aÿÿ['id']] = $_obfuscate_6Aÿÿ;
        }
        $_obfuscate_2Zj6 = $CNOA_DB->db_select( array( "id", "name", "type" ), $this->t_set_field_detail, "WHERE `id` IN (".implode( ",", $_obfuscate_QrenqRn2PzA ).") " );
        if ( !is_array( $_obfuscate_2Zj6 ) )
        {
            $_obfuscate_2Zj6 = array( );
        }
        $_obfuscate_rvwe5cLI9_YAQÿÿ = array( );
        foreach ( $_obfuscate_2Zj6 as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_rvwe5cLI9_YAQÿÿ[$_obfuscate_6Aÿÿ['id']] = $_obfuscate_6Aÿÿ;
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_SeV31Qÿÿ['write'] = $_obfuscate_6Aÿÿ['write'];
            $_obfuscate_SeV31Qÿÿ['must'] = $_obfuscate_6Aÿÿ['must'];
            $_obfuscate_SeV31Qÿÿ['hide'] = $_obfuscate_6Aÿÿ['hide'];
            $_obfuscate_SeV31Qÿÿ['show'] = $_obfuscate_6Aÿÿ['show'];
            $_obfuscate_SeV31Qÿÿ['status'] = $_obfuscate_6Aÿÿ['status'];
            if ( $_obfuscate_6Aÿÿ['from'] == 1 )
            {
                $_obfuscate_SeV31Qÿÿ['id'] = "d_".$_obfuscate_6Aÿÿ['fieldId'];
                $_obfuscate_SeV31Qÿÿ['name'] = $_obfuscate_rvwe5cLI9_YAQÿÿ[$_obfuscate_6Aÿÿ['fieldId']]['name'];
                $_obfuscate_SeV31Qÿÿ['otype'] = $_obfuscate_rvwe5cLI9_YAQÿÿ[$_obfuscate_6Aÿÿ['fieldId']]['type'];
                $_obfuscate_SeV31Qÿÿ['type'] = "text";
            }
            else
            {
                $_obfuscate_SeV31Qÿÿ['id'] = $_obfuscate_6Aÿÿ['fieldId'];
                $_obfuscate_SeV31Qÿÿ['name'] = $_obfuscate_mLjk2t6lphUÿ[$_obfuscate_6Aÿÿ['fieldId']]['name'];
                $_obfuscate_SeV31Qÿÿ['otype'] = $_obfuscate_mLjk2t6lphUÿ[$_obfuscate_6Aÿÿ['fieldId']]['otype'];
                $_obfuscate_SeV31Qÿÿ['type'] = $_obfuscate_mLjk2t6lphUÿ[$_obfuscate_6Aÿÿ['fieldId']]['type'];
            }
            $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_SeV31Qÿÿ;
        }
        foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
        {
            if ( $_obfuscate_VgKtFegÿ['otype'] == "calculate" )
            {
                $_obfuscate_6RYLWQÿÿ[$_obfuscate_Vwty]['write'] = 0;
            }
        }
        return $_obfuscate_6RYLWQÿÿ;
    }

    private function _loadChildPermit( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_step_fields, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_O6QLLacÿ = array( 0 );
        $_obfuscate_QrenqRn2PzA = array( 0 );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['from'] == 1 )
            {
                $_obfuscate_QrenqRn2PzA[] = $_obfuscate_6Aÿÿ['fieldId'];
            }
            else
            {
                $_obfuscate_O6QLLacÿ[] = $_obfuscate_6Aÿÿ['fieldId'];
            }
        }
        $_obfuscate_Pm3ZMWpPkgÿÿ = $CNOA_DB->db_select( array( "id", "name", "otype", "type" ), $this->t_set_field, "WHERE `id` IN (".implode( ",", $_obfuscate_O6QLLacÿ ).") " );
        if ( !is_array( $_obfuscate_Pm3ZMWpPkgÿÿ ) )
        {
            $_obfuscate_Pm3ZMWpPkgÿÿ = array( );
        }
        $_obfuscate_mLjk2t6lphUÿ = array( );
        foreach ( $_obfuscate_Pm3ZMWpPkgÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_mLjk2t6lphUÿ[$_obfuscate_6Aÿÿ['id']] = $_obfuscate_6Aÿÿ;
        }
        $_obfuscate_2Zj6 = $CNOA_DB->db_select( array( "id", "name", "type" ), $this->t_set_field_detail, "WHERE `id` IN (".implode( ",", $_obfuscate_QrenqRn2PzA ).") " );
        if ( !is_array( $_obfuscate_2Zj6 ) )
        {
            $_obfuscate_2Zj6 = array( );
        }
        $_obfuscate_rvwe5cLI9_YAQÿÿ = array( );
        foreach ( $_obfuscate_2Zj6 as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_rvwe5cLI9_YAQÿÿ[$_obfuscate_6Aÿÿ['id']] = $_obfuscate_6Aÿÿ;
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_SeV31Qÿÿ['status'] = $_obfuscate_6Aÿÿ['status'];
            $_obfuscate_SeV31Qÿÿ['stepId'] = $_obfuscate_0Ul8BBkt;
            if ( $_obfuscate_6Aÿÿ['from'] == 1 )
            {
                $_obfuscate_SeV31Qÿÿ['id'] = "d_".$_obfuscate_6Aÿÿ['fieldId'];
                $_obfuscate_SeV31Qÿÿ['name'] = $_obfuscate_rvwe5cLI9_YAQÿÿ[$_obfuscate_6Aÿÿ['fieldId']]['name'];
                $_obfuscate_SeV31Qÿÿ['otype'] = $_obfuscate_rvwe5cLI9_YAQÿÿ[$_obfuscate_6Aÿÿ['fieldId']]['type'];
                $_obfuscate_SeV31Qÿÿ['type'] = "text";
            }
            else
            {
                $_obfuscate_SeV31Qÿÿ['id'] = $_obfuscate_6Aÿÿ['fieldId'];
                $_obfuscate_SeV31Qÿÿ['name'] = $_obfuscate_mLjk2t6lphUÿ[$_obfuscate_6Aÿÿ['fieldId']]['name'];
                $_obfuscate_SeV31Qÿÿ['otype'] = $_obfuscate_mLjk2t6lphUÿ[$_obfuscate_6Aÿÿ['fieldId']]['otype'];
                $_obfuscate_SeV31Qÿÿ['type'] = $_obfuscate_mLjk2t6lphUÿ[$_obfuscate_6Aÿÿ['fieldId']]['type'];
            }
            $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_SeV31Qÿÿ;
        }
        return $_obfuscate_6RYLWQÿÿ;
    }

    private function _loadDetailFieldsData( $_obfuscate_0W8ÿ )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "dataType", "fid", "id", "name", "type" ), $this->t_set_field_detail, "WHERE `fid` = '".$_obfuscate_0W8ÿ."' ORDER BY `id` ASC " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
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
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_vwGQSAÿÿ = array( );
        $_obfuscate_YsVdvv0c = array( );
        $_obfuscate_fdSO3eSyAQÿÿ = array( );
        $_obfuscate_6mlyHgÿÿ = array( );
        $_obfuscate_m5leXC9_Zgÿÿ = array( );
        $_obfuscate_ZxVMfNbI5ugv4Ksÿ = array( );
        $_obfuscate_flKx1gÿÿ = array( );
        $_obfuscate_Lw9wXKzqBgÿÿ = array( 0 );
        $_obfuscate_MtpzvDgUD7YblQÿÿ = array( 0 );
        $_obfuscate__ooZFvTbHAÿÿ = array( 0 );
        $_obfuscate_HmJYx_HCewÿÿ = array( 0 );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['type'] == "dept" )
            {
                $_obfuscate_Lw9wXKzqBgÿÿ[] = $_obfuscate_6Aÿÿ['dept'];
            }
            else if ( $_obfuscate_6Aÿÿ['type'] == "people" )
            {
                $_obfuscate__ooZFvTbHAÿÿ[] = $_obfuscate_6Aÿÿ['people'];
            }
            else if ( $_obfuscate_6Aÿÿ['type'] == "exclude" )
            {
                $_obfuscate_JF89pTCN4WiNCgÿÿ[] = $_obfuscate_6Aÿÿ['exclude'];
            }
            else if ( $_obfuscate_6Aÿÿ['type'] == "station" )
            {
                $_obfuscate_MtpzvDgUD7YblQÿÿ[] = $_obfuscate_6Aÿÿ['station'];
            }
            else if ( $_obfuscate_6Aÿÿ['type'] == "rule" )
            {
                $_obfuscate_MtpzvDgUD7YblQÿÿ[] = $_obfuscate_6Aÿÿ['rule_s'];
            }
            else if ( $_obfuscate_6Aÿÿ['type'] == "kong" )
            {
                $_obfuscate_HmJYx_HCewÿÿ[] = $_obfuscate_6Aÿÿ['kong'];
            }
            else
            {
                $_obfuscate_Lw9wXKzqBgÿÿ[] = $_obfuscate_6Aÿÿ['dept'];
                $_obfuscate_MtpzvDgUD7YblQÿÿ[] = $_obfuscate_6Aÿÿ['station'];
            }
        }
        $_obfuscate_dga5p5gjYJ23VQÿÿ = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate__ooZFvTbHAÿÿ );
        $_obfuscate_dQ8cgLyveESU = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_JF89pTCN4WiNCgÿÿ );
        $_obfuscate_uLf44wk1NRqS = app::loadapp( "main", "station" )->api_getNamesByIds( $_obfuscate_MtpzvDgUD7YblQÿÿ );
        $_obfuscate_2wÿÿ = app::loadapp( "main", "struct" )->api_getNamesByIds( $_obfuscate_Lw9wXKzqBgÿÿ );
        $_obfuscate__Ja_D7YH = $CNOA_DB->db_select( array( "id", "name" ), $this->t_set_field, "WHERE `id` IN (".implode( ",", $_obfuscate_HmJYx_HCewÿÿ ).") " );
        if ( !is_array( $_obfuscate__Ja_D7YH ) )
        {
            $_obfuscate__Ja_D7YH = array( );
        }
        $_obfuscate_HmJYx_HCewÿÿ = array( );
        foreach ( $_obfuscate__Ja_D7YH as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_HmJYx_HCewÿÿ[$_obfuscate_6Aÿÿ['id']] = $_obfuscate_6Aÿÿ['name'];
        }
        $_obfuscate_EzJXRY5VDKzFxgÿÿ = array( "faqi" => "[å‘èµ·äºº]", "zhuban" => "[ä¸»åŠäºº]", "faqiself" => "[å‘èµ·äººè‡ªå·±]", "beforepeop" => "[æ‰€æœ‰å·²åŠç†äºº]", "myDept" => "[æ‰€å±éƒ¨é—¨]", "upDept" => "[ä¸Šçº§éƒ¨é—¨]", "myUpDept" => "[æ‰€å±éƒ¨é—¨å’Œä¸Šçº§éƒ¨é—¨]", "allDept" => "[æ‰€å±éƒ¨é—¨åŠæ‰€æœ‰ä¸Šçº§éƒ¨é—¨]" );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['type'] == "dept" )
            {
                $_obfuscate_XRvPgP5V0t4ÿ = $_obfuscate_2wÿÿ[$_obfuscate_6Aÿÿ['dept']];
                $_obfuscate_SeV31Qÿÿ['id'] = $_obfuscate_6Aÿÿ['dept'];
                $_obfuscate_SeV31Qÿÿ['text'] = isset( $_obfuscate_XRvPgP5V0t4ÿ ) ? $_obfuscate_XRvPgP5V0t4ÿ : lang( "deptBeenDel" );
                $_obfuscate_vwGQSAÿÿ[] = $_obfuscate_SeV31Qÿÿ;
            }
            else if ( $_obfuscate_6Aÿÿ['type'] == "people" )
            {
                $_obfuscate__Wi6396IheAÿ = $_obfuscate_dga5p5gjYJ23VQÿÿ[$_obfuscate_6Aÿÿ['people']]['truename'];
                $_obfuscate_SeV31Qÿÿ['id'] = $_obfuscate_6Aÿÿ['people'];
                $_obfuscate_SeV31Qÿÿ['text'] = isset( $_obfuscate__Wi6396IheAÿ ) ? $_obfuscate__Wi6396IheAÿ : lang( "userNotExists" );
                $_obfuscate_YsVdvv0c[] = $_obfuscate_SeV31Qÿÿ;
            }
            else if ( $_obfuscate_6Aÿÿ['type'] == "exclude" )
            {
                $_obfuscate_RoxLIlJRRAIfNYKoClY = $_obfuscate_dQ8cgLyveESU[$_obfuscate_6Aÿÿ['exclude']]['truename'];
                $_obfuscate_SeV31Qÿÿ['id'] = $_obfuscate_6Aÿÿ['exclude'];
                $_obfuscate_SeV31Qÿÿ['text'] = isset( $_obfuscate_RoxLIlJRRAIfNYKoClY ) ? $_obfuscate_RoxLIlJRRAIfNYKoClY : lang( "userNotExists" );
                $_obfuscate_fdSO3eSyAQÿÿ[] = $_obfuscate_SeV31Qÿÿ;
            }
            else if ( $_obfuscate_6Aÿÿ['type'] == "rule" )
            {
                $_obfuscate_SeV31Qÿÿ['dept'] = $_obfuscate_6Aÿÿ['rule_d'];
                $_obfuscate_SeV31Qÿÿ['id'] = $_obfuscate_6Aÿÿ['rule_s'];
                $_obfuscate_SeV31Qÿÿ['people'] = $_obfuscate_6Aÿÿ['rule_p'];
                if ( $_obfuscate_6Aÿÿ['rule_p'] == "faqiself" )
                {
                    $_obfuscate_SeV31Qÿÿ['text'] = $_obfuscate_EzJXRY5VDKzFxgÿÿ[$_obfuscate_6Aÿÿ['rule_p']];
                }
                else if ( $_obfuscate_6Aÿÿ['rule_p'] == "beforepeop" )
                {
                    $_obfuscate_SeV31Qÿÿ['text'] = $_obfuscate_EzJXRY5VDKzFxgÿÿ[$_obfuscate_6Aÿÿ['rule_p']];
                }
                else
                {
                    $_obfuscate_SeV31Qÿÿ['text'] = $_obfuscate_EzJXRY5VDKzFxgÿÿ[$_obfuscate_6Aÿÿ['rule_p']]." ".$_obfuscate_EzJXRY5VDKzFxgÿÿ[$_obfuscate_6Aÿÿ['rule_d']]." [(".lang( "station" ).")".$_obfuscate_uLf44wk1NRqS[$_obfuscate_6Aÿÿ['rule_s']]."]";
                }
                $_obfuscate_6mlyHgÿÿ[] = $_obfuscate_SeV31Qÿÿ;
            }
            else if ( $_obfuscate_6Aÿÿ['type'] == "station" )
            {
                $_obfuscate_SeV31Qÿÿ['id'] = $_obfuscate_6Aÿÿ['station'];
                $_obfuscate_SeV31Qÿÿ['text'] = $_obfuscate_uLf44wk1NRqS[$_obfuscate_6Aÿÿ['station']];
                $_obfuscate_m5leXC9_Zgÿÿ[] = $_obfuscate_SeV31Qÿÿ;
            }
            else if ( $_obfuscate_6Aÿÿ['type'] == "deptstation" )
            {
                $_obfuscate_XRvPgP5V0t4ÿ = $_obfuscate_2wÿÿ[$_obfuscate_6Aÿÿ['dept']];
                $_obfuscate_Ox8sY3sXWruQLLYÿ = $_obfuscate_uLf44wk1NRqS[$_obfuscate_6Aÿÿ['station']];
                if ( $_obfuscate_XRvPgP5V0t4ÿ && $_obfuscate_Ox8sY3sXWruQLLYÿ )
                {
                    $_obfuscate_SeV31Qÿÿ['text'] = "[".$_obfuscate_XRvPgP5V0t4ÿ."] [(".lang( "station" ).")".$_obfuscate_Ox8sY3sXWruQLLYÿ."]";
                }
                else
                {
                    $_obfuscate_SeV31Qÿÿ['text'] = lang( "ruleNotLegitimate" );
                }
                $_obfuscate_SeV31Qÿÿ['id'] = $_obfuscate_6Aÿÿ['dept'].",".$_obfuscate_6Aÿÿ['station'];
                $_obfuscate_ZxVMfNbI5ugv4Ksÿ[] = $_obfuscate_SeV31Qÿÿ;
            }
            else if ( $_obfuscate_6Aÿÿ['type'] == "kong" )
            {
                $_obfuscate_SeV31Qÿÿ['id'] = $_obfuscate_6Aÿÿ['kong'];
                $_obfuscate_SeV31Qÿÿ['text'] = $_obfuscate_HmJYx_HCewÿÿ[$_obfuscate_6Aÿÿ['kong']];
                $_obfuscate_flKx1gÿÿ[] = $_obfuscate_SeV31Qÿÿ;
            }
        }
        $_obfuscate_6RYLWQÿÿ['dept'] = $_obfuscate_vwGQSAÿÿ;
        $_obfuscate_6RYLWQÿÿ['kong'] = array( );
        $_obfuscate_6RYLWQÿÿ['people'] = $_obfuscate_YsVdvv0c;
        $_obfuscate_6RYLWQÿÿ['exclude'] = $_obfuscate_fdSO3eSyAQÿÿ;
        $_obfuscate_6RYLWQÿÿ['rule'] = $_obfuscate_6mlyHgÿÿ;
        $_obfuscate_6RYLWQÿÿ['station'] = $_obfuscate_m5leXC9_Zgÿÿ;
        $_obfuscate_6RYLWQÿÿ['deptStation'] = $_obfuscate_ZxVMfNbI5ugv4Ksÿ;
        $_obfuscate_6RYLWQÿÿ['kong'] = $_obfuscate_flKx1gÿÿ;
        return $_obfuscate_6RYLWQÿÿ;
    }

    private function _loadDealWay( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $_obfuscate_fMfsswÿÿ = $CNOA_DB->db_select( array( "deal" ), $this->t_use_deal_way, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND `stepId`={$_obfuscate_0Ul8BBkt}" );
        if ( !is_array( $_obfuscate_fMfsswÿÿ ) )
        {
            $_obfuscate_fMfsswÿÿ = array( );
        }
        $_obfuscate_SeV31Qÿÿ = $_obfuscate_6RYLWQÿÿ = array( );
        foreach ( $_obfuscate_fMfsswÿÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_SeV31Qÿÿ[] = $_obfuscate_VgKtFegÿ;
        }
        $_obfuscate_6RYLWQÿÿ['dealWay'] = $_obfuscate_SeV31Qÿÿ;
        return $_obfuscate_6RYLWQÿÿ;
    }

    private function _loadConditionData( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_step_condition, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' AND `stepId` = '{$_obfuscate_0Ul8BBkt}' ORDER BY `id` ASC " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_MnVVbyZQFVwÿ = array( );
        $_obfuscate_wO3K = array( );
        $_obfuscate_eBU_Sjcÿ = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_MnVVbyZQFVwÿ[$_obfuscate_6Aÿÿ['nextStepId']][] = $_obfuscate_6Aÿÿ;
            if ( $_obfuscate_6Aÿÿ['head'] == 1 || $_obfuscate_6Aÿÿ['head'] == 0 && $_obfuscate_6Aÿÿ['pid'] == 0 )
            {
                $_obfuscate_wO3K[$_obfuscate_6Aÿÿ['nextStepId']][] = $_obfuscate_6Aÿÿ['id'];
            }
            else
            {
                $_obfuscate_eBU_Sjcÿ[$_obfuscate_6Aÿÿ['pid']][] = $_obfuscate_6Aÿÿ;
            }
            $_obfuscate_Hb1v[$_obfuscate_6Aÿÿ['id']] = $_obfuscate_6Aÿÿ;
        }
        $_obfuscate_j9sJesÿ = array( );
        foreach ( $_obfuscate_wO3K as $_obfuscate_5wÿÿ => $_obfuscate_YupB5gÿÿ )
        {
            $_obfuscate_ = array( );
            foreach ( $_obfuscate_YupB5gÿÿ as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_SeV31Qÿÿ['text'] = $_obfuscate_Hb1v[$_obfuscate_6Aÿÿ]['text'];
                $_obfuscate_SeV31Qÿÿ['orAnd'] = $_obfuscate_Hb1v[$_obfuscate_6Aÿÿ]['orAnd'];
                $_obfuscate_Wlf9Dgÿÿ['name'] = $_obfuscate_Hb1v[$_obfuscate_6Aÿÿ]['name'];
                $_obfuscate_Wlf9Dgÿÿ['rule'] = $_obfuscate_Hb1v[$_obfuscate_6Aÿÿ]['rule'];
                $_obfuscate_Wlf9Dgÿÿ['ovalue'] = $_obfuscate_Hb1v[$_obfuscate_6Aÿÿ]['ovalue'];
                $_obfuscate_Wlf9Dgÿÿ['orAnd'] = $_obfuscate_Hb1v[$_obfuscate_6Aÿÿ]['orAnd'];
                if ( in_array( $_obfuscate_Hb1v[$_obfuscate_6Aÿÿ]['fieldType'], array( "n_n", "n_s", "n_d", "d_n", "d_s", "d_d" ) ) )
                {
                    $_obfuscate_Wlf9Dgÿÿ['name'] = "s|".$_obfuscate_Hb1v[$_obfuscate_6Aÿÿ]['fieldType'];
                }
                $_obfuscate_SeV31Qÿÿ['items'][] = $_obfuscate_Wlf9Dgÿÿ;
                if ( 0 < count( $_obfuscate_eBU_Sjcÿ[$_obfuscate_6Aÿÿ] ) )
                {
                    $_obfuscate_SeV31Qÿÿ['left'] = 1;
                    $_obfuscate_SeV31Qÿÿ['right'] = 1;
                    foreach ( $_obfuscate_eBU_Sjcÿ[$_obfuscate_6Aÿÿ] as $_obfuscate_bRQÿ )
                    {
                        $_obfuscate_Wlf9Dgÿÿ['name'] = $_obfuscate_bRQÿ['name'];
                        $_obfuscate_Wlf9Dgÿÿ['rule'] = $_obfuscate_bRQÿ['rule'];
                        $_obfuscate_Wlf9Dgÿÿ['ovalue'] = $_obfuscate_bRQÿ['ovalue'];
                        $_obfuscate_Wlf9Dgÿÿ['orAnd'] = $_obfuscate_bRQÿ['orAnd'];
                        if ( in_array( $_obfuscate_bRQÿ['fieldType'], array( "n_n", "n_s", "n_d", "d_n", "d_s", "d_d" ) ) )
                        {
                            $_obfuscate_Wlf9Dgÿÿ['name'] = "s|".$_obfuscate_bRQÿ['fieldType'];
                        }
                        $_obfuscate_SeV31Qÿÿ['items'][] = $_obfuscate_Wlf9Dgÿÿ;
                    }
                }
                else
                {
                    $_obfuscate_SeV31Qÿÿ['left'] = 0;
                    $_obfuscate_SeV31Qÿÿ['right'] = 0;
                }
                $_obfuscate_[] = $_obfuscate_SeV31Qÿÿ;
                unset( $_obfuscate_SeV31Qÿÿ );
            }
            $_obfuscate_6RYLWQÿÿ['id'] = $_obfuscate_5wÿÿ;
            $_obfuscate_6RYLWQÿÿ['items'] = $_obfuscate_;
            $_obfuscate_j9sJesÿ[] = $_obfuscate_6RYLWQÿÿ;
            unset( $_obfuscate_AGk1QY4ÿ );
        }
        return $_obfuscate_j9sJesÿ;
    }

    private function _loadChildData( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_step_child_kongjian, "WHERE `flowId` = ".$_obfuscate_F4AbnVRh." AND `cStepId` = {$_obfuscate_0Ul8BBkt} " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( ereg( "T_", $_obfuscate_6Aÿÿ['childKongjian'] ) )
            {
                $_obfuscate_kM1PB1K[] = str_replace( "T_", "", $_obfuscate_6Aÿÿ['childKongjian'] );
            }
            else if ( ereg( "D_", $_obfuscate_6Aÿÿ['childKongjian'] ) )
            {
                $_obfuscate_8XjS1n72[] = str_replace( "D_", "", $_obfuscate_6Aÿÿ['childKongjian'] );
            }
            if ( ereg( "T_", $_obfuscate_6Aÿÿ['parentKongjian'] ) )
            {
                $_obfuscate_kM1PB1K[] = str_replace( "T_", "", $_obfuscate_6Aÿÿ['parentKongjian'] );
            }
            else if ( ereg( "D_", $_obfuscate_6Aÿÿ['parentKongjian'] ) )
            {
                $_obfuscate_8XjS1n72[] = str_replace( "D_", "", $_obfuscate_6Aÿÿ['parentKongjian'] );
            }
            $_obfuscate_l2CIvUX0Kvp4[] = $_obfuscate_6Aÿÿ['bangdingFlow'];
        }
        if ( !empty( $_obfuscate_l2CIvUX0Kvp4 ) )
        {
            $_obfuscate_SIUSR4F6 = $CNOA_DB->db_select( "*", $this->t_set_flow, "WHERE `flowId` IN (".implode( ",", $_obfuscate_l2CIvUX0Kvp4 ).") " );
            if ( !is_array( $_obfuscate_SIUSR4F6 ) )
            {
                $_obfuscate_SIUSR4F6 = array( );
            }
            foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_WMVwRv5Dgÿÿ[$_obfuscate_6Aÿÿ['flowId']] = $_obfuscate_6Aÿÿ['name'];
            }
        }
        if ( !empty( $_obfuscate_kM1PB1K ) )
        {
            $_obfuscate_XKxKFeaAMUQÿ = $CNOA_DB->db_select( "*", $this->t_set_field, "WHERE `id` IN (".implode( ",", $_obfuscate_kM1PB1K ).") " );
            if ( !is_array( $_obfuscate_XKxKFeaAMUQÿ ) )
            {
                $_obfuscate_XKxKFeaAMUQÿ = array( );
            }
            foreach ( $_obfuscate_XKxKFeaAMUQÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_Eedcyws5SG0U["T_".$_obfuscate_6Aÿÿ['id']] = $_obfuscate_6Aÿÿ['name'];
            }
        }
        if ( !empty( $_obfuscate_8XjS1n72 ) )
        {
            $_obfuscate_7Hp0w_lfFt4ÿ = $CNOA_DB->db_select( "*", $this->t_set_field_detail, "WHERE `id` IN (".implode( ",", $_obfuscate_8XjS1n72 ).") " );
            if ( !is_array( $_obfuscate_7Hp0w_lfFt4ÿ ) )
            {
                $_obfuscate_7Hp0w_lfFt4ÿ = array( );
            }
            foreach ( $_obfuscate_7Hp0w_lfFt4ÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_dGoPOiQ2Iw5a["D_".$_obfuscate_6Aÿÿ['id']] = $_obfuscate_6Aÿÿ['name'];
            }
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['bangdingFlowName'] = $_obfuscate_WMVwRv5Dgÿÿ[$_obfuscate_6Aÿÿ['bangdingFlow']];
            if ( ereg( "T_", $_obfuscate_6Aÿÿ['parentKongjian'] ) )
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['parentKongjianName'] = $_obfuscate_Eedcyws5SG0U[$_obfuscate_6Aÿÿ['parentKongjian']];
            }
            else if ( ereg( "D_", $_obfuscate_6Aÿÿ['parentKongjian'] ) )
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['parentKongjianName'] = $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_6Aÿÿ['parentKongjian']];
            }
            if ( ereg( "T_", $_obfuscate_6Aÿÿ['childKongjian'] ) )
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['childKongjianName'] = $_obfuscate_Eedcyws5SG0U[$_obfuscate_6Aÿÿ['childKongjian']];
            }
            else if ( ereg( "D_", $_obfuscate_6Aÿÿ['childKongjian'] ) )
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['childKongjianName'] = $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_6Aÿÿ['childKongjian']];
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
        foreach ( $_obfuscate_xs33Yt_k as $_obfuscate_6Aÿÿ )
        {
            if ( !empty( $_obfuscate_6Aÿÿ['user'] ) )
            {
                $_obfuscate_7Ri3[] = $_obfuscate_6Aÿÿ['user'];
            }
            if ( !empty( $_obfuscate_6Aÿÿ['dept'] ) )
            {
                $_obfuscate_2sZ8Toxw[] = $_obfuscate_6Aÿÿ['dept'];
            }
            if ( !empty( $_obfuscate_6Aÿÿ['rule'] ) )
            {
                $_obfuscate_6mlyHgÿÿ = explode( ",", $_obfuscate_6Aÿÿ['rule'] );
                foreach ( $_obfuscate_6mlyHgÿÿ as $_obfuscate_OQÿÿ )
                {
                    list( ,  ) = explode( "|", $_obfuscate_OQÿÿ );
                    $_obfuscate_y6jH[] = $Var_384;
                }
            }
        }
        $_obfuscate_rNTRvK6XhPsP = $_obfuscate_mMnyN6u83wÿÿ = $_obfuscate_wtaEWTJi_Qÿÿ = array( );
        $_obfuscate_9Weh8jtBTtqrLwÿÿ = $CNOA_DB->db_select( "*", $this->t_set_autoFenfa, "WHERE `flowId`=".$_obfuscate_F4AbnVRh );
        if ( !is_array( $_obfuscate_9Weh8jtBTtqrLwÿÿ ) )
        {
            $_obfuscate_9Weh8jtBTtqrLwÿÿ = array( );
        }
        foreach ( $_obfuscate_9Weh8jtBTtqrLwÿÿ as $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_mMnyN6u83wÿÿ[$_obfuscate_VgKtFegÿ['stepId']] = array_unique( explode( ",", $_obfuscate_VgKtFegÿ['uids'] ) );
            $_obfuscate_wtaEWTJi_Qÿÿ[$_obfuscate_VgKtFegÿ['stepId']] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( explode( ",", $_obfuscate_VgKtFegÿ['uids'] ) );
        }
        foreach ( $_obfuscate_mMnyN6u83wÿÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
        {
            foreach ( $_obfuscate_VgKtFegÿ as $_obfuscate_LQ8UKgÿÿ )
            {
                $_obfuscate_rNTRvK6XhPsP[$_obfuscate_Vwty]['autoFenfa'][] = array(
                    $_obfuscate_LQ8UKgÿÿ,
                    $_obfuscate_wtaEWTJi_Qÿÿ[$_obfuscate_Vwty][$_obfuscate_LQ8UKgÿÿ]
                );
            }
        }
        $_obfuscate__Wi6396IheAÿ = $_obfuscate_XRvPgP5V0t4ÿ = $_obfuscate_m5leXC9_Zgÿÿ = array( );
        if ( 0 < count( $_obfuscate_7Ri3 ) )
        {
            $_obfuscate_7Ri3 = array_unique( explode( ",", implode( ",", $_obfuscate_7Ri3 ) ) );
            $_obfuscate__Wi6396IheAÿ = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_7Ri3 );
        }
        if ( 0 < count( $_obfuscate_2sZ8Toxw ) )
        {
            $_obfuscate_2sZ8Toxw = array_unique( explode( ",", implode( ",", $_obfuscate_2sZ8Toxw ) ) );
            $_obfuscate_XRvPgP5V0t4ÿ = app::loadapp( "main", "struct" )->api_getNamesByIds( $_obfuscate_2sZ8Toxw );
        }
        if ( 0 < count( $_obfuscate_y6jH ) )
        {
            $_obfuscate_y6jH = array_unique( $_obfuscate_y6jH );
            $_obfuscate_m5leXC9_Zgÿÿ = app::loadapp( "main", "station" )->api_getNamesByIds( $_obfuscate_y6jH );
        }
        $_obfuscate_dhMyCb2idSLFhAÿÿ = array( "faqi" => "å‘èµ·äºº", "zhuban" => "ä¸»åŠäºº" );
        $_obfuscate_ZoJ6n0QmFoIÿ = array( "myDept" => "æ‰€å±éƒ¨é—¨", "upDept" => "ä¸Šçº§éƒ¨é—¨", "myUpDept" => "æ‰€å±éƒ¨é—¨å’Œä¸Šçº§éƒ¨é—¨", "allDept" => "æ‰€å±éƒ¨é—¨åŠæ‰€æœ‰ä¸Šçº§éƒ¨é—¨" );
        $_obfuscate_ktRUIU_2er7vxwÿÿ = array( );
        foreach ( $_obfuscate_xs33Yt_k as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_eVTMIa1A = $_obfuscate_7Ri3 = $_obfuscate_2sZ8Toxw = $_obfuscate_6mlyHgÿÿ = array( );
            if ( !empty( $_obfuscate_6Aÿÿ['user'] ) )
            {
                $_obfuscate_7Ri3 = explode( ",", $_obfuscate_6Aÿÿ['user'] );
            }
            if ( !empty( $_obfuscate_6Aÿÿ['dept'] ) )
            {
                $_obfuscate_2sZ8Toxw = explode( ",", $_obfuscate_6Aÿÿ['dept'] );
            }
            if ( !empty( $_obfuscate_6Aÿÿ['rule'] ) )
            {
                $_obfuscate_6mlyHgÿÿ = explode( ",", $_obfuscate_6Aÿÿ['rule'] );
            }
            foreach ( $_obfuscate_7Ri3 as $_obfuscate_0W8ÿ )
            {
                $_obfuscate_eVTMIa1A['user'][] = array(
                    $_obfuscate_0W8ÿ,
                    $_obfuscate__Wi6396IheAÿ[$_obfuscate_0W8ÿ]
                );
            }
            foreach ( $_obfuscate_2sZ8Toxw as $_obfuscate_0W8ÿ )
            {
                $_obfuscate_eVTMIa1A['dept'][] = array(
                    $_obfuscate_0W8ÿ,
                    $_obfuscate_XRvPgP5V0t4ÿ[$_obfuscate_0W8ÿ]
                );
            }
            foreach ( $_obfuscate_6mlyHgÿÿ as $_obfuscate_TAxu )
            {
                list( $_obfuscate_8wÿÿ, $_obfuscate_5gÿÿ, $p ) = explode( "|", $_obfuscate_TAxu );
                $_obfuscate_eVTMIa1A['rule'][] = array(
                    $_obfuscate_TAxu,
                    "[".$_obfuscate_dhMyCb2idSLFhAÿÿ[$_obfuscate_8wÿÿ]."][{$_obfuscate_ZoJ6n0QmFoIÿ[$_obfuscate_5gÿÿ]}][{$_obfuscate_m5leXC9_Zgÿÿ[$p]}]"
                );
            }
            switch ( $_obfuscate_6Aÿÿ['operate'] )
            {
            case self::$OPERATE_TYPE_HUIQIAN :
                $_obfuscate_ktRUIU_2er7vxwÿÿ[$_obfuscate_6Aÿÿ['stepId']]['huiqian'] = $_obfuscate_eVTMIa1A;
                break;
            case self::$OPERATE_TYPE_FENFA :
                $_obfuscate_ktRUIU_2er7vxwÿÿ[$_obfuscate_6Aÿÿ['stepId']]['fenfa'] = $_obfuscate_eVTMIa1A;
            }
        }
        foreach ( $_obfuscate_rNTRvK6XhPsP as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_ktRUIU_2er7vxwÿÿ[$_obfuscate_Vwty]['fenfa']['autoFenfa'] = $_obfuscate_rNTRvK6XhPsP[$_obfuscate_Vwty]['autoFenfa'];
        }
        return $_obfuscate_ktRUIU_2er7vxwÿÿ;
    }

    private function _getJsonData( $_obfuscate_lWk5hHye = FALSE )
    {
        global $CNOA_DB;
        $_obfuscate_Bk2lGlkÿ = "WHERE 1 ";
        $_obfuscate_mV9HBLYÿ = getpar( $_POST, "start", 0 );
        $_obfuscate_v1GprsIz = getpar( $_POST, "sortId", 0 );
        $_obfuscate_KiCCqRQÿ = getpar( $_POST, "sname", "" );
        if ( !empty( $_obfuscate_v1GprsIz ) )
        {
            $_obfuscate_Bk2lGlkÿ = "WHERE `sortId` = '".$_obfuscate_v1GprsIz."' ";
        }
        if ( !empty( $_obfuscate_KiCCqRQÿ ) )
        {
            $_obfuscate_Bk2lGlkÿ .= "AND `name` LIKE '%".$_obfuscate_KiCCqRQÿ."%' ";
        }
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "flowId", "name", "sortId", "status", "tplSort", "flowType", "pageset" ), $this->t_set_flow, $_obfuscate_Bk2lGlkÿ.( "ORDER BY `order`,`flowId` DESC LIMIT ".$_obfuscate_mV9HBLYÿ.",{$this->rows}" ) );
        if ( $_obfuscate_lWk5hHye )
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "flowId", "name", "sortId", "status", "tplSort", "flowType", "pageset" ), $this->t_set_flow, $_obfuscate_Bk2lGlkÿ."ORDER BY `order`,`flowId` DESC" );
        }
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
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['sort'] = $_obfuscate_RopcQP_w[$_obfuscate_6Aÿÿ['sortId']]['name'];
        }
        if ( $_obfuscate_lWk5hHye )
        {
            return $_obfuscate_mPAjEGLn;
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_mPAjEGLn;
        $_obfuscate_NlQÿ->total = $CNOA_DB->db_getCount( $this->t_set_flow, $_obfuscate_Bk2lGlkÿ );
        echo $_obfuscate_NlQÿ->makeJsonData( );
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
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _submit( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_LeS8hwÿÿ = getpar( $_POST, "type", 0 );
        $_obfuscate_v1GprsIz = getpar( $_POST, "sortId", 0 );
        $_obfuscate_6RYLWQÿÿ['name'] = getpar( $_POST, "name", "" );
        $_obfuscate_6RYLWQÿÿ['nameRule'] = getpar( $_POST, "nameRule", "" );
        $_obfuscate_6RYLWQÿÿ['sortId'] = $_obfuscate_v1GprsIz;
        $_obfuscate_6RYLWQÿÿ['about'] = getpar( $_POST, "about", "" );
        $_obfuscate_6RYLWQÿÿ['tplSort'] = getpar( $_POST, "tplSort", "" );
        $_obfuscate_6RYLWQÿÿ['flowType'] = getpar( $_POST, "flowType", "" );
        $_obfuscate_6RYLWQÿÿ['nameRuleAllowEdit'] = getpar( $_POST, "nameRuleAllowEdit", 0 );
        $_obfuscate_6RYLWQÿÿ['nameDisallowBlank'] = getpar( $_POST, "nameDisallowBlank", 1 );
        $_obfuscate_6RYLWQÿÿ['noticeCallback'] = getpar( $_POST, "noticeCallback", 1 );
        $_obfuscate_6RYLWQÿÿ['noticeCancel'] = getpar( $_POST, "noticeCancel", 1 );
        $_obfuscate_6RYLWQÿÿ['noticeAtGoBack'] = getpar( $_POST, "noticeAtGoBack", 1 );
        $_obfuscate_6RYLWQÿÿ['noticeAtReject'] = getpar( $_POST, "noticeAtReject", 1 );
        $_obfuscate_6RYLWQÿÿ['noticeAtInterrupt'] = getpar( $_POST, "noticeAtInterrupt", 1 );
        $_obfuscate_6RYLWQÿÿ['noticeAtFinish'] = getpar( $_POST, "noticeAtFinish", 1 );
        if ( empty( $_obfuscate_F4AbnVRh ) )
        {
            $_obfuscate_6RYLWQÿÿ['uid'] = $CNOA_SESSION->get( "UID" );
            $_obfuscate_6RYLWQÿÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->t_set_flow, "WHERE `name` = '".$_obfuscate_6RYLWQÿÿ['name']."' " );
            if ( !empty( $_obfuscate_Ybai ) )
            {
                msg::callback( FALSE, lang( "flowNameExist" ) );
            }
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_set_flow );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3662, $_obfuscate_6RYLWQÿÿ['name'], lang( "flow" ) );
        }
        else
        {
            $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->t_set_flow, "WHERE `name` = '".$_obfuscate_6RYLWQÿÿ['name']."' AND `flowId` != '{$_obfuscate_F4AbnVRh}' " );
            $_obfuscate_Z4PHlvEÿ = $CNOA_DB->db_getcount( $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
            if ( !empty( $_obfuscate_Ybai ) )
            {
                msg::callback( FALSE, lang( "flowNameExist" ) );
            }
            $CNOA_DB->db_update( $_obfuscate_6RYLWQÿÿ, $this->t_set_flow, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."'" );
            if ( !empty( $_obfuscate_Z4PHlvEÿ ) )
            {
                $CNOA_DB->db_update( "`sortId`=".$_obfuscate_v1GprsIz, $this->t_use_flow, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."'" );
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 3662, $_obfuscate_6RYLWQÿÿ['name'], lang( "flow" ) );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadFormData( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_6RYLWQÿÿ = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' " );
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _changeStatus( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_LeS8hwÿÿ = getpar( $_POST, "type", "" );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_POST, "flowType", 0 );
        if ( $_obfuscate_XkuTFqZ6Tmkÿ == 0 )
        {
            if ( $_obfuscate_LeS8hwÿÿ == "stop" )
            {
                $_obfuscate_6RYLWQÿÿ['status'] = 0;
            }
            else if ( $_obfuscate_LeS8hwÿÿ == "use" )
            {
                $_obfuscate_euP1Hw5BxD3jMYDR4Qÿÿ = $CNOA_DB->db_getcount( $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
                if ( $_obfuscate_euP1Hw5BxD3jMYDR4Qÿÿ < 2 )
                {
                    msg::callback( FALSE, lang( "noStepDesignStep" ) );
                }
                $_obfuscate_6RYLWQÿÿ['status'] = 1;
            }
        }
        else if ( $_obfuscate_LeS8hwÿÿ == "stop" )
        {
            $_obfuscate_6RYLWQÿÿ['status'] = 0;
        }
        else
        {
            $_obfuscate_6RYLWQÿÿ['status'] = 1;
        }
        $CNOA_DB->db_update( $_obfuscate_6RYLWQÿÿ, $this->t_set_flow, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' " );
        $CNOA_DB->db_update( array(
            "status" => $_obfuscate_6RYLWQÿÿ['status']
        ), $this->t_u_wffav, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' " );
        $_obfuscate_Thgÿ = $CNOA_DB->db_getone( array( "name", "status" ), $this->t_set_flow, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' " );
        if ( $_obfuscate_Thgÿ['status'] == 1 )
        {
            app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3662, lang( "enableFlow" ), $_obfuscate_sx8ÿ['status'] );
        }
        else
        {
            app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3662, lang( "disableFlow" ), $_obfuscate_sx8ÿ['status'] );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadFlowTotal( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_fKn99vuO9tUÿ = $CNOA_DB->db_getcount( $this->t_use_flow, "WHERE `status`='2' AND `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $_obfuscate_c426Ts9OtRNmjK0ÿ = $CNOA_DB->db_getcount( $this->t_use_flow, "WHERE `status`='1' AND `flowId`='".$_obfuscate_F4AbnVRh."'" );
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->doing = $_obfuscate_c426Ts9OtRNmjK0ÿ;
        $_obfuscate_NlQÿ->done = $_obfuscate_fKn99vuO9tUÿ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _ms_loadTemplateFile( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = intval( getpar( $_GET, "flowId", 0 ) );
        $_obfuscate_pEvU7Kz2Ywÿÿ = intval( getpar( $_GET, "tplSort", 0 ) );
        $_obfuscate_o6LA2yPirJIreFAÿ = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/doc.history.0.php" );
        $_obfuscate_U9NJHZRq6Jr7T_Aÿ = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/xls.history.0.php" );
        if ( $_obfuscate_pEvU7Kz2Ywÿÿ == 1 || $_obfuscate_pEvU7Kz2Ywÿÿ == 3 )
        {
            if ( file_exists( $_obfuscate_o6LA2yPirJIreFAÿ ) )
            {
                $_obfuscate_6hS1Rwÿÿ = @file_get_contents( $_obfuscate_o6LA2yPirJIreFAÿ );
            }
            else
            {
                $_obfuscate_MI4_ZH9BUwÿÿ = CNOA_PATH_FILE.( "/common/wf/set/{".$_obfuscate_F4AbnVRh."}/{$_obfuscate_F4AbnVRh}.php" );
                if ( file_exists( $_obfuscate_MI4_ZH9BUwÿÿ ) )
                {
                    $_obfuscate_6hS1Rwÿÿ = @file_get_contents( $_obfuscate_MI4_ZH9BUwÿÿ );
                }
                else
                {
                    mkdirs( dirname( $_obfuscate_o6LA2yPirJIreFAÿ ) );
                }
            }
        }
        else if ( file_exists( $_obfuscate_U9NJHZRq6Jr7T_Aÿ ) )
        {
            $_obfuscate_6hS1Rwÿÿ = @file_get_contents( $_obfuscate_U9NJHZRq6Jr7T_Aÿ );
        }
        else
        {
            $_obfuscate_MI4_ZH9BUwÿÿ = CNOA_PATH_FILE.( "/common/wf/set/{".$_obfuscate_F4AbnVRh."}/{$_obfuscate_F4AbnVRh}.php" );
            if ( file_exists( $_obfuscate_MI4_ZH9BUwÿÿ ) )
            {
                $_obfuscate_6hS1Rwÿÿ = @file_get_contents( $_obfuscate_MI4_ZH9BUwÿÿ );
            }
            else
            {
                mkdirs( dirname( $_obfuscate_U9NJHZRq6Jr7T_Aÿ ) );
            }
        }
        echo $_obfuscate_6hS1Rwÿÿ;
        exit( );
    }

    private function _ms_submitMsOfficeData( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = intval( getpar( $_POST, "flowId", 0 ) );
        $_obfuscate_LeS8hwÿÿ = getpar( $_GET, "type", 0 );
        $_obfuscate_zfubNC9lKJsÿ = strtolower( strrchr( $_FILES['msOffice']['name'], "." ) );
        if ( !in_array( strtolower( $_obfuscate_zfubNC9lKJsÿ ), array( ".doc", ".docx", ".xls", ".xlsx" ) ) )
        {
            echo "0";
            exit( );
        }
        if ( $_obfuscate_LeS8hwÿÿ == "1" || $_obfuscate_LeS8hwÿÿ == "3" )
        {
            $_obfuscate_7tmDAr7acvgDxwÿÿ = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/doc.history.0.php" );
            mkdirs( dirname( $_obfuscate_7tmDAr7acvgDxwÿÿ ) );
            if ( !cnoa_move_uploaded_file( $_FILES['msOffice']['tmp_name'], $_obfuscate_7tmDAr7acvgDxwÿÿ ) )
            {
                echo "0";
                exit( );
            }
        }
        else
        {
            $_obfuscate_7tmDAr7acvgDxwÿÿ = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/xls.history.0.php" );
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

    private function __updateStepsInfo( $_obfuscate_dw4x, $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        $_obfuscate_sc7AoZlouuAÿ = xml2array( stripslashes( $_obfuscate_dw4x ), 1, "mxGraphModel" );
        $_obfuscate_t3xmZlf1zM0ÿ = $_obfuscate_sc7AoZlouuAÿ['mxGraphModel']['root']['mxCell'];
        if ( !is_array( $_obfuscate_t3xmZlf1zM0ÿ ) )
        {
            $_obfuscate_t3xmZlf1zM0ÿ = array( );
        }
        $_obfuscate_j9eamhYÿ = array( );
        $_obfuscate_7B4LUz4ÿ = array( );
        $_obfuscate_S0PSA37yAwÿÿ = array( );
        foreach ( $_obfuscate_t3xmZlf1zM0ÿ as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_snMÿ = $_obfuscate_6Aÿÿ['attr'];
            if ( $_obfuscate_snMÿ['edge'] == 1 )
            {
                $_obfuscate_7B4LUz4ÿ[] = array(
                    "source" => $_obfuscate_snMÿ['source'],
                    "target" => $_obfuscate_snMÿ['target'],
                    "mark" => $_obfuscate_snMÿ['mark']
                );
            }
            if ( in_array( $_obfuscate_snMÿ['nodeType'], array( "node", "startNode", "endNode", "cNode", "bNode", "childNode", "bcNode" ) ) )
            {
                switch ( $_obfuscate_snMÿ['nodeType'] )
                {
                case "startNode" :
                    $_obfuscate_L9PX7r5kCPQÿ = 1;
                    $_obfuscate_vpZO7cBY1GZYtnUÿ = intval( $_obfuscate_snMÿ['id'] );
                    break;
                case "endNode" :
                    $_obfuscate_L9PX7r5kCPQÿ = 3;
                    break;
                case "bNode" :
                    $_obfuscate_L9PX7r5kCPQÿ = 4;
                    break;
                case "cNode" :
                    $_obfuscate_L9PX7r5kCPQÿ = 5;
                    break;
                case "bcNode" :
                    $_obfuscate_L9PX7r5kCPQÿ = 6;
                    break;
                case "childNode" :
                    $_obfuscate_L9PX7r5kCPQÿ = 7;
                    break;
                default :
                    $_obfuscate_L9PX7r5kCPQÿ = 2;
                }
                $_obfuscate_xmNtcXVJoqq_154F = 0;
                if ( $_obfuscate_snMÿ['mark'] == "child" )
                {
                    $_obfuscate_xmNtcXVJoqq_154F = 1;
                }
                $_obfuscate_j9eamhYÿ[$_obfuscate_snMÿ['id']] = array(
                    "stepType" => addslashes( $_obfuscate_L9PX7r5kCPQÿ ),
                    "stepId" => intval( $_obfuscate_snMÿ['id'] ),
                    "stepName" => addslashes( $_obfuscate_snMÿ['value'] ),
                    "childFlow" => $_obfuscate_xmNtcXVJoqq_154F,
                    "nextStep" => array( )
                );
                $_obfuscate_S0PSA37yAwÿÿ[] = $_obfuscate_snMÿ['id'];
            }
        }
        unset( $_obfuscate_6Aÿÿ );
        unset( $_obfuscate_snMÿ );
        foreach ( $_obfuscate_7B4LUz4ÿ as $_obfuscate_6Aÿÿ )
        {
            if ( !in_array( $_obfuscate_6Aÿÿ['target'], $_obfuscate_j9eamhYÿ[$_obfuscate_6Aÿÿ['source']]['nextStep'] ) )
            {
                $_obfuscate_j9eamhYÿ[$_obfuscate_6Aÿÿ['source']]['nextStep'][] = $_obfuscate_6Aÿÿ['target'];
            }
            if ( !( $_obfuscate_6Aÿÿ['mark'] == "bothway" ) && in_array( $_obfuscate_6Aÿÿ['source'], $_obfuscate_j9eamhYÿ[$_obfuscate_6Aÿÿ['target']]['nextStep'] ) )
            {
                $_obfuscate_j9eamhYÿ[$_obfuscate_6Aÿÿ['target']]['nextStep'][] = $_obfuscate_6Aÿÿ['source'];
            }
        }
        unset( $_obfuscate_6Aÿÿ );
        foreach ( $_obfuscate_j9eamhYÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6Aÿÿ['nextStep'] = json_encode( $_obfuscate_6Aÿÿ['nextStep'] );
            $_obfuscate_6Aÿÿ['flowId'] = $_obfuscate_F4AbnVRh;
            $_obfuscate_o5fQ1gÿÿ = $CNOA_DB->db_getone( "*", $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `stepId`='{$_obfuscate_6Aÿÿ['stepId']}'" );
            if ( !$_obfuscate_o5fQ1gÿÿ )
            {
                $CNOA_DB->db_insert( $_obfuscate_6Aÿÿ, $this->t_set_step );
            }
            else
            {
                $CNOA_DB->db_update( $_obfuscate_6Aÿÿ, $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `stepId`='{$_obfuscate_6Aÿÿ['stepId']}'" );
            }
            $CNOA_DB->db_delete( $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `stepId` NOT IN(".implode( ",", $_obfuscate_S0PSA37yAwÿÿ ).")" );
        }
        return array(
            $_obfuscate_S0PSA37yAwÿÿ,
            $_obfuscate_vpZO7cBY1GZYtnUÿ
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

    private function _delete( $_obfuscate_F4AbnVRh, $_obfuscate_aMwmYIÿ, $_obfuscate_XkuTFqZ6Tmkÿ, $_obfuscate_pEvU7Kz2Ywÿÿ )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_gftfagwÿ = $CNOA_DB->db_getcount( $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( $_obfuscate_gftfagwÿ && $_obfuscate_aMwmYIÿ === "false" )
        {
            msg::callback( FALSE, lang( "DelBPMfirst" ) );
        }
        if ( $_obfuscate_aMwmYIÿ === "true" )
        {
            $_obfuscate_8Bnz38wN01cÿ = $CNOA_DB->db_select( array( "uFlowId", "attach" ), $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
            if ( !is_array( $_obfuscate_8Bnz38wN01cÿ ) )
            {
                $_obfuscate_8Bnz38wN01cÿ = array( );
            }
            ( );
            $_obfuscate_2ggÿ = new fs( );
            foreach ( $_obfuscate_8Bnz38wN01cÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_WPvkSFEMgÿÿ = json_decode( $_obfuscate_6Aÿÿ['attach'], TRUE );
                if ( is_array( $_obfuscate_WPvkSFEMgÿÿ ) && 0 < count( $_obfuscate_WPvkSFEMgÿÿ ) )
                {
                    $_obfuscate_2ggÿ->deleteFile( $_obfuscate_WPvkSFEMgÿÿ );
                }
                ( $_obfuscate_6Aÿÿ['uFlowId'] );
                $_obfuscate_e53ODz04JQÿÿ = new wfCache( );
                $_obfuscate_e53ODz04JQÿÿ->deleteCache( );
                $CNOA_DB->db_delete( $this->t_use_event, "WHERE `uFlowId`='".$_obfuscate_6Aÿÿ['uFlowId']."'" );
                $CNOA_DB->db_delete( $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_6Aÿÿ['uFlowId']."'" );
                $CNOA_DB->db_delete( $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_6Aÿÿ['uFlowId']."'" );
                $CNOA_DB->db_delete( $this->t_use_step_huiqian, "WHERE `uFlowId`='".$_obfuscate_6Aÿÿ['uFlowId']."'" );
                $CNOA_DB->db_delete( $this->t_use_proxy_uflow, "WHERE `uFlowId`='".$_obfuscate_6Aÿÿ['uFlowId']."'" );
                $CNOA_DB->db_delete( $this->t_use_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
                $CNOA_DB->db_delete( "wf_u_convergence_deal", "WHERE `uFlowId`='".$_obfuscate_6Aÿÿ['uFlowId']."'" );
            }
            $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->db_select( array( "id" ), $this->t_set_field, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `otype`='detailtable'" );
            if ( !is_array( $_obfuscate_7qDAYo85aGAÿ ) )
            {
                $_obfuscate_7qDAYo85aGAÿ = array( );
            }
            foreach ( $_obfuscate_7qDAYo85aGAÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_P8V5kcÿ = mysql_table_exists( "cnoa_z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_6Aÿÿ['id'] );
                $_obfuscate_HO4Pznsÿ = mysql_table_exists( "cnoa_z_wf_t_".$_obfuscate_F4AbnVRh );
                if ( $_obfuscate_P8V5kcÿ )
                {
                    $CNOA_DB->query( "DROP TABLE cnoa_z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_6Aÿÿ['id'] );
                }
                if ( $_obfuscate_HO4Pznsÿ )
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
        foreach ( $_obfuscate_h8TEbtzlSdi as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_rRDK3rdtK6wC = json_decode( $_obfuscate_6Aÿÿ['flowId'], TRUE );
            $_obfuscate_816eS6MRxRMEflcQ = array( );
            if ( is_array( $_obfuscate_rRDK3rdtK6wC ) && 0 < count( $_obfuscate_rRDK3rdtK6wC ) )
            {
                foreach ( $_obfuscate_rRDK3rdtK6wC as $_obfuscate_6cgÿ )
                {
                    if ( $_obfuscate_6cgÿ != $_obfuscate_F4AbnVRh )
                    {
                        $_obfuscate_816eS6MRxRMEflcQ[] = $_obfuscate_6cgÿ;
                    }
                }
            }
            $CNOA_DB->db_update( array(
                "flowId" => addslashes( json_encode( $_obfuscate_816eS6MRxRMEflcQ ) )
            ), $this->t_use_proxy, "WHERE `id`='".$_obfuscate_6Aÿÿ['id']."'" );
        }
        $CNOA_DB->db_delete( $this->t_use_proxy_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $_obfuscate_neM4JBUJlmgÿ = $CNOA_DB->db_getfield( "name", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 3362, $_obfuscate_neM4JBUJlmgÿ, lang( "flow" ) );
        $CNOA_DB->db_delete( $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $CNOA_DB->db_delete( $this->t_set_field, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $CNOA_DB->db_delete( $this->t_set_field_detail, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."'" );
        $CNOA_DB->db_delete( $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $CNOA_DB->db_delete( $this->t_set_step_condition, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $CNOA_DB->db_delete( $this->t_set_step_fields, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $CNOA_DB->db_delete( $this->t_set_step_user, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $CNOA_DB->db_delete( $this->t_s_bingfa_condition, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $CNOA_DB->db_delete( "wf_s_step_child_kongjian", "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( $_obfuscate_XkuTFqZ6Tmkÿ == 0 && $_obfuscate_pEvU7Kz2Ywÿÿ != 0 )
        {
            $_obfuscate_eG0q4_wH0Qcÿ = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/doc.history.0.php" );
            if ( file_exists( $_obfuscate_eG0q4_wH0Qcÿ ) )
            {
                $_obfuscate_8SedAwBPAÿÿ = dirname( $_obfuscate_eG0q4_wH0Qcÿ );
                @rmdir( $_obfuscate_8SedAwBPAÿÿ );
            }
        }
        $CNOA_DB->db_delete( "", $this->t_use_step_child_flow, "WHERE `flowId` = ".$_obfuscate_F4AbnVRh." AND `status` = 0 " );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function api_deleteDesignFlow( $_obfuscate_F4AbnVRh, $_obfuscate_aMwmYIÿ )
    {
        $this->_delete( $_obfuscate_F4AbnVRh, $_obfuscate_aMwmYIÿ );
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
            foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_SIUSR4F6[$_obfuscate_5wÿÿ] = addslashes( $_obfuscate_6Aÿÿ );
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
        foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( CNOA_ISSAAS === TRUE )
            {
                unset( $_obfuscate_6Aÿÿ['domainID'] );
            }
            unset( $_obfuscate_6Aÿÿ['id'] );
            foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_3QYÿ => $_obfuscate_EGUÿ )
            {
                $_obfuscate_6Aÿÿ[$_obfuscate_3QYÿ] = addslashes( $_obfuscate_EGUÿ );
            }
            $_obfuscate_6Aÿÿ['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
            $CNOA_DB->db_insert( $_obfuscate_6Aÿÿ, $this->t_set_step );
        }
        $_obfuscate_u7iA1IfUn0ÿ = array( );
        $_obfuscate_Iol2Fs8yYvPvnJBshwÿÿ = array( );
        $_obfuscate_ECnh5SGd0mzGXxdpqqFH = array( );
        $_obfuscate_Pm3ZMWpPkgÿÿ = $CNOA_DB->db_select( "*", $this->t_set_field, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( !is_array( $_obfuscate_Pm3ZMWpPkgÿÿ ) )
        {
            $_obfuscate_Pm3ZMWpPkgÿÿ = array( );
        }
        foreach ( $_obfuscate_Pm3ZMWpPkgÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_8jhldA9Y9Aÿÿ = $_obfuscate_6Aÿÿ['id'];
            if ( CNOA_ISSAAS === TRUE )
            {
                unset( $_obfuscate_6Aÿÿ['domainID'] );
            }
            $_obfuscate_Iol2Fs8yYvPvnJBshwÿÿ[$_obfuscate_8jhldA9Y9Aÿÿ] = 0;
            $_obfuscate_I3l9sFvE = $_obfuscate_6Aÿÿ['id'];
            unset( $_obfuscate_6Aÿÿ['id'] );
            foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_3QYÿ => $_obfuscate_EGUÿ )
            {
                $_obfuscate_6Aÿÿ[$_obfuscate_3QYÿ] = addslashes( $_obfuscate_EGUÿ );
            }
            $_obfuscate_6Aÿÿ['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
            if ( $_obfuscate_6Aÿÿ['otype'] == "detailtable" )
            {
                $_obfuscate_ECnh5SGd0mzGXxdpqqFH[$_obfuscate_8jhldA9Y9Aÿÿ] = $CNOA_DB->db_insert( $_obfuscate_6Aÿÿ, $this->t_set_field );
                $_obfuscate_u7iA1IfUn0ÿ['normal'][$_obfuscate_I3l9sFvE] = $_obfuscate_ECnh5SGd0mzGXxdpqqFH[$_obfuscate_8jhldA9Y9Aÿÿ];
                $_obfuscate_Iol2Fs8yYvPvnJBshwÿÿ[$_obfuscate_8jhldA9Y9Aÿÿ] = $_obfuscate_ECnh5SGd0mzGXxdpqqFH[$_obfuscate_8jhldA9Y9Aÿÿ];
            }
            else
            {
                $_obfuscate_Iol2Fs8yYvPvnJBshwÿÿ[$_obfuscate_8jhldA9Y9Aÿÿ] = $CNOA_DB->db_insert( $_obfuscate_6Aÿÿ, $this->t_set_field );
                $_obfuscate_u7iA1IfUn0ÿ['normal'][$_obfuscate_I3l9sFvE] = $_obfuscate_Iol2Fs8yYvPvnJBshwÿÿ[$_obfuscate_8jhldA9Y9Aÿÿ];
            }
        }
        $_obfuscate_rsprfS3qDSJ2MvYrvAÿ = array( );
        $_obfuscate_7Hp0w_lfFt4ÿ = $CNOA_DB->db_select( "*", $this->t_set_field_detail, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( !is_array( $_obfuscate_7Hp0w_lfFt4ÿ ) )
        {
            $_obfuscate_7Hp0w_lfFt4ÿ = array( );
        }
        foreach ( $_obfuscate_7Hp0w_lfFt4ÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_Xw4g1h2yLKFUuQÿÿ = $_obfuscate_6Aÿÿ['fid'];
            if ( CNOA_ISSAAS === TRUE )
            {
                unset( $_obfuscate_6Aÿÿ['domainID'] );
            }
            $_obfuscate_ndJExEgCiEo8 = $_obfuscate_6Aÿÿ['id'];
            $_obfuscate_rsprfS3qDSJ2MvYrvAÿ[$_obfuscate_ndJExEgCiEo8] = 0;
            $_obfuscate_I3l9sFvE = $_obfuscate_6Aÿÿ['id'];
            unset( $_obfuscate_6Aÿÿ['id'] );
            foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_3QYÿ => $_obfuscate_EGUÿ )
            {
                $_obfuscate_6Aÿÿ[$_obfuscate_3QYÿ] = addslashes( $_obfuscate_EGUÿ );
            }
            $_obfuscate_6Aÿÿ['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
            $_obfuscate_6Aÿÿ['fid'] = $_obfuscate_ECnh5SGd0mzGXxdpqqFH[$_obfuscate_Xw4g1h2yLKFUuQÿÿ];
            $_obfuscate_rsprfS3qDSJ2MvYrvAÿ[$_obfuscate_ndJExEgCiEo8] = $CNOA_DB->db_insert( $_obfuscate_6Aÿÿ, $this->t_set_field_detail );
            $_obfuscate_u7iA1IfUn0ÿ['detail'][$_obfuscate_I3l9sFvE] = $_obfuscate_rsprfS3qDSJ2MvYrvAÿ[$_obfuscate_ndJExEgCiEo8];
        }
        $_obfuscate_8MSmTrf2URD2fgÿÿ = $CNOA_DB->db_select( "*", $this->t_set_step_fields, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( !is_array( $_obfuscate_8MSmTrf2URD2fgÿÿ ) )
        {
            $_obfuscate_8MSmTrf2URD2fgÿÿ = array( );
        }
        foreach ( $_obfuscate_8MSmTrf2URD2fgÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            unset( $_obfuscate_6Aÿÿ['id'] );
            if ( CNOA_ISSAAS === TRUE )
            {
                unset( $_obfuscate_6Aÿÿ['domainID'] );
            }
            foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_3QYÿ => $_obfuscate_EGUÿ )
            {
                $_obfuscate_6Aÿÿ[$_obfuscate_3QYÿ] = addslashes( $_obfuscate_EGUÿ );
            }
            if ( $_obfuscate_6Aÿÿ['from'] == 0 )
            {
                $_obfuscate_6Aÿÿ['fieldId'] = $_obfuscate_Iol2Fs8yYvPvnJBshwÿÿ[$_obfuscate_6Aÿÿ['fieldId']];
            }
            else
            {
                $_obfuscate_6Aÿÿ['fieldId'] = $_obfuscate_rsprfS3qDSJ2MvYrvAÿ[$_obfuscate_6Aÿÿ['fieldId']];
            }
            $_obfuscate_6Aÿÿ['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
            $CNOA_DB->db_insert( $_obfuscate_6Aÿÿ, $this->t_set_step_fields );
        }
        $_obfuscate_kdHhV5xyiC7wnDiem5LxSqE9Fe2c4Eÿ = array( );
        $_obfuscate_XfY8n0HVHlugRiyQdwÿÿ = $CNOA_DB->db_select( "*", $this->t_set_step_condition, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' ORDER BY `id` ASC" );
        if ( !is_array( $_obfuscate_XfY8n0HVHlugRiyQdwÿÿ ) )
        {
            $_obfuscate_XfY8n0HVHlugRiyQdwÿÿ = array( );
        }
        foreach ( $_obfuscate_XfY8n0HVHlugRiyQdwÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_sBdvc3n = $_obfuscate_6Aÿÿ['id'];
            unset( $_obfuscate_6Aÿÿ['id'] );
            if ( CNOA_ISSAAS === TRUE )
            {
                unset( $_obfuscate_6Aÿÿ['domainID'] );
            }
            foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_3QYÿ => $_obfuscate_EGUÿ )
            {
                $_obfuscate_6Aÿÿ[$_obfuscate_3QYÿ] = addslashes( $_obfuscate_EGUÿ );
            }
            $_obfuscate_6Aÿÿ['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
            $_obfuscate_6Aÿÿ['name'] = $_obfuscate_Iol2Fs8yYvPvnJBshwÿÿ[$_obfuscate_6Aÿÿ['name']];
            $_obfuscate_kdHhV5xyiC7wnDiem5LxSqE9Fe2c4Eÿ[$_obfuscate_sBdvc3n] = $CNOA_DB->db_insert( $_obfuscate_6Aÿÿ, $this->t_set_step_condition );
        }
        foreach ( $_obfuscate_kdHhV5xyiC7wnDiem5LxSqE9Fe2c4Eÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_XcKeMwÿÿ = $CNOA_DB->db_getone( "*", $this->t_set_step_condition, "WHERE `id`='".$_obfuscate_6Aÿÿ."'" );
            if ( $_obfuscate_XcKeMwÿÿ['pid'] != 0 )
            {
                $CNOA_DB->db_update( array(
                    "pid" => $_obfuscate_kdHhV5xyiC7wnDiem5LxSqE9Fe2c4Eÿ[$_obfuscate_XcKeMwÿÿ['pid']]
                ), $this->t_set_step_condition, "WHERE `id`='".$_obfuscate_6Aÿÿ."'" );
            }
        }
        $_obfuscate_o_IRkEDpxeoÿ = $CNOA_DB->db_select( "*", $this->t_set_step_user, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( !is_array( $_obfuscate_o_IRkEDpxeoÿ ) )
        {
            $_obfuscate_o_IRkEDpxeoÿ = array( );
        }
        foreach ( $_obfuscate_o_IRkEDpxeoÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            unset( $_obfuscate_6Aÿÿ['id'] );
            if ( CNOA_ISSAAS === TRUE )
            {
                unset( $_obfuscate_6Aÿÿ['domainID'] );
            }
            $_obfuscate_6Aÿÿ['kong'] = $_obfuscate_6Aÿÿ['kong'] != 0 ? $_obfuscate_Iol2Fs8yYvPvnJBshwÿÿ[$_obfuscate_6Aÿÿ['kong']] : 0;
            foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_3QYÿ => $_obfuscate_EGUÿ )
            {
                $_obfuscate_6Aÿÿ[$_obfuscate_3QYÿ] = addslashes( $_obfuscate_EGUÿ );
            }
            $_obfuscate_6Aÿÿ['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
            $CNOA_DB->db_insert( $_obfuscate_6Aÿÿ, $this->t_set_step_user );
        }
        unset( $_obfuscate_o_IRkEDpxeoÿ );
        $_obfuscate_WxmaeT80fdbw = $CNOA_DB->db_select( "*", $this->t_s_bingfa_condition, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( !is_array( $_obfuscate_WxmaeT80fdbw ) )
        {
            $_obfuscate_WxmaeT80fdbw = array( );
        }
        foreach ( $_obfuscate_WxmaeT80fdbw as $_obfuscate_6Aÿÿ )
        {
            unset( $_obfuscate_6Aÿÿ['id'] );
            if ( CNOA_ISSAAS === TRUE )
            {
                unset( $_obfuscate_6Aÿÿ['domainID'] );
            }
            $_obfuscate_6Aÿÿ['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
            $CNOA_DB->db_insert( $_obfuscate_6Aÿÿ, $this->t_s_bingfa_condition );
        }
        unset( $_obfuscate_WxmaeT80fdbw );
        $_obfuscate_WO71JHrWfe8IY1qwÿÿ = $CNOA_DB->db_select( "*", "wf_s_step_child_kongjian", "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( !is_array( $_obfuscate_WO71JHrWfe8IY1qwÿÿ ) )
        {
            $_obfuscate_WO71JHrWfe8IY1qwÿÿ = array( );
        }
        foreach ( $_obfuscate_WO71JHrWfe8IY1qwÿÿ as $_obfuscate_6Aÿÿ )
        {
            unset( $_obfuscate_6Aÿÿ['id'] );
            if ( CNOA_ISSAAS === TRUE )
            {
                unset( $_obfuscate_6Aÿÿ['domainID'] );
            }
            $_obfuscate_6Aÿÿ['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
            $_obfuscate_6Aÿÿ['parentKongjian'] = "T_".$_obfuscate_Iol2Fs8yYvPvnJBshwÿÿ[substr( $_obfuscate_6Aÿÿ['parentKongjian'], 2 )];
            $CNOA_DB->db_insert( $_obfuscate_6Aÿÿ, "wf_s_step_child_kongjian" );
        }
        unset( $_obfuscate_WO71JHrWfe8IY1qwÿÿ );
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
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_SIUSR4F6 = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( $_obfuscate_SIUSR4F6 === FALSE )
        {
            msg::callback( FALSE, lang( "beCopyFlowNotExist" ) );
        }
        if ( $_obfuscate_SIUSR4F6 )
        {
            unset( $_obfuscate_SIUSR4F6['flowId'] );
            foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_SIUSR4F6[$_obfuscate_5wÿÿ] = addslashes( $_obfuscate_6Aÿÿ );
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
            if ( $_obfuscate_XkuTFqZ6Tmkÿ == 0 && $_obfuscate_pEvU7Kz2Ywÿÿ != 0 )
            {
                $_obfuscate_eG0q4_wH0Qcÿ = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/doc.history.0.php" );
                if ( file_exists( $_obfuscate_eG0q4_wH0Qcÿ ) )
                {
                    $_obfuscate_3D8Qp35EPoWsdC0ÿ = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_7Jp_oeV8fuZh."/doc.history.0.php" );
                    @mkdirs( @dirname( $_obfuscate_3D8Qp35EPoWsdC0ÿ ) );
                    @copy( $_obfuscate_eG0q4_wH0Qcÿ, $_obfuscate_3D8Qp35EPoWsdC0ÿ );
                }
                $_obfuscate_Tx7M9W = $CNOA_DB->db_select( "*", $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
                if ( !is_array( $_obfuscate_Tx7M9W ) )
                {
                    $_obfuscate_Tx7M9W = array( );
                }
                foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
                {
                    unset( $_obfuscate_6Aÿÿ['id'] );
                    foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_3QYÿ => $_obfuscate_EGUÿ )
                    {
                        $_obfuscate_6Aÿÿ[$_obfuscate_3QYÿ] = addslashes( $_obfuscate_EGUÿ );
                    }
                    $_obfuscate_6Aÿÿ['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
                    $CNOA_DB->db_insert( $_obfuscate_6Aÿÿ, $this->t_set_step );
                }
                $_obfuscate_XfY8n0HVHlugRiyQdwÿÿ = $CNOA_DB->db_select( "*", $this->t_set_step_condition, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
                if ( !is_array( $_obfuscate_XfY8n0HVHlugRiyQdwÿÿ ) )
                {
                    $_obfuscate_XfY8n0HVHlugRiyQdwÿÿ = array( );
                }
                foreach ( $_obfuscate_XfY8n0HVHlugRiyQdwÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
                {
                    unset( $_obfuscate_6Aÿÿ['id'] );
                    foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_3QYÿ => $_obfuscate_EGUÿ )
                    {
                        $_obfuscate_6Aÿÿ[$_obfuscate_3QYÿ] = addslashes( $_obfuscate_EGUÿ );
                    }
                    $_obfuscate_6Aÿÿ['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
                    $CNOA_DB->db_insert( $_obfuscate_6Aÿÿ, $this->t_set_step_condition );
                }
                $_obfuscate_o_IRkEDpxeoÿ = $CNOA_DB->db_select( "*", $this->t_set_step_user, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
                if ( !is_array( $_obfuscate_o_IRkEDpxeoÿ ) )
                {
                    $_obfuscate_o_IRkEDpxeoÿ = array( );
                }
                foreach ( $_obfuscate_o_IRkEDpxeoÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
                {
                    unset( $_obfuscate_6Aÿÿ['id'] );
                    foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_3QYÿ => $_obfuscate_EGUÿ )
                    {
                        $_obfuscate_6Aÿÿ[$_obfuscate_3QYÿ] = addslashes( $_obfuscate_EGUÿ );
                    }
                    $_obfuscate_6Aÿÿ['flowId'] = $_obfuscate_7Jp_oeV8fuZh;
                    $CNOA_DB->db_insert( $_obfuscate_6Aÿÿ, $this->t_set_step_user );
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
            $_obfuscate_VBCv7Qÿÿ = array( );
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_VBCv7Qÿÿ[$_obfuscate_6Aÿÿ['stepId']] = $_obfuscate_6Aÿÿ['stepName'];
            }
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "id", "condition" ), $this->t_s_bingfa_condition, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND `stepId`={$_obfuscate_0Ul8BBkt}" );
            if ( !is_array( $_obfuscate_mPAjEGLn ) )
            {
                $_obfuscate_mPAjEGLn = array( );
            }
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_6Aÿÿ = json_decode( $_obfuscate_6Aÿÿ['condition'], TRUE );
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['text'] = "";
                foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_EGUÿ )
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['text'] .= $_obfuscate_VBCv7Qÿÿ[$_obfuscate_EGUÿ]."+";
                }
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['text'] = substr( $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['text'], 0, -1 );
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
        $_obfuscate_Pm3ZMWpPkgÿÿ = $CNOA_DB->db_select( "*", $this->t_set_field, "WHERE `flowId` = ".$_obfuscate_F4AbnVRh." " );
        $_obfuscate_mPAjEGLn = array( );
        if ( !is_array( $_obfuscate_Pm3ZMWpPkgÿÿ ) )
        {
            $_obfuscate_Pm3ZMWpPkgÿÿ = array( );
        }
        foreach ( $_obfuscate_Pm3ZMWpPkgÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_p5ZWxr4ÿ = json_decode( str_replace( "'", "\"", $_obfuscate_6Aÿÿ['odata'] ), TRUE );
            $_obfuscate_SeV31Qÿÿ['id'] = "T_".$_obfuscate_6Aÿÿ['id'];
            $_obfuscate_SeV31Qÿÿ['name'] = $_obfuscate_6Aÿÿ['name'];
            $_obfuscate_SeV31Qÿÿ['childType'] = $_obfuscate_p5ZWxr4ÿ['dataType'];
            $_obfuscate_mPAjEGLn[] = $_obfuscate_SeV31Qÿÿ;
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _filterConvergence( $_obfuscate_2WmUvLIQx8SCLGgÿ, $_obfuscate_0Ul8BBkt, $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        if ( empty( $_obfuscate_2WmUvLIQx8SCLGgÿ ) )
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
            $_obfuscate_905Hi3cÿ = array( );
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_905Hi3cÿ[] = $_obfuscate_6Aÿÿ['id'];
            }
            unset( $_obfuscate_mPAjEGLn );
            $_obfuscate_6RYLWQÿÿ = array( );
            foreach ( $_obfuscate_2WmUvLIQx8SCLGgÿ as $_obfuscate_6Aÿÿ )
            {
                if ( $_obfuscate_6Aÿÿ['id'] == 0 )
                {
                    $_obfuscate_6RYLWQÿÿ[] = "('".$_obfuscate_F4AbnVRh."','{$_obfuscate_0Ul8BBkt}','{$_obfuscate_6Aÿÿ['condition']}')";
                }
                else
                {
                    unset( $_obfuscate_905Hi3cÿ[array_search( $_obfuscate_6Aÿÿ['id'], $_obfuscate_905Hi3cÿ )] );
                }
            }
            if ( !empty( $_obfuscate_6RYLWQÿÿ ) )
            {
                $_obfuscate_3y0Y = "INSERT INTO ".tname( $this->t_s_bingfa_condition )." (`flowId`,`stepId`,`condition`) VALUES ".implode( ",", $_obfuscate_6RYLWQÿÿ );
                $CNOA_DB->query( $_obfuscate_3y0Y );
            }
            unset( $_obfuscate_6RYLWQÿÿ );
            $_obfuscate_905Hi3cÿ = implode( ",", $_obfuscate_905Hi3cÿ );
            if ( !empty( $_obfuscate_905Hi3cÿ ) )
            {
                $CNOA_DB->db_delete( $this->t_s_bingfa_condition, "WHERE `id` IN (".$_obfuscate_905Hi3cÿ.")" );
            }
            unset( $_obfuscate_905Hi3cÿ );
        }
    }

    private function _filterChild( $_obfuscate_eBU_Sjcÿ, $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $CNOA_DB->db_delete( $this->t_set_step_child_kongjian, "WHERE `flowId` = ".$_obfuscate_F4AbnVRh." AND `cStepId` = {$_obfuscate_0Ul8BBkt} " );
        foreach ( $_obfuscate_eBU_Sjcÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6RYLWQÿÿ = array( );
            $_obfuscate_6RYLWQÿÿ['bangdingFlow'] = $_obfuscate_6Aÿÿ['bangdingFlow'];
            $_obfuscate_6RYLWQÿÿ['childKongjian'] = $_obfuscate_6Aÿÿ['childKongjian'];
            if ( ereg( "D_d_", $_obfuscate_6Aÿÿ['parentKongjian'] ) )
            {
                $_obfuscate_6RYLWQÿÿ['parentKongjian'] = str_replace( "D_d_", "D_", $_obfuscate_6Aÿÿ['parentKongjian'] );
            }
            else
            {
                $_obfuscate_6RYLWQÿÿ['parentKongjian'] = $_obfuscate_6Aÿÿ['parentKongjian'];
            }
            $_obfuscate_6RYLWQÿÿ['arrow'] = $_obfuscate_6Aÿÿ['arrow'];
            $_obfuscate_6RYLWQÿÿ['flowId'] = $_obfuscate_F4AbnVRh;
            $_obfuscate_6RYLWQÿÿ['stepId'] = $_obfuscate_6Aÿÿ['stepId'];
            $_obfuscate_6RYLWQÿÿ['cStepId'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_6RYLWQÿÿ['childType'] = $_obfuscate_6Aÿÿ['childType'];
            $_obfuscate_6RYLWQÿÿ['parentType'] = $_obfuscate_6Aÿÿ['parentType'];
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_set_step_child_kongjian );
        }
    }

    private function _filterDeal( $_obfuscate_rFR1zydggÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $CNOA_DB->db_delete( $this->t_use_deal_way, "WHERE `stepId`='".$_obfuscate_0Ul8BBkt."' AND `flowId`='{$_obfuscate_F4AbnVRh}' " );
        if ( !empty( $_obfuscate_rFR1zydggÿÿ ) )
        {
            $_obfuscate_6RYLWQÿÿ = array( );
            $_obfuscate_6RYLWQÿÿ['stepId'] = $_obfuscate_0Ul8BBkt;
            $_obfuscate_6RYLWQÿÿ['flowId'] = $_obfuscate_F4AbnVRh;
            $_obfuscate_6RYLWQÿÿ['deal'] = $_obfuscate_rFR1zydggÿÿ['deal'];
            if ( $_obfuscate_6RYLWQÿÿ['deal'] )
            {
                $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_use_deal_way );
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
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_6Aÿÿ['flowId']] = $_obfuscate_6Aÿÿ;
        }
        return $_obfuscate_6RYLWQÿÿ;
    }

    public function _getOrderList( )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $this->_getJsonData( TRUE );
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_NlQÿ->makeJsonData( );
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
            $_obfuscate_f517kgÿÿ = intval( $_obfuscate_Vwty ) + 1;
            $_obfuscate_6RYLWQÿÿ = array( );
            $_obfuscate_6RYLWQÿÿ['order'] = $_obfuscate_f517kgÿÿ;
            $CNOA_DB->db_update( $_obfuscate_6RYLWQÿÿ, $this->t_set_flow, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' " );
        }
        msg::callback( TRUE, lang( "successopt" ) );
        exit( );
    }

    private function _taoHong( )
    {
        $_obfuscate_LeS8hwÿÿ = getpar( $_GET, "type" );
        switch ( $_obfuscate_LeS8hwÿÿ )
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
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_Il8i = "index.php?app=wf&func=flow&action=set&modul=flow&task=taoHong&type=getfile&file=".$_obfuscate_6Aÿÿ['name']."&CNOAOASESSID={$_obfuscate_y6jH}";
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['urlView'] = "<a href='javascript:void(0);' class='gridview' onclick='openOfficeForView(\"".$_obfuscate_Il8i."\", \"doc\", \"{$_obfuscate_6Aÿÿ['oldname']}\")'>æµè§ˆ</a>";
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['urlEdit'] = "<a href='javascript:void(0);' class='gridview3 jianju' onclick='openOfficeForEdit_Attach(\"".$_obfuscate_Il8i."\", \"doc\", \"{$_obfuscate_6Aÿÿ['oldname']}\", 1, \"{$_obfuscate_Il8i}&edit=msofficeeditsubmit\", true)'>".lang( "modify" )."</a>";
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_mPAjEGLn;
        $_obfuscate_NlQÿ->total = $CNOA_DB->db_getcount( $this->t_set_taohong );
        echo $_obfuscate_NlQÿ->makeJsonData( );
    }

    private function _getfile( )
    {
        $_obfuscate_6hS1Rwÿÿ = getpar( $_GET, "file" );
        $_obfuscate_6aSoeVqLulQÿ = getpar( $_GET, "edit" );
        $_obfuscate_Il8i = CNOA_PATH_FILE."/common/wf/weboffice/".$_obfuscate_6hS1Rwÿÿ;
        if ( $_obfuscate_6aSoeVqLulQÿ == "msofficeeditsubmit" )
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
        $_obfuscate_VXnKvu82BAÿÿ = TRUE;
        if ( !isset( $_FILES['Filedata'] ) )
        {
            $_obfuscate_mHQL4kA3m08nUiQÿ = lang( "fileTooBigToUpload" );
            $_obfuscate_VXnKvu82BAÿÿ = FALSE;
        }
        else if ( !is_uploaded_file( $_FILES['Filedata']['tmp_name'] ) )
        {
            $_obfuscate_mHQL4kA3m08nUiQÿ = lang( "notNormalFile" );
            $_obfuscate_VXnKvu82BAÿÿ = FALSE;
        }
        else if ( $_FILES['Filedata']['error'] != 0 )
        {
            $_obfuscate_mHQL4kA3m08nUiQÿ = lang( "uploadError" ).":" + $_FILES['Filedata']['error'];
            $_obfuscate_VXnKvu82BAÿÿ = FALSE;
        }
        else
        {
            $_obfuscate_mHQL4kA3m08nUiQÿ = lang( "uploadSucess" );
        }
        $_obfuscate_p9iS3rrNwQQÿ = CNOA_PATH_FILE."/common/wf/weboffice/";
        @mkdirs( $_obfuscate_p9iS3rrNwQQÿ );
        if ( $_obfuscate_VXnKvu82BAÿÿ )
        {
            $_obfuscate_moWVHtDG_Aÿÿ = strtolower( strrchr( $_FILES['Filedata']['name'], "." ) );
            if ( $_obfuscate_moWVHtDG_Aÿÿ == ".doc" || $_obfuscate_moWVHtDG_Aÿÿ == ".docx" )
            {
                $_obfuscate_JTe7jJ4eGW8ÿ = string::rands( 50 ).".cnoa";
                $_obfuscate_OESonJ_jLYcÿ = $_obfuscate_p9iS3rrNwQQÿ."/".$_obfuscate_JTe7jJ4eGW8ÿ;
                @cnoa_move_uploaded_file( $_FILES['Filedata']['tmp_name'], $_obfuscate_OESonJ_jLYcÿ );
                $_obfuscate_6RYLWQÿÿ = array( );
                $_obfuscate_6RYLWQÿÿ['oldname'] = $_FILES['Filedata']['name'];
                $_obfuscate_6RYLWQÿÿ['name'] = $_obfuscate_JTe7jJ4eGW8ÿ;
                $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_set_taohong );
            }
        }
        msg::callback( $_obfuscate_VXnKvu82BAÿÿ, $_obfuscate_mHQL4kA3m08nUiQÿ );
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
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )
            {
                if ( !empty( $_obfuscate_6Aÿÿ['name'] ) )
                {
                    @unlink( CNOA_PATH_FILE."/common/wf/weboffice/".$_obfuscate_6Aÿÿ['name'] );
                }
            }
            msg::callback( TRUE, lang( "successopt" ) );
        }
    }

    private function _checkStepUser( )
    {
        global $CNOA_DB;
        $_obfuscate_3y0Y = "SELECT a.flowid, a.name, b.stepName, b.stepid, c.id FROM cnoa_wf_s_flow AS a \r\n\t\t\t\tLEFT JOIN cnoa_wf_s_step AS b ON a.flowid = b.flowid \r\n\t\t\t\tLEFT JOIN cnoa_wf_s_step_user AS c ON (a.flowid=c.flowid AND b.stepid = c.stepid) \r\n\t\t\t\tWHERE c.type = '' AND b.stepType!=1 AND b.stepType!=3 AND b.stepType!=4 AND b.stepType!=5\r\n\t\t\t\tORDER BY a.flowid,b.stepid";
        $_obfuscate_ammigv8ÿ = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQÿÿ = array( );
        while ( $_obfuscate_SF4ÿ = $CNOA_DB->get_array( $_obfuscate_ammigv8ÿ ) )
        {
            $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_SF4ÿ;
        }
        $_obfuscate_A1jN = "";
        foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_A1jN .= "æµç¨‹[<span style='color:red;'>".$_obfuscate_VgKtFegÿ['name']."</span>]æ­¥éª¤[<span style='color:green;'>{$_obfuscate_VgKtFegÿ['stepName']}</span>]ä¸å­˜åœ¨ç»åŠäºº<br />";
        }
        return $_obfuscate_A1jN;
    }

    private function _checkStepCondition( )
    {
        global $CNOA_DB;
        $_obfuscate_A1jN = $this->_checkStepUser( );
        $_obfuscate_3y0Y = "SELECT a.fieldType, a.ovalue, b.name, c.stepName FROM cnoa_wf_s_step_condition AS a \r\n\t\t\t\tLEFT JOIN cnoa_wf_s_flow AS b ON a.flowid = b.flowid \r\n\t\t\t\tLEFT JOIN cnoa_wf_s_step AS c ON ( a.flowid = c.flowid AND a.stepid = c.stepid ) \r\n\t\t\t\tWHERE a.fieldType != 'nor'";
        $_obfuscate_ammigv8ÿ = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQÿÿ = array( );
        while ( $_obfuscate_SF4ÿ = $CNOA_DB->get_array( $_obfuscate_ammigv8ÿ ) )
        {
            $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_SF4ÿ;
        }
        foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_BcV_lAÿÿ = "æµç¨‹[<span style='color:red;'>".$_obfuscate_VgKtFegÿ['name']."</span>]æ­¥éª¤[<span style='color:green;'>{$_obfuscate_VgKtFegÿ['stepName']}</span>]æ¡ä»¶è®¾ç½®ä¸å­˜åœ¨";
            $_obfuscate_R6z0YAÿÿ = "[<span style='color:blue;'>".$_obfuscate_VgKtFegÿ['ovalue']."</span>]<br />";
            if ( ( $_obfuscate_VgKtFegÿ['fieldType'] == "n_n" || $_obfuscate_VgKtFegÿ['fieldType'] == "d_n" ) && !empty( $_obfuscate_VgKtFegÿ['ovalue'] ) )
            {
                $_obfuscate_gftfagwÿ = $CNOA_DB->db_getcount( "main_user", "WHERE `truename`='".$_obfuscate_VgKtFegÿ['ovalue']."' AND `isSystemUser`=1 AND `workStatusType`=1" );
                if ( empty( $_obfuscate_gftfagwÿ ) )
                {
                    $_obfuscate_A1jN .= "{$_obfuscate_BcV_lAÿÿ}ç”¨æˆ·{$_obfuscate_R6z0YAÿÿ}";
                }
            }
            if ( ( $_obfuscate_VgKtFegÿ['fieldType'] == "n_s" || $_obfuscate_VgKtFegÿ['fieldType'] == "d_s" ) && !empty( $_obfuscate_VgKtFegÿ['ovalue'] ) )
            {
                $_obfuscate_gftfagwÿ = $CNOA_DB->db_getcount( "main_station", "WHERE `name`='".$_obfuscate_VgKtFegÿ['ovalue']."'" );
                if ( empty( $_obfuscate_gftfagwÿ ) )
                {
                    $_obfuscate_A1jN .= "{$_obfuscate_BcV_lAÿÿ}å²—ä½{$_obfuscate_R6z0YAÿÿ}";
                }
            }
            if ( !( $_obfuscate_VgKtFegÿ['fieldType'] == "n_d" ) || !( $_obfuscate_VgKtFegÿ['fieldType'] == "d_d" ) && empty( $_obfuscate_VgKtFegÿ['ovalue'] ) )
            {
                $_obfuscate_gftfagwÿ = $CNOA_DB->db_getcount( "main_struct", "WHERE `name`='".$_obfuscate_VgKtFegÿ['ovalue']."'" );
                if ( empty( $_obfuscate_gftfagwÿ ) )
                {
                    $_obfuscate_A1jN .= "{$_obfuscate_BcV_lAÿÿ}éƒ¨é—¨{$_obfuscate_R6z0YAÿÿ}";
                }
            }
        }
        if ( empty( $_obfuscate_A1jN ) )
        {
            msg::callback( TRUE, "æµç¨‹è®¾ç½®æ²¡é—®é¢˜" );
        }
        else
        {
            $_obfuscate_A1jN = substr( $_obfuscate_A1jN, 0, -6 );
            msg::callback( FALSE, $_obfuscate_A1jN );
        }
    }

}

?>
