<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FoodController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('FoodModel');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('url');
    }


    // =========================
    // FOOD PAGE
    // =========================

    public function index()
    {
        if (!$this->session->userdata('is_admin_logged_in')) {
            redirect('AdminController/index');
        }

        $data['foods'] = $this->FoodModel->getData();

        $data['food'] = null;

        $this->load->view('foodPage', $data);
    }


    // =========================
    // ADD FOOD
    // =========================

    public function saveData()
    {
        if (!$this->session->userdata('is_admin_logged_in')) {
            redirect('AdminController/index');
        }

        // FOOD NAME
        $this->form_validation->set_rules(
            'food_name',
            'Food Name',
            'required|trim|min_length[2]|max_length[100]'
        );

        // DESCRIPTION
        $this->form_validation->set_rules(
            'description',
            'Description',
            'required|trim|max_length[255]'
        );

        // PRICE
        $this->form_validation->set_rules(
            'price',
            'Price',
            'required|trim|numeric|greater_than[0]'
        );

        // QUANTITY
        $this->form_validation->set_rules(
            'quantity',
            'Quantity',
            'required|trim|integer|greater_than_equal_to[0]'
        );

        // STATUS
        $this->form_validation->set_rules(
            'status',
            'Status',
            'required|in_list[Available,Unavailable]'
        );


        // =========================
        // VALIDATION FAILED
        // =========================

        if ($this->form_validation->run() == FALSE)
        {
            $data['foods'] = $this->FoodModel->getData();
            $data['food'] = null;

            $this->load->view('foodPage', $data);

            return;
        }


        // =========================
        // VALIDATION PASSED
        // =========================

        $data = array(
            'food_name'   => $this->input->post('food_name', TRUE),
            'description' => $this->input->post('description', TRUE),
            'price'       => $this->input->post('price', TRUE),
            'quantity'    => $this->input->post('quantity', TRUE),
            'status'      => $this->input->post('status', TRUE)
        );

        $this->FoodModel->insertData($data);

        $this->session->set_flashdata(
            'success',
            'Food added successfully!'
        );

        redirect('FoodController/index');
    }


    // =========================
    // EDIT FOOD
    // =========================

    public function edit($id)
    {
        if (!$this->session->userdata('is_admin_logged_in')) {
            redirect('AdminController/index');
        }

        $data['food'] = $this->FoodModel->getFoodById($id);

        if (!$data['food']) {
            show_404();
        }

        $data['foods'] = $this->FoodModel->getData();

        $this->load->view('foodPage', $data);
    }


    // =========================
    // UPDATE FOOD
    // =========================

    public function updateData($id)
    {
        if (!$this->session->userdata('is_admin_logged_in')) {
            redirect('AdminController/index');
        }

        // FOOD NAME
        $this->form_validation->set_rules(
            'food_name',
            'Food Name',
            'required|trim|min_length[2]|max_length[100]'
        );

        // DESCRIPTION
        $this->form_validation->set_rules(
            'description',
            'Description',
            'required|trim|max_length[255]'
        );

        // PRICE
        $this->form_validation->set_rules(
            'price',
            'Price',
            'required|trim|numeric|greater_than[0]'
        );

        // QUANTITY
        $this->form_validation->set_rules(
            'quantity',
            'Quantity',
            'required|trim|integer|greater_than_equal_to[0]'
        );

        // STATUS
        $this->form_validation->set_rules(
            'status',
            'Status',
            'required|in_list[Available,Unavailable]'
        );


        // =========================
        // VALIDATION FAILED
        // =========================

        if ($this->form_validation->run() == FALSE)
        {
            $data['food'] = $this->FoodModel->getFoodById($id);
            $data['foods'] = $this->FoodModel->getData();

            $this->load->view('foodPage', $data);

            return;
        }


        // =========================
        // VALIDATION PASSED
        // =========================

        $data = array(
            'food_name'   => $this->input->post('food_name', TRUE),
            'description' => $this->input->post('description', TRUE),
            'price'       => $this->input->post('price', TRUE),
            'quantity'    => $this->input->post('quantity', TRUE),
            'status'      => $this->input->post('status', TRUE)
        );

        $this->FoodModel->updateFood($id, $data);

        $this->session->set_flashdata(
            'success',
            'Food updated successfully!'
        );

        redirect('FoodController/index');
    }


    // =========================
    // DELETE FOOD
    // =========================

    public function delete($id)
    {
        if (!$this->session->userdata('is_admin_logged_in')) {
            redirect('AdminController/index');
        }

        $food = $this->FoodModel->getFoodById($id);

        if (!$food) {
            show_404();
        }

        // Delete image file if food has an image
        if (!empty($food->image)) {

            $image_path = FCPATH . 'uploads/foods/' . $food->image;

            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        $this->FoodModel->deleteData($id);

        $this->session->set_flashdata(
            'success',
            'Food deleted successfully!'
        );

        redirect('FoodController/index');
    }


    // =========================
    // TEST PHPSPREADSHEET
    // =========================

    public function testExcel()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        echo "PhpSpreadsheet is working!";
    }


    // =========================
    // IMPORT FOOD FROM EXCEL
    // =========================

    public function importExcel()
    {
        // Check if a file was uploaded
        if (
            !isset($_FILES['excel_file']) ||
            $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK
        ) {

            $this->session->set_flashdata(
                'error',
                'Please upload a valid Excel file.'
            );

            redirect('FoodController');

            return;
        }


        $file = $_FILES['excel_file'];


        // Get file extension
        $extension = strtolower(
            pathinfo($file['name'], PATHINFO_EXTENSION)
        );


        // Allowed file types
        $allowed_extensions = array(
            'xlsx',
            'xls',
            'csv'
        );


        if (!in_array($extension, $allowed_extensions)) {

            $this->session->set_flashdata(
                'error',
                'Invalid file type. Only XLSX, XLS, and CSV files are allowed.'
            );

            redirect('FoodController');

            return;
        }


        // Load PhpSpreadsheet
        try {

            $spreadsheet =
                \PhpOffice\PhpSpreadsheet\IOFactory::load(
                    $file['tmp_name']
                );

            $worksheet =
                $spreadsheet->getActiveSheet();

            $rows =
                $worksheet->toArray();

        } catch (Exception $e) {

            $this->session->set_flashdata(
                'error',
                'Unable to read the Excel file.'
            );

            redirect('FoodController');

            return;
        }


        // Check if Excel file is empty
        if (empty($rows)) {

            $this->session->set_flashdata(
                'error',
                'The Excel file is empty.'
            );

            redirect('FoodController');

            return;
        }


        // Expected Excel headers
        $expected_headers = array(
            'food_name',
            'description',
            'price',
            'quantity',
            'status'
        );


        // Get headers
        $headers = array_map(
            'trim',
            $rows[0]
        );


        // Check headers
        if ($headers !== $expected_headers) {

            $this->session->set_flashdata(
                'error',
                'Invalid Excel format. Please use the required column headers.'
            );

            redirect('FoodController');

            return;
        }


        // Remove header row
        unset($rows[0]);


        $valid_rows = array();

        $errors = array();

        $row_number = 2;


        // Validate each Excel row
        foreach ($rows as $row) {


            // Skip completely empty rows
            if (
                empty(trim($row[0] ?? '')) &&
                empty(trim($row[1] ?? '')) &&
                empty(trim($row[2] ?? '')) &&
                empty(trim($row[3] ?? '')) &&
                empty(trim($row[4] ?? ''))
            ) {

                $row_number++;

                continue;
            }


            $food_name =
                trim($row[0] ?? '');

            $description =
                trim($row[1] ?? '');

            $price =
                trim($row[2] ?? '');

            $quantity =
                trim($row[3] ?? '');

            $status =
                trim($row[4] ?? '');


            $row_has_error = false;


            // Food name validation
            if ($food_name === '') {

                $errors[] =
                    "Row {$row_number}: Food name is required.";

                $row_has_error = true;
            }


            // Description validation
            if ($description === '') {

                $errors[] =
                    "Row {$row_number}: Description is required.";

                $row_has_error = true;
            }


            // Price validation
            if (
                $price === '' ||
                !is_numeric($price) ||
                $price < 0
            ) {

                $errors[] =
                    "Row {$row_number}: Price must be a valid number.";

                $row_has_error = true;
            }


            // Quantity validation
            if (
                $quantity === '' ||
                filter_var(
                    $quantity,
                    FILTER_VALIDATE_INT
                ) === false ||
                $quantity < 0
            ) {

                $errors[] =
                    "Row {$row_number}: Quantity must be a whole number.";

                $row_has_error = true;
            }


            // Status validation
            if (
                !in_array(
                    $status,
                    array(
                        'Available',
                        'Unavailable'
                    )
                )
            ) {

                $errors[] =
                    "Row {$row_number}: Status must be Available or Unavailable.";

                $row_has_error = true;
            }


            // Store valid row
            if (!$row_has_error) {

                $valid_rows[] = array(
                    'food_name'   => $food_name,
                    'description' => $description,
                    'price'       => $price,
                    'quantity'    => $quantity,
                    'status'      => $status
                );
            }


            $row_number++;
        }


        // Stop if there are validation errors
        if (!empty($errors)) {

            $this->session->set_flashdata(
                'error',
                implode('<br>', $errors)
            );

            redirect('FoodController');

            return;
        }


        // Stop if there are no valid records
        if (empty($valid_rows)) {

            $this->session->set_flashdata(
                'error',
                'No food records were found in the Excel file.'
            );

            redirect('FoodController');

            return;
        }


        // Start database transaction
        $this->db->trans_start();


        foreach ($valid_rows as $food) {

            $this->db->insert(
                'foods',
                $food
            );
        }


        // Complete transaction
        $this->db->trans_complete();


        // Check transaction status
        if ($this->db->trans_status() === FALSE) {

            $this->session->set_flashdata(
                'error',
                'Failed to import the food records.'
            );

        } else {

            $this->session->set_flashdata(
                'success',
                count($valid_rows) .
                ' food record(s) imported successfully.'
            );
        }


        redirect('FoodController');
    }


    // =========================
    // UPLOAD FOOD IMAGE
    // =========================

    public function uploadImage($id)
    {
        // Check admin login
        if (!$this->session->userdata('is_admin_logged_in')) {

            redirect('AdminController/index');

            return;
        }


        // Get food
        $food = $this->FoodModel->getFoodById($id);


        if (!$food) {

            show_404();

            return;
        }


        // Check uploaded file
        if (
            !isset($_FILES['food_image']) ||
            $_FILES['food_image']['error'] !== UPLOAD_ERR_OK
        ) {

            $this->session->set_flashdata(
                'error',
                'Please select a food image.'
            );

            redirect('FoodController/index');

            return;
        }


        // =========================
        // UPLOAD DIRECTORY
        // =========================

        $upload_path =
            FCPATH . 'uploads/foods/';


        // Create directory if it does not exist
        if (!is_dir($upload_path)) {

            mkdir(
                $upload_path,
                0777,
                true
            );
        }


        // Check directory
        if (!is_dir($upload_path)) {

            $this->session->set_flashdata(
                'error',
                'The food image upload folder does not exist and could not be created.'
            );

            redirect('FoodController/index');

            return;
        }


        // Upload configuration
        $config['upload_path'] =
            $upload_path;

        $config['allowed_types'] =
            'jpg|jpeg|png|webp';

        $config['max_size'] =
            2048;

        $config['encrypt_name'] =
            TRUE;


        // Load upload library
        $this->load->library(
            'upload',
            $config
        );


        // Upload image
        if (!$this->upload->do_upload('food_image')) {

            $this->session->set_flashdata(
                'error',
                $this->upload->display_errors('', '')
            );

            redirect('FoodController/index');

            return;
        }


        // Get uploaded file
        $upload_data =
            $this->upload->data();


        $image_name =
            $upload_data['file_name'];


        // =========================
        // DELETE OLD IMAGE
        // =========================

        if (!empty($food->image)) {

            $old_image =
                FCPATH .
                'uploads/foods/' .
                $food->image;


            if (file_exists($old_image)) {

                unlink($old_image);
            }
        }


        // =========================
        // SAVE IMAGE NAME TO DATABASE
        // =========================

        $this->db->where(
            'id',
            $id
        );


        $this->db->update(
            'foods',
            array(
                'image' => $image_name
            )
        );


        // =========================
        // SUCCESS
        // =========================

        $this->session->set_flashdata(
            'success',
            'Food image uploaded successfully!'
        );


        redirect(
            'FoodController/index'
        );
    }
}