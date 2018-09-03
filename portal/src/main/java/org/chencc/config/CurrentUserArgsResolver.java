package org.chencc.config;

import org.apache.commons.lang.StringUtils;
import org.chencc.common.CurrentUserWrapper;
import org.chencc.service.MainUserService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.core.MethodParameter;
import org.springframework.web.bind.support.WebDataBinderFactory;
import org.springframework.web.context.request.NativeWebRequest;
import org.springframework.web.context.request.RequestAttributes;
import org.springframework.web.method.support.HandlerMethodArgumentResolver;
import org.springframework.web.method.support.ModelAndViewContainer;
import org.springframework.web.multipart.support.MissingServletRequestPartException;

import javax.servlet.http.HttpServletRequest;
import java.util.List;
import java.util.Map;

/**
 * @Description
 * @author:chencc
 * @Date:2018/7/29
 * @Time:上午10:39
 */
public class CurrentUserArgsResolver implements HandlerMethodArgumentResolver {
    @Autowired
    private MainUserService mainUserService;

    @Override
    public Object resolveArgument(MethodParameter parameter, ModelAndViewContainer container, NativeWebRequest nativeWebRequest,
                                  WebDataBinderFactory binderFactory) throws Exception {

        HttpServletRequest request = nativeWebRequest.getNativeRequest(HttpServletRequest.class);
        CurrentUserWrapper currentUser = (CurrentUserWrapper)request.getSession().getAttribute("currentUser");
        if (currentUser != null) {
            return currentUser;
        }
        throw new MissingServletRequestPartException("currentUser");
    }


    @Override
    public boolean supportsParameter(MethodParameter methodParameter) {
        //如果参数类型是User并且有CurrentUser注解则支持
        if (methodParameter.getParameterType().isAssignableFrom(CurrentUserWrapper.class) &&
                methodParameter.hasParameterAnnotation(CurrentUser.class)) {
            return true;
        }
        return false;
    }
}
