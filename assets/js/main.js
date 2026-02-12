let currentSlide = 0;
let products = [];
let sellers = [];
let cartItems = JSON.parse(localStorage.getItem('cartItems') || '[]');

// Initialize the application
document.addEventListener('DOMContentLoaded', function () {
    initializeApp();
});

function initializeApp() {
    loadProducts();
    loadSellers();
    setupHeroSlider();
    setupFilters();
    setupPriceRange();
    updateCartCount();
}



// Product Functions
function loadProducts() {
    fetch('backend/get_products.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                products = data.products;
                renderProducts(products);
            } else {
                console.error('Error loading products:', data.error);
                // Fallback to sample data
                loadSampleProducts();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Fallback to sample data
            loadSampleProducts();
        });
}

function loadSampleProducts() {
    // Sample product data as fallback
    products = [
        {
            id: 1,
            title: "EA FC 25",
            platform: "xbox",
            region: "Global",
            original_price: 69.99,
            price: 34.99,
            discount: 50,
            rating: 4.1,
            seller_name: "Sports Central",
            seller_verified: false,
            image: "images/EA FC 25.png",
            description: "The World's Game - EA FC 25 Ultimate Edition"
        },
        {
            id: 2,
            title: "God of War",
            platform: "playstation",
            region: "Global",
            original_price: 59.99,
            price: 21.93,
            discount: 75,
            rating: 4.2,
            seller_name: "GameHub Store",
            seller_verified: false,
            image: "images/god of war.jpg",
            description: "God of War is a popular game loved by players worldwide."
        },
        {
            id: 3,
            title: "Cyberpunk 2077",
            platform: "steam",
            region: "Global",
            original_price: 59.99,
            price: 14.99,
            discount: 75,
            rating: 4.2,
            seller_name: "GameHub Store",
            seller_verified: true,
            image: "images/Cyberpunk 2077.png",
            description: "Open-world action-adventure story set in Night City"
        },
        {
            id: 4,
            title: "The Witcher 3: Wild Hunt",
            platform: "playstation",
            region: "Global",
            original_price: 59.99,
            price: 21.93,
            discount: 75,
            rating: 4.2,
            seller_name: "GameHub Store",
            seller_verified: false,
            image: "images/witcher3.jpg",
            description: "The Witcher 3: Wild Hunt is a popular game loved by players worldwide."
        },
        {
            id: 5,
            title: "Red Dead Redemption 2",
            platform: "playstation",
            region: "NA",
            original_price: 39.99,
            price: 19.99,
            discount: 50,
            rating: 4.6,
            seller_name: "Game Central",
            seller_verified: true,
            image: "images/RDR2.png",
            description: "Epic tale of life in America's unforgiving heartland",
            category: "action"
        },
        {
            id: 6,
            title: "Grand Theft Auto V",
            platform: "playstation",
            region: "Global",
            original_price: 29.99,
            price: 8.99,
            discount: 70,
            rating: 4.5,
            seller_name: "Key Masters",
            seller_verified: true,
            image: "images/GTA5.png",
            description: "Open world crime action adventure game",
            category: "action"
        },
        {
            id: 7,
            title: "ARK: Survival Evolved",
            platform: "steam",
            region: "Global",
            original_price: 49.99,
            price: 14.99,
            discount: 70,
            rating: 4.3,
            seller_name: "Studio Wildcard",
            seller_verified: true,
            image: "images/ark10.jpg",
            description: "Stranded on an island full of dinosaurs, you must survive and thrive.",
            category: "survival"
        },
        {
            id: 8,
            title: "Rust",
            platform: "steam",
            region: "Global",
            original_price: 39.99,
            price: 19.99,
            discount: 50,
            rating: 4.1,
            seller_name: "Facepunch Studios",
            seller_verified: true,
            image: "images/rust.jpg",
            description: "The only aim in Rust is to survive in a hostile world.",
            category: "survival"
        },
        {
            id: 9,
            title: "Raft",
            platform: "nintendo",
            region: "Global",
            original_price: 19.99,
            price: 15.99,
            discount: 20,
            rating: 4.5,
            seller_name: "Redbeet Interactive",
            seller_verified: true,
            image: "images/raft.jpg",
            description: "Survive on a raft in the middle of the ocean.",
            category: "survival"
        },
        {
            id: 10,
            title: "Fortnite",
            platform: "epic",
            region: "Global",
            original_price: 0.00,
            price: 0.00,
            discount: 0,
            rating: 4.2,
            seller_name: "Epic Games",
            seller_verified: true,
            image: "images/fortnite1.jpg",
            description: "The world's most popular Battle Royale game.",
            category: "battle-royale"
        },
        {
            id: 11,
            title: "The Forest",
            platform: "steam",
            region: "Global",
            original_price: 19.99,
            price: 9.99,
            discount: 50,
            rating: 4.6,
            seller_name: "Endnight Games Ltd",
            seller_verified: true,
            image: "images/theforest.jpg",
            description: "Survive in a cannibalistic peninsula after a plane crash.",
            category: "survival"
        },
        {
            id: 12,
            title: "Euro Truck Simulator 2",
            platform: "steam",
            region: "Global",
            original_price: 19.99,
            price: 4.99,
            discount: 75,
            rating: 4.8,
            seller_name: "SCS Software",
            seller_verified: true,
            image: "images/etc.jpg",
            description: "Drive across Europe delivering cargo in authentic trucks.",
            category: "simulation"
        },
        {
            id: 13,
            title: "American Truck Simulator",
            platform: "Nintendo",
            region: "Global",
            original_price: 19.99,
            price: 4.99,
            discount: 75,
            rating: 4.7,
            seller_name: "SCS Software",
            seller_verified: true,
            image: "images/atc.jpg",
            description: "Experience legendary American trucks and deliver cargo across the USA.",
            category: "simulation"
        },
        {
            id: 14,
            title: "SnowRunner",
            platform: "xbox",
            region: "Global",
            original_price: 49.99,
            price: 24.99,
            discount: 50,
            rating: 4.4,
            seller_name: "Saber Interactive",
            seller_verified: true,
            image: "images/snow.jpg",
            description: "Overcome extreme terrain with powerful vehicles.",
            category: "simulation"
        },
        {
            id: 15,
            title: "MudRunner",
            platform: "xbox",
            region: "Global",
            original_price: 24.99,
            price: 6.24,
            discount: 75,
            rating: 4.2,
            seller_name: "Saber Interactive",
            seller_verified: true,
            image: "images/mud.jpg",
            description: "Ultimate off-road experience with realistic mud physics.",
            category: "simulation"
        },
        {
            id: 16,
            title: "Counter-Strike: Global Offensive",
            platform: "steam",
            region: "Global",
            original_price: 0.00,
            price: 0.00,
            discount: 0,
            rating: 4.5,
            seller_name: "Valve",
            seller_verified: true,
            image: "images/cs.jpg",
            description: "The world's most popular tactical FPS game.",
            category: "fps"
        },
        {
            id: 17,
            title: "Sons of The Forest",
            platform: "steam",
            region: "Global",
            original_price: 29.99,
            price: 29.99,
            discount: 0,
            rating: 4.1,
            seller_name: "Endnight Games Ltd",
            seller_verified: true,
            image: "images/son.jpg",
            description: "Survive in a cannibalistic island in this sequel to The Forest.",
            category: "survival"
        },
        {
            id: 18,
            title: "Mortal Kombat 11",
            platform: "playstation",
            region: "Global",
            original_price: 49.99,
            price: 12.49,
            discount: 75,
            rating: 4.3,
            seller_name: "NetherRealm Studios",
            seller_verified: true,
            image: "images/11.jpg",
            description: "The legendary fighting game franchise returns with brutal combat."
        },
        {
            id: 19,
            title: "V-Bucks Gift Card",
            platform: "Epic Games",
            region: "Global",
            original_price: 25.00,
            price: 21.99,
            discount: 12,
            rating: 4.8,
            seller_name: "Epic Games",
            seller_verified: true,
            image: "images/vbucks.jpg",
            description: "Use V-Bucks in Fortnite to buy Battle Passes, outfits, emotes and more.",
            category: "gift-card"
        },
        {
            id: 20,
            title: "Robux Gift Card",
            platform: "Roblox",
            region: "Global",
            original_price: 20.00,
            price: 18.49,
            discount: 8,
            rating: 4.7,
            seller_name: "Roblox Corporation",
            seller_verified: true,
            image: "images/robux.jpg",
            description: "Redeem this gift card to get Robux and customize your Roblox experience.",
            category: "gift-card"
        },
        {
            id: 21,
            title: "League of Legends Riot Points Gift Card",
            platform: "Riot Games",
            region: "Global",
            original_price: 25.00,
            price: 22.49,
            discount: 10,
            rating: 4.6,
            seller_name: "Riot Games",
            seller_verified: true,
            image: "images/riot.jpg",
            description: "Use Riot Points to unlock skins, champions, and more in League of Legends.",
            category: "gift-card"
        },
        {
            id: 22,
            title: "Brawl Stars Gems Gift Card",
            platform: "Supercell",
            region: "Global",
            original_price: 15.00,
            price: 13.49,
            discount: 10,
            rating: 4.5,
            seller_name: "Supercell",
            seller_verified: true,
            image: "images/brawlstars.jpg",
            description: "Buy gems to unlock brawlers, skins, and in-game rewards in Brawl Stars.",
            category: "gift-card"
        },
        {
            id: 23,
            title: "FC 25 FIFA Points Gift Card",
            platform: "EA Sports",
            region: "Global",
            original_price: 20.00,
            price: 17.99,
            discount: 10,
            rating: 4.4,
            seller_name: "Electronic Arts",
            seller_verified: true,
            image: "images/fifa.jpg",
            description: "Use FIFA Points to purchase packs and build your Ultimate Team in FC 25.",
            category: "gift-card"
        },
        {
            id: 24,
            title: "Microsoft Office 365 Subscription",
            platform: "Microsoft",
            region: "Global",
            original_price: 69.99,
            price: 49.99,
            discount: 29,
            rating: 4.9,
            seller_name: "Microsoft Corporation",
            seller_verified: true,
            image: "images/office365.jpg",
            description: "Access Word, Excel, PowerPoint, Outlook and more with a 1-year Office 365 subscription.",
            category: "software"
        },
        {
            id: 25,
            title: "Windows 11 Pro License Key",
            platform: "Microsoft",
            region: "Global",
            original_price: 139.00,
            price: 24.99,
            discount: 82,
            rating: 4.8,
            seller_name: "Microsoft Authorized",
            seller_verified: true,
            image: "images/windows11.jpg",
            description: "Genuine license key for Windows 11 Pro. Activate your PC instantly and securely.",
            category: "software"
        },
        {
            id: 26,
            title: "GTA 6",
            platform: "playstation",
            region: "Global",
            original_price: 69.99,
            price: 69.99,
            discount: 0,
            rating: 0,
            seller_name: "Rockstar Games",
            seller_verified: true,
            image: "images/gta6.png",
            description: "The next evolution in open-world crime epics.",
            category: "upcoming"
        },
        {
            id: 27,
            title: "REMATCH",
            platform: "steam",
            region: "Global",
            original_price: 39.99,
            price: 39.99,
            discount: 0,
            rating: 0,
            seller_name: "IndieForge",
            seller_verified: true,
            image: "images/rematch.png",
            description: "A stylish, high-stakes fighting game with deep lore.",
            category: "upcoming"
        },
        {
            id: 28,
            title: "Dune: Awakening",
            platform: "steam",
            region: "Global",
            original_price: 59.99,
            price: 59.99,
            discount: 0,
            rating: 0,
            seller_name: "Funcom",
            seller_verified: true,
            image: "images/dune.png",
            description: "Survive and dominate the unforgiving world of Arrakis.",
            category: "upcoming"
        }


    ];

    renderProducts(products);
}

