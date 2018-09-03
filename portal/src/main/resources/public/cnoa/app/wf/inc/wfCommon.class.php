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
        0 => "æ™®é€š",
        1 => "é‡è¦",
        2 => "éå¸¸é‡è¦"
    );
    protected $f_level_turn = array
    (
        "æ™®é€š" => 0,
        "é‡è¦" => 1,
        "éå¸¸é‡è¦" => 2
    );
    protected $f_status = array
    (
        0 => "æœªå‘å¸ƒ",
        1 => "åŠç†ä¸­",
        2 => "å·²åŠç†",
        3 => "å·²é€€ä»¶",
        4 => "å·²æ’¤é”€",
        5 => "å·²åˆ é™¤",
        6 => "å·²ä¸­æ­¢"
    );
    protected $f_status_turn = array
    (
        "æœªå‘å¸ƒ" => 0,
        "åŠç†ä¸­" => 1,
        "å·²åŠç†" => 2,
        "å·²é€€ä»¶" => 3,
        "å·²æ’¤é”€" => 4,
        "å·²åˆ é™¤" => 5,
        "å·²ä¸­æ­¢" => 6
    );
    protected $f_stepType = array
    (
        0 => "æœªå¼€å§‹",
        1 => "åŠç†ä¸­",
        2 => "å·²åŠç†",
        3 => "å·²ä¸­æ­¢",
        4 => "ä¿ç•™æ„è§",
        5 => "å·²æ’¤é”€"
    );
    protected $f_eventType = array
    (
        0 => "æœªå‘å¸ƒ",
        1 => "å¼€å§‹",
        2 => "åŠç†",
        3 => "æ’¤é”€",
        4 => "é€€å›",
        5 => "æ‹’ç»",
        6 => "ç»“æŸ",
        7 => "å¬å›",
        8 => "å§”æ‰˜",
        9 => "ä¿ç•™æ„è§",
        10 => "ä¸­æ­¢",
        11 => "ä¼šç­¾",
        12 => "å­æµç¨‹",
        13 => "æ›´æ–°æ•°æ®"
    );
    protected $f_isread = array
    (
        0 => "æœªé˜…",
        1 => "å·²é˜…"
    );
    protected $f_font = array
    (
        1 => "å®‹ä½“",
        2 => "æ¥·ä½“",
        3 => "éš¶ä¹¦",
        4 => "é»‘ä½“",
        5 => "andale mono",
        6 => "arial",
        7 => "arial black",
        8 => "comic sons ms",
        9 => "impact",
        10 => "times new roman",
        11 => "å¾®è½¯é›…é»‘",
        12 => "ä»¿å®‹"
    );
    protected $f_font_format = array
    (
        "bold" => "<B>åŠ ç²—</B>",
        "italic" => "<I>æ–œä½“</I>",
        "underline" => "<U>ä¸‹åˆ’çº¿</U>"
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
                0 => "yyyyå¹´mmæœˆddæ—¥",
                1 => "2012å¹´10æœˆ31æ—¥"
            ),
            9 => array
            (
                0 => "yyyyå¹´mmæœˆ",
                1 => "2012å¹´10æœˆ"
            ),
            10 => array
            (
                0 => "mmæœˆddæ—¥",
                1 => "10æœˆ31æ—¥"
            )
        ),
        "time" => array
        (
            1 => array
            (
                0 => "HH:MM(24å°æ—¶åˆ¶)",
                1 => "16:01"
            ),
            2 => array
            (
                0 => "HH:MM AM(12å°æ—¶åˆ¶)",
                1 => "10:21 AM"
            ),
            3 => array
            (
                0 => "HH:MM ä¸Šåˆ(12å°æ—¶åˆ¶)",
                1 => "10:21 ä¸Šåˆ"
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
            8 => "Yå¹´mæœˆdæ—¥",
            9 => "Yå¹´mæœˆ",
            10 => "mæœˆdæ—¥"
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
            "name" => "ã€€",
            "code" => "null"
        ),
        "admarticlesa" => array
        (
            "name" => "åŠå…¬ç”¨å“ï¼šé‡‡è´­ç”³è¯·æ˜ç»†è¡¨",
            "class" => "engAdmArticlesa"
        ),
        "admarticlesb" => array
        (
            "name" => "åŠå…¬ç”¨å“ï¼šå…¥åº“ç”³è¯·æ˜ç»†è¡¨",
            "class" => "engAdmArticlesb"
        ),
        "admarticlesc" => array
        (
            "name" => "åŠå…¬ç”¨å“ï¼šç”¨å“é¢†ç”¨æ˜ç»†è¡¨",
            "class" => "engAdmArticlesc"
        ),
        "admarticlesd" => array
        (
            "name" => "åŠå…¬ç”¨å“ï¼šç”¨å“å€Ÿç”¨æ˜ç»†è¡¨",
            "class" => "engAdmArticlesd"
        ),
        "admarticlese" => array
        (
            "name" => "åŠå…¬ç”¨å“ï¼šç”³è¯·å½’è¿˜æ˜ç»†è¡¨",
            "class" => "engAdmArticlese"
        ),
        "admarticlesf" => array
        (
            "name" => "åŠå…¬ç”¨å“ï¼šç”³è¯·ç»´æŠ¤æ˜ç»†è¡¨",
            "class" => "engAdmArticlesf"
        ),
        "jxcRuku" => array
        (
            "name" => "è¿›é”€å­˜ï¼šå…¥åº“ç”³è¯·ç‰©å“æ˜ç»†è¡¨"
        ),
        "jxcChuku" => array
        (
            "name" => "è¿›é”€å­˜ï¼šå‡ºåº“ç”³è¯·ç‰©å“æ˜ç»†è¡¨"
        ),
        "sqldetail" => array
        (
            "name" => "è‡ªå®šä¹‰ï¼šSQLæ•°æ®æ˜ç»†"
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
                "funname" => "å·¥ä½œæµç¨‹",
                "move" => "å®¡æ‰¹",
                "title" => "æ‚¨æœ‰ä¸€æ¡å·¥ä½œæµç¨‹éœ€è¦å®¡æ‰¹",
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
                "funname" => "å·¥ä½œæµç¨‹",
                "move" => "å®¡æ‰¹ç»“æŸ",
                "title" => "æ‚¨çš„ä¸€æ¡å·¥ä½œæµç¨‹å·²ç»ç»“æŸ",
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
                "funname" => "å·¥ä½œæµç¨‹",
                "move" => "å§”æ‰˜å®¡æ‰¹",
                "title" => "æ‚¨æœ‰ä¸€æ¡å·¥ä½œæµç¨‹å§”æ‰˜ç»™ä½ å®¡æ‰¹",
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
                "funname" => "å·¥ä½œæµç¨‹",
                "move" => "å®¡æ‰¹"
            )
        ),
        "huiqian" => array
        (
            0 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&type=huiqian&from=showflow",
                "from" => 31,
                "funname" => "å·¥ä½œæµç¨‹ä¼šç­¾",
                "move" => "æµç¨‹ä¼šç­¾",
                "title" => "æ‚¨æœ‰ä¸€æ¡å·¥ä½œæµç¨‹éœ€è¦ä¼šç­¾",
                "table" => "wf_u_huiqian",
                "fromtype" => "write"
            ),
            1 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=todo&task=loadPage&from=dealflow",
                "from" => 31,
                "funname" => "å·¥ä½œæµç¨‹ä¼šç­¾",
                "move" => "æµç¨‹ä¼šç­¾",
                "title" => "æ‚¨æœ‰ä¸€æ¡å·¥ä½œæµç¨‹ä¼šç­¾æ„è§",
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
                "funname" => "å·¥ä½œæµç¨‹å¬å›",
                "move" => "æµç¨‹å¬å›",
                "title" => "æ‚¨æœ‰ä¸€æ¡å·¥ä½œæµç¨‹è¢«å¬å›",
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
                "funname" => "å·¥ä½œæµç¨‹",
                "move" => "é€€å›",
                "title" => "æ‚¨æœ‰ä¸€æ¡å·¥ä½œæµç¨‹è¢«é€€å›",
                "table" => "wf_u_step",
                "fromtype" => "backToPeop"
            ),
            1 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=done&task=loadPage&from=showflow",
                "from" => 31,
                "funname" => "å·¥ä½œæµç¨‹",
                "move" => "é€€å›",
                "title" => "æ‚¨æœ‰ä¸€æ¡å·¥ä½œæµç¨‹è¢«é€€å›",
                "table" => "wf_u_step",
                "fromtype" => "backToHand"
            ),
            2 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=done&task=loadPage&from=showflow",
                "from" => 31,
                "funname" => "å·¥ä½œæµç¨‹",
                "move" => "é€€å›",
                "title" => "æ‚¨æœ‰ä¸€æ¡å·¥ä½œæµç¨‹è¢«é€€å›",
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
                "funname" => "å·¥ä½œæµç¨‹",
                "move" => "æµç¨‹ä¸­æ­¢",
                "title" => "æ‚¨æœ‰ä¸€æ¡å·¥ä½œæµç¨‹è¢«ä¸­æ­¢",
                "table" => "wf_u_step",
                "fromtype" => "stopToFaqi"
            ),
            1 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=view&task=loadPage&from=viewflow",
                "from" => 31,
                "funname" => "å·¥ä½œæµç¨‹",
                "move" => "æµç¨‹ä¸­æ­¢",
                "title" => "æ‚¨æœ‰ä¸€æ¡å·¥ä½œæµç¨‹è¢«ä¸­æ­¢",
                "table" => "wf_u_step",
                "fromtype" => "stopToPeop"
            ),
            2 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=view&task=loadPage&from=viewflow",
                "from" => 31,
                "funname" => "å·¥ä½œæµç¨‹",
                "move" => "æµç¨‹ä¸­æ­¢",
                "title" => "æ‚¨æœ‰ä¸€æ¡å·¥ä½œæµç¨‹è¢«ä¸­æ­¢",
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
                "funname" => "å·¥ä½œæµç¨‹ç£åŠ",
                "move" => "æµç¨‹ç£åŠ",
                "title" => "å·¥ä½œæµç¨‹ç£åŠ",
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
                "funname" => "å·¥ä½œæµç¨‹åˆ†å‘",
                "move" => "æµç¨‹åˆ†å‘",
                "title" => "å·¥ä½œæµç¨‹åˆ†å‘",
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
                "funname" => "å·¥ä½œæµç¨‹è¯„é˜…",
                "move" => "æµç¨‹è¯„é˜…",
                "title" => "å·¥ä½œæµç¨‹è¯„é˜…",
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
                "funname" => "å·¥ä½œæµç¨‹æ’¤é”€",
                "move" => "æµç¨‹æ’¤é”€",
                "title" => "å·¥ä½œæµç¨‹æ’¤é”€",
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
                "funname" => "å·¥ä½œæµç¨‹åˆ é™¤",
                "move" => "æµç¨‹åˆ é™¤",
                "title" => "å·¥ä½œæµç¨‹åˆ é™¤",
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
                "funname" => "å·¥ä½œæµç¨‹å­æµç¨‹å¸ƒç½®",
                "move" => "å­æµç¨‹å¸ƒç½®",
                "title" => "å­æµç¨‹å¸ƒç½®",
                "table" => "wf_u_step_child_flow",
                "fromtype" => "buzhi"
            ),
            1 => array
            (
                "href" => "index.php?app=wf&func=flow&action=use&modul=todo",
                "from" => 31,
                "funname" => "å·¥ä½œæµç¨‹å­æµç¨‹å¸ƒç½®",
                "move" => "å­æµç¨‹ç»“æŸ",
                "title" => "å­æµç¨‹ç»“æŸ",
                "table" => "wf_u_step_child_flow",
                "fromtype" => "buzhi"
            ),
            2 => array
            (
                "href" => "",
                "from" => 31,
                "funname" => "å·¥ä½œæµç¨‹å­æµç¨‹å·²å‘èµ·",
                "move" => "å­æµç¨‹å‘èµ·",
                "title" => "å­æµç¨‹å‘èµ·æˆåŠŸ",
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

    protected function api_getSortByIds( $_obfuscate_O6QLLacÿ )
    {
        global $CNOA_DB;
        if ( !is_array( $_obfuscate_O6QLLacÿ ) && count( $_obfuscate_O6QLLacÿ ) <= 0 )
        {
            return array( );
        }
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_sort, "WHERE `sortId` IN (".implode( ",", $_obfuscate_O6QLLacÿ ).") " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_6RYLWQÿÿ = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_6Aÿÿ['sortId']] = $_obfuscate_6Aÿÿ;
        }
        return $_obfuscate_6RYLWQÿÿ;
    }

    protected function api_getFlowByIds( $_obfuscate_O6QLLacÿ )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_flow, "WHERE `flowId` IN (".implode( ",", $_obfuscate_O6QLLacÿ ).") " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_6RYLWQÿÿ = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6RYLWQÿÿ[$_obfuscate_6Aÿÿ['flowId']] = $_obfuscate_6Aÿÿ;
        }
        return $_obfuscate_6RYLWQÿÿ;
    }

    protected function api_createTable( $_obfuscate_0W8ÿ, $_obfuscate_7qDAYo85aGAÿ, $_obfuscate_3tiDsnMÿ = "cnoa_z_wf_t_" )
    {
        global $CNOA_DB;
        $_obfuscate_3tiDsnMÿ .= $_obfuscate_0W8ÿ;
        $_obfuscate_3gn_eQÿÿ = addslashes( $_obfuscate_7qDAYo85aGAÿ['name'] );
        $_obfuscate_3y0Y = "CREATE TABLE IF NOT EXISTS `".$_obfuscate_3tiDsnMÿ.( "` (\n\t\t\t\t`fid` INT( 10 ) NOT NULL AUTO_INCREMENT ,\n\t\t\t\t`uFlowId` INT( 10 ) NOT NULL,\n\t\t\t\t`flowNumber` char(100) NOT NULL COMMENT 'æµç¨‹ç¼–å·',\n\t\t\t\t`flowName` varchar(200) NOT NULL COMMENT 'æµç¨‹åç§°',\n\t\t\t\t`uid` int(10) NOT NULL COMMENT 'å‘å¸ƒäºº',\n\t\t\t\t`status` int(1) NOT NULL COMMENT 'æµç¨‹çŠ¶æ€',\n\t\t\t\t`level` int(1) NOT NULL COMMENT 'çº§åˆ«: 0æ™®é€š 1é‡è¦ 2éå¸¸é‡è¦',\n\t\t\t\t`reason` mediumtext NOT NULL COMMENT 'å‘èµ·ç†ç”±',\n\t\t\t\t`posttime` int(10) NOT NULL COMMENT 'å‘å¸ƒæ—¶é—´',\n\t\t\t\t`endtime` int(10) NOT NULL COMMENT 'ç»“æŸæ—¶é—´',\n\t\t\t\tPRIMARY KEY (`fid`)\n\t\t\t\t) ENGINE = MYISAM COMMENT =  'å·¥ä½œæµæ•°æ®è¡¨ - ".$_obfuscate_3gn_eQÿÿ."';" );
        if ( $CNOA_DB->query( $_obfuscate_3y0Y ) )
        {
            $this->_createSqlField( $_obfuscate_0W8ÿ, $_obfuscate_3tiDsnMÿ );
            return TRUE;
        }
        return FALSE;
    }

    private function _createSqlField( $_obfuscate_F4AbnVRh, $_obfuscate_3tiDsnMÿ )
    {
        global $CNOA_DB;
        $_obfuscate_ammigv8ÿ = $CNOA_DB->query( "SHOW COLUMNS FROM  `".$_obfuscate_3tiDsnMÿ."` WHERE  `Field` REGEXP  '^T_'" );
        $_obfuscate_mLjk2t6lphUÿ = $_obfuscate_5V41wAcLtSe5 = array( );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_ammigv8ÿ ) )
        {
            $_obfuscate_mLjk2t6lphUÿ[] = $_obfuscate_gkt['Field'];
            $_obfuscate_5V41wAcLtSe5[$_obfuscate_gkt['Field']] = $_obfuscate_gkt['Type'];
        }
        unset( $_obfuscate_ammigv8ÿ );
        unset( $_obfuscate_gkt );
        $_obfuscate_Pm3ZMWpPkgÿÿ = $CNOA_DB->db_select( "*", $this->t_set_field, "WHERE `flowId` = '".$_obfuscate_F4AbnVRh."' " );
        if ( !is_array( $_obfuscate_Pm3ZMWpPkgÿÿ ) )
        {
            $_obfuscate_Pm3ZMWpPkgÿÿ = array( );
        }
        $_obfuscate_gTTQ1okbnc5vtx4ÿ = $_obfuscate_a1TTPdof = array( );
        foreach ( $_obfuscate_Pm3ZMWpPkgÿÿ as $_obfuscate_YIq2A8cÿ )
        {
            if ( $_obfuscate_YIq2A8cÿ['otype'] == "detailtable" )
            {
                $_obfuscate_gTTQ1okbnc5vtx4ÿ[] = $_obfuscate_YIq2A8cÿ;
            }
            $_obfuscate_p5ZWxr4ÿ = json_decode( str_replace( "'", "\"", $_obfuscate_YIq2A8cÿ['odata'] ), TRUE );
            $_obfuscate_8UKWnDlantDd = "T_".$_obfuscate_YIq2A8cÿ['id'];
            $_obfuscate_ZbugDtiHBgÿÿ = addslashes( $_obfuscate_YIq2A8cÿ['name'] );
            if ( in_array( $_obfuscate_8UKWnDlantDd, $_obfuscate_mLjk2t6lphUÿ ) )
            {
                if ( !( $_obfuscate_p5ZWxr4ÿ['dataType'] != "int" ) && !( $_obfuscate_5V41wAcLtSe5[$_obfuscate_8UKWnDlantDd] != "text" ) )
                {
                    $_obfuscate_a1TTPdof[] = "CHANGE  `".$_obfuscate_8UKWnDlantDd."`  `{$_obfuscate_8UKWnDlantDd}` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '{$_obfuscate_ZbugDtiHBgÿÿ}'";
                }
            }
            else if ( $_obfuscate_p5ZWxr4ÿ['dataType'] == "int" )
            {
                $_obfuscate_a1TTPdof[] = "ADD `".$_obfuscate_8UKWnDlantDd."` VARCHAR( 50 ) NOT NULL COMMENT  '{$_obfuscate_ZbugDtiHBgÿÿ}'";
            }
            else
            {
                $_obfuscate_a1TTPdof[] = "ADD  `".$_obfuscate_8UKWnDlantDd."` TEXT NOT NULL COMMENT  '{$_obfuscate_ZbugDtiHBgÿÿ}'";
            }
        }
        unset( $_obfuscate_Pm3ZMWpPkgÿÿ );
        unset( $_obfuscate_mLjk2t6lphUÿ );
        unset( $_obfuscate_5V41wAcLtSe5 );
        unset( $_obfuscate_j0UfixEÿ );
        if ( !empty( $_obfuscate_a1TTPdof ) )
        {
            $this->_alterTabel( $_obfuscate_a1TTPdof, $_obfuscate_3tiDsnMÿ );
        }
        foreach ( $_obfuscate_gTTQ1okbnc5vtx4ÿ as $_obfuscate_YIq2A8cÿ )
        {
            $this->_createDetailTable( $_obfuscate_F4AbnVRh, $_obfuscate_YIq2A8cÿ );
        }
    }

    private function _createDetailTable( $_obfuscate_F4AbnVRh, $_obfuscate_6RYLWQÿÿ )
    {
        global $CNOA_DB;
        $_obfuscate_3tiDsnMÿ = "cnoa_z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_6RYLWQÿÿ['id'];
        $_obfuscate_3y0Y = "CREATE TABLE IF NOT EXISTS `".$_obfuscate_3tiDsnMÿ."` (\n\t\t\t\t`fid` INT( 10 ) NOT NULL AUTO_INCREMENT ,\n\t\t\t\t`uFlowId` INT( 10 ) NOT NULL,\n\t\t\t\t`rowid` INT( 10 ) NOT NULL,\n\t\t\t\t`bindid` INT( 10 ) NOT NULL,\n\t\t\t\tPRIMARY KEY (  `fid` )\n\t\t\t\t) ENGINE=MYISAM COMMENT='å·¥ä½œæµæ˜ç»†è¡¨-';";
        $CNOA_DB->query( $_obfuscate_3y0Y );
        $_obfuscate_ammigv8ÿ = $CNOA_DB->query( "SHOW COLUMNS FROM  `".$_obfuscate_3tiDsnMÿ."` WHERE  `Field` REGEXP  '^D_'" );
        $_obfuscate_mLjk2t6lphUÿ = $_obfuscate_5V41wAcLtSe5 = array( );
        while ( $_obfuscate_gkt = $CNOA_DB->get_array( $_obfuscate_ammigv8ÿ ) )
        {
            $_obfuscate_mLjk2t6lphUÿ[] = $_obfuscate_gkt['Field'];
            $_obfuscate_5V41wAcLtSe5[$_obfuscate_gkt['Field']] = $_obfuscate_gkt['Type'];
        }
        unset( $_obfuscate_ammigv8ÿ );
        unset( $_obfuscate_gkt );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "type", "dataType", "id" ), $this->t_set_field_detail, "WHERE `fid` = '".$_obfuscate_6RYLWQÿÿ['id']."' ORDER BY `id` DESC " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_a1TTPdof = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_YIq2A8cÿ )
        {
            $_obfuscate_8UKWnDlantDd = "D_".$_obfuscate_YIq2A8cÿ['id'];
            $_obfuscate_ZbugDtiHBgÿÿ = addslashes( $_obfuscate_YIq2A8cÿ['name'] );
            if ( in_array( $_obfuscate_8UKWnDlantDd, $_obfuscate_mLjk2t6lphUÿ ) )
            {
                if ( !( $_obfuscate_YIq2A8cÿ['dataType'] != "int" ) && !( $_obfuscate_5V41wAcLtSe5[$_obfuscate_8UKWnDlantDd] != "text" ) )
                {
                    $_obfuscate_a1TTPdof[] = "CHANGE  `".$_obfuscate_8UKWnDlantDd."`  `{$_obfuscate_8UKWnDlantDd}` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '{$_obfuscate_ZbugDtiHBgÿÿ}'";
                }
            }
            else if ( $_obfuscate_YIq2A8cÿ['dataType'] == "int" )
            {
                $_obfuscate_a1TTPdof[] = "ADD `".$_obfuscate_8UKWnDlantDd."` VARCHAR( 50 ) NOT NULL COMMENT  '{$_obfuscate_ZbugDtiHBgÿÿ}'";
            }
            else
            {
                $_obfuscate_a1TTPdof[] = "ADD  `".$_obfuscate_8UKWnDlantDd."` TEXT NOT NULL COMMENT  '{$_obfuscate_ZbugDtiHBgÿÿ}'";
            }
        }
        unset( $_obfuscate_mPAjEGLn );
        unset( $_obfuscate_mLjk2t6lphUÿ );
        unset( $_obfuscate_5V41wAcLtSe5 );
        unset( $_obfuscate_YIq2A8cÿ );
        if ( !empty( $_obfuscate_a1TTPdof ) )
        {
            $this->_alterTabel( $_obfuscate_a1TTPdof, $_obfuscate_3tiDsnMÿ );
        }
    }

    private function _alterTabel( $_obfuscate_tjILu7ZH, $_obfuscate_3tiDsnMÿ )
    {
        global $CNOA_DB;
        $_obfuscate_tjILu7ZH = array_chunk( $_obfuscate_tjILu7ZH, 100 );
        foreach ( $_obfuscate_tjILu7ZH as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6Aÿÿ = implode( ",", $_obfuscate_6Aÿÿ );
            $_obfuscate_3y0Y = "ALTER TABLE ".$_obfuscate_3tiDsnMÿ." {$_obfuscate_6Aÿÿ}";
            $CNOA_DB->query( $_obfuscate_3y0Y );
        }
    }

    protected function api_takeTrustData( $_obfuscate_49fO, $_obfuscate_LeS8hwÿÿ = "all" )
    {
        global $CNOA_DB;
        $_obfuscate_Bk2lGlkÿ = "WHERE 1 ";
        if ( $_obfuscate_LeS8hwÿÿ == "sort" )
        {
            $_obfuscate_Bk2lGlkÿ .= "AND `type` = 'sort' ";
        }
        else if ( $_obfuscate_LeS8hwÿÿ == "flow" )
        {
            $_obfuscate_Bk2lGlkÿ .= "AND `type` = 'flow' ";
        }
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_trust_flow, $_obfuscate_Bk2lGlkÿ.( "AND `tid` = '".$_obfuscate_49fO."' " ) );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        return $_obfuscate_mPAjEGLn;
    }

    protected function api_loadFlowInfo( $_obfuscate_TlvKhtsoOQÿÿ )
    {
        global $CNOA_DB;
        $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        if ( $_obfuscate_7qDAYo85aGAÿ !== FALSE )
        {
            $_obfuscate_7qDAYo85aGAÿ['uname'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_7qDAYo85aGAÿ['uid'] );
        }
        $_obfuscate_7qDAYo85aGAÿ['posttime'] = date( "Yå¹´mæœˆdæ—¥ Hæ—¶iåˆ†", $_obfuscate_7qDAYo85aGAÿ['posttime'] );
        $_obfuscate_7qDAYo85aGAÿ['level'] = $this->f_level[$_obfuscate_7qDAYo85aGAÿ['level']];
        $_obfuscate_7qDAYo85aGAÿ['status'] = $this->f_status[$_obfuscate_7qDAYo85aGAÿ['status']];
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_6RYLWQÿÿ['flowInfo'] = $_obfuscate_7qDAYo85aGAÿ;
        return $_obfuscate_6RYLWQÿÿ;
    }

    protected function getCmMapping( $_obfuscate_Ce9h, $_obfuscate_TervNcSylPEÿ = TRUE )
    {
        global $CNOA_DB;
        return "T_".$_obfuscate_Ce9h;
    }

    protected function api_deleteFields( $_obfuscate_mLjk2t6lphUÿ )
    {
        global $CNOA_DB;
        foreach ( $_obfuscate_mLjk2t6lphUÿ as $_obfuscate_VgKtFegÿ )
        {
            $_obfuscate_2s5w882kTgÿÿ = CNOA_PATH_FILE.( "/common/wf/sqlselector/".$_obfuscate_VgKtFegÿ.".php" );
            if ( file_exists( $_obfuscate_2s5w882kTgÿÿ ) )
            {
                @unlink( $_obfuscate_2s5w882kTgÿÿ );
            }
        }
        $CNOA_DB->db_delete( $this->t_set_field, "WHERE `id` IN (".implode( ",", $_obfuscate_mLjk2t6lphUÿ ).") " );
        $CNOA_DB->db_delete( $this->t_set_field_detail, "WHERE `fid` IN (".implode( ",", $_obfuscate_mLjk2t6lphUÿ ).") " );
        $CNOA_DB->db_delete( $this->t_set_step_fields, "WHERE `fieldId` IN (".implode( ",", $_obfuscate_mLjk2t6lphUÿ ).") " );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_step_condition, "WHERE `name` IN (".implode( ",", $_obfuscate_mLjk2t6lphUÿ ).") " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        foreach ( $_obfuscate_mLjk2t6lphUÿ as $_obfuscate_6Aÿÿ )
        {
            if ( !empty( $_obfuscate_6Aÿÿ ) )
            {
                $CNOA_DB->db_delete( $this->t_set_step_condition, "WHERE `pid` = '".$_obfuscate_6Aÿÿ."' OR `name` = '{$_obfuscate_6Aÿÿ}' " );
            }
        }
        unset( $_obfuscate_mLjk2t6lphUÿ[array_search( 0, $_obfuscate_mLjk2t6lphUÿ )] );
        if ( count( $_obfuscate_mLjk2t6lphUÿ ) )
        {
            $CNOA_DB->db_delete( $this->t_set_step_user, "WHERE `kong` IN (".implode( ",", $_obfuscate_mLjk2t6lphUÿ ).")" );
        }
    }

    protected function api_getFieldInfoById( )
    {
    }

    protected function api_getFieldInfoByName( $_obfuscate_3gn_eQÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_8XjS1n72 = FALSE )
    {
        global $CNOA_DB;
        if ( $_obfuscate_8XjS1n72 )
        {
            return $CNOA_DB->db_getone( "*", $this->t_set_field_detail, "WHERE `name`='".$_obfuscate_3gn_eQÿÿ."' AND `fid`='{$_obfuscate_F4AbnVRh}'" );
        }
        return $CNOA_DB->db_getone( "*", $this->t_set_field, "WHERE `name`='".$_obfuscate_3gn_eQÿÿ."' AND `flowId`='{$_obfuscate_F4AbnVRh}'" );
    }

    protected function api_splitGongShi( $_obfuscate_sSwuE42EWQÿÿ )
    {
        $_obfuscate_sSwuE42EWQÿÿ = str_replace( array( "ï¼", "Ã—" ), array( "-", "*" ), $_obfuscate_sSwuE42EWQÿÿ );
        $_obfuscate_sSwuE42EWQÿÿ = str_split( $_obfuscate_sSwuE42EWQÿÿ );
        $_obfuscate_jh2JTltz90ht5hYÿ = array( );
        $_obfuscate_bVo7VnFDppsÿ = "";
        foreach ( $_obfuscate_sSwuE42EWQÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( in_array( $_obfuscate_6Aÿÿ, array( "+", "-", "*", "/", "(", ")" ) ) )
            {
                if ( !empty( $_obfuscate_bVo7VnFDppsÿ ) )
                {
                    $_obfuscate_jh2JTltz90ht5hYÿ[] = $_obfuscate_bVo7VnFDppsÿ;
                }
                $_obfuscate_jh2JTltz90ht5hYÿ[] = $_obfuscate_6Aÿÿ;
                $_obfuscate_NAsÿ = "r";
                $_obfuscate_bVo7VnFDppsÿ = "";
            }
            else
            {
                $_obfuscate_bVo7VnFDppsÿ .= $_obfuscate_6Aÿÿ;
            }
        }
        if ( !empty( $_obfuscate_bVo7VnFDppsÿ ) )
        {
            $_obfuscate_jh2JTltz90ht5hYÿ[] = $_obfuscate_bVo7VnFDppsÿ;
        }
        return $_obfuscate_jh2JTltz90ht5hYÿ;
    }

    protected function api_saveFieldData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_JQJwE4USnB0ÿ, $_obfuscate_dGoPOiQ2Iw5a = array( ), $_obfuscate_BqBV6WSz3wel0ZDw = array( ), $_obfuscate_vholQÿÿ = "", $_obfuscate_qZkmBgÿÿ = "" )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_3tiDsnMÿ = "z_wf_t_".$_obfuscate_F4AbnVRh;
        $_obfuscate_6RYLWQÿÿ = array(
            "uFlowId" => $_obfuscate_TlvKhtsoOQÿÿ
        );
        $_obfuscate_1JE3WRAÿ = $CNOA_DB->db_getone( "*", $_obfuscate_3tiDsnMÿ, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        if ( is_array( $_obfuscate_JQJwE4USnB0ÿ['normal'] ) )
        {
            foreach ( $_obfuscate_JQJwE4USnB0ÿ['normal'] as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_6RYLWQÿÿ["T_".$_obfuscate_5wÿÿ] = addslashes( $_obfuscate_6Aÿÿ );
            }
        }
        if ( $_obfuscate_vholQÿÿ == "new" )
        {
            $_obfuscate_6RYLWQÿÿ['flowNumber'] = $_obfuscate_qZkmBgÿÿ['flowNumber'];
            $_obfuscate_6RYLWQÿÿ['flowName'] = $_obfuscate_qZkmBgÿÿ['flowName'];
            $_obfuscate_6RYLWQÿÿ['uid'] = $_obfuscate_qZkmBgÿÿ['uid'];
            $_obfuscate_6RYLWQÿÿ['level'] = $_obfuscate_qZkmBgÿÿ['level'];
            $_obfuscate_6RYLWQÿÿ['reason'] = $_obfuscate_qZkmBgÿÿ['reason'];
            $_obfuscate_6RYLWQÿÿ['posttime'] = $_obfuscate_qZkmBgÿÿ['posttime'];
            $_obfuscate_tjILu7ZH = $CNOA_DB->db_select( array( "odata", "id" ), $this->t_set_field, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND otype='macro'" );
            if ( !is_array( $_obfuscate_tjILu7ZH ) )
            {
                $_obfuscate_tjILu7ZH = array( );
            }
            foreach ( $_obfuscate_tjILu7ZH as $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_p5ZWxr4ÿ = json_decode( str_replace( "'", "\"", $_obfuscate_6Aÿÿ['odata'] ), TRUE );
                if ( $_obfuscate_p5ZWxr4ÿ['dataType'] == "flownum" )
                {
                    $_obfuscate_cO0ZkQÿÿ = $CNOA_DB->db_getcount( $this->t_set_step_fields, "WHERE `flowId`= ".$_obfuscate_F4AbnVRh." and `write`= 1 and `stepId` = 2 and `fieldId` = ".$_obfuscate_6Aÿÿ['id'] );
                    if ( $_obfuscate_cO0ZkQÿÿ )
                    {
                        $_obfuscate_6RYLWQÿÿ["T_".$_obfuscate_6Aÿÿ['id']] = $_obfuscate_qZkmBgÿÿ['flowNumber'];
                    }
                }
                else if ( $_obfuscate_p5ZWxr4ÿ['dataType'] == "flowname" )
                {
                    $_obfuscate_cO0ZkQÿÿ = $CNOA_DB->db_getcount( $this->t_set_step_fields, "WHERE `flowId`= ".$_obfuscate_F4AbnVRh." and `write`= 1 and stepId = 2 and fieldId = ".$_obfuscate_6Aÿÿ['id'] );
                    if ( $_obfuscate_cO0ZkQÿÿ )
                    {
                        $_obfuscate_6RYLWQÿÿ["T_".$_obfuscate_6Aÿÿ['id']] = $_obfuscate_qZkmBgÿÿ['flowName'];
                    }
                }
            }
        }
        if ( !$_obfuscate_1JE3WRAÿ )
        {
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $_obfuscate_3tiDsnMÿ );
        }
        else
        {
            $CNOA_DB->db_update( $_obfuscate_6RYLWQÿÿ, $_obfuscate_3tiDsnMÿ, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        }
        $_obfuscate_rBZ_wlJtMUNj = array( );
        unset( $_obfuscate_6RYLWQÿÿ );
        if ( !empty( $_obfuscate_JQJwE4USnB0ÿ['detail'] ) )
        {
            foreach ( $_obfuscate_JQJwE4USnB0ÿ['detail'] as $_obfuscate_gkt => $_obfuscate_eBU_Sjcÿ )
            {
                foreach ( $_obfuscate_eBU_Sjcÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
                {
                    $_obfuscate_rBZ_wlJtMUNj[$_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_5wÿÿ]][$_obfuscate_gkt][$_obfuscate_5wÿÿ] = $_obfuscate_6Aÿÿ;
                }
            }
            foreach ( $_obfuscate_rBZ_wlJtMUNj as $_obfuscate_V0WIw2BKQgÿÿ => $_obfuscate_8XjS1n72 )
            {
                $_obfuscate_3tiDsnMÿ = "z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_V0WIw2BKQgÿÿ;
                $_obfuscate_Gfham6St = array( );
                foreach ( $_obfuscate_8XjS1n72 as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
                {
                    $_obfuscate_Gfham6St[] = $_obfuscate_5wÿÿ;
                }
                $CNOA_DB->db_delete( $_obfuscate_3tiDsnMÿ, "WHERE `rowid` NOT IN (".implode( ",", $_obfuscate_Gfham6St ).( ") AND `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " ) );
            }
            foreach ( $_obfuscate_rBZ_wlJtMUNj as $_obfuscate_V0WIw2BKQgÿÿ => $_obfuscate_8XjS1n72 )
            {
                $_obfuscate_3tiDsnMÿ = "z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_V0WIw2BKQgÿÿ;
                ksort( &$_obfuscate_8XjS1n72 );
                foreach ( $_obfuscate_8XjS1n72 as $_obfuscate_gkt => $_obfuscate_s594VX4T0wÿÿ )
                {
                    $_obfuscate_6RYLWQÿÿ = array(
                        "uFlowId" => $_obfuscate_TlvKhtsoOQÿÿ
                    );
                    foreach ( $_obfuscate_s594VX4T0wÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
                    {
                        if ( is_array( $_obfuscate_6Aÿÿ ) )
                        {
                            $_obfuscate_6Aÿÿ = addslashes( json_encode( $_obfuscate_6Aÿÿ ) );
                        }
                        $_obfuscate_6RYLWQÿÿ["D_".$_obfuscate_5wÿÿ] = addslashes( $_obfuscate_6Aÿÿ );
                    }
                    $_obfuscate_6RYLWQÿÿ['rowid'] = $_obfuscate_gkt;
                    $_obfuscate_6RYLWQÿÿ['bindid'] = $_obfuscate_BqBV6WSz3wel0ZDw[$_obfuscate_V0WIw2BKQgÿÿ][$_obfuscate_gkt];
                    $_obfuscate_8Q1yVKUÿ = $CNOA_DB->db_getone( "*", $_obfuscate_3tiDsnMÿ, "WHERE `rowid` = '".$_obfuscate_gkt."' AND `uFlowId` = '{$_obfuscate_TlvKhtsoOQÿÿ}' " );
                    if ( empty( $_obfuscate_8Q1yVKUÿ ) )
                    {
                        $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $_obfuscate_3tiDsnMÿ );
                    }
                    else
                    {
                        $CNOA_DB->db_update( $_obfuscate_6RYLWQÿÿ, $_obfuscate_3tiDsnMÿ, "WHERE `rowid` = '".$_obfuscate_gkt."' AND `uFlowId` = '{$_obfuscate_TlvKhtsoOQÿÿ}' " );
                    }
                }
            }
        }
    }

    protected function api_getWfFieldData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_H9Mbnwÿÿ = FALSE )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_1ahPX6OQ7wÿÿ = $CNOA_DB->db_getone( "*", "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        if ( !is_array( $_obfuscate_1ahPX6OQ7wÿÿ ) )
        {
            $_obfuscate_1ahPX6OQ7wÿÿ = array( );
        }
        $_obfuscate_tjILu7ZH = $CNOA_DB->db_select( array( "id", "odata" ), "wf_s_field", " WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND `otype`='macro'" );
        if ( !is_array( $_obfuscate_tjILu7ZH ) )
        {
            $_obfuscate_tjILu7ZH = array( );
        }
        foreach ( $_obfuscate_tjILu7ZH as $_obfuscate_3QYÿ => $_obfuscate_EGUÿ )
        {
            $_obfuscate_p5ZWxr4ÿ = json_decode( str_replace( "'", "\"", $_obfuscate_EGUÿ['odata'] ), TRUE );
            if ( $_obfuscate_p5ZWxr4ÿ['dataType'] == "loginname" )
            {
                $_obfuscate_YIq2A8cÿ[] = $_obfuscate_EGUÿ['id'];
            }
        }
        if ( !is_array( $_obfuscate_YIq2A8cÿ ) )
        {
            $_obfuscate_YIq2A8cÿ = array( );
        }
        $_obfuscate_Jrp1 = array( );
        foreach ( $_obfuscate_1ahPX6OQ7wÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( ereg( "T_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_ppiyBfP31z3fvR8ÿ = str_replace( "T_", "", $_obfuscate_5wÿÿ );
                if ( in_array( $_obfuscate_ppiyBfP31z3fvR8ÿ, $_obfuscate_YIq2A8cÿ ) )
                {
                    $_obfuscate_0W8ÿ = $CNOA_DB->db_getfield( "uid", "main_user", " WHERE `truename`='".$_obfuscate_6Aÿÿ."'" );
                    if ( !is_numeric( $_obfuscate_6Aÿÿ ) || !empty( $_obfuscate_0W8ÿ ) )
                    {
                        $_obfuscate_6Aÿÿ = $_obfuscate_0W8ÿ;
                    }
                    else
                    {
                        $_obfuscate_6Aÿÿ = $_obfuscate_6Aÿÿ;
                    }
                }
                if ( !$_obfuscate_H9Mbnwÿÿ )
                {
                    $_obfuscate_Jrp1[] = array(
                        "id" => $_obfuscate_ppiyBfP31z3fvR8ÿ,
                        "data" => $_obfuscate_6Aÿÿ
                    );
                }
                else
                {
                    $_obfuscate_Jrp1[$_obfuscate_ppiyBfP31z3fvR8ÿ] = $_obfuscate_6Aÿÿ;
                }
            }
        }
        return $_obfuscate_Jrp1;
    }

    protected function api_getWfFieldDetailData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_X7G10FfJmb, $_obfuscate_H9Mbnwÿÿ = FALSE )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        if ( $_obfuscate_TlvKhtsoOQÿÿ == 0 )
        {
            return array( );
        }
        $this->resetDetailRowNumber( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_X7G10FfJmb );
        $_obfuscate_1ahPX6OQ7wÿÿ = $CNOA_DB->db_select( "*", "z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_X7G10FfJmb, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        if ( !is_array( $_obfuscate_1ahPX6OQ7wÿÿ ) )
        {
            $_obfuscate_1ahPX6OQ7wÿÿ = array( );
        }
        $_obfuscate_Jrp1 = array( );
        foreach ( $_obfuscate_1ahPX6OQ7wÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_ClAÿ => $_obfuscate_bRQÿ )
            {
                if ( ereg( "D_", $_obfuscate_ClAÿ ) )
                {
                    $_obfuscate_ppiyBfP31z3fvR8ÿ = str_replace( "D_", "", $_obfuscate_ClAÿ );
                    if ( !$_obfuscate_H9Mbnwÿÿ )
                    {
                        $_obfuscate_Jrp1[$_obfuscate_6Aÿÿ['rowid']][] = array(
                            "id" => $_obfuscate_6Aÿÿ['rowid']."_".$_obfuscate_ppiyBfP31z3fvR8ÿ,
                            "data" => $_obfuscate_bRQÿ
                        );
                    }
                    else
                    {
                        $_obfuscate_Jrp1[$_obfuscate_6Aÿÿ['rowid']][$_obfuscate_ppiyBfP31z3fvR8ÿ] = $_obfuscate_bRQÿ;
                    }
                }
            }
            $_obfuscate_Jrp1[$_obfuscate_6Aÿÿ['rowid']]['bindid'] = $_obfuscate_6Aÿÿ['bindid'];
        }
        return $_obfuscate_Jrp1;
    }

    protected function resetDetailRowNumber( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_X7G10FfJmb )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "fid" ), "z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_X7G10FfJmb, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' ORDER BY `rowid` ASC " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_gkt = 1;
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $CNOA_DB->db_update( array(
                "rowid" => $_obfuscate_gkt
            ), "z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_X7G10FfJmb, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `fid` = '{$_obfuscate_6Aÿÿ['fid']}' " );
            ++$_obfuscate_gkt;
        }
    }

    protected function api_formatFlowNumber( $_obfuscate_IMeby9iyHIgÿ, $_obfuscate_neM4JBUJlmgÿ, $_obfuscate_zPP1hxIu42teMwÿÿ, $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_NS44QYkÿ = $CNOA_SESSION->get( "TRUENAME" );
        $_obfuscate_KYPe3Fn6DvBxAÿÿ = "";
        $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->db_getone( array( "nameRule", "nameRuleAllowEdit" ), $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $_obfuscate_anIYs0Ctkexb619U = $_obfuscate_7qDAYo85aGAÿ['nameRule'];
        if ( $_obfuscate_anIYs0Ctkexb619U != $_obfuscate_IMeby9iyHIgÿ && $_obfuscate_7qDAYo85aGAÿ['nameRuleAllowEdit'] == 0 )
        {
            $_obfuscate_IMeby9iyHIgÿ = $_obfuscate_anIYs0Ctkexb619U;
        }
        $_obfuscate_KYPe3Fn6DvBxAÿÿ = str_replace( "{F}", $_obfuscate_neM4JBUJlmgÿ, $_obfuscate_IMeby9iyHIgÿ );
        $_obfuscate_KYPe3Fn6DvBxAÿÿ = str_replace( "{U}", $_obfuscate_NS44QYkÿ, $_obfuscate_KYPe3Fn6DvBxAÿÿ );
        $_obfuscate_KYPe3Fn6DvBxAÿÿ = str_replace( "{Y}", date( "Y", $GLOBALS['CNOA_TIMESTAMP'] ), $_obfuscate_KYPe3Fn6DvBxAÿÿ );
        $_obfuscate_KYPe3Fn6DvBxAÿÿ = str_replace( "{M}", date( "m", $GLOBALS['CNOA_TIMESTAMP'] ), $_obfuscate_KYPe3Fn6DvBxAÿÿ );
        $_obfuscate_KYPe3Fn6DvBxAÿÿ = str_replace( "{D}", date( "d", $GLOBALS['CNOA_TIMESTAMP'] ), $_obfuscate_KYPe3Fn6DvBxAÿÿ );
        $_obfuscate_KYPe3Fn6DvBxAÿÿ = str_replace( "{H}", date( "H", $GLOBALS['CNOA_TIMESTAMP'] ), $_obfuscate_KYPe3Fn6DvBxAÿÿ );
        $_obfuscate_KYPe3Fn6DvBxAÿÿ = str_replace( "{I}", date( "i", $GLOBALS['CNOA_TIMESTAMP'] ), $_obfuscate_KYPe3Fn6DvBxAÿÿ );
        $_obfuscate_KYPe3Fn6DvBxAÿÿ = str_replace( "{S}", date( "s", $GLOBALS['CNOA_TIMESTAMP'] ), $_obfuscate_KYPe3Fn6DvBxAÿÿ );
        $_obfuscate_odvySwÿÿ = preg_replace( "/(.*)\\{([N]{1,})\\}(.*)/i", "\\2", $_obfuscate_KYPe3Fn6DvBxAÿÿ );
        if ( strlen( $_obfuscate_odvySwÿÿ ) < strlen( $_obfuscate_KYPe3Fn6DvBxAÿÿ ) )
        {
            $_obfuscate_nguaagÿÿ = str_pad( $_obfuscate_zPP1hxIu42teMwÿÿ, strlen( $_obfuscate_odvySwÿÿ ), "0", STR_PAD_LEFT );
            $_obfuscate_KYPe3Fn6DvBxAÿÿ = str_replace( "{".$_obfuscate_odvySwÿÿ."}", $_obfuscate_nguaagÿÿ, $_obfuscate_KYPe3Fn6DvBxAÿÿ );
        }
        return $_obfuscate_KYPe3Fn6DvBxAÿÿ;
    }

    public function api_getBaseList( $_obfuscate_TlvKhtsoOQÿÿ )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        if ( $_obfuscate_mPAjEGLn !== FALSE )
        {
            $_obfuscate_mPAjEGLn['uname'] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_mPAjEGLn['uid'] );
            $_obfuscate_mPAjEGLn['level'] = $this->f_level[$_obfuscate_mPAjEGLn['level']];
            $_obfuscate_mPAjEGLn['status'] = $this->f_status[$_obfuscate_mPAjEGLn['status']];
            $_obfuscate_mPAjEGLn['posttime'] = formatdate( $_obfuscate_mPAjEGLn['posttime'], "Y-m-d H:i" );
        }
        return $_obfuscate_mPAjEGLn;
    }

    protected function _checkChildFlow( $_obfuscate_TlvKhtsoOQÿÿ = 0, $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_jOcDpChC9wÿÿ = $CNOA_DB->db_getone( "*", $this->t_use_step_child_flow, "WHERE `uFlowId` = ".$_obfuscate_TlvKhtsoOQÿÿ." " );
        if ( !empty( $_obfuscate_jOcDpChC9wÿÿ ) )
        {
            $_obfuscate_sx8ÿ = $CNOA_DB->db_select( "*", $this->t_set_step_child_kongjian, "WHERE `arrow` = 'right' AND `bangdingFlow` = ".$_obfuscate_jOcDpChC9wÿÿ['flowId']." AND `stepId` = {$_obfuscate_jOcDpChC9wÿÿ['stepId']} " );
            if ( !empty( $_obfuscate_sx8ÿ ) )
            {
                $_obfuscate_dXDzrHajXwÿÿ = $CNOA_DB->db_getfield( "flowId", $this->t_use_flow, "WHERE `uFlowId` = ".$_obfuscate_jOcDpChC9wÿÿ['puFlowId']." " );
                $this->_updateFlowData( $_obfuscate_sx8ÿ, $_obfuscate_jOcDpChC9wÿÿ['flowId'], $_obfuscate_dXDzrHajXwÿÿ, $_obfuscate_jOcDpChC9wÿÿ['puFlowId'], $_obfuscate_TlvKhtsoOQÿÿ );
            }
        }
    }

    protected function _updateFlowData( $_obfuscate_ixuxHoql0ImL, $_obfuscate_F4AbnVRh, $_obfuscate_dXDzrHajXwÿÿ, $_obfuscate_wzlwEupWLkwÿ, $_obfuscate_TlvKhtsoOQÿÿ )
    {
        global $CNOA_DB;
        foreach ( $_obfuscate_ixuxHoql0ImL as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['flowId'] == $_obfuscate_dXDzrHajXwÿÿ )
            {
                if ( ereg( "T_", $_obfuscate_6Aÿÿ['childKongjian'] ) )
                {
                    $_obfuscate_LI3AQT0XxAÿÿ[] = $_obfuscate_6Aÿÿ['childKongjian'];
                }
                else if ( ereg( "D_", $_obfuscate_6Aÿÿ['childKongjian'] ) )
                {
                    $_obfuscate_8jhldA9Y9Aÿÿ = str_replace( "D_", "", $_obfuscate_6Aÿÿ['childKongjian'] );
                    $_obfuscate_Ce9h = $CNOA_DB->db_getfield( "fid", $this->t_set_field_detail, "WHERE `id` = ".$_obfuscate_8jhldA9Y9Aÿÿ." " );
                    $_obfuscate_qWuC296rSwÿÿ[$_obfuscate_Ce9h][] = $_obfuscate_6Aÿÿ['childKongjian'];
                    $_obfuscate_w2Zth0sNjFqUYc_5pS0ÿ[$_obfuscate_6Aÿÿ['childKongjian']] = $_obfuscate_Ce9h;
                }
            }
        }
        $_obfuscate_gZ2OAUO8XAÿÿ = array( );
        $_obfuscate_8RkPDdDI4gÿÿ = array( );
        if ( !empty( $_obfuscate_LI3AQT0XxAÿÿ ) )
        {
            $_obfuscate_XKxKFeaAMUQÿ = $CNOA_DB->db_getone( $_obfuscate_LI3AQT0XxAÿÿ, "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE `uFlowId` = ".$_obfuscate_TlvKhtsoOQÿÿ." " );
        }
        if ( !empty( $_obfuscate_qWuC296rSwÿÿ ) )
        {
            $_obfuscate_dGoPOiQ2Iw5a = array( );
            foreach ( $_obfuscate_qWuC296rSwÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_7Hp0w_lfFt4ÿ = $CNOA_DB->db_getone( $_obfuscate_6Aÿÿ, "z_wf_d_".$_obfuscate_F4AbnVRh."_".$_obfuscate_5wÿÿ, "WHERE `uFlowId` = ".$_obfuscate_TlvKhtsoOQÿÿ." " );
                if ( !is_array( $_obfuscate_7Hp0w_lfFt4ÿ ) )
                {
                    $_obfuscate_7Hp0w_lfFt4ÿ = array( );
                }
                $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_5wÿÿ] = $_obfuscate_7Hp0w_lfFt4ÿ;
            }
        }
        foreach ( $_obfuscate_ixuxHoql0ImL as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['flowId'] == $_obfuscate_dXDzrHajXwÿÿ )
            {
                if ( ereg( "T_", $_obfuscate_6Aÿÿ['parentKongjian'] ) )
                {
                    if ( ereg( "T_", $_obfuscate_6Aÿÿ['childKongjian'] ) )
                    {
                        $_obfuscate_gZ2OAUO8XAÿÿ[$_obfuscate_6Aÿÿ['parentKongjian']] = $_obfuscate_XKxKFeaAMUQÿ[$_obfuscate_6Aÿÿ['childKongjian']];
                    }
                    else if ( ereg( "D_", $_obfuscate_6Aÿÿ['childKongjian'] ) )
                    {
                        $_obfuscate_gZ2OAUO8XAÿÿ[$_obfuscate_6Aÿÿ['parentKongjian']] = $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_w2Zth0sNjFqUYc_5pS0ÿ[$_obfuscate_6Aÿÿ['childKongjian']]][$_obfuscate_6Aÿÿ['childKongjian']];
                    }
                }
                if ( ereg( "D_", $_obfuscate_6Aÿÿ['parentKongjian'] ) )
                {
                    $_obfuscate_8jhldA9Y9Aÿÿ = str_replace( "D_", "", $_obfuscate_6Aÿÿ['parentKongjian'] );
                    $_obfuscate_Ce9h = $CNOA_DB->db_getfield( "fid", $this->t_set_field_detail, "WHERE `id` = ".$_obfuscate_8jhldA9Y9Aÿÿ." " );
                    if ( ereg( "T_", $_obfuscate_6Aÿÿ['childKongjian'] ) )
                    {
                        $_obfuscate_8RkPDdDI4gÿÿ[$_obfuscate_Ce9h][$_obfuscate_6Aÿÿ['parentKongjian']] = $_obfuscate_XKxKFeaAMUQÿ[$_obfuscate_6Aÿÿ['childKongjian']];
                    }
                    else if ( ereg( "D_", $_obfuscate_6Aÿÿ['childKongjian'] ) )
                    {
                        $_obfuscate_8RkPDdDI4gÿÿ[$_obfuscate_Ce9h][$_obfuscate_6Aÿÿ['parentKongjian']] = $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_w2Zth0sNjFqUYc_5pS0ÿ[$_obfuscate_6Aÿÿ['childKongjian']]][$_obfuscate_6Aÿÿ['childKongjian']];
                    }
                }
            }
        }
        if ( !empty( $_obfuscate_gZ2OAUO8XAÿÿ ) )
        {
            ( $_obfuscate_wzlwEupWLkwÿ );
            $_obfuscate_e53ODz04JQÿÿ = new wfCache( );
            $_obfuscate_8LH7ik2lzjhs7gÿÿ = $_obfuscate_e53ODz04JQÿÿ->getConfig( "step_child_kongjian" );
            if ( empty( $_obfuscate_8LH7ik2lzjhs7gÿÿ ) )
            {
                return;
            }
            $_obfuscate_jOcDpChC9wÿÿ = $CNOA_DB->db_getone( "*", $this->t_use_step_child_flow, "WHERE `uFlowId` = ".$_obfuscate_TlvKhtsoOQÿÿ );
            if ( !is_array( $_obfuscate_jOcDpChC9wÿÿ ) )
            {
                $_obfuscate_jOcDpChC9wÿÿ = array( );
            }
            $_obfuscate_YnNK_c9snwÿÿ = array( );
            foreach ( $_obfuscate_8LH7ik2lzjhs7gÿÿ as $_obfuscate_VgKtFegÿ )
            {
                if ( $_obfuscate_VgKtFegÿ['bangdingFlow'] == $_obfuscate_F4AbnVRh )
                {
                    $_obfuscate_YnNK_c9snwÿÿ[] = $_obfuscate_VgKtFegÿ;
                }
            }
            foreach ( $_obfuscate_YnNK_c9snwÿÿ as $_obfuscate_YIq2A8cÿ )
            {
                foreach ( $_obfuscate_gZ2OAUO8XAÿÿ as $_obfuscate_Vwty => $_obfuscate_LQ8UKgÿÿ )
                {
                    if ( !( $_obfuscate_YIq2A8cÿ['stepId'] != $_obfuscate_jOcDpChC9wÿÿ['stepId'] ) )
                    {
                        if ( $_obfuscate_YIq2A8cÿ['parentType'] == "str" && $_obfuscate_Vwty == $_obfuscate_YIq2A8cÿ['parentKongjian'] && $_obfuscate_YIq2A8cÿ['arrow'] == "right" && !empty( $_obfuscate_LQ8UKgÿÿ ) )
                        {
                            if ( $_obfuscate_YIq2A8cÿ['childType'] == "user_sel" )
                            {
                                $_obfuscate_LQ8UKgÿÿ = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_LQ8UKgÿÿ );
                            }
                            else if ( $_obfuscate_YIq2A8cÿ['childType'] == "users_sel" )
                            {
                                $_obfuscate_xs33Yt_k = app::loadapp( "main", "user" )->api_getUserTruenameByUid( explode( ",", $_obfuscate_LQ8UKgÿÿ ) );
                                $_obfuscate_LQ8UKgÿÿ = implode( ",", $_obfuscate_xs33Yt_k );
                            }
                            else if ( $_obfuscate_YIq2A8cÿ['childType'] == "dept_sel" )
                            {
                                $_obfuscate_LQ8UKgÿÿ = app::loadapp( "main", "struct" )->api_getNameById( $_obfuscate_LQ8UKgÿÿ );
                            }
                            else if ( $_obfuscate_YIq2A8cÿ['childType'] == "depts_sel" )
                            {
                                $_obfuscate_VgKtFegÿ = app::loadapp( "main", "struct" )->api_getNamesByIds( explode( ",", $_obfuscate_LQ8UKgÿÿ ) );
                                $_obfuscate_LQ8UKgÿÿ = implode( ",", $_obfuscate_VgKtFegÿ );
                            }
                            else if ( $_obfuscate_YIq2A8cÿ['childType'] == "station_sel" )
                            {
                                $_obfuscate_LQ8UKgÿÿ = app::loadapp( "main", "station" )->api_getNameById( $_obfuscate_LQ8UKgÿÿ );
                            }
                            else if ( $_obfuscate_YIq2A8cÿ['childType'] == "stations_sel" )
                            {
                                $_obfuscate_VgKtFegÿ = app::loadapp( "main", "station" )->api_getNamesByIds( explode( ",", $_obfuscate_LQ8UKgÿÿ ) );
                                $_obfuscate_LQ8UKgÿÿ = implode( ",", $_obfuscate_VgKtFegÿ );
                            }
                            else if ( $_obfuscate_YIq2A8cÿ['childType'] == "job_sel" )
                            {
                                $_obfuscate_LQ8UKgÿÿ = app::loadapp( "main", "job" )->api_getNameById( $_obfuscate_LQ8UKgÿÿ );
                            }
                            else if ( $_obfuscate_YIq2A8cÿ['childType'] == "jobs_sel" )
                            {
                                $_obfuscate_VgKtFegÿ = app::loadapp( "main", "job" )->api_getNamesByIds( explode( ",", $_obfuscate_LQ8UKgÿÿ ) );
                                $_obfuscate_LQ8UKgÿÿ = implode( ",", $_obfuscate_VgKtFegÿ );
                            }
                            else if ( $_obfuscate_YIq2A8cÿ['childType'] == "loginname" )
                            {
                                $_obfuscate_LQ8UKgÿÿ = app::loadapp( "main", "user" )->api_getUserTruenameByUid( $_obfuscate_LQ8UKgÿÿ );
                            }
                        }
                        $_obfuscate_gZ2OAUO8XAÿÿ[$_obfuscate_Vwty] = $_obfuscate_LQ8UKgÿÿ;
                    }
                }
            }
            $CNOA_DB->db_update( $_obfuscate_gZ2OAUO8XAÿÿ, "z_wf_t_".$_obfuscate_dXDzrHajXwÿÿ, "WHERE `uFlowId` = ".$_obfuscate_wzlwEupWLkwÿ." " );
        }
        if ( !empty( $_obfuscate_8RkPDdDI4gÿÿ ) )
        {
            foreach ( $_obfuscate_8RkPDdDI4gÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $CNOA_DB->update( $_obfuscate_gZ2OAUO8XAÿÿ, "z_wf_t_".$_obfuscate_dXDzrHajXwÿÿ."_".$_obfuscate_5wÿÿ, "WHERE `uFlowId` = ".$_obfuscate_wzlwEupWLkwÿ." " );
            }
        }
    }

    public function api_getStepList( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmkÿ )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_step, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `uid`!=0 AND `status`!=0 ORDER BY `id` ASC" );
        if ( $_obfuscate_mPAjEGLn !== FALSE )
        {
            $_obfuscate_PVLK5jra = array( 0 );
            $_obfuscate_QwT4KwrB2wÿÿ = array( 0 );
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_PVLK5jra[] = $_obfuscate_6Aÿÿ['uid'];
                $_obfuscate_PVLK5jra[] = $_obfuscate_6Aÿÿ['proxyUid'];
                $_obfuscate_QwT4KwrB2wÿÿ[] = $_obfuscate_6Aÿÿ['uStepId'];
            }
            $_obfuscate_dga5p5gjYJ23VQÿÿ = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_PVLK5jra );
            $_obfuscate_8NPwP9PwBwÿ = $CNOA_DB->db_select( array( "stepId", "doBtnText" ), $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `stepId` IN (".implode( ",", $_obfuscate_QwT4KwrB2wÿÿ ).")" );
            if ( !is_array( $_obfuscate_8NPwP9PwBwÿ ) )
            {
                $_obfuscate_8NPwP9PwBwÿ = array( );
            }
            $_obfuscate_hEQ34cXm = array( );
            foreach ( $_obfuscate_8NPwP9PwBwÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_hEQ34cXm[$_obfuscate_6Aÿÿ['stepId']] = $_obfuscate_6Aÿÿ;
            }
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                if ( $_obfuscate_XkuTFqZ6Tmkÿ == 0 )
                {
                    if ( $_obfuscate_6Aÿÿ['status'] == 2 && $_obfuscate_6Aÿÿ['pStepId'] != 0 )
                    {
                        $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['statusText'] = $this->f_stepType[$_obfuscate_6Aÿÿ['status']]."(".$this->f_btn_text[$_obfuscate_hEQ34cXm[$_obfuscate_6Aÿÿ['uStepId']]['doBtnText']].")";
                    }
                    else
                    {
                        $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['statusText'] = $this->f_stepType[$_obfuscate_6Aÿÿ['status']];
                    }
                }
                else
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['statusText'] = $this->f_stepType[$_obfuscate_6Aÿÿ['status']];
                }
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['nodename'] = $_obfuscate_6Aÿÿ['stepname'];
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['uname'] = empty( $_obfuscate_dga5p5gjYJ23VQÿÿ[$_obfuscate_6Aÿÿ['uid']]['truename'] ) ? "<span style='color:#FF6600'>".lang( "userNotExist" )."</span>" : $_obfuscate_dga5p5gjYJ23VQÿÿ[$_obfuscate_6Aÿÿ['uid']]['truename'];
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['cuibanName'] = empty( $_obfuscate_dga5p5gjYJ23VQÿÿ[$_obfuscate_6Aÿÿ['uid']]['truename'] ) ? "<span style='color:#FF6600'>".lang( "userNotExist" )."</span>" : $_obfuscate_dga5p5gjYJ23VQÿÿ[$_obfuscate_6Aÿÿ['uid']]['truename'];
                if ( $this->clientType == "PC" )
                {
                    $_obfuscate_hn8oeqVsTHUÿ = "<img src='./resources/images/icons/arrow_right.png' ext:qtip='".lang( "hasBeenEntruste" )."' />";
                    $_obfuscate_D4nNVUJFJQÿÿ = "<img src='./resources/images/icons/user--pencil.png' ext:qtip='".lang( "acceptOfficer" )."' />";
                }
                if ( $this->clientType == "MOB" )
                {
                    $_obfuscate_hn8oeqVsTHUÿ = "=>";
                    $_obfuscate_D4nNVUJFJQÿÿ = "";
                }
                if ( $_obfuscate_6Aÿÿ['proxyUid'] != 0 && ( $_obfuscate_6Aÿÿ['status'] == 2 || $_obfuscate_6Aÿÿ['status'] == 4 ) )
                {
                    if ( $_obfuscate_6Aÿÿ['dealUid'] == $_obfuscate_6Aÿÿ['proxyUid'] )
                    {
                        $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['uname'] .= $_obfuscate_hn8oeqVsTHUÿ.$_obfuscate_dga5p5gjYJ23VQÿÿ[$_obfuscate_6Aÿÿ['proxyUid']]['truename'].$_obfuscate_D4nNVUJFJQÿÿ;
                    }
                    else
                    {
                        $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['uname'] = $_obfuscate_D4nNVUJFJQÿÿ.$_obfuscate_dga5p5gjYJ23VQÿÿ[$_obfuscate_6Aÿÿ['uid']]['truename'];
                        $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['uname'] .= $_obfuscate_hn8oeqVsTHUÿ.$_obfuscate_dga5p5gjYJ23VQÿÿ[$_obfuscate_6Aÿÿ['proxyUid']]['truename'];
                        $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['cuibanName'] = $_obfuscate_dga5p5gjYJ23VQÿÿ[$_obfuscate_6Aÿÿ['uid']]['truename'];
                    }
                }
                else if ( $_obfuscate_6Aÿÿ['proxyUid'] != 0 && $_obfuscate_6Aÿÿ['status'] == 1 )
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['uname'] .= $_obfuscate_hn8oeqVsTHUÿ.$_obfuscate_dga5p5gjYJ23VQÿÿ[$_obfuscate_6Aÿÿ['proxyUid']]['truename'];
                }
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['formatStime'] = date( "Yå¹´mæœˆdæ—¥ H:i", $_obfuscate_6Aÿÿ['stime'] );
                if ( ( $_obfuscate_6Aÿÿ['status'] == 2 || $_obfuscate_6Aÿÿ['status'] == 4 ) && $_obfuscate_6Aÿÿ['uStepId'] != 0 )
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['utime'] = timeformat2( $_obfuscate_6Aÿÿ['etime'] - $_obfuscate_6Aÿÿ['stime'] );
                    if ( $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['utime'] == 0 )
                    {
                        $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['utime'] = "----";
                    }
                }
                else if ( $_obfuscate_6Aÿÿ['uStepId'] != 0 )
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['utime'] = timeformat2( $GLOBALS['CNOA_TIMESTAMP'] - $_obfuscate_6Aÿÿ['stime'] );
                }
                else
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['utime'] = "----";
                }
                if ( $_obfuscate_6Aÿÿ['status'] == 2 && !( $_obfuscate_6Aÿÿ['nStepId'] == 0 ) || ( !( $_obfuscate_6Aÿÿ['status'] == 0 ) && !( $_obfuscate_6Aÿÿ['nStepId'] == 0 ) ) )
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['uname'] = "----";
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['statusText'] = "----";
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['formatStime'] = "";
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['utime'] = "----";
                }
            }
        }
        return $_obfuscate_mPAjEGLn;
    }

    public function api_getEventList( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmkÿ )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_event, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' ORDER BY `uEventId` ASC" );
        if ( $_obfuscate_mPAjEGLn !== FALSE )
        {
            $_obfuscate_PVLK5jra = array( 0 );
            $_obfuscate_QwT4KwrB2wÿÿ = array( 0 );
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_PVLK5jra[] = $_obfuscate_6Aÿÿ['uid'];
                $_obfuscate_QwT4KwrB2wÿÿ[] = $_obfuscate_6Aÿÿ['step'];
            }
            $_obfuscate_dga5p5gjYJ23VQÿÿ = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_PVLK5jra, FALSE );
            $_obfuscate_8NPwP9PwBwÿ = $CNOA_DB->db_select( array( "stepId", "doBtnText" ), $this->t_set_step, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `stepId` IN (".implode( ",", $_obfuscate_QwT4KwrB2wÿÿ ).")" );
            if ( !is_array( $_obfuscate_8NPwP9PwBwÿ ) )
            {
                $_obfuscate_8NPwP9PwBwÿ = array( );
            }
            $_obfuscate_hEQ34cXm = array( );
            foreach ( $_obfuscate_8NPwP9PwBwÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_hEQ34cXm[$_obfuscate_6Aÿÿ['stepId']] = $_obfuscate_6Aÿÿ;
            }
            foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                if ( $_obfuscate_XkuTFqZ6Tmkÿ == 0 )
                {
                    if ( $_obfuscate_6Aÿÿ['type'] == 2 )
                    {
                        $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['typename'] = $this->f_btn_text[$_obfuscate_hEQ34cXm[$_obfuscate_6Aÿÿ['step']]['doBtnText']];
                    }
                    else
                    {
                        $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['typename'] = $this->f_eventType[$_obfuscate_6Aÿÿ['type']];
                    }
                }
                else
                {
                    $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['typename'] = $this->f_eventType[$_obfuscate_6Aÿÿ['type']];
                }
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['uname'] = empty( $_obfuscate_dga5p5gjYJ23VQÿÿ[$_obfuscate_6Aÿÿ['uid']]['truename'] ) ? "<span style=\"color:#FF6600\">ç”¨æˆ·ä¸å­˜åœ¨</span>" : $_obfuscate_dga5p5gjYJ23VQÿÿ[$_obfuscate_6Aÿÿ['uid']]['truename'];
                $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['posttime'] = date( "Yå¹´mæœˆdæ—¥ H:i", $_obfuscate_6Aÿÿ['posttime'] );
            }
        }
        return $_obfuscate_mPAjEGLn;
    }

    public function api_getReadList( $_obfuscate_TlvKhtsoOQÿÿ )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_6RYLWQÿÿ['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_6RYLWQÿÿ['isread'] = 1;
        $_obfuscate_6RYLWQÿÿ['viewtime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $CNOA_DB->db_update( $_obfuscate_6RYLWQÿÿ, $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' AND `touid`='{$_obfuscate_7Ri3}'" );
        $_obfuscate_0WaREsXoZ4wÿ = array( );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_fenfa, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_PVLK5jra = array( 0 );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_PVLK5jra[$_obfuscate_6Aÿÿ['touid']] = $_obfuscate_6Aÿÿ['touid'];
            $_obfuscate_PVLK5jra[$_obfuscate_6Aÿÿ['fenfauid']] = $_obfuscate_6Aÿÿ['fenfauid'];
        }
        $_obfuscate_dga5p5gjYJ23VQÿÿ = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_PVLK5jra );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6b8lIO4y = $_obfuscate_6Aÿÿ['isread'] == 1 ? "<span class=\"cnoa_color_red\">[".lang( "readed" )."]</span>" : "<span class=\"cnoa_color_gray\">[".lang( "unread" )."]</span>";
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['fenfaName'] = $_obfuscate_dga5p5gjYJ23VQÿÿ[$_obfuscate_6Aÿÿ['fenfauid']]['truename'];
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['uname'] = $_obfuscate_6b8lIO4y.( empty( $_obfuscate_dga5p5gjYJ23VQÿÿ[$_obfuscate_6Aÿÿ['touid']]['truename'] ) ? "<span style=\"color:#FF6600\">ç”¨æˆ·ä¸å­˜åœ¨</span>" : $_obfuscate_dga5p5gjYJ23VQÿÿ[$_obfuscate_6Aÿÿ['touid']]['truename'] );
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['sayDate'] = formatdate( $_obfuscate_6Aÿÿ['viewtime'], "Y-m-d H:i" );
            $_obfuscate_mPAjEGLn[$_obfuscate_5wÿÿ]['deptment'] = app::loadapp( "main", "user" )->api_getDeptNameByUid( $_obfuscate_6Aÿÿ['touid'] );
        }
        return $_obfuscate_mPAjEGLn;
    }

    public function api_loadProxyFormData( $_obfuscate_0W8ÿ, $_obfuscate_yiKUFDGCugÿÿ )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_RopcQP_w = $CNOA_DB->db_select( "*", $this->t_set_sort );
        if ( !is_array( $_obfuscate_RopcQP_w ) )
        {
            $_obfuscate_RopcQP_w = array( );
        }
        $_obfuscate_uly_hPh_dQÿÿ = array( );
        foreach ( $_obfuscate_RopcQP_w as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_uly_hPh_dQÿÿ[$_obfuscate_6Aÿÿ['sortId']] = $_obfuscate_6Aÿÿ;
        }
        $_obfuscate_PpZPqh6HEAÿÿ = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_IRFhnYwÿ = "WHERE `fromuid`='".$_obfuscate_yiKUFDGCugÿÿ."' AND ((`etime`=0 AND `stime`<={$_obfuscate_PpZPqh6HEAÿÿ}) OR (`stime`<={$_obfuscate_PpZPqh6HEAÿÿ} AND `etime`>={$_obfuscate_PpZPqh6HEAÿÿ}))";
        $_obfuscate_4WYIiiNEiQyn = $CNOA_DB->db_select( "*", $this->t_use_proxy_flow, $_obfuscate_IRFhnYwÿ );
        if ( !is_array( $_obfuscate_4WYIiiNEiQyn ) )
        {
            $_obfuscate_4WYIiiNEiQyn = array( );
        }
        $_obfuscate_WMVwRv5Dgÿÿ = array( );
        foreach ( $_obfuscate_4WYIiiNEiQyn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['uProxyId'] == $_obfuscate_0W8ÿ )
            {
                $_obfuscate_6Aÿÿ['checked'] = TRUE;
                $_obfuscate_6Aÿÿ['enable'] = TRUE;
            }
            else
            {
                $_obfuscate_6Aÿÿ['checked'] = FALSE;
                $_obfuscate_6Aÿÿ['enable'] = FALSE;
            }
            $_obfuscate_WMVwRv5Dgÿÿ[$_obfuscate_6Aÿÿ['flowId']] = $_obfuscate_6Aÿÿ;
        }
        $_obfuscate_SIUSR4F6 = $CNOA_DB->db_select( array( "sortId", "flowId", "name" ), $this->t_set_flow, "WHERE `status`=1" );
        if ( !is_array( $_obfuscate_SIUSR4F6 ) )
        {
            $_obfuscate_SIUSR4F6 = array( );
        }
        $_obfuscate_8Bnz38wN01cÿ = array( );
        foreach ( $_obfuscate_SIUSR4F6 as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_WMVwRv5Dgÿÿ[$_obfuscate_6Aÿÿ['flowId']]['checked'] === TRUE )
            {
                $_obfuscate_6Aÿÿ['checked'] = TRUE;
            }
            else
            {
                $_obfuscate_6Aÿÿ['checked'] = FALSE;
            }
            if ( $_obfuscate_WMVwRv5Dgÿÿ[$_obfuscate_6Aÿÿ['flowId']]['enable'] === FALSE )
            {
                $_obfuscate_6Aÿÿ['enable'] = FALSE;
            }
            else
            {
                $_obfuscate_6Aÿÿ['enable'] = TRUE;
            }
            $_obfuscate_8Bnz38wN01cÿ[$_obfuscate_6Aÿÿ['sortId']][] = $_obfuscate_6Aÿÿ;
        }
        $_obfuscate_6RYLWQÿÿ = array( );
        foreach ( $_obfuscate_8Bnz38wN01cÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_6RYLWQÿÿ[] = array(
                "sortId" => $_obfuscate_6Aÿÿ[0]['sortId'],
                "sname" => $_obfuscate_uly_hPh_dQÿÿ[$_obfuscate_6Aÿÿ[0]['sortId']]['name'],
                "items" => $_obfuscate_6Aÿÿ
            );
        }
        return $_obfuscate_6RYLWQÿÿ;
    }

    protected function insertEvent( $_obfuscate_JG8GuYÿ )
    {
        global $CNOA_DB;
        if ( !is_array( $_obfuscate_JG8GuYÿ ) )
        {
            return;
        }
        foreach ( $_obfuscate_JG8GuYÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_JG8GuYÿ[$_obfuscate_5wÿÿ] = addslashes( $_obfuscate_6Aÿÿ );
        }
        $_obfuscate_UNQLdMbxTK0ÿ = $CNOA_DB->db_insert( $_obfuscate_JG8GuYÿ, $this->t_use_event );
        return $_obfuscate_UNQLdMbxTK0ÿ;
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
        $_obfuscate_ncdC0pMÿ = getpar( $_POST, "width", 100 );
        $CNOA_DB->db_delete( $this->t_s_print, "WHERE `uid` = '".$_obfuscate_7Ri3."' " );
        $_obfuscate_ncdC0pMÿ = $_obfuscate_ncdC0pMÿ == 0 ? 1 : $_obfuscate_ncdC0pMÿ;
        $CNOA_DB->db_insert( array(
            "uid" => $_obfuscate_7Ri3,
            "width" => $_obfuscate_ncdC0pMÿ
        ), $this->t_s_print );
        msg::callback( TRUE, lang( "successopt" ) );
    }

    protected function printStep( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmkÿ )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mPAjEGLn = $this->api_getStepList( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmkÿ );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_lEGQqwÿÿ = "";
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_lEGQqwÿÿ .= "<tr>";
            $_obfuscate_lEGQqwÿÿ .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6Aÿÿ['stepname']."</td>";
            $_obfuscate_lEGQqwÿÿ .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6Aÿÿ['statusText']."</td>";
            $_obfuscate_lEGQqwÿÿ .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6Aÿÿ['uname']."</br>".$_obfuscate_6Aÿÿ['formatStime']."</td>";
            $_obfuscate_lEGQqwÿÿ .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6Aÿÿ['utime']."</td>";
            $_obfuscate_lEGQqwÿÿ .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6Aÿÿ['say']."</td>";
            $_obfuscate_lEGQqwÿÿ .= "</tr>";
        }
        return $_obfuscate_lEGQqwÿÿ;
    }

    protected function printFs( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        $_obfuscate_5LuNFL5U2xQÿ = $CNOA_DB->db_getone( "*", $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $_obfuscate_1jUa = json_decode( $_obfuscate_5LuNFL5U2xQÿ['attach'], TRUE );
        foreach ( $_obfuscate_1jUa as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_ViKf3gÿÿ .= $_obfuscate_6Aÿÿ.",";
        }
        $_obfuscate_1jUa = rtrim( $_obfuscate_ViKf3gÿÿ, "," );
        $_obfuscate_mLjk2t6lphUÿ = $CNOA_DB->db_select( array( "id" ), "wf_s_field", " WHERE `flowId`=".$_obfuscate_F4AbnVRh." and `otype`='attach'" );
        if ( !empty( $_obfuscate_mLjk2t6lphUÿ ) )
        {
            $_obfuscate_3tiDsnMÿ = "z_wf_t_".$_obfuscate_F4AbnVRh;
            if ( !is_array( $_obfuscate_mLjk2t6lphUÿ ) )
            {
                $_obfuscate_mLjk2t6lphUÿ = array( );
            }
            foreach ( $_obfuscate_mLjk2t6lphUÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_YIq2A8cÿ = "T_".$_obfuscate_6Aÿÿ['id'];
                $_obfuscate_SeV31Qÿÿ = $CNOA_DB->db_getfield( $_obfuscate_YIq2A8cÿ, $_obfuscate_3tiDsnMÿ, " WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
                $_obfuscate_1_pbjTIdLU49 .= $_obfuscate_SeV31Qÿÿ.",";
            }
            $_obfuscate_ViKf3gÿÿ = rtrim( str_replace( "[", "", str_replace( "]", "", $_obfuscate_1_pbjTIdLU49 ) ), "," );
            if ( !empty( $_obfuscate_1jUa ) || !empty( $_obfuscate_ViKf3gÿÿ ) )
            {
                $_obfuscate_1jUa = $_obfuscate_1jUa.",".$_obfuscate_ViKf3gÿÿ;
            }
            else if ( empty( $_obfuscate_1jUa ) && !empty( $_obfuscate_ViKf3gÿÿ ) )
            {
                $_obfuscate_1jUa = $_obfuscate_ViKf3gÿÿ;
            }
            else
            {
                $_obfuscate_1jUa = $_obfuscate_1jUa;
            }
        }
        $_obfuscate_fMfsswÿÿ = $CNOA_DB->db_select( "*", "system_fs", "WHERE `id` IN(".$_obfuscate_1jUa.")" );
        if ( !is_array( $_obfuscate_fMfsswÿÿ ) )
        {
            $_obfuscate_fMfsswÿÿ = array( );
        }
        $_obfuscate_lEGQqwÿÿ = "";
        $_obfuscate__eqrEQÿÿ = array( 0 );
        $_obfuscate_xIiKpDYD = array( );
        foreach ( $_obfuscate_fMfsswÿÿ as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate__eqrEQÿÿ[] = $_obfuscate_6Aÿÿ['uid'];
            $_obfuscate_xIiKpDYD[] = array(
                "filename" => $_obfuscate_6Aÿÿ['oldname'],
                "uid" => $_obfuscate_6Aÿÿ['uid'],
                "date" => formatdate( $_obfuscate_6Aÿÿ['posttime'], "Y-m-d H:i:s" ),
                "id" => $_obfuscate_6Aÿÿ['id']
            );
        }
        $_obfuscate_tqpXCDgÿ = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate__eqrEQÿÿ );
        foreach ( $_obfuscate_xIiKpDYD as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_lEGQqwÿÿ .= "<tr>";
            $_obfuscate_lEGQqwÿÿ .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_xIiKpDYD[$_obfuscate_5wÿÿ]['filename']."</td>";
            $_obfuscate_lEGQqwÿÿ .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_tqpXCDgÿ[$_obfuscate_6Aÿÿ['uid']]['truename']."</td>";
            $_obfuscate_lEGQqwÿÿ .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_xIiKpDYD[$_obfuscate_5wÿÿ]['date']."</td>";
            $_obfuscate_lEGQqwÿÿ .= "<td style = \"display:none\">".$_obfuscate_xIiKpDYD[$_obfuscate_5wÿÿ]['id']."</td>";
            $_obfuscate_lEGQqwÿÿ .= "</tr>";
        }
        return $_obfuscate_lEGQqwÿÿ;
    }

    protected function printEvent( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmkÿ )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mPAjEGLn = $this->api_getEventList( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmkÿ );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_lEGQqwÿÿ = "";
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_lEGQqwÿÿ .= "<tr>";
            $_obfuscate_lEGQqwÿÿ .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6Aÿÿ['typename']."</td>";
            $_obfuscate_lEGQqwÿÿ .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6Aÿÿ['stepname']."</td>";
            $_obfuscate_lEGQqwÿÿ .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6Aÿÿ['uname']."</br>".$_obfuscate_6Aÿÿ['posttime']."</td>";
            $_obfuscate_lEGQqwÿÿ .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6Aÿÿ['say']."</td>";
            $_obfuscate_lEGQqwÿÿ .= "</tr>";
        }
        return $_obfuscate_lEGQqwÿÿ;
    }

    protected function printRead( $_obfuscate_TlvKhtsoOQÿÿ )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mPAjEGLn = $this->api_getReadList( $_obfuscate_TlvKhtsoOQÿÿ );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_lEGQqwÿÿ = "";
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_lEGQqwÿÿ .= "<tr>";
            $_obfuscate_lEGQqwÿÿ .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6Aÿÿ['uname']."</td>";
            $_obfuscate_lEGQqwÿÿ .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6Aÿÿ['deptment']."</td>";
            $_obfuscate_lEGQqwÿÿ .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6Aÿÿ['say']."</td>";
            $_obfuscate_lEGQqwÿÿ .= "<td valign=\"middle\" class=\"field\">".$_obfuscate_6Aÿÿ['sayDate']."</td>";
            $_obfuscate_lEGQqwÿÿ .= "</tr>";
        }
        return $_obfuscate_lEGQqwÿÿ;
    }

    protected function printBase( $_obfuscate_TlvKhtsoOQÿÿ )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_mPAjEGLn = $this->api_getBaseList( $_obfuscate_TlvKhtsoOQÿÿ );
        return $_obfuscate_mPAjEGLn;
    }

    protected function printList( )
    {
        $_obfuscate_TlvKhtsoOQÿÿ = getpar( $_GET, "uFlowId", getpar( $_POST, "uFlowId", 0 ) );
        $_obfuscate_F4AbnVRh = getpar( $_GET, "flowId", getpar( $_POST, "flowId", 0 ) );
        $_obfuscate_XkuTFqZ6Tmkÿ = getpar( $_GET, "flowType", getpar( $_POST, "flowType", 0 ) );
        $_obfuscate_pEvU7Kz2Ywÿÿ = getpar( $_GET, "tplSort", getpar( $_POST, "tplSort", 0 ) );
        ( );
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $this->printBase( $_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_NlQÿ->step = $this->printStep( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmkÿ );
        $_obfuscate_NlQÿ->event = $this->printEvent( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_XkuTFqZ6Tmkÿ );
        $_obfuscate_NlQÿ->read = $this->printRead( $_obfuscate_TlvKhtsoOQÿÿ );
        echo $_obfuscate_NlQÿ->makeJsonData( );
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
            $msg = makedownfileoncelink( "å·¥ä½œæµå¯¼å‡º[".$flow['flowNumber']."]".$ext, "/common/temp/".$htmlTempFile.$ext );
            app::loadapp( "main", "systemLogs" )->api_addLogs( "export", 3605, "äº†æµç¨‹ï¼Œæµç¨‹åç§°[".$flow['flowName']."]ç¼–å·[".$flow['flowNumber']."]" );
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
                $msg = makedownfileoncelink( "å·¥ä½œæµå¯¼å‡º[".$flow['flowNumber']."].jpg", "/common/temp/".$imageTempFile.".jpg" );
            }
            app::loadapp( "main", "systemLogs" )->api_addLogs( "export", 3605, "äº†æµç¨‹ï¼Œæµç¨‹åç§°[".$flow['flowName']."]ç¼–å·[".$flow['flowNumber']."]" );
            msg::callback( TRUE, $msg );
        }
    }

    protected function getProxyUid( $_obfuscate_F4AbnVRh, $_obfuscate_CArovL72wÿÿ )
    {
        global $CNOA_DB;
        $_obfuscate_lQ81YBMÿ = 0;
        $_obfuscate_h8TEbtzlSdi = $CNOA_DB->db_select( "*", $this->t_use_proxy, "WHERE `status`=1 AND `fromuid`='".$_obfuscate_CArovL72wÿÿ."'" );
        if ( !is_array( $_obfuscate_h8TEbtzlSdi ) )
        {
            $_obfuscate_h8TEbtzlSdi = array( );
        }
        $_obfuscate_PpZPqh6HEAÿÿ = $GLOBALS['CNOA_TIMESTAMP'];
        foreach ( $_obfuscate_h8TEbtzlSdi as $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['etime'] == 0 && !( $_obfuscate_6Aÿÿ['stime'] <= $_obfuscate_PpZPqh6HEAÿÿ ) || ( !( $_obfuscate_6Aÿÿ['stime'] <= $_obfuscate_PpZPqh6HEAÿÿ ) && !( $_obfuscate_PpZPqh6HEAÿÿ <= $_obfuscate_6Aÿÿ['etime'] ) ) )
            {
                $_obfuscate_8Bnz38wN01cÿ = json_decode( $_obfuscate_6Aÿÿ['flowId'], TRUE );
                if ( is_array( $_obfuscate_8Bnz38wN01cÿ ) )
                {
                    foreach ( $_obfuscate_8Bnz38wN01cÿ as $_obfuscate_EGUÿ )
                    {
                        if ( $_obfuscate_EGUÿ == $_obfuscate_F4AbnVRh )
                        {
                            $_obfuscate_lQ81YBMÿ = $_obfuscate_6Aÿÿ['touid'];
                        }
                    }
                }
            }
        }
        return $_obfuscate_lQ81YBMÿ;
    }

    protected function getProxyFromuid( $_obfuscate_F4AbnVRh, $_obfuscate_5ZL98vEÿ )
    {
        global $CNOA_DB;
        $_obfuscate_CArovL72wÿÿ = 0;
        $_obfuscate_h8TEbtzlSdi = $CNOA_DB->db_select( "*", $this->t_use_proxy, "WHERE `status`=1 AND `touid`='".$_obfuscate_5ZL98vEÿ."'" );
        if ( !is_array( $_obfuscate_h8TEbtzlSdi ) )
        {
            $_obfuscate_h8TEbtzlSdi = array( );
        }
        $_obfuscate_PpZPqh6HEAÿÿ = $GLOBALS['CNOA_TIMESTAMP'];
        foreach ( $_obfuscate_h8TEbtzlSdi as $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['etime'] == 0 && !( $_obfuscate_6Aÿÿ['stime'] <= $_obfuscate_PpZPqh6HEAÿÿ ) || ( !( $_obfuscate_6Aÿÿ['stime'] <= $_obfuscate_PpZPqh6HEAÿÿ ) && !( $_obfuscate_PpZPqh6HEAÿÿ <= $_obfuscate_6Aÿÿ['etime'] ) ) )
            {
                $_obfuscate_8Bnz38wN01cÿ = json_decode( $_obfuscate_6Aÿÿ['flowId'], TRUE );
                if ( is_array( $_obfuscate_8Bnz38wN01cÿ ) )
                {
                    foreach ( $_obfuscate_8Bnz38wN01cÿ as $_obfuscate_EGUÿ )
                    {
                        if ( $_obfuscate_EGUÿ == $_obfuscate_F4AbnVRh )
                        {
                            $_obfuscate_CArovL72wÿÿ = $_obfuscate_6Aÿÿ['formuid'];
                        }
                    }
                }
            }
        }
        return $_obfuscate_CArovL72wÿÿ;
    }

    protected function insertProxyData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_CArovL72wÿÿ, $_obfuscate_lQ81YBMÿ )
    {
        global $CNOA_DB;
        if ( intval( $_obfuscate_lQ81YBMÿ ) == 0 )
        {
            return;
        }
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_6RYLWQÿÿ['flowId'] = $_obfuscate_F4AbnVRh;
        $_obfuscate_6RYLWQÿÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
        $_obfuscate_6RYLWQÿÿ['fromuid'] = $_obfuscate_CArovL72wÿÿ;
        $_obfuscate_6RYLWQÿÿ['touid'] = $_obfuscate_lQ81YBMÿ;
        $_obfuscate_6RYLWQÿÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_use_proxy_uflow );
    }

    protected function api_cancelFlow( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "id", "uStepId", "uid", "proxyUid", "dealUid", "status", "stepType", "stepname" ), $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_5NhzjnJq_f8ÿ = $_obfuscate_td3BMkoeV0sT = $_obfuscate_wozTGT5K9nKFLM8ÿ = $_obfuscate_Rho2Gip16nFI79JtdQÿÿ = array( );
        $_obfuscate_tC8MNsAzXAÿÿ = $_obfuscate_Y0NtxNfStZgTsQÿÿ = 0;
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_td3BMkoeV0sT[] = $_obfuscate_6Aÿÿ['id'];
            if ( in_array( $_obfuscate_6Aÿÿ['status'], array( 2, 4 ) ) )
            {
                if ( $_obfuscate_6Aÿÿ['proxyUid'] != 0 )
                {
                    $_obfuscate_wozTGT5K9nKFLM8ÿ[$_obfuscate_6Aÿÿ['id']] = $_obfuscate_6Aÿÿ['proxyUid'];
                }
                else if ( $_obfuscate_6Aÿÿ['dealUid'] != 0 )
                {
                    $_obfuscate_wozTGT5K9nKFLM8ÿ[$_obfuscate_6Aÿÿ['id']] = $_obfuscate_6Aÿÿ['dealUid'];
                }
            }
            else if ( $_obfuscate_6Aÿÿ['status'] == 1 )
            {
                if ( $_obfuscate_6Aÿÿ['proxyUid'] != 0 )
                {
                    $_obfuscate_Rho2Gip16nFI79JtdQÿÿ[$_obfuscate_6Aÿÿ['id']] = $_obfuscate_6Aÿÿ['proxyUid'];
                }
                else if ( $_obfuscate_6Aÿÿ['uid'] != 0 )
                {
                    $_obfuscate_Rho2Gip16nFI79JtdQÿÿ[$_obfuscate_6Aÿÿ['id']] = $_obfuscate_6Aÿÿ['uid'];
                }
            }
            if ( $_obfuscate_6Aÿÿ['stepType'] == 1 )
            {
                $_obfuscate_tC8MNsAzXAÿÿ = $_obfuscate_6Aÿÿ['dealUid'];
                $_obfuscate_Y0NtxNfStZgTsQÿÿ = $_obfuscate_6Aÿÿ['id'];
            }
            if ( isset( $_obfuscate_5NhzjnJq_f8ÿ[$_obfuscate_6Aÿÿ['uStepId']] ) )
            {
                if ( $_obfuscate_5NhzjnJq_f8ÿ[$_obfuscate_6Aÿÿ['uStepId']]['id'] < $_obfuscate_6Aÿÿ['id'] )
                {
                    $_obfuscate_5NhzjnJq_f8ÿ[$_obfuscate_6Aÿÿ['uStepId']] = $_obfuscate_6Aÿÿ;
                }
            }
            else
            {
                $_obfuscate_5NhzjnJq_f8ÿ[$_obfuscate_6Aÿÿ['uStepId']] = $_obfuscate_6Aÿÿ;
            }
        }
        unset( $_obfuscate_mPAjEGLn );
        $_obfuscate_JG8GuYÿ = array( );
        $_obfuscate_JG8GuYÿ['uFlowId'] = $_obfuscate_TlvKhtsoOQÿÿ;
        $_obfuscate_JG8GuYÿ['type'] = 3;
        $_obfuscate_JG8GuYÿ['step'] = $_obfuscate_0Ul8BBkt;
        $_obfuscate_JG8GuYÿ['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_JG8GuYÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_JG8GuYÿ['say'] = "";
        $_obfuscate_JG8GuYÿ['stepname'] = $_obfuscate_5NhzjnJq_f8ÿ[$_obfuscate_0Ul8BBkt]['stepname'];
        $this->insertEvent( $_obfuscate_JG8GuYÿ );
        $this->deleteAllNotice( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_td3BMkoeV0sT );
        $_obfuscate_hTew0boWJESy = $CNOA_DB->db_getone( array( "flowName", "flowNumber" ), $this->t_use_flow, "WHERE `uFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        ( $_obfuscate_TlvKhtsoOQÿÿ );
        $_obfuscate_e53ODz04JQÿÿ = new wfCache( );
        $_obfuscate_qZkmBgÿÿ = $_obfuscate_e53ODz04JQÿÿ->getFlow( );
        unset( $_obfuscate_e53ODz04JQÿÿ );
        $_obfuscate_6RYLWQÿÿ['content'] = lang( "flowName" )."[".$_obfuscate_hTew0boWJESy['flowName']."]".lang( "bianHao" )."[".$_obfuscate_hTew0boWJESy['flowNumber']."]".lang( "beRevoked" );
        $_obfuscate_6RYLWQÿÿ['href'] = "&uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ."&flowId={$_obfuscate_F4AbnVRh}&step={$_obfuscate_0Ul8BBkt}";
        unset( $_obfuscate_hTew0boWJESy );
        $_obfuscate_td3BMkoeV0sT = implode( ",", $_obfuscate_td3BMkoeV0sT );
        $CNOA_DB->db_update( array(
            "status" => 4,
            "endtime" => $GLOBALS['CNOA_TIMESTAMP']
        ), $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        $CNOA_DB->db_update( array(
            "status" => 5,
            "etime" => $GLOBALS['CNOA_TIMESTAMP']
        ), $this->t_use_step, "WHERE `id` IN (".$_obfuscate_td3BMkoeV0sT.")" );
        $_obfuscate_eOytqZnoPmMkuTA2Aÿÿ = $CNOA_DB->db_select( "*", $this->t_use_step_child_flow, "WHERE `status` IN (0,1) AND `puFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        if ( $_obfuscate_eOytqZnoPmMkuTA2Aÿÿ )
        {
            $_obfuscate_xHZmyK5cgÿÿ = array( );
            foreach ( $_obfuscate_eOytqZnoPmMkuTA2Aÿÿ as $_obfuscate_6Aÿÿ )
            {
                $this->deleteNotice( "both", $_obfuscate_6Aÿÿ['id'], "child" );
            }
        }
        $CNOA_DB->db_delete( $this->t_use_step_child_flow, "WHERE `status` IN (0,1) AND `puFlowId`='".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        $CNOA_DB->db_update( array( "status" => 4 ), "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        $_obfuscate_etmAKmzA4Rvws_UM = array( );
        if ( $_obfuscate_qZkmBgÿÿ['noticeCancel'] == 1 )
        {
            $_obfuscate_etmAKmzA4Rvws_UM = $_obfuscate_Rho2Gip16nFI79JtdQÿÿ;
        }
        else if ( $_obfuscate_qZkmBgÿÿ['noticeCancel'] == 2 )
        {
            $_obfuscate_etmAKmzA4Rvws_UM = $_obfuscate_Rho2Gip16nFI79JtdQÿÿ;
            $_obfuscate_etmAKmzA4Rvws_UM[$_obfuscate_Y0NtxNfStZgTsQÿÿ] = $_obfuscate_tC8MNsAzXAÿÿ;
        }
        else if ( $_obfuscate_qZkmBgÿÿ['noticeCancel'] == 3 )
        {
            $_obfuscate_etmAKmzA4Rvws_UM = $_obfuscate_wozTGT5K9nKFLM8ÿ;
        }
        unset( $_obfuscate_wozTGT5K9nKFLM8ÿ );
        unset( $_obfuscate_Rho2Gip16nFI79JtdQÿÿ );
        unset( $_obfuscate_Y0NtxNfStZgTsQÿÿ );
        unset( $_obfuscate_tC8MNsAzXAÿÿ );
        foreach ( $_obfuscate_etmAKmzA4Rvws_UM as $_obfuscate_0W8ÿ => $_obfuscate_7Ri3 )
        {
            $_obfuscate_6RYLWQÿÿ['fromid'] = $_obfuscate_0W8ÿ;
            $this->addNotice( "notice", $_obfuscate_7Ri3, $_obfuscate_6RYLWQÿÿ, "cancel" );
        }
        unset( $_obfuscate_etmAKmzA4Rvws_UM );
    }

    protected function checkMgrPermit( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_7Ri3 = 0, $_obfuscate_iuzS = 0, $_obfuscate_y6jH = 0 )
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
        $_obfuscate_v1GprsIz = $CNOA_DB->db_getfield( "sortId", $this->t_use_flow, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."' " );
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( array( "permitId", "type" ), $this->t_set_sort_permit, "WHERE `from` = 'g' AND `sortId` = '".$_obfuscate_v1GprsIz."' " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( $_obfuscate_6Aÿÿ['type'] == "d" && $_obfuscate_iuzS == $_obfuscate_6Aÿÿ['permitId'] )
            {
                return TRUE;
            }
            if ( $_obfuscate_6Aÿÿ['type'] == "s" && $_obfuscate_y6jH == $_obfuscate_6Aÿÿ['permitId'] )
            {
                return TRUE;
            }
            if ( !( $_obfuscate_6Aÿÿ['type'] == "p" ) && !( $_obfuscate_7Ri3 == $_obfuscate_6Aÿÿ['permitId'] ) )
            {
                continue;
            }
            return TRUE;
        }
        return FALSE;
    }

    protected function addNotice( $_obfuscate_LeS8hwÿÿ = "both", $_obfuscate_7Ri3, $_obfuscate_6RYLWQÿÿ, $_obfuscate_IO8hYI15, $_obfuscate_Ybai = 0 )
    {
        $_obfuscate_e7PLR79F = $this->noticeFormat[$_obfuscate_IO8hYI15][$_obfuscate_Ybai];
        global $CNOA_DB;
        $_obfuscate__eqrEQÿÿ = $_obfuscate_gIjdVQÿÿ = array( );
        ( $CNOA_DB, FALSE );
        $_obfuscate_RZYtO9Yÿ = new appAndWechatNotice( );
        $_obfuscate_kIVhqJkÿ = array(
            "from" => $_obfuscate_e7PLR79F['from'],
            "fromtype" => $_obfuscate_e7PLR79F['fromtype'],
            "href" => $_obfuscate_e7PLR79F['href'].$_obfuscate_6RYLWQÿÿ['href']
        );
        if ( is_array( $_obfuscate_7Ri3 ) )
        {
            $_obfuscate_7Ri3 = array_unique( $_obfuscate_7Ri3 );
            foreach ( $_obfuscate_7Ri3 as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                if ( !empty( $_obfuscate_6Aÿÿ ) )
                {
                    $_obfuscate_kIVhqJkÿ['touid'] = $_obfuscate_6Aÿÿ;
                    $_obfuscate_gIjdVQÿÿ[] = $_obfuscate_RZYtO9Yÿ->getAppUrl( $_obfuscate_kIVhqJkÿ, FALSE );
                    $_obfuscate__eqrEQÿÿ[] = $_obfuscate_6Aÿÿ;
                }
            }
        }
        else if ( !empty( $_obfuscate_7Ri3 ) )
        {
            $_obfuscate_kIVhqJkÿ['touid'] = $_obfuscate_7Ri3;
            $_obfuscate_gIjdVQÿÿ = $_obfuscate_RZYtO9Yÿ->getAppUrl( $_obfuscate_kIVhqJkÿ, FALSE );
            $_obfuscate__eqrEQÿÿ = $_obfuscate_7Ri3;
        }
        if ( is_array( $_obfuscate_gIjdVQÿÿ ) )
        {
            foreach ( $_obfuscate_gIjdVQÿÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
            {
                JPush::push( $_obfuscate__eqrEQÿÿ[$_obfuscate_Vwty], $_obfuscate_6RYLWQÿÿ['content'], $_obfuscate_e7PLR79F['title'], $_obfuscate_VgKtFegÿ, "process" );
            }
        }
        else
        {
            JPush::push( $_obfuscate__eqrEQÿÿ, $_obfuscate_6RYLWQÿÿ['content'], $_obfuscate_e7PLR79F['title'], $_obfuscate_gIjdVQÿÿ, "process" );
        }
        switch ( $_obfuscate_LeS8hwÿÿ )
        {
        case "notice" :
            $this->notice( $_obfuscate_7Ri3, $_obfuscate_6RYLWQÿÿ, $_obfuscate_IO8hYI15, $_obfuscate_Ybai );
            break;
        case "todo" :
            $this->todo( $_obfuscate_7Ri3, $_obfuscate_6RYLWQÿÿ, $_obfuscate_IO8hYI15, $_obfuscate_Ybai );
            break;
        case "both" :
            $this->notice( $_obfuscate_7Ri3, $_obfuscate_6RYLWQÿÿ, $_obfuscate_IO8hYI15, $_obfuscate_Ybai );
            $this->todo( $_obfuscate_7Ri3, $_obfuscate_6RYLWQÿÿ, $_obfuscate_IO8hYI15, $_obfuscate_Ybai );
        }
    }

    private function notice( $_obfuscate_7Ri3, $_obfuscate_6RYLWQÿÿ, $_obfuscate_IO8hYI15, $_obfuscate_Ybai )
    {
        global $CNOA_DB;
        $_obfuscate_e7PLR79F = $this->noticeFormat[$_obfuscate_IO8hYI15][$_obfuscate_Ybai];
        $_obfuscate_vholQÿÿ = $_obfuscate_e7PLR79F['from'];
        if ( empty( $_obfuscate_6RYLWQÿÿ['time'] ) )
        {
            $_obfuscate_5c1ea0lUBl8z4Qÿÿ = $GLOBALS['CNOA_TIMESTAMP'];
        }
        else
        {
            $_obfuscate_5c1ea0lUBl8z4Qÿÿ = $_obfuscate_6RYLWQÿÿ['time'];
        }
        $_obfuscate_pVruBBT3a8oÿ = $_obfuscate_6RYLWQÿÿ['fromid'];
        $_obfuscate_gIjdVQÿÿ = $_obfuscate_e7PLR79F['href'].$_obfuscate_6RYLWQÿÿ['href'];
        $_obfuscate_obqvewÿÿ = $_obfuscate_e7PLR79F['title'];
        $_obfuscate__WwKzYz1wAÿÿ = addslashes( $_obfuscate_6RYLWQÿÿ['content'] );
        $_obfuscate_3tiDsnMÿ = $_obfuscate_e7PLR79F['table'];
        $_obfuscate_9dRdazrpecÿ = $_obfuscate_e7PLR79F['fromtype'];
        if ( is_array( $_obfuscate_7Ri3 ) )
        {
            $_obfuscate_7Ri3 = array_unique( $_obfuscate_7Ri3 );
            foreach ( $_obfuscate_7Ri3 as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                if ( !empty( $_obfuscate_6Aÿÿ ) )
                {
                    notice::add( $_obfuscate_6Aÿÿ, $_obfuscate_obqvewÿÿ, $_obfuscate__WwKzYz1wAÿÿ, $_obfuscate_gIjdVQÿÿ, $_obfuscate_5c1ea0lUBl8z4Qÿÿ, $_obfuscate_vholQÿÿ, $_obfuscate_pVruBBT3a8oÿ, 0, $_obfuscate_3tiDsnMÿ, $_obfuscate_9dRdazrpecÿ );
                }
            }
        }
        else if ( !empty( $_obfuscate_7Ri3 ) )
        {
            notice::add( $_obfuscate_7Ri3, $_obfuscate_obqvewÿÿ, $_obfuscate__WwKzYz1wAÿÿ, $_obfuscate_gIjdVQÿÿ, $_obfuscate_5c1ea0lUBl8z4Qÿÿ, $_obfuscate_vholQÿÿ, $_obfuscate_pVruBBT3a8oÿ, 0, $_obfuscate_3tiDsnMÿ, $_obfuscate_9dRdazrpecÿ );
        }
    }

    private function todo( $_obfuscate_7Ri3, $_obfuscate_6RYLWQÿÿ, $_obfuscate_IO8hYI15, $_obfuscate_Ybai )
    {
        global $CNOA_DB;
        $_obfuscate_e7PLR79F = $this->noticeFormat[$_obfuscate_IO8hYI15][$_obfuscate_Ybai];
        $_obfuscate_0AITFwÿÿ['from'] = $_obfuscate_e7PLR79F['from'];
        if ( empty( $_obfuscate_6RYLWQÿÿ['time'] ) )
        {
            $_obfuscate_0AITFwÿÿ['fromtime'] = $GLOBALS['CNOA_TIMESTAMP'];
        }
        else
        {
            $_obfuscate_0AITFwÿÿ['fromtime'] = $_obfuscate_6RYLWQÿÿ['time'];
        }
        if ( !empty( $_obfuscate_e7PLR79F['href2'] ) )
        {
            $_obfuscate_0AITFwÿÿ['href2'] = $_obfuscate_e7PLR79F['href2'].$_obfuscate_6RYLWQÿÿ['href'];
        }
        $_obfuscate_0AITFwÿÿ['fromid'] = $_obfuscate_6RYLWQÿÿ['fromid'];
        $_obfuscate_0AITFwÿÿ['href'] = $_obfuscate_e7PLR79F['href'].$_obfuscate_6RYLWQÿÿ['href'];
        $_obfuscate_0AITFwÿÿ['title'] = $_obfuscate_e7PLR79F['title'];
        $_obfuscate_0AITFwÿÿ['content'] = addslashes( $_obfuscate_6RYLWQÿÿ['content'] );
        $_obfuscate_0AITFwÿÿ['operate'] = 0;
        $_obfuscate_0AITFwÿÿ['funname'] = $_obfuscate_e7PLR79F['funname'];
        $_obfuscate_0AITFwÿÿ['move'] = $_obfuscate_e7PLR79F['move'];
        $_obfuscate_0AITFwÿÿ['table'] = $_obfuscate_e7PLR79F['table'];
        $_obfuscate_0AITFwÿÿ['fromtype'] = $_obfuscate_e7PLR79F['fromtype'];
        if ( is_array( $_obfuscate_7Ri3 ) )
        {
            $_obfuscate_7Ri3 = array_unique( $_obfuscate_7Ri3 );
            foreach ( $_obfuscate_7Ri3 as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                if ( !empty( $_obfuscate_6Aÿÿ ) )
                {
                    $_obfuscate_0AITFwÿÿ['touid'] = $_obfuscate_6Aÿÿ;
                    notice::add2( $_obfuscate_0AITFwÿÿ );
                }
            }
        }
        else if ( !empty( $_obfuscate_7Ri3 ) )
        {
            $_obfuscate_0AITFwÿÿ['touid'] = $_obfuscate_7Ri3;
            notice::add2( $_obfuscate_0AITFwÿÿ );
        }
    }

    public function doneAll( $_obfuscate_LeS8hwÿÿ = "both", $_obfuscate_0W8ÿ, $_obfuscate_IO8hYI15, $_obfuscate_Ybai = 0, $_obfuscate_NB2vnCJktMSZ = "" )
    {
        global $CNOA_DB;
        $_obfuscate_e7PLR79F = $this->noticeFormat[$_obfuscate_IO8hYI15][$_obfuscate_Ybai];
        $_obfuscate_3tiDsnMÿ = $_obfuscate_e7PLR79F['table'];
        $_obfuscate_OKFUMuZQtcKCuwÿÿ = $_obfuscate_e7PLR79F['fromtype'];
        switch ( $_obfuscate_LeS8hwÿÿ )
        {
        case "notice" :
            notice::donen( $_obfuscate_0W8ÿ, $_obfuscate_3tiDsnMÿ, $_obfuscate_OKFUMuZQtcKCuwÿÿ );
            break;
        case "todo" :
            notice::donet( $_obfuscate_0W8ÿ, $_obfuscate_3tiDsnMÿ, $_obfuscate_OKFUMuZQtcKCuwÿÿ );
            break;
        case "both" :
            notice::donen( $_obfuscate_0W8ÿ, $_obfuscate_3tiDsnMÿ, $_obfuscate_OKFUMuZQtcKCuwÿÿ );
            notice::donet( $_obfuscate_0W8ÿ, $_obfuscate_3tiDsnMÿ, $_obfuscate_OKFUMuZQtcKCuwÿÿ );
        }
        $CNOA_DB->db_delete( "system_notice", "WHERE `sourceid`=".$_obfuscate_0W8ÿ." AND `fromtable` = '{$_obfuscate_3tiDsnMÿ}' AND `fromtype` = '{$_obfuscate_OKFUMuZQtcKCuwÿÿ}' " );
    }

    public function deleteAllNotice( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_QwT4KwrB2wÿÿ, $_obfuscate_F6OhcOnPhJV3hjhz = "" )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        foreach ( $_obfuscate_QwT4KwrB2wÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $this->deleteNotice( "both", $_obfuscate_6Aÿÿ, "todo" );
            $this->deleteNotice( "both", $_obfuscate_6Aÿÿ, "trust" );
            $this->deleteNotice( "both", $_obfuscate_6Aÿÿ, "tuihui" );
            $this->deleteNotice( "both", $_obfuscate_6Aÿÿ, "tuihui", 1 );
            $this->deleteNotice( "both", $_obfuscate_6Aÿÿ, "tuihui", 2 );
            $this->deleteNotice( "both", $_obfuscate_6Aÿÿ, "stop", 0 );
            $this->deleteNotice( "both", $_obfuscate_6Aÿÿ, "stop", 1 );
            $this->deleteNotice( "both", $_obfuscate_6Aÿÿ, "stop", 2 );
            $this->deleteNotice( "both", $_obfuscate_6Aÿÿ, "callback" );
            $this->deleteNotice( "both", $_obfuscate_6Aÿÿ, "cancel" );
            $this->deleteNotice( "both", $_obfuscate_6Aÿÿ, "huiqian", 1 );
            $this->deleteNotice( "both", $_obfuscate_6Aÿÿ, "stop" );
            $this->deleteNotice( "both", $_obfuscate_6Aÿÿ, "stop", 1 );
            $this->deleteNotice( "both", $_obfuscate_6Aÿÿ, "stop", 2 );
        }
        $this->deleteNotice( "both", $_obfuscate_TlvKhtsoOQÿÿ, "done" );
        $this->deleteNotice( "both", $_obfuscate_TlvKhtsoOQÿÿ, "fenfa" );
        $this->deleteNotice( "both", $_obfuscate_TlvKhtsoOQÿÿ, "comment" );
        $this->deleteNotice( "both", $_obfuscate_TlvKhtsoOQÿÿ, "cancel" );
        $this->deleteNotice( "both", $_obfuscate_TlvKhtsoOQÿÿ, "warn" );
        if ( !empty( $_obfuscate_F6OhcOnPhJV3hjhz ) )
        {
            foreach ( $_obfuscate_F6OhcOnPhJV3hjhz as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $this->deleteNotice( "both", $_obfuscate_6Aÿÿ, "huiqian" );
            }
        }
    }

    public function deleteNotice( $_obfuscate_LeS8hwÿÿ = "both", $_obfuscate_0W8ÿ, $_obfuscate_IO8hYI15, $_obfuscate_Ybai = 0 )
    {
        $_obfuscate_e7PLR79F = $this->noticeFormat[$_obfuscate_IO8hYI15][$_obfuscate_Ybai];
        $_obfuscate_3tiDsnMÿ = $_obfuscate_e7PLR79F['table'];
        $_obfuscate__5HBFZgÿ = $_obfuscate_e7PLR79F['fromtype'];
        switch ( $_obfuscate_LeS8hwÿÿ )
        {
        case "notice" :
            notice::deletenotice( $_obfuscate_0W8ÿ, 0, $_obfuscate_3tiDsnMÿ, $_obfuscate__5HBFZgÿ );
            break;
        case "todo" :
            notice::deletenotice( 0, $_obfuscate_0W8ÿ, $_obfuscate_3tiDsnMÿ, $_obfuscate__5HBFZgÿ );
            break;
        case "both" :
            notice::deletenotice( $_obfuscate_0W8ÿ, $_obfuscate_0W8ÿ, $_obfuscate_3tiDsnMÿ, $_obfuscate__5HBFZgÿ );
        }
    }

    protected function __getOdata( $_obfuscate_p5ZWxr4ÿ )
    {
        return json_decode( str_replace( "'", "\"", $_obfuscate_p5ZWxr4ÿ ), TRUE );
    }

    protected function __formatDatetime( $_obfuscate_e7PLR79F, $_obfuscate_c6UELgÿÿ = "" )
    {
        if ( empty( $_obfuscate_c6UELgÿÿ ) )
        {
            $_obfuscate_c6UELgÿÿ = $GLOBALS['CNOA_TIMESTAMP'];
        }
        switch ( $_obfuscate_e7PLR79F )
        {
        case "100" :
            $_obfuscate_6RYLWQÿÿ = date( "Y-m-d", $_obfuscate_c6UELgÿÿ );
            return $_obfuscate_6RYLWQÿÿ;
        case "110" :
            $_obfuscate_6RYLWQÿÿ = date( "Yå¹´mæœˆdæ—¥", $_obfuscate_c6UELgÿÿ );
            return $_obfuscate_6RYLWQÿÿ;
        case "120" :
            $_obfuscate_6RYLWQÿÿ = date( "Ymd", $_obfuscate_c6UELgÿÿ );
            return $_obfuscate_6RYLWQÿÿ;
        case "130" :
            $_obfuscate_6RYLWQÿÿ = date( "Y/m/d", $_obfuscate_c6UELgÿÿ );
            return $_obfuscate_6RYLWQÿÿ;
        case "140" :
            $_obfuscate_6RYLWQÿÿ = date( "Y.m.d", $_obfuscate_c6UELgÿÿ );
            return $_obfuscate_6RYLWQÿÿ;
        case "150" :
            $_obfuscate_6RYLWQÿÿ = date( "Yå¹´mæœˆ", $_obfuscate_c6UELgÿÿ );
            return $_obfuscate_6RYLWQÿÿ;
        case "160" :
            $_obfuscate_6RYLWQÿÿ = date( "mæœˆdæ—¥", $_obfuscate_c6UELgÿÿ );
            return $_obfuscate_6RYLWQÿÿ;
        case "190" :
            $_obfuscate_6RYLWQÿÿ = date( "Y-m-d H:i", $_obfuscate_c6UELgÿÿ );
            return $_obfuscate_6RYLWQÿÿ;
        case "200" :
            $_obfuscate_6RYLWQÿÿ = date( "yå¹´mæœˆ Hæ—¶iåˆ†", $_obfuscate_c6UELgÿÿ );
            return $_obfuscate_6RYLWQÿÿ;
        case "300" :
            $_obfuscate_6RYLWQÿÿ = date( "Yå¹´mæœˆdæ—¥ Hæ—¶iåˆ†", $_obfuscate_c6UELgÿÿ );
            return $_obfuscate_6RYLWQÿÿ;
        case "400" :
            $_obfuscate_6RYLWQÿÿ = date( "Yå¹´mæœˆdæ—¥ Hæ—¶iåˆ†sç§’", $_obfuscate_c6UELgÿÿ );
        }
        return $_obfuscate_6RYLWQÿÿ;
    }

    protected function __formatTime( $_obfuscate_e7PLR79F, $_obfuscate_c6UELgÿÿ = "" )
    {
        if ( empty( $_obfuscate_c6UELgÿÿ ) )
        {
            $_obfuscate_c6UELgÿÿ = $GLOBALS['CNOA_TIMESTAMP'];
        }
        switch ( $_obfuscate_e7PLR79F )
        {
        case "100" :
            $_obfuscate_6RYLWQÿÿ = date( "H:i", $_obfuscate_c6UELgÿÿ );
            return $_obfuscate_6RYLWQÿÿ;
        case "200" :
            $_obfuscate_6RYLWQÿÿ = date( "h:i A", $_obfuscate_c6UELgÿÿ );
            return $_obfuscate_6RYLWQÿÿ;
        case "300" :
            $_obfuscate_6RYLWQÿÿ = date( "h:i", $_obfuscate_c6UELgÿÿ );
            if ( date( "A", $_obfuscate_c6UELgÿÿ ) == "AM" )
            {
                $_obfuscate_6RYLWQÿÿ .= "ä¸Šåˆ";
                return $_obfuscate_6RYLWQÿÿ;
            }
            $_obfuscate_6RYLWQÿÿ .= "ä¸‹åˆ";
        }
        return $_obfuscate_6RYLWQÿÿ;
    }

    protected function __getFlowType( $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->db_getone( "*", $this->t_set_flow, "WHERE `flowId`='".$_obfuscate_F4AbnVRh."'" );
        $_obfuscate_HLysAWJVREÿ = array( );
        if ( !empty( $_obfuscate_7qDAYo85aGAÿ ) )
        {
            $_obfuscate_HLysAWJVREÿ = array(
                "flowType" => $_obfuscate_7qDAYo85aGAÿ['flowType'],
                "tplSort" => $_obfuscate_7qDAYo85aGAÿ['tplSort']
            );
        }
        return $_obfuscate_HLysAWJVREÿ;
    }

    protected function api_loadTemplateFile( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_XkuTFqZ6Tmkÿ, $_obfuscate_pEvU7Kz2Ywÿÿ, $_obfuscate_jTbXTguM6pC9CAÿÿ )
    {
        global $CNOA_DB;
        if ( $_obfuscate_XkuTFqZ6Tmkÿ == 0 )
        {
            $_obfuscate_o6LA2yPirJIreFAÿ = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/doc.history.0.php" );
            $_obfuscate_U9NJHZRq6Jr7T_Aÿ = CNOA_PATH_FILE.( "/common/wf/set/".$_obfuscate_F4AbnVRh."/xls.history.0.php" );
        }
        else
        {
            $_obfuscate_o6LA2yPirJIreFAÿ = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQÿÿ}/doc.history.0.php" );
            $_obfuscate_U9NJHZRq6Jr7T_Aÿ = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQÿÿ}/xls.history.0.php" );
        }
        if ( $_obfuscate_jTbXTguM6pC9CAÿÿ == "add" )
        {
            if ( $_obfuscate_pEvU7Kz2Ywÿÿ == 1 || $_obfuscate_pEvU7Kz2Ywÿÿ == 3 )
            {
                if ( file_exists( $_obfuscate_o6LA2yPirJIreFAÿ ) )
                {
                    $_obfuscate_6hS1Rwÿÿ = @file_get_contents( $_obfuscate_o6LA2yPirJIreFAÿ );
                }
                else
                {
                    mkdirs( dirname( $_obfuscate_o6LA2yPirJIreFAÿ ) );
                    $_obfuscate_6hS1Rwÿÿ = @file_get_contents( CNOA_PATH."/resources/empty.doc" );
                }
            }
            else if ( file_exists( $_obfuscate_U9NJHZRq6Jr7T_Aÿ ) )
            {
                $_obfuscate_6hS1Rwÿÿ = @file_get_contents( $_obfuscate_U9NJHZRq6Jr7T_Aÿ );
            }
            else
            {
                mkdirs( dirname( $_obfuscate_U9NJHZRq6Jr7T_Aÿ ) );
                $_obfuscate_6hS1Rwÿÿ = @file_get_contents( CNOA_PATH."/resources/empty.xls" );
            }
        }
        else
        {
            $_obfuscate_Xz9QCGd6R6zz76K0kKbP = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQÿÿ}/doc.history.0.php" );
            $_obfuscate_UTO1M21cc2PuEITQ7av = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQÿÿ}/xls.history.0.php" );
            if ( $_obfuscate_pEvU7Kz2Ywÿÿ == 1 || $_obfuscate_pEvU7Kz2Ywÿÿ == 3 )
            {
                if ( file_exists( $_obfuscate_Xz9QCGd6R6zz76K0kKbP ) )
                {
                    $_obfuscate_6hS1Rwÿÿ = @file_get_contents( $_obfuscate_Xz9QCGd6R6zz76K0kKbP );
                }
            }
            else if ( file_exists( $_obfuscate_UTO1M21cc2PuEITQ7av ) )
            {
                $_obfuscate_6hS1Rwÿÿ = @file_get_contents( $_obfuscate_UTO1M21cc2PuEITQ7av );
            }
        }
        echo $_obfuscate_6hS1Rwÿÿ;
        exit( );
    }

    protected function api_saveTemplateData( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_LeS8hwÿÿ )
    {
        global $CNOA_DB;
        $_obfuscate_zfubNC9lKJsÿ = strtolower( strrchr( $_FILES['msOffice']['name'], "." ) );
        if ( !in_array( strtolower( $_obfuscate_zfubNC9lKJsÿ ), array( ".doc", ".docx", ".xls", ".xlsx" ) ) )
        {
            echo "0";
            exit( );
        }
        if ( $_obfuscate_LeS8hwÿÿ == "1" || $_obfuscate_LeS8hwÿÿ == "3" )
        {
            $_obfuscate_7tmDAr7acvgDxwÿÿ = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQÿÿ}/doc.history.0.php" );
            mkdirs( dirname( $_obfuscate_7tmDAr7acvgDxwÿÿ ) );
            if ( !cnoa_move_uploaded_file( $_FILES['msOffice']['tmp_name'], $_obfuscate_7tmDAr7acvgDxwÿÿ ) )
            {
                echo "0";
                exit( );
            }
        }
        else
        {
            $_obfuscate_7tmDAr7acvgDxwÿÿ = CNOA_PATH_FILE.( "/common/wf/use/".$_obfuscate_F4AbnVRh."/{$_obfuscate_TlvKhtsoOQÿÿ}/xls.history.0.php" );
            mkdirs( dirname( $_obfuscate_7tmDAr7acvgDxwÿÿ ) );
            if ( !cnoa_move_uploaded_file( $_FILES['msOffice']['tmp_name'], $_obfuscate_7tmDAr7acvgDxwÿÿ ) )
            {
                echo "0";
                exit( );
            }
        }
        echo "1";
        exit( );
    }

    protected function getAllStepByUFlowId( $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_eLlzdwÿÿ = "all" )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_use_step, "WHERE `uFlowId` = '".$_obfuscate_TlvKhtsoOQÿÿ."'" );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_5NhzjnJq_f8ÿ = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_6Aÿÿ )
        {
            if ( isset( $_obfuscate_5NhzjnJq_f8ÿ[$_obfuscate_6Aÿÿ['uStepId']] ) )
            {
                if ( !( $_obfuscate_5NhzjnJq_f8ÿ[$_obfuscate_6Aÿÿ['uStepId']]['id'] < $_obfuscate_6Aÿÿ['id'] ) && !( $_obfuscate_5NhzjnJq_f8ÿ[$_obfuscate_6Aÿÿ['uStepId']]['dealUid'] == 0 ) )
                {
                    $_obfuscate_5NhzjnJq_f8ÿ[$_obfuscate_6Aÿÿ['uStepId']] = $_obfuscate_6Aÿÿ;
                }
            }
            else
            {
                $_obfuscate_5NhzjnJq_f8ÿ[$_obfuscate_6Aÿÿ['uStepId']] = $_obfuscate_6Aÿÿ;
            }
        }
        unset( $_obfuscate_mPAjEGLn );
        $_obfuscate_HYI4w55m58H2WjCs['stepInfo'] = $_obfuscate_5NhzjnJq_f8ÿ;
        return $_obfuscate_HYI4w55m58H2WjCs;
    }

    protected function _saveFormFieldInfo( $_obfuscate_uGltphXQjCRWoAÿÿ = "", $_obfuscate_0cocFTVhmhKt8lwÿ = "", $_obfuscate_TlvKhtsoOQÿÿ = 0 )
    {
        global $CNOA_DB;
        $_obfuscate_JQJwE4USnB0ÿ = array( );
        $_obfuscate_V7H2J5ahgÿÿ = array( );
        $_obfuscate_u_DK_o5AB8le = array( );
        $_obfuscate_BqBV6WSz3wel0ZDw = array( );
        $_obfuscate_FYo_0_BVp9xjgDsÿ = array( );
        $_obfuscate_piwqe2DnH9mIPU0P = array( );
        $_obfuscate_vddvYsrvcSVy = array( );
        $_obfuscate_SYIrzK6Qi3Pggÿÿ = array( );
        ( );
        $_obfuscate_2ggÿ = new fs( );
        $_obfuscate_St0kagJ6AtprGsÿ = getpar( $_POST, "filesUpload", array( ) );
        if ( $_obfuscate_St0kagJ6AtprGsÿ )
        {
            foreach ( $_obfuscate_St0kagJ6AtprGsÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
            {
                $_obfuscate_vddvYsrvcSVy["wf_field_".$_obfuscate_Vwty] = $_obfuscate_VgKtFegÿ;
            }
            foreach ( $_obfuscate_vddvYsrvcSVy as $_obfuscate_3QYÿ => $_obfuscate_EGUÿ )
            {
                $_obfuscate_SYIrzK6Qi3Pggÿÿ[$_obfuscate_3QYÿ] = json_encode( $_obfuscate_2ggÿ->add( $_obfuscate_EGUÿ, 1 ) );
            }
        }
        foreach ( $_obfuscate_SYIrzK6Qi3Pggÿÿ as $_obfuscate_nJgÿ => $_obfuscate_NZMÿ )
        {
            if ( ereg( "wf_field_", $_obfuscate_nJgÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_field_", "", $_obfuscate_nJgÿ );
                if ( !is_array( $_obfuscate_uGltphXQjCRWoAÿÿ ) )
                {
                    $_obfuscate_JQJwE4USnB0ÿ['normal'][$_obfuscate_gfGsQGKrGgÿÿ] = $_obfuscate_NZMÿ;
                }
                else if ( in_array( $_obfuscate_gfGsQGKrGgÿÿ, $_obfuscate_uGltphXQjCRWoAÿÿ ) )
                {
                    $_obfuscate_JQJwE4USnB0ÿ['normal'][$_obfuscate_gfGsQGKrGgÿÿ] = $_obfuscate_NZMÿ;
                }
            }
        }
        if ( $_obfuscate_JQJwE4USnB0ÿ && $_obfuscate_TlvKhtsoOQÿÿ )
        {
            foreach ( $_obfuscate_JQJwE4USnB0ÿ as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
            {
                foreach ( $_obfuscate_VgKtFegÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
                {
                    $_obfuscate_MAduV4GmcAN9 = $CNOA_DB->db_getfield( "T_".$_obfuscate_5wÿÿ, "z_wf_t_".$this->flowId, "WHERE uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ );
                    if ( $_obfuscate_MAduV4GmcAN9 )
                    {
                        $_obfuscate_MAduV4GmcAN9 = substr( $_obfuscate_MAduV4GmcAN9, 1, -1 );
                        $_obfuscate_gQÿÿ = substr( $_obfuscate_6Aÿÿ, 1, -1 );
                        $_obfuscate_Ihkaz3Fr28zrS4ÿ = "[".$_obfuscate_MAduV4GmcAN9.",".$_obfuscate_gQÿÿ."]";
                        $_obfuscate_JQJwE4USnB0ÿ[$_obfuscate_Vwty][$_obfuscate_5wÿÿ] = $_obfuscate_Ihkaz3Fr28zrS4ÿ;
                    }
                }
            }
        }
        foreach ( $GLOBALS['_POST'] as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            if ( ereg( "wf_field_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_field_", "", $_obfuscate_5wÿÿ );
                if ( !is_array( $_obfuscate_uGltphXQjCRWoAÿÿ ) )
                {
                    $_obfuscate_JQJwE4USnB0ÿ['normal'][$_obfuscate_gfGsQGKrGgÿÿ] = $_obfuscate_6Aÿÿ;
                }
                else if ( in_array( $_obfuscate_gfGsQGKrGgÿÿ, $_obfuscate_uGltphXQjCRWoAÿÿ ) )
                {
                    $_obfuscate_JQJwE4USnB0ÿ['normal'][$_obfuscate_gfGsQGKrGgÿÿ] = $_obfuscate_6Aÿÿ;
                }
            }
            else if ( ereg( "wf_fieldJ_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_fieldJ_", "", $_obfuscate_5wÿÿ );
                $_obfuscate_JQJwE4USnB0ÿ['normal'][$_obfuscate_gfGsQGKrGgÿÿ] = $_obfuscate_6Aÿÿ;
            }
            else if ( ereg( "wf_fieldC_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_fieldC_", "", $_obfuscate_5wÿÿ );
                $_obfuscate_SeV31Qÿÿ = explode( "_", $_obfuscate_gfGsQGKrGgÿÿ );
                $_obfuscate_V7H2J5ahgÿÿ[$_obfuscate_SeV31Qÿÿ[0]][$_obfuscate_SeV31Qÿÿ[1]] = $_obfuscate_6Aÿÿ;
            }
            else if ( ereg( "wf_detail_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_detail_", "", $_obfuscate_5wÿÿ );
                $_obfuscate_SeV31Qÿÿ = explode( "_", $_obfuscate_gfGsQGKrGgÿÿ );
                if ( !is_array( $_obfuscate_0cocFTVhmhKt8lwÿ ) )
                {
                    $_obfuscate_JQJwE4USnB0ÿ['detail'][$_obfuscate_SeV31Qÿÿ[0]][$_obfuscate_SeV31Qÿÿ[1]] = $_obfuscate_6Aÿÿ;
                }
                else if ( in_array( $_obfuscate_SeV31Qÿÿ[1], $_obfuscate_0cocFTVhmhKt8lwÿ ) )
                {
                    $_obfuscate_JQJwE4USnB0ÿ['detail'][$_obfuscate_SeV31Qÿÿ[0]][$_obfuscate_SeV31Qÿÿ[1]] = $_obfuscate_6Aÿÿ;
                }
            }
            else if ( ereg( "wf_detailJ_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_detailJ_", "", $_obfuscate_5wÿÿ );
                $_obfuscate_SeV31Qÿÿ = explode( "_", $_obfuscate_gfGsQGKrGgÿÿ );
                $_obfuscate_JQJwE4USnB0ÿ['detail'][$_obfuscate_SeV31Qÿÿ[0]][$_obfuscate_SeV31Qÿÿ[1]] = $_obfuscate_6Aÿÿ;
            }
            else if ( ereg( "wf_detailC_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_detailC_", "", $_obfuscate_5wÿÿ );
                $_obfuscate_SeV31Qÿÿ = explode( "_", $_obfuscate_gfGsQGKrGgÿÿ );
                $_obfuscate_u_DK_o5AB8le[$_obfuscate_SeV31Qÿÿ[0]][$_obfuscate_SeV31Qÿÿ[1]][] = $_obfuscate_6Aÿÿ;
            }
            else if ( ereg( "wf_detailbid_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_detailbid_", "", $_obfuscate_5wÿÿ );
                $_obfuscate_SeV31Qÿÿ = explode( "_", $_obfuscate_gfGsQGKrGgÿÿ );
                $_obfuscate_BqBV6WSz3wel0ZDw[intval( $_obfuscate_SeV31Qÿÿ[1] )][intval( $_obfuscate_SeV31Qÿÿ[0] )] = $_obfuscate_6Aÿÿ;
            }
            else if ( ereg( "wf_detailmax_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_detailmax_", "", $_obfuscate_5wÿÿ );
                $_obfuscate_SeV31Qÿÿ = explode( "_", $_obfuscate_gfGsQGKrGgÿÿ );
                $_obfuscate_piwqe2DnH9mIPU0P[$_obfuscate_SeV31Qÿÿ[1]."_".$_obfuscate_SeV31Qÿÿ[0]] = $_obfuscate_6Aÿÿ;
            }
            else if ( ereg( "detailid_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_FYo_0_BVp9xjgDsÿ[] = intval( str_replace( "detailid_", "", $_obfuscate_5wÿÿ ) );
            }
            else if ( ereg( "wf_attach_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_IuoXR2yOaxkRDwÿÿ[] = intval( str_replace( "wf_attach_", "", $_obfuscate_5wÿÿ ) );
            }
            else if ( ereg( "wf_fieldS_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_fieldS_", "", $_obfuscate_5wÿÿ );
                if ( !empty( $_obfuscate_6Aÿÿ ) )
                {
                    $_obfuscate_6Aÿÿ = explode( ";", $_obfuscate_6Aÿÿ );
                    $_obfuscate_kCxvBLni6Qÿÿ = array( );
                    foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_EGUÿ )
                    {
                        if ( !empty( $_obfuscate_EGUÿ ) )
                        {
                            if ( stripos( $_obfuscate_EGUÿ, "seal" ) )
                            {
                                $_obfuscate_EGUÿ = "seal:".$_obfuscate_EGUÿ;
                            }
                            list( $_obfuscate_Vwty, $_obfuscate_TAxu ) = explode( ":", $_obfuscate_EGUÿ );
                            $_obfuscate_kCxvBLni6Qÿÿ[$_obfuscate_Vwty] = $_obfuscate_TAxu;
                        }
                    }
                    $_obfuscate_JQJwE4USnB0ÿ['normal'][$_obfuscate_gfGsQGKrGgÿÿ] = json_encode( $_obfuscate_kCxvBLni6Qÿÿ );
                }
            }
            else if ( ereg( "wf_fieldpic_", $_obfuscate_5wÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = str_replace( "wf_fieldpic_", "", $_obfuscate_5wÿÿ );
                if ( ereg( "editpic", $_obfuscate_6Aÿÿ ) )
                {
                    $_obfuscate_JTe7jJ4eGW8ÿ = str_replace( "{$GLOBALS['URL_FILE']}/editpic/", "", $_obfuscate_6Aÿÿ );
                    $_obfuscate_GsJ20flAQÿÿ = $GLOBALS['URL_FILE']."/common/wf/form/".getpar( $_POST, "flowId", 0 );
                    @mkdirs( CNOA_PATH."/".$_obfuscate_GsJ20flAQÿÿ );
                    @rename( CNOA_PATH."/".$_obfuscate_6Aÿÿ, CNOA_PATH."/".$_obfuscate_GsJ20flAQÿÿ."/".$_obfuscate_JTe7jJ4eGW8ÿ );
                    $_obfuscate_JQJwE4USnB0ÿ['normal'][$_obfuscate_gfGsQGKrGgÿÿ] = $_obfuscate_GsJ20flAQÿÿ."/".$_obfuscate_JTe7jJ4eGW8ÿ;
                }
                else
                {
                    $_obfuscate_JQJwE4USnB0ÿ['normal'][$_obfuscate_gfGsQGKrGgÿÿ] = $_obfuscate_6Aÿÿ;
                }
            }
            else if ( preg_match( "/^wf_budgetDept_(\\d+)\$/", $_obfuscate_5wÿÿ, $_obfuscate_8UmnTppRcAÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = $_obfuscate_8UmnTppRcAÿÿ[1];
                $_obfuscate_6Aÿÿ = intval( $_obfuscate_6Aÿÿ );
                $_obfuscate_3y0Y = "INSERT INTO ".tname( "budget_tmp_tabel" )."(`uFlowId`, `fieldId`, `deptId`) ".( "VALUES(".$_obfuscate_TlvKhtsoOQÿÿ.", {$_obfuscate_gfGsQGKrGgÿÿ}, {$_obfuscate_6Aÿÿ}) " ).( "ON DUPLICATE KEY UPDATE `deptId`=".$_obfuscate_6Aÿÿ );
                $CNOA_DB->query( $_obfuscate_3y0Y );
            }
            else if ( preg_match( "/^wf_budgetProj_(\\d+)\$/", $_obfuscate_5wÿÿ, $_obfuscate_8UmnTppRcAÿÿ ) )
            {
                $_obfuscate_gfGsQGKrGgÿÿ = $_obfuscate_8UmnTppRcAÿÿ[1];
                $_obfuscate_6Aÿÿ = intval( $_obfuscate_6Aÿÿ );
                $_obfuscate_3y0Y = "INSERT INTO ".tname( "budget_tmp_tabel_proj" )."(`uFlowId`, `fieldId`, `projId`) ".( "VALUES(".$_obfuscate_TlvKhtsoOQÿÿ.", {$_obfuscate_gfGsQGKrGgÿÿ}, {$_obfuscate_6Aÿÿ}) " ).( "ON DUPLICATE KEY UPDATE `projId`=".$_obfuscate_6Aÿÿ );
                $CNOA_DB->query( $_obfuscate_3y0Y );
            }
        }
        foreach ( $_obfuscate_V7H2J5ahgÿÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_JQJwE4USnB0ÿ['normal'][$_obfuscate_5wÿÿ] = json_encode( $_obfuscate_6Aÿÿ );
        }
        foreach ( $_obfuscate_u_DK_o5AB8le as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
        {
            foreach ( $_obfuscate_6Aÿÿ as $_obfuscate_ClAÿ => $_obfuscate_bRQÿ )
            {
                $_obfuscate_JQJwE4USnB0ÿ['detail'][$_obfuscate_5wÿÿ][$_obfuscate_ClAÿ] = json_encode( $_obfuscate_bRQÿ );
            }
        }
        $_obfuscate_dGoPOiQ2Iw5a = array( );
        if ( !empty( $_obfuscate_FYo_0_BVp9xjgDsÿ ) )
        {
            $_obfuscate_7Hp0w_lfFt4ÿ = $CNOA_DB->db_select( array( "id", "fid" ), $this->t_set_field_detail, "WHERE `fid` IN (".implode( ",", $_obfuscate_FYo_0_BVp9xjgDsÿ ).") " );
            if ( !is_array( $_obfuscate_7Hp0w_lfFt4ÿ ) )
            {
                $_obfuscate_7Hp0w_lfFt4ÿ = array( );
            }
            foreach ( $_obfuscate_7Hp0w_lfFt4ÿ as $_obfuscate_5wÿÿ => $_obfuscate_6Aÿÿ )
            {
                $_obfuscate_dGoPOiQ2Iw5a[$_obfuscate_6Aÿÿ['id']] = $_obfuscate_6Aÿÿ['fid'];
            }
        }
        return array(
            $_obfuscate_IuoXR2yOaxkRDwÿÿ,
            $_obfuscate_JQJwE4USnB0ÿ,
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
        $_obfuscate_6RYLWQÿÿ = array( );
        $_obfuscate_6RYLWQÿÿ['uFlowId'] = $this->uFlowId;
        $_obfuscate_6RYLWQÿÿ['uStepId'] = $this->stepId;
        $_obfuscate_6RYLWQÿÿ['uid'] = $_obfuscate_7Ri3;
        $_obfuscate_6RYLWQÿÿ['posttime'] = $GLOBALS['CNOA_TIMESTAMP'];
        $_obfuscate_6RYLWQÿÿ['toMobile'] = "";
        $_obfuscate_6RYLWQÿÿ['toName'] = "";
        $_obfuscate_6RYLWQÿÿ['content'] = "";
        ( );
        $_obfuscate_wVdkSQao12a = new sms( );
        foreach ( $GLOBALS['_POST']['sms'] as $_obfuscate_6Aÿÿ )
        {
            $_obfuscate_a9lP = json_decode( $_obfuscate_6Aÿÿ, TRUE );
            $_obfuscate_6RYLWQÿÿ['toMobile'] = addslashes( json_encode( $_obfuscate_a9lP['mobiles'] ) );
            $_obfuscate_6RYLWQÿÿ['toName'] = addslashes( json_encode( $_obfuscate_a9lP['names'] ) );
            $_obfuscate_6RYLWQÿÿ['content'] = $_obfuscate_a9lP['content'];
            $CNOA_DB->db_insert( $_obfuscate_6RYLWQÿÿ, $this->t_use_sms );
            foreach ( $_obfuscate_a9lP['mobiles'] as $_obfuscate_ty0ÿ => $_obfuscate_snMÿ )
            {
                $_obfuscate_wVdkSQao12a->sendByMobile( $_obfuscate_snMÿ, $_obfuscate_a9lP['content'], $GLOBALS['CNOA_TIMESTAMP'], $_obfuscate_a9lP['names'][$_obfuscate_ty0ÿ], 0, "flow" );
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
        $_obfuscate_NlQÿ = new dataStore( );
        $_obfuscate_NlQÿ->data = $_obfuscate_mPAjEGLn;
        echo $_obfuscate_NlQÿ->makeJsonData( );
        exit( );
    }

    protected function getFlowFields( )
    {
        global $CNOA_DB;
        $_obfuscate_F4AbnVRh = ( integer )getpar( $_POST, "flowId" );
        $_obfuscate_zKrG7sLw5AIÿ = array(
            self::FIELD_DATASOURCE,
            self::FIELD_DETAIL_TABLE,
            self::FIELD_SIGNATURE,
            self::FIELD_CHECKBOX
        );
        $_obfuscate_tjILu7ZH = $CNOA_DB->db_select( array( "id", "name", "otype", "odata" ), $this->t_set_field, "WHERE `flowId`=".$_obfuscate_F4AbnVRh." AND otype NOT IN ('".implode( "','", $_obfuscate_zKrG7sLw5AIÿ )."')" );
        $_obfuscate_Tc82k3jOQÿÿ = array( );
        if ( is_array( $_obfuscate_tjILu7ZH ) )
        {
            foreach ( $_obfuscate_tjILu7ZH as $_obfuscate_6Qÿÿ )
            {
                $_obfuscate_p5ZWxr4ÿ = json_decode( str_replace( "'", "\"", $_obfuscate_6Qÿÿ['odata'] ), TRUE );
                if ( in_array( $_obfuscate_p5ZWxr4ÿ['dataType'], array( "photo_upload", "huiqian" ) ) )
                {
                    unset( $_obfuscate_6Qÿÿ );
                }
                else
                {
                    $_obfuscate_6Qÿÿ['dataType'] = $_obfuscate_p5ZWxr4ÿ['dataType'];
                    $_obfuscate_Tc82k3jOQÿÿ[] = $_obfuscate_6Qÿÿ;
                }
            }
        }
        return $_obfuscate_Tc82k3jOQÿÿ;
    }

    protected function getAutoNextWfInfo( )
    {
        global $CNOA_DB;
        global $CNOA_SESSION;
        $_obfuscate_7Ri3 = $CNOA_SESSION->get( "UID" );
        $_obfuscate_F4_qwZVhBmemwÿÿ = $CNOA_DB->db_getfield( "wfAutoNext", "main_user", "WHERE `uid`='".$_obfuscate_7Ri3."'" );
        if ( $_obfuscate_F4_qwZVhBmemwÿÿ == "1" )
        {
            $_obfuscate_7qDAYo85aGAÿ = $CNOA_DB->get_one( "SELECT s.uFlowId, s.uStepId, u.flowId,ss.flowType,ss.tplSort FROM `cnoa_wf_u_step` AS s LEFT JOIN `cnoa_wf_u_flow` AS u ON s.uFlowId = u.uFlowId LEFT JOIN `cnoa_wf_s_flow` AS ss ON u.flowId = ss.flowId WHERE s.uid = 1 AND s.status = 1 ORDER BY s.id DESC LIMIT 1" );
            return $_obfuscate_7qDAYo85aGAÿ;
        }
        return FALSE;
    }

    public function getPageSet( $_obfuscate_F4AbnVRh )
    {
        global $CNOA_DB;
        $_obfuscate_36fJ0cHnXAÿÿ = $CNOA_DB->db_getfield( "pageset", $this->t_set_flow, "WHERE `flowId` = ".$_obfuscate_F4AbnVRh." " );
        if ( empty( $_obfuscate_36fJ0cHnXAÿÿ ) || $_obfuscate_36fJ0cHnXAÿÿ == "null" )
        {
            $_obfuscate_36fJ0cHnXAÿÿ = array( "pageSize" => "a4page", "pageDir" => "lengthways", "pageUp" => "10", "pageDown" => "10", "pageLeft" => "10", "pageRight" => "10" );
            $_obfuscate_36fJ0cHnXAÿÿ = json_encode( $_obfuscate_36fJ0cHnXAÿÿ );
        }
        return $_obfuscate_36fJ0cHnXAÿÿ;
    }

    protected function getUFlowIdsBySearch( $_obfuscate_F4AbnVRh, $_obfuscate_tjILu7ZH )
    {
        global $CNOA_DB;
        $_obfuscate_3tiDsnMÿ = self::TABLE_PRE_DATA.$_obfuscate_F4AbnVRh;
        $_obfuscate_Tc82k3jOQÿÿ = $_obfuscate_IRFhnYwÿ = array( );
        $_obfuscate_6RYLWQÿÿ = $this->getFlowFields( );
        foreach ( $_obfuscate_6RYLWQÿÿ as $_obfuscate_6Qÿÿ )
        {
            $_obfuscate_Tc82k3jOQÿÿ[$_obfuscate_6Qÿÿ['id']] = $_obfuscate_6Qÿÿ;
        }
        $_obfuscate_hjcEgBhYOi9kjIÿ = $CNOA_DB->db_getFieldsName( $_obfuscate_3tiDsnMÿ );
        $_obfuscate_JJKEg0IiY8Eÿ = array( "creatername", "createrdept", "createrjob", "createrstation", "loginname", "logindept", "loginjob", "loginstation" );
        foreach ( $_obfuscate_tjILu7ZH as $_obfuscate_Vwty => $_obfuscate_TAxu )
        {
            if ( !in_array( $_obfuscate_Vwty, $_obfuscate_hjcEgBhYOi9kjIÿ ) && !( $_obfuscate_TAxu != "" ) )
            {
                $_obfuscate_Ce9h = str_replace( "T_", "", $_obfuscate_Vwty );
                switch ( $_obfuscate_Tc82k3jOQÿÿ[$_obfuscate_Ce9h]['otype'] )
                {
                case self::FIELD_CHOICE :
                    switch ( $_obfuscate_Tc82k3jOQÿÿ[$_obfuscate_Ce9h]['dataType'] )
                    {
                    case "jobs_sel" :
                    case "users_sel" :
                    case "depts_sel" :
                    case "stations_sel" :
                        $_obfuscate_TAxu = explode( ",", $_obfuscate_TAxu );
                        foreach ( $_obfuscate_TAxu as $_obfuscate_6Aÿÿ )
                        {
                            $_obfuscate_IRFhnYwÿ[] = "FIND_IN_SET('".$_obfuscate_6Aÿÿ."' ,`{$_obfuscate_Vwty}`)";
                        }
                        break;
                    default :
                        $_obfuscate_IRFhnYwÿ[] = "{$_obfuscate_Vwty} = '{$_obfuscate_TAxu}'";
                    }
                    break;
                default :
                    if ( in_array( $_obfuscate_Tc82k3jOQÿÿ[$_obfuscate_Ce9h]['dataType'], $_obfuscate_JJKEg0IiY8Eÿ ) )
                    {
                        $_obfuscate_IRFhnYwÿ[] = "{$_obfuscate_Vwty} = '{$_obfuscate_TAxu}'";
                    }
                    else
                    {
                        $_obfuscate_IRFhnYwÿ[] = "{$_obfuscate_Vwty} LIKE '%{$_obfuscate_TAxu}%'";
                    }
                }
            }
        }
        return implode( " AND ", $_obfuscate_IRFhnYwÿ );
    }

    protected function _fillAutoFenFaUsers( $_obfuscate_HYuzLzerU95Vhgÿÿ, $_obfuscate_F4AbnVRh, $_obfuscate_0Ul8BBkt, $_obfuscate_7rU5WM0ÿ )
    {
        global $CNOA_DB;
        $_obfuscate__eqrEQÿÿ = array( );
        if ( !empty( $_obfuscate_7rU5WM0ÿ ) )
        {
            foreach ( $_obfuscate_7rU5WM0ÿ as $_obfuscate_VgKtFegÿ )
            {
                $_obfuscate__eqrEQÿÿ[] = $_obfuscate_VgKtFegÿ[0];
            }
            $_obfuscate_6b8lIO4y = $_obfuscate_HYuzLzerU95Vhgÿÿ;
            $_obfuscate__eqrEQÿÿ = implode( ",", $_obfuscate__eqrEQÿÿ );
            $_obfuscate_3y0Y = "INSERT INTO ".tname( $this->t_set_autoFenfa )." (`flowId`, `stepId`, `status`, `uids`)".( " VALUES ('".$_obfuscate_F4AbnVRh."', '{$_obfuscate_0Ul8BBkt}', '{$_obfuscate_6b8lIO4y}', '{$_obfuscate__eqrEQÿÿ}')" )." ON DUPLICATE KEY UPDATE `status`=VALUES(`status`), `uids`=VALUES(`uids`)";
            $CNOA_DB->query( $_obfuscate_3y0Y );
        }
        else
        {
            $_obfuscate_IRFhnYwÿ = "WHERE `flowId`='".$_obfuscate_F4AbnVRh."' AND `stepId`='{$_obfuscate_0Ul8BBkt}' ";
            $CNOA_DB->db_delete( $this->t_set_autoFenfa, $_obfuscate_IRFhnYwÿ );
        }
    }

    protected function api_autoFenfa( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_0Ul8BBkt )
    {
        app::loadapp( "wf", "flowUseTodo" )->api_addFenfa( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_0Ul8BBkt );
    }

    public function api_getFormatFlowNumber( $_obfuscate_IMeby9iyHIgÿ, $_obfuscate_neM4JBUJlmgÿ, $_obfuscate_zPP1hxIu42teMwÿÿ, $_obfuscate_F4AbnVRh )
    {
        return $this->api_formatFlowNumber( $_obfuscate_IMeby9iyHIgÿ, $_obfuscate_neM4JBUJlmgÿ, $_obfuscate_zPP1hxIu42teMwÿÿ, $_obfuscate_F4AbnVRh );
    }

    public function getAttachList( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_WPvkSFEMgÿÿ )
    {
        global $CNOA_DB;
        if ( empty( $_obfuscate_TlvKhtsoOQÿÿ ) )
        {
            return $_obfuscate_WPvkSFEMgÿÿ['attach'];
        }
        $_obfuscate_1_pbjTIdLU49 = $CNOA_DB->db_select( array( "id" ), $this->t_set_field, "WHERE flowId=".$_obfuscate_F4AbnVRh." AND otype='attach'" );
        if ( !is_array( $_obfuscate_1_pbjTIdLU49 ) )
        {
            $_obfuscate_1_pbjTIdLU49 = array( );
        }
        if ( $_obfuscate_1_pbjTIdLU49 )
        {
            foreach ( $_obfuscate_1_pbjTIdLU49 as $_obfuscate_Vwty => $_obfuscate_VgKtFegÿ )
            {
                $m = $CNOA_DB->db_getfield( "T_".$_obfuscate_VgKtFegÿ['id'], "z_wf_t_".$_obfuscate_F4AbnVRh, "WHERE uFlowId=".$_obfuscate_TlvKhtsoOQÿÿ );
                if ( $m )
                {
                    $_obfuscate_MAduV4GmcAN9 .= substr( $m, 1, -1 ).",";
                }
            }
        }
        if ( $_obfuscate_MAduV4GmcAN9 )
        {
            if ( 2 < strlen( $_obfuscate_WPvkSFEMgÿÿ['attach'] ) )
            {
                return "[".$_obfuscate_MAduV4GmcAN9.substr( $_obfuscate_WPvkSFEMgÿÿ['attach'], 1, -1 )."]";
            }
            return "[".substr( $_obfuscate_MAduV4GmcAN9, 0, -1 )."]";
        }
        if ( 2 < strlen( $_obfuscate_WPvkSFEMgÿÿ['attach'] ) && empty( $_obfuscate_MAduV4GmcAN9 ) )
        {
            return $_obfuscate_WPvkSFEMgÿÿ['attach'];
        }
    }

    public function _checkDraft( $_obfuscate_F4AbnVRh, $_obfuscate_TlvKhtsoOQÿÿ, $_obfuscate_VBCv7Qÿÿ )
    {
        $_obfuscate_PW9SQhMxAgÿÿ = CNOA_PATH_FILE.( "/common/wf/draft/cwj&flowId=".$_obfuscate_F4AbnVRh."&uFlowId={$_obfuscate_TlvKhtsoOQÿÿ}&step={$_obfuscate_VBCv7Qÿÿ}.php" );
        if ( is_file( $_obfuscate_PW9SQhMxAgÿÿ ) )
        {
            return $_obfuscate_PW9SQhMxAgÿÿ;
        }
        return "";
    }

}

?>
