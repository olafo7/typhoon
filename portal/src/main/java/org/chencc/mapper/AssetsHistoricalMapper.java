package org.chencc.mapper;

import org.apache.ibatis.annotations.*;
import org.chencc.model.AssetsHistorical;

import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/25
 * @Time:下午10:02
 */
public interface AssetsHistoricalMapper {

    @Select("${sqlBuffer}")
    @Results({
            @Result(property = "hid", column = "hid"),
            @Result(property = "operation", column = "operation"),
            @Result(property = "turnover", column = "turnover"),
            @Result(property = "operator", column = "operator"),
            @Result(property = "cnum", column = "cnum"),
            @Result(property = "assetsName", column = "assets_name"),
            @Result(property = "borrowNumber", column = "borrow_number"),
            @Result(property = "borrowDate", column = "borrow_date"),
            @Result(property = "acceptDpt", column = "accept_dpt"),
            @Result(property = "receiver", column = "receiver"),
            @Result(property = "returnDate", column = "return_date"),
            @Result(property = "status", column = "status"),
            @Result(property = "remark", column = "remark"),
            @Result(property = "shid", column = "shid")
    })
    List<AssetsHistorical> getHistoricalList(@Param("sqlBuffer") String sqlBuffer);

    @Select("select id,cnum,assets_name as assetsName from db_assets_manage where type = '1' ORDER BY id DESC")
    List<Map> getCnumList();
}