function renderProducts(productsToRender) {
    const productGrid = document.getElementById('productGrid');
    productGrid.innerHTML = '';

    productsToRender.forEach(product => {
        const productCard = createProductCard(product);
        productGrid.appendChild(productCard);
    });
}

function createProductCard(product) {
    const card = document.createElement('div');
    card.className = 'product-card';
    card.innerHTML = `
        <div class="product-image" style="background-image: url('${product.image}')">
            <div class="product-discount">-${product.discount}%</div>
            <div class="product-platform">${product.platform}</div>
            <div class="product-overlay">
                <button class="btn btn-primary" onclick="quickView(${product.id})">Quick View</button>
            </div>
        </div>
        <div class="product-info">
            <h3 class="product-title">${product.title}</h3>
            <p class="product-description">${product.description}</p>
            <div class="product-meta">
                <span class="product-region">${product.region}</span>
                <div class="product-rating">
                    <span class="star">★</span>
                    <span>${product.rating}</span>
                </div>
            </div>
            <div class="product-seller">
                <span>${product.seller_name}</span>
                ${product.seller_verified ? '<span class="verified-badge">DNA Verified</span>' : ''}
            </div>
            <div class="product-pricing">
                <div>
                    <span class="original-price">$${product.original_price}</span>
                    <span class="current-price">$${product.price}</span>
                </div>
            </div>
            <div class="product-actions">
                <button class="btn btn-primary" onclick="addToCart(${product.id})">Add to Cart</button>
                <button class="btn btn-secondary" onclick="buyNow(${product.id})">Buy Now</button>
            </div>
        </div>
    `;
    return card;
}





