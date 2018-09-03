package org.chencc.service.impl;

import com.github.pagehelper.PageHelper;
import com.github.pagehelper.PageInfo;
import org.apache.commons.lang.StringUtils;
import org.chencc.mapper.MainJobMapper;
import org.chencc.model.MainJob;
import org.chencc.service.MainJobService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/28
 * @Time:上午9:56
 */
@Service
@Transactional
public class MainJobServiceImpl implements MainJobService {
    @Autowired
    private MainJobMapper mainJobMapper;

    @Override
    public PageInfo<Map> getJobList(Integer start, Integer limit,Integer deptId,String word,Boolean widthSon) {
        StringBuffer sqlBuffer = new StringBuffer("select id,name,dept_id as deptId,(select name from db_main_struct as a where dept_id=a.id) as dept," +
                "case job_type when 'superAdmin' then '超级管理员' when 'admin' then '管理员' else '普通职位' end as jobType ,about " +
                "from db_main_job where 1=1");

        if(widthSon){ //所有下级
            //通过递归查询获取所有下级部门
            String allChildrenId = mainJobMapper.getAllChildrenId(deptId);
            if(StringUtils.isNotBlank(allChildrenId)){
                sqlBuffer.append(" and dept_id in ("+deptId+","+allChildrenId+")");
            }
        }else{
            sqlBuffer.append(" and dept_id in ("+deptId+")");
        }

        /*if(deptId !=0){
            //通过递归查询获取所有下级部门
            String allChildrenId = mainJobMapper.getAllChildrenId(deptId);
            if(StringUtils.isNotBlank(allChildrenId)){
                sqlBuffer.append(" and dept_id in ("+deptId+","+allChildrenId+")");
            }else{
                sqlBuffer.append(" and dept_id in ("+deptId+")");
            }

        }*/

        if(StringUtils.isNotBlank(word)){
            sqlBuffer.append(" and name like '%"+word+"%'");
        }

        PageHelper.startPage(start,limit);
        List<Map> jobList = mainJobMapper.getJobList(sqlBuffer.toString());
        PageInfo<Map> pageInfo = new PageInfo<Map>(jobList);

        return pageInfo;
    }

    @Override
    public List<Map> getJobListByDeptId(Integer deptId) {
        return mainJobMapper.getJobListByDeptId(deptId);
    }

    @Override
    public List<MainJob> getJobListInDept(Integer deptId) {
        return mainJobMapper.getJobListInDept(deptId);
    }
}
