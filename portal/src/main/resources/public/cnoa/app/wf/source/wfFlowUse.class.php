<?php

class wfFlowUse extends wfCommon
{

    public function run( )
    {
        $_obfuscate_5_QxeMoÿ = getpar( $_GET, "modul", "" );
        switch ( $_obfuscate_5_QxeMoÿ )
        {
        case "new" :
            app::loadapp( "wf", "flowUseNew" )->run( );
            return;
        case "newfree" :
            app::loadapp( "wf", "flowUseNewfree" )->run( );
            return;
        case "draft" :
            app::loadapp( "wf", "flowUseDraft" )->run( );
            return;
        case "todo" :
            app::loadapp( "wf", "flowUseTodo" )->run( );
            return;
        case "done" :
            app::loadapp( "wf", "flowUseDone" )->run( );
            return;
        case "view" :
            app::loadapp( "wf", "flowUseView" )->run( );
            return;
        case "proxy" :
            app::loadapp( "wf", "flowUseProxy" )->run( );
            return;
        case "getBalanceByDeptId" :
            $this->_getBalanceByDeptId( );
            return;
        case "getBalanceByProjId" :
            $this->_getBalanceByProjId( );
            return;
        case "getProjCombo" :
            $this->_getProjCombo( );
            return;
        case "getAttLeaveDays" :
            $this->_getAttLeaveDays( );
            return;
        case "getAttEvectionDays" :
            $this->_getAttEvectionDays( );
            return;
        case "getAttTime" :
            $this->_getAttTime( );
            return;
        case "getBindDriver" :
            $this->_getBindDriver( );
            return;
        case "getFieldsId" :
            $this->_getFieldsId( );
            return;
        }
        msg::callback( FALSE, "æ²¡æœ‰è®¾ç½®modulå‚æ•°" );
    }

    private function _getBalanceByDeptId( )
    {
        $_obfuscate_2sZ8Toxw = intval( getpar( $_POST, "deptId", 0 ) );
        $_obfuscate_QgimGDPtdQÿÿ = app::loadapp( "budget", "Setting" )->api_getBalanceByDeptId( $_obfuscate_2sZ8Toxw );
        echo $_obfuscate_QgimGDPtdQÿÿ;
        exit( );
    }

    private function _getBalanceByProjId( )
    {
        $_obfuscate_nGayiuNZ = intval( getpar( $_POST, "projId", 0 ) );
        $_obfuscate_QgimGDPtdQÿÿ = app::loadapp( "budget", "Setting" )->api_getBalanceByProjId( $_obfuscate_nGayiuNZ );
        echo $_obfuscate_QgimGDPtdQÿÿ;
        exit( );
    }

