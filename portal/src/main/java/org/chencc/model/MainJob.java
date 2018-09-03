package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description 职位管理实体类
 * @author:chencc
 * @Date:2018/7/28
 * @Time:上午9:21
 */
@Data
@Table(name = "db_main_job")
public class MainJob implements Serializable {
    @Column(name = "id", type = MySqlTypeConstant.INT, isKey = true,isAutoIncrement = true,length = 10)
    private Integer id;
    @Column(name = "name", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 40)
    private String name;
    @Column(name = "dept_id", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private String deptId;
    @Column(name = "job_type", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 10)
    private String jobType;
    @Column(name = "about", type = MySqlTypeConstant.TEXT, isNull = false)
    private String about;
}
