<?php

class wfEngineAttLeaveInHours extends wfEngineInterface implements wfEngineInterface
{

    protected $code = "attLeaveInHours";
    private $table_leave_hours = "att_person_leave_hours";

    public function __construct( )
    {
        $FN_-2147483647( );
    }

    protected function init( )
    {
        global $CNOA_DB;
        $data = $this->checkIdea;
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
        if ( $this->isNew == "new" )
        {
            $this->apply( );
        }
        else
        {
            $this->insertData( );
        }
    }

    protected function apply( )
    {
        $this->makeData4Post( );
        $stime = getpar( $_POST, "stime" );
        $etime = getpar( $_POST, "etime" );
        if ( $etime < $stime )
        {
            msg::callback( FALSE, lang( "StimeGreaterThanEtime" ) );
        }
    }

    private function insertData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $leaveArr = array( "0" => "病假", "1" => "事假", "2" => "年假", "3" => "路途假", "4" => "婚假", "5" => "陪产假", "6" => "产假", "7" => "丧假", "8" => "调休", "9" => "其他" );
        $this->makeData4Table( );
        $reason = getpar( $_POST, "leaveReason" );
        $hours = getpar( $_POST, "hours" );
        $leaveType = getpar( $_POST, "leaveType" );
        $stime = getpar( $_POST, "stime" );
        $etime = getpar( $_POST, "etime" );
        $flowId = getpar( $_POST, "flowId", 0 );
        $checkfield = getpar( $_POST, "checkfield" );
        if ( $etime < $stime )
        {
            msg::callback( FALSE, lang( "StimeGreaterThanEtime" ) );
        }
        foreach ( $leaveArr as $key => $value )
        {
            if ( $leaveType == $value )
            {
                $leaveType = $key;
            }
        }
        $return = $CNOA_DB->db_getone( array( "posttime", "uid" ), "z_wf_t_".$flowId, "WHERE `uFlowId`='".$this->uFlowId."'" );
        $posttime = $return['posttime'];
        $uid = $return['uid'];
        $truename = app::loadapp( "main", "user" )->api_getUserNameByUids( $uid );
        $data = array( );
        $data['uid'] = $uid;
        $data['truename'] = $truename;
        $data['hours'] = $hours;
        $data['reason'] = $reason;
        $data['leaveType'] = $leaveType;
        $data['stime'] = strtotime( $stime );
        $data['etime'] = strtotime( $etime );
        $data['posttime'] = $posttime;
        $data['ip'] = getip( );
        $data['uFlowId'] = $this->uFlowId;
        $data['status'] = 0;
        if ( $this->nextStepUid == 0 )
        {
            $data['status'] = 1;
            $data['approver'] = $CNOA_SESSION->get( "UID" );
        }
        $nextStepType = $this->nextStepType;
        if ( $this->nextStepUid == 0 && $_POST['checkfield'] == "" && $nextStepType != 4 )
        {
            msg::callback( FALSE, lang( "noBindingField" ) );
        }
        $count = $CNOA_DB->db_getcount( $this->table_leave_hours, "WHERE `uFlowId`='".$this->uFlowId."'" );
        if ( $count == 0 )
        {
            $CNOA_DB->db_insert( $data, $this->table_leave_hours );
        }
        if ( $this->nextStepUid == 0 && $checkfield == $this->bindCheck['idea'][0] )
        {
            $CNOA_DB->db_update( $data, $this->table_leave_hours, "WHERE `uFlowId`='".$this->uFlowId."'" );
        }
    }

}

?>
