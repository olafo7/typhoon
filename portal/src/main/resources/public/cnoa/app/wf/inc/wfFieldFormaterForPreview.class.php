<?php

class wfFieldFormaterForPreview extends wfCommon
{

    private $t_style = "wf_s_form_style";
    public $styleList = NULL;
    private $formHtml = NULL;
    private $elements = NULL;
    public $flowNumber = "";
    public $flowName = "";
    public $creatorUid = 0;
    public $extHtml = "";
    public $parseForOrder = FALSE;

    public function __construct( $elements, $html )
    {
        $this->styleList = $this->__getStylesList( );
        $this->formHtml = $html;
        $this->elements = $elements;
    }

    public function crteateFormHtml( )
    {
        global $CNOA_DB;
        foreach ( $this->elements as $fv )
        {
            if ( $this->parseForOrder )
            {
                $item = $this->formatFormItemForOrder( $fv );
                $this->formHtml = str_replace( $fv['html'], $item, $this->formHtml );
            }
            else
            {
                $item = $this->formatFormItem( $fv );
                if ( $fv['otype'] == "detailtable" )
                {
                    $this->formHtml = str_replace( $fv['html'], "<{[change]}>", $this->formHtml );
                    $html = explode( "<{[change]}>", $this->formHtml );
                    $html[0] = substr( $html[0], 0, strripos( $html[0], "<tr" ) );
                    $html[1] = substr( $html[1], stripos( $html[1], "</tr>" ) + 5, strlen( $html[1] ) );
                    $this->formHtml = $html[0].$item.$html[1];
                }
                else
                {
                    $this->formHtml = str_replace( $fv['html'], $item, $this->formHtml );
                }
            }
        }
        $this->formHtml = str_replace( "visibility:hidden", "display:none", $this->formHtml );
        $this->formHtml .= $this->extHtml;
        return $this->formHtml;
    }

    private function formatFormItem( $element )
    {
        if ( $element['tagName'] != "input" )
        {
            return "";
        }
        if ( in_array( $element['otype'], array( "textfield", "textarea", "select", "radio", "checkbox", "calculate", "macro", "choice", "detailtable", "signature" ) ) )
        {
            eval( "\$return = \$this->_format_".$element['otype']."(\$element);" );
            return $return;
        }
        return "";
    }

    private function formatFormItemForOrder( $element )
    {
        if ( $element['tagName'] != "input" )
        {
            return "";
        }
        $odata = $this->__getOdata( $element['odata'] );
        $style = $this->styleList[$odata['style']];
        $style = "style=\"".$element['style'].";".$style[0].";".$style[1].";\" ";
        $item = "<input readonly=\"readonly\" type=\"text\" class=\"formatFormItemForOrder1\" fieldid=\"".$element['fieldid']."\" ".$style."value=\"(".$element['order'].")".$element['name'].$qtipex."\" fieldname=\"".$element['name']."\" order=\"".$element['order']."\" />";
        return $item;
    }

    public function __getOdata( $odata )
    {
        return json_decode( str_replace( "'", "\"", $odata ), TRUE );
    }

    private function _format_textfield( $element )
    {
        $odata = $this->__getOdata( $element['odata'] );
        $value = htmlspecialchars( $element['dvalue'] );
        $style = $this->styleList[$odata['style']];
        $style = "style=\"".$element['style'].";".$style[0].";".$style[1].";\" ";
        $qtipex = $odata['dataType'] == "str" ? "" : "<br />格式要求：".$this->autoFormat[$odata['dataType']][$odata['dataFormat']][0];
        $qtipex .= $odata['dataType'] == "str" ? "" : "<br />例　　如：".$this->autoFormat[$odata['dataType']][$odata['dataFormat']][1];
        $item = "<input type=\"text\" ext:qtip=\"".$element['name'].$qtipex."\"otype=\"".$element['otype']."\" oname=\"".$element['name']."\" class=\"wf_field\" value=\"".$value."\" ".$style."/>";
        return $item;
    }

    private function _format_signature( $element )
    {
        $odata = $this->__getOdata( $element['odata'] );
        if ( $odata['type'] == "graph" )
        {
            $item = "<input type=\"button\" ext:qtip=\"".$element['name']."\" otype=\"".$element['otype']."\" signaturetype=\"".$odata['type']."\" oname=\"".$element['name']."\" value=\"图形签章\" style=\"".$element['style']."\" />";
            return $item;
        }
        if ( $odata['type'] == "electron" )
        {
            $item = "";
            if ( $odata['kind'] == "all" )
            {
                $width = $odata['width'] / 2;
                $element['style'] = preg_replace( "/width:(\\d+)px;/", "width:".$width."px;", $element['style'] );
            }
            if ( $odata['kind'] == "all" || $odata['kind'] == "seal" )
            {
                $item .= "<input type=\"button\" ext:qtip=\"".$element['name']."\" otype=\"".$element['otype']."\" signaturetype=\"".$odata['type']."\" oname=\"".$element['name']."\" style=\"".$element['style']."\"value=\"盖章\" />";
            }
            if ( $odata['kind'] == "all" || $odata['kind'] == "hand" )
            {
                $item .= "<input type=\"button\" ext:qtip=\"".$element['name']."\" otype=\"".$element['otype']."\" signaturetype=\"".$odata['type']."\" oname=\"".$element['name']."\" style=\"".$element['style']."\"value=\"手写\" />";
            }
        }
        return $item;
    }

