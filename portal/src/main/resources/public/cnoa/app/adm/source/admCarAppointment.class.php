<?php
//decode by qq2859470

class admCarAppointment extends model
{

    private $table = "adm_car_apply";
    private $table_info = "adm_car_info";
    private $rows = 15;

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        if ( $task == "loadPage" )
        {
            global $CNOA_CONTROLLER;
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/car/appointment.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            exit( );
        }
        if ( $task == "list" )
        {
            $this->_list( );
        }
        else if ( $task == "apply" )
        {
            $this->_apply( );
        }
        else if ( $task == "info" )
        {
            $id = getpar( $_GET, "id", 0 );
            app::loadapp( "adm", "carInfo" )->api_admCarInfoShow( $id );
        }
    }

    private function _list( )
    {
        global $CNOA_DB;
        $day = getpar( $_POST, "day", 0 );
        $start = getpar( $_POST, "start", 0 );
        $todayStart = strtotime( date( "Y-m-d", $GLOBALS['CNOA_TIMESTAMP'] )." 00:00:00" ) + 86400 * $day;
        $todayEnd = strtotime( date( "Y-m-d", $GLOBALS['CNOA_TIMESTAMP'] )." 23:59:59" ) + 86400 * $day;
        $CNOA_DB->db_update( array( "status" => 5 ), $this->table, "WHERE `starttime`<=".$GLOBALS['CNOA_TIMESTAMP']." AND `endtime`>={$GLOBALS['CNOA_TIMESTAMP']} AND `status`=3" );
        $dblist = $CNOA_DB->db_select( "*", $this->table, "WHERE (`starttime`<".$todayEnd." AND `endtime`>{$todayStart} AND `realstarttime` = 0) OR (`realstarttime`!=0 AND `realstarttime`<{$todayEnd} AND (`realendtime`>{$todayStart} OR realendtime = 0)) " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $useList = array( );
        foreach ( $dblist as $k => $v )
        {
            if ( empty( $v['realstarttime'] ) )
            {
                $startTime = $v['starttime'];
                $endTime = $v['endtime'];
                $status = $v['status'];
            }
            else
            {
                $startTime = $v['realstarttime'];
                $endTime = $v['realendtime'] == 0 ? $v['endtime'] : $v['realendtime'];
                $status = 7;
            }
            $useList[$v['cid']][] = $this->__formatTime( $todayStart, $startTime, $todayEnd, $endTime, $status );
        }
        $data = array( );
        foreach ( $useList as $k => $v )
        {
            $data[$k] = array(
                "cid" => $k,
                "dt" => $this->__array_sum( $v )
            );
        }
        $allCar = $CNOA_DB->db_select( array( "carnumber", "id" ), $this->table_info, "ORDER BY `id` DESC LIMIT ".$start.",{$this->rows}" );
        if ( !is_array( $allCar ) )
        {
            $allCar = array( );
        }
        $carData = array( );
        foreach ( $allCar as $car )
        {
            if ( array_key_exists( $car['id'], $data ) )
            {
                $data[$car['id']]['carnumber'] = $car['carnumber'];
                $carData[] = $data[$car['id']];
            }
            else
            {
                $carData[] = array(
                    "cid" => $car['id'],
                    "carnumber" => $car['carnumber'],
                    "dt" => array( )
                );
            }
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = $CNOA_DB->db_getcount( $this->table_info, "WHERE 1" );
        $dataStore->date = date( "Y-m-d", $GLOBALS['CNOA_TIMESTAMP'] + 86400 * $day );
        $dataStore->data = $carData;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _apply( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        $cid = getpar( $_GET, "cid", 0 );
        $day = getpar( $_GET, "day", 0 );
        $todayStart = strtotime( date( "Y-m-d", $GLOBALS['CNOA_TIMESTAMP'] )." 00:00:00" ) + 86400 * $day;
        $todayEnd = strtotime( date( "Y-m-d", $GLOBALS['CNOA_TIMESTAMP'] )." 23:59:59" ) + 86400 * $day;
        $dblist = $CNOA_DB->db_select( "*", $this->table, "WHERE `starttime`<".$todayEnd." AND `endtime`>{$todayStart} AND `cid`={$cid} AND `status`!=0" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['carnumber'] = $this->__carNumber( $v['cid'] );
            $dblist[$k]['starttime'] = $this->__formatDate( $v['starttime'] );
            $dblist[$k]['endtime'] = $this->__formatDate( $v['endtime'] );
            $dblist[$k]['department'] = $this->__getDepartName( $v['deptID'] );
            $dblist[$k]['status'] = $this->__status( $v['status'] );
            $dblist[$k]['tname'] = $this->__takeUserName( $v['tid'] );
            $dblist[$k]['applyname'] = $this->__takeUserName( $v['applyUid'] );
        }
        $GLOBALS['GLOBALS']['adm']['car']['appointment'] = $dblist;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/car/appointment_list.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function __takeUserName( $id )
    {
        $data = app::loadapp( "main", "user" )->api_getUserDataByUid( $id );
        return $data['truename'];
    }

    private function __carNumber( $id )
    {
        global $CNOA_DB;
        $data = $CNOA_DB->db_getone( array( "carnumber" ), $this->table_info, "WHERE `id`=".$id );
        return $data['carnumber'];
    }

    private function __formatTime( $todayStart, $starttime, $todayEnd, $endtime, $status )
    {
        if ( 0 <= $todayStart - $starttime )
        {
            $start = 0;
        }
        else
        {
            $time = date( "H:i", $starttime );
            $time = explode( ":", $time );
            $start = intval( $time[0] ) * 2 + ( $time[1] == 30 ? 1 : 0 );
        }
        if ( 0 <= $endtime - $todayEnd )
        {
            $end = 47;
        }
        else
        {
            $time = date( "H:i", $endtime );
            $time = explode( ":", $time );
            $end = intval( $time[0] ) * 2 + ( $time[1] == 30 ? 1 : 0 ) - 1;
        }
        $i = 0;
        for ( ; $i < 48; ++$i )
        {
            if ( $start <= $i && $i <= $end )
            {
                $data[$i] = $status;
            }
            else
            {
                $data[$i] = 0;
            }
        }
        return $data;
    }

    private function __array_sum( $arr )
    {
        $temp = array( );
        foreach ( $arr as $k => $v )
        {
            foreach ( $v as $k2 => $v2 )
            {
                if ( !isset( $temp[$k2] ) )
                {
                    $temp[$k2] = $v2;
                }
                else
                {
                    $temp[$k2] = $temp[$k2] + $v2;
                }
            }
        }
        foreach ( $temp as $k3 => $v3 )
        {
            $data["t".$k3] = $v3;
        }
        return $data;
    }

    private function __getDepartName( $deptID )
    {
        $depList = app::loadapp( "main", "struct" )->api_getArrayList( );
        $deptName = $depList[$deptID];
        return $deptName;
    }

    private function __formatDate( $value )
    {
        if ( $value == 0 )
        {
            return "";
        }
        return date( "Y-m-d H:i", $value );
    }

    private function __status( $value )
    {
        if ( $value == 0 )
        {
            return lang( "idle" );
        }
        if ( $value == 1 )
        {
            return lang( "unPass" );
        }
        if ( $value == 2 )
        {
            return lang( "Pending" );
        }
        if ( $value == 3 )
        {
            return lang( "pass" );
        }
        if ( $value == 5 )
        {
            return lang( "inUse" );
        }
    }

}

?>
