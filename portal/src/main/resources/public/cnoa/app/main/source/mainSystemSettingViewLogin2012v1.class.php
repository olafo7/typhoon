<?php
//decode by qq2859470

class mainSystemSettingViewLogin2012v1
{

    public function _editLoadFormDataInfo( )
    {
        global $CNOA_DB;
        $setting = include( CNOA_PATH_FILE."/common/login/setting.2012v1.php" );
        if ( !is_array( $setting ) )
        {
            $setting = array( );
        }
        $data = array( );
        foreach ( $setting as $k => $v )
        {
            $data["login_".$k] = $v;
        }
        $s_tdn = $CNOA_DB->db_getfield( "s_tdn", "system_config", "WHERE `id`=1" );
        $data['s_tdn'] = $s_tdn;
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    public function _submitFormDataInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $setting = include( CNOA_PATH_FILE."/common/login/setting.2012v1.php" );
        if ( !is_array( $setting ) )
        {
            $setting = array( );
        }
        $setting['name'] = getpar( $_POST, "login_name" );
        $setting['thumb'] = getpar( $_POST, "login_thumb" );
        $setting['filename'] = getpar( $_POST, "login_filename" );
        $setting['bg_showtype'] = getpar( $_POST, "login_bg_showtype" );
        $setting['bg_color'] = getpar( $_POST, "login_bg_color" );
        $setting['table_split'] = getpar( $_POST, "login_table_split" ) == "hide" ? "hide" : "show";
        $setting['logo_show'] = getpar( $_POST, "login_logo_show" ) == "hide" ? "hide" : "show";
        $setting['logo_padding_left'] = intval( getpar( $_POST, "login_logo_padding_left" ) );
        @file_put_contents( CNOA_PATH_FILE."/common/login/setting.2012v1.php", "<?php return ".@var_export( $setting, TRUE ).";" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 91, lang( "loginView" ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function _upLogo( )
    {
        set_time_limit( 0 );
        $img_ext = strtolower( strrchr( $_FILES['face']['name'], "." ) );
        $img_name = "2011v1.logo";
        $img_dst = CNOA_PATH_FILE."/common/login/".$img_name;
        $img_url = "{$GLOBALS['URL_FILE']}/common/login/".$img_name.$img_ext;
        $extArray = array( ".jpg", ".gif", ".png" );
        if ( !in_array( $img_ext, $extArray ) )
        {
            msg::callback( FALSE, lang( "picOnlyFormat" ) );
        }
        if ( cnoa_move_uploaded_file( $_FILES['face']['tmp_name'], $img_dst.$img_ext ) )
        {
            $setting = include( CNOA_PATH_FILE."/common/login/setting.2012v1.php" );
            if ( !is_array( $setting ) )
            {
                $setting = array( );
            }
            $setting['logo'] = $img_name.$img_ext;
            @file_put_contents( CNOA_PATH_FILE."/common/login/setting.2012v1.php", "<?php return ".@var_export( $setting, TRUE ).";" );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 91, "LOGO" );
            msg::callback( TRUE, $img_url );
        }
        else
        {
            msg::callback( FALSE, lang( "uploadFail" ) );
        }
    }

    public function _upBackground( )
    {
        set_time_limit( 0 );
        $img_ext = strtolower( strrchr( $_FILES['face']['name'], "." ) );
        $img_name = $GLOBALS['CNOA_TIMESTAMP']."_".string::rands( 5 );
        $img_dst = CNOA_PATH_FILE."/common/login/".$img_name;
        $img_url = "{$GLOBALS['URL_FILE']}/common/login/".$img_name;
        $extArray = array( ".jpg", ".gif", ".png" );
        if ( !in_array( $img_ext, $extArray ) )
        {
            msg::callback( FALSE, lang( "picOnlyFormat" ) );
        }
        if ( cnoa_move_uploaded_file( $_FILES['face']['tmp_name'], $img_dst.$img_ext ) )
        {
            ( );
            $picture = new picture( );
            $picture->setSrcImg( $img_dst.$img_ext );
            $picture->setDstImg( $img_dst.".thumb".$img_ext );
            $picture->createImg( 120, 90 );
            $list = include( CNOA_PATH_FILE."/common/login/config.php" );
            if ( !is_array( $list ) )
            {
                $list = array( );
            }
            $list[] = array(
                "name" => str_replace( $img_ext, "", $_FILES['face']['name'] ),
                "filename" => $img_name.$img_ext,
                "thumb" => $img_name.".thumb".$img_ext,
                "size" => $_FILES['face']['size']
            );
            @file_put_contents( CNOA_PATH_FILE."/common/login/config.php", "<?php return ".@var_export( $list, TRUE ).";" );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 91, lang( "backgroundPic" ) );
            msg::callback( TRUE, $img_url );
        }
        else
        {
            msg::callback( FALSE, lang( "uploadFail" ) );
        }
    }

    public function _deleteBackground( )
    {
        $id = intval( getpar( $_POST, "id", NULL ) );
        $list = include( CNOA_PATH_FILE."/common/login/config.php" );
        if ( !is_array( $list ) )
        {
            $list = array( );
        }
        $setting = include( CNOA_PATH_FILE."/common/login/setting.2012v1.php" );
        if ( !is_array( $setting ) )
        {
            $setting = array( );
        }
        $data = array( );
        foreach ( $list as $k => $v )
        {
            if ( $k != $id )
            {
                $data[] = $v;
            }
            else
            {
                if ( $setting['filename'] == $v['filename'] )
                {
                    msg::callback( FALSE, lang( "imageBeenSetNotDel" ) );
                }
                @unlink( $GLOBALS['CNOA_BASE_URL_FILE']."/common/login/".$v['filename'] );
                @unlink( $GLOBALS['CNOA_BASE_URL_FILE']."/common/login/".$v['thumb'] );
            }
        }
        @file_put_contents( CNOA_PATH_FILE."/common/login/config.php", "<?php return ".@var_export( $data, TRUE ).";" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 91, lang( "backgroundPic" ) );
        msg::callback( TRUE, lang( "AlreadyDel" ) );
    }

    public function _makePreviewData( )
    {
        @file_put_contents( CNOA_PATH_FILE."/common/login/previewData.php", "<?php return ".@var_export( $_POST, TRUE ).";" );
        msg::callback( TRUE, 1 );
    }

    public function _preview( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        $setting = include( CNOA_PATH_FILE."/common/login/previewData.php" );
        $GLOBALS['GLOBALS']['CNOA_LOGINPAGE_URL'] = "{$GLOBALS['URL_FILE']}/common/login/".$setting['login_filename'];
        if ( $setting['login_bg_showtype'] == "center" )
        {
            $GLOBALS['GLOBALS']['CNOA_LOGINPAGE_SHOWTYPE'] = " center center no-repeat";
        }
        else if ( $setting['login_bg_showtype'] == "repeat" )
        {
            $GLOBALS['GLOBALS']['CNOA_LOGINPAGE_SHOWTYPE'] = " repeat";
        }
        else if ( $setting['login_bg_showtype'] == "repeatX" )
        {
            $GLOBALS['GLOBALS']['CNOA_LOGINPAGE_SHOWTYPE'] = " repeat-x center";
        }
        else if ( $setting['login_bg_showtype'] == "repeatY" )
        {
            $GLOBALS['GLOBALS']['CNOA_LOGINPAGE_SHOWTYPE'] = " center repeat-y";
        }
        $logoData = include( CNOA_PATH_FILE."/common/login/setting.2012v1.php" );
        if ( !is_array( $logoData ) )
        {
            $logoData = array( );
        }
        $GLOBALS['GLOBALS']['CNOA_LOGO_URL'] = "{$GLOBALS['URL_FILE']}/common/login/".$logoData['logo'];
        $GLOBALS['GLOBALS']['CNOA_BACKGROUND_COLOR'] = empty( $setting['login_bg_color'] ) ? "#FFF" : $setting['login_bg_color'];
        $GLOBALS['GLOBALS']['CNOA_TABLE_SPLITE_CLASS'] = $setting['login_table_split'] == "hide" ? "" : "split";
        $GLOBALS['GLOBALS']['CNOA_LOGO_SHOW'] = $setting['login_logo_show'] == "hide" ? "none" : "block";
        $GLOBALS['GLOBALS']['CNOA_LOGO_PDLEFT'] = $setting['login_logo_padding_left'];
        $systemInfo = $CNOA_DB->db_getone( array( "version1", "login_page" ), "system_config", "WHERE `id`=1" );
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/passport/".$systemInfo['login_page'];
        if ( ereg( "full\\.htm", $tplPath ) )
        {
            $CNOA_CONTROLLER->loadViewCustom( $tplPath, FALSE, FALSE );
        }
    }

    public function _parseLoginPage( )
    {
        $setting = include( CNOA_PATH_FILE."/common/login/setting.2012v1.php" );
        if ( !is_array( $setting ) )
        {
            $setting = array( );
        }
        $GLOBALS['GLOBALS']['CNOA_LOGINPAGE_URL'] = "{$GLOBALS['URL_FILE']}/common/login/".$setting['filename'];
        if ( $setting['bg_showtype'] == "center" )
        {
            $GLOBALS['GLOBALS']['CNOA_LOGINPAGE_SHOWTYPE'] = " center center no-repeat";
        }
        else if ( $setting['bg_showtype'] == "repeat" )
        {
            $GLOBALS['GLOBALS']['CNOA_LOGINPAGE_SHOWTYPE'] = " repeat";
        }
        else if ( $setting['bg_showtype'] == "repeatX" )
        {
            $GLOBALS['GLOBALS']['CNOA_LOGINPAGE_SHOWTYPE'] = " repeat-x center";
        }
        else if ( $setting['bg_showtype'] == "repeatY" )
        {
            $GLOBALS['GLOBALS']['CNOA_LOGINPAGE_SHOWTYPE'] = " center repeat-y";
        }
        $GLOBALS['GLOBALS']['CNOA_LOGO_URL'] = "{$GLOBALS['URL_FILE']}/common/login/".$setting['logo'];
        $GLOBALS['GLOBALS']['CNOA_BACKGROUND_COLOR'] = empty( $setting['bg_color'] ) ? "#FFF" : $setting['bg_color'];
        $GLOBALS['GLOBALS']['CNOA_TABLE_SPLITE_CLASS'] = $setting['table_split'] == "hide" ? "" : "split";
        $GLOBALS['GLOBALS']['CNOA_LOGO_SHOW'] = $setting['logo_show'] == "hide" ? "none" : "block";
        $GLOBALS['GLOBALS']['CNOA_LOGO_PDLEFT'] = $setting['logo_padding_left'];
    }

}

?>
