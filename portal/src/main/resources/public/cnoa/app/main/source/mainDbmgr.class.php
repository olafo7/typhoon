<?php
//decode by qq2859470

class mainDbmgr extends model
{

    private $table = "main_dbmgr";
    private $backupDirList = array( );
    private $excludeDirList = array( );

    public function __construct( )
    {
        global $CNOA_SESSION;
        $this->backupDirList[] = "common/desktop";
        $this->backupDirList[] = "common/face";
        $this->backupDirList[] = "common/upload";
        $this->backupDirList[] = "common/vfs";
        $this->backupDirList[] = "common/disk";
        $this->backupDirList[] = "common/.htaccess";
        $this->backupDirList[] = "cache";
        $this->excludeDirList[] = "common/temp";
        $uid = $CNOA_SESSION->get( "UID" );
        if ( $uid != 1 )
        {
            msg::callback( FALSE, "no access permition!" );
        }
    }

    public function actionIndex( )
    {
        $task = getpar( $_GET, "task", getpar( $_POST, "task" ) );
        if ( $task == "getJsonList" )
        {
            $this->_getJsonList( );
        }
        else if ( $task == "export" )
        {
            $this->checkIsTryOA( );
            $this->_export( );
        }
        else if ( $task == "delete" )
        {
            $this->checkIsTryOA( );
            $this->_delete( );
        }
        else if ( !( $task == "download" ) )
        {
            if ( $task == "restore" )
            {
                $this->checkIsTryOA( );
                $this->_restore( );
            }
            else if ( $task == "upBackupFile" )
            {
                $this->checkIsTryOA( );
                $this->_upBackupFile( );
            }
            else if ( $task == "repairDb" )
            {
                $this->_repairDb( );
            }
        }
    }

