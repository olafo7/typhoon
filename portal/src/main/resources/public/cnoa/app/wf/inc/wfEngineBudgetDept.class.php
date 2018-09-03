<?php

class wfEngineBudgetDept
{

    private $tabel_apply_record = "budget_apply_record";
    private $tabel_set = "budget_set_budget";
    private $tabel_tmp_tabel = "budget_tmp_tabel";
    private $tabel_u_flow = "wf_u_flow";

    public function setData( $flowId, $uFlowId, $fieldId )
    {
        global $CNOA_DB;
        $table = "z_wf_t_".$flowId;
        $deptId = $CNOA_DB->db_getfield( "deptId", $this->tabel_tmp_tabel, "WHERE `uFlowId`=".$uFlowId." AND `fieldId`={$fieldId}" );
        $deptId = intval( $deptId );
        $field = "T_".$fieldId;
        $dblist = $CNOA_DB->db_getone( array(
            "posttime",
            $field
        ), $table, "WHERE `uFlowId`=".$uFlowId );
        $sum = $dblist[$field];
        $applyTime = $dblist['posttime'];
        $uid = $CNOA_DB->db_getfield( "uid", $this->tabel_u_flow, "WHERE `uFlowId`=".$uFlowId );
        $uid = intval( $uid );
        $data['deptId'] = $deptId;
        $data['uid'] = $uid;
        $data['sum'] = $sum;
        $data['status'] = 1;
        $data['applyTime'] = $applyTime;
        $data['activateTime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['uFlowId'] = $uFlowId;
        if ( $deptId )
        {
            $id = $CNOA_DB->db_insert( $data, $this->tabel_apply_record );
            if ( $id )
            {
                $sql = "UPDATE ".tname( $this->tabel_set ).( " SET `balance`=`balance`-".$sum.", `usedSum`=`usedSum`+{$sum} WHERE `deptId`={$deptId}" );
                $CNOA_DB->query( $sql );
            }
        }
        $CNOA_DB->db_delete( $this->tabel_tmp_tabel, "WHERE `uFlowId`=".$uFlowId." AND `fieldId`={$fieldId}" );
    }

    public function verify( $fieldId, $flowId, $uFlowId )
    {
        global $CNOA_DB;
        $fieldPostName = "wf_field_".$fieldId;
        $deptPostName = "wf_budgetDept_".$fieldId;
        if ( isset( $_POST[$fieldPostName] ) )
        {
            $sum = getpar( $_POST, $fieldPostName );
            $deptId = intval( getpar( $_POST, $deptPostName, 0 ) );
        }
        else
        {
            $table = "z_wf_t_".$flowId;
            $sum = $CNOA_DB->db_getfield( "T_".$fieldId, $table, "WHERE `uFlowId`=".$uFlowId );
            $deptId = $CNOA_DB->db_getfield( "deptId", $this->tabel_tmp_tabel, "WHERE `uFlowId`=".$uFlowId." AND `fieldId`={$fieldId}" );
        }
        $balance = app::loadapp( "budget", "Setting" )->api_getBalanceByDeptId( $deptId );
        if ( $sum <= $balance )
        {
            return TRUE;
        }
        return lang( "deptBudgetAppNotGreter" );
    }

}

?>
