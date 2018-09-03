<?php

class wfForm extends wfCommon
{

    protected $from = NULL;
    protected $uFlowInfo = NULL;
    protected $faqiUid = 0;

    protected function _formatText4Setting( &$_obfuscate_6kJ_st61, $_obfuscate_42oUddZOsSA?, $_obfuscate_7R7jAawdKeMxGQ?? )
    {
        if ( $_obfuscate_42oUddZOsSA? == "int" )
        {
            $_obfuscate_6kJ_st61['decimalPrecision'] = 0;
            if ( $_obfuscate_7R7jAawdKeMxGQ?? == 2 )
            {
                $_obfuscate_6kJ_st61['thousands'] = TRUE;
            }
        }
        else if ( $_obfuscate_42oUddZOsSA? == "float" )
        {
            switch ( $_obfuscate_7R7jAawdKeMxGQ?? )
            {
            case "1" :
                $_obfuscate_6kJ_st61['decimalPrecision'] = 1;
                break;
            case "2" :
                $_obfuscate_6kJ_st61['decimalPrecision'] = 2;
                break;
            case "3" :
                $_obfuscate_6kJ_st61['decimalPrecision'] = 3;
                break;
            case "4" :
                $_obfuscate_6kJ_st61['thousands'] = TRUE;
                $_obfuscate_6kJ_st61['decimalPrecision'] = 1;
                break;
            case "5" :
                $_obfuscate_6kJ_st61['thousands'] = TRUE;
                $_obfuscate_6kJ_st61['decimalPrecision'] = 2;
                break;
            case "6" :
                $_obfuscate_6kJ_st61['thousands'] = TRUE;
                $_obfuscate_6kJ_st61['decimalPrecision'] = 3;
                break;
            case "9" :
                $_obfuscate_6kJ_st61['autoCompleteDecimal'] = TRUE;
                $_obfuscate_6kJ_st61['decimalPrecision'] = 1;
                break;
            case "10" :
                $_obfuscate_6kJ_st61['autoCompleteDecimal'] = TRUE;
                $_obfuscate_6kJ_st61['decimalPrecision'] = 2;
                break;
            case "11" :
                $_obfuscate_6kJ_st61['autoCompleteDecimal'] = TRUE;
                $_obfuscate_6kJ_st61['decimalPrecision'] = 3;
                break;
            case "12" :
                $_obfuscate_6kJ_st61['thousands'] = TRUE;
                $_obfuscate_6kJ_st61['autoCompleteDecimal'] = TRUE;
                $_obfuscate_6kJ_st61['decimalPrecision'] = 1;
                break;
            case "13" :
                $_obfuscate_6kJ_st61['thousands'] = TRUE;
                $_obfuscate_6kJ_st61['autoCompleteDecimal'] = TRUE;
                $_obfuscate_6kJ_st61['decimalPrecision'] = 2;
                break;
            case "14" :
                $_obfuscate_6kJ_st61['thousands'] = TRUE;
                $_obfuscate_6kJ_st61['autoCompleteDecimal'] = TRUE;
                $_obfuscate_6kJ_st61['decimalPrecision'] = 3;
            }
        }
    }

