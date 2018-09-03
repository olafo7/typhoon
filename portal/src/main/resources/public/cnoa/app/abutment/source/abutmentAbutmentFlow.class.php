<?php
//decode by qq2859470

class abutmentAbutmentFlow extends abutmentAbutment
{

    public function run( )
    {
        global $CNOA_SESSION;
        $task = getpar( $_GET, "task", "loadPage" );
        switch ( $task )
        {
        case "loadPage" :
            global $CNOA_CONTROLLER;
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/abutment_flow.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            break;
        case "saveAbutment" :
            $this->_saveAbutment( );
            break;
        case "getJsonData" :
            $this->_getJsonData( );
            break;
        case "deleteFlow" :
            $this->_deleteFlow( );
            break;
        case "setOpen" :
            $this->_setOpen( );
            break;
        case "getAbutmenData" :
            $this->_getAbutmenData( );
            break;
        case "testWhereSql" :
            $this->_testWhereSql( );
        }
    }

    private function _saveAbutment( )
    {
        global $CNOA_DB;
        $editId = getpar( $_POST, "edit" );
        if ( !empty( $editId ) )
        {
            $this->editAbutment( $editId );
            exit( );
        }
        $data = array( );
        $data['flowId'] = getpar( $_POST, "flowId" );
        $data['sheetId'] = getpar( $_POST, "sheetId" );
        $count = $CNOA_DB->db_getcount( "abutment_flow", "WHERE `flowId`=".$data['flowId']." AND `sheetId`={$data['sheetId']}" );
        if ( 0 < $count )
        {
            msg::callback( FALSE, "数据表已对接流程！" );
        }
        $data['name'] = getpar( $_POST, "name" );
        $count = $CNOA_DB->db_getcount( "abutment_flow", "WHERE `name` = \"".$data['name']."\"" );
        if ( 0 < $count )
        {
            msg::callback( FALSE, "名字已存在！" );
        }
        $data['detailId'] = getpar( $_POST, "detailId", 0 );
        $fieldsAbutment = getpar( $_POST, "fieldsAbutment" );
        $data['map'] = implode( $fieldsAbutment, "," );
        $data['whereSQL'] = getpar( $_POST, "whereSQL", "" );
        $data['outType'] = getpar( $_POST, "outType", "" );
        if ( strchr( $data['map'], "cnoa_check" ) === FALSE )
        {
            msg::callback( FALSE, "未绑定审批字段" );
        }
        $CNOA_DB->db_insert( $data, "abutment_flow" );
        $CNOA_DB->db_update( array( "bindfunction" => "abutment" ), "wf_s_flow", "WHERE `flowId`=".$data['flowId'] );
        $field = $detail = array( );
        $detail['id'] = $data['detailId'];
        $detail['field'] = array( );
        foreach ( $fieldsAbutment as $key => $value )
        {
            $temp = explode( "|", $value );
            if ( $temp[1] == "cnoa_check" )
            {
                $cnoa_check = $temp[0];
            }
            else if ( FALSE !== strpos( $temp[0], "detail_" ) )
            {
                $detailFieldId = str_replace( "detail_", "", $temp[0] );
                $detail['field'][$detailFieldId] = $temp[1];
            }
            else
            {
                $field[$temp[0]] = $temp[1];
            }
        }
        if ( empty( $detail['field'] ) )
        {
            $detail = array( );
        }
        @mkdir( CNOA_PATH_FILE."/common/wf/abutment" );
        $cacheFile = CNOA_PATH_FILE."/common/wf/abutment/".$data['flowId'].".map.php";
        if ( file_exists( $cacheFile ) )
        {
            $map = include_once( $cacheFile );
        }
        else
        {
            $map = array( );
        }
        if ( array_key_exists( $data['sheetId'], $map ) )
        {
            $map[$data['sheetId']]['field'] = $field;
            $map[$data['sheetId']]['detail'] = $detail;
            $map[$data['sheetId']]['whereSQL'] = $data['whereSQL'];
            $map[$data['sheetId']]['outType'] = $data['outType'];
        }
        else
        {
            $map[$data['sheetId']] = array( );
            $map[$data['sheetId']]['open'] = FALSE;
            $map[$data['sheetId']]['field'] = $field;
            $map[$data['sheetId']]['detail'] = $detail;
            $map[$data['sheetId']]['whereSQL'] = $data['whereSQL'];
            $map[$data['sheetId']]['outType'] = $data['outType'];
            $map[$data['sheetId']]['check'] = array(
                "id" => $cnoa_check,
                "idea" => array( "同意", "不同意" )
            );
        }
        $content = "<?php\r\nreturn ".var_export( $map, TRUE ).";\r\n?>";
        file_put_contents( $cacheFile, $content );
        msg::callback( TRUE, "操作成功！" );
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        $sql = "SELECT af.id, af.name, af.flowId, af.sheetId, af.whereSQL, af.outType, af.detailId, af.open, ds.name AS `sheetName`, f.name AS `flowName` \r\n\t\t\t\tFROM `cnoa_abutment_flow` AS `af` LEFT JOIN `cnoa_wf_s_flow` AS `f` ON af.flowId = f.flowId \r\n\t\t\t\tLEFT JOIN `cnoa_abutment_datasheet` AS `ds` ON af.sheetId = ds.id \r\n\t\t\t\tWHERE 1 ORDER BY af.id";
        $result = $CNOA_DB->query( $sql );
        $data = array( );
        while ( $row = $CNOA_DB->get_array( $result ) )
        {
            $data[] = $row;
        }
        ( );
        $ds = new DataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
    }

