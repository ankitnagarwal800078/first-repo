
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teacher_Data_Show extends CI_Model {

   function getEmployees($postData=null){

     $response = array();

     ## Read value
     $draw = $postData['draw'];
     $start = $postData['start'];
     $rowperpage = $postData['length']; // Rows display per page
     $columnIndex = $postData['order'][0]['column']; // Column index
     $columnName = $postData['columns'][$columnIndex]['data']; // Column name
     $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
     $searchValue = $postData['search']['value']; // Search value

     ## Search 
     $searchQuery = "";
     if($searchValue != ''){
        $searchQuery = " (name like '%".$searchValue."%' or post like '%".$searchValue."%' or salary like'%".$searchValue."%') ";
     }

     ## Total number of records without filtering
     $this->db->select('count(*) as allcount');
     $records = $this->db->get('teachers_data')->result();
     $totalRecords = $records[0]->allcount;

     ## Total number of record with filtering
     $this->db->select('count(*) as allcount');
     if($searchQuery != '')
        $this->db->where($searchQuery);
     $records = $this->db->get('teachers_data')->result();
     $totalRecordwithFilter = $records[0]->allcount;

     ## Fetch records
     $this->db->select('*');
     if($searchQuery != '')
        $this->db->where($searchQuery);
     $this->db->order_by($columnName, $columnSortOrder);
     $this->db->limit($rowperpage, $start);
     $records = $this->db->get('teachers_data')->result();

     $data = array();

     foreach($records as $record ){

        $data[] = array( 
           "name"=>$record->name,
           "post"=>$record->post,
           "salary"=>$record->salary,
           "mobile_number"=>$record->mobile_number,
           "photo"=>"<img style='width:100px;height:100px;' src='http://localhost/Student_Management/photos/$record->photo'>",
           "Edit"=>"<a  class='btn' href='".base_url('Edit_Teacher_Details/index/').$record->id."'> Edit</a>",
           "Delete"=>"<a href='".base_url('Teacher/delete/').$record->id."'>Delete</a>"
        ); 
     }

     ## Response
     $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
     );

     return $response;
   }


}






?>