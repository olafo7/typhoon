<?php
//decode by qq2859470

class mainSystemLoginlimit extends model
{

    private $t_table = "system_login_limit";

    public function run( )
    {
        global $CNOA_SESSION;
        $task = getpar( $_GET, "task", "" );
        switch ( $task )
        {
        case "getList" :
            $this->_getList( );
            break;
        case "setLoginLimit" :
            $this->_setLoginLimit( );
            break;
        case "getSelectorData" :
            $this->_getSelectorData( );
            break;
        case "submitForm" :
            $this->_submitForm( );
            break;
        case "editLoadFormData" :
            $this->_editLoadFormDatam( );
            break;
        case "changeInUse" :
            $this->_changeInUse( );
            break;
        case "delete" :
            $this->_delete( );
            break;
        case "getlSetting" :
            $this->_getlSetting( );
        }
    }

    private function _getList( )
    {
        global $CNOA_DB;
        $type = getpar( $_POST, "type", 1 );
        $list = $CNOA_DB->db_select( "*", $this->t_table, "WHERE `type`='".$type."'" );
        if ( !is_array( $list ) )
        {
            $list = array( );
        }
        foreach ( $list as $k => $v )
        {
            switch ( $v['target'] )
            {
            case "1" :
                $deptIds = json_decode( $v['content'], TRUE );
                $deptNames = app::loadapp( "main", "struct" )->api_getNamesByIds( $deptIds );
                $list[$k]['content'] = implode( ", ", $deptNames );
                break;
            case "2" :
                $jobIds = json_decode( $v['content'], TRUE );
                $jobNames = app::loadapp( "main", "job" )->api_getNamesByIds( $jobIds );
                $list[$k]['content'] = implode( ", ", $jobNames );
                break;
            case "3" :
                $uids = json_decode( $v['content'], TRUE );
                $uNames = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uids );
                $unames_tmp = array( );
                foreach ( $uNames as $v )
                {
                    $unames_tmp[] = $v['truename'];
                }
                $list[$k]['content'] = implode( ", ", $unames_tmp );
                break;
            case "4" :
                $sids = json_decode( $v['content'], TRUE );
                $sNames = app::loadapp( "main", "station" )->api_getNamesByIds( $sids );
                $list[$k]['content'] = implode( ", ", $sNames );
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $list;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _setLoginLimit( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_XXTEA;
        $systemInfo = array( );
        $systemInfo['login_limit'] = getpar( $_POST, "login_limit", "" );
        if ( !in_array( $systemInfo['login_limit'], array( 0, 1, 2, 3 ) ) )
        {
            msg::callback( FALSE, lang( "optFail" ) );
        }
        $CNOA_DB->db_update( $systemInfo, "system_config", "WHERE `id`=1" );
        msg::callback( TRUE, lang( "saved" ) );
    }

    private function _getSelectorData( )
    {
        $target = getpar( $_GET, "target", 0 );
        switch ( $target )
        {
        case "1" :
            echo app::loadapp( "main", "struct" )->api_getStructTree( );
            exit( );
        case "2" :
            echo app::loadapp( "main", "job" )->api_getJsonList( );
            exit( );
        case "3" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $userList );
            exit( );
        case "4" :
            $list = app::loadapp( "main", "station" )->api_getStationList( FALSE );
            if ( !is_array( $list ) )
            {
                $list = array( );
            }
            foreach ( $list as $k => $v )
            {
                $list[$k]['stationid'] = $v['sid'];
                unset( $v['sid'] );
            }
            ( );
            $dataStore = new dataStore( );
            $dataStore->total = 0;
            $dataStore->data = $list;
            echo $dataStore->makeJsonData( );
        }
        exit( );
    }

    private function _submitForm( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = intval( getpar( $_POST, "id", 0 ) );
        $data['name'] = getpar( $_POST, "name", 0 );
        $data['content'] = $_POST['content'];
        $data['sip'] = getpar( $_POST, "sip", 0 );
        $data['eip'] = getpar( $_POST, "eip", 0 );
        $data['target'] = getpar( $_POST, "target", 0 );
        $data['type'] = getpar( $_POST, "type", 0 );
        $data['uid'] = $CNOA_SESSION->get( "UID" );
        $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        if ( !in_array( $data['target'], array( 1, 2, 3, 4 ) ) || $id == 0 )
        {
            msg::callback( FALSE, lang( "ruleObjError" ) );
        }
        if ( !in_array( $data['type'], array( 1, 2 ) ) )
        {
            msg::callback( FALSE, lang( "ruleTypeRrror" ) );
        }
        $data['content'] = json_decode( $data['content'], TRUE );
        if ( is_array( $data['content'] ) && 0 < count( $data['content'] ) )
        {
            $data['content'] = json_encode( $data['content'] );
        }
        else
        {
            msg::callback( FALSE, lang( "ruleObjNotEmpty" ) );
        }
        $sip = iptolong( $data['sip'] );
        $eip = iptolong( $data['eip'] );
        if ( $eip < $sip )
        {
            msg::callback( FALSE, lang( "startIPNotLessthanEip" ) );
        }
        $GLOBALS['dataSafeScan']->stopAttackFromArray( $data );
        if ( $id == 0 )
        {
            $data['inuse'] = 0;
            $CNOA_DB->db_insert( $data, $this->t_table );
        }
        else
        {
            unset( $data['target'] );
            $CNOA_DB->db_update( $data, $this->t_table, "WHERE `id`='".$id."'" );
        }
        msg::callback( TRUE, lang( "saved" ) );
        exit( );
    }

    private function _changeInUse( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = getpar( $_POST, "id", 0 );
        $inuse = getpar( $_POST, "inuse", 0 );
        $CNOA_DB->db_update( array(
            "inuse" => $inuse
        ), $this->t_table, "WHERE `id`='".$id."'" );
        msg::callback( TRUE, lang( "hasBeenEdit" ) );
        exit( );
    }

    private function _delete( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = getpar( $_POST, "id", 0 );
        $CNOA_DB->db_delete( $this->t_table, "WHERE `id`='".$id."'" );
        msg::callback( TRUE, lang( "AlreadyDel" ) );
        exit( );
    }

    private function _getlSetting( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        ( );
        $dataStore = new dataStore( );
        $login_limit = $CNOA_DB->db_getfield( "login_limit", "system_config", "WHERE `id`=1" );
        $dataStore->data = array(
            "login_limit" => $login_limit
        );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _editLoadFormDatam( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $id = getpar( $_POST, "id", 0 );
        $info = $CNOA_DB->db_getone( "*", $this->t_table, "WHERE `id`='".$id."'" );
        ( );
        $dataStore = new dataStore( );
        if ( !$info )
        {
            $dataStore->success = FALSE;
            $dataStore->msg = lang( "nodata" );
            echo $dataStore->makeJsonData( );
            exit( );
        }
        $ids = json_decode( $info['content'], TRUE );
        $items = array( );
        switch ( $info['target'] )
        {
        case "1" :
            $names = app::loadapp( "main", "struct" )->api_getNamesByIds( $ids );
            break;
        case "2" :
            $names = app::loadapp( "main", "job" )->api_getNamesByIds( $ids );
            break;
        case "3" :
            $names = app::loadapp( "main", "user" )->api_getUserNamesByUids( $ids );
            break;
        case "4" :
            $names = app::loadapp( "main", "station" )->api_getNamesByIds( $ids );
        }
        foreach ( $names as $k => $v )
        {
            if ( $info['target'] == 3 )
            {
                $tmp = array(
                    "name" => $v['truename'],
                    "id" => $v['uid']
                );
            }
            else
            {
                $tmp = array(
                    "name" => $v,
                    "id" => $k
                );
            }
            $items[] = $tmp;
        }
        $info['items'] = $items;
        $dataStore->data = $info;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    public function api_is_limited( $did, $jid, $uid, $sid, $type )
    {
        global $CNOA_DB;
        $mip = iptolong( getip( ) );
        $login_limit = $CNOA_DB->db_getfield( "login_limit", "system_config", "WHERE `id`=1" );
        if ( $type == "login" )
        {
            if ( $login_limit == 0 || $login_limit == 2 )
            {
                return FALSE;
            }
            $dtype = 1;
        }
        if ( $type == "checkin" )
        {
            if ( $login_limit == 0 || $login_limit == 1 )
            {
                return FALSE;
            }
            $dtype = 2;
        }
        if ( $uid == 1 )
        {
            $ini = @parse_ini_file( CNOA_PATH_FILE."/config/loginLimit.ini", TRUE );
            if ( $ini['GLOBAL']['adminLoginLimit'] === "0" )
            {
                return FALSE;
            }
        }
        $list = $CNOA_DB->db_select( "*", $this->t_table, "WHERE `type`='".$dtype."' AND `inuse`='1'" );
        if ( !is_array( $list ) )
        {
            $list = array( );
        }
        $limited = TRUE;
        foreach ( $list as $v )
        {
            $content = json_decode( $v['content'], TRUE );
            $sip = iptolong( $v['sip'] );
            $eip = iptolong( $v['eip'] );
            switch ( $v['target'] )
            {
            case "1" :
                if ( !in_array( $did, $content ) && !( $mip <= $eip ) && !( $sip <= $mip ) )
                {
                    break;
                }
                $limited = FALSE;
                break;
            case "2" :
                if ( !in_array( $jid, $content ) && !( $mip <= $eip ) && !( $sip <= $mip ) )
                {
                    break;
                }
                $limited = FALSE;
                break;
            case "3" :
                if ( !in_array( $uid, $content ) && !( $mip <= $eip ) && !( $sip <= $mip ) )
                {
                    break;
                }
                $limited = FALSE;
                break;
            case "4" :
                if ( !in_array( $sid, $content ) && !( $mip <= $eip ) && !( $sip <= $mip ) )
                {
                    break;
                }
                $limited = FALSE;
            }
        }
        return $limited;
    }

}

?>
