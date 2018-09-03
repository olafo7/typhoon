<?php

class wfFlowUsePhone extends wfForm
{

    private static $map_level = array
    (
        0 => "普通",
        1 => "重要",
        2 => "非常重要"
    );
    private $uid = NULL;
    private $flowId = NULL;
    private $uFlowId = NULL;
    private $uStepId = NULL;
    private $attach = NULL;

    const FROM_NEW = "new";
    const FROM_TODO = "todo";
    const FROM_DONE = "done";

    public function run( )
    {
        $task = getpar( $_GET, "task", "loadPage" );
        switch ( $task )
        {
        case "loadPage" :
            $this->_loadPage( );
            exit( );
        case "loadFlowInfo" :
            $this->_loadFlowForm( );
        }
        exit( );
    }

    private function _loadPage( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/phone.form.htm";
        $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
        exit( );
    }

    private function _loadFlowForm( )
    {
        global $CNOA_SESSION;
        $this->uid = $CNOA_SESSION->get( "UID" );
        $this->from = getpar( $_POST, "from" );
        $this->flowId = intval( getpar( $_POST, "flowId" ) );
        $this->uFlowId = intval( getpar( $_POST, "uFlowId" ) );
        $this->uStepId = intval( getpar( $_POST, "uStepId" ) );
        if ( !$this->from )
        {
            if ( !empty( $this->uFlowId ) || !empty( $this->uStepId ) )
            {
                $this->from = self::FROM_TODO;
            }
            else if ( !empty( $this->flowId ) )
            {
                $this->from = self::FROM_NEW;
            }
        }
        $this->user_sel_array = array( );
        $info['flowInfo'] = $this->_getFlowInfo( );
        $info['permit'] = $this->_getStepPermit( );
        $info['attach'] = $this->_getAttach( $info['permit'] );
        $info['formInfo'] = $this->_getFromFields( );
        $this->_changeFenfaIsRead( $this->uid );
        echo json_encode( $info );
    }

