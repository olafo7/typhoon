<?php
//decode by qq2859470

class admCarRecord extends model
{

    private $table_info = "adm_car_info";
    private $table_check = "adm_car_check";
    private $table_apply = "adm_car_apply";
    private $table_photos = "adm_car_photo";
    private $table_driver = "adm_car_driver";
    private $rows = 15;

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        if ( $task == "checklist" )
        {
            $from = getpar( $_GET, "from", "" );
            if ( $from == "loadPage" )
            {
                global $CNOA_CONTROLLER;
                $cid = getpar( $_GET, "cid", 0 );
                $GLOBALS['GLOBALS']['adm']['car']['cid'] = $cid;
                $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/car/record_list.htm";
                $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
                exit( );
            }
        }
        else if ( $task == "list" )
        {
            $this->_list( );
        }
        else if ( $task == "pic" )
        {
            global $CNOA_DB;
            $id = getpar( $_GET, "id", 0 );
            $pic = $CNOA_DB->db_getfield( "pic", $this->table_info, "WHERE `id`='".$id."'" );
            $tmp = $CNOA_DB->db_select( array( "value" ), $this->table_photos, "WHERE `cid`='".$id."'" );
            if ( !is_array( $tmp ) )
            {
                $tmp = array( );
            }
            if ( !empty( $tmp ) && !empty( $pic ) )
            {
                if ( $tmp )
                {
                    foreach ( $tmp as $value )
                    {
                        echo "<img src='".$GLOBALS['URL_FILE']."/common/car/".$value['value']."'>";
                        echo "<br/>";
                    }
                }
                if ( $pic )
                {
                    echo "<img src='".$GLOBALS['URL_FILE']."/common/car/".$pic."'>";
                    echo "<br/>";
                }
            }
            else
            {
                echo lang( "noPictureCar" );
            }
        }
        else if ( $task == "recordlist" )
        {
            $this->_recordlist( );
        }
        else if ( $task == "view" )
        {
            $this->_view( );
        }
        else if ( $task == "applyinfo" )
        {
            global $CNOA_DB;
            global $CNOA_CONTROLLER;
            $aid = getpar( $_GET, "aid", 0 );
            $dblist = $CNOA_DB->db_getone( "*", $this->table_apply, "WHERE `id`=".$aid );
            $info = $this->__getCarInfo( $dblist['cid'] );
            if ( !empty( $dblist['driver'] ) || $dblist['uFlowId'] == 0 )
            {
                $dblist['driver'] = $CNOA_DB->db_getfield( "name", $this->table_driver, "WHERE `did`=".$dblist['driver'] );
            }
            $dblist['carnumber'] = $info['carnumber'];
            $dblist['notes'] = $info['notes'];
            $dblist['applyname'] = $this->__takeUserName( $dblist['applyUid'] );
            $dblist['transport'] = $this->__takeUserName( $dblist['tid'] );
            $dblist['starttime'] = $this->__formatDate( $dblist['starttime'] );
            $dblist['endtime'] = $this->__formatDate( $dblist['endtime'] );
            $dblist['realstarttime'] = $this->__formatDate( $dblist['realstarttime'] );
            $dblist['realendtime'] = $this->__formatDate( $dblist['realendtime'] );
            $dblist['checkname'] = $this->__takeUserName( $dblist['checkid'] );
            $dblist['department'] = $dblist['deptID'] == "" ? "" : $this->__getDepartName( $dblist['deptID'] );
            $GLOBALS['GLOBALS']['adm']['car']['apply'] = $dblist;
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/car/applydetails.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            exit( );
        }
    }

    private function __getDepartName( $deptID )
    {
        $depList = app::loadapp( "main", "struct" )->api_getArrayList( );
        $deptName = $depList[$deptID];
        return $deptName;
    }

    private function __getCarInfo( $value )
    {
        global $CNOA_DB;
        $data = $CNOA_DB->db_getone( "*", $this->table_info, "WHERE `id`=".$value );
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        return $data;
    }

    private function _view( )
    {
        $cid = getpar( $_GET, "id", 0 );
        app::loadapp( "adm", "carInfo" )->api_admCarInfoShow( $cid );
    }

    private function _list( )
    {
        global $CNOA_DB;
        $start = getpar( $_POST, "start", 0 );
        $dblist = $CNOA_DB->db_select( "*", $this->table_info, "ORDER BY `id` DESC LIMIT ".$start.", {$this->rows}" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['status'] = $this->__formatStatus( $v['status'] );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = $CNOA_DB->db_getcount( $this->table_info );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _recordlist( )
    {
        global $CNOA_DB;
        $cid = getpar( $_GET, "cid", 0 );
        $start = getpar( $_POST, "start", 0 );
        $dblist = $CNOA_DB->db_select( "*", $this->table_apply, "WHERE `cid`=".$cid." ORDER BY `id` DESC LIMIT {$start}, {$this->rows}" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $k => $v )
        {
            $date = $this->__takeUserName( $v['applyUid'] ).", 预约时间".$this->__formatDate( $v['starttime'] )." ~ ".$this->__formatDate( $v['endtime'] ).",  调度员: ".$this->__takeUserName( $v['tid'] ).", 用车人：".$v['caruser'].", 目的地：".$v['destination'];
            $data[] = $this->__takeChecklist( $v['id'], $date );
        }
        $final = array( );
        foreach ( $data as $k => $v )
        {
            foreach ( $v as $value )
            {
                $final[] = $value;
            }
        }
        $car = $CNOA_DB->db_getone( array( "carnumber" ), $this->table_info, "WHERE `id`=".$cid );
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = $CNOA_DB->db_getcount( $this->table_apply );
        $dataStore->carnumber = $car['carnumber'];
        $dataStore->data = $final;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function __formatStatus( $value )
    {
        switch ( $value )
        {
        case 4 :
            return lang( "available" );
        case 3 :
            return lang( "damage" );
        case 2 :
            return lang( "maintenance" );
        case 1 :
            return lang( "scrap" );
        }
    }

    private function __takeChecklist( $aid, $date )
    {
        global $CNOA_DB;
        $data = $CNOA_DB->db_select( "*", $this->table_check, "WHERE `aid`=".$aid." ORDER BY `id` ASC" );
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        $i = 1;
        foreach ( $data as $k => $v )
        {
            $data[$k]['step'] = "第".$i."步";
            $data[$k]['date'] = $date;
            $data[$k]['prename'] = $this->__takeUserName( $v['preuid'] );
            $data[$k]['checkname'] = $this->__takeUserName( $v['checkid'] );
            $data[$k]['status'] = $this->__formatCheckStatus( $v['status'] );
            $data[$k]['time'] = $this->__formatDate( $v['time'] );
            ++$i;
        }
        $data[$i - 1]['unpassnotes'] = "<a href='javascript:void(0)' onclick='CNOA_adm_car_recordlist.applyinfo(".$aid.")'>".lang( "viewApplyInfor" )."</a>";
        $data[$i - 1]['date'] = $date;
        return $data;
    }

    private function __takeUserName( $id )
    {
        $data = app::loadapp( "main", "user" )->api_getUserDataByUid( $id );
        return $data['truename'];
    }

    private function __formatDate( $value )
    {
        if ( $value == 0 )
        {
            return "";
        }
        return date( "Y-m-d H:i", $value );
    }

    private function __formatCheckStatus( $value )
    {
        switch ( $value )
        {
        case 4 :
            return lang( "superiorApp" );
        case 3 :
            return lang( "appCancel" );
        case 2 :
            return "<span class='cnoa_color_green'>".lang( "approvalThrough" )."</span>";
        case 1 :
            return "<span class='cnoa_color_red'>".lang( "approvalNotThrough" )."</span>";
        }
        return lang( "pendingTrial" );
    }

    private function __formatApplyInfo( $id )
    {
        global $CNOA_DB;
        $data = $CNOA_DB->db_getone( "*", $this->table_apply, "WHERE `id`=".$id );
        return $data;
    }

}

?>
