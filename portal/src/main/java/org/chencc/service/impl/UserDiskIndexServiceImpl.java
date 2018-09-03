package org.chencc.service.impl;

import org.chencc.mapper.UserDiskIndexMapper;
import org.chencc.service.UserDiskIndexService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/30
 * @Time:下午10:05
 */
@Service
@Transactional
public class UserDiskIndexServiceImpl implements UserDiskIndexService {
    @Autowired
    private UserDiskIndexMapper userDiskIndexMapper;

    @Override
    public List<Map> getUserDirByPid(Integer uid, Integer pid){
        return userDiskIndexMapper.getUserDirByPid(uid, pid);
    }



}
