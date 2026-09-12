# DarkStore — PHP Online Store

## Project Overview

DarkStore is a PHP-based online store application developed as a course project. The application allows users to browse products, select quantities, add products to a shopping cart, and complete the checkout process.

The project is being developed incrementally throughout the course. Each week introduces additional functionality based on the concepts covered in the course material. The final project will use PHP, MySQL, HTML, CSS, and JavaScript and will be submitted through GitHub.

The application is designed to have the look and feel of a modern online shopping website while maintaining a clean and organized code structure.

---

## Project Goals

The primary goals of the project are to:

* Create a functional online shopping experience.
* Allow users to browse available products.
* Allow users to select product quantities.
* Provide a shopping cart for selected products.
* Allow users to review and modify their cart.
* Provide a checkout process.
* Store users, products, and order information in a MySQL database.
* Use PHP for server-side application functionality.
* Use JavaScript for client-side interaction.
* Use HTML and CSS to create the storefront interface.
* Transition the application to an MVC architecture during Week 4.
* Develop the project incrementally throughout the course.

---

## Technology Stack

| Technology | Purpose                                       |
| ---------- | --------------------------------------------- |
| HTML5      | Webpage structure                             |
| CSS3       | Styling and responsive design                 |
| JavaScript | Client-side functionality                     |
| PHP        | Server-side application logic                 |
| MySQL      | Database management                           |
| Git        | Version control                               |
| GitHub     | Project repository and final submission       |
| MVC        | Application architecture introduced in Week 4 |

---

## Store Design

DarkStore uses a dark-mode visual design intended to resemble a modern technology-focused online store.

### Design Characteristics

* Dark charcoal and black background
* High-contrast white text
* Blue accent color
* Responsive product cards
* Navigation bar
* Shopping cart indicator
* Hero section
* Featured products
* Store feature section
* Footer navigation
* Mobile-responsive layout

The initial storefront includes:

```text
Home
Products
Cart
Login
Register
Checkout
```

The design will be expanded as additional functionality is implemented.

---

## Core Store Workflow

The primary shopping workflow is:

```text
Browse Products
       |
       v
Select Product
       |
       v
Select Quantity
       |
       v
Add to Cart
       |
       v
Review Cart
       |
       +----> Update Quantity
       |
       +----> Remove Item
       |
       v
Checkout
       |
       v
Create Order
       |
       v
Order Confirmation
```

---

## Database Design

The application uses MySQL to store persistent application data.

The initial database consists of four primary tables:

```text
users
  |
  | 1-to-many
  v
orders
  |
  | 1-to-many
  v
order_items
  ^
  |
  | many-to-one
  |
products
```

### Users

Stores customer account information.

Suggested columns:

```text
user_id
first_name
last_name
email
password_hash
created_at
```

`user_id` is the primary key.

---

### Products

Stores products available for purchase.

Suggested columns:

```text
product_id
product_name
description
price
quantity_available
image
created_at
```

`product_id` is the primary key.

---

### Orders

Stores completed customer orders.

Suggested columns:

```text
order_id
user_id
order_date
total
```

`order_id` is the primary key.

`user_id` is a foreign key referencing `users.user_id`.

---

### Order Items

Stores the individual products contained within an order.

Suggested columns:

```text
order_item_id
order_id
product_id
quantity
price
```

`order_item_id` is the primary key.

`order_id` is a foreign key referencing `orders.order_id`.

`product_id` is a foreign key referencing `products.product_id`.

The `price` field records the product price at the time of purchase so that historical orders remain accurate if the product's price changes later.

---

## Project Structure

The initial project structure is organized as follows:

```text
OnlineStore/
│
├── index.php
│
├── products.php
├── product.php
├── cart.php
├── checkout.php
├── confirmation.php
├── login.php
└── register.php
│
├── css/
│   └── style.css
│
├── js/
│   └── script.js
│
├── images/
│   └── product images
│
├── php/
│   ├── config.php
│   ├── database.php
│   ├── functions.php
│   ├── cart_functions.php
│   └── checkout_functions.php
│
├── api/
│   ├── add_to_cart.php
│   ├── update_cart.php
│   ├── remove_from_cart.php
│   └── checkout.php
│
├── sql/
│   └── database.sql
│
└── README.md
```

