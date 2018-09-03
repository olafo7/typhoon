<?php

class wfEngineAdmArticles
{

    private $funcs = array
    (
        "admarticlesb" => array
        (
            "record" => 1,
            "apply" => 0
        ),
        "admarticlesc" => array
        (
            "record" => 2,
            "apply" => 3
        ),
        "admarticlesd" => array
        (
            "record" => 3,
            "apply" => 2
        )
    );
    private $bindfunc = NULL;

    public function __construct( $bindfunc )
    {
        if ( !array_key_exists( $bindfunc, $this->funcs ) )
        {
            return FALSE;
        }
        $this->bindfunc = $bindfunc;
    }

    public function getData( )
    {
        $from = getpar( $_GET, "from", "list" );
        switch ( $from )
        {
        case "library" :
            app::loadapp( "adm", "articlesPersonRegister" )->api_getLibrary( );
            break;
        case "type" :
            app::loadapp( "adm", "articlesPersonRegister" )->api_getType( );
            break;
        case "list" :
            app::loadapp( "adm", "articlesPersonRegister" )->api_getList( $this->funcs[$this->bindfunc]['apply'] );
        }
    }

    public function setData( $flowId, $uFlowId, $detailId )
    {
        if ( !array_key_exists( $this->bindfunc, $this->funcs ) )
        {
            return FALSE;
        }
        global $CNOA_DB;
        $table = "z_wf_d_".$flowId."_".$detailId;
        $list = $CNOA_DB->db_select( "*", $table, "WHERE `uFlowId`='".$uFlowId."'" );
        if ( !is_array( $list ) )
        {
            $list = array( );
        }
        $detailList = array( );
        ( $uFlowId );
        $wfCache = new wfCache( );
        $detailFields = $wfCache->getDetailFields( );
        if ( is_array( $detailFields ) )
        {
            foreach ( $detailFields as $v )
            {
                if ( $v['binfield'] == "count" )
                {
                    $detailList[$v['id']] = $v['id'];
                }
            }
            $detailList = array_merge( $detailList );
        }
        $bindList = array( );
        foreach ( $list as $v )
        {
            foreach ( $v as $k1 => $v1 )
            {
                if ( ereg( "D_", $k1 ) )
                {
                    $k2 = intval( str_replace( "D_", "", $k1 ) );
                    if ( in_array( $k2, $detailList ) )
                    {
                        $bindList[$v['bindid']][] = intval( $v1 );
                    }
                }
            }
        }
        $uid = $CNOA_DB->db_getfield( "uid", "wf_u_flow", "WHERE `uFlowId`=".$uFlowId );
        $record = $this->funcs[$this->bindfunc]['record'];
        if ( is_array( $bindList ) )
        {
            foreach ( $bindList as $id => $v1 )
            {
                foreach ( $v1 as $number )
                {
                    app::loadapp( "adm", "articlesInfo" )->api_updateLibraryNumber( $record, $id, $number, $uid );
                }
            }
        }
    }

}

?>
