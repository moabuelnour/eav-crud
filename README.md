# **🚀 Getting Started**

## **1️⃣ Prerequisites**

Ensure you have the following installed:

- **PHP 8.x**
- **Composer**
- **MySQL**
- **Postman / Insomnia** (for API testing)

---

## **2️⃣ Installation Steps**

### **Clone the Repository**

```
git clone https://github.com/moabuelnour/eav-crud.git
cd eav-crud
```

### **Install Dependencies**

```
composer install
```

### **Set Up Environment**

```
cp .env.example .env
```

Update `.env` with database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=database_user
DB_PASSWORD=database_password
```

### **Generate App Key**

```
php artisan key:generate
```

### **Run Migrations**

```
php artisan migrate
```

### **Run Seed Database (dummy data)**

```
php artisan seed DummySeeder
```

### **Install & Configure Passport**

```
php artisan passport:install
```

This will generate keys for OAuth2 authentication. Update `.env` with:

```
PASSPORT_PERSONAL_ACCESS_CLIENT_ID=client-id-here
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=client-secret-here
```

### **Start the Server**

```
php artisan serve
```

The project will be accessible at `http://127.0.0.1:8000`.

---

# **👀 Advanced Filtering**

### **Usage:**

- Filtering is supported on both **standard attributes** and **EAV attributes**.
- Supported operators: `=`, `>`, `<`, `LIKE`.

#### **Example Requests:**

```
GET /api/projects?filters[name]=ProjectA&filters[department]=IT
GET /api/projects?filters[status]=Active&filters[start_date][>]=2024-01-01
```

#### **Example Response:**

```
{
  "data": [
    {
      "id": 1,
      "name": "ProjectX",
      "status": "Active",
      "attributes": {
        "Department": "IT",
        "Start Date": "2024-12-25"
      }
    }
  ]
}
```

---

# **📡 API Documentation**

Postman Documentation: https://documenter.getpostman.com/view/6469965/2sAYkAQhnP

## **🔑 Authentication**

### **Register User**

- **Endpoint:** `POST /api/register`
- **Request Body:**

```
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

- **Response:**

```
{
  "message": "User registered successfully",
  "token": "Bearer xxxxx"
}
```

### **Login User**

- **Endpoint:** `POST /api/login`
- **Request Body:**

```
{
  "email": "john@example.com",
  "password": "password123"
}
```

- **Response:**

```
{
  "message": "Login successful",
  "token": "Bearer xxxxx"
}
```

### **Logout User**

- **Endpoint:** `POST /api/logout`
- **Headers:**

```
{
  "Authorization": "Bearer xxxxx"
}
```

- **Response:**

```
{
  "message": "Logout successful"
}
```

---

## **📂 Projects**

### **1️⃣ Create a Project**

- **Endpoint:** `POST /api/projects`
- **Headers:**

```
{
  "Authorization": "Bearer xxxxx"
}
```

- **Request Body:**

```
{
  "name": "New Project",
  "status": "Active",
  "attributes": [
    {"attribute_id": 1, "value": "IT"},
    {"attribute_id": 2, "value": "2024-12-25"}
  ]
}
```

- **Response:**

```
{
  "message": "Project created successfully",
  "project": {
    "id": 1,
    "name": "New Project",
    "status": "Active"
  }
}
```

### **2️⃣ Get All Projects (With Filtering)**

- **Endpoint:**
  `GET /api/projects?filters[name]=ProjectX&filters[status]=Active`
- **Response:**

```json
{
  "data": [
    {
      "id": 1,
      "name": "ProjectX",
      "status": "Active",
      "attributes": {
        "Department": "IT",
        "Start Date": "2024-12-25"
      }
    }
  ]
}
```

### **3️⃣ Get a Specific Project**

- **Endpoint:** `GET /api/projects/{id}`
- **Response:**

```json
{
  "id": 1,
  "name": "ProjectX",
  "status": "Active",
  "attributes": {
    "Department": "IT",
    "Start Date": "2024-12-25"
  }
}
```

### **4️⃣ Update a Project**

- **Endpoint:** `PUT /api/projects/{id}`
- **Request Body:**

```json
{
  "name": "Updated Project",
  "status": "Completed"
}
```

- **Response:**

```json
{
  "message": "Project updated successfully",
  "project": {
    "id": 1,
    "name": "Updated Project",
    "status": "Completed"
  }
}
```

### **5️⃣ Delete a Project**

- **Endpoint:** `DELETE /api/projects/{id}`
- **Response:**

```json
{
  "message": "Project deleted successfully"
}
```

---

## **📜 Attributes (EAV)**

### **1️⃣ Get All Attributes**

- **Endpoint:** `GET /api/attributes`
- **Response:**

```json
{
  "data": [
    {
      "id": 1,
      "name": "Department",
      "type": "text"
    },
    {
      "id": 2,
      "name": "Start Date",
      "type": "date"
    }
  ]
}
```

### **2️⃣ Create an Attribute**

- **Endpoint:** `POST /api/attributes`
- **Request Body:**

```json
{
  "name": "Budget",
  "type": "number"
}
```

- **Response:**

```json
{
  "message": "Attribute created successfully",
  "attribute": {
    "id": 3,
    "name": "Budget",
    "type": "number"
  }
}
```

### **3️⃣ Update an Attribute**

- **Endpoint:** `PUT /api/attributes/{id}`
- **Request Body:**

```json
{
  "name": "New Budget",
  "type": "number"
}
```

- **Response:**

```json
{
  "message": "Attribute updated successfully"
}
```

### **4️⃣ Delete an Attribute**

- **Endpoint:** `DELETE /api/attributes/{id}`
- **Response:**

```json
{
  "message": "Attribute deleted successfully"
}
```

---

## **📊 Timesheets**

### **1️⃣ Create a Timesheet**

- **Endpoint:** `POST /api/timesheets`
- **Request Body:**

```json
{
  "project_id": 1,
  "task_name": "Develop API",
  "date": "2025-01-01",
  "hours": 4
}
```

- **Response:**

```json
{
  "message": "Timesheet created successfully",
  "timesheet": {
    "id": 1,
    "task_name": "Develop API",
    "date": "2025-01-01",
    "hours": 4
  }
}
```

### **2️⃣ Get All Timesheets**

- **Endpoint:** `GET /api/timesheets`
- **Response:**

```json
{
  "data": [
    {
      "id": 1,
      "project_id": 1,
      "task_name": "Develop API",
      "date": "2025-01-01",
      "hours": 4
    }
  ]
}
```

### **3️⃣ Update a Timesheet**

- **Endpoint:** `PUT /api/timesheets/{id}`
- **Request Body:**

```json
{
  "task_name": "Updated Task",
  "hours": 5
}
```

- **Response:**

```json
{
  "message": "Timesheet updated successfully"
}
```

### **4️⃣ Delete a Timesheet**

- **Endpoint:** `DELETE /api/timesheets/{id}`
- **Response:**

```json
{
  "message": "Timesheet deleted successfully"
}
```

---

# **🔐 Test Credentials**

| Email                      | Password |
| -------------------------- | -------- |
| moabuelnour@programmer.net | password |

```
```
