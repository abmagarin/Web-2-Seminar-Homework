# Web-2-Seminar-Homework
**Group 01 - NOTEBOOK**

## Project setup
To set up the project, clone the repository to your `xampp/htdocs` directory.<br>
You should be able to **run Apache and SQL** via XAMPP and **create the Database** using the following URL:<br>
[http://localhost/Web-2-Seminar-Homework/create_db.php](http://localhost/Web-2-Seminar-Homework/create_db.php)<br>
<br>
If there is any problem accessing the URL, check the path to your repository inside your local files.<br>

## DB administration
To manage all the items in the database from the files `notebook.txt`, `opsystem.txt`, and `processor.txt`:
- **CREATE DATABASE**: [http://localhost/Web-2-Seminar-Homework/create_db.php](http://localhost/Web-2-Seminar-Homework/create_db.php)
- **INSERT DATA**: [http://localhost/Web-2-Seminar-Homework/import_data.php](http://localhost/Web-2-Seminar-Homework/import_data.php)
- **DELETE DATA**: [http://localhost/Web-2-Seminar-Homework/delete_data.php](http://localhost/Web-2-Seminar-Homework/delete_data.php)

## Pending tasks (Objectives)
Here is a list of objectives that need to be completed for the project. Use the checkboxes to track progress:

1. **Exercise 1 (Required element!)**
    - [x] a. Store the names of the menu items in the database.
    - [x] b. Implement a multi-level menu system.
    - [x] c. Registration and Login: Implement roles for "visitor", "registered visitor", and "admin".
    - [x] d. On the first page, introduce the selected topic.

2. **Find and apply a free responsive theme for your pages.** *(5 points)*
    - [x] Describe which theme you have chosen in the documentation. You can find ideas at the end of this document.

3. **SOAP server menu: Create a RESTful web service** *(5 points)*
    - [x] Use all tables in the database for this task.

4. **SOAP client menu: Create a client for the SOAP web service** *(5 points)*
    - [x] Test the SOAP service with the client.

5. **SOAP-MNB menu: Use the Hungarian National Bank SOAP data service** *(15 points)*
    - [x] Implement multiple query options:
        - a. What was the exchange rate on a given day for a given currency pair? (e.g., EUR-HUF, EUR-USD)
        - b. Display a table of exchange rates for each day of a given currency pair in a given month, along with a graph. Use [Chart.js](https://www.chartjs.org/) for graphing.

6. **RESTful server menu: Create a RESTful web service** *(5 points)*
    - [x] Implement GET, POST, PUT, and DELETE functions for one table in your database.
    - [x] Test the web service using cURL and Postman, and describe the testing steps in the documentation.

7. **RESTful client menu: Create a client for the RESTful web service** *(5 points)*
    - [x] Test the client with the RESTful web service (GET, POST, PUT, DELETE functions).

8. **PDF menu: Create a PDF creation service with TCPDF** *(5 points)*
    - [x] The user enters data in 3 text fields or selects from a drop-down list. The system reads from the database and creates a downloadable PDF using data from 3 tables.

9. **Upload and implement your application on Internet hosting (Mandatory element!)**
    - [x] Ensure the project works on a live server and check the functionality.
    - [x] The URL must include the name of one of the group members.

10. **Use the GitHub version control system (Mandatory element!)**
    - [x] Upload the project in at least 5 steps per person, showing sub-states of the project.
    - [x] Each team member must have an identifiable username on GitHub.

11. **Use the Project work method on GitHub** *(5 points)*
    - [x] Implement proper project management using GitHub project boards and issues.

12. **Create a documentation of at least 15 pages with screenshots (Mandatory element!)**
    - [x] Present the application and describe how each task was implemented.
    - [x] Include the URL of your hosted page, the GitHub repository, and login credentials for both an admin and a regular user.
