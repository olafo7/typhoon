package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description 编号设置实体类
 * @author:chencc
 * @Date:2018/7/24
 * @Time:下午11:39
 */
@Data
@Table(name = "db_assets_number")
public class AssetsNumber implements Serializable {
    @Column(name = "zimu", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 20)
    private String zimu;  //英文字母
    @Column(name = "fuhao", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 6)
    private String fuhao; //符号
    @Column(name = "num", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 20)
    private String num;  //流水号
    @Column(name = "now_num", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 11)
    private String nowNum;  //起始号
    @Column(name = "status", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer status;  //状态
    @Column(name = "zimu_check", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer zimuCheck; //英文字母校验
    @Column(name = "fuhao_check", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer fuhaoCheck; //符号校验
    @Column(name = "num_how", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 225)
    private String numShow;  //编号示例

}
