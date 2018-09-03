<?php
//decode by qq2859470

class admCarInfo extends model
{

    private $table = "adm_car_info";
    private $table_transport = "adm_car_transport";
    private $table_apply = "adm_car_apply";
    private $table_photos = "adm_car_photo";
    private $table_check = "adm_car_check";
    private $table_driver = "adm_car_driver";
    private $table_sort = "adm_car_sort";
    private $rows = 15;

    const DROPDOWN_TYPE_CARSORT = 0;

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        if ( $task == "loadPage" )
        {
            $this->_loadPage( );
        }
        else if ( $task == "add" )
        {
            $this->_add( );
        }
        else if ( $task == "list" )
        {
            $this->_list( );
        }
        else if ( $task == "editList" )
        {
            $this->_editList( );
        }
        else if ( $task == "edit" )
        {
            $this->_edit( );
        }
        else if ( $task == "delete" )
        {
            $this->_delete( );
        }
        else if ( $task == "pic" )
        {
            global $CNOA_DB;
            $id = getpar( $_GET, "id", 0 );
            $pic = $CNOA_DB->db_getfield( "pic", $this->table, "WHERE `id`='".$id."'" );
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
        else if ( $task == "getAllUserListsInPermitDeptTree" )
        {
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $userList );
            exit( );
        }
        else if ( $task == "view" )
        {
            $this->_view( );
        }
        else if ( $task == "driverList" )
        {
            $this->_driverList( );
        }
        else if ( $task == "update2" )
        {
            $this->_update2( );
        }
        else if ( $task == "delete2" )
        {
            $this->_delete2( );
        }
        else if ( $task == "getcarnumber" )
        {
            app::loadapp( "adm", "carApply" )->__getcarnumber( );
        }
        else if ( $task == "getSortList" )
        {
            $this->_getSortList( );
        }
        else if ( $task == "getCarType" )
        {
            $this->_getCarType( );
        }
        else if ( $task == "getPhotoList" )
        {
            $this->_getPhotoList( );
        }
        else if ( $task == "upPhoto" )
        {
            $this->_upPhoto( );
        }
        else if ( $task == "getOnePhoto" )
        {
            $this->_getOnePhoto( );
        }
        else if ( $task == "delPhoto" )
        {
            $this->_delPhoto( );
        }
        else if ( $task == "comboStore" )
        {
            $this->_comboStore( );
        }
    }

    private function _view( )
    {
        $cid = getpar( $_GET, "id", 0 );
        $this->api_admCarInfoShow( $cid );
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/car/info.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _add( )
    {
        global $CNOA_DB;
        $data['carnumber'] = getpar( $_POST, "carnumber" );
        $data['driver'] = getpar( $_POST, "driver" );
        $data['actionnumber'] = getpar( $_POST, "actionnumber" );
        $data['date'] = strtotime( getpar( $_POST, "date" ) );
        $data['companynumber'] = getpar( $_POST, "companynumber" );
        $data['carSort'] = getpar( $_POST, "carSort" );
        $data['dept'] = getpar( $_POST, "dept" );
        $data['price'] = getpar( $_POST, "price" );
        $data['status'] = getpar( $_POST, "status" );
        $data['notes'] = getpar( $_POST, "notes" );
        $data['other'] = getpar( $_POST, "other", "" );
        $data_transports = getpar( $_POST, "transports" );
        ( );
        $fs = new fs( );
        $filesUpload = getpar( $_POST, "filesUpload", array( ) );
        $attch = $fs->add( $filesUpload, 1 );
        $data['attach'] = json_encode( $attch );
        $cid = $CNOA_DB->db_insert( $data, $this->table );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 137, $data['carnumber'], lang( "carInformation" ) );
        $transports = explode( ",", $data_transports );
        if ( !is_array( $transports ) )
        {
            $transports = array( );
        }
        foreach ( $transports as $v )
        {
            $CNOA_DB->db_insert( array(
                "cid" => $cid,
                "tid" => $v
            ), $this->table_transport );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function __mkName( )
    {
        return $GLOBALS['CNOA_TIMESTAMP'].string::rands( 4, 4 );
    }

    private function _list( )
    {
        global $CNOA_DB;
        $start = getpar( $_POST, "start", 0 );
        $carSort = getpar( $_POST, "carSort", "" );
        $carnumber = getpar( $_POST, "carnumber", "" );
        $WHERE[] = "WHERE 1";
        if ( $carSort )
        {
        }
        if ( $carnumber )
        {
        }
        $WHERE[$WHERE = implode( " AND ", $WHERE );
        $dblist = $CNOA_DB->db_select( "*", $this->table, "{$WHERE} ORDER BY `id` DESC LIMIT {$start}, {$this->rows}" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $v )
        {
            $v['date'] = $this->__formatDate( $v['date'] );
            $v['carSort'] = $CNOA_DB->db_getfield( "value", $this->table_sort, "WHERE tid = ".$v['carSort'] );
            $v['status'] = $this->__formatStatus( $v['status'] );
            $v['dept'] = !empty( $v['dept'] ) ? app::loadapp( "main", "struct" )->api_getNameById( $v['dept'] ) : " ";
            $data[] = $v;
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        $dataStore->total = $CNOA_DB->db_getcount( $this->table, $WHERE );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getSortList( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "value", "tid" ), $this->table_sort, "WHERE type = 0" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $list = array( );
        foreach ( $dblist as $v )
        {
            $r = array( );
            $r['text'] = $v['value'];
            $r['value'] = $v['value'];
            $r['tid'] = $v['tid'];
            $r['iconCls'] = "icon-style-page-key";
            $r['leaf'] = TRUE;
            $r['href'] = "javascript:void(0);";
            $list[] = $r;
        }
        echo json_encode( $list );
        exit( );
    }

    private function _getCarType( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "tid", "value" ), $this->table_sort, "WHERE type = 0" );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _editList( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", 0 );
        $data = $CNOA_DB->db_getone( "*", $this->table, "WHERE `id`='".$id."'" );
        ( );
        $fs = new fs( );
        $data['attach'] = $fs->getEditList( json_decode( $data['attach'], TRUE ) );
        $data['date'] = $this->__formatDate( $data['date'] );
        $dblist = $CNOA_DB->db_select( array( "tid" ), $this->table_transport, "WHERE `cid`='".$id."'" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $v )
        {
            $tid .= $v['tid'].",";
            $name .= $this->__takeUserName( $v['tid'] ).", ";
        }
        $data['transports'] = substr( $tid, 0, strlen( $tid ) - 1 );
        $data['transport'] = $name;
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function __takeUserName( $id )
    {
        $data = app::loadapp( "main", "user" )->api_getUserDataByUid( $id );
        return $data['truename'];
    }

    private function _edit( )
    {
        global $CNOA_DB;
        $id = getpar( $_GET, "id", 0 );
        $data['carnumber'] = getpar( $_POST, "carnumber" );
        $data['driver'] = getpar( $_POST, "driver" );
        $data['actionnumber'] = getpar( $_POST, "actionnumber" );
        $data['date'] = strtotime( getpar( $_POST, "date" ) );
        $data['companynumber'] = getpar( $_POST, "companynumber" );
        $data['carSort'] = getpar( $_POST, "carSort" );
        $data['dept'] = getpar( $_POST, "dept" );
        $data['price'] = getpar( $_POST, "price" );
        $data['status'] = getpar( $_POST, "status" );
        $data['notes'] = getpar( $_POST, "notes" );
        $data['other'] = getpar( $_POST, "other" );
        $data_transports = getpar( $_POST, "transports" );
        ( );
        $fs = new fs( );
        $filesUpload = getpar( $_POST, "filesUpload", array( ) );
        $infoO = $CNOA_DB->db_getone( "*", $this->table, "WHERE `id`='".$id."'" );
        $attch = $fs->edit( $filesUpload, json_decode( $infoO['attach'], FALSE ), 1 );
        $data['attach'] = json_encode( $attch );
        $CNOA_DB->db_update( $data, $this->table, "WHERE `id`='".$id."'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 137, $data['carnumber'], lang( "carInformation" ) );
        $transports = explode( ",", $data_transports );
        if ( !is_array( $transports ) )
        {
            $transports = array( );
        }
        $CNOA_DB->db_delete( $this->table_transport, "WHERE `cid`='".$id."'" );
        foreach ( $transports as $v )
        {
            $CNOA_DB->db_insert( array(
                "cid" => $id,
                "tid" => $v
            ), $this->table_transport );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _delete( )
    {
        global $CNOA_DB;
        $ids = getpar( $_POST, "ids" );
        if ( $ids )
        {
            $ids = explode( ",", substr( $ids, 0, -1 ) );
            if ( is_array( $ids ) )
            {
                foreach ( $ids as $v )
                {
                    $DB = $CNOA_DB->db_select( array( "noticeid_t", "todoid_t" ), $this->table_apply, "WHERE `cid`=".$v );
                    $DB2 = $CNOA_DB->db_select( array( "noticeid_c", "todoid_c" ), $this->table_check, "WHERE `cid`=".$v );
                    if ( !is_array( $DB ) )
                    {
                        $DB = array( );
                    }
                    if ( !is_array( $DB2 ) )
                    {
                        $DB2 = array( );
                    }
                    $notice = array( );
                    $todo = array( );
                    foreach ( $DB as $k => $v1 )
                    {
                        $notice[] = $v1['noticeid_t'];
                        $todo[] = $v1['todoid_t'];
                    }
                    foreach ( $DB2 as $k => $v2 )
                    {
                        $notice[] = $v2['noticeid_c'];
                        $todo[] = $v2['todoid_c'];
                    }
                    notice::deletenotice( $notice, $todo );
                    $temp = $CNOA_DB->db_getone( array( "pic", "carnumber", "attach" ), $this->table, "WHERE `id`='".$v."'" );
                    ( );
                    $fs = new fs( );
                    $fs->deleteFile( json_decode( $temp['attach'], TRUE ) );
                    @unlink( CNOA_PATH_FILE."/common/car/".$temp['pic'] );
                    $CNOA_DB->db_delete( $this->table, "WHERE `id`=".$v );
                    app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 137, $temp['carnumber'], lang( "carInformation" ) );
                    $CNOA_DB->db_delete( $this->table_transport, "WHERE `cid`=".$v );
                    $data = notice::deletenoticebysourceid( 11, $this->__getCarApply( $v ) );
                    $CNOA_DB->db_delete( $this->table_apply, "WHERE `cid`=".$v );
                    $CNOA_DB->db_delete( $this->table_check, "WHERE `cid`=".$v );
                }
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function __getCarApply( $id )
    {
        global $CNOA_DB;
        $data['id'] = "";
        $data = $CNOA_DB->db_getone( array( "id" ), $this->table_apply, "WHERE `cid`=".$id );
        return $data['id'];
    }

    private function __formatDate( $value )
    {
        if ( $value == "0" )
        {
            return "";
        }
        return date( "Y-m-d", $value );
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

    private function __takeTransports( $cid )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "tid" ), $this->table_transport, "WHERE `cid`=".$cid );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $temp = "";
        foreach ( $dblist as $v )
        {
            $temp .= $this->__takeUserName( $v['tid'] ).", ";
        }
        return $temp;
    }

    public function api_admCarInfoShow( $cid )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        $dblist = $CNOA_DB->db_getone( "*", $this->table, "WHERE `id`=".$cid );
        ( );
        $fs = new fs( );
        $dblist['attach'] = json_decode( $dblist['attach'], TRUE );
        $dblist['attachCount'] = !$dblist['attach'] ? 0 : count( $dblist['attach'] );
        $dblist['attach'] = $fs->getDownLoadItems4normal( $dblist['attach'], TRUE );
        $dblist['driver'] = $CNOA_DB->db_getfield( "name", $this->table_driver, "WHERE `cid`='".$dblist['id']."'" );
        $dblist['date'] = $this->__formatDate( $dblist['date'] );
        $dblist['status'] = $this->__formatStatus( $dblist['status'] );
        $dblist['carSort'] = $CNOA_DB->db_getfield( "value", $this->table_sort, "WHERE tid = ".$dblist['carSort'] );
        $dblist['dept'] = !empty( $dblist['dept'] ) ? app::loadapp( "main", "struct" )->api_getNameById( $dblist['dept'] ) : " ";
        $dblist['transports'] = $this->__takeTransports( $cid );
        $GLOBALS['GLOBALS']['adm']['car']['info'] = $dblist;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/car/info_list.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _driverList( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->table_driver, "ORDER BY `did` DESC" );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _update2( )
    {
        global $CNOA_DB;
        $name = getpar( $_POST, "name", "" );
        $carnumber = getpar( $_POST, "carnumber", "" );
        $info = $CNOA_DB->db_getfield( "did", $this->table_driver, "WHERE `name`= '".$name."' AND `carnumber`='{$carnumber}' " );
        if ( empty( $info['did'] ) )
        {
            $CNOA_DB->db_insert( array(
                "name" => $name,
                "did" => $did,
                "carnumber" => $carnumber
            ), $this->table_driver );
        }
        else
        {
            $CNOA_DB->db_update( array(
                "name" => $name,
                "carnumber" => $carnumber
            ), $this->table_driver, "WHERE `did`=>".$info['did']." " );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _delete2( )
    {
        global $CNOA_DB;
        $dids = getpar( $_POST, "dids", 0 );
        $dids = substr( $dids, 0, -1 );
        $didArr = explode( ",", $dids );
        foreach ( $didArr as $v )
        {
            $CNOA_DB->db_delete( $this->table_driver, "WHERE `did`='".$v."'" );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function __getdriver( )
    {
        $this->_driverList( );
    }

    public function api_getCarInfo( $cidArr )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->table, "WHERE `id` IN (".implode( ",", $cidArr ).")" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $v )
        {
            $data[$v['id']] = $v;
        }
        return $data;
    }

    private function _getPhotoList( )
    {
        global $CNOA_DB;
        $cid = getpar( $_GET, "id", "" );
        if ( empty( $cid ) )
        {
            return FALSE;
        }
        $oldImage = $CNOA_DB->db_getfield( "pic", $this->table, "WHERE `id`=".$cid );
        if ( $oldImage )
        {
            $tempData['value'] = $oldImage;
            $tempData['date'] = time( );
            $tempData['cid'] = $cid;
            $result = $CNOA_DB->db_insert( $tempData, $this->table_photos );
            if ( 0 < $result )
            {
                $CNOA_DB->db_update( array( "pic" => "" ), $this->table, "WHERE `id`=".$cid );
            }
        }
        $list = $CNOA_DB->db_select( "*", $this->table_photos, "WHERE `cid`=".$cid." ORDER BY `date` DESC" );
        if ( !is_array( $list ) )
        {
            $list = array( );
        }
        $data = array( );
        foreach ( $list as $key => $value )
        {
            $temp = array( );
            $temp['id'] = $value['id'];
            $temp['value'] = $value['value'];
            $temp['date'] = date( "Y-m-d", $value['date'] );
            $data[] = $temp;
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _upPhoto( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $cid = getpar( $_POST, "id", "" );
        if ( empty( $cid ) )
        {
            return FALSE;
        }
        set_time_limit( 0 );
        $img_ext = strtolower( strrchr( $_FILES['photo']['name'], "." ) );
        $img_name = $GLOBALS['CNOA_TIMESTAMP']."_".md5( $GLOBALS['CNOA_TIMESTAMP'] ).$img_ext;
        $img_dst = CNOA_PATH_FILE."/common/car/".$img_name;
        $img_url = $GLOBALS['CNOA_BASE_URL_FILE']."/common/car/".$img_name;
        $extArray = array( ".jpg", ".gif", ".png" );
        if ( !in_array( $img_ext, $extArray ) )
        {
            msg::callback( FALSE, "只能上传jpg、png、gif格式" );
        }
        if ( cnoa_move_uploaded_file( $_FILES['photo']['tmp_name'], $img_dst."_tmp" ) )
        {
            ( );
            $picture = new picture( );
            $picture->setSrcImg( $img_dst."_tmp" );
            $picture->setDstImg( $img_dst );
            $picture->createImg( 800, 600 );
            @unlink( $img_dst."_tmp" );
            $data['cid'] = $cid;
            $data['value'] = $img_name;
            $data['date'] = $GLOBALS['CNOA_TIMESTAMP'];
            $insert = $CNOA_DB->db_insert( $data, $this->table_photos );
            msg::callback( TRUE, $img_url );
            exit( );
        }
        msg::callback( FALSE, lang( "uploadFail" ) );
        exit( );
    }

    private function _getOnePhoto( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id" );
        if ( empty( $id ) )
        {
            return FALSE;
        }
        $img_name = $CNOA_DB->db_getfield( "value", $this->table_photos, "WHERE `id`=".$id );
        $img_url = $GLOBALS['CNOA_BASE_URL_FILE']."/common/car/".$img_name;
        msg::callback( TRUE, $img_url );
        exit( );
    }

    private function _delPhoto( )
    {
        global $CNOA_DB;
        $ids = getpar( $_POST, "ids" );
        if ( empty( $ids ) )
        {
            msg::callback( FALSE, lang( "delFail" ) );
        }
        if ( isinformat( $ids ) )
        {
            $list = $CNOA_DB->db_select( array( "value" ), $this->table_photos, "WHERE `id` IN (".$ids.")" );
            $CNOA_DB->db_delete( $this->table_photos, "WHERE `id` IN (".$ids.")" );
            if ( !is_array( $list ) )
            {
                $list = array( );
            }
            foreach ( $list as $v )
            {
                $img_name = $v['value'];
                if ( !empty( $img_name ) )
                {
                    $img_dst = CNOA_PATH_FILE."/common/car/".$img_name;
                    @unlink( $img_dst );
                }
            }
            msg::callback( TRUE, lang( "delSuccess" ) );
        }
        else
        {
            msg::callback( FALSE, lang( "delFail" ) );
        }
    }

    private function _comboStore( )
    {
        global $CNOA_DB;
        $key = getpar( $_GET, "type", "" );
        $dropType = $this->getDropType( $key );
        $this->_comboDrop( $dropType );
    }

    private function getDropType( $key )
    {
        switch ( $key )
        {
        case "carSort" :
            return self::DROPDOWN_TYPE_CARSORT;
        }
    }

    private function _comboDrop( $type )
    {
        global $CNOA_DB;
        $data = $CNOA_DB->db_select( array( "tid", "value" ), $this->table_sort, "where `type`='".$type."'" );
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
        exit( );
    }

}

?>
