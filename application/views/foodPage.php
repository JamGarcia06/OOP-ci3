<!DOCTYPE html>
<html>

<head>

    <title>Food Management</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Alertify CSS -->
    <link rel="stylesheet"
          href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css">

    <link rel="stylesheet"
          href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css">

</head>


<body class="bg-light">


<!-- NAVBAR -->

<nav class="navbar navbar-dark bg-danger">

    <div class="container">

        <span class="navbar-brand fw-bold">
            Food Reservation System
        </span>

        <a href="<?php echo base_url('index.php/AdminController/adminPage'); ?>"
           class="btn btn-light btn-sm">

            Dashboard

        </a>

    </div>

</nav>



<!-- MAIN CONTENT -->

<div class="container py-5">


    <!-- FLASH MESSAGES -->

    <?php if ($this->session->flashdata('success')): ?>

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <?php echo $this->session->flashdata('success'); ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    <?php endif; ?>


    <?php if ($this->session->flashdata('error')): ?>

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <?php echo $this->session->flashdata('error'); ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    <?php endif; ?>



    <!-- PAGE TITLE -->

    <div class="mb-4">

        <h2 class="text-danger">
            Food Management
        </h2>

        <p class="text-muted">
            Add, edit, and manage the food available in your store.
        </p>

    </div>



    <!-- ========================================= -->
    <!-- ADD FOOD -->
    <!-- ========================================= -->

    <?php if($food == null){ ?>


        <div class="card shadow-sm border-0 mb-5">

            <div class="card-header bg-danger text-white">

                <h5 class="mb-0">
                    Add Food
                </h5>

            </div>


            <div class="card-body">

                <form action="<?php echo base_url('index.php/FoodController/saveData'); ?>"
                      method="post">


                    <!-- FOOD NAME -->

                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Food Name
                        </label>

                        <input type="text"
                               name="food_name"
                               class="form-control"
                               placeholder="Enter food name"
                               value="<?php echo set_value('food_name'); ?>"
                               required>

                        <?php echo form_error(
                            'food_name',
                            '<div class="text-danger mt-1">',
                            '</div>'
                        ); ?>

                    </div>



                    <!-- DESCRIPTION -->

                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Description
                        </label>

                        <textarea name="description"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Enter food description"
                                  required><?php echo set_value('description'); ?></textarea>

                        <?php echo form_error(
                            'description',
                            '<div class="text-danger mt-1">',
                            '</div>'
                        ); ?>

                    </div>



                    <!-- PRICE + QUANTITY -->

                    <div class="row">


                        <!-- PRICE -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-bold">
                                Price
                            </label>

                            <input type="number"
                                   name="price"
                                   step="0.01"
                                   class="form-control"
                                   placeholder="0.00"
                                   value="<?php echo set_value('price'); ?>"
                                   required>

                            <?php echo form_error(
                                'price',
                                '<div class="text-danger mt-1">',
                                '</div>'
                            ); ?>

                        </div>



                        <!-- QUANTITY -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-bold">
                                Quantity
                            </label>

                            <input type="number"
                                   name="quantity"
                                   class="form-control"
                                   placeholder="Enter quantity"
                                   value="<?php echo set_value('quantity'); ?>"
                                   required>

                            <?php echo form_error(
                                'quantity',
                                '<div class="text-danger mt-1">',
                                '</div>'
                            ); ?>

                        </div>


                    </div>



                    <!-- STATUS -->

                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select"
                                required>

                            <option value="Available"
                                <?php echo set_select('status', 'Available'); ?>>

                                Available

                            </option>

                            <option value="Unavailable"
                                <?php echo set_select('status', 'Unavailable'); ?>>

                                Unavailable

                            </option>

                        </select>

                        <?php echo form_error(
                            'status',
                            '<div class="text-danger mt-1">',
                            '</div>'
                        ); ?>

                    </div>



                    <!-- BUTTON -->

                    <button type="submit"
                            class="btn btn-danger">

                        Add Food

                    </button>


                </form>

            </div>

        </div>


    <?php }else{ ?>



    <!-- ========================================= -->
    <!-- EDIT FOOD -->
    <!-- ========================================= -->

        <div class="card shadow-sm border-0 mb-5">


            <?php if(isset($error)){ ?>

                <div class="alert alert-danger m-3">

                    <?php echo $error; ?>

                </div>

            <?php } ?>



            <div class="card-header bg-danger text-white">

                <h5 class="mb-0">
                    Edit Food
                </h5>

            </div>



            <div class="card-body">

                <form action="<?php echo base_url('index.php/FoodController/updateData/'.$food->id); ?>"
                      method="post">


                    <!-- FOOD NAME -->

                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Food Name
                        </label>

                        <input type="text"
                               name="food_name"
                               value="<?php echo set_value('food_name', $food->food_name); ?>"
                               class="form-control"
                               required>

                    </div>



                    <!-- DESCRIPTION -->

                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Description
                        </label>

                        <textarea name="description"
                                  class="form-control"
                                  rows="3"
                                  required><?php echo set_value('description', $food->description); ?></textarea>

                    </div>



                    <!-- PRICE + QUANTITY -->

                    <div class="row">


                        <!-- PRICE -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-bold">
                                Price
                            </label>

                            <input type="number"
                                   name="price"
                                   step="0.01"
                                   value="<?php echo set_value('price', $food->price); ?>"
                                   class="form-control"
                                   required>

                        </div>



                        <!-- QUANTITY -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-bold">
                                Quantity
                            </label>

                            <input type="number"
                                   name="quantity"
                                   value="<?php echo set_value('quantity', $food->quantity); ?>"
                                   class="form-control"
                                   required>

                        </div>


                    </div>



                    <!-- STATUS -->

                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select"
                                required>


                            <option value="Available"
                                <?php echo set_select(
                                    'status',
                                    'Available',
                                    strtolower(trim($food->status)) == 'available'
                                ); ?>>

                                Available

                            </option>


                            <option value="Unavailable"
                                <?php echo set_select(
                                    'status',
                                    'Unavailable',
                                    strtolower(trim($food->status)) == 'unavailable'
                                ); ?>>

                                Unavailable

                            </option>


                        </select>

                    </div>



                    <!-- BUTTONS -->

                    <button type="submit"
                            class="btn btn-danger">

                        Update Food

                    </button>


                    <a href="<?php echo base_url('index.php/FoodController/index'); ?>"
                       class="btn btn-secondary">

                        Cancel

                    </a>


                </form>

            </div>

        </div>


    <?php } ?>



    <!-- ========================================= -->
    <!-- IMPORT FOOD FROM EXCEL -->
    <!-- ========================================= -->

    <div class="card shadow-sm border-0 mb-5">

        <div class="card-header bg-dark text-white">

            <h5 class="mb-0">
                Import Food from Excel
            </h5>

        </div>


        <div class="card-body">

            <form action="<?php echo base_url('index.php/FoodController/importExcel'); ?>"
                  method="post"
                  enctype="multipart/form-data">


                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Excel File
                    </label>

                    <input type="file"
                           name="excel_file"
                           class="form-control"
                           accept=".xlsx,.xls,.csv"
                           required>

                    <div class="form-text">

                        Accepted files: .xlsx, .xls, .csv

                    </div>

                </div>


                <button type="submit"
                        class="btn btn-dark">

                    Import Excel

                </button>


            </form>

        </div>

    </div>



    <!-- ========================================= -->
    <!-- FOOD LIST -->
    <!-- ========================================= -->

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            <h3 class="text-danger mb-1">
                Food List
            </h3>

            <p class="text-muted mb-0">
                Foods currently registered in your store.
            </p>

        </div>

    </div>



    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            <div class="table-responsive">


                <table class="table table-hover table-striped align-middle mb-0">


                    <!-- TABLE HEADER -->

                    <thead class="table-danger">

                        <tr>

                            <th>ID</th>

                            <th>Photo</th>

                            <th>Food Name</th>

                            <th>Description</th>

                            <th>Price</th>

                            <th>Quantity</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>



                    <!-- TABLE BODY -->

                    <tbody>


                    <?php foreach($foods as $foodItem){ ?>


                        <tr>


                            <!-- ID -->

                            <td>

                                <?php echo $foodItem->id; ?>

                            </td>



                            <!-- ================================= -->
                            <!-- FOOD IMAGE -->
                            <!-- ================================= -->

                            <td style="min-width: 180px;">


                                <?php if(!empty($foodItem->image)): ?>


                                    <!-- CURRENT IMAGE -->

                                    <img src="<?php echo base_url('uploads/foods/'.$foodItem->image); ?>"
                                         alt="<?php echo htmlspecialchars($foodItem->food_name); ?>"
                                         width="80"
                                         height="80"
                                         style="object-fit: cover;
                                                border-radius: 8px;
                                                display: block;
                                                margin-bottom: 8px;">


                                <?php else: ?>


                                    <!-- NO IMAGE -->

                                    <div class="text-muted mb-2">

                                        No Image

                                    </div>


                                <?php endif; ?>



                                <!-- IMAGE UPLOAD FORM -->

                                <form action="<?php echo base_url('index.php/FoodController/uploadImage/'.$foodItem->id); ?>"
                                      method="post"
                                      enctype="multipart/form-data">


                                    <input type="file"
                                           name="food_image"
                                           class="form-control form-control-sm mb-2"
                                           accept=".jpg,.jpeg,.png,.webp"
                                           required>


                                    <button type="submit"
                                            class="btn btn-sm btn-danger">


                                        <?php if(!empty($foodItem->image)): ?>

                                            Change Image

                                        <?php else: ?>

                                            Upload Image

                                        <?php endif; ?>


                                    </button>


                                </form>


                            </td>



                            <!-- FOOD NAME -->

                            <td class="fw-bold">

                                <?php echo $foodItem->food_name; ?>

                            </td>



                            <!-- DESCRIPTION -->

                            <td>

                                <?php echo $foodItem->description; ?>

                            </td>



                            <!-- PRICE -->

                            <td class="fw-bold">

                                ₱<?php echo number_format($foodItem->price, 2); ?>

                            </td>



                            <!-- QUANTITY -->

                            <td>

                                <?php echo $foodItem->quantity; ?>

                            </td>



                            <!-- STATUS -->

                            <td>


                                <?php if(strtolower(trim($foodItem->status)) == "available"){ ?>


                                    <span class="badge bg-success">

                                        Available

                                    </span>


                                <?php }else{ ?>


                                    <span class="badge bg-secondary">

                                        Unavailable

                                    </span>


                                <?php } ?>


                            </td>



                            <!-- ACTION -->

                            <td style="min-width: 130px;">


                                <!-- EDIT -->

                                <a href="<?php echo base_url('index.php/FoodController/edit/'.$foodItem->id); ?>"
                                   class="btn btn-warning btn-sm mb-1">

                                    Edit

                                </a>



                                <!-- DELETE -->

                                <a href="<?php echo base_url('index.php/FoodController/delete/'.$foodItem->id); ?>"
                                   class="btn btn-danger btn-sm delete-food mb-1">

                                    Delete

                                </a>


                            </td>


                        </tr>


                    <?php } ?>


                    </tbody>


                </table>


            </div>

        </div>

    </div>


</div>



<!-- ========================================= -->
<!-- BOOTSTRAP JS -->
<!-- ========================================= -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



<!-- ========================================= -->
<!-- ALERTIFY JS -->
<!-- ========================================= -->

<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>



<script>


/* =========================================
   DELETE FOOD CONFIRMATION
   ========================================= */

document.querySelectorAll('.delete-food').forEach(function(button) {


    button.addEventListener('click', function(event) {


        event.preventDefault();


        var deleteUrl = this.href;


        alertify.confirm(

            'Delete Food',

            'Are you sure you want to delete this food?',

            function() {

                window.location.href = deleteUrl;

            },

            function() {

                alertify.message('Deletion cancelled.');

            }

        );


    });


});


</script>


</body>

</html>