package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description 群组管理实体类
 * @author:chencc
 * @Date:2018/7/21
 * @Time:下午10:24
 */
@Data
@Table(name = "db_system_group")
public class SystemGroup implements Serializable {
    @Column(name = "id", type = MySqlTypeConstant.INT, isKey = true,isAutoIncrement = true,length = 10)
    private Integer id;
    @Column(name = "uid", type = MySqlTypeConstant.INT,isNull = false,length = 10)
    private Integer uid;
    @Column(name = "group_name", type = MySqlTypeConstant.VARCHAR,isNull = false,length = 100)
    private String groupName;  //群组名称
    @Column(name = "is_system", type = MySqlTypeConstant.INT,isNull = false,length = 1)
    private Integer isSystem;
}
