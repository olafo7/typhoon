<?php
//decode by qq2859470

class newsBbsMypost extends newsBbs
{

    public function run( )
    {
        $task = getpar( $_GET, "task", "loadPage" );
        switch ( $task )
        {
        case "loadPage" :
            global $CNOA_CONTROLLER;
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/bbs/bbs_mypost.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            break;
        case "getIndex" :
            app::loadapp( "news", "bbsIndex" )->run( );
            break;
        case "getForumTree" :
            $this->_getForumTree( );
            break;
        case "getPostList" :
            $this->_getPostList( );
            break;
        case "deletePost" :
            $this->_deletePost( );
        }
    }

    private function _getForumTree( $fid = 0 )
    {
        global $CNOA_DB;
        $dbList = $CNOA_DB->db_select( "*", $this->table_forum, "WHERE `fid`='".$fid."'" );
        if ( empty( $fid ) )
        {
            $type = getpar( $_GET, "type", "" );
        }
        $list = array( );
        foreach ( $dbList as $v )
        {
            $r = array( );
            $r['text'] = $v['name'];
            $r['name'] = $v['name'];
            $r['type'] = $v['name'];
            $r['forumId'] = $v['id'];
            $r['iconCls'] = "icon-style-page-key";
            $r['href'] = "javascript:void(0);";
            if ( $type == "all" )
            {
                $r['leaf'] = FALSE;
                $r['children'] = $this->_getForumTree( $v['id'] );
                $r['expanded'] = TRUE;
            }
            else
            {
                $r['leaf'] = TRUE;
            }
            $list[] = $r;
        }
        if ( !empty( $fid ) )
        {
            return $list;
        }
        echo json_encode( $list );
        exit( );
    }

    private function _getPostList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $where = "WHERE 1 ";
        $forumId = getpar( $_POST, "forumId", 0 );
        if ( !empty( $forumId ) )
        {
            $result = $CNOA_DB->db_select( array( "id" ), $this->table_forum, "WHERE `fid`='".$forumId."'" );
            if ( !empty( $result ) )
            {
                $ids = "";
                foreach ( $result as $value )
                {
                    $ids .= $value['id'].",";
                }
                $ids = substr( $ids, 0, -1 );
                $where .= "AND `fid` IN (".$ids.") ";
            }
            else
            {
                $where .= "AND `fid`='".$forumId."' ";
            }
        }
        $start = getpar( $_POST, "start", 0 );
        if ( isset( $_GET['user'] ) )
        {
            $uid = $CNOA_SESSION->get( "UID" );
            $limit = getpagesize( "news_bbs_setting_myPostList" );
        }
        else
        {
            $uid = getpar( $_POST, "uid" );
            $limit = getpagesize( "news_bbs_setting_postList" );
        }
        if ( !empty( $uid ) )
        {
            $where .= "AND `uid`='".$uid."' ";
        }
        $title = getpar( $_POST, "title" );
        if ( !empty( $title ) )
        {
            $where .= "AND `title` LIKE '%".$title."%' ";
        }
        $total = $CNOA_DB->db_getcount( $this->table_post, $where );
        $where .= "ORDER BY posttime DESC LIMIT ".$start.", {$limit}";
        $data = $CNOA_DB->db_select( array( "id", "uid", "title", "posttime", "reply", "browse" ), $this->table_post, $where );
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        foreach ( $data as $key => $value )
        {
            $data[$key]['author'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $value['uid'] );
            $data[$key]['posttime'] = date( "Y-m-d h:i:s", $value['posttime'] );
        }
        ( );
        $ds = new dataStore( );
        $ds->total = $total;
        $ds->data = $data;
        echo $ds->makeJsonData( );
    }

    private function _deletePost( $ids = "", $return = FALSE )
    {
        global $CNOA_DB;
        if ( empty( $ids ) )
        {
            $ids = getpar( $_POST, "ids" );
        }
        if ( !empty( $ids ) )
        {
            $CNOA_DB->db_delete( $this->table_post, "WHERE `id` IN (".$ids.")" );
            $this->_deleteReply( $ids, ture );
        }
        if ( $return )
        {
            return;
        }
        msg::callback( TRUE, "成功" );
    }

}

?>
