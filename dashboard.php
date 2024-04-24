
<head>
    <title>Ellavill Baking Supplies and Packaging Needs</title>
    <link rel="stylesheet" href="styles.css">
    <?php include('header.php')?>
    <?php include('auth.php'); ?>
</head>
<body class="no-scroll">
    
    <div class="background">
        <p style="margin-top: 10px;">WE SELL AFFORDABLE</p><br>
        <p style="margin-left: 315px;">AND</p><br>
        <p style="margin-left: 143px;">100% GOOD QUALITY</p><br>
        <p style="margin-left: 177px;">BAKING SUPPLIES</p>
    </div>

    <div class="design">
        <div class="pin-container">
            <img src="images/pin.png">
        </div>
        <div class="sugar-container">
            <img src="images/sugar.png">
        </div>
        <div class="spoon-container">
            <img src="images/spoon.png">
        </div>
        <div class="egg-container">
            <img src="images/egg.png">
        </div>
        <div class="spatula-container">
            <img src="images/spatula.png">
        </div>
        <div class="whisk-container">
            <img src="images/whisk.png">
        </div>
    </div>

    <div class="expiration-window" id="expirationWindow">
        <h3>Product Expiration</h3>
        <table id="productTable">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Days Left</th>
                </tr>
            </thead>
            <tbody id="productList">
            </tbody>
        </table>
    </div>

    <script>
        function checkEasterEgg() {
            if (window.location.pathname.includes("dashboard.php")) {
                var reloadCount = parseInt(localStorage.getItem('reloadCount')) || 0;
                reloadCount++;

                localStorage.setItem('reloadCount', reloadCount);

                if (reloadCount >= 100) {
                    localStorage.removeItem('reloadCount');

                    window.location.href = 'Egg.html';
                }
            }
        }

        function hasProductsNearExpiration(productList) {
            var currentDate = new Date();
            for (var i = 0; i < productList.length; i++) {
                var expirationDate = localStorage.getItem(productList[i].name + "_expiration");
                if (expirationDate) {
                    var expirationInfo = new Date(expirationDate);
                    var daysLeft = Math.ceil((expirationInfo - currentDate) / (1000 * 60 * 60 * 24));
                    if (daysLeft >= 0 && daysLeft <= 7) {
                        return true;
                    }
                }
            }
            return false;
        }

        function displayExpirationPopup() {
            var productList = JSON.parse(localStorage.getItem("products")) || [];
            var productListBody = document.getElementById("productList");
            productListBody.innerHTML = "";
            var currentDate = new Date();
            var productsNearExpiration = [];

            productList.forEach(function(product) {
                var expirationDate = localStorage.getItem(product.name + "_expiration");
                if (expirationDate) {
                    var expirationInfo = new Date(expirationDate);
                    var daysLeft = Math.ceil((expirationInfo - currentDate) / (1000 * 60 * 60 * 24));
                    if (daysLeft >= 0 && daysLeft <= 30) {
                        productsNearExpiration.push({ name: product.name, daysLeft: daysLeft });
                    }
                }
            });

            productsNearExpiration.sort(function(a, b) {
                return a.daysLeft - b.daysLeft;
            });

            productsNearExpiration.forEach(function(product) {
                var newRow = productListBody.insertRow();
                newRow.innerHTML = `
                    <td>${product.name}</td>
                    <td>${product.daysLeft} days</td>
                `;
            });

            if (productsNearExpiration.length > 0) {
                setTimeout(function() {
                    document.getElementById("expirationWindow").classList.add("show");
                }, 1500);

                setTimeout(function() {
                    document.getElementById("expirationWindow").classList.remove("show");
                }, 6500);
            }
        }


        window.onload = function() {
            checkEasterEgg();
            displayExpirationPopup();
        };
    </script>
</body>