CREATE DATABASE flowerpower;

CREATE TABLE employee(
    id INT NOT NULL AUTO_INCREMENT,
    initials VARCHAR(255) NOT NULL,
    prefix VARCHAR(255),
    last_name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);

CREATE TABLE customer(
	id INT NOT NULL AUTO_INCREMENT,
    initials VARCHAR(255) NOT NULL,
    prefix VARCHAR(255),
    last_name VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    postal_code VARCHAR(255) NOT NULL,
    residence VARCHAR(255) NOT NULL,
    birth_date INT NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	primary key(id)
);

CREATE TABLE store(
	id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
	postal_code INT NOT NULL,
	residence VARCHAR(255) NOT NULL,
	phone_number INT NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	primary key(id)
);

CREATE TABLE product(
	id INT NOT NULL AUTO_INCREMENT,
	product VARCHAR(255) NOT NULL,
	price INT NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	primary key(id)
);

CREATE TABLE Invoice(
	id INT NOT NULL AUTO_INCREMENT,
	date INT NOT NULL,
    customer_id INT NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	primary key(id),
	FOREIGN KEY(customer_id) REFERENCES customer(id)
);

CREATE TABLE InvoiceLine(
	id INT NOT NULL AUTO_INCREMENT,
    amount INT NOT NULL,
	price INT NOT NULL,
    product_id INT NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	primary key(id),
	FOREIGN KEY(product_id) REFERENCES product(id)
);

CREATE TABLE orders(
	id INT NOT NULL AUTO_INCREMENT,
    product_id INT NOT NULL,
    store_id INT NOT NULL,
	amount INT NOT NULL,
    customer_id INT NOT NULL,
    employee_id INT NOT NULL,
	picked_up INT,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	primary key(id),
    FOREIGN KEY(product_id) REFERENCES product(id),
	FOREIGN KEY(store_id) REFERENCES store(id),
    FOREIGN KEY(customer_id) REFERENCES customer(id),
    FOREIGN KEY(employee_id) REFERENCES employee(id)
);