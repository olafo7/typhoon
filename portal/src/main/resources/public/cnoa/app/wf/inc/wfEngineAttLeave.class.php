<?php

class wfEngineAttLeave extends wfEngineInterface implements wfEngineInterface
{

    protected $code = "attLeave";
    private $table_leave = "att_person_leave";

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
        global $CNOA_DB;
        global $CNOA_SESSION;
        if ( !$this->interfaceCheckBind( ) )
        {
            return;
        }
        if ( $this->isNew == "new" )
        {
            $this->apply( );
        }
        else
        {
            $year = date( "Y" );
            $leaveArr = array( "0" => "病假", "1" => "事假", "2" => "年假", "3" => "路途假", "4" => "婚假", "5" => "陪产假", "6" => "产假", "7" => "丧假", "8" => "调休", "9" => "其他" );
            $this->makeData4Table( );
            $reason = getpar( $_POST, "leaveReason" );
            $days = getpar( $_POST, "days" );
            $hour = getpar( $_POST, "hour" );
            $leaveType = getpar( $_POST, "leaveType" );
            $useLeave = getpar( $_POST, "useLeave" );
            $useLeaveHour = getpar( $_POST, "useLeaveHour" );
            $useRest = getpar( $_POST, "useRest" );
            $flowId = getpar( $_POST, "flowId", 0 );
            $checkfield = getpar( $_POST, "checkfield" );
            $time = getpar( $_POST, "time" );
            $time = explode( " 至 ", $time );
            $stime = strtotime( $time[0] );
            $etime = strtotime( $time[1] );
            foreach ( $leaveArr as $key => $value )
            {
                if ( $leaveType == $value )
                {
                    $leaveType = $key;
                }
            }
            $return = $CNOA_DB->db_getone( array( "posttime", "uid" ), "z_wf_t_".$flowId, "WHERE `uFlowId`='".$this->uFlowId."'" );
            $posttime = $return['posttime'];
            $uid = $return['uid'];
            $truename = app::loadapp( "main", "user" )->api_getUserNameByUids( $uid );
            $data = array( );
            $data['uid'] = $uid;
            $data['truename'] = $truename;
            $data['days'] = $days;
            $data['hour'] = $hour;
            $data['leaveType'] = $leaveType;
            $data['reason'] = $reason;
            $data['stime'] = $stime;
            $data['etime'] = $etime;
            $data['posttime'] = $posttime;
            $data['ip'] = getip( );
            $data['uFlowId'] = $this->uFlowId;
            $data['status'] = 0;
            if ( $this->nextStepUid == 0 )
            {
                $data['status'] = 1;
                $data['approver'] = $CNOA_SESSION->get( "UID" );
            }
            $nextStepType = $this->nextStepType;
            if ( $this->nextStepUid == 0 && $_POST['checkfield'] == "" && $nextStepType != 4 )
            {
                msg::callback( FALSE, lang( "noBindingField" ) );
            }
            $count = $CNOA_DB->db_getcount( $this->table_leave, "WHERE `uFlowId`='".$this->uFlowId."'" );
            if ( $count == 0 )
            {
                $CNOA_DB->db_insert( $data, $this->table_leave );
            }
            if ( $this->nextStepUid == 0 && $checkfield == $this->bindCheck['idea'][0] )
            {
                include_once( CNOA_PATH."/app/att/inc/attCommon.class.php" );
                $CNOA_DB->db_update( $data, $this->table_leave, "WHERE `uFlowId`='".$this->uFlowId."'" );
                $insertData = app::loadapp( "att", "Person" )->api_getAttTimeByTimeBucket( $stime, $etime, $uid, "cnum" );
                foreach ( $insertData as $value )
                {
                    $stype = 0;
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
                if ( !empty( $useLeave ) && !empty( $useLeaveHour ) )
                {
                    $open = $CNOA_DB->db_getfield( "open", "att_auto_rest", "WHERE 1" );
                    if ( $open )
                    {
                        $table = "att_annualrest_new";
                    }
                    else
                    {
                        $table = "att_annualrest";
                    }
                    $annualRest = $CNOA_DB->db_getone( array( "annualLeave", "useLeave" ), $table, "WHERE `uid`=".$uid." AND `truename`='{$truename}' AND `year`='{$year}'" );
                    $oneDayHours = $CNOA_DB->db_getfield( "hour", "att_rest_setting", "WHERE 1" ) * 2;
                    $unUse = $annualRest['annualLeave'] - $annualRest['useLeave'];
                    $useLeave = $useLeave * $oneDayHours + $useLeaveHour;
                    if ( $unUse < $useLeave )
                    {
                        msg::callback( FALSE, lang( "AnnualBeyondRemainAnnual" ).",".lang( "user" ).( " ".$truename." " ).lang( "remainAnnual" ).( " ".$unUse." " ).lang( "tian" ) );
                    }
                    $sql = "UPDATE ".tname( $table ).( " SET `useLeave`=`useLeave`+'".$useLeave."' " ).( "WHERE `uid`=".$uid." AND `truename`='{$truename}' AND `year`='{$year}'" );
                    $CNOA_DB->query( $sql );
                }
                if ( !empty( $useRest ) )
                {
                    $hour = $CNOA_DB->db_getfield( "hour", "att_rest_setting" );
                    $useHour = $useRest / 0.5 * $hour;
                    $rest = $CNOA_DB->db_getone( array( "sumHour", "useHour" ), "att_takerest", "WHERE `uid`=".$uid." AND `year`='{$year}' AND `truename`='{$truename}'" );
                    if ( !is_array( $rest ) )
                    {
                        $rest = array( );
                    }
                    $takeRest = ( integer )( ( $rest['sumHour'] - $rest['useHour'] ) / $hour );
                    $takeRest /= 2;
                    $unUse = $takeRest - $rest['useRest'];
                    if ( $unUse < $useRest )
                    {
                        msg::callback( FALSE, lang( "useRestThanRest" )."{$unUse}".lang( "tian" ) );
                        exit( );
                    }
                    $sql = "UPDATE ".tname( "att_takerest" ).( " SET `useRest`=`useRest`+'".$useRest."', `useHour`=`useHour`+'{$useHour}' " ).( "WHERE `uid`=".$uid." AND `truename`='{$truename}' AND `year`='{$year}'" );
                    $CNOA_DB->query( $sql );
                }
            }
        }
    }

