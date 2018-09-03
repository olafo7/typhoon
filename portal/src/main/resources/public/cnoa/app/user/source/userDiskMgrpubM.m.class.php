<?php
//decode by qq2859470

class userDiskMgrpubM extends userDiskM
{

    private $table_list = "user_disk_main";
    private $table_public = "user_disk_public";
    private $table_user = "user_disk_user";
    private $table_config = "user_disk_config";
    private $user_disk_folder = "user_disk_folder";
    private $user_disk_folder_file = "user_disk_folder_file";
    private $table_public_permit_p = "user_disk_mgrpub_permit_p";
    private $table_public_permit_s = "user_disk_mgrpub_permit_s";
    private $table_public_file = "user_disk_public_file";
    private $table_versions = "user_disk_versions";
    private $table_log = "user_disk_log";
    private $table_outsideLink = "user_disk_outsidelink";
    private $logArr = array( );
    private $fidArr = array( );
    private $fidData = array( );
    private $fidPath = array( );
    private $fidPermit = array( );
    private $isAdmin = FALSE;

    public function __construct( )
    {
        global $CNOA_SESSION;
        $this->logArr = array(
            0 => lang( "createFile" ),
            1 => lang( "upFile" ),
            2 => lang( "editFileOnline" ),
            3 => lang( "editOnlineVersion" ),
            4 => lang( "browseFileOnline" ),
            5 => lang( "downFile" ),
            6 => lang( "createFolder" ),
            7 => lang( "renameFolder" )
        );
        $this->isAdmin = $CNOA_SESSION->get( "JOBTYPE" ) == "superAdmin";
    }

    public function run( )
    {
        global $CNOA_SESSION;
        $special = getpar( $_GET, "special", getpar( $_POST, "special" ) );
        if ( $special == "yes" )
        {
            $this->_saveasdiskandfs( );
            exit( );
        }
        $task = getpar( $_GET, "task", getpar( $_POST, "task" ) );
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
            $from = getpar( $_GET, "from", "" );
            if ( $from == "folder" )
            {
                $this->_uploadFolder( );
            }
            else if ( $from == "finishFolder" )
            {
                $this->_finishFolder( );
            }
            else
            {
                $this->_upload( );
            }
            break;
        case "delete" :
            $this->_delete( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $this->_getAllUserListsInPermitDeptTree( );
            break;
        case "getStructTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            echo app::loadapp( "main", "struct" )->api_getStructTree( 0 );
            exit( );
        case "permitListByM" :
            $this->_permitListByM( );
            break;
        case "permitListByS" :
            $this->_permitListByS( );
            break;
        case "addPermitM" :
            $this->_addPermitM( );
            break;
        case "addPermitS" :
            $this->_addPermitS( );
            break;
        case "addPermitDataM" :
            $this->_addPermitDataM( );
            break;
        case "addPermitDataS" :
            $this->_addPermitDataS( );
            break;
        case "deletePermitM" :
            $this->_deletePermitM( );
            break;
        case "deletePermitS" :
            $this->_deletePermitS( );
            break;
        case "deleteEmptyPermit" :
            $this->_deleteEmptyPermit( );
            break;
        case "getVersionsList" :
            $this->_getVersionsList( );
            break;
        case "getLogList" :
            $this->_getLogList( );
            break;
        case "saveasdisk" :
            $this->_saveasdisk( );
            break;
        case "selector" :
            $target = getpar( $_GET, "target", getpar( $_POST, "target", "" ) );
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            switch ( $target )
            {
            case "user" :
                app::loadapp( "main", "user" )->api_getSelectorData( );
                exit( );
            case "dept" :
                app::loadapp( "main", "struct" )->api_getSelectorData( );
            }
            exit( );
        case "submitShare" :
            $this->_submitShare( );
            break;
        case "outsidelink" :
            $this->_outsidelink( );
            break;
        case "doExtend" :
            $this->_doExtend( );
            break;
        case "getThumb" :
            $this->_getThumb( );
            break;
        case "changeViewMod" :
            $this->_changeViewMod( );
            break;
        case "sharedpeople" :
            $this->_sharedpeople( );
            break;
        case "import" :
            $this->_import( );
            break;
        case "moveToDir" :
            $this->_moveToDir( );
            break;
        case "loadPage" :
            $this->_loadPage( );
        }
    }

