<?php
//decode by qq2859470

class jxcBaseBindflow extends jxcBase
{

    const FIXTYPE = 1;
    const CUSTOMTYPE = 2;

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            exit( );
        case "getFlowList" :
            $this->_getFlowList( );
            exit( );
        case "getBindFlowList" :
            $this->_getBindFlowList( );
            exit( );
        case "submitBindFlow" :
            $this->_submitBindFlow( );
            exit( );
        case "delBindflow" :
            $this->_delBindflow( );
            exit( );
        case "setStatus" :
            $this->_setStatus( );
            exit( );
        case "getFlowFields" :
            $this->_getFlowFields( );
            exit( );
        case "getBindFieldList" :
            $this->_getBindFieldList( );
            exit( );
        case "submitBindFlowField" :
            $this->_submitBindFlowField( );
        }
        exit( );
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/base/bindflow.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    private function _getFlowList( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "flowId", "name", "sortId" ), "wf_s_flow", "WHERE `status` = 1 AND `tplSort`=0 ORDER BY `sortId` ASC, `name` ASC" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array(
            array( "flowId" => "0", "name" => "未绑定流程" )
        );
        include_once( CNOA_PATH."/app/wf/inc/wfCommon.class.php" );
        $sortList = app::loadapp( "wf", "flowSetSort" )->api_getSortList( );
        foreach ( $dblist as $k => $v )
        {
            $v['name'] = "[".$sortList[$v['sortId']]['name']."] ".$v['name'];
            $data[] = $v;
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
    }

    private function _getBindFlowList( )
    {
        global $CNOA_DB;
        $model = array( 3 => "入库业务", 4 => "出库业务" );
        $sql = "SELECT `b`.*, `f`.`name` AS `flowName` FROM ".tname( $this->table_bindflow )." AS `b` LEFT JOIN ".tname( "wf_s_flow" )." AS `f` ON `f`.`flowId`=`b`.`flowId` ";
        $result = $CNOA_DB->query( $sql );
        $data = array( );
        while ( $row = $CNOA_DB->get_array( $result ) )
        {
            $row['fromName'] = $model[$row['mid']];
            $data[] = $row;
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
    }

    private function _submitBindFlow( )
    {
        global $CNOA_DB;
        $name = getpar( $_POST, "name" );
        if ( empty( $name ) )
        {
            msg::callback( FALSE, lang( "nameNoEmpty" ) );
        }
        $mid = getpar( $_POST, "mid" );
        if ( empty( $mid ) )
        {
            msg::callback( FALSE, lang( "belongFunNotEmpty" ) );
        }
        $flowId = getpar( $_POST, "flowId" );
        if ( empty( $flowId ) )
        {
            msg::callback( FALSE, lang( "bindingflowNotEmpty" ) );
        }
        $data['name'] = $name;
        $data['mid'] = $mid;
        $data['flowId'] = $flowId;
        $id = intval( getpar( $_POST, "id" ) );
        if ( empty( $id ) )
        {
            if ( $CNOA_DB->db_getfield( "id", $this->table_bindflow, "WHERE `mid`=".$mid ) )
            {
                msg::callback( FALSE, lang( "notBindingMore" ) );
            }
            $CNOA_DB->db_insert( $data, $this->table_bindflow );
            $CNOA_DB->db_update( array(
                "bindfunction" => $this->__getCodeByMid( $mid )
            ), "wf_s_flow", "WHERE `flowId`=".$flowId );
            msg::callback( TRUE, lang( "addBindingSuccess" ) );
        }
        else
        {
            $oldFlowId = $CNOA_DB->db_getfield( "flowId", $this->table_bindflow, "WHERE `id`=".$id );
            if ( $oldFlowId != $flowId )
            {
                $this->__delBindFields( $id );
                $CNOA_DB->db_update( array( "bindfunction" => "" ), "wf_s_flow", "WHERE `flowId`=".$oldFlowId );
                $CNOA_DB->db_update( array(
                    "bindfunction" => $this->__getCodeByMid( $mid )
                ), "wf_s_flow", "WHERE `flowId`=".$flowId );
            }
            $CNOA_DB->db_update( $data, $this->table_bindflow, "WHERE `id`=".$id );
            msg::callback( TRUE, lang( "modifyBindingSuccess" ) );
        }
    }

    private function __getCodeByMid( $mid )
    {
        switch ( $mid )
        {
        case 3 :
            return "JxcRuku";
        case 4 :
            return "JxcChuku";
        }
    }

    private function _delBindflow( )
    {
        global $CNOA_DB;
        $id = intval( getpar( $_POST, "id" ) );
        if ( !empty( $id ) )
        {
            $this->__delBindFields( $id );
            $CNOA_DB->db_delete( $this->table_bindflow, "WHERE `id`=".$id );
            msg::callback( TRUE, lang( "bdFlowDelSucc" ) );
        }
        msg::callback( FALSE, lang( "bdFlowDelFail" ) );
    }

    private function _setStatus( )
    {
        global $CNOA_DB;
        $id = intval( getpar( $_POST, "id" ) );
        $sql = "UPDATE ".tname( $this->table_bindflow ).( " SET  `status` =  !`status` WHERE  `id`=".$id );
        $CNOA_DB->query( $sql );
        msg::callback( TRUE, lang( "editSuccess" ) );
    }

    private function _getFlowFields( )
    {
        global $CNOA_DB;
        $id = intval( getpar( $_POST, "id" ) );
        $flowId = $CNOA_DB->db_getfield( "flowId", $this->table_bindflow, "WHERE `id`=".$id );
        $flowId = intval( $flowId );
        $dblist = $CNOA_DB->db_select( array( "id", "name", "otype" ), "wf_s_field", "WHERE `flowId`=".$flowId );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        array_unshift( &$dblist, array( "id" => 0, "未选择控件" ) );
        ( );
        $ds = new dataStore( );
        $ds->data = $dblist;
        echo $ds->makeJsonData( );
    }

    private function _getBindFieldList( )
    {
        global $CNOA_DB;
        $id = intval( getpar( $_POST, "id" ) );
        $bindInfo = $CNOA_DB->db_getone( array( "mid", "flowId" ), $this->table_bindflow, "WHERE `id`=".$id );
        $fields = array( );
        ( self::CUSTOM_FIELD_CODE );
        $cf = new customField( );
        $fixFields = $cf->getFieldsNameByMid( $bindInfo['mid'], "fix", FALSE, TRUE );
        $customFields = $cf->getFieldsNameByMid( $bindInfo['mid'], "custom", FALSE, TRUE );
        if ( !is_array( $fixFields ) )
        {
            $fixFields = array( );
        }
        if ( !is_array( $customFields ) )
        {
            $customFields = array( );
        }
        $dblist = $CNOA_DB->db_select( array( "type", "fieldId", "widgetId" ), $this->table_bindfield, "WHERE `flowId`=".$bindInfo['flowId']." AND `mid`={$bindInfo['mid']}" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $fixMapFields = $customMapFields = array( );
        foreach ( $dblist as $v )
        {
            if ( $v['type'] == self::FIXTYPE )
            {
                $fixMapFields[$v['fieldId']] = $v['widgetId'];
            }
            else if ( $v['type'] == self::CUSTOMTYPE )
            {
                $customMapFields[$v['fieldId']] = $v['widgetId'];
            }
        }
        foreach ( $fixFields as $v )
        {
            $v['type'] = self::FIXTYPE;
            $v['widgetId'] = $fixMapFields[$v['fieldId']];
            $fields[] = $v;
        }
        foreach ( $customFields as $v )
        {
            $v['type'] = self::CUSTOMTYPE;
            $v['widgetId'] = $customMapFields[$v['fieldId']];
            $fields[] = $v;
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $fields;
        echo $ds->makeJsonData( );
    }

    private function _submitBindFlowField( )
    {
        global $CNOA_DB;
        $id = intval( getpar( $_POST, "id" ) );
        $bindInfo = $CNOA_DB->db_getone( array( "mid", "flowId" ), $this->table_bindflow, "WHERE `id`=".$id );
        $mid = $bindInfo['mid'];
        $flowId = $bindInfo['flowId'];
        $data = json_decode( $_POST['data'], TRUE );
        if ( is_array( $data ) )
        {
            $values = $delId = array( );
            foreach ( $data as $v )
            {
                if ( empty( $v['widgetId'] ) )
                {
                    $delId[$v['type']][] = $v['fieldId'];
                }
                else
                {
                    $values[] = "(".$v['type'].", {$v['fieldId']}, {$mid}, {$flowId}, {$v['widgetId']})";
                }
            }
            if ( 0 < count( $values ) )
            {
                $values = implode( ",", $values );
                $sql = "REPLACE INTO ".tname( $this->table_bindfield ).( "(`type`, `fieldId`, `mid`, `flowId`, `widgetId`) VALUES ".$values );
                $CNOA_DB->query( $sql );
            }
            foreach ( $delId as $type => $ids )
            {
                if ( 0 < count( $ids ) )
                {
                    $CNOA_DB->db_delete( $this->table_bindfield, "WHERE fieldId IN (".implode( ",", $ids ).") AND type=".$type );
                }
            }
        }
        msg::callback( TRUE, lang( "bindSucess" ) );
    }

    private function __delBindFields( $id )
    {
        global $CNOA_DB;
        $bindInfo = $CNOA_DB->db_getone( array( "mid", "flowId" ), $this->table_bindflow, "WHERE `id`=".$id );
        $CNOA_DB->db_delete( $this->table_bindfield, "WHERE `mid`=".$bindInfo['mid']." AND `flowId`={$bindInfo['flowId']}" );
    }

}

?>
