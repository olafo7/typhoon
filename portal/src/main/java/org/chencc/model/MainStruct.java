package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description 组织结构管理实体类
 * @author:chencc
 * @Date:2018/7/21
 * @Time:下午3:25
 */
@Data
//@Table(name = "db_main_struct")
public class MainStruct implements Serializable {
    @Column(name = "id", type = MySqlTypeConstant.INT, isKey = true,isAutoIncrement = true,length = 10)
    private Integer id;   //数据库标识
    @Column(name = "name", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 40)
    private String name;  //组织结构名称
    @Column(name = "order", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private Integer order;  //排序
    @Column(name = "fid", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private Integer fid;  //父类id
    @Column(name = "path", type = MySqlTypeConstant.TEXT, isNull = false)
    private String path;  //部门位置
    @Column(name = "about", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 225)
    private String about;  //部门描述
}
