<?php

class wfFlowUseFormM extends wfForm
{

    private $flowId = 0;
    private $uFlowId = 0;
    private $uStepId = 0;
    private $fields = array( );
    private $stepFields = array( );
    private $curStepPermit = array( );
    private $detailFields = array( );
    private $fieldOption = array( );
    private $items = array( );
    private $fieldValue = NULL;
    private $attach = array( );

    const BG_MUST = "#FFD0D0";
    const BG_WRITE = "#EAF7EA";
    const BG_WIDGET = "#FEEDDE";
    const NAME_PRE = "wf_field_";
    const ITEM_NAME_PRE = "wf_field_item_";

    public function __construct( )
    {
        $this->flowId = intval( getpar( $_POST, "flowId" ) );
        $this->uFlowId = intval( getpar( $_POST, "uFlowId" ) );
        $this->uStepId = intval( getpar( $_POST, "uStepId" ) );
        $this->from = getpar( $_POST, "from" );
        $this->_getStepInfo( );
    }

    public function run( )
    {
        $task = getpar( $_GET, "task", "" );
        switch ( $task )
        {
        case "getDetail" :
            $this->_getDetail( );
        }
    }

    private function _getDetail( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $flowId = intval( getpar( $_POST, "flowId", 0 ) );
        $uFlowId = intval( getpar( $_POST, "uFlowId", 0 ) );
        $uid = $CNOA_SESSION->get( "UID" );
        if ( !empty( $this->uFlowId ) || !empty( $this->flowId ) )
        {
            $widgetData = $CNOA_DB->db_getone( "*", "z_wf_t_".$this->flowId, "WHERE `uFlowId` = '".$this->uFlowId."'" );
            $this->uFlowInfo = $CNOA_DB->db_getone( array( "flowNumber", "flowName", "uid", "level", "status", "reason", "posttime", "attach" ), $this->t_use_flow, "WHERE `uFlowId` = '".$this->uFlowId."'" );
            $this->uFlowInfo['posttime'] = formatdate( $this->uFlowInfo['posttime'], "Y-m-d H:i" );
            $this->uFlowInfo['faqiUname'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $this->uFlowInfo['uid'] );
            $this->faqiUid = $this->uFlowInfo['uid'];
            $attachs = json_decode( $this->uFlowInfo['attach'], TRUE );
            if ( !empty( $attachs ) )
            {
                ( );
                $fs = new fs( );
                $attachs = $fs->getDownLoadFileListByIds( $attachs );
                $isA = FALSE;
                if ( $this->curStepPermit['allowAttachEdit'] || $this->curStepPermit['allowAttachView'] || $this->curStepPermit['allowAttachDown'] )
                {
                    $isA = TRUE;
                }
                foreach ( $attachs as $k => $v )
                {
                    if ( 15 < mb_strlen( $v['name'], "utf-8" ) )
                    {
                        $v['name'] = mb_substr( $v['name'], 0, 13, "utf-8" )."...";
                    }
                    if ( $isA )
                    {
                        $v['name'] = "<a target=\"_blank\" href=\"".$v['url']."\">{$v['name']}</a>";
                    }
                    unset( $v['url'] );
                    $attachs[$k] = $v;
                }
                $this->attach = $attachs;
            }
        }
        else
        {
            $this->uFlowInfo['flowNumber'] = $CNOA_DB->db_getfield( "nameRule", $this->t_set_flow, "WHERE `flowId`=".$this->flowId );
            $this->faqiUid = $CNOA_SESSION->get( "UID" );
        }
        foreach ( $this->fields as $v )
        {
            $fid = $v['id'];
            if ( !isset( $this->stepFields[$fid] ) && $this->stepFields[$fid]['hide'] == 1 )
            {
                $this->fieldValue = isset( $widgetData["T_".$fid] ) ? $widgetData["T_".$fid] : "";
                switch ( $v['otype'] )
                {
                case "textfield" :
                    $this->_formatTextfield( $v );
                    break;
                case "radio" :
                    $this->_formatRadiofield( $v );
                    break;
                case "checkbox" :
                    $this->_formatCheckfield( $v );
                    break;
                case "select" :
                    $this->_formatSelectfield( $v );
                    break;
                case "textarea" :
                    $this->_formatTextareafield( $v );
                    break;
                case "macro" :
                    $this->_formatMacro( $v );
                    break;
                case "choice" :
                    $this->_formatChoice( $v );
                    break;
                case "signature" :
                    $this->_formatSignature( $v );
                }
            }
        }
        $response['data'] = $this->uFlowInfo;
        $response['items'] = $this->items;
        $response['attach'] = $this->attach;
        $response['permit'] = $this->curStepPermit;
        echo json_encode( $response );
    }

