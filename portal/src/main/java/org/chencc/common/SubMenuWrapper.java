package org.chencc.common;

import lombok.Data;

import java.io.Serializable;
import java.util.List;

/**
 * @Description 用于系统左侧子菜单数据封装类
 * @author:chencc
 * @Date:2018/7/24
 * @Time:下午5:39
 */
@Data
public class SubMenuWrapper implements Serializable {
    private String id;
    private String text;
    private String newtext;
    private String cls;
    private String iconCls;
    private boolean leaf;
    private boolean expanded;
    private boolean singleClickExpand;
    private boolean forceRefresh;
    private boolean isIsClass;
    private String code;
    private String autoLoadUrl;
    private String href;
    private String system;
    private String clickEvent;
    private String order;
    private boolean isIsCustom;
    private boolean isIsNewLabel;
    private List<SubMenuWrapper> children;


    /*"id": "CNOA_MENU_SYSTEM_NEED_TODO",
    "text": "待办事务",
    "newtext": "",
    "cls": "xxxxxxxxxxxxx",
    "iconCls": "icon-system-notice",
    "leaf": true,
    "expanded": true,
    "singleClickExpand": false,
    "forceRefresh": true,
    "isClass": true,
    "code": "notice_notice_todo",
    "autoLoadUrl": "index.php?app=notice&func=notice&action=todo",
    "href": "javascript:void(0);",
    "system": "0",
    "clickEvent": "",
    "order": "2",
    "isCustom": false,
    "isNewLabel": false*/
}
