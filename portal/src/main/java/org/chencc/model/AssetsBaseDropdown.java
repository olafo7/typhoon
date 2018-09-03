package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description 基础设置
 * @author:chencc
 * @Date:2018/7/23
 * @Time:下午6:19
 */
@Data
@Table(name = "db_assets_base_dropdown")
public class AssetsBaseDropdown implements Serializable {
    @Column(name = "id", type = MySqlTypeConstant.INT, isKey = true,isAutoIncrement = true,length = 4)
    private Integer id;
    @Column(name = "type", type = MySqlTypeConstant.INT, isNull = false,length = 4)
    private Integer type;  //基础设置类型（计量单位/资产来源/减少方式/存放地点/制造厂商/供应商/残值率）
    @Column(name = "value", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 50)
    private String value;  //值
}
