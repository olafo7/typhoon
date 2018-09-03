package org.chencc.service;

import com.github.pagehelper.PageInfo;
import org.chencc.model.MainJob;

import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/28
 * @Time:上午9:56
 */
public interface MainJobService {
    PageInfo<Map> getJobList(Integer start, Integer limit, Integer deptId,String word,Boolean widthSon);

    List<Map> getJobListByDeptId(Integer deptId);

    List<MainJob> getJobListInDept(Integer deptId);
}
