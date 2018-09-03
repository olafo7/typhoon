<?php

class wfFlowUseDoneM extends wfCommon
{

    public function run( )
    {
        $_obfuscate_M_5JJwÿÿ = getpar( $_GET, "task" );
        switch ( $_obfuscate_M_5JJwÿÿ )
        {
        case "getWfList" :
            $this->_getWfList( );
            break;
        case "getAllFlowlist" :
            return $this->_getAllFlowlist( );
        case "getMyFlowlist" :
            return $this->_getMyFlowlist( );
        case "getJsonData" :
            return $this->_getJsonData( );
        case "callback" :
            $this->_callback( );
            break;
        case "cancelflow" :
            $this->_cancelflow( );
            break;
        case "loadFenfaFormData" :
            $this->_loadFenfaFormData( );
            break;
        case "fenFaFlow" :
            $this->_fenFaFlow( );
        }
    }

    private function _getWfList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_mV9HBLYÿ = ( integer )getpar( $_POST, "start" );
        $_obfuscate_xvYeh9Iÿ = ( integer )getpar( $_POST, "limit", 15 );
        $_obfuscate_dcwitxb = getpar( $_POST, "search" );
        $_obfuscate_IRFhnYwÿ[] = "f.status IN (1, 2, 4, 6)";
        if ( !empty( $_obfuscate_dcwitxb ) )
        {
            $_obfuscate_IRFhnYwÿ[] = "(f.flowName LIKE '%".$_obfuscate_dcwitxb."%' OR f.flowNumber LIKE '%{$_obfuscate_dcwitxb}%')";
        }
        $_obfuscate_UgqmbAÿÿ = "SELECT {fields} FROM (SELECT * FROM ".tname( $this->t_use_step ).( " WHERE (`status`=2 OR `status`=4 OR `status`=5) AND (`uid`='".$_obfuscate_7Ri3."' OR `proxyUid`='{$_obfuscate_7Ri3}')) AS u" )." LEFT JOIN ".tname( $this->t_use_flow )." AS f ON u.uFlowId = f.uFlowId LEFT JOIN ".tname( $this->t_set_flow )." AS sf ON sf.flowId = f.flowId LEFT JOIN ".tname( $this->main_user )." AS user ON f.uid = user.uid WHERE ".implode( " AND ", $_obfuscate_IRFhnYwÿ );
        $_obfuscate_tjILu7ZH = "f.flowId, f.uFlowId, f.flowName, f.flowNumber, f.status, f.uid, f.level, f.posttime, f.allowCallback, f.allowCancel, sf.flowType, sf.tplSort, u.uStepId, user.face, user.truename";
        $_obfuscate_3y0Y = "SELECT * FROM (".strtr( $_obfuscate_UgqmbAÿÿ, array(
            "{fields}" => $_obfuscate_tjILu7ZH
        ) )." GROUP BY f.uFlowId DESC ORDER BY f.level DESC, f.posttime DESC".( " LIMIT ".$_obfuscate_mV9HBLYÿ.", {$_obfuscate_xvYeh9Iÿ} ) AS m ORDER BY status ASC, level DESC, posttime DESC" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_orbsHf4ÿ = array( );
        $_obfuscate_ouqX2cxvhAÿÿ = $CNOA_DB->db_select( array( "uFlowId" ), $this->t_use_step_huiqian, " WHERE `touid`=".$_obfuscate_7Ri3." AND `status`=1" );
        foreach ( $_obfuscate_ouqX2cxvhAÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_EKP_rQÿÿ = "select f.flowId, f.uFlowId, f.flowName, f.flowNumber, f.status, f.uid, f.level, f.posttime, f.allowCallback, f.allowCancel, sf.flowType, sf.tplSort, u.uStepId, user.face, user.truename from (select * from ".tname( $this->t_use_step )."WHERE (`status`=2 OR `status`=4 OR `status`=5))as u left join".tname( $this->t_use_flow )."AS f ON u.uFlowId = f.uFlowId LEFT JOIN ".tname( $this->t_set_flow )." AS sf ON sf.flowId = f.flowId LEFT JOIN ".tname( $this->main_user )." AS user ON f.uid = user.uid WHERE ".implode( " AND ", $_obfuscate_IRFhnYwÿ )." and f.uFlowId=".$_obfuscate_6Aÿÿ['uFlowId'];
            $_obfuscate_amHmH9FVAÿÿ = $CNOA_DB->query( $_obfuscate_EKP_rQÿÿ );
            while ( $_obfuscate_4h6otZd1 = $CNOA_DB->get_array( $_obfuscate_amHmH9FVAÿÿ ) )
            {
                $_obfuscate_orbsHf4ÿ[] = $_obfuscate_4h6otZd1;
            }
        }
        $_obfuscate_6RYLWQÿÿ = array( );
        while ( $_obfuscate__kbonB1Z = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_orbsHf4ÿ[] = $_obfuscate__kbonB1Z;
        }
        foreach ( $_obfuscate_orbsHf4ÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['flowId'] = $_obfuscate_6Aÿÿ['flowId'];
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['flowNumber'] = $_obfuscate_6Aÿÿ['flowNumber'];
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['uFlowId'] = $_obfuscate_6Aÿÿ['uFlowId'];
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['flowName'] = $_obfuscate_6Aÿÿ['flowName'];
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['flowType'] = $_obfuscate_6Aÿÿ['flowType'];
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['tplSort'] = $_obfuscate_6Aÿÿ['tplSort'];
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['uStepId'] = $_obfuscate_6Aÿÿ['uStepId'];
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['truename'] = $_obfuscate_6Aÿÿ['truename'];
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['uid'] = $_obfuscate_6Aÿÿ['uid'];
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['posttime'] = formatdate( $_obfuscate_6Aÿÿ['posttime'], "Y-m-d H:i" );
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['level'] = $this->_getLevelText( $_obfuscate_6Aÿÿ['level'] );
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['face'] = $this->_getFacePath( $_obfuscate_6Aÿÿ['face'], $_obfuscate_6Aÿÿ['uid'] );
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['allowFenFa'] = 0;
            if ( $_obfuscate_6Aÿÿ['uid'] == $_obfuscate_7Ri3 && $_obfuscate_6Aÿÿ['status'] == 1 )
            {
                $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['allowCallback'] = $_obfuscate_6Aÿÿ['allowCallback'];
                $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['allowCancel'] = $_obfuscate_6Aÿÿ['allowCancel'];
            }
            else
            {
                $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['allowCallback'] = 0;
                $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['allowCancel'] = 0;
            }
            if ( $_obfuscate_6Aÿÿ['status'] == 2 )
            {
                $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['allowFenFa'] = 1;
            }
            if ( $_obfuscate_6Aÿÿ['status'] == 2 )
            {
                $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['uStepId'] = 3;
            }
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['status'] = $this->_getStatusText( $_obfuscate_6Aÿÿ['status'] );
        }
        $_obfuscate_3y0Y = strtr( $_obfuscate_UgqmbAÿÿ, array( "{fields}" => "count(DISTINCT f.uFlowId) AS total" ) );
        $_obfuscate_j9sJesÿ = $CNOA_DB->get_one( $_obfuscate_3y0Y );
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_6RYLWQÿÿ;
        $_obfuscate_SUjPN94Er7yI->total = $_obfuscate_j9sJesÿ['total'];
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _getAllFlowlist( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_mV9HBLYÿ = ( integer )getpar( $_GET, "start", getpar( $_POST, "start" ) );
        $_obfuscate_xvYeh9Iÿ = ( integer )getpar( $_GET, "limit", getpar( $_POST, "limit", 15 ) );
        $_obfuscate_dcwitxb = getpar( $_GET, "search", getpar( $_POST, "search", "" ) );
        $_obfuscate_Bk2lGlkÿ[] = "s.status IN(1, 2, 4, 5) AND (s.uid = ".$_obfuscate_7Ri3." OR s.proxyUid = {$_obfuscate_7Ri3} OR (h.touid = {$_obfuscate_7Ri3} AND h.status = 0 AND h.issubmit = 1) OR (ff.touid = {$_obfuscate_7Ri3} AND ff.status = 0 AND ff.isread = 0)) AND sf.tplSort = 0";
        if ( !empty( $_obfuscate_dcwitxb ) )
        {
            $_obfuscate_Bk2lGlkÿ[] = "(f.flowNumber LIKE '%".$_obfuscate_dcwitxb."%' OR f.flowName LIKE '%{$_obfuscate_dcwitxb}%')";
        }
        $_obfuscate_tjILu7ZH = "SELECT `f`.`uFlowId`, `f`.`flowId`, `f`.`flowName`, `f`.`flowNumber`, `f`.`uid`, `f`.`posttime`, `f`.`level`, `f`.`allowCallback`, `f`.`allowCancel`, `f`.`status` AS fstatus, `f`.`endtime`, `s`.`uStepId`, `s`.`status`, `sf`.`tplSort`, `sf`.`flowType`, `s`.`uid` AS sUid, `user`.`face`, `user`.`truename`, `h`.`touid` AS hqUid, `h`.`status` AS hqStatus, `ff`.`touid` AS ffUid, `ff`.`status` AS ffStatus ";
        $_obfuscate_aM13xdJ441kqAÿÿ = " ORDER BY s.status ASC";
        $_obfuscate_0nWRhrF6sLGirzfm = "GROUP BY uFlowId DESC ORDER BY level DESC, posttime DESC LIMIT ".$_obfuscate_mV9HBLYÿ.", {$_obfuscate_xvYeh9Iÿ}";
        $_obfuscate_0yyGECzPHaDJUBj = "FROM ".tname( $this->t_use_flow )." AS f LEFT JOIN ".tname( $this->t_set_flow )." AS sf ON sf.flowId = f.flowId LEFT JOIN ".tname( $this->t_use_step )." AS s ON f.uFlowId = s.uFlowId LEFT JOIN ".tname( $this->t_use_step_huiqian )." AS h ON f.uFlowId = h.uFlowId AND s.uStepId = h.stepId LEFT JOIN ".tname( $this->t_use_fenfa )." AS ff ON f.uFlowId = ff.uFlowId AND s.uStepId = ff.stepId LEFT JOIN ".tname( $this->main_user )." AS user ON f.uid = user.uid ";
        $_obfuscate_3y0Y = "SELECT * FROM (SELECT * FROM (".$_obfuscate_tjILu7ZH.$_obfuscate_0yyGECzPHaDJUBj."WHERE ".implode( " AND ", $_obfuscate_Bk2lGlkÿ ).$_obfuscate_aM13xdJ441kqAÿÿ.") AS m ".$_obfuscate_0nWRhrF6sLGirzfm.") AS n ORDER BY fstatus, level DESC, posttime DESC";
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_8Bnz38wN01cÿ = array( );
        while ( $_obfuscate_VgKhVhHUmwÿÿ = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            if ( $_obfuscate_VgKhVhHUmwÿÿ['status'] == 2 || $_obfuscate_VgKhVhHUmwÿÿ['status'] == 4 )
            {
                $_obfuscate_VgKhVhHUmwÿÿ['category'] = 2;
                if ( $_obfuscate_VgKhVhHUmwÿÿ['uid'] == $_obfuscate_7Ri3 && $_obfuscate_VgKhVhHUmwÿÿ['fstatus'] == 1 )
                {
                    $_obfuscate_VgKhVhHUmwÿÿ['allowCallback'] = $_obfuscate_VgKhVhHUmwÿÿ['allowCallback'];
                    $_obfuscate_VgKhVhHUmwÿÿ['allowCancel'] = $_obfuscate_VgKhVhHUmwÿÿ['allowCancel'];
                    $_obfuscate_VgKhVhHUmwÿÿ['allowFenFa'] = 0;
                }
                else if ( $_obfuscate_VgKhVhHUmwÿÿ['uid'] == $_obfuscate_7Ri3 && $_obfuscate_VgKhVhHUmwÿÿ['fstatus'] == 2 )
                {
                    $_obfuscate_VgKhVhHUmwÿÿ['allowCallback'] = 0;
                    $_obfuscate_VgKhVhHUmwÿÿ['allowCancel'] = 0;
                    $_obfuscate_VgKhVhHUmwÿÿ['allowFenFa'] = 1;
                }
                else
                {
                    $_obfuscate_VgKhVhHUmwÿÿ['allowCallback'] = 0;
                    $_obfuscate_VgKhVhHUmwÿÿ['allowCancel'] = 0;
                    $_obfuscate_VgKhVhHUmwÿÿ['allowFenFa'] = 0;
                }
            }
            else if ( $_obfuscate_VgKhVhHUmwÿÿ['status'] == 1 )
            {
                $_obfuscate_VgKhVhHUmwÿÿ['allowFenFa'] = 0;
                $_obfuscate_VgKhVhHUmwÿÿ['allowCallback'] = 0;
                $_obfuscate_VgKhVhHUmwÿÿ['allowCancel'] = 0;
                $_obfuscate_VgKhVhHUmwÿÿ['category'] = 1;
            }
            else
            {
                $_obfuscate_VgKhVhHUmwÿÿ['category'] = 2;
                $_obfuscate_VgKhVhHUmwÿÿ['allowCallback'] = 0;
                $_obfuscate_VgKhVhHUmwÿÿ['allowCancel'] = 0;
                $_obfuscate_VgKhVhHUmwÿÿ['allowFenFa'] = 0;
            }
            $_obfuscate_VgKhVhHUmwÿÿ['posttime'] = formatdate( $_obfuscate_VgKhVhHUmwÿÿ['posttime'], "Y-m-d H:i" );
            $_obfuscate_VgKhVhHUmwÿÿ['userName'] = $_obfuscate_VgKhVhHUmwÿÿ['truename'];
            $_obfuscate_VgKhVhHUmwÿÿ['level'] = $this->_getLevelText( $_obfuscate_VgKhVhHUmwÿÿ['level'] );
            if ( $_obfuscate_VgKhVhHUmwÿÿ['fstatus'] == 2 )
            {
                $_obfuscate_VgKhVhHUmwÿÿ['uStepId'] = "3";
            }
            $_obfuscate_VgKhVhHUmwÿÿ['fstatus'] = $this->_getStatusText( $_obfuscate_VgKhVhHUmwÿÿ['fstatus'] );
            $_obfuscate_VgKhVhHUmwÿÿ['face'] = $this->_getFacePath( $_obfuscate_VgKhVhHUmwÿÿ['face'], $_obfuscate_VgKhVhHUmwÿÿ['uid'] );
            if ( $_obfuscate_VgKhVhHUmwÿÿ['hqUid'] == $_obfuscate_7Ri3 && $_obfuscate_VgKhVhHUmwÿÿ['status'] == 1 )
            {
                if ( $_obfuscate_VgKhVhHUmwÿÿ['hqStatus'] == 0 )
                {
                    $_obfuscate_VgKhVhHUmwÿÿ['toHQ'] = 1;
                }
                else
                {
                    $_obfuscate_VgKhVhHUmwÿÿ['toHQ'] = 0;
                }
            }
            else
            {
                $_obfuscate_VgKhVhHUmwÿÿ['toHQ'] = 0;
            }
            if ( $_obfuscate_VgKhVhHUmwÿÿ['ffUid'] == $_obfuscate_7Ri3 && $_obfuscate_VgKhVhHUmwÿÿ['status'] == 1 )
            {
                if ( $_obfuscate_VgKhVhHUmwÿÿ['ffStatus'] == 0 )
                {
                    $_obfuscate_VgKhVhHUmwÿÿ['toFenfa'] = 1;
                    $_obfuscate_VgKhVhHUmwÿÿ['fstatus'] = "å¾…é˜…ä¸­";
                }
                else
                {
                    $_obfuscate_VgKhVhHUmwÿÿ['toFenfa'] = 0;
                }
            }
            else
            {
                $_obfuscate_VgKhVhHUmwÿÿ['toFenfa'] = 0;
            }
            $_obfuscate_8Bnz38wN01cÿ[] = $_obfuscate_VgKhVhHUmwÿÿ;
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_8Bnz38wN01cÿ;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _getMyFlowlist( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_mV9HBLYÿ = ( integer )getpar( $_POST, "start" );
        $_obfuscate_xvYeh9Iÿ = ( integer )getpar( $_POST, "limit", 15 );
        $_obfuscate_dcwitxb = getpar( $_POST, "search" );
        $_obfuscate_IRFhnYwÿ[] = "f.uid = '".$_obfuscate_7Ri3."'";
        if ( !empty( $_obfuscate_dcwitxb ) )
        {
            $_obfuscate_IRFhnYwÿ[] = "(f.flowName LIKE '%".$_obfuscate_dcwitxb."%' OR f.flowNumber LIKE '%{$_obfuscate_dcwitxb}%')";
        }
        $_obfuscate_H9Mbnwÿÿ = " GROUP BY f.uFlowId DESC ORDER BY f.level DESC, f.posttime DESC LIMIT ".$_obfuscate_mV9HBLYÿ.", {$_obfuscate_xvYeh9Iÿ}";
        $_obfuscate_tjILu7ZH = "f.flowId, f.uFlowId, f.flowName, f.flowNumber, f.status AS fstatus, f.uid, f.level, f.posttime, f.allowCallback, f.allowCancel, sf.flowType, sf.tplSort, u.uStepId, u.status, user.face, user.truename";
        $_obfuscate_3y0Y = "SELECT * FROM (SELECT ".$_obfuscate_tjILu7ZH." FROM (SELECT uStepId, uFlowId, status FROM ".tname( $this->t_use_step ).( " WHERE (status = 2 OR status = 4) AND uid = ".$_obfuscate_7Ri3.") AS u " )."LEFT JOIN ".tname( $this->t_use_flow )." AS f ON u.uFlowId = f.uFlowId LEFT JOIN ".tname( $this->t_set_flow )." AS sf ON sf.flowId = f.flowId LEFT JOIN ".tname( $this->main_user )." AS user ON f.uid = user.uid WHERE ".implode( " AND ", $_obfuscate_IRFhnYwÿ ).$_obfuscate_H9Mbnwÿÿ.") AS m ORDER BY fstatus, level DESC, posttime DESC";
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQÿÿ = array( );
        while ( $_obfuscate_orbsHf4ÿ = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_orbsHf4ÿ['allowFenFa'] = 0;
            $_obfuscate_orbsHf4ÿ['allowCallback'] = 0;
            $_obfuscate_orbsHf4ÿ['allowCancel'] = 0;
            $_obfuscate_orbsHf4ÿ['category'] = 2;
            if ( $_obfuscate_orbsHf4ÿ['fstatus'] == 2 && $_obfuscate_orbsHf4ÿ['uid'] == $_obfuscate_7Ri3 )
            {
                $_obfuscate_orbsHf4ÿ['allowFenFa'] = 1;
            }
            if ( $_obfuscate_orbsHf4ÿ['uid'] == $_obfuscate_7Ri3 && $_obfuscate_orbsHf4ÿ['fstatus'] == 1 )
            {
                $_obfuscate_orbsHf4ÿ['allowCallback'] = 1;
                $_obfuscate_orbsHf4ÿ['allowCancel'] = 1;
            }
            $_obfuscate_orbsHf4ÿ['posttime'] = formatdate( $_obfuscate_orbsHf4ÿ['posttime'], "Y-m-d H:i" );
            $_obfuscate_orbsHf4ÿ['level'] = $this->_getLevelText( $_obfuscate_orbsHf4ÿ['level'] );
            $_obfuscate_orbsHf4ÿ['face'] = $this->_getFacePath( $_obfuscate_orbsHf4ÿ['face'], $_obfuscate_orbsHf4ÿ['uid'] );
            if ( $_obfuscate_orbsHf4ÿ['fstatus'] == 2 )
            {
                $_obfuscate_orbsHf4ÿ['uStepId'] = "3";
            }
            $_obfuscate_orbsHf4ÿ['fstatus'] = $this->_getStatusText( $_obfuscate_orbsHf4ÿ['fstatus'] );
            $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_orbsHf4ÿ;
        }
        ( );
        $_obfuscate_o5n931n9CIUÿ = new stdClass( );
        $_obfuscate_o5n931n9CIUÿ->success = TRUE;
        $_obfuscate_o5n931n9CIUÿ->data = $_obfuscate_6RYLWQÿÿ;
        return $_obfuscate_o5n931n9CIUÿ;
    }

    private function _callback( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_JG8GuYÿ = array( );
        $_obfuscate_JG8GuYÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
        $_obfuscate_JG8GuYÿ['type'] = 7;
        $_obfuscate_JG8GuYÿ['step'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_JG8GuYÿ['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuYÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuYÿ['say'] = "";
        $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "stepname", "dealUid", "proxyUid" ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId`='{$_obfuscate_0Ul8BBkt}'" );
        $_obfuscate_JG8GuYÿ['stepname'] = $_obfuscate_Tx7M9W['stepName'];
        $this->insertEvent( $_obfuscate_JG8GuYÿ );
        $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "id", "uid", "proxyUid", "uStepId" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        if ( !is_array( $_obfuscate_Tx7M9W ) )
        {
            $_obfuscate_Tx7M9W = array( );
        }
        $_obfuscate_QwT4KwrB2wÿÿ = array( );
        foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_QwT4KwrB2wÿÿ[] = $_obfuscate_6Aÿÿ['id'];
        }
        $this->deleteAllNotice( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_QwT4KwrB2wÿÿ );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        $_obfuscate_qZkmBgÿÿ = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( $_obfuscate_qZkmBgÿÿ['noticeCallback'] != 4 )
        {
            if ( $_obfuscate_qZkmBgÿÿ['noticeCallback'] == 1 )
            {
                $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "recalles" );
                $_obfuscate_6RYLWQÿÿ['href'] = "";
                $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "uid", "id", "dealUid" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `stepType` = '1' " );
                $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_Tx7M9W['id'];
                $this->addNotice( "notice", $_obfuscate_Tx7M9W['dealUid'], $_obfuscate_6RYLWQÿÿ, "callback", 1 );
            }
            else if ( $_obfuscate_qZkmBgÿÿ['noticeCallback'] == 2 )
            {
                $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "recalles" );
                $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "uid", "id", "dealUid" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uStepId` = '{$_obfuscate_0Ul8BBkt}' " );
                $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
                $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_Tx7M9W['id'];
                $this->addNotice( "notice", $_obfuscate_Tx7M9W['dealUid'], $_obfuscate_6RYLWQÿÿ, "callback", 2 );
            }
            else
            {
                $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "recalles" );
                $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
                $_obfuscate_SIUSR4F6 = $CNOA_DB->db_select( "*", $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `status`=2" );
                if ( !is_array( $_obfuscate_SIUSR4F6 ) )
                {
                    $_obfuscate_SIUSR4F6 = array( );
                }
                foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_6Aÿÿ )
                {
                    $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_6Aÿÿ['id'];
                    $this->addNotice( "notice", $_obfuscate_6Aÿÿ['dealUid'], $_obfuscate_6RYLWQÿÿ, "callback", 3 );
                }
            }
        }
        $_obfuscate_7I1iFsUPtGtFc0eFtkEÿ = "1";
        $CNOA_DB->db_update( array(
            "status" => 0,
            "edittime" => $GLOBALS['CNOA_TIMESTAMP'],
            "callBackStatus" => $_obfuscate_7I1iFsUPtGtFc0eFtkEÿ
        ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        $CNOA_DB->db_delete( $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        $_obfuscate_BxXST4lMTfRblygB = array( );
        $_obfuscate_BxXST4lMTfRblygB['status'] = 0;
        $_obfuscate_8WrXMY5WMEIjCNkjAÿÿ = array( );
        $_obfuscate_Y5NNIxXl7FiX = $CNOA_DB->db_select( array( "fieldId" ), $this->t_set_step_fields, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' AND `stepId` = 2 AND `write` = 1 " );
        if ( $_obfuscate_Y5NNIxXl7FiX )
        {
            foreach ( $_obfuscate_Y5NNIxXl7FiX as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_8WrXMY5WMEIjCNkjAÿÿ[] = $_obfuscate_6Aÿÿ['fieldId'];
            }
        }
        $_obfuscate_mnGpwkqvvb1bAÿÿ = $CNOA_DB->db_select( array( "id", "dvalue" ), $this->t_set_field, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' " );
        if ( $_obfuscate_mnGpwkqvvb1bAÿÿ )
        {
            foreach ( $_obfuscate_mnGpwkqvvb1bAÿÿ as $_obfuscate_6Aÿÿ )
            {
                if ( !in_array( $_obfuscate_6Aÿÿ['id'], $_obfuscate_8WrXMY5WMEIjCNkjAÿÿ ) )
                {
                    $_obfuscate_6Aÿÿ['dvalue'] = $_obfuscate_6Aÿÿ['dvalue'] == "default" ? "" : $_obfuscate_6Aÿÿ['dvalue'];
                    $_obfuscate_BxXST4lMTfRblygB["T_".$_obfuscate_6Aÿÿ['id']] = $_obfuscate_6Aÿÿ['dvalue'];
                }
            }
        }
        $CNOA_DB->db_update( $_obfuscate_BxXST4lMTfRblygB, "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        $_obfuscate_eOytqZnoPmMkuTA2Aÿÿ = $CNOA_DB->db_select( "*", $this->t_use_step_child_flow, "WHERE `status` IN (0,1) AND `puFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        if ( $_obfuscate_eOytqZnoPmMkuTA2Aÿÿ )
        {
            $_obfuscate_xHZmyK5cgÿÿ = array( );
            foreach ( $_obfuscate_eOytqZnoPmMkuTA2Aÿÿ as $_obfuscate_6Aÿÿ )
            {
                $this->deleteNotice( "both", $_obfuscate_6Aÿÿ['id'], "child" );
            }
        }
        $CNOA_DB->db_delete( $this->t_use_step_child_flow, "WHERE `status` IN (0,1) AND `puFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _cancelflow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uid` = '{$_obfuscate_7Ri3}' " );
        if ( empty( $_obfuscate_Ybai ) )
        {
            msg::callback( FALSE, lang( "youNotSponsor" ) );
        }
        $this->api_cancelFlow( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getFlowIdsByUid( $_obfuscate_7Ri3 )
    {
        global $CNOA_DB;
        $_obfuscate_3y0Y = "SELECT DISTINCT uFlowId FROM ".tname( $this->t_use_step ).( " WHERE (`status`=2 OR `status`=4) AND `dealUid`!=0 AND (`uid`='".$_obfuscate_7Ri3."' OR `proxyUid`='{$_obfuscate_7Ri3}')" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        while ( $_obfuscate_VBCv7Qÿÿ = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_dDHiUSY4Qoÿ[] = $_obfuscate_VBCv7Qÿÿ['uFlowId'];
        }
        $_obfuscate_3y0Y = "SELECT DISTINCT uFlowId FROM ".tname( $this->t_use_step_huiqian ).( " WHERE `touid`='".$_obfuscate_7Ri3."' AND `status`=1" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        while ( $_obfuscate_VBCv7Qÿÿ = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_dDHiUSY4Qoÿ[] = $_obfuscate_VBCv7Qÿÿ['uFlowId'];
        }
        return array_unique( $_obfuscate_dDHiUSY4Qoÿ );
    }

    private function _loadFenfaFormData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        if ( empty( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        else
        {
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_5ZL98vEÿ .= $_obfuscate_6Aÿÿ['touid'].",";
                $_obfuscate_e_N_6txY .= app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_6Aÿÿ['touid'] ).",";
                $_obfuscate_tVoOOQj4 .= $this->_getFaceName( $_obfuscate_6Aÿÿ['touid'] ).",";
            }
            $_obfuscate_5ZL98vEÿ = substr( $_obfuscate_5ZL98vEÿ, 0, -1 );
            $_obfuscate_e_N_6txY = substr( $_obfuscate_e_N_6txY, 0, -1 );
            $_obfuscate_tVoOOQj4 = substr( $_obfuscate_tVoOOQj4, 0, -1 );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = array(
            "touid" => $_obfuscate_5ZL98vEÿ,
            "toName" => $_obfuscate_e_N_6txY,
            "toFace" => $_obfuscate_tVoOOQj4
        );
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _fenFaFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate__eqrEQÿÿ = getpar( $_POST, "touid", "" );
        $_obfuscate_PVLK5jra = explode( ",", $_obfuscate__eqrEQÿÿ );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        if ( 0 < $_obfuscate_Ybai )
        {
            $CNOA_DB->db_delete( $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        }
        $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_rLpOghzteElp = array( );
        foreach ( $_obfuscate_PVLK5jra as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_rLpOghzteElp['touid'] = $_obfuscate_6Aÿÿ;
            $_obfuscate_rLpOghzteElp['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
            $_obfuscate_rLpOghzteElp['fenfauid'] = $_obfuscate_7Ri3;
            $CNOA_DB->db_insert( $_obfuscate_rLpOghzteElp, $this->t_use_fenfa );
            $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_7qDAYo85aGAÿ['flowName']."]ç¼–å·[".$_obfuscate_7qDAYo85aGAÿ['flowNumber']."]åˆ†å‘ç»™ä½ é˜…è¯»ã€‚";
            $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_7qDAYo85aGAÿ['flowId']}&flowType={$_obfuscate_XkuTFqZ6Tmkÿ}&tplSort={$_obfuscate_pEvU7Kz2Ywÿÿ}";
            $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_7qDAYo85aGAÿ['flowId'];
            $this->addNotice( "notice", $_obfuscate_rLpOghzteElp['touid'], $_obfuscate_6RYLWQÿÿ, "fenfa" );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getLevelText( $_obfuscate_pYzeLf4ÿ )
    {
        switch ( $_obfuscate_pYzeLf4ÿ )
        {
        case 0 :
            $_obfuscate_hpR8t8270Mhv = "<span style=\"color: green\">æ™®é€š</span>";
            return $_obfuscate_hpR8t8270Mhv;
        case 1 :
            $_obfuscate_hpR8t8270Mhv = "<span style=\"color: orange\">é‡è¦</span>";
            return $_obfuscate_hpR8t8270Mhv;
        case 2 :
            $_obfuscate_hpR8t8270Mhv = "<span style=\"color: red\">éå¸¸é‡è¦</span>";
        }
        return $_obfuscate_hpR8t8270Mhv;
    }

    private function _getStatusText( $_obfuscate_6b8lIO4y )
    {
        switch ( $_obfuscate_6b8lIO4y )
        {
        case 0 :
            $_obfuscate_2xuRTVWhE3sW8wÿÿ = "æœªå‘å¸ƒ";
            return $_obfuscate_2xuRTVWhE3sW8wÿÿ;
        case 1 :
            $_obfuscate_2xuRTVWhE3sW8wÿÿ = "åŠç†ä¸­";
            return $_obfuscate_2xuRTVWhE3sW8wÿÿ;
        case 2 :
            $_obfuscate_2xuRTVWhE3sW8wÿÿ = "å·²åŠç†";
            return $_obfuscate_2xuRTVWhE3sW8wÿÿ;
        case 3 :
            $_obfuscate_2xuRTVWhE3sW8wÿÿ = "å·²é€€ä»¶";
            return $_obfuscate_2xuRTVWhE3sW8wÿÿ;
        case 4 :
            $_obfuscate_2xuRTVWhE3sW8wÿÿ = "å·²æ’¤é”€";
            return $_obfuscate_2xuRTVWhE3sW8wÿÿ;
        case 5 :
            $_obfuscate_2xuRTVWhE3sW8wÿÿ = "å·²åˆ é™¤";
            return $_obfuscate_2xuRTVWhE3sW8wÿÿ;
        case 6 :
            $_obfuscate_2xuRTVWhE3sW8wÿÿ = "å·²ä¸­æ­¢";
        }
        return $_obfuscate_2xuRTVWhE3sW8wÿÿ;
    }

    private function _getFaceName( $_obfuscate_7Ri3 )
    {
        global $CNOA_DB;
        if ( empty( $_obfuscate_7Ri3 ) )
        {
            return "";
        }
        $_obfuscate_s6tRQQÿÿ = $CNOA_DB->db_getfield( "face", tname( "main_user" ), "WHERE uid = ".$_obfuscate_7Ri3 );
        return $this->_getFacePath( $_obfuscate_s6tRQQÿÿ, $_obfuscate_7Ri3 );
    }

    private function _getFacePath( $_obfuscate_pp9pYwÿÿ, $_obfuscate_7Ri3 )
    {
        if ( empty( $_obfuscate_pp9pYwÿÿ ) )
        {
            $_obfuscate_6UUC = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
            return $_obfuscate_6UUC;
        }
        $_obfuscate_b9_qaEFdaQÿÿ = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/".$_obfuscate_7Ri3."/130x130_".$_obfuscate_pp9pYwÿÿ;
        if ( file_exists( $_obfuscate_b9_qaEFdaQÿÿ ) )
        {
            $_obfuscate_6UUC = $_obfuscate_b9_qaEFdaQÿÿ;
            return $_obfuscate_6UUC;
        }
        $_obfuscate_6UUC = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
        return $_obfuscate_6UUC;
    }

}

?>
