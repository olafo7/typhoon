<?php
//decode by qq2859470

class flowFlowCommon extends model
{

    public function __construct( )
    {
    }

    public function __destruct( )
    {
    }

    public function api_parseFormForView( $html, $data, $formItems, $flowInfo, $showIng = TRUE )
    {
        if ( !is_array( $nowItems ) )
        {
            $nowItems = array( );
        }
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        if ( !is_array( $formItems ) )
        {
            $formItems = array( );
        }
        $elementList = $this->__getHtmlElement( $html );
        $nowItems2 = array( );
        foreach ( $nowItems as $v )
        {
            $nowItems2[$v['itemid']] = $v;
        }
        unset( $nowItems );
        unset( $v );
        $formItems2 = array( );
        foreach ( $formItems as $v )
        {
            $formItems2[$v['itemid']] = $v;
        }
        unset( $formItems );
        unset( $v );
        foreach ( $data as $v )
        {
            if ( $v['step'] <= $flowInfo['step'] )
            {
                $html = str_replace( $formItems2[$v['itemid']]['html'], nl2br( $v['data'] ), $html );
            }
        }
        unset( $data );
        unset( $v );
        foreach ( $elementList as $v )
        {
            if ( $showIng )
            {
                $html = str_replace( $v['html'], "<span style='color:#FFF;background-color:#660000;padding:2px;'>".$formItems2[$v['itemid']]['title']."</span>", $html );
            }
            else
            {
                $html = str_replace( $v['html'], "", $html );
            }
        }
        return $html;
    }

    public function api_parseFormForNextFlowStep( $html, $nowItems, $data, $formItems, $stepInfo )
    {
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        if ( !is_array( $nowItems ) )
        {
            $nowItems = array( );
        }
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        if ( !is_array( $formItems ) )
        {
            $formItems = array( );
        }
        if ( !is_array( $stepInfo ) )
        {
            $stepInfo = array( );
        }
        $tmpFormItems = $formItems;
        $formItems = array( );
        foreach ( $tmpFormItems as $v )
        {
            if ( $v['type'] == "textarea" && !eregi( "<\\/textarea>", $v['html'] ) )
            {
                $v['html'] .= "</textarea>";
            }
            $formItems[$v['itemid']] = $v;
        }
        unset( $tmpFormItems );
        unset( $v );
        $tmpNowItems = $nowItems;
        $nowItems = array( );
        foreach ( $tmpNowItems as $v )
        {
            $nowItems[$v['itemid']] = $v;
        }
        unset( $tmpNowItems );
        unset( $v );
        $nowStep = $stepInfo['stepid'];
        $dataList = array( );
        $dataList['ddata'] = array( );
        $dataList['ndata'] = array( );
        foreach ( $data as $v )
        {
            if ( $v['step'] < $nowStep )
            {
                $dataList['ddata'][$v['itemid']] = $v;
            }
            if ( $v['step'] == $nowStep )
            {
                $dataList['ndata'][$v['itemid']] = $v;
            }
        }
        unset( $data );
        unset( $v );
        foreach ( $dataList['ddata'] as $v )
        {
            $html = str_replace( $formItems[$v['itemid']]['html'], $v['data'], $html );
        }
        unset( $dataList['ddata'] );
        unset( $v );
        foreach ( $nowItems as $v )
        {
            if ( $v['used'] === TRUE || $v['need'] || $v['must'] )
            {
                $this->__parseMacroInputs( $html, $formItems[$v['itemid']] );
                if ( $stepInfo['operatortype'] == 2 && $stepInfo['operatorperson'] == 2 )
                {
                    if ( $dataList['ndata'][$v['itemid']]['uid'] == $uid )
                    {
                        $this->__setValue( $html, $formItems[$v['itemid']], $dataList['ndata'][$v['itemid']]['data'] );
                    }
                    else if ( !empty( $dataList['ndata'][$v['itemid']]['data'] ) )
                    {
                        $html = str_replace( $formItems[$v['itemid']]['html'], $dataList['ndata'][$v['itemid']]['data'], $html );
                    }
                }
                else
                {
                    $this->__setValue( $html, $formItems[$v['itemid']], $dataList['ndata'][$v['itemid']]['data'] );
                }
                if ( $nowItems[$v['itemid']]['need'] == "1" )
                {
                    $itemReplace = "name=\"FLOWDATA[".$v['itemid']."]\"";
                    if ( $nowItems[$v['itemid']]['must'] == "1" )
                    {
                        $html = str_replace( $itemReplace, " class=\"flow_form_must_color\" ".$itemReplace, $html );
                    }
                    else
                    {
                        $html = str_replace( $itemReplace, " class=\"flow_form_need_color\" ".$itemReplace, $html );
                    }
                }
            }
            else
            {
                $html = str_replace( $formItems[$v['itemid']]['html'], "<span style='color:#FFF;background-color:#660000;padding:2px;' title='未办理'>".$formItems[$v['itemid']]['title']."</span>", $html );
            }
        }
        unset( $v );
        return $html;
    }

