package org.chencc.common;

import lombok.Data;

/**
 * @Description 用于封装数据库返回至前台的列表数据(object类型)
 * @author:chencc
 * @Date:2018/7/20
 * @Time:下午11:43
 */

@Data
public class DataWrapper {
    private long total;
    private int cpage;
    private int rows;
    private String state;
    private String msg;
    private String paterId;
    private boolean success;
    private Object data;
}
