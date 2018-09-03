<?php

class wfCache extends wfCommon
{

    private $flowId = 0;
    private $uFlowId = 0;
    public $uFlowDb = NULL;

    public function __construct( $uFlowId )
    {
        $this->uFlowId = intval( $uFlowId );
    }

    public function getFlow( )
    {
        $this->initCache( );
        return $this->uFlowDb['flow'];
    }

    public function getFlowXML( )
    {
        $this->initCache( );
        return $this->uFlowDb['flow']['flowXml'];
    }

    public function getFlowFields( )
    {
        $this->initCache( );
        return $this->uFlowDb['field'];
    }

    public function getStepByStepId( $stepId )
    {
        $this->initCache( );
        $steInfo = $this->uFlowDb['step'];
        if ( !is_array( $steInfo ) )
        {
            $steInfo = array( );
        }
        $return = array( );
        foreach ( $steInfo as $v )
        {
            if ( !( $v['stepId'] == $stepId ) )
            {
                continue;
            }
            $return = $v;
            break;
        }
        return $return;
    }

    public function getAllStep( )
    {
        $this->initCache( );
        return $this->uFlowDb['step'];
    }

    public function getStepUserByStepId( $stepId )
    {
        $this->initCache( );
        $stepUserInfo = $this->uFlowDb['step_user'];
        if ( !is_array( $stepUserInfo ) )
        {
            $stepUserInfo = array( );
        }
        $return = array( );
        foreach ( $stepUserInfo as $v )
        {
            if ( $v['stepId'] == $stepId )
            {
                $return[] = $v;
            }
        }
        return $return;
    }

    public function getStepInfoByIdArr( $ids )
    {
        $stepInfo = $this->getConfig( "step" );
        if ( !is_array( $stepInfo ) )
        {
            return array( );
        }
        $data = array( );
        foreach ( $stepInfo as $step )
        {
            if ( in_array( $step['stepId'], $ids ) )
            {
                $data[] = $step;
            }
        }
        return $data;
    }

