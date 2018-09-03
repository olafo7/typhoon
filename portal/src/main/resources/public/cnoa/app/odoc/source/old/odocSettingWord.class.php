<?php
//decode by qq2859470

class odocSettingWord extends model
{

    private $t_type_list = "odoc_setting_word_type";
    private $t_level_list = "odoc_setting_word_level";
    private $t_hurry_list = "odoc_setting_word_hurry";
    private $t_secret_list = "odoc_setting_word_secret";
    private $t_type_permit = "odoc_setting_word_type_permit";
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
        case "deleteData" :
            $this->_deleteData( );
            break;
        case "add" :
            $this->_add( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $userList );
            exit( );
        case "loadFormData" :
            $this->_loadFormData( );
            break;
        case "submitTypePermit" :
            $this->_submitTypePermit( );
        }
    }

    private function _loadpage( )
    {
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        $from = getpar( $_POST, "from", "type" );
        $start = getpar( $_POST, "start", 0 );
        switch ( $from )
        {
        case "type" :
            $table = $this->t_type_list;
            break;
        case "level" :
            $table = $this->t_level_list;
            break;
        case "hurry" :
            $table = $this->t_hurry_list;
            break;
        case "secret" :
            $table = $this->t_secret_list;
        }
        $dblist = $CNOA_DB->db_select( "*", $table, "ORDER BY `order` ASC LIMIT ".$start.", {$this->rows}" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $v )
        {
            $uidArr[] = $v['postuid'];
        }
        $uids = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['postname'] = $uids[$v['postuid']]['truename'];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        $dataStore->total = $CNOA_DB->db_getcount( $table, "WHERE 1" );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _add( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $tid = getpar( $_POST, "tid", 0 );
        $data['title'] = getpar( $_POST, "title", "" );
        $data['order'] = getpar( $_POST, "order", 0 );
        $data['postuid'] = $CNOA_SESSION->get( "UID" );
        $from = getpar( $_POST, "from", "type" );
        switch ( $from )
        {
        case "type" :
            $table = $this->t_type_list;
            $content = "公文类型";
            break;
        case "level" :
            $table = $this->t_level_list;
            $content = "秘密等级";
            break;
        case "hurry" :
            $table = $this->t_hurry_list;
            $content = "缓急情况";
            break;
        case "secret" :
            $table = $this->t_secret_list;
            $content = "保密期限";
        }
        $num = $CNOA_DB->db_getcount( $table, "WHERE `tid` != '".$tid."' AND `title` = '{$data['title']}'" );
        if ( 0 < $num )
        {
            msg::callback( FALSE, "已存在该名称" );
        }
        if ( !empty( $tid ) )
        {
            $CNOA_DB->db_update( $data, $table, "WHERE `tid` = '".$tid."'" );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 3302, $data['title'], $content );
        }
        else
        {
            $CNOA_DB->db_insert( $data, $table );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3302, $data['title'], $content );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _deleteData( )
    {
        global $CNOA_DB;
        $ids = getpar( $_POST, "ids", 0 );
        $ids = substr( $ids, 0, -1 );
        $idArr = explode( ",", $ids );
        $from = getpar( $_POST, "from", "type" );
        switch ( $from )
        {
        case "type" :
            $table = $this->t_type_list;
            $content = "公文等级";
            break;
        case "level" :
            $table = $this->t_level_list;
            $content = "秘密等级";
            break;
        case "hurry" :
            $table = $this->t_hurry_list;
            $content = "缓急情况";
            break;
        case "secret" :
            $table = $this->t_secret_list;
            $content = "保密期限";
        }
        $DB = $CNOA_DB->db_select( array( "title", "tid" ), $table, "WHERE `tid` IN (".$ids.")" );
        foreach ( $DB as $v )
        {
            $title[$v['tid']] = $v['title'];
        }
        foreach ( $idArr as $v )
        {
            $CNOA_DB->db_delete( $table, "WHERE `tid` = '".$v."' " );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 3302, $title[$v], $content );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    public function api_getTypeList( )
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

    public function api_getLevelList( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "tid", "title" ), $this->t_level_list, "ORDER BY `order` ASC " );
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

    public function api_getHurryList( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "tid", "title" ), $this->t_hurry_list, "ORDER BY `order` ASC " );
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

    public function api_getSecretList( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "tid", "title", "order" ), $this->t_secret_list, "ORDER BY `order` ASC " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['title'] = $v['title'];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    public function api_getTypeAllArr( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "tid", "title" ), $this->t_type_list, "ORDER BY `order` ASC " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array(
            0 => array( "title" => "" )
        );
        foreach ( $dblist as $k => $v )
        {
            $data[$v['tid']] = $v;
        }
        return $data;
    }

    public function api_getLevelAllArr( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "tid", "title" ), $this->t_level_list, "ORDER BY `order` ASC " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array(
            0 => array( "title" => "" )
        );
        foreach ( $dblist as $k => $v )
        {
            $data[$v['tid']] = $v;
        }
        return $data;
    }

    public function api_getHurryAllArr( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "tid", "title" ), $this->t_hurry_list, "ORDER BY `order` ASC " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array(
            0 => array( "title" => "" )
        );
        foreach ( $dblist as $k => $v )
        {
            $data[$v['tid']] = $v;
        }
        return $data;
    }

    public function api_getSecretAllArr( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "tid", "title" ), $this->t_secret_list, "ORDER BY `order` ASC " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array(
            0 => array( "title" => "" )
        );
        foreach ( $dblist as $k => $v )
        {
            $data[$v['tid']] = $v;
        }
        return $data;
    }

}

?>
