<?php
//decode by qq2859470

class noticeNoticeTodo extends model
{

    private $t_menu_list = "system_notice_menu_list";
    private $t_notice = "system_notice_list";
    private $t_calendar = "system_notice_calendar";
    private $t_color_type = "system_notice_calendar_color";
    private $t_calendar_share = "system_notice_calendar_share";
    private $rows = 15;

    public function run( )
    {
        $task = getpar( $_GET, "task", "loadpage" );
        switch ( $task )
        {
        case "loadpage" :
            $this->_loadpage( );
            exit( );
        case "getTodoList" :
            $this->_getTodoList( );
            exit( );
        case "getAllMenuList" :
            $this->_getAllMenuList( );
            exit( );
        case "getModuleList" :
            $this->_getModuleList( );
            exit( );
        case "saveTodoModel" :
            $this->_saveTodoModel( );
            exit( );
        case "refreshTodoTotal" :
            $this->_refreshTodoTotal( );
            exit( );
        case "getColorData" :
            app::loadapp( "notice", "noticeCalendar" )->api_getColorData( );
            exit( );
        case "addCalendar" :
            app::loadapp( "notice", "noticeCalendar" )->api_addCalendar( );
            exit( );
        case "setDirect" :
            $this->_setDirect( );
            exit( );
        case "deleteTodo" :
            $this->_deleteTodo( );
            exit( );
        case "getAllUserListsInPermitDeptTree" :
            $this->_getAllUserListsInPermitDeptTree( );
        }
        exit( );
    }