    private function _format_textarea( $element )
    {
        $odata = $this->__getOdata( $element['odata'] );
        $value = htmlspecialchars( $element['dvalue'] );
        $style = $this->styleList[$odata['style']];
        $style = "style=\"".$element['style'].";".$style[0].";".$style[1]."\" ";
        $item = "<textarea id=\"wf_field_".$element['name']."\" ext:qtip=\"".$element['name']."\" class=\"wf_field\" ".$style.">".$value."</textarea>";
        if ( $odata['richText'] == "on" )
        {
            $item .= "<input type=\"hidden\" richtext=\"true\" from=\"wf_field_".$element['name']."\" />";
        }
        return $item;
    }

    private function _format_select( $element )
    {
        $odata = $this->__getOdata( $element['odata'] );
        $value = htmlspecialchars( $odata['selected'] );
        $style = $this->styleList[$odata['style']];
        $style = "style=\"".$element['style'].";".$style[0].";".$style[1]."\" ";
        $item = "<select ext:qtip=\"".$element['name']."\" oname=\"".$element['name']."\" otype=\"".$element['otype']."\" ".$style."class=\"wf_field\" >";
        if ( !is_array( $odata['dataItems'] ) )
        {
            $odata['dataItems'] = array( );
        }
        $item .= "<option value=\"\">&#160;</option>";
        foreach ( $odata['dataItems'] as $opt )
        {
            if ( $odata['dataType'] == "int" )
            {
                $selected = $value == $opt['value'] ? " selected" : "";
            }
            else
            {
                $selected = $value == $opt['name'] ? " selected" : "";
            }
            $item .= "<option value=\"".$opt['value']."\" ".$selected.">".$opt['name']."</option>";
        }
        $item .= "</select>";
        return $item;
    }

    private function _format_radio( $element )
    {
        $odata = $this->__getOdata( $element['odata'] );
        $value = htmlspecialchars( $element['dvalue'] );
        $style = $this->styleList[$odata['style']];
        $style = "style=\"display:inline-block;".$element['style'].";".$style[0].";".$style[1]."\" ";
        $item = "<span ".$style.">";
        if ( !is_array( $odata['dataItems'] ) )
        {
            $odata['dataItems'] = array( );
        }
        $id = 0;
        foreach ( $odata['dataItems'] as $k => $opt )
        {
            $checked = $element['dvalue'] == $opt['name'] ? "checked=\"checked\"" : "";
            $ck = $element['dvalue'] == $opt['name'] ? TRUE : FALSE;
            ++$id;
            $onclick = "";
            if ( $odata['dataType'] == "display" && ( !empty( $opt['display'] ) && !empty( $opt['undisplay'] ) ) )
            {
                $onclick = "onclick=\"CNOA_wf_form_checker.showHide('".$opt['display']."','".$opt['undisplay']."', {radio:this})\"";
                if ( $ck )
                {
                    $this->extHtml .= "<input type=\"hidden\" showhide=\"true\" display=\"".$opt['display']."\" undisplay=\"".$opt['undisplay']."\" fromtype=\"radio\" from=\"wf_field_".$element['id']."_".$id."\">";
                }
            }
            $item .= "<label for=\"wf_field_".$element['name']."_".$id."\"><input type=\"radio\" ".$checked." ".$onclick."\" id=\"wf_field_".$element['name']."_".$id."\" name=\"wf_field_".$element['name']."\" oname=\"".$element['name']."\" value=\"".$opt['name']."\" >".$opt['name']."</label>";
            $item .= count( $odata['dataItems'] ) == $k + 1 ? "" : "&nbsp;";
            if ( !( count( $odata['dataItems'] ) != $k + 1 ) && !( $odata['henshu'] == 2 ) )
            {
                $item .= "<br />";
            }
        }
        $item .= "</span>";
        return $item;
    }

