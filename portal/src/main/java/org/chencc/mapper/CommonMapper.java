package org.chencc.mapper;

import org.apache.ibatis.annotations.Param;
import org.apache.ibatis.annotations.Update;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/29
 * @Time:下午11:05
 */
public interface CommonMapper {
    @Update("update db_system_pagesize set pagesize=#{pagesize} where uid=#{uid} and id=#{id}")
    void updatePagesize(@Param("uid") Integer uid,@Param("id") String id,@Param("pagesize") Integer pagesize);
}
