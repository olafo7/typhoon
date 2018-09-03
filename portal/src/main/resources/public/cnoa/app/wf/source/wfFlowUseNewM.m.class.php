<?php

class wfFlowUseNewM extends wfCommon
{

    public function run( )
    {
        $_obfuscate_M_5JJwÿÿ = getpar( $_GET, "task", "" );
        switch ( $_obfuscate_M_5JJwÿÿ )
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
            $_obfuscate_IRFhnYwÿ = "`status`=1 AND `tplSort`=0 AND flowType=0 AND `flowId` IN (".implode( ",", $_obfuscate_OTt14hA7OyXA44prAwUÿ ).") ";
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
        $_obfuscate_6RYLWQÿÿ = $CNOA_DB->db_select( array( "signature", "url" ), "system_signature", "WHERE `uid`=".$_obfuscate_7Ri3 );
        echo json_encode( $_obfuscate_6RYLWQÿÿ );
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
        $_obfuscate_Y5hr7Fgÿ[] = array( "sortId" => "-1", "sortName" => "éœ€è¦æˆ‘å‘èµ·çš„å­æµç¨‹" );
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

}

?>
