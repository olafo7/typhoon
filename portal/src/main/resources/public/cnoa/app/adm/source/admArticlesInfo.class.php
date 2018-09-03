<?php
//decode by qq2859470

class admArticlesInfo extends model
{

    private $tableType = "adm_articles_type";
    private $table = "adm_articles_product";
    private $tableRegister = "adm_articles_register";
    private $tableRegisterPermit = "adm_articles_register_permit";
    private $tableRecord = "adm_articles_record";
    private $tableLibrary = "adm_articles_library";
    private $tableLibraryPermit = "adm_articles_library_permit";
    private $rows = 15;
    private $data = array( );

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        if ( $task == "loadPage" )
        {
            global $CNOA_CONTROLLER;
            $from = getpar( $_GET, "from", "" );
            if ( $from == "manage" )
            {
                $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/articles/info_manage.htm";
            }
            else if ( $from == "editType" )
            {
                $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/articles/info_editType.htm";
            }
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            exit( );
        }
        if ( $task == "add" )
        {
            $this->_add( );
        }
        else if ( $task == "library" )
        {
            $this->_library( );
        }
        else if ( $task == "edit" )
        {
            $this->_edit( );
        }
        else if ( $task == "getList" )
        {
            $this->_getList( );
        }
        else if ( $task == "editList" )
        {
            $this->_editList( );
        }
        else if ( $task == "batchChange" )
        {
            $this->_batchChange( );
        }
        else if ( $task == "viewDetails" )
        {
            $this->_viewDetails( );
        }
        else if ( $task == "delete" )
        {
            $this->_delete( );
        }
        else if ( $task == "getArticlesLibraryList" )
        {
            app::loadapp( "adm", "articlesLibrary" )->api_getArticlesLibraryList( );
        }
        else if ( $task == "getArticlesTypelList" )
        {
            $this->_getArticlesTypelList( );
        }
        else if ( $task == "getAllUserListsInPermitDeptTree" )
        {
            $this->_getAllUserListsInPermitDeptTree( );
        }
        else if ( $task == "getArticlesChecklList" )
        {
            $this->_getArticlesChecklList( );
        }
        else if ( $task == "getArticlesProductlList" )
        {
            $this->_getArticlesProductlList( );
        }
        else if ( $task == "getAllUserListsInPermitDeptTree" )
        {
            $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $userList );
            exit( );
        }
    }

    private function _add( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $data['stock'] = 0;
        $data['lowerStock'] = getpar( $_POST, "lowerStock", "" );
        $data['heightStock'] = getpar( $_POST, "heightStock", "" );
        if ( $data['heightStock'] < $data['lowerStock'] )
        {
            msg::callback( FALSE, lang( "minInitNotDY" ) );
        }
        $data['name'] = getpar( $_POST, "name", "" );
        $data['unit'] = getpar( $_POST, "unit", "" );
        $data['supplies'] = getpar( $_POST, "supplies", "" );
        $data['libraryID'] = getpar( $_POST, "libraryID", "" );
        $data['description'] = getpar( $_POST, "description", "" );
        $data['number'] = getpar( $_POST, "number", "" );
        $data['price'] = getpar( $_POST, "price", "" );
        $data['typeID'] = getpar( $_POST, "typeID", "" );
        $data['checkID'] = getpar( $_POST, "checkID", "" );
        $data['deptID'] = getpar( $_POST, "deptID", "" );
        $signInIDs = explode( ",", getpar( $_POST, "signInIDs", "" ) );
        $data['signInIDs'] = json_encode( $signInIDs );
        $data['uid'] = $CNOA_SESSION->get( "UID" );
        $data['standard'] = getpar( $_POST, "standard", "" );
        $data['applyType'] = getpar( $_POST, "applyType", "" );
        $insertID = $CNOA_DB->db_insert( $data, $this->table );
        if ( isemptyarray( $signInIDs ) )
        {
            $CNOA_DB->db_insert( array(
                "libraryID" => $data['libraryID'],
                "typeID" => $data['typeID'],
                "productID" => $insertID,
                "uid" => 0
            ), $this->tableRegisterPermit );
        }
        else
        {
            foreach ( $signInIDs as $v )
            {
                if ( !empty( $v ) )
                {
                    $CNOA_DB->db_insert( array(
                        "libraryID" => $data['libraryID'],
                        "typeID" => $data['typeID'],
                        "productID" => $insertID,
                        "uid" => $v
                    ), $this->tableRegisterPermit );
                }
            }
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 116, $data['name'], lang( "article" ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _edit( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = getpar( $_GET, "id", "" );
        $data['name'] = getpar( $_POST, "name", "" );
        $data['unit'] = getpar( $_POST, "unit", "" );
        $data['supplies'] = getpar( $_POST, "supplies", "" );
        $data['lowerStock'] = getpar( $_POST, "lowerStock", "" );
        $data['libraryID'] = getpar( $_POST, "libraryID", "" );
        $data['description'] = getpar( $_POST, "description", "" );
        $data['number'] = getpar( $_POST, "number", "" );
        $data['price'] = getpar( $_POST, "price", "" );
        $data['heightStock'] = getpar( $_POST, "heightStock", "" );
        $data['typeID'] = getpar( $_POST, "typeID", "" );
        $data['checkID'] = getpar( $_POST, "checkID", "" );
        $data['deptID'] = getpar( $_POST, "deptID", "" );
        $signInIDs = explode( ",", getpar( $_POST, "signInIDs", "" ) );
        $data['signInIDs'] = json_encode( $signInIDs );
        $data['uid'] = $CNOA_SESSION->get( "UID" );
        $data['applyType'] = getpar( $_POST, "applyType", "" );
        $data['standard'] = getpar( $_POST, "standard", "" );
        $CNOA_DB->db_update( $data, $this->table, "WHERE `id`=".$id );
        $CNOA_DB->db_delete( $this->tableRegisterPermit, "WHERE `productID`='".$id."'" );
        if ( isemptyarray( $signInIDs ) )
        {
            $CNOA_DB->db_insert( array(
                "libraryID" => $data['libraryID'],
                "typeID" => $data['typeID'],
                "productID" => $id,
                "uid" => 0
            ), $this->tableRegisterPermit );
        }
        else
        {
            foreach ( $signInIDs as $v )
            {
                if ( !empty( $v ) )
                {
                    $CNOA_DB->db_insert( array(
                        "libraryID" => $data['libraryID'],
                        "typeID" => $data['typeID'],
                        "productID" => $id,
                        "uid" => $v
                    ), $this->tableRegisterPermit );
                }
            }
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 116, $data['name'], lang( "article" ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _batchChange( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uids = getpar( $_POST, "uids" );
        $uids = explode( ",", $uids );
        $ids = getpar( $_POST, "ids", 0 );
        $ids = substr( $ids, 0, -1 );
        $CNOA_DB->db_delete( $this->tableRegisterPermit, "WHERE `productID` IN (".$ids.")" );
        $ids = explode( ",", $ids );
        foreach ( $ids as $v )
        {
            $temp = $CNOA_DB->db_getone( array( "libraryID", "typeID" ), $this->table, "WHERE `id`=".$v );
            $CNOA_DB->db_update( array(
                "signInIDs" => json_encode( $uids )
            ), $this->table, "WHERE `id`=".$v );
            foreach ( $uids as $v2 )
            {
                $CNOA_DB->db_insert( array(
                    "libraryID" => $temp['libraryID'],
                    "typeID" => $temp['typeID'],
                    "productID" => $v,
                    "uid" => $v2
                ), $this->tableRegisterPermit, "WHERE `productID`=".$v );
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _library( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $type = getpar( $_POST, "type" );
        $productID = getpar( $_POST, "productID" );
        $quantity = getpar( $_POST, "quantity" );
        $notes = getpar( $_POST, "notes" );
        $uid = $CNOA_SESSION->get( "UID" );
        $dblist = $CNOA_DB->db_getone( array( "number", "stock", "lowerStock", "heightStock", "name", "uid", "unit", "typeID", "libraryID" ), $this->table, "WHERE `id`='".$productID."'" );
        $typeName = app::loadapp( "adm", "articlesInfo" )->api_getTypeName( $dblist['typeID'] );
        $libraryName = app::loadapp( "adm", "articlesLibrary" )->api_getLibraryName( $dblist['libraryID'] );
        if ( $type == 3 )
        {
            $CNOA_DB->db_updateNum( "stock", "+".$quantity, $this->table, "WHERE `id`='".$productID."'" );
            $dblist['stock'] = $dblist['stock'] + $quantity;
            $property = 1;
        }
        if ( $type == 1 )
        {
            if ( $dblist['stock'] <= $quantity )
            {
                $quantity = $dblist['stock'];
            }
            $CNOA_DB->db_updateNum( "stock", "-".$quantity, $this->table, "WHERE `id`='".$productID."'" );
            $dblist['stock'] = $dblist['stock'] - $quantity;
            $property = 5;
        }
        if ( $type == 2 )
        {
            if ( $dblist['stock'] < $quantity )
            {
                msg::callback( FALSE, lang( "stockNumBuZu" ) );
            }
            $noticTime = getpar( $_POST, "notice" );
            $noticTime = strtotime( $noticTime );
            $repairTime = getpar( $_POST, "repair" );
            $content = lang( "needInLibrary" ).( "：".$libraryName."，" ).lang( "sort" ).( "：".$typeName."，" ).lang( "maintenance1" )."{$dblist['name']}，{$quantity} {$dblist['unit']}，".lang( "timeIs" )."{$repairTime}, ".lang( "remark" ).( "：".$notes );
            notice::add( intval( $uid ), lang( "xzSupplieWeihu" ), $content, "", "", 10, $productID, 1 );
            $property = 6;
        }
        if ( $dblist['heightStock'] != 0 && $dblist['lowerStock'] != 0 && ( $dblist['heightStock'] < $dblist['stock'] || $dblist['stock'] < $dblist['lowerStock'] ) )
        {
            notice::add( intval( $dblist['uid'] ), lang( "supplieWarning" ), lang( "article" ).( ":".$dblist['name'] ).lang( "hasExceeWarnLevel" ), "", "", 10, $productID, 1 );
        }
        $CNOA_DB->db_insert( array(
            "productID" => $productID,
            "productNumber" => $dblist['number'],
            "productName" => $dblist['name'],
            "typeName" => $typeName,
            "libraryName" => $libraryName,
            "quantity" => $quantity,
            "stock" => $dblist['stock'],
            "uid" => $uid,
            "time" => $GLOBALS['CNOA_TIMESTAMP'],
            "property" => $property,
            "notes" => $notes
        ), $this->tableRecord );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 116, $dblist['name'], lang( "stock" ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _formateName( $value )
    {
        $value = json_decode( $value );
        if ( !is_array( $value ) )
        {
            $value = array( );
        }
        if ( array_sum( $value ) == "0" )
        {
            return "任何人可登记";
        }
        if ( !is_array( $value ) )
        {
            $value = array( );
        }
        foreach ( $value as $v )
        {
            $name .= $this->_takeUserName( $v ).",";
        }
        return $name;
    }

    private function _formateID( $value )
    {
        $value = json_decode( $value );
        if ( !is_array( $value ) )
        {
            $value = array( );
        }
        foreach ( $value as $v )
        {
            $name .= $v.",";
        }
        $name = substr( $name, 0, strlen( $name ) - 1 );
        return $name;
    }

    private function _getList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = $CNOA_SESSION->get( "UID" );
        $WHERE = "WHERE 1 ";
        $name = getpar( $_POST, "name", "" );
        $number = getpar( $_POST, "number", "" );
        $begin = getpar( $_POST, "begin", 0 );
        $end = getpar( $_POST, "end", 0 );
        $start = getpar( $_POST, "start", 0 );
        if ( !empty( $name ) )
        {
            $WHERE .= "AND `name` LIKE '%".$name."%' ";
        }
        if ( !empty( $number ) )
        {
            $WHERE .= "AND `number` LIKE '%".$number."%' ";
        }
        if ( !empty( $begin ) && !empty( $end ) )
        {
            if ( !empty( $begin ) || !empty( $end ) )
            {
                if ( $end < $begin )
                {
                    msg::callback( FALSE, lang( "stockRangeWrong" ) );
                }
                else
                {
                    $WHERE .= "AND `stock` <= ".$end." AND `stock` >= {$begin} ";
                }
            }
            else
            {
                msg::callback( FALSE, lang( "fillStockNumRange" ) );
            }
        }
        $dblist = $CNOA_DB->db_select( "*", $this->table, $WHERE.( "ORDER BY `id` DESC LIMIT ".$start.", {$this->rows}" ) );
        $this->_formatList( $dblist );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $this->data;
        $dataStore->total = $CNOA_DB->db_getcount( $this->table, $WHERE );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _viewDetails( )
    {
        global $CNOA_CONTROLLER;
        global $CNOA_DB;
        $id = getpar( $_GET, "ID" );
        $data = $CNOA_DB->db_getone( "*", $this->table, "WHERE `id`='".$id."'" );
        $typeNameList = $this->_getTypeName( );
        $library = app::loadapp( "adm", "articlesLibrary" )->api_getAllArticlesLibraryName( );
        $data['libraryName'] = $library[$data['libraryID']]['name'];
        $data['typeName'] = $typeNameList[$data['typeID']]['name'];
        $data['checkName'] = $this->_takeUserName( $data['checkID'] );
        $data['signInNames'] = $this->_formateName( $data['signInIDs'] );
        $data['createName'] = $this->_takeUserName( $data['uid'] );
        $GLOBALS['GLOBALS']['adm']['articlesViewDetails'] = $data;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/articles/info_articlesViewDetails.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _editList( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id" );
        $dblist = $CNOA_DB->db_getone( "*", $this->table, "WHERE `id`=".$id );
        $dblist['signInNames'] = $this->_formateName( $dblist['signInIDs'] );
        $dblist['signInIDs'] = $this->_formateID( $dblist['signInIDs'] );
        $dblist['checkName'] = $this->_takeUserName( $dblist['checkID'] );
        $dblist['typeTake'] = $this->__formatType( $dblist['applyType'] );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    public function _formatList( $dblist )
    {
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $lists = array( );
        $typeNameList = $this->_getTypeName( );
        $library = app::loadapp( "adm", "articlesLibrary" )->api_getAllArticlesLibraryName( );
        $uidArr = array( );
        foreach ( $dblist as $v )
        {
            $list = array( );
            $list['id'] = $v['id'];
            $list['name'] = $v['name'];
            $list['number'] = $v['number'];
            $list['unit'] = $v['unit'];
            $list['price'] = $v['price'];
            $list['supplies'] = $v['supplies'];
            $list['stock'] = $v['stock'];
            $list['lowerStock'] = $v['lowerStock'];
            $list['heightStock'] = $v['heightStock'];
            $list['standard'] = $v['standard'];
            if ( ( $list['heightStock'] != 0 || $list['lowerStock'] != 0 ) && ( $list['heightStock'] <= $list['stock'] || $list['stock'] <= $list['lowerStock'] ) )
            {
                $list['stock'] = "<span style='color:red'>".$list['stock']."</span>";
            }
            $list['libraryID'] = $v['libraryID'] == 0 ? "" : $v['libraryID'];
            $list['libraryName'] = $library[$v['libraryID']]['name'];
            $list['typeID'] = $v['typeID'];
            $list['typeName'] = $typeNameList[$v['typeID']]['name'];
            $list['description'] = $v['description'];
            $list['checkID'] = $v['checkID'];
            $list['checkName'] = $this->_takeUserName( $v['checkID'] );
            $list['signInIDs'] = $this->_formateID( $v['signInIDs'] );
            $list['signInNames'] = $this->_formateName( $v['signInIDs'] );
            $list['deptID'] = $v['deptID'];
            $list['deptName'] = $this->_getDepartName( $v['deptID'] );
            $list['uid'] = $v['uid'];
            $uidArr[] = $v['uid'];
            $this->data[] = $list;
            $lists[] = $list;
        }
        $uids = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr );
        if ( !is_array( $this->data ) )
        {
            $this->data = array( );
        }
        foreach ( $this->data as $k => $v )
        {
            $this->data[$k]['truename'] = $uids[$v['uid']]['truename'];
            $lists[$k]['truename'] = $uids[$v['uid']]['truename'];
        }
        return $lists;
    }

    private function _delete( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $ids = getpar( $_POST, "ids" );
        if ( $ids )
        {
            $ids = explode( ",", substr( $ids, 0, -1 ) );
            if ( is_array( $ids ) )
            {
                $DB = $CNOA_DB->db_select( array( "noticeid_t", "noticeid_r", "noticeid_c", "todoid_t", "todoid_r", "todoid_c" ), $this->tableRegister, "WHERE `productID` IN (".implode( ",", $ids ).") " );
                if ( !is_array( $DB ) )
                {
                    $DB = array( );
                }
                $notice = array( );
                $todo = array( );
                foreach ( $DB as $k => $v )
                {
                    $notice[] = $v['noticeid_t'];
                    $notice[] = $v['noticeid_r'];
                    $notice[] = $v['noticeid_c'];
                    $todo[] = $v['todoid_t'];
                    $todo[] = $v['todoid_r'];
                    $todo[] = $v['todoid_c'];
                }
                notice::deletenotice( $notice, $todo );
                foreach ( $ids as $v )
                {
                    $product = $CNOA_DB->db_getone( array( "number", "name", "typeID", "libraryID" ), $this->table, "WHERE `id`='".$v."'" );
                    $type = $CNOA_DB->db_getone( array( "name" ), $this->tableType, "WHERE `id`='".$product['typeID']."'" );
                    $library = $CNOA_DB->db_getone( array( "name" ), $this->tableLibrary, "WHERE `id`='".$product['libraryID']."'" );
                    $CNOA_DB->db_insert( array(
                        "property" => 7,
                        "notes" => "删除用品",
                        "productID" => $v,
                        "productNumber" => $product['number'],
                        "productName" => $product['name'],
                        "typeName" => $type['name'],
                        "libraryName" => $library['name'],
                        "uid" => $CNOA_SESSION->get( "UID" ),
                        "time" => $GLOBALS['CNOA_TIMESTAMP']
                    ), $this->tableRecord );
                    $CNOA_DB->db_delete( "adm_articles_register_permit", "WHERE `productID`='".$v."'" );
                    $CNOA_DB->db_delete( $this->table, "WHERE `id`='".$v."'" );
                    app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 116, $type['name'], lang( "article" ) );
                    $CNOA_DB->db_delete( $this->tableRegister, "WHERE `productID`='".$v."'" );
                }
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getArticlesTypelList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = getpar( $_POST, "id", 0 );
        if ( $id == 0 )
        {
            ( );
            $dataStore = new dataStore( );
            $dataStore->total = 0;
            $dataStore->data = array( );
            echo $dataStore->makeJsonData( );
            exit( );
        }
        $dblist = $CNOA_DB->db_select( "*", $this->tableType, "WHERE `libraryID`=".$id );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $list = array( );
        foreach ( $dblist as $k => $v )
        {
            $list[$k]['typeID'] = $v['id'];
            $list[$k]['name'] = $v['name'];
            $list[$k]['libraryID'] = $v['libraryID'];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = 0;
        $dataStore->data = $list;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getAllUserListsInPermitDeptTree( )
    {
        $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
        $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
        echo json_encode( $userList );
        exit( );
    }

    private function _getTypeName( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->tableType );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $list = array( );
        foreach ( $dblist as $v )
        {
            $list[$v['id']]['id'] = $v['id'];
            $list[$v['id']]['libraryID'] = $v['libraryID'];
            $list[$v['id']]['name'] = $v['name'];
        }
        return $list;
    }

    private function _getArticlesChecklList( )
    {
        $id = getpar( $_POST, "id", 0 );
        if ( $id == 0 )
        {
            ( );
            $dataStore = new dataStore( );
            $dataStore->total = 0;
            $dataStore->data = array( );
            echo $dataStore->makeJsonData( );
            exit( );
        }
        $list = app::loadapp( "adm", "articlesLibrary" )->api_getAllArticlesLibraryByOneList( $id );
        $adminIDs = json_decode( $list['adminIDs'] );
        if ( !is_array( $adminIDs ) )
        {
            $adminIDs = array( );
        }
        $list = array( );
        foreach ( $adminIDs as $v )
        {
            $data['checkID'] = $v;
            $data['name'] = $this->_takeUserName( $v );
            $list[] = $data;
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = 0;
        $dataStore->data = $list;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getArticlesProductlList( )
    {
        global $CNOA_DB;
        $id = ( integer )getpar( $_POST, "id" );
        if ( $id == 0 )
        {
            ( );
            $dataStore = new dataStore( );
            $dataStore->total = 0;
            $dataStore->data = array( );
            echo $dataStore->makeJsonData( );
            exit( );
        }
        $libaryIds = $this->__getLibaryID( );
        if ( array_sum( $libaryIds ) == 0 )
        {
            ( );
            $dataStore = new dataStore( );
            $dataStore->data = array( );
            echo $dataStore->makeJsonData( );
            exit( );
        }
        $dblist = $CNOA_DB->db_select( "*", $this->table, "WHERE `libraryID` IN (".implode( ",", $libaryIds ).( ") AND typeID=".$id ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $list = array( );
        foreach ( $dblist as $k => $v )
        {
            $list['productID'] = $v['id'];
            $list['name'] = $v['name']." / 库存:".$v['stock'].$v['unit'];
            $data[] = $list;
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = 0;
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getDepartName( $deptID )
    {
        $depList = app::loadapp( "main", "struct" )->api_getArrayList( );
        $deptName = $depList[$deptID];
        return $deptName;
    }

    private function _takeUserName( $id )
    {
        $data = app::loadapp( "main", "user" )->api_getUserDataByUid( $id );
        return $data['truename'];
    }

    private function __getLibaryID( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $dblist = $CNOA_DB->db_select( array( "libraryID" ), $this->tableLibraryPermit, "WHERE `uid`='".$uid."'" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $v )
        {
            $data[] = $v['libraryID'];
        }
        return $data;
    }

    public function api_getArticlesTypeName( $id )
    {
        global $CNOA_DB;
        $list = $CNOA_DB->db_getone( array( "name" ), $this->tableType, "WHERE `id`=".$id );
        return $list['name'];
    }

    public function api_getTypeName( $typeID )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_getone( array( "name" ), $this->tableType, "WHERE `id`='".$typeID."'" );
        return $dblist['name'];
    }

    public function api_getTypeData( $id )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_getone( "*", $this->tableType, "WHERE `id`='".$id."'" );
        return $dblist;
    }

    public function api_getProductData( $id )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_getone( "*", $this->table, "WHERE `id`='".$id."'" );
        return $dblist;
    }

    public function api_getAllArticlesTypeList( $id )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "name" ), $this->tableType, "WHERE `libraryID`=".$id );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $v )
        {
            $list .= $v['name'].", ";
        }
        return $list;
    }

    public function api_libraryToTypeID( $libraryID )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "id" ), $this->tableType, "WHERE `libraryID`=".$libraryID );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $v )
        {
            $CNOA_DB->db_delete( $this->table, "WHERE `typeID`=".$v['id'] );
        }
        $CNOA_DB->db_delete( $this->tableType, "WHERE `libraryID`=".$libraryID );
    }

    public function api_updateLibraryNumber( $recordType, $id, $number, $uid )
    {
        global $CNOA_DB;
        $libInfo = $CNOA_DB->db_getone( "*", $this->table, "WHERE `id`='".$id."'" );
        if ( !$libInfo )
        {
            return FALSE;
        }
        $type = in_array( $recordType, array( 1, 4 ) ) ? 1 : 0;
        if ( $type )
        {
            $CNOA_DB->db_updateNum( "stock", "+".$number, $this->table, "WHERE `id`='".$id."'" );
        }
        else if ( $libInfo['stock'] - $number <= 0 )
        {
            $CNOA_DB->db_update( array( "stock" => 0 ), $this->table, "WHERE `id`='".$id."'" );
        }
        else
        {
            $CNOA_DB->db_updateNum( "stock", "-".$number, $this->table, "WHERE `id`='".$id."'" );
        }
        unset( $libInfo );
        $sql = "SELECT `p`.`name` as pname, `p`.`number`, `p`.`stock`, `l`.`name` as lname, `t`.`name` as tname FROM ".tname( "adm_articles_product" )." AS `p` LEFT JOIN ".tname( "adm_articles_library" )." AS `l` ON `p`.`libraryid` = `l`.`id` LEFT JOIN ".tname( "adm_articles_type" )." AS `t` ON `p`.`typeid` = `t`.`id` ".( "WHERE `p`.`id` = ".$id." " );
        $goodsInfo = $CNOA_DB->get_one( $sql );
        if ( !empty( $goodsInfo ) )
        {
            $record = array( );
            $record['property'] = $recordType;
            $record['productID'] = $id;
            $record['productNumber'] = $goodsInfo['number'];
            $record['productName'] = $goodsInfo['pname'];
            $record['typeName'] = $goodsInfo['tname'];
            $record['libraryName'] = $goodsInfo['lname'];
            $record['quantity'] = $number;
            $record['stock'] = $goodsInfo['stock'];
            $record['reguid'] = $uid;
            $record['uid'] = $uid;
            $record['time'] = $GLOBALS['CNOA_TIMESTAMP'];
            $CNOA_DB->db_insert( $record, $this->tableRecord );
            unset( $record );
        }
        unset( $goodsInfo );
        unset( $sql );
    }

    private function __formatType( $value )
    {
        if ( $value == 3 )
        {
            return lang( "consuming" );
        }
        if ( $value == 2 )
        {
            return lang( "borrowing" );
        }
        if ( $value == 1 )
        {
            return lang( "restitution" );
        }
    }

}

?>
