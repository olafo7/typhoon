<?php
//decode by qq2859470

class mainSystemSettingViewLogin2013v1
{

    private $configFile = "";

    public function __construct( )
    {
        $this->configFile = CNOA_PATH_FILE."/common/login/login2013v1/config.php";
    }

    public function _load_copyright_info_mobile( )
    {
        global $CNOA_DB;
        $config = include( $this->configFile );
        $data = array( );
        $data['copyright_info_mobile'] = empty( $config['copyright_info_mobile'] ) ? "" : $config['copyright_info_mobile'];
        @include( CNOA_PATH_FILE."/config/oem.conf.php" );
        if ( defined( "OEM_VERSION" ) )
        {
            $data['copyright_info_mobile'] = OEM_COPYMOBILE;
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    public function _submit_copyright_info_mobile( )
    {
        global $CNOA_DB;
        $v = $_POST['v'];
        $config = array( );
        $config = include( $this->configFile );
        $configText = "<?php \r\n return ";
        $oaType = @file_get_contents( CNOA_PATH_FILE."/config/vtp.conf.php" );
        if ( $oaType == "X1" )
        {
            $config['copyright_info_mobile'] = $v;
            $msg = "";
        }
        else
        {
            $config['copyright_info_mobile'] = "Copyright © '.date(\"Y\").' CNOA. All Rights Reserved.<br />广州协众";
            $msg = "<br />正式版方可修改登陆页底部信息";
        }
        @include( CNOA_PATH_FILE."/config/oem.conf.php" );
        if ( defined( "OEM_VERSION" ) )
        {
            $config['copyright_info_mobile'] = OEM_COPYMOBILE;
        }
        @file_put_contents( $this->configFile, $configText.@var_export( $config, TRUE ).";" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 91, "手机登陆界面" );
        msg::callback( TRUE, lang( "successopt" ).$msg );
        exit( );
    }

    public function _editLoadFormDataInfo( )
    {
        global $CNOA_DB;
        $CNOA_LOGO_URL_TMP = "{$GLOBALS['URL_FILE']}/common/login/login2013v1/logo_tmp.jpg";
        $CNOA_BG_URL_TMP = "{$GLOBALS['URL_FILE']}/common/login/login2013v1/index_bg_tmp.jpg";
        $CNOA_CONFIG_TMP = "{$GLOBALS['URL_FILE']}/common/login/login2013v1/config_tmp.php";
        @unlink( $CNOA_LOGO_URL_TMP );
        @unlink( $CNOA_BG_URL_TMP );
        @unlink( $CNOA_CONFIG_TMP );
        $config = include( $this->configFile );
        $data = array( );
        $data['login_logo'] = "login2013v1/logo.jpg";
        $data['login_thumb'] = "login2013v1/index_bg.jpg";
        $data['login_bg_bg'] = "login2013v1/bg_bg.jpg";
        $data = array_merge( $data, $config );
        @include( CNOA_PATH_FILE."/config/oem.conf.php" );
        if ( defined( "OEM_VERSION" ) )
        {
            $data['copyright_info'] = OEM_COPYRIGHT;
            $data['copyright_info_mobile'] = OEM_COPYMOBILE;
        }
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
        $img_logo1 = CNOA_PATH_FILE."/common/login/login2013v1/logo_tmp.jpg";
        $img_logo2 = CNOA_PATH_FILE."/common/login/login2013v1/logo.jpg";
        $img_bg1 = CNOA_PATH_FILE."/common/login/login2013v1/index_bg_tmp.jpg";
        $img_bg2 = CNOA_PATH_FILE."/common/login/login2013v1/index_bg.jpg";
        $img_bg_bg1 = CNOA_PATH_FILE."/common/login/login2013v1/bg_bg_tmp.jpg";
        $img_bg_bg2 = CNOA_PATH_FILE."/common/login/login2013v1/bg_bg.jpg";
        if ( file_exists( $img_logo1 ) )
        {
            @unlink( $img_logo2 );
            @rename( $img_logo1, $img_logo2 );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 91, "LOGO" );
        }
        if ( file_exists( $img_bg1 ) )
        {
            @unlink( $img_bg2 );
            @rename( $img_bg1, $img_bg2 );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 91, "背景图片1" );
        }
        if ( file_exists( $img_bg_bg1 ) )
        {
            @unlink( $img_bg_bg2 );
            @rename( $img_bg_bg1, $img_bg_bg2 );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 91, "背景图片2" );
        }
        $bgbgType = getpar( $_POST, "bgbgType", "bg" );
        $login_bg_color = getpar( $_POST, "login_bg_color", "#FFFFFF" );
        $copyright_info = $_POST['copyright_info'];
        $configs = include( $this->configFile );
        $config = array( );
        $oaType = @file_get_contents( CNOA_PATH_FILE."/config/vtp.conf.php" );
        if ( $oaType == "X1" )
        {
            $config['copyright_info'] = $copyright_info;
            $msg = "";
        }
        else
        {
            @include( CNOA_PATH_FILE."/config/oem.conf.php" );
            if ( defined( "OEM_VERSION" ) )
            {
                $config['copyright_info'] = OEM_COPYRIGHT;
            }
            else
            {
                $config['copyright_info'] = "<a href=\"http://www.cnoa.cn/\" target=\"_blank\">协众软件官方网站</a> | <a href=\"http://www.cnoa.cn/\" target=\"_blank\">© '.date(\"Y\").' 广州协众软件科技有限公司</a>";
            }
            $msg = "<br />正式版方可修改登陆页底部信息";
        }
        $config['copyright_info_mobile'] = $configs['copyright_info_mobile'];
        $configText = "<?php \r\n return ";
        $config['bgbgType'] = $bgbgType;
        $config['login_bg_color'] = $login_bg_color;
        @file_put_contents( $this->configFile, $configText.@var_export( $config, TRUE ).";" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 91, "登陆界面" );
        msg::callback( TRUE, lang( "successopt" ).$msg );
    }

    public function _upLogo( )
    {
        set_time_limit( 0 );
        $img_ext = strtolower( strrchr( $_FILES['face']['name'], "." ) );
        $img_dst = CNOA_PATH_FILE."/common/login/login2013v1/logo_tmp";
        $img_url = "{$GLOBALS['URL_FILE']}/common/login/login2013v1/logo_tmp";
        $extArray = array( ".jpg" );
        if ( !in_array( $img_ext, $extArray ) )
        {
            msg::callback( FALSE, lang( "picOnlyJpg" ) );
        }
        if ( cnoa_move_uploaded_file( $_FILES['face']['tmp_name'], $img_dst.$img_ext ) )
        {
            msg::callback( TRUE, $img_url.$img_ext );
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
        $img_dst = CNOA_PATH_FILE."/common/login/login2013v1/index_bg_tmp";
        $img_url = "{$GLOBALS['URL_FILE']}/common/login/login2013v1/index_bg_tmp";
        $extArray = array( ".jpg" );
        if ( !in_array( $img_ext, $extArray ) )
        {
            msg::callback( FALSE, lang( "picOnlyJpg" ) );
        }
        if ( cnoa_move_uploaded_file( $_FILES['face']['tmp_name'], $img_dst.$img_ext ) )
        {
            msg::callback( TRUE, $img_url.$img_ext );
        }
        else
        {
            msg::callback( FALSE, lang( "uploadFail" ) );
        }
    }

    public function _upBgBg( )
    {
        set_time_limit( 0 );
        $img_ext = strtolower( strrchr( $_FILES['face']['name'], "." ) );
        $img_dst = CNOA_PATH_FILE."/common/login/login2013v1/bg_bg_tmp";
        $img_url = "{$GLOBALS['URL_FILE']}/common/login/login2013v1/bg_bg_tmp";
        $extArray = array( ".jpg" );
        if ( !in_array( $img_ext, $extArray ) )
        {
            msg::callback( FALSE, lang( "picOnlyJpg" ) );
        }
        if ( cnoa_move_uploaded_file( $_FILES['face']['tmp_name'], $img_dst.$img_ext ) )
        {
            msg::callback( TRUE, $img_url.$img_ext );
        }
        else
        {
            msg::callback( FALSE, lang( "uploadFail" ) );
        }
    }

    public function _deleteBackground( )
    {
    }

    public function _makePreviewData( )
    {
        $bgbgType = getpar( $_POST, "bgbgType", "bg" );
        $login_bg_color = getpar( $_POST, "login_bg_color", "#FFFFFF" );
        $copyright_info = $_POST['copyright_info'];
        $config = array( );
        $config['bgbgType'] = $bgbgType;
        $config['login_bg_color'] = $login_bg_color;
        $config['copyright_info'] = $copyright_info;
        @file_put_contents( @str_replace( "config", "config_tmp", $this->configFile ), "<?php return ".@var_export( $config, TRUE ).";" );
        msg::callback( TRUE, 1 );
    }

    public function _preview( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        $rand = "?r=_".string::rands( 10 );
        $CNOA_LOGO_URL_TMP = "{$GLOBALS['URL_FILE']}/common/login/login2013v1/logo_tmp.jpg".$rand;
        $CNOA_LOGO_URL = "{$GLOBALS['URL_FILE']}/common/login/login2013v1/logo.jpg".$rand;
        if ( file_exists( $CNOA_LOGO_URL_TMP ) )
        {
            $GLOBALS['GLOBALS']['CNOA_LOGO_URL'] = $CNOA_LOGO_URL_TMP;
        }
        else
        {
            $GLOBALS['GLOBALS']['CNOA_LOGO_URL'] = $CNOA_LOGO_URL;
        }
        $CNOA_BG_URL_TMP = "{$GLOBALS['URL_FILE']}/common/login/login2013v1/index_bg_tmp.jpg".$rand;
        $CNOA_BG_URL = "{$GLOBALS['URL_FILE']}/common/login/login2013v1/index_bg.jpg".$rand;
        if ( file_exists( $CNOA_BG_URL_TMP ) )
        {
            $GLOBALS['GLOBALS']['CNOA_BG_URL'] = $CNOA_BG_URL_TMP;
        }
        else
        {
            $GLOBALS['GLOBALS']['CNOA_BG_URL'] = $CNOA_BG_URL;
        }
        $GLOBALS['GLOBALS']['CONFIG'] = include( str_replace( "config", "config_tmp", $this->configFile ) );
        if ( $GLOBALS['CONFIG']['bgbgType'] == "color" )
        {
            $GLOBALS['GLOBALS']['CNOA_BG_BG'] = "background-color: ".$GLOBALS['CONFIG']['login_bg_color'].";";
        }
        else
        {
            $CNOA_BG_BG_URL_TMP = "{$GLOBALS['URL_FILE']}/common/login/login2013v1/bg_bg_tmp.jpg".$rand;
            $CNOA_BG_BG_URL = "{$GLOBALS['URL_FILE']}/common/login/login2013v1/bg_bg.jpg".$rand;
            if ( file_exists( $CNOA_BG_BG_URL_TMP ) )
            {
                $GLOBALS['GLOBALS']['CNOA_BG_BG'] = "background:url(".$CNOA_BG_BG_URL_TMP.") repeat-x center;";
            }
            else
            {
                $GLOBALS['GLOBALS']['CNOA_BG_BG'] = "background:url(".$CNOA_BG_BG_URL.") repeat-x center;";
            }
        }
        $systemInfo = $CNOA_DB->db_getone( array( "version1", "login_page" ), "system_config", "WHERE `id`=1" );
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/passport/".$systemInfo['login_page'];
        if ( ereg( "full\\.htm", $tplPath ) )
        {
            $CNOA_CONTROLLER->loadViewCustom( $tplPath, FALSE, FALSE );
        }
    }

    public function _parseLoginPage( )
    {
        $GLOBALS['GLOBALS']['CNOA_LOGO_URL'] = "{$GLOBALS['URL_FILE']}/common/login/login2013v1/logo.jpg";
        $GLOBALS['GLOBALS']['CNOA_BG_URL'] = "{$GLOBALS['URL_FILE']}/common/login/login2013v1/index_bg.jpg";
        $GLOBALS['GLOBALS']['CNOA_BG_BG_URL'] = "{$GLOBALS['URL_FILE']}/common/login/login2013v1/bg_bg.jpg";
        $GLOBALS['GLOBALS']['CONFIG'] = include( $this->configFile );
        if ( $GLOBALS['CONFIG']['bgbgType'] == "color" )
        {
            $GLOBALS['GLOBALS']['CNOA_BG_BG'] = "background-color: ".$GLOBALS['CONFIG']['login_bg_color'].";";
        }
        else
        {
            $GLOBALS['GLOBALS']['CNOA_BG_BG'] = "background:url(".$GLOBALS['URL_FILE']."/common/login/login2013v1/bg_bg.jpg) repeat-x center;";
        }
    }

}

?>
