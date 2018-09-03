<?php

class wfFlowUsePhoneMM extends wfForm
{

    private static $map_level = array
    (
        0 => "普通",
        1 => "重要",
        2 => "非常重要"
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
        $_obfuscate_M_5JJw�� = getpar( $_GET, "task", "loadPage" );
        switch ( $_obfuscate_M_5JJw�� )
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
        $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/newFlowForm.m.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQ�� );
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
        $_obfuscate_wvQILQ�� = $CNOA_DB->db_getone( array( "id" ), "wf_u_huiqian", "WHERE uFlowId=".$this->uFlowId." AND stepId={$this->uStepId} AND touid={$this->uid}" );
        if ( !is_array( $_obfuscate_wvQILQ�� ) )
        {
            $_obfuscate_wvQILQ�� = array( );
        }
        if ( 0 < count( $_obfuscate_wvQILQ�� ) )
        {
            $this->hqid = $_obfuscate_wvQILQ��;
        }
        else
        {
            $this->hqid = "";
        }
        $_obfuscate_dTuAPTvj1A�� = $CNOA_DB->db_getone( array( "uFenfaId" ), "wf_u_fenfa", "WHERE uFlowId=".$this->uFlowId." AND stepId={$this->uStepId} AND touid={$this->uid}" );
        if ( !is_array( $_obfuscate_dTuAPTvj1A�� ) )
        {
            $_obfuscate_dTuAPTvj1A�� = array( );
        }
        if ( 0 < count( $_obfuscate_dTuAPTvj1A�� ) )
        {
            $this->fenfaid = $_obfuscate_dTuAPTvj1A��;
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
        $_obfuscate_o5fQ1g��['flowInfo'] = $this->_getFlowInfo( );
        $_obfuscate_o5fQ1g��['permit'] = $this->_getStepPermit( );
        $_obfuscate_o5fQ1g��['attach'] = $this->_getAttach( $_obfuscate_o5fQ1g��['permit'] );
        $_obfuscate_o5fQ1g��['formInfo'] = $this->_getFromFields( );
        $_obfuscate_QHb7ty385uUoOy3M = $CNOA_DB->db_getfield( "nameDisallowBlank", "wf_s_flow", " WHERE `flowId`=".$this->flowId );
        $_obfuscate_o5fQ1g��['permit']['nameDisallowBlank'] = ( integer )$_obfuscate_QHb7ty385uUoOy3M == 1 ? TRUE : FALSE;
        $_obfuscate_xH1w2Nq_Hqk� = $this->_getRelFlowSum( );
        $_obfuscate_o5fQ1g��['permit']['allowRelateFlow'] = $_obfuscate_xH1w2Nq_Hqk�['allowRelateFlow'];
        $_obfuscate_o5fQ1g��['permit']['showChildAlert'] = $_obfuscate_xH1w2Nq_Hqk�['showChildAlert'];
        $this->_changeFenfaIsRead( $this->uid );
        echo json_encode( $_obfuscate_o5fQ1g�� );
    }

