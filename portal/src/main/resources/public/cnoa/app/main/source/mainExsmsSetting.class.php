<?php
//decode by qq2859470

class mainExsmsSetting extends model
{

    private $table_setting = "main_exsms_setting";
    private $table_outbox = "main_exsms_outbox";
    private $table_status = "main_exsms_status";

    public function run( )
    {
        global $CNOA_SESSION;
        $task = getpar( $_GET, "task", getpar( $_POST, "task" ) );
        switch ( $task )
        {
        case "getSetting" :
            $this->_getSetting( );
            break;
        case "submit" :
            $this->_submit( );
            break;
        case "getBalance" :
            $this->_getBalance( );
            break;
        case "getStatusList" :
            $this->_getStatusList( );
            break;
        case "updateStatus" :
            $this->_updateStatus( );
            break;
        case "deleteStatus" :
            $this->_deleteStatus( );
        }
    }

    private function _getSetting( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $data = $CNOA_DB->db_getone( "*", $this->table_setting );
        $data['msgoutcount'] = "0";
        $data['msgoutcount'] = $CNOA_DB->db_getcount( $this->table_outbox, "WHERE 1" );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _submit( )
    {
        global $CNOA_DB;
        $data = array( );
        $data['api_send_url'] = getpar( $_POST, "api_send_url", "", 1 );
        $data['api_balance_url'] = getpar( $_POST, "api_balance_url", "", 1 );
        $data['timeformat'] = getpar( $_POST, "timeformat", "", 1 );
        $data['mobilesplite'] = getpar( $_POST, "mobilesplite", "", 1 );
        $data['charset'] = getpar( $_POST, "charset", "", 1 );
        $data['callbackregex'] = getpar( $_POST, "callbackregex", "", 1 );
        $data['balanceregex'] = getpar( $_POST, "balanceregex", "", 1 );
        $CNOA_DB->db_update( $data, $this->table_setting, "WHERE `id`=1" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 105, lang( "smsSet" ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getBalance( $return = FALSE )
    {
        global $CNOA_DB;
        $api_balance_url = $this->__getSetting( "api_balance_url" );
        $balanceregex = $this->__getSetting( "balanceregex" );
        if ( empty( $api_balance_url ) )
        {
            if ( $return )
            {
                return "接口地址为空，不能查余额";
            }
            msg::callback( FALSE, lang( "interfaceEmptyNotCheck" ) );
        }
        $back = @file_get_contents( $api_balance_url );
        if ( !$back )
        {
            if ( $return )
            {
                return "接口查询失败，请检查接口地址是否设置正确";
            }
            msg::callback( FALSE, lang( "interfaceQueryFailed" ) );
        }
        if ( !empty( $balanceregex ) )
        {
            $back = @preg_replace( $balanceregex, "\\1", $back );
        }
        if ( $return )
        {
            return strip_tags( $back );
        }
        $b = array( );
        $b[''] = TRUE;
        $b['msg'] = "当前接口的余额信息为：<br>".$back;
        echo json_encode( $b );
        exit( );
    }

    private function __getSetting( $field = "*" )
    {
        global $CNOA_DB;
        $info = $CNOA_DB->db_getone( "*", $this->table_setting, "WHERE id='1'" );
        if ( $field != "*" )
        {
            return $info[$field];
        }
        return $info;
    }

    private function _getStatusList( )
    {
        global $CNOA_DB;
        $dblist = array( );
        $dblist = $CNOA_DB->db_select( "*", $this->table_status, "WHERE 1" );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _updateStatus( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $id = getpar( $_POST, "id", "0" );
        $code = getpar( $_POST, "code", "", 1 );
        $name = getpar( $_POST, "name", "", 1 );
        $data = array( );
        $code == "" ? NULL : $data['code'] = $code;
        $name == "" ? NULL : $data['name'] = $name;
        if ( $id == 0 )
        {
            $CNOA_DB->db_insert( $data, $this->table_status );
            if ( !empty( $name ) )
            {
                $temp = $name;
            }
            else
            {
                $temp = $code;
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 105, $temp, lang( "status" ) );
        }
        else
        {
            $old = $CNOA_DB->db_getone( array( "name", "code" ), $this->table_status, "WHERE `id`='".$id."'" );
            $CNOA_DB->db_update( $data, $this->table_status, "WHERE `id`='".$id."'" );
            if ( $old['name'] != $name )
            {
                $temp = array(
                    "代码说明：".$old['name'] => $name
                );
            }
            if ( $old['code'] != $code )
            {
                $temp = array(
                    "返回代码：".$old['code'] => $code
                );
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 105, $temp, lang( "status" ).$code );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _deleteStatus( )
    {
        global $CNOA_DB;
        $ids = getpar( $_POST, "ids", NULL );
        $temp = $CNOA_DB->db_getone( array( "name", "code" ), $this->table_status, "WHERE `id`='".$ids."'" );
        $CNOA_DB->db_delete( $this->table_status, "WHERE `id`='".$ids."'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 105, $temp['code']."：".$temp['name'], lang( "status" ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function api_getSetting( )
    {
        return $this->__getSetting( );
    }

}

?>
