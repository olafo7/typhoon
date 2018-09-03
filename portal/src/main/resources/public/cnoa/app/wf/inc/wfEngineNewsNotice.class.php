<?php

class wfEngineNewsNotice extends wfEngineInterface implements wfEngineInterface
{

    protected $code = "newsNotice";
    private $table_notice_list = "news_notice_list";

    public function __construct( )
    {
        $FN_-2147483647( );
    }

    protected function init( )
    {
        global $CNOA_DB;
        $_obfuscate_6RYLWQÿÿ = $this->checkIdea;
        return $_obfuscate_6RYLWQÿÿ;
    }

    public function runWithoutBindingStep( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        if ( !$this->interfaceCheckBind( ) )
        {
            return;
        }
        if ( $this->isNew == "new" )
        {
            if ( $this->noticeLid == 0 )
            {
                msg::callback( FALSE, "æ°æ®éè¯¯ï¼è¯·éæ°æä½!" );
            }
            $_obfuscate_3y0Y = "UPDATE ".tname( $this->table_notice_list ).( " SET `uFlowId` = ".$this->uFlowId." WHERE `id`={$this->noticeLid}" );
            $CNOA_DB->query( $_obfuscate_3y0Y );
        }
        $this->makeData4Table( );
        $_obfuscate_W8kbIJeYImJLnAÿÿ = $_POST['checkfield'] == "åæ" ? 1 : 2;
        $_obfuscate_BOv37ISEbxxb04w9 = $this->nextStepType;
        if ( $this->nextStepUid == 0 && $_POST['checkfield'] == "" && $_obfuscate_BOv37ISEbxxb04w9 != 4 )
        {
            msg::callback( FALSE, lang( "noBindingField" ) );
        }
        if ( $this->nextStepUid == 0 && $_obfuscate_BOv37ISEbxxb04w9 != 4 )
        {
            $_obfuscate_3y0Y = "UPDATE ".tname( $this->table_notice_list ).( " SET `approveStatus`=".$_obfuscate_W8kbIJeYImJLnAÿÿ." WHERE `uFlowId`={$this->uFlowId}" );
            $CNOA_DB->query( $_obfuscate_3y0Y );
            $_obfuscate_5ZL98vEÿ = $CNOA_DB->db_getfield( "uid", "wf_u_flow", "WHERE `uFlowId`=".$this->uFlowId );
            $_obfuscate_gkt = $CNOA_DB->db_getone( "*", $this->table_notice_list, "WHERE `uFlowId`=".$this->uFlowId );
            $_obfuscate_obqvewÿÿ = "æ é¢ä¸º[".$_obfuscate_gkt['title']."]å·²ç»å®¡æ¹ï¼ç¹å»æ¥çã";
            $_obfuscate_Xj70RIkÿ = "index.php?app=news&func=notice&action=mgr";
            notice::add( $_obfuscate_5ZL98vEÿ, "æµç¨å®¡æ¹", $_obfuscate_obqvewÿÿ, $_obfuscate_Xj70RIkÿ, 0, 7, $_obfuscate_5ZL98vEÿ );
            $_obfuscate_AVvqwrkLw0ÿ = array( );
            if ( $_obfuscate_gkt['to_all'] )
            {
                $_obfuscate_y3fou6_NPLtfz5cÿ = app::loadapp( "main", "user" )->api_getAllUserList( array( "uid" ) );
                foreach ( $_obfuscate_y3fou6_NPLtfz5cÿ as $_obfuscate_6Aÿÿ )
                {
                    $_obfuscate_AVvqwrkLw0ÿ[] = $_obfuscate_6Aÿÿ['uid'];
                    $this->__sendNotice( $_obfuscate_6Aÿÿ['uid'], $_obfuscate_gkt['title'], $_obfuscate_gkt['id'], $_obfuscate_gkt['stime'] );
                }
            }
            else
            {
                $_obfuscate__eqrEQÿÿ = $CNOA_DB->db_select( array( "uid" ), "news_notice_permit", "WHERE `id`=".$_obfuscate_gkt['id'] );
                $_obfuscate_ze7OWPWQ = $CNOA_DB->db_select( array( "dept" ), "news_notice_permit_dept", "WHERE `id`=".$_obfuscate_gkt['id'] );
                $_obfuscate_ze7OWPWQ = $this->getArray( $_obfuscate_ze7OWPWQ, "dept" );
                if ( empty( $_obfuscate_ze7OWPWQ ) )
                {
                    $_obfuscate_ze7OWPWQ = array( 0 );
                }
                $_obfuscate_PVLK5jra = app::loadapp( "main", "user" )->api_getUserByFids( $_obfuscate_ze7OWPWQ );
                if ( !empty( $_obfuscate__eqrEQÿÿ ) )
                {
                    foreach ( $_obfuscate__eqrEQÿÿ as $_obfuscate_6Aÿÿ )
                    {
                        $_obfuscate_AVvqwrkLw0ÿ[] = $_obfuscate_6Aÿÿ['uid'];
                        $this->__sendNotice( $_obfuscate_6Aÿÿ['uid'], $_obfuscate_gkt['title'], $_obfuscate_gkt['id'], $_obfuscate_gkt['stime'] );
                    }
                }
                if ( !empty( $_obfuscate_PVLK5jra ) )
                {
                    foreach ( $_obfuscate_PVLK5jra as $_obfuscate_6Aÿÿ )
                    {
                        if ( !in_array( $_obfuscate_6Aÿÿ['uid'], $_obfuscate_AVvqwrkLw0ÿ ) )
                        {
                            $_obfuscate_AVvqwrkLw0ÿ[] = $_obfuscate_6Aÿÿ['uid'];
                            $this->__sendNotice( $_obfuscate_6Aÿÿ['uid'], $_obfuscate_gkt['title'], $_obfuscate_gkt['id'], $_obfuscate_gkt['stime'] );
                        }
                    }
                }
            }
            $_obfuscate_gIjdVQÿÿ = "m.php?app=news&func=notice&action=index&task=loadPage&from=view&id=".$_obfuscate_0W8ÿ;
            JPush::push( $_obfuscate_AVvqwrkLw0ÿ, $_obfuscate_gkt['title'], "éç¥å¬å", $_obfuscate_gIjdVQÿÿ, "notice" );
        }
    }

