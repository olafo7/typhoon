<?php
//decode by qq2859470

class userDiskIndexM extends userDiskM
{

    private $table_list = "user_disk_main";
    private $table_user = "user_disk_user";
    private $table_config = "user_disk_config";

    public function run( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $task = getpar( $_GET, "task", "loadPage" );
        switch ( $task )
        {
        case "getDir" :
            $this->_getDir( );
            break;
        case "getList" :
            $this->_getList( );
            break;
        case "rename" :
            $this->_rename( );
            break;
        case "upload" :
            $this->_upload( );
            break;
        case "checksize" :
            $this->_checksize( );
            break;
        case "delete" :
            $this->_delete( );
            break;
        case "getUsedInfo" :
            $this->_getUsedInfo( );
            break;
        case "downLoadTimes" :
            $this->_downLoadTimes( );
            break;
        case "viewTimes" :
            $this->_viewTimes( );
            break;
        case "packagefile" :
            $this->_packagefile( );
            break;
        case "loadPage" :
            $this->_loadPage( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/disk/index.m.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    private function _getDir( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $pid = intval( getpar( $_POST, "pid", 0 ) );
        $uid = $CNOA_SESSION->get( "UID" );
        $dbList = $CNOA_DB->db_select( "*", $this->table_list, "WHERE `pid`='".$pid."' AND `uid`='{$uid}' AND `type`='d' ORDER BY `name` ASC" );
        if ( !is_array( $dbList ) )
        {
            $dbList = array( );
        }
        $jsonList = array( );
        foreach ( $dbList as $k => $v )
        {
            $jsonList[] = array(
                "id" => $v['fid'],
                "text" => $v['name'],
                "cls" => "folder",
                "pid" => $v['pid'],
                "fid" => $v['fid']
            );
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
        $paterId = $CNOA_DB->db_getfield( "pid", $this->table_list, "WHERE `fid`='".$pid."' AND `uid`='{$uid}'" );
        $paterId = $paterId ? $paterId : "0";
        $dbList = $CNOA_DB->db_select( "*", $this->table_list, "WHERE `pid`='".$pid."' AND `uid`='{$uid}' {$where} ORDER BY `type` ASC, `name` ASC" );
        if ( !is_array( $dbList ) )
        {
            $dbList = array( );
        }
        $data = array( );
        foreach ( $dbList as $k => $v )
        {
            if ( $v['type'] == "sf" )
            {
                $contiune = $this->__checkShareCondition( $v );
                if ( !$contiune )
                {
                    $GLOBALS['_POST']['ids'] = json_encode( array(
                        $v['fid']
                    ) );
                    $this->_delete( FALSE );
                }
                else
                {
                    $temp = $this->__handlerShareFile( $v, $v['sharefrom'] );
                }
            }
            else
            {
                $temp = $v;
                $temp['posttime'] = date( "Y-m-d H:i:s", $v['posttime'] );
                $temp['downpath'] = makedownloadiconm( "{$GLOBALS['URL_FILE']}/common/disk/user/{$uid}/{$v['storename']}", $v['name'].".".$v['ext'], "html" );
                $temp['viewpath'] = makeattachpreviewiconm( $v['name'].".".$v['ext'], "{$GLOBALS['URL_FILE']}/common/disk/user/{$uid}/{$v['storename']}", ".".$v['ext'] );
                $temp['size'] = $v['type'] == "d" ? "" : sizeformat( $v['size'] );
            }
            $data[] = $temp;
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->paterId = $paterId;
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function __handlerShareFile( $v, $uid )
    {
        $data = $v;
        $data['posttime'] = date( "Y-m-d H:i:s", $v['posttime'] );
        $data['size'] = $v['type'] == "d" ? "" : sizeformat( $v['size'] );
        $data['sharefrom'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $v['sharefrom'] );
        if ( $v['download'] == 1 )
        {
            $data['downpath'] = makedownloadiconm( "{$GLOBALS['URL_FILE']}/common/disk/user/{$uid}/{$v['storename']}", $v['name'].".".$v['ext'], "html" );
            if ( !empty( $v['disDownload'] ) )
            {
                $data['downpath'] = str_replace( "'>".lang( "download" )."</a>", "' onclick='CNOA_user_disk_index.downloadTimes(".$v['fid'].");'>".lang( "download" )."</a>", $data['downpath'] );
            }
        }
        $data['downpath'] .= makedownloadiconm( "{$GLOBALS['URL_FILE']}/common/disk/user/{$uid}/{$v['storename']}", $v['name'].".".$v['ext'], "html" );
        if ( !empty( $v['disView'] ) )
        {
            $data['downpath'] = str_replace( "'>".lang( "browse" )."</a>", "' onclick='CNOA_user_disk_index.viewTimes(".$v['fid'].");'>".lang( "browse" )."</a>", $data['downpath'] );
        }
        if ( $v['edit'] == 1 )
        {
            $data['downpath'] .= makediskediticon( "{$GLOBALS['URL_FILE']}/common/disk/user/{$uid}/{$v['storename']}", ".".$v['ext'], FALSE, "disk_self", $v['fid'], $v['storename'], $v['name'] );
        }
        return $data;
    }

    private function __checkShareCondition( $v )
    {
        if ( !empty( $v['disTime'] ) || $v['disTime'] <= $GLOBALS['CNOA_TIMESTAMP'] )
        {
            return FALSE;
        }
        return TRUE;
    }

    private function _rename( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $name = getpar( $_POST, "name", "" );
        $fid = intval( getpar( $_POST, "fid", 0 ) );
        $pid = intval( getpar( $_POST, "pid", 0 ) );
        $type = getpar( $_POST, "type", "" );
        $uid = $CNOA_SESSION->get( "UID" );
        if ( empty( $name ) )
        {
            msg::callback( FALSE, lang( "fileFoldNameNotEmpty" ) );
        }
        $data2 = array( );
        $pInfo = $CNOA_DB->db_getone( "*", $this->table_list, "WHERE `fid`='".$pid."'" );
        if ( !$pInfo )
        {
            $pInfo['path2'] = "0";
        }
        if ( $type == "add" )
        {
            $data2['name'] = $name;
            $data2['uid'] = $uid;
            $data2['pid'] = $pid;
            $data2['type'] = "d";
            $data2['path'] = $pInfo['path2'];
            $data2['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $newfid = $CNOA_DB->db_insert( $data2, $this->table_list );
            $CNOA_DB->db_update( array(
                "path2" => $data2['path']."-".$newfid
            ), $this->table_list, "WHERE `fid`='".$newfid."'" );
            app::loadapp( "main", "systemLogs" )->api_addLogs( $type, "28", $data2['name'], lang( "folder" ) );
        }
        else
        {
            $data = $CNOA_DB->db_getone( "*", $this->table_list, "WHERE `fid`='".$fid."'" );
            if ( $data !== FALSE )
            {
                $CNOA_DB->db_update( array(
                    "name" => $name
                ), $this->table_list, "WHERE `fid`='".$fid."' AND `uid`='{$uid}'" );
            }
            $newfid = $fid;
            if ( $data['ext'] == NULL )
            {
                app::loadapp( "main", "systemLogs" )->api_addLogs( "update", "28", array(
                    $data['name'] => $name
                ), lang( "folderName" ) );
            }
            else
            {
                app::loadapp( "main", "systemLogs" )->api_addLogs( "update", "28", array(
                    $data['name'].".".$data['ext'] => $name.".".$data['ext']
                ), lang( "fileName" ) );
            }
        }
        msg::callback( TRUE, $newfid );
    }

    public function _upload( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        @ini_set( "default_socket_timeout", "86400" );
        @ini_set( "max_input_time", "86400" );
        set_time_limit( 0 );
        $uid = $CNOA_SESSION->get( "UID" );
        $pid = getpar( $_POST, "pid", 0 );
        $success = TRUE;
        if ( !isset( $_FILES['Filedata'] ) )
        {
            msg::callback( TRUE, "" );
        }
        else if ( !is_uploaded_file( $_FILES['Filedata']['tmp_name'] ) )
        {
            $upload_good = lang( "notNormalFile" );
            $success = FALSE;
        }
        else if ( $_FILES['Filedata']['error'] != 0 )
        {
            $upload_good = lang( "uploadError" ).":" + $_FILES['Filedata']['error'];
            $success = FALSE;
        }
        else
        {
            $upload_good = lang( "uploadSucess" );
        }
        $userPath = CNOA_PATH_FILE.( "/common/disk/user/".$uid );
        mkdirs( $userPath );
        $pInfo = $CNOA_DB->db_getone( "*", $this->table_list, "WHERE `fid`='".$pid."'" );
        if ( !$pInfo )
        {
            $pInfo['path2'] = "0";
        }
        if ( $success )
        {
            $isMaxed = $this->_checksize( 2, $_FILES['Filedata']['size'] );
            if ( $isMaxed !== TRUE )
            {
                msg::callback( FALSE, $isMaxed );
            }
            $fileext = strtolower( strrchr( $_FILES['Filedata']['name'], "." ) );
            $filename = string::rands( 50 ).".cnoa";
            $file_dst = $userPath."/".$filename;
            filterphpfileupload( );
            @move_uploaded_file( $_FILES['Filedata']['tmp_name'], $file_dst );
            $data = array( );
            $data['uid'] = $uid;
            $data['pid'] = $pid;
            $data['name'] = preg_replace( "/(.*)".$fileext."\$/i", "\\1", $_FILES['Filedata']['name'] );
            $data['ext'] = str_replace( ".", "", $fileext );
            $data['storename'] = $filename;
            $data['type'] = "f";
            $data['path'] = $pInfo['path2'];
            $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $data['size'] = $_FILES['Filedata']['size'];
            $id = $CNOA_DB->db_insert( $data, $this->table_list );
            $CNOA_DB->db_update( array(
                "path2" => $data['path']."-".$id
            ), $this->table_list, "WHERE `fid`='".$id."'" );
            $CNOA_DB->db_updateNum( "used", "+".$_FILES['Filedata']['size'], $this->table_user, "WHERE `uid`='".$uid."'" );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", "28", $data['name'].".".$data['ext'], lang( "file" ) );
        }
        msg::callback( $success, $upload_good );
    }

    private function _checksize( $returntype = 1, $addsize = 0 )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $maxInfo = $CNOA_DB->db_getone( "*", $this->table_user, "WHERE `uid`='".$uid."'" );
        $configInfo = $CNOA_DB->db_getone( "*", $this->table_config, "WHERE `id`=1" );
        if ( !$maxInfo )
        {
            $data = array(
                "uid" => $uid,
                "maxsize" => 0,
                "used" => 0
            );
            $CNOA_DB->db_insert( $data, $this->table_user );
        }
        else
        {
            if ( $maxInfo['maxsize'] != 0 )
            {
                $maxsize = $maxInfo['maxsize'];
            }
            else
            {
                $maxsize = $configInfo['maxsizeperuser'];
            }
            if ( $maxsize < $maxInfo['used'] + $addsize )
            {
                if ( $returntype != 1 )
                {
                    return lang( "noDiskSpaceNotice" )."<br />[".lang( "yourDiskSpace" ).":".sizeformat( $maxsize ).",".lang( "used" ).":".sizeformat( $maxInfo['used'] )."]";
                }
                msg::callback( FALSE, lang( "noDiskSpaceNotice" )."<br />[".lang( "yourDiskSpace" ).":".sizeformat( $maxsize ).",".lang( "used" ).":".sizeformat( $maxInfo['used'] )."]" );
            }
        }
        if ( $returntype != 1 )
        {
            return TRUE;
        }
        msg::callback( TRUE, 1 );
    }

    private function _delete( $exit = TRUE )
    {
        set_time_limit( 0 );
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $ids = json_decode( $_POST['ids'], TRUE );
        if ( !is_array( $ids ) )
        {
            msg::callback( FALSE, lang( "error" ) );
        }
        if ( count( $ids ) < 1 )
        {
            msg::callback( FALSE, lang( "error" ) );
        }
        $info = $CNOA_DB->db_select( "*", $this->table_list, "WHERE `fid` IN (".implode( ",", $ids ).( ") AND `uid`='".$uid."'" ) );
        if ( !$info )
        {
            msg::callback( FALSE, lang( "nodata" ) );
        }
        foreach ( $info as $v )
        {
            if ( $v['type'] == "f" || $v['type'] == "sf" )
            {
                $this->__deleteFile( $v );
            }
            else
            {
                $this->__deleteDir( $v );
            }
        }
        $this->api_tidyDisk( );
        if ( $exit )
        {
            msg::callback( TRUE, lang( "successopt" ) );
        }
    }

    private function __deleteDir( $dir )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $lists = $CNOA_DB->db_select( "*", $this->table_list, "WHERE `path2` LIKE '".$dir['path2']."-%'" );
        if ( !is_array( $lists ) )
        {
            $lists = array( );
        }
        $dirList = $fileList = array( );
        foreach ( $lists as $v )
        {
            if ( $v['type'] == "f" )
            {
                $this->__deleteFile( $v );
            }
            else
            {
                $CNOA_DB->db_delete( $this->table_list, "WHERE `fid`='".$v['fid']."' AND `uid`='{$uid}'" );
            }
        }
        $CNOA_DB->db_delete( $this->table_list, "WHERE `fid`='".$dir['fid']."'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", "28", $dir['name'], "文件夹" );
    }

    private function __deleteFile( $info )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $CNOA_DB->db_delete( $this->table_list, "WHERE `fid`='".$info['fid']."' AND `uid`='{$uid}'" );
        if ( $info['type'] == "sf" && $info['sharefrom'] != "0" )
        {
            $uid = $info['sharefrom'];
        }
        else
        {
            $uid = $info['uid'];
        }
        $userPath = CNOA_PATH_FILE.( "/common/disk/user/".$uid."/{$info['storename']}" );
        @unlink( $userPath );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", "28", $info['name'].".".$info['ext'], "文件" );
    }

    private function _getUsedInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $userDiskInfo = $CNOA_DB->db_getone( "*", $this->table_user, "WHERE `uid`='".$uid."'" );
        if ( !$userDiskInfo && $userDiskInfo['maxsize'] == 0 )
        {
            $configDiskInfo = $CNOA_DB->db_getone( "*", $this->table_config, "WHERE 1" );
            if ( !$userDiskInfo )
            {
                $msg = lang( "used" ).": 0 / ".sizeformat( $configDiskInfo['maxsizeperuser'] )." (0%)";
            }
            else
            {
                $msg = lang( "used" ).": ".sizeformat( $userDiskInfo['used'] )." / ".sizeformat( $configDiskInfo['maxsizeperuser'] )." (".floor( $userDiskInfo['used'] / $configDiskInfo['maxsizeperuser'] * 100 )."%)";
            }
        }
        else
        {
            $msg = lang( "used" ).": ".sizeformat( $userDiskInfo['used'] )." / ".sizeformat( $userDiskInfo['maxsize'] )." (".floor( $userDiskInfo['used'] / $userDiskInfo['maxsize'] * 100 )."%)";
        }
        msg::callback( TRUE, $msg );
    }

    private function _downLoadTimes( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $fid = getpar( $_GET, "fid", 0 );
        $db = $CNOA_DB->db_getone( array( "disDownload", "downLoadTimes" ), $this->table_list, "WHERE `fid` = ".$fid." AND `uid` = {$uid} " );
        if ( $db['downLoadTimes'] == $db['disDownload'] )
        {
            $GLOBALS['_POST']['ids'] = json_encode( array(
                $fid
            ) );
            $this->_delete( FALSE );
            $reflash = TRUE;
        }
        else
        {
            $CNOA_DB->db_update( array(
                "downLoadTimes" => $db['downLoadTimes'] + 1
            ), $this->table_list, "WHERE `fid` = ".$fid." AND `uid` = {$uid} " );
            $reflash = FALSE;
        }
        echo json_encode( array(
            "success" => TRUE,
            "reflash" => $reflash
        ) );
        exit( );
    }

    private function _viewTimes( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $fid = getpar( $_GET, "fid", 0 );
        $db = $CNOA_DB->db_getone( array( "disView", "viewTimes" ), $this->table_list, "WHERE `fid` = ".$fid." AND `uid` = {$uid} " );
        if ( $db['viewTimes'] == $db['disView'] )
        {
            $GLOBALS['_POST']['ids'] = json_encode( array(
                $fid
            ) );
            $this->_delete( FALSE );
            $reflash = TRUE;
        }
        else
        {
            $CNOA_DB->db_update( array(
                "viewTimes" => $db['viewTimes'] + 1
            ), $this->table_list, "WHERE `fid` = ".$fid." AND `uid` = {$uid} " );
            $reflash = FALSE;
        }
        echo json_encode( array(
            "success" => TRUE,
            "reflash" => $reflash
        ) );
        exit( );
    }

    private function _packagefile( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $ids = getpar( $_POST, "ids", 0 );
        $db = $CNOA_DB->db_select( "*", $this->table_list, "WHERE `fid` IN (".substr( $ids, 0, -1 ).") " );
        if ( !is_array( $db ) )
        {
            $db = array( );
        }
        $path = array( );
        $spath = CNOA_PATH_FILE."/temp/".$uid;
        @mkdirs( $spath );
        foreach ( $db as $k => $v )
        {
            if ( $v['type'] == "d" )
            {
                $path = $spath."/".$v['name'];
                @mkdirs( @string::iconv2( $path, "utf-8", "gbk" ) );
                $this->_packageDir( $v['fid'], $path );
            }
            else
            {
                $file = $spath."/".$v['name'].".".$v['ext'];
                if ( !empty( $v['sharefrom'] ) )
                {
                    @copy( CNOA_PATH_FILE."/common/disk/user/".$v['sharefrom']."/".$v['storename'], @string::iconv2( $file, "utf-8", "gbk" ) );
                }
                else
                {
                    @copy( CNOA_PATH_FILE."/common/disk/user/".$uid."/".$v['storename'], @string::iconv2( $file, "utf-8", "gbk" ) );
                }
            }
        }
        ( );
        $CNOA_FS = new fs( );
        $file_name = $CNOA_FS->mkName( ).".zip";
        ( string::iconv2( CNOA_PATH_FILE."/common/temp/".$file_name, "utf-8", "gbk" ) );
        $zip = new zipcmd( );
        $zip->setBaseDir( $spath );
        $zip->setNoDirectory( FALSE );
        if ( $handle = opendir( CNOA_PATH_FILE."/temp/".$uid ) )
        {
            while ( FALSE !== ( $file = readdir( $handle ) ) )
            {
                if ( !( $file != "." ) && !( $file != ".." ) )
                {
                    $zip->add( $file );
                }
            }
            closedir( $handle );
        }
        $zip->make( );
        @deldir( $spath );
        $packname = getpar( $_POST, "packname", lang( "packFile" ) );
        $msg = "||".$packname.".zip||".$file_name."||".filesize( string::iconv2( CNOA_PATH_FILE."/common/temp/".$file_name, "utf-8", "gbk" ) );
        msg::callback( TRUE, $msg );
    }

    private function _packageDir( $pid, $path )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $db = $CNOA_DB->db_select( "*", $this->table_list, "WHERE `pid` = ".$pid );
        if ( !empty( $db ) )
        {
            foreach ( $db as $k => $v )
            {
                if ( $v['type'] == "d" )
                {
                    $path2 = $path."/".$v['name'];
                    @mkdirs( @string::iconv2( $path2, "utf-8", "gbk" ) );
                    $this->_packageDir( $v['fid'], $path2 );
                }
                else
                {
                    $file = $path."/".$v['name'].".".$v['ext'];
                    if ( !empty( $v['sharefrom'] ) )
                    {
                        @copy( CNOA_PATH_FILE."/common/disk/user/".$v['sharefrom']."/".$v['storename'], @string::iconv2( $file, "utf-8", "gbk" ) );
                    }
                    else
                    {
                        @copy( CNOA_PATH_FILE."/common/disk/user/".$uid."/".$v['storename'], @string::iconv2( $file, "utf-8", "gbk" ) );
                    }
                }
            }
        }
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

    public function api_tidyDisk( )
    {
        set_time_limit( 0 );
        global $CNOA_DB;
        global $CNOA_SESSION;
        $list = $CNOA_DB->db_select( array( "size", "uid" ), $this->table_list, "WHERE `type`='f' OR `type`='sf'" );
        if ( !is_array( $list ) )
        {
            $list = array( );
        }
        $lists = array( );
        foreach ( $list as $v )
        {
            if ( isset( $lists[$v['uid']] ) )
            {
                $lists[$v['uid']] += intval( $v['size'] );
            }
            else
            {
                $lists[$v['uid']] = intval( $v['size'] );
            }
        }
        foreach ( $lists as $k => $v )
        {
            if ( !$CNOA_DB->db_getone( "*", $this->table_user, "WHERE `uid`='".$k."'" ) )
            {
                $data = array(
                    "uid" => $k,
                    "maxsize" => 0,
                    "used" => $v
                );
                $CNOA_DB->db_insert( $data, $this->table_user );
            }
            else
            {
                $data = array(
                    "used" => $v
                );
                $CNOA_DB->db_update( $data, $this->table_user, "WHERE `uid`='".$k."'" );
            }
        }
    }

}

?>
