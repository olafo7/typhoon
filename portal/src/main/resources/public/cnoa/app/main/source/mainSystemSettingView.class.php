<?php
//decode by qq2859470

class mainSystemSettingView
{

    private $table = "system_outlink";
    private $desk_table = "system_desktop";
    private $desk_permit = "system_desk_permit";

    public function run( )
    {
        $task = getpar( $_GET, "task", getpar( $_POST, "task" ) );
        switch ( $task )
        {
        case "getBackgroundList" :
            $this->_getBackgroundList( );
            break;
        case "upBgBg" :
            $this->_upBgBg( );
            break;
        case "upBackground" :
            $this->_upBackground( );
            break;
        case "deleteBackground" :
            $this->_deleteBackground( );
            break;
        case "upLogo" :
            $this->_upLogo( );
            break;
        case "upLoadingLogo" :
            $this->_upLoadingLogo( );
            break;
        case "upThemeLogo" :
            $this->_upThemeLogo( );
            break;
        case "upPhoneBackground" :
            $this->_upPhoneBackground( );
            break;
        case "upPhoneLogo" :
            $this->_upPhoneLogo( );
            break;
        case "editLoadFormDataInfo" :
            $this->_editLoadFormDataInfo( );
            break;
        case "submitFormDataInfo" :
            $this->_submitFormDataInfo( );
            break;
        case "load_copyright_info_mobile" :
            $this->_load_copyright_info_mobile( );
            break;
        case "submit_copyright_info_mobile" :
            $this->_submit_copyright_info_mobile( );
            break;
        case "makePreviewData" :
            $this->_makePreviewData( );
            break;
        case "saveTopNum" :
            $this->_saveTopNum( );
            break;
        case "saveTagNum" :
            $this->_saveTagNum( );
            break;
        case "getTagNum" :
            $this->_getTagNum( );
            break;
        case "getDeskList" :
            $this->_getDeskList( );
            break;
        case "getStructTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            echo app::loadapp( "main", "struct" )->api_getStructTree( );
            exit( );
        case "getStationTree" :
            app::loadapp( "main", "station" )->api_getSelectorData( );
            exit( );
        case "getAllUserListsInPermitDeptTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $userList );
            exit( );
        case "submitBoardPermitFormData" :
            $this->_submitBoardPermitFormData( );
            break;
        case "loadPermitFormData" :
            $this->_loadPermitFormData( );
        }
    }

    private function _getBackgroundList( )
    {
        global $CNOA_DB;
        $list = include( CNOA_PATH_FILE."/common/login/config.php" );
        if ( !is_array( $list ) )
        {
            $list = array( );
        }
        $data = array( );
        foreach ( $list as $k => $v )
        {
            $data[] = array(
                "id" => $k,
                "name" => $v['name'],
                "filename" => $v['filename'],
                "size" => $v['size'],
                "thumb" => $v['thumb'],
                "url" => "{$GLOBALS['URL_FILE']}/common/login/".$v['thumb']
            );
        }
        echo json_encode( array(
            "images" => $data
        ) );
        exit( );
    }

    private function _upBackground( )
    {
        $class = $this->_getLoginViewClass( );
        $class->_upBackground( );
    }

    private function _upBgBg( )
    {
        $class = $this->_getLoginViewClass( );
        $class->_upBgBg( );
    }

    private function _deleteBackground( )
    {
        $class = $this->_getLoginViewClass( );
        $class->_deleteBackground( );
    }

    private function _upLogo( )
    {
        $class = $this->_getLoginViewClass( );
        $class->_upLogo( );
    }

    private function _upLoadingLogo( )
    {
        set_time_limit( 0 );
        $img_ext = strtolower( strrchr( $_FILES['face']['name'], "." ) );
        $img_name = "logo-loading.gif";
        $img_dst = CNOA_PATH_FILE."/common/login/".$img_name;
        $img_url = "{$GLOBALS['URL_FILE']}/common/login/".$img_name;
        $extArray = array( ".gif" );
        if ( !in_array( $img_ext, $extArray ) )
        {
            msg::callback( FALSE, lang( "picOnlyGIFimg" ) );
        }
        if ( cnoa_move_uploaded_file( $_FILES['face']['tmp_name'], $img_dst ) )
        {
            msg::callback( TRUE, $img_url );
        }
        else
        {
            msg::callback( FALSE, lang( "uploadFail" ) );
        }
    }

