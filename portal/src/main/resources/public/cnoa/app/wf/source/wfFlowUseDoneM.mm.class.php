<?php

class wfFlowUseDoneM extends wfCommon
{

    public function run( )
    {
        $_obfuscate_M_5JJw�� = getpar( $_GET, "task" );
        switch ( $_obfuscate_M_5JJw�� )
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
        $_obfuscate_mV9HBLY� = ( integer )getpar( $_POST, "start" );
        $_obfuscate_xvYeh9I� = ( integer )getpar( $_POST, "limit", 15 );
        $_obfuscate_dcwitxb = getpar( $_POST, "search" );
        $_obfuscate_IRFhnYw�[] = "f.status IN (1, 2, 4, 6)";
        if ( !empty( $_obfuscate_dcwitxb ) )
        {
            $_obfuscate_IRFhnYw�[] = "(f.flowName LIKE '%".$_obfuscate_dcwitxb."%' OR f.flowNumber LIKE '%{$_obfuscate_dcwitxb}%')";
        }
        $_obfuscate_UgqmbA�� = "SELECT {fields} FROM (SELECT * FROM ".tname( $this->t_use_step ).( " WHERE (`status`=2 OR `status`=4 OR `status`=5) AND (`uid`='".$_obfuscate_7Ri3."' OR `proxyUid`='{$_obfuscate_7Ri3}')) AS u" )." LEFT JOIN ".tname( $this->t_use_flow )." AS f ON u.uFlowId = f.uFlowId LEFT JOIN ".tname( $this->t_set_flow )." AS sf ON sf.flowId = f.flowId LEFT JOIN ".tname( $this->main_user )." AS user ON f.uid = user.uid WHERE ".implode( " AND ", $_obfuscate_IRFhnYw� );
        $_obfuscate_tjILu7ZH = "f.flowId, f.uFlowId, f.flowName, f.flowNumber, f.status, f.uid, f.level, f.posttime, f.allowCallback, f.allowCancel, sf.flowType, sf.tplSort, u.uStepId, user.face, user.truename";
        $_obfuscate_3y0Y = "SELECT * FROM (".strtr( $_obfuscate_UgqmbA��, array(
            "{fields}" => $_obfuscate_tjILu7ZH
        ) )." GROUP BY f.uFlowId DESC ORDER BY f.level DESC, f.posttime DESC".( " LIMIT ".$_obfuscate_mV9HBLY�.", {$_obfuscate_xvYeh9I�} ) AS m ORDER BY status ASC, level DESC, posttime DESC" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_orbsHf4� = array( );
        $_obfuscate_ouqX2cxvhA�� = $CNOA_DB->db_select( array( "uFlowId" ), $this->t_use_step_huiqian, " WHERE `touid`=".$_obfuscate_7Ri3." AND `status`=1" );
        foreach ( $_obfuscate_ouqX2cxvhA�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_EKP_rQ�� = "select f.flowId, f.uFlowId, f.flowName, f.flowNumber, f.status, f.uid, f.level, f.posttime, f.allowCallback, f.allowCancel, sf.flowType, sf.tplSort, u.uStepId, user.face, user.truename from (select * from ".tname( $this->t_use_step )."WHERE (`status`=2 OR `status`=4 OR `status`=5))as u left join".tname( $this->t_use_flow )."AS f ON u.uFlowId = f.uFlowId LEFT JOIN ".tname( $this->t_set_flow )." AS sf ON sf.flowId = f.flowId LEFT JOIN ".tname( $this->main_user )." AS user ON f.uid = user.uid WHERE ".implode( " AND ", $_obfuscate_IRFhnYw� )." and f.uFlowId=".$_obfuscate_6A��['uFlowId'];
            $_obfuscate_amHmH9FVA�� = $CNOA_DB->query( $_obfuscate_EKP_rQ�� );
            while ( $_obfuscate_4h6otZd1 = $CNOA_DB->get_array( $_obfuscate_amHmH9FVA�� ) )
            {
                $_obfuscate_orbsHf4�[] = $_obfuscate_4h6otZd1;
            }
        }
        $_obfuscate_6RYLWQ�� = array( );
        while ( $_obfuscate__kbonB1Z = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_orbsHf4�[] = $_obfuscate__kbonB1Z;
        }
        foreach ( $_obfuscate_orbsHf4� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['flowId'] = $_obfuscate_6A��['flowId'];
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['flowNumber'] = $_obfuscate_6A��['flowNumber'];
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['uFlowId'] = $_obfuscate_6A��['uFlowId'];
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['flowName'] = $_obfuscate_6A��['flowName'];
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['flowType'] = $_obfuscate_6A��['flowType'];
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['tplSort'] = $_obfuscate_6A��['tplSort'];
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['uStepId'] = $_obfuscate_6A��['uStepId'];
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['truename'] = $_obfuscate_6A��['truename'];
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['uid'] = $_obfuscate_6A��['uid'];
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['posttime'] = formatdate( $_obfuscate_6A��['posttime'], "Y-m-d H:i" );
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['level'] = $this->_getLevelText( $_obfuscate_6A��['level'] );
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['face'] = $this->_getFacePath( $_obfuscate_6A��['face'], $_obfuscate_6A��['uid'] );
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['allowFenFa'] = 0;
            if ( $_obfuscate_6A��['uid'] == $_obfuscate_7Ri3 && $_obfuscate_6A��['status'] == 1 )
            {
                $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['allowCallback'] = $_obfuscate_6A��['allowCallback'];
                $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['allowCancel'] = $_obfuscate_6A��['allowCancel'];
            }
            else
            {
                $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['allowCallback'] = 0;
                $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['allowCancel'] = 0;
            }
            if ( $_obfuscate_6A��['status'] == 2 )
            {
                $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['allowFenFa'] = 1;
            }
            if ( $_obfuscate_6A��['status'] == 2 )
            {
                $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['uStepId'] = 3;
            }
            $_obfuscate_6RYLWQ��[$_obfuscate_5w��]['status'] = $this->_getStatusText( $_obfuscate_6A��['status'] );
        }
        $_obfuscate_3y0Y = strtr( $_obfuscate_UgqmbA��, array( "{fields}" => "count(DISTINCT f.uFlowId) AS total" ) );
        $_obfuscate_j9sJes� = $CNOA_DB->get_one( $_obfuscate_3y0Y );
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_6RYLWQ��;
        $_obfuscate_SUjPN94Er7yI->total = $_obfuscate_j9sJes�['total'];
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _getAllFlowlist( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_mV9HBLY� = ( integer )getpar( $_GET, "start", getpar( $_POST, "start" ) );
        $_obfuscate_xvYeh9I� = ( integer )getpar( $_GET, "limit", getpar( $_POST, "limit", 15 ) );
        $_obfuscate_dcwitxb = getpar( $_GET, "search", getpar( $_POST, "search", "" ) );
        $_obfuscate_Bk2lGlk�[] = "s.status IN(1, 2, 4, 5) AND (s.uid = ".$_obfuscate_7Ri3." OR s.proxyUid = {$_obfuscate_7Ri3} OR (h.touid = {$_obfuscate_7Ri3} AND h.status = 0 AND h.issubmit = 1) OR (ff.touid = {$_obfuscate_7Ri3} AND ff.status = 0 AND ff.isread = 0)) AND sf.tplSort = 0";
        if ( !empty( $_obfuscate_dcwitxb ) )
        {
            $_obfuscate_Bk2lGlk�[] = "(f.flowNumber LIKE '%".$_obfuscate_dcwitxb."%' OR f.flowName LIKE '%{$_obfuscate_dcwitxb}%')";
        }
        $_obfuscate_tjILu7ZH = "SELECT `f`.`uFlowId`, `f`.`flowId`, `f`.`flowName`, `f`.`flowNumber`, `f`.`uid`, `f`.`posttime`, `f`.`level`, `f`.`allowCallback`, `f`.`allowCancel`, `f`.`status` AS fstatus, `f`.`endtime`, `s`.`uStepId`, `s`.`status`, `sf`.`tplSort`, `sf`.`flowType`, `s`.`uid` AS sUid, `user`.`face`, `user`.`truename`, `h`.`touid` AS hqUid, `h`.`status` AS hqStatus, `ff`.`touid` AS ffUid, `ff`.`status` AS ffStatus ";
        $_obfuscate_aM13xdJ441kqA�� = " ORDER BY s.status ASC";
        $_obfuscate_0nWRhrF6sLGirzfm = "GROUP BY uFlowId DESC ORDER BY level DESC, posttime DESC LIMIT ".$_obfuscate_mV9HBLY�.", {$_obfuscate_xvYeh9I�}";
        $_obfuscate_0yyGECzPHaDJUBj = "FROM ".tname( $this->t_use_flow )." AS f LEFT JOIN ".tname( $this->t_set_flow )." AS sf ON sf.flowId = f.flowId LEFT JOIN ".tname( $this->t_use_step )." AS s ON f.uFlowId = s.uFlowId LEFT JOIN ".tname( $this->t_use_step_huiqian )." AS h ON f.uFlowId = h.uFlowId AND s.uStepId = h.stepId LEFT JOIN ".tname( $this->t_use_fenfa )." AS ff ON f.uFlowId = ff.uFlowId AND s.uStepId = ff.stepId LEFT JOIN ".tname( $this->main_user )." AS user ON f.uid = user.uid ";
        $_obfuscate_3y0Y = "SELECT * FROM (SELECT * FROM (".$_obfuscate_tjILu7ZH.$_obfuscate_0yyGECzPHaDJUBj."WHERE ".implode( " AND ", $_obfuscate_Bk2lGlk� ).$_obfuscate_aM13xdJ441kqA��.") AS m ".$_obfuscate_0nWRhrF6sLGirzfm.") AS n ORDER BY fstatus, level DESC, posttime DESC";
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_8Bnz38wN01c� = array( );
        while ( $_obfuscate_VgKhVhHUmw�� = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            if ( $_obfuscate_VgKhVhHUmw��['status'] == 2 || $_obfuscate_VgKhVhHUmw��['status'] == 4 )
            {
                $_obfuscate_VgKhVhHUmw��['category'] = 2;
                if ( $_obfuscate_VgKhVhHUmw��['uid'] == $_obfuscate_7Ri3 && $_obfuscate_VgKhVhHUmw��['fstatus'] == 1 )
                {
                    $_obfuscate_VgKhVhHUmw��['allowCallback'] = $_obfuscate_VgKhVhHUmw��['allowCallback'];
                    $_obfuscate_VgKhVhHUmw��['allowCancel'] = $_obfuscate_VgKhVhHUmw��['allowCancel'];
                    $_obfuscate_VgKhVhHUmw��['allowFenFa'] = 0;
                }
                else if ( $_obfuscate_VgKhVhHUmw��['uid'] == $_obfuscate_7Ri3 && $_obfuscate_VgKhVhHUmw��['fstatus'] == 2 )
                {
                    $_obfuscate_VgKhVhHUmw��['allowCallback'] = 0;
                    $_obfuscate_VgKhVhHUmw��['allowCancel'] = 0;
                    $_obfuscate_VgKhVhHUmw��['allowFenFa'] = 1;
                }
                else
                {
                    $_obfuscate_VgKhVhHUmw��['allowCallback'] = 0;
                    $_obfuscate_VgKhVhHUmw��['allowCancel'] = 0;
                    $_obfuscate_VgKhVhHUmw��['allowFenFa'] = 0;
                }
            }
            else if ( $_obfuscate_VgKhVhHUmw��['status'] == 1 )
            {
                $_obfuscate_VgKhVhHUmw��['allowFenFa'] = 0;
                $_obfuscate_VgKhVhHUmw��['allowCallback'] = 0;
                $_obfuscate_VgKhVhHUmw��['allowCancel'] = 0;
                $_obfuscate_VgKhVhHUmw��['category'] = 1;
            }
            else
            {
                $_obfuscate_VgKhVhHUmw��['category'] = 2;
                $_obfuscate_VgKhVhHUmw��['allowCallback'] = 0;
                $_obfuscate_VgKhVhHUmw��['allowCancel'] = 0;
                $_obfuscate_VgKhVhHUmw��['allowFenFa'] = 0;
            }
            $_obfuscate_VgKhVhHUmw��['posttime'] = formatdate( $_obfuscate_VgKhVhHUmw��['posttime'], "Y-m-d H:i" );
            $_obfuscate_VgKhVhHUmw��['userName'] = $_obfuscate_VgKhVhHUmw��['truename'];
            $_obfuscate_VgKhVhHUmw��['level'] = $this->_getLevelText( $_obfuscate_VgKhVhHUmw��['level'] );
            if ( $_obfuscate_VgKhVhHUmw��['fstatus'] == 2 )
            {
                $_obfuscate_VgKhVhHUmw��['uStepId'] = "3";
            }
            $_obfuscate_VgKhVhHUmw��['fstatus'] = $this->_getStatusText( $_obfuscate_VgKhVhHUmw��['fstatus'] );
            $_obfuscate_VgKhVhHUmw��['face'] = $this->_getFacePath( $_obfuscate_VgKhVhHUmw��['face'], $_obfuscate_VgKhVhHUmw��['uid'] );
            if ( $_obfuscate_VgKhVhHUmw��['hqUid'] == $_obfuscate_7Ri3 && $_obfuscate_VgKhVhHUmw��['status'] == 1 )
            {
                if ( $_obfuscate_VgKhVhHUmw��['hqStatus'] == 0 )
                {
                    $_obfuscate_VgKhVhHUmw��['toHQ'] = 1;
                }
                else
                {
                    $_obfuscate_VgKhVhHUmw��['toHQ'] = 0;
                }
            }
            else
            {
                $_obfuscate_VgKhVhHUmw��['toHQ'] = 0;
            }
            if ( $_obfuscate_VgKhVhHUmw��['ffUid'] == $_obfuscate_7Ri3 && $_obfuscate_VgKhVhHUmw��['status'] == 1 )
            {
                if ( $_obfuscate_VgKhVhHUmw��['ffStatus'] == 0 )
                {
                    $_obfuscate_VgKhVhHUmw��['toFenfa'] = 1;
                    $_obfuscate_VgKhVhHUmw��['fstatus'] = "待阅中";
                }
                else
                {
                    $_obfuscate_VgKhVhHUmw��['toFenfa'] = 0;
                }
            }
            else
            {
                $_obfuscate_VgKhVhHUmw��['toFenfa'] = 0;
            }
            $_obfuscate_8Bnz38wN01c�[] = $_obfuscate_VgKhVhHUmw��;
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_8Bnz38wN01c�;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _getMyFlowlist( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_mV9HBLY� = ( integer )getpar( $_POST, "start" );
        $_obfuscate_xvYeh9I� = ( integer )getpar( $_POST, "limit", 15 );
        $_obfuscate_dcwitxb = getpar( $_POST, "search" );
        $_obfuscate_IRFhnYw�[] = "f.uid = '".$_obfuscate_7Ri3."'";
        if ( !empty( $_obfuscate_dcwitxb ) )
        {
            $_obfuscate_IRFhnYw�[] = "(f.flowName LIKE '%".$_obfuscate_dcwitxb."%' OR f.flowNumber LIKE '%{$_obfuscate_dcwitxb}%')";
        }
        $_obfuscate_H9Mbnw�� = " GROUP BY f.uFlowId DESC ORDER BY f.level DESC, f.posttime DESC LIMIT ".$_obfuscate_mV9HBLY�.", {$_obfuscate_xvYeh9I�}";
        $_obfuscate_tjILu7ZH = "f.flowId, f.uFlowId, f.flowName, f.flowNumber, f.status AS fstatus, f.uid, f.level, f.posttime, f.allowCallback, f.allowCancel, sf.flowType, sf.tplSort, u.uStepId, u.status, user.face, user.truename";
        $_obfuscate_3y0Y = "SELECT * FROM (SELECT ".$_obfuscate_tjILu7ZH." FROM (SELECT uStepId, uFlowId, status FROM ".tname( $this->t_use_step ).( " WHERE (status = 2 OR status = 4) AND uid = ".$_obfuscate_7Ri3.") AS u " )."LEFT JOIN ".tname( $this->t_use_flow )." AS f ON u.uFlowId = f.uFlowId LEFT JOIN ".tname( $this->t_set_flow )." AS sf ON sf.flowId = f.flowId LEFT JOIN ".tname( $this->main_user )." AS user ON f.uid = user.uid WHERE ".implode( " AND ", $_obfuscate_IRFhnYw� ).$_obfuscate_H9Mbnw��.") AS m ORDER BY fstatus, level DESC, posttime DESC";
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQ�� = array( );
        while ( $_obfuscate_orbsHf4� = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_orbsHf4�['allowFenFa'] = 0;
            $_obfuscate_orbsHf4�['allowCallback'] = 0;
            $_obfuscate_orbsHf4�['allowCancel'] = 0;
            $_obfuscate_orbsHf4�['category'] = 2;
            if ( $_obfuscate_orbsHf4�['fstatus'] == 2 && $_obfuscate_orbsHf4�['uid'] == $_obfuscate_7Ri3 )
            {
                $_obfuscate_orbsHf4�['allowFenFa'] = 1;
            }
            if ( $_obfuscate_orbsHf4�['uid'] == $_obfuscate_7Ri3 && $_obfuscate_orbsHf4�['fstatus'] == 1 )
            {
                $_obfuscate_orbsHf4�['allowCallback'] = 1;
                $_obfuscate_orbsHf4�['allowCancel'] = 1;
            }
            $_obfuscate_orbsHf4�['posttime'] = formatdate( $_obfuscate_orbsHf4�['posttime'], "Y-m-d H:i" );
            $_obfuscate_orbsHf4�['level'] = $this->_getLevelText( $_obfuscate_orbsHf4�['level'] );
            $_obfuscate_orbsHf4�['face'] = $this->_getFacePath( $_obfuscate_orbsHf4�['face'], $_obfuscate_orbsHf4�['uid'] );
            if ( $_obfuscate_orbsHf4�['fstatus'] == 2 )
            {
                $_obfuscate_orbsHf4�['uStepId'] = "3";
            }
            $_obfuscate_orbsHf4�['fstatus'] = $this->_getStatusText( $_obfuscate_orbsHf4�['fstatus'] );
            $_obfuscate_6RYLWQ��[] = $_obfuscate_orbsHf4�;
        }
        ( );
        $_obfuscate_o5n931n9CIU� = new stdClass( );
        $_obfuscate_o5n931n9CIU�->success = TRUE;
        $_obfuscate_o5n931n9CIU�->data = $_obfuscate_6RYLWQ��;
        return $_obfuscate_o5n931n9CIU�;
    }

    private function _callback( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_JG8GuY� = array( );
        $_obfuscate_JG8GuY�['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
        $_obfuscate_JG8GuY�['type'] = 7;
        $_obfuscate_JG8GuY�['step'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_JG8GuY�['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuY�['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuY�['say'] = "";
        $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "stepname", "dealUid", "proxyUid" ), $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId`='{$_obfuscate_0Ul8BBkt}'" );
        $_obfuscate_JG8GuY�['stepname'] = $_obfuscate_Tx7M9W['stepName'];
        $this->insertEvent( $_obfuscate_JG8GuY� );
        $_obfuscate_Tx7M9W = $CNOA_DB->db_select( array( "id", "uid", "proxyUid", "uStepId" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        if ( !is_array( $_obfuscate_Tx7M9W ) )
        {
            $_obfuscate_Tx7M9W = array( );
        }
        $_obfuscate_QwT4KwrB2w�� = array( );
        foreach ( $_obfuscate_Tx7M9W as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_QwT4KwrB2w��[] = $_obfuscate_6A��['id'];
        }
        $this->deleteAllNotice( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_QwT4KwrB2w�� );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber" ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        $_obfuscate_qZkmBg�� = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        if ( $_obfuscate_qZkmBg��['noticeCallback'] != 4 )
        {
            if ( $_obfuscate_qZkmBg��['noticeCallback'] == 1 )
            {
                $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "recalles" );
                $_obfuscate_6RYLWQ��['href'] = "";
                $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "uid", "id", "dealUid" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `stepType` = '1' " );
                $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_Tx7M9W['id'];
                $this->addNotice( "notice", $_obfuscate_Tx7M9W['dealUid'], $_obfuscate_6RYLWQ��, "callback", 1 );
            }
            else if ( $_obfuscate_qZkmBg��['noticeCallback'] == 2 )
            {
                $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "recalles" );
                $_obfuscate_Tx7M9W = $CNOA_DB->db_getone( array( "uid", "id", "dealUid" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `uStepId` = '{$_obfuscate_0Ul8BBkt}' " );
                $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
                $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_Tx7M9W['id'];
                $this->addNotice( "notice", $_obfuscate_Tx7M9W['dealUid'], $_obfuscate_6RYLWQ��, "callback", 2 );
            }
            else
            {
                $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "recalles" );
                $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
                $_obfuscate_SIUSR4F6 = $CNOA_DB->db_select( "*", $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `status`=2" );
                if ( !is_array( $_obfuscate_SIUSR4F6 ) )
                {
                    $_obfuscate_SIUSR4F6 = array( );
                }
                foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_6A�� )
                {
                    $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_6A��['id'];
                    $this->addNotice( "notice", $_obfuscate_6A��['dealUid'], $_obfuscate_6RYLWQ��, "callback", 3 );
                }
            }
        }
        $_obfuscate_7I1iFsUPtGtFc0eFtkE� = "1";
        $CNOA_DB->db_update( array(
            "status" => 0,
            "edittime" => $GLOBALS['CNOA_TIMESTAMP'],
            "callBackStatus" => $_obfuscate_7I1iFsUPtGtFc0eFtkE�
        ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        $CNOA_DB->db_delete( $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        $_obfuscate_BxXST4lMTfRblygB = array( );
        $_obfuscate_BxXST4lMTfRblygB['status'] = 0;
        $_obfuscate_8WrXMY5WMEIjCNkjA�� = array( );
        $_obfuscate_Y5NNIxXl7FiX = $CNOA_DB->db_select( array( "fieldId" ), $this->t_set_step_fields, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' AND `stepId` = 2 AND `write` = 1 " );
        if ( $_obfuscate_Y5NNIxXl7FiX )
        {
            foreach ( $_obfuscate_Y5NNIxXl7FiX as $_obfuscate_6A�� )
            {
                $_obfuscate_8WrXMY5WMEIjCNkjA��[] = $_obfuscate_6A��['fieldId'];
            }
        }
        $_obfuscate_mnGpwkqvvb1bA�� = $CNOA_DB->db_select( array( "id", "dvalue" ), $this->t_set_field, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' " );
        if ( $_obfuscate_mnGpwkqvvb1bA�� )
        {
            foreach ( $_obfuscate_mnGpwkqvvb1bA�� as $_obfuscate_6A�� )
            {
                if ( !in_array( $_obfuscate_6A��['id'], $_obfuscate_8WrXMY5WMEIjCNkjA�� ) )
                {
                    $_obfuscate_6A��['dvalue'] = $_obfuscate_6A��['dvalue'] == "default" ? "" : $_obfuscate_6A��['dvalue'];
                    $_obfuscate_BxXST4lMTfRblygB["T_".$_obfuscate_6A��['id']] = $_obfuscate_6A��['dvalue'];
                }
            }
        }
        $CNOA_DB->db_update( $_obfuscate_BxXST4lMTfRblygB, "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        $_obfuscate_eOytqZnoPmMkuTA2A�� = $CNOA_DB->db_select( "*", $this->t_use_step_child_flow, "WHERE `status` IN (0,1) AND `puFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        if ( $_obfuscate_eOytqZnoPmMkuTA2A�� )
        {
            $_obfuscate_xHZmyK5cg�� = array( );
            foreach ( $_obfuscate_eOytqZnoPmMkuTA2A�� as $_obfuscate_6A�� )
            {
                $this->deleteNotice( "both", $_obfuscate_6A��['id'], "child" );
            }
        }
        $CNOA_DB->db_delete( $this->t_use_step_child_flow, "WHERE `status` IN (0,1) AND `puFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _cancelflow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId", 0 );
        $_obfuscate_0Ul8BBkt = getpar( $_POST, "stepId", 0 );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' AND `uid` = '{$_obfuscate_7Ri3}' " );
        if ( empty( $_obfuscate_Ybai ) )
        {
            msg::callback( FALSE, lang( "youNotSponsor" ) );
        }
        $this->api_cancelFlow( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getFlowIdsByUid( $_obfuscate_7Ri3 )
    {
        global $CNOA_DB;
        $_obfuscate_3y0Y = "SELECT DISTINCT uFlowId FROM ".tname( $this->t_use_step ).( " WHERE (`status`=2 OR `status`=4) AND `dealUid`!=0 AND (`uid`='".$_obfuscate_7Ri3."' OR `proxyUid`='{$_obfuscate_7Ri3}')" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        while ( $_obfuscate_VBCv7Q�� = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_dDHiUSY4Qo�[] = $_obfuscate_VBCv7Q��['uFlowId'];
        }
        $_obfuscate_3y0Y = "SELECT DISTINCT uFlowId FROM ".tname( $this->t_use_step_huiqian ).( " WHERE `touid`='".$_obfuscate_7Ri3."' AND `status`=1" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        while ( $_obfuscate_VBCv7Q�� = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_dDHiUSY4Qo�[] = $_obfuscate_VBCv7Q��['uFlowId'];
        }
        return array_unique( $_obfuscate_dDHiUSY4Qo� );
    }

    private function _loadFenfaFormData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
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
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_5ZL98vE� .= $_obfuscate_6A��['touid'].",";
                $_obfuscate_e_N_6txY .= app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_6A��['touid'] ).",";
                $_obfuscate_tVoOOQj4 .= $this->_getFaceName( $_obfuscate_6A��['touid'] ).",";
            }
            $_obfuscate_5ZL98vE� = substr( $_obfuscate_5ZL98vE�, 0, -1 );
            $_obfuscate_e_N_6txY = substr( $_obfuscate_e_N_6txY, 0, -1 );
            $_obfuscate_tVoOOQj4 = substr( $_obfuscate_tVoOOQj4, 0, -1 );
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = array(
            "touid" => $_obfuscate_5ZL98vE�,
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
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_POST, "uFlowId", 0 );
        $_obfuscate__eqrEQ�� = getpar( $_POST, "touid", "" );
        $_obfuscate_PVLK5jra = explode( ",", $_obfuscate__eqrEQ�� );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_POST, "flowType", 0 );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_POST, "tplSort", 0 );
        $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        if ( 0 < $_obfuscate_Ybai )
        {
            $CNOA_DB->db_delete( $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        }
        $_obfuscate_7qDAYo85aGA� = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_rLpOghzteElp = array( );
        foreach ( $_obfuscate_PVLK5jra as $_obfuscate_6A�� )
        {
            $_obfuscate_rLpOghzteElp['touid'] = $_obfuscate_6A��;
            $_obfuscate_rLpOghzteElp['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
            $_obfuscate_rLpOghzteElp['fenfauid'] = $_obfuscate_7Ri3;
            $CNOA_DB->db_insert( $_obfuscate_rLpOghzteElp, $this->t_use_fenfa );
            $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_7qDAYo85aGA�['flowName']."]编号[".$_obfuscate_7qDAYo85aGA�['flowNumber']."]分发给你阅读。";
            $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_7qDAYo85aGA�['flowId']}&flowType={$_obfuscate_XkuTFqZ6Tmk�}&tplSort={$_obfuscate_pEvU7Kz2Yw��}";
            $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_7qDAYo85aGA�['flowId'];
            $this->addNotice( "notice", $_obfuscate_rLpOghzteElp['touid'], $_obfuscate_6RYLWQ��, "fenfa" );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getLevelText( $_obfuscate_pYzeLf4� )
    {
        switch ( $_obfuscate_pYzeLf4� )
        {
        case 0 :
            $_obfuscate_hpR8t8270Mhv = "<span style=\"color: green\">普通</span>";
            return $_obfuscate_hpR8t8270Mhv;
        case 1 :
            $_obfuscate_hpR8t8270Mhv = "<span style=\"color: orange\">重要</span>";
            return $_obfuscate_hpR8t8270Mhv;
        case 2 :
            $_obfuscate_hpR8t8270Mhv = "<span style=\"color: red\">非常重要</span>";
        }
        return $_obfuscate_hpR8t8270Mhv;
    }

    private function _getStatusText( $_obfuscate_6b8lIO4y )
    {
        switch ( $_obfuscate_6b8lIO4y )
        {
        case 0 :
            $_obfuscate_2xuRTVWhE3sW8w�� = "未发布";
            return $_obfuscate_2xuRTVWhE3sW8w��;
        case 1 :
            $_obfuscate_2xuRTVWhE3sW8w�� = "办理中";
            return $_obfuscate_2xuRTVWhE3sW8w��;
        case 2 :
            $_obfuscate_2xuRTVWhE3sW8w�� = "已办理";
            return $_obfuscate_2xuRTVWhE3sW8w��;
        case 3 :
            $_obfuscate_2xuRTVWhE3sW8w�� = "已退件";
            return $_obfuscate_2xuRTVWhE3sW8w��;
        case 4 :
            $_obfuscate_2xuRTVWhE3sW8w�� = "已撤销";
            return $_obfuscate_2xuRTVWhE3sW8w��;
        case 5 :
            $_obfuscate_2xuRTVWhE3sW8w�� = "已删除";
            return $_obfuscate_2xuRTVWhE3sW8w��;
        case 6 :
            $_obfuscate_2xuRTVWhE3sW8w�� = "已中止";
        }
        return $_obfuscate_2xuRTVWhE3sW8w��;
    }

    private function _getFaceName( $_obfuscate_7Ri3 )
    {
        global $CNOA_DB;
        if ( empty( $_obfuscate_7Ri3 ) )
        {
            return "";
        }
        $_obfuscate_s6tRQQ�� = $CNOA_DB->db_getfield( "face", tname( "main_user" ), "WHERE uid = ".$_obfuscate_7Ri3 );
        return $this->_getFacePath( $_obfuscate_s6tRQQ��, $_obfuscate_7Ri3 );
    }

    private function _getFacePath( $_obfuscate_pp9pYw��, $_obfuscate_7Ri3 )
    {
        if ( empty( $_obfuscate_pp9pYw�� ) )
        {
            $_obfuscate_6UUC = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
            return $_obfuscate_6UUC;
        }
        $_obfuscate_b9_qaEFdaQ�� = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/".$_obfuscate_7Ri3."/130x130_".$_obfuscate_pp9pYw��;
        if ( file_exists( $_obfuscate_b9_qaEFdaQ�� ) )
        {
            $_obfuscate_6UUC = $_obfuscate_b9_qaEFdaQ��;
            return $_obfuscate_6UUC;
        }
        $_obfuscate_6UUC = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
        return $_obfuscate_6UUC;
    }

}

?>
