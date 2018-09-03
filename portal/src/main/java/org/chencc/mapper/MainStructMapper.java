package org.chencc.mapper;

import org.apache.ibatis.annotations.*;
import org.chencc.model.MainStruct;

import java.util.List;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/21
 * @Time:下午3:56
 */
public interface MainStructMapper {
    @Select("select * from db_main_struct where id=#{id}")
    @Results({
            @Result(property = "id", column = "id"),
            @Result(property = "name", column = "name"),
            @Result(property = "order", column = "order"),
            @Result(property = "fid", column = "fid"),
            @Result(property = "path", column = "path"),
            @Result(property = "about", column = "about")
    })
    MainStruct loadStructById(@Param("id") Integer id);

    @Delete("delete from db_main_struct where id=#{id}")
    void deleteRecord(@Param("id") Integer id);

    @Insert("insert into db_main_struct(name,order,fid,path,about) values(#{name},#{order},#{fid},#{path},#{about})")
    void saveNewRecord(@Param("name") String name,@Param("order") Integer order,@Param("fid") Integer fid,@Param("path") String path,@Param("about") String about);

    @Update("update db_main_struct set name=#{name},order=#{order},fid=#{fid},path=#{path},about=#{about} where id=#{id}")
    void saveEditRecord(@Param("id") Integer id,@Param("name") String name,@Param("order") Integer order,@Param("fid") Integer fid,@Param("path") String path,@Param("about") String about);

    @Select("select * from db_main_struct where fid=0")
    @Results({
            @Result(property = "id", column = "id"),
            @Result(property = "name", column = "name"),
            @Result(property = "order", column = "order"),
            @Result(property = "fid", column = "fid"),
            @Result(property = "path", column = "path"),
            @Result(property = "about", column = "about")
    })
    MainStruct getTopStruct();

    @Select("select ms.* from db_main_struct as ms where ms.fid=#{deptId} order by ms.order")
    @Results({
            @Result(property = "id", column = "id"),
            @Result(property = "name", column = "name"),
            @Result(property = "order", column = "order"),
            @Result(property = "fid", column = "fid"),
            @Result(property = "path", column = "path"),
            @Result(property = "about", column = "about")
    })
    List<MainStruct> getChildrenStructList(@Param("deptId") Integer deptId);
}
