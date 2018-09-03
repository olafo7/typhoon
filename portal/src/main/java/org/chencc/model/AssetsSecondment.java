package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/26
 * @Time:下午8:28
 */
@Data
@Table(name = "db_assets_secondment")
public class AssetsSecondment implements Serializable {
    @Column(name = "id", type = MySqlTypeConstant.INT, isKey = true,isAutoIncrement = true,length = 11)
    private Integer id;
    @Column(name = "type", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer type;
    @Column(name = "borrow_number", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 50)
    private String borrowNumber;
    @Column(name = "borrow_date", type = MySqlTypeConstant.INT, isNull = false,length = 11)
    private String borrowDate;
    @Column(name = "accept_dpt", type = MySqlTypeConstant.INT, isNull = false,length = 11)
    private String acceptDpt;
    @Column(name = "receiver", type = MySqlTypeConstant.INT, isNull = false,length = 11)
    private String receiver;
    @Column(name = "return_date", type = MySqlTypeConstant.INT, isNull = false,length = 11)
    private String returnDate;
    @Column(name = "status", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer status;
    @Column(name = "remark", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 200)
    private String remark;
    @Column(name = "mid", type = MySqlTypeConstant.INT, isNull = false,length = 11)
    private Integer mid;
}
