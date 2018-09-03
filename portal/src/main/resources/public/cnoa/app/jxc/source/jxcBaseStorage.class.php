<?php
//decode by qq2859470

class jxcBaseStorage extends jxcBase
{

    protected $modelId = 2;

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            exit( );
        case "getStorageCustomField" :
            $this->getCustomField( );
            exit( );
        case "getStorage" :
            $this->_getStorage( );
            exit( );
        case "getStorageList" :
            $this->_getStorageList( );
            exit( );
        case "editStorage" :
            $this->_editStorage( );
            exit( );
        case "delStorage" :
            $this->_delStorage( );
            exit( );
        case "getComboStore" :
            $this->_getComboStore( );
            exit( );
        case "getGoodsSorts" :
            $this->_getGoodsSorts( );
            exit( );
        case "getGoods" :
            $this->_getGoods( );
        }
        exit( );
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/base/storage.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    private function _getGoodsSorts( )
    {
        $id = getpar( $_POST, "sid", 0 );
        if ( empty( $id ) )
        {
            $sorts = $this->api_getsorts( );
            if ( $sorts )
            {
                echo json_encode( $sorts );
            }
            else
            {
                msg::callback( FALSE, lang( "setGoodFL" ) );
            }
        }
        else
        {
            $sorts = $this->api_getSortsBySid( $id );
            ( );
            $dataStore = new dataStore( );
            $dataStore->data = $sorts;
            echo $dataStore->makeJsonData( );
        }
    }

    private function _getStorage( )
    {
        $storages = $this->api_getStorage( );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $storages;
        echo $dataStore->makeJsonData( );
    }

    private function _getStorageList( )
    {
        global $CNOA_DB;
        $start = getpar( $_POST, "start", 0 );
        $limit = getpar( $_POST, "limit", 15 );
        ( self::CUSTOM_FIELD_CODE );
        $cf = new customField( );
        $cf->setAllComboItem( $this->modelId );
        $cf->setCustomFieldType( $this->modelId );
        $dblist = $CNOA_DB->db_select( "*", $this->table_storage, " WHERE 1 ORDER BY `id` DESC LIMIT ".$start.", {$limit}" );
        $sorts = $this->api_getsorts( TRUE );
        $sid2sortname = create_function( "&\$value, \$key, \$sorts", "\$value = \$sorts[\$value];" );
        $fields = $sids = array( );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $key => $value )
        {
            $value['managerName'] = $this->getUserNames( $value['manager'] );
            $value = $cf->transformCustomFieldData( $value );
            $sids = explode( ",", $value['sids'] );
            array_walk( &$sids, $sid2sortname, $sorts );
            $value['sortNames'] = implode( ",", $sids );
            $fields[] = $value;
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $fields;
        $ds->total = $CNOA_DB->db_getcount( $this->table_storage );
        echo $ds->makeJsonData( );
    }

    private function _editStorage( )
    {
        global $CNOA_DB;
        $storagename = getpar( $_POST, "storagename" );
        if ( empty( $storagename ) )
        {
            msg::callback( FALSE, lang( "warehouseNotEmpty" ) );
        }
        $manager = getpar( $_POST, "manager" );
        if ( empty( $manager ) )
        {
            msg::callback( FALSE, lang( "ckAdminNotEmpty" ) );
        }
        $data['storagename'] = $storagename;
        $data['manager'] = $manager;
        ( self::CUSTOM_FIELD_CODE );
        $cf = new customField( );
        $fieldNames = $cf->getAllCustomFieldsByMid( $this->modelId );
        $sids = array( );
        foreach ( $GLOBALS['_POST'] as $k => $v )
        {
            if ( array_key_exists( $k, $fieldNames ) )
            {
                $data[$k] = getpar( $_POST, $k, "" );
                $data[$k] = $cf->formatCustomFieldData( $fieldNames[$k]['fieldtype'], $data[$k] );
            }
            if ( !preg_match( "/^sid_(\\d+)\$/", $k, $match ) && !( $v == "on" ) )
            {
                $sids[] = $match[1];
            }
        }
        $data['sids'] = implode( ",", $sids );
        $id = intval( getpar( $_POST, "id" ) );
        if ( empty( $id ) )
        {
            $CNOA_DB->db_insert( $data, $this->table_storage );
            msg::callback( TRUE, lang( "successopt" ) );
        }
        else
        {
            $CNOA_DB->db_update( $data, $this->table_storage, "WHERE `id`=".$id );
            msg::callback( TRUE, lang( "editSuccess" ) );
        }
    }

    private function _delStorage( )
    {
        global $CNOA_DB;
        $ids = getpar( $_POST, "ids", 0 );
        if ( !empty( $ids ) )
        {
            $result = $CNOA_DB->db_select( array( "storagename" ), $this->table_storage, "WHERE `id` IN (".$ids.")" );
            $storageName = array( );
            foreach ( $result as $value )
            {
                $storagename[] = $value['storagename'];
            }
            $CNOA_DB->db_delete( $this->table_storage, "WHERE `id` IN (".$ids.")" );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 210, implode( ",", $storagename ), "仓库" );
        msg::callback( TRUE, lang( "delSuccess" ) );
    }

    protected function getUserNames( $uids )
    {
        global $CNOA_DB;
        if ( !empty( $uids ) )
        {
            $list = $CNOA_DB->db_select( array( "truename" ), "main_user", "WHERE `uid` IN (".$uids.")" );
        }
        if ( !is_array( $list ) )
        {
            $list = array( );
        }
        $data = array( );
        foreach ( $list as $value )
        {
            $data[] = $value['truename'];
        }
        $userName = implode( ",", $data );
        return $userName;
    }

}

?>
