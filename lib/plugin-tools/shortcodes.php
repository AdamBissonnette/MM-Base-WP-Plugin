<?php
namespace MmmPluginToolsNamespace;

function getEntity($atts) {

    extract( shortcode_atts( array(
        'entity' => '',
        'id' => ''
        ), $atts ) );

    global $MMM_Curl_Manager;

    $curly = $MMM_Curl_Manager->curlHandler;

    $data = array("fn" => "GET", "entityName" => $entity, "id" => $id);

    $json = json_decode($curly->DoCurl($data));

    return _output_json($json);
}

function outputRegistrationForm($atts)
{
    $content = "";
    $party_name = "";
    //Preload registration form values
    if (isset($_REQUEST["party_name"]))
    {
        $party_name = $_REQUEST["party_name"];
    }



ob_start(); //Since there isn't a nice way to get this content we have to use the output buffer

?>

<div class="registration-forms">
<h3>Your Details</h3>

    <form data-toggle="validator" name="registration" action="/get-started/" method="post" id="get_started" class="start-form">

<div class="form-group">
    <label for="inputPartyName" class="control-label">Party Name</label>
    <div class="controls"><input type="text" class="form-control" id="inputPartyName" name="registration_party_name" value="<?php echo $party_name; ?>" placeholder="e.g. Psychic Socks" required></div>
    <div class="help-block with-errors"><ul class="list-unstyled"><li>*Required</li></ul></div>
</div>

<div class="form-group">
    <label for="inputName" class="control-label">Your Name</label>
    <div class="controls"><input type="text" class="form-control" id="inputName" name="registration_name" placeholder="e.g. John Smith" required></div>
    <div class="help-block with-errors"><ul class="list-unstyled"><li>*Required</li></ul></div>
</div>

<div class="form-group">
    <label for="inputEmail" class="control-label">Your Email</label>
    <div class="controls"><input type="email" class="form-control" id="inputEmail" name="registration_email" placeholder="e.g. JSmith@internet.com" required></div>
    <div class="help-block with-errors"><ul class="list-unstyled"><li>*Required</li></ul></div>
</div>

<div class="form-group">
    <label for="inputPhone" class="control-label">Your Phone</label>
    <div class="controls"><input type="text" pattern="\+?1?\W*([2-9][0-8][0-9])\W*([2-9][0-9]{2})\W*([0-9]{4})(\se?x?t?(\d*))?" class="form-control" id="inputPhone" name="registration_phone" placeholder="e.g. +13069921212" required></div>
    <div class="help-block with-errors"><ul class="list-unstyled"><li>*Required - enter like this: +13069921212</li></ul></div>
</div>

<div class="form-group">
    <a class="et_pb_promo_button" href="javascript: void(0);" id="get_started_btn">Save Your Details</a>
</div>
        </form>
</div>
    <?php

    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

function outputAddPartyMemberForm($atts)
{

    global $MMM_Curl_Manager;

    wp_enqueue_style('app_styles', $MMM_Curl_Manager->location_folder . '/assets/css/app.css', false, null);

    wp_enqueue_style('MCM_bootstrap_css', $MMM_Curl_Manager->location_folder . '/assets/css/bootstrap.css', false, null);
    wp_enqueue_script('MCM_jquery_js', $MMM_Curl_Manager->location_folder . '/assets/js/jquery-1.9.1.min.js', false, null);        
    wp_enqueue_script('MCM_bootstrap_js', $MMM_Curl_Manager->location_folder . '/assets/js/plugins.js', false, null);
    wp_enqueue_script('MCM_formtools_js', $MMM_Curl_Manager->location_folder . '/assets/js/formtools.js', false, null);


    $content = "";



ob_start(); //Since there isn't a nice way to get this content we have to use the output buffer

?>

<div class="add-party-member-form">
    <!-- <div id="party-form-overlay"></div> -->

    <h3>Your Party Members</h3>

    <form data-toggle="validator" name="add-party-member" action="/get-started/" method="post" id="add_party_member" class="start-form">
        <div class="form-group">
            <label for="inputAPName" class="control-label">Name</label>
            <div class="controls"><input type="text" class="form-control" id="inputAPName" name="add_party_member_name" placeholder="e.g. John Smith" required></div>
            <div class="help-block with-errors"><ul class="list-unstyled"><li>*Required</li></ul></div>
        </div>

        <div class="form-group">
            <label for="inputAPEmail" class="control-label">Email</label>
            <div class="controls"><input type="email" class="form-control" id="inputAPEmail" name="add_party_member_email" placeholder="e.g. JSmith@internet.com" required></div>
            <div class="help-block with-errors"><ul class="list-unstyled"><li>*Required</li></ul></div>
        </div>

        <div class="form-group">
            <label for="inputAPPhone" class="control-label">Phone</label>
            <div class="controls"><input type="text" pattern="1?\W*([2-9][0-8][0-9])\W*([2-9][0-9]{2})\W*([0-9]{4})(\se?x?t?(\d*))?" class="form-control" id="inputAPPhone" name="add_party_member_phone" placeholder="e.g. +13069921212" required></div>
            <div class="help-block with-errors"><ul class="list-unstyled"><li>*Required - Enter like this: +13069921212</li></ul></div>
        </div>

        <div class="form-group">
            <a class="et_pb_promo_button" href="javascript: void(0);" id="add_party_member_btn">Add Party Member</a>
        </div>

        </form>
</div>

    <?php

    $content = ob_get_contents();
    ob_end_clean();

    return $content; 
}

?>