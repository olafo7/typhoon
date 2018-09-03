<?php

class wfFieldFormaterForDeal extends wfCommon
{

    private $t_style = "wf_s_form_style";
    public $styleList = NULL;
    private $flowId = 0;
    public $uFlowId = 0;
    private $stepId = 0;
    private $againId = 0;
    private $flowInfo = array( );
    public $flowFields = array( );
    public $flowDetailFields = array( );
    private $stepFields = array( );
    private $stepColFields = array( );
    private $fieldRule = array( );
    public $extHtml = "";
    private $startStep = NULL;
    private $draft = 0;
    public $flowNumber = "";
    public $flowName = "";
    public $creatorUid = 0;
    public $childDetailDB = array( );
    private $fromParentValue = array( );
    private $draftData = array( );

    public function __construct( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt, $_obfuscate_FygnrXwW3bt6 = TRUE, $_obfuscate_TlvKhtsoOQ?? = 0, $_obfuscate_8ftcdUn7PA?? = 0 )
    {
        global $CNOA_DB;
        $this->flowId = $_obfuscate_F4AbnVRh;
        $this->uFlowId = $_obfuscate_TlvKhtsoOQ??;
        $this->againId = $_obfuscate_8ftcdUn7PA??;
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
            $this->stepFields = $GLOBALS['UWFCACHE']->getStepFields( $this->stepId, self::FIELD_RULE_NORMAL );
            $this->stepColFields = $GLOBALS['UWFCACHE']->getStepFields( $this->stepId, self::FIELD_RULE_DETAIL );
        }
        $_obfuscate_l5xoT48YaQ?? = ( integer )getpar( $_GET, "childId" );
        if ( $_obfuscate_l5xoT48YaQ?? !== 0 )
        {
            $_obfuscate_jOcDpChC9w?? = $CNOA_DB->db_getone( "*", $this->t_use_step_child_flow, "WHERE `id` = ".$_obfuscate_l5xoT48YaQ?? );
        }
        else if ( !empty( $_obfuscate_TlvKhtsoOQ?? ) )
        {
            $_obfuscate_jOcDpChC9w?? = $CNOA_DB->db_getone( "*", $this->t_use_step_child_flow, "WHERE `uFlowId` = ".$_obfuscate_TlvKhtsoOQ?? );
        }
        else
        {
            $_obfuscate_s8yswCWZEIY? = getpar( $_GET, "otherApp", 0 );
            if ( !empty( $_obfuscate_s8yswCWZEIY? ) )
            {
                $this->_otherApp( );
            }
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
        foreach ( $this->flowFields as $_obfuscate_Vwty => $_obfuscate_QHYNVoqEtzC )
        {
            foreach ( $_obfuscate_8LH7ik2lzjhs7g?? as $_obfuscate_YSzwHNrbZM1 )
            {
                if ( !( $_obfuscate_YSzwHNrbZM1['stepId'] !== $_obfuscate_jOcDpChC9w??['stepId'] ) )
                {
                    if ( $_obfuscate_YSzwHNrbZM1['arrow'] == "right" )
                    {
                        break;
                    }
                }
                else
                {
                    continue;
                }
                $_obfuscate_qkfuZ86mHVAwQGS5 = strtr( $_obfuscate_YSzwHNrbZM1['childKongjian'], array( "T_" => "" ) );
                if ( $_obfuscate_QHYNVoqEtzC['id'] == $_obfuscate_qkfuZ86mHVAwQGS5 )
                {
                    $this->flowFields[$_obfuscate_Vwty]['childKong'] = 1;
                    $_obfuscate_VgKtFeg? = $CNOA_DB->db_getfield( $_obfuscate_YSzwHNrbZM1['parentKongjian'], "z_wf_t_".$_obfuscate_YSzwHNrbZM1['flowId'], "WHERE `uFlowId` = ".$_obfuscate_jOcDpChC9w??['puFlowId'] );
                    if ( $_obfuscate_YSzwHNrbZM1['parentType'] == "loginname" )
                    {
                        $_obfuscate_VgKtFeg? = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_VgKtFeg? );
                    }
                    else if ( $_obfuscate_YSzwHNrbZM1['parentType'] == "user_sel" )
                    {
                        $_obfuscate_VgKtFeg? = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_VgKtFeg? );
                    }
                    else if ( $_obfuscate_YSzwHNrbZM1['parentType'] == "users_sel" )
                    {
                        if ( !empty( $_obfuscate_VgKtFeg? ) )
                        {
                            $_obfuscate_xs33Yt_k = app::loadapp( "main", "user" )->api_getUserTruenameByUid( explode( ",", $_obfuscate_VgKtFeg? ) );
                            $_obfuscate_VgKtFeg? = implode( ",", $_obfuscate_xs33Yt_k );
                        }
                    }
                    else if ( $_obfuscate_YSzwHNrbZM1['parentType'] == "dept_sel" )
                    {
                        if ( !empty( $_obfuscate_VgKtFeg? ) )
                        {
                            $_obfuscate_VgKtFeg? = app::loadapp( "main", "struct" )->api_getNameById( $_obfuscate_VgKtFeg? );
                        }
                    }
                    else if ( $_obfuscate_YSzwHNrbZM1['parentType'] == "depts_sel" )
                    {
                        if ( !empty( $_obfuscate_VgKtFeg? ) )
                        {
                            $_obfuscate_VgKtFeg? = app::loadapp( "main", "struct" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg? ) );
                            $_obfuscate_VgKtFeg? = implode( ",", $_obfuscate_VgKtFeg? );
                        }
                    }
                    else if ( $_obfuscate_YSzwHNrbZM1['parentType'] == "station_sel" )
                    {
                        if ( !empty( $_obfuscate_VgKtFeg? ) )
                        {
                            $_obfuscate_VgKtFeg? = app::loadapp( "main", "station" )->api_getNameById( $_obfuscate_VgKtFeg? );
                        }
                    }
                    else if ( $_obfuscate_YSzwHNrbZM1['parentType'] == "stations_sel" )
                    {
                        if ( !empty( $_obfuscate_VgKtFeg? ) )
                        {
                            $_obfuscate_VgKtFeg? = app::loadapp( "main", "station" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg? ) );
                            $_obfuscate_VgKtFeg? = implode( ",", $_obfuscate_VgKtFeg? );
                        }
                    }
                    else if ( $_obfuscate_YSzwHNrbZM1['parentType'] == "job_sel" )
                    {
                        if ( !empty( $_obfuscate_VgKtFeg? ) )
                        {
                            $_obfuscate_VgKtFeg? = app::loadapp( "main", "job" )->api_getNameById( $_obfuscate_VgKtFeg? );
                        }
                    }
                    else if ( $_obfuscate_YSzwHNrbZM1['parentType'] == "jobs_sel" && !empty( $_obfuscate_VgKtFeg? ) )
                    {
                        $_obfuscate_VgKtFeg? = app::loadapp( "main", "job" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg? ) );
                        $_obfuscate_VgKtFeg? = implode( ",", $_obfuscate_VgKtFeg? );
                    }
                    $this->fromParentValue[$_obfuscate_QHYNVoqEtzC['id']] = $_obfuscate_VgKtFeg?;
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
        if ( !is_array( $_obfuscate_7Hp0w_lfFt4? ) )
        {
            $_obfuscate_7Hp0w_lfFt4? = array( );
        }
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
        if ( !is_array( $_obfuscate_MYQ3MdCHwszPfGoLQw?? ) )
        {
            $_obfuscate_MYQ3MdCHwszPfGoLQw?? = array( );
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

    public function _otherApp( )
    {
        global $CNOA_DB;
        $_obfuscate_CgmidHjdgZGsBg?? = getpar( $_GET, "otherApp", 0 );
        $_obfuscate_zthMC4Odd8? = $CNOA_DB->db_getone( "*", $this->t_s_flow_other_app_data, "WHERE `id` = ".$_obfuscate_CgmidHjdgZGsBg??." " );
        $_obfuscate_zthMC4Odd8? = json_decode( $_obfuscate_zthMC4Odd8?['data'], TRUE );
        foreach ( $this->flowFields as $_obfuscate_5w?? => $_obfuscate_6A?? )
        {
            if ( isset( $_obfuscate_zthMC4Odd8?[$_obfuscate_6A??['id']] ) )
            {
                $this->flowFields[$_obfuscate_5w??]['dvalue'] = $_obfuscate_zthMC4Odd8?[$_obfuscate_6A??['id']];
            }
        }
    }

    public function crteateFormHtml( )
    {
        global $CNOA_DB;
        $jumpKongjian = array( );
        if ( !$this->startStep )
        {
            $fileName = $this->_checkDraft( $this->flowId, $this->uFlowId, $this->stepId );
            if ( $fileName )
            {
                $this->draft = 1;
                $this->draftData = include_once( $fileName );
                $fieldData = $this->draftData['field'];
            }
            else
            {
                $fieldData = $this->api_getWfFieldData( $this->flowId, $GLOBALS['UWFCACHE']->getUflowId( ), TRUE );
            }
        }
        else
        {
            $fieldData = array( );
            if ( $this->uFlowId != 0 )
            {
                $fieldData = $this->api_getWfFieldData( $this->flowId, $this->uFlowId, TRUE );
            }
        }
        $dblist = $CNOA_DB->db_select( "*", "wf_s_step_child_kongjian", "WHERE `flowId`=".$this->flowId." OR `bangdingFlow`={$this->flowId}" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $v )
        {
            if ( $v['flowId'] == $this->flowId && $v['arrow'] == "right" )
            {
                if ( !( $v['childType'] == "flownum" ) || !( $v['childType'] == "flowname" ) )
                {
                    $fieldId = str_replace( "T_", "", $v['parentKongjian'] );
                    $jumpKongjian[$fieldId] = $v;
                }
            }
            else if ( !( $v['bangdingFlow'] == $this->flowId ) && !( $v['arrow'] == "left" ) && !( $v['parentType'] == "flownum" ) || !( $v['parentType'] == "flowname" ) )
            {
                $fieldId = str_replace( "T_", "", $v['childKongjian'] );
                $jumpKongjian[$fieldId] = $v;
            }
        }
        unset( $dblist );
        unset( $fieldId );
        if ( $this->againId )
        {
            $againFieldInfo = $CNOA_DB->db_select( array( "fieldId" ), $this->t_set_step_fields, "WHERE `stepId`=2 AND `flowId`=".$this->flowId." AND `write`=1 " );
            if ( !is_array( $againFieldInfo ) )
            {
                $againFieldInfo = array( );
            }
            $againFieldIds = array( );
            foreach ( $againFieldInfo as $v )
            {
                $againFieldIds[$v['fieldId']] = $v['fieldId'];
            }
            $diffFieldId = array_diff_key( $fieldData, $againFieldIds );
            if ( $diffFieldId )
            {
                foreach ( $diffFieldId as $k => $v )
                {
                    unset( $fieldData[$k] );
                }
            }
        }
        foreach ( $this->flowFields as $fk => $fv )
        {
            if ( isset( $this->fromParentValue[$fv['id']] ) )
            {
                $fieldData[$fv['id']] = $this->fromParentValue[$fv['id']];
            }
            if ( array_key_exists( $fv['id'], $jumpKongjian ) )
            {
                $fv['otype'] = "jumpKongjian";
                $fv['jump'] = $jumpKongjian[$fv['id']];
            }
            if ( $fv['dvalue'] == "undefined" )
            {
                $fv['dvalue'] = $this->flowFields[$fk]['dvalue'] = "";
            }
            $rule = $this->stepFields[$fv['id']];
            if ( empty( $rule ) )
            {
                $rule = array( "show" => 1, "hide" => 0, "edit" => 0, "write" => 0, "must" => 0 );
            }
            $this->fieldRule = $rule;
            if ( $rule['show'] == 1 )
            {
                $item = $this->formatFormItem( $fv, $rule, $fieldData[$fv['id']] );
            }
            else if ( $fv['otype'] == "calculate" )
            {
                $item = $this->formatFormItem( $fv, $rule, $fieldData[$fv['id']] );
            }
            else
            {
                $item = "";
            }
            if ( $fv['otype'] == "attach" && !empty( $this->uFlowId ) )
            {
                $attachPer = $CNOA_DB->db_getone( "*", $this->t_set_step, "WHERE flowId=".$this->flowId." AND stepId = {$this->stepId}" );
                $flowConfig = array( );
                $flowConfig['allowAttachAdd'] = $attachPer['allowAttachAdd'];
                $flowConfig['allowAttachEdit'] = $attachPer['allowAttachEdit'];
                $flowConfig['allowAttachDelete'] = 0;
                $flowConfig['allowAttachView'] = $attachPer['allowAttachView'];
                $flowConfig['allowAttachDown'] = $attachPer['allowAttachDown'];
                $attachKJ = $CNOA_DB->db_getone( "*", "z_wf_t_".$this->flowId, "WHERE uFlowId=".$this->uFlowId );
                $fv['attach'] = $attachKJ["T_"."{$fv['id']}"];
                if ( $fv['attach'] )
                {
                    $this->flowInfo['html'] = "";
                    ( );
                    $fs = new fs( );
                    $attach = $fs->getListInfo4wf( json_decode( $fv['attach'], TRUE ), $flowConfig, TRUE, "deal" );
                    foreach ( $attach as $key => $value )
                    {
                        $this->flowInfo['html'] .= "<tr class=\"attach_".$value['attachid']."\"><td colspan=\"100\">".$value['filename'].$value['optStr']."</tr></td><br/>";
                    }
                    $this->flowInfo['formHtml'] = str_replace( $fv['html'], $fv['html']."<br/>".$this->flowInfo['html'], $this->flowInfo['formHtml'] );
                }
            }
            if ( $fv['otype'] == "detailtable" )
            {
                $this->flowInfo['formHtml'] = str_replace( $fv['html'], "<{[change]}>", $this->flowInfo['formHtml'] );
                $html = explode( "<{[change]}>", $this->flowInfo['formHtml'] );
                $html[0] = substr( $html[0], 0, strripos( $html[0], "<tr" ) );
                $html[1] = substr( $html[1], stripos( $html[1], "</tr>" ) + 5, strlen( $html[1] ) );
                $this->flowInfo['formHtml'] = $html[0].$item.$html[1];
                $this->flowInfo['formHtml'] = str_replace( "visibility:hidden", "display:none", $this->flowInfo['formHtml'] );
            }
            else
            {
                $this->flowInfo['formHtml'] = str_replace( $fv['html'], $item, $this->flowInfo['formHtml'] );
            }
        }
        $this->flowInfo['formHtml'] .= $this->extHtml;
        if ( !empty( $this->flowInfo['bindfunction'] ) )
        {
            if ( $this->flowInfo['bindfunction'] == "salaryApprove" )
            {
                $salaryApproveId = ( integer )getpar( $_GET, "salaryApproveId" );
                $salaryApproveHtml = "<p><a style='display:none' otype='salaryApproveId' value='".$salaryApproveId."'></a></p>";
                $this->flowInfo['formHtml'] .= $salaryApproveHtml;
            }
            else if ( $this->flowInfo['bindfunction'] == "userReadlySubmit" || $this->flowInfo['bindfunction'] == "userSubmit" )
            {
                $userCid = getpar( $_GET, "userCid" );
                $userHtml = "<p><a style='display:none' otype='userCid' value='".$userCid."'></a></p>";
                $this->flowInfo['formHtml'] .= $userHtml;
            }
            $this->flowInfo['formHtml'] = "<p><a style=\"display:none\" otype=\"bindfunc\" value=\"".$this->flowInfo['bindfunction']."\"></a></p>".$this->flowInfo['formHtml'];
        }
        return $this->flowInfo['formHtml'];
    }

    public function formatFormItem( $element, $rule, $value = "" )
    {
        if ( $element['tagName'] != "input" )
        {
            return "";
        }
        if ( in_array( $element['otype'], array( "textfield", "textarea", "select", "radio", "checkbox", "calculate", "macro", "choice", "detailtable", "signature", "jumpKongjian", "datasource", "attach", "abutment" ) ) )
        {
            eval( "\$return = \$this->_format_".$element['otype']."(\$element, \$rule, \$value);" );
            return $return;
        }
        return "";
    }

    public function __isNum( $_obfuscate_42oUddZOsSA? )
    {
        return in_array( $_obfuscate_42oUddZOsSA?, array( "int", "float" ) );
    }

    protected function __getOdata( $_obfuscate_p5ZWxr4? )
    {
        return json_decode( str_replace( "'", "\"", $_obfuscate_p5ZWxr4? ), TRUE );
    }

    private function _format_textfield( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
        $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
        if ( $_obfuscate_VgKtFeg? != "" )
        {
            $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_VgKtFeg? );
        }
        else
        {
            $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_60GquoKMPw??['dvalue'] );
        }
        $_obfuscate_0sRnHy4? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "style=\"".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1].";\" ";
        $_obfuscate_vZOcQA?? = "";
        if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_TervNcSylPE? = "";
            $_obfuscate_F43xrelD_9A? = " wf_field_write";
            $_obfuscate_3gn_eQ?? = "name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" ";
            $_obfuscate_4Bjbo6tz = $_obfuscate_p5ZWxr4?['dataType'] == "str" ? "" : "<br />??????¨¨|??¡À????".$this->autoFormat[$_obfuscate_p5ZWxr4?['dataType']][$_obfuscate_p5ZWxr4?['dataFormat']][0];
            $_obfuscate_4Bjbo6tz .= $_obfuscate_p5ZWxr4?['dataType'] == "str" ? "" : "<br />??????????|????".$this->autoFormat[$_obfuscate_p5ZWxr4?['dataType']][$_obfuscate_p5ZWxr4?['dataFormat']][1];
            if ( $_obfuscate_6mlyHg??['must'] == 1 )
            {
                $_obfuscate_F43xrelD_9A? = " wf_field_must";
                $_obfuscate_vZOcQA?? = "must=\"true\"";
            }
            $_obfuscate_LQ8UKg?? = "<input type=\"text\" ".$_obfuscate_vZOcQA??." id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" ".$_obfuscate_3gn_eQ??.( $_obfuscate_p5ZWxr4?['dataType'] == "str" ? "" : "autoformat=\"".$this->autoFormatPHP[$_obfuscate_p5ZWxr4?['dataType']][$_obfuscate_p5ZWxr4?['dataFormat']]."\" " )."ext:qtip=\"".$_obfuscate_60GquoKMPw??['name'].$_obfuscate_4Bjbo6tz."\"field=\"".$_obfuscate_60GquoKMPw??['id']."\"field=\"".$_obfuscate_60GquoKMPw??['id']."\"otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" isnum=\"".( $this->__isNum( $_obfuscate_p5ZWxr4?['dataType'] ) ? "true" : "false" )."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" value=\"".$_obfuscate_VgKtFeg?."\" ".$_obfuscate_0sRnHy4?.$_obfuscate_TervNcSylPE?." tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" />";
            return $_obfuscate_LQ8UKg??;
        }
        $_obfuscate_TervNcSylPE? = "readonly=\"readonly\"";
        $_obfuscate_F43xrelD_9A? = " wf_field_read";
        if ( $_obfuscate_60GquoKMPw??['childKong'] == 1 )
        {
            $_obfuscate_3gn_eQ?? = "name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" ";
        }
        else
        {
            $_obfuscate_3gn_eQ?? = "";
            $_obfuscate_4Bjbo6tz = "";
        }
        $_obfuscate_LQ8UKg?? = "<input type=\"text\" ".$_obfuscate_vZOcQA??." id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" ".$_obfuscate_3gn_eQ??.( $_obfuscate_p5ZWxr4?['dataType'] == "str" ? "" : "autoformat=\"".$this->autoFormatPHP[$_obfuscate_p5ZWxr4?['dataType']][$_obfuscate_p5ZWxr4?['dataFormat']]."\" " )."ext:qtip=\"".$_obfuscate_60GquoKMPw??['name'].$_obfuscate_4Bjbo6tz."<br>?????¡ä?????1???<span class='cnoa_color_red'>".$_obfuscate_VgKtFeg?."</span>\"otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" field=\"".$_obfuscate_60GquoKMPw??['id']."\"oname=\"".$_obfuscate_60GquoKMPw??['name']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" value=\"".$_obfuscate_VgKtFeg?."\" ".$_obfuscate_0sRnHy4?.$_obfuscate_TervNcSylPE?." tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" />";
        return $_obfuscate_LQ8UKg??;
    }

    private function _format_signature( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
        $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
        $_obfuscate_9VrqILQ = getpar( $_GET, "device", "" );
        $_obfuscate_LQ8UKg?? = "<span style=\"position:relative;display:inline-block;width:".$_obfuscate_p5ZWxr4?['width']."px;\"> ";
        if ( !empty( $_obfuscate_VgKtFeg? ) )
        {
            $_obfuscate_VgKtFeg? = json_decode( $_obfuscate_VgKtFeg?, TRUE );
            $_obfuscate_0sRnHy4? = "position:absolute; float:left; left: ".$_obfuscate_VgKtFeg?['left']."px; top: ".$_obfuscate_VgKtFeg?['top']."px;";
        }
        if ( $_obfuscate_9VrqILQ == "Phone" || $_obfuscate_9VrqILQ == "Tablet" )
        {
            $_obfuscate_y8OVa_c? = NULL;
            $_obfuscate_lEGQqw?? = $_obfuscate_6RYLWQ?? = "";
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_y8OVa_c? = "wfGraphSignature(this)";
            }
            if ( is_array( $_obfuscate_VgKtFeg? ) )
            {
                $_obfuscate_lEGQqw?? = "{$_obfuscate_LQ8UKg??}<img fieldid=\"{$_obfuscate_60GquoKMPw??['id']}\"  text=\"{{$_obfuscate_60GquoKMPw??['name']}}\" data=\"{$_obfuscate_60GquoKMPw??['style']}\" src=\"{$_obfuscate_VgKtFeg?['url']}\" onclick=\"{$_obfuscate_y8OVa_c?}\" style=\"{$_obfuscate_0sRnHy4?}\" /></span>";
                $_obfuscate_6RYLWQ?? = "url:".$_obfuscate_VgKtFeg?['url'].";left:{$_obfuscate_VgKtFeg?['left']};top:{$_obfuscate_VgKtFeg?['top']}";
            }
            else
            {
                $_obfuscate_lEGQqw?? .= "<button fieldid=\"".$_obfuscate_60GquoKMPw??['id']."\"  text=\"{{$_obfuscate_60GquoKMPw??['name']}}\" data=\"{$_obfuscate_60GquoKMPw??['style']}\" style=\"{$_obfuscate_60GquoKMPw??['style']}\" onclick=\"{$_obfuscate_y8OVa_c?}\">{$_obfuscate_60GquoKMPw??['name']}</button>";
            }
            $_obfuscate_lEGQqw?? .= "<input type=\"hidden\" name=\"wf_fieldS_".$_obfuscate_60GquoKMPw??['id']."\" value=\"{$_obfuscate_6RYLWQ??}\" />";
            unset( $_obfuscate_y8OVa_c? );
            unset( $_obfuscate_6RYLWQ?? );
            return $_obfuscate_lEGQqw??;
        }
        if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_DkYUGTH = "";
            $_obfuscate_6RYLWQ?? = "";
            if ( $_obfuscate_p5ZWxr4?['type'] == "graph" )
            {
                if ( !empty( $_obfuscate_VgKtFeg? ) )
                {
                    $_obfuscate_LQ8UKg?? .= "<img src=\"".$_obfuscate_VgKtFeg?['url']."\" style=\"".$_obfuscate_0sRnHy4?."background-color:#FFFACD;border:1px dashed red;cursor:move;-moz-opacity:0.8;opacity:.80;filter:alpha(opacity=80);\" signaturetype=\"".$_obfuscate_p5ZWxr4?['type']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" id=\"wf_fieldS_".$_obfuscate_60GquoKMPw??['id']."\" />";
                    $_obfuscate_6RYLWQ?? = "url:".$_obfuscate_VgKtFeg?['url'].";left:{$_obfuscate_VgKtFeg?['left']};top:{$_obfuscate_VgKtFeg?['top']}";
                    $_obfuscate_DkYUGTH = "display:none;";
                }
                $_obfuscate_LQ8UKg?? .= "<input type=\"button\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" signaturetype=\"".$_obfuscate_p5ZWxr4?['type']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" value=\"?????¡é?-????\" id=\"wf_fieldS_".$_obfuscate_60GquoKMPw??['id']."\" style=\"".$_obfuscate_DkYUGTH.$_obfuscate_60GquoKMPw??['style']."\" tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" /></span>";
            }
            else if ( $_obfuscate_p5ZWxr4?['type'] == "electron" )
            {
                $_obfuscate_yJJ1itpLOxyc = "";
                if ( !is_array( $_obfuscate_p5ZWxr4?['lockfield'] ) )
                {
                    $_obfuscate_p5ZWxr4?['lockfield'] = array( );
                }
                foreach ( $_obfuscate_p5ZWxr4?['lockfield'] as $_obfuscate_6A?? )
                {
                    $_obfuscate_yJJ1itpLOxyc .= $_obfuscate_6A??.",";
                }
                $_obfuscate_yJJ1itpLOxyc = substr( $_obfuscate_yJJ1itpLOxyc, 0, -1 );
                if ( $_obfuscate_p5ZWxr4?['kind'] == "all" )
                {
                    $_obfuscate_ncdC0pM? = $_obfuscate_p5ZWxr4?['width'] / 2;
                    $_obfuscate_60GquoKMPw??['style'] = preg_replace( "/width:(\\d+)px;/", "width:".$_obfuscate_ncdC0pM?."px;", $_obfuscate_60GquoKMPw??['style'] );
                }
                if ( $_obfuscate_p5ZWxr4?['kind'] == "all" || $_obfuscate_p5ZWxr4?['kind'] == "seal" )
                {
                    $_obfuscate_LQ8UKg?? .= "<input type=\"button\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" signaturetype=\"".$_obfuscate_p5ZWxr4?['type']."\" id=\"wf_fieldS_".$_obfuscate_60GquoKMPw??['id']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" lockfield=\"".$_obfuscate_yJJ1itpLOxyc."\" opt=\"seal\" style=\"".$_obfuscate_60GquoKMPw??['style']."\" tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" value=\"??????\" />";
                    if ( !empty( $_obfuscate_VgKtFeg?['seal'] ) )
                    {
                        $_obfuscate_LQ8UKg?? .= "<input type=\"hidden\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" sealstoredata=\"true\" value=\"".$_obfuscate_VgKtFeg?['seal']."\" id=\"seal_wf_signature_".$_obfuscate_60GquoKMPw??['id']."\" style=\"".$_obfuscate_60GquoKMPw??['style']."\" />";
                    }
                }
                if ( $_obfuscate_p5ZWxr4?['kind'] == "all" || $_obfuscate_p5ZWxr4?['kind'] == "hand" )
                {
                    $_obfuscate_LQ8UKg?? .= "<input type=\"button\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" signaturetype=\"".$_obfuscate_p5ZWxr4?['type']."\" id=\"wf_fieldS_".$_obfuscate_60GquoKMPw??['id']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" lockfield=\"".$_obfuscate_yJJ1itpLOxyc."\" opt=\"hand\" style=\"".$_obfuscate_60GquoKMPw??['style']."\" value=\"??????\" tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" />";
                    if ( !empty( $_obfuscate_VgKtFeg?['handwrite'] ) )
                    {
                        $_obfuscate_LQ8UKg?? .= "<input type=\"hidden\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" sealstoredata=\"true\" value=\"".$_obfuscate_VgKtFeg?['handwrite']."\" style=\"".$_obfuscate_60GquoKMPw??['style']."\" id=\"hand_wf_signature_".$_obfuscate_60GquoKMPw??['id']."\" />";
                    }
                }
                $_obfuscate_LQ8UKg?? .= "</span>";
            }
            $_obfuscate_LQ8UKg?? .= "<input type=\"hidden\" name=\"wf_fieldS_".$_obfuscate_60GquoKMPw??['id']."\" field=\"".$_obfuscate_60GquoKMPw??['id']."\"oname=\"".$_obfuscate_60GquoKMPw??['name']."\" signaturetype=\"".$_obfuscate_p5ZWxr4?['type']."\" value=\"".$_obfuscate_6RYLWQ??."\" ".( $_obfuscate_6mlyHg??['must'] ? "must=\"true\" mustfor=\"signature\" " : "" )."/>";
            return $_obfuscate_LQ8UKg??;
        }
        if ( $_obfuscate_p5ZWxr4?['type'] == "graph" )
        {
            if ( !empty( $_obfuscate_VgKtFeg? ) )
            {
                $_obfuscate_LQ8UKg?? .= "<img src=\"".$_obfuscate_VgKtFeg?['url']."\" style=\"".$_obfuscate_0sRnHy4?."\"/>";
            }
        }
        else if ( $_obfuscate_p5ZWxr4?['type'] == "electron" )
        {
            if ( $_obfuscate_p5ZWxr4?['kind'] == "all" )
            {
                $_obfuscate_ncdC0pM? = $_obfuscate_p5ZWxr4?['width'] / 2;
                $_obfuscate_60GquoKMPw??['style'] = preg_replace( "/width:(\\d+)px;/", "width:".$_obfuscate_ncdC0pM?."px;", $_obfuscate_60GquoKMPw??['style'] );
            }
            if ( !empty( $_obfuscate_VgKtFeg?['seal'] ) )
            {
                $_obfuscate_LQ8UKg?? .= "<input type=\"hidden\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" sealstoredata=\"true\" value=\"".$_obfuscate_VgKtFeg?['seal']."\" style=\"".$_obfuscate_60GquoKMPw??['style']."\" id=\"seal_wf_signature_".$_obfuscate_60GquoKMPw??['id']."\" />";
            }
            if ( !empty( $_obfuscate_VgKtFeg?['handwrite'] ) )
            {
                $_obfuscate_LQ8UKg?? .= "<input type=\"hidden\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" sealstoredata=\"true\" value=\"".$_obfuscate_VgKtFeg?['handwrite']."\" style=\"".$_obfuscate_60GquoKMPw??['style']."\" id=\"hand_wf_signature_".$_obfuscate_60GquoKMPw??['id']."\" />";
            }
        }
        $_obfuscate_LQ8UKg?? .= "</span>";
        return $_obfuscate_LQ8UKg??;
    }

    private function _format_textarea( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
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
        $_obfuscate_0sRnHy4? = "style=\"".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1]."\" ";
        $_obfuscate_vZOcQA?? = "";
        if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_TervNcSylPE? = "";
            $_obfuscate_F43xrelD_9A? = " wf_field_write";
            $_obfuscate_3gn_eQ?? = "name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" ";
            if ( $_obfuscate_6mlyHg??['must'] == 1 )
            {
                $_obfuscate_F43xrelD_9A? = " wf_field_must";
                $_obfuscate_vZOcQA?? = "must=\"true\"";
            }
        }
        else
        {
            $_obfuscate_TervNcSylPE? = "readonly=\"readonly\"";
            $_obfuscate_F43xrelD_9A? = " wf_field_read";
            if ( $_obfuscate_60GquoKMPw??['childKong'] == 1 )
            {
                $_obfuscate_3gn_eQ?? = "name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" ";
            }
            else
            {
                $_obfuscate_3gn_eQ?? = "";
                $_obfuscate_4Bjbo6tz = "";
            }
        }
        if ( $_obfuscate_p5ZWxr4?['richText'] == "on" )
        {
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_LQ8UKg?? = "<textarea ".$_obfuscate_vZOcQA??." id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" ".$_obfuscate_3gn_eQ??."field=\"".$_obfuscate_60GquoKMPw??['id']."\"otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_0sRnHy4?."tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" >".$_obfuscate_VgKtFeg?."</textarea>";
                $_obfuscate_LQ8UKg?? .= "<input type=\"hidden\" richtext=\"true\" from=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" />";
                return $_obfuscate_LQ8UKg??;
            }
            $_obfuscate_60GquoKMPw??['style'] = str_replace( "height:", "min-height:", $_obfuscate_60GquoKMPw??['style'] ).( ";_height:".$_obfuscate_p5ZWxr4?['height'].";overflow:visible;" );
            $_obfuscate_60GquoKMPw??['style'] = str_replace( "HEIGHT:", "min-HEIGHT:", $_obfuscate_60GquoKMPw??['style'] ).( ";_height:".$_obfuscate_p5ZWxr4?['height'].";overflow:visible;" );
            $_obfuscate_LQ8UKg?? .= "<div id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."_ct\" style=\"".$_obfuscate_60GquoKMPw??['style'].";text-align:left;\">".htmlspecialchars_decode( $_obfuscate_VgKtFeg? )."</div><br/>";
            return $_obfuscate_LQ8UKg??;
        }
        $_obfuscate_LQ8UKg?? = "<textarea ".$_obfuscate_vZOcQA??." id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" ".$_obfuscate_3gn_eQ??."ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" field=\"".$_obfuscate_60GquoKMPw??['id']."\"otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_0sRnHy4?.$_obfuscate_TervNcSylPE?." tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" >".$_obfuscate_VgKtFeg?."</textarea>";
        return $_obfuscate_LQ8UKg??;
    }

    private function _format_select( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
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
        $_obfuscate_0sRnHy4? = "style=\"".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1]."\" ";
        $_obfuscate_vZOcQA?? = "";
        if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_TervNcSylPE? = "";
            $_obfuscate_F43xrelD_9A? = " wf_field_write";
            if ( $_obfuscate_6mlyHg??['must'] == 1 )
            {
                $_obfuscate_F43xrelD_9A? = " wf_field_must";
                $_obfuscate_vZOcQA?? = "must=\"true\"";
            }
            $_obfuscate_LQ8UKg?? = "<select ".$_obfuscate_vZOcQA??." ".$_obfuscate_TervNcSylPE?." id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" field=\"".$_obfuscate_60GquoKMPw??['id']."\"oname=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" ".$_obfuscate_0sRnHy4?."tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" >";
            if ( !is_array( $_obfuscate_p5ZWxr4?['dataItems'] ) )
            {
                $_obfuscate_p5ZWxr4?['dataItems'] = array( );
            }
            $_obfuscate_LQ8UKg?? .= "<option value=\"\">&#160;</option>";
            foreach ( $_obfuscate_p5ZWxr4?['dataItems'] as $_obfuscate_Ilme )
            {
                if ( $_obfuscate_p5ZWxr4?['dataType'] == "int" )
                {
                    $_obfuscate_nBiPkeZjKpA? = $_obfuscate_VgKtFeg? == $_obfuscate_Ilme['value'] ? " selected" : "";
                    $_obfuscate_hgei5uBj = $_obfuscate_Ilme['value'];
                }
                else
                {
                    $_obfuscate_nBiPkeZjKpA? = $_obfuscate_VgKtFeg? == $_obfuscate_Ilme['name'] ? " selected" : "";
                    $_obfuscate_hgei5uBj = $_obfuscate_Ilme['name'];
                }
                $_obfuscate_LQ8UKg?? .= "<option value=\"".$_obfuscate_hgei5uBj."\" ".$_obfuscate_nBiPkeZjKpA?.">".$_obfuscate_Ilme['name']."</option>";
            }
            $_obfuscate_LQ8UKg?? .= "</select>";
            return $_obfuscate_LQ8UKg??;
        }
        $_obfuscate_LQ8UKg?? = "<input type=\"text\" ".$_obfuscate_vZOcQA??." id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."<br>?????¡ä?????1???<span class='cnoa_color_red'>".$_obfuscate_VgKtFeg?."</span>\"otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" field=\"".$_obfuscate_60GquoKMPw??['id']."\"oname=\"".$_obfuscate_60GquoKMPw??['name']."\" class=\"wf_field wf_field_read\" value=\"".$_obfuscate_VgKtFeg?."\" ".$_obfuscate_0sRnHy4?."readonly=\"readonly\" style=\"border:none;\" tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" />";
        return $_obfuscate_LQ8UKg??;
    }

    private function _format_radio( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
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
        $_obfuscate_0sRnHy4? = "style=\"display:inline-block;".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1]."\" ";
        $_obfuscate_vZOcQA?? = "";
        if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_F43xrelD_9A? = " wf_field_write";
            if ( $_obfuscate_6mlyHg??['must'] == 1 )
            {
                $_obfuscate_F43xrelD_9A? = " wf_field_must";
                $_obfuscate_vZOcQA?? = "must=\"true\"";
            }
            $_obfuscate_LQ8UKg?? = "<span class=\"".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_0sRnHy4?.">";
            if ( !is_array( $_obfuscate_p5ZWxr4?['dataItems'] ) )
            {
                $_obfuscate_p5ZWxr4?['dataItems'] = array( );
            }
            $_obfuscate_0W8? = 0;
            foreach ( $_obfuscate_p5ZWxr4?['dataItems'] as $_obfuscate_5w?? => $_obfuscate_Ilme )
            {
                $_obfuscate_VPuPMczbSQ?? = "";
                $_obfuscate_ts4? = FALSE;
                if ( !empty( $_obfuscate_VgKtFeg? ) )
                {
                    $_obfuscate_VPuPMczbSQ?? = $_obfuscate_VgKtFeg? == $_obfuscate_Ilme['name'] ? "checked=\"checked\"" : "";
                    $_obfuscate_ts4? = $_obfuscate_VgKtFeg? == $_obfuscate_Ilme['name'] ? TRUE : FALSE;
                }
                else
                {
                    $_obfuscate_VPuPMczbSQ?? = $_obfuscate_Ilme['pchecked'] == "on" ? "checked=\"checked\"" : "";
                    $_obfuscate_ts4? = $_obfuscate_Ilme['pchecked'] == "on" ? TRUE : FALSE;
                }
                ++$_obfuscate_0W8?;
                $_obfuscate_r9VC_isDSQ?? = "";
                if ( $_obfuscate_p5ZWxr4?['dataType'] == "display" && ( !empty( $_obfuscate_Ilme['display'] ) && !empty( $_obfuscate_Ilme['undisplay'] ) ) )
                {
                    $_obfuscate_r9VC_isDSQ?? = "onclick=\"CNOA_wf_form_checker.showHide('".$_obfuscate_Ilme['display']."','".$_obfuscate_Ilme['undisplay']."', {radio:this})\"";
                    if ( $_obfuscate_ts4? )
                    {
                        $this->extHtml .= "<input type=\"hidden\" showhide=\"true\" display=\"".$_obfuscate_Ilme['display']."\" undisplay=\"".$_obfuscate_Ilme['undisplay']."\" fromtype=\"radio\" from=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."_".$_obfuscate_0W8?."\">";
                    }
                }
                $_obfuscate_LQ8UKg?? .= "<label for=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."_".$_obfuscate_0W8?."\"><input type=\"radio\" ".$_obfuscate_r9VC_isDSQ??."\" id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."_".$_obfuscate_0W8?."\" name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" ".$_obfuscate_vZOcQA??." ".$_obfuscate_VPuPMczbSQ??." field=\"".$_obfuscate_60GquoKMPw??['id']."\"oname=\"".$_obfuscate_60GquoKMPw??['name']."\" value=\"".$_obfuscate_Ilme['name']."\" tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" >".$_obfuscate_Ilme['name']."</label>";
                $_obfuscate_LQ8UKg?? .= count( $_obfuscate_p5ZWxr4?['dataItems'] ) == $_obfuscate_5w?? + 1 ? "" : "&nbsp;";
                if ( !( count( $_obfuscate_p5ZWxr4?['dataItems'] ) != $_obfuscate_5w?? + 1 ) && !( $_obfuscate_p5ZWxr4?['henshu'] == 2 ) )
                {
                    $_obfuscate_LQ8UKg?? .= "<br />";
                }
            }
            $_obfuscate_LQ8UKg?? .= "</span>";
            if ( $_obfuscate_6mlyHg??['must'] == 1 )
            {
                $this->extHtml .= "<input type=\"hidden\" must=\"true\" foroname=\"".$_obfuscate_60GquoKMPw??['name']."\" mustfor=\"radio\" musttarget=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" />";
                return $_obfuscate_LQ8UKg??;
            }
        }
        else
        {
            $_obfuscate_LQ8UKg?? = "<span ".$_obfuscate_0sRnHy4?.">";
            $_obfuscate_XmnUiLlqOQ?? = TRUE;
            if ( !is_array( $_obfuscate_p5ZWxr4?['dataItems'] ) )
            {
                $_obfuscate_p5ZWxr4?['dataItems'] = array( );
            }
            foreach ( $_obfuscate_p5ZWxr4?['dataItems'] as $_obfuscate_5w?? => $_obfuscate_Ilme )
            {
                $_obfuscate_VPuPMczbSQ?? = "";
                $_obfuscate_ts4? = FALSE;
                if ( !empty( $_obfuscate_VgKtFeg? ) )
                {
                    $_obfuscate_VPuPMczbSQ?? = $_obfuscate_VgKtFeg? == $_obfuscate_Ilme['name'] ? "radio" : "unradio";
                    $_obfuscate_ts4? = $_obfuscate_VgKtFeg? == $_obfuscate_Ilme['name'] ? TRUE : FALSE;
                    $_obfuscate_XmnUiLlqOQ?? = FALSE;
                }
                else if ( !empty( $_obfuscate_60GquoKMPw??['dvalue'] ) )
                {
                    $_obfuscate_VPuPMczbSQ?? = $_obfuscate_60GquoKMPw??['dvalue'] == $_obfuscate_Ilme['name'] ? "radio" : "unradio";
                    $_obfuscate_ts4? = $_obfuscate_60GquoKMPw??['dvalue'] == $_obfuscate_Ilme['name'] ? TRUE : FALSE;
                    $_obfuscate_XmnUiLlqOQ?? = FALSE;
                }
                if ( $_obfuscate_p5ZWxr4?['dataType'] == "display" )
                {
                    if ( $_obfuscate_VgKtFeg? == $_obfuscate_Ilme['name'] )
                    {
                        $_obfuscate_LQ8UKg?? .= "<image onload=\"CNOA_wf_form_checker.showHide('".$_obfuscate_Ilme['display']."','".$_obfuscate_Ilme['undisplay']."', {radio:this})\" src=\"resources/images/cnoa/wf-".$_obfuscate_VPuPMczbSQ??.".gif\" />".$_obfuscate_Ilme['name'];
                    }
                    else
                    {
                        $_obfuscate_LQ8UKg?? .= "<image src=\"resources/images/cnoa/wf-".$_obfuscate_VPuPMczbSQ??.".gif\" />".$_obfuscate_Ilme['name'];
                    }
                }
                else
                {
                    $_obfuscate_LQ8UKg?? .= "<image src=\"resources/images/cnoa/wf-".$_obfuscate_VPuPMczbSQ??.".gif\" />".$_obfuscate_Ilme['name'];
                }
                $_obfuscate_LQ8UKg?? .= count( $_obfuscate_p5ZWxr4?['dataItems'] ) == $_obfuscate_5w?? + 1 ? "" : "&nbsp;";
                if ( count( $_obfuscate_p5ZWxr4?['dataItems'] ) != $_obfuscate_5w?? + 1 && $_obfuscate_p5ZWxr4?['henshu'] == 2 )
                {
                    $_obfuscate_LQ8UKg?? .= "<br />";
                }
                if ( !( $_obfuscate_p5ZWxr4?['dataType'] == "display" ) && empty( $_obfuscate_Ilme['display'] ) && empty( $_obfuscate_Ilme['undisplay'] ) || !$_obfuscate_ts4? )
                {
                    $this->extHtml .= "<input type=\"hidden\" showhide=\"true\" display=\"".$_obfuscate_Ilme['display']."\" undisplay=\"".$_obfuscate_Ilme['undisplay']."\" fromtype=\"radio\">";
                }
            }
            if ( $_obfuscate_XmnUiLlqOQ?? )
            {
                return "<span ".$_obfuscate_0sRnHy4?.">&nbsp;</span>";
            }
            $_obfuscate_LQ8UKg?? .= "</span>";
        }
        return $_obfuscate_LQ8UKg??;
    }

    private function _format_checkbox( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
        $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
        $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_VgKtFeg?, ENT_NOQUOTES );
        $_obfuscate_VgKtFeg? = json_decode( $_obfuscate_VgKtFeg?, TRUE );
        $_obfuscate_0sRnHy4? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "style=\"display:inline-block;".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1]."\" ";
        if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_F43xrelD_9A? = " wf_field_write";
            if ( $_obfuscate_6mlyHg??['must'] == 1 )
            {
                $_obfuscate_F43xrelD_9A? = " wf_field_must";
                $_obfuscate_vZOcQA?? = "must=\"true\"";
            }
            $_obfuscate_LQ8UKg?? = "<span class=\"".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_0sRnHy4?.">";
            if ( !is_array( $_obfuscate_p5ZWxr4?['dataItems'] ) )
            {
                $_obfuscate_p5ZWxr4?['dataItems'] = array( );
            }
            $_obfuscate_0W8? = 0;
            foreach ( $_obfuscate_p5ZWxr4?['dataItems'] as $_obfuscate_5w?? => $_obfuscate_Ilme )
            {
                if ( !empty( $_obfuscate_VgKtFeg? ) || is_array( $_obfuscate_VgKtFeg? ) )
                {
                    $_obfuscate_VPuPMczbSQ?? = in_array( $_obfuscate_Ilme['name'], $_obfuscate_VgKtFeg? ) ? " checked=\"checked\" " : "";
                    $_obfuscate_ts4? = in_array( $_obfuscate_Ilme['name'], $_obfuscate_VgKtFeg? ) ? "1" : "0";
                }
                else
                {
                    $_obfuscate_VPuPMczbSQ?? = $_obfuscate_Ilme['checked'] == "on" ? " checked=\"checked\" " : "";
                    $_obfuscate_ts4? = $_obfuscate_Ilme['checked'] == "on" ? "1" : "0";
                }
                ++$_obfuscate_0W8?;
                $_obfuscate_r9VC_isDSQ?? = "";
                if ( $_obfuscate_p5ZWxr4?['display'] == "on" && ( !empty( $_obfuscate_Ilme['display'] ) && !empty( $_obfuscate_Ilme['undisplay'] ) ) )
                {
                    $_obfuscate_r9VC_isDSQ?? = "CNOA_wf_form_checker.showHide('".$_obfuscate_Ilme['display']."','".$_obfuscate_Ilme['undisplay']."', {checkbox:this});";
                    $this->extHtml .= "<input type=\"hidden\" showhide=\"true\" display=\"".$_obfuscate_Ilme['display']."\" undisplay=\"".$_obfuscate_Ilme['undisplay']."\" fromtype=\"checkbox\" from=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."_".$_obfuscate_0W8?."\" checkboxck=\"".( $_obfuscate_ts4? ? "1" : "0" )."\" />";
                }
                $_obfuscate_r9VC_isDSQ?? .= "CNOA_wf_form_checker.rsyncCheckbox(this);";
                $_obfuscate_LQ8UKg?? .= "<label for=\"wf_checkbox_".$_obfuscate_60GquoKMPw??['id']."_".$_obfuscate_0W8?."\"><input type=\"checkbox\" onclick=\"".$_obfuscate_r9VC_isDSQ??."\" id=\"wf_checkbox_".$_obfuscate_60GquoKMPw??['id']."_".$_obfuscate_0W8?."\" checkboxid=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."_".$_obfuscate_0W8?."\" gid=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" itemname=\"".$_obfuscate_Ilme['name']."\" field=\"".$_obfuscate_60GquoKMPw??['id']."\"oname=\"".$_obfuscate_60GquoKMPw??['name']."\" value=\"".$_obfuscate_Ilme['name']."\" ".$_obfuscate_VPuPMczbSQ??."tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" />".$_obfuscate_Ilme['name']."</label><input type=\"hidden\"id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."_".$_obfuscate_0W8?."\" gid=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" itemname=\"".$_obfuscate_Ilme['name']."\" field=\"".$_obfuscate_60GquoKMPw??['id']."\"oname=\"".$_obfuscate_60GquoKMPw??['name']."\" name=\"wf_fieldC_".$_obfuscate_60GquoKMPw??['id']."_".$_obfuscate_Ilme['name']."\" value=\"".( empty( $_obfuscate_VPuPMczbSQ?? ) ? "" : $_obfuscate_Ilme['name'] )."\" />";
                $_obfuscate_LQ8UKg?? .= count( $_obfuscate_p5ZWxr4?['dataItems'] ) == $_obfuscate_5w?? + 1 ? "" : "&nbsp;";
                if ( !( count( $_obfuscate_p5ZWxr4?['dataItems'] ) != $_obfuscate_5w?? + 1 ) && !( $_obfuscate_p5ZWxr4?['henshu'] == 2 ) )
                {
                    $_obfuscate_LQ8UKg?? .= "<br />";
                }
            }
            $_obfuscate_LQ8UKg?? .= "</span>";
            if ( $_obfuscate_6mlyHg??['must'] == 1 )
            {
                $this->extHtml .= "<input type=\"hidden\" must=\"true\" foroname=\"".$_obfuscate_60GquoKMPw??['name']."\" mustfor=\"checkbox\" musttarget=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\">";
                return $_obfuscate_LQ8UKg??;
            }
        }
        else
        {
            $_obfuscate_LQ8UKg?? = "<span ".$_obfuscate_0sRnHy4?.">";
            $_obfuscate_XmnUiLlqOQ?? = TRUE;
            if ( !is_array( $_obfuscate_p5ZWxr4?['dataItems'] ) )
            {
                $_obfuscate_p5ZWxr4?['dataItems'] = array( );
            }
            foreach ( $_obfuscate_p5ZWxr4?['dataItems'] as $_obfuscate_5w?? => $_obfuscate_Ilme )
            {
                if ( !empty( $_obfuscate_VgKtFeg? ) || is_array( $_obfuscate_VgKtFeg? ) )
                {
                    $_obfuscate_VPuPMczbSQ?? = in_array( $_obfuscate_Ilme['name'], $_obfuscate_VgKtFeg? ) != "" ? "check" : "uncheck";
                    $_obfuscate_ts4? = in_array( $_obfuscate_Ilme['name'], $_obfuscate_VgKtFeg? ) ? "1" : "0";
                    $_obfuscate_XmnUiLlqOQ?? = FALSE;
                }
                else
                {
                    $_obfuscate_ts4? = "0";
                    $_obfuscate_VPuPMczbSQ?? = "uncheck";
                }
                $_obfuscate_LQ8UKg?? .= "<image src=\"resources/images/cnoa/wf-".$_obfuscate_VPuPMczbSQ??.".gif\" />".$_obfuscate_Ilme['name'];
                $_obfuscate_LQ8UKg?? .= count( $_obfuscate_p5ZWxr4?['dataItems'] ) == $_obfuscate_5w?? + 1 ? "" : "&nbsp;";
                if ( count( $_obfuscate_p5ZWxr4?['dataItems'] ) != $_obfuscate_5w?? + 1 && $_obfuscate_p5ZWxr4?['henshu'] == 2 )
                {
                    $_obfuscate_LQ8UKg?? .= "<br />";
                }
                if ( !( $_obfuscate_p5ZWxr4?['display'] == "on" ) && empty( $_obfuscate_Ilme['display'] ) && empty( $_obfuscate_Ilme['undisplay'] ) )
                {
                    $this->extHtml .= "<input type=\"hidden\" showhide=\"true\" display=\"".$_obfuscate_Ilme['display']."\" undisplay=\"".$_obfuscate_Ilme['undisplay']."\" fromtype=\"checkbox\" checkboxck=\"".$_obfuscate_ts4?."\" />";
                }
            }
            if ( $_obfuscate_XmnUiLlqOQ?? )
            {
                return "<span ".$_obfuscate_0sRnHy4?.">&nbsp;</span>";
            }
            $_obfuscate_LQ8UKg?? .= "</span>";
        }
        return $_obfuscate_LQ8UKg??;
    }

    private function _format_calculate( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
        $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
        $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_VgKtFeg? );
        $_obfuscate_0sRnHy4? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_sSwuE42EWQ?? = $_obfuscate_p5ZWxr4?['expression'];
        $_obfuscate_w5qdpW_0mo2_qehs = $this->api_splitGongShi( $_obfuscate_sSwuE42EWQ?? );
        $_obfuscate_t1EW = "";
        foreach ( $_obfuscate_w5qdpW_0mo2_qehs as $_obfuscate_5w?? => $_obfuscate_GrQ? )
        {
            if ( !in_array( $_obfuscate_GrQ?, array( "+", "-", "*", "/" ) ) )
            {
                foreach ( $this->flowFields as $_obfuscate_WgE? )
                {
                    if ( $_obfuscate_GrQ? == $_obfuscate_WgE?['name'] )
                    {
                        $_obfuscate_w5qdpW_0mo2_qehs[$_obfuscate_5w??] = "wf|".$_obfuscate_WgE?['id'];
                    }
                    else
                    {
                        foreach ( $this->flowDetailFields as $_obfuscate_5DM? )
                        {
                            if ( $_obfuscate_GrQ? == $_obfuscate_5DM?['name'] )
                            {
                                $_obfuscate_w5qdpW_0mo2_qehs[$_obfuscate_5w??] = "wfd|".$_obfuscate_5DM?['id'];
                                $_obfuscate_t1EW = "mix=\"true\"";
                            }
                        }
                    }
                }
            }
        }
        $_obfuscate_sSwuE42EWQ?? = json_encode( $_obfuscate_w5qdpW_0mo2_qehs );
        $_obfuscate_sSwuE42EWQ?? = str_replace( "\"", "'", $_obfuscate_sSwuE42EWQ?? );
        $_obfuscate_7R7jAawdKeMxGQ?? = $_obfuscate_p5ZWxr4?['dataFormat'] == 2 ? "autoformat=\"".$this->autoFormatPHP[$_obfuscate_p5ZWxr4?['dataType']][$_obfuscate_p5ZWxr4?['dataFormat']]."\"" : "";
        if ( $_obfuscate_6mlyHg??['hide'] == 1 )
        {
            $_obfuscate_LQ8UKg?? = "<input type=\"hidden\" id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" name=\"wf_fieldJ_".$_obfuscate_60GquoKMPw??['id']."\" field=\"".$_obfuscate_60GquoKMPw??['id']."\"otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" class=\"wf_field\" value=\"".$_obfuscate_VgKtFeg?."\"/>";
        }
        else if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_0sRnHy4? = "style=\"".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1]."\" ";
            $_obfuscate_LQ8UKg?? = "<input type=\"text\" id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" name=\"wf_fieldJ_".$_obfuscate_60GquoKMPw??['id']."\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" field=\"".$_obfuscate_60GquoKMPw??['id']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" ".$_obfuscate_7R7jAawdKeMxGQ??."class=\"wf_field\" ".$_obfuscate_0sRnHy4?."readonly=\"readonly\" value=\"".$_obfuscate_VgKtFeg?."\"tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" />";
        }
        else
        {
            $_obfuscate_0sRnHy4? = "style=\"border-width:0;".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1]."\" ";
            $_obfuscate_LQ8UKg?? = "<input type=\"text\" id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" name=\"wf_fieldJ_".$_obfuscate_60GquoKMPw??['id']."\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."<br>?????¡ä?????1???<span class='cnoa_color_red'>".$_obfuscate_VgKtFeg?."</span>\"field=\"".$_obfuscate_60GquoKMPw??['id']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" class=\"wf_field wf_field_read\" ".$_obfuscate_0sRnHy4?."readonly=\"readonly\" value=\"".$_obfuscate_VgKtFeg?."\"tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" />";
        }
        $this->extHtml .= "<input type=\"hidden\" ".$_obfuscate_7R7jAawdKeMxGQ??." calculate=\"true\" datatype=\"".$_obfuscate_p5ZWxr4?['dataType']."\" roundtype=\"".$_obfuscate_p5ZWxr4?['dataFormat']."\" baoliu=\"".$_obfuscate_p5ZWxr4?['baoliu']."\" detail=\"false\" gongshi=\"".$_obfuscate_sSwuE42EWQ??."\" field=\"".$_obfuscate_60GquoKMPw??['id']."\" to=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" />";
        return $_obfuscate_LQ8UKg??;
    }

    private function _format_macro( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
        global $CNOA_SESSION;
        global $CNOA_DB;
        $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_VgKtFeg? );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_iuzS = $CNOA_SESSION->get( "DID" );
        $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
        $_obfuscate_0sRnHy4? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "{$_obfuscate_60GquoKMPw??['style']};{$_obfuscate_0sRnHy4?[0]};{$_obfuscate_0sRnHy4?[1]}";
        if ( $_obfuscate_p5ZWxr4?['dataType'] == "huiqian" )
        {
            $_obfuscate_ouqX2cxvhA?? = "";
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
                    $_obfuscate_ouqX2cxvhA?? .= "<span style=\"line-height:25px;\">".str_replace( array( "{S}", "{U}", "{T}", "{I}" ), $_obfuscate_77tGbWOiZg??, $_obfuscate_O1qQ )."</span><br/>";
                }
            }
            return "<div style='".$_obfuscate_0sRnHy4?.";display:inline-block'>{$_obfuscate_ouqX2cxvhA??}</div>";
        }
        if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_e7PLR79F = $_obfuscate_p5ZWxr4?['dataFormat'];
            $_obfuscate_2r6NB7kV = $_obfuscate_60GquoKMPw??['style'];
            $_obfuscate_U0CBGzzg = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
            $_obfuscate_3gn_eQ?? = "wf_field_".$_obfuscate_60GquoKMPw??['id'];
            if ( $_obfuscate_p5ZWxr4?['allowedit'] == 1 && !in_array( $_obfuscate_p5ZWxr4?['dataType'], array( "flowname", "flownum" ) ) )
            {
                $_obfuscate_TervNcSylPE? = "";
                $_obfuscate_F43xrelD_9A? = " wf_field_write";
            }
            else
            {
                $_obfuscate_TervNcSylPE? = "readonly=\"readonly\"";
                $_obfuscate_F43xrelD_9A? = "";
            }
            $_obfuscate_LQ8UKg?? = "<input type=\"text\"  class=\"wf_field ".$_obfuscate_F43xrelD_9A?."\" id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" ".( "name='".$_obfuscate_3gn_eQ??."'" )."ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" field=\"".$_obfuscate_60GquoKMPw??['id']."\"otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" ".( "style='".$_obfuscate_0sRnHy4?."'" );
            switch ( $_obfuscate_p5ZWxr4?['dataType'] )
            {
            case "month" :
                $_obfuscate_LQ8UKg?? .= "value=\"".$this->__formatMonth( $_obfuscate_e7PLR79F )."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "quarter" :
                $_obfuscate_LQ8UKg?? .= "value=\"".$this->__formatQuarter( $_obfuscate_e7PLR79F )."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "datetime" :
                $_obfuscate_LQ8UKg?? .= "value=\"".$this->__formatDatetime( $_obfuscate_e7PLR79F )."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "flowname" :
                $_obfuscate_LQ8UKg?? .= "value=\"".$this->flowName."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "flownum" :
                $_obfuscate_LQ8UKg?? .= "value=\"".$this->flowNumber."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "createrlname" :
                $_obfuscate_LQ8UKg?? .= "value=\"".app::loadapp( "main", "user" )->api_getUserNameByUid( $this->creatorUid )."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "creatername" :
                $_obfuscate_LQ8UKg?? .= "value=\"".app::loadapp( "main", "user" )->api_getUserTruenameByUid( $this->creatorUid )."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "createrdept" :
                $_obfuscate_vwGQSA?? = app::loadapp( "main", "struct" )->api_getDeptByUid( $this->creatorUid );
                $_obfuscate_LQ8UKg?? .= "value=\"".$_obfuscate_vwGQSA??['name']."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "createrjob" :
                $_obfuscate_97K3 = app::loadapp( "main", "job" )->api_getNameByUid( $this->creatorUid );
                $_obfuscate_LQ8UKg?? .= "value=\"".$_obfuscate_97K3."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "createrstation" :
                $_obfuscate_97K3 = app::loadapp( "main", "station" )->api_getNameByUid( $this->creatorUid );
                $_obfuscate_LQ8UKg?? .= "value=\"".$_obfuscate_97K3."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "loginlname" :
                $_obfuscate_LQ8UKg?? .= "value=\"".$CNOA_SESSION->get( "USERNAME" )."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "loginname" :
                $_obfuscate_LQ8UKg?? = "<input type='text' value='".$CNOA_SESSION->get( "TRUENAME" )."' ".( "class='wf_field".$_obfuscate_F43xrelD_9A?."' " ).( "ext:qtip='".$_obfuscate_60GquoKMPw??['name']."' " )."tabindex='".$_obfuscate_60GquoKMPw??['order']."' ".( "style='".$_obfuscate_0sRnHy4?."' {$_obfuscate_TervNcSylPE?} />" );
                $_obfuscate_LQ8UKg?? .= "<input type='hidden' id='wf_field_".$_obfuscate_60GquoKMPw??['id']." ".( "field='".$_obfuscate_60GquoKMPw??['id']."' " ).( "otype='".$_obfuscate_60GquoKMPw??['otype']."' " ).( "oname='".$_obfuscate_60GquoKMPw??['oname']."' " ).( "value='".$CNOA_SESSION->get( "UID" )."' " ).( "name='".$_obfuscate_3gn_eQ??."' />" );
            case "logindept" :
                $_obfuscate_vwGQSA?? = app::loadapp( "main", "struct" )->api_getDeptByUid( $_obfuscate_7Ri3 );
                $_obfuscate_LQ8UKg?? .= "value=\"".$_obfuscate_vwGQSA??['name']."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "loginjob" :
                $_obfuscate_97K3 = app::loadapp( "main", "job" )->api_getNameById( $CNOA_SESSION->get( "JID" ) );
                $_obfuscate_LQ8UKg?? .= "value=\"".$_obfuscate_97K3."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "loginstation" :
                $_obfuscate_97K3 = app::loadapp( "main", "station" )->api_getNameById( $CNOA_SESSION->get( "SID" ) );
                $_obfuscate_LQ8UKg?? .= "value=\"".$_obfuscate_97K3."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "userip" :
                $_obfuscate_LQ8UKg?? .= "value=\"".getip( )."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "moneyconvert" :
                $_obfuscate_LQ8UKg?? .= $_obfuscate_TervNcSylPE?." isvalid=\"true\" />";
                $_obfuscate_BJBeoQ4Zepw? = $this->api_getFieldInfoByName( $_obfuscate_p5ZWxr4?['from'], $this->flowId );
                $this->extHtml .= "<input type=\"hidden\" moneyconvert=\"true\" fromcount=\"".$_obfuscate_p5ZWxr4?['fromcount']."\" from=\"wf_field_".$_obfuscate_BJBeoQ4Zepw?['id']."\" to=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" />";
            case "budgetDept" :
                $_obfuscate_2sZ8Toxw = $_obfuscate_XRvPgP5V0t4? = "";
                if ( $this->uFlowId )
                {
                    $_obfuscate_3y0Y = "SELECT `t`.`deptId`, `s`.`name` AS `deptName` FROM ".tname( "budget_tmp_tabel" )." AS `t` LEFT JOIN ".tname( "main_struct" )." AS `s` ON `s`.`id`=`t`.`deptId` ".( "WHERE `t`.`uFlowId`=".$this->uFlowId." AND `t`.`fieldId`={$_obfuscate_60GquoKMPw??['id']}" );
                    $_obfuscate_xs33Yt_k = $CNOA_DB->get_one( $_obfuscate_3y0Y );
                    $_obfuscate_2sZ8Toxw = $_obfuscate_xs33Yt_k['deptId'];
                    $_obfuscate_XRvPgP5V0t4? = $_obfuscate_xs33Yt_k['deptName'];
                }
                $_obfuscate_LQ8UKg?? .= "{$_obfuscate_TervNcSylPE?} budgetDept='true' to='{$_obfuscate_60GquoKMPw??['id']}' value='{$_obfuscate_VgKtFeg?}' deptname='{$_obfuscate_XRvPgP5V0t4?}' />";
                $_obfuscate_LQ8UKg?? .= "<input type='hidden' name='wf_budgetDept_".$_obfuscate_60GquoKMPw??['id']."' budgetDeptId=true value='{$_obfuscate_2sZ8Toxw}' />";
            case "budgetProj" :
                $_obfuscate_nGayiuNZ = $_obfuscate_XRvPgP5V0t4? = "";
                if ( $this->uFlowId )
                {
                    $_obfuscate_3y0Y = "SELECT `t`.`projId`, `s`.`name` AS `projName` FROM ".tname( "budget_tmp_tabel_proj" )." AS `t` LEFT JOIN ".tname( "budget_set_budget_project" )." AS `s` ON `s`.`projId`=`t`.`projId` ".( "WHERE `t`.`uFlowId`=".$this->uFlowId." AND `t`.`fieldId`={$_obfuscate_60GquoKMPw??['id']}" );
                    $_obfuscate_xs33Yt_k = $CNOA_DB->get_one( $_obfuscate_3y0Y );
                    $_obfuscate_nGayiuNZ = $_obfuscate_xs33Yt_k['projId'];
                    $_obfuscate_ = $_obfuscate_xs33Yt_k['projName'];
                }
                $_obfuscate_LQ8UKg?? .= "{$_obfuscate_TervNcSylPE?} budgetProj='true' to='{$_obfuscate_60GquoKMPw??['id']}' value='{$_obfuscate_VgKtFeg?}' projname='{$_obfuscate_}' />";
                $_obfuscate_LQ8UKg?? .= "<input type='hidden' name='wf_budgetProj_".$_obfuscate_60GquoKMPw??['id']."' budgetProjId=true value='{$_obfuscate_nGayiuNZ}' />";
                $_obfuscate_LQ8UKg?? .= "<input type='hidden' name='wf_budgetProj_dept_".$_obfuscate_60GquoKMPw??['id']."' value='{$_obfuscate_iuzS}' />";
            case "attLeave" :
                $_obfuscate_LQ8UKg?? .= "{$_obfuscate_TervNcSylPE?} attLeave='true' to='{$_obfuscate_60GquoKMPw??['id']}' value='{$_obfuscate_VgKtFeg?}' />";
                $_obfuscate_LQ8UKg?? .= "<input type='hidden' name='wf_attLeave_".$_obfuscate_60GquoKMPw??['id']."' attLeaveId=true  value=''/>";
            case "attEvection" :
                $_obfuscate_LQ8UKg?? .= "{$_obfuscate_TervNcSylPE?} attEvection='true' to='{$_obfuscate_60GquoKMPw??['id']}' value='{$_obfuscate_VgKtFeg?}' />";
                $_obfuscate_LQ8UKg?? .= "<input type='hidden' name='wf_attEvection_".$_obfuscate_60GquoKMPw??['id']."' attEvection=true  value=''/>";
            case "attTime" :
                $_obfuscate_LQ8UKg?? .= "{$_obfuscate_TervNcSylPE?} attTime='true' to='{$_obfuscate_60GquoKMPw??['id']}' value='{$_obfuscate_VgKtFeg?}' />";
                $_obfuscate_LQ8UKg?? .= "<input type='hidden' name='wf_attTime_".$_obfuscate_60GquoKMPw??['id']."' attTime=true  value=''/>";
            case "holiday" :
                $_obfuscate_LQ8UKg?? .= "value=\"".$this->getUserHoliday( )."\" ".$_obfuscate_TervNcSylPE?."/>";
            default :
                return $_obfuscate_LQ8UKg??;
            }
        }
        if ( $_obfuscate_p5ZWxr4?['dataType'] == "loginname" )
        {
            $_obfuscate_VgKtFeg? = app::loadapp( "main", "user" )->api_getUserNameByUids( intval( $_obfuscate_VgKtFeg? ) );
        }
        $_obfuscate_LQ8UKg?? = "<input type=\"text\" id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."<br>?????¡ä?????1???<span class='cnoa_color_red'>".$_obfuscate_VgKtFeg?."</span>\"field=\"".$_obfuscate_60GquoKMPw??['id']."\"otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" class=\"wf_field wf_field_read\" value=\"".$_obfuscate_VgKtFeg?."\" ".( "style='".$_obfuscate_0sRnHy4?."' " )."readonly=\"readonly\" tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" />";
        return $_obfuscate_LQ8UKg??;
    }

    private function getUserHoliday( )
    {
        include_once( CNOA_PATH."/app/att/inc/attCommon.class.php" );
        include_once( CNOA_PATH."/app/att/source/attPerson.class.php" );
        $data = app::loadapp( "att", "personClasses" )->api_getUserHoliday( );
        return " ?1¡ä?????¡À ".$data['annualLeave']." ?¡è??????a??? {$data['unUseLeave']} ?¡è??????????¨¨¡ã???? {$data['takeRest']} ?¡ã????";
    }

    private function _format_choice( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
        global $CNOA_SESSION;
        $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_VgKtFeg? );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
        $_obfuscate_e7PLR79F = $_obfuscate_p5ZWxr4?['dataFormat'];
        $_obfuscate_0sRnHy4? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "style=\"".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1]."\" ";
        $_obfuscate_vZOcQA?? = "";
        $_obfuscate_F43xrelD_9A? = " ";
        if ( $_obfuscate_6mlyHg??['edit'] == 1 )
        {
            $_obfuscate_TervNcSylPE? = "";
        }
        else
        {
            $_obfuscate_TervNcSylPE? = "readonly=\"readonly\" ";
        }
        if ( $_obfuscate_6mlyHg??['must'] == 1 )
        {
            $_obfuscate_F43xrelD_9A? = " wf_field_must";
            $_obfuscate_vZOcQA?? = "must=\"true\" ";
        }
        else if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_F43xrelD_9A? = " wf_field_write";
        }
        else
        {
            $_obfuscate_F43xrelD_9A? = " wf_field_read";
        }
        $_obfuscate_AlW8F6SaSV8O = "<input type=\"text\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_vZOcQA??.$_obfuscate_TervNcSylPE?.$_obfuscate_0sRnHy4?."id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" field=\"".$_obfuscate_60GquoKMPw??['id']."\" name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" ";
        $_obfuscate_7Ovywpwc3j7W = "<textarea ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_vZOcQA??.$_obfuscate_TervNcSylPE?.$_obfuscate_0sRnHy4?."id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" field=\"".$_obfuscate_60GquoKMPw??['id']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" ";
        $_obfuscate_2FLoTKc2yw?? = "";
        switch ( $_obfuscate_p5ZWxr4?['dataType'] )
        {
        case "date_sel" :
            switch ( $_obfuscate_p5ZWxr4?['dataFormat'] )
            {
            case 100 :
                $_obfuscate_ead0WM? = "yyyy-MM-dd";
                $_obfuscate_JQd4QztKRbU? = "Y-m-d";
                break;
            case 200 :
                $_obfuscate_ead0WM? = "yyyy-MM";
                $_obfuscate_JQd4QztKRbU? = "Y-m";
                break;
            case 400 :
                $_obfuscate_ead0WM? = "yyyyMMdd";
                $_obfuscate_JQd4QztKRbU? = "Ymd";
                break;
            case 600 :
                $_obfuscate_ead0WM? = "yyyy?1¡äMM???";
                $_obfuscate_JQd4QztKRbU? = "Y?1¡äm???";
                break;
            case 700 :
                $_obfuscate_ead0WM? = "yyyy?1¡äMM???dd??£¤";
                $_obfuscate_JQd4QztKRbU? = "Y?1¡äm???d??£¤";
                break;
            case 900 :
                $_obfuscate_ead0WM? = "yyyy.MM";
                $_obfuscate_JQd4QztKRbU? = "Y.m";
                break;
            case 1000 :
                $_obfuscate_ead0WM? = "yyyy.MM.dd";
                $_obfuscate_JQd4QztKRbU? = "Y.m.d";
                break;
            case 1200 :
                $_obfuscate_ead0WM? = "yyyy-MM-dd HH:mm";
                $_obfuscate_JQd4QztKRbU? = "Y-m-d H:i";
                break;
            case 1300 :
                $_obfuscate_ead0WM? = "yyyy?1¡äMM???dd??£¤ HH:mm";
                $_obfuscate_JQd4QztKRbU? = "Y?1¡äm???d??£¤ H:i";
                break;
            case 300 :
                $_obfuscate_ead0WM? = "yy-mm-dd";
                $_obfuscate_JQd4QztKRbU? = "y-m-d";
                break;
            case 500 :
                $_obfuscate_ead0WM? = "mm-dd yyyy";
                $_obfuscate_JQd4QztKRbU? = "m-d Y";
                break;
            case 800 :
                $_obfuscate_ead0WM? = "mm???dd??£¤";
                $_obfuscate_JQd4QztKRbU? = "m???d??£¤";
                break;
            case 1100 :
                $_obfuscate_ead0WM? = "mm.dd";
                $_obfuscate_JQd4QztKRbU? = "m.d";
            }
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                if ( getpar( $_GET, "device", "" ) == "Tablet" )
                {
                    $_obfuscate_2FLoTKc2yw?? = "onClick=\"WdatePicker('".$_obfuscate_ead0WM?."', this)\" ";
                }
                else
                {
                    $_obfuscate_2FLoTKc2yw?? = "onClick=\"WdatePicker({dateFmt:'".$_obfuscate_ead0WM?."'})\" ";
                }
            }
            if ( ( empty( $_obfuscate_VgKtFeg? ) || $_obfuscate_VgKtFeg? == "default" || $_obfuscate_VgKtFeg? == "null" ) && $_obfuscate_6mlyHg??['write'] == 1 )
            {
                if ( $_obfuscate_60GquoKMPw??['dvalue'] == "default" )
                {
                    $_obfuscate_VgKtFeg? = date( $_obfuscate_JQd4QztKRbU?, $GLOBALS['CNOA_TIMESTAMP'] );
                }
                if ( $_obfuscate_60GquoKMPw??['dvalue'] == "null" )
                {
                    $_obfuscate_VgKtFeg? = "";
                }
            }
            $_obfuscate_VgKtFeg? = "value=\"".$_obfuscate_VgKtFeg?."\" ";
            $_obfuscate_LQ8UKg?? = $_obfuscate_AlW8F6SaSV8O.$_obfuscate_VgKtFeg?.$_obfuscate_2FLoTKc2yw??." />";
            break;
        case "time_sel" :
            switch ( $_obfuscate_p5ZWxr4?['dataFormat'] )
            {
            case 100 :
                $_obfuscate_JQd4QztKRbU? = "H:i";
                break;
            case 200 :
                $_obfuscate_JQd4QztKRbU? = "H:i A";
                break;
            case 300 :
                $_obfuscate_JQd4QztKRbU? = "H:i a";
            }
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_2FLoTKc2yw?? = "onClick=\"wfTimePicker.show(this, ".intval( $_obfuscate_p5ZWxr4?['dataFormat'] ).")\" ";
            }
            if ( empty( $_obfuscate_VgKtFeg? ) && $_obfuscate_6mlyHg??['write'] == 1 && $_obfuscate_60GquoKMPw??['dvalue'] == "default" )
            {
                $_obfuscate_VgKtFeg? = date( $_obfuscate_JQd4QztKRbU?, $GLOBALS['CNOA_TIMESTAMP'] );
                $_obfuscate_VgKtFeg? = str_replace( array( "am", "pm" ), array( "??????", "??????" ), $_obfuscate_VgKtFeg? );
            }
            $_obfuscate_VgKtFeg? = "value=\"".$_obfuscate_VgKtFeg?."\" ";
            $_obfuscate_LQ8UKg?? = $_obfuscate_AlW8F6SaSV8O.$_obfuscate_VgKtFeg?.$_obfuscate_2FLoTKc2yw??." />";
            break;
        case "dept_sel" :
            if ( !empty( $_obfuscate_VgKtFeg? ) )
            {
                $_obfuscate_VcY7imjf = app::loadapp( "main", "struct" )->api_getNameById( $_obfuscate_VgKtFeg? );
            }
            $_obfuscate_AlW8F6SaSV8O = "<input type=\"text\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_vZOcQA??.$_obfuscate_TervNcSylPE?.$_obfuscate_0sRnHy4?."to=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" value=\"".$_obfuscate_VcY7imjf."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" ";
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_2FLoTKc2yw?? = " onclick=\"wf_selector('dept', this, false)\"";
            }
            $_obfuscate_LQ8UKg?? = $_obfuscate_AlW8F6SaSV8O.$_obfuscate_2FLoTKc2yw??." /><input type=\"hidden\" value=\"".$_obfuscate_VgKtFeg?."\" id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\"/>";
            break;
        case "depts_sel" :
            if ( !empty( $_obfuscate_VgKtFeg? ) )
            {
                $_obfuscate_VcY7imjf = app::loadapp( "main", "struct" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg? ) );
                $_obfuscate_VcY7imjf = implode( ",", $_obfuscate_VcY7imjf );
            }
            $_obfuscate_AlW8F6SaSV8O = "<textarea ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_vZOcQA??.$_obfuscate_TervNcSylPE?.$_obfuscate_0sRnHy4?."tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" to=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" ";
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_2FLoTKc2yw?? = " onclick=\"wf_selector('dept', this, true)\"";
            }
            $_obfuscate_LQ8UKg?? = $_obfuscate_AlW8F6SaSV8O.$_obfuscate_2FLoTKc2yw??." />".$_obfuscate_VcY7imjf."</textarea><input type=\"hidden\" value=\"".$_obfuscate_VgKtFeg?."\" id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\"/>";
            break;
        case "station_sel" :
            if ( !empty( $_obfuscate_VgKtFeg? ) )
            {
                $_obfuscate_VcY7imjf = app::loadapp( "main", "station" )->api_getNameById( $_obfuscate_VgKtFeg? );
            }
            $_obfuscate_AlW8F6SaSV8O = "<input type=\"text\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_vZOcQA??.$_obfuscate_TervNcSylPE?.$_obfuscate_0sRnHy4?."to=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" value=\"".$_obfuscate_VcY7imjf."\" tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" ";
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_2FLoTKc2yw?? = " onclick=\"wf_selector('station', this, false)\"";
            }
            $_obfuscate_LQ8UKg?? = $_obfuscate_AlW8F6SaSV8O.$_obfuscate_2FLoTKc2yw??." /><input type=\"hidden\" value=\"".$_obfuscate_VgKtFeg?."\" id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\"/>";
            break;
        case "stations_sel" :
            if ( !empty( $_obfuscate_VgKtFeg? ) )
            {
                $_obfuscate_VcY7imjf = app::loadapp( "main", "station" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg? ) );
                $_obfuscate_VcY7imjf = implode( ",", $_obfuscate_VcY7imjf );
            }
            $_obfuscate_AlW8F6SaSV8O = "<textarea ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_vZOcQA??.$_obfuscate_TervNcSylPE?.$_obfuscate_0sRnHy4?."tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" to=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" ";
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_2FLoTKc2yw?? = " onclick=\"wf_selector('station', this, true)\"";
            }
            $_obfuscate_LQ8UKg?? = $_obfuscate_AlW8F6SaSV8O.$_obfuscate_2FLoTKc2yw??." />".$_obfuscate_VcY7imjf."</textarea><input type=\"hidden\" value=\"".$_obfuscate_VgKtFeg?."\" id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\"/>";
            break;
        case "job_sel" :
            if ( !empty( $_obfuscate_VgKtFeg? ) )
            {
                $_obfuscate_VcY7imjf = app::loadapp( "main", "job" )->api_getNameById( $_obfuscate_VgKtFeg? );
            }
            $_obfuscate_AlW8F6SaSV8O = "<input type=\"text\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_vZOcQA??.$_obfuscate_TervNcSylPE?.$_obfuscate_0sRnHy4?."tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" to=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" value=\"".$_obfuscate_VcY7imjf."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" ";
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_2FLoTKc2yw?? = " onclick=\"wf_selector('job', this, false)\"";
            }
            $_obfuscate_LQ8UKg?? = $_obfuscate_AlW8F6SaSV8O.$_obfuscate_2FLoTKc2yw??." /><input type=\"hidden\" value=\"".$_obfuscate_VgKtFeg?."\" id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\"/>";
            break;
        case "jobs_sel" :
            if ( !empty( $_obfuscate_VgKtFeg? ) )
            {
                $_obfuscate_VcY7imjf = app::loadapp( "main", "job" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg? ) );
                $_obfuscate_VcY7imjf = implode( ",", $_obfuscate_VcY7imjf );
            }
            $_obfuscate_AlW8F6SaSV8O = "<textarea ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_vZOcQA??.$_obfuscate_TervNcSylPE?.$_obfuscate_0sRnHy4?."tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" to=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" ";
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_2FLoTKc2yw?? = " onclick=\"wf_selector('job', this, true)\"";
            }
            $_obfuscate_LQ8UKg?? = $_obfuscate_AlW8F6SaSV8O.$_obfuscate_2FLoTKc2yw??." />".$_obfuscate_VcY7imjf."</textarea><input type=\"hidden\" value=\"".$_obfuscate_VgKtFeg?."\" id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\"/>";
            break;
        case "user_sel" :
            if ( !empty( $_obfuscate_VgKtFeg? ) )
            {
                $_obfuscate_VcY7imjf = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_VgKtFeg? );
            }
            $_obfuscate_AlW8F6SaSV8O = "<input type=\"text\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_vZOcQA??.$_obfuscate_TervNcSylPE?.$_obfuscate_0sRnHy4?."tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" to=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" value=\"".$_obfuscate_VcY7imjf."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" ";
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_2FLoTKc2yw?? = " onclick=\"wf_selector('user', this, false)\" ";
            }
            $_obfuscate_LQ8UKg?? = $_obfuscate_AlW8F6SaSV8O.$_obfuscate_2FLoTKc2yw??." /><input type=\"hidden\" dataType=\"user_sel\" value=\"".$_obfuscate_VgKtFeg?."\" id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\"/>";
            break;
        case "users_sel" :
            if ( !empty( $_obfuscate_VgKtFeg? ) )
            {
                $_obfuscate_GCfDSanL49WUEA?? = app::loadapp( "main", "user" )->api_getUserTruenameByUid( explode( ",", $_obfuscate_VgKtFeg? ) );
                if ( is_array( $_obfuscate_GCfDSanL49WUEA?? ) )
                {
                    $_obfuscate_VcY7imjf = implode( ",", $_obfuscate_GCfDSanL49WUEA?? );
                }
            }
            $_obfuscate_AlW8F6SaSV8O = "<textarea ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_vZOcQA??.$_obfuscate_TervNcSylPE?.$_obfuscate_0sRnHy4?."tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" to=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" ";
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_2FLoTKc2yw?? = " onclick=\"wf_selector('user', this, true)\" ";
            }
            $_obfuscate_LQ8UKg?? = $_obfuscate_AlW8F6SaSV8O.$_obfuscate_2FLoTKc2yw??." />".$_obfuscate_VcY7imjf."</textarea><input type=\"hidden\" value=\"".$_obfuscate_VgKtFeg?."\" id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\"/>";
            break;
        case "photo_upload" :
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_r9VC_isDSQ?? = "onclick=\"uploadEditPhoto(".$_obfuscate_p5ZWxr4?['width'].",".$_obfuscate_p5ZWxr4?['height'].", this);\"";
            }
            $_obfuscate_vZOcQA?? = "";
            if ( $_obfuscate_6mlyHg??['must'] == 1 )
            {
                $_obfuscate_vZOcQA?? = "must=\"true\" mustfor=\"choice\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\"";
            }
            $_obfuscate_LQ8UKg?? = "<img src=\"".$_obfuscate_VgKtFeg?."\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" tourl=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" ".$_obfuscate_r9VC_isDSQ??." class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_0sRnHy4?." />";
            $_obfuscate_LQ8UKg?? .= "<input type=\"hidden\" ".$_obfuscate_vZOcQA??." id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" name=\"wf_fieldpic_".$_obfuscate_60GquoKMPw??['id']."\" value=\"".$_obfuscate_VgKtFeg?."\" />";
            break;
        case "kehuEgressRecord" :
            $_obfuscate_AlW8F6SaSV8O = "<div id='ID_RECORD'".( "ext:qtip='[".$_obfuscate_60GquoKMPw??['name']."]' " ).( "otype='".$_obfuscate_60GquoKMPw??['otype']."' " ).( "class='wf_field".$_obfuscate_F43xrelD_9A?."' " ).$_obfuscate_vZOcQA??.$_obfuscate_TervNcSylPE?.$_obfuscate_0sRnHy4?.( "tabindex='".$_obfuscate_60GquoKMPw??['order']."' " ).( "to='wf_field_".$_obfuscate_60GquoKMPw??['id']."' " ).( "oname='".$_obfuscate_60GquoKMPw??['name']."' " );
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_2FLoTKc2yw?? = " onclick='wf_kehuEgressRecord(this)' ";
            }
            $_obfuscate_VgKtFeg? = str_replace( "&lt;", "<", $_obfuscate_VgKtFeg? );
            $_obfuscate_VgKtFeg? = str_replace( "&gt;", ">", $_obfuscate_VgKtFeg? );
            $_obfuscate_VgKtFeg? = str_replace( "&amp;", "&", $_obfuscate_VgKtFeg? );
            $_obfuscate_VgKtFeg? = str_replace( "&quot;", "'", $_obfuscate_VgKtFeg? );
            $_obfuscate_LQ8UKg?? = $_obfuscate_AlW8F6SaSV8O.$_obfuscate_2FLoTKc2yw??." />".$_obfuscate_VgKtFeg?."</div><input type=\"hidden\" value=\"".$_obfuscate_VgKtFeg?."\" id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\"/>";
        }
        return $_obfuscate_LQ8UKg??;
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
            if ( !empty( $this->uFlowId ) )
            {
                $_obfuscate_wzlwEupWLkw? = $CNOA_DB->db_getfield( "puFlowId", "wf_u_step_child_flow", "WHERE `uFlowId`=".$this->uFlowId." AND `flowId`={$this->flowId}" );
            }
            else
            {
                $_obfuscate_wzlwEupWLkw? = getpar( $_POST, "puFlowId" );
            }
            if ( empty( $_obfuscate_wzlwEupWLkw? ) )
            {
                return $_obfuscate_LQ8UKg??.$_obfuscate_VgKtFeg?."</span>";
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
                return $_obfuscate_LQ8UKg??.$_obfuscate_VgKtFeg?."</span>";
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
            if ( $_obfuscate_hTew0boWJESy['status'] == 1 )
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
        $_obfuscate_LQ8UKg?? .= "<a href=\"javascript:void(0);\" mark=\"flowview\" data='".json_encode( $_obfuscate_0CYzhQ?? )."' ext:qtip=\"??£¤???¨¨¡¥|????????1\" >".$_obfuscate_VgKtFeg?."</span></a> ";
        return $_obfuscate_LQ8UKg??;
    }

    private function _format_datasource( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
        if ( $_obfuscate_6mlyHg??['write'] )
        {
            $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
            $_obfuscate_EU3Hg?? = $_obfuscate_p5ZWxr4?['maps'];
            if ( is_null( $_obfuscate_EU3Hg??[0] ) )
            {
                foreach ( $this->flowFields as $_obfuscate_YIq2A8c? )
                {
                    foreach ( $_obfuscate_EU3Hg?? as $_obfuscate_Vwty => $_obfuscate_Ag?? )
                    {
                        if ( $_obfuscate_Ag?? == $_obfuscate_YIq2A8c?['name'] )
                        {
                            $_obfuscate_EU3Hg??[$_obfuscate_Vwty] = "wf_field_".$_obfuscate_YIq2A8c?['id'];
                        }
                    }
                }
            }
            else
            {
                foreach ( $this->flowFields as $_obfuscate_YIq2A8c? )
                {
                    foreach ( $_obfuscate_EU3Hg?? as $_obfuscate_Vwty => $_obfuscate_Ag?? )
                    {
                        if ( $_obfuscate_Ag??['des'] == $_obfuscate_YIq2A8c?['name'] )
                        {
                            $_obfuscate_EU3Hg??[$_obfuscate_Vwty]['des'] = "wf_field_".$_obfuscate_YIq2A8c?['id'];
                        }
                    }
                }
            }
            $_obfuscate_LQ8UKg?? = "<input type=\"button\" id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" fieldId=\"".$_obfuscate_60GquoKMPw??['id']."\" datasource=\"".$_obfuscate_p5ZWxr4?['datasource']."\" sqldetail=\"".$_obfuscate_p5ZWxr4?['sqlDetail']."\" maps='".json_encode( $_obfuscate_EU3Hg?? )."' tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" value=\"".$_obfuscate_60GquoKMPw??['name']."\" />";
            $_obfuscate_LQ8UKg?? .= "<input type=\"hidden\" name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" value=\"".$_obfuscate_VgKtFeg?."\" />";
            return $_obfuscate_LQ8UKg??;
        }
        $_obfuscate_LQ8UKg?? = "<input type=\"button\" value=\"".$_obfuscate_60GquoKMPw??['name']."\" style=\"visibility:hidden\" />";
        return $_obfuscate_LQ8UKg??;
    }

    private function _format_attach( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "" )
    {
        if ( $_obfuscate_6mlyHg??['must'] == 1 )
        {
            $_obfuscate_F43xrelD_9A? = " wf_field_must";
            $_obfuscate_vZOcQA?? = "must=\"true\" ";
        }
        else if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_F43xrelD_9A? = " wf_field_write";
        }
        else
        {
            $_obfuscate_F43xrelD_9A? = " wf_field_read";
        }
        if ( $_obfuscate_6mlyHg??['write'] )
        {
            $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
            $_obfuscate_LQ8UKg?? = "<input type=\"button\" ".$_obfuscate_vZOcQA??."data-attach=\"wf_attach_".$_obfuscate_60GquoKMPw??['id']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" onclick=\"uploadEditAttach(".$_obfuscate_60GquoKMPw??['id'].",this)\" value=\"".$_obfuscate_60GquoKMPw??['name']."\" />";
            return $_obfuscate_LQ8UKg??;
        }
        $_obfuscate_LQ8UKg?? = "<input type=\"button\" value=\"".$_obfuscate_60GquoKMPw??['name']."\" style=\"visibility:hidden\" />";
        return $_obfuscate_LQ8UKg??;
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
                $_obfuscate_vILV9AXm = "border:1px solid #000;border-width:0 0 1px 0;";
            }
            else if ( $_obfuscate_6A??['border'] == 1 )
            {
                $_obfuscate_vILV9AXm = "border:1px solid #000;";
            }
            else if ( $_obfuscate_6A??['border'] == 2 )
            {
                $_obfuscate_vILV9AXm = "border:1px solid #000;border-width:0;";
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

    private function __formatMonth( $_obfuscate_e7PLR79F )
    {
        switch ( $_obfuscate_e7PLR79F )
        {
        case "100" :
            $_obfuscate_6RYLWQ?? = date( "m???", $GLOBALS['CNOA_TIMESTAMP'] );
            return $_obfuscate_6RYLWQ??;
        case "200" :
            $_obfuscate_6RYLWQ?? = date( "Ym", $GLOBALS['CNOA_TIMESTAMP'] );
            return $_obfuscate_6RYLWQ??;
        case "300" :
            $_obfuscate_6RYLWQ?? = date( "Y-m", $GLOBALS['CNOA_TIMESTAMP'] );
            return $_obfuscate_6RYLWQ??;
        case "400" :
            $_obfuscate_6RYLWQ?? = date( "Y?1¡äm???", $GLOBALS['CNOA_TIMESTAMP'] );
        }
        return $_obfuscate_6RYLWQ??;
    }

    private function __formatQuarter( $_obfuscate_e7PLR79F )
    {
        $_obfuscate_3etEdseqXg?? = floor( ( date( "n", $GLOBALS['CNOA_TIMESTAMP'] ) - 1 ) / 3 ) + 1;
        switch ( $_obfuscate_e7PLR79F )
        {
        case "100" :
            $_obfuscate_6RYLWQ?? = "Q".$_obfuscate_3etEdseqXg??;
            return $_obfuscate_6RYLWQ??;
        case "200" :
            $_obfuscate_6RYLWQ?? = "???".$_obfuscate_3etEdseqXg??."?-¡ê";
        }
        return $_obfuscate_6RYLWQ??;
    }

    private function _format_detailtable( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? )
    {
        global $CNOA_DB;
        $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_60GquoKMPw??['odata'] );
        if ( $this->draft )
        {
            $_obfuscate_YyTDO8lpRpm8 = $this->draftData['detail'][$_obfuscate_60GquoKMPw??['id']];
        }
        else if ( $this->againId && $_obfuscate_6mlyHg??['write'] == 0 )
        {
            $_obfuscate_YyTDO8lpRpm8 = array( );
        }
        else
        {
            $_obfuscate_YyTDO8lpRpm8 = $this->api_getWfFieldDetailData( $this->flowId, $this->uFlowId, $_obfuscate_60GquoKMPw??['id'], TRUE );
        }
        if ( empty( $_obfuscate_YyTDO8lpRpm8 ) )
        {
            $_obfuscate_YyTDO8lpRpm8 = $this->childDetailDB[$_obfuscate_60GquoKMPw??['id']];
            if ( empty( $_obfuscate_YyTDO8lpRpm8 ) )
            {
                $_obfuscate_YyTDO8lpRpm8 = array( );
            }
        }
        if ( $this->startStep )
        {
            $_obfuscate_D5cvgOQDiG = $CNOA_DB->db_select( array( "id", "name", "flowId" ), $this->t_set_field_detail, "WHERE `fid` = '".$_obfuscate_60GquoKMPw??['id']."' " );
        }
        else
        {
            $_obfuscate_D5cvgOQDiG = $this->flowDetailFields;
        }
        if ( !is_array( $_obfuscate_D5cvgOQDiG ) )
        {
            $_obfuscate_D5cvgOQDiG = array( );
        }
        $_obfuscate_YIq2A8c? = array( );
        foreach ( $_obfuscate_D5cvgOQDiG as $_obfuscate_5w?? => $_obfuscate_6A?? )
        {
            $_obfuscate_YIq2A8c?[$_obfuscate_6A??['name']] = $_obfuscate_6A??;
        }
        if ( $_obfuscate_6mlyHg??['edit'] == 1 )
        {
            $_obfuscate_XbWsmcH__Jid = "addRemove";
        }
        else
        {
            $_obfuscate_XbWsmcH__Jid = "";
        }
        $_obfuscate_GamAiZQGw?? = count( $_obfuscate_YyTDO8lpRpm8 ) <= 0 ? 1 : count( $_obfuscate_YyTDO8lpRpm8 );
        ( $this, $_obfuscate_60GquoKMPw??, $_obfuscate_p5ZWxr4?, $_obfuscate_6mlyHg??, $_obfuscate_YyTDO8lpRpm8, $_obfuscate_YIq2A8c?, $this->stepColFields );
        $_obfuscate_1EyMoKVr1BNxkRexSyMAs5LvKCjWKlOXcJHXg?? = new wfDetailTableFormaterForDeal( );
        $_obfuscate_LQ8UKg?? = $_obfuscate_1EyMoKVr1BNxkRexSyMAs5LvKCjWKlOXcJHXg??->parseTable( );
        $this->extHtml .= "<input type=\"hidden\" name=\"detailid_".$_obfuscate_60GquoKMPw??['id']."\" field=\"".$_obfuscate_60GquoKMPw??['id']."\" value=\"".$_obfuscate_VgKtFeg?."\" linenum=\"".$_obfuscate_GamAiZQGw??."\" record=\"true\" addremove=\"".$_obfuscate_XbWsmcH__Jid."\" detailid=\"".$_obfuscate_60GquoKMPw??['id']."\" />";
        return $_obfuscate_LQ8UKg??;
    }

    private function getSqlselectorData( $_obfuscate_0W8? )
    {
        global $CNOA_DB;
        $_obfuscate_3y0Y = "SELECT ss.*, db.dbname, db.host, db.dbType, db.chart, db.user, db.password \r\n\t\t\t\tFROM cnoa_abutment_sqlselector AS ss LEFT JOIN cnoa_abutment_database AS db ON ss.databaseId = db.id \r\n\t\t\t\tWHERE ss.id = ".$_obfuscate_0W8?;
        $_obfuscate_xs33Yt_k = $CNOA_DB->get_one( $_obfuscate_3y0Y );
        if ( empty( $_obfuscate_xs33Yt_k ) )
        {
            return array( );
        }
        if ( $_obfuscate_xs33Yt_k['selectorType'] == 0 )
        {
            $_obfuscate_sx8? = app::loadapp( "abutment", "abutment" )->connectDb( $_obfuscate_xs33Yt_k );
            if ( $_obfuscate_xs33Yt_k['selectorStyle'] == 1 )
            {
                $_obfuscate_ammigv8? = $_obfuscate_sx8?->query( $_obfuscate_xs33Yt_k['selectorSQL'] );
                $_obfuscate_6RYLWQ?? = $_obfuscate_sx8?->getResult( $_obfuscate_ammigv8? );
            }
            else if ( $_obfuscate_xs33Yt_k['selectorStyle'] == 2 )
            {
                $_obfuscate_6RYLWQ?? = $_obfuscate_sx8?->get_one( $_obfuscate_xs33Yt_k['selectorSQL'] );
            }
            $CNOA_DB->select_db( CNOA_DB_NAME );
        }
        else
        {
            $_obfuscate_xs33Yt_k['value'] = "value";
            $_obfuscate_xs33Yt_k['display'] = "display";
            if ( $_obfuscate_xs33Yt_k['selectorStyle'] == 1 )
            {
                $_obfuscate_ = explode( ",", $_obfuscate_xs33Yt_k['items'] );
                $_obfuscate_6RYLWQ?? = array( );
                foreach ( $_obfuscate_ as $_obfuscate_Vwty => $_obfuscate_VgKtFeg? )
                {
                    $_obfuscate_SeV31Q?? = explode( "|", $_obfuscate_VgKtFeg? );
                    $_obfuscate_6RYLWQ??[$_obfuscate_Vwty]['value'] = $_obfuscate_SeV31Q??[0];
                    $_obfuscate_6RYLWQ??[$_obfuscate_Vwty]['display'] = $_obfuscate_SeV31Q??[1];
                }
            }
            else
            {
                $_obfuscate_ = explode( ",", $_obfuscate_xs33Yt_k['items'] );
                $_obfuscate_6RYLWQ?? = array( );
                foreach ( $_obfuscate_ as $_obfuscate_Vwty => $_obfuscate_VgKtFeg? )
                {
                    $_obfuscate_SeV31Q?? = explode( "|", $_obfuscate_VgKtFeg? );
                    $_obfuscate_6RYLWQ??['value'] = $_obfuscate_SeV31Q??[0];
                    $_obfuscate_6RYLWQ??['display'] = $_obfuscate_SeV31Q??[1];
                    break;
                }
            }
        }
        return array(
            $_obfuscate_xs33Yt_k,
            $_obfuscate_6RYLWQ??
        );
    }

    private function formatSqlselector1( &$_obfuscate_60GquoKMPw??, &$_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg?, &$_obfuscate_6RYLWQ??, &$_obfuscate_p5ZWxr4? )
    {
        $_obfuscate_0sRnHy4? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "style=\"".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1]."\" ";
        $_obfuscate_vZOcQA?? = "";
        if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_TervNcSylPE? = "";
            $_obfuscate_F43xrelD_9A? = " wf_field_write";
            if ( $_obfuscate_6mlyHg??['must'] == 1 )
            {
                $_obfuscate_F43xrelD_9A? = " wf_field_must";
                $_obfuscate_vZOcQA?? = "must=\"true\"";
            }
            $_obfuscate_LQ8UKg?? = "<select ".$_obfuscate_vZOcQA??." ".$_obfuscate_TervNcSylPE?." id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" field=\"".$_obfuscate_60GquoKMPw??['id']."\"oname=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" ".$_obfuscate_0sRnHy4?."tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" >";
            $_obfuscate_LQ8UKg?? .= "<option value=\"\">&#160;</option>";
            foreach ( $_obfuscate_6RYLWQ??[1] as $_obfuscate_Ilme )
            {
                $_obfuscate_LQ8UKg?? .= "<option value=\"".$_obfuscate_Ilme[$_obfuscate_6RYLWQ??[0]['value']]."\" >".$_obfuscate_Ilme[$_obfuscate_6RYLWQ??[0]['display']]."</option>";
            }
            $_obfuscate_LQ8UKg?? .= "</select>";
            return $_obfuscate_LQ8UKg??;
        }
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
        $_obfuscate_LQ8UKg?? = "<input type=\"text\" ".$_obfuscate_vZOcQA??." id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."<br>?????¡ä?????1???<span class='cnoa_color_red'>".$_obfuscate_VgKtFeg?."</span>\"otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" field=\"".$_obfuscate_60GquoKMPw??['id']."\"oname=\"".$_obfuscate_60GquoKMPw??['name']."\" class=\"wf_field wf_field_read\" value=\"".$_obfuscate_VgKtFeg?."\" ".$_obfuscate_0sRnHy4?."readonly=\"readonly\" style=\"border:none;\" tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" />";
        return $_obfuscate_LQ8UKg??;
    }

    private function formatSqlselector2( &$_obfuscate_60GquoKMPw??, &$_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg?, &$_obfuscate_6RYLWQ??, &$_obfuscate_p5ZWxr4? )
    {
        $_obfuscate_0sRnHy4? = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "style=\"".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1]."\" ";
        $_obfuscate_vZOcQA?? = "";
        $_obfuscate_j574IU5Dhw?? = $_obfuscate_6RYLWQ??[1][$_obfuscate_6RYLWQ??[0]['display']];
        $_obfuscate_VgKtFeg? = $_obfuscate_6RYLWQ??[1][$_obfuscate_6RYLWQ??[0]['value']];
        $_obfuscate_LQ8UKg?? = "<span ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."<br>?????¡ä?????1???<span class='cnoa_color_red'>".$_obfuscate_VgKtFeg?."</span>\"class=\"wf_field wf_field_read\" ".$_obfuscate_0sRnHy4?."style=\"border:none;\" >".$_obfuscate_j574IU5Dhw??."</span>";
        $_obfuscate_LQ8UKg?? .= "<input type=\"hidden\" ".$_obfuscate_vZOcQA??." id=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" name=\"wf_field_".$_obfuscate_60GquoKMPw??['id']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" field=\"".$_obfuscate_60GquoKMPw??['id']."\"oname=\"".$_obfuscate_60GquoKMPw??['name']."\" class=\"wf_field wf_field_read\" value=\"".$_obfuscate_VgKtFeg?."\" ".$_obfuscate_0sRnHy4?."readonly=\"readonly\" style=\"border:none;\" tabindex=\"".$_obfuscate_60GquoKMPw??['order']."\" />";
        return $_obfuscate_LQ8UKg??;
    }

    private function _format_abutment( &$element, &$rule, $value )
    {
        $odata = $this->__getOdata( $element['odata'] );
        $mapPath = CNOA_PATH_FILE.( "/common/wf/sqlselector/".$element['id'].".php" );
        if ( empty( $this->uFlowId ) || !file_exists( $mapPath ) )
        {
            @mkdir( CNOA_PATH_FILE."/common/wf/sqlselector" );
            $data = $this->getSqlselectorData( $odata['dataType'] );
            $content = "<?php\r\nreturn ".var_export( $data, TRUE ).";\r\n?>";
            file_put_contents( $mapPath, $content );
        }
        else
        {
            $data = include_once( $mapPath );
        }
        if ( $data[0]['selectorStyle'] == 1 )
        {
            $item = $this->formatSqlselector1( $element, $rule, $value, $data, $odata );
            return $item;
        }
        if ( $data[0]['selectorStyle'] == 2 )
        {
            $item = $this->formatSqlselector2( $element, $rule, $value, $data, $odata );
        }
        return $item;
    }

}

