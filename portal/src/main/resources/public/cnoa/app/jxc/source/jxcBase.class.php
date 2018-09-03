<?php
//decode by qq2859470

class jxcBase extends model
{

    protected $table_fix_field = "jxc_custom_fix_field";
    protected $table_custom_field = "jxc_custom_field";
    protected $table_field_type = "custom_field_type";
    protected $table_field_model = "jxc_custom_field_model";
    protected $table_select_item = "jxc_custom_select_item";
    protected $table_sort = "jxc_sort";
    protected $table_storage = "jxc_storage";
    protected $table_goods = "jxc_goods";
    protected $table_bindflow = "jxc_bind_flow";
    protected $table_bindfield = "jxc_bind_flowfield";

    const CUSTOM_FIELD_CODE = "jxc";
    const FIELD_TYPE_TEXT = 1;
    const FIELD_TYPE_TEXTAREA = 2;
    const FIELD_TYPE_INT = 3;
    const FIELD_TYPE_REAL = 4;
    const FIELD_TYPE_DATA = 5;
    const FIELD_TYPE_COMBO = 6;
    const FIELD_TYPE_USER = 7;
    const FIELD_TYPE_CHECKBOX = 8;

    public function actionGoods( )
    {
        app::loadapp( "jxc", "baseGoods" )->run( );
    }

    public function actionStorage( )
    {
        app::loadapp( "jxc", "baseStorage" )->run( );
    }

    public function actionCustom( )
    {
        app::loadapp( "jxc", "baseCustom" )->run( );
    }

    public function actionBindflow( )
    {
        app::loadapp( "jxc", "baseBindflow" )->run( );
    }

    public function actionOutputtype( )
    {
        app::loadapp( "jxc", "baseOutputtype" )->run( );
    }

    protected function _getComboStore( )
    {
        $key = getpar( $_POST, "key" );
        ( self::CUSTOM_FIELD_CODE );
        $cf = new customField( );
        $store = $cf->getComboStore( $this->modelId, $key );
        if ( $store === FALSE )
        {
            $function = "get".ucfirst( $key );
            if ( method_exists( $this, $function ) )
            {
                $store = $this->$function( $key );
            }
        }
        ( );
        $ds = new dataStore( );
        $ds->data = is_array( $store ) ? $store : FALSE;
        echo $ds->makeJsonData( );
    }

    protected function getSortName( $key )
    {
        $sorts = $this->api_getsorts( );
        if ( !is_array( $sorts ) )
        {
            $sorts = array( );
        }
        $data = array( );
        foreach ( $sorts as $v )
        {
            $data[] = array(
                "sid" => $v['id'],
                "sortName" => $v['name']
            );
        }
        return $data;
    }

