package org.chencc.mapper;

import org.apache.ibatis.annotations.*;
import org.chencc.model.AssetsSort;

import java.util.List;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/25
 * @Time:下午1:11
 */
public interface AssetsSortSetMapper {
    @Select("select * from db_assets_sort where fid=0")
    AssetsSort getTopLevelSort();

    @Select("select * from db_assets_sort where db_assets_sort.fid=#{fid} order by db_assets_sort.order")
    List<AssetsSort> getAssetsSortListByFid(@Param("fid") Integer fid);

    @Select("select das.id,das.name,das.order,das.fid,das.about,(select a.name from db_assets_sort as a where das.fid=a.id) as fname from db_assets_sort as das where das.id=#{id}")
    AssetsSort getAssetsSortById(@Param("id") Integer id);

    @Delete("delete from db_assets_sort where id=#{id}")
    void deleteRecord(@Param("id") Integer id);

    @Update("update db_assets_sort set name=#{name},fid=#{fid},order=#{order},abour=#{about} where id=#{id}")
    void saveEditRecord(String name, Integer fid, Integer order, String about, Integer id);

    @Insert("insert into db_assets_sort(name,fid,order,about) values(#{name},#{fid},#{order},#{about}) ")
    void saveNewRecord(String name, Integer fid, Integer order, String about);

    @Select("select name as fname from db_assets_sort where id=#{id}")
    String getNameById(@Param("id") Integer id);
}
