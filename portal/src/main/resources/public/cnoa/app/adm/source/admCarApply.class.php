<?php//decode by qq2859470class admCarApply extends model{    private $table_info = "adm_car_info";    private $table_transport = "adm_car_transport";    private $table_apply = "adm_car_apply";    private $table_check = "adm_car_check";    private $table_driver = "adm_car_driver";    private $rows = 15;    public function run( )    {        $_obfuscate_M_5JJwÿÿ = getpar( $_GET, "task", "" );        if ( $_obfuscate_M_5JJwÿÿ == "applylist" )        {            global $CNOA_CONTROLLER;            $_obfuscate_0W8ÿ = getpar( $_GET, "cid", 0 );            $_obfuscate_vholQÿÿ = getpar( $_GET, "from", "" );            if ( $_obfuscate_vholQÿÿ == "loadPage" )            {                $_obfuscate_2PfU = getpar( $_GET, "aid", 0 );                $GLOBALS['GLOBALS']['car']['apply']['list'] = $_obfuscate_2PfU;                $GLOBALS['GLOBALS']['car']['apply']['from'] = "apply";                $_obfuscate_pp9pYwÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/car/applylist.htm";                $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_pp9pYwÿÿ );                exit( );            }        }        else if ( $_obfuscate_M_5JJwÿÿ == "loadPage" )        {            global $CNOA_CONTROLLER;            $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/car/apply.htm";            $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQÿÿ );            exit( );        }        else if ( $_obfuscate_M_5JJwÿÿ == "getcarnumber" )        {            $this->_getCarnumber( );        }        else if ( $_obfuscate_M_5JJwÿÿ == "department" )        {            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";            echo app::loadapp( "main", "struct" )->api_getStructTree( );            exit( );        }        else if ( $_obfuscate_M_5JJwÿÿ == "gettransport" )        {            $this->_gettransport( );        }        else if ( $_obfuscate_M_5JJwÿÿ == "add" )        {            $this->_add( );        }        else if ( $_obfuscate_M_5JJwÿÿ == "list" )        {            $this->_list( );        }        else if ( $_obfuscate_M_5JJwÿÿ == "info" )        {            global $CNOA_DB;            $_obfuscate_0W8ÿ = getpar( $_GET, "id", 0 );            $_obfuscate_SeV31Qÿÿ = $CNOA_DB->db_getone( array( "cid" ), $this->table_apply, "WHERE `id`=".$_obfuscate_0W8ÿ );            app::loadapp( "adm", "carInfo" )->api_admCarInfoShow( $_obfuscate_SeV31Qÿÿ['cid'] );        }        else if ( $_obfuscate_M_5JJwÿÿ == "getapplylist" )        {            $this->_getapplylist( );        }        else if ( $_obfuscate_M_5JJwÿÿ == "getapplydetails" )        {            $this->_getapplydetails( );        }        else if ( $_obfuscate_M_5JJwÿÿ == "getdriver" )        {            $this->_getdriver( );        }        else if ( $_obfuscate_M_5JJwÿÿ == "delete" )        {            $this->_delete( );        }        else if ( $_obfuscate_M_5JJwÿÿ == "revoke" )        {            $this->_revoke( );        }        else if ( $_obfuscate_M_5JJwÿÿ == "delrecord" )        {            $this->_delRecord( );        }    }    private function _add( $_obfuscate_9lVMg1Gb = TRUE )    {        global $CNOA_DB;        global $CNOA_SESSION;        $_obfuscate_6RYLWQÿÿ['cid'] = getpar( $_POST, "cid" );        $_obfuscate_6RYLWQÿÿ['caruser'] = getpar( $_POST, "caruser", $CNOA_SESSION->get( "TRUENAME" ) );        $_obfuscate_PUTXpN5XKFnM = getpar( $_POST, "startdate", "" );        $_obfuscate_Xif3q_HAaupN = getpar( $_POST, "starttime", "" );        $_obfuscate_hcivMGsnAÿÿ = getpar( $_POST, "enddate", "" );        $_obfuscate_4mjhMGF9Kgÿÿ = getpar( $_POST, "endtime", "" );        $_obfuscate_6RYLWQÿÿ['way'] = getpar( $_POST, "way", "" );        $_obfuscate_6RYLWQÿÿ['deptID'] = getpar( $_POST, "deptID", "" );        $_obfuscate_6RYLWQÿÿ['destination'] = getpar( $_POST, "destination", "" );        $_obfuscate_6RYLWQÿÿ['checkid'] = getpar( $_POST, "checkid", "" );        $_obfuscate_6RYLWQÿÿ['driver'] = getpar( $_POST, "driver", "" );        $_obfuscate_6RYLWQÿÿ['tid'] = getpar( $_POST, "tid", "" );        $_obfuscate_6RYLWQÿÿ['other'] = getpar( $_POST, "other", "" );        $_obfuscate_6RYLWQÿÿ['uFlowId'] = getpar( $_POST, "uFlowId", 0 );        $_obfuscate_6RYLWQÿÿ['status'] = 2;        $_obfuscate_6RYLWQÿÿ['starttime'] = strtotime( $_obfuscate_PUTXpN5XKFnM.$_obfuscate_Xif3q_HAaupN );        $_obfuscate_6RYLWQÿÿ['endtime'] = strtotime( $_obfuscate_hcivMGsnAÿÿ.$_obfuscate_4mjhMGF9Kgÿÿ );        $_obfuscate_6RYLWQÿÿ['applyUid'] = $CNOA_SESSION->get( "UID" );        $_obfuscate_8Svggs52liYÿ = "";        if ( getpar( $_GET, "app", "" ) === "wf" )        {            $_obfuscate_8Svggs52liYÿ = create_function( "", "if(".$_obfuscate_6RYLWQÿÿ['uFlowId']."!=0){global \$CNOA_DB;\$CNOA_DB->db_delete(\"wf_u_flow\", \"WHERE `uFlowId`=".$_obfuscate_6RYLWQÿÿ['uFlowId']."\");}" );        }        if ( empty( $_obfuscate_6RYLWQÿÿ['cid'] ) )        {            msg::callback( FALSE, lang( "licenseNumRequireField" ), TRUE, FALSE, $_obfuscate_8Svggs52liYÿ );        }        if ( empty( $_obfuscate_6RYLWQÿÿ['driver'] ) )        {            msg::callback( FALSE, lang( "driverRequiredField" ), TRUE, FALSE, $_obfuscate_8Svggs52liYÿ );        }        if ( empty( $_obfuscate_6RYLWQÿÿ['starttime'] ) )        {            msg::callback( FALSE, lang( "sTimeRequireField" ), TRUE, FALSE, $_obfuscate_8Svggs52liYÿ );        }        if ( empty( $_obfuscate_6RYLWQÿÿ['endtime'] ) )        {            msg::callback( FALSE, lang( "eTimeRequireField" ), TRUE, FALSE, $_obfuscate_8Svggs52liYÿ );        }        if ( empty( $_obfuscate_6RYLWQÿÿ['checkid'] ) )        {            msg::callback( FALSE, lang( "appPerpleRequireField" ), TRUE, FALSE, $_obfuscate_8Svggs52liYÿ );        }        $_obfuscate_FwcndFIjewÿÿ = $CNOA_DB->db_getone( array( "status", "carnumber" ), $this->table_info, "WHERE `id`=".$_obfuscate_6RYLWQÿÿ['cid'] );        if ( $_obfuscate_FwcndFIjewÿÿ['status'] != 4 )        {            if ( $_obfuscate_FwcndFIjewÿÿ['status'] == 1 )            {                msg::callback( FALSE, lang( "vehicelScrappNotApply" ), TRUE, FALSE, $_obfuscate_8Svggs52liYÿ );            }            if ( $_obfuscate_FwcndFIjewÿÿ['status'] == 2 )            {                msg::callback( FALSE, lang( "vehicleRepairNotApply" ), TRUE, FALSE, $_obfuscate_8Svggs52liYÿ );            }            if ( $_obfuscate_FwcndFIjewÿÿ['status'] == 3 )            {                msg::callback( FALSE, lang( "vehicleDamageNotApply" ), TRUE, FALSE, $_obfuscate_8Svggs52liYÿ );            }        }        if ( $_obfuscate_6RYLWQÿÿ['endtime'] <= $_obfuscate_6RYLWQÿÿ['starttime'] )        {            msg::callback( FALSE, lang( "stimeAndEtime" ), TRUE, FALSE, $_obfuscate_8Svggs52liYÿ );        }        $_obfuscate_Ybai = $CNOA_DB->db_getone( array( "id", "starttime", "endtime" ), $this->table_apply, "WHERE (`starttime`<".$_obfuscate_6RYLWQÿÿ['endtime']." AND `endtime`>{$_obfuscate_6RYLWQÿÿ['starttime']} AND `status`=3 AND `cid`={$_obfuscate_6RYLWQÿÿ['cid']}) OR (`realstarttime`<{$_obfuscate_6RYLWQÿÿ['endtime']} AND `realendtime`>{$_obfuscate_6RYLWQÿÿ['starttime']} AND `status`=5  AND `cid`={$_obfuscate_6RYLWQÿÿ['cid']})" );        $_obfuscate_LeS8hwÿÿ = $CNOA_DB->db_getfield( "type", $this->table_apply, "WHERE (`starttime`<".$_obfuscate_6RYLWQÿÿ['endtime']." AND `endtime`>{$_obfuscate_6RYLWQÿÿ['starttime']} AND `status`=3 AND `cid`={$_obfuscate_6RYLWQÿÿ['cid']}) OR (`realstarttime`<{$_obfuscate_6RYLWQÿÿ['endtime']} AND `realendtime`>{$_obfuscate_6RYLWQÿÿ['starttime']} AND `status`=5  AND `cid`={$_obfuscate_6RYLWQÿÿ['cid']})" );        if ( 0 < $_obfuscate_Ybai['id'] && $_obfuscate_LeS8hwÿÿ != 2 )        {            msg::callback( FALSE, lang( "thisVehicle" )."(".formatdate( $_obfuscate_Ybai['starttime'], "Y-m-d H:i" )."---".formatdate( $_obfuscate_Ybai['endtime'], "Y-m-d H:i" ).")".lang( "timePeriodBeenAppOrUseing" ), TRUE, FALSE, $_obfuscate_8Svggs52liYÿ );        }        unset( $_obfuscate_8Svggs52liYÿ );        $_obfuscate_0W8ÿ = $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->table_apply );        $_obfuscate_3umuFHrAlwczOQÿÿ = notice::add( $_obfuscate_6RYLWQÿÿ['checkid'], lang( "vehicleAppMgr" ), lang( "car" ).( "[".$_obfuscate_FwcndFIjewÿÿ['carnumber']."]" ).lang( "needYouApproval" ), "index.php?app=adm&func=car&action=check", "", 11, $_obfuscate_0W8ÿ );        $_obfuscate_gb3bCas1['touid'] = $_obfuscate_6RYLWQÿÿ['checkid'];        $_obfuscate_gb3bCas1['from'] = 11;        $_obfuscate_gb3bCas1['fromid'] = $_obfuscate_0W8ÿ;        $_obfuscate_gb3bCas1['href'] = "index.php?app=adm&func=car&action=check";        $_obfuscate_gb3bCas1['title'] = lang( "car" ).( "[".$_obfuscate_FwcndFIjewÿÿ['carnumber']."]ï¼" ).lang( "needYouApproval" ).",".lang( "submitter" ).": [".$CNOA_SESSION->get( "TRUENAME" )."]";        $_obfuscate_gb3bCas1['content'] = lang( "vehicleSubmitUseTime" )."[".formatdate( $_obfuscate_6RYLWQÿÿ['starttime'], "Y-m-d H:i" )."]".lang( "to" )."[".formatdate( $_obfuscate_6RYLWQÿÿ['endtime'], "Y-m-d H:i" )."]";        if ( !empty( $_obfuscate_6RYLWQÿÿ['caruser'] ) )        {            $_obfuscate_gb3bCas1['content'] .= "  ".lang( "vehicleUser" ).( "[".$_obfuscate_6RYLWQÿÿ['caruser']."]" );        }        if ( !empty( $_obfuscate_6RYLWQÿÿ['driver'] ) )        {            $_obfuscate_gb3bCas1['content'] .= "  ".lang( "driver" ).( "[".$_obfuscate_6RYLWQÿÿ['driver']."]" );        }        $_obfuscate_gb3bCas1['funname'] = lang( "carMgr" );        $_obfuscate_gb3bCas1['move'] = lang( "approval2" );        if ( $_obfuscate_9lVMg1Gb )        {            $_obfuscate_Z9Q9tYdcvqMÿ = notice::add2( $_obfuscate_gb3bCas1 );        }        $CNOA_DB->db_insert( array(            "noticeid_c" => $_obfuscate_3umuFHrAlwczOQÿÿ,            "todoid_c" => $_obfuscate_Z9Q9tYdcvqMÿ,            "aid" => $_obfuscate_0W8ÿ,            "preuid" => $_obfuscate_6RYLWQÿÿ['applyUid'],            "cid" => $_obfuscate_6RYLWQÿÿ['cid'],            "checkid" => $_obfuscate_6RYLWQÿÿ['checkid'],            "notes" => $_obfuscate_6RYLWQÿÿ['other'],            "uFlowId" => $_obfuscate_6RYLWQÿÿ['uFlowId']        ), $this->table_check );        app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 143, $_obfuscate_FwcndFIjewÿÿ['carnumber'], lang( "appVehicleRecord" ) );        if ( $_obfuscate_9lVMg1Gb )        {            msg::callback( TRUE, lang( "successopt" ) );        }    }    private function _getdriver( )    {        global $CNOA_DB;        $_obfuscate_KBWh = getpar( $_POST, "cid", "" );        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->table_driver, "WHERE `cid` = '".$_obfuscate_KBWh."' " );        if ( $_obfuscate_mPAjEGLn === FALSE )        {            $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->table_driver );        }        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )        {            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['driver'] = $_obfuscate_6Aÿÿ['name'];        }        ( );        $_obfuscate_SUjPN94Er7yI = new dataStore( );        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_mPAjEGLn;        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );        exit( );    }    private function _delete( )    {        global $CNOA_DB;        global $CNOA_SESSION;        $_obfuscate_0W8ÿ = getpar( $_POST, "id", 0 );        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );        $_obfuscate_Thgÿ = $CNOA_DB->db_getone( array( "noticeid_t", "todoid_t", "cid" ), $this->table_apply, "WHERE `status` = 2 AND `id` = '".$_obfuscate_0W8ÿ."' AND `applyUid` = '{$_obfuscate_7Ri3}' " );        $_obfuscate_yX0p2NnzSdui = $CNOA_DB->db_getfield( "carnumber", $this->table_info, "WHERE `id`='".$_obfuscate_Thgÿ['cid']."'" );        $_obfuscate_jNJj = $CNOA_DB->db_select( array( "noticeid_c", "todoid_c" ), $this->table_check, "WHERE `aid` = '".$_obfuscate_0W8ÿ."' " );        $_obfuscate_gb3bCas1 = array(            $_obfuscate_Thgÿ['noticeid_t']        );        $_obfuscate_njPckAÿÿ = array(            $_obfuscate_Thgÿ['todoid_t']        );        if ( !is_array( $_obfuscate_jNJj ) )        {            $_obfuscate_jNJj = array( );        }        foreach ( $_obfuscate_jNJj as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )        {            $_obfuscate_gb3bCas1[] = $_obfuscate_6Aÿÿ['noticeid_c'];            $_obfuscate_njPckAÿÿ[] = $_obfuscate_6Aÿÿ['todoid_c'];        }        notice::deletenotice( $_obfuscate_gb3bCas1, $_obfuscate_njPckAÿÿ );        if ( empty( $_obfuscate_Thgÿ ) )        {            msg::callback( FALSE, lang( "notDelThisApp" ) );        }        $CNOA_DB->db_delete( $this->table_apply, "WHERE `id` = '".$_obfuscate_0W8ÿ."' " );        $CNOA_DB->db_delete( $this->table_check, "WHERE `aid` = '".$_obfuscate_0W8ÿ."' " );        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 143, $_obfuscate_yX0p2NnzSdui, lang( "appVehicleRecord" ) );        msg::callback( TRUE, lang( "successopt" ) );    }    private function _delRecord( )    {        global $CNOA_DB;        global $CNOA_SESSION;        $_obfuscate_0W8ÿ = getpar( $_POST, "id", 0 );        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );        $_obfuscate_Thgÿ = $CNOA_DB->db_getone( array( "noticeid_t", "todoid_t", "cid" ), $this->table_apply, "WHERE `id` = '".$_obfuscate_0W8ÿ."' AND `applyUid` = '{$_obfuscate_7Ri3}' " );        $_obfuscate_yX0p2NnzSdui = $CNOA_DB->db_getfield( "carnumber", $this->table_info, "WHERE `id`='".$_obfuscate_Thgÿ['cid']."'" );        $_obfuscate_jNJj = $CNOA_DB->db_select( array( "noticeid_c", "todoid_c" ), $this->table_check, "WHERE `aid` = '".$_obfuscate_0W8ÿ."' " );        $_obfuscate_gb3bCas1 = array(            $_obfuscate_Thgÿ['noticeid_t']        );        $_obfuscate_njPckAÿÿ = array(            $_obfuscate_Thgÿ['todoid_t']        );        if ( !is_array( $_obfuscate_jNJj ) )        {            $_obfuscate_jNJj = array( );        }        foreach ( $_obfuscate_jNJj as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )        {            $_obfuscate_gb3bCas1[] = $_obfuscate_6Aÿÿ['noticeid_c'];            $_obfuscate_njPckAÿÿ[] = $_obfuscate_6Aÿÿ['todoid_c'];        }        notice::deletenotice( $_obfuscate_gb3bCas1, $_obfuscate_njPckAÿÿ );        if ( empty( $_obfuscate_Thgÿ ) )        {            msg::callback( FALSE, lang( "notDelThisApp" ) );        }        $CNOA_DB->db_delete( $this->table_apply, "WHERE `id` = '".$_obfuscate_0W8ÿ."' " );        $CNOA_DB->db_delete( $this->table_check, "WHERE `aid` = '".$_obfuscate_0W8ÿ."' " );        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 143, $_obfuscate_yX0p2NnzSdui, lang( "appVehicleRecord" ) );        msg::callback( TRUE, lang( "successopt" ) );    }    private function _list( )    {        global $CNOA_DB;        global $CNOA_SESSION;        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );        $_obfuscate_mV9HBLYÿ = getpar( $_POST, "start", 0 );        $_obfuscate_Bj089LCE6qL = getpar( $_POST, "storeType", "uncheck" );        $_obfuscate_Bk2lGlkÿ = "WHERE 1 ";        if ( $_obfuscate_Bj089LCE6qL == "uncheck" )        {            $_obfuscate_Bk2lGlkÿ .= "AND `status` = 2 ";        }        else if ( $_obfuscate_Bj089LCE6qL == "check" )        {            $_obfuscate_Bk2lGlkÿ .= "AND (`status` = 0 OR `status` = 1 OR `status` = 3 OR `status` = 5 OR `status` = 6) ";        }        $_obfuscate_38lJ6BqA = getpar( $_POST, "number", 0 );        $_obfuscate_mCI3ESxEoQÿÿ = getpar( $_POST, "caruser", "" );        $_obfuscate_qx37NMÿ = getpar( $_POST, "stime", 0 );        $_obfuscate_KWKBW4ÿ = getpar( $_POST, "etime", 0 );        if ( !empty( $_obfuscate_38lJ6BqA ) )        {            $_obfuscate_SeV31Qÿÿ = $CNOA_DB->db_select( array( "id" ), $this->table_info, "WHERE `carnumber` LIKE '%".$_obfuscate_38lJ6BqA."%' " );            if ( empty( $_obfuscate_SeV31Qÿÿ ) )            {                $_obfuscate_Bk2lGlkÿ .= "AND `cid` = 0 ";            }            else            {                if ( !is_array( $_obfuscate_SeV31Qÿÿ ) )                {                    $_obfuscate_SeV31Qÿÿ = array( );                }                foreach ( $_obfuscate_SeV31Qÿÿ as $_obfuscate_6Aÿÿ )                {                    $_obfuscate_vLQXJvoÿ[] = $_obfuscate_6Aÿÿ['id'];                }                $_obfuscate_Bk2lGlkÿ .= "AND `cid` IN (".implode( ",", $_obfuscate_vLQXJvoÿ ).") ";            }        }        if ( !empty( $_obfuscate_mCI3ESxEoQÿÿ ) )        {            $_obfuscate_Bk2lGlkÿ .= "AND `caruser` LIKE '%".$_obfuscate_mCI3ESxEoQÿÿ."%' ";        }        if ( !empty( $_obfuscate_qx37NMÿ ) )        {            $_obfuscate_qx37NMÿ = strtotime( $_obfuscate_qx37NMÿ." 00:00:00" );            $_obfuscate_Bk2lGlkÿ .= "AND `starttime` > ".$_obfuscate_qx37NMÿ." ";        }        if ( !empty( $_obfuscate_KWKBW4ÿ ) )        {            $_obfuscate_KWKBW4ÿ = strtotime( $_obfuscate_KWKBW4ÿ." 23:59:59" );            $_obfuscate_Bk2lGlkÿ .= "AND `endtime` < ".$_obfuscate_KWKBW4ÿ." ";        }        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->table_apply, $_obfuscate_Bk2lGlkÿ.( "AND `applyUid` = '".$_obfuscate_7Ri3."' ORDER BY `id` DESC LIMIT {$_obfuscate_mV9HBLYÿ}, {$this->rows} " ) );        if ( !is_array( $_obfuscate_mPAjEGLn ) )        {            $_obfuscate_mPAjEGLn = array( );        }        $_obfuscate_qBtzXvhm = array( 0 );        $_obfuscate_kFg0eB0Sdh8oj6Iÿ = array( 0 );        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )        {            $_obfuscate_qBtzXvhm[] = $_obfuscate_6Aÿÿ['cid'];            $_obfuscate_kFg0eB0Sdh8oj6Iÿ[] = $_obfuscate_6Aÿÿ['checkid'];        }        $_obfuscate_FwcndFIjewÿÿ = app::loadapp( "adm", "carInfo" )->api_getCarInfo( $_obfuscate_qBtzXvhm );        $_obfuscate_SM9gqiNDyIIy0pcÿ = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_kFg0eB0Sdh8oj6Iÿ );        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )        {            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['carnumber'] = $_obfuscate_FwcndFIjewÿÿ[$_obfuscate_6Aÿÿ['cid']]['carnumber'];            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['checkname'] = $_obfuscate_SM9gqiNDyIIy0pcÿ[$_obfuscate_6Aÿÿ['checkid']]['truename'];            if ( $_obfuscate_6Aÿÿ['endtime'] < $GLOBALS['CNOA_TIMESTAMP'] )            {                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['checktime'] = 1;            }            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['starttime'] = formatdate( $_obfuscate_6Aÿÿ['starttime'], "Y-m-d H:i" );            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['endtime'] = formatdate( $_obfuscate_6Aÿÿ['endtime'], "Y-m-d H:i" );        }        ( );        $_obfuscate_SUjPN94Er7yI = new dataStore( );        $_obfuscate_SUjPN94Er7yI->total = $CNOA_DB->db_getcount( $this->table_apply, $_obfuscate_IRFhnYwÿ );        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_mPAjEGLn;        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );        exit( );    }    private function __formatDate( $_obfuscate_VgKtFegÿ )    {        if ( $_obfuscate_VgKtFegÿ == 0 )        {            return "";        }        return date( "Y-m-d H:i", $_obfuscate_VgKtFegÿ );    }    private function __getCarNumberByOne( $_obfuscate_0W8ÿ )    {        global $CNOA_DB;        $_obfuscate_6RYLWQÿÿ = $CNOA_DB->db_getone( array( "carnumber" ), $this->table_info, "WHERE `id`='".$_obfuscate_0W8ÿ."'" );        if ( !is_array( $_obfuscate_6RYLWQÿÿ ) )        {            $_obfuscate_6RYLWQÿÿ['carnumber'] = "";        }        return $_obfuscate_6RYLWQÿÿ['carnumber'];    }    private function __getCarInfoByOne( $_obfuscate_0W8ÿ )    {        global $CNOA_DB;        $_obfuscate_6RYLWQÿÿ = $CNOA_DB->db_getone( "*", $this->table_info, "WHERE `id`='".$_obfuscate_0W8ÿ."'" );        return $_obfuscate_6RYLWQÿÿ;    }    private function _getCarnumber( )    {        global $CNOA_DB;        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "carnumber", "id", "notes", "realwayend" ), $this->table_info );        ( );        $_obfuscate_SUjPN94Er7yI = new dataStore( );        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_mPAjEGLn;        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );        exit( );    }    private function _getapplydetails( )    {        global $CNOA_DB;        global $CNOA_CONTROLLER;        $_obfuscate_2PfU = getpar( $_GET, "aid", 0 );        $_obfuscate_mPAjEGLn = $CNOA_DB->db_getone( "*", $this->table_apply, "WHERE `id`=".$_obfuscate_2PfU );        $_obfuscate_kilXYnsVbQÿÿ = $this->__getCarInfoByOne( $_obfuscate_mPAjEGLn['cid'] );        $_obfuscate_mPAjEGLn['carnumber'] = $_obfuscate_kilXYnsVbQÿÿ['carnumber'];        $_obfuscate_mPAjEGLn['notes'] = $_obfuscate_kilXYnsVbQÿÿ['notes'];        $_obfuscate_mPAjEGLn['applyname'] = $this->__takeUserName( $_obfuscate_mPAjEGLn['applyUid'] );        $_obfuscate_mPAjEGLn['transport'] = $this->__takeUserName( $_obfuscate_mPAjEGLn['tid'] );        $_obfuscate_mPAjEGLn['starttime'] = formatdate( $_obfuscate_mPAjEGLn['starttime'], "Y-m-d H:i" );        $_obfuscate_mPAjEGLn['endtime'] = formatdate( $_obfuscate_mPAjEGLn['endtime'], "Y-m-d H:i" );        $_obfuscate_mPAjEGLn['realstarttime'] = formatdate( $_obfuscate_mPAjEGLn['realstarttime'], "Y-m-d H:i" );        $_obfuscate_mPAjEGLn['realendtime'] = formatdate( $_obfuscate_mPAjEGLn['realendtime'], "Y-m-d H:i" );        $_obfuscate_mPAjEGLn['department'] = $_obfuscate_mPAjEGLn['deptID'] == "" ? "" : $this->__getDepartName( $_obfuscate_mPAjEGLn['deptID'] );        $GLOBALS['GLOBALS']['adm']['car']['apply'] = $_obfuscate_mPAjEGLn;        $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/car/applydetails.htm";        $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQÿÿ );        exit( );    }    public function api_getapplydetails( )    {        $this->_getapplydetails( );    }    private function __getDepartName( $_obfuscate_rfClaO11 )    {        $_obfuscate_MRcvgWNJSwÿÿ = app::loadapp( "main", "struct" )->api_getArrayList( );        $_obfuscate_XRvPgP5V0t4ÿ = $_obfuscate_MRcvgWNJSwÿÿ[$_obfuscate_rfClaO11];        return $_obfuscate_XRvPgP5V0t4ÿ;    }    private function _getapplylist( )    {        global $CNOA_DB;        $_obfuscate_2PfU = getpar( $_GET, "aid", 0 );        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->table_check, "WHERE `aid`=".$_obfuscate_2PfU );        if ( !is_array( $_obfuscate_mPAjEGLn ) )        {            $_obfuscate_mPAjEGLn = array( );        }        $_obfuscate_7wÿÿ = 1;        $_obfuscate_PVLK5jra = array( 0 );        $_obfuscate_HX1MRaQK = array( 0 );        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )        {            $_obfuscate_PVLK5jra[] = $_obfuscate_6Aÿÿ['preuid'];            $_obfuscate_PVLK5jra[] = $_obfuscate_6Aÿÿ['checkid'];            $_obfuscate_KBWh = $_obfuscate_6Aÿÿ['cid'];        }        $_obfuscate_yX0p2NnzSdui = $this->__getCarNumberByOne( $_obfuscate_KBWh );        $_obfuscate_SM9gqiNDyIIy0pcÿ = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_PVLK5jra );        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )        {            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['step'] = "ç¬¬".$_obfuscate_7wÿÿ."æ­¥";            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['prename'] = $_obfuscate_SM9gqiNDyIIy0pcÿ[$_obfuscate_6Aÿÿ['preuid']]['truename'];            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['checkname'] = $_obfuscate_SM9gqiNDyIIy0pcÿ[$_obfuscate_6Aÿÿ['checkid']]['truename'];            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['status'] = $this->__formatCheckStatus( $_obfuscate_6Aÿÿ['status'] );            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['time'] = formatdate( $_obfuscate_6Aÿÿ['time'], "Y-m-d H:i" );            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['revoketime'] = formatdate( $_obfuscate_6Aÿÿ['revoketime'], "Y-m-d H:i" );            ++$_obfuscate_7wÿÿ;        }        ( );        $_obfuscate_SUjPN94Er7yI = new dataStore( );        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_mPAjEGLn;        $_obfuscate_SUjPN94Er7yI->aid = $_obfuscate_2PfU;        $_obfuscate_SUjPN94Er7yI->carnumber = $_obfuscate_yX0p2NnzSdui;        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );        exit( );    }    public function api_getapplylist( )    {        $this->_getapplylist( );    }    private function _gettransport( )    {        global $CNOA_DB;        $_obfuscate_KBWh = getpar( $_POST, "cid", 0 );        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "tid" ), $this->table_transport, "WHERE `cid`='".$_obfuscate_KBWh."'" );        if ( !is_array( $_obfuscate_mPAjEGLn ) )        {            $_obfuscate_mPAjEGLn = array( );        }        $_obfuscate_PVLK5jra = array( 0 );        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )        {            $_obfuscate_PVLK5jra[] = $_obfuscate_6Aÿÿ['tid'];        }        $_obfuscate_SM9gqiNDyIIy0pcÿ = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_PVLK5jra );        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )        {            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['name'] = $_obfuscate_SM9gqiNDyIIy0pcÿ[$_obfuscate_6Aÿÿ['tid']]['truename'];        }        ( );        $_obfuscate_SUjPN94Er7yI = new dataStore( );        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_mPAjEGLn;        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );        exit( );    }    private function __formatCheckStatus( $_obfuscate_VgKtFegÿ )    {        switch ( $_obfuscate_VgKtFegÿ )        {        case 5 :            return lang( "undone" );        case 4 :            return lang( "superiorApp" );        case 3 :            return lang( "appCancel" );        case 2 :            return "<span class='cnoa_color_green'>".lang( "approvalThrough" )."</span>";        case 1 :            return "<span class='cnoa_color_red'>".lang( "approvalNotThrough" )."</span>";        }        return lang( "pendingTrial" );    }    private function _revoke( )    {        global $CNOA_DB;        global $CNOA_SESSION;        $_obfuscate_0W8ÿ = getpar( $_POST, "id" );        if ( !empty( $_obfuscate_0W8ÿ ) )        {            $_obfuscate_xs33Yt_k = $CNOA_DB->db_getone( "*", $this->table_apply, "WHERE `id`='".$_obfuscate_0W8ÿ."'" );            $_obfuscate_6RYLWQÿÿ['aid'] = $_obfuscate_0W8ÿ;            $_obfuscate_6RYLWQÿÿ['cid'] = $_obfuscate_xs33Yt_k['cid'];            $_obfuscate_6RYLWQÿÿ['uFlowId'] = $_obfuscate_xs33Yt_k['uFlowId'];            $_obfuscate_6RYLWQÿÿ['preuid'] = $_obfuscate_xs33Yt_k['checkid'];            $_obfuscate_6RYLWQÿÿ['checkid'] = $_obfuscate_xs33Yt_k['checkid'];            $_obfuscate_6RYLWQÿÿ['revoketime'] = $GLOBALS['CNOA_TIMESTAMP'];            $_obfuscate_6RYLWQÿÿ['revokenotes'] = getpar( $_POST, "revokenotes" );            $_obfuscate_6RYLWQÿÿ['status'] = 5;            $_obfuscate_FwcndFIjewÿÿ = $CNOA_DB->db_getone( array( "carnumber" ), $this->table_info, "WHERE `id`='".$_obfuscate_xs33Yt_k['cid']."'" );            $_obfuscate_6b8lIO4y = $CNOA_DB->db_update( array( "status" => "6", "endtime" => "0" ), $this->table_apply, "WHERE `id`='".$_obfuscate_0W8ÿ."'" );            $_obfuscate_605G4QxMqIoM = notice::add( $_obfuscate_xs33Yt_k['checkid'], lang( "vehicleAppMgr" ), lang( "hasBeApp" ).lang( "car" ).( "[".$_obfuscate_FwcndFIjewÿÿ['carnumber']."]" ).lang( "undone" ), "index.php?app=adm&func=car&action=check", "", 11, $_obfuscate_0W8ÿ );            $_obfuscate_1wGVgUrV1rs9 = notice::add( $_obfuscate_xs33Yt_k['tid'], lang( "vehicleAppMgr" ), lang( "hasBeApp" ).lang( "car" ).( "[".$_obfuscate_FwcndFIjewÿÿ['carnumber']."]" ).lang( "undone" ), "index.php?app=adm&func=car&action=check", "", 11, $_obfuscate_0W8ÿ );            $_obfuscate_2ldU2ddtBVttnwÿÿ = $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->table_check );            msg::callback( TRUE, lang( "successopt" ) );        }    }    private function __takeUserName( $_obfuscate_0W8ÿ )    {        $_obfuscate_6RYLWQÿÿ = app::loadapp( "main", "user" )->api_getUserDataByUid( $_obfuscate_0W8ÿ );        return $_obfuscate_6RYLWQÿÿ['truename'];    }    public function __getCarnumber( )    {        $this->_getCarnumber( );    }    public function api_add( $_obfuscate_9lVMg1Gb )    {        $this->_add( $_obfuscate_9lVMg1Gb );    }}?>