    private function _getStepInfo( )
    {
        if ( $this->from == "todo" )
        {
            ( $this->uFlowId );
            $wfCache = new wfCache( );
            $this->fields = $wfCache->getFlowFields( );
            $this->stepFields = $wfCache->getStepFields( $this->uStepId, self::FIELD_RULE_NORMAL );
            $this->detailFields = $wfCache->getDetailFields( );
            $this->curStepPermit = $wfCache->getStepByStepId( $this->uStepId );
        }
        else if ( $this->from == "new" )
        {
            global $CNOA_DB;
            $this->fields = $CNOA_DB->db_select( "*", $this->t_set_field, "WHERE `flowId`='".$this->flowId."'" );
            $dblist = $CNOA_DB->db_select( "*", $this->t_set_step_fields, "WHERE `flowId` = '".$this->flowId."' AND `stepId`={$this->uStepId}" );
            if ( !is_array( $dblist ) )
            {
                $dblist = array( );
            }
            foreach ( $dblist as $v )
            {
                $this->stepFields[$v['fieldId']] = $v;
            }
            $this->curStepPermit = $CNOA_DB->db_getone( "*", $this->t_set_step, "WHERE `flowId`=".$this->flowId." AND `stepId`={$this->uStepId}" );
        }
        if ( !is_array( $this->fields ) )
        {
            $this->fields = array( );
        }
        if ( !is_array( $this->stepFields ) )
        {
            $this->stepFields = array( );
        }
        if ( !is_array( $this->curStepPermit ) )
        {
            $this->curStepPermit = array( );
        }
    }

    private function _setItemConf( $field )
    {
        $this->fieldOption = $this->stepFields[$field['id']];
        $itemConf = array( );
        if ( $this->fieldOption['must'] )
        {
            $itemConf['style'] = "background:".self::BG_MUST;
            $itemConf['name'] = self::NAME_PRE.$field['id'];
            $itemConf['required'] = TRUE;
        }
        else if ( $this->fieldOption['write'] )
        {
            $itemConf['style'] = "background:".self::BG_WRITE;
            $itemConf['name'] = self::NAME_PRE.$field['id'];
        }
        else
        {
            $itemConf['readOnly'] = TRUE;
            $itemConf['xtype'] = "textfield";
        }
        $itemConf['label'] = "{$field['name']}:";
        return $itemConf;
    }

    private function _formatTextfield( $field )
    {
        $itemConf = $this->_setItemConf( $field );
        $odata = $this->__getOdata( $field['odata'] );
        if ( $odata['dataType'] != "str" && ( $this->fieldOption['must'] || $this->fieldOption['write'] ) )
        {
            $itemConf['xtype'] = "cnoanumberfield";
            $this->_formatText4Setting( $itemConf, $odata['dataType'], $odata['dataFormat'] );
        }
        else
        {
            $itemConf['xtype'] = "textfield";
        }
        $itemConf['value'] = $this->fieldValue;
        $this->items[] = $itemConf;
    }

