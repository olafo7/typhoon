<?php

class wfEngineLeave extends wfBusinessEngine
{

    protected $code = "leave";

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

    protected function _apply( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $this->makeData4Post( );
        $stime = getpar( $_POST, "stime" );
        $etime = getpar( $_POST, "etime" );
        $reason = getpar( $_POST, "reason" );
        $data = array( );
        $data['uid'] = $uid;
        $data['stime'] = strtotime( $stime );
        $data['etime'] = strtotime( $etime );
        $data['reason'] = $reason;
        $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['ip'] = getip( );
        $data['status'] = 0;
        $data['uFlowId'] = $this->uFlowId;
        $CNOA_DB->db_insert( $data, "user_attendance_leave" );
    }

    protected function _approve( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $data = array( );
        if ( !isset( $_POST["wf_field_".$this->bindCheck['id']] ) )
        {
            msg::callback( FALSE, lang( "noBindingField" ) );
        }
        switch ( $_POST["wf_field_".$this->bindCheck['id']] )
        {
        case $this->bindCheck['idea'][0] :
            $data['status'] = 1;
            break;
        case $this->bindCheck['idea'][1] :
            $data['status'] = 2;
            break;
        default :
            msg::callback( FALSE, lang( "notAppCpndition" ) );
        }
        $data['vid'] = $uid;
        $CNOA_DB->db_update( $data, "user_attendance_leave", "WHERE `uFlowId`=".$this->uFlowId );
    }

}

?>