    protected function _getMacroValue( $_obfuscate_p5ZWxr4? )
    {
        global $CNOA_SESSION;
        $_obfuscate_VgKtFeg? = "";
        switch ( $_obfuscate_p5ZWxr4?['dataType'] )
        {
        case "month" :
            $_obfuscate_VgKtFeg? = $this->_getFormatMonth( $_obfuscate_p5ZWxr4?['dataFormat'] );
            break;
        case "quarter" :
            $_obfuscate_VgKtFeg? = $this->_getFormatQuarter( $_obfuscate_p5ZWxr4?['dataFormat'] );
            break;
        case "datetime" :
            $_obfuscate_VgKtFeg? = $this->__formatDatetime( $_obfuscate_p5ZWxr4?['dataFormat'] );
            break;
        case "flowname" :
            $_obfuscate_VgKtFeg? = $this->from == "new" ? "" : $this->uFlowInfo['flowName'];
            break;
        case "flownum" :
            $_obfuscate_VgKtFeg? = $this->from == "new" ? "" : $this->uFlowInfo['flowNumber'];
            break;
        case "createrlname" :
            $_obfuscate_VgKtFeg? = app::loadapp( "main", "user" )->api_getUserNameByUid( $this->faqiUid );
            break;
        case "creatername" :
            $_obfuscate_VgKtFeg? = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $this->faqiUid );
            break;
        case "createrdept" :
            $_obfuscate_vwGQSA?? = app::loadapp( "main", "struct" )->api_getDeptByUid( $this->faqiUid );
            $_obfuscate_VgKtFeg? = $_obfuscate_vwGQSA??['name'];
            break;
        case "createrjob" :
            $_obfuscate_VgKtFeg? = app::loadapp( "main", "job" )->api_getNameByUid( $this->faqiUid );
            break;
        case "createrstation" :
            $_obfuscate_VgKtFeg? = app::loadapp( "main", "station" )->api_getNameByUid( $this->faqiUid );
            break;
        case "loginlname" :
            $_obfuscate_VgKtFeg? = $CNOA_SESSION->get( "USERNAME" );
            break;
        case "loginname" :
            $_obfuscate_VgKtFeg? = $CNOA_SESSION->get( "TRUENAME" );
            break;
        case "logindept" :
            $_obfuscate_VgKtFeg? = $CNOA_SESSION->get( "DEPTNAME" );
            break;
        case "loginjob" :
            $_obfuscate_VgKtFeg? = $CNOA_SESSION->get( "JOBNAME" );
            break;
        case "loginstation" :
            $_obfuscate_VgKtFeg? = $CNOA_SESSION->get( "STATIONNAME" );
            break;
        case "userip" :
            $_obfuscate_VgKtFeg? = getip( );
            break;
        case "moneyconvert" :
        case "holiday" :
            $_obfuscate_VgKtFeg? = $this->getUserHoliday( );
        }
        return $_obfuscate_VgKtFeg?;
    }

    private function getUserHoliday( )
    {
        include_once( CNOA_PATH."/app/att/inc/attCommon.class.php" );
        include_once( CNOA_PATH."/app/att/source/attPerson.class.php" );
        $data = app::loadapp( "att", "personClasses" )->api_getUserHoliday( );
        return "?1¡ä?????¡À ".$data['annualLeave']." ?¡è??????a??? {$data['unUseLeave']} ?¡è??????????¨¨¡ã???? {$data['takeRest']} ?¡ã????";
    }

    private function _getFormatMonth( $_obfuscate_e7PLR79F )
    {
        switch ( $_obfuscate_e7PLR79F )
        {
        case "100" :
            $_obfuscate_6RYLWQ?? = date( "m???", $GLOBALS['CNOA_TIMESTAMP'] );
            return $_obfuscate_6RYLWQ??;
        case "200" :
            $_obfuscate_6RYLWQ?? = date( "Ym", $GLOBALS['CNOA_TIMESTAMP'] );
            return $_obfuscate_6RYLWQ??;
        case "300" :
            $_obfuscate_6RYLWQ?? = date( "Y-m", $GLOBALS['CNOA_TIMESTAMP'] );
            return $_obfuscate_6RYLWQ??;
        case "400" :
            $_obfuscate_6RYLWQ?? = date( "Y?1¡äm???", $GLOBALS['CNOA_TIMESTAMP'] );
        }
        return $_obfuscate_6RYLWQ??;
    }

    private function _getFormatQuarter( $_obfuscate_e7PLR79F )
    {
        $_obfuscate_3etEdseqXg?? = floor( ( date( "n", $GLOBALS['CNOA_TIMESTAMP'] ) - 1 ) / 3 ) + 1;
        switch ( $_obfuscate_e7PLR79F )
        {
        case "100" :
            $_obfuscate_6RYLWQ?? = "Q".$_obfuscate_3etEdseqXg??;
            return $_obfuscate_6RYLWQ??;
        case "200" :
            $_obfuscate_6RYLWQ?? = "???".$_obfuscate_3etEdseqXg??."?-¡ê";
        }
        return $_obfuscate_6RYLWQ??;
    }

    protected function _getDataFormat( $_obfuscate_7R7jAawdKeMxGQ?? )
    {
        $_obfuscate_a0NiqdNhfFI? = FALSE;
        $_obfuscate__bfjxSE3ZNg? = TRUE;
        switch ( $_obfuscate_7R7jAawdKeMxGQ?? )
        {
        case 100 :
            $_obfuscate_7R7jAawdKeMxGQ?? = "Y-m-d";
            break;
        case 200 :
            $_obfuscate_7R7jAawdKeMxGQ?? = "Y-m";
            break;
        case 400 :
            $_obfuscate_7R7jAawdKeMxGQ?? = "Ymd";
            break;
        case 600 :
            $_obfuscate_7R7jAawdKeMxGQ?? = "Y?1¡äm???";
            $_obfuscate__bfjxSE3ZNg? = array( "year" => TRUE, "month" => TRUE );
            break;
        case 700 :
            $_obfuscate_7R7jAawdKeMxGQ?? = "Y?1¡äm???d??£¤";
            break;
        case 900 :
            $_obfuscate_7R7jAawdKeMxGQ?? = "Y.m";
            $_obfuscate__bfjxSE3ZNg? = array( "year" => TRUE, "month" => TRUE );
            break;
        case 1000 :
            $_obfuscate_7R7jAawdKeMxGQ?? = "Y.m.d";
            break;
        case 1200 :
            $_obfuscate_7R7jAawdKeMxGQ?? = "Y-m-d H:i";
            $_obfuscate_a0NiqdNhfFI? = TRUE;
            break;
        case 1300 :
            $_obfuscate_7R7jAawdKeMxGQ?? = "Y?1¡äm???d??£¤ H:i";
            $_obfuscate_a0NiqdNhfFI? = TRUE;
        }
        return array(
            "format" => $_obfuscate_7R7jAawdKeMxGQ??,
            "showTime" => $_obfuscate_a0NiqdNhfFI?,
            "showDate" => $_obfuscate__bfjxSE3ZNg?
        );
    }

    protected function _getTimeFormat( $_obfuscate_e7PLR79F )
    {
        switch ( $_obfuscate_e7PLR79F )
        {
        case 100 :
            $_obfuscate_e7PLR79F = "H:i";
            return $_obfuscate_e7PLR79F;
        case 200 :
            $_obfuscate_e7PLR79F = "h:i A";
            return $_obfuscate_e7PLR79F;
        case 300 :
            $_obfuscate_e7PLR79F = "h:i a";
        }
        return $_obfuscate_e7PLR79F;
    }

}

?>
