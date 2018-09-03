<?php
//decode by qq2859470

class odocReadMeeting extends model
{

    private $t_meeting_fenfa_check = "meeting_mgr_fenfa_check";
    private $t_meeting_fenfa_reader = "meeting_mgr_fenfa_reader";
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
        case "readed" :
            $this->_readed( );
            break;
        case "getReaderList" :
            $this->_getReaderList( );
            break;
        case "viewMeetingRoomApplyDetails" :
            app::loadapp( "meeting", "mgrList" )->api_viewMeetingRoomApplyDetails( );
        }
    }

    private function _loadpage( )
    {
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $storeType = getpar( $_POST, "storeType", "waiting" );
        $start = getpar( $_POST, "start", 0 );
        $WHERE = "WHERE 1 ";
        if ( $storeType == "waiting" )
        {
            $WHERE .= "AND `status` = 0 ";
        }
        else if ( $storeType == "readed" )
        {
            $WHERE .= "AND `status` = 1 ";
        }
        $s_name = getpar( $_POST, "name", "" );
        $s_title = getpar( $_POST, "title", "" );
        $s_stime = getpar( $_POST, "stime", 0 );
        $s_etime = getpar( $_POST, "etime", 0 );
        if ( !empty( $s_name ) )
        {
            $WHERE .= "AND `name` LIKE '%".$s_name."%' ";
        }
        if ( !empty( $s_title ) )
        {
            $WHERE .= "AND `title` LIKE '%".$s_title."%' ";
        }
        if ( !empty( $s_stime ) )
        {
            $s_stime = strtotime( $s_stime." 00:00:00" );
            $WHERE .= "AND `stime` >= '".$s_stime."' ";
        }
        if ( !empty( $s_etime ) )
        {
            $s_etime = strtotime( $s_etime." 23:59:59" );
            $WHERE .= "AND `etime` <= '".$s_etime."' ";
        }
        $uid = $CNOA_SESSION->get( "UID" );
        $dblist = $CNOA_DB->db_select( "*", $this->t_meeting_fenfa_reader, $WHERE.( " AND `uid` = '".$uid."' ORDER BY `rid` DESC LIMIT {$start}, {$this->rows} " ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $appUidArr = array( 0 );
        $markUidArr = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $appUidArr[] = $v['appuid'];
            $markUidArr[] = $v['markuid'];
        }
        $uidArr = array_merge( $appUidArr, $markUidArr );
        $truenameDB = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['stime'] = formatdate( $v['stime'] );
            $dblist[$k]['etime'] = formatdate( $v['etime'] );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _readed( )
    {
        global $CNOA_DB;
        $rid = getpar( $_POST, "rid", 0 );
        $data['readtime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['status'] = 1;
        $CNOA_DB->db_update( $data, $this->t_meeting_fenfa_reader, "WHERE `rid` = '".$rid."'" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getReaderList( )
    {
        global $CNOA_DB;
        $aid = getpar( $_GET, "aid", 0 );
        $dblist = $CNOA_DB->db_select( array( "status", "uid", "readtime" ), $this->t_meeting_fenfa_reader, "WHERE `aid` = '".$aid."' AND (`status` = 0 OR `status` = 1) ORDER BY `status` DESC" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $uidArr = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $uidArr[] = $v['uid'];
        }
        $truenameArr = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr );
        if ( !is_array( $truenameArr ) )
        {
            $truenameArr = array( );
        }
        $deptIdArr = array( 0 );
        foreach ( $truenameArr as $k => $v )
        {
            $deptIdArr[] = $v['deptId'];
        }
        $deptNameArr = app::loadapp( "main", "struct" )->api_getNamesByIds( $deptIdArr );
        $data = array( );
        foreach ( $dblist as $k => $v )
        {
            if ( !empty( $truenameArr[$v['uid']]['truename'] ) )
            {
                $v['deptname'] = $deptNameArr[$truenameArr[$v['uid']]['deptId']];
                if ( $v['status'] == 1 )
                {
                    $v['name'] = "<span class='cnoa_color_red'>".$truenameArr[$v['uid']]['truename']."</span>";
                }
                else
                {
                    $v['name'] = $truenameArr[$v['uid']]['truename'];
                }
                $v['readtime'] = formatdate( $v['readtime'], "Y-m-d H:i" );
                $data[] = $v;
            }
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

}

?>
