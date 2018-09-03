<?php
//decode by qq2859470

class admRecord extends model
{

    private $tableRecord = "adm_articles_record";
    private $tableProduct = "adm_articles_product";
    private $tableRegister = "adm_articles_register";
    private $rows = 15;

    public function actionAdd( )
    {
        global $CNOA_CONTROLLER;
        global $CNOA_DB;
        $task = getpar( $_GET, "task" );
        if ( $task == "loadPage" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/record/record_add.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            exit( );
        }
        if ( $task == "list" )
        {
            $this->_addList( );
        }
        else if ( $task == "getAllUserListsInPermitDeptTree" )
        {
            $this->__getAllUserListsInPermitDeptTree( );
        }
        else if ( $task == "delete" )
        {
            $ids = getpar( $_POST, "ids", "" );
            $ids = substr( $ids, 0, -1 );
            $idArr = explode( ",", $ids );
            foreach ( $idArr as $v )
            {
                $CNOA_DB->db_delete( $this->tableRecord, "WHERE `id` = '".$v."' " );
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 129 );
            msg::callback( TRUE, lang( "successopt" ) );
        }
        else if ( $task == "exportExcel" )
        {
            $this->_exportExcel( );
        }
    }

    public function actionExport( )
    {
        global $CNOA_CONTROLLER;
        $task = getpar( $_GET, "task", "" );
        if ( $task == "loadPage" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/record/record_export.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        }
        else if ( $task == "exportExcel" )
        {
            $this->_exportExcel( );
        }
    }

    public function actionGet( )
    {
        global $CNOA_CONTROLLER;
        global $CNOA_DB;
        $task = getpar( $_GET, "task" );
        if ( $task == "loadPage" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/record/record_get.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            exit( );
        }
        if ( $task == "list" )
        {
            $this->_getList( );
        }
        else if ( $task == "getAllUserListsInPermitDeptTree" )
        {
            $this->__getAllUserListsInPermitDeptTree( );
        }
        else if ( $task == "delete" )
        {
            $ids = getpar( $_POST, "ids", "" );
            $ids = substr( $ids, 0, -1 );
            $idArr = explode( ",", $ids );
            foreach ( $idArr as $v )
            {
                $CNOA_DB->db_delete( $this->tableRecord, "WHERE `id` = '".$v."' " );
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 131 );
            msg::callback( TRUE, lang( "successopt" ) );
        }
    }

    public function actionBorrow( )
    {
        global $CNOA_CONTROLLER;
        global $CNOA_DB;
        $task = getpar( $_GET, "task" );
        if ( $task == "loadPage" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/record/record_borrow.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            exit( );
        }
        if ( $task == "list" )
        {
            $this->_borrowList( );
        }
        else if ( $task == "getAllUserListsInPermitDeptTree" )
        {
            $this->__getAllUserListsInPermitDeptTree( );
        }
        else if ( $task == "delete" )
        {
            $ids = getpar( $_POST, "ids", "" );
            $ids = substr( $ids, 0, -1 );
            $idArr = explode( ",", $ids );
            foreach ( $idArr as $v )
            {
                $CNOA_DB->db_delete( $this->tableRecord, "WHERE `id` = '".$v."' " );
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 132 );
            msg::callback( TRUE, lang( "successopt" ) );
        }
    }

    public function actionDrop( )
    {
        global $CNOA_CONTROLLER;
        global $CNOA_DB;
        $task = getpar( $_GET, "task" );
        if ( $task == "loadPage" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/record/record_drop.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            exit( );
        }
        if ( $task == "list" )
        {
            $this->_dropList( );
        }
        else if ( $task == "getAllUserListsInPermitDeptTree" )
        {
            $this->__getAllUserListsInPermitDeptTree( );
        }
        else if ( $task == "delete" )
        {
            $ids = getpar( $_POST, "ids", "" );
            $ids = substr( $ids, 0, -1 );
            $idArr = explode( ",", $ids );
            foreach ( $idArr as $v )
            {
                $CNOA_DB->db_delete( $this->tableRecord, "WHERE `id` = '".$v."' " );
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 130 );
            msg::callback( TRUE, lang( "successopt" ) );
        }
    }

    public function actionRepair( )
    {
        global $CNOA_CONTROLLER;
        global $CNOA_DB;
        $task = getpar( $_GET, "task" );
        if ( $task == "loadPage" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/record/record_repair.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            exit( );
        }
        if ( $task == "list" )
        {
            $this->_repairList( );
        }
        else if ( $task == "getAllUserListsInPermitDeptTree" )
        {
            $this->__getAllUserListsInPermitDeptTree( );
        }
        else if ( $task == "delete" )
        {
            $ids = getpar( $_POST, "ids", "" );
            $ids = substr( $ids, 0, -1 );
            $idArr = explode( ",", $ids );
            foreach ( $idArr as $v )
            {
                $CNOA_DB->db_delete( $this->tableRecord, "WHERE `id` = '".$v."' " );
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 133 );
            msg::callback( TRUE, lang( "successopt" ) );
        }
    }

