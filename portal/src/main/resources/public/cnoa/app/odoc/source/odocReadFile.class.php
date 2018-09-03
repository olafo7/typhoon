<?php 
class odocReadFile extends model{
	private $t_borrow	= "odoc_files_borrow_list";
	
	private $t_files_dangan			= "odoc_files_dangan";
	private $t_odoc_data			= "odoc_data";
	private $t_read_file= "odoc_read_file";
	
	private $f_from		= array(1=>"发文", 2=>"收文", 3=>"档案");
	
	private $rows		= 15;
									
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
			case "loadpage":
				$this->_loadpage();
				break;
			case "getJsonData" :
				$this->_getJsonData();
				break;
			#查看
			case 'view':
				
				$this->_viewFile();
				break;
		}
	}
	
	private function _viewFile(){
		global $CNOA_DB, $CNOA_SESSION;
		
		$cuid	= $CNOA_SESSION->get('UID');
		
					
		$id = getPar($_GET, 'id');
		if (empty($id)){
			$id = getPar($_POST, 'id');
		}
		
		//如果未曾阅读，则设置readed = 1
		$where	= "WHERE 1";
		$where	.= " AND `receiveuid`={$cuid} AND `type`=3 AND `fileid`={$id}";
		
		
		//如果没有阅读过
		//$readed = $CNOA_DB->db_getfield('readed', $this->t_read_file, $where);
		//if(intval($readed) == 0){
			//记录谁已阅读过
			$data = array();
			$data['readed']		= 1;
			$data['readtime']	= $GLOBALS['CNOA_TIMESTAMP'];
			$CNOA_DB->db_update($data, $this->t_read_file, $where);
		//}
		 
		
		app::loadApp("odoc", "commonFilesView")->run();
	}
	
	private function _loadpage(){
		global $CNOA_CONTROLLER,$CNOA_DB;
		$from = getPar($_GET, "from", "");
		
		switch($from){
			case 'viewflow'	:
				//更新为已读
				$id = getPar($_GET, 'id', 0);
				$CNOA_DB->db_update(array('readed'=>1), $this->t_borrow, "WHERE `id` = {$id} ");
				
				$fileid = getPar($_GET, "fileid", 0);
				
				$fromid = $CNOA_DB->db_getfield('fromid', $this->t_files_dangan, "WHERE `id` = {$fileid} ");
				$GLOBALS['app']['uFlowId'] = $CNOA_DB->db_getfield('uFlowId', $this->t_odoc_data, "WHERE `id` = {$fromid} ");
				//查看流程
				$GLOBALS['app']['step']				= getPar($_GET, "step", 0);
		
				$GLOBALS['app']['wf']['type']		= "done";//已办流程， 用于查看页面显示召回和撤销按钮
				$DB									= $CNOA_DB->db_getone(array("status", "flowId"), "wf_u_flow", "WHERE `uFlowId`='{$GLOBALS['app']['uFlowId']}'");
				$GLOBALS['app']['flowId']			= $DB['flowId'];
				$flow								= $CNOA_DB->db_getone(array("tplSort", "flowType"), "wf_s_flow", "WHERE `flowId` = {$DB['flowId']} ");
				$GLOBALS['app']['wf']['allowCallback']	= 0;
				$GLOBALS['app']['wf']['allowCancel']	= 0;
				$GLOBALS['app']['allowPrint']			='false';
				$GLOBALS['app']['allowFenfa']			='false';
				$GLOBALS['app']['allowExport']			='false';
				$GLOBALS['app']['status']				= 1;
				$GLOBALS['app']['wf']['tplSort']		= $flow["tplSort"];
				$GLOBALS['app']['wf']['flowType']		= $flow["flowType"];
				$GLOBALS['app']['wf']['owner']			= 0;//getpar($_GET, "owner", 0);
				$tplPath = CNOA_PATH . '/app/wf/tpl/default/flow/use/showflow.htm';
				$CNOA_CONTROLLER->loadExtraTpl($tplPath);exit;
		}
	}
	
	private function _getJsonData(){
		global $CNOA_DB, $CNOA_SESSION;
		$uid = $CNOA_SESSION->get("UID");
		$start = getPar($_POST, "start", 0);
		$WHERE = "WHERE 1 ";
		$storeType = getPar($_POST, "storeType", "waiting");
		if($storeType == "waiting"){
			$WHERE .= "AND `readed` = '0' ";
		}elseif ($storeType == "readed"){
			$WHERE .= "AND `readed` = '1' ";
		}
		
		$dblist = $CNOA_DB->db_select(array("stime", "etime", "fileid", "id", "type"), $this->t_borrow, $WHERE . "AND `postuid` = '{$uid}' AND `stime` < '{$GLOBALS['CNOA_TIMESTAMP']}' AND `etime` > '{$GLOBALS['CNOA_TIMESTAMP']}' ORDER BY `id` DESC LIMIT {$start}, {$this->rows} ");
		!is_array($dblist) && $dblist = array();
		$filesArr = array(0);
		foreach ($dblist as $k=>$v) {
			$filesArr[]		= $v['fileid'];
		}
		$roomArr		= app::loadApp("odoc", "filesSetting")->api_getRoomData();
		$typeArr		= app::loadApp("odoc", "filesSetting")->api_getTypeData();
		$wenzhongArr	= app::loadApp("odoc", "filesSetting")->api_getWenzhongData();
		$anjuanArr		= app::loadApp("odoc", "filesAnjuanmgr")->api_anjuanArr();
		$levelArr		= app::loadApp("odoc", "common")->getLevelAllArr();
		$danganArr = app::loadApp("odoc", "filesDananmgr")->api_getAllDanganArr($filesArr);
		foreach ($dblist as $k=>$v) {
			$dblist[$k]['type']			= $this->f_from[$v['type']];
			$dblist[$k]['danganshi']	= $roomArr[$danganArr[$v['fileid']]['danganshi']]['title'];
			$dblist[$k]['type1']		= $typeArr[$danganArr[$v['fileid']]['type']]['title'];
			$dblist[$k]['anjuan']		= $anjuanArr[$danganArr[$v['fileid']]['anjuan']]['title'];
			$dblist[$k]['wenzhong']		= $wenzhongArr[$danganArr[$v['fileid']]['wenzhong']]['title'];
			$dblist[$k]['level']		= $levelArr[$danganArr[$v['fileid']]['level']]['title'];
			$dblist[$k]['title']		= $danganArr[$v['fileid']]['title'];
			$dblist[$k]['stime']		= formatDate($v['stime']);
			$dblist[$k]['etime']		= formatDate($v['etime']);
		}
		
		$dataStore = new dataStore();
		$dataStore->data = $dblist;
		//$data->total = $CNOA_DB->db_getcount($this->t_borrow, $WHERE . "AND `status` = '1' AND `postuid` = '{$uid}' AND `stime` < '{$GLOBALS['CNOA_TIMESTAMP']}' AND `etime` > '{$GLOBALS['CNOA_TIMESTAMP']}' ");
		echo $dataStore->makeJsonData();
		exit();
	}
}
?>