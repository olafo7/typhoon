<?php

class wfDatasource
{

    private $datasource = array
    (
        0 => array
        (
            "tag" => self::TAG_CUSTOMER_INFO,
            "name" => "å®¢æˆ·ä¿¡æ¯"
        ),
        1 => array
        (
            "tag" => self::TAG_CUSTOMER_LINKMAN,
            "name" => "å®¢æˆ·è”ç³»äºº"
        ),
        2 => array
        (
            "tag" => self::TAG_CUSTOMER_NAMELINK,
            "name" => "å®¢æˆ·/è”ç³»"
        ),
        3 => array
        (
            "tag" => self::TAG_ARCHIVES,
            "name" => "å…¬æ–‡æ¡£æ¡ˆ"
        ),
        4 => array
        (
            "tag" => self::TAG_SQLDETAIL,
            "name" => "SQLæ•°æ®æ˜Žç»†"
        )
    );

    const CUSTOM_CODE_CUSTOMER = "user_customers";
    const MODULE_CUSTOMER_LINKMAN = 1;
    const MODULE_CUSTOMER_INFO = 2;
    const MODULE_CUSTOMER_NAMELINK = 3;
    const TAG_CUSTOMER_INFO = "customerInfo";
    const TAG_CUSTOMER_LINKMAN = "customerLinkman";
    const TAG_CUSTOMER_NAMELINK = "customerNameLink";
    const TAG_ARCHIVES = "archives";
    const TAG_SQLDETAIL = "sqlDetail";

    public function getDatasource( )
    {
        return $this->datasource;
    }

