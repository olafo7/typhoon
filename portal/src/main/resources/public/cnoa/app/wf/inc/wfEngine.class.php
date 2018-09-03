<?php

class wfEngine extends wfCommon
{

    private $bindFunctions = array
    (
        0 => "budgetDept",
        1 => "budgetProj"
    );

    public function __construct( )
    {
        $this->bindFunctions = array_merge( $this->bindFunctions, array_keys( $this->bindFunctionList ) );
    }

    public function run( $uFlowId )
    {
        ( $uFlowId );
        $GLOBALS['GLOBALS']['wfCache'] = new wfCache( );
        $flowFields = $GLOBALS['wfCache']->getFlowFields( );
        try
        {
            do
            {
                if ( !is_array( $flowFields ) )
                {
                    break;
                }
                else
                {
                    foreach ( $flowFields as $v )
                    {
                        if ( !in_array( $v['bindfunction'], $this->bindFunctions ) && !( $subEngine = $this->_getClassObject( $v['bindfunction'] ) ) )
                        {
                            $subEngine->setData( $v['flowId'], $uFlowId, $v['id'] );
                        }
                    }
                    break;
                }
            }
            catch ( Exception $e )
            {
            }
        } while ( 0 );
    }

    public function verify( $from, $flowId, $uFlowId )
    {
        if ( $from == "todo" )
        {
            ( $uFlowId );
            $GLOBALS['GLOBALS']['wfCache'] = new wfCache( );
            $flowFields = $GLOBALS['wfCache']->getFlowFields( );
        }
        else if ( $from == "new" )
        {
            global $CNOA_DB;
            $flowFields = $CNOA_DB->db_select( "*", "wf_s_field", "WHERE `flowId`=".$flowId );
        }
        if ( is_array( $flowFields ) )
        {
            foreach ( $flowFields as $v )
            {
                if ( !in_array( $v['bindfunction'], $this->bindFunctions ) && !( $subEngine = $this->_getClassObject( $v['bindfunction'] ) && method_exists( $subEngine, "verify" ) ) )
                {
                    continue;
                }
                return $subEngine->verify( $v['id'], $flowId, $uFlowId );
            }
        }
        return TRUE;
    }

    public function getQueryData( $bindFunction )
    {
        if ( $subEngine = $this->_getClassObject( $bindFunction ) )
        {
            $subEngine->getData( );
        }
    }

    private function _getClassObject( $bindFunction )
    {
        if ( preg_match( "/^admarticles(.*)/", $bindFunction ) )
        {
            $className = substr( $bindFunction, 0, -1 );
        }
        else
        {
            $className = $bindFunction;
        }
        $class = "wfEngine".ucfirst( $className );
        if ( !class_exists( $class ) )
        {
            return FALSE;
        }
        ( $bindFunction );
        return new $class( );
    }

}

?>
