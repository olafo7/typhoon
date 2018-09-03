<?php 
class odocFilesBrwchk extends model{
	private $t_step_list	= "odoc_step";
	private $t_dangan		= "odoc_files_dangan";
	private $t_borrow		= "odoc_files_borrow_list";
	private $t_read_file= "odoc_read_file";

	private $f_status		= array(0=>"待审", 1=>"审批通过", 2=>"审批不通过", 3=>"已归还");
	private $f_from			= array(3=>"其他", 1=>"发文", 2=>"收文");
	
	private $rows			= 15;
									
	/**
	 * 构造函数
	 */
	public function __construct() {
		//callConstructFunction();
	}
	/**
	 * 析构函数
	 */
	public function __destruct(){
		//callDestructFunction();
	}

	public function run(){
		$task = getPar($_GET, "task", "loadpage");
		switch ($task) {
			case "loadpage" :
				$this->_loadpage();
				break;
			case "getJsonData" :
				$this->_getJsonData();
				break;
			case "submit" :
				$from = getPar($_POST, "from", "");
				if($from == "agree"){
					$this->_agree();
				}elseif ($from == "disagree"){
					$this->_disagree();
				}
				break;
			#查看
			case 'view':
				app::loadApp("odoc", "commonFilesView")->run();
				break;
		}
	}
	
	private function _loadpage(){
		
	}
	
	private function _getJsonData(){
		global $CNOA_DB, $CNOA_SESSION;
		$start = getPar($_POST, "start", 0);
		$storeType = getPar($_POST, "storeType", "waiting");
		$WHERE = "WHERE 1 ";
		if($storeType == "waiting") {
			$WHERE .= "AND `status` = '1' ";
		}elseif ($storeType == "pass") {
			$WHERE .= "AND `status` = '2' ";
		}
		$uid = $CNOA_SESSION->get("UID");
		$CNOA_DB->db_update(array("status"=>3), $this->t_borrow, "WHERE `etime` < '{$GLOBALS['CNOA_TIMESTAMP']}'");
		$dblist = $CNOA_DB->db_select(array("fromId", "id"), $this->t_step_list, $WHERE . "AND `uid` = '{$uid}' AND `fromType` = '3' AND `stepid` != '1' ORDER BY `id` ASC LIMIT {$start}, {$this->rows}");
		!is_array($dblist) && $dblist = array();
		$stepArr = array(0);
		foreach ($dblist as $k=>$v) {
			$stepArr[] = $v['fromId'];
		}
		//获取档案室所有信息
		$roomArr		= app::loadApp("odoc", "filesSetting")->api_getRoomData();
		//获取文件类别信息
		$typeArr		= app::loadApp("odoc", "filesSetting")->api_getTypeData();
		//获取文种类别信息
		$wenzhongArr	= app::loadApp("odoc", "filesSetting")->api_getWenzhongData();
		//获取案卷信息
		$anjuanArr		= app::loadApp("odoc", "filesAnjuanmgr")->api_anjuanArr();
		//获取等级信息
		$levelArr		= app::loadApp("odoc", "common")->getLevelAllArr();
		$tempBorrow		= $CNOA_DB->db_select("*", $this->t_borrow, "WHERE `id` IN (" . implode(',', $stepArr) . ") ");

		!is_array($tempBorrow) && $tempBorrow = array();
		$borrowArr = array();
		foreach ($tempBorrow as $k=>$v) {
			$borrowArr[$v['id']] = $v;
		}
		$filesArr = array(0);
		$postuidArr = array(0);
		foreach ($dblist as $k=>$v) {
			$filesArr[]		= $borrowArr[$v['fromId']]['fileid'];
			$postuidArr[]	= $borrowArr[$v['fromId']]['postuid'];
		}
		
		$danganArr = app::loadApp("odoc", "filesDananmgr")->api_getAllDanganArr($filesArr);
		$truenameArr = app::loadApp("main", "user")->api_getUserNamesByUids($postuidArr);
		
		foreach ($dblist as $k=>$v) {
			$dblist[$k]['fileid']		= $borrowArr[$v['fromId']]['fileid'];
			$dblist[$k]['posttime']		= formatDate($borrowArr[$v['fromId']]['posttime']);
			$dblist[$k]['status']		= $this->f_status[$borrowArr[$v['fromId']]['status']];
			$dblist[$k]['type']			= $this->f_from[$danganArr[$borrowArr[$v['fromId']]['fileid']]['from']];
			$dblist[$k]['danganshi']	= $roomArr[$danganArr[$borrowArr[$v['fromId']]['fileid']]['danganshi']]['title'];
			$dblist[$k]['type1']		= $typeArr[$danganArr[$borrowArr[$v['fromId']]['fileid']]['type']]['title'];
			$dblist[$k]['anjuan']		= $anjuanArr[$danganArr[$borrowArr[$v['fromId']]['fileid']]['anjuan']]['title'];
			$dblist[$k]['wenzhong']		= $wenzhongArr[$danganArr[$borrowArr[$v['fromId']]['fileid']]['wenzhong']]['title'];
			$dblist[$k]['level']		= $levelArr[$danganArr[$borrowArr[$v['fromId']]['fileid']]['level']]['title'];
			$dblist[$k]['reader']		= $truenameArr[$borrowArr[$v['fromId']]['postuid']]['truename'];
			$dblist[$k]['title']		= $danganArr[$borrowArr[$v['fromId']]['fileid']]['title'];
			$dblist[$k]['senddate']		= formatdate($danganArr[$borrowArr[$v['fromId']]['fileid']]['senddate']);
			$dblist[$k]['stime']		= formatDate($borrowArr[$v['fromId']]['stime']);
			$dblist[$k]['etime']		= formatDate($borrowArr[$v['fromId']]['etime']);
			$dblist[$k]['reason']		= nl2br($borrowArr[$v['fromId']]['reason']);
		}

		$dataStore = new dataStore();
		$dataStore->data = $dblist;
		$dataStore->total = $CNOA_DB->db_getcount($this->t_step_list, $WHERE . "AND `uid` = '{$uid}' AND fromType = '3' AND `stepid` != '1'");
		echo $dataStore->makeJsonData();
		exit();
	}
	
