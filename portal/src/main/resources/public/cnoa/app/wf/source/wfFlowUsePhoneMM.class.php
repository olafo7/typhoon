<?php

class wfFlowUsePhoneMM extends wfForm
{

    private static $map_level = array
    (
        0 => "æ™®é€š",
        1 => "é‡è¦",
        2 => "éžå¸¸é‡è¦"
    );
    private $uid = NULL;
    private $flowId = NULL;
    private $uFlowId = NULL;
    private $uStepId = NULL;
    private $flowType = NULL;
    private $tplSort = NULL;
    private $attach = NULL;
    private $wfAttachConfig = array( );
    private $hqid = NULL;
    private $fenfaid = NULL;
    private $rule = NULL;
    private $flowFields = array( );

    const FROM_NEW = "new";
    const FROM_TODO = "todo";
    const FROM_DONE = "done";

    public function run( )
    {
        $_obfuscate_M_5JJwÿÿ = getpar( $_GET, "task", "loadPage" );
        switch ( $_obfuscate_M_5JJwÿÿ )
        {
        case "loadPage" :
            $this->_loadPage( );
            exit( );
        case "loadFlowInfo" :
            $this->_loadFlowForm( );
            exit( );
        case "getRelFlow" :
            $this->_getRelFlow( );
            exit( );
        case "getBusinessData" :
            $this->_getBusinessData( );
        }
        exit( );
    }

