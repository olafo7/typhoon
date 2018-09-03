<?php
//decode by qq2859470

class odocReadSend extends model
{

    private $t_odoc_view_fieldset = "odoc_view_fieldset";
    private $t_odoc_view_field = "odoc_view_field";
    private $t_odoc_data = "odoc_data";
    private $t_odoc_view_field_list = "odoc_view_field_list";
    private $t_odoc_bind_flow_kj = "odoc_bind_flow_kj";
    private $t_odoc_fenfa = "odoc_fenfa";
    private $t_s_flow_other_app_data = "wf_s_flow_other_app_data";
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
            $this->_loadPage( );
            break;
        case "loadLayout" :
            $this->_loadLayout( );
            break;
        case "getJsonList" :
            $this->_getJsonList( );
            break;
        case "loadColumn" :
            $this->_loadColumn( );
            break;
        case "loadAttachList" :
            $this->_loadAttachList( );
        }
    }

    private function _loadPage( )
    {
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        global $CNOA_DB;
        $from = getpar( $_GET, "from", "" );
        switch ( $from )
        {
        case "viewflow" :
            $GLOBALS['GLOBALS']['app']['uFlowId'] = getpar( $_GET, "uFlowId", 0 );
            $GLOBALS['GLOBALS']['app']['step'] = getpar( $_GET, "step", 0 );
            $uid = $CNOA_SESSION->get( "UID" );
            $CNOA_DB->db_update( array(
                "isread" => 1,
                "uid" => $uid,
                "viewtime" => $GLOBALS['CNOA_TIMESTAMP']
            ), "wf_u_fenfa", "WHERE `touid` = ".$uid." AND `uFlowId` = {$GLOBALS['app']['uFlowId']} " );
            $GLOBALS['GLOBALS']['app']['wf']['type'] = "done";
            $DB = $CNOA_DB->db_getone( array( "status", "flowId" ), "wf_u_flow", "WHERE `uFlowId`='".$GLOBALS['app']['uFlowId']."'" );
            $GLOBALS['GLOBALS']['app']['flowId'] = $DB['flowId'];
            $flow = $CNOA_DB->db_getone( array( "tplSort", "flowType" ), "wf_s_flow", "WHERE `flowId` = ".$DB['flowId']." " );
            $GLOBALS['GLOBALS']['app']['wf']['allowCallback'] = 0;
            $GLOBALS['GLOBALS']['app']['wf']['allowCancel'] = 0;
            $GLOBALS['GLOBALS']['app']['allowPrint'] = "false";
            $GLOBALS['GLOBALS']['app']['allowFenfa'] = "false";
            $GLOBALS['GLOBALS']['app']['allowExport'] = "false";
            $GLOBALS['GLOBALS']['app']['status'] = 1;
            $GLOBALS['GLOBALS']['app']['wf']['tplSort'] = $flow['tplSort'];
            $GLOBALS['GLOBALS']['app']['wf']['flowType'] = $flow['flowType'];
            $GLOBALS['GLOBALS']['app']['wf']['owner'] = 0;
            $tplPath = CNOA_PATH."/app/wf/tpl/default/flow/use/showflow.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            exit( );
        }
    }

    private function _loadLayout( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $dbFieldSetDB = $CNOA_DB->db_select( "*", $this->t_odoc_view_fieldset, "WHERE `from` = 'send' " );
        $dbFieldDB = $CNOA_DB->db_select( "*", $this->t_odoc_view_field, "WHERE `from` = 'send' " );
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
        foreach ( $dbFieldDB as $k => $v )
        {
            if ( $v['field'] % 2 == 0 )
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

    private function _getJsonList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $WHERE = "WHERE `from` = 'send' AND `uid` = ".$uid." ";
        $start = getpar( $_POST, "start", 0 );
        $from = getpar( $_GET, "from" );
        if ( $from != "desktop" )
        {
            $WHERE2 = $WHERE.( "LIMIT ".$start.", {$this->rows}" );
        }
        else
        {
            $WHERE2 = $WHERE;
        }
        $dblist = $CNOA_DB->db_select( "*", $this->t_odoc_fenfa, $WHERE2 );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $idArr = array( 0 );
        $date = array( );
        foreach ( $dblist as $k => $v )
        {
            $idArr[] = $v['id'];
            $date[$v['id']] = date( "Y-m-d H:i", $v['posttime'] );
        }
        $dblist = $CNOA_DB->db_select( "*", $this->t_odoc_data, "WHERE `id` IN (".implode( ",", $idArr ).")  ORDER BY `id` DESC" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $uFlows = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['posttime'] = $date[$v['id']];
            $uFlows[] = $v['uFlowId'];
        }
        if ( $from == "desktop" )
        {
            $uFlowList = $CNOA_DB->db_select( array( "uFlowId", "flowNumber", "flowName" ), "wf_u_flow", "WHERE `uFlowId` IN (".implode( ",", $uFlows ).")" );
            if ( !is_array( $uFlowList ) )
            {
                $uFlowList = array( );
            }
            $uFlowLists = array( );
            foreach ( $uFlowList as $v )
            {
                $uFlowLists[$v['uFlowId']] = $v;
            }
            foreach ( $dblist as $k => $v )
            {
                $dblist[$k]['flowNumber'] = $uFlowLists[$v['uFlowId']]['flowNumber'];
                $dblist[$k]['flowName'] = $uFlowLists[$v['uFlowId']]['flowName'];
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $dblist;
        if ( $from != "desktop" )
        {
            $ds->total = $CNOA_DB->db_getcount( $this->t_odoc_fenfa, $WHERE );
        }
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _loadColumn( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->t_odoc_view_field_list, "WHERE `from` = 'send' " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $field = array( );
        foreach ( $dblist as $k => $v )
        {
            $field[] = "field_".$v['field'];
        }
        $result = array(
            "success" => TRUE,
            "field" => $field,
            "column" => $dblist
        );
        echo json_encode( $result );
        exit( );
    }

    private function _loadAttachList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = getpar( $_POST, "id", "0" );
        $uid = $CNOA_SESSION->get( "UID" );
        $list = array( );
        $fenfaInfo = $CNOA_DB->db_getone( "*", $this->t_odoc_fenfa, "WHERE `id`='".$id."' AND `uid`='{$uid}'" );
        $odocInfo = $CNOA_DB->db_getone( "*", $this->t_odoc_data, "WHERE `id`='".$id."'" );
        $uFlowId = $odocInfo['uFlowId'];
        $attachList = json_decode( $odocInfo['attach'], TRUE );
        if ( !is_array( $attachList ) )
        {
            $attachList = array( );
        }
        ( );
        $fs = new fs( );
        if ( $fenfaInfo['viewWord'] == 1 )
        {
            $attachid = intval( $attachList[0] );
            if ( $attachid == 0 )
            {
                $list['word'] = "无文件可以查看";
            }
            else
            {
                $wordInfo = $fs->getDownLoadItems4normal( array(
                    $attachList[0]
                ), TRUE );
                if ( ereg( "\\[正文\\]", $wordInfo ) )
                {
                    $list['word'] = $wordInfo;
                }
                else
                {
                    $list['word'] = "无文件可以查看";
                }
            }
        }
        if ( $fenfaInfo['viewAttach'] == 1 )
        {
            array_shift( &$attachList );
            if ( is_array( $attachList ) )
            {
                if ( 0 < count( $attachList ) )
                {
                    $list['attach'] = $fs->getDownLoadItems4normal( $attachList, TRUE );
                }
                else
                {
                    $list['attach'] = "无文件可以查看";
                }
            }
            else
            {
                $list['attach'] = "无文件可以查看";
            }
        }
        if ( $fenfaInfo['viewForm'] == 1 )
        {
            $targetPath = ( CNOA_PATH_FILE."/common/odoc/sendForm/".$uFlowId % 300 )."/".$uFlowId.".php";
            $list['form'] = file_get_contents( $targetPath );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $list;
        echo $dataStore->makeJsonData( );
        exit( );
    }

}

?>
