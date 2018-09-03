package org.chencc.mapper;

import org.apache.ibatis.annotations.Param;
import org.apache.ibatis.annotations.Select;

import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/30
 * @Time:下午10:05
 */
public interface UserDiskIndexMapper {

    @Select("select fid as id,name as text,'folder' as cls,pid,fid from db_user_disk_main WHERE pid=#{pid} AND uid=#{uid} AND type='d' ORDER BY name ASC")
    List<Map> getUserDirByPid(@Param("uid") Integer uid,@Param("pid") Integer pid);




}