    private function _formatRadiofield( $field )
    {
        $itemConf = $this->_setItemConf( $field );
        $itemConf['value'] = $this->fieldValue;
        if ( $this->fieldOption['must'] || $this->fieldOption['write'] )
        {
            $itemConf['xtype'] = "cnoaselect";
            $itemConf['selectType'] = "radio";
            $itemConf['clearIcon'] = FALSE;
            $odata = $this->__getOdata( $field['odata'] );
            foreach ( $odata['dataItems'] as $item )
            {
                $radioItem = array( );
                $radioItem['xtype'] = "radiofield";
                $radioItem['name'] = self::ITEM_NAME_PRE.$field['id'];
                $radioItem['label'] = $item['name'];
                $radioItem['value'] = $item['name'];
                if ( $item['name'] == $this->fieldValue )
                {
                    $radioItem['checked'] = TRUE;
                }
                $itemConf['selectItems'][] = $radioItem;
            }
        }
        $this->items[] = $itemConf;
    }

    private function _formatCheckfield( $field )
    {
        $itemConf = $this->_setItemConf( $field );
        $value = json_decode( $this->fieldValue, TRUE );
        $checked = array( );
        if ( !is_array( $value ) )
        {
            $value = array( );
        }
        foreach ( $value as $v )
        {
            if ( !empty( $v ) )
            {
                $checked[] = $v;
            }
        }
        $itemConf['value'] = implode( ",", $checked );
        if ( $this->fieldOption['must'] || $this->fieldOption['write'] )
        {
            $itemConf['name'] = "wf_fieldC_".$field['id'];
            $itemConf['xtype'] = "cnoaselect";
            $itemConf['selectType'] = "checkbox";
            $itemConf['clearIcon'] = FALSE;
            $odata = $this->__getOdata( $field['odata'] );
            foreach ( $odata['dataItems'] as $item )
            {
                $checkItem = array( );
                $checkItem['xtype'] = "checkboxfield";
                $checkItem['name'] = "wf_fieldC_".$field['id'];
                $checkItem['label'] = $item['name'];
                $checkItem['value'] = $item['name'];
                if ( in_array( $item['name'], $checked ) )
                {
                    $checkItem['checked'] = TRUE;
                }
                $itemConf['selectItems'][] = $checkItem;
            }
        }
        $this->items[] = $itemConf;
    }

    private function _formatSelectfield( $field )
    {
        $itemConf = $this->_setItemConf( $field );
        $itemConf['value'] = $this->fieldValue;
        if ( $this->fieldOption['must'] || $this->fieldOption['write'] )
        {
            $itemConf['xtype'] = "selectfield";
            $itemConf['autoSelect'] = FALSE;
            $itemConf['valueField'] = "item";
            $itemConf['displayField'] = "item";
            $odata = $this->__getOdata( $field['odata'] );
            $data = array( );
            foreach ( $odata['dataItems'] as $item )
            {
                $data[] = array(
                    "item" => $item['name']
                );
            }
            $itemConf['store']['data'] = $data;
        }
        $this->items[] = $itemConf;
    }

    private function _formatTextareafield( $field )
    {
        $itemConf = $this->_setItemConf( $field );
        $itemConf['xtype'] = "textareafield";
        $itemConf['value'] = $this->fieldValue;
        $this->items[] = $itemConf;
    }

