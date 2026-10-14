package portal;

import java.sql.*;
import jakarta.servlet.ServletContext;

/** Small helper that opens a JDBC connection using the settings from web.xml. */
public class DB {
    public static Connection get(ServletContext ctx) throws SQLException {
        try { Class.forName("com.mysql.cj.jdbc.Driver"); }          // MySQL Connector/J
        catch (ClassNotFoundException e) {
            try { Class.forName("org.mariadb.jdbc.Driver"); }       // MariaDB driver as fallback
            catch (ClassNotFoundException e2) { throw new SQLException("JDBC driver missing", e2); }
        }
        return DriverManager.getConnection(ctx.getInitParameter("dbUrl"),
                ctx.getInitParameter("dbUser"), ctx.getInitParameter("dbPass"));
    }
}
