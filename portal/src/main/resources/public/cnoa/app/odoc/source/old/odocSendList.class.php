<?php
//decode by qq2859470

class odocSendList extends model
{

    private $t_step = "odoc_step";
    private $t_send_list = "odoc_send_list";
    private $rows = 15;

    public function __construct( )
    {
    }

    public function __destruct( )
    {
    }

    public function run( )
    {
        $task = getpar( $_GET, "task", "loadpage" );
        switch ( $task )
        {
        case "loadpage" :
            $this->_loadpage( );
            break;
        case "getJsonData" :
            $this->_getJsonData( );
            break;
        case "getTypeList" :
            app::loadapp( "odoc", "settingWord" )->api_getTypeList( );
            break;
        case "getLevelList" :
            app::loadapp( "odoc", "settingWord" )->api_getLevelList( );
            break;
        case "getHurryList" :
            app::loadapp( "odoc", "settingWord" )->api_getHurryList( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $this->_getAllUserListsInPermitDeptTree( );
            break;
        case "submitIssue" :
            $this->_submitIssue( );
            break;
        case "submitSign" :
            $this->_submitSignSend( );
            break;
        case "view" :
            $GLOBALS['_POST']['func'] = "send";
            app::loadapp( "odoc", "commonView" )->run( "send" );
            break;
        case "getSignData" :
            $this->_getSignData( );
            break;
        case "getSendStepList" :
            $this->_getSendStepList( );
            break;
        case "getSendReadList" :
            $this->_getSendReadList( );
        }
    }

    private function _getSendReadList( )
    {
        $id = getpar( $_POST, "id" );
        $where = "WHERE `fileid`=".$id." AND `type`=1";
        $dblist = app::loadapp( "odoc", "readSend" )->api_getList( $where );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $info )
        {
            $db =& $data[];
            $uid = $info['receiveuid'];
            $db['dept'] = app::loadapp( "main", "user" )->api_getDeptNameByUid( $uid );
            $db['name'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $uid );
            $db['time'] = formatdate( $info['readtime'], "Y-m-d H:i" );
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        $js = $ds->makeJsonData( );
        echo $js;
        exit( );
    }

    private function _getSendStepList( )
    {
        return app::loadapp( "odoc", "sendCheck" )->api_getSendStepList( );
    }

    private function _getSignData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = getpar( $_POST, "id" );
        $where = "WHERE 1";
        $where .= " AND `id`=".$id;
        $info = $CNOA_DB->db_getone( "*", $this->t_send_list, $where );
        $data = array( );
        $data['title'] = $info['title'];
        $data['dept'] = $info['createdept'];
        $data['print'] = $info['many'];
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        $js = $ds->makeJsonData( );
        echo $js;
        exit( );
    }

    private function __copyDocFile( $srcId, $destId, $other = 1 )
    {
        global $CNOA_DB;
        $srcPath = CNOA_PATH_FILE."/common/odoc/send/".$srcId."/";
        $destPath = CNOA_PATH_FILE."/common/odoc/receive/".$destId."/";
        @mkdir( CNOA_PATH_FILE."/common/odoc/receive/", 448 );
        @mkdir( $destPath, 448 );
        $srcStepid = $CNOA_DB->db_getfield( "id", $this->t_step, "WHERE `fromId`=".$srcId." AND `fromType`=1 ORDER BY `stepid` DESC" );
        $destStepid = $CNOA_DB->db_getfield( "id", $this->t_step, "WHERE `fromId`=".$destId." AND `fromType`=2 ORDER BY `stepid` DESC" );
        if ( $other == 1 )
        {
            @copy( $srcPath."form.history.".$srcStepid.".php", $destPath."form.history.".$destStepid.".php" );
        }
        if ( $other == 2 )
        {
            $destStepid = 0;
        }
        @copy( $srcPath."doc.history.".$srcStepid.".php", $destPath."doc.history.".$destStepid.".php" );
    }

