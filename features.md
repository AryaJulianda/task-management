# Project Name : bpkh_task_management

Features :

## 🔐 1. Authentication

- Register
- Login
- Logout
- Update profile
- Change password

---

## 📁 2. Project Management

- Create project
- Edit project
- Delete project
- List project milik user

---

## 🗂 3. Task Management (Core)

- Create task
- Edit task
- Delete task
- Assign task ke user (optional kalau solo)
- Status:
    - Todo
    - In Progress
    - Done
- Priority:
    - Low
    - Medium
    - High
- Due date
- Basic filtering (status / priority)

---

## 💬 4. Task Comment

- Add comment
- Delete comment
- List comment per task

---

# 📊 ERD

## 👤 users

```
users
- id (uuid) PK
- name
- email (unique)
- password
- created_at
- updated_at
- deleted_at
```

---

## 📁 projects

```
projects
- id (uuid) PK
- user_id (FK -> users.id)
- name
- description
- created_at
- updated_at
- deleted_at
```

Relasi:

- 1 User → Many Projects

---

## 🗂 tasks

```
tasks
- id (uuid) PK
- project_id (FK -> projects.id)
- title
- description
- status (todo/in_progress/done)
- priority (low/medium/high)
- due_date
- created_by (FK -> users.id)
- created_at
- updated_at
- deleted_at
```

Relasi:

- 1 Project → Many Tasks
- 1 User → Many Tasks (assignee)

## 🗂 tasks_assignees

```
task_assignees
- task_id (FK -> tasks.id)
- user_id (FK -> users.id)
- assigned_at
PRIMARY KEY (task_id, user_id)
```

Relasi:

- 1 Task → Many Users
- 1 User → Many Tasks

---

## 💬 task_activities

```
task_activities
- id (uuid) PK
- task_id (FK -> tasks.id)
- user_id (FK -> users.id)
- comment
- created_at
- updated_at
```

Relasi:

- 1 Task → Many activities
- 1 User → Many activities

---

# 📌 Relasi Diagram Sederhana

```
User
 ├── Projects
 │     └── Tasks
 │            └── Comments
 └── Assigned Tasks
```

---

# 🚀 Improvement Path

- workspace (multi-team)
- Add label system
- Add activity log
- Add notification
- Add attachment
- Add drag & drop kanban

&nbsp;
