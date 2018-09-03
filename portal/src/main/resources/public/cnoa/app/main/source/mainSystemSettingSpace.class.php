<?php
//decode by qq2859470

class mainSystemSettingSpace
{

    private $table = "user_disk_user";
    private $table_list = "user_disk_config";
    private $table_default_space = "system_default_space";
    private $table_user_space = "system_user_space";
    private $table_fs = "system_fs";

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        switch ( $task )
        {
        case "loadFormDataInfo" :
            $this->_loadFormDataInfo( );
            break;
        case "updateDiskSize" :
            $this->_updateDiskSize( );
            break;
        case "updateSmsInsideSize" :
            $this->_updateSmsInsideSize( );
            break;
        case "updateFsSize" :
            $this->_updateFsSize( );
        }
    }

    private function _loadFormDataInfo( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_getone( "*", $this->table_list, "WHERE `id`=1" );
        $data['disksize'] = sprintf( "%01.2f", $dblist['maxsizeperuser'] / 1048576 );
        $space = $CNOA_DB->db_getone( array( "insidesize", "fssize" ), $this->table_default_space, "WHERE `id`=1" );
        $data['insidesize'] = sprintf( "%01.2f", $space['insidesize'] / 1048576 );
        $data['fssize'] = sprintf( "%01.2f", $space['fssize'] / 1048576 );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _updateDiskSize( )
    {
        global $CNOA_DB;
        $maxsize = getpar( $_POST, "disksize", "" );
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

    private function _updateSmsInsideSize( )
    {
        global $CNOA_DB;
        $maxsize = getpar( $_POST, "insidesize", "" );
        $maxsize = 1048576 * intval( $maxsize );
        $fssize = $CNOA_DB->db_getfield( "fssize", $this->table_default_space, "WHERE `id`=1" );
        if ( $fssize < $maxsize )
        {
            msg::callback( FALSE, lang( "mainboxSizeMustLessThan" ) );
        }
        $CNOA_DB->db_update( array(
            "insidesize" => $maxsize
        ), $this->table_default_space, "WHERE `id`=1" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function _updateFsSize( )
    {
        global $CNOA_DB;
        $fssize = getpar( $_POST, "fssize", "" );
        $fssize = 1048576 * intval( $fssize );
        $insidesize = $CNOA_DB->db_getfield( "insidesize", $this->table_default_space, "WHERE `id`=1" );
        if ( empty( $insidesize ) )
        {
            $CNOA_DB->db_insert( array(
                "fssize" => $fssize
            ), $this->table_default_space );
            $insidesize = $CNOA_DB->db_getfield( "insidesize", $this->table_default_space, "WHERE `id`=1" );
        }
        if ( $fssize < $insidesize )
        {
            msg::callback( FALSE, lang( "totalMustLessThanMainbox" ) );
        }
        $CNOA_DB->db_update( array(
            "fssize" => $fssize
        ), $this->table_default_space, "WHERE `id`=1" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function api_updateSmsInsideSize( $uid, $insidesize, $fssize )
    {
        global $CNOA_DB;
        if ( !empty( $uid ) )
        {
            $total = $CNOA_DB->db_select( array( "size", "type" ), $this->table_fs, "WHERE `uid`=".$uid );
            if ( $total )
            {
                $totalused = 0;
                $insideused = 0;
                foreach ( $total as $v )
                {
                    if ( $v['type'] == 1 )
                    {
                        $insideused += $v['size'];
                    }
                    $totalused += $v['size'];
                }
            }
            $data['totalsize'] = 1048576 * intval( $fssize );
            $data['insidesize'] = 1048576 * intval( $insidesize );
            $db = $CNOA_DB->db_select( "*", $this->table_user_space, "WHERE `uid`=".$uid );
            if ( $db )
            {
                $CNOA_DB->db_update( $data, $this->table_user_space, "WHERE `uid`=".$uid );
            }
            else
            {
                $data['totalused'] = $totalused;
                $data['insideused'] = $insideused;
                $data['uid'] = $uid;
                $CNOA_DB->db_insert( $data, $this->table_user_space, "WHERE `uid`=".$uid );
            }
        }
    }

    public function api_reduceUsedSpace( $args )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        if ( is_array( $args ) && !empty( $args ) )
        {
            $ids = array( );
            foreach ( $args as $k => $v )
            {
                $args[$k] = json_decode( $v['attach'] );
                $ids = array_merge( $ids, $args[$k] );
            }
            $ids = implode( ",", $ids );
            if ( !empty( $ids ) )
            {
                $record = $CNOA_DB->db_select( array( "size" ), $this->table_fs, "WHERE `id` IN (".$ids.")" );
                if ( $record )
                {
                    $total = 0;
                    foreach ( $record as $v )
                    {
                        $total += $v['size'];
                    }
                    if ( $total != 0 )
                    {
                        $uid = $CNOA_SESSION->get( "UID" );
                        $sql = "UPDATE ".tname( $this->table_user_space ).( " SET `totalused`=`totalused`-".$total.", `insideused`=`insideused`-{$total} " ).( "WHERE `uid`=".$uid );
                        $CNOA_DB->query( $sql );
                    }
                }
            }
        }
    }

    public function api_reduceSpace( $attach )
    {
        if ( is_array( $attach ) )
        {
            $attach = implode( ",", $attach );
        }
        if ( !isinformat( $attach ) )
        {
            return FALSE;
        }
        $record = $CNOA_DB->db_select( array( "size" ), $this->table_fs, "WHERE `id` IN (".$attach.")" );
        if ( is_array( $record ) )
        {
            $total = 0;
            foreach ( $record as $v )
            {
                $total += $v['size'];
            }
            if ( $total != 0 )
            {
                global $CNOA_DB;
                global $CNOA_SESION;
                $uid = $CNOA_SESION->get( "UID" );
                $sql = "UPDATE ".tname( $this->table_user_space ).( " SET `totalused`=`totalused`-".$total.", `insideused`=`insideused`-{$total} " ).( "WHERE `uid`=".$uid );
                $CNOA_DB->query( $sql );
            }
        }
    }

    public function api_addUsedSpace( $args, $ids = "" )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        if ( is_string( $args ) )
        {
            $vars[] = $args;
        }
        else if ( is_array( $args ) )
        {
            $vars = $args;
        }
        if ( is_array( $args ) && !empty( $args ) )
        {
            $total = 0;
            foreach ( $args as $v )
            {
                $fileinfo = explode( "||", $v );
                $total += $fileinfo[3];
                unset( $fileinfo );
            }
        }
        if ( $total != 0 )
        {
            $sql = "UPDATE ".tname( $this->table_user_space ).( " SET `totalused`=`totalused`+".$total.", `insideused`=`insideused`+{$total} " ).( "WHERE `uid`=".$uid );
            $CNOA_DB->query( $sql );
            unset( $sql );
            if ( !empty( $ids ) )
            {
                $ids = implode( ",", array_filter( $ids ) );
                $sql = "UPDATE ".tname( $this->table_user_space ).( " SET `totalused`=`totalused`+".$total.", `insideused`=`insideused`+{$total} " ).( "WHERE `uid` IN (".$ids.")" );
                $CNOA_DB->query( $sql );
            }
        }
    }

    public function api_usedSpace( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $data['insideused'] = $CNOA_DB->db_sum( "size", $this->table_fs, "WHERE `uid`=".$uid." AND `type`=1" );
        $data['totalused'] = $CNOA_DB->db_sum( "size", $this->table_fs, "WHERE `uid`=".$uid );
        $CNOA_DB->db_update( $data, $this->table_user_space, "WHERE `uid`=".$uid );
    }

    public function api_isFull( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $where = "WHERE `uid`=".$uid." ";
        $usedSpace = $CNOA_DB->db_getone( "*", $this->table_user_space, $where );
        $defaultSpace = $CNOA_DB->db_getone( "*", $this->table_default_space, "WHERE `id`=1" );
        if ( $usedSpace )
        {
            $usedSpace['totalsize'] = $usedSpace['totalsize'] != 0 ? $usedSpace['totalsize'] : $defaultSpace['fssize'];
            $usedSpace['insidesize'] = $usedSpace['insidesize'] != 0 ? $usedSpace['insidesize'] : $defaultSpace['insidesize'];
            if ( $usedSpace['totalsize'] <= $usedSpace['totalused'] )
            {
                msg::callback( FALSE, lang( "spaceFullCleanUP" ) );
            }
            else if ( $usedSpace['insidesize'] <= $usedSpace['insideused'] )
            {
                msg::callback( FALSE, lang( "mailSpaceFullClean" ) );
            }
        }
        else
        {
            $totalsize = $CNOA_DB->db_sum( "size", $this->table_fs, $where );
            $insidesize = $CNOA_DB->db_sum( "size", $this->table_fs, $where."AND `type`=1" );
            if ( $defaultSpace['fssize'] <= $totalsize )
            {
                msg::callback( FALSE, lang( "spaceFullCleanUP" ) );
            }
            else if ( $defaultSpace['insidesize'] <= $insidesize )
            {
                msg::callback( FALSE, lang( "mailSpaceFullClean" ) );
            }
        }
    }

}

?>