    private function _format_checkbox( $element )
    {
        $odata = $this->__getOdata( $element['odata'] );
        $style = $this->styleList[$odata['style']];
        $style = "style=\"display:inline-block;".$element['style'].";".$style[0].";".$style[1]."\" ";
        $item = "<span ".$style.">";
        if ( !is_array( $odata['dataItems'] ) )
        {
            $odata['dataItems'] = array( );
        }
        $id = 0;
        foreach ( $odata['dataItems'] as $k => $opt )
        {
            if ( $opt['checked'] )
            {
                $ck = TRUE;
                $checked = " checked";
            }
            else
            {
                $ck = FALSE;
                $checked = "";
            }
            ++$id;
            $onclick = "";
            if ( $odata['display'] == "on" && ( !empty( $opt['display'] ) && !empty( $opt['undisplay'] ) ) )
            {
                $onclick = "CNOA_wf_form_checker.showHide('".$opt['display']."','".$opt['undisplay']."', {checkbox:this});";
                $this->extHtml .= "<input type=\"hidden\" showhide=\"true\" display=\"".$opt['display']."\" undisplay=\"".$opt['undisplay']."\" fromtype=\"checkbox\" from=\"wf_field_".$element['id']."_".$id."\" checkboxck=\"".( $ck ? "1" : "0" )."\" />";
            }
            $item .= "<label for=\"wf_field_".$element['name']."_".$id."\"><input type=\"checkbox\" onclick=\"".$onclick."\" id=\"wf_field_".$element['name']."_".$id."\" value=\"".$opt['value']."\" ".$checked."/>".$opt['name']."</label>";
            $item .= count( $odata['dataItems'] ) == $k + 1 ? "" : "&nbsp;";
            if ( !( count( $odata['dataItems'] ) != $k + 1 ) && !( $odata['henshu'] == 2 ) )
            {
                $item .= "<br />";
            }
        }
        $item .= "</span>";
        return $item;
    }

    private function _format_calculate( $element )
    {
        $odata = $this->__getOdata( $element['odata'] );
        $style = $this->styleList[$odata['style']];
        $style = "style=\"".$element['style'].";".$style[0].";".$style[1]."\" ";
        $item = "<input type=\"text\" ext:qtip=\"".$element['name']."\" class=\"wf_field\" ".$style."readonly=\"readonly\" />";
        return $item;
    }

    private function _format_macro( $element )
    {
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $odata = $this->__getOdata( $element['odata'] );
        $style = $this->styleList[$odata['style']];
        $style = "{$element['style']};{$style[0]};{$style[1]}";
        $format = $odata['dataFormat'];
        $name = "name=\"wf_field_".$element['id']."\" ";
        if ( $odata['allowedit'] == 1 && !in_array( $odata['dataType'], array( "flownum", "flowname" ) ) )
        {
            $readOnly = "";
            $extclass = " wf_field_write";
        }
        else
        {
            $readOnly = "readonly=\"readonly\"";
            $extclass = "";
        }
        $item = "<input type=\"text\"  class=\"wf_field".$extclass."\" ext:qtip=\"".$element['name']."\" otype=\"".$element['otype']."\" oname=\"".$element['name']."\" style=\"".$style."\" ";
        switch ( $odata['dataType'] )
        {
        case "month" :
            $item .= "value=\"".$this->__formatMonth( $format )."\" ".$readOnly."/>";
            break;
        case "quarter" :
            $item .= "value=\"".$this->__formatQuarter( $format )."\" ".$readOnly."/>";
            break;
        case "datetime" :
            $item .= "value=\"".$this->__formatDatetime( $format )."\" ".$readOnly."/>";
            break;
        case "flowname" :
            $item .= "value=\"".$this->flowName."\" ".$readOnly."/>";
            break;
        case "flownum" :
            $item .= "value=\"".$this->flowNumber."\" ".$readOnly."/>";
            break;
        case "createrlname" :
            $item .= "value=\"".app::loadapp( "main", "user" )->api_getUserNameByUid( $this->creatorUid )."\" ".$readOnly."/>";
            break;
        case "creatername" :
            $item .= "value=\"".app::loadapp( "main", "user" )->api_getUserTruenameByUid( $this->creatorUid )."\" ".$readOnly."/>";
            break;
        case "createrdept" :
            $dept = app::loadapp( "main", "struct" )->api_getDeptByUid( $this->creatorUid );
            $item .= "value=\"".$dept['name']."\" ".$readOnly."/>";
            break;
        case "createrjob" :
            $job = app::loadapp( "main", "job" )->api_getNameByUid( $this->creatorUid );
            $item .= "value=\"".$job."\" ".$readOnly."/>";
            break;
        case "createrstation" :
            $job = app::loadapp( "main", "station" )->api_getNameByUid( $this->creatorUid );
            $item .= "value=\"".$job."\" ".$readOnly."/>";
            break;
        case "loginlname" :
            $item .= "value=\"".$CNOA_SESSION->get( "USERNAME" )."\" ".$readOnly."/>";
            break;
        case "loginname" :
            $item .= "value=\"".$CNOA_SESSION->get( "TRUENAME" )."\" ".$readOnly."/>";
            break;
        case "logindept" :
            $dept = app::loadapp( "main", "struct" )->api_getDeptByUid( $uid );
            $item .= "value=\"".$dept['name']."\" ".$readOnly."/>";
            break;
        case "loginjob" :
            $job = app::loadapp( "main", "job" )->api_getNameById( $CNOA_SESSION->get( "JID" ) );
            $item .= "value=\"".$job."\" ".$readOnly."/>";
            break;
        case "loginstation" :
            $job = app::loadapp( "main", "station" )->api_getNameById( $CNOA_SESSION->get( "SID" ) );
            $item .= "value=\"".$job."\" ".$readOnly."/>";
            break;
        case "userip" :
            $item .= "value=\"".getip( )."\" ".$readOnly."/>";
            break;
        case "moneyconvert" :
            $item .= $readOnly." isvalid=\"true\" />";
            $fromItem = $this->api_getFieldInfoByName( $odata['from'], $this->flowId );
            $this->extHtml .= "<input type=\"hidden\" moneyconvert=\"true\" fromcount=\"".$odata['fromcount']."\" from=\"wf_field_".$fromItem['id']."\" to=\"wf_field_".$element['id']."\" />";
            break;
        case "huiqian" :
            $item = "<div class='wf_field".$extclass."' style='{$style};display:inline-block;'></div>";
        }
        return $item;
    }

