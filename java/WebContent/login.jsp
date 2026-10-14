<%@ page contentType="text/html;charset=UTF-8" %>
<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Login</title>
<link rel="stylesheet" href="style.css"></head><body>
<main class="card"><h1>Student Portal Login</h1>
<% if (request.getAttribute("error") != null) { %>
  <p class="error"><%= request.getAttribute("error") %></p>
<% } %>
<form action="login" method="post">
  <div class="field"><label>Username</label><input name="username" required></div>
  <div class="field"><label>Password</label><input type="password" name="password" required></div>
  <button>Login</button>
</form></main></body></html>
