<?php
//decode by qq2859470

class salaryManageCount extends salaryManage
{

    private $monthArr = array
    (
        "01" => "jan",
        "02" => "feb",
        "03" => "mar",
        "04" => "apr",
        "05" => "may",
        "06" => "jun",
        "07" => "jul",
        "08" => "aug",
        "09" => "sept",
        10 => "oct",
        11 => "nov",
        12 => "dec"
    );
    private $salaryArr = array
    (
        "shouldPay" => "e.`field1`",
        "actualPay" => "e.`field2`"
    );
    private $titleArr = array
    (
        "truename" => "姓名",
        "dept" => "所在部门",
        "salStatus" => "状态",
        "year" => "年份",
        "jan" => "一月",
        "feb" => "二月",
        "mar" => "三月",
        "apr" => "四月",
        "may" => "五月",
        "jun" => "六月",
        "jul" => "七月",
        "aug" => "八月",
        "sept" => "九月",
        "oct" => "十月",
        "nov" => "十一月",
        "dec" => "十二月",
        "count" => "总计"
    );
    private $oldYearNum = 10;
    private $newYearNum = 0;

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            exit( );
        case "getDeptTree" :
            $this->_getDeptTree( );
            exit( );
        case "getcountJsonData" :
            $this->_getcountJsonData( );
            exit( );
        case "salaryCountExportExcel" :
            $this->_salaryCountExportExcel( );
            exit( );
        case "getYear" :
            $this->_getYear( );
        }
        exit( );
    }

    private function _loadPage( )
    {
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/manage/count.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
    }

    private function _getDeptTree( )
    {
        echo app::loadapp( "main", "struct" )->api_getStructTree( );
    }

    private function _getYear( )
    {
        global $CNOA_DB;
        global $CNOA_PERMIT;
        $data = array( );
        $nowYear = intval( date( "Y", time( ) ) );
        $i = 0;
        for ( ; $i < $this->oldYearNum; ++$i )
        {
            $data[]['value'] = $nowYear - ( $this->oldYearNum - $i );
        }
        $data[]['value'] = $nowYear;
        $i = 1;
        for ( ; $i < $this->newYearNum; ++$i )
        {
            $data[]['value'] = $nowYear + $i;
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        echo $ds->makeJsonData( );
    }

    private function _getcountJsonData( )
    {
        global $CNOA_DB;
        global $CNOA_PERMIT;
        $truename = getpar( $_POST, "truename" );
        $deptId = ( integer )getpar( $_POST, "deptId" );
        $salary = ( integer )getpar( $_POST, "salary", 1 );
        $status = getpar( $_POST, "status", "" );
        $search = getpar( $_POST, "isSearch", "" );
        $year = getpar( $_POST, "date", 2016 );
        $start = getpar( $_POST, "start", 0 );
        $row = getpagesize( "salary_manage_detail" );
        if ( empty( $deptId ) )
        {
            return;
        }
        if ( $salary == 1 )
        {
            $field = "e.`field1`";
            $salStatus = "应发";
        }
        else if ( $salary == 2 )
        {
            $field = "e.`field2`";
            $salStatus = "实发";
        }
        $fields = "";
        foreach ( $this->monthArr as $k => $v )
        {
            $fields .= " sum( CASE e.`month` WHEN '".$k."' THEN {$field} ELSE 0 END ) AS `{$v}`,";
        }
        $fields .= "e.`id`,e.`truename`,e.`uid`,e.`year`,d.`name` AS dept ";
        $WHERE = array( );
        if ( !empty( $year ) )
        {
            $WHERE[] = " e.`year` = '".$year."'";
        }
        if ( $search == "search" && !empty( $truename ) )
        {
            $WHERE[] = "u.`truename` LIKE '%".$truename."%'";
        }
        $WHERE[] = "e.`isInSure`=1";
        if ( !empty( $deptId ) )
        {
            $WHERE[] = "u.`deptId`=".$deptId;
        }
        $WHERE[] = "k.salaryKey != 0";
        $WHERE = implode( " AND ", $WHERE );
        $groupOrder = "GROUP BY e.`truename`,e.`year` ORDER BY e.`year` DESC";
        $sql = "SELECT".$fields."FROM ".tname( $this->table_manage_entering )." AS e LEFT JOIN ".tname( $this->table_main_user )." AS u ON u.uid = e.uid LEFT JOIN ".tname( $this->table_main_struct )." AS d ON u.deptId = d.id LEFT JOIN ".tname( $this->table_basic_key )." AS k ON k.uid = e.uid ".( "WHERE ".$WHERE." {$groupOrder} LIMIT {$start},{$row}" );
        $result = $CNOA_DB->query( $sql );
        $temp = $data = array( );
        foreach ( $this->monthArr as $v )
        {
            $count[$v] = 0;
        }
        $count['count'] = 0;
        while ( $rows = $CNOA_DB->get_array( $result ) )
        {
            $rows['salStatus'] = $salStatus;
            $rows['count'] = 0;
            foreach ( $this->monthArr as $v )
            {
                $rows['count'] += $rows[$v];
                $count[$v] += $rows[$v];
            }
            $count['count'] += $rows['count'];
            $data[] = $rows;
        }
        if ( !empty( $data ) )
        {
            $count['truename'] = "总计";
            $data[] = $count;
        }
        unset( $count );
        $sql = "SELECT count(*) AS count FROM ".tname( $this->table_manage_entering )." AS e LEFT JOIN ".tname( $this->table_main_user )." AS u ON u.uid = e.uid LEFT JOIN ".tname( $this->table_main_struct )." AS d ON u.deptId = d.id LEFT JOIN ".tname( $this->table_basic_key )." AS k ON k.uid = e.uid ".( "WHERE ".$WHERE." {$groupOrder}" );
        $result = $CNOA_DB->query( $sql );
        $total = 0;
        while ( !empty( $result ) || ( $rows = $CNOA_DB->get_array( $result ) ) )
        {
            ++$total;
        }
        ( );
        $ds = new dataStore( );
        $ds->data = $data;
        $ds->total = $total;
        echo $ds->makeJsonData( );
    }

    private function _salaryCountExportExcel( )
    {
        global $CNOA_SESSION;
        $step = getpar( $_GET, "step" );
        if ( $step == 1 )
        {
            $date = getpar( $_POST, "date" );
            $deptId = getpar( $_POST, "deptId" );
            $act = getpar( $_POST, "act", "all" );
            $this->getSalaryExportFile( $date, $deptId, $act );
        }
        else if ( $step == 2 )
        {
            $fileName = getpar( $_GET, "file", "" );
            $value = getpar( $_GET, "value" );
            $date = getpar( $_GET, "date" );
            $this->downSalaryExportFile( $fileName );
            $truename = $CNOA_SESSION->get( "TRUENAME" );
            $deptName = app::loadapp( "main", "struct" )->api_getNameById( $value );
            if ( $value )
            {
                $name = $truename.( "导出[".$date."年]的薪酬统计记录，导出部门：{$deptName}" );
            }
            else
            {
                $name = $truename.( "导出[".$date."年]的薪酬统计记录，导出部门：所有部门" );
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 5000, $name, "薪酬导出" );
        }
    }

    public function getSalaryExportFile( $date, $deptId, $act = "all" )
    {
        global $CNOA_DB;
        global $CNOA_PERMIT;
        if ( $act == "dept" && $deptId != "" )
        {
            $shouldPayArr = $this->getSalaryData( $this->salaryArr['shouldPay'], $date, $deptId );
            $actualPayArr = $this->getSalaryData( $this->salaryArr['actualPay'], $date, $deptId );
            if ( empty( $shouldPayArr ) && empty( $actualPayArr ) )
            {
                msg::callback( FALSE, "该部门没有薪酬记录" );
            }
            $fileName = "{$shouldPayArr[0]['dept']}[{$date}]薪酬统计";
            $sheetName = "{$shouldPayArr[0]['dept']}薪酬统计";
            $title = "{$date}{$shouldPayArr[0]['dept']}薪酬统计列表-[时间：".date( "Y-m-d H:i:s", time( ) )."]";
        }
        else if ( $act == "all" || $deptId == "" )
        {
            $shouldPayArr = $this->getSalaryData( $this->salaryArr['shouldPay'], $date, "all" );
            $actualPayArr = $this->getSalaryData( $this->salaryArr['actualPay'], $date, "all" );
            if ( empty( $shouldPayArr ) && empty( $actualPayArr ) )
            {
                msg::callback( FALSE, "没有薪酬记录" );
            }
            $fileName = "所有部门[".$date."]薪酬统计";
            $sheetName = "所有部门薪酬统计";
            $title = "{$date}所有部门薪酬统计列表-[时间：".date( "Y-m-d H:i:s", time( ) )."]";
        }
        $count = $temp = NULL;
        $count['truename'] = "总计";
        foreach ( $this->monthArr as $v )
        {
            $temp[$v]['scount'] = 0;
            $temp[$v]['acount'] = 0;
        }
        $temp['count']['scount'] = 0;
        $temp['count']['acount'] = 0;
        $shouldcount = $actualcount = 0;
        foreach ( $shouldPayArr as $k => $v )
        {
            $v['count'] = 0;
            $shouldcount = $actualcount = 0;
            foreach ( $this->monthArr as $key => $val )
            {
                $temp[$val]['scount'] += $v[$val];
                $temp[$val]['acount'] += $actualPayArr[$k][$val];
                $v[$val] .= "/".$actualPayArr[$k][$val];
                $shouldcount += $v[$val];
                $actualcount += $actualPayArr[$k][$val];
            }
            $v['count'] = $shouldcount."/".$actualcount;
            $info[] = $v;
        }
        foreach ( $this->monthArr as $v )
        {
            $count[$v] = $temp[$v]['scount']."/".$temp[$v]['acount'];
            $temp['count']['scount'] += $temp[$v]['scount'];
            $temp['count']['acount'] += $temp[$v]['acount'];
        }
        $count['count'] = $temp['count']['scount']."/".$temp['count']['acount'];
        unset( $temp );
        foreach ( $info as $v )
        {
            $v['status'] = "应发工资/实发工资";
            $data[] = $v;
        }
        $source = $data;
        $source[] = $count;
        ( );
        $excel = new salaryCountExportExcel( );
        $excel->init( $source, $title, $sheetName, $this->titleArr );
        $excel->save( CNOA_PATH_FILE."/common/temp/".$fileName, "excel2007" );
        msg::callback( TRUE, $fileName );
    }

    public function downSalaryExportFile( $fileName )
    {
        $file = CNOA_PATH_FILE."/common/temp/".$fileName;
        if ( !file_exists( $file ) )
        {
            echo "文件不存在!";
            exit( );
        }
        @ini_set( "zlib.output_compression", "Off" );
        header( "Content-Type: application/octet-stream" );
        header( "Content-Disposition: attachment;filename=".cn_urlencode( "{$fileName}.xlsx" ) );
        header( "Content-Length: ".filesize( $file ) );
        ob_clean( );
        flush( );
        readfile( $file );
        @unlink( $file );
    }

    private function getSalaryData( $field, $date = "", $deptId = "all" )
    {
        global $CNOA_DB;
        $fields = "";
        foreach ( $this->monthArr as $k => $v )
        {
            $fields .= " SUM(CASE e.`month` WHEN '".$k."' THEN {$field} ELSE 0 END )  AS `{$v}`,";
        }
        $fields .= " e.`id`,e.`truename`,e.`uid`,e.`year`,d.`name` AS dept ";
        $WHERE = array( );
        if ( !empty( $date ) )
        {
            $WHERE[] = " e.`year` = '".$date."'";
        }
        $WHERE[] = "e.`isInSure`=1";
        if ( !empty( $deptId ) || $deptId != "all" )
        {
            $WHERE[] = "u.`deptId`=".$deptId;
        }
        $WHERE[] = "k.salaryKey != 0";
        $WHERE = implode( " AND ", $WHERE );
        $groupOrder = "GROUP BY e.`truename`,e.`year` ORDER BY e.`year` DESC";
        $sql = "SELECT".$fields."FROM ".tname( $this->table_manage_entering )." AS e LEFT JOIN ".tname( $this->table_main_user )." AS u ON u.uid = e.uid LEFT JOIN ".tname( $this->table_main_struct )." AS d ON u.deptId = d.id LEFT JOIN ".tname( $this->table_basic_key )." AS k ON k.uid = e.uid ".( "WHERE ".$WHERE." {$groupOrder}" );
        $result = $CNOA_DB->query( $sql );
        $data = array( );
        while ( $rows = $CNOA_DB->get_array( $result ) )
        {
            $data[] = $rows;
        }
        return $data;
    }

}

?>
