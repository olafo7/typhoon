<?php 

class attExportExcel extends attCommon 
{
	public function attStatisticsExport1($dids, $sdate, $edate){
		$sdate = strtotime($sdate);
		$edate = strtotime($edate . " 23:59:59");

		$where = "(o.`stime` BETWEEN '{$sdate}' AND '{$edate}' OR o.`etime` BETWEEN '{$sdate}' AND '{$edate}') AND o.status = 1";


		$fileName = "CNOA.HR-".date("Ymd", $GLOBALS['CNOA_TIMESTAMP'])."-".string::rands(10, 2).".xlsx";
		$info[] = app::loadApp('att', 'arrangeStatistics')->api_getWorkStatistics($dids, $sdate, $edate);
		$info[] = $this->getAttExportLeave($dids, $where, $this->table_leave);
		$info[] = $this->getAttExportEvection($dids, $where, $this->table_evection);
		$info[] = $this->getAttExportEgression($dids, $where, $this->table_egression);
		$info[] = $this->getAttExportOvertime($dids, $where, $this->table_overtime);
		
		$attInfo = include(CNOA_PATH . "/resources/export/att_statistics.php");
		
		$excel = new exportExcel();
		$info = $excel->formatExcelDate($attInfo, $info);
		$timeStr = '  导出时间段为 ' . date('Y-m-d', $sdate) . ' 至 ' . date('Y-m-d', $edate);

		$sheetName=array('员工考勤统计', '请假统计', '出差统计', '外出统计', '加班统计');

		$excel->init($info, $sheetName, $timeStr);
		$excel->save(CNOA_PATH_FILE. "/common/temp/". $fileName, 'excel2007');
		unset($info, $attInfo);
		msg::callBack(true, $fileName);
	}

	public function attStatisticsExport2($fileName){
		$file	  = CNOA_PATH_FILE. "/common/temp/". $fileName;
		
		if(!file_exists($file)){
			echo '文件不存在!';
			exit;
		}
		
		@ini_set('zlib.output_compression', 'Off');
				    
	    //开始下载文件
	    header( "Content-Type: application/octet-stream");
	    header( "Content-Disposition: attachment;filename=".cn_urlencode('考勤统计列表.xlsx'));
	    header( "Content-Length: ".filesize($file));
	    
	    ob_clean();
	    flush();
	    readfile($file);

	    @unlink($file);
	}

	public function attRecordExport1($uid, $date){
		if (empty($date)) {
			$date = date('Y-m');
		}
		$curDate = strtotime($date);
		$year = date('Y', $curDate);
		$month = date('m', $curDate);
		$days = date("t", strtotime("{$year}-{$month}-01"));
		$sdate = strtotime("{$year}-{$month}-01");
		$edate = strtotime("{$year}-{$month}-{$days}");

		$today = date('d');
		$nowMon = date('m');
		$truename = app::loadApp('main', 'user')->api_getUserTruenameByUid($uid);

		$fileName = "CNOA.HR-".date("Ymd", $GLOBALS['CNOA_TIMESTAMP'])."-".string::rands(10, 2).".xlsx";
		$result = app::loadApp('att', 'arrangeRecord')->api_getRecordWorkTime($uid, $sdate, $edate, 'getRecord');
		
		$data = array();
		foreach ($result as $value) {
			$item = array();

			$item['uid'] = $uid;
			$item['date'] = $value['date'];
			$item['oneTime'] = $this->changeWorkStatus($value['oneStime'], $value['oneTime'], $value['oneExplain']);
			$item['twoTime'] = $this->changeWorkStatus($value['oneEtime'], $value['twoTime'], $value['twoExplain']);
			$item['threeTime'] = $this->changeWorkStatus($value['twoStime'], $value['threeTime'], $value['threeExplain']);
			$item['fourTime'] = $this->changeWorkStatus($value['twoEtime'], $value['fourTime'], $value['fourExplain']);
			$item['leaveHours'] = empty($value['leaveHours']) ? "" : "请假".$value['leaveHours']."小时";
			$data[] = $item;
		}

		$info[] = $data;
		$attInfo = include(CNOA_PATH . "/resources/export/att_record.php");
		
		$excel = new exportExcel();
		$info = $excel->formatExcelDate($attInfo, $info);

		$sheetName=array($truename . '的考勤记录');
		$excel->init($info, $sheetName);
		$excel->save(CNOA_PATH_FILE. "/common/temp/". $fileName, 'excel2007');
		unset($info, $attInfo, $data, $result);
		msg::callBack(true, $fileName);
	}

