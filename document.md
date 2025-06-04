# NestJS Todo Application Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [Application Architecture](#application-architecture)
3. [Core Modules](#core-modules)
4. [Services](#services)
5. [Controllers](#controllers)
6. [DTOs (Data Transfer Objects)](#dtos-data-transfer-objects)
7. [Entities](#entities)
8. [Middleware](#middleware)
9. [Database Configuration](#database-configuration)
10. [Testing Guide](#testing-guide)
11. [API Endpoints](#api-endpoints)

---

## Project Overview

This is a NestJS-based Todo application that demonstrates modern backend development practices including:
- **Modular architecture** with feature modules
- **Dynamic modules** for flexible configuration
- **Dependency injection** for loose coupling
- **Validation** using DTOs and class-validator
- **Database integration** with TypeORM and SQLite
- **Middleware** for request logging
- **Notification system** with multiple output types

---

## Application Architecture

### What is NestJS?
NestJS is a Node.js framework for building scalable server-side applications. It uses TypeScript by default and combines elements of OOP (Object Oriented Programming), FP (Functional Programming), and FRP (Functional Reactive Programming).

### Why NestJS?
- **Modular Structure**: Organizes code into modules for better maintainability
- **Dependency Injection**: Makes code more testable and flexible
- **TypeScript Support**: Provides type safety and better developer experience
- **Decorator-based**: Uses decorators for clean, declarative code

---

## Core Modules

### 1. App Module (`src/app.module.ts`)

#### What is it?
The root module of the application that imports all feature modules and configures the database connection.

#### How to use it?
```typescript
@Module({
  imports: [
    TypeOrmModule.forRoot({
      type: 'sqlite',
      database: 'todo.sqlite',
      entities: [User, Task],
      synchronize: true,
    }),
    UserModule,
    TaskModule,
  ],
  controllers: [AppController],
  providers: [AppService],
})
export class AppModule implements NestModule {
  configure(consumer: MiddlewareConsumer) {
    consumer.apply(LoggerMiddleware).forRoutes('*');
  }
}
```

#### Why we use it?
- **Central Configuration**: All modules are registered here
- **Database Setup**: TypeORM configuration is defined here
- **Global Middleware**: Applied to all routes

#### When to use it?
- When you need to add new feature modules
- When configuring global settings like database connections
- When applying global middleware

---

### 2. Notification Module (`src/notification/notification.module.ts`)

#### What is it?
A **dynamic module** that provides notification functionality with configurable output types (email, SMS, log).

#### How to use it?
```typescript
// Register with specific configuration
NotificationModule.register({ type: 'log' })
NotificationModule.register({ type: 'email' })
NotificationModule.register({ type: 'sms' })
```

#### Why we use it?
- **Flexibility**: Can be configured differently for different environments
- **Reusability**: Same module can be used with different configurations
- **Dynamic Configuration**: Options are passed at runtime

#### When to use it?
- When you need configurable modules
- When building libraries or reusable components
- When module behavior needs to change based on environment

---

### 3. User Module (`src/modules/user/user.module.ts`)

#### What is it?
A feature module that encapsulates all user-related functionality including controllers, services, and entities.

#### How to use it?
```typescript
@Module({
  imports: [TypeOrmModule.forFeature([User])],
  providers: [UsersService],
  controllers: [UsersController],
  exports: [UsersService], // Make service available to other modules
})
export class UserModule {}
```

#### Why we use it?
- **Separation of Concerns**: User logic is isolated
- **Maintainability**: Easier to maintain and test
- **Reusability**: Can be imported by other modules

#### When to use it?
- When managing user authentication and profiles
- When other modules need user data
- When implementing user-related business logic

---

### 4. Task Module (`src/modules/task/task.module.ts`)

#### What is it?
A feature module that handles task management functionality and integrates with the notification system.

#### How to use it?
```typescript
@Module({
  imports: [
    NotificationModule.register({type: 'log'}),
    TypeOrmModule.forFeature([Task]), 
    UserModule,
  ],
  providers: [TaskService],
  controllers: [TasksController],
})
export class TaskModule {}
```

#### Why we use it?
- **Task Management**: Handles all task-related operations
- **Integration**: Connects with user module and notification system
- **Business Logic**: Encapsulates task creation, updating, deletion

#### When to use it?
- When managing todo items or tasks
- When you need to track task ownership (user relationship)
- When implementing task notifications

---

## Services

### 1. Notification Service (`src/notification/notification.service.ts`)

#### What is it?
A service that handles sending notifications through different channels based on configuration.

#### How to use it?
```typescript
@Injectable()
export class TaskService {
  constructor(private readonly notifier: NotificationService) {}
  
  create(task: any) {
    this.notifier.notify(`Task "${task.name}" created.`);
  }
}
```

#### Why we use it?
- **Abstraction**: Hides notification implementation details
- **Flexibility**: Easy to switch between notification types
- **Centralized Logic**: All notification logic in one place

#### When to use it?
- When you need to send notifications
- When implementing audit trails
- When providing user feedback

---

### 2. Task Service (`src/modules/task/task.service.ts`)

#### What is it?
A service that contains all business logic for task management including CRUD operations and database interactions.

#### How to use it?
```typescript
// Inject into controller
constructor(private readonly taskService: TaskService) {}

// Use methods
const task = await this.taskService.createTask(createTaskDto);
const allTasks = await this.taskService.getAllTasks();
```

#### Why we use it?
- **Business Logic**: Separates business logic from HTTP handling
- **Database Abstraction**: Handles all database operations
- **Reusability**: Can be used by multiple controllers or services

#### When to use it?
- When implementing task CRUD operations
- When business logic becomes complex
- When you need to reuse task operations

---

### 3. Users Service (`src/modules/user/user.service.ts`)

#### What is it?
A service that manages user-related operations including creation, retrieval, updates, and deletion.

#### How to use it?
```typescript
// Inject into controller
constructor(private readonly userService: UsersService) {}

// Use methods
const user = await this.userService.create(createUserDto);
const users = await this.userService.findAll();
```

#### Why we use it?
- **User Management**: Handles all user operations
- **Data Validation**: Ensures user data integrity
- **Relationship Management**: Manages user-task relationships

#### When to use it?
- When implementing user registration
- When managing user profiles
- When implementing authentication systems

---

## Controllers

### 1. Tasks Controller (`src/modules/task/task.controller.ts`)

#### What is it?
A controller that defines HTTP endpoints for task management and handles request/response logic.

#### How to use it?
```typescript
// Available endpoints:
GET    /tasks           // Get all tasks
GET    /tasks/:id       // Get specific task
POST   /tasks           // Create new task
PATCH  /tasks/:id       // Update task
DELETE /tasks/:id       // Delete task
POST   /tasks/test-notification // Test notification
```

#### Why we use it?
- **HTTP Interface**: Provides REST API for task operations
- **Request Handling**: Processes HTTP requests and responses
- **Validation**: Applies validation to incoming data

#### When to use it?
- When building REST APIs
- When you need HTTP endpoints for task management
- When implementing web or mobile app backends

---

### 2. Users Controller (`src/modules/user/user.controller.ts`)

#### What is it?
A controller that exposes HTTP endpoints for user management operations.

#### How to use it?
```typescript
// Available endpoints:
GET    /users           // Get all users
GET    /users/:id       // Get specific user
POST   /users           // Create new user
PATCH  /users/:id       // Update user
DELETE /users/:id       // Delete user
```

#### Why we use it?
- **User API**: Provides REST endpoints for user operations
- **Data Processing**: Handles user data input and output
- **Validation**: Ensures incoming user data is valid

#### When to use it?
- When implementing user registration/login
- When building user management systems
- When creating admin panels

---

## DTOs (Data Transfer Objects)

### What are DTOs?
DTOs are classes that define the shape and validation rules for data being transferred between client and server.

### 1. Create User DTO (`src/modules/user/dto/create-user.dto.ts`)

#### How to use it?
```typescript
export class CreateUserDto {
    @IsNotEmpty()
    username: string;

    @IsEmail()
    email: string;

    @MinLength(3)
    password: string;
}
```

#### Why we use it?
- **Validation**: Ensures data meets requirements before processing
- **Type Safety**: Provides compile-time type checking
- **Documentation**: Serves as API documentation

#### When to use it?
- When accepting user input
- When you need data validation
- When building type-safe APIs

---

### 2. Create Task DTO (`src/modules/task/dto/create-task.dto.ts`)

#### How to use it?
```typescript
export class CreateTaskDto {
    @IsNotEmpty()
    @IsString()
    name: string;

    @IsOptional()
    @IsString()
    description?: string;

    @IsInt()
    userId: number;
}
```

#### Why we use it?
- **Task Validation**: Ensures task data is valid
- **Required Fields**: Enforces mandatory fields like name and userId
- **Type Checking**: Validates data types (string, number, etc.)

#### When to use it?
- When creating new tasks
- When validating task input from forms
- When ensuring data integrity

---

## Entities

### What are Entities?
Entities are classes that represent database tables and define the structure of your data.

### 1. User Entity (`src/modules/user/user.entity.ts`)

#### How to use it?
```typescript
@Entity()
export class User {
  @PrimaryGeneratedColumn()
  id: number;

  @Column()
  username: string;

  @Column()
  email: string;

  @OneToMany(() => Task, task => task.user)
  tasks: Task[];
}
```

#### Why we use it?
- **Database Mapping**: Maps to database tables
- **Relationships**: Defines relationships between entities
- **Type Safety**: Provides TypeScript types for database records

#### When to use it?
- When defining database schema
- When working with TypeORM
- When you need object-relational mapping

---

### 2. Task Entity (`src/modules/task/task.entity.ts`)

#### How to use it?
```typescript
@Entity()
export class Task {
  @PrimaryGeneratedColumn()
  id: number;

  @Column()
  name: string;

  @Column({ nullable: true })
  description: string;

  @ManyToOne(() => User, user => user.tasks)
  user: User;
}
```

#### Why we use it?
- **Task Structure**: Defines task database structure
- **User Relationship**: Links tasks to users
- **Data Persistence**: Handles task storage and retrieval

#### When to use it?
- When storing task data
- When querying tasks from database
- When managing task-user relationships

---

## Middleware

### Logger Middleware (`src/common/middleware/logger.middleware.ts`)

#### What is it?
Middleware that logs incoming HTTP requests with timestamp, method, and URL.

#### How to use it?
```typescript
@Injectable()
export class LoggerMiddleware implements NestMiddleware {
    use(req: any, res: any, next: NextFunction){
        console.log(`[${new Date().toISOString()}] ${req.method} ${req.originalUrl || req.url}`);
        next();
    }
}
```

#### Why we use it?
- **Debugging**: Helps track API usage and debug issues
- **Monitoring**: Provides insight into application traffic
- **Auditing**: Creates logs for security and compliance

#### When to use it?
- When you need request logging
- When debugging API issues
- When monitoring application performance

---

## Database Configuration

### TypeORM Setup
The application uses SQLite database with TypeORM for object-relational mapping.

#### Configuration:
```typescript
TypeOrmModule.forRoot({
  type: 'sqlite',
  database: 'todo.sqlite',
  entities: [User, Task],
  synchronize: true, // Only for development
})
```

#### Why SQLite?
- **Simplicity**: No separate database server needed
- **Development**: Perfect for development and testing
- **Portability**: Database file can be easily moved

#### When to use it?
- Development and testing environments
- Small to medium applications
- Prototyping and demos

---

## Testing Guide

### Running Tests
Based on the checklist, here are the key test scenarios:

#### 1. Create user without email
```bash
curl -X POST http://localhost:3000/users \
  -H "Content-Type: application/json" \
  -d '{"username": "testuser", "password": "12345"}'
```
**Expected**: Validation error (400 Bad Request)

#### 2. Create task without name
```bash
curl -X POST http://localhost:3000/tasks \
  -H "Content-Type: application/json" \
  -d '{"description": "Test task", "userId": 1}'
```
**Expected**: Validation error (400 Bad Request)

#### 3. Fetch non-existing task ID
```bash
curl -X GET http://localhost:3000/tasks/999
```
**Expected**: 404 Not Found

#### 4. Fetch non-existing user ID
```bash
curl -X GET http://localhost:3000/users/999
```
**Expected**: 404 Not Found

#### 5. Create task with non-integer userId
```bash
curl -X POST http://localhost:3000/tasks \
  -H "Content-Type: application/json" \
  -d '{"name": "Test Task", "userId": "invalid"}'
```
**Expected**: Validation error (400 Bad Request)

#### 6. Observe logs on each request
Every request should log:
```
[2025-06-04T18:11:19.784Z] POST /tasks
[2025-06-04T18:12:52.151Z] GET /users
```

---

## API Endpoints

### User Endpoints
| Method | Endpoint | Description | Body |
|--------|----------|-------------|------|
| GET | `/users` | Get all users | - |
| GET | `/users/:id` | Get user by ID | - |
| POST | `/users` | Create new user | `{username, email, password}` |
| PATCH | `/users/:id` | Update user | `{username?, email?, password?}` |
| DELETE | `/users/:id` | Delete user | - |

### Task Endpoints
| Method | Endpoint | Description | Body |
|--------|----------|-------------|------|
| GET | `/tasks` | Get all tasks | - |
| GET | `/tasks/:id` | Get task by ID | - |
| POST | `/tasks` | Create new task | `{name, description?, userId}` |
| PATCH | `/tasks/:id` | Update task | `{name?, description?}` |
| DELETE | `/tasks/:id` | Delete task | - |
| POST | `/tasks/test-notification` | Test notification | `{title}` |

---

## Getting Started

### Prerequisites
- Node.js (v14 or higher)
- npm or yarn

### Installation
```bash
# Install dependencies
npm install

# Start development server
npm run start:dev
```

### Environment
The application runs on port 3000 by default. You can change this by setting the `PORT` environment variable:
```bash
PORT=3001 npm run start:dev
```

---

## Best Practices

### 1. Module Organization
- Keep related functionality in the same module
- Export services that other modules might need
- Import only what you need

### 2. Service Design
- Keep services focused on single responsibility
- Use dependency injection for loose coupling
- Handle errors appropriately

### 3. Controller Design
- Keep controllers thin - delegate to services
- Use DTOs for validation
- Return appropriate HTTP status codes

### 4. Error Handling
- Use NestJS built-in exceptions
- Provide meaningful error messages
- Log errors for debugging

---

This documentation provides a comprehensive guide to understanding and working with the NestJS Todo application. Each component is explained in detail with practical examples and best practices for a complete beginner's understanding.