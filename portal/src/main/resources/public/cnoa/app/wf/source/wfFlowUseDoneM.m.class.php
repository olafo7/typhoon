<?php

class wfFlowUseDoneM extends wfCommon
{

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        switch ( $task )
        {
        case "getWfList" :
            return $this->_getWfList( );
        case "getAllFlowlist" :
            return $this->_getAllFlowlist( );
        case "getMyFlowlist" :
            return $this->_getMyFlowlist( );
        }
    }

    private function _getWfList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $start = ( integer )getpar( $_POST, "start" );
        $limit = ( integer )getpar( $_POST, "limit", 15 );
        $search = getpar( $_POST, "search" );
        $where[] = "f.status IN (1, 2, 4, 6)";
        if ( !empty( $search ) )
        {
            $where[] = "(f.flowName LIKE '%".$search."%' OR f.flowNumber LIKE '%{$search}%')";
        }
        $stmt = "SELECT {fields} FROM  (SELECT * FROM ".tname( $this->t_use_step ).( " WHERE `dealUid`!=0 AND (`status`=2 OR `status`=4) AND (`uid`='".$uid."' OR `proxyUid`='{$uid}')" )." ORDER BY etime DESC) AS u LEFT JOIN ".tname( $this->t_use_flow )." AS f ON u.uFlowId=f.uFlowId LEFT JOIN ".tname( $this->main_user )." AS user ON f.uid=user.uid WHERE ".implode( " AND ", $where );
        $fields = "f.flowId, f.uFlowId, f.flowName, f.flowNumber, f.status, f.level, f.posttime, u.uStepId, user.face, user.truename ";
        $sql = strtr( $stmt, array(
            "{fields}" => $fields
        ) )." GROUP BY u.uFlowId DESC ORDER BY f.level DESC, etime DESC".( " LIMIT ".$start.", {$limit}" );
        $result = $CNOA_DB->query( $sql );
        $data = array( );
        while ( $uflow = $CNOA_DB->get_array( $result ) )
        {
            $uflow['status'] = $this->_getStatusText( $uflow['status'] );
            $uflow['posttime'] = formatdate( $uflow['posttime'], "Y-m-d H:i" );
            if ( $uflow['level'] == 0 )
            {
                $uflow['level'] = "<span style=\"color: green\">普通</span>";
            }
            else if ( $uflow['level'] == 1 )
            {
                $uflow['level'] = "<span style=\"color: orange\">重要</span>";
            }
            else if ( $uflow['level'] == 2 )
            {
                $uflow['level'] = "<span style=\"color: red\">非常重要</span>";
            }
            if ( empty( $uflow['face'] ) )
            {
                $uflow['face'] = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
            }
            else
            {
                $faceUrl = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/".$uid."/120x160_".$uflow['face'];
                if ( file_exists( $faceUrl ) )
                {
                    $uflow['face'] = $faceUrl;
                }
                else
                {
                    $uflow['face'] = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
                }
            }
            $data[] = $uflow;
        }
        $sql = strtr( $stmt, array( "{fields}" => "count(DISTINCT f.uFlowId) AS total" ) );
        $total = $CNOA_DB->get_one( $sql );
        ( );
        $response = new stdClass( );
        $response->success = TRUE;
        $response->total = $total['total'];
        $response->data = $data;
        return $response;
    }

    private function _getAllFlowlist( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $start = ( integer )getpar( $_POST, "start" );
        $limit = ( integer )getpar( $_POST, "limit", 15 );
        $search = getpar( $_POST, "search" );
        $where[] = "f.status IN (0, 1, 2, 3, 4, 6)";
        if ( !empty( $search ) )
        {
            $where[] = "(f.flowName LIKE '%".$search."%' OR f.flowNumber LIKE '%{$search}%')";
        }
        $stmt = "SELECT {fields} FROM  (SELECT * FROM ".tname( $this->t_use_step ).( " WHERE `uid`='".$uid."' OR `proxyUid`='{$uid}'" )." ORDER BY etime DESC) AS s LEFT JOIN ".tname( $this->t_use_flow )." AS f ON s.uFlowId=f.uFlowId LEFT JOIN ".tname( $this->main_user )." AS u ON f.uid=u.uid WHERE ".implode( " AND ", $where );
        $data = array( );
        $fields = "DISTINCT f.uFlowId, f.flowId, f.flowName, f.flowNumber, f.status, f.level, f.posttime, s.uStepId, u.face, u.truename";
        $sql = strtr( $stmt, array(
            "{fields}" => $fields
        ) )." GROUP BY s.uFlowId ORDER BY f.level DESC, etime DESC".( " LIMIT ".$start.", {$limit}" );
        $result = $CNOA_DB->query( $sql );
        while ( $uflow = $CNOA_DB->get_array( $result ) )
        {
            $uflow['status'] = $this->_getStatusText( $uflow['status'] );
            $uflow['posttime'] = formatdate( $uflow['posttime'], "Y-m-d H:i" );
            if ( $uflow['level'] == 0 )
            {
                $uflow['level'] = "<span style=\"color: green\">普通</span>";
            }
            else if ( $uflow['level'] == 1 )
            {
                $uflow['level'] = "<span style=\"color: orange\">重要</span>";
            }
            else if ( $uflow['level'] == 2 )
            {
                $uflow['level'] = "<span style=\"color: red\">非常重要</span>";
            }
            if ( empty( $uflow['face'] ) )
            {
                $uflow['face'] = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
            }
            else
            {
                $faceUrl = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/".$uid."/120x160_".$uflow['face'];
                if ( file_exists( $faceUrl ) )
                {
                    $uflow['face'] = $faceUrl;
                }
                else
                {
                    $uflow['face'] = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
                }
            }
            $data[] = $uflow;
        }
        $sql = strtr( $stmt, array( "{fields}" => "count(DISTINCT f.uFlowId) AS total" ) );
        $total = $CNOA_DB->get_one( $sql );
        ( );
        $response = new stdClass( );
        $response->success = TRUE;
        $response->total = $total['total'];
        $response->data = $data;
        return $response;
    }

    private function _getMyFlowlist( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $start = ( integer )getpar( $_POST, "start" );
        $limit = ( integer )getpar( $_POST, "limit", 15 );
        $search = getpar( $_POST, "search" );
        $where[] = "f.status IN (0, 1, 2, 3, 4, 6) AND f.uid='".$uid."'";
        if ( !empty( $search ) )
        {
            $where[] = "(f.flowName LIKE '%".$search."%' OR f.flowNumber LIKE '%{$search}%')";
        }
        $stmt = "SELECT {fields} FROM ".tname( $this->t_use_flow )." AS f, ".tname( $this->t_set_flow )." AS sf, ".tname( $this->t_use_step )." AS s, ".tname( $this->main_user )." AS u WHERE sf.flowId=f.flowId AND f.uFlowId=s.uFlowId AND f.uid=u.uid AND ".implode( " AND ", $where );
        $data = array( );
        $fields = "f.flowId, f.uFlowId, f.flowName, f.flowNumber, f.status, f.level, f.posttime, s.uStepId, u.face, u.truename";
        $sql = strtr( $stmt, array(
            "{fields}" => $fields
        ) )." GROUP BY s.uFlowId ORDER BY f.level DESC, etime DESC".( " LIMIT ".$start.", {$limit}" );
        $result = $CNOA_DB->query( $sql );
        while ( $uflow = $CNOA_DB->get_array( $result ) )
        {
            $uflow['status'] = $this->_getStatusText( $uflow['status'] );
            $uflow['posttime'] = formatdate( $uflow['posttime'], "Y-m-d H:i" );
            if ( $uflow['level'] == 0 )
            {
                $uflow['level'] = "<span style=\"color: green\">普通</span>";
            }
            else if ( $uflow['level'] == 1 )
            {
                $uflow['level'] = "<span style=\"color: orange\">重要</span>";
            }
            else if ( $uflow['level'] == 2 )
            {
                $uflow['level'] = "<span style=\"color: red\">非常重要</span>";
            }
            if ( empty( $uflow['face'] ) )
            {
                $uflow['face'] = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
            }
            else
            {
                $faceUrl = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/".$uid."/120x160_".$uflow['face'];
                if ( file_exists( $faceUrl ) )
                {
                    $uflow['face'] = $faceUrl;
                }
                else
                {
                    $uflow['face'] = $GLOBALS['CNOA_BASE_URL_FILE']."/common/face/default-face.jpg";
                }
            }
            $data[] = $uflow;
        }
        $sql = strtr( $stmt, array( "{fields}" => "count(DISTINCT f.uFlowId) AS total" ) );
        $total = $CNOA_DB->get_one( $sql );
        ( );
        $response = new stdClass( );
        $response->success = TRUE;
        $response->total = $total['total'];
        $response->data = $data;
        return $response;
    }

    private function _getFlowIdsByUid( $uid )
    {
        global $CNOA_DB;
        $sql = "SELECT DISTINCT uFlowId FROM ".tname( $this->t_use_step ).( " WHERE (`status`=2 OR `status`=4) AND `dealUid`!=0 AND (`uid`='".$uid."' OR `proxyUid`='{$uid}')" );
        $result = $CNOA_DB->query( $sql );
        while ( $step = $CNOA_DB->get_array( $result ) )
        {
            $uFlowIds[] = $step['uFlowId'];
        }
        $sql = "SELECT DISTINCT uFlowId FROM ".tname( $this->t_use_step_huiqian ).( " WHERE `touid`='".$uid."' AND `status`=1" );
        $result = $CNOA_DB->query( $sql );
        while ( $step = $CNOA_DB->get_array( $result ) )
        {
            $uFlowIds[] = $step['uFlowId'];
        }
        return array_unique( $uFlowIds );
    }

    private function _getStatusText( $status )
    {
        switch ( $status )
        {
        case 0 :
            return "未发布";
        case 1 :
            return "办理中";
        case 2 :
            return "已办理";
        case 3 :
            return "已退件";
        case 4 :
            return "已撤销";
        case 5 :
            return "已删除";
        case 5 :
            return "已中止";
        }
        return "";
    }

}

?>