    private function getArray( $_obfuscate_6RYLWQÿÿ, $_obfuscate_YIq2A8cÿ )
    {
        $_obfuscate_ = array( );
        if ( !is_array( $_obfuscate_6RYLWQÿÿ ) )
        {
            $_obfuscate_6RYLWQÿÿ = array( );
        }
        foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_[] = $_obfuscate_VgKtFegÿ[$_obfuscate_YIq2A8cÿ];
        }
        return $_obfuscate_;
    }

    private function __sendNotice( $_obfuscate_5ZL98vEÿ, $_obfuscate_obqvewÿÿ, $_obfuscate_0W8ÿ, $_obfuscate_l70_BOLArQkÿ = 0 )
    {
        $_obfuscate_KR8HIAdlQÿÿ = "å¬å/éç¥";
        $_obfuscate_JwPuTUWI6gÿÿ = string::cut( $_obfuscate_obqvewÿÿ, 100 );
        $_obfuscate_axNwiRAh6wÿÿ = "index.php?app=news&func=notice&action=index&task=loadPage&from=view&id=".$_obfuscate_0W8ÿ;
        $_obfuscate_QeHQn40klQÿÿ = notice::add( $_obfuscate_5ZL98vEÿ, $_obfuscate_KR8HIAdlQÿÿ, $_obfuscate_JwPuTUWI6gÿÿ, $_obfuscate_axNwiRAh6wÿÿ, $_obfuscate_l70_BOLArQkÿ, 7, $_obfuscate_0W8ÿ );
    }

}

?>
