<?php
class odocCommon{
	private $t_send_list	= "odoc_send_list";
	private $t_tpl_list		= "odoc_setting_template_list";
	private $t_setting_word_level		= "odoc_setting_word_level";
	private $t_setting_word_hurry		= "odoc_setting_word_hurry";
	private $t_setting_word_secret		= "odoc_setting_word_secret";
	private $t_setting_word_type		= "odoc_setting_word_type";
	private $t_flow			= "odoc_setting_flow";
	private $t_flow_step	= "odoc_setting_flow_step";
	private $t_step			= "odoc_step";
	private $t_step_temp	= "odoc_step_temp";
									
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

	/**
	 * 从发文模板列表中获取发文单模板, 转化表单，转换发文模板到发文稿表单
	 * Enter description here ...
	 * @param unknown_type $tempid
	 */
	public function getFormFromTplById($tempid){
		global $CNOA_DB;
		
		$tplInfo = $CNOA_DB->db_getone("*", $this->t_tpl_list, "WHERE `id`='{$tempid}'");

		//去掉双引号及换行符号
		$html = str_replace(
			array("\r\n", "\n", "\""),
			array("", "", "'"),
			$tplInfo['fawenform']
		);
		
		//分析表单元素
		$elementList = array();

		preg_match_all('/((<((input))[^>]*>)|(<textarea[\s\S]+?><\/textarea>)|(<select[\s\S]+?>[\s\S]+?<\/select>))/i', $html, $arr);

		foreach ($arr[0] AS $v){
			$tmp = array ();
			$tmp['name']		= preg_replace ( "/(.*)name=[\"|']([^\"|']*)[\"|']\s?(.*)/is", "\\2", $v );
			$tmp['cname']		= preg_replace ( "/(.*)cname=[\"|']([^\"|']*)[\"|']\s?(.*)/is", "\\2", $v );
			$tmp['type']		= preg_replace ( "/(.*)type=[\"|']([^\"|']*)[\"|']\s?(.*)/is", "\\2", $v );
			$tmp['value']		= preg_replace ( "/(.*)value=[\"|']([^\"|']*)[\"|']\s?(.*)/is", "\\2", $v );
			$tmp['style']		= preg_replace ( "/(.*)style=[\"|']([^\"|']*)[\"|']\s?(.*)/is", "\\2", $v );
			$tmp['htmltag']		= strtolower ( substr ( $v, 1, strpos ( $v, " " ) - 1 ) );
			$tmp['html']		= $v;
			$elementList[]		= $tmp;
		}
		unset($v);
		
		#取出缓急程度数据
		$levelDb = $CNOA_DB->db_select("*", $this->t_setting_word_level, "WHERE 1 ORDER BY `order` ASC"); 
		!is_array($levelDb) && $levelDb = array();
		$levels = "";
		foreach($levelDb AS $v){
			$levels .= "<option value='{$v['tid']}'>{$v['title']}</option>";
		}
		unset($v,$levelDb);
		
		#取出密级数据
		$hurryDb = $CNOA_DB->db_select("*", $this->t_setting_word_hurry, "WHERE 1 ORDER BY `order` ASC");
		!is_array($hurryDb) && $hurryDb = array();
		$hurrys = "";
		foreach($hurryDb AS $v){
			$hurrys .= "<option value='{$v['tid']}'>{$v['title']}</option>";
		}
		unset($v, $hurryDb);
		
		#取出保密期限
		$secretDb = $CNOA_DB->db_select("*", $this->t_setting_word_secret, "WHERE 1 ORDER BY `order` ASC");
		!is_array($secretDb) && $secretDb = array();
		$secrets = "";
		foreach($secretDb AS $v){
			$secrets .= "<option value='{$v['tid']}'>{$v['title']}</option>";
		}
		unset($v, $secretDb);
		
		$odoc_form_id = 0;

		#重造发文单数据
		$elementList2 = array();
		foreach($elementList AS $v){
			if($v['type'] == 'odoc'){
				switch ($v['cname']){
					#单行文本框
					case "number":
					case "title":
					case "page":
					case "many":
					case "regdate":
					case "senddate":
					case "createname_send":
					case "createname_receive":
					case "receivedate":
						$tname = str_replace(array("{", "}"), "", $v['value']);

						$v['html2'] = "<!-- odoc_start_{$odoc_form_id} --><input type='text' name='id_{$odoc_form_id}__{$v['name']}' tname='{$tname}' style='{$v['style']}' ext:qtip='{$v['value']}' /><!-- odoc_end_{$odoc_form_id} -->";
						$elementList2[] = $v['html2'];
						$html = str_replace($v['html'], $v['html2'], $html);
						break;
					case "selectdate":
						// onClick='WdatePicker({dateFmt:\"yyyy年MM月dd日\"})'
						$tname = str_replace(array("{", "}"), "", $v['value']);

						$v['html2'] = "<!-- odoc_start_{$odoc_form_id} --><input onClick='WdatePicker({dateFmt:{CNOASHUANG}yyyy年MM月dd日{CNOASHUANG}})' type='text' name='id_{$odoc_form_id}__{$v['name']}' tname='{$tname}' style='{$v['style']}' ext:qtip='{$v['value']}' /><!-- odoc_end_{$odoc_form_id} -->";
						$elementList2[] = $v['html2'];
						$html = str_replace_once($v['html'], $v['html2'], $html);

						break;
					#多行文本框
					case "sign":
					case "createdept":
					case "range":
					case "fromdept":
					case "receivedept":
					case "createdept":
					case "summary":
						$tname = str_replace(array("{", "}"), "", $v['value']);
						
						$v['html2'] = "<!-- odoc_start_{$odoc_form_id} --><textarea name='id_{$odoc_form_id}__{$v['name']}' tname='{$tname}' ext:qtip='{$v['value']}' style='{$v['style']}' ></textarea><!-- odoc_end_{$odoc_form_id} -->";
						$elementList2[] = $v['html2'];
						$html = str_replace($v['html'], $v['html2'], $html);
						break;
					#下拉选择框
					case "level":
					case "hurry":
					case "secret":
						$tname = str_replace(array("{", "}"), "", $v['value']);
						
						if($v['cname'] == "level"){
							$level2 = $levels;
						}elseif($v['cname'] == "secret"){
							$level2 = $secrets;
						}else{
							$level2 = $hurrys;
						}
						
						$v['html2'] = "<!-- odoc_start_{$odoc_form_id} --><select name='id_{$odoc_form_id}__{$v['name']}' tname='{$tname}' ext:qtip='{$v['value']}' style='{$v['style']}'>{$level2}</select><!-- odoc_end_{$odoc_form_id} -->";
						
						$elementList2[] = $v['html2'];
						$html = str_replace($v['html'], $v['html2'], $html);
						break;
					#会签框
					case "huiqian":
						$tname = str_replace(array("{", "}"), "", $v['value']);
						
						$v['html2'] = "<!-- odoc_start_{$odoc_form_id} --><textarea name='id_{$odoc_form_id}__huiqian' id='id_{$odoc_form_id}__huiqian' tname='{$tname}' ext:qtip='{$v['value']}' style='{$v['style']}'></textarea>";
						$v['html2'] .= "<br><span style='color:#000'><label><input type='radio' name='id_radio_{$odoc_form_id}__huiqian' cname='已阅' />已阅</label>&nbsp;&nbsp;<label><input type='radio' name='id_radio_{$odoc_form_id}__huiqian' cname='同意' />同意</label>&nbsp;&nbsp;<label><input type='radio' name='id_radio_{$odoc_form_id}__huiqian' cname='不同意' />不同意</label></span><!-- odoc_end_{$odoc_form_id} -->";
						$elementList2[] = $v['html2'];
						$html = str_replace_once($v['html'], $v['html2'], $html);
						break;
				}
			}else{
				switch ($v['htmltag']){
					#单行文本框
					case "input":
					#多行文本框
					case "textarea":
					#下拉选择框
					case "select":
						$v['html2'] = str_replace("name='", "name='id_{$odoc_form_id}__", $v['html']);
						$v['html2'] = "<!-- odoc_start_{$odoc_form_id} -->{$v['html2']}<!-- odoc_end_{$odoc_form_id} -->";
						$elementList2[] = $v['html2'];
						$html = str_replace_once($v['html'], $v['html2'], $html);
						break;
				}
			}

			$odoc_form_id ++;
		}
		
		return $html;
	}
	
