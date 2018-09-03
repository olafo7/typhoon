package org.chencc.mapper;

import org.apache.ibatis.annotations.*;
import org.chencc.model.WfSetFlow;
import org.chencc.model.WfSort;
import org.omg.PortableInterceptor.INACTIVE;

import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/29
 * @Time:下午9:22
 */
public interface FlowSettingMapper {

    @Select("${sqlBuffer}")
    List<Map> getSortList(@Param("sqlBuffer") String sqlBuffer);

    @Select("select * from db_wf_sort where sort_id=#{sortId}")
    @Results({
            @Result(property = "sortId", column = "sort_id"),
            @Result(property = "name", column = "name"),
            @Result(property = "note", column = "note"),
            @Result(property = "system", column = "system"),
            @Result(property = "order", column = "order")
    })
    WfSort loadWfSortById(@Param("sortId") Integer sortId);

    @Update("update db_wf_sort as ws set ws.order=#{order} where ws.sort_id=#{sortId}")
    void updateSortOrder(@Param("sortId") Integer sortId,@Param("order") Integer order);

    @Delete("delete from db_wf_sort where sort_id in (#{ids})")
    void deleteSortList(@Param("ids") String ids);

    @Insert("insert into db_wf_set_flow(name,status,tpl_sort,flow_type,post_time,uid,name_rule," +
            "name_rule_allow_edit,name_disallow_blank,sort_id,notice_callback,notice_cancel," +
            "notice_at_go_back,notice_at_reject,notice_at_interrupt,notice_at_finish,about) " +
            "values(#{name},#{status},#{tplSort},#{flowType},#{postTime},#{uid},#{nameRule}," +
            "#{nameRuleAllowEdit},#{nameDisallowBlank},#{sortId},#{noticeCallback},#{noticeCancel}," +
            "#{noticeAtGoBack},#{noticeAtReject},#{noticeAtInterrupt},#{noticeAtFinish},#{about})")
    void saveNewRecord(@Param("flowId") Integer flowId, @Param("flowType") Integer flowType, @Param("tplSort") Integer tplSort,
                       @Param("name") String name, @Param("sortId") Integer sortId, @Param("nameRule") String nameRule, @Param("nameRuleAllowEdit") Integer nameRuleAllowEdit,
                       @Param("nameDisallowBlank") Integer nameDisallowBlank, @Param("noticeCallback") Integer noticeCallback, @Param("noticeCancel") Integer noticeCancel,
                       @Param("noticeAtGoBack") Integer noticeAtGoBack, @Param("noticeAtReject") Integer noticeAtReject, @Param("noticeAtInterrupt") Integer noticeAtInterrupt,
                       @Param("noticeAtFinish") Integer noticeAtFinish, @Param("about") String about, @Param("uid") Integer uid, @Param("postTime") Integer postTime, @Param("status") Integer status);

    @Select("select * from db_wf_set_flow where name =#{name}")
    List<Map> getFlowListByName(@Param("name") String name);

    @Select("select * from db_wf_set_flow where name =#{name} and flow_id != #{flowId}")
    List<Map> getFlowListByNameAndId(@Param("name") String name,@Param("flowId") Integer flowId);

    @Update("update db_wf_set_flow set flow_type=#{flowType},tpl_sort=#{tplSort},name=#{name}," +
            "sort_id=#{sortId},name_rule=#{nameRule},name_rule_allow_edit=#{nameRuleAllowEdit}," +
            "name_disallow_blank=#{nameDisallowBlank},notice_callback=#{noticeCallback},notice_cancel=#{noticeCancel}," +
            "notice_at_go_back=#{noticeAtGoBack},notice_at_reject=#{noticeAtReject},notice_at_interrupt=#{noticeAtInterrupt}," +
            "notice_at_finish=#{noticeAtFinish},about=#{about} where flow_id=#{flowId}")
    void saveEditRecord(@Param("flowId") Integer flowId, @Param("flowType") Integer flowType, @Param("tplSort") Integer tplSort,
                        @Param("name") String name, @Param("sortId") Integer sortId, @Param("nameRule") String nameRule, @Param("nameRuleAllowEdit") Integer nameRuleAllowEdit,
                        @Param("nameDisallowBlank") Integer nameDisallowBlank, @Param("noticeCallback") Integer noticeCallback, @Param("noticeCancel") Integer noticeCancel,
                        @Param("noticeAtGoBack") Integer noticeAtGoBack, @Param("noticeAtReject") Integer noticeAtReject, @Param("noticeAtInterrupt") Integer noticeAtInterrupt,
                        @Param("noticeAtFinish") Integer noticeAtFinish, @Param("about") String about);

