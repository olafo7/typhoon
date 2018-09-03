package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description 流程分类实体类
 * @author:chencc
 * @Date:2018/7/29
 * @Time:下午9:19
 */
@Data
@Table(name = "db_wf_sort")
public class WfSort implements Serializable {
    @Column(name = "sort_id", type = MySqlTypeConstant.INT, isKey = true,isAutoIncrement = true,length = 10)
    private Integer sortId;
    @Column(name = "name", type = MySqlTypeConstant.VARCHAR, length = 50)
    private String name;
    @Column(name = "note", type = MySqlTypeConstant.VARCHAR, length = 200)
    private String note;
    @Column(name = "system", type = MySqlTypeConstant.INT, length = 1)
    private Integer system;
    @Column(name = "order", type = MySqlTypeConstant.INT, length = 10)
    private Integer order;
}
