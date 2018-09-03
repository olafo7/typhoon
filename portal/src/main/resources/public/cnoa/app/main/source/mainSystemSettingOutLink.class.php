<?php
//decode by qq2859470

class mainSystemSettingOutLink
{

    private $table = "system_outlink";

    public function run( )
    {
        $task = getpar( $_GET, "task", getpar( $_POST, "task" ) );
        switch ( $task )
        {
        case "getList" :
            $this->_getList( );
            break;
        case "submit" :
            $this->_submit( );
            break;
        case "delete" :
            $this->_delete( );
        }
    }

    private function _getList( )
    {
        global $CNOA_DB;
        $where = "WHERE 1 ORDER BY `order` ASC";
        $dbList = $CNOA_DB->db_select( "*", $this->table, $where );
        if ( !is_array( $dbList ) )
        {
            $dbList = array( );
        }
        foreach ( $dbList as $k => $v )
        {
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = count( $dbList );
        $dataStore->data = $dbList;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getDeskTopList( )
    {
        global $CNOA_DB;
        $where = "WHERE 1 ORDER BY `order` ASC";
        $dbList = $CNOA_DB->db_select( "*", $this->table, $where );
        if ( !is_array( $dbList ) )
        {
            $dbList = array( );
        }
        foreach ( $dbList as $k => $v )
        {
            if ( $k % 3 == 0 )
            {
                $temp[0][] = $v;
            }
            if ( $k % 3 == 1 )
            {
                $temp[1][] = $v;
            }
            if ( $k % 3 == 2 )
            {
                $temp[2][] = $v;
            }
        }
        count( $temp[0] );
        foreach ( $temp[0] as $k => $v )
        {
            $temp2['name1'] = $v['name'];
            $temp2['link1'] = $v['link'];
            $temp2['name2'] = $temp[1][$k]['name'];
            $temp2['link2'] = $temp[1][$k]['link'];
            $temp2['name3'] = $temp[2][$k]['name'];
            $temp2['link3'] = $temp[2][$k]['link'];
            $data[] = $temp2;
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _submit( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", 0 );
        $data = array( );
        $data['name'] = getpar( $_POST, "name", 0 );
        $data['link'] = getpar( $_POST, "link", 0 );
        $data['order'] = getpar( $_POST, "order", 0 );
        if ( $id != 0 )
        {
            $CNOA_DB->db_update( $data, $this->table, "WHERE `id`='".$id."'" );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 91, $data['name'], lang( "link" ) );
        }
        else
        {
            $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $CNOA_DB->db_insert( $data, $this->table );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 91, $data['name'], lang( "link" ) );
        }
        msg::callback( TRUE, lang( "successopt" ) );
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
                    $name = $CNOA_DB->db_getfield( "name", $this->table, "WHERE `id`='".$v."'" );
                    $CNOA_DB->db_delete( $this->table, "WHERE `id`='".$v."'" );
                    app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 91, $name, lang( "outLink" ) );
                }
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function api_getOutLinkList( )
    {
        $from = getpar( $_GET, "from", "normal" );
        if ( $from == "desktop" )
        {
            $this->_getDeskTopList( );
        }
        else
        {
            $this->_getList( );
        }
    }

}

?>
