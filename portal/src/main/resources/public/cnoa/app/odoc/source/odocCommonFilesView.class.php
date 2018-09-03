<?php
//decode by qq2859470

class odocCommonFilesView
{

    private $t_files_dangan = "odoc_files_dangan";
    private $t_read_file = "odoc_read_file";
    private $t_dangan_room = "odoc_files_dangan_room";
    private $t_anjuan_list = "odoc_files_anjuan_list";
    private $t_send_list = "odoc_send_list";
    private $t_receive_list = "odoc_receive_list";
    private $t_tpl_list = "odoc_setting_template_list";
    private $t_setting_word_level = "odoc_setting_word_level";
    private $t_setting_word_hurry = "odoc_setting_word_hurry";
    private $t_flow = "odoc_setting_flow";
    private $t_flow_step = "odoc_setting_flow_step";
    private $t_step = "odoc_step";
    private $t_step_temp = "odoc_step_temp";
    private $t_odoc_data = "odoc_data";
    private $fromType = "send";
    private $table_list = "";

    public function __construct( )
    {
    }

    public function __destruct( )
    {
    }

    public function run( )
    {
        global $CNOA_DB;
        $act = getpar( $_GET, "act", "" );
        switch ( $act )
        {
        case "loadWordFile" :
            $this->_loadWordFile( );
            return;
        }
        $this->_loadInfo( );
    }

    private function _loadInfo( )
    {
        global $CNOA_DB;
        $id = getpar( $_POST, "id", 0 );
        $info = $CNOA_DB->db_getone( "*", $this->t_files_dangan, "WHERE `id`='".$id."'" );
        $level = $CNOA_DB->db_getfield( "title", $this->t_setting_word_level, "WHERE `tid`='".$info['level']."'" );
        $level = !$level ? "----" : $level;
        $a['title'] = "基本信息";
        $a['list'][] = array(
            "title" => "文件标题",
            "value" => $info['title']
        );
        $a['list'][] = array(
            "title" => "密级",
            "value" => $level
        );
        $a['list'][] = array(
            "title" => "件号",
            "value" => $info['filesnum']
        );
        $a['list'][] = array(
            "title" => "文件字号",
            "value" => $info['number']
        );
        $a['list'][] = array(
            "title" => "成文日期",
            "value" => formatdate( $info['senddate'], "Y-m-d" )
        );
        $a['list'][] = array(
            "title" => "责任者",
            "value" => $info['respon']
        );
        $a['list'][] = array(
            "title" => "页号",
            "value" => $info['page']
        );
        $a['list'][] = array(
            "title" => "归档日期",
            "value" => formatdate( $info['collectdate'], "Y-m-d" )
        );
        $a['list'][] = array(
            "title" => "文种",
            "value" => $CNOA_DB->db_getfield( "title", "odoc_files_dangan_wenzhong", "WHERE `id`='".$info['wenzhong']."'" )
        );
        $a['list'][] = array(
            "title" => "档案室",
            "value" => $CNOA_DB->db_getfield( "title", "odoc_files_dangan_room", "WHERE `id`='".$info['danganshi']."'" )
        );
        $a['list'][] = array(
            "title" => "案卷",
            "value" => $CNOA_DB->db_getfield( "title", "odoc_files_anjuan_list", "WHERE `id`='".$info['anjuan']."'" )
        );
        $a['list'][] = array(
            "title" => "备注",
            "value" => nl2br( $info['note'] )
        );
        if ( count( $a['list'] ) % 2 == 1 )
        {
            $a['list'][] = array( "title" => "", "value" => "" );
        }
        $odocInfo = $CNOA_DB->db_getone( "*", $this->t_odoc_data, "WHERE `id`='".$info['fromid']."'" );
        $uFlowId = $odocInfo['uFlowId'];
        $targetPath = ( CNOA_PATH_FILE."/common/odoc/sendForm/".$uFlowId % 300 )."/".$uFlowId.".php";
        $form = @file_get_contents( $targetPath );
        $attach = json_decode( $info['attach'], TRUE );
        if ( !is_array( $attach ) )
        {
            $attach = array( );
        }
        $attachList = json_decode( $odocInfo['attach'], TRUE );
        if ( !is_array( $attachList ) )
        {
            $attachList = array( );
        }
        $attachList = array_merge( $attachList, $attach );
        if ( 0 < count( $attachList ) )
        {
            ( );
            $fs = new fs( );
            $attach = $fs->getDownLoadItems4normal( $attachList, TRUE );
        }
        else
        {
            $attach = "无文件可以查看";
        }
        ( );
        $ds = new dataStore( );
        $ds->data['baseInfo'] = $a;
        $ds->data['form'] = $form;
        $ds->data['attach'] = $attach;
        echo $ds->makeJsonData( );
        exit( );
    }

    private function _loadWordFile( )
    {
        global $CNOA_DB;
        $id = getpar( $_GET, "id", 0 );
        $info = $CNOA_DB->db_getone( "*", $this->t_files_dangan, "WHERE `id`='".$id."'" );
        $odocInfo = $CNOA_DB->db_getone( "*", $this->t_odoc_data, "WHERE `id`='".$info['fromid']."'" );
        $uFlowId = $odocInfo['uFlowId'];
        $flowId = $CNOA_DB->db_getfield( "flowId", "wf_u_flow", "WHERE `uFlowId`='".$uFlowId."'" );
        $docfilePath = CNOA_PATH_FILE.( "/common/wf/use/".$flowId."/{$uFlowId}/doc.history.0.php" );
        if ( file_exists( $docfilePath ) )
        {
            $file = @file_get_contents( $docfilePath );
        }
        else
        {
            $file = "无正文";
        }
        echo $file;
        exit( );
    }

}

?>
