package org.chencc.common;

import lombok.Data;

import java.io.Serializable;

/**
 * @Description 职位数据包装类
 * @author:chencc
 * @Date:2018/7/28
 * @Time:下午5:49
 */
@Data
public class JobWrapper implements Serializable {
    private String jid;
    private String name;
}
