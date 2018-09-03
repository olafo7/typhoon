<?php
//decode by qq2859470

class odocSettingTemplate extends model
{

    private $t_type_list = "odoc_setting_word_type";
    private $t_list = "odoc_setting_template_list";

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
        case "getTypeList" :
            $this->_getTypeList( );
            break;
        case "getJsonData" :
            $this->_getJsonData( );
            break;
        case "add" :
            $this->_add( );
            break;
        case "deleteData" :
            $this->_deleteData( );
            break;
        case "loadTemplate" :
            $this->_loadTemplate( );
        case "editLoadFormData" :
            $this->_editLoadFormData( );
            break;
        case "editTemplate" :
            $this->_editTemplate( );
            break;
        case "editTemplateTpl" :
            $this->_editTemplateTpl( );
            break;
        case "saveDocTpl" :
            $this->_saveDocTpl( );
            break;
        case "submit" :
            $this->_submit( );
            break;
        case "getStructTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            echo app::loadapp( "main", "struct" )->api_getStructTree( );
            exit( );
        }
    }

    private function _loadpage( )
    {
    }

    private function _getTypeList( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "tid", "title" ), $this->t_type_list, "ORDER BY `order` ASC " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        $WHERE = "WHERE 1 ";
        $s_title = getpar( $_POST, "title", "" );
        $s_type = getpar( $_POST, "type", 0 );
        if ( !empty( $s_title ) )
        {
            $WHERE .= "AND `title` LIKE '%".$s_title."%' ";
        }
        if ( !empty( $s_type ) )
        {
            $WHERE .= "AND `type` = '".$s_type."' ";
        }
        $dblist = $CNOA_DB->db_select( "*", $this->t_list, $WHERE."ORDER BY `order` ASC" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $v )
        {
            $uidArr[] = $v['postuid'];
        }
        $truenameArr = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['postname'] = $truenameArr[$v['postuid']]['truename'];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        $dataStore->total = $CNOA_DB->db_getcount( $this->t_list, $WHERE );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _add( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $id = getpar( $_POST, "id", 0 );
        $data['title'] = getpar( $_POST, "title", "" );
        $data['order'] = getpar( $_POST, "order", 0 );
        $data['type'] = getpar( $_POST, "type", 0 );
        $data['postuid'] = $CNOA_SESSION->get( "UID" );
        $data['fromType'] = getpar( $_POST, "fromType", 1 );
        $num = $CNOA_DB->db_getcount( $this->t_list, "WHERE `id` != '".$id."' AND `title` = '{$data['title']}' AND `type` = '{$data['type']}' " );
        if ( 0 < $num )
        {
            msg::callback( FALSE, "该名称已存在" );
        }
        if ( !empty( $id ) )
        {
            $CNOA_DB->db_update( $data, $this->t_list, "WHERE `id` = '".$id."'" );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 3303, $data['title'], "模板信息" );
        }
        else
        {
            $CNOA_DB->db_insert( $data, $this->t_list );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3303, $data['title'], "模板信息" );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _deleteData( )
    {
        global $CNOA_DB;
        $ids = getpar( $_POST, "ids", 0 );
        $ids = substr( $ids, 0, -1 );
        $DB = $CNOA_DB->db_select( array( "title" ), $this->t_list, "WHERE `id` IN (".$ids.") " );
        $CNOA_DB->db_delete( $this->t_list, "WHERE `id` IN (".$ids.") " );
        foreach ( $DB as $v )
        {
            app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 3304, $v['title'], "模板" );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadTemplate( )
    {
        global $CNOA_DB;
        $id = intval( getpar( $_GET, "id", 0 ) );
        $infoDb = $CNOA_DB->db_getone( array( "template" ), $this->t_list, "WHERE `id`='".$id."' ORDER BY `order` ASC" );
        if ( !empty( $infoDb['template'] ) )
        {
            echo file_get_contents( CNOA_PATH_FILE.( "/common/odoc/template/".$id."/{$infoDb['template']}.php" ) );
            exit( );
        }
        echo " ";
        exit( );
    }

    private function _editTemplate( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        $GLOBALS['GLOBALS']['id'] = intval( getpar( $_GET, "id" ) );
        $GLOBALS['GLOBALS']['CNOA_SYSTEM_NAME'] = "修改发文模板";
        $CNOA_CONTROLLER->loadViewCustom( $CNOA_CONTROLLER->appPath."/tpl/default/setting/editTemplate.htm", TRUE, TRUE );
        exit( );
    }

    private function _editTemplateTpl( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        $GLOBALS['GLOBALS']['id'] = intval( getpar( $_GET, "id" ) );
        $GLOBALS['GLOBALS']['CNOA_SYSTEM_NAME'] = "修改发文红头模板";
        $CNOA_CONTROLLER->loadViewCustom( $CNOA_CONTROLLER->appPath."/tpl/default/setting/editTemplateTpl.htm", TRUE, TRUE );
        exit( );
    }

    private function _editLoadFormData( )
    {
        global $CNOA_DB;
        $id = intval( getpar( $_POST, "id", 0 ) );
        $infoDb = $CNOA_DB->db_getone( "*", $this->t_list, "WHERE `id`='".$id."' ORDER BY `order` ASC" );
        $infoDb['tid'] = $infoDb['type'];
        if ( empty( $infoDb['template'] ) )
        {
            $infoDb['template'] = "未制作红头模板 [<a style='color:red' href='index.php?app=odoc&func=setting&action=template&task=editTemplateTpl&id=".$id."' target='cnoa_odoc_edittpl'>制作</a>]";
        }
        else
        {
            $infoDb['template'] .= ".doc [<a style='color:red' href='index.php?app=odoc&func=setting&action=template&task=editTemplateTpl&id=".$id."' target='cnoa_odoc_edittpl'>编辑</a>]";
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $infoDb;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _submit( )
    {
        global $CNOA_DB;
        $data = array( );
        $data['title'] = getpar( $_POST, "title" );
        $data['fromType'] = getpar( $_POST, "fromType", 1 );
        $data['type'] = getpar( $_POST, "tid" );
        $data['num1'] = getpar( $_POST, "num1" );
        $data['num2'] = getpar( $_POST, "num2" );
        $data['about'] = getpar( $_POST, "about" );
        $data['fawenform'] = getpar( $_POST, "fawenform", "", 1, 0 );
        if ( empty( $data['title'] ) )
        {
            msg::callback( FALSE, "模板名称不能为空" );
        }
        if ( empty( $data['type'] ) )
        {
            msg::callback( FALSE, "请选择模板类型" );
        }
        $id = intval( getpar( $_POST, "id", 0 ) );
        $CNOA_DB->db_update( $data, $this->t_list, "WHERE `id`='".$id."'" );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 3303, $data['title'], "模板" );
        msg::callback( FALSE, lang( "successopt" ) );
        exit( );
    }

    private function _saveDocTpl( )
    {
        global $CNOA_DB;
        $id = intval( getpar( $_POST, "id", 0 ) );
        $infoDb = $CNOA_DB->db_getone( "*", $this->t_list, "WHERE `id`='".$id."'" );
        if ( empty( $infoDb['template'] ) )
        {
            $infoDb['template'] = string::rands( 20, 1 );
        }
        $odoc_ext = strtolower( strrchr( $_FILES['msOffice']['name'], "." ) );
        if ( !in_array( strtolower( $odoc_ext ), array( ".doc", ".docx", ".xls", ".xlsx" ) ) )
        {
            echo "0";
            exit( );
        }
        $uploadfile = CNOA_PATH_FILE.( "/common/odoc/template/".$id."/{$infoDb['template']}.php" );
        mkdirs( dirname( $uploadfile ) );
        if ( !move_uploaded_file( $_FILES['msOffice']['tmp_name'], $uploadfile ) )
        {
            echo "0";
            exit( );
        }
        $CNOA_DB->db_update( array(
            "template" => $infoDb['template']
        ), $this->t_list, "WHERE `id`='".$id."'" );
        echo "1";
        exit( );
    }

    public function api_getTemplateList( $typeId, $fromType = 1 )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "title", "id" ), $this->t_list, "WHERE 1 AND `fromType` = '".$fromType."' AND `type` = '{$typeId}' ORDER BY `order` ASC" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

}

?>
