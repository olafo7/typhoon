package org.chencc.mapper;

import org.apache.ibatis.annotations.*;
import org.chencc.model.SystemConfig;
import org.chencc.model.SystemMenu;
import org.chencc.model.SystemOutlink;

import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/22
 * @Time:下午3:31
 */
public interface SystemSettingMapper {
    @Select("select * from db_system_config")
    @Results({
            @Result(property = "id", column = "id"),
            @Result(property = "serverName", column = "server_name"),
            @Result(property = "localUrl", column = "local_url"),
            @Result(property = "version1", column = "version1"),
            @Result(property = "version2", column = "version2"),
            @Result(property = "versionType", column = "version_type"),
            @Result(property = "lastUpdateTime", column = "last_update_time"),
            @Result(property = "startTime", column = "start_time"),
            @Result(property = "versionName", column = "version_name"),
            @Result(property = "custom", column = "custom"),
            @Result(property = "company", column = "company"),
            @Result(property = "linkMan", column = "link_man"),
            @Result(property = "linkPhone", column = "link_phone"),
            @Result(property = "linkEmail", column = "link_email"),
            @Result(property = "sn", column = "sn"),
            @Result(property = "prCode", column = "pr_code"),
            @Result(property = "puCode", column = "pu_code"),
            @Result(property = "lastOlcktime", column = "last_olcktime"),
            @Result(property = "useForgetPwd", column = "use_forget_pwd"),
            @Result(property = "lastUpdate", column = "last_update"),
            @Result(property = "smtpHost", column = "smtp_host"),
            @Result(property = "smtpPort", column = "smtp_port"),
            @Result(property = "smtpUser", column = "smtp_user"),
            @Result(property = "smtpPass", column = "smtp_pass"),
            @Result(property = "smtpAuth", column = "smtp_auth"),
            @Result(property = "smtpSsl", column = "smtp_ssl"),
            @Result(property = "smtpFrom", column = "smtp_from"),
            @Result(property = "smtpFromName", column = "smtp_from_name"),
            @Result(property = "systemTitleShow", column = "system_title_show"),
            @Result(property = "smsEnable", column = "sms_enable"),
            @Result(property = "smsUseApi", column = "sms_use_api"),
            @Result(property = "smsApiType", column = "sms_api_type"),
            @Result(property = "smsApiSendUrl", column = "sms_api_send_url"),
            @Result(property = "smsApiReceiveUrl", column = "sms_api_receive_url"),
            @Result(property = "loginAuthCode", column = "login_auth_code"),
            @Result(property = "loginAuthCodeWap", column = "login_auth_code_wap"),
            @Result(property = "loginPage", column = "login_page"),
            @Result(property = "allowBodyContextMenu", column = "allow_body_context_menu"),
            @Result(property = "allowTextCopy", column = "allow_text_copy"),
            @Result(property = "lockIndexLayout", column = "lock_index_layout"),
            @Result(property = "layoutType", column = "layout_type"),
            @Result(property = "loginLimit", column = "login_limit"),
            @Result(property = "loginDtpass", column = "login_dtpass"),
            @Result(property = "sHbt", column = "s_hbt"),
            @Result(property = "sHma", column = "s_hma"),
            @Result(property = "sHmh", column = "s_hmh"),
            @Result(property = "sMeb", column = "s_meb"),
            @Result(property = "sMeu", column = "s_meu"),
            @Result(property = "sClo", column = "s_clo"),
            @Result(property = "sTlo", column = "s_tlo"),
            @Result(property = "sTdn", column = "s_tdn"),
            @Result(property = "tagNum", column = "tag_num"),
            @Result(property = "activexVersion", column = "activex_version"),
            @Result(property = "activexClsId", column = "activex_cls_id"),
            @Result(property = "activexProgId", column = "activex_prog_id"),
            @Result(property = "activexUrl", column = "activex_url"),
            @Result(property = "officeViewAllowPrint", column = "office_view_allow_print"),
            @Result(property = "officeViewAllowCopy", column = "office_view_allow_copy"),
            @Result(property = "startSendEmail", column = "start_send_email"),
            @Result(property = "startSendAppNotice", column = "start_send_app_notice"),
            @Result(property = "qqLinkEnable", column = "qq_link_enable"),
            @Result(property = "qqLinkType", column = "qq_link_type"),
            @Result(property = "isSysEasemob", column = "is_sys_easemob")
    })
    SystemConfig getSystemConfig();

    @Select("${sqlBuffer}")
    @Results({
            @Result(property = "id", column = "id"),
            @Result(property = "pid", column = "pid"),
            @Result(property = "menuId", column = "menu_id"),
            @Result(property = "text", column = "text"),
            @Result(property = "language", column = "language"),
            @Result(property = "newText", column = "new_text"),
            @Result(property = "iconCls", column = "icon_cls"),
            @Result(property = "expanded", column = "expanded"),
            @Result(property = "singleClickExpand", column = "single_click_expand"),
            @Result(property = "clickEvent", column = "click_event"),
            @Result(property = "cls", column = "cls"),
            @Result(property = "leaf", column = "leaf"),
            @Result(property = "forceRefresh", column = "force_refresh"),
            @Result(property = "href", column = "href"),
            @Result(property = "autoLoadUrl", column = "auto_load_url"),
            @Result(property = "code", column = "code"),
            @Result(property = "isClass", column = "is_class"),
            @Result(property = "show", column = "show"),
            @Result(property = "order", column = "order"),
            @Result(property = "system", column = "system"),
            @Result(property = "systemShow", column = "system_show"),
            @Result(property = "about", column = "about"),
            @Result(property = "permitId", column = "permit_id"),
            @Result(property = "blong", column = "blong")
    })
    List<SystemMenu> getSystemMenuList(@Param("sqlBuffer") String sqlBuffer);

    @Select("select * from db_system_outlink")
    @Results({
            @Result(property = "id", column = "id"),
            @Result(property = "name", column = "name"),
            @Result(property = "link", column = "link"),
            @Result(property = "order", column = "order"),
            @Result(property = "postTime", column = "post_time")
    })
    List<SystemOutlink> getOutlinkList();

    @Select("select true_name as trueName,work_status_type,sex,bianhao,dept_id,job_id,part_time,part_time_job,uid,username,station_id,qq from db_main_user where work_status_type=#{type}")
    List<Map> getUserListByType(@Param("type") String type);
}
