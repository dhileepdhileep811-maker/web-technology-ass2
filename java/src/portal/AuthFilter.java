package portal;

import java.io.IOException;
import jakarta.servlet.*;
import jakarta.servlet.annotation.WebFilter;
import jakarta.servlet.http.*;

/** Session handling: every protected URL passes through here. Not logged in => redirect to login. */
@WebFilter(urlPatterns = {"/students", "/file", "/students.jsp"})
public class AuthFilter implements Filter {
    @Override
    public void doFilter(ServletRequest rq, ServletResponse rs, FilterChain chain) throws IOException, ServletException {
        HttpServletRequest req = (HttpServletRequest) rq;
        HttpServletResponse res = (HttpServletResponse) rs;
        HttpSession s = req.getSession(false);
        if (s == null || s.getAttribute("user") == null) {
            res.sendRedirect(req.getContextPath() + "/login.jsp");
            return;
        }
        chain.doFilter(rq, rs);
    }
}
