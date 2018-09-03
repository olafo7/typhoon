<?php
//decode by qq2859470

class admAmmanageSet extends model
{

    private $t_set_dep = "adm_ammanage_set_dep";
    private $t_set_type = "adm_ammanage_set_type";
    private $t_set_add = "adm_ammanage_set_add";
    private $t_set_user = "adm_ammanage_set_user";

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        if ( $task == "loadPage" )
        {
            $this->_loadPage( );
        }
        else if ( $task == "update" )
        {
            $this->_update( );
        }
        else if ( $task == "list" )
        {
            $this->_list( );
        }
        else if ( $task == "delete" )
        {
            $this->_delete( );
        }
        else if ( $task == "submitDep" )
        {
            $this->_submitDep( );
        }
        else if ( $task == "loadDep" )
        {
            $this->_loadDep( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/ammanage/set.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _update( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", 0 );
        $data['name'] = getpar( $_POST, "name" );
        $data['order'] = getpar( $_POST, "order" );
        if ( $id == 0 )
        {
            $CNOA_DB->db_insert( $data, $this->__formatTable( ) );
        }
        else
        {
            $CNOA_DB->db_update( $data, $this->__formatTable( ), "WHERE `id`='".$id."'" );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _list( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->__formatTable( ), "ORDER BY `order` ASC" );
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

    private function __formatTable( )
    {
        $type = getpar( $_POST, "type", "add" );
        switch ( $type )
        {
        case "type" :
            $table = $this->t_set_type;
            return $table;
        case "user" :
            $table = $this->t_set_user;
            return $table;
        case "add" :
            $table = $this->t_set_add;
        }
        return $table;
    }

    private function _delete( )
    {
        global $CNOA_DB;
        $ids = getpar( $_POST, "ids", NULL );
        if ( $ids )
        {
            $ids = explode( ",", substr( $ids, 0, -1 ) );
            if ( is_array( $ids ) )
            {
                foreach ( $ids as $v )
                {
                    $CNOA_DB->db_delete( $this->__formatTable( ), "WHERE `id`='".$v."'" );
                }
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _submitDep( )
    {
        global $CNOA_DB;
        $Depreciation_way = getpar( $_POST, "Depreciation_way", 0 );
        $Salvage_value = getpar( $_POST, "Salvage_value", 0 );
        $num = $CNOA_DB->db_getcount( $this->t_set_dep );
        if ( $num < 1 )
        {
            $CNOA_DB->db_insert( array(
                "Salvage_value" => $Salvage_value,
                "Depreciation_way" => $Depreciation_way
            ), $this->t_set_dep );
        }
        else
        {
            $CNOA_DB->db_update( array(
                "Salvage_value" => $Salvage_value,
                "Depreciation_way" => $Depreciation_way
            ), $this->t_set_dep, "WHERE `id` = '1'" );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadDep( )
    {
        global $CNOA_DB;
        $data = $CNOA_DB->db_select( "*", $this->t_set_dep, "WHERE `id` = '1'" );
        $dblist['Salvage_value'] = $data[0]['Salvage_value'];
        $dblist['Depreciation_way'] = $data[0]['Depreciation_way'];
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    public function api_getAdmUserdepStore( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->t_set_user );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['name'] = $v['name'];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    public function api_getAdmTypeStore( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->t_set_type );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['name'] = $v['name'];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    public function api_getAdmAddStore( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->t_set_add );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['name'] = $v['name'];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

}

?>
