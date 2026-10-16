<!DOCTYPE html>
<html>

<head>

    <title>User Page</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Alertify CSS -->
    <link rel="stylesheet"
          href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.css">

    <link rel="stylesheet"
          href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/bootstrap.min.css">

</head>


<body class="bg-light">


<!-- ============================= -->
<!-- NAVBAR -->
<!-- ============================= -->

<nav class="navbar navbar-dark bg-danger">

    <div class="container">

        <span class="navbar-brand fw-bold">
            Food Reservation System
        </span>

        <a href="<?php echo base_url('index.php/UserController/logout'); ?>"
           class="btn btn-light btn-sm">

            Logout

        </a>

    </div>

</nav>



<!-- ============================= -->
<!-- MAIN CONTAINER -->
<!-- ============================= -->

<div class="container py-5">


    <!-- ============================= -->
    <!-- SUCCESS MESSAGE -->
    <!-- ============================= -->

    <?php if(isset($success)){ ?>

        <div class="alert alert-success alert-dismissible fade show">

            <?php echo htmlspecialchars($success); ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    <?php } ?>



    <!-- ============================= -->
    <!-- STORE NAME -->
    <!-- ============================= -->

    <?php if(isset($admin) && $admin != null){ ?>

        <div class="text-center mb-4">

            <h1 class="text-danger fw-bold">
                <?php echo htmlspecialchars($admin->store_name); ?>
            </h1>

            <p class="text-muted">
                Choose from our available food.
            </p>

        </div>

    <?php } ?>



    <!-- ============================= -->
    <!-- SELECTED FOOD / RESERVATION -->
    <!-- ============================= -->

    <?php if(isset($food) && $food != null){ ?>

        <div class="card shadow-sm border-0 mb-5">

            <div class="card-header bg-danger text-white">

                <h4 class="mb-0">
                    Reserve Food
                </h4>

            </div>


            <div class="card-body p-4">


                <!-- FOOD IMAGE -->

                <div class="text-center mb-4">

                    <?php if(!empty($food->image)){ ?>

                        <img src="<?php echo base_url('uploads/foods/'.$food->image); ?>"
                             alt="<?php echo htmlspecialchars($food->food_name); ?>"
                             class="img-fluid"
                             style="
                                width: 220px;
                                height: 220px;
                                object-fit: cover;
                                border-radius: 12px;
                             ">

                    <?php }else{ ?>

                        <div class="d-flex align-items-center justify-content-center bg-light border"
                             style="
                                width: 220px;
                                height: 220px;
                                margin: auto;
                                border-radius: 12px;
                             ">

                            <span class="text-muted">
                                No Image
                            </span>

                        </div>

                    <?php } ?>

                </div>



                <!-- FOOD NAME -->

                <h3 class="text-danger">
                    <?php echo htmlspecialchars($food->food_name); ?>
                </h3>


                <!-- DESCRIPTION -->

                <p class="text-muted">
                    <?php echo htmlspecialchars($food->description); ?>
                </p>


                <!-- PRICE -->

                <p class="fs-5">

                    <strong>Price:</strong>

                    ₱<?php echo number_format($food->price, 2); ?>

                </p>



                <!-- RESERVATION FORM -->

                <form id="reservationForm"
                      action="<?php echo base_url('index.php/UserController/saveReservation'); ?>"
                      method="post">


                    <input type="hidden"
                           name="food_id"
                           value="<?php echo $food->id; ?>">


                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Quantity
                        </label>

                        <input type="number"
                               name="quantity"
                               class="form-control"
                               min="1"
                               max="<?php echo $food->quantity; ?>"
                               required>

                    </div>



                    <!-- ALERTIFY BUTTON -->

                    <button type="button"
                            class="btn btn-danger"
                            onclick="confirmOrder()">

                        Submit Reservation

                    </button>


                    <a href="<?php echo base_url('index.php/UserController/index'); ?>"
                       class="btn btn-secondary">

                        Back to Food List

                    </a>


                </form>


            </div>

        </div>

    <?php } ?>



    <!-- ============================= -->
    <!-- AVAILABLE FOODS -->
    <!-- ============================= -->

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            <h2 class="text-danger mb-1">
                Available Foods
            </h2>

            <p class="text-muted mb-0">
                Select a food to make a reservation.
            </p>

        </div>

    </div>



    <!-- ============================= -->
    <!-- FOOD TABLE -->
    <!-- ============================= -->

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



                            <!-- PHOTO -->

                            <td>

                                <?php if(!empty($foodItem->image)){ ?>

                                    <img src="<?php echo base_url('uploads/foods/'.$foodItem->image); ?>"
                                         alt="<?php echo htmlspecialchars($foodItem->food_name); ?>"
                                         width="80"
                                         height="80"
                                         style="
                                            object-fit: cover;
                                            border-radius: 8px;
                                            display: block;
                                         ">

                                <?php }else{ ?>

                                    <div class="d-flex align-items-center justify-content-center bg-light border"
                                         style="
                                            width: 80px;
                                            height: 80px;
                                            border-radius: 8px;
                                         ">

                                        <small class="text-muted text-center">
                                            No Image
                                        </small>

                                    </div>

                                <?php } ?>

                            </td>



                            <!-- FOOD NAME -->

                            <td class="fw-bold">

                                <?php echo htmlspecialchars($foodItem->food_name); ?>

                            </td>



                            <!-- DESCRIPTION -->

                            <td>

                                <?php echo htmlspecialchars($foodItem->description); ?>

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

                                <?php if(strtolower($foodItem->status) == 'available'){ ?>

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

                            <td>

                                <?php if(
                                    strtolower($foodItem->status) == 'available'
                                    && $foodItem->quantity > 0
                                ){ ?>

                                    <a href="<?php echo base_url('index.php/UserController/reserve/'.$foodItem->id); ?>"
                                       class="btn btn-danger btn-sm">

                                        Reserve

                                    </a>

                                <?php }else{ ?>

                                    <span class="text-muted">
                                        Unavailable
                                    </span>

                                <?php } ?>

                            </td>


                        </tr>


                    <?php } ?>


                    </tbody>

                </table>

            </div>

        </div>

    </div>



    <!-- ============================= -->
    <!-- FOOD PHOTO EMAILER -->
    <!-- ============================= -->

    <div class="card shadow-sm border-0 mt-5">

        <div class="card-header bg-danger text-white">

            <h4 class="mb-0">
                Send Food Photo to Seller
            </h4>

        </div>


        <div class="card-body p-4">

            <p class="text-muted">
                Send a food-related photo directly to the seller with a message.
            </p>


            <form action="<?php echo base_url('index.php/UserController/sendFoodPhoto'); ?>"
                  method="post"
                  enctype="multipart/form-data">


                <!-- SELECT FOOD -->

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Select Food
                    </label>

                    <select name="food_id"
                            class="form-select"
                            required>

                        <option value="">
                            -- Select Food --
                        </option>

                        <?php foreach($foods as $foodItem){ ?>

                            <option value="<?php echo $foodItem->id; ?>">

                                <?php echo htmlspecialchars($foodItem->food_name); ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>



                <!-- PHOTO -->

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Food Photo
                    </label>

                    <input type="file"
                           name="food_photo"
                           class="form-control"
                           accept="image/jpeg,image/png,image/gif,image/webp"
                           required>

                    <small class="text-muted">
                        JPG, JPEG, PNG, GIF or WEBP. Maximum 5 MB.
                    </small>

                </div>



                <!-- MESSAGE -->

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Message
                    </label>

                    <textarea name="message"
                              class="form-control"
                              rows="4"
                              placeholder="Write your question or message..."
                              required></textarea>

                </div>



                <!-- SEND BUTTON -->

                <button type="submit"
                        class="btn btn-danger">

                    Send Photo to Seller

                </button>


            </form>

        </div>

    </div>



    <!-- ============================= -->
    <!-- MY RESERVATIONS -->
    <!-- ============================= -->

    <div class="text-center mt-4">

        <a href="<?php echo base_url('index.php/UserController/myReservations'); ?>"
           class="btn btn-outline-danger">

            My Reservations

        </a>

    </div>


</div>



<!-- ============================= -->
<!-- BOOTSTRAP JS -->
<!-- ============================= -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



<!-- ============================= -->
<!-- ALERTIFY JS -->
<!-- ============================= -->

<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>



<script>

function confirmOrder() {

    var foodName = "<?php echo (isset($food) && $food != null) ? htmlspecialchars($food->food_name, ENT_QUOTES) : ''; ?>";


    alertify.confirm(

        "Confirm Order",

        "Do you want to order <b>" + foodName + "</b>?",


        function() {

            // User clicked OK

            document.getElementById("reservationForm").submit();

        },


        function() {

            // User clicked Cancel

            alertify.error("Order cancelled");

        }

    );

}

</script>


</body>

</html>