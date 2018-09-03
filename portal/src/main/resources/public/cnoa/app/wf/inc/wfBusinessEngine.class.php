<?php

class wfBusinessEngine
{

    protected $bindSteps = NULL;
    protected $bindFields = NULL;
    protected $bindCheck = NULL;
    protected $checkIdea = NULL;
    protected $salaryApproveId = 0;
    protected $uFlowId = NULL;
    protected $nextStepUid = NULL;
    protected $isNew = NULL;
    protected $nextStepType = NULL;

    public function __construct( )
    {
        $mapPath = CNOA_PATH_FILE.( "/common/wf/engine/".$this->code.".map.php" );
        if ( !file_exists( $mapPath ) )
        {
            return FALSE;
        }
        $bindMap = current( include( $mapPath ) );
        if ( !$bindMap['open'] )
        {
            return FALSE;
        }
        $this->bindSteps = $bindMap['step'];
        $this->bindFields = $bindMap['field'];
        $this->bindCheck = $bindMap['check'];
        $this->checkIdea[$this->bindCheck['id']]['data'][] = array(
            "idea" => $this->bindCheck['idea'][0]
        );
        $this->checkIdea[$this->bindCheck['id']]['data'][] = array(
            "idea" => $this->bindCheck['idea'][1]
        );
        $this->checkIdea[$this->bindCheck['id']]['display'] = "idea";
        $this->checkIdea[$this->bindCheck['id']]['value'] = "idea";
    }

    public function getBusinessData( $_obfuscate_pYzeLf4�, $_obfuscate_h8xAiUQ� = NULL )
    {
        if ( $_obfuscate_h8xAiUQ� == "phone" )
        {
            $_obfuscate_6RYLWQ�� = $this->$_obfuscate_pYzeLf4�( "phone" );
            return $_obfuscate_6RYLWQ��;
        }
        echo json_encode( $this->$_obfuscate_pYzeLf4�( ) );
        exit( );
    }

    public function setBusinessData( $_obfuscate_wD9kdBY�, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_0Ul8BBkt, $_obfuscate_WyQCbNwalCxRcNM�, $_obfuscate_qXF5WAWqYSI� = 0, $_obfuscate_tunzLxLK = 0, $_obfuscate_0eI4mradFg�� = 0, $_obfuscate_0muy1AMpeuH = 0, $_obfuscate_DoJ0U_BJSGiVrBuNnUtLQ�� = 0, $_obfuscate_BOv37ISEbxxb04w9 )
    {
        $_obfuscate_VBCv7Q�� = $this->bindSteps[$_obfuscate_0Ul8BBkt];
        $this->uFlowId = $_obfuscate_TlvKhtsoOQ��;
        $this->isNew = $_obfuscate_wD9kdBY�;
        $this->nextStepUid = $_obfuscate_WyQCbNwalCxRcNM�;
        $this->salaryApproveId = $_obfuscate_qXF5WAWqYSI�;
        $this->salaryOldUflowId = $_obfuscate_tunzLxLK;
        $this->userCid = $_obfuscate_0eI4mradFg��;
        $this->noticeLid = $_obfuscate_0muy1AMpeuH;
        $this->noticeOldUflowId = $_obfuscate_DoJ0U_BJSGiVrBuNnUtLQ��;
        $this->nextStepType = $_obfuscate_BOv37ISEbxxb04w9;
        if ( $this instanceof wfEngineInterface )
        {
            $this->runWithoutBindingStep( );
        }
        else
        {
            $_obfuscate_hoeokj1 = "_".$_obfuscate_VBCv7Q��;
            if ( method_exists( $this, $_obfuscate_hoeokj1 ) )
            {
                $this->$_obfuscate_hoeokj1( );
            }
        }
    }