    public function getSourceFields( $_obfuscate_kLNZ, $_obfuscate_R6iMIclcHAÿÿ = TRUE )
    {
        $_obfuscate_tjILu7ZH = array( );
        $_obfuscate_eVTMIa1A = $_obfuscate_R6iMIclcHAÿÿ ? "all" : "show";
        switch ( $_obfuscate_kLNZ )
        {
        case self::TAG_CUSTOMER_INFO :
            ( "user_customers" );
            $_obfuscate_LU8ÿ = new customField( );
            if ( CNOA_ISSAAS === TRUE )
            {
                global $CNOA_DB;
                $_obfuscate_T1Ej = $CNOA_DB->db_getfield( "id", "user_customers_custom_field_model", "WHERE `table` = 'user_customers'" );
            }
            else
            {
                $_obfuscate_T1Ej = self::MODULE_CUSTOMER_INFO;
            }
            $_obfuscate_tjILu7ZH = $_obfuscate_LU8ÿ->queryAllFieldNameByPermit( $_obfuscate_T1Ej, $_obfuscate_eVTMIa1A );
            break;
        case self::TAG_CUSTOMER_LINKMAN :
            ( "user_customers" );
            $_obfuscate_LU8ÿ = new customField( );
            if ( CNOA_ISSAAS === TRUE )
            {
                global $CNOA_DB;
                $_obfuscate_T1Ej = $CNOA_DB->db_getfield( "id", "user_customers_custom_field_model", "WHERE `table` = 'user_customers_linkman'" );
            }
            else
            {
                $_obfuscate_T1Ej = self::MODULE_CUSTOMER_LINKMAN;
            }
            $_obfuscate_tjILu7ZH = $_obfuscate_LU8ÿ->queryAllFieldNameByPermit( $_obfuscate_T1Ej, $_obfuscate_eVTMIa1A );
            break;
        case self::TAG_CUSTOMER_NAMELINK :
            ( "user_customers" );
            $_obfuscate_LU8ÿ = new customField( );
            $_obfuscate_tjILu7ZH = array( "cname" => "æ‰€å±žç”¨æˆ·å", "cid" => "æ‰€å±žç”¨æˆ·id", "lname" => "è”ç³»äººåå­—", "lid" => "è”ç³»äººid" );
            break;
        case self::TAG_ARCHIVES :
            $_obfuscate_tjILu7ZH = array( "title" => "æ–‡ä»¶æ ‡é¢˜", "from" => "æ–‡ä»¶ç±»åž‹", "type" => "å†…ç›®", "level" => "å¯†çº§", "filesnum" => "ä»¶å·", "number" => "æ–‡ä»¶å­—å·", "senddate" => "æˆæ–‡æ—¥æœŸ", "respon" => "è´£ä»»è€…", "page" => "é¡µå·", "collectdate" => "å½’æ¡£æ—¥æœŸ", "wenzhong" => "æ–‡ç§", "danganshi" => "æ¡£æ¡ˆå®¤", "anjuan" => "æ¡ˆå·", "note" => "å¤‡æ³¨" );
            break;
        case self::TAG_SQLDETAIL :
            global $CNOA_DB;
            $_obfuscate_0W8ÿ = getpar( $_POST, "sqldetailId", 0 );
            $_obfuscate_LB6BqQÿÿ = $CNOA_DB->db_getfield( "map", "abutment_sqldetail", "WHERE `id`=".$_obfuscate_0W8ÿ );
            if ( empty( $_obfuscate_LB6BqQÿÿ ) )
            {
                break;
            }
            $_obfuscate_SeV31Qÿÿ = json_decode( $_obfuscate_LB6BqQÿÿ, TRUE );
            $_obfuscate_7wÿÿ = 1;
            foreach ( $_obfuscate_SeV31Qÿÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
            {
                if ( !empty( $_obfuscate_VgKtFegÿ['mapName'] ) )
                {
                    $_obfuscate_tjILu7ZH[$_obfuscate_VgKtFegÿ['name']] = $_obfuscate_VgKtFegÿ['mapName'];
                    ++$_obfuscate_7wÿÿ;
                }
            }
        }
        $_obfuscate_6RYLWQÿÿ = array( );
        if ( $_obfuscate_R6iMIclcHAÿÿ )
        {
            foreach ( $_obfuscate_tjILu7ZH as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_6RYLWQÿÿ[] = array(
                    "field" => $_obfuscate_5wÿÿ,
                    "fieldname" => $_obfuscate_6Aÿÿ
                );
            }
            return $_obfuscate_6RYLWQÿÿ;
        }
        foreach ( $_obfuscate_tjILu7ZH as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6RYLWQÿÿ[] = array(
                "field" => $_obfuscate_5wÿÿ,
                "fieldname" => $_obfuscate_6Aÿÿ,
                "show" => 1
            );
        }
        return $_obfuscate_6RYLWQÿÿ;
    }

    public function getDsData( $_obfuscate_kLNZ )
    {
        switch ( $_obfuscate_kLNZ )
        {
        case self::TAG_CUSTOMER_INFO :
            $_obfuscate_SUjPN94Er7yI = $this->getData4csInfo( );
            break;
        case self::TAG_CUSTOMER_LINKMAN :
            $_obfuscate_SUjPN94Er7yI = $this->getData4csLinkman( );
            break;
        case self::TAG_CUSTOMER_NAMELINK :
            $_obfuscate_SUjPN94Er7yI = $this->getData4csNameLink( );
            break;
        case self::TAG_ARCHIVES :
            $_obfuscate_SUjPN94Er7yI = $this->getData4Archives( );
            break;
            return "";
        }
        return $_obfuscate_SUjPN94Er7yI->makeJsonData( );
    }

    private function getData4csInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_mV9HBLYÿ = getpar( $_POST, "start", 0 );
        $_obfuscate_lnCAb94lsYIBRORJ = getpar( $_POST, "name", getpar( $_GET, "name", "" ) );
        $_obfuscate_rVsNRAÿÿ = 15;
        $_obfuscate_01zn_wÿÿ = array( );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( "SELECT DISTINCT cid FROM".tname( "user_customers_share" ).( " WHERE uid=".$_obfuscate_7Ri3 ) );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_01zn_wÿÿ[] = $_obfuscate_gkt['cid'];
        }
        $_obfuscate_ZOFNaoll9Fcÿ = $_obfuscate_IaQ9GFSuNS7v2wÿ = "";
        if ( !empty( $_obfuscate_01zn_wÿÿ ) )
        {
            $_obfuscate_ZOFNaoll9Fcÿ = " OR c.cid IN (".implode( ",", $_obfuscate_01zn_wÿÿ ).")";
        }
        if ( !empty( $_obfuscate_lnCAb94lsYIBRORJ ) )
        {
            $_obfuscate_IaQ9GFSuNS7v2wÿ = " AND c.name LIKE '%".$_obfuscate_lnCAb94lsYIBRORJ."%'";
        }
        $_obfuscate_IRFhnYwÿ = "WHERE c.status=0 ".$_obfuscate_IaQ9GFSuNS7v2wÿ." AND (c.flowmanuid={$_obfuscate_7Ri3} {$_obfuscate_ZOFNaoll9Fcÿ})";
        $_obfuscate_3y0Y = "SELECT  c.*, l.name AS linkman, l.qq, l.email, l.mobile, i.name AS sort, s.name AS sid, r1.name AS region, r2.name AS region2, u.truename AS flowmanuid, d.name AS degreeId FROM ".tname( "user_customers" )." AS c LEFT JOIN ".tname( "user_customers_linkman" )." AS l ON l.lid=c.lid LEFT JOIN ".tname( "user_customers_idcf" )." AS i ON i.iid=c.sort LEFT JOIN ".tname( "user_customers_sort" )." AS s ON s.id=c.sid LEFT JOIN ".tname( "user_customers_region" )." AS r1 ON r1.rid=c.region LEFT JOIN ".tname( "user_customers_region2" )." AS r2 ON r2.pid=c.region2 LEFT JOIN ".tname( "user_customers_degree" )." AS d ON d.id=c.degreeId LEFT JOIN ".tname( "main_user" )." AS u ON u.uid=c.flowmanuid "."{$_obfuscate_IRFhnYwÿ} ORDER BY `posttime` DESC LIMIT {$_obfuscate_mV9HBLYÿ} , {$_obfuscate_rVsNRAÿÿ}";
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQÿÿ = array( );
        ( self::CUSTOM_CODE_CUSTOMER );
        $_obfuscate_LU8ÿ = new customField( );
        $_obfuscate_LU8ÿ->setAllComboItem( self::MODULE_CUSTOMER_INFO );
        $_obfuscate_LU8ÿ->setCustomFieldType( self::MODULE_CUSTOMER_INFO );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_gkt = $_obfuscate_LU8ÿ->transformCustomFieldData( $_obfuscate_gkt );
            $_obfuscate_gkt = $this->transformData( $_obfuscate_gkt );
            $_obfuscate_gkt['datasourceId'] = $_obfuscate_gkt['cid'];
            $_obfuscate_gkt['posttime'] = formatdate( $_obfuscate_gkt['posttime'] );
            $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_gkt;
        }
        $_obfuscate_j9sJesÿ = ( integer )$CNOA_DB->db_getcount( "user_customers", strtr( $_obfuscate_IRFhnYwÿ, array( "c." => "" ) ) );
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_6RYLWQÿÿ;
        $_obfuscate_SUjPN94Er7yI->total = $_obfuscate_j9sJesÿ;
        return $_obfuscate_SUjPN94Er7yI;
    }

    private function getData4csLinkman( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_mV9HBLYÿ = getpar( $_POST, "start", 0 );
        $_obfuscate_rVsNRAÿÿ = 15;
        $_obfuscate_dcwitxb = getpar( $_POST, "name", getpar( $_GET, "name", "" ) );
        ( self::CUSTOM_CODE_CUSTOMER );
        $_obfuscate_LU8ÿ = new customField( );
        $_obfuscate_LU8ÿ->setAllComboItem( self::MODULE_CUSTOMER_LINKMAN );
        $_obfuscate_LU8ÿ->setCustomFieldType( self::MODULE_CUSTOMER_LINKMAN );
        $_obfuscate_01zn_wÿÿ = array( );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( "SELECT DISTINCT cid FROM".tname( "user_customers_share" ).( " WHERE uid=".$_obfuscate_7Ri3 ) );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_01zn_wÿÿ[] = $_obfuscate_gkt['cid'];
        }
        $_obfuscate_ZOFNaoll9Fcÿ = "";
        if ( !empty( $_obfuscate_01zn_wÿÿ ) )
        {
            $_obfuscate_ZOFNaoll9Fcÿ = " OR c.cid IN (".implode( ",", $_obfuscate_01zn_wÿÿ ).")";
        }
        $_obfuscate_IaQ9GFSuNS7v2wÿ = "";
        if ( !empty( $_obfuscate_dcwitxb ) )
        {
            $_obfuscate_IaQ9GFSuNS7v2wÿ = " AND (l.name LIKE '%".$_obfuscate_dcwitxb."%' OR c.name LIKE '%{$_obfuscate_dcwitxb}%' OR l.mobile LIKE '%{$_obfuscate_dcwitxb}%')";
        }
        $_obfuscate_IRFhnYwÿ = "c.status=0 AND (c.flowmanuid=".$_obfuscate_7Ri3." {$_obfuscate_ZOFNaoll9Fcÿ}){$_obfuscate_IaQ9GFSuNS7v2wÿ}";
        $_obfuscate_3y0Y = "SELECT l.*, c.lock, c.name AS cid, u.truename AS addName FROM ".tname( "user_customers_linkman" )." AS `l` LEFT JOIN ".tname( "main_user" )." AS `u` ON u.uid=l.addUid LEFT JOIN ".tname( "user_customers" )." AS `c` ON c.cid=l.cid ".( "WHERE ".$_obfuscate_IRFhnYwÿ." ORDER BY l.lid DESC LIMIT {$_obfuscate_mV9HBLYÿ} , {$_obfuscate_rVsNRAÿÿ}" );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQÿÿ = array( );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_gkt = $_obfuscate_LU8ÿ->transformCustomFieldData( $_obfuscate_gkt );
            $_obfuscate_gkt = $this->transformData( $_obfuscate_gkt );
            $_obfuscate_gkt['datasourceId'] = $_obfuscate_gkt['lid'];
            $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_gkt;
        }
        $_obfuscate_3y0Y = "SELECT count(*) AS count FROM ".tname( "user_customers_linkman" )." AS `l` LEFT JOIN ".tname( "user_customers" )." AS `c` ON c.cid=l.cid ".( "WHERE ".$_obfuscate_IRFhnYwÿ );
        $_obfuscate_xs33Yt_k = $CNOA_DB->get_one( $_obfuscate_3y0Y );
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_6RYLWQÿÿ;
        $_obfuscate_SUjPN94Er7yI->total = ( integer )$_obfuscate_xs33Yt_k['count'];
        return $_obfuscate_SUjPN94Er7yI;
    }

    private function getData4Archives( )
    {
        global $CNOA_DB;
        $_obfuscate_vholQÿÿ = array( 0 => "å…¶ä»–", 1 => "å‘æ–‡", 2 => "æ”¶æ–‡" );
        $_obfuscate_mV9HBLYÿ = getpar( $_POST, "start", 0 );
        $_obfuscate_rVsNRAÿÿ = 15;
        $_obfuscate_dcwitxb = getpar( $_POST, "name", getpar( $_GET, "name", "" ) );
        $_obfuscate_IaQ9GFSuNS7v2wÿ = "";
        if ( !empty( $_obfuscate_dcwitxb ) )
        {
            $_obfuscate_IaQ9GFSuNS7v2wÿ = "WHERE f.title LIKE '%".$_obfuscate_dcwitxb."%'";
        }
        $_obfuscate_3y0Y = "SELECT f.*, r.title AS danganshi, w.title AS wenzhong, t.title AS type, a.title AS anjuan, l.title AS level FROM ".tname( "odoc_files_dangan" )." AS f LEFT JOIN ".tname( "odoc_files_dangan_room" )." AS r ON r.id=f.danganshi LEFT JOIN ".tname( "odoc_files_dangan_wenzhong" )." AS w ON w.id=f.wenzhong LEFT JOIN ".tname( "odoc_files_dangan_type" )." AS t ON t.id=f.type LEFT JOIN ".tname( "odoc_files_anjuan_list" )." AS a ON a.id=f.anjuan LEFT JOIN ".tname( "odoc_setting_word_level" )." AS l ON l.tid=f.level "."{$_obfuscate_IaQ9GFSuNS7v2wÿ} ORDER BY `id` DESC LIMIT {$_obfuscate_mV9HBLYÿ} , {$_obfuscate_rVsNRAÿÿ}";
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQÿÿ = array( );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_gkt['datasourceId'] = $_obfuscate_gkt['id'];
            $_obfuscate_gkt['from'] = $_obfuscate_vholQÿÿ[$_obfuscate_gkt['from']];
            $_obfuscate_gkt['senddate'] = formatdate( $_obfuscate_gkt['senddate'] );
            $_obfuscate_gkt['collectdate'] = formatdate( $_obfuscate_gkt['collectdate'] );
            $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_gkt;
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_6RYLWQÿÿ;
        $_obfuscate_SUjPN94Er7yI->total = $CNOA_DB->db_getcount( "odoc_files_dangan", "WHERE 1" );
        return $_obfuscate_SUjPN94Er7yI;
    }

    private function transformData( $_obfuscate_gkt )
    {
        foreach ( $_obfuscate_gkt as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( preg_match( "/(.*)(_name)\$/", $_obfuscate_5wÿÿ, $_obfuscate_8UmnTppRcAÿÿ ) )
            {
                $_obfuscate_gkt[$_obfuscate_8UmnTppRcAÿÿ[1]] = $_obfuscate_gkt[$_obfuscate_5wÿÿ];
                unset( $_obfuscate_gkt[$_obfuscate_5wÿÿ] );
            }
        }
        return $_obfuscate_gkt;
    }

    private function getData4csNameLink( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_mV9HBLYÿ = getpar( $_POST, "start", 0 );
        $_obfuscate_lnCAb94lsYIBRORJ = getpar( $_POST, "name", "" );
        $_obfuscate_rVsNRAÿÿ = 15;
        $_obfuscate_IRFhnYwÿ = "WHERE ";
        $_obfuscate_KvACGgÿÿ = array( );
        if ( in_array( "user_customers_zhuguan", $GLOBALS['user']['permitArray'] ) )
        {
            $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
            ( );
            $_obfuscate_eVTMIa1A = new permit( );
            $_obfuscate_nJGEkXHPKje6iQÿÿ = $_obfuscate_eVTMIa1A->getUserPermissionAreaInfo( $_obfuscate_7Ri3, "user", "customers", "zhuguan" );
            unset( $_obfuscate_eVTMIa1A );
            $_obfuscate_yI4F5WJaZteib0Uÿ = app::loadapp( "main", "user" )->api_getUserByFids( $_obfuscate_nJGEkXHPKje6iQÿÿ['area'], "", TRUE );
            if ( $_obfuscate_yI4F5WJaZteib0Uÿ )
            {
                $_obfuscate_PVLK5jra = array_keys( $_obfuscate_yI4F5WJaZteib0Uÿ );
            }
        }
        $_obfuscate_PVLK5jra[] = $_obfuscate_7Ri3;
        $_obfuscate__eqrEQÿÿ = array_unique( $_obfuscate_PVLK5jra );
        if ( is_array( $_obfuscate__eqrEQÿÿ ) && 0 < count( $_obfuscate__eqrEQÿÿ ) )
        {
            $_obfuscate_KvACGgÿÿ[] = "flowmanuid IN (".implode( ",", $_obfuscate__eqrEQÿÿ ).")";
        }
        $_obfuscate_HX1MRaQK = array( );
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( "SELECT DISTINCT cid FROM".tname( "user_customers_share" ).( " WHERE uid=".$_obfuscate_7Ri3 ) );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_HX1MRaQK[] = $_obfuscate_gkt['cid'];
        }
        $_obfuscate_01zn_wÿÿ = $_obfuscate_HX1MRaQK;
        if ( is_array( $_obfuscate_01zn_wÿÿ ) && 0 < count( $_obfuscate_01zn_wÿÿ ) )
        {
            $_obfuscate_KvACGgÿÿ[] = "c.cid IN (".implode( ",", $_obfuscate_01zn_wÿÿ ).")";
        }
        $_obfuscate_IRFhnYwÿ .= "(".implode( " OR ", $_obfuscate_KvACGgÿÿ ).")";
        $_obfuscate_IRFhnYwÿ .= " AND c.status!= -1";
        $_obfuscate_3y0Y = "SELECT  c.cid AS cid, c.name AS cname, l.lid AS lid, l.name AS lname FROM ".tname( "user_customers" )." AS c LEFT JOIN ".tname( "user_customers_linkman" )." AS l ON l.cid = c.cid "."{$_obfuscate_IRFhnYwÿ} ORDER BY c.cid LIMIT {$_obfuscate_mV9HBLYÿ} , {$_obfuscate_rVsNRAÿÿ}";
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_6RYLWQÿÿ = array( );
        ( self::CUSTOM_CODE_CUSTOMER );
        $_obfuscate_LU8ÿ = new customField( );
        $_obfuscate_LU8ÿ->setAllComboItem( self::MODULE_CUSTOMER_INFO );
        $_obfuscate_LU8ÿ->setCustomFieldType( self::MODULE_CUSTOMER_INFO );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_gkt = $_obfuscate_LU8ÿ->transformCustomFieldData( $_obfuscate_gkt );
            $_obfuscate_gkt = $this->transformData( $_obfuscate_gkt );
            $_obfuscate_gkt['datasourceId'] = $_obfuscate_gkt['cid'];
            $_obfuscate_gkt['posttime'] = formatdate( $_obfuscate_gkt['posttime'] );
            $_obfuscate_6RYLWQÿÿ[] = $_obfuscate_gkt;
        }
        $_obfuscate_3y0Y = "SELECT count(*) AS count FROM ".tname( "user_customers" )." AS c LEFT JOIN ".tname( "user_customers_linkman" )." AS l ON c.cid=l.cid "."{$_obfuscate_IRFhnYwÿ}";
        $_obfuscate_xs33Yt_k = $CNOA_DB->query( $_obfuscate_3y0Y );
        if ( $_obfuscate_6UUC = $CNOA_DB->get_array( $_obfuscate_xs33Yt_k ) )
        {
            $_obfuscate_j9sJesÿ = $_obfuscate_6UUC['count'];
        }
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_6RYLWQÿÿ;
        $_obfuscate_SUjPN94Er7yI->total = $_obfuscate_j9sJesÿ;
        return $_obfuscate_SUjPN94Er7yI;
    }

}

?>
