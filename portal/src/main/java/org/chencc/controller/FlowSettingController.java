package org.chencc.controller;

import com.github.pagehelper.PageInfo;
import net.sf.json.JSONObject;
import org.apache.commons.lang.StringUtils;
import org.chencc.common.CurrentUserWrapper;
import org.chencc.common.DataListWrapper;
import org.chencc.common.DataWrapper;
import org.chencc.common.HandlerInfo;
import org.chencc.config.CurrentUser;
import org.chencc.model.*;
import org.chencc.service.FlowSettingService;
import org.chencc.utils.GlobalFunc;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.redis.core.ValueOperations;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.*;

import javax.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.lang.reflect.Array;
import java.util.*;

/**
 *
 * @Description 流程设置控制器，用于控制流程分类、设计、表单设计等功能
 * @author:chencc
 * @Date:2018/7/12
 * @Time:上午07:19
 *
 * */
@Controller
@RequestMapping("/workFlow")
public class FlowSettingController {
    @Autowired
    private FlowSettingService flowSettingService;

    /**
     * 流程分类设置：列表页面
     * @return
     */
    @GetMapping(value = "/classifySetting")
    public String classifySetting(){
        return "workFlow/classify_setting";
    }



    /**
     * 流程分类设置：加载列表分类数据
     * @return
     * @throws IOException
     */
    @GetMapping(value = "/getSortJsonData")
    @ResponseBody
    public DataWrapper getSortJsonData(String sname){
        DataWrapper dataWrapper = new DataWrapper();

        dataWrapper.setTotal(1);
        dataWrapper.setCpage(1);
        dataWrapper.setRows(10);
        dataWrapper.setState("");
        dataWrapper.setPaterId("");

        try{
            List<Map> sortList = flowSettingService.getSortList(sname);

            dataWrapper.setSuccess(true);
            dataWrapper.setData(sortList);
            dataWrapper.setMsg("操作成功！");
        }catch (Exception ex){
            dataWrapper.setSuccess(false);
            dataWrapper.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return dataWrapper;
    }



    /**
     * 流程分类设置：加载需要修改的数据
     * @param sortId
     *
     * @return
     */
    @RequestMapping(value = "/loadSortFormData", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public DataWrapper loadSortFormData(Integer sortId){
        DataWrapper dataWrapper = new DataWrapper();

        dataWrapper.setTotal(1);
        dataWrapper.setCpage(1);
        dataWrapper.setRows(10);
        dataWrapper.setState("");
        dataWrapper.setPaterId("");

        try{
            WfSort wfSort = flowSettingService.loadWfSortById(sortId);

            dataWrapper.setSuccess(true);
            dataWrapper.setData(wfSort);
            dataWrapper.setMsg("操作成功！");
        }catch (Exception ex){
            dataWrapper.setSuccess(false);
            dataWrapper.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return dataWrapper;
    }




    /**
     * 流程分类设置：提交变更后的排序数据
     * @return
     */
    @PostMapping(value = "/submitSortOrder")
    @ResponseBody
    public HandlerInfo submitSortOrder(Integer sortId,Integer order) {

        HandlerInfo handlerInfo = new HandlerInfo();
        try{
            flowSettingService.updateSortOrder(sortId,order);
            handlerInfo.setSuccess(true);
            handlerInfo.setMsg("更新成功！");
        }catch (Exception ex){
            handlerInfo.setSuccess(false);
            handlerInfo.setMsg("更新失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return handlerInfo;
    }



    /**
     * 流程分类设置：删除分类数据
     * @return
     */
    @PostMapping(value = "/deleteSort")
    @ResponseBody
    public HandlerInfo deleteSort(String ids) {

        HandlerInfo handlerInfo = new HandlerInfo();
        try{
            flowSettingService.deleteSortList(ids);
            handlerInfo.setSuccess(true);
            handlerInfo.setMsg("操作成功！");
        }catch (Exception ex){
            handlerInfo.setSuccess(false);
            handlerInfo.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return handlerInfo;
    }




    /**
     * 流程设计
     * @return
     */
    @GetMapping(value = "/flowDesign")
    public String flowDesign(){
        return "workFlow/flow_design";
    }


    /**
     * 流程设计：新建/修改/复制流程中的"所属分类"字段下拉列表内容
     * @return
     */
    @RequestMapping(value = "/getFlowClassify", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public DataListWrapper getFlowClassify(){

        DataListWrapper dataWrapper = new DataListWrapper();

        dataWrapper.setTotal(1);
        dataWrapper.setCpage(1);
        dataWrapper.setRows(10);
        dataWrapper.setState("");
        dataWrapper.setPaterId("");

        try{
            List<Map> classifyList = flowSettingService.getSortList("");

            dataWrapper.setSuccess(true);
            dataWrapper.setData(classifyList);
            dataWrapper.setMsg("操作成功！");
        }catch (Exception ex){
            dataWrapper.setSuccess(false);
            dataWrapper.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return dataWrapper;
    }


    /**
     * 流程设计：保存新建/修改流程信息
     * @param flowId 所需修改流程记录id
     * @param type 操作类型：add/edit/clone
     * @param flowType 流程类型
     * @param tplSort 正文模板
     * @param name 流程名称
     * @param sortId 流程分类id
     * @param nameRule 编号规则
     * @param nameRuleAllowEdit 允许修改编号
     * @param nameDisallowBlank 自定义标题是否必填
     * @param noticeCallback 召回时通知
     * @param noticeCancel 撤销时通知
     * @param noticeAtGoBack 退回时通知
     * @param noticeAtReject 拒绝时通知
     * @param noticeAtInterrupt 终止时通知
     * @param noticeAtFinish 结束时通知
     * @param about 备注
     * @return
     */
    @PostMapping(value = "/submit")
    @ResponseBody
    public HandlerInfo submit(@CurrentUser CurrentUserWrapper currentUser, Integer flowId, String type,
                                Integer flowType, Integer tplSort,
                                String name, Integer sortId, String nameRule, Integer nameRuleAllowEdit,
                                Integer nameDisallowBlank, Integer noticeCallback, Integer noticeCancel,
                                Integer noticeAtGoBack, Integer noticeAtReject, Integer noticeAtInterrupt,
                                Integer noticeAtFinish, String about){

        HandlerInfo handlerInfo = new HandlerInfo();
        try{
            if("add".equals(type)){ //新增
                //判断是否存在相同名称的流程，并给予提醒
                boolean isExist = flowSettingService.judgeExistFlowByName(name,0);
                if(isExist){
                    handlerInfo.setSuccess(false);
                    handlerInfo.setMsg("该名称已存在， 请重新修改流程名称。");
                }else{
                    flowSettingService.saveNewRecord(flowId,flowType,tplSort,name,sortId,nameRule,nameRuleAllowEdit,
                            nameDisallowBlank,noticeCallback,noticeCancel,noticeAtGoBack,noticeAtReject,
                            noticeAtInterrupt,noticeAtFinish,about,currentUser.getUid(),GlobalFunc.currentTimeStamp(),1);

                    handlerInfo.setSuccess(true);
                    handlerInfo.setMsg("操作成功！");
                }
            }else{

                boolean isExist = flowSettingService.judgeExistFlowByName(name,flowId);
                if(isExist) {
                    handlerInfo.setSuccess(false);
                    handlerInfo.setMsg("该名称已存在， 请重新修改流程名称。");
                }else{
                    flowSettingService.saveEditRecord(flowId,flowType,tplSort,name,sortId,nameRule,nameRuleAllowEdit,
                            nameDisallowBlank,noticeCallback,noticeCancel,noticeAtGoBack,noticeAtReject,
                            noticeAtInterrupt,noticeAtFinish,about);

                    handlerInfo.setSuccess(true);
                    handlerInfo.setMsg("操作成功！");
                }
            }

        }catch (Exception ex){
            handlerInfo.setSuccess(false);
            handlerInfo.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return handlerInfo;
    }


    /**
     * 流程设计：获取已保存的流程数据,用于列表显示
     * @param currentUser
     * @param start
     * @param limit
     * @param sortId 分类id
     * @param sname 过滤条件：流程名称
     * @return
     */
    @RequestMapping(value = "/getFlowData", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public DataListWrapper getFlowData(@CurrentUser CurrentUserWrapper currentUser,@RequestParam(value="start",required=false,defaultValue="0") Integer start,
                                   @RequestParam(value="limit",required=false,defaultValue="15") Integer limit,Integer sortId,String sname){
        DataListWrapper dataListWrapper = new DataListWrapper();

        dataListWrapper.setCpage(1);
        dataListWrapper.setRows(10);
        dataListWrapper.setState("");
        dataListWrapper.setPaterId("");
        dataListWrapper.setPageId("");
        start = (start/limit)+1;
        dataListWrapper.setLimit(limit);

        try{
            PageInfo<Map> flowList = flowSettingService.getFlowList(start,limit,sortId,sname);
            dataListWrapper.setTotal(flowList.getTotal());
            dataListWrapper.setData(flowList.getList());
            dataListWrapper.setSuccess(true);
            dataListWrapper.setMsg("操作成功！");

        }catch (Exception ex){
            dataListWrapper.setSuccess(false);
            dataListWrapper.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return dataListWrapper;
    }



    /**
     * 流程设计：修改流程
     * @return
     * @throws IOException
     */
    @PostMapping(value = "/loadFlowData")
    @ResponseBody
    public DataWrapper loadFlowData(Integer flowId){
        DataWrapper dataWrapper = new DataWrapper();

        dataWrapper.setTotal(1);
        dataWrapper.setCpage(1);
        dataWrapper.setRows(10);
        dataWrapper.setState("");
        dataWrapper.setPaterId("");

        try{
            WfSetFlow wfSetFlow = flowSettingService.getFlowInfoById(flowId);

            dataWrapper.setSuccess(true);
            dataWrapper.setData(wfSetFlow);
            dataWrapper.setMsg("操作成功！");
        }catch (Exception ex){
            dataWrapper.setSuccess(false);
            dataWrapper.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return dataWrapper;
    }



    /**
     * 流程设计：变更流程状态（启用/禁用）
     * @param flowId
     * @param type
     * @param tplSort
     * @param flowType
     * @return
     * @throws IOException
     */
    @PostMapping(value = "/changeStatus")
    @ResponseBody
    public HandlerInfo changeStatus(Integer flowId, String type, Integer tplSort,Integer flowType) {

        HandlerInfo handlerInfo = new HandlerInfo();
        Integer status = 1;

        try{
            if(flowType == 0){
                if ("stop".equals(type)){
                    status = 0;
                }else if ("use".equals(type)){
                    int nodeNum = flowSettingService.countNodeNum(flowId);
                    if (nodeNum < 2 ){ //节点数小于2时，说明仅存在开始及结束节点
                        handlerInfo.setSuccess(false);
                        handlerInfo.setMsg("此流程未有任何步骤，请设计步骤先！");
                    }
                    status = 1;
                }
            }else if("stop".equals(type)){
                status = 0;
            }else{
                status = 1;
            }
            flowSettingService.updateFlowStatus(flowId,status);
            flowSettingService.updateUseFavStatus(flowId,status);

            handlerInfo.setSuccess(true);
            handlerInfo.setMsg("操作成功！");
        }catch (Exception ex){
            handlerInfo.setSuccess(false);
            handlerInfo.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return handlerInfo;
    }



    /**
     * 流程设计：查看表单
     * @param flowId
     *
     * @return
     */
    @RequestMapping(value = "/showFormInfo", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public DataWrapper showFormInfo(Integer flowId){

        DataWrapper dataWrapper = new DataWrapper();

        dataWrapper.setTotal(1);
        dataWrapper.setCpage(1);
        dataWrapper.setRows(10);
        dataWrapper.setState("");
        dataWrapper.setPaterId("");

        try{
            String formHtml = flowSettingService.getFormHtmlByFlowId(flowId);
            if(StringUtils.isBlank(formHtml)){
                dataWrapper.setSuccess(false);
                dataWrapper.setMsg("无数据...");
            }else{

            }

            dataWrapper.setSuccess(true);
            dataWrapper.setData(formHtml);
            dataWrapper.setMsg("操作成功！");
        }catch (Exception ex){
            dataWrapper.setSuccess(false);
            dataWrapper.setMsg("操作失败，请联系系统管理员！");
            ex.printStackTrace();
        }

        return dataWrapper;

/*
        global $CNOA_DB;
        global $CNOA_SESSION;
        flowId = getpar( $_POST, "flowId", 0 );
        $_obfuscate_o5fQ1g�� = $CNOA_DB->db_getone( array( "formHtml" ), $this->t_set_flow, "WHERE `flowId`='".flowId."'" );
        if ( !$_obfuscate_o5fQ1g�� )
        {
            msg::callback( FALSE, lang( "nodata" ) );
        }
        $_obfuscate_lEGQqw�� = str_replace( "&#39;", "'", $_obfuscate_o5fQ1g��['formHtml'] );
        $_obfuscate_DxNBNSYrOIc� = app::loadapp( "wf", "flowSetForm" )->api_getHtmlElement( $_obfuscate_lEGQqw�� );
        ( $_obfuscate_DxNBNSYrOIc�, $_obfuscate_lEGQqw�� );
        $_obfuscate_fgijdEZiFS2w4Q9zQQ�� = new wfFieldFormaterForPreview( );
        $_obfuscate_lEGQqw�� = $_obfuscate_fgijdEZiFS2w4Q9zQQ��->crteateFormHtml( );
        $_obfuscate_lEGQqw�� = "<table class=\"wf_div_cttb\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n\t\t<tr>\r\n\t\t<td class=\"wf_bd wf_tl\"></td>\r\n\t\t<td class=\"wf_bd wf_t\"></td>\r\n\t\t<td class=\"wf_bd wf_tr\"></td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t<td class=\"wf_bd2 wf_l\"></td>\r\n\t\t<td style=\"padding:50px;\" class=\"wf_c wf_form_content\">".$_obfuscate_lEGQqw��."</td>\r\n\t\t<td class=\"wf_bd2 wf_r\"></td>\r\n\t\t</tr>\r\n\t\t<tr>\r\n\t\t<td class=\"wf_bd wf_bl\"></td>\r\n\t\t<td class=\"wf_bd wf_b\"></td>\r\n\t\t<td class=\"wf_bd wf_br\"></td>\r\n\t\t</tr>\r\n\t\t</table>";
        $_obfuscate_o5fQ1g��['formHtml'] = $_obfuscate_lEGQqw��;
        ( );
        $_obfuscate_SUjPN94Er7yI = new dataStore( );
        $_obfuscate_SUjPN94Er7yI->total = 0;
        $_obfuscate_SUjPN94Er7yI->data = $_obfuscate_o5fQ1g��;
        echo $_obfuscate_SUjPN94Er7yI->makeJsonData( );
        exit( );
*/

    }



    /**
     * 流程设计：获取流程分类数据
     * @return
     */
    @RequestMapping(value = "/getSortTree", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public String getSortTree(){
        String flowData = "[{\"text\":\"\\u4eba\\u4e8b\\u7c7b\",\"type\":\"\\u4eba\\u4e8b\\u7c7b\",\"sortId\":\"1\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"},{\"text\":\"\\u9879\\u76ee\\u7c7b\",\"type\":\"\\u9879\\u76ee\\u7c7b\",\"sortId\":\"9\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"},{\"text\":\"\\u884c\\u653f\\u7c7b\",\"type\":\"\\u884c\\u653f\\u7c7b\",\"sortId\":\"8\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"},{\"text\":\"\\u5e93\\u5b58\\u7c7b\",\"type\":\"\\u5e93\\u5b58\\u7c7b\",\"sortId\":\"7\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"},{\"text\":\"\\u7ee9\\u6548\\u7c7b\",\"type\":\"\\u7ee9\\u6548\\u7c7b\",\"sortId\":\"6\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"},{\"text\":\"\\u516c\\u6587\\u7c7b\",\"type\":\"\\u516c\\u6587\\u7c7b\",\"sortId\":\"5\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"},{\"text\":\"\\u91c7\\u8d2d\\u7c7b\",\"type\":\"\\u91c7\\u8d2d\\u7c7b\",\"sortId\":\"4\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"},{\"text\":\"\\u5408\\u540c\\u7c7b\",\"type\":\"\\u5408\\u540c\\u7c7b\",\"sortId\":\"3\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"},{\"text\":\"\\u8d39\\u7528\\u7c7b\",\"type\":\"\\u8d39\\u7528\\u7c7b\",\"sortId\":\"2\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"},{\"text\":\"\\u540e\\u52e4\\u7c7b\",\"type\":\"\\u540e\\u52e4\\u7c7b\",\"sortId\":\"13\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"}]";
        return flowData;
    }


    /**
     * 流程设计：获取流程分类数据,用于新增/修改流程时使用
     * @return
     */
    @RequestMapping(value = "/getSortStore", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public String getSortStore(){
        String flowData = "[{\"text\":\"\\u4eba\\u4e8b\\u7c7b\",\"type\":\"\\u4eba\\u4e8b\\u7c7b\",\"sortId\":\"1\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"},{\"text\":\"\\u9879\\u76ee\\u7c7b\",\"type\":\"\\u9879\\u76ee\\u7c7b\",\"sortId\":\"9\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"},{\"text\":\"\\u884c\\u653f\\u7c7b\",\"type\":\"\\u884c\\u653f\\u7c7b\",\"sortId\":\"8\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"},{\"text\":\"\\u5e93\\u5b58\\u7c7b\",\"type\":\"\\u5e93\\u5b58\\u7c7b\",\"sortId\":\"7\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"},{\"text\":\"\\u7ee9\\u6548\\u7c7b\",\"type\":\"\\u7ee9\\u6548\\u7c7b\",\"sortId\":\"6\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"},{\"text\":\"\\u516c\\u6587\\u7c7b\",\"type\":\"\\u516c\\u6587\\u7c7b\",\"sortId\":\"5\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"},{\"text\":\"\\u91c7\\u8d2d\\u7c7b\",\"type\":\"\\u91c7\\u8d2d\\u7c7b\",\"sortId\":\"4\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"},{\"text\":\"\\u5408\\u540c\\u7c7b\",\"type\":\"\\u5408\\u540c\\u7c7b\",\"sortId\":\"3\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"},{\"text\":\"\\u8d39\\u7528\\u7c7b\",\"type\":\"\\u8d39\\u7528\\u7c7b\",\"sortId\":\"2\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"},{\"text\":\"\\u540e\\u52e4\\u7c7b\",\"type\":\"\\u540e\\u52e4\\u7c7b\",\"sortId\":\"13\",\"iconCls\":\"icon-style-page-key\",\"leaf\":true,\"href\":\"javascript:void(0);\"}]";
        return flowData;
    }


    /**
     * 流程设计：表单设计页面
     * @return
     */
    @GetMapping(value = "/formDesigner")
    public String formDesigner(String flowId){
        return "workFlow/form_designer";
    }

    /**
     * 流程设计：流程设计页面
     * @return
     */
    @GetMapping(value = "/flowDesigner")
    public String flowDesigner(){

        return "workFlow/flow_designer";
    }


    /**
     * 流程设计：获取流程设计器数据
     * @return
     */
    @RequestMapping(value = "/loadFlowDesignData", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public DataWrapper loadFlowDesignData(Integer flowId){
        //String flowData = "{\"total\":0,\"cpage\":1,\"rows\":10,\"state\":null,\"msg\":\"\",\"paterId\":null,\"success\":true,\"data\":{\"flowXml\":null,\"fields\":[],\"steps\":[]}}";
        //return flowData;

        String flowXml = flowSettingService.getFlowXmlByFlowId(flowId);
        List<Map> fieldList = flowSettingService.getFieldListByFlowId(flowId);

        //a = array( );  fieldMapList
        //b = array( );  fieldDetailMapList
        //m = map
        //g = detailMap

        List<Map> fieldMapList = new ArrayList<Map>();
        List<Map> fieldDetailMapList = new ArrayList<Map>();

        for(Map field : fieldList){
            String odata = field.get("odata").toString().replaceAll("'","\"");
            JSONObject fromObject = JSONObject.fromObject(odata);

            Map map = new HashMap();
            map.put("id",field.get("id"));
            map.put("name",field.get("name"));
            map.put("otype",field.get("otype"));
            map.put("type",field.get("type"));
            map.put("table",field.get("id"));
            map.put("gname","&nbsp;普通表单控件");
            map.put("tableid","0");
            map.put("from","normal");
            map.put("dataType",fromObject.get("dataType"));

            if ("detailtable".equals(field.get("otype"))){
                List<Map> fieldDetailList = flowSettingService.getFieldDetailListByFid(field.get("id").toString());

                for(Map fieldDetail : fieldDetailList){
                    Map detailMap = new HashMap();
                    detailMap.put("id","d_"+fieldDetail.get("id"));
                    detailMap.put("tableid",map.get("id"));

                    detailMap.put("table",map.get("name"));
                    detailMap.put("gname","&nbsp;明细表："+map.get("name"));
                    detailMap.put("name",fieldDetail.get("name"));
                    detailMap.put("otype",fieldDetail.get("type"));
                    detailMap.put("type","text");
                    detailMap.put("dataType",fieldDetail.get("data_type"));

                    fieldDetailMapList.add(detailMap);
                }
            }
            fieldMapList.add(map);
        }

        for(Map detailMap : fieldDetailMapList){
            fieldMapList.add(detailMap);
        }

        $_obfuscate_ktRUIU_2er7vxw�� = loadPermit(flowId);

        List<Map> stepList = flowSettingService.getStepListByFlowId(flowId);
        j = array( );
        foreach ( stepList as n )
        {
            nextStep = json_decode( n['nextStep'] );
            if ( !empty( nextStep ) )
            {
                j[n['stepId']] = nextStep;
            }
        }

        List<Map> stepsList = new ArrayList<Map>();
        for(Map stepMap : stepList){
            Map map = new HashMap();

            map.put("stepId",stepMap.get("step_id"));
            map.put("stepName",stepMap.get("step_name"));
            map.put("allowReject",Integer.parseInt(stepMap.get("allow_reject").toString()) == 1 ? "on" : "");
            map.put("allowHuiqian",Integer.parseInt(stepMap.get("allow_huiqian").toString()) == 1 ? "on" : "");
            map.put("allowFenfa",Integer.parseInt(stepMap.get("allow_fenfa").toString()) == 1 ? "on" : "");
            map.put("allowTuihui",Integer.parseInt(stepMap.get("allow_tuihui").toString()) == 1 ? "on" : "");
            map.put("allowPrint",Integer.parseInt(stepMap.get("allow_print").toString()) == 1 ? "on" : "");
            map.put("allowCallback",Integer.parseInt(stepMap.get("allow_callback").toString()) == 1 ? "on" : "");
            map.put("allowCancel",Integer.parseInt(stepMap.get("allow_cancel").toString()) == 1 ? "on" : "");
            map.put("allowYijian",Integer.parseInt(stepMap.get("allow_yijian").toString()) == 1 ? "on" : "");
            map.put("allowAttachAdd",Integer.parseInt(stepMap.get("allow_attach_add").toString()) == 1 ? "on" : "");
            map.put("allowAttachView",Integer.parseInt(stepMap.get("allow_attach_view").toString()) == 1 ? "on" : "");
            map.put("allowAttachEdit",Integer.parseInt(stepMap.get("allow_attach_edit").toString()) == 1 ? "on" : "");
            map.put("allowAttachDelete",Integer.parseInt(stepMap.get("allow_attach_delete").toString()) == 1 ? "on" : "");
            map.put("allowAttachDown",Integer.parseInt(stepMap.get("allow_attach_down").toString()) == 1 ? "on" : "");
            map.put("allowWordEdit",Integer.parseInt(stepMap.get("allow_word_edit").toString()) == 1 ? "on" : "");
            map.put("allowAttachWordEdit",Integer.parseInt(stepMap.get("allow_attach_word_edit").toString()) == 1 ? "on" : "");
            map.put("allowHqAttachAdd",Integer.parseInt(stepMap.get("allow_hq_attach_add").toString()) == 1 ? "on" : "");
            map.put("allowHqAttachView",Integer.parseInt(stepMap.get("allow_hq_attach_view").toString()) == 1 ? "on" : "");
            map.put("allowHqAttachEdit",Integer.parseInt(stepMap.get("allow_hq_attach_edit").toString()) == 1 ? "on" : "");
            map.put("allowHqAttachDelete",Integer.parseInt(stepMap.get("allow_hq_attach_delete").toString()) == 1 ? "on" : "");
            map.put("allowHqAttachDown",Integer.parseInt(stepMap.get("allow_hq_attach_down").toString()) == 1 ? "on" : "");
            map.put("allowSms",Integer.parseInt(stepMap.get("allow_sms").toString()) == 1 ? "on" : "");
            map.put("doBtnText",stepMap.get("do_btn_text"));
            map.put("stepTime",stepMap.get("step_time"));
            map.put("urgeBefore",stepMap.get("urge_before"));
            map.put("urgeTarget",Integer.parseInt(stepMap.get("urge_target").toString()) == 0 ? "" : stepMap.get("urge_target"));
            map.put("fields",);
            map.put("user",);
            map.put("condition",);
            map.put("dealWay",);
            /*m['fields'] = $this->loadFieldsData( flowId, k['stepId'] );
            m['user'] = $this->loadUserData( flowId, k['stepId'] );
            m['condition'] = $this->loadConditionData( flowId, k['stepId'] );
            m['dealWay'] = $this->_loadDealWay( flowId, k['stepId'] );
            if ( !empty( k['bingids'] ) )
            {
                foreach ( j as $_obfuscate_5w�� => $_obfuscate_jVOC2dbrYw�� )
                {
                    if ( in_array( k['stepId'], $_obfuscate_jVOC2dbrYw�� ) )
                    {
                        m['childPermit'] = $this->loadChildPermit( flowId, $_obfuscate_5w�� );
                    }
                }
            }*/
            map.put("bingNames",stepMap.get("bing_names"));
            map.put("bingIds",stepMap.get("bing_ids"));
            map.put("faqiFlow",stepMap.get("faqi_flow"));
            map.put("endFlow",stepMap.get("end_flow"));
            map.put("shareFile",Integer.parseInt(stepMap.get("share_file").toString()) == 1 ? "on" : "");
            map.put("child",);
            map.put("huiqianPermit",);
            map.put("fenfaPermit",);
            /*m['child'] = $this->_loadChildData( flowId, k['stepId'] );
            m['huiqianPermit'] = $_obfuscate_ktRUIU_2er7vxw��[k['stepId']]['huiqian'];
            m['fenfaPermit'] = $_obfuscate_ktRUIU_2er7vxw��[k['stepId']]['fenfa'];*/

            stepsList.add(map);
        }

        DataWrapper dataWrapper = new DataWrapper();
        dataWrapper.setTotal(1);
        dataWrapper.setCpage(1);
        dataWrapper.setRows(10);
        dataWrapper.setState("");
        dataWrapper.setPaterId("");

        Map dataMap = new HashMap();

        dataMap.put("flowXml",flowXml);
        dataMap.put("fields",fieldMapList);
        dataMap.put("steps",stepsList);

        dataWrapper.setData(dataMap);

        return dataWrapper;
    }



    private String loadFieldsData(Integer flowId, Integer stepId){
        List<Map> stepFieldList = flowSettingService.getStepFieldsList(flowId,stepId);

        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_O6QLLac� = array( 0 );
        $_obfuscate_QrenqRn2PzA = array( 0 );
        foreach ( stepFieldList as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['from'] == 1 )
            {
                $_obfuscate_QrenqRn2PzA[] = $_obfuscate_6A��['fieldId'];
            }
            else
            {
                $_obfuscate_O6QLLac�[] = $_obfuscate_6A��['fieldId'];
            }
        }
        $_obfuscate_Pm3ZMWpPkg�� = $CNOA_DB->db_select( array( "id", "name", "otype", "type" ), $this->t_set_field, "WHERE `id` IN (".implode( ",", $_obfuscate_O6QLLac� ).") " );
        if ( !is_array( $_obfuscate_Pm3ZMWpPkg�� ) )
        {
            $_obfuscate_Pm3ZMWpPkg�� = array( );
        }
        $_obfuscate_mLjk2t6lphU� = array( );
        foreach ( $_obfuscate_Pm3ZMWpPkg�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_mLjk2t6lphU�[$_obfuscate_6A��['id']] = $_obfuscate_6A��;
        }
        $_obfuscate_2Zj6 = $CNOA_DB->db_select( array( "id", "name", "type" ), $this->t_set_field_detail, "WHERE `id` IN (".implode( ",", $_obfuscate_QrenqRn2PzA ).") " );
        if ( !is_array( $_obfuscate_2Zj6 ) )
        {
            $_obfuscate_2Zj6 = array( );
        }
        $_obfuscate_rvwe5cLI9_YAQ�� = array( );
        foreach ( $_obfuscate_2Zj6 as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_rvwe5cLI9_YAQ��[$_obfuscate_6A��['id']] = $_obfuscate_6A��;
        }
        foreach ( stepFieldList as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_SeV31Q��['write'] = $_obfuscate_6A��['write'];
            $_obfuscate_SeV31Q��['must'] = $_obfuscate_6A��['must'];
            $_obfuscate_SeV31Q��['hide'] = $_obfuscate_6A��['hide'];
            $_obfuscate_SeV31Q��['show'] = $_obfuscate_6A��['show'];
            $_obfuscate_SeV31Q��['status'] = $_obfuscate_6A��['status'];
            if ( $_obfuscate_6A��['from'] == 1 )
            {
                $_obfuscate_SeV31Q��['id'] = "d_".$_obfuscate_6A��['fieldId'];
                $_obfuscate_SeV31Q��['name'] = $_obfuscate_rvwe5cLI9_YAQ��[$_obfuscate_6A��['fieldId']]['name'];
                $_obfuscate_SeV31Q��['otype'] = $_obfuscate_rvwe5cLI9_YAQ��[$_obfuscate_6A��['fieldId']]['type'];
                $_obfuscate_SeV31Q��['type'] = "text";
            }
            else
            {
                $_obfuscate_SeV31Q��['id'] = $_obfuscate_6A��['fieldId'];
                $_obfuscate_SeV31Q��['name'] = $_obfuscate_mLjk2t6lphU�[$_obfuscate_6A��['fieldId']]['name'];
                $_obfuscate_SeV31Q��['otype'] = $_obfuscate_mLjk2t6lphU�[$_obfuscate_6A��['fieldId']]['otype'];
                $_obfuscate_SeV31Q��['type'] = $_obfuscate_mLjk2t6lphU�[$_obfuscate_6A��['fieldId']]['type'];
            }
            $_obfuscate_6RYLWQ��[] = $_obfuscate_SeV31Q��;
        }
        foreach ( $_obfuscate_6RYLWQ�� as $_obfuscate_Vwty => $_obfuscate_VgKtFeg� )
        {
            if ( $_obfuscate_VgKtFeg�['otype'] == "calculate" )
            {
                $_obfuscate_6RYLWQ��[$_obfuscate_Vwty]['write'] = 0;
            }
        }
        return $_obfuscate_6RYLWQ��;
    }



    private String loadUserData(Integer flowId,Integer stepId )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_step_user, "WHERE `flowId` = '".flowId."' AND `stepId` = '{stepId}' " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_vwGQSA�� = array( );
        $_obfuscate_YsVdvv0c = array( );
        $_obfuscate_fdSO3eSyAQ�� = array( );
        $_obfuscate_6mlyHg�� = array( );
        $_obfuscate_m5leXC9_Zg�� = array( );
        $_obfuscate_ZxVMfNbI5ugv4Ks� = array( );
        $_obfuscate_flKx1g�� = array( );
        $_obfuscate_Lw9wXKzqBg�� = array( 0 );
        $_obfuscate_MtpzvDgUD7YblQ�� = array( 0 );
        $_obfuscate__ooZFvTbHA�� = array( 0 );
        $_obfuscate_HmJYx_HCew�� = array( 0 );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['type'] == "dept" )
            {
                $_obfuscate_Lw9wXKzqBg��[] = $_obfuscate_6A��['dept'];
            }
            else if ( $_obfuscate_6A��['type'] == "people" )
            {
                $_obfuscate__ooZFvTbHA��[] = $_obfuscate_6A��['people'];
            }
            else if ( $_obfuscate_6A��['type'] == "exclude" )
            {
                $_obfuscate_JF89pTCN4WiNCg��[] = $_obfuscate_6A��['exclude'];
            }
            else if ( $_obfuscate_6A��['type'] == "station" )
            {
                $_obfuscate_MtpzvDgUD7YblQ��[] = $_obfuscate_6A��['station'];
            }
            else if ( $_obfuscate_6A��['type'] == "rule" )
            {
                $_obfuscate_MtpzvDgUD7YblQ��[] = $_obfuscate_6A��['rule_s'];
            }
            else if ( $_obfuscate_6A��['type'] == "kong" )
            {
                $_obfuscate_HmJYx_HCew��[] = $_obfuscate_6A��['kong'];
            }
            else
            {
                $_obfuscate_Lw9wXKzqBg��[] = $_obfuscate_6A��['dept'];
                $_obfuscate_MtpzvDgUD7YblQ��[] = $_obfuscate_6A��['station'];
            }
        }
        $_obfuscate_dga5p5gjYJ23VQ�� = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate__ooZFvTbHA�� );
        $_obfuscate_dQ8cgLyveESU = app::loadapp( "main", "user" )->api_getUserNamesByUids( $_obfuscate_JF89pTCN4WiNCg�� );
        $_obfuscate_uLf44wk1NRqS = app::loadapp( "main", "station" )->api_getNamesByIds( $_obfuscate_MtpzvDgUD7YblQ�� );
        $_obfuscate_2w�� = app::loadapp( "main", "struct" )->api_getNamesByIds( $_obfuscate_Lw9wXKzqBg�� );
        $_obfuscate__Ja_D7YH = $CNOA_DB->db_select( array( "id", "name" ), $this->t_set_field, "WHERE `id` IN (".implode( ",", $_obfuscate_HmJYx_HCew�� ).") " );
        if ( !is_array( $_obfuscate__Ja_D7YH ) )
        {
            $_obfuscate__Ja_D7YH = array( );
        }
        $_obfuscate_HmJYx_HCew�� = array( );
        foreach ( $_obfuscate__Ja_D7YH as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_HmJYx_HCew��[$_obfuscate_6A��['id']] = $_obfuscate_6A��['name'];
        }
        $_obfuscate_EzJXRY5VDKzFxg�� = array( "faqi" => "[发起人]", "zhuban" => "[主办人]", "faqiself" => "[发起人自己]", "beforepeop" => "[所有已办理人]", "myDept" => "[所属部门]", "upDept" => "[上级部门]", "myUpDept" => "[所属部门和上级部门]", "allDept" => "[所属部门及所有上级部门]" );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['type'] == "dept" )
            {
                $_obfuscate_XRvPgP5V0t4� = $_obfuscate_2w��[$_obfuscate_6A��['dept']];
                $_obfuscate_SeV31Q��['id'] = $_obfuscate_6A��['dept'];
                $_obfuscate_SeV31Q��['text'] = isset( $_obfuscate_XRvPgP5V0t4� ) ? $_obfuscate_XRvPgP5V0t4� : lang( "deptBeenDel" );
                $_obfuscate_vwGQSA��[] = $_obfuscate_SeV31Q��;
            }
            else if ( $_obfuscate_6A��['type'] == "people" )
            {
                $_obfuscate__Wi6396IheA� = $_obfuscate_dga5p5gjYJ23VQ��[$_obfuscate_6A��['people']]['truename'];
                $_obfuscate_SeV31Q��['id'] = $_obfuscate_6A��['people'];
                $_obfuscate_SeV31Q��['text'] = isset( $_obfuscate__Wi6396IheA� ) ? $_obfuscate__Wi6396IheA� : lang( "userNotExists" );
                $_obfuscate_YsVdvv0c[] = $_obfuscate_SeV31Q��;
            }
            else if ( $_obfuscate_6A��['type'] == "exclude" )
            {
                $_obfuscate_RoxLIlJRRAIfNYKoClY = $_obfuscate_dQ8cgLyveESU[$_obfuscate_6A��['exclude']]['truename'];
                $_obfuscate_SeV31Q��['id'] = $_obfuscate_6A��['exclude'];
                $_obfuscate_SeV31Q��['text'] = isset( $_obfuscate_RoxLIlJRRAIfNYKoClY ) ? $_obfuscate_RoxLIlJRRAIfNYKoClY : lang( "userNotExists" );
                $_obfuscate_fdSO3eSyAQ��[] = $_obfuscate_SeV31Q��;
            }
            else if ( $_obfuscate_6A��['type'] == "rule" )
            {
                $_obfuscate_SeV31Q��['dept'] = $_obfuscate_6A��['rule_d'];
                $_obfuscate_SeV31Q��['id'] = $_obfuscate_6A��['rule_s'];
                $_obfuscate_SeV31Q��['people'] = $_obfuscate_6A��['rule_p'];
                if ( $_obfuscate_6A��['rule_p'] == "faqiself" )
                {
                    $_obfuscate_SeV31Q��['text'] = $_obfuscate_EzJXRY5VDKzFxg��[$_obfuscate_6A��['rule_p']];
                }
                else if ( $_obfuscate_6A��['rule_p'] == "beforepeop" )
                {
                    $_obfuscate_SeV31Q��['text'] = $_obfuscate_EzJXRY5VDKzFxg��[$_obfuscate_6A��['rule_p']];
                }
                else
                {
                    $_obfuscate_SeV31Q��['text'] = $_obfuscate_EzJXRY5VDKzFxg��[$_obfuscate_6A��['rule_p']]." ".$_obfuscate_EzJXRY5VDKzFxg��[$_obfuscate_6A��['rule_d']]." [(".lang( "station" ).")".$_obfuscate_uLf44wk1NRqS[$_obfuscate_6A��['rule_s']]."]";
                }
                $_obfuscate_6mlyHg��[] = $_obfuscate_SeV31Q��;
            }
            else if ( $_obfuscate_6A��['type'] == "station" )
            {
                $_obfuscate_SeV31Q��['id'] = $_obfuscate_6A��['station'];
                $_obfuscate_SeV31Q��['text'] = $_obfuscate_uLf44wk1NRqS[$_obfuscate_6A��['station']];
                $_obfuscate_m5leXC9_Zg��[] = $_obfuscate_SeV31Q��;
            }
            else if ( $_obfuscate_6A��['type'] == "deptstation" )
            {
                $_obfuscate_XRvPgP5V0t4� = $_obfuscate_2w��[$_obfuscate_6A��['dept']];
                $_obfuscate_Ox8sY3sXWruQLLY� = $_obfuscate_uLf44wk1NRqS[$_obfuscate_6A��['station']];
                if ( $_obfuscate_XRvPgP5V0t4� && $_obfuscate_Ox8sY3sXWruQLLY� )
                {
                    $_obfuscate_SeV31Q��['text'] = "[".$_obfuscate_XRvPgP5V0t4�."] [(".lang( "station" ).")".$_obfuscate_Ox8sY3sXWruQLLY�."]";
                }
                else
                {
                    $_obfuscate_SeV31Q��['text'] = lang( "ruleNotLegitimate" );
                }
                $_obfuscate_SeV31Q��['id'] = $_obfuscate_6A��['dept'].",".$_obfuscate_6A��['station'];
                $_obfuscate_ZxVMfNbI5ugv4Ks�[] = $_obfuscate_SeV31Q��;
            }
            else if ( $_obfuscate_6A��['type'] == "kong" )
            {
                $_obfuscate_SeV31Q��['id'] = $_obfuscate_6A��['kong'];
                $_obfuscate_SeV31Q��['text'] = $_obfuscate_HmJYx_HCew��[$_obfuscate_6A��['kong']];
                $_obfuscate_flKx1g��[] = $_obfuscate_SeV31Q��;
            }
        }
        $_obfuscate_6RYLWQ��['dept'] = $_obfuscate_vwGQSA��;
        $_obfuscate_6RYLWQ��['kong'] = array( );
        $_obfuscate_6RYLWQ��['people'] = $_obfuscate_YsVdvv0c;
        $_obfuscate_6RYLWQ��['exclude'] = $_obfuscate_fdSO3eSyAQ��;
        $_obfuscate_6RYLWQ��['rule'] = $_obfuscate_6mlyHg��;
        $_obfuscate_6RYLWQ��['station'] = $_obfuscate_m5leXC9_Zg��;
        $_obfuscate_6RYLWQ��['deptStation'] = $_obfuscate_ZxVMfNbI5ugv4Ks�;
        $_obfuscate_6RYLWQ��['kong'] = $_obfuscate_flKx1g��;
        return $_obfuscate_6RYLWQ��;
    }



    private function loadConditionData(Integer flowId, Integer stepId )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_step_condition, "WHERE `flowId` = '".flowId."' AND `stepId` = '{stepId}' ORDER BY `id` ASC " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_MnVVbyZQFVw� = array( );
        $_obfuscate_wO3K = array( );
        $_obfuscate_eBU_Sjc� = array( );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_MnVVbyZQFVw�[$_obfuscate_6A��['nextStepId']][] = $_obfuscate_6A��;
            if ( $_obfuscate_6A��['head'] == 1 || $_obfuscate_6A��['head'] == 0 && $_obfuscate_6A��['pid'] == 0 )
            {
                $_obfuscate_wO3K[$_obfuscate_6A��['nextStepId']][] = $_obfuscate_6A��['id'];
            }
            else
            {
                $_obfuscate_eBU_Sjc�[$_obfuscate_6A��['pid']][] = $_obfuscate_6A��;
            }
            $_obfuscate_Hb1v[$_obfuscate_6A��['id']] = $_obfuscate_6A��;
        }
        $_obfuscate_j9sJes� = array( );
        foreach ( $_obfuscate_wO3K as $_obfuscate_5w�� => $_obfuscate_YupB5g�� )
        {
            $_obfuscate_ = array( );
            foreach ( $_obfuscate_YupB5g�� as $_obfuscate_6A�� )
            {
                $_obfuscate_SeV31Q��['text'] = $_obfuscate_Hb1v[$_obfuscate_6A��]['text'];
                $_obfuscate_SeV31Q��['orAnd'] = $_obfuscate_Hb1v[$_obfuscate_6A��]['orAnd'];
                $_obfuscate_Wlf9Dg��['name'] = $_obfuscate_Hb1v[$_obfuscate_6A��]['name'];
                $_obfuscate_Wlf9Dg��['rule'] = $_obfuscate_Hb1v[$_obfuscate_6A��]['rule'];
                $_obfuscate_Wlf9Dg��['ovalue'] = $_obfuscate_Hb1v[$_obfuscate_6A��]['ovalue'];
                $_obfuscate_Wlf9Dg��['orAnd'] = $_obfuscate_Hb1v[$_obfuscate_6A��]['orAnd'];
                if ( in_array( $_obfuscate_Hb1v[$_obfuscate_6A��]['fieldType'], array( "n_n", "n_s", "n_d", "d_n", "d_s", "d_d" ) ) )
                {
                    $_obfuscate_Wlf9Dg��['name'] = "s|".$_obfuscate_Hb1v[$_obfuscate_6A��]['fieldType'];
                }
                $_obfuscate_SeV31Q��['items'][] = $_obfuscate_Wlf9Dg��;
                if ( 0 < count( $_obfuscate_eBU_Sjc�[$_obfuscate_6A��] ) )
                {
                    $_obfuscate_SeV31Q��['left'] = 1;
                    $_obfuscate_SeV31Q��['right'] = 1;
                    foreach ( $_obfuscate_eBU_Sjc�[$_obfuscate_6A��] as $_obfuscate_bRQ� )
                    {
                        $_obfuscate_Wlf9Dg��['name'] = $_obfuscate_bRQ�['name'];
                        $_obfuscate_Wlf9Dg��['rule'] = $_obfuscate_bRQ�['rule'];
                        $_obfuscate_Wlf9Dg��['ovalue'] = $_obfuscate_bRQ�['ovalue'];
                        $_obfuscate_Wlf9Dg��['orAnd'] = $_obfuscate_bRQ�['orAnd'];
                        if ( in_array( $_obfuscate_bRQ�['fieldType'], array( "n_n", "n_s", "n_d", "d_n", "d_s", "d_d" ) ) )
                        {
                            $_obfuscate_Wlf9Dg��['name'] = "s|".$_obfuscate_bRQ�['fieldType'];
                        }
                        $_obfuscate_SeV31Q��['items'][] = $_obfuscate_Wlf9Dg��;
                    }
                }
                else
                {
                    $_obfuscate_SeV31Q��['left'] = 0;
                    $_obfuscate_SeV31Q��['right'] = 0;
                }
                $_obfuscate_[] = $_obfuscate_SeV31Q��;
                unset( $_obfuscate_SeV31Q�� );
            }
            $_obfuscate_6RYLWQ��['id'] = $_obfuscate_5w��;
            $_obfuscate_6RYLWQ��['items'] = $_obfuscate_;
            $_obfuscate_j9sJes�[] = $_obfuscate_6RYLWQ��;
            unset( $_obfuscate_AGk1QY4� );
        }
        return $_obfuscate_j9sJes�;
    }



    private function loadChildPermit(Integer flowId,Integer stepId )
    {
        global $CNOA_DB;
        $_obfuscate_mPAjEGLn = $CNOA_DB->db_select( "*", $this->t_set_step_fields, "WHERE `flowId` = '".flowId."' AND `stepId` = '{stepId}' " );
        if ( !is_array( $_obfuscate_mPAjEGLn ) )
        {
            $_obfuscate_mPAjEGLn = array( );
        }
        $_obfuscate_6RYLWQ�� = array( );
        $_obfuscate_O6QLLac� = array( 0 );
        $_obfuscate_QrenqRn2PzA = array( 0 );
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            if ( $_obfuscate_6A��['from'] == 1 )
            {
                $_obfuscate_QrenqRn2PzA[] = $_obfuscate_6A��['fieldId'];
            }
            else
            {
                $_obfuscate_O6QLLac�[] = $_obfuscate_6A��['fieldId'];
            }
        }
        $_obfuscate_Pm3ZMWpPkg�� = $CNOA_DB->db_select( array( "id", "name", "otype", "type" ), $this->t_set_field, "WHERE `id` IN (".implode( ",", $_obfuscate_O6QLLac� ).") " );
        if ( !is_array( $_obfuscate_Pm3ZMWpPkg�� ) )
        {
            $_obfuscate_Pm3ZMWpPkg�� = array( );
        }
        $_obfuscate_mLjk2t6lphU� = array( );
        foreach ( $_obfuscate_Pm3ZMWpPkg�� as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_mLjk2t6lphU�[$_obfuscate_6A��['id']] = $_obfuscate_6A��;
        }
        $_obfuscate_2Zj6 = $CNOA_DB->db_select( array( "id", "name", "type" ), $this->t_set_field_detail, "WHERE `id` IN (".implode( ",", $_obfuscate_QrenqRn2PzA ).") " );
        if ( !is_array( $_obfuscate_2Zj6 ) )
        {
            $_obfuscate_2Zj6 = array( );
        }
        $_obfuscate_rvwe5cLI9_YAQ�� = array( );
        foreach ( $_obfuscate_2Zj6 as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_rvwe5cLI9_YAQ��[$_obfuscate_6A��['id']] = $_obfuscate_6A��;
        }
        foreach ( $_obfuscate_mPAjEGLn as $_obfuscate_5w�� => $_obfuscate_6A�� )
        {
            $_obfuscate_SeV31Q��['status'] = $_obfuscate_6A��['status'];
            $_obfuscate_SeV31Q��['stepId'] = stepId;
            if ( $_obfuscate_6A��['from'] == 1 )
            {
                $_obfuscate_SeV31Q��['id'] = "d_".$_obfuscate_6A��['fieldId'];
                $_obfuscate_SeV31Q��['name'] = $_obfuscate_rvwe5cLI9_YAQ��[$_obfuscate_6A��['fieldId']]['name'];
                $_obfuscate_SeV31Q��['otype'] = $_obfuscate_rvwe5cLI9_YAQ��[$_obfuscate_6A��['fieldId']]['type'];
                $_obfuscate_SeV31Q��['type'] = "text";
            }
            else
            {
                $_obfuscate_SeV31Q��['id'] = $_obfuscate_6A��['fieldId'];
                $_obfuscate_SeV31Q��['name'] = $_obfuscate_mLjk2t6lphU�[$_obfuscate_6A��['fieldId']]['name'];
                $_obfuscate_SeV31Q��['otype'] = $_obfuscate_mLjk2t6lphU�[$_obfuscate_6A��['fieldId']]['otype'];
                $_obfuscate_SeV31Q��['type'] = $_obfuscate_mLjk2t6lphU�[$_obfuscate_6A��['fieldId']]['type'];
            }
            $_obfuscate_6RYLWQ��[] = $_obfuscate_SeV31Q��;
        }
        return $_obfuscate_6RYLWQ��;
    }



    private function loadPermit(Integer flowId)
    {
        List<Map> stepPermitList = flowSettingService.getStepPermitListByFlowId(flowId);

        a = b = c = array( );

        for(Map stepPermit : stepPermitList){
            if ( !empty( stepPermit['user'] ) )
            {
                a[] = stepPermit['user'];
            }
            if ( !empty( stepPermit['dept'] ) )
            {
                b[] = stepPermit['dept'];
            }
            if ( !empty( stepPermit['rule'] ) )
            {
                $_obfuscate_6mlyHg�� = explode( ",", stepPermit['rule'] );
                foreach ( $_obfuscate_6mlyHg�� as $_obfuscate_OQ�� )
                {
                    list( ,  ) = explode( "|", $_obfuscate_OQ�� );
                    c[] = $Var_384;
                }
            }
        }

        foreach ( stepPermitList as $_obfuscate_6A�� )
        {

        }
        $_obfuscate_rNTRvK6XhPsP = $_obfuscate_mMnyN6u83w�� = $_obfuscate_wtaEWTJi_Q�� = array( );
        $_obfuscate_9Weh8jtBTtqrLw�� = $CNOA_DB->db_select( "*", $this->t_set_autoFenfa, "WHERE `flowId`=".flowId );
        if ( !is_array( $_obfuscate_9Weh8jtBTtqrLw�� ) )
        {
            $_obfuscate_9Weh8jtBTtqrLw�� = array( );
        }
        foreach ( $_obfuscate_9Weh8jtBTtqrLw�� as $_obfuscate_VgKtFeg� )
        {
            $_obfuscate_mMnyN6u83w��[$_obfuscate_VgKtFeg�['stepId']] = array_unique( explode( ",", $_obfuscate_VgKtFeg�['uids'] ) );
            $_obfuscate_wtaEWTJi_Q��[$_obfuscate_VgKtFeg�['stepId']] = app::loadapp( "main", "user" )->api_getUserTruenameByUid( explode( ",", $_obfuscate_VgKtFeg�['uids'] ) );
        }
        foreach ( $_obfuscate_mMnyN6u83w�� as $_obfuscate_Vwty => $_obfuscate_VgKtFeg� )
        {
            foreach ( $_obfuscate_VgKtFeg� as $_obfuscate_LQ8UKg�� )
            {
                $_obfuscate_rNTRvK6XhPsP[$_obfuscate_Vwty]['autoFenfa'][] = array(
                    $_obfuscate_LQ8UKg��,
                    $_obfuscate_wtaEWTJi_Q��[$_obfuscate_Vwty][$_obfuscate_LQ8UKg��]
                );
            }
        }
        $_obfuscate__Wi6396IheA� = $_obfuscate_XRvPgP5V0t4� = $_obfuscate_m5leXC9_Zg�� = array( );
        if ( 0 < count( a ) )
        {
            a = array_unique( explode( ",", implode( ",", a ) ) );
            $_obfuscate__Wi6396IheA� = app::loadapp( "main", "user" )->api_getUserTruenameByUid( a );
        }
        if ( 0 < count( b ) )
        {
            b = array_unique( explode( ",", implode( ",", b ) ) );
            $_obfuscate_XRvPgP5V0t4� = app::loadapp( "main", "struct" )->api_getNamesByIds( b );
        }
        if ( 0 < count( c ) )
        {
            c = array_unique( c );
            $_obfuscate_m5leXC9_Zg�� = app::loadapp( "main", "station" )->api_getNamesByIds( c );
        }
        $_obfuscate_dhMyCb2idSLFhA�� = array( "faqi" => "发起人", "zhuban" => "主办人" );
        $_obfuscate_ZoJ6n0QmFoI� = array( "myDept" => "所属部门", "upDept" => "上级部门", "myUpDept" => "所属部门和上级部门", "allDept" => "所属部门及所有上级部门" );
        $_obfuscate_ktRUIU_2er7vxw�� = array( );
        foreach ( stepPermitList as $_obfuscate_6A�� )
        {
            $_obfuscate_eVTMIa1A = a = b = $_obfuscate_6mlyHg�� = array( );
            if ( !empty( $_obfuscate_6A��['user'] ) )
            {
                a = explode( ",", $_obfuscate_6A��['user'] );
            }
            if ( !empty( $_obfuscate_6A��['dept'] ) )
            {
                b = explode( ",", $_obfuscate_6A��['dept'] );
            }
            if ( !empty( $_obfuscate_6A��['rule'] ) )
            {
                $_obfuscate_6mlyHg�� = explode( ",", $_obfuscate_6A��['rule'] );
            }
            foreach ( a as $_obfuscate_0W8� )
            {
                $_obfuscate_eVTMIa1A['user'][] = array(
                    $_obfuscate_0W8�,
                    $_obfuscate__Wi6396IheA�[$_obfuscate_0W8�]
                );
            }
            foreach ( b as $_obfuscate_0W8� )
            {
                $_obfuscate_eVTMIa1A['dept'][] = array(
                    $_obfuscate_0W8�,
                    $_obfuscate_XRvPgP5V0t4�[$_obfuscate_0W8�]
                );
            }
            foreach ( $_obfuscate_6mlyHg�� as $_obfuscate_TAxu )
            {
                list( $_obfuscate_8w��, $_obfuscate_5g��, $p ) = explode( "|", $_obfuscate_TAxu );
                $_obfuscate_eVTMIa1A['rule'][] = array(
                    $_obfuscate_TAxu,
                    "[".$_obfuscate_dhMyCb2idSLFhA��[$_obfuscate_8w��]."][{$_obfuscate_ZoJ6n0QmFoI�[$_obfuscate_5g��]}][{$_obfuscate_m5leXC9_Zg��[$p]}]"
                );
            }
            switch ( $_obfuscate_6A��['operate'] )
            {
                case self::$OPERATE_TYPE_HUIQIAN :
                    $_obfuscate_ktRUIU_2er7vxw��[$_obfuscate_6A��['stepId']]['huiqian'] = $_obfuscate_eVTMIa1A;
                    break;
                case self::$OPERATE_TYPE_FENFA :
                    $_obfuscate_ktRUIU_2er7vxw��[$_obfuscate_6A��['stepId']]['fenfa'] = $_obfuscate_eVTMIa1A;
            }
        }
        foreach ( $_obfuscate_rNTRvK6XhPsP as $_obfuscate_Vwty => $_obfuscate_VgKtFeg� )
        {
            $_obfuscate_ktRUIU_2er7vxw��[$_obfuscate_Vwty]['fenfa']['autoFenfa'] = $_obfuscate_rNTRvK6XhPsP[$_obfuscate_Vwty]['autoFenfa'];
        }
        return $_obfuscate_ktRUIU_2er7vxw��;
    }



    //&task=loadFormHtmlForOrder
    /**
     *
     * @return
     */
    @RequestMapping(value = "/getPrintPage", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public String getPrintPage(String flowId){
        String formData = "{\"success\":true,\"pageset\":\"{\\\"pageSize\\\":\\\"a4page\\\",\\\"pageDir\\\":\\\"lengthways\\\",\\\"pageUp\\\":\\\"10\\\",\\\"pageDown\\\":\\\"10\\\",\\\"pageLeft\\\":\\\"10\\\",\\\"pageRight\\\":\\\"10\\\"}\"}";
        return formData;
    }


    /**
     *
     * @return
     */
    @RequestMapping(value = "/config", method = {RequestMethod.POST,RequestMethod.GET})
    @ResponseBody
    public String config(){
        String configData = "{\"imageActionName\":\"uploadimage\",\"imageFieldName\":\"upfile\",\"imageMaxSize\":22048000,\"imageAllowFiles\":[\".png\",\".jpg\",\".jpeg\",\".gif\",\".bmp\"],\"imageCompressEnable\":true,\"imageCompressBorder\":1600,\"imageInsertAlign\":\"none\",\"imageUrlPrefix\":\"\",\"imagePathFormat\":\"..\\/..\\/..\\/file\\/common\\/wf\\/formimage\\/201807\\/\",\"scrawlActionName\":\"uploadscrawl\",\"scrawlFieldName\":\"upfile\",\"scrawlPathFormat\":\"\\/ueditor\\/php\\/upload\\/image\\/{yyyy}{mm}{dd}\\/{time}{rand:6}\",\"scrawlMaxSize\":2048000,\"scrawlUrlPrefix\":\"\",\"scrawlInsertAlign\":\"none\",\"snapscreenActionName\":\"uploadimage\",\"snapscreenPathFormat\":\"\\/ueditor\\/php\\/upload\\/image\\/{yyyy}{mm}{dd}\\/{time}{rand:6}\",\"snapscreenUrlPrefix\":\"\",\"snapscreenInsertAlign\":\"none\",\"catcherLocalDomain\":[\"127.0.0.1\",\"localhost\",\"img.baidu.com\"],\"catcherActionName\":\"catchimage\",\"catcherFieldName\":\"source\",\"catcherPathFormat\":\"..\\/..\\/..\\/file\\/common\\/wf\\/formimage\\/201807\\/\",\"catcherUrlPrefix\":\"\",\"catcherMaxSize\":2048000,\"catcherAllowFiles\":[\".png\",\".jpg\",\".jpeg\",\".gif\",\".bmp\"],\"videoActionName\":\"uploadvideo\",\"videoFieldName\":\"upfile\",\"videoPathFormat\":\"\\/ueditor\\/php\\/upload\\/video\\/{yyyy}{mm}{dd}\\/{time}{rand:6}\",\"videoUrlPrefix\":\"\",\"videoMaxSize\":102400000,\"videoAllowFiles\":[\".flv\",\".swf\",\".mkv\",\".avi\",\".rm\",\".rmvb\",\".mpeg\",\".mpg\",\".ogg\",\".ogv\",\".mov\",\".wmv\",\".mp4\",\".webm\",\".mp3\",\".wav\",\".mid\"],\"fileActionName\":\"uploadfile\",\"fileFieldName\":\"upfile\",\"filePathFormat\":\"\\/ueditor\\/php\\/upload\\/file\\/{yyyy}{mm}{dd}\\/{time}{rand:6}\",\"fileUrlPrefix\":\"\",\"fileMaxSize\":51200000,\"fileAllowFiles\":[\".png\",\".jpg\",\".jpeg\",\".gif\",\".bmp\",\".flv\",\".swf\",\".mkv\",\".avi\",\".rm\",\".rmvb\",\".mpeg\",\".mpg\",\".ogg\",\".ogv\",\".mov\",\".wmv\",\".mp4\",\".webm\",\".mp3\",\".wav\",\".mid\",\".rar\",\".zip\",\".tar\",\".gz\",\".7z\",\".bz2\",\".cab\",\".iso\",\".doc\",\".docx\",\".xls\",\".xlsx\",\".ppt\",\".pptx\",\".pdf\",\".txt\",\".md\",\".xml\"],\"imageManagerActionName\":\"listimage\",\"imageManagerListPath\":\"\\/ueditor\\/php\\/upload\\/image\\/\",\"imageManagerListSize\":20,\"imageManagerUrlPrefix\":\"\",\"imageManagerInsertAlign\":\"none\",\"imageManagerAllowFiles\":[\".png\",\".jpg\",\".jpeg\",\".gif\",\".bmp\"],\"fileManagerActionName\":\"listfile\",\"fileManagerListPath\":\"\\/ueditor\\/php\\/upload\\/file\\/\",\"fileManagerUrlPrefix\":\"\",\"fileManagerListSize\":20,\"fileManagerAllowFiles\":[\".png\",\".jpg\",\".jpeg\",\".gif\",\".bmp\",\".flv\",\".swf\",\".mkv\",\".avi\",\".rm\",\".rmvb\",\".mpeg\",\".mpg\",\".ogg\",\".ogv\",\".mov\",\".wmv\",\".mp4\",\".webm\",\".mp3\",\".wav\",\".mid\",\".rar\",\".zip\",\".tar\",\".gz\",\".7z\",\".bz2\",\".cab\",\".iso\",\".doc\",\".docx\",\".xls\",\".xlsx\",\".ppt\",\".pptx\",\".pdf\",\".txt\",\".md\",\".xml\"]}";
        return configData;
    }
}
