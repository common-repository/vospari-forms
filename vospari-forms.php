<?php
/*
Plugin Name: Vospari Forms
Version: 1.8
Description: Генератор форм для TradeSmarter / Заказчик компания Vospari
Plugin URI:
Author: JBlog
Author URI: https://jblog-project.ru/
*/

/*  Copyright 2016  Roman Zharikov  (email : admin@jblog-project.ru)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
register_activation_hook(__FILE__, array('VospariForms', 'activation'));
register_deactivation_hook(__FILE__, array('VospariForms', 'deactivation'));
register_uninstall_hook(__FILE__, array('VospariForms', 'uninstall'));
add_action('plugins_loaded', array('VospariForms', 'instance'));
if (!class_exists('VospariForms')) {
    class VospariForms
    {
        public $plugin_name = '';
        public $plugin_dir = '';
        public $plugin_url = '';
        public $registration_link = '';
        protected static $_instance = null;

        public static function instance()
        {
            if (self::$_instance == null) {
                self::$_instance = new VospariForms();
            }
            return self::$_instance;
        }

        private function __construct()
        {
            $this->plugin_name = plugin_basename(__FILE__);
            $this->plugin_dir = plugin_dir_path(__FILE__);
            $this->plugin_url = plugin_dir_url(__FILE__);
            $this->registration_link = 'https://trading.vospari.com/ru/index/sign-up';
            if (function_exists('add_shortcode')) {
                add_shortcode('vospari_short', array(&$this, 'clientFormShortcodeShort'));
                add_shortcode('vospari_short_x', array(&$this, 'clientFormShortcodeMedium'));
                add_shortcode('vospari_full', array(&$this, 'clientFormShortcodeFull'));
                add_shortcode('vospari_auth', array(&$this, 'clientFormShortcodeAuth'));
            }
            wp_enqueue_script('validation_input', $this->plugin_url . 'assets/js/valid.js', array(), false, true);
            wp_enqueue_style('validation_input_css', $this->plugin_url . 'assets/css/valid.css');
        }

        private function __clone()
        {
        }

        function clientFormShortcodeShort($atts)
        {
            $url = $atts['url'] . '?reg=short';
            $width = $atts['width'];
            $registration_link = $this->registration_link;
            $policy = ($atts['policy'] == 1) ? true : false;
            $practice = ($atts['practice'] == 1) ? true : false;
            $value = ($atts['value']) ? $atts['value'] : "Отправить";
            $partner = $atts['a_aid'];
            ob_start();
            if ($url && $url !== '?reg=short') {
                if ($width && absint($width)) {
                    ?>
                    <style>
                    #vospari_forms_short {
                        width: <?php echo $width;?>px;
                    }
                    </style><?php
                }
                ?>
                <form id="vospari_forms_short" class=""
                      action="<?php echo $registration_link; ?>?redirectUrl=<?php echo $url; ?>&noCountry=0&wow=true"
                      method="post">
                    <input type="hidden" name="shortRegistration" value="1">
                    <input type="hidden" name="practiceMode" value="<?php if($practice) echo 'true';?>" checked="checked" id="short_real_mode">
                    <p><input autocomplete="off" maxlength="60" type="email" name="email" placeholder="E-mail"
                              class="vospari_forms_short_email"/></p>
                    <?php if($policy){
                        ?>
                        <table id="vospari_policy_short" style="border: none;">
                            <tr>
                                <td><input id="vospari_confirmYes_short" checked type="checkbox"></td>
                                <td><span id="vospari_policy_short">Я прочитал и принимаю <a href="https://trading.vospari.com/ru?pro=1#/terms-of-service" target="_blank">Условия использования</a> и <a href="https://trading.vospari.com/ru?pro=1#/privacy-policy" target="_blank">Политику
                                            конфиденциальности</a></span></td>
                            </tr>
                        </table>
                    <?php
                    }?>
                    <p><input type="submit" value="<?php echo $value;?>"/></p>
                </form>
                <?php
                if (isset($_GET['a_aid']) && !isset($partner)) {
                    if($_SERVER['HTTPS'] == 'on') $protocol = 'https'; else $protocol = 'http';
                    echo '<iframe src="'.$protocol.'://trading.vospari.com/ru?a_aid='.preg_replace('/\\\"\>/','',wp_strip_all_tags($_GET['a_aid'])).'" style="display:none;" width="0" height="0"></iframe>';
                }
                if(isset($partner)){
                    if($_SERVER['HTTPS'] == 'on') $protocol = 'https'; else $protocol = 'http';
                    echo '<iframe src="'.$protocol.'://trading.vospari.com/ru?a_aid='.preg_replace('/\\\"\>/','',wp_strip_all_tags($partner)).'" style="display:none;" width="0" height="0"></iframe>';
                }
                ?>
                <p id="vospari_forms_short_mess"></p>
            <?php
            } else {
                ?><p>Не переданы обязательные атрибуты url, для перенаправления</p><?php
            }
            $out = ob_get_clean();
            return $out;
        }

        function clientFormShortcodeMedium($atts)
        {
            $url = $atts['url'] . '?reg=short';
            $width = $atts['width'];
            $registration_link = $this->registration_link;
            $policy = ($atts['policy'] == 1) ? true : false;
            $practice = ($atts['practice'] == 1) ? true : false;
            $value = ($atts['value']) ? $atts['value'] : "Отправить";
            $partner = $atts['a_aid'];
            ob_start();
            if ($url && $url !== '?reg=short') {
                if ($width && absint($width)) {
                    ?>
                    <style>
                    #vospari_forms_medium {
                        width: <?php echo $width;?>px;
                    }
                    </style><?php
                }
                ?>
                <form id="vospari_forms_medium" class=""
                      action="<?php echo $registration_link; ?>?redirectUrl=<?php echo $url; ?>&noCountry=0&wow=true"
                      method="post">
                    <input name="wow" value="true" type="hidden">
                    <input type="hidden" name="shortRegistration" value="1">
                    <input type="hidden" name="practiceMode" value="<?php if($practice) echo 'true';?>" checked="checked" id="medium_real_mode">
                    <span id="vospari_forms_medium_error_name"></span>
                    <p><input autocomplete="off" type="text" id="vospari_firstNameReg_medium" name="firstName" placeholder="Имя" class="vospari_forms_medium_name" maxlength="100" onblur="validateInputM(this);"/></p>
                    <span id="vospari_forms_medium_error_email"></span>
                    <p><input autocomplete="off" type="email" id="vospari_emailReg_medium" name="email" placeholder="E-mail" class="vospari_forms_medium_email" maxlength="70" onblur="validateInputM(this);"/></p>
                    <?php if($policy){
                        ?>
                        <table id="vospari_policy_medium" style="border: none;">
                            <tr>
                                <td><input id="vospari_confirmYes_medium" checked type="checkbox"></td>
                                <td><span id="vospari_policy_medium">Я прочитал и принимаю <a href="https://trading.vospari.com/ru?pro=1#/terms-of-service" target="_blank">Условия использования</a> и <a href="https://trading.vospari.com/ru?pro=1#/privacy-policy" target="_blank">Политику
                                            конфиденциальности</a></span></td>
                            </tr>
                        </table>
                    <?php
                    }?>
                    <p><input type="submit" value="<?php echo $value;?>"/></p>
                </form>
                <?php
                if (isset($_GET['a_aid']) && !isset($partner)) {
                    if($_SERVER['HTTPS'] == 'on') $protocol = 'https'; else $protocol = 'http';
                    echo '<iframe src="'.$protocol.'://trading.vospari.com/ru?a_aid='.preg_replace('/\\\"\>/','',wp_strip_all_tags($_GET['a_aid'])).'" style="display:none;" width="0" height="0"></iframe>';
                }
                if(isset($partner)){
                    if($_SERVER['HTTPS'] == 'on') $protocol = 'https'; else $protocol = 'http';
                    echo '<iframe src="'.$protocol.'://trading.vospari.com/ru?a_aid='.preg_replace('/\\\"\>/','',wp_strip_all_tags($partner)).'" style="display:none;" width="0" height="0"></iframe>';
                }
                ?>
            <?php
            } else {
                ?><p>Не переданы обязательные атрибуты url, для перенаправления</p><?php
            }
            $out = ob_get_clean();
            return $out;
        }

        function clientFormShortcodeFull($atts)
        {
            $url = $atts['url'] . '?reg=full';
            $width = $atts['width'];
            $registration_link = $this->registration_link;
            $policy = ($atts['policy'] == 1) ? true : false;
            $practice = ($atts['practice'] == 1) ? true : false;
            $value = ($atts['value']) ? $atts['value'] : "Отправить";
            $partner = $atts['a_aid'];
            ob_start();
            if ($url && $url !== '?reg=full') {
                if ($width && absint($width)) {
                    ?>
                    <style>
                    #vospari_forms_full {
                        width: <?php echo $width;?>px;
                    }
                    </style><?php
                }
                $a_aid = $_REQUEST['a_aid'];
                if (isset($a_aid) && !isset($partner)) {
                    $a_aid = preg_replace('/\\\"\>/','',wp_strip_all_tags($_GET['a_aid']));
                    $string_sign = 'landing={"a_aid":"' . $a_aid . '", "serial":"camp1"}';
                }
                if(isset($partner)){
                    $a_aid = preg_replace('/\\\"\>/','',wp_strip_all_tags($partner));
                    $string_sign = 'landing={"a_aid":"' . $a_aid . '", "serial":"camp1"}';
                }
                ?>
                <form id="vospari_forms_full" class=""
                      action="<?php echo $registration_link; ?>?redirectUrl=<?php echo $url; ?>&noCountry=0&wow=true"
                      method="post">
                    <input type="hidden" name="landing" id="vospari_landing" value='<?php echo $string_sign; ?>'>
                    <input type="hidden" name="a_aid" id="vospari_a_aid" value="<?php echo $a_aid; ?>">
                    <input type="hidden" name="countryDialCode" id="vospari_country_dial_code" value="+7">
                    <input name="wow" value="true" type="hidden">
                    <input type="hidden" name="practiceMode" value="<?php if($practice) echo 'true';?>" id="full_real_mode">
                    <span id="vospari_forms_full_error_name"></span>
                    <p><input autocomplete="off" type="text" id="vospari_firstNameReg" name="firstName" placeholder="Имя" class="vospari_forms_full_name" maxlength="100" onblur="validateInput(this);"/></p>
                    <span id="vospari_forms_full_error_lastname"></span>
                    <p><input autocomplete="off" type="text" id="vospari_lastNameReg" name="lastName" placeholder="Фамилия" class="vospari_forms_full_lastname" maxlength="100" onblur="validateInput(this);"/></p>
                    <span id="vospari_forms_full_error_password_temp"></span>
                    <p><input autocomplete="off" type="password" id="vospari_passwordTempReg" name="passwordTemp" placeholder="Создать пароль" class="vospari_forms_full_password_temp" maxlength="20" onblur="validateInput(this);"/></p>
                    <input type="hidden" id="vospari_passwordHash" name="password" value=""/>
                    <input type="hidden" id="vospari_passwordHash_confirmation" name="password-confirmation" value=""/>
                    <span id="vospari_forms_full_error_email"></span>
                    <p><input autocomplete="off" type="email" id="vospari_emailReg" name="email" placeholder="E-mail" class="vospari_forms_full_email" maxlength="70" onblur="validateInput(this);"/></p>
                    <p><select name="country" id="vospari_countryReg" onchange="setPhoneAreaCode(this.value)">
                        <option value="af">Afghanistan</option>
                        <option value="al">Albania</option>
                        <option value="dz">Algeria</option>
                        <option value="as">American Samoa</option>
                        <option value="ad">Andorra</option>
                        <option value="ao">Angola</option>
                        <option value="ai">Anguilla</option>
                        <option value="ag">Antigua and Barbuda</option>
                        <option value="ar">Argentina</option>
                        <option value="am">Armenia</option>
                        <option value="aw">Aruba</option>
                        <option value="au">Australia</option>
                        <option value="at">Austria</option>
                        <option value="az">Azerbaijan</option>
                        <option value="bs">Bahamas</option>
                        <option value="bh">Bahrain</option>
                        <option value="bd">Bangladesh</option>
                        <option value="bb">Barbados</option>
                        <option value="by">Belarus</option>
                        <option value="be">Belgium</option>
                        <option value="bz">Belize</option>
                        <option value="bj">Benin</option>
                        <option value="bm">Bermuda</option>
                        <option value="bt">Bhutan</option>
                        <option value="bo">Bolivia</option>
                        <option value="ba">Bosnia and Herzegovina</option>
                        <option value="bw">Botswana</option>
                        <option value="bv">Bouvet Island</option>
                        <option value="br">Brazil</option>
                        <option value="io">British Indian Ocean Territory</option>
                        <option value="bn">Brunei Darussalam</option>
                        <option value="bg">Bulgaria</option>
                        <option value="bf">Burkina Faso</option>
                        <option value="bi">Burundi</option>
                        <option value="kh">Cambodia</option>
                        <option value="cm">Cameroon</option>
                        <option value="ca">Canada</option>
                        <option value="cv">Cape Verde</option>
                        <option value="ky">Cayman Islands</option>
                        <option value="cf">Central African Republic</option>
                        <option value="td">Chad</option>
                        <option value="cl">Chile</option>
                        <option value="cn">China</option>
                        <option value="co">Colombia</option>
                        <option value="km">Comoros</option>
                        <option value="cg">Congo</option>
                        <option value="cd">Congo, The Democratic Republic</option>
                        <option value="ck">Cook Islands</option>
                        <option value="cr">Costa Rica</option>
                        <option value="ci">Cote D'Ivoire</option>
                        <option value="hr">Croatia</option>
                        <option value="cu">Cuba</option>
                        <option value="cy">Cyprus</option>
                        <option value="cz">Czech Republic</option>
                        <option value="dk">Denmark</option>
                        <option value="dj">Djibouti</option>
                        <option value="dm">Dominica</option>
                        <option value="do">Dominican Republic</option>
                        <option value="ec">Ecuador</option>
                        <option value="eg">Egypt</option>
                        <option value="sv">El Salvador</option>
                        <option value="gq">Equatorial Guinea</option>
                        <option value="er">Eritrea</option>
                        <option value="ee">Estonia</option>
                        <option value="et">Ethiopia</option>
                        <option value="fk">Falkland Islands (Malvinas)</option>
                        <option value="fo">Faroe Islands</option>
                        <option value="fj">Fiji</option>
                        <option value="fi">Finland</option>
                        <option value="fr">France</option>
                        <option value="gf">French Guiana</option>
                        <option value="pf">French Polynesia</option>
                        <option value="ga">Gabon</option>
                        <option value="gm">Gambia</option>
                        <option value="ge">Georgia</option>
                        <option value="de">Germany</option>
                        <option value="gh">Ghana</option>
                        <option value="gi">Gibraltar</option>
                        <option value="gr">Greece</option>
                        <option value="gl">Greenland</option>
                        <option value="gd">Grenada</option>
                        <option value="gp">Guadeloupe</option>
                        <option value="gu">Guam</option>
                        <option value="gt">Guatemala</option>
                        <option value="gn">Guinea</option>
                        <option value="gw">Guinea-Bissau</option>
                        <option value="gy">Guyana</option>
                        <option value="ht">Haiti</option>
                        <option value="va">Holy See (Vatican City State)</option>
                        <option value="hn">Honduras</option>
                        <option value="hk">Hong Kong</option>
                        <option value="hu">Hungary</option>
                        <option value="is">Iceland</option>
                        <option value="in">India</option>
                        <option value="id">Indonesia</option>
                        <option value="ir">Iran, Islamic Republic</option>
                        <option value="iq">Iraq</option>
                        <option value="ie">Ireland</option>
                        <option value="il">Israel</option>
                        <option value="it">Italy</option>
                        <option value="jm">Jamaica</option>
                        <option value="jp">Japan</option>
                        <option value="jo">Jordan</option>
                        <option value="kz">Kazakhstan</option>
                        <option value="ke">Kenya</option>
                        <option value="ki">Kiribati</option>
                        <option value="kp">Korea (North)</option>
                        <option value="kr">Korea (South)</option>
                        <option value="kw">Kuwait</option>
                        <option value="kg">Kyrgyzstan</option>
                        <option value="la">Laos</option>
                        <option value="lv">Latvia</option>
                        <option value="lb">Lebanon</option>
                        <option value="ls">Lesotho</option>
                        <option value="lr">Liberia</option>
                        <option value="ly">Libyan Arab Jamahiriya</option>
                        <option value="li">Liechtenstein</option>
                        <option value="lt">Lithuania</option>
                        <option value="lu">Luxembourg</option>
                        <option value="mo">Macao</option>
                        <option value="mk">Macedonia</option>
                        <option value="mg">Madagascar</option>
                        <option value="mw">Malawi</option>
                        <option value="my">Malaysia</option>
                        <option value="mv">Maldives</option>
                        <option value="ml">Mali</option>
                        <option value="mt">Malta</option>
                        <option value="mh">Marshall Islands</option>
                        <option value="mq">Martinique</option>
                        <option value="mr">Mauritania</option>
                        <option value="mu">Mauritius</option>
                        <option value="yt">Mayotte</option>
                        <option value="mx">Mexico</option>
                        <option value="fm">Micronesia</option>
                        <option value="md">Moldova</option>
                        <option value="mc">Monaco</option>
                        <option value="mn">Mongolia</option>
                        <option value="ms">Montserrat</option>
                        <option value="ma">Morocco</option>
                        <option value="mz">Mozambique</option>
                        <option value="mm">Myanmar</option>
                        <option value="na">Namibia</option>
                        <option value="nr">Nauru</option>
                        <option value="np">Nepal</option>
                        <option value="nl">Netherlands</option>
                        <option value="an">Netherlands Antilles</option>
                        <option value="nc">New Caledonia</option>
                        <option value="nz">New Zealand</option>
                        <option value="ni">Nicaragua</option>
                        <option value="ne">Niger</option>
                        <option value="ng">Nigeria</option>
                        <option value="nu">Niue</option>
                        <option value="nf">Norfolk Island</option>
                        <option value="mp">Northern Mariana Islands</option>
                        <option value="no">Norway</option>
                        <option value="om">Oman</option>
                        <option value="pk">Pakistan</option>
                        <option value="pw">Palau</option>
                        <option value="ps">Palestinian Territory</option>
                        <option value="pa">Panama</option>
                        <option value="pg">Papua New Guinea</option>
                        <option value="py">Paraguay</option>
                        <option value="pe">Peru</option>
                        <option value="ph">Philippines</option>
                        <option value="pl">Poland</option>
                        <option value="pt">Portugal</option>
                        <option value="pr">Puerto Rico</option>
                        <option value="qa">Qatar</option>
                        <option value="re">Reunion</option>
                        <option value="ro">Romania</option>
                        <option value="ru" selected>Russian Federation</option>
                        <option value="rw">Rwanda</option>
                        <option value="sh">Saint Helena</option>
                        <option value="kn">Saint Kitts and Nevis</option>
                        <option value="lc">Saint Lucia</option>
                        <option value="pm">Saint Pierre and Miquelon</option>
                        <option value="vc">Saint Vincent and the Grenadines</option>
                        <option value="ws">Samoa</option>
                        <option value="sm">San Marino</option>
                        <option value="st">Sao Tome and Principe</option>
                        <option value="sa">Saudi Arabia</option>
                        <option value="sn">Senegal</option>
                        <option value="cs">Serbia and Montenegro</option>
                        <option value="sc">Seychelles</option>
                        <option value="sl">Sierra Leone</option>
                        <option value="sg">Singapore</option>
                        <option value="sk">Slovakia</option>
                        <option value="si">Slovenia</option>
                        <option value="sb">Solomon Islands</option>
                        <option value="so">Somalia</option>
                        <option value="za">South Africa</option>
                        <option value="gs">South Georgia and the South Sandwich Islands</option>
                        <option value="es">Spain</option>
                        <option value="lk">Sri Lanka</option>
                        <option value="sd">Sudan</option>
                        <option value="sr">Suriname</option>
                        <option value="sz">Swaziland</option>
                        <option value="se">Sweden</option>
                        <option value="ch">Switzerland</option>
                        <option value="sy">Syrian Arab Republic</option>
                        <option value="tw">Taiwan</option>
                        <option value="tj">Tajikistan</option>
                        <option value="tz">Tanzania</option>
                        <option value="th">Thailand</option>
                        <option value="tl">Timor-Leste</option>
                        <option value="tg">Togo</option>
                        <option value="tk">Tokelau</option>
                        <option value="to">Tonga</option>
                        <option value="tt">Trinidad and Tobago</option>
                        <option value="tn">Tunisia</option>
                        <option value="tr">Turkey</option>
                        <option value="tm">Turkmenistan</option>
                        <option value="tc">Turks and Caicos Islands</option>
                        <option value="tv">Tuvalu</option>
                        <option value="ug">Uganda</option>
                        <option value="ua">Ukraine</option>
                        <option value="ae">United Arab Emirates</option>
                        <option value="gb">United Kingdom</option>
                        <option value="us">United States</option>
                        <option value="uy">Uruguay</option>
                        <option value="uz">Uzbekistan</option>
                        <option value="vu">Vanuatu</option>
                        <option value="ve">Venezuela</option>
                        <option value="vn">Vietnam</option>
                        <option value="vg">Virgin Islands, British</option>
                        <option value="vi">Virgin Islands, U.S.</option>
                        <option value="wf">Wallis and Futuna</option>
                        <option value="eh">Western Sahara</option>
                        <option value="ye">Yemen</option>
                        <option value="zm">Zambia</option>
                        <option value="zw">Zimbabwe</option>
                    </select></p>
                    <span id="vospari_forms_full_error_phone"></span>
                    <p style="position: relative;">
                        <span id="vospari_read_only_dial_code_reg">+7</span>
                        <input autocomplete="off" type="text" id="vospari_phoneReg" name="phone" placeholder="Телефон" class="vospari_forms_full_phone" maxlength="15" onblur="validateInput(this);"/>
                    </p>
                    <?php if($policy){
                        ?>
                        <table id="vospari_policy_full" style="border: none;">
                            <tr>
                                <td><input id="vospari_confirmYes_full" checked type="checkbox"></td>
                                <td><span id="vospari_policy_full">Я прочитал и принимаю <a href="https://trading.vospari.com/ru?pro=1#/terms-of-service" target="_blank">Условия использования</a> и <a href="https://trading.vospari.com/ru?pro=1#/privacy-policy" target="_blank">Политику
                                            конфиденциальности</a></span></td>
                            </tr>
                        </table>
                    <?php
                    }?>
                    <p><input type="submit" value="<?php echo $value;?>"/></p>
                </form>
                <?php
                if (isset($_GET['a_aid']) && !isset($partner)) {
                    if($_SERVER['HTTPS'] == 'on') $protocol = 'https'; else $protocol = 'http';
                    echo '<iframe src="'.$protocol.'://trading.vospari.com/ru?a_aid='.$a_aid.'" style="display:none;" width="0" height="0"></iframe>';
                }
                if (isset($partner)) {
                    if($_SERVER['HTTPS'] == 'on') $protocol = 'https'; else $protocol = 'http';
                    echo '<iframe src="'.$protocol.'://trading.vospari.com/ru?a_aid='.$a_aid.'" style="display:none;" width="0" height="0"></iframe>';
                }
                ?>
            <?php
            } else {
                ?><p>Не переданы обязательные атрибуты url, для перенаправления</p><?php
            }
            $out = ob_get_clean();
            return $out;
        }

        function clientFormShortcodeAuth($atts)
        {
            $fb = ($atts['fb'] == 1) ? 1 : 0;
            $url = $atts['url'];
            $width = $atts['width'];
            $height = $atts['height'];
            $lang = $atts['lang'];
            ob_start();
            ?><iframe class="vospari_quick_login" style="width:<?php $w = ($width) ? absint($width).'px' : 'auto'; echo $w;?>;height:<?php $h = ($height) ? absint($height).'px' : 'auto'; echo $h;?>;" src="<?php if($_SERVER['HTTPS'] == 'on') echo 'https'; else echo 'http'?>://widgets.vospari.com<?php echo $lang;?>/widget/quick-login?fb=<?php echo $fb;?>&redirectUrl=<?php echo $url;?>" frameborder="0"></iframe><?php
            $out = ob_get_clean();
            return $out;
        }

        function activation()
        {
            if (!current_user_can('activate_plugins'))
                return;
        }

        function deactivation()
        {
            if (!current_user_can('activate_plugins'))
                return;
            $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
            check_admin_referer("deactivate-plugin_{$plugin}");
            wp_deregister_script('validation_input');
            wp_dequeue_style('validation_input_css');
        }

        function uninstall()
        {
            if (!current_user_can('activate_plugins'))
                return;
            check_admin_referer('bulk-plugins');
            if (__FILE__ != WP_UNINSTALL_PLUGIN)
                return;
            wp_deregister_script('validation_input');
            wp_dequeue_style('validation_input_css');
        }
    }
}