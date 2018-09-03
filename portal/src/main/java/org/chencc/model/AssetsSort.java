package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.beans.IntrospectionException;
import java.io.Serializable;

/**
 * @Description 分类设置实体类
 * @author:chencc
 * @Date:2018/7/25
 * @Time:上午8:31
 */
@Data
@Table(name = "db_assets_sort")
public class AssetsSort implements Serializable {
    @Column(name = "id", type = MySqlTypeConstant.INT, isKey = true,isAutoIncrement = true,length = 11)
    private Integer id;
    @Column(name = "name", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 40)
    private String name;  //分类名称
    @Column(name = "order", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private Integer order;  //排序
    @Column(name = "fid", type = MySqlTypeConstant.INT, isNull = false,length = 11)
    private Integer fid;  //上级分类
    @Column(name = "about", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 225)
    private String about;  //说明
    private String fname;  //上级分类名称
}
