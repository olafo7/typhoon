<?php

class wfFlowTimeout extends wfCommon
{

    public function run( )
    {
        $_obfuscate_M_5JJw�� = getpar( $_GET, "task" );
        switch ( $_obfuscate_M_5JJw�� )
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
        $_obfuscate_BxoH_SjRHQ�� = $CNOA_CONTROLLER->appPath."/tpl/default/flow/timeout.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQ�� );
    }

    private function _getTimeoutStep( )
    {
        global $CNOA_DB;
        $_obfuscate_mV9HBLY� = getpar( $_POST, "start", 0 );
        $_obfuscate_xvYeh9I� = getpagesize( "wf_flow_timeout_getTimeoutStep" );
        $_obfuscate_IRFhnYw� = array( );
        $_obfuscate_IRFhnYw�[] = "s.timelimit>0";
        $_obfuscate_IRFhnYw�[] = "s.timegap>s.timelimit";
        $_obfuscate_6b8lIO4y = intval( getpar( $_POST, "status" ) );
        if ( !empty( $_obfuscate_6b8lIO4y ) )
        {
            $_obfuscate_IRFhnYw�[] = "s.status=".$_obfuscate_6b8lIO4y;
        }
        $_obfuscate_tm07XSOCCeIy = intval( getpar( $_POST, "timelimit" ) );
        if ( !empty( $_obfuscate_tm07XSOCCeIy ) )
        {
            $_obfuscate_tm07XSOCCeIy = $_obfuscate_tm07XSOCCeIy * 60 * 60;
            $_obfuscate_IRFhnYw�[] = "s.timelimit>".$_obfuscate_tm07XSOCCeIy;
        }
        $_obfuscate_5E5Av0svlQ�� = intval( getpar( $_POST, "timeout" ) );
        if ( !empty( $_obfuscate_5E5Av0svlQ�� ) )
        {
            $_obfuscate_5E5Av0svlQ�� = $_obfuscate_5E5Av0svlQ�� * 60 * 60;
            $_obfuscate_IRFhnYw�[] = "s.timegap-s.timelimit>".$_obfuscate_5E5Av0svlQ��;
        }
        $_obfuscate_IRFhnYw� = implode( " AND ", $_obfuscate_IRFhnYw� );
        $_obfuscate_3y0Y = "UPDATE ".tname( $this->t_use_step ).( " SET `timegap`=".$GLOBALS['CNOA_TIMESTAMP']."-`stime` WHERE `etime`=0" );
        $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_3y0Y = "SELECT s.stepName, s.uid, s.proxyUid, s.dealUid, s.status, s.timegap, s.timelimit, f.flowNumber, f.flowName FROM ".tname( $this->t_use_step )." AS s LEFT JOIN ".tname( $this->t_use_flow )." AS f ON f.uFlowId=s.uFlowId ".( "WHERE ".$_obfuscate_IRFhnYw�." ORDER BY `timegap` DESC LIMIT {$_obfuscate_mV9HBLY�}, {$_obfuscate_xvYeh9I�}" );
        $_obfuscate_ammigv8� = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate__eqrEQ�� = array( );
        while ( $_obfuscate_rVsNRA�� = $CNOA_DB->get_array( $_obfuscate_ammigv8� ) )
        {
            if ( $_obfuscate_rVsNRA��['dealUid'] )
            {
                $_obfuscate_rVsNRA��['user'] = $_obfuscate_rVsNRA��['dealUid'];
            }
            else if ( $_obfuscate_rVsNRA��['proxyUid'] )
            {
                $_obfuscate_rVsNRA��['user'] = $_obfuscate_rVsNRA��['proxyUid'];
            }
            else
            {
                $_obfuscate_rVsNRA��['user'] = $_obfuscate_rVsNRA��['uid'];
            }
            $_obfuscate__eqrEQ��[] = $_obfuscate_rVsNRA��['user'];
            $_obfuscate_rVsNRA��['timeout'] = $_obfuscate_rVsNRA��['timegap'] - $_obfuscate_rVsNRA��['timelimit'];
            unset( $_obfuscate_6A��['dealUid'] );
            unset( $_obfuscate_6A��['proxyUid'] );
            unset( $_obfuscate_6A��['uid'] );
            unset( $_obfuscate_6A��['timegap'] );
            $_obfuscate_6RYLWQ��[] = $_obfuscate_rVsNRA��;
        }
        $_obfuscate__Wi6396IheA� = array( );
        if ( !empty( $_obfuscate__eqrEQ�� ) )
        {
            $_obfuscate__eqrEQ�� = array_unique( $_obfuscate__eqrEQ�� );
            $_obfuscate__Wi6396IheA� = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate__eqrEQ�� );
        }
        foreach ( $_obfuscate_6RYLWQ�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_6A��['timeout'] = timeformat2( $_obfuscate_6A��['timeout'] );
            $_obfuscate_6A��['timelimit'] = timeformat2( $_obfuscate_6A��['timelimit'] );
            $_obfuscate_6A��['status'] = $this->f_stepType[$_obfuscate_6A��['status']];
            $_obfuscate_6A��['user'] = $_obfuscate__Wi6396IheA�[$_obfuscate_6A��['user']];
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��] = $_obfuscate_6A��;
        }
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_6RYLWQ��;
        $_obfuscate_NlQ�->total = $CNOA_DB->db_getcount( $this->t_use_step, "WHERE ".str_replace( "s.", "", $_obfuscate_IRFhnYw� ) );
        echo $_obfuscate_NlQ�->makeJsonData( );
    }

    private function _getFlowSort( )
    {
        global $CNOA_DB;
        $_obfuscate_ubP__Q�� = $CNOA_DB->db_select( array( "sortId", "name" ), "wf_s_sort", "ORDER BY `order`" );
        if ( !is_array( $_obfuscate_ubP__Q�� ) )
        {
            $_obfuscate_ubP__Q�� = array( );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_ubP__Q��;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
    }

    private function _getFlows( )
    {
        global $CNOA_DB;
        $_obfuscate_y6jH = intval( getpar( $_POST, "sid" ) );
        if ( !empty( $_obfuscate_y6jH ) )
        {
            $_obfuscate_a96y4zw� = $CNOA_DB->db_select( array( "flowId", "name" ), "wf_s_flow", "WHERE `sortId`=".$_obfuscate_y6jH." AND `status`=1" );
        }
        if ( !is_array( $_obfuscate_a96y4zw� ) )
        {
            $_obfuscate_a96y4zw� = array( );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_a96y4zw�;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
    }

    private function _getStatistics( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = intval( getpar( $_POST, "flowId" ) );
        $_obfuscate_6RYLWQ�� = array( );
        if ( empty( $_obfuscate_F4AbnVRh ) )
        {
            ( );
            $_obfuscate_NlQ� = new dataStore( );
            $_obfuscate_NlQ�->data = $_obfuscate_6RYLWQ��;
            echo $_obfuscate_NlQ�->makeJsonData( );
            exit( );
        }
        $_obfuscate_3y0Y = "UPDATE ".tname( $this->t_use_step ).( " SET `timegap`=".$GLOBALS['CNOA_TIMESTAMP']."-`stime` WHERE `etime`=0" );
        $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "uFlowId" ), $this->t_use_flow, "WHERE `flowId`=".$_obfuscate_F4AbnVRh );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_dDHiUSY4Qo� = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A�� )
        {
            $_obfuscate_dDHiUSY4Qo�[] = $_obfuscate_6A��['uFlowId'];
        }
        if ( !empty( $_obfuscate_dDHiUSY4Qo� ) )
        {
            $_obfuscate_dDHiUSY4Qo� = implode( ",", $_obfuscate_dDHiUSY4Qo� );
            $_obfuscate_rHKTnTkt = array( );
            $_obfuscate__eqrEQ�� = array( );
            $_obfuscate_IRFhnYw� = array( );
            $_obfuscate_IRFhnYw�[] = "uFlowId IN (".$_obfuscate_dDHiUSY4Qo�.")";
            $_obfuscate_qx37NM� = getpar( $_POST, "stime" );
            if ( !empty( $_obfuscate_qx37NM� ) )
            {
                $_obfuscate_qx37NM� = strtotime( "{$_obfuscate_qx37NM�} 00:00:00" );
                $_obfuscate_IRFhnYw�[] = "stime>=".$_obfuscate_qx37NM�;
            }
            $_obfuscate_KWKBW4� = getpar( $_POST, "etime" );
            if ( !empty( $_obfuscate_KWKBW4� ) )
            {
                $_obfuscate_KWKBW4� = strtotime( "{$_obfuscate_KWKBW4�} 23:59:59" );
                $_obfuscate_IRFhnYw�[] = "stime<=".$_obfuscate_KWKBW4�;
            }
            $_obfuscate_IRFhnYw�[] = "status>0 AND timelimit>0";
            if ( !empty( $_obfuscate_IRFhnYw� ) )
            {
                $_obfuscate_IRFhnYw� = implode( " AND ", $_obfuscate_IRFhnYw� );
            }
            $_obfuscate_3y0Y = "SELECT s.uid, s.proxyUid, s.dealUid, s.status, (SELECT COUNT(*) FROM ".tname( $this->t_use_step ).( " WHERE ".$_obfuscate_IRFhnYw�.") AS stepTotal " )."FROM ".tname( $this->t_use_step )." AS s ".( "WHERE ".$_obfuscate_IRFhnYw�." AND timegap>timelimit" );
            $_obfuscate_ammigv8� = $CNOA_DB->query( $_obfuscate_3y0Y );
            while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_ammigv8� ) )
            {
                if ( $_obfuscate_gkt['dealUid'] )
                {
                    $_obfuscate_7Ri3 = $_obfuscate_gkt['dealUid'];
                }
                else if ( $_obfuscate_rVsNRA��['proxyUid'] )
                {
                    $_obfuscate_7Ri3 = $_obfuscate_gkt['proxyUid'];
                }
                else
                {
                    $_obfuscate_7Ri3 = $_obfuscate_gkt['uid'];
                }
                $_obfuscate__eqrEQ��[] = $_obfuscate_7Ri3;
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
            $_obfuscate__Wi6396IheA� = array( );
            if ( !empty( $_obfuscate__eqrEQ�� ) )
            {
                $_obfuscate__eqrEQ�� = array_unique( $_obfuscate__eqrEQ�� );
                $_obfuscate__Wi6396IheA� = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate__eqrEQ�� );
            }
            foreach ( $_obfuscate_rHKTnTkt as $_obfuscate_6A�� )
            {
                $_obfuscate_6A��['timeoutTotal'] = $_obfuscate_6A��['todo'] + $_obfuscate_6A��['done'];
                $_obfuscate_6A��['user'] = $_obfuscate__Wi6396IheA�[$_obfuscate_6A��['user']];
                $_obfuscate_6RYLWQ��[] = $_obfuscate_6A��;
            }
        }
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_6RYLWQ��;
        echo $_obfuscate_NlQ�->makeJsonData( );
    }

    private function _getRanking( )
    {
        global $CNOA_DB;
        $_obfuscate_qx37NM� = getpar( $_POST, "stime", date( "Y-m-01", time( ) ) );
        $_obfuscate_qx37NM� = strtotime( "{$_obfuscate_qx37NM�} 00:00:00" );
        $_obfuscate_KWKBW4� = getpar( $_POST, "etime", date( "Y-m-t", time( ) ) );
        $_obfuscate_KWKBW4� = strtotime( "{$_obfuscate_KWKBW4�} 23:59:59" );
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_3y0Y = "SELECT u.truename AS user, sum(s.etime-s.stime) AS timegap, count(s.id) AS count, t.name AS dept, j.name AS job FROM ".tname( $this->t_use_step )." AS s LEFT JOIN ".tname( "main_user" )." AS u ON u.uid=s.dealUid LEFT JOIN ".tname( "main_struct" )." AS t ON t.id=u.deptId LEFT JOIN ".tname( "main_job" )." AS j ON j.id=u.jobId ".( "WHERE `stime` > ".$_obfuscate_qx37NM�." AND `etime` < {$_obfuscate_KWKBW4�} AND `status` = 2 AND `stepType` != 1 AND `stepType` != 3 " )."GROUP BY s.dealUid";
        $_obfuscate_ammigv8� = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_ubP__Q�� = array( );
        while ( $_obfuscate_rVsNRA�� = $CNOA_DB->get_array( $_obfuscate_ammigv8� ) )
        {
            if ( !$_obfuscate_rVsNRA��['user'] )
            {
            }
            else
            {
                $_obfuscate_GcJqhJb8Fw�� = round( $_obfuscate_rVsNRA��['timegap'] / $_obfuscate_rVsNRA��['count'] );
                $_obfuscate_ubP__Q��[] = $_obfuscate_GcJqhJb8Fw��;
                $_obfuscate_rVsNRA��['count'] = ( integer )$_obfuscate_rVsNRA��['count'];
                $_obfuscate_rVsNRA��['time'] = timetodhms( $_obfuscate_GcJqhJb8Fw�� );
                $_obfuscate_6RYLWQ��[] = $_obfuscate_rVsNRA��;
            }
        }
        array_multisort( $_obfuscate_ubP__Q��, $_obfuscate_6RYLWQ�� );
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_6RYLWQ��;
        echo $_obfuscate_NlQ�->makeJsonData( );
    }

}

?>
