<?php

include CNOA_PATH . '/app/user/source/userCustomers.class.php';

class userCustomersM {
    
    //数据库表
    public $table_user          = 'main_user';
    public $table_customers     = 'user_customers';             //客户信息
    public $table_linkman       = 'user_customers_linkman';     //联系人
    public $table_postsales     = 'user_customers_postsales';   //售后回访
    public $table_presales      = 'user_customers_presales';    //售前跟踪表
    public $table_record        = 'user_customers_record';      //成交记录表
    
    public $table_idcf          = 'user_customers_idcf';        //行业分类
    public $table_sort          = 'user_customers_sort';        //客户类别表
    public $table_follow        = 'user_customers_follow';      //跟踪方式
    public $table_transport     = 'user_customers_transport';   //运输方式
    public $table_degree        = 'user_customers_degree';      //客户程度
    public $table_region        = 'user_customers_region';      //地域表
    public $table_region2       = 'user_customers_region2';     //地区信息表
    
    public $table_comment       = 'user_customers_comment';     //上级评阅
    public $table_flow          = 'user_customers_flow';        //客户绑定流程表
    public $table_agent_data    = 'user_customers_agent_data';  //渠道信息
    public $table_share         = 'user_customers_share';       //客户共享表
    public $table_share_record  = 'user_customers_share_record';//共享客户记录
    public $table_share_update_record = 'user_customers_share_update_record';//共享修改记录
    public $table_degree_record = 'user_customers_degree_record';   //改变客户程度记录表
    public $table_setting       = 'user_customers_setting';     //客户设置表
    public $table_s_condition   = 'user_customers_search_condition';    //查询模版表
    
    //列表显示行数
    public $rows = 15;
    
    private $_taskMap = array(
        'addCustomer' => 'editCustomerInfo',
        'addLinkman' => 'editLinkman',
        'addFollow' => 'addPresales',
        'searchCustomer' => 'getCustomerCollect'
    );

    //我的客户
    public function actionIndex(){
        $task = getPar($_GET, 'task');
        
        $this->_setTaskValue($task);

        switch ($task) {
            case 'addCustomer':
            case 'deleteCustomer':
            case 'moveCustomer':
            case 'releaseCustomer':
            case 'addLinkman':
            case 'editLinkman':
            case 'searchCustomer':
            case 'taskCustomer':
            case 'signIn':
            case 'signOut':
            case 'getSignCustomers':
                $controller = new userCustomers();
                $controller->actionIndex();
                break;

            case 'addFollow':
                // 附件
                $attach = getPar($_POST, 'attach', array());
                if (is_array($attach)) {
                    foreach ($attach as $key => $value) {
                        $value = UploadHelper::decode($value);
                        $attach[$key] = UploadHelper::pack($value);
                    }
                }
                $_POST['filesUpload'] = $attach;
                unset($_POST['attach']);

                $time = strtotime(getPar($_POST, 'time', date('Y-m-d H:i')));
                $_POST['time_d'] = date('Y-m-d', $time);
                $_POST['time_t'] = date('H:i', $time);

                if (isset($_POST['nexttime'])) {
                    $nexttime = strtotime(getPar($_POST, 'nexttime'));
                    $_POST['nexttime_d'] = date('Y-m-d', $nexttime);
                    $_POST['nexttime_t'] = date('H:i', $nexttime);
                }

                $controller = new userCustomers();
                $controller->actionIndex();
                break;

            case 'getMyCustomers':
            case 'getCustomerDetail':
            case 'updateCustomer':
            case 'getFollowInfo':
            case 'getCustomerLinkman':
            case 'getNearbyCustomer':

            case 'getCustomerSort':
            case 'getFollowType':
            case 'getDegree':
                $method = "_{$task}";
                if (method_exists($this, $method)) {
                    return $this->$method();
                }

            default:
                $response = new stdClass();
                $response->error = true;
                $response->msg = 'There is not this api';
                return $response;
        }
    }

