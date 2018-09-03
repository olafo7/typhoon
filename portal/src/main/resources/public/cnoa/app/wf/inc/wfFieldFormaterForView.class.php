<?php

class wfFieldFormaterForView extends wfCommon
{

    private $t_style = "wf_s_form_style";
    private $styleList = NULL;
    private $flowId = 0;
    private $uFlowId = 0;
    private $stepId = 0;
    private $flowInfo = array( );
    private $flowFields = array( );
    private $flowDetailFields = array( );
    private $stepFields = array( );
    private $stepColFields = array( );
    private $fieldRule = array( );
    private $extHtml = "";
    private $startStep = NULL;
    private $tdNum = 0;
    private $childSeeParent = "";
    private $puStepId = "";
    private $fromParentValue = array( );

    public function __construct( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt, $_obfuscate_FygnrXwW3bt6 = TRUE, $_obfuscate_LeS8hw?? = "", $_obfuscate_TlvKhtsoOQ?? = 0, $_obfuscate_pcmxgqXbvl0yPCL_KHM? = "", $_obfuscate_tp9SP3Q9McA? = "" )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $this->flowId = $_obfuscate_F4AbnVRh;
        $this->uFlowId = $_obfuscate_TlvKhtsoOQ??;
        $this->childSeeParent = $_obfuscate_pcmxgqXbvl0yPCL_KHM?;
        $this->puStepId = $_obfuscate_tp9SP3Q9McA?;
        $this->styleList = $this->__getStylesList( );
        $this->startStep = $_obfuscate_FygnrXwW3bt6;
        if ( $this->startStep )
        {
            $this->flowInfo = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$this->flowId."'" );
            $this->stepId = $this->flowInfo['startStepId'];
            $this->flowFields = $CNOA_DB->db_select( "*", $this->t_set_field, "WHERE `flowId`='".$this->flowId."'" );
            if ( !is_array( $this->flowFields ) )
            {
                $this->flowFields = array( );
            }
            $this->flowDetailFields = $CNOA_DB->db_select( "*", $this->t_set_field_detail, "WHERE `flowId`='".$this->flowId."'" );
            if ( !is_array( $this->flowDetailFields ) )
            {
                $this->flowDetailFields = array( );
            }
            $this->stepFields = $CNOA_DB->db_select( "*", $this->t_set_step_fields, "WHERE `flowId`='".$this->flowId."' AND `stepId`='{$this->stepId}'" );
            if ( !is_array( $this->stepFields ) )
            {
                $this->stepFields = array( );
            }
            $_obfuscate_juwe = array( );
            foreach ( $this->stepFields as $_obfuscate_B0M? )
            {
                if ( $_obfuscate_B0M?['from'] == 1 )
                {
                    $this->stepColFields[$_obfuscate_B0M?['fieldId']] = $_obfuscate_B0M?;
                }
                else
                {
                    $_obfuscate_juwe[$_obfuscate_B0M?['fieldId']] = $_obfuscate_B0M?;
                }
            }
            $this->stepFields = $_obfuscate_juwe;
        }
        else if ( $_obfuscate_LeS8hw?? == "done" )
        {
            $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
            $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "uStepId" ), $this->t_use_step, "WHERE `uFlowId` = '".$this->uFlowId."' AND (`uid` = '{$_obfuscate_7Ri3}' OR `proxyUid` = '{$_obfuscate_7Ri3}') " );
            if ( !is_array( $_obfuscate_Tx7M9W ) )
            {
                $_obfuscate_Tx7M9W = array( );
            }
            $_obfuscate_QwT4KwrB2w?? = array( 0 );
            foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5w?? => $_obfuscate_6A?? )
            {
                $_obfuscate_QwT4KwrB2w??[] = $_obfuscate_6A??['uStepId'];
            }
            $this->flowInfo = $GLOBALS['UWFCACHE']->getFlow( );
            $this->stepId = $_obfuscate_0Ul8BBkt;
            $this->flowFields = $GLOBALS['UWFCACHE']->getFlowFields( );
            if ( !is_array( $this->flowFields ) )
            {
                $this->flowFields = array( );
            }
            $this->flowDetailFields = $GLOBALS['UWFCACHE']->getDetailFields( );
            if ( !is_array( $this->flowDetailFields ) )
            {
                $this->flowDetailFields = array( );
            }
            $this->stepFields = $GLOBALS['UWFCACHE']->mergeMyFields( $_obfuscate_QwT4KwrB2w??, $this->uFlowId );
            foreach ( $this->stepFields as $_obfuscate_B0M? )
            {
                if ( $_obfuscate_B0M?['from'] == 1 )
                {
                    $this->stepColFields[$_obfuscate_B0M?['fieldId']] = $_obfuscate_B0M?;
                }
                else
                {
                    $_obfuscate_juwe[$_obfuscate_B0M?['fieldId']] = $_obfuscate_B0M?;
                }
            }
            $this->stepFields = $_obfuscate_juwe;
        }
        else
        {
            $this->flowInfo = $GLOBALS['UWFCACHE']->getFlow( );
            $this->stepId = $_obfuscate_0Ul8BBkt;
            $this->flowFields = $GLOBALS['UWFCACHE']->getFlowFields( );
            if ( !is_array( $this->flowFields ) )
            {
                $this->flowFields = array( );
            }
            $this->flowDetailFields = $GLOBALS['UWFCACHE']->getDetailFields( );
            if ( !is_array( $this->flowDetailFields ) )
            {
                $this->flowDetailFields = array( );
            }
            if ( $this->childSeeParent == "childSeeParent" )
            {
                $this->stepFields = $GLOBALS['UWFCACHE']->getStepFields( $this->puStepId, self::FIELD_RULE_NORMAL );
                $this->stepColFields = $GLOBALS['UWFCACHE']->getStepFields( $this->puStepId, self::FIELD_RULE_DETAIL );
            }
            else
            {
                $this->stepFields = $GLOBALS['UWFCACHE']->getStepFields( $this->stepId, self::FIELD_RULE_NORMAL );
                $this->stepColFields = $GLOBALS['UWFCACHE']->getStepFields( $this->stepId, self::FIELD_RULE_DETAIL );
            }
        }
        $_obfuscate_l5xoT48YaQ?? = getpar( $_GET, "childId", 0 );
        if ( !empty( $_obfuscate_l5xoT48YaQ?? ) )
        {
            $_obfuscate_jOcDpChC9w?? = $CNOA_DB->db_getone( "*", $this->t_use_step_child_flow, "WHERE `id` = ".$_obfuscate_l5xoT48YaQ??." " );
        }
        else if ( !empty( $_obfuscate_TlvKhtsoOQ?? ) )
        {
            $_obfuscate_jOcDpChC9w?? = $CNOA_DB->db_getone( "*", $this->t_use_step_child_flow, "WHERE `uFlowId` = ".$_obfuscate_TlvKhtsoOQ??." " );
        }
        else
        {
            return;
        }
        if ( empty( $_obfuscate_jOcDpChC9w?? ) )
        {
            return;
        }
        ( $_obfuscate_jOcDpChC9w??['puFlowId'] );
        $_obfuscate_e53ODz04JQ?? = new wfCache( );
        $_obfuscate_8LH7ik2lzjhs7g?? = $_obfuscate_e53ODz04JQ??->getConfig( "step_child_kongjian" );
        if ( empty( $_obfuscate_8LH7ik2lzjhs7g?? ) )
        {
            return;
        }
        foreach ( $this->flowFields as $_obfuscate_5w?? => $_obfuscate_6A?? )
        {
            foreach ( $_obfuscate_8LH7ik2lzjhs7g?? as $_obfuscate_eBU_Sjc? )
            {
                $_obfuscate_WO71JHrWfe8IY1qw?? = str_replace( "T_", "", $_obfuscate_eBU_Sjc?['childKongjian'] );
                if ( !( $_obfuscate_WO71JHrWfe8IY1qw?? == $_obfuscate_6A??['id'] ) && !( $_obfuscate_eBU_Sjc?['arrow'] == "left" ) )
                {
                    $_obfuscate_VgKtFeg? = $CNOA_DB->db_getfield( $_obfuscate_eBU_Sjc?['parentKongjian'], "z_wf_t_".$_obfuscate_eBU_Sjc?['flowId'], "WHERE `uFlowId` = ".$_obfuscate_jOcDpChC9w??['puFlowId'] );
                    if ( $_obfuscate_eBU_Sjc?['parentType'] == "loginname" )
                    {
                        $_obfuscate_VgKtFeg? = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_VgKtFeg? );
                    }
                    else if ( $_obfuscate_eBU_Sjc?['parentType'] == "user_sel" )
                    {
                        $_obfuscate_VgKtFeg? = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_VgKtFeg? );
                    }
                    else if ( $_obfuscate_eBU_Sjc?['parentType'] == "users_sel" )
                    {
                        if ( !empty( $_obfuscate_VgKtFeg? ) )
                        {
                            $_obfuscate_xs33Yt_k = app::loadapp( "main", "user" )->api_getUserTruenameByUid( explode( ",", $_obfuscate_VgKtFeg? ) );
                            $_obfuscate_VgKtFeg? = implode( ",", $_obfuscate_xs33Yt_k );
                        }
                    }
                    else if ( $_obfuscate_eBU_Sjc?['parentType'] == "dept_sel" )
                    {
                        if ( !empty( $_obfuscate_VgKtFeg? ) )
                        {
                            $_obfuscate_VgKtFeg? = app::loadapp( "main", "struct" )->api_getNameById( $_obfuscate_VgKtFeg? );
                        }
                    }
                    else if ( $_obfuscate_eBU_Sjc?['parentType'] == "depts_sel" )
                    {
                        if ( !empty( $_obfuscate_VgKtFeg? ) )
                        {
                            $_obfuscate_VgKtFeg? = app::loadapp( "main", "struct" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg? ) );
                            $_obfuscate_VgKtFeg? = implode( ",", $_obfuscate_VgKtFeg? );
                        }
                    }
                    else if ( $_obfuscate_eBU_Sjc?['parentType'] == "station_sel" )
                    {
                        if ( !empty( $_obfuscate_VgKtFeg? ) )
                        {
                            $_obfuscate_VgKtFeg? = app::loadapp( "main", "station" )->api_getNameById( $_obfuscate_VgKtFeg? );
                        }
                    }
                    else if ( $_obfuscate_eBU_Sjc?['parentType'] == "stations_sel" )
                    {
                        if ( !empty( $_obfuscate_VgKtFeg? ) )
                        {
                            $_obfuscate_VgKtFeg? = app::loadapp( "main", "station" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg? ) );
                            $_obfuscate_VgKtFeg? = implode( ",", $_obfuscate_VgKtFeg? );
                        }
                    }
                    else if ( $_obfuscate_eBU_Sjc?['parentType'] == "job_sel" )
                    {
                        if ( !empty( $_obfuscate_VgKtFeg? ) )
                        {
                            $_obfuscate_VgKtFeg? = app::loadapp( "main", "job" )->api_getNameById( $_obfuscate_VgKtFeg? );
                        }
                    }
                    else if ( $_obfuscate_eBU_Sjc?['parentType'] == "jobs_sel" && !empty( $_obfuscate_VgKtFeg? ) )
                    {
                        $_obfuscate_VgKtFeg? = app::loadapp( "main", "job" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg? ) );
                        $_obfuscate_VgKtFeg? = implode( ",", $_obfuscate_VgKtFeg? );
                    }
                    $this->fromParentValue[$_obfuscate_6A??['id']] = $_obfuscate_VgKtFeg?;
                }
            }
        }
        if ( empty( $this->flowDetailFields ) )
        {
            return;
        }
        foreach ( $this->flowDetailFields as $_obfuscate_5w?? => $_obfuscate_6A?? )
        {
            foreach ( $_obfuscate_8LH7ik2lzjhs7g?? as $_obfuscate_eBU_Sjc? )
            {
                $_obfuscate_WO71JHrWfe8IY1qw?? = str_replace( "D_", "", $_obfuscate_eBU_Sjc?['childKongjian'] );
                $_obfuscate_KWTaK5TDFRT5UQ8PK6w? = str_replace( "D_", "", $_obfuscate_eBU_Sjc?['parentKongjian'] );
                if ( $_obfuscate_WO71JHrWfe8IY1qw?? == $_obfuscate_6A??['id'] )
                {
                    $_obfuscate_8XjS1n72[] = $_obfuscate_WO71JHrWfe8IY1qw??;
                    $_obfuscate_8XjS1n72[] = $_obfuscate_KWTaK5TDFRT5UQ8PK6w?;
                    $_obfuscate_JSsk2BzWfQ??[] = $_obfuscate_WO71JHrWfe8IY1qw??;
                    $_obfuscate_MONibJFKxA??[] = $_obfuscate_KWTaK5TDFRT5UQ8PK6w?;
                    $_obfuscate_rpYdnic9Jh1_aav["D_".$_obfuscate_WO71JHrWfe8IY1qw??] = array(
                        "cid" => "D_".$_obfuscate_WO71JHrWfe8IY1qw??,
                        "pid" => "D_".$_obfuscate_KWTaK5TDFRT5UQ8PK6w?
                    );
                    $_obfuscate_rpYdnic9Jh1_aav["D_".$_obfuscate_KWTaK5TDFRT5UQ8PK6w?] = array(
                        "cid" => "D_".$_obfuscate_WO71JHrWfe8IY1qw??,
                        "pid" => "D_".$_obfuscate_KWTaK5TDFRT5UQ8PK6w?
                    );
                }
            }
        }
        if ( empty( $_obfuscate_8XjS1n72 ) )
        {
            return;
        }
        $_obfuscate_7Hp0w_lfFt4? = $CNOA_DB->db_select( array( "id", "fid" ), $this->t_set_field_detail, "WHERE `id` IN (".implode( ",", $_obfuscate_8XjS1n72 ).") " );
        foreach ( $_obfuscate_7Hp0w_lfFt4? as $_obfuscate_5w?? => $_obfuscate_6A?? )
        {
            if ( in_array( $_obfuscate_6A??['id'], $_obfuscate_JSsk2BzWfQ?? ) )
            {
                $_obfuscate_M4z['child'][$_obfuscate_6A??['fid']][] = "D_".$_obfuscate_6A??['id'];
                $_obfuscate_rpYdnic9Jh1_aav["D_".$_obfuscate_6A??['id']]['cfid'] = $_obfuscate_6A??['fid'];
            }
            else if ( in_array( $_obfuscate_6A??['id'], $_obfuscate_MONibJFKxA?? ) )
            {
                $_obfuscate_M4z['parent'][$_obfuscate_6A??['fid']][] = "D_".$_obfuscate_6A??['id'];
                $_obfuscate_rpYdnic9Jh1_aav["D_".$_obfuscate_6A??['id']]['pfid'] = $_obfuscate_6A??['fid'];
            }
        }
        $_obfuscate_MYQ3MdCHwszPfGoLQw?? = array( );
        $_obfuscate_7wiD6aiSGQ?? = $CNOA_DB->db_getfield( "flowId", $this->t_use_flow, "WHERE `uFlowId` = ".$_obfuscate_jOcDpChC9w??['puFlowId']." " );
        foreach ( $_obfuscate_M4z['parent'] as $_obfuscate_5w?? => $_obfuscate_6A?? )
        {
            $_obfuscate_MYQ3MdCHwszPfGoLQw??[$_obfuscate_5w??] = $CNOA_DB->db_select( $_obfuscate_6A??, "z_wf_d_".$_obfuscate_7wiD6aiSGQ??."_".$_obfuscate_5w??, "WHERE `uFlowId` = ".$_obfuscate_jOcDpChC9w??['puFlowId']." " );
        }
        foreach ( $_obfuscate_MYQ3MdCHwszPfGoLQw?? as $_obfuscate_Vwty => $_obfuscate_eBU_Sjc? )
        {
            $_obfuscate_7w?? = 1;
            foreach ( $_obfuscate_eBU_Sjc? as $_obfuscate_5w?? => $_obfuscate_6A?? )
            {
                foreach ( $_obfuscate_6A?? as $_obfuscate_ty0? => $_obfuscate_snM? )
                {
                    $_obfuscate_KBWh = $_obfuscate_rpYdnic9Jh1_aav[$_obfuscate_ty0?]['cid'];
                    $_obfuscate_PJl1Rw?? = $_obfuscate_rpYdnic9Jh1_aav[$_obfuscate_KBWh]['cfid'];
                    $_obfuscate_KBWh = str_replace( "D_", "", $_obfuscate_KBWh );
                    $_obfuscate_SeV31Q??[$_obfuscate_PJl1Rw??][$_obfuscate_KBWh] = $_obfuscate_snM?;
                }
                foreach ( $_obfuscate_SeV31Q?? as $_obfuscate_ty0? => $_obfuscate_snM? )
                {
                    $this->childDetailDB[$_obfuscate_ty0?][$_obfuscate_7w??] = $_obfuscate_snM?;
                    ++$_obfuscate_7w??;
                }
            }
        }
    }

    public function __destruct( )
    {
    }

    public function crteateFormHtml( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        ( $this->uFlowId );
        $_obfuscate_e53ODz04JQ?? = new wfCache( );
        $_obfuscate_eFn3J0h1mU7Ghetl = array( );
        if ( !$this->startStep )
        {
            $_obfuscate_4puJ00P0cS = $this->api_getWfFieldData( $this->flowId, $GLOBALS['UWFCACHE']->getUflowId( ), TRUE );
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", "wf_s_step_child_kongjian", "WHERE `flowId`=".$this->flowId." OR `bangdingFlow`={$this->flowId}" );
            if ( !is_array( $_obfuscate_mPAjEGLn ) )
            {
                $_obfuscate_mPAjEGLn = array( );
            }
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A?? )
            {
                if ( $_obfuscate_6A??['flowId'] == $this->flowId && $_obfuscate_6A??['arrow'] == "right" )
                {
                    if ( !( $_obfuscate_6A??['childType'] == "flownum" ) || !( $_obfuscate_6A??['childType'] == "flowname" ) )
                    {
                        $_obfuscate_8jhldA9Y9A?? = str_replace( "T_", "", $_obfuscate_6A??['parentKongjian'] );
                        $_obfuscate_eFn3J0h1mU7Ghetl[$_obfuscate_8jhldA9Y9A??] = $_obfuscate_6A??;
                    }
                }
                else if ( !( $_obfuscate_6A??['bangdingFlow'] == $this->flowId ) && !( $_obfuscate_6A??['arrow'] == "left" ) && !( $_obfuscate_6A??['parentType'] == "flownum" ) || !( $_obfuscate_6A??['parentType'] == "flowname" ) )
                {
                    $_obfuscate_8jhldA9Y9A?? = str_replace( "T_", "", $_obfuscate_6A??['childKongjian'] );
                    $_obfuscate_eFn3J0h1mU7Ghetl[$_obfuscate_8jhldA9Y9A??] = $_obfuscate_6A??;
                }
            }
            unset( $_obfuscate_mPAjEGLn );
            unset( $_obfuscate_8jhldA9Y9A?? );
        }
        else
        {
            $_obfuscate_4puJ00P0cS = array( );
        }
        $_obfuscate_ZQjbv3g? = $CNOA_DB->db_getone( array( "flowNumber", "flowName" ), $this->t_use_flow, "WHERE `uFlowId`=".$this->uFlowId );
        foreach ( $this->flowFields as $_obfuscate_WgE? )
        {
            if ( isset( $this->fromParentValue[$_obfuscate_WgE?['id']] ) )
            {
                $_obfuscate_4puJ00P0cS[$_obfuscate_WgE?['id']] = $this->fromParentValue[$_obfuscate_WgE?['id']];
            }
            if ( array_key_exists( $_obfuscate_WgE?['id'], $_obfuscate_eFn3J0h1mU7Ghetl ) )
            {
                $_obfuscate_WgE?['otype'] = "jumpKongjian";
                $_obfuscate_WgE?['jump'] = $_obfuscate_eFn3J0h1mU7Ghetl[$_obfuscate_WgE?['id']];
            }
            $_obfuscate_6mlyHg?? = $this->stepFields[$_obfuscate_WgE?['id']];
            if ( empty( $_obfuscate_6mlyHg?? ) )
            {
                $_obfuscate_6mlyHg?? = array( "show" => 1, "hide" => 0, "edit" => 0, "write" => 0, "must" => 0, "status" => 1 );
            }
            $this->fieldRule = $_obfuscate_6mlyHg??;
            if ( $CNOA_SESSION->get( "wfRoot" ) == "allow" )
            {
                $_obfuscate_6mlyHg??['show'] = 1;
                $_obfuscate_LQ8UKg?? = $this->formatFormItem( $_obfuscate_WgE?, $_obfuscate_6mlyHg??, $_obfuscate_4puJ00P0cS[$_obfuscate_WgE?['id']] );
            }
            else if ( $this->childSeeParent == "childSeeParent" )
            {
                if ( $_obfuscate_6mlyHg??['show'] == 1 && $_obfuscate_6mlyHg??['status'] == 1 )
                {
                    $_obfuscate_LQ8UKg?? = $this->formatFormItem( $_obfuscate_WgE?, $_obfuscate_6mlyHg??, $_obfuscate_4puJ00P0cS[$_obfuscate_WgE?['id']] );
                }
                else if ( in_array( $_obfuscate_WgE?['otype'], array( "checkbox", "radio" ) ) )
                {
                    $_obfuscate_LQ8UKg?? = $this->formatFormItem( $_obfuscate_WgE?, $_obfuscate_6mlyHg??, $_obfuscate_4puJ00P0cS[$_obfuscate_WgE?['id']] );
                }
                else
                {
                    $_obfuscate_LQ8UKg?? = "";
                }
            }
            else if ( $_obfuscate_6mlyHg??['show'] == 1 )
            {
                $_obfuscate_LQ8UKg?? = $this->formatFormItem( $_obfuscate_WgE?, $_obfuscate_6mlyHg??, $_obfuscate_4puJ00P0cS[$_obfuscate_WgE?['id']] );
            }
            else if ( in_array( $_obfuscate_WgE?['otype'], array( "checkbox", "radio" ) ) )
            {
                $_obfuscate_LQ8UKg?? = $this->formatFormItem( $_obfuscate_WgE?, $_obfuscate_6mlyHg??, $_obfuscate_4puJ00P0cS[$_obfuscate_WgE?['id']] );
            }
            else
            {
                $_obfuscate_LQ8UKg?? = "";
            }
            $CNOA_SESSION->del( "wfRoot" );
            if ( $_obfuscate_WgE?['otype'] == "attach" )
            {
                $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "uStepId" ), $this->t_use_step, "WHERE `uFlowId`=".$this->uFlowId." AND `uid` = {$_obfuscate_7Ri3} " );
                if ( !is_array( $_obfuscate_Tx7M9W ) )
                {
                    $_obfuscate_Tx7M9W = array( );
                }
                $_obfuscate_QwT4KwrB2w?? = array( );
                foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5w?? => $_obfuscate_6A?? )
                {
                    $_obfuscate_QwT4KwrB2w??[] = $_obfuscate_6A??['uStepId'];
                }
                $_obfuscate_vzGArcjOKr8_7A??['allowReject'] = 0;
                $_obfuscate_vzGArcjOKr8_7A??['allowAttachAdd'] = 0;
                $_obfuscate_vzGArcjOKr8_7A??['allowAttachEdit'] = 0;
                $_obfuscate_vzGArcjOKr8_7A??['allowAttachDelete'] = 0;
                $_obfuscate_vzGArcjOKr8_7A??['allowAttachView'] = 0;
                $_obfuscate_vzGArcjOKr8_7A??['allowAttachDown'] = 0;
                foreach ( $_obfuscate_e53ODz04JQ??->getStepInfoByIdArr( $_obfuscate_QwT4KwrB2w?? ) as $_obfuscate_5w?? => $_obfuscate_6A?? )
                {
                    if ( $_obfuscate_6A??['allowAttachView'] == 1 )
                    {
                        $_obfuscate_vzGArcjOKr8_7A??['allowAttachView'] = 1;
                    }
                    if ( $_obfuscate_6A??['allowAttachDown'] == 1 )
                    {
                        $_obfuscate_vzGArcjOKr8_7A??['allowAttachDown'] = 1;
                    }
                }
                $_obfuscate_bieFdpvEw_g? = $CNOA_DB->db_getone( "*", "z_wf_t_".$this->flowId, "WHERE uFlowId=".$this->uFlowId );
                $_obfuscate_WgE?['attach'] = $_obfuscate_bieFdpvEw_g?["T_"."{$_obfuscate_WgE?['id']}"];
                if ( $_obfuscate_WgE?['attach'] )
                {
                    $this->flowInfo['html'] = "";
                    ( );
                    $_obfuscate_2gg? = new fs( );
                    $_obfuscate_8CpDPPa = $_obfuscate_2gg?->getListInfo4wf( json_decode( $_obfuscate_WgE?['attach'], TRUE ), $_obfuscate_vzGArcjOKr8_7A??, TRUE, "deal" );
                    foreach ( $_obfuscate_8CpDPPa as $_obfuscate_Vwty => $_obfuscate_VgKtFeg? )
                    {
                        $this->flowInfo['html'] .= "<tr><td colspan=\"100\">".$_obfuscate_VgKtFeg?['filename'].$_obfuscate_VgKtFeg?['optStr']."</tr></td><br/>";
                    }
                    $this->flowInfo['formHtml'] = str_replace( $_obfuscate_WgE?['html'], $_obfuscate_WgE?['html']."<br/>".$this->flowInfo['html'], $this->flowInfo['formHtml'] );
                }
            }
            if ( $_obfuscate_WgE?['otype'] == "detailtable" )
            {
                $this->flowInfo['formHtml'] = str_replace( $_obfuscate_WgE?['html'], "<{[change]}>", $this->flowInfo['formHtml'] );
                $_obfuscate_lEGQqw?? = explode( "<{[change]}>", $this->flowInfo['formHtml'] );
                $_obfuscate_lEGQqw??[0] = substr( $_obfuscate_lEGQqw??[0], 0, strripos( $_obfuscate_lEGQqw??[0], "<tr" ) );
                $_obfuscate_lEGQqw??[1] = substr( $_obfuscate_lEGQqw??[1], stripos( $_obfuscate_lEGQqw??[1], "</tr>" ) + 5, strlen( $_obfuscate_lEGQqw??[1] ) );
                $this->flowInfo['formHtml'] = $_obfuscate_lEGQqw??[0].$_obfuscate_LQ8UKg??.$_obfuscate_lEGQqw??[1];
            }
            else
            {
                $this->flowInfo['formHtml'] = str_replace( $_obfuscate_WgE?['html'], $_obfuscate_LQ8UKg??, $this->flowInfo['formHtml'] );
            }
        }
        $this->flowInfo['formHtml'] = str_replace( "visibility:hidden", "display:none", $this->flowInfo['formHtml'] );
        $this->flowInfo['formHtml'] .= $this->extHtml;
        return $this->flowInfo['formHtml'];
    }

    public function formatFormItem( $element, $rule, $value = "" )
    {
        if ( $element['tagName'] != "input" )
        {
            return "";
        }
        $value = str_replace( " ", "&nbsp;", $value );
        if ( in_array( $element['otype'], array( "textfield", "textarea", "select", "radio", "checkbox", "calculate", "macro", "choice", "detailtable", "signature", "jumpKongjian", "abutment" ) ) )
        {
            eval( "\$return = \$this->_format_".$element['otype']."(\$element, \$rule, \$value);" );
            return $return;
        }
        return "";
    }

    public function __getOdata( $_obfuscate_p5ZWxr4? )
    {
        return json_decode( str_replace( "'", "\"", $_obfuscate_p5ZWxr4? ), TRUE );
    }

    private function _format_textfield( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
        $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
        $_obfuscate_T1KtUeK5x7Y? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = $_obfuscate_60GquoKMPw??['style'].";";
        $_obfuscate_0sRnHy4? .= $_obfuscate_T1KtUeK5x7Y?[0]." ";
        $_obfuscate_0sRnHy4? .= $_obfuscate_T1KtUeK5x7Y?[1]." ";
        $_obfuscate_0sRnHy4? .= "\"";
        if ( $_obfuscate_60GquoKMPw??['dvalue'] !== "hiddenTextfield" )
        {
            return $this->__spanTab( $_obfuscate_VgKtFeg?, $_obfuscate_0sRnHy4? );
        }
        return "";
    }

    private function _format_signature( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
        $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
        $_obfuscate_VgKtFeg? = json_decode( $_obfuscate_VgKtFeg?, TRUE );
        if ( !empty( $_obfuscate_VgKtFeg? ) )
        {
            if ( $_obfuscate_p5ZWxr4?['type'] == "graph" )
            {
                return $this->__imgTab( $_obfuscate_VgKtFeg?, $_obfuscate_p5ZWxr4? );
            }
            if ( $_obfuscate_p5ZWxr4?['type'] == "electron" )
            {
                return $this->__sealTab( $_obfuscate_60GquoKMPw??['name'], $_obfuscate_VgKtFeg?, $_obfuscate_60GquoKMPw??['id'] );
            }
        }
    }

    private function _format_textarea( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
        $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
        $_obfuscate_T1KtUeK5x7Y? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "width:".$_obfuscate_p5ZWxr4?['width']."px; ";
        $_obfuscate_0sRnHy4? .= "min-height:".$_obfuscate_p5ZWxr4?['height']."px; overflow: visible;_height:".$_obfuscate_p5ZWxr4?['height']."px;";
        $_obfuscate_0sRnHy4? .= $_obfuscate_T1KtUeK5x7Y?[0]." ";
        $_obfuscate_0sRnHy4? .= $_obfuscate_T1KtUeK5x7Y?[1]." ";
        $_obfuscate_0sRnHy4? .= ";text-align:left;";
        if ( $_obfuscate_p5ZWxr4?['richText'] == "on" )
        {
            return "<div ".$_obfuscate_0sRnHy4?.">".htmlspecialchars_decode( $_obfuscate_VgKtFeg? )."</div><br/>";
        }
        return $this->__spanTab( nl2br( $_obfuscate_VgKtFeg? ), $_obfuscate_0sRnHy4? );
    }

    private function _format_select( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
        $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
        $_obfuscate_T1KtUeK5x7Y? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "width:".$_obfuscate_p5ZWxr4?['width']."px; ";
        $_obfuscate_0sRnHy4? .= $_obfuscate_T1KtUeK5x7Y?[0]." ";
        $_obfuscate_0sRnHy4? .= $_obfuscate_T1KtUeK5x7Y?[1]." ";
        return $this->__spanTab( $_obfuscate_VgKtFeg?, $_obfuscate_0sRnHy4? );
    }

    private function _format_radio( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
        $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
        $_obfuscate_T1KtUeK5x7Y? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "width:".$_obfuscate_p5ZWxr4?['width']."px; ";
        $_obfuscate_0sRnHy4? .= $_obfuscate_T1KtUeK5x7Y?[0]." ";
        $_obfuscate_0sRnHy4? .= $_obfuscate_T1KtUeK5x7Y?[1]." ";
        foreach ( $_obfuscate_p5ZWxr4?['dataItems'] as $_obfuscate_Ilme )
        {
            if ( $_obfuscate_VgKtFeg? == $_obfuscate_Ilme['name'] )
            {
                $_obfuscate_ts4? = TRUE;
                $_obfuscate_LQ8UKg?? .= "<img onload=\"CNOA_wf_form_checker.showHide('".$_obfuscate_Ilme['display']."','".$_obfuscate_Ilme['undisplay']."', {radio:this})\" src=\"resources/images/cnoa/wf-radio.gif\" />".$_obfuscate_Ilme['name']."&nbsp;";
            }
            else
            {
                $_obfuscate_ts4? = FALSE;
                $_obfuscate_LQ8UKg?? .= "<img src=\"resources/images/cnoa/wf-unradio.gif\" />".$_obfuscate_Ilme['name']."&nbsp;";
            }
            if ( count( $_obfuscate_p5ZWxr4?['dataItems'] ) != $_obfuscate_5w?? + 1 && $_obfuscate_p5ZWxr4?['henshu'] == 2 )
            {
                $_obfuscate_LQ8UKg?? .= "<br />";
            }
            if ( !( $_obfuscate_p5ZWxr4?['dataType'] == "display" ) && empty( $_obfuscate_Ilme['display'] ) && empty( $_obfuscate_Ilme['undisplay'] ) || !$_obfuscate_ts4? )
            {
                $this->extHtml .= "<input type=\"hidden\" showhide=\"true\" display=\"".$_obfuscate_Ilme['display']."\" undisplay=\"".$_obfuscate_Ilme['undisplay']."\" fromtype=\"radio\">";
            }
        }
        return $this->__spanTab( $_obfuscate_LQ8UKg??, $_obfuscate_0sRnHy4? );
    }

    private function _format_checkbox( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
        $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
        $_obfuscate_T1KtUeK5x7Y? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = $_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1];
        $_obfuscate_InKox2iMsxk? = json_decode( $_obfuscate_VgKtFeg?, TRUE );
        if ( !is_array( $_obfuscate_InKox2iMsxk? ) )
        {
            $_obfuscate_InKox2iMsxk? = array( );
        }
        foreach ( $_obfuscate_p5ZWxr4?['dataItems'] as $_obfuscate_Ilme )
        {
            if ( !empty( $_obfuscate_InKox2iMsxk? ) || is_array( $_obfuscate_InKox2iMsxk? ) )
            {
                $_obfuscate_VPuPMczbSQ?? = in_array( $_obfuscate_Ilme['name'], $_obfuscate_InKox2iMsxk? ) != "" ? "check" : "uncheck";
                $_obfuscate_ts4? = in_array( $_obfuscate_Ilme['name'], $_obfuscate_InKox2iMsxk? ) ? "1" : "0";
                $_obfuscate_LQ8UKg?? .= "<img src=\"resources/images/cnoa/wf-".$_obfuscate_VPuPMczbSQ??.".gif\" />".$_obfuscate_Ilme['name']."&nbsp;";
            }
            else
            {
                $_obfuscate_ts4? = "0";
                $_obfuscate_LQ8UKg?? .= "<img src=\"resources/images/cnoa/wf-uncheck.gif\" />".$_obfuscate_Ilme['name']."&nbsp;";
            }
            if ( count( $_obfuscate_p5ZWxr4?['dataItems'] ) != $_obfuscate_5w?? + 1 && $_obfuscate_p5ZWxr4?['henshu'] == 2 )
            {
                $_obfuscate_LQ8UKg?? .= "<br />";
            }
            if ( !( $_obfuscate_p5ZWxr4?['display'] == "on" ) && empty( $_obfuscate_Ilme['display'] ) && empty( $_obfuscate_Ilme['undisplay'] ) )
            {
                $this->extHtml .= "<input type=\"hidden\" showhide=\"true\" display=\"".$_obfuscate_Ilme['display']."\" undisplay=\"".$_obfuscate_Ilme['undisplay']."\" fromtype=\"checkbox\" checkboxck=\"".$_obfuscate_ts4?."\" />";
            }
        }
        return $this->__spanTab( $_obfuscate_LQ8UKg??, $_obfuscate_0sRnHy4? );
    }

    private function _format_calculate( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
        $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
        $_obfuscate_T1KtUeK5x7Y? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "width:".$_obfuscate_p5ZWxr4?['width']."px;";
        $_obfuscate_0sRnHy4? .= $_obfuscate_T1KtUeK5x7Y?[0]." ";
        $_obfuscate_0sRnHy4? .= $_obfuscate_T1KtUeK5x7Y?[1]." ";
        return $this->__spanTab( $_obfuscate_VgKtFeg?, $_obfuscate_0sRnHy4? );
    }

    private function _format_detailtable( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg?? )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        if ( $this->startStep )
        {
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "id", "name", "flowId" ), $this->t_set_field_detail, "WHERE `fid` = '".$_obfuscate_60GquoKMPw??['id']."' " );
        }
        else
        {
            $_obfuscate_mPAjEGLn = $this->flowDetailFields;
        }
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_YIq2A8c? = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w?? => $_obfuscate_6A?? )
        {
            $_obfuscate_YIq2A8c?[$_obfuscate_6A??['name']] = $_obfuscate_6A??;
        }
        $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
        $_obfuscate_XbWsmcH__Jid = "";
        $_obfuscate_TlvKhtsoOQ?? = getpar( $_POST, "uFlowId", getpar( $_GET, "uFlowId", "" ) );
        $_obfuscate_GamAiZQGw?? = 1;
        $GLOBALS['_POST']['linenum'] = $_obfuscate_GamAiZQGw??;
        if ( empty( $_obfuscate_mPAjEGLn[0]['flowId'] ) )
        {
            $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", getpar( $_POST, "flowId", 0 ) );
        }
        else
        {
            $_obfuscate_F4AbnVRh = $_obfuscate_mPAjEGLn[0]['flowId'];
        }
        $_obfuscate_7Hp0w_lfFt4? = $CNOA_DB->db_select( "*", "z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_60GquoKMPw??['id'], "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ??."' ORDER BY `rowid` " );
        if ( !is_array( $_obfuscate_7Hp0w_lfFt4? ) )
        {
            $_obfuscate_7Hp0w_lfFt4? = array( );
        }
        $_obfuscate_LQ8UKg?? = "";
        foreach ( $_obfuscate_7Hp0w_lfFt4? as $_obfuscate_5w?? => $_obfuscate_6A?? )
        {
            $this->tdNum = 0;
            $_obfuscate_LQ8UKg?? .= "<tr class=\"detail-line\" style=\"height:".$_obfuscate_p5ZWxr4?['height']."px;\" detailTableId=".$_obfuscate_60GquoKMPw??['id'].">";
            $_obfuscate_LQ8UKg?? .= $this->__formatDetailtable( $_obfuscate_p5ZWxr4?, $_obfuscate_YIq2A8c?, $_obfuscate_60GquoKMPw??['id'], $_obfuscate_6A?? );
            $_obfuscate_LQ8UKg?? .= "</tr>";
        }
        return $_obfuscate_LQ8UKg??;
    }

    private function __formatDetailtable( $_obfuscate_p5ZWxr4?, $_obfuscate_YIq2A8c?, $_obfuscate_Ce9h, $_obfuscate_6RYLWQ?? )
    {
        if ( !is_array( $_obfuscate_p5ZWxr4? ) )
        {
            $_obfuscate_p5ZWxr4? = array( );
        }
        $_obfuscate_LQ8UKg?? = "";
        foreach ( $_obfuscate_p5ZWxr4?['items'] as $_obfuscate_6A?? )
        {
            $_obfuscate_Vwty = "D_".$_obfuscate_YIq2A8c?[$_obfuscate_6A??['name']]['id'];
            $_obfuscate_qTzeBsmr = $_obfuscate_p5ZWxr4?['tdattrs'][$this->tdNum];
            $_obfuscate_LQ8UKg?? .= "<td align='".$_obfuscate_6A??['align']."' ";
            $_obfuscate_LQ8UKg?? .= "valign='".$_obfuscate_qTzeBsmr['valign']."' ";
            $_obfuscate_LQ8UKg?? .= "height='".$_obfuscate_p5ZWxr4?['height']."' ";
            $_obfuscate_LQ8UKg?? .= "width='".$_obfuscate_qTzeBsmr['width']."' ";
            $_obfuscate_LQ8UKg?? .= "class='".str_replace( "selectTdClass", "", $_obfuscate_qTzeBsmr['class'] )."' ";
            $_obfuscate_LQ8UKg?? .= "style='".$_obfuscate_qTzeBsmr['style']."' ";
            $_obfuscate_LQ8UKg?? .= ">";
            if ( $this->childSeeParent == "childSeeParent" )
            {
                if ( $this->stepColFields[$_obfuscate_YIq2A8c?[$_obfuscate_6A??['name']]['id']]['hide'] != 1 && $this->stepColFields[$_obfuscate_YIq2A8c?[$_obfuscate_6A??['name']]['id']]['status'] == 1 )
                {
                    if ( $_obfuscate_6A??['odata']['dataType'] == "loginname" )
                    {
                        $_obfuscate_6RYLWQ??[$_obfuscate_Vwty] = app::loadapp( "main", "user" )->api_getUserNameByUids( intval( $_obfuscate_6RYLWQ??[$_obfuscate_Vwty] ) );
                    }
                    $_obfuscate_LQ8UKg?? .= $this->__formatCell( $_obfuscate_6A??, $_obfuscate_6RYLWQ??[$_obfuscate_Vwty] );
                }
            }
            else if ( $this->stepColFields[$_obfuscate_YIq2A8c?[$_obfuscate_6A??['name']]['id']]['hide'] != 1 )
            {
                if ( $_obfuscate_6A??['odata']['dataType'] == "loginname" )
                {
                    $_obfuscate_6RYLWQ??[$_obfuscate_Vwty] = app::loadapp( "main", "user" )->api_getUserNameByUids( intval( $_obfuscate_6RYLWQ??[$_obfuscate_Vwty] ) );
                }
                $_obfuscate_LQ8UKg?? .= $this->__formatCell( $_obfuscate_6A??, $_obfuscate_6RYLWQ??[$_obfuscate_Vwty] );
            }
            $_obfuscate_LQ8UKg?? .= "</td>";
            $this->tdNum++;
        }
        return $_obfuscate_LQ8UKg??;
    }

    private function __formatCell( $_obfuscate_6A??, $_obfuscate_VgKtFeg? )
    {
        $_obfuscate_LeS8hw?? = $_obfuscate_6A??['odata']['type'];
        $_obfuscate_p5ZWxr4? = $_obfuscate_6A??['odata'];
        switch ( $_obfuscate_LeS8hw?? )
        {
        case "textfield" :
            $_obfuscate_T1KtUeK5x7Y? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
            $_obfuscate_0sRnHy4? = "width:".$_obfuscate_p5ZWxr4?['width']."px; ";
            $_obfuscate_0sRnHy4? .= $_obfuscate_T1KtUeK5x7Y?[0]." ";
            $_obfuscate_LQ8UKg?? = $this->__spanTab( $_obfuscate_VgKtFeg?, $_obfuscate_0sRnHy4? );
            return $_obfuscate_LQ8UKg??;
        case "textarea" :
            $_obfuscate_T1KtUeK5x7Y? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
            $_obfuscate_0sRnHy4? = "width:".$_obfuscate_p5ZWxr4?['width']."px; ";
            $_obfuscate_0sRnHy4? .= "height:".$_obfuscate_p5ZWxr4?['height']."px; ";
            $_obfuscate_0sRnHy4? .= $_obfuscate_T1KtUeK5x7Y?[0]." ";
            $_obfuscate_VgKtFeg? = nl2br( $_obfuscate_VgKtFeg? );
            $_obfuscate_LQ8UKg?? = $this->__spanTab( $_obfuscate_VgKtFeg?, $_obfuscate_0sRnHy4? );
            return $_obfuscate_LQ8UKg??;
        case "select" :
            $_obfuscate_T1KtUeK5x7Y? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
            $_obfuscate_0sRnHy4? = "width:".$_obfuscate_p5ZWxr4?['width']."px; ";
            $_obfuscate_0sRnHy4? .= $_obfuscate_T1KtUeK5x7Y?[0]." ";
            $_obfuscate_LQ8UKg?? = $this->__spanTab( $_obfuscate_VgKtFeg?, $_obfuscate_0sRnHy4? );
            return $_obfuscate_LQ8UKg??;
        case "calculate" :
            $_obfuscate_T1KtUeK5x7Y? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
            $_obfuscate_0sRnHy4? = "width:".$_obfuscate_p5ZWxr4?['width']."px; ";
            $_obfuscate_0sRnHy4? .= $_obfuscate_T1KtUeK5x7Y?[0]." ";
            $_obfuscate_LQ8UKg?? = $this->__spanTab( $_obfuscate_VgKtFeg?, $_obfuscate_0sRnHy4? );
            return $_obfuscate_LQ8UKg??;
        case "radio" :
            $_obfuscate_T1KtUeK5x7Y? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
            $_obfuscate_0sRnHy4? = "width:".$_obfuscate_p5ZWxr4?['width']."px; ";
            $_obfuscate_0sRnHy4? .= $_obfuscate_T1KtUeK5x7Y?[0]." ";
            if ( !is_array( $_obfuscate_p5ZWxr4?['item'] ) )
            {
                $_obfuscate_p5ZWxr4?['item'] = array( );
            }
            foreach ( $_obfuscate_p5ZWxr4?['item'] as $_obfuscate_5w?? => $_obfuscate_Ilme )
            {
                if ( $_obfuscate_VgKtFeg? == $_obfuscate_Ilme['pname'] )
                {
                    $_obfuscate_LQ8UKg?? .= "<image src=\"resources/images/cnoa/wf-radio.gif\" />".$_obfuscate_Ilme['pname'];
                }
                else
                {
                    $_obfuscate_LQ8UKg?? .= "<image src=\"resources/images/cnoa/wf-unradio.gif\" />".$_obfuscate_Ilme['pname'];
                }
                $_obfuscate_LQ8UKg?? .= count( $_obfuscate_p5ZWxr4?['item'] ) == $_obfuscate_5w?? + 1 ? "" : "&nbsp;";
            }
            $_obfuscate_LQ8UKg?? = $this->__spanTab( $_obfuscate_LQ8UKg??, $_obfuscate_0sRnHy4? );
            return $_obfuscate_LQ8UKg??;
        case "checkbox" :
            $_obfuscate_T1KtUeK5x7Y? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
            $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_VgKtFeg?, ENT_NOQUOTES );
            $_obfuscate_VgKtFeg? = json_decode( $_obfuscate_VgKtFeg?, TRUE );
            $_obfuscate_0sRnHy4? = "width:".$_obfuscate_p5ZWxr4?['width']."px; ";
            $_obfuscate_0sRnHy4? .= $_obfuscate_T1KtUeK5x7Y?[0]." ";
            if ( !is_array( $_obfuscate_p5ZWxr4?['item'] ) )
            {
                $_obfuscate_p5ZWxr4?['item'] = array( );
            }
            foreach ( $_obfuscate_p5ZWxr4?['item'] as $_obfuscate_5w?? => $_obfuscate_Ilme )
            {
                if ( !empty( $_obfuscate_VgKtFeg? ) || is_array( $_obfuscate_VgKtFeg? ) )
                {
                    if ( in_array( $_obfuscate_Ilme['pname'], $_obfuscate_VgKtFeg? ) )
                    {
                        $_obfuscate_VPuPMczbSQ?? = "check";
                    }
                    else
                    {
                        $_obfuscate_VPuPMczbSQ?? = "uncheck";
                    }
                }
                else
                {
                    $_obfuscate_VPuPMczbSQ?? = $_obfuscate_Ilme['pname'] ? "check" : "uncheck";
                }
                $_obfuscate_LQ8UKg?? .= "<image src=\"resources/images/cnoa/wf-".$_obfuscate_VPuPMczbSQ??.".gif\" />".$_obfuscate_Ilme['pname'];
                $_obfuscate_LQ8UKg?? .= count( $_obfuscate_p5ZWxr4?['item'] ) == $_obfuscate_5w?? + 1 ? "" : "&nbsp;";
            }
            $_obfuscate_LQ8UKg?? = $this->__spanTab( $_obfuscate_LQ8UKg??, $_obfuscate_0sRnHy4? );
            return $_obfuscate_LQ8UKg??;
        case "macro" :
            $_obfuscate_T1KtUeK5x7Y? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
            $_obfuscate_0sRnHy4? = "width:".$_obfuscate_p5ZWxr4?['width']."px; ";
            $_obfuscate_0sRnHy4? .= $_obfuscate_T1KtUeK5x7Y?[0]." ";
            $_obfuscate_LQ8UKg?? = $this->__spanTab( $_obfuscate_VgKtFeg?, $_obfuscate_0sRnHy4? );
            return $_obfuscate_LQ8UKg??;
        case "choice" :
            $_obfuscate_T1KtUeK5x7Y? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
            $_obfuscate_0sRnHy4? = "width:".$_obfuscate_p5ZWxr4?['width']."px; ";
            $_obfuscate_0sRnHy4? .= $_obfuscate_T1KtUeK5x7Y?[0]." ";
            switch ( $_obfuscate_p5ZWxr4?['dataType'] )
            {
            case "user_sel" :
                $_obfuscate_VgKtFeg? = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_VgKtFeg? );
                break;
            case "users_sel" :
                $_obfuscate_GCfDSanL49WUEA?? = app::loadapp( "main", "user" )->api_getUserNamesByUids( explode( ",", $_obfuscate_VgKtFeg? ) );
                $_obfuscate_VgKtFeg? = "";
                foreach ( $_obfuscate_GCfDSanL49WUEA?? as $_obfuscate_6A?? )
                {
                    $_obfuscate_VgKtFeg? .= $_obfuscate_6A??['truename'].",";
                }
                break;
            case "station_sel" :
                $_obfuscate_VgKtFeg? = app::loadapp( "main", "station" )->api_getNameById( $_obfuscate_VgKtFeg? );
                break;
            case "stations_sel" :
                $_obfuscate_VcY7imjf = app::loadapp( "main", "station" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg? ) );
                $_obfuscate_VgKtFeg? = implode( ",", $_obfuscate_VcY7imjf );
                break;
            case "dept_sel" :
                $_obfuscate_VgKtFeg? = app::loadapp( "main", "struct" )->api_getNameById( $_obfuscate_VgKtFeg? );
                break;
            case "depts_sel" :
                $_obfuscate_VcY7imjf = app::loadapp( "main", "struct" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg? ) );
                $_obfuscate_VgKtFeg? = implode( ",", $_obfuscate_VcY7imjf );
                break;
            case "job_sel" :
                $_obfuscate_VgKtFeg? = app::loadapp( "main", "job" )->api_getNameById( $_obfuscate_VgKtFeg? );
                break;
            case "jobs_sel" :
                $_obfuscate_VcY7imjf = app::loadapp( "main", "job" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg? ) );
                $_obfuscate_VgKtFeg? = implode( ",", $_obfuscate_VcY7imjf );
            }
            $_obfuscate_LQ8UKg?? = $this->__spanTab( $_obfuscate_VgKtFeg?, $_obfuscate_0sRnHy4? );
        }
        return $_obfuscate_LQ8UKg??;
    }

    private function __spanTab( $_obfuscate_VgKtFeg?, $_obfuscate_0sRnHy4? )
    {
        if ( $_obfuscate_VgKtFeg? === "" )
        {
            $_obfuscate_VgKtFeg? = "&nbsp;";
        }
        $_obfuscate_lEGQqw?? = "<span class=\"wf_readonly\" ";
        $_obfuscate_lEGQqw?? .= "style=\"display:inline-block;".$_obfuscate_0sRnHy4?."\"";
        $_obfuscate_lEGQqw?? .= ">".$_obfuscate_VgKtFeg?."<";
        $_obfuscate_lEGQqw?? .= "/span>";
        return $_obfuscate_lEGQqw??;
    }

    private function __imgTab( $_obfuscate_VgKtFeg?, $_obfuscate_p5ZWxr4? )
    {
        if ( isset( $_obfuscate_VgKtFeg?['url'] ) )
        {
            $_obfuscate_0sRnHy4? = "style=\"position:absolute; float:left; ";
            $_obfuscate_0sRnHy4? .= "left:".$_obfuscate_VgKtFeg?['left'];
            $_obfuscate_0sRnHy4? .= "px; top:".$_obfuscate_VgKtFeg?['top'];
            $_obfuscate_0sRnHy4? .= "px;\"";
            $_obfuscate_lEGQqw?? = "<span style=\"position:relative;display:inline-block;width:".$_obfuscate_p5ZWxr4?['width']."px;\"><img src=\"".$_obfuscate_VgKtFeg?['url'];
            $_obfuscate_lEGQqw?? .= "\" ".$_obfuscate_0sRnHy4?;
            $_obfuscate_lEGQqw?? .= "/></span>";
        }
        return $_obfuscate_lEGQqw??;
    }

    private function __sealTab( $_obfuscate_ugYVum8?, $_obfuscate_VgKtFeg?, $_obfuscate_0W8? )
    {
        $_obfuscate_lEGQqw?? = "<input type=\"hidden\" oname=\"".$_obfuscate_ugYVum8?."\" sealstoredata=\"true\" value=\"";
        if ( isset( $_obfuscate_VgKtFeg?['seal'] ) )
        {
            $_obfuscate_QAvrkQ?? = $_obfuscate_lEGQqw??.$_obfuscate_VgKtFeg?['seal']."\" id=\"seal_wf_signature_".$_obfuscate_0W8?."\" />";
        }
        if ( isset( $_obfuscate_VgKtFeg?['handwrite'] ) )
        {
            $_obfuscate_P12L8g?? = $_obfuscate_lEGQqw??.$_obfuscate_VgKtFeg?['handwrite']."\" id=\"hand_wf_signature_".$_obfuscate_0W8?."\" />";
        }
        $_obfuscate_lEGQqw?? = $_obfuscate_QAvrkQ??.$_obfuscate_P12L8g??;
        return $_obfuscate_lEGQqw??;
    }

    private function _format_macro( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
        global $CNOA_SESSION;
        $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
        $_obfuscate_0sRnHy4? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = $_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1];
        if ( $_obfuscate_60GquoKMPw??['otype'] == "macro" && ( $_obfuscate_p5ZWxr4?['dataType'] == "loginname" || $_obfuscate_p5ZWxr4?['dataType'] == "creatername" ) && !empty( $_obfuscate_VgKtFeg? ) )
        {
            if ( !is_numeric( $_obfuscate_VgKtFeg? ) )
            {
                $_obfuscate_VgKtFeg? = $_obfuscate_VgKtFeg?;
            }
            else
            {
                $_obfuscate_VgKtFeg? = app::loadapp( "main", "user" )->api_getUserNameByUids( intval( $_obfuscate_VgKtFeg? ) );
            }
        }
        if ( $_obfuscate_p5ZWxr4?['dataType'] == "huiqian" )
        {
            global $CNOA_DB;
            $_obfuscate_VgKtFeg? = "";
            if ( ( integer )$this->uFlowId != 0 )
            {
                $_obfuscate_O1qQ = $_obfuscate_p5ZWxr4?['huiqianTpl'];
                ( $this->uFlowId );
                $_obfuscate_bIsJe6A? = new wfCache( );
                $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "stepId", "truename", "message", "writetime" ), $this->t_use_step_huiqian, "WHERE uFlowId=".$this->uFlowId." ORDER BY posttime" );
                if ( !is_array( $_obfuscate_mPAjEGLn ) )
                {
                    $_obfuscate_mPAjEGLn = array( );
                }
                foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A?? )
                {
                    $_obfuscate_A260kBOEy4wB = empty( $_obfuscate_6A??['writetime'] ) ? "---" : date( "Y-m-d H:i", $_obfuscate_6A??['writetime'] );
                    $_obfuscate_5NhzjnJq_f8? = $_obfuscate_bIsJe6A?->getStepByStepId( $_obfuscate_6A??['stepId'] );
                    $_obfuscate_77tGbWOiZg?? = array(
                        $_obfuscate_5NhzjnJq_f8?['stepName'],
                        $_obfuscate_6A??['truename'],
                        $_obfuscate_A260kBOEy4wB,
                        $_obfuscate_6A??['message']
                    );
                    $_obfuscate_VgKtFeg? .= "<span style=\"line-height:25px;\">".str_replace( array( "{S}", "{U}", "{T}", "{I}" ), $_obfuscate_77tGbWOiZg??, $_obfuscate_O1qQ )."</span><br />";
                }
            }
        }
        return $this->__spanTab( $_obfuscate_VgKtFeg?, $_obfuscate_0sRnHy4? );
    }

    private function _format_jumpKongjian( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
        if ( !empty( $_obfuscate_VgKtFeg? ) )
        {
            $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_VgKtFeg? );
        }
        else
        {
            $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_60GquoKMPw??['dvalue'] );
        }
        $_obfuscate_0sRnHy4? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "style=\"".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1].";display:inline-block;\" ";
        $_obfuscate_LQ8UKg?? = "<span ".$_obfuscate_0sRnHy4?.">";
        if ( $_obfuscate_60GquoKMPw??['jump']['arrow'] == "left" )
        {
            $_obfuscate_wzlwEupWLkw? = $CNOA_DB->db_getfield( "puFlowId", "wf_u_step_child_flow", "WHERE `uFlowId`=".$this->uFlowId." AND `flowId`={$this->flowId}" );
            if ( empty( $_obfuscate_wzlwEupWLkw? ) )
            {
                return $_obfuscate_LQ8UKg??."</span>";
            }
            $_obfuscate_3y0Y = "SELECT `u`.`status`,`u`.`flowId`,`u`.`tplSort`,`u`.`uid`,`s`.`flowType` FROM  ".tname( "wf_u_flow" )." AS u LEFT JOIN  ".tname( "wf_s_flow" )." AS s ON u.flowId = s.flowId WHERE u.uFlowId =".$_obfuscate_wzlwEupWLkw?;
            $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
            $_obfuscate_hTew0boWJESy = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k );
            $_obfuscate_djysM0wfDn = $CNOA_DB->db_getfield( "uStepId", $this->t_use_step, "WHERE `uFlowId`=".$_obfuscate_wzlwEupWLkw?." ORDER BY `etime`" );
            $_obfuscate_0CYzhQ??['status'] = $_obfuscate_hTew0boWJESy['status'];
            $_obfuscate_0CYzhQ??['uFlowId'] = $_obfuscate_wzlwEupWLkw?;
            $_obfuscate_0CYzhQ??['flowId'] = $_obfuscate_hTew0boWJESy['flowId'];
            $_obfuscate_0CYzhQ??['stepId'] = $_obfuscate_djysM0wfDn;
            $_obfuscate_0CYzhQ??['flowType'] = $_obfuscate_hTew0boWJESy['flowType'];
            $_obfuscate_0CYzhQ??['tplSort'] = $_obfuscate_hTew0boWJESy['tplSort'];
            if ( $_obfuscate_hTew0boWJESy['status'] != 1 )
            {
                $_obfuscate_HWVCjBk? = $_obfuscate_7Ri3 == $_obfuscate_hTew0boWJESy['uid'] ? 1 : 0;
                $_obfuscate_0CYzhQ??['owner'] = $_obfuscate_HWVCjBk?;
            }
        }
        else if ( $_obfuscate_60GquoKMPw??['jump']['arrow'] == "right" )
        {
            $_obfuscate_OtEajCKVE6U? = $CNOA_DB->db_getfield( "uFlowId", "wf_u_step_child_flow", "WHERE `puFlowId`=".$this->uFlowId." AND `flowId`={$_obfuscate_60GquoKMPw??['jump']['bangdingFlow']} AND `stepId`={$_obfuscate_60GquoKMPw??['jump']['stepId']}" );
            if ( empty( $_obfuscate_OtEajCKVE6U? ) )
            {
                return $_obfuscate_LQ8UKg??."</span>";
            }
            $_obfuscate_3y0Y = "SELECT `u`.`status`,`u`.`flowId`,`u`.`tplSort`,`u`.`uid`,`s`.`flowType` FROM  ".tname( "wf_u_flow" )." AS u LEFT JOIN  ".tname( "wf_s_flow" )." AS s ON u.flowId = s.flowId WHERE u.uFlowId =".$_obfuscate_OtEajCKVE6U?;
            $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
            $_obfuscate_hTew0boWJESy = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k );
            $_obfuscate_djysM0wfDn = $CNOA_DB->db_getfield( "uStepId", $this->t_use_step, "WHERE `uFlowId`=".$_obfuscate_OtEajCKVE6U?." ORDER BY `etime`" );
            $_obfuscate_0CYzhQ??['status'] = $_obfuscate_hTew0boWJESy['status'];
            $_obfuscate_0CYzhQ??['uFlowId'] = $_obfuscate_OtEajCKVE6U?;
            $_obfuscate_0CYzhQ??['flowId'] = $_obfuscate_hTew0boWJESy['flowId'];
            $_obfuscate_0CYzhQ??['stepId'] = $_obfuscate_djysM0wfDn;
            $_obfuscate_0CYzhQ??['flowType'] = $_obfuscate_hTew0boWJESy['flowType'];
            $_obfuscate_0CYzhQ??['tplSort'] = $_obfuscate_hTew0boWJESy['tplSort'];
            if ( $_obfuscate_hTew0boWJESy['status'] != 1 )
            {
                $_obfuscate_HWVCjBk? = $_obfuscate_7Ri3 == $_obfuscate_hTew0boWJESy['uid'] ? 1 : 0;
                $_obfuscate_0CYzhQ??['owner'] = $_obfuscate_HWVCjBk?;
            }
            $_obfuscate_VgKtFeg? = $CNOA_DB->db_getfield( $_obfuscate_60GquoKMPw??['jump']['childKongjian'], "z_wf_t_".$_obfuscate_60GquoKMPw??['jump']['bangdingFlow'], "WHERE `uFlowId`=".$_obfuscate_OtEajCKVE6U? );
        }
        unset( $_obfuscate_3y0Y );
        unset( $_obfuscate_xs33Yt_k );
        unset( $_obfuscate_hTew0boWJESy );
        unset( $_obfuscate_djysM0wfDn );
        unset( $_obfuscate_HWVCjBk? );
        $_obfuscate_LQ8UKg?? .= "<a href=\"javascript:void(0);\" mark=\"flowview\" data='".json_encode( $_obfuscate_0CYzhQ?? )."' ext:qtip=\"??гд???иибе|????????1\" >".$_obfuscate_VgKtFeg?."</span></a> ";
        return $_obfuscate_LQ8UKg??;
    }

    private function _format_choice( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
        global $CNOA_SESSION;
        $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
        $_obfuscate_0sRnHy4? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = $_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1];
        switch ( $_obfuscate_p5ZWxr4?['dataType'] )
        {
        case "user_sel" :
            $_obfuscate_VgKtFeg? = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_VgKtFeg? );
            break;
        case "users_sel" :
            if ( empty( $_obfuscate_VgKtFeg? ) )
            {
                break;
            }
            $_obfuscate_GCfDSanL49WUEA?? = app::loadapp( "main", "user" )->api_getUserTruenameByUid( explode( ",", $_obfuscate_VgKtFeg? ), TRUE );
            if ( !is_array( $_obfuscate_GCfDSanL49WUEA?? ) )
            {
                break;
            }
            $_obfuscate_VgKtFeg? = implode( ",", $_obfuscate_GCfDSanL49WUEA?? );
            break;
        case "station_sel" :
            $_obfuscate_VgKtFeg? = app::loadapp( "main", "station" )->api_getNameById( $_obfuscate_VgKtFeg? );
            break;
        case "stations_sel" :
            if ( $_obfuscate_VgKtFeg? == "" )
            {
                $_obfuscate_VgKtFeg? = 0;
            }
            $_obfuscate_VcY7imjf = app::loadapp( "main", "station" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg? ) );
            if ( !is_array( $_obfuscate_VcY7imjf ) )
            {
                $_obfuscate_VcY7imjf = array( );
            }
            $_obfuscate_VgKtFeg? = implode( ",", $_obfuscate_VcY7imjf );
            break;
        case "dept_sel" :
            $_obfuscate_VgKtFeg? = app::loadapp( "main", "struct" )->api_getNameById( $_obfuscate_VgKtFeg? );
            break;
        case "depts_sel" :
            if ( $_obfuscate_VgKtFeg? == "" )
            {
                $_obfuscate_VgKtFeg? = 0;
            }
            $_obfuscate_VcY7imjf = app::loadapp( "main", "struct" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg? ) );
            if ( !is_array( $_obfuscate_VcY7imjf ) )
            {
                $_obfuscate_VcY7imjf = array( );
            }
            $_obfuscate_VgKtFeg? = implode( ",", $_obfuscate_VcY7imjf );
            break;
        case "job_sel" :
            $_obfuscate_VgKtFeg? = app::loadapp( "main", "job" )->api_getNameById( $_obfuscate_VgKtFeg? );
            break;
        case "jobs_sel" :
            if ( $_obfuscate_VgKtFeg? == "" )
            {
                $_obfuscate_VgKtFeg? = 0;
            }
            $_obfuscate_VcY7imjf = app::loadapp( "main", "job" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg? ) );
            if ( !is_array( $_obfuscate_VcY7imjf ) )
            {
                $_obfuscate_VcY7imjf = array( );
            }
            $_obfuscate_VgKtFeg? = implode( ",", $_obfuscate_VcY7imjf );
            break;
        case "date_sel" :
        case "time_sel" :
        case "photo_upload" :
            $_obfuscate_VgKtFeg? = "<img src=\"".$_obfuscate_VgKtFeg?."\" style=\"width:".$_obfuscate_p5ZWxr4?['width']."px;height:".$_obfuscate_p5ZWxr4?['height']."px;\" />";
            break;
        case "kehuEgressRecord" :
            $_obfuscate_VgKtFeg? = str_replace( "&lt;", "<", $_obfuscate_VgKtFeg? );
            $_obfuscate_VgKtFeg? = str_replace( "&gt;", ">", $_obfuscate_VgKtFeg? );
            $_obfuscate_VgKtFeg? = str_replace( "&amp;", "&", $_obfuscate_VgKtFeg? );
            $_obfuscate_VgKtFeg? = str_replace( "&quot;", "'", $_obfuscate_VgKtFeg? );
            $_obfuscate_VgKtFeg? = str_replace( "&nbsp;", " ", $_obfuscate_VgKtFeg? );
            $_obfuscate_VgKtFeg? = "<div style='width:".$_obfuscate_p5ZWxr4?['width']."px;height:{$_obfuscate_p5ZWxr4?['height']}px;'>{$_obfuscate_VgKtFeg?}</div>";
        }
        return $this->__spanTab( $_obfuscate_VgKtFeg?, $_obfuscate_0sRnHy4? );
    }

    private function __getStylesList( )
    {
        global $CNOA_DB;
        $_obfuscate_BYpBZ3ox7rbL = $CNOA_DB->db_select( "*", $this->t_set_form_style, "WHERE 1" );
        if ( !is_array( $_obfuscate_BYpBZ3ox7rbL ) )
        {
            $_obfuscate_BYpBZ3ox7rbL = array( );
        }
        $_obfuscate_fMfssw?? = array( );
        foreach ( $_obfuscate_BYpBZ3ox7rbL as $_obfuscate_6A?? )
        {
            $_obfuscate_QUdEYU09 = "";
            $_obfuscate_QUdEYU09 .= "font-family:".$this->f_font[$_obfuscate_6A??['font']].";";
            $_obfuscate_QUdEYU09 .= "font-size:".$_obfuscate_6A??['size']."px;";
            $_obfuscate_QUdEYU09 .= "color:#".$_obfuscate_6A??['color'].";";
            if ( $_obfuscate_6A??['border'] == 0 )
            {
                $_obfuscate_vILV9AXm = "border-bottom:1px solid #000;";
            }
            else if ( $_obfuscate_6A??['border'] == 1 )
            {
                $_obfuscate_vILV9AXm = "border:1px solid #000;";
            }
            else if ( $_obfuscate_6A??['border'] == 2 )
            {
                $_obfuscate_vILV9AXm = "";
            }
            $_obfuscate_QUdEYU09 .= $_obfuscate_6A??['italic'] == 0 ? "" : "font-style:italic;";
            $_obfuscate_QUdEYU09 .= $_obfuscate_6A??['bold'] == 0 ? "" : "font-weight:bold;";
            $_obfuscate_fMfssw??[$_obfuscate_6A??['sid']] = array(
                $_obfuscate_QUdEYU09,
                $_obfuscate_vILV9AXm
            );
        }
        return $_obfuscate_fMfssw??;
    }

    private function formatSqlselector1( &$_obfuscate_60GquoKMPw??, &$_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg?, &$_obfuscate_6RYLWQ??, &$_obfuscate_p5ZWxr4? )
    {
        $_obfuscate_0sRnHy4? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "style=\"".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1]."\" ";
        if ( !empty( $_obfuscate_VgKtFeg? ) )
        {
            $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_VgKtFeg? );
        }
        else
        {
            $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_60GquoKMPw??['dvalue'] );
        }
        foreach ( $_obfuscate_6RYLWQ??[1] as $_obfuscate_Ilme )
        {
            if ( !( $_obfuscate_Ilme[$_obfuscate_6RYLWQ??[0]['value']] == $_obfuscate_VgKtFeg? ) )
            {
                continue;
            }
            $_obfuscate_VgKtFeg? = $_obfuscate_Ilme[$_obfuscate_6RYLWQ??[0]['display']];
            break;
        }
        return $this->__spanTab( $_obfuscate_VgKtFeg?, $_obfuscate_0sRnHy4? );
    }

    private function formatSqlselector2( &$_obfuscate_60GquoKMPw??, &$_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg?, &$_obfuscate_6RYLWQ??, &$_obfuscate_p5ZWxr4? )
    {
        $_obfuscate_0sRnHy4? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "style=\"".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1]."\" ";
        $_obfuscate_j574IU5Dhw?? = $_obfuscate_6RYLWQ??[1][$_obfuscate_6RYLWQ??[0]['display']];
        $_obfuscate_VgKtFeg? = $_obfuscate_6RYLWQ??[1][$_obfuscate_6RYLWQ??[0]['value']];
        return $this->__spanTab( $_obfuscate_VgKtFeg?, $_obfuscate_0sRnHy4? );
    }

    private function _format_abutment( &$element, &$rule, $value )
    {
        $odata = $this->__getOdata( $element['odata'] );
        $mapPath = CNOA_PATH_FILE.( "/common/wf/sqlselector/".$element['id'].".php" );
        if ( file_exists( $mapPath ) )
        {
            $data = include_once( $mapPath );
        }
        else
        {
            $data = array( );
        }
        if ( $data[0]['selectorType'] == 1 )
        {
            $item = $this->formatSqlselector1( $element, $rule, $value, $data, $odata );
            return $item;
        }
        if ( $data[0]['selectorType'] == 2 )
        {
            $item = $this->formatSqlselector2( $element, $rule, $value, $data, $odata );
        }
        return $item;
    }

}

?>
