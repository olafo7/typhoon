<?php

class wfEngineMeeting extends wfBusinessEngine
{

    protected $code = "meeting";

    public function __construct( )
    {
        $FN_-2147483647( );
    }

    protected function init( )
    {
        global $CNOA_DB;
        $data = $this->checkIdea;
        $type = $this->_getBindFieldId( "type" );
        $data[$type]['data'] = $CNOA_DB->db_select( "*", "meeting_mgr_type" );
        $data[$type]['display'] = "name";
        $data[$type]['value'] = "tid";
        $mid = $this->_getBindFieldId( "mid" );
        $midArr = app::loadapp( "meeting", "mgrApply" )->api_getMidArr( );
        $data[$mid]['data'] = array( );
        if ( !empty( $midArr ) )
        {
            $data[$mid]['data'] = $CNOA_DB->db_select( array( "name", "mid" ), "meeting_mgr_room", "WHERE `mid` IN (".implode( ",", $midArr ).")" );
        }
        $data[$mid]['display'] = "name";
        $data[$mid]['value'] = "mid";
        $data[$mid]['relative'] = array( "level" => "mgruid", "keys" => "mid" );
        $dblist = $CNOA_DB->db_select( array( "uid" ), "meeting_mgr_room_check" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $v )
        {
            $uids[] = $v['uid'];
        }
        $truenames = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uids );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['name'] = $truenames[$v['uid']]['truename'];
        }
        $checkuid = $this->_getBindFieldId( "checkuid" );
        $data[$checkuid]['data'] = $dblist;
        $data[$checkuid]['display'] = "name";
        $data[$checkuid]['value'] = "uid";
        return $data;
    }

    protected function mgruid( )
    {
        global $CNOA_DB;
        $mid = getpar( $_POST, "mid", 0 );
        $dblist = $CNOA_DB->db_select( array( "uid" ), "meeting_mgr_room_mgr", "WHERE `mid`=".$mid );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $v )
        {
            $uids[] = $v['uid'];
        }
        $truenames = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uids );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['name'] = $truenames[$v['uid']]['truename'];
        }
        $mgruid = $this->_getBindFieldId( "mgruid" );
        if ( empty( $mgruid ) )
        {
            return "";
        }
        $data[$mgruid]['data'] = $dblist;
        $data[$mgruid]['display'] = "name";
        $data[$mgruid]['value'] = "uid";
        return $data;
    }

    protected function _apply( )
    {
        $this->makeData4Post( );
        $GLOBALS['_POST']['uFlowId'] = $this->uFlowId;
        $GLOBALS['_POST']['checkuid'] = $this->nextStepUid;
        $GLOBALS['_POST']['isNew'] = $this->isNew;
        app::loadapp( "meeting", "mgrApply" )->api_add( FALSE );
    }

    protected function _approve( )
    {
        global $CNOA_DB;
        $GLOBALS['_POST']['aid'] = $CNOA_DB->db_getfield( "aid", "meeting_mgr_apply", "WHERE `uFlowId`=".$this->uFlowId );
        if ( !isset( $_POST["wf_field_".$this->bindCheck['id']] ) )
        {
            msg::callback( FALSE, lang( "noBindingField" ) );
        }
        switch ( $_POST["wf_field_".$this->bindCheck['id']] )
        {
        case $this->bindCheck['idea'][0] :
            $GLOBALS['_POST']['type'] = "agree";
            break;
        case $this->bindCheck['idea'][1] :
            $GLOBALS['_POST']['type'] = "disagree";
            break;
        default :
            msg::callback( FALSE, lang( "notAppCpndition" ) );
        }
        app::loadapp( "meeting", "mgrList" )->api_comfirmAppMeetingroom( FALSE );
    }

}

?>
