<?php
//decode by qq2859470

class userDiskPublic extends model
{

    private $table_list = "user_disk_main";
    private $table_public = "user_disk_public";
    private $table_user = "user_disk_user";
    private $table_config = "user_disk_config";

    public function run( )
    {
        global $CNOA_SESSION;
        $task = getpar( $_GET, "task", getpar( $_POST, "task" ) );
        switch ( $task )
        {
        case "getDir" :
            $this->_getDir( );
            break;
        case "getList" :
            $this->_getList( );
        }
    }

    private function _getDir( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $pid = intval( getpar( $_POST, "pid", 0 ) );
        $uid = $CNOA_SESSION->get( "UID" );
        $dbList = $CNOA_DB->db_select( "*", $this->table_public, "WHERE `pid`='".$pid."' AND `type`='d' ORDER BY `name` ASC" );
        if ( !is_array( $dbList ) )
        {
            $dbList = array( );
        }
        $jsonList = array( );
        foreach ( $dbList as $k => $v )
        {
            if ( $this->__isShowDir( $v ) === TRUE )
            {
                $jsonList[] = array(
                    "id" => $v['fid'],
                    "text" => $v['name'],
                    "cls" => "folder",
                    "pid" => $v['pid'],
                    "fid" => $v['fid']
                );
            }
        }
        echo json_encode( $jsonList );
        exit( );
    }

    private function _getList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $pid = intval( getpar( $_POST, "pid", 0 ) );
        $uid = $CNOA_SESSION->get( "UID" );
        $where = "";
        $findStr = $this->_findFile( );
        if ( trim( $findStr ) != "" )
        {
            $where .= " and ".$findStr;
        }
        $pdbList = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$pid."'" );
        $dbList = $CNOA_DB->db_select( "*", $this->table_public, "WHERE `pid`='".$pid."' {$where} ORDER BY `type` ASC, `name` ASC" );
        if ( !is_array( $dbList ) )
        {
            $dbList = array( );
        }
        foreach ( $dbList as $k => $v )
        {
            if ( $v['type'] == "d" )
            {
                if ( $this->__isShowDir( $v ) === TRUE )
                {
                    $dbList[$k]['posttime'] = date( "Y-m-d H:i:s", $v['posttime'] );
                    $dbList[$k]['size'] = "";
                }
                else
                {
                    unset( $dbList[$k] );
                }
            }
            else if ( $this->__isDirHavePermit( $pdbList, 0 ) === TRUE )
            {
                $dbList[$k]['downpath'] = makedownloadicon( "{$GLOBALS['URL_FILE']}/common/disk/public/{$v['storepath']}/{$v['storename']}", $v['name'].".".$v['ext'], "img" );
                $dbList[$k]['posttime'] = date( "Y-m-d H:i:s", $v['posttime'] );
                $dbList[$k]['size'] = $v['type'] == "d" ? "" : sizeformat( $v['size'] );
            }
            else
            {
                unset( $dbList[$k] );
            }
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = array_merge( $dbList );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function __isShowDir( $ds )
    {
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $did = $CNOA_SESSION->get( "DID" );
        if ( $this->__isDirHavePermit( $ds, 0 ) )
        {
            return TRUE;
        }
        $permitList = array( );
        $this->__getAllChildDirsPermitList( $ds['fid'], $permitList );
        $havePermit = FALSE;
        foreach ( $permitList as $k => $v )
        {
            if ( !$this->__isDirHavePermit( $v, 1 ) )
            {
                continue;
            }
            $havePermit = TRUE;
            break;
        }
        return $havePermit;
    }

    private function __getAllChildDirsPermitList( $fid, &$permitList )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $dbList = $CNOA_DB->db_select( array( "fid", "people", "dept" ), $this->table_public, "WHERE `pid`='".$fid."'" );
        if ( !is_array( $dbList ) )
        {
            $dbList = array( );
        }
        foreach ( $dbList as $k => $v )
        {
            $permitList[] = $v;
            $this->__getAllChildDirsPermitList( $v['fid'], $permitList );
        }
    }

    private function __isDirHavePermit( $ds, $deep )
    {
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $did = $CNOA_SESSION->get( "DID" );
        if ( $deep === 0 && empty( $ds['people'] ) && empty( $ds['dept'] ) )
        {
            return TRUE;
        }
        if ( !empty( $ds['people'] ) )
        {
            $p = json_decode( $ds['people'], TRUE );
            if ( in_array( $uid, $p['uids'] ) )
            {
                return TRUE;
            }
        }
        if ( !empty( $ds['dept'] ) )
        {
            $d = json_decode( $ds['dept'], TRUE );
            if ( in_array( $did, $d ) )
            {
                return TRUE;
            }
        }
        return FALSE;
    }

    private function _findFile( )
    {
        global $CNOA_DB;
        $word = getpar( $_POST, "word" );
        if ( empty( $word ) )
        {
            return;
        }
        $s = " `name` like '%".$word."%'";
        return $s;
    }

}

?>
