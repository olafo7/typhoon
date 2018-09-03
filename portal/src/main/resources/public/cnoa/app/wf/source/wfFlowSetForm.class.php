<?php

class wfFlowSetForm extends wfCommon
{

    public function run( )
    {
        $task = getpar( $_GET, "task", getpar( $_POST, "task", "loadpage" ) );
        switch ( $task )
        {
        case "formdesign" :
            $this->_formdesign( );
            exit( );
        case "saveFormDesignData" :
            $this->_saveFormDesignData( );
            exit( );
        case "makePreviewHtml" :
            $this->_makePreviewHtml( );
            exit( );
        case "exportFormHtml" :
            $this->_exportFormHtml( );
            exit( );
        case "submitStyle" :
            $this->_submitStyle( );
            exit( );
        case "getStyleList" :
            $this->_getStyleList( );
            exit( );
        case "deleteStyle" :
            $this->_deleteStyle( );
            exit( );
        case "importFromExcel" :
            $this->_importFromExcel( );
            exit( );
        case "ueditorbackground" :
            $this->_ueditorbackground( );
            exit( );
        case "getPrintPage" :
            $this->_getPrintPage( );
            exit( );
        case "getTplList" :
            $this->_getTplList( );
            exit( );
        case "getTplHtml" :
            $this->_getTplHtml( );
            exit( );
        case "getBindFunctionWidget" :
            $this->_getBindFunctionWidget( );
            exit( );
        case "getDataSource" :
            $this->_getDataSource( );
            exit( );
        case "getSourceFields" :
            $this->_getSourceFields( );
            exit( );
        case "loadFormHtmlForOrder" :
            $this->_loadFormHtmlForOrder( );
        case "loadFormItemsForOrder" :
            $this->_loadFormItemsForOrder( );
        case "saveFormItemsOrder" :
            $this->_saveFormItemsOrder( );
        }
        exit( );
    }

    private function _formdesign( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        global $CNOA_SESSION;
        $flowId = getpar( $_GET, "flowId", 0 );
        $GLOBALS['GLOBALS']['content'] = $CNOA_DB->db_getfield( "formHtml", $this->t_set_flow, "WHERE `flowId`='".$flowId."'" );
        $GLOBALS['GLOBALS']['content'] = str_replace( array( "<", ">" ), array( "&lt;", "&gt;" ), $GLOBALS['content'] );
        $GLOBALS['GLOBALS']['LANGUAGE'] = $CNOA_SESSION->get( "LANGUAGE" );
        $CNOA_CONTROLLER->tplHeaderType = "extjs";
        $GLOBALS['GLOBALS']['formid'] = $flowId;
        $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/flow/set/flow_form.htm";
        $CNOA_CONTROLLER->loadViewCustom( $tplPath, TRUE, TRUE );
    }

