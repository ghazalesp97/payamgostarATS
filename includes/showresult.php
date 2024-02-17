<?php 
function ishttps($any){
    if ( substr($any, 0, 5) == 'https') {
        return true;
      } else {
        return false;
      }
    
}


function replace_variables($actualcariables){
    $checkbox_value = get_option('ats-template-checkbox');
    if($checkbox_value == '1'){ $templatecodeinput  = get_option('pg_template_html_code');
    }
    else{
        $file=__DIR__ . '/templates/defaultlisttemplate.html'; 
        $templatecodeinput = file_get_contents($file);
    }
    $dollvariavles = ["{jobdescription}","{experience}","{salary}","{skills}","{gender}","{applybutton}"];
    
    $templatecodeoutput = str_replace($dollvariavles, $actualcariables, $templatecodeinput);
    return $templatecodeoutput;
}
function web_publish_val( $extprop , $userKey){
    $value = null;
    foreach ($extprop as $property) {
        $value='';
        if ($property['userKey'] === $userKey) {
            $value = $property['value'];
            break;
        }
    };
    return $value;
}

$wholedata =[];
function send_custom_request_to_api() {

$imgaddress = plugins_url('img', __DIR__ );

    ?>

<div class="apply-modal">
    <div class="apply-modal-content">
    <span class="close-apply-modal"><img src="<?php echo $imgaddress.'/close.svg'; ?>"></span>
        <form action="" method="post" enctype= "multipart/form-data">
        <input name="atsclickedjob" id="clickedjob" type="hidden" value="">
        <input name="atsjobtitle" id="jobtitle" type="hidden" value="">
        <div class="apply-modal-line">
            <div class="modal-input"> 
            <label>نام </label> <input type="text" name="atsname" required><br>
            </div>
            <div class="modal-input">    
            <label> نام خانوادگی </label>  <input type="text" name="atslastname" required><br>
            </div>
        </div>
        <div class="apply-modal-line" style="margin: 30px 0;">

            <div class="modal-input">     
            <label>شماره تماس</label><input type="text" pattern="09[0-9]{9}" name="atsnumber" required><br>
            </div>
            <div class="modal-input">  <label>ایمیل</label><input type="email" name="atsemail" required><br>
            </div>
            </div>
            <div class="">    <label>رزومه</label>    <input name="atsresume" type="file" accept=".pdf" required/>
</div>
      <div class="apply-submit">  <input class="apply-submit-btn" name="atssubmit" type="submit" value="ارسال رزومه"></div>
        </form>
    </div>
</div>
    <?php

    wp_register_script( 'ats_scripts', plugins_url('script/ats_scripts.js', __DIR__ ), array('jquery'));
    wp_enqueue_script( 'ats_scripts' );
    $usernamevalue = get_option('payamgostar_username');
    $passwordvalue = get_option('payamgostar_password');
    $endpointvalue = get_option('payamgostar_endpoint');

    //after submitt
    if (isset($_POST['atssubmit'])) {

    $targetDir = WP_CONTENT_DIR . '/uploads/ats-resumes/';
    // Generate a unique file name
    $fileName = uniqid() . '_' . basename($_FILES['atsresume']['name']);

    $targetFilePath = $targetDir . $fileName;
    if(move_uploaded_file($_FILES['atsresume']['tmp_name'], $targetFilePath)) {
        $fileaddresstosend = $fileaddresstosend = get_site_url().'/wp-content/uploads/ats-resumes/'.$fileName;
    // Set up wp_remote_post
        $resbody = array(
            'endPointAddress' => $endpointvalue,
            'userName' => $usernamevalue,
            'password' => $passwordvalue,
            'isHttps' => ishttps($endpointvalue),
            'email'   => sanitize_email($_POST['atsemail']),
            'phone' => sanitize_text_field($_POST['atsnumber']),
            'fullName'    => sanitize_text_field($_POST['atsname']. ' ' .$_POST['atslastname']),
            'approver'   => '',
            'important'   => true,
            'source'=> 'Website' ,
            'title' => sanitize_text_field($_POST['atsjobtitle']),
            'approverNote'   => '',
            'fileAddress' => $fileaddresstosend,
            'refId'   => sanitize_text_field($_POST['atsnumber']),
            'jobPostId'   => 'Website',
            'auth'   => '',
            'employmentRequestFormId' => sanitize_text_field($_POST['atsclickedjob']),
            'transferToEmploymentRequestFormId' => sanitize_text_field($_POST['atsclickedjob']),
            'totalResumeCount' =>  0,
            'resumeStatus' =>  1,
            'resumeSendDate' => date('Y m d'),
            'firstRejectReason'   => ''

        );
        $resheaders = array(
            'Accept' => 'text/plain',
            'Content-Type' => 'application/json'
        );
        $resargs = array(
            'body'        => wp_json_encode($resbody),
            'timeout'     => 120,
            'redirection' => 5,
            'httpversion' => '1.0',
            'headers'     => $resheaders,
            'cookies'     => array(),
        );
        $response = wp_remote_post('https://service.payamgostar.com/api/Extention/ResumeJobPost/Save', $resargs);
       
    
        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                echo'<div class="ats-alert" >
                        <span class="ats-closebtn" id="atsclosebtn"><img src="'.$imgaddress.'/close-white.svg"></span><img style="margin-left:10px" src="'.$imgaddress.'/check-one.svg">' .$_POST['atsname'] .'
                        عزیز رزومه شما با موفقیت ارسال گردید
                   </div>
                   <script>
                   
                   jQuery(function($) {
                    $(".ats-alert").fadeIn("slow");
                    $(".ats-closebtn").click(function(){
                        $(".ats-alert").fadeOut();
                    })
                        setTimeout(function(){
                            $(".ats-alert").fadeOut();
                        },4000)
                    
                    $(document).on ("click", "#atsclosebtn", function () {
                        $(".ats-alert").fadeOut();
                    });
       
                });

                   </script>
                   ';
                $response_body = wp_remote_retrieve_body($response);
        } else {
            // Request failed or HTTP response code is not 200
                $error_message = is_wp_error($response) ? $response->get_error_message() : 'درخواست شما موفقیت آمیز نبود!';
                echo'<div class="ats-alert ats-failedrequest" >
                <span class="ats-closebtn" id="atsclosebtn"><img src="'.$imgaddress.'/close-white.svg"></span><img style="margin-left:10px" src="'.$imgaddress.'/error.svg">' .$_POST['atsname'] .'
                عزیز ارسال رزومه با موفقیت انجام نشد!
                </div>
                <script>
                
                    jQuery(function($) {
                        $(".ats-alert").fadeIn("slow");
                        $(".ats-closebtn").click(function(){
                            $(".ats-alert").fadeOut();
                        })
                            setTimeout(function(){
                                $(".ats-alert").fadeOut();
                            },4000)
                        
                        $(document).on ("click", "#atsclosebtn", function () {
                            $(".ats-alert").fadeOut();
                        });

                        });

                </script>
                ';

        }

   } else {
        echo "رزومه بارگذاری نشد!";}
   
   //Delete the temporary file when done
   unlink( wp_upload_dir()['basedir'].'/ats-resumes/'. $fileName);
}


    $action = 'https://service.payamgostar.com/api/Extention/EmployementForm/All/Open/web';
    $headers = array(
        'Accept' => 'text/plain',
        'Content-Type' => 'application/json'
    );
    $data = array(
        'endPointAddress' => $endpointvalue,
        'userName' => $usernamevalue,
        'password' => $passwordvalue,
        'isHttps' => ishttps($endpointvalue)
    );
    $body = wp_json_encode($data);

    $response = wp_remote_post($action, array(
        'headers' => $headers,
        'body' => $body,
        'timeout' => 240
    ));
    $output = "";
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
    } else {
        $body = wp_remote_retrieve_body($response);
        $decoded_response = json_decode($body, true);
        //sort by priority
        $jobhighpriority = [];
        $joblowerpriority = [];
        if($decoded_response){
            foreach ($decoded_response as $item) {
                $priorityshow = web_publish_val( $item['extendedProperties'], "EmployRequestWebPublishPriority");
                if ($priorityshow != '') {
                    $jobhighpriority[] = $item;
                } else {
                    $joblowerpriority[] = $item;
                }
            }
        }

        $aftersorting = array_merge($jobhighpriority, $joblowerpriority);
        $response_length = count($aftersorting); 
        $numberofjobs = 0;


        for($job = 0; $job < $response_length; $job++){

            $crmid = $aftersorting[$job]['crmId'];
            $extprop = $aftersorting[$job]['extendedProperties'];
            $subject = web_publish_val( $extprop , "EmployRequestWebPublishTitle");
            $Title ="<span class='ats-title'>".$subject."</span>";            
            $jobdescription ="<p class='ats-title-section'>شرح شغل</p>
            <span class='ats-jobdescription'>". web_publish_val( $extprop , "EmployRequestWebPublishJD")."</span>";
            if(web_publish_val( $extprop , "EmployRequestWebPublishJD") == ""){
                $jobdescription = '';
            }
            $Experience ="<p class='ats-title-section'>حداقل سابقه کاری</p>
            <span class='ats-experience'>". web_publish_val( $extprop , "EmployRequestWebPublishExperience")."</span>";
            if(web_publish_val( $extprop , "EmployRequestWebPublishExperience") == ""){
                $Experience = '';
            }
            $salary ="<span class='ats-salary'>". web_publish_val( $extprop , "EmployRequestWebPublishSalary")."</span>";
            if(web_publish_val( $extprop , "EmployRequestWebPublishSalary") == ""){
                $salary = '<span class="ats-salary">توافقی </span>';
            }
            $Skills =" <p class='ats-title-section'>مهارت ها </p>
            <span class='ats-skills'>". web_publish_val( $extprop , "EmployRequestWebPublishSkills")."</span>";
            if(web_publish_val( $extprop , "EmployRequestWebPublishSkills") == ""){
                $Skills = '';
            }
            $gender ="<span class='ats-gender'>".  web_publish_val( $extprop , "Gender") ."</span>";
            if(web_publish_val( $extprop , "Gender") == ""){
                $gender = '<span class="ats-salary">مهم نیست</span>';
            }
            $applybtn = "<button class='ats-send-resume ats-trigger' data-crmid='$crmid' data-jobtitle='$subject' >ارسال رزومه </button>";
            $showdata = [  $Experience , $jobdescription , $salary , $Skills , $gender , $applybtn];
            $wholedata [$job] = $showdata;
                        $output .=  "<div class='ats-job-container' data-index-number='$job'>
                        <div class='ats-accordion'>$Title <img class='ats-arrow' src='$imgaddress/down-arrow.svg'></div><div class='ats-panel'>"
                         . replace_variables($showdata) .
                         "</div></div>";
                          $numberofjobs ++;
        }
    };

        return '
        <div class="ats-container">
        <span class="title-job-opportunity">موقعیت‌های شغلی</span>
        <span class="active-job-opportunities">'.$numberofjobs.' فرصت شغلی فعال</span>
        <div class="ats-filter-parent"><input type="text" id="ats-filter" onkeyup="filterjob()" placeholder="جستجو..."></div>
        '.$output.'</div>';
}

add_shortcode( 'atsshowjobs', 'send_custom_request_to_api' );
?>
