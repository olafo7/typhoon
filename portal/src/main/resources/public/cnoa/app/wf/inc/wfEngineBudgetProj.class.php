<?php

class wfEngineBudgetProj
{

    private $budget_apply_record_proj = "budget_apply_record_proj";
    private $budget_set_budget_proj = "budget_set_budget_project";
    private $budget_tmp_tabel_proj = "budget_tmp_tabel_proj";
    private $wf_u_flow = "wf_u_flow";

    public function setData( $flowId, $uFlowId, $fieldId )
    {
        global $CNOA_DB;
        $table = "z_wf_t_".$flowId;
        $projId = $CNOA_DB->db_getfield( "projId", $this->budget_tmp_tabel_proj, "WHERE `uFlowId`=".$uFlowId." AND `fieldId`={$fieldId}" );
        $projId = intval( $projId );
        $field = "T_".$fieldId;
        $dblist = $CNOA_DB->db_getone( array(
            "posttime",
            $field
        ), $table, "WHERE `uFlowId`=".$uFlowId );
        $sum = intval( $dblist[$field] );
        $applyTime = $dblist['posttime'];
        $uid = $CNOA_DB->db_getfield( "uid", $this->wf_u_flow, "WHERE `uFlowId`=".$uFlowId );
        $uid = intval( $uid );
        $data['projId'] = $projId;
        $data['uid'] = $uid;
        $data['sum'] = $sum;
        $data['status'] = 1;
        $data['applyTime'] = $applyTime;
        $data['activateTime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['uFlowId'] = $uFlowId;
        if ( $projId )
        {
            $id = $CNOA_DB->db_insert( $data, $this->budget_apply_record_proj );
            if ( $id )
            {
                $sql = "UPDATE ".tname( $this->budget_set_budget_proj ).( " SET `balance`=`balance`-".$sum.", `usedSum`=`usedSum`+{$sum} WHERE `projId`={$projId}" );
                $CNOA_DB->query( $sql );
            }
        }
        $CNOA_DB->db_delete( $this->budget_tmp_tabel_proj, "WHERE `uFlowId`=".$uFlowId." AND `fieldId`={$fieldId}" );
    }

    public function verify( $fieldId, $flowId, $uFlowId )
    {
        global $CNOA_DB;
        $fieldPostName = "wf_field_".$fieldId;
        $projPostName = "wf_budgetProj_".$fieldId;
        if ( isset( $_POST[$fieldPostName] ) )
        {
            $sum = getpar( $_POST, $fieldPostName );
            $projId = intval( getpar( $_POST, $projPostName, 0 ) );
        }
        else
        {
            $table = "z_wf_t_".$flowId;
            $sum = $CNOA_DB->db_getfield( "T_".$fieldId, $table, "WHERE `uFlowId`=".$uFlowId );
            $projId = $CNOA_DB->db_getfield( "projId", $this->budget_tmp_tabel_proj, "WHERE `uFlowId`=".$uFlowId." AND `fieldId`={$fieldId}" );
        }
        $balance = app::loadapp( "budget", "Setting" )->api_getBalanceByProjId( $projId );
        if ( $sum <= $balance )
        {
            return TRUE;
        }
        return lang( "projBudgetAppNotGreter" );
    }

}

?>
