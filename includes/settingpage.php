<div class="pg-setting">
        <?php
        
        $path = plugin_dir_url(  __DIR__ ) . '/img/logo-PayamGostar.png';
        $path = str_replace('\\', '/', $path);
        $infoimg = plugin_dir_url(  __DIR__ ) . '/img/info.svg';
        $infoimg = str_replace('\\', '/', $infoimg);
        ?>
	<img class="pglogo" src= "<?php echo  $path  ?>" >
        <p class="connect-crm">برای ارتباط با CRM پیام‌گستر، اطلاعات حساب کاربری خود را وارد کنید.</h1>
        <form id="pg-form" method="post" action="options.php">
            <?php
            settings_fields('payamgostar_settings_group');
            do_settings_sections('payamgostar_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row" style="white-space: nowrap;">نام کاربری خود را وارد کنید</th>
                    <td><input type="text" name="payamgostar_username" value="<?php echo esc_attr(get_option('payamgostar_username')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">رمز عبور خود را وارد کنید</th>
                    <td><input type="password" name="payamgostar_password" value="<?php echo esc_attr(get_option('payamgostar_password')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">آدرس سرور را وارد کنید</th>
                    <td><input type="text" name="payamgostar_endpoint" value="<?php echo esc_attr(get_option('payamgostar_endpoint')); ?>" /></td>
                </tr>
            </table>
       

        <div class="btnsections">
            <?php submit_button('ذخیره'); ?>
            <div id="connectapiparent" >تست اتصال</div>
            <div class="connectinfo" >برای تست اتصال، ابتدا باید فرم را ذخیره کنید. </div>

        </div>
        </form>
        <p class="ats-template-explain settingpage">
           <img src= "<?php   $infoimg  ?>">
            برای نمایش عناوین شغلی خود، از کد کوتاه <span class="atsshortcode">[atsshowjobs]</span> استفاده نمایید.</p>
       <div  class="">
<?php 
    $usernamevalue = get_option('payamgostar_username');
    $passwordvalue = get_option('payamgostar_password');
    $endpointvalue = get_option('payamgostar_endpoint');
?>
<script>
    let crmConnectionTest = document.getElementById('connectapiparent');
    const fetchingAnimation = '<div class="ats-ellipsis"><div></div><div></div><div></div><div></div></div>';
    document.querySelector('#connectapiparent').addEventListener('click', fetchresult)
    async function fetchresult() {
    crmConnectionTest.innerHTML = fetchingAnimation;
    const pguser = "<?php echo $usernamevalue ?>" ;
    const pgpass = "<?php echo $passwordvalue ?>" ;
    const pgendpoint = "<?php echo $endpointvalue ?>" ;
    const ishttps = pgendpoint.slice(0, 5) == 'https' ? true : false ;
    try {
        const responseInit = await fetch('https://service.payamgostar.com/api/Extention/Schema/Check', {
        method: 'POST',
        headers: {
            'accept': 'text/plain',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            "endPointAddress": `${pgendpoint}`,
            "userName": `${pguser}`,
            "password": `${pgpass}`,
            "isHttps": ishttps
        })
        });

        if (responseInit.ok) {
            const resultInit = await responseInit.json();
            if(resultInit == true){
                crmConnectionTest.innerHTML  = "اتصال به CRM برقرار شد!";
            }
            else{
                crmConnectionTest.innerHTML  = "در حال ایجاد درخواست برای ساخت اسکیما مربوط در CRM" + fetchingAnimation;
                try {
                const responseCheck = await fetch('https://service.payamgostar.com/api/Extention/Init', {
                method: 'POST',
                headers: {
                    'accept': 'text/plain',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    "endPointAddress": `${pgendpoint}`,
                    "userName": `${pguser}`,
                    "password": `${pgpass}`,
                    "isHttps": ishttps
                })
                });
                const resultCheck = await responseCheck.json();

                if (!responseCheck.ok) {
                    crmConnectionTest.innerHTML  = "اسکیما اعمال نشد!"
                }
                else{
                    crmConnectionTest.innerHTML  = "اسکیما ساخته شد."
                }


            } catch (error) {
                console.error('Error:', error);
            }
            }
        }
        else{
            console.log('heh');
            crmConnectionTest.innerHTML  = "اتصال به CRM برقرار نشد!";
        }


    } catch (error) {
        console.error('Error:', error);
        crmConnectionTest.innerHTML  = "اتصال به CRM برقرار نشد!";

    }
    }
</script>

        </div>
    </div>


    
