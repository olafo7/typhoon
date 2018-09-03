package org.chencc.mapper;

import org.apache.ibatis.annotations.*;
import org.chencc.model.MainStation;

import java.util.List;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/18
 * @Time:上午10:21
 */
public interface MainStationMapper {
    @Select("select * from db_main_station")
    List<MainStation> getStationList();

    @Insert("insert into db_main_station(name,about) values(#{name},#{about})")
    void saveNewRecord(@Param("name") String name,@Param("about") String about);

    @Update("update db_main_station set name=#{name},about=#{about} where sid=#{sid}")
    void saveEditRecord(@Param("sid") int sid,@Param("name") String name,@Param("about") String about);

    @Delete("delete from db_main_station where sid=#{sid}")
    void deleteRecord(@Param("sid") int sid);

    @Update("update db_main_station set sort=#{value} where sid=#{sid}")
    void updateSort(@Param("sid") int sid,@Param("value") int value);

    @Select("select * from db_main_station where sid=#{sid}")
    @Results({
            @Result(property = "sid", column = "sid"),
            @Result(property = "name", column = "name"),
            @Result(property = "about", column = "about"),
            @Result(property = "sort", column = "sort")
    })
    MainStation loadStationById(@Param("sid") int sid);
}
