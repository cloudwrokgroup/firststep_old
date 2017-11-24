<?php


if(isset($_POST['mem_email'])){
    $goto_after_mail = "http://www.1stfirststep.com/new.htm";

    //$email_static ="sarvammail@gmail.com";
    $email_to = "1st.firststep@gmail.com";
    $email_subject = "New Order";

    function died($error) {
        echo json_encode(array('status'=>'error','message'=>$error));
        die();
    }

    if(!isset($_POST['mem_name']) || !isset($_POST['mem_email']) || !isset($_POST['mem_mob']) || !isset($_POST['mem_address']) || !isset($_POST['prod_name'])) {
        died('We are sorry, but there appears to be a problem with the form you submitted.');      

    }

    $first_name = $_POST['mem_name']; // required
    $email_from = $_POST['mem_email']; // required
    $telephone = $_POST['mem_mob']; // not required
    $comments = $_POST['mem_address']; // required
    $prod_name = $_POST['prod_name']; // required
    $qty_val = $_POST['qty_val']; // required
    $price_val = $_POST['price_val']; // not required
    $grandTot = $_POST['grandTot']; // required
    //$email_to=$_POST['mem_email_to']; //required
    $email=$email_from;
    $error_message = "";
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
    if(!preg_match($email_exp,$email_to)) {
        $error_message .= 'The Email Address(To) you entered does not appear to be valid.<br />';
    }
    if(!preg_match($email_exp,$email_from)) {
        $error_message .= 'The Email Address(From) you entered does not appear to be valid.<br />';
    }

    $string_exp = "/^[A-Za-z .'-]+$/";
    if(!preg_match($string_exp,$first_name)) {
        $error_message .= 'The First Name you entered does not appear to be valid.<br />';
    }
    if(strlen($comments) < 2) {
        $error_message .= 'The Comments you entered do not appear to be valid.<br />';
    }
    if(strlen($error_message) > 0) {
        died($error_message);
    }

    $email_message = "Form details below.\n\n";
    function clean_string($string) {
        $bad = array("content-type","bcc:","to:","cc:","href");
        return str_replace($bad,"",$string);
    }

    // prod detail
    $email_message .="<html><head><title>Product  Detail</title></head>";
    $email_message .="<body><table border='1' style='border-collapse:collapse;border: 1px solid #ddd;width:50%'><thead><tr style='background: #4D95D2;color: whitesmoke;'><th>Product Name</th><th>Quantity</th><th>Price</th></tr><thead><tbody>";
    for($i=0 ; $i<count($prod_name); $i++){
        $email_message .="<tr>
                            <td>".$prod_name[$i]."</td>
                            <td>".$qty_val[$i]."(".$price_val[$i].")</td>
                            <td>".$qty_val[$i]*$price_val[$i]."</td>
                        </tr>";
    }
    $email_message .="<tr><td></td><td style='text-align:right;font-weight:bold'>Grand Total</td><td>".$grandTot."</td></tr></tbody></table>";
    $email_message .="<br><br><table border='1' style='border-collapse:collapse;border: 1px solid #ddd;width:50%'>
        <tbody>
        <tr>
        <td>Name</td>
        <td>".clean_string($first_name)."</td>
        </tr>
        <tr>
        <td>Email</td>
        <td>".clean_string($email_from)."</td>
        </tr>
        <tr>
        <td>Telephone</td>
        <td>".clean_string($telephone)."</td>
        </tr>
        <tr>
        <td>Comments</td>
        <td>".clean_string($comments)."</td>
        </tr>
        </tbody>
        </table>
        </body></html><br><br>";
    // here reply-to is removed because  to avoid from and to are the same
    $headers = 'From: '.clean_string($first_name)."\r\n".
        'Reply-to: '.""."\r\n" .    
        'Content-Type: text/html'."\r\n".
        'X-Mailer: PHP v' . phpversion();

    $send=@mail($email_to, $email_subject, $email_message, $headers); 
    if($send){
        echo json_encode(array('status'=>'success','message'=>'Thank you for shopping with us. We will be in touch with you very soon.'));
    }else{
        echo json_encode(array('status'=>'error','message'=>'Unable to Place a Order Please try again later!..'));
    }
}else{
    echo json_encode(array('status'=>'success','message'=>'Unable to prepare a mail'));
}
?>