package org.chencc.service.impl;

import com.github.pagehelper.PageHelper;
import com.github.pagehelper.PageInfo;
import org.apache.commons.lang.StringUtils;
import org.chencc.mapper.FlowSettingMapper;
import org.chencc.model.AssetsHistorical;
import org.chencc.model.WfSetFlow;
import org.chencc.model.WfSort;
import org.chencc.service.FlowSettingService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.*;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/29
 * @Time:下午9:22
 */
@Service
@Transactional
public class FlowSettingServiceImpl implements FlowSettingService {
    @Autowired
    private FlowSettingMapper flowSettingMapper;

    @Override
    public List<Map> getSortList(String sname) {
        StringBuffer sqlBuffer = new StringBuffer("select ws.sort_id as sortId,ws.name,ws.note,ws.system,ws.order from db_wf_sort as ws where 1=1");
        if(StringUtils.isNotBlank(sname)){
            sqlBuffer.append(" and ws.name like '%"+sname+"%'");
        }
        sqlBuffer.append(" order by ws.order");

        return flowSettingMapper.getSortList(String.valueOf(sqlBuffer));
    }

    @Override
    public WfSort loadWfSortById(Integer sortId) {
        return flowSettingMapper.loadWfSortById(sortId);
    }

    @Override
    public void updateSortOrder(Integer sortId, Integer order) {
        flowSettingMapper.updateSortOrder(sortId, order);
    }

    @Override
    public void deleteSortList(String ids) {
        flowSettingMapper.deleteSortList(ids);
    }

    @Override
    public boolean judgeExistFlowByName(String name,Integer flowId) {
        List<Map> flowList = new ArrayList<Map>();
        if(flowId == 0){
            flowList = flowSettingMapper.getFlowListByName(name);
        }else{
            flowList = flowSettingMapper.getFlowListByNameAndId(name,flowId);
        }

        if(flowList.isEmpty()){
            return false;
        }else{
            return true;
        }
    }

    @Override
    public void saveNewRecord(Integer flowId, Integer flowType, Integer tplSort,
                              String name, Integer sortId, String nameRule,
                              Integer nameRuleAllowEdit, Integer nameDisallowBlank,
                              Integer noticeCallback, Integer noticeCancel,
                              Integer noticeAtGoBack, Integer noticeAtReject,
                              Integer noticeAtInterrupt, Integer noticeAtFinish,
                              String about, Integer uid, Integer postTime,Integer status) {
        flowSettingMapper.saveNewRecord(flowId,flowType,tplSort,name,sortId,nameRule,nameRuleAllowEdit,
                nameDisallowBlank,noticeCallback,noticeCancel,noticeAtGoBack,noticeAtReject,
                noticeAtInterrupt,noticeAtFinish,about,uid,postTime,status);
    }

    @Override
    public void saveEditRecord(Integer flowId, Integer flowType, Integer tplSort,
                        String name, Integer sortId, String nameRule, Integer nameRuleAllowEdit,
                        Integer nameDisallowBlank, Integer noticeCallback, Integer noticeCancel,
                        Integer noticeAtGoBack, Integer noticeAtReject, Integer noticeAtInterrupt,
                        Integer noticeAtFinish, String about){
        flowSettingMapper.saveEditRecord(flowId,flowType,tplSort,name,sortId,nameRule,nameRuleAllowEdit,
                nameDisallowBlank,noticeCallback,noticeCancel,noticeAtGoBack,noticeAtReject,
                noticeAtInterrupt,noticeAtFinish,about);
    }

    @Override
    public PageInfo<Map> getFlowList(Integer start, Integer limit, Integer sortId, String sname) {
        StringBuffer sqlBuffer = new StringBuffer("select sf.flow_id as flowId,sf.name,sf.sort_id as sortId," +
                "sf.status,sf.tpl_sort as tplSort,sf.flow_type as flowType,sf.page_set as pageSet,(select a.name from db_wf_sort as a where a.sort_id = sf.sort_id) as sort" +
                " from db_wf_set_flow as sf where 1=1");

        if(null != sortId && sortId != 0){
            sqlBuffer.append(" and sf.sort_id = "+sortId+"");
        }

        if(StringUtils.isNotBlank(sname)){
            sqlBuffer.append(" and sf.name like '%"+sname+"%'");
        }

        sqlBuffer.append(" order by sf.post_time desc");

        PageHelper.startPage(start,limit);
        List<Map> flowList = flowSettingMapper.getFlowList(sqlBuffer.toString());
        PageInfo<Map> pageInfo = new PageInfo<Map>(flowList);

        return pageInfo;
    }

    @Override
    public WfSetFlow getFlowInfoById(Integer flowId) {
        return flowSettingMapper.getFlowInfoById(flowId);
    }

    @Override
    public int countNodeNum(Integer flowId) {
        return flowSettingMapper.getFlowStepById(flowId).size();
    }

    @Override
    public void updateFlowStatus(Integer flowId, Integer status) {
        flowSettingMapper.updateFlowStatus(flowId, status);
    }

    @Override
    public void updateUseFavStatus(Integer flowId, Integer status) {
        flowSettingMapper.updateUseFavStatus(flowId, status);
    }

    @Override
    public String getFormHtmlByFlowId(Integer flowId) {
        return flowSettingMapper.getFormHtmlByFlowId(flowId);
    }

    @Override
    public String getFlowXmlByFlowId(Integer flowId) {
        return flowSettingMapper.getFlowXmlByFlowId(flowId);
    }

    @Override
    public List<Map> getFieldListByFlowId(Integer flowId){
        return flowSettingMapper.getFieldListByFlowId(flowId);
    }

    @Override
    public List<Map> getFieldDetailListByFid(String fid) {
        return flowSettingMapper.getFieldDetailListByFid(fid);
    }

    @Override
    public List<Map> getStepListByFlowId(Integer flowId) {
        return flowSettingMapper.getStepListByFlowId(flowId);
    }

    @Override
    public List<Map> getStepFieldsList(Integer flowId, Integer stepId) {
        return flowSettingMapper.getStepFieldsList(flowId,stepId);
    }

    @Override
    public List<Map> getStepPermitListByFlowId(Integer flowId) {
        return flowSettingMapper.getStepPermitListByFlowId(flowId);
    }
}
