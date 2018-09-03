package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/23
 * @Time:下午8:36
 */
@Data
@Table(name = "db_system_outlink")
public class SystemOutlink implements Serializable {
    @Column(name = "id", type = MySqlTypeConstant.INT, isKey = true,isAutoIncrement = true,length = 10)
    private Integer id;
    @Column(name = "name", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 200)
    private String name;
    @Column(name = "link", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 250)
    private String link;
    @Column(name = "order", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private Integer order;
    @Column(name = "post_time", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private String postTime;
}
