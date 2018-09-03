package org.chencc.mapper;

import org.apache.ibatis.annotations.Result;
import org.apache.ibatis.annotations.Results;
import org.apache.ibatis.annotations.Select;
import org.chencc.model.MainMessage;

import java.util.List;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/22
 * @Time:上午8:54
 */
public interface MainMessageMapper {

    @Select("select id,title,content,FROM_UNIXTIME(`start`,'%Y-%m-%d %h:%i') as start,FROM_UNIXTIME(`end`,'%Y-%m-%d %h:%i') as end," +
            "post_time,from_uid,state,in_use,attach from db_main_message")
    @Results({
            @Result(property = "id", column = "id"),
            @Result(property = "title", column = "title"),
            @Result(property = "content", column = "content"),
            @Result(property = "start", column = "start"),
            @Result(property = "end", column = "end"),
            @Result(property = "postTime", column = "post_time"),
            @Result(property = "fromUid", column = "from_uid"),
            @Result(property = "state", column = "state"),
            @Result(property = "inUse", column = "in_use"),
            @Result(property = "attach", column = "attach")
    })
    List<MainMessage> getMessageList();

}
