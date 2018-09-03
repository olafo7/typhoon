package org.chencc.config;

import org.springframework.stereotype.Component;

import javax.servlet.*;
import javax.servlet.annotation.WebFilter;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;

/**
 * @Description Session过滤器,将所有的请求保存
 * @author:chencc
 * @Date:2018/8/30
 * @Time:下午10:18
 */
@Component
@WebFilter(urlPatterns = "/*", filterName = "sessionValidate")
public class SessionValidateFilter implements Filter {
    @Override
    public void init(FilterConfig filterConfig) throws ServletException {
    }

    @Override
    public void doFilter(ServletRequest request, ServletResponse response,
                         FilterChain chain) throws IOException, ServletException {
        SystemContent.setRequest((HttpServletRequest) request);
        SystemContent.setResponse((HttpServletResponse) response);
        chain.doFilter(request, response);
    }

    @Override
    public void destroy() {

    }
}