package org.chencc.service.impl;

import org.chencc.mapper.AssetsNumberSetMapper;
import org.chencc.model.AssetsNumber;
import org.chencc.service.AssetsNumberSetService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/24
 * @Time:下午11:25
 */
@Service
@Transactional
public class AssetsNumberSetServiceImpl implements AssetsNumberSetService {
    @Autowired
    private AssetsNumberSetMapper assetsNumberSetMapper;

    @Override
    public AssetsNumber getSortList(){
        return assetsNumberSetMapper.getSortList();
    }

    @Override
    public void saveEditRecord(Integer status, String zimu, String fuhao, String num, Integer zimuCheck,
                               Integer fuhaoCheck, String nowNum,String numShow) {
        assetsNumberSetMapper.saveEditRecord(status, zimu, fuhao, num, zimuCheck, fuhaoCheck, nowNum,numShow);
    }
}
