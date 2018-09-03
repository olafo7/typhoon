package org.chencc.mapper;

import org.apache.ibatis.annotations.*;
import org.chencc.model.SystemMenu;
import org.chencc.model.SystemPagesize;

import java.util.List;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/24
 * @Time:下午10:17
 */
public interface WelcomeMapper {
    @Select("${sqlBuffer}")
    @Results({
            @Result(property = "id", column = "id"),
            @Result(property = "pid", column = "pid"),
            @Result(property = "menuId", column = "menu_id"),
            @Result(property = "text", column = "text"),
            @Result(property = "language", column = "language"),
            @Result(property = "newText", column = "new_text"),
            @Result(property = "iconCls", column = "icon_cls"),
            @Result(property = "expanded", column = "expanded"),
            @Result(property = "singleClickExpand", column = "single_click_expand"),
            @Result(property = "clickEvent", column = "click_event"),
            @Result(property = "cls", column = "cls"),
            @Result(property = "leaf", column = "leaf"),
            @Result(property = "forceRefresh", column = "force_refresh"),
            @Result(property = "href", column = "href"),
            @Result(property = "autoLoadUrl", column = "auto_load_url"),
            @Result(property = "code", column = "code"),
            @Result(property = "isClass", column = "is_class"),
            @Result(property = "show", column = "show"),
            @Result(property = "order", column = "order"),
            @Result(property = "system", column = "system"),
            @Result(property = "systemShow", column = "system_show"),
            @Result(property = "about", column = "about"),
            @Result(property = "permitId", column = "permit_id"),
            @Result(property = "blong", column = "blong")
    })
    List<SystemMenu> getSystemMenuList(@Param("sqlBuffer") String sqlBuffer);

    @Select("select * from db_system_pagesize")
    List<SystemPagesize> getPagesize();
}
