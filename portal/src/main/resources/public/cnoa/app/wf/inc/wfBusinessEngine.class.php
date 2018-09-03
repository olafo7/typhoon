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

    public function getBusinessData( $_obfuscate_pYzeLf4ÿ, $_obfuscate_h8xAiUQÿ = NULL )
    {
        if ( $_obfuscate_h8xAiUQÿ == "phone" )
        {
            $_obfuscate_6RYLWQÿÿ = $this->$_obfuscate_pYzeLf4ÿ( "phone" );
            return $_obfuscate_6RYLWQÿÿ;
        }
        echo json_encode( $this->$_obfuscate_pYzeLf4ÿ( ) );
        exit( );
    }

    public function setBusinessData( $_obfuscate_wD9kdBYÿ, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_0Ul8BBkt, $_obfuscate_WyQCbNwalCxRcNMÿ, $_obfuscate_qXF5WAWqYSIÿ = 0, $_obfuscate_tunzLxLK = 0, $_obfuscate_0eI4mradFgÿÿ = 0, $_obfuscate_0muy1AMpeuH = 0, $_obfuscate_DoJ0U_BJSGiVrBuNnUtLQÿÿ = 0, $_obfuscate_BOv37ISEbxxb04w9 )
    {
        $_obfuscate_VBCv7Qÿÿ = $this->bindSteps[$_obfuscate_0Ul8BBkt];
        $this->uFlowId = $_obfuscate_TlvKhtsoOQÿÿ;
        $this->isNew = $_obfuscate_wD9kdBYÿ;
        $this->nextStepUid = $_obfuscate_WyQCbNwalCxRcNMÿ;
        $this->salaryApproveId = $_obfuscate_qXF5WAWqYSIÿ;
        $this->salaryOldUflowId = $_obfuscate_tunzLxLK;
        $this->userCid = $_obfuscate_0eI4mradFgÿÿ;
        $this->noticeLid = $_obfuscate_0muy1AMpeuH;
        $this->noticeOldUflowId = $_obfuscate_DoJ0U_BJSGiVrBuNnUtLQÿÿ;
        $this->nextStepType = $_obfuscate_BOv37ISEbxxb04w9;
        if ( $this instanceof wfEngineInterface )
        {
            $this->runWithoutBindingStep( );
        }
        else
        {
            $_obfuscate_hoeokj1 = "_".$_obfuscate_VBCv7Qÿÿ;
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
        foreach ( $this->bindFields as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( !( $_obfuscate_6Aÿÿ == $_obfuscate_dcwitxb ) )
            {
                continue;
            }
            return $_obfuscate_5wÿÿ;
        }
        return "";
    }

    protected function makeData4Post( $_obfuscate_b55OQÿÿ = array( ) )
    {
        if ( !is_array( $_obfuscate_b55OQÿÿ ) )
        {
            return FALSE;
        }
        foreach ( $GLOBALS['_POST'] as $_obfuscate_3gn_eQÿÿ => $_obfuscate_TAxu )
        {
            if ( preg_match( "/^wf_field_(\\d+)/", $_obfuscate_3gn_eQÿÿ, $_obfuscate_0W8ÿ ) )
            {
                $_obfuscate_YIq2A8cÿ = $this->bindFields[$_obfuscate_0W8ÿ[1]];
                if ( !isset( $_obfuscate_YIq2A8cÿ ) )
                {
                }
                else if ( !empty( $_obfuscate_b55OQÿÿ ) || !in_array( $_obfuscate_YIq2A8cÿ, $_obfuscate_b55OQÿÿ ) )
                {
                    $_obfuscate_FckHk8RR1IQÿ = "wf_engine_field_".$_obfuscate_0W8ÿ[1];
                    $GLOBALS['_POST'][$_obfuscate_YIq2A8cÿ] = isset( $_POST[$_obfuscate_FckHk8RR1IQÿ] ) ? $_POST[$_obfuscate_FckHk8RR1IQÿ] : $_obfuscate_TAxu;
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
        foreach ( ( array )$_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( !( stripos( $_obfuscate_5wÿÿ, "T_" ) !== 0 ) )
            {
                $_obfuscate_8jhldA9Y9Aÿÿ = intval( substr( $_obfuscate_5wÿÿ, 2 ) );
                $_obfuscate_YIq2A8cÿ = $this->bindFields[$_obfuscate_8jhldA9Y9Aÿÿ];
                if ( !isset( $_obfuscate_YIq2A8cÿ ) )
                {
                }
                else
                {
                    $GLOBALS['_POST'][$_obfuscate_YIq2A8cÿ] = in_array( $_obfuscate_YIq2A8cÿ, $_obfuscate_rFekUqpblT ) ? $_POST["wf_engine_field_".$_obfuscate_8jhldA9Y9Aÿÿ] : $_obfuscate_6Aÿÿ;
                }
            }
        }
        $this->makeData4Post( );
    }

    protected function insertAttach( $_obfuscate_3tiDsnMÿ, $_obfuscate_YIq2A8cÿ )
    {
        global $CNOA_DB;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_8CpDPPa = $CNOA_DB->db_getfield( "attach", "wf_u_flow", "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ );
        $CNOA_DB->db_update( array(
            $_obfuscate_YIq2A8cÿ => $_obfuscate_8CpDPPa
        ), $_obfuscate_3tiDsnMÿ, "WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQÿÿ );
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
