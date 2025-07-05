    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Purchase') }}
            </h2>
        </x-slot>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <form method="get" action="{{route('searchvendor')}}">
                            @csrf
                            <label for="phone">Phone Number:</label><br>
                            <input type="text" id="phone" name="phone" oninput="test(this)" value=""><br>

                            <label for="vendor_name">Vendor Name:</label><br>
                            <input type="text" id="vendor_name" name="vendor_name" value=""><br>

                            <label for="email">Email Address:</label><br>
                            <input type="text" id="email" name="email" value=""><br>

                            <label for="address">Address</label><br>
                            <input type="text" id="address" name="address" value=""><br>
                        </form>

                        <br></br>

                        <div class="mb-3">
                            <label for="products" class="form-label">Product</label>

                            <br></br>
                            <td>
                                <input type="text" name="src_product" id="src_product" oninput="search(this)" class="form-control unit-price" step="0.01" style="width: 120px;" required>
                                <div class="userlist" id="userlist"></div>
                            </td>

                        </div>
                        <div class="selProduct" id="selProduct"></div>
                    </div>
                </div>
            </div>
        </div>


        <script>
            function test(e) {
                if (e.value) {
                    $.ajax({
                        url: "/searchvendor",
                        type: "GET",
                        data: {
                            id: e.value
                        },
                        success: function(response) {
                            console.log(response.vendor_name);
                            $('#vendor_name').val(response.vendor_name);
                            $('#email').val(response.email);
                            $('#address').val(response.address);
                        },
                    });
                }

            }
        </script>

        <!-- For Product Search-->
        <script>
            function search(e) {
                if (e.value) {
                    $.ajax({
                        url: "/searchproduct",
                        type: "GET",
                        data: {
                            id: e.value
                        },
                        success: function(response) {
                            var html = "<ul class='dropdown-menu show'>";
                            $.each(response, function(key, value) {
                                html += "<li><a href='#' class='dropdown-item' data-model='" + value.model + "' data-description='" + value.description + "'>" + value.model + "-" + value.description + "</a></li>"
                            });
                            html += "</ul>";
                            $("#userlist").html(html);
                        },
                    });
                }
            }

            // Event delegation for dynamically created elements            
            let count = 0;
            $(document).on('click', '.dropdown-item', function(e) {
                count++;
                e.preventDefault();

                let name = $(this).data('model');
                let description = $(this).data('description');
                let price = $(this).data('unit_price');
                let productId = $(this).data('id');

                let staticHTML = `
                <div class="product-row mb-3 p-2 border rounded">
                    <form id="productForm" method="POST" action="">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered" id="productTable">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Item</th>
                                        <th>Unit Price</th>
                                        <th>Qty.</th>
                                        <th>Total Price</th>
                                        <th>PSN</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>`;

                let newRow = `
                <tr>
                    <td>${count}</td>
                    <td>
                        <input type="text" name="products[${count}][model]" class="form-control item-name" style="width: 400px;" required>
                    </td>
                    <td>
                        <input type="number" name="products[${count}][unit_price]" class="form-control unit-price" step="0.01" style="width: 120px;" required>
                    </td>
                    <td>
                        <input type="number" name="products[${count}][quantity]" class="form-control quantity" min="1" value="1" style="width:70px;" required>
                    </td>
                    <td>
                        <input type="number" name="products[${count}][total_price]" class="form-control total-price" style="width: 120px;" readonly>
                    </td>

                    <td class="part-numbers">
                        <div class="part-number-group">
                            <input type="text" name="products[${count}][part_numbers][0]" class="form-control part-number mb-1" required>
                        </div>
                    </td>

                    
                    <td>
                        <button type="button" class="btn btn-danger remove-row">X</button>
                    </td>
                </tr>
            `;

                if ($('#productForm').length === 0) {
                    $('#selProduct').append(staticHTML);
                }
                $('#productTable tbody').append(newRow);
                $(`input[name="products[${count}][model]"]`).val(name + '-' + description);
                $(`input[name="products[${count}][unit_price]"]`).val(price);
                $('#userlist').html('');

                // Calculate total price and update part number fields when quantity changes
                $(document).on('change', '.quantity', function() {
                    const row = $(this).closest('tr');
                    const unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
                    const quantity = parseInt($(this).val()) || 1;
                    const totalPrice = unitPrice * quantity;

                    row.find('.total-price').val(totalPrice.toFixed(2));

                    // Update part number fields based on quantity
                    const partNumbersContainer = row.find('.part-numbers');
                    const currentPartNumberCount = partNumbersContainer.find('.part-number').length;

                    if (quantity > currentPartNumberCount) {
                        // Add additional part number fields
                        for (let i = currentPartNumberCount; i < quantity; i++) {
                            const productIndex = row.index();
                            partNumbersContainer.append(`
                        <div class="part-number-group">
                            <input type="text" name="products[${productIndex}][part_numbers][${i}]" class="form-control part-number mb-1" required>
                        </div>
                    `);
                        }
                    } else if (quantity < currentPartNumberCount) {
                        // Remove excess part number fields
                        partNumbersContainer.find('.part-number-group:gt(' + (quantity - 1) + ')').remove();
                    }
                });


            });
            //    For Table row close
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
            });
        </script>

    </x-app-layout>