class wfDetailTableFormaterForDeal extends wfCommon
{

    private $wfFFFD = NULL;
    private $tableEl = NULL;
    private $odata = NULL;
    private $tableRule = NULL;
    private $tableData = NULL;
    private $fieldList = NULL;
    private $ruleList = NULL;
    private $nowLine = NULL;
    private $tdNum = 0;

    public function __construct( $wfFFFD, $tableEl, $odata, $tableRule, $tableData, $fieldList, $ruleList )
    {
        $this->wfFFFD = $wfFFFD;
        $this->tableEl = $tableEl;
        $this->odata = $odata;
        $this->tableRule = $tableRule;
        $this->tableData = $tableData;
        $this->fieldList = $fieldList;
        $this->ruleList = $ruleList;
        $this->detailMax = include_once( CNOA_PATH_FILE."/common/wf/detailmaxcache/".$wfFFFD->uFlowId.".php" );
    }

    public function __destruct( )
    {
    }

    public function parseTable( )
    {
        $_obfuscate_LQ8UKg?? = "";
        if ( is_array( $this->tableData ) && 0 < count( $this->tableData ) )
        {
            $_obfuscate_7w?? = 1;
            for ( ; do
 {
 $_obfuscate_7w?? <= count( $this->tableData ); ++$_obfuscate_7w??, )
                {
                    $this->nowLine = $_obfuscate_7w??;
                    $_obfuscate_LQ8UKg?? .= $this->parseTr( $_obfuscate_7w??, $this->odata['items'], $this->tableData[$_obfuscate_7w??] );
                    break;
                }
            } while ( 1 );
        }
        else if ( !empty( $this->odata['rownumber'] ) )
        {
            $_obfuscate_7w?? = 1;
            for ( ; do
 {
 $_obfuscate_7w?? <= $this->odata['rownumber']; ++$_obfuscate_7w??, )
                {
                    $this->nowLine = $_obfuscate_7w??;
                    $_obfuscate_LQ8UKg?? .= $this->parseTr( $_obfuscate_7w??, $this->odata['items'], "" );
                    break;
                }
            } while ( 1 );
        }
        else
        {
            $this->nowLine = 1;
            $_obfuscate_LQ8UKg?? = $this->parseTr( 1, $this->odata['items'], array( ) );
        }
        return $_obfuscate_LQ8UKg??;
    }

