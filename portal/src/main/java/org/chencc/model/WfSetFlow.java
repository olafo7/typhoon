package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description 流程设计信息实体类
 * @author:chencc
 * @Date:2018/8/25
 * @Time:上午10:07
 */
@Data
@Table(name = "db_wf_set_flow")
public class WfSetFlow implements Serializable {
    @Column(name = "flow_id", type = MySqlTypeConstant.INT, isKey = true,isAutoIncrement = true,length = 11)
    private Integer flowId;
    @Column(name = "name", type = MySqlTypeConstant.VARCHAR, length = 225)
    private String name;
    @Column(name = "status", type = MySqlTypeConstant.INT, length = 1)
    private Integer status;
    @Column(name = "tpl_sort", type = MySqlTypeConstant.INT, length = 3)
    private Integer tplSort;
    @Column(name = "flow_type", type = MySqlTypeConstant.INT, length = 3)
    private Integer flowType;
    @Column(name = "post_time", type = MySqlTypeConstant.INT, length = 10)
    private Integer postTime;
    @Column(name = "attach", type = MySqlTypeConstant.TEXT)
    private String attach;
    @Column(name = "start_step_id", type = MySqlTypeConstant.INT, length = 10)
    private Integer startStepId;
    @Column(name = "uid", type = MySqlTypeConstant.INT, length = 10)
    private Integer uid;
    @Column(name = "name_rule", type = MySqlTypeConstant.VARCHAR, length = 225)
    private String nameRule;
    @Column(name = "name_rule_id", type = MySqlTypeConstant.INT, length = 10)
    private Integer nameRuleId;
    @Column(name = "name_rule_allow_edit", type = MySqlTypeConstant.INT, length = 1)
    private Integer nameRuleAllowEdit;
    @Column(name = "name_disallow_blank", type = MySqlTypeConstant.INT, length = 1)
    private Integer nameDisallowBlank;
    @Column(name = "user_title", type = MySqlTypeConstant.VARCHAR, length = 225)
    private String userTitle;
    @Column(name = "form_html", type = MySqlTypeConstant.TEXT)
    private Integer formHtml;
    @Column(name = "page_set", type = MySqlTypeConstant.VARCHAR, length = 500)
    private String pageSet;
    @Column(name = "flow_xml", type = MySqlTypeConstant.TEXT)
    private String flowXml;
    @Column(name = "flow_html5", type = MySqlTypeConstant.TEXT)
    private String flowHtml5;
    @Column(name = "sort_id", type = MySqlTypeConstant.INT, length = 10)
    private Integer sortId;
    @Column(name = "notice_callback", type = MySqlTypeConstant.INT, length = 1)
    private Integer noticeCallback;
    @Column(name = "notice_cancel", type = MySqlTypeConstant.INT, length = 1)
    private Integer noticeCancel;
    @Column(name = "notice_at_go_back", type = MySqlTypeConstant.INT, length = 1)
    private Integer noticeAtGoBack;
    @Column(name = "notice_at_reject", type = MySqlTypeConstant.INT, length = 1)
    private Integer noticeAtReject;
    @Column(name = "notice_at_interrupt", type = MySqlTypeConstant.INT, length = 1)
    private Integer noticeAtInterrupt;
    @Column(name = "notice_at_finish", type = MySqlTypeConstant.INT, length = 1)
    private Integer noticeAtFinish;
    @Column(name = "about", type = MySqlTypeConstant.VARCHAR, length = 225)
    private String about;
    @Column(name = "table", type = MySqlTypeConstant.VARCHAR, length = 30)
    private String table;
    @Column(name = "bind_function", type = MySqlTypeConstant.VARCHAR, length = 100)
    private String bindFunction;
    @Column(name = "order", type = MySqlTypeConstant.INT, length = 11)
    private Integer order;
}
