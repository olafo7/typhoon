<?php
//decode by qq2859470

class newsNews extends model
{

    protected $table_list = "news_news_list";
    protected $table_sort = "news_news_sort";
    protected $table_sort_permit = "news_news_sort_permit";
    protected $table_comment = "news_news_comment";
    protected $table_focus = "news_news_focus";
    protected $table_reader = "news_news_reader";

    const KEY_VIEW = 0;
    const KEY_MANAGE = 1;
    const KEY_RELEASE = 2;

    public function actionPost( )
    {
        app::loadapp( "news", "newsPost" )->run( );
    }

    public function actionView( )
    {
        app::loadapp( "news", "newsView" )->run( );
    }

    public function actionManage( )
    {
        app::loadapp( "news", "newsManage" )->run( );
    }

    public function actionSetting( )
    {
        app::loadapp( "news", "newsSetting" )->run( );
    }

    public function actionRead( )
    {
        app::loadapp( "news", "newsRead" )->run( );
    }

    protected function checkPermit( $sid, $type )
    {
        $sid = ( integer )$sid;
        if ( $sid == 0 )
        {
            return FALSE;
        }
        switch ( $type )
        {
        case self::KEY_VIEW :
            $field = "allowallview";
            break;
        case self::KEY_MANAGE :
            $field = "allowallmanage";
            break;
        case self::KEY_RELEASE :
            $field = "allowallrelease";
            break;
            return FALSE;
        }
        global $CNOA_DB;
        global $CNOA_SESSION;
        if ( $CNOA_DB->db_getfield( $field, $this->table_sort, "WHERE `sid`=".$sid ) )
        {
            return TRUE;
        }
        $where = "WHERE `sid`=".$sid." AND `type`={$type} AND (FIND_IN_SET({$CNOA_SESSION->get( "DID" )}, `dids`) OR FIND_IN_SET({$CNOA_SESSION->get( "UID" )}, `uids`))";
        $count = $CNOA_DB->db_getcount( $this->table_sort_permit, $where );
        if ( $count )
        {
            return TRUE;
        }
        return FALSE;
    }

    protected function getAllowColumn( $type )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        if ( !is_int( $type ) )
        {
            return FALSE;
        }
        $where = "WHERE `type`=".$type." AND (FIND_IN_SET({$CNOA_SESSION->get( "DID" )}, `dids`) OR FIND_IN_SET({$CNOA_SESSION->get( "UID" )}, `uids`))";
        $columns = $CNOA_DB->db_select( array( "sid" ), $this->table_sort_permit, $where );
        if ( !is_array( $columns ) )
        {
            $columns = array( );
        }
        $sids = array( );
        foreach ( $columns as $v )
        {
            $sids[] = $v['sid'];
        }
        switch ( $type )
        {
        case self::KEY_VIEW :
            $field = "allowallview";
            break;
        case self::KEY_MANAGE :
            $field = "allowallmanage";
            break;
        case self::KEY_RELEASE :
            $field = "allowallrelease";
            break;
            return $sids;
        }
        $dblist = $CNOA_DB->db_select( array( "sid" ), $this->table_sort, "WHERE `".$field."`=1 AND `show`=1" );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $v )
        {
            $sids[] = $v['sid'];
        }
        return array_unique( $sids );
    }

    protected function getAllowSort( $type, $format = FALSE )
    {
        global $CNOA_DB;
        $sids = implode( ",", $this->getAllowColumn( $type ) );
        if ( empty( $sids ) )
        {
            return array( );
        }
        $sords = $CNOA_DB->db_select( array( "name", "sid", "type" ), $this->table_sort, "WHERE `sid` IN (".$sids.") AND `show`=1" );
        if ( !is_array( $sords ) )
        {
            $sords = array( );
        }
        if ( $format )
        {
            foreach ( $sords as $k => $v )
            {
                $sords[$k]['text'] = $v['name'];
                $sords[$k]['sid'] = $v['sid'];
                $sords[$k]['iconCls'] = "icon-style-page-key";
                $sords[$k]['leaf'] = TRUE;
                $sords[$k]['href'] = "javascript:void(0);";
            }
            return json_encode( $sords );
        }
        return $sords;
    }

}

?>
