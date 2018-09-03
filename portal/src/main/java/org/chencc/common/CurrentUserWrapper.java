package org.chencc.common;

import lombok.Data;

import java.io.Serializable;

/**
 * @Description 登录用户信息封装类
 * @author:chencc
 * @Date:2018/7/29
 * @Time:上午10:52
 */
@Data
public class CurrentUserWrapper implements Serializable {
    private Integer uid;
    private String username;
    private String trueName;
    private String salt;
    private String password;
    private String email;
    private Integer jobId;
    private String job;
    private String jobType;
    private Integer deptId;
    private String dept;
    private Integer stationId;
    private String station;
    private String theme;
    private Integer wfNewWindow;
    private Integer menuCollapsed;
}