	public function attRecordExport2($fileName){
		$file = CNOA_PATH_FILE. "/common/temp/". $fileName;
		
		if(!file_exists($file)){
			echo '文件不存在!';
			exit;
		}
		
		@ini_set('zlib.output_compression', 'Off');

		app::loadApp('main', 'systemLogs')->api_addLogs('export', 4501,'考勤记录.xlsx', '了考勤记录');

	    //开始下载文件
	    header( "Content-Type: application/octet-stream");
	    header( "Content-Disposition: attachment;filename=".cn_urlencode('考勤记录.xlsx'));
	    header( "Content-Length: ".filesize($file));
	    
	    ob_clean();
	    flush();
	    readfile($file);

	    @unlink($file);
	}

	private function getAttExportLeave($dids, $where, $table){
		global $CNOA_DB;

		$sql = 'SELECT s.name, o.* FROM ' . tname('main_user') . ' AS u '
			 . 'LEFT JOIN ' . tname('main_struct') . ' AS s ON u.deptId=s.id '
			 . 'LEFT JOIN ' . tname($table) . ' AS o ON o.uid=u.uid '
			 . "WHERE s.id IN ({$dids}) AND {$where} ORDER BY o.uid ASC";

		$result = $CNOA_DB->query($sql);
		$data = array();
		while ($row = $CNOA_DB->get_array($result)){
			$item = array();
			$deptName = $row['name'];
			$item['uid'] = $row['uid'];
			$item['truename'] = $row['truename'];
			$item['deptName'] = $row['name'];
			$item['posttime'] = date('Y-m-d H:i', $row['posttime']);
			$item['stime'] = date('Y-m-d H:i', $row['stime']);
			$item['etime'] = date('Y-m-d H:i', $row['etime']);
			$item['time'] = $row['days'].'天';
			$item['reason'] = $row['reason'];

			$data[] = $item;
		}

		$sql = 'SELECT s.name, o.* FROM ' . tname('main_user') . ' AS u '
			 . 'LEFT JOIN ' . tname('main_struct') . ' AS s ON u.deptId=s.id '
			 . 'LEFT JOIN ' . tname($this->table_leave_hours) . ' AS o ON o.uid=u.uid '
			 . "WHERE s.id IN ({$dids}) AND {$where} ORDER BY o.uid ASC";

		$result = $CNOA_DB->query($sql);
		while ($row = $CNOA_DB->get_array($result)){
			$item = array();
			$deptName = $row['name'];
			$item['uid'] = $row['uid'];
			$item['truename'] = $row['truename'];
			$item['deptName'] = $row['name'];
			$item['posttime'] = date('Y-m-d H:i', $row['posttime']);
			$item['stime'] = date('Y-m-d H:i', $row['stime']);
			$item['etime'] = date('Y-m-d H:i', $row['etime']);
			$item['time'] = $row['hours'].'小时';
			$item['reason'] = $row['reason'];

			$data[] = $item;
		}
		return $data;
	}
	
	private function getAttExportEvection($dids, $where, $table){
		global $CNOA_DB;

		$sql = 'SELECT s.name, o.* FROM ' . tname('main_user') . ' AS u '
			 . 'LEFT JOIN ' . tname('main_struct') . ' AS s ON u.deptId=s.id '
			 . 'LEFT JOIN ' . tname($table) . ' AS o ON o.uid=u.uid '
			 . "WHERE s.id IN ({$dids}) AND {$where} ORDER BY o.uid ASC";

		$result = $CNOA_DB->query($sql);
		$data = array();
		while ($row = $CNOA_DB->get_array($result)){
			$item = array();
			$deptName = $row['name'];
			$item['uid'] = $row['uid'];
			$item['truename'] = $row['truename'];
			$item['deptName'] = $row['name'];
			$item['posttime'] = date('Y-m-d H:i', $row['posttime']);
			$item['stime'] = date('Y-m-d H:i', $row['stime']);
			$item['etime'] = date('Y-m-d H:i', $row['etime']);
			$item['address'] = $row['address'];
			$item['reason'] = $row['reason'];

			$data[] = $item;
		}

		return $data;
	}

