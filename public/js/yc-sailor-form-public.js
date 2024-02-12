jQuery(document).ready(function($){
  $('.wpmc-step-item.yc_sailors_form input[type="text"]').on('blur',function(){
    if($(this).val() != ''){
      $(this).closest('p.form-row').removeClass("woocommerce-invalid-required-field");
      $(this).closest('p.form-row').removeClass("woocommerce-invalid");
      $(this).closest('p.form-row').addClass("woocommerce-validated");
    }
  });
  $('.wpmc-step-item.yc_sailors_form input[type="date"]').on('blur',function(){
    if($(this).val() != ''){
      $(this).closest('p.form-row').removeClass("woocommerce-invalid-required-field");
      $(this).closest('p.form-row').removeClass("woocommerce-invalid");
      $(this).closest('p.form-row').addClass("woocommerce-validated");
    }
  });
  $('.wpmc-step-item.yc_sailors_form input[type="textarea"]').on('blur',function(){
    if($(this).val() != ''){
      $(this).closest('p.form-row').removeClass("woocommerce-invalid-required-field");
      $(this).closest('p.form-row').removeClass("woocommerce-invalid");
      $(this).closest('p.form-row').addClass("woocommerce-validated");
    }
  });
  $('.wpmc-step-item.yc_sailors_form input[type="checkbox"]').change(function() {
    if(this.checked) {
      console.log('blue moon!');
      $(this).closest('p.form-row').removeClass("woocommerce-invalid-required-field");
      $(this).closest('p.form-row').removeClass("woocommerce-invalid");
      $(this).closest('p.form-row').removeClass("validate-required");
      $(this).closest('p.form-row').addClass("woocommerce-validated");
    } else {
      $(this).closest('p.form-row').addClass("woocommerce-invalid-required-field");
      $(this).closest('p.form-row').addClass("woocommerce-invalid");
      $(this).closest('p.form-row').addClass("validate-required");
      $(this).closest('p.form-row').removeClass("woocommerce-validated");
    }
  });
  $('.wpmc-step-item.yc_sailors_form input[type="radio"]').change(function() {
    if(this.checked) {
      console.log('blue moon!');
      $(this).closest('p.form-row').removeClass("woocommerce-invalid-required-field");
      $(this).closest('p.form-row').removeClass("woocommerce-invalid");
      $(this).closest('p.form-row').removeClass("validate-required");
      $(this).closest('p.form-row').addClass("woocommerce-validated");
    } else {
      $(this).closest('p.form-row').addClass("woocommerce-invalid-required-field");
      $(this).closest('p.form-row').addClass("woocommerce-invalid");
      $(this).closest('p.form-row').addClass("validate-required");
      $(this).closest('p.form-row').removeClass("woocommerce-validated");
    }
  });

  $('#wpmc-next').on('click',function(){
    if ($('.yc_allergy_textbox').length > 0  && $('.yc_allergy_textbox').is(":visible")) {
      var textbox = $('.yc_allergy_textbox').find('textarea');
      $(textbox).each(function(){
        if( $(this).val() == '' ){
          $(this).closest('p.form-row').addClass("woocommerce-invalid-required-field");
          $(this).closest('p.form-row').addClass("woocommerce-invalid");
          $(this).closest('p.form-row').addClass("validate-required");
          $(this).closest('p.form-row').removeClass("woocommerce-validated");
        }
      });
    }
  });

});