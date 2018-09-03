<?php
//decode by qq2859470

class noticeCheckPeriod extends model
{

    private $table_day = "system_period_notice_day";
    private $table_week = "system_period_notice_week";
    private $table_month = "system_period_notice_month";
    private $time = 0;
    private $where = "";

    public function __construct( )
    {
        $this->time = $GLOBALS['CNOA_TIMESTAMP'];
        $this->where = "WHERE `sdate` = 0 OR `sdate`<'".$this->time."' ";
        $this->delete( );
        $this->updateDay( );
        $this->updateWeek( );
        $this->updateMonth( );
    }

    private function updateDay( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->table_day, $this->where );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $v )
        {
            if ( date( "Y-m-d", $v['lastnoticetime'] - 86400 ) == date( "Y-m-d", $this->time ) )
            {
            }
            $CNOA_DB->db_update( array( ), $this->table_day, "WHERE `nid`='".$v['nid']."'" );
        }
    }

    private function updateWeek( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->table_week, $this->where );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $v )
        {
        }
    }

    private function updateMonth( )
    {
        global $CNOA_DB;
        $dblist = $CNOA_DB->db_select( "*", $this->table_month, $this->where );
        if ( !is_array( $dblist ) )
        {
            $dblist = array( );
        }
        foreach ( $dblist as $k => $v )
        {
        }
    }

    private function delete( )
    {
        global $CNOA_DB;
        $CNOA_DB->db_delete( $this->table_day, "WHERE `edate` < '".$this->time."' " );
        $CNOA_DB->db_delete( $this->table_week, "WHERE `edate` < '".$this->time."' " );
        $CNOA_DB->db_delete( $this->table_month, "WHERE `edate` < '".$this->time."' " );
    }

}

?>
