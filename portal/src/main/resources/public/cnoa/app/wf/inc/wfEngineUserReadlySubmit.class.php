<?php

class wfEngineUserReadlySubmit extends wfEngineInterface implements wfEngineInterface
{

    protected $code = "userReadlySubmit";
    private $table_user_binding = "user_customers_binding";
    private $table_user_binding_detail = "user_customers_binding_detail";
    private $table_user_binding_field = "user_customers_binding_field";

    public function __construct( )
    {
        $FN_-2147483647( );
    }

    protected function init( )
    {
        global $CNOA_DB;
        $bindfunc = getpar( $_GET, "bindfunc" );
        $flowId = $CNOA_DB->db_getfield( "flowId", "wf_s_flow", "WHERE `bindfunction`='".$bindfunc."'" );
        $fid = $CNOA_DB->db_getfield( "id", $this->table_user_binding, "WHERE `flowId`='".$flowId."'" );
        $userCid = ( integer )getpar( $_GET, "userCid" );
        if ( $userCid === 0 )
        {
            $uFlowId = getpar( $_GET, "uFlowId" );
            $userCid = $CNOA_DB->db_getfield( "cid", $this->table_user_binding_detail, "WHERE `uFlowId`=".$uFlowId );
        }
        $customersFix = $bindFieldIds = $selectArr = $tempArr = array( );
        $result = $CNOA_DB->db_select( array( "fieldId", "valuefield" ), "user_customers_custom_fix_field", "WHERE mid=2" );
        if ( !is_array( $result ) )
        {
            $result = array( );
        }
        foreach ( $result as $value )
        {
            $customersFix[$value['fieldId']] = $value['valuefield'];
            $tempArr[] = $value['fieldId'];
        }
        $result = $CNOA_DB->db_select( array( "cFieldId", "fieldId", "cType" ), $this->table_user_binding_field, "WHERE fid=".$fid );
        if ( !is_array( $result ) )
        {
            $result = array( );
        }
        foreach ( $result as $key => $value )
        {
            if ( $value['cType'] == 2 )
            {
                $bindFieldIds[$value['fieldId']] = "field".$value['cFieldId'];
                unset( $result[$key] );
            }
            else if ( in_array( $value['cFieldId'], $tempArr ) )
            {
                $bindFieldIds[$value['fieldId']] = $customersFix[$value['cFieldId']];
                unset( $result[$key] );
            }
        }
        $insertData = app::loadapp( "user", "Customers" )->api_loadCustomerInfo( $userCid );
        $regex = "/(field)/";
        foreach ( $bindFieldIds as $key => $value )
        {
            if ( preg_match( $regex, $value ) )
            {
                if ( !empty( $insertData[$value."_name"] ) )
                {
                    $bindFieldIds[$key] = $insertData[$value."_name"];
                }
                else
                {
                    $bindFieldIds[$key] = $insertData[$value];
                }
            }
            else
            {
                $bindFieldIds[$key] = $insertData[$value];
            }
        }
        $data = array( );
        $data['checkIdea'] = $this->checkIdea;
        $data['result'] = $bindFieldIds;
        return $data;
    }

    public function runWithoutBindingStep( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        if ( !$this->interfaceCheckBind( ) )
        {
            return;
        }
        $this->makeData4Table( );
        if ( $this->isNew == "new" )
        {
            $data = array( );
            $data['cid'] = $this->userCid;
            $data['bid'] = 1;
            $data['uFlowId'] = $this->uFlowId;
            $data['status'] = 0;
            $CNOA_DB->db_insert( $data, $this->table_user_binding_detail );
        }
        $bindingCheckId = $CNOA_DB->db_getfield( "checkId", $this->table_user_binding, "WHERE id=1" );
        $checkIdea = $_POST["wf_engine_field_".$bindingCheckId];
        if ( $checkIdea == $this->bindCheck['idea'][0] )
        {
            $sql = "UPDATE ".tname( $this->table_user_binding_detail ).( " SET `status`=1 WHERE `uFlowId`=".$this->uFlowId );
            $CNOA_DB->query( $sql );
        }
    }

}

?>
