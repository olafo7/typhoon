package org.chencc.common;

import lombok.Data;

import java.io.Serializable;
import java.util.List;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/28
 * @Time:下午11:17
 */
@Data
public class SelectorOfUserWrapper implements Serializable {
    private String id;
    private String deptId;
    private String iconCls;
    private boolean leaf;
    private boolean disabled;
    private boolean expanded;
    private boolean singleClickExpand;
    private String dept;
    private List<UserWrapper> users;
    private List<SelectorOfUserWrapper> children;
}
