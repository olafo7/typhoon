package org.chencc.service;

import com.github.pagehelper.PageInfo;

import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/27
 * @Time:下午11:12
 */
public interface MainUserService {
    PageInfo<Map> getUserListByType(String type, Integer start, Integer limit, Integer deptId,Boolean widthSon,
                                    String trueName, String username, String mobile, String isSystemUser, String workStatusType,
                                    String stationId, String atjod);

    List<Map> getUserListByDeptId(Integer deptId);

    List<Map> getInfoByUsername(String username);
}
