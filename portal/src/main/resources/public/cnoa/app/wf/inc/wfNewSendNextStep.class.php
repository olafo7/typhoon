<?php

class wfNewSendNextStep extends wfSendNextStep
{

    private $_level = NULL;
    private $_reason = NULL;
    private $olduFlowId = "";
    private $action = "";
    private $callBackStatus = "";
    private $t_calendar = "system_notice_calendar";
    private $t_calendar_period = "system_notice_calendar_period";

    public function __construct( )
    {
        global $CNOA_DB;
        $this->flowId = ( integer )getpar( $_POST, "flowId" );
        $FN_-2147483637( );
        $this->_level = getpar( $_POST, "level", 0 );
        $this->_reason = getpar( $_POST, "reason", "" );
        $this->flowNumber = getpar( $_POST, "flowNumber", "" );
        $this->flowName = getpar( $_POST, "flowName", "" );
        $this->isnew = "";
        $this->action = getpar( $_GET, "edit" );
        if ( $this->action == "edit" )
        {
            $this->isnew = "new";
            $this->olduFlowId = getpar( $_POST, "uFlowId" );
            $this->uFlowId = getpar( $_POST, "uFlowId" );
            $_obfuscate_6b8lIO4y = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$this->uFlowId."'" );
            $this->callBackStatus = $_obfuscate_6b8lIO4y['callBackStatus'];
        }
        $this->flowInfo = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$this->flowId."'" );
        $this->flowType = ( integer )$this->flowInfo['flowType'];
        $this->tplSort = ( integer )$this->flowInfo['tplSort'];
        $this->stepId = $this->flowInfo['startStepId'];
        $this->stepInfo = $CNOA_DB->db_getone( "*", $this->t_set_step, "WHERE `flowId`=".$this->flowId." AND `stepId`={$this->stepId} AND `stepType`=1 " );
        $this->nextStepInfo = $CNOA_DB->db_getone( "*", $this->t_set_step, "WHERE `flowId`='".$this->flowId."' AND `stepId`='{$this->nextStepId}'" );
        $this->_sendNextStep( );
    }

    private function _sendNextStep( )
    {
        global $CNOA_DB;
        if ( $this->callBackStatus != 1 )
        {
            $this->flowNumber = $this->api_formatFlowNumber( $this->flowNumber, $this->flowInfo['name'], $this->flowInfo['nameRuleId'] + 1, $this->flowId );
            $CNOA_DB->query( ( "UPDATE ".tname( $this->t_set_flow )." SET `nameRuleId`='".( $this->flowInfo['nameRuleId'] + 1 ) ).( "' WHERE `flowId`=".$this->flowId ) );
        }
        $this->_insertuFlowInfo( );
        $this->_saveData( );
        $this->_firstStep( );
        $this->addEvent( $this->_reason );
        ( $this->uFlowId );
        $_obfuscate_e53ODz04JQ?? = new wfCache( );
        $_obfuscate_e53ODz04JQ??->setFlowId( $this->flowId );
        $_obfuscate_e53ODz04JQ??->createCache( );
        if ( $this->stepInfo['allowSms'] == "1" )
        {
            $this->_saveSms( );
        }
        if ( $this->nextStepInfo['stepType'] == self::STEP_TYPE_END )
        {
            $this->stepOfEnd( );
        }
        else
        {
            if ( $this->nextStepInfo['stepType'] == self::STEP_TYPE_BINGFA )
            {
                if ( !empty( $this->allNextStepUid ) )
                {
                    $_obfuscate_1yTCLz0QeA0jcv3T7A?? = json_decode( $this->nextStepInfo['nextStep'], TRUE );
                    $_obfuscate_1yTCLz0QeA0jcv3T7A?? = implode( ",", $_obfuscate_1yTCLz0QeA0jcv3T7A?? );
                    $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_step, "WHERE `flowId`='".$this->flowId."' AND `stepId` IN ({$_obfuscate_1yTCLz0QeA0jcv3T7A??})" );
                    if ( !is_array( $_obfuscate_mPAjEGLn ) )
                    {
                        $_obfuscate_mPAjEGLn = array( );
                    }
                    foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A?? )
                    {
                        $this->allNextStepInfo[$_obfuscate_6A??['stepId']] = $_obfuscate_6A??;
                    }
                    unset( $_obfuscate_1yTCLz0QeA0jcv3T7A?? );
                    unset( $_obfuscate_mPAjEGLn );
                    unset( $_obfuscate_td3BMkoeV0sT );
                    $this->stepOfBingfa( );
                }
            }
            else
            {
                $this->stepOfOther( );
            }
            $CNOA_DB->db_update( array( "status" => 2 ), $this->t_use_step_child_flow, "WHERE `uFlowId` = ".$this->uFlowId." " );
        }
        $this->updateuFlowInfo( );
        if ( $this->action == "edit" && !empty( $this->olduFlowId ) )
        {
            $_obfuscate_gftfagw? = $CNOA_DB->db_getcount( $this->t_use_step_child_flow, "WHERE uFlowId= ".$this->olduFlowId );
            if ( 0 < $_obfuscate_gftfagw? )
            {
                $_obfuscate_3y0Y = "UPDATE ".tname( $this->t_use_step_child_flow ).( " SET `uFlowId`=".$this->uFlowId." WHERE uFlowId = {$this->olduFlowId}" );
                $CNOA_DB->query( $_obfuscate_3y0Y );
            }
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3602, lang( "turnNextStepFlowName" ).( "[".$this->flowName."] " ).lang( "bianHao" ).( "[".$this->flowNumber."]" ) );
    }

    private function _insertuFlowInfo( )
    {
        global $CNOA_DB;
        $_obfuscate_qZkmBg??['flowId'] = $this->flowId;
        $_obfuscate_qZkmBg??['flowNumber'] = $this->flowNumber;
        $_obfuscate_qZkmBg??['flowName'] = $this->flowName;
        $_obfuscate_qZkmBg??['uid'] = $this->uid;
        $_obfuscate_qZkmBg??['level'] = $this->_level;
        $_obfuscate_qZkmBg??['reason'] = $this->_reason;
        $_obfuscate_qZkmBg??['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_qZkmBg??['updatetime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_qZkmBg??['sortId'] = $this->flowInfo['sortId'];
        $_obfuscate_qZkmBg??['tplSort'] = $this->flowInfo['tplSort'];
        $_obfuscate_qZkmBg??['status'] = 1;
        $_obfuscate_qZkmBg??['allowCallback'] = $this->stepInfo['allowCallback'];
        $_obfuscate_qZkmBg??['allowCancel'] = $this->stepInfo['allowCancel'];
        $_obfuscate_KBWh = getpar( $_GET, "cid", "" );
        $_obfuscate_fdpE = getpar( $_GET, "pid", "" );
        $_obfuscate_qx37NM? = strtotime( date( "Y-m-d", $GLOBALS['CNOA_TIMESTAMP'] )." 00:00:00" );
        $_obfuscate_KWKBW4? = strtotime( date( "Y-m-d", $GLOBALS['CNOA_TIMESTAMP'] )." 23:59:59" );
        if ( empty( $this->uFlowId ) )
        {
            $this->uFlowId = $CNOA_DB->db_insert( $_obfuscate_qZkmBg??, $this->t_use_flow );
            if ( $_obfuscate_fdpE )
            {
                $_obfuscate_PoOwnw?? = $CNOA_DB->db_getfield( "cid", $this->t_calendar, "WHERE `pid` = ".$_obfuscate_fdpE." AND `stime` >= {$_obfuscate_qx37NM?} AND `etime` <= {$_obfuscate_KWKBW4?}" );
                if ( empty( $_obfuscate_KBWh ) )
                {
                    $_obfuscate_PoOwnw?? ? ( $_obfuscate_KBWh = $_obfuscate_PoOwnw?? ) : "";
                }
            }
            if ( $_obfuscate_KBWh )
            {
                if ( empty( $_obfuscate_fdpE ) )
                {
                    $CNOA_DB->db_update( array(
                        "uFlowId" => $this->uFlowId
                    ), $this->t_calendar, "WHERE `cid`='".$_obfuscate_KBWh."'" );
                }
                if ( $_obfuscate_KBWh && $_obfuscate_fdpE )
                {
                    $CNOA_DB->db_update( array(
                        "uFlowId" => $this->uFlowId
                    ), $this->t_calendar, "WHERE `cid`='".$_obfuscate_KBWh."' AND `pid`='{$_obfuscate_fdpE}' " );
                    $CNOA_DB->db_update( array(
                        "uFlowId" => $this->uFlowId
                    ), $this->t_calendar_period, "WHERE  `pid`='".$_obfuscate_fdpE."'" );
                }
            }
            $this->startEngine( "new" );
        }
        else
        {
            $this->startEngine( $this->isnew );
            $CNOA_DB->db_update( $_obfuscate_qZkmBg??, $this->t_use_flow, "WHERE `uFlowId`='".$this->uFlowId."'" );
        }
        $_obfuscate_l5xoT48YaQ?? = getpar( $_GET, "childId", 0 );
        if ( !empty( $_obfuscate_l5xoT48YaQ?? ) )
        {
            $CNOA_DB->db_update( array(
                "uFlowId" => $this->uFlowId,
                "status" => 2,
                "faqitime" => $GLOBALS['CNOA_TIMESTAMP']
            ), $this->t_use_step_child_flow, "WHERE `id` = ".$_obfuscate_l5xoT48YaQ??." AND `faqiUid` = {$this->uid} " );
            $this->doneAll( "both", $_obfuscate_l5xoT48YaQ??, "child" );
            $_obfuscate_jOcDpChC9w?? = $CNOA_DB->db_getone( "*", $this->t_use_step_child_flow, "WHERE `id` = ".$_obfuscate_l5xoT48YaQ??." " );
            $_obfuscate_6RYLWQ??['content'] = lang( "flowName" ).( "[".$this->flowName."]?????¡¤[{$this->flowNumber}]?¡¤2?????????¨¨¦Ì¡¤???" );
            $_obfuscate_6RYLWQ??['href'] = "";
            $_obfuscate_6RYLWQ??['fromid'] = $_obfuscate_l5xoT48YaQ??;
            $this->addNotice( "notice", $_obfuscate_jOcDpChC9w??['postuid'], $_obfuscate_6RYLWQ??, "child", 2 );
            unset( $_obfuscate_jOcDpChC9w?? );
        }
        unset( $_obfuscate_l5xoT48YaQ?? );
        if ( $this->flowInfo['tplSort'] != 0 )
        {
            if ( $this->flowInfo['tplSort'] == 1 )
            {
                $_obfuscate_6hS1Rw?? = "doc.history.0.php";
            }
            else if ( $this->flowInfo['tplSort'] == 2 )
            {
                $_obfuscate_6hS1Rw?? = "xls.history.0.php";
            }
            $_obfuscate_RaJNhvj = CNOA_PATH_FILE.( "/common/wf/set/".$this->flowId."/{$_obfuscate_6hS1Rw??}" );
            $_obfuscate_Ns_JyWSm = CNOA_PATH_FILE.( "/common/wf/use/".$this->flowId."/{$this->uFlowId}/{$_obfuscate_6hS1Rw??}" );
            if ( !file_exists( $_obfuscate_Ns_JyWSm ) )
            {
                mkdirs( dirname( $_obfuscate_Ns_JyWSm ) );
                @copy( $_obfuscate_RaJNhvj, $_obfuscate_Ns_JyWSm );
            }
        }
        $this->uFlowInfo = $_obfuscate_qZkmBg??;
        unset( $_obfuscate_qZkmBg?? );
    }

    private function _firstStep( )
    {
        global $CNOA_DB;
        $_obfuscate_VBCv7Q?? = array( );
        $_obfuscate_VBCv7Q??['uFlowId'] = $this->uFlowId;
        $_obfuscate_VBCv7Q??['uStepId'] = $this->stepId;
        $_obfuscate_VBCv7Q??['uid'] = $this->uid;
        $_obfuscate_VBCv7Q??['status'] = 2;
        $_obfuscate_VBCv7Q??['stime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_VBCv7Q??['etime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_VBCv7Q??['say'] = $this->_reason;
        $_obfuscate_VBCv7Q??['pStepId'] = 0;
        $_obfuscate_VBCv7Q??['nStepId'] = $this->nextStepId;
        $_obfuscate_VBCv7Q??['stepname'] = $this->stepInfo['stepName'];
        $_obfuscate_VBCv7Q??['stepType'] = 1;
        $_obfuscate_VBCv7Q??['dealUid'] = $this->uid;
        $_obfuscate_VBCv7Q??['sortId'] = $this->flowInfo['sortId'];
        $_obfuscate_VBCv7Q??['childFlow'] = $this->stepInfo['childFlow'];
        $CNOA_DB->db_insert( $_obfuscate_VBCv7Q??, $this->t_use_step );
        unset( $_obfuscate_VBCv7Q?? );
    }

}

?>
