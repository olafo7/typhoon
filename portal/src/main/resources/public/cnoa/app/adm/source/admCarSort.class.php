<?php
//decode by qq2859470

class admCarSort extends model
{

    private $table_sort = "adm_car_sort";

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        if ( $task == "loadPage" )
        {
            $this->_loadPage( );
        }
        else if ( $task == "getJsonList" )
        {
            $this->_getJsonList( );
        }
        else if ( $task == "addSort" )
        {
            $this->_addSort( );
        }
        else if ( $task == "delete" )
        {
            $this->_delete( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/car/sort.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _getJsonList( )
    {
        global $CNOA_DB;
        $type = getpar( $_GET, "type", "" );
        $dblist = $CNOA_DB->db_select( "*", $this->table_sort, "where `type`='".$type."'" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _addSort( )
    {
        global $CNOA_DB;
        $tid = getpar( $_POST, "tid", 0 );
        $type = getpar( $_POST, "type", 0 );
        $value = getpar( $_POST, "value", "" );
        $typeValue = $CNOA_DB->db_getone( "*", $this->table_sort, "WHERE `type`=".$type." AND `value`='{$value}'" );
        if ( $typeValue )
        {
            msg::callback( FALSE, "该名称已存在" );
        }
        if ( empty( $tid ) )
        {
            $result = $CNOA_DB->db_insert( array(
                "type" => $type,
                "value" => $value
            ), $this->table_sort );
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
            $result = $CNOA_DB->db_update( array(
                "value" => $value
            ), $this->table_sort, "WHERE `tid`=".$tid );
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

    private function _delete( )
    {
        global $CNOA_DB;
        $tid = getpar( $_POST, "tid", 0 );
        if ( empty( $tid ) )
        {
            msg::callback( FALSE, lang( "optFail" ) );
        }
        $CNOA_DB->db_delete( $this->table_sort, "WHERE `tid`=".$tid );
        $response = lang( "successopt" );
        msg::callback( TRUE, $response );
        exit( );
    }

}

?>
