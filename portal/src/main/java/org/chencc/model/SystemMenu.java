package org.chencc.model;

import lombok.Data;

import java.io.Serializable;

/**
 * @Description 系统菜单实体类
 * @author:chencc
 * @Date:2018/7/23
 * @Time:下午6:58
 */
@Data
public class SystemMenu implements Serializable {

    private Integer id;
    private Integer pid;
    private String menuId;
    private String text;
    private String language;
    private String newText;
    private String iconCls;
    private Integer expanded;
    private Integer singleClickExpand;
    private String clickEvent;
    private String cls;
    private Integer leaf;
    private Integer forceRefresh;
    private String href;
    private String autoLoadUrl;
    private String code;
    private Integer isClass;
    private Integer show;
    private Integer order;
    private Integer system;
    private Integer systemShow;
    private String about;
    private Integer permitId;
    private String blong;
}