    private function _getJsonList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $listInfo = $CNOA_DB->db_select( "*", $this->table, $where." ORDER BY `date` DESC" );
        if ( !is_array( $listInfo ) )
        {
            $listInfo = array( );
        }
        foreach ( $listInfo as $k => $v )
        {
            $listInfo[$k]['date'] = date( "Y年m月d日 H时i分s秒", $v['date'] );
            $listInfo[$k]['size'] = sizeformat( $v['size'] );
            if ( $v['type'] == 1 )
            {
                $listInfo[$k]['type'] = "<span class='cnoa_color_red'>数据库</span>";
            }
            if ( $v['type'] == 2 )
            {
                $listInfo[$k]['type'] = "<span class='cnoa_color_green'>用户文件</span>";
            }
            if ( $v['type'] == 3 )
            {
                $listInfo[$k]['type'] = "<span class='cnoa_color_red'>数据库</span>+<span class='cnoa_color_green'>用户文件</span>";
            }
            $listInfo[$k]['file'] = makedownloadicon( "{$GLOBALS['URL_FILE']}/backup/".$v['backname'].".php", $v['backname'], "html" );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = 0;
        $dataStore->data = $listInfo;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _export( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $b_sql = getpar( $_POST, "sql", "false" );
        $b_file = getpar( $_POST, "file", "false" );
        if ( $b_sql == "false" && $b_file == "false" )
        {
            msg::callback( FALSE, lang( "chooseTypeBackup" ) );
        }
        $b_sql = $b_sql == "true" ? 1 : 0;
        $b_file = $b_file == "true" ? 2 : 0;
        set_time_limit( 0 );
        $data = array( );
        $data['backname'] = date( "YmdHis", $GLOBALS['CNOA_TIMESTAMP'] ).( $b_sql + $b_file ).string::rands( 15, 2 );
        $data['size'] = 0;
        $data['date'] = $GLOBALS['CNOA_TIMESTAMP'];
        $filePath = CNOA_PATH_FILE."/backup/".$data['backname'].".sql";
        $zipfile = str_replace( ".sql", ".zip", $filePath );
        ( $zipfile );
        $zip = new zipcmd( );
        $zip->setBaseDir( CNOA_PATH_FILE );
        $version = $CNOA_DB->db_getfield( "version2", "system_config", "WHERE `id`=1" );
        $versionfile = CNOA_PATH_FILE."/backup/version.txt";
        @file_put_contents( $versionfile, $version );
        $zip->setNoDirectory( TRUE );
        $zip->add( $versionfile );
        $zip->make( );
        @unlink( $versionfile );
        if ( $b_sql === 1 )
        {
            $tables = array( );
            $query = $CNOA_DB->query( "SHOW TABLES FROM ".CNOA_DB_NAME );
            while ( $r = $CNOA_DB->get_array( $query ) )
            {
                foreach ( $r as $v )
                {
                    $tables[] = $v;
                }
            }
            unset( $v );
            foreach ( $tables as $v )
            {
                $this->dumpTable( $v, $filePath );
            }
            $zip->fileList = array( );
            $zip->setNoDirectory( TRUE );
            $zip->add( $filePath );
            $zip->make( );
            @unlink( $filePath );
        }
        if ( $b_file === 2 )
        {
            $zip->setNoDirectory( FALSE );
            $zip->fileList = array( );
            foreach ( $this->backupDirList as $v )
            {
                $zip->add( $v );
            }
            $zip->make( );
        }
        @rename( $zipfile, $zipfile.".php" );
        $data['backname'] = $data['backname'].".zip";
        $data['size'] = filesize( $zipfile.".php" );
        $data['type'] = $b_sql + $b_file;
        $CNOA_DB->db_insert( $data, $this->table );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _delete( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = intval( getpar( $_POST, "id", 0 ) );
        if ( $id == 0 )
        {
            msg::callback( FALSE, lang( "paramsError" ) );
        }
        $info = $CNOA_DB->db_getone( "*", $this->table, "WHERE `id`='".$id."'" );
        if ( !$info )
        {
            msg::callback( FALSE, lang( "nodata" ) );
        }
        $filePath = CNOA_PATH_FILE."/backup/".$info['backname'].".php";
        @unlink( $filePath );
        $CNOA_DB->db_delete( $this->table, "WHERE `id`='".$id."'" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _download( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = intval( getpar( $_GET, "id", 0 ) );
        if ( $id == 0 )
        {
            msg::callback( FALSE, lang( "paramsError" ) );
        }
        $info = $CNOA_DB->db_getone( "*", $this->table, "WHERE `id`='".$id."'" );
        if ( !$info )
        {
            msg::callback( FALSE, lang( "nodata" ) );
        }
        $filePath = CNOA_PATH_FILE."/backup/".$info['backname'].".php";
        $tempFile = "{$GLOBALS['URL_FILE']}/common/temp/backup.".string::rands( 40 ).".download";
        copy( $filePath, CNOA_PATH."/".$tempFile );
        gourl( $tempFile );
        exit( );
    }

    private function _restore( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = intval( getpar( $_POST, "id", 0 ) );
        set_time_limit( 0 );
        $info = $CNOA_DB->db_getone( "*", $this->table, "WHERE `id`='".$id."'" );
        if ( !$info )
        {
            msg::callback( FALSE, lang( "nodata" ) );
        }
        $zipFile = CNOA_PATH_FILE."/backup/".$info['backname'].".php";
        if ( !file_exists( $zipFile ) )
        {
            msg::callback( FALSE, lang( "backDataNotExist" ) );
        }
        $data = $CNOA_DB->db_select( "*", $this->table );
        $pathTemp = CNOA_PATH_FILE."/backup/temp";
        deldir( $pathTemp );
        mkdirs( $pathTemp );
        ( $zipFile );
        $zip = new unzip( );
        $zip->Extract( $zipFile, $pathTemp );
        $version = $CNOA_DB->db_getfield( "version2", "system_config", "WHERE `id`=1" );
        $versionfile = $pathTemp."/version.txt";
        $version2 = @file_get_contents( $versionfile );
        if ( $version !== $version2 )
        {
            msg::callback( FALSE, lang( "cilckContactUs" ) );
        }
        $sqlFile = str_replace( ".zip", ".sql", $pathTemp."/".$info['backname'] );
        if ( file_exists( $sqlFile ) )
        {
            $tableCount = 0;
            $dataCount = 0;
            $sql = file_get_contents( $sqlFile );
            $this->sql_execute( $sql, $tableCount, $dataCount );
        }
        if ( is_dir( $pathTemp ) )
        {
            $countFile = 0;
            $countDir = 0;
            foreach ( $this->backupDirList as $v )
            {
                copydir( $pathTemp."/".$v, CNOA_PATH_FILE."/".$v, TRUE, $countDir, $countFile );
            }
        }
        $CNOA_DB->query( "TRUNCATE TABLE ".tname( $this->table ) );
        foreach ( $data as $v )
        {
            $CNOA_DB->db_insert( $v, $this->table );
        }
        deldir( $pathTemp );
        $msgExt = "";
        if ( $tableCount != 0 || $dataCount != 0 )
        {
            $msgExt .= "<br >共恢复数据表".$tableCount."个，数据{$dataCount}条。";
        }
        if ( $countFile != 0 || $countDir != 0 )
        {
            $msgExt .= "<br >共恢复文件夹".$countDir."个，文件{$countFile}个。";
        }
        $CNOA_SESSION->kickAllUser( );
        msg::callback( TRUE, lang( "dataBeenRestore" ).date( "Y年m月d日 H时i分" ).$msgExt );
    }

    private function _upBackupFile( )
    {
        global $CNOA_DB;
        set_time_limit( 0 );
        $file_ext = strtolower( strrchr( $_FILES['file']['name'], "." ) );
        $file_name = $_FILES['file']['name'];
        $file_dst = CNOA_PATH_FILE."/backup/".$file_name.".php";
        $extArray = array( ".zip" );
        if ( !in_array( $file_ext, $extArray ) )
        {
            msg::callback( FALSE, lang( "fileToZipFormat" ) );
        }
        if ( preg_match( "/^[0-9]{14,14}[1-3]{1,1}[0-9]{15,15}\\.zip\$/is", $file_name ) === 0 )
        {
            msg::callback( FALSE, lang( "fileFormatNotZQ" )."<br>如：201008191500423295470159317748.zip" );
        }
        if ( file_exists( $file_dst ) )
        {
            msg::callback( FALSE, lang( "backFileExist" ) );
        }
        if ( cnoa_move_uploaded_file( $_FILES['file']['tmp_name'], $file_dst ) )
        {
            $nameSplit = str_replace( ".zip", "", $file_name );
            $data = array( );
            $data['backname'] = $file_name;
            $data['date'] = strtotime( substr( $nameSplit, 0, 14 ) );
            $data['size'] = filesize( $file_dst );
            $data['type'] = $nameSplit[14];
            $CNOA_DB->db_insert( $data, $this->table );
            msg::callback( TRUE, lang( "successopt" ) );
        }
        else
        {
            msg::callback( FALSE, lang( "uploadFail" ) );
        }
    }

    private function _repairDb( )
    {
        global $CNOA_DB;
        set_time_limit( 0 );
        $tables = array( );
        $query = $CNOA_DB->query( "SHOW TABLES FROM ".CNOA_DB_NAME );
        $sql = "REPAIR TABLE ";
        while ( $r = $CNOA_DB->get_array( $query ) )
        {
            foreach ( $r as $v )
            {
                $tables[] = "`".$v."`";
            }
        }
        $sql .= implode( ",", $tables );
        $CNOA_DB->query( $sql );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function dumpTable( $tableName, $fileName )
    {
        global $CNOA_DB;
        $tabledump = "DROP TABLE IF EXISTS `".$tableName."`;\n";
        $createtable = $CNOA_DB->query( "SHOW CREATE TABLE `".$tableName."`" );
        $create = $CNOA_DB->get_array( $createtable, MYSQL_NUM );
        $tabledump .= $create[1].";\n\n";
        @mkdirs( @dirname( $fileName ) );
        file_put_contents( $fileName, $tabledump, FILE_APPEND );
        $query = $CNOA_DB->get_one( "SELECT COUNT(*) AS `count` FROM `".$tableName."`" );
        $total = $query['count'];
        $perrow = 5000;
        $num = ceil( $total / $perrow );
        $i = 0;
        for ( ; $i < $num; ++$i )
        {
            $tabledump = "";
            $start = $i * $perrow;
            $rows = $CNOA_DB->query( "SELECT * FROM `".$tableName."` LIMIT {$start}, {$perrow}" );
            $numfields = $CNOA_DB->num_fields( $rows );
            $numrows = $CNOA_DB->num_rows( $rows );
            while ( $row = $CNOA_DB->get_array( $rows, MYSQL_NUM ) )
            {
                $comma = "";
                $tabledump .= "INSERT INTO `".$tableName."` VALUES(";
                $j = 0;
                for ( ; $j < $numfields; ++$j )
                {
                    $tabledump .= $comma."'".mysql_escape_string( $row[$j] )."'";
                    $comma = ",";
                }
                $tabledump .= ");\n";
            }
            file_put_contents( $fileName, $tabledump, FILE_APPEND );
        }
        file_put_contents( $fileName, "\n\n", FILE_APPEND );
    }

    private function sql_execute( $sql, $tableCount = 0, $dataCount = 0 )
    {
        global $CNOA_DB;
        $sqls = $this->sql_split( $sql );
        if ( is_array( $sqls ) )
        {
            foreach ( $sqls as $sql )
            {
                if ( trim( $sql ) != "" )
                {
                    if ( strpos( $sql, "CREATE TABLE" ) !== FALSE )
                    {
                        ++$tableCount;
                    }
                    if ( strpos( $sql, "INSERT INTO" ) !== FALSE )
                    {
                        ++$dataCount;
                    }
                    $CNOA_DB->query( $sql );
                }
            }
            return TRUE;
        }
        if ( strpos( $sql, "CREATE TABLE" ) !== FALSE )
        {
            ++$tableCount;
        }
        if ( strpos( $sql, "INSERT INTO" ) !== FALSE )
        {
            ++$dataCount;
        }
        $CNOA_DB->query( $sqls );
        return TRUE;
    }

    private function sql_split( $sql )
    {
        global $CNOA_DB;
        if ( "4.1" < $CNOA_DB->version( ) && CNOA_DB_CHARSET )
        {
            $sql = preg_replace( "/TYPE=(InnoDB|MyISAM)( DEFAULT CHARSET=[^; ]+)?/", "TYPE=\\1 DEFAULT CHARSET=".CNOA_DB_CHARSET, $sql );
        }
        $sql = str_replace( "\r", "\n", $sql );
        $ret = array( );
        $num = 0;
        $queriesarray = explode( ";\n", trim( $sql ) );
        unset( $sql );
        foreach ( $queriesarray as $query )
        {
            $ret[$num] = "";
            $queries = explode( "\n", trim( $query ) );
            $queries = array_filter( $queries );
            foreach ( $queries as $query )
            {
                $str1 = substr( $query, 0, 1 );
                if ( !( $str1 != "#" ) && !( $str1 != "-" ) )
                {
                    $ret[$num] .= $query;
                }
            }
            ++$num;
        }
        return $ret;
    }

    private function checkIsTryOA( )
    {
        $host = gethost( );
        if ( ereg( "\\.oa\\.cnoa\\.cn", $host ) )
        {
            msg::callback( FALSE, lang( "sorryOnlineNotFun" ) );
        }
    }

}

?>