	public function getFormWithValue($form, $value){
		#处理值
		$values = array();
		foreach ($value AS $k=>$v){
			if(ereg("id_[0-9]{1,}__", $k)){
				$id = preg_replace("/id_([0-9]{1,})__.*/is", "\\1", $k);
				$values[$id] = $v;
			}
		}

		#提取表单元素
		preg_match_all('/((<((input))[^>]*>)|(<textarea[\s\S]+?><\/textarea>)|(<select[\s\S]+?>[\s\S]+?<\/select>))/i', $form, $arr);
		
		$elementList = array();
		foreach ($arr[0] AS $v){
			$tmp = array ();
			$tmp['name']		= preg_replace ( "/(.*)[^t]name=[\"|']([^\"|']*)[\"|']\s?(.*)/is", "\\2", $v );
			$tmp['cname']		= preg_replace ( "/(.*)cname=[\"|']([^\"|']*)[\"|']\s?(.*)/is", "\\2", $v );
			$tmp['type']		= preg_replace ( "/(.*)type=[\"|']([^\"|']*)[\"|']\s?(.*)/is", "\\2", $v );
			$tmp['htmltag']		= strtolower ( substr ( $v, 1, strpos ( $v, " " ) - 1 ) );
			$tmp['html']		= $v;
			$elementList[]		= $tmp;
		}
		
		foreach($elementList AS $v){
			#获取ID号
			$id = preg_replace("/id_([0-9]{1,})__.*/is", "\\1", $v['name']);
			
			#处理select
			if($v['htmltag'] == "select"){
				#初始化
				$v['html2'] = str_replace(" selected='selected'", "", $v['html']);
				#设置值
				$v['html2'] = str_replace(" value='{$values[$id]}'", " value='{$values[$id]}' selected='selected'", $v['html']);
				
				$form = str_replace($v['html'], $v['html2'], $form);
			}
			#处理textarea
			elseif($v['htmltag'] == "textarea"){
				$v['html2'] = str_replace("</textarea>", "{$values[$id]}</textarea>", $v['html']);
				
				$form = str_replace($v['html'], $v['html2'], $form);
			}
			#处理textfield
			elseif($v['htmltag'] == "input"){
				if($v['type'] == "text"){
					#初始化
					$v['html2'] = preg_replace("/(.*) value='[^']*'(.*)/is", "\\1\\2", $v['html']);
					$v['html2'] = str_replace("<input", "<input value='{$values[$id]}'", $v['html2']);
					
					$form = str_replace($v['html'], $v['html2'], $form);
				}
			}
		}
		
		return $form;
	}
	
