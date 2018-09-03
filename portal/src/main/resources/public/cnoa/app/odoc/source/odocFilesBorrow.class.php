<?php
//decode by qq2859470

class odocFilesBorrow extends model
{

    private $t_files_dangan = "odoc_files_dangan";
    private $t_files_borrow = "odoc_files_borrow";
    private $archives_type = array
    (
        1 => "发文",
        2 => "收文",
        3 => "其他"
    );
    private $rows = 15;

    public function run( )
    {
        $task = getpar( $_GET, "task", "loadpage" );
        switch ( $task )
        {
        case "loadpage" :
            $this->_loadpage( );
            exit( );
        case "getJsonData" :
            $this->_getJsonData( );
            exit( );
        case "viewFile" :
            $this->_viewFile( );
            exit( );
        case "returnFile" :
            $this->_returnFile( );
        }
        exit( );
    }

    private function _loadpage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/files/borrow.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $titleValue = getpar( $_POST, "titleValue" );
        $where = "WHERE b.postuid=".$uid." ";
        $start = getpar( $_POST, "start", 0 );
        $row = 15;
        if ( !empty( $titleValue ) )
        {
            $where .= "AND d.title LIKE '%".$titleValue."%' ";
        }
        $storeType = getpar( $_POST, "storeType" );
        switch ( $storeType )
        {
        case "waiting" :
            $where .= "AND b.status=0 ";
            break;
        case "pass" :
            $where .= "AND b.status=1 ";
            break;
        case "unpass" :
            $where .= "AND b.status=2 ";
            break;
        case "return" :
            $where .= "AND b.returned=1 ";
            break;
        case "unreturn" :
            $where .= "AND b.returned=0 ";
        }
        $sql = "SELECT b.*, d.title, u.truename AS postname, s.name AS postdept FROM ".tname( $this->t_files_borrow )." AS b LEFT JOIN ".tname( $this->t_files_dangan )." AS d ON d.id=b.fileid LEFT JOIN ".tname( "main_user" )." AS u ON u.uid=b.postuid LEFT JOIN ".tname( "main_struct" )." AS s ON s.id=u.deptId "."{$where}  ORDER BY `id` DESC LIMIT {$start},{$row}";
        $result = $CNOA_DB->query( $sql );
        $data = array( );
        while ( $row = $CNOA_DB->get_array( $result ) )
        {
            $row['from'] = $this->archives_type[$row['from']];
            $row['posttime'] = formatdate( $row['posttime'], "Y-m-d H:i" );
            $row['stime'] = formatdate( $row['stime'] );
            $row['etime'] = formatdate( $row['etime'] );
            $row['approvetime'] = formatdate( $row['approvetime'], "Y-m-d H:i" );
            $data[] = $row;
        }
        $sql = "SELECT count(*) AS count FROM ".tname( $this->t_files_borrow )." AS b LEFT JOIN ".tname( $this->t_files_dangan )." AS d ON d.id=b.fileid "."{$where}";
        $result = $CNOA_DB->query( $sql );
        while ( $row = $CNOA_DB->get_array( $result ) )
        {
            $total = $row['count'];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        $dataStore->total = $total;
        echo $dataStore->makeJsonData( );
    }

    private function _viewFile( )
    {
        app::loadapp( "odoc", "commonFilesView" )->run( );
        exit( );
    }

    private function _returnFile( )
    {
        global $CNOA_DB;
        $fileId = intval( getpar( $_POST, "fileId", 0 ) );
        if ( $fileId == 0 )
        {
            msg::callback( FALSE, "閿欒?鍙傛暟" );
        }
        $CNOA_DB->db_update( array( "returned" => 1 ), $this->t_files_borrow, "WHERE `fileId`='".$fileId."'" );
        msg::callback( TRUE, "鎿嶄綔鎴愬姛" );
    }

}

?>
