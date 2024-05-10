DROP TABLE book;

CREATE TABLE IF NOT EXISTS book (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    title VARCHAR(255) NOT NULL, isbn VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL
);

INSERT INTO book
    (title, isbn, author, image_path)
VALUES
    ('Elephants Can Remember', '9780002312202', 'Agatha Christie', 'https://pictures.abebooks.com/isbn/9780002312202-uk.jpg'),
    ('Void Moon', '0752821385', 'Michael Connelly', 'https://pictures.abebooks.com/isbn/9780752821382-uk.jpg'),
    ('Walkin the Dog', '1852426500 ', 'Walter Mosley', 'https://pictures.abebooks.com/inventory/22660540583.jpg')
;