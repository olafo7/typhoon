<?php
//decode by qq2859470

class userTask extends model
{

    protected $table_reader = "user_task_reader";
    protected $table_list = "user_task_list";
    protected $table_approval = "user_task_approval";
    protected $table_participant = "user_task_participant";
    protected $table_event = "user_task_event";
    protected $table_progress = "user_task_progress";
    protected $table_discuss_list = "user_task_discuss_list";
    protected $table_discuss_content = "user_task_discuss_content";
    protected $table_document = "user_task_document";
    protected $viewUrl = "index.php?app=user&func=task&action=default&task=loadPage&from=view&tid=";
    protected $listUrl = "index.php?app=user&func=task&action=default&task=loadPage&from=list";

    public function actionDefault( )
    {
        app::loadapp( "user", "taskDefault" )->run( );
    }

    public function actionSetting( )
    {
        app::loadapp( "user", "taskSetting" )->run( );
    }

    public function actionTotal( )
    {
        app::loadapp( "user", "taskTotal" )->run( );
    }

    public function api_getTaskListForPlan( $planid )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $info = $CNOA_DB->db_select( array( "tid", "title", "status" ), $this->table_list, "WHERE `from`='plan' AND `fromid`='".$planid."' ORDER BY `posttime` DESC" );
        return $info;
    }

    protected function _getStatus( $type = FALSE )
    {
        global $CNOA_DB;
        $info = $CNOA_DB->db_getone( array( "status", "uids" ), $this->table_approval );
        $signInIDs = explode( ",", $info['uids'] );
        $data['status'] = $info['status'];
        if ( !$type )
        {
            $data['signInIDs'] = $info['uids'];
            $data['signInNames'] = $this->_formateName( $signInIDs );
        }
        return $data;
    }

    protected function _formateName( $value )
    {
        if ( !is_array( $value ) )
        {
            $value = array( );
        }
        if ( array_sum( $value ) == "0" )
        {
            return "请选择人员";
        }
        if ( !is_array( $value ) )
        {
            $value = array( );
        }
        foreach ( $value as $v )
        {
            $name .= $this->_takeUserName( $v ).",";
        }
        return $name;
    }

    protected function _takeUserName( $id )
    {
        $data = app::loadapp( "main", "user" )->api_getUserDataByUid( $id );
        return $data['truename'];
    }

}

?>
