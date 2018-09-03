package org.chencc.service.impl;

import com.github.pagehelper.PageHelper;
import com.github.pagehelper.PageInfo;
import org.apache.commons.lang.StringUtils;
import org.chencc.mapper.MainJobMapper;
import org.chencc.mapper.MainUserMapper;
import org.chencc.service.MainUserService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/27
 * @Time:下午11:12
 */
@Service
@Transactional
public class MainUserServiceImpl implements MainUserService {
    @Autowired
    private MainUserMapper mainUserMapper;
    @Autowired
    private MainJobMapper mainJobMapper;

    @Override
    public PageInfo<Map> getUserListByType(String type, Integer start, Integer limit, Integer deptId,Boolean widthSon,String trueName,
                                           String username, String mobile, String isSystemUser, String workStatusType,
                                           String stationId, String atjod) {

        StringBuffer sqlBuffer = new StringBuffer("select mu.uid,mu.true_name as trueName,case mu.work_status_type when 1 then '在职' else '离职' end as workStatusType," +
                "case mu.sex when 1 then '男' else '女' end as sex,mu.bianhao,(select name from db_main_struct a where mu.dept_id = a.id) as deptId," +
                "(select name from db_main_job b where mu.job_id = b.id) as jobId,mu.part_time as partTime,mu.part_time_job as partTimeJob,mu.username,mu.station_id,mu.station_id as partTimeStation," +
                "(select name from db_main_station c where mu.station_id = c.sid) as station,mu.qq from db_main_user as mu where mu.work_status_type = 1");

        if(widthSon){ //所有下级
            //通过递归查询获取所有下级部门
            String allChildrenId = mainJobMapper.getAllChildrenId(deptId);
            if(StringUtils.isNotBlank(allChildrenId)){
                sqlBuffer.append(" and mu.dept_id in ("+deptId+","+allChildrenId+")");
            }
        }else{
            sqlBuffer.append(" and mu.dept_id in ("+deptId+")");
        }

        /*if(deptId !=0){ //加载指定部门人员
            //通过递归查询获取所有下级部门
            String allChildrenId = mainJobMapper.getAllChildrenId(deptId);
            if(StringUtils.isNotBlank(allChildrenId)){
                sqlBuffer.append(" and mu.dept_id in ("+deptId+","+allChildrenId+")");
            }else{
                sqlBuffer.append(" and mu.dept_id in ("+deptId+")");
            }
        }*/

        if(StringUtils.isNotBlank(trueName)){
            sqlBuffer.append(" and mu.true_name like '%"+trueName+"%'");
        }

        if(StringUtils.isNotBlank(username)){
            sqlBuffer.append(" and mu.username like '%"+username+"%'");
        }

        if(StringUtils.isNotBlank(mobile)){
            sqlBuffer.append(" and mu.mobile like '%"+mobile+"%'");
        }

        if(StringUtils.isNotBlank(isSystemUser)){
            sqlBuffer.append(" and mu.is_system_user = '"+isSystemUser+"'");
        }

        if(StringUtils.isNotBlank(isSystemUser)){
            sqlBuffer.append(" and mu.is_system_user = '"+isSystemUser+"'");
        }

        if(StringUtils.isNotBlank(workStatusType)){
            sqlBuffer.append(" and mu.work_status_type = '"+workStatusType+"'");
        }

        if(StringUtils.isNotBlank(stationId)){
            sqlBuffer.append(" and mu.station_id = "+stationId+"");
        }

        if(StringUtils.isNotBlank(atjod)){
            sqlBuffer.append(" and mu.job_id = "+atjod+"");
        }

        sqlBuffer.append(" order by mu.order");

        PageHelper.startPage(start,limit);
        List<Map> userList = mainUserMapper.getUserList(sqlBuffer.toString());
        PageInfo<Map> pageInfo = new PageInfo<Map>(userList);

        return pageInfo;
    }

    @Override
    public List<Map> getUserListByDeptId(Integer deptId) {
        return mainUserMapper.getUserListByDeptId(deptId);
    }

    @Override
    public List<Map> getInfoByUsername(String username){
        return mainUserMapper.getInfoByUsername(username);
    }
}
