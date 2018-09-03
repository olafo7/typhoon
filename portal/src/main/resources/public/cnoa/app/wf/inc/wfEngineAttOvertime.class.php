<?php

class wfEngineAttOvertime extends wfEngineInterface implements wfEngineInterface
{

    protected $code = "attOvertime";
    private $table_overtime = "att_person_overtime";

    public function __construct( )
    {
        $FN_-2147483647( );
    }

    protected function init( )
    {
        global $CNOA_DB;
        $_obfuscate_6RYLWQÿÿ = $this->checkIdea;
        return $_obfuscate_6RYLWQÿÿ;
    }

    public function runWithoutBindingStep( )
    {
        if ( !$this->interfaceCheckBind( ) )
        {
            return;
        }
        if ( $this->isNew == "new" )
        {
            $this->makeData4Post( );
            $_obfuscate_qx37NMÿ = strtotime( getpar( $_POST, "stime" ) );
            $_obfuscate_KWKBW4ÿ = strtotime( getpar( $_POST, "etime" ) );
            if ( $_obfuscate_KWKBW4ÿ < $_obfuscate_qx37NMÿ )
            {
                msg::callback( FALSE, lang( "StimeGreaterThanEtime" ) );
            }
        }
        else
        {
            global $CNOA_DB;
            $this->makeData4Table( );
            $_obfuscate_qx37NMÿ = getpar( $_POST, "stime" );
            $_obfuscate_KWKBW4ÿ = getpar( $_POST, "etime" );
            $_obfuscate_MtvJpVij = getpar( $_POST, "reason" );
            $_obfuscate_4QMgvgÿÿ = getpar( $_POST, "hour" );
            $_obfuscate_F43zfwÿÿ = getpar( $_POST, "isRest" );
            $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId" );
            $_obfuscate_W8kbIJeYImJLnAÿÿ = getpar( $_POST, "checkfield" );
            $_obfuscate_4QMgvgÿÿ = number_format( $_obfuscate_4QMgvgÿÿ, 1 );
            $_obfuscate_F43zfwÿÿ = htmlspecialchars_decode( $_obfuscate_F43zfwÿÿ );
            $_obfuscate_A2du1_je = json_decode( $_obfuscate_F43zfwÿÿ, 1 );
            if ( !is_array( $_obfuscate_A2du1_je ) )
            {
                $_obfuscate_A2du1_je = array( );
            }
            foreach ( $_obfuscate_A2du1_je as $_obfuscate_VgKtFegÿ )
            {
                if ( !empty( $_obfuscate_VgKtFegÿ ) )
                {
                    $_obfuscate_A2du1_je = 1;
                }
                else
                {
                    $_obfuscate_A2du1_je = 0;
                }
            }
            $_obfuscate_lWk5hHye = $CNOA_DB->db_getone( array( "posttime", "uid" ), "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE `uFlowId`='".$this->uFlowId."'" );
            $_obfuscate_PqzkWMU3WN4ÿ = $_obfuscate_lWk5hHye['posttime'];
            $_obfuscate_7Ri3 = $_obfuscate_lWk5hHye['uid'];
            $_obfuscate__Wi6396IheAÿ = app::loadapp( "main", "user" )->api_getUserNameByUids( $_obfuscate_7Ri3 );
            $_obfuscate_6RYLWQÿÿ = array( );
            $_obfuscate_6RYLWQÿÿ['uid'] = $_obfuscate_7Ri3;
            $_obfuscate_6RYLWQÿÿ['posttime'] = $_obfuscate_PqzkWMU3WN4ÿ;
            $_obfuscate_6RYLWQÿÿ['stime'] = strtotime( $_obfuscate_qx37NMÿ );
            $_obfuscate_6RYLWQÿÿ['etime'] = strtotime( $_obfuscate_KWKBW4ÿ );
            $_obfuscate_6RYLWQÿÿ['truename'] = $_obfuscate__Wi6396IheAÿ;
            $_obfuscate_6RYLWQÿÿ['reason'] = $_obfuscate_MtvJpVij;
            $_obfuscate_6RYLWQÿÿ['hour'] = $_obfuscate_4QMgvgÿÿ;
            $_obfuscate_6RYLWQÿÿ['isRest'] = $_obfuscate_A2du1_je;
            $_obfuscate_6RYLWQÿÿ['uFlowId'] = $this->uFlowId;
            $_obfuscate_6RYLWQÿÿ['status'] = 0;
            if ( $this->nextStepUid == 0 )
            {
                $_obfuscate_6RYLWQÿÿ['status'] = 1;
            }
            $_obfuscate_BOv37ISEbxxb04w9 = $this->nextStepType;
            if ( $this->nextStepUid == 0 && $_POST['checkfield'] == "" && $_obfuscate_BOv37ISEbxxb04w9 != 4 )
            {
                msg::callback( FALSE, lang( "noBindingField" ) );
            }
            $_obfuscate_gftfagwÿ = $CNOA_DB->db_getcount( $this->table_overtime, "WHERE `uFlowId`='".$this->uFlowId."'" );
            if ( $_obfuscate_gftfagwÿ == 0 )
            {
                $_obfuscate_xs33Yt_k = $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->table_overtime );
            }
            if ( $this->nextStepUid == 0 && $_obfuscate_W8kbIJeYImJLnAÿÿ == $this->bindCheck['idea'][0] )
            {
                $CNOA_DB->db_update( $_obfuscate_6RYLWQÿÿ, $this->table_overtime, "WHERE `uFlowId`='".$this->uFlowId."'" );
                if ( $_obfuscate_A2du1_je != 0 )
                {
                    $_obfuscate_aimHgÿÿ = date( "Y" );
                    $_obfuscate_3y0Y = "INSERT INTO ".tname( "att_takerest" )." (`uid`,`truename`,`year`,`sumHour`) ".( "VALUES ('".$_obfuscate_7Ri3."','{$_obfuscate__Wi6396IheAÿ}','{$_obfuscate_aimHgÿÿ}','{$_obfuscate_4QMgvgÿÿ}') " ).( "ON DUPLICATE KEY UPDATE `sumHour`=`sumHour`+'".$_obfuscate_4QMgvgÿÿ."'" );
                    $CNOA_DB->query( $_obfuscate_3y0Y );
                }
            }
        }
    }

}

?>
