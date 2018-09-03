package org.chencc.mapper;

import org.apache.ibatis.annotations.Param;
import org.apache.ibatis.annotations.Result;
import org.apache.ibatis.annotations.Results;
import org.apache.ibatis.annotations.Select;
import org.chencc.model.MainSignature;

import java.util.List;
import java.util.Map;

/**
 * @Description 继承通用Mapper获取CURD方法
 * @author:chencc
 * @Date:2018/7/31
 * @Time:下午6:47
 */
public interface MainSignatureMapper{
    @Select("${sqlBuffer}")
    List<Map> getSignatureList(@Param("sqlBuffer") String sqlBuffer);
}
