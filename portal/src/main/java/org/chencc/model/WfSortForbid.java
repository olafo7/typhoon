package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description 流程分类操作禁止发起表实体类
 * @author:chencc
 * @Date:2018/7/29
 * @Time:下午11:00
 */
@Data
@Table(name = "db_wf_sort_forbid")
public class WfSortForbid implements Serializable {
    @Column(name = "sort_id", type = MySqlTypeConstant.INT, length = 11)
    private Integer sortId;
    @Column(name = "permit_id", type = MySqlTypeConstant.INT, length = 11)
    private Integer permitId;
    @Column(name = "sort_id", type = MySqlTypeConstant.VARCHAR, length = 5)
    private String type;  //p d s
}
