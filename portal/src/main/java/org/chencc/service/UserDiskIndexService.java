package org.chencc.service;


import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/30
 * @Time:下午10:04
 */
public interface UserDiskIndexService {
    List<Map> getUserDirByPid(Integer uid,Integer pid);
}