    protected function apply( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $truename = $CNOA_SESSION->get( "TRUENAME" );
        $year = date( "Y" );
        $this->makeData4Post( );
        $useLeaveHour = getpar( $_POST, "useLeaveHour" );
        $useLeave = getpar( $_POST, "useLeave" );
        $useRest = getpar( $_POST, "useRest" );
        $days = getpar( $_POST, "days" );
        $time = getpar( $_POST, "time" );
        $time = explode( " 至 ", $time );
        $stime = strtotime( $time[0] );
        $etime = strtotime( $time[1] );
        include_once( CNOA_PATH."/app/att/inc/attCommon.class.php" );
        $days = app::loadapp( "att", "Person" )->api_getAttLeaveDays( $stime, $etime, $uid, "getdays", "0" );
        if ( $etime < $stime )
        {
            msg::callback( FALSE, lang( "StimeGreaterThanEtime" ) );
        }
        if ( 360 < ( $etime - $stime ) / 86400 )
        {
            msg::callback( FALSE, lang( "leaveTimeTooLong" ) );
        }
        if ( !empty( $useLeave ) && !empty( $useLeaveHour ) )
        {
            if ( $days < $useLeave )
            {
                msg::callback( FALSE, lang( "leaveDayLessthanAnnual" ) );
            }
            $oneDayHours = $CNOA_DB->db_getfield( "hour", "att_rest_setting", "WHERE 1" ) * 2;
            $useLeave *= $oneDayHours;
            $open = $CNOA_DB->db_getfield( "open", "att_auto_rest", "WHERE 1" );
            if ( $open )
            {
                $table = "att_annualrest_new";
            }
            else
            {
                $table = "att_annualrest";
            }
            $annual = $CNOA_DB->db_getone( array( "annualLeave", "useLeave" ), $table, "WHERE `uid`=".$uid." AND `year`='{$year}' AND `truename`='{$truename}'" );
            if ( !is_array( $annual ) )
            {
                $annual = array( );
            }
            if ( empty( $annual ) )
            {
                msg::callback( FALSE, lang( "user" ).( " ".$truename." " ).lang( "notSetAnnual" ) );
            }
            $unUse = $annual['annualLeave'] - $annual['useLeave'];
            if ( $unUse < $useLeave + $useLeaveHour )
            {
                $day = ( integer )( $unUse / $oneDayHours );
                $hour = $unUse % $oneDayHours;
                msg::callback( FALSE, lang( "AnnualBeyondRemainAnnual" ).",".lang( "user" ).( " ".$truename." " ).lang( "remainAnnual" ).( " ".$day." " ).lang( "tian" )."{$hour}小时" );
            }
        }
        if ( !empty( $useRest ) )
        {
            $hour = $CNOA_DB->db_getfield( "hour", "att_rest_setting" );
            if ( empty( $hour ) )
            {
                msg::callback( FALSE, lang( "goToAttSet" ) );
            }
            if ( $days < $useRest )
            {
                msg::callback( FALSE, lang( "leaveLessThanTaskRest" ) );
            }
            $rest = $CNOA_DB->db_getone( array( "sumHour", "useHour" ), "att_takerest", "WHERE `uid`=".$uid." AND `year`='{$year}' AND `truename`='{$truename}'" );
            if ( !is_array( $rest ) )
            {
                $rest = array( );
            }
            if ( empty( $rest ) )
            {
                msg::callback( FALSE, lang( "user" ).( " ".$truename." " ).lang( "noTaskRestNotUse" ) );
            }
            $takeRest = ( integer )( ( $rest['sumHour'] - $rest['useHour'] ) / $hour );
            $takeRest /= 2;
            $unUse = $takeRest - $rest['useRest'];
            if ( $unUse < $useRest )
            {
                msg::callback( FALSE, lang( "useRestThanRest" )."{$unUse}".lang( "tian" ) );
            }
        }
    }

}

?>
