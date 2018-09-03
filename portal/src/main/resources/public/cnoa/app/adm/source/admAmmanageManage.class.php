<?php
//decode by qq2859470

class admAmmanageManage extends model
{

    private $t_set_dep = "adm_ammanage_set_dep";
    private $t_set_type = "adm_ammanage_set_type";
    private $t_set_add = "adm_ammanage_set_add";
    private $t_set_user = "adm_ammanage_set_user";

    public function run( )
    {
        $task = getpar( $_GET, "task" );
        if ( $task == "loadPage" )
        {
            $this->_loadPage( );
        }
        else if ( $task == "getUserdepStore" )
        {
            app::loadapp( "adm", "ammanageSet" )->api_getAdmUserdepStore( );
        }
        else if ( $task == "getTypeStore" )
        {
            app::loadapp( "adm", "ammanageSet" )->api_getAdmTypeStore( );
        }
        else if ( $task == "getJsonData" )
        {
            $this->_getJsonData( );
        }
    }

    private function _loadPage( )
    {
    }

}

?>
