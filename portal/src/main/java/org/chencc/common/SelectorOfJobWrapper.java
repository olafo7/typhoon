package org.chencc.common;

import lombok.Data;

import java.io.Serializable;
import java.util.List;

/**
 * @Description 职位选择器包装类
 * @author:chencc
 * @Date:2018/7/28
 * @Time:下午5:46
 */
@Data
public class SelectorOfJobWrapper implements Serializable {
    private String id;
    private String deptId;
    private String iconCls;
    private boolean leaf;
    private boolean disabled;
    private boolean expanded;
    private String dept;
    private List<JobWrapper> jobs;
    private List<SubSelectorOfJobWrapper> children;


    /*"id": "CNOA_main_struct_list_tree_node_1",
    "deptId": "1",
    "iconCls": "icon-tree-root-cnoa",
    "leaf": false,
    "disabled": false,
    "expanded": false,
    "dept": "协众OA协同管理系统-试用",
    jobs:
    children:*/
}
