<?php

class wfNextStepData extends wfCommon
{

    private $flowFrom = NULL;
    private $flowData = NULL;
    private $flowId = NULL;
    private $uFlowId = NULL;
    private $uid = NULL;
    private $step = NULL;

    public function getNextStepInfo( $_obfuscate_UZ68Oucf )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $this->uid = $CNOA_SESSION->get( "UID" );
        $this->flowId = $_obfuscate_UZ68Oucf['flowId'];
        $this->formData = $_obfuscate_UZ68Oucf['formData'];
        $this->uFlowId = $_obfuscate_UZ68Oucf['uFlowId'];
        $this->step = $_obfuscate_UZ68Oucf['step'];
        $this->__comfirmFlowFrom( );
        $_obfuscate_WYOD1IJTSw?? = $_obfuscate_T1JGvNjMhVs? = $_obfuscate_NOIdChW6H4WQ9A?? = $_obfuscate_359jHH774OUs8Pw = $_obfuscate_g9qiaC_UVAvelMUZhkY? = array( );
        $_obfuscate_QwT4KwrB2w?? = $this->__getNowStepArr( );
        if ( $_obfuscate_QwT4KwrB2w??['nowStep']['stepType'] == 5 )
        {
            $_obfuscate_R1ogC2utJw?? = $CNOA_DB->db_getfield( "pStepId", $this->t_use_step, " WHERE `uStepId`=".$this->step." AND `uFlowId`={$this->uFlowId}" );
            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_step, " WHERE `pStepId`=".$_obfuscate_R1ogC2utJw??." AND `stepType`=6 AND `uFlowId`={$this->uFlowId}" );
            $_obfuscate_YUm8GYPOA? = array( );
            $_obfuscate_cIpwAQ?? = TRUE;
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w?? => $_obfuscate_6A?? )
            {
                if ( $_obfuscate_6A??['uid'] != 0 )
                {
                    if ( $_obfuscate_6A??['status'] != 2 )
                    {
                        $_obfuscate_cIpwAQ?? = FALSE;
                    }
                    else
                    {
                        array_push( &$_obfuscate_YUm8GYPOA?, $_obfuscate_6A??['uStepId'] );
                    }
                }
            }
            unset( $_obfuscate_mPAjEGLn );
            if ( !$_obfuscate_cIpwAQ?? )
            {
                $_obfuscate_1tz3iHa7jjybg?? = $CNOA_DB->db_select( array( "condition" ), $this->t_s_bingfa_condition, "WHERE `stepId`=".$this->step." AND `flowId`={$this->flowId}" );
                $_obfuscate_XP4WpjIMhOSD = array( );
                foreach ( $_obfuscate_1tz3iHa7jjybg?? as $_obfuscate_6A?? )
                {
                    $_obfuscate_XP4WpjIMhOSD[] = json_decode( $_obfuscate_6A??['condition'], TRUE );
                }
                foreach ( $_obfuscate_XP4WpjIMhOSD as $_obfuscate_6A?? )
                {
                    if ( count( array_diff( $_obfuscate_6A??, $_obfuscate_YUm8GYPOA? ) ) == 0 )
                    {
                        $_obfuscate_cIpwAQ?? = TRUE;
                    }
                }
            }
            if ( !$_obfuscate_cIpwAQ?? )
            {
                msg::callback( FALSE, "ии??????1?????-гдижaбш?2?ииж╠бу???" );
                exit( );
            }
        }
        $_obfuscate_NOIdChW6H4WQ9A?? = $this->__getNextStepList( $_obfuscate_QwT4KwrB2w?? );
        $_obfuscate_juwe = "";
        $_obfuscate_52n2_GE? = array( "isEnd", "operator", "stepId", "stepName" );
        foreach ( $_obfuscate_NOIdChW6H4WQ9A?? as $_obfuscate_5w?? => $_obfuscate_6A?? )
        {
            if ( $_obfuscate_6A??['stepType'] == 4 )
            {
                $_obfuscate_juwe = $this->step;
                $this->step = $_obfuscate_6A??['stepId'];
                $_obfuscate_EfQ5Ifdn82GI = $this->__getNowStepArr( );
                $_obfuscate_g9qiaC_UVAvelMUZhkY? = $this->__getNextStepList( $_obfuscate_EfQ5Ifdn82GI );
                foreach ( $_obfuscate_g9qiaC_UVAvelMUZhkY? as $_obfuscate_ClA? => $_obfuscate_bRQ? )
                {
                    foreach ( $_obfuscate_bRQ? as $_obfuscate_3QY? => $_obfuscate_EGU? )
                    {
                        if ( !in_array( $_obfuscate_3QY?, $_obfuscate_52n2_GE? ) )
                        {
                            unset( in_array( $_obfuscate_3QY?, $_obfuscate_52n2_GE? )[$_obfuscate_3QY?] );
                        }
                    }
                }
                $_obfuscate_359jHH774OUs8Pw[$_obfuscate_5w??]['child'] = array_merge( $_obfuscate_g9qiaC_UVAvelMUZhkY? );
                $_obfuscate_359jHH774OUs8Pw[$_obfuscate_5w??]['convergenenUname'] = $this->_convergenceUname( $_obfuscate_6A??['stepId'] );
            }
            else
            {
                $_obfuscate_359jHH774OUs8Pw[$_obfuscate_5w??]['child'] = "";
            }
            $_obfuscate_359jHH774OUs8Pw[$_obfuscate_5w??]['stepType'] = $_obfuscate_6A??['stepType'];
        }
        $this->step = $_obfuscate_juwe;
        unset( $_obfuscate_juwe );
        unset( $_obfuscate_EfQ5Ifdn82GI );
        unset( $_obfuscate_52n2_GE? );
        unset( $_obfuscate_g9qiaC_UVAvelMUZhkY? );
        foreach ( $_obfuscate_NOIdChW6H4WQ9A?? as $_obfuscate_5w?? => $_obfuscate_6A?? )
        {
            $_obfuscate_SeV31Q?? = array( );
            $_obfuscate_fh2Upg?? = $CNOA_DB->db_getfield( "deal", $this->t_use_deal_way, "WHERE `flowId`=".$_obfuscate_6A??['flowId']." AND `stepId`={$_obfuscate_6A??['stepId']}" );
            if ( $_obfuscate_6A??['access'] )
            {
                $_obfuscate_359jHH774OUs8Pw[$_obfuscate_5w??] = array_merge( $_obfuscate_359jHH774OUs8Pw[$_obfuscate_5w??], array(
                    "stepId" => $_obfuscate_6A??['stepId'],
                    "stepName" => $_obfuscate_6A??['stepName'],
                    "isEnd" => $_obfuscate_6A??['isEnd'],
                    "operator" => $_obfuscate_6A??['operator'],
                    "dealWay" => $_obfuscate_fh2Upg??
                ) );
            }
            else
            {
                unset( $_obfuscate_359jHH774OUs8Pw[$_obfuscate_5w??] );
            }
        }
        return array_merge( $_obfuscate_359jHH774OUs8Pw );
    }

    private function excludeUids( $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        $_obfuscate_pVdgcB4wHyx2GG0? = $CNOA_DB->db_select( array( "exclude" ), $this->t_set_step_user, "WHERE `flowId` = ".$_obfuscate_F4AbnVRh." AND `stepId` = {$_obfuscate_0Ul8BBkt}" );
        if ( !is_array( $_obfuscate_pVdgcB4wHyx2GG0? ) )
        {
            $_obfuscate_pVdgcB4wHyx2GG0? = array( );
        }
        $_obfuscate_ = array( );
        foreach ( $_obfuscate_pVdgcB4wHyx2GG0? as $_obfuscate_Vwty => $_obfuscate_VgKtFeg? )
        {
            $_obfuscate_[] = $_obfuscate_VgKtFeg?['exclude'];
        }
        return $_obfuscate_;
    }

    private function __comfirmFlowFrom( )
    {
        if ( isset( $this->uFlowId, $this->step ) )
        {
            $this->flowFrom = "todo";
        }
        else
        {
            $this->flowFrom = "new";
        }
    }

    private function __getNowStepArr( )
    {
        global $CNOA_DB;
        $_obfuscate_WYOD1IJTSw?? = array( );
        $_obfuscate_T1JGvNjMhVs? = array( );
        if ( $this->flowFrom == "new" )
        {
            $_obfuscate_Tx7M9W = $CNOA_DB->db_select( "*", $this->t_set_step, "WHERE `flowId` = ".$this->flowId );
            if ( !is_array( $_obfuscate_Tx7M9W ) )
            {
                $_obfuscate_Tx7M9W = array( );
            }
            foreach ( $_obfuscate_Tx7M9W as $_obfuscate_6A?? )
            {
                $_obfuscate_T1JGvNjMhVs?[$_obfuscate_6A??['stepId']] = $_obfuscate_6A??;
                if ( empty( $this->step ) )
                {
                    if ( $_obfuscate_6A??['stepType'] == 1 )
                    {
                        $_obfuscate_WYOD1IJTSw?? = $_obfuscate_6A??;
                    }
                }
                else if ( $_obfuscate_6A??['stepId'] == $this->step )
                {
                    $_obfuscate_WYOD1IJTSw?? = $_obfuscate_6A??;
                }
            }
        }
        else if ( $this->flowFrom == "todo" )
        {
            ( $this->uFlowId );
            $GLOBALS['GLOBALS']['UWFCACHE'] = new wfCache( );
            $_obfuscate_WYOD1IJTSw?? = $GLOBALS['UWFCACHE']->getStepByStepId( $this->step );
        }
        return array(
            "nowStep" => $_obfuscate_WYOD1IJTSw??,
            "stepList" => $_obfuscate_T1JGvNjMhVs?
        );
    }

    private function __getNextStepList( $_obfuscate_UZ68Oucf )
    {
        global $CNOA_DB;
        $_obfuscate_WYOD1IJTSw?? = $_obfuscate_UZ68Oucf['nowStep'];
        $_obfuscate_T1JGvNjMhVs? = $_obfuscate_UZ68Oucf['stepList'];
        $_obfuscate_NOIdChW6H4WQ9A?? = array( );
        $_obfuscate_MnVVbyZQFVw? = json_decode( $_obfuscate_WYOD1IJTSw??['nextStep'], TRUE );
        if ( !is_array( $_obfuscate_MnVVbyZQFVw? ) )
        {
            $_obfuscate_MnVVbyZQFVw? = array( );
        }
        foreach ( $_obfuscate_MnVVbyZQFVw? as $_obfuscate_5w?? => $_obfuscate_6A?? )
        {
            if ( $this->flowFrom == "new" )
            {
                $_obfuscate_NOIdChW6H4WQ9A??[$_obfuscate_T1JGvNjMhVs?[$_obfuscate_6A??]['stepId']] = $_obfuscate_T1JGvNjMhVs?[$_obfuscate_6A??];
                $_obfuscate_IRFhnYw? = "WHERE `flowId`='".$this->flowId."' AND `stepId`=2 AND `nextStepId`='{$_obfuscate_T1JGvNjMhVs?[$_obfuscate_6A??]['stepId']}' ";
                $_obfuscate_IRFhnYw? .= "ORDER BY `id` ASC";
                $_obfuscate_JZ6I88Wbqo9BBXRMJA?? = $CNOA_DB->db_select( "*", $this->t_set_step_condition, $_obfuscate_IRFhnYw? );
                $_obfuscate_6A?? = $_obfuscate_T1JGvNjMhVs?[$_obfuscate_6A??]['stepId'];
            }
            else if ( $this->flowFrom == "todo" )
            {
                $_obfuscate_7qDAYo85aGA? = $CNOA_DB->db_getone( array( "uid" ), $this->t_use_flow, "WHERE `uFlowId`='".$this->uFlowId."'" );
                $_obfuscate_tVChs9bkVRSlzLv = $GLOBALS['UWFCACHE']->getStepByStepId( $_obfuscate_6A?? );
                if ( !( $_obfuscate_tVChs9bkVRSlzLv['stepType'] == 7 ) )
                {
                    if ( $_obfuscate_tVChs9bkVRSlzLv['stepType'] == 5 )
                    {
                        $_obfuscate_fZ6hQpORHHICsQa9gw? = $CNOA_DB->db_getfield( "uid", $this->t_use_step, "WHERE `uFlowId`=".$this->uFlowId." AND `uStepId`={$_obfuscate_tVChs9bkVRSlzLv['stepId']}" );
                        if ( $_obfuscate_fZ6hQpORHHICsQa9gw? == 0 )
                        {
                            $_obfuscate_uZuyz7j0HpmJyZsbm6zE = $CNOA_DB->db_getfield( "rule", "wf_u_convergence_deal", "WHERE `uFlowId`=".$this->uFlowId." AND `stepId`={$_obfuscate_tVChs9bkVRSlzLv['stepId']}" );
                        }
                    }
                    $_obfuscate_NOIdChW6H4WQ9A??[$_obfuscate_6A??] = $GLOBALS['UWFCACHE']->getStepByStepId( $_obfuscate_6A?? );
                    $_obfuscate_JZ6I88Wbqo9BBXRMJA?? = $GLOBALS['UWFCACHE']->getConditionList( $this->step, $_obfuscate_6A?? );
                    $this->uid = $_obfuscate_7qDAYo85aGA?['uid'];
                }
            }
            if ( $_obfuscate_NOIdChW6H4WQ9A??[$_obfuscate_6A??]['stepType'] == 3 )
            {
                $_obfuscate_NOIdChW6H4WQ9A??[$_obfuscate_6A??]['isEnd'] = TRUE;
            }
            else
            {
                $_obfuscate_NOIdChW6H4WQ9A??[$_obfuscate_6A??]['isEnd'] = FALSE;
            }
            if ( !is_array( $_obfuscate_JZ6I88Wbqo9BBXRMJA?? ) )
            {
                $_obfuscate_JZ6I88Wbqo9BBXRMJA?? = array( );
            }
            if ( $this->_isStepAccess( $this->flowId, $_obfuscate_6A??, $_obfuscate_JZ6I88Wbqo9BBXRMJA?? ) )
            {
                if ( $this->flowFrom == "new" )
                {
                    $_obfuscate_fMfssw?? = $CNOA_DB->db_select( "*", $this->t_set_step_user, "WHERE `flowId`='".$this->flowId."' AND `stepId`='{$_obfuscate_6A??}' ORDER BY `people`" );
                }
                else if ( $this->flowFrom == "todo" )
                {
                    $_obfuscate_fMfssw?? = $GLOBALS['UWFCACHE']->getStepUserByStepId( $_obfuscate_6A?? );
                    if ( $_obfuscate_tVChs9bkVRSlzLv['stepType'] == 5 && $_obfuscate_fZ6hQpORHHICsQa9gw? == 0 )
                    {
                        if ( !is_array( $_obfuscate_fMfssw?? ) )
                        {
                            $_obfuscate_fMfssw?? = array( );
                        }
                        foreach ( $_obfuscate_fMfssw?? as $_obfuscate_Vwty => $_obfuscate_TAxu )
                        {
                            if ( $_obfuscate_TAxu['kong'] != $_obfuscate_uZuyz7j0HpmJyZsbm6zE )
                            {
                                unset( $_obfuscate_fMfssw??[$_obfuscate_Vwty] );
                            }
                        }
                        unset( $_obfuscate_uZuyz7j0HpmJyZsbm6zE );
                    }
                }
                $_obfuscate_pVdgcB4wHyx2GG0? = $this->excludeUids( $this->flowId, $_obfuscate_6A?? );
                $_obfuscate_NOIdChW6H4WQ9A??[$_obfuscate_6A??]['operator'] = $this->_getOperatorsByStepInfo( $_obfuscate_fMfssw?? );
                if ( !empty( $_obfuscate_NOIdChW6H4WQ9A??[$_obfuscate_6A??]['operator'] ) )
                {
                    $_obfuscate_SeV31Q?? = array( );
                    foreach ( $_obfuscate_NOIdChW6H4WQ9A??[$_obfuscate_6A??]['operator'] as $_obfuscate_Vwty => $_obfuscate_VgKtFeg? )
                    {
                        if ( in_array( $_obfuscate_VgKtFeg?['uid'], $_obfuscate_pVdgcB4wHyx2GG0? ) )
                        {
                            unset( $this->operator[$_obfuscate_Vwty] );
                        }
                        else
                        {
                            $_obfuscate_SeV31Q??[] = $_obfuscate_VgKtFeg?;
                        }
                    }
                    $_obfuscate_NOIdChW6H4WQ9A??[$_obfuscate_6A??]['operator'] = $_obfuscate_SeV31Q??;
                }
                if ( $_obfuscate_tVChs9bkVRSlzLv['stepType'] == 5 && $_obfuscate_fZ6hQpORHHICsQa9gw? != 0 )
                {
                    foreach ( $_obfuscate_NOIdChW6H4WQ9A??[$_obfuscate_6A??]['operator'] as $_obfuscate_Vwty => $_obfuscate_TAxu )
                    {
                        if ( !( $_obfuscate_TAxu['uid'] == $_obfuscate_fZ6hQpORHHICsQa9gw? ) )
                        {
                            continue;
                        }
                        unset( $_obfuscate_NOIdChW6H4WQ9A??[$_obfuscate_6A??]['operator']['operator'] );
                        $_obfuscate_NOIdChW6H4WQ9A??[$_obfuscate_6A??]['operator'][] = $_obfuscate_TAxu;
                        break;
                    }
                }
                unset( $_obfuscate_fZ6hQpORHHICsQa9gw? );
                unset( $_obfuscate_Vwty );
                unset( $_obfuscate_TAxu );
                $_obfuscate_NOIdChW6H4WQ9A??[$_obfuscate_6A??]['access'] = TRUE;
            }
            else
            {
                $_obfuscate_NOIdChW6H4WQ9A??[$_obfuscate_6A??]['operator'] = array( );
                $_obfuscate_NOIdChW6H4WQ9A??[$_obfuscate_6A??]['access'] = FALSE;
            }
        }
        return $_obfuscate_NOIdChW6H4WQ9A??;
    }

    protected function _isStepAccess( $flowId, $nextStepId, $conditionList )
    {
        global $CNOA_SESSION;
        $cdnList = $cdnListTmp = array( );
        if ( !is_array( $conditionList ) )
        {
            return TRUE;
        }
        if ( count( $conditionList ) <= 0 )
        {
            return TRUE;
        }
        $optor['n_u'] = $this->uid;
        $optor['n_u_info'] = app::loadapp( "main", "user" )->api_getUserInfoByUid( $optor['n_u'] );
        $optor['n_n'] = $optor['n_u_info']['truename'];
        $optor['n_s'] = app::loadapp( "main", "station" )->api_getNameById( $optor['n_u_info']['stationid'] );
        $optor['n_d'] = app::loadapp( "main", "struct" )->api_getNameById( $optor['n_u_info']['deptId'] );
        $optor['d_u'] = $CNOA_SESSION->get( "UID" );
        if ( $optor['d_u'] == $optor['n_u'] )
        {
            $optor['d_u_info'] = $optor['n_u_info'];
        }
        else
        {
            $optor['d_u_info'] = app::loadapp( "main", "user" )->api_getUserInfoByUid( $optor['d_u'] );
        }
        $optor['d_n'] = $optor['d_u_info']['truename'];
        $optor['d_s'] = app::loadapp( "main", "station" )->api_getNameById( $optor['d_u_info']['stationid'] );
        $optor['d_d'] = app::loadapp( "main", "struct" )->api_getNameById( $optor['d_u_info']['deptId'] );
        foreach ( $conditionList as $v )
        {
            if ( $v['pid'] == 0 )
            {
                $cdnListTmp[$v['id']][] = $v;
            }
            else
            {
                $cdnListTmp[$v['pid']][] = $v;
            }
        }
        $condition = array( );
        $orAnd = array( "or" => " || ", "and" => " && " );
        foreach ( $cdnListTmp as $v )
        {
            if ( 1 < count( $v ) )
            {
                $boolean = $judgetTmp = array( );
                $judgeStr = "";
                foreach ( $v as $ck => $cv )
                {
                    if ( $ck == 0 )
                    {
                        $leftJudge = $cv['orAnd'];
                    }
                    $boolean[] = array(
                        "boolean" => $this->__ckConditionItem( $cv, $this->formData, $optor ),
                        "leftJudge" => $cv['orAnd']
                    );
                }
                foreach ( $boolean as $bk => $bv )
                {
                    if ( $bk == 0 )
                    {
                        $judgeStr .= $bv['boolean']." ";
                    }
                    else
                    {
                        $judgeStr .= $orAnd[$bv['leftJudge']]." ".$bv['boolean']." ";
                    }
                }
                eval( "\$judgetTmp['boolean']=(".$judgeStr.");" );
                $judgetTmp['boolean'] = $judgetTmp['boolean'] ? "true" : "false";
                $judgetTmp['leftJudge'] = $leftJudge;
                $condition[] = $judgetTmp;
            }
            else
            {
                $condition[] = array(
                    "boolean" => $this->__ckConditionItem( $v[0], $this->formData, $optor ),
                    "leftJudge" => $v[0]['orAnd']
                );
            }
        }
        $judgeStr = "";
        foreach ( $condition as $ck => $cv )
        {
            if ( $ck == 0 )
            {
                $judgeStr .= $cv['boolean']." ";
            }
            else
            {
                $judgeStr .= $orAnd[$cv['leftJudge']]." ".$cv['boolean']." ";
            }
        }
        eval( "\$judget=(".$judgeStr.");" );
        return $judget;
    }

    protected function _getOperatorsByStepInfo( $_obfuscate_fMfssw?? )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        if ( !is_array( $_obfuscate_fMfssw?? ) )
        {
            $_obfuscate_fMfssw?? = array( );
        }
        $_obfuscate_irK5Vac? = array(
            "people" => array( 0 ),
            "station" => array( 0 ),
            "dept" => array( 0 ),
            "rule" => array( 0 ),
            "deptstation" => array(
                array( "dept" => 0, "station" => 0 )
            ),
            "kong" => array( 0 )
        );
        $_obfuscate_6RYLWQ?? = array( );
        foreach ( $_obfuscate_fMfssw?? as $_obfuscate_6A?? )
        {
            if ( !empty( $this->uFlowId ) )
            {
                $_obfuscate_oysBlb52zkcdeA?? = $CNOA_DB->db_getone( "*", $this->t_use_step, "WHERE `stepType`<>5 AND `uStepId` = ".$_obfuscate_6A??['stepId']." AND `uFlowId` = {$this->uFlowId} AND `uid`!=0 " );
            }
            if ( !empty( $_obfuscate_oysBlb52zkcdeA?? ) )
            {
                $_obfuscate_irK5Vac?['people'][] = $_obfuscate_oysBlb52zkcdeA??['uid'];
            }
            else if ( $_obfuscate_6A??['type'] == "people" )
            {
                $_obfuscate_irK5Vac?['people'][] = $_obfuscate_6A??['people'];
            }
            else if ( $_obfuscate_6A??['type'] == "dept" )
            {
                $_obfuscate_irK5Vac?['dept'][] = $_obfuscate_6A??['dept'];
            }
            else if ( $_obfuscate_6A??['type'] == "station" )
            {
                $_obfuscate_irK5Vac?['station'][] = $_obfuscate_6A??['station'];
            }
            else if ( $_obfuscate_6A??['type'] == "rule" )
            {
                $_obfuscate_irK5Vac?['rule'][] = array(
                    "rule_p" => $_obfuscate_6A??['rule_p'],
                    "rule_d" => $_obfuscate_6A??['rule_d'],
                    "rule_s" => $_obfuscate_6A??['rule_s']
                );
            }
            else if ( $_obfuscate_6A??['type'] == "deptstation" )
            {
                $_obfuscate_irK5Vac?['deptstation'][] = array(
                    "dept" => $_obfuscate_6A??['dept'],
                    "station" => $_obfuscate_6A??['station']
                );
            }
            else if ( $_obfuscate_6A??['type'] == "kong" )
            {
                $_obfuscate_irK5Vac?['kong'][] = $_obfuscate_6A??['kong'];
            }
        }
        unset( $this->people[0] );
        if ( 0 < count( $_obfuscate_irK5Vac?['people'] ) )
        {
            $_obfuscate_kIVhqJk? = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_irK5Vac?['people'] );
            if ( !is_array( $_obfuscate_kIVhqJk? ) )
            {
                $_obfuscate_kIVhqJk? = array( );
            }
            foreach ( $_obfuscate_kIVhqJk? as $_obfuscate_5w?? => $_obfuscate_6A?? )
            {
                $_obfuscate_6RYLWQ??[$_obfuscate_6A??['uid']] = array(
                    "name" => $_obfuscate_6A??['online']." ".$_obfuscate_6A??['truename'],
                    "sid" => $_obfuscate_6A??['stationid']
                );
            }
        }
        unset( $this->station[0] );
        if ( 0 < count( $_obfuscate_irK5Vac?['station'] ) )
        {
            $_obfuscate_kIVhqJk? = app::loadapp( "main", "user" )->api_getUserNamesByStationIds( $_obfuscate_irK5Vac?['station'], TRUE );
            if ( !is_array( $_obfuscate_kIVhqJk? ) )
            {
                $_obfuscate_kIVhqJk? = array( );
            }
            foreach ( $_obfuscate_kIVhqJk? as $_obfuscate_5w?? => $_obfuscate_6A?? )
            {
                $_obfuscate_6RYLWQ??[$_obfuscate_6A??['uid']] = array(
                    "name" => $_obfuscate_6A??['online']." ".$_obfuscate_6A??['truename'],
                    "sid" => $_obfuscate_6A??['stationid']
                );
            }
        }
        unset( $this->kong[0] );
        if ( 0 < count( $_obfuscate_irK5Vac?['kong'] ) )
        {
            $_obfuscate_OTcZiSgKrj_PeTG7Og?? = array( );
            if ( $this->flowFrom == "new" )
            {
                $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "odata" ), $this->t_set_field, "WHERE `id` IN (".implode( ",", $_obfuscate_irK5Vac?['kong'] ).") AND `otype`=\"macro\"" );
                if ( !is_array( $_obfuscate_mPAjEGLn ) )
                {
                    $_obfuscate_mPAjEGLn = array( );
                }
                foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A?? )
                {
                    $_obfuscate_6A?? = json_decode( str_replace( "'", "\"", $_obfuscate_6A??['odata'] ), TRUE );
                    if ( !( $_obfuscate_6A??['dataType'] == "loginname" ) )
                    {
                        continue;
                    }
                    $_obfuscate_OTcZiSgKrj_PeTG7Og??[] = $CNOA_SESSION->get( "UID" );
                    break;
                }
                unset( $_obfuscate_mPAjEGLn );
            }
            else
            {
                foreach ( $_obfuscate_irK5Vac?['kong'] as $_obfuscate_6A?? )
                {
                    $_obfuscate_YIq2A8c? = $GLOBALS['UWFCACHE']->getField( $_obfuscate_6A?? );
                    $_obfuscate_YIq2A8c? = json_decode( str_replace( "'", "\"", $_obfuscate_YIq2A8c?['odata'] ), TRUE );
                    if ( $_obfuscate_YIq2A8c?['dataType'] == "loginname" )
                    {
                        $_obfuscate_LRlbhQ?? = $CNOA_DB->db_getfield( "T_".$_obfuscate_6A??, "z_wf_t_".$this->flowId, "WHERE `uFlowId`=".$this->uFlowId );
                        if ( !is_numeric( $_obfuscate_LRlbhQ?? ) )
                        {
                            $_obfuscate_LRlbhQ?? = $CNOA_DB->db_getfield( "uid", "main_user", " WHERE `truename`='".$_obfuscate_LRlbhQ??."'" );
                        }
                        if ( empty( $_obfuscate_LRlbhQ?? ) )
                        {
                            $_obfuscate_OTcZiSgKrj_PeTG7Og??[] = $_obfuscate_YIq2A8c?['dataType'] == "loginname" ? $CNOA_SESSION->get( "UID" ) : 0;
                        }
                        else
                        {
                            $_obfuscate_OTcZiSgKrj_PeTG7Og??[] = $_obfuscate_LRlbhQ??;
                        }
                    }
                    if ( $_obfuscate_YIq2A8c?['dataType'] == "user_sel" )
                    {
                        $_obfuscate_pv5u3znz = $CNOA_DB->db_getfield( "hide", "wf_s_step_fields", " WHERE `stepId`=".$this->step." AND `flowId`={$this->flowId}" );
                        $_obfuscate_LRlbhQ?? = $CNOA_DB->db_getfield( "T_".$_obfuscate_6A??, "z_wf_t_".$this->flowId, "WHERE `uFlowId`=".$this->uFlowId );
                        if ( $_obfuscate_pv5u3znz == "1" )
                        {
                            $_obfuscate_OTcZiSgKrj_PeTG7Og??[] = $_obfuscate_LRlbhQ??;
                        }
                    }
                }
            }
            $_obfuscate_zuOY9j1YvA?? = getpar( $_POST, "user_sel", "" );
            $_obfuscate_fPA2ehKaoZcvfQ?? = array( );
            foreach ( explode( "|", $_obfuscate_zuOY9j1YvA?? ) as $_obfuscate_6A?? )
            {
                if ( !empty( $_obfuscate_6A?? ) )
                {
                    $_obfuscate_TAxu = explode( "=", $_obfuscate_6A?? );
                    if ( !empty( $_obfuscate_TAxu[1] ) )
                    {
                        $_obfuscate_Vwty = str_replace( "wf_field_", "", $_obfuscate_TAxu[0] );
                        $_obfuscate_fPA2ehKaoZcvfQ??[$_obfuscate_Vwty] = $_obfuscate_TAxu[1];
                    }
                }
            }
            foreach ( $_obfuscate_irK5Vac?['kong'] as $_obfuscate_6A?? )
            {
                if ( !empty( $_obfuscate_fPA2ehKaoZcvfQ??[$_obfuscate_6A??] ) )
                {
                    $_obfuscate_OTcZiSgKrj_PeTG7Og??[] = $_obfuscate_fPA2ehKaoZcvfQ??[$_obfuscate_6A??];
                }
            }
            if ( !empty( $_obfuscate_OTcZiSgKrj_PeTG7Og?? ) )
            {
                $_obfuscate_kIVhqJk? = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_OTcZiSgKrj_PeTG7Og?? );
                if ( !is_array( $_obfuscate_kIVhqJk? ) )
                {
                    $_obfuscate_kIVhqJk? = array( );
                }
                foreach ( $_obfuscate_kIVhqJk? as $_obfuscate_5w?? => $_obfuscate_6A?? )
                {
                    $_obfuscate_6RYLWQ??[$_obfuscate_6A??['uid']] = array(
                        "name" => $_obfuscate_6A??['online']." ".$_obfuscate_6A??['truename'],
                        "sid" => $_obfuscate_6A??['stationid']
                    );
                }
            }
        }
        unset( $this->dept[0] );
        if ( 0 < count( $_obfuscate_irK5Vac?['dept'] ) )
        {
            $_obfuscate_kIVhqJk? = app::loadapp( "main", "user" )->api_getUserNamesByDepts( $_obfuscate_irK5Vac?['dept'], "AND `isSystemUser`=1" );
            if ( !is_array( $_obfuscate_kIVhqJk? ) )
            {
                $_obfuscate_kIVhqJk? = array( );
            }
            foreach ( $_obfuscate_kIVhqJk? as $_obfuscate_5w?? => $_obfuscate_6A?? )
            {
                $_obfuscate_6RYLWQ??[$_obfuscate_6A??['uid']] = array(
                    "name" => $_obfuscate_6A??['online']." ".$_obfuscate_6A??['truename'],
                    "sid" => $_obfuscate_6A??['stationid']
                );
            }
        }
        unset( $this->rule[0] );
        if ( 0 < count( $_obfuscate_irK5Vac?['rule'] ) )
        {
            $_obfuscate_kIVhqJk? = array( );
            foreach ( $_obfuscate_irK5Vac?['rule'] as $_obfuscate_6A?? )
            {
                if ( $_obfuscate_6A??['rule_p'] == "faqi" )
                {
                    if ( $this->flowFrom == "new" )
                    {
                        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
                    }
                    else
                    {
                        $_obfuscate_7Ri3 = $CNOA_DB->db_getfield( "uid", $this->t_use_flow, "WHERE `uFlowId`='".$this->uFlowId."'" );
                    }
                }
                else
                {
                    if ( $_obfuscate_6A??['rule_p'] == "faqiself" )
                    {
                        if ( $this->flowFrom == "new" )
                        {
                            $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
                        }
                        else if ( !empty( $this->uFlowId ) )
                        {
                            $_obfuscate_7Ri3 = $CNOA_DB->db_getfield( "uid", $this->t_use_flow, "WHERE `uFlowId` = ".$this->uFlowId." " );
                        }
                        $_obfuscate_AmnGm1TM = app::loadapp( "main", "user" )->api_getUserNamesByUids( array(
                            $_obfuscate_7Ri3
                        ) );
                        $_obfuscate_kIVhqJk?[$_obfuscate_7Ri3] = array(
                            "truename" => $_obfuscate_AmnGm1TM[$_obfuscate_7Ri3]['online']." ".$_obfuscate_AmnGm1TM[$_obfuscate_7Ri3]['truename'],
                            "stationid" => $_obfuscate_AmnGm1TM[$_obfuscate_7Ri3]['stationid']
                        );
                    }
                    else if ( $_obfuscate_6A??['rule_p'] == "beforepeop" )
                    {
                        if ( !empty( $this->uFlowId ) )
                        {
                            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "dealUid" ), $this->t_use_step, "WHERE `uFlowId` = ".$this->uFlowId." AND `dealUid`!=0 AND (`status`=2 OR `status`=4) " );
                            if ( !is_array( $_obfuscate_mPAjEGLn ) )
                            {
                                $_obfuscate_mPAjEGLn = array( );
                            }
                            $_obfuscate_QivYy6_JUoI? = array( );
                            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_TAxu )
                            {
                                array_push( &$_obfuscate_QivYy6_JUoI?, $_obfuscate_TAxu['dealUid'] );
                            }
                            unset( $_obfuscate_mPAjEGLn );
                            $_obfuscate_QivYy6_JUoI? = array_unique( $_obfuscate_QivYy6_JUoI? );
                            $_obfuscate_m2Kuww?? = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_QivYy6_JUoI? );
                            if ( !is_array( $_obfuscate_m2Kuww?? ) )
                            {
                                $_obfuscate_m2Kuww?? = array( );
                            }
                            foreach ( $_obfuscate_m2Kuww?? as $_obfuscate_TAxu )
                            {
                                $_obfuscate_kIVhqJk?[$_obfuscate_TAxu['uid']] = array(
                                    "truename" => "{$_obfuscate_TAxu['online']} {$_obfuscate_TAxu['truename']}",
                                    "stationid" => $_obfuscate_TAxu['stationid']
                                );
                            }
                            unset( $_obfuscate_QivYy6_JUoI? );
                            unset( $_obfuscate_m2Kuww?? );
                            unset( $_obfuscate_TAxu );
                        }
                    }
                    else
                    {
                        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
                    }
                }
                $_obfuscate_vwGQSA?? = app::loadapp( "main", "user" )->api_getUserDeptIdByUid( $_obfuscate_7Ri3 );
                if ( $_obfuscate_6A??['rule_d'] == "myDept" )
                {
                    $_obfuscate_WHE3MRQ? = array(
                        $_obfuscate_vwGQSA??
                    );
                }
                else if ( $_obfuscate_6A??['rule_d'] == "upDept" )
                {
                    $_obfuscate_oZ6tCOQeBlI? = app::loadapp( "main", "struct" )->api_getInfoById( $_obfuscate_vwGQSA?? );
                    $_obfuscate_RjqCsdVYtio? = $_obfuscate_oZ6tCOQeBlI?['fid'];
                    $_obfuscate_WHE3MRQ? = array(
                        $_obfuscate_RjqCsdVYtio?
                    );
                }
                else if ( $_obfuscate_6A??['rule_d'] == "myUpDept" )
                {
                    $_obfuscate_oZ6tCOQeBlI? = app::loadapp( "main", "struct" )->api_getInfoById( $_obfuscate_vwGQSA?? );
                    $_obfuscate_RjqCsdVYtio? = $_obfuscate_oZ6tCOQeBlI?['fid'];
                    $_obfuscate_WHE3MRQ? = array(
                        $_obfuscate_vwGQSA??,
                        $_obfuscate_RjqCsdVYtio?
                    );
                }
                else if ( $_obfuscate_6A??['rule_d'] == "allDept" )
                {
                    $_obfuscate_oZ6tCOQeBlI? = app::loadapp( "main", "struct" )->api_getInfoById( $_obfuscate_vwGQSA?? );
                    $_obfuscate_WHE3MRQ? = explode( ",", $_obfuscate_oZ6tCOQeBlI?['path'] );
                }
                if ( !empty( $_obfuscate_WHE3MRQ? ) )
                {
                    $_obfuscate_Jrp1 = app::loadapp( "main", "user" )->api_getUserNamesByDepts( $_obfuscate_WHE3MRQ?, "AND `stationid`='".$_obfuscate_6A??['rule_s']."'" );
                    if ( !is_array( $_obfuscate_Jrp1 ) )
                    {
                        $_obfuscate_Jrp1 = array( );
                    }
                    foreach ( $_obfuscate_Jrp1 as $_obfuscate_ClA? => $_obfuscate_bRQ? )
                    {
                        $_obfuscate_kIVhqJk?[$_obfuscate_bRQ?['uid']] = array(
                            "truename" => $_obfuscate_bRQ?['online']." ".$_obfuscate_bRQ?['truename'],
                            "stationid" => $_obfuscate_bRQ?['stationid']
                        );
                    }
                }
            }
            foreach ( $_obfuscate_kIVhqJk? as $_obfuscate_nJg? => $_obfuscate_NZM? )
            {
                if ( $_obfuscate_nJg? != "" )
                {
                    $_obfuscate_6RYLWQ??[$_obfuscate_nJg?] = array(
                        "name" => $_obfuscate_NZM?['truename'],
                        "sid" => $_obfuscate_NZM?['stationid']
                    );
                }
            }
        }
        unset( $this->deptstation[0] );
        if ( 0 < count( $_obfuscate_irK5Vac?['deptstation'] ) )
        {
            foreach ( $_obfuscate_irK5Vac?['deptstation'] as $_obfuscate_6A?? )
            {
                $_obfuscate_PIH1pKEr_jw? = app::loadapp( "main", "user" )->api_getUserNamesByDeptSid( $_obfuscate_6A??['dept'], $_obfuscate_6A??['station'] );
                if ( is_array( $_obfuscate_PIH1pKEr_jw? ) )
                {
                    foreach ( $_obfuscate_PIH1pKEr_jw? as $_obfuscate_EGU? )
                    {
                        $_obfuscate_6RYLWQ??[$_obfuscate_EGU?['uid']] = array(
                            "name" => $_obfuscate_EGU?['online']." ".$_obfuscate_EGU?['truename'],
                            "sid" => $_obfuscate_EGU?['stationid']
                        );
                    }
                }
            }
        }
        $_obfuscate_BMzc5OMhC1w? = array( );
        $_obfuscate__eqrEQ?? = array_keys( $_obfuscate_6RYLWQ?? );
        foreach ( $_obfuscate__eqrEQ?? as $_obfuscate_Vwty => $_obfuscate_VgKtFeg? )
        {
            if ( empty( $_obfuscate_VgKtFeg? ) )
            {
                unset( $_obfuscate__eqrEQ??[$_obfuscate_Vwty] );
            }
        }
        if ( !empty( $_obfuscate__eqrEQ?? ) )
        {
            $_obfuscate__eqrEQ?? = implode( ",", $_obfuscate__eqrEQ?? );
            $_obfuscate__eqrEQ?? = $CNOA_DB->db_select( array( "uid" ), "main_user", "WHERE uid IN (".$_obfuscate__eqrEQ??.") AND isSystemUser=1 ORDER BY `order`" );
            if ( is_array( $_obfuscate__eqrEQ?? ) )
            {
                $_obfuscate_t_AneST3vUnsuw? = app::loadapp( "main", "station" )->api_getStationList( TRUE );
                $_obfuscate_m2Kuww?? = array( );
                foreach ( $_obfuscate__eqrEQ?? as $_obfuscate_6A?? )
                {
                    $_obfuscate_m2Kuww?? = $_obfuscate_6RYLWQ??[$_obfuscate_6A??['uid']];
                    $_obfuscate_BMzc5OMhC1w?[] = array(
                        "uid" => $_obfuscate_6A??['uid'],
                        "name" => $_obfuscate_m2Kuww??['name'],
                        "sname" => $_obfuscate_t_AneST3vUnsuw?[$_obfuscate_m2Kuww??['sid']]['name']
                    );
                }
            }
        }
        return $_obfuscate_BMzc5OMhC1w?;
    }

    private function __ckConditionItem( $_obfuscate_LQ8UKg??, $_obfuscate_JQJwE4USnB0?, $_obfuscate_StroP1Q? )
    {
        global $CNOA_DB;
        $_obfuscate_YIq2A8c? = array( );
        if ( in_array( $_obfuscate_LQ8UKg??['fieldType'], array( "n_n", "n_s", "n_d", "d_n", "d_s", "d_d" ) ) )
        {
            $_obfuscate_VgKtFeg? = $_obfuscate_StroP1Q?[$_obfuscate_LQ8UKg??['fieldType']];
            $_obfuscate_aIl32BH1 = $_obfuscate_LQ8UKg??['ovalue'];
        }
        else
        {
            $_obfuscate_XcDSeZqY = $_obfuscate_LQ8UKg??['name'];
            $_obfuscate_VgKtFeg? = $_obfuscate_JQJwE4USnB0?[$_obfuscate_LQ8UKg??['name']];
            $_obfuscate_aIl32BH1 = $_obfuscate_LQ8UKg??['ovalue'];
            if ( $this->flowFrom == "new" )
            {
                $_obfuscate_YIq2A8c? = $CNOA_DB->db_getone( "*", $this->t_set_field, "WHERE `id`='".$_obfuscate_XcDSeZqY."'" );
            }
            else if ( $this->flowFrom == "todo" )
            {
                $_obfuscate_YIq2A8c? = $GLOBALS['UWFCACHE']->getField( $_obfuscate_XcDSeZqY );
            }
            $_obfuscate_p5ZWxr4? = $this->__getOdata( $_obfuscate_YIq2A8c?['odata'] );
            $_obfuscate_42oUddZOsSA? = $_obfuscate_p5ZWxr4?['dataType'];
            if ( in_array( $_obfuscate_42oUddZOsSA?, array( "int", "float" ) ) )
            {
                $_obfuscate_VgKtFeg? = $this->_getFloatNumber( $_obfuscate_VgKtFeg? );
                $_obfuscate_aIl32BH1 = $this->_getFloatNumber( $_obfuscate_aIl32BH1 );
            }
            if ( preg_match( "/wk[1-7]/is", $_obfuscate_aIl32BH1 ) )
            {
                $_obfuscate_88qmOWYg07So = datetotimestamp( $_obfuscate_VgKtFeg? );
                $_obfuscate_VgKtFeg? = timestamptoweek( $_obfuscate_88qmOWYg07So );
                $_obfuscate_aIl32BH1 = intval( str_replace( "wk", "", $_obfuscate_aIl32BH1 ) );
            }
            if ( $_obfuscate_42oUddZOsSA? == "loginname" )
            {
                $_obfuscate_VgKtFeg? = app::loadapp( "main", "user" )->api_getUserNameByUids( intval( $_obfuscate_VgKtFeg? ) );
            }
        }
        $_obfuscate_5o2pI12W5Q?? = FALSE;
        switch ( $_obfuscate_LQ8UKg??['rule'] )
        {
        case 1 :
            $_obfuscate_5o2pI12W5Q?? = $_obfuscate_VgKtFeg? == $_obfuscate_aIl32BH1;
            break;
        case 2 :
            $_obfuscate_5o2pI12W5Q?? = $_obfuscate_VgKtFeg? != $_obfuscate_aIl32BH1;
            break;
        case 3 :
            $_obfuscate_5o2pI12W5Q?? = $_obfuscate_aIl32BH1 < $_obfuscate_VgKtFeg?;
            break;
        case 4 :
            $_obfuscate_5o2pI12W5Q?? = $_obfuscate_VgKtFeg? < $_obfuscate_aIl32BH1;
            break;
        case 5 :
            $_obfuscate_5o2pI12W5Q?? = $_obfuscate_aIl32BH1 <= $_obfuscate_VgKtFeg?;
            break;
        case 6 :
            $_obfuscate_5o2pI12W5Q?? = $_obfuscate_VgKtFeg? <= $_obfuscate_aIl32BH1;
            break;
        case 7 :
            $_obfuscate_5o2pI12W5Q?? = 0 < substr_count( $_obfuscate_VgKtFeg?, $_obfuscate_aIl32BH1 );
            break;
        case 8 :
            $_obfuscate_5o2pI12W5Q?? = substr_count( $_obfuscate_VgKtFeg?, $_obfuscate_aIl32BH1 ) <= 0;
            break;
        default :
            $_obfuscate_5o2pI12W5Q?? = FALSE;
        }
        if ( $_obfuscate_YIq2A8c?['otype'] == "checkbox" )
        {
            $_obfuscate_5o2pI12W5Q?? = $this->_check1278( $_obfuscate_LQ8UKg??['rule'], $_obfuscate_VgKtFeg?, $_obfuscate_aIl32BH1 );
        }
        if ( in_array( $_obfuscate_p5ZWxr4?['dataType'], array( "dept_sel", "depts_sel", "station_sel", "stations_sel", "job_sel", "jobs_sel", "user_sel", "users_sel" ) ) )
        {
            $_obfuscate_CvEPg8ITxMR3 = array( );
            $_obfuscate_VgKtFeg? = empty( $_obfuscate_VgKtFeg? ) ? 0 : $_obfuscate_VgKtFeg?;
            switch ( $_obfuscate_p5ZWxr4?['dataType'] )
            {
            case "dept_sel" :
                $_obfuscate_CvEPg8ITxMR3[] = app::loadapp( "main", "struct" )->api_getNameById( $_obfuscate_VgKtFeg? );
                break;
            case "depts_sel" :
                $_obfuscate_CvEPg8ITxMR3 = app::loadapp( "main", "struct" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg? ) );
                break;
            case "station_sel" :
                $_obfuscate_CvEPg8ITxMR3[] = app::loadapp( "main", "station" )->api_getNameById( $_obfuscate_VgKtFeg? );
                break;
            case "stations_sel" :
                $_obfuscate_CvEPg8ITxMR3 = app::loadapp( "main", "station" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg? ) );
                break;
            case "job_sel" :
                $_obfuscate_CvEPg8ITxMR3[] = app::loadapp( "main", "job" )->api_getNameByUid( $_obfuscate_VgKtFeg? );
                break;
            case "jobs_sel" :
                $_obfuscate_CvEPg8ITxMR3 = app::loadapp( "main", "job" )->api_getNamesByIds( explode( ",", $_obfuscate_VgKtFeg? ) );
                break;
            case "user_sel" :
                $_obfuscate_CvEPg8ITxMR3[] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_VgKtFeg? );
                break;
            case "users_sel" :
                $_obfuscate_7rU5WM0? = app::loadapp( "main", "user" )->api_getUserNamesByUids( explode( ",", $_obfuscate_VgKtFeg? ) );
                foreach ( $_obfuscate_7rU5WM0? as $_obfuscate_MFc? )
                {
                    $_obfuscate_CvEPg8ITxMR3[] = $_obfuscate_MFc?['truename'];
                }
            }
            $_obfuscate_CvEPg8ITxMR3 = array_merge( $_obfuscate_CvEPg8ITxMR3 );
            $_obfuscate_5o2pI12W5Q?? = $this->_check1278( $_obfuscate_LQ8UKg??['rule'], $_obfuscate_CvEPg8ITxMR3, $_obfuscate_aIl32BH1 );
        }
        if ( $_obfuscate_5o2pI12W5Q?? )
        {
            return "true";
        }
        return "false";
    }

    private function _check1278( $_obfuscate_6mlyHg??, $_obfuscate_VgKtFeg?, $_obfuscate_aIl32BH1 )
    {
        $_obfuscate_5o2pI12W5Q?? = FALSE;
        if ( !is_array( $_obfuscate_VgKtFeg? ) )
        {
            $_obfuscate_VgKtFeg? = array( );
        }
        switch ( $_obfuscate_6mlyHg?? )
        {
        case 1 :
            if ( count( $_obfuscate_VgKtFeg? ) == 1 )
            {
                $_obfuscate_5o2pI12W5Q?? = $_obfuscate_VgKtFeg?[0] == $_obfuscate_aIl32BH1;
                return $_obfuscate_5o2pI12W5Q??;
            }
            $_obfuscate_5o2pI12W5Q?? = FALSE;
            return $_obfuscate_5o2pI12W5Q??;
        case 2 :
            if ( count( $_obfuscate_VgKtFeg? ) == 1 )
            {
                $_obfuscate_5o2pI12W5Q?? = $_obfuscate_VgKtFeg?[0] != $_obfuscate_aIl32BH1;
                return $_obfuscate_5o2pI12W5Q??;
            }
            $_obfuscate_5o2pI12W5Q?? = FALSE;
            return $_obfuscate_5o2pI12W5Q??;
        case 7 :
            $_obfuscate_S3mD = 0;
            foreach ( $_obfuscate_VgKtFeg? as $_obfuscate_6A?? )
            {
                $_obfuscate_S3mD += substr_count( $_obfuscate_6A??, $_obfuscate_aIl32BH1 );
            }
            $_obfuscate_5o2pI12W5Q?? = 0 < $_obfuscate_S3mD ? TRUE : FALSE;
            return $_obfuscate_5o2pI12W5Q??;
        case 8 :
            $_obfuscate_S3mD = 0;
            foreach ( $_obfuscate_VgKtFeg? as $_obfuscate_6A?? )
            {
                $_obfuscate_S3mD += substr_count( $_obfuscate_6A??, $_obfuscate_aIl32BH1 );
            }
            $_obfuscate_5o2pI12W5Q?? = $_obfuscate_S3mD <= 0 ? TRUE : FALSE;
        }
        return $_obfuscate_5o2pI12W5Q??;
    }

    private function _getFloatNumber( $_obfuscate_VgKtFeg? )
    {
        $_obfuscate_VgKtFeg? = str_replace( ",", "", $_obfuscate_VgKtFeg? );
        $_obfuscate_VgKtFeg? = floatval( $_obfuscate_VgKtFeg? );
        return $_obfuscate_VgKtFeg?;
    }

    private function _convergenceUname( $_obfuscate_BQjjgu6Bg? )
    {
        global $CNOA_DB;
        if ( $this->flowFrom == "new" )
        {
            $_obfuscate_dw4x = $CNOA_DB->db_getfield( "flowXml", $this->t_set_flow, "WHERE `flowId` = ".$this->flowId );
        }
        else if ( $this->flowFrom == "todo" )
        {
            $_obfuscate_dw4x = $GLOBALS['UWFCACHE']->getFlowXML( );
        }
        $_obfuscate_sc7AoZlouuA? = xml2array( stripslashes( $_obfuscate_dw4x ), 1, "mxGraphModel" );
        $_obfuscate_ww9OEn9sSs4? = $_obfuscate_sc7AoZlouuA?['mxGraphModel']['root']['mxCell'];
        unset( $_obfuscate_dw4x );
        unset( $_obfuscate_sc7AoZlouuA? );
        $_obfuscate_h_MzZw?? = "";
        $_obfuscate_lJp8wA?? = array( );
        $_obfuscate_FcvH8FD7nIf1tXFjmw?? = 0;
        foreach ( $_obfuscate_ww9OEn9sSs4? as $_obfuscate_VBCv7Q?? )
        {
            $_obfuscate_4C_dDw?? = $_obfuscate_VBCv7Q??['attr'];
            if ( $_obfuscate_4C_dDw??['id'] == $_obfuscate_BQjjgu6Bg? )
            {
                $_obfuscate_h_MzZw?? = str_replace( "bingfa", "", $_obfuscate_4C_dDw??['mark'] );
            }
            if ( $_obfuscate_4C_dDw??['nodeType'] == "cNode" )
            {
                $_obfuscate_lJp8wA??[$_obfuscate_4C_dDw??['id']] = $_obfuscate_4C_dDw??['mark'];
            }
        }
        foreach ( $_obfuscate_lJp8wA?? as $_obfuscate_5w?? => $_obfuscate_6A?? )
        {
            if ( !( $_obfuscate_h_MzZw?? == str_replace( "convergence", "", $_obfuscate_6A?? ) ) )
            {
                continue;
            }
            $_obfuscate_FcvH8FD7nIf1tXFjmw?? = $_obfuscate_5w??;
            break;
        }
        unset( $_obfuscate_ww9OEn9sSs4? );
        unset( $_obfuscate_h_MzZw?? );
        unset( $_obfuscate_lJp8wAA? );
        if ( $this->flowFrom == "new" )
        {
            $_obfuscate_o_IRkEDpxeo? = $CNOA_DB->db_select( "*", $this->t_set_step_user, "WHERE `flowId`=".$this->flowId." AND `stepId`={$_obfuscate_FcvH8FD7nIf1tXFjmw??}" );
        }
        else if ( $this->flowFrom == "todo" )
        {
            $_obfuscate_o_IRkEDpxeo? = $GLOBALS['UWFCACHE']->getStepUserByStepId( $_obfuscate_FcvH8FD7nIf1tXFjmw?? );
        }
        if ( !is_array( $_obfuscate_o_IRkEDpxeo? ) )
        {
            $_obfuscate_o_IRkEDpxeo? = array( );
        }
        $_obfuscate_6RYLWQ?? = $this->_getOperatorsByStepInfo( $_obfuscate_o_IRkEDpxeo? );
        $_obfuscate_flKx1g?? = array( );
        foreach ( $_obfuscate_o_IRkEDpxeo? as $_obfuscate_6A?? )
        {
            if ( $_obfuscate_6A??['type'] == "kong" )
            {
                array_push( &$_obfuscate_flKx1g??, $_obfuscate_6A??['kong'] );
            }
        }
        if ( 0 < count( $_obfuscate_flKx1g?? ) )
        {
            $_obfuscate_flKx1g?? = array_unique( $_obfuscate_flKx1g?? );
            if ( $this->flowFrom == "new" )
            {
                $_obfuscate_flKx1g?? = implode( ",", $_obfuscate_flKx1g?? );
                $_obfuscate_tjILu7ZH = $CNOA_DB->db_select( array( "id", "name" ), $this->t_set_field, "WHERE `id` IN (".$_obfuscate_flKx1g??.")" );
            }
            else if ( $this->flowFrom == "todo" )
            {
                $_obfuscate_8MSmTrf2URD2fg?? = $GLOBALS['UWFCACHE']->getStepFields( $_obfuscate_FcvH8FD7nIf1tXFjmw??, self::FIELD_RULE_NORMAL );
                foreach ( $_obfuscate_8MSmTrf2URD2fg?? as $_obfuscate_YIq2A8c? )
                {
                    if ( in_array( $_obfuscate_YIq2A8c?['fieldId'], $_obfuscate_flKx1g?? ) )
                    {
                        $_obfuscate_tjILu7ZH[] = $GLOBALS['UWFCACHE']->getField( $_obfuscate_YIq2A8c?['fieldId'] );
                    }
                }
            }
            if ( !is_array( $_obfuscate_tjILu7ZH ) )
            {
                $_obfuscate_tjILu7ZH = array( );
            }
            foreach ( $_obfuscate_tjILu7ZH as $_obfuscate_YIq2A8c? )
            {
                $_obfuscate_6RYLWQ??[] = array(
                    "uid" => 0,
                    "oid" => "k".$_obfuscate_YIq2A8c?['id'],
                    "name" => "{$_obfuscate_YIq2A8c?['name']}",
                    "sname" => "??бь???"
                );
            }
        }
        return $_obfuscate_6RYLWQ??;
    }

}

?>
