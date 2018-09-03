<?php 
/**
 * cnoa framework
 *
 * @package		cnoa
 * @author		cnoa Dev Team & Linxiaoqing
 * @email		linxiaoqing@live.com
 * @copyright	Copyright (c) 2011, cnoa, Inc.
 * @license		http://cnoa.com/user_guide/license.html
 * @since		Version 1.4.0
 * @filesource
 */
class mainSystemLogs extends model{
    private $table		= 'system_logs';
    //private $table_user	= 'main_user';
    					
    public function run(){
        global $CNOA_SESSION;
        
        $task = getPar($_GET, 'task', '');
        
        switch($task){
        	case 'loadPage':        	    
            	$this->_loadPage();
            	break;
        	case 'getListJsonData':
        	    $this->_getListJsonData();
        	    break;
        	case 'getAllUserListsInPermitDeptTree':
        		$this->_getAllUserListsInPermitDeptTree();
        		break;
        	case 'getFormDate':
        	    //获取来源信息
        	    $this->_getFormDate();
        	    break;
        	//清空日志
        	case 'emptyLogs':
        	    $this->_emptyLogs();
        	    break;
        }
    }
    
    /**
     * 加载模板
     */
    private function _loadPage(){
        global $CNOA_SESSION, $CNOA_CONTROLLER;
        
        $tplPath = $CNOA_CONTROLLER->appPath . '/tpl/default/system/logs.htm';
        
        $CNOA_CONTROLLER->loadExtraTpl($tplPath);
        exit;
    }
    
    /**
     * 获取日志列表
     */
    private function _getListJsonData(){
        global $CNOA_DB, $CNOA_SESSION;
        
        $start = getPar($_POST, 'start', 0);
        
        $limit  = getPageSize("main_system_logs_getListJsonData");
        
        $type		= getPar($_POST, 'type', '');
        $uid		= getPar($_POST, 'uid', '');
        $stime		= getPar($_POST, 'start_time', 0);
        $etime		= getPar($_POST, 'end_time', 0);
        $from		= getPar($_POST, 'from', '');
        $ip			= getPar($_POST, 'ip', '');

        $where = 'WHERE 1 ';
        if(!empty($type)){ 
            $where .= "AND `type`='{$type}' ";
        }
        if(!empty($uid)){
            $where .= "AND `uid`='{$uid}' ";
        }
        if(!empty($stime)){
            $stime = strtotime($stime." 00:00:00");
            $where .= "AND `posttime` > '{$stime}' ";
        }
        if(!empty($etime)){
            $etime = strtotime($etime." 23:59:59");
            $where .= "AND `posttime` < '{$etime}' ";
        }
        if(!empty($from)){
            $where .= "AND `from`='{$from}' ";
        }
        if(!empty($ip)){
            $where .= "AND `ip` LIKE '%{$ip}%' ";
        }
        
        $order = "ORDER BY posttime DESC ";
        
        $list = $CNOA_DB->db_select("*", $this->table, $where . $order ."LIMIT {$start}, {$limit}"); 
        !is_array($list) && $list = array();
       
       //获取来源
       include(CNOA_PATH . "/core/inc/systemLogs.conf.php");
       $fromName = $GLOBALS['CNOA_SYSTEM_LOGS_FROM'];

        foreach ($list as $k=>$v){
            
            //格式化类型
            switch ($v['type']){
            	case '1' :
            	    $list[$k]['type'] = '<span class="cnoa_color_green">' . lang('add') . '</span>';
            	    break;
            	case '2' :
            	    $list[$k]['type'] = '<span class="cnoa_color_red">' . lang('del') . '</span>';
            	    break;
            	case '3' :
            	    $list[$k]['type'] = '<span class="cnoa_color_blue">' . lang('modify') . '</span>';
            	    break;
            	case '4' :
            	    $list[$k]['type'] = lang('login');
            	    break;
              case '5':
                  $list[$k]['type'] = '<span class="cnoa_color_orange">' . lang('export2') . '</span>';
                  break;
            	default:
					$list[$k]['type'] = '';
					break;	
            }
            
            /*格式化内容 start*/
            $type = substr($v['content'], 0, 1);
            $v['content'] = substr($v['content'], 2);
            
            //判断内容的类型
            if($type == 's'){
                if(empty($v['extra'])){
                    $v['content'] = "{$list[$k]['type']}&nbsp{$v['content']}&nbsp";
                }else{
                	$v['content'] = "{$list[$k]['type']}{$v['extra']}&nbsp[&nbsp{$v['content']}&nbsp]"; 
                } 
            }else if($type == 'a'){
                $v['content'] = json_decode($v['content'], true);
				$content = '';
                if(empty($v['extra'])){
                    foreach ($v['content'] as $key=>$val){
                        $content .= "{$list[$k]['type']}&nbsp{$key} -> {$val}&nbsp";
                    }
                }else{
	                foreach ($v['content'] as $key=>$val){
	                    $content .= "{$list[$k]['type']}{$v['extra']}&nbsp[&nbsp{$key} -> {$val}&nbsp]";
	                }	
                }
				$v['content'] = $content;
            }
            
            /*格式化内容 end*/
            
            $list[$k]['content']	= $v['content'];
            $list[$k]['posttime']	= date("Y-m-d H:i:s", $v['posttime']);
            $list[$k]['uid']		= $userName[$v['uid']]['truename'];
            $list[$k]['from']		= $fromName[$v['from']]['name'];
        }
        $dataStore = new dataStore();
        $dataStore->total = $CNOA_DB->db_getcount($this->table, $where);
        $dataStore->data = $list;
        echo $dataStore->makeJsonData();
        exit;
    }
    