    public function api_parseFormForNextFlowStep__( $html, $nowItems, $data, $formItems, $stepInfo )
    {
        if ( !is_array( $nowItems ) )
        {
            $nowItems = array( );
        }
        if ( !is_array( $data ) )
        {
            $data = array( );
        }
        if ( !is_array( $formItems ) )
        {
            $formItems = array( );
        }
        if ( !is_array( $stepInfo ) )
        {
            $stepInfo = array( );
        }
        $data2 = $data;
        $data = array( );
        foreach ( $data2 as $k => $v )
        {
            $data[$v['itemid']] = $v;
        }
        unset( $k );
        unset( $v );
        $elementList = $this->__getHtmlElement( $html );
        $nowItems2 = array( );
        foreach ( $nowItems as $v )
        {
            $nowItems2[$v['itemid']] = $v;
        }
        unset( $nowItems );
        unset( $v );
        $formItems2 = array( );
        foreach ( $formItems as $k => $v )
        {
            if ( $formItems[$k]['type'] == "textarea" && !eregi( "<\\/textarea>", $formItems[$k]['html'] ) )
            {
                $formItems[$k]['html'] .= "</textarea>";
                $v['html'] .= "</textarea>";
            }
            $formItems2[$v['itemid']] = $v;
        }
        unset( $formItems );
        unset( $k );
        unset( $v );
        if ( $stepInfo['operatortype'] == 2 && $stepInfo['operatorperson'] == 2 )
        {
            $stepInfo['formitems'] = json_decode( $stepInfo['formitems'], TRUE );
            if ( !is_array( $stepInfo['formitems'] ) )
            {
                $stepInfo['formitems'] = array( );
            }
            foreach ( $stepInfo['formitems'] as $v )
            {
                if ( empty( $data[$v['itemid']]['data'] ) )
                {
                    unset( $data[$v['itemid']] );
                }
            }
        }
        foreach ( $data as $v )
        {
            $html = str_replace( $formItems2[$v['itemid']]['html'], $v['data'], $html );
        }
        unset( $data );
        unset( $v );
        foreach ( $elementList as $v )
        {
            if ( $nowItems2[$v['itemid']]['used'] == "1" )
            {
                $this->__parseMacroInputs( $html, $v );
            }
            if ( $nowItems2[$v['itemid']]['need'] == "1" )
            {
                $itemReplace = "name=\"FLOWDATA[".$v['itemid']."]\"";
                if ( $nowItems2[$v['itemid']]['must'] == "1" )
                {
                    $html = str_replace( $itemReplace, " class=\"flow_form_must_color\" ".$itemReplace, $html );
                }
                else
                {
                    $html = str_replace( $itemReplace, " class=\"flow_form_need_color\" ".$itemReplace, $html );
                }
            }
            else
            {
                $html = str_replace( $v['html'], "<span style='color:#FFF;background-color:#660000;border:1px solid red'>".$formItems2[$v['itemid']]['title']."(未办理)</span>", $html );
            }
        }
        return $html;
    }

