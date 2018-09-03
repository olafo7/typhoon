package org.chencc.common;

import lombok.Data;

import java.io.Serializable;
import java.util.List;

/**
 * @Description 用于资产分类设置树数据的包装
 * @author:chencc
 * @Date:2018/7/25
 * @Time:下午1:31
 */
@Data
public class AssetsSortWrapper implements Serializable {
    private String id;
    private String text;
    private String fid;
    private String order;
    private String about;
    private boolean leaf;
    private String iconCls;
    private List<AssetsSortWrapper> children;
}
