<?php

class wfTodoSendNextStep extends wfSendNextStep
{

    private $_dealtype = NULL;
    private $_say = NULL;
    private $_wfCache = NULL;
    public $stepOfEnd = FALSE;
    private $way = NULL;

    public function __construct( )
    {
        $this->uFlowId = ( integer )getpar( $_POST, "uFlowId", 0 );
        $this->stepId = ( integer )getpar( $_POST, "step" );
        $this->autoStepId = ( integer )getpar( $_POST, "step" );
        $FN_-2147483622( );
        global $CNOA_DB;
        $this->_dealtype = getpar( $_POST, "type", "" );
        $this->_say = getpar( $_POST, "say", "" );
        ( $this->uFlowId );
        $this->_wfCache = new wfCache( );
        $this->flowNumber = $this->uFlowInfo['flowNumber'];
        $this->flowName = $this->uFlowInfo['flowName'];
        $this->flowInfo = $this->_wfCache->getFlow( );
        $this->flowId = ( integer )$this->flowInfo['flowId'];
        $this->flowType = ( integer )$this->flowInfo['flowType'];
        $this->tplSort = ( integer )$this->flowInfo['tplSort'];
        $_obfuscate_hXVTMt5XyOk? = $this->_checkDraft( $this->flowId, $this->uFlowId, $this->stepId );
        if ( $_obfuscate_hXVTMt5XyOk? )
        {
            unlink( $_obfuscate_hXVTMt5XyOk? );
        }
        $this->stepInfo = $this->_wfCache->getStepByStepId( $this->stepId );
        $this->nextStepInfo = $this->_wfCache->getStepByStepId( $this->nextStepId );
        $_obfuscate_gftfagw? = $CNOA_DB->db_getcount( $this->t_use_step, "WHERE `uFlowId`=".$this->uFlowId );
        if ( $_obfuscate_gftfagw? == 1 )
        {
            $this->way = $CNOA_DB->db_getfield( "deal", $this->t_use_deal_way, "WHERE `stepId`=".$this->nextStepId." AND `flowId`={$this->flowId}" );
        }
        else
        {
            $this->way = $CNOA_DB->db_getfield( "deal", $this->t_use_deal_way, "WHERE `stepId`=".$this->stepId." AND `flowId`={$this->flowId}" );
        }
        $this->startEngine( );
        $this->_sendNextStep( );
    }

