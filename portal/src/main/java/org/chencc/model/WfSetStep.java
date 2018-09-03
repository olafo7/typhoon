package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;
import java.math.BigDecimal;

/**
 * @Description 流程节点信息表
 * @author:chencc
 * @Date:2018/8/26
 * @Time:上午12:23
 */
@Data
@Table(name = "db_wf_set_step")
public class WfSetStep implements Serializable {
    @Column(name = "id", type = MySqlTypeConstant.INT, isKey = true,isAutoIncrement = true,length = 10)
    private Integer id;
    @Column(name = "step_id", type = MySqlTypeConstant.INT, length = 10)
    private String stepId;
    @Column(name = "flow_id", type = MySqlTypeConstant.INT, length = 10)
    private Integer flowId;
    @Column(name = "uid", type = MySqlTypeConstant.INT, length = 10)
    private Integer uid;
    @Column(name = "step_type", type = MySqlTypeConstant.INT, length = 1,defaultValue = "2")
    private Integer stepType;
    @Column(name = "step_name", type = MySqlTypeConstant.VARCHAR, length = 225)
    private String stepName;
    @Column(name = "next_step", type = MySqlTypeConstant.TEXT)
    private String nextStep;
    @Column(name = "do_btn_text", type = MySqlTypeConstant.VARCHAR, length = 225)
    private String doBtnText;
    @Column(name = "allow_reject", type = MySqlTypeConstant.INT, length = 1,defaultValue = "0")
    private Integer allowReject;
    @Column(name = "allow_huiqian", type = MySqlTypeConstant.INT, length = 1,defaultValue = "0")
    private Integer allowHuiqian;
    @Column(name = "allow_fenfa", type = MySqlTypeConstant.INT, length = 1)
    private Integer allowFenfa;
    @Column(name = "allow_tuihui", type = MySqlTypeConstant.INT, length = 1)
    private Integer allowTuihui;
    @Column(name = "allow_print", type = MySqlTypeConstant.INT, length = 1)
    private Integer allowPrint;
    @Column(name = "allow_callback", type = MySqlTypeConstant.INT, length = 1)
    private Integer allowCallback;
    @Column(name = "allow_cancel", type = MySqlTypeConstant.INT, length = 1)
    private Integer allowCancel;
    @Column(name = "allow_attach_add", type = MySqlTypeConstant.INT, length = 1,defaultValue = "0")
    private Integer allowAttachAdd;
    @Column(name = "allow_attach_view", type = MySqlTypeConstant.INT, length = 1,defaultValue = "0")
    private Integer allowAttachView;
    @Column(name = "allow_attach_edit", type = MySqlTypeConstant.INT, length = 1,defaultValue = "0")
    private Integer allowAttachEdit;
    @Column(name = "allow_attach_delete", type = MySqlTypeConstant.INT, length = 1,defaultValue = "0")
    private Integer allowAttachDelete;
    @Column(name = "allow_attach_down", type = MySqlTypeConstant.INT, length = 1,defaultValue = "0")
    private Integer allowAttachDown;
    @Column(name = "allow_word_edit", type = MySqlTypeConstant.INT, length = 1,defaultValue = "0")
    private Integer allowWordEdit;
    @Column(name = "allow_attach_word_edit", type = MySqlTypeConstant.INT, length = 1,defaultValue = "0")
    private Integer allowAttachWordEdit;
    @Column(name = "allow_sms", type = MySqlTypeConstant.INT, length = 1,defaultValue = "0")
    private Integer allowSms;
    @Column(name = "step_time", type = MySqlTypeConstant.DECIMAL, length = 6,decimalLength = 1)
    private BigDecimal stepTime;
    @Column(name = "urge_before", type = MySqlTypeConstant.INT, length = 10)
    private Integer urgeBefore;
    @Column(name = "urge_target", type = MySqlTypeConstant.INT, length = 2)
    private Integer urgeTarget;
    @Column(name = "child_flow", type = MySqlTypeConstant.INT, length = 1)
    private Integer childFlow;
    @Column(name = "bing_names", type = MySqlTypeConstant.VARCHAR, length = 300)
    private String bingNames;
    @Column(name = "bing_ids", type = MySqlTypeConstant.VARCHAR, length = 300)
    private String bingIds;
    @Column(name = "faqi_flow", type = MySqlTypeConstant.VARCHAR, length = 20)
    private String faqiFlow;
    @Column(name = "end_flow", type = MySqlTypeConstant.VARCHAR, length = 20)
    private String endFlow;
    @Column(name = "share_file", type = MySqlTypeConstant.INT, length = 1)
    private Integer shareFile;
    @Column(name = "allow_hq_attach_add", type = MySqlTypeConstant.INT, length = 1)
    private Integer allowHqAttachAdd;
    @Column(name = "allow_hq_attach_view", type = MySqlTypeConstant.INT, length = 1)
    private Integer allowHqAttachView;
    @Column(name = "allow_hq_attach_edit", type = MySqlTypeConstant.INT, length = 1)
    private Integer allowHqAttachEdit;
    @Column(name = "allow_hq_attach_delete", type = MySqlTypeConstant.INT, length = 1)
    private Integer allowHqAttachDelete;
    @Column(name = "allow_hq_attach_down", type = MySqlTypeConstant.INT, length = 1)
    private Integer allowHqAttachDown;
    @Column(name = "allow_yijian", type = MySqlTypeConstant.INT, length = 1)
    private Integer allowYijian;
}
