<?php

class wfExportImport extends wfCommon
{

    private $uid = 0;

    public function __construct( )
    {
        global $CNOA_SESSION;
        $this->uid = $CNOA_SESSION->get( "UID" );
    }

    public function export( $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        $_obfuscate_7LQQpqDEQJ401w?? = CNOA_PATH_FILE.( "/common/temp/sFlowExport/".$this->uid."/" );
        deldir( $_obfuscate_7LQQpqDEQJ401w?? );
        mkdirs( $_obfuscate_7LQQpqDEQJ401w?? );
        $_obfuscate_7qDAYo85aGA? = $_obfuscate_6RYLWQ?? = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId` = ".$_obfuscate_F4AbnVRh." " );
        if ( CNOA_ISSAAS === TRUE )
        {
            unset( $_obfuscate_7qDAYo85aGA?['domainID'] );
            unset( $_obfuscate_6RYLWQ??['domainID'] );
        }
        file_put_contents( $_obfuscate_7LQQpqDEQJ401w??."flow.xml", serialize( $_obfuscate_6RYLWQ?? ) );
        $_obfuscate_6RYLWQ?? = $CNOA_DB->db_select( "*", $this->t_set_field, "WHERE `flowId` = ".$_obfuscate_F4AbnVRh." " );
        if ( !is_array( $_obfuscate_6RYLWQ?? ) )
        {
            $_obfuscate_6RYLWQ?? = array( );
        }
        if ( CNOA_ISSAAS === TRUE )
        {
            foreach ( $_obfuscate_6RYLWQ?? as $_obfuscate_Vwty => $_obfuscate_6A?? )
            {
                unset( $_obfuscate_6RYLWQ?? = array( )['domainID'] );
            }
        }
        file_put_contents( $_obfuscate_7LQQpqDEQJ401w??."field.xml", serialize( $_obfuscate_6RYLWQ?? ) );
        $_obfuscate_6RYLWQ?? = $CNOA_DB->db_select( "*", $this->t_set_field_detail, "WHERE `flowId` = ".$_obfuscate_F4AbnVRh." " );
        if ( !is_array( $_obfuscate_6RYLWQ?? ) )
        {
            $_obfuscate_6RYLWQ?? = array( );
        }
        if ( CNOA_ISSAAS === TRUE )
        {
            foreach ( $_obfuscate_6RYLWQ?? as $_obfuscate_Vwty => $_obfuscate_6A?? )
            {
                unset( $_obfuscate_6RYLWQ?? = array( )['domainID'] );
            }
        }
        file_put_contents( $_obfuscate_7LQQpqDEQJ401w??."field_detail.xml", serialize( $_obfuscate_6RYLWQ?? ) );
        $_obfuscate_6RYLWQ?? = $CNOA_DB->db_select( "*", $this->t_set_step, "WHERE `flowId` = ".$_obfuscate_F4AbnVRh." " );
        if ( !is_array( $_obfuscate_6RYLWQ?? ) )
        {
            $_obfuscate_6RYLWQ?? = array( );
        }
        if ( CNOA_ISSAAS === TRUE )
        {
            foreach ( $_obfuscate_6RYLWQ?? as $_obfuscate_Vwty => $_obfuscate_6A?? )
            {
                unset( $_obfuscate_6RYLWQ?? = array( )['domainID'] );
            }
        }
        file_put_contents( $_obfuscate_7LQQpqDEQJ401w??."step.xml", serialize( $_obfuscate_6RYLWQ?? ) );
        $_obfuscate_6RYLWQ?? = $CNOA_DB->db_select( "*", $this->t_set_step_fields, "WHERE `flowId` = ".$_obfuscate_F4AbnVRh." " );
        if ( !is_array( $_obfuscate_6RYLWQ?? ) )
        {
            $_obfuscate_6RYLWQ?? = array( );
        }
        if ( CNOA_ISSAAS === TRUE )
        {
            foreach ( $_obfuscate_6RYLWQ?? as $_obfuscate_Vwty => $_obfuscate_6A?? )
            {
                unset( $_obfuscate_6RYLWQ?? = array( )['domainID'] );
            }
        }
        file_put_contents( $_obfuscate_7LQQpqDEQJ401w??."step_fields.xml", serialize( $_obfuscate_6RYLWQ?? ) );
        $_obfuscate_6RYLWQ?? = $CNOA_DB->db_select( "*", $this->t_s_bingfa_condition, "WHERE `flowId` = ".$_obfuscate_F4AbnVRh." " );
        if ( !is_array( $_obfuscate_6RYLWQ?? ) )
        {
            $_obfuscate_6RYLWQ?? = array( );
        }
        if ( CNOA_ISSAAS === TRUE )
        {
            foreach ( $_obfuscate_6RYLWQ?? as $_obfuscate_Vwty => $_obfuscate_6A?? )
            {
                unset( $_obfuscate_6RYLWQ?? = array( )['domainID'] );
            }
        }
        file_put_contents( $_obfuscate_7LQQpqDEQJ401w??."bingfa_condition.xml", serialize( $_obfuscate_6RYLWQ?? ) );
        unset( $_obfuscate_6RYLWQ?? );
        ( $_obfuscate_7LQQpqDEQJ401w??."flowExport.zip" );
        $_obfuscate_16TN = new zipcmd( );
        $_obfuscate_16TN->setBaseDir( $_obfuscate_7LQQpqDEQJ401w?? );
        $_obfuscate_16TN->setNoDirectory( TRUE );
        $_obfuscate_16TN->add( $_obfuscate_7LQQpqDEQJ401w??."flow.xml" );
        $_obfuscate_16TN->add( $_obfuscate_7LQQpqDEQJ401w??."field.xml" );
        $_obfuscate_16TN->add( $_obfuscate_7LQQpqDEQJ401w??."field_detail.xml" );
        $_obfuscate_16TN->add( $_obfuscate_7LQQpqDEQJ401w??."step.xml" );
        $_obfuscate_16TN->add( $_obfuscate_7LQQpqDEQJ401w??."step_fields.xml" );
        $_obfuscate_16TN->add( $_obfuscate_7LQQpqDEQJ401w??."bingfa_condition.xml" );
        if ( $_obfuscate_7qDAYo85aGA?['tplSort'] == "1" || $_obfuscate_7qDAYo85aGA?['tplSort'] == "3" )
        {
            $_obfuscate_XyJAkFQbDDd0vg?? = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/doc.history.0.php" );
        }
        if ( $_obfuscate_7qDAYo85aGA?['tplSort'] == "2" )
        {
            $_obfuscate_XyJAkFQbDDd0vg?? = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/xls.history.0.php" );
        }
        if ( in_array( $_obfuscate_7qDAYo85aGA?['tplSort'], array( "1", "2", "3" ) ) )
        {
            touch( $_obfuscate_7LQQpqDEQJ401w??."ms.xml" );
            if ( file_exists( $_obfuscate_XyJAkFQbDDd0vg?? ) )
            {
                copy( $_obfuscate_XyJAkFQbDDd0vg??, $_obfuscate_7LQQpqDEQJ401w??."ms.xml" );
            }
            $_obfuscate_16TN->add( $_obfuscate_7LQQpqDEQJ401w??."ms.xml" );
        }
        $_obfuscate_16TN->make( );
        $_obfuscate_0LTM = bin2hex( file_get_contents( $_obfuscate_7LQQpqDEQJ401w??."flowExport.zip" ) );
        $_obfuscate_0LTM = "1234".substr( $_obfuscate_0LTM, 4, strlen( $_obfuscate_0LTM ) );
        file_put_contents( $_obfuscate_7LQQpqDEQJ401w??."flowExport.zip", hex2bin( $_obfuscate_0LTM ) );
        msg::callback( TRUE, "ok" );
    }

    public function downloadExportedFile( )
    {
        global $CNOA_DB;
        $_obfuscate_7LQQpqDEQJ401w?? = CNOA_PATH_FILE.( "/common/temp/sFlowExport/".$this->uid."/" );
        $_obfuscate_7qDAYo85aGA? = unserialize( file_get_contents( $_obfuscate_7LQQpqDEQJ401w??."flow.xml" ) );
        if ( file_exists( $_obfuscate_7LQQpqDEQJ401w??."flowExport.zip" ) )
        {
            if ( $CNOA_DB )
            {
                $CNOA_DB->close( );
            }
            @ini_set( "zlib.output_compression", "Off" );
            header( "Content-Type: application/octet-stream" );
            header( "Content-Disposition: attachment;filename=[".cn_urlencode( "?¡¤£¤????¦Ì?" )."]".cn_urlencode( $_obfuscate_7qDAYo85aGA?['name'] ).".xml" );
            header( "Content-Length: ".filesize( $_obfuscate_7LQQpqDEQJ401w??."flowExport.zip" ) );
            ob_clean( );
            flush( );
            readfile( $_obfuscate_7LQQpqDEQJ401w??."flowExport.zip" );
            exit( );
        }
        echo "<script>alert('?¡¥???o?¡è¡À¨¨¡ä£¤???');</script>";
        exit( );
    }

    public function importFlow( )
    {
        $_obfuscate_7LQQpqDEQJ401w?? = CNOA_PATH_FILE.( "/common/temp/sFlowExport/".$this->uid."/" );
        deldir( $_obfuscate_7LQQpqDEQJ401w?? );
        mkdirs( $_obfuscate_7LQQpqDEQJ401w?? );
        $_obfuscate_OESonJ_jLYc? = $_obfuscate_7LQQpqDEQJ401w??."flowExport.zip";
        if ( cnoa_move_uploaded_file( $_FILES['data']['tmp_name'], $_obfuscate_OESonJ_jLYc? ) )
        {
            $_obfuscate_0LTM = bin2hex( file_get_contents( $_obfuscate_OESonJ_jLYc? ) );
            $_obfuscate_0LTM = "504b".substr( $_obfuscate_0LTM, 4, strlen( $_obfuscate_0LTM ) );
            file_put_contents( $_obfuscate_OESonJ_jLYc?, hex2bin( $_obfuscate_0LTM ) );
            ( );
            $_obfuscate_16TN = new unzip( );
            $_obfuscate_16TN->Extract( $_obfuscate_OESonJ_jLYc?, $_obfuscate_7LQQpqDEQJ401w?? );
            if ( !file_exists( $_obfuscate_7LQQpqDEQJ401w??."flow.xml" ) )
            {
                deldir( $_obfuscate_7LQQpqDEQJ401w?? );
                msg::callback( FALSE, lang( "importFailureNotCorrect" ) );
            }
            $this->startImport( );
            msg::callback( TRUE, lang( "successopt" ) );
        }
        else
        {
            msg::callback( FALSE, lang( "uploadFail" ) );
        }
    }

    private function startImport( )
    {
        global $CNOA_DB;
        $_obfuscate_v1GprsIz = getpar( $_POST, "sortId", 0 );
        $_obfuscate_7LQQpqDEQJ401w?? = CNOA_PATH_FILE.( "/common/temp/sFlowExport/".$this->uid."/" );
        $_obfuscate_7qDAYo85aGA? = unserialize( file_get_contents( $_obfuscate_7LQQpqDEQJ401w??."flow.xml" ) );
        unset( $_obfuscate_7qDAYo85aGA?['flowId'] );
        $_obfuscate_7qDAYo85aGA?['sortId'] = $_obfuscate_v1GprsIz;
        $_obfuscate_7qDAYo85aGA?['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_7qDAYo85aGA?['nameRuleId'] = 0;
        $_obfuscate_7qDAYo85aGA?['uid'] = $this->uid;
        $_obfuscate_7qDAYo85aGA?['status'] = 0;
        $_obfuscate_vpZO7cBY1GZYtnU? = $_obfuscate_7qDAYo85aGA?['startStepId'];
        $_obfuscate_rYSg5Wsph1R8cqE? = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `name`='".$_obfuscate_7qDAYo85aGA?['name']."'" );
        if ( $_obfuscate_rYSg5Wsph1R8cqE? !== FALSE )
        {
            $_obfuscate_7qDAYo85aGA?['name'] = $_obfuscate_7qDAYo85aGA?['name']."[".formatdate( $GLOBALS['CNOA_TIMESTAMP'], "Y-m-d H:i:s" )."]";
        }
        unset( $_obfuscate_7qDAYo85aGA?['bindfunction'] );
        if ( CNOA_ISSAAS === TRUE )
        {
            $GLOBALS['GLOBALS']['CNOA_SKIPCHANGE'] = TRUE;
            $_obfuscate_7qDAYo85aGA?['domainID'] = $GLOBALS['CNOA_DOMAIN_ID'];
            $_obfuscate_F4AbnVRh = $CNOA_DB->db_insert( $this->trimData( $_obfuscate_7qDAYo85aGA? ), $this->t_set_flow );
            unset( $GLOBALS['CNOA_SKIPCHANGE'] );
        }
        else
        {
            $_obfuscate_F4AbnVRh = $CNOA_DB->db_insert( $this->trimData( $_obfuscate_7qDAYo85aGA? ), $this->t_set_flow );
        }
        $_obfuscate_EdcUyMWd6ZEv = unserialize( file_get_contents( $_obfuscate_7LQQpqDEQJ401w??."field.xml" ) );
        $_obfuscate_0fhmJhdCUuty0v = array( );
        if ( is_array( $_obfuscate_EdcUyMWd6ZEv ) )
        {
            foreach ( $_obfuscate_EdcUyMWd6ZEv as $_obfuscate_6A?? )
            {
                $_obfuscate_0W8? = $_obfuscate_6A??['id'];
                unset( $_obfuscate_6A??['id'] );
                $_obfuscate_6A??['flowId'] = $_obfuscate_F4AbnVRh;
                $_obfuscate_0fhmJhdCUuty0v[$_obfuscate_0W8?] = $CNOA_DB->db_insert( $this->trimData( $_obfuscate_6A?? ), $this->t_set_field );
            }
        }
        $_obfuscate_nBydPL5h7SXwp9suqcvC = unserialize( file_get_contents( $_obfuscate_7LQQpqDEQJ401w??."field_detail.xml" ) );
        $_obfuscate_G18OyNG_KDAL7oldBW1fGRNR = array( );
        if ( is_array( $_obfuscate_nBydPL5h7SXwp9suqcvC ) )
        {
            foreach ( $_obfuscate_nBydPL5h7SXwp9suqcvC as $_obfuscate_6A?? )
            {
                $_obfuscate_0W8? = $_obfuscate_6A??['id'];
                unset( $_obfuscate_6A??['id'] );
                $_obfuscate_6A??['flowId'] = $_obfuscate_F4AbnVRh;
                $_obfuscate_6A??['fid'] = $_obfuscate_0fhmJhdCUuty0v[$_obfuscate_6A??['fid']];
                $_obfuscate_G18OyNG_KDAL7oldBW1fGRNR[$_obfuscate_0W8?] = $CNOA_DB->db_insert( $this->trimData( $_obfuscate_6A?? ), $this->t_set_field_detail );
            }
        }
        $_obfuscate_5NhzjnJq_f8? = unserialize( file_get_contents( $_obfuscate_7LQQpqDEQJ401w??."step.xml" ) );
        if ( is_array( $_obfuscate_5NhzjnJq_f8? ) )
        {
            foreach ( $_obfuscate_5NhzjnJq_f8? as $_obfuscate_6A?? )
            {
                $_obfuscate_0W8? = $_obfuscate_6A??['id'];
                unset( $_obfuscate_6A??['id'] );
                $_obfuscate_6A??['flowId'] = $_obfuscate_F4AbnVRh;
                $CNOA_DB->db_insert( $this->trimData( $_obfuscate_6A?? ), $this->t_set_step );
                $_obfuscate_KGKwENp2JbR5yZwSD4? = array( );
                if ( $_obfuscate_vpZO7cBY1GZYtnU? == $_obfuscate_6A??['stepId'] )
                {
                    $_obfuscate_KGKwENp2JbR5yZwSD4?['firstStep'] = 1;
                }
                else
                {
                    $_obfuscate_KGKwENp2JbR5yZwSD4?['firstStep'] = 0;
                }
                $_obfuscate_KGKwENp2JbR5yZwSD4?['stepId'] = $_obfuscate_6A??['stepId'];
                $_obfuscate_KGKwENp2JbR5yZwSD4?['flowId'] = $_obfuscate_6A??['flowId'];
                $_obfuscate_KGKwENp2JbR5yZwSD4?['rule_d'] = "";
                $CNOA_DB->db_insert( $this->trimData( $_obfuscate_KGKwENp2JbR5yZwSD4? ), $this->t_set_step_user );
            }
        }
        $_obfuscate_tvXXw6FUw4Qg_rnU55M? = unserialize( file_get_contents( $_obfuscate_7LQQpqDEQJ401w??."step_fields.xml" ) );
        if ( is_array( $_obfuscate_tvXXw6FUw4Qg_rnU55M? ) )
        {
            foreach ( $_obfuscate_tvXXw6FUw4Qg_rnU55M? as $_obfuscate_6A?? )
            {
                unset( $_obfuscate_6A??['id'] );
                $_obfuscate_6A??['flowId'] = $_obfuscate_F4AbnVRh;
                $_obfuscate_6A??['fieldId'] = $_obfuscate_0fhmJhdCUuty0v[$_obfuscate_6A??['fieldId']];
                $CNOA_DB->db_insert( $this->trimData( $_obfuscate_6A?? ), $this->t_set_step_fields );
            }
        }
        $_obfuscate_bZnA8YNtfAIXMQeA2FqIQWGWwQ?? = unserialize( file_get_contents( $_obfuscate_7LQQpqDEQJ401w??."bingfa_condition.xml" ) );
        if ( is_array( $_obfuscate_bZnA8YNtfAIXMQeA2FqIQWGWwQ?? ) )
        {
            foreach ( $_obfuscate_bZnA8YNtfAIXMQeA2FqIQWGWwQ?? as $_obfuscate_6A?? )
            {
                unset( $_obfuscate_6A??['id'] );
                $_obfuscate_6A??['flowId'] = $_obfuscate_F4AbnVRh;
                $CNOA_DB->db_insert( $this->trimData( $_obfuscate_6A?? ), $this->t_s_bingfa_condition );
            }
        }
        $this->api_createTable( $_obfuscate_F4AbnVRh, $_obfuscate_7qDAYo85aGA? );
        if ( $_obfuscate_7qDAYo85aGA?['tplSort'] == "1" || $_obfuscate_7qDAYo85aGA?['tplSort'] == "3" )
        {
            $_obfuscate_XyJAkFQbDDd0vg?? = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/doc.history.0.php" );
        }
        if ( $_obfuscate_7qDAYo85aGA?['tplSort'] == "2" )
        {
            $_obfuscate_XyJAkFQbDDd0vg?? = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/xls.history.0.php" );
        }
        if ( in_array( $_obfuscate_7qDAYo85aGA?['tplSort'], array( "1", "2", "3" ) ) && file_exists( $_obfuscate_7LQQpqDEQJ401w??."ms.xml" ) )
        {
            @mkdirs( @dirname( $_obfuscate_XyJAkFQbDDd0vg?? ) );
            @copy( $_obfuscate_7LQQpqDEQJ401w??."ms.xml", $_obfuscate_XyJAkFQbDDd0vg?? );
        }
        deldir( $_obfuscate_7LQQpqDEQJ401w?? );
        app::loadapp( "main", "systemLogs" )->api_addLogs( "add", 3662, $_obfuscate_7qDAYo85aGA?['name'], "?¡¥???£¤?¦Ì??¡§?" );
        msg::callback( TRUE, lang( "importSucess" ) );
    }

    private function trimData( $_obfuscate_6RYLWQ?? )
    {
        if ( !is_array( $_obfuscate_6RYLWQ?? ) )
        {
            $_obfuscate_6RYLWQ?? = array( );
        }
        foreach ( $_obfuscate_6RYLWQ?? as $_obfuscate_5w?? => $_obfuscate_6A?? )
        {
            $_obfuscate_6RYLWQ??[$_obfuscate_5w??] = addslashes( $_obfuscate_6A?? );
        }
        return $_obfuscate_6RYLWQ??;
    }

}

?>
