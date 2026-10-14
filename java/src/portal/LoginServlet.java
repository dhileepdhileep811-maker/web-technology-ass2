package portal;

import java.io.IOException;
import java.security.MessageDigest;
import java.sql.*;
import jakarta.servlet.*;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;

@WebServlet("/login")
public class LoginServlet extends HttpServlet {
    // hash the typed password with SHA-256 so it can be compared with the stored hash
    static String sha256(String s) throws Exception {
        StringBuilder sb = new StringBuilder();
        for (byte b : MessageDigest.getInstance("SHA-256").digest(s.getBytes("UTF-8")))
            sb.append(String.format("%02x", b));
        return sb.toString();
    }

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse res) throws ServletException, IOException {
        String user = req.getParameter("username"), pass = req.getParameter("password");
        try (Connection c = DB.get(getServletContext());
             PreparedStatement ps = c.prepareStatement("SELECT id FROM users WHERE username=? AND password_hash=?")) {
            ps.setString(1, user);
            ps.setString(2, sha256(pass == null ? "" : pass));
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    req.getSession().invalidate();                 // new session id (prevents session fixation)
                    HttpSession s = req.getSession(true);
                    s.setAttribute("user", user);                  // marks the session as logged in
                    s.setMaxInactiveInterval(15 * 60);             // 15 min timeout
                    res.sendRedirect("students");
                    return;
                }
            }
            req.setAttribute("error", "Invalid username or password");
            req.getRequestDispatcher("/login.jsp").forward(req, res);
        } catch (Exception e) { throw new ServletException(e); }
    }
}