	private function _agree(){
		global $CNOA_DB, $CNOA_SESSION;
		$ids = getPar($_POST, "ids", 0);
		$ids = substr($ids, 0, -1);
		$uid = $CNOA_SESSION->get("UID");
		$idArr = explode(",", $ids);
		foreach ($idArr as $v) {
			$temp = $CNOA_DB->db_getone(array("stepid", "fromId", "noticeid_c", "todoid_c"), $this->t_step_list, "WHERE `id` = '{$v}' ");
			//审批待办完成时间
			notice::doneN($temp['noticeid_c']);
			notice::doneT($temp['todoid_c']);
			
			$stepid = $temp['stepid'] + 1;
			$next = $CNOA_DB->db_getone(array("id", "uid", "stepname"), $this->t_step_list, "WHERE `stepid` = '{$stepid}' AND `fromType` = '3' AND `fromId` = '{$temp['fromId']}' ");
		
			$borrow = $CNOA_DB->db_getone(array("postuid", "fileid", "stime", "etime"), $this->t_borrow, "WHERE `id` = '{$temp['fromId']}'");
			$info   = $CNOA_DB->db_getone(array("title", "number"), $this->t_dangan, "WHERE `id` = '{$borrow['fileid']}'");
			$CNOA_DB->db_update(array("status"=>2, "etime"=>$GLOBALS['CNOA_TIMESTAMP']), $this->t_step_list, "WHERE `id` = '{$v}'");
			if(!empty($next['id'])){
				//审批中
				$CNOA_DB->db_update(array("status"=>0, "fid"=>$next['id'], "stepname"=>$next['stepname']), $this->t_borrow, "WHERE `id` = '{$temp['fromId']}'");
				$noticeT = "提醒：有借阅流程需要您审批";
				$noticeC = "文号:" . $info['number'] . "标题[" . $info['title'] . "]需要您审批";
				$noticeH	= "index.php?app=odoc&func=files&action=brwchk";
				$step['noticeid_c']	= notice::add($next['uid'], $noticeT, $noticeC, $noticeH, 0, 20, $CNOA_SESSION->get('UID'));
				/*
				$notice['touid']	= $next['uid'];
				$notice['from']		= 17;
				$notice['fromid']	= 0;
				$notice['href']		= $noticeH;
				$notice['title']	= $noticeC;
				$notice['content']	= "";
				$notice['funname']	= "公文借阅管理";
				$notice['move']		= "审批";
				
				$step['todoid_c']	= notice::add2($notice);
				*/
				
				$step['status']	= 1;
				$step['stime']	= $GLOBALS['CNOA_TIMESTAMP'];
				$CNOA_DB->db_update($step, $this->t_step_list, "WHERE `id` = '{$next['id']}'");
				
				$notice['touid']	= $next['uid'];
				$notice['from']		= 18;
				$notice['fromid']	= $borrow['fileid'];
				$notice['href']		= $noticeH;
				$notice['title']	= $noticeC;
				//$notice['content']	= "库[{$library}] 类[{$type}]";
				$notice['funname']	= "公文借阅管理";
				$notice['move']		= "审批";
				notice::add2($notice);
				//系统操作日志
				app::loadApp('main', 'systemLogs')->api_addLogs('', 3207, '通过，转到下一步', "审批档案{$info['title']}");
				msg::callBack(true, "操作成功，流程已经到下一步了");
			}else{
				//审批通过
				$CNOA_DB->db_update(array("status"=>1, "fid"=>$v, "stepname"=>$next['stepname']), $this->t_borrow, "WHERE `id` = '{$temp['fromId']}'");
				$noticeT = "公文借阅管理";
				$noticeC = "文号:" . $info['number'] . "标题[" . $info['title'] . "]借阅审批通过";
				$noticeH	= "index.php?app=odoc&func=read&action=file";
				
				notice::add($borrow['postuid'], $noticeT, $noticeC, $noticeH, 0, 21, $CNOA_SESSION->get('UID'));
				//插入阅读
				$read['fileid'] = $borrow['fileid'];
				$read['type']	= 3;
				$read['receiveuid']	= $borrow['postuid'];
				$read['stime']	= $borrow['stime'];
				$read['etime']	= $borrow['etime'];
				$CNOA_DB->db_insert($read, $this->t_read_file);
				//系统操作日志
				app::loadApp('main', 'systemLogs')->api_addLogs('', 3207, '通过', "审批档案{$info['title']}");
				msg::callBack(true, "您已经是最后一步了，流程已全部走完");
			}
		}
	}
	