    /**
     * 获取我的客户
     * @return stdClass
     */
    private function _getMyCustomers() {
        global $CNOA_DB, $CNOA_SESSION;

        $uid = $CNOA_SESSION->get('UID');

        $start = (int) getPar($_POST, 'start');
        $limit = (int) getPar($_POST, 'limit', 15);
        $search = getPar($_POST, 'search');

        $where[] = "flowmanuid={$uid}";
        $where[] = 'status=' . userCustomers::CUSTOMER_STATUS_NORMAL;
        if (! empty($search)) {
            $where[] = "name LIKE '%{$search}%'";
        }
        $where = implode(' AND ', $where);

        $data = $CNOA_DB->db_select(array('cid', 'name'), $this->table_customers, "WHERE {$where} ORDER BY posttime DESC LIMIT {$start}, {$limit}");

        $ds = new stdClass();
        $ds->success = true;
        $ds->total = (int) $CNOA_DB->db_getcount($this->table_customers, 'WHERE ' . $where);
        $ds->data = is_array($data) ? $data : array();
        return $ds;
    }

    /**
     * 获取客户详情
     * @return stdClass
     */
    private function _getCustomerDetail() {
        $cid = (int) getPar($_POST, 'cid');

        $response = new stdClass();
        $response->success = false;

        if ($cid === 0) {
            $response->msg = '该客户不存在';
            return $response;
        }

        global $CNOA_DB;
        
        $sql = 'SELECT c.cid, c.flowmanuid, c.name, c.address, c.sid, c.degreeId, s.name AS sort, d.name AS degree ' .
                'FROM '. tname($this->table_customers) . ' AS c ' .
                'LEFT JOIN ' . tname($this->table_sort) . ' AS s ON s.id=c.sid ' .
                'LEFT JOIN ' . tname($this->table_degree) . ' AS d ON d.id=c.degreeId ' .
                "WHERE cid={$cid} LIMIT 1";

        $customer = $CNOA_DB->get_one($sql);
        if (! $customer) {
            $response->msg = '该客户不存在';
            return $response;
        }

        $linkman = $CNOA_DB->db_select(array('lid', 'name', 'mobile', 'qq', 'email'), $this->table_linkman, "WHERE cid={$cid}");
        $followInfo = $CNOA_DB->db_select(array('pid', 'content', 'posttime', 'attach'), $this->table_presales, "WHERE cid={$cid}");
        if (! is_array($followInfo)) $followInfo = array();
        foreach ($followInfo as $key => $value) {
            $value['attach'] = count(json_decode($value['attach'], true)) > 0 ? true : false;
            $followInfo[$key] = $value;
        }

        $customer['sort'] = is_null($customer['sort']) ? '未分类' : $customer['sort'];
        $customer['degree'] = is_null($customer['degree']) ? '未分类' : $customer['degree'];
        $customer['linkman'] = is_array($linkman) ? $linkman : array();
        $customer['followInfo'] = $followInfo;
        $customer['address'] = $customer['address'];
        $customer['flowmanuid'] = (int) $customer['flowmanuid'];

        $response->success = true;
        $response->data = $customer;

        return $response;
    }

    /**
     * 修改客户
     * @return stdClass
     */
    private function _updateCustomer() {
        $cid = (int) getPar($_POST, 'cid');

        if ($cid === 0) {
            $response = new stdClass();
            $response->failure = true;
            $response->msg = '请选择需要进行修改的客户';
            return $response;
        }

        $_GET['task'] = 'editCustomerInfo';
        app::loadApp('user', 'customersIndex')->run();
    }

    /**
     * 获取跟进信息
     * @return stdClass
     */
    private function _getFollowInfo() {
        global $CNOA_DB;

        $pid = (int) getPar($_POST, 'pid');

        $sql = 'SELECT p.pid, p.time, p.nexttime, p.content, p.attach, f.name AS followType, l.name AS linkman'
                . ' FROM ' . tname($this->table_presales) . ' AS p'
                . ' LEFT JOIN ' . tname($this->table_follow) . ' AS f ON f.id=p.type'
                . ' LEFT JOIN ' . tname($this->table_linkman) . ' AS l ON l.lid=p.linkmanid'
                . " WHERE p.pid = {$pid} LIMIT 1";
        $data = $CNOA_DB->get_one($sql);

        if (! is_array($data)) {
            $response = new stdClass();
            $response->failure = true;
            $response->msg = '没有此跟进记录';

            return $response;
        }
        
        // 附件
        $aids = json_decode($data['attach'], true);
        $attach = array();

        $fs = new fs();
        foreach ($aids as $aid) {
            $info = $fs->getFileInfoById($aid);
            $attach[] = $info['attachInfo'];
        }

        $data['attach'] = $attach;

        $response = new stdClass();
        $response->success = true;
        $response->data = $data;

        return $response;
    }

