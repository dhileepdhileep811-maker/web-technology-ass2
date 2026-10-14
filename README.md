# Student Admission Portal – Web Technology Assignment II (U21CS501)

## Structure
| Folder | Question | Contents |
|---|---|---|
| `frontend/` | Q1 | `register.html`, `style.css`, `validate.js` |
| `php/` + `db/schema.sql` | Q2 | CRUD pages, upload handling, `uploads/` |
| `java/` | Q3 | Servlets, filter, JSP, `WEB-INF/web.xml` |

## Setup
1. Run `db/schema.sql` in MySQL (creates DB, tables and user `admin` / `Admin@123`).
2. Copy the project to `htdocs/student-portal` (XAMPP). Open `http://localhost/student-portal/frontend/register.html`; records at `php/index.php`.
3. Java: import `java/` into Eclipse/IntelliJ as a Dynamic Web Project (Tomcat 10+, Jakarta Servlet 6, MySQL Connector/J on the classpath (no JSTL needed)). Set `uploadDir` in `web.xml` to the absolute path of `php/uploads/`. Open `/login.jsp`.

## Explanation
**Q1 – Front end.** `register.html` has name, roll number, email, course, password and file upload. `style.css` uses a centred card, focus rings, red/green field states and a media query for phones. `validate.js` checks each field on blur and on submit: required values, regex for roll number and email, password strength (8+ chars with upper, lower, digit, special; a `<meter>` shows strength), and image type/size (JPG/PNG, ≤ 2 MB). Submission is blocked if any rule fails.

**Q2 – PHP + MySQL.** `config.php` opens a PDO connection and contains `saveUpload()`, which checks size (2 MB), detects the real MIME type with `finfo`, allows types per category (profile, ID proof, certificate), stores the file under a random unique name (so nothing is overwritten) and inserts metadata (path, type, size, upload date) into `student_files`, linked by `student_id`. `add.php` = Create, `index.php`/`view.php` = Read (view shows images inline and links to documents), `edit.php` = Update, `delete.php` = Delete (removes files from disk; rows cascade). Prepared statements prevent SQL injection and `h()` prevents XSS; server-side validation repeats the client checks.

**Q3 – Servlet/JDBC.** `LoginServlet` verifies the SHA-256 password hash and creates a session (15-minute timeout, new session id on login). `AuthFilter` guards `/students` and `/file`, redirecting anonymous users to the login page. `StudentListServlet` loads students and their files via JDBC into `Student` beans and forwards to `students.jsp`, which loops over students with a Java `for` loop and uses `if/else` to show a profile image or placeholder and list documents. `FileServlet` receives only a file id (`<img src="file?id=5">`), looks the path up in MySQL and streams the file with the correct content type, so clients can never request arbitrary paths. `LogoutServlet` invalidates the session.

## Conclusion
The portal covers the full flow: a validated, responsive registration form feeds a PHP/MySQL admin panel with secure file uploads, and a session-protected Servlet/JSP portal displays the same records and files through JDBC. Security measures include prepared statements, output escaping, MIME checks, unique file names and session filtering. Sharing one database and upload folder lets both technology stacks work on the same data.

## Screenshots
Add browser screenshots of: registration form (valid/invalid), `php/index.php`, `view.php`, `login.jsp`, `students.jsp`.
GitHub repository link: _<add after pushing>_