    /**
     * 获取操作人列表
     */
    private function _getAllUserListsInPermitDeptTree(){
    	$GLOBALS['user']['permitArea']['area'] = "all";
        $userList = app::loadApp('main', 'user')->api_getAllUserListsInPermitDeptTree();
        
        echo json_encode($userList); 
        exit;
    }
    
    /**
     * 获取数据源
     */
  	private function _getFormDate(){
  	    include(CNOA_PATH . "/core/inc/systemLogs.conf.php");
  	    $fromNames = array_merge($GLOBALS['CNOA_SYSTEM_LOGS_FROM']);
  	    
  	    echo json_encode($fromNames);
  	    exit;
  	}
  	
  	/**
  	 * 记录用户的操作日志
  	 * 
  	 * @param unknown_type $type		操作类型	 1：add, 2:del, 3:update, 4:login, 5:export
  	 * @param unknown_type $from		操作来源		
  	 * @param unknown_type $content		操作的内容	如果是数组array('旧值'=>'新值')
  	 * @param unknown_type $extra		被操作的对象
  	 */
  	public function api_addLogs($type, $from, $content=NULL, $extra=''){
  	    global $CNOA_DB, $CNOA_SESSION;

  	    $data['uid']		= $CNOA_SESSION->get('UID');
  	    $data['posttime']	= $GLOBALS['CNOA_TIMESTAMP'];
  	    $data['ip']			= getip();
  	    $data['truename']	= $CNOA_SESSION->get('TRUENAME');
  	    $data['username']	= $CNOA_SESSION->get('USERNAME');
  	    $data['extra']		= $extra;	    
  	    
  	    //判断操作类型
  	    if($type == 'add'){
  	        $data['type'] = 1;  
  	    }else if($type == 'del'){
  	        $data['type'] = 2;
  	    }else if($type == 'update'){
  	        $data['type'] = 3;
  	    }else if($type == 'login'){
  	        $data['type'] = 4;
  	    }else if($type == 'export'){
            $data['type'] = 5;
        }else{
  	        $data['type'] = 0;
  	    }
  	    
  	    //判断来源
  	    include(CNOA_PATH . "/core/inc/systemLogs.conf.php");
  	    $fromNames = $GLOBALS['CNOA_SYSTEM_LOGS_FROM'];
  	    $data['from']		= $from;
  	    
  	    //格式化内容
  	    if(is_array($content)){
  	        $content = "a|" . addslashes(json_encode($content));
  	    }else if(is_string($content)){
  	        $content = "s|" . $content;
  	    }else if($content == NULL){
  	        $content = "s|" . $fromNames[$from]['name'];
  	    }
  	    
  	    
  	    $data['content']	= $content;

  	    $CNOA_DB->db_insert($data, $this->table);
  	    
  	}
  	
  	/**
  	 * 清空系统日志
  	 */
  	private function _emptyLogs(){
  	    global $CNOA_DB, $CNOA_SESSION;
  	    
  	    $uid = $CNOA_SESSION->get('UID');
  	    
  	    $stime = getPar($_POST, 'stime');
  	    $etime = getPar($_POST, 'etime');
  	    

  	    if(empty($stime) && empty($etime)){
  	    	msg::callBack(false, lang('stimeEtimeNotEmpty'));
  	    }
  	    
  	    $stime = strtotime($stime . ' 00:00:00');
  	    $etime = strtotime($etime . ' 23:59:59');
  	    
  	    if($etime - $stime < 0){
  	        msg::callBack(false, lang('endTimeNoBigStartTime'));
  	    }
  	    
  	    $where = "WHERE `posttime` >= {$stime} AND `posttime` <= {$etime}";
  	    
  	    $CNOA_DB->db_delete($this->table, $where);
  	    //系统操作日志
  	    app::loadApp('main', 'systemLogs')->api_addLogs('', 163, date('Y-m-d',$stime)."--".date('Y-m-d',$etime), lang('emptySystemLog'));
  	    
  	    msg::callBack(true, lang('successopt'));
  	    
  	}
}
?>