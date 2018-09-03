<?php
//decode by qq2859470

class newsNotice extends model
{

    protected $table_list = "news_notice_list";
    protected $table_permit = "news_notice_permit";
    protected $table_permit_dept = "news_notice_permit_dept";
    protected $table_notice_sort = "news_notice_sort";
    protected $table_reader = "news_notice_reader";
    protected $table_system_signature = "system_signature";
    protected $table_notice_tpl = "news_notice_tpl";

    public function actionIndex( )
    {
        app::loadapp( "news", "noticeIndex" )->run( );
    }

    public function actionMgr( )
    {
        app::loadapp( "news", "noticeMgr" )->run( );
    }

    public function actionSort( )
    {
        app::loadapp( "news", "noticeSort" )->run( );
    }

    protected function api_getSortTreeData( )
    {
        global $CNOA_DB;
        $result = $CNOA_DB->db_select( "*", $this->table_notice_sort );
        if ( !is_array( $result ) )
        {
            $result = array( );
        }
        $data = array( );
        foreach ( $result as $value )
        {
            $temp = array( );
            $temp['id'] = $value['sid'];
            $temp['text'] = $value['name'];
            $temp['leaf'] = 1;
            $temp['iconCls'] = "icon-style-page-key";
            $data[] = $temp;
        }
        return $data;
    }

}

?>
