package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description 用户登录记录实体类
 * @author:chencc
 * @Date:2018/7/27
 * @Time:下午1:59
 */
@Data
//@Table(name = "db_system_online")
public class SystemOnline implements Serializable {
    @Column(name = "uid", type = MySqlTypeConstant.INT,isNull = false,length = 10)
    private Integer uid;
    @Column(name = "did", type = MySqlTypeConstant.INT,isNull = false,length = 10)
    private Integer did;
    @Column(name = "update", type = MySqlTypeConstant.INT,isNull = false,length = 10)
    private String update;
    @Column(name = "ip", type = MySqlTypeConstant.VARCHAR,isNull = false,length = 16)
    private String ip;
    @Column(name = "status", type = MySqlTypeConstant.INT,isNull = false,length = 1)
    private Integer status;
    @Column(name = "sessionid", type = MySqlTypeConstant.VARCHAR,isKey = true,isNull = false,length = 100)
    private String sessionid;
    @Column(name = "device", type = MySqlTypeConstant.INT,isNull = false,length = 1)
    private Integer device;
}
