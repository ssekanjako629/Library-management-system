
-- LIBRARY MANAGEMENT SYSTEM
-- SQL REPORT QUERIES

-- 1. Display all books with their authors and categories
SELECT
    books.book_id,
    books.title,
    authors.name AS author,
    categories.name AS category,
    books.total_copies,
    books.available_copies
FROM books
INNER JOIN authors
    ON books.author_id = authors.author_id
INNER JOIN categories
    ON books.category_id = categories.category_id;


-- 2. Display all loans with member and book details
SELECT
    loans.loan_id,
    members.full_name AS member,
    books.title AS book,
    loans.date_issued,
    loans.due_date,
    loans.date_returned
FROM loans
INNER JOIN members
    ON loans.member_id = members.member_id
INNER JOIN books
    ON loans.book_id = books.book_id;


-- 3. Display overdue books and days overdue
SELECT
    loans.loan_id,
    members.full_name AS member,
    books.title AS book,
    loans.due_date,
    DATEDIFF(CURDATE(), loans.due_date) AS days_overdue
FROM loans
INNER JOIN members
    ON loans.member_id = members.member_id
INNER JOIN books
    ON loans.book_id = books.book_id
WHERE loans.date_returned IS NULL
AND loans.due_date < CURDATE();


-- 4. Library summary report
SELECT
    COUNT(*) AS total_books,
    SUM(total_copies) AS total_copies,
    SUM(available_copies) AS available_copies,
    SUM(total_copies - available_copies) AS borrowed_copies
FROM books;
