package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/31
 * @Time:下午6:29
 */
@Data
//@Table(name = "db_main_signature")
public class MainSignature implements Serializable {
    @Column(name = "id", type = MySqlTypeConstant.INT, isKey = true,isAutoIncrement = true,length = 10)
    private Integer id;
    @Column(name = "uid", type = MySqlTypeConstant.INT,length = 10)
    private Integer uid;
    @Column(name = "signature", type = MySqlTypeConstant.VARCHAR, length = 225)
    private String signature;
    @Column(name = "url", type = MySqlTypeConstant.VARCHAR, length = 225)
    private String url;
    @Column(name = "is_use_pwd", type = MySqlTypeConstant.INT,length = 1)
    private Integer isUsePwd;
}
