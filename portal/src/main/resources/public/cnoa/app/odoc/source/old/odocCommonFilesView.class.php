<?php
//decode by qq2859470

class odocCommonFilesView
{

    private $t_files_dangan = "odoc_files_dangan";
    private $t_read_file = "odoc_read_file";
    private $t_dangan_room = "odoc_files_dangan_room";
    private $t_anjuan_list = "odoc_files_anjuan_list";
    private $t_send_list = "odoc_send_list";
    private $t_receive_list = "odoc_receive_list";
    private $t_tpl_list = "odoc_setting_template_list";
    private $t_setting_word_level = "odoc_setting_word_level";
    private $t_setting_word_hurry = "odoc_setting_word_hurry";
    private $t_flow = "odoc_setting_flow";
    private $t_flow_step = "odoc_setting_flow_step";
    private $t_step = "odoc_step";
    private $t_step_temp = "odoc_step_temp";
    private $t_odoc_data = "odoc_data";
    private $fromType = "send";
    private $table_list = "";

    public function __construct( )
    {
    }

    public function __destruct( )
    {
    }

    public function run( )
    {
        global $CNOA_DB;
        $act = getpar( $_GET, "act", "" );
        switch ( $act )
        {
        case "base" :
            $this->_getHtml( );
            return;
        case "getZhengWen" :
            $this->_getZhengWen( );
            return;
        case "getFormHtml" :
            $this->_getFormHtml( );
            return;
        case "getAttachList" :
            $this->_getAttachList( );
            return;
        case "getStepList" :
            $this->_getStepListt( );
            return;
        }
        $this->_loadBaseInfo( );
    }

    public function _getHtml( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        global $CNOA_SESSION;
        $id = getpar( $_GET, "id", "" );
        $GLOBALS['GLOBALS']['id'] = $id;
        $GLOBALS['GLOBALS']['func'] = $CNOA_CONTROLLER->func;
        $GLOBALS['GLOBALS']['action'] = $CNOA_CONTROLLER->action;
        $GLOBALS['GLOBALS']['fromType'] = $this->fromType;
        $GLOBALS['GLOBALS']['CNOA_SYSTEM_NAME'] = "查看档案";
        $GLOBALS['GLOBALS']['LANGUAGE'] = $CNOA_SESSION->get( "LANGUAGE" );
        $GLOBALS['GLOBALS']['CNOA_USERNAME'] = $CNOA_SESSION->get( "TRUENAME" );
        $CNOA_CONTROLLER->loadViewCustom( $CNOA_CONTROLLER->appPath."/tpl/default/viewOdocFile.htm", TRUE, TRUE );
        exit( );
    }

    public function _getFormHtml( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = intval( getpar( $_POST, "id", "" ) );
        $fromTypeI = $this->fromType == "send" ? 1 : 2;
        $info = $CNOA_DB->db_getone( "*", $this->t_files_dangan, "WHERE `id`='".$id."'" );
        $id = $info['fromid'];
        $where = "WHERE `fromType`='".$fromTypeI."' AND `fromId`='{$id}' AND `status`=2";
        $maxid = $CNOA_DB->db_getmax( "id", $this->t_step, $where );
        if ( empty( $maxid ) )
        {
            $where = "WHERE `fromType`='".$fromTypeI."' AND `fromId`='{$id}' AND `status`=1";
            $maxid = $CNOA_DB->db_getmax( "id", $this->t_step, $where );
        }
        $formPath = CNOA_PATH_FILE."/common/odoc/".$this->fromType.( "/".$id."/form.history.{$maxid}.php" );
        if ( file_exists( $formPath ) )
        {
            $form = include( $formPath );
        }
        else
        {
            $form = " ";
        }
        $form = str_replace( array( "\r\n", "\n", "\"" ), array( "&#13;", "&#13;", "'" ), $form );
        echo $form;
        exit( );
    }

