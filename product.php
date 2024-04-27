<head>
    <title>Product Management</title>
    <link rel="stylesheet" href="/styles.css">
    <?php include('header.php')?>
    <?php include('auth.php'); ?>
</head>

<body>

    <div class="content">
        <div class="product-management">
            <h1>Product Management</h1>
            <button id="checkAllBtn" class="chckall-Btn">Check All</button>
            <button id="uncheckAllBtn" class="unchckall-Btn hidden">Uncheck All</button>
            <button id="addProductBtn" class="add-btn">Add Product</button>
            <button id="deleteSelectedBtn" class="delete-btn hidden">Delete Selected Products</button>
            <button id="importExcelBtn" class="add-btn">Import from Excel</button>
            <input type="file" id="importExcelInput" accept=".xls,.xlsx" style="display: none;" />

            <table id="productTable">
                <thead>
                    <tr>
                        <th>Product Name</th>
                    </tr>
                </thead>
                <tbody id="productList">
                </tbody>
            </table>
        </div>

        <div class="supply-management">
            <h1>Supply Management</h1>
            <button id="exportSupplyExcelBtn" class="add-btn">Export to Excel</button>
            <table id="supplyTable">
                <thead>
                    <tr>
                        <th id="productNameHeader">Product Name</th>
                        <th id="quantityHeader">Quantity</th>
                        <th id="priceHeader">Price</th>
                        <th id="supplierHeader">Supplier</th>
                        <th id="dateAddedHeader">Date Added</th>
                        <th id="expirationDateHeader">Expiration Date</th>
                    </tr>
                </thead>
                <tbody id="supplyList">
                </tbody>
            </table>
            <button id="saveButton" class="add-btn save-btn">Save</button>
        </div>

        <button id="toggleArchivedBtn" class="archieved-btn" >Show Archived Products</button>
        <div class="archived-products" id="archivedProductsTableWrapper" style="display: none;">
            <div class="archived-products">
                <h1>Archived Products</h1>
                <table id="archivedProductTable">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Supplier</th>
                            <th>Date Added</th>
                        </tr>
                    </thead>
                    <tbody id="archivedProductList">
                    </tbody>
                </table>
                <button id="restoreSelectedBtn" class="restore-Btn">Restore Selected Products</button>
            </div>
        </div>
    </div>

    <script>
        //location.reload is to reload the whole page
        window.addEventListener("load", function() {
            displayProducts();
            displayArchivedProducts();

            document.querySelectorAll("#productList input[type='checkbox']").forEach(function(checkbox) {
                checkbox.addEventListener("change", toggleButtonsVisibility);
            });

            document.getElementById("saveButton").addEventListener("click", function() {
                changesSaved = true;
            });

            var inputs = document.querySelectorAll("#supplyList td[contenteditable='true']");
            inputs.forEach(function(input) {
                input.addEventListener("input", handleInputChange);
                input.setAttribute("data-original-value", input.textContent.trim());
            });
            
            document.getElementById("toggleArchivedBtn").addEventListener("click", function() {
            var archivedProductsTableWrapper = document.getElementById("archivedProductsTableWrapper");
                if (archivedProductsTableWrapper.style.display === "none") {
                    archivedProductsTableWrapper.style.display = "block";
                    displayArchivedProducts();
                } else {
                    archivedProductsTableWrapper.style.display = "none";
                }
            });

            //this is for user click on header to arrange it
            document.getElementById("productNameHeader").addEventListener("click", function() {
                sortTable(0);
            });
            document.getElementById("quantityHeader").addEventListener("click", function() {
                sortTable(1);
            });
            document.getElementById("priceHeader").addEventListener("click", function() {
                sortTable(2);
            });
            document.getElementById("supplierHeader").addEventListener("click", function() {
                sortTable(3);
            });
            document.getElementById("dateAddedHeader").addEventListener("click", function() {
                sortTable(4);
            });
            document.getElementById("expirationDateHeader").addEventListener("click", function() {
                sortTable(5);
            });

            document.getElementById("checkAllBtn").addEventListener("click", function() {
                var checkboxes = document.querySelectorAll("#productList input[type='checkbox']");
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = true;
                });
                toggleButtonsVisibility();
                toggleDeleteButton();
            });
        
            document.getElementById("uncheckAllBtn").addEventListener("click", function() {
                var checkboxes = document.querySelectorAll("#productList input[type='checkbox']");
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
                toggleButtonsVisibility();
                toggleDeleteButton();
            });
        });
    
        var sortingOrders = [1, 1, 1, 1, 1, 1];
    
        //sorting of table
        function sortTable(columnIndex) {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById("supplyTable");
            switching = true;
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("td")[columnIndex];
                    y = rows[i + 1].getElementsByTagName("td")[columnIndex];
                    if (columnIndex === 1) {
                        var quantityX = parseInt(x.innerHTML);
                        var quantityY = parseInt(y.innerHTML);
                        if (sortingOrders[columnIndex] === 1) {
                            if (quantityX > quantityY) {
                                shouldSwitch = true;
                                break;
                            }
                        } else {
                            if (quantityX < quantityY) {
                                shouldSwitch = true;
                                break;
                            }
                        }
                    } else if (columnIndex === 4 || columnIndex === 5) {
                        var dateX = new Date(Date.parse(x.innerHTML));
                        var dateY = new Date(Date.parse(y.innerHTML));
                        if (sortingOrders[columnIndex] === 1) {
                            if (dateX > dateY) {
                                shouldSwitch = true;
                                break;
                            }
                        } else {
                            if (dateX < dateY) {
                                shouldSwitch = true;
                                break;
                            }
                        }
                    } else {
                        if (sortingOrders[columnIndex] === 1) {
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
           
            sortingOrders[columnIndex] *= -1;
            
            for (var j = 0; j < sortingOrders.length; j++) {
                if (j !== columnIndex) {
                    sortingOrders[j] = Math.abs(sortingOrders[j]);
                }
            }
        }

        displayProductsFromStorage();
        
        //to show and hide the button not needed to be shown
        function toggleButtonsVisibility() {
            var checkboxes = document.querySelectorAll("#productList input[type='checkbox']");
            var checkAllBtn = document.getElementById("checkAllBtn");
            var uncheckAllBtn = document.getElementById("uncheckAllBtn");
            var allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

            if (allChecked) {
                checkAllBtn.classList.add("hidden");
                uncheckAllBtn.classList.remove("hidden");
            } else {
                checkAllBtn.classList.remove("hidden");
                uncheckAllBtn.classList.add("hidden");
            }
        }

        //this is to show delete button if 1 or more product has check
        function toggleDeleteButton() {
            var checkboxes = document.querySelectorAll("#productList input[type='checkbox']");
            var deleteSelectedBtn = document.getElementById("deleteSelectedBtn");
            var checkedCount = Array.from(checkboxes).filter(checkbox => checkbox.checked).length;
            if (checkedCount >= 1) {
                deleteSelectedBtn.classList.remove("hidden");
            } else {
                deleteSelectedBtn.classList.add("hidden");
            }
        }
        
        //this is the process on how the user add product
        document.getElementById("addProductBtn").addEventListener("click", function() {
            var productName;
            do {
                productName = prompt("Enter product name:");
                if (productName === null) {
                    // User clicked cancel, exit the function
                    return;
                }
                if (productName.trim() === "") {
                    alert("Product Name cannot be empty.");
                }
            } while (productName.trim() === "");

            var storedProducts = JSON.parse(localStorage.getItem("products")) || [];
            var productExists = storedProducts.some(product => product.name === productName);
            if (productExists) {
                alert("Product already exists.");
                return;
            }
    
            var quantity;
            do {
                quantity = prompt("Enter quantity:");
                if (quantity === null) {
                    return;
                }
                if (quantity.trim() === "") {
                    alert("Quantity cannot be empty.");
                } else if (isNaN(quantity) || parseInt(quantity) <= 0) {
                    alert("Please enter a valid quantity.");
                }
            } while (quantity === null || quantity.trim() === "" || isNaN(quantity) || parseInt(quantity) <= 0);

    
            var price;
            do {
                price = prompt("Enter price:");
                if (price === null) {
                    return;
                }
                if (price.trim() === "") {
                    alert("Price cannot be empty.");
                } else if (isNaN(price) || parseFloat(price) <= 0) {
                    alert("Please enter a valid price.");
                }
            } while (price === null || price.trim() === "" || isNaN(price) || parseFloat(price) <= 0);


            var dateAdded = new Date().toLocaleString();
    
            var supplier = prompt("Enter supplier:");
    
            var expirationDays;
            do {
                expirationDays = prompt("Enter days before expiration: (negative values = expired)");
                if (expirationDays === null) {
                    return;
                }
                if (expirationDays.trim() === "" || isNaN(expirationDays) || parseInt(expirationDays) === 0) {
                    alert("Please enter a valid number of days.");
                }
            } while (expirationDays === null || expirationDays.trim() === "" || isNaN(expirationDays) || parseInt(expirationDays) === 0);

            var expirationDate = calculateExpirationDate(expirationDays);
            addProductToTable(productName, quantity, price, supplier, dateAdded, expirationDate);
            saveProductToStorage(productName, quantity, price, supplier, dateAdded, expirationDate);
            localStorage.setItem(productName + "_expiration", expirationDate);
            location.reload();
        });

        //calculation of expiration date
        function calculateExpirationDate(days) {
            var today = new Date();
            var expiration = new Date(today);
            expiration.setDate(expiration.getDate() + parseInt(days));
            return expiration.toLocaleDateString();
        }

        document.getElementById("productList").addEventListener("change", function() {
            toggleDeleteButton();
        });

        document.getElementById("deleteSelectedBtn").addEventListener("click", function() {
            var checkedProducts = document.querySelectorAll("#productList input[type='checkbox']:checked");
            if (checkedProducts.length >= 1) {
                if (confirm("Are you sure you want to delete the selected product(s)?")) {
                    checkedProducts.forEach(function(checkbox) {
                        var productName = checkbox.value;
                        deleteProduct(productName);
                    });
                    location.reload();
                }
            }
        });

        document.getElementById("importExcelBtn").addEventListener("click", function() {
            document.getElementById("importExcelInput").click();
        });

        document.getElementById("importExcelInput").addEventListener("change", function(event) {
            var file = event.target.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                var data = new Uint8Array(e.target.result);
                var workbook = XLSX.read(data, { type: 'array' });
                var sheet = workbook.Sheets[workbook.SheetNames[0]];
                var excelData = XLSX.utils.sheet_to_json(sheet, { header: 1 });
                // assumption if one data is empty
                for (var i = 1; i < excelData.length; i++) {
                    var productName = excelData[i][0];
                    var quantity = excelData[i][1];
                    var price = excelData[i][2];
                    var dateAdded = new Date().toLocaleString();
                    var supplier = excelData[i][3];
                    var expirationDateSerial = excelData[i][5];
                    var expirationDate = convertSerialToDate(expirationDateSerial);

                    var existingProducts = JSON.parse(localStorage.getItem("products")) || [];
                    var productExists = existingProducts.some(product => product.name === productName);
                    if (productExists) {
                        console.log("Product already exists, ignoring:", productName);
                        continue; // Skip product with same name
                    }
                    
                    addProductToTable(productName, quantity, price, supplier, dateAdded, expirationDate);
                    saveProductToStorage(productName, quantity, price, supplier, dateAdded, expirationDate);
                    localStorage.setItem(productName + "_expiration", expirationDate);
                }
            };
            reader.readAsArrayBuffer(file);
            location.reload();
        });

        function convertSerialToDate(serial) {
            var startDate = new Date("1899-12-30");
            var millisecondsPerDay = 24 * 60 * 60 * 1000;
            var offsetMilliseconds = serial * millisecondsPerDay;
            var expirationDate = new Date(startDate.getTime() + offsetMilliseconds);
            var month = expirationDate.getMonth() + 1;
            var day = expirationDate.getDate();
            var year = expirationDate.getFullYear();
            return month + "/" + day + "/" + year;
        }

        //display products in product management
        function displayProductsFromStorage() {
            var productList = JSON.parse(localStorage.getItem("products")) || [];
            productList.forEach(function(product) {
                var savedProduct = JSON.parse(localStorage.getItem(product.name)) || {};
                addProductToTable(product.name, product.quantity, product.dateAdded, savedProduct.supplier, savedProduct.price, getProductExpirationDate(product.name));
            });
        }

        //this is to add product to table
        function addProductToTable(productName, quantity, price, supplier,  dateAdded, expirationDate) {
                var productListBody = document.getElementById("productList");
                var newRow = productListBody.insertRow();
                var cell1 = newRow.insertCell(0);

                var checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.value = productName;
                checkbox.classList.add("product-checkbox");
                cell1.appendChild(checkbox);
                cell1.appendChild(document.createTextNode(productName));

            }

        //this is to save the product if has user change details
        function saveProductToStorage(productName, quantity, price, supplier, dateAdded, expirationDate) {
            var productList = JSON.parse(localStorage.getItem("products")) || [];
            productList.push({ name: productName, quantity: quantity, supplier:supplier, dateAdded: dateAdded, expirationDate: expirationDate });
            localStorage.setItem("products", JSON.stringify(productList));
            var productInfo = { price: price, supplier: supplier };
            localStorage.setItem(productName, JSON.stringify(productInfo));
        }

        //this is to delete the product
        function deleteProduct(productName) {
            var productList = JSON.parse(localStorage.getItem("products")) || [];
            var deletedProducts = JSON.parse(localStorage.getItem("deletedProducts")) || [];
            var index = productList.findIndex(function(product) {
                return product.name === productName;
            });
            if (index !== -1) {
                //moving the product to not be deleted permanently
                var deletedProduct = productList.splice(index, 1)[0];
                deletedProducts.push(deletedProduct);
                localStorage.setItem("products", JSON.stringify(productList));
                localStorage.setItem("deletedProducts", JSON.stringify(deletedProducts));
                location.reload();
            }
        }

        document.getElementById("exportSupplyExcelBtn").addEventListener("click", function() {
            exportToExcel();
        });

        //this is to export the table from excel (Supply Management Table)
        function exportToExcel() {
            var table = document.getElementById("supplyTable").cloneNode(true);
            var tbody = table.querySelector("tbody");
            var rows = Array.from(tbody.querySelectorAll("tr"));

            rows.sort((a, b) => {
                var productNameA = a.cells[0].textContent.trim().toLowerCase();
                var productNameB = b.cells[0].textContent.trim().toLowerCase();
                if (productNameA < productNameB) return -1;
                if (productNameA > productNameB) return 1;
                return 0;
            });

            tbody.innerHTML = "";
            rows.forEach(row => tbody.appendChild(row));

            var html = table.outerHTML;
            var blob = new Blob([html], { type: "application/vnd.ms-excel" });
            var url = window.URL.createObjectURL(blob);
            var a = document.createElement("a");
            a.href = url;
            a.download = "product_data.xls";
            a.click();
        }

        function getProductExpirationDate(productName) {
            return localStorage.getItem(productName + "_expiration") || "";
        }

        document.getElementById("saveButton").addEventListener("click", function() {
                confirmSaveProducts();
            });
            var inputs = document.querySelectorAll("#supplyList td[contenteditable='true']");
            inputs.forEach(function(input) {
                input.addEventListener("input", handleInputChange);
                input.setAttribute("data-original-value", input.textContent.trim());
            });

        //checker if changes has been made but not saved
        window.addEventListener("beforeunload", function(event) {
            var unsavedChanges = false;
            var inputs = document.querySelectorAll("#supplyList td[contenteditable='true']");
            inputs.forEach(function(input) {
                var originalValue = input.getAttribute("data-original-value");
                var updatedValue = input.textContent.trim();
                if (originalValue !== updatedValue) {
                    unsavedChanges = true;
                }
            });
            if (unsavedChanges && !changesSaved) {
                event.preventDefault();
                event.returnValue = '';
                var confirmation = confirm("Changes have been made. Are you sure you want to leave this page? Your changes may not be saved.");
                if (confirmation) {
                    event.returnValue = null;
                }
            }
        });

            displayProducts();

        //checker if input is correct
        function handleInputChange() {
            var inputField = this;
            var originalValue = inputField.getAttribute("data-original-value");
            var updatedValue = inputField.textContent.trim();

            if (inputField.cellIndex === 1) {
                if (isNaN(updatedValue) && updatedValue !== "") {
                    alert("Please enter a valid number for the quantity.");
                    inputField.textContent = originalValue;
                    return;
                }
            }   
            
            if (inputField.cellIndex === 2) {
                if (isNaN(updatedValue) && updatedValue !== "") {
                    alert("Please enter a valid number for the price.");
                    inputField.textContent = originalValue;
                    return;
                }
            }

            if (originalValue !== updatedValue) {
                inputField.classList.add("value-changed");
                changesSaved = false;
            } else {
                inputField.classList.remove("value-changed");
                changesSaved = true;
            }
        }    

        //display non-deleted products
        function displayProducts() {
            var supplyList = JSON.parse(localStorage.getItem("products")) || [];
            var deletedProducts = JSON.parse(localStorage.getItem("deletedProducts")) || [];
            var supplyListBody = document.getElementById("supplyList");
            supplyListBody.innerHTML = "";

            supplyList.forEach(function(product) {
                var newRow = supplyListBody.insertRow();
                newRow.innerHTML = `
                    <td>${product.name}</td>
                    <td contenteditable="true">${product.quantity}</td>
                    <td contenteditable="true">${getProductPrice(product.name)}</td>
                    <td contenteditable="true">${getProductSupplier(product.name)}</td>
                    <td>${product.dateAdded}</td>
                    <td>${getProductExpirationDate(product.name)}</td>
                `;
            });
        }

        //display deleted products
        function displayArchivedProducts() {
            var archivedProducts = JSON.parse(localStorage.getItem("deletedProducts")) || [];
            var archivedProductListBody = document.getElementById("archivedProductList");
            archivedProductListBody.innerHTML = "";

            archivedProducts.forEach(function(product) {
                var newRow = archivedProductListBody.insertRow();

                var productNameCell = newRow.insertCell();

                var checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.value = product.name;
                productNameCell.appendChild(checkbox);
                productNameCell.appendChild(document.createTextNode(product.name));

                for (var i = 2; i <= 5; i++) {
                    var cell = newRow.insertCell();
                    cell.textContent = getProductInfoByIndex(product, i);
                }
            });

            var restoreButton = document.getElementById("restoreSelectedBtn");
            restoreButton.classList.remove("hidden");
            restoreButton.addEventListener("click", restoreSelectedProducts);
        }

        //Product Info
        function getProductInfoByIndex(product, index) {
            switch (index) {
                case 1:
                    return product.name;
                case 2:
                    return product.quantity;
                case 3:
                    return getProductPrice(product.name);
                case 4:
                    return getProductSupplier(product.name);
                case 5:
                    return product.dateAdded;
                default:
                    return getProductExpirationDate(product.name);
            }
        }

        function restoreSelectedProducts() {
            var confirmed = confirm("Are you sure you want to restore the selected products?");
    
            if (!confirmed) {
                return;
            }

            var restoredProducts = [];

            var checkboxes = document.querySelectorAll("#archivedProductList input[type='checkbox']:checked");

            checkboxes.forEach(function(checkbox) {
                var productName = checkbox.value;
                var restoredProduct = findProductByName(productName);
                if (restoredProduct) {
                    restoredProducts.push(restoredProduct);
                }
            });

            var productList = JSON.parse(localStorage.getItem("products")) || [];
            productList.push(...restoredProducts);
            localStorage.setItem("products", JSON.stringify(productList));

            var archivedProducts = JSON.parse(localStorage.getItem("deletedProducts")) || [];
            var updatedArchivedProducts = archivedProducts.filter(function(product) {
                return !restoredProducts.some(function(restoredProduct) {
                    return restoredProduct.name === product.name;
                });
            });
            localStorage.setItem("deletedProducts", JSON.stringify(updatedArchivedProducts));

            console.log("Restoration successful!");
            displayArchivedProducts();
            displayProducts();;
            location.reload();
        }

        function findProductByName(productName) {
            var productList = JSON.parse(localStorage.getItem("deletedProducts")) || [];
            return productList.find(function(product) {
                return product.name === productName;
            });
        }

        function getProductSupplier(productName) {
            var productInfo = JSON.parse(localStorage.getItem(productName)) || {};
            return productInfo.supplier || "";
        }

        function getProductPrice(productName) {
            var productInfo = JSON.parse(localStorage.getItem(productName)) || {};
            return productInfo.price || "";
        }

        function confirmSaveProducts() {
            var confirmation = confirm("Are you sure they have the same expiration?");
            if (confirmation) {
                saveProducts();
            } else {
                alert("Save operation cancelled.");
            }
        }

        //saving the new product after restore
        function saveProducts() {
            var supplyList = document.getElementById("supplyList").querySelectorAll("tr");
            var updatedProducts = [];
            supplyList.forEach(function(row) {
                var productName = row.querySelector("td:first-child").textContent;
                var quantity = row.querySelector("td:nth-child(2)").textContent;
                var price = row.querySelector("td:nth-child(3)").textContent.trim();
                var supplier = row.querySelector("td:nth-child(4)").textContent;
                var dateAdded = getProductDateAdded(productName);

                var product = { 
                    name: productName, 
                    quantity: quantity, 
                    price: price,
                    dateAdded: dateAdded,
                    supplier: supplier,
                    expirationDate: getProductExpirationDate(productName)
                };

                updatedProducts.push(product);
                saveProductToStorage(productName, quantity, price, supplier, dateAdded, getProductExpirationDate(productName));
            });

            localStorage.setItem("products", JSON.stringify(updatedProducts));

            alert("Products saved successfully!");
            changesSaved = true;
            location.reload();
        }

        function getProductDateAdded(productName) {
            var productList = JSON.parse(localStorage.getItem("products")) || [];
            for (var i = 0; i < productList.length; i++) {
                if (productList[i].name === productName) {
                    return productList[i].dateAdded;
                }
            }
            return null;
        }

        function getProductExpirationDate(productName) {
            return localStorage.getItem(productName + "_expiration") || "";
        }
</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
</body>