    private function parseTr( $_obfuscate_PWqEdpnd, $_obfuscate_, $_obfuscate_hv2rm1p )
    {
        $this->tdNum = 0;
        $_obfuscate_k6U? = "<tr class=\"detail-line\" style=\"height:".$this->odata['height']."px;\" rownum=\"".$_obfuscate_PWqEdpnd."\" detail=\"".$this->tableEl['id']."\">";
        if ( !is_array( $this->odata['items'] ) )
        {
            $this->odata['items'] = array( );
        }
        foreach ( $this->odata['items'] as $_obfuscate_5w?? => $_obfuscate_6A?? )
        {
            $_obfuscate_k6U? .= $this->parseTd( $_obfuscate_6A??, $this->ruleList[$this->fieldList[$_obfuscate_6A??['name']]['id']], $_obfuscate_hv2rm1p[$this->fieldList[$_obfuscate_6A??['name']]['id']], $_obfuscate_5w?? );
            if ( !( $_obfuscate_6A??['odata']['dataType'] == "int" ) || !( $_obfuscate_6A??['odata']['dataType'] == "float" ) && !( $this->nowLine == 1 ) )
            {
                $_obfuscate_gfGsQGKrGg?? = $this->fieldList[$_obfuscate_6A??['name']]['id'];
                $this->wfFFFD['extHtml'] .= "<input type=\"hidden\" detailid=\"".$this->tableEl['id']."\" columnsum=\"true\" columnid=\"".$_obfuscate_gfGsQGKrGg??."\" id=\"wf_detail_column_".$_obfuscate_gfGsQGKrGg??."\"/>";
            }
        }
        if ( $this->tableRule['write'] == 1 )
        {
            $_obfuscate_S3mD = count( $this->tableData );
            $_obfuscate_iaimBq6DVyXvuDch = $this->odata['bindFunction'];
            if ( array_key_exists( $_obfuscate_iaimBq6DVyXvuDch, $this->bindFunctionList ) )
            {
                $_obfuscate_QbLi3SeqTlw? = preg_match( "/^admarticles.*/", $_obfuscate_iaimBq6DVyXvuDch ) ? substr( $_obfuscate_iaimBq6DVyXvuDch, 0, -1 ) : $_obfuscate_iaimBq6DVyXvuDch == "sqldetail" ? "sqldetail" : "jxcGoods";
                if ( in_array( $_obfuscate_iaimBq6DVyXvuDch, array( "admarticlesd", "admarticlesb" ) ) )
                {
                    if ( $_obfuscate_PWqEdpnd == 1 )
                    {
                        $_obfuscate_k6U? .= "<td align=\"center\" valign=\"middle\" style=\"border:none;\" width=\"20\">";
                        $_obfuscate_k6U? .= "<img class=\"wf_row_jia\" queryadd=\"true\" src=\"resources/images/cnoa/wf-dt-jia.gif\" ext:qtip=\"\" style=\"display:none;\"/>";
                        $_obfuscate_k6U? .= "</td>";
                    }
                    else
                    {
                        $_obfuscate_k6U? .= "<td align=\"center\" valign=\"middle\" style=\"border:none;\" width=\"20\">";
                        $_obfuscate_k6U? .= "<img class=\"wf_row_jian\" src=\"resources/images/cnoa/wf-dt-jian.gif\" ext:qtip=\"???¨¦?¡è???¨¨??\">";
                        $_obfuscate_k6U? .= "</td>";
                    }
                    if ( $_obfuscate_S3mD <= 1 || 1 < $_obfuscate_S3mD && $_obfuscate_PWqEdpnd == $_obfuscate_S3mD )
                    {
                        $_obfuscate_k6U? .= "<tr>";
                        $_obfuscate_k6U? .= "<td align=\"right\" valign=\"top\" colspan=\"".count( $this->odata['items'] )."\">";
                        $_obfuscate_k6U? .= "<img detail=\"".$this->tableEl['id']."\" class=\"wf_list_query\" query=\"true\" querykey=\"".$_obfuscate_QbLi3SeqTlw?."\" bindfunc=\"".$_obfuscate_iaimBq6DVyXvuDch."\" src=\"resources/images/cnoa/wf-list-query.gif\" ext:qtip=\"??£¤¨¨¡¥¡é\" />";
                        $_obfuscate_k6U? .= "</td>";
                        $_obfuscate_k6U? .= "</tr>";
                    }
                }
                else if ( in_array( $_obfuscate_iaimBq6DVyXvuDch, array( "admarticlesc", "jxcRuku", "jxcChuku", "sqldetail" ) ) )
                {
                    if ( $_obfuscate_PWqEdpnd == 1 )
                    {
                        $_obfuscate_k6U? .= "<td align=\"center\" valign=\"middle\" style=\"border:none;\" width=\"20\">";
                        $_obfuscate_k6U? .= "<img class=\"wf_row_jia\" queryadd=\"true\" src=\"resources/images/cnoa/wf-dt-jia.gif\" ext:qtip=\"\" style=\"display:none;\" />";
                        $_obfuscate_k6U? .= "</td>";
                    }
                    else
                    {
                        $_obfuscate_k6U? .= "<td align=\"center\" valign=\"middle\" style=\"border:none;\" width=\"20\">";
                        $_obfuscate_k6U? .= "<img class=\"wf_row_jian\" src=\"resources/images/cnoa/wf-dt-jian.gif\" ext:qtip=\"???¨¦?¡è???¨¨??\">";
                        $_obfuscate_k6U? .= "</td>";
                    }
                    if ( $_obfuscate_S3mD <= 1 || 1 < $_obfuscate_S3mD && $_obfuscate_PWqEdpnd == $_obfuscate_S3mD )
                    {
                        $_obfuscate_k6U? .= "<tr>";
                        $_obfuscate_k6U? .= "<td align=\"right\" valign=\"top\" colspan=\"".count( $this->odata['items'] )."\">";
                        $_obfuscate_k6U? .= "<img detail=\"".$this->tableEl['id']."\" class=\"wf_list_query\" query=\"true\"  querykey=\"".$_obfuscate_QbLi3SeqTlw?."\" bindfunc=\"".$_obfuscate_iaimBq6DVyXvuDch."\" src=\"resources/images/cnoa/wf-list-query.gif\" ext:qtip=\"??£¤¨¨¡¥¡é\" />";
                        $_obfuscate_k6U? .= "</td>";
                        $_obfuscate_k6U? .= "</tr>";
                    }
                }
            }
            else
            {
                $_obfuscate_k6U? .= "<td align=\"center\" valign=\"middle\" style=\"border:none;\" width=\"20\">";
                if ( $_obfuscate_PWqEdpnd == 1 )
                {
                    $_obfuscate_k6U? .= "<img class=\"wf_row_jia\" src=\"resources/images/cnoa/wf-dt-jia.gif\" ext:qtip=\"?¡¤???????¨¨??\" />";
                }
                else
                {
                    $_obfuscate_k6U? .= "<img class=\"wf_row_jian\" src=\"resources/images/cnoa/wf-dt-jian.gif\" ext:qtip=\"???¨¦?¡è???¨¨??\">";
                }
                $_obfuscate_k6U? .= "</td>";
            }
        }
        $_obfuscate_k6U? .= "</tr>";
        return $_obfuscate_k6U?;
    }

