package org.chencc.service.impl;

import org.chencc.mapper.AssetsSecondmentMapper;
import org.chencc.service.AssetsSecondmentService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/26
 * @Time:下午8:42
 */
@Service
@Transactional
public class AssetsSecondmentServiceImpl implements AssetsSecondmentService {
    @Autowired
    private AssetsSecondmentMapper assetsSecondmentMapper;
}
