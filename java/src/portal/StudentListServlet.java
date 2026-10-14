package portal;

import java.io.IOException;
import java.sql.*;
import java.util.*;
import jakarta.servlet.*;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;

/** Fetches all students + file metadata via JDBC and forwards to students.jsp. */
@WebServlet("/students")
public class StudentListServlet extends HttpServlet {
    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse res) throws ServletException, IOException {
        Map<Integer, Student> map = new LinkedHashMap<>();
        try (Connection c = DB.get(getServletContext());
             Statement st = c.createStatement()) {
            try (ResultSet rs = st.executeQuery("SELECT id,name,roll_no,course,email FROM students ORDER BY id")) {
                while (rs.next())   // loop: build one Student per row
                    map.put(rs.getInt(1), new Student(rs.getInt(1), rs.getString(2), rs.getString(3),
                                                      rs.getString(4), rs.getString(5)));
            }
            try (ResultSet rs = st.executeQuery("SELECT id,student_id,category,original_name,file_type FROM student_files")) {
                while (rs.next()) {
                    Student s = map.get(rs.getInt(2));
                    if (s != null) s.getFiles().add(new Student.FileInfo(rs.getInt(1), rs.getString(3),
                                                                         rs.getString(4), rs.getString(5)));
                }
            }
        } catch (SQLException e) { throw new ServletException(e); }
        req.setAttribute("students", map.values());
        req.getRequestDispatcher("/students.jsp").forward(req, res);
    }
}