    protected function _getBindFieldId( $_obfuscate_dcwitxb )
    {
        if ( !is_array( $this->bindFields ) )
        {
            $this->bindFields = array( );
        }
        foreach ( $this->bindFields as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( !( $_obfuscate_6A�� == $_obfuscate_dcwitxb ) )
            {
                continue;
            }
            return $_obfuscate_5w��;
        }
        return "";
    }

    protected function makeData4Post( $_obfuscate_b55OQ�� = array( ) )
    {
        if ( !is_array( $_obfuscate_b55OQ�� ) )
        {
            return FALSE;
        }
        foreach ( $GLOBALS['_POST'] as $_obfuscate_3gn_eQ�� => $_obfuscate_TAxu )
        {
            if ( preg_match( "/^wf_field_(\\d+)/", $_obfuscate_3gn_eQ��, $_obfuscate_0W8� ) )
            {
                $_obfuscate_YIq2A8c� = $this->bindFields[$_obfuscate_0W8�[1]];
                if ( !isset( $_obfuscate_YIq2A8c� ) )
                {
                }
                else if ( !empty( $_obfuscate_b55OQ�� ) || !in_array( $_obfuscate_YIq2A8c�, $_obfuscate_b55OQ�� ) )
                {
                    $_obfuscate_FckHk8RR1IQ� = "wf_engine_field_".$_obfuscate_0W8�[1];
                    $GLOBALS['_POST'][$_obfuscate_YIq2A8c�] = isset( $_POST[$_obfuscate_FckHk8RR1IQ�] ) ? $_POST[$_obfuscate_FckHk8RR1IQ�] : $_obfuscate_TAxu;
                }
            }
        }
    }

    protected function makeData4Table( $_obfuscate_rFekUqpblT = array( ) )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = ( integer )getpar( $_POST, "flowId" );
        if ( $_obfuscate_F4AbnVRh === 0 )
        {
            $_obfuscate_F4AbnVRh = $CNOA_DB->db_getfield( "flowId", "wf_u_flow", "WHERE `uFlowId`=".$this->uFlowId );
            $GLOBALS['_POST']['flowId'] = $_obfuscate_F4AbnVRh;
        }
        if ( empty( $_obfuscate_F4AbnVRh ) )
        {
            return FALSE;
        }
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_getone( "*", "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE `uFlowId`=".$this->uFlowId );
        foreach ( ( array )$_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( !( stripos( $_obfuscate_5w��, "T_" ) !== 0 ) )
            {
                $_obfuscate_8jhldA9Y9A�� = intval( substr( $_obfuscate_5w��, 2 ) );
                $_obfuscate_YIq2A8c� = $this->bindFields[$_obfuscate_8jhldA9Y9A��];
                if ( !isset( $_obfuscate_YIq2A8c� ) )
                {
                }
                else
                {
                    $GLOBALS['_POST'][$_obfuscate_YIq2A8c�] = in_array( $_obfuscate_YIq2A8c�, $_obfuscate_rFekUqpblT ) ? $_POST["wf_engine_field_".$_obfuscate_8jhldA9Y9A��] : $_obfuscate_6A��;
                }
            }
        }
        $this->makeData4Post( );
    }

    protected function insertAttach( $_obfuscate_3tiDsnM�, $_obfuscate_YIq2A8c� )
    {
        global $CNOA_DB;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_8CpDPPa = $CNOA_DB->db_getfield( "attach", "wf_u_flow", "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ�� );
        $CNOA_DB->db_update( array(
            $_obfuscate_YIq2A8c� => $_obfuscate_8CpDPPa
        ), $_obfuscate_3tiDsnM�, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ�� );
    }

    protected function interfaceCheckBind( )
    {
        $mapPath = CNOA_PATH_FILE.( "/common/wf/engine/".$this->code.".map.php" );
        if ( !file_exists( $mapPath ) )
        {
            return FALSE;
        }
        $bindMap = current( include( $mapPath ) );
        if ( !$bindMap['open'] )
        {
            return FALSE;
        }
        return TRUE;
    }

}

?>
