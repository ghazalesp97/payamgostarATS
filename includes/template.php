<style>
#wpfooter{
  display: none !important;
}
</style>
<div class="template-wrapper">
      <div class="ats-template-checkbox">
      <input type="checkbox" id="atscustomrtemplate" name="atscustomrtemplate" value="1" <?php  checked(get_option('ats-template-checkbox'), 1); ?>>
      <label for="atscustomrtemplate">می‌خواهم برای نمایش شغل‌ها از قالب اختصاصی خود استفاده کنم.</label>
      </div>

      <?php
     $contentlist = get_option('pg_template_html_code', '');
  
    ?>
<p class="ats-template-explain">
برای شخصی‌سازی قالب جزیات آگهی شغلی، از متغیرهای زیر در قالب HTML خود استفاده کنید.
</p>
      <table class="ats-info">
        <tr>
            <th>متغیر</th><th>محتوا</th>
        </tr>
        <tr>
           <td>{jobdescription}</td><td>شرح شغل</td>
        </tr>
        <tr>
          <td>{experience}</td><td>تجارب</td>
        </tr>
        <tr>
          <td>{salary}</td><td>حقوق</td>
        </tr>
        <tr>
         <td>{skills}</td> <td>توانایی ها</td>
        </tr>
        <tr>
         <td>{gender}</td> <td>جنسیت</td>
        </tr>
        <tr>
         <td>{applybutton}</td> <td>ارسال رزومه</td>
        </tr>

      </table>


      <div id="listtem" class="tabcontent">
        <h3>کد HTML اختصاصی خود را اینجا وارد کنید.</h3>
        <div class="list">

          <div class="wrap">        
              <form id="html-tem" method="post" action="<?php echo(esc_url(admin_url('admin-post.php'))) ; ?>">
                  <?php wp_nonce_field('save_html_code', 'pg_template_html_code_nonce'); 
                  wp_editor($contentlist, 'pg_template_html_editor'); ?>
                  <input type="hidden" name="action" value="save_html_code">
                  <?php submit_button('Save HTML Code'); ?>
              </form>
              <div class="description">


              </div>
          </div>


        </div>
      </div>


      </div>

  </div>