	private function getAttExportEgression($dids, $where, $table){
		global $CNOA_DB;

		$sql = 'SELECT s.name, o.* FROM ' . tname('main_user') . ' AS u '
			 . 'LEFT JOIN ' . tname('main_struct') . ' AS s ON u.deptId=s.id '
			 . 'LEFT JOIN ' . tname($table) . ' AS o ON o.uid=u.uid '
			 . "WHERE s.id IN ({$dids}) AND {$where} ORDER BY o.uid ASC";

		$result = $CNOA_DB->query($sql);
		$data = array();
		while ($row = $CNOA_DB->get_array($result)){
			$item = array();
			$deptName = $row['name'];
			$item['uid'] = $row['uid'];
			$item['truename'] = $row['truename'];
			$item['deptName'] = $row['name'];
			$item['posttime'] = date('Y-m-d H:i', $row['posttime']);
			$item['stime'] = date('Y-m-d H:i', $row['stime']);
			$item['etime'] = date('Y-m-d H:i', $row['etime']);
			$item['address'] = $row['address'];
			$item['reason'] = $row['reason'];

			$data[] = $item;
		}

		return $data;
	}

	private function getAttExportOvertime($dids, $where, $table){
		global $CNOA_DB;

		$sql = 'SELECT s.name, o.* FROM ' . tname('main_user') . ' AS u '
			 . 'LEFT JOIN ' . tname('main_struct') . ' AS s ON u.deptId=s.id '
			 . 'LEFT JOIN ' . tname($table) . ' AS o ON o.uid=u.uid '
			 . "WHERE s.id IN ({$dids}) AND {$where} ORDER BY o.uid ASC";

		$result = $CNOA_DB->query($sql);
		$data = array();
		while ($row = $CNOA_DB->get_array($result)){
			$item = array();
			$deptName = $row['name'];
			$item['uid'] = $row['uid'];
			$item['truename'] = $row['truename'];
			$item['deptName'] = $row['name'];
			$item['posttime'] = date('Y-m-d H:i', $row['posttime']);
			$item['stime'] = date('Y-m-d H:i', $row['stime']);
			$item['etime'] = date('Y-m-d H:i', $row['etime']);
			$item['hour'] = $row['hour'];
			$item['reason'] = $row['reason'];

			$data[] = $item;
		}

		return $data;
	}

	private function changeWorkStatus($status, $time, $explain) {
		if ($status == '未登记' ) {
			$value = lang('unRegister');
		} else if ($status == '休假') {
			$value = lang('beOnLeave');
		} else if ($status == '请假') {
			$value = lang('leave');
		} else if ($status == '正常') {
			$value = $time;
		} else if ($status == '未登记1') {
			if (!empty($explain)) {
				$value = lang('unRegister') . '(' . $explain . ')';
			} else {
				$value = lang('unRegister');
			}
		} else if ($status == '出差') {
			$value = lang('evection');
		} else if ($status == '外出') {
			$value = lang('egress');
		} else if ($status == '(出差)'){
			$value = $time . '(' . lang('evection') . ')';
		} else if ($status == '(外出)'){
			$value = $time . '(' . lang('egress') . ')';
		} else if ($status == '迟到'){
			if (!empty($explain)) {
				$value = $time . '(' . lang('late') . ')(' . $explain . ')';
			} else {
				$value = $time . '(' . lang('late') . ')';
			}
		} else if ($status == '早退'){
			if (!empty($explain)) {
				$value = $time . '(' . lang('leaveEarly') . ')(' . $explain . ')';
			} else {
				$value = $time . '(' . lang('leaveEarly') . ')';
			}
		} else if ($status == '未登记(休假)') {
			$value = lang('unRegister') . '(' . lang('beOnLeave') . ')';
		} else if ($status == '免签') {
			$value = lang('notSignIn');
		}

		return $value;
	}
}
?>