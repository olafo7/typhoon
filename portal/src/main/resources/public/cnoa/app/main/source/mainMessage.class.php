<?php
//decode by qq2859470

class mainMessage extends model
{

    public function actionIndex( )
    {
        global $CNOA_DB;
        global $CNOA_PERMIT;
        global $CNOA_SESSION;
        $this->_setHeaderType( "iframe" );
        $uid = $CNOA_SESSION->get( "UID" );
        $task = getpar( $_POST, "task", getpar( $_GET, "task", "" ) );
        if ( $task == "getJsonData" )
        {
            echo app::loadapp( "communication", "message" )->api_getSystemMessageList( );
            exit( );
        }
        if ( $task == "preview" )
        {
            $id = getpar( $_POST, "id", 0 );
            $smsDb = app::loadapp( "communication", "message" )->api_getSystemMessageById( $id );
            ( );
            $fs = new fs( );
            $tmp['title'] = $smsDb['title'];
            $tmp['content'] = $smsDb['content'];
            $tmp['posttime'] = date( "Y年m月d日 H时i分", $smsDb['posttime'] );
            $tmp['start'] = date( "Y年m月d日 H时i分", $smsDb['start'] );
            $tmp['end'] = date( "Y年m月d日 H时i分", $smsDb['end'] );
            $tmp['sender'] = app::loadapp( "main", "user" )->api_getUserNameByUid( $smsDb['from_uid'] );
            $tmp['attach'] = json_decode( $tmp['attach'], TRUE );
            $tmp['attachCount'] = !$tmp['attach'] ? 0 : count( $tmp['attach'] );
            $tmp['attach'] = $fs->getDownLoadItems4normal( $tmp['attach'], TRUE );
            ( );
            $dataStore = new dataStore( );
            $dataStore->total = 0;
            $dataStore->data = $tmp;
            echo $dataStore->makeJsonData( );
            exit( );
        }
        if ( $task == "loadForm" )
        {
            $id = getpar( $_POST, "edit_id", 0 );
            $smsDb = app::loadapp( "communication", "message" )->api_getSystemMessageById( $id );
            $tmp['title'] = $smsDb['title'];
            $tmp['inuse'] = $smsDb['inuse'];
            $tmp['content'] = $smsDb['content'];
            $tmp['sdate'] = date( "Y-m-d", $smsDb['start'] );
            $tmp['stime'] = date( "H:i:s", $smsDb['start'] );
            $tmp['edate'] = date( "Y-m-d", $smsDb['end'] );
            $tmp['etime'] = date( "H:i:s", $smsDb['end'] );
            $tmp['attach'] = array( );
            ( );
            $fs = new fs( );
            $tmp['files'] = $fs->getEditList( json_decode( $smsDb['attach'], TRUE ) );
            ( );
            $dataStore = new dataStore( );
            $dataStore->total = 0;
            $dataStore->data = $tmp;
            echo $dataStore->makeJsonData( );
            exit( );
        }
        if ( $task == "add" )
        {
            $data['title'] = strip_tags( getpar( $_POST, "title", "", 1 ) );
            $data['content'] = getpar( $_POST, "content", "", 1, 0 );
            $data['inuse'] = getpar( $_POST, "inuse" );
            $data['inuse'] = $data['inuse'] == "on" ? 1 : 0;
            $sdate = getpar( $_POST, "sdate" );
            $stime = getpar( $_POST, "stime" );
            $edate = getpar( $_POST, "edate" );
            $etime = getpar( $_POST, "etime" );
            $sdate = explode( "-", $sdate );
            $stime = explode( ":", $stime );
            $edate = explode( "-", $edate );
            $etime = explode( ":", $etime );
            if ( is_array( $data['sdate'] ) || is_array( $data['stime'] ) || is_array( $data['edate'] ) || is_array( $data['etime'] ) )
            {
                msg::callback( FALSE, lang( "dateFormatInvald" ) );
            }
            $data['start'] = mktime( $stime[0], $stime[1], $stime[2], $sdate[1], $sdate[2], $sdate[0] );
            $data['end'] = mktime( $etime[0], $etime[1], $etime[2], $edate[1], $edate[2], $edate[0] );
            $data['from_uid'] = $uid;
            $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            ( );
            $fs = new fs( );
            $filesUpload = getpar( $_POST, "filesUpload", array( ) );
            $attch = $fs->add( $filesUpload, 1 );
            $data['attach'] = json_encode( $attch );
            app::loadapp( "communication", "message" )->api_addNewSystemMessage( $data );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 87, $data['title'], lang( "internalCircular" ) );
            msg::callback( TRUE, lang( "successopt" ) );
            exit( );
        }
        if ( $task == "edit" )
        {
            $id = getpar( $_POST, "edit_id", 0 );
            $data['title'] = strip_tags( getpar( $_POST, "title", "", 1 ) );
            $data['content'] = getpar( $_POST, "content", "", 1, 0 );
            $data['inuse'] = getpar( $_POST, "inuse" );
            $data['inuse'] = $data['inuse'] == "on" ? 1 : 0;
            $sdate = getpar( $_POST, "sdate" );
            $stime = getpar( $_POST, "stime" );
            $edate = getpar( $_POST, "edate" );
            $etime = getpar( $_POST, "etime" );
            $sdate = explode( "-", $sdate );
            $stime = explode( ":", $stime );
            $edate = explode( "-", $edate );
            $etime = explode( ":", $etime );
            if ( is_array( $data['sdate'] ) || is_array( $data['stime'] ) || is_array( $data['edate'] ) || is_array( $data['etime'] ) )
            {
                msg::callback( FALSE, lang( "dateFormatInvald" ) );
            }
            $data['start'] = mktime( $stime[0], $stime[1], $stime[2], $sdate[1], $sdate[2], $sdate[0] );
            $data['end'] = mktime( $etime[0], $etime[1], $etime[2], $edate[1], $edate[2], $edate[0] );
            $data['from_uid'] = $uid;
            $attchIds = array( );
            foreach ( $GLOBALS['_POST'] as $key => $v )
            {
                if ( !ereg( "CNOA_SAVED_ATTACH_", $key ) && !( $v == "on" ) )
                {
                    $attchIds[] = preg_replace( "/^CNOA_SAVED_ATTACH_([0-9]{1,10})\$/", "\\1", $key );
                }
            }
            unset( $key );
            unset( $v );
            ( );
            $fs = new fs( );
            $infoO = app::loadapp( "communication", "message" )->api_getSystemMessageById( $id );
            $filesUpload = getpar( $_POST, "filesUpload", array( ) );
            $attch = $fs->edit( $filesUpload, json_decode( $infoO['attach'], FALSE ), 1 );
            $data['attach'] = json_encode( $attch );
            app::loadapp( "communication", "message" )->api_updateSystemMessage( $data, $id );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 87, $data['title'], lang( "internalCircular" ) );
            msg::callback( TRUE, lang( "editSuccess" ) );
            exit( );
        }
        if ( $task == "delete" )
        {
            $ids = getpar( $_POST, "ids", "" );
            $ids = substr( $ids, 0, -1 );
            $list = $CNOA_DB->db_select( "*", "comm_sms_sys", "WHERE `id` IN(".$ids.")" );
            ( );
            $fs = new fs( );
            if ( !is_array( $list ) )
            {
                $list = array( );
            }
            foreach ( $list as $value )
            {
                $fs->deleteFile( json_decode( $value['attach'], TRUE ) );
            }
            $CNOA_DB->db_delete( "comm_sms_sys", "WHERE `id` IN(".$ids.")" );
            msg::callback( TRUE, "删除成功" );
        }
    }

}

?>