    private function _getProjCombo( )
    {
        global $CNOA_DB;
        $_obfuscate_2sZ8Toxw = intval( getpar( $_POST, "deptId", 0 ) );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "projId", "name" ), "budget_set_budget_project", "WHERE `deptId`=".$_obfuscate_2sZ8Toxw );
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _getAttLeaveDays( $return = FALSE )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $truename = $CNOA_SESSION->get( "TRUENAME" );
        $stime = strtotime( getpar( $_POST, "stime" ) );
        $etime = strtotime( getpar( $_POST, "etime" ) );
        $fieldsId = ( integer )getpar( $_POST, "fieldsId" );
        if ( $etime < $stime )
        {
            msg::callback( FALSE, lang( "endTimeNoBigStartTime" ) );
        }
        if ( $fieldsId !== 0 )
        {
            $result = $CNOA_DB->db_getone( array( "flowId", "odata" ), $this->t_set_field, "WHERE `id`=".$fieldsId );
            $odata = json_decode( str_replace( "'", "\"", $result['odata'] ), TRUE );
            $flowId = $result['flowId'];
            $fieldName = $odata['fieldsName'];
            $fieldId = $CNOA_DB->db_getfield( "id", $this->t_set_field, "WHERE `flowId`=".$flowId." AND `name`='{$fieldName}'" );
            include_once( CNOA_PATH."/app/att/inc/attCommon.class.php" );
            $days = app::loadapp( "att", "Person" )->api_getAttLeaveDays( $stime, $etime, $uid, "getdays", "" );
            $data = array( );
            $data['days'] = $days;
            $data['stime'] = date( "Y-m-d H:i:s", $stime );
            $data['etime'] = date( "Y-m-d H:i:s", $etime );
            $data['fieldId'] = $fieldId;
            if ( $return )
            {
                return $data;
            }
            ( );
            $ds = new dataStore( );
            $ds->data = $data;
            echo $ds->makeJsonData( );
        }
    }

    private function _getAttEvectionDays( $return = FALSE )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $truename = $CNOA_SESSION->get( "TRUENAME" );
        $stime = strtotime( getpar( $_POST, "stime" ) );
        $etime = strtotime( getpar( $_POST, "etime" ) );
        $fieldsId = ( integer )getpar( $_POST, "fieldsId" );
        if ( $etime < $stime )
        {
            msg::callback( FALSE, lang( "endTimeNoBigStartTime" ) );
        }
        if ( $fieldsId !== 0 )
        {
            $result = $CNOA_DB->db_getone( array( "flowId", "odata" ), $this->t_set_field, "WHERE `id`=".$fieldsId );
            $odata = json_decode( str_replace( "'", "\"", $result['odata'] ), TRUE );
            $flowId = $result['flowId'];
            $fieldName = $odata['fieldsName'];
            $fieldId = $CNOA_DB->db_getfield( "id", $this->t_set_field, "WHERE `flowId`=".$flowId." AND `name`='{$fieldName}'" );
            include_once( CNOA_PATH."/app/att/inc/attCommon.class.php" );
            $days = app::loadapp( "att", "Person" )->api_getAttEvectionDays( $stime, $etime, $uid, "getdays", "" );
            $data = array( );
            $data['days'] = $days;
            $data['stime'] = date( "Y-m-d H:i:s", $stime );
            $data['etime'] = date( "Y-m-d H:i:s", $etime );
            $data['fieldId'] = $fieldId;
            if ( $return )
            {
                return $data;
            }
            ( );
            $ds = new dataStore( );
            $ds->data = $data;
            echo $ds->makeJsonData( );
        }
    }

    private function _getBindDriver( )
    {
        global $CNOA_DB;
        $_obfuscate_KBWh = ( integer )getpar( $_POST, "cid" );
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_xs33Yt_k = $CNOA_DB->db_select( array( "did", "name" ), "adm_car_driver", "WHERE cid = ".$_obfuscate_KBWh );
        if ( !is_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_xs33Yt_k = $CNOA_DB->db_select( array( "did", "name" ), "adm_car_driver" );
        }
        foreach ( $_obfuscate_xs33Yt_k as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_Vwty]['display'] = $_obfuscate_VgKtFegÿ['name'];
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_Vwty]['value'] = $_obfuscate_VgKtFegÿ['did'];
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
    }

    public function api_getWfAttLeaveDays( )
    {
        return $this->_getAttLeaveDays( TRUE );
    }

    private function _getFieldsId( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate__Wi6396IheAÿ = $CNOA_SESSION->get( "TRUENAME" );
        $_obfuscate_255EBzbYVVEÿ = ( integer )getpar( $_POST, "fieldsId" );
        if ( $_obfuscate_255EBzbYVVEÿ !== 0 )
        {
            $_obfuscate_xs33Yt_k = $CNOA_DB->db_getone( array( "flowId", "odata" ), $this->t_set_field, "WHERE `id`=".$_obfuscate_255EBzbYVVEÿ );
            $_obfuscate_p5ZWxr4ÿ = json_decode( str_replace( "'", "\"", $_obfuscate_xs33Yt_k['odata'] ), TRUE );
            $_obfuscate_F4AbnVRh = $_obfuscate_xs33Yt_k['flowId'];
            $_obfuscate_8UKWnDlantDd = $_obfuscate_p5ZWxr4ÿ['fieldsName'];
            $_obfuscate_8jhldA9Y9Aÿÿ = $CNOA_DB->db_getfield( "id", $this->t_set_field, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND `name`='{$_obfuscate_8UKWnDlantDd}'" );
            $_obfuscate_6RYLWQÿÿ = array( );
            $_obfuscate_6RYLWQÿÿ['fieldId'] = $_obfuscate_8jhldA9Y9Aÿÿ;
            ( );
            $_obfuscate_NlQÿ = new dataStore( );
            $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
            echo $_obfuscate_NlQÿ->makeJsonData( );
        }
    }

    private function _getAttTime( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $truename = $CNOA_SESSION->get( "TRUENAME" );
        $stime = strtotime( getpar( $_POST, "stime" ) );
        $etime = strtotime( getpar( $_POST, "etime" ) );
        $fieldsId = ( integer )getpar( $_POST, "fieldsId" );
        if ( $etime < $stime )
        {
            msg::callback( FALSE, lang( "endTimeNoBigStartTime" ) );
        }
        if ( $fieldsId !== 0 )
        {
            $result = $CNOA_DB->db_getone( array( "flowId", "odata" ), $this->t_set_field, "WHERE `id`=".$fieldsId );
            $odata = json_decode( str_replace( "'", "\"", $result['odata'] ), TRUE );
            $flowId = $result['flowId'];
            $fieldName = $odata['fieldsName'];
            $fieldId = $CNOA_DB->db_getfield( "id", $this->t_set_field, "WHERE `flowId`=".$flowId." AND `name`='{$fieldName}'" );
            $fieldName2 = $odata['fieldsName2'];
            $fieldId2 = $CNOA_DB->db_getfield( "id", $this->t_set_field, "WHERE `flowId`=".$flowId." AND `name`='{$fieldName2}'" );
            include_once( CNOA_PATH."/app/att/inc/attCommon.class.php" );
            $attTime = app::loadapp( "att", "Person" )->api_getAttTimeByTimeBucket( $stime, $etime, $uid );
            $data = array( );
            if ( $odata['outputType'] == "dh" )
            {
                $data['days'] = $attTime['dd'];
                $data['hour'] = $attTime['dh'];
            }
            else
            {
                $data['days'] = $attTime[$odata['outputType']];
            }
            $data['stime'] = date( "Y-m-d H:i:s", $stime );
            $data['etime'] = date( "Y-m-d H:i:s", $etime );
            $data['fieldId'] = $fieldId;
            $data['fieldId2'] = $fieldId2;
            if ( $return )
            {
                return $data;
            }
            ( );
            $ds = new dataStore( );
            $ds->data = $data;
            echo $ds->makeJsonData( );
        }
    }

}

?>
