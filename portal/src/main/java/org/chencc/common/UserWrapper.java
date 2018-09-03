package org.chencc.common;

import lombok.Data;

import java.io.Serializable;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/28
 * @Time:下午11:18
 */
@Data
public class UserWrapper implements Serializable {
    private String uid;
    private String name;
    private String email;
}