	public function getHtmlWithValue($form, $value){
		global $CNOA_DB;
		#处理值
		foreach ($value AS $k=>$v){
			if(ereg("id_[0-9]{1,}__", $k)){
				$id = preg_replace("/id_([0-9]{1,})__.*/is", "\\1", $k);
				$tp = preg_replace("/id_([0-9]{1,})__(.*)/is", "\\2", $k);
				
				switch ($tp){
					case "level":
						$v = $CNOA_DB->db_getfield("title", $this->t_setting_word_level, "WHERE `tid`='{$v}' ");
						break;
					case "hurry":
						$v = $CNOA_DB->db_getfield("title", $this->t_setting_word_hurry, "WHERE `tid`='{$v}' ");
						break;
					case "secret":
						$v = $CNOA_DB->db_getfield("title", $this->t_setting_word_secret, "WHERE `tid`='{$v}' ");
						break;
				}
				
				empty($v) && $v="&nbsp;";
				
				$form = preg_replace("/(.*)<!-- odoc_start_{$id}.*odoc_end_{$id} -->(.*)/is", "\${1}{$v}\${2}", $form);
			}
		}

		return $form;
	}
	
	/*
	 *  保存发文单历史记录
	 */
	public function saveHistory($id, $html, $version=0, $type){
		if(!in_array($type, array("send", "receive"))){
			echo "wrong {$type}";
			exit;
		}
		
		$savePath = CNOA_PATH_FILE . "/common/odoc/{$type}/{$id}/form.history.{$version}.php";
		mkdirs(dirname($savePath));
		
		$html = "<?php\r\n" . "return \"" . $html . "\";";
		
		swritefile($savePath, $html);
	}
	
