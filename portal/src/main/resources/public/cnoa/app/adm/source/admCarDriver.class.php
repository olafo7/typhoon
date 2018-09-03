<?php
//decode by qq2859470

class admCarDriver extends model
{

    private $table_info = "adm_car_info";
    private $table_driver = "adm_car_driver";
    private $f_sex = array
    (
        1 => "男",
        2 => "女"
    );
    private $f_tall = array
    (
        1 => "185CM以上",
        2 => "175-180CM",
        3 => "170-175CM",
        4 => "165-170CM",
        5 => "160-165CM",
        6 => "155-160CM",
        7 => "150-155CM",
        8 => "150CM以下"
    );
    private $f_experience = array
    (
        1 => "1年经验",
        2 => "2年经验",
        3 => "3年经验",
        4 => "3年以上经验"
    );
    private $f_rank = array
    (
        1 => "A级",
        2 => "B级",
        3 => "C级"
    );
    private $rows = 15;

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        if ( !( $task == "loadPage" ) )
        {
            if ( $task == "getJsonList" )
            {
                $this->_getJsonList( );
            }
            else if ( $task == "edit" )
            {
                $this->_edit( );
            }
            else if ( $task == "loadFormData" )
            {
                $this->_loadFormData( );
            }
            else if ( $task == "delete" )
            {
                $this->_delete( );
            }
            else if ( $task == "add" )
            {
                $this->_add( );
            }
            else if ( $task == "getcarnumber" )
            {
                $this->_getCarnumber( );
            }
            else if ( $task == "driverInfo" )
            {
                global $CNOA_DB;
                global $CNOA_CONTROLLER;
                $did = getpar( $_GET, "did", "" );
                $sql = "SELECT `d`.*, `i`.`carnumber` FROM ".tname( $this->table_driver )." AS `d` LEFT JOIN ".tname( $this->table_info )." AS `i` ON `i`.`id`=`d`.`cid` ".( "WHERE `did`=".$did );
                $result = $CNOA_DB->query( $sql );
                $data = $CNOA_DB->get_array( $result );
                $data['sex'] = $this->f_sex[$data['sex']];
                $data['rank'] = $this->f_rank[$data['rank']];
                $data['tall'] = $this->f_tall[$data['tall']];
                $data['experience'] = $this->f_experience[$data['experience']];
                $GLOBALS['GLOBALS']['adm']['car']['driver'] = $data;
                $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/car/driverView.htm";
                $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
                exit( );
            }
        }
    }

    private function _loadPage( )
    {
    }

    private function _getJsonList( )
    {
        global $CNOA_DB;
        $start = getpar( $_POST, "start", 0 );
        $searchKey['name'] = getpar( $_POST, "name", "" );
        $searchKey['cid'] = getpar( $_POST, "cid", "" );
        $carnumber = getpar( $_POST, "carnumber", "" );
        $WHERE[] = "WHERE 1";
        if ( !empty( $searchKey['name'] ) )
        {
        }
        if ( !empty( $searchKey['cid'] ) )
        {
        }
        if ( $carnumber )
        {
        }
        $WHERE[$WHERE = implode( " AND ", $WHERE );
        $sql = "SELECT `d`.*, `i`.`carnumber` FROM ".tname( $this->table_driver )." AS `d` LEFT JOIN ".tname( $this->table_info )." AS `i` ON `i`.`id`=`d`.`cid` "."{$WHERE} ORDER BY `did` DESC LIMIT {$start}, {$this->rows}";
        $result = $CNOA_DB->query( $sql );
        $data = array( );
        while ( $row = $CNOA_DB->get_array( $result ) )
        {
            $data[] = array(
                "did" => $row['did'],
                "carnumber" => $row['carnumber'],
                "name" => $row['name'],
                "sex" => $this->f_sex[$row['sex']],
                "age" => $row['age'],
                "mobile" => $row['mobile'],
                "cid" => $row['cid'],
                "tall" => $this->f_tall[$row['tall']],
                "experience" => $this->f_experience[$row['experience']],
                "rank" => $this->f_rank[$row['rank']],
                "address" => $row['address']
            );
        }
        $CNOA_DB->free_result( $result );
        $WHERE = str_replace( "i.", "", $WHERE );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        $dataStore->total = $CNOA_DB->db_getcount( $this->table_driver, $WHERE );
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _add( )
    {
        global $CNOA_DB;
        $data = array( );
        $data['name'] = getpar( $_POST, "name", "" );
        $data['cid'] = getpar( $_POST, "cid", "" );
        $data['sex'] = getpar( $_POST, "sex", "" );
        $data['tall'] = getpar( $_POST, "tall", "" );
        $data['age'] = getpar( $_POST, "age", "" );
        $data['experience'] = getpar( $_POST, "experience", "" );
        $data['mobile'] = getpar( $_POST, "mobile", "" );
        $data['rank'] = getpar( $_POST, "rank", "" );
        $data['address'] = getpar( $_POST, "address", "" );
        $CNOA_DB->db_insert( $data, $this->table_driver );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 148, $data['name'], lang( "driverInformation" ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadFormData( )
    {
        global $CNOA_DB;
        $did = getpar( $_POST, "did", "" );
        $dblist = $CNOA_DB->db_getone( "*", $this->table_driver, "WHERE `did` = '".$did."' " );
        $dblist['carnumber'] = $CNOA_DB->db_getfield( "carnumber", $this->table_info, "WHERE `id`='".$dblist['cid']."'" );
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _edit( )
    {
        global $CNOA_DB;
        $data = array( );
        $data['did'] = getpar( $_POST, "did", "" );
        $data['name'] = getpar( $_POST, "name", "" );
        $data['cid'] = getpar( $_POST, "cid", "" );
        $data['sex'] = getpar( $_POST, "sex", "" );
        $data['tall'] = getpar( $_POST, "tall", "" );
        $data['age'] = getpar( $_POST, "age", "" );
        $data['experience'] = getpar( $_POST, "experience", "" );
        $data['mobile'] = getpar( $_POST, "mobile", "" );
        $data['rank'] = getpar( $_POST, "rank", "" );
        $data['address'] = getpar( $_POST, "address", "" );
        $CNOA_DB->db_update( $data, $this->table_driver, "WHERE `did`= ".$data['did']." " );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 148, $data['name'], lang( "driverInformation" ) );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _delete( )
    {
        global $CNOA_DB;
        $dids = getpar( $_POST, "dids", 0 );
        $dids = substr( $dids, 0, -1 );
        $didArr = explode( ",", $dids );
        foreach ( $didArr as $v )
        {
            $name = $CNOA_DB->db_getfield( "name", $this->table_driver, "WHERE `did`='".$v."'" );
            $CNOA_DB->db_delete( $this->table_driver, "WHERE `did`='".$v."'" );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "del", 148, $name, lang( "driverInformation" ) );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getCarnumber( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( array( "carnumber", "id" ), $this->table_info );
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['cid'] = $v['id'];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $dblist;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    public function api_getDriverNameById( $did )
    {
        global $CNOA_DB;
        $name = $CNOA_DB->db_getfield( "name", $this->table_driver, "WHERE `did`='".$did."'" );
        if ( !$name )
        {
            return "";
        }
        return $name;
    }

}

?>