    private function __getDirPermit( $dir )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $did = $CNOA_SESSION->get( "DID" );
        $uid = $CNOA_SESSION->get( "UID" );
        $permit = array( "dl" => 0, "sh" => 0, "up" => 0, "dt" => 0, "ed" => 0, "vi" => 0, "mgr" => 0 );
        if ( $dir['uid'] == $uid || $this->isAdmin )
        {
            $permit = array( "dl" => 1, "sh" => 1, "up" => 1, "dt" => 1, "ed" => 1, "vi" => 1, "mgr" => 1 );
            return $permit;
        }
        $pp = $CNOA_DB->db_select( array( "dl", "sh", "up", "dt", "ed", "vi", "mgr" ), $this->table_public_permit_p, "WHERE `uid`='".$uid."' AND `fid`='{$dir['fid']}'" );
        $dp = $CNOA_DB->db_select( array( "dl", "sh", "up", "dt", "ed", "vi", "mgr" ), $this->table_public_permit_s, "WHERE `did`='".$did."' AND `fid`='{$dir['fid']}'" );
        if ( !is_array( $pp ) )
        {
            $pp = array( );
        }
        if ( !is_array( $dp ) )
        {
            $dp = array( );
        }
        $ppp = array_merge( $pp, $dp );
        unset( $pp );
        unset( $dp );
        foreach ( $ppp as $v )
        {
            foreach ( array( "dl", "sh", "up", "dt", "ed", "vi", "mgr" ) as $dpv )
            {
                if ( $v[$dpv] == 1 )
                {
                    $permit[$dpv] = 1;
                }
            }
        }
        if ( $dir['extend'] == 0 )
        {
            return $permit;
        }
        $dir2 = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$dir['pid']."'" );
        $permit2 = $this->__getDirPermit( $dir2 );
        foreach ( array( "dl", "sh", "up", "dt", "ed", "vi", "mgr" ) as $dpv )
        {
            if ( $permit2[$dpv] == 1 )
            {
                $permit[$dpv] = 1;
            }
        }
        return $permit;
    }

    private function __getChildDirs( $pid )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $childs = $CNOA_DB->db_select( array( "name", "path", "path2", "fid", "pid", "extend", "uid", "posttime" ), $this->table_public, "WHERE `pid`='".$pid."' ORDER BY `fid` ASC " );
        if ( !is_array( $childs ) )
        {
            $childs = array( );
        }
        $dirs = array( );
        foreach ( $childs as $k => $v )
        {
            $dirPermit = $this->__getDirPermit( $v );
            $include = FALSE;
            foreach ( array( "dl", "sh", "up", "dt", "ed", "vi", "mgr" ) as $dpv )
            {
                if ( $dirPermit[$dpv] == 1 )
                {
                    $include = TRUE;
                }
            }
            if ( $include )
            {
                $dirs[] = $v;
            }
        }
        return $dirs;
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/disk/mgrpub.m.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    private function _getDir( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $did = $CNOA_SESSION->get( "DID" );
        $uid = $CNOA_SESSION->get( "UID" );
        $pid = getpar( $_POST, "pid", 0 );
        $type = getpar( $_GET, "type" );
        $dirs = array( );
        if ( !$this->isAdmin )
        {
            $treeDB = $this->__getChildDirs( $pid );
        }
        else
        {
            $treeDB = $CNOA_DB->db_select( array( "name", "path", "path2", "fid", "pid" ), $this->table_public, "WHERE `pid`='".$pid."' ORDER BY `fid` ASC " );
            if ( !is_array( $treeDB ) )
            {
                $treeDB = array( );
            }
        }
        if ( $type == "tree" )
        {
            foreach ( $treeDB as $k => $v )
            {
                $db = array( );
                $db['id'] = $v['fid'];
                $db['fid'] = $v['fid'];
                $db['text'] = $v['name'];
                $db['cls'] = "folder";
                $db['pid'] = $v['pid'];
                $db['path'] = $v['path'];
                $dirs[] = $db;
            }
            echo json_encode( $dirs );
            exit( );
        }
        if ( $type == "combo" )
        {
            foreach ( $treeDB as $k => $v )
            {
                $db = array( );
                $db['id'] = $v['fid'];
                $db['fid'] = $v['fid'];
                $db['text'] = $v['name'];
                $db['cls'] = "folder";
                $db['pid'] = $v['pid'];
                $db['path'] = $v['path'];
                $db['checked'] = FALSE;
                $dirs[] = $db;
            }
            echo json_encode( $dirs );
            exit( );
        }
    }

    private function _getSearchList( $pid, $name, $uid, $did )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        ( );
        $dataStore = new dataStore( );
        $dirs = array( );
        $fids = array( 0 );
        $dirsData = array( );
        $dirPermits = array( );
        $dir = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$pid."'" );
        if ( !$dir )
        {
            $path2 = "0";
        }
        else
        {
            $path2 = $dir['path2'];
        }
        $childDirs = $CNOA_DB->db_select( "*", $this->table_public, "WHERE `path2` LIKE '".$path2."-%' OR `path2`='{$path2}'" );
        if ( !is_array( $childDirs ) )
        {
            $childDirs = array( );
        }
        foreach ( $childDirs as $k => $v )
        {
            $dirPermits[$v['fid']] = $this->__getDirPermit( $v );
            $include = FALSE;
            $dirsData[$v['fid']] = $v;
            foreach ( array( "dl", "sh", "up", "dt", "ed", "vi", "mgr" ) as $dpv )
            {
                if ( $dirPermits[$v['fid']][$dpv] == 1 )
                {
                    $include = TRUE;
                }
            }
            if ( $include )
            {
                if ( preg_match( "/".$name."/is", $v['name'] ) == 1 )
                {
                    $dirs[] = $v;
                }
                $fids[] = $v['fid'];
            }
        }
        $files = $CNOA_DB->db_select( "*", $this->table_public_file, "WHERE `name` LIKE '%".$name."%' AND `fid` IN (".implode( ",", $fids ).")" );
        if ( !is_array( $files ) )
        {
            $files = array( );
        }
        foreach ( $dirs as $k => $v )
        {
            $uidArr[] = $v['uid'];
            $dirs[$k]['id'] = $v['fid'];
            $dirs[$k]['posttime'] = date( "Y-m-d H:i", $v['posttime'] );
            $dirs[$k]['type'] = "d";
        }
        foreach ( $files as $k => $v )
        {
            $uidArr[] = $v['uid'];
            $files[$k]['id'] = $v['fileid'];
            $files[$k]['posttime'] = date( "Y-m-d H:i", $v['posttime'] );
            $files[$k]['name'] = $v['name'];
            $files[$k]['downpath'] = "";
            $dirPermit = $dirPermits[$v['fid']];
            if ( $dirPermit['dl'] || $dirPermit['mgr'] == 1 )
            {
                $files[$k]['downpath'] .= makedownloadicon( "{$GLOBALS['URL_FILE']}/common/disk/public/{$v['storepath']}/{$v['storename']}", $v['name'].".".$v['ext'], "html" );
                $files[$k]['downhref'] = makedownloadicon( "{$GLOBALS['URL_FILE']}/common/disk/public/{$v['storepath']}/{$v['storename']}", $v['name'].".".$v['ext'], "href" );
                $files[$k]['dl'] = TRUE;
            }
            if ( $dirPermit['sh'] || $dirPermit['mgr'] == 1 )
            {
                $files[$k]['sh'] = TRUE;
            }
            if ( $dirPermit['ed'] || $dirPermit['mgr'] == 1 )
            {
                $files[$k]['downpath'] .= "&nbsp;".makediskediticon( "{$GLOBALS['URL_FILE']}/common/disk/public/{$v['storepath']}/{$v['storename']}", ".".$v['ext'], TRUE, "disk_pub", $v['fileid'], $v['storename'], $v['name'], "pubdisk" );
                $files[$k]['edithref'] = makediskediticon( "{$GLOBALS['URL_FILE']}/common/disk/public/{$v['storepath']}/{$v['storename']}", ".".$v['ext'], TRUE, "disk_pub", $v['fileid'], $v['storename'], $v['name'], "pubdisk", "href" );
                $files[$k]['ed'] = TRUE;
            }
            if ( $dirPermit['vi'] || $dirPermit['mgr'] == 1 )
            {
                $files[$k]['downpath'] .= "&nbsp;".makeattachpreviewicon( $v['name'].".".$v['ext'], "{$GLOBALS['URL_FILE']}/common/disk/public/{$v['storepath']}/{$v['storename']}", ".".$v['ext'], $v['fileid'] );
                $files[$k]['viewhref'] = makeattachpreviewicon( $v['name'].".".$v['ext'], "{$GLOBALS['URL_FILE']}/common/disk/public/{$v['storepath']}/{$v['storename']}", ".".$v['ext'], $v['fileid'], "href" );
                $files[$k]['vi'] = TRUE;
            }
            $files[$k]['size'] = sizeformat( $v['size'] );
            $files[$k]['type'] = "f";
            $files[$k]['ext'] = $v['ext'];
            $dirs[] = $files[$k];
        }
        $truename = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr );
        foreach ( $dirs as $k => $v )
        {
            $dirs[$k]['postname'] = $truename[$v['uid']]['truename'];
        }
        $dataStore->data = $dirs;
        $dataStore->nowpath = "/".str_replace( "-", "/", $dir['path2'] );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $did = $CNOA_SESSION->get( "DID" );
        $pid = intval( getpar( $_POST, "pid", 0 ) );
        $name = getpar( $_POST, "word", "" );
        $type = getpar( $_GET, "type", "all" );
        if ( !empty( $name ) )
        {
            $this->_getSearchList( $pid, $name, $uid, $did );
        }
        ( );
        $dataStore = new dataStore( );
        $uidArr = array( 0 );
        $dirs = $this->__getChildDirs( $pid );
        foreach ( $dirs as $k => $v )
        {
            $uidArr[] = $v['uid'];
            $dirs[$k]['id'] = $v['fid'];
            $dirs[$k]['posttime'] = date( "Y-m-d H:i", $v['posttime'] );
            $dirs[$k]['type'] = "d";
            $dirs[$k]['ext'] = "folder";
        }
        $dir = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$pid."'" );
        $dirPermit = $this->__getDirPermit( $dir );
        if ( $type != "onlydir" && ( $dirPermit['dl'] == 1 || $dirPermit['vi'] == 1 || $dirPermit['ed'] == 1 || $dirPermit['sh'] == 1 || $dirPermit['dt'] == 1 || $dirPermit['mgr'] == 1 ) )
        {
            $files = $CNOA_DB->db_select( "*", $this->table_public_file, "WHERE `fid` = '".$pid."' ORDER BY `name` ASC" );
            $total = $CNOA_DB->db_getcount( $this->table_public_file, "WHERE `fid` = '".$pid."'" );
            if ( !is_array( $files ) )
            {
                $files = array( );
            }
            foreach ( $files as $k => $v )
            {
                $uidArr[] = $v['uid'];
                $files[$k]['id'] = $v['fileid'];
                $files[$k]['posttime'] = date( "Y-m-d H:i", $v['posttime'] );
                if ( $dirPermit['dl'] || $dirPermit['mgr'] == 1 )
                {
                    $files[$k]['downpath'] = makedownloadiconm( "{GLOBALS\$['URL_FILE']}/common/disk/public/".$v['storepath']."/{$v['storename']}", $v['name'].".".$v['ext'], "href" );
                    $files[$k]['downhref'] = makedownloadiconm( "{$GLOBALS['URL_FILE']}/common/disk/public/{$v['storepath']}/{$v['storename']}", $v['name'].".".$v['ext'], "html" );
                    $files[$k]['dl'] = TRUE;
                }
                if ( $dirPermit['sh'] || $dirPermit['mgr'] == 1 )
                {
                    $files[$k]['sh'] = TRUE;
                }
                if ( $dirPermit['ed'] || $dirPermit['mgr'] == 1 )
                {
                    $files[$k]['downpath'] = makedownloadiconm( "{GLOBALS\$['URL_FILE']}/common/disk/public/".$v['storepath']."/{$v['storename']}", $v['name'].".".$v['ext'], "href" );
                    $files[$k]['edithref'] = makedownloadiconm( "{$GLOBALS['URL_FILE']}/common/disk/public/{$v['storepath']}/{$v['storename']}", $v['name'].".".$v['ext'], "html" );
                    $files[$k]['ed'] = TRUE;
                }
                if ( $dirPermit['vi'] || $dirPermit['mgr'] == 1 )
                {
                    $files[$k]['downpath'] = makedownloadiconm( "{GLOBALS\$['URL_FILE']}/common/disk/public/".$v['storepath']."/{$v['storename']}", $v['name'].".".$v['ext'], "href" );
                    $files[$k]['viewhref'] = makedownloadiconm( "{$GLOBALS['URL_FILE']}/common/disk/public/{$v['storepath']}/{$v['storename']}", $v['name'].".".$v['ext'], "html" );
                    $files[$k]['vi'] = TRUE;
                }
                $files[$k]['size'] = sizeformat( $v['size'] );
                $files[$k]['type'] = "f";
                $files[$k]['ext'] = $v['ext'];
                $dirs[] = $files[$k];
            }
        }
        $truename = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr );
        foreach ( $dirs as $k => $v )
        {
            $dirs[$k]['postname'] = $truename[$v['uid']]['truename'];
        }
        foreach ( $dirPermit as $k => $v )
        {
            eval( "\$dataStore->\$k=\$v;" );
        }
        $dataStore->data = $dirs;
        $dataStore->total = $total;
        $dataStore->nowpath = "/".str_replace( "-", "/", $dir['path2'] );
        $dataStore->paterId = $dir['pid'] ? $dir['pid'] : "0";
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _rename( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $did = $CNOA_SESSION->get( "DID" );
        $name = getpar( $_POST, "name", "" );
        $fid = intval( getpar( $_POST, "fid", 0 ) );
        $pid = intval( getpar( $_POST, "pid", 0 ) );
        $type = getpar( $_POST, "type", "" );
        $ftype = getpar( $_POST, "ftype", "d" );
        $uid = $CNOA_SESSION->get( "UID" );
        if ( $ftype == "f" )
        {
            $file = $CNOA_DB->db_getone( "*", $this->table_public_file, "WHERE `fileid` = '".$fid."' " );
            $fid = $file['fid'];
        }
        if ( $type == "add" )
        {
            $fid = $pid;
        }
        $dir = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$fid."'" );
        $dirPermit = $this->__getDirPermit( $dir );
        if ( $dirPermit['mgr'] != "1" )
        {
            msg::callback( FALSE, lang( "noPermitOpt" ) );
        }
        if ( empty( $name ) )
        {
            msg::callback( FALSE, lang( "fileFoldNameNotEmpty" ) );
        }
        if ( $type == "add" )
        {
            $data = array( );
            $data['name'] = $name;
            $data['uid'] = $uid;
            $data['pid'] = $pid;
            $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $num = $CNOA_DB->db_getcount( $this->table_public, "WHERE `pid` = '".$pid."' AND `name` = '{$name}' " );
            if ( !empty( $num ) )
            {
                msg::callback( FALSE, lang( "folderExist" ) );
            }
            if ( $pid == 0 )
            {
                if ( !$this->isAdmin )
                {
                    msg::callback( FALSE, lang( "notAdminToAddFolderInRoot" ) );
                }
                $data['path'] = 0;
            }
            else
            {
                $path = $CNOA_DB->db_getfield( "path", $this->table_public, "WHERE `fid` = '".$pid."' " );
                $data['path'] = $path."-".$pid;
            }
            $newfid = $CNOA_DB->db_insert( $data, $this->table_public );
            insertdisklog( 6, 1, $data['path']."-".$newfid, $newfid, $name );
            $CNOA_DB->db_update( array(
                "path2" => $data['path']."-".$newfid
            ), $this->table_public, "WHERE `fid` = '".$newfid."' " );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 30, $data['name'], lang( "folder" ) );
        }
        else if ( $ftype == "d" )
        {
            $data = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$fid."'" );
            $num = $CNOA_DB->db_getcount( $this->table_public, "WHERE `pid` = '".$data['pid']."' AND `name` = '{$name}'" );
            if ( !empty( $num ) )
            {
                msg::callback( FALSE, lang( "folderExist" ) );
            }
            if ( $data !== FALSE )
            {
                $other = "[".$data['name']."]->[".$name."]";
                insertdisklog( 7, 1, $data['path2'], $fid, $other );
                $CNOA_DB->db_update( array(
                    "name" => $name
                ), $this->table_public, "WHERE `fid`='".$fid."'" );
                app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 30, array(
                    "{$data['name']}" => "{$name}"
                ), lang( "folderName" ) );
            }
            $newfid = $fid;
        }
        else if ( $ftype == "f" )
        {
            $num = $CNOA_DB->db_getcount( $this->table_public_file, "WHERE `fid` = '".$file['fid']."' AND `name` = '{$name}' AND `ext` = '{$file['ext']}' " );
            if ( !empty( $num ) )
            {
                msg::callback( FALSE, lang( "fileExist" ) );
            }
            if ( $data !== FALSE )
            {
                $other = "[".$file['name']."]->[".$name."]";
                insertdisklog( 7, 0, $file['path'], $file['fileid'], $other );
                $CNOA_DB->db_update( array(
                    "name" => $name
                ), $this->table_public_file, "WHERE `fileid`='".$file['fileid']."'" );
                app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 30, array(
                    "{$file['name']}.{$file['ext']}" => "{$name}.{$file['ext']}"
                ), lang( "fileName" ) );
            }
            $newfid = $fid;
        }
        msg::callback( TRUE, $newfid );
    }

    public function _uploadFolder( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $fn = isset( $_SERVER['HTTP_X_FILENAME'] ) ? $_SERVER['HTTP_X_FILENAME'] : FALSE;
        $uid = $CNOA_SESSION->get( "UID" );
        if ( $fn )
        {
            $type = getpar( $_GET, "type", "" );
            $size = getpar( $_GET, "size", 0 );
            $fname = $_GET['fname'];
            if ( $fname != "" && $fname != "undefined" )
            {
                if ( $type == "folder" )
                {
                    $fname = substr( $fname, 0, -2 );
                    $name = str_replace( "/", "", substr( $fname, strripos( $fname, "/" ) ) );
                    $CNOA_DB->db_insert( array(
                        "path" => $fname,
                        "uid" => $uid,
                        "name" => $name
                    ), $this->user_disk_folder );
                    exit( );
                }
                $fileName = getpar( $_GET, "filename" );
                $postiionPoint = strripos( $fileName, "." );
                $insert = array( );
                $insert['uid'] = $uid;
                $insert['name'] = substr( $fileName, 0, $postiionPoint );
                $insert['ext'] = substr( $fileName, $postiionPoint + 1 );
                $insert['storepath'] = date( "Y/m", $GLOBALS['CNOA_TIMESTAMP'] );
                $insert['storename'] = string::rands( 50 ).".cnoa";
                $insert['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
                $insert['size'] = getpar( $_GET, "size", 0 );
                $fileid = $CNOA_DB->db_insert( $insert, $this->table_public_file );
                $insert2 = array( );
                $insert2['fileid'] = $fileid;
                $insert2['path'] = substr( $fname, 0, strripos( $fname, "/" ) );
                $insert2['uid'] = $uid;
                $CNOA_DB->db_insert( $insert2, $this->user_disk_folder_file );
                $targetdir = CNOA_PATH_FILE."/common/disk/public/".date( "Y", $GLOBALS['CNOA_TIMESTAMP'] )."/".date( "m", $GLOBALS['CNOA_TIMESTAMP'] );
                @mkdirs( $targetdir );
                $targetfile = $targetdir."/".$insert['storename'];
                if ( $this->download( "php://input", $targetfile ) )
                {
                    echo "ok";
                    exit( );
                }
                echo "failed";
            }
        }
        exit( );
    }

    private function _finishFolder( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $pid = getpar( $_GET, "pid", 0 );
        $db = $CNOA_DB->db_select( "*", $this->user_disk_folder, "WHERE `uid` = ".$uid." ORDER BY `path` ASC" );
        $dblistFile = $CNOA_DB->db_select( "*", $this->user_disk_folder_file, "WHERE `uid` = ".$uid." " );
        if ( empty( $db ) )
        {
            $db = array(
                array(
                    "name" => $dblistFile[0]['path'],
                    "path" => $dblistFile[0]['path'],
                    "uid" => $uid
                )
            );
        }
        if ( !is_array( $db ) )
        {
            $db = array( );
        }
        $data = array( );
        $dblist = array( );
        $pathArr = explode( "/", $db[0]['path'] );
        if ( count( $pathArr ) == 2 )
        {
            $dblist[0] = array(
                "name" => $pathArr[0],
                "path" => $pathArr[0],
                "uid" => $uid
            );
            foreach ( $db as $k => $v )
            {
                $dblist[] = $v;
            }
        }
        else
        {
            $dblist = $db;
        }
        foreach ( $dblist as $k => $v )
        {
            if ( $k == 0 )
            {
                $dblist[$k]['pid'] = $pid;
            }
            else
            {
                $dblist[$k]['pid'] = 0;
            }
        }
        foreach ( $dblist as $k => $v )
        {
            $data[$v['path']] = $v;
        }
        foreach ( $dblist as $k => $v )
        {
            $insert = array( );
            $insert['name'] = $v['name'];
            $insert['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $fid = $CNOA_DB->db_insert( $insert, $this->table_public );
            $data[$v['path']]['fid'] = $fid;
            if ( empty( $v['pid'] ) )
            {
                $keyPath = str_replace( "/".$v['name'], "", $v['path'] );
                $data[$v['path']]['pid'] = $data[$keyPath]['fid'];
                $data[$v['path']]['path'] = $data[$keyPath]['path2'];
                $data[$v['path']]['path2'] = $data[$keyPath]['path2']."-".$fid;
            }
            else
            {
                $firstPid = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid` = ".$v['pid']." " );
                $data[$v['path']]['path'] = $firstPid['path2'];
                $data[$v['path']]['path2'] = $firstPid['path2']."-".$fid;
            }
        }
        if ( !is_array( $dblistFile ) )
        {
            $dblistFile = array( );
        }
        foreach ( $dblistFile as $k => $v )
        {
            $update['fid'] = $data[$v['path']]['fid'];
            $CNOA_DB->db_update( $update, $this->table_public_file, "WHERE `fileid` = ".$v['fileid']." " );
        }
        foreach ( $data as $k => $v )
        {
            unset( $v['id'] );
            $CNOA_DB->db_update( $v, $this->table_public, "WHERE `fid` = ".$v['fid']." " );
        }
        $CNOA_DB->db_delete( $this->user_disk_folder, "WHERE `uid` = ".$uid." " );
        $CNOA_DB->db_delete( $this->user_disk_folder_file, "WHERE `uid` = ".$uid." " );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function download( $file_source, $file_target )
    {
        $rh = fopen( $file_source, "rb" );
        $wh = fopen( $file_target, "wb" );
        if ( $rh === FALSE || $wh === FALSE )
        {
            return FALSE;
        }
        while ( !feof( $rh ) )
        {
            if ( !( fwrite( $wh, fread( $rh, 1024 ) ) === FALSE ) )
            {
                continue;
            }
            return FALSE;
        }
        fclose( $rh );
        fclose( $wh );
        return TRUE;
    }

    public function _upload( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        @ini_set( "default_socket_timeout", "86400" );
        @ini_set( "max_input_time", "86400" );
        set_time_limit( 0 );
        $fid = getpar( $_POST, "pid", 0 );
        $note = getpar( $_POST, "note", "" );
        $uid = $CNOA_SESSION->get( "UID" );
        $did = $CNOA_SESSION->get( "DID" );
        $success = TRUE;
        $dir = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$fid."'" );
        $dirPermit = $this->__getDirPermit( $dir );
        if ( !( $dirPermit['up'] == "1" ) || !( $dirPermit['mgr'] == "1" ) )
        {
            msg::callback( FALSE, lang( "noPermitOpt" ) );
        }
        if ( !isset( $_FILES['Filedata'] ) )
        {
            $upload_good = lang( "fileTooBigToUpload" );
            $success = FALSE;
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
        $storePath = date( "Y", $GLOBALS['CNOA_TIMESTAMP'] )."/".date( "m", $GLOBALS['CNOA_TIMESTAMP'] );
        $userPath = CNOA_PATH_FILE."/common/disk/public/".$storePath;
        @mkdirs( $userPath );
        if ( $success )
        {
            $fileext = strtolower( strrchr( $_FILES['Filedata']['name'], "." ) );
            $filename = string::rands( 50 ).".cnoa";
            $file_dst = $userPath."/".$filename;
            filterphpfileupload( );
            @move_uploaded_file( $_FILES['Filedata']['tmp_name'], $file_dst );
            $pid = getpar( $_POST, "pid", 0 );
            $data = array( );
            $data['uid'] = $uid;
            $data['fid'] = $pid;
            $data['name'] = preg_replace( "/(.*)".$fileext."\$/i", "\\1", $_FILES['Filedata']['name'] );
            $data['ext'] = str_replace( ".", "", $fileext );
            $data['storename'] = $filename;
            $data['storepath'] = $storePath;
            $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $data['size'] = $_FILES['Filedata']['size'];
            $data['note'] = $note;
            $data['path'] = $CNOA_DB->db_getfield( "path", $this->table_public, "WHERE `fid` = '".$pid."' " )."-".$pid;
            $DB = $CNOA_DB->db_getone( "*", $this->table_public_file, "WHERE `fid` = '".$pid."' AND `name` = '{$data['name']}' AND `ext` = '{$data['ext']}' " );
            if ( empty( $DB ) )
            {
                $fileid = $CNOA_DB->db_insert( $data, $this->table_public_file );
                insertdisklog( 0, 0, $data['path'], $fileid );
            }
            else
            {
                $versions['type'] = 0;
                $versions['fileid'] = $DB['fileid'];
                $versions['storename'] = $DB['storename'];
                $versions['storepath'] = $DB['storepath'];
                $versions['posttime'] = $DB['posttime'];
                $versions['size'] = $DB['size'];
                $versions['note'] = $DB['note'];
                $versions['uid'] = $DB['uid'];
                $CNOA_DB->db_insert( $versions, $this->table_versions );
                if ( !empty( $DB['thumb'] ) )
                {
                    $thumbPath = CNOA_PATH_FILE.( "/common/disk/public/".$DB['storepath']."/" ).$DB['thumb'].".".$DB['ext'];
                    @unlink( $thumbPath );
                    $thumbPath = CNOA_PATH_FILE.( "/common/disk/public/".$DB['storepath']."/" ).$DB['thumb'].".mid.".$DB['ext'];
                    @unlink( $thumbPath );
                    $data['thumb'] = "";
                }
                $CNOA_DB->db_update( $data, $this->table_public_file, "WHERE `fileid` = '".$DB['fileid']."' " );
                insertdisklog( 1, 0, $DB['path'], $DB['fileid'] );
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 30, "{$data['name']}\\.{$data['ext']}", "文件" );
        }
        msg::callback( TRUE, $fileid, $upload_good );
    }

    private function __checkMyFieldPermit( $fileid, $path )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $did = $CNOA_SESSION->get( "DID" );
        if ( $this->isAdmin )
        {
            return TRUE;
        }
        if ( empty( $path ) )
        {
            $pathArr = array( 0 );
        }
        else
        {
            $pathArr = explode( "-", $path );
        }
        $num = $CNOA_DB->db_getcount( $this->table_public_permit_p, "WHERE (`path` = '".$path."' AND (`dl` = 1 OR `vi` = 1)) OR (`fid` IN (".implode( ",", $pathArr ).") AND `mgr` = 1 )" );
        if ( empty( $num ) )
        {
            $num = $CNOA_DB->db_getcount( $this->table_public_permit_s, "WHERE (`path` = '".$path."' AND (`dl` = 1 OR `vi` = 1)) OR (`fid` IN (".implode( ",", $pathArr ).") AND `mgr` = 1 )" );
            if ( empty( $num ) )
            {
                return FALSE;
            }
            return TRUE;
        }
        return TRUE;
    }

    private function _getVersionsList( )
    {
        global $CNOA_DB;
        $fileid = getpar( $_GET, "fileid", 0 );
        $DB = $CNOA_DB->db_getone( array( "path", "name", "ext" ), $this->table_public_file, "WHERE `fileid` = '".$fileid."' " );
        if ( !$this->__checkMyFieldPermit( $fileid, $DB['path'] ) )
        {
            exit( );
        }
        $dblist = $CNOA_DB->db_select( "*", $this->table_versions, "WHERE `fileid` = '".$fileid."' ORDER BY `id` DESC " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $uidArr = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $uidArr[] = $v['uid'];
        }
        $trueName = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr );
        $num = count( $dblist );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['num'] = $num;
            $dblist[$k]['posttime'] = formatdate( $v['posttime'], "Y-m-d H:i" );
            $dblist[$k]['truename'] = $trueName[$v['uid']]['truename'];
            $dblist[$k]['down'] = makedownloadicon( "{$GLOBALS['URL_FILE']}/common/disk/public/{$v['storepath']}/{$v['storename']}", $DB['name'].".".$DB['ext'], "html" );
            $dblist[$k]['view'] = "&nbsp;".makeattachpreviewicon( $v['name'].".".$v['ext'], "{$GLOBALS['URL_FILE']}/common/disk/public/{$v['storepath']}/{$v['storename']}", ".".$DB['ext'] );
            --$num;
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $dblist;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _getLogList( )
    {
        global $CNOA_DB;
        $type = getpar( $_GET, "type", 0 );
        $id = getpar( $_GET, "id", 0 );
        $type == "d" ? ( $type = 1 ) : ( $type = 0 );
        $dblist = $CNOA_DB->db_select( "*", $this->table_log, "WHERE `type` = '".$type."' AND `fileid` = '{$id}' ORDER BY `id` DESC " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['log'] = $this->logArr[$v['log']].$v['other'];
            $dblist[$k]['posttime'] = formatdate( $v['posttime'], "Y-m-d H:i" );
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $dblist;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _saveasdisk( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $did = $CNOA_SESSION->get( "JID" );
        $uid = $CNOA_SESSION->get( "UID" );
        $ids = substr( getpar( $_POST, "ids", 0 ), 0, -1 );
        $idArr = explode( ",", $ids );
        $dir = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid` IN ('".$ids."')" );
        $dirPermit = $this->__getDirPermit( $dir );
        if ( !( $dirPermit['up'] == "1" ) || !( $dirPermit['mgr'] == "1" ) )
        {
            msg::callback( FALSE, lang( "noPermitUploadInFolder", $dir['name'] ) );
        }
        $data = getpar( $_POST, "data", "" );
        $name = getpar( $_POST, "name", "" );
        $dataArr = explode( ",", $data );
        $nameArr = explode( ",", $name );
        $from = getpar( $_POST, "from", "" );
        foreach ( $dataArr as $k => $v )
        {
            $result = $this->__formatFrom( $from, $v );
            $storePath = date( "Y", $GLOBALS['CNOA_TIMESTAMP'] )."/".date( "m", $GLOBALS['CNOA_TIMESTAMP'] );
            $dest = CNOA_PATH_FILE."/common/disk/public/".$storePath;
            @mkdirs( $dest );
            $size = filesize( $result['path'] );
            foreach ( $idArr as $id )
            {
                $filename = string::rands( 50 ).".cnoa";
                $newDir = $dest."/".$filename;
                copy( $result['path'], $newDir );
                $insert['fid'] = $id;
                $insert['uid'] = $uid;
                $insert['name'] = $nameArr[$k];
                $insert['ext'] = $result['ext'];
                $insert['size'] = $size;
                $insert['storename'] = $filename;
                $insert['storepath'] = $storePath;
                $insert['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
                $insert['path'] = $CNOA_DB->db_getfield( "path2", $this->table_public, "WHERE `fid` = '".$id."' " );
                $CNOA_DB->db_insert( $insert, $this->table_public_file );
            }
            @unlink( $result['path'] );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _saveasdiskandfs( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $did = $CNOA_SESSION->get( "JID" );
        $uid = $CNOA_SESSION->get( "UID" );
        $ids = substr( getpar( $_POST, "ids", 0 ), 0, -1 );
        $idArr = explode( ",", $ids );
        $fids = substr( getpar( $_GET, "fids", getpar( $_POST, "fids" ) ), 0, -1 );
        $fidArr = explode( ",", $fids );
        $dir = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid` IN ('".$ids."')" );
        $dirPermit = $this->__getDirPermit( $dir );
        if ( !( $dirPermit['up'] == "1" ) || !( $dirPermit['mgr'] == "1" ) )
        {
            msg::callback( FALSE, lang( "noPermitUploadInFolder", $dir['name'] ) );
        }
        $data = getpar( $_POST, "data", "" );
        $name = getpar( $_POST, "name", "" );
        $dataArr = explode( ",", $data );
        $nameArr = explode( ",", $name );
        $from = getpar( $_POST, "from", "" );
        foreach ( $idArr as $k => $id )
        {
            $newFolder = array( );
            $newFolder['name'] = $nameArr[0];
            $newFolder['uid'] = $uid;
            $newFolder['pid'] = $id;
            $newFolder['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $num = $CNOA_DB->db_getcount( $this->table_public, "WHERE `pid` = '".$id."' AND `name` = '{$newFolder['name']}' " );
            if ( !empty( $num ) )
            {
                msg::callback( FALSE, lang( "folderExist" ) );
            }
            if ( $id == 0 )
            {
                if ( !$this->isAdmin )
                {
                    msg::callback( FALSE, lang( "notAdminToAddFolderInRoot" ) );
                }
                $newFolder['path'] = 0;
            }
            else
            {
                $path = $CNOA_DB->db_getfield( "path", $this->table_public, "WHERE `fid` = '".$id."' " );
                $newFolder['path'] = $path."-".$id;
            }
            $newfid = $CNOA_DB->db_insert( $newFolder, $this->table_public );
            $idArr[$k] = $newfid;
            insertdisklog( 6, 1, $newFolder['path']."-".$newfid, $newfid, $name );
            $CNOA_DB->db_update( array(
                "path2" => $newFolder['path']."-".$newfid
            ), $this->table_public, "WHERE `fid` = '".$newfid."' " );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 30, $newFolder['name'], lang( "folder" ) );
        }
        foreach ( $dataArr as $k => $v )
        {
            $result = $this->__formatFrom( $from, $v );
            $storePath = date( "Y", $GLOBALS['CNOA_TIMESTAMP'] )."/".date( "m", $GLOBALS['CNOA_TIMESTAMP'] );
            $dest = CNOA_PATH_FILE."/common/disk/public/".$storePath;
            @mkdirs( $dest );
            $size = filesize( $result['path'] );
            foreach ( $idArr as $id )
            {
                $filename = string::rands( 50 ).".cnoa";
                $newDir = $dest."/".$filename;
                copy( $result['path'], $newDir );
                $insert['fid'] = $id;
                $insert['uid'] = $uid;
                $insert['name'] = $nameArr[$k];
                $insert['ext'] = $result['ext'];
                $insert['size'] = $size;
                $insert['storename'] = $filename;
                $insert['storepath'] = $storePath;
                $insert['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
                $insert['path'] = $CNOA_DB->db_getfield( "path2", $this->table_public, "WHERE `fid` = '".$id."' " );
                $CNOA_DB->db_insert( $insert, $this->table_public_file );
                foreach ( $fidArr as $key => $fid )
                {
                    $fileInfo = $CNOA_DB->db_getone( "*", "system_fs", "WHERE `id`='".$fid."'" );
                    $oldDir = CNOA_PATH_FILE."/common/vfs/".substr( $fileInfo[name], 0, 4 )."/".substr( $fileInfo[name], 4, 2 )."/".$fileInfo[name];
                    $filename = string::rands( 50 ).".cnoa";
                    $newDir = $dest."/".$filename;
                    copy( $oldDir, $newDir );
                    $oldName = explode( ".", $fileInfo[oldname] );
                    $insert['fid'] = $id;
                    $insert['uid'] = $uid;
                    $insert['name'] = substr( $fileInfo[oldname], 0, -1 - strlen( $oldName[1] ) );
                    $insert['ext'] = $oldName[1];
                    $insert['size'] = filesize( $oldDir );
                    $insert['storename'] = $filename;
                    $insert['storepath'] = $storePath;
                    $insert['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
                    $insert['path'] = $CNOA_DB->db_getfield( "path2", $this->table_public, "WHERE `fid` = '".$id."' " );
                    $CNOA_DB->db_insert( $insert, $this->table_public_file );
                }
            }
            @unlink( $result['path'] );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _submitShare( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $fileid = getpar( $_POST, "fileid", 0 );
        $dblist = $CNOA_DB->db_getone( "*", $this->table_public_file, "WHERE `fileid` = ".$fileid." " );
        $destPath = CNOA_PATH_FILE."/common/disk/user/".$uid;
        $fileName = string::rands( 50 ).".cnoa";
        @mkdirs( $destPath );
        $storeArr = explode( "/", $dblist['storepath'] );
        $sourcePath = CNOA_PATH_FILE."/common/disk/public/".$storeArr[0]."/".$storeArr[1]."/".$dblist['storename'];
        @copy( $sourcePath, $destPath."/".$fileName );
        $insert = array( );
        $insert['name'] = $dblist['name'];
        $insert['ext'] = $dblist['ext'];
        $insert['storename'] = $fileName;
        $insert['type'] = "sf";
        $insert['pid'] = "0";
        $insert['path'] = "0";
        $insert['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $insert['size'] = $dblist['size'];
        $insert['downLoad'] = getpar( $_POST, "download", "" ) == "on" ? 1 : 0;
        $insert['edit'] = getpar( $_POST, "edit", "" ) == "on" ? 1 : 0;
        $insert['sharefrom'] = $uid;
        $insert['disTime'] = getpar( $_POST, "disTime", "" ) == "" ? "" : strtotime( getpar( $_POST, "disTime", "" ) );
        $insert['disDownload'] = getpar( $_POST, "disDownload", 0 );
        $insert['disView'] = getpar( $_POST, "disView", 0 );
        $outsideLink = getpar( $_POST, "outsideLink", "" );
        $rand = FALSE;
        $randomUrl = "";
        if ( $outsideLink == "on" )
        {
            $randomUrl = $this->__createOutsideLink( $insert, $fileid );
            $rand = TRUE;
        }
        $uids = getpar( $_POST, "peopleUid", 0 );
        $uidArr = explode( ",", $uids );
        $insert['email'] = getpar( $_POST, "email", "" ) == "on" ? 0 : 1;
        foreach ( $uidArr as $k => $v )
        {
            $insert['uid'] = $v;
            $id = $CNOA_DB->db_insert( $insert, $this->table_list );
            $CNOA_DB->db_update( array(
                "path2" => "0-".$id
            ), $this->table_list, "WHERE `fid`='".$id."'" );
        }
        $deptIds = getpar( $_POST, "deptIds", 0 );
        $uidArr = app::loadapp( "main", "user" )->api_getUidsByDeptIdList( $deptIds );
        if ( !is_array( $uidArr ) )
        {
            $uidArr = array( );
        }
        foreach ( $uidArr as $k => $v )
        {
            $insert['uid'] = $v['uid'];
            $id = $CNOA_DB->db_insert( $insert, $this->table_list );
            $CNOA_DB->db_update( array(
                "path2" => "0-".$id
            ), $this->table_list, "WHERE `fid`='".$id."'" );
        }
        $CNOA_DB->db_update( array(
            "sharedpeopleUid" => $uids
        ), $this->table_public_file, "WHERE `fileid` = ".$fileid." " );
        app::loadapp( "user", "diskIndex" )->api_tidyDisk( );
        echo json_encode( array(
            "success" => TRUE,
            "msg" => lang( "successopt" ),
            "rand" => $rand,
            "randomUrl" => $randomUrl
        ) );
        exit( );
    }

    public function api_outsidelink( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        $key = getpar( $_GET, "key", "" );
        if ( empty( $key ) || strlen( $key ) != 50 )
        {
            echo lang( "wrongDiskLink" );
            exit( );
        }
        $db = $CNOA_DB->db_getone( "*", $this->table_outsideLink, "WHERE `randomnum` = '".$key."' " );
        if ( empty( $db ) )
        {
            echo lang( "linkExpire" );
            exit( );
        }
        if ( !empty( $db['disTime'] ) || $db['disTime'] < $GLOBALS['CNOA_TIMESTAMP'] )
        {
            $CNOA_DB->db_delete( $this->table_outsideLink, "WHERE `randomnum` = '".$key."' " );
            echo lang( "linkExpire" );
            exit( );
        }
        $fileDB = $CNOA_DB->db_getone( "*", $this->table_public_file, "WHERE `fileid` = ".$db['fid']." " );
        $viewIcon = makeattachpreviewicon( $fileDB['name'].".".$fileDB['ext'], "{$GLOBALS['URL_FILE']}/common/disk/public/{$fileDB['storepath']}/{$fileDB['storename']}", ".".$fileDB['ext'] );
        if ( !empty( $db['disView'] ) )
        {
            $viewIcon = str_replace( "'>".lang( "browse" )."</a>", "' onclick='startAjax(\"view\");'>".lang( "browse" )."</a>", $viewIcon );
        }
        $html = $viewIcon;
        if ( $db['download'] )
        {
            $downLoadIcon = makedownloadicon( "{$GLOBALS['URL_FILE']}/common/disk/public/{$fileDB['storepath']}/{$fileDB['storename']}", $fileDB['name'].".".$fileDB['ext'], "html" );
            if ( !empty( $db['disDownload'] ) )
            {
                $downLoadIcon = str_replace( "'>".lang( "download" )."</a>", "' onclick='startAjax(\"download\");'>".lang( "download" )."</a>", $downLoadIcon );
            }
            $html .= $downLoadIcon;
        }
        if ( $db['edit'] )
        {
            $editIcon = makediskediticon( "{$GLOBALS['URL_FILE']}/common/disk/public/{$fileDB['storepath']}/{$fileDB['storename']}", ".".$fileDB['ext'], TRUE, "disk_pub", $fileDB['fileid'], $fileDB['storename'], $fileDB['name'], "pubdisk" );
            if ( !empty( $db['disView'] ) )
            {
                $editIcon = str_replace( "'>".lang( "modify" )."</a>", "' onclick='startAjax(\"view\");'>".lang( "modify" )."</a>", $editIcon );
            }
            $html .= $editIcon;
        }
        $GLOBALS['GLOBALS']['outsidelink']['html'] = $html;
        $GLOBALS['GLOBALS']['outsidelink']['key'] = $key;
        $tplPath = CNOA_PATH."/app/user/tpl/default/disk/outsidelink.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    public function api_outsidelinkhandler( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $key = getpar( $_GET, "key", "" );
        $db = $CNOA_DB->db_getone( "*", $this->table_outsideLink, "WHERE `randomnum` = '".$key."' " );
        $from = getpar( $_GET, "from", "" );
        if ( $from == "view" )
        {
            if ( $db['viewTimes'] == $db['disView'] )
            {
                $reflash = TRUE;
                $CNOA_DB->db_delete( $this->table_outsideLink, "WHERE `randomnum` = '".$key."' " );
            }
            else
            {
                $CNOA_DB->db_update( array(
                    "viewTimes" => $db['viewTimes'] + 1
                ), $this->table_outsideLink, "WHERE `randomnum` = '".$key."' " );
                $reflash = FALSE;
            }
        }
        if ( $from == "download" )
        {
            if ( $db['downLoadTimes'] == $db['disDownload'] )
            {
                $reflash = TRUE;
                $CNOA_DB->db_delete( $this->table_outsideLink, "WHERE `randomnum` = '".$key."' " );
            }
            else
            {
                $CNOA_DB->db_update( array(
                    "downLoadTimes" => $db['downLoadTimes'] + 1
                ), $this->table_outsideLink, "WHERE `randomnum` = '".$key."' " );
                $reflash = FALSE;
            }
        }
        echo json_encode( array(
            "success" => TRUE,
            "reflash" => $reflash
        ) );
        exit( );
    }

    private function __createOutsideLink( $insert, $fileid )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $data['fid'] = $fileid;
        $data['download'] = $insert['downLoad'];
        $data['edit'] = $insert['edit'];
        $data['disTime'] = $insert['disTime'];
        $data['disDownload'] = $insert['disDownload'];
        $data['downLoadTimes'] = $insert['downLoadTimes'];
        $data['disView'] = $insert['disView'];
        $data['viewTimes'] = $insert['viewTimes'];
        $data['randomnum'] = string::rands( 50 );
        if ( empty( $data['disTime'] ) && empty( $data['disDownload'] ) && empty( $data['disView'] ) )
        {
            $data['disTime'] = $GLOBALS['CNOA_TIMESTAMP'] + 691200;
        }
        $CNOA_DB->db_insert( $data, $this->table_outsideLink );
        $port = "";
        if ( $_SERVER['SERVER_PORT'] != 80 )
        {
            $port = ":".$_SERVER['SERVER_PORT'];
        }
        $url = "http://".$_SERVER['SERVER_NAME'].$port."/index.php?action=commonJob&act=outsidelink&key=".$data['randomnum'];
        return $url;
    }

    private function __formatFrom( $from, $data )
    {
        switch ( $from )
        {
        case "reportWf" :
            $result['path'] = CNOA_PATH_FILE."/common/report/temp/".$data;
            $result['ext'] = "xlsx";
            return $result;
        case "wfExport" :
            $result['path'] = CNOA_PATH_FILE."/common/temp/".$data;
            $result['ext'] = "jpg";
            return $result;
        }
        return FALSE;
    }

    private function _delete( )
    {
        set_time_limit( 0 );
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $did = $CNOA_SESSION->get( "DID" );
        $ids = $_POST['ids'];
        $ids = json_decode( $ids, TRUE );
        $type = getpar( $_POST, "type", "" );
        $myJobType = $CNOA_SESSION->get( "JOBTYPE" );
        if ( !is_array( $ids ) )
        {
            msg::callback( FALSE, lang( "error" ) );
        }
        if ( count( $ids ) < 1 )
        {
            msg::callback( FALSE, lang( "error" ) );
        }
        $parentId = 0;
        if ( $ids[0]['type'] == "f" )
        {
            $parentId = $CNOA_DB->db_getfield( "fid", $this->table_public_file, "WHERE `fileid`='".$ids[0]['id']."'" );
        }
        else
        {
            $parentId = $CNOA_DB->db_getfield( "pid", $this->table_public, "WHERE `fid`='".$ids[0]['id']."'" );
        }
        $dir = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$parentId."'" );
        $dirPermit = $this->__getDirPermit( $dir );
        if ( $dirPermit['dl'] != "1" )
        {
            msg::callback( FALSE, lang( "noPermitOpt" ) );
        }
        foreach ( $ids as $v )
        {
            if ( $v['type'] == "f" )
            {
                $this->__deleteFile( $v['id'] );
            }
            else if ( $v['type'] == "d" )
            {
                $this->__deleteDir( $v['id'] );
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function __deleteDir( $fid )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $dir = $CNOA_DB->db_getone( array( "name", "path2" ), $this->table_public, "WHERE `fid` = '".$fid."'" );
        $path2 = $dir['path2'];
        $dblist = $CNOA_DB->db_select( array( "fid" ), $this->table_public, "WHERE `path2` LIKE '".$path2."-%' OR `path2`='{$path2}'" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $fidArr = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $fidArr[] = $v['fid'];
        }
        $files = $CNOA_DB->db_select( "*", $this->table_public_file, "WHERE `fid` IN (".implode( ",", $fidArr ).")" );
        if ( !is_array( $files ) )
        {
            $files = array( );
        }
        foreach ( $files as $k => $v )
        {
            $this->__deleteFile( $v['fileid'], FALSE );
        }
        $CNOA_DB->db_delete( $this->table_public_file, "WHERE `fid` IN (".implode( ",", $fidArr ).") " );
        $CNOA_DB->db_delete( $this->table_public, "WHERE `fid` IN (".implode( ",", $fidArr ).") " );
        $CNOA_DB->db_delete( $this->table_public_permit_p, "WHERE `fid` IN (".implode( ",", $fidArr ).") " );
        $CNOA_DB->db_delete( $this->table_public_permit_s, "WHERE `fid` IN (".implode( ",", $fidArr ).") " );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 30, $dir['name'], lang( "folder" ) );
    }

    private function __deleteFile( $id, $noLogs = TRUE )
    {
        global $CNOA_DB;
        $data = $CNOA_DB->db_getone( "*", $this->table_public_file, "WHERE `fileid` = '".$id."' " );
        $DB = $CNOA_DB->db_select( "*", $this->table_versions, "WHERE `fileid` = '".$id."' " );
        if ( !is_array( $DB ) )
        {
            $DB = array( );
        }
        foreach ( $DB as $k => $v )
        {
            $userPath = CNOA_PATH_FILE.( "/common/disk/public/".$v['storepath']."/{$v['storename']}" );
            @unlink( $userPath );
            $thumbPath = CNOA_PATH_FILE.( "/common/disk/public/".$v['storepath']."/" ).$v['thumb'].".".$v['ext'];
            @unlink( $thumbPath );
            $thumbPath = CNOA_PATH_FILE.( "/common/disk/public/".$v['storepath']."/" ).$v['thumb'].".mid.".$v['ext'];
            @unlink( $thumbPath );
        }
        $CNOA_DB->db_delete( $this->table_public_file, "WHERE `fileid`='".$id."'" );
        $CNOA_DB->db_delete( $this->table_versions, "WHERE `fileid`='".$id."'" );
        $userPath = CNOA_PATH_FILE.( "/common/disk/public/".$data['storepath']."/{$data['storename']}" );
        @unlink( $userPath );
        $thumbPath = CNOA_PATH_FILE.( "/common/disk/public/".$data['storepath']."/" ).$data['thumb'].".".$data['ext'];
        @unlink( $thumbPath );
        $thumbPath = CNOA_PATH_FILE.( "/common/disk/public/".$data['storepath']."/" ).$data['thumb'].".mid.".$data['ext'];
        @unlink( $thumbPath );
        if ( $noLogs )
        {
            app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 30, "{$data['name']}\\.{$data['ext']}", lang( "file" ) );
        }
    }

    private function _getAllUserListsInPermitDeptTree( )
    {
        $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
        $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
        echo json_encode( $userList );
        exit( );
    }

    private function _permitListByM( )
    {
        global $CNOA_DB;
        $fid = getpar( $_GET, "fid", 0 );
        $data = array( );
        $uidArr = array( 0 );
        $folder = $CNOA_DB->db_getone( array( "extend", "path" ), $this->table_public, "WHERE `fid` = '".$fid."'" );
        if ( $folder['extend'] == 1 )
        {
            $parents = explode( "-", $folder['path'] );
            unset( $parents[0] );
            if ( 0 < count( $parents ) )
            {
                $pFolders = $CNOA_DB->db_select( array( "fid", "extend" ), $this->table_public, "WHERE `fid` IN (".implode( ",", $parents ).") ORDER BY `fid` DESC" );
                if ( !is_array( $pFolders ) )
                {
                    $pFolders = array( );
                }
                $fids = array( 0 );
                foreach ( $pFolders as $v )
                {
                    if ( !( $v['extend'] == "1" ) )
                    {
                        break;
                    }
                    $fids[] = $v['fid'];
                }
                $parentsPermits = $CNOA_DB->db_select( "*", $this->table_public_permit_p, "WHERE `fid` IN (".implode( ",", $fids ).") " );
                if ( !is_array( $parentsPermits ) )
                {
                    $parentsPermits = array( );
                }
                $data2 = array( );
                foreach ( $parentsPermits as $v )
                {
                    if ( empty( $data2[$v['uid']] ) )
                    {
                        $data2[$v['uid']] = $v;
                    }
                    else
                    {
                        foreach ( array( "dl", "sh", "up", "dt", "ed", "vi", "mgr" ) as $dpv )
                        {
                            if ( $v[$dpv] == 1 )
                            {
                                $data2[$v['uid']][$dpv] = 1;
                            }
                        }
                    }
                }
                foreach ( $data2 as $v )
                {
                    $uidArr[] = $v['uid'];
                    $v['extend'] = 1;
                    $data[] = $v;
                }
            }
        }
        $dblist = $CNOA_DB->db_select( "*", $this->table_public_permit_p, "WHERE `fid` = '".$fid."' " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $v )
        {
            $uidArr[] = $v['uid'];
            $data[] = $dblist[$k];
        }
        $trueName = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr );
        foreach ( $data as $k => $v )
        {
            $data[$k]['name'] = $trueName[$v['uid']]['truename'];
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        $ds->extend = $folder['extend'];
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _permitListByS( )
    {
        global $CNOA_DB;
        $fid = getpar( $_GET, "fid", 0 );
        $didArr = array( 0 );
        $folder = $CNOA_DB->db_getone( array( "extend", "path" ), $this->table_public, "WHERE `fid` = '".$fid."'" );
        $data = array( );
        if ( $folder['extend'] == 1 )
        {
            $parents = explode( "-", $folder['path'] );
            unset( $parents[0] );
            if ( 0 < count( $parents ) )
            {
                $pFolders = $CNOA_DB->db_select( array( "fid", "extend" ), $this->table_public, "WHERE `fid` IN (".implode( ",", $parents ).") ORDER BY `fid` DESC" );
                if ( !is_array( $pFolders ) )
                {
                    $pFolders = array( );
                }
                $fids = array( 0 );
                foreach ( $pFolders as $v )
                {
                    if ( !( $v['extend'] == "1" ) )
                    {
                        break;
                    }
                    $fids[] = $v['fid'];
                }
                $parentsPermits = $CNOA_DB->db_select( "*", $this->table_public_permit_s, "WHERE `fid` IN (".implode( ",", $fids ).") " );
                if ( !is_array( $parentsPermits ) )
                {
                    $parentsPermits = array( );
                }
                $data2 = array( );
                foreach ( $parentsPermits as $v )
                {
                    if ( empty( $data2[$v['did']] ) )
                    {
                        $data2[$v['did']] = $v;
                    }
                    else
                    {
                        foreach ( array( "dl", "sh", "up", "dt", "ed", "vi", "mgr" ) as $dpv )
                        {
                            if ( $v[$dpv] == 1 )
                            {
                                $data2[$v['did']][$dpv] = 1;
                            }
                        }
                    }
                }
                foreach ( $data2 as $v )
                {
                    $didArr[] = $v['did'];
                    $v['extend'] = 1;
                    $data[] = $v;
                }
            }
        }
        $dblist = $CNOA_DB->db_select( "*", $this->table_public_permit_s, "WHERE `fid` = '".$fid."' " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $v )
        {
            $didArr[] = $v['did'];
            $data[] = $dblist[$k];
        }
        $deptName = app::loadapp( "main", "struct" )->api_getNamesByIds( $didArr );
        foreach ( $data as $k => $v )
        {
            $data[$k]['name'] = $deptName[$v['did']];
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        $ds->extend = $folder['extend'];
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _addPermitM( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uids = getpar( $_POST, "uid", 0 );
        $fid = getpar( $_POST, "fid", 0 );
        $uid = $CNOA_SESSION->get( "UID" );
        if ( empty( $fid ) )
        {
            msg::callback( FALSE, lang( "optFail" ) );
        }
        $path = $CNOA_DB->db_getfield( "path", $this->table_public, "WHERE `fid` = '".$fid."' " );
        $path = $path."-".$fid;
        $uidArr = explode( ",", $uids );
        foreach ( $uidArr as $k => $v )
        {
            if ( !( $v == $uid ) )
            {
                $num = $CNOA_DB->db_getcount( $this->table_public_permit_p, "WHERE `fid` = '".$fid."' AND `uid` = '{$v}' " );
                if ( !empty( $num ) )
                {
                }
                else
                {
                    $CNOA_DB->db_insert( array(
                        "uid" => $v,
                        "fid" => $fid,
                        "path" => $path
                    ), $this->table_public_permit_p );
                }
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _addPermitS( )
    {
        global $CNOA_DB;
        $did = getpar( $_POST, "did", 0 );
        $fid = getpar( $_POST, "fid", 0 );
        $didArr = explode( ",", $did );
        if ( empty( $fid ) )
        {
            msg::callback( FALSE, lang( "optFail" ) );
        }
        $path = $CNOA_DB->db_getfield( "path", $this->table_public, "WHERE `fid` = '".$fid."' " );
        $path = $path."-".$fid;
        foreach ( $didArr as $k => $v )
        {
            $num = $CNOA_DB->db_getcount( $this->table_public_permit_s, "WHERE `fid` = '".$fid."' AND `did` = '{$v}' " );
            if ( empty( $num ) )
            {
                $CNOA_DB->db_insert( array(
                    "did" => $v,
                    "fid" => $fid,
                    "path" => $path
                ), $this->table_public_permit_s );
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _addPermitDataM( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $fid = getpar( $_POST, "fid", 0 );
        if ( empty( $fid ) )
        {
            msg::callback( FALSE, lang( "optFail" ) );
        }
        $uid = $CNOA_SESSION->get( "UID" );
        $did = $CNOA_SESSION->get( "DID" );
        $jobType = $CNOA_SESSION->get( "JOBTYPE" );
        $dir = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$fid."'" );
        $dirPermit = $this->__getDirPermit( $dir );
        if ( $dirPermit['mgr'] != "1" )
        {
            msg::callback( FALSE, lang( "noPermitOpt" ) );
        }
        $data = getpar( $_POST, "mem", array( ) );
        if ( empty( $data ) )
        {
            $CNOA_DB->db_delete( $this->table_public_permit_p, "WHERE `fid` = '".$fid."' " );
            msg::callback( TRUE, lang( "successopt" ) );
        }
        $update = array( );
        foreach ( $data as $k => $v )
        {
            if ( $v['mgr'] == 1 )
            {
                $update['mgr'] = 1;
                $update['vi'] = 1;
                $update['ed'] = 1;
                $update['dl'] = 1;
                $update['sh'] = 1;
                $update['up'] = 1;
                $update['dt'] = 1;
            }
            else
            {
                $update['vi'] = $v['vi'];
                $update['ed'] = $v['ed'];
                $update['dl'] = $v['dl'];
                $update['sh'] = $v['sh'];
                $update['up'] = $v['up'];
                $update['dt'] = $v['dt'];
                $update['mgr'] = 0;
            }
            $allPermitUserArr[] = $k;
            $CNOA_DB->db_update( $update, $this->table_public_permit_p, "WHERE `pid` = '".$k."' AND `fid` = '{$fid}' " );
        }
        if ( !empty( $allPermitUserArr ) )
        {
            $CNOA_DB->db_delete( $this->table_public_permit_p, "WHERE `fid` = '".$fid."' AND `pid` NOT IN (".implode( ",", $allPermitUserArr ).") " );
        }
        $path = $CNOA_DB->db_getfield( "path2", $this->table_public, "WHERE `fid`='".$fid."'" );
        $paths = explode( "-", $path );
        $where = "WHERE `fid` IN (";
        foreach ( $paths as $v )
        {
            if ( $v != 0 )
            {
                $where .= $v.",";
            }
        }
        $where = substr_replace( $where, ")", -1 );
        $fileNames = $CNOA_DB->db_select( array( "name" ), $this->table_public, $where );
        $filePath = "";
        $i = 0;
        for ( ; $i < count( $fileNames ); ++$i )
        {
            $filePath .= $fileNames[$i]['name']."/";
        }
        $filePath = substr_replace( $filePath, "", -1 );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 30, lang( "folderPermitOfUser", $filePath ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _addPermitDataS( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $fid = getpar( $_POST, "fid", 0 );
        if ( empty( $fid ) )
        {
            msg::callback( FALSE, lang( "optFail" ) );
        }
        $uid = $CNOA_SESSION->get( "UID" );
        $did = $CNOA_SESSION->get( "DID" );
        $dir = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$fid."'" );
        $dirPermit = $this->__getDirPermit( $dir );
        if ( $dirPermit['mgr'] != "1" )
        {
            msg::callback( FALSE, lang( "noPermitOpt" ) );
        }
        $data = getpar( $_POST, "dept", array( ) );
        $update = array( );
        foreach ( $data as $k => $v )
        {
            if ( $v['mgr'] == 1 )
            {
                $update['mgr'] = 1;
                $update['vi'] = 1;
                $update['ed'] = 1;
                $update['dl'] = 1;
                $update['sh'] = 1;
                $update['up'] = 1;
                $update['dt'] = 1;
            }
            else
            {
                $update['vi'] = $v['vi'];
                $update['ed'] = $v['ed'];
                $update['dl'] = $v['dl'];
                $update['sh'] = $v['sh'];
                $update['up'] = $v['up'];
                $update['dt'] = $v['dt'];
                $update['mgr'] = 0;
            }
            $allPermitUserArr[] = $k;
            $CNOA_DB->db_update( $update, $this->table_public_permit_s, "WHERE `sid` = '".$k."' AND `fid` = '{$fid}' " );
        }
        $CNOA_DB->db_delete( $this->table_public_permit_s, "WHERE `fid` = '".$fid."' AND `sid` NOT IN (".implode( ",", $allPermitUserArr ).") " );
        $path = $CNOA_DB->db_getfield( "path2", $this->table_public, "WHERE `fid`='".$fid."'" );
        $paths = explode( "-", $path );
        $where = "WHERE `fid` IN (";
        foreach ( $paths as $v )
        {
            if ( $v != 0 )
            {
                $where .= $v.",";
            }
        }
        $where = substr_replace( $where, ")", -1 );
        $fileNames = $CNOA_DB->db_select( array( "name" ), $this->table_public, $where );
        $filePath = "";
        $i = 0;
        for ( ; $i < count( $fileNames ); ++$i )
        {
            $filePath .= $fileNames[$i]['name']."/";
        }
        $filePath = substr_replace( $filePath, "", -1 );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 30, lang( "folderOfDeptPermit", $filePath ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _deletePermitM( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $pid = getpar( $_GET, "pid", 0 );
        $fid = getpar( $_POST, "fid", 0 );
        $uid = $CNOA_SESSION->get( "UID" );
        $did = $CNOA_SESSION->get( "DID" );
        $dir = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$fid."'" );
        $dirPermit = $this->__getDirPermit( $dir );
        if ( $dirPermit['mgr'] != "1" )
        {
            msg::callback( FALSE, lang( "noPermitOpt" ) );
        }
        $path = $CNOA_DB->db_getfield( "path", $this->table_public_permit_p, "WHERE `pid`='".$pid."'" );
        $CNOA_DB->db_delete( $this->table_public_permit_p, "WHERE `pid` = '".$pid."' " );
        $paths = explode( "-", $path );
        $where = "WHERE `fid` IN (";
        foreach ( $paths as $v )
        {
            if ( $v != 0 )
            {
                $where .= $v.",";
            }
        }
        $where = substr_replace( $where, ")", -1 );
        $fileNames = $CNOA_DB->db_select( array( "name" ), $this->table_public, $where );
        $filePath = "";
        $i = 0;
        for ( ; $i < count( $fileNames ); ++$i )
        {
            $filePath .= $fileNames[$i]['name']."/";
        }
        $filePath = substr_replace( $filePath, "", -1 );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 30, lang( "folderPermitOfUser", $filePath ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _deletePermitS( )
    {
        global $CNOA_DB;
        $pid = getpar( $_GET, "sid", 0 );
        $fid = getpar( $_POST, "fid", 0 );
        $dir = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$fid."'" );
        $dirPermit = $this->__getDirPermit( $dir );
        if ( $dirPermit['mgr'] != "1" )
        {
            msg::callback( FALSE, lang( "noPermitOpt" ) );
        }
        $path = $CNOA_DB->db_getfield( "path", $this->table_public_permit_s, "WHERE `sid`='".$pid."'" );
        $CNOA_DB->db_delete( $this->table_public_permit_s, "WHERE `sid` = '".$pid."' " );
        $paths = explode( "-", $path );
        $where = "WHERE `fid` IN (";
        foreach ( $paths as $v )
        {
            if ( $v != 0 )
            {
                $where .= $v.",";
            }
        }
        $where = substr_replace( $where, ")", -1 );
        $fileNames = $CNOA_DB->db_select( array( "name" ), $this->table_public, $where );
        $filePath = "";
        $i = 0;
        for ( ; $i < count( $fileNames ); ++$i )
        {
            $filePath .= $fileNames[$i]['name']."/";
        }
        $filePath = substr_replace( $filePath, "", -1 );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 30, lang( "folderOfDeptPermit", $filePath ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _deleteEmptyPermit( )
    {
        global $CNOA_DB;
        $fid = getpar( $_POST, "fid", 0 );
        $CNOA_DB->db_delete( $this->table_public_permit_p, "WHERE `fid` = ".$fid." AND `ed` = 0 AND `vi` = 0 AND `dl` = 0 AND `up` = 0 AND `dt` = 0 " );
        $CNOA_DB->db_delete( $this->table_public_permit_s, "WHERE `fid` = ".$fid." AND `ed` = 0 AND `vi` = 0 AND `dl` = 0 AND `up` = 0 AND `dt` = 0 " );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _doShare( )
    {
        global $CNOA_DB;
        $fid = intval( getpar( $_POST, "fid", 0 ) );
        $data_p = json_decode( stripslashes( $_POST['data_p'] ), TRUE );
        $data_s = json_decode( stripslashes( $_POST['data_s'] ), TRUE );
        if ( !is_array( $data_p ) )
        {
            $data_p = array( );
        }
        if ( !is_array( $data_s ) )
        {
            $data_s = array( );
        }
        if ( $fid == 0 )
        {
            msg::callback( FALSE, lang( "goWrong" ) );
        }
        if ( count( $data_p ) <= 0 && count( $data_s ) <= 0 )
        {
            $CNOA_DB->db_update( array( "people" => "", "dept" => "" ), $this->table_public, "WHERE `fid`='".$fid."' AND `type`='d'" );
            msg::callback( TRUE, lang( "optSucessAllCanView" ) );
        }
        $uids = array( );
        foreach ( $data_p as $pv )
        {
            $uids[] = $pv['uid'];
        }
        $data = array( );
        if ( 0 < count( $data_p ) )
        {
            $data['people'] = addslashes( json_encode( array(
                "uids" => $uids,
                "data" => $data_p
            ) ) );
        }
        else
        {
            $data['people'] = "";
        }
        if ( 0 < count( $data_s ) )
        {
            $data['dept'] = addslashes( json_encode( $data_s ) );
        }
        else
        {
            $data['dept'] = "";
        }
        $CNOA_DB->db_update( $data, $this->table_public, "WHERE `fid`='".$fid."' AND `type`='d'" );
        msg::callback( TRUE, lang( "successopt" ) );
        exit( );
    }

    private function _getShareData( )
    {
        global $CNOA_DB;
        $fid = intval( getpar( $_POST, "fid", 0 ) );
        $data = $CNOA_DB->db_getone( array( "people", "dept" ), $this->table_public, "WHERE `fid`='".$fid."' AND `type`='d'" );
        if ( $data['people'] == NULL )
        {
            $data['people'] = "[]";
        }
        if ( $data['dept'] == NULL )
        {
            $data['dept'] = "[]";
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = array(
            "data_p" => json_decode( $data['people'], TRUE ),
            "data_s" => json_decode( $data['dept'], TRUE )
        );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _doExtend( )
    {
        global $CNOA_DB;
        $fid = intval( getpar( $_POST, "fid", 0 ) );
        $extend = intval( getpar( $_POST, "extend", 1 ) );
        $copy = intval( getpar( $_POST, "copy", 0 ) );
        $info = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$fid."'" );
        if ( !$info )
        {
            msg::callback( FALSE, lang( "nodata" ) );
        }
        if ( $extend == 1 )
        {
            $CNOA_DB->db_delete( $this->table_public_permit_p, "WHERE `fid`='".$fid."'" );
            $CNOA_DB->db_delete( $this->table_public_permit_s, "WHERE `fid`='".$fid."'" );
        }
        else if ( $copy == 1 && $info['extend'] == 1 )
        {
            $parents = explode( "-", $info['path'] );
            unset( $parents[0] );
            if ( 0 < count( $parents ) )
            {
                $pFolders = $CNOA_DB->db_select( array( "fid", "extend" ), $this->table_public, "WHERE `fid` IN (".implode( ",", $parents ).") ORDER BY `fid` DESC" );
                if ( !is_array( $pFolders ) )
                {
                    $pFolders = array( );
                }
                $fids = array( 0 );
                foreach ( $pFolders as $v )
                {
                    if ( !( $v['extend'] == "1" ) )
                    {
                        break;
                    }
                    $fids[] = $v['fid'];
                }
                $parentsPermits = $CNOA_DB->db_select( "*", $this->table_public_permit_p, "WHERE `fid` IN (".implode( ",", $fids ).") " );
                if ( !is_array( $parentsPermits ) )
                {
                    $parentsPermits = array( );
                }
                $data2 = array( );
                foreach ( $parentsPermits as $v )
                {
                    if ( empty( $data2[$v['uid']] ) )
                    {
                        $data2[$v['uid']] = $v;
                    }
                    else
                    {
                        foreach ( array( "dl", "sh", "up", "dt", "ed", "vi", "mgr" ) as $dpv )
                        {
                            if ( $v[$dpv] == 1 )
                            {
                                $data2[$v['uid']][$dpv] = 1;
                            }
                        }
                    }
                }
                foreach ( $data2 as $v )
                {
                    unset( $v['pid'] );
                    $v['path'] = $info['path2'];
                    $v['fid'] = $info['fid'];
                    $CNOA_DB->db_insert( $v, $this->table_public_permit_p );
                }
                $parentsPermits = $CNOA_DB->db_select( "*", $this->table_public_permit_s, "WHERE `fid` IN (".implode( ",", $fids ).") " );
                if ( !is_array( $parentsPermits ) )
                {
                    $parentsPermits = array( );
                }
                $data2 = array( );
                foreach ( $parentsPermits as $v )
                {
                    if ( empty( $data2[$v['did']] ) )
                    {
                        $data2[$v['did']] = $v;
                    }
                    else
                    {
                        foreach ( array( "dl", "sh", "up", "dt", "ed", "vi", "mgr" ) as $dpv )
                        {
                            if ( $v[$dpv] == 1 )
                            {
                                $data2[$v['did']][$dpv] = 1;
                            }
                        }
                    }
                }
                foreach ( $data2 as $v )
                {
                    unset( $v['sid'] );
                    $v['path'] = $info['path2'];
                    $v['fid'] = $info['fid'];
                    $CNOA_DB->db_insert( $v, $this->table_public_permit_s );
                }
                foreach ( $data2 as $v )
                {
                    $uidArr[] = $v['uid'];
                    $v['extend'] = 1;
                    $data[] = $v;
                }
            }
        }
        $CNOA_DB->db_update( array(
            "extend" => $extend
        ), $this->table_public, "WHERE `fid`='".$fid."'" );
        msg::callback( TRUE, lang( "successopt" ) );
        exit( );
    }

    private function _getThumb( )
    {
        global $CNOA_DB;
        $fileid = intval( getpar( $_GET, "fileid" ) );
        $target = getpar( $_GET, "target" );
        $thumb = $CNOA_DB->db_getone( "*", "user_disk_public_file", "WHERE `fileid`='".$fileid."'" );
        if ( !$thumb )
        {
            return;
        }
        if ( empty( $thumb['thumb'] ) )
        {
            $filePath = CNOA_PATH_FILE.( "/common/disk/public/".$thumb['storepath']."/" );
            $thumbName = string::rands( 16 );
            ( );
            $picture = new picture( );
            $picture->setSrcImg( $filePath.$thumb['storename'] );
            $picture->setDstImg( $filePath.$thumbName.( ".".$thumb['ext'] ) );
            $picture->createImg( 90, 90 );
            ( );
            $picture = new picture( );
            $picture->setSrcImg( $filePath.$thumb['storename'] );
            $picture->setDstImg( $filePath.$thumbName.( ".mid.".$thumb['ext'] ) );
            $picture->createImg( 300, 300 );
            if ( file_exists( $filePath.$thumbName.( ".".$thumb['ext'] ) ) )
            {
                $CNOA_DB->db_update( array(
                    "thumb" => $thumbName
                ), "user_disk_public_file", "WHERE `fileid`='".$fileid."'" );
            }
            if ( $target == "big" )
            {
                $thumbName = $thumb['storename'];
            }
            else if ( $target == "middle" )
            {
                $thumbName = $thumb['thumb'].( ".mid.".$thumb['ext'] );
            }
            else
            {
                $thumbName = $thumb['storename'].( ".".$thumb['ext'] );
            }
            header( "content-type: image/jpeg" );
            header( "Location:".$GLOBALS['URL_FILE'].( "/common/disk/public/".$thumb['storepath']."/" ).$thumbName );
        }
        else
        {
            if ( $target == "big" )
            {
                $thumbName = $thumb['storename'];
            }
            else if ( $target == "middle" )
            {
                $thumbName = $thumb['thumb'].( ".mid.".$thumb['ext'] );
            }
            else
            {
                $thumbName = $thumb['thumb'].( ".".$thumb['ext'] );
            }
            header( "content-type: image/jpeg" );
            header( "Location:".$GLOBALS['URL_FILE'].( "/common/disk/public/".$thumb['storepath']."/" ).$thumbName );
        }
    }

    private function _changeViewMod( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $viewMod = getpar( $_POST, "viewMod", "list" );
        if ( !in_array( $viewMod, array( "thumb", "list" ) ) )
        {
            return;
        }
        $sql = "INSERT INTO ";
        $sql .= tname( $this->table_user )." ";
        $sql .= "(`uid`, `maxsize`, `used`, `viewMod`) ";
        $sql .= "values ";
        $sql .= "('".$uid."', '0', '0', '{$viewMod}"."') ";
        $sql .= "ON DUPLICATE KEY UPDATE `viewMod`='".$viewMod."'";
        $CNOA_DB->query( $sql );
    }

    private function api_saveFormatDir( $from )
    {
        $format = array(
            "report" => array( )
        );
        return $dir;
    }

    public function api_saveToDisk( $fname, $from )
    {
        global $CNOA_DB;
    }

    public function _sharedpeople( )
    {
        global $CNOA_DB;
        $fileid = getpar( $_POST, "fileid", "" );
        $sharedpeople = $CNOA_DB->db_getfield( "sharedpeopleUid", $this->table_public_file, "WHERE `fileid`='".$fileid."'" );
        $sharedpeople = explode( ",", $sharedpeople );
        $truename = app::loadapp( "main", "user" )->api_getUserNamesByUids( $sharedpeople );
        $temp = $data = array( );
        foreach ( $truename as $k => $v )
        {
            $truename[$k]['sharedpeopleUid'] = $truename[$v['uid']]['truename'];
            $data[] = $truename[$k]['sharedpeopleUid'];
        }
        $temp = implode( ",", $data );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $temp;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _import( )
    {
        $this->importFromDisk( );
    }

    private function _moveToDir( )
    {
        global $CNOA_DB;
        $fileid = getpar( $_POST, "fileid" );
        $pid = getpar( $_POST, "pid" );
        if ( !empty( $fileid ) || !empty( $pid ) )
        {
            $getPath = $CNOA_DB->db_getfield( "path2", $this->table_public, "WHERE `fid`=".$pid );
            $CNOA_DB->db_update( array(
                "fid" => $pid,
                "path" => $getPath
            ), $this->table_public_file, "WHERE `fileid`=".$fileid );
            msg::callback( TRUE, lang( "successopt" ) );
        }
        msg::callback( FALSE, lang( "optFail" ) );
        exit( );
    }

}

?>