	public function addEvent(){
		global $CNOA_DB;
		
		$data = array();
		$data['type']		= 1;
		$data['fromid']		= 1;
		$data['title']		= 1;
		$data['text']		= 1;
		$data['version']	= 1;
		$data['posttime']	= 1;
		$data['uid']		= 1;
		$data['uname']		= 1;		
	}

	public function getTypeList(){
		global $CNOA_DB;
		$dblist = $CNOA_DB->db_select(array("tid", "title"), $this->t_setting_word_type, "ORDER BY `order` ASC ");
		!is_array($dblist) && $dblist = array();
		$dataStore = new dataStore();
		$dataStore->data = $dblist;
		echo $dataStore->makeJsonData();
		exit();
	}
	
	public function getLevelList(){
		global $CNOA_DB;
		$dblist = $CNOA_DB->db_select(array("tid", "title"), $this->t_setting_word_level, "ORDER BY `order` ASC ");
		!is_array($dblist) && $dblist = array();
		$dataStore = new dataStore();
		$dataStore->data = $dblist;
		echo $dataStore->makeJsonData();
		exit();
	}

	public function getTypeAllArr(){
		global $CNOA_DB;
		$dblist = $CNOA_DB->db_select(array("tid", "title"), $this->t_setting_word_type, "ORDER BY `order` ASC ");
		!is_array($dblist) && $dblist = array();
		$data = array(0=>array("title"=>""));
		foreach ($dblist as $k=>$v) {
			$data[$v['tid']] = $v;
		}
		return $data;
	}

	public function getLevelAllArr(){
		global $CNOA_DB;
		$dblist = $CNOA_DB->db_select(array("tid", "title"), $this->t_setting_word_level, "ORDER BY `order` ASC ");
		!is_array($dblist) && $dblist = array();
		$data = array(0=>array("title"=>""));
		foreach ($dblist as $k=>$v) {
			$data[$v['tid']] = $v;
		}
		return $data;
	}
	
	public function getSecretAllArr(){
		global $CNOA_DB;
		$dblist = $CNOA_DB->db_select(array("tid", "title"), $this->t_setting_word_secret, "ORDER BY `order` ASC ");
		!is_array($dblist) && $dblist = array();
		$data = array(0=>array("title"=>""));
		foreach ($dblist as $k=>$v) {
			$data[$v['tid']] = $v;
		}
		return $data;
	}
	
	public function getHurryAllArr(){
		global $CNOA_DB;
		$dblist = $CNOA_DB->db_select(array("tid", "title"), $this->t_setting_word_hurry, "ORDER BY `order` ASC ");
		!is_array($dblist) && $dblist = array();
		$data = array(0=>array("title"=>""));
		foreach ($dblist as $k=>$v) {
			$data[$v['tid']] = $v;
		}
		return $data;
	}
}