<?php
//decode by qq2859470

class userDiskPubCommon extends model
{

    private $table_list = "user_disk_main";
    private $table_public = "user_disk_public";
    private $table_public_file = "user_disk_public_file";
    private $table_user = "user_disk_user";
    private $table_config = "user_disk_config";
    private $user_disk_folder = "user_disk_folder";
    private $files = array( );
    private $srcBaseDir = NULL;
    private $dirs = array( );
    private $fulueFolders = 0;
    private $fulueFiles = 0;

    public function importFromDisk( )
    {
        global $CNOA_SESSION;
        global $CNOA_DB;
        set_time_limit( 0 );
        $uid = $CNOA_SESSION->get( "UID" );
        if ( $uid != 1 )
        {
            msg::callback( FALSE, "此功能只对admin用户开放！" );
        }
        $toPid = intval( getpar( $_POST, "pid", 0 ) );
        if ( $toPid == 0 )
        {
            msg::callback( FALSE, "不能导入到根目录" );
        }
        $folder = getpar( $_POST, "folder" );
        $folder2 = iconv( "UTF-8", "GBK//IGNORE", $folder );
        $folder = str_replace( array( "\\\\", "\\" ), "/", $folder );
        $folder = realpath( iconv( "UTF-8", "GBK//IGNORE", $folder ) );
        $src = str_replace( array( "\\\\", "\\" ), "/", $folder );
        $this->srcBaseDir = $src;
        if ( !file_exists( $this->srcBaseDir ) )
        {
            msg::callback( FALSE, "服务器上无此目录" );
        }
        $srcList = $this->lsDisk( $this->srcBaseDir, ".php\$" );
        usort( &$srcList, create_function( "\$a,\$b", "return strlen(\$b) < strlen(\$a);" ) );
        $folderCount = 0;
        foreach ( $srcList as $v )
        {
            if ( is_dir( $v ) )
            {
                $this->importDir( $v, $toPid );
                ++$folderCount;
            }
        }
        $fileCount = 0;
        foreach ( $srcList as $v )
        {
            if ( is_file( $v ) )
            {
                $this->importFile( $v, $toPid );
                ++$fileCount;
            }
        }
        $nowDir = $CNOA_DB->db_getone( array( "name", "fid", "path" ), $this->table_public, "WHERE `fid`='".$toPid."'" );
        $msg = "导入目录详情：<br />";
        $msg .= "导入文件夹：".$folder2." 到：{$nowDir['name']}<br />";
        $msg .= "文件夹数量：".$folderCount." 个，忽略：{$this->fulueFolders} 个<br />";
        $msg .= "文件数量：".$fileCount." ，忽略：{$this->fulueFiles} 个";
        insertdisklog( 6, 1, $nowDir['path'], $toPid, "导入文件夹".$folder2 );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 30, $msg, lang( "folder" ) );
        msg::callback( TRUE, $msg );
        exit( );
    }

    private function lsDisk( $dir, $mask )
    {
        static $i = 0;
        $d = opendir( $dir );
        $files = array( );
        while ( $file = readdir( $d ) )
        {
            if ( !( $file == "." ) || !( $file == ".." ) )
            {
                if ( $Tmp_3 )
                {
                    break;
                }
            }
            else
            {
                continue;
            }
            if ( is_dir( $dir."/".$file ) )
            {
                $files += $this->lsDisk( $dir."/".$file, $mask );
                $files[$i++] = $dir."/".$file;
            }
            else
            {
                $files[$i++] = $dir."/".$file;
            }
        }
        return $files;
    }

    private function importDir( $dir, $toPid )
    {
        global $CNOA_SESSION;
        global $CNOA_DB;
        $uid = $CNOA_SESSION->get( "UID" );
        $data = array( );
        $data['uid'] = $uid;
        $data['type'] = "d";
        $data['size'] = 0;
        $data['extend'] = 1;
        $dirs = explode( "/", str_replace( $this->srcBaseDir."/", "", $dir ) );
        $dirs2 = array( );
        foreach ( $dirs as $dd )
        {
            $name = addslashes( iconv( "GBK", "UTF-8//IGNORE", $dd ) );
            $dirs2[] = $name;
            $nowDir = $CNOA_DB->db_getone( array( "name", "fid" ), $this->table_public, "WHERE `pid`='".$toPid."' AND `name`='{$name}'" );
            if ( $nowDir['name'] == $name )
            {
                $toPid = $nowDir['fid'];
                $this->dirs[md5( implode( "/", $dirs2 ) )] = $toPid;
                $this->fulueFolders++;
            }
            else
            {
                $data['pid'] = $toPid;
                $data['posttime'] = time( );
                $data['name'] = $name;
                $path = $CNOA_DB->db_getfield( "path", $this->table_public, "WHERE `fid` = '".$toPid."' " );
                $data['path'] = $path."-".$toPid;
                $toPid = $CNOA_DB->db_insert( $data, $this->table_public );
                $CNOA_DB->db_update( array(
                    "path2" => $data['path']."-".$toPid
                ), $this->table_public, "WHERE `fid` = '".$toPid."' " );
                $this->dirs[md5( implode( "/", $dirs2 ) )] = $toPid;
            }
        }
    }

    private function importFile( $file, $toPid )
    {
        global $CNOA_SESSION;
        global $CNOA_DB;
        $uid = $CNOA_SESSION->get( "UID" );
        $filePath = $file;
        $file = iconv( "GBK", "UTF-8//IGNORE", $file );
        $files = explode( "/", str_replace( iconv( "GBK", "UTF-8//IGNORE", $this->srcBaseDir )."/", "", $file ) );
        if ( 1 < count( $files ) )
        {
            $file = array_pop( &$files );
            $path = implode( "/", $files );
            $pid = $this->dirs[md5( $path )];
        }
        else
        {
            $file = $files[0];
            $pid = $toPid;
        }
        $fileInfo = pathinfo( $file );
        $fileExists = $CNOA_DB->db_getone( array( "name" ), $this->table_public_file, "WHERE `fid`='".$pid."' AND `name`='".addslashes( $fileInfo['filename'] )."'" );
        if ( $fileExists )
        {
            $this->fulueFiles++;
        }
        else
        {
            $path2 = $CNOA_DB->db_getfield( "path2", $this->table_public, "WHERE `fid`='".$pid."'" );
            $data = array( );
            $data['fid'] = $pid;
            $data['path'] = $path2;
            $data['uid'] = $uid;
            $data['name'] = addslashes( $fileInfo['filename'] );
            $data['ext'] = addslashes( strtolower( $fileInfo['extension'] ) );
            $data['storepath'] = date( "Y/m", $GLOBALS['CNOA_TIMESTAMP'] );
            $data['storename'] = string::rands( 50 ).".cnoa";
            $data['posttime'] = time( );
            $data['size'] = filesize( $filePath );
            $file_dst = CNOA_PATH_FILE."/common/disk/public/".$data['storepath']."/".$data['storename'];
            @mkdirs( @dirname( $file_dst ) );
            if ( @copy( $filePath, $file_dst ) )
            {
                $CNOA_DB->db_insert( $data, $this->table_public_file );
            }
        }
    }

}

?>
