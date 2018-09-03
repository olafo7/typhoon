本程序只用于学习研究之用途,可解密 Zend 加密的php文件
找源码,PHPJM,PHPDP等解密请联系QQ 2859470 想免费的不要加

zend5.3、zend5.4、其他混淆解密 联系QQ 2859470  
Zend加密文件(解密文件限制512k以内)：    新版

本程序仅供学习测试用途，以下为解密结果（如果为空可能解密失败）


<?php
//decode by qq2859470

class odocReceiveList extends model
{

    private $t_receive_list = "odoc_receive_list";
    private $t_read_file = "odoc_read_file";
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
        case "getTypeList" :
            app::loadapp( "odoc", "settingWord" )->api_getTypeList( );
            break;
        case "getLevelList" :
            app::loadapp( "odoc", "settingWord" )->api_getLevelList( );
            break;
        case "getHurryList" :
            app::loadapp( "odoc", "settingWord" )->api_getHurryList( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
            $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
            echo json_encode( $userList );
            exit( );
        case "sendFile" :
            $this->_sendFile( );
            break;
        case "loadFormData" :
            $this->_loadFormData( );
            break;
        case "getReaderList" :
            $this->_getReaderList( );
            break;
        case "view" :
            app::loadapp( "odoc", "commonView" )->run( "receive" );
        }
    }

    private function _loadpage( )
    {
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $WHERE = "WHERE 1 ";
        $start = getpar( $_POST, "start", 0 );
        $s_title = getpar( $_POST, "title", "" );
        $s_number = getpar( $_POST, "number", "" );
        $s_type = getpar( $_POST, "type", 0 );
        $s_level = getpar( $_POST, "level", 0 );
        $s_hurry = getpar( $_POST, "hurry", 0 );
        $storeType = getpar( $_POST, "storeType", "all" );
        if ( !( $storeType == "all" ) || $storeType == "history" )
        {
            $this->__getJsonDataHistory( );
        }
        if ( !empty( $s_title ) )
        {
            $WHERE .= "AND `title` LIKE '%".$s_title."%' ";
        }
        if ( !empty( $s_number ) )
        {
            $WHERE .= "AND `number` LIKE '%".$s_number."%' ";
        }
        if ( !empty( $s_type ) )
        {
            $WHERE .= "AND `type` = '".$s_type."' ";
        }
        if ( !empty( $s_level ) )
        {
            $WHERE .= "AND `level` = '".$s_level."' ";
        }
        if ( !empty( $s_hurry ) )
        {
            $WHERE .= "AND `hurry` = '".$s_hurry."' ";
        }
        $typeArr = app::loadapp( "odoc", "settingWord" )->api_getTypeAllArr( );
        $levelArr = app::loadapp( "odoc", "settingWord" )->api_getLevelAllArr( );
        $hurryArr = app::loadapp( "odoc", "settingWord" )->api_getHurryAllArr( );
        $secretArr = app::loadapp( "odoc", "settingWord" )->api_getSecretAllArr( );
        $dblist = $CNOA_DB->db_select( "*", $this->t_receive_list, $WHERE.( "AND `status` = '2' ORDER BY `id` DESC LIMIT ".$start.", {$this->rows}" ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $uidArr = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $uidArr[] = $v['createuid'];
        }
        $truenameArr = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['type'] = $typeArr[$v['type']]['title'];
            $dblist[$k]['level'] = $levelArr[$v['level']]['title'];
            $dblist[$k]['hurry'] = $hurryArr[$v['hurry']]['title'];
            $dblist[$k]['secret'] = $secretArr[$v['secret']]['title'];
            $dblist[$k]['createname'] = $truenameArr[$v['createuid']]['truename'];
            $dblist[$k]['createtime'] = formatdate( $v['createtime'], "Y-m-d H:i" );
            $dblist[$k]['receivedate'] = formatdate( $v['receivedate'] );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        $dataStore->total = $CNOA_DB->db_getcount( $this->t_receive_list, $WHERE."AND `status` = '2' " );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function __getJsonDataHistory( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $WHERE = "WHERE 1 ";
        $start = getpar( $_POST, "start", 0 );
        $search = $this->__searchForJson( );
        $WHERE .= "AND `fileid` IN (".implode( ",", $search ).") ";
        $uid = $CNOA_SESSION->get( "UID" );
        $dblist = $CNOA_DB->db_select( "*", $this->t_read_file, $WHERE.( "AND `type` = '2' AND `senduid` = '".$uid."' GROUP BY `fileid` LIMIT {$start}, {$this->rows}" ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $idArr = array( 0 );
        $uidArr = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $idArr[] = $v['fileid'];
        }
        $receiveDB = $CNOA_DB->db_select( "*", $this->t_receive_list, "WHERE `id` IN (".implode( ",", $idArr ).")" );
        if ( !is_array( $receiveDB ) )
        {
            $receiveDB = array( );
        }
        foreach ( $receiveDB as $k => $v )
        {
            $receiveArr[$v['id']] = $v;
            $uidArr[] = $v['createuid'];
        }
        $truenameArr = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr );
        $typeArr = app::loadapp( "odoc", "settingWord" )->api_getTypeAllArr( );
        $levelArr = app::loadapp( "odoc", "settingWord" )->api_getLevelAllArr( );
        $hurryArr = app::loadapp( "odoc", "settingWord" )->api_getHurryAllArr( );
        $secretArr = app::loadapp( "odoc", "settingWord" )->api_getSecretAllArr( );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['title'] = $receiveArr[$v['fileid']]['title'];
            $dblist[$k]['number'] = $receiveArr[$v['fileid']]['number'];
            $dblist[$k]['many'] = $receiveArr[$v['fileid']]['many'];
            $dblist[$k]['fromdept'] = $receiveArr[$v['fileid']]['fromdept'];
            $dblist[$k]['createname'] = $truenameArr[$receiveArr[$v['fileid']]['createuid']]['truename'];
            $dblist[$k]['receivedate'] = formatdate( $receiveArr[$v['fileid']]['receivedate'] );
            $dblist[$k]['type'] = $typeArr[$receiveArr[$v['fileid']]['type']]['title'];
            $dblist[$k]['level'] = $levelArr[$receiveArr[$v['fileid']]['level']]['title'];
            $dblist[$k]['hurry'] = $hurryArr[$receiveArr[$v['fileid']]['hurry']]['title'];
            $dblist[$k]['secret'] = $secretArr[$receiveArr[$v['fileid']]['secret']]['title'];
            $dblist[$k]['id'] = $v['fileid'];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        $dataStore->total = $CNOA_DB->db_getcount( $this->t_read_file, $WHERE.( "AND `type` = '3' AND `senduid` = '".$uid."' GROUP BY `fileid` " ) );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function __searchForJson( )
    {
        global $CNOA_DB;
        $s_title = getpar( $_POST, "title", "" );
        $s_number = getpar( $_POST, "number", "" );
        $s_type = getpar( $_POST, "type", 0 );
        $s_level = getpar( $_POST, "level", 0 );
        $s_hurry = getpar( $_POST, "hurry", 0 );
        $WHERE = "WHERE 1 ";
        if ( !empty( $s_title ) )
        {
            $WHERE .= "AND `title` LIKE '%".$s_title."%' ";
        }
        if ( !empty( $s_number ) )
        {
            $WHERE .= "AND `number` LIKE '%".$s_number."%' ";
        }
        if ( !empty( $s_type ) )
        {
            $WHERE .= "AND `type` = '".$s_type."' ";
        }
        if ( !empty( $s_level ) )
        {
            $WHERE .= "AND `level` = '".$s_level."' ";
        }
        if ( !empty( $s_hurry ) )
        {
            $WHERE .= "AND `hurry` = '".$s_hurry."' ";
        }
        $dblist = $CNOA_DB->db_select( array( "id" ), $this->t_receive_list, $WHERE );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $data[] = $v['id'];
        }
        return $data;
    }

    private function _sendFile( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $id = getpar( $_POST, "id", 0 );
        $uids = getpar( $_POST, "splituid", 0 );
        $uidArr = explode( ",", $uids );
        $stime = strtotime( getpar( $_POST, "stime", "" )." 00:00" );
        $etime = getpar( $_POST, "etime", "" );
        if ( !empty( $etime ) )
        {
            $etime = strtotime( getpar( $_POST, "etime", "" )." 23:59" );
        }
        $data['stime'] = $stime;
        $data['etime'] = $etime;
        $data['senduid'] = $uid;
        $data['sendtime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $data['type'] = 2;
        $data['fileid'] = $id;
        $filterArr = $this->__filterData( $id, $uidArr );
        $info = $CNOA_DB->db_getone( array( "title", "number" ), $this->t_receive_list, "WHERE `id` = '".$id."' " );
        foreach ( $filterArr as $v2 )
        {
            $data['receiveuid'] = $v2;
            $noticeT = "提醒：收文分发";
            $noticeC = "文号:".$info['number'].lang( "title" )."[".$info['title']."]分发给您";
            $noticeH = "index.php?app=odoc&func=read&action=receive";
            notice::add( $v2, $noticeT, $noticeC, $noticeH, 0, 23, $CNOA_SESSION->get( "UID" ) );
            $CNOA_DB->db_insert( $data, $this->t_read_file );
        }
        $man = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr );
        $argMan = "";
        foreach ( $man as $v )
        {
            $argMan .= "{$v['truename']}, ";
        }
        $argMan = substr( $argMan, 0, -2 );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "", 3104, $argMan, "分发收文（".$info['title']."）" );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function __filterData( $fileid, $uidArr )
    {
        global $CNOA_DB;
        $CNOA_DB->db_delete( $this->t_read_file, "WHERE `fileid` = '".$fileid."' AND `type` = '2' AND `receiveuid` NOT IN (".implode( ",", $uidArr ).") " );
        $dblist = $CNOA_DB->db_select( array( "receiveuid" ), $this->t_read_file, "WHERE `fileid` = '".$fileid."' AND `type` = '2'" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( );
        foreach ( $dblist as $k => $v )
        {
            $data[] = $v['receiveuid'];
        }
        $data = array_diff( $uidArr, $data );
        return $data;
    }

    private function _loadFormData( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", 0 );
        $dblist = $CNOA_DB->db_select( "*", $this->t_read_file, "WHERE `fileid` = '".$id."' AND `type` = '2' " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $uidArr = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $uidArr[] = $v['receiveuid'];
        }
        $truenameArr = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr );
        $data = array( );
        $data['stime'] = formatdate( $dblist[0]['stime'] );
        $data['etime'] = formatdate( $dblist[0]['etime'] );
        foreach ( $dblist as $k => $v )
        {
            $data['splituid'] .= $v['receiveuid'].",";
            $data['split'] .= $truenameArr[$v['receiveuid']]['truename'].", ";
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getReaderList( )
    {
        global $CNOA_DB;
        $id = getpar( $_GET, "id", 0 );
        $dblist = $CNOA_DB->db_select( array( "readed", "receiveuid", "readtime" ), $this->t_read_file, "WHERE `fileid` = '".$id."' AND `type` = '2' ORDER BY `readed` DESC" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $uidArr = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $uidArr[] = $v['receiveuid'];
        }
        $truenameArr = app::loadapp( "main", "user" )->api_getUserNamesByUids( $uidArr );
        if ( !is_array( $truenameArr ) )
        {
            $truenameArr = array( );
        }
        $deptIdArr = array( 0 );
        foreach ( $truenameArr as $k => $v )
        {
            $deptIdArr[] = $v['deptId'];
        }
        $deptNameArr = app::loadapp( "main", "struct" )->api_getNamesByIds( $deptIdArr );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['deptname'] = $deptNameArr[$truenameArr[$v['receiveuid']]['deptId']];
            if ( $v['readed'] == 1 )
            {
                $dblist[$k]['name'] = "<span class='cnoa_color_red'>".$truenameArr[$v['receiveuid']]['truename']."</span>";
            }
            else
            {
                $dblist[$k]['name'] = $truenameArr[$v['receiveuid']]['truename'];
            }
            $dblist[$k]['readtime'] = formatdate( $v['readtime'], "Y-m-d H:i" );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    public function api_getReceiveData( $idArr )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->t_receive_list, "WHERE `id` IN (".implode( ",", $idArr ).") " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $data = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $data[$v['id']] = $v;
        }
        return $data;
    }

}

?>

已成功解密 72719 个加密文件,累计解密数据 610.46 M.