package org.chencc.common;

import lombok.Data;

import java.util.List;

/**
 * @Description  用于封装数据库返回至前台的列表数据(集合类型)
 * @author:chencc
 * @Date:2018/7/18
 * @Time:上午10:24
 */

@Data
public class DataListWrapper {

    private long total;
    private int cpage;
    private int rows;
    private String pageId;
    private int limit;  //每页条数
    private String state;
    private String msg;
    private String paterId;
    private boolean success;
    private List<?> data;
}
