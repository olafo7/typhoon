<?php

abstract class wfSendNextStep extends wfCommon
{

    protected $uid = 0;
    protected $flowType = NULL;
    protected $tplSort = NULL;
    protected $flowNumber = NULL;
    protected $flowName = NULL;
    protected $flowId = NULL;
    protected $flowInfo = NULL;
    protected $uFlowId = NULL;
    protected $uFlowInfo = NULL;
    protected $stepId = NULL;
    protected $stepInfo = NULL;
    protected $nextStepId = NULL;
    protected $nextStepInfo = NULL;
    protected $nextStepUid = NULL;
    protected $allNextStepUid = array( );
    protected $allNextStepInfo = array( );
    protected $convergenenUid = "";
    protected $flow = NULL;
    protected $from = NULL;

    public function __construct( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $this->uid = $CNOA_SESSION->get( "UID" );
        $this->nextStepId = ( integer )getpar( $_POST, "nextStepId" );
        $this->convergenenUid = getpar( $_POST, "convergenenUid", "" );
        $_obfuscate_otG_kFN1GSY? = getpar( $_POST, "nextUid", 0 );
        if ( $this instanceof wfTodoSendNextStep )
        {
            $this->from = "todo";
        }
        else if ( $this instanceof wfNewSendNextStep )
        {
            $this->from = "new";
        }
        else
        {
            msg::callback( FALSE, lang( "flowSendFailure" ) );
        }
        $_obfuscate_otG_kFN1GSY? = explode( ",", $_obfuscate_otG_kFN1GSY? );
        $_obfuscate_mc2H = count( $_obfuscate_otG_kFN1GSY? );
        if ( $_obfuscate_mc2H == 0 )
        {
            msg::callback( FALSE, lang( "chooseStepToDeal" ) );
        }
        else if ( $_obfuscate_mc2H == 1 )
        {
            $this->nextStepUid = $_obfuscate_otG_kFN1GSY?[0];
        }
        else
        {
            $_obfuscate_7w?? = 0;
            for ( ; $_obfuscate_7w?? < $_obfuscate_mc2H; $_obfuscate_7w?? += 2 )
            {
                $this->allNextStepUid[$_obfuscate_otG_kFN1GSY?[$_obfuscate_7w??]] = $_obfuscate_otG_kFN1GSY?[$_obfuscate_7w?? + 1];
            }
        }
        unset( $_obfuscate_mc2H );
        unset( $_obfuscate_otG_kFN1GSY? );
        $this->_checkFlow( );
        ( );
        $_obfuscate_yHckwJMI = new wfEngine( );
        if ( ( $_obfuscate_A1jN = $_obfuscate_yHckwJMI->verify( $this->from, $this->flowId, $this->uFlowId ) ) !== TRUE )
        {
            msg::callback( FALSE, $_obfuscate_A1jN );
        }
        unset( $_obfuscate_yHckwJMI );
    }

