package org.chencc.common;

import lombok.Data;

import java.io.Serializable;
import java.util.List;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/25
 * @Time:下午6:01
 */
@Data
public class AssetsSortWrapperForChoose implements Serializable {
    private String id;
    private String text;
    private String fid;
    private String order;
    private String about;
    private boolean leaf;
    private boolean checked;
    private String iconCls;
    private List<AssetsSortWrapperForChoose> children;
}