    private function _getRelFlowSum( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_3y0Y = "SELECT COUNT(DISTINCT `uFlowId`) AS total FROM ".tname( $this->t_use_step_child_flow ).( " WHERE (`puFlowId` = ".$this->uFlowId." AND `uFlowId`!=0) OR `uFlowId`={$this->uFlowId}" );
        $_obfuscate_j9sJes� = $CNOA_DB->get_one( $_obfuscate_3y0Y );
        $_obfuscate_eVTMIa1A = array( );
        if ( 0 < $_obfuscate_j9sJes�['total'] )
        {
            $_obfuscate_eVTMIa1A['allowRelateFlow'] = TRUE;
        }
        else
        {
            $_obfuscate_eVTMIa1A['allowRelateFlow'] = FALSE;
        }
        ( $this->uFlowId );
        $_obfuscate_e53ODz04JQ�� = new wfCache( );
        $_obfuscate_5NhzjnJq_f8� = $CNOA_DB->db_getone( array( "uid", "proxyUid", "childFlow", "stepType" ), $this->t_use_step, "WHERE `uFlowId`=".$this->uFlowId." AND `uStepId`={$this->uStepId} AND (`uid`={$_obfuscate_7Ri3} OR `proxyUid`={$_obfuscate_7Ri3}) ORDER BY `id` DESC" );
        $_obfuscate_eVTMIa1A['showChildAlert'] = FALSE;
        if ( $_obfuscate_5NhzjnJq_f8�['childFlow'] == 1 )
        {
            $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->t_use_step_child_flow, "WHERE `puFlowId` = ".$this->uFlowId." AND `stepId` = {$this->uStepId} " );
            if ( empty( $_obfuscate_Ybai ) )
            {
                $_obfuscate_ibEsWI9S['puFlowId'] = $this->uFlowId;
                $_obfuscate_ibEsWI9S['stepId'] = $this->uStepId;
                $_obfuscate_ibEsWI9S['postuid'] = $_obfuscate_7Ri3;
                $_obfuscate_Tx7M9W = $_obfuscate_e53ODz04JQ��->getStepByStepId( $this->uStepId );
                $_obfuscate_MnVVbyZQFVw� = json_decode( $_obfuscate_Tx7M9W['nextStep'], TRUE );
                if ( !is_array( $_obfuscate_MnVVbyZQFVw� ) )
                {
                    $_obfuscate_MnVVbyZQFVw� = array( );
                }
                foreach ( $_obfuscate_MnVVbyZQFVw� as $_obfuscate_5w�� => $_obfuscate_6A�� )
                {
                    $_obfuscate_SeV31Q�� = $_obfuscate_e53ODz04JQ��->getStepByStepId( $_obfuscate_6A�� );
                    if ( $_obfuscate_SeV31Q��['stepType'] == 7 )
                    {
                        $_obfuscate_J9i4sncOcw�� = substr( $_obfuscate_SeV31Q��['bingids'], 0, -1 );
                        if ( !empty( $_obfuscate_J9i4sncOcw�� ) )
                        {
                            $_obfuscate_SIUSR4F6 = $CNOA_DB->db_select( array( "name", "flowId" ), $this->t_set_flow, "WHERE `flowId` IN (".$_obfuscate_J9i4sncOcw��.") " );
                            foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_snM� )
                            {
                                $_obfuscate_ibEsWI9S['flowId'] = $_obfuscate_snM�['flowId'];
                                $_obfuscate_ibEsWI9S['faqiFlow'] = $_obfuscate_SeV31Q��['faqiFlow'];
                                $_obfuscate_ibEsWI9S['endFlow'] = $_obfuscate_SeV31Q��['endFlow'];
                                $_obfuscate_ibEsWI9S['sharefile'] = $_obfuscate_SeV31Q��['sharefile'];
                                $CNOA_DB->db_insert( $_obfuscate_ibEsWI9S, $this->t_use_step_child_flow );
                            }
                        }
                    }
                }
            }
            $_obfuscate_pcmxgqXbvl0yPCL_KHM� = getpar( $_POST, "childSeeParent", "" );
            if ( empty( $_obfuscate_pcmxgqXbvl0yPCL_KHM� ) )
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
            msg::showerror( "没有选择需要办理的流程" );
        }
        $_obfuscate_7K2adqbGwg�� = $CNOA_DB->db_select( array( "puFlowId", "uFlowId" ), $this->t_use_step_child_flow, "WHERE (`puFlowId` = ".$this->uFlowId." AND `uFlowId`!=0) OR `uFlowId`={$this->uFlowId}" );
        $_obfuscate_vYhijxiHce2BjA��[] = $this->uFlowId;
        if ( !is_array( $_obfuscate_7K2adqbGwg�� ) )
        {
            $_obfuscate_7K2adqbGwg�� = array( );
        }
        $_obfuscate_TlvKhtsoOQ�� = array( );
        foreach ( $_obfuscate_7K2adqbGwg�� as $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['puFlowId'] == $this->uFlowId )
            {
                $_obfuscate_vYhijxiHce2BjA��[] = $_obfuscate_6A��['uFlowId'];
                $_obfuscate_TlvKhtsoOQ��[] = $_obfuscate_6A��['uFlowId'];
            }
            if ( $_obfuscate_6A��['uFlowId'] == $this->uFlowId )
            {
                $_obfuscate_vYhijxiHce2BjA��[] = $_obfuscate_6A��['puFlowId'];
                $_obfuscate_TlvKhtsoOQ��[] = $_obfuscate_6A��['puFlowId'];
            }
        }
        while ( 0 < count( $_obfuscate_TlvKhtsoOQ�� ) )
        {
            $_obfuscate_TlvKhtsoOQ�� = implode( ",", $_obfuscate_TlvKhtsoOQ�� );
            $_obfuscate_7K2adqbGwg�� = $CNOA_DB->db_select( array( "puFlowId", "uFlowId" ), $this->t_use_step_child_flow, "WHERE (`puFlowId` IN (".$_obfuscate_TlvKhtsoOQ��.") AND `uFlowId`!=0) OR `uFlowId` IN ({$_obfuscate_TlvKhtsoOQ��})" );
            if ( !is_array( $_obfuscate_7K2adqbGwg�� ) && !( 0 < count( $_obfuscate_7K2adqbGwg�� ) ) )
            {
                $_obfuscate_TlvKhtsoOQ�� = array( );
                foreach ( $_obfuscate_7K2adqbGwg�� as $_obfuscate_6A�� )
                {
                    if ( !in_array( $_obfuscate_6A��['puFlowId'], $_obfuscate_vYhijxiHce2BjA�� ) )
                    {
                        $_obfuscate_vYhijxiHce2BjA��[] = $_obfuscate_6A��['puFlowId'];
                        $_obfuscate_TlvKhtsoOQ��[] = $_obfuscate_6A��['puFlowId'];
                    }
                    if ( !in_array( $_obfuscate_6A��['uFlowId'], $_obfuscate_vYhijxiHce2BjA�� ) )
                    {
                        $_obfuscate_vYhijxiHce2BjA��[] = $_obfuscate_6A��['uFlowId'];
                        $_obfuscate_TlvKhtsoOQ��[] = $_obfuscate_6A��['uFlowId'];
                    }
                }
            }
        }
        unset( $_obfuscate_7K2adqbGwg�� );
        unset( $_obfuscate_TlvKhtsoOQ�� );
        array_shift( &$_obfuscate_vYhijxiHce2BjA�� );
        if ( 0 < count( $_obfuscate_vYhijxiHce2BjA�� ) )
        {
            $_obfuscate_vYhijxiHce2BjA�� = implode( ",", $_obfuscate_vYhijxiHce2BjA�� );
            $_obfuscate_J3xoGDNkbSJolvqe = $CNOA_DB->db_select( array( "uFlowId", "flowId", "flowNumber", "tplSort", "status" ), $this->t_use_flow, "WHERE `uFlowId` IN (".$_obfuscate_vYhijxiHce2BjA��.")" );
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "uFlowId", "uStepId", "etime" ), $this->t_use_step, "WHERE `uFlowId` IN (".$_obfuscate_vYhijxiHce2BjA��.")" );
            if ( !is_array( $_obfuscate_mPAjEGLn ) )
            {
                $_obfuscate_mPAjEGLn = array( );
            }
            $_obfuscate_EAma1UOylRWnspih = array( );
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                if ( isset( $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6A��['uFlowId']] ) )
                {
                    if ( $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6A��['uFlowId']]['etime'] < $_obfuscate_6A��['etime'] )
                    {
                        $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6A��['uFlowId']] = $_obfuscate_6A��;
                    }
                }
                else
                {
                    $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6A��['uFlowId']] = $_obfuscate_6A��;
                }
            }
            if ( !is_array( $_obfuscate_J3xoGDNkbSJolvqe ) )
            {
                $_obfuscate_J3xoGDNkbSJolvqe = array( );
            }
            foreach ( $_obfuscate_J3xoGDNkbSJolvqe as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_J3xoGDNkbSJolvqe[$_obfuscate_5w��]['step'] = $_obfuscate_EAma1UOylRWnspih[$_obfuscate_6A��['uFlowId']]['uStepId'];
                $_obfuscate_EPBXIrrI[] = $_obfuscate_6A��['flowId'];
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
                foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A�� )
                {
                    $_obfuscate_EPBXIrrI[$_obfuscate_6A��['flowId']] = $_obfuscate_6A��['flowType'];
                }
                foreach ( $_obfuscate_J3xoGDNkbSJolvqe as $_obfuscate_5w�� => $_obfuscate_6A�� )
                {
                    $_obfuscate_J3xoGDNkbSJolvqe[$_obfuscate_5w��]['flowType'] = $_obfuscate_EPBXIrrI[$_obfuscate_6A��['flowId']];
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
        $_obfuscate_7qDAYo85aGA� = array( );
        $_obfuscate_GRtq_c1sXSMthq47 = $CNOA_DB->db_getfield( "bindfunction", $this->t_set_flow, " WHERE `flowId`=".$this->flowId );
        if ( $this->from === self::FROM_TODO || $this->from === self::FROM_DONE )
        {
            $_obfuscate_3y0Y = "SELECT f.uid, f.flowId, f.flowNumber, f.flowName, f.level, f.reason, f.posttime, f.htmlFormContent, f.changeFlowInfo, u.truename AS uname, f.attach FROM ".tname( $this->t_use_flow )." AS f LEFT JOIN ".tname( "main_user" )." AS u ON u.uid=f.uid ".( "WHERE f.uFlowId=".$this->uFlowId );
            $_obfuscate_hTew0boWJESy = $CNOA_DB->get_one( $_obfuscate_3y0Y );
            $_obfuscate_7qDAYo85aGA�['flowNumber'] = $_obfuscate_hTew0boWJESy['flowNumber'];
            $_obfuscate_7qDAYo85aGA�['flowName'] = $_obfuscate_hTew0boWJESy['flowName'];
            $_obfuscate_7qDAYo85aGA�['level'] = $_obfuscate_hTew0boWJESy['level'];
            $_obfuscate_7qDAYo85aGA�['reason'] = $_obfuscate_hTew0boWJESy['reason'];
            $_obfuscate_7qDAYo85aGA�['uname'] = $_obfuscate_hTew0boWJESy['uname'];
            $_obfuscate_7qDAYo85aGA�['htmlFormContent'] = $_obfuscate_hTew0boWJESy['htmlFormContent'];
            $_obfuscate_7qDAYo85aGA�['changeFlowInfo'] = $_obfuscate_hTew0boWJESy['changeFlowInfo'];
            $_obfuscate_7qDAYo85aGA�['nameDisallowBlank'] = 0;
            $_obfuscate_7qDAYo85aGA�['posttime'] = formatdate( $_obfuscate_hTew0boWJESy['posttime'], "Y-m-d H:i" );
            if ( !empty( $_obfuscate_GRtq_c1sXSMthq47 ) )
            {
                $_obfuscate_7qDAYo85aGA�['engine'] = $_obfuscate_GRtq_c1sXSMthq47;
            }
            $this->uFlowInfo = $_obfuscate_hTew0boWJESy;
            $this->flowId = $_obfuscate_hTew0boWJESy['flowId'];
            $this->faqiUid = $_obfuscate_hTew0boWJESy['uid'];
            $this->attach = $_obfuscate_hTew0boWJESy['attach'];
            return $_obfuscate_7qDAYo85aGA�;
        }
        if ( $this->from == self::FROM_NEW )
        {
            $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "nameRule", "startStepId", "nameDisallowBlank", "nameRuleAllowEdit" ), $this->t_set_flow, "WHERE flowId=".$this->flowId );
            $this->uStepId = $_obfuscate_hTew0boWJESy['startStepId'];
            $_obfuscate_7qDAYo85aGA�['flowNumber'] = $_obfuscate_hTew0boWJESy['nameRule'];
            $_obfuscate_7qDAYo85aGA�['nameDisallowBlank'] = $_obfuscate_hTew0boWJESy['nameDisallowBlank'];
            $_obfuscate_7qDAYo85aGA�['nameRuleAllowEdit'] = $_obfuscate_hTew0boWJESy['nameRuleAllowEdit'];
            $_obfuscate_7qDAYo85aGA�['flowName'] = "";
            $_obfuscate_7qDAYo85aGA�['level'] = 0;
            $_obfuscate_7qDAYo85aGA�['reason'] = "";
            $_obfuscate_7qDAYo85aGA�['uname'] = $CNOA_SESSION->get( "TRUENAME" );
            $_obfuscate_7qDAYo85aGA�['posttime'] = date( "Y-m-d H:i" );
            if ( !empty( $_obfuscate_GRtq_c1sXSMthq47 ) )
            {
                $_obfuscate_7qDAYo85aGA�['engine'] = $_obfuscate_GRtq_c1sXSMthq47;
            }
            $this->uFlowInfo = $_obfuscate_7qDAYo85aGA�;
            $this->faqiUid = $CNOA_SESSION->get( "UID" );
        }
        return $_obfuscate_7qDAYo85aGA�;
    }

    private function _getStepPermit( )
    {
        global $CNOA_DB;
        if ( $this->from === self::FROM_TODO || $this->from === self::FROM_DONE )
        {
            ( $this->uFlowId );
            $_obfuscate_e53ODz04JQ�� = new wfCache( );
            $_obfuscate_o5fQ1g�� = $_obfuscate_e53ODz04JQ��->getStepByStepId( $this->uStepId );
            $_obfuscate_eVTMIa1A['allowReject'] = ( integer )$_obfuscate_o5fQ1g��['allowReject'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowTuihui'] = ( integer )$_obfuscate_o5fQ1g��['allowTuihui'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowHuiqian'] = ( integer )$_obfuscate_o5fQ1g��['allowHuiqian'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowFenfa'] = ( integer )$_obfuscate_o5fQ1g��['allowFenfa'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowAttachAdd'] = ( integer )$_obfuscate_o5fQ1g��['allowAttachAdd'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowAttachView'] = ( integer )$_obfuscate_o5fQ1g��['allowAttachView'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowAttachEdit'] = ( integer )$_obfuscate_o5fQ1g��['allowAttachEdit'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowAttachDelete'] = ( integer )$_obfuscate_o5fQ1g��['allowAttachDelete'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowAttachDown'] = ( integer )$_obfuscate_o5fQ1g��['allowAttachDown'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowHqAttachAdd'] = ( integer )$_obfuscate_o5fQ1g��['allowHqAttachAdd'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowHqAttachView'] = ( integer )$_obfuscate_o5fQ1g��['allowHqAttachView'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowHqAttachEdit'] = ( integer )$_obfuscate_o5fQ1g��['allowHqAttachEdit'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowHqAttachDelete'] = ( integer )$_obfuscate_o5fQ1g��['allowHqAttachDelete'] == 1 ? TRUE : FALSE;
            $_obfuscate_eVTMIa1A['allowHqAttachDown'] = ( integer )$_obfuscate_o5fQ1g��['allowHqAttachDown'] == 1 ? TRUE : FALSE;
            if ( $this->from === self::FROM_TODO )
            {
                $_obfuscate_eVTMIa1A['allowChildFlow'] = ( integer )$_obfuscate_o5fQ1g��['childFlow'] == 1 ? TRUE : FALSE;
            }
            else
            {
                $_obfuscate_eVTMIa1A['allowChildFlow'] = FALSE;
            }
        }
        else if ( $this->from == self::FROM_NEW )
        {
            $_obfuscate_o5fQ1g�� = $CNOA_DB->db_getone( array( "allowReject", "allowTuihui", "allowHuiqian", "allowFenfa", "allowAttachAdd", "allowAttachView", "allowAttachEdit", "allowAttachDelete", "allowAttachDown", "allowHqAttachAdd", "allowHqAttachView", "allowHqAttachEdit", "allowHqAttachDelete", "allowHqAttachDown" ), $this->t_set_step, "WHERE flowId=".$this->flowId." AND stepId={$this->uStepId}" );
            if ( !is_array( $_obfuscate_o5fQ1g�� ) )
            {
                $_obfuscate_o5fQ1g�� = array( );
            }
        }
        $_obfuscate_eVTMIa1A['allowReject'] = ( integer )$_obfuscate_o5fQ1g��['allowReject'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowTuihui'] = ( integer )$_obfuscate_o5fQ1g��['allowTuihui'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowHuiqian'] = ( integer )$_obfuscate_o5fQ1g��['allowHuiqian'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowFenfa'] = ( integer )$_obfuscate_o5fQ1g��['allowFenfa'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowAttachAdd'] = ( integer )$_obfuscate_o5fQ1g��['allowAttachAdd'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowAttachView'] = ( integer )$_obfuscate_o5fQ1g��['allowAttachView'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowAttachEdit'] = ( integer )$_obfuscate_o5fQ1g��['allowAttachEdit'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowAttachDelete'] = ( integer )$_obfuscate_o5fQ1g��['allowAttachDelete'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowAttachDown'] = ( integer )$_obfuscate_o5fQ1g��['allowAttachDown'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowHqAttachAdd'] = ( integer )$_obfuscate_o5fQ1g��['allowHqAttachAdd'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowHqAttachView'] = ( integer )$_obfuscate_o5fQ1g��['allowHqAttachView'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowHqAttachEdit'] = ( integer )$_obfuscate_o5fQ1g��['allowHqAttachEdit'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowHqAttachDelete'] = ( integer )$_obfuscate_o5fQ1g��['allowHqAttachDelete'] == 1 ? TRUE : FALSE;
        $_obfuscate_eVTMIa1A['allowHqAttachDown'] = ( integer )$_obfuscate_o5fQ1g��['allowHqAttachDown'] == 1 ? TRUE : FALSE;
        return $_obfuscate_eVTMIa1A;
    }

    private function _getAttach( $_obfuscate_eVTMIa1A )
    {
        global $CNOA_DB;
        if ( $this->flowType == 0 )
        {
            $_obfuscate_urgydSw7IkMKIoqpA�� = $this->api_getWfFieldData( $this->flowId, $this->uFlowId );
            if ( !is_array( $_obfuscate_urgydSw7IkMKIoqpA�� ) )
            {
                $_obfuscate_urgydSw7IkMKIoqpA�� = array( );
            }
        }
        $_obfuscate_m72dzeHEyWQltg�� = $CNOA_DB->db_select( array( "id" ), "wf_s_field", "WHERE `flowId` = '".$this->flowId."' AND otype = 'attach' " );
        if ( !is_array( $_obfuscate_m72dzeHEyWQltg�� ) )
        {
            $_obfuscate_m72dzeHEyWQltg�� = array( );
        }
        foreach ( $_obfuscate_m72dzeHEyWQltg�� as $_obfuscate_Vwty => $_obfuscate_VgKtFeg� )
        {
            foreach ( $_obfuscate_urgydSw7IkMKIoqpA�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                if ( !( $_obfuscate_VgKtFeg�['id'] == $_obfuscate_6A��['id'] ) && !$_obfuscate_6A��['data'] )
                {
                    $_obfuscate_1_pbjTIdLU49 .= substr( $_obfuscate_6A��['data'], 1, -1 ).",";
                }
            }
        }
        if ( 2 < strlen( $_obfuscate_5LuNFL5U2xQ�['attach'] ) )
        {
            $_obfuscate_1_pbjTIdLU49 = "[".$_obfuscate_1_pbjTIdLU49.substr( $_obfuscate_5LuNFL5U2xQ�['attach'], 1, -1 )."]";
        }
        else
        {
            $_obfuscate_1_pbjTIdLU49 = "[".substr( $_obfuscate_1_pbjTIdLU49, 0, -1 )."]";
        }
        $_obfuscate_t46obxFWLYKePmU� = explode( ",", rtrim( ltrim( $_obfuscate_1_pbjTIdLU49, "[" ), "]" ) );
        $_obfuscate_vddvYsrvcSVy = explode( ",", rtrim( ltrim( $this->attach, "[" ), "]" ) );
        $_obfuscate_K_L5uQ�� = array_filter( array_flip( array_flip( array_merge( $_obfuscate_t46obxFWLYKePmU�, $_obfuscate_vddvYsrvcSVy ) ) ) );
        $_obfuscate_WPvkSFEMg�� = array( );
        if ( !empty( $_obfuscate_K_L5uQ�� ) )
        {
            ( );
            $_obfuscate_2gg� = new fs( );
            $_obfuscate_WPvkSFEMg�� = $_obfuscate_2gg�->getDownLoadFileListByIds( $_obfuscate_K_L5uQ�� );
            if ( !$_obfuscate_eVTMIa1A['allowAttachEdit'] || !$_obfuscate_eVTMIa1A['allowAttachView'] || !$_obfuscate_eVTMIa1A['allowAttachDown'] )
            {
                foreach ( $_obfuscate_WPvkSFEMg�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
                {
                    unset( $_obfuscate_6A��['url'] );
                }
            }
        }
        return $_obfuscate_WPvkSFEMg��;
    }

    private function _getFromFields( )
    {
        global $CNOA_DB;
        $_obfuscate_tjILu7ZH = array( );
        $_obfuscate_8MSmTrf2URD2fg�� = array( );
        $_obfuscate_1AvjumH3Nt16yyvF_Q�� = array( );
        $_obfuscate_9or11jDOGs2OA�� = array( );
        if ( $this->from == self::FROM_NEW )
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_step_fields, "WHERE `flowId`=".$this->flowId." AND `stepId`=2 AND hide=0" );
            if ( !is_array( $_obfuscate_mPAjEGLn ) )
            {
                $_obfuscate_mPAjEGLn = array( );
            }
            $_obfuscate_8jhldA9Y9A�� = array( );
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A�� )
            {
                $_obfuscate_8MSmTrf2URD2fg��[$_obfuscate_6A��['fieldId']] = $_obfuscate_6A��;
                $_obfuscate_8jhldA9Y9A��[] = $_obfuscate_6A��['fieldId'];
            }
            if ( !empty( $_obfuscate_8jhldA9Y9A�� ) )
            {
                $_obfuscate_8jhldA9Y9A�� = implode( ",", $_obfuscate_8jhldA9Y9A�� );
                $_obfuscate_tjILu7ZH = $CNOA_DB->db_select( "*", $this->t_set_field, "WHERE `flowId`=".$this->flowId." AND id IN ({$_obfuscate_8jhldA9Y9A��}) ORDER BY `order` ASC" );
            }
            $_obfuscate_1AvjumH3Nt16yyvF_Q�� = $CNOA_DB->db_getone( "*", $this->t_set_step, "WHERE `flowId`=".$this->flowId." AND `stepId`={$this->uStepId}" );
        }
        else if ( $this->from == self::FROM_TODO || $this->from === self::FROM_DONE )
        {
            ( $this->uFlowId );
            $_obfuscate_e53ODz04JQ�� = new wfCache( );
            $_obfuscate_tjILu7ZH = $_obfuscate_e53ODz04JQ��->getFlowFields( );
            $_obfuscate_8MSmTrf2URD2fg�� = $_obfuscate_e53ODz04JQ��->getStepFields( $this->uStepId, self::FIELD_RULE_NORMAL );
            $_obfuscate_1AvjumH3Nt16yyvF_Q�� = $_obfuscate_e53ODz04JQ��->getStepByStepId( $this->uStepId );
            if ( $this->flowType == 0 )
            {
                $_obfuscate_9or11jDOGs2OA�� = $CNOA_DB->db_getone( "*", "z_wf_t_".$this->flowId, "WHERE `uFlowId` = '".$this->uFlowId."'" );
            }
        }
        $this->flowFields = $_obfuscate_tjILu7ZH;
        $_obfuscate_Tc82k3jOQ�� = array( );
        foreach ( $_obfuscate_tjILu7ZH as $_obfuscate_EdcUyMWd6ZEv )
        {
            $_obfuscate_8jhldA9Y9A�� = $_obfuscate_EdcUyMWd6ZEv['id'];
            $_obfuscate_e_xqByHUUUzRtNY� = $_obfuscate_8MSmTrf2URD2fg��[$_obfuscate_8jhldA9Y9A��];
            if ( !$_obfuscate_e_xqByHUUUzRtNY� )
            {
                $_obfuscate_6mlyHg�� = array( "show" => 1, "hide" => 0, "edit" => 0, "write" => 0, "must" => 0 );
            }
            if ( !( $_obfuscate_e_xqByHUUUzRtNY�['hide'] == 1 ) )
            {
                $_obfuscate_VdCStif4m42Fg�� = empty( $_obfuscate_9or11jDOGs2OA��["T_".$_obfuscate_8jhldA9Y9A��] ) ? $_obfuscate_EdcUyMWd6ZEv['dvalue'] : $_obfuscate_9or11jDOGs2OA��["T_".$_obfuscate_8jhldA9Y9A��];
                if ( $_obfuscate_EdcUyMWd6ZEv['otype'] == "signature" && $_obfuscate_e_xqByHUUUzRtNY�['write'] != 1 && empty( $_obfuscate_VdCStif4m42Fg�� ) )
                {
                    if ( $_obfuscate_EdcUyMWd6ZEv['otype'] == "datasource" && !$_obfuscate_e_xqByHUUUzRtNY�['write'] )
                    {
                    }
                    else if ( $_obfuscate_EdcUyMWd6ZEv['otype'] == "attach" && !$_obfuscate_e_xqByHUUUzRtNY�['write'] || empty( $_obfuscate_VdCStif4m42Fg�� ) )
                    {
                        $_obfuscate_6kJ_st61 = $this->_formatField( $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_e_xqByHUUUzRtNY�, $_obfuscate_VdCStif4m42Fg�� );
                        if ( !is_null( $_obfuscate_6kJ_st61 ) )
                        {
                            $_obfuscate_Tc82k3jOQ��[] = $_obfuscate_6kJ_st61;
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
            foreach ( $_obfuscate__LVTMsLmCyfG as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                foreach ( $_obfuscate_Tc82k3jOQ�� as $_obfuscate_3QY� => $_obfuscate_EGU� )
                {
                    if ( $_obfuscate_EGU�['name'] == "wf_field_".$_obfuscate_5w�� )
                    {
                        $_obfuscate_Tc82k3jOQ��[$_obfuscate_3QY�]['items'] = $_obfuscate_6A��;
                    }
                }
            }
        }
        return array(
            "items" => $_obfuscate_Tc82k3jOQ��
        );
    }

    private function _getBusinessData( $_obfuscate_olwD8Q�� )
    {
        $_obfuscate_pYzeLf4� = "init";
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
        return $_obfuscate_7eRCEirmYGaYE1FJCfc0hw��->getBusinessData( $_obfuscate_pYzeLf4�, "phone" );
    }

    private function _formatField( $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_e_xqByHUUUzRtNY�, $_obfuscate_VdCStif4m42Fg�� )
    {
        $_obfuscate_6kJ_st61 = array( );
        $_obfuscate_6kJ_st61['label'] = $_obfuscate_EdcUyMWd6ZEv['name'];
        $_obfuscate_6kJ_st61['tag'] = $_obfuscate_EdcUyMWd6ZEv['otype'];
        $_obfuscate_6kJ_st61['name'] = "wf_field_".$_obfuscate_EdcUyMWd6ZEv['id'];
        $_obfuscate_6kJ_st61['value'] = $_obfuscate_VdCStif4m42Fg��;
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
                if ( $_obfuscate_e_xqByHUUUzRtNY�['must'] )
                {
                    $_obfuscate_6kJ_st61['must'] = TRUE;
                }
                if ( !$_obfuscate_e_xqByHUUUzRtNY�['write'] )
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
            $this->_formatMacro( $_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_e_xqByHUUUzRtNY� );
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
            $this->_formatSignature( $_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_e_xqByHUUUzRtNY� );
            break;
        case "datasource" :
            $this->_formatDatasource( $_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_e_xqByHUUUzRtNY� );
            break;
        case "attach" :
            $this->_formatAttach( $_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_e_xqByHUUUzRtNY� );
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
            $_obfuscate_fr489CNrjWzinRUKCuozmmU� = new wfFieldFormaterForDealM( );
            $_obfuscate_6mlyHg�� = $_obfuscate_fr489CNrjWzinRUKCuozmmU�->stepFields[$_obfuscate_EdcUyMWd6ZEv['id']];
        }
        else if ( $this->from == "new" )
        {
            ( $this->flowId, $this->uFlowId, TRUE );
            $_obfuscate_fr489CNrjWzinRUKCuozmmU� = new wfFieldFormaterForDealM( );
            $_obfuscate_6mlyHg�� = $_obfuscate_fr489CNrjWzinRUKCuozmmU�->stepFields[$_obfuscate_EdcUyMWd6ZEv['id']];
        }
        if ( empty( $_obfuscate_6mlyHg�� ) )
        {
            $_obfuscate_6mlyHg�� = array( "show" => 1, "hide" => 0, "edit" => 0, "write" => 0, "must" => 0 );
        }
        foreach ( $_obfuscate_fr489CNrjWzinRUKCuozmmU�->flowFields as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( !( $_obfuscate_EdcUyMWd6ZEv['id'] != $_obfuscate_6A��['id'] ) )
            {
                $_obfuscate_60GquoKMPw�� = $_obfuscate_6A��;
            }
        }
        $_obfuscate_p5ZWxr4� = $_obfuscate_fr489CNrjWzinRUKCuozmmU�->__getOdata( $_obfuscate_60GquoKMPw��['odata'] );
        $_obfuscate_sSwuE42EWQ�� = $_obfuscate_p5ZWxr4�['expression'];
        $_obfuscate_w5qdpW_0mo2_qehs = $_obfuscate_fr489CNrjWzinRUKCuozmmU�->api_splitGongShi( $_obfuscate_sSwuE42EWQ�� );
        $_obfuscate_t1EW = "";
        foreach ( $_obfuscate_w5qdpW_0mo2_qehs as $_obfuscate_5w�� => $_obfuscate_GrQ� )
        {
            if ( !in_array( $_obfuscate_GrQ�, array( "+", "-", "*", "/" ) ) )
            {
                foreach ( $_obfuscate_fr489CNrjWzinRUKCuozmmU�->flowFields as $_obfuscate_WgE� )
                {
                    if ( $_obfuscate_GrQ� == $_obfuscate_WgE�['name'] )
                    {
                        $_obfuscate_w5qdpW_0mo2_qehs[$_obfuscate_5w��] = "wf|".$_obfuscate_WgE�['id'];
                    }
                    else
                    {
                        foreach ( $_obfuscate_fr489CNrjWzinRUKCuozmmU�->flowDetailFields as $_obfuscate_5DM� )
                        {
                            if ( $_obfuscate_GrQ� == $_obfuscate_5DM�['name'] )
                            {
                                $_obfuscate_w5qdpW_0mo2_qehs[$_obfuscate_5w��] = "wfd|".$_obfuscate_5DM�['id'];
                                $_obfuscate_t1EW = "mix=\"true\"";
                            }
                        }
                    }
                }
            }
        }
        $_obfuscate_sSwuE42EWQ�� = json_encode( $_obfuscate_w5qdpW_0mo2_qehs );
        $_obfuscate_sSwuE42EWQ�� = str_replace( "\"", "'", $_obfuscate_sSwuE42EWQ�� );
        $_obfuscate_6kJ_st61['gongshi'] = $_obfuscate_sSwuE42EWQ��;
        $_obfuscate_6kJ_st61['roundtype'] = $_obfuscate_p5ZWxr4�['dataFormat'];
        $_obfuscate_6kJ_st61['baoliu'] = $_obfuscate_p5ZWxr4�['baoliu'];
    }

    private function _formatDatasource( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_e_xqByHUUUzRtNY� )
    {
        $_obfuscate_p5ZWxr4� = $this->__getOdata( $_obfuscate_EdcUyMWd6ZEv['odata'] );
        $_obfuscate_EU3Hg�� = $_obfuscate_p5ZWxr4�['maps'];
        if ( is_null( $_obfuscate_EU3Hg��[0] ) )
        {
            foreach ( $this->flowFields as $_obfuscate_YIq2A8c� )
            {
                foreach ( $_obfuscate_EU3Hg�� as $_obfuscate_Vwty => $_obfuscate_Ag�� )
                {
                    if ( $_obfuscate_Ag�� == $_obfuscate_YIq2A8c�['name'] )
                    {
                        $_obfuscate_EU3Hg��[$_obfuscate_Vwty] = "wf_field_".$_obfuscate_YIq2A8c�['id'];
                    }
                }
            }
        }
        else
        {
            foreach ( $this->flowFields as $_obfuscate_YIq2A8c� )
            {
                foreach ( $_obfuscate_EU3Hg�� as $_obfuscate_Vwty => $_obfuscate_Ag�� )
                {
                    if ( $_obfuscate_Ag��['des'] == $_obfuscate_YIq2A8c�['name'] )
                    {
                        $_obfuscate_EU3Hg��[$_obfuscate_Vwty]['des'] = "wf_field_".$_obfuscate_YIq2A8c�['id'];
                    }
                }
            }
        }
        $_obfuscate_6kJ_st61['datasource'] = $_obfuscate_p5ZWxr4�['datasource'];
        $_obfuscate_EU3Hg�� = json_encode( $_obfuscate_EU3Hg�� );
        $_obfuscate_6kJ_st61['maps'] = str_replace( "\"", "'", $_obfuscate_EU3Hg�� );
        $_obfuscate_6kJ_st61['tabindex'] = $_obfuscate_EdcUyMWd6ZEv['order'];
    }

    private function _formatSignature( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_e_xqByHUUUzRtNY� )
    {
        $_obfuscate_p5ZWxr4� = $this->__getOdata( $_obfuscate_EdcUyMWd6ZEv['odata'] );
        if ( !$_obfuscate_6kJ_st61['readOnly'] )
        {
            $_obfuscate_6kJ_st61['signaturetype'] = $_obfuscate_p5ZWxr4�['type'];
        }
        $_obfuscate_6kJ_st61['name'] = "wf_fieldS_".$_obfuscate_EdcUyMWd6ZEv['id'];
        $_obfuscate_6kJ_st61['width'] = $_obfuscate_p5ZWxr4�['width'];
        $_obfuscate_6kJ_st61['height'] = $_obfuscate_p5ZWxr4�['height'];
    }

    private function _formatAttach( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_e_xqByHUUUzRtNY� )
    {
        if ( $_obfuscate_6kJ_st61['value'] )
        {
            $_obfuscate_1_pbjTIdLU49 = json_decode( $_obfuscate_6kJ_st61['value'], TRUE );
        }
        ( );
        $_obfuscate_2gg� = new fs( );
        $_obfuscate_UzL8YIsf8yyIKIS = $_obfuscate_2gg�->getListInfo4wfM( $_obfuscate_1_pbjTIdLU49, $this->wfAttachConfig, TRUE, "deal" );
        $_obfuscate_6kJ_st61['attach'] = $_obfuscate_UzL8YIsf8yyIKIS;
        if ( count( $_obfuscate_6kJ_st61['attach'] ) )
        {
            $_obfuscate_6kJ_st61['wfAttachConfig'] = $this->wfAttachConfig;
        }
        $_obfuscate_6kJ_st61['field'] = $_obfuscate_EdcUyMWd6ZEv['id'];
        $_obfuscate_6kJ_st61['write'] = $_obfuscate_e_xqByHUUUzRtNY�['write'];
    }

    private function _formatTextfield( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv )
    {
        if ( !$_obfuscate_6kJ_st61['readOnly'] )
        {
            $_obfuscate_p5ZWxr4� = $this->__getOdata( $_obfuscate_EdcUyMWd6ZEv['odata'] );
            $_obfuscate_6kJ_st61['dataType'] = $_obfuscate_p5ZWxr4�['dataType'];
            $this->_formatText4Setting( $_obfuscate_6kJ_st61, $_obfuscate_p5ZWxr4�['dataType'], $_obfuscate_p5ZWxr4�['dataFormat'] );
        }
    }

    private function _formatRadiofield( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv )
    {
        $_obfuscate_p5ZWxr4� = $this->__getOdata( $_obfuscate_EdcUyMWd6ZEv['odata'] );
        $_obfuscate_ = $_obfuscate_LQ8UKg�� = array( );
        foreach ( $_obfuscate_p5ZWxr4�['dataItems'] as $_obfuscate_6A�� )
        {
            $_obfuscate_LQ8UKg�� = array(
                "label" => $_obfuscate_6A��['name'],
                "value" => $_obfuscate_6A��['name']
            );
            if ( $_obfuscate_6A��['name'] == $_obfuscate_6kJ_st61['value'] )
            {
                $_obfuscate_LQ8UKg��['checked'] = TRUE;
            }
            $_obfuscate_[] = $_obfuscate_LQ8UKg��;
        }
        $_obfuscate_6kJ_st61['items'] = $_obfuscate_;
    }

    private function _formatCheckfield( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv )
    {
        $_obfuscate_p5ZWxr4� = $this->__getOdata( $_obfuscate_EdcUyMWd6ZEv['odata'] );
        $_obfuscate_VgKtFeg� = json_decode( htmlspecialchars( $_obfuscate_6kJ_st61['value'], ENT_NOQUOTES ), TRUE );
        $_obfuscate_ = $_obfuscate_LQ8UKg�� = array( );
        foreach ( $_obfuscate_p5ZWxr4�['dataItems'] as $_obfuscate_6A�� )
        {
            $_obfuscate_LQ8UKg�� = array(
                "label" => $_obfuscate_6A��['name'],
                "value" => $_obfuscate_6A��['name']
            );
            if ( !empty( $_obfuscate_VgKtFeg� ) || is_array( $_obfuscate_VgKtFeg� ) )
            {
                $_obfuscate_LQ8UKg��['checked'] = in_array( $_obfuscate_6A��['name'], $_obfuscate_VgKtFeg� );
            }
            else if ( $_obfuscate_6A��['checked'] == 1 )
            {
                $_obfuscate_LQ8UKg��['checked'] = TRUE;
            }
            $_obfuscate_[] = $_obfuscate_LQ8UKg��;
        }
        $_obfuscate_6kJ_st61['items'] = $_obfuscate_;
    }

    private function _formatSelectfield( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv )
    {
        $_obfuscate_p5ZWxr4� = $this->__getOdata( $_obfuscate_EdcUyMWd6ZEv['odata'] );
        $_obfuscate_[] = array( "label" => "", "value" => "" );
        foreach ( $_obfuscate_p5ZWxr4�['dataItems'] as $_obfuscate_6A�� )
        {
            $_obfuscate_LQ8UKg�� = array( );
            $_obfuscate_LQ8UKg��['label'] = $_obfuscate_6A��['name'];
            $_obfuscate_LQ8UKg��['value'] = $_obfuscate_p5ZWxr4�['dataType'] == "int" ? $_obfuscate_6A��['value'] : $_obfuscate_6A��['name'];
            if ( $_obfuscate_LQ8UKg��['value'] == $_obfuscate_6kJ_st61['value'] )
            {
                $_obfuscate_LQ8UKg��['selected'] = TRUE;
            }
            $_obfuscate_[] = $_obfuscate_LQ8UKg��;
        }
        $_obfuscate_6kJ_st61['items'] = $_obfuscate_;
    }

    private function _formatMacro( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv, $_obfuscate_6mlyHg�� )
    {
        global $CNOA_DB;
        $_obfuscate_p5ZWxr4� = $this->__getOdata( $_obfuscate_EdcUyMWd6ZEv['odata'] );
        $_obfuscate_6kJ_st61['dataType'] = $_obfuscate_p5ZWxr4�['dataType'];
        if ( !$_obfuscate_6kJ_st61['readOnly'] )
        {
            if ( $_obfuscate_6kJ_st61['dataType'] == "loginname" )
            {
                global $CNOA_SESSION;
                $_obfuscate_6kJ_st61['value'] = $CNOA_SESSION->get( "UID" );
                $_obfuscate_6kJ_st61['displayValue'] = app::loadapp( "main", "user" )->api_getUserNameByUids( intval( $_obfuscate_6kJ_st61['value'] ) );
            }
            else if ( $_obfuscate_p5ZWxr4�['dataType'] != "moneyconvert" )
            {
                $_obfuscate_6kJ_st61['value'] = $this->_getMacroValue( $_obfuscate_p5ZWxr4� );
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
        if ( $_obfuscate_6mlyHg��['write'] == 1 )
        {
            if ( $_obfuscate_p5ZWxr4�['allowedit'] == 1 && !in_array( $_obfuscate_p5ZWxr4�['dataType'], array( "flowname", "flownum" ) ) )
            {
                $_obfuscate_6kJ_st61['readOnly'] = FALSE;
            }
            else if ( $_obfuscate_p5ZWxr4�['dataType'] == "attLeave" || $_obfuscate_p5ZWxr4�['dataType'] == "attTime" )
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
        if ( $_obfuscate_p5ZWxr4�['dataType'] == "moneyconvert" )
        {
            ( $_obfuscate_TlvKhtsoOQ�� );
            $GLOBALS['GLOBALS']['UWFCACHE'] = new wfCache( );
            ( $GLOBALS['UWFCACHE']->getFlowId( ), $_obfuscate_0Ul8BBkt, FALSE, $this->uFlowId );
            $_obfuscate_yjs8_LzXVySVfFwNCXGOS9IvPvS41w�� = new wfFieldFormaterForDealM( );
            $_obfuscate_BJBeoQ4Zepw� = $_obfuscate_yjs8_LzXVySVfFwNCXGOS9IvPvS41w��->api_getFieldInfoByName( $_obfuscate_p5ZWxr4�['from'], $this->flowId );
            $_obfuscate_6kJ_st61['from'] = "wf_field_".$_obfuscate_BJBeoQ4Zepw�['id'];
        }
        if ( $_obfuscate_p5ZWxr4�['dataType'] == "huiqian" )
        {
            $_obfuscate_ouqX2cxvhA�� = "";
            if ( ( integer )$this->uFlowId != 0 )
            {
                $_obfuscate_O1qQ = $_obfuscate_p5ZWxr4�['huiqianTpl'];
                ( $this->uFlowId );
                $_obfuscate_bIsJe6A� = new wfCache( );
                $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "stepId", "truename", "message", "writetime" ), "wf_u_huiqian", "WHERE uFlowId=".$this->uFlowId." ORDER BY posttime" );
                if ( !is_array( $_obfuscate_mPAjEGLn ) )
                {
                    $_obfuscate_mPAjEGLn = array( );
                }
                foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A�� )
                {
                    $_obfuscate_A260kBOEy4wB = empty( $_obfuscate_6A��['writetime'] ) ? "---" : date( "Y-m-d H:i", $_obfuscate_6A��['writetime'] );
                    $_obfuscate_5NhzjnJq_f8� = $_obfuscate_bIsJe6A�->getStepByStepId( $_obfuscate_6A��['stepId'] );
                    $_obfuscate_77tGbWOiZg�� = array(
                        $_obfuscate_5NhzjnJq_f8�['stepName'],
                        $_obfuscate_6A��['truename'],
                        $_obfuscate_A260kBOEy4wB,
                        $_obfuscate_6A��['message']
                    );
                    $_obfuscate_ouqX2cxvhA�� .= "<span style=\"line-height:25px;\">".str_replace( array( "{S}", "{U}", "{T}", "{I}" ), $_obfuscate_77tGbWOiZg��, $_obfuscate_O1qQ )."</span><br />";
                }
            }
            $_obfuscate_6kJ_st61['displayValue'] = $_obfuscate_ouqX2cxvhA��;
        }
    }

    private function _formatChoice( &$_obfuscate_6kJ_st61, $_obfuscate_EdcUyMWd6ZEv )
    {
        $_obfuscate_p5ZWxr4� = $this->__getOdata( $_obfuscate_EdcUyMWd6ZEv['odata'] );
        $_obfuscate_VgKtFeg� = $_obfuscate_6kJ_st61['value'];
        $_obfuscate_6kJ_st61['displayValue'] = "";
        switch ( $_obfuscate_p5ZWxr4�['dataType'] )
        {
        case "user_sel" :
            $_obfuscate_6kJ_st61['dataType'] = "user";
            $_obfuscate_6kJ_st61['multi'] = FALSE;
            if ( empty( $_obfuscate_VgKtFeg� ) )
            {
                break;
            }
            $_obfuscate_6kJ_st61['displayValue'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_VgKtFeg� );
            break;
        case "users_sel" :
            $_obfuscate_6kJ_st61['dataType'] = "user";
            $_obfuscate_6kJ_st61['multi'] = TRUE;
            if ( empty( $_obfuscate_VgKtFeg� ) )
            {
                break;
            }
            $_obfuscate_VgKtFeg� = app::loadapp( "main", "user" )->api_getUserTruenameByUid( explode( ",", $_obfuscate_VgKtFeg� ) );
            $_obfuscate_6kJ_st61['displayValue'] = implode( ",", $_obfuscate_VgKtFeg� );
            break;
        case "job_sel" :
            $_obfuscate_6kJ_st61['dataType'] = "job";
            $_obfuscate_6kJ_st61['multi'] = FALSE;
            if ( empty( $_obfuscate_VgKtFeg� ) )
            {
                break;
            }
            $_obfuscate_6kJ_st61['displayValue'] = app::loadapp( "main", "job" )->api_getNameById( $_obfuscate_VgKtFeg� );
            break;
        case "jobs_sel" :
            $_obfuscate_6kJ_st61['dataType'] = "job";
            $_obfuscate_6kJ_st61['multi'] = TRUE;
            if ( empty( $_obfuscate_VgKtFeg� ) )
            {
                break;
            }
            $_obfuscate_VgKtFeg� = app::loadapp( "main", "job" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg� ) );
            $_obfuscate_6kJ_st61['displayValue'] = implode( ",", $_obfuscate_VgKtFeg� );
            break;
        case "dept_sel" :
            $_obfuscate_6kJ_st61['dataType'] = "dept";
            $_obfuscate_6kJ_st61['multi'] = FALSE;
            if ( empty( $_obfuscate_VgKtFeg� ) )
            {
                break;
            }
            $_obfuscate_6kJ_st61['displayValue'] = app::loadapp( "main", "struct" )->api_getNameById( $_obfuscate_VgKtFeg� );
            break;
        case "depts_sel" :
            $_obfuscate_6kJ_st61['dataType'] = "dept";
            $_obfuscate_6kJ_st61['multi'] = TRUE;
            if ( empty( $_obfuscate_VgKtFeg� ) )
            {
                break;
            }
            $_obfuscate_VgKtFeg� = app::loadapp( "main", "struct" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg� ) );
            $_obfuscate_6kJ_st61['displayValue'] = implode( ",", $_obfuscate_VgKtFeg� );
            break;
        case "station_sel" :
            $_obfuscate_6kJ_st61['dataType'] = "station";
            $_obfuscate_6kJ_st61['multi'] = FALSE;
            if ( empty( $_obfuscate_VgKtFeg� ) )
            {
                break;
            }
            $_obfuscate_6kJ_st61['displayValue'] = app::loadapp( "main", "station" )->api_getNameById( $_obfuscate_VgKtFeg� );
            break;
        case "stations_sel" :
            $_obfuscate_6kJ_st61['dataType'] = "station";
            $_obfuscate_6kJ_st61['multi'] = TRUE;
            if ( empty( $_obfuscate_VgKtFeg� ) )
            {
                break;
            }
            $_obfuscate_VgKtFeg� = app::loadapp( "main", "station" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg� ) );
            $_obfuscate_6kJ_st61['displayValue'] = implode( ",", $_obfuscate_VgKtFeg� );
            break;
        case "time_sel" :
            switch ( $_obfuscate_p5ZWxr4�['dataFormat'] )
            {
            case 100 :
                $_obfuscate_ead0WM� = "HH:MM";
                break;
            case 200 :
                $_obfuscate_ead0WM� = "HH:MM AM";
                break;
            case 300 :
                $_obfuscate_ead0WM� = "HH:MM a";
            }
            $_obfuscate_6kJ_st61['dtfmt'] = intval( $_obfuscate_p5ZWxr4�['dataFormat'] );
            $_obfuscate_6kJ_st61['dataType'] = "time";
            $_obfuscate_6kJ_st61['format'] = $this->_getTimeFormat( $_obfuscate_p5ZWxr4�['dataFormat'] );
            if ( !empty( $_obfuscate_VgKtFeg� ) || $_obfuscate_VgKtFeg� != "default" && $_obfuscate_VgKtFeg� != "null" )
            {
                $_obfuscate_6kJ_st61['displayValue'] = $_obfuscate_VgKtFeg�;
            }
            else
            {
                if ( !( $_obfuscate_VgKtFeg� == "default" ) )
                {
                    break;
                }
                $_obfuscate_VgKtFeg� = date( $_obfuscate_6kJ_st61['format'] );
                $_obfuscate_6kJ_st61['displayValue'] = str_replace( array( "am", "pm" ), array( "上午", "下午" ), $_obfuscate_VgKtFeg� );
                $_obfuscate_6kJ_st61['value'] = $_obfuscate_6kJ_st61['displayValue'];
            }
            break;
        case "date_sel" :
            switch ( $_obfuscate_p5ZWxr4�['dataFormat'] )
            {
            case 100 :
                $_obfuscate_ead0WM� = "yyyy-MM-dd";
                break;
            case 200 :
                $_obfuscate_ead0WM� = "yyyy-MM";
                break;
            case 300 :
                $_obfuscate_ead0WM� = "yy-mm-dd";
                break;
            case 400 :
                $_obfuscate_ead0WM� = "yyyyMMdd";
                break;
            case 500 :
                $_obfuscate_ead0WM� = "mm-dd yyyy";
                break;
            case 600 :
                $_obfuscate_ead0WM� = "yyyy年MM月";
                break;
            case 700 :
                $_obfuscate_ead0WM� = "yyyy年MM月dd日";
                break;
            case 800 :
                $_obfuscate_ead0WM� = "mm月dd日";
                break;
            case 900 :
                $_obfuscate_ead0WM� = "yyyy.MM";
                break;
            case 1000 :
                $_obfuscate_ead0WM� = "yyyy.MM.dd";
                break;
            case 1100 :
                $_obfuscate_ead0WM� = "mm.dd";
                break;
            case 1200 :
                $_obfuscate_ead0WM� = "yyyy-MM-dd HH:mm";
                break;
            case 1300 :
                $_obfuscate_ead0WM� = "yyyy年MM月dd日 HH:mm";
            }
            $_obfuscate_6kJ_st61['dtfmt'] = $_obfuscate_ead0WM�;
            $_obfuscate_6kJ_st61['dataType'] = "date";
            $_obfuscate_7R7jAawdKeMxGQ�� = $this->_getDataFormat( $_obfuscate_p5ZWxr4�['dataFormat'] );
            $_obfuscate_6kJ_st61['format'] = $_obfuscate_7R7jAawdKeMxGQ��['format'];
            if ( !empty( $_obfuscate_VgKtFeg� ) || $_obfuscate_VgKtFeg� != "default" && $_obfuscate_VgKtFeg� != "null" )
            {
                $_obfuscate_6kJ_st61['displayValue'] = $_obfuscate_6kJ_st61['value'] == "null" ? "" : $_obfuscate_6kJ_st61['value'];
            }
            else
            {
                if ( $_obfuscate_VgKtFeg� == "default" )
                {
                    $_obfuscate_6kJ_st61['displayValue'] = date( $_obfuscate_7R7jAawdKeMxGQ��['format'] );
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
