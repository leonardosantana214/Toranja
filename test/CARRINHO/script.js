// Add the following code to your script.js file

// Get the cart items container and other necessary elements
const cartItemsContainer = document.getElementById("cart-items-container");
const cartItemsCountElement = document.getElementById("cart-items");
const cartTotalElement = document.getElementById("cart-total");

// Initialize cart variables
let cart = [];
let total = 0;

// Function to add items to the cart
function addToCart(product) {
  const existingItem = cart.find(item => item.id === product.id);

  if (existingItem) {
    // If item already exists, increase quantity
    existingItem.quantity++;
  } else {
    // If item doesn't exist, add it to the cart
    cart.push({ ...product, quantity: 1 });
  }

  // Update cart info and window
  updateCartInfo();
  updateCartWindow();
}

// Function to update cart info (item count and total)
function updateCartInfo() {
  let itemCount = 0;
  total = 0;

  cart.forEach(item => {
    itemCount += item.quantity;
    total += item.price * item.quantity;
  });

  cartItemsCountElement.textContent = itemCount;
  cartTotalElement.textContent = total.toFixed(2);
}

// Function to update the cart window
function updateCartWindow() {
  cartItemsContainer.innerHTML = "";

  cart.forEach(item => {
    const cartItemElement = document.createElement("div");
    cartItemElement.classList.add("cart-item");
    cartItemElement.innerHTML = `
      <img src="${item.img}" alt="${item.name}">
      <div class="item-details">
        <h3>${item.name}</h3>
        <p class="price">$${(item.price * item.quantity).toFixed(2)}</p>
        <p>Quantity: ${item.quantity}</p>
      </div>
      <button class="remove-item" onclick="removeFromCart(${item.id})">Remove</button>
    `;

    cartItemsContainer.appendChild(cartItemElement);
  });
}

// Function to remove items from the cart
function removeFromCart(productId) {
  cart = cart.filter(item => item.id !== productId);
  updateCartInfo();
  updateCartWindow();
}

// Initial products array (replace with actual product data)
const products = [
  { id: 1, name: "Product 1", price: 19.99, img: "product1.jpg" },
  { id: 2, name: "Product 2", price: 29.99, img: "product2.jpg" },
  { id: 3, name: "Product 3", price: 39.99, img: "product3.jpg" }
];

// Add event listeners for adding items to the cart
products.forEach(product => {
  const productElement = document.getElementById(`product${product.id}`);
  productElement.addEventListener("click", () => addToCart(product));
});
