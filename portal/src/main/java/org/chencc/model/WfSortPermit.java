package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description 流程分类操作许可表实体类（设置分类下的流程发起、查阅及管理权限），禁止发起
 * 权限表存储在db_wf_sort_forbid表中
 * @author:chencc
 * @Date:2018/7/29
 * @Time:下午10:54
 */
@Data
//@Table(name = "db_wf_sort_permit")
public class WfSortPermit implements Serializable {
    @Column(name = "sort_id", type = MySqlTypeConstant.INT, length = 10)
    private Integer sortId;
    @Column(name = "permit_id", type = MySqlTypeConstant.INT, length = 10)
    private Integer permitId;
    @Column(name = "type", type = MySqlTypeConstant.VARCHAR, length = 5)
    private String type;  //d s p n
    @Column(name = "from", type = MySqlTypeConstant.VARCHAR, length = 5)
    private String from; //权限类型：c(查阅)/g(管理)/f(发起)
}