// Enhanced filterByPlatform function
function filterByPlatform(platform) {
    console.log('Filtering by platform:', platform);

    // Remove active class from all platform buttons
    document.querySelectorAll('.platform-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    // Add active class to clicked button
    const clickedButton = document.querySelector(`[data-platform="${platform}"]`);
    if (clickedButton) {
        clickedButton.classList.add('active');
    }

    // Update category title
    const categoryTitle = document.getElementById('categoryTitle');
    if (categoryTitle) {
        const titles = {
            'all': 'All Games',
            'pc': 'PC Games',
            'xbox': 'Xbox Games',
            'playstation': 'PlayStation Games',
            'nintendo': 'Nintendo Games'
        };
        categoryTitle.textContent = titles[platform] || 'Games';
    }

    // Filter products by platform
    let filteredProducts = [];

    if (platform === 'all') {
        filteredProducts = [...products];
    } else if (platform === 'pc') {
        filteredProducts = products.filter(product => {
            const p = product.platform.toLowerCase();
            return p === 'steam' || p === 'origin' || p === 'epic';
        });
    } else {
        filteredProducts = products.filter(product => {
            return product.platform.toLowerCase() === platform;
        });
    }

    renderProducts(filteredProducts);
}


// Enhanced filterByCategory function
function filterByCategory(category) {
    console.log('Filtering by category:', category);

    // Remove active class from all category buttons
    document.querySelectorAll('.category-item-new').forEach(btn => {
        btn.classList.remove('active');
    });

    // Add active class to clicked button
    const clickedButton = document.querySelector(`[data-category="${category}"]`);
    if (clickedButton) {
        clickedButton.classList.add('active');
    }

    // Update category title
    const categoryTitle = document.getElementById('categoryTitle');
    if (categoryTitle) {
        const titles = {
            'bestsellers': 'Bestselling Games',
            'upcoming': 'Upcoming Games',
            'software': 'Software',
            'gift-cards': 'Gaming Gift Cards'
        };
        categoryTitle.textContent = titles[category] || 'All Products';
    }

    // Filter products based on category
    let filteredProducts = [];

    if (category === 'bestsellers') {
        filteredProducts = products.filter(product => product.rating >= 4.5);
    } else if (category === 'upcoming') {
        filteredProducts = products.filter(product => product.category === 'upcoming');
    } else if (category === 'software') {
        filteredProducts = products.filter(product => product.category === 'software');
    } else if (category === 'gift-cards') {
        filteredProducts = products.filter(product => product.category === 'gift-card');
    } else {
        filteredProducts = [...products]; // fallback for "all" or unknown
    }

    renderProducts(filteredProducts);
}


function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    if (product) {
        const existingItem = cartItems.find(item => item.id === productId);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cartItems.push({
                ...product,
                quantity: 1
            });
        }

        localStorage.setItem('cartItems', JSON.stringify(cartItems));
        updateCartCount();
        alert('Product added to cart!');
    }
}

function buyNow(productId) {
    console.log('Buying now:', productId);
    window.location.href = `game-details.html?id=${productId}`;
}

function quickView(productId) {
    console.log('Quick view:', productId);
    window.location.href = `game-details.html?id=${productId}`;
}

// Navigation Functions
function openCart() {
    console.log('Opening cart');
    window.location.href = 'cart.html';
}

function openUserMenu() {
    console.log('Opening user menu');
    window.location.href = 'login.php';
}


function shopNow() {
    console.log('Shop now clicked');
    document.getElementById('productGrid').scrollIntoView({ behavior: 'smooth' });
}

function updateCartCount() {
    const cartCount = cartItems.reduce((total, item) => total + item.quantity, 0);
    document.getElementById('cartCount').textContent = cartCount;
}

// Search functionality
document.getElementById('searchInput')?.addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        const searchTerm = this.value;
        console.log('Searching for:', searchTerm);

        const filteredProducts = products.filter(product =>
            product.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
            product.description.toLowerCase().includes(searchTerm.toLowerCase())
        );

        renderProducts(filteredProducts);
    }
});