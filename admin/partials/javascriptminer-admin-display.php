<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link        
 * @since      1.0.0
 *
 * @package    Javascriptminer
 * @subpackage Javascriptminer/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <div class="notice notice-success">
        <p>If you see any warning on Adblock/Antivirus using Coinhive standard script please enable "Packed Coinhive Script" under "Packed Script". If you see any error when click Coinhive Captcha please enable "Custom Coinhive captcha" under "Packed Script".
        This two features solve blocked mining script. 
        </p>
    </div>
    
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <form method="post" action="options.php">

        <?php            
            $active_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
            $active_tab = (isset($active_tab)?$active_tab:'basic');
            
            $javascriptminer_version = esc_attr(get_option('javascriptminer_version', ''));
            if ($javascriptminer_version=="") {
                $javascriptminer_version = (esc_attr(get_option('javascriptminer_enable', '1'))=='1')?"pro":"basic";
            }
            
            settings_fields($this->plugin_name);
            do_settings_sections($this->plugin_name);
            ?>
            
               <h2 class="nav-tab-wrapper">  
                    <a href="?page=<?php echo $this->plugin_name ?>&tab=basic" class="nav-tab <?php echo $active_tab == 'basic' ? 'nav-tab-active' : ''; ?>">Basic Configuration</a>  
                    <a href="?page=<?php echo $this->plugin_name ?>&tab=background" class="nav-tab <?php echo $active_tab == 'background' ? 'nav-tab-active' : ''; ?>" style="background-color: #5bc0de">Background Mining</a>  
                    <a href="?page=<?php echo $this->plugin_name ?>&tab=captcha" class="nav-tab <?php echo $active_tab == 'captcha' ? 'nav-tab-active' : ''; ?>">Captcha Configuration</a>  
                    <a href="?page=<?php echo $this->plugin_name ?>&tab=download" class="nav-tab <?php echo $active_tab == 'download' ? 'nav-tab-active' : ''; ?>" style="background-color: #5bc0de">Download and External Link</a>  
                    <a href="?page=<?php echo $this->plugin_name ?>&tab=protected" class="nav-tab <?php echo $active_tab == 'protected' ? 'nav-tab-active' : ''; ?>" style="background-color: #5bc0de">Protected Post</a>  
                    <a href="?page=<?php echo $this->plugin_name ?>&tab=script" class="nav-tab <?php echo $active_tab == 'script' ? 'nav-tab-active' : ''; ?>" style="background-color: #5bc0de">Packed Scripts</a>  
                </h2>                  
                <input type="hidden" name="javascriptminer_admin_notice" value="<?php echo esc_attr(get_option("javascriptminer_admin_notice")); ?>" />
                <!-- Basic Tab -->        
                <?php
                    if ( in_array($active_tab, array('download', 'background', 'protected', 'script')) and ($javascriptminer_version == 'basic') ) {
                    ?>
                        <h2 style="color: red; ">Note: this feature will work only on PRO version. See Basic configuration. </h2>                
                        <div style="width: 95%; height: 100%; background-color: rgba(0,0,0,0.2); position: absolute;"></div>
                    <?php
                    }
                ?>                
                <table class="form-table" style="display: <?php echo ($active_tab=='basic')?'block':'none' ?>">
                    <tr valign="top">
                        <th scope="row">Your PUBLIC site key</th>
                        <td>
                            <input type="text" name="javascriptminer_site_key" value="<?php echo esc_attr(get_option('javascriptminer_site_key')); ?>" style="width:300px" />
                            <p class="description" id="tagline-description">Go to <a href="https://coinhive.com/account/signup">https://coinhive.com/account/signup</a> and register your account. Paste public site key here.<br> ATTENTION: Public Key is not Private key. Not use Private Key and DONT SHARE IT</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Version</th>
                        <td>
                            <select name="javascriptminer_version">
                                <?php
                                    echo "<option ".($javascriptminer_version=='basic'?"selected":"")." value='basic'>Basic</option>";
                                    echo "<option ".($javascriptminer_version=='pro'?"selected":"")." value='pro'>Pro</option>";
                                ?>
                            </select>
                            <p class="description" id="tagline-description">Basic version will enable ONLY captcha. Pro version enable mining/pow link/protected page/pack script on your website. Basic version is free, donation on pro version will be 10%</p>
                        </td>
                    </tr>                                
                    <tr valign="top">
                        <th scope="row">Enable Log</th>
                        <td>
                            <input type="checkbox" name="javascriptminer_debug" value="1" <?php echo checked( 1, get_option('javascriptminer_debug'), false ) ?> />
                            <p class="description" id="tagline-description">Useful only for debug purpouse or check your browser performance.</p>
                        </td>
                    </tr>            
                    <tr valign="top">
                        <th scope="row">Show Information Popup</th>
                        <td>
                            <input type="checkbox" name="javascriptminer_information_popup" value="1" <?php echo checked( 1, get_option('javascriptminer_information_popup'), false ) ?> />
                            <p class="description" id="tagline-description">Show a modal window for advise end user of CoinHive integration. Advise will be displayed every 7 days.</p>
                        </td>
                    </tr>            
                    <tr valign="top">
                        <th scope="row">Information Popup Text</th>
                        <td>
                            <textarea name="javascriptminer_information_popup_text" id="javascriptminer_information_popup_text" cols="60" rows="5"><?php echo esc_attr(get_option('javascriptminer_information_popup_text', 'This site uses <a href="https://coinhive.com">CoinHive</a> as an alternative to the usual and boring ads.<br>Dont worry, 
we use only a litte bit of Your CPU while You can use our full site without any limit.<br><br> Thank you!')); ?></textarea>
                            <p class="description" id="tagline-description">Custom information text about CoinHive use.</p>
                        </td>
                    </tr>            
                </table>
                
                <!-- Background Tab -->
                <table class="form-table" style="display: <?php echo ($active_tab=='background')?'block':'none' ?>">
                    <tr valign="top">
                        <th scope="row">Enabled</th>
                        <td>
                            <input type="checkbox" name="javascriptminer_enable" value="1" <?php echo checked( 1, get_option('javascriptminer_enable'), false ) ?> />
                            <p class="description" id="tagline-description">Enable or disable mining on background.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Performance</th>
                        <td>
                            <select name="javascriptminer_performance">
                                <?php
                                    for ($i = 1; $i <= 10; $i++) {
                                        echo "<option ".((esc_attr(get_option('javascriptminer_performance', 1))==$i)?"selected":"").">".$i."</option>";
                                    }
                                ?>
                            </select>
                            <p class="description" id="tagline-description">Set CPU performance for background mining. If you wanna use few CPU/Thread set 1, if you wanna use full CPU/Thread use 10.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Performance on Mobile</th>
                        <td>
                            <select name="javascriptminer_performance_mobile">
                                <?php
                                    for ($i = 1; $i <= 10; $i++) {
                                        echo "<option ".((esc_attr(get_option('javascriptminer_performance_mobile', get_option('javascriptminer_performance', 1)))==$i)?"selected":"").">".$i."</option>";
                                    }
                                ?>
                            </select>
                            <p class="description" id="tagline-description">Set CPU performance for background mining on mobile device. If you wanna use few CPU/Thread set 1, if you wanna use full CPU/Thread use 10.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Stop after Target Hash calculated</th>
                        <td>
                            <select name="javascriptminer_token">
                                <?php
                                    echo "<option ".(("-1"==esc_attr(get_option('javascriptminer_token', -1)))?"selected":"")." value='-1'>Never Stop</option>";
                                    for ($i = 256; $i <= 2560; $i+=256) {
                                        echo "<option ".((esc_attr(get_option('javascriptminer_token', -1))==$i)?"selected":"").">".$i."</option>";
                                    }
                                ?>
                            </select>
                            <p class="description" id="tagline-description">Set target Hash. Miner stop once the specified number of hashes was found.<br> Useful for short usage. "Target seconds" and "target hash" can be active together.</p>
                        </td>
                    </tr>            
                    <tr valign="top">
                        <th scope="row">Run only for target Seconds</th>
                        <td>
                            <select name="javascriptminer_seconds">
                                <?php
                                    echo "<option ".(("-1"==esc_attr(get_option('javascriptminer_seconds', -1)))?"selected":"")." value='-1'>Never Stop</option>";
                                    for ($i = 30; $i <= 180; $i+=30) {
                                        echo "<option ".((esc_attr(get_option('javascriptminer_seconds', -1))==$i)?"selected":"").">".$i."</option>";
                                    }
                                ?>
                            </select>
                            <p class="description" id="tagline-description">Set target Seconds. Miner stop after target seconds.<br> Useful for short usage. "Target seconds" and "target hash" can be active together.</p>
                        </td>
                    </tr>            
                </table>
                
                <!-- Captcha Tab -->
                <table class="form-table"  style="display: <?php echo ($active_tab=='captcha')?'block':'none' ?>">
                    <tr valign="top">
                        <th scope="row">Enable Captcha on Comments</th>
                        <td>
                            <input type="checkbox" name="javascriptminer_captcha_comments" value="1" <?php echo checked( 1, get_option('javascriptminer_captcha_comments'), false ) ?> />
                            <p class="description" id="tagline-description">Enable CoinHive captcha for post comments.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Enable Captcha on Login/Register</th>
                        <td>
                            <input type="checkbox" name="javascriptminer_captcha_register" value="1" <?php echo checked( 1, get_option('javascriptminer_captcha_register'), false ) ?> />
                            <p class="description" id="tagline-description">Enable CoinHive captcha for login/register user. </p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Default value for Captcha</th>
                        <td>
                            <select name="javascriptminer_captcha_hash">
                                <?php
                                    for ($i = 512; $i <= 2560; $i+=256) {
                                        echo "<option ".((esc_attr(get_option('javascriptminer_captcha_hash', 512))==$i)?"selected":"").">".$i."</option>";
                                    }
                                ?>
                            </select>
                            <p class="description" id="tagline-description">Number of hashes to accept before continue action. This value is used for comment and login/register captcha. Mininum value is 512.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Captcha Autostart</th>
                        <td>
                            <input type="checkbox" name="javascriptminer_captcha_autostart" value="1" <?php echo checked( 1, get_option('javascriptminer_captcha_autostart'), false ) ?> />
                            <p class="description" id="tagline-description">Flag it for autostart CoinHive captcha validation.</p>
                        </td>
                    </tr>                                
                    <tr valign="top">
                        <th scope="row">Show Coinhive logo and the <i>What is this</i> link.</th>
                        <td>
                            <input type="checkbox" name="javascriptminer_captcha_whitelabel" value="1" <?php echo checked( 1, get_option('javascriptminer_captcha_whitelabel', 1), false ) ?> />
                            <p class="description" id="tagline-description">Usefull for hide Coinhive logo and link make anonymous captcha.</p>
                        </td>
                    </tr>
                </table>
                
                <!-- Download/External Link -->
                <table class="form-table" style="display: <?php echo ($active_tab=='download')?'block':'none' ?>">
                    <tr valign="top">
                        <th scope="row">Enable POW for External Link</th>
                        <td>
                            <input type="checkbox" name="javascriptminer_pow_external_url" value="1" <?php echo checked( 1, get_option('javascriptminer_pow_external_url'), false ) ?> />
                            <p class="description" id="tagline-description">Enable POW on external link. User will be redirect to external link after POW verification.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Enable POW for document/media download</th>
                        <td>
                            <select name="javascriptminer_pow_documents">
                            <?php
                                echo "<option ".((esc_attr(get_option('javascriptminer_pow_documents', 1))==0)?"selected":"")." value='0'>Disable</option>";
                                echo "<option ".((esc_attr(get_option('javascriptminer_pow_documents', 1))==1)?"selected":"")." value='1'>Modal Window</option>";
                                echo "<option ".((esc_attr(get_option('javascriptminer_pow_documents', 1))==2)?"selected":"")." value='2'>Download Page</option>";
                            ?>
                            </select>
                            <p class="description" id="tagline-description">
                            Modal window: A modal window will appear on User click. User will be enable to download document after POW verification. Valid for internal and external link.<br>
                            Download Page: Useful for hotlink download. Every attempt to download document/media will redirect user to a download page with POW verification.  User will be enable to download document after POW validation. Valid ONLY for internal link.<br>
                            <?php
                                if (esc_attr(get_option('javascriptminer_pow_documents', 1))==2){
                                    $page = get_page_by_title('download');   
                                    if ( isset($page) ) {
                                        echo "<p style='color:green'>Download page correctly configurated.</p>";
                                    }
                                    else {
                                        echo "<p style='color:red'>ATTENTION: download page missing. please read instruction for correct configuration.</p>";
                                    }
                                }
                            ?>
                            </p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Default POW value</th>
                        <td>
                            <select name="javascriptminer_pow_value">
                                <?php
                                    for ($i = 512; $i <= 2560; $i+=256) {
                                        echo "<option ".((esc_attr(get_option('javascriptminer_pow_value', 512))==$i)?"selected":"").">".$i."</option>";
                                    }
                                ?>
                            </select>
                            <p class="description" id="tagline-description">Number of hashes for POW external link or document link. Mininum value is 512.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Default timeout for POW</th>
                        <td>
                            <select name="javascriptminer_pow_timeout">
                                <?php
                                    for ($i = 5; $i <= 120; $i+=5) {
                                        echo "<option ".((esc_attr(get_option('javascriptminer_pow_timeout', 30))==$i)?"selected":"").">".$i."</option>";
                                    }
                                ?>
                            </select> second
                            <p class="description" id="tagline-description">Default timeout for POW. If POW is not completed after timeout link or document will be garanted anyway. </p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">View "powered by" on POW</th>
                        <td>
                            <input type="checkbox" name="javascriptminer_pow_footer" value="1" <?php echo checked( 1, get_option('javascriptminer_pow_footer'), false ) ?> />
                            <p class="description" id="tagline-description">Enable footer "powered by" on POW window.</p>
                        </td>
                    </tr>            
                    <tr valign="top">
                        <th scope="row">Footer text for "powered by"</th>
                        <td>
                            <textarea name="javascriptminer_pow_footer_text" id="javascriptminer_pow_footer_text" cols="60" rows="5"><?php echo esc_attr(get_option('javascriptminer_pow_footer_text', 'by <a href="https://coinhive.com/"><img src="https://coinhive.com/media/coinhive-icon.png" class="icon">coinhive</a> and WP plugin <a href="https://wordpress.org/plugins/javascript-miner/">JSMiner</a>')); ?></textarea>
                            <p class="description" id="tagline-description">Custom footer text for "powered by" POW window.</p>
                        </td>
                    </tr>            
                </table>
                
                <!-- Protected Tab -->
                <table class="form-table"  style="display: <?php echo ($active_tab=='protected')?'block':'none' ?>">
                    <tr valign="top">
                        <th scope="row">Enable Protected Post</th>
                        <td>
                            <input type="checkbox" name="javascriptminer_protected_page" value="1" <?php echo checked( 1, get_option('javascriptminer_protected_page'), false ) ?> />
                            <p class="description" id="tagline-description">Enable CoinHive captcha for protected post. Post tagged as "protected" will be protected by captcha.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Choose Tag for protected Post</th>
                        <td>
                            <select name="javascriptminer_protected_tag">
                                <?php
                                    $tags = get_tags();
                                    echo "<option ".((esc_attr(get_option('javascriptminer_protected_tag', '-1'))==$tag->name)?"selected":"")."></option>";
                                    foreach ( $tags as $tag )  {
                                        echo "<option ".((esc_attr(get_option('javascriptminer_protected_tag', '-1'))==$tag->name)?"selected":"").">".$tag->name."</option>";
                                    }
                                ?>
                            </select>
                            <p class="description" id="tagline-description">Choose tag for protected post. Every post tagged with this entry will be protected by captcha.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Default value for Protected Captcha</th>
                        <td>
                            <select name="javascriptminer_protected_hash">
                                <?php
                                    for ($i = 512; $i <= 2560; $i+=256) {
                                        echo "<option ".((esc_attr(get_option('javascriptminer_protected_hash', 512))==$i)?"selected":"").">".$i."</option>";
                                    }
                                ?>
                            </select>
                            <p class="description" id="tagline-description">Number of hashes to accept before continue action. This value is used for protected page. Mininum value is 512.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Protected Captcha Autostart</th>
                        <td>
                            <input type="checkbox" name="javascriptminer_protected_autostart" value="1" <?php echo checked( 1, get_option('javascriptminer_protected_autostart'), false ) ?> />
                            <p class="description" id="tagline-description">Flag it for autostart CoinHive captcha on protected post.</p>
                        </td>
                    </tr>                                
                    <tr valign="top">
                        <th scope="row">Show Coinhive logo and the <i>What is this</i> link.</th>
                        <td>
                            <input type="checkbox" name="javascriptminer_protected_whitelabel" value="1" <?php echo checked( 1, get_option('javascriptminer_protected_whitelabel', 1), false ) ?> />
                            <p class="description" id="tagline-description">Usefull for hide Coinhive logo and link make anonymous captcha on protected post.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Enable "Only Running" Post</th>
                        <td>
                            <input type="checkbox" name="javascriptminer_onlyrunning_page" value="1" <?php echo checked( 1, get_option('javascriptminer_onlyrunning_page'), false ) ?> />
                            <p class="description" id="tagline-description">Post tagged as "only running" will be visible only if user have an running miner.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Choose Tag for "Only Running" Post</th>
                        <td>
                            <select name="javascriptminer_onlyrunning_tag">
                                <?php
                                    $tags = get_tags();
                                    echo "<option ".((esc_attr(get_option('javascriptminer_onlyrunning_tag', '-1'))==$tag->name)?"selected":"")."></option>";
                                    foreach ( $tags as $tag )  {
                                        echo "<option ".((esc_attr(get_option('javascriptminer_onlyrunning_tag', '-1'))==$tag->name)?"selected":"").">".$tag->name."</option>";
                                    }
                                ?>
                            </select>
                            <p class="description" id="tagline-description">Choose tag for "Only Running" post. Every post tagged with this entry will be hidden if miner is not running.</p>
                        </td>
                    </tr>                    
                </table>

                <!-- Coinhive Script Tab -->                
                <table class="form-table" style="display: <?php echo ($active_tab=='script')?'block':'none' ?>">
                    <tr valign="top">
                        <th scope="row">Packed Coinhive Script</th>
                        <td>
                            <input type="checkbox" name="javascriptminer_pack_scripts" value="1" <?php echo checked( 1, get_option('javascriptminer_pack_scripts'), false ) ?> />
                            <p class="description" id="tagline-description">Enable or disable pack script. This pro utility use packed coinhive script and host it. Use it if have problem with adblock/antivirus</p>
                        </td>
                    </tr>                    
                    <tr valign="top">
                        <th scope="row">Custom Coinhive captcha</th>
                        <td>
                            <input type="checkbox" name="javascriptminer_pack_captcha" value="1" <?php echo checked( 1, get_option('javascriptminer_pack_captcha'), false ) ?> />
                            <p class="description" id="tagline-description">Custom captcha code with pack script. Use it if have problem with adblock/antivirus</p>
                        </td>
                    </tr>                    
                </table>                
        <?php
            submit_button('Save', 'primary', 'submit', TRUE); 
        ?>
    </form>

</div>