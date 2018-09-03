package org.chencc.common;

import lombok.Data;

import java.io.Serializable;
import java.util.List;

/**
 * @Description 用于系统核心设置中的菜单数据封装类
 * @author:chencc
 * @Date:2018/7/23
 * @Time:下午11:30
 */
@Data
public class MenuWrapperForSetting implements Serializable {
    private String id;
    private String pid;
    private String menuId;
    private String language;
    private String text;
    private String newText;
    private String cls;
    private String iconCls;
    private boolean leaf;
    private String about;
    private String system;
    private String blong;
    private boolean isIsCustom;
    private String uiProvider;
    private boolean checked;
    private List<MenuWrapperForSetting> children;
}
