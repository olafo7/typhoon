package org.chencc.mapper;

import org.apache.ibatis.annotations.Param;
import org.apache.ibatis.annotations.Select;

import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/27
 * @Time:下午11:11
 */
public interface MainUserMapper {
    @Select("${sqlBuffer}")
    List<Map> getUserList(@Param("sqlBuffer") String sqlBuffer);

    @Select("select mu.uid,mu.true_name as name,mu.email from db_main_user as mu where mu.dept_id =#{deptId} and mu.work_status_type = 1 order by mu.order")
    List<Map> getUserListByDeptId(@Param("deptId") Integer deptId);

    @Select("select mu.uid,mu.username,mu.true_name,mu.salt,mu.password,mu.email,mu.job_id," +
            "(select a.name from db_main_job as a where mu.job_id = a.id) as job,mu.dept_id," +
            "(select b.name from db_main_struct as b where mu.dept_id = b.id) as dept,mu.station_id," +
            "(select c.name from db_main_station as c where mu.station_id=c.sid) as station," +
            "(select d.job_type from db_main_job as d where mu.job_id = d.id ) as job_type,mu.theme,mu.wf_new_window,mu.menu_collapsed " +
            "from db_main_user as mu where mu.username=#{username} and mu.work_status_type = 1")
    List<Map> getInfoByUsername(@Param("username") String username);
}
