<?php
//decode by qq2859470

class mainSignatureCircle extends model
{

    private $table = "system_signature";

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        switch ( $task )
        {
        case "loadFont" :
            $this->_loadFont( );
            break;
        case "sealImg" :
            $this->_sealImg( );
            break;
        case "preView" :
            $this->_preView( );
            break;
        case "loadImg" :
            $this->_loadImg( );
            break;
        case "saveCircle" :
            $this->_saveCircle( );
        }
    }

    private function _loadFont( )
    {
        $os = getos( );
        if ( !( $os == "linux" ) )
        {
        }
        $fontDir = str_replace( "\\", "/", $_SERVER['WINDIR'] )."/Fonts/";
        if ( !is_dir( $fontDir ) )
        {
            return 0;
        }
        $font = array( "楷体" => "SIMKAI.TTF", "宋体" => "SIMSUN.TTC", "仿宋体" => "SIMFANG.TTF", "黑体" => "SIMHEI.TTF", "雅黑" => "MSYH.TTF" );
        $sysFont = scandir( $fontDir );
        foreach ( $sysFont as $k => $v )
        {
            $sysFont[$k] = strtoupper( $v );
        }
        $data = array( );
        $i = 0;
        foreach ( $font as $k => $v )
        {
            if ( !in_array( $v, $sysFont ) )
            {
                unset( $font[$k] );
            }
            else
            {
                $data[$i]['font'] = $k;
                $data[$i]['url'] = $v;
                ++$i;
            }
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _sealImg( $url = "" )
    {
        $data = $_GET['data'];
        if ( empty( $data ) )
        {
            ( );
            $seal = new circleSeal( );
        }
        else
        {
            $data = json_decode( str_replace( "&quot;", "\"", $data ), TRUE );
            $GLOBALS['dataSafeScan']->stopAttackFromArray( $data );
            ( $data );
            $seal = new circleSeal( );
        }
        $seal->doImg( $url );
    }

    private function _preView( )
    {
        $data = $this->getArg( );
        msg::callback( TRUE, urlencode( json_encode( $data ) ) );
    }

    private function _loadImg( )
    {
        $imgDir = CNOA_PATH_FILE."/common/signature/bmp/";
        $imgs = scandir( $imgDir );
        $data = array( );
        $i = 0;
        foreach ( $imgs as $k => $v )
        {
            $pos = strrpos( $v, "." );
            if ( $v == "." || $v == ".." || substr( $v, $pos ) != ".png" )
            {
                unset( $imgs[$k] );
            }
            else
            {
                $data[$i]['name'] = substr( $v, 0, $pos );
                $data[$i]['url'] = $GLOBALS['URL_FILE']."/common/signature/bmp/".$v;
                ++$i;
            }
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _saveCircle( )
    {
        global $CNOA_DB;
        $save = $this->getArg( );
        $data['uid'] = getpar( $_POST, "userId", "" );
        $data['signature'] = getpar( $_POST, "sealName", "" );
        $data['isUsePwd'] = ( integer )getpar( $_POST, "isUsePwd" );
        if ( empty( $data['uid'] ) )
        {
            msg::callback( FALSE, lang( "userNameNotEmpty" ) );
        }
        if ( empty( $data['signature'] ) )
        {
            msg::callback( FALSE, lang( "signatureNotEmpty" ) );
        }
        else
        {
            $where = "WHERE `uid`=".$data['uid']." AND `signature`='{$data['signature']}' ";
            $is_exists = $CNOA_DB->db_getfield( "id", $this->table, $where );
            if ( $is_exists )
            {
                msg::callback( FALSE, lang( "signatureNameExist" ) );
            }
        }
        $img_ext = ".png";
        $img_name = $GLOBALS['CNOA_TIMESTAMP']."_".md5( $GLOBALS['CNOA_TIMESTAMP'] ).$img_ext;
        $img_dst = CNOA_PATH_FILE."/common/signature/graph/".$img_name;
        $img_url = $GLOBALS['CNOA_BASE_URL_FILE']."/common/signature/graph/".$img_name;
        ( $save );
        $seal = new circleSeal( );
        $data['url'] = $img_url;
        $seal->doImg( $img_url );
        $CNOA_DB->db_insert( $data, $this->table );
        msg::callback( TRUE, "" );
    }

    private function getArg( )
    {
        $data['width'] = getpar( $_POST, "width", "" );
        $data['height'] = getpar( $_POST, "height", "" );
        $data['rim'] = getpar( $_POST, "rim", "" );
        $data['upText'] = getpar( $_POST, "upText", "" );
        $data['upFont'] = getpar( $_POST, "upFont", "" );
        $data['upFontHeight'] = getpar( $_POST, "upFontHeight", "" );
        $data['croText'] = getpar( $_POST, "croText", "" );
        $data['croFont'] = getpar( $_POST, "croFont", "" );
        $data['croFontHeight'] = getpar( $_POST, "croFontHeight", "" );
        $data['inMove'] = getpar( $_POST, "inMove", "" );
        $data['downMove'] = getpar( $_POST, "downMove", "" );
        $data['inImg'] = getpar( $_POST, "inImg", "" );
        $data['uAngle'] = getpar( $_POST, "uAngle", "" );
        $data['inImgWidth'] = getpar( $_POST, "inImgWidth", "" );
        $data['inImgDown'] = getpar( $_POST, "inImgDown", "" );
        $data['spacing'] = getpar( $_POST, "spacing", "" );
        return $data;
    }

}

?>
