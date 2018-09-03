package org.chencc.mapper;

import org.apache.ibatis.annotations.*;
import org.chencc.model.AssetsBaseDropdown;

import java.util.List;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/23
 * @Time:下午6:28
 */
public interface AssetsBaseSetMapper {
    @Select("select * from db_assets_base_dropdown where type=#{type}")
    List<AssetsBaseDropdown> getDropdownByType(@Param("type") Integer type);

    @Update("update db_assets_base_dropdown set value=#{value } where type=#{type} and id=#{id}")
    void saveEditRecord(@Param("type") Integer type, @Param("value") String value, @Param("id") Integer id);

    @Insert("insert into db_assets_base_dropdown(type,value) values(#{type},#{value})")
    void saveNewRecord(@Param("type") Integer type, @Param("value") String value);

    @Delete("delete from db_assets_base_dropdown where id=#{id}")
    void deleteRecord(@Param("id") Integer id);
}
