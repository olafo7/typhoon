<?php
//decode by qq2859470

class odocSettingFlow extends model
{

    private $t_odoc_view_fieldset = "odoc_view_fieldset";
    private $t_odoc_view_field = "odoc_view_field";
    private $t_odoc_data = "odoc_data";
    private $t_odoc_view_field_list = "odoc_view_field_list";
    private $t_odoc_bind_flow_kj = "odoc_bind_flow_kj";
    private $t_odoc_bind_flow = "odoc_bind_flow";
    private $f_which = array
    (
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
        case "loadpage" :
            $this->_loadpage( );
            break;
        case "submitBindFlow" :
            $this->_submitBindFlow( );
            break;
        case "submitAddWinLayout" :
            $this->_submitAddWinLayout( );
            break;
        case "loadFormData" :
            $this->_loadFormData( );
            break;
        case "getFieldList" :
            $this->_getFieldList( );
            break;
        case "submitList" :
            $this->_submitList( );
            break;
        case "loadColumn" :
            app::loadapp( "user", "customersAgent" )->api_loadColumn( );
            break;
        case "getFlowList" :
            $this->_getFlowList( );
            break;
        case "getBindFieldList" :
            $this->_getBindFieldList( );
            break;
        case "getFlowJsonData" :
            $this->_getFlowJsonData( );
            break;
        case "getFlowSortTree" :
            include_once( CNOA_PATH."/app/wf/inc/wfCommon.class.php" );
            app::loadapp( "wf", "flowSetSort" )->api_getSortTree( "all" );
            break;
        case "addBindFlowId" :
            $this->_addBindFlowId( );
            break;
        case "getFlowFieldList" :
            $this->_getFlowFieldList( );
            break;
        case "submitBindFlowField" :
            $this->_submitBindFlowField( );
            break;
        case "submitDept" :
            $this->_submitDept( );
            break;
        case "list" :
            $this->_getJsonList( );
            break;
        case "deleteData" :
            $this->_deleteData( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $userList );
            exit( );
        case "removeAgent" :
            $this->_removeAgent( );
            break;
        case "getWordList" :
            $this->_getWordList( );
            break;
        case "loadPermitFormData" :
            $this->_loadPermitFormData( );
            break;
        case "editLoadBindFlowData" :
            $this->_editLoadBindFlowData( );
            break;
        case "deleteFlow" :
            $this->_deleteFlow( );
            break;
        case "setStatus" :
            $this->_setStatus( );
        }
    }

    private function _loadpage( )
    {
    }

    private function _getJsonList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $dblist = $CNOA_DB->db_select( "*", $this->t_odoc_data, "WHERE 1 ORDER BY `id` DESC " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $uidArr = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            foreach ( $v as $key => $val )
            {
                if ( !empty( $val ) )
                {
                    if ( $fieldArr[$key] == "type" )
                    {
                        $typeArr[] = $val;
                    }
                    if ( $fieldArr[$key] == "sort" )
                    {
                        $sortArr[] = $val;
                    }
                }
            }
            $uidArr[] = $v['uid'];
        }
        $trueNameDB = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['posttime'] = date( "Y-m-d", $v['posttime'] );
            $dblist[$k]['postname'] = $trueNameDB[$v['uid']]['truename'];
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $dblist;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _submitBindFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $from = getpar( $_POST, "from", "" );
        $bindFlow = getpar( $_POST, "bindFlow", 0 );
        $name = getpar( $_POST, "name", "" );
        $tp = getpar( $_POST, "tp", "" );
        $id = getpar( $_POST, "id", "0" );
        if ( $bindFlow == 0 )
        {
            msg::callback( FALSE, "请绑定一条工作流程" );
        }
        if ( empty( $from ) )
        {
            msg::callback( FALSE, "发生错误" );
        }
        $data = array( );
        if ( $tp == "edit" )
        {
            $data['name'] = $name;
            $data['flowId'] = $bindFlow;
            $CNOA_DB->db_update( $data, $this->t_odoc_bind_flow, "WHERE `id`='".$id."'" );
            $CNOA_DB->db_update( array(
                "flowId" => $bindFlow
            ), $this->t_odoc_bind_flow_kj, "WHERE `id`='".$id."'" );
        }
        else
        {
            $data['name'] = $name;
            $data['flowId'] = $bindFlow;
            $data['from'] = $from;
            $data['status'] = 0;
            $CNOA_DB->db_insert( $data, $this->t_odoc_bind_flow );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _submitAddWinLayout( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $data = $_POST['data'];
        $data = json_decode( $data, TRUE );
        $GLOBALS['dataSafeScan']->stopAttackFromArray( $data );
        $from = getpar( $_POST, "from", "" );
        $fieldsets = $fields = array( );
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        foreach ( $data as $fieldset )
        {
            $fieldsetid = intval( $fieldset['fieldset']['id'] );
            if ( $fieldsetid == 0 )
            {
                $fieldsetid = $CNOA_DB->db_insert( array(
                    "name" => $fieldset['fieldset']['name'],
                    "from" => $from
                ), $this->t_odoc_view_fieldset );
                $CNOA_DB->db_update( array(
                    "fieldset" => $fieldsetid
                ), $this->t_odoc_view_fieldset, "WHERE `id`='".$fieldsetid."'" );
            }
            else
            {
                $CNOA_DB->db_update( array(
                    "name" => $fieldset['fieldset']['name']
                ), $this->t_odoc_view_fieldset, "WHERE `from`='".$from."' AND `fieldset`='{$fieldsetid}'" );
            }
            $items = $fieldset['items'];
            if ( !is_array( $items ) )
            {
                $items = array( );
            }
            foreach ( $items as $item )
            {
                $dt = array( );
                $arr = explode( "_", $item['name'] );
                $arr[1] = intval( $arr[1] );
                $arr[2] = intval( $arr[2] );
                $arr[3] = intval( $arr[3] );
                $type = $arr[4];
                $dt['from'] = $from;
                $dt['fieldset'] = $fieldsetid;
                $dt['type'] = $type;
                $dt['name'] = addslashes( preg_replace( "/\\{\\[.*\\]\\}/i", "", $item['value'] ) );
                if ( empty( $dt['name'] ) )
                {
                    $dt['name'] = " ";
                }
                if ( $arr[3] != 0 )
                {
                    $field = $CNOA_DB->db_getone( "*", $this->t_odoc_view_field, "WHERE `id`='".$arr[3]."'" );
                    if ( $field )
                    {
                        $CNOA_DB->db_update( $dt, $this->t_odoc_view_field, "WHERE `id`='".$arr[3]."'" );
                    }
                    $CNOA_DB->db_update( array(
                        "name" => $dt['name']
                    ), $this->t_odoc_bind_flow_kj, "WHERE `field`='".$arr[3]."'" );
                    $CNOA_DB->db_update( array(
                        "name" => $dt['name']
                    ), $this->t_odoc_view_field_list, "WHERE `field`='".$arr[3]."'" );
                    $fieldid = $arr[3];
                }
                else
                {
                    $fieldid = $CNOA_DB->db_insert( $dt, $this->t_odoc_view_field );
                    $CNOA_DB->db_update( array(
                        "field" => $fieldid
                    ), $this->t_odoc_view_field, "WHERE `id`='".$fieldid."'" );
                }
                if ( !$CNOA_DB->db_fieldExists( "odoc_data", "field_".$fieldid ) )
                {
                    if ( $dt['type'] == "textarea" )
                    {
                        $CNOA_DB->query( "ALTER TABLE  `cnoa_odoc_data` ADD  `field_".$fieldid."` TEXT NOT NULL COMMENT  '".$dt['name']."'" );
                    }
                    else
                    {
                        $CNOA_DB->query( "ALTER TABLE  `cnoa_odoc_data` ADD  `field_".$fieldid."` VARCHAR( 100 ) NOT NULL COMMENT  '".$dt['name']."'" );
                    }
                }
                else if ( $dt['type'] == "textarea" )
                {
                    $CNOA_DB->query( "ALTER TABLE `cnoa_odoc_data` CHANGE `field_".$fieldid."` `field_{$fieldid}` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '{$dt['name']}'" );
                }
                else
                {
                    $CNOA_DB->query( "ALTER TABLE `cnoa_odoc_data` CHANGE `field_".$fieldid."` `field_{$fieldid}` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '{$dt['name']}'" );
                }
                $fields[] = $fieldid;
            }
            $fieldsets[] = $fieldsetid;
        }
        if ( 0 < count( $fieldsets ) )
        {
            $CNOA_DB->db_delete( $this->t_odoc_view_fieldset, "WHERE `from`='".$from."' AND `id` NOT IN (".implode( ",", $fieldsets ).")" );
        }
        else
        {
            $CNOA_DB->db_delete( $this->t_odoc_view_fieldset, "WHERE `from`='".$from."'" );
        }
        $fields = array_unique( $fields );
        if ( 0 < count( $fields ) )
        {
            $CNOA_DB->db_delete( $this->t_odoc_view_field, "WHERE `from`='".$from."' AND `id` NOT IN (".implode( ",", $fields ).")" );
            $CNOA_DB->db_delete( $this->t_odoc_bind_flow_kj, "WHERE `from`='".$from."' AND `field` NOT IN (".implode( ",", $fields ).")" );
            $CNOA_DB->db_delete( $this->t_odoc_view_field_list, "WHERE `from`='".$from."' AND `field` NOT IN (".implode( ",", $fields ).")" );
        }
        else
        {
            $CNOA_DB->db_delete( $this->t_odoc_view_field, "WHERE `from`='".$from."'" );
            $CNOA_DB->db_delete( $this->t_odoc_bind_flow_kj, "WHERE `from`='".$from."'" );
            $CNOA_DB->db_delete( $this->t_odoc_view_field_list, "WHERE `from`='".$from."'" );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadFormData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $from = getpar( $_POST, "from", "send" );
        $dbFieldSetDB = $CNOA_DB->db_select( array( "fieldset", "name" ), $this->t_odoc_view_fieldset, "WHERE `from` = '".$from."' ORDER BY `id` ASC" );
        $dbFieldDB = $CNOA_DB->db_select( "*", $this->t_odoc_view_field, "WHERE `from` = '".$from."' ORDER BY `id` ASC" );
        if ( !is_array( $dbFieldSetDB ) )
        {
            $dbFieldSetDB = array( );
        }
        if ( !is_array( $dbFieldDB ) )
        {
            $dbFieldDB = array( );
        }
        $dbFieldArr = array( );
        $temp = array( );
        $dataList = array( );
        foreach ( $dbFieldDB as $k => $v )
        {
            if ( $v['type'] == "textarea" )
            {
                $dataList[] = $v;
                $dataList[] = array( );
            }
            else
            {
                $dataList[] = $v;
            }
        }
        foreach ( $dataList as $k => $v )
        {
            if ( $k % 2 != 0 )
            {
                $temp['secname'] = $v['name'];
                $temp['secfield'] = $v['field'];
                $dbFieldArr[] = $temp;
                $temp = array( );
            }
            else
            {
                $temp['fstname'] = $v['name'];
                $temp['type'] = $v['type'];
                $temp['fstfield'] = $v['field'];
                $temp['fieldset'] = $v['fieldset'];
            }
        }
        $result = array(
            "success" => TRUE,
            "fieldset" => $dbFieldSetDB,
            "field" => $dbFieldArr
        );
        echo json_encode( $result );
        exit( );
    }

    private function _getFieldList( )
    {
        global $CNOA_DB;
        $from = getpar( $_POST, "from", "send" );
        $dblist = $CNOA_DB->db_select( "*", $this->t_odoc_view_field, "WHERE `from` = '".$from."' " );
        $column = $CNOA_DB->db_select( "*", $this->t_odoc_view_field_list, "WHERE `from` = '".$from."' " );
        if ( !is_array( $column ) )
        {
            $column = array( );
        }
        $columnArr = array( );
        foreach ( $column as $k => $v )
        {
            $columnArr[] = $v['field'];
        }
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $k => $v )
        {
            if ( !in_array( $v['field'], $columnArr ) )
            {
                $data[] = $v;
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _submitList( )
    {
        global $CNOA_DB;
        $data = $_POST['data'];
        $from = getpar( $_POST, "from", "send" );
        $dataArr = json_decode( $data, TRUE );
        $GLOBALS['dataSafeScan']->stopAttackFromArray( $dataArr );
        $order = 1;
        $CNOA_DB->db_delete( $this->t_odoc_view_field_list, "WHERE `from` = '".$from."' " );
        foreach ( $dataArr as $k => $v )
        {
            if ( empty( $v['title'] ) )
            {
                $v['title'] = $v['name'];
            }
            if ( empty( $v['width'] ) )
            {
                $v['width'] = 100;
            }
            $v['from'] = $from;
            $v['order'] = $order;
            $CNOA_DB->db_insert( $v, $this->t_odoc_view_field_list );
            ++$order;
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getFlowList( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "flowId", "name", "sortId" ), "wf_s_flow", "WHERE `status` = 1 ORDER BY `sortId` ASC, `name` ASC" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array(
            array( "flowId" => "0", "name" => "未绑定流程" )
        );
        include_once( CNOA_PATH."/app/wf/inc/wfCommon.class.php" );
        $sortList = app::loadapp( "wf", "flowSetSort" )->api_getSortList( );
        foreach ( $dblist as $k => $v )
        {
            $v['name'] = "[".$sortList[$v['sortId']]['name']."] ".$v['name'];
            $data[] = $v;
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _getBindFieldList( )
    {
        global $CNOA_DB;
        $from = getpar( $_POST, "from", "send" );
        $id = getpar( $_POST, "id", "0" );
        $dblist = $CNOA_DB->db_select( "*", $this->t_odoc_bind_flow_kj, "WHERE `from` = '".$from."' AND `id`='{$id}'" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $k => $v )
        {
            unset( $v['id'] );
            $data[] = $v;
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _getFlowJsonData( )
    {
        global $CNOA_DB;
        $from = getpar( $_GET, "from", "send" );
        $dblist = $CNOA_DB->db_select( "*", $this->t_odoc_bind_flow, "WHERE `from` = '".$from."' " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $flowIds = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $flowIds[] = $v['flowId'];
        }
        include_once( CNOA_PATH."/app/wf/inc/wfCommon.class.php" );
        $flowList = app::loadapp( "wf", "flowSetFlow" )->api_getFlowData( array(
            "flowIdArr" => $flowIds,
            "field" => array( "flowId", "name" )
        ) );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['flowName'] = $flowList[$v['flowId']]['name'];
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $dblist;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _addBindFlowId( )
    {
        global $CNOA_DB;
        $flowId = getpar( $_POST, "flowId", 0 );
        $from = getpar( $_POST, "from", "send" );
        $data['flowId'] = $flowId;
        $data['from'] = $from;
        if ( $from == "borrow" )
        {
            $data['field'] = "title";
            $data['type'] = "title";
            $data['name'] = "文件标题";
            $CNOA_DB->db_insert( $data, $this->t_odoc_bind_flow_kj );
            $data['field'] = "number";
            $data['type'] = "number";
            $data['name'] = "文件字号";
            $CNOA_DB->db_insert( $data, $this->t_odoc_bind_flow_kj );
            $data['field'] = "agree";
            $data['type'] = "agree";
            $data['name'] = "是否同意";
            $CNOA_DB->db_insert( $data, $this->t_odoc_bind_flow_kj );
            $data['field'] = "optinion";
            $data['type'] = "optinion";
            $data['name'] = "意见";
            $CNOA_DB->db_insert( $data, $this->t_odoc_bind_flow_kj );
            $data['field'] = "stime";
            $data['type'] = "stime";
            $data['name'] = "借阅时间";
            $CNOA_DB->db_insert( $data, $this->t_odoc_bind_flow_kj );
            $data['field'] = "etime";
            $data['type'] = "etime";
            $data['name'] = "归还时间";
            $CNOA_DB->db_insert( $data, $this->t_odoc_bind_flow_kj );
            $data['field'] = "postuid";
            $data['type'] = "postuid";
            $data['name'] = "借阅人";
            $CNOA_DB->db_insert( $data, $this->t_odoc_bind_flow_kj );
            $data['field'] = "dept";
            $data['type'] = "dept";
            $data['name'] = "借阅部门";
            $CNOA_DB->db_insert( $data, $this->t_odoc_bind_flow_kj );
            $data['field'] = "reason";
            $data['type'] = "reason";
            $data['name'] = "原由";
            $CNOA_DB->db_insert( $data, $this->t_odoc_bind_flow_kj );
            msg::callback( TRUE, lang( "successopt" ) );
        }
        $dblist = $CNOA_DB->db_select( "*", $this->t_odoc_view_field, "WHERE `from` = '".$from."' " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        if ( $from == "receive" )
        {
            $data['field'] = "deptment";
            $data['type'] = "deptment";
            $data['name'] = "来文单位";
            $CNOA_DB->db_insert( $data, $this->t_odoc_bind_flow_kj );
            $data['field'] = "title";
            $data['type'] = "title";
            $data['name'] = "来文标题";
            $CNOA_DB->db_insert( $data, $this->t_odoc_bind_flow_kj );
            $data['field'] = "number";
            $data['type'] = "number";
            $data['name'] = "来文编号";
            $CNOA_DB->db_insert( $data, $this->t_odoc_bind_flow_kj );
        }
        foreach ( $dblist as $k => $v )
        {
            $data['field'] = $v['field'];
            $data['type'] = $v['type'];
            $data['name'] = $v['name'];
            $CNOA_DB->db_insert( $data, $this->t_odoc_bind_flow_kj );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getFlowFieldList( )
    {
        global $CNOA_DB;
        $from = getpar( $_POST, "from", "send" );
        $id = getpar( $_POST, "id", "0" );
        $dataA = $CNOA_DB->db_getone( "*", $this->t_odoc_bind_flow, "WHERE `from` = '".$from."' AND `id`='{$id}'" );
        $flowId = $dataA['flowId'];
        if ( empty( $flowId ) )
        {
            ( );
            $ds = new dataStore( );
            $ds->data = array( );
            echo $ds->makeJsonData( );
            exit( );
        }
        $dblistA = $CNOA_DB->db_select( "*", $this->t_odoc_view_field, "WHERE `from` = '".$from."' " );
        $dblistB = $CNOA_DB->db_select( "*", $this->t_odoc_bind_flow_kj, "WHERE `from` = '".$from."' AND `id`='{$id}'" );
        if ( !is_array( $dblistA ) )
        {
            $dblistA = array( );
        }
        if ( !is_array( $dblistB ) )
        {
            $dblistB = array( );
        }
        $fieldArr = array( );
        $bindFieldArr = array( );
        foreach ( $dblistB as $k => $v )
        {
            $bindFieldArr[] = $v['field'];
        }
        foreach ( $dblistA as $k => $v )
        {
            if ( !in_array( $v['field'], $bindFieldArr ) )
            {
                $data = array( );
                $data['id'] = $dataA['id'];
                $data['from'] = $dataA['from'];
                $data['flowId'] = $dataA['flowId'];
                $data['field'] = $v['field'];
                $data['name'] = $v['name'];
                $data['type'] = $v['type'];
                $data['kongjian'] = "";
                $CNOA_DB->db_insert( $data, $this->t_odoc_bind_flow_kj );
            }
        }
        unset( $data );
        $dblist = $CNOA_DB->db_select( "*", "wf_s_field", "WHERE `flowId` = ".$flowId." " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array(
            array( "id" => "0", "name" => "未选择控件" )
        );
        foreach ( $dblist as $k => $v )
        {
            if ( !( $v['otype'] == "textfield" ) || !( $v['otype'] == "textarea" ) || !$v['otype'] = $v['type'] == "text" )
            {
                $data[] = $v;
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _submitBindFlowField( )
    {
        global $CNOA_DB;
        $data = $_POST['data'];
        $id = $_POST['id'];
        $data = json_decode( $data, TRUE );
        $GLOBALS['dataSafeScan']->stopAttackFromArray( $data );
        $from = getpar( $_POST, "from", "send" );
        foreach ( $data as $k => $v )
        {
            $CNOA_DB->db_update( array(
                "kongjian" => $v['kongjian']
            ), $this->t_odoc_bind_flow_kj, "WHERE `from` = '".$from."' AND `field` = '{$v['field']}' AND `id` = '{$id}' " );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _submitDept( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        if ( $uid != 1 )
        {
            msg::callback( FALSE, "对不起，该权限只能由超级管理员设置" );
        }
        $dept = getpar( $_POST, "dept", 0 );
        if ( !file_exists( CNOA_PATH_FILE."/config/customerAgent.php" ) )
        {
            touch( CNOA_PATH_FILE."/config/customerAgent.php" );
        }
        $handle = fopen( CNOA_PATH_FILE."/config/customerAgent.php", "w+" );
        $content = "<?php return array('stationid'=>".$_POST['stationid'].", 'deptId'=>".$_POST['deptId'].", 'jobId'=>".$_POST['jobId'].", 'usemessager'=>".$_POST['usemessager'].", 'usesend'=>".$_POST['usesend'].", 'usereceive'=>".$_POST['usereceive']."); ?>";
        fwrite( $handle, $content );
        fclose( $handle );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _deleteData( )
    {
        global $CNOA_DB;
        $ids = getpar( $_POST, "ids", 0 );
        $ids = substr( $ids, 0, -1 );
        $CNOA_DB->db_delete( $this->t_agent_data, "WHERE `id` IN (".$ids.") " );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _removeAgent( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $removeToUid = getpar( $_POST, "removeToUid", 0 );
        $ids = getpar( $_POST, "ids", 0 );
        $CNOA_DB->db_update( array(
            "uid" => $removeToUid
        ), $this->t_agent_data, "WHERE `id` IN (".substr( $ids, 0, -1 ).") " );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getWordList( )
    {
        global $CNOA_DB;
        $from = getpar( $_POST, "from", "send" );
        $fieldDB = $CNOA_DB->db_select( "*", $this->t_odoc_view_field, "WHERE `from` = '".$from."' " );
        if ( !is_array( $fieldDB ) )
        {
            $fieldDB = array( );
        }
        $fieldName = array( );
        foreach ( $fieldDB as $k => $v )
        {
            $field[] = $v['field'];
            $fieldName[$v['field']] = $v['name'];
        }
        $data = $CNOA_DB->db_select( "*", $this->t_odoc_view_field_list, "WHERE `from` = '".$from."' ORDER BY `order` ASC " );
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        $dblist = array( );
        foreach ( $data as $k => $v )
        {
            if ( !in_array( $v['field'], $field ) )
            {
                $CNOA_DB->db_delete( $this->t_odoc_view_field_list, "WHERE `from` = '".$from."' AND `field` = {$v['field']}" );
            }
            else
            {
                $v['name'] = $fieldName[$v['field']];
                $dblist[] = $v;
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $dblist;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _loadPermitFormData( )
    {
        global $CNOA_DB;
        $file = include( CNOA_PATH_FILE."/config/customerAgent.php" );
        $file['deptName'] = app::loadapp( "main", "struct" )->api_getNameById( $file['deptId'] );
        ( );
        $ds = new dataStore( );
        $ds->data = $file;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _editLoadBindFlowData( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", 0 );
        $data = array( );
        $info = $CNOA_DB->db_getone( "*", $this->t_odoc_bind_flow, "WHERE `id`='".$id."'" );
        $data['name'] = $info['name'];
        $data['bindFlow'] = $info['flowId'];
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _setStatus( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", 0 );
        $info = $CNOA_DB->db_getone( "*", $this->t_odoc_bind_flow, "WHERE `id`='".$id."'" );
        $data = array( );
        $data['status'] = $info['status'] == 1 ? 0 : 1;
        $CNOA_DB->db_update( $data, $this->t_odoc_bind_flow, "WHERE `id`='".$id."'" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _deleteFlow( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", 0 );
        $CNOA_DB->db_delete( $this->t_odoc_bind_flow, "WHERE `id`='".$id."'" );
        $CNOA_DB->db_delete( $this->t_odoc_bind_flow_kj, "WHERE `id`='".$id."'" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function api_loadFormData( )
    {
        $this->_loadFormData( );
    }

}

?>
