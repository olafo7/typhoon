<?php
//decode by qq2859470

class mainStruct extends model
{

    private $table_struct = "main_struct";
    private $treeData = array( );
    private $treeList = array( );

    public function actionDefault( )
    {
        global $CNOA_CONTROLLER;
        $CNOA_CONTROLLER->action = "list";
        $this->actionList( );
    }

    public function actionList( )
    {
        global $CNOA_DB;
        global $CNOA_PERMIT;
        global $CNOA_SESSION;
        $this->_setHeaderType( "iframe" );
        $myJobType = $CNOA_SESSION->get( "JOBTYPE" );
        $task = getpar( $_GET, "task", NULL );
        if ( $task == "getStructTree" )
        {
            echo $this->_getStructNodeForTreeJson( );
            exit( );
        }
        $permissionList = array( "list", "add", "edit", "delete" );
        foreach ( $permissionList as $v )
        {
            $GLOBALS['GLOBALS']['permissionController'][$v] = in_array( "main_struct_".$v, $GLOBALS['user']['permitArray'] ) ? 1 : 0;
        }
    }

    public function actionAdd( )
    {
        global $CNOA_DB;
        global $CNOA_PERMIT;
        global $CNOA_SESSION;
        $task = getpar( $_GET, "task", NULL );
        $myJobType = $CNOA_SESSION->get( "JOBTYPE" );
        if ( $task == "getBrotherList" )
        {
            $fid = getpar( $_GET, "fid", 0 );
            ( );
            $dataStore = new dataStore( );
            $dataStore->total = 0;
            $dataStore->data = $this->_getBortherList_ByFid( $fid, "add" );
            echo $dataStore->makeJsonData( );
            exit( );
        }
        if ( $task == "getStructTree" )
        {
            echo $this->_getStructNodeForTreeJson( );
            exit( );
        }
        $name = getpar( $_POST, "name", "" );
        $fid = getpar( $_POST, "fid", 0 );
        $about = getpar( $_POST, "about", "" );
        $sortAction = getpar( $_POST, "edit_radio", "last" );
        $broId = getpar( $_POST, "broId", 0 );
        if ( $fid == 0 )
        {
            msg::callback( FALSE, lang( "illegalOperation" ) );
        }
        if ( !$CNOA_PERMIT->check( $fid ) )
        {
            msg::callback( FALSE, lang( "noPermit" ) );
        }
        $data = array(
            "name" => $name,
            "fid" => $fid,
            "about" => $about
        );
        $insert_id = $CNOA_DB->db_insert( $data, "main_struct" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 83, $name, "组织结构" );
        if ( $fid != 0 )
        {
            @extract( @$CNOA_DB->get_one( "SELECT `path` AS `parent_path` FROM ".@tname( "main_struct" ).( "WHERE `id`='".$fid."'" ) ) );
            $path = $parent_path.",".$insert_id;
            $this->_permitAreaDownExtends( $fid, $insert_id );
        }
        else
        {
            $path = $insert_id;
        }
        $CNOA_DB->db_update( array(
            "path" => $path
        ), "main_struct", "WHERE `id`='".$insert_id."'" );
        $this->_sortOrder( $sortAction, $insert_id, $fid, $broId );
        $isOpenWeChat = $CNOA_DB->db_getfield( "isOpen", "wechat_config", "WHERE `id`=1" );
        if ( $isOpenWeChat == "1" )
        {
            $data['order'] = $CNOA_DB->db_getfield( "order", "main_struct", "WHERE `id`='".$insert_id."'" );
            $data['id'] = $insert_id;
            app::loadapp( "wechat", "setting" )->api_createDept( $data );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function actionEdit( )
    {
        global $CNOA_DB;
        global $CNOA_PERMIT;
        global $CNOA_SESSION;
        $tryversion = FALSE;
        $task = getpar( $_GET, "task", NULL );
        $myJobType = $CNOA_SESSION->get( "JOBTYPE" );
        if ( $task == "loadData" )
        {
            $sid = getpar( $_POST, "id", "" );
            if ( !$CNOA_PERMIT->check( $sid ) )
            {
                msg::callback( FALSE, lang( "noPermit" ) );
            }
            ( );
            $dataStore = new dataStore( );
            $data = $CNOA_DB->db_getone( array( "id", "name", "fid", "about" ), "main_struct", "WHERE `id`=".$sid );
            $dataStore->total = 0;
            $dataStore->data = $data;
            $GLOBALS['GLOBALS']['data'] = json_encode( array(
                "success" => TRUE,
                "data" => $data
            ) );
            echo $dataStore->makeJsonData( );
            exit( );
        }
        if ( $task == "submit" )
        {
            $name = getpar( $_POST, "name", "" );
            $id = getpar( $_POST, "id", "" );
            $fid = getpar( $_POST, "fid", 0 );
            $about = getpar( $_POST, "about", "" );
            $sortAction = getpar( $_POST, "edit_radio", "last" );
            $broId = getpar( $_POST, "broId", 0 );
            if ( $tryversion )
            {
            }
            $fpath = "";
            if ( $fid == $id )
            {
                msg::callback( FALSE, lang( "notMoveItself" ) );
            }
            $info = $CNOA_DB->get_one( "SELECT `id`,`path`,`fid` FROM ".tname( "main_struct" ).( "WHERE `id`='".$id."'" ) );
            $this->treeList[] = $info;
            if ( $info['fid'] != $fid && $CNOA_SESSION->get( "DID" ) != $info['id'] )
            {
                $allSonNodes = $CNOA_DB->db_select( array( "id", "path" ), "main_struct", "WHERE FIND_IN_SET(".$id.", `path`)" );
                $targetPath = $CNOA_DB->db_getfield( "path", "main_struct", "WHERE `id`=".$fid );
                $clipLen = strlen( $info['path'] ) - strlen( $id ) - 1;
                if ( !is_array( $allSonNodes ) )
                {
                    $allSonNodes = array( );
                }
                $values = array( );
                foreach ( $allSonNodes as $v )
                {
                    if ( $v['id'] == $fid )
                    {
                        msg::callback( FALSE, lang( "superiorNotMove" ) );
                        exit( );
                    }
                    $v['path'] = $targetPath.substr( $v['path'], $clipLen );
                    $values[] = "(".$v['id'].", '{$v['path']}')";
                }
                $values = implode( ",", $values );
                $sql = "INSERT INTO ".tname( "main_struct" ).( " (`id`, `path`) VALUES ".$values." ON DUPLICATE KEY UPDATE `path`=VALUES(`path`)" );
                $CNOA_DB->query( $sql );
            }
            $CNOA_DB->db_update( array(
                "fid" => $fid,
                "about" => $about,
                "name" => $name
            ), "main_struct", "WHERE `id`=".$id );
            if ( $CNOA_SESSION->get( "DID" ) != $id )
            {
                $this->_sortOrder( $sortAction, $id, $fid, $broId );
            }
            $isOpenWeChat = $CNOA_DB->db_getfield( "isOpen", "wechat_config", "WHERE `id`=1" );
            if ( $isOpenWeChat == "1" )
            {
                $order = $CNOA_DB->db_getfield( "order", "main_struct", "WHERE `id`='".$id."'" );
                app::loadapp( "wechat", "setting" )->api_updateDept( $id, array(
                    "fid" => $fid,
                    "order" => $order,
                    "name" => $name
                ) );
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 83, $name, lang( "struct" ) );
            msg::callback( TRUE, lang( "editSuccess" ) );
            exit( );
        }
        if ( $task == "getBrotherList" )
        {
            $fid = getpar( $_GET, "fid", 0 );
            ( );
            $dataStore = new dataStore( );
            $dataStore->total = 1;
            $dataStore->data = $this->_getBortherList_ByFid( $fid, "edit" );
            echo $dataStore->makeJsonData( );
            exit( );
        }
        if ( $task == "getStructTree" )
        {
            echo $this->_getStructNodeForTreeJson( );
            exit( );
        }
    }

    public function actionDelete( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = getpar( $_POST, "id", 0 );
        $myJobType = $CNOA_SESSION->get( "JOBTYPE" );
        if ( $id != 0 && $id != 1 )
        {
            $sonNodes = $CNOA_DB->db_getone( array( "id" ), "main_struct", "WHERE `fid`='".$id."'" );
            if ( $sonNodes !== FALSE )
            {
                msg::callback( FALSE, lang( "deptHasChildDept" ) );
            }
            $userInfo = app::loadapp( "main", "user" )->api_getUserByFid( $id );
            if ( $userInfo !== FALSE )
            {
                msg::callback( FALSE, lang( "deptHasStaff" ) );
            }
            $jobList = array( );
            $jobListDb = app::loadapp( "main", "job" )->api_getJobListByFid( $id );
            if ( is_array( $jobListDb ) && 0 < count( $jobListDb ) )
            {
                foreach ( $jobListDb as $v )
                {
                    $jobList[] = $v['id'];
                }
                unset( $jobListDb );
                $parttimeDb = app::loadapp( "main", "user" )->api_getPartTimeJobListByJid( $jobList );
                if ( is_array( $parttimeDb ) && 0 < count( $parttimeDb ) )
                {
                    unset( $parttimeDb );
                    unset( $jobList );
                    msg::callback( FALSE, lang( "deptHasParttimeJob" ) );
                }
            }
            app::loadapp( "main", "job" )->api_deleteJobsByFid( $id );
            app::loadapp( "main", "job" )->api_deleteCustomPermitByDeptId( $id );
            app::loadapp( "main", "user" )->api_deleteCustomPermitByDeptId( $id );
            $name = $CNOA_DB->db_getfield( "name", "main_struct", "WHERE `id`='".$id."'" );
            $CNOA_DB->db_delete( "main_struct", "WHERE `id`='".$id."'" );
            $CNOA_DB->db_delete( "budget_set_budget", "WHERE `deptId`='".$id."'" );
            $isOpenWeChat = $CNOA_DB->db_getfield( "isOpen", "wechat_config", "WHERE `id`=1" );
            if ( $isOpenWeChat == "1" )
            {
                app::loadapp( "wechat", "setting" )->api_deleteDept( $id, $name );
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 83, $name, lang( "struct" ) );
            msg::callback( TRUE, lang( "delSuccess" ) );
        }
        else
        {
            msg::callback( FALSE, lang( "delFail" ) );
        }
    }

    public function _getStructTreeData( $leval = 0 )
    {
        global $CNOA_DB;
        if ( $leval == 1 )
        {
            $whereFids = " AND `fid`=0 ";
        }
        else if ( $leval == 2 )
        {
            $whereFids = " AND `fid` IN (0,1) ";
        }
        else
        {
            $whereFids = "";
        }
        $condition = "1";
        $sql = "SELECT * FROM ".tname( "main_struct" ).( " WHERE ".$condition." {$whereFids} ORDER BY `path` ASC" );
        $query = $CNOA_DB->query( $sql );
        if ( $CNOA_DB->num_rows( $query ) == 0 )
        {
            return;
        }
        $data = array( );
        $temp = array( );
        while ( $rs = $CNOA_DB->get_array( $query ) )
        {
            $data[] = $rs;
        }
        $tmp1 = array( );
        foreach ( $data as $k1 => $v1 )
        {
            if ( $GLOBALS['user']['permitArea']['area'] == "all" )
            {
                $v1['permit'] = 1;
            }
            else if ( in_array( $v1['id'], $GLOBALS['user']['permitArea']['area'] ) )
            {
                $v1['permit'] = 1;
            }
            else
            {
                $v1['permit'] = 0;
            }
            $tmp1[$v1['fid']][] = $v1;
        }
        foreach ( $tmp1 as $k2 => $v2 )
        {
            $tmp1[$k2] = $this->_sortChildrenArray( $v2 );
        }
        $this->treeData = $tmp1[0];
        array_shift( &$tmp1 );
        $this->_makeRealArray( $tmp1 );
        unset( $tmp1 );
        unset( $k1 );
        unset( $k2 );
        unset( $v1 );
        unset( $v2 );
        return $this->treeData;
    }

    private function _sortChildrenArray( $arr )
    {
        foreach ( $arr as $key => $row )
        {
            $fid[$key] = $row['fid'];
            $order[$key] = $row['order'];
        }
        array_multisort( $fid, SORT_ASC, $order, SORT_ASC, $arr );
        return $arr;
    }

    private function _makeRealArray( &$arr )
    {
        if ( is_array( $arr ) )
        {
            $data = $this->treeData;
            foreach ( $data as $k => $v )
            {
                if ( $v['id'] == $arr[0][0]['fid'] )
                {
                    $this->treeData = array_push_after( $this->treeData, $arr[0], $k );
                    array_shift( &$arr );
                    $this->_makeRealArray( $arr );
                }
            }
        }
    }

    public function _getStructNodeForTreeArray( $leval = 0 )
    {
        $data = $this->_getStructTreeData( $leval );
        $temp = array( );
        if ( !is_array( $data ) )
        {
            return;
        }
        $count = count( $data );
        foreach ( $data as $v )
        {
            $temp['id'] = "CNOA_main_struct_list_tree_node_".$v['id'];
            $temp['selfid'] = $v['id'];
            $temp['deptId'] = $v['id'];
            $temp['permit'] = $v['permit'];
            $temp['text'] = $v['name'];
            $temp['iconCls'] = $v['id'] == 1 ? "icon-tree-root-cnoa" : "icon-style-page-key";
            $temp['cls'] = "cls";
            $temp['href'] = "javascript:void(0);";
            $temp['leaf'] = TRUE;
            eval( "\$arr[\"".str_replace( ",", "\"][\"children\"][\"", $v['path'] )."\"] = \$temp;" );
        }
        $this->_makeArray( $arr );
        $arr = $this->_clearNode( $arr );
        return $arr;
    }

    public function _getStructNodeForTreeJson( $leval = 0 )
    {
        $arr = $this->_getStructNodeForTreeArray( $leval );
        return json_encode( $arr );
    }

    private function __treeArrayExchangeToList( $arr, &$data )
    {
        if ( !is_array( $arr ) )
        {
            $arr = array( );
        }
        foreach ( $arr as $v )
        {
            $num = count( $v );
            if ( !empty( $num ) )
            {
                $data[] = array(
                    "text" => $v['text'],
                    "value" => $v['text']
                );
                $this->__treeArrayExchangeToList( $v['children'], $data );
            }
            else
            {
                $i = 0;
            }
        }
    }

    public function _getStructNodeForTreeJsonByPhone( $leval = 0 )
    {
        $arr = $this->_getStructNodeForTreeArray( $leval );
        $data = array( );
        $this->__treeArrayExchangeToList( $arr, $data );
        echo json_encode( $data );
        return $arr;
    }

    public function _makeArray( &$arr )
    {
        $arr = array_merge_recursive( $arr );
        foreach ( $arr as $k => $v )
        {
            if ( $v['permit'] == 1 )
            {
                $arr[$k]['disabled'] = FALSE;
                $arr[$k]['ds'] = FALSE;
            }
            else
            {
                $arr[$k]['disabled'] = TRUE;
                $arr[$k]['ds'] = TRUE;
            }
            if ( is_array( $v['children'] ) )
            {
                $arr[$k]['leaf'] = FALSE;
                $arr[$k]['cls'] = "package";
                $arr[$k]['expanded'] = FALSE;
                $this->_makeArray( $arr[$k]['children'] );
            }
            else
            {
                $arr[$k]['isClass'] = TRUE;
            }
        }
    }

    private function getArrayTreeMaxLevel( $currentNode )
    {
        $level = 1;
        $maxLevel = 0;
        foreach ( $currentNode as $k => $v )
        {
            if ( is_array( $currentNode[$k]['children'] ) )
            {
                $level = $this->getArrayTreeMaxLevel( $currentNode[$k]['children'] ) + 1;
            }
            if ( $maxLevel < $level )
            {
                $maxLevel = $level;
            }
        }
        return $maxLevel;
    }

    private function _clearNoSonNode( &$arr )
    {
        global $b;
        foreach ( $arr as $k => $v )
        {
            ++$b;
            if ( is_array( $v['children'] ) && 0 < count( $v['children'] ) )
            {
                $this->_clearNoSonNode( $arr[$k]['children'] );
            }
            else if ( $v['disabled'] )
            {
                unset( $arr[$k] );
                $arr = array_merge_recursive( $arr );
            }
        }
    }

    private function _clearNode( $arr )
    {
        $maxLeval = $this->getArrayTreeMaxLevel( $arr );
        $i = 0;
        for ( ; $i < $maxLeval; ++$i )
        {
            $this->_clearNoSonNode( $arr );
        }
        return $arr;
    }

    public function _getStructNodeForComboxJson( $data, $hasRoot = TRUE )
    {
        $array = array( );
        $array['data'] = array( );
        if ( is_array( $data ) )
        {
            foreach ( $data as $v )
            {
                $level = substr_count( $v['path'], "," );
                if ( 0 < $level )
                {
                    $v['name'] = str_repeat( "　", $level )."├ ".$v['name'];
                }
                array_push( &$array['data'], array(
                    "fid" => $v['id'],
                    "value" => $v['name']
                ) );
            }
        }
        return json_encode( $array );
    }

    private function _getAllSonNode( $fid )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "id", "path", "fid", "name" ), "main_struct", "WHERE `fid`='".$fid."'" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $rs )
        {
            $this->treeList[] = $rs;
            $this->_getAllSonNode( $rs[id] );
        }
    }

    private function _getAllSonNode2( $fid )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "id", "path", "fid", "name" ), "main_struct", "WHERE `fid`=".$fid." OR `id` = {$fid} " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $rs )
        {
            $this->treeList[] = $rs;
            $this->_getAllSonNode( $rs[id] );
        }
    }

    private function _getBortherList_ByFid( $fid, $action = "add" )
    {
        global $CNOA_DB;
        if ( $action == "edit" )
        {
            $id = getpar( $_GET, "id", "" );
        }
        $deptList = array( );
        $array = $CNOA_DB->db_select( array( "id", "name" ), "main_struct", "WHERE `fid`='".$fid."' ORDER BY `order` ASC" );
        $array = is_array( $array ) ? $array : array( );
        foreach ( $array as $v )
        {
            if ( $GLOBALS['user']['permitArea']['area'] != "all" )
            {
                if ( in_array( $v['id'], $GLOBALS['user']['permitArea']['area'] ) )
                {
                    $v['disabled'] = FALSE;
                }
                else
                {
                    $v['disabled'] = TRUE;
                }
            }
            $deptList[] = $v;
        }
        unset( $v );
        unset( $array );
        $n = 0;
        if ( $action == "edit" && $id != "" )
        {
            foreach ( $deptList as $key => $v )
            {
                if ( $id == $v['id'] )
                {
                    $n = 1;
                    unset( $deptList[$key] );
                }
                else if ( $n == 1 )
                {
                    $deptList[$key]['beforeme'] = TRUE;
                    $n = 0;
                }
            }
        }
        unset( $key );
        unset( $v );
        return array_merge_recursive( $deptList );
    }

    private function _getBortherList_ById( $id )
    {
        global $CNOA_DB;
        $info = $CNOA_DB->db_getone( array( "fid" ), "main_struct", "WHERE `id`='".$id."'" );
        $array = $CNOA_DB->db_select( array( "id", "name", "order" ), "main_struct", "WHERE `fid`='".$info['fid']."'" );
    }

    public function _sortOrder( $sortAction, $id, $fid, $broId = NULL )
    {
        global $CNOA_DB;
        switch ( $sortAction )
        {
        case "last" :
            $sql = "SELECT `id`,`order` FROM ".tname( "main_struct" ).( " WHERE `fid`='".$fid."' AND `id`!='{$id}' ORDER BY `order` DESC LIMIT 1" );
            $data = $CNOA_DB->get_one( $sql );
            if ( !$data )
            {
                $order = 1;
            }
            else
            {
                $order = intval( $data['order'] ) + 1;
            }
            $CNOA_DB->db_update( array(
                "order" => $order
            ), "main_struct", "WHERE `id`='".$id."'" );
            break;
        case "in" :
            $data = $CNOA_DB->db_select( array( "id", "order" ), "main_struct", "WHERE `fid`='".$fid."' AND `id`!='{$id}' ORDER BY `order` ASC" );
            if ( !$data )
            {
                $order = 1;
                $CNOA_DB->db_update( array(
                    "order" => $order
                ), "main_struct", "WHERE `id`='".$id."'" );
            }
            else
            {
                if ( !is_array( $data ) )
                {
                    break;
                }
                $i = 1;
                foreach ( $data as $key => $v )
                {
                    if ( $v['id'] == $broId )
                    {
                        $CNOA_DB->db_update( array(
                            "order" => $i
                        ), "main_struct", "WHERE `id`='".$id."'" );
                        ++$i;
                    }
                    $CNOA_DB->db_update( array(
                        "order" => $i
                    ), "main_struct", "WHERE `id`='".$v['id']."'" );
                    ++$i;
                }
            }
        }
    }

    private function _getAllFidsByFid( $id )
    {
        global $CNOA_DB;
        $dbList = array( );
        if ( $id == 1 )
        {
            $dbList = $CNOA_DB->db_select( "*", "main_struct", "WHERE 1" );
            return $dbList;
        }
        if ( CNOA_COMPANYS_MODE )
        {
            $info = $CNOA_DB->db_getone( "*", "main_struct", "WHERE `id`='".$id."'" );
            $pathArray = explode( ",", $info['path'] );
            $rootFid = $pathArray[1];
            $this->_getAllSonNode( $rootFid );
            $dbList = $this->treeList;
            $self = $CNOA_DB->db_getone( "*", "main_struct", "WHERE `id`='".$rootFid."'" );
            if ( !empty( $self ) )
            {
                $dbList[] = $self;
            }
            if ( !empty( $info ) )
            {
                $dbList[] = $info;
                return $dbList;
            }
        }
        else
        {
            $dbList = $CNOA_DB->db_select( "*", "main_struct", "WHERE 1" );
        }
        return $dbList;
    }

    public function api_getJsonList( )
    {
        return $this->_getStructNodeForComboxJson( $this->_getStructTreeData( ), TRUE );
    }

    public function api_getArrayList( )
    {
        $data2 = array( );
        $data1 = $this->_getStructTreeData( );
        if ( is_array( $data1 ) )
        {
            foreach ( $data1 as $key => $v )
            {
                $data2[$v['id']] = $v['name'];
            }
        }
        return $data2;
    }

    public function api_getChildrenIds( $fid )
    {
        $this->_getAllSonNode( $fid );
        $ids = array( );
        if ( is_array( $this->treeList ) )
        {
            foreach ( $this->treeList as $v )
            {
                $ids[] = $v['id'];
            }
        }
        return $ids;
    }

    public function api_getChildrenIds2( $fid )
    {
        $this->_getAllSonNode2( $fid );
        $ids = array( );
        if ( is_array( $this->treeList ) )
        {
            foreach ( $this->treeList as $v )
            {
                $ids[] = $v['id'];
            }
        }
        return $ids;
    }

    public function api_getChildrenInfo( $fid )
    {
        $this->_getAllSonNode( $fid );
        return $this->treeList;
    }

    public function api_getNameById( $id )
    {
        global $CNOA_DB;
        if ( empty( $id ) )
        {
            return FALSE;
        }
        $sdb = $CNOA_DB->db_getone( array( "name" ), "main_struct", "WHERE `id`='".$id."'" );
        if ( !$sdb )
        {
            return FALSE;
        }
        return $sdb['name'];
    }

    public function api_getNamesByIds( $ids )
    {
        global $CNOA_DB;
        if ( empty( $ids ) )
        {
            $ids = array( 0 );
        }
        $ids = array_filter( array_unique( $ids ) );
        if ( !is_array( $ids ) && count( $ids ) <= 0 )
        {
            return FALSE;
        }
        $deptInfo = $CNOA_DB->db_select( array( "id", "name" ), "main_struct", "WHERE `id` IN (".implode( ",", $ids ).")" );
        $deptInfo = is_array( $deptInfo ) ? $deptInfo : array( );
        $tmp = array( );
        foreach ( $deptInfo as $k => $v )
        {
            $tmp[$v['id']] = $v['name'];
        }
        return $tmp;
    }

    public function api_getInfoById( $id )
    {
        global $CNOA_DB;
        $id = intval( $id );
        if ( $id == 0 )
        {
            return FALSE;
        }
        $sdb = $CNOA_DB->db_getone( "*", "main_struct", "WHERE `id`='".$id."'" );
        return $sdb;
    }

    public function api_getListByIds( $fidArray )
    {
        global $CNOA_DB;
        $where = "WHERE ";
        if ( is_array( $fidArray ) )
        {
            foreach ( $fidArray as $v )
            {
                $where .= " `id`='".$v."' OR ";
            }
            $where = substr( $where, 0, -3 );
        }
        else
        {
            $where .= " 1 ";
        }
        return $CNOA_DB->db_select( array( "id", "name" ), "main_struct", $where );
    }

    public function api_getStructTree( $level = 0 )
    {
        return $this->_getStructNodeForTreeJson( $level );
    }

    public function api_getStructTreeForPhone( $level = 0 )
    {
        return $this->_getStructNodeForTreeJsonByPhone( $level );
    }

    public function api_getStructTreeForArray( $level = 0 )
    {
        return $this->_getStructNodeForTreeArray( $level );
    }

    public function api_getStructListByfid( $fid )
    {
        return $this->_getAllFidsByFid( $fid );
    }

    public function api_getRootFidByUid( $uid )
    {
        global $CNOA_DB;
        $deptId = app::loadapp( "main", "user" )->api_getUserDeptIdByUid( $uid );
        $path = $CNOA_DB->db_getfield( "path", $this->table_struct, "WHERE `id`='".$deptId."'" );
        $paths = explode( ",", $path );
        $root = 0;
        if ( CNOA_COMPANYS_MODE )
        {
            if ( count( $paths ) < 2 )
            {
                $root = $paths[0];
                return $root;
            }
            $root = $paths[1];
            return $root;
        }
        $root = $paths[0];
        return $root;
    }

    public function api_getDeptByUid( $uid )
    {
        global $CNOA_DB;
        $deptId = app::loadapp( "main", "user" )->api_getUserDeptIdByUid( $uid );
        $data = $CNOA_DB->db_getone( array( "id", "name" ), $this->table_struct, "WHERE `id`='".$deptId."'" );
        return $data;
    }

    public function api_getSelectorData( $return = FALSE )
    {
        if ( $return )
        {
            return $this->_getStructNodeForTreeJson( );
        }
        echo $this->_getStructNodeForTreeJson( );
    }

    public function _permitAreaDownExtends( $fid, $insert_id )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $userInfoDB = $CNOA_DB->db_select( "*", "main_user", "WHERE `customPermit`='1'" );
        if ( !is_array( $userInfoDB ) )
        {
            $userInfoDB = array( );
        }
        $uidArr = array( 0 );
        foreach ( $userInfoDB as $v )
        {
            $uidArr[] = $v['uid'];
        }
        $userPermitDB = $CNOA_DB->db_select( "*", "main_permit_user", "WHERE `uid` IN (".implode( ",", $uidArr ).") " );
        if ( !is_array( $userPermitDB ) )
        {
            $userPermitDB = array( );
        }
        $userPermitCt = array( );
        foreach ( $userPermitDB as $k => $v )
        {
            $userPermitCt[$v['uid']][$v['permitid']][] = $v;
        }
        $userPermitValues = array( );
        foreach ( $userPermitCt as $k => $v )
        {
            foreach ( $v as $k1 => $v1 )
            {
                if ( is_array( $v1 ) )
                {
                    foreach ( $v1 as $v2 )
                    {
                        if ( $fid == $v2['deptid'] )
                        {
                            $userPermitValues[] = "('".$v2['uid']."', '{$v2['permitid']}', '{$insert_id}')";
                        }
                    }
                }
            }
        }
        $userPermitValues = array_chunk( $userPermitValues, 200 );
        foreach ( $userPermitValues as $v )
        {
            $values1 = implode( ",", $v );
            $sql = "INSERT INTO ".tname( "main_permit_user" )." (`uid`, `permitid`, `deptid`) VALUES ".$values1;
            $CNOA_DB->query( $sql );
        }
        $jobPermitDB = $CNOA_DB->db_select( "*", "main_permit_job", "WHERE 1" );
        if ( !is_array( $jobPermitDB ) )
        {
            $jobPermitDB = array( );
        }
        $jobPermitCt = array( );
        foreach ( $jobPermitDB as $k => $v )
        {
            $jobPermitCt[$v['jobid']][$v['permitid']][] = $v;
        }
        $jobPermitValues = array( );
        foreach ( $jobPermitCt as $k => $v )
        {
            foreach ( $v as $k1 => $v1 )
            {
                if ( is_array( $v1 ) )
                {
                    foreach ( $v1 as $v2 )
                    {
                        if ( $fid == $v2['deptid'] )
                        {
                            $jobPermitValues[] = "('".$v2['jobid']."', '{$v2['permitid']}', '{$insert_id}')";
                        }
                    }
                }
            }
        }
        $jobPermitValues = array_chunk( $jobPermitValues, 200 );
        foreach ( $jobPermitValues as $v )
        {
            $values2 = implode( ",", $v );
            $sql = "INSERT INTO ".tname( "main_permit_job" )." (`jobid`, `permitid`, `deptid`) VALUES ".$values2;
            $CNOA_DB->query( $sql );
        }
    }

}

?>
