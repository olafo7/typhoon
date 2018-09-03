<?php
/**
 * @date: 2013-4-16
 * @author: 黄邦龙
 */
class jxcCommon extends model{
	
	//自定义标示符
	const CUSTOM_FIELD_CODE = 'jxc';
	
	//自定义模块
	const MODULE_GOODS		= 1;
	const MODULE_STORAGE	= 2;
	const MODULE_RUKU		= 3;
	const MODULE_CHUKU		= 4;
	
	protected $table_storage		= 'jxc_storage';
	protected $talbe_stock			= 'jxc_stock';
	protected $table_ruku 			= 'jxc_stock_ruku';
	protected $table_chuku			= 'jxc_stock_chuku';
	protected $table_goods_detail	= 'jxc_stock_goods_detail';
	protected $table_goods			= 'jxc_goods';
	
	/**
	 * 获取自定义字段
	 * @date: 2013-4-23
	 * @author: 黄邦龙
	 */
	protected function getCustomField($mid){
		global $CNOA_DB;
	
		//获取自定义字段
		$cf = new customField(self::CUSTOM_FIELD_CODE);
		$fields = $cf->getAllFieldsByMid($mid);
	
		echo json_encode($fields);
	}
	
	/**
	 * 获取下拉框的选项
	 * @date: 2013-5-3
	 * @author: 黄邦龙
	 */
	protected function getComboStore(jxcCommon $jxc){
		$key = getPar($_POST, 'key');
		$cf = new customField(self::CUSTOM_FIELD_CODE);
		//判断是否为自定义字段
		$store = $cf->getComboStore($jxc->modelId, $key);
		if($store === false){
			$function = 'get'.ucfirst($key);
			if(method_exists($jxc, $function)){
				$store = $jxc->$function($key);
			}
		}
		$ds = new dataStore();
		$ds->data = is_array($store) ? $store : false;
		echo $ds->makeJsonData();
	}
	
	/**
	 * 获取仓库
	 * @date: 2013-5-10
	 * @author: 黄邦龙
	 */
	protected function getStoragename(){
		$storage = app::loadApp('jxc', 'base')->api_getStorage();
		!is_array($storage) && $storage=array();
		$data = array();
		foreach($storage as $v){
			$data[] = array(
					'storageid'=>$v['id'],
					'storagename'=>$v['storagename']
			);
		}
		return $data;
	}
	
	/**
	 * 获取商品分类类型
	 * @date: 2013-5-2
	 * @author: 黄邦龙
	 */
	protected function getSortName(){
		$sorts = app::loadApp('jxc', 'base')->api_getsorts();
		!is_array($sorts) && $sorts=array();
		$data = array();
		foreach($sorts as $v){
			$data[] = array(
					'sid'=>$v['id'],
					'sortName'=>$v['name']
			);
		}
		return $data;
	}
	
	/**
	 * 获取入库类型
	 * @date 2013-10-29
	 */
	protected function getRukuTypeName(){
		global $CNOA_DB;
		$sql = 'SELECT s.id AS type, s.name AS rukuTypeName '.
				'FROM '.tname('jxc_custom_fix_field').' AS f '.
				'RIGHT JOIN '.tname('jxc_custom_select_item'). ' AS s ON s.fieldId=f.fieldId '.
				"WHERE f.displayfield='rukuTypeName' AND s.type=1 AND f.mid=".self::MODULE_RUKU;
		$result = $CNOA_DB->query($sql);
		
		$data = array();
		while($row = $CNOA_DB->get_array($result)){
			$data[] = $row;
		}
		return $data;
	}
	
	/**
	 * 获取出库类型
	 * @date 2013-10-29
	 */
	protected function getChukuTypeName(){
		global $CNOA_DB;
		$sql = 'SELECT s.id AS type, s.name AS chukuTypeName '.
				'FROM '.tname('jxc_custom_fix_field').' AS f '.
				'RIGHT JOIN '.tname('jxc_custom_select_item'). ' AS s ON s.fieldId=f.fieldId '.
				"WHERE f.displayfield='chukuTypeName' AND s.type=1 AND f.mid=".self::MODULE_CHUKU;
		$result = $CNOA_DB->query($sql);
		
		$data = array();
		while($row = $CNOA_DB->get_array($result)){
			$data[] = $row;
		}
		
		return $data;
	}
	
	/**
	 * 获取货品数量
	 *
	 * @date: 2013-5-9
	 * @author: 黄邦龙
	 */
	public function api_getGoodsQuantity(){
		$quantity = array();
	
		$sql = 'SELECT `goodsId`, sum(`quantity`) AS `quantity` '.
				'FROM '.tname($this->table_goods_detail).
				"WHERE 1 GROUP BY `storageId`, `goodsId`";
		global $CNOA_DB;
		$result = $CNOA_DB->query($sql);
		while ($row = $CNOA_DB->get_array($result)){
			$quantity[$row['goodsId']] = intval($row['quantity']);
		}
	
		return $quantity;
	}
}
?>