    public function _getAttachList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = getpar( $_GET, "id", getpar( $_POST, "id", 0 ) );
        $info = $CNOA_DB->db_getone( "*", $this->t_files_dangan, "WHERE `id`='".$id."'" );
        $fromid = $info['fromid'];
        if ( !( $info['from'] == 3 ) )
        {
            $info = $CNOA_DB->db_getone( "*", $this->table_list, "WHERE `id`='".$fromid."'" );
        }
        ( );
        $fs = new fs( );
        $info['attach'] = json_decode( $info['attach'], TRUE );
        $info['attachCount'] = !$info['attach'] ? 0 : count( $info['attach'] );
        $info['attach'] = $fs->getDownLoadItems4normal( $info['attach'], TRUE );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = array(
            "attach" => $info['attach']
        );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    public function _getStepListt( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $func = $CNOA_CONTROLLER->func;
        $action = $CNOA_CONTROLLER->action;
        $id = getpar( $_GET, "id", getpar( $_POST, "id", 0 ) );
        $danganInfo = $CNOA_DB->db_getone( "*", $this->t_files_dangan, "WHERE `id`='".$id."'" );
        $GLOBALS['_POST']['fid'] = $danganInfo['fromid'];
        if ( $danganInfo['from'] == 1 )
        {
            app::loadapp( "odoc", "sendCheck" )->api_getStepList( );
            exit( );
        }
        if ( $danganInfo['from'] == 2 )
        {
            app::loadapp( "odoc", "receiveCheck" )->api_getStepList( );
        }
        exit( );
    }

    public function _loadBaseInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $id = getpar( $_POST, "id", 0 );
        $info = $CNOA_DB->db_getone( "*", $this->t_files_dangan, "WHERE `id`='".$id."'" );
        $a['title'] = "基本信息";
        $a['list'][] = array(
            "title" => "文件标题",
            "value" => $info['title']
        );
        $a['list'][] = array(
            "title" => "密级",
            "value" => $CNOA_DB->db_getfield( "title", $this->t_setting_word_level, "WHERE `tid`='".$info['level']."'" )
        );
        $a['list'][] = array(
            "title" => "件号",
            "value" => $info['filesnum']
        );
        $a['list'][] = array(
            "title" => "文件字号",
            "value" => $info['number']
        );
        $a['list'][] = array(
            "title" => "成文日期",
            "value" => formatdate( $info['senddate'], "Y-m-d" )
        );
        $a['list'][] = array(
            "title" => "责任者",
            "value" => $info['respon']
        );
        $a['list'][] = array(
            "title" => "页号",
            "value" => $info['page']
        );
        $a['list'][] = array(
            "title" => "归档日期",
            "value" => formatdate( $info['collectdate'], "Y-m-d" )
        );
        $a['list'][] = array(
            "title" => "文种",
            "value" => $CNOA_DB->db_getfield( "title", "odoc_files_dangan_wenzhong", "WHERE `id`='".$info['wenzhong']."'" )
        );
        $a['list'][] = array(
            "title" => "档案室",
            "value" => $CNOA_DB->db_getfield( "title", "odoc_files_dangan_room", "WHERE `id`='".$info['danganshi']."'" )
        );
        $a['list'][] = array(
            "title" => "案卷",
            "value" => $CNOA_DB->db_getfield( "title", "odoc_files_anjuan_list", "WHERE `id`='".$info['anjuan']."'" )
        );
        $a['list'][] = array(
            "title" => "备注",
            "value" => nl2br( $info['note'] )
        );
        if ( count( $a['list'] ) % 2 == 1 )
        {
            $a['list'][] = array( "title" => "", "value" => "" );
        }
        echo json_encode( $a );
        exit( );
    }

    public function _getZhengWen( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        global $CNOA_SESSION;
        $id = getpar( $_GET, "id", getpar( $_POST, "id", 0 ) );
        $info = $CNOA_DB->db_getone( "*", $this->t_files_dangan, "WHERE `id`='".$id."'" );
        $fromid = $info['fromid'];
        $fromTypeI = $this->fromType == "send" ? 1 : 2;
        $maxid = $CNOA_DB->db_getmax( "id", $this->t_step, "WHERE `fromType`='".$fromTypeI."' AND `fromId`='{$fromid}' AND `status`=2" );
        if ( empty( $maxid ) )
        {
            $where = "WHERE `fromType`='".$fromTypeI."' AND `fromId`='{$fromid}' AND `status`=1";
            $maxid = $CNOA_DB->db_getmax( "id", $this->t_step, $where );
        }
        $filePath = CNOA_PATH_FILE."/common/odoc/".$this->fromType.( "/".$fromid."/doc.history.{$maxid}.php" );
        if ( file_exists( $filePath ) )
        {
            $file = @file_get_contents( $filePath );
        }
        else
        {
            $file = " ";
        }
        echo $file;
        exit( );
    }

}

?>
