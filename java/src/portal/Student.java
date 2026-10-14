package portal;

import java.util.ArrayList;
import java.util.List;

/** Simple bean: one student plus the list of his/her files. */
public class Student {
    public static class FileInfo {
        private int id; private String category, name, type;
        public FileInfo(int id, String category, String name, String type) {
            this.id = id; this.category = category; this.name = name; this.type = type;
        }
        public int getId() { return id; }
        public String getCategory() { return category; }
        public String getName() { return name; }
        public String getType() { return type; }
        public boolean isImage() { return type.startsWith("image/"); }
    }
    private int id; private String name, rollNo, course, email;
    private List<FileInfo> files = new ArrayList<>();
    public Student(int id, String name, String rollNo, String course, String email) {
        this.id = id; this.name = name; this.rollNo = rollNo; this.course = course; this.email = email;
    }
    public int getId() { return id; }
    public String getName() { return name; }
    public String getRollNo() { return rollNo; }
    public String getCourse() { return course; }
    public String getEmail() { return email; }
    public List<FileInfo> getFiles() { return files; }
}
