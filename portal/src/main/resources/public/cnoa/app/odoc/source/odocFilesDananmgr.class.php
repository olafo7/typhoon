<?php
//decode by qq2859470

class odocFilesDananmgr extends model
{

    private $t_files_dangan = "odoc_files_dangan";
    private $t_read_file = "odoc_read_file";
    private $t_dangan_room = "odoc_files_dangan_room";
    private $t_anjuan_list = "odoc_files_anjuan_list";
    private $t_type_list = "odoc_setting_word_type";
    private $t_level_list = "odoc_setting_word_level";
    private $t_hurry_list = "odoc_setting_word_hurry";
    private $t_secret_list = "odoc_setting_word_secret";
    private $t_type_permit = "odoc_setting_word_type_permit";
    private $t_odoc_fenfa = "odoc_fenfa";
    private $t_odoc_data = "odoc_data";
    private $f_from = array
    (
        0 => "其他",
        1 => "发文",
        2 => "收文"
    );
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
        case "getJsonData" :
            $this->_getJsonData( );
            break;
        case "roomList" :
            $this->_roomList( );
            break;
        case "anjuanList" :
            $this->_anjuanList( );
            break;
        case "viewFile" :
            $this->_viewFile( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $this->_getAllUserListsInPermitDeptTree( );
            break;
        case "submitIssue" :
            $this->_submitIssue( );
        }
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $start = getpar( $_POST, "start", 0 );
        $WHERE = "WHERE 1 ";
        $storeType = getpar( $_POST, "storeType", "all" );
        $s_title = getpar( $_POST, "title", "" );
        $s_number = getpar( $_POST, "number", "" );
        $s_sort = getpar( $_POST, "sort", 0 );
        $s_type = getpar( $_POST, "type", 0 );
        $s_level = getpar( $_POST, "level", 0 );
        $s_stime = getpar( $_POST, "stime", 0 );
        $s_etime = getpar( $_POST, "etime", 0 );
        if ( !empty( $s_title ) )
        {
            $WHERE .= "AND `title` LIKE '%".$s_title."%' ";
        }
        if ( !empty( $s_number ) )
        {
            $WHERE .= "AND `number` LIKE '%".$s_number."%' ";
        }
        if ( !empty( $s_sort ) )
        {
            $WHERE .= "AND `from` = '".$s_sort."' ";
        }
        if ( !empty( $s_type ) )
        {
            $WHERE .= "AND `danganshi` = '".$s_type."' ";
        }
        if ( !empty( $s_level ) )
        {
            $WHERE .= "AND `anjuan` = '".$s_level."' ";
        }
        if ( !empty( $s_stime ) )
        {
            $s_stime = strtotime( $s_stime );
            $WHERE .= "AND `collectdate` > '".$s_stime."' ";
        }
        if ( !empty( $s_etime ) )
        {
            $s_etime = strtotime( $s_etime );
            $WHERE .= "AND `collectdate` < '".$s_etime."' ";
        }
        if ( $storeType == "all" )
        {
            $dblist = $CNOA_DB->db_select( "*", $this->t_files_dangan, $WHERE.( "ORDER BY `id` DESC LIMIT ".$start.", {$this->rows}" ) );
        }
        else if ( $storeType == "history" )
        {
            $this->__getJsonDataHistory( );
        }
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $typeArr = app::loadapp( "odoc", "common" )->getTypeAllArr( );
        $levelArr = app::loadapp( "odoc", "common" )->getLevelAllArr( );
        $roomArr = app::loadapp( "odoc", "filesSetting" )->api_getRoomData( );
        $anjuanArr = app::loadapp( "odoc", "filesAnjuanmgr" )->api_anjuanArr( );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['from'] = $this->f_from[$v['from']];
            $dblist[$k]['type'] = $typeArr[$v['type']]['title'];
            $dblist[$k]['level'] = $levelArr[$v['level']]['title'];
            $dblist[$k]['danganshi'] = $roomArr[$v['danganshi']]['title'];
            $dblist[$k]['anjuan'] = $anjuanArr[$v['anjuan']]['title'];
            $dblist[$k]['senddate'] = formatdate( $v['senddate'] );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        $dataStore->total = $CNOA_DB->db_getcount( $this->t_files_dangan, $WHERE );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function __getJsonDataHistory( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $WHERE = "WHERE 1 ";
        $start = getpar( $_POST, "start", 0 );
        $uid = $CNOA_SESSION->get( "UID" );
        $dblist = $CNOA_DB->db_select( "*", $this->t_read_file, $WHERE.( "AND `type` = '3' AND `senduid` = '".$uid."' GROUP BY `fileid` LIMIT {$start}, {$this->rows}" ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $idArr = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $idArr[] = $v['fileid'];
        }
        $roomArr = app::loadapp( "odoc", "filesSetting" )->api_getRoomData( );
        $typeArr = app::loadapp( "odoc", "filesSetting" )->api_getTypeData( );
        $wenzhongArr = app::loadapp( "odoc", "filesSetting" )->api_getWenzhongData( );
        $anjuanArr = app::loadapp( "odoc", "filesAnjuanmgr" )->api_anjuanArr( );
        $levelArr = app::loadapp( "odoc", "common" )->getLevelAllArr( );
        $danganArr = $this->api_getAllDanganArr( $idArr );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['title'] = $danganArr[$v['fileid']]['title'];
            $dblist[$k]['number'] = $danganArr[$v['fileid']]['number'];
            $dblist[$k]['from'] = $this->f_from[$danganArr[$v['fileid']]['from']];
            $dblist[$k]['type'] = $typeArr[$danganArr[$v['fileid']]['type']]['title'];
            $dblist[$k]['level'] = $levelArr[$danganArr[$v['fileid']]['level']]['title'];
            $dblist[$k]['danganshi'] = $roomArr[$danganArr[$v['fileid']]['danganshi']]['title'];
            $dblist[$k]['anjuan'] = $anjuanArr[$danganArr[$v['fileid']]['anjuan']]['title'];
            $dblist[$k]['senddate'] = formatdate( $danganArr[$v['fileid']]['senddate'] );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        $dataStore->total = $CNOA_DB->db_getcount( $this->t_read_file, $WHERE.( "AND `senduid` = '".$uid."' GROUP BY `fileid` " ) );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _roomList( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->t_dangan_room );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $dblist;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _anjuanList( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", 0 );
        $dblist = $CNOA_DB->db_select( "*", $this->t_anjuan_list, "WHERE `danganshi` = '".$id."'" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $dblist;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _submitIssue( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $cuid = $CNOA_SESSION->get( "UID" );
        $id = getpar( $_POST, "id" );
        $recvMan = getpar( $_POST, "recvMan" );
        $recvUids = getpar( $_POST, "recvUids" );
        $dangan = $CNOA_DB->db_getone( "*", $this->t_files_dangan, "WHERE `id` = ".$id." " );
        $odocData = $CNOA_DB->db_getone( "*", $this->t_odoc_data, "WHERE `id` = ".$dangan['fromid']." " );
        $argMan = explode( ",", $recvMan );
        $argUid = explode( ",", $recvUids );
        $uFlowId = $odocData['uFlowId'];
        foreach ( $argUid as $uid )
        {
            $data = array( );
            $data['id'] = $id;
            $data['from'] = "borrow";
            $data['uid'] = $uid;
            $data['postuid'] = $cuid;
            $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
            $data['uFlowId'] = $uFlowId;
            $CNOA_DB->db_insert( $data, $this->t_odoc_fenfa );
            $noticeT = "公文档案分发";
            $noticeC = "你有一条公文档案信息分发给您";
            $noticeH = "index.php?app=odoc&func=read&action=borrow";
            notice::add( $uid, $noticeT, $noticeC, $noticeH, 0, 17 );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3004, $recvMan, "分发档案" );
        msg::callback( TRUE, "操作成功." );
    }

    public function api_getAllDanganArr( $idArr )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->t_files_dangan, "WHERE `id` IN (".implode( ",", $idArr ).")" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $k => $v )
        {
            $data[$v['id']] = $v;
            $data[$v['id']]['from'] = $this->f_from[$v['from']];
            $data[$v['id']]['senddate'] = formatdate( $v['senddate'] );
        }
        return $data;
    }

    private function _viewFile( )
    {
        app::loadapp( "odoc", "commonFilesView" )->run( );
        exit( );
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