    public function isUserInStep( $stepId, $uid = "" )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        if ( !empty( $uid ) )
        {
            $did = $CNOA_SESSION->get( "DID" );
            $sid = $CNOA_SESSION->get( "SID" );
        }
        $proxyUid = $CNOA_DB->db_getfield( "proxyUid", $this->t_use_step, "WHERE uFlowId=".$this->uFlowId." AND uStepId={$stepId} AND status=1" );
        if ( ( integer )$proxyUid === ( integer )$uid )
        {
            return TRUE;
        }
        $accees = FALSE;
        $stepInfo = $this->getStepUserByStepId( $stepId );
        foreach ( $stepInfo as $k => $v )
        {
            if ( $v['type'] == "people" && $v['people'] == $uid )
            {
                $accees = TRUE;
            }
            else if ( $v['type'] == "station" && $v['station'] == $sid )
            {
                $accees = TRUE;
            }
            else if ( $v['type'] == "dept" && $v['dept'] == $did )
            {
                $accees = TRUE;
            }
            else if ( $v['type'] == "deptstation" && $v['dept'] == $did && $v['station'] == $sid )
            {
                $accees = TRUE;
            }
            else if ( $v['type'] == "rule" )
            {
                if ( ( $v['rule_p'] == "faqi" || $v['rule_p'] == "zhuban" ) && ( $v['rule_d'] == "myDept" || $v['rule_d'] == "upDept" || $v['rule_d'] == "myUpDept" || $v['rule_d'] == "allDept" ) && $v['rule_s'] == $sid )
                {
                    $accees = TRUE;
                }
                if ( !( $v['rule_p'] == "faqiself" ) || !( $v['rule_p'] == "beforepeop" ) )
                {
                    $accees = TRUE;
                }
            }
            else if ( !( $v['type'] == "kong" ) && !( $this->uFlowId != 0 ) )
            {
                $val = $CNOA_DB->db_getfield( "T_".$v['kong'], "z_wf_t_".$this->getFlowId( ), "WHERE uFlowId=".$this->uFlowId );
                if ( ( integer )$val == ( integer )$uid )
                {
                    $accees = TRUE;
                }
            }
        }
        if ( $this->uFlowId != 0 )
        {
            $uStep = $CNOA_DB->db_getone( "*", "wf_u_step", "WHERE `uFlowId`='".$this->uFlowId."' AND (`uid`='{$uid}' OR `proxyUid`='{$uid}')" );
            if ( $uStep === FALSE )
            {
                $accees = FALSE;
            }
        }
        return $accees;
    }

    public function getConditionList( $nowStepId, $nextStepId )
    {
        $conditionInfo = $this->getConfig( "step_condition" );
        $conditionList = array( );
        foreach ( $conditionInfo as $v )
        {
            if ( !( $v['stepId'] == $nowStepId ) && !( $v['nextStepId'] == $nextStepId ) )
            {
                $conditionList[] = $v;
            }
        }
        return $conditionList;
    }

    public function setFlowId( $flowId )
    {
        $this->flowId = intval( $flowId );
    }

    public function getUflowId( )
    {
        return $this->uFlowId;
    }

    public function getFlowId( )
    {
        $this->initCache( );
        return $this->flowId;
    }

    public function getStepFields( $stepId, $from )
    {
        $normal = $detail = array( );
        foreach ( $this->getConfig( "step_fields" ) as $field )
        {
            if ( $field['stepId'] == $stepId )
            {
                if ( $field['from'] == self::FIELD_RULE_NORMAL )
                {
                    $normal[$field['fieldId']] = $field;
                }
                else if ( $field['from'] == self::FIELD_RULE_DETAIL )
                {
                    $detail[$field['fieldId']] = $field;
                }
            }
        }
        if ( $from == 0 )
        {
            return $normal;
        }
        if ( $from == 1 )
        {
            return $detail;
        }
    }

    public function getField( $fieldId )
    {
        $fields = $this->getFields( );
        if ( !is_array( $fields ) )
        {
            return array( );
        }
        $field = array( );
        foreach ( $fields as $v )
        {
            if ( $v['id'] == $fieldId )
            {
                $field = $v;
            }
        }
        return $field;
    }

    public function getFields( )
    {
        return $this->getConfig( "field" );
    }

    public function getDetailFields( )
    {
        return $this->getConfig( "field_detail" );
    }

    public function getStepPermit( )
    {
        return $this->getConfig( "step_permit" );
    }

    public function getStepPermitByOperate( $stepId, $operate )
    {
        foreach ( $this->getConfig( "step_permit" ) as $v )
        {
            if ( !( $stepId == $v['stepId'] ) && !( $v['operate'] == $operate ) )
            {
                continue;
            }
            return $v;
        }
        return array( );
    }

    public function getConfig( $name )
    {
        $this->initCache( );
        return $this->uFlowDb[$name];
    }

    public function initCache( )
    {
        global $CNOA_DB;
        if ( $this->uFlowDb == NULL )
        {
            $path = $this->_getCacheFilePath( );
            if ( file_exists( $path ) )
            {
                $infoDb = include( $path );
            }
            else
            {
                $this->flowId = $CNOA_DB->db_getfield( "flowId", "wf_u_flow", "WHERE `uFlowId`=".$this->uFlowId );
                $this->createCache( );
                $path = $this->_getCacheFilePath( );
                $infoDb = include( $path );
            }
            if ( !$infoDb )
            {
                $infoDb['field'] = array( );
                $infoDb['field_detail'] = array( );
                $infoDb['flow'] = array( );
                $infoDb['step'] = array( );
                $infoDb['step_condition'] = array( );
                $infoDb['step_fields'] = array( );
                $infoDb['step_user'] = array( );
                $infoDb['step_permit'] = array( );
                $infoDb['step_child_kongjian'] = array( );
            }
            $this->uFlowDb = array( );
            $this->uFlowDb['field'] = $infoDb['field'];
            $this->uFlowDb['field_detail'] = $infoDb['field_detail'];
            $this->uFlowDb['flow'] = $infoDb['flow'];
            $this->uFlowDb['step'] = $infoDb['step'];
            $this->uFlowDb['step_condition'] = $infoDb['step_condition'];
            $this->uFlowDb['step_fields'] = $infoDb['step_fields'];
            $this->uFlowDb['step_user'] = $infoDb['step_user'];
            $this->uFlowDb['step_permit'] = $infoDb['step_permit'];
            $this->uFlowDb['step_child_kongjian'] = $infoDb['step_child_kongjian'];
            $this->flowId = $this->uFlowDb['flow']['flowId'];
        }
    }

    public function createCache( )
    {
        global $CNOA_DB;
        if ( $this->flowId == 0 || $this->uFlowId == 0 )
        {
            return FALSE;
        }
        $list = array(
            "flowId" => $this->flowId,
            "uFlowId" => $this->uFlowId
        );
        $list['flow'] = array( );
        $listDb = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$this->flowId."'" );
        if ( !is_array( $listDb ) )
        {
            $listDb = array( );
        }
        $list['flow'] = $listDb;
        $list['field'] = array( );
        $listDb = $CNOA_DB->db_select( "*", $this->t_set_field, "WHERE `flowId`='".$this->flowId."' ORDER BY `order` ASC" );
        if ( !is_array( $listDb ) )
        {
            $listDb = array( );
        }
        $list['field'] = $listDb;
        $list['field_detail'] = array( );
        $listDb = $CNOA_DB->db_select( "*", $this->t_set_field_detail, "WHERE `flowId`='".$this->flowId."'" );
        if ( !is_array( $listDb ) )
        {
            $listDb = array( );
        }
        $list['field_detail'] = $listDb;
        $list['step'] = array( );
        $listDb = $CNOA_DB->db_select( "*", $this->t_set_step, "WHERE `flowId`='".$this->flowId."'" );
        if ( !is_array( $listDb ) )
        {
            $listDb = array( );
        }
        $list['step'] = $listDb;
        $list['step_condition'] = array( );
        $listDb = $CNOA_DB->db_select( "*", $this->t_set_step_condition, "WHERE `flowId`='".$this->flowId."' ORDER BY `id` ASC" );
        if ( !is_array( $listDb ) )
        {
            $listDb = array( );
        }
        $list['step_condition'] = $listDb;
        $list['step_fields'] = array( );
        $listDb = $CNOA_DB->db_select( "*", $this->t_set_step_fields, "WHERE `flowId`='".$this->flowId."'" );
        if ( !is_array( $listDb ) )
        {
            $listDb = array( );
        }
        $list['step_fields'] = $listDb;
        $list['step_user'] = array( );
        $listDb = $CNOA_DB->db_select( "*", $this->t_set_step_user, "WHERE `flowId`='".$this->flowId."'" );
        if ( !is_array( $listDb ) )
        {
            $listDb = array( );
        }
        $list['step_user'] = $listDb;
        $list['step_permit'] = array( );
        $listDb = $CNOA_DB->db_select( "*", $this->t_set_step_permit, "WHERE `flowId`='".$this->flowId."'" );
        if ( !is_array( $listDb ) )
        {
            $listDb = array( );
        }
        $list['step_permit'] = $listDb;
        $listDb = $CNOA_DB->db_select( "*", $this->t_set_step_child_kongjian, "WHERE `flowId`='".$this->flowId."'" );
        $list['step_child_kongjian'] = is_array( $listDb ) ? $listDb : array( );
        $filename = $this->_getCacheFilePath( "create" );
        $str = "<?php \n return ".var_export( $list, TRUE ).";";
        file_put_contents( $filename, $str );
    }

    public function deleteCache( )
    {
        $file = $this->_getCacheFilePath( );
        @unlink( $file );
    }

    public function mergeMyFields( $stepArr, $uFlowId )
    {
        global $CNOA_DB;
        $infoDB = $this->uFlowDb;
        $all = array( );
        $field = $infoDB['step_fields'];
        foreach ( $field as $k => $v )
        {
            if ( in_array( $v['stepId'], $stepArr ) )
            {
                $all[$v['fieldId']] = $v;
                if ( $v['show'] == 1 )
                {
                    $show[] = $v['fieldId'];
                }
            }
        }
        foreach ( $all as $k => $v )
        {
            if ( in_array( $k, $show ) )
            {
                $all[$k]['show'] = 1;
                $all[$k]['hide'] = 0;
            }
            else
            {
                $all[$k]['show'] = 0;
                $all[$k]['hide'] = 1;
            }
        }
        return $all;
    }

    public function getFlowPathByStepId( )
    {
        $this->initCache( );
        $xmlArray = xml2array( stripslashes( $this->uFlowDb['flow']['flowXml'] ), 1, "mxGraphModel" );
        $mxCell = $xmlArray['mxGraphModel']['root']['mxCell'];
        unset( $xmlArray );
        $nodes = array( );
        foreach ( $mxCell as $v )
        {
            $attr = $v['attr'];
            if ( $attr['edge'] == 1 )
            {
                $nodes[$attr['source']][] = $attr['target'];
            }
        }
        unset( $mxCell );
    }

    private function _getCacheFilePath( $type = "" )
    {
        $destination = CNOA_PATH_FILE."/common/wf/cache/";
        $dir = $this->uFlowId % 300;
        if ( $type == "create" )
        {
            @mkdirs( $destination.$dir );
        }
        return $destination.$dir."/".$this->uFlowId.".php";
    }

}

?>
