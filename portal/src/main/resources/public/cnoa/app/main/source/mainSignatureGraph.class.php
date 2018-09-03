<?php
//decode by qq2859470

class mainSignatureGraph extends model
{

    private $table = "system_signature";

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        switch ( $task )
        {
        case "getJsonDatas" :
            $this->_getJsonDatas( );
            break;
        case "getAllUserListsInPermitDeptTree" :
            $this->_getAllUserListsInPermitDeptTree( );
            break;
        case "addEditSignature" :
            $this->_addEditSignature( );
            break;
        case "editLoadFormDataInfo" :
            $this->_editLoadFormDataInfo( );
            break;
        case "delSignature" :
            $this->_delSignature( );
        }
    }

    private function _getJsonDatas( )
    {
        global $CNOA_DB;
        $where = array( );
        $uid = ( integer )getpar( $_POST, "uid" );
        if ( !empty( $uid ) )
        {
            $where[] = "s.uid=".$uid;
        }
        $sname = getpar( $_POST, "sname" );
        if ( !empty( $sname ) )
        {
            $where[] = "s.signature LIKE '%".$sname."%'";
        }
        $where = 0 < count( $where ) ? "WHERE ".implode( " AND ", $where ) : "";
        $start = getpar( $_POST, "start", 0 );
        $limit = getpagesize( "main_signature_index_graph_getJsonDatas" );
        $data = array( );
        $sql = "SELECT s.*, u.truename AS username FROM ".tname( $this->table )." AS s LEFT JOIN ".tname( "main_user" )." AS u ON u.uid=s.uid "."{$where} ORDER BY s.id DESC LIMIT {$start}, {$limit}";
        $result = $CNOA_DB->query( $sql );
        while ( $row = $CNOA_DB->get_array( $result ) )
        {
            $data[] = $row;
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        $dataStore->total = $CNOA_DB->db_getcount( $this->table, str_replace( "s.", "", $where ) );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _getAllUserListsInPermitDeptTree( )
    {
        $GLOBALS['GLOBALS']['user']['permitArea']['area'] = "all";
        $userList = app::loadapp( "main", "user" )->api_getAllUserListsInPermitDeptTree( );
        echo json_encode( $userList );
        exit( );
    }

    private function _editLoadFormDataInfo( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", "" );
        if ( !empty( $id ) )
        {
            $info = $CNOA_DB->db_getone( array( "uid", "signature", "isUsePwd" ), $this->table, "WHERE `id`='".$id."'" );
            $info['name'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $info['uid'] );
            ( );
            $dataStore = new dataStore( );
            $dataStore->data = $info;
            echo $dataStore->makeJsonData( );
        }
        exit( );
    }

    private function _addEditSignature( )
    {
        global $CNOA_DB;
        if ( !empty( $_FILES['image']['name'] ) )
        {
            $this->checkExt( );
        }
        $id = getpar( $_POST, "id", "" );
        $data['signature'] = getpar( $_POST, "signature", "" );
        $data['uid'] = getpar( $_POST, "uid", "" );
        $data['isUsePwd'] = ( integer )getpar( $_POST, "isUsePwd" );
        if ( !empty( $data ) )
        {
            $where = "WHERE `uid`=".$data['uid']." AND `signature`='{$data['signature']}' ";
            if ( !empty( $id ) )
            {
                $where .= "AND `id` != ".$id;
            }
            $is_exists = $CNOA_DB->db_getfield( "id", $this->table, $where );
            if ( $is_exists )
            {
                msg::callback( FALSE, lang( "signatureNameExist" ) );
            }
            if ( !empty( $_FILES['image']['name'] ) )
            {
                $data['url'] = $this->uploadSignature( $id );
            }
            if ( empty( $id ) )
            {
                $CNOA_DB->db_insert( $data, $this->table );
            }
            else
            {
                $CNOA_DB->db_update( $data, $this->table, "WHERE `id`=".$id );
            }
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _delSignature( )
    {
        global $CNOA_DB;
        $ids = getpar( $_POST, "ids", "" );
        if ( !empty( $ids ) )
        {
            $url = $CNOA_DB->db_select( array( "url" ), $this->table, "WHERE `id` IN (".$ids.")" );
            if ( !is_array( $url ) )
            {
                $url = array( );
            }
            foreach ( $url as $v )
            {
                @unlink( CNOA_PATH."/".$v['url'] );
            }
            $CNOA_DB->db_delete( $this->table, "WHERE `id` IN (".$ids.")" );
            msg::callback( TRUE, lang( "delSuccess" ) );
        }
        else
        {
            msg::callback( FALSE, lang( "delFail" ) );
        }
    }

    private function checkExt( )
    {
        $img_ext = strtolower( strrchr( $_FILES['image']['name'], "." ) );
        $extArray = array( ".gif", ".png" );
        if ( !in_array( $img_ext, $extArray ) )
        {
            msg::callback( FALSE, lang( "signatureImageFormat" ) );
        }
    }

    private function uploadSignature( $id )
    {
        global $CNOA_DB;
        set_time_limit( 0 );
        $img_ext = strtolower( strrchr( $_FILES['image']['name'], "." ) );
        if ( $img_ext != ".png" )
        {
            msg::callback( FALSE, lang( "imagedMustPNGformat" ) );
        }
        if ( empty( $id ) )
        {
            $img_name = $GLOBALS['CNOA_TIMESTAMP']."_".md5( $GLOBALS['CNOA_TIMESTAMP'] ).$img_ext;
            $img_dst = CNOA_PATH_FILE."/common/signature/graph/".$img_name;
            $img_url = $GLOBALS['CNOA_BASE_URL_FILE']."/common/signature/graph/".$img_name;
        }
        else
        {
            $url = $CNOA_DB->db_getfield( "url", $this->table, "WHERE `id`=".$id );
            $img_dst = CNOA_PATH."/".$url;
            @unlink( $img_dst );
            $img_url = $url;
        }
        if ( @cnoa_move_uploaded_file( $_FILES['image']['tmp_name'], $img_dst ) )
        {
            return $img_url;
        }
        msg::callback( FALSE, lang( "uploadFail" ) );
    }

}

?>
