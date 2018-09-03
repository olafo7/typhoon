<?php
//decode by qq2859470

class assetsBaseCustom extends assetsBase
{

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadpage( );
            exit( );
        case "getCustom" :
            $this->_getcustom( );
            exit( );
        case "addCustom" :
            $this->_addcustom( );
            exit( );
        case "editCustom" :
            $this->_editcustom( );
            exit( );
        case "delCustom" :
            $this->_delcustom( );
            exit( );
        case "getFieldType" :
            $this->_getFieldType( );
            exit( );
        case "order" :
            $this->_order( );
        }
        exit( );
    }

    private function _loadpage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/base/custom.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    public function _getcustom( )
    {
        global $CNOA_DB;
        $fieldid = getpar( $_POST, "fieldid" );
        $fieldidname = getpar( $_POST, "fieldidname" );
        $valuefieldid = getpar( $_POST, "valuefieldid" );
        $view = getpar( $_POST, "view" );
        $add = getpar( $_POST, "add" );
        $show = getpar( $_POST, "show" );
        $start = ( integer )getpar( $_POST, "start" );
        $row = 15;
        $result = $CNOA_DB->db_select( "*", $this->table_custom, "WHERE 1 ORDER BY `fieldid` DESC" );
        if ( !is_array( $result ) )
        {
            $result = array( );
        }
        $dblist = $CNOA_DB->db_select( array( "fieldId" ), $this->table_select_item, "WHERE `type`=2 GROUP BY fieldId" );
        $fieldids = array( );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $v )
        {
            $fieldids[] = $v['fieldId'];
        }
        $temp = $data = array( );
        foreach ( $result as $v )
        {
            $temp['fieldid'] = $v['fieldid'];
            $temp['fieldname'] = $v['fieldname'];
            $temp['type'] = $v['fieldtype'];
            $temp['fieldtype'] = $this->_getTypeName( $v['fieldtype'] );
            $temp['items'] = in_array( $v['fieldid'], $fieldids ) ? $this->_getCustomContent( $v['fieldid'] ) : "";
            $temp['add'] = $v['add'];
            $temp['show'] = $v['show'];
            $temp['must'] = $v['must'];
            $temp['order'] = $v['order'];
            $data[] = $temp;
        }
        ( );
        $ds = new dataStore( );
        $ds->total = $CNOA_DB->db_getcount( $this->table_custom );
        $ds->data = $data;
        echo $ds->makeJsonData( );
    }

    private function _getTypeName( $type )
    {
        global $CNOA_DB;
        $name = $CNOA_DB->db_getfield( "name", $this->table_field_type, "WHERE `id`=".$type );
        return $name;
    }

    private function _getCustomContent( $fieldid )
    {
        global $CNOA_DB;
        $list = $CNOA_DB->db_select( array( "name" ), $this->table_select_item, "WHERE `fieldId`=".$fieldid." AND `type`=2 AND `status`=1" );
        if ( !is_array( $list ) )
        {
            $list = array( );
        }
        if ( !empty( $list ) )
        {
            $str = "";
            foreach ( $list as $value )
            {
                $str .= $value['name']."\n";
            }
            return $str;
        }
    }

    private function _addcustom( )
    {
        global $CNOA_DB;
        $data['fieldname'] = getpar( $_POST, "fieldname" );
        $data['fieldtype'] = getpar( $_POST, "fieldtype" );
        $data['add'] = getpar( $_POST, "add" );
        $data['show'] = getpar( $_POST, "show" );
        $data['must'] = getpar( $_POST, "must" );
        $data['order'] = getpar( $_POST, "order" );
        $items = getpar( $_POST, "items" );
        $fieldname = $CNOA_DB->db_getfield( "fieldname", $this->table_custom, "WHERE `fieldname` = '".$data['fieldname']."'" );
        if ( !empty( $fieldname ) )
        {
            msg::callback( FALSE, lang( "beenAroundPleaseChange" ) );
        }
        $fieldtype = $CNOA_DB->db_getone( array( "type", "length" ), $this->table_field_type, "WHERE `id` = ".$data['fieldtype'] );
        $insertID = $CNOA_DB->db_insert( $data, $this->table_custom );
        if ( $fieldtype['length'] )
        {
            $sql = "ALTER TABLE ".tname( $this->table_manage ).( " ADD `field".$insertID."` " ).$fieldtype['type'].( "(".$fieldtype['length'].") NOT NULL" );
        }
        else
        {
            $sql = "ALTER TABLE ".tname( $this->table_manage ).( " ADD `field".$insertID."` " ).$fieldtype['type']." NOT NULL";
        }
        $CNOA_DB->query( $sql );
        if ( $data['fieldtype'] == 6 && empty( $items ) )
        {
            msg::callback( FALSE, "选项不能为空" );
        }
        if ( $data['fieldtype'] == 8 && empty( $items ) )
        {
            msg::callback( FALSE, "选项不能为空" );
        }
        if ( ( $data['fieldtype'] == 6 || $data['fieldtype'] == 8 ) && isset( $items ) )
        {
            $items = explode( "\n", $items );
            foreach ( $items as $v )
            {
                if ( !empty( $v ) )
                {
                    $v = str_replace( array( "\r", "\n" ), "", $v );
                    if ( !empty( $v ) )
                    {
                        $sql2 = "INSERT INTO ".tname( $this->table_select_item ).( " (`name`, `fieldId`, `type`) VALUES ('".$v."', '{$insertID}', '2')" );
                        $CNOA_DB->query( $sql2 );
                    }
                }
            }
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 6004, "", "自定义字段" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _editcustom( )
    {
        global $CNOA_DB;
        $fieldid = getpar( $_POST, "fieldid" );
        $fieldname = getpar( $_POST, "fieldname" );
        $fieldvalue = getpar( $_POST, "value" );
        if ( $fieldname == "add" || $fieldname == "show" || $fieldname == "must" )
        {
            $sql = "UPDATE ".tname( $this->table_custom )." SET `".$fieldname."`='".$fieldvalue."' WHERE `fieldid` = ".$fieldid;
            $CNOA_DB->query( $sql );
        }
        else
        {
            $dblist = $CNOA_DB->db_select( array( "id", "name" ), $this->table_select_item, "WHERE `fieldId`=".$fieldid." AND `status`=1 AND `type`=2" );
            if ( !is_array( $dblist ) )
            {
                $dblist = array( );
            }
            $existItems = array( );
            foreach ( $dblist as $v )
            {
                $existItems[$v['id']] = $v['name'];
            }
            $items = explode( "\n", $fieldvalue );
            $values = array( );
            foreach ( $items as $v )
            {
                if ( !empty( $v ) )
                {
                    $v = str_replace( array( "\r", "\n" ), "", $v );
                    if ( $key = array_search( $v, $existItems ) )
                    {
                        unset( $existItems[$key] );
                    }
                    else
                    {
                        $values[] = "('".$v."', {$fieldid}, 2)";
                    }
                }
            }
            if ( !empty( $existItems ) )
            {
                $removeItems = implode( ",", array_keys( $existItems ) );
                $CNOA_DB->db_update( array( "status" => 0 ), $this->table_select_item, "WHERE `id` IN(".$removeItems.")" );
                app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 6004, "", "自定义字段" );
            }
            if ( !empty( $values ) )
            {
                $sql = "INSERT INTO ".tname( $this->table_select_item )." (`name`, `fieldId`, `type`) VALUES ".implode( ",", $values );
                $CNOA_DB->query( $sql );
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _delcustom( )
    {
        global $CNOA_DB;
        $fieldid = getpar( $_POST, "fieldid" );
        if ( isinformat( $fieldid ) )
        {
            if ( $CNOA_DB->db_delete( $this->table_custom, "WHERE `fieldid` = '".$fieldid."'" ) )
            {
                $sql = "ALTER TABLE".tname( $this->table_manage ).( " DROP field".$fieldid );
                $CNOA_DB->query( $sql );
                $row = $CNOA_DB->db_select( array( "id" ), $this->table_select_item, "WHERE `fieldId` = '".$fieldid."'" );
                if ( !is_array( $row ) )
                {
                    $row = array( );
                }
                if ( !empty( $row ) )
                {
                    $CNOA_DB->db_delete( $this->table_select_item, "WHERE fieldId = ".$fieldid );
                }
                app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 6004, "", "自定义字段" );
                msg::callback( TRUE, lang( "delSuccess" ) );
            }
            else
            {
                msg::callback( FALSE, lang( "delFail" ) );
            }
        }
    }

    private function _getFieldType( )
    {
        global $CNOA_DB;
        $list = $CNOA_DB->db_select( array( "id", "name" ), $this->table_field_type );
        $temp = $data = array( );
        if ( !is_array( $list ) )
        {
            $list = array( );
        }
        foreach ( $list as $v )
        {
            $temp['fieldtype'] = $v['id'];
            $temp['fieldtypeName'] = $v['name'];
            $data[] = $temp;
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
    }

    private function _order( )
    {
        global $CNOA_DB;
        $fieldid = getpar( $_POST, "fieldid" );
        $value = getpar( $_POST, "value" );
        $sql = "UPDATE ".tname( $this->table_custom )." SET `order`='".$value."' WHERE `fieldid` = ".$fieldid;
        if ( $result = $CNOA_DB->query( $sql ) )
        {
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 6004, "", "自定义字段" );
            msg::callback( TRUE, lang( "successopt" ) );
        }
    }

}

?>