    public function api_parseFormForNewFlow( $html, $firstItems, $flowFormItems )
    {
        if ( !is_array( $itemsInfo ) )
        {
            $itemsInfo = array( );
        }
        $elementList = $this->__getHtmlElement( $html );
        $firstItems2 = array( );
        foreach ( $firstItems as $v )
        {
            $firstItems2[$v['itemid']] = $v;
        }
        unset( $firstItems );
        unset( $v );
        $flowFormItems2 = array( );
        if ( !is_array( $flowFormItems ) )
        {
            $flowFormItems = array( );
        }
        foreach ( $flowFormItems as $v )
        {
            $flowFormItems2[$v['itemid']] = $v;
        }
        unset( $flowFormItems );
        unset( $v );
        foreach ( $elementList as $v )
        {
            if ( $flowFormItems2[$v['itemid']]['type'] == "SYS_FLOWTITLE" )
            {
                $html = str_replace( $v['html'], "<span style='color:yellow;background-color:#660000'>".lang( "flowName" )."</span>", $html );
            }
            if ( $flowFormItems2[$v['itemid']]['type'] == "SYS_FLOWNAME" )
            {
                $html = str_replace( $v['html'], "<span style='color:yellow;background-color:#660000'>流程编号</span>", $html );
            }
        }
        unset( $v );
        foreach ( $elementList as $v )
        {
            if ( $firstItems2[$v['itemid']]['used'] == "1" )
            {
                $this->__parseMacroInputs( $html, $v );
            }
            if ( $firstItems2[$v['itemid']]['need'] == "1" )
            {
                $itemReplace = "name=\"FLOWDATA[".$v['itemid']."]\"";
                if ( $firstItems2[$v['itemid']]['must'] == "1" )
                {
                    $html = str_replace( $itemReplace, " class=\"flow_form_must_color\" ".$itemReplace, $html );
                }
                else
                {
                    $html = str_replace( $itemReplace, " class=\"flow_form_need_color\" ".$itemReplace, $html );
                }
            }
            else
            {
                $html = str_replace( $v['html'], "<span style='color:#FFF;background-color:#660000;padding:2px;'>".$flowFormItems2[$v['itemid']]['title']."</span>", $html );
            }
        }
        return $html;
    }

    private function __getHtmlElement( $html )
    {
        $elementList = array( );
        preg_match_all( "/((<((input))[^>]*>)|(<textarea[\\s\\S]+?><\\/textarea>)|(<select[\\s\\S]+?>[\\s\\S]+?<\\/select>))/i", $html, $arr );
        foreach ( $arr[0] as $v )
        {
            $tmp = array( );
            $tmp['itemid'] = preg_replace( "/(.*)flowitemid=\"([^\"]*)\"\\s?(.*)/is", "\\2", $v );
            $tmp['type'] = preg_replace( "/(.*)flowitemtp=\"([^\"]*)\"\\s?(.*)/is", "\\2", $v );
            $tmp['name'] = preg_replace( "/(.*)name=\"([^\"]*)\"\\s?(.*)/is", "\\2", $v );
            $tmp['title'] = preg_replace( "/(.*)title=\"([^\"]*)\"\\s?(.*)/is", "\\2", $v );
            $tmp['htmltag'] = strtolower( substr( $v, 1, strpos( $v, " " ) - 1 ) );
            $tmp['html'] = $v;
            $elementList[] = $tmp;
        }
        return $elementList;
    }