    @Select("${sqlBuffer}")
    List<Map> getFlowList(@Param("sqlBuffer")  String sqlBuffer);

    @Select("select * from db_wf_set_flow where flow_id=#{flowId}")
    @Results({
            @Result(property = "flowId", column = "flow_id"),
            @Result(property = "name", column = "name"),
            @Result(property = "status", column = "status"),
            @Result(property = "tplSort", column = "tpl_sort"),
            @Result(property = "flowType", column = "flow_type"),
            @Result(property = "postTime", column = "post_time"),
            @Result(property = "attach", column = "attach"),
            @Result(property = "startStepId", column = "start_step_id"),
            @Result(property = "uid", column = "uid"),
            @Result(property = "nameRule", column = "name_rule"),
            @Result(property = "nameRuleId", column = "name_rule_id"),
            @Result(property = "nameRuleAllowEdit", column = "name_rule_allow_edit"),
            @Result(property = "nameDisallowBlank", column = "name_disallow_blank"),
            @Result(property = "userTitle", column = "user_title"),
            @Result(property = "formHtml", column = "form_html"),
            @Result(property = "pageSet", column = "page_set"),
            @Result(property = "flowXml", column = "flow_xml"),
            @Result(property = "flowHtml5", column = "flow_html5"),
            @Result(property = "sortId", column = "sort_id"),
            @Result(property = "noticeCallback", column = "notice_callback"),
            @Result(property = "noticeCancel", column = "notice_cancel"),
            @Result(property = "noticeAtGoBack", column = "notice_at_go_back"),
            @Result(property = "noticeAtReject", column = "notice_at_reject"),
            @Result(property = "noticeAtInterrupt", column = "notice_at_interrupt"),
            @Result(property = "noticeAtFinish", column = "notice_at_finish"),
            @Result(property = "about", column = "about"),
            @Result(property = "table", column = "table"),
            @Result(property = "bindFunction", column = "bind_function"),
            @Result(property = "order", column = "order")
    })
    WfSetFlow getFlowInfoById(@Param("flowId") Integer flowId);

    @Select("select * from db_wf_set_step where flow_id=#{flowId}")
    List<Map> getFlowStepById(@Param("flowId") Integer flowId);

    @Update("update db_wf_set_flow set status=#{status} where flow_id=#{flowId}")
    void updateFlowStatus(@Param("flowId") Integer flowId, @Param("status") Integer status);

    @Update("update db_wf_use_wffav set status=#{status} where flow_id=#{flowId}")
    void updateUseFavStatus(@Param("flowId") Integer flowId, @Param("status") Integer status);

    @Select("select form_html from db_wf_set_flow where flow_id=#{flowId}")
    String getFormHtmlByFlowId(@Param("flowId") Integer flowId);

    @Select("select flow_xml from db_wf_set_flow where flow_id =#{flowId}")
    String getFlowXmlByFlowId(@Param("flowId") Integer flowId);

    @Select("select * from db_wf_set_field where flow_id =#{flowId} ORDER BY `id` ASC")
    List<Map> getFieldListByFlowId(@Param("flowId") Integer flowId);

    @Select("select * from db_wf_set_field_detail where fid =#{fid}")
    List<Map> getFieldDetailListByFid(String fid);

    @Select("select * from db_wf_set_step where flow_id =#{flowId} ORDER BY `id` ASC")
    List<Map> getStepListByFlowId(@Param("flowId") Integer flowId);

    @Select("select * from db_wf_set_step_fields where flow_id =#{flowId} and step_id=#{stepId}")
    List<Map> getStepFieldsList(@Param("flowId") Integer flowId, @Param("stepId") Integer stepId);

    @Select("select * from db_wf_set_step_permit where flow_id =#{flowId}")
    List<Map> getStepPermitListByFlowId(Integer flowId);
}
