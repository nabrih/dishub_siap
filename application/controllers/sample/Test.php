<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

   class Test extends CI_Controller {
  
      public function index() { 
         // $result = $this->db->select('user');
         echo 'ddd';
         echo base_url().'assets';
         // echo var_dump($result);
      } 

      public function hello() { 
      	// $this->load->model('user_model');
      	// $this->load->view('test');
         echo "This is hello function."; 
      } 

      public function form()
      {
         $this->load->view('test/v_form');
      }

      public function formhidden()
      {
         $hidden = array('username' => 'Joe', 'member_id' => '234');
         echo form_open('email/send', '', $hidden);
         echo form_input('phone', "" );
         echo form_close();
      }

      public function formshow()
      {
         echo form_open('email/send', '');
         $js = 'onClick="some_function()"';

         //name, value, paramLainnya
         echo form_input('username', 'johndoe', $js);
         echo form_textarea('address', 'null', $js);


         // dropdown
         $options = array(
                 'small'         => 'Small Shirt',
                 'med'           => 'Medium Shirt',
                 'large'         => 'Large Shirt',
                 'xlarge'        => 'Extra Large Shirt',
         );

         $shirts_on_sale = array('small', 'large');
         echo form_dropdown('shirts', $options, 'large', '');
         //
         echo form_dropdown('shirts', $options, $shirts_on_sale);
         //
         $js = 'id="shirts3" onChange="some_function();"';
         echo form_dropdown('shirts', $options, 'large', $js);

         echo form_close();
      }

      public function fieldset()
      {
         $attributes = array(
                 'id'    => 'address_info',
                 'class' => 'address_info'
         );

         echo form_fieldset('Address Information', $attributes);
         echo "<p>fieldset content here</p>\n";
         echo form_fieldset_close();
      }

       public function formpost()
      {
         echo form_open('fetch', '');
         echo form_input('phone', "" );
         echo form_button('kirim', 'Kirim data');
         echo form_submit('namasubmit', 'Submit !');
         echo form_close();
      }
   } 
