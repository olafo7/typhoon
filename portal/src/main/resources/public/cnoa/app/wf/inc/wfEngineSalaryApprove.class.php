<?php

class wfEngineSalaryApprove extends wfEngineInterface implements wfEngineInterface
{

    protected $code = "salaryApprove";
    protected $table_manage_approve = "salary_manage_approve";

    public function __construct( )
    {
        $FN_-2147483647( );
    }

    protected function init( )
    {
        global $CNOA_DB;
        $salaryApproveId = ( integer )getpar( $_GET, "salaryApproveId" );
        $uFlowId = ( integer )getpar( $_GET, "uFlowId" );
        if ( $salaryApproveId !== 0 )
        {
            $WHERE = "WHERE id=".$salaryApproveId;
        }
        else
        {
            $WHERE = "WHERE uFlowId=".$uFlowId;
        }
        $result = $CNOA_DB->db_getone( array( "shouldSum", "actualSum" ), $this->table_manage_approve, $WHERE );
        $actualFieldId = $shouldFieldId = 0;
        foreach ( $this->bindFields as $key => $value )
        {
            if ( $value == "viewActualSum" )
            {
                $actualFieldId = $key;
            }
            else if ( $value == "viewShouldSum" )
            {
                $shouldFieldId = $key;
            }
        }
        $data = array( );
        $data['checkIdea'] = $this->checkIdea;
        $data['actual'] = array(
            "actualSum" => $result['actualSum'],
            "fieldId" => $actualFieldId
        );
        $data['should'] = array(
            "shouldSum" => $result['shouldSum'],
            "fieldId" => $shouldFieldId
        );
        return $data;
    }

    public function runWithoutBindingStep( )
    {
        if ( !$this->interfaceCheckBind( ) )
        {
            return;
        }
        global $CNOA_DB;
        if ( $this->isNew == "new" )
        {
            $this->makeData4Table( );
            $lastAppUid = ( integer )getpar( $_POST, "lastAppUid" );
            $sql = "UPDATE ".tname( $this->table_manage_approve ).( " SET `appuid`=".$lastAppUid.", `uFlowId`={$this->uFlowId} WHERE `id`={$this->salaryApproveId}" );
            $CNOA_DB->query( $sql );
            $CNOA_DB->db_delete( "wf_u_flow", "WHERE `uFlowId`=".$this->salaryOldUflowId );
        }
        $this->makeData4Table( );
        $lastAppUid = ( integer )getpar( $_POST, "lastAppUid" );
        $checkfield = $_POST['checkfield'] == "同意" ? 1 : 2;
        $nextStepType = $this->nextStepType;
        if ( $this->nextStepUid == 0 && $_POST['checkfield'] == "" && $nextStepType != 4 )
        {
            msg::callback( FALSE, lang( "noBindingField" ) );
        }
        if ( $this->nextStepUid == 0 && $nextStepType != 4 )
        {
            $sql = "UPDATE ".tname( $this->table_manage_approve ).( " SET `appuid`=".$lastAppUid.", `status`={$checkfield} WHERE `uFlowId`={$this->uFlowId}" );
            $CNOA_DB->query( $sql );
            $touid = $CNOA_DB->db_getfield( "uid", "wf_u_flow", "WHERE `uFlowId`=".$this->uFlowId );
            $title = $CNOA_DB->db_getfield( "title", $this->table_manage_approve, "WHERE `uFlowId`=".$this->uFlowId );
            $title = "标题为[".$title."]已经审批，点击查看。";
            $newsH = "index.php?app=salary&func=manage&action=approve&task=loadPage";
            notice::add( $touid, "流程审批", $title, $newsH, $sendTime, 33, $touid );
        }
    }

}

?>
