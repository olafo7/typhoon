package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description 系统配置实体类
 * @author:chencc
 * @Date:2018/7/22
 * @Time:下午2:33
 */
@Data
@Table(name = "db_system_config")
public class SystemConfig implements Serializable {
    @Column(name = "id", type = MySqlTypeConstant.INT, isKey = true,isAutoIncrement = true,length = 10)
    private Integer id;
    @Column(name = "server_name", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 50)
    private String serverName;
    @Column(name = "local_url", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 200)
    private String localUrl;
    @Column(name = "version1", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 10)
    private String version1;
    @Column(name = "version2", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 10)
    private String version2;
    @Column(name = "version_type", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 10)
    private String versionType;
    @Column(name = "last_update_time", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private String lastUpdateTime;
    @Column(name = "start_time", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private String startTime;
    @Column(name = "version_name", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 100)
    private String versionName;
    @Column(name = "custom", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer custom;
    @Column(name = "company", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 200)
    private String company;
    @Column(name = "link_man", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 100)
    private String linkMan;
    @Column(name = "link_phone", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 100)
    private String linkPhone;
    @Column(name = "link_email", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 100)
    private String linkEmail;
    @Column(name = "sn", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 16)
    private String sn;
    @Column(name = "pr_code", type = MySqlTypeConstant.TEXT, isNull = false)
    private String prCode;
    @Column(name = "pu_code", type = MySqlTypeConstant.TEXT, isNull = false)
    private String puCode;
    @Column(name = "last_olcktime", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private Integer lastOlcktime;
    @Column(name = "use_forget_pwd", type = MySqlTypeConstant.INT, isNull = false,length = 11)
    private Integer useForgetPwd;
    @Column(name = "last_update", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private Integer lastUpdate;
    @Column(name = "smtp_host", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 100)
    private String smtpHost;
    @Column(name = "smtp_port", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private Integer smtpPort;
    @Column(name = "smtp_user", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 100)
    private String smtpUser;
    @Column(name = "smtp_pass", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 500)
    private String smtpPass;
    @Column(name = "smtp_auth", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer smtpAuth;
    @Column(name = "smtp_ssl", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer smtpSsl;
    @Column(name = "smtp_from", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 100)
    private String smtpFrom;
    @Column(name = "smtp_from_name", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 100)
    private String smtpFromName;
    @Column(name = "system_title_show", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer systemTitleShow;
    @Column(name = "sms_enable", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer smsEnable;
    @Column(name = "sms_use_api", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer smsUseApi;
    @Column(name = "sms_api_type", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 20)
    private String smsApiType;
    @Column(name = "sms_api_send_url", type = MySqlTypeConstant.TEXT, isNull = false)
    private String smsApiSendUrl;
    @Column(name = "sms_api_receive_url", type = MySqlTypeConstant.TEXT, isNull = false)
    private String smsApiReceiveUrl;
    @Column(name = "login_auth_code", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer loginAuthCode;
    @Column(name = "login_auth_code_wap", type = MySqlTypeConstant.INT, isNull = false,length = 1 )
    private Integer loginAuthCodeWap;
    @Column(name = "login_page", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 100)
    private String loginPage;
    @Column(name = "allow_body_context_menu", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer allowBodyContextMenu;
    @Column(name = "allow_text_copy", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer allowTextCopy;
    @Column(name = "lock_index_layout", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer lockIndexLayout;
    @Column(name = "layout_type", type = MySqlTypeConstant.INT, isNull = false,length = 2)
    private Integer layoutType;
    @Column(name = "login_limit", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer loginLimit;
    @Column(name = "login_dtpass", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer loginDtpass;
    @Column(name = "s_hbt", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer sHbt;
    @Column(name = "s_hma", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer sHma;
    @Column(name = "s_hmh", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer sHmh;
    @Column(name = "s_meb", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer sMeb;
    @Column(name = "s_meu", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 225)
    private String sMeu;
    @Column(name = "s_clo", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer sClo;
    @Column(name = "s_tlo", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer sTlo;
    @Column(name = "sT_tdn", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer sTdn;
    @Column(name = "tag_num", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer tagNum;
    @Column(name = "activex_version", type = MySqlTypeConstant.INT, isNull = false,length = 6)
    private Integer activexVersion;
    @Column(name = "activex_cls_id", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 100)
    private String activexClsId;
    @Column(name = "activex_prog_id", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 100)
    private String activexProgId;
    @Column(name = "activex_url", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 200)
    private String activexUrl;
    @Column(name = "office_view_allow_print", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer officeViewAllowPrint;
    @Column(name = "office_view_allow_copy", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer officeViewAllowCopy;
    @Column(name = "start_send_email", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private Integer startSendEmail;
    @Column(name = "start_send_app_notice", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private Integer startSendAppNotice;
    @Column(name = "qq_link_enable", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer qqLinkEnable;
    @Column(name = "qq_link_type", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 3)
    private String qqLinkType;
    @Column(name = "is_sys_easemob", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer isSysEasemob;
}
