<?php
//decode by qq2859470

class flowFlowSetting extends model
{

    private $table_sort = "flow_flow_sort";
    private $table_dept_sort = "flow_flow_dept_sort";
    private $table_user_sort = "flow_flow_user_sort";
    private $table_list = "flow_flow_list";
    private $table_list_node = "flow_flow_list_node";
    private $table_form = "flow_flow_form";
    private $table_form_item = "flow_flow_form_item";
    private $table_u_list = "flow_flow_u_list";
    private $table_u_node = "flow_flow_u_node";
    private $table_u_formdata = "flow_flow_u_formdata";
    private $table_u_event = "flow_flow_u_event";
    private $table_u_entrust = "flow_flow_u_entrust";
    private $table_sort_permit = "news_news_sort_permit";
    private $table_huiqian = "flow_flow_u_huiqian";
    private $table_dispense = "flow_flow_u_dispense";
    private $cachePath = "";

    public function __construct( )
    {
        $this->cachePath = CNOA_PATH_FILE."/cache";
    }

    public function __destruct( )
    {
    }

    public function run( )
    {
        global $CNOA_SESSION;
        $task = getpar( $_GET, "task", getpar( $_POST, "task" ) );
        if ( $task == "loadPage" )
        {
            $this->_loadPage( );
        }
        else if ( $task == "sortGetJsonData" )
        {
            $this->_sortGetJsonData( );
        }
        else if ( $task == "sortAdd" )
        {
            $this->_sortAdd( );
        }
        else if ( $task == "sortEdit" )
        {
            $this->_sortEdit( );
        }
        else if ( $task == "sortDelete" )
        {
            $this->_sortDelete( );
        }
        else if ( $task == "sortLoadFormData" )
        {
            $this->_sortLoadFormData( );
        }
        else if ( $task == "getFlowJsonData" )
        {
            $this->_getFlowJsonData( );
        }
        else if ( $task == "flowadd" )
        {
            $this->_flowadd( );
        }
        else if ( $task == "flowedit" )
        {
            $this->_flowedit( );
        }
        else if ( $task == "floweditLoadForm" )
        {
            $this->_floweditLoadForm( );
        }
        else if ( $task == "flowdelete" )
        {
            $this->_flowdelete( );
        }
        else if ( $task == "flowdesignLoadData" )
        {
            $this->_flowdesignLoadData( );
        }
        else if ( $task == "flowdesignSubmitData" )
        {
            $this->_flowdesignSubmitData( );
        }
        else if ( $task == "setFlowPublish" )
        {
            $this->_setFlowPublish( );
        }
        else if ( $task == "getAllFlowJsonData" )
        {
            $this->_getAllFlowJsonData( );
        }
        else if ( $task == "deleteflow" )
        {
            $this->_deleteflow( );
        }
        else if ( $task == "exportExcel" )
        {
            $this->_exportExcel( );
        }
        else if ( $task == "getFlowFrom" )
        {
            $this->_getFlowFrom( );
        }
        else if ( $task == "getFormJsonData" )
        {
            $this->_getFormJsonData( );
        }
        else if ( $task == "formAdd" )
        {
            $this->_formAdd( );
        }
        else if ( $task == "formEdit" )
        {
            $this->_fromEdit( );
        }
        else if ( $task == "formeditLoadForm" )
        {
            $this->_formeditLoadForm( );
        }
        else if ( $task == "formdelete" )
        {
            $this->_formdelete( );
        }
        else if ( $task == "formdesign" )
        {
            $this->_formdesign( );
        }
        else if ( $task == "saveFormDesignData" )
        {
            $this->_saveFormDesignData( );
        }
        else if ( $task == "getSortList" )
        {
            $this->_getSortList( );
        }
        else if ( $task == "getFormList" )
        {
            $this->_getFormList( );
        }
        else if ( $task == "getAllUserListsInPermitDeptTree" )
        {
            $this->_getAllUserListsInPermitDeptTree( );
        }
        else if ( $task == "getStructTree" )
        {
            $this->_getStructTree( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $from = getpar( $_GET, "from", "" );
        if ( $from == "flow" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/setting_flow.htm";
        }
        else if ( $from == "form" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/setting_form.htm";
        }
        else if ( $from == "sort" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/setting_sort.htm";
        }
        else if ( $from == "mgr" )
        {
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/setting_mgr.htm";
        }
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _sortGetJsonData( )
    {
        global $CNOA_DB;
        ( );
        $dataStore = new dataStore( );
        $dblist = $CNOA_DB->db_select( "*", $this->table_sort, "WHERE 1 ORDER BY `order` ASC" );
        $dataStore->total = 0;
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _sortAdd( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $data['name'] = getpar( $_POST, "name", "" );
        $data['about'] = getpar( $_POST, "about", "", 1 );
        $data['order'] = 999;
        if ( empty( $data['name'] ) )
        {
            msg::callback( FALSE, "分类名称不能为空" );
        }
        $sid = $CNOA_DB->db_insert( $data, $this->table_sort );
        $deptId = explode( ",", getpar( $_POST, "deptIds" ) );
        foreach ( $deptId as $k => $v )
        {
            $info = array( );
            $info['sid'] = $sid;
            $info['deptId'] = $v;
            $CNOA_DB->db_insert( $info, $this->table_dept_sort );
        }
        $allUids = explode( ",", getpar( $_POST, "allUids" ) );
        foreach ( $allUids as $k => $v )
        {
            $userinfo = array( );
            $userinfo['sid'] = $sid;
            $userinfo['allUid'] = $v;
            $CNOA_DB->db_insert( $userinfo, $this->table_user_sort );
        }
        app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 78, $data['name'], "流程分类" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _sortEdit( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $sid = getpar( $_POST, "sid", 0 );
        $deptIds = getpar( $_POST, "deptIds" );
        $allUids = getpar( $_POST, "allUids" );
        $data['name'] = getpar( $_POST, "name", "" );
        $data['about'] = getpar( $_POST, "about", "", 1 );
        if ( empty( $data['name'] ) )
        {
            msg::callback( FALSE, "分类名称不能为空" );
        }
        $info = $CNOA_DB->db_getone( "*", $this->table_sort, "WHERE `sid`='".$sid."'" );
        if ( $info['type'] == "sys" )
        {
            unset( $data['name'] );
        }
        $CNOA_DB->db_delete( $this->table_dept_sort, "WHERE `sid`='".$sid."'" );
        $deptIdsArray = explode( ",", $deptIds );
        if ( is_array( $deptIdsArray ) )
        {
            foreach ( $deptIdsArray as $v )
            {
                $CNOA_DB->db_insert( array(
                    "sid" => $sid,
                    "deptId" => $v
                ), $this->table_dept_sort );
            }
        }
        $CNOA_DB->db_delete( $this->table_user_sort, "WHERE `sid`='".$sid."'" );
        $allUidsArray = explode( ",", $allUids );
        if ( is_array( $allUidsArray ) )
        {
            foreach ( $allUidsArray as $v )
            {
                $CNOA_DB->db_insert( array(
                    "sid" => $sid,
                    "allUid" => $v
                ), $this->table_user_sort );
            }
        }
        $CNOA_DB->db_update( $data, $this->table_sort, "WHERE `sid`='".$sid."'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 78, $data['name'], "流程分类" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _sortDelete( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $sid = getpar( $_POST, "sid", 0 );
        $uid = $CNOA_SESSION->get( "UID" );
        if ( $sid == 112 )
        {
            msg::callback( FALSE, "受保护的分类不可删除" );
        }
        $info = $CNOA_DB->db_getone( "*", $this->table_sort, "WHERE `sid`='".$sid."'" );
        if ( $info['type'] == "sys" )
        {
            msg::callback( FALSE, "系统内置分类不可删除" );
        }
        $CNOA_DB->db_delete( $this->table_sort, "WHERE `sid`='".$sid."'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 78, $info['name'], "流程分类" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getFlowFrom( )
    {
        $GLOBALS['_GET']['type'] = "combo";
        app::loadapp( "flow", "flow" )->api_getSortList( );
    }

    private function _sortLoadFormData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $uid = $CNOA_SESSION->get( "UID" );
        $sid = getpar( $_POST, "sid", 0 );
        $info = $CNOA_DB->db_getone( "*", $this->table_sort, "WHERE `sid`='".$sid."'" );
        if ( !$info )
        {
            msg::callback( FALSE, lang( "nodata" ) );
        }
        ( );
        $dataStore = new dataStore( );
        $dlist = $CNOA_DB->db_select( "*", $this->table_dept_sort, "WHERE `sid`='".$info['sid']."'" );
        $ulist = $CNOA_DB->db_select( "*", $this->table_user_sort, "WHERE `sid`='".$info['sid']."'" );
        if ( $dlist )
        {
            $deptIds = array( );
            foreach ( $dlist as $v )
            {
                $deptIds[] = $v['deptId'];
            }
            $deptNames = app::loadapp( "main", "struct" )->api_getNamesByIds( $deptIds );
            $info['deptIds'] = is_array( $deptIds ) ? implode( ",", $deptIds ) : "";
            $info['deptNames'] = is_array( $deptNames ) ? implode( ",", $deptNames ) : "";
        }
        else
        {
            $info['sid'] = $info['sid'];
            $info['name'] = $info['name'];
        }
        if ( $ulist )
        {
            $uids = array( );
            foreach ( $ulist as $v )
            {
                $uids[] = $v['allUid'];
            }
            $userNames = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uids );
            if ( !is_array( $userNames ) )
            {
                $userNames = array( );
            }
            $tmp = array( );
            foreach ( $userNames as $v )
            {
                $tmp[] = $v['truename'];
            }
            $userNames = $tmp;
            $info['allUids'] = is_array( $uids ) ? implode( ",", $uids ) : "";
            $info['allUserNames'] = is_array( $userNames ) ? implode( ",", $userNames ) : "";
        }
        else
        {
            $info['sid'] = $info['sid'];
            $info['name'] = $info['name'];
        }
        $dataStore->total = 0;
        $dataStore->data = $info;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getFlowJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $start = getpar( $_POST, "start", 0 );
        $rows = 15;
        $sort = intval( getpar( $_POST, "sort", 0 ) );
        $name = getpar( $_POST, "name", "" );
        $flowFrom = getpar( $_POST, "flowFrom", "" );
        if ( $sort == 0 )
        {
            $where = "WHERE 1 ";
        }
        else
        {
            $where = "WHERE `sort`='".$sort."' ";
        }
        if ( !empty( $name ) )
        {
            $where .= " AND `l`.`name` LIKE '%".$name."%'";
        }
        if ( !empty( $flowFrom ) )
        {
            $where .= " AND `l`.`sort` = '".$flowFrom."'";
        }
        $sql = "SELECT  `l`.`name`, `l`.`lid`, `l`.`publish`, `s`.`name` AS `sname` FROM ".tname( $this->table_list )." AS `l` LEFT JOIN ".tname( $this->table_sort )." AS `s` ON `l`.`sort`=`s`.`sid` ".$where."ORDER BY `posttime` DESC ".( "LIMIT ".$start.", {$rows}" );
        $dblist = array( );
        $queryList = $CNOA_DB->query( $sql );
        while ( $list = $CNOA_DB->get_array( $queryList ) )
        {
            $list['posttime'] = date( "Y-m-d H:i", $list['posttime'] );
            $dblist[] = $list;
        }
        $sql = "SELECT  count(*) AS `count` FROM ".tname( $this->table_list )." AS `l` ".$where;
        $total = $CNOA_DB->get_one( $sql );
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = $total['count'];
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _flowadd( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $data['name'] = getpar( $_POST, "name", "" );
        $data['sort'] = getpar( $_POST, "sid", 0 );
        $data['formid'] = getpar( $_POST, "formid", 0 );
        $data['number'] = getpar( $_POST, "number", "" );
        $data['about'] = getpar( $_POST, "about", "", 1 );
        $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['uid'] = $uid;
        $CNOA_DB->db_insert( $data, $this->table_list );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 80, $data['name'], "流程设计" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _flowedit( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $lid = getpar( $_POST, "lid", 0 );
        $data['name'] = getpar( $_POST, "name", "" );
        $data['sort'] = getpar( $_POST, "sid", 0 );
        $data['formid'] = getpar( $_POST, "formid", 0 );
        $data['number'] = getpar( $_POST, "number", "" );
        $data['about'] = getpar( $_POST, "about", "", 1 );
        $data['uid'] = $uid;
        $CNOA_DB->db_update( $data, $this->table_list, "WHERE `lid`='".$lid."'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 80, $data['name'], "流程设计" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _flowdelete( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $lid = getpar( $_POST, "lid", 0 );
        $protect_fids = array( 193, 185, 188, 191 );
        if ( in_array( $lid, $protect_fids ) )
        {
            msg::callback( FALSE, "受保护的工作流程不允许删除" );
        }
        $DB = $CNOA_DB->db_select( "*", $this->table_u_list, "WHERE `lid`='".$lid."' " );
        if ( !is_array( $DB ) )
        {
            $DB = array( );
        }
        $ulidArr = array( 0 );
        foreach ( $DB as $k => $v )
        {
            $ulidArr[] = $v['ulid'];
        }
        $DB1 = $CNOA_DB->db_select( array( "noticeid_c", "todoid_c" ), $this->table_u_node, "WHERE `ulid` IN (".implode( ",", $ulidArr ).") " );
        $DB2 = $CNOA_DB->db_select( array( "noticeid_c", "todoid_c" ), $this->table_huiqian, "WHERE `ulid` IN (".implode( ",", $ulidArr ).") " );
        $DB3 = $CNOA_DB->db_select( array( "noticeid_c", "todoid_c" ), $this->table_dispense, "WHERE `ulid` IN (".implode( ",", $ulidArr ).") " );
        if ( !is_array( $DB1 ) )
        {
            $DB1 = array( );
        }
        if ( !is_array( $DB2 ) )
        {
            $DB2 = array( );
        }
        if ( !is_array( $DB3 ) )
        {
            $DB3 = array( );
        }
        $notice = array( );
        $todo = array( );
        foreach ( $DB1 as $k => $v )
        {
            $notice[] = $v['noticeid_c'];
            $todo[] = $v['todoid_c'];
        }
        foreach ( $DB2 as $k => $v )
        {
            $notice[] = $v['noticeid_c'];
            $todo[] = $v['todoid_c'];
        }
        foreach ( $DB3 as $k => $v )
        {
            $notice[] = $v['noticeid_c'];
            $todo[] = $v['todoid_c'];
        }
        notice::deletenotice( $notice, $todo );
        $flowname = $CNOA_DB->db_getfield( "name", $this->table_list, "WHERE `lid`='".$lid."'" );
        $CNOA_DB->db_delete( $this->table_list, "WHERE `lid`='".$lid."'" );
        $CNOA_DB->db_delete( $this->table_list_node, "WHERE `lid`='".$lid."'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 80, $flowname, "流程设计" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _floweditLoadForm( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $uid = $CNOA_SESSION->get( "UID" );
        $lid = getpar( $_POST, "lid", 0 );
        $info = $CNOA_DB->db_getone( "*", $this->table_list, "WHERE `lid`='".$lid."'" );
        if ( !$info )
        {
            msg::callback( FALSE, lang( "nodata" ) );
        }
        if ( $info['formid'] == 0 )
        {
            unset( $info['formid'] );
        }
        else
        {
            $info['formname'] = $CNOA_DB->db_getfield( "name", $this->table_form, "WHERE `fid`='".$info['formid']."'" );
            if ( !$info['formname'] )
            {
                unset( $info['formid'] );
                unset( $info['formname'] );
            }
        }
        $info['sid'] = $info['sort'];
        unset( $info['sort'] );
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = 0;
        $dataStore->data = $info;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    public function _flowdesignLoadData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $lid = getpar( $_POST, "lid", 0 );
        $data = $CNOA_DB->db_select( array( "lid", "stepid", "name", "allowup", "allowdown", "type", "operatorperson", "operatortype", "operator", "formitems", "smsdeal" ), $this->table_list_node, "WHERE `lid`='".$lid."' ORDER BY `stepid` ASC" );
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        foreach ( $data as $k => $v )
        {
            $data[$k]['id'] = $v['stepid'];
            $data[$k]['upAttach'] = $v['allowup'];
            $data[$k]['downAttach'] = $v['allowdown'];
            $data[$k]['smsdeal'] = $v['smsdeal'];
            $data[$k]['operatorperson'] = $v['operatorperson'];
            $data[$k]['operatortype'] = $v['operatortype'];
            $data[$k]['operator'] = json_decode( $v['operator'], TRUE );
            $data[$k]['formitems'] = json_decode( $v['formitems'], TRUE );
            unset( json_decode( $v['formitems'], TRUE )['allowup'] );
            unset( json_decode( $v['formitems'], TRUE )['allowdown'] );
        }
        $formid = $CNOA_DB->db_getfield( "formid", $this->table_list, "WHERE `lid`='".$lid."'" );
        $cacheFormItems = $this->cachePath."/flow/flow/formItems/".$formid.".php";
        @include( $cacheFormItems );
        $GLOBALS['GLOBALS']['flowFormItems2'] = $GLOBALS['flowFormItems'];
        $GLOBALS['GLOBALS']['flowFormItems2'] = array( );
        foreach ( $GLOBALS['GLOBALS']['flowFormItems2'] as $v )
        {
            if ( !in_array( $v['type'], array( "SYS_FLOWTITLE", "SYS_FLOWNAME" ) ) )
            {
                $GLOBALS['GLOBALS']['flowFormItems'][] = $v;
            }
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->flowFormItems = $GLOBALS['flowFormItems'];
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _flowdesignSubmitData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $lid = getpar( $_POST, "lid", 0 );
        $data = json_decode( $_POST['data'], TRUE );
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        $CNOA_DB->db_delete( $this->table_list_node, "WHERE `lid`='".$lid."'" );
        foreach ( $data as $k => $v )
        {
            $nodeData = array( );
            $nodeData['name'] = getpar( $v, "name", "" );
            $nodeData['lid'] = $lid;
            $nodeData['stepid'] = getpar( $v, "id", 0 );
            $nodeData['type'] = getpar( $v, "type", "node" );
            $nodeData['smsdeal'] = getpar( $v, "smsdeal", 0 );
            $nodeData['allowup'] = getpar( $v, "upAttach", 0 );
            $nodeData['allowdown'] = getpar( $v, "downAttach", 0 );
            $nodeData['operatorperson'] = getpar( $v, "operatorperson", 1 );
            $nodeData['operatortype'] = getpar( $v, "operatortype", 2 );
            $nodeData['operator'] = json_encode( $v['operator'] );
            $nodeData['operator'] = getpar( $nodeData, "operator", "", 1 );
            $nodeData['formitems'] = json_encode( $v['formitems'] );
            $nodeData['formitems'] = getpar( $nodeData, "formitems", "", 1 );
            if ( $nodeData['stepid'] == 0 )
            {
                $nodeData['type'] = "start";
            }
            $CNOA_DB->db_insert( $nodeData, $this->table_list_node );
        }
        $flowname = $CNOA_DB->db_getfield( "name", $this->table_list, "WHERE `lid`='".$lid."'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 80, $flowname, "设计流程" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _setFlowPublish( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $lid = getpar( $_POST, "lid", 0 );
        $flowInfo = $CNOA_DB->db_getone( "*", $this->table_list, "WHERE `lid`='".$lid."'" );
        if ( !$flowInfo )
        {
            msg::callback( FALSE, lang( "nodata" ) );
        }
        if ( $flowInfo['publish'] == 1 )
        {
            $CNOA_DB->db_update( array( "publish" => 0 ), $this->table_list, "WHERE `lid`='".$lid."'" );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "", 80, $flowInfo['name'], "禁用流程设计" );
        }
        else
        {
            if ( intval( $flowInfo['formid'] ) == 0 )
            {
                msg::callback( FALSE, "该流程没有绑定表单，不能启用" );
            }
            $nodeInfo = $CNOA_DB->db_select( "*", $this->table_list_node, "WHERE `lid`='".$lid."'" );
            if ( !is_array( $nodeInfo ) )
            {
                $nodeInfo = array( );
            }
            $haveStart = FALSE;
            $haveEnd = FALSE;
            foreach ( $nodeInfo as $v )
            {
                if ( $v['type'] == "start" )
                {
                    $haveStart = TRUE;
                }
                if ( $v['type'] == "end" )
                {
                    $haveEnd = TRUE;
                }
            }
            if ( !$haveStart )
            {
                msg::callback( FALSE, "该流程没有开始节点，不能启用" );
            }
            if ( !$haveEnd )
            {
                msg::callback( FALSE, "该流程没有结束节点，不能启用" );
            }
            $CNOA_DB->db_update( array( "publish" => 1 ), $this->table_list, "WHERE `lid`='".$lid."'" );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "", 80, $flowInfo['name'], "启用流程设计" );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getFormJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $start = getpar( $_POST, "start", 0 );
        $rows = 15;
        $sortAll = intval( getpar( $_POST, "sort", 0 ) );
        $sort = getpar( $_POST, "flowFrom", "" );
        $name = getpar( $_POST, "name", "" );
        if ( $sortAll == 0 )
        {
            $where = "WHERE 1 ";
        }
        else
        {
            $where = "WHERE `sort`='".$sortAll."' ";
        }
        if ( !empty( $sort ) )
        {
            $where .= "AND `l`.`sort` = '".$sort."' ";
        }
        if ( !empty( $name ) )
        {
            $where .= " AND `l`.`name` LIKE '%".$name."%'";
        }
        $sql = "SELECT  `l`.`name`, `l`.`fid`, `l`.`about`, `s`.`name` AS `sname` FROM ".tname( $this->table_form )." AS `l` LEFT JOIN ".tname( $this->table_sort )." AS `s` ON `l`.`sort`=`s`.`sid` ".$where."ORDER BY `posttime` DESC ".( "LIMIT ".$start.", {$rows}" );
        $dblist = array( );
        $queryList = $CNOA_DB->query( $sql );
        while ( $list = $CNOA_DB->get_array( $queryList ) )
        {
            $list['posttime'] = date( "Y-m-d H:i", $list['posttime'] );
            $dblist[] = $list;
        }
        $sql = "SELECT  count(*) AS `count` FROM ".tname( $this->table_form )." AS `l` ".$where;
        ( );
        $dataStore = new dataStore( );
        $total = $CNOA_DB->get_one( $sql );
        $dataStore->total = $total['count'];
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _formAdd( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $data = array( );
        $data['name'] = getpar( $_POST, "name", "" );
        $data['sort'] = getpar( $_POST, "sid", 0 );
        $data['about'] = getpar( $_POST, "about", "", 1 );
        $data['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['uid'] = $uid;
        $CNOA_DB->db_insert( $data, $this->table_form );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 79, $data['name'], "流程表单" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _fromEdit( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $fid = getpar( $_POST, "fid", 0 );
        $data = array( );
        $data['name'] = getpar( $_POST, "name", "" );
        $data['sort'] = getpar( $_POST, "sid", 0 );
        $data['about'] = getpar( $_POST, "about", "", 1 );
        $data['uid'] = $uid;
        $CNOA_DB->db_update( $data, $this->table_form, "WHERE `fid`='".$fid."'" );
        header( "Content-type: text/html" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 79, $data['name'], "流程表单" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _formdelete( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $fid = getpar( $_POST, "fid", 0 );
        $protect_fids = array( 170, 174, 175, 176 );
        if ( in_array( $fid, $protect_fids ) )
        {
            msg::callback( FALSE, "受保护的表单不允许删除" );
        }
        $info = $CNOA_DB->db_getone( "*", $this->table_form, "WHERE `fid`='".$fid."'" );
        $CNOA_DB->db_delete( $this->table_form, "WHERE `fid`='".$fid."'" );
        $CNOA_DB->db_update( array( "publish" => 0, "formid" => "" ), $this->table_list, "WHERE `formid`='".$fid."'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 79, $info['name'], "流程表单" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _formdesign( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        $fid = getpar( $_GET, "fid", 0 );
        $job = getpar( $_GET, "job", "" );
        if ( $job == "getMaxNum" )
        {
            $num = $CNOA_DB->db_getfield( "item_max", $this->table_form, "WHERE `fid`='".$fid."'" );
            $CNOA_DB->db_updateNum( "item_max", "+1", $this->table_form, "WHERE `fid`='".$fid."'" );
            echo $num;
            exit( );
        }
        $GLOBALS['GLOBALS']['content'] = $CNOA_DB->db_getfield( "content", $this->table_form, "WHERE `fid`='".$fid."'" );
        $GLOBALS['GLOBALS']['content'] = htmlspecialchars( $GLOBALS['content'] );
        $CNOA_CONTROLLER->tplHeaderType = "extjs";
        $GLOBALS['GLOBALS']['formid'] = $fid;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/setting_form_design.htm";
        $CNOA_CONTROLLER->loadViewCustom( $tplPath, TRUE, TRUE );
    }

    private function _saveFormDesignData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $data = array( );
        $fid = getpar( $_POST, "fid", 0 );
        $content = $_POST['content'];
        $content = str_replace( "iename", "name", $content );
        $object = $this->__getHtmlElement( $content );
        $data['content'] = str_replace( "'", "\"", $object['html'] );
        $data['content'] = getpar( $data, "content", "", 1, 0 );
        $cacheForm = $this->cachePath."/flow/flow/form/";
        $cacheFormItems = $this->cachePath."/flow/flow/formItems/";
        @mkdirs( $cacheForm );
        @mkdirs( $cacheFormItems );
        @file_put_contents( $cacheForm."{$fid}.php", "<?php\r\n\$GLOBALS['flowFormHtml']='".@str_replace( "'", "\"", $object['html'] )."'; ?>" );
        @file_put_contents( $cacheFormItems."{$fid}.php", "<?php\r\n\$GLOBALS['flowFormItems']=".@var_export( $object['element'], TRUE ).";\r\n?>" );
        $CNOA_DB->db_update( $data, $this->table_form, "WHERE `fid`='".$fid."'" );
        $CNOA_DB->db_delete( $this->table_form_item, "WHERE `fid`='".$fid."'" );
        if ( !is_array( $object['element'] ) )
        {
            $object['element'] = array( );
        }
        foreach ( $object['element'] as $v )
        {
            $data = array( );
            $data['fid'] = $fid;
            $data['itemid'] = $v['itemid'];
            $data['type'] = $v['type'];
            $data['title'] = $v['title'];
            $data['name'] = $v['name'];
            $data['htmltag'] = $v['htmltag'];
            $CNOA_DB->db_insert( $data, $this->table_form_item );
        }
        $name = $CNOA_DB->db_getfield( "name", $this->table_form, "WHERE `fid`='".$fid."'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 79, $name, "设计流程表单" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function __getHtmlElement( $html )
    {
        global $CNOA_DB;
        $elementList = array( );
        preg_match_all( "/((<((input))[^>]*>)|(<textarea[\\s\\S]+?><\\/textarea>)|(<select[\\s\\S]+?>[\\s\\S]+?<\\/select>))/i", $html, $arr );
        foreach ( $arr[0] as $v )
        {
            $tmp = array( );
            $tmp['itemid'] = preg_replace( "/(.*)flowitemid=\"([^\"]*)\"\\s?(.*)/is", "\\2", $v );
            $tmp['type'] = preg_replace( "/(.*)flowitemtp=\"([^\"]*)\"\\s?(.*)/is", "\\2", $v );
            $tmp['name'] = preg_replace( "/(.*)name=\"([^\"]*)\"\\s?(.*)/is", "\\2", $v );
            $tmp['title'] = preg_replace( "/(.*)title=\"([^\"]*)\"\\s?(.*)/is", "\\2", $v );
            $tmp['htmltag'] = strtolower( substr( $v, 1, strpos( $v, " " ) - 1 ) );
            $tmp['html'] = $v;
            $elementList[] = $tmp;
        }
        $nums = array( );
        $repeats = array( );
        foreach ( $elementList as $v )
        {
            $nums[] = $v['itemid'];
            $repeats[$v['itemid']][] = $v;
        }
        rsort( &$nums, SORT_NUMERIC );
        $maxnum2 = $maxnum = $nums[0];
        unset( $v );
        unset( $elementList );
        $elementList = array( );
        foreach ( $repeats as $v )
        {
            if ( 1 < count( $v ) )
            {
                foreach ( $v as $v2 )
                {
                    ++$maxnum;
                    $itemhtml = $v2['html'];
                    $v2['itemid'] = $maxnum;
                    $v2['name'] = "FLOWDATA[".$maxnum."]";
                    $v2['html'] = preg_replace( "/(.*)name=\"FLOWDATA([^\"]*)(\"\\s?(.*))/is", "\\1name=\"FLOWDATA[".$maxnum."]\\3", $v2['html'] );
                    $v2['html'] = preg_replace( "/(.*)flowitemid=\"([^\"]*)(\"\\s?(.*))/is", "\\1flowitemid=\"".$maxnum."\\3", $v2['html'] );
                    $elementList[] = $v2;
                    $html = str_replace_once( $itemhtml, $v2['html'], $html );
                }
            }
            else
            {
                $itemhtml = $v[0]['html'];
                $v[0]['name'] = "FLOWDATA[".$v[0]['itemid']."]";
                $v[0]['html'] = preg_replace( "/(.*)name=\"FLOWDATA([^\"]*)(\"\\s?(.*))/is", "\\1name=\"FLOWDATA[".$v[0]['itemid']."]\\3", $v[0]['html'] );
                $v[0]['html'] = preg_replace( "/(.*)flowitemid=\"([^\"]*)(\"\\s?(.*))/is", "\\1flowitemid=\"".$v[0]['itemid']."\\3", $v[0]['html'] );
                $elementList[] = $v[0];
                $html = str_replace_once( $itemhtml, $v[0]['html'], $html );
            }
        }
        $html = str_replace( array( "\t", "\n" ), "", $html );
        $fid = getpar( $_POST, "fid", "0" );
        if ( $maxnum2 < $maxnum )
        {
            $CNOA_DB->db_update( array(
                "item_max" => ++$maxnum
            ), "flow_flow_form", "WHERE `fid`='".$fid."'" );
        }
        return array(
            "element" => $elementList,
            "html" => $html
        );
    }

    public function _formeditLoadForm( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $uid = $CNOA_SESSION->get( "UID" );
        $fid = getpar( $_POST, "fid", 0 );
        $info = $CNOA_DB->db_getone( "*", $this->table_form, "WHERE `fid`='".$fid."'" );
        if ( !$info )
        {
            msg::callback( FALSE, lang( "nodata" ) );
        }
        $info['sid'] = $info['sort'];
        unset( $info['sort'] );
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = 0;
        $dataStore->data = $info;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    public function _getSortList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $type = getpar( $_GET, "type", "combo" );
        $myJobType = $CNOA_SESSION->get( "JOBTYPE" );
        if ( $myJobType == "superAdmin" )
        {
            $dblist = $CNOA_DB->db_select( "*", $this->table_sort, "WHERE 1 ORDER BY `order` ASC" );
        }
        else
        {
            $deptId = $CNOA_DB->db_getone( "*", "main_user", "WHERE `uid` = '".$uid."'" );
            $ssid = $CNOA_DB->db_select( "*", $this->table_dept_sort, "WHERE `deptId` = '".$deptId['deptId']."'" );
            if ( !is_array( $ssid ) )
            {
                $ssid = array( );
            }
            $sids = array( 0 );
            foreach ( $ssid as $v )
            {
                $sids[] = $v['sid'];
            }
            $dblist = $CNOA_DB->db_select( "*", $this->table_sort, "WHERE `sid` IN (".implode( ",", $sids ).") ORDER BY `order` ASC" );
        }
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        if ( $type == "tree" )
        {
            $list = array( );
            foreach ( $dblist as $v )
            {
                $r = array( );
                $r['text'] = $v['name'];
                $r['sid'] = $v['sid'];
                $r['iconCls'] = "icon-style-page-key";
                $r['leaf'] = TRUE;
                $r['href'] = "javascript:void(0);";
                $list[] = $r;
            }
            echo json_encode( $list );
            exit( );
        }
        if ( $type == "combo" )
        {
            ( );
            $dataStore = new dataStore( );
            $dataStore->total = count( $dblist );
            $dataStore->data = $dblist;
            echo $dataStore->makeJsonData( );
            exit( );
        }
    }

    private function _getFormList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $type = getpar( $_GET, "type", "" );
        $sort = getpar( $_GET, "sort", 0 );
        $flist = $CNOA_DB->db_select( array( "name", "fid", "sort" ), $this->table_form, "WHERE `sort`='".$sort."'" );
        if ( !is_array( $flist ) )
        {
            $flist = array( );
        }
        $list = array( );
        foreach ( $flist as $vv )
        {
            $rr = array( );
            $rr['text'] = $vv['name'];
            $rr['fid'] = $vv['fid'];
            $rr['iconCls'] = "icon-style-page-key";
            $rr['leaf'] = TRUE;
            $rr['href'] = "javascript:void(0);";
            $list[] = $rr;
        }
        echo json_encode( $list );
        exit( );
    }

    private function _getAllUserListsInPermitDeptTree( )
    {
        $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
        $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
        echo json_encode( $userList );
        exit( );
    }

    private function _getStructTree( )
    {
        $GLOBALS['GLOBALS']['GLOBALS']['user']['permitArea']['area'] = "all";
        echo app::loadapp( "main", "struct" )->api_getStructTree( 0 );
        exit( );
    }

    private function __findFlowInfo( )
    {
        $name = getpar( $_POST, "name" );
        $title = getpar( $_POST, "title" );
        $document = getpar( $_POST, "document" );
        $stime = getpar( $_POST, "beginTime" );
        $etime = getpar( $_POST, "endTime" );
        $status = getpar( $_POST, "status" );
        $uid = getpar( $_POST, "buildUser" );
        $s = "";
        if ( !empty( $uid ) )
        {
            $s .= " AND `uid`=".$uid;
        }
        if ( !empty( $status ) || strval( $status ) != "-99" )
        {
            $s .= " AND `status` = ".$status;
        }
        if ( !empty( $name ) )
        {
            $s .= " AND `name` LIKE '%".$name."%'";
        }
        if ( !empty( $title ) )
        {
            $s .= " AND `title` LIKE '%".$title."%'";
        }
        if ( !empty( $document ) )
        {
            $s .= " AND `document` LIKE '%".$document."%'";
        }
        if ( !empty( $stime ) || empty( $etime ) )
        {
            $stime = strtotime( $stime." 00:00:00" );
            $s .= " AND `posttime` >= ".$stime;
        }
        if ( !empty( $etime ) || empty( $stime ) )
        {
            $etime = strtotime( $etime." 23:59:59" );
            $s .= " AND `posttime` <= ".$etime;
        }
        if ( !empty( $stime ) || !empty( $etime ) )
        {
            $stime = strtotime( $stime." 00:00:00" );
            $etime = strtotime( $etime." 23:59:59" );
            if ( $etime < $stime )
            {
                msg::callback( FALSE, "查询开始时间不能大于结束时间" );
            }
            else
            {
                $s .= " AND `posttime` > ".$stime." AND `posttime` < {$etime}";
            }
        }
        return " ".$s." ";
    }

}

?>
