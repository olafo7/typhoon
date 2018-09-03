package org.chencc.common;

import lombok.Data;

import java.io.Serializable;
import java.util.List;

/**
 * @Description 职位选择器子集包装类
 * @author:chencc
 * @Date:2018/7/28
 * @Time:下午5:51
 */
@Data
public class SubSelectorOfJobWrapper implements Serializable {
    private String id;
    private String deptId;
    private String iconCls;
    private boolean leaf;
    private boolean disabled;
    private String dept;
    private boolean singleClickExpand;
    private List<JobWrapper> jobs;
    private List<SubSelectorOfJobWrapper> children;

    /*"id": "CNOA_main_struct_list_tree_node_2",
    "deptId": "2",
    "iconCls": "icon-style-tree",
    "leaf": false,
    "disabled": false,
    "dept": "综合部",
    "children": [],
    "jobs": [],
    "singleClickExpand": false*/
}