    public function actionDelete( )
    {
        global $CNOA_CONTROLLER;
        global $CNOA_DB;
        $task = getpar( $_GET, "task" );
        if ( $task == "loadPage" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/record/record_delete.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            exit( );
        }
        if ( $task == "list" )
        {
            $this->_deleteList( );
        }
        else if ( $task == "getAllUserListsInPermitDeptTree" )
        {
            $this->__getAllUserListsInPermitDeptTree( );
        }
        else if ( $task == "delete" )
        {
            $ids = getpar( $_POST, "ids", "" );
            $ids = substr( $ids, 0, -1 );
            $idArr = explode( ",", $ids );
            foreach ( $idArr as $v )
            {
                $CNOA_DB->db_delete( $this->tableRecord, "WHERE `id` = '".$v."' " );
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 135 );
            msg::callback( TRUE, lang( "successopt" ) );
        }
    }

    public function actionList( )
    {
        global $CNOA_CONTROLLER;
        global $CNOA_DB;
        $task = getpar( $_GET, "task" );
        if ( $task == "loadPage" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/record/record_list.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            exit( );
        }
        if ( $task == "list" )
        {
            $this->_list( );
        }
        else if ( $task == "getAllUserListsInPermitDeptTree" )
        {
            $this->__getAllUserListsInPermitDeptTree( );
        }
        else if ( $task == "delete" )
        {
            $ids = getpar( $_POST, "ids", "" );
            $ids = substr( $ids, 0, -1 );
            $idArr = explode( ",", $ids );
            foreach ( $idArr as $v )
            {
                $CNOA_DB->db_delete( $this->tableRecord, "WHERE `id` = '".$v."' " );
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 134 );
            msg::callback( TRUE, lang( "successopt" ) );
        }
    }