    private function _submitSignSend( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $cuid = $CNOA_SESSION->get( "UID" );
        $id = getpar( $_POST, "id" );
        $uids = getpar( $_POST, "uids" );
        $slInfo = $CNOA_DB->db_getone( "*", $this->t_send_list, "WHERE `id`=".$id );
        $tempid = $slInfo['tempid'];
        $number = $slInfo['number'];
        $form = $slInfo['form'];
        $formdata = $slInfo['formdata'];
        $attach = $slInfo['attach'];
        $type = $slInfo['type'];
        $level = $slInfo['level'];
        $hurry = $slInfo['hurry'];
        $fromdept = getpar( $_POST, "dept" );
        $title = getpar( $_POST, "title" );
        $type = $type;
        $many = getpar( $_POST, "print" );
        $recvdate = $GLOBALS['CNOA_TIMESTAMP'];
        $data = array( );
        $data['status'] = 3;
        $CNOA_DB->db_update( $data, $this->t_send_list, "WHERE `id`=".$id );
        $argUid = explode( ",", $uids );
        foreach ( $argUid as $uid )
        {
            $recvdept = "";
            $arg = array( );
            $arg['uid'] = $uid;
            $arg['title'] = $title;
            $arg['number'] = $number;
            $arg['fromdept'] = $fromdept;
            $arg['many'] = $many;
            $arg['attach'] = $attach;
            $arg['level'] = $level;
            $arg['hurry'] = $hurry;
            $arg['createuid'] = $uid;
            $arg['createtime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $rid = app::loadapp( "odoc", "receiveApply" )->api_addRecvDoc( $arg );
            $this->__copyDocFile( $id, $rid, 2 );
            $this->__sendNotice( $uid, $title, 1, 5 );
        }
        $man = app::loadapp( "main", "user" )->api_getUserNamesByUids( $argUid );
        $argMan = "";
        foreach ( $man as $v )
        {
            $argMan .= "{$v['truename']}, ";
        }
        $argMan = substr( $argMan, 0, -2 );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3004, $argMan, "签发发文（".$title."）" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _submitIssue( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $cuid = $CNOA_SESSION->get( "UID" );
        $id = getpar( $_POST, "id" );
        $recvMan = getpar( $_POST, "recvMan" );
        $recvUids = getpar( $_POST, "recvUids" );
        $sendInfo = $CNOA_DB->db_getone( "*", $this->t_send_list, "WHERE `id`=".$id );
        $send_title = $sendInfo['title'];
        $data = array( );
        $data['status'] = 3;
        $CNOA_DB->db_update( $data, $this->t_send_list, "WHERE `id`=".$id );
        $argMan = explode( ",", $recvMan );
        $argUid = explode( ",", $recvUids );
        foreach ( $argUid as $uid )
        {
            $data = array( );
            $data['fileid'] = $id;
            $data['type'] = 1;
            $data['receiveuid'] = $uid;
            $data['senduid'] = $cuid;
            $data['sendtime'] = $GLOBALS['CNOA_TIMESTAMP'];
            app::loadapp( "odoc", "readSend" )->api_addRead( $data );
            $this->__sendNotice( $uid, $send_title, 1, 3 );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3004, $recvMan, "分发发文".$send_title );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function __sendNotice( $uid, $title = "", $type = 1, $to = 1 )
    {
        app::loadapp( "odoc", "sendCheck" )->api_sendNotice( $uid, $title, $type, $to );
    }

    private function _getAllUserListsInPermitDeptTree( )
    {
        $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
        $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
        echo json_encode( $userList );
        exit( );
    }

    private function _loadpage( )
    {
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $start = getpar( $_POST, "start", 0 );
        $status = getpar( $_POST, "status", 1 );
        $WHERE = "WHERE 1 AND (`f`.`createuid`='".$uid."' OR (`s`.`uid`='{$uid}' AND `s`.`status`=2 AND `s`.`stepType`=1 AND `s`.`fromType`=1)) ";
        $s_title = getpar( $_POST, "title", "" );
        $s_number = getpar( $_POST, "number", "" );
        $s_type = getpar( $_POST, "type", 0 );
        $s_level = getpar( $_POST, "level", 0 );
        $s_hurry = getpar( $_POST, "hurry", 0 );
        if ( !empty( $s_title ) )
        {
            $WHERE .= "AND `f`.`title` LIKE '%".$s_title."%' ";
        }
        if ( !empty( $s_number ) )
        {
            $WHERE .= "AND `f`.`number` LIKE '%".$s_number."%' ";
        }
        if ( !empty( $s_type ) )
        {
            $WHERE .= "AND `f`.`type` = '".$s_type."' ";
        }
        if ( !empty( $s_level ) )
        {
            $WHERE .= "AND `f`.`level` = '".$s_level."' ";
        }
        if ( !empty( $s_hurry ) )
        {
            $WHERE .= "AND `f`.`hurry` = '".$s_hurry."' ";
        }
        switch ( intval( $status ) )
        {
        case 1 :
            $WHERE .= " AND `f`.`status`=2 ";
            break;
        case 2 :
            $WHERE .= " AND `f`.`status` IN (3,4) ";
        }
        $sql2 = "SELECT DISTINCT `f`.`id`,`f`.* FROM ".tname( $this->t_send_list )." AS `f` LEFT JOIN ".tname( $this->t_step )." AS `s` ON `f`.`id`=`s`.`fromId` ".$WHERE;
        $totalDb = $CNOA_DB->get_one( $sql2 );
        $totalNum = $totalDb['num'];
        $sql = "SELECT DISTINCT `f`.`id`,`f`.* FROM ".tname( $this->t_send_list )." AS `f` LEFT JOIN ".tname( $this->t_step )." AS `s` ON `f`.`id`=`s`.`fromId` ".$WHERE.( "ORDER BY `f`.`id` DESC LIMIT ".$start.",{$this->rows} " );
        $flowlist = $CNOA_DB->query( $sql );
        $dblist = array( );
        while ( $item = $CNOA_DB->get_array( $flowlist ) )
        {
            $dblist[] = $item;
        }
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $typeArr = app::loadapp( "odoc", "settingWord" )->api_getTypeAllArr( );
        $levelArr = app::loadapp( "odoc", "settingWord" )->api_getLevelAllArr( );
        $hurryArr = app::loadapp( "odoc", "settingWord" )->api_getHurryAllArr( );
        foreach ( $dblist as $k => $v )
        {
            $db =& $dblist[$k];
            $db['type'] = $typeArr[$v['type']]['title'];
            $db['level'] = $levelArr[$v['level']]['title'];
            $db['hurry'] = $hurryArr[$v['hurry']]['title'];
            $db['createtime'] = formatdate( $v['createtime'], "Y-m-d H:i" );
            $db['status'] = $this->arrSendStatus[$dblist[$k]['status']];
            $db['formdata'] = "";
            $db['form'] = "";
            $db['senddate'] = formatdate( $v['senddate'], "Y-m-d H:i" );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        $dataStore->total = $totalNum;
        echo $dataStore->makeJsonData( );
        exit( );
    }

}

?>