    private function _format_choice( $element )
    {
        global $CNOA_SESSION;
        $value = htmlspecialchars( $value );
        $uid = $CNOA_SESSION->get( "UID" );
        $odata = $this->__getOdata( $element['odata'] );
        $format = $odata['dataFormat'];
        $style = $this->styleList[$odata['style']];
        $style = "style=\"".$element['style'].";".$style[0].";".$style[1]."\" ";
        $itemInput = "<input type=\"text\" ext:qtip=\"".$element['name']."\" otype=\"".$element['otype']."\" class=\"wf_field".$extclass."\" ".$must.$readOnly.$style."id=\"wf_field_".$element['id']."\" name=\"wf_field_".$element['id']."\" oname=\"".$element['name']."\" ";
        $itemTextA = "<textarea ext:qtip=\"".$element['name']."\" class=\"wf_field".$extclass."\" ".$must.$readOnly.$style."id=\"wf_field_".$element['id']."\" name=\"wf_field_".$element['id']."\" otype=\"".$element['otype']."\" oname=\"".$element['name']."\" ";
        $onClick = "";
        switch ( $odata['dataType'] )
        {
        case "date_sel" :
            switch ( $odata['dataFormat'] )
            {
            case 100 :
                $dtfmt = "yyyy-MM-dd";
                $dtfmtphp = "Y-m-d";
                break;
            case 200 :
                $dtfmt = "yyyy-MM";
                $dtfmtphp = "Y-m";
                break;
            case 300 :
                $dtfmt = "yy-MM-dd";
                $dtfmtphp = "y-m-d";
                break;
            case 400 :
                $dtfmt = "yyyyMMdd";
                $dtfmtphp = "Ymd";
                break;
            case 500 :
                $dtfmt = "MM-dd yyyy";
                $dtfmtphp = "m-d Y";
                break;
            case 600 :
                $dtfmt = "yyyy年MM月";
                $dtfmtphp = "Y年m月";
                break;
            case 700 :
                $dtfmt = "yyyy年MM月dd日";
                $dtfmtphp = "Y年m月d日";
                break;
            case 800 :
                $dtfmt = "MM月dd日";
                $dtfmtphp = "m月d日";
                break;
            case 900 :
                $dtfmt = "MM";
                $dtfmtphp = "m";
                break;
            case 1000 :
                $dtfmt = "yyyy.MM.dd";
                $dtfmtphp = "Y.m.d";
                break;
            case 1100 :
                $dtfmt = "MM.dd";
                $dtfmtphp = "m.d";
            }
            $onClick = "onClick=\"WdatePicker({dateFmt:'".$dtfmt."'})\" ";
            if ( $element['dvalue'] == "default" )
            {
                $value = date( $dtfmtphp, $GLOBALS['CNOA_TIMESTAMP'] );
            }
            $value = "value=\"".$value."\" ";
            $item = $itemInput.$value.$onClick.$readOnly." />";
            break;
        case "time_sel" :
            switch ( $odata['dataFormat'] )
            {
            case 100 :
                $dtfmtphp = "H:i";
                break;
            case 200 :
                $dtfmtphp = "H:i A";
                break;
            case 300 :
                $dtfmtphp = "H:i a";
            }
            $onClick = "onClick=\"wfTimePicker.show(this, ".intval( $odata['dataFormat'] ).")\" ";
            if ( $element['dvalue'] == "default" )
            {
                $value = date( $dtfmtphp, $GLOBALS['CNOA_TIMESTAMP'] );
                $value = str_replace( array( "am", "pm" ), array( "上午", "下午" ), $value );
            }
            $value = "value=\"".$value."\" ";
            $item = $itemInput.$value.$onClick.$readOnly." />";
            break;
        case "dept_sel" :
            $value = "value=\"".$value."\" ";
            $item = $itemInput.$value." />".$onClick;
            break;
        case "depts_sel" :
            $item = $itemTextA." />".$value."</textarea>".$onClick;
            break;
        case "station_sel" :
            $value = "value=\"".$value."\" ";
            $item = $itemInput.$value." />".$onClick;
            break;
        case "stations_sel" :
            $item = $itemTextA." />".$value."</textarea>".$onClick;
            break;
        case "job_sel" :
            $value = "value=\"".$value."\" ";
            $item = $itemInput.$value." />".$onClick;
            break;
        case "jobs_sel" :
            $item = $itemTextA." />".$value."</textarea>".$onClick;
            break;
        case "user_sel" :
            $value = "value=\"".$value."\" ";
            $item = $itemInput.$value." />".$onClick;
            break;
        case "users_sel" :
            $item = $itemTextA." />".$value."</textarea>".$onClick;
        }
        return $item;
    }