    private function _loadpage( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        $from = getpar( $_GET, "from", "" );
        $other = getpar( $_GET, "other", "no" );
        $GLOBALS['GLOBALS']['CNOA_NOTICE_TODO'] = $from;
        $GLOBALS['GLOBALS']['CNOA_NOTICE_TODO_OTHER'] = $other;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/notice/todo.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    private function _getTodoList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $where = array( );
        $where[] = "touid=".$uid;
        $storeType = getpar( $_POST, "storeType", "todo" );
        if ( $storeType == "todo" )
        {
            $where[] = "readed=0";
        }
        else
        {
            $where[] = "readed=1";
        }
        $content = getpar( $_POST, "content" );
        if ( !empty( $content ) )
        {
            $where[] = "content LIKE '%".$content."%'";
        }
        $stime = getpar( $_POST, "stime" );
        $etime = getpar( $_POST, "etime" );
        $now = $GLOBALS['CNOA_TIMESTAMP'];
        if ( !empty( $stime ) )
        {
            $stime = strtotime( "{$stime} 00:00:00" );
            $where[] = "fromtime >= ".$stime;
        }
        if ( !empty( $etime ) )
        {
            $etime = strtotime( "{$etime} 23:59:59" );
            if ( $now < $etime )
            {
                $etime = $now;
            }
        }
        else
        {
            $etime = $now;
        }
        $where[] = "fromtime <= ".$etime;
        $module = intval( getpar( $_POST, "module" ) );
        if ( !empty( $module ) )
        {
            $where[] = "n.from = ".$module;
        }
        $where = implode( " AND ", $where );
        $start = intval( getpar( $_POST, "start" ) );
        $limit = intval( getpar( $_POST, "rows", 5 ) );
        if ( $module == 0 )
        {
            $todoModule = $CNOA_DB->db_getone( array( "mids" ), $this->t_menu_list, "WHERE uid=".$uid );
            if ( is_array( $todoModule ) && !empty( $todoModule['mids'] ) )
            {
                $where .= " AND n.from IN (".$todoModule['mids'].")";
            }
            $limit = getpagesize( "noticeIndex" );
            $sql = "SELECT substring_index(group_concat(nid ORDER BY nid DESC), ',', ".$limit.") AS ids FROM ".tname( $this->t_notice )." AS n ".( "WHERE ".$where." GROUP BY n.from" );
            $result = $CNOA_DB->query( $sql );
            $nid = array( );
            while ( $row = $CNOA_DB->get_array( $result ) )
            {
                $nid[] = $row['ids'];
            }
            if ( !empty( $nid ) )
            {
                $nid = implode( ",", $nid );
                $sql = "SELECT n.nid, n.funname, n.title, n.content, n.from, n.fromtime, n.href, n.href2, n.readed FROM ".tname( $this->t_notice )." AS n ".( "WHERE nid IN (".$nid.") ORDER BY `nid` DESC" );
                $result = $CNOA_DB->query( $sql );
            }
        }
        else
        {
            $limit = getpagesize( "noticePage" );
            $sql = "SELECT n.nid, n.funname, n.title, n.content, n.from, n.fromtime, n.href, n.href2, n.readed FROM ".tname( $this->t_notice )." AS n ".( "WHERE ".$where." ORDER BY `nid` DESC LIMIT {$start}, {$limit}" );
            $result = $CNOA_DB->query( $sql );
        }
        include( CNOA_PATH."/core/inc/notice.conf.php" );
        $moduleInfo = $GLOBALS['CNOA_NOTICE_FROM'];
        $data = array( );
        while ( $row = $CNOA_DB->get_array( $result ) )
        {
            $item = array( );
            $item['nid'] = $row['nid'];
            $item['content'] = strip_tags( $row['content'] );
            if ( empty( $item['content'] ) )
            {
                $item['content'] = strip_tags( $row['title'] )."<span style='color:#FFF'>[标题]</span>";
            }
            $item['module'] = $moduleInfo[$row['from']]['name'];
            $item['from'] = $row['from'];
            if ( $storeType == "todo" )
            {
                $item['dwell'] = timeformat2( $GLOBALS['CNOA_TIMESTAMP'] - $row['fromtime'] );
            }
            else
            {
                $item['dwell'] = timeformat2( $row['readtime'] - $row['fromtime'] );
            }
            $item['href'] = !$row['readed'] == 1 && !empty( $row['href2'] ) ? $row['href2'] : $row['href'];
            if ( strpos( $row['href'], "index.php?app=flow&func=flow&action=user&task=loadPage&from=viewflow" ) === 0 && $row['readed'] == 1 )
            {
                $item['href'] = str_replace( "viewflow", "showflow", $row['href'] );
            }
            if ( $row['from'] == 31 )
            {
                $arr = explode( "&", $row[href] );
                $arr2 = explode( "=", $arr[6] );
                $item['level'] = empty( $arr2[1] ) ? 0 : $CNOA_DB->db_getfield( "level", "wf_u_flow", "WHERE `uFlowId`=".$arr2[1]." " );
                if ( $item['level'] == 2 )
                {
                    $item['content'] .= "<span style=\"color:red;\">　[非常重要]</span>";
                }
                if ( $item['level'] == 1 )
                {
                    $item['content'] .= "<span style=\"color:orange;\">　[重要]</span>";
                }
            }
            $data[$row['from']][] = $item;
        }
        if ( !empty( $data[31] ) )
        {
            foreach ( $data[31] as $key => $row )
            {
                $volume[$key] = $row['level'];
                $volume2[$key] = $row['nid'];
            }
            array_multisort( $volume, SORT_DESC, $volume2, SORT_DESC, $data[31] );
        }
        $sql = "SELECT count(*) AS count FROM ".tname( $this->t_notice ).( " AS n WHERE ".$where );
        $total = $CNOA_DB->get_one( $sql );
        $sql = "SELECT count(*) AS count FROM ".tname( $this->t_notice ).( " AS n WHERE `readed` = 0 AND `touid`=".$uid." AND fromtime<='{$GLOBALS['CNOA_TIMESTAMP']}'" ).( $module == 0 ? "" : " AND n.from=".$module );
        $todoTotal = $CNOA_DB->get_one( $sql );
        ( );
        $ds = new dataStore( );
        $ds->data = $module == 0 ? $data : $data[$module];
        $ds->total = $total['count'];
        $ds->limit = intval( $limit );
        $ds->todoTotal = $todoTotal['count'];
        if ( !empty( $todoModule['mids'] ) )
        {
            $ds->orderby = $todoModule['mids'];
        }
        echo $ds->makeJsonData( );
    }