	private function _disagree(){
		global $CNOA_DB, $CNOA_SESSION;
		$ids = getPar($_POST, "ids", 0);
		$ids = substr($ids, 0, -1);
		$uid = $CNOA_SESSION->get("UID");
		$idArr = explode(",", $ids);
		foreach ($idArr as $v) {
			$temp = $CNOA_DB->db_getone(array("stepid", "fromId", "noticeid_c", "todoid_c"), $this->t_step_list, "WHERE `id` = '{$v}' ");
			//审批待办完成时间
			notice::doneN($temp['noticeid_c']);
			notice::doneT($temp['todoid_c']);
			
			$stepid = $temp['stepid'] + 1;
			$next = $CNOA_DB->db_getone(array("id", "uid", "stepname"), $this->t_step_list, "WHERE `stepid` = '{$stepid}' AND `fromType` = '3' AND `fromId` = '{$temp['fromId']}' ");
		
			$borrow = $CNOA_DB->db_getone(array("postuid", "fileid"), $this->t_borrow, "WHERE `id` = '{$temp['fromId']}'");
			$info   = $CNOA_DB->db_getone(array("title", "number"), $this->t_dangan, "WHERE `id` = '{$borrow['fileid']}'");
			$CNOA_DB->db_update(array("status"=>2, "etime"=>$GLOBALS['CNOA_TIMESTAMP']), $this->t_step_list, "WHERE `id` = '{$v}'");
			
			//审批不通过
			$CNOA_DB->db_update(array("status"=>2, "fid"=>$v, "stepname"=>$next['stepname']), $this->t_borrow, "WHERE `id` = '{$temp['fromId']}'");
			$noticeT = "公文借阅管理";
			$noticeC = "文号:" . $info['number'] . "标题[" . $info['title'] . "]借阅审批不通过";
			$noticeH	= "index.php?app=odoc&func=files&action=borrow&from=unpass";
			
			notice::add($borrow['postuid'], $noticeT, $noticeC, $noticeH, 0, 20, $CNOA_SESSION->get('UID'));
			
			$CNOA_DB->db_update(array("status"=>2), $this->t_receive_list, "WHERE `id` = '{$temp['fromId']}'");
			//系统操作日志
			app::loadApp('main', 'systemLogs')->api_addLogs('', 3207, '不通过', "审批档案{$info['title']}");
			msg::callBack(true, "您已经是最后一步了，流程已全部走完");
		
		}
	}
	