    private function _format_detailtable( $element )
    {
        global $CNOA_DB;
        $odata = $this->__getOdata( $element['odata'] );
        ( $this, $element, $odata );
        $wfDetailTableFormaterForDeal = new wfDetailTableFormaterForPreview( );
        $item = $wfDetailTableFormaterForDeal->parseTable( );
        return $item;
    }

    private function _format_datasource( $element )
    {
        $item = "<input type=\"button\" value=\"".$element['name']."\" style=\"visibility:hidden\" />";
        return $item;
    }

    private function __getStylesList( )
    {
        global $CNOA_DB;
        $styleList = $CNOA_DB->db_select( "*", $this->t_set_form_style, "WHERE 1" );
        if ( !is_array( $styleList ) )
        {
            $styleList = array( );
        }
        $list = array( );
        foreach ( $styleList as $v )
        {
            $style1 = "";
            $style1 .= "font-family:".$this->f_font[$v['font']].";";
            $style1 .= "font-size:".$v['size']."px;";
            $style1 .= "color:#".$v['color'].";";
            if ( $v['border'] == 0 )
            {
                $style2 = "border:1px solid #000;border-width:0 0 1px 0;";
            }
            else if ( $v['border'] == 1 )
            {
                $style2 = "border:1px solid #000;";
            }
            else if ( $v['border'] == 2 )
            {
                $style2 = "border:1px solid #000;border-width:0;";
            }
            $style1 .= $v['italic'] == 0 ? "" : "font-style:italic;";
            $style1 .= $v['bold'] == 0 ? "" : "font-weight:bold;";
            $list[$v['sid']] = array(
                $style1,
                $style2
            );
        }
        return $list;
    }

    private function __formatMonth( $format )
    {
        switch ( $format )
        {
        case "100" :
            $data = date( "m月", $GLOBALS['CNOA_TIMESTAMP'] );
            return $data;
        case "200" :
            $data = date( "Ym", $GLOBALS['CNOA_TIMESTAMP'] );
            return $data;
        case "300" :
            $data = date( "Y-m", $GLOBALS['CNOA_TIMESTAMP'] );
            return $data;
        case "400" :
            $data = date( "Y年m月", $GLOBALS['CNOA_TIMESTAMP'] );
        }
        return $data;
    }

    private function __formatQuarter( $format )
    {
        $quarter = floor( ( date( "n", $GLOBALS['CNOA_TIMESTAMP'] ) - 1 ) / 3 ) + 1;
        switch ( $format )
        {
        case "100" :
            $data = "Q".$quarter;
            return $data;
        case "200" :
            $data = "第".$quarter."季";
        }
        return $data;
    }

}

class wfDetailTableFormaterForPreview extends wfCommon
{

    private $wfFFFD = NULL;
    private $tableEl = NULL;
    private $odata = NULL;
    private $tableRule = NULL;
    private $tableData = NULL;
    private $fieldList = NULL;
    private $ruleList = NULL;
    private $nowLine = NULL;
    private $tdNum = 0;

    public function __construct( $wfFFFD, $tableEl, $odata )
    {
        $this->wfFFFD = $wfFFFD;
        $this->tableEl = $tableEl;
        $this->odata = $odata;
    }

    public function __destruct( )
    {
    }

    public function parseTable( )
    {
        $item = "";
        if ( !empty( $this->odata['rownumber'] ) )
        {
            $i = 1;
            for ( ; do
 {
 $i <= $this->odata['rownumber']; ++$i, )
                {
                    $this->nowLine = $i;
                    $item .= $this->parseTr( $i, $this->odata['items'] );
                    break;
                }
            } while ( 1 );
        }
        else
        {
            $this->nowLine = 1;
            $item = $this->parseTr( 1, $this->odata['items'] );
        }
        return $item;
    }

    private function parseTr( $rowNum, $items )
    {
        $this->tdNum = 0;
        $tr = "<tr style=\"height:".$this->odata['height']."px;\" >";
        foreach ( $this->odata['items'] as $v )
        {
            $tr .= $this->parseTd( $v );
        }
        $tr .= "<td align=\"center\" valign=\"middle\" style=\"border:none;\" width=\"20\">";
        $tr .= "<img class=\"wf_row_jia\" src=\"resources/images/cnoa/wf-dt-jia.gif\" ext:qtip=\"添加一行\" />";
        $tr .= "</td>";
        $tr .= "</tr>";
        return $tr;
    }

