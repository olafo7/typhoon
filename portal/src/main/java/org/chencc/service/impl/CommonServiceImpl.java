package org.chencc.service.impl;

import org.chencc.mapper.CommonMapper;
import org.chencc.service.CommonService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/29
 * @Time:下午11:05
 */
@Service
@Transactional
public class CommonServiceImpl implements CommonService {
    @Autowired
    private CommonMapper commonMapper;

    @Override
    public void updatePagesize(Integer uid, String id, Integer pagesize) {
        commonMapper.updatePagesize(uid,id,pagesize);
    }
}
