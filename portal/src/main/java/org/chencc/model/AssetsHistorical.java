package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description 历史记录实体类
 * @author:chencc
 * @Date:2018/7/25
 * @Time:下午9:42
 */
@Data
@Table(name = "db_assets_historical")
public class AssetsHistorical implements Serializable {
    @Column(name = "hid", type = MySqlTypeConstant.INT, isKey = true,isAutoIncrement = true,length = 11)
    private Integer hid;
    @Column(name = "operation", type = MySqlTypeConstant.INT, length = 1)
    private String operation;
    @Column(name = "turnover", type = MySqlTypeConstant.INT, length = 11)
    private String turnover;
    @Column(name = "operator", type = MySqlTypeConstant.INT, length = 11)
    private String operator;
    @Column(name = "cnum", type = MySqlTypeConstant.VARCHAR, length = 100)
    private String cnum;
    @Column(name = "assets_name", type = MySqlTypeConstant.VARCHAR, length = 50)
    private String assetsName;
    @Column(name = "borrow_number", type = MySqlTypeConstant.VARCHAR, length = 50)
    private String borrowNumber;
    @Column(name = "borrow_date", type = MySqlTypeConstant.INT, length = 11)
    private String borrowDate;
    @Column(name = "accept_dpt", type = MySqlTypeConstant.INT, length = 11)
    private String acceptDpt;
    @Column(name = "receiver", type = MySqlTypeConstant.INT, length = 11)
    private String receiver;
    @Column(name = "return_date", type = MySqlTypeConstant.INT, length = 11)
    private String returnDate;
    @Column(name = "status", type = MySqlTypeConstant.INT, length = 1)
    private String status;
    @Column(name = "remark", type = MySqlTypeConstant.VARCHAR, length = 225)
    private String remark;
    @Column(name = "shid", type = MySqlTypeConstant.INT, length = 11)
    private Integer shid;
}
