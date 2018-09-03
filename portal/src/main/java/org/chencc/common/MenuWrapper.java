package org.chencc.common;

import lombok.Data;

import java.io.Serializable;
import java.util.List;

/**
 * @Description 用于系统左侧菜单数据封装类
 * @author:chencc
 * @Date:2018/7/24
 * @Time:下午5:30
 */
@Data
public class MenuWrapper implements Serializable {
    private String id;
    private String text;
    private String newtext;
    private String cls;
    private String iconCls;
    private boolean expanded;
    private String singleClickExpand;
    private String isIsClass;
    private String order;
    private boolean isIsCustom;
    private List<SubMenuWrapper> children;


    //"id": "CNOA_MENU_SYSTEM_NOTICE",
    //"text": "待办事务",
    //"newtext": "",
    //"cls": "package",
    //"iconCls": "icon-system-notice",
    //"expanded": true,
    //"singleClickExpand": "1",
    //"isClass": "0",
    //"order": "1",
    //"isCustom": false,




}