    private function _addList( )
    {
        global $CNOA_DB;
        $start = getpar( $_POST, "start", 0 );
        $where = " WHERE `property`=1 ";
        $where .= $this->__searchRecordList( );
        $dblist = $CNOA_DB->db_select( "*", $this->tableRecord, $where.( " ORDER BY `id` DESC LIMIT ".$start.", {$this->rows}" ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $v )
        {
            $v['truename'] = $this->__takeUserName( $v['uid'] );
            $v['time'] = $this->__formateDate( $v['time'] );
            $data[] = $v;
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        $dataStore->total = $CNOA_DB->db_getcount( $this->tableRecord, $where );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getList( )
    {
        global $CNOA_DB;
        $start = getpar( $_POST, "start", 0 );
        $where = " WHERE `property`=2 ";
        $where .= $this->__searchRecordList( );
        $dblist = $CNOA_DB->db_select( "*", $this->tableRecord, " ".$where." ORDER BY `id` DESC LIMIT {$start}, {$this->rows}" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        $uidArr = array( );
        foreach ( $dblist as $v )
        {
            $v['truename'] = $this->__takeUserName( $v['uid'] );
            $uidArr[] = $v['uid'];
            $uidArr[] = $v['reguid'];
            $v['time'] = $this->__formateDate( $v['time'] );
            $data[] = $v;
        }
        $allTruename = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr );
        foreach ( $data as $k => $v )
        {
            $data[$k]['transname'] = $allTruename[$v['uid']]['truename'];
            $data[$k]['regname'] = $allTruename[$v['reguid']]['truename'];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        $dataStore->total = $CNOA_DB->db_getcount( $this->tableRecord, $where );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _borrowList( )
    {
        global $CNOA_DB;
        $start = getpar( $_POST, "start", 0 );
        $where = " WHERE (`property`=3 OR `property`=4) ";
        $where .= $this->__searchRecordList( );
        $dblist = $CNOA_DB->db_select( "*", $this->tableRecord, " ".$where." ORDER BY `id` DESC LIMIT {$start}, {$this->rows}" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $v )
        {
            $v['type'] = $v['property'] == 3 ? "借用" : "归还";
            $v['truename'] = $this->__takeUserName( $v['uid'] );
            $v['borrower'] = $this->__takeUserName( $v['reguid'] );
            $v['time'] = $this->__formateDate( $v['time'] );
            $data[] = $v;
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        $dataStore->total = $CNOA_DB->db_getcount( $this->tableRecord, $where );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _dropList( )
    {
        global $CNOA_DB;
        $start = getpar( $_POST, "start", 0 );
        $where = " WHERE `property`=5 ";
        $where .= $this->__searchRecordList( );
        $dblist = $CNOA_DB->db_select( "*", $this->tableRecord, " ".$where." ORDER BY `id` DESC LIMIT {$start}, {$this->rows}" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $v )
        {
            $v['truename'] = $this->__takeUserName( $v['uid'] );
            $v['time'] = $this->__formateDate( $v['time'] );
            $data[] = $v;
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        $dataStore->total = $CNOA_DB->db_getcount( $this->tableRecord, $where );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _repairList( )
    {
        global $CNOA_DB;
        $start = getpar( $_POST, "start", 0 );
        $where = " WHERE `property`=6 ";
        $where .= $this->__searchRecordList( );
        $dblist = $CNOA_DB->db_select( "*", $this->tableRecord, " ".$where." ORDER BY `id` DESC LIMIT {$start}, {$this->rows}" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $v )
        {
            $v['truename'] = $this->__takeUserName( $v['uid'] );
            $v['time'] = $this->__formateDate( $v['time'] );
            $data[] = $v;
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        $dataStore->total = $CNOA_DB->db_getcount( $this->tableRecord, $where );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _deleteList( )
    {
        global $CNOA_DB;
        $start = getpar( $_POST, "start", 0 );
        $where = " WHERE `property`=7 ";
        $where .= $this->__searchRecordList( );
        $dblist = $CNOA_DB->db_select( "*", $this->tableRecord, " ".$where." ORDER BY `id` DESC LIMIT {$start}, {$this->rows}" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $v )
        {
            $v['truename'] = $this->__takeUserName( $v['uid'] );
            $v['time'] = $this->__formateDate( $v['time'] );
            $data[] = $v;
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        $dataStore->total = $CNOA_DB->db_getcount( $this->tableRecord, $where );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _list( )
    {
        global $CNOA_DB;
        $start = getpar( $_POST, "start", 0 );
        $where = " WHERE 1 ";
        $where .= $this->__searchRecordList( 1 );
        $dblist = $CNOA_DB->db_select( "*", $this->tableProduct, " ".$where." ORDER BY `id` DESC LIMIT {$start}, {$this->rows}" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $v )
        {
            $temp['id'] = $v['id'];
            $temp['productName'] = $v['name'];
            $temp['productNumber'] = $v['number'];
            $temp['typeName'] = app::loadapp( "adm", "articlesInfo" )->api_getTypeName( $v['typeID'] );
            $temp['libraryName'] = app::loadapp( "adm", "articlesLibrary" )->api_getLibraryName( $v['libraryID'] );
            $temp['stock'] = $v['stock'];
            $temp['lowerStock'] = $v['lowerStock'];
            $temp['heightStock'] = $v['heightStock'];
            if ( !empty( $temp['heightStock'] ) || !empty( $temp['lowerStock'] ) || ( $temp['heightStock'] <= $temp['stock'] || $temp['stock'] <= $temp['lowerStock'] ) )
            {
                $temp['stock'] = "<span style='color:red'>".$temp['stock']."</span>";
            }
            $temp['truename'] = $this->__takeUserName( $v['uid'] );
            $data[] = $temp;
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        $dataStore->total = $CNOA_DB->db_getcount( $this->tableProduct, $where );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function __formateDate( $timeInt )
    {
        if ( $timeInt == 0 )
        {
            return "";
        }
        return date( "Y-m-d", $timeInt );
    }

    private function __takeUserName( $id )
    {
        $data = app::loadapp( "main", "user" )->api_getUserDataByUid( $id );
        return $data['truename'];
    }

    private function __searchRecordList( $act = 0 )
    {
        $name = getpar( $_POST, "name" );
        $uid = getpar( $_POST, "user" );
        $stime = getpar( $_POST, "stime", "" );
        $etime = getpar( $_POST, "etime", "" );
        if ( strtotime( $etime ) - strtotime( $stime ) < 0 )
        {
            msg::callback( FALSE, lang( "StimeGreaterThanEtime" ) );
        }
        $name_key = "productName";
        switch ( intval( $act ) )
        {
        case 1 :
            $name_key = "name";
        }
        $s = "";
        if ( !empty( $name ) )
        {
            $s .= " AND `".$name_key."` LIKE '%{$name}%'";
        }
        if ( !empty( $uid ) )
        {
            $s .= " AND `uid`=".$uid;
        }
        if ( !empty( $stime ) )
        {
            $stime = strtotime( $stime."00:00:00" );
            $s .= " AND `time` >='".$stime."'";
        }
        if ( !empty( $etime ) )
        {
            $etime = strtotime( $etime."23:59:59" );
            $s .= " AND `time` <= '".$etime."'";
        }
        return " ".$s." ";
    }

    private function __getAllUserListsInPermitDeptTree( )
    {
        $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
        $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
        echo json_encode( $userList );
        exit( );
    }

    private function _exportExcel( )
    {
        global $CNOA_DB;
        $step = getpar( $_GET, "step", 0 );
        $stime = getpar( $_POST, "stime", 0 );
        $etime = getpar( $_POST, "etime", 0 );
        if ( strtotime( $etime ) - strtotime( $stime ) < 0 )
        {
            msg::callback( FALSE, lang( "StimeGreaterThanEtime" ) );
        }
        if ( $step == 1 )
        {
            $mapping = array(
                "ruku" => array( "property" => 1 ),
                "lingyong" => array( "property" => 2 ),
                "jieyong" => array( "property" => 3 ),
                "guihuan" => array( "property" => 4 ),
                "baofei" => array( "property" => 5 ),
                "weihu" => array( "property" => 6 ),
                "del" => array( "property" => 7 )
            );
            $info = array( );
            if ( getpar( $_POST, "kucun", "" ) == "on" )
            {
                $kucun = $CNOA_DB->db_select( "*", $this->tableProduct, "WHERE 1" );
                foreach ( $kucun as $k => $v )
                {
                    $kucun[$k]['typeID'] = app::loadapp( "adm", "articlesInfo" )->api_getTypeName( $v['typeID'] );
                    $kucun[$k]['libraryID'] = app::loadapp( "adm", "articlesLibrary" )->api_getLibraryName( $v['libraryID'] );
                }
                if ( !empty( $kucun ) )
                {
                    $info['库存'] = $kucun;
                }
            }
            $property = array( );
            foreach ( $mapping as $k => $v )
            {
                $selected = getpar( $_POST, $k, "off" );
                if ( $selected == "on" )
                {
                    $property[] = $v['property'];
                }
            }
            if ( !empty( $property ) )
            {
                $property = implode( ",", $property );
                $where = "WHERE `property` IN (".$property.") ";
                if ( !empty( $stime ) )
                {
                    $stime = strtotime( $stime." 00:00:00" );
                    $where .= "AND `time` >= '".$stime."' ";
                }
                if ( !empty( $etime ) )
                {
                    $etime = strtotime( $etime." 23:59:59" );
                    $where .= "AND `time` <= '".$etime."' ";
                }
                $dbList = $CNOA_DB->db_select( "*", $this->tableRecord, $where." ORDER BY `time` ASC" );
            }
            if ( !empty( $dbList ) && !empty( $info['库存'] ) )
            {
                if ( !is_array( $dbList ) )
                {
                    $dbList = array( );
                }
                foreach ( $dbList as $v )
                {
                    switch ( $v['property'] )
                    {
                    case 1 :
                        $info['入库'][] = $v;
                        break;
                    case 2 :
                        $info['领用'][] = $v;
                        break;
                    case 3 :
                    case 4 :
                        $info['借用、归还'][] = $v;
                        break;
                    case 5 :
                        $info['报废'][] = $v;
                        break;
                    case 6 :
                        $info['维护'][] = $v;
                        break;
                    case 7 :
                        $info['删除'][] = $v;
                    }
                }
                include( CNOA_PATH."/core/inc/admRecord.php" );
                ( );
                $excelClass = new exportExcel( );
                $info = $excelClass->formatExcelDate( $fieldName, $info );
                $fileName = "CNOA.ADM-".date( "Ymd", $GLOBALS['CNOA_TIMESTAMP'] )."-".string::rands( 10, 2 ).".xlsx";
                $excelClass->init( $info );
                $excelClass->save( CNOA_PATH_FILE."/common/temp/".$fileName, "excel2007" );
                msg::callback( TRUE, $fileName );
            }
            else
            {
                msg::callback( FALSE, lang( "noDataToExport" ) );
            }
        }
        else
        {
            $fileName = getpar( $_GET, "file", "" );
            $file = CNOA_PATH_FILE."/common/temp/".$fileName;
            if ( !file_exists( $file ) )
            {
                echo "文件不存在";
                exit( );
            }
            if ( $CNOA_DB )
            {
                $CNOA_DB->close( );
            }
            @ini_set( "zlib.output_compression", "Off" );
            header( "Content-type: application/octet-stream" );
            header( "Content-Disposition: attachment;filename=".cn_urlencode( "用品.xlsx" ) );
            header( "Content-Length: ".filesize( $file ) );
            ob_clean( );
            flush( );
            readfile( $file );
            @unlink( $file );
        }
        exit( );
    }

}

?>