    private function _saveFormDesignData( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $data = array( );
        $flowId = getpar( $_POST, "flowId", 0 );
        $data['formHtml'] = str_replace( "&#39;", "'", $_POST['formHtml'] );
        $this->__checkFormItems( );
        $elements = $this->__getHtmlElement( $data['formHtml'] );
        if ( !is_array( $elements ) )
        {
            $elements = array( );
        }
        $nameArr = array( );
        foreach ( $elements as $v )
        {
            $nameArr[] = $v['name'];
        }
        $nameList = array_count_values( $nameArr );
        foreach ( $nameList as $k => $v )
        {
            if ( 1 < $v )
            {
                msg::callback( FALSE, lang( "formControl" )."[".$k."]".lang( "haveRepeatPleaseEdit" ) );
            }
        }
        $dblist = $CNOA_DB->db_select( "*", $this->t_set_field, "WHERE `flowId` = '".$flowId."' " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $nameDB = array( );
        $deleteIdArr = array( 0 );
        foreach ( $dblist as $k => $v )
        {
            if ( !in_array( $v['name'], $nameArr ) )
            {
                $deleteIdArr[] = $v['id'];
            }
            else
            {
                $nameDB[] = $v['name'];
            }
        }
        $this->api_deleteFields( $deleteIdArr );
        foreach ( $elements as $v )
        {
            $dvalue = addslashes( $v['dvalue'] );
            $dt = array( );
            $dt['flowId'] = $flowId;
            $dt['name'] = addslashes( $v['name'] );
            $dt['otype'] = addslashes( $v['otype'] );
            $dt['type'] = addslashes( $v['type'] );
            $dt['style'] = addslashes( $v['style'] );
            $dt['odata'] = addslashes( $v['odata'] );
            $dt['tagName'] = addslashes( $v['tagName'] );
            $dt['dvalue'] = $dvalue == "undefined" ? "" : $dvalue;
            $dt['html'] = addslashes( $v['html'] );
            if ( $dt['otype'] == "detailtable" || $dt['otype'] == "macro" )
            {
                $odata = $this->__getOdata( $v['odata'] );
                if ( !empty( $odata['bindFunction'] ) )
                {
                    $dt['bindfunction'] = $odata['bindFunction'];
                    if ( $dt['bindfunction'] == "sqldetail" )
                    {
                        $dt['sqldetailId'] = $odata['sqldetail'];
                    }
                }
            }
            if ( in_array( $v['name'], $nameDB ) )
            {
                $CNOA_DB->db_update( $dt, $this->t_set_field, "WHERE `name` = '".$v['name']."' AND `flowId` = '{$flowId}' " );
                if ( $dt['otype'] == "detailtable" )
                {
                    $fid = $CNOA_DB->db_getfield( "id", $this->t_set_field, "WHERE `name` = '".$v['name']."' AND `flowId` = '{$flowId}' " );
                }
            }
            else
            {
                $fid = $CNOA_DB->db_insert( $dt, $this->t_set_field );
            }
            if ( $dt['otype'] == "detailtable" )
            {
                $this->_createDetailData( $v['odata'], $fid, $flowId );
            }
        }
        $data['formHtml'] = getpar( $data, "formHtml", "", 1, 0 );
        $data['pageset'] = getpar( $_POST, "pageset", "", 1, 0 );
        $CNOA_DB->db_update( $data, $this->t_set_flow, "WHERE `flowId`='".$flowId."' " );
        $flowInfo = $CNOA_DB->db_getone( array( "name" ), $this->t_set_flow, "WHERE `flowId`='".$flowId."'" );
        $this->api_createTable( $flowId, $flowInfo );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "update", 3662, $flowInfo['name'], lang( "processForm" ) );
        msg::callback( TRUE, lang( "saved" ) );
    }

    private function _createDetailData( $odata, $fid, $flowId )
    {
        global $CNOA_DB;
        $odata = json_decode( str_replace( "'", "\"", $odata ), TRUE );
        $dblist = $CNOA_DB->db_select( array( "name" ), $this->t_set_field_detail, "WHERE `fid` = '".$fid."' " );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $allNameArr = array( );
        foreach ( $dblist as $v )
        {
            $allNameArr[] = $v['name'];
        }
        if ( !is_array( $odata['items'] ) )
        {
            $odata['items'] = array( );
        }
        foreach ( $odata['items'] as $v )
        {
            $data = array( );
            if ( !empty( $v['name'] ) )
            {
                $data['name'] = $v['name'];
                $data['align'] = $v['align'];
                $data['dataType'] = $v['odata']['dataType'];
                $data['baoliu'] = $v['odata']['baoliu'];
                $data['dataFormat'] = $v['odata']['dataFormat'];
                $data['fid'] = $fid;
                $data['flowId'] = $flowId;
                $data['dvalue'] = $v['odata']['dvalue'];
                $data['type'] = $v['odata']['type'];
                $data['width'] = $v['odata']['width'];
                if ( $data['dataType'] == "moneyconvert" )
                {
                    $data['item'] = addslashes( json_encode( array(
                        "from" => $v['odata']['kongjian']
                    ) ) );
                }
                else
                {
                    $data['item'] = addslashes( json_encode( $v['odata']['item'] ) );
                }
                if ( !empty( $v['odata']['bindField'] ) )
                {
                    $data['binfield'] = $v['odata']['bindField'];
                }
                else
                {
                    $data['binfield'] = "";
                }
                $DB = $CNOA_DB->db_getone( array( "id" ), $this->t_set_field_detail, "WHERE `name` = '".$data['name']."' AND `fid` = '{$fid}' " );
                if ( empty( $DB ) )
                {
                    $id = $CNOA_DB->db_insert( $data, $this->t_set_field_detail );
                }
                else
                {
                    $CNOA_DB->db_update( $data, $this->t_set_field_detail, "WHERE `name` = '".$data['name']."' AND `fid` = '{$fid}' " );
                }
                $nameArr[] = $v['name'];
            }
        }
        foreach ( $allNameArr as $v )
        {
            if ( !in_array( $v, $nameArr ) )
            {
                $CNOA_DB->db_delete( $this->t_set_field_detail, "WHERE `name` = '".$v."' AND `fid` = '{$fid}' " );
            }
        }
    }