    protected function _checkFlow( )
    {
        if ( $this->nextStepId === 0 )
        {
            msg::callback( FALSE, lang( "selectNext" ) );
        }
        if ( $this->from == "new" )
        {
            if ( $this->flowId === 0 )
            {
                msg::callback( FALSE, lang( "flowSendFailure" ) );
            }
        }
        else if ( $this->from == "todo" )
        {
            if ( $this->uFlowId === 0 || $this->stepId === 0 )
            {
                msg::callback( FALSE, lang( "flowSendFailure" ) );
            }
            global $CNOA_DB;
            $this->uFlowInfo = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`=".$this->uFlowId );
            $_obfuscate_SeV31Q?? = array( );
            $_obfuscate_1TWdhZly3Est8w8? = $CNOA_DB->db_select( array( "id" ), $this->t_set_field, "WHERE `otype`='attach' AND flowId=".$this->uFlowInfo['flowId'] );
            if ( !is_array( $_obfuscate_1TWdhZly3Est8w8? ) )
            {
                $_obfuscate_1TWdhZly3Est8w8? = array( );
            }
            foreach ( $_obfuscate_1TWdhZly3Est8w8? as $_obfuscate_Vwty => $_obfuscate_VgKtFeg? )
            {
                $_obfuscate_8CpDPPa = $CNOA_DB->db_getfield( "T_".$_obfuscate_VgKtFeg?['id'], "z_wf_t_".$this->uFlowInfo['flowId'], "WHERE `uFlowId`=".$this->uFlowId );
                if ( $_obfuscate_8CpDPPa )
                {
                    $_obfuscate_WPvkSFEMg?? .= substr( $_obfuscate_8CpDPPa, 1, -1 ).",";
                }
            }
            if ( $this->uFlowInfo['attach'] )
            {
                $_obfuscate_MAduV4GmcAN9 = substr( $this->uFlowInfo['attach'], 1, -1 );
                $this->uFlowInfo['attach'] = "[".$_obfuscate_WPvkSFEMg??.$_obfuscate_MAduV4GmcAN9."]";
            }
            else
            {
                $this->uFlowInfo['attach'] = "[".substr( $_obfuscate_WPvkSFEMg??, 0, -1 )."]";
            }
            $_obfuscate_6b8lIO4y = $CNOA_DB->db_getfield( "status", $this->t_use_step, "WHERE `uFlowId`=".$this->uFlowId." AND `uStepId`={$this->stepId} AND (`uid`={$this->uid} OR `proxyUid`={$this->uid}) ORDER BY `id` DESC " );
            if ( in_array( $_obfuscate_6b8lIO4y, array(
                self::STEP_STATUS_DONE,
                self::STEP_STATUS_RESERVATION
            ) ) )
            {
                msg::callback( FALSE, lang( "flowBeenDealtNotOpt" ) );
            }
            if ( $this->uFlowInfo['status'] == self::FLOW_STATUS_BACKOFF )
            {
                msg::callback( FALSE, lang( "flowBeenSuspendNotOpt" ) );
            }
            if ( $this->uFlowInfo['status'] == self::FLOW_STATUS_BACKOUT )
            {
                msg::callback( FALSE, lang( "flowBeenRevokNotOpt" ) );
            }
        }
    }

    protected function _saveData( $_obfuscate_uGltphXQjCRWoA?? = "", $_obfuscate_0cocFTVhmhKt8lw? = "" )
    {
        global $CNOA_DB;
        $_obfuscate_IuoXR2yOaxkRDw?? = array( );
        if ( $this->tplSort == 0 || $this->tplSort == 3 )
        {
            $_obfuscate_DZrwJy4LZQ?? = $this->_saveFormFieldInfo( $_obfuscate_uGltphXQjCRWoA??, $_obfuscate_0cocFTVhmhKt8lw?, $this->uFlowId );
            $_obfuscate_IuoXR2yOaxkRDw?? = $_obfuscate_DZrwJy4LZQ??[0];
            $_obfuscate_JQJwE4USnB0? = $_obfuscate_DZrwJy4LZQ??[1];
            $_obfuscate_dGoPOiQ2Iw5a = $_obfuscate_DZrwJy4LZQ??[2];
            $_obfuscate_BqBV6WSz3wel0ZDw = $_obfuscate_DZrwJy4LZQ??[3];
            $_obfuscate_piwqe2DnH9mIPU0P = $_obfuscate_DZrwJy4LZQ??[4];
            $this->_makeDetailMaxCache( $_obfuscate_piwqe2DnH9mIPU0P );
            if ( is_a( $this, "wfNewSendNextStep" ) )
            {
                $this->api_saveFieldData( $this->flowId, $this->uFlowId, $_obfuscate_JQJwE4USnB0?, $_obfuscate_dGoPOiQ2Iw5a, $_obfuscate_BqBV6WSz3wel0ZDw, "new", $this->uFlowInfo );
            }
            else if ( is_a( $this, "wfTodoSendNextStep" ) )
            {
                $this->api_saveFieldData( $this->flowId, $this->uFlowId, $_obfuscate_JQJwE4USnB0?, $_obfuscate_dGoPOiQ2Iw5a, $_obfuscate_BqBV6WSz3wel0ZDw );
            }
            unset( $_obfuscate_JQJwE4USnB0? );
            unset( $_obfuscate_dGoPOiQ2Iw5a );
            unset( $_obfuscate_BqBV6WSz3wel0ZDw );
            unset( $_obfuscate_BqBV6WSz3wel0ZDw );
            $this->_checkChildFlow( $this->uFlowId, $this->flowId, $this->stepId );
        }
        else
        {
            foreach ( $GLOBALS['_POST'] as $_obfuscate_5w?? => $_obfuscate_6A?? )
            {
                if ( ereg( "wf_attach_", $_obfuscate_5w?? ) )
                {
                    $_obfuscate_IuoXR2yOaxkRDw??[] = intval( str_replace( "wf_attach_", "", $_obfuscate_5w?? ) );
                }
            }
        }
        if ( $this->stepInfo['allowAttachAdd'] == 1 || $this->stepInfo['allowAttachDelete'] == 1 )
        {
            $_obfuscate_8CpDPPa = $this->handleAttach( $_obfuscate_IuoXR2yOaxkRDw?? );
        }
        $_obfuscate_s8yswCWZEIY? = getpar( $_GET, "otherApp", 0 );
        if ( !empty( $_obfuscate_s8yswCWZEIY? ) )
        {
            $CNOA_DB->db_update( array(
                "uFlowId" => $this->uFlowId
            ), $this->t_s_flow_other_app_data, "WHERE `id` = ".$_obfuscate_s8yswCWZEIY?." " );
        }
    }

    protected function handleAttach( $_obfuscate_IuoXR2yOaxkRDw?? )
    {
        $_obfuscate_8CpDPPa = "";
        ( );
        $_obfuscate_2gg? = new fs( );
        $_obfuscate_vCKayYE4IxP3uvQw0A?? = json_decode( $this->uFlowInfo['attach'], TRUE );
        if ( !is_array( $_obfuscate_vCKayYE4IxP3uvQw0A?? ) )
        {
            $_obfuscate_vCKayYE4IxP3uvQw0A?? = array( );
        }
        $_obfuscate_1ERfSWbp = $_obfuscate_2gg?->uploadFile4Wf( $_obfuscate_IuoXR2yOaxkRDw??, $_obfuscate_vCKayYE4IxP3uvQw0A?? );
        $_obfuscate_8CpDPPa = json_encode( $_obfuscate_1ERfSWbp[0] );
        unset( $_obfuscate_2gg? );
        unset( $_obfuscate_vCKayYE4IxP3uvQw0A?? );
        unset( $_obfuscate_1ERfSWbp );
        $this->flow['attach'] = $_obfuscate_8CpDPPa;
    }

    private function _makeDetailMaxCache( $_obfuscate_piwqe2DnH9mIPU0P )
    {
        $_obfuscate_6hS1Rw?? = CNOA_PATH_FILE."/common/wf/detailmaxcache/";
        @mkdirs( $_obfuscate_6hS1Rw?? );
        $_obfuscate_R2_b = "<?php\r\n";
        $_obfuscate_R2_b .= "return ".var_export( $_obfuscate_piwqe2DnH9mIPU0P, TRUE ).";";
        @file_put_contents( $_obfuscate_6hS1Rw??.$this->uFlowId.".php", $_obfuscate_R2_b );
    }

    protected function addEvent( $_obfuscate_1l6P = "", $_obfuscate_Kcrl6ZeKv4? = "" )
    {
        global $CNOA_DB;
        $_obfuscate_JG8GuY? = array( );
        $_obfuscate_JG8GuY?['uFlowId'] = $this->uFlowId;
        $_obfuscate_JG8GuY?['step'] = $this->stepId;
        $_obfuscate_JG8GuY?['uid'] = $this->uid;
        $_obfuscate_JG8GuY?['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuY?['say'] = $_obfuscate_1l6P;
        $_obfuscate_JG8GuY?['stepname'] = $this->stepInfo['stepName'];
        if ( $this->stepInfo['stepType'] == 1 )
        {
            $_obfuscate_JG8GuY?['type'] = 1;
        }
        else if ( $this->stepInfo['stepType'] == 4 )
        {
            $_obfuscate_JG8GuY?['type'] = 2;
        }
        else if ( $_obfuscate_Kcrl6ZeKv4? == "agree" )
        {
            $_obfuscate_JG8GuY?['type'] = 2;
        }
        else
        {
            $_obfuscate_JG8GuY?['type'] = 9;
        }
        $this->insertEvent( $_obfuscate_JG8GuY? );
        if ( $this->nextStepInfo['stepType'] == 3 )
        {
            $_obfuscate_JG8GuY?['step'] = $this->nextStepId;
            $_obfuscate_JG8GuY?['say'] = "";
            $_obfuscate_JG8GuY?['stepname'] = $this->nextStepInfo['stepName'];
            $_obfuscate_JG8GuY?['type'] = 6;
            $this->insertEvent( $_obfuscate_JG8GuY? );
        }
    }

    protected function startEngine( $_obfuscate_wD9kdBY? = "" )
    {
        global $CNOA_DB;
        $_obfuscate_MyQzpf7Jw? = $this->flowInfo['bindfunction'];
        if ( empty( $_obfuscate_MyQzpf7Jw? ) )
        {
            return;
        }
        $_obfuscate_wZ6MPP0? = "wfEngine".ucfirst( $_obfuscate_MyQzpf7Jw? );
        if ( !class_exists( $_obfuscate_wZ6MPP0? ) )
        {
            return;
        }
        if ( $_obfuscate_MyQzpf7Jw? == "salaryApprove" )
        {
            $_obfuscate_qXF5WAWqYSI? = getpar( $_GET, "salaryApproveId" );
            $_obfuscate_tunzLxLK = getpar( $_GET, "salaryOldUflowId" );
            if ( !empty( $_obfuscate_qXF5WAWqYSIAVZHSWZ9H ) )
            {
                $CNOA_DB->db_update( array(
                    "uFlowId" => $_obfuscate_tunzLxLK
                ), "salary_manage_approve", " WHERE `id`=".$_obfuscate_qXF5WAWqYSI? );
            }
        }
        if ( $_obfuscate_MyQzpf7Jw? == "userReadlySubmit" || $_obfuscate_MyQzpf7Jw? == "userSubmit" )
        {
            $_obfuscate_0eI4mradFg?? = ( integer )getpar( $_GET, "userCid" );
        }
        if ( $_obfuscate_MyQzpf7Jw? == "newsNotice" )
        {
            $_obfuscate_0muy1AMpeuH = ( integer )getpar( $_GET, "noticeLid" );
            $_obfuscate_DoJ0U_BJSGiVrBuNnUtLQ?? = ( integer )getpar( $_GET, "noticeOldUflowId" );
        }
        $_obfuscate_BOv37ISEbxxb04w9 = $this->nextStepInfo['stepType'];
        $this->allNextStepUid && $this->nextStepUid == "" ? ( $this->nextStepUid = $this->allNextStepUid ) : ( $this->nextStepUid = $this->nextStepUid );
        if ( $_obfuscate_MyQzpf7Jw? == "abutment" )
        {
            ( $_obfuscate_wD9kdBY?, $this->flowId, $this->uFlowId, $this->stepId, $this->nextStepUid );
            $_obfuscate_xpLrRbzlFasmoAmUf7ud7A?? = new wfAbutmentEngine( );
        }
        else
        {
            ( );
            $_obfuscate_7eRCEirmYGaYE1FJCfc0hw?? = new $_obfuscate_wZ6MPP0?( );
            $_obfuscate_7eRCEirmYGaYE1FJCfc0hw??->setBusinessData( $_obfuscate_wD9kdBY?, $this->uFlowId, $this->stepId, $this->nextStepUid, $_obfuscate_qXF5WAWqYSI?, $_obfuscate_tunzLxLK, $_obfuscate_0eI4mradFg??, $_obfuscate_0muy1AMpeuH, $_obfuscate_DoJ0U_BJSGiVrBuNnUtLQ??, $_obfuscate_BOv37ISEbxxb04w9 );
        }
    }

    protected function stepOfOther( )
    {
        global $CNOA_DB;
        $_obfuscate_VBCv7Q?? = array( );
        $_obfuscate_rFR1zydgg?? = $CNOA_DB->db_getfield( "deal", $this->t_use_deal_way, "WHERE `stepId`=".$this->nextStepId." AND `flowId`={$this->flowId}" );
        if ( !empty( $this->allNextStepUid ) || $_obfuscate_rFR1zydgg?? == 1 )
        {
            foreach ( $this->allNextStepUid as $_obfuscate_6A?? )
            {
                $_obfuscate_UTaQ0tZbOZ2slLemr40_Q?? = $this->getProxyUid( $this->flowId, $_obfuscate_6A?? );
                $_obfuscate_VBCv7Q??['uFlowId'] = $this->uFlowId;
                $_obfuscate_VBCv7Q??['sortId'] = $this->flowInfo['sortId'];
                $_obfuscate_VBCv7Q??['uStepId'] = $this->nextStepId;
                $_obfuscate_VBCv7Q??['uid'] = $this->nextStepInfo['stepType'] == 4 ? $this->uid : $_obfuscate_6A??;
                $_obfuscate_VBCv7Q??['proxyUid'] = $this->nextStepInfo['stepType'] == 4 ? 0 : $_obfuscate_UTaQ0tZbOZ2slLemr40_Q??;
                $_obfuscate_VBCv7Q??['dealUid'] = $this->nextStepInfo['stepType'] == 4 ? $this->uid : 0;
                $_obfuscate_VBCv7Q??['status'] = $this->nextStepInfo['stepType'] == 4 ? 2 : 1;
                $_obfuscate_VBCv7Q??['stepType'] = $this->nextStepInfo['stepType'];
                $_obfuscate_VBCv7Q??['stepname'] = $this->nextStepInfo['stepName'];
                $_obfuscate_VBCv7Q??['stime'] = $GLOBALS['CNOA_TIMESTAMP'];
                $_obfuscate_VBCv7Q??['etime'] = $this->nextStepInfo['stepType'] == 4 ? $GLOBALS['CNOA_TIMESTAMP'] : 0;
                $_obfuscate_VBCv7Q??['say'] = "";
                $_obfuscate_VBCv7Q??['childFlow'] = $this->nextStepInfo['childFlow'];
                $_obfuscate_VBCv7Q??['pStepId'] = $this->stepId;
                $_obfuscate_VBCv7Q??['nStepId'] = 0;
                $_obfuscate_VBCv7Q??['timelimit'] = $this->nextStepInfo['stepTime'] * 3600;
                if ( $this->nextStepInfo['stepType'] == 5 )
                {
                    $CNOA_DB->db_update( $_obfuscate_VBCv7Q??, $this->t_use_step, "WHERE `uFlowId`=".$this->uFlowId." AND `uStepId`={$this->nextStepInfo['stepId']} AND `status`=0" );
                    $_obfuscate_0W8? = $CNOA_DB->db_getfield( "id", $this->t_use_step, "WHERE `uFlowId`=".$this->uFlowId." AND `uStepId`={$this->nextStepInfo['stepId']} ORDER BY `id` DESC" );
                }
                else
                {
                    $_obfuscate_0W8? = $CNOA_DB->db_insert( $_obfuscate_VBCv7Q??, $this->t_use_step );
                }
                unset( $_obfuscate_VBCv7Q?? );
                $this->insertProxyData( $this->flowId, $this->uFlowId, $this->v, $_obfuscate_UTaQ0tZbOZ2slLemr40_Q?? );
                if ( $this->tplSort == 0 || $this->tplSort == 3 )
                {
                    $CNOA_DB->db_update( array( "status" => 1 ), "z_wf_t_".$this->flowId, "WHERE `uFlowId` = '".$this->uFlowId."' " );
                }
                if ( $this->nextStepInfo['stepType'] != 4 )
                {
                    $this->_insertNotice( $_obfuscate_0W8?, $_obfuscate_UTaQ0tZbOZ2slLemr40_Q??, $_obfuscate_6A?? );
                }
            }
        }
        else
        {
            $_obfuscate_UTaQ0tZbOZ2slLemr40_Q?? = $this->getProxyUid( $this->flowId, $this->nextStepUid );
            $_obfuscate_VBCv7Q??['uFlowId'] = $this->uFlowId;
            $_obfuscate_VBCv7Q??['sortId'] = $this->flowInfo['sortId'];
            $_obfuscate_VBCv7Q??['uStepId'] = $this->nextStepId;
            $_obfuscate_VBCv7Q??['uid'] = $this->nextStepInfo['stepType'] == 4 ? $this->uid : $this->nextStepUid;
            $_obfuscate_VBCv7Q??['proxyUid'] = $this->nextStepInfo['stepType'] == 4 ? 0 : $_obfuscate_UTaQ0tZbOZ2slLemr40_Q??;
            $_obfuscate_VBCv7Q??['dealUid'] = $this->nextStepInfo['stepType'] == 4 ? $this->uid : 0;
            $_obfuscate_VBCv7Q??['status'] = $this->nextStepInfo['stepType'] == 4 ? 2 : 1;
            $_obfuscate_VBCv7Q??['stepType'] = $this->nextStepInfo['stepType'];
            $_obfuscate_VBCv7Q??['stepname'] = $this->nextStepInfo['stepName'];
            $_obfuscate_VBCv7Q??['stime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $_obfuscate_VBCv7Q??['etime'] = $this->nextStepInfo['stepType'] == 4 ? $GLOBALS['CNOA_TIMESTAMP'] : 0;
            $_obfuscate_VBCv7Q??['say'] = "";
            $_obfuscate_VBCv7Q??['childFlow'] = $this->nextStepInfo['childFlow'];
            $_obfuscate_VBCv7Q??['pStepId'] = $this->stepId;
            $_obfuscate_VBCv7Q??['nStepId'] = 0;
            $_obfuscate_VBCv7Q??['timelimit'] = $this->nextStepInfo['stepTime'] * 3600;
            if ( $this->nextStepInfo['stepType'] == 5 )
            {
                $CNOA_DB->db_update( $_obfuscate_VBCv7Q??, $this->t_use_step, "WHERE `uFlowId`=".$this->uFlowId." AND `uStepId`={$this->nextStepInfo['stepId']} AND `status`=0" );
                $_obfuscate_0W8? = $CNOA_DB->db_getfield( "id", $this->t_use_step, "WHERE `uFlowId`=".$this->uFlowId." AND `uStepId`={$this->nextStepInfo['stepId']} ORDER BY `id` DESC" );
            }
            else
            {
                $_obfuscate_0W8? = $CNOA_DB->db_insert( $_obfuscate_VBCv7Q??, $this->t_use_step );
            }
            unset( $_obfuscate_VBCv7Q?? );
            $this->insertProxyData( $this->flowId, $this->uFlowId, $this->nextStepUid, $_obfuscate_UTaQ0tZbOZ2slLemr40_Q?? );
            if ( $this->tplSort == 0 || $this->tplSort == 3 )
            {
                $CNOA_DB->db_update( array( "status" => 1 ), "z_wf_t_".$this->flowId, "WHERE `uFlowId` = '".$this->uFlowId."' " );
            }
            if ( $this->nextStepInfo['stepType'] != 4 )
            {
                $this->_insertNotice( $_obfuscate_0W8?, $_obfuscate_UTaQ0tZbOZ2slLemr40_Q??, "" );
            }
        }
        unset( $_obfuscate_0W8? );
        unset( $_obfuscate_UTaQ0tZbOZ2slLemr40_Q?? );
    }

    protected function stepOfBingfa( )
    {
        global $CNOA_DB;
        $this->stepOfOther( );
        $this->stepId = $this->nextStepId;
        $this->stepInfo = $this->nextStepInfo;
        $this->nextStepId = NULL;
        $this->nextStepInfo = NULL;
        $this->addEvent( );
        foreach ( $this->allNextStepInfo as $_obfuscate_6A?? )
        {
            if ( !( $_obfuscate_6A??['stepType'] == 6 ) || !( $_obfuscate_6A??['stepType'] == 2 ) )
            {
                $this->nextStepId = $_obfuscate_6A??['stepId'];
                $this->nextStepInfo = $_obfuscate_6A??;
                if ( array_key_exists( $_obfuscate_6A??['stepId'], $this->allNextStepUid ) )
                {
                    $this->nextStepUid = $this->allNextStepUid[$_obfuscate_6A??['stepId']];
                }
                else
                {
                    $this->nextStepUid = 0;
                }
                $this->stepOfOther( );
            }
        }
        $_obfuscate_FcvH8FD7nIf1tXFjmw?? = $this->_searchConvergenceId( );
        if ( !filter_var( $this->convergenenUid, FILTER_VALIDATE_INT ) )
        {
            if ( preg_match( "/^k\\d+/i", $this->convergenenUid ) )
            {
                $_obfuscate_Folj = substr( $this->convergenenUid, 1 );
                $CNOA_DB->db_insert( array(
                    "uFlowId" => $this->uFlowId,
                    "stepId" => $_obfuscate_FcvH8FD7nIf1tXFjmw??,
                    "type" => 1,
                    "rule" => $_obfuscate_Folj
                ), $this->t_use_convergence_deal );
            }
            $this->convergenenUid = 0;
        }
        $_obfuscate_UTaQ0tZbOZ2slLemr40_Q?? = $this->getProxyUid( $this->flowId, $this->convergenenUid );
        $_obfuscate_VBCv7Q?? = array( );
        $_obfuscate_VBCv7Q??['uFlowId'] = $this->uFlowId;
        $_obfuscate_VBCv7Q??['sortId'] = $this->uFlowInfo['sortId'];
        $_obfuscate_VBCv7Q??['uStepId'] = $_obfuscate_FcvH8FD7nIf1tXFjmw??;
        $_obfuscate_VBCv7Q??['uid'] = $this->convergenenUid;
        $_obfuscate_VBCv7Q??['proxyUid'] = $_obfuscate_UTaQ0tZbOZ2slLemr40_Q??;
        $_obfuscate_VBCv7Q??['status'] = 0;
        $_obfuscate_VBCv7Q??['stepType'] = 5;
        $_obfuscate_VBCv7Q??['stepname'] = "";
        $_obfuscate_VBCv7Q??['stime'] = 0;
        $_obfuscate_VBCv7Q??['etime'] = 0;
        $_obfuscate_VBCv7Q??['say'] = "";
        $_obfuscate_VBCv7Q??['pStepId'] = $this->stepId;
        $_obfuscate_VBCv7Q??['nStepId'] = 0;
        $_obfuscate_VBCv7Q??['childFlow'] = $this->stepInfo['childFlow'];
        $_obfuscate_VBCv7Q??['timelimit'] = $this->nextStepInfo['stepTime'] * 3600;
        unset( $_obfuscate_FcvH8FD7nIf1tXFjmw?? );
        unset( $_obfuscate_UTaQ0tZbOZ2slLemr40_Q?? );
        $CNOA_DB->db_insert( $_obfuscate_VBCv7Q??, $this->t_use_step );
    }

    protected function stepOfEnd( )
    {
        global $CNOA_DB;
        $_obfuscate_VBCv7Q?? = array( );
        $_obfuscate_VBCv7Q??['uFlowId'] = $this->uFlowId;
        $_obfuscate_VBCv7Q??['sortId'] = $this->flowInfo['sortId'];
        $_obfuscate_VBCv7Q??['uStepId'] = $this->nextStepId;
        $_obfuscate_VBCv7Q??['uid'] = $this->uid;
        $_obfuscate_VBCv7Q??['dealUid'] = $this->uid;
        $_obfuscate_VBCv7Q??['status'] = 2;
        $_obfuscate_VBCv7Q??['stepType'] = 3;
        $_obfuscate_VBCv7Q??['stepname'] = $this->nextStepInfo['stepName'];
        $_obfuscate_VBCv7Q??['stime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_VBCv7Q??['etime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_VBCv7Q??['say'] = "";
        $_obfuscate_VBCv7Q??['childFlow'] = $this->nextStepInfo['childFlow'];
        $_obfuscate_VBCv7Q??['pStepId'] = $this->stepId;
        $_obfuscate_VBCv7Q??['nStepId'] = 0;
        $CNOA_DB->db_insert( $_obfuscate_VBCv7Q??, $this->t_use_step );
        unset( $_obfuscate_VBCv7Q?? );
        $_obfuscate_qZkmBg??['status'] = 2;
        $_obfuscate_qZkmBg??['endtime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $CNOA_DB->db_update( $_obfuscate_qZkmBg??, $this->t_use_flow, "WHERE `uFlowId`='".$this->uFlowId."'" );
        if ( $this->tplSort == 0 || $this->tplSort == 3 )
        {
            $CNOA_DB->db_update( $_obfuscate_qZkmBg??, "z_wf_t_".$this->flowId, "WHERE `uFlowId` = '".$this->uFlowId."' " );
        }
        unset( $_obfuscate_qZkmBg?? );
        $_obfuscate_6RYLWQ??['content'] = lang( "flowName" ).( "[".$this->flowName."]?????，[{$this->flowNumber}]?μ??：??，2?????????" );
        $_obfuscate_6RYLWQ??['href'] = "&uFlowId=".$this->uFlowId."&flowId={$this->flowId}&flowType={$this->flowType}&tplSort={$this->tplSort}";
        $_obfuscate_6RYLWQ??['fromid'] = $this->uFlowId;
        if ( $this->flowInfo['noticeAtFinish'] == 1 )
        {
            $this->addNotice( "notice", $this->uFlowInfo['uid'], $_obfuscate_6RYLWQ??, "done" );
        }
        else if ( $this->flowInfo['noticeAtFinish'] == 2 )
        {
            $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "dealUid" ), $this->t_use_step, "WHERE `uFlowId` = '".$this->uFlowId."' " );
            if ( !is_array( $_obfuscate_Tx7M9W ) )
            {
                $_obfuscate_Tx7M9W = array( );
            }
            $_obfuscate_n_r8MV2Eng?? = array( );
            foreach ( $_obfuscate_Tx7M9W as $_obfuscate_6A?? )
            {
                $_obfuscate_n_r8MV2Eng??[] = $_obfuscate_6A??['dealUid'];
            }
            $this->addNotice( "notice", $_obfuscate_n_r8MV2Eng??, $_obfuscate_6RYLWQ??, "done" );
            unset( $_obfuscate_Tx7M9W );
            unset( $_obfuscate_n_r8MV2Eng?? );
        }
        $_obfuscate__e3924lsxQ?? = $CNOA_DB->db_getfield( "postuid", $this->t_use_step_child_flow, "WHERE `uFlowId` = ".$this->uFlowId." " );
        if ( $_obfuscate__e3924lsxQ?? )
        {
            $CNOA_DB->db_update( array( "status" => 3 ), $this->t_use_step_child_flow, "WHERE `uFlowId` =".$this->uFlowId." " );
            $_obfuscate_6RYLWQ??['content'] = lang( "flowName" ).( "[".$this->flowName."]?????，[{$this->flowNumber}]?-??μ??：??，2?????????" );
            $_obfuscate_6RYLWQ??['href'] = "&uFlowId=".$this->uFlowId."&flowId={$this->flowId}&stepId=0&task=loadPage&from=viewflow";
            $_obfuscate_6RYLWQ??['fromid'] = 3;
            $this->addNotice( "notice", $_obfuscate__e3924lsxQ??, $_obfuscate_6RYLWQ??, "child", 1 );
        }
        if ( $this->tplSort == 0 || $this->tplSort == 3 )
        {
            ( );
            $_obfuscate_yHckwJMI = new wfEngine( );
            $_obfuscate_yHckwJMI->run( $this->uFlowId );
        }
        @unlink( CNOA_PATH_FILE."/common/wf/detailmaxcache/".$this->uFlowId.".php" );
        unset( $_obfuscate_6RYLWQ?? );
    }

    protected function updateuFlowInfo( )
    {
        global $CNOA_DB;
        $this->flow['updatetime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $this->flow['status'] = $this->nextStepInfo['stepType'] == 3 ? 2 : 1;
        $CNOA_DB->db_update( $this->flow, $this->t_use_flow, "WHERE `uFlowId`='".$this->uFlowId."'" );
    }

    private function _insertNotice( $_obfuscate_0W8?, $_obfuscate_UTaQ0tZbOZ2slLemr40_Q??, $_obfuscate_7Ri3 = "" )
    {
        $_obfuscate_6RYLWQ??['content'] = lang( "flowName" ).( "[".$this->flowName."]" ).lang( "bianHao" ).( "[".$this->flowNumber."]" ).lang( "needYouApproval" );
        $_obfuscate_6RYLWQ??['href'] = "&uFlowId=".$this->uFlowId."&flowId={$this->flowId}&step={$this->nextStepInfo['stepId']}&flowType={$this->flowType}&tplSort={$this->tplSort}";
        $_obfuscate_6RYLWQ??['fromid'] = $_obfuscate_0W8?;
        $_obfuscate_Dg?? = empty( $_obfuscate_7Ri3 ) ? $this->nextStepUid : $_obfuscate_7Ri3;
        $this->addNotice( "both", array(
            $_obfuscate_UTaQ0tZbOZ2slLemr40_Q??,
            $_obfuscate_Dg??
        ), $_obfuscate_6RYLWQ??, "todo" );
        $_obfuscate_H6Hh = getpar( $_POST, "phone", "" );
        if ( $_obfuscate_H6Hh == "phone" )
        {
            if ( !empty( $_obfuscate_UTaQ0tZbOZ2slLemr40_Q?? ) )
            {
                $_obfuscate_3Thl6So? = app::loadapp( "main", "user" )->api_getUserDataByUid( $_obfuscate_UTaQ0tZbOZ2slLemr40_Q?? );
                ( );
                $_obfuscate_a9lP = new sms( );
                $_obfuscate_a9lP->sendByMobile( $_obfuscate_3Thl6So?['mobile'], $_obfuscate_6RYLWQ??['content'], $GLOBALS['CNOA_TIMESTAMP'], $_obfuscate_3Thl6So?['truename'] );
            }
            $_obfuscate_3Thl6So? = app::loadapp( "main", "user" )->api_getUserDataByUid( $_obfuscate_Dg?? );
            ( );
            $_obfuscate_a9lP = new sms( );
            $_obfuscate_a9lP->sendByMobile( $_obfuscate_3Thl6So?['mobile'], $_obfuscate_6RYLWQ??['content'], $GLOBALS['CNOA_TIMESTAMP'], $_obfuscate_3Thl6So?['truename'] );
        }
        if ( ( double )$this->nextStepInfo['stepTime'] != 0 && $this->nextStepInfo['urgeTarget'] !== 4 )
        {
            $_obfuscate_6RYLWQ??['content'] = lang( "flowName" ).( "[".$this->flowName."]" ).lang( "bianHao" ).( "[".$this->flowNumber."]" ).lang( "beReminder" );
            $_obfuscate_6RYLWQ??['time'] = $GLOBALS['CNOA_TIMESTAMP'] + $this->nextStepInfo['stepTime'] * 3600 - $this->nextStepInfo['urgeBefore'] * 60;
            if ( $this->nextStepInfo['urgeTarget'] == 1 )
            {
                $_obfuscate_zfHrWrbA9aI9UA?? = $this->uFlowInfo['uid'];
            }
            else if ( $this->nextStepInfo['urgeTarget'] == 2 )
            {
                $_obfuscate_zfHrWrbA9aI9UA?? = array(
                    $_obfuscate_UTaQ0tZbOZ2slLemr40_Q??,
                    $_obfuscate_Dg??
                );
            }
            else if ( $this->nextStepInfo['urgeTarget'] == 3 )
            {
                $_obfuscate_zfHrWrbA9aI9UA?? = array(
                    $_obfuscate_UTaQ0tZbOZ2slLemr40_Q??,
                    $_obfuscate_Dg??,
                    $this->uFlowInfo['uid']
                );
            }
            $this->addNotice( "both", $_obfuscate_zfHrWrbA9aI9UA??, $_obfuscate_6RYLWQ??, "todo" );
            unset( $_obfuscate_zfHrWrbA9aI9UA?? );
        }
        unset( $_obfuscate_6RYLWQ?? );
    }

    private function _searchConvergenceId( )
    {
        $_obfuscate_dw4x = $this->flowInfo['flowXml'];
        $_obfuscate_sc7AoZlouuA? = xml2array( stripslashes( $_obfuscate_dw4x ), 1, "mxGraphModel" );
        $_obfuscate_t3xmZlf1zM0? = $_obfuscate_sc7AoZlouuA?['mxGraphModel']['root']['mxCell'];
        $_obfuscate_h_MzZw?? = "";
        $_obfuscate_lJp8wA?? = array( );
        if ( !is_array( $_obfuscate_t3xmZlf1zM0? ) )
        {
            return 0;
        }
        foreach ( $_obfuscate_t3xmZlf1zM0? as $_obfuscate_6A?? )
        {
            $_obfuscate_snM? = $_obfuscate_6A??['attr'];
            if ( $_obfuscate_snM?['id'] == $this->stepId )
            {
                $_obfuscate_h_MzZw?? = str_replace( "bingfa", "", $_obfuscate_snM?['mark'] );
            }
            if ( $_obfuscate_snM?['nodeType'] == "cNode" )
            {
                $_obfuscate_lJp8wA??[$_obfuscate_snM?['id']] = $_obfuscate_snM?['mark'];
            }
        }
        unset( $_obfuscate_dw4x );
        unset( $_obfuscate_sc7AoZlouuA? );
        unset( $_obfuscate_t3xmZlf1zM0? );
        $_obfuscate_FcvH8FD7nIf1tXFjmw?? = 0;
        foreach ( $_obfuscate_lJp8wA?? as $_obfuscate_5w?? => $_obfuscate_6A?? )
        {
            if ( !( $_obfuscate_h_MzZw?? == str_replace( "convergence", "", $_obfuscate_6A?? ) ) )
            {
                continue;
            }
            $_obfuscate_FcvH8FD7nIf1tXFjmw?? = $_obfuscate_5w??;
            break;
        }
        unset( $_obfuscate_h_MzZw?? );
        unset( $_obfuscate_lJp8wAA? );
        return $_obfuscate_FcvH8FD7nIf1tXFjmw??;
    }

    public function getuFlowId( )
    {
        return $this->uFlowId;
    }

}

?>