    private function parseTd( $element, $rule, $value = "", $k )
    {
        $tdattr = $this->odata['tdattrs'][$this->tdNum];
        $td = "<td align='".$tdattr['align']."' ";
        $td .= "valign='".$tdattr['valign']."' ";
        $td .= "height='".$this->odata['height']."' ";
        $td .= "width='".$tdattr['width']."' ";
        $td .= "class='".str_replace( "selectTdClass", "", $tdattr['class'] )."' ";
        $td .= "style='word-break: break-all;".$tdattr['style']."' ";
        $td .= ">";
        $elReadOnly = $this->isBindElReadOnly( $element );
        eval( "\$input = \$this->_format_".$element['odata']['type']."(\$element, \$rule, \$value, \$elReadOnly);" );
        $td .= str_replace( "style=\"", "style=\"text-align:".$element['align'], $input );
        if ( array_key_exists( $this->odata['bindFunction'], $this->bindFunctionList ) && $k == 0 )
        {
            if ( empty( $this->tableData[$this->nowLine]['bindid'] ) )
            {
                $detailbidvalue = "";
            }
            else
            {
                $detailbidvalue = "value=\"".$this->tableData[$this->nowLine]['bindid']."\"";
            }
            $inputName = "name=\"wf_detailbid_".$this->nowLine."_".$this->tableEl['id']."\" ";
            $inputId = "id=\"wf_detailbid_".$this->nowLine."_".$this->tableEl['id']."\" ";
            $td .= "<input ".$inputName." ".$inputId." detailbindid=\"true\" type=\"hidden\" ".$detailbidvalue." />";
        }
        $td .= "</td>";
        $this->tdNum++;
        return $td;
    }

