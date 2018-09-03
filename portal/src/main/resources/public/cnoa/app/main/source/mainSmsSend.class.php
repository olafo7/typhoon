<?php
//decode by qq2859470

class mainSmsSend extends model
{

    private $t_template = "main_sms_template";
    private $rows = 15;

    public function run( )
    {
        $task = getpar( $_GET, "task", getpar( $_POST, "task" ) );
        switch ( $task )
        {
        case "submit" :
            $this->_submit( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $this->_getAllUserListsInPermitDeptTree( );
            break;
        case "editTempate" :
            $this->_editTemplate( );
            break;
        case "getTemplateList" :
            $this->_getTemplateList( );
            break;
        case "loadTemplate" :
            $this->_loadTemplate( );
            break;
        case "deleteTemplate" :
            $this->_deleteTemplate( );
        }
    }

    private function _deleteTemplate( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $cuid = $CNOA_SESSION->get( "UID" );
        $ids = getpar( $_POST, "ids" );
        $where = "WHERE `id` IN (".$ids.") AND `uid`={$cuid}";
        $temp = $CNOA_DB->db_select( array( "name" ), $this->t_template, $where );
        $CNOA_DB->db_delete( $this->t_template, $where );
        foreach ( $temp as $v )
        {
            app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 97, $v['name'], lang( "smsTemp" ) );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadTemplate( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $cuid = $CNOA_SESSION->get( "UID" );
        $tid = getpar( $_POST, "tid" );
        $where = "WHERE `id`=".$tid;
        $dbinfo = $CNOA_DB->db_getone( "*", $this->t_template, $where );
        ( );
        $ds = new dataStore( );
        $ds->data = $dbinfo;
        $js = $ds->makeJsonData( );
        echo $js;
        exit( );
    }

    private function _getTemplateList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $cuid = $CNOA_SESSION->get( "UID" );
        $start = getpar( $_POST, "start", 0 );
        $where = "WHERE 1";
        $where .= " AND `uid`=".$cuid." ";
        $dblist = $CNOA_DB->db_select( "*", $this->t_template, $where.( " LIMIT ".$start.",{$this->rows}" ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $info )
        {
            $db =& $dblist[$k];
            $db['content'] = nl2br( $db['content'] );
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $dblist;
        $js = $ds->makeJsonData( );
        echo $js;
        exit( );
    }

    private function _editTemplate( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $cuid = $CNOA_SESSION->get( "UID" );
        $edit = getpar( $_GET, "edit", 0 );
        $tid = getpar( $_POST, "tid" );
        $name = getpar( $_POST, "name" );
        $content = getpar( $_POST, "content" );
        $data = array( );
        $data['name'] = $name;
        $data['content'] = $content;
        $data['uid'] = $cuid;
        $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        switch ( intval( $edit ) )
        {
        case 0 :
            $CNOA_DB->db_insert( $data, $this->t_template );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 97, $data['name'], lang( "smsTemp" ) );
            break;
        case 1 :
            $where = "WHERE `id`=".$tid;
            $CNOA_DB->db_update( $data, $this->t_template, $where );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 97, $data['name'], lang( "smsTemp" ) );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _submit( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uids = getpar( $_POST, "receiverUids", "" );
        if ( $uids[strlen( $uids ) - 1] == "," )
        {
            $uids = substr( $uids, 0, -1 );
        }
        $arrUid = explode( ",", $uids );
        $recvMobiles = getpar( $_POST, "customRecvUser" );
        $recvMobilesObj_tmp = json_decode( $_POST['customRecvMobile'], TRUE );
        $GLOBALS['dataSafeScan']->stopAttackFromArray( $recvMobilesObj_tmp );
        $recvMobilesObj = array( );
        if ( is_array( $recvMobilesObj_tmp ) && 0 < count( $recvMobilesObj_tmp ) )
        {
            foreach ( $recvMobilesObj_tmp as $v )
            {
                $recvMobilesObj[$v['m']] = $v['n'];
            }
        }
        $time = getpar( $_POST, "time", 0 );
        if ( $time != 0 )
        {
            $time = strtotime( $time );
            if ( $time < $GLOBALS['CNOA_TIMESTAMP'] )
            {
                $time = $GLOBALS['CNOA_TIMESTAMP'];
            }
        }
        else
        {
            $time = $GLOBALS['CNOA_TIMESTAMP'];
        }
        $text = getpar( $_POST, "text", "" );
        $from = "hand";
        if ( ( !is_array( $arrUid ) && count( $arrUid ) <= 0 ) && ( empty( $recvMobiles ) || !isset( $recvMobiles ) ) )
        {
            msg::callback( FALSE, lang( "notChoiceReceiveUser" ) );
        }
        ( );
        $sms = new sms( );
        if ( !is_array( $arrUid ) )
        {
            $arrUid = array( );
        }
        if ( !empty( $uids ) || 0 < count( $arrUid ) )
        {
            $sms->sendByUids( $arrUid, $text, $time, $from );
        }
        if ( !empty( $recvMobiles ) )
        {
            $arrRecv = explode( ";", $recvMobiles );
            if ( !is_array( $arrRecv ) )
            {
                $arrRecv = array( );
            }
            foreach ( $arrRecv as $mobile )
            {
                if ( !empty( $mobile ) )
                {
                    $text2 = str_replace( "@@recvMan@@", "", $text );
                    $sms->sendByMobile( $mobile, $text2, $time, $recvMobilesObj[$mobile] );
                }
            }
        }
        $mobiles = explode( ";", substr( $recvMobiles, 0, -1 ) );
        foreach ( $mobiles as $v )
        {
            $content = $text.( "( To:".$v." " ).date( "H-m-d H:i:s", $time ).")";
            app::loadapp( "main", "systemLogs" )->api_addLogs( "", 97, $content, lang( "sendSms" ) );
        }
        msg::callback( TRUE, lang( "SMSbeenSuccess" ) );
    }

    private function _getAllUserListsInPermitDeptTree( )
    {
        $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
        $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
        echo json_encode( $userList );
        exit( );
    }

}

?>