    /**
     * 获取客户联系人
     * @return stdClass
     */
    private function _getCustomerLinkman() {
        $cid = (int) getPar($_POST, 'cid');

        global $CNOA_DB;
        
        $data = array();
        $linkmans = $CNOA_DB->db_select(array('lid', 'name'), $this->table_linkman, "WHERE cid={$cid} ORDER BY `lid` DESC");
        if (is_array($linkmans)) {
            foreach($linkmans as $linkman){
                $data[] = array('id'=>$linkman['lid'], 'name'=>$linkman['name']);
            }
        }

        $response = new stdClass();
        $response->success = true;
        $response->data = $data;

        return $response;
    }

    /**
     * 获取附近客户
     * @return stdClass
     */
    private function _getNearbyCustomer() {
        global $CNOA_DB;

        $longitude = (double) getPar($_POST, 'longitude');
        $latitude = (double) getPar($_POST, 'latitude');
        $radius = (double) getPar($_POST, 'radius');

        $lonLat = LonLatUtil::getAround($longitude, $latitude, $radius);

        $where[] = "flowmanuid != 0";
        $where[] = "longitude >= {$lonLat[0]} AND longitude <= {$lonLat[2]}";
        $where[] = "latitude >= {$lonLat[1]} AND latitude <= {$lonLat[3]}";
        $where = implode(' AND ', $where);

        $customers = $CNOA_DB->db_select(array('cid', 'name', 'longitude', 'latitude'), $this->table_customers, "WHERE {$where}");

        $data = array();
        if (is_array($customers)) {
            foreach ($customers as $key => $customer) {
                $distance = LonLatUtil::getDistance($longitude, $latitude, $customer['longitude'], $customer['latitude']);

                if ($distance > $radius) {
                    continue;
                }

                $customer['longitude'] = $customer['longitude'];
                $customer['latitude'] = $customer['latitude'];
                $customer['distance'] = $distance;

                $data[] = $customer;
            }
        }

        $ds = new stdClass();
        $ds->success = true;
        $ds->data = $data;
        return $ds;
    }

    /**
     * 获取客户类别
     * @return stdClass
     */
    private function _getCustomerSort() {
        global $CNOA_DB;

        $data[] = array('sid'=>0, 'sortName'=>'未分类');

        $sorts = $CNOA_DB->db_select(array('id', 'name'), $this->table_sort, 'ORDER BY `order`');
        if (is_array($sorts)) {
            foreach($sorts as $sort){
                $data[] = array('sid'=>$sort['id'], 'sortName'=>$sort['name']);
            }
        }

        $response = new stdClass();
        $response->success = true;
        $response->data = $data;

        return $response;
    }

    /**
     * 获取跟踪方式
     * @return stdClass
     */
    private function _getFollowType() {
        global $CNOA_DB;

        $data = $CNOA_DB->db_select(array('id', 'name'), $this->table_follow, 'ORDER BY `order` ASC');

        $response = new stdClass();
        $response->success = true;
        $response->data = is_array($data) ? $data : array();

        return $response;
    }

    /**
     * 获取客户程度
     * @return stdClass
     */
    private function _getDegree() {
        global $CNOA_DB;

        $data = $CNOA_DB->db_select(array('id', 'name'), $this->table_degree, 'ORDER BY `order` ASC');

        $response = new stdClass();
        $response->success = true;
        $response->data = is_array($data) ? $data : array();

        return $response;
    }

    private function _setTaskValue($task) {
        if (array_key_exists($task, $this->_taskMap)) {
            $_GET['task'] = $this->_taskMap[$task];
        }
    }

}