    private function _loadPage( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/newFlowForm.m.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQÿÿ );
        exit( );
    }

    private function _loadFlowForm( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $this->uid = $CNOA_SESSION->get( "UID" );
        $this->from = getpar( $_POST, "from" );
        $this->flowId = intval( getpar( $_POST, "flowId" ) );
        $this->uFlowId = intval( getpar( $_POST, "uFlowId" ) );
        $this->uStepId = intval( getpar( $_POST, "uStepId" ) );
        $this->flowType = intval( getpar( $_POST, "flowType", 0 ) );
        $this->tplSort = intval( getpar( $_POST, "tplSort" ), 0 );
        $this->finish = getpar( $_POST, "isfinish", "" );
        if ( !$this->from )
        {
            if ( !empty( $this->uFlowId ) || !empty( $this->uStepId ) )
            {
                $this->from = self::FROM_TODO;
            }
            else if ( !empty( $this->flowId ) )
            {
                $this->from = self::FROM_NEW;
            }
        }
        if ( $this->finish == "finish" )
        {
            $this->from = self::FROM_TODO;
        }
        $_obfuscate_wvQILQÿÿ = $CNOA_DB->db_getone( array( "id" ), "wf_u_huiqian", "WHERE uFlowId=".$this->uFlowId." AND stepId={$this->uStepId} AND touid={$this->uid}" );
        if ( !is_array( $_obfuscate_wvQILQÿÿ ) )
        {
            $_obfuscate_wvQILQÿÿ = array( );
        }
        if ( 0 < count( $_obfuscate_wvQILQÿÿ ) )
        {
            $this->hqid = $_obfuscate_wvQILQÿÿ;
        }
        else
        {
            $this->hqid = "";
        }
        $_obfuscate_dTuAPTvj1Aÿÿ = $CNOA_DB->db_getone( array( "uFenfaId" ), "wf_u_fenfa", "WHERE uFlowId=".$this->uFlowId." AND stepId={$this->uStepId} AND touid={$this->uid}" );
        if ( !is_array( $_obfuscate_dTuAPTvj1Aÿÿ ) )
        {
            $_obfuscate_dTuAPTvj1Aÿÿ = array( );
        }
        if ( 0 < count( $_obfuscate_dTuAPTvj1Aÿÿ ) )
        {
            $this->fenfaid = $_obfuscate_dTuAPTvj1Aÿÿ;
        }
        else
        {
            $this->fenfaid = "";
        }
        $_obfuscate_tjILu7ZH = array( "allowAttachAdd", "allowAttachEdit", "allowAttachDelete", "allowAttachView", "allowAttachDown", "allowHqAttachView", "allowHqAttachEdit", "allowHqAttachAdd", "allowHqAttachDelete", "allowHqAttachDown" );
        $_obfuscate_1XF5zCVrBCqE = $CNOA_DB->db_getone( $_obfuscate_tjILu7ZH, "wf_s_step", "WHERE flowId=".$this->flowId." AND stepId = {$this->uStepId}" );
        if ( !array(
            $_obfuscate_1XF5zCVrBCqE
        ) )
        {
            $_obfuscate_1XF5zCVrBCqE = array( );
        }
        $_obfuscate_1XF5zCVrBCqE['allowAttachDelete'] = 0;
        $_obfuscate_1XF5zCVrBCqE['allowHqAttachDelete'] = 0;
        $this->wfAttachConfig = $_obfuscate_1XF5zCVrBCqE;
        $this->user_sel_array = array( );
        $_obfuscate_o5fQ1gÿÿ['flowInfo'] = $this->_getFlowInfo( );
        $_obfuscate_o5fQ1gÿÿ['permit'] = $this->_getStepPermit( );
        $_obfuscate_o5fQ1gÿÿ['attach'] = $this->_getAttach( $_obfuscate_o5fQ1gÿÿ['permit'] );
        $_obfuscate_o5fQ1gÿÿ['formInfo'] = $this->_getFromFields( );
        $_obfuscate_QHb7ty385uUoOy3M = $CNOA_DB->db_getfield( "nameDisallowBlank", "wf_s_flow", " WHERE `flowId`=".$this->flowId );
        $_obfuscate_o5fQ1gÿÿ['permit']['nameDisallowBlank'] = ( integer )$_obfuscate_QHb7ty385uUoOy3M == 1 ? TRUE : FALSE;
        $_obfuscate_xH1w2Nq_Hqkÿ = $this->_getRelFlowSum( );
        $_obfuscate_o5fQ1gÿÿ['permit']['allowRelateFlow'] = $_obfuscate_xH1w2Nq_Hqkÿ['allowRelateFlow'];
        $_obfuscate_o5fQ1gÿÿ['permit']['showChildAlert'] = $_obfuscate_xH1w2Nq_Hqkÿ['showChildAlert'];
        $this->_changeFenfaIsRead( $this->uid );
        echo json_encode( $_obfuscate_o5fQ1gÿÿ );
    }

    private function _getRelFlowSum( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_3y0Y = "SELECT COUNT(DISTINCT `uFlowId`) AS total FROM ".tname( $this->t_use_step_child_flow ).( " WHERE (`puFlowId` = ".$this->uFlowId." AND `uFlowId`!=0) OR `uFlowId`={$this->uFlowId}" );
        $_obfuscate_j9sJesÿ = $CNOA_DB->get_one( $_obfuscate_3y0Y );
        $_obfuscate_eVTMIa1A = array( );
        if ( 0 < $_obfuscate_j9sJesÿ['total'] )
        {
            $_obfuscate_eVTMIa1A['allowRelateFlow'] = TRUE;
        }
        else
        {
            $_obfuscate_eVTMIa1A['allowRelateFlow'] = FALSE;
        }
        ( $this->uFlowId );
        $_obfuscate_e53ODz04JQÿÿ = new wfCache( );
        $_obfuscate_5NhzjnJq_f8ÿ = $CNOA_DB->db_getone( array( "uid", "proxyUid", "childFlow", "stepType" ), $this->t_use_step, "WHERE `uFlowId`=".$this->uFlowId." AND `uStepId`={$this->uStepId} AND (`uid`={$_obfuscate_7Ri3} OR `proxyUid`={$_obfuscate_7Ri3}) ORDER BY `id` DESC" );
        $_obfuscate_eVTMIa1A['showChildAlert'] = FALSE;
        if ( $_obfuscate_5NhzjnJq_f8ÿ['childFlow'] == 1 )
        {
            $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->t_use_step_child_flow, "WHERE `puFlowId` = ".$this->uFlowId." AND `stepId` = {$this->uStepId} " );
            if ( empty( $_obfuscate_Ybai ) )
            {
                $_obfuscate_ibEsWI9S['puFlowId'] = $this->uFlowId;
                $_obfuscate_ibEsWI9S['stepId'] = $this->uStepId;
                $_obfuscate_ibEsWI9S['postuid'] = $_obfuscate_7Ri3;
                $_obfuscate_Tx7M9W = $_obfuscate_e53ODz04JQÿÿ->getStepByStepId( $this->uStepId );
                $_obfuscate_MnVVbyZQFVwÿ = json_decode( $_obfuscate_Tx7M9W['nextStep'], TRUE );
                if ( !is_array( $_obfuscate_MnVVbyZQFVwÿ ) )
                {
                    $_obfuscate_MnVVbyZQFVwÿ = array( );
                }
                foreach ( $_obfuscate_MnVVbyZQFVwÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
                {
                    $_obfuscate_SeV31Qÿÿ = $_obfuscate_e53ODz04JQÿÿ->getStepByStepId( $_obfuscate_6Aÿÿ );
                    if ( $_obfuscate_SeV31Qÿÿ['stepType'] == 7 )
                    {
                        $_obfuscate_J9i4sncOcwÿÿ = substr( $_obfuscate_SeV31Qÿÿ['bingids'], 0, -1 );
                        if ( !empty( $_obfuscate_J9i4sncOcwÿÿ ) )
                        {
                            $_obfuscate_SIUSR4F6 = $CNOA_DB->db_select( array( "name", "flowId" ), $this->t_set_flow, "WHERE `flowId` IN (".$_obfuscate_J9i4sncOcwÿÿ.") " );
                            foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_snMÿ )
                            {
                                $_obfuscate_ibEsWI9S['flowId'] = $_obfuscate_snMÿ['flowId'];
                                $_obfuscate_ibEsWI9S['faqiFlow'] = $_obfuscate_SeV31Qÿÿ['faqiFlow'];
                                $_obfuscate_ibEsWI9S['endFlow'] = $_obfuscate_SeV31Qÿÿ['endFlow'];
                                $_obfuscate_ibEsWI9S['sharefile'] = $_obfuscate_SeV31Qÿÿ['sharefile'];
                                $CNOA_DB->db_insert( $_obfuscate_ibEsWI9S, $this->t_use_step_child_flow );
                            }
                        }
                    }
                }
            }
            $_obfuscate_pcmxgqXbvl0yPCL_KHMÿ = getpar( $_POST, "childSeeParent", "" );
            if ( empty( $_obfuscate_pcmxgqXbvl0yPCL_KHMÿ ) )
            {
                $_obfuscate_zWYc3vx7p7TyMq4W = $CNOA_DB->db_getcount( $this->t_use_step_child_flow, "WHERE `puFlowId` = ".$this->uFlowId."  AND `stepId` = {$this->uStepId} AND `status` = 0 " );
                if ( empty( $_obfuscate_zWYc3vx7p7TyMq4W ) )
                {
                    $_obfuscate_eVTMIa1A['showChildAlert'] = FALSE;
                    return $_obfuscate_eVTMIa1A;
                }
                $_obfuscate_eVTMIa1A['showChildAlert'] = TRUE;
            }
        }
        return $_obfuscate_eVTMIa1A;
    }

    private function _getRelFlow( )
    {
        global $CNOA_DB;
        $this->uFlowId = intval( getpar( $_POST, "uFlowId", 0 ) );
        if ( $this->uFlowId == 0 )
        {
            msg::showerror( "æ²¡æœ‰é€‰æ‹©éœ€è¦åŠžç†çš„æµç¨‹" );
        }
        $_obfuscate_7K2adqbGwgÿÿ = $CNOA_DB->db_select( array( "puFlowId", "uFlowId" ), $this->t_use_step_child_flow, "WHERE (`puFlowId` = ".$this->uFlowId." AND `uFlowId`!=0) OR `uFlowId`={$this->uFlowId}" );
        $_obfuscate_vYhijxiHce2BjAÿÿ[] = $this->uFlowId;
        if ( !is_array( $_obfuscate_7K2adqbGwgÿÿ ) )
        {
            $_obfuscate_7K2adqbGwgÿÿ = array( );
        }
        $_obfuscate_TlvKhtsoOQÿÿ = array( );
        foreach ( $_obfuscate_7K2adqbGwgÿÿ as $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['puFlowId'] == $this->uFlowId )
            {
                $_obfuscate_vYhijxiHce2BjAÿÿ[] = $_obfuscate_6Aÿÿ['uFlowId'];
                $_obfuscate_TlvKhtsoOQÿÿ[] = $_obfuscate_6Aÿÿ['uFlowId'];
            }
            if ( $_obfuscate_6Aÿÿ['uFlowId'] == $this->uFlowId )
            {
                $_obfuscate_vYhijxiHce2BjAÿÿ[] = $_obfuscate_6Aÿÿ['puFlowId'];
                $_obfuscate_TlvKhtsoOQÿÿ[] = $_obfuscate_6Aÿÿ['puFlowId'];
            }
        }
        while ( 0 < count( $_obfuscate_TlvKhtsoOQÿÿ ) )
        {
            $_obfuscate_TlvKhtsoOQÿÿ = implode( ",", $_obfuscate_TlvKhtsoOQÿÿ );
            $_obfuscate_7K2adqbGwgÿÿ = $CNOA_DB->db_select( array( "puFlowId", "uFlowId" ), $this->t_use_step_child_flow, "WHERE (`puFlowId` IN (".$_obfuscate_TlvKhtsoOQÿÿ.") AND `uFlowId`!=0) OR `uFlowId` IN ({$_obfuscate_TlvKhtsoOQÿÿ})" );
            if ( !is_array( $_obfuscate_7K2adqbGwgÿÿ ) && !( 0 < count( $_obfuscate_7K2adqbGwgÿÿ ) ) )
            {
                $_obfuscate_TlvKhtsoOQÿÿ = array( );
                foreach ( $_obfuscate_7K2adqbGwgÿÿ as $_obfuscate_6Aÿÿ )
                {
                    if ( !in_array( $_obfuscate_6Aÿÿ['puFlowId'], $_obfuscate_vYhijxiHce2BjAÿÿ ) )
                    {
                        $_obfuscate_vYhijxiHce2BjAÿÿ[] = $_obfuscate_6Aÿÿ['puFlowId'];
                        $_obfuscate_TlvKhtsoOQÿÿ[] = $_obfuscate_6Aÿÿ['puFlowId'];
                    }
                    if ( !in_array( $_obfuscate_6Aÿÿ['uFlowId'], $_obfuscate_vYhijxiHce2BjAÿÿ ) )
                    {
                        $_obfuscate_vYhijxiHce2BjAÿÿ[] = $_obfuscate_6Aÿÿ['uFlowId'];
                        $_obfuscate_TlvKhtsoOQÿÿ[] = $_obfuscate_6Aÿÿ['uFlowId'];
                    }
                }
            }
        }
        unset( $_obfuscate_7K2adqbGwgÿÿ );
        unset( $_obfuscate_TlvKhtsoOQÿÿ );
        array_shift( &$_obfuscate_vYhijxiHce2BjAÿÿ );
        if ( 0 < count( $_obfuscate_vYhijxiHce2BjAÿÿ ) )
        {
            $_obfuscate_vYhijxiHce2BjAÿÿ = implode( ",", $_obfuscate_vYhijxiHce2BjAÿÿ );
            $_obfuscate_J3xoGDNkbSJolvqe = $CNOA_DB->db_select( array( "uFlowId", "flowId", "flowNumber", "tplSort", "status" ), $this->t_use_flow, "WHERE `uFlowId` IN (".$_obfuscate_vYhijxiHce2BjAÿÿ.")" );
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "uFlowId", "uStepId", "etime" ), $this->t_use_step, "WHERE `uFlowId` IN (".$_obfuscate_vYhijxiHce2BjAÿÿ.")" );
            if ( !is_array( $_obfuscate_mPAjEGLn ) )
            {
                $_obfuscate_mPAjEGLn = array( );
            }
            $_obfuscate_EAma1UOylRWnspih = array( );
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                if ( isset( $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6Aÿÿ['uFlowId']] ) )
                {
                    if ( $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6Aÿÿ['uFlowId']]['etime'] < $_obfuscate_6Aÿÿ['etime'] )
                    {
                        $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6Aÿÿ['uFlowId']] = $_obfuscate_6Aÿÿ;
                    }
                }
                else
                {
                    $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6Aÿÿ['uFlowId']] = $_obfuscate_6Aÿÿ;
                }
            }
            if ( !is_array( $_obfuscate_J3xoGDNkbSJolvqe ) )
            {
                $_obfuscate_J3xoGDNkbSJolvqe = array( );
            }
            foreach ( $_obfuscate_J3xoGDNkbSJolvqe as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_J3xoGDNkbSJolvqe[$_obfuscate_5wÿÿ]['step'] = $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6Aÿÿ['uFlowId']]['uStepId'];
                $_obfuscate_EPBXIrrI[] = $_obfuscate_6Aÿÿ['flowId'];
            }
            if ( 0 < count( $_obfuscate_EPBXIrrI ) )
            {
                $_obfuscate_EPBXIrrI = implode( ",", $_obfuscate_EPBXIrrI );
                $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "flowId", "flowType" ), $this->t_set_flow, "WHERE `flowId` IN (".$_obfuscate_EPBXIrrI.")" );
                if ( !is_array( $_obfuscate_mPAjEGLn ) )
                {
                    $_obfuscate_mPAjEGLn = array( );
                }
                $_obfuscate_EPBXIrrI = array( );
                foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )
                {
                    $_obfuscate_EPBXIrrI[$_obfuscate_6Aÿÿ['flowId']] = $_obfuscate_6Aÿÿ['flowType'];
                }
                foreach ( $_obfuscate_J3xoGDNkbSJolvqe as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
                {
                    $_obfuscate_J3xoGDNkbSJolvqe[$_obfuscate_5wÿÿ]['flowType'] = $_obfuscate_EPBXIrrI[$_obfuscate_6Aÿÿ['flowId']];
                }
                unset( $_obfuscate_EPBXIrrIAMD0 );
            }
            unset( $_obfuscate_mPAjEGLn );
            unset( $_obfuscate_EAma1UOylRWnspih );
        }
        else
        {
            $_obfuscate_J3xoGDNkbSJolvqe = array( );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_J3xoGDNkbSJolvqe;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _getFlowInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7qDAYo85aGAÿ = array( );
        $_obfuscate_GRtq_c1sXSMthq47 = $CNOA_DB->db_getfield( "bindfunction", $this->t_set_flow, " WHERE `flowId`=".$this->flowId );
        if ( $this->from === self::FROM_TODO || $this->from === self::FROM_DONE )
        {
            $_obfuscate_3y0Y = "SELECT f.uid, f.flowId, f.flowNumber, f.flowName, f.level, f.reason, f.posttime, f.htmlFormContent, f.changeFlowInfo, u.truename AS uname, f.attach FROM ".tname( $this->t_use_flow )." AS f LEFT JOIN ".tname( "main_user" )." AS u ON u.uid=f.uid ".( "WHERE f.uFlowId=".$this->uFlowId );
            $_obfuscate_hTew0boWJESy = $CNOA_DB->get_one( $_obfuscate_3y0Y );
            $_obfuscate_7qDAYo85aGAÿ['flowNumber'] = $_obfuscate_hTew0boWJESy['flowNumber'];
            $_obfuscate_7qDAYo85aGAÿ['flowName'] = $_obfuscate_hTew0boWJESy['flowName'];
            $_obfuscate_7qDAYo85aGAÿ['level'] = $_obfuscate_hTew0boWJESy['level'];
            $_obfuscate_7qDAYo85aGAÿ['reason'] = $_obfuscate_hTew0boWJESy['reason'];
            $_obfuscate_7qDAYo85aGAÿ['uname'] = $_obfuscate_hTew0boWJESy['uname'];
            $_obfuscate_7qDAYo85aGAÿ['htmlFormContent'] = $_obfuscate_hTew0boWJESy['htmlFormContent'];
            $_obfuscate_7qDAYo85aGAÿ['changeFlowInfo'] = $_obfuscate_hTew0boWJESy['changeFlowInfo'];
            $_obfuscate_7qDAYo85aGAÿ['nameDisallowBlank'] = 0;
            $_obfuscate_7qDAYo85aGAÿ['posttime'] = formatdate( $_obfuscate_hTew0boWJESy['posttime'], "Y-m-d H:i" );
            if ( !empty( $_obfuscate_GRtq_c1sXSMthq47 ) )
            {
                $_obfuscate_7qDAYo85aGAÿ['engine'] = $_obfuscate_GRtq_c1sXSMthq47;
            }
            $this->uFlowInfo = $_obfuscate_hTew0boWJESy;
            $this->flowId = $_obfuscate_hTew0boWJESy['flowId'];
            $this->faqiUid = $_obfuscate_hTew0boWJESy['uid'];
            $this->attach = $_obfuscate_hTew0boWJESy['attach'];
            return $_obfuscate_7qDAYo85aGAÿ;
        }
        if ( $this->from == self::FROM_NEW )
        {
            $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "nameRule", "startStepId", "nameDisallowBlank", "nameRuleAllowEdit" ), $this->t_set_flow, "WHERE flowId=".$this->flowId );
            $this->uStepId = $_obfuscate_hTew0boWJESy['startStepId'];
            $_obfuscate_7qDAYo85aGAÿ['flowNumber'] = $_obfuscate_hTew0boWJESy['nameRule'];
            $_obfuscate_7qDAYo85aGAÿ['nameDisallowBlank'] = $_obfuscate_hTew0boWJESy['nameDisallowBlank'];
            $_obfuscate_7qDAYo85aGAÿ['nameRuleAllowEdit'] = $_obfuscate_hTew0boWJESy['nameRuleAllowEdit'];
            $_obfuscate_7qDAYo85aGAÿ['flowName'] = "";
            $_obfuscate_7qDAYo85aGAÿ['level'] = 0;
            $_obfuscate_7qDAYo85aGAÿ['reason'] = "";
            $_obfuscate_7qDAYo85aGAÿ['uname'] = $CNOA_SESSION->get( "TRUENAME" );
            $_obfuscate_7qDAYo85aGAÿ['posttime'] = date( "Y-m-d H:i" );
            if ( !empty( $_obfuscate_GRtq_c1sXSMthq47 ) )
            {
                $_obfuscate_7qDAYo85aGAÿ['engine'] = $_obfuscate_GRtq_c1sXSMthq47;
            }
            $this->uFlowInfo = $_obfuscate_7qDAYo85aGAÿ;
            $this->faqiUid = $CNOA_SESSION->get( "UID" );
        }
        return $_obfuscate_7qDAYo85aGAÿ;
    }

    private function _getStepPermit( )
    {
        global $CNOA_DB;
        if ( $this->from === self::FROM_TODO || $this->from === self::FROM_DONE )
        {
            ( $this->uFlowId );
            $_obfuscate_e53ODz04JQÿÿ = new wfCache( );
            $_obfuscate_o5fQ1gÿÿ = $_obfuscate_e53ODz04JQÿÿ->getStepByStepId( $this->uStepId );
            $_obfuscate_eVTMIa1A['allowReject'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowReject'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowTuihui'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowTuihui'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowHuiqian'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowHuiqian'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowFenfa'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowFenfa'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowAttachAdd'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowAttachAdd'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowAttachView'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowAttachView'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowAttachEdit'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowAttachEdit'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowAttachDelete'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowAttachDelete'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowAttachDown'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowAttachDown'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowHqAttachAdd'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowHqAttachAdd'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowHqAttachView'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowHqAttachView'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowHqAttachEdit'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowHqAttachEdit'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowHqAttachDelete'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowHqAttachDelete'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowHqAttachDown'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowHqAttachDown'] == 1 ? TRUE : FALSE;
            if ( $this->from === self::FROM_TODO )
            {
                $_obfuscate_eVTMIa1A['allowChildFlow'] = ( integer )$_obfuscate_o5fQ1gÿÿ['childFlow'] == 1 ? TRUE : FALSE;
            }
            else
            {
                $_obfuscate_eVTMIa1A['allowChildFlow'] = FALSE;
            }
        }
        else if ( $this->from == self::FROM_NEW )
        {
            $_obfuscate_o5fQ1gÿÿ = $CNOA_DB->db_getone( array( "allowReject", "allowTuihui", "allowHuiqian", "allowFenfa", "allowAttachAdd", "allowAttachView", "allowAttachEdit", "allowAttachDelete", "allowAttachDown", "allowHqAttachAdd", "allowHqAttachView", "allowHqAttachEdit", "allowHqAttachDelete", "allowHqAttachDown" ), $this->t_set_step, "WHERE flowId=".$this->flowId." AND stepId={$this->uStepId}" );
            if ( !is_array( $_obfuscate_o5fQ1gÿÿ ) )
            {
                $_obfuscate_o5fQ1gÿÿ = array( );
            }
        }
        $_obfuscate_eVTMIa1A['allowReject'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowReject'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowTuihui'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowTuihui'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowHuiqian'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowHuiqian'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowFenfa'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowFenfa'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowAttachAdd'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowAttachAdd'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowAttachView'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowAttachView'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowAttachEdit'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowAttachEdit'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowAttachDelete'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowAttachDelete'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowAttachDown'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowAttachDown'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowHqAttachAdd'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowHqAttachAdd'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowHqAttachView'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowHqAttachView'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowHqAttachEdit'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowHqAttachEdit'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowHqAttachDelete'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowHqAttachDelete'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowHqAttachDown'] = ( integer )$_obfuscate_o5fQ1gÿÿ['allowHqAttachDown'] == 1 ? TRUE : FALSE;
        return $_obfuscate_eVTMIa1A;
    }

    private function _getAttach( $_obfuscate_eVTMIa1A )
    {
        global $CNOA_DB;
        if ( $this->flowType == 0 )
        {
            $_obfuscate_urgydSw7IkMKIoqpAÿÿ = $this->api_getWfFieldData( $this->flowId, $this->uFlowId );
            if ( !is_array( $_obfuscate_urgydSw7IkMKIoqpAÿÿ ) )
            {
                $_obfuscate_urgydSw7IkMKIoqpAÿÿ = array( );
            }
        }
        $_obfuscate_m72dzeHEyWQltgÿÿ = $CNOA_DB->db_select( array( "id" ), "wf_s_field", "WHERE `flowId` = '".$this->flowId."' AND otype = 'attach' " );
        if ( !is_array( $_obfuscate_m72dzeHEyWQltgÿÿ ) )
        {
            $_obfuscate_m72dzeHEyWQltgÿÿ = array( );
        }
        foreach ( $_obfuscate_m72dzeHEyWQltgÿÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
        {
            foreach ( $_obfuscate_urgydSw7IkMKIoqpAÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                if ( !( $_obfuscate_VgKtFegÿ['id'] == $_obfuscate_6Aÿÿ['id'] ) && !$_obfuscate_6Aÿÿ['data'] )
                {
                    $_obfuscate_1_pbjTIdLU49 .= substr( $_obfuscate_6Aÿÿ['data'], 1, -1 ).",";
                }
            }
        }
        if ( 2 < strlen( $_obfuscate_5LuNFL5U2xQÿ['attach'] ) )
        {
            $_obfuscate_1_pbjTIdLU49 = "[".$_obfuscate_1_pbjTIdLU49.substr( $_obfuscate_5LuNFL5U2xQÿ['attach'], 1, -1 )."]";
        }
        else
        {
            $_obfuscate_1_pbjTIdLU49 = "[".substr( $_obfuscate_1_pbjTIdLU49, 0, -1 )."]";
        }
        $_obfuscate_t46obxFWLYKePmUÿ = explode( ",", rtrim( ltrim( $_obfuscate_1_pbjTIdLU49, "[" ), "]" ) );
        $_obfuscate_vddvYsrvcSVy = explode( ",", rtrim( ltrim( $this->attach, "[" ), "]" ) );
        $_obfuscate_K_L5uQÿÿ = array_filter( array_flip( array_flip( array_merge( $_obfuscate_t46obxFWLYKePmUÿ, $_obfuscate_vddvYsrvcSVy ) ) ) );
        $_obfuscate_WPvkSFEMgÿÿ = array( );
        if ( !empty( $_obfuscate_K_L5uQÿÿ ) )
        {
            ( );
            $_obfuscate_2ggÿ = new fs( );
            $_obfuscate_WPvkSFEMgÿÿ = $_obfuscate_2ggÿ->getDownLoadFileListByIds( $_obfuscate_K_L5uQÿÿ );
            if ( !$_obfuscate_eVTMIa1A['allowAttachEdit'] || !$_obfuscate_eVTMIa1A['allowAttachView'] || !$_obfuscate_eVTMIa1A['allowAttachDown'] )
            {
                foreach ( $_obfuscate_WPvkSFEMgÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
                {
                    unset( $_obfuscate_6Aÿÿ['url'] );
                }
            }
        }
        return $_obfuscate_WPvkSFEMgÿÿ;
    }

    private function _getFromFields( )
    {
        global $CNOA_DB;
        $_obfuscate_tjILu7ZH = array( );
        $_obfuscate_8MSmTrf2URD2fgÿÿ = array( );
        $_obfuscate_1AvjumH3Nt16yyvF_Qÿÿ = array( );
        $_obfuscate_9or11jDOGs2OAÿÿ = array( );
        if ( $this->from == self::FROM_NEW )
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_step_fields, "WHERE `flowId`=".$this->flowId." AND `stepId`=2 AND hide=0" );
            if ( !is_array( $_obfuscate_mPAjEGLn ) )
            {
                $_obfuscate_mPAjEGLn = array( );
            }
            $_obfuscate_8jhldA9Y9Aÿÿ = array( );
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_8MSmTrf2URD2fgÿÿ[$_obfuscate_6Aÿÿ['fieldId']] = $_obfuscate_6Aÿÿ;
                $_obfuscate_8jhldA9Y9Aÿÿ[] = $_obfuscate_6Aÿÿ['fieldId'];
            }
            if ( !empty( $_obfuscate_8jhldA9Y9Aÿÿ ) )
            {
                $_obfuscate_8jhldA9Y9Aÿÿ = implode( ",", $_obfuscate_8jhldA9Y9Aÿÿ );
                $_obfuscate_tjILu7ZH = $CNOA_DB->db_select( "*", $this->t_set_field, "WHERE `flowId`=".$this->flowId." AND id IN ({$_obfuscate_8jhldA9Y9Aÿÿ}) ORDER BY `order` ASC" );
            }
            $_obfuscate_1AvjumH3Nt16yyvF_Qÿÿ = $CNOA_DB->db_getone( "*", $this->t_set_step, "WHERE `flowId`=".$this->flowId." AND `stepId`={$this->uStepId}" );
        }
        else if ( $this->from == self::FROM_TODO || $this->from === self::FROM_DONE )
        {
            ( $this->uFlowId );
            $_obfuscate_e53ODz04JQÿÿ = new wfCache( );
            $_obfuscate_tjILu7ZH = $_obfuscate_e53ODz04JQÿÿ->getFlowFields( );
            $_obfuscate_8MSmTrf2URD2fgÿÿ = $_obfuscate_e53ODz04JQÿÿ->getStepFields( $this->uStepId, self::FIELD_RULE_NORMAL );
            $_obfuscate_1AvjumH3Nt16yyvF_Qÿÿ = $_obfuscate_e53ODz04JQÿÿ->getStepByStepId( $this->uStepId );
            if ( $this->flowType == 0 )
            {
                $_obfuscate_9or11jDOGs2OAÿÿ = $CNOA_DB->db_getone( "*", "z_wf_t_".$this->flowId, "WHERE `uFlowId` = '".$this->uFlowId."'" );
            }
        }
        $this->flowFields = $_obfuscate_tjILu7ZH;
        $_obfuscate_Tc82k3jOQÿÿ = array( );
        foreach ( $_obfuscate_tjILu7ZH as $_obfuscate_EdcUyMWd6ZEv )
        {
            $_obfuscate_8jhldA9Y9Aÿÿ = $_obfuscate_EdcUyMWd6ZEv['id'];
            $_obfuscate_e_xqByHUUUzRtNYÿ = $_obfuscate_8MSmTrf2URD2fgÿÿ[$_obfuscate_8jhldA9Y9Aÿÿ];
            if ( !$_obfuscate_e_xqByHUUUzRtNYÿ )
            {
                $_obfuscate_6mlyHgÿÿ = array( "show" => 1, "hide" => 0, "edit" => 0, "write" => 0, "must" => 0 );
            }
            if ( !( $_obfuscate_e_xqByHUUUzRtNYÿ['hide'] == 1 ) )
            {
                $_obfuscate_VdCStif4m42Fgÿÿ = empty( $_obfuscate_9or11jDOGs2OAÿÿ["T_".$_obfuscate_8jhldA9Y9Aÿÿ] ) ? $_obfuscate_EdcUyMWd6ZEv['dvalue'] : $_obfuscate_9or11jDOGs2OAÿÿ["T_".$_obfuscate_8jhldA9Y9Aÿÿ];
                if ( $_obfuscate_EdcUyMWd6ZEv['otype'] == "signature" && $_obfuscate_e_xqByHUUUzRtNYÿ['write'] != 1 && empty( $_obfuscate_VdCStif4m42Fgÿÿ ) )
                {
                    if ( $_obfuscate_EdcUyMWd6ZEv['otype'] == "datasource" && !$_obfuscate_e_xqByHUUUzRtNYÿ['write'] )
                    {
                    }
                    else if ( $_obfuscate_EdcUyMWd6ZEv['otype'] == "attach" && !$_obfuscate_e_xqByHUUUzRtNYÿ['write'] || empty( $_obfuscate_VdCStif4m42Fgÿÿ ) )
                    {
                        $_obfuscate_6kJ_st61 = $this->_formatField( $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_e_xqByHUUUzRtNYÿ, $_obfuscate_VdCStif4m42Fgÿÿ );
                        if ( !is_null( $_obfuscate_6kJ_st61 ) )
                        {
                            $_obfuscate_Tc82k3jOQÿÿ[] = $_obfuscate_6kJ_st61;
                        }
                    }
                }
            }
        }
        $_obfuscate_GRtq_c1sXSMthq47 = $CNOA_DB->db_getfield( "bindfunction", $this->t_set_flow, " WHERE `flowId`=".$this->flowId );
        if ( $_obfuscate_GRtq_c1sXSMthq47 != "abutment" )
        {
            $_obfuscate__LVTMsLmCyfG = $this->_getBusinessData( $_obfuscate_GRtq_c1sXSMthq47 );
        }
        if ( $_obfuscate_GRtq_c1sXSMthq47 == "admCar" )
        {
            foreach ( $_obfuscate__LVTMsLmCyfG as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                foreach ( $_obfuscate_Tc82k3jOQÿÿ as $_obfuscate_3QYÿ => $_obfuscate_EGUÿ )
                {
                    if ( $_obfuscate_EGUÿ['name'] == "wf_field_".$_obfuscate_5wÿÿ )
                    {
                        $_obfuscate_Tc82k3jOQÿÿ[$_obfuscate_3QYÿ]['items'] = $_obfuscate_6Aÿÿ;
                    }
                }
            }
        }
        return array(
            "items" => $_obfuscate_Tc82k3jOQÿÿ
        );
    }

    private function _getBusinessData( $_obfuscate_olwD8Qÿÿ )
    {
        $_obfuscate_pYzeLf4ÿ = "init";
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
        return $_obfuscate_7eRCEirmYGaYE1FJCfc0hwÿÿ->getBusinessData( $_obfuscate_pYzeLf4ÿ, "phone" );
    }

    private function _formatField( $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_e_xqByHUUUzRtNYÿ, $_obfuscate_VdCStif4m42Fgÿÿ )
    {
        $_obfuscate_6kJ_st61 = array( );
        $_obfuscate_6kJ_st61['label'] = $_obfuscate_EdcUyMWd6ZEv['name'];
        $_obfuscate_6kJ_st61['tag'] = $_obfuscate_EdcUyMWd6ZEv['otype'];
        $_obfuscate_6kJ_st61['name'] = "wf_field_".$_obfuscate_EdcUyMWd6ZEv['id'];
        $_obfuscate_6kJ_st61['value'] = $_obfuscate_VdCStif4m42Fgÿÿ;
        if ( $this->from === self::FROM_DONE )
        {
            $_obfuscate_6kJ_st61['readOnly'] = TRUE;
        }
        else
        {
            if ( $this->from === self::FROM_TODO && ( $this->hqid || $this->fenfaid ) )
            {
                $_obfuscate_6kJ_st61['readOnly'] = TRUE;
            }
            else
            {
                if ( $_obfuscate_e_xqByHUUUzRtNYÿ['must'] )
                {
                    $_obfuscate_6kJ_st61['must'] = TRUE;
                }
                if ( !$_obfuscate_e_xqByHUUUzRtNYÿ['write'] )
                {
                    $_obfuscate_6kJ_st61['readOnly'] = TRUE;
                }
            }
        }
        switch ( $_obfuscate_EdcUyMWd6ZEv['otype'] )
        {
        case "textfield" :
            $this->_formatTextfield( $_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv );
            break;
        case "textarea" :
            $_obfuscate_lZ9QjfF3 = $_obfuscate_EdcUyMWd6ZEv['odata'];
            if ( !( strpos( $_obfuscate_lZ9QjfF3, "richText" ) !== FALSE ) )
            {
                break;
            }
            $_obfuscate_6kJ_st61['richText'] = "on";
            $_obfuscate_6kJ_st61['dealvalue'] = strip_tags( $_obfuscate_6kJ_st61['value'] );
            break;
        case "radio" :
            $this->_formatRadiofield( $_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv );
            break;
        case "checkbox" :
            $_obfuscate_6kJ_st61['name'] = "wf_fieldC_".$_obfuscate_EdcUyMWd6ZEv['id'];
            $this->_formatCheckfield( $_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv );
            break;
        case "select" :
            $this->_formatSelectfield( $_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv );
            break;
        case "macro" :
            $this->_formatMacro( $_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_e_xqByHUUUzRtNYÿ );
            break;
        case "choice" :
            $this->_formatChoice( $_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv );
            break;
        case "detailtable" :
            $this->_formatDetailtable( $_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv );
            break;
        case "calculate" :
            $this->_formatCalculate( $_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv );
            break;
        case "signature" :
            $this->_formatSignature( $_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_e_xqByHUUUzRtNYÿ );
            break;
        case "datasource" :
            $this->_formatDatasource( $_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_e_xqByHUUUzRtNYÿ );
            break;
        case "attach" :
            $this->_formatAttach( $_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_e_xqByHUUUzRtNYÿ );
            break;
        default :
            return;
        }
        return $_obfuscate_6kJ_st61;
    }

    private function _formatCalculate( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv )
    {
        if ( $this->from == "todo" )
        {
            ( $this->uFlowId );
            $GLOBALS['GLOBALS']['UWFCACHE'] = new wfCache( );
            ( $GLOBALS['UWFCACHE']->getFlowId( ), $this->uStepId, FALSE, $this->uFlowId );
            $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ = new wfFieldFormaterForDealM( );
            $_obfuscate_6mlyHgÿÿ = $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ->stepFields[$_obfuscate_EdcUyMWd6ZEv['id']];
        }
        else if ( $this->from == "new" )
        {
            ( $this->flowId, $this->uFlowId, TRUE );
            $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ = new wfFieldFormaterForDealM( );
            $_obfuscate_6mlyHgÿÿ = $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ->stepFields[$_obfuscate_EdcUyMWd6ZEv['id']];
        }
        if ( empty( $_obfuscate_6mlyHgÿÿ ) )
        {
            $_obfuscate_6mlyHgÿÿ = array( "show" => 1, "hide" => 0, "edit" => 0, "write" => 0, "must" => 0 );
        }
        foreach ( $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ->flowFields as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( !( $_obfuscate_EdcUyMWd6ZEv['id'] != $_obfuscate_6Aÿÿ['id'] ) )
            {
                $_obfuscate_60GquoKMPwÿÿ = $_obfuscate_6Aÿÿ;
            }
        }
        $_obfuscate_p5ZWxr4ÿ = $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ->__getOdata( $_obfuscate_60GquoKMPwÿÿ['odata'] );
        $_obfuscate_sSwuE42EWQÿÿ = $_obfuscate_p5ZWxr4ÿ['expression'];
        $_obfuscate_w5qdpW_0mo2_qehs = $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ->api_splitGongShi( $_obfuscate_sSwuE42EWQÿÿ );
        $_obfuscate_t1EW = "";
        foreach ( $_obfuscate_w5qdpW_0mo2_qehs as $_obfuscate_5wÿÿ => $_obfuscate_GrQÿ )
        {
            if ( !in_array( $_obfuscate_GrQÿ, array( "+", "-", "*", "/" ) ) )
            {
                foreach ( $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ->flowFields as $_obfuscate_WgEÿ )
                {
                    if ( $_obfuscate_GrQÿ == $_obfuscate_WgEÿ['name'] )
                    {
                        $_obfuscate_w5qdpW_0mo2_qehs[$_obfuscate_5wÿÿ] = "wf|".$_obfuscate_WgEÿ['id'];
                    }
                    else
                    {
                        foreach ( $_obfuscate_fr489CNrjWzinRUKCuozmmUÿ->flowDetailFields as $_obfuscate_5DMÿ )
                        {
                            if ( $_obfuscate_GrQÿ == $_obfuscate_5DMÿ['name'] )
                            {
                                $_obfuscate_w5qdpW_0mo2_qehs[$_obfuscate_5wÿÿ] = "wfd|".$_obfuscate_5DMÿ['id'];
                                $_obfuscate_t1EW = "mix=\"true\"";
                            }
                        }
                    }
                }
            }
        }
        $_obfuscate_sSwuE42EWQÿÿ = json_encode( $_obfuscate_w5qdpW_0mo2_qehs );
        $_obfuscate_sSwuE42EWQÿÿ = str_replace( "\"", "'", $_obfuscate_sSwuE42EWQÿÿ );
        $_obfuscate_6kJ_st61['gongshi'] = $_obfuscate_sSwuE42EWQÿÿ;
        $_obfuscate_6kJ_st61['roundtype'] = $_obfuscate_p5ZWxr4ÿ['dataFormat'];
        $_obfuscate_6kJ_st61['baoliu'] = $_obfuscate_p5ZWxr4ÿ['baoliu'];
    }

    private function _formatDatasource( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_e_xqByHUUUzRtNYÿ )
    {
        $_obfuscate_p5ZWxr4ÿ = $this->__getOdata( $_obfuscate_EdcUyMWd6ZEv['odata'] );
        $_obfuscate_EU3Hgÿÿ = $_obfuscate_p5ZWxr4ÿ['maps'];
        if ( is_null( $_obfuscate_EU3Hgÿÿ[0] ) )
        {
            foreach ( $this->flowFields as $_obfuscate_YIq2A8cÿ )
            {
                foreach ( $_obfuscate_EU3Hgÿÿ as $_obfuscate_Vwty => $_obfuscate_Agÿÿ )
                {
                    if ( $_obfuscate_Agÿÿ == $_obfuscate_YIq2A8cÿ['name'] )
                    {
                        $_obfuscate_EU3Hgÿÿ[$_obfuscate_Vwty] = "wf_field_".$_obfuscate_YIq2A8cÿ['id'];
                    }
                }
            }
        }
        else
        {
            foreach ( $this->flowFields as $_obfuscate_YIq2A8cÿ )
            {
                foreach ( $_obfuscate_EU3Hgÿÿ as $_obfuscate_Vwty => $_obfuscate_Agÿÿ )
                {
                    if ( $_obfuscate_Agÿÿ['des'] == $_obfuscate_YIq2A8cÿ['name'] )
                    {
                        $_obfuscate_EU3Hgÿÿ[$_obfuscate_Vwty]['des'] = "wf_field_".$_obfuscate_YIq2A8cÿ['id'];
                    }
                }
            }
        }
        $_obfuscate_6kJ_st61['datasource'] = $_obfuscate_p5ZWxr4ÿ['datasource'];
        $_obfuscate_EU3Hgÿÿ = json_encode( $_obfuscate_EU3Hgÿÿ );
        $_obfuscate_6kJ_st61['maps'] = str_replace( "\"", "'", $_obfuscate_EU3Hgÿÿ );
        $_obfuscate_6kJ_st61['tabindex'] = $_obfuscate_EdcUyMWd6ZEv['order'];
    }

    private function _formatSignature( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_e_xqByHUUUzRtNYÿ )
    {
        $_obfuscate_p5ZWxr4ÿ = $this->__getOdata( $_obfuscate_EdcUyMWd6ZEv['odata'] );
        if ( !$_obfuscate_6kJ_st61['readOnly'] )
        {
            $_obfuscate_6kJ_st61['signaturetype'] = $_obfuscate_p5ZWxr4ÿ['type'];
        }
        $_obfuscate_6kJ_st61['name'] = "wf_fieldS_".$_obfuscate_EdcUyMWd6ZEv['id'];
        $_obfuscate_6kJ_st61['width'] = $_obfuscate_p5ZWxr4ÿ['width'];
        $_obfuscate_6kJ_st61['height'] = $_obfuscate_p5ZWxr4ÿ['height'];
    }

    private function _formatAttach( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_e_xqByHUUUzRtNYÿ )
    {
        if ( $_obfuscate_6kJ_st61['value'] )
        {
            $_obfuscate_1_pbjTIdLU49 = json_decode( $_obfuscate_6kJ_st61['value'], TRUE );
        }
        ( );
        $_obfuscate_2ggÿ = new fs( );
        $_obfuscate_UzL8YIsf8yyIKIS = $_obfuscate_2ggÿ->getListInfo4wfM( $_obfuscate_1_pbjTIdLU49, $this->wfAttachConfig, TRUE, "deal" );
        $_obfuscate_6kJ_st61['attach'] = $_obfuscate_UzL8YIsf8yyIKIS;
        if ( count( $_obfuscate_6kJ_st61['attach'] ) )
        {
            $_obfuscate_6kJ_st61['wfAttachConfig'] = $this->wfAttachConfig;
        }
        $_obfuscate_6kJ_st61['field'] = $_obfuscate_EdcUyMWd6ZEv['id'];
        $_obfuscate_6kJ_st61['write'] = $_obfuscate_e_xqByHUUUzRtNYÿ['write'];
    }

    private function _formatTextfield( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv )
    {
        if ( !$_obfuscate_6kJ_st61['readOnly'] )
        {
            $_obfuscate_p5ZWxr4ÿ = $this->__getOdata( $_obfuscate_EdcUyMWd6ZEv['odata'] );
            $_obfuscate_6kJ_st61['dataType'] = $_obfuscate_p5ZWxr4ÿ['dataType'];
            $this->_formatText4Setting( $_obfuscate_6kJ_st61, $_obfuscate_p5ZWxr4ÿ['dataType'], $_obfuscate_p5ZWxr4ÿ['dataFormat'] );
        }
    }

    private function _formatRadiofield( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv )
    {
        $_obfuscate_p5ZWxr4ÿ = $this->__getOdata( $_obfuscate_EdcUyMWd6ZEv['odata'] );
        $_obfuscate_ = $_obfuscate_LQ8UKgÿÿ = array( );
        foreach ( $_obfuscate_p5ZWxr4ÿ['dataItems'] as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_LQ8UKgÿÿ = array(
                "label" => $_obfuscate_6Aÿÿ['name'],
                "value" => $_obfuscate_6Aÿÿ['name']
            );
            if ( $_obfuscate_6Aÿÿ['name'] == $_obfuscate_6kJ_st61['value'] )
            {
                $_obfuscate_LQ8UKgÿÿ['checked'] = TRUE;
            }
            $_obfuscate_[] = $_obfuscate_LQ8UKgÿÿ;
        }
        $_obfuscate_6kJ_st61['items'] = $_obfuscate_;
    }

    private function _formatCheckfield( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv )
    {
        $_obfuscate_p5ZWxr4ÿ = $this->__getOdata( $_obfuscate_EdcUyMWd6ZEv['odata'] );
        $_obfuscate_VgKtFegÿ = json_decode( htmlspecialchars( $_obfuscate_6kJ_st61['value'], ENT_NOQUOTES ), TRUE );
        $_obfuscate_ = $_obfuscate_LQ8UKgÿÿ = array( );
        foreach ( $_obfuscate_p5ZWxr4ÿ['dataItems'] as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_LQ8UKgÿÿ = array(
                "label" => $_obfuscate_6Aÿÿ['name'],
                "value" => $_obfuscate_6Aÿÿ['name']
            );
            if ( !empty( $_obfuscate_VgKtFegÿ ) || is_array( $_obfuscate_VgKtFegÿ ) )
            {
                $_obfuscate_LQ8UKgÿÿ['checked'] = in_array( $_obfuscate_6Aÿÿ['name'], $_obfuscate_VgKtFegÿ );
            }
            else if ( $_obfuscate_6Aÿÿ['checked'] == 1 )
            {
                $_obfuscate_LQ8UKgÿÿ['checked'] = TRUE;
            }
            $_obfuscate_[] = $_obfuscate_LQ8UKgÿÿ;
        }
        $_obfuscate_6kJ_st61['items'] = $_obfuscate_;
    }

    private function _formatSelectfield( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv )
    {
        $_obfuscate_p5ZWxr4ÿ = $this->__getOdata( $_obfuscate_EdcUyMWd6ZEv['odata'] );
        $_obfuscate_[] = array( "label" => "", "value" => "" );
        foreach ( $_obfuscate_p5ZWxr4ÿ['dataItems'] as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_LQ8UKgÿÿ = array( );
            $_obfuscate_LQ8UKgÿÿ['label'] = $_obfuscate_6Aÿÿ['name'];
            $_obfuscate_LQ8UKgÿÿ['value'] = $_obfuscate_p5ZWxr4ÿ['dataType'] == "int" ? $_obfuscate_6Aÿÿ['value'] : $_obfuscate_6Aÿÿ['name'];
            if ( $_obfuscate_LQ8UKgÿÿ['value'] == $_obfuscate_6kJ_st61['value'] )
            {
                $_obfuscate_LQ8UKgÿÿ['selected'] = TRUE;
            }
            $_obfuscate_[] = $_obfuscate_LQ8UKgÿÿ;
        }
        $_obfuscate_6kJ_st61['items'] = $_obfuscate_;
    }

    private function _formatMacro( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_6mlyHgÿÿ )
    {
        global $CNOA_DB;
        $_obfuscate_p5ZWxr4ÿ = $this->__getOdata( $_obfuscate_EdcUyMWd6ZEv['odata'] );
        $_obfuscate_6kJ_st61['dataType'] = $_obfuscate_p5ZWxr4ÿ['dataType'];
        if ( !$_obfuscate_6kJ_st61['readOnly'] )
        {
            if ( $_obfuscate_6kJ_st61['dataType'] == "loginname" )
            {
                global $CNOA_SESSION;
                $_obfuscate_6kJ_st61['value'] = $CNOA_SESSION->get( "UID" );
                $_obfuscate_6kJ_st61['displayValue'] = app::loadapp( "main", "user" )->api_getUserNameByUids( intval( $_obfuscate_6kJ_st61['value'] ) );
            }
            else if ( $_obfuscate_p5ZWxr4ÿ['dataType'] != "moneyconvert" )
            {
                $_obfuscate_6kJ_st61['value'] = $this->_getMacroValue( $_obfuscate_p5ZWxr4ÿ );
            }
        }
        else if ( $_obfuscate_6kJ_st61['dataType'] == "loginname" && !empty( $_obfuscate_6kJ_st61['value'] ) )
        {
            $_obfuscate_6kJ_st61['displayValue'] = app::loadapp( "main", "user" )->api_getUserNameByUids( intval( $_obfuscate_6kJ_st61['value'] ) );
        }
        else
        {
            $_obfuscate_6kJ_st61['displayValue'] = "";
        }
        if ( $_obfuscate_6mlyHgÿÿ['write'] == 1 )
        {
            if ( $_obfuscate_p5ZWxr4ÿ['allowedit'] == 1 && !in_array( $_obfuscate_p5ZWxr4ÿ['dataType'], array( "flowname", "flownum" ) ) )
            {
                $_obfuscate_6kJ_st61['readOnly'] = FALSE;
            }
            else if ( $_obfuscate_p5ZWxr4ÿ['dataType'] == "attLeave" || $_obfuscate_p5ZWxr4ÿ['dataType'] == "attTime" )
            {
                $_obfuscate_6kJ_st61['readOnly'] = FALSE;
            }
            else
            {
                $_obfuscate_6kJ_st61['readOnly'] = TRUE;
            }
        }
        else
        {
            $_obfuscate_6kJ_st61['readOnly'] = TRUE;
        }
        if ( $_obfuscate_p5ZWxr4ÿ['dataType'] == "moneyconvert" )
        {
            ( $_obfuscate_TlvKhtsoOQÿÿ );
            $GLOBALS['GLOBALS']['UWFCACHE'] = new wfCache( );
            ( $GLOBALS['UWFCACHE']->getFlowId( ), $_obfuscate_0Ul8BBkt, FALSE, $this->uFlowId );
            $_obfuscate_yjs8_LzXVySVfFwNCXGOS9IvPvS41wÿÿ = new wfFieldFormaterForDealM( );
            $_obfuscate_BJBeoQ4Zepwÿ = $_obfuscate_yjs8_LzXVySVfFwNCXGOS9IvPvS41wÿÿ->api_getFieldInfoByName( $_obfuscate_p5ZWxr4ÿ['from'], $this->flowId );
            $_obfuscate_6kJ_st61['from'] = "wf_field_".$_obfuscate_BJBeoQ4Zepwÿ['id'];
        }
        if ( $_obfuscate_p5ZWxr4ÿ['dataType'] == "huiqian" )
        {
            $_obfuscate_ouqX2cxvhAÿÿ = "";
            if ( ( integer )$this->uFlowId != 0 )
            {
                $_obfuscate_O1qQ = $_obfuscate_p5ZWxr4ÿ['huiqianTpl'];
                ( $this->uFlowId );
                $_obfuscate_bIsJe6Aÿ = new wfCache( );
                $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "stepId", "truename", "message", "writetime" ), "wf_u_huiqian", "WHERE uFlowId=".$this->uFlowId." ORDER BY posttime" );
                if ( !is_array( $_obfuscate_mPAjEGLn ) )
                {
                    $_obfuscate_mPAjEGLn = array( );
                }
                foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )
                {
                    $_obfuscate_A260kBOEy4wB = empty( $_obfuscate_6Aÿÿ['writetime'] ) ? "---" : date( "Y-m-d H:i", $_obfuscate_6Aÿÿ['writetime'] );
                    $_obfuscate_5NhzjnJq_f8ÿ = $_obfuscate_bIsJe6Aÿ->getStepByStepId( $_obfuscate_6Aÿÿ['stepId'] );
                    $_obfuscate_77tGbWOiZgÿÿ = array(
                        $_obfuscate_5NhzjnJq_f8ÿ['stepName'],
                        $_obfuscate_6Aÿÿ['truename'],
                        $_obfuscate_A260kBOEy4wB,
                        $_obfuscate_6Aÿÿ['message']
                    );
                    $_obfuscate_ouqX2cxvhAÿÿ .= "<span style=\"line-height:25px;\">".str_replace( array( "{S}", "{U}", "{T}", "{I}" ), $_obfuscate_77tGbWOiZgÿÿ, $_obfuscate_O1qQ )."</span><br />";
                }
            }
            $_obfuscate_6kJ_st61['displayValue'] = $_obfuscate_ouqX2cxvhAÿÿ;
        }
    }

    private function _formatChoice( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv )
    {
        $_obfuscate_p5ZWxr4ÿ = $this->__getOdata( $_obfuscate_EdcUyMWd6ZEv['odata'] );
        $_obfuscate_VgKtFegÿ = $_obfuscate_6kJ_st61['value'];
        $_obfuscate_6kJ_st61['displayValue'] = "";
        switch ( $_obfuscate_p5ZWxr4ÿ['dataType'] )
        {
        case "user_sel" :
            $_obfuscate_6kJ_st61['dataType'] = "user";
            $_obfuscate_6kJ_st61['multi'] = FALSE;
            if ( empty( $_obfuscate_VgKtFegÿ ) )
            {
                break;
            }
            $_obfuscate_6kJ_st61['displayValue'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_VgKtFegÿ );
            break;
        case "users_sel" :
            $_obfuscate_6kJ_st61['dataType'] = "user";
            $_obfuscate_6kJ_st61['multi'] = TRUE;
            if ( empty( $_obfuscate_VgKtFegÿ ) )
            {
                break;
            }
            $_obfuscate_VgKtFegÿ = app::loadapp( "main", "user" )->api_getUserTruenameByUid( explode( ",", $_obfuscate_VgKtFegÿ ) );
            $_obfuscate_6kJ_st61['displayValue'] = implode( ",", $_obfuscate_VgKtFegÿ );
            break;
        case "job_sel" :
            $_obfuscate_6kJ_st61['dataType'] = "job";
            $_obfuscate_6kJ_st61['multi'] = FALSE;
            if ( empty( $_obfuscate_VgKtFegÿ ) )
            {
                break;
            }
            $_obfuscate_6kJ_st61['displayValue'] = app::loadapp( "main", "job" )->api_getNameById( $_obfuscate_VgKtFegÿ );
            break;
        case "jobs_sel" :
            $_obfuscate_6kJ_st61['dataType'] = "job";
            $_obfuscate_6kJ_st61['multi'] = TRUE;
            if ( empty( $_obfuscate_VgKtFegÿ ) )
            {
                break;
            }
            $_obfuscate_VgKtFegÿ = app::loadapp( "main", "job" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFegÿ ) );
            $_obfuscate_6kJ_st61['displayValue'] = implode( ",", $_obfuscate_VgKtFegÿ );
            break;
        case "dept_sel" :
            $_obfuscate_6kJ_st61['dataType'] = "dept";
            $_obfuscate_6kJ_st61['multi'] = FALSE;
            if ( empty( $_obfuscate_VgKtFegÿ ) )
            {
                break;
            }
            $_obfuscate_6kJ_st61['displayValue'] = app::loadapp( "main", "struct" )->api_getNameById( $_obfuscate_VgKtFegÿ );
            break;
        case "depts_sel" :
            $_obfuscate_6kJ_st61['dataType'] = "dept";
            $_obfuscate_6kJ_st61['multi'] = TRUE;
            if ( empty( $_obfuscate_VgKtFegÿ ) )
            {
                break;
            }
            $_obfuscate_VgKtFegÿ = app::loadapp( "main", "struct" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFegÿ ) );
            $_obfuscate_6kJ_st61['displayValue'] = implode( ",", $_obfuscate_VgKtFegÿ );
            break;
        case "station_sel" :
            $_obfuscate_6kJ_st61['dataType'] = "station";
            $_obfuscate_6kJ_st61['multi'] = FALSE;
            if ( empty( $_obfuscate_VgKtFegÿ ) )
            {
                break;
            }
            $_obfuscate_6kJ_st61['displayValue'] = app::loadapp( "main", "station" )->api_getNameById( $_obfuscate_VgKtFegÿ );
            break;
        case "stations_sel" :
            $_obfuscate_6kJ_st61['dataType'] = "station";
            $_obfuscate_6kJ_st61['multi'] = TRUE;
            if ( empty( $_obfuscate_VgKtFegÿ ) )
            {
                break;
            }
            $_obfuscate_VgKtFegÿ = app::loadapp( "main", "station" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFegÿ ) );
            $_obfuscate_6kJ_st61['displayValue'] = implode( ",", $_obfuscate_VgKtFegÿ );
            break;
        case "time_sel" :
            switch ( $_obfuscate_p5ZWxr4ÿ['dataFormat'] )
            {
            case 100 :
                $_obfuscate_ead0WMÿ = "HH:MM";
                break;
            case 200 :
                $_obfuscate_ead0WMÿ = "HH:MM AM";
                break;
            case 300 :
                $_obfuscate_ead0WMÿ = "HH:MM a";
            }
            $_obfuscate_6kJ_st61['dtfmt'] = intval( $_obfuscate_p5ZWxr4ÿ['dataFormat'] );
            $_obfuscate_6kJ_st61['dataType'] = "time";
            $_obfuscate_6kJ_st61['format'] = $this->_getTimeFormat( $_obfuscate_p5ZWxr4ÿ['dataFormat'] );
            if ( !empty( $_obfuscate_VgKtFegÿ ) || $_obfuscate_VgKtFegÿ != "default" && $_obfuscate_VgKtFegÿ != "null" )
            {
                $_obfuscate_6kJ_st61['displayValue'] = $_obfuscate_VgKtFegÿ;
            }
            else
            {
                if ( !( $_obfuscate_VgKtFegÿ == "default" ) )
                {
                    break;
                }
                $_obfuscate_VgKtFegÿ = date( $_obfuscate_6kJ_st61['format'] );
                $_obfuscate_6kJ_st61['displayValue'] = str_replace( array( "am", "pm" ), array( "ä¸Šåˆ", "ä¸‹åˆ" ), $_obfuscate_VgKtFegÿ );
                $_obfuscate_6kJ_st61['value'] = $_obfuscate_6kJ_st61['displayValue'];
            }
            break;
        case "date_sel" :
            switch ( $_obfuscate_p5ZWxr4ÿ['dataFormat'] )
            {
            case 100 :
                $_obfuscate_ead0WMÿ = "yyyy-MM-dd";
                break;
            case 200 :
                $_obfuscate_ead0WMÿ = "yyyy-MM";
                break;
            case 300 :
                $_obfuscate_ead0WMÿ = "yy-mm-dd";
                break;
            case 400 :
                $_obfuscate_ead0WMÿ = "yyyyMMdd";
                break;
            case 500 :
                $_obfuscate_ead0WMÿ = "mm-dd yyyy";
                break;
            case 600 :
                $_obfuscate_ead0WMÿ = "yyyyå¹´MMæœˆ";
                break;
            case 700 :
                $_obfuscate_ead0WMÿ = "yyyyå¹´MMæœˆddæ—¥";
                break;
            case 800 :
                $_obfuscate_ead0WMÿ = "mmæœˆddæ—¥";
                break;
            case 900 :
                $_obfuscate_ead0WMÿ = "yyyy.MM";
                break;
            case 1000 :
                $_obfuscate_ead0WMÿ = "yyyy.MM.dd";
                break;
            case 1100 :
                $_obfuscate_ead0WMÿ = "mm.dd";
                break;
            case 1200 :
                $_obfuscate_ead0WMÿ = "yyyy-MM-dd HH:mm";
                break;
            case 1300 :
                $_obfuscate_ead0WMÿ = "yyyyå¹´MMæœˆddæ—¥ HH:mm";
            }
            $_obfuscate_6kJ_st61['dtfmt'] = $_obfuscate_ead0WMÿ;
            $_obfuscate_6kJ_st61['dataType'] = "date";
            $_obfuscate_7R7jAawdKeMxGQÿÿ = $this->_getDataFormat( $_obfuscate_p5ZWxr4ÿ['dataFormat'] );
            $_obfuscate_6kJ_st61['format'] = $_obfuscate_7R7jAawdKeMxGQÿÿ['format'];
            if ( !empty( $_obfuscate_VgKtFegÿ ) || $_obfuscate_VgKtFegÿ != "default" && $_obfuscate_VgKtFegÿ != "null" )
            {
                $_obfuscate_6kJ_st61['displayValue'] = $_obfuscate_6kJ_st61['value'] == "null" ? "" : $_obfuscate_6kJ_st61['value'];
            }
            else
            {
                if ( $_obfuscate_VgKtFegÿ == "default" )
                {
                    $_obfuscate_6kJ_st61['displayValue'] = date( $_obfuscate_7R7jAawdKeMxGQÿÿ['format'] );
                    $_obfuscate_6kJ_st61['value'] = $_obfuscate_6kJ_st61['displayValue'];
                }
                else
                {
                    $_obfuscate_6kJ_st61['value'] = $_obfuscate_6kJ_st61['value'] == "null" ? "" : $_obfuscate_6kJ_st61['value'];
                }
            }
        }
    }

    private function _formatDetailtable( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv )
    {
        $_obfuscate_6kJ_st61['tableId'] = $_obfuscate_EdcUyMWd6ZEv['id'];
    }

    private function _changeFenfaIsRead( $_obfuscate_7Ri3 )
    {
        global $CNOA_DB;
        $_obfuscate_oem6gj5nf_TU = $CNOA_DB->db_getone( "*", "wf_u_fenfa", "WHERE `touid`='".$_obfuscate_7Ri3."' AND `uFlowId`='{$this->uFlowId}' AND `stepId`='{$this->uStepId}'" );
        if ( $_obfuscate_oem6gj5nf_TU )
        {
            $CNOA_DB->db_update( array( "isread" => 1 ), "wf_u_fenfa", "WHERE `touid`='".$_obfuscate_7Ri3."' AND `uFlowId`='{$this->uFlowId}' AND `stepId`='{$this->uStepId}'" );
        }
    }

}

?>
