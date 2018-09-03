package org.chencc.service;

import com.github.pagehelper.PageInfo;
import org.chencc.model.WfSetFlow;
import org.chencc.model.WfSort;
import org.omg.CORBA.INTERNAL;

import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/29
 * @Time:下午9:22
 */
public interface FlowSettingService {
    List<Map> getSortList(String sname);

    WfSort loadWfSortById(Integer sortId);

    void updateSortOrder(Integer sortId,Integer order);

    void deleteSortList(String ids);

    boolean judgeExistFlowByName(String name,Integer flowId);

    void saveNewRecord(Integer flowId, Integer flowType, Integer tplSort,
                       String name, Integer sortId, String nameRule, Integer nameRuleAllowEdit,
                       Integer nameDisallowBlank, Integer noticeCallback, Integer noticeCancel,
                       Integer noticeAtGoBack, Integer noticeAtReject, Integer noticeAtInterrupt,
                       Integer noticeAtFinish, String about,Integer uid,Integer postTime,Integer status);

    void saveEditRecord(Integer flowId, Integer flowType, Integer tplSort,
                       String name, Integer sortId, String nameRule, Integer nameRuleAllowEdit,
                       Integer nameDisallowBlank, Integer noticeCallback, Integer noticeCancel,
                       Integer noticeAtGoBack, Integer noticeAtReject, Integer noticeAtInterrupt,
                       Integer noticeAtFinish, String about);

    PageInfo<Map> getFlowList(Integer start,Integer limit,Integer sortId,String sname);

    WfSetFlow getFlowInfoById(Integer flowId);

    int countNodeNum(Integer flowId);

    void updateFlowStatus(Integer flowId,Integer status);

    void updateUseFavStatus(Integer flowId,Integer status);

    String getFormHtmlByFlowId(Integer flowId);

    String getFlowXmlByFlowId(Integer flowId);

    List<Map> getFieldListByFlowId(Integer flowId);

    List<Map> getFieldDetailListByFid(String fid);

    List<Map> getStepListByFlowId(Integer flowId);

    List<Map> getStepFieldsList(Integer flowId,Integer stepId);

    List<Map> getStepPermitListByFlowId(Integer flowId);
}
