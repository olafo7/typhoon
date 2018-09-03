<?php

class wfEngineAttAgain extends wfEngineInterface implements wfEngineInterface
{

    protected $code = "attAgain";
    private $table_again = "att_person_again";
    private $table_user_time = "att_user_checktime";
    private $table_user_status = "att_status_checktime";

    public function __construct( )
    {
        $FN_-2147483647( );
    }

    protected function init( )
    {
        global $CNOA_DB;
        $data = $this->checkIdea;
        return $data;
    }

    public function runWithoutBindingStep( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        if ( !$this->interfaceCheckBind( ) )
        {
            return;
        }
        $classes = array( "0" => "第一次打卡时间", "1" => "第二次打卡时间", "2" => "第三次打卡时间", "3" => "第四次打卡时间" );
        $this->makeData4Table( );
        $againDate = strtotime( getpar( $_POST, "againDate" ) );
        $reason = getpar( $_POST, "reason" );
        $againClasses = getpar( $_POST, "againClasses" );
        $flowId = getpar( $_POST, "flowId" );
        foreach ( $classes as $key => $value )
        {
            if ( $value === $againClasses )
            {
                $againClasses = $key;
            }
        }
        if ( $this->isNew == "new" )
        {
            $this->checkAttAgain( $againClasses, $againDate );
        }
        $data = array( );
        $data['againDate'] = $againDate;
        $data['againClasses'] = $againClasses;
        $data['reason'] = $reason;
        $data['uFlowId'] = $this->uFlowId;
        $data['status'] = 0;
        if ( $this->nextStepUid == 0 )
        {
            $return = $CNOA_DB->db_getone( array( "posttime", "uid" ), "z_wf_t_".$flowId, "WHERE `uFlowId`='".$this->uFlowId."'" );
            $posttime = $return['posttime'];
            $uid = $return['uid'];
            $data['posttime'] = $posttime;
            $data['uid'] = $uid;
            $data['approver'] = $CNOA_SESSION->get( "UID" );
            $data['status'] = 1;
        }
        $count = $CNOA_DB->db_getcount( $this->table_again, "WHERE `uFlowId`='".$this->uFlowId."'" );
        if ( $count == 0 )
        {
            $CNOA_DB->db_insert( $data, $this->table_again );
        }
        $nextStepType = $this->nextStepType;
        if ( $this->nextStepUid == 0 && $_POST['checkfield'] == "" && $nextStepType != 4 )
        {
            msg::callback( FALSE, lang( "noBindingField" ) );
        }
        if ( $this->nextStepUid == 0 && $_POST['checkfield'] == $this->bindCheck['idea'][0] )
        {
            $result = app::loadappinc( "att", "attCommon" )->api_getOneDateClasses( $againDate, $uid );
            switch ( $againClasses )
            {
            case "0" :
                $time = $result['oneStime'];
                break;
            case "1" :
                $time = $result['oneEtime'];
                break;
            case "2" :
                $time = $result['twoStime'];
                break;
            case "3" :
                $time = $result['twoEtime'];
            }
            $insertData = array( );
            $insertData['time'] = $time;
            $insertData['cname'] = $result['name'];
            $insertData['truename'] = $result['truename'];
            $insertData['cnum'] = $againClasses;
            $insertData['date'] = $againDate;
            $insertData['type'] = 3;
            $insertData['regType'] = "again";
            $insertData['uid'] = $uid;
            $fields = "(`".implode( "`,`", array_keys( $insertData ) )."`)";
            $value = "('".implode( "','", $insertData )."')";
            $sql = "INSERT INTO ".tname( $this->table_user_time )." ".$fields." VALUES ".$value." ON DUPLICATE KEY UPDATE `time`= VALUES(`time`), `type`=VALUES(`type`), `regType`=VALUES(`regType`)";
            $CNOA_DB->query( $sql );
            $CNOA_DB->db_update( $data, $this->table_again, "WHERE `uFlowId`=".$this->uFlowId );
        }
    }

    private function checkAttAgain( $againClasses, $againDate )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $uid = $CNOA_SESSION->get( "UID" );
        $classes = getpar( $_POST, "againClasses" );
        $date = getpar( $_POST, "againDate" );
        $userCnum = $CNOA_DB->db_getfield( "cnum", $this->table_user_status, "WHERE `uid`=".$uid." AND `date`={$againDate} AND `stype`=0" );
        if ( !empty( $userCnum ) )
        {
            $userCnum = explode( ",", $userCnum );
            if ( in_array( $againClasses, $userCnum ) )
            {
                msg::callback( FALSE, "[".$date."]{$classes}处于请假状态，不允许补卡!" );
            }
        }
    }

}

?>