    private function _upThemeLogo( )
    {
        set_time_limit( 0 );
        $theme = getpar( $_POST, "theme", "" );
        $img_ext = strtolower( strrchr( $_FILES['face']['name'], "." ) );
        $img_name = $theme.".gif";
        $img_dst = CNOA_PATH_FILE."/webcache/logo/".$img_name;
        $img_url = "{$GLOBALS['URL_FILE']}/webcache/logo/".$img_name;
        $extArray = array( ".gif" );
        if ( !in_array( $img_ext, $extArray ) )
        {
            msg::callback( FALSE, lang( "picOnlyGIFimg" ) );
        }
        if ( cnoa_move_uploaded_file( $_FILES['face']['tmp_name'], $img_dst ) )
        {
            msg::callback( TRUE, $img_url );
        }
        else
        {
            msg::callback( FALSE, lang( "uploadFail" ) );
        }
    }

    private function _upPhoneBackground( )
    {
        set_time_limit( 0 );
        $img_ext = strtolower( strrchr( $_FILES['face']['name'], "." ) );
        $img_name = "bg.jpg";
        $img_dst = CNOA_PATH_FILE."/common/login/m/".$img_name;
        $img_url = "{$GLOBALS['URL_FILE']}/common/login/m/".$img_name;
        $extArray = array( ".jpg" );
        if ( !in_array( $img_ext, $extArray ) )
        {
            msg::callback( FALSE, lang( "picOnlyJpg" ) );
        }
        if ( @cnoa_move_uploaded_file( $_FILES['face']['tmp_name'], $img_dst ) )
        {
            msg::callback( TRUE, $img_url );
        }
        else
        {
            msg::callback( FALSE, lang( "uploadFail" ) );
        }
    }

    private function _upPhoneLogo( )
    {
        set_time_limit( 0 );
        $img_ext = strtolower( strrchr( $_FILES['face']['name'], "." ) );
        $img_name = "logo.png";
        $img_dst = CNOA_PATH_FILE."/common/login/m/".$img_name;
        $img_url = "{$GLOBALS['URL_FILE']}/common/login/m/".$img_name;
        $extArray = array( ".png" );
        if ( !in_array( $img_ext, $extArray ) )
        {
            msg::callback( FALSE, lang( "picOnlyPng" ) );
        }
        if ( cnoa_move_uploaded_file( $_FILES['face']['tmp_name'], $img_dst ) )
        {
            msg::callback( TRUE, $img_url );
        }
        else
        {
            msg::callback( FALSE, lang( "uploadFail" ) );
        }
    }

    private function _load_copyright_info_mobile( )
    {
        $class = $this->_getLoginViewClass( );
        $class->_load_copyright_info_mobile( );
    }

    private function _submit_copyright_info_mobile( )
    {
        $class = $this->_getLoginViewClass( );
        $class->_submit_copyright_info_mobile( );
    }

    private function _editLoadFormDataInfo( )
    {
        $class = $this->_getLoginViewClass( );
        $class->_editLoadFormDataInfo( );
    }

    private function _submitFormDataInfo( )
    {
        $class = $this->_getLoginViewClass( );
        $class->_submitFormDataInfo( );
    }

    private function _getLoginViewClass( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $class = FALSE;
        $loginPage = $CNOA_DB->db_getfield( "login_page", "system_config", "WHERE `id`=1" );
        switch ( $loginPage )
        {
        case "login.2012v1.full.htm" :
            $class = app::loadapp( "main", "systemSettingViewLogin2012v1" );
            return $class;
        case "login.2013v1.full.htm" :
            $class = app::loadapp( "main", "systemSettingViewLogin2013v1" );
        }
        return $class;
    }

