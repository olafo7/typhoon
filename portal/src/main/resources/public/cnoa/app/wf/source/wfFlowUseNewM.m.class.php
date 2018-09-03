<?php

class wfFlowUseNewM extends wfCommon
{

    public function run( )
    {
        $_obfuscate_M_5JJw�� = getpar( $_GET, "task", "" );
        switch ( $_obfuscate_M_5JJw�� )
        {
        case "getJsonData" :
            $this->_getJsonData( );
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
        case "loadPage" :
            $this->_loadPage( );
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
            $_obfuscate_IRFhnYw� = "`status`=1 AND `tplSort`=0 AND flowType=0 AND `flowId` IN (".implode( ",", $_obfuscate_OTt14hA7OyXA44prAwU� ).") ";
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
        $_obfuscate_6RYLWQ�� = $CNOA_DB->db_select( array( "signature", "url" ), "system_signature", "WHERE `uid`=".$_obfuscate_7Ri3 );
        echo json_encode( $_obfuscate_6RYLWQ�� );
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
        $_obfuscate_Y5hr7Fg�[] = array( "sortId" => "-1", "sortName" => "需要我发起的子流程" );
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

}

?>
