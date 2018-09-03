<?php
//decode by qq2859470

class mainSystemLogin extends model
{

    private $t_table = "system_login_limit";

    public function run( )
    {
        global $CNOA_SESSION;
        $module = getpar( $_GET, "module", "" );
        switch ( $module )
        {
        case "ip" :
            app::loadapp( "main", "systemLoginIp" )->run( );
            exit( );
        case "security" :
            app::loadapp( "main", "systemLoginSecurity" )->run( );
            exit( );
        }
    }

}

?>