    private function isBindElReadOnly( $_obfuscate_60GquoKMPw?? )
    {
        $_obfuscate_ZSs? = FALSE;
        switch ( $this->tableEl['bindfunction'] )
        {
        case "admarticlesc" :
            $_obfuscate_ZSs? = in_array( $_obfuscate_60GquoKMPw??['odata']['bindField'], array( "lib", "sort", "name", "number", "guige", "danwei" ) );
        }
        return $_obfuscate_ZSs?;
    }

    private function _format_textfield( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "", $_obfuscate_zdFnXMEXKAQUUA?? = FALSE )
    {
        $_obfuscate_p5ZWxr4? = $_obfuscate_60GquoKMPw??['odata'];
        $_obfuscate_ncdC0pM? = $_obfuscate_p5ZWxr4?['width'];
        $_obfuscate_gfGsQGKrGg?? = $this->fieldList[$_obfuscate_60GquoKMPw??['name']]['id'];
        if ( $_obfuscate_6mlyHg??['hide'] )
        {
            $_obfuscate_LQ8UKg?? = $this->__getFillDiv( $_obfuscate_p5ZWxr4?['width'] );
            return $_obfuscate_LQ8UKg??;
        }
        if ( $_obfuscate_VgKtFeg? != "" )
        {
            $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_VgKtFeg? );
        }
        else
        {
            $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_p5ZWxr4?['dvalue'] );
        }
        $_obfuscate_3gn_eQ?? = "name=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" ";
        $_obfuscate_vZOcQA?? = "";
        if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_TervNcSylPE? = "";
            $_obfuscate_F43xrelD_9A? = " wf_field_write";
            $_obfuscate_4Bjbo6tz = $_obfuscate_p5ZWxr4?['dataType'] == "str" ? "" : "<br />??????¨¨|??¡À????".$this->autoFormat[$_obfuscate_p5ZWxr4?['dataType']][$_obfuscate_p5ZWxr4?['dataFormat']][0];
            $_obfuscate_4Bjbo6tz .= $_obfuscate_p5ZWxr4?['dataType'] == "str" ? "" : "<br />??????????|????".$this->autoFormat[$_obfuscate_p5ZWxr4?['dataType']][$_obfuscate_p5ZWxr4?['dataFormat']][1];
            if ( $_obfuscate_6mlyHg??['must'] == 1 )
            {
                $_obfuscate_F43xrelD_9A? = " wf_field_must";
                $_obfuscate_vZOcQA?? = " must=\"true\" ";
            }
        }
        else
        {
            $_obfuscate_TervNcSylPE? = "readonly=\"readonly\"";
            $_obfuscate_F43xrelD_9A? = " wf_field_read";
        }
        $_obfuscate_0W8? = "id=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" ";
        $_obfuscate_0sRnHy4? = $this->wfFFFD->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "style=\"".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1].";width:".$_obfuscate_ncdC0pM?."px;\" ";
        if ( !empty( $_obfuscate_p5ZWxr4?['dvalue'] ) )
        {
            $_obfuscate_XoQj4PaA = " dvalue=\"".$_obfuscate_p5ZWxr4?['dvalue']."\" ";
        }
        if ( $_obfuscate_zdFnXMEXKAQUUA?? )
        {
            $_obfuscate_TervNcSylPE? = "readonly=\"readonly\"";
            $_obfuscate_F43xrelD_9A? = " wf_field_read";
        }
        $_obfuscate_LQ8UKg?? = "<input type=\"text\" ".$_obfuscate_vZOcQA??.$_obfuscate_0W8?.$_obfuscate_3gn_eQ??.$_obfuscate_XoQj4PaA.( $_obfuscate_p5ZWxr4?['dataType'] == "str" ? "" : "autoformat=\"".$this->autoFormatPHP[$_obfuscate_p5ZWxr4?['dataType']][$_obfuscate_p5ZWxr4?['dataFormat']]."\" " )."ext:qtip=\"".$_obfuscate_60GquoKMPw??['name'].$_obfuscate_4Bjbo6tz."\"otype=\"".$_obfuscate_p5ZWxr4?['type']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" field=\"".$_obfuscate_gfGsQGKrGg??."\"class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" value=\"".$_obfuscate_VgKtFeg?."\" isnum=\"".( $this->wfFFFD->__isNum( $_obfuscate_p5ZWxr4?['dataType'] ) ? "true" : "false" )."\" ".$this->__getBindFieldStr( $_obfuscate_p5ZWxr4?['bindField'] ).$this->__getBindMaxNumStr( $_obfuscate_gfGsQGKrGg??, $this->nowLine ).$_obfuscate_0sRnHy4?.$_obfuscate_TervNcSylPE?." />";
        return $_obfuscate_LQ8UKg??;
    }

    private function _format_textarea( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "", $_obfuscate_zdFnXMEXKAQUUA?? = FALSE )
    {
        $_obfuscate_p5ZWxr4? = $_obfuscate_60GquoKMPw??['odata'];
        $_obfuscate_ncdC0pM? = $_obfuscate_p5ZWxr4?['width'];
        $_obfuscate_3FCLQL2p = $this->odata['height'];
        $_obfuscate_gfGsQGKrGg?? = $this->fieldList[$_obfuscate_60GquoKMPw??['name']]['id'];
        if ( !empty( $_obfuscate_VgKtFeg? ) )
        {
            $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_VgKtFeg? );
        }
        else
        {
            $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_p5ZWxr4?['dvalue'] );
        }
        $_obfuscate_vZOcQA?? = "";
        if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_TervNcSylPE? = "";
            $_obfuscate_F43xrelD_9A? = " wf_field_write";
            $_obfuscate_3gn_eQ?? = "name=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" ";
            if ( $_obfuscate_6mlyHg??['must'] == 1 )
            {
                $_obfuscate_F43xrelD_9A? = " wf_field_must";
                $_obfuscate_vZOcQA?? = "must=\"true\"";
            }
        }
        else
        {
            $_obfuscate_TervNcSylPE? = "readonly=\"readonly\"";
            $_obfuscate_F43xrelD_9A? = " wf_field_read";
            $_obfuscate_3gn_eQ?? = "";
        }
        $_obfuscate_0W8? = "id=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" ";
        if ( !empty( $_obfuscate_p5ZWxr4?['dvalue'] ) )
        {
            $_obfuscate_XoQj4PaA = " dvalue=\"".$_obfuscate_p5ZWxr4?['dvalue']."\" ";
        }
        $_obfuscate_0sRnHy4? = $this->wfFFFD->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "style=\"".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1].";width:".$_obfuscate_ncdC0pM?."px; height:".$_obfuscate_3FCLQL2p."px;\" ";
        if ( $_obfuscate_zdFnXMEXKAQUUA?? )
        {
            $_obfuscate_TervNcSylPE? = "readonly=\"readonly\"";
            $_obfuscate_F43xrelD_9A? = " wf_field_read";
        }
        $_obfuscate_LQ8UKg?? = "<textarea ".$_obfuscate_vZOcQA??." ".$_obfuscate_0W8?.$_obfuscate_3gn_eQ??.$_obfuscate_XoQj4PaA."ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_p5ZWxr4?['type']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" ".$this->__getBindFieldStr( $_obfuscate_p5ZWxr4?['bindField'] ).$this->__getBindMaxNumStr( $_obfuscate_gfGsQGKrGg??, $this->nowLine ).$_obfuscate_0sRnHy4?.$_obfuscate_TervNcSylPE?." >".$_obfuscate_VgKtFeg?."</textarea>";
        return $_obfuscate_LQ8UKg??;
    }

    private function _format_select( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "", $_obfuscate_zdFnXMEXKAQUUA?? = FALSE )
    {
        $_obfuscate_p5ZWxr4? = $_obfuscate_60GquoKMPw??['odata'];
        $_obfuscate_ncdC0pM? = $_obfuscate_p5ZWxr4?['width'];
        $_obfuscate_3FCLQL2p = $this->odata['height'];
        $_obfuscate_gfGsQGKrGg?? = $this->fieldList[$_obfuscate_60GquoKMPw??['name']]['id'];
        if ( !empty( $_obfuscate_VgKtFeg? ) )
        {
            $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_VgKtFeg? );
        }
        else
        {
            $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_p5ZWxr4?['dvalue'] );
        }
        $_obfuscate_0sRnHy4? = $this->wfFFFD->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "style=\"".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1].";width:".$_obfuscate_ncdC0pM?."px;\" ";
        $_obfuscate_vZOcQA?? = "";
        if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_TervNcSylPE? = "";
            $_obfuscate_F43xrelD_9A? = " wf_field_write";
            if ( $_obfuscate_6mlyHg??['must'] == 1 )
            {
                $_obfuscate_F43xrelD_9A? = " wf_field_must";
                $_obfuscate_vZOcQA?? = " must=\"true\" ";
            }
            if ( $_obfuscate_zdFnXMEXKAQUUA?? )
            {
                $_obfuscate_TervNcSylPE? = "readonly=\"readonly\"";
                $_obfuscate_F43xrelD_9A? = " wf_field_read";
            }
            $_obfuscate_LQ8UKg?? = "<select ".$_obfuscate_vZOcQA??.$_obfuscate_TervNcSylPE?.$_obfuscate_0sRnHy4?."id=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" name=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" ".$this->__getBindFieldStr( $_obfuscate_p5ZWxr4?['bindField'] ).$this->__getBindMaxNumStr( $_obfuscate_gfGsQGKrGg??, $this->nowLine )."otype=\"".$_obfuscate_p5ZWxr4?['otype']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" >";
            if ( !is_array( $_obfuscate_p5ZWxr4?['item'] ) )
            {
                $_obfuscate_p5ZWxr4?['item'] = array( );
            }
            $_obfuscate_LQ8UKg?? .= "<option value=\"\">&#160;</option>";
            foreach ( $_obfuscate_p5ZWxr4?['item'] as $_obfuscate_Ilme )
            {
                if ( $_obfuscate_p5ZWxr4?['dataType'] == "int" )
                {
                    if ( !empty( $_obfuscate_VgKtFeg? ) )
                    {
                        $_obfuscate_nBiPkeZjKpA? = $_obfuscate_VgKtFeg? == $_obfuscate_Ilme['pvalue'] ? " selected " : "";
                    }
                    else
                    {
                        $_obfuscate_nBiPkeZjKpA? = $_obfuscate_Ilme['pradio'] == "on" ? "selected " : "";
                    }
                    $_obfuscate_b7QZNyc? = $_obfuscate_Ilme['pvalue'];
                }
                else
                {
                    if ( !empty( $_obfuscate_VgKtFeg? ) )
                    {
                        $_obfuscate_nBiPkeZjKpA? = $_obfuscate_VgKtFeg? == $_obfuscate_Ilme['pname'] ? " selected " : "";
                    }
                    else
                    {
                        $_obfuscate_nBiPkeZjKpA? = $_obfuscate_Ilme['pradio'] == "on" ? "selected " : "";
                    }
                    $_obfuscate_b7QZNyc? = $_obfuscate_Ilme['pname'];
                }
                $_obfuscate_XoQj4PaA = $_obfuscate_Ilme['pradio'] == "on" ? " dvalue=\"true\" " : "";
                $_obfuscate_LQ8UKg?? .= "<option value=\"".$_obfuscate_b7QZNyc?."\" ".$_obfuscate_nBiPkeZjKpA?.$_obfuscate_XoQj4PaA.">".$_obfuscate_Ilme['pname']."</option>";
            }
            $_obfuscate_LQ8UKg?? .= "</select>";
            return $_obfuscate_LQ8UKg??;
        }
        $_obfuscate_LQ8UKg?? = "<input type=\"text\" ".$_obfuscate_vZOcQA??." id=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_p5ZWxr4?['otype']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" name=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" class=\"wf_field wf_field_read\" value=\"".$_obfuscate_VgKtFeg?."\" ".$_obfuscate_0sRnHy4?.$this->__getBindFieldStr( $_obfuscate_p5ZWxr4?['bindField'] ).$this->__getBindMaxNumStr( $_obfuscate_gfGsQGKrGg??, $this->nowLine )."readonly=\"readonly\" />";
        return $_obfuscate_LQ8UKg??;
    }

    private function _format_radio( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "", $_obfuscate_zdFnXMEXKAQUUA?? = FALSE )
    {
        $_obfuscate_p5ZWxr4? = $_obfuscate_60GquoKMPw??['odata'];
        $_obfuscate_ncdC0pM? = $_obfuscate_p5ZWxr4?['width'];
        $_obfuscate_3FCLQL2p = $this->odata['height'];
        $_obfuscate_gfGsQGKrGg?? = $this->fieldList[$_obfuscate_60GquoKMPw??['name']]['id'];
        $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_VgKtFeg? );
        $_obfuscate_0sRnHy4? = $this->wfFFFD->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "style=\"padding:3px;display:block;".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1].";width:".$_obfuscate_ncdC0pM?."px;\" ";
        $_obfuscate_vZOcQA?? = "";
        if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_F43xrelD_9A? = " wf_field_write";
            if ( $_obfuscate_6mlyHg??['must'] == 1 )
            {
                $_obfuscate_F43xrelD_9A? = " wf_field_must";
                $_obfuscate_vZOcQA?? = "must=\"true\"";
            }
            if ( $_obfuscate_zdFnXMEXKAQUUA?? )
            {
                $_obfuscate_TervNcSylPE? = "readonly=\"readonly\"";
                $_obfuscate_F43xrelD_9A? = " wf_field_read";
            }
            $_obfuscate_LQ8UKg?? = "<span ".$this->__getBindFieldStr( $_obfuscate_p5ZWxr4?['bindField'] )." ".$this->__getBindMaxNumStr( $_obfuscate_gfGsQGKrGg??, $this->nowLine )." class=\"".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_0sRnHy4?.">";
            if ( !is_array( $_obfuscate_p5ZWxr4?['item'] ) )
            {
                $_obfuscate_p5ZWxr4?['item'] = array( );
            }
            $_obfuscate_0W8? = 0;
            foreach ( $_obfuscate_p5ZWxr4?['item'] as $_obfuscate_5w?? => $_obfuscate_Ilme )
            {
                $_obfuscate_VPuPMczbSQ?? = "";
                if ( !empty( $_obfuscate_VgKtFeg? ) )
                {
                    $_obfuscate_VPuPMczbSQ?? = $_obfuscate_VgKtFeg? == $_obfuscate_Ilme['pname'] ? "checked=\"checked\" " : "";
                }
                else
                {
                    $_obfuscate_VPuPMczbSQ?? = $_obfuscate_Ilme['pradio'] == "on" ? "checked=\"checked\" " : "";
                }
                $_obfuscate_XoQj4PaA = $_obfuscate_Ilme['pradio'] == "on" ? "dvalue=\"true\"" : "";
                ++$_obfuscate_0W8?;
                $_obfuscate_LQ8UKg?? .= "<label for=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."_".$_obfuscate_0W8?."\"><input type=\"radio\" name=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" id=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."_".$_obfuscate_0W8?."\" ".$_obfuscate_vZOcQA??." ".$_obfuscate_VPuPMczbSQ??." ".$_obfuscate_TervNcSylPE?." ".$_obfuscate_XoQj4PaA." oname=\"".$_obfuscate_60GquoKMPw??['name']."\" value=\"".$_obfuscate_Ilme['pname']."\" >".$_obfuscate_Ilme['pname']."</label>";
                $_obfuscate_LQ8UKg?? .= count( $_obfuscate_p5ZWxr4?['item'] ) == $_obfuscate_5w?? + 1 ? "" : "&nbsp;";
            }
            $_obfuscate_LQ8UKg?? .= "</span>";
            if ( $_obfuscate_6mlyHg??['must'] == 1 )
            {
                $this->extHtml .= "<input type=\"hidden\" must=\"true\" foroname=\"".$_obfuscate_60GquoKMPw??['name']."\" mustfor=\"radio\" musttarget=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\">";
                return $_obfuscate_LQ8UKg??;
            }
        }
        else
        {
            $_obfuscate_LQ8UKg?? = "<span ".$_obfuscate_0sRnHy4?.">";
            if ( !is_array( $_obfuscate_p5ZWxr4?['item'] ) )
            {
                $_obfuscate_p5ZWxr4?['item'] = array( );
            }
            foreach ( $_obfuscate_p5ZWxr4?['item'] as $_obfuscate_5w?? => $_obfuscate_Ilme )
            {
                if ( !empty( $_obfuscate_VgKtFeg? ) )
                {
                    $_obfuscate_VPuPMczbSQ?? = $_obfuscate_VgKtFeg? == $_obfuscate_Ilme['pname'] ? "radio" : "unradio";
                }
                else
                {
                    $_obfuscate_VPuPMczbSQ?? = $_obfuscate_60GquoKMPw??['dvalue'] == $_obfuscate_Ilme['pname'] ? "radio" : "unradio";
                }
                $_obfuscate_LQ8UKg?? .= "<image src=\"resources/images/cnoa/wf-".$_obfuscate_VPuPMczbSQ??.".gif\" />".$_obfuscate_Ilme['pname'];
                $_obfuscate_LQ8UKg?? .= count( $_obfuscate_p5ZWxr4?['item'] ) == $_obfuscate_5w?? + 1 ? "" : "&nbsp;";
            }
            $_obfuscate_LQ8UKg?? .= "</span>";
        }
        return $_obfuscate_LQ8UKg??;
    }

    private function getSqlselectorData( $_obfuscate_0W8? )
    {
        global $CNOA_DB;
        $_obfuscate_3y0Y = "SELECT ss.*, db.dbname, db.host, db.dbType, db.chart, db.user, db.password \r\n\t\t\t\tFROM cnoa_abutment_sqlselector AS ss LEFT JOIN cnoa_abutment_database AS db ON ss.databaseId = db.id \r\n\t\t\t\tWHERE ss.id = ".$_obfuscate_0W8?;
        $_obfuscate_xs33Yt_k = $CNOA_DB->get_one( $_obfuscate_3y0Y );
        if ( empty( $_obfuscate_xs33Yt_k ) )
        {
            return array( );
        }
        if ( $_obfuscate_xs33Yt_k['selectorType'] == 0 )
        {
            $_obfuscate_sx8? = app::loadapp( "abutment", "abutment" )->connectDb( $_obfuscate_xs33Yt_k );
            if ( $_obfuscate_xs33Yt_k['selectorStyle'] == 1 )
            {
                $_obfuscate_ammigv8? = $_obfuscate_sx8?->query( $_obfuscate_xs33Yt_k['selectorSQL'] );
                $_obfuscate_6RYLWQ?? = $_obfuscate_sx8?->getResult( $_obfuscate_ammigv8? );
            }
            else if ( $_obfuscate_xs33Yt_k['selectorStyle'] == 2 )
            {
                $_obfuscate_6RYLWQ?? = $_obfuscate_sx8?->get_one( $_obfuscate_xs33Yt_k['selectorSQL'] );
            }
            $CNOA_DB->select_db( CNOA_DB_NAME );
        }
        else
        {
            $_obfuscate_xs33Yt_k['value'] = "value";
            $_obfuscate_xs33Yt_k['display'] = "display";
            if ( $_obfuscate_xs33Yt_k['selectorStyle'] == 1 )
            {
                $_obfuscate_ = explode( ",", $_obfuscate_xs33Yt_k['items'] );
                $_obfuscate_6RYLWQ?? = array( );
                foreach ( $_obfuscate_ as $_obfuscate_Vwty => $_obfuscate_VgKtFeg? )
                {
                    $_obfuscate_SeV31Q?? = explode( "|", $_obfuscate_VgKtFeg? );
                    $_obfuscate_6RYLWQ??[$_obfuscate_Vwty]['value'] = $_obfuscate_SeV31Q??[0];
                    $_obfuscate_6RYLWQ??[$_obfuscate_Vwty]['display'] = $_obfuscate_SeV31Q??[1];
                }
            }
            else
            {
                $_obfuscate_ = explode( ",", $_obfuscate_xs33Yt_k['items'] );
                $_obfuscate_6RYLWQ?? = array( );
                foreach ( $_obfuscate_ as $_obfuscate_Vwty => $_obfuscate_VgKtFeg? )
                {
                    $_obfuscate_SeV31Q?? = explode( "|", $_obfuscate_VgKtFeg? );
                    $_obfuscate_6RYLWQ??['value'] = $_obfuscate_SeV31Q??[0];
                    $_obfuscate_6RYLWQ??['display'] = $_obfuscate_SeV31Q??[1];
                    break;
                }
            }
        }
        return array(
            $_obfuscate_xs33Yt_k,
            $_obfuscate_6RYLWQ??
        );
    }

    private function formatSqlselector1( &$_obfuscate_60GquoKMPw??, &$_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg?, &$_obfuscate_6RYLWQ??, &$_obfuscate_p5ZWxr4? )
    {
        $_obfuscate_ncdC0pM? = $_obfuscate_p5ZWxr4?['width'];
        $_obfuscate_3FCLQL2p = $this->odata['height'];
        $_obfuscate_gfGsQGKrGg?? = $this->fieldList[$_obfuscate_60GquoKMPw??['name']]['id'];
        $_obfuscate_0sRnHy4? = $this->wfFFFD->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "style=\"".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1].";width:".$_obfuscate_ncdC0pM?."px;\" ";
        $_obfuscate_vZOcQA?? = "";
        if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_TervNcSylPE? = "";
            $_obfuscate_F43xrelD_9A? = " wf_field_write";
            if ( $_obfuscate_6mlyHg??['must'] == 1 )
            {
                $_obfuscate_F43xrelD_9A? = " wf_field_must";
                $_obfuscate_vZOcQA?? = "must=\"true\"";
            }
            if ( $_obfuscate_zdFnXMEXKAQUUA?? )
            {
                $_obfuscate_TervNcSylPE? = "readonly=\"readonly\"";
                $_obfuscate_F43xrelD_9A? = " wf_field_read";
            }
            $_obfuscate_LQ8UKg?? = "<select ".$_obfuscate_vZOcQA??.$_obfuscate_TervNcSylPE?.$_obfuscate_0sRnHy4?."id=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" name=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" ".$this->__getBindFieldStr( $_obfuscate_p5ZWxr4?['bindField'] ).$this->__getBindMaxNumStr( $_obfuscate_gfGsQGKrGg??, $this->nowLine )."otype=\"".$_obfuscate_p5ZWxr4?['otype']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" >";
            $_obfuscate_LQ8UKg?? .= "<option value=\"\">&#160;</option>";
            foreach ( $_obfuscate_6RYLWQ??[1] as $_obfuscate_Ilme )
            {
                $_obfuscate_LQ8UKg?? .= "<option value=\"".$_obfuscate_Ilme[$_obfuscate_6RYLWQ??[0]['value']]."\" >".$_obfuscate_Ilme[$_obfuscate_6RYLWQ??[0]['display']]."</option>";
            }
            $_obfuscate_LQ8UKg?? .= "</select>";
            return $_obfuscate_LQ8UKg??;
        }
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
            $_obfuscate_j574IU5Dhw?? = $_obfuscate_Ilme[$_obfuscate_6RYLWQ??[0]['display']];
            $_obfuscate_VgKtFeg? = $_obfuscate_Ilme[$_obfuscate_6RYLWQ??[0]['value']];
            break;
        }
        $_obfuscate_LQ8UKg?? = "<span ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."<br>?????¡ä?????1???<span class='cnoa_color_red'>".$_obfuscate_j574IU5Dhw??."</span>\"class=\"wf_field wf_field_read\" ".$_obfuscate_0sRnHy4?."style=\"border:none;\" >".$_obfuscate_j574IU5Dhw??."</span>";
        $_obfuscate_LQ8UKg?? .= "<input type=\"hidden\" ".$_obfuscate_vZOcQA??." id=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_p5ZWxr4?['otype']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" name=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" class=\"wf_field wf_field_read\" value=\"".$_obfuscate_VgKtFeg?."\" ".$_obfuscate_0sRnHy4?.$this->__getBindFieldStr( $_obfuscate_p5ZWxr4?['bindField'] ).$this->__getBindMaxNumStr( $_obfuscate_gfGsQGKrGg??, $this->nowLine )."readonly=\"readonly\" />";
        return $_obfuscate_LQ8UKg??;
    }

    private function formatSqlselector2( &$_obfuscate_60GquoKMPw??, &$_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg?, &$_obfuscate_6RYLWQ??, &$_obfuscate_p5ZWxr4? )
    {
        $_obfuscate_ncdC0pM? = $_obfuscate_p5ZWxr4?['width'];
        $_obfuscate_3FCLQL2p = $this->odata['height'];
        $_obfuscate_gfGsQGKrGg?? = $this->fieldList[$_obfuscate_60GquoKMPw??['name']]['id'];
        $_obfuscate_0sRnHy4? = $this->wfFFFD->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "style=\"".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1].";width:".$_obfuscate_ncdC0pM?."px;\" ";
        $_obfuscate_vZOcQA?? = "";
        $_obfuscate_j574IU5Dhw?? = $_obfuscate_6RYLWQ??[1][$_obfuscate_6RYLWQ??[0]['display']];
        $_obfuscate_VgKtFeg? = $_obfuscate_6RYLWQ??[1][$_obfuscate_6RYLWQ??[0]['value']];
        $_obfuscate_LQ8UKg?? = "<span ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."<br>?????¡ä?????1???<span class='cnoa_color_red'>".$_obfuscate_VgKtFeg?."</span>\"class=\"wf_field wf_field_read\" ".$_obfuscate_0sRnHy4?."style=\"border:none;\" >".$_obfuscate_j574IU5Dhw??."</span>";
        $_obfuscate_LQ8UKg?? .= "<input type=\"hidden\" ".$_obfuscate_vZOcQA??." ".$_obfuscate_0sRnHy4?.$_obfuscate_TervNcSylPE?."id=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" name=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" otype=\"".$_obfuscate_p5ZWxr4?['otype']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" class=\"wf_field wf_field_read\" value=\"".$_obfuscate_VgKtFeg?."\" ".$this->__getBindFieldStr( $_obfuscate_p5ZWxr4?['bindField'] ).$this->__getBindMaxNumStr( $_obfuscate_gfGsQGKrGg??, $this->nowLine )."/>";
        return $_obfuscate_LQ8UKg??;
    }

    private function _format_abutment( $element, $rule, $value = "", $elReadOnly = FALSE )
    {
        $odata = $element['odata'];
        $fieldid = $this->fieldList[$element['name']]['id'];
        $mapPath = CNOA_PATH_FILE.( "/common/wf/detail/sqlselector/".$fieldid.".php" );
        if ( empty( $this->uFlowId ) || !file_exists( $mapPath ) )
        {
            @mkdir( CNOA_PATH_FILE."/common/wf/detail/sqlselector" );
            $data = $this->getSqlselectorData( $odata['item'][0]['sqlselectorID'] );
            $content = "<?php\r\nreturn ".var_export( $data, TRUE ).";\r\n?>";
            file_put_contents( $mapPath, $content );
        }
        else
        {
            $data = include_once( $mapPath );
        }
        if ( $data[0]['selectorStyle'] == 1 )
        {
            $item = $this->formatSqlselector1( $element, $rule, $value, $data, $odata );
            return $item;
        }
        if ( $data[0]['selectorStyle'] == 2 )
        {
            $item = $this->formatSqlselector2( $element, $rule, $value, $data, $odata );
        }
        return $item;
    }

    private function _format_checkbox( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "", $_obfuscate_zdFnXMEXKAQUUA?? = FALSE )
    {
        $_obfuscate_p5ZWxr4? = $_obfuscate_60GquoKMPw??['odata'];
        $_obfuscate_ncdC0pM? = $_obfuscate_p5ZWxr4?['width'];
        $_obfuscate_3FCLQL2p = $this->odata['height'];
        $_obfuscate_gfGsQGKrGg?? = $this->fieldList[$_obfuscate_60GquoKMPw??['name']]['id'];
        $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_VgKtFeg?, ENT_NOQUOTES );
        $_obfuscate_VgKtFeg? = json_decode( $_obfuscate_VgKtFeg?, TRUE );
        $_obfuscate_0sRnHy4? = $this->wfFFFD->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "style=\"padding:3px;display:block;".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1].";width:".$_obfuscate_ncdC0pM?."px;\" ";
        if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_F43xrelD_9A? = " wf_field_write";
            if ( $_obfuscate_6mlyHg??['must'] == 1 )
            {
                $_obfuscate_F43xrelD_9A? = " wf_field_must";
                $_obfuscate_vZOcQA?? = "must=\"true\"";
            }
            if ( $_obfuscate_zdFnXMEXKAQUUA?? )
            {
                $_obfuscate_TervNcSylPE? = "readonly=\"readonly\"";
                $_obfuscate_F43xrelD_9A? = " wf_field_read";
            }
            $_obfuscate_LQ8UKg?? = "<span ".$this->__getBindFieldStr( $_obfuscate_p5ZWxr4?['bindField'] )." ".$this->__getBindMaxNumStr( $_obfuscate_gfGsQGKrGg??, $this->nowLine )." class=\"".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_0sRnHy4?.">";
            if ( !is_array( $_obfuscate_p5ZWxr4?['item'] ) )
            {
                $_obfuscate_p5ZWxr4?['item'] = array( );
            }
            $_obfuscate_0W8? = 0;
            foreach ( $_obfuscate_p5ZWxr4?['item'] as $_obfuscate_5w?? => $_obfuscate_Ilme )
            {
                if ( !empty( $_obfuscate_VgKtFeg? ) || is_array( $_obfuscate_VgKtFeg? ) )
                {
                    $_obfuscate_VPuPMczbSQ?? = in_array( $_obfuscate_Ilme['pname'], $_obfuscate_VgKtFeg? ) ? " checked=\"checked\" " : "";
                }
                else
                {
                    $_obfuscate_VPuPMczbSQ?? = $_obfuscate_Ilme['pchecked'] == "on" ? " checked=\"checked\" " : "";
                }
                $_obfuscate_XoQj4PaA = $_obfuscate_Ilme['pchecked'] == "on" ? "dvalue=\"true\" " : "";
                ++$_obfuscate_0W8?;
                $_obfuscate_LQ8UKg?? .= "<label for=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."_".$_obfuscate_0W8?."\"><input type=\"checkbox\"id=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."_".$_obfuscate_0W8?."\" gid=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" itemname=\"".$_obfuscate_Ilme['pname']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" name=\"wf_detailC_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."_".$_obfuscate_Ilme['pname']."\" value=\"".$_obfuscate_Ilme['pname']."\" ".$_obfuscate_VPuPMczbSQ??.$_obfuscate_XoQj4PaA.$_obfuscate_TervNcSylPE?." />".$_obfuscate_Ilme['pname']."</label>";
                $_obfuscate_LQ8UKg?? .= count( $_obfuscate_p5ZWxr4?['item'] ) == $_obfuscate_5w?? + 1 ? "" : "&nbsp;";
            }
            $_obfuscate_LQ8UKg?? .= "</span>";
            if ( $_obfuscate_6mlyHg??['must'] == 1 )
            {
                $this->extHtml .= "<input type=\"hidden\" must=\"true\" foroname=\"".$_obfuscate_60GquoKMPw??['name']."\" mustfor=\"checkbox\" musttarget=\"wf_detail_".$_obfuscate_gfGsQGKrGg??."\">";
                return $_obfuscate_LQ8UKg??;
            }
        }
        else
        {
            $_obfuscate_LQ8UKg?? = "<span ".$_obfuscate_0sRnHy4?.">";
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
            $_obfuscate_LQ8UKg?? .= "</span>";
        }
        return $_obfuscate_LQ8UKg??;
    }

    private function _format_calculate( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "", $_obfuscate_zdFnXMEXKAQUUA?? = FALSE )
    {
        $_obfuscate_p5ZWxr4? = $_obfuscate_60GquoKMPw??['odata'];
        $_obfuscate_ncdC0pM? = $_obfuscate_p5ZWxr4?['width'];
        $_obfuscate_3FCLQL2p = $this->odata['height'];
        $_obfuscate_gfGsQGKrGg?? = $this->fieldList[$_obfuscate_60GquoKMPw??['name']]['id'];
        $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_VgKtFeg? );
        $_obfuscate_sSwuE42EWQ?? = $_obfuscate_p5ZWxr4?['expression'];
        $_obfuscate_w5qdpW_0mo2_qehs = $this->api_splitGongShi( $_obfuscate_sSwuE42EWQ?? );
        foreach ( $_obfuscate_w5qdpW_0mo2_qehs as $_obfuscate_5w?? => $_obfuscate_GrQ? )
        {
            if ( !in_array( $_obfuscate_GrQ?, array( "+", "-", "*", "/" ) ) )
            {
                foreach ( $this->wfFFFD->flowDetailFields as $_obfuscate_5DM? )
                {
                    if ( $_obfuscate_GrQ? == $_obfuscate_5DM?['name'] )
                    {
                        if ( $_obfuscate_5DM?['fid'] == $this->tableEl['id'] )
                        {
                            $_obfuscate_w5qdpW_0mo2_qehs[$_obfuscate_5w??] = "wfd|".$_obfuscate_5DM?['id'];
                        }
                        else
                        {
                            $_obfuscate_w5qdpW_0mo2_qehs[$_obfuscate_5w??] = "wfo|".$_obfuscate_5DM?['id'];
                        }
                    }
                    else
                    {
                        foreach ( $this->wfFFFD->flowFields as $_obfuscate_WgE? )
                        {
                            if ( $_obfuscate_GrQ? == $_obfuscate_WgE?['name'] )
                            {
                                $_obfuscate_w5qdpW_0mo2_qehs[$_obfuscate_5w??] = "wf|".$_obfuscate_WgE?['id'];
                            }
                        }
                    }
                }
            }
        }
        $_obfuscate_sSwuE42EWQ?? = json_encode( $_obfuscate_w5qdpW_0mo2_qehs );
        $_obfuscate_sSwuE42EWQ?? = str_replace( "\"", "'", $_obfuscate_sSwuE42EWQ?? );
        $_obfuscate_0sRnHy4? = $this->wfFFFD->styleList[$_obfuscate_p5ZWxr4?['style']];
        if ( !empty( $_obfuscate_p5ZWxr4?['dvalue'] ) )
        {
            $_obfuscate_XoQj4PaA = " dvalue=\"".$_obfuscate_p5ZWxr4?['dvalue']."\" ";
        }
        if ( $_obfuscate_6mlyHg??['hide'] == 1 )
        {
            $_obfuscate_LQ8UKg?? = "<input type=\"hidden\" id=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" name=\"wf_detailJ_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" otype=\"".$_obfuscate_p5ZWxr4?['type']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" field=\"".$_obfuscate_gfGsQGKrGg??."\"class=\"wf_field\" ".$this->__getBindFieldStr( $_obfuscate_p5ZWxr4?['bindField'] ).$this->__getBindMaxNumStr( $_obfuscate_gfGsQGKrGg??, $this->nowLine )."roundtype=\"".$_obfuscate_p5ZWxr4?['dataFormat']."\" baoliu=\"".$_obfuscate_p5ZWxr4?['baoliu']."\"value=\"".$_obfuscate_VgKtFeg?."\"/>";
        }
        else if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_0sRnHy4? = "style=\"".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1].";width:".$_obfuscate_ncdC0pM?."px;\" ";
            $_obfuscate_7R7jAawdKeMxGQ?? = $_obfuscate_p5ZWxr4?['dataFormat'] == 2 ? "autoformat=\"".$this->autoFormatPHP[$_obfuscate_p5ZWxr4?['dataType']][$_obfuscate_p5ZWxr4?['dataFormat']]."\"" : "";
            $_obfuscate_LQ8UKg?? = "<input ".$_obfuscate_XoQj4PaA." type=\"text\" id=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" name=\"wf_detailJ_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" field=\"".$_obfuscate_gfGsQGKrGg??."\"otype=\"".$_obfuscate_p5ZWxr4?['type']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" class=\"wf_field\" ".$_obfuscate_7R7jAawdKeMxGQ??.$_obfuscate_0sRnHy4?.$this->__getBindFieldStr( $_obfuscate_p5ZWxr4?['bindField'] ).$this->__getBindMaxNumStr( $_obfuscate_gfGsQGKrGg??, $this->nowLine )."readonly=\"readonly\" value=\"".$_obfuscate_VgKtFeg?."\" gongshi=\"".$_obfuscate_sSwuE42EWQ??."\" roundtype=\"".$_obfuscate_p5ZWxr4?['dataFormat']."\" baoliu=\"".$_obfuscate_p5ZWxr4?['baoliu']."\"/>";
        }
        else
        {
            $_obfuscate_0sRnHy4? = "style=\"border-width:0;".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1].";width:".$_obfuscate_ncdC0pM?."px;\" ";
            $_obfuscate_LQ8UKg?? = "<input ".$_obfuscate_XoQj4PaA." type=\"text\" id=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" name=\"wf_detailJ_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" field=\"".$_obfuscate_gfGsQGKrGg??."\"otype=\"".$_obfuscate_p5ZWxr4?['type']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" class=\"wf_field wf_field_read\" ".$_obfuscate_0sRnHy4?.$this->__getBindFieldStr( $_obfuscate_p5ZWxr4?['bindField'] ).$this->__getBindMaxNumStr( $_obfuscate_gfGsQGKrGg??, $this->nowLine )."readonly=\"readonly\" value=\"".$_obfuscate_VgKtFeg?."\" gongshi=\"".$_obfuscate_sSwuE42EWQ??."\" roundtype=\"".$_obfuscate_p5ZWxr4?['dataFormat']."\" baoliu=\"".$_obfuscate_p5ZWxr4?['baoliu']."\"/>";
        }
        $this->wfFFFD['extHtml'] .= "<input type=\"hidden\" field=\"".$_obfuscate_gfGsQGKrGg??."\" calculate=\"true\" detail=\"true\" gongshi=\"".$_obfuscate_sSwuE42EWQ??."\" to=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" />";
        return $_obfuscate_LQ8UKg??;
    }

    private function _format_macro( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "", $_obfuscate_zdFnXMEXKAQUUA?? = FALSE )
    {
        global $CNOA_SESSION;
        $_obfuscate_p5ZWxr4? = $_obfuscate_60GquoKMPw??['odata'];
        $_obfuscate_ncdC0pM? = $_obfuscate_p5ZWxr4?['width'];
        $_obfuscate_3FCLQL2p = $this->odata['height'];
        $_obfuscate_gfGsQGKrGg?? = $this->fieldList[$_obfuscate_60GquoKMPw??['name']]['id'];
        $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_VgKtFeg? );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_0sRnHy4? = $this->wfFFFD->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "style=\"".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1].";width:".$_obfuscate_ncdC0pM?."px;\" ";
        if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_e7PLR79F = $_obfuscate_p5ZWxr4?['dataFormat'];
            $_obfuscate_2r6NB7kV = $_obfuscate_60GquoKMPw??['style'];
            $_obfuscate_U0CBGzzg = $this->styleList[$_obfuscate_p5ZWxr4?['style']];
            if ( $_obfuscate_p5ZWxr4?['allowedit'] == 1 )
            {
                $_obfuscate_TervNcSylPE? = "";
                $_obfuscate_F43xrelD_9A? = " wf_field_write";
            }
            else
            {
                $_obfuscate_TervNcSylPE? = "readonly=\"readonly\"";
                $_obfuscate_F43xrelD_9A? = "";
            }
            if ( $_obfuscate_zdFnXMEXKAQUUA?? )
            {
                $_obfuscate_TervNcSylPE? = "readonly=\"readonly\"";
                $_obfuscate_F43xrelD_9A? = " wf_field_read";
            }
            $_obfuscate_LQ8UKg?? = "<input type=\"text\"  class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" id=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" name=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" field=\"".$_obfuscate_gfGsQGKrGg??."\"otype=\"".$_obfuscate_p5ZWxr4?['type']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" ".$this->__getBindFieldStr( $_obfuscate_p5ZWxr4?['bindField'] ).$this->__getBindMaxNumStr( $_obfuscate_gfGsQGKrGg??, $this->nowLine ).$_obfuscate_0sRnHy4?;
            switch ( $_obfuscate_p5ZWxr4?['dataType'] )
            {
            case "month" :
                $_obfuscate_LQ8UKg?? .= "value=\"".$this->__formatMonth( $_obfuscate_e7PLR79F )."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "quarter" :
                $_obfuscate_LQ8UKg?? .= "value=\"".$this->__formatQuarter( $_obfuscate_e7PLR79F )."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "datetime" :
                $_obfuscate_LQ8UKg?? .= "value=\"".$this->__formatDatetime( $_obfuscate_e7PLR79F )."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "flowname" :
                $_obfuscate_LQ8UKg?? .= "value=\"".$this->wfFFFD->flowName."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "flownum" :
                $_obfuscate_LQ8UKg?? .= "value=\"".$this->wfFFFD->flowNumber."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "creatername" :
                $_obfuscate_LQ8UKg?? .= "value=\"".app::loadapp( "main", "user" )->api_getUserTruenameByUid( $this->wfFFFD->creatorUid )."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "createrdept" :
                $_obfuscate_vwGQSA?? = app::loadapp( "main", "struct" )->api_getDeptByUid( $this->wfFFFD->creatorUid );
                $_obfuscate_LQ8UKg?? .= "value=\"".$_obfuscate_vwGQSA??['name']."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "createrjob" :
                $_obfuscate_97K3 = app::loadapp( "main", "job" )->api_getNameByUid( $this->wfFFFD->creatorUid );
                $_obfuscate_LQ8UKg?? .= "value=\"".$_obfuscate_97K3."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "createrstation" :
                $_obfuscate_97K3 = app::loadapp( "main", "station" )->api_getNameByUid( $this->wfFFFD->creatorUid );
                $_obfuscate_LQ8UKg?? .= "value=\"".$_obfuscate_97K3."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "loginname" :
                $_obfuscate_LQ8UKg?? = "<input type=\"text\" value=\"".$CNOA_SESSION->get( "TRUENAME" )."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\"  ext:qtip=\"".$_obfuscate_60GquoKMPw??['name'].( "\" ".$_obfuscate_0sRnHy4?." {$_obfuscate_TervNcSylPE?} />" );
                $_obfuscate_LQ8UKg?? .= "<input type=\"hidden\" id=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" \" name=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" \" field=\"".$_obfuscate_gfGsQGKrGg??."\" otype=\"".$_obfuscate_p5ZWxr4?['otype']."\" oname=\"".$_obfuscate_60GquoKMPw??['oname']."\"  value=\"".$CNOA_SESSION->get( "UID" )."\"".$this->__getBindFieldStr( $_obfuscate_p5ZWxr4?['bindField'] ).$this->__getBindMaxNumStr( $_obfuscate_gfGsQGKrGg??, $this->nowLine )."\" />";
            case "logindept" :
                $_obfuscate_vwGQSA?? = app::loadapp( "main", "struct" )->api_getDeptByUid( $_obfuscate_7Ri3 );
                $_obfuscate_LQ8UKg?? .= "value=\"".$_obfuscate_vwGQSA??['name']."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "loginjob" :
                $_obfuscate_97K3 = app::loadapp( "main", "job" )->api_getNameById( $CNOA_SESSION->get( "JID" ) );
                $_obfuscate_LQ8UKg?? .= "value=\"".$_obfuscate_97K3."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "loginstation" :
                $_obfuscate_97K3 = app::loadapp( "main", "station" )->api_getNameById( $CNOA_SESSION->get( "SID" ) );
                $_obfuscate_LQ8UKg?? .= "value=\"".$_obfuscate_97K3."\" ".$_obfuscate_TervNcSylPE?."/>";
            case "userip" :
                $_obfuscate_LQ8UKg?? .= "value=\"192.168.1.100\" ".$_obfuscate_TervNcSylPE?."/>";
            case "moneyconvert" :
                $_obfuscate_LQ8UKg?? .= $_obfuscate_TervNcSylPE?." isvalid=\"true\" />";
                $_obfuscate_BJBeoQ4Zepw? = $this->api_getFieldInfoByName( $_obfuscate_p5ZWxr4?['from'], $this->flowId );
                $this->extHtml .= "<input type=\"hidden\" moneyconvert=\"true\" fromcount=\"".$_obfuscate_p5ZWxr4?['fromcount']."\" from=\"wf_detail_".$_obfuscate_BJBeoQ4Zepw?['id']."\" to=\"wf_detail_".$_obfuscate_gfGsQGKrGg??."\" />";
            case "snum" :
                $_obfuscate_8H1aLpUNLQ?? = $_obfuscate_p5ZWxr4?['expression'];
                $_obfuscate_WbLu7qQvg5AY = str_replace( "{x}", "{$this->nowLine}", $_obfuscate_8H1aLpUNLQ?? );
                $_obfuscate_LQ8UKg?? .= "value=\"".$_obfuscate_WbLu7qQvg5AY."\" snumber=\"".addslashes( $_obfuscate_8H1aLpUNLQ?? )."\" ".$_obfuscate_TervNcSylPE?."/>";
            default :
                return $_obfuscate_LQ8UKg??;
            }
            else
            {
                switch ( $_obfuscate_p5ZWxr4?['dataType'] )
                {
                case "month" :
                case "quarter" :
                case "datetime" :
                case "flowname" :
                case "flownum" :
                case "creatername" :
                    $_obfuscate_VgKtFeg? = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_VgKtFeg? );
                    break;
                case "createrdept" :
                    $_obfuscate_VgKtFeg? = app::loadapp( "main", "struct" )->api_getDeptByUid( $_obfuscate_VgKtFeg? );
                    break;
                case "createrjob" :
                    $_obfuscate_VgKtFeg? = app::loadapp( "main", "job" )->api_getNameById( $_obfuscate_VgKtFeg? );
                    break;
                case "createrstation" :
                    $_obfuscate_VgKtFeg? = app::loadapp( "main", "station" )->api_getNameById( $_obfuscate_VgKtFeg? );
                    break;
                case "loginname" :
                    $_obfuscate_VgKtFeg? = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_VgKtFeg? );
                    break;
                case "logindept" :
                case "loginjob" :
                case "loginstation" :
                case "userip" :
                case "moneyconvert" :
                case "snum" :
                }
            }
        }
        $_obfuscate_LQ8UKg?? = "<input type=\"text\" id=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" name=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" field=\"".$_obfuscate_gfGsQGKrGg??."\"otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" class=\"wf_field wf_field_read\" value=\"".$_obfuscate_VgKtFeg?."\" ".$_obfuscate_0sRnHy4?.$this->__getBindFieldStr( $_obfuscate_p5ZWxr4?['bindField'] ).$this->__getBindMaxNumStr( $_obfuscate_gfGsQGKrGg??, $this->nowLine )."readonly=\"readonly\" />";
        return $_obfuscate_LQ8UKg??;
    }

    private function _format_choice( $_obfuscate_60GquoKMPw??, $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg? = "", $_obfuscate_zdFnXMEXKAQUUA?? = FALSE )
    {
        global $CNOA_SESSION;
        $_obfuscate_p5ZWxr4? = $_obfuscate_60GquoKMPw??['odata'];
        $_obfuscate_ncdC0pM? = $_obfuscate_p5ZWxr4?['width'];
        $_obfuscate_3FCLQL2p = $this->odata['height'];
        $_obfuscate_gfGsQGKrGg?? = $this->fieldList[$_obfuscate_60GquoKMPw??['name']]['id'];
        $_obfuscate_VgKtFeg? = htmlspecialchars( $_obfuscate_VgKtFeg? );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_e7PLR79F = $_obfuscate_p5ZWxr4?['dataFormat'];
        $_obfuscate_0sRnHy4? = $this->wfFFFD->styleList[$_obfuscate_p5ZWxr4?['style']];
        $_obfuscate_0sRnHy4? = "style=\"".$_obfuscate_60GquoKMPw??['style'].";".$_obfuscate_0sRnHy4?[0].";".$_obfuscate_0sRnHy4?[1].";width:".$_obfuscate_ncdC0pM?."px;\" ";
        $_obfuscate_vZOcQA?? = "";
        $_obfuscate_F43xrelD_9A? = " ";
        if ( $_obfuscate_6mlyHg??['edit'] == 1 )
        {
            $_obfuscate_TervNcSylPE? = "";
        }
        else
        {
            $_obfuscate_TervNcSylPE? = "readonly=\"readonly\"";
        }
        if ( $_obfuscate_6mlyHg??['must'] == 1 )
        {
            $_obfuscate_F43xrelD_9A? = " wf_field_must";
            $_obfuscate_vZOcQA?? = "must=\"true\" ";
        }
        else if ( $_obfuscate_6mlyHg??['write'] == 1 )
        {
            $_obfuscate_F43xrelD_9A? = " wf_field_write";
        }
        if ( $_obfuscate_zdFnXMEXKAQUUA?? )
        {
            $_obfuscate_TervNcSylPE? = "readonly=\"readonly\"";
            $_obfuscate_F43xrelD_9A? = " wf_field_read";
        }
        $_obfuscate_AlW8F6SaSV8O = "<input type=\"text\" ".$_obfuscate_0sRnHy4?."ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_vZOcQA??.$_obfuscate_TervNcSylPE?.$this->__getBindFieldStr( $_obfuscate_p5ZWxr4?['bindField'] ).$this->__getBindMaxNumStr( $_obfuscate_gfGsQGKrGg??, $this->nowLine )."value=\"".$_obfuscate_VgKtFeg?."\" id=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" field=\"".$_obfuscate_gfGsQGKrGg??."\"name=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" ";
        $_obfuscate_7Ovywpwc3j7W = "<textarea ".$_obfuscate_0sRnHy4?."ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_vZOcQA??.$_obfuscate_TervNcSylPE?.$this->__getBindFieldStr( $_obfuscate_p5ZWxr4?['bindField'] ).$this->__getBindMaxNumStr( $_obfuscate_gfGsQGKrGg??, $this->nowLine )."id=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" field=\"".$_obfuscate_gfGsQGKrGg??."\"name=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" ";
        switch ( $_obfuscate_p5ZWxr4?['dataType'] )
        {
        case "date_sel" :
            switch ( $_obfuscate_p5ZWxr4?['dataFormat'] )
            {
            case 100 :
                $_obfuscate_ead0WM? = "yyyy-MM-dd";
                break;
            case 200 :
                $_obfuscate_ead0WM? = "yyyy-MM";
                break;
            case 300 :
                $_obfuscate_ead0WM? = "yy-MM-dd";
                break;
            case 400 :
                $_obfuscate_ead0WM? = "yyyyMMdd";
                break;
            case 500 :
                $_obfuscate_ead0WM? = "MM-dd yyyy";
                break;
            case 600 :
                $_obfuscate_ead0WM? = "yyyy?1¡äMM???";
                break;
            case 700 :
                $_obfuscate_ead0WM? = "yyyy?1¡äMM???dd??£¤";
                break;
            case 800 :
                $_obfuscate_ead0WM? = "MM???dd??£¤";
                break;
            case 900 :
                $_obfuscate_ead0WM? = "MM";
                break;
            case 1000 :
                $_obfuscate_ead0WM? = "yyyy.MM.dd";
                break;
            case 1100 :
                $_obfuscate_ead0WM? = "MM.dd";
                break;
            case 1200 :
                $_obfuscate_ead0WM? = "yyyy-MM-dd HH:mm";
                break;
            case 1300 :
                $_obfuscate_ead0WM? = "yyyy?1¡äMM???dd??£¤  HH:mm";
            }
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                if ( getpar( $_GET, "device", "" ) == "Tablet" )
                {
                    $_obfuscate_2FLoTKc2yw?? = "onClick=\"WdatePicker('".$_obfuscate_ead0WM?."', this)\" ";
                }
                else
                {
                    $_obfuscate_2FLoTKc2yw?? = "onClick=\"WdatePicker({dateFmt:'".$_obfuscate_ead0WM?."'})\" ";
                }
            }
            $_obfuscate_LQ8UKg?? = $_obfuscate_AlW8F6SaSV8O.$_obfuscate_2FLoTKc2yw??.$_obfuscate_TervNcSylPE?." />";
            break;
        case "time_sel" :
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_2FLoTKc2yw?? = "onClick=\"wfTimePicker.show(this, ".intval( $_obfuscate_p5ZWxr4?['dataFormat'] ).")\" ";
            }
            $_obfuscate_LQ8UKg?? = $_obfuscate_AlW8F6SaSV8O.$_obfuscate_2FLoTKc2yw??.$_obfuscate_TervNcSylPE?." />";
            break;
        case "dept_sel" :
            if ( !empty( $_obfuscate_VgKtFeg? ) )
            {
                $_obfuscate_VcY7imjf = app::loadapp( "main", "struct" )->api_getNameById( $_obfuscate_VgKtFeg? );
            }
            $_obfuscate_AlW8F6SaSV8O = "<input type=\"text\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_vZOcQA??.$_obfuscate_TervNcSylPE?.$_obfuscate_0sRnHy4?."to=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" value=\"".$_obfuscate_VcY7imjf."\" oname=\"".$_obfuscate_60GquoKMPw??['name']."\" ";
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_2FLoTKc2yw?? = " onclick=\"wf_selector('dept', this, false)\"";
            }
            $_obfuscate_LQ8UKg?? = $_obfuscate_AlW8F6SaSV8O.$_obfuscate_2FLoTKc2yw??." /><input type=\"hidden\" value=\"".$_obfuscate_VgKtFeg?."\" id=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" name=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\"/>";
            break;
        case "depts_sel" :
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_2FLoTKc2yw?? = "<button onclick=\"wf_selector('dept', ".$_obfuscate_gfGsQGKrGg??.", true)\">¨¦?????</button>";
            }
            $_obfuscate_LQ8UKg?? = $_obfuscate_7Ovywpwc3j7W." />".$_obfuscate_VgKtFeg?."</textarea>".$_obfuscate_2FLoTKc2yw??;
            break;
        case "station_sel" :
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_2FLoTKc2yw?? = "<button onclick=\"wf_selector('station', ".$_obfuscate_gfGsQGKrGg??.", false)\">¨¦?????</button>";
            }
            $_obfuscate_LQ8UKg?? = $_obfuscate_AlW8F6SaSV8O." />".$_obfuscate_2FLoTKc2yw??;
            break;
        case "stations_sel" :
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_2FLoTKc2yw?? = "<button onclick=\"wf_selector('station', ".$_obfuscate_gfGsQGKrGg??.", true)\">¨¦?????</button>";
            }
            $_obfuscate_LQ8UKg?? = $_obfuscate_7Ovywpwc3j7W." />".$_obfuscate_VgKtFeg?."</textarea>".$_obfuscate_2FLoTKc2yw??;
            break;
        case "job_sel" :
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_2FLoTKc2yw?? = "<button onclick=\"wf_selector('job', ".$_obfuscate_gfGsQGKrGg??.", false)\">¨¦?????</button>";
            }
            $_obfuscate_LQ8UKg?? = $_obfuscate_AlW8F6SaSV8O." />".$_obfuscate_2FLoTKc2yw??;
            break;
        case "jobs_sel" :
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_2FLoTKc2yw?? = "<button onclick=\"wf_selector('job', ".$_obfuscate_gfGsQGKrGg??.", true)\">¨¦?????</button>";
            }
            $_obfuscate_LQ8UKg?? = $_obfuscate_7Ovywpwc3j7W." />".$_obfuscate_VgKtFeg?."</textarea>".$_obfuscate_2FLoTKc2yw??;
            break;
        case "user_sel" :
            if ( !empty( $_obfuscate_VgKtFeg? ) )
            {
                $_obfuscate_VcY7imjf = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_VgKtFeg? );
            }
            $_obfuscate_AlW8F6SaSV8O = "<input type=\"text\" ext:qtip=\"".$_obfuscate_60GquoKMPw??['name']."\" otype=\"".$_obfuscate_60GquoKMPw??['otype']."\" class=\"wf_field".$_obfuscate_F43xrelD_9A?."\" ".$_obfuscate_0sRnHy4?.$_obfuscate_vZOcQA??.$_obfuscate_TervNcSylPE?.$_obfuscate_0sRnHy4?.$this->__getBindFieldStr( $_obfuscate_p5ZWxr4?['bindField'] ).$this->__getBindMaxNumStr( $_obfuscate_gfGsQGKrGg??, $this->nowLine )."value=\"".$_obfuscate_VcY7imjf."\" to=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" field=\"".$_obfuscate_gfGsQGKrGg??."\"oname=\"".$_obfuscate_60GquoKMPw??['name']."\" ";
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_2FLoTKc2yw?? = " onclick=\"wf_selector('user', this, false)\" ";
            }
            $_obfuscate_LQ8UKg?? = $_obfuscate_AlW8F6SaSV8O.$_obfuscate_VgKtFeg?.$_obfuscate_2FLoTKc2yw??." /><input type=\"hidden\" value=\"".$_obfuscate_VgKtFeg?."\" id=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" name=\"wf_detail_".$this->nowLine."_".$_obfuscate_gfGsQGKrGg??."\" />";
            break;
        case "users_sel" :
            if ( $_obfuscate_6mlyHg??['write'] == 1 )
            {
                $_obfuscate_2FLoTKc2yw?? = "<button onclick=\"wf_selector('user', ".$_obfuscate_gfGsQGKrGg??.", true)\">¨¦?????</button>";
            }
            $_obfuscate_LQ8UKg?? = $_obfuscate_7Ovywpwc3j7W." />".$_obfuscate_VgKtFeg?."</textarea>".$_obfuscate_2FLoTKc2yw??;
        }
        return $_obfuscate_LQ8UKg??;
    }

    private function __getFillDiv( $_obfuscate_ncdC0pM? )
    {
        return "<div style=\"width:".$_obfuscate_ncdC0pM?."px;\">&nbsp;</div>";
    }

    private function __getBindFieldStr( $_obfuscate_qke3ljUC4wzb )
    {
        if ( !empty( $_obfuscate_qke3ljUC4wzb ) )
        {
            return "bindfield=\"".$_obfuscate_qke3ljUC4wzb."\" ";
        }
        return "";
    }

    private function __getBindMaxNumStr( $_obfuscate_gfGsQGKrGg??, $_obfuscate_iDZo2kKvIw?? )
    {
        if ( !empty( $this->detailMax[$_obfuscate_gfGsQGKrGg??."_".$_obfuscate_iDZo2kKvIw??] ) )
        {
            return "maxnum=\"".$this->detailMax[$_obfuscate_gfGsQGKrGg??."_".$_obfuscate_iDZo2kKvIw??]."\" ";
        }
        return "";
    }

}

?>
