package portal;

import java.io.*;
import java.nio.file.*;
import java.sql.*;
import jakarta.servlet.*;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;

/** Streams a stored image/document. Usage: &lt;img src="file?id=5"&gt; */
@WebServlet("/file")
public class FileServlet extends HttpServlet {
    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse res) throws ServletException, IOException {
        int id;
        try { id = Integer.parseInt(req.getParameter("id")); }
        catch (NumberFormatException e) { res.sendError(400); return; }

        String stored = null, type = null, original = null;
        try (Connection c = DB.get(getServletContext());
             PreparedStatement ps = c.prepareStatement("SELECT file_path,file_type,original_name FROM student_files WHERE id=?")) {
            ps.setInt(1, id);
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) { stored = rs.getString(1); type = rs.getString(2); original = rs.getString(3); }
            }
        } catch (SQLException e) { throw new ServletException(e); }
        if (stored == null) { res.sendError(404); return; }

        // the client never supplies a path - only an id - so path traversal is impossible
        Path file = Paths.get(getServletContext().getInitParameter("uploadDir"), stored);
        if (!Files.exists(file)) { res.sendError(404); return; }
        res.setContentType(type);
        res.setContentLengthLong(Files.size(file));
        res.setHeader("Content-Disposition", "inline; filename=\"" + original.replace("\"", "") + "\"");
        Files.copy(file, res.getOutputStream());   // stream bytes to the browser
    }
}
