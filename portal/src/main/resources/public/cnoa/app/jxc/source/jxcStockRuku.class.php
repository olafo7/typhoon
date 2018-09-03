<?php
//decode by qq2859470

class jxcStockRuku extends jxcStock
{

    protected $modelId = self::MODULE_RUKU;

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            exit( );
        case "getRukuCustomField" :
            $this->getCustomField( self::MODULE_RUKU );
            exit( );
        case "getRukuList" :
            $this->_getRukuList( );
            exit( );
        case "getComboStore" :
            $this->getComboStore( $this );
        }
        exit( );
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/stock/ruku.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    private function _getRukuList( )
    {
        global $CNOA_DB;
        $start = getpar( $_POST, "start", 0 );
        $limit = getpar( $_POST, "limit", 15 );
        ( self::CUSTOM_FIELD_CODE );
        $cf = new customField( );
        $cf->setAllComboItem( self::MODULE_RUKU );
        $cf->setCustomFieldType( self::MODULE_RUKU );
        $where = array( );
        $type = intval( getpar( $_POST, "type" ) );
        if ( !empty( $type ) )
        {
            $where[] = "r.type=".$type;
        }
        $storageid = intval( getpar( $_POST, "storageid" ) );
        if ( !empty( $storageid ) )
        {
            $where[] = "r.storageid=".$storageid;
        }
        $stime = getpar( $_POST, "posttime_start" );
        $etime = getpar( $_POST, "posttime_end" );
        if ( !empty( $stime ) )
        {
            $stime = strtotime( "{$stime} 00:00:00" );
            $where[] = "r.posttime > ".$stime;
        }
        if ( !empty( $etime ) )
        {
            $etime = strtotime( "{$etime} 23:59:59" );
            $where[] = "r.posttime < ".$etime;
        }
        $indentnumber = intval( getpar( $_POST, "indentnumber" ) );
        if ( !empty( $indentnumber ) )
        {
            $where[] = "r.indentnumber LIKE '%".$indentnumber."%'";
        }
        $cusWhere = $cf->getSearchWhere( self::MODULE_RUKU, "r" );
        if ( !empty( $cusWhere ) )
        {
            $where = array_merge( $where, $cusWhere );
        }
        $where = empty( $where ) ? "1" : implode( " AND ", $where );
        $sql = "SELECT `r`.*, `s`.`storagename` FROM ".tname( $this->table_ruku )." AS `r` LEFT JOIN ".tname( $this->table_storage )." AS `s` ON `s`.`id`=`r`.`storageid` ".( "WHERE ".$where." ORDER BY `id` DESC LIMIT {$start}, {$limit}" );
        $result = $CNOA_DB->query( $sql );
        $data = array( );
        while ( $row = $CNOA_DB->get_array( $result ) )
        {
            $row = $cf->transformCustomFieldData( $row, array( "rukuTypeName" => "type" ) );
            $row['posttime'] = formatdate( $row['posttime'], "Y-m-d" );
            $data[] = $row;
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        $ds->total = $CNOA_DB->db_getcount( $this->table_ruku, "WHERE ".str_replace( "r.", "", $where ) );
        echo $ds->makeJsonData( );
    }

}

?>
