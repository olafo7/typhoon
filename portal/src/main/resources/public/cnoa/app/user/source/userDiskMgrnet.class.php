<?php
//decode by qq2859470

class userDiskMgrnet extends model
{

    private $table = "user_disk_user";
    private $table_list = "user_disk_config";

    public function run( )
    {
        global $CNOA_SESSION;
        $task = getpar( $_GET, "task", "loadpage" );
        switch ( $task )
        {
        case "loadpage" :
            $this->_loadpage( );
            break;
        case "submitForm" :
            $this->_submitForm( );
            break;
        case "loadFormData" :
            $this->_loadFormData( );
        }
    }

    public function _loadpage( )
    {
    }

    public function _submitForm( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $maxsize = getpar( $_POST, "maxsizeperuser", "" );
        $maxsizes = 1048576 * intval( $maxsize );
        $temp = $CNOA_DB->db_getfield( "maxsizeperuser", $this->table_list );
        $temp = intval( $temp ) / 1048576;
        $CNOA_DB->db_update( array(
            "maxsizeperuser" => $maxsizes,
            "maxsizeperfile" => $maxsizes
        ), $this->table_list, "WHERE `id`= 1" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 200, array(
            "{$temp}M" => "{$maxsize}M"
        ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function _loadFormData( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_getone( "*", $this->table_list, "WHERE `id`=1" );
        $dblist['maxsizeperuser'] = sprintf( "%01.2f", $dblist['maxsizeperuser'] / 1048576 );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    public function api_submitForm( $uid, $maxsizes )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $maxInfo = $CNOA_DB->db_getone( "*", $this->table, "WHERE `uid`='".$uid."'" );
        if ( !$maxInfo )
        {
            $data = array(
                "uid" => $uid,
                "maxsize" => $maxsizes,
                "used" => 0
            );
            $CNOA_DB->db_insert( $data, $this->table );
        }
        else
        {
            $data = array(
                "maxsize" => $maxsizes
            );
            $CNOA_DB->db_update( $data, $this->table, "WHERE `uid`='".$uid."'" );
        }
    }

}

?>