    private function _deleteFlow( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id" );
        $data = $CNOA_DB->db_getone( array( "sheetId", "flowId" ), "abutment_flow", "WHERE `id`=".$id );
        $mapPath = CNOA_PATH_FILE.( "/common/wf/abutment/".$data['flowId'].".map.php" );
        if ( file_exists( $mapPath ) )
        {
            $map = include_once( $mapPath );
            unset( $map[$data['sheetId']] );
            if ( empty( $map ) )
            {
                @unlink( $mapPath );
                $CNOA_DB->db_update( array( "bindfunction" => "" ), "wf_s_flow", "WHERE `flowId`=".$data['flowId'] );
            }
            else
            {
                $content = "<?php\r\nreturn ".var_export( $map, TRUE ).";\r\n?>";
                file_put_contents( $mapPath, $content );
            }
        }
        $CNOA_DB->db_delete( "abutment_flow", "WHERE `id`=".$id );
        msg::callback( TRUE, "删除成功" );
    }

    private function _setOpen( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id" );
        $data = $CNOA_DB->db_getone( array( "sheetId", "flowId", "open" ), "abutment_flow", "WHERE `id`=".$id );
        $open = $data['open'] == 1 ? 0 : 1;
        $mapPath = CNOA_PATH_FILE.( "/common/wf/abutment/".$data['flowId'].".map.php" );
        if ( file_exists( $mapPath ) )
        {
            $map = include_once( $mapPath );
            $map[$data['sheetId']]['open'] = !$map[$data['sheetId']]['open'];
            $content = "<?php\r\nreturn ".var_export( $map, TRUE ).";\r\n?>";
            file_put_contents( $mapPath, $content );
            $CNOA_DB->db_update( array(
                "open" => $open
            ), "abutment_flow", "WHERE `id`=".$id );
            msg::callback( TRUE, "操作成功！" );
        }
    }

    private function _getAbutmenData( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id" );
        $abutmentFlow = $CNOA_DB->db_getone( "*", "abutment_flow", "WHERE `id`=".$id );
        $sql = "SELECT `id`, `name` FROM `cnoa_wf_s_field` WHERE `flowId`=".$abutmentFlow['flowId'];
        $query = $CNOA_DB->query( $sql );
        $flowFields = $sheetFields = $detailFields = $data = array( );
        while ( $rs = mysql_fetch_array( $query, MYSQL_ASSOC ) )
        {
            $flowFields[$rs['id']] = $rs['name'];
        }
        $sql = "SELECT `id`, `name` FROM `cnoa_wf_s_field_detail` WHERE `flowId`=".$abutmentFlow['flowId'];
        $query = $CNOA_DB->query( $sql );
        while ( $rs = mysql_fetch_array( $query, MYSQL_ASSOC ) )
        {
            $detailFields[$rs['id']] = $rs['name'];
        }
        $map = $CNOA_DB->db_getfield( "map", "abutment_datasheet", "WHERE `id`=".$abutmentFlow['sheetId'] );
        $temp = json_decode( $map, TRUE );
        foreach ( $temp as $value )
        {
            if ( !empty( $value['mapName'] ) )
            {
                $sheetFields[$value['name']] = $value['mapName'];
            }
        }
        $sheetFields['cnoa_check'] = "审批";
        $temp = explode( ",", $abutmentFlow['map'] );
        foreach ( $temp as $value )
        {
            $a = explode( "|", $value );
            if ( FALSE !== strpos( $a[0], "detail_" ) )
            {
                $id = substr( $a[0], 7 );
                $data[] = array(
                    "flowText" => $detailFields[$id],
                    "sheetText" => $sheetFields[$a[1]],
                    "value" => $value
                );
            }
            else
            {
                $data[] = array(
                    "flowText" => $flowFields[$a[0]],
                    "sheetText" => $sheetFields[$a[1]],
                    "value" => $value
                );
            }
        }
        echo json_encode( array(
            "data" => $data
        ) );
    }