    private function parseTd( $element )
    {
        $tdattr = $this->odata['tdattrs'][$this->tdNum];
        $td = "<td align='".$tdattr['align']."' ";
        $td .= "valign='".$tdattr['valign']."' ";
        $td .= "height='".$this->odata['height']."' ";
        $td .= "width='".$tdattr['width']."' ";
        $td .= "class='".str_replace( "selectTdClass", "", $tdattr['class'] )."' ";
        $td .= "style='word-break: break-all;".$tdattr['style']."' ";
        $td .= ">";
        eval( "\$td .= \$this->_format_".$element['odata']['type']."(\$element);" );
        $td .= "</td>";
        $this->tdNum++;
        return $td;
    }

    private function _format_textfield( $element )
    {
        $odata = $element['odata'];
        $width = $odata['width'];
        $fieldid = $this->fieldList[$element['name']]['id'];
        if ( $rule['hide'] )
        {
            $item = $this->__getFillDiv( $odata['width'] );
            return $item;
        }
        $value = htmlspecialchars( $odata['dvalue'] );
        $qtipex = $odata['dataType'] == "str" ? "" : "<br />格式要求：".$this->autoFormat[$odata['dataType']][$odata['dataFormat']][0];
        $qtipex .= $odata['dataType'] == "str" ? "" : "<br />例　　如：".$this->autoFormat[$odata['dataType']][$odata['dataFormat']][1];
        $style = $this->wfFFFD->styleList[$odata['style']];
        $style = "style=\"".$element['style'].";".$style[0].";".$style[1].";width:".$width."px;\" ";
        $item = "<input type=\"text\" ".( $odata['dataType'] == "str" ? "" : "autoformat=\"".$this->autoFormatPHP[$odata['dataType']][$odata['dataFormat']]."\" " )."ext:qtip=\"".$element['name'].$qtipex."\"class=\"wf_field\" value=\"".$value."\" ".$style.$readOnly." />";
        return $item;
    }

    private function _format_textarea( $element )
    {
        $odata = $element['odata'];
        $width = $odata['width'];
        $height = $this->odata['height'];
        $fieldid = $this->fieldList[$element['name']]['id'];
        $value = htmlspecialchars( $odata['dvalue'] );
        $style = $this->wfFFFD->styleList[$odata['style']];
        $style = "style=\"".$element['style'].";".$style[0].";".$style[1].";width:".$width."px; height:".$height."px;\" ";
        $item = "<textarea ext:qtip=\"".$element['name']."\" class=\"wf_field".$extclass."\" ".$style.">".$value."</textarea>";
        return $item;
    }

    private function _format_select( $element )
    {
        $odata = $element['odata'];
        $width = $odata['width'];
        $height = $this->odata['height'];
        $value = htmlspecialchars( $odata['dvalue'] );
        $style = $this->wfFFFD->styleList[$odata['style']];
        $style = "style=\"".$element['style'].";".$style[0].";".$style[1].";width:".$width."px;\" ";
        $item = "<select ".$style."ext:qtip=\"".$element['name']."\" class=\"wf_field\" >";
        if ( !is_array( $odata['item'] ) )
        {
            $odata['item'] = array( );
        }
        $item .= "<option value=\"\">&#160;</option>";
        foreach ( $odata['item'] as $opt )
        {
            $selected = $value == $opt['pvalue'] ? " selected" : "";
            $item .= "<option value=\"".$opt['pvalue']."\" ".$selected.">".$opt['pname']."</option>";
        }
        $item .= "</select>";
        return $item;
    }

    private function _format_radio( $element )
    {
        $odata = $element['odata'];
        $width = $odata['width'];
        $height = $this->odata['height'];
        $style = $this->wfFFFD->styleList[$odata['style']];
        $style = "style=\"padding:3px;display:block;".$element['style'].";".$style[0].";".$style[1].";width:".$width."px;\" ";
        $item = "<span ".$style.">";
        if ( !is_array( $odata['item'] ) )
        {
            $odata['item'] = array( );
        }
        $id = 0;
        $fieldid = md5( uniqid( ) );
        foreach ( $odata['item'] as $k => $opt )
        {
            $checked = $odata['dvalue'] == $opt['pvalue'] ? "checked=\"checked\"" : "";
            ++$id;
            $item .= "<label for=\"wf_detail_".$this->nowLine."_".$fieldid."_".$id."\"><input type=\"radio\" name=\"wf_detail_".$this->nowLine."_".$fieldid."\" id=\"wf_detail_".$this->nowLine."_".$fieldid."_".$id."\" ".$checked." value=\"".$opt['pvalue']."\" >".$opt['pname']."</label>";
            $item .= count( $odata['item'] ) == $k + 1 ? "" : "&nbsp;";
        }
        $item .= "</span>";
        return $item;
    }