    private function _makePreviewHtml( )
    {
        $html = str_replace( "&#39;", "'", $_POST['html'] );
        $elements = $this->__getHtmlElement( $html );
        ( $elements, $html );
        $fieldFormater = new wfFieldFormaterForPreview( );
        $html = $fieldFormater->crteateFormHtml( );
        $html = "<table id=\"wf_previewtable\" class=\"wf_div_cttb\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin:0 auto;\">\r\n\t\t<tr>\r\n\t\t<td class=\"wf_bd wf_tl\"></td>\r\n\t\t<td class=\"wf_bd wf_t\"></td>\r\n\t\t<td class=\"wf_bd wf_tr\"></td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t<td class=\"wf_bd2 wf_l\"></td>\r\n\t\t<td style=\"\" id=\"wf_previewtd\" class=\"wf_c wf_form_content\"><div id=\"wf_previewcontent\">".$html."<div></td>\r\n\t\t<td class=\"wf_bd2 wf_r\"></td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t<td class=\"wf_bd wf_bl\"></td>\r\n\t\t<td class=\"wf_bd wf_b\"></td>\r\n\t\t<td class=\"wf_bd wf_br\"></td>\r\n\t\t</tr>\r\n\t\t</table>";
        echo $html;
        exit( );
    }

    private function _exportFormHtml( )
    {
        global $CNOA_DB;
        $html = str_replace( "&#39;", "'", $_POST['html'] );
        $step = getpar( $_POST, "step", 0 );
        if ( $step == 1 )
        {
            $file = "wf_form_export_".time( ).string::rands( 20 ).".txt";
            file_put_contents( CNOA_PATH_FILE."/temp/".$file, $html );
            echo $file;
            exit( );
        }
        $flowId = getpar( $_GET, "flowId", 0 );
        $fileName = getpar( $_GET, "file" );
        $file = CNOA_PATH_FILE."/temp/".$fileName;
        $flowName = $CNOA_DB->db_getfield( "name", $this->t_set_flow, "WHERE `flowId`='".$flowId."'" );
        if ( $CNOA_DB )
        {
            $CNOA_DB->close( );
        }
        @ini_set( "zlib.output_compression", "Off" );
        header( "Content-Type: application/octet-stream" );
        header( "Content-Disposition: attachment;filename=".cn_urlencode( "工作流表单[".$flowName."].txt" ) );
        header( "Content-Length: ".filesize( $file ) );
        ob_clean( );
        flush( );
        readfile( $file );
        exit( );
    }

