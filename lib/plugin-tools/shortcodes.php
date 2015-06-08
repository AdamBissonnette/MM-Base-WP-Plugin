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
    <form name="registration" action="/get-started/" method="post" id="get_started" class="start-form">
        <label for="registration_party_name">Party Name (required)</label>
        <p>    <input type="text" id="registration_party_name" name="registration_party_name" value="<?php echo $party_name; ?>" placeholder="e.g. The Taco Salads"> </p>
        <label for="registration_name">Your Name (required)</label>
        <p>    <input type="text" id="registration_name" name="registration_name" value="" placeholder="e.g. John Smith"> </p>
        <label for="registration_email">Your Email (required)</label>
        <p>    <input type="email" id="registration_email" name="registration_email" value="" placeholder="e.g. J.Smith@lookgo.io"> </p>
        <label for="registration_phone">Your Phone (required)</label>
        <p>    <input type="tel" id="registration_phone" name="registration_phone" value="" placeholder="e.g. +13062649895"> </p>

        <p class="aligncenter"><a class="et_pb_promo_button" href="javascript: void(0)" onclick="get_started.submit();">Save Your Details</a></p>
        </form>
</div>
    <?php

    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

function outputAddPartyMemberForm($atts)
{
       $content = "";
    //Preload registration form values
    if (isset($_REQUEST["party_name"]))
    {
        $party_name = $_REQUEST["party_name"];
    }



ob_start(); //Since there isn't a nice way to get this content we have to use the output buffer

?>

<div class="add-party-member-form">
    <div id="party-form-overlay"></div>

    <h2>Your Party Members</h2>

    <form name="add-party-member" action="/get-started/" method="post" id="add_member" class="start-form">
        <label for="add_party_member_name">Name (required)</label>
        <p>    <input id="add_party_member_name" name="add_party_member_name"  type="text" name="name" value="" placeholder="e.g. John Smith"> </p>
        <label for="add_party_member_email">Email (required)</label>
        <p>    <input id="add_party_member_email" name="add_party_member_email" type="email" name="email" value="" placeholder="e.g. J.Smith@lookgo.io"> </p>
        <label for="add_party_member_phone">Phone (required)</label>
        <p>    <input id="add_party_member_phone" name="add_party_member_phone" type="tel" name="phone" value="" placeholder="e.g. +13062649895"> </p>

        <p class="aligncenter"><a class="et_pb_promo_button" href="javascript: void(0)" onclick="add_member.submit();">Add Party Member</a></p>
        </form>
</div>
    <?php

    $content = ob_get_contents();
    ob_end_clean();

    return $content; 
}

?>