<?php
//decode by qq2859470

class portalsPortals extends model
{

    protected $table_portals = "portals_list";
    protected $table_desktop = "system_desktop";

    public function actionMy( )
    {
        app::loadapp( "portals", "portalsMy" )->run( );
    }

    public function actionSetting( )
    {
        app::loadapp( "portals", "portalsSetting" )->run( );
    }

}

?>