    private function _format_checkbox( $element )
    {
        $odata = $element['odata'];
        $width = $odata['width'];
        $height = $this->odata['height'];
        $fieldid = md5( uniqid( ) );
        $style = $this->wfFFFD->styleList[$odata['style']];
        $style = "style=\"padding:3px;display:block;".$element['style'].";".$style[0].";".$style[1].";width:".$width."px;\" ";
        $item = "<span ".$style.">";
        if ( !is_array( $odata['item'] ) )
        {
            $odata['item'] = array( );
        }
        $id = 0;
        foreach ( $odata['item'] as $k => $opt )
        {
            $checked = $opt['pvalue'] ? " checked=\"checked\" " : "";
            ++$id;
            $item .= "<label for=\"wf_detail_".$this->nowLine."_".$fieldid."_".$id."\"><input type=\"checkbox\"id=\"wf_detail_".$this->nowLine."_".$fieldid."_".$id."\" value=\"".$opt['pvalue']."\" ".$checked."/>".$opt['pname']."</label>";
            $item .= count( $odata['item'] ) == $k + 1 ? "" : "&nbsp;";
        }
        $item .= "</span>";
        return $item;
    }

    private function _format_calculate( $element )
    {
        $odata = $element['odata'];
        $width = $odata['width'];
        $height = $this->odata['height'];
        $style = $this->wfFFFD->styleList[$odata['style']];
        $style = "style=\"".$element['style'].";".$style[0].";".$style[1].";width:".$width."px;\" ";
        $item = "<input type=\"text\" ext:qtip=\"".$element['name']."\" otype=\"".$odata['type']."\" oname=\"".$element['name']."\" class=\"wf_field\" ".$style."readonly=\"readonly\" value=\"".$value."\" />";
        $this->wfFFFD['extHtml'] .= "<input type=\"hidden\" calculate=\"true\" detail=\"true\" gongshi=\"".$gongshi."\" to=\"wf_detail_".$this->nowLine."_".$fieldid."\" />";
        return $item;
    }

    private function _format_macro( $element )
    {
        global $CNOA_SESSION;
        $odata = $element['odata'];
        $width = $odata['width'];
        $height = $this->odata['height'];
        $fieldid = md5( uniqid( ) );
        $uid = $CNOA_SESSION->get( "UID" );
        $style = $this->wfFFFD->styleList[$odata['style']];
        $style = "style=\"".$element['style'].";".$style[0].";".$style[1].";width:".$width."px;\" ";
        $format = $odata['dataFormat'];
        $styleA = $element['style'];
        $styleB = $this->styleList[$odata['style']];
        if ( $odata['allowedit'] == 1 )
        {
            $readOnly = "";
            $extclass = " wf_field_write";
        }
        else
        {
            $readOnly = "readonly=\"readonly\"";
            $extclass = "";
        }
        $item = "<input type=\"text\"  class=\"wf_field".$extclass."\" ext:qtip=\"".$element['name']."\" ".$style;
        switch ( $odata['dataType'] )
        {
        case "month" :
            $item .= "value=\"".$this->__formatMonth( $format )."\" ".$readOnly."/>";
            break;
        case "quarter" :
            $item .= "value=\"".$this->__formatQuarter( $format )."\" ".$readOnly."/>";
            break;
        case "datetime" :
            $item .= "value=\"".$this->__formatDatetime( $format )."\" ".$readOnly."/>";
            break;
        case "flowname" :
            $item .= "value=\"".$this->wfFFFD->flowNumber."\" ".$readOnly."/>";
            break;
        case "flownum" :
            $item .= "value=\"".$this->wfFFFD->flowNumber."\" ".$readOnly."/>";
            break;
        case "creatername" :
            $item .= "value=\"".app::loadapp( "main", "user" )->api_getUserTruenameByUid( $this->wfFFFD->creatorUid )."\" ".$readOnly."/>";
            break;
        case "createrdept" :
            $dept = app::loadapp( "main", "struct" )->api_getDeptByUid( $this->wfFFFD->creatorUid );
            $item .= "value=\"".$dept['name']."\" ".$readOnly."/>";
            break;
        case "createrjob" :
            $job = app::loadapp( "main", "job" )->api_getNameByUid( $this->wfFFFD->creatorUid );
            $item .= "value=\"".$job."\" ".$readOnly."/>";
            break;
        case "createrstation" :
            $job = app::loadapp( "main", "station" )->api_getNameByUid( $this->wfFFFD->creatorUid );
            $item .= "value=\"".$job."\" ".$readOnly."/>";
            break;
        case "loginname" :
            $item .= "value=\"".$CNOA_SESSION->get( "TRUENAME" )."\" ".$readOnly."/>";
            break;
        case "logindept" :
            $dept = app::loadapp( "main", "struct" )->api_getDeptByUid( $uid );
            $item .= "value=\"".$dept['name']."\" ".$readOnly."/>";
            break;
        case "loginjob" :
            $job = app::loadapp( "main", "job" )->api_getNameById( $CNOA_SESSION->get( "JID" ) );
            $item .= "value=\"".$job."\" ".$readOnly."/>";
            break;
        case "loginstation" :
            $job = app::loadapp( "main", "station" )->api_getNameById( $CNOA_SESSION->get( "SID" ) );
            $item .= "value=\"".$job."\" ".$readOnly."/>";
            break;
        case "userip" :
            $item .= "value=\"192.168.1.100\" ".$readOnly."/>";
            break;
        case "moneyconvert" :
            $item .= $readOnly." isvalid=\"true\" />";
            $fromItem = $this->api_getFieldInfoByName( $odata['from'], $this->flowId );
            $this->extHtml .= "<input type=\"hidden\" moneyconvert=\"true\" fromcount=\"".$odata['fromcount']."\" from=\"wf_detail_".$fromItem['id']."\" to=\"wf_detail_".$fieldid."\" />";
            break;
        case "snum" :
            $snumber = $odata['expression'];
            $rowNumber = str_replace( "{x}", "{$this->nowLine}", $snumber );
            $item .= "value=\"".$rowNumber."\" snumber=\"".addslashes( $snumber )."\" ".$readOnly."/>";
        }
        return $item;
    }

