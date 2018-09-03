package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description 列表界面每页条数实体类
 * @author:chencc
 * @Date:2018/7/26
 * @Time:下午11:44
 */
@Data
//@Table(name = "db_system_pagesize")
public class SystemPagesize implements Serializable {
    @Column(name = "uid", type = MySqlTypeConstant.INT, length = 10)
    private Integer uid;
    @Column(name = "id", type = MySqlTypeConstant.VARCHAR, length = 100)
    private String id;
    @Column(name = "pagesize", type = MySqlTypeConstant.INT, length = 4)
    private Integer pagesize;
}
