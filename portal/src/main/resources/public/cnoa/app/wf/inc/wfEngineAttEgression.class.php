<?php

class wfEngineAttEgression extends wfEngineInterface implements wfEngineInterface
{

    protected $code = "attEgression";
    private $table_egression = "att_person_egression";

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
            $stime = strtotime( getpar( $_POST, "stime" ) );
            $etime = strtotime( getpar( $_POST, "etime" ) );
            if ( $etime < $stime )
            {
                msg::callback( FALSE, lang( "StimeGreaterThanEtime" ) );
            }
        }
        else
        {
            global $CNOA_DB;
            global $CNOA_SESSION;
            $this->makeData4Table( );
            $stime = strtotime( getpar( $_POST, "stime" ) );
            $etime = strtotime( getpar( $_POST, "etime" ) );
            $address = getpar( $_POST, "address" );
            $reason = getpar( $_POST, "reason" );
            $checkfield = getpar( $_POST, "checkfield" );
            $flowId = getpar( $_POST, "flowId" );
            $result = $CNOA_DB->db_getone( array( "uid", "posttime" ), "z_wf_t_".$flowId, "WHERE `uFlowId`='".$this->uFlowId."'" );
            $uid = $result['uid'];
            $posttime = $result['posttime'];
            $truename = app::loadapp( "main", "user" )->api_getUserNameByUids( $uid );
            $data = array( );
            $data['uid'] = $uid;
            $data['stime'] = $stime;
            $data['etime'] = $etime;
            $data['posttime'] = $posttime;
            $data['address'] = $address;
            $data['reason'] = $reason;
            $data['truename'] = $truename;
            $data['status'] = 0;
            $data['uFlowId'] = $this->uFlowId;
            if ( $this->nextStepUid == 0 )
            {
                $data['status'] = 1;
            }
            $nextStepType = $this->nextStepType;
            if ( $this->nextStepUid == 0 && $_POST['checkfield'] == "" && $nextStepType != 4 )
            {
                msg::callback( FALSE, lang( "noBindingField" ) );
            }
            $count = $CNOA_DB->db_getcount( $this->table_egression, "WHERE `uFlowId`='".$this->uFlowId."'" );
            if ( $count == 0 )
            {
                $CNOA_DB->db_insert( $data, $this->table_egression );
            }
            if ( $this->nextStepUid == 0 && $checkfield == $this->bindCheck['idea'][0] )
            {
                include_once( CNOA_PATH."/app/att/inc/attCommon.class.php" );
                $CNOA_DB->db_update( $data, $this->table_egression, "WHERE `uFlowId`='".$this->uFlowId."'" );
                $insertData = app::loadapp( "att", "Person" )->api_getAttTimeByTimeBucket( $stime, $etime, $uid, "cnum" );
                foreach ( $insertData as $value )
                {
                    $stype = 2;
                    $date = $value['date'];
                    $cnum = $value['cnum'];
                    $value['uid'] = $uid;
                    $value['stype'] = $stype;
                    $where = "WHERE `uid`='".$uid."' AND `date`='{$date}' AND `stype`='{$stype}'";
                    $getCnum = $CNOA_DB->db_getfield( "cnum", "att_status_checktime", $where );
                    if ( empty( $getCnum ) )
                    {
                        $CNOA_DB->db_insert( $value, "att_status_checktime" );
                    }
                    else
                    {
                        $sql = "UPDATE ".tname( "att_status_checktime" ).( " SET `cnum`='".$cnum."' {$where}" );
                        $CNOA_DB->query( $sql );
                    }
                }
            }
        }
    }

}

?>
