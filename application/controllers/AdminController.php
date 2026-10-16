<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminController extends CI_Controller {

    public function __construct(){

        parent::__construct();

        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('AdminModel');
        $this->load->library('email');

    }


    public function index()
    {
        $this->load->view('adminLogin');
    }


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

        $this->load->view('adminLogin', $data);

        return;
    }


    // =========================
    // GET INPUT
    // =========================

    $email = $this->input->post('email', TRUE);
    $password = $this->input->post('password', TRUE);


    // =========================
    // CHECK ADMIN LOGIN
    // =========================

    $admin = $this->AdminModel->login($email, $password);


    if($admin)
    {
        // =========================
        // CREATE ADMIN SESSION
        // =========================

        $session_data = [
            'admin_id' => $admin->id,
            'email' => $admin->email,
            'is_admin_logged_in' => TRUE
        ];

        $this->session->set_userdata($session_data);


        // Go to admin page
        redirect('AdminController/adminPage');
    }
    else
    {
        // Invalid login
        $data['error'] = 'Invalid email or password.';

        $this->load->view('adminLogin', $data);
    }
}


    public function logout()
    {
    // Clear specific admin session keys or destroy the entire session
    $this->session->unset_userdata(['admin_id', 'email', 'is_admin_logged_in']);
    
    // Optional: Destroy the full session
    $this->session->sess_destroy();

    // Set a logout message
    $this->session->set_flashdata('success', 'You have been logged out successfully.');

    // Redirect to the login page
    redirect('AdminController/index');
    }


      public function adminPage()
    {
    if(!$this->session->userdata('is_admin_logged_in')){
        redirect('AdminController/index');
    }

    $admin_id = $this->session->userdata('admin_id');

    $this->db->where('id', $admin_id);
    $data['admin'] = $this->db->get('admin_acc')->row();

    // Get most ordered foods
    $data['foodStats'] = $this->getFoodOrderStats();

    $this->load->view('adminPage', $data);
    }
   

    public function getFoodOrderStats()
    {
    if(!$this->session->userdata('is_admin_logged_in')){
        redirect('AdminController/index');
    }

    $this->db->select('foods.food_name, SUM(reservation.quantity) AS total_orders');
    $this->db->from('reservation');
    $this->db->join('foods', 'foods.id = reservation.food_id');
    $this->db->where('reservation.status !=', 'Rejected');
    $this->db->group_by('reservation.food_id');
    $this->db->order_by('total_orders', 'DESC');

    return $this->db->get()->result();
    }

    public function reservations()
{
    if(!$this->session->userdata('is_admin_logged_in')){
        redirect('AdminController/index');
    }

    $this->load->model('ReservationModel');

    $data['reservations'] = $this->ReservationModel->getAllReservations();

    $this->load->view('adminReservations', $data);
}

public function updateStatus($id, $status)
{
    // Check admin login
    if(!$this->session->userdata('is_admin_logged_in'))
    {
        redirect('AdminController/index');
    }

    // Load models
    $this->load->model('ReservationModel');
    $this->load->model('FoodModel');

    // Get reservation
    $reservation = $this->ReservationModel->getReservationById($id);

    if(!$reservation)
    {
        show_error('Reservation not found.');
    }

    // Approve reservation
    if($status == "Approved")
    {
        $food = $this->FoodModel->getFoodById($reservation->food_id);

        if($food)
        {
            $new_quantity = $food->quantity - $reservation->quantity;

            $this->FoodModel->updateQuantity(
                $reservation->food_id,
                $new_quantity
            );
        }
    }

    // Update reservation status
    $this->ReservationModel->updateStatus($id, $status);


    // ==============================
    // SEND EMAIL
    // ==============================

    $this->load->library('email');

    $this->email->from(
        'datadamian06@gmail.com',
        'Food Reservation System'
    );

    // Send to the actual user's email
    $this->email->to($reservation->email);

    $this->email->subject(
        'Food Reservation - ' . $status
    );

    $message = "
        <h2>Food Reservation Update</h2>

        <p>Hello,</p>

        <p>
            Your food reservation has been
            <strong>{$status}</strong>.
        </p>

        <hr>

        <p>
            <strong>Food:</strong> {$reservation->food_name}
        </p>

        <p>
            <strong>Quantity:</strong> {$reservation->quantity}
        </p>

        <p>
            <strong>Status:</strong> {$status}
        </p>

        <p>
            <strong>Reservation Time:</strong> {$reservation->reservation_time}
        </p>

        <hr>

        <p>
            Thank you for using our Food Reservation System.
        </p>
    ";

    $this->email->set_mailtype('html');

    $this->email->message($message);


    // ==============================
    // SEND EMAIL
    // ==============================

    if($this->email->send())
    {
        $this->session->set_flashdata(
            'success',
            'Reservation updated and email sent successfully!'
        );
    }
    else
    {
        // Show the actual email error
        echo '<pre>';
        echo $this->email->print_debugger();
        echo '</pre>';
        exit;
    }


    // Return to reservations page
    redirect('AdminController/reservations');
}


     public function storeSettings()
    {
        if(!$this->session->userdata('is_admin_logged_in')){
            redirect('AdminController/index');
        }

        $admin_id = $this->session->userdata('admin_id');

        $this->db->where('id', $admin_id);

        $data['admin'] = $this->db->get('admin_acc')->row();

        $this->load->view('storeSettings', $data);
    }

   public function updateStoreName()
{
    $this->load->library('form_validation');

    $this->form_validation->set_rules(
        'store_name',
        'Store Name',
        'required|trim|min_length[2]|max_length[100]'
    );


    if($this->form_validation->run() == FALSE)
    {
        $this->session->set_flashdata(
            'error',
            $this->form_validation->error_string()
        );

        redirect('AdminController/storeSettings');

        return;
    }


    $store_name = $this->input->post('store_name', TRUE);

    $admin_id = $this->session->userdata('admin_id');


    // Update store name
    $this->AdminModel->updateStoreName(
        $admin_id,
        $store_name
    );


    $this->session->set_flashdata(
        'success',
        'Store name updated successfully.'
    );


    redirect('AdminController/storeSettings');
}

}

?>