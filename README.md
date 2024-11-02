
## Flowchart and System Design

Flowchart Overview: Here’s a basic flow for the Library Book Management System.

1. User Registration & Login:
- User signs up or logs in using Sanctum tokens.
- Tokens authenticate requests to the system.

2. Book Management:
- Librarians can add, edit, and delete books.
- Members can view book availability.

3. Loan Management:
- Members can borrow and return books.
- The system checks for book availability before borrowing.

## Project Delivery Document

1. Project Structure and Requirements
- Framework: Laravel 11 (API only)
- Authentication: Sanctum for secure API authentication
- Authorization: Policies and roles (librarian and member) for resource management

2. Endpoints:
- Auth: Register, Login, Logout
- Books: Add, Edit, Delete, List, View details
- Loans: Borrow, Return, List user’s loans

3. System Requirements
- PHP: 8.2 or higher
- Database: MySQL
- Tools: Laravel Sanctum for API security

4. Deployment and Development
- Clone the repository, run composer install, and set up .env for local DB.
- Run php artisan migrate --seed to initialize database tables.
