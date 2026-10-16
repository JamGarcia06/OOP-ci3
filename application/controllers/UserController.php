<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('email');

        $this->load->model('UserModel');
        $this->load->model('FoodModel');
        $this->load->model('ReservationModel');
    }


    // =========================
    // USER PAGE
    // =========================

    public function index()
    {
        if(!$this->session->userdata('is_user_logged_in'))
        {
            $this->load->view('userLogin');
            return;
        }

        $data['foods'] = $this->FoodModel->getData();
        $data['admin'] = $this->db->get('admin_acc')->row();

        $this->load->view('userPage', $data);
    }


    // =========================
    // LOGIN
    // =========================

  public function login()
{
    // Load form validation library
    $this->load->library('form_validation');


    // =========================
    // VALIDATION RULES
    // =========================

    $this->form_validation->set_rules(
        'email',
        'Email',
        'required|trim|valid_email'
    );

    $this->form_validation->set_rules(
        'password',
        'Password',
        'required|trim'
    );


    // =========================
    // RUN VALIDATION
    // =========================

    if($this->form_validation->run() == FALSE)
    {
        $data['error'] = $this->form_validation->error_string();

        $this->load->view('userLogin', $data);

        return;
    }


    // =========================
    // GET INPUT
    // =========================

    $email = $this->input->post('email', TRUE);
    $password = $this->input->post('password', TRUE);


    // =========================
    // CHECK LOGIN
    // =========================

    $user = $this->UserModel->login($email, $password);


    if($user)
    {
        // =========================
        // CREATE SESSION
        // =========================

        $session_data = [
            'user_id' => $user->id,
            'email' => $user->email,
            'is_user_logged_in' => TRUE
        ];

        $this->session->set_userdata($session_data);


        // Go to user page
        redirect('UserController/index');
    }
    else
    {
        $data['error'] = 'Invalid email or password.';

        $this->load->view('userLogin', $data);
    }
}


    // =========================
    // LOGOUT
    // =========================

    public function logout()
    {
        $this->session->sess_destroy();

        redirect('UserController/login');
    }


    // =========================
    // RESERVE FOOD PAGE
    // =========================

    public function reserve($food_id)
    {
        if(!$this->session->userdata('is_user_logged_in'))
        {
            redirect('UserController/index');
        }

        $data['foods'] = $this->FoodModel->getData();
        $data['food'] = $this->FoodModel->getFood($food_id);
        $data['admin'] = $this->db->get('admin_acc')->row();

        $this->load->view('userPage', $data);
    }


    // =========================
    // SAVE RESERVATION
    // =========================

    public function saveReservation()
    {
        if(!$this->session->userdata('is_user_logged_in'))
        {
            redirect('UserController/index');
        }

        $food_id = $this->input->post('food_id');
        $quantity = $this->input->post('quantity');

        $quantity = (int)$quantity;

        $food = $this->FoodModel->getFood($food_id);

        if(!$food)
        {
            show_error('Food not found.');
            return;
        }

        if($quantity <= 0)
        {
            show_error('Invalid quantity.');
            return;
        }

        if($quantity > $food->quantity)
        {
            $data['available'] = $food->quantity;
            $data['food_name'] = $food->food_name;
            $data['food_id'] = $food_id;

            $this->load->view('reservationFailed', $data);

            return;
        }

        $data = [
            'user_id' => $this->session->userdata('user_id'),
            'food_id' => $food_id,
            'quantity' => $quantity,
            'status' => 'Pending',
            'reservation_time' => date('Y-m-d H:i:s')
        ];

        if($this->ReservationModel->insertData($data))
        {
            redirect('UserController/myReservations');
        }
        else
        {
            show_error('Error saving reservation.');
        }
    }


    // =========================
    // MY RESERVATIONS
    // =========================

    public function myReservations()
    {
        if(!$this->session->userdata('is_user_logged_in'))
        {
            redirect('UserController/index');
        }

        $user_id = $this->session->userdata('user_id');

        $data['reservations'] =
            $this->ReservationModel->getUserReservations($user_id);

        $this->load->view('myReservations', $data);
    }


    // =========================
    // ADD FOOD
    // =========================

    public function saveData()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules(
            'food_name',
            'Food Name',
            'required|trim'
        );

        $this->form_validation->set_rules(
            'description',
            'Description',
            'required|trim'
        );

        $this->form_validation->set_rules(
            'price',
            'Price',
            'required|numeric|greater_than[0]'
        );

        $this->form_validation->set_rules(
            'quantity',
            'Quantity',
            'required|integer|greater_than_equal_to[0]'
        );

        $this->form_validation->set_rules(
            'status',
            'Status',
            'required|in_list[Available,Unavailable]'
        );


        if($this->form_validation->run() == FALSE)
        {
            $data['foods'] = $this->FoodModel->getData();
            $data['food'] = null;
            $data['error'] = validation_errors();

            $this->load->view('foodPage', $data);

            return;
        }


        $data = [
            'food_name' => $this->input->post('food_name'),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price'),
            'quantity' => $this->input->post('quantity'),
            'status' => $this->input->post('status')
        ];


        $response = $this->FoodModel->insertData($data);


        if($response)
        {
            redirect('FoodController/index', 'refresh');
        }
        else
        {
            show_error('Error saving food');
        }
    }


    // =========================
    // UPDATE FOOD
    // =========================

    public function updateData($id)
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules(
            'food_name',
            'Food Name',
            'required|trim'
        );

        $this->form_validation->set_rules(
            'description',
            'Description',
            'required|trim'
        );

        $this->form_validation->set_rules(
            'price',
            'Price',
            'required|numeric|greater_than[0]'
        );

        $this->form_validation->set_rules(
            'quantity',
            'Quantity',
            'required|integer|greater_than_equal_to[0]'
        );

        $this->form_validation->set_rules(
            'status',
            'Status',
            'required|in_list[Available,Unavailable]'
        );


        if($this->form_validation->run() == FALSE)
        {
            $data['foods'] = $this->FoodModel->getData();
            $data['food'] = $this->FoodModel->getFood($id);
            $data['error'] = validation_errors();

            $this->load->view('foodPage', $data);

            return;
        }


        $data = [
            'food_name' => $this->input->post('food_name'),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price'),
            'quantity' => $this->input->post('quantity'),
            'status' => $this->input->post('status')
        ];


        $response = $this->FoodModel->updateData($id, $data);


        if($response)
        {
            redirect('FoodController/index', 'refresh');
        }
        else
        {
            show_error('Error updating food');
        }
    }


    // =========================
    // SEND FOOD PHOTO TO SELLER
    // =========================

    public function sendFoodPhoto()
    {
        // Make sure user is logged in
        if(!$this->session->userdata('is_user_logged_in'))
        {
            redirect('UserController/index');
            return;
        }


        // Get submitted data
        $food_id = $this->input->post('food_id');
        $message = $this->input->post('message', TRUE);


        // Get selected food
        $food = $this->FoodModel->getFood($food_id);


        if(!$food)
        {
            show_error('Food not found.');
            return;
        }


        // Check if photo was uploaded
        if(empty($_FILES['food_photo']['name']))
        {
            show_error('Please select a food photo.');
            return;
        }


        // Upload folder
        $upload_path = FCPATH . 'uploads/food_photos/';


        // Create folder if it does not exist
        if(!is_dir($upload_path))
        {
            mkdir($upload_path, 0777, TRUE);
        }


        // Upload configuration
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
        $config['max_size'] = 5120;
        $config['encrypt_name'] = TRUE;


        $this->load->library('upload', $config);


        // Upload photo
        if(!$this->upload->do_upload('food_photo'))
        {
            show_error($this->upload->display_errors());
            return;
        }


        // Get uploaded file information
        $upload_data = $this->upload->data();

        $file_path = $upload_data['full_path'];


        // ==========================================
        // EMAIL RECIPIENT
        // ==========================================

        // Food photo will ALWAYS be sent here
        $seller_email = 'datadamian06@gmail.com';


        // Get logged-in user's email
        $user_email = $this->session->userdata('email');


        // Clear previous email data
        $this->email->clear(TRUE);


        // Use the SMTP account from email.php
        $smtp_email = $this->config->item('smtp_user');


        $this->email->from(
            $smtp_email,
            'Food Reservation System'
        );


        // Send directly to your Gmail
        $this->email->to($seller_email);


        // Email subject
        $this->email->subject(
            'Food Photo - ' . $food->food_name
        );


        // Email message
        $email_message = '
            <h3>Food Photo Message</h3>

            <p>
                <strong>From:</strong>
                ' . htmlspecialchars($user_email) . '
            </p>

            <p>
                <strong>Food:</strong>
                ' . htmlspecialchars($food->food_name) . '
            </p>

            <p>
                <strong>Message:</strong>
            </p>

            <p>
                ' . nl2br(htmlspecialchars($message)) . '
            </p>

            <hr>

            <p>
                This photo was sent through the Food Reservation System.
            </p>
        ';


        // HTML email
        $this->email->set_mailtype('html');

        $this->email->message($email_message);


        // Attach uploaded photo
        $this->email->attach($file_path);


        // Send email
        if($this->email->send())
        {
            // Delete uploaded file after successful email
            unlink($file_path);


            // Get foods again for user page
            $data['foods'] = $this->FoodModel->getData();

            // Get admin/store information
            $data['admin'] = $this->db->get('admin_acc')->row();

            // Success message
            $data['success'] =
                'Food photo successfully sent to the seller.';


            // Return to user page
            $this->load->view('userPage', $data);
        }
        else
        {
            // Delete uploaded file if email failed
            unlink($file_path);


            // Show email error
            show_error(
                'Email could not be sent.<br><br>' .
                $this->email->print_debugger()
            );
        }
    }

}

?>