<?php
//decode by qq2859470

class userAttendanceAgent extends model
{

    private $table_checktime = "user_attendance_checktime";
    private $rows = 15;

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        switch ( $task )
        {
        case "upload_excel" :
            $this->_upload_excel( );
            break;
        case "getList" :
            $this->_getList( );
            break;
        case "editChecktimeExplain" :
            $this->_editChecktimeExplain( );
            break;
        case "getUsersTree" :
            $this->_getUserTree( );
        }
    }

    private function _getUserTree( )
    {
        app::loadapp( "hr", "attendanceRecord" )->api_getUserTree( );
    }

    public function _getList( )
    {
        echo $_SESSION['_agent_data'];
        exit( );
    }

    public function __searchUid( $needle, $hay )
    {
        foreach ( $hay as $key => $value )
        {
            if ( !( $value['key'] == $needle ) )
            {
                continue;
            }
            return $key;
        }
    }

    private function _insertExcelTo( $data )
    {
        global $CNOA_DB;
        $userList = app::loadapp( "main", "user" )->api_getUserData( "WHERE `importKey`<>''" );
        if ( !is_array( $userList ) )
        {
            $userList = array( );
        }
        if ( count( $userList ) <= 0 )
        {
            msg::callback( FALSE, "请您先给员工设置“导入识别符”，用于识别员工身份." );
            exit( );
        }
        $keys = array( );
        $import = array( );
        foreach ( $userList as $userInfo )
        {
            $uid = $userInfo['uid'];
            $imp =& $import[$uid];
            $key = $userInfo['importKey'];
            $oKey = json_decode( $key );
            $rKey = $oKey->radio;
            $uKey = $oKey->text;
            if ( $rKey == 2 )
            {
                $uKey = $userInfo['idcard'];
            }
            $imp['key'] = $uKey;
            $imp['wtId'] = $userInfo['worktimeId'];
            $imp['truename'] = $userInfo['truename'];
            $keys[] = $uKey;
        }
        $repeat = 0;
        $output = array( );
        $i = 2;
        for ( ; $i <= count( $data ); ++$i )
        {
            $key = $data[$i][0];
            $time = $data[$i][1];
            $time = gmdate( "Y-m-d H:i:s", PHPExcel_Shared_Date::exceltophp( $time ) );
            $time = strtotime( $time );
            $uid = $this->__searchUid( $key, $import );
            if ( 0 < $uid )
            {
                $wtid = $import[$uid]['wtId'];
                $ctInfo = app::loadapp( "hr", "attendance" )->api_getChecktimeInfo( );
                $onbeforetime = $ctInfo['onbeforetime'];
                $onaftertime = $ctInfo['onaftertime'];
                $offbeforetime = $ctInfo['offbeforetime'];
                $offaftertime = $ctInfo['offaftertime'];
                $cnum = 0;
                $wtInfo = app::loadapp( "hr", "attendance" )->api_getWorktimeInfo( $wtid );
                $wtDatas = $wtInfo['data'];
                $wtDatas = json_decode( $wtDatas );
                $lastTime = 0;
                foreach ( $wtDatas as $wtData )
                {
                    $wtTime = $wtData->time;
                    if ( !( $wtTime == NULL ) )
                    {
                        ++$cnum;
                        $wtTime = strtotime( date( "Y-m-d", $time )." ".$wtTime );
                        $wtType = $wtData->type;
                        switch ( $wtType )
                        {
                        case 0 :
                            $beforetime = strtotime( "-".$onbeforetime." minutes", $wtTime );
                            $aftertime = strtotime( "+".$onaftertime." minutes", $wtTime );
                            break;
                        case 1 :
                            $beforetime = strtotime( "-".$offbeforetime." minutes", $wtTime );
                            $aftertime = strtotime( "+".$offaftertime." minutes", $wtTime );
                        }
                        if ( $beforetime <= $time && $time <= $aftertime )
                        {
                            break;
                        }
                        $lastTime = $wtTime;
                    }
                }
                if ( !( $time < $lastTime ) )
                {
                    $ins = array( );
                    $ins['uid'] = $uid;
                    $ins['cid'] = $wtid;
                    $ins['cnum'] = $cnum;
                    $ins['date'] = date( "Y-m-d", $time );
                    $ins['numDate'] = date( "Ymd", $time );
                    $ins['time'] = date( "H:i:s", $time );
                    $ins['ip'] = getip( );
                    $count = $CNOA_DB->db_getcount( $this->table_checktime, "WHERE `uid`=".$uid." AND `cid`={$wtid} AND `cnum`={$cnum} AND `date`='{$ins['date']}' AND `time`='{$ins['time']}'" );
                    if ( intval( $count ) <= 0 )
                    {
                        $CNOA_DB->db_insert( $ins, $this->table_checktime );
                        $outp = array( );
                        $outp['key'] = $key;
                        $outp['truename'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $uid );
                        $outp['time'] = date( "Y-m-d H:i:s", $time );
                        $output[] = $outp;
                    }
                    else
                    {
                        ++$repeat;
                    }
                }
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $output;
        $ds->total = count( $output );
        $js = $ds->makeJsonData( );
        $_SESSION['_agent_data'] = $js;
        msg::callback( TRUE, lang( "importSucess" ).count( $output )."条考勤数据(去除了重复的数据有".$repeat."条)." );
    }

    private function _importExcel( $excel_file )
    {
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        global $CNOA_XXTEA;
        $GLOBALS['GLOBALS']['data'] = array( );
        if ( !file_exists( $excel_file ) )
        {
            echo "文件不存在[0x0030010]";
            exit( );
        }
        include( CNOA_PATH_CLASS."/PHPExcel/IOFactory.php" );
        try
        {
            $objReader = PHPExcel_IOFactory::createreader( "Excel5" );
            try
            {
                $objPHPExcel = $objReader->load( $excel_file );
            }
            catch ( Exception $e1 )
            {
                $objReader = PHPExcel_IOFactory::createreader( "excel2007" );
                try
                {
                    $objPHPExcel = $objReader->load( $excel_file );
                }
                catch ( Exception $e1 )
                {
                    echo "不能正常解析文件，请确认是否是正常的Excel文件[0x0030023]";
                    exit( );
                }
            }
        }
        catch ( Exception $e )
        {
            $objReader = PHPExcel_IOFactory::createreader( "excel2007" );
            try
            {
                $objPHPExcel = $objReader->load( $excel_file );
            }
            catch ( Exception $e1 )
            {
                echo "不能正常解析文件，请确认是否是正常的Excel文件[0x0030023]";
                exit( );
            }
        }
        if ( !empty( $objPHPExcel ) )
        {
            try
            {
                $sheet = $objPHPExcel->getSheet( 0 );
                $highestRow = $sheet->getHighestRow( );
                $highestColumn = $sheet->getHighestColumn( );
                foreach ( $objPHPExcel->getWorksheetIterator( ) as $j => $worksheet )
                {
                    $worksheetTitle = $worksheet->getTitle( );
                    $highestRow = $worksheet->getHighestRow( );
                    $highestColumn = $worksheet->getHighestColumn( );
                    $highestColumnIndex = PHPExcel_Cell::columnindexfromstring( $highestColumn );
                    $nrColumns = ord( $highestColumn ) - 64;
                    $GLOBALS['GLOBALS']['totalColum'] = $highestColumnIndex;
                    $row = 1;
                    for ( ; $row <= $highestRow; ++$row )
                    {
                        $col = 0;
                        for ( ; $col < $highestColumnIndex; ++$col )
                        {
                            $cell = $worksheet->getCellByColumnAndRow( $col, $row );
                            $val = $cell->getValue( );
                            if ( gettype( $val ) == "object" )
                            {
                                $GLOBALS['GLOBALS']['data'][$row][$col] = $val->__toString( );
                            }
                            else
                            {
                                $GLOBALS['GLOBALS']['data'][$row][$col] = $val;
                            }
                        }
                    }
                }
            }
            catch ( Exception $e )
            {
                echo "不能正常解析文件，请确认是否是正常的Excel文件[0x0030021]";
                exit( );
            }
        }
        else
        {
            echo "不能正常解析文件，请确认是否是正常的Excel文件[0x0030022]";
            exit( );
        }
        $this->_insertExcelTo( $GLOBALS['data'] );
    }

    private function _upload_excel( )
    {
        set_time_limit( 0 );
        $file_ext = strtolower( strrchr( $_FILES['importExcel']['name'], "." ) );
        $file_name = $GLOBALS['CNOA_TIMESTAMP']."_".md5( $GLOBALS['CNOA_TIMESTAMP'] ).$file_ext;
        $file_dst = CNOA_PATH_FILE."/common/temp/".$file_name;
        $file_url = $GLOBALS['CNOA_BASE_URL_FILE']."/common/temp/".$file_name;
        $extArray = array( ".xls" );
        if ( !in_array( $file_ext, $extArray ) )
        {
            msg::callback( FALSE, lang( "fileCanEXCELxls" ) );
        }
        if ( cnoa_move_uploaded_file( $_FILES['importExcel']['tmp_name'], $file_dst."_tmp" ) )
        {
            $this->_importExcel( $file_dst."_tmp" );
            @unlink( $file_dst."_tmp" );
            exit( );
        }
        msg::callback( FALSE, lang( "uploadFail" ) );
    }

    private function _getChecktime( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $dbinfo = app::loadapp( "main", "user" )->api_getUserDataByUid( $uid );
        $cid = $dbinfo['worktimeId'];
        app::loadapp( "hr", "attendance" )->api_initSetting( );
        $where = "WHERE `uid`=".$uid." AND `status`=1";
        $lcount = app::loadapp( "user", "attendanceLeave" )->api_getLeaveCount( $where );
        if ( 0 < $lcount )
        {
            msg::callback( FALSE, "请您先到“个人考勤”的“请假登记”那申请销假." );
        }
        $dblist = app::loadapp( "hr", "attendance" )->api_getVacationList( "" );
        $lcount = 0;
        $cdate = date( "Y-m-d" );
        foreach ( $dblist as $dbinfo )
        {
            $stime = $dbinfo['begindate'];
            $stime = date( "Y-m-d", $stime );
            $etime = $dbinfo['enddate'];
            $arrTime2 = array( );
            do
            {
                $w = count( $arrTime2 );
                if ( 0 < $w )
                {
                    $time = $arrTime2[$w - 1];
                }
                else
                {
                    $time = $stime;
                    $s = date( "Y-m-d", strtotime( $stime ) );
                    $arrTime2[] = $s;
                }
                $time = strtotime( $time );
                $s = strtotime( "+24 hours", $time );
                $ss = date( "Y-m-d", $s );
                $arrTime2[] = $ss;
                if ( $ss == $cdate )
                {
                    msg::callback( FALSE, "今天是节假日，不用考勤." );
                }
            } while ( 1 );
        }
        $return = app::loadapp( "hr", "attendance" )->api_getSettingWorktime( $cid );
        $infos = json_decode( $return );
        $date = date( "Y-m-d" );
        $dblist = $CNOA_DB->db_select( "*", $this->table_checktime, "WHERE `cid`=".$cid." AND `uid`={$uid} AND `date`='{$date}'" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $i = 0;
        foreach ( $infos->data as $v )
        {
            $data =& $infos->data[$i];
            $data->recTime = "";
            $data->recExplain = "";
            $data->recType = 0;
            $data->checked = 0;
            $onbeforetime = $infos->onbeforetime;
            $onaftertime = $infos->onaftertime;
            $offbeforetime = $infos->offbeforetime;
            $offaftertime = $infos->offaftertime;
            $time = strtotime( $data->time );
            reset( &$dblist );
            foreach ( $dblist as $v2 )
            {
                if ( $v->cnum == $v2['cnum'] )
                {
                    $data->recTime = $v2['time'];
                    $data->recExplain = $v2['explain'];
                    $recTime = strtotime( $v2['time'] );
                    if ( $data->type == 0 )
                    {
                        if ( $time < $recTime )
                        {
                            $data->recType = 1;
                        }
                    }
                    else if ( $recTime < $time )
                    {
                        $data->recType = 2;
                    }
                }
            }
            switch ( $data->type )
            {
            case 0 :
                $beforetime = strtotime( "-".$onbeforetime." minutes", $time );
                $aftertime = strtotime( "+".$onaftertime." minutes", $time );
                break;
            case 1 :
                $beforetime = strtotime( "-".$offbeforetime." minutes", $time );
                $aftertime = strtotime( "+".$offaftertime." minutes", $time );
            }
            $now_time = strtotime( date( "Y-m-d H:i:s" ) );
            if ( $beforetime <= $now_time && $now_time <= $aftertime )
            {
                $data->checked = 1;
            }
            ++$i;
        }
        $echo = json_encode( $infos );
        echo $echo;
        exit( );
    }

    private function _addChecktime( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $cid = getpar( $_POST, "cid" );
        $num = getpar( $_POST, "num" );
        $this->__addChecktime( $uid, $cid, $num );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _editChecktimeExplain( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $cid = getpar( $_POST, "cid" );
        $num = getpar( $_POST, "cnum" );
        $explain = getpar( $_POST, "explain" );
        $uid = $CNOA_SESSION->get( "UID" );
        $date = date( "Y-m-d" );
        $where = "WHERE `cid`=".$cid." AND `uid`={$uid} AND `date`='{$date}' AND `cnum`={$num}";
        $count = $CNOA_DB->db_getcount( $this->table_checktime, $where );
        $data = array( );
        $data['explain'] = $explain;
        if ( 0 < $count )
        {
            $CNOA_DB->db_update( $data, $this->table_checktime, $where );
        }
        else
        {
            $data['uid'] = $uid;
            $data['cid'] = $cid;
            $data['cnum'] = $num;
            $data['date'] = $date;
            $data['numDate'] = date( "Ymd" );
            $data['ip'] = getip( );
            $CNOA_DB->db_insert( $data, $this->table_checktime );
        }
        msg::callback( TRUE, lang( "successopt" ) );
        exit( );
    }

    private function __addChecktime( $uid, $cid, $num )
    {
        global $CNOA_DB;
        $date = date( "Y-m-d" );
        $time = date( "H:i:s" );
        $wt = app::loadapp( "hr", "attendanceSetting" )->api_getSettingWorktime( $cid );
        $wt = json_decode( $wt );
        $wtInfo = $wt->data[$num - 1];
        $wtTime = $wtInfo->time;
        if ( $wtInfo->type == 0 )
        {
            $stime = $wt->onbeforetime;
            $etime = $wt->onaftertime;
        }
        else
        {
            $stime = $wt->offbeforetime;
            $etime = $wt->offaftertime;
        }
        $wtTime = strtotime( $date." ".$wtTime );
        $stime = strtotime( "-".$stime." minutes", $wtTime );
        $etime = strtotime( "+".$etime." minutes", $wtTime );
        $now_time = time( );
        if ( $now_time < $stime || $etime < $now_time )
        {
            msg::callback( FALSE, "您好，登记时间已过，不能进行登记操作." );
        }
        $db_count = $CNOA_DB->db_getcount( $this->table_checktime, "WHERE `cid`=".$cid." AND `cnum`={$num} AND `date`='{$date}' AND `uid`={$uid}" );
        if ( 0 < $db_count )
        {
            msg::callback( FALSE, "您好，请不要重复提交." );
        }
        $insert = array( );
        $insert['cid'] = $cid;
        $insert['cnum'] = $num;
        $insert['date'] = $date;
        $insert['numDate'] = date( "Ymd" );
        $insert['time'] = $time;
        $insert['uid'] = $uid;
        $insert['ip'] = getip( );
        $CNOA_DB->db_insert( $insert, $this->table_checktime );
    }

    public function api_addChecktime( $uid, $cid, $num )
    {
        $this->__addChecktime( $uid, $cid, $num );
    }

    public function api_isVacation( )
    {
        $dblist = app::loadapp( "hr", "attendance" )->api_getVacationList( "" );
        $isVacation = FALSE;
        $lcount = 0;
        $cdate = date( "Y-m-d" );
        foreach ( $dblist as $dbinfo )
        {
            $stime = $dbinfo['begindate'];
            $stime = date( "Y-m-d", $stime );
            $etime = $dbinfo['enddate'];
            $arrTime2 = array( );
            do
            {
                $w = count( $arrTime2 );
                if ( 0 < $w )
                {
                    $time = $arrTime2[$w - 1];
                }
                else
                {
                    $time = $stime;
                    $s = date( "Y-m-d", strtotime( $stime ) );
                    $arrTime2[] = $s;
                }
                $time = strtotime( $time );
                $s = strtotime( "+24 hours", $time );
                $ss = date( "Y-m-d", $s );
                $arrTime2[] = $ss;
                if ( $ss == $cdate )
                {
                    $isVacation = TRUE;
                }
            } while ( 1 );
        }
        return $isVacation;
    }

}

?>
