<?php
//decode by qq2859470

class newsBbsBbs extends newsBbs
{

    public function run( )
    {
        $task = getpar( $_GET, "task", "loadPage" );
        switch ( $task )
        {
        case "loadPage" :
            global $CNOA_CONTROLLER;
            $tplPath = $CNOA_CONTROLLER->appPath."/tpl/default/bbs/main.htm";
            $CNOA_CONTROLLER->loadExtraTpl( $tplPath );
            break;
        case "getIndex" :
            app::loadapp( "news", "bbsIndex" )->run( );
        }
    }

}

?>
