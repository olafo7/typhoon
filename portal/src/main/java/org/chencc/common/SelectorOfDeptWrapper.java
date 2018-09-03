package org.chencc.common;

import lombok.Data;

import java.io.Serializable;
import java.util.List;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/28
 * @Time:下午10:40
 */
@Data
public class SelectorOfDeptWrapper implements Serializable {
    private String id;
    private String selfid;
    private String deptId;
    private Integer permit;
    private String text;
    private String iconCls;
    private String cls;
    private String href;
    private boolean leaf;
    private boolean disabled;
    private boolean ds;
    private boolean expanded;
    private List<SelectorOfDeptWrapper> children;


    /*"id": "CNOA_main_struct_list_tree_node_1",
    "selfid": "1",
    "deptId": "1",
    "permit": 1,
    "text": "协众OA协同管理系统-试用",
    "iconCls": "icon-tree-root-cnoa",
    "cls": "package",
    "href": "javascript:void(0);",
    "leaf": false,
    "children"
    "disabled": false,
    "ds": false,
    "expanded": false*/
}