    private function _getFlowInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $flowInfo = array( );
        if ( $this->from === self::FROM_TODO || $this->from === self::FROM_DONE )
        {
            $sql = "SELECT f.uid, f.flowId, f.flowNumber, f.flowName, f.level, f.reason, f.posttime, u.truename AS uname, f.attach FROM ".tname( $this->t_use_flow )." AS f LEFT JOIN ".tname( "main_user" )." AS u ON u.uid=f.uid ".( "WHERE f.uFlowId=".$this->uFlowId );
            $uFlowInfo = $CNOA_DB->get_one( $sql );
            $flowInfo['flowNumber'] = $uFlowInfo['flowNumber'];
            $flowInfo['flowName'] = $uFlowInfo['flowName'];
            $flowInfo['level'] = $uFlowInfo['level'];
            $flowInfo['reason'] = $uFlowInfo['reason'];
            $flowInfo['uname'] = $uFlowInfo['uname'];
            $flowInfo['nameDisallowBlank'] = 0;
            $flowInfo['posttime'] = formatdate( $uFlowInfo['posttime'], "Y-m-d H:i" );
            $this->uFlowInfo = $uFlowInfo;
            $this->flowId = $uFlowInfo['flowId'];
            $this->faqiUid = $uFlowInfo['uid'];
            $this->attach = $uFlowInfo['attach'];
            return $flowInfo;
        }
        if ( $this->from == self::FROM_NEW )
        {
            $uFlowInfo = $CNOA_DB->db_getone( array( "nameRule", "startStepId", "nameDisallowBlank" ), $this->t_set_flow, "WHERE flowId=".$this->flowId );
            $this->uStepId = $uFlowInfo['startStepId'];
            $flowInfo['flowNumber'] = $uFlowInfo['nameRule'];
            $flowInfo['nameDisallowBlank'] = $uFlowInfo['nameDisallowBlank'];
            $flowInfo['flowName'] = "";
            $flowInfo['level'] = 0;
            $flowInfo['reason'] = "";
            $flowInfo['uname'] = $CNOA_SESSION->get( "TRUENAME" );
            $flowInfo['posttime'] = date( "Y-m-d H:i" );
            $this->uFlowInfo = $flowInfo;
            $this->faqiUid = $CNOA_SESSION->get( "UID" );
        }
        return $flowInfo;
    }

    private function _getStepPermit( )
    {
        if ( $this->from === self::FROM_TODO || $this->from === self::FROM_DONE )
        {
            ( $this->uFlowId );
            $wfCache = new wfCache( );
            $info = $wfCache->getStepByStepId( $this->uStepId );
            $permit['allowReject'] = ( integer )$info['allowReject'] == 1 ? TRUE : FALSE;
            $permit['allowTuihui'] = ( integer )$info['allowTuihui'] == 1 ? TRUE : FALSE;
            $permit['allowHuiqian'] = ( integer )$info['allowHuiqian'] == 1 ? TRUE : FALSE;
            $permit['allowFenfa'] = ( integer )$info['allowFenfa'] == 1 ? TRUE : FALSE;
            $permit['allowAttachAdd'] = ( integer )$info['allowAttachAdd'] == 1 ? TRUE : FALSE;
            $permit['allowAttachView'] = ( integer )$info['allowAttachView'] == 1 ? TRUE : FALSE;
            $permit['allowAttachEdit'] = ( integer )$info['allowAttachEdit'] == 1 ? TRUE : FALSE;
            $permit['allowAttachDelete'] = ( integer )$info['allowAttachDelete'] == 1 ? TRUE : FALSE;
            $permit['allowAttachDown'] = ( integer )$info['allowAttachDown'] == 1 ? TRUE : FALSE;
        }
        else if ( $this->from == self::FROM_NEW )
        {
            global $CNOA_DB;
            $info = $CNOA_DB->db_getone( array( "allowReject", "allowTuihui", "allowHuiqian", "allowFenfa", "allowAttachAdd", "allowAttachView", "allowAttachEdit", "allowAttachDelete", "allowAttachDown" ), $this->t_set_step, "WHERE flowId=".$this->flowId." AND stepId={$this->uStepId}" );
            if ( !is_array( $info ) )
            {
                $info = array( );
            }
        }
        $permit['allowReject'] = ( integer )$info['allowReject'] == 1 ? TRUE : FALSE;
        $permit['allowTuihui'] = ( integer )$info['allowTuihui'] == 1 ? TRUE : FALSE;
        $permit['allowHuiqian'] = ( integer )$info['allowHuiqian'] == 1 ? TRUE : FALSE;
        $permit['allowFenfa'] = ( integer )$info['allowFenfa'] == 1 ? TRUE : FALSE;
        $permit['allowAttachAdd'] = ( integer )$info['allowAttachAdd'] == 1 ? TRUE : FALSE;
        $permit['allowAttachView'] = ( integer )$info['allowAttachView'] == 1 ? TRUE : FALSE;
        $permit['allowAttachEdit'] = ( integer )$info['allowAttachEdit'] == 1 ? TRUE : FALSE;
        $permit['allowAttachDelete'] = ( integer )$info['allowAttachDelete'] == 1 ? TRUE : FALSE;
        $permit['allowAttachDown'] = ( integer )$info['allowAttachDown'] == 1 ? TRUE : FALSE;
        return $permit;
    }

    private function _getAttach( $permit )
    {
        $attachs = array( );
        $aids = json_decode( $this->attach, TRUE );
        if ( !empty( $aids ) )
        {
            ( );
            $fs = new fs( );
            $attachs = $fs->getDownLoadFileListByIds( $aids );
            if ( !$permit['allowAttachEdit'] || !$permit['allowAttachView'] || !$permit['allowAttachDown'] )
            {
                foreach ( $attachs as $k => $v )
                {
                    unset( $v['url'] );
                }
            }
        }
        return $attachs;
    }

    private function _getFromFields( )
    {
        global $CNOA_DB;
        $fields = array( );
        $stepFields = array( );
        $curStepPermit = array( );
        $widgetData = array( );
        if ( $this->from == self::FROM_NEW )
        {
            $dblist = $CNOA_DB->db_select( "*", $this->t_set_step_fields, "WHERE `flowId`=".$this->flowId." AND `stepId`=2 AND hide=0" );
            if ( !is_array( $dblist ) )
            {
                $dblist = array( );
            }
            $fieldId = array( );
            foreach ( $dblist as $v )
            {
                $stepFields[$v['fieldId']] = $v;
                $fieldId[] = $v['fieldId'];
            }
            if ( !empty( $fieldId ) )
            {
                $fieldId = implode( ",", $fieldId );
                $fields = $CNOA_DB->db_select( "*", $this->t_set_field, "WHERE `flowId`=".$this->flowId." AND id IN ({$fieldId}) ORDER BY `order` ASC" );
            }
            $curStepPermit = $CNOA_DB->db_getone( "*", $this->t_set_step, "WHERE `flowId`=".$this->flowId." AND `stepId`={$this->uStepId}" );
        }
        else if ( $this->from == self::FROM_TODO || $this->from === self::FROM_DONE )
        {
            ( $this->uFlowId );
            $wfCache = new wfCache( );
            $fields = $wfCache->getFlowFields( );
            $stepFields = $wfCache->getStepFields( $this->uStepId, self::FIELD_RULE_NORMAL );
            $curStepPermit = $wfCache->getStepByStepId( $this->uStepId );
            $widgetData = $CNOA_DB->db_getone( "*", "z_wf_t_".$this->flowId, "WHERE `uFlowId` = '".$this->uFlowId."'" );
        }
        $widgets = array( );
        foreach ( $fields as $fieldInfo )
        {
            if ( $this->from === self::FROM_NEW && $fieldInfo['otype'] == "detailtable" )
            {
                $fieldId = $fieldInfo['id'];
                $fieldPermit = $stepFields[$fieldId];
                if ( !$fieldPermit )
                {
                    $rule = array( "show" => 1, "hide" => 0, "edit" => 0, "write" => 0, "must" => 0 );
                }
                if ( !( $fieldPermit['hide'] == 1 ) )
                {
                    $fieldValue = empty( $widgetData["T_".$fieldId] ) ? $fieldInfo['dvalue'] : $widgetData["T_".$fieldId];
                    $widget = $this->_formatField( $fieldInfo, $fieldPermit, $fieldValue );
                    if ( !is_null( $widget ) )
                    {
                        $widgets[] = $widget;
                    }
                }
            }
        }
        return array(
            "items" => $widgets
        );
    }

    private function _formatField( $fieldInfo, $fieldPermit, $fieldValue )
    {
        $widget = array( );
        $widget['label'] = $fieldInfo['name'];
        $widget['tag'] = $fieldInfo['otype'];
        $widget['name'] = "wf_field_".$fieldInfo['id'];
        $widget['value'] = $fieldValue;
        if ( $this->from === self::FROM_DONE )
        {
            $widget['readOnly'] = TRUE;
        }
        else
        {
            if ( $fieldPermit['must'] )
            {
                $widget['must'] = TRUE;
            }
            if ( !$fieldPermit['write'] )
            {
                $widget['readOnly'] = TRUE;
            }
        }
        switch ( $fieldInfo['otype'] )
        {
        case "textfield" :
            $this->_formatTextfield( $widget, $fieldInfo );
            break;
        case "textarea" :
        case "radio" :
            $this->_formatRadiofield( $widget, $fieldInfo );
            break;
        case "checkbox" :
            $widget['name'] = "wf_fieldC_".$fieldInfo['id'];
            $this->_formatCheckfield( $widget, $fieldInfo );
            break;
        case "select" :
            $this->_formatSelectfield( $widget, $fieldInfo );
            break;
        case "macro" :
            $this->_formatMacro( $widget, $fieldInfo );
            break;
        case "choice" :
            $this->_formatChoice( $widget, $fieldInfo );
            break;
        case "detailtable" :
            $this->_formatDetailtable( $widget, $fieldInfo );
            break;
        default :
            return;
        }
        return $widget;
    }

    private function _formatTextfield( &$widget, $fieldInfo )
    {
        if ( !$widget['readOnly'] )
        {
            $odata = $this->__getOdata( $fieldInfo['odata'] );
            $widget['dataType'] = $odata['dataType'];
            $this->_formatText4Setting( $widget, $odata['dataType'], $odata['dataFormat'] );
        }
    }

    private function _formatRadiofield( &$widget, $fieldInfo )
    {
        $odata = $this->__getOdata( $fieldInfo['odata'] );
        $items = $item = array( );
        foreach ( $odata['dataItems'] as $v )
        {
            $item = array(
                "label" => $v['name'],
                "value" => $v['name']
            );
            if ( $v['name'] == $widget['value'] )
            {
                $item['checked'] = TRUE;
            }
            $items[] = $item;
        }
        $widget['items'] = $items;
    }

    private function _formatCheckfield( &$widget, $fieldInfo )
    {
        $odata = $this->__getOdata( $fieldInfo['odata'] );
        $value = json_decode( htmlspecialchars( $widget['value'], ENT_NOQUOTES ), TRUE );
        $items = $item = array( );
        foreach ( $odata['dataItems'] as $v )
        {
            $item = array(
                "label" => $v['name'],
                "value" => $v['name']
            );
            if ( !empty( $value ) || is_array( $value ) )
            {
                $item['checked'] = in_array( $v['name'], $value );
            }
            else if ( $v['checked'] == 1 )
            {
                $item['checked'] = TRUE;
            }
            $items[] = $item;
        }
        $widget['items'] = $items;
    }

    private function _formatSelectfield( &$widget, $fieldInfo )
    {
        $odata = $this->__getOdata( $fieldInfo['odata'] );
        $items[] = array( "label" => "", "value" => "" );
        foreach ( $odata['dataItems'] as $v )
        {
            $item = array( );
            $item['label'] = $v['name'];
            $item['value'] = $odata['dataType'] == "int" ? $v['value'] : $v['name'];
            if ( $item['value'] == $widget['value'] )
            {
                $item['selected'] = TRUE;
            }
            $items[] = $item;
        }
        $widget['items'] = $items;
    }

    private function _formatMacro( &$widget, $fieldInfo )
    {
        $odata = $this->__getOdata( $fieldInfo['odata'] );
        if ( $odata['dataType'] == "moneyconvert" )
        {
            return;
        }
        $widget['dataType'] = $odata['dataType'];
        if ( !$widget['readOnly'] )
        {
            if ( $widget['dataType'] == "loginname" )
            {
                global $CNOA_SESSION;
                $widget['value'] = $CNOA_SESSION->get( "UID" );
                $widget['displayValue'] = app::loadapp( "main", "user" )->api_getUserNameByUids( intval( $widget['value'] ) );
            }
            else
            {
                $widget['value'] = $this->_getMacroValue( $odata );
            }
        }
        else if ( $widget['dataType'] == "loginname" && !empty( $widget['value'] ) )
        {
            $widget['displayValue'] = app::loadapp( "main", "user" )->api_getUserNameByUids( intval( $widget['value'] ) );
        }
        else
        {
            $widget['displayValue'] = "";
        }
    }

    private function _formatChoice( &$widget, $fieldInfo )
    {
        $odata = $this->__getOdata( $fieldInfo['odata'] );
        $value = $widget['value'];
        $widget['displayValue'] = "";
        switch ( $odata['dataType'] )
        {
        case "user_sel" :
            $widget['dataType'] = "user";
            $widget['multi'] = FALSE;
            if ( empty( $value ) )
            {
                break;
            }
            $widget['displayValue'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $value );
            break;
        case "users_sel" :
            $widget['dataType'] = "user";
            $widget['multi'] = TRUE;
            if ( empty( $value ) )
            {
                break;
            }
            $value = app::loadapp( "main", "user" )->api_getUserTruenameByUid( explode( ",", $value ) );
            $widget['displayValue'] = implode( ",", $value );
            break;
        case "job_sel" :
            $widget['dataType'] = "job";
            $widget['multi'] = FALSE;
            if ( empty( $value ) )
            {
                break;
            }
            $widget['displayValue'] = app::loadapp( "main", "job" )->api_getNameById( $value );
            break;
        case "jobs_sel" :
            $widget['dataType'] = "job";
            $widget['multi'] = TRUE;
            if ( empty( $value ) )
            {
                break;
            }
            $value = app::loadapp( "main", "job" )->api_getNamesByIds( explode( ",", $value ) );
            $widget['displayValue'] = implode( ",", $value );
            break;
        case "dept_sel" :
            $widget['dataType'] = "dept";
            $widget['multi'] = FALSE;
            if ( empty( $value ) )
            {
                break;
            }
            $widget['displayValue'] = app::loadapp( "main", "struct" )->api_getNameById( $value );
            break;
        case "depts_sel" :
            $widget['dataType'] = "dept";
            $widget['multi'] = TRUE;
            if ( empty( $value ) )
            {
                break;
            }
            $value = app::loadapp( "main", "struct" )->api_getNamesByIds( explode( ",", $value ) );
            $widget['displayValue'] = implode( ",", $value );
            break;
        case "station_sel" :
            $widget['dataType'] = "station";
            $widget['multi'] = FALSE;
            if ( empty( $value ) )
            {
                break;
            }
            $widget['displayValue'] = app::loadapp( "main", "station" )->api_getNameById( $value );
            break;
        case "stations_sel" :
            $widget['dataType'] = "station";
            $widget['multi'] = TRUE;
            if ( empty( $value ) )
            {
                break;
            }
            $value = app::loadapp( "main", "station" )->api_getNamesByIds( explode( ",", $value ) );
            $widget['displayValue'] = implode( ",", $value );
            break;
        case "time_sel" :
            $widget['dataType'] = "time";
            $widget['format'] = $this->_getTimeFormat( $odata['dataFormat'] );
            if ( !empty( $value ) || $value != "default" && $value != "null" )
            {
                $widget['displayValue'] = $value;
            }
            else
            {
                if ( !( $value == "default" ) )
                {
                    break;
                }
                $value = date( $widget['format'] );
                $widget['displayValue'] = str_replace( array( "am", "pm" ), array( "上午", "下午" ), $value );
                $widget['value'] = $widget['displayValue'];
            }
            break;
        case "date_sel" :
            $widget['dataType'] = "date";
            $dataFormat = $this->_getDataFormat( $odata['dataFormat'] );
            $widget['format'] = $dataFormat['format'];
            if ( !empty( $value ) || $value != "default" && $value != "null" )
            {
                $widget['displayValue'] = $value;
            }
            else
            {
                if ( !( $value == "default" ) )
                {
                    break;
                }
                $widget['displayValue'] = date( $dataFormat['format'] );
                $widget['value'] = $widget['displayValue'];
            }
        }
    }

    private function _formatDetailtable( &$widget, $fieldInfo )
    {
        $widget['tableId'] = $fieldInfo['id'];
    }

    private function _changeFenfaIsRead( $uid )
    {
        global $CNOA_DB;
        $fenFaInfo = $CNOA_DB->db_getone( "*", "wf_u_fenfa", "WHERE `touid`='".$uid."' AND `uFlowId`='{$this->uFlowId}' AND `stepId`='{$this->uStepId}'" );
        if ( $fenFaInfo )
        {
            $CNOA_DB->db_update( array( "isread" => 1 ), "wf_u_fenfa", "WHERE `touid`='".$uid."' AND `uFlowId`='{$this->uFlowId}' AND `stepId`='{$this->uStepId}'" );
        }
    }

}

?>
