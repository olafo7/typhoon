package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description 内部通知实体类
 * @author:chencc
 * @Date:2018/7/21
 * @Time:下午11:40
 */

@Data
//@Table(name = "db_main_message")
public class MainMessage implements Serializable {
    @Column(name = "id", type = MySqlTypeConstant.INT, isKey = true,isAutoIncrement = true,length = 10)
    private Integer id;
    @Column(name = "title", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 60)
    private String title;  //标题
    @Column(name = "content", type = MySqlTypeConstant.TEXT, isNull = false)
    private String content;  //内容
    @Column(name = "start", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private String start;  //开始时间
    @Column(name = "end", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private String end;   //结束时间
    @Column(name = "post_time", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private String postTime;  //发送时间
    @Column(name = "from_uid", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private Integer fromUid;  //发件人id
    @Column(name = "state", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer state;  //是否过期
    @Column(name = "in_use", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer inUse;  //是否启用
    @Column(name = "attach", type = MySqlTypeConstant.TEXT, isNull = false)
    private String attach;  //附件
}
