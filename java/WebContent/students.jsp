<%@ page contentType="text/html;charset=UTF-8" import="java.util.*,portal.Student" %>
<%!
  // escape text before printing it into HTML (prevents XSS)
  static String h(String s) { return s == null ? "" : s.replace("&","&amp;").replace("<","&lt;").replace(">","&gt;").replace("\"","&quot;"); }
%>
<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Student Profiles</title>
<link rel="stylesheet" href="style.css"></head><body class="list">
<main class="card" style="max-width:1000px">
  <h1>Student Profiles</h1>
  <p>Logged in as <b><%= h((String) session.getAttribute("user")) %></b> | <a href="logout">Logout</a></p>
  <div class="grid">
  <%
    Collection<Student> students = (Collection<Student>) request.getAttribute("students");
    // looping: one card per student
    for (Student s : students) {
        // conditional logic: find the profile image, if any
        int photo = -1;
        for (Student.FileInfo f : s.getFiles()) if (f.getCategory().equals("profile")) photo = f.getId();
  %>
    <div class="stu">
      <% if (photo != -1) { %>
        <img src="file?id=<%= photo %>" alt="<%= h(s.getName()) %>">
      <% } else { %>
        <p><i>No profile image</i></p>
      <% } %>
      <h3><%= h(s.getName()) %></h3>
      <p><%= h(s.getRollNo()) %> | <%= h(s.getCourse()) %><br><%= h(s.getEmail()) %></p>
      <ul>
        <% for (Student.FileInfo f : s.getFiles()) { if (f.getCategory().equals("profile")) continue; %>
          <li><a href="file?id=<%= f.getId() %>" target="_blank"><%= h(f.getCategory() + ": " + f.getName()) %></a></li>
        <% } %>
      </ul>
    </div>
  <% } if (students.isEmpty()) { %><p>No students found.</p><% } %>
  </div>
</main></body></html>