    private function editAbutment( $id )
    {
        global $CNOA_DB;
        $data = array( );
        $data['flowId'] = getpar( $_POST, "flowId" );
        $data['sheetId'] = getpar( $_POST, "sheetId" );
        $data['name'] = getpar( $_POST, "name" );
        $data['whereSQL'] = getpar( $_POST, "whereSQL", "" );
        $data['outType'] = getpar( $_POST, "outType", "" );
        $count = $CNOA_DB->db_getcount( "abutment_flow", "WHERE `name`='".$data['name'].( "' AND `id`!=".$id ) );
        if ( 0 < $count )
        {
            msg::callback( FALSE, "名字已存在！" );
        }
        $fieldsAbutment = getpar( $_POST, "fieldsAbutment" );
        $data['map'] = implode( $fieldsAbutment, "," );
        if ( strchr( $data['map'], "cnoa_check" ) === FALSE )
        {
            msg::callback( FALSE, "未绑定审批字段" );
        }
        $CNOA_DB->db_update( $data, "abutment_flow", "WHERE `id`=".$id );
        $field = $detail = array( );
        $detail['id'] = getpar( $_POST, "detailId", 0 );
        $detail['field'] = array( );
        foreach ( $fieldsAbutment as $key => $value )
        {
            $temp = explode( "|", $value );
            if ( $temp[1] == "cnoa_check" )
            {
                $cnoa_check = $temp[0];
            }
            else if ( FALSE !== strpos( $temp[0], "detail_" ) )
            {
                $detailFieldId = str_replace( "detail_", "", $temp[0] );
                $detail['field'][$detailFieldId] = $temp[1];
            }
            else
            {
                $field[$temp[0]] = $temp[1];
            }
        }
        if ( empty( $detail['field'] ) )
        {
            $detail = array( );
        }
        @mkdir( CNOA_PATH_FILE."/common/wf/abutment" );
        $cacheFile = CNOA_PATH_FILE."/common/wf/abutment/".$data['flowId'].".map.php";
        if ( file_exists( $cacheFile ) )
        {
            $map = include_once( $cacheFile );
        }
        else
        {
            $map = array( );
        }
        $map[$data['sheetId']]['field'] = $field;
        $map[$data['sheetId']]['detail'] = $detail;
        $map[$data['sheetId']]['whereSQL'] = $data['whereSQL'];
        $map[$data['sheetId']]['outType'] = $data['outType'];
        $map[$data['sheetId']]['check'] = array(
            "id" => $cnoa_check,
            "idea" => array( "同意", "不同意" )
        );
        $content = "<?php\r\nreturn ".var_export( $map, TRUE ).";\r\n?>";
        file_put_contents( $cacheFile, $content );
        msg::callback( TRUE, "操作成功！" );
    }

    private function _testWhereSql( )
    {
        global $CNOA_DB;
        $sheetId = getpar( $_POST, "sheetId", 0 );
        $where = getpar( $_POST, "whereSQL" );
        $where = preg_replace( "/\\{\\w+\\}/", "'1'", $where );
        $sql = "SELECT db.*, ds.sheet FROM `cnoa_abutment_datasheet` AS ds \r\n\t\t\t\tLEFT JOIN `cnoa_abutment_database` AS db ON ds.sid = db.id \r\n\t\t\t\tWHERE ds.id = ".$sheetId;
        $database = $CNOA_DB->get_one( $sql );
        $db = $this->connectDb( $database );
        $sql = "SELECT * FROM ".$database['sheet']." ".$where;
        if ( $db->query( $sql ) )
        {
            msg::callback( TRUE, "SQL正确" );
        }
        else
        {
            msg::callback( FALSE, "错误的SQL，请检查字段名、表名是否正确，字符串是否已加引号" );
        }
    }

}

?>
