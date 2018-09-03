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
        $_obfuscate_6RYLWQ�� = $this->checkIdea;
        return $_obfuscate_6RYLWQ��;
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
            $_obfuscate_qx37NM� = strtotime( getpar( $_POST, "stime" ) );
            $_obfuscate_KWKBW4� = strtotime( getpar( $_POST, "etime" ) );
            if ( $_obfuscate_KWKBW4� < $_obfuscate_qx37NM� )
            {
                msg::callback( FALSE, lang( "StimeGreaterThanEtime" ) );
            }
        }
        else
        {
            global $CNOA_DB;
            $this->makeData4Table( );
            $_obfuscate_qx37NM� = getpar( $_POST, "stime" );
            $_obfuscate_KWKBW4� = getpar( $_POST, "etime" );
            $_obfuscate_MtvJpVij = getpar( $_POST, "reason" );
            $_obfuscate_4QMgvg�� = getpar( $_POST, "hour" );
            $_obfuscate_F43zfw�� = getpar( $_POST, "isRest" );
            $_obfuscate_F4AbnVRh = getpar( $_POST, "flowId" );
            $_obfuscate_W8kbIJeYImJLnA�� = getpar( $_POST, "checkfield" );
            $_obfuscate_4QMgvg�� = number_format( $_obfuscate_4QMgvg��, 1 );
            $_obfuscate_F43zfw�� = htmlspecialchars_decode( $_obfuscate_F43zfw�� );
            $_obfuscate_A2du1_je = json_decode( $_obfuscate_F43zfw��, 1 );
            if ( !is_array( $_obfuscate_A2du1_je ) )
            {
                $_obfuscate_A2du1_je = array( );
            }
            foreach ( $_obfuscate_A2du1_je as $_obfuscate_VgKtFeg� )
            {
                if ( !empty( $_obfuscate_VgKtFeg� ) )
                {
                    $_obfuscate_A2du1_je = 1;
                }
                else
                {
                    $_obfuscate_A2du1_je = 0;
                }
            }
            $_obfuscate_lWk5hHye = $CNOA_DB->db_getone( array( "posttime", "uid" ), "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE `uFlowId`='".$this->uFlowId."'" );
            $_obfuscate_PqzkWMU3WN4� = $_obfuscate_lWk5hHye['posttime'];
            $_obfuscate_7Ri3 = $_obfuscate_lWk5hHye['uid'];
            $_obfuscate__Wi6396IheA� = app::loadapp( "main", "user" )->api_getUserNameByUids( $_obfuscate_7Ri3 );
            $_obfuscate_6RYLWQ�� = array( );
            $_obfuscate_6RYLWQ��['uid'] = $_obfuscate_7Ri3;
            $_obfuscate_6RYLWQ��['posttime'] = $_obfuscate_PqzkWMU3WN4�;
            $_obfuscate_6RYLWQ��['stime'] = strtotime( $_obfuscate_qx37NM� );
            $_obfuscate_6RYLWQ��['etime'] = strtotime( $_obfuscate_KWKBW4� );
            $_obfuscate_6RYLWQ��['truename'] = $_obfuscate__Wi6396IheA�;
            $_obfuscate_6RYLWQ��['reason'] = $_obfuscate_MtvJpVij;
            $_obfuscate_6RYLWQ��['hour'] = $_obfuscate_4QMgvg��;
            $_obfuscate_6RYLWQ��['isRest'] = $_obfuscate_A2du1_je;
            $_obfuscate_6RYLWQ��['uFlowId'] = $this->uFlowId;
            $_obfuscate_6RYLWQ��['status'] = 0;
            if ( $this->nextStepUid == 0 )
            {
                $_obfuscate_6RYLWQ��['status'] = 1;
            }
            $_obfuscate_BOv37ISEbxxb04w9 = $this->nextStepType;
            if ( $this->nextStepUid == 0 && $_POST['checkfield'] == "" && $_obfuscate_BOv37ISEbxxb04w9 != 4 )
            {
                msg::callback( FALSE, lang( "noBindingField" ) );
            }
            $_obfuscate_gftfagw� = $CNOA_DB->db_getcount( $this->table_overtime, "WHERE `uFlowId`='".$this->uFlowId."'" );
            if ( $_obfuscate_gftfagw� == 0 )
            {
                $_obfuscate_xs33Yt_k = $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->table_overtime );
            }
            if ( $this->nextStepUid == 0 && $_obfuscate_W8kbIJeYImJLnA�� == $this->bindCheck['idea'][0] )
            {
                $CNOA_DB->db_update( $_obfuscate_6RYLWQ��, $this->table_overtime, "WHERE `uFlowId`='".$this->uFlowId."'" );
                if ( $_obfuscate_A2du1_je != 0 )
                {
                    $_obfuscate_aimHg�� = date( "Y" );
                    $_obfuscate_3y0Y = "INSERT INTO ".tname( "att_takerest" )." (`uid`,`truename`,`year`,`sumHour`) ".( "VALUES ('".$_obfuscate_7Ri3."','{$_obfuscate__Wi6396IheA�}','{$_obfuscate_aimHg��}','{$_obfuscate_4QMgvg��}') " ).( "ON DUPLICATE KEY UPDATE `sumHour`=`sumHour`+'".$_obfuscate_4QMgvg��."'" );
                    $CNOA_DB->query( $_obfuscate_3y0Y );
                }
            }
        }
    }

}

?>
