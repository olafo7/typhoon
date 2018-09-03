<?php
//decode by qq2859470

class mainSmsSetting extends model
{

    private $table_inbox = "main_sms_inbox";
    private $table_outbox = "main_sms_outbox";
    private $table_sendbox = "main_sms_sendbox";

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
        }
    }

    private function _getSetting( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $data = $CNOA_DB->db_getone( array( "sms_enable", "sms_api_type", "sms_api_sendurl", "sms_api_receiveurl", "sms_useapi" ), "system_config" );
        $data['msgoutcount'] = "0";
        $data['msgincount'] = "0";
        $data['enable'] = intval( $data['sms_enable'] );
        $data['sms_useapi'] = $data['sms_useapi'];
        $data['api_out'] = $data['sms_api_sendurl'];
        $data['api_in'] = $data['sms_api_receiveurl'];
        $data['api_type'] = $data['sms_api_type'];
        if ( !include( CNOA_PATH_FILE."/config/api.sms_sender.conf.php" ) )
        {
            @include( CNOA_PATH_FILE."/config/api.conf.php" );
        }
        $data['api_sms_sender_code'] = $GLOBALS['checkcode.sms_sender.php'];
        $data['msgoutcount'] = $CNOA_DB->db_getcount( $this->table_outbox, "WHERE 1" );
        $data['msgincount'] = $CNOA_DB->db_getcount( $this->table_inbox, "WHERE 1" );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _submit( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $data = array( );
        $data['sms_enable'] = getpar( $_POST, "enable" );
        $data['sms_api_sendurl'] = getpar( $_POST, "api_out", "", 1 );
        $data['sms_api_receiveurl'] = getpar( $_POST, "api_in", "", 1 );
        $data['sms_useapi'] = getpar( $_POST, "sms_useapi", "", 1 );
        $data['sms_api_type'] = getpar( $_POST, "api_type", "" );
        $data['sms_enable'] = $data['sms_enable'] == "on" ? 1 : 0;
        $api_sms_sender_code = getpar( $_POST, "api_sms_sender_code", "" );
        $api_sms_sender_code_file = "<?php \r\n";
        $api_sms_sender_code_file .= "\$GLOBALS['checkcode.sms_sender.php'] = \"".$api_sms_sender_code."\";\r\n";
        @file_put_contents( CNOA_PATH_FILE."/config/api.sms_sender.conf.php", $api_sms_sender_code_file );
        $CNOA_DB->db_update( $data, "system_config", "WHERE `id`=1" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 101, lang( "smsSet" ) );
        msg::callback( TRUE, lang( "successopt" ) );
        exit( );
    }

}

?>
