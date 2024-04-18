<head>
    <title>Product Report</title>
    <link rel="stylesheet" href="styles.css">
    <?php include('header.php')?>
    <?php include('auth.php'); ?>
</head>

<body>

    <div class="content">
        <div class="section" id="section1">
            <h3>Expired Products</h3>
            <table id="expiredProductTable">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Expiration Date</th>
                        <th>Days Expired</th>
                    </tr>
                </thead>
                <tbody id="expiredProductList">
                </tbody>
            </table>
        </div>

        <div class="section" id="section2">
            <h3>Products Near Expiration</h3>
            <table id="productTable">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Date Added</th>
                        <th>Expiration</th>
                    </tr>
                </thead>
                <tbody id="productList">
                </tbody>
            </table>
        </div>
    </div>

    <div class="section" id="section3">
        <h3>All Products</h3>
        <table id="allProductTable">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price/piece</th>
                    <th>Expiration Date</th>
                </tr>
            </thead>
            <tbody id="allProductList">
            </tbody>
        </table>
    </div>

    <div class="expiration-window" id="expirationWindow">
        <h3>Product Expiration</h3>
        <p><strong>Product:</strong> <span id="expirationProductName"></span></p>
        <p><strong>Expiration Date:</strong> <span id="expirationDate"></span></p>
        <p><strong>Days Left:</strong> <span id="daysLeft"></span></p>
    </div>

    <div id="clock"></div>

    <script>
        window.onload = function() {
            updateTime();
            setInterval(updateTime, 1000); // Update time every second
            displayAllProducts();
            displayProductsNearExpiration();
            displayExpiredProduct();
            
            var headers = document.querySelectorAll("#allProductTable th");
            headers.forEach(function(header, index) {
                header.addEventListener("click", function() {
                    sortAllProductTable(index);
                });
            });
        };

        // Clock function
        function updateTime() {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();

            hours = hours < 10 ? '0' + hours : hours;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            var timeString = hours + ':' + minutes + ':' + seconds;

            document.getElementById('clock').textContent = timeString;
        }

        function calculateDaysLeft(expirationDate) {
            var currentDate = new Date();
            var timeDiff = expirationDate.getTime() - currentDate.getTime();
            var daysLeft = Math.ceil(timeDiff / (1000 * 3600 * 24));
            return daysLeft;
        }

        var allProductSortingOrders = [1, 1, 1, 1];

        function sortAllProductTable(columnIndex) {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById("allProductTable");
            switching = true;
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("td")[columnIndex];
                    y = rows[i + 1].getElementsByTagName("td")[columnIndex];
                    if (columnIndex === 2 || columnIndex === 3) {
                        var valueX = parseInt(x.textContent || x.innerText);
                        var valueY = parseInt(y.textContent || y.innerText);
                        if (allProductSortingOrders[columnIndex] === 1) {
                            if (valueX > valueY) {
                                shouldSwitch = true;
                                break;
                            }
                        } else {
                            if (valueX < valueY) {
                                shouldSwitch = true;
                                break;
                            }
                        }
                    } else if (columnIndex === 1) {
                        var priceX = parseInt(x.textContent || x.innerText);
                        var priceY = parseInt(y.textContent || y.innerText);
                        if (allProductSortingOrders[columnIndex] === 1) {
                            if (priceX > priceY) {
                                shouldSwitch = true;
                                break;
                            }
                        } else {
                            if (priceX < priceY) {
                                shouldSwitch = true;
                                break;
                            }
                        }
                    } else {
                        if (allProductSortingOrders[columnIndex] === 1) {
                            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                                shouldSwitch = true;
                                break;
                            }
                        } else {
                            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                                shouldSwitch = true;
                                break;
                            }
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
            allProductSortingOrders[columnIndex] *= -1;
        }

        function displayExpiredProduct() {
            var philippinesCurrentDate = new Date();
            philippinesCurrentDate.setHours(philippinesCurrentDate.getHours() + 8);

            var supplyList = JSON.parse(localStorage.getItem("products")) || [];
            var expiredProductListBody = document.getElementById("expiredProductList");
            expiredProductListBody.innerHTML = "";

            supplyList.forEach(function(product) {
                var expirationDate = localStorage.getItem(product.name + "_expiration");
                if (expirationDate) {
                    var expirationInfo = new Date(expirationDate);
                    
                    var daysExpired = Math.floor((philippinesCurrentDate.getTime() - expirationInfo.getTime()) / (1000 * 60 * 60 * 24)) - 1;
                    var expirationText = expirationInfo.toLocaleDateString();
                    if (daysExpired > 0) {
                        var newRow = expiredProductListBody.insertRow();
                        newRow.innerHTML = `
                            <td>${product.name}</td>
                            <td>${expirationText}</td>
                            <td>${daysExpired}</td>
                        `;
                    }
                }
            });
        }

        function displayProductsNearExpiration() {
            var productList = JSON.parse(localStorage.getItem("products")) || [];
            var productListBody = document.getElementById("productList");
            productListBody.innerHTML = "";

            productList.forEach(function(product) {
                var expirationDate = localStorage.getItem(product.name + "_expiration");
                if (expirationDate) {
                    var expirationInfo = new Date(expirationDate);
                    var daysLeft = calculateDaysLeft(expirationInfo);
                    var expirationText = expirationInfo ? expirationInfo.toLocaleDateString() : "Not set";
                    if (daysLeft >= 0 && daysLeft <= 30) {
                        var newRow = productListBody.insertRow();
                        newRow.innerHTML = `
                            <td>${product.name}</td>
                            <td>${product.quantity}</td>
                            <td>${product.dateAdded}</td>
                            <td>${expirationText}</td>
                        `;
                        newRow.addEventListener("click", function() {
                            showExpirationWindow(product.name, expirationInfo);
                        });
                    }
                }
            });
        }

        function getProductQuantity(productName) {
            var productInfo = JSON.parse(localStorage.getItem(productName)) || {};
            return productInfo.quantity || "";
        }

        function showExpirationWindow(productName, expirationDate) {
            var daysLeft = calculateDaysLeft(expirationDate);
            document.getElementById("expirationProductName").textContent = productName;
            document.getElementById("expirationDate").textContent = expirationDate.toLocaleDateString();
            document.getElementById("daysLeft").textContent = daysLeft + " days left";
            document.getElementById("expirationWindow").classList.add("show");

            setTimeout(function() {
                document.getElementById("expirationWindow").classList.remove("show");
            }, 7000);
        }

        function displayAllProducts() {
            var allProductList = JSON.parse(localStorage.getItem("products")) || [];
            var allProductListBody = document.getElementById("allProductList");
            allProductListBody.innerHTML = "";

            allProductList.forEach(function(product) {
                var expirationDate = localStorage.getItem(product.name + "_expiration");
                var productInfo = JSON.parse(localStorage.getItem(product.name)) || {};
                var expirationInfo = expirationDate ? new Date(expirationDate) : null;
                var expirationText = expirationInfo ? expirationInfo.toLocaleDateString() : "Not set";
                
                var newRow = allProductListBody.insertRow();
                newRow.innerHTML = `
                    <td>${product.name}</td>
                    <td>${product.quantity}</td>
                    <td>${getProductPrice(product.name)}</td>
                    <td>${expirationText}</td>
                `;
            });
        }

        function getProductPrice(productName) {
            var productInfo = JSON.parse(localStorage.getItem(productName)) || {};
            return productInfo.price || "";
        }

    </script>
</body>