    private function _getAllMenuList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $data = array( );
        $todoTotal = $this->_refreshTodoTotal( TRUE );
        include( CNOA_PATH."/core/inc/notice.conf.php" );
        $menu = $GLOBALS['CNOA_NOTICE_FROM'];
        foreach ( $menu as $k => $v )
        {
            if ( !( $v['unshow'] == 1 ) )
            {
                $data[] = array(
                    "mid" => $k,
                    "name" => $v['name'],
                    "total" => intval( $todoTotal[$k] ),
                    "iconCls" => $v['icon']
                );
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
    }

    private function _getModuleList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        include( CNOA_PATH."/core/inc/notice.conf.php" );
        $module = $GLOBALS['CNOA_NOTICE_FROM'];
        $data = array( );
        $order = $CNOA_DB->db_getone( array( "mids" ), $this->t_menu_list, "WHERE uid=".$uid );
        if ( is_array( $order ) )
        {
            $mids = explode( ",", $order['mids'] );
            foreach ( $mids as $mid )
            {
                $item = array( );
                $item['mid'] = $mid;
                $item['name'] = $module[$mid]['name'];
                $data[] = $item;
                unset( $module[$mid] );
            }
        }
        foreach ( $module as $k => $v )
        {
            if ( !( $v['unshow'] == 1 ) )
            {
                $item = array( );
                $item['mid'] = $k;
                $item['name'] = $v['name'];
                $data[] = $item;
            }
        }
        echo json_encode( $data );
    }

    private function _saveTodoModel( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $mids = getpar( $_POST, "mids" );
        $sql = "INSERT INTO ".tname( $this->t_menu_list ).( " (uid, mids) VALUES (".$uid.", '{$mids}')  ON DUPLICATE KEY UPDATE mids='{$mids}'" );
        $CNOA_DB->query( $sql );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _refreshTodoTotal( $return = FALSE )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $todoTotal = array( );
        $sql = "SELECT count(*) AS count, n.from FROM ".tname( $this->t_notice ).( " AS n WHERE n.touid=".$uid." AND n.readed=0 AND fromtime<='{$GLOBALS['CNOA_TIMESTAMP']}' GROUP BY n.from " );
        $result = $CNOA_DB->query( $sql );
        while ( $row = $CNOA_DB->get_array( $result ) )
        {
            $todoTotal[$row['from']] = intval( $row['count'] );
            $todoTotal['counts'] += intval( $row['count'] );
        }
        if ( $return )
        {
            return $todoTotal;
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $todoTotal;
        $ds->total = count( $todoTotal );
        echo $ds->makeJsonData( );
    }

    private function _setDirect( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $data['uid'] = $CNOA_SESSION->get( "UID" );
        $data['stime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['etime'] = $GLOBALS['CNOA_TIMESTAMP'] + 18000;
        $data['nid'] = getpar( $_POST, "nid", 0 );
        $calendar = $CNOA_DB->db_getone( "*", $this->t_calendar, "WHERE `nid` = '".$data['nid']."' " );
        if ( !empty( $calendar ) )
        {
            msg::callback( FALSE, lang( "calendarExist" )."[".formatdate( $calendar['stime'], "Y-m-d H:i" )." ~ ".formatdate( $calendar['etime'], "Y-m-d H:i" )."]" );
        }
        $data['title'] = getpar( $_POST, "title", "" );
        $data['colorid'] = 1;
        $cid = $CNOA_DB->db_insert( $data, $this->t_calendar );
        $url = "index.php?app=notice&func=notice&action=calendar&from=notice&cid=".$cid;
        notice::add( $data['uid'], $data['title'], "", $url, $data['etime'], 28, $cid, 0 );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getAllUserListsInPermitDeptTree( )
    {
        $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
        $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
        echo json_encode( $userList );
        exit( );
    }

    private function _deleteTodo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $nid = intval( getpar( $_POST, "nid" ) );
        $CNOA_DB->db_delete( $this->t_notice, "WHERE `touid`=".$uid." AND `nid`={$nid} " );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function api_getJsonData( )
    {
        $this->_getTodoList( );
    }

}

?>
