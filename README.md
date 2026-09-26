# DarkStore — PHP Online Store

## Project Overview

DarkStore is a PHP/MySQL online store application developed as a course project. The application allows customers to browse products, select quantities, add products to a shopping cart, create an account, complete checkout, and view their order history.

The application is built using PHP, MySQL, HTML, and CSS and follows a lightweight Model-View-Controller (MVC) architecture.

The project was developed incrementally throughout the course, with functionality and architecture being added as new concepts were introduced.

The application uses a dark-mode storefront design intended to provide a modern technology-focused shopping experience while maintaining a clean and organized code structure.

---

## Project Goals

The primary goals of the project are to:

* Create a functional online shopping experience.
* Allow customers to browse available products.
* Display individual product information.
* Allow customers to select product quantities.
* Provide a session-based shopping cart.
* Allow customers to update cart quantities.
* Allow customers to remove products from the cart.
* Calculate cart totals dynamically.
* Provide customer registration and login.
* Securely hash customer passwords.
* Associate orders with customer accounts.
* Support guest checkout.
* Store users, products, orders, and order items in MySQL.
* Use prepared SQL statements for database operations.
* Validate user input on the server.
* Use a lightweight MVC architecture.
* Maintain existing application URLs while separating application responsibilities.
* Provide a responsive dark-mode storefront.

---

## Technology Stack

| Technology | Purpose                                          |
| ---------- | ------------------------------------------------ |
| HTML5      | Webpage structure                                |
| CSS3       | Styling, dark-mode design, and responsive layout |
| PHP        | Server-side application logic                    |
| MySQL      | Database management                              |
| PDO        | PHP/MySQL database connectivity                  |
| Git        | Version control                                  |
| GitHub     | Project repository and submission                |
| MVC        | Application architecture                         |

### JavaScript

The project does not currently require JavaScript for its core functionality.

An empty `js/script.js` file was previously included during the initial project structure, but it was removed because the current application does not depend on client-side JavaScript.

Cart updates, checkout processing, authentication, and database operations are handled by PHP.

---

## Store Design

DarkStore uses a dark-mode visual design intended to resemble a modern technology-focused online store.

### Design Characteristics

* Dark charcoal and black backgrounds
* High-contrast white text
* Subtle gray borders
* Responsive product cards
* Responsive product grid
* Dark-themed navigation bar
* Product imagery
* Featured products
* Shopping cart interface
* Checkout interface
* Account and order-history pages
* Responsive layouts for desktop, tablet, and mobile devices
* Consistent buttons and navigation elements

The primary navigation includes:

Home
Products
Cart
Checkout
My Account
Login
Register
Logout


The navigation changes based on the customer's authentication status.

---

## Application Architecture

DarkStore uses a lightweight MVC architecture.

### Model

Models are responsible for database-related operations.

Current models include:

models/
├── Product.php
├── User.php
├── Order.php
└── OrderItem.php

Responsibilities include:

* Retrieving products
* Retrieving users
* Creating user accounts
* Retrieving orders
* Creating orders
* Creating order items
* Retrieving order history
* Retrieving order details

---

### View

Views are responsible for HTML presentation.

Current views include:

views/
├── layouts/
│   ├── header.php
│   └── footer.php
│
├── home/
│   └── index.php
│
├── products/
│   ├── index.php
│   └── show.php
│
├── cart/
│   └── index.php
│
├── checkout/
│   ├── index.php
│   └── confirmation.php
│
├── auth/
│   ├── login.php
│   └── register.php
│
└── account/
    ├── index.php
    └── order.php


Views are responsible for displaying information and should not contain database queries.

---

### Controller

Controllers coordinate application requests between models and views.

Current controllers include:

controllers/
├── HomeController.php
├── ProductController.php
├── CartController.php
├── CheckoutController.php
├── OrderController.php
├── AuthController.php
└── AccountController.php

Controllers are responsible for application flow such as:

* Loading products
* Displaying product details
* Preparing cart information
* Processing checkout
* Creating orders
* Handling authentication
* Displaying account information
* Displaying order information

---

## Request Flow

The general application flow is:

Browser
   |
   v
Root PHP Entry Point
   |
   v
Controller
   |
   +----------> Model
   |               |
   |               v
   |            MySQL
   |               |
   |               v
   |            Model
   |
   v
View
   |
   v
Browser


The root PHP files preserve the application's existing URLs while forwarding requests to the appropriate controller.

For example:

products.php
     |
     v
ProductController
     |
     v
Product Model
     |
     v
products/index.php

This allows the project to use MVC organization without requiring a complex routing framework.

---

## Project Structure

The current project structure is:

final_project/

├── index.php
├── products.php
├── product.php
├── cart.php
├── checkout.php
├── confirmation.php
├── login.php
├── register.php
├── account.php
└── order.php

├── controllers/
│   ├── HomeController.php
│   ├── ProductController.php
│   ├── CartController.php
│   ├── CheckoutController.php
│   ├── OrderController.php
│   ├── AuthController.php
│   └── AccountController.php

├── models/
│   ├── Product.php
│   ├── User.php
│   ├── Order.php
│   └── OrderItem.php

├── views/
│   ├── layouts/
│   │   ├── header.php
│   │   └── footer.php
│   │
│   ├── home/
│   │   └── index.php
│   │
│   ├── products/
│   │   ├── index.php
│   │   └── show.php
│   │
│   ├── cart/
│   │   └── index.php
│   │
│   ├── checkout/
│   │   ├── index.php
│   │   └── confirmation.php
│   │
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   │
│   └── account/
│       ├── index.php
│       └── order.php

├── css/
│   └── style.css

├── images/
│   ├── laptop.jpg
│   ├── keyboard.jpg
│   ├── mouse.jpg
│   ├── monitor.jpg
│   ├── headphones.jpg
│   ├── usb-hub.jpg
│   ├── gaming-keyboard.jpg
│   ├── controller.jpg
│   ├── smartphone.jpg
│   └── speaker.jpg

├── php/
│   ├── config.php
│   └── database.php

├── api/
│   ├── add_to_cart.php
│   ├── update_cart.php
│   ├── remove_from_cart.php
│   ├── login.php
│   ├── register.php
│   └── logout.php

├── sql/
│   └── database.sql

└── README.md

The project no longer uses the previous versions.