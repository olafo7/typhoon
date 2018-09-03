package org.chencc.model;
import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description 岗位管理实体类
 * @author:chencc
 * @Date:2018/7/17
 * @Time:下午10:20
 */

@Data
//@Table(name = "db_main_station")
public class MainStation implements Serializable {
    @Column(name = "sid", type = MySqlTypeConstant.INT, isKey = true,isAutoIncrement = true,length = 10)
    private Integer sid;  //数据库标识
    @Column(name = "name", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 100)
    private String name;  //岗位名称
    @Column(name = "about", type = MySqlTypeConstant.TEXT,isNull = false)
    private String about;  //备注
    @Column(name = "sort", type = MySqlTypeConstant.INT, isNull = false, length = 11)
    private Integer sort;  //排序
}
