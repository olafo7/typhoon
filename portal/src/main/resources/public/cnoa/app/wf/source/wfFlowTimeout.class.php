<?php

class wfFlowTimeout extends wfCommon
{

    public function run( )
    {
        $_obfuscate_M_5JJwÿÿ = getpar( $_GET, "task" );
        switch ( $_obfuscate_M_5JJwÿÿ )
        {
        case "loadPage" :
            $this->_loadPage( );
            exit( );
        case "getFlows" :
            $this->_getFlows( );
            exit( );
        case "getFlowSort" :
            $this->_getFlowSort( );
            exit( );
        case "getTimeoutStep" :
            $this->_getTimeoutStep( );
            exit( );
        case "getStatistics" :
            $this->_getStatistics( );
            exit( );
        case "getRanking" :
            $this->_getRanking( );
        }
        exit( );
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $_obfuscate_BxoH_SjRHQÿÿ = $CNOA_CONTROLLER->appPath."/tpl/default/flow/timeout.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQÿÿ );
    }

    private function _getTimeoutStep( )
    {
        global $CNOA_DB;
        $_obfuscate_mV9HBLYÿ = getpar( $_POST, "start", 0 );
        $_obfuscate_xvYeh9Iÿ = getpagesize( "wf_flow_timeout_getTimeoutStep" );
        $_obfuscate_IRFhnYwÿ = array( );
        $_obfuscate_IRFhnYwÿ[] = "s.timelimit>0";
        $_obfuscate_IRFhnYwÿ[] = "s.timegap>s.timelimit";
        $_obfuscate_6b8lIO4y = intval( getpar( $_POST, "status" ) );
        if ( !empty( $_obfuscate_6b8lIO4y ) )
        {
            $_obfuscate_IRFhnYwÿ[] = "s.status=".$_obfuscate_6b8lIO4y;
        }
        $_obfuscate_tm07XSOCCeIy = intval( getpar( $_POST, "timelimit" ) );
        if ( !empty( $_obfuscate_tm07XSOCCeIy ) )
        {
            $_obfuscate_tm07XSOCCeIy = $_obfuscate_tm07XSOCCeIy * 60 * 60;
            $_obfuscate_IRFhnYwÿ[] = "s.timelimit>".$_obfuscate_tm07XSOCCeIy;
        }
        $_obfuscate_5E5Av0svlQÿÿ = intval( getpar( $_POST, "timeout" ) );
        if ( !empty( $_obfuscate_5E5Av0svlQÿÿ ) )
        {
            $_obfuscate_5E5Av0svlQÿÿ = $_obfuscate_5E5Av0svlQÿÿ * 60 * 60;
            $_obfuscate_IRFhnYwÿ[] = "s.timegap-s.timelimit>".$_obfuscate_5E5Av0svlQÿÿ;
        }
        $_obfuscate_IRFhnYwÿ = implode( " AND ", $_obfuscate_IRFhnYwÿ );
        $_obfuscate_3y0Y = "UPDATE ".tname( $this->t_use_step ).( " SET `timegap`=".$GLOBALS['CNOA_TIMESTAMP']."-`stime` WHERE `etime`=0" );
        $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_3y0Y = "SELECT s.stepName, s.uid, s.proxyUid, s.dealUid, s.status, s.timegap, s.timelimit, f.flowNumber, f.flowName FROM ".tname( $this->t_use_step )." AS s LEFT JOIN ".tname( $this->t_use_flow )." AS f ON f.uFlowId=s.uFlowId ".( "WHERE ".$_obfuscate_IRFhnYwÿ." ORDER BY `timegap` DESC LIMIT {$_obfuscate_mV9HBLYÿ}, {$_obfuscate_xvYeh9Iÿ}" );
        $_obfuscate_ammigv8ÿ = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate__eqrEQÿÿ = array( );
        while ( $_obfuscate_rVsNRAÿÿ = $CNOA_DB->get_array( $_obfuscate_ammigv8ÿ ) )
        {
            if ( $_obfuscate_rVsNRAÿÿ['dealUid'] )
            {
                $_obfuscate_rVsNRAÿÿ['user'] = $_obfuscate_rVsNRAÿÿ['dealUid'];
            }
            else if ( $_obfuscate_rVsNRAÿÿ['proxyUid'] )
            {
                $_obfuscate_rVsNRAÿÿ['user'] = $_obfuscate_rVsNRAÿÿ['proxyUid'];
            }
            else
            {
                $_obfuscate_rVsNRAÿÿ['user'] = $_obfuscate_rVsNRAÿÿ['uid'];
            }
            $_obfuscate__eqrEQÿÿ[] = $_obfuscate_rVsNRAÿÿ['user'];
            $_obfuscate_rVsNRAÿÿ['timeout'] = $_obfuscate_rVsNRAÿÿ['timegap'] - $_obfuscate_rVsNRAÿÿ['timelimit'];
            unset( $_obfuscate_6Aÿÿ['dealUid'] );
            unset( $_obfuscate_6Aÿÿ['proxyUid'] );
            unset( $_obfuscate_6Aÿÿ['uid'] );
            unset( $_obfuscate_6Aÿÿ['timegap'] );
            $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_rVsNRAÿÿ;
        }
        $_obfuscate__Wi6396IheAÿ = array( );
        if ( !empty( $_obfuscate__eqrEQÿÿ ) )
        {
            $_obfuscate__eqrEQÿÿ = array_unique( $_obfuscate__eqrEQÿÿ );
            $_obfuscate__Wi6396IheAÿ = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate__eqrEQÿÿ );
        }
        foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6Aÿÿ['timeout'] = timeformat2( $_obfuscate_6Aÿÿ['timeout'] );
            $_obfuscate_6Aÿÿ['timelimit'] = timeformat2( $_obfuscate_6Aÿÿ['timelimit'] );
            $_obfuscate_6Aÿÿ['status'] = $this->f_stepType[$_obfuscate_6Aÿÿ['status']];
            $_obfuscate_6Aÿÿ['user'] = $_obfuscate__Wi6396IheAÿ[$_obfuscate_6Aÿÿ['user']];
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ] = $_obfuscate_6Aÿÿ;
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        $_obfuscate_NlQÿ->total = $CNOA_DB->db_getcount( $this->t_use_step, "WHERE ".str_replace( "s.", "", $_obfuscate_IRFhnYwÿ ) );
        echo $_obfuscate_NlQÿ->makeJsonData( );
    }

    private function _getFlowSort( )
    {
        global $CNOA_DB;
        $_obfuscate_ubP__Qÿÿ = $CNOA_DB->db_select( array( "sortId", "name" ), "wf_s_sort", "ORDER BY `order`" );
        if ( !is_array( $_obfuscate_ubP__Qÿÿ ) )
        {
            $_obfuscate_ubP__Qÿÿ = array( );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_ubP__Qÿÿ;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
    }

    private function _getFlows( )
    {
        global $CNOA_DB;
        $_obfuscate_y6jH = intval( getpar( $_POST, "sid" ) );
        if ( !empty( $_obfuscate_y6jH ) )
        {
            $_obfuscate_a96y4zwÿ = $CNOA_DB->db_select( array( "flowId", "name" ), "wf_s_flow", "WHERE `sortId`=".$_obfuscate_y6jH." AND `status`=1" );
        }
        if ( !is_array( $_obfuscate_a96y4zwÿ ) )
        {
            $_obfuscate_a96y4zwÿ = array( );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_a96y4zwÿ;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
    }

    private function _getStatistics( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = intval( getpar( $_POST, "flowId" ) );
        $_obfuscate_6RYLWQÿÿ = array( );
        if ( empty( $_obfuscate_F4AbnVRh ) )
        {
            ( );
            $_obfuscate_NlQÿ = new dataStore( );
            $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
            echo $_obfuscate_NlQÿ->makeJsonData( );
            exit( );
        }
        $_obfuscate_3y0Y = "UPDATE ".tname( $this->t_use_step ).( " SET `timegap`=".$GLOBALS['CNOA_TIMESTAMP']."-`stime` WHERE `etime`=0" );
        $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "uFlowId" ), $this->t_use_flow, "WHERE `flowId`=".$_obfuscate_F4AbnVRh );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_dDHiUSY4Qoÿ = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_dDHiUSY4Qoÿ[] = $_obfuscate_6Aÿÿ['uFlowId'];
        }
        if ( !empty( $_obfuscate_dDHiUSY4Qoÿ ) )
        {
            $_obfuscate_dDHiUSY4Qoÿ = implode( ",", $_obfuscate_dDHiUSY4Qoÿ );
            $_obfuscate_rHKTnTkt = array( );
            $_obfuscate__eqrEQÿÿ = array( );
            $_obfuscate_IRFhnYwÿ = array( );
            $_obfuscate_IRFhnYwÿ[] = "uFlowId IN (".$_obfuscate_dDHiUSY4Qoÿ.")";
            $_obfuscate_qx37NMÿ = getpar( $_POST, "stime" );
            if ( !empty( $_obfuscate_qx37NMÿ ) )
            {
                $_obfuscate_qx37NMÿ = strtotime( "{$_obfuscate_qx37NMÿ} 00:00:00" );
                $_obfuscate_IRFhnYwÿ[] = "stime>=".$_obfuscate_qx37NMÿ;
            }
            $_obfuscate_KWKBW4ÿ = getpar( $_POST, "etime" );
            if ( !empty( $_obfuscate_KWKBW4ÿ ) )
            {
                $_obfuscate_KWKBW4ÿ = strtotime( "{$_obfuscate_KWKBW4ÿ} 23:59:59" );
                $_obfuscate_IRFhnYwÿ[] = "stime<=".$_obfuscate_KWKBW4ÿ;
            }
            $_obfuscate_IRFhnYwÿ[] = "status>0 AND timelimit>0";
            if ( !empty( $_obfuscate_IRFhnYwÿ ) )
            {
                $_obfuscate_IRFhnYwÿ = implode( " AND ", $_obfuscate_IRFhnYwÿ );
            }
            $_obfuscate_3y0Y = "SELECT s.uid, s.proxyUid, s.dealUid, s.status, (SELECT COUNT(*) FROM ".tname( $this->t_use_step ).( " WHERE ".$_obfuscate_IRFhnYwÿ.") AS stepTotal " )."FROM ".tname( $this->t_use_step )." AS s ".( "WHERE ".$_obfuscate_IRFhnYwÿ." AND timegap>timelimit" );
            $_obfuscate_ammigv8ÿ = $CNOA_DB->query( $_obfuscate_3y0Y );
            while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_ammigv8ÿ ) )
            {
                if ( $_obfuscate_gkt['dealUid'] )
                {
                    $_obfuscate_7Ri3 = $_obfuscate_gkt['dealUid'];
                }
                else if ( $_obfuscate_rVsNRAÿÿ['proxyUid'] )
                {
                    $_obfuscate_7Ri3 = $_obfuscate_gkt['proxyUid'];
                }
                else
                {
                    $_obfuscate_7Ri3 = $_obfuscate_gkt['uid'];
                }
                $_obfuscate__eqrEQÿÿ[] = $_obfuscate_7Ri3;
                if ( !isset( $_obfuscate_rHKTnTkt[$_obfuscate_7Ri3] ) )
                {
                    $_obfuscate_rHKTnTkt[$_obfuscate_7Ri3] = array(
                        "todo" => 0,
                        "done" => 0,
                        "user" => $_obfuscate_7Ri3,
                        "stepTotal" => $_obfuscate_gkt['stepTotal']
                    );
                }
                if ( $_obfuscate_gkt['status'] == 1 )
                {
                    ++$_obfuscate_rHKTnTkt[$_obfuscate_7Ri3]['todo'];
                }
                else
                {
                    ++$_obfuscate_rHKTnTkt[$_obfuscate_7Ri3]['done'];
                }
            }
            $_obfuscate__Wi6396IheAÿ = array( );
            if ( !empty( $_obfuscate__eqrEQÿÿ ) )
            {
                $_obfuscate__eqrEQÿÿ = array_unique( $_obfuscate__eqrEQÿÿ );
                $_obfuscate__Wi6396IheAÿ = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate__eqrEQÿÿ );
            }
            foreach ( $_obfuscate_rHKTnTkt as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_6Aÿÿ['timeoutTotal'] = $_obfuscate_6Aÿÿ['todo'] + $_obfuscate_6Aÿÿ['done'];
                $_obfuscate_6Aÿÿ['user'] = $_obfuscate__Wi6396IheAÿ[$_obfuscate_6Aÿÿ['user']];
                $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_6Aÿÿ;
            }
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
    }

    private function _getRanking( )
    {
        global $CNOA_DB;
        $_obfuscate_qx37NMÿ = getpar( $_POST, "stime", date( "Y-m-01", time( ) ) );
        $_obfuscate_qx37NMÿ = strtotime( "{$_obfuscate_qx37NMÿ} 00:00:00" );
        $_obfuscate_KWKBW4ÿ = getpar( $_POST, "etime", date( "Y-m-t", time( ) ) );
        $_obfuscate_KWKBW4ÿ = strtotime( "{$_obfuscate_KWKBW4ÿ} 23:59:59" );
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_3y0Y = "SELECT u.truename AS user, sum(s.etime-s.stime) AS timegap, count(s.id) AS count, t.name AS dept, j.name AS job FROM ".tname( $this->t_use_step )." AS s LEFT JOIN ".tname( "main_user" )." AS u ON u.uid=s.dealUid LEFT JOIN ".tname( "main_struct" )." AS t ON t.id=u.deptId LEFT JOIN ".tname( "main_job" )." AS j ON j.id=u.jobId ".( "WHERE `stime` > ".$_obfuscate_qx37NMÿ." AND `etime` < {$_obfuscate_KWKBW4ÿ} AND `status` = 2 AND `stepType` != 1 AND `stepType` != 3 " )."GROUP BY s.dealUid";
        $_obfuscate_ammigv8ÿ = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_ubP__Qÿÿ = array( );
        while ( $_obfuscate_rVsNRAÿÿ = $CNOA_DB->get_array( $_obfuscate_ammigv8ÿ ) )
        {
            if ( !$_obfuscate_rVsNRAÿÿ['user'] )
            {
            }
            else
            {
                $_obfuscate_GcJqhJb8Fwÿÿ = round( $_obfuscate_rVsNRAÿÿ['timegap'] / $_obfuscate_rVsNRAÿÿ['count'] );
                $_obfuscate_ubP__Qÿÿ[] = $_obfuscate_GcJqhJb8Fwÿÿ;
                $_obfuscate_rVsNRAÿÿ['count'] = ( integer )$_obfuscate_rVsNRAÿÿ['count'];
                $_obfuscate_rVsNRAÿÿ['time'] = timetodhms( $_obfuscate_GcJqhJb8Fwÿÿ );
                $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_rVsNRAÿÿ;
            }
        }
        array_multisort( $_obfuscate_ubP__Qÿÿ, $_obfuscate_6RYLWQÿÿ );
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        echo $_obfuscate_NlQÿ->makeJsonData( );
    }

}

?>