    private function _formatMacro( $field )
    {
        $itemConf = $this->_setItemConf( $field );
        $itemConf['xtype'] = "textfield";
        $itemConf['readOnly'] = TRUE;
        $odata = $this->__getOdata( $field['odata'] );
        if ( $odata['dataType'] == "moneyconvert" )
        {
            return;
        }
        if ( $this->fieldOption['must'] || $this->fieldOption['write'] )
        {
            $itemConf['style'] = "background:".self::BG_WIDGET;
            $itemConf['value'] = $this->_getMacroValue( $odata );
            if ( $odata['allowedit'] == 1 && !in_array( $odata['dataType'], array( "flowname", "flownum" ) ) )
            {
                unset( $itemConf['readOnly'] );
            }
            if ( $odata['dataType'] == "loginname" )
            {
                global $CNOA_SESSION;
                unset( $itemConf['name'] );
                $this->items[] = $itemConf;
                $itemConf = $this->_setItemConf( $field );
                $itemConf['xtype'] = "hiddenfield";
                $itemConf['value'] = $CNOA_SESSION->get( "UID" );
            }
            if ( !( $odata['dataType'] == "moneyconvert" ) )
            {
                break;
            }
            else
            {
            }
        }
        else
        {
            do
            {
                if ( $odata['dataType'] == "loginname" && !empty( $this->fieldValue ) )
                {
                    $this->fieldValue = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $this->fieldValue );
                }
                $itemConf['value'] = $this->fieldValue;
            } while ( 0 );
            $this->items[] = $itemConf;
        }
    }

    private function _formatChoice( $field )
    {
        $itemConf = $this->_setItemConf( $field );
        $odata = $this->__getOdata( $field['odata'] );
        if ( ( $this->fieldOption['must'] || $this->fieldOption['write'] ) && !in_array( $odata['dataType'], array(
            "date_sel",
            "time_sel",
            "photo_upload"
        ) ) )
        {
            $this->items[] = array(
                "name" => self::NAME_PRE.$field['id'],
                "xtype" => "hiddenfield",
                "value" => $this->fieldValue
            );
            $itemConf['xtype'] = "selector";
            $itemConf['id'] = $itemConf['name'];
            unset( $itemConf['name'] );
        }
        $value = "";
        switch ( $odata['dataType'] )
        {
        case "user_sel" :
            $itemConf['itemType'] = "radiofield";
            $itemConf['selectType'] = "user";
            if ( empty( $this->fieldValue ) )
            {
                break;
            }
            $value = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $this->fieldValue );
            break;
        case "users_sel" :
            $itemConf['itemType'] = "checkboxfield";
            $itemConf['selectType'] = "user";
            if ( empty( $this->fieldValue ) )
            {
                break;
            }
            $value = app::loadapp( "main", "user" )->api_getUserTruenameByUid( explode( ",", $this->fieldValue ) );
            $value = implode( ",", $value );
            break;
        case "job_sel" :
            $itemConf['itemType'] = "radiofield";
            $itemConf['selectType'] = "job";
            if ( empty( $this->fieldValue ) )
            {
                break;
            }
            $value = app::loadapp( "main", "job" )->api_getNameByUid( $this->fieldValue );
            break;
        case "jobs_sel" :
            $itemConf['itemType'] = "checkboxfield";
            $itemConf['selectType'] = "jobs";
            if ( empty( $this->fieldValue ) )
            {
                break;
            }
            $value = app::loadapp( "main", "job" )->api_getNamesByIds( explode( ",", $this->fieldValue ) );
            $value = implode( ",", $value );
            break;
        case "dept_sel" :
            $itemConf['itemType'] = "radiofield";
            $itemConf['selectType'] = "dept";
            if ( empty( $this->fieldValue ) )
            {
                break;
            }
            $value = app::loadapp( "main", "struct" )->api_getNameById( $this->fieldValue );
            break;
        case "depts_sel" :
            $itemConf['itemType'] = "checkboxfield";
            $itemConf['selectType'] = "depts";
            if ( empty( $this->fieldValue ) )
            {
                break;
            }
            $value = app::loadapp( "main", "struct" )->api_getNamesByIds( explode( ",", $this->fieldValue ) );
            $value = implode( ",", $value );
            break;
        case "station_sel" :
            $itemConf['itemType'] = "radiofield";
            $itemConf['selectType'] = "station";
            if ( empty( $this->fieldValue ) )
            {
                break;
            }
            $value = app::loadapp( "main", "station" )->api_getNameById( $this->fieldValue );
            break;
        case "stations_sel" :
            $itemConf['itemType'] = "checkboxfield";
            $itemConf['selectType'] = "stations";
            if ( empty( $this->fieldValue ) )
            {
                break;
            }
            $value = app::loadapp( "main", "station" )->api_getNamesByIds( explode( ",", $this->fieldValue ) );
            $value = implode( ",", $value );
            break;
        case "time_sel" :
            if ( $this->fieldOption['must'] || $this->fieldOption['write'] )
            {
                $itemConf['xtype'] = "timepickerfield";
                $format = $this->_getTimeFormat( $odata['dataFormat'] );
                $itemConf['dateFormat'] = $format;
                $itemConf['showDate'] = FALSE;
                if ( !empty( $this->fieldValue ) )
                {
                    $itemConf['timestamp'] = strtotime( str_replace( array( "下午", "上午" ), array( "pm", "am" ), date( $this->fieldValue ) ) )."000";
                }
                else if ( $field['dvalue'] == "default" )
                {
                    $itemConf['timestamp'] = time( )."000";
                    $this->fieldValue = str_replace( array( "pm", "am" ), array( "下午", "上午" ), date( $format ) );
                }
            }
            $value = $this->fieldValue;
            break;
        case "date_sel" :
            if ( $this->fieldOption['must'] || $this->fieldOption['write'] )
            {
                $itemConf['xtype'] = "timepickerfield";
                $format = $this->_getDataFormat( $odata['dataFormat'] );
                $itemConf['dateFormat'] = $format['format'];
                $itemConf['showTime'] = $format['showTime'];
                $itemConf['showDate'] = $format['showDate'];
                if ( !empty( $this->fieldValue ) )
                {
                    $itemConf['timestamp'] = strtotime( str_replace( array( "年", "月", "日" ), array( "-", "-", "" ), $this->fieldValue ) )."000";
                }
                else if ( $field['dvalue'] == "default" )
                {
                    $itemConf['timestamp'] = time( )."000";
                    $this->fieldValue = date( $format['format'] );
                }
            }
            $value = $this->fieldValue;
            break;
        case "photo_upload" :
            if ( $this->fieldOption['must'] || $this->fieldOption['write'] )
            {
                $itemConf['xtype'] = "uploadfiled";
                $itemConf['name'] = "wf_fieldpic_".$field['id'];
                $itemConf['labelAlign'] = "left";
                $itemConf['url'] = "m.php?app=wf&func=flow&action=use&modul=todo&task=uploadfile";
            }
            else
            {
                $itemConf['xtype'] = "imagefield";
                $itemConf['src'] = "{$this->fieldValue}";
            }
            $value = $this->fieldValue;
        }
        $itemConf['value'] = $value;
        $this->items[] = $itemConf;
    }

    private function _formatSignature( $field )
    {
        $odata = $this->__getOdata( $field['odata'] );
        if ( $odata['type'] != "graph" )
        {
            return;
        }
        $itemConf = $this->_setItemConf( $field );
        unset( $itemConf['name'] );
        unset( $itemConf['required'] );
        $itemConf['id'] = "wf_fieldS_".$field['id'];
        $value = $imgInfo = "";
        if ( !empty( $this->fieldValue ) )
        {
            $imgInfo = json_decode( $this->fieldValue, TRUE );
            $itemConf['html'] = "<img src=\"".$imgInfo['url']."\" />";
            $value = "url:".$imgInfo['url'].";left:{$imgInfo['left']};top:{$imgInfo['top']}";
        }
        if ( $this->fieldOption['must'] || $this->fieldOption['write'] )
        {
            $itemConf['xtype'] = "signaturefield";
            $itemConf['labelAlign'] = "left";
            $itemConf['cls'] = $this->fieldOption['must'] ? "x-field-required" : "";
            $this->items[] = array(
                "name" => "wf_fieldS_".$field['id'],
                "xtype" => "hiddenfield",
                "value" => $value,
                "required" => $this->fieldOption['must'] ? TRUE : FALSE
            );
        }
        else
        {
            unset( $itemConf['html'] );
            $itemConf['xtype'] = "imagefield";
            $itemConf['src'] = empty( $imgInfo ) ? "" : "{$imgInfo['url']}";
        }
        $this->items[] = $itemConf;
    }

}

?>
