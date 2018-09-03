package org.chencc.mapper;

import org.apache.ibatis.annotations.*;
import org.chencc.model.MainJob;

import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/28
 * @Time:上午9:55
 */
public interface MainJobMapper {
    @Select("${sqlBuffer}")
    List<Map> getJobList(@Param("sqlBuffer") String sqlBuffer);

    @Select("select group_concat(id) as cid from db_main_struct where FIND_IN_SET(fid,getChildrenOrg(${deptId}));")
    String getAllChildrenId(@Param("deptId")Integer deptId);

    @Select("select id as jid,name from db_main_job where dept_id=#{deptId}")
    List<Map> getJobListByDeptId(@Param("deptId") Integer deptId);


    @Select("select * from db_main_job where dept_id=#{deptId}")
    @Results({
            @Result(property = "id", column = "id"),
            @Result(property = "name", column = "name"),
            @Result(property = "deptId", column = "dept_id"),
            @Result(property = "jobType", column = "job_type"),
            @Result(property = "about", column = "about")
    })
    List<MainJob> getJobListInDept(@Param("deptId") Integer deptId);
}