	/*
	private function _agree(){
		global $CNOA_DB, $CNOA_SESSION;
		$ids = getPar($_POST, "ids", 0);
		$ids = substr($ids, 0, -1);
		$uid = $CNOA_SESSION->get("UID");
		$idDB = $CNOA_DB->db_select("*", $this->t_step_list, "WHERE `fromId` IN ({$ids}) AND `status` = '1' AND `uid` = '{$uid}' ");
		$CNOA_DB->db_update(array("etime"=>$GLOBALS['CNOA_TIMESTAMP'], "status"=>2), $this->t_step_list, "WHERE `fromId` IN ({$ids}) AND `status` = '1' AND `uid` = '{$uid}' ");
		!is_array($idDB) && $idDB = array();
		foreach ($idDB as $k=>$v) {
			$id = $CNOA_DB->db_getfield("id",$this->t_step_list, "WHERE `fromId` = {$v['fromId']} AND `id` > '{$v['id']}' ORDER BY `id` ASC ");
			$update['fid'] = $v['id'];
			$update['stepname'] = $v['stepname'];
			if($id == ""){
				$update['status'] = 1;
			}else{
				$CNOA_DB->db_update(array("status"=>1, "stime"=>$GLOBALS['CNOA_TIMESTAMP']), $this->t_step_list, "WHERE `id` = '{$id}'");
			}
			$CNOA_DB->db_update($update, $this->t_borrow, "WHERE `id` = '{$v['fromId']}'");
		}
		msg::callBack(true, "操作成功");
	}
	
	private function _disagree(){
		global $CNOA_DB, $CNOA_SESSION;
		$ids = getPar($_POST, "ids", 0);
		$ids = substr($ids, 0, -1);
		$uid = $CNOA_SESSION->get("UID");
		$idDB = $CNOA_DB->db_select("*", $this->t_step_list, "WHERE `fromId` IN ({$ids}) AND `status` = '1' AND `uid` = '{$uid}' ");
		$CNOA_DB->db_update(array("etime"=>$GLOBALS['CNOA_TIMESTAMP'], "status"=>2), $this->t_step_list, "WHERE `fromId` IN ({$ids}) AND `status` = '1' AND `uid` = '{$uid}' ");
		!is_array($idDB) && $idDB = array();
		foreach ($idDB as $k=>$v) {
			$update['fid'] = $v['id'];
			$update['stepname'] = $v['stepname'];
			$update['status'] = 2;
			$CNOA_DB->db_update($update, $this->t_borrow, "WHERE `id` = '{$v['fromId']}'");
		}
		msg::callBack(true, "操作成功");
	}*/
}
?>