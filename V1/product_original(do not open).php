<head>
    <title>Product Management</title>
    <link rel="stylesheet" href="styles.css">
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
            <button id="importExcelBtn" class="import-btn">Import from Excel</button>
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
            <button id="exportSupplyExcelBtn" class="export-btn">Export to Excel</button>
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
    </div>

    <script>
        window.addEventListener("load", function() {
            displayProducts();

            document.querySelectorAll("#productList input[type='checkbox']").forEach(function(checkbox) {
                checkbox.addEventListener("change", toggleButtonsVisibility);
            });

            document.getElementById("saveButton").addEventListener("click", function() {
                confirmSaveProducts();
            });

            var inputs = document.querySelectorAll("#supplyList td[contenteditable='true']");
            inputs.forEach(function(input) {
                input.addEventListener("input", handleInputChange);
                input.setAttribute("data-original-value", input.textContent.trim());
            });
    
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
    
        document.getElementById("addProductBtn").addEventListener("click", function() {
            var productName = prompt("Enter product name:");
            if (productName === null || productName.trim() === "") {
                alert("Product Name cannot be empty.");
                return;
            }

            var storedProducts = JSON.parse(localStorage.getItem("products")) || [];
            var productExists = storedProducts.some(product => product.name === productName);
            if (productExists) {
                alert("Product already exists.");
                return;
            }
    
            var quantity = prompt("Enter quantity:");
            if (quantity === null) {
                alert("Quantity cannot be empty.");
                return;
            }
            if (quantity.trim() === "" || isNaN(quantity) || parseInt(quantity) <= 0) {
                alert("Please enter a valid quantity.");
                return;
            }
    
            var price = prompt("Enter price:");
            if (price === null) {
                alert("Price cannot be empty");
            }
            if (price.trim() === "" || isNaN(price) || parseInt(price) <= 0) {
                alert("Please enter a valid Price.");
            }

            var dateAdded = new Date().toLocaleString();
    
            var supplier = prompt("Enter supplier:");
    
            var expirationDays = prompt("Enter days before expiration:");
            if (expirationDays === null || expirationDays.trim() === "" || isNaN(expirationDays) || parseInt(expirationDays) <= 0) {
                alert("Please enter a valid number of days.");      
            }
            var expirationDate = calculateExpirationDate(expirationDays);
            addProductToTable(productName, quantity, price, supplier, dateAdded, expirationDate);
            saveProductToStorage(productName, quantity, price, supplier, dateAdded, expirationDate);
            localStorage.setItem(productName + "_expiration", expirationDate);
            location.reload();
        });

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
            // assumption ng laman kapag ala undefined
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
                    continue; // Skip adding this product
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

    function displayProductsFromStorage() {
        var productList = JSON.parse(localStorage.getItem("products")) || [];
        productList.forEach(function(product) {
            var savedProduct = JSON.parse(localStorage.getItem(product.name)) || {};
            addProductToTable(product.name, product.quantity, product.dateAdded, savedProduct.supplier, savedProduct.price, getProductExpirationDate(product.name));
        });
    }

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

    function saveProductToStorage(productName, quantity, price, supplier, dateAdded, expirationDate) {
        var productList = JSON.parse(localStorage.getItem("products")) || [];
        productList.push({ name: productName, quantity: quantity, supplier:supplier, dateAdded: dateAdded, expirationDate: expirationDate });
        localStorage.setItem("products", JSON.stringify(productList));
        var productInfo = { price: price, supplier: supplier };
        localStorage.setItem(productName, JSON.stringify(productInfo));
    }

    function deleteProduct(productName) {
        var productList = JSON.parse(localStorage.getItem("products")) || [];
        var index = productList.findIndex(function(product) {
            return product.name === productName;
        });
        if (index !== -1) {
            productList.splice(index, 1);
            localStorage.setItem("products", JSON.stringify(productList));
            displayProductsFromStorage();
        }
    }

    document.getElementById("exportSupplyExcelBtn").addEventListener("click", function() {
        exportToExcel();
    });

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

    window.addEventListener("load", function() {
        displayProducts();
        document.getElementById("saveButton").addEventListener("click", function() {
            confirmSaveProducts();
        });
        var inputs = document.querySelectorAll("#supplyList td[contenteditable='true']");
        inputs.forEach(function(input) {
            input.addEventListener("input", handleInputChange);
            input.setAttribute("data-original-value", input.textContent.trim());
        });
    });

    function handleInputChange() {
        var inputField = this;
        var originalValue = inputField.getAttribute("data-original-value");
        var updatedValue = inputField.textContent.trim();

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

    function displayProducts() {
        var supplyList = JSON.parse(localStorage.getItem("products")) || [];
        var supplyListBody = document.getElementById("supplyList");
        supplyListBody.innerHTML = "";

        supplyList.forEach(function(product) {
            var newRow = supplyListBody.insertRow();
            newRow.innerHTML = `
                <td contenteditable="true">${product.name}</td>
                <td contenteditable="true">${product.quantity}</td>
                <td contenteditable="true">${getProductPrice(product.name)}</td>
                <td contenteditable="true">${getProductSupplier(product.name)}</td>
                <td>${getProductDateAdded(product.name)}</td>
                <td>${getProductExpirationDate(product.name)}</td>
            `;
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