    private function _makePreviewData( )
    {
        $class = $this->_getLoginViewClass( );
        $class->_makePreviewData( );
    }

    private function _saveTopNum( )
    {
        global $CNOA_DB;
        $s_tdn = getpar( $_POST, "s_tdn", 2 );
        $CNOA_DB->db_update( array(
            "s_tdn" => $s_tdn
        ), "system_config", "WHERE `id`=1" );
        msg::callback( TRUE, "" );
    }

    private function _getTagNum( )
    {
        global $CNOA_DB;
        $tag = $CNOA_DB->db_getfield( "tag_num", "system_config", "WHERE `id`=1" );
        ( );
        $ds = new dataStore( );
        $ds->data = array(
            "tag_num" => $tag
        );
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _saveTagNum( )
    {
        global $CNOA_DB;
        $tag = getpar( $_POST, "tag_num", 7 );
        $CNOA_DB->db_update( array(
            "tag_num" => $tag
        ), "system_config", "WHERE `id`=1" );
        msg::callback( TRUE, "" );
    }

    private function _getDeskList( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->desk_table );
        ( );
        $ds = new dataStore( );
        $ds->data = $dblist;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _loadPermitFormData( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", 0 );
        $data = $CNOA_DB->db_getone( "*", $this->desk_table, "WHERE `id` = '".$id."' " );
        $dblist = $CNOA_DB->db_select( "*", $this->desk_permit, "WHERE `board_id` = '".$id."' ORDER BY `type` " );
        if ( !empty( $dblist ) )
        {
            $deptArr = array( 0 );
            $struArr = array( 0 );
            $peopArr = array( 0 );
            foreach ( $dblist as $k => $v )
            {
                if ( $v['type'] == "d" )
                {
                    $deptArr[] = $v['permitId'];
                }
                else if ( $v['type'] == "s" )
                {
                    $struArr[] = $v['permitId'];
                }
                else if ( $v['type'] == "p" )
                {
                    $peopArr[] = $v['permitId'];
                }
            }
            $deptDB = app::loadapp( "main", "struct" )->api_getNamesByIds( $deptArr );
            $structDB = app::loadapp( "main", "station" )->api_getNamesByIds( $struArr );
            $truenameDB = app::loadapp( "main", "user" )->api_getUserNamesByUids( $peopArr );
            foreach ( $dblist as $k => $v )
            {
                if ( $v['type'] != "n" )
                {
                    $data['deskPermit'][] = $this->__loadForm_FormatPermit( $v, $deptDB, $structDB, $truenameDB );
                }
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _submitBoardPermitFormData( )
    {
        global $CNOA_DB;
        $deskIds = getpar( $_POST, "deskIds", 0 );
        $id = getpar( $_POST, "id", 0 );
        $insert['board_id'] = $id;
        if ( empty( $deskIds ) )
        {
            $insert['type'] = "n";
            $insert['permitId'] = 0;
            $CNOA_DB->db_insert( $insert, $this->desk_permit, FALSE );
        }
        else
        {
            $deskArr = explode( ",", $deskIds );
            foreach ( $deskArr as $v )
            {
                $ex_deskArr = explode( "-", $v );
                if ( !empty( $ex_deskArr[1] ) )
                {
                    $insert['type'] = $ex_deskArr[0];
                    $insert['permitId'] = $ex_deskArr[1];
                    $CNOA_DB->db_insert( $insert, $this->desk_permit, FALSE );
                }
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function __loadForm_FormatPermit( $data, $deptDB, $structDB, $truenameDB )
    {
        switch ( $data['type'] )
        {
        case "d" :
            return array(
                "id" => "d-".$data['permitId'],
                "text" => "(部门)".$deptDB[$data['permitId']]
            );
        case "s" :
            return array(
                "id" => "s-".$data['permitId'],
                "text" => "(岗位)".$structDB[$data['permitId']]
            );
        case "p" :
            return array(
                "id" => "p-".$data['permitId'],
                "text" => "(人员)".$truenameDB[$data['permitId']]['truename']
            );
        }
    }

}

?>
