<?php
//decode by qq2859470

class abutmentAbutmentSheet extends abutmentAbutment
{

    private $access_token = "";
    private $signPackage = "";

    public function run( )
    {
        global $CNOA_SESSION;
        $task = getpar( $_GET, "task", "loadPage" );
        switch ( $task )
        {
        case "loadPage" :
            global $CNOA_CONTROLLER;
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/abutment_sheet.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            break;
        case "getDatabase" :
            $this->_getDatabase( );
            break;
        case "saveDatasheet" :
            $this->_saveDatasheet( );
            break;
        case "getDatasheet" :
            $this->_getDatasheet( );
            break;
        case "getDbFieldsName" :
            $this->_getDbFieldsName( );
            break;
        case "getDbTablesName" :
            $this->_getDbTablesName( );
            break;
        case "updateMapName" :
            $this->_updateMapName( );
            break;
        case "updateSheetName" :
            $this->_updateSheetName( );
            break;
        case "getSheetFields" :
            $this->_getSheetFields( );
            break;
        case "deleteSheet" :
            $this->_deleteSheet( );
        }
    }

    private function _getDatabase( )
    {
        global $CNOA_DB;
        $data = $CNOA_DB->db_select( "*", "abutment_database", "WHERE 1" );
        ( );
        $ds = new dataStore( );
        $ds->total = count( $data );
        $ds->data = $data;
        echo $ds->makeJsonData( );
    }

    private function _getDatasheet( )
    {
        global $CNOA_DB;
        $sql = "SELECT s.id, s.sid, s.sheet, s.name, b.name AS `database` FROM `cnoa_abutment_datasheet` AS s \r\n\t\t\t\tLEFT JOIN `cnoa_abutment_database` AS b ON b.id = s.sid WHERE 1";
        $result = $CNOA_DB->query( $sql );
        $data = array( );
        while ( $rs = $CNOA_DB->get_array( $result ) )
        {
            $data[] = $rs;
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        $ds->total = count( $data );
        echo $ds->makeJsonData( );
    }

    private function _getDbFieldsName( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", 0 );
        $json = $CNOA_DB->db_getfield( "map", "abutment_datasheet", "WHERE `id`=".$id );
        if ( !empty( $json ) )
        {
            $map = json_decode( $json, TRUE );
        }
        else
        {
            $databaseID = getpar( $_POST, "sid", 0 );
            $sheet = getpar( $_POST, "sheet", "" );
            $map = $this->api_getDbFieldName( $databaseID, $sheet );
            $json = $this->encode_json( $map );
            $CNOA_DB->select_db( CNOA_DB_NAME );
            $CNOA_DB->db_update( array(
                "map" => $json
            ), "abutment_datasheet", "WHERE `id`=".$id );
        }
        foreach ( $map as $key => $value )
        {
            if ( $value['isnullable'] == 0 )
            {
                $map[$key]['name'] .= "(not null)";
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $map;
        echo $ds->makeJsonData( );
    }

    private function _getDbTablesName( )
    {
        global $CNOA_DB;
        $databaseID = getpar( $_POST, "id", 0 );
        $database = $CNOA_DB->db_getone( "*", "abutment_database", "WHERE `id`=".$databaseID );
        $db = $this->connectDb( $database );
        $result = $db->db_getTablesName( );
        ( );
        $ds = new dataStore( );
        $ds->data = $result;
        $ds->total = count( $data );
        echo $ds->makeJsonData( );
    }

    private function _saveDatasheet( )
    {
        global $CNOA_DB;
        $sid = getpar( $_POST, "id", 0 );
        $sheet = getpar( $_POST, "sheet", 0 );
        $name = getpar( $_POST, "name", "" );
        $id = getpar( $_POST, "edit", 0 );
        $count = $CNOA_DB->db_getcount( "abutment_datasheet", "WHERE `sid`=".$sid." AND `sheet`='{$sheet}'".( empty( $id ) ? "" : "AND `id`!=".$id ) );
        if ( $count )
        {
            msg::callback( FALSE, "数据表已存在" );
        }
        if ( !empty( $id ) )
        {
            $CNOA_DB->db_update( array(
                "sid" => $sid,
                "sheet" => $sheet,
                "name" => $name
            ), "abutment_datasheet", "WHERE `id`=".$id );
        }
        else
        {
            $CNOA_DB->db_insert( array(
                "sid" => $sid,
                "sheet" => $sheet,
                "name" => $name
            ), "abutment_datasheet" );
        }
        msg::callback( TRUE, "添加成功" );
    }

    private function _updateMapName( )
    {
        global $CNOA_DB;
        $fid = getpar( $_POST, "fid" );
        $row = getpar( $_POST, "row", 0 );
        $json = $CNOA_DB->db_getfield( "map", "abutment_datasheet", "WHERE `id`=".$fid );
        $map = json_decode( $json, TRUE );
        $map[$row]['mapName'] = getpar( $_POST, "value" );
        $json = $this->encode_json( $map );
        $json = addslashes( $json );
        $CNOA_DB->db_update( array(
            "map" => $json
        ), "abutment_datasheet", "WHERE `id`=".$fid );
        msg::callback( TRUE, "操作成功" );
    }

    private function _updateSheetName( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id" );
        $field = getpar( $_POST, "field", 0 );
        $value = getpar( $_POST, "value", 0 );
        $CNOA_DB->db_update( array(
            $field => $value
        ), "abutment_datasheet", "WHERE `id`=".$id );
        msg::callback( TRUE, "操作成功！" );
    }

    private function _getSheetFields( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id" );
        $map = $CNOA_DB->db_getfield( "map", "abutment_datasheet", "WHERE `id`=".$id );
        $fields = json_decode( $map, TRUE );
        foreach ( $fields as $key => $value )
        {
            if ( empty( $value['mapName'] ) )
            {
                unset( $fields[$key] );
            }
        }
        $fields[] = array( "id" => 0, "name" => "cnoa_check", "mapName" => "审批" );
        echo $this->encode_json( array(
            "fields" => $fields
        ) );
    }

    private function _deleteSheet( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id" );
        $count = $CNOA_DB->db_getcount( "abutment_flow", "WHERE `sheetId`=".$id );
        if ( 0 < $count )
        {
            msg::callback( FALSE, "存在此数据表的流程对接，请先删除" );
        }
        $CNOA_DB->db_delete( "abutment_datasheet", "WHERE `id`=".$id );
        msg::callback( TRUE, "操作成功！" );
    }

}

?>
