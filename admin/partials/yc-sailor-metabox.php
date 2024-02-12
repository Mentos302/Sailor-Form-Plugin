<div class="wrap">
<table class="widefat cabin-table services_fields">
    <tbody>
<?php
    foreach ( $order->get_items() as $item_id => $item ) {
        $product = $item->get_product(); 
        $product_id = $product->get_id();
        if (has_term( 'merchandise', 'product_cat', $product_id )) {
                continue;
            }
        $quantity = $item->get_quantity();
        $counter = 1;
        echo "<tr><td>";
        echo "<h1>Product Name : ".$product->get_name()."</h1>";
        echo "</td></tr><tr><td>";
        while ($counter <= $quantity) {
        woocommerce_form_field( 'yc_firstname_'.$product_id.'_pid_qty_'.$counter, array(
            'type'  => 'text',
            'required'  => true, 
            'class' => array('input-text form-row-first'),
            'label' => __('Sailor First Name'),
        ), $order->get_meta( 'yc_firstname_'.$product_id.'_pid_qty_'.$counter ) );

        woocommerce_form_field( 'yc_lastname_'.$product_id.'_pid_qty_'.$counter, array(
            'type'  => 'text',
            'required'  => true, 
            'class' => array('input-text form-row-last'),
            'label' => __('Sailor Last Name'),
        ), $order->get_meta( 'yc_lastname_'.$product_id.'_pid_qty_'.$counter ) );

        woocommerce_form_field( 'yc_dob_'.$product_id.'_pid_qty_'.$counter, array(
            'type'  => 'date',
            'required'  => true, 
            'class' => array('input-text form-row-first'),
            'label' => __('Sailor Date of Birth'),
        ), $order->get_meta( 'yc_dob_'.$product_id.'_pid_qty_'.$counter ) );

        woocommerce_form_field( 'yc_allergy_'.$product_id.'_pid_qty_'.$counter, array(
            'type'  => 'textarea',
            'required'  => true, 
            'class' => array('input-text form-row-last'),
            'label' => __('Sailor Allergies/Medical Conditions'),
        ), $order->get_meta( 'yc_allergy_'.$product_id.'_pid_qty_'.$counter ) );

        woocommerce_form_field( 'yc_guardianfirst_'.$product_id.'_pid_qty_'.$counter, array(
            'type'  => 'text',
            'required'  => true, 
            'class' => array('input-text form-row-first'),
            'label' => __('Parent / Guardian First Name'),
        ), $order->get_meta( 'yc_guardianfirst_'.$product_id.'_pid_qty_'.$counter ) );

        woocommerce_form_field( 'yc_guardianlast_'.$product_id.'_pid_qty_'.$counter, array(
            'type'  => 'text',
            'required'  => true, 
            'class' => array('input-text form-row-last'),
            'label' => __('Parent / Guardian Last Name'),
        ), $order->get_meta( 'yc_guardianlast_'.$product_id.'_pid_qty_'.$counter ) );

        woocommerce_form_field( 'yc_guardiantel_'.$product_id.'_pid_qty_'.$counter, array(
            'type'  => 'tel',
            'required'  => true, 
            'class' => array('input-text form-row-first'),
            'label' => __('Parent / Guardian Contact phone'),
        ), $order->get_meta( 'yc_guardiantel_'.$product_id.'_pid_qty_'.$counter ) );

        woocommerce_form_field( 'yc_guardianmail_'.$product_id.'_pid_qty_'.$counter, array(
            'type'  => 'email',
            'required'  => true, 
            'class' => array('input-text form-row-last'),
            'label' => __('Parent / Guardian Contact email'),
        ), $order->get_meta( 'yc_guardianmail_'.$product_id.'_pid_qty_'.$counter ) );

        woocommerce_form_field( 'yc_permissionvideo_'.$product_id.'_pid_qty_'.$counter, array(
            'type'  => 'radio',
            'required'  => true, 
            'class' => array('input-text form-row-first'),
            'label' => __('Permission to be videoed/ photographed'),
            'options' => array( 'NO' => 'NO','Yes' => 'Yes'),
        ), $order->get_meta( 'yc_permissionvideo_'.$product_id.'_pid_qty_'.$counter ) );

        woocommerce_form_field( 'yc_permissionleave_'.$product_id.'_pid_qty_'.$counter, array(
            'type'  => 'radio',
            'required'  => true, 
            'class' => array('input-text form-row-last'),
            'label' => __('Permission to leave club premises during the day'),
            'options' => array( 'NO' => 'NO','Yes' => 'Yes'),
        ), $order->get_meta( 'yc_permissionleave_'.$product_id.'_pid_qty_'.$counter ) );

        woocommerce_form_field( 'yc_permissionirishdb_'.$product_id.'_pid_qty_'.$counter, array(
            'type'  => 'radio',
            'required'  => true, 
            'class' => array('input-text form-row-first'),
            'label' => __('Permission to add to Irish Sailing database'),
            'options' => array( 'NO' => 'NO','Yes' => 'Yes')
        ), $order->get_meta( 'yc_permissionirishdb_'.$product_id.'_pid_qty_'.$counter ) );
        // if ($order->get_meta( 'yc_clubuse_'.$product_id.'_pid_qty_'.$counter )) {
        // woocommerce_form_field( 'yc_clubuse_'.$product_id.'_pid_qty_'.$counter, array(
        //     'type'  => 'checkbox',
        //     'required'  => true, 
        //     'class' => array('input-text form-row-last'),
        //     'label' => __('I understand that Howth Yacht Club may use the contact information I have provided to send communications relating to the sailing course'),
        // ), $order->get_meta( 'yc_clubuse_'.$product_id.'_pid_qty_'.$counter ) );
        // }
        // if ($order->get_meta( 'yc_liability_'.$product_id.'_pid_qty_'.$counter )) {
        // woocommerce_form_field( 'yc_liability_'.$product_id.'_pid_qty_'.$counter, array(
        //     'type'  => 'checkbox',
        //     'required'  => true, 
        //     'class' => array('input-text form-row-last'),
        //     'label' => __('I understand that no liability is attached to Howth Yacht Club, its members or servants, for any loss or damage to property or for injury sustained by any child enrolled for this tuition.'),
        // ), $order->get_meta( 'yc_liability_'.$product_id.'_pid_qty_'.$counter ) );
        // }
        $counter++;
    }
    echo "</td></tr>";
}   
?>
            </td>
        </tr>
</tbody>
</table>
</div>