    private function _sendNextStep( )
    {
        global $CNOA_DB;
        $_obfuscate_uGltphXQjCRWoA?? = array( );
        $_obfuscate_0cocFTVhmhKt8lw? = array( );
        foreach ( $this->_wfCache->getStepFields( $this->stepId, self::FIELD_RULE_NORMAL ) as $_obfuscate_afRCPNY? => $_obfuscate_MrgdvEX4lw?? )
        {
            if ( !( $_obfuscate_MrgdvEX4lw??['edit'] == 1 ) || !( $_obfuscate_MrgdvEX4lw??['write'] == 1 ) )
            {
                $_obfuscate_uGltphXQjCRWoA??[] = $_obfuscate_MrgdvEX4lw??['fieldId'];
            }
        }
        foreach ( $this->_wfCache->getStepFields( $this->stepId, self::FIELD_RULE_DETAIL ) as $_obfuscate__twqKj4? => $_obfuscate_Yi1euVYyBw?? )
        {
            if ( !( $_obfuscate_Yi1euVYyBw??['edit'] == 1 ) || !( $_obfuscate_Yi1euVYyBw??['write'] == 1 ) )
            {
                $_obfuscate_0cocFTVhmhKt8lw?[] = $_obfuscate_Yi1euVYyBw??['fieldId'];
            }
        }
        $this->_saveData( $_obfuscate_uGltphXQjCRWoA??, $_obfuscate_0cocFTVhmhKt8lw? );
        unset( $_obfuscate_uGltphXQjCRWoA?? );
        unset( $_obfuscate_0cocFTVhmhKt8lw? );
        $this->_updateCurStepInfo( );
        $this->addEvent( $this->_say, $this->_dealtype );
        $this->_updateNotice( );
        if ( $this->stepInfo['allowSms'] == "1" )
        {
            $this->_saveSms( );
        }
        if ( $this->nextStepInfo['stepType'] == self::STEP_TYPE_END )
        {
            $this->stepOfEnd( );
            $this->stepOfEnd = TRUE;
        }
        else if ( $this->nextStepInfo['stepType'] == self::STEP_TYPE_BINGFA )
        {
            if ( !empty( $this->allNextStepUid ) )
            {
                $_obfuscate_1yTCLz0QeA0jcv3T7A?? = json_decode( $this->nextStepInfo['nextStep'], TRUE );
                $this->allNextStepInfo = $this->_wfCache->getStepInfoByIdArr( $_obfuscate_1yTCLz0QeA0jcv3T7A?? );
                unset( $_obfuscate_1yTCLz0QeA0jcv3T7A?? );
                $this->stepOfBingfa( );
            }
        }
        else if ( $this->nextStepInfo['stepType'] == self::STEP_TYPE_CONVERGENCE )
        {
            $this->_stepOfConvergence( );
        }
        else
        {
            $this->stepOfOther( );
        }
        $this->api_autoFenfa( $this->flowId, $this->uFlowId, $this->autoStepId );
        $this->updateuFlowInfo( );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3604, "???????¦Ì??¡§?????¦Ì??¡§?????¡ì¡ã[".$this->uFlowInfo['flowName']."]?????¡¤[".$this->uFlowInfo['flowNumber']."]" );
    }

    private function _updateCurStepInfo( )
    {
        global $CNOA_DB;
        $_obfuscate_VBCv7Q?? = array( );
        if ( $this->_dealtype == "keep" )
        {
            $_obfuscate_VBCv7Q??['status'] = 4;
            $this->_say = getpar( $_POST, "say", "?????????¨¨¡ì?" );
        }
        else
        {
            $_obfuscate_VBCv7Q??['status'] = 2;
            $this->_say = getpar( $_POST, "say", "??????" );
        }
        $_obfuscate_VBCv7Q??['etime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_VBCv7Q??['say'] = $this->_say;
        $_obfuscate_VBCv7Q??['nStepId'] = $this->nextStepId;
        $_obfuscate_VBCv7Q??['timegap'] = $_obfuscate_VBCv7Q??['etime'] - $CNOA_DB->db_getfield( "stime", $this->t_use_step, "WHERE `uFlowId`='".$this->uFlowId."' AND `uStepId`='{$this->stepId}' AND `status`=1 ORDER BY `id` DESC" );
        $_obfuscate_VBCv7Q??['dealUid'] = $this->uid;
        if ( $this->way == 1 )
        {
            $CNOA_DB->db_update( $_obfuscate_VBCv7Q??, $this->t_use_step, "WHERE `uFlowId`='".$this->uFlowId."' AND `uStepId`='{$this->stepId}' AND `status`=1 AND (`uid`={$this->uid} OR `proxyUid`={$this->uid}) ORDER BY `id` DESC" );
            $_obfuscate_VBCv7Q??['dealUid'] = 0;
        }
        $CNOA_DB->db_update( $_obfuscate_VBCv7Q??, $this->t_use_step, "WHERE `uFlowId`='".$this->uFlowId."' AND `uStepId`='{$this->stepId}' AND `status`=1 ORDER BY `id` DESC" );
    }

    private function _updateNotice( )
    {
        global $CNOA_DB;
        $thisStep = $CNOA_DB->db_select( array( "id" ), $this->t_use_step, "WHERE `uFlowId`='".$this->uFlowId."' AND `uStepId`='{$this->stepId}' ORDER BY `id` DESC" );
        foreach ( $thisStep as $_obfuscate_VgKtFeg? )
        {
            $this->doneAll( "both", $_obfuscate_VgKtFeg?['id'], "trust", 0, TRUE );
            $this->doneAll( "both", $_obfuscate_VgKtFeg?['id'], "todo", 0, TRUE );
            $this->doneAll( "notice", $_obfuscate_VgKtFeg?['id'], "huiqian", 1 );
            unset( $_obfuscate_VgKtFeg? );
        }
        unset( $thisStep );
        $_obfuscate_EyPNUV5TiETe = $CNOA_DB->db_select( array( "id" ), $this->t_use_step_huiqian, "WHERE `uFlowId` = '".$this->uFlowId."' AND `stepId` = '{$this->stepId}' " );
        if ( !is_array( $_obfuscate_EyPNUV5TiETe ) )
        {
            $_obfuscate_EyPNUV5TiETe = array( );
        }
        foreach ( $_obfuscate_EyPNUV5TiETe as $_obfuscate_6A?? )
        {
            $this->doneAll( "both", $_obfuscate_6A??['id'], "huiqian" );
        }
    }

    private function _stepOfConvergence( )
    {
        global $CNOA_DB;
        $_obfuscate_dw4x = $this->_wfCache->getFlowXML( );
        $_obfuscate_sc7AoZlouuA? = xml2array( stripslashes( $_obfuscate_dw4x ), 1, "mxGraphModel" );
        $_obfuscate_t3xmZlf1zM0? = $_obfuscate_sc7AoZlouuA?['mxGraphModel']['root']['mxCell'];
        $_obfuscate_h_MzZw?? = array( );
        foreach ( $_obfuscate_t3xmZlf1zM0? as $_obfuscate_6A?? )
        {
            if ( !empty( $_obfuscate_6A??['attr']['mark'] ) )
            {
                $_obfuscate_h_MzZw??[$_obfuscate_6A??['attr']['id']] = $_obfuscate_6A??['attr']['mark'];
            }
        }
        $_obfuscate_OHHSWD6twA?? = str_replace( "convergence", "bingfa", $_obfuscate_h_MzZw??[$this->nextStepInfo['stepId']] );
        if ( !is_array( $_obfuscate_h_MzZw?? ) )
        {
            $_obfuscate_h_MzZw?? = array( );
        }
        foreach ( $_obfuscate_h_MzZw?? as $_obfuscate_5w?? => $_obfuscate_6A?? )
        {
            if ( $_obfuscate_6A?? == $_obfuscate_OHHSWD6twA?? )
            {
                $this->stepId = $_obfuscate_5w??;
            }
        }
        unset( $_obfuscate_dw4x );
        unset( $_obfuscate_sc7AoZlouuA? );
        unset( $_obfuscate_t3xmZlf1zM0? );
        unset( $_obfuscate_h_MzZw?? );
        unset( $_obfuscate_OHHSWD6twA?? );
        $_obfuscate_x1tcCgpyXQ?? = $this->_wfCache->getAllStep( );
        $_obfuscate_Msn0UGqP4kc? = array( );
        foreach ( $_obfuscate_x1tcCgpyXQ?? as $_obfuscate_6A?? )
        {
            $_obfuscate_MnVVbyZQFVw? = json_decode( $_obfuscate_6A??['nextStep'], TRUE );
            if ( !is_array( $_obfuscate_MnVVbyZQFVw? ) )
            {
                $_obfuscate_MnVVbyZQFVw? = array( );
            }
            foreach ( $_obfuscate_MnVVbyZQFVw? as $_obfuscate_EGU? )
            {
                if ( !( $_obfuscate_EGU? == $this->nextStepInfo['stepId'] ) )
                {
                    continue;
                }
                $_obfuscate_Msn0UGqP4kc?[] = $_obfuscate_6A??['stepId'];
                break;
            }
        }
        unset( $_obfuscate_x1tcCgpyXQ?? );
        $_obfuscate_Msn0UGqP4kc? = implode( ",", $_obfuscate_Msn0UGqP4kc? );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "id", "uStepId", "status", "uid" ), $this->t_use_step, "WHERE `uFlowId`=".$this->uFlowId." AND `uStepId` IN ({$_obfuscate_Msn0UGqP4kc?})" );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_bOtGH1wcGbtvr4? = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A?? )
        {
            if ( isset( $_obfuscate_bOtGH1wcGbtvr4?[$_obfuscate_6A??['uStepId']] ) )
            {
                if ( $_obfuscate_bOtGH1wcGbtvr4?[$_obfuscate_6A??['uStepId']]['id'] < $_obfuscate_6A??['id'] )
                {
                    $_obfuscate_bOtGH1wcGbtvr4?[$_obfuscate_6A??['uStepId']] = $_obfuscate_6A??;
                }
            }
            else
            {
                $_obfuscate_bOtGH1wcGbtvr4?[$_obfuscate_6A??['uStepId']] = $_obfuscate_6A??;
            }
        }
        unset( $_obfuscate_mPAjEGLn );
        $_obfuscate_Xw3EIVHJ92w? = TRUE;
        $_obfuscate_xv5Nfm7aigk? = $_obfuscate_td3BMkoeV0sT = array( );
        foreach ( $_obfuscate_bOtGH1wcGbtvr4? as $_obfuscate_6A?? )
        {
            if ( $_obfuscate_6A??['uid'] != 0 )
            {
                if ( $_obfuscate_6A??['status'] != 2 && $_obfuscate_6A??['status'] != 4 )
                {
                    $_obfuscate_Xw3EIVHJ92w? = FALSE;
                }
                else
                {
                    array_push( &$_obfuscate_xv5Nfm7aigk?, $_obfuscate_6A??['uStepId'] );
                }
                $_obfuscate_td3BMkoeV0sT[$_obfuscate_6A??['uStepId']] = $_obfuscate_6A??['id'];
            }
        }
        unset( $_obfuscate_bOtGH1wcGbtvr4? );
        if ( !$_obfuscate_Xw3EIVHJ92w? )
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "condition" ), $this->t_s_bingfa_condition, "WHERE `flowId`=".$this->nextStepInfo['flowId'] );
            if ( !is_array( $_obfuscate_mPAjEGLn ) )
            {
                $_obfuscate_mPAjEGLn = array( );
            }
            $_obfuscate_XP4WpjIMhOSD = array( );
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A?? )
            {
                $_obfuscate_XP4WpjIMhOSD[] = json_decode( $_obfuscate_6A??['condition'], TRUE );
            }
            if ( 0 < count( $_obfuscate_XP4WpjIMhOSD ) )
            {
                foreach ( $_obfuscate_XP4WpjIMhOSD as $_obfuscate_6A?? )
                {
                    if ( !( count( array_diff( $_obfuscate_6A??, $_obfuscate_xv5Nfm7aigk? ) ) == 0 ) )
                    {
                        continue;
                    }
                    $_obfuscate_Xw3EIVHJ92w? = TRUE;
                    $CNOA_DB->db_update( array( "status" => 2 ), $this->t_use_step, "WHERE `id` IN (".implode( ",", $_obfuscate_td3BMkoeV0sT ).")" );
                    break;
                }
            }
        }
        if ( $_obfuscate_Xw3EIVHJ92w? )
        {
            $this->stepOfOther( );
            foreach ( $_obfuscate_xv5Nfm7aigk? as $_obfuscate_6A?? )
            {
                if ( array_key_exists( $_obfuscate_6A??, $_obfuscate_td3BMkoeV0sT ) )
                {
                    unset( $_obfuscate_td3BMkoeV0sT[$_obfuscate_6A??] );
                }
            }
            $_obfuscate_td3BMkoeV0sT = array_values( $_obfuscate_td3BMkoeV0sT );
            if ( count( $_obfuscate_td3BMkoeV0sT ) != 0 )
            {
                $this->deleteNotice( "both", $_obfuscate_td3BMkoeV0sT, "todo" );
                $CNOA_DB->db_delete( "system_notice", "WHERE `fromtable`=\"wf_u_step\" AND `sourceid` IN (".implode( ",", $_obfuscate_td3BMkoeV0sT ).")" );
            }
        }
    }

}

?>