    public function __setValue( &$html, $item, $value )
    {
        $value = trim( $value );
        if ( $item['htmltag'] == "input" )
        {
            $item['html2'] = str_replace( "<input", "<input value='".$value."'", $item['html'] );
        }
        if ( $item['htmltag'] == "textarea" )
        {
            $item['html2'] = str_replace( "</textarea>", $value."</textarea>", $item['html'] );
        }
        if ( $item['htmltag'] == "select" )
        {
            $item['html2'] = str_replace( "<option value=\"".$value."\">", "<option value=\"".$value."\" selected=\"selected\">", $item['html'] );
        }
        $html = str_replace( $item['html'], $item['html2'], $html );
    }

    private function __parseMacroInputs( &$html, $item )
    {
        $value = "";
        switch ( $item['type'] )
        {
        case "SYS_DATE" :
            $value = $this->__SYS_DATE( );
            break;
        case "SYS_DATE_CN" :
            $value = $this->__SYS_DATE_CN( );
            break;
        case "SYS_DATE_CN_SHORT3" :
            $value = $this->__SYS_DATE_CN_SHORT3( );
            break;
        case "SYS_DATE_CN_SHORT4" :
            $value = $this->__SYS_DATE_CN_SHORT4( );
            break;
        case "SYS_DATE_CN_SHORT1" :
            $value = $this->__SYS_DATE_CN_SHORT1( );
            break;
        case "SYS_DATE_CN_SHORT2" :
            $value = $this->__SYS_DATE_CN_SHORT2( );
            break;
        case "SYS_TIME" :
            $value = $this->__SYS_TIME( );
            break;
        case "SYS_DATETIME" :
            $value = $this->__SYS_DATETIME( );
            break;
        case "SYS_WEEK" :
            $value = $this->__SYS_WEEK( );
            break;
        case "SYS_UID" :
            $value = $this->__SYS_UID( );
            break;
        case "SYS_TRUENAME" :
            $value = $this->__SYS_TRUENAME( );
            break;
        case "SYS_USERNAME" :
            $value = $this->__SYS_USERNAME( );
            break;
        case "SYS_DEPTNAME" :
            $value = $this->__SYS_DEPTNAME( );
            break;
        case "SYS_JOBNAME" :
            $value = $this->__SYS_JOBNAME( );
            break;
        case "SYS_STATIONNAME" :
            $value = $this->__SYS_STATIONNAME( );
            break;
        case "SYS_TRUENAME_DATE" :
            $value = $this->__SYS_TRUENAME_DATE( );
            break;
        case "SYS_TRUENAME_DATETIME" :
            $value = $this->__SYS_TRUENAME_DATETIME( );
            break;
        case "SYS_FORMNAME" :
            $value = $this->__SYS_FORMNAME( );
            break;
        case "SYS_FLOWTITLE" :
            $value = $this->__SYS_FLOWTITLE( );
            break;
        case "SYS_FLOWNAME" :
            $value = $this->__SYS_FLOWNAME( );
            break;
        case "SYS_SDATE" :
            $value = $this->__SYS_SDATE( );
            break;
        case "SYS_SDATETIME" :
            $value = $this->__SYS_SDATETIME( );
            break;
        case "SYS_IP" :
            $value = $this->__SYS_IP( );
            break;
        default :
            return;
        }
        $value .= "<input type='hidden' name='".$item['name']."' value='{$value}'>";
        $html = str_replace( $item['html'], $value, $html );
    }

    private function __SYS_DATE( )
    {
        return date( "Y-m-d", $GLOBALS['CNOA_TIMESTAMP'] );
    }

    private function __SYS_DATE_CN( )
    {
        return date( "Y年m月d日", $GLOBALS['CNOA_TIMESTAMP'] );
    }

    private function __SYS_DATE_CN_SHORT3( )
    {
        return date( "Y年", $GLOBALS['CNOA_TIMESTAMP'] );
    }

    private function __SYS_DATE_CN_SHORT4( )
    {
        return date( "Y", $GLOBALS['CNOA_TIMESTAMP'] );
    }

