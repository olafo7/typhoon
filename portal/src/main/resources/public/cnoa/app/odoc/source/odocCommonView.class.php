<?php
//decode by qq2859470

class odocCommonView
{

    private $t_send_list = "odoc_send_list";
    private $t_receive_list = "odoc_receive_list";
    private $t_tpl_list = "odoc_setting_template_list";
    private $t_setting_word_level = "odoc_setting_word_level";
    private $t_setting_word_hurry = "odoc_setting_word_hurry";
    private $t_flow = "odoc_setting_flow";
    private $t_flow_step = "odoc_setting_flow_step";
    private $t_step = "odoc_step";
    private $t_step_temp = "odoc_step_temp";
    private $fromType = "send";
    private $table_list = "";

    public function __construct( )
    {
    }

    public function __destruct( )
    {
    }

    public function run( $fromType = "send" )
    {
        $this->fromType = $fromType;
        $this->table_list = $this->fromType == "send" ? $this->t_send_list : $this->t_receive_list;
        $act = getpar( $_GET, "act", "" );
        switch ( $act )
        {
        case "getHtml" :
            $this->_getHtml( );
            break;
        case "getZhengWen" :
            $this->_getZhengWen( );
            break;
        case "getFormHtml" :
            $this->_getFormHtml( );
            break;
        case "getAttachList" :
            $this->_getAttachList( );
            break;
        case "getStepList" :
            $this->_getStepListt( );
        }
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
        $GLOBALS['GLOBALS']['CNOA_SYSTEM_NAME'] = $this->fromType == "send" ? "查看发文" : "查看收文";
        $GLOBALS['GLOBALS']['CNOA_USERNAME'] = $CNOA_SESSION->get( "TRUENAME" );
        $CNOA_CONTROLLER->loadViewCustom( $CNOA_CONTROLLER->appPath."/tpl/default/viewOdoc.htm", TRUE, TRUE );
        exit( );
    }

    public function _getFormHtml( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = intval( getpar( $_POST, "id", "" ) );
        $fromTypeI = $this->fromType == "send" ? 1 : 2;
        $maxid = $CNOA_DB->db_getmax( "id", $this->t_step, "WHERE `fromType`='".$fromTypeI."' AND `fromId`='{$id}' AND `stepType`=1 AND `status`=2" );
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
            $form = "无发文单";
        }
        $form = str_replace( array( "\r\n", "\n", "\"" ), array( "&#13;", "&#13;", "'" ), $form );
        echo $form;
        exit( );
    }

    public function _getAttachList( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = getpar( $_POST, "id", 0 );
        $info = $CNOA_DB->db_getone( "*", $this->table_list, "WHERE `id`='".$id."'" );
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
        if ( $func == "send" )
        {
            app::loadapp( "odoc", "sendCheck" )->api_getStepList( );
            exit( );
        }
        if ( $func == "receive" )
        {
            app::loadapp( "odoc", "receiveCheck" )->api_getStepList( );
            exit( );
        }
        if ( $action == "send" )
        {
            app::loadapp( "odoc", "sendCheck" )->api_getStepList( );
            exit( );
        }
        if ( $action == "receive" )
        {
            app::loadapp( "odoc", "receiveCheck" )->api_getStepList( );
        }
        exit( );
    }

    public function _getZhengWen( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        global $CNOA_SESSION;
        $id = getpar( $_GET, "id", "" );
        $fromTypeI = $this->fromType == "send" ? 1 : 2;
        $maxid = $CNOA_DB->db_getmax( "id", $this->t_step, "WHERE `fromType`='".$fromTypeI."' AND `fromId`='{$id}' AND `stepType`=1 AND `status`=2" );
        if ( empty( $maxid ) )
        {
            $where = "WHERE `fromType`='".$fromTypeI."' AND `fromId`='{$id}' AND `status`=1";
            $maxid = $CNOA_DB->db_getmax( "id", $this->t_step, $where );
        }
        $filePath = CNOA_PATH_FILE."/common/odoc/".$this->fromType.( "/".$id."/doc.history.{$maxid}.php" );
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
