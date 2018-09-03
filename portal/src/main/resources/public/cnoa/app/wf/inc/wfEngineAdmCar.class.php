<?php

class wfEngineAdmCar extends wfBusinessEngine
{

    protected $code = "admCar";

    public function __construct( )
    {
        $FN_-2147483647( );
    }

    protected function init( $_obfuscate_h8xAiUQ� = NULL )
    {
        global $CNOA_DB;
        $_obfuscate_6RYLWQ�� = $this->checkIdea;
        $_obfuscate_KBWh = $this->_getBindFieldId( "cid" );
        $_obfuscate_6RYLWQ��[$_obfuscate_KBWh]['data'] = $_obfuscate_ZOv4cVx67w�� = $CNOA_DB->db_select( array( "carnumber", "id" ), "adm_car_info" );
        $_obfuscate_6RYLWQ��[$_obfuscate_KBWh]['display'] = "carnumber";
        $_obfuscate_6RYLWQ��[$_obfuscate_KBWh]['value'] = "id";
        $_obfuscate_6RYLWQ��[$_obfuscate_KBWh]['engineField'] = "admCarNum";
        $_obfuscate_hqY0IaKQ = $this->_getBindFieldId( "driver" );
        $_obfuscate_6RYLWQ��[$_obfuscate_hqY0IaKQ]['data'] = $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "did", "name" ), "adm_car_driver" );
        $_obfuscate_6RYLWQ��[$_obfuscate_hqY0IaKQ]['display'] = "name";
        $_obfuscate_6RYLWQ��[$_obfuscate_hqY0IaKQ]['value'] = "did";
        $_obfuscate_6RYLWQ��[$_obfuscate_hqY0IaKQ]['engineField'] = "admCarDriver";
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", getpar( $_GET, "uFlowId", 0 ) );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", getpar( $_GET, "flowId", 0 ) );
        $_obfuscate_gftfagw� = $CNOA_DB->db_getcount( "wf_u_flow", " WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ��." AND `flowId`=".$_obfuscate_F4AbnVRh );
        if ( 0 < $_obfuscate_gftfagw� )
        {
            $_obfuscate_pnrw_ipvpw�� = $CNOA_DB->db_getone( array( "cid", "driver" ), "adm_car_apply", " WHERE `uFlowId`=".$_obfuscate_TlvKhtsoOQ�� );
            foreach ( $_obfuscate_ZOv4cVx67w�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                if ( $_obfuscate_6A��['id'] == $_obfuscate_pnrw_ipvpw��['cid'] )
                {
                    $_obfuscate_6RYLWQ��[$_obfuscate_KBWh]['data'][$_obfuscate_5w��]['default'] = TRUE;
                    $_obfuscate_ZOv4cVx67w��[$_obfuscate_5w��]['selected'] = TRUE;
                }
            }
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                if ( $_obfuscate_6A��['did'] == $_obfuscate_pnrw_ipvpw��['driver'] )
                {
                    $_obfuscate_6RYLWQ��[$_obfuscate_hqY0IaKQ]['data'][$_obfuscate_5w��]['default'] = TRUE;
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['selected'] = TRUE;
                }
            }
        }
        if ( $_obfuscate_h8xAiUQ� == "phone" )
        {
            $_obfuscate_SeV31Q�� = array( );
            foreach ( $_obfuscate_ZOv4cVx67w�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_ZOv4cVx67w��[$_obfuscate_5w��]['label'] = $_obfuscate_6A��['id'];
                $_obfuscate_ZOv4cVx67w��[$_obfuscate_5w��]['value'] = $_obfuscate_6A��['carnumber'];
                unset( $_obfuscate_6A��['carnumber']['carnumber'] );
            }
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['label'] = $_obfuscate_6A��['did'];
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['value'] = $_obfuscate_6A��['name'];
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['id'] = $_obfuscate_6A��['did'];
                unset( $_obfuscate_6A��['did']['name'] );
                unset( $_obfuscate_6A��['did']['did'] );
            }
            $_obfuscate_SeV31Q��[$_obfuscate_KBWh] = $_obfuscate_ZOv4cVx67w��;
            $_obfuscate_SeV31Q��[$_obfuscate_hqY0IaKQ] = $_obfuscate_mPAjEGLn;
            return $_obfuscate_SeV31Q��;
        }
        $_obfuscate_6RYLWQ��[$_obfuscate_KBWh]['bindFieldId'] = $_obfuscate_hqY0IaKQ;
        return $_obfuscate_6RYLWQ��;
    }

    protected function _apply( )
    {
        $this->makeData4Table( );
        $GLOBALS['_POST']['uFlowId'] = $this->uFlowId;
        $GLOBALS['_POST']['checkid'] = $this->nextStepUid;
        app::loadapp( "adm", "carApply" )->api_add( FALSE );
    }

    protected function _approve( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_01zn_w�� = $this->_getBindFieldId( "cid" );
        $_obfuscate_mhuUfOL5Yw�� = $this->_getBindFieldId( "driver" );
        $_obfuscate_KBWh = $_POST["wf_engine_field_".$_obfuscate_01zn_w��];
        $_obfuscate_hqY0IaKQ = $_POST["wf_engine_field_".$_obfuscate_mhuUfOL5Yw��];
        $CNOA_DB->db_update( array(
            "checkid" => $_obfuscate_7Ri3,
            "cid" => $_obfuscate_KBWh,
            "driver" => $_obfuscate_hqY0IaKQ
        ), "adm_car_apply", "WHERE `uFlowId`=".$this->uFlowId );
        $CNOA_DB->db_update( array(
            "checkid" => $_obfuscate_7Ri3
        ), "adm_car_check", "WHERE `uFlowId`=".$this->uFlowId );
        $GLOBALS['_POST']['id'] = $CNOA_DB->db_getfield( "id", "adm_car_check", "WHERE `uFlowId`=".$this->uFlowId );
        if ( !isset( $_POST["wf_field_".$this->bindCheck['id']] ) )
        {
            msg::callback( FALSE, lang( "noBindingField" ) );
        }
        switch ( $_POST["wf_field_".$this->bindCheck['id']] )
        {
        case $this->bindCheck['idea'][0] :
            $GLOBALS['_POST']['pass'] = "pass";
            break;
        case $this->bindCheck['idea'][1] :
            $GLOBALS['_POST']['pass'] = "unpass";
            break;
        default :
            msg::callback( FALSE, lang( "notAppCpndition" ) );
        }
        app::loadapp( "adm", "carCheck" )->api_pass( FALSE );
    }

    protected function _control( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $CNOA_DB->db_update( array(
            "tid" => $_obfuscate_7Ri3
        ), "adm_car_apply", "WHERE `uFlowId`=".$this->uFlowId );
        $_obfuscate_KBWh = $this->_getBindFieldId( "cid" );
        $_obfuscate_hqY0IaKQ = $this->_getBindFieldId( "driver" );
        $_obfuscate_01zn_w�� = $_POST["wf_engine_field_".$_obfuscate_KBWh];
        $_obfuscate_mhuUfOL5Yw�� = $_POST["wf_engine_field_".$_obfuscate_hqY0IaKQ];
        $CNOA_DB->db_update( array(
            "cid" => $_obfuscate_01zn_w��,
            "driver" => $_obfuscate_mhuUfOL5Yw��
        ), "adm_car_apply", "WHERE `uFlowId`=".$this->uFlowId );
        $GLOBALS['_POST']['aid'] = $CNOA_DB->db_getfield( "id", "adm_car_apply", "WHERE `uFlowId`=".$this->uFlowId );
        $GLOBALS['_POST']['type'] = "unuse";
        $this->makeData4Table( );
        app::loadapp( "adm", "carTransport" )->api_add( FALSE );
    }

    protected function _giveback( )
    {
        global $CNOA_DB;
        $GLOBALS['_POST']['aid'] = $CNOA_DB->db_getfield( "id", "adm_car_apply", "WHERE `uFlowId`=".$this->uFlowId );
        $GLOBALS['_POST']['type'] = "useover";
        $this->makeData4Table( );
        app::loadapp( "adm", "carTransport" )->api_add( FALSE );
    }

}

?>