    private function _submitStyle( )
    {
        global $CNOA_DB;
        $type = getpar( $_POST, "type", "" );
        $sid = getpar( $_POST, "sid", 0 );
        $data['name'] = getpar( $_POST, "name", "" );
        $data['font'] = getpar( $_POST, "font", 0 );
        $data['size'] = getpar( $_POST, "size", "" );
        $data['color'] = getpar( $_POST, "color", "" );
        $data['border'] = getpar( $_POST, "border", 0 );
        $data['bold'] = getpar( $_POST, "bold", "" ) == "on" ? 1 : 0;
        $data['italic'] = getpar( $_POST, "italic", "" ) == "on" ? 1 : 0;
        if ( $type == "add" )
        {
            $num = $CNOA_DB->db_getcount( $this->t_set_form_style, "WHERE `name` = '".$data['name']."' " );
            if ( !empty( $num ) )
            {
                msg::callback( FALSE, lang( "nameExist" ) );
            }
            $CNOA_DB->db_insert( $data, $this->t_set_form_style );
        }
        else if ( $type == "edit" )
        {
            $num = $CNOA_DB->db_getcount( $this->t_set_form_style, "WHERE `name` = '".$data['name']."' AND `sid` != '{$sid}' " );
            if ( !empty( $num ) )
            {
                msg::callback( FALSE, lang( "nameExist" ) );
            }
            $CNOA_DB->db_update( $data, $this->t_set_form_style, "WHERE `sid` = '".$sid."' " );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _getStyleList( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->t_set_form_style );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        $from = getpar( $_GET, "from", "" );
        if ( $from != "set" )
        {
            $data = array(
                array( "sid" => 0, "name" => "　　" )
            );
        }
        foreach ( $dblist as $k => $v )
        {
            $dblist[$k]['fontName'] = $this->f_font[$v['font']];
            $format = "";
            $format .= $v['bold'] == 1 ? $this->f_font_format['bold']." " : " ";
            $format .= $v['italic'] == 1 ? $this->f_font_format['italic']." " : " ";
            $format .= $v['underline'] == 1 ? $this->f_font_format['underline']." " : " ";
            if ( trim( $format ) == "" )
            {
                $format = "常规";
            }
            $dblist[$k]['format'] = $format;
            $data[] = $dblist[$k];
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _deleteStyle( )
    {
        global $CNOA_DB;
        $sid = getpar( $_POST, "sid", 0 );
        $CNOA_DB->db_delete( $this->t_set_form_style, "WHERE `sid` = '".$sid."' " );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _importFromExcel( )
    {
        set_time_limit( 0 );
        $img_ext = strtolower( strrchr( $_FILES['excel']['name'], "." ) );
        $img_name = $GLOBALS['CNOA_TIMESTAMP']."_".md5( $GLOBALS['CNOA_TIMESTAMP'] ).$img_ext;
        $img_dst = CNOA_PATH_FILE."/common/temp/".$img_name;
        $img_url = $GLOBALS['CNOA_BASE_URL_FILE']."/common/temp/".$img_name;
        $extArray = array( ".xls", ".xlsx" );
        if ( !in_array( $img_ext, $extArray ) )
        {
            msg::callback( FALSE, lang( "onlyImportXLSXLSX" ) );
        }
        if ( cnoa_move_uploaded_file( $_FILES['excel']['tmp_name'], $img_dst ) )
        {
            $objPHPExcel = initExcel::getobjphpexcel( $img_dst );
            ( $objPHPExcel );
            $objWriter = new PHPExcel_Writer_HTML( );
            $msg = $objWriter->generateHtml( );
            $msg = json_encode( array(
                "success" => TRUE,
                "msg" => str_replace( "\r\n", "", urlencode( $msg ) )
            ) );
            echo $msg;
            exit( );
        }
        msg::callback( FALSE, lang( "importUploadFailed" ) );
    }

    private function _ueditorbackground( )
    {
        global $CNOA_DB;
        $bgWidth = getpar( $_GET, "width", 0 );
        $bgHeight = getpar( $_GET, "height", 0 );
        $marginLeft = getpar( $_GET, "left", 0 );
        $marginRight = getpar( $_GET, "right", 0 );
        $marginTop = getpar( $_GET, "top", 0 );
        $marginDown = getpar( $_GET, "down", 0 );
        ( $bgWidth, $bgHeight, $marginLeft, $marginRight, $marginTop, $marginDown );
        $handler = new ueditorBackGround( );
    }

    private function _getPrintPage( )
    {
        global $CNOA_DB;
        $flowId = getpar( $_GET, "flowId", 0 );
        $pageset = $CNOA_DB->db_getfield( "pageset", $this->t_set_flow, "WHERE `flowId` = ".$flowId." " );
        if ( empty( $pageset ) || $pageset == "null" )
        {
            $pageset = "{\"pageSize\":\"a4page\",\"pageDir\":\"lengthways\",\"pageUp\":\"10\",\"pageDown\":\"10\",\"pageLeft\":\"10\",\"pageRight\":\"10\"}";
        }
        $result = array(
            "success" => TRUE,
            "pageset" => $pageset
        );
        echo json_encode( $result );
        exit( );
    }

    private function __getHtmlElement( $html )
    {
        preg_match_all( "/(<(input)[^>]*>)/i", $html, $arr );
        $elementList = array( );
        foreach ( $arr[0] as $v )
        {
            $tmp = array( );
            $tmp['name'] = preg_replace( "/(.*)name=\"([^\"]*)\"\\s?(.*)/is", "\\2", $v );
            $tmp['otype'] = preg_replace( "/(.*)otype=\"([^\"]*)\"\\s?(.*)/is", "\\2", $v );
            $tmp['type'] = preg_replace( "/(.*)type=\"([^\"]*)\"\\s?(.*)/is", "\\2", str_replace( "otype", "xxx", $v ) );
            $tmp['maxLength'] = preg_replace( "/(.*)maxLength=\"([^\"]*)\"\\s?(.*)/is", "\\2", $v );
            $tmp['dvalue'] = preg_replace( "/(.*)dvalue=\"([^\"]*)\"\\s?(.*)/is", "\\2", $v );
            $tmp['odata'] = preg_replace( "/(.*)odata=\"([^\"]*)\"\\s?(.*)/is", "\\2", $v );
            $tmp['style'] = preg_replace( "/(.*)style=\"([^\"]*)\"\\s?(.*)/is", "\\2", $v );
            $tmp['table'] = preg_replace( "/(.*)table=\"([^\"]*)\"\\s?(.*)/is", "\\2", $v );
            $tmp['showsum'] = preg_replace( "/(.*)showsum=\"([^\"]*)\"\\s?(.*)/is", "\\2", $v );
            $tmp['tagName'] = strtolower( substr( $v, 1, strpos( $v, " " ) - 1 ) );
            $tmp['type'] = $tmp['type'] == $v ? "" : $tmp['type'];
            $tmp['otype'] = $tmp['otype'] == $v ? "" : $tmp['otype'];
            $tmp['dvalue'] = $tmp['dvalue'] == $v ? "" : $tmp['dvalue'];
            $tmp['odata'] = $tmp['odata'] == $v ? "" : $tmp['odata'];
            $tmp['style'] = $tmp['style'] == $v ? "" : $tmp['style'];
            $tmp['table'] = $tmp['table'] == $v ? "" : $tmp['table'];
            $tmp['maxLength'] = $tmp['maxLength'] == $v ? "" : $tmp['maxLength'];
            $tmp['showsum'] = $tmp['showsum'] == $v ? "" : $tmp['showsum'];
            $tmp['html'] = $v;
            if ( !empty( $tmp['otype'] ) )
            {
                $elementList[] = $tmp;
            }
        }
        return $elementList;
    }

    public function api_getHtmlElement( $html )
    {
        return $this->__getHtmlElement( $html );
    }

    private function __checkFormItems( )
    {
    }

    private function _getTplList( )
    {
        $mapFile = CNOA_PATH_FILE."/common/wf_template/tpl.map";
        $tpls = array( );
        if ( file_exists( $mapFile ) )
        {
            $map = explode( "\n", file_get_contents( $mapFile ) );
            foreach ( $map as $v )
            {
                list( $code, $name ) = explode( "|", $v );
                $tpls[] = array(
                    "code" => $code,
                    "tplName" => "{$name}"
                );
            }
        }
        echo json_encode( array(
            "data" => $tpls
        ) );
        exit( );
    }

    private function _getTplHtml( )
    {
        $code = getpar( $_POST, "code", "" );
        $html = "";
        if ( !empty( $code ) )
        {
            $tplFile = CNOA_PATH_FILE.( "/common/wf_template/".$code.".txt" );
            if ( file_exists( $tplFile ) )
            {
                $html = file_get_contents( $tplFile );
            }
        }
        echo $html;
    }

    private function _getBindFunctionWidget( )
    {
        $bindFields = array(
            "admarticlesa" => array(
                array( "value" => "", "name" => "　" ),
                array( "value" => "lib", "name" => "所在库" ),
                array( "value" => "sort", "name" => "所属分类" ),
                array( "value" => "name", "name" => "物品名称" ),
                array( "value" => "number", "name" => "物品编号" ),
                array( "value" => "guige", "name" => "物品规格" ),
                array( "value" => "danwei", "name" => "单位" ),
                array( "value" => "count", "name" => "数量" ),
                array( "value" => "danjia", "name" => "单价" ),
                array( "value" => "jine", "name" => "金额" )
            ),
            "admarticlesb" => array(
                array( "value" => "", "name" => "　" ),
                array( "value" => "lib", "name" => "所在库" ),
                array( "value" => "sort", "name" => "所属分类" ),
                array( "value" => "name", "name" => "物品名称" ),
                array( "value" => "number", "name" => "物品编号" ),
                array( "value" => "guige", "name" => "物品规格" ),
                array( "value" => "danwei", "name" => "单位" ),
                array( "value" => "count", "name" => "数量" ),
                array( "value" => "price", "name" => "单价" )
            ),
            "admarticlesc" => array(
                array( "value" => "", "name" => "　" ),
                array( "value" => "lib", "name" => "所在库" ),
                array( "value" => "sort", "name" => "所属分类" ),
                array( "value" => "name", "name" => "物品名称" ),
                array( "value" => "number", "name" => "物品编号" ),
                array( "value" => "guige", "name" => "物品规格" ),
                array( "value" => "danwei", "name" => "单位" ),
                array( "value" => "count", "name" => "数量" ),
                array( "value" => "price", "name" => "单价" )
            ),
            "admarticlesd" => array(
                array( "value" => "", "name" => "　" ),
                array( "value" => "lib", "name" => "所在库" ),
                array( "value" => "sort", "name" => "所属分类" ),
                array( "value" => "name", "name" => "物品名称" ),
                array( "value" => "number", "name" => "物品编号" ),
                array( "value" => "guige", "name" => "物品规格" ),
                array( "value" => "danwei", "name" => "单位" ),
                array( "value" => "count", "name" => "数量" ),
                array( "value" => "price", "name" => "单价" )
            ),
            "admarticlese" => array(
                array( "value" => "", "name" => "　" ),
                array( "value" => "lib", "name" => "所在库" ),
                array( "value" => "sort", "name" => "所属分类" ),
                array( "value" => "name", "name" => "物品名称" ),
                array( "value" => "number", "name" => "物品编号" ),
                array( "value" => "guige", "name" => "物品规格" ),
                array( "value" => "danwei", "name" => "单位" ),
                array( "value" => "count", "name" => "数量" )
            ),
            "admarticlesf" => array(
                array( "value" => "", "name" => "　" ),
                array( "value" => "lib", "name" => "所在库" ),
                array( "value" => "sort", "name" => "所属分类" ),
                array( "value" => "name", "name" => "物品名称" ),
                array( "value" => "number", "name" => "物品编号" ),
                array( "value" => "guige", "name" => "物品规格" ),
                array( "value" => "danwei", "name" => "单位" ),
                array( "value" => "count", "name" => "数量" )
            )
        );
        $bindFunction = getpar( $_POST, "bindFunction", "" );
        $data[] = array( "value" => "", "name" => "　" );
        switch ( $bindFunction )
        {
        case "jxcRuku" :
        case "jxcChuku" :
            ( "jxc" );
            $cf = new customField( );
            $data[] = array( "value" => "quantity", "name" => "数量" );
            $data[] = array( "value" => "sum", "name" => "金额" );
            $fields = $cf->getAllFieldsByMid( 1 );
            if ( !is_array( $fields ) )
            {
                $fields = array( );
            }
            foreach ( $fields as $v )
            {
                $data[] = array(
                    "value" => $v['field'],
                    "name" => $v['fieldname']
                );
            }
            break;
        case "sqldetail" :
            global $CNOA_SESSION;
            global $CNOA_DB;
            $id = getpar( $_POST, "sqldetailId", 0 );
            $json = $CNOA_DB->db_getfield( "map", "abutment_sqldetail", "WHERE `id`=".$id );
            if ( empty( $json ) )
            {
                break;
            }
            $temp = json_decode( $json, TRUE );
            $i = 1;
            foreach ( $temp as $key => $value )
            {
                if ( !empty( $value['mapName'] ) )
                {
                    $data[$i]['value'] = $value['name'];
                    $data[$i]['name'] = $value['mapName'];
                    ++$i;
                }
            }
            break;
            $data = $bindFields[$bindFunction];
        }
        ( );
        $ds = new dataStore( );
        $ds->data = is_array( $data ) ? $data : array( );
        echo $ds->makeJsonData( );
    }

    private function _getDataSource( )
    {
        ( );
        $datasource = new wfDatasource( );
        ( );
        $ds = new dataStore( );
        $ds->data = $datasource->getDatasource( );
        echo $ds->makeJsonData( );
    }

    private function _getSourceFields( )
    {
        $tag = getpar( $_POST, "tag" );
        ( );
        $datasource = new wfDatasource( );
        ( );
        $ds = new dataStore( );
        $ds->data = $datasource->getSourceFields( $tag );
        echo $ds->makeJsonData( );
    }

    private function _loadFormItemsForOrder( )
    {
        global $CNOA_DB;
        $flowId = getpar( $_POST, "flowId", 0 );
        $fieldInfo = $CNOA_DB->db_select( array( "id", "name" ), $this->t_set_field, "WHERE `flowId`='".$flowId."' ORDER BY `id` ASC" );
        if ( !is_array( $fieldInfo ) )
        {
            $fieldInfo = array( );
        }
        $data = array( );
        foreach ( $fieldInfo as $k => $v )
        {
            $data[] = array(
                "fieldid" => $v['id'],
                "fieldname" => $v['name']
            );
        }
        ( );
        $dataStore = new dataStore( );
        $dataStore->data = $data;
        echo $dataStore->makeJsonData( );
        exit( );
    }

    private function _saveFormItemsOrder( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $flowId = ( integer )getpar( $_POST, "flowId", 0 );
        $order = $_POST['order'];
        $order = json_decode( $order );
        if ( !is_array( $order ) )
        {
            msg::callback( FALSE, "error" );
        }
        $CNOA_DB->db_update( array(
            "order" => count( $order ) + 1
        ), $this->t_set_field, "WHERE `flowId`='".$flowId."'" );
        foreach ( $order as $k => $v )
        {
            $id = addslashes( intval( $v ) );
            $CNOA_DB->db_update( array(
                "order" => $k + 1
            ), $this->t_set_field, "WHERE `flowId`='".$flowId."' AND `id`='{$id}'" );
        }
        msg::callback( TRUE, lang( "successopt" ) );
    }

    private function _loadFormHtmlForOrder( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $flowId = getpar( $_POST, "flowId", 0 );
        $parseForOrder = ( integer )getpar( $_POST, "parseForOrder" );
        $fieldInfo = $CNOA_DB->db_select( array( "id", "name", "order" ), $this->t_set_field, "WHERE `flowId`='".$flowId."' ORDER BY `id` ASC" );
        if ( !is_array( $fieldInfo ) )
        {
            $fieldInfo = array( );
        }
        $fieldInfos = array( );
        foreach ( $fieldInfo as $k => $v )
        {
            $fieldInfos[$v['name']] = array(
                "id" => $v['id'],
                "name" => $v['name'],
                "order" => $v['order']
            );
        }
        $info = $CNOA_DB->db_getone( array( "formHtml" ), $this->t_set_flow, "WHERE `flowId`='".$flowId."'" );
        if ( !$info )
        {
            msg::callback( FALSE, lang( "nodata" ) );
        }
        $html = str_replace( "&#39;", "'", $info['formHtml'] );
        $elements = app::loadapp( "wf", "flowSetForm" )->api_getHtmlElement( $html );
        if ( !is_array( $elements ) )
        {
            $elements = array( );
        }
        foreach ( $elements as $k => $v )
        {
            $elements[$k]['fieldid'] = $fieldInfos[$v['name']]['id'];
            $elements[$k]['order'] = $fieldInfos[$v['name']]['order'];
        }
        ( $elements, $html );
        $fieldFormater = new wfFieldFormaterForPreview( );
        if ( $parseForOrder == 1 )
        {
            $fieldFormater->parseForOrder = TRUE;
        }
        $html = $fieldFormater->crteateFormHtml( );
        $html = "<table id=\"wfFormHtmlForOrder\" class=\"wf_div_cttb\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n\t\t<tr>\r\n\t\t<td class=\"wf_bd wf_tl\"></td>\r\n\t\t<td class=\"wf_bd wf_t\"></td>\r\n\t\t<td class=\"wf_bd wf_tr\"></td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t<td class=\"wf_bd2 wf_l\"></td>\r\n\t\t<td style=\"padding:50px;\" class=\"wf_c wf_form_content\">".$html."</td>\r\n\t\t<td class=\"wf_bd2 wf_r\"></td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t<td class=\"wf_bd wf_bl\"></td>\r\n\t\t<td class=\"wf_bd wf_b\"></td>\r\n\t\t<td class=\"wf_bd wf_br\"></td>\r\n\t\t</tr>\r\n\t\t</table>";
        $info['formHtml'] = $html;
        ( );
        $dataStore = new dataStore( );
        $dataStore->total = 0;
        $dataStore->data = $info;
        echo $dataStore->makeJsonData( );
        exit( );
    }

}

?>
