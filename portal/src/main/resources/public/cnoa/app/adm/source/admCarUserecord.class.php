<?php
//decode by qq2859470

class admCarUserecord extends model
{

    private $table_info = "adm_car_info";
    private $table_check = "adm_car_check";
    private $table_apply = "adm_car_apply";
    private $f_status = array
    (
        1 => "审批不通过",
        2 => "待批",
        3 => "审批通过"
    );
    private $rows = 15;

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
        case "apply" :
            app::loadapp( "adm", "carCheck" )->api_apply( );
            break;
        case "export" :
            $this->_export( );
        }
    }

    private function _loadpage( )
    {
    }

    private function _getJsonData( )
    {
        global $CNOA_DB;
        $start = getpar( $_POST, "start", 0 );
        $carnumber = getpar( $_POST, "carnumber", "" );
        $y_stime = getpar( $_POST, "y_stime", 0 );
        $y_etime = getpar( $_POST, "y_etime", 0 );
        $stime = getpar( $_POST, "stime", 0 );
        $etime = getpar( $_POST, "etime", 0 );
        $status = getpar( $_POST, "status", 0 );
        $driver = getpar( $_POST, "driver", "" );
        $WHERE = "WHERE 1 ";
        if ( !empty( $carnumber ) )
        {
            $s_cidDB = $CNOA_DB->db_select( array( "id" ), $this->table_info, "WHERE `carnumber` LIKE '%".$carnumber."%' " );
            if ( !is_array( $s_cidDB ) )
            {
                $s_cidDB = array( );
            }
            $s_cidArr = array( 0 );
            foreach ( $s_cidDB as $k => $v )
            {
                $s_cidArr[] = $v['id'];
            }
            $WHERE .= "AND `cid` IN (".implode( ",", $s_cidArr ).") ";
        }
        if ( !empty( $y_stime ) && !empty( $y_etime ) )
        {
            if ( !empty( $y_stime ) || !empty( $y_etime ) )
            {
                $y_stime = strtotime( $y_stime." 00:00:00" );
                $y_etime = strtotime( $y_etime." 23:59:59" );
                if ( $y_etime < $y_stime )
                {
                    msg::callback( FALSE, lang( "StimeGreaterThanEtime" ) );
                }
                else
                {
                    $WHERE .= "AND `starttime` < ".$y_etime." AND `endtime` > {$y_stime} ";
                }
            }
            else
            {
                msg::callback( FALSE, lang( "fillStimeEtime" ) );
            }
        }
        if ( !empty( $stime ) && !empty( $etime ) )
        {
            if ( !empty( $stime ) || !empty( $etime ) )
            {
                $stime = strtotime( $stime." 00:00:00" );
                $etime = strtotime( $etime." 23:59:59" );
                if ( $etime < $stime )
                {
                    msg::callback( FALSE, lang( "StimeGreaterThanEtime" ) );
                }
                else
                {
                    $WHERE .= "AND `realstarttime` < ".$etime." AND `realendtime` > {$stime} ";
                }
            }
            else
            {
                msg::callback( FALSE, lang( "fillStimeEtime" ) );
            }
        }
        if ( !empty( $status ) )
        {
            if ( $status == 5 )
            {
                $WHERE .= "AND `status` = 2 ";
            }
            else if ( $status == 1 )
            {
                $WHERE .= "AND (`status` = 3 OR `status` = 5) ";
            }
            else if ( $status == 2 )
            {
                $WHERE .= "AND `status` = 1 ";
            }
            else if ( $status == 3 )
            {
                $WHERE .= "AND `type` = 1 ";
            }
            else if ( $status == 4 )
            {
                $WHERE .= "AND `type` = 2 ";
            }
        }
        if ( !empty( $driver ) )
        {
            $WHERE .= "AND `driver` LIKE '%".$driver."%' ";
        }
        $dblist = $CNOA_DB->db_select( "*", $this->table_apply, $WHERE.( "AND `status` != 0 ORDER BY `id` DESC LIMIT ".$start.", {$this->rows}" ) );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        if ( empty( $dblist ) )
        {
            ( );
            $ds = new dataStore( );
            $ds->data = array( );
            echo $ds->makeJsonData( );
            exit( );
        }
        $cidArr = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            $cidArr[] = $v['cid'];
        }
        $carDB = $CNOA_DB->db_select( array( "carnumber", "id" ), $this->table_info, "WHERE `id` IN (".implode( ",", $cidArr ).")" );
        if ( !is_array( $carDB ) )
        {
            $carDB = array( );
        }
        foreach ( $carDB as $k => $v )
        {
            $carArr[$v['id']] = $v['carnumber'];
        }
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['carnumber'] = $carArr[$v['cid']];
            $dblist[$k]['starttime'] = formatdate( $v['starttime'], "Y-m-d H:i" );
            $dblist[$k]['endtime'] = formatdate( $v['endtime'], "Y-m-d H:i" );
            $dblist[$k]['realstarttime'] = formatdate( $v['realstarttime'], "Y-m-d H:i" );
            $dblist[$k]['realendtime'] = formatdate( $v['realendtime'], "Y-m-d H:i" );
            $dblist[$k]['status'] = $this->f_status[$v['status']];
            if ( $v['type'] == 1 )
            {
                $dblist[$k]['status'] = lang( "beenIssued" );
            }
            else if ( $v['type'] == 2 )
            {
                $dblist[$k]['status'] = lang( "carBeenBack" );
            }
            if ( !empty( $status ) )
            {
                if ( $status == 5 )
                {
                    $dblist[$k]['status'] = lang( "Pending" );
                }
                else if ( $status == 1 )
                {
                    $dblist[$k]['status'] = lang( "approvalThrough" );
                }
                else if ( $status == 2 )
                {
                    $dblist[$k]['status'] = lang( "approvalNotThrough" );
                }
                else if ( $status == 3 )
                {
                    $dblist[$k]['status'] = lang( "beenIssued" );
                }
                else if ( $status == 4 )
                {
                    $dblist[$k]['status'] = lang( "carBeenBack" );
                }
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $dblist;
        $ds->total = $CNOA_DB->db_getcount( $this->table_apply, $WHERE."AND `status` != 0 " );
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _export( )
    {
        global $CNOA_DB;
        $step = getpar( $_GET, "step", "" );
        if ( $step == 1 )
        {
            $DB = $CNOA_DB->db_select( "*", $this->table_apply, "WHERE `status` != 0" );
            if ( $DB )
            {
                $carDB = $CNOA_DB->db_select( array( "id", "carnumber" ), $this->table_info );
                $car = array( );
                foreach ( $carDB as $v )
                {
                    $car[$v['id']] = $v['carnumber'];
                }
                foreach ( $DB as $k => $v )
                {
                    $DB[$k]['cid'] = $car[$v['cid']];
                }
                $info['车辆使用记录'] = $DB;
                include( CNOA_PATH."/core/inc/admCarUserecord.php" );
                ( );
                $excelClass = new exportExcel( );
                $info = $excelClass->formatExcelDate( $fieldName, $info );
                $fileName = "CNOA.ADM-".date( "Ymd", $GLOBALS['CNOA_TIMESTAMP'] )."-".string::rands( 10, 2 ).".xlsx";
                $excelClass->init( $info );
                $excelClass->save( CNOA_PATH_FILE."/common/temp/".$fileName, "excel2007" );
                msg::callback( TRUE, $fileName );
            }
            else
            {
                msg::callback( FALSE, lang( "noDataToExport" ) );
            }
        }
        else
        {
            $fileName = getpar( $_GET, "file", "" );
            $file = CNOA_PATH_FILE."/common/temp/".$fileName;
            if ( !file_exists( $file ) )
            {
                msg::callback( FALSE, lang( "fileNotExist" ) );
            }
            if ( $CNOA_DB )
            {
                $CNOA_DB->close( );
            }
            @ini_set( "zlib.output_compression", "Off" );
            header( "Content-type: application/octet-stream" );
            header( "Content-Disposition: attachment;filename=".cn_urlencode( "车辆使用.xlsx" ) );
            header( "Content-Length: ".filesize( $file ) );
            ob_clean( );
            flush( );
            readfile( $file );
            @unlink( $file );
        }
        exit( );
    }

}

?>
