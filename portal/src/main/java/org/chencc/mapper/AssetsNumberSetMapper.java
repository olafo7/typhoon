package org.chencc.mapper;

import org.apache.ibatis.annotations.*;
import org.chencc.model.AssetsNumber;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/24
 * @Time:下午11:26
 */
public interface AssetsNumberSetMapper {
    @Select("select * from db_assets_number")
    @Results({
            @Result(property = "zimu", column = "zimu"),
            @Result(property = "fuhao", column = "fuhao"),
            @Result(property = "num", column = "num"),
            @Result(property = "nowNum", column = "now_num"),
            @Result(property = "status", column = "status"),
            @Result(property = "zimuCheck", column = "zimu_check"),
            @Result(property = "fuhaoCheck", column = "fuhao_check")
    })
    AssetsNumber getSortList();

    @Update("update db_assets_number(status,zimu,fuhao,num,zimu_check,fuhao_check,now_num,num_show) values(#{status},#{zimu},#{fuhao},#{num},#{zimuCheck},#{fuhaoCheck},#{nowNum},#{nuwShow})")
    void saveEditRecord(@Param("status") Integer status,@Param("zimu") String zimu,@Param("fuhao")String fuhao,
                        @Param("num") String num,@Param("zimuCheck") Integer zimuCheck,@Param("fuhaoCheck") Integer fuhaoCheck,
                        @Param("nowNum") String nowNum,@Param("numShow") String numShow);
}
