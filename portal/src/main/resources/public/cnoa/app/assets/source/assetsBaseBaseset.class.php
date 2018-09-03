<?php
//decode by qq2859470

class assetsBaseBaseset extends assetsBase
{

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        if ( $task == "loadPage" )
        {
            $this->_loadPage( );
        }
        else if ( $task == "getdrop" )
        {
            $this->_getdrop( );
        }
        else if ( $task == "add" )
        {
            $this->_add( );
        }
        else if ( $task == "delete" )
        {
            $this->_del( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/base/baseset.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    private function _getdrop( )
    {
        $type = getpar( $_GET, "type", "" );
        $this->api_getdrop( $type );
    }

    private function _add( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", 0 );
        $type = getpar( $_POST, "type", 0 );
        $value = getpar( $_POST, "value", "" );
        $regex = "/^0\\.1\$|^0\\.0\\d\$/";
        $typeValue = $CNOA_DB->db_getone( "*", $this->table_dropdown, "WHERE `type`=".$type." AND `value`='{$value}'" );
        if ( $typeValue )
        {
            msg::callback( FALSE, "该名称已存在" );
        }
        if ( empty( $id ) )
        {
            if ( $type == 9 && !preg_match( $regex, $value ) )
            {
                msg::callback( FALSE, "请输入0.01到0.1之间的小数(2位)" );
            }
            $result = $CNOA_DB->db_insert( array(
                "type" => $type,
                "value" => $value
            ), $this->table_dropdown );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 6001, "", "基础设置" );
            if ( $result )
            {
                $response = lang( "successopt" );
            }
            else
            {
                $response = lang( "optFail" );
            }
        }
        else
        {
            if ( $type == 9 && !preg_match( $regex, $value ) )
            {
                msg::callback( FALSE, "请输入0.01到0.1之间的小数(2位)" );
            }
            $result = $CNOA_DB->db_update( array(
                "value" => $value
            ), $this->table_dropdown, "WHERE `id`=".$id );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 6001, "", "基础设置" );
            if ( $result )
            {
                $response = lang( "editSuccess" );
            }
            else
            {
                $response = lang( "editFail" );
            }
        }
        msg::callback( TRUE, $response );
        exit( );
    }

    private function _del( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", 0 );
        if ( empty( $id ) )
        {
            msg::callback( FALSE, lang( "optFail" ) );
        }
        $list = $CNOA_DB->db_select( "*", $this->table_manage, "WHERE `measure`=".$id." OR \r\n\t\t\t\t`source`={$id} OR  `storage`={$id} OR `way`={$id} OR \r\n\t\t\t\t`manufactuer`={$id} OR  `supplier`={$id}" );
        $row = $CNOA_DB->db_select( array( "status" ), $this->table_secondment_list, "WHERE `status` = ".$id );
        if ( !empty( $list ) && !empty( $row ) )
        {
            msg::callback( FALSE, lang( "stopToDel" ) );
        }
        $CNOA_DB->db_delete( $this->table_dropdown, "WHERE `id`=".$id );
        $response = lang( "successopt" );
        msg::callback( TRUE, $response );
        exit( );
    }

}

?>
