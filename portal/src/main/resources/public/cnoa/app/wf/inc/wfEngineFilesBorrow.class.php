<?php

class wfEngineFilesBorrow extends wfBusinessEngine
{

    protected $code = "filesBorrow";

    public function __construct( )
    {
        $FN_-2147483647( );
    }

    protected function init( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $data = $this->checkIdea;
        return $data;
    }

    protected function _apply( )
    {
        $this->makeData4Post( );
        $data['stime'] = strtotime( replacehanzi( getpar( $_POST, "stime" ) ) );
        $data['etime'] = strtotime( replacehanzi( getpar( $_POST, "etime" ) ) );
        $data['fileid'] = getpar( $_POST, "fileid" );
        $data['postuid'] = getpar( $_POST, "postuid" );
        $data['reason'] = getpar( $_POST, "reason" );
        $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['uFlowId'] = $this->uFlowId;
        $data['status'] = 0;
        global $CNOA_DB;
        $CNOA_DB->db_insert( $data, "odoc_files_borrow" );
    }

    protected function _approve( )
    {
        if ( !isset( $_POST["wf_field_".$this->bindCheck['id']] ) )
        {
            msg::callback( FALSE, lang( "noBindingField" ) );
        }
        global $CNOA_DB;
        global $CNOA_SESSION;
        $data['approveuid'] = $CNOA_SESSION->get( "UID" );
        $data['approvetime'] = $GLOBALS['CNOA_TIMESTAMP'];
        switch ( $_POST["wf_field_".$this->bindCheck['id']] )
        {
        case $this->bindCheck['idea'][0] :
            $data['status'] = 1;
            $CNOA_DB->db_update( $data, "odoc_files_borrow", "WHERE `uFlowId`=".$this->uFlowId );
            break;
        case $this->bindCheck['idea'][1] :
            $data['status'] = 2;
            $CNOA_DB->db_update( $data, "odoc_files_borrow", "WHERE `uFlowId`=".$this->uFlowId );
            break;
        default :
            msg::callback( FALSE, lang( "notAppCpndition" ) );
        }
    }

}

?>
