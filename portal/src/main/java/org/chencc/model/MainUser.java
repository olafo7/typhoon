package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description 系统用户实体类
 * @author:chencc
 * @Date:2018/7/21
 * @Time:下午10:32
 */

@Data
//@Table(name = "db_main_user")
public class MainUser implements Serializable {
    @Column(name = "uid", type = MySqlTypeConstant.INT, isKey = true,isAutoIncrement = true,length = 10)
    private Integer uid;
    @Column(name = "username", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 100)
    private String username;  //用户名
    @Column(name = "password", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 32)
    private String password;  //密码
    @Column(name = "salt", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 32)
    private String salt;  //盐值
    @Column(name = "is_system_user", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer isSystemUser;  //是否系统用户
    @Column(name = "language", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 20)
    private String language;  //语言
    @Column(name = "custom_permit", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 5)
    private String customPermit;
    @Column(name = "part_time", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 5)
    private String partTime;
    @Column(name = "part_time_job", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 50)
    private String partTimeJob;  //兼职职位
    @Column(name = "person_sign", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 200)
    private String personSign;
    @Column(name = "true_name", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 25)
    private String trueName;  //真实名称
    @Column(name = "bianhao", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 20)
    private String bianhao;
    @Column(name = "work_status_type", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer workStatusType;  //在职状态
    @Column(name = "sex", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer sex;  //性别
    @Column(name = "mobile", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 25)
    private String mobile;  //移动电话
    @Column(name = "work_phone", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 25)
    private String workPhone;  //工作电话
    @Column(name = "qq", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 100)
    private String qq;  //qq
    @Column(name = "wangwang", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 225)
    private String wangwang;  //旺旺号
    @Column(name = "email", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 40)
    private String email;  //邮箱
    @Column(name = "phone", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 25)
    private String phone;  //手机
    @Column(name = "birthday", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private Integer birthday;  //出生日期
    @Column(name = "born_data_type", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer bornDataType;
    @Column(name = "job_id", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer jobId;  //职位id
    @Column(name = "dept_id", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer deptId;  //部门
    @Column(name = "station_id", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer stationId;  //岗位
    @Column(name = "ruzhishijian", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer ruzhishijian;
    @Column(name = "address", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 150)
    private String address;  //地址
    @Column(name = "id_card", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 20)
    private String idCard;  //身份证号码
    @Column(name = "leave_day", type = MySqlTypeConstant.DOUBLE, isNull = false)
    private Float leaveDay;
    @Column(name = "worktime_id", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private Integer worktimeId;
    @Column(name = "bind_ip", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 16)
    private String bindIp;
    @Column(name = "import_key", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 8)
    private String importKey;
    @Column(name = "import_type", type = MySqlTypeConstant.INT, isNull = false,length = 2)
    private Integer importType;  //导入类型
    @Column(name = "face", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 100)
    private String face;
    @Column(name = "theme", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 20)
    private String theme;  //主题
    @Column(name = "last_login_ip", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 16)
    private String lastLoginIp;  //上一次登陆ip
    @Column(name = "last_login_time", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer lastLoginTime; //上一次登陆时间
    @Column(name = "order", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer order;  //排序
    @Column(name = "menu_collapsed", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer menuCollapsed;
    @Column(name = "dtpass_has", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer dtpassHas;
    @Column(name = "dtpass_inuse", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer dtpassInuse;
    @Column(name = "dtpass_sn", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 20)
    private String dtpassSn;
    @Column(name = "dtpass_info", type = MySqlTypeConstant.TEXT, isNull = false)
    private String dtpassInfo;
    @Column(name = "dtpass_old_info", type = MySqlTypeConstant.TEXT, isNull = false)
    private String dtpassOldInfo;
    @Column(name = "reg_time", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private Integer regTime;
    @Column(name = "exit_alert", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer exitAlert;
    @Column(name = "wf_auto_next", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer wfAutoNext;
    @Column(name = "wf_new_window", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer wfNewWindow;
    @Column(name = "index_layout", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer indexLayout;
    @Column(name = "ram_pass", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 32)
    private String ramPass;
    @Column(name = "qy_username", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 225)
    private String qyUsername;  //企业名称
    @Column(name = "is_sys_easemob", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer isSysEasemob;
}