    public function api_getsorts( $format = FALSE )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->table_sort, "ORDER BY `order`" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        if ( $format )
        {
            $sorts = array( );
            foreach ( $dblist as $v )
            {
                $sorts[$v['id']] = $v['name'];
            }
            return $sorts;
        }
        $sorts = $dblist;
        return $sorts;
    }

    public function api_getSortsBySid( $id )
    {
        global $CNOA_DB;
        $sids = $CNOA_DB->db_getfield( "sids", $this->table_storage, "WHERE `id`=".$id );
        if ( empty( $sids ) )
        {
            return;
        }
        $sorts = $CNOA_DB->db_select( array( "id", "name" ), $this->table_sort, "WHERE `id` IN (".$sids.")" );
        return $sorts;
    }

    public function api_getStorage( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->table_storage );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        return $dblist;
    }

    public function api_getGoodsCustomField( )
    {
        ( self::CUSTOM_FIELD_CODE );
        $cf = new customField( );
        $fields = $cf->getAllFieldsByMid( $this->modelId, $fields );
        return $fields;
    }

    public function api_getGoodsList( $storageId = 0, $page = FALSE )
    {
        global $CNOA_DB;
        ( self::CUSTOM_FIELD_CODE );
        $cf = new customField( );
        $cf->setAllComboItem( $this->modelId );
        $cf->setCustomFieldType( $this->modelId );
        $where = "WHERE 1 ";
        $sid = intval( getpar( $_POST, "sid" ) );
        if ( !empty( $sid ) )
        {
            $where .= "AND `sid`=".$sid." ";
        }
        $manager = getpar( $_POST, "manager" );
        if ( !empty( $manager ) )
        {
            $where .= "AND `manager`=".$manager." ";
        }
        $price = getpar( $_POST, "price" );
        if ( !empty( $price ) )
        {
            $where .= "AND `price`=".$price." ";
        }
        $goodsCode = getpar( $_POST, "goodsCode" );
        if ( !empty( $goodsCode ) )
        {
            $where .= "AND `goodsCode` like '%".$goodsCode."%' ";
        }
        $priceStart = getpar( $_POST, "priceStart" );
        if ( !empty( $priceStart ) )
        {
            $where .= "AND `price`>=".$priceStart." ";
        }
        $priceEnd = getpar( $_POST, "priceEnd" );
        if ( !empty( $priceEnd ) )
        {
            $where .= "AND `price`<=".$priceEnd." ";
        }
        $fields = getpar( $_POST, "fields" );
        if ( !empty( $fields ) )
        {
            $fieldName = getpar( $_POST, "fieldName" );
            if ( !empty( $fieldName ) )
            {
                $where .= "AND ".$fields." like '%{$fieldName}%' ";
            }
        }
        if ( !empty( $storageId ) )
        {
            $sids = $CNOA_DB->db_getfield( "sids", $this->table_storage, "WHERE `id`=".$storageId );
            if ( !empty( $sids ) )
            {
                $where .= "AND `sid` IN(".$sids.") ";
            }
        }
        $goodsname = getpar( $_POST, "goodsname" );
        if ( !empty( $goodsname ) )
        {
            $where .= "AND `goodsname` like '%".$goodsname."%' ";
        }
        $unit = getpar( $_POST, "unit" );
        if ( !empty( $unit ) )
        {
            $where .= "AND `unit` like '%".$unit."%' ";
        }
        $standard = getpar( $_POST, "standard" );
        if ( !empty( $standard ) )
        {
            $where .= "AND `standard` like '%".$standard."%' ";
        }
        $ids = getpar( $_POST, "ids", getpar( $_GET, "ids", "" ) );
        if ( !empty( $ids ) )
        {
            $where .= "AND g.id IN (".$ids.") ";
        }
        $fieldNames = $cf->getAllCustomFieldsByMid( $this->modelId );
        foreach ( $GLOBALS['_POST'] as $k => $v )
        {
            if ( array_key_exists( $k, $fieldNames ) )
            {
                $value = getpar( $_POST, $k, "" );
                if ( !empty( $value ) )
                {
                    $where .= "AND `".$k."` like '%{$value}%' ";
                }
            }
        }
        $sql = "SELECT `g`.*, `s`.`name` AS `sortName` FROM ".tname( $this->table_goods )." AS `g` LEFT JOIN ".tname( $this->table_sort )." AS `s` ON `s`.`id`=`g`.`sid` "."{$where} ORDER BY `id` DESC ";
        if ( $page )
        {
            $start = getpar( $_POST, "start", 0 );
            $limit = getpagesize( "jxc_base_goods_getGoodsList" );
            if ( getpar( $_POST, "step", getpar( $_GET, "step", 0 ) ) != 1 )
            {
                $sql .= "LIMIT ".$start.", {$limit}";
            }
        }
        $result = $CNOA_DB->query( $sql );
        $data = $uids = array( );
        while ( $row = $CNOA_DB->get_array( $result ) )
        {
            $row = $cf->transformCustomFieldData( $row );
            $uids[] = $row['manager'];
            $data[] = $row;
        }
        if ( !empty( $uids ) )
        {
            $truenames = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $uids );
            foreach ( $data as $k => $v )
            {
                $data[$k]['managerName'] = $truenames[$v['manager']];
            }
        }
        if ( $page )
        {
            if ( getpar( $_POST, "step", getpar( $_GET, "step", 0 ) ) == 1 )
            {
                $where = "";
            }
            $total = $CNOA_DB->db_getcount( $this->table_goods, $where );
            return array(
                "data" => $data,
                "total" => $total
            );
        }
        return $data;
    }

    protected function getCustomField( )
    {
        $fields = $this->api_getGoodsCustomField( );
        echo json_encode( $fields );
    }

    public function formatCustomFieldData2( $fieldtype, $data, $fieldid )
    {
        global $CNOA_DB;
        switch ( $fieldtype )
        {
        case self::FIELD_TYPE_DATA :
            $data = strtotime( $data );
            return $data;
        case self::FIELD_TYPE_COMBO :
            $temp = array( );
            $temp['name'] = $data;
            $temp['fieldId'] = $fieldid;
            $temp['mid'] = 1;
            $temp['type'] = 2;
            $temp['status'] = 1;
            $data = $CNOA_DB->db_insert( $temp, $this->table_select_item );
            return $data;
        case self::FIELD_TYPE_USER :
            $data = $CNOA_DB->db_getfield( "uid", "main_user", "WHERE `truename`= '".$data."'" );
            return $data;
        case self::FIELD_TYPE_CHECKBOX :
            $temp = $ids = array( );
            $data = explode( ",", $data );
            foreach ( $data as $k => $v )
            {
                $temp['name'] = $v;
                $temp['fieldId'] = $fieldid;
                $temp['mid'] = 1;
                $temp['type'] = 2;
                $temp['status'] = 1;
                $ids[] = $CNOA_DB->db_insert( $temp, $this->table_select_item );
            }
            $data = implode( ",", $ids );
        }
        return $data;
    }

}

?>
