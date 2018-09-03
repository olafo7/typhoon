<?php
//decode by qq2859470

class admArticlesTransport extends model
{

    private $tableType = "adm_articles_type";
    private $tableLibrary = "adm_articles_library";
    private $tableProduct = "adm_articles_product";
    private $table = "adm_articles_register";
    private $tableRecord = "adm_articles_record";
    private $rows = 15;

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        if ( $task == "loadPage" )
        {
            global $CNOA_CONTROLLER;
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/articles/info_transport.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            exit( );
        }
        if ( $task == "list" )
        {
            $this->_list( );
        }
        else if ( $task == "finishTask" )
        {
            $this->_finishTask( );
        }
    }

    private function _list( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = $CNOA_SESSION->get( "UID" );
        $start = getpar( $_POST, "start", 0 );
        $WHERE = "WHERE 1 ";
        $storeType = getpar( $_POST, "storeType", "borrow" );
        if ( $storeType == "borrow" )
        {
            $WHERE .= "AND `type`=2 ";
        }
        else if ( $storeType == "lead" )
        {
            $WHERE .= "AND `type`=3 ";
        }
        else if ( $storeType == "return" )
        {
            $WHERE .= "AND `type`=1 ";
        }
        $dblist = $CNOA_DB->db_select( "*", $this->table, $WHERE.( "AND `status`='2' AND `transportID`='".$id."' ORDER BY `id` DESC LIMIT {$start}, {$this->rows}" ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $v )
        {
            $list['id'] = $v['id'];
            $list['productID'] = $v['productID'];
            $list['libraryName'] = $this->__getLibraryName( $v['libraryID'] );
            $list['typeName'] = $this->__getTypeName( $v['typeID'] );
            $list['productName'] = $this->__getProductName( $v['productID'] );
            $list['checkName'] = $this->__getUserTruename( $v['checkID'] );
            $list['signInName'] = $this->__getUserTruename( $v['signInID'] );
            $list['type'] = $this->__getType( $v['type'] );
            $list['status'] = $v['type'];
            $list['quantity'] = $v['quantity'];
            $list['transportStatus'] = $v['transportStatus'];
            $list['finishTime'] = $this->__formateDate( $v['finishTime'] );
            $list['signInStatus'] = $v['signInStatus'];
            $data[] = $list;
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        $dataStore->total = $CNOA_DB->db_getcount( $this->table, "WHERE `status`=2 AND `transportID`='".$id."'" );
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

    private function _finishTask( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = getpar( $_POST, "id", 0 );
        $productID = getpar( $_POST, "productID" );
        $status = getpar( $_POST, "status", 0 );
        $uid = $CNOA_SESSION->get( "UID" );
        $list = $CNOA_DB->db_getone( array( "notes", "quantity", "signInID", "noticeid_t", "todoid_t" ), $this->table, "WHERE `id`='".$id."'" );
        $quantity = $list['quantity'];
        $notes = $list['notes'];
        if ( $status == 1 )
        {
            $property = 4;
            $CNOA_DB->db_updateNum( "stock", "+".$quantity, $this->tableProduct, "WHERE `id`='".$productID."'" );
        }
        else
        {
            if ( $status == 2 )
            {
                $property = 3;
            }
            else if ( $status == 3 )
            {
                $property = 2;
            }
            $CNOA_DB->db_updateNum( "stock", "-".$quantity, $this->tableProduct, "WHERE `id`='".$productID."'" );
        }
        $product = $CNOA_DB->db_getone( array( "stock", "lowerStock", "heightStock", "number", "name", "uid", "typeID", "libraryID" ), $this->tableProduct, "WHERE `id`='".$productID."'" );
        $libraryName = $this->__getLibraryName( $product['libraryID'] );
        $typeName = $this->__getTypeName( $product['typeID'] );
        if ( $product['heightStock'] != 0 && $product['lowerStock'] != 0 && ( $product['heightStock'] < $product['stock'] || $product['stock'] < $product['lowerStock'] ) )
        {
            notice::add( intval( $product['uid'] ), lang( "supplieWarning" ), lang( "article" ).( ":".$product['name'] ).lang( "hasExceeWarnLevel" ), "", "", 10, $productID, 1 );
        }
        $record['productID'] = $productID;
        $record['productNumber'] = $product['number'];
        $record['productName'] = $product['name'];
        $record['productNumber'] = $product['number'];
        $record['typeName'] = $typeName;
        $record['libraryName'] = $libraryName;
        $record['quantity'] = $quantity;
        $record['stock'] = $product['stock'];
        $record['uid'] = $uid;
        $record['reguid'] = $list['signInID'];
        $record['time'] = $GLOBALS['CNOA_TIMESTAMP'];
        $record['property'] = $property;
        $record['notes'] = $notes;
        $record['aid'] = $id;
        $noticeid_r = notice::add( $list['signInID'], lang( "supplieDDmgr" ), lang( "article" ).( ":".$product['name']."，" ).lang( "hasGotRegPersonListView" ), "index.php?app=adm&func=articles&action=personRegister&task=loadPage&from=transport", "", 10, $id, 1 );
        $notice['touid'] = $list['signInID'];
        $notice['from'] = 10;
        $notice['fromid'] = $id;
        $notice['href'] = "index.php?app=adm&func=articles&action=personRegister&task=loadPage&from=transport";
        $notice['title'] = "[".$product['name']."]".lang( "article" )."，".lang( "needYouConfirmBeenHand" );
        $notice['content'] = lang( "housr" ).( "[".$libraryName."] " ).lang( "sort" ).( "[".$typeName."]" );
        $notice['funname'] = lang( "articleMgr" );
        $notice['move'] = lang( "confirm" );
        $todoid_r = notice::add2( $notice );
        if ( $status == 1 )
        {
            $CNOA_DB->db_update( array(
                "transportStatus" => 2,
                "signInStatus" => 1,
                "noticeid_r" => $noticeid_r,
                "todoid_r" => $todoid_r,
                "finishTime" => $GLOBALS['CNOA_TIMESTAMP']
            ), $this->table, "WHERE `id` = '".$id."' " );
        }
        else
        {
            $CNOA_DB->db_update( array(
                "transportStatus" => 1,
                "noticeid_r" => $noticeid_r,
                "todoid_r" => $todoid_r
            ), $this->table, "WHERE `id` = '".$id."' " );
        }
        $CNOA_DB->db_insert( $record, $this->tableRecord );
        notice::donen( $list['noticeid_t'] );
        notice::donet( $list['todoid_t'] );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function __getLibraryName( $id )
    {
        $data = app::loadapp( "adm", "articlesLibrary" )->api_getLibraryData( $id );
        return $data['name'];
    }

    private function __getType( $value )
    {
        if ( $value == "3" )
        {
            return lang( "consuming" );
        }
        if ( $value == "2" )
        {
            return lang( "borrowing" );
        }
        if ( $value == "1" )
        {
            return lang( "restitution" );
        }
    }

    private function __getTypeName( $id )
    {
        $data = app::loadapp( "adm", "articlesInfo" )->api_getTypeData( $id );
        return $data['name'];
    }

    private function __getProductName( $id )
    {
        $data = app::loadapp( "adm", "articlesInfo" )->api_getProductData( $id );
        return $data['name'];
    }

    private function __getUserTruename( $id )
    {
        $data = app::loadapp( "main", "user" )->api_getUserDataByUid( $id );
        return $data['truename'];
    }

}

?>