    private function __SYS_DATE_CN_SHORT1( )
    {
        return date( "Y年m月", $GLOBALS['CNOA_TIMESTAMP'] );
    }

    private function __SYS_DATE_CN_SHORT2( )
    {
        return date( "m月d日", $GLOBALS['CNOA_TIMESTAMP'] );
    }

    private function __SYS_TIME( )
    {
        return date( "H时i分", $GLOBALS['CNOA_TIMESTAMP'] );
    }

    private function __SYS_DATETIME( )
    {
        return date( "Y年m月d日 H时i分", $GLOBALS['CNOA_TIMESTAMP'] );
    }

    private function __SYS_WEEK( )
    {
        return getweek( $GLOBALS['CNOA_TIMESTAMP'] );
    }

    private function __SYS_UID( )
    {
        global $CNOA_SESSION;
        return $CNOA_SESSION->get( "UID" );
    }

    private function __SYS_TRUENAME( )
    {
        global $CNOA_SESSION;
        return $CNOA_SESSION->get( "TRUENAME" );
    }

    private function __SYS_USERNAME( )
    {
        global $CNOA_SESSION;
        return $CNOA_SESSION->get( "TRUENAME" );
    }

    private function __SYS_DEPTNAME( )
    {
        global $CNOA_SESSION;
        return app::loadapp( "main", "struct" )->api_getNameById( $CNOA_SESSION->get( "DID" ) );
    }

    private function __SYS_JOBNAME( )
    {
        global $CNOA_SESSION;
        return app::loadapp( "main", "job" )->api_getNameById( $CNOA_SESSION->get( "JID" ) );
    }

    private function __SYS_STATIONNAME( )
    {
        global $CNOA_SESSION;
        return app::loadapp( "main", "station" )->api_getNameById( $CNOA_SESSION->get( "SID" ) );
    }

    private function __SYS_TRUENAME_DATE( )
    {
        global $CNOA_SESSION;
        return $CNOA_SESSION->get( "TRUENAME" )." ".date( "Y年m月d日", $GLOBALS['CNOA_TIMESTAMP'] );
    }

    private function __SYS_TRUENAME_DATETIME( )
    {
        global $CNOA_SESSION;
        return $CNOA_SESSION->get( "TRUENAME" )." ".date( "Y年m月d日 H时i分", $GLOBALS['CNOA_TIMESTAMP'] );
    }

    private function __SYS_FORMNAME( )
    {
        global $CNOA_DB;
        $fid = getpar( $_POST, "fid", 0 );
        return $CNOA_DB->db_getfield( "name", "flow_flow_form", "WHERE `fid`='".$fie."'" );
    }

    private function __SYS_FLOWTITLE( )
    {
        global $CNOA_DB;
        $lid = getpar( $_POST, "lid", 0 );
        return $CNOA_DB->db_getfield( "name", "flow_flow_list", "WHERE `lid`='".$fie."'" );
    }

    private function __SYS_FLOWNAME( )
    {
        global $CNOA_DB;
        $lid = getpar( $_POST, "lid", 0 );
        return $CNOA_DB->db_getfield( "number", "flow_flow_list", "WHERE `lid`='".$fie."'" );
    }

    private function __SYS_SDATE( )
    {
        return date( "Y年m月d日", $GLOBALS['CNOA_TIMESTAMP'] );
    }

    private function __SYS_SDATETIME( )
    {
        return date( "Y年m月d日 H时2i1分", $GLOBALS['CNOA_TIMESTAMP'] );
    }

    private function __SYS_IP( )
    {
        return getip( );
    }

    public function api_SYS_FLOWTITLE( )
    {
        $this->__SYS_FLOWTITLE( );
    }

    public function api_SYS_FORMNAME( )
    {
        $this->__SYS_FORMNAME( );
    }

}

?>