This structure is intended for the initial development stages. The project will be reorganized when MVC architecture is introduced.

---

## Application Architecture

Before MVC is introduced, the application will separate the major responsibilities of the project:

### Front End

HTML, CSS, and JavaScript will handle:

* Page structure
* Visual presentation
* Product display
* User interaction
* Form validation
* Cart interaction

### PHP

PHP will handle:

* Server-side processing
* Database communication
* User authentication
* Product retrieval
* Cart processing
* Checkout processing
* Order creation

### MySQL

MySQL will handle persistent data including:

* User accounts
* Products
* Orders
* Order items

The browser will not connect directly to MySQL.

The general request flow is:

```text
Browser
   |
   v
HTML / JavaScript
   |
   v
PHP
   |
   v
MySQL
   |
   v
PHP
   |
   v
Browser
```

---

## Shopping Cart

The active shopping cart will initially be handled using PHP session data.

Conceptually:

```text
$_SESSION['cart']
```

The cart will contain the products and quantities selected by the current customer.

At checkout, the application will:

1. Validate the cart.
2. Validate product quantities.
3. Calculate the order total.
4. Create an order record.
5. Create order item records.
6. Complete the checkout process.
7. Clear the shopping cart.

---

## Security Considerations

Security will be considered throughout development.

Planned security practices include:

* Password hashing using PHP password functions.
* Prepared SQL statements.
* Input validation.
* Server-side validation.
* Client-side validation where appropriate.
* Session management.
* Avoiding plaintext password storage.
* Protecting database credentials.
* Validating product quantities before checkout.
* Preventing invalid database relationships through foreign keys.

User passwords will be stored as password hashes rather than plaintext passwords.

---

## Development Roadmap

### Initial Development

* [x] Establish project structure
* [x] Design initial dark-mode storefront
* [x] Design MySQL database schema
* [x] Create MySQL database
* [ ] Create product data
* [ ] Connect PHP application to MySQL
* [ ] Display products dynamically

### Shopping Cart

* [ ] Create product selection
* [ ] Add products to cart
* [ ] Display cart contents
* [ ] Update quantities
* [ ] Remove products
* [ ] Calculate cart totals

### User Accounts

* [ ] Create registration page
* [ ] Create login page
* [ ] Implement password hashing
* [ ] Implement user sessions
* [ ] Associate orders with users

### Checkout

* [ ] Create checkout page
* [ ] Review order
* [ ] Calculate final total
* [ ] Create order
* [ ] Create order items
* [ ] Display confirmation

### MVC — Week 4

The project will be reorganized into an MVC architecture as MVC concepts are introduced in the course.

The planned MVC structure is:

```text
OnlineStore/
│
├── public/
│   ├── index.php
│   ├── css/
│   ├── js/
│   └── images/
│
├── app/
│   ├── controllers/
│   │   ├── ProductController.php
│   │   ├── CartController.php
│   │   └── CheckoutController.php
│   │
│   ├── models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Order.php
│   │   └── OrderItem.php
│   │
│   └── views/
│       ├── products/
│       ├── cart/
│       ├── checkout/
│       └── account/
│
├── config/
│   └── database.php
│
└── sql/
    └── database.sql
```

The MVC structure will be developed when the course reaches that portion of the project.

---

## Future Improvements

Potential future enhancements include:

* Product categories
* Product search
* Product filtering
* Inventory management
* Order history
* User account management
* Shipping information
* Order status tracking
* Improved mobile navigation
* Product reviews
* Additional storefront features

These features will only be added if they fit the course requirements and project scope.

---

## Author

**Michael Baird**

PHP Web Application Course Project

---

## Project Status

**Status:** In Development

The application is being developed incrementally throughout the course. Features and architecture will be added as new course concepts are introduced.