    private function _format_choice( $element )
    {
        global $CNOA_SESSION;
        $odata = $element['odata'];
        $width = $odata['width'];
        $height = $this->odata['height'];
        $fieldid = md5( uniqid( ) );
        $uid = $CNOA_SESSION->get( "UID" );
        $format = $odata['dataFormat'];
        $style = $this->wfFFFD->styleList[$odata['style']];
        $style = "style=\"".$element['style'].";".$style[0].";".$style[1].";width:".$width."px;\" ";
        $itemInput = "<input type=\"text\" ".$style."ext:qtip=\"".$element['name']."\" otype=\"".$element['otype']."\" class=\"wf_field".$extclass."\" value=\"".$value."\" ";
        $itemTextA = "<textarea ".$style."ext:qtip=\"".$element['name']."\" class=\"wf_field".$extclass."\" ";
        switch ( $odata['dataType'] )
        {
        case "date_sel" :
            switch ( $odata['dataFormat'] )
            {
            case 100 :
                $dtfmt = "yyyy-MM-dd";
                break;
            case 200 :
                $dtfmt = "yyyy-MM";
                break;
            case 300 :
                $dtfmt = "yy-MM-dd";
                break;
            case 400 :
                $dtfmt = "yyyyMMdd";
                break;
            case 500 :
                $dtfmt = "MM-dd yyyy";
                break;
            case 600 :
                $dtfmt = "yyyy年MM月";
                break;
            case 700 :
                $dtfmt = "yyyy年MM月dd日";
                break;
            case 800 :
                $dtfmt = "MM月dd日";
                break;
            case 900 :
                $dtfmt = "MM";
                break;
            case 1000 :
                $dtfmt = "yyyy.MM.dd";
                break;
            case 1100 :
                $dtfmt = "MM.dd";
            }
            $onClick = "onClick=\"WdatePicker({dateFmt:'".$dtfmt."'})\" ";
            $item = $itemInput.$onClick.$readOnly." />";
            break;
        case "time_sel" :
            $onClick = "onClick=\"wfTimePicker.show(this, ".intval( $odata['dataFormat'] ).")\" ";
            $item = $itemInput.$onClick.$readOnly." />";
            break;
        case "dept_sel" :
            $onClick = "<button \">选择</button>";
            $item = $itemInput." />".$onClick;
            break;
        case "depts_sel" :
            $onClick = "<button >选择</button>";
            $item = $itemTextA." />".$value."</textarea>".$onClick;
            break;
        case "station_sel" :
            $onClick = "<button >选择</button>";
            $item = $itemInput." />".$onClick;
            break;
        case "stations_sel" :
            $onClick = "<button >选择</button>";
            $item = $itemTextA." />".$value."</textarea>".$onClick;
            break;
        case "job_sel" :
            $onClick = "<button >选择</button>";
            $item = $itemInput." />".$onClick;
            break;
        case "jobs_sel" :
            $onClick = "<button>选择</button>";
            $item = $itemTextA." />".$value."</textarea>".$onClick;
            break;
        case "user_sel" :
            $onClick = "<button >选择</button>";
            $item = $itemInput." />".$onClick;
            break;
        case "users_sel" :
            $onClick = "<button >选择</button>";
            $item = $itemTextA." />".$value."</textarea>".$onClick;
        }
        return $item;
    }

    private function __getFillDiv( $width )
    {
        return "<div style=\"width:".$width."px;\">&nbsp;</div>";
    }

}

?>
