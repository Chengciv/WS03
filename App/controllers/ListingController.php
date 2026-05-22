<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;
use Framework\Authorization;

use PDO;

class ListingController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);  // ← $this->db not $db
    }

    public function index() {

        $listings = $this->db->query('SELECT * FROM listings ORDER BY created_at DESC')->fetchAll(PDO::FETCH_OBJ);

        loadView('listings/index', ['listings' => $listings]);
    }

    public function create()
    {
        loadView('listings/create');
    }

    public function show($params)
    {
        $id = $params['id'] ?? '';
        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        //Check if listing exists
        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        loadView('listings/show', [
            'listing' => $listing
        ]);
    }

/**
 * Store data in database
 * 
 * @return void
 */
public function store(){

    $allowedFields = ['title', 'description', 'salary', 
    'tags', 'company', 'address', 
    'city', 'state', 'phone', 
    'email', 'requirements', 'benefits'
    ];

    $newListingData = array_intersect_key
    ($_POST, array_flip($allowedFields));

    $newListingData['user_id'] = Session::get
    ('user')['id'];

    $newListingData = array_map('sanitize', 
    $newListingData);

    $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'state'];

    $errors = [];

    foreach($requiredFields as $field) {
        if(empty($newListingData[$field]) || 
        !Validation::string($newListingData
        [$field])) {  // ← removed semicolon
            $errors[] = ucfirst($field) . 
        ' is required';    
        }
    }

    if(!empty($errors)) {
        //Reload view with errors
            loadView('listings/create', [
             'errors' => $errors,
             'listing' => $newListingData
            ]);
    } else  {
            //Submit data
            //echo "Success";

            $fields = [];
            
            foreach($newListingData as $field => $value) {
                $fields[] = $field;
            } 
            $fields = implode(', ', $fields);

            foreach($newListingData as $field => $value) {
                //Convert empty strings to null
                if($value === '') {
                    $newListingData[$field] = null;
                }
                $values[] = ':' . $field;
            }
            $values = implode(', ', $values);

            $query = "INSERT INTO listings 
            ({$fields}) VALUES ({$values})";

            $this->db->query($query, $newListingData);
            
            Session::setFlashMessage('success_message', 'Listing 
            created successfully');

            redirect('/listings');


        }
    }

    /**
     * Delete a listing
     * 
     * @param array $params
     * @return void
     */

    public function destroy($params) {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE 
        id = :id', $params)->fetch();

        //Check if listing exists
        if(!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }


        //Authorization
        if(!Authorization::isOwner($listing->user_id)) {
            Session::setFlashMessage('error_message', 'You are not authorized to delete this listing');
            return redirect('/listings/' . 
            $listing->id);
        }
            
        $this->db->query('DELETE FROM listings WHERE id = :id', 
        $params);

        //Set flash message
        Session::setFlashMessage('success_message', 'Listing deleted successfully');
        redirect('/listings');
    }
    
    public function edit($params)
    {
        $id = $params['id'] ?? '';
        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        //Check if listing exists
        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        loadView('listings/edit', [
            'listing' => $listing
        ]);
    }

    /**
     * Update a listing
     * 
     * @param array $params
     * @return void
     */
    public function update($params)
    {
        $id = $params['id'] ?? '';
        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

        //Check if listing exists
        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        $allowedFields = ['title', 'description', 'salary', 
        'tags', 'company', 'address', 
        'city', 'state', 'phone', 
        'email', 'requirements', 'benefits'
        ];

        $updatedValues = array_intersect_key($_POST, array_flip($allowedFields));

        $updatedValues = array_map('sanitize', $updatedValues);

        $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'state'];

        $errors = [];

        foreach($requiredFields as $field) {
            if(empty($updatedValues[$field]) || 
            !Validation::string($updatedValues[$field])) {
                $errors[] = ucfirst($field) . ' is required';
            }
        }

        if(!empty($errors)) {
            loadView('listings/edit', [
                'errors' => $errors,
                'listing' => $listing
            ]);
        } else {
            $updateFields = [];

            foreach(array_keys($updatedValues) as $field) {
                $updateFields[] = "{$field} = :{$field}";
            }

            $updateFields = implode(', ', $updateFields);

            $updatedValues['id'] = $id;

            $query = "UPDATE listings SET {$updateFields} WHERE id = :id";

            $this->db->query($query, $updatedValues);

            Session::setFlashMessage('success_message', 
            'Listing updated successfully');

            redirect('/listings/' . $id);
        }
    }

}