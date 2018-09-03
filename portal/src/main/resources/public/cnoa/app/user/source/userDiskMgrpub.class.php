<?php
//decode by qq2859470

class userDiskMgrpub extends userDiskPubCommon
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
        $_obfuscate_eKY1Cu_NLgÿÿ = getpar( $_GET, "special", getpar( $_POST, "special" ) );
        if ( $_obfuscate_eKY1Cu_NLgÿÿ == "yes" )
        {
            $this->_saveasdiskandfs( );
            exit( );
        }
        $_obfuscate_M_5JJwÿÿ = getpar( $_GET, "task", getpar( $_POST, "task" ) );
        switch ( $_obfuscate_M_5JJwÿÿ )
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
            $_obfuscate_vholQÿÿ = getpar( $_GET, "from", "" );
            if ( $_obfuscate_vholQÿÿ == "folder" )
            {
                $this->_uploadFolder( );
            }
            else if ( $_obfuscate_vholQÿÿ == "finishFolder" )
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
        case "deleteVersions" :
            $this->_deleteVersions( );
            break;
        case "updataVersions" :
            $this->_updataVersions( );
            break;
        case "getLogList" :
            $this->_getLogList( );
            break;
        case "saveasdisk" :
            $this->_saveasdisk( );
            break;
        case "selector" :
            $_obfuscate_Ns_JyWSm = getpar( $_GET, "target", getpar( $_POST, "target", "" ) );
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            switch ( $_obfuscate_Ns_JyWSm )
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
        case "move" :
            $this->_move( );
            break;
        case "exportCatalog" :
            $this->_exportCatalog( );
        }
    }

    private function __getDirPermit( $_obfuscate_Fwl1 )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_iuzS = $CNOA_SESSION->get( "DID" );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_eVTMIa1A = array( "dl" => 0, "sh" => 0, "mv" => 0, "pr" => 0, "up" => 0, "dt" => 0, "ed" => 0, "vi" => 0, "mgr" => 0 );
        if ( $_obfuscate_Fwl1['uid'] == $_obfuscate_7Ri3 || $this->isAdmin )
        {
            $_obfuscate_eVTMIa1A = array( "dl" => 1, "mv" => 1, "pr" => 1, "sh" => 1, "up" => 1, "dt" => 1, "ed" => 1, "vi" => 1, "mgr" => 1 );
            return $_obfuscate_eVTMIa1A;
        }
        $_obfuscate_tPMÿ = $CNOA_DB->db_select( array( "dl", "mv", "pr", "sh", "up", "dt", "ed", "vi", "mgr" ), $this->table_public_permit_p, "WHERE `uid`='".$_obfuscate_7Ri3."' AND `fid`='{$_obfuscate_Fwl1['fid']}'" );
        $_obfuscate_8Rgÿ = $CNOA_DB->db_select( array( "dl", "mv", "pr", "sh", "up", "dt", "ed", "vi", "mgr" ), $this->table_public_permit_s, "WHERE `did`='".$_obfuscate_iuzS."' AND `fid`='{$_obfuscate_Fwl1['fid']}'" );
        if ( !is_array( $_obfuscate_tPMÿ ) )
        {
            $_obfuscate_tPMÿ = array( );
        }
        if ( !is_array( $_obfuscate_8Rgÿ ) )
        {
            $_obfuscate_8Rgÿ = array( );
        }
        $_obfuscate_gg8f = array_merge( $_obfuscate_tPMÿ, $_obfuscate_8Rgÿ );
        unset( $_obfuscate_tPMÿ );
        unset( $_obfuscate_8Rgÿ );
        foreach ( $_obfuscate_gg8f as $_obfuscate_6Aÿÿ )
        {
            foreach ( array( "dl", "sh", "mv", "pr", "up", "dt", "ed", "vi", "mgr" ) as $_obfuscate_I_Xm )
            {
                if ( $_obfuscate_6Aÿÿ[$_obfuscate_I_Xm] == 1 )
                {
                    $_obfuscate_eVTMIa1A[$_obfuscate_I_Xm] = 1;
                }
            }
        }
        if ( $_obfuscate_Fwl1['extend'] == 0 )
        {
            return $_obfuscate_eVTMIa1A;
        }
        $_obfuscate_0FpOvgÿÿ = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$_obfuscate_Fwl1['pid']."'" );
        $_obfuscate_nVaq6PxzyAÿÿ = $this->__getDirPermit( $_obfuscate_0FpOvgÿÿ );
        foreach ( array( "dl", "sh", "mv", "pr", "up", "dt", "ed", "vi", "mgr" ) as $_obfuscate_I_Xm )
        {
            if ( $_obfuscate_nVaq6PxzyAÿÿ[$_obfuscate_I_Xm] == 1 )
            {
                $_obfuscate_eVTMIa1A[$_obfuscate_I_Xm] = 1;
            }
        }
        return $_obfuscate_eVTMIa1A;
    }

    private function __getChildDirs( $_obfuscate_fdpE )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_zqY_4WCe = $CNOA_DB->db_select( array( "name", "path", "path2", "fid", "pid", "extend", "uid", "posttime" ), $this->table_public, "WHERE `pid`='".$_obfuscate_fdpE."' ORDER BY `fid` ASC " );
        if ( !is_array( $_obfuscate_zqY_4WCe ) )
        {
            $_obfuscate_zqY_4WCe = array( );
        }
        $_obfuscate_V2p4zwÿÿ = array( );
        foreach ( $_obfuscate_zqY_4WCe as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_ljWwbP9jSVP1 = $this->__getDirPermit( $_obfuscate_6Aÿÿ );
            $_obfuscate_vViIY6y6dQÿÿ = FALSE;
            foreach ( array( "dl", "sh", "mv", "pr", "up", "dt", "ed", "vi", "mgr" ) as $_obfuscate_I_Xm )
            {
                if ( $_obfuscate_ljWwbP9jSVP1[$_obfuscate_I_Xm] == 1 )
                {
                    $_obfuscate_vViIY6y6dQÿÿ = TRUE;
                }
            }
            if ( $_obfuscate_vViIY6y6dQÿÿ )
            {
                $_obfuscate_V2p4zwÿÿ[] = $_obfuscate_6Aÿÿ;
            }
        }
        return $_obfuscate_V2p4zwÿÿ;
    }

    private function _getDir( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_iuzS = $CNOA_SESSION->get( "DID" );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_fdpE = getpar( $_POST, "pid", 0 );
        $_obfuscate_LeS8hwÿÿ = getpar( $_GET, "type" );
        $_obfuscate_V2p4zwÿÿ = array( );
        if ( !$this->isAdmin )
        {
            $_obfuscate_qTCgW3xH = $this->__getChildDirs( $_obfuscate_fdpE );
        }
        else
        {
            $_obfuscate_qTCgW3xH = $CNOA_DB->db_select( array( "name", "path", "path2", "fid", "pid" ), $this->table_public, "WHERE `pid`='".$_obfuscate_fdpE."' ORDER BY `fid` ASC " );
            if ( !is_array( $_obfuscate_qTCgW3xH ) )
            {
                $_obfuscate_qTCgW3xH = array( );
            }
        }
        if ( $_obfuscate_LeS8hwÿÿ == "tree" )
        {
            foreach ( $_obfuscate_qTCgW3xH as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_sx8ÿ = array( );
                $_obfuscate_sx8ÿ['id'] = $_obfuscate_6Aÿÿ['fid'];
                $_obfuscate_sx8ÿ['fid'] = $_obfuscate_6Aÿÿ['fid'];
                $_obfuscate_sx8ÿ['text'] = $_obfuscate_6Aÿÿ['name'];
                $_obfuscate_sx8ÿ['cls'] = "folder";
                $_obfuscate_sx8ÿ['pid'] = $_obfuscate_6Aÿÿ['pid'];
                $_obfuscate_sx8ÿ['path'] = $_obfuscate_6Aÿÿ['path'];
                $_obfuscate_V2p4zwÿÿ[] = $_obfuscate_sx8ÿ;
            }
            echo json_encode( $_obfuscate_V2p4zwÿÿ );
            exit( );
        }
        if ( $_obfuscate_LeS8hwÿÿ == "combo" )
        {
            foreach ( $_obfuscate_qTCgW3xH as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_sx8ÿ = array( );
                $_obfuscate_sx8ÿ['id'] = $_obfuscate_6Aÿÿ['fid'];
                $_obfuscate_sx8ÿ['fid'] = $_obfuscate_6Aÿÿ['fid'];
                $_obfuscate_sx8ÿ['text'] = $_obfuscate_6Aÿÿ['name'];
                $_obfuscate_sx8ÿ['cls'] = "folder";
                $_obfuscate_sx8ÿ['pid'] = $_obfuscate_6Aÿÿ['pid'];
                $_obfuscate_sx8ÿ['path'] = $_obfuscate_6Aÿÿ['path'];
                $_obfuscate_sx8ÿ['checked'] = FALSE;
                $_obfuscate_V2p4zwÿÿ[] = $_obfuscate_sx8ÿ;
            }
            echo json_encode( $_obfuscate_V2p4zwÿÿ );
            exit( );
        }
    }

    private function _getSearchList( $_obfuscate_fdpE, $_obfuscate_3gn_eQÿÿ, $_obfuscate_7Ri3, $_obfuscate_iuzS )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_V2p4zwÿÿ = array( );
        $_obfuscate_ViKf3gÿÿ = array( 0 );
        $_obfuscate_c20_E9piTM8ÿ = array( );
        $_obfuscate_4aU_dKxPLjyhpwÿÿ = array( );
        $_obfuscate_Fwl1 = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$_obfuscate_fdpE."'" );
        if ( !$_obfuscate_Fwl1 )
        {
            $_obfuscate_uFOIOgQÿ = "0";
        }
        else
        {
            $_obfuscate_uFOIOgQÿ = $_obfuscate_Fwl1['path2'];
        }
        $_obfuscate_uiuSJmSjvcB = $CNOA_DB->db_select( "*", $this->table_public, "WHERE `path2` LIKE '".$_obfuscate_uFOIOgQÿ."-%' OR `path2`='{$_obfuscate_uFOIOgQÿ}'" );
        if ( !is_array( $_obfuscate_uiuSJmSjvcB ) )
        {
            $_obfuscate_uiuSJmSjvcB = array( );
        }
        foreach ( $_obfuscate_uiuSJmSjvcB as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_4aU_dKxPLjyhpwÿÿ[$_obfuscate_6Aÿÿ['fid']] = $this->__getDirPermit( $_obfuscate_6Aÿÿ );
            $_obfuscate_vViIY6y6dQÿÿ = FALSE;
            $_obfuscate_c20_E9piTM8ÿ[$_obfuscate_6Aÿÿ['fid']] = $_obfuscate_6Aÿÿ;
            foreach ( array( "dl", "sh", "mv", "pr", "up", "dt", "ed", "vi", "mgr" ) as $_obfuscate_I_Xm )
            {
                if ( $_obfuscate_4aU_dKxPLjyhpwÿÿ[$_obfuscate_6Aÿÿ['fid']][$_obfuscate_I_Xm] == 1 )
                {
                    $_obfuscate_vViIY6y6dQÿÿ = TRUE;
                }
            }
            if ( $_obfuscate_vViIY6y6dQÿÿ )
            {
                if ( preg_match( "/".$_obfuscate_3gn_eQÿÿ."/is", $_obfuscate_6Aÿÿ['name'] ) == 1 )
                {
                    $_obfuscate_V2p4zwÿÿ[] = $_obfuscate_6Aÿÿ;
                }
                $_obfuscate_ViKf3gÿÿ[] = $_obfuscate_6Aÿÿ['fid'];
            }
        }
        $_obfuscate_I9APXAkÿ = $CNOA_DB->db_select( "*", $this->table_public_file, "WHERE `name` LIKE '%".$_obfuscate_3gn_eQÿÿ."%' AND `fid` IN (".implode( ",", $_obfuscate_ViKf3gÿÿ ).")" );
        if ( !is_array( $_obfuscate_I9APXAkÿ ) )
        {
            $_obfuscate_I9APXAkÿ = array( );
        }
        foreach ( $_obfuscate_V2p4zwÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_PVLK5jra[] = $_obfuscate_6Aÿÿ['uid'];
            $_obfuscate_V2p4zwÿÿ[$_obfuscate_5wÿÿ]['id'] = $_obfuscate_6Aÿÿ['fid'];
            $_obfuscate_V2p4zwÿÿ[$_obfuscate_5wÿÿ]['posttime'] = date( "Y-m-d H:i", $_obfuscate_6Aÿÿ['posttime'] );
            $_obfuscate_V2p4zwÿÿ[$_obfuscate_5wÿÿ]['type'] = "d";
        }
        foreach ( $_obfuscate_I9APXAkÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_PVLK5jra[] = $_obfuscate_6Aÿÿ['uid'];
            $_obfuscate_I9APXAkÿ[$_obfuscate_5wÿÿ]['id'] = $_obfuscate_6Aÿÿ['fileid'];
            $_obfuscate_I9APXAkÿ[$_obfuscate_5wÿÿ]['posttime'] = date( "Y-m-d H:i", $_obfuscate_6Aÿÿ['posttime'] );
            $_obfuscate_I9APXAkÿ[$_obfuscate_5wÿÿ]['name'] = "<span class='cnoa_color_red'>[".$_obfuscate_c20_E9piTM8ÿ[$_obfuscate_6Aÿÿ['fid']]['name']."]</span>".$_obfuscate_6Aÿÿ['name'];
            $_obfuscate_I9APXAkÿ[$_obfuscate_5wÿÿ]['downpath'] = "";
            $_obfuscate_ljWwbP9jSVP1 = $_obfuscate_4aU_dKxPLjyhpwÿÿ[$_obfuscate_6Aÿÿ['fid']];
            if ( $_obfuscate_ljWwbP9jSVP1['dl'] || $_obfuscate_ljWwbP9jSVP1['mgr'] == 1 )
            {
                $_obfuscate_I9APXAkÿ[$_obfuscate_5wÿÿ]['downpath'] .= makedownloadicon( "{$GLOBALS['URL_FILE']}/common/disk/public/{$_obfuscate_6Aÿÿ['storepath']}/{$_obfuscate_6Aÿÿ['storename']}", $_obfuscate_6Aÿÿ['name'].".".$_obfuscate_6Aÿÿ['ext'], "html" );
                $_obfuscate_I9APXAkÿ[$_obfuscate_5wÿÿ]['downhref'] = makedownloadicon( "{$GLOBALS['URL_FILE']}/common/disk/public/{$_obfuscate_6Aÿÿ['storepath']}/{$_obfuscate_6Aÿÿ['storename']}", $_obfuscate_6Aÿÿ['name'].".".$_obfuscate_6Aÿÿ['ext'], "href" );
                $_obfuscate_I9APXAkÿ[$_obfuscate_5wÿÿ]['dl'] = TRUE;
            }
            if ( $_obfuscate_ljWwbP9jSVP1['sh'] || $_obfuscate_ljWwbP9jSVP1['mgr'] == 1 )
            {
                $_obfuscate_I9APXAkÿ[$_obfuscate_5wÿÿ]['sh'] = TRUE;
            }
            if ( $_obfuscate_ljWwbP9jSVP1['ed'] || $_obfuscate_ljWwbP9jSVP1['mgr'] == 1 )
            {
                $_obfuscate_I9APXAkÿ[$_obfuscate_5wÿÿ]['downpath'] .= "&nbsp;".makediskediticon( "{$GLOBALS['URL_FILE']}/common/disk/public/{$_obfuscate_6Aÿÿ['storepath']}/{$_obfuscate_6Aÿÿ['storename']}", ".".$_obfuscate_6Aÿÿ['ext'], TRUE, "disk_pub", $_obfuscate_6Aÿÿ['fileid'], $_obfuscate_6Aÿÿ['storename'], $_obfuscate_6Aÿÿ['name'], "pubdisk" );
                $_obfuscate_I9APXAkÿ[$_obfuscate_5wÿÿ]['edithref'] = makediskediticon( "{$GLOBALS['URL_FILE']}/common/disk/public/{$_obfuscate_6Aÿÿ['storepath']}/{$_obfuscate_6Aÿÿ['storename']}", ".".$_obfuscate_6Aÿÿ['ext'], TRUE, "disk_pub", $_obfuscate_6Aÿÿ['fileid'], $_obfuscate_6Aÿÿ['storename'], $_obfuscate_6Aÿÿ['name'], "pubdisk", "href" );
                $_obfuscate_I9APXAkÿ[$_obfuscate_5wÿÿ]['ed'] = TRUE;
            }
            if ( $_obfuscate_ljWwbP9jSVP1['vi'] || $_obfuscate_ljWwbP9jSVP1['mgr'] == 1 )
            {
                $_obfuscate_I9APXAkÿ[$_obfuscate_5wÿÿ]['downpath'] .= "&nbsp;".makeattachpreviewicon( $_obfuscate_6Aÿÿ['name'].".".$_obfuscate_6Aÿÿ['ext'], "{$GLOBALS['URL_FILE']}/common/disk/public/{$_obfuscate_6Aÿÿ['storepath']}/{$_obfuscate_6Aÿÿ['storename']}", ".".$_obfuscate_6Aÿÿ['ext'], $_obfuscate_6Aÿÿ['fileid'] );
                $_obfuscate_I9APXAkÿ[$_obfuscate_5wÿÿ]['viewhref'] = makeattachpreviewicon( $_obfuscate_6Aÿÿ['name'].".".$_obfuscate_6Aÿÿ['ext'], "{$GLOBALS['URL_FILE']}/common/disk/public/{$_obfuscate_6Aÿÿ['storepath']}/{$_obfuscate_6Aÿÿ['storename']}", ".".$_obfuscate_6Aÿÿ['ext'], $_obfuscate_6Aÿÿ['fileid'], "href" );
                $_obfuscate_I9APXAkÿ[$_obfuscate_5wÿÿ]['vi'] = TRUE;
            }
            $_obfuscate_I9APXAkÿ[$_obfuscate_5wÿÿ]['size'] = sizeformat( $_obfuscate_6Aÿÿ['size'] );
            $_obfuscate_I9APXAkÿ[$_obfuscate_5wÿÿ]['type'] = "f";
            $_obfuscate_I9APXAkÿ[$_obfuscate_5wÿÿ]['ext'] = $_obfuscate_6Aÿÿ['ext'];
            $_obfuscate_V2p4zwÿÿ[] = $_obfuscate_I9APXAkÿ[$_obfuscate_5wÿÿ];
        }
        $_obfuscate__Wi6396IheAÿ = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_PVLK5jra );
        foreach ( $_obfuscate_V2p4zwÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_V2p4zwÿÿ[$_obfuscate_5wÿÿ]['postname'] = $_obfuscate__Wi6396IheAÿ[$_obfuscate_6Aÿÿ['uid']]['truename'];
        }
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_V2p4zwÿÿ;
        $_obfuscate_SUjPN94Er7yI->nowpath = "/".str_replace( "-", "/", $_obfuscate_Fwl1['path2'] );
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
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
        $start = getpar( $_POST, "start", 0 );
        $row = 50;
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
            $files = $CNOA_DB->db_select( "*", $this->table_public_file, "WHERE `fid` = '".$pid."' ORDER BY `name` ASC LIMIT {$start}, {$row}" );
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
                $files[$k]['downpath'] = "";
                if ( $dirPermit['dl'] || $dirPermit['mgr'] == 1 )
                {
                    $files[$k]['name'] = str_replace( "&amp;", "&", $v['name'] );
                    $files[$k]['downpath'] .= makedownloadicon( "{$GLOBALS['URL_FILE']}/common/disk/public/{$v['storepath']}/{$v['storename']}", $files[$k]['name'].".".$v['ext'], "html" );
                    $files[$k]['downhref'] = makedownloadicon( "{$GLOBALS['URL_FILE']}/common/disk/public/{$v['storepath']}/{$v['storename']}", $files[$k]['name'].".".$v['ext'], "href" );
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
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _exportCatalog( )
    {
        global $CNOA_DB;
        $_obfuscate_Ce9h = intval( getpar( $_GET, "fid", 0 ) );
        ob_start( );
        if ( $_obfuscate_Ce9h == 0 )
        {
            $_obfuscate_3gn_eQÿÿ = "å¬å±ç¡¬ç";
        }
        else
        {
            $_obfuscate_3gn_eQÿÿ = $CNOA_DB->db_getfield( "name", $this->table_public, "WHERE `fid`='".$_obfuscate_Ce9h."'" );
        }
        echo $_obfuscate_3gn_eQÿÿ."/\n";
        $this->echoCatalog( $_obfuscate_Ce9h );
        $m = ob_get_contents( );
        ob_end_clean( );
        $_obfuscate_JTe7jJ4eGW8ÿ = CNOA_PATH_FILE."/temp/".time( ).".doc";
        file_put_contents( $_obfuscate_JTe7jJ4eGW8ÿ, $m );
        header( "Content-Type:application/octet-stream" );
        header( "Content-Disposition: attachment; filename=\"".time( ).".doc\"" );
        header( "Content-Length:".filesize( $_obfuscate_JTe7jJ4eGW8ÿ ) );
        ob_clean( );
        flush( );
        readfile( $_obfuscate_JTe7jJ4eGW8ÿ );
        @unlink( $_obfuscate_JTe7jJ4eGW8ÿ );
        exit( );
    }

    private function echoCatalog( $_obfuscate_Ce9h, $_obfuscate_R2_b = "" )
    {
        global $CNOA_DB;
        $_obfuscate_TtI95_W5tAÿÿ = array( "âââ ", "â   ", "âââ ", "    " );
        $_obfuscate_V2p4zwÿÿ = $this->__getChildDirs( $_obfuscate_Ce9h );
        $_obfuscate_Fwl1 = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$_obfuscate_Ce9h."'" );
        $_obfuscate_ljWwbP9jSVP1 = $this->__getDirPermit( $_obfuscate_Fwl1 );
        if ( $_obfuscate_ljWwbP9jSVP1['dl'] == 1 || $_obfuscate_ljWwbP9jSVP1['vi'] == 1 || $_obfuscate_ljWwbP9jSVP1['ed'] == 1 || $_obfuscate_ljWwbP9jSVP1['sh'] == 1 || $_obfuscate_ljWwbP9jSVP1['dt'] == 1 || $_obfuscate_ljWwbP9jSVP1['mgr'] == 1 )
        {
            $_obfuscate_I9APXAkÿ = $CNOA_DB->db_select( array( "name", "ext" ), $this->table_public_file, "WHERE `fid` = '".$_obfuscate_Ce9h."'" );
        }
        if ( !empty( $_obfuscate_V2p4zwÿÿ ) )
        {
            $_obfuscate_KQÿÿ = count( $_obfuscate_V2p4zwÿÿ );
            foreach ( $_obfuscate_V2p4zwÿÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
            {
                if ( $_obfuscate_Vwty == $_obfuscate_KQÿÿ - 1 && empty( $_obfuscate_I9APXAkÿ ) )
                {
                    $A = $_obfuscate_TtI95_W5tAÿÿ[2];
                }
                else
                {
                    $A = $_obfuscate_TtI95_W5tAÿÿ[0];
                }
                echo $_obfuscate_R2_b.$A.$_obfuscate_VgKtFegÿ['name']."/\n";
                if ( $_obfuscate_Vwty == $_obfuscate_KQÿÿ - 1 && empty( $_obfuscate_I9APXAkÿ ) )
                {
                    $this->echoCatalog( $_obfuscate_VgKtFegÿ['fid'], $_obfuscate_R2_b.$_obfuscate_TtI95_W5tAÿÿ[3] );
                }
                else
                {
                    $this->echoCatalog( $_obfuscate_VgKtFegÿ['fid'], $_obfuscate_R2_b.$_obfuscate_TtI95_W5tAÿÿ[1] );
                }
            }
        }
        if ( !empty( $_obfuscate_I9APXAkÿ ) )
        {
            $_obfuscate_KQÿÿ = count( $_obfuscate_I9APXAkÿ );
            foreach ( $_obfuscate_I9APXAkÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
            {
                if ( $_obfuscate_Vwty == $_obfuscate_KQÿÿ - 1 )
                {
                    $A = $_obfuscate_TtI95_W5tAÿÿ[2];
                }
                else
                {
                    $A = $_obfuscate_TtI95_W5tAÿÿ[0];
                }
                echo $_obfuscate_R2_b.$A.$_obfuscate_VgKtFegÿ['name'].".".$_obfuscate_VgKtFegÿ['ext']."\n";
            }
        }
    }

    private function _rename( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_iuzS = $CNOA_SESSION->get( "DID" );
        $_obfuscate_3gn_eQÿÿ = getpar( $_POST, "name", "" );
        $_obfuscate_Ce9h = intval( getpar( $_POST, "fid", 0 ) );
        $_obfuscate_fdpE = intval( getpar( $_POST, "pid", 0 ) );
        $_obfuscate_LeS8hwÿÿ = getpar( $_POST, "type", "" );
        $_obfuscate_X887ZQcÿ = getpar( $_POST, "ftype", "d" );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        if ( $_obfuscate_X887ZQcÿ == "f" )
        {
            $_obfuscate_6hS1Rwÿÿ = $CNOA_DB->db_getone( "*", $this->table_public_file, "WHERE `fileid` = '".$_obfuscate_Ce9h."' " );
            $_obfuscate_Ce9h = $_obfuscate_6hS1Rwÿÿ['fid'];
        }
        if ( $_obfuscate_LeS8hwÿÿ == "add" )
        {
            $_obfuscate_Ce9h = $_obfuscate_fdpE;
        }
        $_obfuscate_Fwl1 = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$_obfuscate_Ce9h."'" );
        $_obfuscate_ljWwbP9jSVP1 = $this->__getDirPermit( $_obfuscate_Fwl1 );
        if ( $_obfuscate_ljWwbP9jSVP1['mgr'] != "1" )
        {
            msg::callback( FALSE, lang( "noPermitOpt" ) );
        }
        if ( empty( $_obfuscate_3gn_eQÿÿ ) )
        {
            msg::callback( FALSE, lang( "fileFoldNameNotEmpty" ) );
        }
        if ( $_obfuscate_LeS8hwÿÿ == "add" )
        {
            $_obfuscate_6RYLWQÿÿ = array( );
            $_obfuscate_6RYLWQÿÿ['name'] = $_obfuscate_3gn_eQÿÿ;
            $_obfuscate_6RYLWQÿÿ['uid'] = $_obfuscate_7Ri3;
            $_obfuscate_6RYLWQÿÿ['pid'] = $_obfuscate_fdpE;
            $_obfuscate_6RYLWQÿÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->table_public, "WHERE `pid` = '".$_obfuscate_fdpE."' AND `name` = '{$_obfuscate_3gn_eQÿÿ}' " );
            if ( !empty( $_obfuscate_Ybai ) )
            {
                msg::callback( FALSE, lang( "folderExist" ) );
            }
            if ( $_obfuscate_fdpE == 0 )
            {
                if ( !$this->isAdmin )
                {
                    msg::callback( FALSE, lang( "notAdminToAddFolderInRoot" ) );
                }
                $_obfuscate_6RYLWQÿÿ['path'] = 0;
            }
            else
            {
                $_obfuscate_pp9pYwÿÿ = $CNOA_DB->db_getfield( "path", $this->table_public, "WHERE `fid` = '".$_obfuscate_fdpE."' " );
                $_obfuscate_6RYLWQÿÿ['path'] = $_obfuscate_pp9pYwÿÿ."-".$_obfuscate_fdpE;
            }
            $_obfuscate_dmhV4bRK = $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->table_public );
            insertdisklog( 6, 1, $_obfuscate_6RYLWQÿÿ['path']."-".$_obfuscate_dmhV4bRK, $_obfuscate_dmhV4bRK, $_obfuscate_3gn_eQÿÿ );
            $CNOA_DB->db_update( array(
                "path2" => $_obfuscate_6RYLWQÿÿ['path']."-".$_obfuscate_dmhV4bRK
            ), $this->table_public, "WHERE `fid` = '".$_obfuscate_dmhV4bRK."' " );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 30, $_obfuscate_6RYLWQÿÿ['name'], lang( "folder" ) );
        }
        else if ( $_obfuscate_X887ZQcÿ == "d" )
        {
            $_obfuscate_6RYLWQÿÿ = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$_obfuscate_Ce9h."'" );
            $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->table_public, "WHERE `pid` = '".$_obfuscate_6RYLWQÿÿ['pid']."' AND `name` = '{$_obfuscate_3gn_eQÿÿ}'" );
            if ( !empty( $_obfuscate_Ybai ) )
            {
                msg::callback( FALSE, lang( "folderExist" ) );
            }
            if ( $_obfuscate_6RYLWQÿÿ !== FALSE )
            {
                $_obfuscate_FitaZ8Mÿ = "[".$_obfuscate_6RYLWQÿÿ['name']."]->[".$_obfuscate_3gn_eQÿÿ."]";
                insertdisklog( 7, 1, $_obfuscate_6RYLWQÿÿ['path2'], $_obfuscate_Ce9h, $_obfuscate_FitaZ8Mÿ );
                $CNOA_DB->db_update( array(
                    "name" => $_obfuscate_3gn_eQÿÿ
                ), $this->table_public, "WHERE `fid`='".$_obfuscate_Ce9h."'" );
                app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 30, array(
                    "{$_obfuscate_6RYLWQÿÿ['name']}" => "{$_obfuscate_3gn_eQÿÿ}"
                ), lang( "folderName" ) );
            }
            $_obfuscate_dmhV4bRK = $_obfuscate_Ce9h;
        }
        else if ( $_obfuscate_X887ZQcÿ == "f" )
        {
            $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->table_public_file, "WHERE `fid` = '".$_obfuscate_6hS1Rwÿÿ['fid']."' AND `name` = '{$_obfuscate_3gn_eQÿÿ}' AND `ext` = '{$_obfuscate_6hS1Rwÿÿ['ext']}' " );
            if ( !empty( $_obfuscate_Ybai ) )
            {
                msg::callback( FALSE, lang( "fileExist" ) );
            }
            if ( $_obfuscate_6RYLWQÿÿ !== FALSE )
            {
                $_obfuscate_FitaZ8Mÿ = "[".$_obfuscate_6hS1Rwÿÿ['name']."]->[".$_obfuscate_3gn_eQÿÿ."]";
                insertdisklog( 7, 0, $_obfuscate_6hS1Rwÿÿ['path'], $_obfuscate_6hS1Rwÿÿ['fileid'], $_obfuscate_FitaZ8Mÿ );
                $CNOA_DB->db_update( array(
                    "name" => $_obfuscate_3gn_eQÿÿ
                ), $this->table_public_file, "WHERE `fileid`='".$_obfuscate_6hS1Rwÿÿ['fileid']."'" );
                app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 30, array(
                    "{$_obfuscate_6hS1Rwÿÿ['name']}.{$_obfuscate_6hS1Rwÿÿ['ext']}" => "{$_obfuscate_3gn_eQÿÿ}.{$_obfuscate_6hS1Rwÿÿ['ext']}"
                ), lang( "fileName" ) );
            }
            $_obfuscate_dmhV4bRK = $_obfuscate_Ce9h;
        }
        msg::callback( TRUE, $_obfuscate_dmhV4bRK );
    }

    public function _uploadFolder( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_KMÿ = isset( $_SERVER['HTTP_X_FILENAME'] ) ? $_SERVER['HTTP_X_FILENAME'] : FALSE;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        if ( $_obfuscate_KMÿ )
        {
            $_obfuscate_LeS8hwÿÿ = getpar( $_GET, "type", "" );
            $_obfuscate_hNQa0gÿÿ = getpar( $_GET, "size", 0 );
            $_obfuscate_xbQRFgÿÿ = $_GET['fname'];
            if ( $_obfuscate_xbQRFgÿÿ != "" && $_obfuscate_xbQRFgÿÿ != "undefined" )
            {
                if ( $_obfuscate_LeS8hwÿÿ == "folder" )
                {
                    $_obfuscate_xbQRFgÿÿ = substr( $_obfuscate_xbQRFgÿÿ, 0, -2 );
                    $_obfuscate_3gn_eQÿÿ = str_replace( "/", "", substr( $_obfuscate_xbQRFgÿÿ, strripos( $_obfuscate_xbQRFgÿÿ, "/" ) ) );
                    $CNOA_DB->db_insert( array(
                        "path" => $_obfuscate_xbQRFgÿÿ,
                        "uid" => $_obfuscate_7Ri3,
                        "name" => $_obfuscate_3gn_eQÿÿ
                    ), $this->user_disk_folder );
                    exit( );
                }
                $_obfuscate_PW9SQhMxAgÿÿ = getpar( $_GET, "filename" );
                $_obfuscate_4u18J7MFlVugNyZ33wÿÿ = strripos( $_obfuscate_PW9SQhMxAgÿÿ, "." );
                $_obfuscate_ibEsWI9S = array( );
                $_obfuscate_ibEsWI9S['uid'] = $_obfuscate_7Ri3;
                $_obfuscate_ibEsWI9S['name'] = substr( $_obfuscate_PW9SQhMxAgÿÿ, 0, $_obfuscate_4u18J7MFlVugNyZ33wÿÿ );
                $_obfuscate_ibEsWI9S['ext'] = substr( $_obfuscate_PW9SQhMxAgÿÿ, $_obfuscate_4u18J7MFlVugNyZ33wÿÿ + 1 );
                $_obfuscate_ibEsWI9S['storepath'] = date( "Y/m", $GLOBALS['CNOA_TIMESTAMP'] );
                $_obfuscate_ibEsWI9S['storename'] = string::rands( 50 ).".cnoa";
                $_obfuscate_ibEsWI9S['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
                $_obfuscate_ibEsWI9S['size'] = getpar( $_GET, "size", 0 );
                $_obfuscate_N9qwQCSc = $CNOA_DB->db_insert( $_obfuscate_ibEsWI9S, $this->table_public_file );
                $_obfuscate_XLLZsBVE3gÿÿ = array( );
                $_obfuscate_XLLZsBVE3gÿÿ['fileid'] = $_obfuscate_N9qwQCSc;
                $_obfuscate_XLLZsBVE3gÿÿ['path'] = substr( $_obfuscate_xbQRFgÿÿ, 0, strripos( $_obfuscate_xbQRFgÿÿ, "/" ) );
                $_obfuscate_XLLZsBVE3gÿÿ['uid'] = $_obfuscate_7Ri3;
                $CNOA_DB->db_insert( $_obfuscate_XLLZsBVE3gÿÿ, $this->user_disk_folder_file );
                $_obfuscate_avMKPmB_13_P = CNOA_PATH_FILE."/common/disk/public/".date( "Y", $GLOBALS['CNOA_TIMESTAMP'] )."/".date( "m", $GLOBALS['CNOA_TIMESTAMP'] );
                @mkdirs( $_obfuscate_avMKPmB_13_P );
                $_obfuscate_95shZILiIgxgyQÿÿ = $_obfuscate_avMKPmB_13_P."/".$_obfuscate_ibEsWI9S['storename'];
                if ( $this->download( "php://input", $_obfuscate_95shZILiIgxgyQÿÿ ) )
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
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_fdpE = getpar( $_GET, "pid", 0 );
        $_obfuscate_sx8ÿ = $CNOA_DB->db_select( "*", $this->user_disk_folder, "WHERE `uid` = ".$_obfuscate_7Ri3." ORDER BY `path` ASC" );
        $_obfuscate_87qtSzObTplj1Aÿÿ = $CNOA_DB->db_select( "*", $this->user_disk_folder_file, "WHERE `uid` = ".$_obfuscate_7Ri3." " );
        if ( empty( $_obfuscate_sx8ÿ ) )
        {
            $_obfuscate_sx8ÿ = array(
                array(
                    "name" => $_obfuscate_87qtSzObTplj1Aÿÿ[0]['path'],
                    "path" => $_obfuscate_87qtSzObTplj1Aÿÿ[0]['path'],
                    "uid" => $_obfuscate_7Ri3
                )
            );
        }
        if ( !is_array( $_obfuscate_sx8ÿ ) )
        {
            $_obfuscate_sx8ÿ = array( );
        }
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_mPAjEGLn = array( );
        $_obfuscate_HH0WlIPhAgÿÿ = explode( "/", $_obfuscate_sx8ÿ[0]['path'] );
        if ( count( $_obfuscate_HH0WlIPhAgÿÿ ) == 2 )
        {
            $_obfuscate_mPAjEGLn[0] = array(
                "name" => $_obfuscate_HH0WlIPhAgÿÿ[0],
                "path" => $_obfuscate_HH0WlIPhAgÿÿ[0],
                "uid" => $_obfuscate_7Ri3
            );
            foreach ( $_obfuscate_sx8ÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_mPAjEGLn[] = $_obfuscate_6Aÿÿ;
            }
        }
        else
        {
            $_obfuscate_mPAjEGLn = $_obfuscate_sx8ÿ;
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_5wÿÿ == 0 )
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['pid'] = $_obfuscate_fdpE;
            }
            else
            {
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['pid'] = 0;
            }
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_6Aÿÿ['path']] = $_obfuscate_6Aÿÿ;
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_ibEsWI9S = array( );
            $_obfuscate_ibEsWI9S['name'] = $_obfuscate_6Aÿÿ['name'];
            $_obfuscate_ibEsWI9S['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $_obfuscate_Ce9h = $CNOA_DB->db_insert( $_obfuscate_ibEsWI9S, $this->table_public );
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_6Aÿÿ['path']]['fid'] = $_obfuscate_Ce9h;
            if ( empty( $_obfuscate_6Aÿÿ['pid'] ) )
            {
                $_obfuscate_v6TxaLdyOwÿÿ = str_replace( "/".$_obfuscate_6Aÿÿ['name'], "", $_obfuscate_6Aÿÿ['path'] );
                $_obfuscate_6RYLWQÿÿ[$_obfuscate_6Aÿÿ['path']]['pid'] = $_obfuscate_6RYLWQÿÿ[$_obfuscate_v6TxaLdyOwÿÿ]['fid'];
                $_obfuscate_6RYLWQÿÿ[$_obfuscate_6Aÿÿ['path']]['path'] = $_obfuscate_6RYLWQÿÿ[$_obfuscate_v6TxaLdyOwÿÿ]['path2'];
                $_obfuscate_6RYLWQÿÿ[$_obfuscate_6Aÿÿ['path']]['path2'] = $_obfuscate_6RYLWQÿÿ[$_obfuscate_v6TxaLdyOwÿÿ]['path2']."-".$_obfuscate_Ce9h;
            }
            else
            {
                $_obfuscate_VX_GBEWcB9Iÿ = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid` = ".$_obfuscate_6Aÿÿ['pid']." " );
                $_obfuscate_6RYLWQÿÿ[$_obfuscate_6Aÿÿ['path']]['path'] = $_obfuscate_VX_GBEWcB9Iÿ['path2'];
                $_obfuscate_6RYLWQÿÿ[$_obfuscate_6Aÿÿ['path']]['path2'] = $_obfuscate_VX_GBEWcB9Iÿ['path2']."-".$_obfuscate_Ce9h;
            }
        }
        if ( !is_array( $_obfuscate_87qtSzObTplj1Aÿÿ ) )
        {
            $_obfuscate_87qtSzObTplj1Aÿÿ = array( );
        }
        foreach ( $_obfuscate_87qtSzObTplj1Aÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_T7Mk4ltM['fid'] = $_obfuscate_6RYLWQÿÿ[$_obfuscate_6Aÿÿ['path']]['fid'];
            $CNOA_DB->db_update( $_obfuscate_T7Mk4ltM, $this->table_public_file, "WHERE `fileid` = ".$_obfuscate_6Aÿÿ['fileid']." " );
        }
        foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            unset( $_obfuscate_6Aÿÿ['id'] );
            $CNOA_DB->db_update( $_obfuscate_6Aÿÿ, $this->table_public, "WHERE `fid` = ".$_obfuscate_6Aÿÿ['fid']." " );
        }
        $CNOA_DB->db_delete( $this->user_disk_folder, "WHERE `uid` = ".$_obfuscate_7Ri3." " );
        $CNOA_DB->db_delete( $this->user_disk_folder_file, "WHERE `uid` = ".$_obfuscate_7Ri3." " );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function download( $_obfuscate_nFW4ipBT0TLYX5Iÿ, $_obfuscate_TymOiTYJEhrqSUÿ )
    {
        $_obfuscate_9Ywÿ = fopen( $_obfuscate_nFW4ipBT0TLYX5Iÿ, "rb" );
        $_obfuscate_FiAÿ = fopen( $_obfuscate_TymOiTYJEhrqSUÿ, "wb" );
        if ( $_obfuscate_9Ywÿ === FALSE || $_obfuscate_FiAÿ === FALSE )
        {
            return FALSE;
        }
        while ( !feof( $_obfuscate_9Ywÿ ) )
        {
            if ( !( fwrite( $_obfuscate_FiAÿ, fread( $_obfuscate_9Ywÿ, 1024 ) ) === FALSE ) )
            {
                continue;
            }
            return FALSE;
        }
        fclose( $_obfuscate_9Ywÿ );
        fclose( $_obfuscate_FiAÿ );
        return TRUE;
    }

    public function _upload( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        @ini_set( "default_socket_timeout", "86400" );
        @ini_set( "max_input_time", "86400" );
        set_time_limit( 0 );
        $_obfuscate_Ce9h = getpar( $_POST, "pid", 0 );
        $_obfuscate_xLknrwÿÿ = getpar( $_POST, "note", "" );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_iuzS = $CNOA_SESSION->get( "DID" );
        $_obfuscate_VXnKvu82BAÿÿ = TRUE;
        $_obfuscate_Fwl1 = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$_obfuscate_Ce9h."'" );
        $_obfuscate_ljWwbP9jSVP1 = $this->__getDirPermit( $_obfuscate_Fwl1 );
        if ( !( $_obfuscate_ljWwbP9jSVP1['up'] == "1" ) || !( $_obfuscate_ljWwbP9jSVP1['mgr'] == "1" ) )
        {
            msg::callback( FALSE, lang( "noPermitOpt" ) );
        }
        if ( !isset( $_FILES['Filedata'] ) )
        {
            $_obfuscate_mHQL4kA3m08nUiQÿ = lang( "fileTooBigToUpload" );
            $_obfuscate_VXnKvu82BAÿÿ = FALSE;
        }
        else if ( !is_uploaded_file( $_FILES['Filedata']['tmp_name'] ) )
        {
            $_obfuscate_mHQL4kA3m08nUiQÿ = lang( "notNormalFile" );
            $_obfuscate_VXnKvu82BAÿÿ = FALSE;
        }
        else if ( $_FILES['Filedata']['error'] != 0 )
        {
            $_obfuscate_mHQL4kA3m08nUiQÿ = lang( "uploadError" ).":" + $_FILES['Filedata']['error'];
            $_obfuscate_VXnKvu82BAÿÿ = FALSE;
        }
        else
        {
            $_obfuscate_mHQL4kA3m08nUiQÿ = lang( "uploadSucess" );
        }
        $_obfuscate_yq3iF6PvwJEC = date( "Y", $GLOBALS['CNOA_TIMESTAMP'] )."/".date( "m", $GLOBALS['CNOA_TIMESTAMP'] );
        $_obfuscate_p9iS3rrNwQQÿ = CNOA_PATH_FILE."/common/disk/public/".$_obfuscate_yq3iF6PvwJEC;
        @mkdirs( $_obfuscate_p9iS3rrNwQQÿ );
        if ( $_obfuscate_VXnKvu82BAÿÿ )
        {
            $_obfuscate_moWVHtDG_Aÿÿ = strtolower( strrchr( $_FILES['Filedata']['name'], "." ) );
            $_obfuscate_JTe7jJ4eGW8ÿ = string::rands( 50 ).".cnoa";
            $_obfuscate_OESonJ_jLYcÿ = $_obfuscate_p9iS3rrNwQQÿ."/".$_obfuscate_JTe7jJ4eGW8ÿ;
            @cnoa_move_uploaded_file( $_FILES['Filedata']['tmp_name'], $_obfuscate_OESonJ_jLYcÿ );
            $_obfuscate_fdpE = getpar( $_POST, "pid", 0 );
            $_obfuscate_6RYLWQÿÿ = array( );
            $_obfuscate_6RYLWQÿÿ['uid'] = $_obfuscate_7Ri3;
            $_obfuscate_6RYLWQÿÿ['fid'] = $_obfuscate_fdpE;
            $_obfuscate_6RYLWQÿÿ['name'] = preg_replace( "/(.*)".$_obfuscate_moWVHtDG_Aÿÿ."\$/i", "\\1", $_FILES['Filedata']['name'] );
            $_obfuscate_6RYLWQÿÿ['ext'] = str_replace( ".", "", $_obfuscate_moWVHtDG_Aÿÿ );
            $_obfuscate_6RYLWQÿÿ['storename'] = $_obfuscate_JTe7jJ4eGW8ÿ;
            $_obfuscate_6RYLWQÿÿ['storepath'] = $_obfuscate_yq3iF6PvwJEC;
            $_obfuscate_6RYLWQÿÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $_obfuscate_6RYLWQÿÿ['size'] = $_FILES['Filedata']['size'];
            $_obfuscate_6RYLWQÿÿ['note'] = $_obfuscate_xLknrwÿÿ;
            $_obfuscate_6RYLWQÿÿ['path'] = $CNOA_DB->db_getfield( "path", $this->table_public, "WHERE `fid` = '".$_obfuscate_fdpE."' " )."-".$_obfuscate_fdpE;
            $_obfuscate_Thgÿ = $CNOA_DB->db_getone( "*", $this->table_public_file, "WHERE `fid` = '".$_obfuscate_fdpE."' AND `name` = '{$_obfuscate_6RYLWQÿÿ['name']}' AND `ext` = '{$_obfuscate_6RYLWQÿÿ['ext']}' " );
            if ( empty( $_obfuscate_Thgÿ ) )
            {
                $_obfuscate_N9qwQCSc = $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->table_public_file );
                insertdisklog( 0, 0, $_obfuscate_6RYLWQÿÿ['path'], $_obfuscate_N9qwQCSc );
            }
            else
            {
                $_obfuscate_xx6UFms_nJUÿ['type'] = 0;
                $_obfuscate_xx6UFms_nJUÿ['fileid'] = $_obfuscate_Thgÿ['fileid'];
                $_obfuscate_xx6UFms_nJUÿ['storename'] = $_obfuscate_Thgÿ['storename'];
                $_obfuscate_xx6UFms_nJUÿ['storepath'] = $_obfuscate_Thgÿ['storepath'];
                $_obfuscate_xx6UFms_nJUÿ['posttime'] = $_obfuscate_Thgÿ['posttime'];
                $_obfuscate_xx6UFms_nJUÿ['size'] = $_obfuscate_Thgÿ['size'];
                $_obfuscate_xx6UFms_nJUÿ['note'] = $_obfuscate_Thgÿ['note'];
                $_obfuscate_xx6UFms_nJUÿ['uid'] = $_obfuscate_Thgÿ['uid'];
                $CNOA_DB->db_insert( $_obfuscate_xx6UFms_nJUÿ, $this->table_versions );
                if ( !empty( $_obfuscate_Thgÿ['thumb'] ) )
                {
                    $_obfuscate_wFQDAd5DBKjI = CNOA_PATH_FILE.( "/common/disk/public/".$_obfuscate_Thgÿ['storepath']."/" ).$_obfuscate_Thgÿ['thumb'].".".$_obfuscate_Thgÿ['ext'];
                    @unlink( $_obfuscate_wFQDAd5DBKjI );
                    $_obfuscate_wFQDAd5DBKjI = CNOA_PATH_FILE.( "/common/disk/public/".$_obfuscate_Thgÿ['storepath']."/" ).$_obfuscate_Thgÿ['thumb'].".mid.".$_obfuscate_Thgÿ['ext'];
                    @unlink( $_obfuscate_wFQDAd5DBKjI );
                    $_obfuscate_6RYLWQÿÿ['thumb'] = "";
                }
                $CNOA_DB->db_update( $_obfuscate_6RYLWQÿÿ, $this->table_public_file, "WHERE `fileid` = '".$_obfuscate_Thgÿ['fileid']."' " );
                insertdisklog( 1, 0, $_obfuscate_Thgÿ['path'], $_obfuscate_Thgÿ['fileid'] );
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 30, "{$_obfuscate_6RYLWQÿÿ['name']}\\.{$_obfuscate_6RYLWQÿÿ['ext']}", "æä»¶" );
        }
        msg::callback( $_obfuscate_VXnKvu82BAÿÿ, $_obfuscate_mHQL4kA3m08nUiQÿ );
    }

    private function __checkMyFieldPermit( $_obfuscate_N9qwQCSc, $_obfuscate_pp9pYwÿÿ )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_iuzS = $CNOA_SESSION->get( "DID" );
        if ( $this->isAdmin )
        {
            return TRUE;
        }
        if ( empty( $_obfuscate_pp9pYwÿÿ ) )
        {
            $_obfuscate_HH0WlIPhAgÿÿ = array( 0 );
        }
        else
        {
            $_obfuscate_HH0WlIPhAgÿÿ = explode( "-", $_obfuscate_pp9pYwÿÿ );
        }
        $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->table_public_permit_p, "WHERE (`path` = '".$_obfuscate_pp9pYwÿÿ."' AND (`dl` = 1 OR `vi` = 1)) OR (`fid` IN (".implode( ",", $_obfuscate_HH0WlIPhAgÿÿ ).") AND `mgr` = 1 )" );
        if ( empty( $_obfuscate_Ybai ) )
        {
            $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->table_public_permit_s, "WHERE (`path` = '".$_obfuscate_pp9pYwÿÿ."' AND (`dl` = 1 OR `vi` = 1)) OR (`fid` IN (".implode( ",", $_obfuscate_HH0WlIPhAgÿÿ ).") AND `mgr` = 1 )" );
            if ( empty( $_obfuscate_Ybai ) )
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
        $_obfuscate_N9qwQCSc = getpar( $_GET, "fileid", 0 );
        $_obfuscate_Thgÿ = $CNOA_DB->db_getone( array( "path", "name", "ext" ), $this->table_public_file, "WHERE `fileid` = '".$_obfuscate_N9qwQCSc."' " );
        if ( !$this->__checkMyFieldPermit( $_obfuscate_N9qwQCSc, $_obfuscate_Thgÿ['path'] ) )
        {
            exit( );
        }
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->table_versions, "WHERE `fileid` = '".$_obfuscate_N9qwQCSc."' ORDER BY `id` DESC " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_PVLK5jra = array( 0 );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_PVLK5jra[] = $_obfuscate_6Aÿÿ['uid'];
        }
        $_obfuscate_r5Ne4piDbMÿ = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_PVLK5jra );
        $_obfuscate_Ybai = count( $_obfuscate_mPAjEGLn );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['num'] = $_obfuscate_Ybai;
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['posttime'] = formatdate( $_obfuscate_6Aÿÿ['posttime'], "Y-m-d H:i" );
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['truename'] = $_obfuscate_r5Ne4piDbMÿ[$_obfuscate_6Aÿÿ['uid']]['truename'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['down'] = makedownloadicon( "{$GLOBALS['URL_FILE']}/common/disk/public/{$_obfuscate_6Aÿÿ['storepath']}/{$_obfuscate_6Aÿÿ['storename']}", $_obfuscate_Thgÿ['name'].".".$_obfuscate_Thgÿ['ext'], "html" );
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['view'] = "&nbsp;".makeattachpreviewicon( $_obfuscate_6Aÿÿ['name'].".".$_obfuscate_6Aÿÿ['ext'], "{$GLOBALS['URL_FILE']}/common/disk/public/{$_obfuscate_6Aÿÿ['storepath']}/{$_obfuscate_6Aÿÿ['storename']}", ".".$_obfuscate_Thgÿ['ext'] );
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['edit'] = makediskediticon( "{$GLOBALS['URL_FILE']}/common/disk/public/{$_obfuscate_6Aÿÿ['storepath']}/{$_obfuscate_6Aÿÿ['storename']}", ".".$_obfuscate_Thgÿ['ext'], TRUE, "disk_pub", $_obfuscate_6Aÿÿ['id'], $_obfuscate_6Aÿÿ['storename'], $_obfuscate_Thgÿ['name'], "pubdisk", "history" );
            --$_obfuscate_Ybai;
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _deleteVersions( )
    {
        global $CNOA_DB;
        $_obfuscate_0W8ÿ = getpar( $_POST, "id" );
        $CNOA_DB->db_delete( $this->table_versions, "WHERE `id`=".$_obfuscate_0W8ÿ );
        msg::callback( TRUE, "æä½æå" );
    }

    private function _updataVersions( )
    {
        global $CNOA_DB;
        $_obfuscate_0W8ÿ = getpar( $_POST, "id" );
        $_obfuscate_YIq2A8cÿ = getpar( $_POST, "field" );
        $_obfuscate_VgKtFegÿ = getpar( $_POST, "value" );
        $CNOA_DB->db_update( array(
            $_obfuscate_YIq2A8cÿ => $_obfuscate_VgKtFegÿ
        ), $this->table_versions, "WHERE `id`=".$_obfuscate_0W8ÿ );
        msg::callback( TRUE, "æä½æå" );
    }

    private function _getLogList( )
    {
        global $CNOA_DB;
        $_obfuscate_LeS8hwÿÿ = getpar( $_GET, "type", 0 );
        $_obfuscate_0W8ÿ = getpar( $_GET, "id", 0 );
        $_obfuscate_LeS8hwÿÿ == "d" ? ( $_obfuscate_LeS8hwÿÿ = 1 ) : ( $_obfuscate_LeS8hwÿÿ = 0 );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->table_log, "WHERE `type` = '".$_obfuscate_LeS8hwÿÿ."' AND `fileid` = '{$_obfuscate_0W8ÿ}' ORDER BY `id` DESC " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['log'] = $this->logArr[$_obfuscate_6Aÿÿ['log']].$_obfuscate_6Aÿÿ['other'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['posttime'] = formatdate( $_obfuscate_6Aÿÿ['posttime'], "Y-m-d H:i" );
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _saveasdisk( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_iuzS = $CNOA_SESSION->get( "JID" );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_1jUa = substr( getpar( $_POST, "ids", 0 ), 0, -1 );
        $_obfuscate_O6QLLacÿ = explode( ",", $_obfuscate_1jUa );
        $_obfuscate_Fwl1 = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid` IN ('".$_obfuscate_1jUa."')" );
        $_obfuscate_ljWwbP9jSVP1 = $this->__getDirPermit( $_obfuscate_Fwl1 );
        if ( !( $_obfuscate_ljWwbP9jSVP1['up'] == "1" ) || !( $_obfuscate_ljWwbP9jSVP1['mgr'] == "1" ) )
        {
            msg::callback( FALSE, lang( "noPermitUploadInFolder", $_obfuscate_Fwl1['name'] ) );
        }
        $_obfuscate_6RYLWQÿÿ = getpar( $_POST, "data", "" );
        $_obfuscate_3gn_eQÿÿ = getpar( $_POST, "name", "" );
        $_obfuscate_CjEG56H1qwÿÿ = explode( ",", $_obfuscate_6RYLWQÿÿ );
        $_obfuscate_L1ZGEQTB4wÿÿ = explode( ",", $_obfuscate_3gn_eQÿÿ );
        $_obfuscate_vholQÿÿ = getpar( $_POST, "from", "" );
        foreach ( $_obfuscate_CjEG56H1qwÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_xs33Yt_k = $this->__formatFrom( $_obfuscate_vholQÿÿ, $_obfuscate_6Aÿÿ );
            $_obfuscate_yq3iF6PvwJEC = date( "Y", $GLOBALS['CNOA_TIMESTAMP'] )."/".date( "m", $GLOBALS['CNOA_TIMESTAMP'] );
            $_obfuscate_byaxKAÿÿ = CNOA_PATH_FILE."/common/disk/public/".$_obfuscate_yq3iF6PvwJEC;
            @mkdirs( $_obfuscate_byaxKAÿÿ );
            $_obfuscate_hNQa0gÿÿ = filesize( $_obfuscate_xs33Yt_k['path'] );
            foreach ( $_obfuscate_O6QLLacÿ as $_obfuscate_0W8ÿ )
            {
                $_obfuscate_JTe7jJ4eGW8ÿ = string::rands( 50 ).".cnoa";
                $_obfuscate_YzxYuUGI = $_obfuscate_byaxKAÿÿ."/".$_obfuscate_JTe7jJ4eGW8ÿ;
                @copy( $_obfuscate_xs33Yt_k['path'], $_obfuscate_YzxYuUGI );
                $_obfuscate_ibEsWI9S['fid'] = $_obfuscate_0W8ÿ;
                $_obfuscate_ibEsWI9S['uid'] = $_obfuscate_7Ri3;
                $_obfuscate_ibEsWI9S['name'] = $_obfuscate_L1ZGEQTB4wÿÿ[$_obfuscate_5wÿÿ];
                $_obfuscate_ibEsWI9S['ext'] = $_obfuscate_xs33Yt_k['ext'];
                $_obfuscate_ibEsWI9S['size'] = $_obfuscate_hNQa0gÿÿ;
                $_obfuscate_ibEsWI9S['storename'] = $_obfuscate_JTe7jJ4eGW8ÿ;
                $_obfuscate_ibEsWI9S['storepath'] = $_obfuscate_yq3iF6PvwJEC;
                $_obfuscate_ibEsWI9S['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
                $_obfuscate_ibEsWI9S['path'] = $CNOA_DB->db_getfield( "path2", $this->table_public, "WHERE `fid` = '".$_obfuscate_0W8ÿ."' " );
                $CNOA_DB->db_insert( $_obfuscate_ibEsWI9S, $this->table_public_file );
            }
            @unlink( $_obfuscate_xs33Yt_k['path'] );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _saveasdiskandfs( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_iuzS = $CNOA_SESSION->get( "JID" );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_1jUa = substr( getpar( $_POST, "ids", 0 ), 0, -1 );
        $_obfuscate_O6QLLacÿ = explode( ",", $_obfuscate_1jUa );
        $_obfuscate_ViKf3gÿÿ = substr( getpar( $_GET, "fids", getpar( $_POST, "fids" ) ), 0, -1 );
        $_obfuscate_jgÿÿ = explode( ",", $_obfuscate_ViKf3gÿÿ );
        $_obfuscate_Fwl1 = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid` IN ('".$_obfuscate_1jUa."')" );
        $_obfuscate_ljWwbP9jSVP1 = $this->__getDirPermit( $_obfuscate_Fwl1 );
        if ( !( $_obfuscate_ljWwbP9jSVP1['up'] == "1" ) || !( $_obfuscate_ljWwbP9jSVP1['mgr'] == "1" ) )
        {
            msg::callback( FALSE, lang( "noPermitUploadInFolder", $_obfuscate_Fwl1['name'] ) );
        }
        $_obfuscate_6RYLWQÿÿ = getpar( $_POST, "data", "" );
        $_obfuscate_3gn_eQÿÿ = getpar( $_POST, "name", "" );
        $_obfuscate_CjEG56H1qwÿÿ = explode( ",", $_obfuscate_6RYLWQÿÿ );
        $_obfuscate_L1ZGEQTB4wÿÿ = explode( ",", $_obfuscate_3gn_eQÿÿ );
        $_obfuscate_vholQÿÿ = getpar( $_POST, "from", "" );
        foreach ( $_obfuscate_O6QLLacÿ as $_obfuscate_5wÿÿ => $_obfuscate_0W8ÿ )
        {
            $_obfuscate__83zoR09OjK = array( );
            $_obfuscate__83zoR09OjK['name'] = $_obfuscate_L1ZGEQTB4wÿÿ[0];
            $_obfuscate__83zoR09OjK['uid'] = $_obfuscate_7Ri3;
            $_obfuscate__83zoR09OjK['pid'] = $_obfuscate_0W8ÿ;
            $_obfuscate__83zoR09OjK['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->table_public, "WHERE `pid` = '".$_obfuscate_0W8ÿ."' AND `name` = '{$_obfuscate__83zoR09OjK['name']}' " );
            if ( !empty( $_obfuscate_Ybai ) )
            {
                msg::callback( FALSE, lang( "folderExist" ) );
            }
            if ( $_obfuscate_0W8ÿ == 0 )
            {
                if ( !$this->isAdmin )
                {
                    msg::callback( FALSE, lang( "notAdminToAddFolderInRoot" ) );
                }
                $_obfuscate__83zoR09OjK['path'] = 0;
            }
            else
            {
                $_obfuscate_pp9pYwÿÿ = $CNOA_DB->db_getfield( "path", $this->table_public, "WHERE `fid` = '".$_obfuscate_0W8ÿ."' " );
                $_obfuscate__83zoR09OjK['path'] = $_obfuscate_pp9pYwÿÿ."-".$_obfuscate_0W8ÿ;
            }
            $_obfuscate_dmhV4bRK = $CNOA_DB->db_insert( $_obfuscate__83zoR09OjK, $this->table_public );
            $_obfuscate_O6QLLacÿ[$_obfuscate_5wÿÿ] = $_obfuscate_dmhV4bRK;
            insertdisklog( 6, 1, $_obfuscate__83zoR09OjK['path']."-".$_obfuscate_dmhV4bRK, $_obfuscate_dmhV4bRK, $_obfuscate_3gn_eQÿÿ );
            $CNOA_DB->db_update( array(
                "path2" => $_obfuscate__83zoR09OjK['path']."-".$_obfuscate_dmhV4bRK
            ), $this->table_public, "WHERE `fid` = '".$_obfuscate_dmhV4bRK."' " );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 30, $_obfuscate__83zoR09OjK['name'], lang( "folder" ) );
        }
        foreach ( $_obfuscate_CjEG56H1qwÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_xs33Yt_k = $this->__formatFrom( $_obfuscate_vholQÿÿ, $_obfuscate_6Aÿÿ );
            $_obfuscate_yq3iF6PvwJEC = date( "Y", $GLOBALS['CNOA_TIMESTAMP'] )."/".date( "m", $GLOBALS['CNOA_TIMESTAMP'] );
            $_obfuscate_byaxKAÿÿ = CNOA_PATH_FILE."/common/disk/public/".$_obfuscate_yq3iF6PvwJEC;
            @mkdirs( $_obfuscate_byaxKAÿÿ );
            $_obfuscate_hNQa0gÿÿ = @filesize( $_obfuscate_xs33Yt_k['path'] );
            foreach ( $_obfuscate_O6QLLacÿ as $_obfuscate_0W8ÿ )
            {
                $_obfuscate_JTe7jJ4eGW8ÿ = string::rands( 50 ).".cnoa";
                $_obfuscate_YzxYuUGI = $_obfuscate_byaxKAÿÿ."/".$_obfuscate_JTe7jJ4eGW8ÿ;
                @copy( $_obfuscate_xs33Yt_k['path'], $_obfuscate_YzxYuUGI );
                $_obfuscate_ibEsWI9S['fid'] = $_obfuscate_0W8ÿ;
                $_obfuscate_ibEsWI9S['uid'] = $_obfuscate_7Ri3;
                $_obfuscate_ibEsWI9S['name'] = $_obfuscate_L1ZGEQTB4wÿÿ[$_obfuscate_5wÿÿ];
                $_obfuscate_ibEsWI9S['ext'] = $_obfuscate_xs33Yt_k['ext'];
                $_obfuscate_ibEsWI9S['size'] = $_obfuscate_hNQa0gÿÿ;
                $_obfuscate_ibEsWI9S['storename'] = $_obfuscate_JTe7jJ4eGW8ÿ;
                $_obfuscate_ibEsWI9S['storepath'] = $_obfuscate_yq3iF6PvwJEC;
                $_obfuscate_ibEsWI9S['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
                $_obfuscate_ibEsWI9S['path'] = $CNOA_DB->db_getfield( "path2", $this->table_public, "WHERE `fid` = '".$_obfuscate_0W8ÿ."' " );
                $CNOA_DB->db_insert( $_obfuscate_ibEsWI9S, $this->table_public_file );
                foreach ( $_obfuscate_jgÿÿ as $_obfuscate_Vwty => $_obfuscate_Ce9h )
                {
                    $_obfuscate_e4RqR5pcaUkÿ = $CNOA_DB->db_getone( "*", "system_fs", "WHERE `id`='".$_obfuscate_Ce9h."'" );
                    $_obfuscate_iPQOmcI = CNOA_PATH_FILE."/common/vfs/".substr( $_obfuscate_e4RqR5pcaUkÿ[name], 0, 4 )."/".substr( $_obfuscate_e4RqR5pcaUkÿ[name], 4, 2 )."/".$_obfuscate_e4RqR5pcaUkÿ[name];
                    $_obfuscate_JTe7jJ4eGW8ÿ = string::rands( 50 ).".cnoa";
                    $_obfuscate_YzxYuUGI = $_obfuscate_byaxKAÿÿ."/".$_obfuscate_JTe7jJ4eGW8ÿ;
                    @copy( $_obfuscate_iPQOmcI, $_obfuscate_YzxYuUGI );
                    $_obfuscate_dz7t3F6f4gÿÿ = explode( ".", $_obfuscate_e4RqR5pcaUkÿ[oldname] );
                    $_obfuscate_ibEsWI9S['fid'] = $_obfuscate_0W8ÿ;
                    $_obfuscate_ibEsWI9S['uid'] = $_obfuscate_7Ri3;
                    $_obfuscate_ibEsWI9S['name'] = str_replace( ".".$_obfuscate_dz7t3F6f4gÿÿ[count( $_obfuscate_dz7t3F6f4gÿÿ ) - 1], "", $_obfuscate_e4RqR5pcaUkÿ['oldname'] );
                    $_obfuscate_ibEsWI9S['ext'] = $_obfuscate_dz7t3F6f4gÿÿ[count( $_obfuscate_dz7t3F6f4gÿÿ ) - 1];
                    $_obfuscate_ibEsWI9S['size'] = filesize( $_obfuscate_iPQOmcI );
                    $_obfuscate_ibEsWI9S['storename'] = $_obfuscate_JTe7jJ4eGW8ÿ;
                    $_obfuscate_ibEsWI9S['storepath'] = $_obfuscate_yq3iF6PvwJEC;
                    $_obfuscate_ibEsWI9S['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
                    $_obfuscate_ibEsWI9S['path'] = $CNOA_DB->db_getfield( "path2", $this->table_public, "WHERE `fid` = '".$_obfuscate_0W8ÿ."' " );
                    $CNOA_DB->db_insert( $_obfuscate_ibEsWI9S, $this->table_public_file );
                }
            }
            @unlink( $_obfuscate_xs33Yt_k['path'] );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _submitShare( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_N9qwQCSc = getpar( $_POST, "fileid", 0 );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_getone( "*", $this->table_public_file, "WHERE `fileid` = ".$_obfuscate_N9qwQCSc." " );
        $_obfuscate_Wu3qBkSVfXgÿ = CNOA_PATH_FILE."/common/disk/user/".$_obfuscate_7Ri3;
        $_obfuscate_PW9SQhMxAgÿÿ = string::rands( 50 ).".cnoa";
        @mkdirs( $_obfuscate_Wu3qBkSVfXgÿ );
        $_obfuscate_oiWljGvpmNIÿ = explode( "/", $_obfuscate_mPAjEGLn['storepath'] );
        $_obfuscate_yXz2vGnU4PoUNgÿÿ = CNOA_PATH_FILE."/common/disk/public/".$_obfuscate_oiWljGvpmNIÿ[0]."/".$_obfuscate_oiWljGvpmNIÿ[1]."/".$_obfuscate_mPAjEGLn['storename'];
        @copy( $_obfuscate_yXz2vGnU4PoUNgÿÿ, $_obfuscate_Wu3qBkSVfXgÿ."/".$_obfuscate_PW9SQhMxAgÿÿ );
        $_obfuscate_ibEsWI9S = array( );
        $_obfuscate_ibEsWI9S['name'] = $_obfuscate_mPAjEGLn['name'];
        $_obfuscate_ibEsWI9S['ext'] = $_obfuscate_mPAjEGLn['ext'];
        $_obfuscate_ibEsWI9S['storename'] = $_obfuscate_PW9SQhMxAgÿÿ;
        $_obfuscate_ibEsWI9S['type'] = "sf";
        $_obfuscate_ibEsWI9S['pid'] = "0";
        $_obfuscate_ibEsWI9S['path'] = "0";
        $_obfuscate_ibEsWI9S['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_ibEsWI9S['size'] = $_obfuscate_mPAjEGLn['size'];
        $_obfuscate_ibEsWI9S['downLoad'] = getpar( $_POST, "download", "" ) == "on" ? 1 : 0;
        $_obfuscate_ibEsWI9S['edit'] = getpar( $_POST, "edit", "" ) == "on" ? 1 : 0;
        $_obfuscate_ibEsWI9S['sharefrom'] = $_obfuscate_7Ri3;
        $_obfuscate_ibEsWI9S['fromfileid'] = $_obfuscate_mPAjEGLn['fileid'];
        $_obfuscate_ibEsWI9S['disTime'] = getpar( $_POST, "disTime", "" ) == "" ? "" : strtotime( getpar( $_POST, "disTime", "" ) );
        $_obfuscate_ibEsWI9S['disDownload'] = getpar( $_POST, "disDownload", 0 );
        $_obfuscate_ibEsWI9S['disView'] = getpar( $_POST, "disView", 0 );
        $_obfuscate_ibEsWI9S['email'] = getpar( $_POST, "email", "" ) == "on" ? 0 : 1;
        $_obfuscate_zQYokyJI5nPOvsÿ = getpar( $_POST, "outsideLink", "" );
        $_obfuscate_RrCtNAÿÿ = FALSE;
        $_obfuscate_97pDCpNOBQmS = "";
        if ( $_obfuscate_zQYokyJI5nPOvsÿ == "on" )
        {
            $_obfuscate_97pDCpNOBQmS = $this->__createOutsideLink( $_obfuscate_ibEsWI9S, $_obfuscate_N9qwQCSc );
            $_obfuscate_RrCtNAÿÿ = TRUE;
        }
        $_obfuscate__eqrEQÿÿ = getpar( $_POST, "peopleUid", 0 );
        $_obfuscate_YnJ5teemawÿÿ = explode( ",", $_obfuscate__eqrEQÿÿ );
        $_obfuscate_9TOt5LYBswÿÿ = getpar( $_POST, "deptIds", 0 );
        $_obfuscate_E3_SXpFk = app::loadapp( "main", "user" )->api_getUidsByDeptIdList( $_obfuscate_9TOt5LYBswÿÿ );
        if ( !is_array( $_obfuscate_E3_SXpFk ) )
        {
            $_obfuscate_E3_SXpFk = array( );
        }
        $_obfuscate_PVLK5jra = array( );
        foreach ( $_obfuscate_YnJ5teemawÿÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_PVLK5jra[] = $_obfuscate_VgKtFegÿ;
        }
        foreach ( $_obfuscate_E3_SXpFk as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_PVLK5jra[] = $_obfuscate_VgKtFegÿ['uid'];
        }
        $_obfuscate_PVLK5jra = array_unique( $_obfuscate_PVLK5jra );
        $_obfuscate_BXLwiJME1L1E = array( );
        $_obfuscate_xs33Yt_k = $CNOA_DB->db_select( array( "uid", "sharefrom", "storename" ), $this->table_list, "WHERE `fromfileid`='".$_obfuscate_mPAjEGLn['fileid']."'" );
        if ( !is_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_xs33Yt_k = array( );
        }
        foreach ( $_obfuscate_xs33Yt_k as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_BXLwiJME1L1E[] = $_obfuscate_VgKtFegÿ['uid'];
        }
        if ( !empty( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_p9iS3rrNwQQÿ = CNOA_PATH_FILE.( "/common/disk/user/".$_obfuscate_xs33Yt_k[0]['sharefrom']."/{$_obfuscate_xs33Yt_k[0]['storename']}" );
            @unlink( $_obfuscate_p9iS3rrNwQQÿ );
        }
        $_obfuscate_XogUCWLn92l9Qÿÿ = array_diff( $_obfuscate_BXLwiJME1L1E, $_obfuscate_PVLK5jra );
        $_obfuscate_ilS8kPLkS9bSJwÿÿ = array_intersect( $_obfuscate_BXLwiJME1L1E, $_obfuscate_PVLK5jra );
        $_obfuscate_VqHuvOTHKgOBrQÿÿ = array_diff( $_obfuscate_PVLK5jra, $_obfuscate_BXLwiJME1L1E );
        if ( !is_array( $_obfuscate_XogUCWLn92l9Qÿÿ ) )
        {
            $_obfuscate_XogUCWLn92l9Qÿÿ = array( );
        }
        foreach ( $_obfuscate_XogUCWLn92l9Qÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $CNOA_DB->db_delete( $this->table_list, "WHERE `uid`='".$_obfuscate_6Aÿÿ."' AND `fromfileid`='{$_obfuscate_mPAjEGLn['fileid']}'" );
        }
        if ( !is_array( $_obfuscate_ilS8kPLkS9bSJwÿÿ ) )
        {
            $_obfuscate_ilS8kPLkS9bSJwÿÿ = array( );
        }
        foreach ( $_obfuscate_ilS8kPLkS9bSJwÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $CNOA_DB->db_update( $_obfuscate_ibEsWI9S, $this->table_list, "WHERE `uid`='".$_obfuscate_6Aÿÿ."' AND `fromfileid`='{$_obfuscate_mPAjEGLn['fileid']}'" );
        }
        if ( !is_array( $_obfuscate_VqHuvOTHKgOBrQÿÿ ) )
        {
            $_obfuscate_VqHuvOTHKgOBrQÿÿ = array( );
        }
        foreach ( $_obfuscate_VqHuvOTHKgOBrQÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_ibEsWI9S['uid'] = $_obfuscate_6Aÿÿ;
            $_obfuscate_0W8ÿ = $CNOA_DB->db_insert( $_obfuscate_ibEsWI9S, $this->table_list );
            $CNOA_DB->db_update( array(
                "path2" => "0-".$_obfuscate_0W8ÿ
            ), $this->table_list, "WHERE `fid`='".$_obfuscate_0W8ÿ."'" );
        }
        $CNOA_DB->db_update( array(
            "sharedpeopleUid" => $_obfuscate__eqrEQÿÿ,
            "sharedDeptIds" => $_obfuscate_9TOt5LYBswÿÿ
        ), $this->table_public_file, "WHERE `fileid` = ".$_obfuscate_N9qwQCSc." " );
        app::loadapp( "user", "diskIndex" )->api_tidyDisk( );
        echo json_encode( array(
            "success" => TRUE,
            "msg" => lang( "successopt" ),
            "rand" => $_obfuscate_RrCtNAÿÿ,
            "randomUrl" => $_obfuscate_97pDCpNOBQmS
        ) );
        exit( );
    }

    public function api_outsidelink( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        $_obfuscate_Vwty = getpar( $_GET, "key", "" );
        if ( empty( $_obfuscate_Vwty ) || strlen( $_obfuscate_Vwty ) != 50 )
        {
            echo lang( "wrongDiskLink" );
            exit( );
        }
        $_obfuscate_sx8ÿ = $CNOA_DB->db_getone( "*", $this->table_outsideLink, "WHERE `randomnum` = '".$_obfuscate_Vwty."' " );
        if ( empty( $_obfuscate_sx8ÿ ) )
        {
            echo lang( "linkExpire" );
            exit( );
        }
        if ( !empty( $_obfuscate_sx8ÿ['disTime'] ) || $_obfuscate_sx8ÿ['disTime'] < $GLOBALS['CNOA_TIMESTAMP'] )
        {
            $CNOA_DB->db_delete( $this->table_outsideLink, "WHERE `randomnum` = '".$_obfuscate_Vwty."' " );
            echo lang( "linkExpire" );
            exit( );
        }
        $_obfuscate_IZnbbbl = $CNOA_DB->db_getone( "*", $this->table_public_file, "WHERE `fileid` = ".$_obfuscate_sx8ÿ['fid']." " );
        $_obfuscate_NDG5eBIMYiIÿ = makeattachpreviewicon( $_obfuscate_IZnbbbl['name'].".".$_obfuscate_IZnbbbl['ext'], "{$GLOBALS['URL_FILE']}/common/disk/public/{$_obfuscate_IZnbbbl['storepath']}/{$_obfuscate_IZnbbbl['storename']}", ".".$_obfuscate_IZnbbbl['ext'] );
        if ( !empty( $_obfuscate_sx8ÿ['disView'] ) )
        {
            $_obfuscate_NDG5eBIMYiIÿ = str_replace( "'>".lang( "browse" )."</a>", "' onclick='startAjax(\"view\");'>".lang( "browse" )."</a>", $_obfuscate_NDG5eBIMYiIÿ );
        }
        $_obfuscate_lEGQqwÿÿ = $_obfuscate_NDG5eBIMYiIÿ;
        if ( $_obfuscate_sx8ÿ['download'] )
        {
            $_obfuscate_ULEIY_rBrWNngbz2 = makedownloadicon( "{$GLOBALS['URL_FILE']}/common/disk/public/{$_obfuscate_IZnbbbl['storepath']}/{$_obfuscate_IZnbbbl['storename']}", $_obfuscate_IZnbbbl['name'].".".$_obfuscate_IZnbbbl['ext'], "html" );
            if ( !empty( $_obfuscate_sx8ÿ['disDownload'] ) )
            {
                $_obfuscate_ULEIY_rBrWNngbz2 = str_replace( "'>".lang( "download" )."</a>", "' onclick='startAjax(\"download\");'>".lang( "download" )."</a>", $_obfuscate_ULEIY_rBrWNngbz2 );
            }
            $_obfuscate_lEGQqwÿÿ .= $_obfuscate_ULEIY_rBrWNngbz2;
        }
        if ( $_obfuscate_sx8ÿ['edit'] )
        {
            $_obfuscate_L_LLbeezctEÿ = makediskediticon( "{$GLOBALS['URL_FILE']}/common/disk/public/{$_obfuscate_IZnbbbl['storepath']}/{$_obfuscate_IZnbbbl['storename']}", ".".$_obfuscate_IZnbbbl['ext'], TRUE, "disk_pub", $_obfuscate_IZnbbbl['fileid'], $_obfuscate_IZnbbbl['storename'], $_obfuscate_IZnbbbl['name'], "pubdisk" );
            if ( !empty( $_obfuscate_sx8ÿ['disView'] ) )
            {
                $_obfuscate_L_LLbeezctEÿ = str_replace( "'>".lang( "modify" )."</a>", "' onclick='startAjax(\"view\");'>".lang( "modify" )."</a>", $_obfuscate_L_LLbeezctEÿ );
            }
            $_obfuscate_lEGQqwÿÿ .= $_obfuscate_L_LLbeezctEÿ;
        }
        $GLOBALS['GLOBALS']['outsidelink']['html'] = $_obfuscate_lEGQqwÿÿ;
        $GLOBALS['GLOBALS']['outsidelink']['key'] = $_obfuscate_Vwty;
        $_obfuscate_BxoH_SjRHQÿÿ = CNOA_PATH."/app/user/tpl/default/disk/outsidelink.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $_obfuscate_BxoH_SjRHQÿÿ );
        exit( );
    }

    public function api_outsidelinkhandler( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_Vwty = getpar( $_GET, "key", "" );
        $_obfuscate_sx8ÿ = $CNOA_DB->db_getone( "*", $this->table_outsideLink, "WHERE `randomnum` = '".$_obfuscate_Vwty."' " );
        $_obfuscate_vholQÿÿ = getpar( $_GET, "from", "" );
        if ( $_obfuscate_vholQÿÿ == "view" )
        {
            if ( $_obfuscate_sx8ÿ['viewTimes'] == $_obfuscate_sx8ÿ['disView'] )
            {
                $_obfuscate_pmYlpelK3wÿÿ = TRUE;
                $CNOA_DB->db_delete( $this->table_outsideLink, "WHERE `randomnum` = '".$_obfuscate_Vwty."' " );
            }
            else
            {
                $CNOA_DB->db_update( array(
                    "viewTimes" => $_obfuscate_sx8ÿ['viewTimes'] + 1
                ), $this->table_outsideLink, "WHERE `randomnum` = '".$_obfuscate_Vwty."' " );
                $_obfuscate_pmYlpelK3wÿÿ = FALSE;
            }
        }
        if ( $_obfuscate_vholQÿÿ == "download" )
        {
            if ( $_obfuscate_sx8ÿ['downLoadTimes'] == $_obfuscate_sx8ÿ['disDownload'] )
            {
                $_obfuscate_pmYlpelK3wÿÿ = TRUE;
                $CNOA_DB->db_delete( $this->table_outsideLink, "WHERE `randomnum` = '".$_obfuscate_Vwty."' " );
            }
            else
            {
                $CNOA_DB->db_update( array(
                    "downLoadTimes" => $_obfuscate_sx8ÿ['downLoadTimes'] + 1
                ), $this->table_outsideLink, "WHERE `randomnum` = '".$_obfuscate_Vwty."' " );
                $_obfuscate_pmYlpelK3wÿÿ = FALSE;
            }
        }
        echo json_encode( array(
            "success" => TRUE,
            "reflash" => $_obfuscate_pmYlpelK3wÿÿ
        ) );
        exit( );
    }

    private function __createOutsideLink( $_obfuscate_ibEsWI9S, $_obfuscate_N9qwQCSc )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_6RYLWQÿÿ['fid'] = $_obfuscate_N9qwQCSc;
        $_obfuscate_6RYLWQÿÿ['download'] = $_obfuscate_ibEsWI9S['downLoad'];
        $_obfuscate_6RYLWQÿÿ['edit'] = $_obfuscate_ibEsWI9S['edit'];
        $_obfuscate_6RYLWQÿÿ['disTime'] = $_obfuscate_ibEsWI9S['disTime'];
        $_obfuscate_6RYLWQÿÿ['disDownload'] = $_obfuscate_ibEsWI9S['disDownload'];
        $_obfuscate_6RYLWQÿÿ['downLoadTimes'] = $_obfuscate_ibEsWI9S['downLoadTimes'];
        $_obfuscate_6RYLWQÿÿ['disView'] = $_obfuscate_ibEsWI9S['disView'];
        $_obfuscate_6RYLWQÿÿ['viewTimes'] = $_obfuscate_ibEsWI9S['viewTimes'];
        $_obfuscate_6RYLWQÿÿ['randomnum'] = string::rands( 50 );
        if ( empty( $_obfuscate_6RYLWQÿÿ['disTime'] ) && empty( $_obfuscate_6RYLWQÿÿ['disDownload'] ) && empty( $_obfuscate_6RYLWQÿÿ['disView'] ) )
        {
            $_obfuscate_6RYLWQÿÿ['disTime'] = $GLOBALS['CNOA_TIMESTAMP'] + 691200;
        }
        $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->table_outsideLink );
        $_obfuscate_4Honjwÿÿ = "";
        if ( $_SERVER['SERVER_PORT'] != 80 )
        {
            $_obfuscate_4Honjwÿÿ = ":".$_SERVER['SERVER_PORT'];
        }
        $_obfuscate_Il8i = "http://".$_SERVER['SERVER_NAME'].$_obfuscate_4Honjwÿÿ."/index.php?action=commonJob&act=outsidelink&key=".$_obfuscate_6RYLWQÿÿ['randomnum'];
        return $_obfuscate_Il8i;
    }

    private function __formatFrom( $_obfuscate_vholQÿÿ, $_obfuscate_6RYLWQÿÿ )
    {
        switch ( $_obfuscate_vholQÿÿ )
        {
        case "reportWf" :
            $_obfuscate_xs33Yt_k['path'] = CNOA_PATH_FILE."/common/report/temp/".$_obfuscate_6RYLWQÿÿ;
            $_obfuscate_xs33Yt_k['ext'] = "xlsx";
            return $_obfuscate_xs33Yt_k;
        case "wfExport" :
            $_obfuscate_xs33Yt_k['path'] = CNOA_PATH_FILE."/common/temp/".$_obfuscate_6RYLWQÿÿ;
            $_obfuscate_xs33Yt_k['ext'] = "jpg";
            return $_obfuscate_xs33Yt_k;
        }
        return FALSE;
    }

    private function _delete( )
    {
        set_time_limit( 0 );
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_iuzS = $CNOA_SESSION->get( "DID" );
        $_obfuscate_1jUa = $_POST['ids'];
        $_obfuscate_1jUa = json_decode( $_obfuscate_1jUa, TRUE );
        $_obfuscate_LeS8hwÿÿ = getpar( $_POST, "type", "" );
        $_obfuscate_1AhknqdeNNto = $CNOA_SESSION->get( "JOBTYPE" );
        if ( !is_array( $_obfuscate_1jUa ) )
        {
            msg::callback( FALSE, lang( "error" ) );
        }
        if ( count( $_obfuscate_1jUa ) < 1 )
        {
            msg::callback( FALSE, lang( "error" ) );
        }
        $_obfuscate_z8FCmw3OhDYÿ = 0;
        if ( $_obfuscate_1jUa[0]['type'] == "f" )
        {
            $_obfuscate_z8FCmw3OhDYÿ = $CNOA_DB->db_getfield( "fid", $this->table_public_file, "WHERE `fileid`='".$_obfuscate_1jUa[0]['id']."'" );
        }
        else
        {
            $_obfuscate_z8FCmw3OhDYÿ = $CNOA_DB->db_getfield( "pid", $this->table_public, "WHERE `fid`='".$_obfuscate_1jUa[0]['id']."'" );
        }
        $_obfuscate_Fwl1 = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$_obfuscate_z8FCmw3OhDYÿ."'" );
        $_obfuscate_ljWwbP9jSVP1 = $this->__getDirPermit( $_obfuscate_Fwl1 );
        if ( $_obfuscate_ljWwbP9jSVP1['dl'] != "1" )
        {
            msg::callback( FALSE, lang( "noPermitOpt" ) );
        }
        foreach ( $_obfuscate_1jUa as $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['type'] == "f" )
            {
                $this->__deleteFile( $_obfuscate_6Aÿÿ['id'] );
            }
            else if ( $_obfuscate_6Aÿÿ['type'] == "d" )
            {
                $this->__deleteDir( $_obfuscate_6Aÿÿ['id'] );
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function __deleteDir( $_obfuscate_Ce9h )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_Fwl1 = $CNOA_DB->db_getone( array( "name", "path2" ), $this->table_public, "WHERE `fid` = '".$_obfuscate_Ce9h."'" );
        $_obfuscate_uFOIOgQÿ = $_obfuscate_Fwl1['path2'];
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "fid" ), $this->table_public, "WHERE `path2` LIKE '".$_obfuscate_uFOIOgQÿ."-%' OR `path2`='{$_obfuscate_uFOIOgQÿ}'" );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_jgÿÿ = array( 0 );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_jgÿÿ[] = $_obfuscate_6Aÿÿ['fid'];
        }
        $_obfuscate_I9APXAkÿ = $CNOA_DB->db_select( "*", $this->table_public_file, "WHERE `fid` IN (".implode( ",", $_obfuscate_jgÿÿ ).")" );
        if ( !is_array( $_obfuscate_I9APXAkÿ ) )
        {
            $_obfuscate_I9APXAkÿ = array( );
        }
        foreach ( $_obfuscate_I9APXAkÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $this->__deleteFile( $_obfuscate_6Aÿÿ['fileid'], FALSE );
        }
        $CNOA_DB->db_delete( $this->table_public_file, "WHERE `fid` IN (".implode( ",", $_obfuscate_jgÿÿ ).") " );
        $CNOA_DB->db_delete( $this->table_public, "WHERE `fid` IN (".implode( ",", $_obfuscate_jgÿÿ ).") " );
        $CNOA_DB->db_delete( $this->table_public_permit_p, "WHERE `fid` IN (".implode( ",", $_obfuscate_jgÿÿ ).") " );
        $CNOA_DB->db_delete( $this->table_public_permit_s, "WHERE `fid` IN (".implode( ",", $_obfuscate_jgÿÿ ).") " );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 30, $_obfuscate_Fwl1['name'], lang( "folder" ) );
    }

    private function __deleteFile( $_obfuscate_0W8ÿ, $_obfuscate_rSzowuI = TRUE )
    {
        global $CNOA_DB;
        $_obfuscate_6RYLWQÿÿ = $CNOA_DB->db_getone( "*", $this->table_public_file, "WHERE `fileid` = '".$_obfuscate_0W8ÿ."' " );
        $_obfuscate_Thgÿ = $CNOA_DB->db_select( "*", $this->table_versions, "WHERE `fileid` = '".$_obfuscate_0W8ÿ."' " );
        if ( !is_array( $_obfuscate_Thgÿ ) )
        {
            $_obfuscate_Thgÿ = array( );
        }
        foreach ( $_obfuscate_Thgÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_p9iS3rrNwQQÿ = CNOA_PATH_FILE.( "/common/disk/public/".$_obfuscate_6Aÿÿ['storepath']."/{$_obfuscate_6Aÿÿ['storename']}" );
            @unlink( $_obfuscate_p9iS3rrNwQQÿ );
            $_obfuscate_wFQDAd5DBKjI = CNOA_PATH_FILE.( "/common/disk/public/".$_obfuscate_6Aÿÿ['storepath']."/" ).$_obfuscate_6Aÿÿ['thumb'].".".$_obfuscate_6Aÿÿ['ext'];
            @unlink( $_obfuscate_wFQDAd5DBKjI );
            $_obfuscate_wFQDAd5DBKjI = CNOA_PATH_FILE.( "/common/disk/public/".$_obfuscate_6Aÿÿ['storepath']."/" ).$_obfuscate_6Aÿÿ['thumb'].".mid.".$_obfuscate_6Aÿÿ['ext'];
            @unlink( $_obfuscate_wFQDAd5DBKjI );
        }
        $CNOA_DB->db_delete( $this->table_public_file, "WHERE `fileid`='".$_obfuscate_0W8ÿ."'" );
        $CNOA_DB->db_delete( $this->table_versions, "WHERE `fileid`='".$_obfuscate_0W8ÿ."'" );
        $_obfuscate_p9iS3rrNwQQÿ = CNOA_PATH_FILE.( "/common/disk/public/".$_obfuscate_6RYLWQÿÿ['storepath']."/{$_obfuscate_6RYLWQÿÿ['storename']}" );
        @unlink( $_obfuscate_p9iS3rrNwQQÿ );
        $_obfuscate_wFQDAd5DBKjI = CNOA_PATH_FILE.( "/common/disk/public/".$_obfuscate_6RYLWQÿÿ['storepath']."/" ).$_obfuscate_6RYLWQÿÿ['thumb'].".".$_obfuscate_6RYLWQÿÿ['ext'];
        @unlink( $_obfuscate_wFQDAd5DBKjI );
        $_obfuscate_wFQDAd5DBKjI = CNOA_PATH_FILE.( "/common/disk/public/".$_obfuscate_6RYLWQÿÿ['storepath']."/" ).$_obfuscate_6RYLWQÿÿ['thumb'].".mid.".$_obfuscate_6RYLWQÿÿ['ext'];
        @unlink( $_obfuscate_wFQDAd5DBKjI );
        if ( !empty( $_obfuscate_6RYLWQÿÿ['sharedpeopleUid'] ) && !empty( $_obfuscate_6RYLWQÿÿ['sharedDeptIds'] ) )
        {
            $_obfuscate_xs33Yt_k = $CNOA_DB->db_select( array( "sharefrom", "storename" ), $this->table_list, "WHERE `fromfileid`='".$_obfuscate_0W8ÿ."'" );
            if ( !empty( $_obfuscate_xs33Yt_k ) )
            {
                $CNOA_DB->db_delete( $this->table_list, "WHERE `fromfileid`='".$_obfuscate_0W8ÿ."'" );
                $_obfuscate_p9iS3rrNwQQÿ = CNOA_PATH_FILE.( "/common/disk/user/".$_obfuscate_xs33Yt_k[0]['sharefrom']."/{$_obfuscate_xs33Yt_k[0]['storename']}" );
                @unlink( $_obfuscate_p9iS3rrNwQQÿ );
            }
        }
        if ( $_obfuscate_rSzowuI )
        {
            app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 30, "{$_obfuscate_6RYLWQÿÿ['name']}\\.{$_obfuscate_6RYLWQÿÿ['ext']}", lang( "file" ) );
        }
    }

    private function _getAllUserListsInPermitDeptTree( )
    {
        $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
        $_obfuscate_phKp89pDgwQÿ = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
        echo json_encode( $_obfuscate_phKp89pDgwQÿ );
        exit( );
    }

    private function _permitListByM( )
    {
        global $CNOA_DB;
        $_obfuscate_Ce9h = getpar( $_GET, "fid", 0 );
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_PVLK5jra = array( 0 );
        $_obfuscate_434k0Jj9 = $CNOA_DB->db_getone( array( "extend", "path" ), $this->table_public, "WHERE `fid` = '".$_obfuscate_Ce9h."'" );
        if ( $_obfuscate_434k0Jj9['extend'] == 1 )
        {
            $_obfuscate_tuurdYM93wÿÿ = explode( "-", $_obfuscate_434k0Jj9['path'] );
            unset( $_obfuscate_tuurdYM93wÿÿ[0] );
            if ( 0 < count( $_obfuscate_tuurdYM93wÿÿ ) )
            {
                $_obfuscate_gdh9zQIRsWkÿ = $CNOA_DB->db_select( array( "fid", "extend" ), $this->table_public, "WHERE `fid` IN (".implode( ",", $_obfuscate_tuurdYM93wÿÿ ).") ORDER BY `fid` DESC" );
                if ( !is_array( $_obfuscate_gdh9zQIRsWkÿ ) )
                {
                    $_obfuscate_gdh9zQIRsWkÿ = array( );
                }
                $_obfuscate_ViKf3gÿÿ = array( 0 );
                foreach ( $_obfuscate_gdh9zQIRsWkÿ as $_obfuscate_6Aÿÿ )
                {
                    if ( !( $_obfuscate_6Aÿÿ['extend'] == "1" ) )
                    {
                        break;
                    }
                    $_obfuscate_ViKf3gÿÿ[] = $_obfuscate_6Aÿÿ['fid'];
                }
                $_obfuscate_P2FlQKPbkCzM5BBRZpcÿ = $CNOA_DB->db_select( "*", $this->table_public_permit_p, "WHERE `fid` IN (".implode( ",", $_obfuscate_ViKf3gÿÿ ).") " );
                if ( !is_array( $_obfuscate_P2FlQKPbkCzM5BBRZpcÿ ) )
                {
                    $_obfuscate_P2FlQKPbkCzM5BBRZpcÿ = array( );
                }
                $_obfuscate_m2Gt6Iÿ = array( );
                foreach ( $_obfuscate_P2FlQKPbkCzM5BBRZpcÿ as $_obfuscate_6Aÿÿ )
                {
                    if ( empty( $_obfuscate_m2Gt6Iÿ[$_obfuscate_6Aÿÿ['uid']] ) )
                    {
                        $_obfuscate_m2Gt6Iÿ[$_obfuscate_6Aÿÿ['uid']] = $_obfuscate_6Aÿÿ;
                    }
                    else
                    {
                        foreach ( array( "dl", "sh", "up", "dt", "ed", "vi", "mgr" ) as $_obfuscate_I_Xm )
                        {
                            if ( $_obfuscate_6Aÿÿ[$_obfuscate_I_Xm] == 1 )
                            {
                                $_obfuscate_m2Gt6Iÿ[$_obfuscate_6Aÿÿ['uid']][$_obfuscate_I_Xm] = 1;
                            }
                        }
                    }
                }
                foreach ( $_obfuscate_m2Gt6Iÿ as $_obfuscate_6Aÿÿ )
                {
                    $_obfuscate_PVLK5jra[] = $_obfuscate_6Aÿÿ['uid'];
                    $_obfuscate_6Aÿÿ['extend'] = 1;
                    $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_6Aÿÿ;
                }
            }
        }
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->table_public_permit_p, "WHERE `fid` = '".$_obfuscate_Ce9h."' " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_PVLK5jra[] = $_obfuscate_6Aÿÿ['uid'];
            $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ];
        }
        $_obfuscate_r5Ne4piDbMÿ = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_PVLK5jra );
        foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['name'] = $_obfuscate_r5Ne4piDbMÿ[$_obfuscate_6Aÿÿ['uid']]['truename'];
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        $_obfuscate_NlQÿ->extend = $_obfuscate_434k0Jj9['extend'];
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _permitListByS( )
    {
        global $CNOA_DB;
        $_obfuscate_Ce9h = getpar( $_GET, "fid", 0 );
        $_obfuscate_KFzuhQ5t = array( 0 );
        $_obfuscate_434k0Jj9 = $CNOA_DB->db_getone( array( "extend", "path" ), $this->table_public, "WHERE `fid` = '".$_obfuscate_Ce9h."'" );
        $_obfuscate_6RYLWQÿÿ = array( );
        if ( $_obfuscate_434k0Jj9['extend'] == 1 )
        {
            $_obfuscate_tuurdYM93wÿÿ = explode( "-", $_obfuscate_434k0Jj9['path'] );
            unset( $_obfuscate_tuurdYM93wÿÿ[0] );
            if ( 0 < count( $_obfuscate_tuurdYM93wÿÿ ) )
            {
                $_obfuscate_gdh9zQIRsWkÿ = $CNOA_DB->db_select( array( "fid", "extend" ), $this->table_public, "WHERE `fid` IN (".implode( ",", $_obfuscate_tuurdYM93wÿÿ ).") ORDER BY `fid` DESC" );
                if ( !is_array( $_obfuscate_gdh9zQIRsWkÿ ) )
                {
                    $_obfuscate_gdh9zQIRsWkÿ = array( );
                }
                $_obfuscate_ViKf3gÿÿ = array( 0 );
                foreach ( $_obfuscate_gdh9zQIRsWkÿ as $_obfuscate_6Aÿÿ )
                {
                    if ( !( $_obfuscate_6Aÿÿ['extend'] == "1" ) )
                    {
                        break;
                    }
                    $_obfuscate_ViKf3gÿÿ[] = $_obfuscate_6Aÿÿ['fid'];
                }
                $_obfuscate_P2FlQKPbkCzM5BBRZpcÿ = $CNOA_DB->db_select( "*", $this->table_public_permit_s, "WHERE `fid` IN (".implode( ",", $_obfuscate_ViKf3gÿÿ ).") " );
                if ( !is_array( $_obfuscate_P2FlQKPbkCzM5BBRZpcÿ ) )
                {
                    $_obfuscate_P2FlQKPbkCzM5BBRZpcÿ = array( );
                }
                $_obfuscate_m2Gt6Iÿ = array( );
                foreach ( $_obfuscate_P2FlQKPbkCzM5BBRZpcÿ as $_obfuscate_6Aÿÿ )
                {
                    if ( empty( $_obfuscate_m2Gt6Iÿ[$_obfuscate_6Aÿÿ['did']] ) )
                    {
                        $_obfuscate_m2Gt6Iÿ[$_obfuscate_6Aÿÿ['did']] = $_obfuscate_6Aÿÿ;
                    }
                    else
                    {
                        foreach ( array( "dl", "sh", "up", "dt", "ed", "vi", "mgr" ) as $_obfuscate_I_Xm )
                        {
                            if ( $_obfuscate_6Aÿÿ[$_obfuscate_I_Xm] == 1 )
                            {
                                $_obfuscate_m2Gt6Iÿ[$_obfuscate_6Aÿÿ['did']][$_obfuscate_I_Xm] = 1;
                            }
                        }
                    }
                }
                foreach ( $_obfuscate_m2Gt6Iÿ as $_obfuscate_6Aÿÿ )
                {
                    $_obfuscate_KFzuhQ5t[] = $_obfuscate_6Aÿÿ['did'];
                    $_obfuscate_6Aÿÿ['extend'] = 1;
                    $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_6Aÿÿ;
                }
            }
        }
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->table_public_permit_s, "WHERE `fid` = '".$_obfuscate_Ce9h."' " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_KFzuhQ5t[] = $_obfuscate_6Aÿÿ['did'];
            $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ];
        }
        $_obfuscate_XRvPgP5V0t4ÿ = app::loadapp( "main", "struct" )->api_getNamesByIds( $_obfuscate_KFzuhQ5t );
        foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_5wÿÿ]['name'] = $_obfuscate_XRvPgP5V0t4ÿ[$_obfuscate_6Aÿÿ['did']];
        }
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_6RYLWQÿÿ;
        $_obfuscate_NlQÿ->extend = $_obfuscate_434k0Jj9['extend'];
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    private function _addPermitM( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate__eqrEQÿÿ = getpar( $_POST, "uid", 0 );
        $_obfuscate_Ce9h = getpar( $_POST, "fid", 0 );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        if ( empty( $_obfuscate_Ce9h ) )
        {
            msg::callback( FALSE, lang( "optFail" ) );
        }
        $_obfuscate_pp9pYwÿÿ = $CNOA_DB->db_getfield( "path", $this->table_public, "WHERE `fid` = '".$_obfuscate_Ce9h."' " );
        $_obfuscate_pp9pYwÿÿ = $_obfuscate_pp9pYwÿÿ."-".$_obfuscate_Ce9h;
        $_obfuscate_PVLK5jra = explode( ",", $_obfuscate__eqrEQÿÿ );
        foreach ( $_obfuscate_PVLK5jra as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( !( $_obfuscate_6Aÿÿ == $_obfuscate_7Ri3 ) )
            {
                $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->table_public_permit_p, "WHERE `fid` = '".$_obfuscate_Ce9h."' AND `uid` = '{$_obfuscate_6Aÿÿ}' " );
                if ( !empty( $_obfuscate_Ybai ) )
                {
                }
                else
                {
                    $CNOA_DB->db_insert( array(
                        "uid" => $_obfuscate_6Aÿÿ,
                        "fid" => $_obfuscate_Ce9h,
                        "path" => $_obfuscate_pp9pYwÿÿ
                    ), $this->table_public_permit_p );
                }
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _addPermitS( )
    {
        global $CNOA_DB;
        $_obfuscate_iuzS = getpar( $_POST, "did", 0 );
        $_obfuscate_Ce9h = getpar( $_POST, "fid", 0 );
        $_obfuscate_KFzuhQ5t = explode( ",", $_obfuscate_iuzS );
        if ( empty( $_obfuscate_Ce9h ) )
        {
            msg::callback( FALSE, lang( "optFail" ) );
        }
        $_obfuscate_pp9pYwÿÿ = $CNOA_DB->db_getfield( "path", $this->table_public, "WHERE `fid` = '".$_obfuscate_Ce9h."' " );
        $_obfuscate_pp9pYwÿÿ = $_obfuscate_pp9pYwÿÿ."-".$_obfuscate_Ce9h;
        foreach ( $_obfuscate_KFzuhQ5t as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_Ybai = $CNOA_DB->db_getcount( $this->table_public_permit_s, "WHERE `fid` = '".$_obfuscate_Ce9h."' AND `did` = '{$_obfuscate_6Aÿÿ}' " );
            if ( empty( $_obfuscate_Ybai ) )
            {
                $CNOA_DB->db_insert( array(
                    "did" => $_obfuscate_6Aÿÿ,
                    "fid" => $_obfuscate_Ce9h,
                    "path" => $_obfuscate_pp9pYwÿÿ
                ), $this->table_public_permit_s );
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _addPermitDataM( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_Ce9h = getpar( $_POST, "fid", 0 );
        if ( empty( $_obfuscate_Ce9h ) )
        {
            msg::callback( FALSE, lang( "optFail" ) );
        }
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_iuzS = $CNOA_SESSION->get( "DID" );
        $_obfuscate_Ngl5tw08BAÿÿ = $CNOA_SESSION->get( "JOBTYPE" );
        $_obfuscate_Fwl1 = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$_obfuscate_Ce9h."'" );
        $_obfuscate_ljWwbP9jSVP1 = $this->__getDirPermit( $_obfuscate_Fwl1 );
        if ( $_obfuscate_ljWwbP9jSVP1['mgr'] != "1" )
        {
            msg::callback( FALSE, lang( "noPermitOpt" ) );
        }
        $_obfuscate_6RYLWQÿÿ = getpar( $_POST, "mem", array( ) );
        if ( empty( $_obfuscate_6RYLWQÿÿ ) )
        {
            $CNOA_DB->db_delete( $this->table_public_permit_p, "WHERE `fid` = '".$_obfuscate_Ce9h."' " );
            msg::callback( TRUE, lang( "successopt" ) );
        }
        $_obfuscate_T7Mk4ltM = array( );
        foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['mgr'] == 1 )
            {
                $_obfuscate_T7Mk4ltM['mgr'] = 1;
                $_obfuscate_T7Mk4ltM['vi'] = 1;
                $_obfuscate_T7Mk4ltM['ed'] = 1;
                $_obfuscate_T7Mk4ltM['dl'] = 1;
                $_obfuscate_T7Mk4ltM['sh'] = 1;
                $_obfuscate_T7Mk4ltM['mv'] = 1;
                $_obfuscate_T7Mk4ltM['pr'] = 1;
                $_obfuscate_T7Mk4ltM['up'] = 1;
                $_obfuscate_T7Mk4ltM['dt'] = 1;
            }
            else
            {
                $_obfuscate_T7Mk4ltM['vi'] = $_obfuscate_6Aÿÿ['vi'];
                $_obfuscate_T7Mk4ltM['ed'] = $_obfuscate_6Aÿÿ['ed'];
                $_obfuscate_T7Mk4ltM['dl'] = $_obfuscate_6Aÿÿ['dl'];
                $_obfuscate_T7Mk4ltM['mv'] = $_obfuscate_6Aÿÿ['mv'];
                $_obfuscate_T7Mk4ltM['pr'] = $_obfuscate_6Aÿÿ['pr'];
                $_obfuscate_T7Mk4ltM['sh'] = $_obfuscate_6Aÿÿ['sh'];
                $_obfuscate_T7Mk4ltM['up'] = $_obfuscate_6Aÿÿ['up'];
                $_obfuscate_T7Mk4ltM['dt'] = $_obfuscate_6Aÿÿ['dt'];
                $_obfuscate_T7Mk4ltM['mgr'] = 0;
            }
            $_obfuscate_HFMJFhFd3_bj2xQO7IZrVAÿÿ[] = $_obfuscate_5wÿÿ;
            $CNOA_DB->db_update( $_obfuscate_T7Mk4ltM, $this->table_public_permit_p, "WHERE `pid` = '".$_obfuscate_5wÿÿ."' AND `fid` = '{$_obfuscate_Ce9h}' " );
        }
        if ( !empty( $_obfuscate_HFMJFhFd3_bj2xQO7IZrVAÿÿ ) )
        {
            $CNOA_DB->db_delete( $this->table_public_permit_p, "WHERE `fid` = '".$_obfuscate_Ce9h."' AND `pid` NOT IN (".implode( ",", $_obfuscate_HFMJFhFd3_bj2xQO7IZrVAÿÿ ).") " );
        }
        $_obfuscate_pp9pYwÿÿ = $CNOA_DB->db_getfield( "path2", $this->table_public, "WHERE `fid`='".$_obfuscate_Ce9h."'" );
        $_obfuscate_Y_ZYaWsÿ = explode( "-", $_obfuscate_pp9pYwÿÿ );
        $_obfuscate_IRFhnYwÿ = "WHERE `fid` IN (";
        foreach ( $_obfuscate_Y_ZYaWsÿ as $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ != 0 )
            {
                $_obfuscate_IRFhnYwÿ .= $_obfuscate_6Aÿÿ.",";
            }
        }
        $_obfuscate_IRFhnYwÿ = substr_replace( $_obfuscate_IRFhnYwÿ, ")", -1 );
        $_obfuscate_QuaGFqxRSLoF = $CNOA_DB->db_select( array( "name" ), $this->table_public, $_obfuscate_IRFhnYwÿ );
        $_obfuscate_Iesÿ = "";
        $_obfuscate_7wÿÿ = 0;
        for ( ; $_obfuscate_7wÿÿ < count( $_obfuscate_QuaGFqxRSLoF ); ++$_obfuscate_7wÿÿ )
        {
            $_obfuscate_Iesÿ .= $_obfuscate_QuaGFqxRSLoF[$_obfuscate_7wÿÿ]['name']."/";
        }
        $_obfuscate_Iesÿ = substr_replace( $_obfuscate_Iesÿ, "", -1 );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 30, lang( "folderPermitOfUser", $_obfuscate_Iesÿ ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _addPermitDataS( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_Ce9h = getpar( $_POST, "fid", 0 );
        if ( empty( $_obfuscate_Ce9h ) )
        {
            msg::callback( FALSE, lang( "optFail" ) );
        }
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_iuzS = $CNOA_SESSION->get( "DID" );
        $_obfuscate_Fwl1 = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$_obfuscate_Ce9h."'" );
        $_obfuscate_ljWwbP9jSVP1 = $this->__getDirPermit( $_obfuscate_Fwl1 );
        if ( $_obfuscate_ljWwbP9jSVP1['mgr'] != "1" )
        {
            msg::callback( FALSE, lang( "noPermitOpt" ) );
        }
        $_obfuscate_6RYLWQÿÿ = getpar( $_POST, "dept", array( ) );
        $_obfuscate_T7Mk4ltM = array( );
        foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['mgr'] == 1 )
            {
                $_obfuscate_T7Mk4ltM['mgr'] = 1;
                $_obfuscate_T7Mk4ltM['vi'] = 1;
                $_obfuscate_T7Mk4ltM['ed'] = 1;
                $_obfuscate_T7Mk4ltM['dl'] = 1;
                $_obfuscate_T7Mk4ltM['sh'] = 1;
                $_obfuscate_T7Mk4ltM['mv'] = 1;
                $_obfuscate_T7Mk4ltM['pr'] = 1;
                $_obfuscate_T7Mk4ltM['up'] = 1;
                $_obfuscate_T7Mk4ltM['dt'] = 1;
            }
            else
            {
                $_obfuscate_T7Mk4ltM['vi'] = $_obfuscate_6Aÿÿ['vi'];
                $_obfuscate_T7Mk4ltM['ed'] = $_obfuscate_6Aÿÿ['ed'];
                $_obfuscate_T7Mk4ltM['dl'] = $_obfuscate_6Aÿÿ['dl'];
                $_obfuscate_T7Mk4ltM['sh'] = $_obfuscate_6Aÿÿ['sh'];
                $_obfuscate_T7Mk4ltM['mv'] = $_obfuscate_6Aÿÿ['mv'];
                $_obfuscate_T7Mk4ltM['pr'] = $_obfuscate_6Aÿÿ['pr'];
                $_obfuscate_T7Mk4ltM['up'] = $_obfuscate_6Aÿÿ['up'];
                $_obfuscate_T7Mk4ltM['dt'] = $_obfuscate_6Aÿÿ['dt'];
                $_obfuscate_T7Mk4ltM['mgr'] = 0;
            }
            $_obfuscate_HFMJFhFd3_bj2xQO7IZrVAÿÿ[] = $_obfuscate_5wÿÿ;
            $CNOA_DB->db_update( $_obfuscate_T7Mk4ltM, $this->table_public_permit_s, "WHERE `sid` = '".$_obfuscate_5wÿÿ."' AND `fid` = '{$_obfuscate_Ce9h}' " );
        }
        $CNOA_DB->db_delete( $this->table_public_permit_s, "WHERE `fid` = '".$_obfuscate_Ce9h."' AND `sid` NOT IN (".implode( ",", $_obfuscate_HFMJFhFd3_bj2xQO7IZrVAÿÿ ).") " );
        $_obfuscate_pp9pYwÿÿ = $CNOA_DB->db_getfield( "path2", $this->table_public, "WHERE `fid`='".$_obfuscate_Ce9h."'" );
        $_obfuscate_Y_ZYaWsÿ = explode( "-", $_obfuscate_pp9pYwÿÿ );
        $_obfuscate_IRFhnYwÿ = "WHERE `fid` IN (";
        foreach ( $_obfuscate_Y_ZYaWsÿ as $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ != 0 )
            {
                $_obfuscate_IRFhnYwÿ .= $_obfuscate_6Aÿÿ.",";
            }
        }
        $_obfuscate_IRFhnYwÿ = substr_replace( $_obfuscate_IRFhnYwÿ, ")", -1 );
        $_obfuscate_QuaGFqxRSLoF = $CNOA_DB->db_select( array( "name" ), $this->table_public, $_obfuscate_IRFhnYwÿ );
        $_obfuscate_Iesÿ = "";
        $_obfuscate_7wÿÿ = 0;
        for ( ; $_obfuscate_7wÿÿ < count( $_obfuscate_QuaGFqxRSLoF ); ++$_obfuscate_7wÿÿ )
        {
            $_obfuscate_Iesÿ .= $_obfuscate_QuaGFqxRSLoF[$_obfuscate_7wÿÿ]['name']."/";
        }
        $_obfuscate_Iesÿ = substr_replace( $_obfuscate_Iesÿ, "", -1 );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 30, lang( "folderOfDeptPermit", $_obfuscate_Iesÿ ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _deletePermitM( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_fdpE = getpar( $_GET, "pid", 0 );
        $_obfuscate_Ce9h = getpar( $_POST, "fid", 0 );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_iuzS = $CNOA_SESSION->get( "DID" );
        $_obfuscate_Fwl1 = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$_obfuscate_Ce9h."'" );
        $_obfuscate_ljWwbP9jSVP1 = $this->__getDirPermit( $_obfuscate_Fwl1 );
        if ( $_obfuscate_ljWwbP9jSVP1['mgr'] != "1" )
        {
            msg::callback( FALSE, lang( "noPermitOpt" ) );
        }
        $_obfuscate_pp9pYwÿÿ = $CNOA_DB->db_getfield( "path", $this->table_public_permit_p, "WHERE `pid`='".$_obfuscate_fdpE."'" );
        $CNOA_DB->db_delete( $this->table_public_permit_p, "WHERE `pid` = '".$_obfuscate_fdpE."' " );
        $_obfuscate_Y_ZYaWsÿ = explode( "-", $_obfuscate_pp9pYwÿÿ );
        $_obfuscate_IRFhnYwÿ = "WHERE `fid` IN (";
        foreach ( $_obfuscate_Y_ZYaWsÿ as $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ != 0 )
            {
                $_obfuscate_IRFhnYwÿ .= $_obfuscate_6Aÿÿ.",";
            }
        }
        $_obfuscate_IRFhnYwÿ = substr_replace( $_obfuscate_IRFhnYwÿ, ")", -1 );
        $_obfuscate_QuaGFqxRSLoF = $CNOA_DB->db_select( array( "name" ), $this->table_public, $_obfuscate_IRFhnYwÿ );
        $_obfuscate_Iesÿ = "";
        $_obfuscate_7wÿÿ = 0;
        for ( ; $_obfuscate_7wÿÿ < count( $_obfuscate_QuaGFqxRSLoF ); ++$_obfuscate_7wÿÿ )
        {
            $_obfuscate_Iesÿ .= $_obfuscate_QuaGFqxRSLoF[$_obfuscate_7wÿÿ]['name']."/";
        }
        $_obfuscate_Iesÿ = substr_replace( $_obfuscate_Iesÿ, "", -1 );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 30, lang( "folderPermitOfUser", $_obfuscate_Iesÿ ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _deletePermitS( )
    {
        global $CNOA_DB;
        $_obfuscate_fdpE = getpar( $_GET, "sid", 0 );
        $_obfuscate_Ce9h = getpar( $_POST, "fid", 0 );
        $_obfuscate_Fwl1 = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$_obfuscate_Ce9h."'" );
        $_obfuscate_ljWwbP9jSVP1 = $this->__getDirPermit( $_obfuscate_Fwl1 );
        if ( $_obfuscate_ljWwbP9jSVP1['mgr'] != "1" )
        {
            msg::callback( FALSE, lang( "noPermitOpt" ) );
        }
        $_obfuscate_pp9pYwÿÿ = $CNOA_DB->db_getfield( "path", $this->table_public_permit_s, "WHERE `sid`='".$_obfuscate_fdpE."'" );
        $CNOA_DB->db_delete( $this->table_public_permit_s, "WHERE `sid` = '".$_obfuscate_fdpE."' " );
        $_obfuscate_Y_ZYaWsÿ = explode( "-", $_obfuscate_pp9pYwÿÿ );
        $_obfuscate_IRFhnYwÿ = "WHERE `fid` IN (";
        foreach ( $_obfuscate_Y_ZYaWsÿ as $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ != 0 )
            {
                $_obfuscate_IRFhnYwÿ .= $_obfuscate_6Aÿÿ.",";
            }
        }
        $_obfuscate_IRFhnYwÿ = substr_replace( $_obfuscate_IRFhnYwÿ, ")", -1 );
        $_obfuscate_QuaGFqxRSLoF = $CNOA_DB->db_select( array( "name" ), $this->table_public, $_obfuscate_IRFhnYwÿ );
        $_obfuscate_Iesÿ = "";
        $_obfuscate_7wÿÿ = 0;
        for ( ; $_obfuscate_7wÿÿ < count( $_obfuscate_QuaGFqxRSLoF ); ++$_obfuscate_7wÿÿ )
        {
            $_obfuscate_Iesÿ .= $_obfuscate_QuaGFqxRSLoF[$_obfuscate_7wÿÿ]['name']."/";
        }
        $_obfuscate_Iesÿ = substr_replace( $_obfuscate_Iesÿ, "", -1 );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 30, lang( "folderOfDeptPermit", $_obfuscate_Iesÿ ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _deleteEmptyPermit( )
    {
        global $CNOA_DB;
        $_obfuscate_Ce9h = getpar( $_POST, "fid", 0 );
        $CNOA_DB->db_delete( $this->table_public_permit_p, "WHERE `fid` = ".$_obfuscate_Ce9h." AND `ed` = 0 AND `vi` = 0 AND `dl` = 0 AND `up` = 0 AND `dt` = 0 AND `mv` = 0 AND `pr` = 0 " );
        $CNOA_DB->db_delete( $this->table_public_permit_s, "WHERE `fid` = ".$_obfuscate_Ce9h." AND `ed` = 0 AND `vi` = 0 AND `dl` = 0 AND `up` = 0 AND `dt` = 0 AND `mv` = 0 AND `pr` = 0 " );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _doShare( )
    {
        global $CNOA_DB;
        $_obfuscate_Ce9h = intval( getpar( $_POST, "fid", 0 ) );
        $_obfuscate_iTGbas0n = json_decode( stripslashes( $_POST['data_p'] ), TRUE );
        $_obfuscate_PReztkZZ = json_decode( stripslashes( $_POST['data_s'] ), TRUE );
        if ( !is_array( $_obfuscate_iTGbas0n ) )
        {
            $_obfuscate_iTGbas0n = array( );
        }
        if ( !is_array( $_obfuscate_PReztkZZ ) )
        {
            $_obfuscate_PReztkZZ = array( );
        }
        if ( $_obfuscate_Ce9h == 0 )
        {
            msg::callback( FALSE, lang( "goWrong" ) );
        }
        if ( count( $_obfuscate_iTGbas0n ) <= 0 && count( $_obfuscate_PReztkZZ ) <= 0 )
        {
            $CNOA_DB->db_update( array( "people" => "", "dept" => "" ), $this->table_public, "WHERE `fid`='".$_obfuscate_Ce9h."' AND `type`='d'" );
            msg::callback( TRUE, lang( "optSucessAllCanView" ) );
        }
        $_obfuscate__eqrEQÿÿ = array( );
        foreach ( $_obfuscate_iTGbas0n as $_obfuscate_6cgÿ )
        {
            $_obfuscate__eqrEQÿÿ[] = $_obfuscate_6cgÿ['uid'];
        }
        $_obfuscate_6RYLWQÿÿ = array( );
        if ( 0 < count( $_obfuscate_iTGbas0n ) )
        {
            $_obfuscate_6RYLWQÿÿ['people'] = addslashes( json_encode( array(
                "uids" => $_obfuscate__eqrEQÿÿ,
                "data" => $_obfuscate_iTGbas0n
            ) ) );
        }
        else
        {
            $_obfuscate_6RYLWQÿÿ['people'] = "";
        }
        if ( 0 < count( $_obfuscate_PReztkZZ ) )
        {
            $_obfuscate_6RYLWQÿÿ['dept'] = addslashes( json_encode( $_obfuscate_PReztkZZ ) );
        }
        else
        {
            $_obfuscate_6RYLWQÿÿ['dept'] = "";
        }
        $CNOA_DB->db_update( $_obfuscate_6RYLWQÿÿ, $this->table_public, "WHERE `fid`='".$_obfuscate_Ce9h."' AND `type`='d'" );
        msg::callback( TRUE, lang( "successopt" ) );
        exit( );
    }

    private function _getShareData( )
    {
        global $CNOA_DB;
        $_obfuscate_Ce9h = intval( getpar( $_POST, "fid", 0 ) );
        $_obfuscate_6RYLWQÿÿ = $CNOA_DB->db_getone( array( "people", "dept" ), $this->table_public, "WHERE `fid`='".$_obfuscate_Ce9h."' AND `type`='d'" );
        if ( $_obfuscate_6RYLWQÿÿ['people'] == NULL )
        {
            $_obfuscate_6RYLWQÿÿ['people'] = "[]";
        }
        if ( $_obfuscate_6RYLWQÿÿ['dept'] == NULL )
        {
            $_obfuscate_6RYLWQÿÿ['dept'] = "[]";
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = array(
            "data_p" => json_decode( $_obfuscate_6RYLWQÿÿ['people'], TRUE ),
            "data_s" => json_decode( $_obfuscate_6RYLWQÿÿ['dept'], TRUE )
        );
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _doExtend( )
    {
        global $CNOA_DB;
        $_obfuscate_Ce9h = intval( getpar( $_POST, "fid", 0 ) );
        $_obfuscate_hdFqCNib = intval( getpar( $_POST, "extend", 1 ) );
        $_obfuscate_caTTlwÿÿ = intval( getpar( $_POST, "copy", 0 ) );
        $_obfuscate_o5fQ1gÿÿ = $CNOA_DB->db_getone( "*", $this->table_public, "WHERE `fid`='".$_obfuscate_Ce9h."'" );
        if ( !$_obfuscate_o5fQ1gÿÿ )
        {
            msg::callback( FALSE, lang( "nodata" ) );
        }
        if ( $_obfuscate_hdFqCNib == 1 )
        {
            $CNOA_DB->db_delete( $this->table_public_permit_p, "WHERE `fid`='".$_obfuscate_Ce9h."'" );
            $CNOA_DB->db_delete( $this->table_public_permit_s, "WHERE `fid`='".$_obfuscate_Ce9h."'" );
        }
        else if ( $_obfuscate_caTTlwÿÿ == 1 && $_obfuscate_o5fQ1gÿÿ['extend'] == 1 )
        {
            $_obfuscate_tuurdYM93wÿÿ = explode( "-", $_obfuscate_o5fQ1gÿÿ['path'] );
            unset( $_obfuscate_tuurdYM93wÿÿ[0] );
            if ( 0 < count( $_obfuscate_tuurdYM93wÿÿ ) )
            {
                $_obfuscate_gdh9zQIRsWkÿ = $CNOA_DB->db_select( array( "fid", "extend" ), $this->table_public, "WHERE `fid` IN (".implode( ",", $_obfuscate_tuurdYM93wÿÿ ).") ORDER BY `fid` DESC" );
                if ( !is_array( $_obfuscate_gdh9zQIRsWkÿ ) )
                {
                    $_obfuscate_gdh9zQIRsWkÿ = array( );
                }
                $_obfuscate_ViKf3gÿÿ = array( 0 );
                foreach ( $_obfuscate_gdh9zQIRsWkÿ as $_obfuscate_6Aÿÿ )
                {
                    if ( !( $_obfuscate_6Aÿÿ['extend'] == "1" ) )
                    {
                        break;
                    }
                    $_obfuscate_ViKf3gÿÿ[] = $_obfuscate_6Aÿÿ['fid'];
                }
                $_obfuscate_P2FlQKPbkCzM5BBRZpcÿ = $CNOA_DB->db_select( "*", $this->table_public_permit_p, "WHERE `fid` IN (".implode( ",", $_obfuscate_ViKf3gÿÿ ).") " );
                if ( !is_array( $_obfuscate_P2FlQKPbkCzM5BBRZpcÿ ) )
                {
                    $_obfuscate_P2FlQKPbkCzM5BBRZpcÿ = array( );
                }
                $_obfuscate_m2Gt6Iÿ = array( );
                foreach ( $_obfuscate_P2FlQKPbkCzM5BBRZpcÿ as $_obfuscate_6Aÿÿ )
                {
                    if ( empty( $_obfuscate_m2Gt6Iÿ[$_obfuscate_6Aÿÿ['uid']] ) )
                    {
                        $_obfuscate_m2Gt6Iÿ[$_obfuscate_6Aÿÿ['uid']] = $_obfuscate_6Aÿÿ;
                    }
                    else
                    {
                        foreach ( array( "dl", "sh", "mv", "pr", "up", "dt", "ed", "vi", "mgr" ) as $_obfuscate_I_Xm )
                        {
                            if ( $_obfuscate_6Aÿÿ[$_obfuscate_I_Xm] == 1 )
                            {
                                $_obfuscate_m2Gt6Iÿ[$_obfuscate_6Aÿÿ['uid']][$_obfuscate_I_Xm] = 1;
                            }
                        }
                    }
                }
                foreach ( $_obfuscate_m2Gt6Iÿ as $_obfuscate_6Aÿÿ )
                {
                    unset( $_obfuscate_6Aÿÿ['pid'] );
                    $_obfuscate_6Aÿÿ['path'] = $_obfuscate_o5fQ1gÿÿ['path2'];
                    $_obfuscate_6Aÿÿ['fid'] = $_obfuscate_o5fQ1gÿÿ['fid'];
                    $CNOA_DB->db_insert( $_obfuscate_6Aÿÿ, $this->table_public_permit_p );
                }
                $_obfuscate_P2FlQKPbkCzM5BBRZpcÿ = $CNOA_DB->db_select( "*", $this->table_public_permit_s, "WHERE `fid` IN (".implode( ",", $_obfuscate_ViKf3gÿÿ ).") " );
                if ( !is_array( $_obfuscate_P2FlQKPbkCzM5BBRZpcÿ ) )
                {
                    $_obfuscate_P2FlQKPbkCzM5BBRZpcÿ = array( );
                }
                $_obfuscate_m2Gt6Iÿ = array( );
                foreach ( $_obfuscate_P2FlQKPbkCzM5BBRZpcÿ as $_obfuscate_6Aÿÿ )
                {
                    if ( empty( $_obfuscate_m2Gt6Iÿ[$_obfuscate_6Aÿÿ['did']] ) )
                    {
                        $_obfuscate_m2Gt6Iÿ[$_obfuscate_6Aÿÿ['did']] = $_obfuscate_6Aÿÿ;
                    }
                    else
                    {
                        foreach ( array( "dl", "sh", "mv", "pr", "up", "dt", "ed", "vi", "mgr" ) as $_obfuscate_I_Xm )
                        {
                            if ( $_obfuscate_6Aÿÿ[$_obfuscate_I_Xm] == 1 )
                            {
                                $_obfuscate_m2Gt6Iÿ[$_obfuscate_6Aÿÿ['did']][$_obfuscate_I_Xm] = 1;
                            }
                        }
                    }
                }
                foreach ( $_obfuscate_m2Gt6Iÿ as $_obfuscate_6Aÿÿ )
                {
                    unset( $_obfuscate_6Aÿÿ['sid'] );
                    $_obfuscate_6Aÿÿ['path'] = $_obfuscate_o5fQ1gÿÿ['path2'];
                    $_obfuscate_6Aÿÿ['fid'] = $_obfuscate_o5fQ1gÿÿ['fid'];
                    $CNOA_DB->db_insert( $_obfuscate_6Aÿÿ, $this->table_public_permit_s );
                }
                foreach ( $_obfuscate_m2Gt6Iÿ as $_obfuscate_6Aÿÿ )
                {
                    $_obfuscate_PVLK5jra[] = $_obfuscate_6Aÿÿ['uid'];
                    $_obfuscate_6Aÿÿ['extend'] = 1;
                    $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_6Aÿÿ;
                }
            }
        }
        $CNOA_DB->db_update( array(
            "extend" => $_obfuscate_hdFqCNib
        ), $this->table_public, "WHERE `fid`='".$_obfuscate_Ce9h."'" );
        msg::callback( TRUE, lang( "successopt" ) );
        exit( );
    }

    private function _getThumb( )
    {
        global $CNOA_DB;
        $_obfuscate_N9qwQCSc = intval( getpar( $_GET, "fileid" ) );
        $_obfuscate_Ns_JyWSm = getpar( $_GET, "target" );
        $_obfuscate_hUlweQMÿ = $CNOA_DB->db_getone( "*", "user_disk_public_file", "WHERE `fileid`='".$_obfuscate_N9qwQCSc."'" );
        if ( !$_obfuscate_hUlweQMÿ )
        {
            return;
        }
        if ( empty( $_obfuscate_hUlweQMÿ['thumb'] ) )
        {
            $_obfuscate_Iesÿ = CNOA_PATH_FILE.( "/common/disk/public/".$_obfuscate_hUlweQMÿ['storepath']."/" );
            $_obfuscate_ysNkiBIc3pJJ = string::rands( 16 );
            ( );
            $_obfuscate_JD_fILI4TAÿÿ = new picture( );
            $_obfuscate_JD_fILI4TAÿÿ->setSrcImg( $_obfuscate_Iesÿ.$_obfuscate_hUlweQMÿ['storename'] );
            $_obfuscate_JD_fILI4TAÿÿ->setDstImg( $_obfuscate_Iesÿ.$_obfuscate_ysNkiBIc3pJJ.( ".".$_obfuscate_hUlweQMÿ['ext'] ) );
            $_obfuscate_JD_fILI4TAÿÿ->createImg( 90, 90 );
            ( );
            $_obfuscate_JD_fILI4TAÿÿ = new picture( );
            $_obfuscate_JD_fILI4TAÿÿ->setSrcImg( $_obfuscate_Iesÿ.$_obfuscate_hUlweQMÿ['storename'] );
            $_obfuscate_JD_fILI4TAÿÿ->setDstImg( $_obfuscate_Iesÿ.$_obfuscate_ysNkiBIc3pJJ.( ".mid.".$_obfuscate_hUlweQMÿ['ext'] ) );
            $_obfuscate_JD_fILI4TAÿÿ->createImg( 300, 300 );
            if ( file_exists( $_obfuscate_Iesÿ.$_obfuscate_ysNkiBIc3pJJ.( ".".$_obfuscate_hUlweQMÿ['ext'] ) ) )
            {
                $CNOA_DB->db_update( array(
                    "thumb" => $_obfuscate_ysNkiBIc3pJJ
                ), "user_disk_public_file", "WHERE `fileid`='".$_obfuscate_N9qwQCSc."'" );
            }
            if ( $_obfuscate_Ns_JyWSm == "big" )
            {
                $_obfuscate_ysNkiBIc3pJJ = $_obfuscate_hUlweQMÿ['storename'];
            }
            else if ( $_obfuscate_Ns_JyWSm == "middle" )
            {
                $_obfuscate_ysNkiBIc3pJJ = $_obfuscate_hUlweQMÿ['thumb'].( ".mid.".$_obfuscate_hUlweQMÿ['ext'] );
            }
            else
            {
                $_obfuscate_ysNkiBIc3pJJ = $_obfuscate_hUlweQMÿ['storename'].( ".".$_obfuscate_hUlweQMÿ['ext'] );
            }
            header( "content-type: image/jpeg" );
            header( "Location:".$GLOBALS['URL_FILE'].( "/common/disk/public/".$_obfuscate_hUlweQMÿ['storepath']."/" ).$_obfuscate_ysNkiBIc3pJJ );
        }
        else
        {
            if ( $_obfuscate_Ns_JyWSm == "big" )
            {
                $_obfuscate_ysNkiBIc3pJJ = $_obfuscate_hUlweQMÿ['storename'];
            }
            else if ( $_obfuscate_Ns_JyWSm == "middle" )
            {
                $_obfuscate_ysNkiBIc3pJJ = $_obfuscate_hUlweQMÿ['thumb'].( ".mid.".$_obfuscate_hUlweQMÿ['ext'] );
            }
            else
            {
                $_obfuscate_ysNkiBIc3pJJ = $_obfuscate_hUlweQMÿ['thumb'].( ".".$_obfuscate_hUlweQMÿ['ext'] );
            }
            header( "content-type: image/jpeg" );
            header( "Location:".$GLOBALS['URL_FILE'].( "/common/disk/public/".$_obfuscate_hUlweQMÿ['storepath']."/" ).$_obfuscate_ysNkiBIc3pJJ );
        }
    }

    private function _changeViewMod( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_el6wL4FT0wÿÿ = getpar( $_POST, "viewMod", "list" );
        if ( !in_array( $_obfuscate_el6wL4FT0wÿÿ, array( "thumb", "list" ) ) )
        {
            return;
        }
        $_obfuscate_3y0Y = "INSERT INTO ";
        $_obfuscate_3y0Y .= tname( $this->table_user )." ";
        $_obfuscate_3y0Y .= "(`uid`, `maxsize`, `used`, `viewMod`) ";
        $_obfuscate_3y0Y .= "values ";
        $_obfuscate_3y0Y .= "('".$_obfuscate_7Ri3."', '0', '0', '{$_obfuscate_el6wL4FT0wÿÿ}"."') ";
        $_obfuscate_3y0Y .= "ON DUPLICATE KEY UPDATE `viewMod`='".$_obfuscate_el6wL4FT0wÿÿ."'";
        $CNOA_DB->query( $_obfuscate_3y0Y );
    }

    private function api_saveFormatDir( $_obfuscate_vholQÿÿ )
    {
        $_obfuscate_e7PLR79F = array(
            "report" => array( )
        );
        return $_obfuscate_Fwl1;
    }

    public function api_saveToDisk( $_obfuscate_xbQRFgÿÿ, $_obfuscate_vholQÿÿ )
    {
        global $CNOA_DB;
    }

    public function _sharedpeople( )
    {
        global $CNOA_DB;
        $_obfuscate_N9qwQCSc = getpar( $_POST, "fileid", "" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->db_select( array( "sharedpeopleUid", "sharedDeptIds" ), $this->table_public_file, "WHERE `fileid`='".$_obfuscate_N9qwQCSc."'" );
        $_obfuscate_SeV31Qÿÿ = $_obfuscate_6RYLWQÿÿ = array( );
        if ( !empty( $_obfuscate_xs33Yt_k[0]['sharedpeopleUid'] ) )
        {
            $_obfuscate_DDzLx451lxAioQÿÿ = explode( ",", $_obfuscate_xs33Yt_k[0]['sharedpeopleUid'] );
            $_obfuscate__Wi6396IheAÿ = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_DDzLx451lxAioQÿÿ );
            foreach ( $_obfuscate__Wi6396IheAÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_6RYLWQÿÿ[] = $_obfuscate__Wi6396IheAÿ[$_obfuscate_6Aÿÿ['uid']]['truename'];
            }
            $_obfuscate_SeV31Qÿÿ[0] = $_obfuscate_xs33Yt_k[0]['sharedpeopleUid'];
            $_obfuscate_SeV31Qÿÿ[1] = implode( ",", $_obfuscate_6RYLWQÿÿ );
        }
        else
        {
            $_obfuscate_SeV31Qÿÿ[0] = "";
            $_obfuscate_SeV31Qÿÿ[1] = "";
        }
        $_obfuscate_6RYLWQÿÿ = array( );
        if ( !empty( $_obfuscate_xs33Yt_k[0]['sharedDeptIds'] ) )
        {
            $_obfuscate_9TOt5LYBswÿÿ = explode( ",", $_obfuscate_xs33Yt_k[0]['sharedDeptIds'] );
            $_obfuscate_XRvPgP5V0t4ÿ = app::loadapp( "main", "struct" )->api_getNamesByIds( $_obfuscate_9TOt5LYBswÿÿ );
            foreach ( $_obfuscate_XRvPgP5V0t4ÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_6Aÿÿ;
            }
            $_obfuscate_SeV31Qÿÿ[2] = $_obfuscate_xs33Yt_k[0]['sharedDeptIds'];
            $_obfuscate_SeV31Qÿÿ[3] = implode( ",", $_obfuscate_6RYLWQÿÿ );
        }
        else
        {
            $_obfuscate_SeV31Qÿÿ[2] = "";
            $_obfuscate_SeV31Qÿÿ[3] = "";
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_SeV31Qÿÿ;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
    }

    private function _import( )
    {
        $this->importFromDisk( );
    }

    private function _moveToDir( )
    {
        global $CNOA_DB;
        $_obfuscate_N9qwQCSc = getpar( $_POST, "fileid" );
        $_obfuscate_fdpE = getpar( $_POST, "pid" );
        if ( !empty( $_obfuscate_N9qwQCSc ) || !empty( $_obfuscate_fdpE ) )
        {
            $_obfuscate_fKuAiftxvQÿÿ = $CNOA_DB->db_getfield( "path2", $this->table_public, "WHERE `fid`=".$_obfuscate_fdpE );
            $CNOA_DB->db_update( array(
                "fid" => $_obfuscate_fdpE,
                "path" => $_obfuscate_fKuAiftxvQÿÿ
            ), $this->table_public_file, "WHERE `fileid`=".$_obfuscate_N9qwQCSc );
            msg::callback( TRUE, lang( "successopt" ) );
        }
        msg::callback( FALSE, lang( "optFail" ) );
        exit( );
    }

    private function _move( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_iuzS = $CNOA_SESSION->get( "DID" );
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_N9qwQCSc = getpar( $_POST, "fileid" );
        $_obfuscate_Ce9h = $CNOA_DB->db_getfield( "fid", $this->table_public_file, "WHERE `fileid`=".$_obfuscate_N9qwQCSc );
        $_obfuscate__aYÿ = $CNOA_DB->db_getone( array( "mv" ), $this->table_public_permit_p, "WHERE `fid`=".$_obfuscate_Ce9h." AND `uid` = {$_obfuscate_7Ri3} " );
        $_obfuscate_LDtS = $CNOA_DB->db_getone( array( "mv" ), $this->table_public_permit_s, "WHERE `fid`=".$_obfuscate_Ce9h." AND `did` = {$_obfuscate_iuzS} " );
        if ( $_obfuscate_7Ri3 != 1 )
        {
            if ( ( empty( $_obfuscate__aYÿ ) || $_obfuscate__aYÿ['mv'] == 0 ) && ( empty( $_obfuscate_LDtS ) || $_obfuscate_LDtS['mv'] == 0 ) )
            {
                msg::callback( FALSE, "æ æéè½¬ç§»è¯¥æä»¶å¤¹æä»¶" );
            }
            if ( $_obfuscate__aYÿ['mv'] == 0 && $_obfuscate_LDtS['mv'] == 1 && !empty( $_obfuscate__aYÿ ) )
            {
                msg::callback( FALSE, "æ æéè½¬ç§»è¯¥æä»¶å¤¹æä»¶" );
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
        exit( );
    }

}

?>
