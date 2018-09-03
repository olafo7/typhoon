package org.chencc.model;

import com.gitee.sunchenbin.mybatis.actable.annotation.Column;
import com.gitee.sunchenbin.mybatis.actable.annotation.Table;
import com.gitee.sunchenbin.mybatis.actable.constants.MySqlTypeConstant;
import lombok.Data;

import java.io.Serializable;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/30
 * @Time:下午10:17
 */
@Data
@Table(name = "db_user_disk_main")
public class UserDiskMain implements Serializable {
    @Column(name = "fid", type = MySqlTypeConstant.INT, isKey = true,isAutoIncrement = true,length = 10)
    private Integer fid;
    @Column(name = "uid", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private Integer uid;
    @Column(name = "pid", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private Integer pid;
    @Column(name = "name", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 150)
    private String name;
    @Column(name = "ext", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 10)
    private String ext;
    @Column(name = "store_name", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 60)
    private String storeName;
    @Column(name = "path", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 500)
    private String path;
    @Column(name = "path2", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 500)
    private String path2;
    @Column(name = "type", type = MySqlTypeConstant.VARCHAR, isNull = false,length = 5)
    private String type; //'d','f','sf'
    @Column(name = "share_from", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private Integer shareFrom;
    @Column(name = "from_file_id", type = MySqlTypeConstant.INT, isNull = false,length = 11)
    private Integer fromFileId;
    @Column(name = "post_time", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private String postTime;
    @Column(name = "size", type = MySqlTypeConstant.INT, isNull = false,length = 11)
    private Integer size;
    @Column(name = "download", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer download;
    @Column(name = "edit", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer edit;
    @Column(name = "email", type = MySqlTypeConstant.INT, isNull = false,length = 1)
    private Integer email;
    @Column(name = "dis_time", type = MySqlTypeConstant.INT, isNull = false,length = 10)
    private Integer disTime;
    @Column(name = "dis_download", type = MySqlTypeConstant.INT, isNull = false,length = 5)
    private Integer disDownload;
    @Column(name = "download_times", type = MySqlTypeConstant.INT, isNull = false,length = 5)
    private Integer downloadTimes;
    @Column(name = "dis_view", type = MySqlTypeConstant.INT, isNull = false,length = 5)
    private Integer disiew;
    @Column(name = "view_times", type = MySqlTypeConstant.INT, isNull = false,length = 5)
    private Integer viewTimes;
}
