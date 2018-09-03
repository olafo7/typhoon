<?php

class wfCommon extends model
{

    public $clientType = "PC";
    protected $t_set_sort = "wf_s_sort";
    protected $t_set_sort_permit = "wf_s_sort_permit";
    protected $t_set_sort_forbid = "wf_s_sort_forbid";
    protected $t_set_flow = "wf_s_flow";
    protected $t_set_field_detail = "wf_s_field_detail";
    protected $t_set_field = "wf_s_field";
    protected $t_set_form_style = "wf_s_form_style";
    protected $t_set_trust = "wf_s_trust";
    protected $t_set_trust_flow = "wf_s_trust_flow";
    protected $t_set_step = "wf_s_step";
    protected $t_set_step_fields = "wf_s_step_fields";
    protected $t_set_step_user = "wf_s_step_user";
    protected $t_set_step_condition = "wf_s_step_condition";
    protected $t_use_flow = "wf_u_flow";
    protected $t_use_proxy = "wf_u_proxy";
    protected $t_use_proxy_flow = "wf_u_proxy_flow";
    protected $t_use_proxy_uflow = "wf_u_proxy_uflow";
    protected $t_use_fenfa = "wf_u_fenfa";
    protected $t_use_event = "wf_u_event";
    protected $t_use_step = "wf_u_step";
    protected $t_use_step_temptime = "wf_s_step_temptime";
    protected $t_use_step_huiqian = "wf_u_huiqian";
    protected $t_use_step_child_flow = "wf_u_step_child_flow";
    protected $t_set_step_child_kongjian = "wf_s_step_child_kongjian";
    protected $t_use_flowattach = "wf_u_flowattach";
    protected $t_s_print = "wf_s_print";
    protected $t_u_wffav = "wf_u_wffav";
    protected $t_s_bingfa_condition = "wf_s_bingfa_condition";
    protected $t_use_convergence_deal = "wf_u_convergence_deal";
    protected $t_s_rel_permit = "wf_s_rel_permit";
    protected $t_u_setting = "wf_u_setting";
    protected $t_use_sms = "wf_u_sms";
    protected $t_s_flow_other_app_data = "wf_s_flow_other_app_data";
    protected $t_set_step_permit = "wf_s_step_permit";
    protected $t_u_desktop = "wf_u_desktop";
    protected $t_set_autoFenfa = "wf_s_step_autofenfa";
    protected $t_set_taohong = "wf_s_taohong";
    protected $t_use_deal_way = "wf_s_deal_way";
    protected $main_user = "main_user";
    protected $ware_house = "wf_ware_house";
    protected $ware_fields = "wf_ware_fields";
    protected $t_use_dispose_type = "wf_u_dispose_type";
    protected $rows = 15;
    protected $f_level = array
    (
        0 => "普通",
        1 => "重要",
        2 => "非常重要"
    );
    protected $f_level_turn = array
    (
        "普通" => 0,
        "重要" => 1,
        "非常重要" => 2
    );
    protected $f_status = array
    (
        0 => "未发布",
        1 => "办理中",
        2 => "已办理",
        3 => "已退件",
        4 => "已撤销",
        5 => "已删除",
        6 => "已中止"
    );
    protected $f_status_turn = array
    (
        "未发布" => 0,
        "办理中" => 1,
        "已办理" => 2,
        "已退件" => 3,
        "已撤销" => 4,
        "已删除" => 5,
        "已中止" => 6
    );
    protected $f_stepType = array
    (
        0 => "未开始",
        1 => "办理中",
        2 => "已办理",
        3 => "已中止",
        4 => "保留意见",
        5 => "已撤销"
    );
    protected $f_eventType = array
    (
        0 => "未发布",
        1 => "开始",
        2 => "办理",
        3 => "撤销",
        4 => "退回",
        5 => "拒绝",
        6 => "结束",
        7 => "召回",
        8 => "委托",
        9 => "保留意见",
        10 => "中止",
        11 => "会签",
        12 => "子流程",
        13 => "更新数据"
    );
    protected $f_isread = array
    (
        0 => "未阅",
        1 => "已阅"
    );
    protected $f_font = array
    (
        1 => "宋体",
        2 => "楷体",
        3 => "隶书",
        4 => "黑体",
        5 => "andale mono",
        6 => "arial",
        7 => "arial black",
        8 => "comic sons ms",
        9 => "impact",
        10 => "times new roman",
        11 => "微软雅黑",
        12 => "仿宋"
    );
    protected $f_font_format = array
    (
        "bold" => "<B>加粗</B>",
        "italic" => "<I>斜体</I>",
        "underline" => "<U>下划线</U>"
    );
    protected $f_btn_text = array( );
    protected $autoFormat = array
    (
        "str" => "",
        "int" => array
        (
            1 => array
            (
                0 => "#",
                1 => "123"
            ),
            2 => array
            (
                0 => "#,###",
                1 => "12,345"
            )
        ),
        "float" => array
        (
            1 => array
            (
                0 => "#.#",
                1 => "1.2"
            ),
            2 => array
            (
                0 => "#.##",
                1 => "1.23"
            ),
            3 => array
            (
                0 => "#.###",
                1 => "1.234"
            ),
            4 => array
            (
                0 => "#,###.#",
                1 => "1,234.5"
            ),
            5 => array
            (
                0 => "#,###.##",
                1 => "1,234.56"
            ),
            6 => array
            (
                0 => "#,###.###",
                1 => "1,234.567"
            ),
            7 => array
            (
                0 => "#",
                1 => "123"
            ),
            8 => array
            (
                0 => "#,###",
                1 => "12,345"
            ),
            9 => array
            (
                0 => "#.0",
                1 => "1.0"
            ),
            10 => array
            (
                0 => "#.00",
                1 => "1.20"
            ),
            11 => array
            (
                0 => "#.000",
                1 => "1.230"
            ),
            12 => array
            (
                0 => "#,###.0",
                1 => "1,234.0"
            ),
            13 => array
            (
                0 => "#,###.00",
                1 => "1,234.50"
            ),
            14 => array
            (
                0 => "#,###.000",
                1 => "1,234.560"
            ),
            15 => array
            (
                0 => "#.####",
                1 => "1.2345"
            )
        ),
        "date" => array
        (
            1 => array
            (
                0 => "yyyy-mm-dd",
                1 => "2012-10-31"
            ),
            2 => array
            (
                0 => "mm-dd yyyy",
                1 => "10-31 2012"
            ),
            3 => array
            (
                0 => "yy-mm-dd",
                1 => "12-10-31"
            ),
            4 => array
            (
                0 => "mm-dd yy",
                1 => "10-31 12"
            ),
            5 => array
            (
                0 => "yyyy-mm",
                1 => "2012-10"
            ),
            6 => array
            (
                0 => "mm-dd",
                1 => "10-31"
            ),
            7 => array
            (
                0 => "yy-mm",
                1 => "12-10"
            ),
            8 => array
            (
                0 => "yyyy年mm月dd日",
                1 => "2012年10月31日"
            ),
            9 => array
            (
                0 => "yyyy年mm月",
                1 => "2012年10月"
            ),
            10 => array
            (
                0 => "mm月dd日",
                1 => "10月31日"
            )
        ),
        "time" => array
        (
            1 => array
            (
                0 => "HH:MM(24小时制)",
                1 => "16:01"
            ),
            2 => array
            (
                0 => "HH:MM AM(12小时制)",
                1 => "10:21 AM"
            ),
            3 => array
            (
                0 => "HH:MM 上午(12小时制)",
                1 => "10:21 上午"
            )
        )
    );
    protected $autoFormatPHP = array
    (
        "int" => array
        (
            1 => "#",
            2 => "#,###"
        ),
        "float" => array
        (
            1 => "#.#",
            2 => "#.##",
            3 => "#.###",
            4 => "#,###.#",
            5 => "#,###.##",
            6 => "#,###.###",
            7 => "#",
            8 => "#,###",
            9 => "#.0",
            10 => "#.00",
            11 => "#.000",
            12 => "#,###.0",
            13 => "#,###.00",
            14 => "#,###.000",
            15 => "#.####"
        ),
        "date" => array
        (
            1 => "Y-m-d",
            2 => "m-d Y",
            3 => "y-m-d",
            4 => "m-d y",
            5 => "Y-m",
            6 => "m-d",
            7 => "y-m",
            8 => "Y年m月d日",
            9 => "Y年m月",
            10 => "m月d日"
        ),
        "time" => array
        (
            1 => "H:i",
            2 => "h:i A",
            3 => "h:i A"
        )
    );
    protected $bindFunctionList = array
    (
        "null" => array
        (
            "name" => "　",
            "code" => "null"
        ),
        "admarticlesa" => array
        (
            "name" => "办公用品：采购申请明细表",
            "class" => "engAdmArticlesa"
        ),
        "admarticlesb" => array
        (
            "name" => "办公用品：入库申请明细表",
            "class" => "engAdmArticlesb"
        ),
        "admarticlesc" => array
        (
            "name" => "办公用品：用品领用明细表",
            "class" => "engAdmArticlesc"
        ),
        "admarticlesd" => array
        (
            "name" => "办公用品：用品借用明细表",
            "class" => "engAdmArticlesd"
        ),
        "admarticlese" => array
        (
            "name" => "办公用品：申请归还明细表",
            "class" => "engAdmArticlese"
        ),
        "admarticlesf" => array
        (
            "name" => "办公用品：申请维护明细表",
            "class" => "engAdmArticlesf"
        ),
        "jxcRuku" => array
        (
            "name" => "进销存：入库申请物品明细表"
        ),
        "jxcChuku" => array
        (
            "name" => "进销存：出库申请物品明细表"
        ),
        "sqldetail" => array
        (
            "name" => "自定义：SQL数据明细"
        )
    );
    protected $noticeFormat = array
    (
        "todo" => array
        (
            0 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&from=dealflow",
                "href2" => "index.php?app=wf&func=flow&action=use&modul=done&task=loadPage&from=showflow",
                "from" => 31,
                "funname" => "工作流程",
                "move" => "审批",
                "title" => "您有一条工作流程需要审批",
                "table" => "wf_u_step",
                "fromtype" => "check"
            )
        ),
        "done" => array
        (
            0 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=done&task=loadPage&from=showflow",
                "from" => 31,
                "funname" => "工作流程",
                "move" => "审批结束",
                "title" => "您的一条工作流程已经结束",
                "table" => "wf_u_step",
                "fromtype" => "done"
            )
        ),
        "trust" => array
        (
            0 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&from=dealflow",
                "from" => 31,
                "funname" => "工作流程",
                "move" => "委托审批",
                "title" => "您有一条工作流程委托给你审批",
                "table" => "wf_u_step",
                "fromtype" => "trust"
            )
        ),
        "read" => array
        (
            0 => array
            (
                "href" => "",
                "from" => "",
                "funname" => "工作流程",
                "move" => "审批"
            )
        ),
        "huiqian" => array
        (
            0 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&type=huiqian&from=showflow",
                "from" => 31,
                "funname" => "工作流程会签",
                "move" => "流程会签",
                "title" => "您有一条工作流程需要会签",
                "table" => "wf_u_huiqian",
                "fromtype" => "write"
            ),
            1 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&from=dealflow",
                "from" => 31,
                "funname" => "工作流程会签",
                "move" => "流程会签",
                "title" => "您有一条工作流程会签意见",
                "table" => "wf_u_step",
                "fromtype" => "notice"
            )
        ),
        "callback" => array
        (
            0 => array
            (
                "href" => "",
                "from" => 31,
                "funname" => "工作流程召回",
                "move" => "流程召回",
                "title" => "您有一条工作流程被召回",
                "table" => "wf_u_step",
                "fromtype" => "callback"
            )
        ),
        "tuihui" => array
        (
            0 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&from=dealflow",
                "from" => 31,
                "funname" => "工作流程",
                "move" => "退回",
                "title" => "您有一条工作流程被退回",
                "table" => "wf_u_step",
                "fromtype" => "backToPeop"
            ),
            1 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=done&task=loadPage&from=showflow",
                "from" => 31,
                "funname" => "工作流程",
                "move" => "退回",
                "title" => "您有一条工作流程被退回",
                "table" => "wf_u_step",
                "fromtype" => "backToHand"
            ),
            2 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=done&task=loadPage&from=showflow",
                "from" => 31,
                "funname" => "工作流程",
                "move" => "退回",
                "title" => "您有一条工作流程被退回",
                "table" => "wf_u_step",
                "fromtype" => "backToFaqi"
            )
        ),
        "stop" => array
        (
            0 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=view&task=loadPage&from=viewflow",
                "from" => 31,
                "funname" => "工作流程",
                "move" => "流程中止",
                "title" => "您有一条工作流程被中止",
                "table" => "wf_u_step",
                "fromtype" => "stopToFaqi"
            ),
            1 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=view&task=loadPage&from=viewflow",
                "from" => 31,
                "funname" => "工作流程",
                "move" => "流程中止",
                "title" => "您有一条工作流程被中止",
                "table" => "wf_u_step",
                "fromtype" => "stopToPeop"
            ),
            2 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=view&task=loadPage&from=viewflow",
                "from" => 31,
                "funname" => "工作流程",
                "move" => "流程中止",
                "title" => "您有一条工作流程被中止",
                "table" => "wf_u_step",
                "fromtype" => "stopToHand"
            )
        ),
        "warn" => array
        (
            0 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&from=dealflow",
                "from" => 31,
                "funname" => "工作流程督办",
                "move" => "流程督办",
                "title" => "工作流程督办",
                "table" => "wf_s_flow",
                "fromtype" => "warn"
            )
        ),
        "fenfa" => array
        (
            0 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=view&task=loadPage&from=viewflow",
                "from" => 31,
                "funname" => "工作流程分发",
                "move" => "流程分发",
                "title" => "工作流程分发",
                "table" => "wf_u_fenfa",
                "fromtype" => "fenfa"
            )
        ),
        "comment" => array
        (
            0 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=view&task=loadPage&from=viewflow",
                "from" => 31,
                "funname" => "工作流程评阅",
                "move" => "流程评阅",
                "title" => "工作流程评阅",
                "table" => "t_use_fenfa",
                "fromtype" => "comment"
            )
        ),
        "cancel" => array
        (
            0 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=done&task=loadPage&from=showflow",
                "from" => 31,
                "funname" => "工作流程撤销",
                "move" => "流程撤销",
                "title" => "工作流程撤销",
                "table" => "wf_s_flow",
                "fromtype" => "cancel"
            )
        ),
        "delete" => array
        (
            0 => array
            (
                "href" => "",
                "from" => 31,
                "funname" => "工作流程删除",
                "move" => "流程删除",
                "title" => "工作流程删除",
                "table" => "wf_s_flow",
                "fromtype" => "delete"
            )
        ),
        "child" => array
        (
            0 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=new&task=loadPage&from=newflow",
                "from" => 31,
                "funname" => "工作流程子流程布置",
                "move" => "子流程布置",
                "title" => "子流程布置",
                "table" => "wf_u_step_child_flow",
                "fromtype" => "buzhi"
            ),
            1 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=todo",
                "from" => 31,
                "funname" => "工作流程子流程布置",
                "move" => "子流程结束",
                "title" => "子流程结束",
                "table" => "wf_u_step_child_flow",
                "fromtype" => "buzhi"
            ),
            2 => array
            (
                "href" => "",
                "from" => 31,
                "funname" => "工作流程子流程已发起",
                "move" => "子流程发起",
                "title" => "子流程发起成功",
                "table" => "wf_u_step_child_flow",
                "fromtype" => "buzhi"
            )
        )
    );

    const STEP_TYPE_START = 1;
    const STEP_TYPE_GENERAL = 2;
    const STEP_TYPE_END = 3;
    const STEP_TYPE_BINGFA = 4;
    const STEP_TYPE_CONVERGENCE = 5;
    const STEP_TYPE_LOWER = 6;
    const STEP_TYPE_CHILD = 7;
    const STEP_STATUS_TODO = 1;
    const STEP_STATUS_DONE = 2;
    const STEP_STATUS_BACKOFF = 3;
    const STEP_STATUS_RESERVATION = 4;
    const STEP_STATUS_BACKOUT = 5;
    const FLOW_STATUS_TODO = 1;
    const FLOW_STATUS_DONE = 2;
    const FLOW_STATUS_RETURN = 3;
    const FLOW_STATUS_BACKOUT = 4;
    const FLOW_STATUS_DELETE = 5;
    const FLOW_STATUS_BACKOFF = 6;
    const EVENT_START = 1;
    const EVENT_DEAL = 2;
    const EVENT_BACKOUT = 3;
    const EVENT_RETURN = 4;
    const EVENT_REJECT = 5;
    const EVENT_END = 6;
    const EVENT_RECALL = 7;
    const EVENT_TRUST = 8;
    const EVENT_RESERVATION = 9;
    const EVENT_BACKOFF = 10;
    const FIELD_TEXTFIELD = "textfield";
    const FIELD_TEXTAREA = "textarea";
    const FIELD_SELECT = "select";
    const FIELD_CALCULATE = "calculate";
    const FIELD_RADIO = "radio";
    const FIELD_CHECKBOX = "checkbox";
    const FIELD_MACRO = "macro";
    const FIELD_DETAIL_TABLE = "detailtable";
    const FIELD_CHOICE = "choice";
    const FIELD_SIGNATURE = "signature";
    const FIELD_DATASOURCE = "datasource";
    const TABLE_PRE_DATA = "z_wf_t_";
    const TABLE_PRE_DETAIL = "z_wf_d_";
    const FIELD_RULE_NORMAL = 0;
    const FIELD_RULE_DETAIL = 1;

    public function __construct( )
    {
        $this->f_btn_text = array(
            1 => lang( "agree" ),
            2 => lang( "approval" ),
            3 => lang( "approval2" ),
            4 => lang( "confirm" ),
            5 => lang( "opt" ),
            6 => lang( "archive" ),
            7 => lang( "readed" )
        );
    }

    protected function api_getSortByIds( $_obfuscate_O6QLLac� )
    {
        global $CNOA_DB;
        if ( !is_array( $_obfuscate_O6QLLac� ) && count( $_obfuscate_O6QLLac� ) <= 0 )
        {
            return array( );
        }
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_sort, "WHERE `sortId` IN (".implode( ",", $_obfuscate_O6QLLac� ).") " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_6RYLWQ�� = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_6RYLWQ��[$_obfuscate_6A��['sortId']] = $_obfuscate_6A��;
        }
        return $_obfuscate_6RYLWQ��;
    }

    protected function api_getFlowByIds( $_obfuscate_O6QLLac� )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_flow, "WHERE `flowId` IN (".implode( ",", $_obfuscate_O6QLLac� ).") " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_6RYLWQ�� = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_6RYLWQ��[$_obfuscate_6A��['flowId']] = $_obfuscate_6A��;
        }
        return $_obfuscate_6RYLWQ��;
    }

    protected function api_createTable( $_obfuscate_0W8�, $_obfuscate_7qDAYo85aGA�, $_obfuscate_3tiDsnM� = "cnoa_z_wf_t_" )
    {
        global $CNOA_DB;
        $_obfuscate_3tiDsnM� .= $_obfuscate_0W8�;
        $_obfuscate_3gn_eQ�� = addslashes( $_obfuscate_7qDAYo85aGA�['name'] );
        $_obfuscate_3y0Y = "CREATE TABLE IF NOT EXISTS `".$_obfuscate_3tiDsnM�.( "` (\n\t\t\t\t`fid` INT( 10 ) NOT NULL AUTO_INCREMENT ,\n\t\t\t\t`uFlowId` INT( 10 ) NOT NULL,\n\t\t\t\t`flowNumber` char(100) NOT NULL COMMENT '流程编号',\n\t\t\t\t`flowName` varchar(200) NOT NULL COMMENT '流程名称',\n\t\t\t\t`uid` int(10) NOT NULL COMMENT '发布人',\n\t\t\t\t`status` int(1) NOT NULL COMMENT '流程状态',\n\t\t\t\t`level` int(1) NOT NULL COMMENT '级别: 0普通 1重要 2非常重要',\n\t\t\t\t`reason` mediumtext NOT NULL COMMENT '发起理由',\n\t\t\t\t`posttime` int(10) NOT NULL COMMENT '发布时间',\n\t\t\t\t`endtime` int(10) NOT NULL COMMENT '结束时间',\n\t\t\t\tPRIMARY KEY (`fid`)\n\t\t\t\t) ENGINE = MYISAM COMMENT =  '工作流数据表 - ".$_obfuscate_3gn_eQ��."';" );
        if ( $CNOA_DB->query( $_obfuscate_3y0Y ) )
        {
            $this->_createSqlField( $_obfuscate_0W8�, $_obfuscate_3tiDsnM� );
            return TRUE;
        }
        return FALSE;
    }

    private function _createSqlField( $_obfuscate_F4AbnVRh, $_obfuscate_3tiDsnM� )
    {
        global $CNOA_DB;
        $_obfuscate_ammigv8� = $CNOA_DB->query( "SHOW COLUMNS FROM  `".$_obfuscate_3tiDsnM�."` WHERE  `Field` REGEXP  '^T_'" );
        $_obfuscate_mLjk2t6lphU� = $_obfuscate_5V41wAcLtSe5 = array( );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_ammigv8� ) )
        {
            $_obfuscate_mLjk2t6lphU�[] = $_obfuscate_gkt['Field'];
            $_obfuscate_5V41wAcLtSe5[$_obfuscate_gkt['Field']] = $_obfuscate_gkt['Type'];
        }
        unset( $_obfuscate_ammigv8� );
        unset( $_obfuscate_gkt );
        $_obfuscate_Pm3ZMWpPkg�� = $CNOA_DB->db_select( "*", $this->t_set_field, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' " );
        if ( !is_array( $_obfuscate_Pm3ZMWpPkg�� ) )
        {
            $_obfuscate_Pm3ZMWpPkg�� = array( );
        }
        $_obfuscate_gTTQ1okbnc5vtx4� = $_obfuscate_a1TTPdof = array( );
        foreach ( $_obfuscate_Pm3ZMWpPkg�� as $_obfuscate_YIq2A8c� )
        {
            if ( $_obfuscate_YIq2A8c�['otype'] == "detailtable" )
            {
                $_obfuscate_gTTQ1okbnc5vtx4�[] = $_obfuscate_YIq2A8c�;
            }
            $_obfuscate_p5ZWxr4� = json_decode( str_replace( "'", "\"", $_obfuscate_YIq2A8c�['odata'] ), TRUE );
            $_obfuscate_8UKWnDlantDd = "T_".$_obfuscate_YIq2A8c�['id'];
            $_obfuscate_ZbugDtiHBg�� = addslashes( $_obfuscate_YIq2A8c�['name'] );
            if ( in_array( $_obfuscate_8UKWnDlantDd, $_obfuscate_mLjk2t6lphU� ) )
            {
                if ( !( $_obfuscate_p5ZWxr4�['dataType'] != "int" ) && !( $_obfuscate_5V41wAcLtSe5[$_obfuscate_8UKWnDlantDd] != "text" ) )
                {
                    $_obfuscate_a1TTPdof[] = "CHANGE  `".$_obfuscate_8UKWnDlantDd."`  `{$_obfuscate_8UKWnDlantDd}` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '{$_obfuscate_ZbugDtiHBg��}'";
                }
            }
            else if ( $_obfuscate_p5ZWxr4�['dataType'] == "int" )
            {
                $_obfuscate_a1TTPdof[] = "ADD `".$_obfuscate_8UKWnDlantDd."` VARCHAR( 50 ) NOT NULL COMMENT  '{$_obfuscate_ZbugDtiHBg��}'";
            }
            else
            {
                $_obfuscate_a1TTPdof[] = "ADD  `".$_obfuscate_8UKWnDlantDd."` TEXT NOT NULL COMMENT  '{$_obfuscate_ZbugDtiHBg��}'";
            }
        }
        unset( $_obfuscate_Pm3ZMWpPkg�� );
        unset( $_obfuscate_mLjk2t6lphU� );
        unset( $_obfuscate_5V41wAcLtSe5 );
        unset( $_obfuscate_j0UfixE� );
        if ( !empty( $_obfuscate_a1TTPdof ) )
        {
            $this->_alterTabel( $_obfuscate_a1TTPdof, $_obfuscate_3tiDsnM� );
        }
        foreach ( $_obfuscate_gTTQ1okbnc5vtx4� as $_obfuscate_YIq2A8c� )
        {
            $this->_createDetailTable( $_obfuscate_F4AbnVRh, $_obfuscate_YIq2A8c� );
        }
    }

    private function _createDetailTable( $_obfuscate_F4AbnVRh, $_obfuscate_6RYLWQ�� )
    {
        global $CNOA_DB;
        $_obfuscate_3tiDsnM� = "cnoa_z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_6RYLWQ��['id'];
        $_obfuscate_3y0Y = "CREATE TABLE IF NOT EXISTS `".$_obfuscate_3tiDsnM�."` (\n\t\t\t\t`fid` INT( 10 ) NOT NULL AUTO_INCREMENT ,\n\t\t\t\t`uFlowId` INT( 10 ) NOT NULL,\n\t\t\t\t`rowid` INT( 10 ) NOT NULL,\n\t\t\t\t`bindid` INT( 10 ) NOT NULL,\n\t\t\t\tPRIMARY KEY (  `fid` )\n\t\t\t\t) ENGINE=MYISAM COMMENT='工作流明细表-';";
        $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_ammigv8� = $CNOA_DB->query( "SHOW COLUMNS FROM  `".$_obfuscate_3tiDsnM�."` WHERE  `Field` REGEXP  '^D_'" );
        $_obfuscate_mLjk2t6lphU� = $_obfuscate_5V41wAcLtSe5 = array( );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_ammigv8� ) )
        {
            $_obfuscate_mLjk2t6lphU�[] = $_obfuscate_gkt['Field'];
            $_obfuscate_5V41wAcLtSe5[$_obfuscate_gkt['Field']] = $_obfuscate_gkt['Type'];
        }
        unset( $_obfuscate_ammigv8� );
        unset( $_obfuscate_gkt );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "type", "dataType", "id" ), $this->t_set_field_detail, "WHERE `fid` = '".$_obfuscate_6RYLWQ��['id']."' ORDER BY `id` DESC " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_a1TTPdof = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_YIq2A8c� )
        {
            $_obfuscate_8UKWnDlantDd = "D_".$_obfuscate_YIq2A8c�['id'];
            $_obfuscate_ZbugDtiHBg�� = addslashes( $_obfuscate_YIq2A8c�['name'] );
            if ( in_array( $_obfuscate_8UKWnDlantDd, $_obfuscate_mLjk2t6lphU� ) )
            {
                if ( !( $_obfuscate_YIq2A8c�['dataType'] != "int" ) && !( $_obfuscate_5V41wAcLtSe5[$_obfuscate_8UKWnDlantDd] != "text" ) )
                {
                    $_obfuscate_a1TTPdof[] = "CHANGE  `".$_obfuscate_8UKWnDlantDd."`  `{$_obfuscate_8UKWnDlantDd}` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '{$_obfuscate_ZbugDtiHBg��}'";
                }
            }
            else if ( $_obfuscate_YIq2A8c�['dataType'] == "int" )
            {
                $_obfuscate_a1TTPdof[] = "ADD `".$_obfuscate_8UKWnDlantDd."` VARCHAR( 50 ) NOT NULL COMMENT  '{$_obfuscate_ZbugDtiHBg��}'";
            }
            else
            {
                $_obfuscate_a1TTPdof[] = "ADD  `".$_obfuscate_8UKWnDlantDd."` TEXT NOT NULL COMMENT  '{$_obfuscate_ZbugDtiHBg��}'";
            }
        }
        unset( $_obfuscate_mPAjEGLn );
        unset( $_obfuscate_mLjk2t6lphU� );
        unset( $_obfuscate_5V41wAcLtSe5 );
        unset( $_obfuscate_YIq2A8c� );
        if ( !empty( $_obfuscate_a1TTPdof ) )
        {
            $this->_alterTabel( $_obfuscate_a1TTPdof, $_obfuscate_3tiDsnM� );
        }
    }

    private function _alterTabel( $_obfuscate_tjILu7ZH, $_obfuscate_3tiDsnM� )
    {
        global $CNOA_DB;
        $_obfuscate_tjILu7ZH = array_chunk( $_obfuscate_tjILu7ZH, 100 );
        foreach ( $_obfuscate_tjILu7ZH as $_obfuscate_6A�� )
        {
            $_obfuscate_6A�� = implode( ",", $_obfuscate_6A�� );
            $_obfuscate_3y0Y = "ALTER TABLE ".$_obfuscate_3tiDsnM�." {$_obfuscate_6A��}";
            $CNOA_DB->query( $_obfuscate_3y0Y );
        }
    }

    protected function api_takeTrustData( $_obfuscate_49fO, $_obfuscate_LeS8hw�� = "all" )
    {
        global $CNOA_DB;
        $_obfuscate_Bk2lGlk� = "WHERE 1 ";
        if ( $_obfuscate_LeS8hw�� == "sort" )
        {
            $_obfuscate_Bk2lGlk� .= "AND `type` = 'sort' ";
        }
        else if ( $_obfuscate_LeS8hw�� == "flow" )
        {
            $_obfuscate_Bk2lGlk� .= "AND `type` = 'flow' ";
        }
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_trust_flow, $_obfuscate_Bk2lGlk�.( "AND `tid` = '".$_obfuscate_49fO."' " ) );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        return $_obfuscate_mPAjEGLn;
    }

    protected function api_loadFlowInfo( $_obfuscate_TlvKhtsoOQ�� )
    {
        global $CNOA_DB;
        $_obfuscate_7qDAYo85aGA� = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        if ( $_obfuscate_7qDAYo85aGA� !== FALSE )
        {
            $_obfuscate_7qDAYo85aGA�['uname'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_7qDAYo85aGA�['uid'] );
        }
        $_obfuscate_7qDAYo85aGA�['posttime'] = date( "Y年m月d日 H时i分", $_obfuscate_7qDAYo85aGA�['posttime'] );
        $_obfuscate_7qDAYo85aGA�['level'] = $this->f_level[$_obfuscate_7qDAYo85aGA�['level']];
        $_obfuscate_7qDAYo85aGA�['status'] = $this->f_status[$_obfuscate_7qDAYo85aGA�['status']];
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_6RYLWQ��['flowInfo'] = $_obfuscate_7qDAYo85aGA�;
        return $_obfuscate_6RYLWQ��;
    }

    protected function getCmMapping( $_obfuscate_Ce9h, $_obfuscate_TervNcSylPE� = TRUE )
    {
        global $CNOA_DB;
        return "T_".$_obfuscate_Ce9h;
    }

    protected function api_deleteFields( $_obfuscate_mLjk2t6lphU� )
    {
        global $CNOA_DB;
        foreach ( $_obfuscate_mLjk2t6lphU� as $_obfuscate_VgKtFeg� )
        {
            $_obfuscate_2s5w882kTg�� = CNOA_PATH_FILE.( "/common/wf/sqlselector/".$_obfuscate_VgKtFeg�.".php" );
            if ( file_exists( $_obfuscate_2s5w882kTg�� ) )
            {
                @unlink( $_obfuscate_2s5w882kTg�� );
            }
        }
        $CNOA_DB->db_delete( $this->t_set_field, "WHERE `id` IN (".implode( ",", $_obfuscate_mLjk2t6lphU� ).") " );
        $CNOA_DB->db_delete( $this->t_set_field_detail, "WHERE `fid` IN (".implode( ",", $_obfuscate_mLjk2t6lphU� ).") " );
        $CNOA_DB->db_delete( $this->t_set_step_fields, "WHERE `fieldId` IN (".implode( ",", $_obfuscate_mLjk2t6lphU� ).") " );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_step_condition, "WHERE `name` IN (".implode( ",", $_obfuscate_mLjk2t6lphU� ).") " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        foreach ( $_obfuscate_mLjk2t6lphU� as $_obfuscate_6A�� )
        {
            if ( !empty( $_obfuscate_6A�� ) )
            {
                $CNOA_DB->db_delete( $this->t_set_step_condition, "WHERE `pid` = '".$_obfuscate_6A��."' OR `name` = '{$_obfuscate_6A��}' " );
            }
        }
        unset( $_obfuscate_mLjk2t6lphU�[array_search( 0, $_obfuscate_mLjk2t6lphU� )] );
        if ( count( $_obfuscate_mLjk2t6lphU� ) )
        {
            $CNOA_DB->db_delete( $this->t_set_step_user, "WHERE `kong` IN (".implode( ",", $_obfuscate_mLjk2t6lphU� ).")" );
        }
    }

    protected function api_getFieldInfoById( )
    {
    }

    protected function api_getFieldInfoByName( $_obfuscate_3gn_eQ��, $_obfuscate_F4AbnVRh, $_obfuscate_8XjS1n72 = FALSE )
    {
        global $CNOA_DB;
        if ( $_obfuscate_8XjS1n72 )
        {
            return $CNOA_DB->db_getone( "*", $this->t_set_field_detail, "WHERE `name`='".$_obfuscate_3gn_eQ��."' AND `fid`='{$_obfuscate_F4AbnVRh}'" );
        }
        return $CNOA_DB->db_getone( "*", $this->t_set_field, "WHERE `name`='".$_obfuscate_3gn_eQ��."' AND `flowId`='{$_obfuscate_F4AbnVRh}'" );
    }

    protected function api_splitGongShi( $_obfuscate_sSwuE42EWQ�� )
    {
        $_obfuscate_sSwuE42EWQ�� = str_replace( array( "－", "×" ), array( "-", "*" ), $_obfuscate_sSwuE42EWQ�� );
        $_obfuscate_sSwuE42EWQ�� = str_split( $_obfuscate_sSwuE42EWQ�� );
        $_obfuscate_jh2JTltz90ht5hY� = array( );
        $_obfuscate_bVo7VnFDpps� = "";
        foreach ( $_obfuscate_sSwuE42EWQ�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( in_array( $_obfuscate_6A��, array( "+", "-", "*", "/", "(", ")" ) ) )
            {
                if ( !empty( $_obfuscate_bVo7VnFDpps� ) )
                {
                    $_obfuscate_jh2JTltz90ht5hY�[] = $_obfuscate_bVo7VnFDpps�;
                }
                $_obfuscate_jh2JTltz90ht5hY�[] = $_obfuscate_6A��;
                $_obfuscate_NAs� = "r";
                $_obfuscate_bVo7VnFDpps� = "";
            }
            else
            {
                $_obfuscate_bVo7VnFDpps� .= $_obfuscate_6A��;
            }
        }
        if ( !empty( $_obfuscate_bVo7VnFDpps� ) )
        {
            $_obfuscate_jh2JTltz90ht5hY�[] = $_obfuscate_bVo7VnFDpps�;
        }
        return $_obfuscate_jh2JTltz90ht5hY�;
    }

    protected function api_saveFieldData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_JQJwE4USnB0�, $_obfuscate_dGoPOiQ2Iw5a = array( ), $_obfuscate_BqBV6WSz3wel0ZDw = array( ), $_obfuscate_vholQ�� = "", $_obfuscate_qZkmBg�� = "" )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_3tiDsnM� = "z_wf_t_".$_obfuscate_F4AbnVRh;
        $_obfuscate_6RYLWQ�� = array(
            "uFlowId" => $_obfuscate_TlvKhtsoOQ��
        );
        $_obfuscate_1JE3WRA� = $CNOA_DB->db_getone( "*", $_obfuscate_3tiDsnM�, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        if ( is_array( $_obfuscate_JQJwE4USnB0�['normal'] ) )
        {
            foreach ( $_obfuscate_JQJwE4USnB0�['normal'] as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_6RYLWQ��["T_".$_obfuscate_5w��] = addslashes( $_obfuscate_6A�� );
            }
        }
        if ( $_obfuscate_vholQ�� == "new" )
        {
            $_obfuscate_6RYLWQ��['flowNumber'] = $_obfuscate_qZkmBg��['flowNumber'];
            $_obfuscate_6RYLWQ��['flowName'] = $_obfuscate_qZkmBg��['flowName'];
            $_obfuscate_6RYLWQ��['uid'] = $_obfuscate_qZkmBg��['uid'];
            $_obfuscate_6RYLWQ��['level'] = $_obfuscate_qZkmBg��['level'];
            $_obfuscate_6RYLWQ��['reason'] = $_obfuscate_qZkmBg��['reason'];
            $_obfuscate_6RYLWQ��['posttime'] = $_obfuscate_qZkmBg��['posttime'];
            $_obfuscate_tjILu7ZH = $CNOA_DB->db_select( array( "odata", "id" ), $this->t_set_field, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND otype='macro'" );
            if ( !is_array( $_obfuscate_tjILu7ZH ) )
            {
                $_obfuscate_tjILu7ZH = array( );
            }
            foreach ( $_obfuscate_tjILu7ZH as $_obfuscate_6A�� )
            {
                $_obfuscate_p5ZWxr4� = json_decode( str_replace( "'", "\"", $_obfuscate_6A��['odata'] ), TRUE );
                if ( $_obfuscate_p5ZWxr4�['dataType'] == "flownum" )
                {
                    $_obfuscate_cO0ZkQ�� = $CNOA_DB->db_getcount( $this->t_set_step_fields, "WHERE `flowId`= ".$_obfuscate_F4AbnVRh." and `write`= 1 and `stepId` = 2 and `fieldId` = ".$_obfuscate_6A��['id'] );
                    if ( $_obfuscate_cO0ZkQ�� )
                    {
                        $_obfuscate_6RYLWQ��["T_".$_obfuscate_6A��['id']] = $_obfuscate_qZkmBg��['flowNumber'];
                    }
                }
                else if ( $_obfuscate_p5ZWxr4�['dataType'] == "flowname" )
                {
                    $_obfuscate_cO0ZkQ�� = $CNOA_DB->db_getcount( $this->t_set_step_fields, "WHERE `flowId`= ".$_obfuscate_F4AbnVRh." and `write`= 1 and stepId = 2 and fieldId = ".$_obfuscate_6A��['id'] );
                    if ( $_obfuscate_cO0ZkQ�� )
                    {
                        $_obfuscate_6RYLWQ��["T_".$_obfuscate_6A��['id']] = $_obfuscate_qZkmBg��['flowName'];
                    }
                }
            }
        }
        if ( !$_obfuscate_1JE3WRA� )
        {
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $_obfuscate_3tiDsnM� );
        }
        else
        {
            $CNOA_DB->db_update( $_obfuscate_6RYLWQ��, $_obfuscate_3tiDsnM�, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        }
        $_obfuscate_rBZ_wlJtMUNj = array( );
        unset( $_obfuscate_6RYLWQ�� );
        if ( !empty( $_obfuscate_JQJwE4USnB0�['detail'] ) )
        {
            foreach ( $_obfuscate_JQJwE4USnB0�['detail'] as $_obfuscate_gkt => $_obfuscate_eBU_Sjc� )
            {
                foreach ( $_obfuscate_eBU_Sjc� as $_obfuscate_5w�� => $_obfuscate_6A�� )
                {
                    $_obfuscate_rBZ_wlJtMUNj[$_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_5w��]][$_obfuscate_gkt][$_obfuscate_5w��] = $_obfuscate_6A��;
                }
            }
            foreach ( $_obfuscate_rBZ_wlJtMUNj as $_obfuscate_V0WIw2BKQg�� => $_obfuscate_8XjS1n72 )
            {
                $_obfuscate_3tiDsnM� = "z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_V0WIw2BKQg��;
                $_obfuscate_Gfham6St = array( );
                foreach ( $_obfuscate_8XjS1n72 as $_obfuscate_5w�� => $_obfuscate_6A�� )
                {
                    $_obfuscate_Gfham6St[] = $_obfuscate_5w��;
                }
                $CNOA_DB->db_delete( $_obfuscate_3tiDsnM�, "WHERE `rowid` NOT IN (".implode( ",", $_obfuscate_Gfham6St ).( ") AND `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " ) );
            }
            foreach ( $_obfuscate_rBZ_wlJtMUNj as $_obfuscate_V0WIw2BKQg�� => $_obfuscate_8XjS1n72 )
            {
                $_obfuscate_3tiDsnM� = "z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_V0WIw2BKQg��;
                ksort( &$_obfuscate_8XjS1n72 );
                foreach ( $_obfuscate_8XjS1n72 as $_obfuscate_gkt => $_obfuscate_s594VX4T0w�� )
                {
                    $_obfuscate_6RYLWQ�� = array(
                        "uFlowId" => $_obfuscate_TlvKhtsoOQ��
                    );
                    foreach ( $_obfuscate_s594VX4T0w�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
                    {
                        if ( is_array( $_obfuscate_6A�� ) )
                        {
                            $_obfuscate_6A�� = addslashes( json_encode( $_obfuscate_6A�� ) );
                        }
                        $_obfuscate_6RYLWQ��["D_".$_obfuscate_5w��] = addslashes( $_obfuscate_6A�� );
                    }
                    $_obfuscate_6RYLWQ��['rowid'] = $_obfuscate_gkt;
                    $_obfuscate_6RYLWQ��['bindid'] = $_obfuscate_BqBV6WSz3wel0ZDw[$_obfuscate_V0WIw2BKQg��][$_obfuscate_gkt];
                    $_obfuscate_8Q1yVKU� = $CNOA_DB->db_getone( "*", $_obfuscate_3tiDsnM�, "WHERE `rowid` = '".$_obfuscate_gkt."' AND `uFlowId` = '{$_obfuscate_TlvKhtsoOQ��}' " );
                    if ( empty( $_obfuscate_8Q1yVKU� ) )
                    {
                        $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $_obfuscate_3tiDsnM� );
                    }
                    else
                    {
                        $CNOA_DB->db_update( $_obfuscate_6RYLWQ��, $_obfuscate_3tiDsnM�, "WHERE `rowid` = '".$_obfuscate_gkt."' AND `uFlowId` = '{$_obfuscate_TlvKhtsoOQ��}' " );
                    }
                }
            }
        }
    }

    protected function api_getWfFieldData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_H9Mbnw�� = FALSE )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_1ahPX6OQ7w�� = $CNOA_DB->db_getone( "*", "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        if ( !is_array( $_obfuscate_1ahPX6OQ7w�� ) )
        {
            $_obfuscate_1ahPX6OQ7w�� = array( );
        }
        $_obfuscate_tjILu7ZH = $CNOA_DB->db_select( array( "id", "odata" ), "wf_s_field", " WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND `otype`='macro'" );
        if ( !is_array( $_obfuscate_tjILu7ZH ) )
        {
            $_obfuscate_tjILu7ZH = array( );
        }
        foreach ( $_obfuscate_tjILu7ZH as $_obfuscate_3QY� => $_obfuscate_EGU� )
        {
            $_obfuscate_p5ZWxr4� = json_decode( str_replace( "'", "\"", $_obfuscate_EGU�['odata'] ), TRUE );
            if ( $_obfuscate_p5ZWxr4�['dataType'] == "loginname" )
            {
                $_obfuscate_YIq2A8c�[] = $_obfuscate_EGU�['id'];
            }
        }
        if ( !is_array( $_obfuscate_YIq2A8c� ) )
        {
            $_obfuscate_YIq2A8c� = array( );
        }
        $_obfuscate_Jrp1 = array( );
        foreach ( $_obfuscate_1ahPX6OQ7w�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( ereg( "T_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_ppiyBfP31z3fvR8� = str_replace( "T_", "", $_obfuscate_5w�� );
                if ( in_array( $_obfuscate_ppiyBfP31z3fvR8�, $_obfuscate_YIq2A8c� ) )
                {
                    $_obfuscate_0W8� = $CNOA_DB->db_getfield( "uid", "main_user", " WHERE `truename`='".$_obfuscate_6A��."'" );
                    if ( !is_numeric( $_obfuscate_6A�� ) || !empty( $_obfuscate_0W8� ) )
                    {
                        $_obfuscate_6A�� = $_obfuscate_0W8�;
                    }
                    else
                    {
                        $_obfuscate_6A�� = $_obfuscate_6A��;
                    }
                }
                if ( !$_obfuscate_H9Mbnw�� )
                {
                    $_obfuscate_Jrp1[] = array(
                        "id" => $_obfuscate_ppiyBfP31z3fvR8�,
                        "data" => $_obfuscate_6A��
                    );
                }
                else
                {
                    $_obfuscate_Jrp1[$_obfuscate_ppiyBfP31z3fvR8�] = $_obfuscate_6A��;
                }
            }
        }
        return $_obfuscate_Jrp1;
    }

    protected function api_getWfFieldDetailData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_X7G10FfJmb, $_obfuscate_H9Mbnw�� = FALSE )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        if ( $_obfuscate_TlvKhtsoOQ�� == 0 )
        {
            return array( );
        }
        $this->resetDetailRowNumber( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_X7G10FfJmb );
        $_obfuscate_1ahPX6OQ7w�� = $CNOA_DB->db_select( "*", "z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_X7G10FfJmb, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        if ( !is_array( $_obfuscate_1ahPX6OQ7w�� ) )
        {
            $_obfuscate_1ahPX6OQ7w�� = array( );
        }
        $_obfuscate_Jrp1 = array( );
        foreach ( $_obfuscate_1ahPX6OQ7w�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            foreach ( $_obfuscate_6A�� as $_obfuscate_ClA� => $_obfuscate_bRQ� )
            {
                if ( ereg( "D_", $_obfuscate_ClA� ) )
                {
                    $_obfuscate_ppiyBfP31z3fvR8� = str_replace( "D_", "", $_obfuscate_ClA� );
                    if ( !$_obfuscate_H9Mbnw�� )
                    {
                        $_obfuscate_Jrp1[$_obfuscate_6A��['rowid']][] = array(
                            "id" => $_obfuscate_6A��['rowid']."_".$_obfuscate_ppiyBfP31z3fvR8�,
                            "data" => $_obfuscate_bRQ�
                        );
                    }
                    else
                    {
                        $_obfuscate_Jrp1[$_obfuscate_6A��['rowid']][$_obfuscate_ppiyBfP31z3fvR8�] = $_obfuscate_bRQ�;
                    }
                }
            }
            $_obfuscate_Jrp1[$_obfuscate_6A��['rowid']]['bindid'] = $_obfuscate_6A��['bindid'];
        }
        return $_obfuscate_Jrp1;
    }

    protected function resetDetailRowNumber( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_X7G10FfJmb )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "fid" ), "z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_X7G10FfJmb, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' ORDER BY `rowid` ASC " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_gkt = 1;
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $CNOA_DB->db_update( array(
                "rowid" => $_obfuscate_gkt
            ), "z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_X7G10FfJmb, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `fid` = '{$_obfuscate_6A��['fid']}' " );
            ++$_obfuscate_gkt;
        }
    }

    protected function api_formatFlowNumber( $_obfuscate_IMeby9iyHIg�, $_obfuscate_neM4JBUJlmg�, $_obfuscate_zPP1hxIu42teMw��, $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_NS44QYk� = $CNOA_SESSION->get( "TRUENAME" );
        $_obfuscate_KYPe3Fn6DvBxA�� = "";
        $_obfuscate_7qDAYo85aGA� = $CNOA_DB->db_getone( array( "nameRule", "nameRuleAllowEdit" ), $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $_obfuscate_anIYs0Ctkexb619U = $_obfuscate_7qDAYo85aGA�['nameRule'];
        if ( $_obfuscate_anIYs0Ctkexb619U != $_obfuscate_IMeby9iyHIg� && $_obfuscate_7qDAYo85aGA�['nameRuleAllowEdit'] == 0 )
        {
            $_obfuscate_IMeby9iyHIg� = $_obfuscate_anIYs0Ctkexb619U;
        }
        $_obfuscate_KYPe3Fn6DvBxA�� = str_replace( "{F}", $_obfuscate_neM4JBUJlmg�, $_obfuscate_IMeby9iyHIg� );
        $_obfuscate_KYPe3Fn6DvBxA�� = str_replace( "{U}", $_obfuscate_NS44QYk�, $_obfuscate_KYPe3Fn6DvBxA�� );
        $_obfuscate_KYPe3Fn6DvBxA�� = str_replace( "{Y}", date( "Y", $GLOBALS['CNOA_TIMESTAMP'] ), $_obfuscate_KYPe3Fn6DvBxA�� );
        $_obfuscate_KYPe3Fn6DvBxA�� = str_replace( "{M}", date( "m", $GLOBALS['CNOA_TIMESTAMP'] ), $_obfuscate_KYPe3Fn6DvBxA�� );
        $_obfuscate_KYPe3Fn6DvBxA�� = str_replace( "{D}", date( "d", $GLOBALS['CNOA_TIMESTAMP'] ), $_obfuscate_KYPe3Fn6DvBxA�� );
        $_obfuscate_KYPe3Fn6DvBxA�� = str_replace( "{H}", date( "H", $GLOBALS['CNOA_TIMESTAMP'] ), $_obfuscate_KYPe3Fn6DvBxA�� );
        $_obfuscate_KYPe3Fn6DvBxA�� = str_replace( "{I}", date( "i", $GLOBALS['CNOA_TIMESTAMP'] ), $_obfuscate_KYPe3Fn6DvBxA�� );
        $_obfuscate_KYPe3Fn6DvBxA�� = str_replace( "{S}", date( "s", $GLOBALS['CNOA_TIMESTAMP'] ), $_obfuscate_KYPe3Fn6DvBxA�� );
        $_obfuscate_odvySw�� = preg_replace( "/(.*)\\{([N]{1,})\\}(.*)/i", "\\2", $_obfuscate_KYPe3Fn6DvBxA�� );
        if ( strlen( $_obfuscate_odvySw�� ) < strlen( $_obfuscate_KYPe3Fn6DvBxA�� ) )
        {
            $_obfuscate_nguaag�� = str_pad( $_obfuscate_zPP1hxIu42teMw��, strlen( $_obfuscate_odvySw�� ), "0", STR_PAD_LEFT );
            $_obfuscate_KYPe3Fn6DvBxA�� = str_replace( "{".$_obfuscate_odvySw��."}", $_obfuscate_nguaag��, $_obfuscate_KYPe3Fn6DvBxA�� );
        }
        return $_obfuscate_KYPe3Fn6DvBxA��;
    }

    public function api_getBaseList( $_obfuscate_TlvKhtsoOQ�� )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        if ( $_obfuscate_mPAjEGLn !== FALSE )
        {
            $_obfuscate_mPAjEGLn['uname'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_mPAjEGLn['uid'] );
            $_obfuscate_mPAjEGLn['level'] = $this->f_level[$_obfuscate_mPAjEGLn['level']];
            $_obfuscate_mPAjEGLn['status'] = $this->f_status[$_obfuscate_mPAjEGLn['status']];
            $_obfuscate_mPAjEGLn['posttime'] = formatdate( $_obfuscate_mPAjEGLn['posttime'], "Y-m-d H:i" );
        }
        return $_obfuscate_mPAjEGLn;
    }

    protected function _checkChildFlow( $_obfuscate_TlvKhtsoOQ�� = 0, $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_jOcDpChC9w�� = $CNOA_DB->db_getone( "*", $this->t_use_step_child_flow, "WHERE `uFlowId` = ".$_obfuscate_TlvKhtsoOQ��." " );
        if ( !empty( $_obfuscate_jOcDpChC9w�� ) )
        {
            $_obfuscate_sx8� = $CNOA_DB->db_select( "*", $this->t_set_step_child_kongjian, "WHERE `arrow` = 'right' AND `bangdingFlow` = ".$_obfuscate_jOcDpChC9w��['flowId']." AND `stepId` = {$_obfuscate_jOcDpChC9w��['stepId']} " );
            if ( !empty( $_obfuscate_sx8� ) )
            {
                $_obfuscate_dXDzrHajXw�� = $CNOA_DB->db_getfield( "flowId", $this->t_use_flow, "WHERE `uFlowId` = ".$_obfuscate_jOcDpChC9w��['puFlowId']." " );
                $this->_updateFlowData( $_obfuscate_sx8�, $_obfuscate_jOcDpChC9w��['flowId'], $_obfuscate_dXDzrHajXw��, $_obfuscate_jOcDpChC9w��['puFlowId'], $_obfuscate_TlvKhtsoOQ�� );
            }
        }
    }

    protected function _updateFlowData( $_obfuscate_ixuxHoql0ImL, $_obfuscate_F4AbnVRh, $_obfuscate_dXDzrHajXw��, $_obfuscate_wzlwEupWLkw�, $_obfuscate_TlvKhtsoOQ�� )
    {
        global $CNOA_DB;
        foreach ( $_obfuscate_ixuxHoql0ImL as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['flowId'] == $_obfuscate_dXDzrHajXw�� )
            {
                if ( ereg( "T_", $_obfuscate_6A��['childKongjian'] ) )
                {
                    $_obfuscate_LI3AQT0XxA��[] = $_obfuscate_6A��['childKongjian'];
                }
                else if ( ereg( "D_", $_obfuscate_6A��['childKongjian'] ) )
                {
                    $_obfuscate_8jhldA9Y9A�� = str_replace( "D_", "", $_obfuscate_6A��['childKongjian'] );
                    $_obfuscate_Ce9h = $CNOA_DB->db_getfield( "fid", $this->t_set_field_detail, "WHERE `id` = ".$_obfuscate_8jhldA9Y9A��." " );
                    $_obfuscate_qWuC296rSw��[$_obfuscate_Ce9h][] = $_obfuscate_6A��['childKongjian'];
                    $_obfuscate_w2Zth0sNjFqUYc_5pS0�[$_obfuscate_6A��['childKongjian']] = $_obfuscate_Ce9h;
                }
            }
        }
        $_obfuscate_gZ2OAUO8XA�� = array( );
        $_obfuscate_8RkPDdDI4g�� = array( );
        if ( !empty( $_obfuscate_LI3AQT0XxA�� ) )
        {
            $_obfuscate_XKxKFeaAMUQ� = $CNOA_DB->db_getone( $_obfuscate_LI3AQT0XxA��, "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE `uFlowId` = ".$_obfuscate_TlvKhtsoOQ��." " );
        }
        if ( !empty( $_obfuscate_qWuC296rSw�� ) )
        {
            $_obfuscate_dGoPOiQ2Iw5a = array( );
            foreach ( $_obfuscate_qWuC296rSw�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_7Hp0w_lfFt4� = $CNOA_DB->db_getone( $_obfuscate_6A��, "z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_5w��, "WHERE `uFlowId` = ".$_obfuscate_TlvKhtsoOQ��." " );
                if ( !is_array( $_obfuscate_7Hp0w_lfFt4� ) )
                {
                    $_obfuscate_7Hp0w_lfFt4� = array( );
                }
                $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_5w��] = $_obfuscate_7Hp0w_lfFt4�;
            }
        }
        foreach ( $_obfuscate_ixuxHoql0ImL as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['flowId'] == $_obfuscate_dXDzrHajXw�� )
            {
                if ( ereg( "T_", $_obfuscate_6A��['parentKongjian'] ) )
                {
                    if ( ereg( "T_", $_obfuscate_6A��['childKongjian'] ) )
                    {
                        $_obfuscate_gZ2OAUO8XA��[$_obfuscate_6A��['parentKongjian']] = $_obfuscate_XKxKFeaAMUQ�[$_obfuscate_6A��['childKongjian']];
                    }
                    else if ( ereg( "D_", $_obfuscate_6A��['childKongjian'] ) )
                    {
                        $_obfuscate_gZ2OAUO8XA��[$_obfuscate_6A��['parentKongjian']] = $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_w2Zth0sNjFqUYc_5pS0�[$_obfuscate_6A��['childKongjian']]][$_obfuscate_6A��['childKongjian']];
                    }
                }
                if ( ereg( "D_", $_obfuscate_6A��['parentKongjian'] ) )
                {
                    $_obfuscate_8jhldA9Y9A�� = str_replace( "D_", "", $_obfuscate_6A��['parentKongjian'] );
                    $_obfuscate_Ce9h = $CNOA_DB->db_getfield( "fid", $this->t_set_field_detail, "WHERE `id` = ".$_obfuscate_8jhldA9Y9A��." " );
                    if ( ereg( "T_", $_obfuscate_6A��['childKongjian'] ) )
                    {
                        $_obfuscate_8RkPDdDI4g��[$_obfuscate_Ce9h][$_obfuscate_6A��['parentKongjian']] = $_obfuscate_XKxKFeaAMUQ�[$_obfuscate_6A��['childKongjian']];
                    }
                    else if ( ereg( "D_", $_obfuscate_6A��['childKongjian'] ) )
                    {
                        $_obfuscate_8RkPDdDI4g��[$_obfuscate_Ce9h][$_obfuscate_6A��['parentKongjian']] = $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_w2Zth0sNjFqUYc_5pS0�[$_obfuscate_6A��['childKongjian']]][$_obfuscate_6A��['childKongjian']];
                    }
                }
            }
        }
        if ( !empty( $_obfuscate_gZ2OAUO8XA�� ) )
        {
            ( $_obfuscate_wzlwEupWLkw� );
            $_obfuscate_e53ODz04JQ�� = new wfCache( );
            $_obfuscate_8LH7ik2lzjhs7g�� = $_obfuscate_e53ODz04JQ��->getConfig( "step_child_kongjian" );
            if ( empty( $_obfuscate_8LH7ik2lzjhs7g�� ) )
            {
                return;
            }
            $_obfuscate_jOcDpChC9w�� = $CNOA_DB->db_getone( "*", $this->t_use_step_child_flow, "WHERE `uFlowId` = ".$_obfuscate_TlvKhtsoOQ�� );
            if ( !is_array( $_obfuscate_jOcDpChC9w�� ) )
            {
                $_obfuscate_jOcDpChC9w�� = array( );
            }
            $_obfuscate_YnNK_c9snw�� = array( );
            foreach ( $_obfuscate_8LH7ik2lzjhs7g�� as $_obfuscate_VgKtFeg� )
            {
                if ( $_obfuscate_VgKtFeg�['bangdingFlow'] == $_obfuscate_F4AbnVRh )
                {
                    $_obfuscate_YnNK_c9snw��[] = $_obfuscate_VgKtFeg�;
                }
            }
            foreach ( $_obfuscate_YnNK_c9snw�� as $_obfuscate_YIq2A8c� )
            {
                foreach ( $_obfuscate_gZ2OAUO8XA�� as $_obfuscate_Vwty => $_obfuscate_LQ8UKg�� )
                {
                    if ( !( $_obfuscate_YIq2A8c�['stepId'] != $_obfuscate_jOcDpChC9w��['stepId'] ) )
                    {
                        if ( $_obfuscate_YIq2A8c�['parentType'] == "str" && $_obfuscate_Vwty == $_obfuscate_YIq2A8c�['parentKongjian'] && $_obfuscate_YIq2A8c�['arrow'] == "right" && !empty( $_obfuscate_LQ8UKg�� ) )
                        {
                            if ( $_obfuscate_YIq2A8c�['childType'] == "user_sel" )
                            {
                                $_obfuscate_LQ8UKg�� = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_LQ8UKg�� );
                            }
                            else if ( $_obfuscate_YIq2A8c�['childType'] == "users_sel" )
                            {
                                $_obfuscate_xs33Yt_k = app::loadapp( "main", "user" )->api_getUserTruenameByUid( explode( ",", $_obfuscate_LQ8UKg�� ) );
                                $_obfuscate_LQ8UKg�� = implode( ",", $_obfuscate_xs33Yt_k );
                            }
                            else if ( $_obfuscate_YIq2A8c�['childType'] == "dept_sel" )
                            {
                                $_obfuscate_LQ8UKg�� = app::loadapp( "main", "struct" )->api_getNameById( $_obfuscate_LQ8UKg�� );
                            }
                            else if ( $_obfuscate_YIq2A8c�['childType'] == "depts_sel" )
                            {
                                $_obfuscate_VgKtFeg� = app::loadapp( "main", "struct" )->api_getNamesByIds( explode( ",", $_obfuscate_LQ8UKg�� ) );
                                $_obfuscate_LQ8UKg�� = implode( ",", $_obfuscate_VgKtFeg� );
                            }
                            else if ( $_obfuscate_YIq2A8c�['childType'] == "station_sel" )
                            {
                                $_obfuscate_LQ8UKg�� = app::loadapp( "main", "station" )->api_getNameById( $_obfuscate_LQ8UKg�� );
                            }
                            else if ( $_obfuscate_YIq2A8c�['childType'] == "stations_sel" )
                            {
                                $_obfuscate_VgKtFeg� = app::loadapp( "main", "station" )->api_getNamesByIds( explode( ",", $_obfuscate_LQ8UKg�� ) );
                                $_obfuscate_LQ8UKg�� = implode( ",", $_obfuscate_VgKtFeg� );
                            }
                            else if ( $_obfuscate_YIq2A8c�['childType'] == "job_sel" )
                            {
                                $_obfuscate_LQ8UKg�� = app::loadapp( "main", "job" )->api_getNameById( $_obfuscate_LQ8UKg�� );
                            }
                            else if ( $_obfuscate_YIq2A8c�['childType'] == "jobs_sel" )
                            {
                                $_obfuscate_VgKtFeg� = app::loadapp( "main", "job" )->api_getNamesByIds( explode( ",", $_obfuscate_LQ8UKg�� ) );
                                $_obfuscate_LQ8UKg�� = implode( ",", $_obfuscate_VgKtFeg� );
                            }
                            else if ( $_obfuscate_YIq2A8c�['childType'] == "loginname" )
                            {
                                $_obfuscate_LQ8UKg�� = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_LQ8UKg�� );
                            }
                        }
                        $_obfuscate_gZ2OAUO8XA��[$_obfuscate_Vwty] = $_obfuscate_LQ8UKg��;
                    }
                }
            }
            $CNOA_DB->db_update( $_obfuscate_gZ2OAUO8XA��, "z_wf_t_".$_obfuscate_dXDzrHajXw��, "WHERE `uFlowId` = ".$_obfuscate_wzlwEupWLkw�." " );
        }
        if ( !empty( $_obfuscate_8RkPDdDI4g�� ) )
        {
            foreach ( $_obfuscate_8RkPDdDI4g�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $CNOA_DB->update( $_obfuscate_gZ2OAUO8XA��, "z_wf_t_".$_obfuscate_dXDzrHajXw��."_".$_obfuscate_5w��, "WHERE `uFlowId` = ".$_obfuscate_wzlwEupWLkw�." " );
            }
        }
    }

    public function api_getStepList( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmk� )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `uid`!=0 AND `status`!=0 ORDER BY `id` ASC" );
        if ( $_obfuscate_mPAjEGLn !== FALSE )
        {
            $_obfuscate_PVLK5jra = array( 0 );
            $_obfuscate_QwT4KwrB2w�� = array( 0 );
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_PVLK5jra[] = $_obfuscate_6A��['uid'];
                $_obfuscate_PVLK5jra[] = $_obfuscate_6A��['proxyUid'];
                $_obfuscate_QwT4KwrB2w��[] = $_obfuscate_6A��['uStepId'];
            }
            $_obfuscate_dga5p5gjYJ23VQ�� = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_PVLK5jra );
            $_obfuscate_8NPwP9PwBw� = $CNOA_DB->db_select( array( "stepId", "doBtnText" ), $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `stepId` IN (".implode( ",", $_obfuscate_QwT4KwrB2w�� ).")" );
            if ( !is_array( $_obfuscate_8NPwP9PwBw� ) )
            {
                $_obfuscate_8NPwP9PwBw� = array( );
            }
            $_obfuscate_hEQ34cXm = array( );
            foreach ( $_obfuscate_8NPwP9PwBw� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_hEQ34cXm[$_obfuscate_6A��['stepId']] = $_obfuscate_6A��;
            }
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                if ( $_obfuscate_XkuTFqZ6Tmk� == 0 )
                {
                    if ( $_obfuscate_6A��['status'] == 2 && $_obfuscate_6A��['pStepId'] != 0 )
                    {
                        $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['statusText'] = $this->f_stepType[$_obfuscate_6A��['status']]."(".$this->f_btn_text[$_obfuscate_hEQ34cXm[$_obfuscate_6A��['uStepId']]['doBtnText']].")";
                    }
                    else
                    {
                        $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['statusText'] = $this->f_stepType[$_obfuscate_6A��['status']];
                    }
                }
                else
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['statusText'] = $this->f_stepType[$_obfuscate_6A��['status']];
                }
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['nodename'] = $_obfuscate_6A��['stepname'];
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['uname'] = empty( $_obfuscate_dga5p5gjYJ23VQ��[$_obfuscate_6A��['uid']]['truename'] ) ? "<span style='color:#FF6600'>".lang( "userNotExist" )."</span>" : $_obfuscate_dga5p5gjYJ23VQ��[$_obfuscate_6A��['uid']]['truename'];
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['cuibanName'] = empty( $_obfuscate_dga5p5gjYJ23VQ��[$_obfuscate_6A��['uid']]['truename'] ) ? "<span style='color:#FF6600'>".lang( "userNotExist" )."</span>" : $_obfuscate_dga5p5gjYJ23VQ��[$_obfuscate_6A��['uid']]['truename'];
                if ( $this->clientType == "PC" )
                {
                    $_obfuscate_hn8oeqVsTHU� = "<img src='./resources/images/icons/arrow_right.png' ext:qtip='".lang( "hasBeenEntruste" )."' />";
                    $_obfuscate_D4nNVUJFJQ�� = "<img src='./resources/images/icons/user--pencil.png' ext:qtip='".lang( "acceptOfficer" )."' />";
                }
                if ( $this->clientType == "MOB" )
                {
                    $_obfuscate_hn8oeqVsTHU� = "=>";
                    $_obfuscate_D4nNVUJFJQ�� = "";
                }
                if ( $_obfuscate_6A��['proxyUid'] != 0 && ( $_obfuscate_6A��['status'] == 2 || $_obfuscate_6A��['status'] == 4 ) )
                {
                    if ( $_obfuscate_6A��['dealUid'] == $_obfuscate_6A��['proxyUid'] )
                    {
                        $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['uname'] .= $_obfuscate_hn8oeqVsTHU�.$_obfuscate_dga5p5gjYJ23VQ��[$_obfuscate_6A��['proxyUid']]['truename'].$_obfuscate_D4nNVUJFJQ��;
                    }
                    else
                    {
                        $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['uname'] = $_obfuscate_D4nNVUJFJQ��.$_obfuscate_dga5p5gjYJ23VQ��[$_obfuscate_6A��['uid']]['truename'];
                        $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['uname'] .= $_obfuscate_hn8oeqVsTHU�.$_obfuscate_dga5p5gjYJ23VQ��[$_obfuscate_6A��['proxyUid']]['truename'];
                        $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['cuibanName'] = $_obfuscate_dga5p5gjYJ23VQ��[$_obfuscate_6A��['uid']]['truename'];
                    }
                }
                else if ( $_obfuscate_6A��['proxyUid'] != 0 && $_obfuscate_6A��['status'] == 1 )
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['uname'] .= $_obfuscate_hn8oeqVsTHU�.$_obfuscate_dga5p5gjYJ23VQ��[$_obfuscate_6A��['proxyUid']]['truename'];
                }
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['formatStime'] = date( "Y年m月d日 H:i", $_obfuscate_6A��['stime'] );
                if ( ( $_obfuscate_6A��['status'] == 2 || $_obfuscate_6A��['status'] == 4 ) && $_obfuscate_6A��['uStepId'] != 0 )
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['utime'] = timeformat2( $_obfuscate_6A��['etime'] - $_obfuscate_6A��['stime'] );
                    if ( $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['utime'] == 0 )
                    {
                        $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['utime'] = "----";
                    }
                }
                else if ( $_obfuscate_6A��['uStepId'] != 0 )
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['utime'] = timeformat2( $GLOBALS['CNOA_TIMESTAMP'] - $_obfuscate_6A��['stime'] );
                }
                else
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['utime'] = "----";
                }
                if ( $_obfuscate_6A��['status'] == 2 && !( $_obfuscate_6A��['nStepId'] == 0 ) || ( !( $_obfuscate_6A��['status'] == 0 ) && !( $_obfuscate_6A��['nStepId'] == 0 ) ) )
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['uname'] = "----";
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['statusText'] = "----";
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['formatStime'] = "";
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['utime'] = "----";
                }
            }
        }
        return $_obfuscate_mPAjEGLn;
    }

    public function api_getEventList( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmk� )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_event, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' ORDER BY `uEventId` ASC" );
        if ( $_obfuscate_mPAjEGLn !== FALSE )
        {
            $_obfuscate_PVLK5jra = array( 0 );
            $_obfuscate_QwT4KwrB2w�� = array( 0 );
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_PVLK5jra[] = $_obfuscate_6A��['uid'];
                $_obfuscate_QwT4KwrB2w��[] = $_obfuscate_6A��['step'];
            }
            $_obfuscate_dga5p5gjYJ23VQ�� = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_PVLK5jra, FALSE );
            $_obfuscate_8NPwP9PwBw� = $CNOA_DB->db_select( array( "stepId", "doBtnText" ), $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `stepId` IN (".implode( ",", $_obfuscate_QwT4KwrB2w�� ).")" );
            if ( !is_array( $_obfuscate_8NPwP9PwBw� ) )
            {
                $_obfuscate_8NPwP9PwBw� = array( );
            }
            $_obfuscate_hEQ34cXm = array( );
            foreach ( $_obfuscate_8NPwP9PwBw� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_hEQ34cXm[$_obfuscate_6A��['stepId']] = $_obfuscate_6A��;
            }
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                if ( $_obfuscate_XkuTFqZ6Tmk� == 0 )
                {
                    if ( $_obfuscate_6A��['type'] == 2 )
                    {
                        $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['typename'] = $this->f_btn_text[$_obfuscate_hEQ34cXm[$_obfuscate_6A��['step']]['doBtnText']];
                    }
                    else
                    {
                        $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['typename'] = $this->f_eventType[$_obfuscate_6A��['type']];
                    }
                }
                else
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['typename'] = $this->f_eventType[$_obfuscate_6A��['type']];
                }
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['uname'] = empty( $_obfuscate_dga5p5gjYJ23VQ��[$_obfuscate_6A��['uid']]['truename'] ) ? "<span style=\"color:#FF6600\">用户不存在</span>" : $_obfuscate_dga5p5gjYJ23VQ��[$_obfuscate_6A��['uid']]['truename'];
                $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['posttime'] = date( "Y年m月d日 H:i", $_obfuscate_6A��['posttime'] );
            }
        }
        return $_obfuscate_mPAjEGLn;
    }

    public function api_getReadList( $_obfuscate_TlvKhtsoOQ�� )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_6RYLWQ��['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_6RYLWQ��['isread'] = 1;
        $_obfuscate_6RYLWQ��['viewtime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $CNOA_DB->db_update( $_obfuscate_6RYLWQ��, $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' AND `touid`='{$_obfuscate_7Ri3}'" );
        $_obfuscate_0WaREsXoZ4w� = array( );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."' " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_PVLK5jra = array( 0 );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_PVLK5jra[$_obfuscate_6A��['touid']] = $_obfuscate_6A��['touid'];
            $_obfuscate_PVLK5jra[$_obfuscate_6A��['fenfauid']] = $_obfuscate_6A��['fenfauid'];
        }
        $_obfuscate_dga5p5gjYJ23VQ�� = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_PVLK5jra );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_6b8lIO4y = $_obfuscate_6A��['isread'] == 1 ? "<span class=\"cnoa_color_red\">[".lang( "readed" )."]</span>" : "<span class=\"cnoa_color_gray\">[".lang( "unread" )."]</span>";
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['fenfaName'] = $_obfuscate_dga5p5gjYJ23VQ��[$_obfuscate_6A��['fenfauid']]['truename'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['uname'] = $_obfuscate_6b8lIO4y.( empty( $_obfuscate_dga5p5gjYJ23VQ��[$_obfuscate_6A��['touid']]['truename'] ) ? "<span style=\"color:#FF6600\">用户不存在</span>" : $_obfuscate_dga5p5gjYJ23VQ��[$_obfuscate_6A��['touid']]['truename'] );
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['sayDate'] = formatdate( $_obfuscate_6A��['viewtime'], "Y-m-d H:i" );
            $_obfuscate_mPAjEGLn[$_obfuscate_5w��]['deptment'] = app::loadapp( "main", "user" )->api_getDeptNameByUid( $_obfuscate_6A��['touid'] );
        }
        return $_obfuscate_mPAjEGLn;
    }

    public function api_loadProxyFormData( $_obfuscate_0W8�, $_obfuscate_yiKUFDGCug�� )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_RopcQP_w = $CNOA_DB->db_select( "*", $this->t_set_sort );
        if ( !is_array( $_obfuscate_RopcQP_w ) )
        {
            $_obfuscate_RopcQP_w = array( );
        }
        $_obfuscate_uly_hPh_dQ�� = array( );
        foreach ( $_obfuscate_RopcQP_w as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_uly_hPh_dQ��[$_obfuscate_6A��['sortId']] = $_obfuscate_6A��;
        }
        $_obfuscate_PpZPqh6HEA�� = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_IRFhnYw� = "WHERE `fromuid`='".$_obfuscate_yiKUFDGCug��."' AND ((`etime`=0 AND `stime`<={$_obfuscate_PpZPqh6HEA��}) OR (`stime`<={$_obfuscate_PpZPqh6HEA��} AND `etime`>={$_obfuscate_PpZPqh6HEA��}))";
        $_obfuscate_4WYIiiNEiQyn = $CNOA_DB->db_select( "*", $this->t_use_proxy_flow, $_obfuscate_IRFhnYw� );
        if ( !is_array( $_obfuscate_4WYIiiNEiQyn ) )
        {
            $_obfuscate_4WYIiiNEiQyn = array( );
        }
        $_obfuscate_WMVwRv5Dg�� = array( );
        foreach ( $_obfuscate_4WYIiiNEiQyn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['uProxyId'] == $_obfuscate_0W8� )
            {
                $_obfuscate_6A��['checked'] = TRUE;
                $_obfuscate_6A��['enable'] = TRUE;
            }
            else
            {
                $_obfuscate_6A��['checked'] = FALSE;
                $_obfuscate_6A��['enable'] = FALSE;
            }
            $_obfuscate_WMVwRv5Dg��[$_obfuscate_6A��['flowId']] = $_obfuscate_6A��;
        }
        $_obfuscate_SIUSR4F6 = $CNOA_DB->db_select( array( "sortId", "flowId", "name" ), $this->t_set_flow, "WHERE `status`=1" );
        if ( !is_array( $_obfuscate_SIUSR4F6 ) )
        {
            $_obfuscate_SIUSR4F6 = array( );
        }
        $_obfuscate_8Bnz38wN01c� = array( );
        foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( $_obfuscate_WMVwRv5Dg��[$_obfuscate_6A��['flowId']]['checked'] === TRUE )
            {
                $_obfuscate_6A��['checked'] = TRUE;
            }
            else
            {
                $_obfuscate_6A��['checked'] = FALSE;
            }
            if ( $_obfuscate_WMVwRv5Dg��[$_obfuscate_6A��['flowId']]['enable'] === FALSE )
            {
                $_obfuscate_6A��['enable'] = FALSE;
            }
            else
            {
                $_obfuscate_6A��['enable'] = TRUE;
            }
            $_obfuscate_8Bnz38wN01c�[$_obfuscate_6A��['sortId']][] = $_obfuscate_6A��;
        }
        $_obfuscate_6RYLWQ�� = array( );
        foreach ( $_obfuscate_8Bnz38wN01c� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_6RYLWQ��[] = array(
                "sortId" => $_obfuscate_6A��[0]['sortId'],
                "sname" => $_obfuscate_uly_hPh_dQ��[$_obfuscate_6A��[0]['sortId']]['name'],
                "items" => $_obfuscate_6A��
            );
        }
        return $_obfuscate_6RYLWQ��;
    }

    protected function insertEvent( $_obfuscate_JG8GuY� )
    {
        global $CNOA_DB;
        if ( !is_array( $_obfuscate_JG8GuY� ) )
        {
            return;
        }
        foreach ( $_obfuscate_JG8GuY� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_JG8GuY�[$_obfuscate_5w��] = addslashes( $_obfuscate_6A�� );
        }
        $_obfuscate_UNQLdMbxTK0� = $CNOA_DB->db_insert( $_obfuscate_JG8GuY�, $this->t_use_event );
        return $_obfuscate_UNQLdMbxTK0�;
    }

    protected function exportFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $uid = $CNOA_SESSION->get( "UID" );
        $GLOBALS['GLOBALS']['LANGUAGE'] = $CNOA_SESSION->get( "LANGUAGE" );
        $flowId = ( integer )getpar( $_GET, "flowId" );
        $uFlowId = ( integer )getpar( $_GET, "uFlowId" );
        $step = ( integer )getpar( $_GET, "step" );
        $hidden = getpar( $_GET, "isHidden", "" );
        $baseBody = getpar( $_GET, "baseBody", 1 );
        $formBody = getpar( $_GET, "formBody", 0 );
        $stepBody = getpar( $_GET, "stepBody", 1 );
        $eventBody = getpar( $_GET, "eventBody", 1 );
        $readBody = getpar( $_GET, "readBody", 1 );
        $fsBody = getpar( $_GET, "fsBody", 1 );
        $data = $this->printBase( $uFlowId );
        $nstep = $this->printStep( $uFlowId, $flowId, 0 );
        $event = $this->printEvent( $uFlowId, $flowId, 0 );
        $read = $this->printRead( $uFlowId );
        $fs = $this->printFs( $uFlowId, $flowId );
        ( $uFlowId );
        $GLOBALS['GLOBALS']['UWFCACHE'] = new wfCache( );
        $flowDB = $GLOBALS['UWFCACHE']->getFlow( );
        $pageset = $flowDB['pageset'];
        $isEnd = $CNOA_DB->db_getfield( "stepType", "wf_u_step", "WHERE `uFlowId`=".$uFlowId." ORDER BY `id` DESC" );
        if ( $isEnd == "3" )
        {
            $formHtml = app::loadapp( "wf", "flowUseTodo" )->_loadFormHtmlView( $flowId, $uFlowId, $step, "done", TRUE );
        }
        else
        {
            $formHtml = app::loadapp( "wf", "flowUseTodo" )->_loadFormHtmlView( $flowId, $uFlowId, $step, "show", TRUE );
        }
        include( $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/newPrint.htm" );
    }

    protected function exportFreeFlow( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        global $CNOA_CONTROLLER;
        $uid = $CNOA_SESSION->get( "UID" );
        $GLOBALS['GLOBALS']['LANGUAGE'] = $CNOA_SESSION->get( "LANGUAGE" );
        $flowId = ( integer )getpar( $_GET, "flowId" );
        $uFlowId = ( integer )getpar( $_GET, "uFlowId" );
        $step = ( integer )getpar( $_GET, "step" );
        $flowType = ( integer )getpar( $_GET, "flowType" );
        $tplSort = ( integer )getpar( $_GET, "tplSort" );
        $data = $this->printBase( $uFlowId );
        $nstep = $this->printStep( $uFlowId, $flowId, 0 );
        $event = $this->printEvent( $uFlowId, $flowId, 0 );
        $read = $this->printRead( $uFlowId );
        if ( $tplSort == 1 || $tplSort == 2 )
        {
            $formHidden = 1;
        }
        if ( $tplSort == 3 )
        {
            $formHtml = app::loadapp( "wf", "flowUseTodo" )->_loadFormHtmlView( $flowId, $uFlowId, $step, "done", TRUE );
        }
        if ( $tplSort == 0 )
        {
            $formHtml = $CNOA_DB->db_getfield( "htmlFormContent", $this->t_use_flow, "WHERE `uFlowId`='".$uFlowId."'" );
        }
        include( $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/newPrintFree.htm" );
    }

    protected function saveprint( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_ncdC0pM� = getpar( $_POST, "width", 100 );
        $CNOA_DB->db_delete( $this->t_s_print, "WHERE `uid` = '".$_obfuscate_7Ri3."' " );
        $_obfuscate_ncdC0pM� = $_obfuscate_ncdC0pM� == 0 ? 1 : $_obfuscate_ncdC0pM�;
        $CNOA_DB->db_insert( array(
            "uid" => $_obfuscate_7Ri3,
            "width" => $_obfuscate_ncdC0pM�
        ), $this->t_s_print );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    protected function printStep( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmk� )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mPAjEGLn = $this->api_getStepList( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmk� );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_lEGQqw�� = "";
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_lEGQqw�� .= "<tr>";
            $_obfuscate_lEGQqw�� .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6A��['stepname']."</td>";
            $_obfuscate_lEGQqw�� .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6A��['statusText']."</td>";
            $_obfuscate_lEGQqw�� .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6A��['uname']."</br>".$_obfuscate_6A��['formatStime']."</td>";
            $_obfuscate_lEGQqw�� .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6A��['utime']."</td>";
            $_obfuscate_lEGQqw�� .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6A��['say']."</td>";
            $_obfuscate_lEGQqw�� .= "</tr>";
        }
        return $_obfuscate_lEGQqw��;
    }

    protected function printFs( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        $_obfuscate_5LuNFL5U2xQ� = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $_obfuscate_1jUa = json_decode( $_obfuscate_5LuNFL5U2xQ�['attach'], TRUE );
        foreach ( $_obfuscate_1jUa as $_obfuscate_6A�� )
        {
            $_obfuscate_ViKf3g�� .= $_obfuscate_6A��.",";
        }
        $_obfuscate_1jUa = rtrim( $_obfuscate_ViKf3g��, "," );
        $_obfuscate_mLjk2t6lphU� = $CNOA_DB->db_select( array( "id" ), "wf_s_field", " WHERE `flowId`=".$_obfuscate_F4AbnVRh." and `otype`='attach'" );
        if ( !empty( $_obfuscate_mLjk2t6lphU� ) )
        {
            $_obfuscate_3tiDsnM� = "z_wf_t_".$_obfuscate_F4AbnVRh;
            if ( !is_array( $_obfuscate_mLjk2t6lphU� ) )
            {
                $_obfuscate_mLjk2t6lphU� = array( );
            }
            foreach ( $_obfuscate_mLjk2t6lphU� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_YIq2A8c� = "T_".$_obfuscate_6A��['id'];
                $_obfuscate_SeV31Q�� = $CNOA_DB->db_getfield( $_obfuscate_YIq2A8c�, $_obfuscate_3tiDsnM�, " WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
                $_obfuscate_1_pbjTIdLU49 .= $_obfuscate_SeV31Q��.",";
            }
            $_obfuscate_ViKf3g�� = rtrim( str_replace( "[", "", str_replace( "]", "", $_obfuscate_1_pbjTIdLU49 ) ), "," );
            if ( !empty( $_obfuscate_1jUa ) || !empty( $_obfuscate_ViKf3g�� ) )
            {
                $_obfuscate_1jUa = $_obfuscate_1jUa.",".$_obfuscate_ViKf3g��;
            }
            else if ( empty( $_obfuscate_1jUa ) && !empty( $_obfuscate_ViKf3g�� ) )
            {
                $_obfuscate_1jUa = $_obfuscate_ViKf3g��;
            }
            else
            {
                $_obfuscate_1jUa = $_obfuscate_1jUa;
            }
        }
        $_obfuscate_fMfssw�� = $CNOA_DB->db_select( "*", "system_fs", "WHERE `id` IN(".$_obfuscate_1jUa.")" );
        if ( !is_array( $_obfuscate_fMfssw�� ) )
        {
            $_obfuscate_fMfssw�� = array( );
        }
        $_obfuscate_lEGQqw�� = "";
        $_obfuscate__eqrEQ�� = array( 0 );
        $_obfuscate_xIiKpDYD = array( );
        foreach ( $_obfuscate_fMfssw�� as $_obfuscate_6A�� )
        {
            $_obfuscate__eqrEQ��[] = $_obfuscate_6A��['uid'];
            $_obfuscate_xIiKpDYD[] = array(
                "filename" => $_obfuscate_6A��['oldname'],
                "uid" => $_obfuscate_6A��['uid'],
                "date" => formatdate( $_obfuscate_6A��['posttime'], "Y-m-d H:i:s" ),
                "id" => $_obfuscate_6A��['id']
            );
        }
        $_obfuscate_tqpXCDg� = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate__eqrEQ�� );
        foreach ( $_obfuscate_xIiKpDYD as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_lEGQqw�� .= "<tr>";
            $_obfuscate_lEGQqw�� .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_xIiKpDYD[$_obfuscate_5w��]['filename']."</td>";
            $_obfuscate_lEGQqw�� .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_tqpXCDg�[$_obfuscate_6A��['uid']]['truename']."</td>";
            $_obfuscate_lEGQqw�� .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_xIiKpDYD[$_obfuscate_5w��]['date']."</td>";
            $_obfuscate_lEGQqw�� .= "<td style = \"display:none\">".$_obfuscate_xIiKpDYD[$_obfuscate_5w��]['id']."</td>";
            $_obfuscate_lEGQqw�� .= "</tr>";
        }
        return $_obfuscate_lEGQqw��;
    }

    protected function printEvent( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmk� )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mPAjEGLn = $this->api_getEventList( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmk� );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_lEGQqw�� = "";
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_lEGQqw�� .= "<tr>";
            $_obfuscate_lEGQqw�� .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6A��['typename']."</td>";
            $_obfuscate_lEGQqw�� .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6A��['stepname']."</td>";
            $_obfuscate_lEGQqw�� .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6A��['uname']."</br>".$_obfuscate_6A��['posttime']."</td>";
            $_obfuscate_lEGQqw�� .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6A��['say']."</td>";
            $_obfuscate_lEGQqw�� .= "</tr>";
        }
        return $_obfuscate_lEGQqw��;
    }

    protected function printRead( $_obfuscate_TlvKhtsoOQ�� )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mPAjEGLn = $this->api_getReadList( $_obfuscate_TlvKhtsoOQ�� );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_lEGQqw�� = "";
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_lEGQqw�� .= "<tr>";
            $_obfuscate_lEGQqw�� .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6A��['uname']."</td>";
            $_obfuscate_lEGQqw�� .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6A��['deptment']."</td>";
            $_obfuscate_lEGQqw�� .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6A��['say']."</td>";
            $_obfuscate_lEGQqw�� .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6A��['sayDate']."</td>";
            $_obfuscate_lEGQqw�� .= "</tr>";
        }
        return $_obfuscate_lEGQqw��;
    }

    protected function printBase( $_obfuscate_TlvKhtsoOQ�� )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mPAjEGLn = $this->api_getBaseList( $_obfuscate_TlvKhtsoOQ�� );
        return $_obfuscate_mPAjEGLn;
    }

    protected function printList( )
    {
        $_obfuscate_TlvKhtsoOQ�� = getpar( $_GET, "uFlowId", getpar( $_POST, "uFlowId", 0 ) );
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", getpar( $_POST, "flowId", 0 ) );
        $_obfuscate_XkuTFqZ6Tmk� = getpar( $_GET, "flowType", getpar( $_POST, "flowType", 0 ) );
        $_obfuscate_pEvU7Kz2Yw�� = getpar( $_GET, "tplSort", getpar( $_POST, "tplSort", 0 ) );
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $this->printBase( $_obfuscate_TlvKhtsoOQ�� );
        $_obfuscate_NlQ�->step = $this->printStep( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmk� );
        $_obfuscate_NlQ�->event = $this->printEvent( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmk� );
        $_obfuscate_NlQ�->read = $this->printRead( $_obfuscate_TlvKhtsoOQ�� );
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    protected function exportWord( )
    {
        global $CNOA_DB;
        global $CNOA_CONTROLLER;
        global $CNOA_SESSION;
        $target = getpar( $_GET, "target", "" );
        $uFlowId = ( integer )getpar( $_GET, "uFlowId" );
        $flowId = ( integer )getpar( $_GET, "flowId" );
        $stepId = ( integer )getpar( $_GET, "stepId" );
        $flow = $CNOA_DB->db_getone( array( "flowNumber", "flowName" ), $this->t_use_flow, "WHERE `uFlowId`='".$uFlowId."'" );
        if ( $target == "html" || $target == "word" )
        {
            $ext = $target == "html" ? ".mht" : ".doc";
            $htmlTempFile = "flowInfoExport.".$GLOBALS['CNOA_TIMESTAMP'].string::rands( 10 );
            $htmlTempPath = CNOA_PATH_FILE."/common/temp/".$htmlTempFile.$ext;
            ob_start( );
            eval( "\$html = \$_POST['html']; " );
            include( $CNOA_CONTROLLER->appPath."/tpl/default/flow/use/newPrint2.htm" );
            ( ob_get_contents( ) );
            $msdoc = new msdoc( );
            $msdoc->save( $htmlTempPath );
            ob_end_clean( );
            $msg = makedownfileoncelink( "工作流导出[".$flow['flowNumber']."]".$ext, "/common/temp/".$htmlTempFile.$ext );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "export", 3605, "了流程，流程名称[".$flow['flowName']."]编号[".$flow['flowNumber']."]" );
            msg::callback( TRUE, $msg );
        }
        else if ( $target == "image" || $target == "toDisk" )
        {
            $baseBody = getpar( $_POST, "baseBody" );
            $formBody = getpar( $_POST, "formBody" );
            $stepBody = getpar( $_POST, "stepBody" );
            $eventBody = getpar( $_POST, "eventBody" );
            $readBody = getpar( $_POST, "readBody" );
            $fsBody = getpar( $_POST, "fsBody" );
            $url = "index.php?app=wf&func=flow&action=use&modul=todo&task=exportFlow";
            $url .= "&uFlowId=".$uFlowId;
            $url .= "&flowId=".$flowId;
            $url .= "&stepId=".$stepId;
            $url .= "&isHidden=1";
            $url .= "&baseBody=".$baseBody;
            $url .= "&formBody=".$formBody;
            $url .= "&stepBody=".$stepBody;
            $url .= "&eventBody=".$eventBody;
            $url .= "&readBody=".$readBody;
            $url .= "&fsBody=".$fsBody;
            $url .= "&CNOAOASESSID=".$CNOA_SESSION->getSessionId( );
            $imageTempFile = "flowInfoExport.".$GLOBALS['CNOA_TIMESTAMP'].string::rands( 10 );
            ( );
            $htmlToImage = new htmlToImage( );
            $htmlToImage->setSaveType( "jpg" );
            $htmlToImage->addPage( getbaseurl( ).$url );
            $htmlToImage->saveAs( CNOA_PATH_FILE."/common/temp/".$imageTempFile );
            if ( $target == "toDisk" )
            {
                $msg = json_encode( array(
                    "file" => $imageTempFile.".jpg",
                    "name" => $flow['flowNumber']." - ".$flow['flowName']
                ) );
            }
            else
            {
                $msg = makedownfileoncelink( "工作流导出[".$flow['flowNumber']."].jpg", "/common/temp/".$imageTempFile.".jpg" );
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "export", 3605, "了流程，流程名称[".$flow['flowName']."]编号[".$flow['flowNumber']."]" );
            msg::callback( TRUE, $msg );
        }
    }

    protected function getProxyUid( $_obfuscate_F4AbnVRh, $_obfuscate_CArovL72w�� )
    {
        global $CNOA_DB;
        $_obfuscate_lQ81YBM� = 0;
        $_obfuscate_h8TEbtzlSdi = $CNOA_DB->db_select( "*", $this->t_use_proxy, "WHERE `status`=1 AND `fromuid`='".$_obfuscate_CArovL72w��."'" );
        if ( !is_array( $_obfuscate_h8TEbtzlSdi ) )
        {
            $_obfuscate_h8TEbtzlSdi = array( );
        }
        $_obfuscate_PpZPqh6HEA�� = $GLOBALS['CNOA_TIMESTAMP'];
        foreach ( $_obfuscate_h8TEbtzlSdi as $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['etime'] == 0 && !( $_obfuscate_6A��['stime'] <= $_obfuscate_PpZPqh6HEA�� ) || ( !( $_obfuscate_6A��['stime'] <= $_obfuscate_PpZPqh6HEA�� ) && !( $_obfuscate_PpZPqh6HEA�� <= $_obfuscate_6A��['etime'] ) ) )
            {
                $_obfuscate_8Bnz38wN01c� = json_decode( $_obfuscate_6A��['flowId'], TRUE );
                if ( is_array( $_obfuscate_8Bnz38wN01c� ) )
                {
                    foreach ( $_obfuscate_8Bnz38wN01c� as $_obfuscate_EGU� )
                    {
                        if ( $_obfuscate_EGU� == $_obfuscate_F4AbnVRh )
                        {
                            $_obfuscate_lQ81YBM� = $_obfuscate_6A��['touid'];
                        }
                    }
                }
            }
        }
        return $_obfuscate_lQ81YBM�;
    }

    protected function getProxyFromuid( $_obfuscate_F4AbnVRh, $_obfuscate_5ZL98vE� )
    {
        global $CNOA_DB;
        $_obfuscate_CArovL72w�� = 0;
        $_obfuscate_h8TEbtzlSdi = $CNOA_DB->db_select( "*", $this->t_use_proxy, "WHERE `status`=1 AND `touid`='".$_obfuscate_5ZL98vE�."'" );
        if ( !is_array( $_obfuscate_h8TEbtzlSdi ) )
        {
            $_obfuscate_h8TEbtzlSdi = array( );
        }
        $_obfuscate_PpZPqh6HEA�� = $GLOBALS['CNOA_TIMESTAMP'];
        foreach ( $_obfuscate_h8TEbtzlSdi as $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['etime'] == 0 && !( $_obfuscate_6A��['stime'] <= $_obfuscate_PpZPqh6HEA�� ) || ( !( $_obfuscate_6A��['stime'] <= $_obfuscate_PpZPqh6HEA�� ) && !( $_obfuscate_PpZPqh6HEA�� <= $_obfuscate_6A��['etime'] ) ) )
            {
                $_obfuscate_8Bnz38wN01c� = json_decode( $_obfuscate_6A��['flowId'], TRUE );
                if ( is_array( $_obfuscate_8Bnz38wN01c� ) )
                {
                    foreach ( $_obfuscate_8Bnz38wN01c� as $_obfuscate_EGU� )
                    {
                        if ( $_obfuscate_EGU� == $_obfuscate_F4AbnVRh )
                        {
                            $_obfuscate_CArovL72w�� = $_obfuscate_6A��['formuid'];
                        }
                    }
                }
            }
        }
        return $_obfuscate_CArovL72w��;
    }

    protected function insertProxyData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_CArovL72w��, $_obfuscate_lQ81YBM� )
    {
        global $CNOA_DB;
        if ( intval( $_obfuscate_lQ81YBM� ) == 0 )
        {
            return;
        }
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_6RYLWQ��['flowId'] = $_obfuscate_F4AbnVRh;
        $_obfuscate_6RYLWQ��['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
        $_obfuscate_6RYLWQ��['fromuid'] = $_obfuscate_CArovL72w��;
        $_obfuscate_6RYLWQ��['touid'] = $_obfuscate_lQ81YBM�;
        $_obfuscate_6RYLWQ��['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_use_proxy_uflow );
    }

    protected function api_cancelFlow( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "id", "uStepId", "uid", "proxyUid", "dealUid", "status", "stepType", "stepname" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_5NhzjnJq_f8� = $_obfuscate_td3BMkoeV0sT = $_obfuscate_wozTGT5K9nKFLM8� = $_obfuscate_Rho2Gip16nFI79JtdQ�� = array( );
        $_obfuscate_tC8MNsAzXA�� = $_obfuscate_Y0NtxNfStZgTsQ�� = 0;
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A�� )
        {
            $_obfuscate_td3BMkoeV0sT[] = $_obfuscate_6A��['id'];
            if ( in_array( $_obfuscate_6A��['status'], array( 2, 4 ) ) )
            {
                if ( $_obfuscate_6A��['proxyUid'] != 0 )
                {
                    $_obfuscate_wozTGT5K9nKFLM8�[$_obfuscate_6A��['id']] = $_obfuscate_6A��['proxyUid'];
                }
                else if ( $_obfuscate_6A��['dealUid'] != 0 )
                {
                    $_obfuscate_wozTGT5K9nKFLM8�[$_obfuscate_6A��['id']] = $_obfuscate_6A��['dealUid'];
                }
            }
            else if ( $_obfuscate_6A��['status'] == 1 )
            {
                if ( $_obfuscate_6A��['proxyUid'] != 0 )
                {
                    $_obfuscate_Rho2Gip16nFI79JtdQ��[$_obfuscate_6A��['id']] = $_obfuscate_6A��['proxyUid'];
                }
                else if ( $_obfuscate_6A��['uid'] != 0 )
                {
                    $_obfuscate_Rho2Gip16nFI79JtdQ��[$_obfuscate_6A��['id']] = $_obfuscate_6A��['uid'];
                }
            }
            if ( $_obfuscate_6A��['stepType'] == 1 )
            {
                $_obfuscate_tC8MNsAzXA�� = $_obfuscate_6A��['dealUid'];
                $_obfuscate_Y0NtxNfStZgTsQ�� = $_obfuscate_6A��['id'];
            }
            if ( isset( $_obfuscate_5NhzjnJq_f8�[$_obfuscate_6A��['uStepId']] ) )
            {
                if ( $_obfuscate_5NhzjnJq_f8�[$_obfuscate_6A��['uStepId']]['id'] < $_obfuscate_6A��['id'] )
                {
                    $_obfuscate_5NhzjnJq_f8�[$_obfuscate_6A��['uStepId']] = $_obfuscate_6A��;
                }
            }
            else
            {
                $_obfuscate_5NhzjnJq_f8�[$_obfuscate_6A��['uStepId']] = $_obfuscate_6A��;
            }
        }
        unset( $_obfuscate_mPAjEGLn );
        $_obfuscate_JG8GuY� = array( );
        $_obfuscate_JG8GuY�['uFlowId'] = $_obfuscate_TlvKhtsoOQ��;
        $_obfuscate_JG8GuY�['type'] = 3;
        $_obfuscate_JG8GuY�['step'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_JG8GuY�['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuY�['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuY�['say'] = "";
        $_obfuscate_JG8GuY�['stepname'] = $_obfuscate_5NhzjnJq_f8�[$_obfuscate_0Ul8BBkt]['stepname'];
        $this->insertEvent( $_obfuscate_JG8GuY� );
        $this->deleteAllNotice( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_td3BMkoeV0sT );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber" ), $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        ( $_obfuscate_TlvKhtsoOQ�� );
        $_obfuscate_e53ODz04JQ�� = new wfCache( );
        $_obfuscate_qZkmBg�� = $_obfuscate_e53ODz04JQ��->getFlow( );
        unset( $_obfuscate_e53ODz04JQ�� );
        $_obfuscate_6RYLWQ��['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "beRevoked" );
        $_obfuscate_6RYLWQ��['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQ��."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}";
        unset( $_obfuscate_hTew0boWJESy );
        $_obfuscate_td3BMkoeV0sT = implode( ",", $_obfuscate_td3BMkoeV0sT );
        $CNOA_DB->db_update( array(
            "status" => 4,
            "endtime" => $GLOBALS['CNOA_TIMESTAMP']
        ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        $CNOA_DB->db_update( array(
            "status" => 5,
            "etime" => $GLOBALS['CNOA_TIMESTAMP']
        ), $this->t_use_step, "WHERE `id` IN (".$_obfuscate_td3BMkoeV0sT.")" );
        $_obfuscate_eOytqZnoPmMkuTA2A�� = $CNOA_DB->db_select( "*", $this->t_use_step_child_flow, "WHERE `status` IN (0,1) AND `puFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        if ( $_obfuscate_eOytqZnoPmMkuTA2A�� )
        {
            $_obfuscate_xHZmyK5cg�� = array( );
            foreach ( $_obfuscate_eOytqZnoPmMkuTA2A�� as $_obfuscate_6A�� )
            {
                $this->deleteNotice( "both", $_obfuscate_6A��['id'], "child" );
            }
        }
        $CNOA_DB->db_delete( $this->t_use_step_child_flow, "WHERE `status` IN (0,1) AND `puFlowId`='".$_obfuscate_TlvKhtsoOQ��."'" );
        $CNOA_DB->db_update( array( "status" => 4 ), "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        $_obfuscate_etmAKmzA4Rvws_UM = array( );
        if ( $_obfuscate_qZkmBg��['noticeCancel'] == 1 )
        {
            $_obfuscate_etmAKmzA4Rvws_UM = $_obfuscate_Rho2Gip16nFI79JtdQ��;
        }
        else if ( $_obfuscate_qZkmBg��['noticeCancel'] == 2 )
        {
            $_obfuscate_etmAKmzA4Rvws_UM = $_obfuscate_Rho2Gip16nFI79JtdQ��;
            $_obfuscate_etmAKmzA4Rvws_UM[$_obfuscate_Y0NtxNfStZgTsQ��] = $_obfuscate_tC8MNsAzXA��;
        }
        else if ( $_obfuscate_qZkmBg��['noticeCancel'] == 3 )
        {
            $_obfuscate_etmAKmzA4Rvws_UM = $_obfuscate_wozTGT5K9nKFLM8�;
        }
        unset( $_obfuscate_wozTGT5K9nKFLM8� );
        unset( $_obfuscate_Rho2Gip16nFI79JtdQ�� );
        unset( $_obfuscate_Y0NtxNfStZgTsQ�� );
        unset( $_obfuscate_tC8MNsAzXA�� );
        foreach ( $_obfuscate_etmAKmzA4Rvws_UM as $_obfuscate_0W8� => $_obfuscate_7Ri3 )
        {
            $_obfuscate_6RYLWQ��['fromid'] = $_obfuscate_0W8�;
            $this->addNotice( "notice", $_obfuscate_7Ri3, $_obfuscate_6RYLWQ��, "cancel" );
        }
        unset( $_obfuscate_etmAKmzA4Rvws_UM );
    }

    protected function checkMgrPermit( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_7Ri3 = 0, $_obfuscate_iuzS = 0, $_obfuscate_y6jH = 0 )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        if ( empty( $_obfuscate_7Ri3 ) )
        {
            $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        }
        if ( empty( $_obfuscate_y6jH ) )
        {
            $_obfuscate_y6jH = $CNOA_SESSION->get( "SID" );
        }
        if ( empty( $_obfuscate_iuzS ) )
        {
            $_obfuscate_iuzS = $CNOA_SESSION->get( "DID" );
        }
        $_obfuscate_v1GprsIz = $CNOA_DB->db_getfield( "sortId", $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."' " );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "permitId", "type" ), $this->t_set_sort_permit, "WHERE `from` = 'g' AND `sortId` = '".$_obfuscate_v1GprsIz."' " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['type'] == "d" && $_obfuscate_iuzS == $_obfuscate_6A��['permitId'] )
            {
                return TRUE;
            }
            if ( $_obfuscate_6A��['type'] == "s" && $_obfuscate_y6jH == $_obfuscate_6A��['permitId'] )
            {
                return TRUE;
            }
            if ( !( $_obfuscate_6A��['type'] == "p" ) && !( $_obfuscate_7Ri3 == $_obfuscate_6A��['permitId'] ) )
            {
                continue;
            }
            return TRUE;
        }
        return FALSE;
    }

    protected function addNotice( $_obfuscate_LeS8hw�� = "both", $_obfuscate_7Ri3, $_obfuscate_6RYLWQ��, $_obfuscate_IO8hYI15, $_obfuscate_Ybai = 0 )
    {
        $_obfuscate_e7PLR79F = $this->noticeFormat[$_obfuscate_IO8hYI15][$_obfuscate_Ybai];
        global $CNOA_DB;
        $_obfuscate__eqrEQ�� = $_obfuscate_gIjdVQ�� = array( );
        ( $CNOA_DB, FALSE );
        $_obfuscate_RZYtO9Y� = new appAndWechatNotice( );
        $_obfuscate_kIVhqJk� = array(
            "from" => $_obfuscate_e7PLR79F['from'],
            "fromtype" => $_obfuscate_e7PLR79F['fromtype'],
            "href" => $_obfuscate_e7PLR79F['href'].$_obfuscate_6RYLWQ��['href']
        );
        if ( is_array( $_obfuscate_7Ri3 ) )
        {
            $_obfuscate_7Ri3 = array_unique( $_obfuscate_7Ri3 );
            foreach ( $_obfuscate_7Ri3 as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                if ( !empty( $_obfuscate_6A�� ) )
                {
                    $_obfuscate_kIVhqJk�['touid'] = $_obfuscate_6A��;
                    $_obfuscate_gIjdVQ��[] = $_obfuscate_RZYtO9Y�->getAppUrl( $_obfuscate_kIVhqJk�, FALSE );
                    $_obfuscate__eqrEQ��[] = $_obfuscate_6A��;
                }
            }
        }
        else if ( !empty( $_obfuscate_7Ri3 ) )
        {
            $_obfuscate_kIVhqJk�['touid'] = $_obfuscate_7Ri3;
            $_obfuscate_gIjdVQ�� = $_obfuscate_RZYtO9Y�->getAppUrl( $_obfuscate_kIVhqJk�, FALSE );
            $_obfuscate__eqrEQ�� = $_obfuscate_7Ri3;
        }
        if ( is_array( $_obfuscate_gIjdVQ�� ) )
        {
            foreach ( $_obfuscate_gIjdVQ�� as $_obfuscate_Vwty => $_obfuscate_VgKtFeg� )
            {
                JPush::push( $_obfuscate__eqrEQ��[$_obfuscate_Vwty], $_obfuscate_6RYLWQ��['content'], $_obfuscate_e7PLR79F['title'], $_obfuscate_VgKtFeg�, "process" );
            }
        }
        else
        {
            JPush::push( $_obfuscate__eqrEQ��, $_obfuscate_6RYLWQ��['content'], $_obfuscate_e7PLR79F['title'], $_obfuscate_gIjdVQ��, "process" );
        }
        switch ( $_obfuscate_LeS8hw�� )
        {
        case "notice" :
            $this->notice( $_obfuscate_7Ri3, $_obfuscate_6RYLWQ��, $_obfuscate_IO8hYI15, $_obfuscate_Ybai );
            break;
        case "todo" :
            $this->todo( $_obfuscate_7Ri3, $_obfuscate_6RYLWQ��, $_obfuscate_IO8hYI15, $_obfuscate_Ybai );
            break;
        case "both" :
            $this->notice( $_obfuscate_7Ri3, $_obfuscate_6RYLWQ��, $_obfuscate_IO8hYI15, $_obfuscate_Ybai );
            $this->todo( $_obfuscate_7Ri3, $_obfuscate_6RYLWQ��, $_obfuscate_IO8hYI15, $_obfuscate_Ybai );
        }
    }

    private function notice( $_obfuscate_7Ri3, $_obfuscate_6RYLWQ��, $_obfuscate_IO8hYI15, $_obfuscate_Ybai )
    {
        global $CNOA_DB;
        $_obfuscate_e7PLR79F = $this->noticeFormat[$_obfuscate_IO8hYI15][$_obfuscate_Ybai];
        $_obfuscate_vholQ�� = $_obfuscate_e7PLR79F['from'];
        if ( empty( $_obfuscate_6RYLWQ��['time'] ) )
        {
            $_obfuscate_5c1ea0lUBl8z4Q�� = $GLOBALS['CNOA_TIMESTAMP'];
        }
        else
        {
            $_obfuscate_5c1ea0lUBl8z4Q�� = $_obfuscate_6RYLWQ��['time'];
        }
        $_obfuscate_pVruBBT3a8o� = $_obfuscate_6RYLWQ��['fromid'];
        $_obfuscate_gIjdVQ�� = $_obfuscate_e7PLR79F['href'].$_obfuscate_6RYLWQ��['href'];
        $_obfuscate_obqvew�� = $_obfuscate_e7PLR79F['title'];
        $_obfuscate__WwKzYz1wA�� = addslashes( $_obfuscate_6RYLWQ��['content'] );
        $_obfuscate_3tiDsnM� = $_obfuscate_e7PLR79F['table'];
        $_obfuscate_9dRdazrpec� = $_obfuscate_e7PLR79F['fromtype'];
        if ( is_array( $_obfuscate_7Ri3 ) )
        {
            $_obfuscate_7Ri3 = array_unique( $_obfuscate_7Ri3 );
            foreach ( $_obfuscate_7Ri3 as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                if ( !empty( $_obfuscate_6A�� ) )
                {
                    notice::add( $_obfuscate_6A��, $_obfuscate_obqvew��, $_obfuscate__WwKzYz1wA��, $_obfuscate_gIjdVQ��, $_obfuscate_5c1ea0lUBl8z4Q��, $_obfuscate_vholQ��, $_obfuscate_pVruBBT3a8o�, 0, $_obfuscate_3tiDsnM�, $_obfuscate_9dRdazrpec� );
                }
            }
        }
        else if ( !empty( $_obfuscate_7Ri3 ) )
        {
            notice::add( $_obfuscate_7Ri3, $_obfuscate_obqvew��, $_obfuscate__WwKzYz1wA��, $_obfuscate_gIjdVQ��, $_obfuscate_5c1ea0lUBl8z4Q��, $_obfuscate_vholQ��, $_obfuscate_pVruBBT3a8o�, 0, $_obfuscate_3tiDsnM�, $_obfuscate_9dRdazrpec� );
        }
    }

    private function todo( $_obfuscate_7Ri3, $_obfuscate_6RYLWQ��, $_obfuscate_IO8hYI15, $_obfuscate_Ybai )
    {
        global $CNOA_DB;
        $_obfuscate_e7PLR79F = $this->noticeFormat[$_obfuscate_IO8hYI15][$_obfuscate_Ybai];
        $_obfuscate_0AITFw��['from'] = $_obfuscate_e7PLR79F['from'];
        if ( empty( $_obfuscate_6RYLWQ��['time'] ) )
        {
            $_obfuscate_0AITFw��['fromtime'] = $GLOBALS['CNOA_TIMESTAMP'];
        }
        else
        {
            $_obfuscate_0AITFw��['fromtime'] = $_obfuscate_6RYLWQ��['time'];
        }
        if ( !empty( $_obfuscate_e7PLR79F['href2'] ) )
        {
            $_obfuscate_0AITFw��['href2'] = $_obfuscate_e7PLR79F['href2'].$_obfuscate_6RYLWQ��['href'];
        }
        $_obfuscate_0AITFw��['fromid'] = $_obfuscate_6RYLWQ��['fromid'];
        $_obfuscate_0AITFw��['href'] = $_obfuscate_e7PLR79F['href'].$_obfuscate_6RYLWQ��['href'];
        $_obfuscate_0AITFw��['title'] = $_obfuscate_e7PLR79F['title'];
        $_obfuscate_0AITFw��['content'] = addslashes( $_obfuscate_6RYLWQ��['content'] );
        $_obfuscate_0AITFw��['operate'] = 0;
        $_obfuscate_0AITFw��['funname'] = $_obfuscate_e7PLR79F['funname'];
        $_obfuscate_0AITFw��['move'] = $_obfuscate_e7PLR79F['move'];
        $_obfuscate_0AITFw��['table'] = $_obfuscate_e7PLR79F['table'];
        $_obfuscate_0AITFw��['fromtype'] = $_obfuscate_e7PLR79F['fromtype'];
        if ( is_array( $_obfuscate_7Ri3 ) )
        {
            $_obfuscate_7Ri3 = array_unique( $_obfuscate_7Ri3 );
            foreach ( $_obfuscate_7Ri3 as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                if ( !empty( $_obfuscate_6A�� ) )
                {
                    $_obfuscate_0AITFw��['touid'] = $_obfuscate_6A��;
                    notice::add2( $_obfuscate_0AITFw�� );
                }
            }
        }
        else if ( !empty( $_obfuscate_7Ri3 ) )
        {
            $_obfuscate_0AITFw��['touid'] = $_obfuscate_7Ri3;
            notice::add2( $_obfuscate_0AITFw�� );
        }
    }

    public function doneAll( $_obfuscate_LeS8hw�� = "both", $_obfuscate_0W8�, $_obfuscate_IO8hYI15, $_obfuscate_Ybai = 0, $_obfuscate_NB2vnCJktMSZ = "" )
    {
        global $CNOA_DB;
        $_obfuscate_e7PLR79F = $this->noticeFormat[$_obfuscate_IO8hYI15][$_obfuscate_Ybai];
        $_obfuscate_3tiDsnM� = $_obfuscate_e7PLR79F['table'];
        $_obfuscate_OKFUMuZQtcKCuw�� = $_obfuscate_e7PLR79F['fromtype'];
        switch ( $_obfuscate_LeS8hw�� )
        {
        case "notice" :
            notice::donen( $_obfuscate_0W8�, $_obfuscate_3tiDsnM�, $_obfuscate_OKFUMuZQtcKCuw�� );
            break;
        case "todo" :
            notice::donet( $_obfuscate_0W8�, $_obfuscate_3tiDsnM�, $_obfuscate_OKFUMuZQtcKCuw�� );
            break;
        case "both" :
            notice::donen( $_obfuscate_0W8�, $_obfuscate_3tiDsnM�, $_obfuscate_OKFUMuZQtcKCuw�� );
            notice::donet( $_obfuscate_0W8�, $_obfuscate_3tiDsnM�, $_obfuscate_OKFUMuZQtcKCuw�� );
        }
        $CNOA_DB->db_delete( "system_notice", "WHERE `sourceid`=".$_obfuscate_0W8�." AND `fromtable` = '{$_obfuscate_3tiDsnM�}' AND `fromtype` = '{$_obfuscate_OKFUMuZQtcKCuw��}' " );
    }

    public function deleteAllNotice( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_QwT4KwrB2w��, $_obfuscate_F6OhcOnPhJV3hjhz = "" )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        foreach ( $_obfuscate_QwT4KwrB2w�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $this->deleteNotice( "both", $_obfuscate_6A��, "todo" );
            $this->deleteNotice( "both", $_obfuscate_6A��, "trust" );
            $this->deleteNotice( "both", $_obfuscate_6A��, "tuihui" );
            $this->deleteNotice( "both", $_obfuscate_6A��, "tuihui", 1 );
            $this->deleteNotice( "both", $_obfuscate_6A��, "tuihui", 2 );
            $this->deleteNotice( "both", $_obfuscate_6A��, "stop", 0 );
            $this->deleteNotice( "both", $_obfuscate_6A��, "stop", 1 );
            $this->deleteNotice( "both", $_obfuscate_6A��, "stop", 2 );
            $this->deleteNotice( "both", $_obfuscate_6A��, "callback" );
            $this->deleteNotice( "both", $_obfuscate_6A��, "cancel" );
            $this->deleteNotice( "both", $_obfuscate_6A��, "huiqian", 1 );
            $this->deleteNotice( "both", $_obfuscate_6A��, "stop" );
            $this->deleteNotice( "both", $_obfuscate_6A��, "stop", 1 );
            $this->deleteNotice( "both", $_obfuscate_6A��, "stop", 2 );
        }
        $this->deleteNotice( "both", $_obfuscate_TlvKhtsoOQ��, "done" );
        $this->deleteNotice( "both", $_obfuscate_TlvKhtsoOQ��, "fenfa" );
        $this->deleteNotice( "both", $_obfuscate_TlvKhtsoOQ��, "comment" );
        $this->deleteNotice( "both", $_obfuscate_TlvKhtsoOQ��, "cancel" );
        $this->deleteNotice( "both", $_obfuscate_TlvKhtsoOQ��, "warn" );
        if ( !empty( $_obfuscate_F6OhcOnPhJV3hjhz ) )
        {
            foreach ( $_obfuscate_F6OhcOnPhJV3hjhz as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $this->deleteNotice( "both", $_obfuscate_6A��, "huiqian" );
            }
        }
    }

    public function deleteNotice( $_obfuscate_LeS8hw�� = "both", $_obfuscate_0W8�, $_obfuscate_IO8hYI15, $_obfuscate_Ybai = 0 )
    {
        $_obfuscate_e7PLR79F = $this->noticeFormat[$_obfuscate_IO8hYI15][$_obfuscate_Ybai];
        $_obfuscate_3tiDsnM� = $_obfuscate_e7PLR79F['table'];
        $_obfuscate__5HBFZg� = $_obfuscate_e7PLR79F['fromtype'];
        switch ( $_obfuscate_LeS8hw�� )
        {
        case "notice" :
            notice::deletenotice( $_obfuscate_0W8�, 0, $_obfuscate_3tiDsnM�, $_obfuscate__5HBFZg� );
            break;
        case "todo" :
            notice::deletenotice( 0, $_obfuscate_0W8�, $_obfuscate_3tiDsnM�, $_obfuscate__5HBFZg� );
            break;
        case "both" :
            notice::deletenotice( $_obfuscate_0W8�, $_obfuscate_0W8�, $_obfuscate_3tiDsnM�, $_obfuscate__5HBFZg� );
        }
    }

    protected function __getOdata( $_obfuscate_p5ZWxr4� )
    {
        return json_decode( str_replace( "'", "\"", $_obfuscate_p5ZWxr4� ), TRUE );
    }

    protected function __formatDatetime( $_obfuscate_e7PLR79F, $_obfuscate_c6UELg�� = "" )
    {
        if ( empty( $_obfuscate_c6UELg�� ) )
        {
            $_obfuscate_c6UELg�� = $GLOBALS['CNOA_TIMESTAMP'];
        }
        switch ( $_obfuscate_e7PLR79F )
        {
        case "100" :
            $_obfuscate_6RYLWQ�� = date( "Y-m-d", $_obfuscate_c6UELg�� );
            return $_obfuscate_6RYLWQ��;
        case "110" :
            $_obfuscate_6RYLWQ�� = date( "Y年m月d日", $_obfuscate_c6UELg�� );
            return $_obfuscate_6RYLWQ��;
        case "120" :
            $_obfuscate_6RYLWQ�� = date( "Ymd", $_obfuscate_c6UELg�� );
            return $_obfuscate_6RYLWQ��;
        case "130" :
            $_obfuscate_6RYLWQ�� = date( "Y/m/d", $_obfuscate_c6UELg�� );
            return $_obfuscate_6RYLWQ��;
        case "140" :
            $_obfuscate_6RYLWQ�� = date( "Y.m.d", $_obfuscate_c6UELg�� );
            return $_obfuscate_6RYLWQ��;
        case "150" :
            $_obfuscate_6RYLWQ�� = date( "Y年m月", $_obfuscate_c6UELg�� );
            return $_obfuscate_6RYLWQ��;
        case "160" :
            $_obfuscate_6RYLWQ�� = date( "m月d日", $_obfuscate_c6UELg�� );
            return $_obfuscate_6RYLWQ��;
        case "190" :
            $_obfuscate_6RYLWQ�� = date( "Y-m-d H:i", $_obfuscate_c6UELg�� );
            return $_obfuscate_6RYLWQ��;
        case "200" :
            $_obfuscate_6RYLWQ�� = date( "y年m月 H时i分", $_obfuscate_c6UELg�� );
            return $_obfuscate_6RYLWQ��;
        case "300" :
            $_obfuscate_6RYLWQ�� = date( "Y年m月d日 H时i分", $_obfuscate_c6UELg�� );
            return $_obfuscate_6RYLWQ��;
        case "400" :
            $_obfuscate_6RYLWQ�� = date( "Y年m月d日 H时i分s秒", $_obfuscate_c6UELg�� );
        }
        return $_obfuscate_6RYLWQ��;
    }

    protected function __formatTime( $_obfuscate_e7PLR79F, $_obfuscate_c6UELg�� = "" )
    {
        if ( empty( $_obfuscate_c6UELg�� ) )
        {
            $_obfuscate_c6UELg�� = $GLOBALS['CNOA_TIMESTAMP'];
        }
        switch ( $_obfuscate_e7PLR79F )
        {
        case "100" :
            $_obfuscate_6RYLWQ�� = date( "H:i", $_obfuscate_c6UELg�� );
            return $_obfuscate_6RYLWQ��;
        case "200" :
            $_obfuscate_6RYLWQ�� = date( "h:i A", $_obfuscate_c6UELg�� );
            return $_obfuscate_6RYLWQ��;
        case "300" :
            $_obfuscate_6RYLWQ�� = date( "h:i", $_obfuscate_c6UELg�� );
            if ( date( "A", $_obfuscate_c6UELg�� ) == "AM" )
            {
                $_obfuscate_6RYLWQ�� .= "上午";
                return $_obfuscate_6RYLWQ��;
            }
            $_obfuscate_6RYLWQ�� .= "下午";
        }
        return $_obfuscate_6RYLWQ��;
    }

    protected function __getFlowType( $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        $_obfuscate_7qDAYo85aGA� = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $_obfuscate_HLysAWJVRE� = array( );
        if ( !empty( $_obfuscate_7qDAYo85aGA� ) )
        {
            $_obfuscate_HLysAWJVRE� = array(
                "flowType" => $_obfuscate_7qDAYo85aGA�['flowType'],
                "tplSort" => $_obfuscate_7qDAYo85aGA�['tplSort']
            );
        }
        return $_obfuscate_HLysAWJVRE�;
    }

    protected function api_loadTemplateFile( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_XkuTFqZ6Tmk�, $_obfuscate_pEvU7Kz2Yw��, $_obfuscate_jTbXTguM6pC9CA�� )
    {
        global $CNOA_DB;
        if ( $_obfuscate_XkuTFqZ6Tmk� == 0 )
        {
            $_obfuscate_o6LA2yPirJIreFA� = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/doc.history.0.php" );
            $_obfuscate_U9NJHZRq6Jr7T_A� = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/xls.history.0.php" );
        }
        else
        {
            $_obfuscate_o6LA2yPirJIreFA� = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQ��}/doc.history.0.php" );
            $_obfuscate_U9NJHZRq6Jr7T_A� = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQ��}/xls.history.0.php" );
        }
        if ( $_obfuscate_jTbXTguM6pC9CA�� == "add" )
        {
            if ( $_obfuscate_pEvU7Kz2Yw�� == 1 || $_obfuscate_pEvU7Kz2Yw�� == 3 )
            {
                if ( file_exists( $_obfuscate_o6LA2yPirJIreFA� ) )
                {
                    $_obfuscate_6hS1Rw�� = @file_get_contents( $_obfuscate_o6LA2yPirJIreFA� );
                }
                else
                {
                    mkdirs( dirname( $_obfuscate_o6LA2yPirJIreFA� ) );
                    $_obfuscate_6hS1Rw�� = @file_get_contents( CNOA_PATH."/resources/empty.doc" );
                }
            }
            else if ( file_exists( $_obfuscate_U9NJHZRq6Jr7T_A� ) )
            {
                $_obfuscate_6hS1Rw�� = @file_get_contents( $_obfuscate_U9NJHZRq6Jr7T_A� );
            }
            else
            {
                mkdirs( dirname( $_obfuscate_U9NJHZRq6Jr7T_A� ) );
                $_obfuscate_6hS1Rw�� = @file_get_contents( CNOA_PATH."/resources/empty.xls" );
            }
        }
        else
        {
            $_obfuscate_Xz9QCGd6R6zz76K0kKbP = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQ��}/doc.history.0.php" );
            $_obfuscate_UTO1M21cc2PuEITQ7av = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQ��}/xls.history.0.php" );
            if ( $_obfuscate_pEvU7Kz2Yw�� == 1 || $_obfuscate_pEvU7Kz2Yw�� == 3 )
            {
                if ( file_exists( $_obfuscate_Xz9QCGd6R6zz76K0kKbP ) )
                {
                    $_obfuscate_6hS1Rw�� = @file_get_contents( $_obfuscate_Xz9QCGd6R6zz76K0kKbP );
                }
            }
            else if ( file_exists( $_obfuscate_UTO1M21cc2PuEITQ7av ) )
            {
                $_obfuscate_6hS1Rw�� = @file_get_contents( $_obfuscate_UTO1M21cc2PuEITQ7av );
            }
        }
        echo $_obfuscate_6hS1Rw��;
        exit( );
    }

    protected function api_saveTemplateData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_LeS8hw�� )
    {
        global $CNOA_DB;
        $_obfuscate_zfubNC9lKJs� = strtolower( strrchr( $_FILES['msOffice']['name'], "." ) );
        if ( !in_array( strtolower( $_obfuscate_zfubNC9lKJs� ), array( ".doc", ".docx", ".xls", ".xlsx" ) ) )
        {
            echo "0";
            exit( );
        }
        if ( $_obfuscate_LeS8hw�� == "1" || $_obfuscate_LeS8hw�� == "3" )
        {
            $_obfuscate_7tmDAr7acvgDxw�� = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQ��}/doc.history.0.php" );
            mkdirs( dirname( $_obfuscate_7tmDAr7acvgDxw�� ) );
            if ( !cnoa_move_uploaded_file( $_FILES['msOffice']['tmp_name'], $_obfuscate_7tmDAr7acvgDxw�� ) )
            {
                echo "0";
                exit( );
            }
        }
        else
        {
            $_obfuscate_7tmDAr7acvgDxw�� = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQ��}/xls.history.0.php" );
            mkdirs( dirname( $_obfuscate_7tmDAr7acvgDxw�� ) );
            if ( !cnoa_move_uploaded_file( $_FILES['msOffice']['tmp_name'], $_obfuscate_7tmDAr7acvgDxw�� ) )
            {
                echo "0";
                exit( );
            }
        }
        echo "1";
        exit( );
    }

    protected function getAllStepByUFlowId( $_obfuscate_TlvKhtsoOQ��, $_obfuscate_eLlzdw�� = "all" )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQ��."'" );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_5NhzjnJq_f8� = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6A�� )
        {
            if ( isset( $_obfuscate_5NhzjnJq_f8�[$_obfuscate_6A��['uStepId']] ) )
            {
                if ( !( $_obfuscate_5NhzjnJq_f8�[$_obfuscate_6A��['uStepId']]['id'] < $_obfuscate_6A��['id'] ) && !( $_obfuscate_5NhzjnJq_f8�[$_obfuscate_6A��['uStepId']]['dealUid'] == 0 ) )
                {
                    $_obfuscate_5NhzjnJq_f8�[$_obfuscate_6A��['uStepId']] = $_obfuscate_6A��;
                }
            }
            else
            {
                $_obfuscate_5NhzjnJq_f8�[$_obfuscate_6A��['uStepId']] = $_obfuscate_6A��;
            }
        }
        unset( $_obfuscate_mPAjEGLn );
        $_obfuscate_HYI4w55m58H2WjCs['stepInfo'] = $_obfuscate_5NhzjnJq_f8�;
        return $_obfuscate_HYI4w55m58H2WjCs;
    }

    protected function _saveFormFieldInfo( $_obfuscate_uGltphXQjCRWoA�� = "", $_obfuscate_0cocFTVhmhKt8lw� = "", $_obfuscate_TlvKhtsoOQ�� = 0 )
    {
        global $CNOA_DB;
        $_obfuscate_JQJwE4USnB0� = array( );
        $_obfuscate_V7H2J5ahg�� = array( );
        $_obfuscate_u_DK_o5AB8le = array( );
        $_obfuscate_BqBV6WSz3wel0ZDw = array( );
        $_obfuscate_FYo_0_BVp9xjgDs� = array( );
        $_obfuscate_piwqe2DnH9mIPU0P = array( );
        $_obfuscate_vddvYsrvcSVy = array( );
        $_obfuscate_SYIrzK6Qi3Pgg�� = array( );
        ( );
        $_obfuscate_2gg� = new fs( );
        $_obfuscate_St0kagJ6AtprGs� = getpar( $_POST, "filesUpload", array( ) );
        if ( $_obfuscate_St0kagJ6AtprGs� )
        {
            foreach ( $_obfuscate_St0kagJ6AtprGs� as $_obfuscate_Vwty => $_obfuscate_VgKtFeg� )
            {
                $_obfuscate_vddvYsrvcSVy["wf_field_".$_obfuscate_Vwty] = $_obfuscate_VgKtFeg�;
            }
            foreach ( $_obfuscate_vddvYsrvcSVy as $_obfuscate_3QY� => $_obfuscate_EGU� )
            {
                $_obfuscate_SYIrzK6Qi3Pgg��[$_obfuscate_3QY�] = json_encode( $_obfuscate_2gg�->add( $_obfuscate_EGU�, 1 ) );
            }
        }
        foreach ( $_obfuscate_SYIrzK6Qi3Pgg�� as $_obfuscate_nJg� => $_obfuscate_NZM� )
        {
            if ( ereg( "wf_field_", $_obfuscate_nJg� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_field_", "", $_obfuscate_nJg� );
                if ( !is_array( $_obfuscate_uGltphXQjCRWoA�� ) )
                {
                    $_obfuscate_JQJwE4USnB0�['normal'][$_obfuscate_gfGsQGKrGg��] = $_obfuscate_NZM�;
                }
                else if ( in_array( $_obfuscate_gfGsQGKrGg��, $_obfuscate_uGltphXQjCRWoA�� ) )
                {
                    $_obfuscate_JQJwE4USnB0�['normal'][$_obfuscate_gfGsQGKrGg��] = $_obfuscate_NZM�;
                }
            }
        }
        if ( $_obfuscate_JQJwE4USnB0� && $_obfuscate_TlvKhtsoOQ�� )
        {
            foreach ( $_obfuscate_JQJwE4USnB0� as $_obfuscate_Vwty => $_obfuscate_VgKtFeg� )
            {
                foreach ( $_obfuscate_VgKtFeg� as $_obfuscate_5w�� => $_obfuscate_6A�� )
                {
                    $_obfuscate_MAduV4GmcAN9 = $CNOA_DB->db_getfield( "T_".$_obfuscate_5w��, "z_wf_t_".$this->flowId, "WHERE uFlowId=".$_obfuscate_TlvKhtsoOQ�� );
                    if ( $_obfuscate_MAduV4GmcAN9 )
                    {
                        $_obfuscate_MAduV4GmcAN9 = substr( $_obfuscate_MAduV4GmcAN9, 1, -1 );
                        $_obfuscate_gQ�� = substr( $_obfuscate_6A��, 1, -1 );
                        $_obfuscate_Ihkaz3Fr28zrS4� = "[".$_obfuscate_MAduV4GmcAN9.",".$_obfuscate_gQ��."]";
                        $_obfuscate_JQJwE4USnB0�[$_obfuscate_Vwty][$_obfuscate_5w��] = $_obfuscate_Ihkaz3Fr28zrS4�;
                    }
                }
            }
        }
        foreach ( $GLOBALS['_POST'] as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( ereg( "wf_field_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_field_", "", $_obfuscate_5w�� );
                if ( !is_array( $_obfuscate_uGltphXQjCRWoA�� ) )
                {
                    $_obfuscate_JQJwE4USnB0�['normal'][$_obfuscate_gfGsQGKrGg��] = $_obfuscate_6A��;
                }
                else if ( in_array( $_obfuscate_gfGsQGKrGg��, $_obfuscate_uGltphXQjCRWoA�� ) )
                {
                    $_obfuscate_JQJwE4USnB0�['normal'][$_obfuscate_gfGsQGKrGg��] = $_obfuscate_6A��;
                }
            }
            else if ( ereg( "wf_fieldJ_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_fieldJ_", "", $_obfuscate_5w�� );
                $_obfuscate_JQJwE4USnB0�['normal'][$_obfuscate_gfGsQGKrGg��] = $_obfuscate_6A��;
            }
            else if ( ereg( "wf_fieldC_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_fieldC_", "", $_obfuscate_5w�� );
                $_obfuscate_SeV31Q�� = explode( "_", $_obfuscate_gfGsQGKrGg�� );
                $_obfuscate_V7H2J5ahg��[$_obfuscate_SeV31Q��[0]][$_obfuscate_SeV31Q��[1]] = $_obfuscate_6A��;
            }
            else if ( ereg( "wf_detail_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_detail_", "", $_obfuscate_5w�� );
                $_obfuscate_SeV31Q�� = explode( "_", $_obfuscate_gfGsQGKrGg�� );
                if ( !is_array( $_obfuscate_0cocFTVhmhKt8lw� ) )
                {
                    $_obfuscate_JQJwE4USnB0�['detail'][$_obfuscate_SeV31Q��[0]][$_obfuscate_SeV31Q��[1]] = $_obfuscate_6A��;
                }
                else if ( in_array( $_obfuscate_SeV31Q��[1], $_obfuscate_0cocFTVhmhKt8lw� ) )
                {
                    $_obfuscate_JQJwE4USnB0�['detail'][$_obfuscate_SeV31Q��[0]][$_obfuscate_SeV31Q��[1]] = $_obfuscate_6A��;
                }
            }
            else if ( ereg( "wf_detailJ_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_detailJ_", "", $_obfuscate_5w�� );
                $_obfuscate_SeV31Q�� = explode( "_", $_obfuscate_gfGsQGKrGg�� );
                $_obfuscate_JQJwE4USnB0�['detail'][$_obfuscate_SeV31Q��[0]][$_obfuscate_SeV31Q��[1]] = $_obfuscate_6A��;
            }
            else if ( ereg( "wf_detailC_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_detailC_", "", $_obfuscate_5w�� );
                $_obfuscate_SeV31Q�� = explode( "_", $_obfuscate_gfGsQGKrGg�� );
                $_obfuscate_u_DK_o5AB8le[$_obfuscate_SeV31Q��[0]][$_obfuscate_SeV31Q��[1]][] = $_obfuscate_6A��;
            }
            else if ( ereg( "wf_detailbid_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_detailbid_", "", $_obfuscate_5w�� );
                $_obfuscate_SeV31Q�� = explode( "_", $_obfuscate_gfGsQGKrGg�� );
                $_obfuscate_BqBV6WSz3wel0ZDw[intval( $_obfuscate_SeV31Q��[1] )][intval( $_obfuscate_SeV31Q��[0] )] = $_obfuscate_6A��;
            }
            else if ( ereg( "wf_detailmax_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_detailmax_", "", $_obfuscate_5w�� );
                $_obfuscate_SeV31Q�� = explode( "_", $_obfuscate_gfGsQGKrGg�� );
                $_obfuscate_piwqe2DnH9mIPU0P[$_obfuscate_SeV31Q��[1]."_".$_obfuscate_SeV31Q��[0]] = $_obfuscate_6A��;
            }
            else if ( ereg( "detailid_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_FYo_0_BVp9xjgDs�[] = intval( str_replace( "detailid_", "", $_obfuscate_5w�� ) );
            }
            else if ( ereg( "wf_attach_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_IuoXR2yOaxkRDw��[] = intval( str_replace( "wf_attach_", "", $_obfuscate_5w�� ) );
            }
            else if ( ereg( "wf_fieldS_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_fieldS_", "", $_obfuscate_5w�� );
                if ( !empty( $_obfuscate_6A�� ) )
                {
                    $_obfuscate_6A�� = explode( ";", $_obfuscate_6A�� );
                    $_obfuscate_kCxvBLni6Q�� = array( );
                    foreach ( $_obfuscate_6A�� as $_obfuscate_EGU� )
                    {
                        if ( !empty( $_obfuscate_EGU� ) )
                        {
                            if ( stripos( $_obfuscate_EGU�, "seal" ) )
                            {
                                $_obfuscate_EGU� = "seal:".$_obfuscate_EGU�;
                            }
                            list( $_obfuscate_Vwty, $_obfuscate_TAxu ) = explode( ":", $_obfuscate_EGU� );
                            $_obfuscate_kCxvBLni6Q��[$_obfuscate_Vwty] = $_obfuscate_TAxu;
                        }
                    }
                    $_obfuscate_JQJwE4USnB0�['normal'][$_obfuscate_gfGsQGKrGg��] = json_encode( $_obfuscate_kCxvBLni6Q�� );
                }
            }
            else if ( ereg( "wf_fieldpic_", $_obfuscate_5w�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = str_replace( "wf_fieldpic_", "", $_obfuscate_5w�� );
                if ( ereg( "editpic", $_obfuscate_6A�� ) )
                {
                    $_obfuscate_JTe7jJ4eGW8� = str_replace( "{$GLOBALS['URL_FILE']}/editpic/", "", $_obfuscate_6A�� );
                    $_obfuscate_GsJ20flAQ�� = $GLOBALS['URL_FILE']."/common/wf/form/".getpar( $_POST, "flowId", 0 );
                    @mkdirs( CNOA_PATH."/".$_obfuscate_GsJ20flAQ�� );
                    @rename( CNOA_PATH."/".$_obfuscate_6A��, CNOA_PATH."/".$_obfuscate_GsJ20flAQ��."/".$_obfuscate_JTe7jJ4eGW8� );
                    $_obfuscate_JQJwE4USnB0�['normal'][$_obfuscate_gfGsQGKrGg��] = $_obfuscate_GsJ20flAQ��."/".$_obfuscate_JTe7jJ4eGW8�;
                }
                else
                {
                    $_obfuscate_JQJwE4USnB0�['normal'][$_obfuscate_gfGsQGKrGg��] = $_obfuscate_6A��;
                }
            }
            else if ( preg_match( "/^wf_budgetDept_(\\d+)\$/", $_obfuscate_5w��, $_obfuscate_8UmnTppRcA�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = $_obfuscate_8UmnTppRcA��[1];
                $_obfuscate_6A�� = intval( $_obfuscate_6A�� );
                $_obfuscate_3y0Y = "INSERT INTO ".tname( "budget_tmp_tabel" )."(`uFlowId`, `fieldId`, `deptId`) ".( "VALUES(".$_obfuscate_TlvKhtsoOQ��.", {$_obfuscate_gfGsQGKrGg��}, {$_obfuscate_6A��}) " ).( "ON DUPLICATE KEY UPDATE `deptId`=".$_obfuscate_6A�� );
                $CNOA_DB->query( $_obfuscate_3y0Y );
            }
            else if ( preg_match( "/^wf_budgetProj_(\\d+)\$/", $_obfuscate_5w��, $_obfuscate_8UmnTppRcA�� ) )
            {
                $_obfuscate_gfGsQGKrGg�� = $_obfuscate_8UmnTppRcA��[1];
                $_obfuscate_6A�� = intval( $_obfuscate_6A�� );
                $_obfuscate_3y0Y = "INSERT INTO ".tname( "budget_tmp_tabel_proj" )."(`uFlowId`, `fieldId`, `projId`) ".( "VALUES(".$_obfuscate_TlvKhtsoOQ��.", {$_obfuscate_gfGsQGKrGg��}, {$_obfuscate_6A��}) " ).( "ON DUPLICATE KEY UPDATE `projId`=".$_obfuscate_6A�� );
                $CNOA_DB->query( $_obfuscate_3y0Y );
            }
        }
        foreach ( $_obfuscate_V7H2J5ahg�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_JQJwE4USnB0�['normal'][$_obfuscate_5w��] = json_encode( $_obfuscate_6A�� );
        }
        foreach ( $_obfuscate_u_DK_o5AB8le as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            foreach ( $_obfuscate_6A�� as $_obfuscate_ClA� => $_obfuscate_bRQ� )
            {
                $_obfuscate_JQJwE4USnB0�['detail'][$_obfuscate_5w��][$_obfuscate_ClA�] = json_encode( $_obfuscate_bRQ� );
            }
        }
        $_obfuscate_dGoPOiQ2Iw5a = array( );
        if ( !empty( $_obfuscate_FYo_0_BVp9xjgDs� ) )
        {
            $_obfuscate_7Hp0w_lfFt4� = $CNOA_DB->db_select( array( "id", "fid" ), $this->t_set_field_detail, "WHERE `fid` IN (".implode( ",", $_obfuscate_FYo_0_BVp9xjgDs� ).") " );
            if ( !is_array( $_obfuscate_7Hp0w_lfFt4� ) )
            {
                $_obfuscate_7Hp0w_lfFt4� = array( );
            }
            foreach ( $_obfuscate_7Hp0w_lfFt4� as $_obfuscate_5w�� => $_obfuscate_6A�� )
            {
                $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_6A��['id']] = $_obfuscate_6A��['fid'];
            }
        }
        return array(
            $_obfuscate_IuoXR2yOaxkRDw��,
            $_obfuscate_JQJwE4USnB0�,
            $_obfuscate_dGoPOiQ2Iw5a,
            $_obfuscate_BqBV6WSz3wel0ZDw,
            $_obfuscate_piwqe2DnH9mIPU0P
        );
    }

    protected function _saveSms( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        if ( !is_array( $_POST['sms'] ) )
        {
            return;
        }
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_6RYLWQ��['uFlowId'] = $this->uFlowId;
        $_obfuscate_6RYLWQ��['uStepId'] = $this->stepId;
        $_obfuscate_6RYLWQ��['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_6RYLWQ��['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_6RYLWQ��['toMobile'] = "";
        $_obfuscate_6RYLWQ��['toName'] = "";
        $_obfuscate_6RYLWQ��['content'] = "";
        ( );
        $_obfuscate_wVdkSQao12a = new sms( );
        foreach ( $GLOBALS['_POST']['sms'] as $_obfuscate_6A�� )
        {
            $_obfuscate_a9lP = json_decode( $_obfuscate_6A��, TRUE );
            $_obfuscate_6RYLWQ��['toMobile'] = addslashes( json_encode( $_obfuscate_a9lP['mobiles'] ) );
            $_obfuscate_6RYLWQ��['toName'] = addslashes( json_encode( $_obfuscate_a9lP['names'] ) );
            $_obfuscate_6RYLWQ��['content'] = $_obfuscate_a9lP['content'];
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQ��, $this->t_use_sms );
            foreach ( $_obfuscate_a9lP['mobiles'] as $_obfuscate_ty0� => $_obfuscate_snM� )
            {
                $_obfuscate_wVdkSQao12a->sendByMobile( $_obfuscate_snM�, $_obfuscate_a9lP['content'], $GLOBALS['CNOA_TIMESTAMP'], $_obfuscate_a9lP['names'][$_obfuscate_ty0�], 0, "flow" );
            }
        }
    }

    protected function getFlowListInSort( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_v1GprsIz = intval( getpar( $_POST, "sortId", 0 ) );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "flowId", "name" ), $this->t_set_flow, "WHERE `sortId`='".$_obfuscate_v1GprsIz."' AND `status`=1" );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        ( );
        $_obfuscate_NlQ� = new dataStore( );
        $_obfuscate_NlQ�->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_NlQ�->makeJsonData( );
        exit( );
    }

    protected function getFlowFields( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = ( integer )getpar( $_POST, "flowId" );
        $_obfuscate_zKrG7sLw5AI� = array(
            self::FIELD_DATASOURCE,
            self::FIELD_DETAIL_TABLE,
            self::FIELD_SIGNATURE,
            self::FIELD_CHECKBOX
        );
        $_obfuscate_tjILu7ZH = $CNOA_DB->db_select( array( "id", "name", "otype", "odata" ), $this->t_set_field, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND otype NOT IN ('".implode( "','", $_obfuscate_zKrG7sLw5AI� )."')" );
        $_obfuscate_Tc82k3jOQ�� = array( );
        if ( is_array( $_obfuscate_tjILu7ZH ) )
        {
            foreach ( $_obfuscate_tjILu7ZH as $_obfuscate_6Q�� )
            {
                $_obfuscate_p5ZWxr4� = json_decode( str_replace( "'", "\"", $_obfuscate_6Q��['odata'] ), TRUE );
                if ( in_array( $_obfuscate_p5ZWxr4�['dataType'], array( "photo_upload", "huiqian" ) ) )
                {
                    unset( $_obfuscate_6Q�� );
                }
                else
                {
                    $_obfuscate_6Q��['dataType'] = $_obfuscate_p5ZWxr4�['dataType'];
                    $_obfuscate_Tc82k3jOQ��[] = $_obfuscate_6Q��;
                }
            }
        }
        return $_obfuscate_Tc82k3jOQ��;
    }

    protected function getAutoNextWfInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_F4_qwZVhBmemw�� = $CNOA_DB->db_getfield( "wfAutoNext", "main_user", "WHERE `uid`='".$_obfuscate_7Ri3."'" );
        if ( $_obfuscate_F4_qwZVhBmemw�� == "1" )
        {
            $_obfuscate_7qDAYo85aGA� = $CNOA_DB->get_one( "SELECT s.uFlowId, s.uStepId, u.flowId,ss.flowType,ss.tplSort FROM `cnoa_wf_u_step` AS s LEFT JOIN `cnoa_wf_u_flow` AS u ON s.uFlowId = u.uFlowId LEFT JOIN `cnoa_wf_s_flow` AS ss ON u.flowId = ss.flowId WHERE s.uid = 1 AND s.status = 1 ORDER BY s.id DESC LIMIT 1" );
            return $_obfuscate_7qDAYo85aGA�;
        }
        return FALSE;
    }

    public function getPageSet( $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        $_obfuscate_36fJ0cHnXA�� = $CNOA_DB->db_getfield( "pageset", $this->t_set_flow, "WHERE `flowId` = ".$_obfuscate_F4AbnVRh." " );
        if ( empty( $_obfuscate_36fJ0cHnXA�� ) || $_obfuscate_36fJ0cHnXA�� == "null" )
        {
            $_obfuscate_36fJ0cHnXA�� = array( "pageSize" => "a4page", "pageDir" => "lengthways", "pageUp" => "10", "pageDown" => "10", "pageLeft" => "10", "pageRight" => "10" );
            $_obfuscate_36fJ0cHnXA�� = json_encode( $_obfuscate_36fJ0cHnXA�� );
        }
        return $_obfuscate_36fJ0cHnXA��;
    }

    protected function getUFlowIdsBySearch( $_obfuscate_F4AbnVRh, $_obfuscate_tjILu7ZH )
    {
        global $CNOA_DB;
        $_obfuscate_3tiDsnM� = self::TABLE_PRE_DATA.$_obfuscate_F4AbnVRh;
        $_obfuscate_Tc82k3jOQ�� = $_obfuscate_IRFhnYw� = array( );
        $_obfuscate_6RYLWQ�� = $this->getFlowFields( );
        foreach ( $_obfuscate_6RYLWQ�� as $_obfuscate_6Q�� )
        {
            $_obfuscate_Tc82k3jOQ��[$_obfuscate_6Q��['id']] = $_obfuscate_6Q��;
        }
        $_obfuscate_hjcEgBhYOi9kjI� = $CNOA_DB->db_getFieldsName( $_obfuscate_3tiDsnM� );
        $_obfuscate_JJKEg0IiY8E� = array( "creatername", "createrdept", "createrjob", "createrstation", "loginname", "logindept", "loginjob", "loginstation" );
        foreach ( $_obfuscate_tjILu7ZH as $_obfuscate_Vwty => $_obfuscate_TAxu )
        {
            if ( !in_array( $_obfuscate_Vwty, $_obfuscate_hjcEgBhYOi9kjI� ) && !( $_obfuscate_TAxu != "" ) )
            {
                $_obfuscate_Ce9h = str_replace( "T_", "", $_obfuscate_Vwty );
                switch ( $_obfuscate_Tc82k3jOQ��[$_obfuscate_Ce9h]['otype'] )
                {
                case self::FIELD_CHOICE :
                    switch ( $_obfuscate_Tc82k3jOQ��[$_obfuscate_Ce9h]['dataType'] )
                    {
                    case "jobs_sel" :
                    case "users_sel" :
                    case "depts_sel" :
                    case "stations_sel" :
                        $_obfuscate_TAxu = explode( ",", $_obfuscate_TAxu );
                        foreach ( $_obfuscate_TAxu as $_obfuscate_6A�� )
                        {
                            $_obfuscate_IRFhnYw�[] = "FIND_IN_SET('".$_obfuscate_6A��."' ,`{$_obfuscate_Vwty}`)";
                        }
                        break;
                    default :
                        $_obfuscate_IRFhnYw�[] = "{$_obfuscate_Vwty} = '{$_obfuscate_TAxu}'";
                    }
                    break;
                default :
                    if ( in_array( $_obfuscate_Tc82k3jOQ��[$_obfuscate_Ce9h]['dataType'], $_obfuscate_JJKEg0IiY8E� ) )
                    {
                        $_obfuscate_IRFhnYw�[] = "{$_obfuscate_Vwty} = '{$_obfuscate_TAxu}'";
                    }
                    else
                    {
                        $_obfuscate_IRFhnYw�[] = "{$_obfuscate_Vwty} LIKE '%{$_obfuscate_TAxu}%'";
                    }
                }
            }
        }
        return implode( " AND ", $_obfuscate_IRFhnYw� );
    }

    protected function _fillAutoFenFaUsers( $_obfuscate_HYuzLzerU95Vhg��, $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt, $_obfuscate_7rU5WM0� )
    {
        global $CNOA_DB;
        $_obfuscate__eqrEQ�� = array( );
        if ( !empty( $_obfuscate_7rU5WM0� ) )
        {
            foreach ( $_obfuscate_7rU5WM0� as $_obfuscate_VgKtFeg� )
            {
                $_obfuscate__eqrEQ��[] = $_obfuscate_VgKtFeg�[0];
            }
            $_obfuscate_6b8lIO4y = $_obfuscate_HYuzLzerU95Vhg��;
            $_obfuscate__eqrEQ�� = implode( ",", $_obfuscate__eqrEQ�� );
            $_obfuscate_3y0Y = "INSERT INTO ".tname( $this->t_set_autoFenfa )." (`flowId`, `stepId`, `status`, `uids`)".( " VALUES ('".$_obfuscate_F4AbnVRh."', '{$_obfuscate_0Ul8BBkt}', '{$_obfuscate_6b8lIO4y}', '{$_obfuscate__eqrEQ��}')" )." ON DUPLICATE KEY UPDATE `status`=VALUES(`status`), `uids`=VALUES(`uids`)";
            $CNOA_DB->query( $_obfuscate_3y0Y );
        }
        else
        {
            $_obfuscate_IRFhnYw� = "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `stepId`='{$_obfuscate_0Ul8BBkt}' ";
            $CNOA_DB->db_delete( $this->t_set_autoFenfa, $_obfuscate_IRFhnYw� );
        }
    }

    protected function api_autoFenfa( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_0Ul8BBkt )
    {
        app::loadapp( "wf", "flowUseTodo" )->api_addFenfa( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_0Ul8BBkt );
    }

    public function api_getFormatFlowNumber( $_obfuscate_IMeby9iyHIg�, $_obfuscate_neM4JBUJlmg�, $_obfuscate_zPP1hxIu42teMw��, $_obfuscate_F4AbnVRh )
    {
        return $this->api_formatFlowNumber( $_obfuscate_IMeby9iyHIg�, $_obfuscate_neM4JBUJlmg�, $_obfuscate_zPP1hxIu42teMw��, $_obfuscate_F4AbnVRh );
    }

    public function getAttachList( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_WPvkSFEMg�� )
    {
        global $CNOA_DB;
        if ( empty( $_obfuscate_TlvKhtsoOQ�� ) )
        {
            return $_obfuscate_WPvkSFEMg��['attach'];
        }
        $_obfuscate_1_pbjTIdLU49 = $CNOA_DB->db_select( array( "id" ), $this->t_set_field, "WHERE flowId=".$_obfuscate_F4AbnVRh." AND otype='attach'" );
        if ( !is_array( $_obfuscate_1_pbjTIdLU49 ) )
        {
            $_obfuscate_1_pbjTIdLU49 = array( );
        }
        if ( $_obfuscate_1_pbjTIdLU49 )
        {
            foreach ( $_obfuscate_1_pbjTIdLU49 as $_obfuscate_Vwty => $_obfuscate_VgKtFeg� )
            {
                $m = $CNOA_DB->db_getfield( "T_".$_obfuscate_VgKtFeg�['id'], "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE uFlowId=".$_obfuscate_TlvKhtsoOQ�� );
                if ( $m )
                {
                    $_obfuscate_MAduV4GmcAN9 .= substr( $m, 1, -1 ).",";
                }
            }
        }
        if ( $_obfuscate_MAduV4GmcAN9 )
        {
            if ( 2 < strlen( $_obfuscate_WPvkSFEMg��['attach'] ) )
            {
                return "[".$_obfuscate_MAduV4GmcAN9.substr( $_obfuscate_WPvkSFEMg��['attach'], 1, -1 )."]";
            }
            return "[".substr( $_obfuscate_MAduV4GmcAN9, 0, -1 )."]";
        }
        if ( 2 < strlen( $_obfuscate_WPvkSFEMg��['attach'] ) && empty( $_obfuscate_MAduV4GmcAN9 ) )
        {
            return $_obfuscate_WPvkSFEMg��['attach'];
        }
    }

    public function _checkDraft( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQ��, $_obfuscate_VBCv7Q�� )
    {
        $_obfuscate_PW9SQhMxAg�� = CNOA_PATH_FILE.( "/common/wf/draft/cwj&flowId=".$_obfuscate_F4AbnVRh."&uFlowId={$_obfuscate_TlvKhtsoOQ��}&step={$_obfuscate_VBCv7Q��}.php" );
        if ( is_file( $_obfuscate_PW9SQhMxAg�� ) )
        {
            return $_obfuscate_PW9SQhMxAg��;
        }
        return "";
    }

}

?>
